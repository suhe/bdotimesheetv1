<?php
	$this->load->view('header');
?>
<body style="background-color:#333333;">

<div id="center">
<div id="wrapper">
<div id="content"> 

	<div id="login" style="background-color:#333333;">
	<form id="form" class="form-container"  method="POST" action="<?=$site ?>/home/login/" style="padding-top:190px; padding-left:300px; " >
	<table align=center>
	<tr>
		<td></td>
		<td><b><?=$msg?></b><br></td>
	</tr>
	<tr>
		<td class="label-login">N.I.K : </td>
		<td>
			<input type="text" class="inputtext" id="nik" name="nik" value="<?=$nik ?>" size="30" />
			</td>
	</td>
	
	<tr>
		<td class="label-login">Password : </td>
		<td><input type="password"  class="inputpassword" id="pass" name="pass" value="" size="30" /></td>
	</tr>
	<tr>
		<td></td>
		<td>
<div class="ff3 UILinkButton">
	<input type="submit"  id="submit"  value="Login" class="ff3 UILinkButton_A"/><div class="UILinkButton_RW"><div class="UILinkButton_R"/></div>
</div>
</td>
</table>
</form>	
</div>
<div id=version>
   <!-- ENDRO
	<b>Version : 2.3.0 - 18072011</b>
	
	<b>Version : 2.3.6 - 09122011</b>
	
	<b>Version : 2.3.7 - 3022012</b>

	<b>Version : 2.3.8 - 17022012</b>
		-->
	<b>Version : 3 - Time Load : {elapsed_time} Second</b>
</div>
<!-- Validate Login -->
<script>
// When the page is ready
$(document).ready(function(){
 	$('#nik').focus();
	}
);	

$(function () {
	$('#submit').click( function (e) {
		var nik = $.trim($('#nik').val()); 
		var pass = $.trim( $('#pass').val());
		var errSubmit = '';

		if (nik.length == 0)
		{
			$('#nik').focus();
			errSubmit += 'N.I.K must be fill out \n';	
		}

		if (pass.length == 0)
		{
			$('#pass').focus();
			errSubmit += 'Password must be fill out \n';	
		} 

		if (errSubmit)
		{
			alert(errSubmit);
			return false;
		} 
		else 
		{
			return true;
			//$('#form').submit(); 
		}			
	});
});
</script>
						
</div>
</div>
</div>

<?php
	$this->load->view('footer');
?>