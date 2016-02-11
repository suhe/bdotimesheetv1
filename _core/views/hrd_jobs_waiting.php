<?php
$this->load->view('site_header');
?>
<div class="grid_12">
<h2 id="page-spacer"></h2>
<div class="box">
	<h2>Approved HRD Job</h2>
		<div id="tables" class="block">
			<div id="paging">
					<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
					<a href="<?=$site?>/administration/hrdJob/3/1" />First</a>
					<a href="<?=$site?>/administration/hrdJob/3/<?=$pg['p']?>" />Prev</a>
					<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
					<a href="<?=$site?>/administration/hrdJob/3/<?=$pg['n']?>" />Next</a>
					<a href="<?=$site?>/administration/hrdJob/3/<?=$pg['l']?>" />Last</a>
			</div>
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="8">HRD Jobs to be approve</th>

		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Status</th>
			<th>Day</th>
			<th>Request Date</th>
			<th>Request By</th>
			<th>View Detail</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $table ) {
	$i = 1;
	foreach ($table as $k=>$v) {
		$class= '';
		$flag ='';
		if ($v['timesheet_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
			  <td>";
		echo $i + $pg['o'];
		echo "</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>".$flag."</td>
				<td>$v[hari]</td>
				<td>$v[drequest]</td>
				<td>$v[approval]</td>

				<td align='right'><a href='".$site."administration/hrdjobApprove/".$v['timesheet_status_id']."'>[ View ]</a></td></tr>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
		</tfoot>

	</table>
	
	<div id="paging">
			<a href="<?=$site?>/administration/hrdJob/3/1" />First</a>
			<a href="<?=$site?>/administration/hrdJob/3/<?=$pg['p']?>" />Prev</a>
			<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
			<a href="<?=$site?>/administration/hrdJob/3/<?=$pg['n']?>" />Next</a>
			<a href="<?=$site?>/administration/hrdJob/3/<?=$pg['l']?>" />Last</a>
		</div>
		</div>
	</div>
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