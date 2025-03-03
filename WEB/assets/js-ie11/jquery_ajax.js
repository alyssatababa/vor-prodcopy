'use strict';

// Needs JQUERY
/*
	additional_configs can be found in:
	https://www.w3schools.com/jquery/ajax_ajax.asp
	http://api.jquery.com/jquery.ajax/
*/
function ajax_request(ajax_type, url, param, success_function) {
	var additional_configs = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : {};

	var ajax_config = {
		type: ajax_type, /* POST or GET or any other HTTP Methods */
		url: url, /* controller URL */
		data: param, /* POST or GET data to be passed to controller */
		error: function error(jqXHR, textStatus, errorThrown) {
			console.log(textStatus + ': ' + errorThrown);
		},
		success: function success(responseText) {
			/* Do something */
			success_function(responseText);
		}
	};

	// if there additional configs, add to main config before processing
	for (config in additional_configs) {
		ajax_config[config] = additional_configs[config];
	};

	// console.log(ajax_config);

	return $.ajax(ajax_config);
}