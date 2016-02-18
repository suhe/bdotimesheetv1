<?php
$this->load->view('site_header');
?>
			<div class="grid_12">
				<h2 id="page-heading">Allowance</h2>
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


			<div class="box">
				<h2><a id="toggle-tables" href="#">Allowance outside the city </a></h2>
				<div id="tables" class="block">
					<div id="paging">

						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=base_url()?>timesheet/allowance/3/1" />First</a>
						<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['l']?>" />Last</a>
						<span style="float:right;text-align:right;"><a href="<?=base_url()?>timesheet/allowance_form" > [ ADD NEW ] </a></span>
					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>Date From</th>
								<th>Date To</th>
								<th>Client</th>
								<th>Project</th>						
								<th class=currency style='padding-right:30px;'>Total</th>
								<th>Date Realization</th>
								<th>Date Approved</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
						</tbody>

					</table>
		<div id="paging">
			<a href="<?=base_url()?>timesheet/allowance/3/1" />First</a>
			<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['p']?>" />Prev</a>
			<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
			<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['n']?>" />Next</a>
			<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['l']?>" />Last</a>
		</div>
		</div>
	</div>
</div>
<?php
$this->load->view('site_footer');
?>