<style>
	/*override bootstrap design*/
	.container.mycontainer {
		-webkit-box-shadow: -1px 11px 37px 0px rgba(0,0,0,0.45);
		-moz-box-shadow: -1px 11px 37px 0px rgba(0,0,0,0.45);
		box-shadow: -1px 11px 37px 0px rgba(0,0,0,0.45);
	}
	.input-group {
		padding-bottom: 5px;
	}
	.nav>li>a.message_action:hover,
	.nav>li>a.message_action:focus {
		border: 0;
		background-color: transparent;
	}
	#mail_body {
		height: 400px;
	}
	#mail_body_new {
		height: 400px;
	}
	.sort_columns:hover,
	.user_mail {
		cursor: pointer;
	}
	#reply_box {
		height: 400px;
	}
	hr.mail_divider {
		border: none;
		height: 3px;
		/* Set the hr color */
		color: #E8E8E8; /* old IE */
		background-color: #E8E8E8; /* Modern Browsers */
	}
	#reply_box{
		overflow-y: scroll;
	}

	.unselectable {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
</style>

<!-- Modal -->
<div class="modal fade" id="new_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        		<div class="modal-header">
  					<h3 class="modal-title">
  						New Message
  						<div class="pull-right">
  							<button class="btn btn-default" id="send_message" onclick="send_message_notif(1);">Send</button>
  							<button class="btn btn-default" data-dismiss="modal" onclick = "clear_modal();">Back</button>
  						</div>
  					</h3>
        		</div>
        		<div class="modal-body">

  					<form id="new_message_form" class="form-horizontal">

	        			<div class="row">
	        				<div class="col-md-5">

									<!-- if user type is SM -->
									<?php if ($user_type_id === '1'): ?>
										<div class="form-group">
											<label for="recipient" class="col-sm-2 control-label">To</label>
											<div class="col-sm-10">
												<select name="recipient" id="recipient_new" class="form-control" required></select>
											</div>
										</div>
									<?php endif; ?>

									<div class="form-group">
										<label for="mail_subj" class="col-sm-2 control-label">Subject</label>
										<div class="col-sm-10">

										<!-- if user type is SM -->
										<?php if ($user_type_id === '1'): ?>
											<input type="text" name="mail_subj_new" id = "mail_subj_new" class="form-control" required>

										<!-- if user type is VENDOR -->
										<?php elseif ($user_type_id === '2'): ?>
											<select name="mail_subj" id="mail_subj_new" class="form-control" required></select>
										<?php endif; ?>

										</div>
									</div>

									<div class="form-group">
										<label for="mail_topic" class="col-sm-2 control-label">Topic</label>
										<div class="col-sm-10">
											<input type="text" name="mail_topic_new" id="mail_topic_new" class="form-control" required>
										</div>
									</div>

	        				</div>
	        			</div>

						<div class="row">
							<div class="col-md-12">
								<textarea name="mail_body" id="mail_body_new" class="form-control" placeholder="Enter message here..." required></textarea>
							</div>
						</div>

						<button type="submit" class="hide"></button>
					</form>

        		</div>
        </div>
    </div>
</div>
<!-- END OF MODAL -->

<!-- Modal -->
<div class="modal fade" id="reply_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        		<div class="modal-header">
        			<h3 class="modal-title">
        				<span id="title"></span>
        				<div class="pull-right">
        					<button class="btn btn-default .btn-send" id="send_message_reply" onclick = "send_message_notif()">Send</button>
        					<button class="btn btn-default .btn-send" data-dismiss="modal" onclick = "reset_replybox();">Back</button>
        					<input type = "hidden" id = "message_id" />
        					<input type = "hidden" id = "recipient_id" />
        				</div>
        			</h3>
        		</div>

        		<div class="modal-body">

					<form id="reply_message_form" class="form-horizontal">

	        			<div class="row">
	        				<div class="col-md-5">

								<div class="form-group">
									<label for="mail_subj" class="col-sm-2 control-label">Subject</label>
									<div class="col-sm-10">
										<input type="text" name="mail_subj" id="mail_subj" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="mail_topic" class="col-sm-2 control-label">Topic</label>
									<div class="col-sm-10">
										<input type="text" name="mail_topic_reply" id="mail_topic_reply" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="mail_recipient" class="col-sm-2 control-label">To</label>
									<div class="col-sm-10">
										<input type="text" name="mail_recipient" id="mail_recipient" class="form-control" readonly>
									</div>
								</div>

							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<!-- <div id="reply_box" class="form-control" contenteditable="true">
								<br class = "unselectable" />
									<span readonly contenteditable="false" disabled class = "unselectable">----------------------------------------------------------------------------------- </span>
									<br class = "unselectable" />
									<span id="previous_mail" readonly contenteditable="false" disabled class = "unselectable"></span>
								</div> -->

								<div id ="reply_box" class = "form-control">
									<div contenteditable="true" id = "real_reply">
										<br>
										<br>
									</div>
									<div contenteditable="false" class = "unselectable">
										<span readonly contenteditable="false" disabled class = "unselectable">-----------------------------------------------------------------------------------</span>
										<br>
										<br>
										<span id="previous_mail" readonly contenteditable="false" disabled class = "unselectable"></span>
									</div>

								</div>
							</div>
						</div>

  					</form>

        		</div>
        </div>
    </div>
</div>
<!-- END OF MODAL -->

<div class="container mycontainer">

	<input type="hidden" id="data_id" value="<?=$data_id?>">
	<input type="hidden" id="data_type" value="<?=$data_type?>">
	<input type="hidden" id="user_type_id" value="<?=$user_type_id?>">
	<input type = "hidden" id ="current_tab" value = "inbox" />

	<div class="row">
		<div class="col-xs-10 col-sm-10">
			<h4 class="margin-header-title">Messages / Notifications</h4>
		</div>
		<div class="col-xs-2 col-sm-2">
			<input type="button" class="btn btn-primary btn-sm float-right" value="Close" onclick="go_to_homepage()">
		</div>
	</div>
	<hr>

	<!-- search filter  -->
	<div class="row" id="filter_div">
		<div class="col-md-5">
			<div class="input-group">
				<span class="input-group-addon">Type</span>
				<select name="mail_type" id="mail_type" class="form-control messages_filter" data-filter-type="TYPE">
					<option value="all">All</option>
					<option value="message">Message</option>
					<option value="notification">Notification</option>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-addon" id="sender_filter">From</span>
				<select name="mail_sender" id="mail_sender" class="form-control messages_filter" data-filter-type="RECIPIENT_ID">
					<script id="senders_template" type="text/template">
							<option value="all">All</option>
						{{#rs}}
							<option value="{{USER_ID}}">{{USERS_NAME}}</option>
						{{/rs}}
					</script>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-addon">Subject</span>
				<select name="mail_subj_search" id="mail_subj_search" class="form-control messages_filter" data-filter-type="SUBJECT">
					<script id="subjects_template" type="text/template">
						<option value="all">All</option>
						{{#rs}}
							<option value="{{SUBJECT}}" data-subj-id="{{DATA_ID}}" data-subj-type="{{SUBJECT_TYPE}}" data-recipient-id="{{RECIPIENT_ID}}">{{SUBJECT}}</option>
						{{/rs}}
						<!-- <option value="others">Others</option> -->
					</script>
				</select>
			</div>
			<div class="input-group">
				<input type="text" id="mail_topic" class="form-control messages_filter" placeholder="Enter topic...">
				<span class="input-group-btn">
					<button id="search_topic" class="btn btn-primary" type="button">Search Topic</button>
				</span>
			</div>
		</div>
	</div>
	<!-- end search filter  -->

	<hr>

	<!-- message actions -->
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs message-actions">

				<li role="presentation" class="active">
					<a class="mailbox_action" data-mail-type="inbox" href="#" onclick = "return false;">Inbox <span class="badge unread_count"></span></a></li>
				<li role="presentation">
					<a class="mailbox_action" data-mail-type="outbox" href="#" onclick = "return false;">Sent Items</a>
				</li>
				<li role="presentation">
					<a class="mailbox_action" data-mail-type="archives" href="#">Archived</a></li>
				<li>
					<a class="message_action" href="#" data-toggle="modal" data-target="#new_message"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Message</a>
				</li>
				<li>
					<a class="message_action" id="archive_message" href="#" onclick = "return false;"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Archive</a>
				</li>

			</ul>
		</div>
	</div>
	<!-- end message actions -->

	<!-- messages  -->
	<div class="row" id = "message_div">
		<div class="col-md-12">
	        <div class="table-responsive">
				<table class="table table-hover" id="messages">
					<thead>
						<tr>
							<th data-col="ID"><input type="checkbox" name="select_all" id="select_all"></th>
							<th style="max-width: 250px;width: 250px;word-wrap: break-word;" data-col="SENDER_RECIPIENT" class ="a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "SENDER_RECIPIENT"><label>From</label>
									<span></span>
								</a>
							</th>
							<th style="max-width: 250px;width: 250px;word-wrap: break-word;" data-col="SUBJECT" class ="a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "SUBJECT"><label>Subject</label>
									<span></span>
								</a>
							</th>
							<th style="max-width: 250px;width: 250px;word-wrap: break-word;" data-col="TOPIC" class ="a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "TOPIC"><label>Topic</label>
									<span></span>
								</a>
							</th>
							<th data-col="MAIL_DATE_FORMATTED" class ="a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "DATE_SENT"><label>Date Received</label>
									<span></span>
								</a>
							</th> <!-- Old data-col : MAIL_DATE-->
							<th data-col="STATUS" class ="a_table_header sort_column sort_default" style = "width:100px;">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "IS_READ"><label>Status</label><span></span></a>
							</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
			<center>
		<div id="content_page"></div>
		<div id="inbox_outbox_pagination"></div>
	</center>
	</div>

	<!-- end messages  -->

</div>
<script type="text/template" id="new_temp">
    {{#ds}}
        <option data-pos = "{{POSITION_ID}}" value="{{POSITION_ID}}"> 
        {{POSITION_NAME}}
        </option>
    {{/ds}}
</script>  

<script>



var burl = $('#asset_url').val();
var sort = 'DESC';
var sort_type = 'DATE_SENT';
var user_type_id = document.getElementById('user_type_id').value;

	//$.getScript("<?=base_url().'assets/js/mail.js'?>");

	$(document).ready(function(){

		let nsubj = '';
				
	let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#mail_type').val(),
    	subject : nsubj,
    	status :'',
    	sort : sort,
    	sort_type : sort_type
    	};

		get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
	});


	$(document).on('click','#inbox_outbox_pagination .cl_pag',function(event){
		let n = this.dataset.pg;
		if($(this).attr('disabled') == 'disabled'){
			return;
		}

		$('#select_all').prop('checked',false);
		let sender = document.getElementById('mail_sender').value;
		let subj = $('#mail_subj_search').val();
		let mail_topic = document.getElementById('mail_topic').value;

		if(sender == 'all' || sender == 'All'){
			sender = '';
		}
		if(subj == 'all' || subj == 'All'){
			subj = '';
		}

		let m = (n-1) * 10;
		let post_params_mail = {
		start : m,
		length : m  + 10,
		message_type: $('#mail_type').val(),
		status : '',
		sort : sort,
		sort_type : sort_type,
		from : sender,
		subject : subj,
		search_topic : mail_topic
		};

		

		let curr_tab = document.getElementById('current_tab').value;
		if(curr_tab == 'inbox'){
			get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
		}else if(curr_tab == 'outbox'){
			get_outbox(post_params_mail);
		}else if(curr_tab == 'archives'){
			post_params_mail.message_type_ioa = "archives";
			get_message_proto(post_params_mail);
		}

		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

	});

function get_outbox(post_params_mail){

	 $('#message_div').addClass('disabledbutton');
    let url  = 'messaging/mail/get_outbox_table';
    let type = 'POST';

    let mail_type = document.getElementById('mail_type').value;
	let sender = document.getElementById('mail_sender').value;
	let subj = $('#mail_subj_search').val();


		if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
		}
		if(sender == 'all' || sender == 'All'){
			sender = '';
		}
		if(subj == 'all' || subj == 'All'){
			subj = '';
		}

    let success_function = function(responseText){

        let n = JSON.parse(responseText);
        let header = [];

            header = [          
            'SENDER_RECIPIENT',
            'SUBJECT',
            'TOPIC',
            'MAIL_DATE_FORMATTED',
            'IS_READ'
        ];



        create_table(n.data,header,10,'messages','IS_READ',1);
        create_pagination_proto(post_params_mail.start/10,Math.ceil(n.recordsTotal/10),'inbox_outbox_pagination');
        $('#message_div').removeClass('disabledbutton');
    }
     ajax_request(type, url, post_params_mail, success_function);
}

function get_message_proto(post_params_mail){


	 $('#message_div').addClass('disabledbutton');
    let url  = 'messaging/mail/get_inbox_outbox_archive';
    let type = 'POST';

    let mail_type = document.getElementById('mail_type').value;
	let sender = document.getElementById('mail_sender').value;
	let subj = $('#mail_subj_search').val();


		if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
		}
		if(sender == 'all' || sender == 'All'){
			sender = '';
		}
		if(subj == 'all' || subj == 'All'){
			subj = '';
		}

    let success_function = function(responseText){
        let n = JSON.parse(responseText);
        let header = [];

            header = [          
            'SENDER_RECIPIENT',
            'SUBJECT',
            'TOPIC',
            'MAIL_DATE_FORMATTED',
            'IS_READ'
        ];



        create_table(n.data,header,10,'messages','IS_READ',1);
        create_pagination_proto(post_params_mail.start/10,Math.ceil(n.recordsTotal/10),'inbox_outbox_pagination');
        $('#message_div').removeClass('disabledbutton');
    }
     ajax_request(type, url, post_params_mail, success_function);
}


	$(document).on('change','#mail_type',function(event){

		if($(this).val() == 'notification'){
			$('#mail_sender').attr('disabled',true);
			$('#mail_sender').val('all');
		}else{
			$('#mail_sender').attr('disabled',false);
		}

		let mail_sender = document.getElementById('mail_sender').value;
		if(mail_sender == 'all' || mail_sender == 'All'){
			mail_sender = '';
		}

		let subj = $('#mail_subj_search').val();


		if(subj == 'all' || subj == 'All'){
			subj = '';
		}

		$('#select_all').prop('checked',false);
		let post_params_mail = {
		start : 0,
		length : 10,
		message_type: $('#mail_type').val(),
		status : '',
		sort : sort,
		sort_type : sort_type,
		from : mail_sender,
		subject : subj
		};


	let curr_tab = document.getElementById('current_tab').value;
		if(curr_tab == 'inbox'){
				get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
		}else if(curr_tab == 'outbox'){
			get_outbox(post_params_mail);
		}else if(curr_tab == 'archives'){
			post_params_mail.message_type_ioa = "archives";
			get_message_proto(post_params_mail);
		}

	
		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;


	});


	$(document).on('change','#mail_sender',function(event){

		let mail_type = document.getElementById('mail_type').value;
		let sender = this.value;

		if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
		}
		if(this.value == 'all' || this.value == 'All'){
			sender = '';
		}
		$('#select_all').prop('checked',false);

		let curr_tab = document.getElementById('current_tab').value;

		let post_params_mail = {
		start : 0,
		length : 10,
		message_type: mail_type,
		status : '',
		sort : sort,
		sort_type : sort_type,
		from : sender,
		message_type_ioa : curr_tab
		};

		if(curr_tab == "inbox"){
			get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
		}else if(curr_tab == "outbox"){
			get_outbox(post_params_mail);
		}else if(curr_tab == "archives"){
			get_message_proto(post_params_mail);
		}

		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;


	});

	$(document).on('change','#mail_subj_search',function(event){

		let mail_type = document.getElementById('mail_type').value;
		let sender = document.getElementById('mail_sender').value;
		//let subj = $('#mail_subj_search').find(':selected').data('subj-id');
		let subj = $('#mail_subj_search').val();

		if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
		}
		if(sender == 'all' || sender == 'All'){
			sender = '';
		}
		if(subj == 'all' || subj == 'All'){
			subj = '';
		}

		$('#select_all').prop('checked',false);
		let ndata = document.getElementById('current_tab').value;

		let post_params_mail = {
		start : 0,
		length : 10,
		message_type: mail_type,
		status : '',
		sort : sort,
		sort_type : sort_type,
		from : sender,
		subject : subj,
		message_type_ioa : ndata

		};

		
		if(ndata == 'inbox'){
			get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
		}else if(ndata =='outbox'){
			get_outbox(post_params_mail);
		}else if(ndata == 'archives'){
			get_message_proto(post_params_mail);
		}



		

		/*alert(this.value);*/
		/*
		alert($(this).prop('selectedIndex').data('subj-id'));
		*/


		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
	});

	$(document).on('click','#messages tr td:not(:nth-child(2))',function(event){

	let parent_tr = $(this).parent('tr');
	let ndata = document.getElementById('current_tab').value;
	document.getElementById('mail_topic_reply').value = '';
	//
	let y = $(parent_tr).find('td:last');
	let n = $(parent_tr).find('td:first').find('input:first');
	let m = $(parent_tr).find('td:first').find('div:first').html();
	let content = '';
	let to = n.data('to');
	let topic = n.data('topic');
	let subject = n.data('subject');
	let datesent = n.data('sentdate');
	let recipient_id = n.data('recid');

	let curr_tab = document.getElementById('current_tab').value;




	if(to == 'Portal'){
		$('#mail_recipient_label').html('From');
		$('#send_message_reply').hide();
		$('#real_reply').attr('contenteditable',false);
		$('#title').text('Notification');
	}else{
		$('#mail_recipient_label').html('To');	
		$('#recipient_id').val(recipient_id);
		$('#message_id').val($(parent_tr).data('message-id'));			
		$('#title').text('Reply to Message');
		if(curr_tab == 'inbox'){
			$('#send_message_reply').show();
			$('#real_reply').attr('contenteditable',true);
		}else{
			$('#send_message_reply').hide();
			$('#real_reply').attr('contenteditable',false);
		}
	}

	if(y.find('input:first').val() == 'mail_unread'){
		if(ndata == 'inbox'){
			open_message($(parent_tr).data('message-id'));
			$(parent_tr).removeClass('info');
			y.find('img:first').attr('src',burl+'img/mail_read.png');
		}else if(ndata == 'outbox'){
			y.find('img:first').attr('src',burl+'img/mail_unread.png');
		}
		
		
/*		y.find('input:first').val('mal_read');*/
	}

	content += 'Sent On - ' + datesent +'<br><br>'
	content += m;
	$('#mail_subj').val(subject);
/*	$('#mail_topic').text(topic);*/
	document.getElementById('mail_topic_reply').value = topic;
	$('#mail_recipient').val(to);
	$('#previous_mail').html(replaceNewLine(content));
	$('#reply_message').modal('show'); 
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

	});



function send_message_notif(is_new = 0){

	$('.btn-send').attr('disabled',true);

	let url  = 'messaging/mail/send_message';
    let type = 'POST';
    let message = {};
    if(is_new == 0){
    	message = {
			mail_body: document.getElementById('reply_box').innerText.replace(/(\n)+/g, '<br />'),
			mail_subj: document.getElementById('mail_subj').value,
			mail_topic: 'RE : '+document.getElementById('mail_topic_reply').value,
			parent_message_id:document.getElementById('message_id').value,
			recipient_id: document.getElementById('recipient_id').value
		}
    }else{

    	let cerror = '';

    	if(user_type_id != 2){

			if(document.getElementById('recipient_new').selectedIndex == 0){
			cerror += '<br> &emsp;&emsp; Recipient is Required!';
			$('#recipient_new').parent('div').addClass('has-error');
			}
			//recipient

    	}


    	if(document.getElementById('mail_body_new').value == ""){
    		cerror += '<br> &emsp;&emsp; Message is Required!';
    		$('#mail_body_new').parent('div').addClass('has-error');
    	}
    	if(document.getElementById('mail_subj_new').value == ""){
    		cerror += '<br> &emsp;&emsp; Subject is Required!';
    		$('#mail_subj_new').parent('div').addClass('has-error');
    	}
    	if(document.getElementById('mail_topic_new').value == ""){
    		cerror += '<br> &emsp;&emsp; Topic is Required!';
    		$('#mail_topic_new').parent('div').addClass('has-error');
    	}

    	if(cerror.length > 0){
    		cerror = '<strong>Failed! Message not sent</strong>' + cerror;
    		modal_notify('new_message',cerror,'danger');
    		return;
    	}

    	message = {
			mail_body: document.getElementById('mail_body_new').value.replace(/(\n)+/g, '<br />'),
			mail_subj: document.getElementById('mail_subj_new').value,
			mail_topic:document.getElementById('mail_topic_new').value,
			parent_message_id:'',		
			recipient_id :$('#mail_subj_new option:selected').data('recipient-id')
		}

		if(user_type_id != 2){
			//message.recipient_id = document.getElementById('recipient_id').value
			message.recipient_id = $('#recipient_new').val();
		}

    }


    let success_function =function(responseText){

		$('.btn-send').attr('disabled',false);

		if(is_new == 0){
			reset_replybox();
			$('#reply_message').modal('toggle');
		}else{
			clear_modal();
			$('#new_message').modal('toggle');
		}
	
		notify('Reply Successfully Sent!.','success');

	}
	ajax_request(type, url, message, success_function);


}

function reset_replybox(){

	$('#real_reply').html('');
}



function get_message_data(){


	let url  = 'messaging/mail/get_from_subject';
    let type = 'POST';

    let success_function  = function(result){
		
    	let n = JSON.parse(result);

		let subject = {

			rs :	n.subjects_filter
		}

		let from = {
			rs : n.from
		}

		let senders  = {
			rs : n.senders
		}



		if(user_type_id != 2){
			create_senders(senders,'recipient_new');
			create_senders(from,'mail_sender');	
			create_subjects(subject,'mail_subj_new');
			create_subjects(subject,'mail_subj_search');
		}else{

			let vendor_subj = {
				rs : n.vendor_subjects

			};

			create_senders(senders,'recipient_new');
			create_senders(from,'mail_sender');	
			create_subjects(vendor_subj,'mail_subj_new');
			create_subject_vendor(vendor_subj);
			create_subjects(subject,'mail_subj_search');
		}


    }

    let params = {};


   ajax_request(type, url, params, success_function);

}

function create_senders(data,select_name){
 let opt = '';
	
	if(select_name == 'recipient_new'){
		opt += '<option>PLEASE SELECT</option>';
		if(user_type_id == 2){
		}else{
			opt += '<option value="all">All</option>';
		}
	}else{
	opt += '<option value="all">All</option>';
	}

	data.rs.forEach(function(val){
		 opt += '<option value="'+val.USER_ID+'">'+val.USERS_NAME+'</option>';
	});

	$('#'+select_name).html(opt);
}

function create_subjects(data,select_name){

	let opt = '';

	
	if(select_name == 'mail_subj_new'){
		opt += '<option>PLEASE SELECT</option>';
		if(user_type_id == 2){

		}else{
		opt += '<option value="all">All</option>';
		}
	}else{
			opt += '<option value="all">All</option>';
		}
	data.rs.forEach(function(val){
  		 opt += '<option value="'+val.SUBJECT+'" data-subj-id = "'+val.DATA_ID+'"  data-subj-type="'+val.SUBJECT_TYPE+'">'+val.SUBJECT+'</option>';
  	});


	$('#'+select_name).html(opt);


	if(select_name == 'mail_subj_search'){

		if(document.getElementById('data_id').value){
			if(document.getElementById('data_id').value != 'null'){
				$('#mail_subj_search option').each(function(){
					let n = $(this).data('subj-id');
					let real_value = $(this).val();
					if(n == document.getElementById('data_id').value){
						$('#mail_subj_search').val(real_value);
						$('#mail_subj_search').trigger('change');
					}
				});

			}
		}
	}

}

function create_subject_vendor(data){

	let opt = ''; 

	opt += '<option>PLEASE SELECT</option>';

	data.rs.forEach(function(val){
  		 opt += '<option value="'+val.SUBJECT+'" data-subj-id = "'+val.DATA_ID+'"  data-subj-type="'+val.SUBJECT_TYPE+'" data-recipient-id = "'+val.RECIPIENT_ID+'">'+val.SUBJECT+'</option>';
  	});

	$('#mail_subj_new').html(opt);
}






$(document).on('click','#messages .a_table_header a',function(e){

e.preventDefault();
 if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true;

	 sort = $(this).data('sort');
	 sort_type = $(this).data('sort_type');
	 let n = this;
	 let mail_topic = document.getElementById('mail_topic').value;
	 let m = $(this).closest('th');


	 $('#messages .a_table_header').each(function(){

	 let x = $(this);

	 $(x).removeClass('sort_column sort_desc');
	 $(x).removeClass('sort_column sort_asc');
	 $(x).addClass('sort_column sort_default');

/*	 	console.log(this);
	 	$(this).removeClass('glyphicon-sort-by-attributes-alt');
	 	$(this).addClass('glyphicon-sort-by-attributes');*/
	 });

	 if(n.dataset.sort == 'desc'){
	 	n.dataset.sort = 'asc';
	 	$(n).data('sort','asc');
		$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_asc');
	 }else{
	 	n.dataset.sort = 'desc';
	 	$(n).data('sort','desc');
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_desc');
	 }

	 sort = $(this).data('sort');

	let mail_type = document.getElementById('mail_type').value;
	let sender = document.getElementById('mail_sender').value;
	let subj = $('#mail_subj_search').val();

	if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
	}
	if(sender == 'all' || sender == 'All'){
			sender = '';
	}
	if(subj == 'all' || subj == 'All'){
		subj = '';
	}



    let curr_tab = document.getElementById('current_tab').value;


	 let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: mail_type,
    	status :'',
    	sort : sort,
    	sort_type : sort_type,
    	message_type_ioa : curr_tab,
    	from : sender,
		subject : subj,
		search_topic : mail_topic
    	};


    	if(curr_tab == 'inbox'){
    		get_table_data(post_params_mail,'inbox_outbox_pagination','messages',1);
    	}else if(curr_tab == 'outbox'){
    		get_outbox(post_params_mail);
    	}else if (curr_tab == 'archives'){
    		get_message_proto(post_params_mail);
    	}
    }
	e.stopImmediatePropagation();
});


