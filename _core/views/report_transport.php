<?php
$this->load->view('site_header');




?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportTransport/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Summary Transport by Period Filter</legend>
		<table align=center>
		<tr><td></tr>
		

		<tr>
			<td class="label">Periode : </td>
			<td>
			  <input type="text"  class="inputtext date" readonly="true" id="date_from" name="date_from" value="<?=$form['date_from'] ?>" size="60" style='width:75px;' />
			   / 
			   <input type="text"  class="inputtext date" readonly="true" id="date_to" name="date_to" value="<?=$form['date_to'] ?>" size="60" style='width:75px;' />
			   <?=form_dropdown('paid',config_item('var_paid'))?>  
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
<a id=export style="cursor:pointer;"><b>Export(Transport Summary)<b></a> |
<a href="<?=site_url('report/reportTransportExcelDetails')?>" style="cursor:pointer;"><b>Export(Transport Details)<b></a>
<div id=inner></div>
<div id=excel>
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Summary Transport by Period </legend>
<table align=center>
<tr><td></tr>

<tr>
	<td class="label">Periode : </td>
	<td><?=$form['date_from'] ?> to <?=$form['date_to'] ?>
	</td>
</tr>
</table>	
		
<table class="grid">
<thead>
<tr>
  <th class="table-head" colspan="13">Summary Transport</th>
</tr>
<tr>
	<th rowspan=3>No</th>
	<th rowspan=3>NIK</th>
	<th rowspan=3>Name</th>
	<th colspan="8" style='text-align:center;'>Transport</th>
	<th style="text-align:center" rowspan="2" colspan="2" class='currency'>Total</th>
    
</tr>

<tr>
	<th style="text-align:center" colspan="2" class='currency'>Office</th>
	<th style="text-align:center" colspan="2" class='currency'>In Town Client</th>
	<th style="text-align:center" colspan="2" class='currency'>Out of Town Client</th>
	<th style="text-align:center" colspan="2" class='currency'>Uknown</th>
</tr>

<tr>
	<th class='currency'>N</th>
	<th class='currency'>Cost</th>
	<th class='currency'>N</th>
	<th class='currency'>Cost</th>
	<th class='currency'>N</th>
	<th class='currency'>Cost</th>
	<th class='currency'>N</th>
	<th class='currency'>Cost</th>
	<th class='currency'>N</th>
	<th class='currency'>Cost</th>
</tr>

	</thead>
	<tbody>
   <?=$row ?>
  <tr><td colspan="13"><i>printed date <?=date("d M Y H:i:s");  ?></i>
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


	$('input.date').datepick({dateFormat:'dd/mm/yy', showWeeks:true, firstDay: 1, minDate:new Date(2008,1,1)});
	
});

</script>
<?php
$this->load->view('site_footer');

?>