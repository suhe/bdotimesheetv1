<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportActualEmployee/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Actual Project Per Employee </legend>
		<table align=center>
		<tr>
			<td class="label">Date : </td>
			<td>
			  <input type="text"  class="inputtext date" readonly="true" id="date_from" name="date_from" value="<?=$form['date_from'] ?>" size="60" style='width:75px;' />
			   / 
			   <input type="text"  class="inputtext date" readonly="true" id="date_to" name="date_to" value="<?=$form['date_to'] ?>" size="60" style='width:75px;' />
			</td>
		</tr>
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="View Report" class="ff3 UILinkButton_A"/>
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
</table>
<a id=export style="cursor:pointer;"><b>Export to EXCEL<b></a>
<div id=inner></div>
<div id=excel>
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Actual per  Employee </legend>
<table align=center >
<tr>
	<td class="label">Periode : </td>
	<td><?=$form['date_from'] ?> to <?=$form['date_to'] ?>
	</td>
</tr>
</table>	
		
<table class="grid" style="font-weight: normal;">
<thead>
<tr>
  <th class="table-head" colspan="8">Actual Project Report per Employee</th>
</tr>
<tr>
	<th>No</th>
	<th>Name</th>
	<th>NIK</th>
    <th>Manager</th>
    <th>Hire Date</th>
	<th style="text-align: center;">HRD Charge (Hour)</th>
	<th style="text-align: center;">Project (Hour)</th>
    <th style="text-align: center;">Total (Hour)</th>
</tr>

	</thead>
	<tbody>
   <?=$row ?>
  <tr><td colspan="8"><i>printed date <?=date("d M Y H:i:s");  ?></i>
	</tbody>
	</table>
</div>
</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#employee_id').focus();
	}
);	


$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

	$('#export').click( function (e) {
	  var data = $('#excel').html();
    
    $("#inner").append('<form id="exportform" action="<?=$site ?>/report/excel" method="post" target="_blank"><input type="hidden" id="exportdata" name="exportdata" /></form>');
    $("#exportdata").val(data);
    $("#exportform").submit().remove();
    
	});


	$('input.date').datepick({dateFormat:'dd-mm-yy', showWeeks:true, firstDay: 1, minDate:new Date(2008,1,1)});
	
});

</script>
<?php
$this->load->view('site_footer');

?>