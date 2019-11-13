<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('DtrModel');
		$this->load->model('Dtrmodel_leave');
		$this->load->model('Dtrmodel_timekeep');
		$this->load->model('Dtrmodel_Encode');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->driver('cache');
	}

	public function index()
	{	
		if ($this->input->is_ajax_request() && $this->input->get()) {

			$access_no = $this->input->get("access_no");
			$date_start = $this->input->get("date_start");
			$date_end = $this->input->get("date_end");
			//Get Time Record
			//$data['result'] = $this->DtrModel->All($access_no,$date_start,$date_end);

			$numRow = $this->DtrModel->count($access_no,$date_start,$date_end);

			$totalRow = $numRow[0]["IO"];
			$Perpage = $numRow[0]["IO"];
			//$links = 2;

			if($totalRow >= 10){

				$Perpage = 10;
			}

			$paginate = $this->paginateBld($totalRow,$Perpage);
			if ($paginate) {

				$this->pagination->initialize($paginate);
            	$page = ($this->uri->segment(1) ? $this->uri->segment(1) : 0);
            	$start = ($page - 1) * $paginate['per_page'];

			}


            $output = array(
            			'pagination_link' => $this->pagination->create_links(),
            			'result' => $this->DtrModel->All($access_no,$date_start,$date_end,$paginate['per_page'],$start)
            		);

            echo json_encode($output); 						

		} else {

			$this->load->view('header');
			$this->load->view('home');
			$this->load->view('footer');

		}
		
	}
	

	public function show()
	{

		$validation = array('success' => 'false','messages' => array());
		$this->form_validation->set_rules('access_no','Access Number','required|trim|numeric');
		$this->form_validation->set_rules('date_start','Date Start','required|trim');
		$this->form_validation->set_rules('date_end','Date End','required|trim|callback_compareDate');
		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

		if ($this->input->is_ajax_request()) {

			if ($this->form_validation->run() == TRUE) {

				$access_no = $this->input->post("access_no");
				$date_start = $this->input->post("date_start");
				$date_end = $this->input->post("date_end");			

				//find leave balances
				$formData = $this->dbCredentials("db_0820193");
				$code = $this->Dtrmodel_timekeep->findOrfail($access_no,$formData);
				
				if ($code) {
					
					$data['balances'] = $this->Dtrmodel_leave->findOrfail($code[0]['EmpCode'],$formData);

				} else {

					$data['balances'] = false;
				}																				

				$this->load->view('result',$data);

			} else {

				foreach ($this->input->post() as $key => $value) {
					
					$validation['messages'][$key] = form_error($key);
				}

				$validation['success'] = 'false';
				$validation['key'] = 'error';
				echo json_encode($validation);
			}
			
		} else if($this->input->get()){

				$this->index();
		}

	}

	private function genpass($db_code=null){

	    $username = 'admin';
	    $password = '1234';
	     
	    $header = [
	    	'Content-Type' => 'application/json; charset=utf-8'
	    ];
	    // Alternative JSON version
	    // $url = 'http://twitter.com/statuses/update.json';
	    // Set up and execute the curl process
	    $result = false;

	    if ($db_code) {
	    	
	    	$genpassUrl = "http://localhost/genpass/api/db/".$db_code."";
		    $curl_handle = curl_init();
		    curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		    curl_setopt($curl_handle, CURLOPT_URL, $genpassUrl);
		    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header);
		    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
		    curl_setopt($curl_handle, CURLOPT_POST, false);	    
		     
		    // Optional, delete this line if your API is open
		    curl_setopt($curl_handle, CURLOPT_USERPWD,$username.':'.$password);
		    $status_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
		     
		    $buffer = curl_exec($curl_handle);
		    curl_close($curl_handle);
		 	
		 	$result = json_decode($buffer,true);	    	
	    }

	 
	    return $result;
	}

	private function dbCredentials($db_code){

		$genpass = $this->genpass($db_code);
		if ($genpass) {
			
			$password = $this->Dtrmodel_Encode->GPDecrypt($genpass['db']['p_code'],$this->config->item('encryption_key'));

			$formData = [

				'host' => $genpass['db']['db_host'],
				'db' => $genpass['db']['db_name'],
				'username' => $genpass['db']['p_username'],
				'password' => $password
			];

			return $formData;
		}

		return false;		
	}


	function compareDate(){

		$startDate = strtotime($this->input->post("date_start"));
		$endDate = strtotime($this->input->post("date_end"));

		if ($endDate >= $startDate) {
			
			return true;

		} else {

			$this->form_validation->set_message('compareDate','Inputed Date on Date Start should be earlier or same as Date End');
			return false;
		}
	}


	function paginateBld($totalRow,$Perpage){

    	$config['base_url'] = '#';
    	$config['total_rows'] = $totalRow;
    	$config['per_page'] = $Perpage;
    	//$config['uri_segment'] = 1;
    	$config['use_page_numbers'] = TRUE;
    	$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
    	$config['full_tag_close'] = '</ul>';
    	$config['first_tag_open'] = '<li class="page-link">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-link">';
        $config['last_tag_close'] = '</li>';    
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="page-link">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link']    = '&lt;';
        $config['prev_tag_open'] = '<li class="page-link">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-link active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-link">';
        $config['num_tag_close'] = '</li>';
        $config['num_links'] = 5;
        
        if ($totalRow && $Perpage) {
        	

        	return $config;
        }

        return false;	
	}


}
