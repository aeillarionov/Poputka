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
	public function showMyRequests(){
		if(isset($_SESSION['user_id'])){
			$data['requests'] = $this->mdl_request->showMyRequests();
			$this->load->view('passenger/my_requests', $data);
		}
	}
	public function showNearestRoutes($string){
		if(isset($_SESSION['user_id'])){
			$coords_arr = explode('-', $string);
			$lat = +str_replace('_','.',$coords_arr[0]);
			$lon = +str_replace('_','.',$coords_arr[1]);
			$data['routes'] = $this->mdl_route->show_nearest($lat, $lon);
			$this->load->view('passenger/show_nearest', $data);
		}
	}
	public function add_request(){
		if(isset($_SESSION['user_id'])){
			$dep_coord = explode(',', $_POST['departureCoord']);
			$des_coord = explode(',', $_POST['destinationCoord']);
			$geo_data = array (
				'dep_lat' => 0 + $dep_coord[0],
				'dep_lon' => 0 + $dep_coord[1],
				'des_lat' => 0 + $des_coord[0],
				'des_lon' => 0 + $des_coord[1],
			);
			$request_data = array (
				'owner_id' => $_SESSION['user_id'],
				'from_time' => strtotime(str_replace('/', '-', $_POST['startDate']).' '.$_POST['startTime']),
				'to_time' => strtotime(str_replace('/', '-', $_POST['startDate']).' '.$_POST['finishTime']),
				'regular' => $_POST['frequencySwitchGroup'] ? 1 : 0,
				'male_pass' => $_POST['male_quantity'],
				'female_pass' => $_POST['female_quantity'],
				'extra' => $_POST['extra'],
			);
			$status = $this->mdl_request->add_request($geo_data, $request_data);
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