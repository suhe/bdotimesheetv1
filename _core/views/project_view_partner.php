<?php
$this->load->view('site_header');

/*
$cClient ='<select name=client_id id=client_id class="mandatory" disabled="true">';
$cClient .= '<option value=0>Please Choose</option>';
foreach ($client as $k=>$v) {
	$selected = '';
	if ( $v['client_id'] == $form['client_id'] ) {
		$selected = ' selected ';
	} 
	$cClient .= '<option value='.$v['client_id']. $selected .'>'. $v['client_name'] .'</option>';
}
$cClient .= '</selected>';*/
$cClient = '<input type="text"  class="inputtext mandatory" id="client_id" name="client_name"  value="'.$form['client_name'].'" size="80"  style="border:none" /> ';

/*
$cJobType ='<select name=jobtype_id id=jobtype_id class="mandatory">';
$cJobType .= '<option value=0>Please Choose</option>';
foreach ($jobtype as $k=>$v) {
	$selected = '';
	if ( $v['jobtype_id'] == $form['jobtype_id'] ) {
		$selected = ' selected ';
	} 
	$cJobType  .= '<option value='.$v['jobtype_id']. $selected .'>'. $v['jobtype'] .'</option>';
}
$cJobType .= '</selected>';
*/
$cJobType = '<input type="text"  class="inputtext mandatory" id=jobtype_id name=jobtype_id  value="'.$form['jobtype'].'" size="80"  style="border:none" /> ';


$ps1='';
$ps2='';
if ( $form['project_status'] == '1'){
	$ps1=' selected ';
	
}
if ( $form['project_status'] == '2'){
	$ps2=' selected ';
}

$ca1='';
$ca2='';
if ( $form['client_approval'] == '1'){
	$ca1=' selected ';
}
if ( $form['client_approval'] == '2'){
	$ca2=' selected ';
}

$sc1 = '';
$sc2 = '';
$sc3 = '';
if ( $form['status_collection'] == '1'){
	$sc1 = ' selected ';
}
if ( $form['status_collection'] == '2'){
	$sc2 = ' selected ';
}
if ( $form['status_collection'] == '3'){
	$sc3 = ' selected ';
}

$year_end ='';
if ( strlen( $form['year_end']) >0 ) {
	$year_end = date("d/m/Y",strtotime($form['year_end'])) ;
	if ($form['year_end']=='0000-00-00' || $form['year_end']=='1970-01-01')  $year_end = '';
}

$start_date ='';
if ( strlen( $form['start_date']) >0 ) {
	$start_date = date("d/m/Y",strtotime($form['start_date'])) ;
	if ($form['start_date']=='0000-00-00' || $form['start_date']=='1970-01-01')  $start_date = '';
}

$finish_date ='';
if ( strlen( $form['finish_date']) >0 ) {
	$finish_date = date("d/m/Y",strtotime($form['finish_date'])) ;
	if ($form['finish_date']=='0000-00-00' || $form['finish_date']=='1970-01-01')  $finish_date = '';
}

$client_approval_date ='';
if ( strlen( $form['client_approval_date']) >0) {
	$client_approval_date = date("d/m/Y",strtotime($form['client_approval_date'])) ;
	if ($form['client_approval_date']=='0000-00-00' || $form['client_approval_date']=='1970-01-01')  $client_approval_date= '';
}
$createdate ='';
if ( strlen( $form['createdate']) >0) {
	$createdate = date("d/m/Y",strtotime($form['createdate'])) ;
}


  $xchecked = '';
      if 	($form['location']=='1'){
        $xchecked = ' checked ';
      }
