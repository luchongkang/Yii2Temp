<?php
namespace common\AliyunMNS\Traits;

use common\AliyunMNS\Constants;
use common\AliyunMNS\Model\Message;

trait MessageIdAndMD5
{
    protected $messageId;
    protected $messageBodyMD5;

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function getMessageBodyMD5()
    {
        return $this->messageBodyMD5;
    }

    public function readMessageIdAndMD5XML(\XMLReader $xmlReader)
    {
        $message = Message::fromXML($xmlReader, TRUE);
        $this->messageId = $message->getMessageId();
        $this->messageBodyMD5 = $message->getMessageBodyMD5();
    }
}

?>
