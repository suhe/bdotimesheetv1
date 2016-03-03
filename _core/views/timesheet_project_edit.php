<?php
$this->load->view('site_header');

$cJob ='<select id="job_id" name=job_id><option value="">- Please Choose -</option>';
foreach ($job as $k=>$v) {
	$selected = '';
	if ( $v['job_id'] == $form['job_id'] ) {
		$selected = ' selected ';
	} 
	$cJob .= '<option value='.$v['job_id']. $selected .'> '.$v['job_no'].' - '. $v['job'] .'</option>';
}
$cJob .= '</selected>';

$cProject ='<select id=project_id name=project_id>';
foreach ($projectlist as $k=>$v) {
	$selected = '';
	if ( $v['project_id'] == $form['project_id'] ) {
		$selected = ' selected ';
	} 
	$cProject .= '<option value='.$v['project_id']. $selected .'> '.$v['project_no'].'</option>';
}
$cProject .= '</selected>';


$cTranport ='<select id="transport_type" name="transport_type">';
$cTranport .= "<option value='00'>- Please Choose -</option>";

foreach ($tranport as $k=>$v) {
	$selected = '';
	if ( $v['lookup_code'] == $form['transport_type'] ) {
		$selected = ' selected ';
	} 
	$cTranport .= '<option value='.$v['lookup_code']. $selected .'> '.$v['lookup_label'].'</option>';
}
$cTranport .= '</selected>';


$xtimesheetdate = now();
if ( strlen( $form['timesheetdate']) >0 ) {
	$xtimesheetdate = date("d/m/Y",strtotime($form['timesheetdate'])) ;
}
?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;width:50%;">
	
	<form id="form" method="POST" action="<?=$site ?>timesheet/Update/" onsubmit="return:false;">
		
		<input type="hidden" id="id" name="id" value="<?=$form['timesheetid'] ?>"  />
		<fieldset class="form-fieldset">
		<!--<legend class="form-legend">Timesheet Posting</legend>
		<span style="color:red">! Tgl 16,20,21 tidak perlu diisi lagi karena sudah automatic sync dengan Cuti Online, apabila salah satu tanggal tersebut anda melakukan Pekerjaan harap Hubungi HRD & Management.</span>-->
		<table align=left width="100%;">
		<tr>
			<td colspan=2 align=left class="label-message"><?=$form['message'] ?></td>
		</td>
		<tr>
			<td class="label">Date : </td>
			<td><input type="text"  class="inputtext date" readonly="true" id="timesheetdate" name="timesheetdate" value="<?=$xtimesheetdate?>" size="60" style='width:75px;' /></td>
		</tr>
		<tr>
			<td class="label">Week / Year: </td>
			<td>
			<input type="text" class="inputtext" readonly="true" id="week" name="week" value="<?=$form['week'] ?>" size="40" style='width:20px;'/>
			<input type="text" class="inputtext" readonly="true" id="year" name="year" value="<?=$form['year'] ?>" size="40" style='width:40px;'/>
			</td>
		</td>
		<tr>
			<td class="label">Project : </td>
			<td><?=$cProject ?></td>
		</tr>

		<tr>
			<td class="label">Job : </td>
			<td><?=$cJob ?></td>
		</tr>
		<tr class="client hide">
			<td class="label" nowrap>Client Name : </td>
			<td><input type="text"  class="inputtext" id="client_name_description" name="client_name_description" value="<?=$form['client_name_description']?>" size="40" style='width:250px;'/></td>
		</tr>
		<tr>
			<td class="label" nowrap>Actual Hour : </td>
			<td><input type="text" maxlength="2"  class="inputtext" id="hour" name="hour" value="<?=$form['hour'] ?>" size="40" style='width:75px;'/> * Including Overtime</td>
		</tr>
		<tr>
			<td class="label" nowrap>Overtime : </td>
			<td><input type="text"  maxlength="2"  class="inputtext" id="overtime" name="overtime" value="<?=$form['overtime'] ?>" size="40" style='width:75px;'/></td>
		</tr>
        
        <tr>
			<td class="label" nowrap>Transport Type: </td>
			<td><?=$cTranport ?></td>
		</tr>
        
		<tr>
			<td class="label" nowrap>Actual Cost: </td>
			<td><input type="text"  maxlength="8"  class="inputtext" id="cost" name="cost" value="<?=$form['cost'] ?>" size="40" style='width:75px;'/>
              * Only for Office (Prudential Tower) & In Town Client (Jabodetabek Area)
            </td>
		</tr>
		
		<tr>
			<!--<td class="label" nowrap>Transport Cost: </td>
			<td><input type="text"  class="inputtext" id="transport_cost" name="transport_cost" value="<?=$form['transport_cost'] ?>" size="40" style='width:75px;'/></td>-->

		</tr>
		<tr>
			<td class="label" valign="top" style="vertical-align:top;">Expenses Notes: </td>
			<td><textarea style='width:275px;height:50px;' class="inputtext" id="notes" name="notes"><?=$form['notes'] ?></textarea> </td>
		</tr>
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton" id="div-submit">
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
		</table>	
		</fieldset>
	</form>	
	<td valign="top" style="vertical-align:top;width:50%;">