?> 
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form_project" method="POST" action="<?=$site ?>/project/UpdatePartner/" >
		<input type="hidden" id="project_id" name="project_id" value="<?=$form['project_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Project Information</legend>
	<table>
	<tr>
		<td colspan=2 align=left style="text-align:left;" class="label-message" id="msg"><?=$form['message'] ?></td>
	</td>
	<tr>
	<td>
	<table style="vertical-align:top;">
		<tr>
			<td class="label">Client Name   : </td>
			<td><?=$cClient ?> </td>
		</td>
		<tr>
			<td class="label">Engagement / Contract Number : </td>
			<td><input type="text"  class="inputtext mandatory" id="contract_no" name="contract_no"  value="<?=$form['contract_no'] ?>" size="40" style="border:none" /> </td>
		</td>
		<tr>
			<td class="label" nowrap style="width:180px;">Financial Year End : </td>
			<td><input type="text"  class="inputtext date mandatory" readonly="true" id="year_end" name="year_end" value="<?=$year_end ?>" size="40" style="width:80px;border:none"/></td>
		</tr>
		<tr>
			<td class="label">Job Type : </td>
			<td><?=$cJobType ?>  </td>
		</td>
		<tr>
			<td class="label">Project Code : </td>
			<td><input type="text" disabled="true" class="inputtext mandatory" id="project_no" name="project_no" value="<?=$form['project_no'] ?>" size="40" style="border:none"/> </td>
		</td>
<!--
		<tr>
			<td class="label">Project Name : </td>
			<td><input type="text"  class="inputtext mandatory" id="project" name="project" value="<?=$form['project'] ?>" size="60" /> Required</td>
		</tr>
-->		
		<tr>
			<td class="label">Start Date : </td>
			<td><input type="text"  class="inputtext date" readonly="true" id="start_date" name="start_date" value="<?=$start_date ?>" size="40"  style="width:80px;border:none"/>
				 &nbsp;&nbsp; Finish Date :  &nbsp;&nbsp;	
				<input type="text"  class="inputtext date" readonly="true" id="finish_date" name="finish_date" value="<?=$finish_date ?>" size="40" style="width:80px;border:none"/>
			</td>
		</tr>
		<tr>
			<td class="label">Created : </td>
			<td><?=$form['creator'] ?> - <?=$createdate ?>
			
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
			<?php //$team ?>
			
			<?php 
             //if($this->session->userdata('department_id')==7){			
		print $this->load->view('project_team_bki');
	    //} else  {
		//print $team;
		print $oteam;
	    //}	
			?>
		</table>
	</td>
</tr>
<tr>
	<td colspan=2 align="center" style="text-align:center;">
		<div class="ff3 UILinkButton" >
			<input type="submit"  id="submit"  value="Update Team" class="ff3 UILinkButton_A"/>
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
</tr>	
</fieldset>
</form>
		
