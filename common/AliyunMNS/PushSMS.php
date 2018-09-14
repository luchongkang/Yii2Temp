<?php 
namespace common\AliyunMNS;
use common\AliyunMNS\Client;
use common\AliyunMNS\Topic;
use common\AliyunMNS\Constants;
use common\AliyunMNS\Model\MailAttributes;
use common\AliyunMNS\Model\SmsAttributes;
use common\AliyunMNS\Model\BatchSmsAttributes;
use common\AliyunMNS\Model\MessageAttributes;
use common\AliyunMNS\Exception\MnsException;
use common\AliyunMNS\Requests\PublishMessageRequest;

/**
* 
*/
class PushSMS
{
	public function sendCode($moblie, $code){
        /**
         * Step 1. 初始化Client
         */
        $this->endPoint = "http://1423344917116609.mns.cn-hangzhou.aliyuncs.com/"; // eg. http://1234567890123456.mns.cn-shenzhen.aliyuncs.com
        $this->accessId = "LTAIKGVlRyaCsS2U";
        $this->accessKey = "s8dSWcPMJba56ZvYVPcgzybXPtquih";
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        /**
         * Step 2. 获取主题引用
         */
        $topicName = "sms.topic-cn-hangzhou";
        $topic = $this->client->getTopicRef($topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes("狗狗科技", "SMS_71360834");
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
        $batchSmsAttributes->addReceiver($moblie, array("bancode" => $code));
        // $batchSmsAttributes->addReceiver("YourReceiverPhoneNumber2", array("YourSMSTemplateParamKey1" => "value1"));
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
         $messageBody = "somebody";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try
        {
            $res = $topic->publishMessage($request);
            return $res->isSucceed();
        }
        catch (MnsException $e)
        {
        	return false;
        }
    }
}
 ?>