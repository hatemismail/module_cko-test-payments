<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Cko\Test\Model\Adminhtml\Source;

/**
 * Authorize.net Payment Action Dropdown source
 * official payment integration available on the marketplace
 */
class PaymentAction implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => 'authorize',
                'label' => __('Authorize Only'),
            ]
        ];
    }
}
