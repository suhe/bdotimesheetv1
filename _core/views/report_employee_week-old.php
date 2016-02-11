<?php $this->load->view('site_header');?>

<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=$site ?>/report/reportEmployeeWeek/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Employee Report by Week</legend>
		<table align=center>
		<tr><td></tr>
		

		<tr>
			<td class="label">Periode : </td>
			<td>
			  <input type="text"  class="inputtext date"  id="date_from" name="date_from" value="<?=$form['date_from']?>" size="60" style='width:75px;' />
			  <input type="text" class="inputtext" readonly="true" id="week" name="week" value="<?=$form['week']?>" size="40" style='width:20px;'/>
			     
               / 
			   <input type="text"  class="inputtext date" id="date_to" name="date_to" value="<?=$form['date_to'] ?>" size="60" style='width:75px;' />
               <input type="text" class="inputtext" readonly="true" id="week2" name="week2" value="<?=$form['week2']?>" size="40" style='width:20px;'/>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><div class="ff3 UILinkButton">
					<input type="submit"  id="submit"  value="View Report" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				<div class="ff3 UILinkButton" style="padding-left:10px;">
					<input type="button"  id="back"  value="Back" class="ff3 UILinkButton_A"/>
					<div class="UILinkButton_RW">
						<div class="UILinkButton_R"/></div>
					</div>
				</div>
				
				</td>
		</table>	
		</fieldset>
	</form>	
</table>
<!--<a id=export style="cursor:pointer;"><b>Export to EXCEL<b></a>-->
<a href="<?=site_url('report/reportEmployeeWeekExcel')?>" style="cursor:pointer;"><b>Export<b></a>
<div id="inner"></div>
<div id="excel">
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Employee Report by Week</legend>
<table align=center>
<tr><td></tr>

<tr>
	<td class="label">Periode : </td>
	<td><?=$form['date_from'] ?> to <?=$form['date_to'] ?>
	</td>
</tr>

<tr>
	<td class="label">Keterangan : </td>
	<td>
        DK : Dalam Kota Per Hari, 
        LK : Luar Kota Per Hari,
        S  : Sakit Per Hari,
        I  : Ijin Per Jam
        C  : Cuti & Ijin >=4 jam
        L  : Libur Per Hari
        OT : Lembur Per Jam
         
	</td>
</tr>

<tr>
	<td class="label">Libur : </td>
	<td>
        <?php 
          if(isset($holidays)):  
          $str = '';
          foreach ($holidays as $k=>$v):
            $str.= $v['date'].':'.$v['descr'].','; 
          endforeach;
          
          print $str;
          endif;
          ?>
	</td>
</tr>

</table>	
		
<table class="grid" style="font-size:10px">
<thead>
<tr>
  <th class="table-head" colspan="<?=5*($x*5)?>">Absent by Period</th>
</tr>
<tr>
	<th rowspan="2">No</th>
	<th rowspan="2">NIK</th>
	<th rowspan="2">Name</th>
	<th rowspan="2">Jabatan</th>
    <?php for($is=$y;$is<=$x;$is++): 
	if($is<=52){
			$i = $is;
			
        }
        else {
			$i = $is-52;
			
        } 		
	?> 
	
	<th colspan="8" style="text-align:center">Minggu <?=$i?></th>
    <?php endfor;?>
	<th colspan="8" style="text-align:center">Total</th>
</tr>

<tr>

<?php for($is=$y;$is<=$x;$is++):
    if($is<=52){
			$i = $is;
			//$year = $year;
        }
        else {
			$i = $is-52;
			//$year = $year + 1;
    } 		
?>
	<th>DK</th>
	<th>LK</th>
    <th>S</th>
    <th>I</th>
    <th>C</th>
    <th>L</th>
	<th>TK</th>
	<th>OT</th>
<?php endfor;?>
	
	<th>DK</th>
	<th>LK</th>
    <th>S</th>
    <th>I</th>
    <th>C</th>
    <th>L</th>
	<th>TK</th>
	<th>OT</th>
