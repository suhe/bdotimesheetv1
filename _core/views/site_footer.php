			<div class="grid_12" id="site_info" style="padding-top:8px">
					<marquee scrolldelay="200">
					   <p align="right" style="color:blue;font-weight:bold;font-size:10pt" > 
					     ::: Payroll <?=config_item('month_payroll')?> : <?=config_item('date_start')?> - <?=config_item('date_end')?> , Please finish and submit before <?=config_item('date_end')?> ::: 		
					   </p>
					</marquee>
					<hr/>
			</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript" src="<?=$base_url ?>/js/jquery-ui.js"></script>
		<script type="text/javascript" src="<?=$base_url ?>/js/jquery-system.js"></script>
	</body>
</html>