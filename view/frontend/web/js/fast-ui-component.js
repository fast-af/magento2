define(['uiComponent', 'jquery', 'ko', 'underscore', 'fastConfig'],
    function(Component, $, ko, _, fastConfigFactory) {
        'use strict';

        var fastConfig = fastConfigFactory();

        return Component.extend({

            initialize: function() {
                var self = this;
                this._super();
                self.shouldShowFastButton = ko.observable(fastConfig.shouldShowFastOnPDP());
                self.fastDark = ko.observable(fastConfig.getBtnTheme());
                $(document).ready(function () {
                    $("#pdp-fast-button").css({
                        'width': ($("#product-addtocart-button").outerWidth() + 'px')
                    });
                    $("#pdp-fast-button").prependTo(".box-tocart .fieldset .actions");
                });
            },
            isFastDarkTheme: function() {
                return fastConfig.getBtnTheme() === 'dark';
            },
            shouldShowFastButton: function() {
                return fastConfig.shouldShowFastOnPDP();
            }
        });
    });
