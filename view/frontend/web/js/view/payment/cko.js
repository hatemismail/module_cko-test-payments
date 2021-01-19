/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
],
function (Component, rendererList) {
    'use strict';

    rendererList.push({
        type: 'cko_test',
        component: 'Cko_Test/js/view/payment/method-renderer/ckotest'
    });

    /** Add view logic here if needed */
    return Component.extend({});
});
