window.azexo_baseurl = baseUri + 'modules/sliderseverywhere/views/js/azexo_composer/';
window.azexo_prefix = '';
window.azexo_editor = true;
window.azexo_online = false;
$(document).ready(function () {
    $('#example').html((decodeURIComponent(window.opener.$('#builder').val())).replace(/\+/g,' '));
    console.log((decodeURIComponent(window.opener.$('#builder').val())).replace(/\+/g,' '));
    $('.edit-layer').click(function () {
        $('div.az-element.az-ctnr.az-column.col-sm-12.ui-sortable > div.az-element.az-layers > div.controls.btn-group.btn-group-xs > button.control.edit').click();
    })
    $('.edit-layer').on('click', '', function () {

    })
    
    $('#example').azexo_composer();

    //edite layer
    //$('.owl-item:eq('+$( '.owl-page').index($('.active'))+')').find('.az-layers:first').children('.controls').find('.edit').click();
    ////copy element
    //$('.owl-item:eq('+$('.owl-page').index($('.owl-page.active'))+')').find('.az-layers:first').children('.controls').find('.paste').click();

})