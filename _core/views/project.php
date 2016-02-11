<?php
$this->load->view('site_header');
?>
			<div class="grid_12">
				<h2 id="page-heading">Project</h2>
			</div>
			<div class="grid_12">
			<div class="box">
				<h2><a id="toggle-forms" href="#" class="hidden">Data Filter</a></h2>

				<div id="forms" class="block" style="display: none;">
					<form id="form" class="form-container"  method="POST" action="<?=$site ?>/project/index/2" >
						<fieldset class="entry">
						<legend>Data Filter</legend>

						<table align=center>
						<tr>
							<td class="label-login">Client Name : </td>
							<td><input type="text"  class="inputtext" id="client_name" name="client_name" value="" size="40" /></td>
						</tr>
						<tr>
							<td class="label-login">Project Code : </td>
							<td><input type="text"  class="inputtext" id="project_no" name="project_no" value="" size="100"  /></td>
						</tr>
						<tr>
							<td class="label-login">Project Name : </td>
							<td><input type="text" class="inputtext" id="project" name="project" value="" size="20" /></td>
						</td>

						<tr>
							<td></td>
							<td><div class="ff3 UILinkButton">
									<input type="submit"  id="submit"  value="Search" class="ff3 UILinkButton_A"/><div class="UILinkButton_RW"><div class="UILinkButton_R"/></div>
			 					</div>
							</td>
						</table>
						</fieldset>
					</form>
				</div> <!-- END FORMS -->
			</div> <!-- END BOX -->

<?php 
if ( $this->session->userdata('acl') === "01" || $this->session->userdata('acl') === "02" || $this->session->userdata('acl') === "03" || $this->session->userdata('acl') == "008" ) {
?>				
<table class="grid">
<thead>
<tr>
<?php 
$cMode="";
if ($this->session->userdata('acl')==='01') {
   echo "<th class='table-head' colspan='13'>Project to be Approve</th>";
   $cMode="Approve";
} elseif ($this->session->userdata('acl')==='02'){ // 04-2010 - rahmat - GC get Approve feature
   echo "<th class='table-head' colspan='13'>Project to be Approve</th>";
   $cMode="Approve";
} elseif ($this->session->userdata('acl')==='03'){
   echo "<th class='table-head' colspan='13'>Project Waiting for Review</th>";  
   $cMode="View";
}
?>
							<tr>
								<th>No</th>
								<th>Project Name</th>
								<th>Project Code</th>
								<th>Client Name</th>
								<th>Project Status</th>
								<th>Year End</th>
								<th>Budget Hour</th>
								<th>Actual Hour</th>
								<th>Budget Cost</th>
								<th>Actual Cost</th>
								<th><?php echo $cMode ;?></th>
							</tr>
						</thead>
						<tbody>
	<?php
	/** pacth update PIC/GC to Edit **/
	if(($this->session->userdata('acl')==='01') || ($this->session->userdata('acl')==='02'))
		$link_view = 'ViewPartner';
	else
		$link_view = 'View';
				
	if ( $review ) {
		$i = 1;
		foreach ($review as $k=>$v) {
			$class= '';
			
			if ( $i % 2 == 0) $class= 'class="odd"';
			
			$link = "<a href='$site/project/Review/$v[project_id]'>[ review ]</a>";
			
			$year_end ='';
			if ( strlen( $v['year_end']) >0 ) {
				$year_end = date("d M Y",strtotime($v['year_end'])) ;
				if ($v['year_end']=='0000-00-00' || $v['year_end']=='1970-01-01')  $year_end = '';
				
			}
			
			$start ='';
			if ( strlen( $v['start_date']) >0 ) {
				$start = date("d M Y",strtotime($v['start_date'])) ;
				if ($v['start_date']=='0000-00-00' || $v['start_date']=='1970-01-01')  $start = '';
				
			}
			
			$finish ='';
			if ( strlen( $v['finish_date']) >0 ) {
				$finish = date("d M Y",strtotime($v['finish_date'])) ;
				if ($v['finish_date']=='0000-00-00' || $v['finish_date']=='1970-01-01')  $finish = '';
			}

			$status = '';
			$link = "<a href='$site/project/Edit/$v[project_id]'>[ edit ]</a>";
			
			
			
			if ($v['project_approval']==1){
				$status = 'Waiting for Review';	
			}
			elseif ($v['project_approval']==2){
				$status = 'Reviewed';	
				$link = "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
			}
			elseif ($v['project_approval']==3){
				$status = 'Approved';	
				$link = "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
			}
			elseif ($v['project_approval']==4){
				$status = 'Closed';	
				$link = "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
			}
			
			
			if ($v['project_approval']==1){
				$link = "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
			}
			
			echo "<tr $class >
					<td>";
			echo $i;
			echo "</td>
					<td>$v[project]</td>
					<td>$v[project_no]</td>
					<td>$v[client_name]</td>
					<td>$status</td>
					<td nowrap>". $year_end ."</td>
					<td align=right class=currency style='padding-right:30px;'>$v[budget_hour]</td>
					<td align=right class=currency style='padding-right:30px;'>$v[hour]</td>
					<td align=right class=currency style='padding-right:30px;'>".number_format($v['budget_cost'],2)."</td>
					<td align=right class=currency style='padding-right:30px;'>".number_format($v['cost'],2)."</td>
					<td align='right' nowrap>$link</td></tr>";
			$i++;
		}
	} 
?>
						</tbody>
	</table>
<br>
<?php } ?>
			<div class="box">
				<h2><a id="toggle-tables" href="#">Project List </a></h2>
				<div id="tables" class="block">
					<div id="paging">

						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=$site?>/project/index/3/1" />First</a>
						<a href="<?=$site?>/project/index/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/project/index/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/project/index/3/<?=$pg['l']?>" />Last</a>
						<span style="float:right;text-align:right;"><a href="<?=$site?>/project/Edit/0" > [ ADD NEW ] </a></span>
						<span style="float:right;text-align:right;"><a href="<?=$site?>/project/Refresh/0" > [ REFRESH ] </a></span>

					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
