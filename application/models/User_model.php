<?php 
/**
* 
*/

require APPPATH . '/libraries/JWT.php';
use \Firebase\JWT\JWT;

class User_model extends CI_Model {

	public function check_select() {
			
		$query = $this->db->query("SELECT * FROM restaurants WHERE 1");

		foreach ($query->result() as $key => $value) {
			$ID_restaurant = $query->result()[$key]->ID_restaurant;
			$menu = $this->db->query("SELECT * FROM foods WHERE ID_restaurant = $ID_restaurant");
			$query->result()[$key]->menu = $menu->result();
		}

		return $query->result();

	}

	public function create_token($username, $password){
		$token['username'] = $username;
		$token['password'] = $password;
		$date = new DateTime();
		$token['iat'] = $date->getTimestamp();
		$token['exp'] = $date->getTimestamp() + 60*60*5;
		$output['id_token'] = JWT::encode($token,"my Secret key!");
		
		return $output['id_token'];
	}
	
	public function registration($data) {
		$token = $this->create_token($data['username'],$data['password']);
		$data['token'] = $token;
		$condition = "username = '".$data['username']."'";
		$query = $this->db
				->select('*')
				->from('users')
				->where($condition)
				->limit(1)
				->get();

		if ($query->num_rows() == 0) {
			$this->db->insert('users', $data);
			$id = $this->db
				->select('ID_user')
				->from('users')
				->where($condition)
				->limit(1)
				->get();
			
			return $token;
		}else {
			return FALSE;
		}
	}

	public function confirm($token) {
		
		$data['status'] = 1;

		$this->db->where('token', $token);
		$this->db->update('users', $data);
		
		die();
	}

	public function delete($token) {
		
		$this->db->delete('users', array('token' => $token));
		die();
	}

	public function login($data) {
		$condition = "username= '".$data['username']."'";
		$query = $this->db
				->select('*')
				->from('users')
				->where($condition)
				->limit(1)
				->get();

		if ($query->num_rows() == 1) {
			$password = $data['password'];
			if (password_verify($password, $query->row('password'))) {

				if ($query->row('status') == 0) {
					return 'account hasnt confirmed';
				}

				$token = $query->row('token');
				$id = $query->row('ID_user');
				$fullname = $query->row('fullname');
				$nickname = $query->row('nickname');

				if ($token === NULL) {
					$token = $this->create_token($data['username'],$data['password']);

					$this->db->where($condition);
					$this->db->update('users', array('token' => $token));

				}

				$output = array(
					'token' => $token,
					'id' => $id,
					'fullname' => $fullname,
					'nickname' => $nickname
				);

				return $output;
			}else {
				return FALSE;
			}
		}else {
			return FALSE;
		}
	}

	public function check_token($token) {
		$condition = "token= '".$token."'";
		$result = $this->db
					->select('ID_user, fullname, nickname, token')
					->from('users')
					->where($condition)
					->limit(1)
					->get();

		if ($result->num_rows() == 1) {
			return $result->result();
		}else {
			return FALSE;
		}
	}

	public function select_restaurant($ID_restaurant, $token) {
		$res = $this->db
						->select('ID_user')
						->from('users')
						->where("token = '$token'")
						->get()->result();
		if ($res == NULL) {
			$query = $this->db->query("SELECT R.ID_restaurant, R.restaurant_name, R.location, R.location_latitude, R.location_longitude, R.phone, R.open, R.close, R.photo, (SELECT COUNT(*) FROM link_users_restaurants WHERE link_users_restaurants.love = 1 AND link_users_restaurants.ID_restaurant = R.ID_restaurant) AS 'Popularity' FROM restaurants R WHERE ID_restaurant = $ID_restaurant");
		}else {
			$ID_user = $res[0]->ID_user;
			$query = $this->db->query("SELECT R.ID_restaurant, R.restaurant_name, R.location, R.location_latitude, R.location_longitude, R.phone, R.open, R.close, R.photo, L.love, L.favorite, (SELECT COUNT(*) FROM link_users_restaurants WHERE link_users_restaurants.love = 1 AND link_users_restaurants.ID_restaurant = R.ID_restaurant) AS 'Popularity' FROM restaurants R, link_users_restaurants L WHERE L.ID_user = $ID_user AND R.ID_restaurant = L.ID_restaurant AND R.ID_restaurant = $ID_restaurant");
		}
		
		foreach ($query->result() as $key => $value) {
			//$ID_restaurant = $query->result()[$key]->ID_restaurant;
			$menu = $this->db->query("SELECT * FROM foods WHERE ID_restaurant = $ID_restaurant");
			$query->result()[$key]->menu = $menu->result();
		}

		return $query->result();
	}

