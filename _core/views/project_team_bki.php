			<tr>
                    <td>1</td>
                    <td>Partner In Charge(PIC) *Required. </td>
                    <td>
                    <select name="pic">
                     <?php
                        print '<option value="">Select Partner In Charge</option>';
                        foreach($pic as $v){
                            $selected = '';
                        	if ( $v['employee_id'] == $form_pic['employee_id'] ){
                        		      $selected = ' selected ';
                        	} 
                            print '<option'.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';
                        }
                     ?>
                       </select>
                    </td>
             </tr>
			 
			 <tr>
                    <td>2</td>
                    <td>Group Coordinator *Required. </td>
                    <td>
                    <select name="gc">
                    <?php
                        print '<option value="">Select Group Coordinator</option>';
                        foreach($gc as $v){
                            $selected = '';
                        	if ( $v['employee_id'] == $form_gc['employee_id'] ) {
                        		      $selected = ' selected ';
                        	} 
                            print '<option'.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';
                        }
                     ?>
                     </select>
                    </td>
                </tr>
				
				<tr>
                    <td>3</td>
                    <td>Manager In Charge *Required. </td>
                    <td>
                    <select name="mic">
                    <?php
                        print '<option value="">Select Manager In Charge</option>';
                        foreach($mic as $v){
                            $selected = '';
                        	if ( $v['employee_id'] == $form_mic['employee_id'] ) {
                        		      $selected = ' selected ';
                        	} 
                            print '<option'.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';
                        }
                     ?>
                     </select>
                    </td>
                </tr>
				
				<tr>
                    <td>4</td>
                    <td>Auditor In Charge *Required. </td>
                    <td class="p_scents">
                    <?php 
                     if($form_aic){
                        foreach($form_aic as $row){
                            print  '<span class="team_aic">';
                            print '<select name="aic[]">';
			    print '<option value="0">-</option>';   
                            foreach($aic as $v){
                                $selected = '';
                                if ( $v['employee_id'] == $row['employee_id'] ) {
                        		      $selected = 'selected ';
                        	    } 
                                print '<option '.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';    
                            }    
                            print '</select>'.$row['department_id'];
			    if($this->session->userdata('department_id')==7)
			      print '<a href id='.$row['teamid'].' class="remove_team_aic">&nbsp;&nbsp;<img style="margin-top:3px" src="'.base_url().'images/switch_minus.gif" /></a> <br style="margin-bottom:5px;margin-top:5px"/>';
                            print '</span>';
							
							
                        }
                     } else {
                         print '<select name="aic[]">';
			 print '<option value="0">-</option>';  
                         foreach($aic as $v){
                                $selected = '';
                                if ( $v['employee_id'] == $row['employee_id'] ) {
                        		      $selected = 'selected ';
                        	    } 
                                print '<option '.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';    
                         }    
                         print '</select>';
                     }
                     ?>
	              <?php if($this->session->userdata('department_id')==7){?>   
                      <a href="#" id="addScnt1" style="border-top:none;" ><img src="<?=base_url()?>images/switch_plus.gif" /> Add</a>
                      <br style="margin-bottom:5px;margin-top:5px"/>
		      <?php } ?>
                    </td>
                </tr>
				
				 <tr>
                    <td>5</td>
                    <td>Assistant </td>
                    <td id="p_scents2">
                    <?php 
                     if($form_ass){
                        foreach($form_ass as $row){
                            print '<span class="team_ass">';
                            print '<select name="ass[]">';
			    print '<option value="0">-</option>';  
                            foreach($aic as $v){
                                $selected = '';
                                if ( $v['employee_id'] == $row['employee_id'] ) {
                        		      $selected = 'selected ';
                        	    } 
                                print '<option '.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';    
                            }    
                            print '</select>';
                            if($this->session->userdata('department_id')==7)
			      print '<a href id='.$row['teamid'].' class="remove_team_ass">&nbsp;&nbsp;<img style="margin-top:3px" src="'.base_url().'images/switch_minus.gif" /></a> <br/>';
                            else
			      print '<br/>'; 
			    print '</span>';
                        }
                     } 
                     ?>
                    </td>
                </tr>
		
		<?php if($this->session->userdata('department_id') == 7) { ?>		
		<tr>
                    <td>6</td>
                    <td>Outsource *Optional </td>
                    <td class="p_scents2">
                    
                    <?php 
                     if($form_aic){
                        foreach($form_ot as $row){
                            print  '<span class="team_aic">';
                            print '<select name="ot[]" style="width:200px">';
			    print '<option value="0">-</option>';   
                            foreach($ot as $v){
                                $selected = '';
                                if ( $v['employee_id'] == $row['employee_id'] ) {
                        		      $selected = 'selected ';
                        	    } 
                                print '<option '.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';    
                            }    
                            print '</select>'.$row['department_id'];
			    if($this->session->userdata('department_id')==7)
			      print '<a href id='.$row['teamid'].' class="remove_team_ot">&nbsp;&nbsp;<img style="margin-top:3px" src="'.base_url().'images/switch_minus.gif" /></a> <br style="margin-bottom:5px;margin-top:5px"/>';
                            print '</span>';
							
							
                        }
                     } else {
                         print '<select name="ot[]" style="width:200px">';
			 print '<option value="0">-</option>';  
                         foreach($ot as $v){
                                $selected = '';
                                if ( $v['employee_id'] == $row['employee_id'] ) {
                        		      $selected = 'selected ';
                        	    } 
                                print '<option '.$selected.' value='.$v['employee_id'].'>'.$v['employeename'].'</option>';    
                         }    
                         print '</select>';
                     }
                     ?>
	              <?php if($this->session->userdata('department_id') == 7){?>   
                      <a href="#" id="addScnt2" style="border-top:none;" ><img src="<?=base_url()?>images/switch_plus.gif" /> Add</a>
                      <br style="margin-bottom:5px;margin-top:5px"/>
		      <?php } ?>
		      
                    <?php 
                     /*if($form_ot){
                        foreach($form_ot as $row){
                            print  '<span class="team_aic">';
                            print '<input name="ot[]" type="text" value="'.$row['team_description'].'" ';
                            print '< style="width:285px" />';
							print '<a href id='.$row['teamid'].' class="remove_team_aic">&nbsp;&nbsp;<img style="margin-top:3px" src="'.base_url().'images/switch_minus.gif" /></a> <br style="margin-bottom:5px;margin-top:5px"/>';
                            print '</span>';
							
							
                        }
                     } */
                     ?>
		      					  
                    </td>
                </tr>
		<?php } ?>
				
				 <tr>
                    <td style="vertical-align:top">
		     <?php
		     $no=7;
		     if($this->session->userdata('department_id')!=7)
		       $no=$no-1;
		     echo $no;
		     ?>
		    </td>
                    <td style="vertical-align:top">Note</td>
                    <td>
                     <?=form_textarea('note',$form['project_note'])?>
                    </td>
                </tr>
				
	<script>
	<?php
    $xx = '<option value="">Select Employee</option>';
    foreach($aic as $v){
        $xx .= '<option value='.$v['employee_id'].'>'.$v['employeename'].'</option>';
    }
	
	$xx2 = '<option value="">Select Outsource</option>';
    foreach($ot as $v){
        $xx2 .= '<option value='.$v['employee_id'].'>'.$v['employeename'].'</option>';
    }
   
