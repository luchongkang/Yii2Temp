<?php
namespace common\AliyunMNS\Requests;

use common\AliyunMNS\Constants;
use common\AliyunMNS\Requests\BaseRequest;
use common\AliyunMNS\Model\TopicAttributes;

class DeleteTopicRequest extends BaseRequest
{
    private $topicName;

    public function __construct($topicName)
    {
        parent::__construct('delete', 'topics/' . $topicName);
        $this->topicName = $topicName;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function generateBody()
    {
        return NULL;
    }

    public function generateQueryString()
    {
        return NULL;
    }
}
?>
