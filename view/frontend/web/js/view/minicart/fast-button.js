define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery',
    'ko',
    'underscore',
    'fastConfig',
    'clearCart'
], function (
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

    return Component.extend({
        observableProperties: [
            'items'
        ],
        initialize: function () {
            var self = this,
                minicart = $('[data-block="minicart"]');
            this._super();
            self.shouldShowFastButton = ko.observable(fastConfig.shouldShowFastOnCart());
            self.fastDark = ko.observable(fastConfig.getBtnTheme());
            customerData.get('cart').subscribe(
                function (cartData) {
                    $.ajax({
                        url: '/fast/cart/check',
                        type: 'GET',
                        dataType: 'json',
                        success: function (data, textStatus, xhr) {
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
                }
            );
            this.items(customerData.get('cart')().items); //get cart items
            minicart.on('contentLoading', function () {
                self.shouldShowFastButton(false);
                self.fastDark(false);
            });
        },
        initObservable: function () {
            this._super();
            this.observe(this.observableProperties);

            return this;
        },

        fastClickOld: function (data, e) {
            // get updated serverConfig values
            $.ajax({
                url: '/fast/config/fast',
                type: 'GET',
                dataType: 'json',
                success: function (data, textStatus, xhr) {
                    var theme = data.theme;

                    // Bail if Fast is not loaded
                    if (typeof Fast !== 'function') {
                        console.error('Fast not loaded, please reload the page and try again.');
                        return false;
                    }
                    Fast.checkout({
                        appId: data.appId,
                        buttonId: e.target.id,
                        cartId: data.cartId,
                        theme: theme
                    });
                }
            });
            return true;
        },

        fastClick: function (data, e) {
            function ajaxCall(callback){
                $.ajax({
                    url: '/fast/config/fast',
                    type: 'GET',
                    dataType: 'json'                
                }).done(function(data){
                    callback(data);
                }).fail(function(data){
                    callback(null);
                });
            }
            
            ajaxCall(function(data){
                if(data){
                    var theme = data.theme;
                    // Bail if Fast is not loaded
                    if (typeof Fast !== 'function') {
                        console.error('Fast not loaded, please reload the page and try again.');
                        return false;
                    }
                    Fast.checkout({
                        appId: data.appId,
                        buttonId: e.target.id,
                        cartId: data.cartId,
                        theme: theme
                    });
                    return true;
                }else{
                    console.error('Config call failed');
                    return true;
                }
            });
        },

        fastDark: function () {
            return fastConfig.getBtnTheme() === 'dark';
        }
    });
});
