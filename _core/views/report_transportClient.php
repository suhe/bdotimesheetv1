<?php 
$this->load->view('site_header');
$cClient ='<select name=client_id id=client_id class="mandatory">';
$cClient .= '<option value=0>Please Choose</option>';
foreach ($client as $k=>$v) {
	$selected = '';
	if ( $v['client_id'] == $form['client_id'] ) {
		$selected = ' selected ';
	} 
	$cClient .= '<option value='.$v['client_id']. $selected .'>'. $v['client_name'] .'</option>';
}
$cClient .= '</selected>';    
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportTransportClient" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Summary Transport by Client Filter</legend>
		<table align=center>
		<tr><td></tr>
		

		<tr>
			<td class="label">Client : </td>
			<td>
			  <?=$cClient;?>
                
            </td>
		</tr>
        <tr>
			<td class="label">Periode >=: </td>
			<td>
			  <?=form_dropdown('year',config_item('lyear'));?>  
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
<!--<a id="export" style="cursor:pointer;"><b>Export by Excel<b></a> |-->
<a href="<?=site_url('report/reportTransportClientExcel')?>" style="cursor:pointer;"><b>Export to Excel<b></a>
<div id="inner"></div>
<div id="excel">
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Summary Transport by Period </legend>

<table>
<tr><td></tr>
<tr>
	<td class="label">Client &amp; Year End : </td>
	<td><?=$form['client'] ?> &amp; >= <?=$form['year'] ?></td>
</tr>
</table>	
		
<table class="grid">
<thead>
<tr>
  <th class="table-head" colspan="9">Summary Transport By Client</th>
</tr>
<tr>
	<th style="width:5%;">No</th>
	<th style="width:15%;">Periode</th>
    <th style="width:5%;">Year End</th>
	<th style="width:10%;">Kode</th>
    <th style="width:15%;" >Project</th>
    <th style="width:30%;" colspan="3">Alamat</th>
	<th style="width:10%;text-align: right;" class='currency'>Budget Cost</th>
</tr>
	</thead>
	<tbody>
   <?=$row ?>
  <tr><td colspan="9"><i>printed date <?=date("d M Y H:i:s");  ?></i>
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