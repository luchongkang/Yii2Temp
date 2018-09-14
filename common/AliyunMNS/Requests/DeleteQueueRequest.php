<?php
namespace common\AliyunMNS\Requests;

use common\AliyunMNS\Constants;
use common\AliyunMNS\Requests\BaseRequest;
use common\AliyunMNS\Model\QueueAttributes;

class DeleteQueueRequest extends BaseRequest
{
    private $queueName;

    public function __construct($queueName)
    {
        parent::__construct('delete', 'queues/' . $queueName);
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
