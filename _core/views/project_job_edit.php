<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>/project/JobUpdate/" >
		<input type="hidden" id="project_id" name="project_id" value="<?=$id ?>"  />
		<input type="hidden" id="mode" name="mode" value="<?=$mode ?>"  />		
		<fieldset class="form-fieldset">
		<legend class="form-legend">JOB Budget </legend>

<table class="grid">
	<thead>
		<tr>
			<th style='width:25px;'>No</th>
			<th style='width:150px;'><input type=checkbox name=select_all id=select_all>Check / UnCheck All</th>
			<th style='width:75px;'>Job Number</th>
			<th>Job</th>
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
				<td style='width:25px;text-align=center;'>";
		echo $i ;
		echo "</td>
				<td align=center style='width:15px;text-align:center;'><input type='checkbox'  name='job_id[]' value=$v[job_id]  style='width:10px;'/> </td>
				<td>$v[job_no]</td>
				<td>$v[job]</td>";
		echo "</tr>";
		$i++;
	}
}
?>
		<tr>
			<td></td>
			<td colspan=3><div class="ff3 UILinkButton">
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
		</tbody>
		</table>	
		</fieldset>
	</form>	

<script>

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});
	
   $('#select_all').click(
      function()
      {
         $("INPUT[type='checkbox']").attr('checked', $('#select_all').is(':checked'));   
      }
   )
	
});
</script>
<?php
$this->load->view('site_footer');

?>