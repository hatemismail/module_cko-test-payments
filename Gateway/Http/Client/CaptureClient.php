<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Http\Client;

/**
 * Class Client
 * Amazon Pay gateway capture client
 */
class CaptureClient extends AbstractClient
{

    /**
     * @inheritdoc
     */
    protected function process(array $data)
    {
        $response = [];

        return $response;
    }
}
