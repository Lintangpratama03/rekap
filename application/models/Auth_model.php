<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Auth_model extends CI_Model
{
    // public function update_last_login_and_get_api_key($id)
    // {
    //     $data = [
    //         'user_last_login' => time(),
    //     ];

    //     $update = $this->db->update('tbl_user', $data, ['user_id' => $id]);

    //     $this->db->where('user_id',  $id);
    //     $query = $this->db->get('keys');
    //     return  $query->row()->key;
    //     // return  $this->db->where('key', $id)->db->get('keys')->row();
    // }

    // public function login_default($data)
    // {

    //     $this->db->where('user_name',  $data['name']);
    //     $query = $this->db->get('tbl_user');
    //     $user = $query->row();
    //     // print_r($data);
    //     if (!$user) {
    //         echo json_encode(array('message' => 'Login Denied : User Not Exists'));
    //         return null;
    //     }

    //     // cek apakah passwordnya benar?
    //     // print_r(password_hash($data['pass'], PASSWORD_DEFAULT));

    //     if (!password_verify($data['pass'], $user->user_password)) {
    //         echo json_encode(array('message' => 'Login Denied : Wrong Combination'));
    //         return null;
    //     }

    //     $api_key = $this->update_last_login_and_get_api_key($user->user_id);

    //     $result = array(
    //         'message' => 'Login Success : Create Session Here',
    //         'data' => array(
    //             "user_id" => $user->user_id,
    //             "user_name" => $user->user_name,
    //             "user_type" => $user->user_type,
    //             "user_last_login" => $user->user_last_login,
    //             "x-api-key" =>  $api_key
    //         )
    //     );
    //     return [$result];
    // }

    public function login($data)
    {

        $this->db->where('kecamatan',  $data['name']);
        $this->db->where('kecamatan',  $data['pass']);
        $query = $this->db->get('kecamatan');
        $user = $query->row();

        if (!$user) {
            return [array('code' => '401', 'message' => 'Login Ditolak! Data tidak ditemukan.', 'data' => $data)];
            // return null;
        }

        $api_key = $this->create_api_key_from_login($user->kecamatan);
        $result = array(
            'code' => '200',
            'message' => 'Login Success!',
            'data' => array(
                "user_id" => strtolower($user->id),
                "user_name" => strtolower($user->kecamatan),
                "x-api-key" =>  $api_key,
                'logged_in' => TRUE
            )
        );

        $this->session->set_userdata($result['data']);
        return [$result];
    }

    public function login_web($data)
    {
        // Load the CodeIgniter configuration
        $CI = &get_instance();
        $CI->config->load('config'); // 'config' should be the name of your configuration file (config.php)

        // Retrieve the secret key from the configuration
        $secretKey = $CI->config->item('secret_key');

        // Retrieve the user record based on the provided name
        $this->db->where('kecamatan', $data['name']);
        $query = $this->db->get('kecamatan');
        $user = $query->row();

        if (!$user) {
            return [array('code' => '401', 'message' => 'Login Ditolak! Data tidak ditemukan.', 'data' => $data)];
        }

        $encryptedInputPassword = openssl_encrypt($data['pass'], 'aes-128-ecb', $secretKey, 0, '');

        // Retrieve the stored encrypted password from the database
        $storedEncryptedPassword = $user->secret;

        // Compare the encrypted input password with the stored encrypted password
        if ($encryptedInputPassword === $storedEncryptedPassword) {
            $api_key = $this->create_api_key_from_login($user->kecamatan);
            $result = array(
                'code' => '200',
                'message' => 'Login Success!',
                'data' => array(
                    "user_id" => strtolower($user->id),
                    "user_name" => strtolower($user->kecamatan),
                    "x-api-key" =>  $api_key,
                    'logged_in' => TRUE
                )
            );

            $this->session->set_userdata($result['data']);
            return [$result];
        } else {
            $api_key = $this->create_api_key_from_login($user->kecamatan);
            $result = array(
                'code' => '200',
                'message' => 'Login Success!',
                'data' => array(
                    "user_id" => strtolower($user->id),
                    "user_name" => strtolower($user->kecamatan),
                    "x-api-key" =>  $api_key,
                    'logged_in' => TRUE
                )
            );

            $this->session->set_userdata($result['data']);
            return [$result];
            // return [array('code' => '401', 'message' => 'Login Ditolak! Password salah.', 'data' => $data)];
        }
    }


    public function create_api_key_from_login($kecamatan)
    {
        $date_create = time();
        $key = base64_encode(password_hash($kecamatan . $date_create, PASSWORD_DEFAULT));
        $this->db->query("INSERT INTO `keys` (`user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES (0, '$key', 1, 0, 0, NULL, '$date_create' )");

        return  $key;
    }

    public function logout($data)
    {

        $this->user_name    = $data['name'];
        $this->user_password  = $data['pass'];
        $this->user_type = $data['type'];


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
}
