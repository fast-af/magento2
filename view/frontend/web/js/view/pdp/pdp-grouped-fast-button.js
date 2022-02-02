define(['fastButtonBase', 'jquery', 'ko', 'underscore'],
    function(Component, $, ko, _) {
        'use strict';

        return Component.extend({

            initialize: function() {
                var self = this;
                this._super();
                self.shouldShowFastButton = ko.observable(self.shouldShowFastButton());
                self.fastDark = ko.observable(self.fastConfig.getBtnTheme());
                self.fastAppId = ko.observable(self.fastConfig.getAppId());
                $(document).ready(function () {
                    self.maybeSetPdpWidth();
                    self.placePdpButton();
                });
            },
            pdpFastClick: function(data, e) {
                var self = this;

                // get the form node via jquery
                var productForm = $('form#product_addtocart_form');

                // validating form
                var validForm = productForm.validation('isValid');

                if (validForm) {
                    // construct a FormData object from the form node
                    // and extract the selected options
                    var formData = new FormData(productForm[0]);

                    var options = [];
                    var productOptions = [];

                    for (var pair of formData.entries()) {

                        if (pair[0].includes('super_group')) {
                            var productQty = Number(pair[1]);
                            console.log('productQty: ' + productQty);
                            if (productQty > 0) { // Fast.js only allows products with a quantity > 0
                                console.log('qty > 0'.productQty);
                                productOptions.push({
                                    id: pair[0].replace(/\D/g, ''),
                                    options: options,
                                    quantity: productQty
                                });
                            }
                        }

                    }
                    // Bail if Fast is not loaded
                    if (typeof Fast !== 'function') {
                        console.error('Fast not loaded, please reload the page and try again.');
                        return false;
                    }
                    // fast checkout
                    Fast.checkout({
                        appId: self.fastConfig.getAppId(),
                        buttonId: event.target.id,
                        products: productOptions
                    });
                }
                return true;
            },
        });
    });
