<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Framework\App\ProductMetadata;
use Cko\Test\Gateway\Helper\SubjectReader;
use Magento\Sales\Api\OrderRepositoryInterface;
use Cko\Test\Gateway\Data\Order\OrderAdapterFactory;

/**
 * Class RefundRequestBuilder
 * Builds refund request for Amazon Pay
 */
class RefundRequestBuilder implements BuilderInterface
{

    /**
     * @var ProductMetadata
     */
    private $productMetaData;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OrderAdapterFactory
     */
    private $orderAdapterFactory;

    /**
     * RefundRequestBuilder constructor.
     *
     * @param ProductMetadata $productMetadata
     * @param SubjectReader $subjectReader
     * @param Data $coreHelper
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderAdapterFactory $orderAdapterFactory
     */
    public function __construct(
        ProductMetaData $productMetadata,
        SubjectReader $subjectReader,
        OrderRepositoryInterface $orderRepository,
        OrderAdapterFactory $orderAdapterFactory
    ) {
        $this->productMetaData = $productMetadata;
        $this->subjectReader = $subjectReader;
        $this->orderRepository = $orderRepository;
        $this->orderAdapterFactory = $orderAdapterFactory;
    }

    /**
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        $data = [];

        return $data;
    }
}
