$(document).ready(function () {
	
	setInterval(function(){
		try{
			if($("#unlock_countdown").html()){
				var unlock_time =  parseInt($("#unlock_countdown").text());
				if(unlock_time > 0){
					if(unlock_time == 1){
						$("#locked_message").html("You can now login " + $("#current_input_username").val());
						$("#login_form").reset(); 
					}else{
						$("#unlock_countdown").text(unlock_time - 1);
					}
				}
			}
		}catch(e){
			
		}
	},1000);
	
	if ($('#hidden_destroy_local_storage').val()==1){
		localStorage.removeItem('user_session');
		localStorage.removeItem('username');
		localStorage.removeItem('password');
		localStorage.removeItem('remember_me');
	}

	if (localStorage.getItem("remember_me") == 1) {
		$('#input_remember_me').prop('checked', true);

		$('#input_username').val(localStorage.getItem("username"));
		$('#input_password').val(localStorage.getItem("password"));
	}
});
