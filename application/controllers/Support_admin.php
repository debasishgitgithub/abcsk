<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Support_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'user_model'
        ]);
    }

    private function save_view($user_id, $id = null)
    {
        if ($user_dtls = $this->user_model->get($id)) {
            view('support_admin/create', compact('user_dtls'), "ABCSK | Branch Edit");
        } else {
            view('support_admin/create', null,  "ABCSK | Branch Register");
        }
    }

    public function index()
    {
        try {
            $this->http->auth(['get', 'post'], ['SUPER_ADMIN']);
            view('support_admin/index', [], 'ABCSK | Branch List');
        } catch (\Throwable $th) {
            redirect(base_url('support_admin'), 'refresh');
        }
    }

    public function get_all()
    {
        try {
            $u = $this->http->auth(['get', 'post'], ['SUPER_ADMIN']);
            $user_id = $u->user_id;

            if ($data = $this->user_model->get_all(NULL, $user_id)) {
                return $this->http->response->create(200, "Branch fetched successfully", $data);
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
            $u = $this->http->auth(['get', 'post'], ['SUPER_ADMIN']);
            $user_id = $u->user_id;

            if (is_post()) {
                $this->form_validation->set_rules(
                    [
                        [
                            'field' => 'first_name',
                            'label' => 'First name',
                            'rules' => 'required|trim',
                        ],
                        [
                            'field' => 'last_name',
                            'label' => 'Last name',
                            'rules' => 'required|trim',
                        ],
                        [
                            'field' => 'username',
                            'label' => 'Username',
                            'rules' => "is_unique_filter[users.username.id!=/{$id}]|trim",
                            'errors' => array(
                                'is_unique_filter' => '%s already exist',
                            ),
                        ],
                        [
                            'field' => 'password',
                            'label' => 'Password',
                            'rules' => 'trim',
                        ],
                        [
                            'field' => 'phone_no',
                            'label' => 'Mobile no',
                            'rules' => 'required|trim',
                        ],
                        [
                            'field' => 'email',
                            'label' => 'Email',
                            'rules' => "required|trim|is_unique_filter[users.email.id!=/{$id}]",
                            'errors' => array(
                                'is_unique_filter' => '%s already exist',
                            ),
                        ],
                        [
                            'field' => 'city',
                            'label' => 'City',
                            'rules' => 'trim',
                        ],
                        [
                            'field' => 'state',
                            'label' => 'State',
                            'rules' => 'trim',
                        ],
                        [
                            'field' => 'pan_no',
                            'label' => 'pan no',
                            'rules' => 'required|trim',
                        ],
                        [
                            'field' => 'pin_no',
                            'label' => 'Pin',
                            'rules' => 'trim',
                        ],
                        [
                            'field' => 'address',
                            'label' => 'Address',
                            'rules' => 'trim',
                        ],
                        [
                            'field' => 'status',
                            'label' => 'Status',
                            'rules' => 'required|in_list[ACTIVE,INACTIVE]|trim',
                            'errors' => array(
                                'is_exist' => '%s not exist',
                            ),
                        ],
                    ]
                );

                if ($this->form_validation->run() == true) {


                    $data = [
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'phone_no' => $this->input->post('phone_no'),
                        'email' => $this->input->post('email'),
                        'city' => $this->input->post('city'),
                        'state' => $this->input->post('state'),
                        'pan_no' => $this->input->post('pan_no'),
                        'pin_no' => $this->input->post('pin_no'),
                        'address' => $this->input->post('address'),
                        'status' => $this->input->post('status'),
                        'user_type' => 'SUPPORT_ADMIN'
                    ];

                    if ($username = $this->input->post('username')) {
                        $data['username'] = $username;
                    } else {
                        if(empty($id)){
                            $lastId = $this->user_model->getLastId();
                            $lastId = $lastId ? $lastId + 1 : 1;
                            $usernameCreate = strtolower($data['first_name']) . strtolower($data['last_name']) . $lastId;
                            $data['username'] = $usernameCreate;
                        }
                    }

                    if ($password = $this->input->post('password')) {
                        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
                    }

                    if (is_null($id)) {
                        // insert
                        if ($this->user_model->insert($data)) {
                            set_message('success', 'Branch register successfully');
                        } else {
                            set_message('danger', 'Branch register failed');
                        }
                    } else {
                        // update
                        if ($this->user_model->update($id, $data)) {
                            set_message('success', 'Branch update success');
                        } else {
                            set_message('danger', 'Branch update failed');
                        }
                    }
                    redirect(base_url('support_admin'), 'refresh');
                } else {
                    $this->save_view($user_id, $id);
                }
            } else {
                $this->save_view($user_id, $id);
            }
        } catch (\Throwable $th) {
            redirect(base_url(), 'refresh');
        }
    }
}
