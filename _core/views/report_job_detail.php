<?php

$ps  = '';
$ps1 = '';
$ps2 = '';
if ( $project['project_approval'] == '1'){
	$ps  = 'Waiting for Review';
	$ps1 = ' selected ';
}

if ( $project['project_approval'] == '2'){
	$ps  = 'Reviewed';
	$ps2 = ' selected ';
}

if ( $project['project_approval'] == '3'){
	$ps  = 'Approved';
	$ps2 = ' selected ';
}

echo $project['project_approval'];
if ( $project['project_approval'] == '4'){
	$ps  = 'Closed';
	$ps2 = ' selected ';
}


$ca  = '';
$ca1 = '';
$ca2 = '';

$year_end ='';
if ( strlen( $project['year_end']) >0 ) {
	$year_end = date("d/m/Y",strtotime($project['year_end'])) ;
}

$start_date ='';
if ( strlen( $project['start_date']) >0 ) {
	$start_date = date("d/m/Y",strtotime($project['start_date'])) ;
}

$finish_date ='';
if ( strlen( $project['finish_date']) >0 ) {
	$finish_date = date("d/m/Y",strtotime($project['finish_date'])) ;
}


?>
<a id=export style='cursor:pointer;'><b>Export to EXCEL</b></a>
<div id=inner></div>
<div id=excel><br>
<div class="grid_12">
<fieldset class="form-fieldset">
<legend class="form-legend">Summary of Project Report</legend>
</fieldset>
	<table>
	<tr>
		<td>
			<table style="vertical-align:top;">
				<tr>
					<td class="label">Client Name : </td>
					<td><?=$project['client_name'] ?></td>
					<td></td>
					<td class="label">Initial company : </td>
					<td><?=$project['client_no'] ?></td>
				</tr>	
				<tr>
					<td class="label">Project Code : </td>
					<td><?=$project['project_no'] ?></td>
					<td></td>
					<td class="label">Starting Project : </td>
					<td><?=$start_date ?> -	<?=$finish_date ?></td>
				</tr>
				<tr>
					<td class="label">Financial Year End : </td>
					<td><?=$year_end ?></td>
					<td></td>
					<td class="label">Project Type : </td>
					<td><?=$project['jobtype'] ?></td>
				</tr>
				<tr>
					<td class="label">Job  : </td>
					<td><?=$project['job_no'] ?> - <?=$project['job'] ?></td>
					<td></td>
					<td class="label">Project Status : </td>
					<td><?=$ps ?></td>
				</tr>
			</table>
		</td>		
	</tr>
	
	<tr><td colspan="2"></td></tr>		
	<tr><td colspan="2">
			<table class="grid">
				<thead>
				<tr>
					<th class="table-head" colspan="17">Budget Hour Realisation</th>
				</tr>
				<tr>
					<th>No</th>
					<th>NIK</th>
					<th>Name</th>
					<th>Level</th>
					<th class=currency>Bgt</th>
					<th class=currency>Rals</th>
				</tr>
				</thead>
				<tbody>
			<?php
			$i = 1;
			$total_bgt= 0;
			$total_rals = 0;
			
			if ( $table ) {
				foreach ($table as $k=>$v) {
					$class= '';
					$bgt = $v['project_title']."_hour";
					$total_bgt += $v[$bgt];
					$total_rals += $v['actual'];
			
					
					if ( $i % 2 == 0) $class= 'class="odd"';
					echo "<tr $class>
								<td>$i</td>
								<td>$v[employeeid]</td>
								<td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
								<td>$v[level]</td>
								<td align=right class=currency>".$v[$bgt]."</td>
								<td align=right class=currency>".$v['actual']."</td>
							</tr>";
					$i++;
				}
			}
			?>
					</tbody>
					<tr>
					<th colspan="4" align="right" class="currency"><b>Total </b></th>
			<?php
					echo "<th class='currency' ><b>$total_bgt</b></th>";
					echo "<th class='currency' ><b>$total_rals</b></th>";
			
			?>
						</tr>
			
					<tr><td colspan=15><i>printed date <?=date("d M Y H:i:s");  ?></i></td></tr>
				</table>
	</table>
</div>
</div> 