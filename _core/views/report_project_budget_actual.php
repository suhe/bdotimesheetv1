<?php
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
if ( $form['project_approval'] == '4'){
	$ps  = 'Closed';
	$ps2 = ' selected ';
}


$ca  = '';
$ca1 = '';
$ca2 = '';

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
<div class="grid_12">
	<fieldset class="form-fieldset">
      <legend class="form-legend">Report by Project</legend>
    </fieldset>
	<div id="excel">
<table>
<tr>
<td>
<table style="vertical-align:top;">
		<tr>
			<td class="label">Client Name : </td>
			<td><?=$cClient ?></td>
			<td></td>
			<td class="label">Initial company : </td>
			<td><?=$form['client_no'] ?></td>
		</tr>
		<tr>
			<td class="label">Project Code : </td>
			<td><?=$form['project_no'] ?></td>
			<td></td>
			<td class="label">Starting Project : </td>
			<td><?=$start_date ?>  -	<?=$finish_date ?></td>
		</tr>
		<tr>
			<td class="label">Financial Year End : </td>
			<td><?=$year_end ?></td>
			<td></td>
			<td class="label">Project Type : </td>
			<td><?=$form['jobtype'] ?></td>
		</tr>
		<tr>
      <td></td>
      <td></td>
      <td></td>
      <td class="label">Project Status : </td>
			<td><?=$ps ?></td>
		</tr>
</table>		
</td>	
</tr>	
<tr>
<td colspan="2">

<table class="grid">
	<thead>
		<tr><th class="table-head" colspan="3">Structure Team</th></tr>
		<tr>
			<th>No</th>
			<th>Title</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
	<?=$team ?>
	</tbody>
</table>
</td>
</tr>
<tr><td colspan="2"></td></tr>		
<tr><td colspan="2">
<table class="grid">
	<thead>
	<tr>
		<th class="table-head" colspan="15">Budget Hour Realisation</th>
	</tr>
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2">Job Number</th>
		<th rowspan="2">Job</th>
<?php
	if ( $header_team ) {
		foreach ($header_team as $k=>$v) {
			echo "<th class=currency colspan=2>$v[tipe]</th>";
		}
	}
?>
<th class=currency colspan=2 >Total per Job</th>

	</tr>
	<tr>
<?php
	if ( $header_team ) {
		foreach ($header_team as $k=>$v) {
			echo "<th class=currency>Bgt</th>";
			echo "<th class=currency>Rals</th>";
			//echo "<th class=currency>A.C</th>";
		}
	}
			echo "<th class=currency>Bgt</th>";
			echo "<th class=currency>Rals</th>";

?>
	</tr>
	</thead>
	<tbody>
<?php
$i = 1;
$budget_hour = '';
$hour = 0;
  $total_row_hour = 0;
  $total_row_hour_act = 0;

  $total_01_hour=0;
  $total_02_hour=0;
  $total_03_hour=0;
  $total_041_hour=0;
  $total_042_hour=0;
  $total_01_hour_act=0;
  $total_02_hour_act=0;
  $total_03_hour_act=0;
  $total_041_hour_act=0;
  $total_042_hour_act=0;
  
if ( $table_job ) {
	foreach ($table_job as $k=>$v) {
	  $class= '';
      $row_hour = 0;
      $row_hour_act = 0;

      $ass_hour  = $v['042_hour'] + $v['043_hour'];
      $ass_hour_act  = $v['042_hour_act'] + $v['043_hour_act'];
	  // Make Total 04-2010 ram
	  $total_01_hour +=$v['01_hour'];
	  $total_02_hour +=$v['02_hour'];
	  $total_03_hour +=$v['03_hour'];
	  $total_041_hour +=$v['041_hour'];
	  $total_042_hour +=$ass_hour;
	  $total_01_hour_act +=$v['01_hour_act'];
	  $total_02_hour_act +=$v['02_hour_act'];
	  $total_03_hour_act +=$v['03_hour_act'];
	  $total_041_hour_act +=$v['041_hour_act'];
	  $total_042_hour_act +=$ass_hour_act;
	  
      $row_hour  = $v['01_hour'] + $v['02_hour'] + $v['03_hour'] + $v['041_hour'] + $v['042_hour'] + $v['043_hour'];
      $total_row_hour += $row_hour ;

      $row_hour_act  = $v['01_hour_act'] + $v['02_hour_act'] + $v['03_hour_act'] + $v['041_hour_act'] + $v['042_hour_act'] + $v['043_hour_act'];
      $total_row_hour_act += $row_hour_act;

		
		if ( $i % 2 == 0) $class= 'class="odd"';
		echo "<tr $class>
					<td>$i</td>
					<td>$v[job_no]</td>
					<td>$v[job]</td>
					<td align=right class=currency>".$v['01_hour']."</td>
					<td align=right class=currency>".$v['01_hour_act']."</td>
					<td align=right class=currency>".$v['02_hour']."</td>
					<td align=right class=currency>".$v['02_hour_act']."</td>
					<td align=right class=currency>".$v['03_hour']."</td>
					<td align=right class=currency>".$v['03_hour_act']."</td>
					<td align=right class=currency>".$v['041_hour']."</td>
					<td align=right class=currency>".$v['041_hour_act']."</td>
					<td align=right class=currency>".$ass_hour  ."</td>
					<td align=right class=currency>".$ass_hour_act  ."</td>
					<td align=right class=currency>".$row_hour ."</td>
					<td align=right class=currency>".$row_hour_act ."</td>

				</tr>";
		$i++;
	}
}
?>
		</tbody>
			<tr>
				<td colspan="3" align="right" class="currency"><b>Total </b></td>
<?php
	/*if ( $budgetTotal ) {
		foreach ($budgetTotal as $k=>$v) {
			echo "<td class='currency' id='totH$k'><b>".number_format($v['budget_hour'])."</b></td>";
			echo "<td class='currency' id='totAH$k'><b>".number_format($v['actual_hour'])."</b></td>";
		}
	}*/
	echo "<td class='currency' id='totH0'><b>".number_format($total_01_hour)."</b></td>";
	echo "<td class='currency' id='totAH0'><b>".number_format($total_01_hour_act)."</b></td>";
	echo "<td class='currency' id='totH1'><b>".number_format($total_02_hour)."</b></td>";
	echo "<td class='currency' id='totAH1'><b>".number_format($total_02_hour_act)."</b></td>";
	echo "<td class='currency' id='totH2'><b>".number_format($total_03_hour)."</b></td>";
	echo "<td class='currency' id='totAH2'><b>".number_format($total_03_hour_act)."</b></td>";
	echo "<td class='currency' id='totH3'><b>".number_format($total_041_hour)."</b></td>";
	echo "<td class='currency' id='totAH3'><b>".number_format($total_041_hour_act)."</b></td>";
	echo "<td class='currency' id='totH4'><b>".number_format($total_042_hour)."</b></td>";
	echo "<td class='currency' id='totAH4'><b>".number_format($total_042_hour_act)."</b></td>";
		echo "<td class='currency' ><b>$total_row_hour</b></td>";
		echo "<td class='currency' ><b>$total_row_hour_act</b></td>";
	
?>
		</tr>
		<tr><td colspan=15><i>printed date <?=date("d M Y H:i:s");  ?></i></td></tr>
	</table>
	</td>
	</tr>
	</table>
</div><!--end Excel-->
</div> 
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

</script>
