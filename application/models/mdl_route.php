<?php
	class Mdl_route extends CI_Model {
		function __construct(){
			parent::__construct();
			$this->load->database();
		}
		public function show_all(){
			$i=0;
			$this->db->order_by('route_id', 'desc');
			$query = $this->db->get('routes');
			$req_arr = $query->result_array();
			
			foreach ($req_arr as $req):
				$owner_id = $req['owner_id'];
				$this->db->where('user_id', $owner_id);
				$query = $this->db->get('users_info');
				$res = $query->row_array();
				$req_arr[$i]['pic_url'] = $res['pic_url'];
				$i++;
			endforeach;
			
			return $req_arr;
		}
		public function add_route($form_data){
			$query = $this->db->insert('routes', $form_data);
			return $this->db->insert_id();
		}
	}
?>