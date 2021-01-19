<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Command;

use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\ErrorMapper\ErrorMessageMapperInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\App\ObjectManager;
use Amazon\Core\Exception\AmazonWebapiException;
use Amazon\Core\Logger\ExceptionLogger;
use Cko\Test\Gateway\Config;

/**
 * Class AmazonAuthCommand
 *
 * Enables customized error handling for Amazon Payment
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CkoTestAuthCommand implements CommandInterface
{
    /**
     * @var BuilderInterface
     */
    private $requestBuilder;

    /**
     * @var TransferFactoryInterface
     */
    private $transferFactory;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var HandlerInterface
     */
    private $handler;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ErrorMessageMapperInterface
     */
    private $errorMessageMapper;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ExceptionLogger
     */
    private $exceptionLogger;

    /**
     * @param BuilderInterface $requestBuilder
     * @param TransferFactoryInterface $transferFactory
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     * @param HandlerInterface $handler
     * @param ValidatorInterface $validator
     * @param ErrorMessageMapperInterface|null $errorMessageMapper
     * @param Config $config
     * @param ExceptionLogger $exceptionLogger;
     */
    public function __construct(
        BuilderInterface $requestBuilder,
        TransferFactoryInterface $transferFactory,
        ClientInterface $client,
        LoggerInterface $logger,
        HandlerInterface $handler = null,
        ValidatorInterface $validator = null,
        ErrorMessageMapperInterface $errorMessageMapper = null,
        Config $config,
        ExceptionLogger $exceptionLogger = null
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->transferFactory = $transferFactory;
        $this->client = $client;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->logger = $logger;
        $this->errorMessageMapper = $errorMessageMapper;
        $this->config = $config;
        $this->exceptionLogger = $exceptionLogger ?: ObjectManager::getInstance()->get(ExceptionLogger::class);
    }

    /**
     * Executes command basing on business object
     *
     * @param array $commandSubject
     * @return \Magento\Payment\Gateway\Command\ResultInterface|null|void
     * @throws AmazonWebapiException
     * @throws \Magento\Payment\Gateway\Http\ClientException
     * @throws \Magento\Payment\Gateway\Http\ConverterException
     */
    public function execute(array $commandSubject)
    {
        try {
            $isTimeout = 0;

            $transferO = $this->transferFactory->create(
                $this->requestBuilder->build($commandSubject)
            );

            $response = $this->client->placeRequest($transferO);
            if ($this->validator !== null) {
                $result = $this->validator->validate(
                    array_merge($commandSubject, ['response' => $response])
                );
                if (!$result->isValid()) {
                    $this->processErrors($result, $response);
                }
            }

            if ($this->handler) {
                $this->handler->handle(
                    $commandSubject,
                    $response
                );
            }
        } catch (\Exception $e) {
            $this->exceptionLogger->logException($e);
            throw $e;
        }
    }

    /**
     * Tries to map error messages from validation result and logs processed message.
     * Throws an exception with mapped message or default error.
     *
     * @throws AmazonWebapiException
     */
    private function processErrors(ResultInterface $result, $mode = '')
    {
        $code = false;
        $messages = [];
        foreach ($result->getFailsDescription() as $failPhrase) {
            $message = (string)$failPhrase;

            if ($this->errorMessageMapper !== null) {
                $mapped = (string)$this->errorMessageMapper->getMessage($message);
                if (!empty($mapped) && !in_array($mapped, $messages)) {
                    $messages[] = $mapped;
                }
            }

            $this->logger->critical('Payment Error: ' . $message . ': ' . $mapped);
        }

        throw new AmazonWebapiException(
            !empty($messages)
                ? __(implode(PHP_EOL, $messages))
                : __('Transaction has been declined. Please try again later.'),
            400
        );

        return $false;
    }
}