<!-- Project Info -->	
		<fieldset class="form-fieldset">
		<legend class="form-legend">Project Info</legend>
		<table align=left width="100%;">
			<tbody id="info_x">
				<tr>
					<td class="label">Project Number : </td>
					<td><?=$project['project_no']?></td>
				</td>
				<tr>
					<td class="label">Project Name : </td>
					<td><?=$project['project']?></td>
				</tr>
				<tr>
					<td class="label">Client : </td>
					<td><?=$project['client_no']?> / <?=$project['client_name']?></td>
				</td>			
				<tr>
					<td class="label">Year End: </td>
					<td><?=$project['year_end']?></td>
				</tr>
				<tr>
					<td class="label">Start Date: </td>
					<td><?=$project['start_date']?></td>
				</tr>
				<tr>
					<td class="label">Finish Date: </td>
					<td><?=$project['finish_date']?></td>
				</tr>
				<tr>
					<td class="label">Budget Hour: </td>
					<td><?=number_format($project['budget_hour'])?></td>
				</tr>
				<tr>
					<td class="label">Actual Hour: </td>
					<td><?=number_format($project['hour'])?></td>
				</tr>
				<tr>
					<td class="label">Budget Cost: </td>
					<td><?=number_format($project['budget_cost'])?></td>
				</tr>
				<tr>
					<td class="label">Actual Cost: </td>
					<td><?=number_format($project['cost'])?></td>
				</tr>
			</tbody>
		</table>	
		</fieldset>
	</td>
</table>

<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>timesheet/getSearchPosting/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Timesheet for Project :: <?=$project['project_no']?></legend>
		<table align=center>
		<tr><td>&nbsp;</tr>
		
		
		<tr>
			<td class="label">Periode : </td>
			<td>
			  <input type="text"  class="inputtext date" readonly="true" id="date_from" name="date_from" value="<?=$date_start?>" size="60" style='width:75px;' />
			   - 
			   <input type="text"  class="inputtext date" readonly="true" id="date_to" name="date_to" value="<?=$date_end?>" size="60" style='width:75px;' />
			  
			</td>
		</tr>
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton" >
					<input type="submit"  id="submit"  value="View" class="ff3 UILinkButton_A"/>
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
		</table>	
		</fieldset>
	</form>	
</table>

<div id="tables" class="block">
<table class="grid">
<thead>

		<tr>
			<th>No</th>
			<th>Date</th>
			<th>Week|Year</th>
			<th>Job </th>
			<th class='currency'>Actual Hour</th>
			<th class='currency'>Overtime</th>
			<th class='currency'>Actual Cost</th>
			<th class='currency'>Trasport Type</th>
			<!--<th class='currency'>Trasport Cost</th>-->
			<th>Notes</th>
			<th>Status</th>
			<th>Created On</th>
			<th></th>
		</tr>
	</thead>
	<tbody id='timesheet-body'>
