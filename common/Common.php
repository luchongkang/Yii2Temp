<?php 
namespace common;
use Yii;
/**
* 公共方法
*/
class Common{
	// 绑定奖励
	public static function BandGift($uid) {
        // 给用户发房卡
        $card = \backend\models\TUserRoomCard::findOne(['f_uid'=>$uid]);
        $num = Yii::$app->params['bind_agent_card'];
		$card->f_num += $num;
		$card->f_lastmodifytime = date('Y-m-d H:i:s');
        $card->save();
        $log = new \backend\models\TManagerRoomCardLog;
        $log->f_mid = 0;
        $log->f_to_mid = $uid;
        $log->f_num = $num;
        $log->f_type = 7;
        $log->f_created = date('Y-m-d H:i:s');
        $log->save();
    }
	//获取表的的指定列返回array
	public static function GetTableColumn($list,$column = 'id'){
		$arr = [];
		foreach ($list as $k) {
			array_push($arr, $k[$column]);
		}
		return $arr;
	}
	// 验证时间格式
	public static function isDate($date, $format = 'Y-m-d H:i:s')
	{
	    $d = \DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	public static function RspJson($arr){
        echo json_encode($arr); 
        Yii::$app->db->close();
        Yii::$app->end();
    }
    public static function SendSMS($mobile, $code){
    	$sms = new \common\AliyunMNS\PushSMS;
    	return $sms->sendCode($mobile, $code);
    }
   	/**
   	 * 随机生成一个订单号
   	 */
    public static function GetOrderNum(){
    	return date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
    public static function getRandomStr($length = 6) {
	    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';//
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	/**
	 * 发送HTTP请求
	 *
	 * @return boolean
	 */
	public static function curlReuqest($url, $isPost = true , $data = array(),$timeout = 30, $CA = false) {
		$cacert = getcwd() . '/cacert.pem'; //CA根证书
		$SSL = substr($url, 0, 8) == "https://" ? true : false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
		if ($SSL && $CA) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
			curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
		} else if ($SSL && !$CA) { 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
		if($isPost){
			curl_setopt($ch, CURLOPT_POST, $isPost);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$ret = curl_exec($ch);
		curl_close($ch); 
		return $ret;
	}
	public  static function getPostData () {
		return json_decode(Yii::$app->getRequest()->getRawBody(), true);
	}
	public static function getSessionId(){
		if(!session_id()){
			session_start();
		}
		return session_id();
	}
}
 ?>