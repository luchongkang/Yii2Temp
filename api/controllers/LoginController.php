<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;
use common\Common;
use common\models\TShopsManager;
use common\models\TSeller;

/**
 *  API登陆统一接口
 */
class LoginController extends  Controller { 

    /**
     * 检测Token是否有效
     * @return [type] [description]
     */
    public function actionOrder(){
        $appid = 'sowcrht3xw7k';
        $secret = 'nMS2W3ven0zwjkKaX0T8W2nVdfl9tHY7';
        $url = 'https://uat.sopay.org/soapi/pay/unifiedorder';
        $data['app_id'] = $appid;
        $data['nonce_str'] = Common::getRandomStr(10);
        $data['body'] = "dddd";
        // $data['attach'] = $appid;
        $data['out_trade_no'] = Common::GetOrderNum();
        $data['fee_type'] = 'ETH';
        $data['total_fee'] = '200.12';
        $data['notify_url'] = 'https://uat.sopay.org';
        $data['trade_type'] = 'MWEB';

        $body = json_encode($data);// Common::curlReuqest($url,true,json_encode($data));
        $body = $body.$secret;
        Yii::info('<<<<<<<<<<---------sign:'.$body,'debug');
        $sign = hash_hmac('sha256', $body, $secret);
        $sign = strtolower($sign);
        Yii::info('<<<<<<<<<<---------sign:'.$sign,'debug');
        echo json_encode($data);
    }
    public function actionError(){
        $this->RspJson([],404,'fail');
    }

    // 发送手机验证码
    public function actionCode(){
        $request = yii::$app->request;
        if(!$request->getIsAjax() || !$request->isPost){
            Common::RspJson(['code'=>1 , 'msg' => 'error']);
        }
        $data = Common::getPostData();
        $code = Common::getRandomStr(4);
        Yii::$app->session->set('check_code', $code);
        // $result = Common::SendSMS($data['mobile'], $code);
        // if ($result) {
             Common::RspJson(['code'=>0]);
        // }
        // Common::RspJson(['code'=>1]);
    }
    // 检查用户手机验证码
    public function actionCheckcode(){
        $request = yii::$app->request;
        if(!$request->getIsAjax() || !$request->isPost){
            $this->RspJson([], 1, '密码错误');
        }
        $data = Common::getPostData();
        // $code = Yii::$app->session->get('check_code');
        // if (!$code || $data['code'] != $code) {
        //     Common::RspJson(['code'=>1 , 'msg' => '验证码错误'.$code]);
        // }
        $model = TShopsManager::findOne(['f_mobile'=>$data['mobile']]);
        if(!$model || $model->f_password != md5($data['code'])){
            $this->RspJson([], 1, '密码错误');
            // $model = new TShopsManager;
            // $model->f_mobile = $data['mobile'];
            // $model->f_created = date('Y-m-d H:i:s');
            // $model->save();
        }
        $session = Yii::$app->session;
        $session->set('sm_id',$model->f_id);
        $session->set('sm_mobile',$model->f_mobile);
        $this->RspJson(['url'=>'/index']);
    }
    // 业主登录接口
    public function actionSeller(){
        $request = yii::$app->request;
        if(!$request->getIsAjax() || !$request->isPost){
            $this->RspJson([], 1, '密码错误');
        }
        $data = Common::getPostData();
        $model = TSeller::findOne(['f_mobile'=>$data['mobile']]);
        if(!$model || $model->f_password != md5($data['password'])){
            $this->RspJson([], 1, '密码错误');
        }
        Yii::$app->session->set('seller_id',$model->f_id);
        $this->RspJson(['url'=>'/index']);
    }
    // 检查用户有没有已经登录
    public function actionCheck($type = 0){
        if($type == 0){
            $session = Yii::$app->session->get('sm_id');
        }else if ($type == 1){
            $session = Yii::$app->session->get('seller_id');
        }
        
        $code['code'] = 0;
        if($session){
            $code['code'] = 1;
        }else{
            $code['token'] = Yii::$app->request->getCsrfToken();
        }
        Common::RspJson($code);
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
