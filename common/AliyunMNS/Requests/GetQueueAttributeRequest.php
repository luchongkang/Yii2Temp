<?php
namespace common\AliyunMNS\Requests;

use common\AliyunMNS\Requests\BaseRequest;

class GetQueueAttributeRequest extends BaseRequest
{
    private $queueName;

    public function __construct($queueName)
    {
        parent::__construct('get', 'queues/' . $queueName);

        $this->queueName = $queueName;
    }

    public function getQueueName()
    {
        return $this->queueName;
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
