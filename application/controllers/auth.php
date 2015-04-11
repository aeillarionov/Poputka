<?php
class Auth extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
	}
	public function index() {
		$client_id = '4871758';
		$client_secret = '2pMyltGbp4gdczcKt36f';
		$redirect_uri = 'http://3942766f.ngrok.com/Poputka/';
		
		$url = 'http://oauth.vk.com/authorize';
		
		$params = array(
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code'
		);
		$data['link'] = $url.'?'.urldecode(http_build_query($params));
		$this->load->view('auth/index', $data);
	}
	public function logout() {
		session_destroy();
		redirect('http://3942766f.ngrok.com/Poputka/');
	}
}
?>