<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'blog_model'
        ]);
    }

    public function index()
    {
        public_view('index');
    }

    public function single_view($id = null)
    {
        try {
            if($blogDtls = $this->blog_model->get($id)){
                public_view('single-blog', compact('blogDtls'));
            } else {
                redirect(base_url());
            }
        } catch (\Throwable $th) {
            pp($th);
        }
    }
}
