<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Overtime to be approve</th>

		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Status</th>
			<th>Overtime</th>
			<th>Request Date</th>
			<th>Request By</th>
			<th>View Detail</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $table ) {
	$i = 1;
	$hour=0;
	$office=0;
	$overtime=0;
	foreach ($table as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['overtime_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
				<td>$i</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>".$flag."</td>
				<td align=center>$v[overtime]</td>

				<td>$v[drequest]</td>
				<td>$v[approval]</td>

				<td align='right'><a href='$site/administration/hrdovertimeApprove/$v[overtime_status_id]'>[ View ]</a></td></tr>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
		</tfoot>

	</table>
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