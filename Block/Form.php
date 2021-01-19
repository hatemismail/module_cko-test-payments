<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Cko\Test\Block;

use Cko\Test\Gateway\Config;
use Magento\Backend\Model\Session\Quote;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Block\Form\Cc;
use Magento\Payment\Model\Config as PaymentConfig;

/**
 * Block for representing the payment form
 *
 * @api
 * @since 100.2.1

 * official payment integration available on the marketplace
 */
class Form extends Cc
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Quote
     */
    private $sessionQuote;

    /**
     * @param Context $context
     * @param PaymentConfig $paymentConfig
     * @param Config $config
     * @param Quote $sessionQuote
     * @param array $data
     */
    public function __construct(
        Context $context,
        PaymentConfig $paymentConfig,
        Config $config,
        Quote $sessionQuote,
        array $data = []
    ) {
        parent::__construct($context, $paymentConfig, $data);
        $this->config = $config;
        $this->sessionQuote = $sessionQuote;
    }

    /**
     * Check if cvv validation is available
     *
     * @return boolean
     * @since 100.2.1
     */
    public function isCvvEnabled(): bool
    {
        return $this->config->isCvvEnabled($this->sessionQuote->getStoreId());
    }
}
