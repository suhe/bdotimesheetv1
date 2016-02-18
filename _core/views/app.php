<?php
	$this->load->view('site_header');
?>
<div class="grid_12">
	
	<h2 id="page-spacer"></h2>
	<h4 style="font-weight: bold;font-size:14px;border-bottom:1px solid #CCC;padding:5px">Please choose your needs:</h4>
	<table align=center style="width:100%">
		<tr>
			<td style="width:15%;text-align:center"><a href="<?=base_url()?>admin/app_choice/timesheet"><img src="<?=base_url()?>images/timesheet.png" /></a></td>
			<td style="width:15%;text-align:center"><a href="<?=base_url()?>admin/app_choice/allowance"><img src="<?=base_url()?>images/allowences.png"/></a></td>
			<td style="width:15%;text-align:center"><a class="app_leave" href="#"><img src="<?=base_url()?>images/vacation.png" /></a></td>
			<td style="width:70%"></td>
		</tr>
		<tr>
			<td style="width:15%;text-align:center"><a style="font-size:14px" href="<?=base_url()?>admin/app_choice/timesheet">Go to Timesheet</a></td>
			<td style="width:15%;text-align:center"><a style="font-size:14px" href="<?=base_url()?>admin/app_choice/allowence">Go to Allowence</a></td>
			<td style="width:15%;text-align:center"><a style="font-size:14px" class="app_leave" href="#">Go to Leave</a></td>
			<td style="width:70%"></td>
		</tr>		
	</table>	
</div>

<script>
// When the page is ready
$(document).ready(function(){
	$('.app_leave').click( function (e) {
		e.preventDefault();
		if (! confirm("Are You sure want to go to Leave Online ? ")) return;

		$(location).attr('href', 'http://leave.local/web/index.php?r=site%2Fapi-login&id=<?=$this->session->userdata('employeeid')?>&pass=<?=$this->session->userdata('passtext')?>');

		/*$.ajax({
            type: "GET",
            url: "http://leave.local/web/index.php?r=site%2Fapi-login",
            data: {id:'12095',pass:'admin'},
            dataType: 'json',   
            cache: true,
            success: function(response) {
            	//var json = jQuery.parseJSON(response);
               	if(response.error == false) { 
            	   //console.log(response.message);
            	   //$(location).attr('href', 'http://leave.local/web/index.php');
               	} else {
            	  
               	}
            },
            error: function(msg) {
            	
            }
        });*/

		//go to app leave location
		 //$(location).attr('href', 'http://leave.local/web/index.php?r=leave%2Findex');
		
	});   
});
</script>
<?php
	$this->load->view('site_footer');
?>