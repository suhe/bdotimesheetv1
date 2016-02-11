<?php
$this->load->view('site_header');	

$acl_data = $this->adminModel->getUserACL();
$acl = '';
if ( count( $acl_data ) > 0 ) {
	foreach ($acl_data as $k=>$v) {
		if ( $v['acl'] == $form['acl'] ) {
			$acl .= $v['aclname'];
		} 
	}
}	

$status_data = $this->adminModel->getUserActive();
$status = '';
if ( count( $status_data ) > 0 ) {
	
	foreach ($status_data as $k=>$v) {
		if ( $v['lookup_code'] == $form['user_active'] ) {
			$status= $v['lookup_label'];
		} 
	}
}	

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>/admin/resetUpdate/" >
		<input type="hidden" name="user_id" value="<?=$form['user_id'] ?>"  />
		<input type="hidden" name="employee_id" value="<?=$form['employee_id'] ?>"  />
		<input type="hidden" name="nik" value="<?=$form['EmployeeID'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">User Password Reset</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">N.I.K</td>
			<td><?=$form['EmployeeID'] ?></td>
		</td>

		<tr>
			<td class="label">Employee : </td>
			<td><?=$form['EmployeeFirstName']." ". $form['EmployeeMiddleName'] ." ". $form['EmployeeLastName']  ?></td>
		</td>
		<tr>
			<td class="label">Department  : </td>
			<td><?=$form['Department'] ?></td>
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
					<input type="submit"  id="submit"  value="Reset Password" class="ff3 UILinkButton_A"/>
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