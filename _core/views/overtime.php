<?php
	$this->load->view('site_header');
?>
	<div class="grid_12">
		<h2 id="page-heading">Administration - OverTime</h2>
	</div>
	<div class="grid_12">
<?php 
	
	if ( $this->session->userdata('department_id') == "13" ) {
?>				
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Overtime to be approve</th>

		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Approval Status</th>
			<th>Approval Date</th>
			<th>Approve By</th>
			<th>Request Date</th>
			<th>Request By</th>
			<th>View Detail</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $waiting ) {
	$i = 1;
	foreach ($waiting as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['overtime_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		if ($v['overtime_approval'] =='2') {
			$flag ='Approved';
		}
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
				<td>$i</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>".$flag."</td>
				<td>$v[dapproval]</td>
				<td>$v[requestor]</td>

				<td>$v[drequest]</td>
				<td>$v[approval]</td>

				<td align='right'><a href='$site/administration/overtimeApprove/$v[overtime_status_id]'>[ View ]</a></td></tr>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>
<br>
<?php } ?>
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Waiting for Overtime Approval.</th>
</tr>
		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Approval Status</th>
			<th>Approval Date</th>
			<th>Approve By</th>
			<th>Request Date</th>
			<th>Request By</th>
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
		if ($v['overtime_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		if ($v['overtime_approval'] =='2') {
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
				<td align='right'><a href='$site/administration/overtimeWaiting/$v[overtime_status_id]'>[ View ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>
<br>

<br>
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Approved Overtime </th>
</tr>
		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Approval Status</th>
			<th>Approval Date</th>
			<th>Approve By</th>
			<th>Request Date</th>
			<th>Request By</th>
			<th>View Detail</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $done ) {
	$i = 1;
	foreach ($done as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['overtime_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		if ($v['overtime_approval'] =='2') {
			$flag ='Approved';
		}
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
				<td>$i</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>$flag</td>
				<td>$v[dapproval]</td>
				<td>$v[approval]</td>
				<td>$v[drequest]</td>
				<td>$v[requestor]</td>
				<td align='right'><a href='$site/administration/overtimeApproved/0/$v[overtime_status_id]/0/0'>[ View ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>
<br>
			<div class="box">
				<h2><b>My Overtime </B></h2>
<!--				<div id="tables" class="block">

					<div id="paging">
						<span style="float:right;text-align:right;"><a href="<?=$site?>/administration/overtimeEdit/0/0/0/0" > 
						[ ADD NEW] </a></span>
					</div>
-->
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>Week</th>
								<th>Year</th>
								<th>Status</th>
								<th class='currency'>Total Hour</th>
								<th class='currency'>Office Hour</th>
								<th class='currency'>Overtime</th>
								<th class='currency'>Posting overtime</th>
							</tr>
						</thead>
						<tbody>
<?php
if ( $table ) {
	$i = 1;
	foreach ($table as $k=>$v) {
		$link = "<a href='$site/administration/overtimeEdit/0/$v[overtime_status_id]/0/0'>[ Posting Overtime ]</a>";
		
		$flag='';
		if ($v['overtime_approval'] =='1') {
			$flag ='Waiting for Approval';
			$link = "<a href='$site/administration/overtimeView/0/$v[overtime_status_id]/0/0'>[ View  ]</a>";
		
		}
		if ($v['overtime_approval'] =='2') {
			$flag ='Approved';
			$link = "<a href='$site/administration/overtimeView/0/$v[overtime_status_id]/0/0'>[ View  ]</a>";
		}

		if ($v['overtime_approval'] == '3' ){
			$flag = 'Returned';
			$link = "<a href='$site/administration/overtimeView/0/$v[overtime_status_id]/0/0'>[ View  ]</a>";
		}		
		$class= '';
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
				<td>";
		echo $i;
		echo "</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>$flag</td>
				<td class='currency'>".number_format($v['hour'],2)."</td>
				<td class='currency'>".number_format($v['office'],2)."</td>
				<td class='currency'>".number_format($v['overtime'],2)."</td>
				<td align='center' class='currency'>$link</td></tr>";
		$i++;
	}
}
?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?php
	$this->load->view('site_footer');
?>