<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Http\Client;

/**
 * Class VoidClient
 * Amazon Pay client for gateway cancel and void
 */
class VoidClient extends AbstractClient
{

    /**
     * @inheritdoc
     */
    protected function process(array $data)
    {
        $response = [
            'status' => false
        ];

        return $response;
    }
}
