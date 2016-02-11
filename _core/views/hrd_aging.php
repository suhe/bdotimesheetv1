<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="11">Aging Timesheet</th>

		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Status</th>
			<th align=center>Aging<br>( Days )</th>
			<th>Day</th>
			<th>Request Date</th>
			<th>Request By</th>
			<th>Request TO</th>
			<th>View Detail</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $table ) {
function GetDeltaTime($dtTime1, $dtTime2)
{
  $nUXDate1 = $dtTime1;//strtotime($dtTime1->format("Y-m-d H:i:s"));
  $nUXDate2 = strtotime(str_replace(' ','',$dtTime2).' 00:00:00'); //strtotime($dtTime2->format("Y-m-d H:i:s"));
  //echo $nUXDate2;
  $nUXDelta = $nUXDate1 - $nUXDate2;
  $strDeltaTime = "" . ceil($nUXDelta/60/60/24); // sec -> hour --> day = rounding up
           
  $nPos = strpos($strDeltaTime, ".");
  if ($nPos !== false)
    $strDeltaTime = substr($strDeltaTime, 0, $nPos + 3);

  return $strDeltaTime;
}

	$i = 1;
	foreach ($table as $k=>$v) {
		$class= '';
		$flag ='';
		
		if ($v['timesheet_approval'] =='1') {
			$flag ='Waiting for Approval';
		}
		
		$datediff=0;
		$x = time();
		$datediff = GetDeltaTime($x, $v['drequest']);
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
				<td>$i</td>
				<td>$v[week]</td>
				<td>$v[year]</td>
				<td>".$flag."</td>
				<td align=center>".$datediff ."</td>
				<td>$v[hari]</td>
				<td>$v[drequest]</td>
				<td>$v[requestor]</td>
				<td>$v[approval]</td>
				<td align='right'><a href='$site/administration/hrdjobinfo/$v[timesheet_status_id]'>[ View ]</a></td></tr>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
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