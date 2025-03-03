
function generate_report_pdf(url, report, date_from, date_to, cat_filter){
    console.log(url);
    console.log(report);
    console.log(date_from);
    console.log(date_to);
    console.log(cat_filter);
    var generate_url = url + "index.php/vendor/vendor_reports/generate_report_excel/" + report + "/" + date_from +"/" + date_to +"/" + cat_filter;
    console.log(generate_url);
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
        var date_to = $("#date_created_to").val();
		var cat_filter = $('#cat_filter').find(":selected").val();
        var iError = 0;
        var sError = 0;
        var spanMsg= '';

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

    if (report == 1){
        $("#report_filter").text("Date Expired :");
    }else if (report == 2){
        $("#report_filter").text("Deactivation Date :");
    }else if (report == 3){
        $("#report_filter").text("Completed Date :");
    }else if (report == 4){
        $("#report_filter").text("Date Range :");
    }    
}