$(document).on('click','.mailbox_action',function(event){

document.getElementById('mail_topic').value ='';
$('#mail_type')[0].selectedIndex = 0;
$('#mail_sender')[0].selectedIndex = 0;
$('#mail_subj_search')[0].selectedIndex = 0;
$('#select_all').prop('checked',false);

let mail_type = document.getElementById('mail_type').value;
	if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
	}

let n = this;
let ndata = $(n).data('mail-type');

	let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: mail_type,
    	status :'',
    	sort : 'DESC',
    	sort_type : 'DATE_SENT',
    	message_type_ioa : ndata
    	};

if(ndata == 'outbox'){
	document.getElementById('current_tab').value = "outbox";
	document.getElementById('sender_filter').innerHTML = 'To';
	get_outbox(post_params_mail);
	$('#messages tr th:nth-child(2) label').html('To');
}else if(ndata == 'inbox'){
	document.getElementById('current_tab').value = "inbox";
	document.getElementById('sender_filter').innerHTML = 'From';
	get_table_data(post_params_mail,'inbox_outbox_pagination','messages',1);
	$('#messages tr th:nth-child(2) label').html('From');
}else if(ndata == 'archives'){
	document.getElementById('current_tab').value = "archives";
	document.getElementById('sender_filter').innerHTML = 'From';
	get_message_proto(post_params_mail);
	$('#messages tr th:nth-child(2) label').html('From');
}


