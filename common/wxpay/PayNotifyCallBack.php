<?php 
namespace common\wxpay;

use common\wxpay\Lib\WxPayNotify;
use common\wxpay\Lib\WxPayOrderQuery;
use common\wxpay\Lib\WxPayApi;
use backend\models\TOrder;
use backend\models\TUserRoomCard;
use common\models\UserInfo;
use common\models\TAgent;
use \backend\models\TManagerRoomCardLog;
use common\models\TAgentRebateLog;
use Yii;

class PayNotifyCallBack extends WxPayNotify{
	//查询订单
	public function Queryorder($transaction_id){
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg){
		$postData = json_encode($data);
		Yii::info("call back:1001" . $postData,'wxpay');
		$notfiyOutput = array();
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			Yii::info($msg,'wxpay');
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			Yii::info($msg,'wxpay');
			return false;
		}
		// 开始发货
		$order_id = $data["out_trade_no"];
		$order = TOrder::findOne(['f_order_id'=>$order_id]);
		if(!$order){
			$msg = "充值订单不存在：".$order_id;
			Yii::info($msg,'wxpay');
            return false;
		}
		if($order->f_status == 1){
            $msg = "订单已发货：".$order_id;
            Yii::info($msg,'wxpay');
            return false;
        }
		$db = Yii::$app->db;
		$transaction = $db->beginTransaction();
		try {
            // 给用户发房卡
            $card = TUserRoomCard::findOne(['f_uid'=>$order->f_uid]);
            if(!$card){
                $card = new TUserRoomCard;
                $card->f_uid = $order->f_uid;
            }
            $card->f_num += $order->f_num;
            $card->save();
            $log = new TManagerRoomCardLog;
            $log->f_mid = 0;
            $log->f_to_mid = $order->f_uid;
            $log->f_num = $order->f_num;
            $log->f_type = 3;
            $log->f_created = date('Y-m-d H:i:s');
            $log->save();
            $order->f_status = 1;
            $order->f_charge_paramdown = $postData;
            $order->save();
            // 代理返利
			$scale = Yii::$app->params['agent_scale'];// 代理比例
			$count = count($scale);
            $user = UserInfo::findOne(['f_id'=>$order->f_uid]);
            $agentId = $user->f_agentId;
            for ($i=1; $i <= $count; $i++) { 
				if (!$agentId) {
					break;
				}
				// $agent = TAgent::findOne(['f_id'=>$agentId]);
				$agent = UserInfo::findOne(['f_id'=>$agentId]);
				if (!$agent) {
					break;
				}
            	$s = $scale[$i-1];				
                $model = new TAgentRebateLog;
                $model->f_uid = $order->f_uid;
                $model->f_aid = $agent->f_id;
                $model->f_username = $order->f_username;
                $model->f_price = $order->f_price;
                $model->f_num = $s * $order->f_price;
                $model->f_scale = $s;
                $model->f_level = $i;
                $model->f_created = date('Y-m-d H:i:s');
                $model->save();
                if(!$agent->f_agentId){
                    break;
                }
                $agentId = $agent->f_agentId;
            }
			$transaction->commit();
		} catch (Exception $e) {
			Yii::info("微信支付回调失败，订单号：".$order_id,'wxpay');
            $transaction->rollBack();
		}
		Yii::$app->db->close();
		return true;
	}
}

 ?>