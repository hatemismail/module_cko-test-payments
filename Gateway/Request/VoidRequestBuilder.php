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

class VoidRequestBuilder implements BuilderInterface
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
     * VoidRequestBuilder constructor.
     *
     * @param ProductMetadata          $productMetadata
     * @param SubjectReader            $subjectReader
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        ProductMetaData $productMetadata,
        SubjectReader $subjectReader,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->productMetaData = $productMetadata;
        $this->subjectReader = $subjectReader;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Builds ENV request
     *
     * @param  array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        $data = [];

        return $data;
    }
}
