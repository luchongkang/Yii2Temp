<?php 
namespace frontend;
use yii;
use yii\web\Controller;
use backend\models\TManager;
/**
* 控制器
*/
class Base extends Controller{
	public $_AgentID;
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;//禁用CSRF
		$session = Yii::$app->session;
		$this->_AgentID = $session->get('agent_id');
        if(!$this->_AgentID){
        	$this->RspJson(-1);
		}
        return parent::beforeAction($action);
    }
    public function afterAction($action, $result){
	    $result = parent::afterAction($action, $result);
	    Yii::$app->db->close();//关闭数据库连接
	    return $result;
	}
	/**
     * 接口统一返回格式
     * @param array   $data [主要数据]
     * @param integer $code [返回码 0 表示正常返回 其它均为错误码]
     * @param string  $msg  [返回消息说明]
     */
	public static function RspJson($code = 0,$data = [] ,$msg = "success"){
		$arr['code'] = $code;
		$arr['data'] = $data;
		$arr['msg'] = $msg;
        echo json_encode($arr);
        Yii::$app->db->close();
        Yii::$app->end();
    }
	public static function GetManagerRoomCard($mid){
		$model = \backend\models\TManagerRoomCard::findOne(['f_mid'=>$mid]);
		if($model){
			return $model->f_num;
		}
		return 0;
	}
	public static function GetUserRoomCard($uid){
		$model = \backend\models\TUserRoomCard::findOne(['f_uid'=>$uid]);
		if($model){
			return $model->f_num;
		}
		return 0;
	}
}
