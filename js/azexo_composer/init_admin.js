window.azexo_baseurl = baseUri + 'modules/sliderseverywhere/js/azexo_composer/';
window.azexo_prefix = '';
window.azexo_editor = true;
window.azexo_online = false;
//window.azexo_exporter = false;
$(function () {
    var matches = (decodeURIComponent(window.opener.$('#builder').val()).replace(/\+/g, ' ')).match(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi);
    if(matches != null)
        $('.az-style').html(matches[0]);
    else
        $('.az-style').html('');
    $('#builder').html(decodeURIComponent(window.opener.$('#builder').val()).replace(/\+/g, ' ').replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, ''));
})
$(document).ready(function () {

    $('.edit-layer').click(function () {
        $('div.az-element.az-ctnr.az-column.col-sm-12.ui-sortable > div.az-element.az-layers > div.controls.btn-group.btn-group-xs > button.control.edit').click();
    })
    $('.add-to-layer').on('click', '', function () {
        $('#center_column > div.az-container-case > div > div.az-element.az-row.row.ui-sortable > div.az-element.az-ctnr.az-column.col-sm-12.ui-sortable > div.az-element.az-layers > div.controls.btn-group.btn-group-xs > button.control.add.btn.btn-default.glyphicon.glyphicon-plus').click();
    })
    $('.switch-options').on('click', '', function () {
        $('.toggle-editor').click();
    })
    $('.help-layer').on('click', function (e) {
        e.preventDefault();
        $('body').chardinJs('start');
    })
    if (window.opener.$('#builder').data('help') == '1')
        $('.help-layer').click();

    //edite layer
    //$('.owl-item:eq('+$( '.owl-page').index($('.active'))+')').find('.az-layers:first').children('.controls').find('.edit').click();
    ////copy element
    //$('.owl-item:eq('+$('.owl-page').index($('.owl-page.active'))+')').find('.az-layers:first').children('.controls').find('.paste').click();
    $('#builder').azexo_composer();
})