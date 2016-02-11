<?php

function NumberFormat($number) {
	if($number % 8 == 0):
		$number = $number/8;
		$formatNumber = ROUND($number,0);
	else:
		$number = ROUND($number/8,0);
		$formatNumber = number_format($number,0);
	endif;	
	return $formatNumber;
}

function Number($number){
	if($number<>0)
		$formatnumber=$number;
	else
		$formatnumber='';
	return $formatnumber;	
}

function getRangeDate($dayStart,$monthStart,$yearStart,$dayEnd,$monthEnd,$yearEnd){
		$d2=GregorianToJD($monthEnd,$dayEnd,$yearEnd);
		$d1=GregorianToJD($monthStart,$dayStart,$yearStart);
		return $d = $d2 - $d1;
}

function getHoliday($year,$month,$day){
    $date = $year."/".$month."/".$day;
    $datename = date('l', strtotime($date));
    if(($datename=="Sunday")||($datename=="Saturday"))
        return true;
    else
        return false;
}

/** Auto Code**/
function autocode($num){
        if($num<10)
            $string = '00'.$num;
        elseif ($num<100)
            $string = '0'.$num; 
        elseif ($num<1000)
            $string = $num;  
		return $string;    
}

function LeaveTimesheetDay($job_id,$hour){
	if((($job_id>=4) && ($job_id<=12)) || ($job_id==17))
	{
		if($hour>=4)
			$day = 1;
		else
			$day = '';
	} else
	{
		$day = '';	
	}
	return $day;
}

function digit($num) {
	if ($num>10)
		$num = $num;
	else 
		$num = '0'.$num;
	return $num;
}