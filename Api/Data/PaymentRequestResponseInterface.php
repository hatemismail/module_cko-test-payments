<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Cko\Test\Api\Data;


/**
 * Interface PaymentRequestResponseInterface
 * @package Cko\Test\Api\Data
 */
interface PaymentRequestResponseInterface
{
    /**
     * @return string
     */
    public function getRedirectUrl();

    /**
     * @param string $url
     * @return PaymentRequestResponseInterface
     */
    public function setRedirectUrl($url);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return PaymentRequestResponseInterface
     */
    public function setStatus($status);


    /**
     * @return string
     */
    public function getError();

    /**
     * @param string $error
     * @return PaymentRequestResponseInterface
     */
    public function setError($error);

}
