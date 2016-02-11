<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="13">Waiting for approval</th>
		<tr>
			<th>No</th>
			<th>Date</th>
			<th>Week</th>
			<th>Year</th>
			<th class=currency>Total Hour</th>
			<th class=currency>Office Hour</th>
			<th class=currency>Overtime</th>
			<th>Notes</th>

		</tr>
	</thead>
	<tbody>
<?php
$office_hour = 8;
$hour 		= 0;
$office 	= 0;
$overtime  	= 0;

if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		$hour += $v['hour'];
		$office += $office_hour;
		
		$overtime+= $v['hour'] - $office_hour;
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		$status = '';
		if ($v['overtime_approval'] == '1' ){
			$status = 'Waiting Approval';
		}
		if ($v['overtime_approval'] == '2' ){
			$status = 'Approved';
		}
		
		$timesheetdate ='';
		if ( strlen( $v['overtimedate']) >0 ) {
			$timesheetdate = date("d M Y",strtotime($v['overtimedate'])) ;
			if ($v['overtimedate']=='0000-00-00')  $timesheetdate = '';
				
		}
		
		echo "<tr $class >
				<td>$i</td>
				<td nowrap>".$timesheetdate."</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td class=currency>".number_format($v['hour'])."</td>
				<td class=currency>".number_format($office_hour)."</td>
				<td class=currency>".number_format($v['hour'] - $office_hour)."</td>
				<td>$v[notes]</td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3"></td>
				<th >Total</th>
				<th class=currency><?=number_format($hour)?></th>
				<th class=currency><?=number_format($office)?></th>
				<th class=currency><?=number_format($overtime)?></th>
				<td colspan="4"></td>
			</tr>
		</tfoot>

	</table>

	
<?php
echo '
<form id="form" method="POST" action="'.$site .'/main/approveTimesheet/" >
<input type="hidden" name="id" value="'. $id .'">
<table align=center>
<tr>
			<td>
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