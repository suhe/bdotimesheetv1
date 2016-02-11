<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=excel.xls" );
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");

$cClient ='';
foreach ($client as $k=>$v) {
	$selected = '';
	if ( $v['client_id'] == $form['client_id'] ) {
		$cClient .= $v['client_name'] ;
	} 
}

$ps  = '';
$ps1 = '';
$ps2 = '';
if ( $form['project_approval'] == '1'){
	$ps  = 'Waiting for Review';
	$ps1 = ' selected ';
}

if ( $form['project_approval'] == '2'){
	$ps  = 'Reviewed';
	$ps2 = ' selected ';
}

if ( $form['project_approval'] == '3'){
	$ps  = 'Approved';
	$ps2 = ' selected ';
}

if ( $project['project_approval'] == '4'){
	$ps  = 'Closed';
	$ps2 = ' selected ';
}


$ca  = '';
$ca1 = '';
$ca2 = '';
?>
<html>
<body>

<div class="grid_12">
<table>
<tr>
<td>
<table style="vertical-align:top;">
		<tr>
			<td class="label">Client Name : </td>
			<td><?=$cClient ?></td>
		</td>
		<tr>
			<td class="label">Engagement / Contract Number : </td>
			<td><?=$form['contract_no'] ?></td>
		</td>
<?php
$year_end ='';
if ( strlen( $form['year_end']) >0 ) {
	$year_end = date("d/m/Y",strtotime($form['year_end'])) ;
}

$start_date ='';
if ( strlen( $form['start_date']) >0 ) {
	$start_date = date("d/m/Y",strtotime($form['start_date'])) ;
}

$finish_date ='';
if ( strlen( $form['finish_date']) >0 ) {
	$finish_date = date("d/m/Y",strtotime($form['finish_date'])) ;
}

$client_approval_date ='';
if ( strlen( $form['client_approval_date']) >0) {
	$client_approval_date = date("d/m/Y",strtotime($form['client_approval_date'])) ;
}
?> 
		<tr>
			<td class="label">Financial Year End : </td>
			<td><?=$year_end ?></td>
		</tr>

		<tr>
			<td class="label">Project Code : </td>
			<td><?=$form['project_no'] ?></td>
		</td>
		<tr>
			<td class="label">Project Name : </td>
			<td><?=$form['project'] ?></td>
		</tr>

		<tr>
			<td class="label">Start - Finish Date : </td>
			<td><?=$start_date ?> &nbsp;&nbsp; / &nbsp;&nbsp;	<?=$finish_date ?>
				</td>
		</tr>
		<tr>
			<td class="label">Project Status</td>
			<td><?=$ps ?>
</table>

<td>		
</td>		
<tr>
<td colspan="2">

<table class="grid">
<thead>
<tr>
<th class="table-head" colspan="5">Team Structure</th>
</tr>
		<tr>
			<th>No</th>
			<th>Title</th>
			<th>Name</th>
		</tr>
	</thead>
<?=$team ?>
</table>
</td>
</tr>
<tr>

<tr><td colspan="2">&nbsp;</td></tr>		
<tr><td colspan="2">
<table class="grid">
	<thead>
	<tr>
		<th class="table-head" colspan="17">Budget Hour Cost</th>
	</tr>
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2">Job Number</th>
		<th rowspan="2">Job</th>
<?php
	if ( $header_team ) {
		foreach ($header_team as $k=>$v) {
			echo "<th class=currency>$v[tipe]</th>";
		}
	}
?>
	</tr>
	<tr>
<?php
	if ( $header_team ) {
		foreach ($header_team as $k=>$v) {
			echo "<th class=currency>P.B.H</th>";
		}
	}
?>
	</thead>
	<tbody>
