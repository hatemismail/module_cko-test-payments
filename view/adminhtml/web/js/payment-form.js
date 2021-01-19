/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Cko_Test/js/cko',
    'jquery'
], function (CkoTest, $) {
    'use strict';

    return function (config, element) {
        var $form = $(element);

        config.active = $form.length > 0 && !$form.is(':hidden');
        new CkoTest(config);
    };
});
