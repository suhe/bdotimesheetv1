<?php
$this->load->view('site_header');
$employee_data = $this->dataModel->getEmployeeDepartment();
$employee = '';
if ( count( $employee_data ) > 0 ) {
	$employee .='<select name=department_id id=department_id> ';
	$employee .= '<option value="">All Group</option>';
	foreach ($employee_data as $k => $v) {
		$selected = '';
		/*if ( $v['department_id'] == $form['department_id'] ) {
			$selected = ' selected ';
		} */
		$employee .= '<option value='.$v['department_id'] . $selected .'>'. $v['department'] .'</option>';
	}
	$employee .= '</selected>';

}	
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportTimesheetGroup/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Timelines of Timesheet Completion Group</legend>
		<table align=center>
		<tr>
			<td class="label">Group : </td>
			<td><?=$employee ?> </td>
		</td>	
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
<a id=export style="cursor:pointer;"><b>Export to EXCEL<b></a>
<div id=inner></div>
<div id=excel>
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Timelines of Timesheet Group Per Periode</legend>
<table align=center>
<tr>
	<td class="label">Periode : </td>
	<td><?=$form['date_from'] ?> to <?=$form['date_to'] ?></td>
</tr>
<tr>
	<td class="label">Black :</td>
	<td>Workday</td>
</tr>
<tr>
	<td class="label">Blue :</td>
	<td>Holiday</td>
</tr>
</table>	
		
<table class="grid">
	<?=$table?>
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