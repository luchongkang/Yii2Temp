<?php
namespace common\AliyunMNS\Exception;

use common\AliyunMNS\Constants;
use common\AliyunMNS\Exception\MnsException;
use common\AliyunMNS\Model\SendMessageResponseItem;

/**
 * BatchSend could fail for some messages,
 *     and BatchSendFailException will be thrown.
 * Results for messages are saved in "$sendMessageResponseItems"
 */
class BatchSendFailException extends MnsException
{
    protected $sendMessageResponseItems;

    public function __construct($code, $message, $previousException = NULL, $requestId = NULL, $hostId = NULL)
    {
        parent::__construct($code, $message, $previousException, Constants::BATCH_SEND_FAIL, $requestId, $hostId);

        $this->sendMessageResponseItems = array();
    }

    public function addSendMessageResponseItem(SendMessageResponseItem $item)
    {
        $this->sendMessageResponseItems[] = $item;
    }

    public function getSendMessageResponseItems()
    {
        return $this->sendMessageResponseItems;
    }
}

?>
