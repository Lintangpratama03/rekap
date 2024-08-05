<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Useroption_model extends CI_Model
{
    public function read()
    {

        $query = $this->db->query("select * from `tbl_user`");
        return $query->result_array();
    }

    public function insert($data)
    {

        $this->user_name    = $data['name'];
        $this->user_password  = $data['pass'];
        $this->user_type = $data['type'];


        $this->db->where('user_name', $data['name']);
        $query = $this->db->get('tbl_user');
        $user = $query->row();

        if ($user) {
            return "Username Exists";
        }

        if ($this->db->insert('tbl_user', $this)) {
            $key = base64_encode(password_hash($data['pass'], PASSWORD_DEFAULT));
            $date_create = time();

            $user_id = 0;
            $this->db->select('user_id')->from('tbl_user')->where('user_name', $data['name']);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $user_id = $query->row()->user_id;
            }

            $this->db->query("INSERT INTO `keys` (`user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES ('$user_id', '$key', 1, 0, 0, NULL, '$date_create' )");
            return "Data is inserted successfully";
        } else {
            return "Error has occured";
        }
    }
    public function update($id, $data)
    {

        $this->user_name    = $data['name'];
        $this->user_password  = $data['pass'];
        $this->user_type = $data['type'];
        $result = $this->db->update('tbl_user', $this, array('user_id' => $id));
        if ($result) {
            return "Data is updated successfully";
        } else {
            return "Error has occurred";
        }
    }
    public function delete($id)
    {

        $result = $this->db->query("delete from `tbl_user` where user_id = $id");
        if ($result) {
            return "Data is deleted successfully";
        } else {
            return "Error has occurred";
        }
    }
}
