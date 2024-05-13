<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog_img_model extends CI_Model
{

    private $table = 'images';

    public function get($id)
    {
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->limit(1);
        $this->db->where("id", $id);
        return $this->db->get()->row();
    }

    public function get_all($where_in = [], $blog_id = null)
    {
        $this->db->select("*");
        $this->db->from($this->table);
        if(!empty($where_in)){
            foreach ($where_in as $column => $valuesArr) {
                $this->db->where_in($column, $valuesArr);
            }
        }
        if(!empty($blog_id)) $this->db->where('blog_id', $blog_id);
        // pp($this->db->get_compiled_select());
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
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
    public function delete($id)
    {
        $this->db->where("id", $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function delete_multiple($blog_id)
    {
        $this->db->where("blog_id", $blog_id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

}
