<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function get($id)
    {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->limit(1);
        $this->db->where("id", $id);
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

    public function get_filter($status = null, $username = null)
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

    
    public function get_all($status = null, $withoutId = null)
    {
        $this->db->select("*, CONCAT(first_name, ' ', last_name ) AS full_name");
        $this->db->from($this->table);
        if (!is_null($status)) {
            $this->db->where("status", $status);
        }
        if (!empty($withoutId)) {
            $this->db->where("id !=", $withoutId);
        }
        return $this->db->get()->result();
    }

    public function insert($data)
    {
        $this->db->set($data);
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where("id", $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where("id", $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
