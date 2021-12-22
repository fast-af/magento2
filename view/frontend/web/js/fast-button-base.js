define(['uiComponent'],
    function(Component) {
        'use strict';

        return Component.extend({

            fastConfig: function() {
                return fastConfigFactory();
            },
            isFastDarkTheme: function() {
                return self.fastConfig.getBtnTheme() === 'dark';
            },
            shouldShowFastButton: function() {
                return self.fastConfig.shouldShowFastOnPDP();
            }
        });
    });
