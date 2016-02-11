<?php
$this->load->view('site_header');

?>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
		<fieldset class="form-fieldset">
		<legend class="form-legend">Administration</legend>
	<table align="center">
	<tr><td>
				<table align=center>
				<tr><td>&nbsp;</tr>
				</table>	
			<td>
				<table align=center>
<?php 

if ( $list ) {
		$i = 1;
	foreach ($list as $k=>$v) {
		if ($v['menuid']=="05010000"){
			if ($this->session->userdata('department_id') == "13" ) {
				echo "<tr><td><a href=".$site."/administration/$v[menu]/>$v[label]</td></tr>";
			}
		} else {
		  //echo "<tr><td><a href=".$site."/administration/$v[menu]/>$v[label]</td></tr>";
		}
	}
}

?>
				</table>	
	</table>				
		</fieldset>
</div>


<?php
	$this->load->view('site_footer');
	
?>