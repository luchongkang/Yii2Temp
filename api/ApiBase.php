<?php 
namespace api;
use Yii;
use yii\web\Controller;
/**
* API基类
*/
class ApiBase extends Controller{
    public $_SM_ID;
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;//禁用CSRF
        $this->_SM_ID = Yii::$app->session->get('sm_id');
        if(!$this->_SM_ID){
            $this->RspJson([], -1);
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
	public static function RspJson($data = [] ,$code = 0 ,$msg = "success"){
		$arr['code'] = $code;
		$arr['data'] = $data;
		$arr['msg'] = $msg;
        echo json_encode($arr);
        Yii::$app->db->close();
        Yii::$app->end();
    }
}
 ?>