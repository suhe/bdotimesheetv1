<?php
	$this->load->view('site_header');
?>
	<div class="grid_12">
		<h2 id="page-heading">Timesheet</h2>
	</div>
	<div class="grid_12">

<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Waiting for Timesheeet Approval.</th>
</tr
		><tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Approval Status</th>
			<th>Approval Date</th>
			<th>Request By</th>
			<th>Request Date</th>
			<th>Approve By</th>
			<th>View Detail</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $request ) {
	$i = 1;
	foreach ($request as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['timesheet_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		if ($v['timesheet_approval'] =='2') {
			$flag ='Approved';
		}
		if ( $i % 2 == 0) $class= 'class="odd"';
		echo "<tr $class>
				<td>$i</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>$flag</td>
				<td>$v[dapproval]</td>
				<td>$v[requestor]</td>
				<td>$v[drequest]</td>
				<td>$v[approval]</td>
				<td align='right'><a href='$site/timesheet/Waiting/$v[timesheet_status_id]'>[ View ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>

<?php
	$this->load->view('site_footer');
?>