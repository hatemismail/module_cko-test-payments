<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Cko\Test\Gateway;

use Cko\Test\Model\Adminhtml\Source\Environment;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Houses configuration for this gateway
 * official payment integration available on the marketplace
 */
class Config extends \Magento\Payment\Gateway\Config\Config
{
    const METHOD = 'cko_test';
    private const KEY_CKO_PUBLIC_KEY = 'cko_public_key';
    private const KEY_CKO_SECRET_KEY = 'cko_secret_key';
    private const KEY_ENVIRONMENT = 'environment';
    private const KEY_SERVICE_NAME = 'service_name';
    private const KEY_SERVICE_DESCRIPTION = 'service_description';
    private const KEY_PAYMENT_ACTION = 'payment_action';
    private const KEY_SHOULD_EMAIL_CUSTOMER = 'email_customer';
    private const KEY_ADDITIONAL_INFO_KEYS = 'paymentInfoKeys';
    private const ENDPOINT_URL_SANDBOX = 'https://api.sandbox.checkout.com';
    private const ENDPOINT_URL_PRODUCTION = 'https://api.checkout.com';

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $methodCode = null,
        $pathPattern = parent::DEFAULT_PATH_PATTERN
    )
    {
        parent::__construct($scopeConfig, $methodCode, $pathPattern);
        $this->setMethodCode(\Cko\Test\Model\Ui\ConfigProvider::CODE);
    }

    /**
     * Gets the login id
     *
     * @param int|null $storeId
     * @return string
     */
    public function getCkoPublicKey($storeId = null): ?string
    {
        return $this->decryptValue(
            $this->getValue(Config::KEY_CKO_PUBLIC_KEY, $storeId)
        );
    }

    /**
     * Gets the current environment
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEnvironment($storeId = null): string
    {
        return $this->getValue(Config::KEY_ENVIRONMENT, $storeId);
    }

    /**
     * Gets the current service name
     *
     * @param int|null $storeId
     * @return string
     */
    public function getServiceName($storeId = null): string
    {
        return $this->getValue(Config::KEY_SERVICE_NAME, $storeId);
    }

    /**
     * Gets the current service description
     *
     * @param int|null $storeId
     * @return string
     */
    public function getServiceDescription($storeId = null): string
    {
        return $this->getValue(Config::KEY_SERVICE_DESCRIPTION, $storeId);
    }

    /**
     * Gets the cko secret key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getCkoSecretKey($storeId = null): ?string
    {
        return $this->decryptValue(
            $this->getValue(Config::KEY_CKO_SECRET_KEY, $storeId)
        );
    }

    /**
     * Gets the API endpoint URL
     *
     * @param int|null $storeId
     * @return string
     */
    public function getApiUrl($storeId = null): string
    {
        $environment = $this->getValue(Config::KEY_ENVIRONMENT, $storeId);

        return $environment === Environment::ENVIRONMENT_SANDBOX
            ? self::ENDPOINT_URL_SANDBOX
            : self::ENDPOINT_URL_PRODUCTION;
    }

    /**
     * Gets the configured payment action
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentAction($storeId = null): ?string
    {
        return $this->getValue(Config::KEY_PAYMENT_ACTION, $storeId);
    }


    /**
     * @param int|null $storeId
     * @return bool
     */
    public function shouldEmailCustomer($storeId = null): bool
    {
        return (bool)$this->getValue(Config::KEY_SHOULD_EMAIL_CUSTOMER, $storeId);
    }

    /**
     * Returns the keys to be pulled from the transaction and displayed
     *
     * @param int|null $storeId
     * @return string[]
     */
    public function getAdditionalInfoKeys($storeId = null): array
    {
        return explode(',', $this->getValue(Config::KEY_ADDITIONAL_INFO_KEYS, $storeId) ?? '');
    }

    /**
     * @param $encryptedValue
     * @return string
     */
    public function decryptValue($encryptedValue){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Framework\Encryption\EncryptorInterface $encryptor */
        $encryptor = $objectManager->create('Magento\Framework\Encryption\EncryptorInterface');
        return $encryptor->decrypt($encryptedValue);
    }
}
