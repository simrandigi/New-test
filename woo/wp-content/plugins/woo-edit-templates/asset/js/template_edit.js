jQuery(document).on( 'click', '.wooet-syntax_highlighting_description-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'wooet_dismiss_syntax_highlighting_description'
        }
    });
});
jQuery(document).on( 'click', '.wooet-rate_plugin-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'wooet_temporary_dismiss_rate'
        }
    });
});
jQuery(document).on( 'click', 'a.wooet-rating', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'wooet_dismiss_rate'
        }
    });
});
jQuery(document).on( 'click', '.wooet-child_theme-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'wooet_child_theme'
        }
    });
});