$('.mailbox_action').each(function(){
	$(this).parent('li').removeClass('active');
});

$(n).parent('li').addClass('active');
event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});

function clear_modal(){


	if(user_type_id != 2){
		$('#recipient_new')[0].selectedIndex = 0;
		$('#mail_subj_new')[0].selectedIndex = 0;
		document.getElementById('mail_subj_new').value = "";

	}else{
		document.getElementById('mail_subj_new').value = "";
	}

			document.getElementById('mail_body_new').value = "";		
			document.getElementById('mail_topic_new').value = "";
			

    		$('#recipient_new').parent('div').removeClass('has-error');
    		$('#mail_body_new').parent('div').removeClass('has-error');
    		$('#mail_subj_new').parent('div').removeClass('has-error');
    		$('#mail_topic_new').parent('div').removeClass('has-error');
    	

}

function archive_message(){

	let message_id = [];

	$('.mail_check').each(function(){
		if($(this).is(':checked')){
			let n = $(this).parent('td').closest('tr').data('message-id');
			message_id.push(n);
		}

	});
	if(message_id.length == 0){
		notify('<strong>Failed!</strong> Please Select Message(s) to be Archived.','danger');
		return;
	}


	var span_message = 'Are you sure you want to archive all selected message(s)? <button type="button" data-msg-id = "'+ message_id.join(',') +'" class="btn btn-success" onclick="archive_all_selected(this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert">No</button>';
    var type = 'info';
    notify(span_message, type, true);


}

