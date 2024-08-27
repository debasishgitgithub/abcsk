<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['user_model', 'student_model']);
	}

	public function save_view($user_type = NULL)
	{
		try {
			$user_type = strtoupper($user_type); // SUPER_ADMIN / SUPPORT_ADMIN / STUDENT

			if ($this->http->session_gets()) {
				view('dashboard');
			} else {
				$this->load->view('login_view', ['user_type' => $user_type ?? 'STUDENT']);
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}
	private function login_mtc($username, $password, $user_type)
	{
		$user_type = strtoupper($user_type);
		if ($user_type == 'SUPPORT_ADMIN' || $user_type == 'SUPER_ADMIN') {
			if ($user = $this->user_model->get_filter(null, $username)) {
				if (password_verify($password, $user->password) === true) {
					return [
						'admin_id' => $user->id,
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
		} else {
			if ($student = $this->student_model->get_filter(null, $username)) {
				if (password_verify($password, $student->password) === true) {
					return [
						'admin_id' => $student->admin_id,
						'user_id' => $student->id,
						'username' => $student->username,
						'user_email' => $student->email,
						'created_on' => $student->created_on,
						'status' => $student->status,
						'type' => 'STUDENT',
					];
				} else {
					return "Username or password are not matched";
				}
			} else {
				return "Username not found";
			}
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
					$lresp = $this->login_mtc($data["username"], $data["password"], $data['user_type'] ?? null);
					if (is_array($lresp)) {
						if (isset($lresp['type']) == 'ACTIVE') {
							$lresp = (object) $lresp;
							// define('USER_DATA', $lresp);
							$this->session->set_userdata('logged_in', $lresp);
							redirect(base_url(), 'refresh');
						} else {
							set_message("danger", "You are not Active");
						}
					} else {
						set_message("danger", $lresp);
					}
				}
			}

			$this->save_view($user_type ?? 'STUDENT');
		} catch (\Throwable $th) {
			redirect(base_url('login'), 'refresh');
		}
	}

	public function session_logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
