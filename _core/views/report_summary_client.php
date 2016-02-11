<?php
$this->load->view('site_header');

$client_data = $this->reportModel->getClient();
$client = '';
if ( count( $client_data ) > 0 ) {
	$client .='<select id=client_id name=client_id>';
	$client .= '<option value="">Please Choose</option>';
	foreach ($client_data as $k=>$v) {
		$selected = '';
		if ( $v['client_id'] == $form['client_id'] ) {
			$selected = ' selected ';
		} 
		$client .= '<option value='.$v['client_id'] . $selected .'>'. $v['client_name'] .'</option>';
	}
	$client .= '</selected>';
}

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>#" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Summary client Report</legend>
		<table align=center>
		<tr><td> <td></tr>
		
		<tr>
			<td class="label">Client : </td>
			<td><?=$client ?> </td>
		</tr>

		<tr>
			<td class="label">Periode : </td>
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
<a id=export style="cursor:pointer;"><b>Export to EXCEL</b></a>
<div id=inner></div>
<div id=excel>
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Summary client Report</legend>
</fieldset>
<table align=center>
<tr><td>&nbsp;</tr>

<tr>
	<td class="label">Partner: </td>
	<td><?=$employee_name ?> </td>
</tr>

<tr>
	<td class="label">Periode : </td>
	<td><?=$form['date_from'] ?> to <?=$form['date_to'] ?>
	</td>
</tr>
</table>	
		
<table class="grid">
<thead>
<tr>
  <th class="table-head" colspan="9">SUMMARY client Report</th>
</tr>
<tr>
	<th>No</th>
	<th>Company Name</th>
	<th>Project Code</th>
	<th>MIC</th>
	<th>Type of Project</th>
	<th>Day</th>
	<th>Year Ended</th>
	<th>Budget</th>
	<th>Realisation</th>
</tr>

	</thead>
	<tbody>
   <?=$row ?>
  <tr><td colspan=9><i>printed date <?=date("d M Y H:i:s");  ?></i>
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