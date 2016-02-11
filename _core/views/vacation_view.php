<?php
$this->load->view('site_header');
?>
			<div class="grid_12">
				<h2 id="page-heading">Vacation By <?=$value['employeefirstname'].' '.$value['employeelastname']?></h2>
			</div>
			
            <div class="grid_12">
			
            <div class="box">
				<h2><a id="toggle-forms" href="#">Summary Vacation</a></h2>

				<div id="forms" class="block">
					<form id="form" class="form-container"  method="POST" action="<?=$site ?>/project/index/2" >
						<fieldset class="entry">
						<legend>Biodata</legend>

						<table align=center>
						<tr>
							<td class="label-login">Name </td>
							<td><?=$value['employeefirstname'].' '.$value['employeelastname']?></td>
						</tr>
						
						<tr>
							<td class="label-login">Vacation</td>
							<td>From <?=$value['date_from']?> to <?=$value['date_to']?> </td>
						</tr>
                        
                        <tr>
							<td class="label-login">Created</td>
							<td><?=$value['created_date']?></td>
						</tr>
                        
                        </td>
						<tr>
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
                            <?php 
                                if($value['employee_id'] <> $this->session->userdata('employee_id')):
                                    if(($value['status']=='Progress') && ( $acl <>$this->session->userdata('employee_id'))):
                                        if($this->session->userdata('aclname')<>'Assistant'):
                            ?>             
                                        <a href="<?=site_url('/timesheet/approvalVacation/'.$value['employee_id'].'/'.$value['created_date'].'/request')?>" > [ APPROVED ALL ] </a>
                                        <a href="<?=site_url('/timesheet/cancelVacation/'.$value['employee_id'].'/'.$value['created_date'].'/request')?>" > [ CANCEL ALL ] </a>
                            <?php
                                        endif;
                                    endif; 
                                endif;
                            ?>
                            <a href="<?=$back_link?>"> [ BACK TO LIST ] </a>
                        </span>
					</div>
                    
     
					<table class="grid">
						<thead>
							<tr>
								<th>No</th>
								<th>Request Date</th>
                                <th>Vacation Date</th>
                                <th>Status</th>
								<th>Description</th>
								<th class="currency" style='padding-right:30px;'>Approved To</th>
								<th>Cancel</th>
							</tr>
						</thead>
						<tbody>
                        
                        <?php 
                            $i=1;
                            $x=12;
                            foreach($records as $rec):?>
                        <tr>
                            <td><?=$i?></td>
						    <th><?php
                                if($i==1)
                                    print $rec['created_date'];
                                ?>
                            </th>
                            <th><?=$rec['vacation_date']?></th>
                            <th><?=$rec['status']?></th>
						    <th><?=$rec['vacation_desc']?></th>
						    <th style="text-align:center;"></th>
				            <th>
                                <?php if($rec['status']=='Progress')?>
                                    <a onclick="return confirm('Are You Sure Want to Cancel !')" href="<?=site_url().'/timesheet/deleteVacationDetails/'.SHA1($rec['vacation_id']);?>">Cancel</a>
                            </th>
                        </tr>
                        <?php 
                            $i++;
                            endforeach;?>
			</tbody>
		</table>
        
	</div>
</div>
<?php
$this->load->view('site_footer');
?>