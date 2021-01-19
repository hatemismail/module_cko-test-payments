<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Cko\Test\Model\Ui;

use Cko\Test\Gateway\Config;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Retrieves config needed for checkout
 * official payment integration available on the marketplace
 */
class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'cko_test';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @param Config $config
     * @param CartInterface $cart
     */
    public function __construct(Config $config, CartInterface $cart)
    {
        $this->config = $config;
        $this->cart = $cart;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $storeId = $this->cart->getStoreId();

        return [
            'payment' => [
                Config::METHOD => [
                    'cko_public_key' => $this->config->getCkoPublicKey($storeId),
                    'environment' => $this->config->getEnvironment($storeId),
                ]
            ]
        ];
    }
}
