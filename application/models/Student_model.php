<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_model extends CI_Model
{

    private $table = 'students';

    public function get($id, $status = null,  $user_id = null)
    {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->limit(1);
        $this->db->where("id", $id);
        if (!is_null($status)) {
            $this->db->where("status", $status);
        }
        if (!empty($user_id)) {
            $this->db->where("user_id", $user_id);
        }
        return $this->db->get()->row();
    }

    public function get_all($status = null,  $user_id = null)
    {
        $this->db->select("*, CONCAT(first_name, ' ', last_name ) AS full_name");
        $this->db->from($this->table);
        if (!is_null($status)) {
            $this->db->where("status", $status);
        }
        if (!empty($user_id)) {
            $this->db->where("user_id", $user_id);
        }
        return $this->db->get()->result();
    }

    public function insert($data)
    {
        $this->db->set($data);
        //pp($this->db->get_compiled_insert($this->table));
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where("id", $id);
        //pp($this->db->get_compiled_update($this->table));
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
    public function delete($id, $user_id=null)
    {
        $this->db->where("id", $id);
        if (!empty($user_id)) {
            $this->db->where("user_id", $user_id);
        }
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
