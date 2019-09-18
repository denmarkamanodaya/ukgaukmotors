function hide_breadcrumbs()
{
    if ( hideBreadcrumbs ) {
        $('.page-breadcrumbs-container').hide();
    }
}

$( document ).ready(function() {
    hide_breadcrumbs();
});

jQuery(document).ready(function($) {
    var _ouibounce = ouibounce(document.getElementById('ouibounce-modal'), {
        aggressive: false,
        timer: 0,
        cookieName: '',
        callback: function() { console.log('ouibounce fired!'); }
    });

    $('body').on('click', function() {
        $('#ouibounce-modal').hide();
    });

    $('#ouibounce-modal .ouimodal-footer').on('click', function() {
        $('#ouibounce-modal').hide();
    });

    $('#ouibounce-modal .ouimodal').on('click', function(e) {
        e.stopPropagation();
    });


});

