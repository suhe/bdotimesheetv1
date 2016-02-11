<?php
$this->load->view('site_header');

if ($form['user_id'] === 0 )	{
	$employee_data = $this->adminModel->getUserEmployee();
} 
else {
	$employee_data = $this->adminModel->getUserEmployeeActive();
}
	
$employee = '';
if ( count( $employee_data ) > 0 ) {
	$employee .='<select name=employee_id>';
	foreach ($employee_data as $k=>$v) {
		$selected = '';
		if ( $v['employee_id'] == $form['employee_id'] ) {
			$selected = ' selected ';
		} 

		$employee .= '<option value='.$v['employee_id'] . $selected .'>'. $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'].'</option>';
	}
	$employee .= '</selected>';
}	
	

$acl_data = $this->adminModel->getUserACL();
$acl = '';
if ( count( $acl_data ) > 0 ) {
	$acl .='<select name=acl>';
	foreach ($acl_data as $k=>$v) {
		$selected = '';
		if ( $v['acl'] == $form['acl'] ) {
			$selected = ' selected ';
		} 
		
		$acl .= '<option value='.$v['acl'] . $selected .'>'. $v['aclname'].' </option>';
	}
	$acl .= '</selected>';
}	

$status_data = $this->adminModel->getUserActive();
$status = '';
if ( count( $status_data ) > 0 ) {
	$status .='<select name=user_active>';
	
	foreach ($status_data as $k=>$v) {
		$selected = '';
		if ( $v['lookup_code'] == $form['user_active'] ) {
			$selected = ' selected ';
		} 
		
		$status .= '<option value='.$v['lookup_code'] . $selected .'>'. $v['lookup_label'].' </option>';
	}
	$status .= '</selected>';
}	

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>/admin/userUpdate/" >
		<input type="hidden" name="user_id" value="<?=$form['user_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">User Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Employee : </td>
			<td><?=$employee ?> </td>
		</td>
		<tr>
			<td class="label">Access Level : </td>
			<td><?=$acl ?> </td>
		</tr>

		<tr>
			<td class="label">Status: </td>
			<td><?=$status ?> </td>
		</tr>

		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
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
<!--
				<div class="ff3 UILinkButton" style="padding-left:10px;">
					<input type="button"  id="reset"  value="Reset Password" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
-->				
				</td>
		</table>	
		</fieldset>
	</form>	
</div>

<script>
// When the page is ready
$(document).ready(function(){
 	$('#client_no').focus();
	}
);	

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

	$('#reset').click( function (e) {
		window.location='<?=$reset?>';
	});
});
</script>
<?php
	$this->load->view('site_footer');
	
?>