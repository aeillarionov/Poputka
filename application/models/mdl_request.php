<?php
	class Mdl_request extends CI_Model {
		function __construct(){
			parent::__construct();
			$this->load->database();
		}
		public function show_all(){
			$i=0;
			$this->db->order_by('request_id', 'desc');
			$query = $this->db->get('requests');
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
		public function showMyRequests(){
			$owner_id = $_SESSION['user_id'];
			$this->db->where('user_id', $owner_id);
			$query = $this->db->get('users_info');
			$res = $query->row_array();
			$pic_url = $res['pic_url'];
			$this->db->order_by('request_id', 'desc');
			$this->db->where('owner_id', $owner_id);
			$query = $this->db->get('requests');
			$req_arr = $query->result_array();
			$i=0;
			foreach($req_arr as $req):
				$point_id = $req['point_id'];
				$this->db->where('point_id', $point_id);
				$query = $this->db->get('points');
				$res = $query->row_array();
				$req_arr[$i]['index'] = $i+1;
				$req_arr[$i]['pic_url'] = $pic_url;
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
		public function show_nearest($lat, $lon){
			$res_arr = array();
			$lat_min = $lat - 0.05;
			$lat_max = $lat + 0.05;
			$lon_min = $lon - 0.05;
			$lon_max = $lon + 0.05;
			$this->db->where('dep_lat >',$lat_min);
			$this->db->where('dep_lat <',$lat_max);
			$this->db->where('dep_lon >',$lon_min);
			$this->db->where('dep_lon <',$lon_max);
			$query = $this->db->get('points');
			$req_arr = $query->result_array();
			$i=0;
			foreach($req_arr as $req):
				$point_id = $req['point_id'];
				$this->db->where('point_id', $point_id);
				$query = $this->db->get('requests');
				$res = $query->row_array();
				if(empty($res)){
					continue 1;
				} else {
					$res_arr[$i]['dep_lat'] = $req['dep_lat'];
					$res_arr[$i]['dep_lon'] = $req['dep_lon'];
					$res_arr[$i]['dep_addr'] = $req['dep_addr'];
					$res_arr[$i]['des_lat'] = $req['des_lat'];
					$res_arr[$i]['des_lon'] = $req['des_lon'];
					$res_arr[$i]['des_addr'] = $req['des_addr'];
					$owner_id = $res['owner_id'];
					$res_arr[$i]['request_id'] = $res['request_id'];
					$res_arr[$i]['owner_id'] = $owner_id;
					$res_arr[$i]['from_time'] = $res['from_time'];
					$res_arr[$i]['to_time'] = $res['to_time'];
					$res_arr[$i]['regular'] = $res['regular'];
					$res_arr[$i]['passengers'] = $res['passengers'];
					$res_arr[$i]['extra'] = $res['extra'];
					$this->db->where('user_id', $owner_id);
					$query = $this->db->get('users_info');
					$res = $query->row_array();
					$res_arr[$i]['pic_url'] = $res['pic_url'];
					$i++;
				}
			endforeach;
			
			return $res_arr;
		}
		public function add_request($geo_data, $request_data){
			$query = $this->db->insert('points', $geo_data);
			$request_data['point_id'] = $this->db->insert_id();
			$query = $this->db->insert('requests', $request_data);
			return $this->db->insert_id();
		}
	}
?>