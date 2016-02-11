<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
	<td class="label" colspan="14"><?=$employee_name ?></td>
</tr>
<tr>
<th class="table-head" colspan="14">Approved HRD Jobs - Detail </th>
</tr>
		<tr>
			<th>No</th>
			<th>Project</th>
			<th>Type</th>
			<th>Job </th>
			<th>Week</th>
			<th>Year</th>
			<th>Day</th>
			<th>Date</th>
			<th class=currency>Actual Hour</th>
			<th class=currency>Work Hour</th>
			<th class=currency>Overtime</th>
			<th class=currency>Actual Cost</th>
			<th class=currency>Leave (Day)*</th>
			<th>Notes</th>

		</tr>
	</thead>
	<tbody>
<?php


//echo date('W'); 
//echo $year . $week;
/*
for($i=1; $i<=7; $i++) {
	$dates[$i] = strtotime($year.'W'.$week.$i);
	echo $dates[$i] .'<br>';
}
*/	
$hour = 0;
$whour = 0;
$cost = 0;
$ovrt = 0;
$totalLeave = 0;
if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		$hour += $v['hour'];
		$whour += $v['work_hour'];
		$cost += $v['cost'];
		$ovrt += $v['overtime'];
		if ( $i % 2 == 0) $class= 'class="odd"';
		$status = '';
		if ($v['timesheet_approval'] == '1' ){
			$status = 'Waiting Approval';
		}
		if ($v['timesheet_approval'] == '2' ){
			$status = 'Approved';
		}
		
		$timesheetdate ='';
		if ( strlen( $v['timesheetdate']) >0 ) {
			$timesheetdate = date("d - m - Y",strtotime($v['timesheetdate'])) ;
			$hari= date("l",strtotime($v['timesheetdate'])) ;
			if ($v['timesheetdate']=='0000-00-00')  $timesheetdate = '';
				
		}
		
		echo "<tr $class >
				<td>$i</td>
				<td>$v[project]</td>
				<td>$v[type]</td>
				<td>$v[job]</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>$hari</td>
				<td nowrap>".$timesheetdate."</td>
				<td class=currency>".number_format($v['hour'])."</td>
				<td class=currency>".number_format($v['work_hour'])."</td>
				<td class=currency>".number_format($v['overtime'])."</td>
				<td class=currency>".number_format($v['cost'])."</td>
				<td class=currency>".$leave=LeaveTimesheetDay($v['job_id'],$v['hour'])."</td>
				<td>$v[notes]</td></tr>";
		echo "</tr>";
		$i++;
		$totalLeave+=$leave;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7"></td>
				<th class=currency align=right>Total</th>
				<th class=currency><?=number_format($hour)?></th>
				<th class=currency><?=number_format($whour)?></th>
				<th class=currency><?=number_format($ovrt)?></th>
				<th class=currency><?=number_format($cost)?></th>
				<th class=currency><?=number_format($totalLeave)?></th>
				<td></td>
			</tr>
		</tfoot>

	</table>
 * Leave : 1 (One) Day (>= 4 Hours) 
<table align="center" width="'100%'">
<tr>
<td colspan=2 align="center" style="text-align:center;">

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
$(document).ready(function(){
 	$('#back').focus();
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
