<?php
	$this->load->view('site_header');
?>
			<div class="grid_12">
				<h2 id="page-heading">Client </h2>
			</div>
			<div class="grid_12">
			<div class="box">
				<h2><a id="toggle-forms" href="#" class="hidden">Data Filter</a></h2>

				<div id="forms" class="block" style="display: none;">
					<form id="form" class="form-container"  method="POST" action="<?=$site ?>/client/index/2" >
						<fieldset class="entry">
						<legend>Data Filter</legend>

						<table align=center>
						<tr>
							<td class="label-login">Client Code : </td>
							<td><input type="text" class="inputtext" id="client_no" name="client_no" value="" size="20" /></td>
						</td>
						<tr>
							<td class="label-login">Client Name : </td>
							<td><input type="text"  class="inputtext" id="client_name" name="client_name" value="" size="40" /></td>
						</tr>
						<tr>
							<td class="label-login">Address : </td>
							<td><input type="text"  class="inputtext" id="address" name="address" value="" size="100" style="width:400px;" /></td>
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
				<h2><a id="toggle-tables" href="#">Client List </a></h2>
				<div id="tables" class="block">
					<div id="paging">

						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=$site?>/client/index/3/1" />First</a>
						<a href="<?=$site?>/client/index/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/client/index/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/client/index/3/<?=$pg['l']?>" />Last</a>
						<span style="float:right;text-align:right;"><a href="<?=$site?>/client/Edit/0" > [ ADD NEW ] </a></span>

					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>Client Code</th>
								<th>Client Name</th>
								<th>Address</th>
								<th>Phone</th>
								<th>Fax</th>
								<th>Contact Person</th>
								<th>Line of Business</th>
								<th>Website</th>
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
				<td>$v[client_no]</td>
				<td>$v[client_name]</td>
				<td>$v[address]</td>
				<td>$v[phone]</td>
				<td>$v[fax]</td>
				<td>$v[contact]</td>
				<td>$v[lob]</td>
				<td>$v[website]</td>
				<td align='right' nowrap><a href='$site/client/Edit/$v[client_id]'>[ edit ]</a></td></tr>";
		$i++;
	}
}
?>
						</tbody>
					</table>
				<div id="paging">
					<a href="<?=$site?>/client/index/3/1" />First</a>
					<a href="<?=$site?>/client/index/3/<?=$pg['p']?>" />Prev</a>
					<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
					<a href="<?=$site?>/client/index/3/<?=$pg['n']?>" />Next</a>
					<a href="<?=$site?>/client/index/3/<?=$pg['l']?>" />Last</a>
				</div>
				</div>
			</div>
		</div>

<?php
$this->load->view('site_footer');

?>