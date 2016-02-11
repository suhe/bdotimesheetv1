<?php
$this->load->view('site_header');
?>
			<div class="grid_12">
				<h2 id="page-heading">Vacation Requests 2013</h2>
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
				<h2><a id="toggle-tables" href="#">Vacation List</a></h2>
				<div id="tables" class="block">
					
                    <div id="paging">
						<span style="float:right;text-align:right;">
                            <a <?php if($req==''){ ?> style="color:red;" <?php }?>  href="<?=$site?>/timesheet/vacationrequests/" > [ My Vacation List ] </a>
                            <?php if($this->session->userdata('aclname')<>'Assistant'): ?>
                            <a <?php if($req=='request'){?> style="color:red;" <?php }?> href="<?=$site?>/timesheet/vacationrequests/request/" > [ Request Vacation <?=$req_count?> ] </a>
                            <a <?php if($req=='approval'){?> style="color:red;" <?php }?> href="<?=$site?>/timesheet/vacationrequests/approval/" > [ Approval Vacation ] </a>
                            <?php endif;?>
                            <a href="<?=$site?>/timesheet/add_vacationrequests/" > [ ADD NEW ] </a>
                        </span>
					</div>
                    
                    <?php if(($req=='request')|| ($req=='approval') ):?>
                    <table class="grid">
						<thead>
							<tr>
								<th>No</th>
                                <th>Employee</th>
								<th>Req.Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
								<th>Description</th>
								<th>Total(Day)</th>
								<th>Action</th>
                                <th>View</th>
							</tr>
						</thead>
						<tbody>
                        
                        <?php 
                            $i=1;
                            $x=12;
                            foreach($records as $rec):?>
                            <?php
                                $x=$x-$rec['total'];
                            ?>
                        <tr>
                            <td><?=$i?></td>
						    <th><?=$rec['employeefirstname'].' '.$rec['employeelastname']?></th>
                            <th><?=$rec['created_date']?></th>
                            <th><?=$rec['date_from']?></th>
                            <th><?=$rec['date_to']?></th>
                            <th><?=$rec['status']?></th>
						    <th><?=$rec['vacation_desc']?></th>
				            <th class="currency" style='padding-right:30px;text-align:center;'><?=$rec['total'];?></th>
				            <th>
                                <?php if($req=='approval'):?>
                                    Approval by me
                                <?php else:?>
                                <a href="<?=site_url('/timesheet/approvalVacation/'.$rec['employee_id'].'/'.$rec['created_date'])?>">Approval</a> |
                                <a href="<?=site_url('/timesheet/cancelVacation/'.$rec['employee_id'].'/'.$rec['created_date'])?>">Cancel</a>
                                <?php endif;?>
                            </th>
                            <th><?=anchor(site_url('timesheet/vacation_view/'.$rec['employee_id'].'/'.$rec['created_date']),'View Details')?></th>
                        </tr>
                        <?php 
                            $i++;
                            endforeach;?> 
			</tbody>
		</table>
                    <?php else:?>
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>Date Req.</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
								<th>Description</th>
								<th>Balanced</th>
								<th class="currency" style='padding-right:30px;'>Credit</th>
								<th class="currency" style='padding-right:30px;'>Over</th>
								<th class="currency" style='padding-right:30px;'>Approved To</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
                        <?php if($req!='request'):?>
                        <tr>
                                <td>1</td>
								<th>01/01/2013</th>
                                <th>01/01/2013</th>
                                <th>31/12/2013</th>
                                <th>Benefit</th>
								<th>Benefit On Leave Year 2013</th>
								<th style="text-align:center;"><?=$balanced?></th>
								<th class="currency" style='padding-right:30px;'></th>
								<th class="currency" style='padding-right:30px;'></th>
								<th class="currency" style='padding-right:30px;'>
                                    <?php if($balanced>0)
                                        echo 'By HRD';
                                    ?>
                                </th>
								<th></th>
                        </tr>
                        <?php endif;?>
                        
                        <?php 
                            $i=2;
                            $x=$balanced;
                            foreach($records as $rec):?>
                            <?php
                                if($rec['status']=='Approval')
                                    $x=$x-$rec['total'];
                            ?>
                        <tr>
                            <td><?=$i?></td>
						    <th><?=$rec['created_date']?></th>
                            <th><?=$rec['date_from']?></th>
                            <th><?=$rec['date_to']?></th>
                            <th><?=$rec['status']?></th>
						    <th><?=$rec['vacation_desc']?></th>
						    <th style="text-align:center;"></th>
				            <th class="currency" style='padding-right:30px;'><?=$rec['total'];?></th>
						    <th class="currency" style='padding-right:30px;'><?=$x?></th>
						    <th class="currency" style='padding-right:30px;'><?=$rec['acl']?></th>
				            <th>
                                <?=anchor(site_url('timesheet/vacation_view/'.$rec['employee_id'].'/'.$rec['created_date']),'View Details')?>
                                <?php if($rec['status']=='Progress'):?>
                                    | <a onclick="return confirm('Are You Sure Want to Delete !')" href="<?=site_url().'/timesheet/deleteVacation/'.$rec['created_date'];?>">Delete</a>
                                <?php endif;?>    
                            </th>
                        </tr>
                        <?php 
                            $i++;
                            endforeach;?>
			</tbody>
		</table>
        <?php endif;?>
		
	</div>
</div>
<?php
$this->load->view('site_footer');
?>