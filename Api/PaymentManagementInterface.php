<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Cko\Test\Api;

use stdClass;

interface PaymentManagementInterface
{

    /**
     * POST for payment request api
     * @param string $ckoToken
     * @param int $cartId
     * @return \Cko\Test\Api\Data\PaymentRequestResponseInterface
     */
    public function paymentRequest($ckoToken, $cartId);

    /**
     * GET for payment 3ds response api
     * @return string
     */
    public function payment3dsResponse();
}

