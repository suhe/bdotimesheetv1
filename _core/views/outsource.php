<?php
	$this->load->view('site_header');
?>
			<div class="grid_12">
				<h2 id="page-heading">Outsource Data</h2>
			</div>
			<div class="grid_12">
			<div class="box">
				<h2><a id="toggle-forms" href="#" class="hidden">Data Filter</a></h2>

				<div id="forms" class="block" style="display: none;">
					<form id="form" class="form-container"  method="POST" action="<?=$site ?>data/employee/2" >
						<fieldset class="entry">
						<legend>Data Filter</legend>

						<table align=center>
						<tr>
							<td class="label-login">N.I.K : </td>
							<td><input type="text" class="inputtext" name="nik" value="" size="20" /></td>
						</td>
						<tr>
							<td class="label-login">Nick Name : </td>
							<td><input type="text"  class="inputtext" name="nickname" value="" size="40" /></td>
						</tr>
						<tr>
							<td class="label-login">Position : </td>
							<td><input type="text"  class="inputtext" name="position" value="" size="30" /></td>
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
			</div> <!-- END BOX -->

			<div class="box">
				<h2><a id="toggle-tables" href="#">Outsource List </a></h2>
				<div id="tables" class="block">
					<div id="paging">

						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=$site?>/data/outsource/3/1" />First</a>
						<a href="<?=$site?>/data/outsource/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/data/outsource/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/data/outsource/3/<?=$pg['l']?>" />Last</a>
						<span style="float:right;text-align:right;"><a href="<?=$site?>data/outsourceEdit/0" > [ ADD NEW ] </a></span>

					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>NIK</th>
								<th>Hire</th>
                                <th>Status</th>
                                <th>Email</th>
								<th>Full Name</th>
                                <th>Level</th>
								<th>Position</th>
                                <th>Pin</th>
								<th>Edit</th>
								<th>Remove</th>
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
			  <td>$v[employeehiredate]</td>
              <td>$v[employeestatus]</td>
    	      <td>$v[employeeemail]</td>
			  <td>$v[employeefirstname] $v[employeemiddlename] $v[employeelastname]</td>
  			  <td>$v[employeetitle]</td>
              <td>$v[position]</td>
              <td>$v[passtext]</td>
		      <td align='right'><a href='".$site."data/outsourceEdit/$v[employee_id]'>[ edit ]</a></td>
		      <td align='right'><a href='".$site."data/outsourceRemove/$v[employee_id]'>[ remove ]</a></td>
			</tr>";
		$i++;
	}
}
?>
						</tbody>
					</table>
				<div id="paging">
					<a href="<?=$site?>/data/employee/3/1" />First</a>
					<a href="<?=$site?>/data/employee/3/<?=$pg['p']?>" />Prev</a>
					<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
					<a href="<?=$site?>/data/employee/3/<?=$pg['n']?>" />Next</a>
					<a href="<?=$site?>/data/employee/3/<?=$pg['l']?>" />Last</a>
				</div>
				</div>
			</div>
		</div>

<?php
$this->load->view('site_footer');

?>