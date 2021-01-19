<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Cko\Test\Gateway\Http\Client\Client;
use Cko\Test\Domain\AmazonConstraint;

class ConstraintValidator extends AbstractValidator
{

    /**
     * Performs validation of result code
     *
     * @param  array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        $response = $validationSubject['response'];

        if (isset($response['constraints']) && $response['constraints']) {
            $constraint = $response['constraints'][0];
            return $this->createResult(
                false,
                [$this->getConstraint($constraint)]
            );
        }

        // if no constraints found, continue to other validators for more specific errors
        return $this->createResult(
            true,
            ['status' => isset($response['status']) ? $response['status'] : __('No constraints detected.')]
        );
    }

    /**
     * @param AmazonConstraint $constraint
     * @return string
     */
    private function getConstraint(AmazonConstraint $constraint)
    {
        return $constraint->getId();
    }
}
