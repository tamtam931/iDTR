<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtrmodel_leave extends CI_Model {

    function __construct() {
        parent::__construct();

    }

    public function Connect($host,$db,$username,$password){

		try{

			$conn = new PDO("sqlsrv:Server=".$host.";Database=".$db."","".$username."","".$password."");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (Exception $e){

			die(print_r($e->getMessage()));
		}

		return $conn;    	
    }

    public function findOrfail($id,$formData){

    	$conn = false;

    	
    	if (sizeof($formData) < 5) {
    		
    		$conn = $this->Connect($formData['host'],$formData['db'],$formData['username'],$formData['password']);
    	}


		if ($id && $conn) {

			$query = "SELECT Balances, LeaveCode FROM tbl_fmLeaveBalance WHERE EmpCode = '".$id."' AND ACTIVE = 1 AND LeaveCode IN ('EL','SL','VL')";
			$res = $conn->prepare($query);
			$res->execute();

			$data = $res->fetchAll(PDO::FETCH_BOTH);

			return $data;

		}

		return false;

    }
	
}