<!--								<th>Project Name</th> -->
								<th>Project Code</th>
								<th>Client Name</th>
								<th>Project Status</th>
								<th>Year End</th>
								<th class=currency style='padding-right:30px;'>Budget Hour</th>
								<th class=currency style='padding-right:30px;'>Actual Hour</th>
								<th class=currency style='padding-right:30px;'>Budget Cost</th>
								<th class=currency style='padding-right:30px;'------>Actual Cost</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
<?php
if ( $table ) {
	$i = 1;
	$budget_hour = 0;
	$hour 		 = 0;
	$budget_cost = 0;
	$cost 		 = 0;
	foreach ($table as $k=>$v) {
		$budget_hour += $v['budget_hour'];
		$hour 		 += $v['hour'];
		$budget_cost += $v['budget_cost'];
		$cost 		 += $v['cost'];

		$class = '';
		
		if ($i % 2 == 0) $class= 'class="odd"';
		
		$status = '';
		$link = "<a href='$site/project/Edit/$v[project_id]'>[ edit ]</a>";
		if ($v['project_approval']==1){
			$status 	= 'Waiting for Review';	
		}
		elseif ($v['project_approval']==2){
			$status 	= 'Reviewed';	
			$link 	= "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
		}
		elseif ($v['project_approval']==3){
			$status 	= 'Approved';	
			$link 	= "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
		}
		elseif ($v['project_approval']==4){
			$status 	= 'Closed';	
			$link 	= "<a href='$site/project/$link_view/$v[project_id]'>[ view ]</a>";
		}

		$year_end = '';
		if ( strlen( $v['year_end']) >0 ) {
			$year_end = date("d M Y",strtotime($v['year_end'])) ;
			if ($v['year_end']=='0000-00-00' || $v['year_end']=='1970-01-01')  $year_end = '';
		}
		
		$start = '';
		if ( strlen( $v['start_date']) >0 ) {
			$start = date("d M Y",strtotime($v['start_date'])) ;
			if ($v['start_date']=='0000-00-00' || $v['start_date']=='1970-01-01')  $start = '';
		}
		
		$finish ='';
		if ( strlen( $v['finish_date']) >0 ) {
			$finish = date("d M Y",strtotime($v['finish_date'])) ;
			if ($v['finish_date']=='0000-00-00' || $v['finish_date']=='1970-01-01')  $finish = '';
		}
			echo "<tr $class >
					<td>";
			echo $i + $pg['o'];
			echo "</td>
<!--
					<td>$v[project]</td> -->
					<td>$v[project_no]</td>
					<td>$v[client_name]</td>
					<td>$status</td>
					<td nowrap>". $year_end ."</td>
					<td class=currency style='padding-right:30px;'>$v[budget_hour]</td>
					<td class=currency style='padding-right:30px;'>$v[hour]</td>
					<td class=currency style='padding-right:30px;'>".number_format($v['budget_cost'],2)."</td>
					<td class=currency style='padding-right:30px;'>".number_format($v['cost'],2)."</td>
					<td align=right nowrap>$link</td></tr>";
			$i++;
	}
}

if (!isset($budget_hour)){
	$budget_hour = 0;
	$hour			 = 0;
	$budget_cost = 0;
	$cost = 0;
}

?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4"></td>
					<th>Total</th>
					<th class=currency style='padding-right:30px;'><?=$budget_hour ?></th>
					<th class=currency style='padding-right:30px;'><?=$hour?></th>
					<th class=currency style='padding-right:30px;'><?=number_format($budget_cost,2)?></th>
					<th class=currency style='padding-right:30px;'><?=number_format($cost,2)?></th>
					<td colspan="2"></td>
				</tr>
			</tfoot>
		</table>
		<div id="paging">
			<a href="<?=$site?>/project/index/3/1" />First</a>
			<a href="<?=$site?>/project/index/3/<?=$pg['p']?>" />Prev</a>
			<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
			<a href="<?=$site?>/project/index/3/<?=$pg['n']?>" />Next</a>
			<a href="<?=$site?>/project/index/3/<?=$pg['l']?>" />Last</a>
		</div>
		</div>
	</div>
</div>
<?php
$this->load->view('site_footer');
?>