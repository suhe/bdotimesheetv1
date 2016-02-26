<?php $this->load->view('site_header');?>
<style>
.center{text-align:center}	
</style>
<div class="grid_12">
	<h2 id="page-spacer"></h2>
<table align="center">	
<tr>
	<td valign="top" style="vertical-align:top;">
	<form id="form" method="POST" action="<?=base_url()?>report/reportOutsourceWeek/" >
		<fieldset class="form-fieldset">
		<legend class="form-legend">Outsource Report by Week</legend>
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
<a href="<?=site_url('report/reportOutsourceWeekExcel')?>" style="cursor:pointer;"><b>Export<b></a>
<div id="inner"></div>
<div id="excel">
<div id="tables" class="block">
<fieldset class="form-fieldset">
<legend class="form-legend">Outsource Report by Week</legend>
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
		SS : Self Study,
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
  <th class="table-head" colspan="<?=4+($x*9) +9?>" >  Absent by Periode</th>
</tr>
<tr>
	<th rowspan="2">No</th>
	<th rowspan="2">NIK</th>
	<th rowspan="2" style="width:180px">Name</th>
	<th rowspan="2">Jabatan</th>
    <?php for($is=$y;$is<=$x;$is++): 
	if($is<=$xmin){
			$i = $is;
			
        }
        else {
			$i = $is-$xmin;
			
        } 		
	?> 
	
	<th colspan="9" style="text-align:center">Minggu <?=$i?></th>
    <?php endfor;?>
	<th colspan="9" style="text-align:center">Total</th>
</tr>

<tr>

<?php for($is=$y;$is<=$x;$is++):
    if($is<=$xmin){
			$i = $is;
			//$year = $year;
        }
        else {
			$i = $is-$xmin;
			//$year = $year + 1;
    } 		
?>
	<th>DK</th>
	<th>LK</th>
	<th>SS</th>
	<th>S</th>
	<th>I</th>
	<th>C</th>
	<th>L</th>
	<th>TK</th>
	<th>OT</th>
<?php endfor;?>
	
	<th>DK</th>
	<th>LK</th>
	<th>SS</th>
	<th>S</th>
	<th>I</th>
	<th>C</th>
	<th>L</th>
	<th>TK</th>
	<th>OT</th>
</tr>
</thead>
<tbody>
    <?=$content_report?>
<tr>
	<td colspan="<?=4+($x*9)+9?>"><i>printed date <?=date("d M Y H:i:s");  ?></i></td>
</tr>

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