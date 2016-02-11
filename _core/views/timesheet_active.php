<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Timesheet</th>
</tr>
</tr>
		<tr>
			<th>No</th>
			<th>Week</th>
			<th>Year</th>
			<th>Date</th>

			<th>Approval Status</th>
			<th>Job </th>
			<th>Actual Hour</th>
			<th>Actual Cost</th>
			<th>Notes</th>
			<th>Edit</th>



		</tr>

		</tr>
	</thead>
	<tbody>
<?php
$hour = 0;

if ( count( $table) > 0 ) {
	$i = 1;
	
	foreach ($table as $k=>$v) {
		$class= '';
		$hour += $v['hour'];
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td>$v[project_no]</td>
				<td>$v[client_name]</td>
				<td>$v[job]</td>
				<td>$v[timesheetdate]</td>
				<td>$v[hour]</td>
				<td>$v[notes]</td>";
		if ($flag_approval=='1') {				
			echo "<td><input type=checkbox name=return[] > &nbsp;Return</td>";
		}
		echo "</tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"></td>
				<th>Total</th>
				<th><?=$hour ?></th>
				<td colspan="2"></td>
			</tr>
		</tfoot>

	</table>



<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="9">Timesheet :: Week - <?=$week ?>  / <?=$year ?></th>
</tr>
		<tr>
			<th>No</th>
			<th>Project</th>
			<th>Client</th>
			<th>Job </th>
			<th>Date</th>
			<th>Actual Hour</th>
			<th>Notes</th>

		</tr>
	</thead>
	<tbody>
<?php
$hour = 0;

if ( count( $table) > 0 ) {
	$i = 1;

	foreach ($table as $k=>$v) {
		$class= '';
		$hour += $v['hour'];
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td>$v[project_no]</td>
				<td>$v[client_name]</td>
				<td>$v[job]</td>
				<td>$v[timesheetdate]</td>
				<td>$v[hour]</td>
				<td>$v[notes]</td>";
if ($flag_approval=='1') {				
	echo "<td><input type=checkbox name=return[] > &nbsp;Return</td>";
}
	echo "</tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"></td>
				<th>Total</th>
				<th><?=$hour ?></th>
				<td colspan="2"></td>
			</tr>
		</tfoot>

	</table>
	
<?php

if ($flag=='0'&& $flag_approval!='1') {
echo '
<form id="form" method="POST" action="'.$site .'main/requestApproval/" >
<input type="hidden" name="week" value="'. $week .'">
<input type="hidden" name="year" value="'. $year .'">
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

}
if ($flag_approval=='1') {
	echo '
			<form id="form" method="POST" action="'.$site .'main/requestApproval/" >
			<input type="hidden" name="week" value="'. $week .'">
			<input type="hidden" name="year" value="'. $year .'">
			<table align=center>
			<tr><td valign=top style="vertical-align:top;"><b>Notes</b><td valign=top><textarea name=notes rows=10 cols=60></textarea>
			
			
			<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
			<input type="submit"  id="submit"  value="Approve Timesheet" class="ff3 UILinkButton_A"/>
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
	
}

?>	

</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#project_no').focus();
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