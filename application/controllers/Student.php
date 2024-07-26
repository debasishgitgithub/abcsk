<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'student_model',
			'courses_model'
		]);
	}

	private function save_view($user_id, $id = null)
	{
		$courses_list = $this->courses_model->get_all(1);
		if ($student_dtls = $this->student_model->get($id, null, $user_id)) {
			view('student/create', compact('courses_list', 'student_dtls'), "ABCSK | Update");
		} else {
			view('student/create', compact('courses_list'), "ABCSK | Register");
		}
	}

	public function index()
	{
		try {
			$this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			view('student/index', [], 'ABCSK | Student');
		} catch (\Throwable $th) {
			redirect(base_url('student'), 'refresh');
		}
	}

	public function get_all()
	{
		try {
			$u = $this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			$user_id = $u->user_id;

			if ($data = $this->student_model->get_all(null, $user_id)) {
				return $this->http->response->create(200, "Data found successfully", $data);
			} else {
				return $this->http->response->create(203, "No data found");
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}

	public function save($id = null)
	{
		try {
			$u = $this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			$user_id = $u->user_id;

			if (is_post()) {
				$this->form_validation->set_rules(
					[
						[
							'field' => 'first_name',
							'label' => 'First name',
							'rules' => 'required',
						],
						[
							'field' => 'last_name',
							'label' => 'Last name',
							'rules' => 'required',
						],
						[
							'field' => 'father_name',
							'label' => 'Father name',
							'rules' => 'required',
						],
						[
							'field' => 'mobile_no',
							'label' => 'Mobile no',
							'rules' => 'required',
						],
						[
							'field' => 'email',
							'label' => 'Email',
							'rules' => 'required',
						],
						[
							'field' => 'city',
							'label' => 'City',
							'rules' => 'required',
						],
						[
							'field' => 'state',
							'label' => 'State',
							'rules' => 'required',
						],
						[
							'field' => 'aadhaar_no',
							'label' => 'Aadhaar no',
							'rules' => 'required',
						],
						[
							'field' => 'pin',
							'label' => 'Pin',
							'rules' => 'required',
						],
						[
							'field' => 'address',
							'label' => 'Address',
							'rules' => 'required',
						],
						[
							'field' => 'installation_type',
							'label' => 'installation_type',
							'rules' => 'required|in_list[one_time,installment]',
							'errors' => array(
								'is_exist' => '%s not exist',
							),
						],

						[
							'field' => 'course_id',
							'label' => 'Course',
							'rules' => 'required|is_exist[courses.id]',
							'errors' => array(
								'is_exist' => '%s not exist',
							),
						],
						[
							'field' => 'status',
							'label' => 'Status',
							'rules' => 'required|in_list[ACTIVE,INACTIVE]',
							'errors' => array(
								'is_exist' => '%s not exist',
							),
						],
						// [
						// 	'field' => 'mp_admit_card',
						// 	'label' => 'MP Admit card',
						// 	// 'rules' => 'file_required[blogimage]|file_extension[blogimage.jpeg|jpg|png]|file_maxsize[blogimage.2024]",
						// 	'rules' => 'file_required[mp_admit_card]',
						// 	'errors' => array(
						// 		'file_required' => 'The {field} field is required',
						// 		'file_extension' => 'The {field} field must have a valid file extension jpeg|jpg|png',
						// 		'file_maxsize' => 'The {field} field must not exceed 2024 KB.',
						// 	),
						// ],
					]
				);

				if ($this->form_validation->run() == true) {
					$upload_file_names = '';

					// file validation for insert
					if (is_null($id)) {
						if (empty($_FILES['mp_admit_card']['name'])) {
							set_message('danger', 'please select your MP admit card');
							$this->save_view($user_id, $id);
							return;
						}
					}

					// file upload
					if (!empty($_FILES['mp_admit_card']['name'])) {

						$config = [
							'upload_path' => 'documents/student/mp_admit',
							'allowed_types' => 'jpg|jpeg|png',
						];

						if (!$this->mfile->upload('mp_admit_card', $config)) {
							set_message('danger', 'File uploading error');
							$this->save_view($user_id, $id);
							return;
						}

						$upload_file_names = $this->mfile->file_names(true);
					}

					$data = [
						'first_name' => $this->input->post('first_name'),
						'last_name' => $this->input->post('last_name'),
						'father_name' => $this->input->post('father_name'),
						'mobile_no' => $this->input->post('mobile_no'),
						'email' => $this->input->post('email'),
						'city' => $this->input->post('city'),
						'state' => $this->input->post('state'),
						'aadhaar_no' => $this->input->post('aadhaar_no'),
						'pin' => $this->input->post('pin'),
						'address' => $this->input->post('address'),
						'course_id' => $this->input->post('course_id'),
						'installation_type' => $this->input->post('installation_type'),
						'status' => $this->input->post('status'),
						'user_id' => $user_id,
					];

					if (!empty($upload_file_names)) {
						$data['mp_admit_card_image'] = $upload_file_names;
					}

					if (is_null($id)) {
						// insert
						if ($blog_id = $this->student_model->insert($data)) {
							set_message('success', 'Student register successfully');
						} else {
							$this->mfile->unlink_files();
							set_message('danger', 'Student register failed');
						}
					} else {
						// update
						if ($this->student_model->update($id, $data)) {
							set_message('success', 'Student update success');
						} else {
							$this->mfile->unlink_files();
							set_message('danger', 'Student update failed');
						}
					}
					redirect(base_url('student'), 'refresh');
				} else {
					$this->save_view($user_id, $id);
				}
			} else {
				$this->save_view($user_id, $id);
			}
		} catch (\Throwable $th) {
			pp($th);
			redirect(base_url(), 'refresh');
		}
	}
}