<?php 
if ($form['project_id'] != 0) { ?>		
<tr><td colspan="2">&nbsp;</td></tr>		
<tr><td colspan="2">
	<form id="form_job" method="POST" action="<?=$site ?>/project/UpdateBudgetCost/" >
		<input type="hidden" id="project_id" name="project_id" value="<?=$form['project_id'] ?>" />

<table class="grid">
	<thead>
	<tr></tr>
	<th class="table-head" colspan="17">Budget Hour Cost</th>
	<tr><td align="left" colspan=17> <div id="paging">	
		<span style="float:right;text-align:right;"><a href="<?=$site?>/project/jobEdit/<?=$form['project_id'] ?>/del/<?=$form['jobtype_id'] ?>" > [ DELETE ] </a></span>
		<span style="float:right;text-align:right;">&nbsp;&nbsp;&nbsp;</span>
		<span style="float:right;text-align:right;"><a href="<?=$site?>/project/jobEdit/<?=$form['project_id'] ?>/add/<?=$form['jobtype_id'] ?>" > [ ADD NEW ] </a></span>
			
		</div>
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
	<th class=currency rowspan=2>Budget Total</th>
	</tr>
  
	<tr>
<?php
	if ( $header_team ) {
		foreach ($header_team as $k=>$v) {
			echo "<th class=currency>P.B.Hd</th>";
		}
	}
?>

	</tr>		
	</thead>
	<tbody>
<?php
	$i = 1;
	$budget_hour = '';
	$hour = 0;
    $total_row_hour = 0;

	if ( $table_job ) {
		foreach ($table_job as $k=>$v) {
      $row_hour = 0;
      //$row_hour  = $v['01_hour'] + $v['02_hour'] + $v['03_hour'] + $v['041_hour'] + $v['042_hour'] + $v['043_hour'];
		$row_hour  = $v['01_hour'] + $v['02_hour'] + $v['03_hour'] + $v['041_hour'] + $v['042_hour'] + $v['777_hour'] ;
        $total_row_hour += $row_hour ;
			$class= '';
			if ( $i % 2 == 0) $class= 'class="odd"';
			if ($this->session->userdata('department_id')==7) 
				 $bki_hour = "<td align=right class=currency><input type='text'  class='inputtext sum5' name='777_hour[]' value='".$v['777_hour']."' size='4' style='width:50px;text-align:right'/></td>";
			else $bki_hour='';
			echo "<input type=hidden name=id[] value=".$v['id'].">";	
			echo "<tr $class>
					<td>$i</td>
					<td>$v[job_no]</td>
					<td>$v[job]</td>
					<td align=right class=currency><input type='text'  class='inputtext sum0' name='01_hour[]'  value='".$v['01_hour']."' size='4' style='width:50px;text-align:right'/> </td>
					<td align=right class=currency><input type='text'  class='inputtext sum1' name='02_hour[]'  value='".$v['02_hour']."' size='4' style='width:50px;text-align:right' /> </td>
					<td align=right class=currency><input type='text'  class='inputtext sum2' name='03_hour[]'  value='".$v['03_hour']."' size='4' style='width:50px;text-align:right' /> </td>
					<td align=right class=currency><input type='text'  class='inputtext sum3' name='041_hour[]' value='".$v['041_hour']."' size='4' style='width:50px;text-align:right' /> </td>
					<td align=right class=currency><input type='text'  class='inputtext sum4' name='042_hour[]' value='".$v['042_hour']."' size='4' style='width:50px;text-align:right'/> </td>
					$bki_hour
					<td align=right class=currency>".$row_hour ."</td>
					
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
			echo "<th class='currency' id='totH$k'>$v[budget_hour]</th>";
		}
	}
	echo "<th class='currency' >$total_row_hour</th>";
?>

	</tr>
	<tr><th colspan="3" align="right" class="currency">Equivalen (days)</th>
<?php
	$total_days = 0;

	if ( $budgetTotal ) {
		foreach ($budgetTotal as $k=>$v) {
    $total_days += $v['budget_days'];
			echo "<th id='equD$k' class='currency'>$v[budget_days]</th>";
		}
	}
	
	echo "<th class='currency' >$total_days</th>";
?>
	</tr>
	<tr><th colspan="3" align="right" class="currency">COST PER DAY</th>
<?php
	if ( $budgetTotal ) {
		foreach ($budgetTotal as $k=>$v) {
			$rate = $v['budget_rate'];
			if (strlen( $rate ) === 0) $rate = 0;
			echo "<td align=right class=currency> 
					<input type=text id='rate$k' name=$v[project_title]_rate value='$rate' size=10 style='width:100px;text-align:right'></td>";
		}
	}
	echo "<td align=right class=currency>&nbsp;"; 
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
	echo "<td id='subTotal11' align=right class=currency>".number_format($total )."</td>";
	//echo "<td id='total-cost$k' align=right class=currency>&nbsp;";
?>
	</tr>
<tr>
		<th colspan="3" align="right" class="currency">Total Cost</th>
		<td id="subTotal1" align="right" class="currency"><?=number_format($total); ?></td>
		<td align="right" colspan="6"> &nbsp;</td>
	</tr>

<?php
  $totalOther = 0;
  $totalOtherBudgetHour = 0;
  $totalOtherBudgetCost = 0;
  $totalOtherActualHour = 0;
  $totalOtherActualCost = 0;

	$y = 1;
	if ( $budgetOther ) {
	echo "<tr><td class=odd colspan=10>&nbsp;</th></tr>
	<tr>
		<th rowspan=2>No</th>
		<th rowspan=2 colspan=2>Special Assignment </th>
		<th colspan=2 align=center style='text-align:center;padding-left:75px;'>Budget</th>
		<th colspan=2 align=center style='text-align:center;padding-left:75px;'>Actual</th>

		<th rowspan=2 colspan=6>&nbsp
		<tr><th align=center style='text-align:center;padding-left:55px;'>Hour</th>
		    <th align=center style='text-align:center;padding-left:55px;'>Cost</th>
		    <th align=center style='text-align:center;padding-left:55px;'>Hour</th>
		    <th align=center style='text-align:center;padding-left:55px;'>Cost</th>";

		foreach ($budgetOther as $k=>$v) {
			$totalOtherBudgetHour += $v['budget_hour'];
			$totalOtherBudgetCost += $v['budget_cost'];
			$totalOtherActualHour += $v['actual_hour'];
			$totalOtherActualCost += $v['actual_cost'];
			echo "<input type=hidden name=id-oth[] value=".$v['teamid'].">";	
    	echo "<tr>
    	    <td align=center>$y
    	    <td colspan=2 align=center>$v[lookup_label]
    	    <td align=right class=currency><input type='text'  class='inputtext sum-oth0' name='other_budget_hour[]' value='".$v['budget_hour']."' size='4' style='width:50px;text-align:right;'/>
    	    <td align=right class=currency><input type='text'  class='inputtext sum-oth1' name='other_budget_cost[]' value='".$v['budget_cost']."' size='4' style='width:50px;text-align:right;'/>
    	    <td align=right class=currency><input type='text'  class='inputtext sum-oth2' name='other_actual_hour[]' value='".$v['actual_hour']."' size='4' style='width:50px;text-align:right;'/>
    	    <td align=right class=currency><input type='text'  class='inputtext sum-oth3' name='other_actual_cost[]' value='".$v['actual_cost']."' size='4' style='width:50px;text-align:right;'/>
		    	<td colspan=6>&nbsp;";
		$y++;
		}
		echo "	<tr>
		<th colspan='3' align='right' class='currency'>SubTotal</th>
		<td id='totOth0' align='right' class='currency'>".number_format($totalOtherBudgetHour)."</td>
		<td id='totOth1' align='right' class='currency'>".number_format($totalOtherBudgetCost)."</td>
		<td id='totOth2' align='right' class='currency'>".number_format($totalOtherActualHour)."</td>
		<td id='totOth3' align='right' class='currency'>".number_format($totalOtherActualCost)."</td>
		<td align='right' colspan='6'> &nbsp;</td>
	</tr>	";


		echo "	<tr>
		<th colspan='3' align='right' class='currency'>Total Cost</th>
		<td id='subTotalBudgetHour' align='right' class='currency'>".number_format($totalOtherBudgetCost)."</td>
		<td align='right' colspan='9'> &nbsp;</td>
	</tr>	";

	}
?>
	<tr>
		<th colspan="3" align="right" class="currency">Grand Total</th>
		<td id="grandTotal" align="right" class="currency"><?=number_format($total)?></td>
		<td align="right" colspan="6"> &nbsp;</td>
	</tr>
	</tfoot>
	</table>

	<table align="center" width="'100%'">
		<tr>
			<td align="center">
<?php
		//if ($this->session->userdata('acl')==='01'|| $this->session->userdata('acl')==='02' || $this->session->userdata('acl')==='03' || $this->session->userdata('acl')==='008' ){
			//if ($form['project_approval']< 2 ) {
				echo '
				<div class="ff3 UILinkButton">
					<input type="submit"  id="submit_job"  value="Update Budget" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW"><div class="UILinkButton_R"/></div></div>
				</div>';
			//}				

						//if ($form['project_approval']=='' ) {
				/*echo '
				<div class="ff3 UILinkButton" style="padding-left:10px;">
					<input type="button"  id="approve"  value="Request Review" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW"><div class="UILinkButton_R"/></div></div>
				</div>';*/		
			//}

		//}
?>

<?php
if ($this->session->userdata('acl')==='01') {
	if ($form['project_approval']==='1') {
		echo '
		<input type=hidden name=flagapproval value=2>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/2').'">Reviewed</a>
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}
	if ($form['project_approval']==='2') {
		echo '
		<input type=hidden name=flagapproval value=3>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/3').'">Approved</a>
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}

	if ($form['project_approval']==='3') {
		echo '
		<input type=hidden name=unlock value=1>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/4').'">Closed</a>
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}
	
	if ($form['project_approval']==='4') {
		echo '
		<input type=hidden name=flagapproval value=3>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/3').'">Reviewed</a>
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}
}

if ($this->session->userdata('acl')==='02') {
	if ($form['project_approval']==='1') {
		echo '
		<input type=hidden name=flagapproval value=2>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/2').'">Reviewed</a>
			<!--<input type="submit"  id="reviewed"  value="Reviewed" class="ff3 UILinkButton_A"/>-->
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}
	if ($form['project_approval']==='2') {
		echo '
		<input type=hidden name=flagapproval value=3>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/3').'">Approved</a>
			<!--<input type="submit"  id="approved"  value="Approved" class="ff3 UILinkButton_A"/>-->
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}

	if ($form['project_approval']==='3') {
		echo '
		<input type=hidden name=flagapproval value=4>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
			<a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/4').'">Closed</a>
			<!--<input type="submit"  id="closed"  value="Closed" class="ff3 UILinkButton_A"/>-->
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}

	if ($form['project_approval']==='4') {
		echo '
		<input type=hidden name=flagapproval value=3>
		<div class="ff3 UILinkButton" style="padding-left:10px;">
		        <a class="ff3 UILinkButton_A" style="font-size:12px" href="'.site_url('project/reviewproject/'.$form['project_id'].'/3').'">Reactived</a>
			<!--<input type="submit"  id="approved"  value="Reactive" class="ff3 UILinkButton_A"/>-->
			<div class="UILinkButton_RW">
				<div class="UILinkButton_R"/></div>
			</div>
		</div>';
	}
}
 
?>

		






			</td>
		</tr>
	</table>		
<?php }  ?>
</form>



