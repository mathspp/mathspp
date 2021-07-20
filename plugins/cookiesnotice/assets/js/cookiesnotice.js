(function($){
    $.showCookiesNotice = function(cookieText, linkHref, linkText, dismissText, cookieStyle) {
        if ($.cookie('cookienotice')!='accepted') {
            var cookieHtml = '<div class="cookienotice">';
                cookieHtml += cookieText;
                cookieHtml += '<div class="cookiewrap"></div><a href="'+linkHref+'">'+linkText+'</a>';
                cookieHtml += '<a href="javascript:$.closeCookieNotice()">'+dismissText+'</a>';
                cookieHtml += '</div>';
            $("body").append(cookieHtml);
            if (cookieStyle=="top") {
                $('.cookienotice').css({
                    'top': '0px'
                });
            }
            else {
                $('.cookienotice').css({
                    'bottom': '0px'
                });
            }
        }
        return this;
    };
    $.closeCookieNotice = function() {
        $("div.cookienotice").remove();
        $.cookie('cookienotice', 'accepted', { expires: 365, path: '/' });
    };
})(jQuery);
