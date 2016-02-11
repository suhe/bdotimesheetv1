<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
	<tr>
	<th class="table-head" colspan="13">Submit Timesheet</th>
		<tr>
			<th>No</th>
			<th>Project</th>
			<th>Project Number</th>
			<th>Client Name</th>

			<th>Week</th>
			<th>Year</th>
			<th>Date</th>

			<th>Approval Status</th>
			<th>Job </th>
			<th class='currency'>Actual Hour</th>
			<th class='currency'>Actual Cost</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
<?php
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
		
		$timesheetdate ='';
		if ( strlen( $v['timesheetdate']) >0 ) {
			$timesheetdate = date("d M Y",strtotime($v['timesheetdate'])) ;
			if ($v['timesheetdate']=='0000-00-00')  $timesheetdate = '';
				
		}
		echo "<tr $class>
					<td>$i</td>
					<td>$v[project]</td>
					<td>$v[project_no]</td>
					<td>$v[client_name]</td>
					<td>$v[week]</td>
					<td>$v[year]</td>
					<td nowrap>".$timesheetdate."</td>
					<td>$status</td>
					<td>$v[job]</td>
					<td class='currency'>".number_format($v['hour'])."</td>
					<td class='currency'>".number_format($v['cost'])."</td>
					<td>$v[notes]</td>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8"></td>
				<th>Total</th>
				<th class='currency'><?=number_format($hour)?></th>
				<th class='currency'><?=number_format($cost)?></th>
				<td colspan="5"></td>
			</tr>
		</tfoot>
	</table>
<?php
echo '
<form id="form" method="POST" action="'.$site .'timesheet/requestApproval/" >
<input type="hidden" name="id" value="'. $id .'">
<table align=center>
<tr>
	<td></td>
	<td><div class="ff3 UILinkButton">
			<input type="submit"  id="submit"  value="Request Approval" class="ff3 UILinkButton_A"/>
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