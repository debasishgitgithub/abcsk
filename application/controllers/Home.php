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
        try {
            if ($this->http->session_gets()) {
                view('blog/dashboard');
            } else {
                $this->load->view('login_view');
            }
        } catch (\Throwable $th) {
            redirect(base_url());
        }
    }
}
