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
		if ($student_dtls = $this->student_model->get($id)) {
			// pp($blg_dtls);
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

	public function save($id = null)
	{
		try {
			$u = $this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			$user_id = $u->user_id;
			$p = $this->input->post();

			if (is_post()) {
				$this->form_validation->set_rules(
					[
						[
							'field' => 'title',
							'label' => 'title',
							'rules' => 'required',
						],
						[
							'field' => 'content',
							'label' => 'Content',
							'rules' => 'required',
						],
						// [
						// 	'field' => 'short_content',
						// 	'label' => 'short_content',
						// 	'rules' => 'required',
						// ],
						[
							'field' => 'category_id',
							'label' => 'Category',
							'rules' => 'required|is_exist[category.id]',
							'errors' => array(
								'is_exist' => '%s not exist',
							),
						],
						[
							'field' => 'status',
							'label' => 'Status',
							'rules' => 'required|in_list[0,1]',
							'errors' => array(
								'is_exist' => '%s not exist',
							),
						],
						[
							'field' => 'blogimage[]',
							'label' => 'Blog Image',
							// 'rules' => 'file_required[blogimage]|file_extension[blogimage.jpeg|jpg|png]|file_maxsize[blogimage.2024]",
							'rules' => 'file_required[blogimage]',
							'errors' => array(
								'file_required' => 'The {field} field is required',
								'file_extension' => 'The {field} field must have a valid file extension jpeg|jpg|png',
								'file_maxsize' => 'The {field} field must not exceed 2024 KB.',
							),
						],
					]
				);

				if ($this->form_validation->run() == true) {
					$upload_file_names = [];

					// file validation for insert
					if (is_null($id)) {
						if (empty($_FILES['blogimage']['name'][0])) {
							set_message('danger', 'please select your file');
							$this->save_view($user_id, $id);
							return;
						}
					}

					// file upload
					if (!empty($_FILES['blogimage']['name'][0])) {

						if (!empty($id) && $u->type == 'SUPPORT_ADMIN') {
							set_message('danger', 'Permission not allow');
							redirect(base_url('blog'), 'refresh');
						}

						$where_in_fileArr = ['img_name' => $_FILES['blogimage']['name']];

						$duplicate_filesArr = array_column($this->blog_img_model->get_all($where_in_fileArr), 'img_name');

						if (!empty($duplicate_filesArr)) {
							$dup_file = array_reduce($duplicate_filesArr, function ($carry, $file_name) {
								return $carry .= "{$file_name} already exists.<br>";
							});
							set_message('danger', $dup_file);
							$this->save_view($user_id, $id);
							return;
						}

						$config = [
							'upload_path' => 'documents/uploads/blog_img',
							'allowed_types' => 'jpg|jpeg|png',
						];

						if (!$this->mfile->upload('blogimage', $config)) {
							set_message('danger', 'File uploading error');
							$this->save_view($user_id, $id);
							return;
						}

						$upload_file_names = $this->mfile->file_names();
					}

					$data = [
						'user_id' => $user_id,
						"title" => $p['title'],
						"short_content" => $p['short_content'],
						"content" => $p['content'],
						"category_id" => $p['category_id'],
						"status" => $p['status']
					];

					if (is_null($id)) {
						// insert
						if ($blog_id = $this->blog_model->insert($data)) {
							$img_data = array_map(fn ($file_name) => ['img_name' => $file_name, 'blog_id' => $blog_id], $upload_file_names);
							if ($this->blog_img_model->insert_batch($img_data)) {
								set_message('success', 'Blog create successfully');
							} else {
								$this->blog_model->delete($blog_id);
								set_message('danger', 'Blog img upload failed');
							}
						} else {
							$this->mfile->unlink_files();
							set_message('danger', 'Blog create failed');
						}
					} else {
						// update
						if ($this->blog_model->get($id, null, $u->type == 'SUPER_ADMIN' ? null : $user_id)) {
							if ($this->blog_model->update($id, $data)) {
								if (!empty($upload_file_names)) {
									$img_data = array_map(fn ($file_name) => ['img_name' => $file_name, 'blog_id' => $id], $upload_file_names);
									if ($this->blog_img_model->insert_batch($img_data)) {
										$blog_img_dtls = $this->blog_img_model->get_all(null, $id);
										foreach ($blog_img_dtls as $row) {
											if (file_exists(FCPATH . $config['upload_path'] . "/{$row->img_name}")) {
												unlink(FCPATH . $config['upload_path'] . "/{$row->img_name}");
											}
										}
										set_message('success', 'Blog updated successfully');
									} else {
										set_message('danger', 'Blog img upload failed');
									}
								} else {
									set_message('success', 'Blog update success');
								}
							} else {
								if (!empty($upload_file_names)) $this->mfile->unlink_files();
								set_message('danger', 'Blog update failed');
							}
						} else {
							set_message('danger', 'Permission not allow');
						}
					}
					redirect(base_url('blog'), 'refresh');
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
