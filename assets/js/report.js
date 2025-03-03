
function generate_report_pdf(url, report, date_from, date_to, cat_filter){
    //console.log(url);
    //console.log(report);
    //console.log(date_from);
    //console.log(date_to);
    //console.log(cat_filter);
    var generate_url = url + "index.php/vendor/vendor_reports/generate_report_excel/" + report + "/" + date_from +"/" + date_to +"/" + cat_filter;
    //console.log(generate_url);
    document.form1.action = generate_url;
    document.form1.target = "_self";
    document.form1.submit();
    
}

$(document).on("click", "#btn_download", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        $("#select_report").parent('div').removeClass('has-error')
        $("#date_created_from").parent('div').removeClass('has-error');
        $("#date_created_to").parent('div').removeClass('has-error');

        var base_url = $('#b_url').attr('data-base-url');
        var report = $("#select_report").val();
        var date_from = $("#date_created_from").val();
        var date_to = $("#date_created_to").val();
		var cat_filter = $('#cat_filter').find(":selected").val();
        var iError = 0;
        var sError = 0;
        var spanMsg= '';

		//Added JRM - June 16 2021
		if(report == 5){
			var delType = $("#delType").val();
			generate_report_pdf(base_url, report, delType,'noneed', cat_filter);
			return false;
		}
		//Added JRM - June 23, 2021
		if(report == 6){
			if(userID.length == 0){
				$("#userError").html("Please select username first.");
				$("#userError").fadeIn();
				return false;
			}
			$("#postIDs").val(JSON.stringify(userID));
			generate_report_pdf(base_url, report, 'noneed','noneed', cat_filter);
			return false;
		}
		//Added JRM - June 25 2021
		if(report == 7){
			if(vCodes.length == 0){
				$("#userError").html("Please select vendor first.");
				$("#userError").fadeIn();
				return false;
			}
			$("#postIDs").val(JSON.stringify(vCodes));
			generate_report_pdf(base_url, report, 'noneed','noneed', cat_filter);
			return false;
		}

		if(report == 8){
			if(userID.length == 0){
				$("#userError").html("Please select username first.");
				$("#userError").fadeIn();
				return false;
			}
			$("#postIDs").val(JSON.stringify(userID));
			generate_report_pdf(base_url, report, 'noneed','noneed', cat_filter);
			return false;
		}

		if(report == 9){			
			var c_date_from = $("#c_date_created_from").val();
			var c_date_to = $("#c_date_created_to").val();

			if (c_date_from.length == 0){
				sError++;
			}

			if (c_date_to.length == 0){
				sError++;
			}
			
			if(sError > 0){
				$("#postIDs").val(JSON.stringify(vCodes));
				generate_report_pdf(base_url, report, 'noneed','noneed', cat_filter);
				return false;
			}else{
				$("#postIDs").val(JSON.stringify(vCodes));
				generate_report_pdf(base_url, report, c_date_from,c_date_to, cat_filter);
				return false;
			}
		}
		
        if($( "#date_created_from" ).datepicker({ dateFormat: 'yy-mm-dd' }).val().length > 10){
            $("#date_created_from").parent('div').addClass('has-error');
            sError++;
        }

        if($( "#date_created_to" ).datepicker({ dateFormat: 'yy-mm-dd' }).val().length > 10){
            $("#date_created_to").parent('div').addClass('has-error');
            sError++;
        }


        if(sError > 0){
            var span_message = '<strong>Failed!</strong> Please enter a valid value. The field has an invalid date!';
            var type = 'danger';
            notify(span_message, type);   
            return;
        }


        if (report == 0){
            $("#select_report").parent('div').addClass('has-error')
           
            iError++;
        }

        if (date_from.length == 0){
            $("#date_created_from").parent('div').addClass('has-error');
          
            iError++;
        }

        if (date_to.length == 0){
            $("#date_created_to").parent('div').addClass('has-error');
        
            iError++;
        }



        var d1 = new Date(date_from);
        var d2 = new Date(date_to);



        if(d1 > d2){  
        var span_message = '<strong>Failed!</strong> Invalid date range.';
        var type = 'danger';
        notify(span_message, type);   
        return;
        }

        if(iError > 0){
            var span_message = '<strong>Failed!</strong> Please enter a valid value. The field is incomplete or has an invalid date!';
            var type = 'danger';
            notify(span_message, type);            
        }else{
            $("#select_report").parent('div').removeClass('has-error')
            $("#date_created_from").parent('div').removeClass('has-error');
            $("#date_created_to").parent('div').removeClass('has-error');
            generate_report_pdf(base_url, report, date_from, date_to, cat_filter);
        }
    }
});

