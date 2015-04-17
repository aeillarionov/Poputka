<?php /*ALTER TABLE pptk_users_auth AUTO_INCREMENT 1 ALTER TABLE pptk_users_info AUTO_INCREMENT 1*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
	class Driver extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model('mdl_request');
			$this->load->model('mdl_route');
			$this->load->model('mdl_auth');
		}
		public function index(){
			if(isset($_GET['code'])){
				$params = array(
					'client_id' => '4871758',
					'client_secret' => '2pMyltGbp4gdczcKt36f',
					'code' => $_GET['code'],
					'redirect_uri' => 'http://localhost/Poputka'
				);

				$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
				
				if (isset($token['access_token'])) {
					$params = array(
						'uids'         => $token['user_id'],
						'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_max',
						'access_token' => $token['access_token']
					);
				}
				$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
				if (isset($userInfo['response'][0]['uid'])) {
					$userInfo = $userInfo['response'][0];
					$this->mdl_auth->auth($userInfo);
				}
			}
			$this->load->view('driver/index');
		}
		public function show_requests(){
			if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
				$data['requests'] = $this->mdl_request->show_all();
				$data['mode'] = 0; /*Switch of tabs: Show hitchers|New route, 0 -> Show hitchers is active*/
				$arg['title'] = 'Водителям | Попутчики';
				$this->load->view('templates/header', $arg);
				$this->load->view('templates/topbar_driver');
				$this->load->view('driver/show_requests', $data);
				$this->load->view('templates/footer');
			}
		}
		public function show_all_requests_list(){
			if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
				$data['requests'] = $this->mdl_request->show_all();
				$this->load->view('driver/all_requests_list', $data);
			}
		}
		public function add_route_page(){
			if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
				$data['requests'] = $this->mdl_request->show_all();
				$data['mode'] = 1; /*Switch of tabs: Show hitchers|New route, 1 -> New route is active*/
				$arg['title'] = 'Водителям | Попутчики';
				$this->load->view('templates/header', $arg);
				$this->load->view('templates/topbar_driver');
				$this->load->view('driver/show_requests', $data);
				$this->load->view('templates/footer');
			}
		}
		public function add_route(){
			if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
				$dep_coord = explode(',', $_POST['departureCoord']);
				$des_coord = explode(',', $_POST['destinationCoord']);
				$geo_data = array (
					'dep_lat' => 0 + $dep_coord[0],
					'dep_lon' => 0 + $dep_coord[1],
					'des_lat' => 0 + $des_coord[0],
					'des_lon' => 0 + $des_coord[1],
				);
				$route_data = array (
					'owner_id' => $_SESSION['user_id'],
					'from_time' => strtotime(str_replace('/', '-', $_POST['startDate']).' '.$_POST['startTime']),
					'to_time' => strtotime(str_replace('/', '-', $_POST['startDate']).' '.$_POST['finishTime']),
					'regular' => $_POST['frequencySwitchGroup'] ? 1 : 0,
					'spots' => $_POST['passangers_quantity'],
					'extra' => $_POST['extra'],
				);
				$status = $this->mdl_route->add_route($geo_data, $route_data);
				if($status){
					$data['mode'] = 1;
					$arg['title'] = 'Водителям | Попутчики';
					$this->load->view('templates/header', $arg);
					$this->load->view('templates/topbar_driver');
					$this->load->view('driver/show_requests', $data);
					$this->load->view('templates/footer');
				}
			}
		}
	}
?>