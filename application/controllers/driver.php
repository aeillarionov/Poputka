<?php /*ALTER TABLE pptk_users_auth AUTO_INCREMENT 1 ALTER TABLE pptk_users_info AUTO_INCREMENT 1*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
	class Driver extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model('mdl_request');
			$this->load->model('mdl_auth');
		}
		public function index(){
			if(isset($_GET['code'])){
				$params = array(
					'client_id' => '4871758',
					'client_secret' => '2pMyltGbp4gdczcKt36f',
					'code' => $_GET['code'],
					'redirect_uri' => 'http://3942766f.ngrok.com/Poputka/'
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
					$result = true;
				}
				if($result){
					$this->mdl_auth->auth($userInfo);
				}
			}
			$this->load->view('driver/index');
		}
		public function show_requests(){
			if(isset($_SESSION['user_id'])/* && $_SESSION['driver']==1*/){
				$data['requests'] = $this->mdl_request->show_all();
				$this->load->view('driver/show_requests', $data);
			}
		}
		public function add_route(){
			if(isset($_SESSION['user_id']) && $_SESSION['driver']==1){
				
			}
		}
	}
?>