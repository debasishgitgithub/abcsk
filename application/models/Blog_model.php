<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog_model extends CI_Model
{

    private $table = 'blogs';
    private $tbl_category = 'category';

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

    public function get_all($status = null,  $user_id = null, $search_text = null, $limit = null, $offset = 0, $order = null)
    {
        $this->db->select("b.*, c.name AS category_name");
        $this->db->from("$this->table b");
        $this->db->join("$this->tbl_category c", "c.id = b.category_id");
        if (!is_null($status)) {
            $this->db->where("b.status", $status);
        }
        if (!empty($user_id)) {
            $this->db->where("b.user_id", $user_id);
        }
        if (!empty($search_text)) {
            $this->db->like('title', $search_text);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($order)) {
            $this->db->order_by($order['column'], strtoupper($order['direction']));
        }
        return $this->db->get()->result();
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch($this->table, $data);
        return $this->db->affected_rows();
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

    public function count_filter($status = null,  $user_id = null, $search_text = null)
    {
        $this->db->select("b.*, c.name AS category_name");
        $this->db->from("$this->table b");
        $this->db->join("$this->tbl_category c", "c.id = b.category_id");
        if (!is_null($status)) {
            $this->db->where("b.status", $status);
        }
        if (!empty($user_id)) {
            $this->db->where("b.user_id", $user_id);
        }
        if (!empty($search_text)) {
            $this->db->like('title', $search_text);
        }
        
        return $this->db->count_all_results();
    }
}
