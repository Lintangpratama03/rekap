<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Useroption extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('database');
        $this->load->model('useroption_model');
    }

    public function user_get()
    {
        $r = $this->useroption_model->read();
        $this->response($r);
    }

    public function user_post()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'pass' => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
            'type' => $this->input->post('type')
        );
        $r = $this->useroption_model->insert($data);
        $this->response($r);
    }

    public function user_put()
    {
        $id = $this->uri->segment(3);

        $data = array(
            'name' => $this->input->get('name'),
            'pass' => $this->input->get('pass'),
            'type' => $this->input->get('type')
        );

        $r = $this->useroption_model->update($id, $data);
        $this->response($r);
    }

    public function user_delete()
    {
        $id = $this->uri->segment(3);
        $r = $this->useroption_model->delete($id);
        $this->response($r);
    }
}