</td></tr>
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
	var totH = [], totOth = [], grandT = 0, grandTOth = 0,grandTAll=0,rate = 0, cost=0;
	totBH=0;	totBC=0;	totAH=0;	totAC=0;
	for(var i=0; i<7; i++) {
		 totH[i] = 0;
		$('input.sum'+i).each(function() {
			var val = $(this).val() ? $(this).val() : 0;
			totH[i] += parseFloat(val);
		});
		$("#totH"+i).html(totH[i]);
		$("#equD"+i).html(Math.ceil(totH[i]/8));
		rate = $('#rate'+i).val() ? parseFloat($('#rate'+i).val()) : 0;
		//alert(rate);
		$('#rate'+i).val(rate);
		//$('#rate'+i).val(addCommas(rate.toFixed(0)));

		cost = Math.ceil(totH[i]/8) * rate;
		//$("#cost"+i).html(Math.ceil(totH[i]/8) * rate);
		$("#cost"+i).html(addCommas(cost.toFixed(0)));
		grandT += Math.ceil(totH[i]/8) * rate;
	}


  	for(var j=0; j<4; j++) {
  		 totOth[j] = 0;
  		 //totBC = 0;
  		$('input.sum-oth'+j).each(function() {
  			var val = $(this).val() ? $(this).val() : 0;
  			totOth[j] += parseFloat(val);
    		if (j == 1){
  		    totBC = totOth[j];
    		}
  		});

  		$("#totOth"+j).html(totOth[j]);
    		if (j == 1){
      			  $('#subTotalBudgetHour').html(addCommas(totBC.toFixed(0)));
    		}
  	}


	
	//grandTOth = $('#subTotalBudgetHour').text() ? parseFloat($('#subTotalBudgetHour').text()) : 0;
  //if ( grandTOth > 0) {
    grandTAll = parseFloat(grandT + totBC );

	//$('#grandTotal').html(grandT);
		$('#grandTotal').html(addCommas(grandTAll.toFixed(0)));
	  $('#subTotal1').html(addCommas(grandT.toFixed(0)));
	  $('#subTotal11').html(addCommas(grandT.toFixed(0)));

	
};

