<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Cko\Test\Gateway\Helper\SubjectReader;
use Magento\Framework\Event\ManagerInterface;

class AuthorizationRequestBuilder implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var SubjectReader
     */
    private $subjectReader;


    /**
     * @var ManagerInterface
     */
    private $eventManager;


    /**
     * AuthorizationRequestBuilder constructor.
     *
     * @param ConfigInterface $config
     * @param SubjectReader $subjectReader
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        ConfigInterface $config,
        SubjectReader $subjectReader,
        ManagerInterface $eventManager
    ) {
        $this->config = $config;
        $this->subjectReader = $subjectReader;
        $this->eventManager = $eventManager;
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
