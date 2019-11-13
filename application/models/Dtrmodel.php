<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtrmodel extends CI_Model {
     
	//public $table = "tEAL_TEMP";
    function __construct() {
        parent::__construct();

    }

    public function All($access_no=null,$dateStart=null,$dateEnd=null,$limit=null,$start=0){

        $this->db->select("*");
        $this->db->from("vEmployeeAttendanceLogFederal");

        if ($access_no) {
            
            $where = array("vEmployeeAttendanceLogFederal.BiometricID" => "$access_no");
            $this->db->where($where);
            
        } else {

            return false;
        }

        if($dateStart && $dateEnd){

            if ($dateStart < $dateEnd) {
                
                $where = "convert(varchar,vEmployeeAttendanceLogFederal.DateTime,23) BETWEEN convert(varchar,'$dateStart',23) and convert(varchar,'$dateEnd',23)";
                $this->db->where($where);
            
            } else {

                $where = "convert(varchar,vEmployeeAttendanceLogFederal.DateTime,23) = '$dateStart'";
                $this->db->where($where);                
            }         

        }

        $this->db->order_by("vEmployeeAttendanceLogFederal.DateTime", "DESC");
        $this->db->limit($limit,$start);

    	$query = $this->db->get();
        $rowcount = $query->num_rows();

    	if($rowcount > 0){

			 return $query->result_array();

    	} else {

    		return false;
    	}
    }

    public function count($access_no,$dateStart=null,$dateEnd=null){

        if ($access_no) {
            
            $this->db->select("COUNT(BiometricID) as IO");
            $this->db->from("vEmployeeAttendanceLogFederal");
            if ($access_no) {
                
                $where = array("BiometricID" => "$access_no");
                $this->db->where($where);
                
            }
            if($dateStart && $dateEnd){

                if ($dateStart < $dateEnd) {
                    
                    $where = "convert(varchar,DateTime,23) BETWEEN convert(varchar,'$dateStart',23) and convert(varchar,'$dateEnd',23)";
                    $this->db->where($where);
                
                } else {

                    $where = "convert(varchar,DateTime,23) = '$dateStart'";
                    $this->db->where($where);                
                }         

            }            
            $query = $this->db->get();
            
            return $query->result_array();                       

        }

        return false;
    }

}