$(document).on("click", "#btn_search", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        search_filter();

    }
});
//added JRM June 21 2021
$(document).on("click", "#addUser", function (e) {
	e.preventDefault();
    if (e.handled !== true) {
		var report = $("#select_report").val();
		var val = $('#userFilter').find(':selected');
		var arr = "";
		var lid = "";
		if(report == 6){
			arr = userID;
			lid = "lou";
		}else if(report == 7){
			arr = vCodes;
			lid = "lov";
		}else if(report == 8){
			arr = userID;
			lid = "lou";
		}else if(report == 9){
			arr = vCodes;
			lid = "lov";
		}
		if(arr.includes(val.val())){
		
		}else{
			
			$("#userError").html($("#userFilter").select2('data')[0].text+" is now added to the list");
			$("#userError").fadeIn()
			arr.push(val.val());
			$("#"+lid).append("<li id='"+val.val()+"'>"+val.text()+" <i class='glyphicon glyphicon-remove' title='Remove' style='cursor:pointer;float:right;' onclick=removeUser('"+val.val()+"') ></i></li>");
		}
	
	}
});




var userID = []; //added by JRM June 22, 2021
var vCodes = []; //added by JRM June 25, 2021

$("#select_report").change(function(){
	$("#userFilter").html("");
	$("#userError").hide();
	if($(this).val() == 6){
		$("#selectLabel").html("Select Usernames: ");
		$("#user-con").show();
		$("#vendor-con").hide();
		getUsers();
	}else if($(this).val() == 7){
		$("#selectLabel").html("Select Vendors: ");
		$("#user-con").hide();
		$("#vendor-con").show();
		getvCodes();
	}else if($(this).val() == 8){
		$("#selectLabel").html("Select Usernames: ");
		$("#user-con").show();
		$("#vendor-con").hide();
		get_active_inactive_users(0,2);
	}else if($(this).val() == 9){
		$("#selectLabel").html("Select Vendors: ");
		$("#user-con").hide();
		$("#vendor-con").show();
		get_contact_persons("01011999|||||01011999");
	}
});

$("#c_date_created_from").keyup(function(){
	var date = $("#c_date_created_from").val() + "|||||" + $("#c_date_created_to").val();
	get_contact_persons(date);
});

$("#c_date_created_to").keyup(function(){
	var date = $("#c_date_created_from").val() + "|||||" + $("#c_date_created_to").val();
	get_contact_persons(date);
});

