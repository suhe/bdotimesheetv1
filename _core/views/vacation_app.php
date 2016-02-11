<?php $this->load->view('site_header');?>
			<div class="grid_12">
				<h2 id="page-heading">Vacation Approval For HRD Manager</h2>
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
                            
                            <a <?php if($req=='senior'){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacationapp/senior" >[On Auditor In Charge]</a> | 
                            <a <?php if($req=='manager'){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacationapp/manager" >[On Manager In Charge]</a> |
                            <a <?php if($req=='partner'){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacationapp/partner" >[On Partner In Charge]</a> |
                            <a <?php if($req=='hrd'){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacationapp/hrd" >[On HRD In Charge]</a> |
                            <a <?php if($req=='approval'){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacationapp/approval" >[Approval Vacation]</a> |
                            <a <?php if($req=='cancel'){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacationapp/cancel" >[Cancel Vacation]</a> |
                            <a <?php if($req==''){ ?> style="color:red;" <?php }?>  href="<?=$site?>/data/vacation_timesheet/" >[Cuti Tahunan Timesheet]</a>
                        </span>
					</div>
                    
                    
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
                                <?php if($rec['status']=='Approval'):?>
                                    Approval by <?=$rec['app_name']?>
                                <?php elseif($rec['status']=='Cancel'):?>
                                    Cancel by <?=$rec['CancelName']?>
                                <?php else:?>
                                <a href="<?=site_url('/data/approvalVacation/'.$rec['employee_id'].'/'.$rec['created_date'])?>">Approval</a> |
                                <a href="<?=site_url('/data/cancelVacation/'.$rec['employee_id'].'/'.$rec['created_date'])?>">Cancel</a>
                                <?php endif;?>
                            </th>
                            <th><?=anchor(site_url('data/vacation_view/'.$rec['employee_id'].'/'.$rec['created_date']),'View Details')?></th>
                        </tr>
                        <?php 
                            $i++;
                            endforeach;?> 
			</tbody>
		</table>
                    
        
        <!--
        <table class="grid">
						<thead>
                            <tr>
                                <td colspan="33">January 2013</td>
                            </tr>
							<tr>
								<th>No</th>
								<th>Nama</th>
                                <?php for($i=1;$i<=31;$i++):?>
                                <th><?=$i?></th>
                                <?php endfor;?>
							</tr>
						</thead>
						<tbody>
                        <tr>
								<td>1</td>
								<td>Suhendar</td>
                                <?php for($i=1;$i<=31;$i++):?>
                                <td><?='8'?></td>
                                <?php endfor;?>
							</tr>
                         
			         </tbody>
		</table>
        
        <table class="grid">
						<thead>
                            <tr>
                                <td colspan="30">Februari 2013</td>
                            </tr>
							<tr>
								<th>No</th>
								<th>Nama</th>
                                <?php for($i=1;$i<=28;$i++):?>
                                <th><?=$i?></th>
                                <?php endfor;?>
							</tr>
						</thead>
						<tbody>
                        <tr>
								<td>1</td>
								<td>Markus</td>
                                <?php for($i=1;$i<=28;$i++):?>
                                <td><?='8'?></td>
                                <?php endfor;?>
							</tr>
                         
			         </tbody>
		</table>
        
        <table class="grid">
						<thead>
                            <tr>
                                <td colspan="33">Maret 2013</td>
                            </tr>
							<tr>
								<th>No</th>
								<th>Nama</th>
                                <?php for($i=1;$i<=31;$i++):?>
                                <th><?=$i?></th>
                                <?php endfor;?>
							</tr>
						</thead>
						<tbody>
                        <tr>
								<td>1</td>
								<td>Fahrani</td>
                                <?php for($i=1;$i<=31;$i++):?>
                                <td><?='8'?></td>
                                <?php endfor;?>
							</tr>
                         
			         </tbody>
		</table>
		-->
	</div>
</div>
<?php
$this->load->view('site_footer');
?>