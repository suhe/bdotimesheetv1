<?php
	$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>/client/Update/" >
		<input type="hidden" id="client_id" name="client_id" value="<?=$form['client_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Client Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message" id="msg"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Client Code : </td>
			<td><input type="text"  class="inputtext mandatory" id="client_no" name="client_no" value="<?=$form['client_no'] ?>" size="40" /></td>
		</td>
		<tr>
			<td class="label">Client Name : </td>
			<td><input type="text"  class="inputtext mandatory" id="client_name" name="client_name" value="<?=$form['client_name'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Address : </td>
			<td><input type="text"  class="inputtext" id="address" name="address" value="<?=$form['address'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Phone : </td>
			<td><input type="text"  class="inputtext" id="phone" name="phone" value="<?=$form['phone'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Fax : </td>
			<td><input type="text"  class="inputtext" id="fax" name="fax" value="<?=$form['fax'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Contact Person : </td>
			<td><input type="text"  class="inputtext" id="contact" name="contact" value="<?=$form['contact'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Contact Person Email : </td>
			<td><input type="text"  class="inputtext" id="contact_email" name="contact_email" value="<?=$form['contact_email'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Line of Business: </td>
			<td><input type="text"  class="inputtext" id="lob" name="lob" value="<?=$form['lob'] ?>" size="40" /></td>
		</tr>

		<tr>
			<td class="label">Web Site Address: </td>
			<td><input type="text"  class="inputtext" id="website" name="website" value="<?=$form['website'] ?>" size="40" /></td>
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
 	$('#client_no').focus();
	}
);	

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});
});

$(function() {
	$('#form').submit(function(event) {
		event.preventDefault();
		event.stopPropagation();

		var _post = [{name:'ts', value:new Date().getTime()}], _err  = 0;

		$('input, select, textarea', $('#form')).each(function () {
			var _val = $(this).val();
			if($(this).hasClass('mandatory') && ! _val) _err=1;
			_post.push({name:this.id, value:_val});
		});

		if(_err) {
			$('#msg').html('Kolom Wajib Diisi');
			return false;
		}
		else {
			$.post('<?=$site?>/client/Update/', _post, function(resp) {
				$("#msg").html('Data Sukses Disimpan');
				$("button", $("#form")).remove();
			});
		}
	});
});
</script>
<?php
	$this->load->view('site_footer');
	
?>