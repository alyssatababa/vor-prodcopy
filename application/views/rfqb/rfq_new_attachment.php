<div class="modal-header">
	<h4 class="modal-title"><?=$category?></h4>
</div>
<div class="modal-body">
<div class="container">
<?=form_open_multipart('form2', array('name' => 'form2') );?>

	<div class="row">
		<input type="hidden" id="modal_row_attachment" name="modal_row_attachment" value="<?=$row?>">
		<input type="hidden" id="col" name="col" value="<?=$col?>">
			<table>
				<tr>
					<td><b>Description:</b><span style="font-weight:100;"id="modal_txt_description_char_num"></span></td>
				</tr>
				<tr>
					<td><input type="text" id="modal_txt_description" maxlength="300" class="form-control" name="modal_txt_description" value=""></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Attachment Type</b></td>
				</tr>
				<tr>
					<td><?=form_dropdown('modal_cbo_attachmenttype', $attachment_type, -1, 'id="modal_cbo_attachmenttype" onchange="change_type_allowed(this.value,'.$row.','.$col.')" class="btn btn-default dropdown-toggle" style="width:200px"');?></td>
				</tr>
				<tr rowspan="3">
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<a id="modal_bom_template_link" target="about:blank" href="<?=base_url('public/templates/BOM_Template.xlsx')?>" style="display: none;">Download BOM Template</a>
					</td>
				</tr>
				<tr rowspan="3">
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><div id="upload_attachment_div"><?=form_upload(array('name'=>'upload_attachment','id'=>'upload_attachment' , 'disabled' => true))?></div></td>
				</tr>
				
			</table>
			<br>	
		</div>
	</div>
<?=form_close();?>
</div>