function pcode() {
  var procode='';
  var client_id = $.trim($('#client_id').val());
  var ajaxUrlClient = '<?=$site?>/project/ajaxClientNo/';
  
  var client_no = '';   
  $.ajax({ type: "GET",   
         url: ajaxUrlClient +  client_id,   
         async: false,
         success : function(text)
         {
             client_no = text;
         }
  });


  var ajaxUrlJobType = '<?=$site ?>/project/ajaxJobTypeNo/';
  var jobtype_id = $.trim($('#jobtype_id').val());  
  var jobtype_no = '';   
  $.ajax({ type: "GET",   
         url: ajaxUrlJobType +  jobtype_id,   
         async: false,
         success : function(text)
         {
             jobtype_no = text;
         }
  });
  

   //var projcode1 = $.trim($('#contract_no').val()).split('-');
   //procode = projcode1[0]+'-'+response;
   procode = jobtype_no + "-" + client_no
   
   if($("#year_end").val() != 0) {
      var projcode2 = $.trim($('#year_end').val()).split('/');
   	//procode += projcode2[0]+''+projcode2[1]+''+ projcode2[2].substr(2,4);
   	procode += projcode2[0]+''+projcode2[1]+''+ projcode2[2];
   }	

	$('#project_no').val(procode);

}

