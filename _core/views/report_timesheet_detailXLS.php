<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=excel.xls" );
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");

/*
			header("Content-type: application/vnd.ms-excel");
			header("Content-type: application/x-msexcel");
			header("Content-type: application/x-msdownload");
			header("Content-disposition: attachment; filename=timesheet.xls");
*/			
?>
<html>
<body>
<table class="grid">
	<tr>
	<thead>
	<th class="table-head" colspan="13">Timesheet Detail</th>
		<tr>
			<th>No</th>
			<th>Date</th>
			<th>Week / Year</th>
			<th>Project</th>
			<th>Job</th>
			<th>Approval Status</th>
			<th class=currency>Actual Hour</th>
			<th class=currency>Actual Cost</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
<?php
/*
for($i=1; $i<=7; $i++) {
	$dates[$i] = strtotime($year.'W'.$week.$i);
	echo $dates[$i] .'<br>';
}
*/	
$hour = 0;
$cost = 0;
if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		$hour += $v['hour'];
		$cost+= $v['cost'];
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		$status = '';
		if ($v['timesheet_approval'] == '1' ){
			$status = 'Waiting Approval';
		}
		if ($v['timesheet_approval'] == '2' ){
			$status = 'Approved';
		}

		if ($v['timesheet_approval'] == '3' ){
			$status = 'Return';
		}
		
		$timesheetdate ='';
		if ( strlen( $v['timesheetdate']) >0 ) {
			$timesheetdate = date("d M Y",strtotime($v['timesheetdate'])) ;
			if ($v['timesheetdate']=='0000-00-00')  $timesheetdate = '';
		}
		
		echo "<tr $class>
				<td>$i</td>
				<td nowrap>".$timesheetdate."</td>
				<td>$v[week] / $v[year]</td>
				<td>$v[project_no] - $v[project]</td>
<!--
				<td>$v[client_name]</td>
-->
				<td>$v[job]</td>
				<td>$status</td>
				<td class=currency>".number_format($v['hour'])."</td>
				<td class=currency>".number_format($v['cost'])."</td>
				<td>$v[notes]</td>
			</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5"></td>
				<th align=right class=currency>Total</th>
				<th class=currency><?=number_format($hour)?></th>
				<th class=currency><?=number_format($cost)?></th>
				<td colspan="5"></td>
			</tr>
		</tfoot>
	</tr>
</table>
</div>
</body>
</html>