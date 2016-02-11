<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>/main/projectPICUpdate/" >
		<input type="hidden" id="project_id" name="project_id" value="<?=$id ?>"  />
		<input type="hidden" id="mode" name="mode" value="<?=$mode ?>"  />		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Project - IN Charge - Update</legend>

<table class="grid">
	<thead>
		<tr>
			<th>No</th>
			<th>Select</th>
			<th>Name</th>
			<th>Title</th>
			<th>Department</th>

		</tr>
	</thead>
	<tbody>
<?php
$i = 1;

if ( $table ) {
	foreach ($table as $k=>$v) {
		$class= '';
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td align=left><input type='checkbox'  class='inputtext' name='employee_id[]' value=$v[employee_id]  style='width:10px;'/> </td>
				<td>$v[employeefirstname] $v[employeemiddlename]  $v[employeelastname]</td>
				<td>$v[employeetitle]</td>
				<td>$v[departmentname]</td>";

		echo "</tr>";
		$i++;
	}
}
?>
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
			<td colspan="3"></td>
		</tbody>
		</table>	
		</fieldset>
	</form>	

<script>

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});
});
</script>
<?php
$this->load->view('site_footer');

?>