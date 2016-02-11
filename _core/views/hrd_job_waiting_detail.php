<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="11">HRD Jobs To be Approve</th>
		<tr>
			<th>No</th>
			<th>Project</th>
			<th>Job </th>

			<th>Week</th>
			<th>Year</th>
			<th>Date</th>
			
			<th class=currency>Total Hour</th>
			<th class=currency>Actual Hour</th>
			<th class=currency>Overtime</th>
			<th class=currency>Actual Cost</th>
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
$cost = 0;
$ovrt = 0;
$total = 0;
if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		$hour += $v['hour'];
		$cost += $v['cost'];
		$ovrt += $v['overtime'];
		$total += $hour + $ovrt;
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
			$timesheetdate = date("d M Y",strtotime($v['timesheetdate'])) ;
			$hari= date("l",strtotime($v['timesheetdate'])) ;
			if ($v['timesheetdate']=='0000-00-00')  $timesheetdate = '';
				
		}
		
		echo "<tr $class >
				<td>$i</td>
				<td>$v[project]</td>
				<td>$v[job]</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td nowrap>".$timesheetdate."</td>
				<td class=currency>".number_format($v['hour'] + $v['overtime'])."</td>
				<td class=currency>".number_format($v['hour'])."</td>
				<td class=currency>".number_format($v['overtime'])."</td>
				<td class=currency>".number_format($v['cost'])."</td>
				<td>$v[notes]</td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5"></td>
				<th class=currency align=right>Total</th>
				<th class=currency><?=number_format($hour + $ovrt)?></th>
				<th class=currency><?=number_format($hour)?></th>
				<th class=currency><?=number_format($ovrt)?></th>
				<th class=currency><?=number_format($cost)?></th>
				<td></td>
			</tr>
		</tfoot>

	</table>
<table align="center" width="'100%'">
<tr>
<td colspan=2 align="center" style="text-align:center;">
	<div class="ff3 UILinkButton" style="padding-left:10px;">
		<input type="button"  id="approve"  value="Approve" class="ff3 UILinkButton_A"/>
		<div class="UILinkButton_RW">
			<div class="UILinkButton_R"/></div>
		</div>
	</div>

	<div class="ff3 UILinkButton" style="padding-left:10px;">
		<input type="button"  id="return"  value="Return" class="ff3 UILinkButton_A"/>
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
	
	$('#approve').click( function (e) {
		window.location.href ='<?=$approve?>';
	});	
	
	$('#return').click( function (e) {
		window.location.href ='<?=$return?>';
	});	
});
</script>
<?php
$this->load->view('site_footer');

?>
