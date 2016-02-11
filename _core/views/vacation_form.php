<?php $this->load->view('site_header');?> 
<div class="grid_12">
	<h2 id="page-spacer"></h2>
	<form id="form_project" method="POST" action="<?=$site?>/timesheet/vacationsave" >
		<input type="hidden" id="project_id" name="project_id" value="<?=$form['project_id'] ?>"  />
		
		<fieldset class="form-fieldset">
		<legend class="form-legend">Vacation Formulir</legend>
	
        <table style="text-align: left;">
	   <tr>
		   <td colspan="2" style="text-align:left;" class="label-message" id="msg"><?=$form['message'] ?></td>
	   </tr>    
    
        <tr>
    	   <td style="text-align:left;width:20px;">As Of Date From Vacation</td>
    	   <td>
                
                <input type="text"  class="inputtext date" readonly="true" id="start_date" name="date_from" value="" size="40" style="width:80px;" style="width:80px;"/>
    				 &nbsp;&nbsp;To&nbsp;&nbsp;	
    	       <input type="text"  class="inputtext date" readonly="true" id="finish_date" name="date_to" value="" size="40" style="width:80px;" style="width:80px;"/>
    	   </td>
    	</tr>
        
        <tr>
    	   <td style="text-align:left;width:20px;" >Vacation Description</td>
    	   <td>
             <input class="inputtext" type="text" name="content" style="width:600px" />   
    	   </td>
    	</tr>
        
        <tr>
    	   <td style="text-align:left;width:20px;" >Address of Vacation</td>
    	   <td>
             <input class="inputtext" type="text" name="address" style="width:600px" />   
    	   </td>
    	</tr>
        
        <tr>
    	   <td style="text-align:left;width:20px;">Date Req.</td>
    	   <td>
             <input class="inputtext date" type="text" value="" name="created" style="width:100px;" />   
    	   </td>
    	</tr>
       	
	</table>
    
    <div class="ff3 UILinkButton">
			<input type="submit" class="ff3 UILinkButton_A" value="Save" id="submit" />
            <div class="UILinkButton_RW">
				<div class="UILinkButton_R"></div>
			</div>
	</div>
    
<script>
    $(document).ready(function(){
	$('input.date').datepick({dateFormat:'dd/mm/yy'});	
});
</script>
<?php $this->load->view('site_footer'); ?>