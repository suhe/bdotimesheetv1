<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/timesheet/timesheetProjectAdd/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Add New Project to My Timesheet</legend>
		<table align=center>
		<tr><td>&nbsp;</tr>
		
		<tr>
			<td class="label">Client : </td>
			<td>
<?php
if ( count( $client) > 0 ) {
	$i = 1;

	$cClient ='<select name=client_id>';
  $cClient  .= '<option value=0>Please Choose</option>';
	foreach ($client as $k=>$v) {
		$selected = '';
		if ( $v['client_id'] == $form['client_id'] ) {
			$selected = ' selected ';
		} 
		
	  $cClient  .= '<option value='.$v['client_id'] . $selected .'>'. $v['client_name'].'</option>';
	}
	$cClient  .= '</selected>';
	
  echo $cClient ;
}
?>
			  
			</td>
		</tr>

		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="View Project" class="ff3 UILinkButton_A"/>
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
</table>
<div id="tables" class="block">
	<form id="form" method="POST" action="<?=$site ?>/timesheet/timesheetProjectAddUpdate/" >
		<input type="hidden" id="clientid" name="clientid" value="<?=$form['client_id'] ?>"  />	  
	  

<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="14">Approved Project List</th>
</tr>
		<tr>
			<th>No</th>
			<th>Select</th>
			<th>Project Code</th>
			<th>Project Name</th>
			<th>Project Status</th>
			<th>Year End</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( count( $project) > 0 ) {
	$i = 1;

	foreach ($project as $k=>$v) {
    $class = '';
		
		if ($i % 2 == 0) $class= 'class="odd"';
		
		$status = '';
		if ($v['project_approval']==1){
			$status 	= 'Waiting for Review';	
		}
		elseif ($v['project_approval']==2){
			$status 	= 'Reviewed';	
		}
		elseif ($v['project_approval']==3){
			$status 	= 'Approved';	
		}

		$year_end = '';
		if ( strlen( $v['year_end']) >0 ) {
			$year_end = date("d M Y",strtotime($v['year_end'])) ;
			if ($v['year_end']=='0000-00-00' || $v['year_end']=='1970-01-01')  $year_end = '';
		}
		
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td align=center style='width:15px;text-align:center;'><input type='checkbox'  name='project_id[]' value=$v[project_id]  style='width:10px;'/> </td>
				<td>$v[project_no] </td>
				<td>$v[project]</td>
				<td>$status</td>
				<td nowrap>". $year_end ."</td>
				</tr>";
		echo "</tr>";
		$i++;
	}
}
?>

		<tr>
			<td></td>
			<td colspan=6><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="Save" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				

		</tbody>
	</table>
	</form>	

</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#employee_id').focus();
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