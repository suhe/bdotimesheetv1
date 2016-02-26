<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
		<fieldset class="form-fieldset">
		<legend class="form-legend">Report Repositories</legend>
	<table align="center">
	<tr><td>
				<table align=center>
<?php if(($this->session->userdata('acl') == '01' && $this->session->userdata('department_id') == 7 ) || ($this->session->userdata('acl') == '03' && $this->session->userdata('department_id') == 7 )) { ?>
	<tr><td><a href="<?=base_url()?>report/reportOutsource/">- Outsource Detail Report</td></tr>
	<tr><td><a href="<?=base_url()?>report/reportOutsourceWeek/">- Outsource Report by Week</td></tr>
	
<?php } ?>				
<?php   
if ($this->session->userdata('acl') === '01' || $this->session->userdata('acl') === '008' || $this->session->userdata('acl') === '009' || $this->session->userdata('acl') === '011' ||  $this->session->userdata('acl') === '02')
{
?>	
				<tr><td><a href="<?=base_url()?>report/reportEmployee/">- Employee Detail Report</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportEmployeeSummary/">- Summary Report by  Employee </td></tr>
				<tr><td><a href="<?=base_url()?>report/reportEmployeeOvertime/">- Summary of Overtime Hours(<i>Updated</i>)</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTransport/">- Summary Transport by Period</td></tr>
                <tr><td><a href="<?=base_url()?>report/reportTransportClient/">- Summary Transport by Client</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTransportEmployee/">- Detail Transport by Employee</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportEmployeeAbsent/">- Absent by Period</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportEmployeeTotal/">- Absent by Week</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportAbsentByEmployee/">- Absent by Employee</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportSummaryproject/">- Summary Report Project</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTimesheetGroup/">- Timesheet Completion Group</td></tr>	
				<tr><td><a href="<?=base_url()?>report/reportProjectGroup/">- Project Completion Per Group</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportAllowance/">- Allowances Outside City</td></tr>	 
				<tr><td></tr>
				</table>	
			<td>
				<table align=center>
				<tr><td><a href="<?=base_url()?>report/reportProject/">- Report by Project</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportJob/">- Summary of Project Report</td></tr>
                <tr><td><a href="<?=base_url()?>report/reportClosed/">- Summary of Closed Report</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportPartner/">- Summary Job by Partner</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTimesheetEmployee/">- Summary Timesheet  by Employee</td></tr>
                <tr><td><a href="<?=base_url()?>report/reportTimesheetCompletionSummary/">- Timesheet Completion Summary Report</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTimesheetCompletion/">- Timesheet Completion Report Approved</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTimesheetCompletionW/">- Timesheet Completion Report Waiting</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportEmployeeWeek/">- Employee Report by Week</td></tr>
                <tr><td><a href="<?=base_url()?>report/reportTimesheetBudget/">- Budget &amp; Actual Project  <i> (New)</i></i></td></tr>
                <tr><td><a href="<?=base_url()?>report/reportActualEmployee/">- Employee Actual Project  <i> (New)</i></i></td></tr>
<!--
				<tr><td><a href="<?=$site ?>/report/reportBudget/">- Project Budget Info Cost</td></tr>
				<tr><td><a href="<?=$site ?>/report/reportBudgetActual/">- Project Budget Info Realisasi</td></tr>
-->
				
<?php
}
if ($this->session->userdata('acl') === '05')
{

?>
 
				<tr><td><a href="<?=base_url()?>report/reportTransport/">- Summary Transport by Period</td></tr>
                <tr><td><a href="<?=base_url()?>report/reportTransportClient/">- Summary Transport by Client</td></tr>
				<tr><td><a href="<?=base_url()?>report/reportTransportEmployeeStatus/">- Status Transport by Employee</td></tr>
				<!--<tr><td><a href="#">- Summary Transport by Client!!(Underconstruction) </td></tr>-->
<?php
}

?>

				</table>	
	</table>				
		</fieldset>
</div>


<?php
	$this->load->view('site_footer');
	
?>