<?php
$hour = 0;
$overtime = 0;
$cost = 0;
$transport = 0;
if ( count( $table) > 0 ) {
	$i = 1;
	foreach ($table as $k=>$v) {
		$class = '';
		$hour += $v['hour'];
		$overtime += $v['overtime'];
		$cost += $v['cost'];
		$transport += $v['transport_cost'];
		
		if ( $i % 2 == 0) $class= 'class="odd"';
	
		$status = '';
		if ($v['timesheet_approval'] == '1' ){
			$status = 'Waiting Approval';
		}
		if ($v['timesheet_approval'] == '2' ){
			$status = 'Approved';
			$link ="";
		}

		if ($v['timesheet_approval'] == '3' ){
			$status = 'Returned';
			$link ="";
		}
		
		if ($v['timesheet_approval'] == '4' ){
			$status = 'Leave';
			$link ="";
		}
		
		$timesheetdate ='';
		if ( strlen( $v['timesheetdate']) >0 ) {
			$timesheetdate = date("d/m/Y",strtotime($v['timesheetdate'])) ;
		}
		echo "<tr $class>
					<td>$i</td>
					<td nowrap>$timesheetdate</td>
					<td>$v[week] | $v[year]</td>
					<td>$v[job]</td>
					<td class='currency'>".number_format($v['hour'],2)."</td>
					<td class='currency'>".number_format($v['overtime'],2)."</td>
					<td class='currency'>".number_format($v['cost'])."</td>
					<td class='currency' nowrap>".$v['transport_type']."</td>
					<!--<td class='currency'>".number_format($v['transport_cost'])."</td>-->

					<td>$v[notes]</td>
					<td>$status</td>
					<td>$v[sysdate]</td>;
					";
					
		if ($status=='') {
			$link = "<a href='$site/timesheet/projectEdit/$v[id]/".$form['project_id']."/0/0'>[ Edit ]</a>";
			//$del = "<a href='$site/timesheet/projectDel/$v[id]/".$form['project_id']."/".$form['timesheetid']."' class='hapus'>[ Del ]</a>";
			$del = "<a id='".$v['id']."'  class='hapus' style='cursor:pointer;'>[ Del ]</a>";
			echo "<td class='currency' nowrap>$link - $del</td>";
		}
		else
		{
			echo "<td class='currency' nowrap></td>";
		}
		echo "</tr>";
		$i++;
	}
}
?>
		</tbody>
		<tfoot id='timesheet-foot'>
			<tr>
				<td colspan="3"></td>
				<th>Total</th>
				<th class='currency'><?=number_format($hour,2)?></th>
				<th class='currency'><?=number_format($overtime,2)?></th>
				<th class='currency'><?=number_format($cost)?></th>
				<th class='currency'>&nbsp;</th>
				<!--<th class='currency'><?=number_format($transport)?></th>-->
				<td colspan="4"></td>
			</tr>
		</tfoot>
		
	</table>
</div>
<style>
	.hide{
		display:none;
	}
</style>
<script>
function getWeek(value, xdate, inst) {
	nWeek = $.datepick.iso8601Week(xdate);
	nYear = new Date(xdate).getFullYear();
	nMonth = value.substring(3,5); 
	if((nMonth=='12') && (nWeek=='1')){
		nYear=nYear+1;
	}
	
	$("#week").val(nWeek);
	$("#year").val(nYear);
}

