<?php
$this->load->view('site_header');

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
		<fieldset class="form-fieldset">
		<legend class="form-legend">Human Resources Administration</legend>
	<table align="center">
	<tr><td>
				<table align=center>
			<!--	<tr><td><a href="<?=$site ?>/administration/hrdOvertime/">- Overtime Request Approval</td></tr>
				<tr><td><a href="<?=$site ?>/administration/hrdOvertimeApproved/">- Overtime Approved</td></tr>
				<tr><td>&nbsp;</tr>
			-->	
				<tr><td><a href="<?=$site ?>administration/hrdJob/">- HRD Jobs  Request Approval</td></tr>
				<tr><td><a href="<?=$site ?>administration/hrdJobApproved/">- HRD Jobs  Approved</td></tr>
				</table>	

			<td>

				<table align=center>
				<tr><td><a href="<?=$site ?>administration/aging/">- Aging  Timesheet</td></tr>	
				</table>

	</table>				
		</fieldset>
</div>


<?php
	$this->load->view('site_footer');
	
?>