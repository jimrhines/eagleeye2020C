jQuery(document).ready(function ($) {

    //console.log('simple-sitemap.js', el[0].innerHTML);

    // if we are in the editor then wait 1 second before 
    if (document.querySelector('body').classList.contains('wp-admin')) {
        //console.log('SSP - ADMIN');
        setTimeout(removeChar, 1000);
    } else {
        //console.log('SSP - FRONT END');
        removeChar();
    }

    function removeChar() {
        // for horizontal sitemap remove trailing chracter from last item (page post type only)
        const el = $('ul.simple-sitemap-page.horizontal li:last');

        $(el).html(function (index, html) {
            return html.substr(0, html.length - 2);
        });
    }

});