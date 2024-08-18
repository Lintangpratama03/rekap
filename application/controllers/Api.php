<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('api_model');
    }

    public function list_kecamatan_get()
    {
        $r = $this->api_model->list_kecamatan();
        $this->response($r);
    }
    public function list_pertanyaan_get()
    {
        $id = $this->input->get('id', TRUE);
        $r = $this->api_model->list_pertanyaan($id);
        $this->response($r);
    }

    public function list_desa_get()
    {
        $id = $this->input->get('id', TRUE);
        $r = $this->api_model->list_desa($id);
        $this->response($r);
    }

    public function list_dusun_get()
    {
        $id = $this->input->get('id', TRUE);
        $r = $this->api_model->list_dusun($id);
        $this->response($r);
    }

    public function list_status_keluarga_get()
    {
        $r = $this->api_model->list_status_keluarga();
        $this->response($r);
    }

    public function list_rekapitulasi_kskps_post()
    {
        $data = array(
            'id_kecamatan' => $this->input->post('id_kecamatan'),
            'id_desa' => $this->input->post('id_desa'),
            'id_dusun' => $this->input->post('id_dusun'),
            'int_rt' => (int)$this->input->post('rt'),
            'int_rw' => (int)$this->input->post('rw'),
            'data_per_page' => $this->input->post('data_per_page'),
            'page' => $this->input->post('page')
        );

        $r = $this->api_model->list_rekapitulasi_kskps($data);
        $this->response($r);
    }

    public function detail_data_kskps_post()
    {
        $data = array(
            'id_keluarga' => $this->input->post('id_keluarga')
        );

        $r = $this->api_model->detail_data_kskps($data);
        $this->response($r);
    }

    public function detail_data_pertanyaan_post() //jihan
    {
        $id_question = $this->input->post('id_question');

        $r = $this->api_model->detail_data_pertanyaan($id_question);
        $this->response($r);
    }


    public function detail_data_kskps_for_edit_get()
    {
        $data = array(
            'id_keluarga' => $this->input->get('id_keluarga')
        );

        $r = $this->api_model->detail_data_kskps_for_edit($data);
        $this->response($r);
    }

    public function data_choice_and_answer_get()
    {
        $r = $this->api_model->data_choice_and_answer();
        $this->response($r);
    }

    public function log_data_keluarga_get()
    {
        $id_keluarga = $this->input->get('id_keluarga');
        $r = $this->api_model->log_data_keluarga($id_keluarga);
        $this->response($r);
    }

    public function delete_data_kskps_post()
    {
        $data = array(
            'id' => $this->input->post('id')
        );

        $this->api_model->subtract_rekap($data);
        $r = $this->api_model->delete_data_kskps($data);
        $this->response($r);
    }

    public function list_data_kskps_post()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data === null) {
            // $this->response(['error' => 'Invalid JSON data'], 400);
            $datas = array(
                'id_kecamatan' => $this->input->post('id_kecamatan'),
                'id_desa' => $this->input->post('id_desa'),
                'id_dusun' => $this->input->post('id_dusun'),
                'int_rt' => (int)$this->input->post('rt'),
                'int_rw' => (int)$this->input->post('rw'),
                'status_keluarga' => $this->input->post('status_keluarga'),
                'kepala_keluarga' => $this->input->post('kepala_keluarga'),
                'data_per_page' => $this->input->post('data_per_page'),
                'page' => $this->input->post('page')
            );

            $r = $this->api_model->list_data_kskps($datas);
            $this->response($r);
        } else {
            $datas = array(
                'id_kecamatan' => $data['id_kecamatan'],
                'id_desa' => $data['id_desa'],
                'id_dusun' => $data['id_dusun'],
                'int_rt' => (int)$data['rt'],
                'int_rw' => (int)$data['rw'],
                'status_keluarga' => $data['status_keluarga'],
                'kepala_keluarga' => $data['kepala_keluarga'],
                'data_per_page' => $data['data_per_page'],
                'page' => $data['page'],
            );
            $r = $this->api_model->list_data_kskps($datas);
            $this->response($r);
        }
    }

    public function list_data_jawaban_post()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        if ($data === null) {
            // $this->response(['error' => 'Invalid JSON data'], 400);
            $datas = array(
                'id_kecamatan' => $this->input->post('id_kecamatan'),
                'data_per_page' => $this->input->post('data_per_page'),
                'page' => $this->input->post('page')
            );

            $r = $this->api_model->list_data_jawaban($datas);
            $this->response($r);
        } else {
            $datas = array(
                'id_kecamatan' => $data['id_kecamatan'],
                'data_per_page' => $data['data_per_page'],
                'page' => $data['page'],
            );
            $r = $this->api_model->list_data_jawaban($datas);
            $this->response($r);
        }
        // var_dump($datas);
    }

    public function save_kuesioner_post()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data === null) {
            $this->response(['error' => 'Invalid JSON data'], 400);
        } else {
            $r = $this->api_model->save_kuesioner($data);
            $this->api_model->add_rekap($r);
            $this->response($r);
        }
    }

    public function save_edit_kuesioner_post()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data === null) {
            $this->response(['error' => 'Invalid JSON data'], 400);
        } else {
            $id_keluarga = $data['id_keluarga'];
            $old_data = array(
                'id' => $id_keluarga
            );
            $this->api_model->subtract_rekap($old_data);
            $r = $this->api_model->save_edit_kuesioner($data);
            $this->api_model->add_rekap($r);
            $this->response($r);
        }
    }

    public function list_choice_kuesioner_get()
    {

        $r = $this->api_model->list_choice_kuesioner();
        $this->response($r);
    }
}
