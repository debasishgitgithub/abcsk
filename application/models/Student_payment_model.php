<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_payment_model extends CI_Model
{

    private $table = 'student_payment';

    public function get($id,  $admin_id = null)
    {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->limit(1);
        $this->db->where("id", $id);
        if (!empty($admin_id)) {
            $this->db->where("admin_id", $admin_id);
        }
        return $this->db->get()->row();
    }

    public function getLastId()
    {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->limit(1);
        $this->db->order_by("id", "desc");
        $result = $this->db->get()->row();
        if ($result->id) {
            return intval($result->id);
        } else {
            return null;
        }
    }

    public function get_all($admin_id = null)
    {
        $this->db->select("*");
        $this->db->from($this->table);
        if (!empty($admin_id)) {
            $this->db->where("admin_id", $admin_id);
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
}
