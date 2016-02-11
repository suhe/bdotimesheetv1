﻿<?php
$this->load->view('site_header');

$overtimedate = now();
if ( strlen( $form['overtimedate']) >0 ) {
	$overtimedate = date("d/m/Y",strtotime($form['overtimedate'])) ;
}
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<form id="form" method="POST" action="<?=$site ?>/administration/overtimeUpdate/" >
	
	<input type="hidden" id="overtime_status_id" name="overtime_status_id" value="<?=$form['overtime_status_id'] ?>"  />

<div id="tables" class="block">
<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="10">Overtime List</th>
</tr>
		<tr>
			<th>No</th>
			<th>Date</th>
			<th>Week/Year</th>
			<th class='currency'>Total Hour</th>
			<th class='currency'>Office Hour</th>
			<th class='currency'>Overtime</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
$hour = 0;
$office  = 0;
$overtime = 0;

if ( count( $table) > 0 ) {
	$i = 1;
	$x=0;
	foreach ($table as $k=>$v) {
		$class = '';
		$hour += $v['hour'];
		$office += $v['office'];
		$overtime  += $v['overtime'];
		
		if ( $i % 2 == 0) $class= 'class="odd"';
	
		$status = '';
		if ($v['overtime_approval'] == '1' ){
			$status = 'Waiting Approval';
		}
		if ($v['overtime_approval'] == '2' ){
			$status = 'Approved';
			$link ="";
		}

		if ($v['overtime_approval'] == '3' ){
			$status = 'Returned';
			$link ="";
		}
		
		$overtimedate ='';
		if ( strlen( $v['overtimedate']) >0 ) {
			$overtimedate = date("d/m/Y",strtotime($v['overtimedate'])) ;
		}
		echo "<input type=hidden name=overtimeid[] value=$v[overtimeid] >";
		echo "<tr $class>
					<td id=$i>$i</td>
					<td nowrap>$overtimedate</td>
					<td>$v[week] - $v[year]</td>
					<td class='currency'><input type='text'  class='inputtext sum' name='hour[]' value='$v[hour]' size='4' style='width:50px;text-align:right'/></td>
					<td class='currency' id=off$x>".number_format($v['office'],0)."</td>
					<td class='currency' id=ot$x>".number_format($v['overtime'],0)."</td>";
		if ($status=='') {
			$del = "<a href='$site/administration/overtimeDel/$v[overtimeid]/$v[overtime_status_id]' class='hapus'>[ Del ]</a>";
			echo "<td class='currency' nowrap>$del</td>";
		}
		else
		{
			echo "<td class='currency' nowrap></td>";
		}
		echo "</tr>";
		$i++;
		$x++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2"></td>
				<th>Total</th>
				<th class='currency' id=totH><?=number_format($hour)?></th>
				<th class='currency'><?=number_format($office)?></th>
				<th class='currency'><?=number_format($overtime)?></th>
				<td colspan="3"></td>
			</tr>
		</tfoot>

</table>
<table align="center" width="'100%'">
<tr>
<td colspan=2 align="center" style="text-align:center;">
	<div class="ff3 UILinkButton" >
		<input type="submit"  id="submit"  value="Save" class="ff3 UILinkButton_A"/>
		<div class="UILinkButton_RW">
			<div class="UILinkButton_R"/></div>
		</div>
	</div>

	<div class="ff3 UILinkButton" style="padding-left:10px;">
		<input type="button"  id="request"  value="Request Approval" class="ff3 UILinkButton_A"/>
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
			
</div>
</form>	

<script>

$(function () {
	$('a.hapus').click( function (e) {
		retval = window.confirm('Are you sure delete this record ?');
		return retval;
	});
	$('#back').click( function (e) {
		window.location.href ='<?=$back?>';
	});

	$('#request').click( function (e) {
		window.location.href ='<?=$request?>';
	});
	
	
	function pcalc() {
		var i=0, totH = 0, rate = 0, cost=0;
		$('input.sum').each(function() {
			var val = $(this).val() ? $(this).val() : 0;
			$('#ot'+i).html(val-8);
			totH += parseFloat(val);
			$i++;
		});
		
		$("#totH").html(totH);
	}		

		
	//$("input[name^='hour']").blur(function() {pcalc();});			
/*
	$('#submit').click( function (e) {
	   var hour = $.trim( $('#hour').val());
     	var errSubmit = '';


		if (hour.length == 0) {
			$('#hour').focus();
			errSubmit += 'Total Hour must be fill out \n';	
		} 

		if (errSubmit) {
			alert(errSubmit);
			return false;
		} else {
			return true;
		}			
	});
*/

});
</script>
<?php
	$this->load->view('site_footer');
?>