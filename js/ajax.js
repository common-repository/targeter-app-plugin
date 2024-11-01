(function($) {
    $(function() {
        var content = $('.entry-content');
        if(!content.length) {
            content = $('.wrap');
            TG_AJAX.ajaxurl = ajaxurl;
        }
        content.click(function() {
            $.get(
                TG_AJAX.ajaxurl,
                {
                    // here we declare the parameters to send along with the request
                    // this means the following action hooks will be fired:
                    // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
                    action : 'tg_ajax',

                    // send the nonce along with the request
                    nonce : TG_AJAX.nonce
                },
                function( response ) {
                  //  content.append('<p>'+response+'</p>');
                }
            );
        });
    });
})(jQuery);