<?php
$i = 1;
$budget_hour = '';
$hour = 0;
if ( $table_job ) {
	foreach ($table_job as $k=>$v) {
		$class= '';
		
		if ( $i % 2 == 0) $class= 'class="odd"';
		echo "<input type=hidden name=id[] value=".$v['id'].">";	
		echo "<tr $class>
					<td>$i</td>
					<td>$v[job_no]</td>
					<td>$v[job]</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum0' name='01_hour[]' value='".$v['01_hour']."' size='4' style='width:50px;text-align:right'/>".$v['01_hour']."</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum1' name='02_hour[]' value='".$v['02_hour']."' size='4' style='width:50px;text-align:right'/>".$v['02_hour']."</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum2' name='03_hour[]' value='".$v['03_hour']."' size='4' style='width:50px;text-align:right'/>".$v['03_hour']."</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum3' name='041_hour[]' value='".$v['041_hour']."' size='4' style='width:50px;text-align:right'/>".$v['041_hour']."</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum4' name='042_hour[]' value='".$v['042_hour']."' size='4' style='width:50px;text-align:right'/>".$v['042_hour']."</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum5' name='043_hour[]' value='".$v['043_hour']."' size='4' style='width:50px;text-align:right'/>".$v['043_hour']."</td>
					<td align=right class=currency><input type='hidden'  class='inputtext sum6' name='044_hour[]' value='".$v['044_hour']."' size='4' style='width:50px;text-align:right'/>".$v['044_hour']."</td>
				</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="3" align="right" class="currency">Total Hour</th>
<?php
	if ( $budgetTotal ) {
		foreach ($budgetTotal as $k=>$v) {
			echo "<th class='currency' id='totH$k'>".number_format($v['budget_hour'])."</th>";
		}
	}
?>
			</tr>
			<tr><th colspan="3" align="right" class="currency">Equivalen (days)</th>
<?php
	if ( $budgetTotal ) {
		foreach ($budgetTotal as $k=>$v) {
			echo "<th id='equD$k' class='currency'>".number_format($v['budget_days'])."</th>";
		}
	}
?>
			<tr><th colspan="3" align="right" class="currency">Rate per Day  </th>
<?php
if ( $budgetTotal ) {
	foreach ($budgetTotal as $k=>$v) {
		echo "<td align=right class=currency> 
				<input type=hidden id='rate$k' name=".$v['project_title']."_rate value='".$v['budget_rate']."' style=width:50px;text-align:right;>".number_format($v['budget_rate'])."";
	}
}
?>
			</tr>
			<tr><th colspan="3" align="right" class="currency">Cost per Level
<?php
	$total=0;
	if ( $budgetTotal ) {
		foreach ($budgetTotal as $k=>$v) {
			$total += $v['budget_cost'];
			echo "<td id='cost$k' align=right class=currency>".number_format($v['budget_cost'])."</td>";
		}
	}
?>
			<tr><th colspan="3" align="right" class="currency">Grand Total</th>
				<td id="grandTotal" align="right" class="currency"><?=number_format($total)?></td>
				<td align="right" colspan="6"> &nbsp;</td>
			</tr>
		</tfoot>
	</table>

<script>
// When the page is ready
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function pcalc() {
	var totH = [], grandT = 0, rate = 0;
	for(var i=0; i<7; i++) {
		 totH[i] = 0;
		$('input.sum'+i).each(function() {
			var val = $(this).val() ? $(this).val() : 0;
			totH[i] += parseInt(val);
		});
		$("#totH"+i).html(totH[i]);
		$("#equD"+i).html(Math.ceil(totH[i]/8));
		rate = $('#rate'+i).val() ? parseFloat($('#rate'+i).val()) : 0;
		$('#rate'+i).val(rate);
		$("#cost"+i).html(Math.ceil(totH[i]/8) * rate);
		grandT += Math.ceil(totH[i]/8) * rate;
	}
	$('#grandTotal').html(addCommas(grandT.toFixed(2));
};
$(document).ready(function(){
 	$('#project_no').focus();
	$('#back').click( function (e) {
		window.location.href = '<?=$back?>';
	});
	pcalc();
	for(var i=0; i<7; i++) {
		$('input.sum'+i).blur(function() {pcalc();});
		$('#rate'+i).blur(function() {pcalc();});
	}
});
/*
$(function () {
	$('#submit_job').click( function (e) {
		//alert('xx');
		//$('#form_job').submit();
	});
});
*/
</script>
</body>
</html>