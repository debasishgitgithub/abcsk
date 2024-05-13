<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['user_model']);
	}
	public function index()
	{
		try {
			if ($this->http->session_gets()) {
				view('blog/dashboard');
			} else {
				$this->load->view('login_view');
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}
	private function login_mtc($username, $password)
	{
		if ($user = $this->user_model->get_filter(null, $username)) {
			if (password_verify($password, $user->password) === true) {
				return [
					'user_id' => $user->id,
					'username' => $user->username,
					'user_email' => $user->email,
					'created_on' => $user->created_on,
					'status' => $user->status,
					'type' => $user->user_type,
				];
			} else {
				return "Username or password are not matched";
			}
		} else {
			return "Username not found";
		}
	}


	public function session_login()
	{
		try {
			//pp(password_hash('password', PASSWORD_BCRYPT));
			if (is_post()) {
				$this->form_validation->set_rules(
					[
						[
							'field' => 'username',
							'label' => 'Username',
							'rules' => "trim|required",
						],
						[
							'field' => 'password',
							'label' => 'Password',
							'rules' => "trim|required",
						]
					]
				);

				if ($this->form_validation->run()) {

					$data =  $this->input->post();
					$lresp = $this->login_mtc($data["username"], $data["password"]);
					if (is_array($lresp)) {
						if (isset($lresp['type']) == 'ACTIVE') {
							$lresp = (object) $lresp;
							// define('USER_DATA', $lresp);
							$this->session->set_userdata('logged_in', $lresp);
							redirect(portal_url(), 'refresh');
						} else {
							set_message("danger", "You are not Active");
						}
					} else {
						set_message("danger", $lresp);
					}
				}
			}
			$this->load->view('login_view');
		} catch (\Throwable $th) {
			redirect(portal_url('login'), 'refresh');
		}
	}

	public function session_logout()
	{
		$this->session->sess_destroy();
		redirect(portal_url("login"));
	}
}
