define(['uiComponent'],
    function(Component) {
        'use strict';

        return Component.extend({

            fastConfig: function() {
                return fastConfigFactory();
            },
            isFastDarkTheme: function() {
                return this.fastConfig.getBtnTheme() === 'dark';
            },
            shouldShowFastButton: function() {
                return this.fastConfig.shouldShowFastOnPDP();
            }
        });
    });
