<?php
	$this->load->view('site_header_nomenu');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>admin/passwordUpdate/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Change Password</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['err'] ?></td>
		</td>
		<tr>
			<td class="label">New Password : </td>
			<td><input type="password"  class="inputtext" id="password_new" name="password_new" value="" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Confirm New Password : </td>
			<td><input type="password"  class="inputtext" id="password_confirm" name="password_confirm" value="" size="40" /></td>
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
				
				</td>
		</table>	
		</fieldset>
	</form>	
</div>

<script>
// When the page is ready
$(document).ready(function(){
 	$('#password_new').focus();
	}
);	

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});
});


$(function () {
	$('#submit').click( function (e) {
		var password_new	= $.trim( $('#password_new').val());
		var password_confirm= $.trim( $('#password_confirm').val());
		var errSubmit = '';


		if (password_new.length == 0) {
			$('#password_new').focus();
			errSubmit += 'New Password can not be Empty. \n';	
		} 

		if (password_new.length <= 3) {
			$('#password_new').focus();
			errSubmit += 'New Password minimum 4 character... \n';	
		} 


		if (password_confirm.length == 0) {
			$('#password_confirm').focus();
			errSubmit += 'Confirm New Password can not be Empty. \n';	
		} 

		if (password_new.length == 0) {
			$('#password_new').focus();
			errSubmit += 'New Password can not be Empty. \n';	
		} 

		if (password_confirm  != password_new ) {
			$('#password_new').focus();
			errSubmit += 'New Password and Confirm New Password must be the same. \n';	
		} 


		if (errSubmit)  {
			alert(errSubmit);
			return false;
		} 
		else {
			return true;
			//$('#form').submit(); 
		}
	});
});
</script>
<?php
	$this->load->view('site_footer');
	
?>