$(function () {
	/** Update Timesheet
	* Disabled date when leave is fill
	**/
	var timesheetdate = $('#timesheetdate').val();
	isLeave(timesheetdate);
	
	function isLeave(args) {
		var arg = args;
		$.ajax({
			type:'POST',
			data:{timesheetdate:arg},
			dataType : 'json',
			url:'<?=base_url()?>timesheet/isLeave',
			success:function(response) {
				if(response.allow == false) {
					alert("Date of " + arg + ", is leave, " + response.message);
					$('#timesheetdate').focus();
					$('#div-submit').hide("slow");
				} else {
					$('#div-submit').show("slow");
				}
			}
		});
	}
	
	$('#timesheetdate').change( function (e) {
		e.preventDefault();
		var timesheetdate = $(this).val();
		isLeave(timesheetdate);
	});
	
	$('a.hapus').click( function (e) {
		retval = window.confirm('Are you sure delete this record ?');
		//alert('deleted flag ' + retval + ';value' + this.id);
		var timesheetid = this.id;
		var projectid = $("#project_id").val();
		//return retval;
				var post = [{name:'ts', value: new Date().getTime()},
						{name:'timesheet_id', value:timesheetid}];
		$.post('<?=$site ?>/timesheet/TimesheetDelete/'+timesheetid+'/'+projectid,post,function(response){
			//alert(response);
			var timesheetDetail = response.split('|');
			$('#timesheet-body').html(timesheetDetail[0]);
			$('#timesheet-foot').html(timesheetDetail[1]);

			//$('#timesheet-detail').html(response);

			//var projx = response.split('|');
			//$('#job_id').html(projx[0]);
			//$('#info_x').html(projx[1]);
		})

		
	});
	$('#back').click( function (e) {
		window.location.href ='<?=$back?>';
	});
	$('input.date').datepick({dateFormat:'dd/mm/yy', showWeeks:true, onClose: getWeek, firstDay: 1, minDate:new Date(2008,1,1)});
 	$('#hour').focus();
	$('#project_id').change(function() {
		var post = [{name:'ts', value: new Date().getTime()},
						{name:'project_id', value:$('#project_id').val()}];
		$.post('<?=base_url() ?>timesheet/getJob/',post,function(response){
			var projx = response.split('|');
			$('#job_id').html(projx[0]);
			$('#info_x').html(projx[1]);
		})
	});
	

	$('#submit').click( function (e) {
		var timesheetdate = $.trim($('#timesheetdate').val()); 
		var project_id = $.trim($('#project_id').val()); 
		var job_id = $.trim( $('#job_id').val());
		var client_name = $.trim( $('#client_name_description').val());
	    var hour = $.trim( $('#hour').val());
		var overtime = $.trim( $('#overtime').val()); //ram
		var transport_type = $.trim( $('#transport_type').val()); //ram
        var cost = $.trim( $('#cost').val());
		var errSubmit = '';
		
		
		if (project_id.length == 0) {
			$('#project_id').focus();
			errSubmit += 'Project must be fill out \n';	
		}
        
        if ((transport_type==3) && (cost>0)  ) {
			$('#cost').focus();
			errSubmit += 'If you fill transport OUT OF TOWN client you DO NOT fill Transport Cost! , Okay';	
		}

		if (job_id.length == 0) {
			$('#job_id').focus();
			errSubmit += 'Job must be fill out \n';	
		} 

		if (job_id == 470 && client_name.length == 0 ) {
			$('#client_name_description').focus();
			errSubmit += 'Client Name be fill out \n';	
		} 

		if (hour.length == 0) {
			$('#hour').focus();
			errSubmit += 'Actual Hour must be fill out \n';	
		} 

		if (overtime.length == 0) {
			$('#overtime').focus();
			errSubmit += 'Overtime must be fill out \n';	
		} 
		
		if (transport_type.length == 2) {
			$('#transport_type').focus();
			errSubmit += 'Transport Type must be fill out \n';	
		} 
		
		if (errSubmit) {
			alert(errSubmit);
			return false;
		} else {
			return true;
			//$('#form').submit(); 
		}			
	});

	$('#job_id').change( function (e) {
		e.preventDefault();
		var job_id = $(this).val();
		if(job_id == 470){
			//$(".hide").show("slow");
			$(".client").removeClass("hide");
		}else {
			//$(".hide").hide("slow");
			$(".client").addClass("hide");
		}
	});
     
     $('#transport_type').click( function (e) {
          var tt = $.trim( $('#transport_type').val());
          var cost = $.trim( $('#cost').val()); 
          if (tt==3) {
            $("#cost").attr('value','0');    
            $("#cost").attr('readonly','readonly');    
          } else {
            $("#cost").attr('readonly','');
          }   
    });  
});
</script>
<?php
	$this->load->view('site_footer');
?>