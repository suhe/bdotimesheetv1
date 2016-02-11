<?php
	$this->load->view('site_header');
?>

<?php if ($this->session->userdata('acl') == "008" || $this->session->userdata('acl') == "009") {?>

			<div class="grid_12">
				<h2 id="page-heading">User Management </h2>
			</div>
			<div class="grid_12">
			<div class="box">

					<form id="form" class="form-container"  method="POST" action="<?=$site ?>/admin/user/2" >
						<fieldset class="entry">
						<legend>Data Filter</legend>

						<table align=center>
						<tr>
							<td class="label-login">N.I.K : </td>
							<td><input type="text" class="inputtext" name="nik" value="" size="20" /></td>
						</td>
						<tr>
							<td class="label-login">Name : </td>
							<td><input type="text"  class="inputtext" name="nickname" value="" size="40" /></td>
						</tr>
						<tr>
							<td class="label-login">Group : </td>
							<td><input type="text"  class="inputtext" name="group" value="" size="100"  /></td>
						</tr>
						<tr>
							<td class="label-login">Approval : </td>
							<td><input type="text"  class="inputtext" name="approval" value="" size="100"  /></td>
						</tr>

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

			<div class="box">
				<h2><a href="#">User List </a></h2>
				<div id="tables" class="block">
					<div id="paging">

						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=$site?>/admin/user/3/1" />First</a>
						<a href="<?=$site?>/admin/user/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/admin/user/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/admin/user/3/<?=$pg['l']?>" />Last</a>
						<span style="float:right;text-align:right;"><a href="<?=$site?>/admin/userEdit/0" > [ ADD NEW ] </a></span>

					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>NIK</th>
								<th>Nick Name</th>
								<th>Full Name</th>
								<th>Access</th>
								<th>Group</th>
								<th>Approval</th>
								<th>Status</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
<?php
if ( $table ) {
	$i = 1;
	foreach ($table as $k=>$v) {
		$class= '';
		if ( $i % 2 == 0) $class= 'class="odd"';

		echo "<tr $class >
			  <td>";
		echo $i + $pg['o'];
		echo "</td>
			  <td>$v[employeeid]</td>
				<td>$v[employeenickname]</td>
			  <td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
    			<td>$v[title]</td>
				<td>$v[department]</td>
				<td>$v[approval]</td>
				<td>$v[status]</td>
				<td align='right'><a href='$site/admin/userEdit/$v[user_id]'> EDIT </a>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
										<a href='$site/admin/userReset/$v[user_id]/$v[employee_id]'> PASSWORD </a>
				</td></tr>";
		$i++;
	}
}
?>
						</tbody>
					</table>
				<div id="paging">
					<a href="<?=$site?>/admin/user/3/1" />First</a>
					<a href="<?=$site?>/admin/user/3/<?=$pg['p']?>" />Prev</a>
					<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
					<a href="<?=$site?>/admin/user/3/<?=$pg['n']?>" />Next</a>
					<a href="<?=$site?>/admin/user/3/<?=$pg['l']?>" />Last</a>
				</div>
				</div>
			</div>
		</div>

<?php } ?>
<?php
$this->load->view('site_footer');

?>