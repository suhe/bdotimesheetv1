<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class holidayModel extends Model {

    function getHolidayDate($date){
        $SQL = " SELECT * FROM holiday WHERE holiday_date='".$date."'";
        $Q = $this->db->query($SQL);
        return $Q->row_array();
    }
}    