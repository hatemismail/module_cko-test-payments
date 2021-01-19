/**
 * Copyright © 2021 CKO By. [Hatem Ismail]. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Ui/js/model/messageList',
    'Magento_Checkout/js/model/quote',
    'ko',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'fancyBox',
    'ckoFramesJs'
], function ($, Component, fullScreenLoader, globalMessageList, quote, ko, urlBuilder, storage) {
    'use strict';

    return Component.extend({
        defaults: {
            active: false,
            cardValid: ko.observable(),
            environment: null,
            ckoPublicKey: null,
            ckoPaymentUrl: null,
            ckoCardToken: null,
            ckoSid: null,
            frames: null,
            currentBillingAddress: quote.billingAddress,
            template: 'Cko_Test/payment/ckotest'
        },

        /**
         * Set list of observable attributes
         *
         * @returns {exports.initObservable}
         */
        initObservable: function () {
            this._super().observe(['active']);
            return this;
        },

        /**
         * @returns {String}
         */
        getCode: function () {
            return 'cko_test';
        },

        /**
         * Initialize form elements for validation
         */
        initFormElement: function (element) {
            this.formElement = element;
            this.frames = Frames;
            this.environment = window.checkoutConfig.payment[this.getCode()].environment
            this.ckoPublicKey = window.checkoutConfig.payment[this.getCode()].cko_public_key
            this.frames.init(this.ckoPublicKey);
        },

        /**
         * @returns {Object}
         */
        getData: function () {
            return {
                method: this.getCode(),
                'additional_data': {
                    'cko_card_token': this.ckoCardToken,
                    'cko-session-id': this.ckoSid
                }
            };
        },

        /**
         * Check if payment is active
         *
         * @returns {Boolean}
         */
        isActive: function () {
            var active = this.getCode() === this.isChecked();
            this.active(active);
            return active;
        },

        /**
         * Check if card is valid by Frames.JS
         *
         * @returns {Observable}
         */
        isValidCard: function(){
            let self = this;
            self.cardValid(self.frames.isCardValid());
            self.frames.addEventHandler(self.frames.Events.CARD_VALIDATION_CHANGED, function (event) {
                self.cardValid(self.frames.isCardValid());
            });
            return this.cardValid;
        },

        /**
         * Prepare data to place order
         */
        beforePlaceOrder: function () {
            let self = this;
            fullScreenLoader.startLoader();
            self.frames.submitCard().then(function (data) {
                Frames.addCardToken(self.formElement, data.token);
                self.ckoCardToken = data.token;

                let serviceUrl, payload, headers = {};
                payload = {
                    "ckoToken": self.ckoCardToken
                };
                serviceUrl = urlBuilder.createUrl('/ckotest/mine/payment-request', {});
                return storage.post(
                    serviceUrl, JSON.stringify(payload), true, 'application/json', headers
                ).fail(
                    function (response) {
                        self._showError(response);
                        console.error(response);
                        self.placeOrder();
                    }
                ).always(
                    function (response) {
                        switch (response.status) {
                            case 'Pending':
                                $.fancybox.open({
                                    src  : response.redirect_url,
                                    type : 'iframe',
                                    opts : {
                                        keyboard: false,
                                        preventCaptionOverlap: false,
                                        arrows: false,
                                        infobar: false,
                                        modal: true,
                                        afterShow : function( instance, current ) {
                                            current.$iframe.on('load', function() {
                                                /* Will get it only when redirect to our server */
                                                if(current.$iframe && current.$iframe[0].contentWindow){
                                                    let urlInstance = new URL(current.$iframe[0].contentWindow.location.href);
                                                    let ckoSid = urlInstance.searchParams.get("cko-session-id");
                                                    console.info(ckoSid);
                                                    instance.close();

                                                    //fullScreenLoader.stopLoader();
                                                    self.placeOrder();
                                                }
                                            });

                                            /* Will fire only when redirect to our server */
                                            let isIframeRedirectBack;
                                            try{
                                                isIframeRedirectBack = () => {
                                                    if(current.$iframe && current.$iframe[0].contentWindow){
                                                        let urlInstance = new URL(current.$iframe[0].contentWindow.location.href);
                                                        let ckoSid = urlInstance.searchParams.get("cko-session-id");
                                                        console.info(ckoSid);
                                                        instance.close();

                                                        //fullScreenLoader.stopLoader();
                                                        self.placeOrder();
                                                    }
                                                }
                                                isIframeRedirectBack();
                                            }catch (e) {
                                                setTimeout(function () {
                                                    isIframeRedirectBack();
                                                }, 500);
                                            }
                                        }
                                    }
                                });
                                break;
                            default:
                                self.placeOrder();
                                break;
                        }
                    }
                );
            }).catch(function (error) {
                fullScreenLoader.stopLoader();
                self._showErrors(['Error when get card token', error]);
                console.error('Error when get card token', error);
            });
        },

        /**
         * Show error messages
         *
         * @param {String[]} errorMessages
         */
        _showErrors: function (errorMessages) {
            $.each(errorMessages, function (index, message) {
                globalMessageList.addErrorMessage({
                    message: message
                });
            });
        }
    });
});
