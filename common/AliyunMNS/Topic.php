<?php
namespace common\AliyunMNS;

use common\AliyunMNS\Http\HttpClient;
use common\AliyunMNS\AsyncCallback;
use common\AliyunMNS\Model\TopicAttributes;
use common\AliyunMNS\Model\SubscriptionAttributes;
use common\AliyunMNS\Model\UpdateSubscriptionAttributes;
use common\AliyunMNS\Requests\SetTopicAttributeRequest;
use common\AliyunMNS\Responses\SetTopicAttributeResponse;
use common\AliyunMNS\Requests\GetTopicAttributeRequest;
use common\AliyunMNS\Responses\GetTopicAttributeResponse;
use common\AliyunMNS\Requests\PublishMessageRequest;
use common\AliyunMNS\Responses\PublishMessageResponse;
use common\AliyunMNS\Requests\SubscribeRequest;
use common\AliyunMNS\Responses\SubscribeResponse;
use common\AliyunMNS\Requests\UnsubscribeRequest;
use common\AliyunMNS\Responses\UnsubscribeResponse;
use common\AliyunMNS\Requests\GetSubscriptionAttributeRequest;
use common\AliyunMNS\Responses\GetSubscriptionAttributeResponse;
use common\AliyunMNS\Requests\SetSubscriptionAttributeRequest;
use common\AliyunMNS\Responses\SetSubscriptionAttributeResponse;
use common\AliyunMNS\Requests\ListSubscriptionRequest;
use common\AliyunMNS\Responses\ListSubscriptionResponse;

class Topic
{
    private $topicName;
    private $client;

    public function __construct(HttpClient $client, $topicName)
    {
        $this->client = $client;
        $this->topicName = $topicName;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function setAttribute(TopicAttributes $attributes)
    {
        $request = new SetTopicAttributeRequest($this->topicName, $attributes);
        $response = new SetTopicAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function getAttribute()
    {
        $request = new GetTopicAttributeRequest($this->topicName);
        $response = new GetTopicAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function generateQueueEndpoint($queueName)
    {
        return "acs:mns:" . $this->client->getRegion() . ":" . $this->client->getAccountId() . ":queues/" . $queueName;
    }

    public function generateMailEndpoint($mailAddress)
    {
        return "mail:directmail:" . $mailAddress;
    }

    public function generateSmsEndpoint($phone = null)
    {
        if ($phone)
        {
            return "sms:directsms:" . $phone;
        }
        else
        {
            return "sms:directsms:anonymous";
        }
    }

    public function generateBatchSmsEndpoint()
    {
        return "sms:directsms:anonymous";
    }

    public function publishMessage(PublishMessageRequest $request)
    {
        $request->setTopicName($this->topicName);
        $response = new PublishMessageResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function subscribe(SubscriptionAttributes $attributes)
    {
        $attributes->setTopicName($this->topicName);
        $request = new SubscribeRequest($attributes);
        $response = new SubscribeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function unsubscribe($subscriptionName)
    {
        $request = new UnsubscribeRequest($this->topicName, $subscriptionName);
        $response = new UnsubscribeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function getSubscriptionAttribute($subscriptionName)
    {
        $request = new GetSubscriptionAttributeRequest($this->topicName, $subscriptionName);
        $response = new GetSubscriptionAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function setSubscriptionAttribute(UpdateSubscriptionAttributes $attributes)
    {
        $attributes->setTopicName($this->topicName);
        $request = new SetSubscriptionAttributeRequest($attributes);
        $response = new SetSubscriptionAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function listSubscription($retNum = NULL, $prefix = NULL, $marker = NULL)
    {
        $request = new ListSubscriptionRequest($this->topicName, $retNum, $prefix, $marker);
        $response = new ListSubscriptionResponse();
        return $this->client->sendRequest($request, $response);
    }
}

?>
