<?php
namespace common\AliyunMNS\Responses;

use common\AliyunMNS\Constants;
use common\AliyunMNS\Exception\MnsException;
use common\AliyunMNS\Exception\QueueNotExistException;
use common\AliyunMNS\Exception\MessageNotExistException;
use common\AliyunMNS\Exception\InvalidArgumentException;
use common\AliyunMNS\Exception\ReceiptHandleErrorException;
use common\AliyunMNS\Responses\BaseResponse;
use common\AliyunMNS\Common\XMLParser;
use common\AliyunMNS\Model\Message;

class ChangeMessageVisibilityResponse extends BaseResponse
{
    protected $receiptHandle;
    protected $nextVisibleTime;

    public function __construct()
    {
    }

    public function getReceiptHandle()
    {
        return $this->receiptHandle;
    }

    public function getNextVisibleTime()
    {
        return $this->nextVisibleTime;
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 200) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }

        $xmlReader = $this->loadXmlContent($content);

        try {
            $message = Message::fromXML($xmlReader, TRUE);
            $this->receiptHandle = $message->getReceiptHandle();
            $this->nextVisibleTime = $message->getNextVisibleTime();
        } catch (\Exception $e) {
            throw new MnsException($statusCode, $e->getMessage(), $e);
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }

    public function parseErrorResponse($statusCode, $content, MnsException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);

        try {
            $result = XMLParser::parseNormalError($xmlReader);

            if ($result['Code'] == Constants::INVALID_ARGUMENT)
            {
                throw new InvalidArgumentException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::QUEUE_NOT_EXIST)
            {
                throw new QueueNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::MESSAGE_NOT_EXIST)
            {
                throw new MessageNotExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::RECEIPT_HANDLE_ERROR)
            {
                throw new ReceiptHandleErrorException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }

            throw new MnsException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        } catch (\Exception $e) {
            if ($exception != NULL) {
                throw $exception;
            } elseif($e instanceof MnsException) {
                throw $e;
            } else {
                throw new MnsException($statusCode, $e->getMessage());
            }
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }

    }
}

?>
