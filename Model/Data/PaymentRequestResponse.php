<?php
declare(strict_types=1);

namespace Cko\Test\Model\Data;

use Cko\Test\Api\Data\PaymentRequestResponseInterface;

/**
 * Class NotifyResponse
 */
class PaymentRequestResponse implements PaymentRequestResponseInterface
{
    /**
     * @var string $redirectUrl
     */
    private $redirectUrl;

    /**
     * @var string $status
     */
    private $status;

    /**
     * @var string $error
     */
    private $error;

    /**
     * @return string
     */
    public function getRedirectUrl(){
        return $this->redirectUrl;
    }

    /**
     * @param string $url
     * @return PaymentRequestResponseInterface
     */
    public function setRedirectUrl($url){
        $this->redirectUrl = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @param string $status
     * @return PaymentRequestResponseInterface
     */
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }


    /**
     * @return string
     */
    public function getError(){
        return $this->error;
    }

    /**
     * @param string $error
     * @return PaymentRequestResponseInterface
     */
    public function setError($error){
        $this->error = $error;
        return $this;
    }
}
