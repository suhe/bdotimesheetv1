<?php
$this->load->view('site_header');
//iman edit 2
$jobtype_data = $this->dataModel->getJobType();
$jobtype = '';
if ( count( $jobtype_data ) > 0 ) {
	$jobtype .='<select name=jobtype_id id=jobtype_id> ';
	foreach ($jobtype_data as $k=>$v) {
		$selected = '';
		if ( $v['jobtype_id'] == $form['jobtype_id'] ) {
			$selected = ' selected ';
		} 
		$jobtype .= '<option value='.$v['jobtype_id'] . $selected .'>'. $v['jobtype'] .'</option>';
	}
	$jobtype .= '</selected>';

}	
/*
$employee_data = $this->modelMain->getUserEmployee();
$employee = '';
$approval = '';
if ( count( $employee_data ) > 0 ) {
	$employee .='<select name=employee_id>';
	$approval .='<select name=approval>';
	foreach ($employee_data as $k=>$v) {
		$selected = '';
		$selected1 = '';
		if ( $v['employee_id'] == $form['employee_id'] ) {
			$selected = ' selected ';
		} 
		
		if ( $v['employee_id'] == $form['approval'] ) {
			$selected1 = ' selected ';
		} 
		$employee .= '<option value='.$v['employee_id'] . $selected .'>'. $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'].'</option>';
		$approval .= '<option value='.$v['employee_id'] . $selected1 .'>'. $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'].'</option>';
	}
	$employee .= '</selected>';
	$approval .= '</selected>';
}	
*/
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/data/jobUpdate/" >
		<input type="hidden" name="job_id" value="<?=$form['job_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Job Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Job Code: </td>
			<td><input type="text"  class="inputtext" id="job_no"  name="job_no" value="<?=$form['job_no'] ?>" size="60" /></td>
		</tr>

		<tr>
			<td class="label">Job Name : </td>
			<td><input type="text"  class="inputtext" id="job"  name="job" value="<?=$form['job'] ?>" size="40" /></td>
		</tr>
		
		<tr>
			<td class="label">Jobtype : </td>
			<td><?=$jobtype ?> </td>
		</tr>
<!--		
		<tr>
			<td class="label">Team Structure: </td>
			<td><input type="checkbox"  class="inputtext" size="40" />MIC
			<input type="checkbox"  class="inputtext" size="40" />AIC
			<input type="checkbox"  class="inputtext" size="40" />ASS
			</td>
		</tr>
-->
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="Save" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				
				</td>
		</table>	
		</fieldset>
	</form>	
</table>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#job_no').focus();
	}
);	

$(function () {
	$('#submit').click( function (e) {
		var job_no = $.trim($('#job_no').val()); 
		var job = $.trim( $('#job').val());
		var errSubmit = '';

		if (job_no.length == 0) {
			$('#job_no').focus();
			errSubmit += 'Job Code must be fill out\n';	
		}

		if (job.length == 0) {
			$('#job').focus();
			errSubmit += 'Job  must be fill out\n';	
		} 

		if (errSubmit) {
			alert(errSubmit);
			return false;
		} else  {
			return true;
		}			
	});
});
</script>
						

<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Job List</th>
</tr>
		<tr>
			<th>No</th>
			<th>Job Code</th>
			<th>Job Name</th>
			<th>Job Type</th>
			<th>Edit</th>



		</tr>
	</thead>
	<tbody>
<?php
if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td>$v[job_no]</td>
				<td>$v[job]</td>
				<td>$v[jobtype]</td>
				<td align='right'><a href='$site/data/job/$v[job_id]/'>[ edit ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>
</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#job_code').focus();
	}
);	

</script>
<?php
$this->load->view('site_footer');

?>