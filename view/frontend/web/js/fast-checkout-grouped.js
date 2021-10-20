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

                var options = [];
                var productOptions = [];

                for (var pair of formData.entries()) {

                    if (pair[0].includes('super_group')) {
                        console.log('super_group found');
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
                    appId: fastConfig.getAppId(),
                    buttonId: event.target.id,
                    products: productOptions
                });
            }
        });
    });