?>

		$(function() {
        var scntDiv = $('.p_scents');
        var i = $('.p_scents p').size() + 1;
        
        $('#addScnt1').live('click', function() {
                $('<span class="xx"><select name="aic[]">'+
                  '<?=$xx?>'+
                  '</select><a href class="remScnt">&nbsp;&nbsp;<img style="margin-top:3px" src="<?=base_url()?>images/switch_minus.gif" /></a> <br style="margin-bottom:5px;margin-top:5px"/></span> ').appendTo(scntDiv);
                i++;
                return false;
        });
		
		var scntDiv2 = $('.p_scents2');
        var i = $('.p_scents2 p').size() + 1;
        
        $('#addScnt2').live('click', function() {
        	$('<span class="xx"><select name="ot[]">'+
                 '<?=$xx2?>'+
                 '</select><a href class="remScnt">&nbsp;&nbsp;<img style="margin-top:3px" src="<?=base_url()?>images/switch_minus.gif" /></a> <br style="margin-bottom:5px;margin-top:5px"/></span> ').appendTo(scntDiv2);
                  i++;
                return false;
        });
		
		$('.remScnt').live('click', function() {
              if(confirm("Are You Sure Want to Delete ! ")){
                $(this).parents(".xx").animate({ opacity: "hide" }, "slow");
              }  
                return false;
        });
		
		$('.remove_team_aic').live('click', function() {
              var element = $(this); //Save the link in a variable called element
    		  var del_id = element.attr("id"); //Find the id of the link that was clicked
         	  var info = 'id=' + del_id;
              if(confirm("Are You Sure Want to Delete ! ")){
                $.ajax({
            		type: "POST",
            		url : "<?=site_url()?>/project/delete_team/",
            		data: info,
            		success: function(){
            		}
            	 });
                $(this).parents(".team_aic").animate({ opacity: "hide" }, "slow");
              }  
                return false;
        });

		$('.remove_team_ot').live('click', function() {
            var element = $(this); //Save the link in a variable called element
  		  var del_id = element.attr("id"); //Find the id of the link that was clicked
       	  var info = 'id=' + del_id;
            if(confirm("Are You Sure Want to Delete ! ")){
              $.ajax({
          		type: "POST",
          		url : "<?=site_url()?>/project/delete_team/",
          		data: info,
          		success: function(){
          		}
          	 });
              $(this).parents(".team_aic").animate({ opacity: "hide" }, "slow");
            }  
              return false;
      });
		
	$('.remove_team_ass').live('click', function() {
              var element = $(this); //Save the link in a variable called element
    	      var del_id = element.attr("id"); //Find the id of the link that was clicked
              var info = 'id=' + del_id;
              if(confirm("Are You Sure Want to Delete ! ")){
                $.ajax({
            		type: "POST",
            		url : "<?=site_url()?>/project/delete_team/",
            		data: info,
            		success: function(){
            		}
            	 });
                $(this).parents(".team_ass").animate({ opacity: "hide" }, "slow");
              }  
                return false;
        });	
        
        
	});
	</script>	