$(document).on('click','#archive_message',function(event){

	archive_message();
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});

function archive_all_selected(n){
	
	let data = {
		message_ids : $(n).data('msg-id')
	}



	let url  = 'messaging/mail/archive_message';
    let type = 'POST';

    let success_function  = function(result){

    var span_message = '<strong>Success!</strong>Message(s) Archived.';
    var type = 'success';
    notify(span_message, type);

    let mail_type = document.getElementById('mail_type').value;
	let sender = document.getElementById('mail_sender').value;

	if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
	}
	if(sender == 'all' || sender == 'All'){
			sender = '';
	}


    let curr_tab = document.getElementById('current_tab').value;


	 let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: mail_type,
    	status :'',
    	sort : sort,
    	sort_type : sort_type,
    	message_type_ioa : curr_tab
    	};


    	if(curr_tab == 'inbox'){
    		get_table_data(post_params_mail,'inbox_outbox_pagination','messages',1);
    	}else if(curr_tab == 'outbox'){
    		get_outbox(post_params_mail);
    	}else if (curr_tab == 'archives'){
    		get_message_proto(post_params_mail);
    	}

    }


   ajax_request(type, url, data, success_function);



}

$(document).on('click','#select_all',function(event){

	if(this.checked == true){
		$('#messages input[type=checkbox]').each(function(){
			$(this).prop('checked',true);
		});
	}else{
		$('#messages input[type=checkbox]').each(function(){
			$(this).prop('checked',false);
		});

	}

	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});

