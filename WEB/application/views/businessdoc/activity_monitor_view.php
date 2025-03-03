<style type="text/css">
    . td {
        vertical-align: middle;
    }
</style>

<script>
    // window.addEventListener('load', update_queue, false);

    function update_queue(){
        // alert('start');
        if (window.XMLHttpRequest)
            xmlhttp = new XMLHttpRequest();
        else
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        path_value = "<?php echo base_url().'index.php/businessdoc/main/get_file_queue'?>";
        
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200 && xmlhttp.responseURL == path_value) {
                // alert('if');
                var json = JSON.parse(xmlhttp.responseText);
                var count_label = document.getElementById("count_label");
                var table = document.getElementById('table_queue').getElementsByTagName('tbody')[0];
                if (json!=null && json.queue!=null && json.queue.length>0) {

                    if (count_label != null) {
                        count_label.innerHTML = json.queue.length.toString() + " PENDING FILE(s)";
                    }
                    for (var i = 0; i < json.queue.length; i++){
                        var queue = json.queue[i];
                        var elem = document.getElementById("FILE_QUEUE_ID_" + queue.FILE_QUEUE_ID);
                        if (elem != null) {
                            document.getElementById("FILE_QUEUE_ID_" + queue.FILE_QUEUE_ID).innerHTML = queue.FILE_QUEUE_ID;
                            document.getElementById("FILE_CODE_" + queue.FILE_QUEUE_ID).innerHTML = queue.FILE_CODE;
                            document.getElementById("FILE_NAME_" + queue.FILE_QUEUE_ID).innerHTML = queue.FILE_NAME;
                            document.getElementById("DATE_CREATED_" + queue.FILE_QUEUE_ID).innerHTML = queue.DATE_CREATED;
                            document.getElementById("STATUS_" + queue.FILE_QUEUE_ID).innerHTML = queue.STATUS;
                            document.getElementById("REMARKS_" + queue.FILE_QUEUE_ID).innerHTML = queue.REMARKS;
                        } else {
                            var row = table.insertRow(table.length);

                            var params = "\'file_queue_id=" + queue.FILE_QUEUE_ID.toString()+"\'";
                            var link =  "\'<?= base_url('index.php/businessdoc/main/reset_file_queue');?>\' ";
                            // console.log(link);
                            row.setAttribute("id", queue.FILE_QUEUE_ID);
                            var cell1 = row.insertCell(0);
                            var cell2 = row.insertCell(1);
                            var cell3 = row.insertCell(2);
                            var cell4 = row.insertCell(3);
                            var cell5 = row.insertCell(4);
                            var cell6 = row.insertCell(5);
                            var cell7 = row.insertCell(6);
                            var cell8 = row.insertCell(7);


                            cell1.innerHTML = queue.FILE_QUEUE_ID;
                            cell2.innerHTML = queue.FILE_CODE;
                            cell3.innerHTML = queue.FILE_NAME;
                            cell4.innerHTML = queue.DATE_CREATED;
                            cell5.innerHTML = queue.STATUS;
                            cell6.innerHTML = queue.REMARKS;
                            cell7.innerHTML = '<button type="button" class="btn btn-link" onClick="call_link_in_background( '+ link + ',' + params + ')"><span class="glyphicon glyphicon-refresh"></span></button>';
                            // console.log(cell7.innerHTML);
                            cell8.innerHTML = '<button type="button" class="btn btn-link" onClick="call_link_in_background( '+ link + ',' + params + ')"><span class="glyphicon glyphicon-trash"></span></button>';

                            cell1.setAttribute("id","FILE_QUEUE_ID_" + queue.FILE_QUEUE_ID);
                            cell2.setAttribute("id","FILE_CODE_" + queue.FILE_QUEUE_ID);
                            cell3.setAttribute("id","FILE_NAME_" + queue.FILE_QUEUE_ID);
                            cell4.setAttribute("id","DATE_CREATED_" + queue.FILE_QUEUE_ID);
                            cell5.setAttribute("id","STATUS_" + queue.FILE_QUEUE_ID);
                            cell6.setAttribute("id","REMARKS_" + queue.FILE_QUEUE_ID);
                            cell7.setAttribute("id","RETRY_" + queue.FILE_QUEUE_ID);
                            cell8.setAttribute("id","ARCHIVE_" + queue.FILE_QUEUE_ID);

                        }
                    }

                    if (table.rows.length>1)
                        for (var t = 1; t < table.rows.length; t++){
                            var tr = table.rows[t];
                            var id = tr.getAttribute('id');
                            var found = false;
                            for (var i = 0; i < json.queue.length; i++){
                                var queue = json.queue[i];
                                if (id==queue.FILE_QUEUE_ID) {
                                    found = true;
                                    break;
                                }
                            }
                            if (!found) {
                                table.deleteRow(t);
                            }
                        }
                } else {
                    count_label.innerHTML =  "0 PENDING FILE(s)";
                    if (table.rows.length>1)
                        for (var t = 1; t < table.rows.length; t++){
                            table.deleteRow(t);
                        }
                }



                setTimeout(update_queue, 1000);
            } else{
                // alert('else');
                //document.getElementById("regen_status").innerHTML = "<br>Please Wait<br><img src='" + loading_gif + "' height='15' width='128' />";
            }
        }
        parameter = "";

        xmlhttp.open("get", path_value, true);
        xmlhttp.setRequestHeader("Content-type", "text/xml");
        xmlhttp.send(parameter);
    }

    // update_queue();

    function call_link_in_background(link, params){
        // alert(link);
        if (window.XMLHttpRequest)
            xmlhttp = new XMLHttpRequest();
        else
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				get_running();get_queue();
            } 
        }

        xmlhttp.open("POST", link, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(params);
    }
    
    function call_etl_in_background(link, params){
        // alert(link);
        if (window.XMLHttpRequest)
            xmlhttp = new XMLHttpRequest();
        else
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        var p = document.getElementById("response_message");
        p.innerHTML = "loading...";

        var url = "<?=base_url('index.php/businessdoc/main/run_etl_command');?>";
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200 && xmlhttp.responseURL == url) {
                // alert(xmlhttp.responseText);
                // var p = document.getElementById("response_message");
                p.innerHTML = xmlhttp.responseText;
				get_running();get_queue();
            } 
        }

        xmlhttp.open("POST", url, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("uri="+link + "&" + params);
    }

