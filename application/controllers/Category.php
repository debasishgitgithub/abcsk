<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'category_model'
		]);
	}

	public function index()
	{
		try {
			$this->http->auth(['get', 'post'], ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			view('blog/category_view', [], 'Category | Blog');
		} catch (\Throwable $th) {
			print_r($th);
		}
	}

	public function save($id = null)
	{
		try {
			$u = $this->http->auth(['post'], ['SUPER_ADMIN']);
			if ($cat_name = $this->http->request->get('cat_name')) {
				if (empty($id)) {
					// insert 
					if ($this->category_model->insert(['name' => $cat_name, 'user_id' => $u->user_id, 'status' => 1])) {
						$this->http->response->create(200, 'Category insert successful');
					} else {
						$this->http->response->create(203, 'Category insert failure');
					}
				} else {
					// update
					if ($this->category_model->update($id, ['name' => $cat_name, 'status' => 1])) {
						$this->http->response->create(200, 'Category update successful');
					} else {
						$this->http->response->create(203, 'Category update failure');
					}
				}
			} else {
				$this->http->response->create(203, 'Category name is required');
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}

	public function get_all()
	{
		try {
			$u = $this->http->auth('get', ['SUPER_ADMIN', 'SUPPORT_ADMIN']);
			if ($data = $this->category_model->get_all(1, null)) {
				$this->http->response->create(200, 'Category fetch successful', $data);
			} else {
				$this->http->response->create(203, 'Category not found');
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}

	public function delete($id)
	{
		try {
			$u = $this->http->auth('post', ['SUPER_ADMIN']);
			$user_id = $u->type == 'SUPER_ADMIN' ? null : $u->user_id;
			if ($data = $this->category_model->delete($id, $user_id)) {
				$this->http->response->create(200, 'Category delete successful', $data);
			} else {
				$this->http->response->create(203, 'Category delete failed');
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}
}
