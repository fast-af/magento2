define(['uiComponent', 'fastConfig', 'jquery'],
    function(Component, fastConfigFactory, $) {
        'use strict';

        return Component.extend({

            fastConfig: fastConfigFactory(),
            maybeSetPdpWidth: function() {
                if (this.fastConfig.shouldSetPdpButtonWidth()) {
                    $("#pdp-fast-button").css({
                        'width': ($("#product-addtocart-button").outerWidth() + 'px')
                    });
                }
            },
            placePdpButton: function() {
                // Prepend the PDP button to the first instance of the add to cart actions div.
                $("#pdp-fast-button").prependTo($(".box-tocart .fieldset .actions").first());
            },
            isFastDarkTheme: function() {
                return this.fastConfig.getBtnTheme() === 'dark';
            },
            shouldShowFastButton: function() {
                return this.fastConfig.shouldShowFastOnPDP();
            }
        });
    });