$(document).ready(function(){
 	$('#project_no').focus();
	$('#back').click( function (e) {
		window.location.href ='<?=$back?>';
	});
	$('#approve').click( function (e) {
		window.location.href = '<?=$approve?>';
	});
	$('input.date').datepick({dateFormat:'dd/mm/yy'});
	pcalc();
	for(var i=0; i<7; i++) {
		$('input.sum'+i).blur(function() {pcalc();});
		$('#rate'+i).blur(function() {pcalc();});
	}
	
	for(var j=0; j<4; j++) {
		$('input.sum-oth'+j).blur(function() {pcalc();});
	}

	$('#contract_no').blur( function (e) { pcode();	});
  $('#client_id').change( function (e) { pcode();	}); 	
  $('#jobtype_id').change( function (e) { pcode();	});   
	$('#year_end').change( function (e) { pcode();	}); 
	
	$('#project_no').focus( function (e) { pcode();	});

});

$(function() {
	$('#form_project').submit(function(event) {
		//event.preventDefault();
		//event.stopPropagation();

		var _post = [{name:'ts', value:new Date().getTime()}], _err  = 0;

		$('input, select, textarea', $('#form_project')).each(function () {
			var _val = $(this).val();
			if($(this).hasClass('mandatory') && ! _val) _err=1;
			_post.push({name:this.id, value:_val});
		});

		if(_err) {
			$('#msg').html('Required Fields must be fill out');
			return false;
		}
		else {
         return true;
/*
			$.post('<?=$site?>/project/Update/', _post, function(resp) {
				$("#msg").html('SAVED');
				$("button", $("#form_project")).remove();
			});
*/
		}
	});
});
</script>

<?php
$this->load->view('site_footer');
?>