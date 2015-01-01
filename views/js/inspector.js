function selectElem(obj) {
    window.opener.document.getElementById('param_40594').value = $(obj).getPath();
    window.close();
}

$.fn.getPath = function () {
    if (this.length != 1)
        throw 'Requires one element.';

    var path, node = this;
    while (node.length) {
        var realNode = node[0], name = realNode.localName;
        if (!name)
            break;

        name = name.toLowerCase();
        if (realNode.id) {
            // As soon as an id is found, there's no need to specify more.
            return name + '#' + realNode.id + (path ? '>' + path : '');
        } else if (realNode.className) {
            name += '.' + realNode.className.split(/\s+/).join('.');
        }

        var parent = node.parent(), siblings = parent.children(name);
        if (siblings.length > 1)
            name += ':eq(' + siblings.index(node) + ')';
        path = name + (path ? '>' + path : '');

        node = parent;
    }

    return path;
};

$('.div_selector_button').live('click', function (eve) {

    var url = $("input[name=test_url]").val();
    var id = $(this).next().attr("id");

    var newUrl = prompt("URL to select element ID:", url);

    if (newUrl != '' && newUrl != null)
        window.open(globalHttpBaseUrl + '/apps/post/?url=' + newUrl + '&src=' + id + '', 'fbl', 'location=no,menubar=no,toolbar=no,top=0,left=0,scrollbars=yes');
});

$(document).ready(
	function ()
	{
		$('a').each(function()
		{
			var href = $(this).attr('href');
			var search = this.search;

			var href_add = 'live_edit_token=' + get('live_edit_token')
				+ '&id_shop=' + get('id_shop')
				+ '&id_employee=' + get('id_employee');

			var baseDir_ = baseDir.replace('https', 'http');

			if (typeof(href) != 'undefined' && href.substr(0, 1) != '#' && href.replace('https', 'http').substr(0, baseDir_.length) == baseDir_)
			{
				if (search.length == 0)
					this.search = href_add;
				else
					this.search += '&' + href_add;
			}
		});

	}
);

function get(name)
{
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.href);

	if (results == null)
		return "";
	else
		return results[1];
}
