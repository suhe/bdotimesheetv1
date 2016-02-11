<?php
$this->load->view('site_header');
/*
//iman edit 2
$department_data = $this->dataModel->getJobDepartment();
$department = '';
if ( count( $department_data ) > 0 ) {
	$department .='<select name=department_id id=department_id> ';
	foreach ($department_data as $k=>$v) {
		$selected = '';
		if ( $v['department_id'] == $form['department_id'] ) {
			$selected = ' selected ';
		} 
		$department .= '<option value='.$v['department_id'] . $selected .'>'. $v['department'] .'</option>';
	}
	$department .= '</selected>';

}	
*/
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/data/jobTypeUpdate/" >
		<input type="hidden" name="jobtype_id" value="<?=$form['jobtype_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Job Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Job Type Code : </td>
			<td><input type="text"  class="inputtext" id="jobtype_no"  name="jobtype_no" value="<?=$form['jobtype_no'] ?>" size="60" /></td>
		</tr>

		<tr>
			<td class="label">Job Type : </td>
			<td><input type="text"  class="inputtext" id="jobtype"  name="jobtype" value="<?=$form['jobtype'] ?>" size="40" /></td>
		</tr>
		<!--iman edit 2 
		<tr>
			<td class="label">Department : </td>
			<td><?=$department ?> </td>
		</tr>
		-->
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
		var job_no = $.trim($('#jobtype_no').val()); 
		var job = $.trim( $('#jobtype').val());
		var errSubmit = '';

		if (job_no.length == 0) {
			$('#jobtype_no').focus();
			errSubmit += 'Job Type Code must be fill out\n';	
		}

		if (job.length == 0) {
			$('#jobtype').focus();
			errSubmit += 'Job  Type must be fill out\n';	
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
			<th>Job Type Code</th>
			<th>Job Type </th>
			<!--<th>Department </th> iman edit 2-->
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
				<td>$v[jobtype_no]</td>
				<td>$v[jobtype]</td>
				<!--iman edit 2
				<td>$v[department]</td>-->
				<td align='right'><a href='$site/data/jobtype/$v[jobtype_id]/'>[ edit ]</a></td></tr>";
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