</tr>
	</thead>
	<tbody>
    <?php 
        $no=1;
        //foreach ($groups as $key => $u): ?>
    <tr>
       <td colspan="<?=8*($x*5)?>">KAP</td>
    </tr>
    
    <?php
    foreach ($rows as $k=>$v): 
      if(($v['department_id']<>18) && ($v['department_id']<>7) ):  
    ?>
    <tr>
        <td><?=$no;?></td>
	    <td><?=$v['employeeid']?></td>
	    <td><?=$v['employeefirstname'].' '.$v['employeemiddlename'].' '.$v['employeelastname'];?></td>
	    <td><?=$v['EmployeeTitle']?></td>
        <?php 
        $vnh = 0; 
        $vlk = 0;
        $vhr = 0;
        $vs  = 0;
        $vi  = 0;
        $vc  = 0;
        $vl =  0;
        $vtk = 0;
        $vot = 0;
        for($j=$y;$j<=$x;$j++):
		if($j>52){
			$j=$j-52;
		}
		?>
	    <td><?=Number($v["week_0$j"])?></td>
	    <td><?=Number($v["lweek_0$j"])?></td>
        <td><?=Number($v["sweek_0$j"])?></td>
        <td><?=Number($v["iweek_0$j"])?></td>
        <td><?=Number($v["cweek_0$j"]+$v["icweek_0$j"])?></td>
        <td><?=Number($v["liweek_0$j"])?></td>
	    <td><?=Number($v["tkweek_0$j"])?></td>
	    <td>
        <?php
            if(($v['EmployeeTitle']=='Assistant') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Associate Consultant') ) 
                echo Number($v["oweek_0$j"]); 
            else
                echo '<strike>'.Number($v["oweek_0$j"]).'</strike>';
                      
        ?></td>
        
        <?php 
            $vnh = $vnh + $v["week_0$j"];
            $vlk = $vlk + $v["lweek_0$j"];
            $vs = $vs + $v["sweek_0$j"];
            $vi = $vi + $v["iweek_0$j"];
            $vc = $vc + ($v["cweek_0$j"]+$v["icweek_0$j"]);
            $vl = $vl + $v["liweek_0$j"];
            $vtk = $vtk + $v["tkweek_0$j"];
            $vot = $vot + $v["oweek_0$j"];
        
		if($j<=3){
			$j=$j+52;
		}    
        endfor;?>
        
        <td><?=Number($vnh)?></td>
	    <td><?=Number($vlk)?></td>
	    <td><?=Number($vs)?></td>
        <td><?=Number($vi)?></td>
        <td><?=Number($vc)?></td>
        <td><?=Number($vl)?></td>
        <td><?=Number($vtk)?></td>
	    <td>
        <?php 
            //if(($v['EmployeeTitle']=='Senior-1') || ($v['EmployeeTitle']=='Senior-1') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Supervisor')) 
            if(($v['EmployeeTitle']=='Assistant') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Associate Consultant') )
                 echo Number($vot);
            else
                 echo Number(0);
        ?>
        </td>    
    </tr>
    <?php 
        $no++;
        endif;
        endforeach;?>
 <tr><td colspan="<?=8*($x*5)?>">-</td></tr>
 
    <tr>
       <td colspan="<?=8*($x*5)?>">PT BINADATA OPTIMA TANUBRATA</td>
    </tr>
    
    <?php
    foreach ($rows as $k=>$v): 
      if(($v['department_id']== 18)):  
    ?>
    <tr>
        <td><?=$no;?></td>
	    <td><?=$v['employeeid']?></td>
	    <td><?=$v['employeefirstname'].' '.$v['employeemiddlename'].' '.$v['employeelastname'];?></td>
	    <td><?=$v['EmployeeTitle']?></td>
        <?php 
        $vnh = 0; 
        $vlk = 0;
        $vs = 0;
        $vi = 0;
        $vc = 0;
        $vl = 0;
        $vtk = 0;
        $vot = 0;
        for($j=$y;$j<=$x;$j++):
		if($j>52){
			$j=$j-52;
		}
		?>
	    <td><?=Number($v["week_0$j"])?></td>
	    <td><?=Number($v["lweek_0$j"])?></td>
        <td><?=Number($v["sweek_0$j"])?></td>
        <td><?=Number($v["iweek_0$j"])?></td>
        <td><?=Number($v["cweek_0$j"]+$v["icweek_0$j"])?></td>
        <td><?=Number($v["liweek_0$j"])?></td>
	    <td><?=Number($v["tkweek_0$j"])?></td>
	    <td>
        <?php
            if(($v['EmployeeTitle']=='Assistant') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Associate Consultant') ) 
                echo Number($v["oweek_0$j"]); 
            else
                echo '<strike>'.Number($v["oweek_0$j"]).'</strike>';
                 //echo Number($v["oweek_0$j"]);     
        ?></td>
        
        <?php 
            $vnh = $vnh + $v["week_0$j"];
            $vlk = $vlk + $v["lweek_0$j"];
            $vs = $vs + $v["sweek_0$j"];
            $vi = $vi + $v["iweek_0$j"];
            $vc = $vc + ($v["cweek_0$j"]+$v["icweek_0$j"]);
            $vl = $vl + $v["liweek_0$j"];
            $vtk = $vtk + $v["tkweek_0$j"];
            $vot = $vot + $v["oweek_0$j"];
        
		if($j<=3){
			$j=$j+52;
		} 
		
        endfor;?>
        
        <td><?=Number($vnh)?></td>
	    <td><?=Number($vlk)?></td>
	    <td><?=Number($vs)?></td>
        <td><?=Number($vi)?></td>
        <td><?=Number($vc)?></td>
        <td><?=Number($vl)?></td>
        <td><?=Number($vtk)?></td>
	    <td>
        <?php 
            //if(($v['EmployeeTitle']=='Senior-1') || ($v['EmployeeTitle']=='Senior-1') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Supervisor')) 
            if(($v['EmployeeTitle']=='Assistant') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Associate Consultant') )
                 echo Number($vot);
            else
                 echo Number(0);
        ?>
        </td>    
    </tr>
    <?php 
        $no++;
        endif;
        endforeach;?>
        
        <tr><td colspan="<?=8*($x*5)?>">-</td></tr>
        
       <tr>
         <td colspan="<?=8*($x*5)?>">BDO KONSULTAN INDONESIA</td>
       </tr>
    
    <?php
    foreach ($rows as $k=>$v): 
      if(($v['department_id']== 7)):  
    ?>
    <tr>
        <td><?=$no;?></td>
	    <td><?=$v['employeeid']?></td>
	    <td><?=$v['employeefirstname'].' '.$v['employeemiddlename'].' '.$v['employeelastname'];?></td>
	    <td><?=$v['EmployeeTitle']?></td>
        <?php 
        $vnh = 0; 
        $vlk = 0;
        $vs  = 0;
        $vi  = 0;
        $vc  = 0;
        $vl  = 0;
        $vtk = 0;
        $vot = 0;
        for($j=$y;$j<=$x;$j++):
		if($j>52){
			$j=$j-52;
		}
		?>
	    <td><?=Number($v["week_0$j"])?></td>
	    <td><?=Number($v["lweek_0$j"])?></td>
        <td><?=Number($v["sweek_0$j"])?></td>
        <td><?=Number($v["iweek_0$j"])?></td>
        <td><?=Number($v["cweek_0$j"]+$v["icweek_0$j"])?></td>
        <td><?=Number($v["liweek_0$j"])?></td>
	    <td><?=Number($v["tkweek_0$j"])?></td>
	    <td>
        <?php
            if(($v['EmployeeTitle']=='Assistant') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Associate Consultant') ) 
                echo Number($v["oweek_0$j"]); 
            else
                echo '<strike>'.Number($v["oweek_0$j"]).'</strike>';
                 //echo Number($v["oweek_0$j"]);     
        ?></td>
        
        <?php 
            $vnh = $vnh + $v["week_0$j"];
            $vlk = $vlk + $v["lweek_0$j"];
            $vs = $vs + $v["sweek_0$j"];
            $vi = $vi + $v["iweek_0$j"];
            $vc = $vc + ($v["cweek_0$j"]+$v["icweek_0$j"]);
            $vl = $vl + $v["liweek_0$j"];
            $vtk = $vtk + $v["tkweek_0$j"];
            $vot = $vot + $v["oweek_0$j"];
        
		if($j<=3){
			$j=$j+52;
		}    
        endfor;?>
        
        <td><?=Number($vnh)?></td>
	    <td><?=Number($vlk)?></td>
	    <td><?=Number($vs)?></td>
        <td><?=Number($vi)?></td>
        <td><?=Number($vc)?></td>
        <td><?=Number($vl)?></td>
        <td><?=Number($vtk)?></td>
	    <td>
        <?php 
            //if(($v['EmployeeTitle']=='Senior-1') || ($v['EmployeeTitle']=='Senior-1') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Supervisor')) 
            if(($v['EmployeeTitle']=='Assistant') || ($v['EmployeeTitle']=='Senior-2') || ($v['EmployeeTitle']=='Associate Consultant') )
                 echo Number($vot);
            else
                 echo Number(0);
        ?>
        </td>    
    </tr>
    <?php 
        $no++;
        endif;
        endforeach;?> 
            
<?php //endforeach;?>
    
    
   
  <tr><td colspan="<?=8*($x*5)?>"><i>printed date <?=date("d M Y H:i:s");  ?></i>
	</tbody>
	</table>
</div>
</div>
<script>
// When the page is ready
$(document).ready(function(){
 	$('#employee_id').focus();
	}
);	

function getWeek(value, xdate, inst) {
	nWeek = $.datepick.iso8601Week(xdate);
	$("#week").val(nWeek);
}

function getWeek2(value, xdate, inst) {
	nWeek = $.datepick.iso8601Week(xdate);
	$("#week2").val(nWeek);
}

$(function () {
	$('#back').click( function (e) {
		window.location='<?=$back?>';
	});

	$('#export').click( function (e) {
	  var data = $('#excel').html();
    
    $("#inner").append('<form id="exportform" action="<?=$site ?>/report/excel" method="post" target="_blank"><input type="hidden" id="exportdata" name="exportdata" /></form>');
    $("#exportdata").val(data);
    $("#exportform").submit().remove();
	});
	$('input#date_from').datepick({dateFormat:'dd/mm/yy', showWeeks:true,onClose: getWeek,firstDay: 1, minDate:new Date(2008,1,1)});
    $('input#date_to').datepick({dateFormat:'dd/mm/yy', showWeeks:true,onClose: getWeek2,firstDay: 1, minDate:new Date(2008,1,1)});
	
});

</script>
<?php $this->load->view('site_footer');?>