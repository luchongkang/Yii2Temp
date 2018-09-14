<?php
namespace common\AliyunMNS;

use common\AliyunMNS\Exception\MnsException;
use common\AliyunMNS\Responses\BaseResponse;

class AsyncCallback
{
    protected $succeedCallback;
    protected $failedCallback;

    public function __construct(callable $succeedCallback, callable $failedCallback)
    {
        $this->succeedCallback = $succeedCallback;
        $this->failedCallback = $failedCallback;
    }

    public function onSucceed(BaseResponse $result)
    {
        return call_user_func($this->succeedCallback, $result);
    }

    public function onFailed(MnsException $e)
    {
        return call_user_func($this->failedCallback, $e);
    }
}

?>
