<?php
$this->load->view('site_header');
$employee_data = $this->reportModel->getUserEmployee();
$employee = '';
$employee_name = '';
if ( count( $employee_data ) > 0 ) {
	$employee .='<select name=employee_id>';
	foreach ($employee_data as $k=>$v) {
		$selected = '';
		if ( $v['employee_id'] == $form['employee_id'] ) {
			$selected       = ' selected ';
			$employee_name  = $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'];
		} 
		$employee .= '<option value='.$v['employee_id'] . $selected .'>'. $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'].'</option>';
	}
	$employee .= '</selected>';
}	
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportTransportEmployeeStatus/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Status Transport By Employee Filter</legend>
		<table align=center>
		<tr><td>&nbsp;</tr>
		
		<tr>
			<td class="label">Employee: </td>
			<td><?=$employee ?></td>
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
<legend class="form-legend">Status Transport By Employee</legend>
</fieldset>
<table align=center>
<tr><td>&nbsp;</tr>
<tr>
	<td class="label">Employee: </td>
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
  <th class="table-head" colspan="9">Summary Transport</th>
</tr>
<tr>
	<th rowspan=2>No</th>
	<th rowspan=2>Day</th>
	<th rowspan=2>Date</th>
	<th rowspan=2>Project</th>
	<th rowspan=2>Client</th>
	<th colspan=3 style='text-align:center;'>Transport</th>
	<th rowspan=2 class='currency'>Cost</th>

</tr>

<tr>
	<th class='currency'>Office</th>
	<th class='currency'>In Town Client</th>
	<th class='currency'>Out of Town Client</th>
</tr>

	</thead>
   <?=$row?>
   
<tr>
			<td></td>
			<!--<td><div class="ff3 UILinkButton">
					<input type="submit"  id="approved"  value="Print PDF" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				</td>
			<td colspan=7></td>-->
			   
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