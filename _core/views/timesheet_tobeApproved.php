<?php
	$this->load->view('site_header');
?>
	<div class="grid_12">
		<h2 id="page-heading">Timesheet</h2>
	</div>
	<div class="grid_12">
<?php 
	// @ilham 18april2011
	//if ( $this->session->userdata('acl') == "2" || $this->session->userdata('acl') == "3" || $this->session->userdata('acl') == "4" ) {
	if ( $this->session->userdata('acl') == "1" || $this->session->userdata('acl') == "2" || $this->session->userdata('acl') == "3" || $this->session->userdata('acl') == "4" ) {
?>				
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Timesheet to be approve</th>

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
				<td>".$flag."</td>
				<td>$v[dapproval]</td>
				<td>$v[approval]</td>
				<td>$v[drequest]</td>
				<td>$v[requestor]</td>

				<td align='right'><a href='".$site."timesheet/Approve/$v[timesheet_status_id]/$v[week]/$v[year]'>[ View ]</a></td></tr>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
	</table>
<br>
<?php } ?>
<?php
	$this->load->view('site_footer');
?>