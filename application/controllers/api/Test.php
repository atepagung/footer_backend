<?php 
/**
* 
*/

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Test extends REST_Controller {
	
	function __construct() {
		parent::__construct();
	}

	public function send_mail_get() {

		$config = Array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'ssl://smtp.googlemail.com',
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
		$this->email->to('agungteja64@yahoo.co.id');

		$this->email->subject('Email Test');
		$this->email->message("<a href='http://www.google.com'>Klik here to link to google.com</a>");

		if($this->email->send()) {
			echo 'Email sent.';
		}else {
			show_error($this->email->print_debugger());
		}
	}


}
 ?>