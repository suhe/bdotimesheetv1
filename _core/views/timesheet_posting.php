<?php
	$this->load->view('site_header');
?>
			<?=$menu ?>
			<div class="clear"></div>
			<div class="grid_12">
				<h2 id="page-heading">Timesheet </h2>
			</div>
			<div class="grid_12">

<div class="box">
					<h2><a id="toggle-tables" href="#">TimeSheet </a></h2>
					
					<div class="box">
					<div id="forms" class="block" style="display: block;">
						<form action="">
							<fieldset class="login">
								<legend>Posting </legend>
								<p>
									<label>Minggu Ke: </label>
									<select name="minggu" style="width:50px;">
										<option>01</option>
										<option>02</option>
										<option>03</option>
										<option>04</option>
										<option>05</option>
										<option>06</option>
									</select>
									<select name="tahun" style="width:50px;">
										<option>2009</option>
										<option>2010</option>
										<option>2011</option>
										<option>2012</option>
										<option>2013</option>
										<option>2014</option>
									</select>

								</p>
								<p>
									<label>Password: </label>
									<input type="password" name="password"/>
								</p>
								<p>
									<label>Re-type Password: </label>
									<input type="password" name="password2"/>
								</p>
								<input type="submit" value="Confirm Availability" class="confirm button"/>
							</fieldset>
						</form>
					</div>
				</div>
				
					<div id="tables" class="block">

						<table summary="This table includes examples of as many table elements as possible">
							<thead>
								<tr>
									<th class="table-head" colspan="4">TimeSheet </th>
								</tr>
								<tr>
									<th>No</th>
									<th>Project</th>
									<th>Job</th>
									<th>Date</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td/>
									<th>Subtotal</th>
									<td/>
									<th class="currency">$500.00</th>
								</tr>
								<tr class="total">
									<td/>
									<th>Total</th>
									<td/>
									<th class="currency">$500.00</th>
								</tr>
							</tfoot>
							<tbody>
<?php 
if ( $table ) {
	$i = 0;
	foreach ($table as $k=>$v) {
		$class= '';
		if ( $i % 2 == 0) $class= 'class="odd"';

		echo '<tr '. $class .'>
			  <td>'. $i .'</td>
			  <td>'. $v['jobid'].'</td>
			  <td>'. $v['project_id'].'</td>
			 <td>'. $v['dstart'].'</td>';
		$i++;
	}
}
?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

<?php
	$this->load->view('site_footer');
?>