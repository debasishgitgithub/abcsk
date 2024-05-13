<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog_img extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'blog_img_model'
		]);
	}

	public function get_all()
	{
		try {
			$blog_id = $this->http->request->get('blog_id');
			if ($data = $this->blog_img_model->get_all(null, $blog_id)) {
				$data = array_map(function ($row) {
					$row->img_name = base_url("documents/uploads/blog_img/" . $row->img_name);
					return $row;
				}, $data);
				return $this->http->response->create(200, "Data found successfully", $data);
			} else {
				return $this->http->response->create(203, "No data found");
			}
		} catch (\Throwable $th) {
			return $this->http->response->serverError($th->getMessage());
		}
	}
}
