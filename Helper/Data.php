<?php
namespace Cko\Test\Helper;
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper{
    public const CKO_DATA_STRUCTURE = [
        'card_token' => null,
        'status' => null,
        'payment_request' => [
            'request' => [],
            'response' => [],
            '3ds_response' => [],
        ]
    ];

    /**
     * @var \Magento\Quote\Model\QuoteFactory $quoteFactory
     */
    private $quoteFactory;

    /**
     * @param Context $context
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        Context $context,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
        $this->quoteFactory = $quoteFactory;
        parent::__construct($context);
    }

    /**
     * @param $cartId
     * @param $newCkoData
     * @return $this
     * @throws \Exception
     */
    public function setQuoteCkoData($cartId, $newCkoData){
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create()->load($cartId);
        $ckoData = self::CKO_DATA_STRUCTURE;
        if($quote->getData('cko_data')){
            $ckoData = json_decode($quote->getData('cko_data'), true);
        }
        $ckoData = $this->array_merge_recursive_new($ckoData, $newCkoData);
        $quote->setData('cko_data', json_encode($ckoData));
        $quote->save();
        return $this;
    }

    /**
     * @param $cartId
     * @return mixed
     */
    public function getQuoteCkoData($cartId){
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create()->load($cartId);
        if(!$quote->getData('cko_data')){
            $this->setQuoteCkoData($cartId, []);
        }
        return json_decode($quote->getData('cko_data'), true);
    }

    private function array_merge_recursive_new() {

        $arrays = func_get_args();
        $base = array_shift($arrays);

        foreach ($arrays as $array) {
            reset($base); //important
            while (list($key, $value) = @each($array)) {
                if (is_array($value) && @is_array($base[$key])) {
                    $base[$key] = $this->array_merge_recursive_new($base[$key], $value);
                } else {
                    $base[$key] = $value;
                }
            }
        }

        return $base;
    }
}