	public function select_all_restaurant($token) {
		$res = $this->db
						->select('ID_user')
						->from('users')
						->where("token = '$token'")
						->get()->result();
		if ($res == NULL) {
			$query = $this->db->query("SELECT R.ID_restaurant, R.restaurant_name, R.location, R.open, R.close, R.photo, (SELECT COUNT(*) FROM link_users_restaurants WHERE link_users_restaurants.love = 1 AND link_users_restaurants.ID_restaurant = R.ID_restaurant) AS 'Popularity' FROM restaurants R WHERE 1");

		}else {
			$ID_user = $res[0]->ID_user;
			/*$query = $this->db->query("SELECT R.ID_restaurant, R.restaurant_name, R.location, R.open, R.close, R.photo, L.love, L.favorite, (SELECT COUNT(*) FROM link_users_restaurants WHERE link_users_restaurants.love = 1 AND link_users_restaurants.ID_restaurant = R.ID_restaurant) AS 'Popularity' FROM restaurants R, link_users_restaurants L WHERE L.ID_user = $ID_user AND R.ID_restaurant = L.ID_restaurant ORDER BY Popularity DESC");*/
			$query = $this->db->query("SELECT R.ID_restaurant, R.restaurant_name, (SELECT COUNT(*) FROM link_users_restaurants WHERE link_users_restaurants.love = 1 AND link_users_restaurants.ID_restaurant = R.ID_restaurant) AS 'Popularity' FROM restaurants R WHERE 1 ORDER BY Popularity DESC");

			foreach ($query->result() as $key => $value) {
				$ID_restaurant = $query->result()[$key]->ID_restaurant;
				
				$q = $this->db->query("SELECT love, favorite FROM link_users_restaurants WHERE ID_restaurant = $ID_restaurant AND ID_user = $ID_user");
				
				if ($q->result() != NULL) {
					$love = $q->result()[0]->love;
					$favorite = $q->result()[0]->favorite;
				}else {
					$love = 0;
					$favorite = 0;
				}
				$query->result()[$key]->love = $love;
				$query->result()[$key]->favorite = $favorite;
			}
		}


		return $query->result();
	}

	public function likeOrFav($ID_restaurant, $token, $stat) {

		$ID_user = $this->db
						->select('ID_user')
						->from('users')
						->where("token = '$token'")
						->get()->result()[0]->ID_user;

		$condition = "link_users_restaurants.ID_restaurant = '$ID_restaurant' AND link_users_restaurants.ID_user = '$ID_user'";
		$likeOrFav = $this->db
					->select($stat)
					->from('link_users_restaurants')
					->where($condition)
					->get();
		if ($likeOrFav->num_rows() == 1) {
			$likeOrFav = $likeOrFav->result()[0]->$stat;
		}else {
			$likeOrFav = 0;
		}
		
		if ($likeOrFav == 0) {
			$query = $this->db->query("UPDATE link_users_restaurants SET $stat = 1 WHERE ID_restaurant = $ID_restaurant AND ID_user = ID_user");
		}else {
			$query = $this->db->query("UPDATE link_users_restaurants SET $stat = 0 WHERE ID_restaurant = $ID_restaurant AND ID_user = ID_user");
		}

		if ($query) {
			return TRUE;
		}else {
			return FALSE;
		}
	}

	public function select_email($token) {
		$email = $this->db
					->select('email')
					->from('users')
					->where("token = '$token'")
					->get()->result()[0]->email;

		if (!empty($email)) {
			return $email;
		}else {
			return FALSE;
		}
	}

	public function change_pass($old_pass, $new_pass, $token) {
		$query = $this->db
					->select('*')
					->from('users')
					->where("token = '$token'")
					->get();

		if ($query->num_rows() == 1) {
			$password = $old_pass;
			if (password_verify($password, $query->row('password'))) {
				
				$result = $this->db
							->set('password', $new_pass)
							->where("token = '$token'")
							->update('users');


				return $result;
			}else {
				return FALSE;
			}
		}else {
			return FALSE;
		}
	}

	public function forgot_pass($new_pass, $token) {
		$data['password'] = $new_pass;
		$result = $this->db
					->where('token', $token)
					->update('users', $data);

		return $result;
	}

	/*public function select_favorite($token) {
		
	}*/

}
 ?>