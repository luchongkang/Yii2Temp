<?php 
namespace backend;
use yii;
use yii\web\Controller;
use backend\models\TManager;
/**
* 控制器
*/
class AdminBase extends Controller{
	public $_ManagerID;
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;//禁用CSRF
		$session = Yii::$app->session;
		$this->_ManagerID = $session->get('admin_id');
		$updateTime = $session->get('admin_updateTime');
		//$info = TManager::findOne($admin_id);|| !$updateTime || strtotime("+2 hour",$updateTime) < time()
        if(!$this->_ManagerID){
			if(Yii::$app->request->getIsAjax()){
				Yii::$app->end();
			}
			$this->redirect(['/login']);
			Yii::$app->end();
		}
		$session->set('admin_updateTime',time());
        return parent::beforeAction($action);
    }
    public function afterAction($action, $result){
	    $result = parent::afterAction($action, $result);
	    Yii::$app->db->close();//关闭数据库连接
	    return $result;
	}
    // 验证权限
    public function CheckAuth($menuId ,$parentId){
    	$rid = Yii::$app->session->get('admin_roleId');
        if($rid != 0){
            $authCache = self::GetAuthByRoleId($rid);
            $auth = explode(',', $authCache);
            if(!in_array($menuId, $auth) && !in_array($menuId, $auth)){
            	$this->redirect(['/login/logout']);
            	Yii::$app->end();
            }
        }
    }
	public function RspJson($arr){
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
	// 获取用户的角色权限
	public static function GetAuthByRoleId($rid){
		$authCache = Yii::$app->cacheFile->get('Auth_'.$rid);
		if(!$authCache){
			// 缓存角色权限
			$role = \backend\models\TRole::findOne(['f_id'=>$rid]);
			Yii::$app->cacheFile->set("Auth_".$rid,$role->f_auth);
			$authCache = $role->f_auth;
		}
		return $authCache;
	}
	
}
