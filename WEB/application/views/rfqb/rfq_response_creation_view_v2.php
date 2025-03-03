<style>
.main_label
{
	color: #43A5CF;
}

.cursor_pointer
{
	cursor: pointer;
}

.btn_min_width
{
	min-width: 100px;
}

.indent_left
{
	padding-left: 10px;
}

.indent_right
{
	padding-right: 30px;
}

thead 
{
	background-color: #d8d8d8;
	width: 100% ;
}

.indent_top
{
	padding-top: 20px;
}

textarea.form-control 
{
		resize: vertical;
		height: 34px;
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
<?=form_open_multipart('form1', array('name' => 'form1', 'id' => 'form1') );?>
<?=(isset($bom_modals) ? $bom_modals : '');?>
<?=(isset($bom_file_modals) ? $bom_file_modals : '');?>
<input type="hidden" name="invite_id" id="invite_id" value="<?=$invite_id?>">
<input type="hidden" name="total_lines" id="total_lines" value="<?=$total_lines?>">
<div class="container mycontainer">
	<div class="row">
		<div class="col-md-4">
			<h4>Response Creation</h4>
		</div>
		<div class="col-md-offset-9">
			<input type="button" class="btn btn-primary btn-sm" id="btn_submit" value="Submit" <?=$disabled_btn?> onclick="validate_response_v2(1)">
			<input type="button" class="btn btn-primary btn-sm" id="btn_draft" value="Save as Draft" <?=$disabled_btn?> onclick="validate_response_v2(0)">
			<input type="button" class="btn btn-primary btn-sm" value="Close" onclick="go_to_homepage()">
		</div>
	</div>
<div class="form_container">
<div class="panel panel-default">
<div class="panel-body">
	<br>
	<div class="row">
		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<?=nbs(5)?><a href="#" class="cls_action" data-action-path="<?=$message_link?>" data-crumb-text="Messages"><b>Messages</b><span class="badge"><?=$message_count?></span></a>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-10">
						<label class="main_label">Time Left:</label>
						<label style="color: red;"><?=$interval?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-10">
						<label class="main_label">Close Date:</label>
						<label class="main_label"><?=$close_date?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-10">
						<label class="main_label">Version:</label>
						<label class="main_label"><?=$version?></label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-5">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-10">
						<div class="col-md-3">
							<label>Title</label>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control field-required" id="title" name="title" placeholder="" value="<?=$title?>" readonly>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<table border="0">
				<tr>
					<td><label>RFQ</label></td>
					<td class="indent_left"><input id="rfx_id" name="rfx_id" class="form-control" value="<?=$id?>" readonly></td>
				</tr>
				<tr>
					<td><label>Currency</label></td>
					<td class="indent_left">
						<?=form_dropdown('currency_display', $currency_data, $currency, 'id="currency_display" class="form-control" disabled')?>
						<input type="hidden" id="currency" name="currency" value="<?=$currency?>">
					</td>
				</tr>
			</table>
		</div>
		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-12">
						<div class="col-md-7">
							<label>Preferred Delivery Date</label>
						</div>
						<div class="col-md-5" style="padding: 0 0 0 0">
							<input type="date" id="delivery_date" name="delivery_date" class="form-control" value="<?=$delivery_date?>" readonly>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<!-- HEAD FOR ITEMS AND TOTAL QOUTEDS -->
	<div class="row">
		<div class="col-md-12">
			<?php
			$quoted_1 = ($total_quotes - $no_quote_count);
			$no_quoted_1 = $no_quote_count;
			$total_quoted_1 = ($total_quotes - $no_quote_count);

			$hidden_array = array('hidden_quoted_items' => $quoted_1,
								  'hidden_no_quote_items' => $no_quoted_1,
								  'hidden_total_quoted' => $total_quoted_1,
								  'total_line_quotes' => $total_quotes);

			echo form_hidden($hidden_array);
			?>
			<table class="table">
			<thead>
				<!--<th>&nbsp;</th>-->
				 <th>Items</th>
				<th>Quoted - <label id="quoted_items"><?=$quoted_1?></label></th>
				<th>No Quote - <label id="no_quote_items"><?=$no_quoted_1?></label></th>
				<th>Total Items - <label id="total_quoted"><?=$total_quoted_1?></label>/<?=$total_quotes?></th> 
			</thead>
			</table>
		</div>
	</div>
	<!-- END HEAD FOR ITEMS AND TOTAL QOUTEDS -->
	<!-- LASER PRINTER MULTI FUNCTION -->
	<?=$table?>
	<!-- END EPSON LX310 DOT MATRIX PRINTER -->

	</div>
	</div>
	</div>
</div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal">                                     
                       </div>
                  <!-- </div> -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-xs btn_min_width" onclick="add_quote()">OK</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width">Close</button>
                  </div>
             </div>
        </div>
	</div>

    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal2">                                     
                       </div>
                  <!-- </div> -->
                  
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
<script>
	// $('.form-control:not(.editable)').prop('readonly', true);
	// $('select.form-control').prop('disabled', true);

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
	
	function attachmentview_response(row)
	{
		if (document.getElementById('attach'+row).value == 0)
		{
			document.getElementById('attach'+row).value = 1;

			document.getElementById('attachment'+row).style.display = 'inherit';
		}
		else	
		{
			document.getElementById('attach'+row).value = 0;

			document.getElementById('attachment'+row).style.display = 'none';
		}	
		return;
	}

	function new_attachment(row)
	{
		if (document.getElementById('attachment_value'+row).value == 0)
		{
			document.getElementById('attachment_value'+row).value = 1;

			document.getElementById('attachment_href'+row).innerHTML = 'Add Attachment <<';
			document.getElementById('add_attachment'+row).style.display = 'inline';
		}
		else	
		{
			document.getElementById('attachment_value'+row).value = 0;

			document.getElementById('attachment_href'+row).innerHTML = 'Add Attachment >>';
			document.getElementById('add_attachment'+row).style.display = 'none';
		}	
		return;
	}
	
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

	function no_quote(row)
	{
		if (document.getElementById('quoteischecked'+row).value == 0)
		{
			document.getElementById('quoteischecked'+row).value = 1;
			document.getElementById('radio_quote'+row).checked = true;
			document.getElementById('txt_quote'+row).readOnly = true;
			document.getElementById('txt_quote'+row).value = 0;
			$('#delivery_time'+row).prop('disabled', true);
			$('#txt_counteroffer'+row).prop('readonly', true);
			$('#add_attachmentbtn'+row).prop('disabled', true);
			document.getElementById('delivery_time'+row).value = '';
			document.getElementById('txt_counteroffer'+row).value = '';

			var quoted_items 	= $('#hidden_quoted_items').val();
			var no_quote_items 	= $('#hidden_no_quote_items').val();
			var total_quoted 	= $('#hidden_total_quoted').val();

			var total_q = parseInt(total_quoted) - 1;
			var total_nqi = parseInt(no_quote_items) + 1;
			var total_qi = parseInt(quoted_items) - 1;

			document.getElementById('hidden_quoted_items').value = total_qi;
			document.getElementById('hidden_no_quote_items').value = total_nqi;
			document.getElementById('hidden_total_quoted').value = total_q;

			document.getElementById('total_quoted').innerHTML = total_q; 
			document.getElementById('no_quote_items').innerHTML = total_nqi; 
			document.getElementById('quoted_items').innerHTML = total_qi; 

			var new_row = row.replace('_1', '');

			$('#num_quote'+new_row).val(1);
			$('#add_another_quote_btn'+new_row).prop('disabled', true);
			document.getElementById('quote_added'+new_row).innerHTML = '';
		}
		else
		{
			document.getElementById('quoteischecked'+row).value = 0;
			document.getElementById('radio_quote'+row).checked = false;
			document.getElementById('txt_quote'+row).readOnly = false;
			document.getElementById('txt_quote'+row).value = "";
			$('#delivery_time'+row).prop('disabled', false);
			$('#txt_counteroffer'+row).prop('readonly', false);
			$('#add_attachmentbtn'+row).prop('disabled', false);
			document.getElementById('delivery_time'+row).value = '';
			document.getElementById('txt_counteroffer'+row).value = '';

			var quoted_items 	= $('#hidden_quoted_items').val();
			var no_quote_items 	= $('#hidden_no_quote_items').val();
			var total_quoted 	= $('#hidden_total_quoted').val();

			var total_q = parseInt(total_quoted) + 1;
			var total_nqi = parseInt(no_quote_items) - 1;
			var total_qi = parseInt(quoted_items) + 1;

			document.getElementById('hidden_quoted_items').value = total_qi;
			document.getElementById('hidden_no_quote_items').value = total_nqi;
			document.getElementById('hidden_total_quoted').value = total_q;

			document.getElementById('total_quoted').innerHTML = total_q; 
			document.getElementById('no_quote_items').innerHTML = total_nqi; 
			document.getElementById('quoted_items').innerHTML = total_qi; 

			var new_row = row.replace('_1', '');
			$('#add_another_quote_btn'+new_row).prop('disabled', false);
		}
	}

	function quote_value(row)
	{
		var radio = document.getElementById('radio_quote'+row);
		if (radio!=null) {
			radio.checked = false;
		}
	}

	function hide_counter_offer(row)
	{
		if (document.getElementById('counter_offer_hidden'+row).value == 0)
		{
			document.getElementById('counter_offer_hidden'+row).value = 1;

			document.getElementById('counter_offer_text'+row).innerHTML = 'Counter Offer <<';
			document.getElementById('counter_offer_textarea'+row).style.display = 'inline';

			// add code here for show next tr
		}
		else	
		{
			document.getElementById('counter_offer_hidden'+row).value = 0;

			document.getElementById('counter_offer_text'+row).innerHTML = 'Counter Offer >>';
			document.getElementById('counter_offer_textarea'+row).style.display = 'none';

			// add code here for hide next tr
		}	
		return;
	}
</script>