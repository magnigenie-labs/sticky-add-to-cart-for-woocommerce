jQuery(document).ready(function($) {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        if (wsac.mobile_enable === 'no') {
            $('.mg-wsac-fix-sticky-bar').remove();
        }
    }
    var lastScrollTop = 0;
    $(window).scroll(function() {
        el = $("button[name='add-to-cart']");
        if (el.length) {
            btn_top = el.offset().top;
            btn_bottom = btn_top + el.outerHeight();
        }
        var st = $(this).scrollTop();
        scroll_bottom = st + $(this).height();
         if (typeof btn_bottom != 'undefined' && typeof btn_top != 'undefined') {
                if (!(btn_bottom > st) && (btn_top < scroll_bottom)) {
                    $('.mg-wsac-fix-sticky-bar').slideDown('slow');
                } else {
                    $('.mg-wsac-fix-sticky-bar').slideUp('slow')
                }
            } else
                $('.mg-wsac-fix-sticky-bar').slideDown('slow');
        lastScrollTop = st;
    });
    $('.mg-wsac-btn').click(function() {
        var dis = $(this);
        var id = $(this).find('input').attr('data-productID');
        $.ajax({
            type: 'POST',
            url: wsac.ajaxurl,
            data: {
                action: 'wsac_to_cart',
                id: id
            },
            dataType: 'json',
            beforeSend: function() {
                $(dis).find('.mg-wsac-shopping').removeClass('mg-wsac-shopping-bag');
                $(dis).prepend('<i class="mg-spin fa fa-spinner fa-spin"></i>');
                $(dis).find('.cart-text').html('Wait..');
                $(dis).attr('disabled', 'true')
            },
            success: function(response) {
                if (response.success) {
                    $(dis).find('.mg-spin').remove();
                    $(dis).find('.cart-text').html('ADDED');
                    $(dis).prepend('<i class="fa fa-check stky-check"></i>');
                    setTimeout(function() {
                        $(dis).find('.stky-check').remove();
                        $(dis).find('.cart-text').html(wsac.btn_message);
                        $(dis).removeAttr('disabled');
                        $(dis).find('.mg-wsac-shopping').addClass('mg-wsac-shopping-bag');
                    }, 1000);
                }
            }
        })
    });
    var star = $('.mg-wsac-fix-sticky-bar .rateyo').data('star');
    $(".mg-wsac-fix-sticky-bar .rateyo").rateYo({
        numStars: 5,
        rating: star,
        starWidth: "20px",
        normalFill: wsac.star_background,
        ratedFill: wsac.star_color,
        readOnly: true
    });
});