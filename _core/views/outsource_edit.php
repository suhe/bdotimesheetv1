<?php
$this->load->view('site_header');

$employee_data = $this->dataModel->getEmployeeDepartment();
$employee = '';
if ( count( $employee_data ) > 0 ) {
	$employee .='<select name=department_id id=department_id> ';
	foreach ($employee_data as $k=>$v) {
		if($v["department_id"] == 777) {
			$selected = '';
			if ( $v['department_id'] == $form['department_id'] ) {
				$selected = ' selected ';
			} 
			$employee .= '<option value='.$v['department_id'] . $selected .'>'. $v['department'] .'</option>';
		}
	}
	$employee .= '</selected>';

}	

$position_data = $this->dataModel->getProjectTitle();

$position = '';
if ( count( $position_data ) > 0 ) {
	$position .='<select name=project_title id=project_title>';
	foreach ($position_data as $k=>$v) {
		if($v['project_title'] == '777') {
			$selected = '';
			if ( $v['project_title'] == $form['project_title'] ) {
				$selected = ' selected ';
			} 
			$position  .= '<option value='.$v['project_title'] . $selected .'>'. $v['project_title_label'] .'</option>';
		}
	}
	$position .= '</selected>';
}	

$approval_data = $this->dataModel->getUserEmployee(array('department_id'=>7));

$approval = '';
if ( count( $approval_data ) > 0 ) {
	$approval .='<select name=approval_id id=approval_id>';
	$approval  .= "<option value=''>Please Choose</option>";
	
	foreach ($approval_data as $k=>$v) {
		$selected = '';
		if ( $v['employee_id'] == $form['approval_id'] ) {
			$selected = ' selected ';
		} 
		
		$approval  .= '<option value='.$v['employee_id'] . $selected .'>'. $v['employeefirstname'] .' '. $v['employeemiddlename'] .' '. $v['employeelastname'] .'</option>';
	}
	$approval .= '</selected>';
}	

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form" method="POST" action="<?=$site ?>data/outsourceUpdate/" >
		<input type="hidden" id="employee_id" name="employee_id" value="<?=$form['employee_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Employee  Information</legend>
		<table align=center>
		<tr>
			<td colspan=2 align=left class="label-message" id="msg"><?=$form['message'] ?></td>
		</td>

		<tr>
			<td class="label">N.I.K: </td>
			<td><input type="text"  class="inputtext mandatory" maxlength="5" id="employeeid"  name="employeeid" value="<?=$form['employeeid'] ?>" style="width:100px" /> *Required</td>
		</td>
		
		<?php if($form['employee_id'] == 0) { ?>
		<tr>
			<td class="label">Password: </td>
			<td><input type="password"  class="inputtext mandatory" maxlength="5" id="passtext"  name="passtext" value="" style="width:150px" /> *Required</td>
		</td>
		<?php } ?>
        
        <tr>
			<td class="label">Hire Date: </td>
			<td><input type="text"  class="inputtext date"  id="hiredate" name="hiredate" value="<?=$form['employeehiredate']?>" size="60" style='width:75px;' />  Required</td>
		</td>
        
        <tr>
			<td class="label">Status: </td>
			<td><?=form_dropdown('status',[2=>'Outsource'],$form['employeestatus']);?> Required</td>
		</td>
		
		<tr>
			<td class="label">Active: </td>
			<td><?=form_dropdown('user_active',[1=>'Active',2=>'Inactive'],$form['user_active']);?> Required</td>
		</td>

		<tr>
			<td class="label">First Name : </td>
			<td><input type="text"  class="inputtext mandatory" id="employeefirstname"  name="employeefirstname" value="<?=$form['employeefirstname'] ?>" size="40" />  Required</td>
		</td>

		<tr>
			<td class="label">Middle Name : </td>
			<td><input type="text"  class="inputtext" id="employeemiddlename" name="employeemiddlename" value="<?=$form['employeemiddlename'] ?>" size="40" /></td>
		</td>

		<tr>
			<td class="label">Last Name : </td>
			<td><input type="text"  class="inputtext" id="employeelastname" name="employeelastname" value="<?=$form['employeelastname'] ?>" size="40" /></td>
		</td>

		<tr>
			<td class="label">Nick Name : </td>
			<td><input type="text"  class="inputtext" id="employeenickname" name="employeenickname" value="<?=$form['employeenickname'] ?>" size="40" /></td>
		</td>

		<tr>
			<td class="label">Title: </td>
			<td>
            <?=form_dropdown('employeetitle',config_item('employeetitle'),$form['employeetitle'],'id="employeetitle"');?>
            <!--<input type="text"  class="inputtext" id="employeetitle" name="employeetitle" value="<?=$form['employeetitle'] ?>" size="40" />-->
            </td>
		</td>

		<tr>
			<td class="label">Email Address: </td>
			<td><input type="text"  class="inputtext" id="employeeemail"  name="employeeemail" value="<?=$form['employeeemail'] ?>" size="40" /></td>
		</td>
		<tr>
			<td class="label">Position : </td>
			<td><?=$position ?> </td>
		</td>

		<tr>
			<td class="label">Group : </td>
			<td><?=$employee ?> </td>
		</td>

		<tr>
			<td class="label">Approval: </td>
			<td><?=$approval ?> </td>
		</td>

		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
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
</div>

<script>
/*
// When the page is ready
$(document).ready(function(){
 	$('#client_no').focus();
	}
);	

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

});

$(function() {
	$('#form').submit(function(event) {
		event.preventDefault();
		event.stopPropagation();

		var _post = [{name:'ts', value:new Date().getTime()}], _err  = 0;

		$('input, select, textarea', $('#form')).each(function () {
			var _val = $(this).val();
			if($(this).hasClass('mandatory') && ! _val) _err=1;
			_post.push({name:this.id, value:_val});
		});

		if(_err) {
			$('#msg').html('Required fields must be fill out');
			return false;
		}
		else {
			$.post('<?=$site?>/data/employeeUpdate/', _post, function(resp) {
				$("#msg").html('SAVED');
				$("button", $("#form")).remove();
			});
		}
	});
    
    $('input.date').datepick({dateFormat:'dd/mm/yy', showWeeks:true,firstDay: 1, minDate:new Date(2008,1,1)});
    
});*/
</script>
<?php
	$this->load->view('site_footer');
?>