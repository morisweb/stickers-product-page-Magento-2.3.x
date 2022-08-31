define(
    [
        'jquery'
    ],
    function ($) {
        $.widget(
            'webdev.productPageSticker',
        {
            _create: function (){
            var self = this;
            $(".product.media").append(self.options.imageTag.imagePath);
            }
        }
    );
    return $.webdev.productPageSticker;
    }
);