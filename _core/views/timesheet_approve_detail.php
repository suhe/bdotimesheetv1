<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">

<form id="form" method="POST" action="<?=$site?>timesheet/approveTimesheet/" >
<input type="hidden" name="id" value="<?=$id ?>">
<table class="grid">
	<tr>
	<thead>
		<th class="table-head" colspan="13">Approve Timesheet</th>
			<tr>
			<th>No</th>
<!--		<th>Date</th>	-->
<!--		<th>Request by</th>	-->
			<th>Project</th>
			<th>Project Number</th>
			<th>Client Name</th>
<!--		<th>Date</th>	-->
			<th>Job </th>
			<th class=currency>Actual Hour</th>
			<th class=currency>Overtime</th>

			<th class=currency>Actual Cost</th>
			<th class=currency>Attandance Hour</th>
			<th>Notes</th>
			<th>Return</th>
		</tr>
	</thead>
	<tbody>
<?php

$ghour = $gcost = $govertime = 0;
$hour = $cost = $ctr = $overtime = 0;

if ( count( $table) > 0 ) {
	$i = 1;
	$xdate = '';
	foreach ($table as $k=>$v) {
		$class = '';
		$hour += $v['hour'];
		$overtime += $v['overtime'];
		$cost	+= $v['cost'];

		$ghour += $v['hour'];
		$govertime += $v['overtime'];
		$gcost += $v['cost'];
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		$status = '';
		if ($v['timesheet_approval'] == '1' ){
			$status = 'Waiting Approval';
		}
		if ($v['timesheet_approval'] == '2' ){
			$status = 'Approved';
		}
		if ($v['timesheet_approval'] == '3' ){
			$status = 'Returned';
		}
		
		$timesheetdate ='';
		if ( strlen( $v['timesheetdate']) >0 ) {
			$timesheetdate = date("d M Y",strtotime($v['timesheetdate'])) ;
			if ($v['timesheetdate']=='1970-01-01' || $v['timesheetdate']=='0000-00-00' )  $timesheetdate = '';
		}

		$personalcalendardate='';
		if ( strlen( $v['personalcalendardate']) >0 ) {
			$personalcalendardate = date("d M Y",strtotime($v['personalcalendardate'])) ;
			if ($v['personalcalendardate']=='1970-01-01' || $v['personalcalendardate']=='0000-00-00' )  $personalcalendardate = '';
		}
		
		if ( $i == 1 ||  $xdate != $personalcalendardate ) {
			if ($i == 1){
				echo "
					<tr class=odd>
						<td colspan=13><b>Request by : ".$v['employeenickname']."
						 <i>(Period : ".$v['week']." - ".$v['year'].")</i></b>
						</td>
					</tr>";
			}
			if ($i > 1 ){
				$hour -= $v['hour'];
				$overtime -= $v['overtime'];
				$cost -= $v['cost'];
				echo "
					<tr>
						<td colspan=5 align=right class=currency><b>Total</b>
						<td align=right class=currency>$hour</td>
						<td align=right class=currency>".number_format($overtime)."</td>
						<td align=right class=currency>".number_format($cost)."</td>
						<td align=right class=currency>".$v['totalhour']."</td>
						<td colspan=2>&nbsp</td>
				 	</tr>";
			}
			$hour  = $v['hour'];
			$overtime = $v['overtime'];
			$cost  = $v['cost'];
			$xdate = $personalcalendardate;
			echo "
				<tr>
					<td colspan=3><b>".$personalcalendardate."</b></td>
			 	</tr>";
	 	}
		echo "
			<tr class=odd>
				<td>$i
<!--			<td>&nbsp;	-->
<!--			<td>$v[employeenickname]</td>	-->
				<td>$v[project]</td>
				<td>$v[project_no]</td>
				<td>$v[client_name]</td>
<!--			<td>".$timesheetdate."</td>	-->
				<td>$v[job]</td>
				<td align=right class=currency>$v[hour]</td>
				<td align=right class=currency>$v[overtime]</td>
				<td align=right class=currency>".number_format($v['cost'])."</td>
				<td>-</td>
				<td>$v[notes]</td>";

		if ($v['timesheet_approval']=='1') {
			echo "<td><input type=checkbox name=return[] value='$v[timesheetid]'></td>";
		}
		else {
			echo "<td><i>".$status."</i></td>";
		}
		echo "</tr>";
		$i++;
	}
	echo "
		<tr>
			<td colspan=5 align=right class=currency><b>Total</b>
			<td align=right class=currency>$hour</td>
			<td align=right class=currency>".number_format($overtime)."</td>
			<td align=right class=currency>".number_format($cost)."</td>
			<td align=right class=currency>".$v['totalhour']."</td>
			<td colspan=2>&nbsp</td>
	 	</tr>";

	echo "
		<tr>
			<td colspan=5 align=right class=currency><b>Grand Total</b>
			<td align=right class=currency><b>".number_format($ghour)."</b></td>
			<td align=right class=currency><b>".number_format($govertime)."</b></td>
			<td align=right class=currency><b>".number_format($gcost)."</b></td>
			<td colspan=3>&nbsp</td>
	 	</tr>";
}
?>
	</tbody>
	</tr>
</table>
<?php
echo '
	<table align=center>
		<tr>
			<td class=label valign=top style=vertical-align:top;>Notes: </td>
			<td><textarea style="width:475px;height:150px;" class="inputtext" id="notes" name="notes"></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="Submit" class="ff3 UILinkButton_A"/>
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
		</tr>
	</table>
</form>';
?>	
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