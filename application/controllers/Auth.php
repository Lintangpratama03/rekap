<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('auth_model');
    }

    public function login_post()
    {
        header("x-api-key: JDJ5JDEwJDJDbDN1emoxZ2QwZGFqYzhyT3RnLi4vV3ZVYmx5QVIwaXFOaUhLSnA1bm41aEZNOFVKRkVx");
        $data = array(
            'name' => $this->input->post('name'),
            'pass' => $this->input->post('pass'),
        );

        $r = $this->auth_model->login($data);
        $this->response($r);
    }
}
