<?php
$this->load->view('site_header');?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form_project" method="POST" action="<?=$site ?>/timesheet/allowence_update/" >
		<input type="hidden" id="id" name="id" value="<?=isset($form['id']) ? $form['id']:null ?>"  />
		<fieldset class="form-fieldset">
		<legend class="form-legend">Allowance Information</legend>
	<table>
	<tr>
		<td colspan=2 align=left style="text-align:left;" class="label-message" id="msg"><?=$form['message'] ?></td>
	</td>
	<tr>
	<td>
	<table style="vertical-align:top;">
		<tr>
			<td class="label">Client Name   : </td>
			<td><?=form_dropdown('client_id', $client_lists,isset($form['client_id']) ? $form['client_id']:0 )?></td>
		
		<tr>
			<td class="label">Project No : </td>
			<td><?=form_dropdown('project_id', [],isset($form['client_id']) ? $form['client_id']:0 )?></td>
		</td>
		<tr>
			<td class="label">Approval Project : </td>
			<td><?=form_dropdown('approval_id',[],isset($form['client_id']) ? $form['client_id']:0 )?></td>
		</td>
		<tr>
			<td class="label">Start Date : </td>
			<td><input type="text"  class="inputtext date" readonly="true" id="start_date" name="start_date" value="<?=isset($form['date_from']) ? $form['date_from'] : ""?>" size="40" style="width:80px;" style="width:80px;"/>
				 &nbsp;&nbsp; Finish Date :  &nbsp;&nbsp;	
				<input type="text"  class="inputtext date" readonly="true" id="finish_date" name="finish_date" value="<?=isset($form['date_to']) ? $form['date_to'] : ""?>" size="40" style="width:80px;" style="width:80px;"/>
			</td>
		</tr>
		<tr>
			<td class="label">Number of Employee : </td>
			<td><input type="text" class="inputtext mandatory" id="employee_total" name="employee_total"  value="<?=isset($form['employee_total']) ? $form['employee_total'] : ""?>" size="40" /> *</td>
		</td>
		<tr>
			<td class="label">Total Allowance : </td>
			<td><input type="text" class="inputtext mandatory currency" id="total_allowance" name="total"  value="<?=isset($form['total']) ? $form['total'] : 0?>" size="18" /> *</td>
		</td>
		<tr>
			<td class="label" nowrap style="width:180px;">Date Realization : </td>
			<td><input type="text"  class="inputtext date mandatory" readonly="true" id="date_realization" name="date_realization" value="<?=isset($form['date_realization']) ? $form['date_realization'] : ""?>" size="40" style="width:80px;"/> *</td>
		</tr>
		<tr>
			<td class="label" nowrap style="width:180px;">Date Approved : </td>
			<td><input type="text"  class="inputtext date mandatory" readonly="true" id="date_approved" name="date_approved" value="<?=isset($form['date_approved']) ? $form['date_approved'] : ""?>" size="40" style="width:80px;"/> *</td>
		</tr>
		
	</table>
</tr>
<tr>
	<td colspan=2 align="center" style="text-align:center;">
		<div style="margin-left:30%" class="ff3 UILinkButton" >
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
</tr>	
</fieldset>
</form>
</table>	
		
<script>
$(function() {
	var client_id = $("select[name='client_id']").val();
	//alert(client_id);
	loadProject(client_id);
	
	function loadProject(args) {
		$.getJSON("<?=base_url()?>timesheet/loadclientproject",
			{ client_id: args },
			function(data) {
				var model = $('select[name="project_id"]');
				model.empty();
				$.each(data, function(key,value) {
					model.append("<option value='"+ value.project_id +"'>" + value.project_no + "</option>");
				});
					
			});
		
	}

	var project_id = $("select[name='project_id']").val();
	loadApprovalProject(project_id);
	function loadApprovalProject(args) {
		$.getJSON("<?=base_url()?>timesheet/loadapprovalproject",
			{ project_id: args },
			function(data) {
				var model = $('select[name="approval_id"]');
				model.empty();
				$.each(data, function(key,value) {
					model.append("<option value='"+ value.employee_id +"'>" + value.approval_name + "</option>");
				});
					
			});
		
	}

	$("select[name='client_id']").change( function (e) {
		var id = $(this).val();
		loadApprovalProject(id);
	});

	$("select[name='project_id']").change( function (e) {
		var id = $(this).val();
		loadProject(id);
	});

	

	
});


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
  var ajaxUrlClient = '<?=$site ?>/project/ajaxClientNo/';
  
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
		window.location.href ='<?=base_url()?>timesheet/allowance';
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