$("#userFilter").change(function(){
	var report = $("#select_report").val();
	var arr = "";
	if(report == 6)
		arr = userID;
	else if(report == 7)
		arr = vCodes;
	else if(report == 8)
		arr = userID;
	
	if(arr.includes($(this).val())){
		
		$("#userError").html($(this).select2('data')[0].text+" is already listed");
		$("#userError").fadeIn();
	}else{
		$("#addUser").attr("disabled",false);
		$("#userError").hide();
	}
});
function removeUser(id){
	var report = $("#select_report").val();
	var arr = "";
	if(report == 6)
		arr = userID;
	else if(report == 7)
		arr = vCodes;
	else if(report == 8)
		arr = userID;
	else if(report == 9)
		arr = vCodes;
			
	$("#"+id).remove();
	var index = arr.indexOf(id);
	if (index > -1) {
	  arr.splice(index, 1);
	}
	if(id == $("#userFilter").val()){
		$("#addUser").attr("disabled",false);
		$("#userError").hide();
	}
	
}
function search_filter(){
    var report = $("#select_report").val();

    $("#select_report").parent('div').removeClass('has-error')
    $("#date_created_from").parent('div').removeClass('has-error');
    $("#date_created_to").parent('div').removeClass('has-error');

    if (report > 0){
        $("#div_filter").show();
        $("#btn_download").prop('disabled', false);
    }else{
        // var span_message = 'Please select a report!';
        // var type = 'danger';
        // notify(span_message, type);
        $("#div_filter").hide();
        $("#btn_download").prop('disabled', true);
    }
	
	//Added JRM - June 17 2021
	$("#delFilter").hide();
	$("#dateFilter").show();
	$("#report_filter").text("");

		
		//Added RM - June 21, 2021
		$("#pendingFilter").hide();
		$("#complete_date_filter").hide();
		
		
    if (report == 1){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").hide();
        $("#report_filter").text("Date Expired :");
    }else if (report == 2){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").hide();
        $("#report_filter").text("Deactivation Date :");
    }else if (report == 3){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").hide();
        $("#report_filter").text("Completed Date :");
    }else if (report == 4){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").hide();
        $("#report_filter").text("Date Range :");
    }else if (report == 5){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").hide();
		$("#delFilter").show();
		$("#dateFilter").hide();
    }else if (report == 6 || report == 7){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").hide();
		$("#pendingFilter").show();
		$("#dateFilter").hide();
	}else if(report == 8){
		$("#reportEightFilter").show();
		$("#complete_date_filter").hide();
		$("#pendingFilter").show();
		$("#dateFilter").hide();
	}else if(report == 9){
		$("#reportEightFilter").hide();
		$("#complete_date_filter").show();
		$("#pendingFilter").show();
		$("#dateFilter").hide();
	}
}

function getUsers(){
	$.post(BASE_URL+'vendor/vendor_reports/get_usernames/',{},function(res){
		$.each(res,function(i,v){
			$("#userFilter").append("<option value='"+v.USER_ID+"'>["+v.USERNAME+"] "+v.USER_FIRST_NAME+" "+v.USER_LAST_NAME+"</option>");
		});
	}).done(function(){
		
		$("#userFilter").select2();
	});
	
	
}

function getvCodes(){
	$.post(BASE_URL+'vendor/vendor_reports/get_vendor_codes/',{},function(res){
		$.each(res,function(i,v){
			$("#userFilter").append("<option value='"+v.VENDOR_CODE+"'>["+v.VENDOR_CODE+"] "+v.VENDOR_NAME+"</option>");
		});
	}).done(function(){
		
		$("#userFilter").select2();
	});
	
	
}

function get_active_inactive_users(userType, userStatus){
	$.post(BASE_URL+'vendor/vendor_reports/get_active_inacvtive_user/'+userType+'/'+userStatus+'/',{},function(res){
		$("#userFilter").append("<option value='"+userType+"zzzz"+userStatus+"zzzzALL'>ALL USERS/VENDOR</option>");
		$.each(res,function(i,v){
			$("#userFilter").append("<option value='"+v.USER_ID+"'>["+v.USERNAME+"] "+v.USER_FIRST_NAME+" "+v.USER_LAST_NAME+"</option>");
		});
	}).done(function(){
		$("#userFilter").select2();
	});
}

function get_contact_persons(date){
	$.post(BASE_URL+'vendor/vendor_reports/get_contact_persons/'+date+'/',{},function(res){
		if(res.status != false){
			removeOptions(document.getElementById('userFilter'));
			//$("#userFilter").append("<option value='ALL'>ALL</option>");
			$.each(res,function(i,v){
				if(v.VENDOR_CODE == "ALL"){
					$("#userFilter").append("<option value='ALL'>ALL</option>");
				}else{
					$("#userFilter").append("<option value='"+v.VENDOR_CODE+"'>["+v.VENDOR_CODE+"] "+v.VENDOR_NAME+"</option>");
				}
					
			});	
		}
	}).done(function(){
		$("#userFilter").select2();
	});
}

function generate_users(){	
	var userType = $("#userType").val();
	var userStatus = $("#userStatus").val();
	removeOptions(document.getElementById('userFilter'));
	get_active_inactive_users(userType,userStatus);
}

function removeOptions(selectElement) {
   var i, L = selectElement.options.length - 1;
   for(i = L; i >= 0; i--) {
      selectElement.remove(i);
   }
}