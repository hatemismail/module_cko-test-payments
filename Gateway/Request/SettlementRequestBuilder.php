<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Request;

use Cko\Test\Gateway\Config;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Model\Method\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class SettlementRequestBuilder implements BuilderInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * SettlementRequestBuilder constructor.
     *
     * @param Config $config
     * @param OrderRepositoryInterface $orderRepository
     * @param CartRepositoryInterface $quoteRepository
     * @param Logger $logger
     */
    public function __construct(
        Config $config,
        OrderRepositoryInterface $orderRepository,
        CartRepositoryInterface $quoteRepository,
        Logger $logger
    ) {
        $this->config = $config;
        $this->orderRepository = $orderRepository;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Sales\Model\Order\Payment $payment
     * @return \Magento\Sales\Model\Order\Invoice
     */
    protected function getCurrentInvoice($payment)
    {
        $result = null;
        $order = $payment->getOrder();
        foreach ($order->getInvoiceCollection() as $invoice) {
            if (!$invoice->getId()) {
                $result = $invoice;
                break;
            }
        }
        return $result;
    }

    /**
     * @param \Magento\Sales\Model\Order\Payment $payment
     * @return string
     */
    protected function getSellerNote($payment)
    {
        $result = '';
        $invoice = $this->getCurrentInvoice($payment);
        if ($invoice && $invoice->getComments()) {
            foreach ($invoice->getComments() as $comment) {
                if ($comment->getComment()) {
                    $result = $comment->getComment();
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $buildSubject
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function build(array $buildSubject)
    {
        $data = [];

        return $data;
    }
}
