<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
private $table = 'user';

public function get($id)
{
    $this->db->select("*");
    $this->db->from($this->table);
    $this->db->limit(1);
    $this->db->where("id", $id);
    return $this->db->get()->row();
}

public function get_filter($status = null,$username = null)
{
    $status = strtoupper($status);

    $this->db->select("*");
    $this->db->from($this->table);
    $this->db->limit(1);
    if (!empty($status)) {
        $this->db->where("status", $status);
    }
    if (!empty($username)) {
        $this->db->where("username", $username);
        $this->db->or_where("email", $username);
    }
    return $this->db->get()->row();
}

}