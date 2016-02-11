<?php
$this->load->view('site_header');

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top">
	<form id="form" method="POST" action="<?=$site ?>/main/projectUpdate/" >
		<input type="hidden" id="project_id" name="project_id" value="<?=$form['project_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Weekly Timesheet Posting</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Week / Year: </td>
			<td><?=getWeek($form['week']) ?>
			<input type="text"  class="inputtext" id="project_no" name="project" value="<?=$form['year'] ?>" size="40" style='width:50px;'/></td>
		</td>
		<tr>
			<td class="label">Date From / To : </td>
			<td></td>
		</tr>

		<tr>
			<td class="label">Date : </td>
			<td><input type="text"  class="inputtext" id="address" name="descryption" value="<?=$form['date'] ?>" size="60" /></td>
		</tr>

		<tr>
			<td class="label">Job : </td>
			<td><input type="text"  class="inputtext" id="dstart" name="dstart" value="<?=$form['job_id'] ?>" size="40" /></td>
		</tr>

		<tr>
			<td class="label">Actual Hour: </td>
			<td><input type="text"  class="inputtext" id="dstart" name="hour" value="<?=$form['hour'] ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="label">Actual Cost: </td>
			<td><input type="text"  class="inputtext" id="dstart" name="cost" value="<?=$form['cost'] ?>" size="40" /></td>
		</tr>

		<tr>
			<td class="label">Notes: </td>
			<td><input type="text"  class="inputtext" id="dstart" name="notes" value="<?=$form['notes'] ?>" size="40" /></td>
		</tr>

		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="Save" class="ff3 UILinkButton_A"/>
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
		</fieldset>
	</form>	
	<td valign="top">
<!-- Project Info -->	
<!--
		<fieldset class="form-fieldset">
		<legend class="form-legend">MY Active Project</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Project Number : </td>
			<td><?=$form['project_no'] ?> </td>
		</td>
		<tr>
			<td class="label">Project Name : </td>
			<td><?=$form['project'] ?></td>
		</tr>
		<tr>
			<td class="label">Descryption: </td>
			<td><?=$form['descryption'] ?> </td>
		</tr>
		<tr>
			<td class="label">Periode: </td>
			<td><?=$form['dstart'] ?></td>
		</tr>

		<tr>
			<td class="label">Begin Date: </td>
			<td><?=$form['dstart'] ?></td>
		</tr>
		<tr>
			<td class="label">End Date: </td>
			<td><?=$form['dend'] ?></td>
		</tr>
		<tr>
			<td class="label">Budget Hour: </td>
			<td><?=$form['budget_hour'] ?></td>
		</tr>

		<tr>
			<td class="label">Actual Hour: </td>
			<td><?=$form['hour'] ?></td>
		</tr>

		<tr>
			<td class="label">Budget Cost: </td>
			<td><?=$form['budget_cost'] ?></td>
		</tr>

		<tr>
			<td class="label">Actual Cost: </td>
			<td><?=$form['cost'] ?></td>
		</tr>


		</table>	
		</fieldset>
-->
	</td>
</table>
<div id="tables" class="block">
<table class="grid">
	<thead>
<tr>
<th class="table-head" colspan="7">My Current Active Timesheet for Week:: <?=$form['week'] ?> - <?=$form['year'] ?></th>
</tr>
		<tr>
			<th>No</th>
			<th>Project Number</th>
			<th>Job </th>
			<th>Date</th>
			<th>Actual Hour</th>
			<th>Actual Cost</th>
			<th>Notes</th>
			<th>Edit</th>



		</tr>
	</thead>
	<tbody>
<?php
function getWeek($week) {
		
		$tmp ='<select name=week>';
		
		for ($i=1; $i<=52; $i++) {	
			$selected = '';
			if ( $i == $week ) {
				$selected = ' selected ';
			} 
			$tmp .= '<option value='.$i . $selected .'>'. $i .'</option>';
		}
		$tmp .= '</selected>';
		return $tmp;
}
	
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
				<td>$v[project_id]</td>
				<td>$v[job]</td>
				<td>$v[timesheetdate]</td>
				<td>$v[hour]</td>
				<td>$v[notes]</td>
				<td align='right'><a href='$site/main/timesheetEdit/$v[id]/$week/$year'>[ edit ]</a></td></tr>";
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5"></td>
				<th>Total</th>
				<th class="currency"><?=$hour ?></th>
			</tr>
		</tfoot>

	</table>
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