<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cko\Test\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Cko\Test\Domain\AmazonConstraint;

/**
 * Class AuthorizationValidator
 * Validates authorization calls during gateway payment
 */
class AuthorizationValidator extends AbstractValidator
{

    /**
     * Performs validation of result code
     *
     * @param  array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {

        $status = 'NotFound';
        $responseSummary = 'No Authorization Response Found';
        if (isset($validationSubject['response']['status'])) {
            $status = $validationSubject['response']['status'];
            $responseSummary = $validationSubject['response']['response_summary'];
            if($validationSubject['response']['status'] == 'Pending'){
                $status = $validationSubject['3ds_response']['status'];
                $responseSummary = $validationSubject['3ds_response']['response_summary'];
            }
        }

        return $this->createResult(
            $status === 'Authorized',
            ['status' => $status, 'description' => $responseSummary]
        );
    }
}
