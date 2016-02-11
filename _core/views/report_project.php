<?php
$this->load->view('site_header');

$form['client_no']   = "";
$client_data = $this->reportModel->getClient();
$client = '';
if ( count( $client_data ) > 0 ) {
	$client .='<select id=client_id name=client_id>';
	$client .= '<option value="">Please Choose</option>';
	foreach ($client_data as $k=>$v) {
		$selected = '';
		if ( $v['client_id'] == $form['client_id'] ) $selected = ' selected ';

		$client .= '<option value='.$v['client_id'] . $selected .'>'. $v['client_name'] .'</option>';
	}
	$client .= '</selected>';
}	


$project ='<select id=project_id name=project_id>';

if (strlen($form['client_id']) > 0 ) {
	$project_data = $this->reportModel->getReportProject($form['client_id']);
	//print_r( $project_data );
	if ( count( $project_data ) > 0 ) {
	 $project .= '<option value="">Please Choose</option>';
	
		foreach ($project_data as $k=>$v) {
			$selected = '';
			if ( $v['project_id'] == $form['project_id'] ) $selected = ' selected ';
	
			$project .= '<option value='.$v['project_id'] . $selected .'>'. $v['project_no'] .'</option>';
		}
	}	
} else {
	$project .= '<option value="">Please Choose Client !!</option>';
}	
$project .= '</selected>';



?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportProject/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Report by Project Filter</legend>

		<table align=center>
		<tr><td colspan="2">&nbsp;<td></tr>
		<tr>
			<td class="label">Client : </td>
			<td><?=$client ?> </td>
		</tr>
		<tr>
			<td class="label">Project : </td>
			<td><?=$project ?> </td>
		</tr>
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="View Report" class="ff3 UILinkButton_A"/>
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
	</td>
</tr>	
</table>
<div id="tables" class="block">
<?php
if ( count( $budgetTotal ) > 0 ) {
	echo "<a id=export style='cursor:pointer;'><b>Export to EXCEL<b></a>
			<div id=inner></div>";
	$this->load->view('report_project_budget_actual');
}
?>
</div>
</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#client_id').focus();
	}
);	
$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

	$('#client_id').change(function() {
		var post = [{name:'ts', value: new Date().getTime()},
						{name:'client_id', value:$('#client_id').val()},
						{name:'project_id', value:$('#project_id').val()}];
		$.post('<?=$site ?>/report/getProject/',post,function(response){
			$('#project_id').html(response);
		})
	});

	$('#export').click( function (e) {
	  var data = $('#excel').html();
    $("#inner").append('<form id="exportform" action="<?=$site ?>/report/excel" method="post" target="_blank"><input type="hidden" id="exportdata" name="exportdata" /></form>');
    $("#exportdata").val(data);
    $("#exportform").submit().remove();
    
	});

});

</script>
<?php
$this->load->view('site_footer');
?>