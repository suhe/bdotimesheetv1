<?php
	$this->load->view('site_header');
?>

<?php if ($this->session->userdata('acl') == "008" || $this->session->userdata('acl') == "009") {?>

			<div class="grid_12">
				<h2 id="page-heading">Benefit Vacation</h2>
			</div>
			<div class="grid_12">
			<div class="box">

					<form id="form" class="form-container"  method="POST" action="<?=$site ?>/admin/search_vacation" >
						<fieldset class="entry">
						<legend>Data Filter</legend>

						<table align=center>
						<tr>
							<td class="label-login">Year</td>
							<td>2013</td>
						</tr>
                        <tr>
							<td class="label-login">Search</td>
							<td>
                                <input type="text" placeholder="NIK" style="width:80px;" class="inputtext" name="nik" value="" size="20" />
                                <input type="text" placeholder="NAME" style="width:150px;" class="inputtext" name="nickname" value="" size="40" />    
                                <input type="text" placeholder="GROUP" style="width:120px;" class="inputtext" name="group" value="" size="100"  />
                            </td>
						</tr>
						
						<tr>
							<td></td>
							<td>
                                <div class="ff3 UILinkButton">
									<input type="submit"  id="submit"  value="Search" class="ff3 UILinkButton_A"/>    
								</div>
							</td>
                         </tr>   
						</table>
						</fieldset>
					</form>
				</div> <!-- END FORMS -->

			<div class="box">
                <?=form_open('admin/saveVacation')?>
				<h2><a href="#">User List </a></h2>
				<div id="tables" class="block">
					<div id="paging">
						<span style="display:inline-block; width:100px; text-align:left;">
                        <div class="ff3 UILinkButton">
						  <input type="submit"  id="submit"  value="Save All" class="ff3 UILinkButton_A"/>    
					    </div>
                        </span>
						<!--<a href="<?=$site?>/admin/user/3/1" />First</a>
						<a href="<?=$site?>/admin/user/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/admin/user/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/admin/user/3/<?=$pg['l']?>" />Last</a>-->
						<span style="float:right;text-align:right;">
                            
                            <a style="color:red;font-weight: bold;" href="<?=$site?>/admin/userEdit/0" > [ 2013 ] </a> |
                            <a href="<?=$site?>/admin/userEdit/0" > [ 2014 ] </a>
                            <a href="<?=$site?>/admin/userEdit/0" > [ 2015 ] </a>
                        </span>

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
								<th>Vacation</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
                        <?php 
                            $class='';
                            $i=1;
                            foreach($users as $v):
                                if ( $i % 2 == 0) $class= 'class="odd"';
                        ?>
                            
                            <tr <?=$class?>>
                                <td><?=$i?></td>
                                <td><?=$v['employeeid']?></td>
                                <td><?=$v['employeenickname']?></td>
                			    <td><?=$v['employeefirstname'].' '.$v['employeemiddlename'].' '.$v['employeelastname']?></td>
                    			<td><?=$v['title']?></td>
                				<td><?=$v['department']?></td>
                				<td>
                                    <input type="checkbox" checked="true" name="ID[]" value="<?=$v['employee_id']?>" />
                                    <input type="text" name="vacation[]" style="width:20px;text-align:right" value="<?=$v['total']?>" />
                                </td>
                				<td></td>
                            </tr>
                        <?php 
                            $i++;
                            endforeach;?>
						</tbody>
					</table>
				    
                    <div id="paging">
						<span style="display:inline-block; width:100px; text-align:left;">
                        <div class="ff3 UILinkButton">
						  <input type="submit"  id="submit"  value="Save All" class="ff3 UILinkButton_A"/>    
					    </div>
                        </span>
						<!--<a href="<?=$site?>/admin/user/3/1" />First</a>
						<a href="<?=$site?>/admin/user/3/<?=$pg['p']?>" />Prev</a>
						<span style="display:inline-block; width:100px; text-align:center;"> Hal. #<?=$pg['c']?>/<?=$pg['l']?> </span>
						<a href="<?=$site?>/admin/user/3/<?=$pg['n']?>" />Next</a>
						<a href="<?=$site?>/admin/user/3/<?=$pg['l']?>" />Last</a>-->
						<span style="float:right;text-align:right;">
                            
                            <a style="color:red;font-weight: bold;" href="<?=$site?>/admin/userEdit/0" > [ 2013 ] </a> |
                            <a href="<?=$site?>/admin/userEdit/0" > [ 2014 ] </a>
                            <a href="<?=$site?>/admin/userEdit/0" > [ 2015 ] </a>
                        </span>

					</div>
                    
				</div>
			</div>
            <?=form_close()?>
		</div>

<?php } ?>
<?php
$this->load->view('site_footer');

?>