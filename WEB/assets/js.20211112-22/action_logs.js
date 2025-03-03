var $tbl_actions_logs = $('#actions_logs');
var $action_logs_pages = $('#action_logs_pages');
var $action_logs_pagination = new Pagination($action_logs_pages, $tbl_actions_logs, 'sort_columns');

function get_action_logs(user_id = '')
{
	var ajax_type = 'GET';
	var url = 'login/get_user_logs';
	var params = {
		user_id: user_id
	}
	var success_function = function(responseText) {
		var rs = $.parseJSON(responseText);

		$action_logs_pagination.create(rs, 'ResultSet');
		$action_logs_pagination.render();
	}

	ajax_request(ajax_type, url, params, success_function);
}

/*
	Check SMNTP_ACTIONS Table for list of actions.
	If needed action is not included, please insert a new row.
*/
function log_action(user_id = '', user_action_id, screen_id = '')
{
	var ajax_type = 'POST';
	var url = 'login/log_user_action';
	var params = {
		user_id: user_id,
		action_id: user_action_id,
		screen_id: screen_id
	}
	var success_function = function(responseText) {
		// console.log(responseText);
		get_action_logs(user_id);
	}

	return ajax_request(ajax_type, url, params, success_function);
}
$(document).ready(function() {

    $('.menu_item').on('click', function(event) {
		console.log('Screen ID = ' + $(this).data('screen-id') + ' | ' + 'Action ID = ' + $(this).data('action-id'));
		log_action('', $(this).data('action-id'), $(this).data('screen-id'));
		event.stopImmediatePropagation();
    });
	
});
get_action_logs($tbl_actions_logs.data('user-id'));