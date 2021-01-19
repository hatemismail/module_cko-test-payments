<?php
/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Cko\Test\Block;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;

/**
 * Translates the labels for the info block
 *
 * @api
 * @since 100.2.1

 * official payment integration available on the marketplace
 */
class Info extends ConfigurableInfo
{
    /**
     * Returns label
     *
     * @param string $field
     * @return Phrase
     * @since 100.2.1
     */
    protected function getLabel($field): Phrase
    {
        return __($field);
    }
}
