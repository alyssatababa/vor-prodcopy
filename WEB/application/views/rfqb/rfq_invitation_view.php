<style>
	textarea.form-control {
		resize: vertical;
		height: 34px;
	}
	textarea.form-control[name="request_note"],
	textarea.form-control[name="reject_reason"] {
		height: 100px;
	}
	thead {
		background-color: #d8d8d8;
	}

	td.pic_attachment
	{
		padding: 0 5px 0 5px;
	}

	.dv_attachment
	{
		padding: 15px 20px 0 20px;
		border: 1px solid #ccc;
	}
</style>


<?=form_open('form1', array('name' => 'form1'))?>
<?=(isset($bom_file_modals) ? $bom_file_modals : '');?>
<div id="result_div">
</div>
<input type="hidden" name="rfx_id" id="rfx_id" value="<?=$id?>">
<input type="hidden" name="position_id" id="position_id" value="<?=$position_id?>">
<input type="hidden" name="status_id" id="status_id" value="<?=$status_id?>">
<input type="hidden" name="invite_id" id="invite_id" value="<?=$invite_id?>">
	<div class="container mycontainer" id="mycontainer">

		<div class="row">
			<div class="col-md-4">
				<h4>RFQ/RFB Invitation</h4>
			</div>
			<div class="col-md-offset-9">
				<input type="button" value="Participate" class="btn btn-primary btn-sm"<?=$is_open?> id="btn_participate" onclick="participate_decline_invitation(1)">
				<input type="button" value="Decline" class="btn btn-primary btn-sm"<?=$is_open?> id="btn_decline" onclick="participate_decline_invitation(0)">
				<input type="button" class="btn btn-primary btn-sm" value="Close" onclick="go_to_homepage()">
			</div>
		</div>

		<hr>
	<div class="form_container">
	<div class="panel panel-default">
	<div class="panel-body">
		<!-- PRIMARY RFQ/RFB DATA -->
		<div class="row">
			<div class="form-group">
				<div class="col-md-2">
					<label>Title</label>
				</div>
				<div class="col-md-4">
					<input type="text" class="form-control field-required" id="title" placeholder="" value="<?=$title?>" disabled>
				</div>
				<div class="col-md-2">
					<label>RFQ/RFB</label>
				</div>
				<div class="col-md-2">
					<div class="col-sm-10"><span id="type" class="form-control"><?=$id?></span></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-md-2">
					<label>Type</label>
				</div>
				<div class="col-md-2">
					<input type="text" class="form-control field-required" id="title" placeholder="" value="<?=$rfqrfb_type_name?>" disabled>
				</div>
			</div>
		</div>
		<!-- END PRIMARY RFQ/RFB DATA -->

		<hr>

		<div class="row">
			<div class="form-group">
				<div class="col-md-2">
					<label>Currency</label>
				</div>
				<div class="col-md-2">
					<?=form_dropdown('currency', $currency_data, $currency, 'id="currency" class="form-control"')?>
				</div>
				<div class="col-md-2">
					<label>Preferred Delivery Date
				</div>
				<div class="col-md-2">
					<input type="date" id="delivery_date" class="form-control" value="<?=$delivery_date?>">
				</div>
				<div class="col-md-2">
					<label>Submission Deadline</label>
				</div>
				<div class="col-md-2">
					<input type="date" id="deadline_date" class="form-control" value="<?=$submission_deadline?>">
				</div>
			</div>
		</div>
		<br>
		<hr>

		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-primary">

					<div class="panel-heading">
						<strong>Lines</strong>
						<div class="pull-right">
							<input type="hidden" id="max_lines" name="max_lines" value="1">
							<!-- <input type="button" value="Add" class="btn btn-default btn-xs" onclick="add_delete_lines(1)" id="add_btn">
							<input type="button" value="Delete" class="btn btn-default btn-xs" onclick="delete_lines(0)" id="del_btn" disabled> -->
						</div>
					</div>

					<div class="panel-body" id="lines_data">

						<!-- LINES DATA -->
						<div id="lines_data_rfx">

							<!-- SAMPLE LINE DATA 1 -->
								<?=$lines?>

						</div> <!-- END OF LINES DATA -->
					</div> <!-- END OF PANEL BODY-->
				</div> <!-- END OF PANEL -->

			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="col-md-2">
					<strong>Note To Buyer</strong>
				</div>
				<div class="col-md-10">
					<textarea name="reject_reason" id="reject_reason" oninput="change_border(this.id)" class="form-control editable"></textarea>
				</div>
			</div>
		</div>

	</div>
</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="reject_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Reject Reason</h3>
			</div>
			<div class="modal-body">
				<!-- <textarea name="reject_reason" class="form-control editable"></textarea> -->
			</div>
			<div class="modal-footer">
				<button id="reject_btn" class="btn btn-primary"  onclick="participate_decline_invitation(0)" data-dismiss="modal">Ok</button>
				<button id="cancel_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
             	<div class="modal-header">					
					
					<span class="document_preview" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Preview</h4>
						<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
						<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
					</span>				
				</div>
                  <div class="modal-body">
                       <div class="container-fluid" id="view_modal3">
							<span class="document_preview" style="display:none;">
								<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
							</span>
                       </div>
                  </div>
                  <div class="modal-footer">
					<span class="document_preview" style="display:none;">
						<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
					</span>
                  </div>
             </div>
        </div>
	</div>
<?=form_close();?>
<!-- END OF MODAL -->

<script>
	$('.form-control:not(.editable)').prop('readonly', true);
	$('select.form-control').prop('disabled', true);

	function specsview(row)
	{
		if (document.getElementById('specs'+row).value == 0)
		{
			document.getElementById('specs'+row).value = 1;

			document.getElementById('specifications'+row).style.display = 'inline';

			// add code here for show next tr
		}
		else	
		{
			document.getElementById('specs'+row).value = 0;

			document.getElementById('specifications'+row).style.display = 'none';

			// add code here for hide next tr
		}	
		return;
	}

	function load_attachment(path)
	{
		var url = BASE_URL.replace('index.php/','') + path;
        
        $('#imagepreview').attr('src', '');
        if (path != '')
        {
            $('#imagepreview').attr('src', url);
            $('#imagepreview').removeClass('zoom_in');
            $('.modal-dialog').addClass('modal-lg');    
            var filext = path.split('.').pop();
            // setting height of iframe according to window size
            var set_height  = '';
            var w_h         = '';
            var t_h         = '';

            if (filext.toLowerCase().match(/(jpeg|jpg|png|pdf)$/))
            {
            	$('#myModal3').modal('show');

			    $('#myModal3 span').hide();
			    $('.alert > span').show(); // dont include to hide these span
			    $('#myModal3 .document_preview').show();

                w_h = $(window).height() /2;
                t_h = $(this).height() /2;
                $('#imagepreview').css('display', 'inherit');
                $('#zoom_image').show();
                $('#zoom_out_image').show();
            }
            else
            {
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#imagepreview').css('display', 'none');
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();                
            }
            $('iframe').height(w_h);
            $(window).resize(function(){
                $('iframe').height(t_h);
            });
            //$('#imagepreview').attr('src', '');
        }
        else
        {
            $('#imagepreview').attr('src', '');
        }
	}

	function zoomimage()
	{
		$('#imagepreview').addClass('zoom_in');
	}

	function zoomoutimage()
	{
	    $('#imagepreview').removeClass('zoom_in');
	}

</script>