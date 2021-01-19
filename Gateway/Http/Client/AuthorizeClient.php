<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Http\Client;

/**
 * Class Client
 * Amazon Pay authorization gateway client
 */
class AuthorizeClient extends AbstractClient
{

    /**
     * @inheritdoc
     */
    protected function process(array $data)
    {
        $ckoData = json_decode($this->subjectReader->getQuote()->getData('cko_data'), true);
        return $ckoData['payment_request'];
    }
}
