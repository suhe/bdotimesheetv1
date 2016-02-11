<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportTimesheet/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Report Status Timesheet</legend>

<?php $this->load->view('report_timesheet_view');		?>
<!--

		<table align=center>
		<tr><td>&nbsp;</tr>
		
		<tr>
			<td class="label">Group: </td>
			<td><?=$employee ?> </td>
		</tr>

		<tr>
			<td class="label">Periode : </td>
			<td><input type="text"  class="inputtext" name="date_from" value="<?=$form['date_from'] ?>" size="60" style="width:75px;"/> / 
				<input type="text"  class="inputtext" name="date_to" value="<?=$form['date_to'] ?>" size="60" style="width:75px;"/>
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
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="14">Report Attandance by Group</th>
</tr
		<tr>
			<th>No</th>
			<th>NIK</th>
			<th>NAME</th>
			<th>Position</th>
			<th>Duty On</th>
			<th>Duty Off</th>
			<th>Late In</th>
			<th>Early Departure</th>
			<th>Overtime</th>
			<th>Total Hour</th>
			<th>Break Hour</th>
			<th>Actual Hour</th>
			<th>Budget Hour</th>
			<th>Balance</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td>$v[employeeid]</td>
				<td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
				<td>$v[employeetitle]</td>
				<td>$v[timecome]</td>
				<td>$v[timehome]</td>
				<td>$v[latein]</td>
				<td>$v[earlyout]</td>
				<td>$v[totalot]</td>
				<td>$v[totalhour]</td>
				<td>-</td>
				<td>$v[actual]</td>
				<td>$v[budget]</td>
				<td>$v[balance]</td>
				
				</tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
-->
	<tr>
			<td>
				<div class="ff3 UILinkButton" style="padding-left:10px;">
					<input type="button"  id="back"  value="Back" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				
				</td>
	</table>

</div>
<script>
// When the page is ready
$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

});

</script>
<?php
$this->load->view('site_footer');

?>