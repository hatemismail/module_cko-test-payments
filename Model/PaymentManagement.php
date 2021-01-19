<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Cko\Test\Model;


class PaymentManagement implements \Cko\Test\Api\PaymentManagementInterface
{
    /**
     * @var \Cko\Test\Api\Data\PaymentRequestResponseInterface
     */
    protected $paymentRequestResponse;

    /**
     * @var \Cko\Test\Gateway\Config
     */
    private $config;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl $curl
     */
    private $curl;

    /**
     * @var \Magento\Quote\Model\QuoteFactory $quoteFactory
     */
    private $quoteFactory;

    /**
     * @var \Cko\Test\Helper\Data $ckoHelper
     */
    private $ckoHelper;

    /**
     * @var \Magento\Framework\Webapi\Rest\Request $request
     */
    private $request;

    /**
     * @var \Magento\Framework\UrlInterface $urlInterface
     */
    private $urlInterface;

    /**
     * PaymentManagement constructor.
     * @param \Cko\Test\Api\Data\PaymentRequestResponseInterface $paymentRequestResponse
     * @param \Cko\Test\Gateway\Config $config
     * @param \Magento\Framework\Webapi\Rest\Request $request
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Cko\Test\Helper\Data $ckoHelper
     */
    public function __construct(
        \Cko\Test\Api\Data\PaymentRequestResponseInterface $paymentRequestResponse,
        \Cko\Test\Gateway\Config $config,
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Cko\Test\Helper\Data $ckoHelper
    )
    {
        $this->paymentRequestResponse = $paymentRequestResponse;
        $this->config = $config;
        $this->request = $request;
        $this->urlInterface = $urlInterface;
        $this->curl = $curlFactory;
        $this->quoteFactory = $quoteFactory;
        $this->ckoHelper = $ckoHelper;
    }

    /**
     * POST for payment request api
     * @param string $ckoToken
     * @param int $cartId
     * @return \Cko\Test\Api\Data\PaymentRequestResponseInterface
     * @throws \Exception
     */
    public function paymentRequest($ckoToken, $cartId)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create()->load($cartId);
        $url = $this->config->getApiUrl().'/payments';

        $httpAdapter = $this->curl->create();
        $payload = [
            'reference' => 'hatem_ismail_cko_mage',
            'source' => [
                'type' => 'token',
                'token' => $ckoToken,
            ],
            'currency' => $quote->getCurrency()->getBaseCurrencyCode(),
            'amount' => $quote->getGrandTotal() * 100,
            '3ds' => [
                'enabled' => true,
                'attempt_n3d' => true
            ],
            "metadata" => [
                "magento_cart_id" => $cartId
            ],
            "success_url" => $this->urlInterface->getBaseUrl().'rest/default/V1/ckotest/mine/3ds-response',
            "failure_url" => $this->urlInterface->getBaseUrl().'rest/default/V1/ckotest/mine/3ds-response',
        ];
        $httpAdapter->write(\Zend_Http_Client::POST, $url, '1.1', [
            "Content-Type: application/json",
            "Authorization: ".$this->config->getCkoSecretKey()
        ], json_encode($payload));
        $result = $httpAdapter->read();
        $body = \Zend_Http_Response::extractBody($result);
        $response = json_decode($body, true);

        $this->paymentRequestResponse->setStatus($response['status']);
        if($response['status'] == 'Declined'){
            $this->paymentRequestResponse->setError($response['response_summary']);
        }elseif ($response['status'] == 'Pending'){
            $this->paymentRequestResponse->setRedirectUrl($response['_links']['redirect']['href']);
        }

        $quoteCkoData = [
            'card_token' => $ckoToken,
            'payment_request' => [
                'request' => $payload,
                'response' => $response
            ]
        ];
        $this->ckoHelper->setQuoteCkoData($cartId, $quoteCkoData);

        return $this->paymentRequestResponse;
    }


    /**
     * GET for payment 3ds response api
     * @return string
     * @throws \Exception
     */
    public function payment3dsResponse(){
        $sid = $this->request->getParam('cko-session-id');
        $url = $this->config->getApiUrl().'/payments/'.$sid;

        $httpAdapter = $this->curl->create();
        $httpAdapter->write(\Zend_Http_Client::GET, $url, '1.1', [
            "Content-Type: application/json",
            "Authorization: ".$this->config->getCkoSecretKey()
        ]);
        $result = $httpAdapter->read();
        $body = \Zend_Http_Response::extractBody($result);
        $response = json_decode($body, true);

        $quoteCkoData = [
            'payment_request' => [
                '3ds_response' => $response
            ]
        ];
        $this->ckoHelper->setQuoteCkoData($response['metadata']['magento_cart_id'], $quoteCkoData);

        return 'Please wait, your payment in process...';
    }
}
