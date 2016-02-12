﻿<?php
$this->load->view('site_header');

$form['client_no']   = "";
$employee_data = $this->reportModel->getProject();
$employee = '';
if ( count( $employee_data ) > 0 ) {
	$employee .='<select name=project_id>';
	foreach ($employee_data as $k=>$v) {
		$selected = '';
		if ( $v['project_id'] == $form['project_id'] ) {
			$selected = ' selected ';
		} 
		$employee .= '<option value='.$v['project_id'] . $selected .'>'. $v['project_no'] .'</option>';
	}
	$employee .= '</selected>';
}	


$client_data = $this->reportModel->getClient();
$client = '';
if ( count( $client_data ) > 0 ) {
	$client .='<select name=client_id>';
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
	<form id="form" method="POST" action="<?=$site ?>/report/reportBudgetActual/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Project Budget Cost Realisasi</legend>

		<table align=center>
		<tr><td>&nbsp;</tr>

<tr>
			<td class="label">Client : </td>
			<td><?=$client ?> </td>
		</tr>
		
		<tr>
			<td class="label">Project : </td>
			<td><?=$employee ?> </td>
		</tr>

<tr>
			<td class="label" valign="top" style="vertical-align:top;">Project Code </td>
			<td><input type="text"  class="inputtext" name="project_no" value="<?=$form['project_no']; ?>" size="60" style="width:175px;"/> </td>
		</tr>


		<tr>
			<td class="label">Periode : </td>
			<td><input type="text"  class="inputtext" name="date_from" value="<?=$form['date_from']; ?>" size="60" style="width:75px;"/> / 
				<input type="text"  class="inputtext" name="date_to" value="<?=$form['date_to']; ?>" size="60" style="width:75px;"/>
			</td>
		</tr>
<!--
		<tr>
			<td class="label">Periode : </td>
			<td><input type="text"  class="inputtext" name="date_from" value="<?=$form['date_from'] ?>" size="60" style="width:75px;"/> / 
				<input type="text"  class="inputtext" name="date_to" value="<?=$form['date_to'] ?>" size="60" style="width:75px;"/>
			</td>
		</tr>
-->
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
<div id="tables" class="block">
<?php
if ( count( $budgetTotal ) > 0 ) {
	$this->load->view('report_project_budget_actual');
}
?>
</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#project_id').focus();
	}
);	
$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

});

</script>
<?php
$this->load->view('site_footer');
?>