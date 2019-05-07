/**
 * Ajax call to our web service, with the specified action to be done. Returns the jQuery XHR Object with the done()
 * property in which we'll define the callback on success or whatever.
 *
 * @param {object} param
 * @param {string} param.action
 * @param {object} param.data
 *
 * @author charlesokolms
 * @version 0.1.1
 * @since 0.1
 *
 * @return {JQuery.jqXHR|boolean} False if not called. JQuery.jqXHR object if the call succeeded.
 */
function ws(param) {
	if (!param.action || param.data === undefined) {
		return false;
	}
	return $.post({
		url: 'ws.php',
		data: {
			'action': param.action,
			'data': param.data || {}
		},
		dataType: 'json'
	});
}

function mtgReplaceManaSymbols(str){
	return str.replace(/{(.*?)}/g, "<i class=\"ms ms-cost ms-$1\"></i>").replace('ms-T', 'ms-tap');
}
