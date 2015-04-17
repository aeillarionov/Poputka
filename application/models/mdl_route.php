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
				$point_id = $req['point_id'];
				$this->db->where('point_id', $point_id);
				$query = $this->db->get('points');
				$res = $query->row_array();
				$req_arr[$i]['dep_lat'] = $res['dep_lat'];
				$req_arr[$i]['dep_lon'] = $res['dep_lon'];
				$req_arr[$i]['dep_addr'] = $res['dep_addr'];
				$req_arr[$i]['des_lat'] = $res['des_lat'];
				$req_arr[$i]['des_lon'] = $res['des_lon'];
				$req_arr[$i]['des_addr'] = $res['des_addr'];
				$i++;
			endforeach;
			
			return $req_arr;
		}
		public function add_route($geo_data, $route_data){
			$query = $this->db->insert('points', $geo_data);
			$route_data['point_id'] = $this->db->insert_id();
			$query = $this->db->insert('routes', $route_data);
			return $this->db->insert_id();
		}
	}
?>