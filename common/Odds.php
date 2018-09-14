<?php 
namespace common;
use Yii;
/**
* 公共方法
*/
class Odds{
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
    /* 
     * 经典的概率算法， 
     * $proArr是一个预先设置的数组， 
     * 假设数组为：array(100,200,300，400)， 
     * 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内，  
     * 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间， 
     * 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。 
     * 这样 筛选到最终，总会有一个数满足要求。 
     * 就相当于去一个箱子里摸东西， 
     * 第一个不是，第二个不是，第三个还不是，那最后一个一定是。 
     * 这个算法简单，而且效率非常 高， 
     * 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。 
     */  
    public  function get_rand($proArr) {   
        $result = '';    
        //概率数组的总概率精度   
        $proSum = array_sum($proArr);    
        //概率数组循环   
        foreach ($proArr as $key => $proCur) {   
            $randNum = mt_rand(1, $proSum);   
            if ($randNum <= $proCur) {   
                $result = $key;   
                break;   
            } else {   
                $proSum -= $proCur;   
            }         
        }   
        unset ($proArr);    
        return $result;   
    }   
	
}
 ?>