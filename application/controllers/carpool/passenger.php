<?php
class Passenger extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('mdl_request');
		$this->load->model('mdl_route');
	}
	public function pick_me(){
		$data['routes'] = $this->mdl_route->show_all();
		$data['mode'] = 1;
		$arg['title'] = 'Подвези меня | Попутчики';
		$this->load->view('templates/header', $arg);
		$this->load->view('templates/topbar_passenger');
		$this->load->view('passenger/pick_me', $data);
		$this->load->view('templates/footer');
	}
	public function show_routes(){
		if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
			$data['routes'] = $this->mdl_route->show_all();
			$data['mode'] = 0; /*Switch of tabs: Show drivers|New route, 0 -> Show drivers is active*/
			$arg['title'] = 'Подвези меня | Попутчики';
			$this->load->view('templates/header', $arg);
			$this->load->view('templates/topbar_passenger');
			$this->load->view('passenger/pick_me', $data);
			$this->load->view('templates/footer');
		}
	}
	public function show_all_routes_list(){
			if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
				$data['routes'] = $this->mdl_route->show_all();
				$this->load->view('passenger/all_routes_list', $data);
			}
		}
	public function add_request(){
		if(isset($_SESSION['user_id'])){
			$form_data = array (
				'departure' => $_POST['departureCoord'],
				'destination' => $_POST['destinationCoord'],
				'owner_id' => $_SESSION['user_id'],
				'from_time' => strtotime(str_replace('/', '-', $_POST['startDate']).' '.$_POST['startTime']),
				'to_time' => strtotime(str_replace('/', '-', $_POST['startDate']).' '.$_POST['finishTime']),
				'regular' => $_POST['frequencySwitchGroup'] ? 1 : 0,
				'passengers' => $_POST['male_quantity'] + $_POST['female_quantity'],
				'extra' => $_POST['extra']
			);
			$status = $this->mdl_request->add_request($form_data);
			if($status){
				$data['mode'] = 1;
				$arg['title'] = 'Подвези меня | Попутчики';
				$this->load->view('templates/header', $arg);
				$this->load->view('templates/topbar_passenger');
				$this->load->view('passenger/pick_me', $data);
				$this->load->view('templates/footer');
			}
		}
	}
}
?>