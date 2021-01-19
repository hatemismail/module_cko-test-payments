<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use Cko\Test\Gateway\Helper\SubjectReader;

/**
 * Class AbstractClient
 * Base class for gateway client classes
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * @var SubjectReader
     */
    protected $subjectReader;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * AbstractClient constructor.
     * @param Logger $logger
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        Logger $logger,
        SubjectReader $subjectReader
    ) {
        $this->subjectReader = $subjectReader;
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
            $message = $e->getMessage() ? $e->getMessage() : "Something went wrong during Gateway request.";
            $log['error'] = $message;
            $this->logger->debug($log);
        }

        return $response;
    }

    /**
     * Process http request
     *
     * @param array $data
     */
    abstract protected function process(array $data);
}
