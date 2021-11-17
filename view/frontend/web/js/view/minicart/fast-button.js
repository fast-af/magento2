define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery',
    'ko',
    'underscore',
    'fastConfig',
    'clearCart'
], function(
    Component,
    customerData,
    $,
    ko,
    _,
    fastConfigFactory,
    fastCartCleanup
) {
    'use strict';

    var fastConfig = fastConfigFactory();
    var fastAppId = '';

    return Component.extend({
        observableProperties: [
            'items'
        ],
        initialize: function() {
            var self = this,
                minicart = $('[data-block="minicart"]');
            this._super();
            self.cartId = ko.observable('');
            self.fastAppId = ko.observable(fastConfig.getAppId());
            self.shouldShowFastButton = ko.observable(fastConfig.shouldShowFastOnCart());
            self.fastDark = ko.observable(fastConfig.getBtnTheme() === 'dark');

            function ajaxCall(callback) {
                $.ajax({
                    url: '/fast/config/fast',
                    type: 'GET',
                    dataType: 'json'
                }).done(function(data) {
                    self.cartId(data.cartId);
                    self.fastAppId(data.appId);
                    self.fastDark(data.theme === 'dark');
                    callback(data);
                }).fail(function(data) {
                    callback(null);
                });
            };
            if (!self.fastAppId()) {
                //initial cart id lookup on page load
                ajaxCall(function(data) {
                    if (data == null) {
                        console.error('Config call failed');
                    }
                });
            }

            customerData.get('cart').subscribe(
                function(cartData) {
                    $.ajax({
                        url: '/fast/cart/check',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data, textStatus, xhr) {
                            self.shouldShowFastButton(data.areAllProductsFast);
                            if (data.theme !== 'dark') {
                                self.fastDark(false);
                            }
                            if (data.theme === 'dark') {
                                self.fastDark(true);
                            }
                        }
                    });
                    self.items(cartData.items);
                },
                //we also subscribe to cart updates to ensure
                //cart id is up to date
                ajaxCall(function(data) {
                    if (data === null) {
                        console.error('Config call failed');
                    }
                })
            );
            this.items(customerData.get('cart')().items); //get cart items
            minicart.on('contentLoading', function() {
                self.shouldShowFastButton(false);
                self.fastDark(false);
            });
        },

        initObservable: function() {
            this._super();
            this.observe(this.observableProperties);
            return this;
        },

        fastClick: function(data, e) {
            var self = this;
            if (typeof Fast !== 'function') {
                console.error('Fast not loaded, please reload the page and try again.');
                return false;
            }
            Fast.checkout({
                appId: self.fastAppId(),
                buttonId: e.target.id,
                cartId: self.cartId(),
                theme: self.fastDark()
            });
        },

        fastDarkFunc: function() {
            return fastConfig.getBtnTheme() === 'dark';
        }
    });
});