</script>

<div class="row">
	<div class="form-group">
		<div class="col-md-12">
			<div class="panel panel-default table-responsive">
				<div class="panel-heading">
					Processing
				</div>
				<div class="panel-body">
					<table id="running_tbl" class="table table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th data-col="FILE_QUEUE_ID">ID</th>
								<th data-col="FILE_CODE">FILE CODE</th>
								<th data-col="FILE_NAME">FILE NAME</th>
								<th data-col="DATE_CREATED">DATE CREATED</th>
								<th data-col="STATUS">STATUS</th>
								<th data-col="FILE_DATE">DATE OF FILE</th>
								<th data-col="FILE_COUNT">RECORD_COUNT</th>
								<th data-col="START_TIMESTAMP">START</th>
								<th data-col="END_TIMESTAMP">END</th>
								<th data-col="REMARKS">REMARKS</th>
								<th data-col="RETRY">RETRY</th>
								<th data-col="ARCHIVE">ARCHIVE</th>
							</tr>
						</thead>
						<tbody id="running_tbl_body">
							<script id="running_tbl_template" type="text/template">
								{{#running_table_template}}
									<tr>
										<td>{{FILE_QUEUE_ID}}</td>
										<td>{{FILE_CODE}}</td>
										<td>{{FILE_NAME}}</td>
										<td>{{DATE_CREATED}}</td>
										<td>{{STATUS}}</td>
										<td>{{FILE_DATE}}</td>
										<td>{{FILE_COUNT}}</td>
										<td>{{START_TIMESTAMP}}</td>
										<td>{{END_TIMESTAMP}}</td>
										<td>{{REMARKS}}</td>
										<td><button type="button" class="btn btn-link" onClick="call_link_in_background('<?= base_url('index.php/businessdoc/main/reset_file_queue');?>','file_queue_id={{FILE_QUEUE_ID}}')"><span class="glyphicon glyphicon-refresh"></span></button></td>
										<td><button type="button" class="btn btn-link" onClick="call_link_in_background('<?= base_url('index.php/businessdoc/main/reset_file_queue');?>','file_queue_id={{FILE_QUEUE_ID}}')"><span class="glyphicon glyphicon-trash"></span></button></td>
										
									</tr>
								{{/running_table_template}}
								{{^running_table_template}}
									<tr>
										<td colspan="6">No processing files.</td>
									</tr>
								{{/running_table_template}}
							</script>
						</tbody>
					</table>
					<!-- <center><div id="running_pagination"></div></center> -->
				</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<div class="panel panel-default table-responsive">
				<div class="panel-heading">
					In Queue
					<button type="button" class="btn btn-link pull-right" style="align:right" onClick="get_queue();"><span class="glyphicon glyphicon-refresh"></span></button>
				</div>
				<div class="panel-body">
					<table id="queue_tbl" class="table table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th data-col="FILE_QUEUE_ID">ID</th>
								<th data-col="FILE_CODE">FILE CODE</th>
								<th data-col="FILE_NAME">FILE NAME</th>
								<th data-col="DATE_CREATED">DATE CREATED</th>
								<th data-col="STATUS">STATUS</th>
								<th data-col="FILE_DATE">DATE OF FILE</th>
								<th data-col="FILE_COUNT">RECORD_COUNT</th>
								<th data-col="START_TIMESTAMP">START</th>
								<th data-col="END_TIMESTAMP">END</th>
								<th data-col="REMARKS">REMARKS</th>
								<th data-col="RETRY">RETRY</th>
								<th data-col="ARCHIVE">ARCHIVE</th>
							</tr>
						</thead>
						<tbody id="queue_tbl_body">
							<script id="queue_tbl_template" type="text/template">
								{{#queue_table_template}}
									<tr>
										<td>{{FILE_QUEUE_ID}}</td>
										<td>{{FILE_CODE}}</td>
										<td>{{FILE_NAME}}</td>
										<td>{{DATE_CREATED}}</td>
										<td>{{STATUS}}</td>
										<td>{{FILE_DATE}}</td>
										<td>{{FILE_COUNT}}</td>
										<td>{{START_TIMESTAMP}}</td>
										<td>{{END_TIMESTAMP}}</td>
										<td>{{REMARKS}}</td>
										<td><button type="button" class="btn btn-link" onClick="call_link_in_background('<?= base_url('index.php/businessdoc/main/reset_file_queue');?>','file_queue_id={{FILE_QUEUE_ID}}');get_running();get_queue();"><span class="glyphicon glyphicon-refresh"></span></button></td>
										<td><button type="button" class="btn btn-link" onClick="call_link_in_background('<?= base_url('index.php/businessdoc/main/archive_file_queue');?>','file_queue_id={{FILE_QUEUE_ID}}');get_running();get_queue();"><span class="glyphicon glyphicon-trash"></span></button></td>
									</tr>
								{{/queue_table_template}}
								{{^queue_table_template}}
									<tr>
										<td colspan="6">No Files in queue.</td>
									</tr>
								{{/queue_table_template}}
							</script>
						</tbody>
					</table>
					<center><div id="queue_pagination"></div></center>
				</div>
				<div class="panel-footer">
					<p> NOTE: files must be put in their corresponding folder in "\\host\smntp etl public" ex. (bdoca > "\\host\smntp etl public\files\upload_files\bdoca")</p>
					<p> </p>
					<button class="btn btn-default" onClick="call_etl_in_background(&quot;index.php/uploader/load_files_to_queue&quot;,null);">Load files to queue</button>
					<button class="btn btn-default" onClick="call_etl_in_background(&quot;index.php/uploader/process_files_in_queue&quot;,null);">Process next in queue</button>
					
					<p id="response_message"> </p>
				</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	loadingScreen('on');

	var $queue_tbl = $('#queue_tbl');
	var $queue_pagination = $('#queue_pagination');
	var queue_pagination = new Pagination($queue_pagination, $queue_tbl, 'sort_columns');

	var $running_tbl = $('#running_tbl');
	var $running_pagination = $('#running_pagination');
	var running_pagination = new Pagination($running_pagination, $running_tbl, 'sort_columns');

	function get_queue(){
		var ajax_type = 'POST';
        var url = "<?= base_url('index.php/businessdoc/main/get_file_queue'); ?>";

        var success_function = function(responseText)
        {
            var tbl_data = $.parseJSON(responseText);
			if (tbl_data!=null) {
				if (tbl_data.queue!=null) {	
					queue_pagination.create(tbl_data.queue, 'queue_table_template');
					queue_pagination.render();
				}
			}
		   loadingScreen('off');
        };

        ajax_request(ajax_type, url, null, success_function);
	}

	get_queue();
	
	function get_running(){
		var ajax_type = 'POST';
        var url = "<?= base_url('index.php/businessdoc/main/get_running_file'); ?>";

        var success_function = function(responseText)
        {
            var tbl_data = $.parseJSON(responseText);
			if (tbl_data!=null) {
				if (tbl_data.queue!=null) {
					running_pagination.create(tbl_data.queue, 'running_table_template');
					running_pagination.render();
				} 
			}
			setTimeout(get_running,3000);
        };

        ajax_request(ajax_type, url, null, success_function);
	}
	
	get_running();

	// setInterval(get_running(),1000);
</script>
