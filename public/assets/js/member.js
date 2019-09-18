
function hide_breadcrumbs()
{
    if ( hideBreadcrumbs ) {
        $('.page-breadcrumbs-container').hide();
    }
}

$( document ).ready(function() {
    hide_breadcrumbs();
    if (jQuery(".sidebar>ul").length != '') {
        //jQuery('.sidebar>ul').slicknav();
    }
});


