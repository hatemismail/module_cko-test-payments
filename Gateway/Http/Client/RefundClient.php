<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

/**
 * Class RefundClient
 * Amazon Pay refund client
 */
class RefundClient implements ClientInterface
{

    const SUCCESS_CODES = ['Open', 'Closed', 'Completed', 'Pending'];

    /**
     * @var Logger
     */
    private $logger;

    /**
     * RefundClient constructor.
     *
     * @param Logger                      $logger
     */
    public function __construct(
        Logger $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {

        $data = $transferObject->getBody();

        $log = [
            'request' => $transferObject->getBody(),
            'client' => static::class
        ];

        $response = [];

        try {
            $response = $this->process($data);
        } catch (\Exception $e) {
            $message = __($e->getMessage() ?: "Something went wrong during Gateway request.");
            $log['error'] = $message;
            $this->logger->debug($log);
        } finally {
            $log['response'] = (array)$response;
            $this->logger->debug($log);
        }

        return $response;
    }

    /**
     * @inheritdoc
     */
    protected function process(array $data)
    {
        $store_id = $data['store_id'];
        unset($data['store_id']);

        $response = [
            'status' => false
        ];

        try {
            //ToDo-->
        } catch (\Exception $e) {
            $log['error'] = $e->getMessage();
            $this->logger->debug($log);
        }

        // Gateway expects response to be in form of array
        return $response;
    }
}
