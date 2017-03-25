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

		$this->set_status(FALSE);

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

		if ($token != FALSE) {
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
				$this->fetched_data = $token;
				$this->assign_data();
				$this->set_status(TRUE, 'email sent successfully');
			}else {
				$this->set_status(FALSE, 'Can\'t to send mail');
			}
		}else {
			$this->set_status(FALSE, 'username telah terdaftar');
		}

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}
		$this->response($this->response_data, $status_code);
	}

	public function confirm_get($token) {
		$this->User_model->confirm($token);
	}

	public function delete_get($token) {
		$this->User_model->delete($token);
	}

	public function login_post() {

		$this->set_status(FALSE);

		$token = $this->post('token');

		if ($token === NULL) {
			$username = $this->post('username');
			$password = $this->post('password');

			if ($username === NULL || $password === NULL) {
				$result['status_login'] = 0;
				$this->fetched_data = $result;
				$this->assign_data();
				$this->set_status(FALSE, 'username atau password kosong');
			}else {
				$data = array(
					'username' => $username,
					'password' => $password
				);

				$token = $this->User_model->login($data);
				
				if ($token == 'account hasnt confirmed') {
					$this->set_status(FALSE, 'account hasn\'t confirmed');
				}elseif ($token != FALSE) {
					$output['token'] = $token;
					$this->fetched_data = $output;
					$this->assign_data();
					$this->set_status(TRUE);
				}else {
					$this->set_status(FALSE, 'username atau password salah');
				}
			}
		}else {
			$output = $this->User_model->check_token($token);
			if ($output != FALSE) {
				$this->fetched_data = $output;
				$this->assign_data();
				$this->set_status(TRUE);
			}else {
				$this->set_status(FALSE, 'token tidak ditemukan');
			}
		}

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
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

	public function like_get($ID_restaurant, $token) {
		$stat = 'love';
		$result = $this->User_model->likeOrFav($ID_restaurant, $token, $stat);

		if ($result) {
			$this->set_status(TRUE,'like');
		}else {
			$this->set_status(FALSE,'error');
		}

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}

		$this->response($this->response_data, $status_code);

	}

	public function fav_get($ID_restaurant, $token) {
		$stat = 'favorite';
		$result = $this->User_model->likeOrFav($ID_restaurant, $token, $stat);

		if ($result) {
			$this->set_status(TRUE,'favorite');
		}else {
			$this->set_status(FALSE,'error');
		}

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}

		$this->response($this->response_data, $status_code);
	}

	public function feedback_post() {
		$this->set_status(FALSE);
		$feedback = $this->post('feedback');
		$output = array(
			'email' => 'footer.coin@gmail.com',
			'message' => $feedback,
			'subject' => 'feedback'
		);

		if ($this->send_mail($output)) {
			$this->set_status(TRUE, 'feedback accepted');
		}else {
			$this->set_status(FALSE, 'problem with email');
		}

		if ($this->check_status()) {
			$status_code = REST_Controller::HTTP_OK;
		}else {
			$status_code  = REST_Controller::HTTP_NOT_FOUND;
		}

		$this->response($this->response_data, $status_code);
	}

	public function forgot_get($token) {
		$link = "http://localhost/footer_backend/index.php/api/User/new_pass/?token=$token";
		$message = "to input your new password, please <a href='$link'>click here</a>";
		$output = array(
			'email' => $this->User_model->select_email($token),
			'message' => $message,
			'subject' => 'Forgot Password'
		);

		if ($this->send_mail($output)) {
				$this->fetched_data = $token;
				$this->assign_data();
				$this->set_status(TRUE, 'email sent successfully');
		}else {
				$this->set_status(FALSE, 'Can\'t to send mail');
		}

		$this->response($this->response_data, $status_code);
	}

	public function new_pass_get() {
		$this->load->view('new_pass');
	}

	public function change_pass_post($token) {
		
		$old_pass = $this->post('old_pass');
		$new_pass = password_hash($this->post('new_pass'),PASSWORD_DEFAULT);

		if (!empty($old_pass)) {
			$result = $this->User_model->change_pass($old_pass, $new_pass, $token);
			if ($result) {
				$this->set_status(TRUE, 'Password Changed');
				$status_code = REST_Controller::HTTP_OK;
			}else {
				$this->set_status(FALSE, 'There is error');
				$status_code  = REST_Controller::HTTP_NOT_FOUND;
			}
			$this->response($this->response_data, $status_code);
		}else {
			$result = $this->User_model->forgot_pass($new_pass, $token);
			if ($result) {
				echo "Success";
			}else {
				echo "There are error";
			}
		}
	}

	public function select_favorite_get($token) {
		
	}

}
 ?>