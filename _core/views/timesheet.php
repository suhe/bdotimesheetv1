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
<th class="table-head" colspan="9">My Active Timesheet </th>
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
if ( $active ) {
	$i = 1;
	foreach ($active as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['timesheet_approval'] == null) {
			$flag ='Active';
		}
		if ($v['timesheet_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		if ($v['timesheet_approval'] =='2') {
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
				<td align='right'><a href='".$site."timesheet/Active/$v[timesheet_status_id]'>[ View ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
if ( $return ) {
	$i = 1;
	foreach ($return as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['timesheet_approval'] =='3') {
			$flag ='Return';
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
				<td align='right'><a href='".$site."timesheet/Active/$v[timesheet_status_id]'>[ View ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>
<br>

<br>
			<div class="box">
				<h2><a id="toggle-tables" href="#">My Project </a></h2>
				<div id="tables" class="block">
					<div id="paging">
						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=$site?>/timesheet/index/3/1" />First</a>
						<a href="<?=$site?>/timesheet/index/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/timesheet/index/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/timesheet/index/3/<?=$pg['l']?>" />Last</a>
<!--
						<span style="float:right;text-align:right;"><a href="<?=$site?>/timesheet/timesheetProjectDel/0" > [ DELETE PROJECT] </a></span> 
-->
<?php
if ( $this->session->userdata('acl') =='041' || $this->session->userdata('acl') =='042'){
 ?>
    <span style='float:right;text-align:right;'><a href="<?=$site?>timesheet/timesheetProjectAdd/0" > [ ADD PROJECT ] &nbsp;&nbsp;</a></span>
<?php
}
?>
					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>Project Name</th>
								<th>Project Number</th>
								<th>Client</th>
								<th>Year End</th>
								<th class='currency'>Budget Hour</th>
								<th class='currency'>Actual Hour</th>
								<th class='currency'>Budget Cost</th>
								<th class='currency'>Actual Cost</th>
								<th>Posting Timesheet</th>
							</tr>
						</thead>
						<tbody>
<?php
if ( $table ) {
	$i = 1;
	foreach ($table as $k=>$v) {
		$class= '';
		$year = substr($v['year_end'],0,4);
		//if($year>=2011){
			if ( $i % 2 == 0) $class= 'class="odd"';
			
			echo "<tr $class >
					<td>";
			echo $i + $pg['o'];
			echo "</td>
					<td>$v[project]</td>
					<td>$v[project_no]</td>
					<td>$v[client_name]</td>
					<td>$v[year_end]</td>
					<td class='currency'>$v[budget_hour]</td>
					<td class='currency'>$v[hour]</td>
					<td class='currency'>".number_format($v['budget_cost'],2)."</td>
					<td class='currency'>".number_format($v['cost'],2)."</td>
					<td align='right'><a href='".$site."timesheet/ProjectEdit/0/$v[project_id]/0/2009/'>[ Posting Timesheet ]</a></td></tr>";
			$i++;
	    //}	
	}
}
?>
						</tbody>
					</table>
				<div id="paging">
					<a href="<?=$site?>timesheet/index/3/1" />First</a>
					<a href="<?=$site?>timesheet/index/3/<?=$pg['p']?>" />Prev</a>
					<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
					<a href="<?=$site?>timesheet/index/3/<?=$pg['n']?>" />Next</a>
					<a href="<?=$site?>timesheet/index/3/<?=$pg['l']?>" />Last</a>
				</div>
				</div>
			</div>
		</div>
<?php
	$this->load->view('site_footer');
?>