$(document).on('click','#search_topic',function(){


	let mail_type = document.getElementById('mail_type').value;
	let sender = document.getElementById('mail_sender').value;
	let subj = $('#mail_subj_search').val();

	if(mail_type == 'all' || mail_type == 'All'){
			mail_type = '';
	}
	if(sender == 'all' || sender == 'All'){
			sender = '';
	}
	if(subj == 'all' || subj == 'All'){
		subj = '';
	}


	$('#select_all').prop('checked',false);
    let curr_tab = document.getElementById('current_tab').value;
    let mail_topic = document.getElementById('mail_topic').value;


	 let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: mail_type,
    	status :'',
    	sort : sort,
    	sort_type : sort_type,
    	message_type_ioa : curr_tab,
    	from : sender,
		subject : subj,
		search_topic : mail_topic
    	};


    	if(curr_tab == 'inbox'){
    		get_table_data(post_params_mail,'inbox_outbox_pagination','messages',1);
    	}else if(curr_tab == 'outbox'){
    		get_outbox(post_params_mail);
    	}else if (curr_tab == 'archives'){
    		get_message_proto(post_params_mail);
    	}

});

function default_data_id(){

		let tmp_subj = '';
		let x = '';
		if(document.getElementById('data_id').value != null || document.getElementById('data_id').value != 'null'){
			tmp_subj = document.getElementById('data_id').value;
			$('#mail_subj_search option').each(function(val){
				let n = $(this).data('subj-id');
				console.log(n);
				if(n == tmp_subj){
					x = $(this).val();
				}

			});

		let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#mail_type').val(),
    	subject : x,
    	status :'',
    	sort : sort,
    	sort_type : sort_type
    	};

		get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
	


		if(x.length > 0){
			$('#mail_subj_search').val(x);
		}

		}
}


	//$(document).ready(function(){
	//	alert(123);

	/*let nsubj = '';
	let x = '';

	if(document.getElementById('data_id').value != null || document.getElementById('data_id').value != 'null'){
			nsubj = document.getElementById('data_id').value;
			$('#mail_subj_search option').each(function(val){
				let n = $(this).data('subj-id');
				console.log(n);
				if(n == nsubj){
					x = $(this).val();

				}

			});
			alert(nsubj);
		}

		alert(x);
		
	let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#mail_type').val(),
    	subject : x,
    	status :'',
    	sort : sort,
    	sort_type : sort_type
    	};

		get_table_data(post_params_mail,'inbox_outbox_pagination','messages', 1);
		get_message_data();


		if(x.length > 0){
			$('#mail_subj_search').val(x);
		}
*/

	//});

			get_message_data();





</script>



<!-- l
	
<script id="messages_template" type="text/template">
							{{#ResultSet}}
								<tr class="user_mail {{IS_READ}}" data-message-id="{{ID}}">
									<td class="mail_chkbx"><input type="checkbox" value="{{ID}}"></td>
									<td>{{SENDER_RECIPIENT}}</td>
									<td>{{SUBJECT}}</td>
									<td>{{TOPIC}}</td>
									<td>{{MAIL_DATE}}</td>
									{{#STATUS}}
										<td><img src="<?=base_url()?>/assets/img/{{STATUS}}.png" width="20"></td>
									{{/STATUS}}
								</tr>
							{{/ResultSet}}
							{{^ResultSet}}
								<tr>
									<td colspan="5">No Records found.</td>
								</tr>
							{{/ResultSet}}
						</script>
 -->