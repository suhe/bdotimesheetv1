<?php
$this->load->view('site_header');?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form_project" method="POST">
		<input type="hidden" id="id" name="id" value="<?=isset($form['id']) ? $form['id']:null ?>"  />
		<fieldset class="form-fieldset">
		<legend class="form-legend">Allowance Information</legend>
	<table>
	<tr>
		<td colspan=2 align=left style="text-align:left;" class="label-message" id="msg"></td>
	</td>
	<tr>
	<td>
	<table style="vertical-align:top;">
		<tr>
			<td class="label">Client Name   : </td>
			<td><?=form_dropdown('client_id', $client_lists,isset($form['client_id']) ? $form['client_id']:0 )?>
				* <span class="error" id="client_id_error">The field is required</span>
			</td>
			
		<tr>
			<td class="label">Project No : </td>
			<td><?=form_dropdown('project_id', [],isset($form['project_id']) ? $form['project_id']:0 )?>
				* <span class="error" id="project_id_error">The field is required</span>
			</td>
		</td>
		<tr>
			<td class="label">Approval Project : </td>
			<td><?=form_dropdown('approval_id',[],isset($form['approval_id']) ? $form['approval_id']:0 )?>
			* <span class="error" id="approval_id_error">The field is required</span>
			</td>
		</td>
		<tr>
			<td class="label">Start Date : </td>
			<td><input type="text"  class="inputtext date" readonly="true" id="start_date" name="start_date" value="<?=isset($form['date_from']) ? $form['date_from'] : ""?>" size="40" style="width:80px;" style="width:80px;"/>
				 &nbsp;&nbsp; Finish Date :  &nbsp;&nbsp;	
				<input type="text"  class="inputtext date" readonly="true" id="finish_date" name="finish_date" value="<?=isset($form['date_to']) ? $form['date_to'] : ""?>" size="40" style="width:80px;" style="width:80px;"/>
				* <span class="error" id="date_error">The field is required</span>
			</td>
		</tr>
		<tr>
			<td class="label">Number of Employee : </td>
			<td><input type="text" class="inputtext mandatory" id="employee_total" name="employee_total"  value="<?=isset($form['total_employee']) ? $form['total_employee'] : ""?>" size="40" /> 
			* <span class="error" id="employee_total_error">The field is required</span></td>
		</td>
		<tr>
			<td class="label">Total Allowance : </td>
			<td><input type="text" class="inputtext mandatory currency" id="total_allowance" name="allowance_total"  value="<?=isset($form['total']) ? $form['total'] : 0?>" size="18" /> 
			* <span class="error" id="allowance_total_error">The field is required</span>
			</td>
		</td>
		<tr>
			<td class="label" nowrap style="width:180px;">Date Realization : </td>
			<td><input type="text"  class="inputtext date mandatory" readonly="true" id="date_realization" name="date_realization" value="<?=isset($form['date_realization']) ? $form['date_realization'] : ""?>" size="40" style="width:80px;"/> 
			* <span class="error" id="date_realization_error">The field is required</span>
			</td>
		</tr>
		<tr>
			<td class="label" nowrap style="width:180px;">Date Approved : </td>
			<td><input type="text"  class="inputtext date mandatory" readonly="true" id="date_approved" name="date_approved" value="<?=isset($form['date_approved']) ? $form['date_approved'] : ""?>" size="40" style="width:80px;"/> </td>
		</tr>
		
	</table>
</tr>
<tr>
	<td colspan=2 align="center" style="text-align:center;">
		<div style="margin-left:30%" class="ff3 UILinkButton" >
			<input type="submit"  id="submit"  value="Save" class="ff3 UILinkButton_A"/>
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<input type="button"  id="back"  value="Back" class="ff3 UILinkButton_A"/>
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>
	</td>
</tr>	
</fieldset>
</form>
</table>	
		
