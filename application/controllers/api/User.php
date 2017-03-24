<?php 
/**
* 
*/

require APPPATH . '/libraries/REST_Controller.php';
//require APPPATH . '/libraries/JWT.php';
use Restserver\Libraries\REST_Controller;
//use \Firebase\JWT\JWT;

class User extends REST_Controller {
	
	private $fetched_data;
	private $response_data;
	private $status_code = "";

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('User_model');
	}

	private function set_status($status,$message = NULL) {
		$this->response_data["status"] = $status;
		if ($message != NULL) {
			$this->response_data["message"] = $message;
		}
	}

	private function check_status() {
		if ($this->response_data['status'] == FALSE || $this->response_data['status'] == TRUE) {
			return $this->response_data["status"];
		}
		else
			return $this->response_data["status"] = FALSE;
	}

	private function assign_data() {
		if (!empty($this->fetched_data)) {
			$this->set_status(TRUE);
			$this->response_data["data"] = $this->fetched_data;
		}
		else
			$this->set_status(FALSE,"No Data");
		return $this->response_data;
	}

	public function check_get() {
		

		$this->set_status(TRUE);

		$dummy = $this->User_model->check_select();
		

		$this->fetched_data = $dummy;
		$this->assign_data();

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}
		else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}

		$this->response($this->response_data, $status_code);
	}

	

	public function send_mail($output) {

		$email = $output['email'];
		$message = $output['message'];
		$subject = $output['subject'];

		$config = Array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'ssl://smtp.gmail.com',
		  'smtp_port' => 465,
		  'smtp_user' => 'footer.coin@gmail.com', // change it to yours
		  'smtp_pass' => 'coin.footer', // change it to yours
		  'mailtype' => 'html',
		  'charset' => 'iso-8859-1',
		  'wordwrap' => TRUE
		);

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$this->email->from('footer.coin@gmail.com');
		$this->email->to("$email");

		$this->email->subject($subject);
		$this->email->message($message);

		if($this->email->send()) {
			return TRUE;
		}else {
			show_error($this->email->print_debugger());
			return FALSE;
		}
	}


	public function register_post() {
		$username = $this->post('username');
		$fullname = $this->post('fullname');
		$nickname = $this->post('nickname');
		$gender = $this->post('gender');
		$email = $this->post('email');
		$password = password_hash($this->post('password'),PASSWORD_DEFAULT);
		$status = 0;

		$data = array(
			'username' => $username,
			'fullname' => $fullname,
			'nickname' => $nickname,
			'gender' => $gender,
			'email' => $email,
			'password' => $password,
			'status' => $status
		);
		


		$token = $this->User_model->registration($data);

		if ($token) {
			$link_confirm = "http://localhost/footer/index.php/api/User/confirm/".$token;
			$link_delete = "http://localhost/footer/index.php/api/User/delete/".$token;


			$message = "Thanks for registration in our Footer apps.
						<br><br>
						To confirm please <a href='$link_confirm'>click here</a>
						<br><br>
						If this is not you, please <a href='$link_delete'>click here</a>";

			$output['message'] = $message;
			$output['subject'] = "Account Confirmation";
			$output['email'] = $data['email'];

			
			if ($this->send_mail($output)) {
				$result['status'] = 1;
				$this->fetched_data = $result;
				$this->assign_data();

				if ($this->check_status()) {
					$status_code = REST_Controller::HTTP_OK;
				}
			}
		}

		$status_code  = REST_Controller::HTTP_NOT_FOUND;
		$this->response($this->response_data, $status_code);
	}

	public function confirm_get($token) {
		$this->User_model->confirm($token);
	}

	public function delete_get($token) {
		$this->User_model->delete($token);
	}

	public function login_post() {
		$token = $this->post('token');

		if ($token === NULL) {
			$username = $this->post('username');
			$password = $this->post('password');

			if ($username === NULL || $password === NULL) {
				$result['status_login'] = 0;
				$this->fetched_data = $result;
				$this->assign_data();
				$status_code  = REST_Controller::HTTP_NOT_FOUND;
			}else {
				$data = array(
					'username' => $username,
					'password' => $password
				);

				$token = $this->User_model->login($data);

				if ($token) {
					$output['token'] = $token;
					$this->fetched_data = $result;
					$this->assign_data();

					if ($this->check_status()) {
						$status_code = REST_Controller::HTTP_OK;
					}
					else {
						$status_code  = REST_Controller::HTTP_NOT_FOUND;
					}
				}else {
					$status_code  = REST_Controller::HTTP_NOT_FOUND;
				}
			}

		}else {
			$this->fetched_data = $this->User_model->check_token($token);
			$this->assign_data();

			if ($this->check_status()) {
				$status_code = REST_Controller::HTTP_OK;
			}
			else {
				$status_code  = REST_Controller::HTTP_NOT_FOUND;
			}
		}

		$this->response($this->response_data, $status_code);
	}

	public function restaurant_get($ID_restaurant,$token) {

		$this->set_status(TRUE);

		$output = $this->User_model->select_restaurant($ID_restaurant, $token);
	

		$this->fetched_data = $output;
		$this->assign_data();

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}
		else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}

		$this->response($this->response_data, $status_code);
	}

	public function all_restaurant_get($token) {

		$this->set_status(TRUE);

		$output = $this->User_model->select_all_restaurant($token);
	

		$this->fetched_data = $output;
		$this->assign_data();

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}
		else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}

		$this->response($this->response_data, $status_code);
	}

}
 ?>