require([
        'jquery',
        'fastConfig',
        'mage/validation'
    ],
    function (
        $,
        fastConfigFactory
    ) {
        var fastConfig = fastConfigFactory();
        var $checkoutButton = document.querySelector('#fast-button');
        $(document).ready(function () {
            $(".product-add-form form .fast-button-wrapper").css({
                'width': ($("#product-addtocart-button").width() + 'px')
            });
            $(".product-add-form form .fast-button-wrapper").prependTo(".box-tocart .fieldset .actions");
        });

        $checkoutButton.addEventListener('click', event => {
            // get the form node via jquery
            var productForm = $('form#product_addtocart_form');

            // validating form
            var validForm = productForm.validation('isValid');

            if (validForm) {
                // construct a FormData object from the form node
                // and extract the selected options
                var formData = new FormData(productForm[0]);

                var options  = [];
                var productOptions = [];
                productOptions.push({
                        id: formData.get('product'),
                        options: options,
                        quantity: Number(formData.get('qty'))
                    });

                for(var pair of formData.entries()) {
                    if(pair[0].includes('super_attribute')) {
                        productOptions = [];
                        options.push({
                            id   : pair[0].replace(/\D/g, ''),
                            value: pair[1],
                        });
                        productOptions.push({
                            id   : formData.get('product'),
                            options: options,
                            quantity: Number(formData.get('qty'))
                        });
                       // break;
                    }
                    if(pair[0].includes('bundle_option')) {
                        console.log('bundle_option found');
                        productOptions.push({
                            id   : pair[1],
                            options: [],
                            quantity: Number(formData.get('qty'))
                        });
                    }
                }
                // Bail if Fast is not loaded
                if (typeof Fast !== 'function') {
                    console.error('Fast not loaded, please reload the page and try again.');
                    return false;
                }
                // fast checkout
                Fast.checkout({
                    appId: fastConfig.getAppId(),
                    buttonId: event.target.id,
                    products: productOptions
                });
            }
        });
    });