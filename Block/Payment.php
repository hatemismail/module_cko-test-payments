<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Cko\Test\Block;

use Cko\Test\Gateway\Config;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Represents the payment block for the admin checkout form
 *
 * @api
 * @since 100.2.1

 * official payment integration available on the marketplace
 */
class Payment extends Template
{
    /**
     * @var ConfigProviderInterface
     */
    private $config;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Context $context
     * @param ConfigProviderInterface $config
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigProviderInterface $config,
        Json $json,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->json = $json;
    }

    /**
     * Retrieves the config that should be used by the block
     *
     * @return string
     * @since 100.2.1
     */
    public function getPaymentConfig(): string
    {
        $payment = $this->config->getConfig()['payment'];
        $config = $payment[$this->getMethodCode()];
        $config['code'] = $this->getMethodCode();

        return $this->json->serialize($config);
    }

    /**
     * Returns the method code for this payment method
     *
     * @return string
     * @since 100.2.1
     */
    public function getMethodCode(): string
    {
        return Config::METHOD;
    }
}
