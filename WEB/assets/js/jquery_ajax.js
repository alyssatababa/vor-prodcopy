// Needs JQUERY
/*
	additional_configs can be found in:
	https://www.w3schools.com/jquery/ajax_ajax.asp
	http://api.jquery.com/jquery.ajax/
*/
function ajax_request(ajax_type, url, param, success_function, additional_configs = {})
{
	var ajax_config = {
		type: ajax_type, /* POST or GET or any other HTTP Methods */
		url: url, /* controller URL */
		data: param,/* POST or GET data to be passed to controller */
		cache: false, 
		headers: { "cache-control": "no-cache" },
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus + ': ' + errorThrown);
		},
		success: function(responseText) {

			let nx = responseText.split('|');

			if(nx[0] == 'no_session'){
			$('#no_session').modal('show');
				return;
			}else{
				success_function(responseText);
				/* Do something */
			}				
		}
	};

	// if there additional configs, add to main config before processing
	for (config in additional_configs) {
		ajax_config[config] = additional_configs[config];
		
	};

	// console.log(ajax_config);

	return $.ajax(ajax_config);
}