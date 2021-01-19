<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Model\Method\Logger;
use Cko\Test\Gateway\Helper\SubjectReader;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class RefundHandler
 * Handles refund behavior for Amazon Pay
 */
class RefundHandler implements HandlerInterface
{

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var SubjectReader
     */
    private $subjectReader;


    /**
     * RefundHandler constructor.
     *
     * @param Logger                        $logger
     * @param SubjectReader                 $subjectReader
     * @param ManagerInterface              $messageManager
     */
    public function __construct(
        Logger $logger,
        SubjectReader $subjectReader,
        ManagerInterface $messageManager
    ) {
        $this->logger = $logger;
        $this->subjectReader = $subjectReader;
        $this->messageManager = $messageManager;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     */
    public function handle(array $handlingSubject, array $response)
    {
        //ToDo
    }
}
