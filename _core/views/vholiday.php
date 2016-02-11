<?php
$this->load->view('site_header');
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/data/HolidayUpdate/" >
		<input type="hidden" name="holiday_id" value="<?=$form['holiday_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Holiday Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">Date : </td>
			<td>
            <input type="text"  class="inputtext date"  id="date" name="holiday_date" value="<?=$form['holiday_date']?>" size="60" style='width:75px;' />
            </td>
		</tr>

		<tr>
			<td class="label">Description: </td>
			<td><input type="text"  class="inputtext" id="desc"  name="holiday_desc" value="<?=$form['holiday_desc'] ?>" size="255" /></td>
		</tr>
		
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="Save" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				
				</td>
		</table>	
		</fieldset>
	</form>	
</table>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#date').focus();
	}
);	

$(function () {
	$('#submit').click( function (e) {
		var date = $.trim($('#date').val()); 
		var desc = $.trim( $('#desc').val());
		var errSubmit = '';

		if (date.length == 0) {
			$('#date').focus();
			errSubmit += 'Date must be fill out\n';	
		}

		if (desc.length == 0) {
			$('#desc').focus();
			errSubmit += 'Desc Type must be fill out\n';	
		} 

		if (errSubmit) {
			alert(errSubmit);
			return false;
		} else  {
			return true;
		}			
	});
    $('input#date').datepick({dateFormat:'dd/mm/yy', showWeeks:true,firstDay: 1, minDate:new Date(2013,1,1)});
});
</script>
						

<div id="tables" class="block">
<table class="grid">
	<thead>
		<tr>
			<th class="table-head" colspan="9">Holiday List</th>
		</tr>
		<tr>
			<th>No</th>
			<th>Date</th>
			<th>Description</th>
			<th>Action</th>
		</tr>
	</thead>
<tbody>
<?php
if ( count( $table) > 0 ) {
	$i = 1;
	foreach ($table as $k=>$v) {
		$class= '';
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		
		echo "<tr $class >
				<td>";
		echo $i ;
		echo "</td>
				<td>$v[date]</td>
				<td>$v[holiday_desc]</td>
				<td align='right'>
                <!--<a href='$site/data/holiday/$v[holiday_id]/'>[ edit ]</a>-->
                <a href='$site/data/removeholiday/$v[holiday_id]/'>[ remove ]</a>
                </td>";
		echo "</tr>";
		$i++;
	}
}
?>
</tbody>
</table>
</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#date').focus();
	}
);	

</script>
<?php
$this->load->view('site_footer');

?>