<script>
$(function() {
	$('input.date').datepick({dateFormat:'dd/mm/yy', showWeeks:true,firstDay: 1, minDate:new Date(2016,1,1)});

	var id = $('input[name="id"]').val();
	var client_id = $("select[name='client_id']").val();
	var project_id = $("select[name='project_id']").val();
	var pid = 0;
	var aid = 0;
	

	if(!id) {
		pid = project_id;
		aid = 0;
	}else {
		pid = <?=isset($form['project_id']) ? $form['project_id'] : 0 ?>;
		aid = <?=isset($form['approval_id']) ? $form['approval_id'] : 0 ?>;
	}
	
	
	loadProject(client_id,pid);
	loadApprovalProject(pid,aid);
	
	function loadProject(args,id) {
		$.getJSON("<?=base_url()?>timesheet/loadclientproject",
			{ client_id: args },
			function(data) {
				var model = $('select[name="project_id"]');
				var model2 = $('select[name="approval_id"]');
				model.empty();
				model2.empty();
				model.append("<option value=''>Please select a project</option>");
				$.each(data, function(key,value) {
					if(value.project_id == id)  
						model.append("<option selected='selected' value='"+ value.project_id +"'>" + value.project_no + "</option>");
					else
						model.append("<option value='"+ value.project_id +"'>" + value.project_no + "</option>");
				});
					
			});
	}

	function loadApprovalProject(args,id) {
		$.getJSON("<?=base_url()?>timesheet/loadapprovalproject",
			{ project_id: args },
			function(data) {
				var model = $('select[name="approval_id"]');
				model.empty();
				model.append("<option value=''>Please select a employee</option>");
				$.each(data, function(key,value) {
					if(value.employee_id == id)  
						model.append("<option selected='selected' value='"+ value.employee_id +"'>" + value.approval_name + "</option>");
					else
						model.append("<option value='"+ value.employee_id +"'>" + value.approval_name + "</option>");
				});		
		});
		
		
	}

	$("select[name='client_id']").change( function (e) {
		var id = $(this).val();
		loadProject(id,0);
		
	});

	$("select[name='project_id']").change( function (e) {
		var prid = $(this).val();
		loadApprovalProject(prid,0);
		
	});


	function isValidDate(date_from,date_to) {
		//date format 31/08/2015
		var date_from = date_from.split("/");
		var date_to = date_to.split("/");
		var start_date = new Date(date_from[2], date_from[1] - 1, date_from[0]);
		var end_date = new Date(date_to[2], date_to[1] - 1, date_to[0]);
		if (start_date <= end_date) {
			return false;
		}
		return true;
	}

	$("#form_project").submit( function (e) {
		e.preventDefault();
		var id = $("input[name='id']").val();
		var client_id = $("select[name='client_id']").val();
		var project_id = $("select[name='project_id']").val();
		var approval_id = $("select[name='approval_id']").val();
		var date_from = $('input[name="start_date"]').val();
		var date_to = $('input[name="finish_date"]').val();
		var employee_total = $('input[name="employee_total"]').val();
		var allowance_total = $('input[name="allowance_total"]').val();
		var date_realization = $('input[name="date_realization"]').val();
		var date_approved = $('input[name="date_approved"]').val();

		//validation manual
		if(!project_id){
			$("#project_id_error").show("slow");
		} else {
			$("#project_id_error").hide("slow");
		}

		if(!approval_id){
			$("#approval_id_error").show("slow");
		}else {
			$("#approval_id_error").hide("slow");
		}

		if(!date_from || !date_to){
			$("#date_error").show("slow");
		}else{
			$("#date_error").hide("slow");
		}

		if(!employee_total){
			$("#employee_total_error").show("slow");
		}else {
			$("#employee_total_error").hide("slow");	
		}

		if(!allowance_total){
			$("#allowance_total_error").show("slow");
		}else{
			$("#allowance_total_error").hide("slow");
		}

		if(!date_realization){
			$("#date_realization_error").show("slow");
		}else {
			$("#date_realization_error").hide("slow");
		}
		
		if(isValidDate(date_from,date_to) == false) {
		}else {
			alert("date is not valid please fix it");
			 $('input[name="start_date"]').focus();
		}

		if(project_id && approval_id && date_from && date_to && employee_total && allowance_total && date_realization) {
			$.ajax({
			    type:'POST',
			    data:{id:id,project_id:project_id,approval_id:approval_id,date_from:date_from,date_to:date_to,employee_total:employee_total,allowance_total:allowance_total,date_realization:date_realization,date_approved:date_approved},
			    dataType : 'json',
			    url:'<?=base_url()?>timesheet/allowance_update',
			    success:function(data) {
			     	if(data.error == false) {
			     		$(location).attr('href', '<?=base_url()?>timesheet/allowance')
				     }
			    }
			});
		}
		
		return false;
	});

	
	
});
</script>

<style>
.error {color:red;font-style:italic;font-weight:bold;display:none}
</style>

<?php
$this->load->view('site_footer');
?>