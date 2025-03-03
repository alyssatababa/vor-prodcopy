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
<?=(isset($bom_file_modals) ? $bom_file_modals : '');?>
<input type="hidden" name="invite_id" id="invite_id" value="<?=$invite_id?>">
<input type="hidden" name="total_lines" id="total_lines" value="<?=$total_lines?>">
<div class="container mycontainer">
	<div class="row">
		<div class="col-md-4">
			<h4>Vendor Response</h4>
		</div>
		<div class="col-md-offset-9">
			<!-- <input type="button" class="btn btn-primary btn-sm" id="btn_submit" value="Submit" onclick="validate_response(1)">
			<input type="button" class="btn btn-primary btn-sm" id="btn_draft" value="Save as Draft" onclick="validate_response(0)"> -->
		</div>
	</div>
<div class="form_container">
<div class="panel panel-default">
<div class="panel-body">
	<br>
	<div class="row">
		<!-- <p><?php echo $draft_res; ?></p> -->
		<div class="col-md-5">
			<div class="form-horizontal">
				<div class="form-group">
					<!-- <a href="#"><label for="title" class="col-sm-2 control-label cursor_pointer">Messages(1)</label></a> -->
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
		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-10">
						<label class="main_label">Close Date:</label>
						<label class="main_label"><?=$close_date?></label>
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
							<label>Preffered Delivery Date</label>
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
			<table class="table">
			<thead>
				<th>&nbsp;</th>
				<!-- <th>Items</th>
				<th>Quoted - 2</th>
				<th>No Qoute - 0</th>
				<th>Total Items - 2/2</th> -->
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

<?=form_close();?>
<script>
	// $('.form-control:not(.editable)').prop('readonly', true);
	// $('select.form-control').prop('disabled', true);
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

			document.getElementById('attachment_href'+row).innerHTML = 'Attachment <<';
			document.getElementById('add_attachment'+row).style.display = 'inline';
		}
		else	
		{
			document.getElementById('attachment_value'+row).value = 0;

			document.getElementById('attachment_href'+row).innerHTML = 'Attachment >>';
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
			document.getElementById('txt_quote'+row).value = "";
		}
		else
		{
			document.getElementById('quoteischecked'+row).value = 0;
			document.getElementById('radio_quote'+row).checked = false;
			document.getElementById('txt_quote'+row).readOnly = true;
			document.getElementById('txt_quote'+row).value = "";
		}
	}

	function quote_value(row)
	{
			document.getElementById('radio_quote'+row).checked = false;
	}

	function hide_counter_offer(row)
	{
		// alert(document.getElementById('counter_offer_hidden'+row).value);
		if ($("#counter_offer_textarea"+row).is(":hidden"))
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