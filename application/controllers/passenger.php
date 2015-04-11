<?php
class Passenger extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('mdl_request');
	}
	public function pick_me(){
		$this->load->view('passenger/pick_me');
	}
	public function add_request(){
		if(isset($_SESSION['user_id'])){
			$form_data = array (
				'departure' => $_POST['departureCoord'],
				'destination' => $_POST['destinationCoord'],
				'owner_id' => $_SESSION['user_id'],
				'from_time' => strtotime($_POST['startDate'].' '.$_POST['startTime'].':00'),
				'to_time' => strtotime($_POST['startDate'].' '.$_POST['finishTime'].':00'),
				'regular' => $_POST['frequencySwitchGroup'] ? 1 : 0,
				'passengers' => $_POST['male_quantity'] + $_POST['female_quantity'],
				'extra' => $_POST['extra']
			);
			$status = $this->mdl_request->add_request($form_data);
			if($status){
				$this->load->view('passenger/pick_me');
			}
		}
	}
}
?>