<?php
	$this->load->view('site_header');
?>
<!--
			<?php $this->load->view('menu'); ?>
-->			
			<?=$menu ?>
			<div class="clear"></div>
			<div class="grid_12">
				<h2 id="page-heading">Client </h2>
			</div>
			<div class="grid_12">

<div class="box">
					<h2>
						<a id="toggle-tables" href="#">Client List Accordion</a>
					</h2>
					<div id="tables" class="block">

						<table summary="This table includes examples of as many table elements as possible">
<!--
							<caption>An example table</caption>
							<colgroup>
								<col class="colA"/>
								<col class="colB"/>
								<col class="colC"/>
								<col class="colD"/>
							</colgroup>
-->							
							<thead>
								<tr>
									<th class="table-head" colspan="4">Client List Header</th>
								</tr>
								<tr>
									<th>No</th>
									<th>Client Code</th>
									<th>Client Name</th>
									<th>Address</th>
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
			  <td>'. $v['client_no'].'</td>
			  <td>'. $v['client_name'].'</td>
			  <td class="currency">$125.00</td>';
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