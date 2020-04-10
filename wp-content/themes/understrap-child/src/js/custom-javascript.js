//Search form in header show/hide behavior
jQuery(function () {
    jQuery('a[href="#search"]').on('click', function(event) {
        event.preventDefault();
        jQuery('#search').addClass('open');
        jQuery('#s').focus();
    });
    
    jQuery('#search, #search button.close').on('click keyup', function(event) {
        if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
            jQuery(this).removeClass('open');
        }
    });
});

//Products page modal image slider: ensures that the particular thumbnail clicked displays first in the modal
function goToSlide(number) {
   jQuery("#productImageModal").carousel(number);
}