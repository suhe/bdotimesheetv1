<?php
$this->load->view('site_header');

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
	<form id="form" method="POST" action="<?=$site ?>/data/departmentUpdate/" >
		<input type="hidden" id="department_id" name="department_id" value="<?=$form['department_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Department Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Department Code: </td>
			<td><input type="text"  class="inputtext" id="departmentcode" name="departmentcode" value="<?=$form['departmentcode'] ?>" size="60" /></td>
		</tr>

		<tr>
			<td class="label">Department Name : </td>
			<td><input type="text"  class="inputtext" id="department" name="department" value="<?=$form['department']?>" size="40" /></td>
		</tr>

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
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Department List</th>
</tr>
		<tr>
			<th>No</th>
			<th>Department Code</th>
			<th>Department Name</th>
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
				<td>$v[departmentcode]</td>
				<td>$v[department]</td>
				<td align='right'><a href='$site/data/department/$v[department_id]/'>[ edit ]</a></td></tr>";
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
 	$('#job_no').focus();
	}
);	

$(function () {
	$('#submit').click( function (e) {
		var departmentcode = $.trim($('#departmentcode').val()); 
		var department = $.trim( $('#department').val());
		var errSubmit = '';

		if (departmentcode.length == 0) {
			$('#departmentcode').focus();
			errSubmit += 'Department Code must be fill out\n';	
		}

		if (department.length == 0) {
			$('#department').focus();
			errSubmit += 'Department  must be fill out\n';	
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
<?php
$this->load->view('site_footer');

?>