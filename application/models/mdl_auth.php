<?php
class Mdl_auth extends CI_Model {
	function __construct(){
				parent::__construct();
				$this->load->database();
			}
	public function auth($userInfo) {
		$this->db->where('social_id',$userInfo['uid']);
		$query = $this->db->get('users_auth');
		if($query->num_rows()){
			$row = $query->row_array();
			$this->db->where('user_id',$row['db_id']);
			$query = $this->db->get('users_info');
			$user = $query->row_array();
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['name'] = $user['name'];
			$_SESSION['surename'] = $user['surename'];
			$_SESSION['driver'] = $user['numberplate'] ? 1 : 0;
			$_SESSION['pic_url'] = $user['pic_url'];
		} else {
			$user = array (
				'name' => $userInfo['first_name'],
				'surename' => $userInfo['last_name'],
				'pic_url' => $userInfo['photo_max']
			);
			$this->db->insert('users_info', $user);
			$user_auth = array (
				'social_id' => $userInfo['uid'],
				'db_id' => $this->db->insert_id()
			);
			$this->db->insert('users_auth', $user_auth);
			$_SESSION['user_id'] = $user_auth['db_id'];
			$_SESSION['name'] = $user['name'];
			$_SESSION['surename'] = $user['surename'];
			$_SESSION['pic_url'] = $user['pic_url'];
		}
	}
}
?>