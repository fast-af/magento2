define(['uiComponent', 'fastConfig'],
    function(Component, fastConfigFactory) {
        'use strict';

        return Component.extend({

            fastConfig: fastConfigFactory(),
            isFastDarkTheme: function() {
                return this.fastConfig.getBtnTheme() === 'dark';
            },
            shouldShowFastButton: function() {
                return this.fastConfig.shouldShowFastOnPDP();
            }
        });
    });
