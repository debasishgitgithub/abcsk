<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Courses extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'courses_model'
		]);
	}

	private function save_view($user_id, $id = null)
	{
		if ($courses_dtls = $this->courses_model->get($id)) {
			view('courses/create', compact('courses_dtls'), "ABCSK | Courses Edit");
		} else {
			view('courses/create', [], "ABCSK | Courses Create");
		}
	}

	public function index()
	{
		try {
			$this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			view('courses/index', [], 'ABCSK | Courses');
		} catch (\Throwable $th) {
			redirect(base_url('courses'), 'refresh');
		}
	}

	public function get_all()
	{
		try {
			$u = $this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			$user_id = $u->user_id;

			if ($data = $this->courses_model->get_all(null)) {
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
							'field' => 'full_name',
							'label' => 'Full name',
							'rules' => 'required|trim',
						],
						[
							'field' => 'short_name',
							'label' => 'Short name',
							'rules' => 'required|trim',
						],
						[
							'field' => 'duration_in_month',
							'label' => 'Duration in month',
							'rules' => 'required|trim',
						],
						[
							'field' => 'fees',
							'label' => 'Fees',
							'rules' => 'required|trim',
						],
						[
							'field' => 'details',
							'label' => 'City details',
							'rules' => 'trim',
						],
						[
							'field' => 'status',
							'label' => 'Status',
							'rules' => 'required|in_list[0,1]',
							'errors' => array(
								'is_exist' => '%s not exist',
							),
						],
					]
				);

				if ($this->form_validation->run() == true) {

					$data = [
						'full_name' => $this->input->post('full_name'),
						'short_name' => $this->input->post('short_name'),
						'duration_in_month' => $this->input->post('duration_in_month'),
						'fees' => $this->input->post('fees'),
						'details' => $this->input->post('details'),
						'status' => $this->input->post('status'),
					];

					if (is_null($id)) {
						// insert
						if ($this->courses_model->insert($data)) {
							set_message('success', 'Courses added successfully');
						} else {
							$this->mfile->unlink_files();
							set_message('danger', 'Courses added failed');
						}
					} else {
						// update
						if ($this->courses_model->update($id, $data)) {
							set_message('success', 'Courses updated success');
						} else {
							$this->mfile->unlink_files();
							set_message('danger', 'Courses updated failed');
						}
					}
					redirect(base_url('courses'), 'refresh');
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

	public function delete($course_id)
	{
		try {
			$u = $this->http->auth(['post'], ['SUPER_ADMIN']);
			if ($this->courses_model->delete($course_id)) {

				return $this->http->response->create(200, "Delete successfully");
			} else {
				return $this->http->response->create(203, "Delete Failed");
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}
}
