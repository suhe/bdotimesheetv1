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
					<form id="form" class="form-container"  method="GET" action="<?=base_url() ?>timesheet/allowance/">
						<fieldset class="entry">
						<legend>Data Filter</legend>

						<table align=center>
						<tr>
							<td class="label-login">Client Name : </td>
							<td><input type="text"  class="inputtext" id="client_name" name="client_name" value="<?=$form['client_name']?>" size="40" /></td>
						</tr>
						<tr>
							<td class="label-login">Project Code : </td>
							<td><input type="text"  class="inputtext" id="project_no" name="project_no" value="<?=$form['project_no']?>" size="100"  /></td>
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
				<h2><a id="toggle-tables" href="#">Allowance outside the city </a></h2>
				<div id="tables" class="block">
					<div id="paging">

						<span style="display:inline-block; width:100px; text-align:left;"> Total : <?=$pg['t']?> data</span>
						<a href="<?=base_url()?>timesheet/allowance/3/1">First</a>
						<a href="<?=base_url()?>timesheet/allowance/3><?=$pg['p']?>" >Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=base_url()?>timesheet/allowance/3<?=$pg['n']?>" >Next</a>
						<a href="<?=base_url()?>timesheet/allowance/3<?=$pg['l']?>" >Last</a>
						<span style="float:right;text-align:right;"><a href="<?=base_url()?>timesheet/allowance_form" > [ ADD NEW ] </a></span>
					</div>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>#</th>
								<th>From</th>
								<th>To</th>
								<th>Days</th>
								<th>Client</th>
								<th>Project</th>	
								<th>MIC</th>					
								<th class=currency style='padding-right:30px;'>Employee</th>
								<th>Realization</th>
								<th>Approved</th>
								<th class=currency style='padding-right:30px;'>Total</th>
								<th style="width:100px">*</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$i = 1;
						foreach($rows as $row) { ?>
						<tr>
							<td><?=$i?></td>
							<td>
							<?php
								if($row["date_approved"] == '-')
									echo '<img src="'.base_url().'/images/BulletRed.png"/>';
								else 
									echo '<img src="'.base_url().'/images/BulletGreen.png"/>';
							?></td>
							<td><?=$row["date_from"]?></td>
							<td><?=$row["date_to"]?></td>
							<td><?=$row["total_day"]?></td>
							<td><?=$row["client_name"]?></td>
							<td><?=$row["project_no"]?></td>
							<td><?=$row["approval_name"]?></td>
							<td class=currency style='padding-right:30px;'><?=$row["total_employee"]?></td>
							<td><?=$row["date_realization"]?></td>
							<td><?=$row["date_approved"]?></td>
							<td class=currency style='padding-right:30px;'><?=number_format($row["total"],2)?></td>
							<td>
								<a href="<?=base_url()?>timesheet/allowance_form/<?=$row["id"]?>" >[Edit]</a>
								<a href="<?=base_url()?>timesheet/allowance_remove/<?=$row["id"]?>" >[Remove]</a>
							</td>
						</tr>
						<?php 
						$i++;
						} ?>
						</tbody>

					</table>
		<div id="paging">
			<a href="<?=base_url()?>timesheet/allowance/3/1">First</a>
			<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['p']?>" >Prev</a>
			<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
			<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['n']?>" >Next</a>
			<a href="<?=base_url()?>timesheet/allowance/3/<?=$pg['l']?>" >Last</a>
		</div>
		</div>
	</div>
</div>
<?php
$this->load->view('site_footer');
?>