<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Api_model extends CI_Model
{

    public function log_data_keluarga($id_keluarga)
    {

        $this->db->select('*');
        $this->db->from('logs_data_keluarga_v2');
        $this->db->where('id_keluarga', $id_keluarga);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function list_pertanyaan_id($id_question)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('id', $id_question);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function detail_data_pertanyaan($id_question)
    {
        $id_kecamatan = 3506010;

        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('id', $id_question);
        $cek_data = $this->db->get()->row_array();

        if ($cek_data['type'] == 'SINGLE_ANSWER') {
            // dengan limit
            // $this->db->select('pilihan, COUNT(pilihan) AS total');
            // $this->db->from('(SELECT c.choice AS pilihan FROM choice_v2 c LEFT JOIN answer a ON a.id_question = c.id_question AND a.choice = c.choice WHERE c.id_question = ' . $id_question . ' LIMIT 5) AS limited_choices');
            // $this->db->group_by('pilihan');
            // $this->db->order_by('total', 'DESC');
            // $query = $this->db->get();

            // tanpa limit
            $this->db->select('c.choice AS pilihan, COUNT(a.choice) AS total');
            $this->db->from('choice_v2 c');
            $this->db->join('answer a', 'a.id_question = c.id_question AND a.choice = c.choice', 'left');
            $this->db->join('keluarga k', 'k.id = a.id_keluarga');
            $this->db->where('c.id_question', $id_question);
            $this->db->where('k.id_kecamatan', $id_kecamatan);
            $this->db->group_by('c.choice');
            $this->db->order_by('total', 'DESC');
            $query = $this->db->get();
        } else {
            // tanpa limit
            $this->db->select('c.choice AS pilihan, COUNT(*) AS total');
            $this->db->from('choice_v2 c');
            $this->db->join('answer a', 'a.id_question = c.id_question', 'inner');
            $this->db->join('keluarga k', 'k.id = a.id_keluarga');
            $this->db->where('c.id_question', $id_question);
            $this->db->where("JSON_CONTAINS(a.choice, JSON_QUOTE(c.choice), '$')");
            $this->db->where('k.id_kecamatan', $id_kecamatan);
            $this->db->group_by('c.choice');
            $this->db->order_by('total', 'DESC');
            $query = $this->db->get();

            // dengan limit
            // $this->db->select('pilihan, COUNT(pilihan) AS total');
            // $this->db->from('(SELECT c.choice AS pilihan FROM choice_v2 c LEFT JOIN answer a ON a.id_question = c.id_question AND JSON_CONTAINS(a.choice, JSON_QUOTE(c.choice), \'$\') WHERE c.id_question = ' . $this->db->escape($id_question) . ' LIMIT 1000) AS limited_choices');
            // $this->db->group_by('pilihan');
            // $this->db->order_by('total', 'DESC');
            // $query = $this->db->get();
        }
        return $query->result_array();
    }

    public function list_kecamatan()
    {
        $query =  $this->db->get('kecamatan');
        return $query->result_array();
    }

    public function list_desa($id)
    {
        $this->db->from('desa');
        if (isset($id) && $id !== '') {
            $this->db->where('id_kecamatan', $id);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function list_dusun($id)
    {
        $this->db->from('dusun');
        if (isset($id) && $id !== '') {
            $this->db->where('id_desa', $id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function list_status_keluarga()
    {
        $query = $this->db->query("SELECT DISTINCT status FROM `keluarga`");
        return $query->result_array();
    }

    public function list_rekapitulasi_kskps($data)
    {
        // default
        $data_per_page = $data['data_per_page'];

        $data_pagination = array(
            "page_no" => 0,
            "data_found_in_page" => 0,
            "page_available" => 0,
            "data_found" => 0,
            "data_shown_to_user" => 0,
            "html_data" => ''
        );

        $this->db->select(
            'ROW_NUMBER() OVER(ORDER BY kecamatan, desa, dusun, rw, rt) AS nomer_tabel, kecamatan.kecamatan, desa.desa,dusun.dusun,rekap.rt, rekap.rw, sum(rekap.kk_pria_pra) as kk_pria_pra, '
                . 'sum(rekap.kk_pria_sejahtera) as kk_pria_sejahtera, '
                . 'sum(rekap.kk_wanita_pra) as kk_wanita_pra, '
                . 'sum(rekap.kk_wanita_sejahtera) as kk_wanita_sejahtera, '
                . 'sum(rekap.pra_sejahtera) as pra_sejahtera, '
                . 'sum(rekap.sejahtera) as sejahtera, '
                . 'sum(rekap.total) as total'
        );
        $this->db->from('rekap');
        $this->db->join('kecamatan', 'rekap.id_kecamatan = kecamatan.id');
        $this->db->join('desa', 'rekap.id_desa = desa.id');
        $this->db->join('dusun', 'rekap.id_dusun = dusun.id');

        if ($data['id_dusun'] != '') {
            $this->db->group_by("kecamatan.kecamatan, desa.desa, dusun.dusun, rekap.rw, rekap.rt");
        } else if ($data['id_desa'] != '') {
            $this->db->group_by("kecamatan.kecamatan, desa.desa, dusun.dusun");
        } else if ($data['id_kecamatan'] != '') {
            $this->db->group_by("kecamatan.kecamatan, desa.desa");
        } else {
            $this->db->group_by("kecamatan.kecamatan");
        }

        // add filter on kecamatan
        if ($data['id_kecamatan'] != '') {
            $this->db->where('rekap.id_kecamatan', $data['id_kecamatan']);
        }

        // add filter on desa
        if ($data['id_desa'] != '') {
            $this->db->where('rekap.id_desa', $data['id_desa']);
        }

        // add filter on dusun
        if ($data['id_dusun'] != '')
            $this->db->where('rekap.id_dusun', $data['id_dusun']);

        // add filter on RT
        if ($data['int_rt'] != 0)
            $this->db->where('rekap.rt', $data['int_rt']);

        // add filter on RW
        if ($data['int_rw'] != 0)
            $this->db->where('rekap.rw', $data['int_rw']);

        $data_pagination["page_no"] =  ($data['page'] <= 1) ? 1 : (int)$data['page'];

        $count_query = clone $this->db;
        $data_pagination["data_found"] = $count_query->count_all_results();
        $data_pagination["page_available"] =    ceil($data['page'] / $data_per_page);

        if ($data['page'] == '' ||  $data['page'] <= 1)
            $this->db->limit($data_per_page, 0);
        else
            $this->db->limit($data_per_page, ($data_per_page * ($data_pagination["page_no"] - 1)));

        $query = $this->db->get();
        $data_pagination["data_found_in_page"]  =  $query->num_rows();
        $data_pagination["data_shown_to_user"] = ($data_per_page * ($data_pagination["page_no"] - 1)) + 1 . " sampai " . ($data_pagination["data_found_in_page"] + ($data_per_page * ($data_pagination["page_no"] - 1))) . " dari " . $data_pagination["data_found"] . " Hasil";

        // Pagination Configuration
        $this->load->library('pagination');
        $config['base_url'] = '#!';
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['next_link']        = '>';
        $config['prev_link']        = '<';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item d-none"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item d-none"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
        $config['total_rows'] = $data_pagination["data_found"];
        $config['cur_page'] =  $data_pagination["page_no"];
        $config['per_page'] = $data_per_page;
        $config['num_links'] = 6;


        $this->pagination->initialize($config);
        $data_pagination['html_data'] = $this->pagination->create_links();

        if (empty($query->result_array())) {
            return [array('message' => 'Data Tidak Ditemukan')];
        } else
            return [array(
                "filtered_data" => $query->result_array(),
                "pagination_data" => [$data_pagination]
            )];
    }

    public function list_data_kskps($data)
    {
        // default
        $data_per_page = $data['data_per_page'];

        $data_pagination = array(
            "page_no" => 0,
            "data_found_in_page" => 0,
            "page_available" => 0,
            "data_found" => 0,
            "data_shown_to_user" => 0,
            "html_data" => ''
        );

        $this->db->select('keluarga.*, kecamatan.kecamatan, desa.desa, dusun.dusun, ROW_NUMBER() OVER() AS nomer_tabel');
        $this->db->from('keluarga');
        $this->db->join('kecamatan', 'keluarga.id_kecamatan = kecamatan.id');
        $this->db->join('desa', 'keluarga.id_desa = desa.id');
        $this->db->join('dusun', 'keluarga.id_dusun = dusun.id');

        // add filter on kecamatan
        if ($data['id_kecamatan'] != '')
            $this->db->where('keluarga.id_kecamatan', $data['id_kecamatan']);

        // add filter on desa
        if ($data['id_desa'] != '')
            $this->db->where('keluarga.id_desa', $data['id_desa']);

        // add filter on dusun
        if ($data['id_dusun'] != '')
            $this->db->where('keluarga.id_dusun', $data['id_dusun']);

        // add filter on RT
        if ($data['int_rt'] != 0)
            $this->db->where('keluarga.rt', $data['int_rt']);

        // add filter on RW
        if ($data['int_rw'] != 0)
            $this->db->where('keluarga.rw', $data['int_rw']);

        // add filter on status keluarga
        if ($data['status_keluarga'] != '')
            $this->db->where('keluarga.status', $data['status_keluarga']);

        // add filter on kepala keluarga
        if ($data['kepala_keluarga'] != '')
            $this->db->like('keluarga.name_kk', $data['kepala_keluarga'], 'both');

        $data_pagination["page_no"] =  ($data['page'] <= 1) ? 1 : (int)$data['page'];

        $count_query = clone $this->db;
        $data_pagination["data_found"] = $count_query->count_all_results();
        $data_pagination["page_available"] =    ceil($data_pagination["data_found"] / $data_per_page);


        if ($data['page'] == '' ||  $data['page'] <= 1)
            $this->db->limit($data_per_page, 0);
        else
            $this->db->limit($data_per_page, ($data_per_page * ($data_pagination["page_no"] - 1)));

        $query = $this->db->get();
        $data_pagination["data_found_in_page"]  =  $query->num_rows();
        $data_pagination["data_shown_to_user"] = ($data_per_page * ($data_pagination["page_no"] - 1)) + 1 . " sampai " . ($data_pagination["data_found_in_page"] + ($data_per_page * ($data_pagination["page_no"] - 1))) . " dari " . $data_pagination["data_found"] . " Hasil";


        // Pagination Configuration
        $this->load->library('pagination');
        $config['base_url'] = '#!';
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['next_link']        = '>';
        $config['prev_link']        = '<';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item d-none"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item d-none"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
        $config['total_rows'] = $data_pagination["data_found"];
        $config['cur_page'] =  $data_pagination["page_no"];
        $config['per_page'] = $data_per_page;
        $config['num_links'] = 6;


        $this->pagination->initialize($config);
        $data_pagination['html_data'] = $this->pagination->create_links();

        if (empty($query->result_array())) {
            return [array('message' => 'Data Tidak Ditemukan')];
        } else
            return [array(
                "filtered_data" => $query->result_array(),
                "pagination_data" => [$data_pagination]
            )];
    }

    public function detail_data_kskps($data)
    {
        // default
        $this->db->select('keluarga.*, kecamatan.kecamatan, desa.desa, dusun.dusun');
        $this->db->from('keluarga');
        $this->db->join('kecamatan', 'keluarga.id_kecamatan = kecamatan.id');
        $this->db->join('desa', 'keluarga.id_desa = desa.id');
        $this->db->join('dusun', 'keluarga.id_dusun = dusun.id');
        $this->db->where('keluarga.id', $data['id_keluarga']);

        $query = $this->db->get();

        $this->db->select('question.id, question.question, answer.choice');
        $this->db->from('question');
        $this->db->join('answer', 'answer.id_question = question.id');
        $this->db->where('answer.id_keluarga', $data['id_keluarga']);

        $query_kuisioner = $this->db->get();


        return [array(
            "keluarga" => $query->result_array(),
            "kuisioner" => $query_kuisioner->result_array(),
        )];
    }


    public function detail_data_kskps_for_edit($data)
    {
        // default
        $this->db->select('keluarga.*, kecamatan.kecamatan, desa.desa, dusun.dusun');
        $this->db->from('keluarga');
        $this->db->join('kecamatan', 'keluarga.id_kecamatan = kecamatan.id');
        $this->db->join('desa', 'keluarga.id_desa = desa.id');
        $this->db->join('dusun', 'keluarga.id_dusun = dusun.id');
        $this->db->where('keluarga.id', $data['id_keluarga']);

        $query = $this->db->get();

        $this->db->select('question.type as JENIS, question.id as NO, question.question as PERTANYAAN, answer.choice');
        $this->db->from('question');
        $this->db->join('answer', 'answer.id_question = question.id');
        $this->db->where('answer.id_keluarga', $data['id_keluarga']);

        $query_kuisioner = $this->db->get();

        $this->db->select('question.id as NO, choice.choice as Answer');
        $this->db->from('question');
        $this->db->join('choice', 'question.id = choice.id_question');

        $query_kuisioner_choice = $this->db->get();

        $result["status"] = 200;
        // remove double bracket
        $result["keluarga"] = $query->result_array();

        foreach ($query_kuisioner->result_array() as $question) {
            $questionItem = [


                "JENIS" => $question["JENIS"],
                "NO" => $question["NO"],
                "PERTANYAAN" => $question["PERTANYAAN"],
                // "choice" => $question["choice"],
                "PILIHAN" => []
            ];

            if ($question["JENIS"] == "MULTIPLE_ANSWER") {
                $decodedChoice = json_decode($question["choice"], true);
            } else {
                $decodedChoice = $question["choice"];
            }

            foreach ($query_kuisioner_choice->result_array() as $choice) {
                if (is_array($decodedChoice)) {
                    if ($choice["NO"] == $question["NO"]) {
                        $value = in_array($choice["Answer"], $decodedChoice) ? true : false;

                        $questionItem["PILIHAN"][] = [
                            "Answer" => $choice["Answer"],
                            "Value" => $value
                        ];
                    }
                } else {
                    if ($choice["NO"] == $question["NO"]) {
                        if ($choice["Answer"] == $question["choice"]) {
                            $value = true;
                        } else {
                            $value = false;
                        }
                        $questionItem["PILIHAN"][] = [
                            "Answer" => $choice["Answer"],
                            "Value" => $value
                        ];
                    }
                }
            }

            $result["data"][] = $questionItem;
        }

        return [$result];
    }

    public function data_choice_and_answer()
    {

        $this->db->select('question.type as JENIS, question.id as NO, question.question as PERTANYAAN');
        $this->db->from('question');
        $query = $this->db->get();

        $this->db->select('question.type as JENIS, question.id as NO, question.question as PERTANYAAN, choice');
        $this->db->from('question');
        $this->db->join('choice', 'question.id = choice.id_question');

        $query_kuisioner = $this->db->get();

        foreach ($query->result_array() as $question) {

            $questionItem = [
                "JENIS" => $question["JENIS"],
                "NO" => $question["NO"],
                "PERTANYAAN" => $question["PERTANYAAN"],
                "PILIHAN" => []
            ];

            foreach ($query_kuisioner->result_array() as $choice) {
                if ($choice["PERTANYAAN"] == $question["PERTANYAAN"])
                    $questionItem["PILIHAN"][] = [
                        "Answer" => $choice["choice"],
                        "Value" => false
                    ];
            }

            $result["data"][] = $questionItem;
        }

        return [$result];
    }

    public function save_kuesioner($data)
    {
        // Insert data into 'keluarga' table and get the inserted ID
        $this->db->insert('keluarga', $data['data_keluarga']);
        $id_keluarga = $this->db->insert_id();

        $kuisionerData = $data['kuisioner'];

        // Start a database transaction
        $this->db->trans_start();

        foreach ($kuisionerData as $kuisioner) {
            $id_question = $kuisioner['id_question'];
            $answer = $kuisioner['answer'];

            $this->db->insert('answer', ['id_keluarga' => $id_keluarga, 'id_question' => $id_question, 'choice' => $answer]);
        }

        // Commit the database transaction
        $this->db->trans_complete();

        // Additional PROCEDURAL FUNCTION to calculate score and status
        $call_for_update = $this->db->query("CALL calculate_status_keluarga_based_on_answer_v2($id_keluarga)");

        if ($this->db->trans_status()) {
            // Query to retrieve data from 'keluarga' table
            $this->db->select('keluarga.*, kecamatan.kecamatan, desa.desa, dusun.dusun');
            $this->db->from('keluarga');
            $this->db->join('kecamatan', 'keluarga.id_kecamatan = kecamatan.id');
            $this->db->join('desa', 'keluarga.id_desa = desa.id');
            $this->db->join('dusun', 'keluarga.id_dusun = dusun.id');
            $this->db->where('keluarga.id', $id_keluarga);

            // Execute the query and return the result
            $query = $this->db->get();
            return ['code' => '200', 'message' => 'Sukses menyimpan jawaban!', 'keluarga' => $query->result_array()];
        } else {
            return ['code' => '401', 'message' => 'Update Ditolak! Data tidak ditemukan.', 'data' => $data['data_keluarga']];
        }
    }

    public function save_edit_kuesioner($data)
    {

        $id_keluarga =  $data['id_keluarga'];
        $data_keluarga =  $data['data_keluarga'];
        $kuisionerData = $data['kuisioner'];


        // Start a database transaction
        $this->db->trans_start();

        foreach ($kuisionerData as $kuisioner) {
            $id_question = $kuisioner['id_question'];
            $answer = $kuisioner['answer'];

            // DO NOT NEED THIS CODE BECAUSE EDIT FUNCTION IS NOT EXIST

            // Check if the record exists in the 'answer' table
            $q = $this->db->get_where('answer', ['id_keluarga' => $id_keluarga, 'id_question' => $id_question]);

            if ($q->num_rows() > 0) {
                // Update the existing record in the 'answer' table
                $id = $q->row()->id;
                $this->db->where('id', $id);
                $this->db->update('answer', ['choice' => $answer]);
            } else {
                // Insert a new record into the 'answer' table
                $this->db->insert('answer', ['id_keluarga' => $id_keluarga, 'id_question' => $id_question, 'choice' => $answer]);
            }
        }

        // Commit the database transaction
        $this->db->trans_complete();

        // Additional PROCEDURAL FUNCTION to calculate score and status
        $call_for_update = $this->db->query("CALL calculate_status_keluarga_based_on_answer_v2($id_keluarga)");

        $this->db->update('keluarga', [
            "nik" => $data_keluarga['nik'],
            "name_kk" => $data_keluarga['name_kk'],
            "gender_kk" => $data_keluarga['gender_kk'],
            "id_kecamatan" => $data_keluarga['id_kecamatan'],
            "id_desa" => $data_keluarga['id_desa'],
            "id_dusun" => $data_keluarga['id_dusun'],
            "rw" => $data_keluarga['rw'],
            "rt" => $data_keluarga['rt'],
            // "created_at" => date('Y-m-d H:i:s'),
        ], ['id' => $id_keluarga]);

        if ($this->db->trans_status()) {
            // Query to retrieve data from 'keluarga' table
            $this->db->select('keluarga.*, kecamatan.kecamatan, desa.desa, dusun.dusun');
            $this->db->from('keluarga');
            $this->db->join('kecamatan', 'keluarga.id_kecamatan = kecamatan.id');
            $this->db->join('desa', 'keluarga.id_desa = desa.id');
            $this->db->join('dusun', 'keluarga.id_dusun = dusun.id');
            $this->db->where('keluarga.id', $id_keluarga);

            // Execute the query and return the result
            $query = $this->db->get();
            return ['code' => '200', 'message' => 'Sukses menyimpan jawaban!', 'keluarga' => $query->result_array()];
        } else {
            return ['code' => '401', 'message' => 'Update Ditolak! Data tidak ditemukan.', 'data' => $data['data_keluarga']];
        }
    }

    public function add_rekap($response_data)
    {
        // Init rekap variable
        $keluarga = $response_data['keluarga'];
        if (!empty($keluarga)) {
            $kecamatan = $keluarga[0]['id_kecamatan'];
            $gender = $keluarga[0]['gender_kk'];
            $desa = $keluarga[0]['id_desa'];
            $dusun = $keluarga[0]['id_dusun'];
            $status = $keluarga[0]['status'];
            $rt = (int)$keluarga[0]['rt'];
            $rw = (int)$keluarga[0]['rw'];
        }

        $columnGenderStatus = '';
        $columStatus = '';

        if ($gender == 'PEREMPUAN') {
            $columnGenderStatus = ($status == 'SEJAHTERA') ? 'kk_wanita_sejahtera' : 'kk_wanita_pra';
            $columStatus = ($status == 'SEJAHTERA') ? 'sejahtera' : 'pra_sejahtera';
        } else {
            $columnGenderStatus = ($status == 'SEJAHTERA') ? 'kk_pria_sejahtera' : 'kk_pria_pra';
            $columStatus = ($status == 'SEJAHTERA') ? 'sejahtera' : 'pra_sejahtera';
        }

        // Check if a record with the specified filters exists
        $this->db->where('id_kecamatan', $kecamatan);
        $this->db->where('id_desa', $desa);
        $this->db->where('id_dusun', $dusun);
        $this->db->where('rw', $rw);
        $this->db->where('rt', $rt);
        $query = $this->db->get('rekap');

        if ($query->num_rows() > 0) {
            // Record exists, perform an UPDATE operation
            $this->db->set('total', 'total + 1', FALSE);
            $this->db->set($columnGenderStatus, "$columnGenderStatus + 1", FALSE);
            $this->db->set($columStatus, "$columStatus + 1", FALSE);
            $this->db->where('id_kecamatan', $kecamatan);
            $this->db->where('id_desa', $desa);
            $this->db->where('id_dusun', $dusun);
            $this->db->where('rw', $rw);
            $this->db->where('rt', $rt);
            $this->db->update('rekap');
        } else {
            // Record doesn't exist, perform an INSERT operation
            $data = array(
                'id_kecamatan' => $kecamatan,
                'id_desa' => $desa,
                'id_dusun' => $dusun,
                'rw' => $rw,
                'rt' => $rt,
                'total' => 1,
                $columnGenderStatus => 1,
                $columStatus => 1
            );

            $this->db->insert('rekap', $data);
        }
    }

    public function subtract_rekap($data)
    {
        // Check if the data exists
        $id = $data['id'];
        $keluarga = $this->db->get_where('keluarga', array('id' => $id))->row();

        // Init rekap variable
        if (!empty($keluarga)) {
            $kecamatan = $keluarga->id_kecamatan;
            $gender = $keluarga->gender_kk;
            $desa = $keluarga->id_desa;
            $dusun = $keluarga->id_dusun;
            $status = $keluarga->status;
            $rt = (int)$keluarga->rt;
            $rw = (int)$keluarga->rw;
        }

        $columnGenderStatus = '';
        $columStatus = '';

        if ($gender == 'PEREMPUAN') {
            $columnGenderStatus = ($status == 'SEJAHTERA') ? 'kk_wanita_sejahtera' : 'kk_wanita_pra';
            $columStatus = ($status == 'SEJAHTERA') ? 'sejahtera' : 'pra_sejahtera';
        } else {
            $columnGenderStatus = ($status == 'SEJAHTERA') ? 'kk_pria_sejahtera' : 'kk_pria_pra';
            $columStatus = ($status == 'SEJAHTERA') ? 'sejahtera' : 'pra_sejahtera';
        }

        // // Check if a record with the specified filters exists
        $this->db->where('id_kecamatan', $kecamatan);
        $this->db->where('id_desa', $desa);
        $this->db->where('id_dusun', $dusun);
        $this->db->where('rw', $rw);
        $this->db->where('rt', $rt);
        $query = $this->db->get('rekap');

        if ($query->num_rows() > 0) {
            // Record exists, perform an UPDATE operation
            $this->db->set('total', 'total - 1', FALSE);
            $this->db->set($columnGenderStatus, "$columnGenderStatus - 1", FALSE);
            $this->db->set($columStatus, "$columStatus - 1", FALSE);
            $this->db->where('id_kecamatan', $kecamatan);
            $this->db->where('id_desa', $desa);
            $this->db->where('id_dusun', $dusun);
            $this->db->where('rw', $rw);
            $this->db->where('rt', $rt);
            $this->db->update('rekap');
        }
    }

    public function delete_data_kskps($data)
    {
        // Check if the data exists
        $id = $data['id'];
        $existing_data = $this->db->get_where('keluarga', array('id' => $id))->row();

        if ($existing_data) {
            // Data exists, delete it
            $this->db->delete('answer', array('id_keluarga' => $id));
            $this->db->delete('keluarga', array('id' => $id));


            // Return the ID and 'success' status
            return array('id' => $id, 'status' => 'Success delete data keluarga');
        } else {
            // Data doesn't exist, return 0 as ID and 'error' status
            return array('id' => 0, 'status' => 'Error delete data keluarga, Data not found');
        }
    }

    public function list_choice_kuesioner()
    {

        $query = $this->db->query("SELECT * FROM `choice`");
        return $query->result_array();
    }


    public function list_data_jawaban($data)
    {
        // default
        $data_per_page = $data['data_per_page'];

        $data_pagination = array(
            "page_no" => 0,
            "data_found_in_page" => 0,
            "page_available" => 0,
            "data_found" => 0,
            "data_shown_to_user" => 0,
            "html_data" => ''
        );

        $this->db->select('q.id AS question_id, q.question, COUNT(DISTINCT a.id_keluarga) AS total_keluarga');
        $this->db->from('question as q');
        $this->db->join('answer as a', 'q.id = a.id_question', 'left');
        $this->db->join('keluarga as k', 'a.id_keluarga = k.id', 'left');

        if ($data['id_kecamatan'] != '') {
            $this->db->where('k.id_kecamatan', $data['id_kecamatan']);
        }
        $this->db->group_by('q.id, q.question');
        $data_pagination["page_no"] =  ($data['page'] <= 1) ? 1 : (int)$data['page'];

        $count_query = clone $this->db;
        $data_pagination["data_found"] = $count_query->count_all_results();
        $data_pagination["page_available"] =    ceil($data_pagination["data_found"] / $data_per_page);


        if ($data['page'] == '' ||  $data['page'] <= 1)
            $this->db->limit($data_per_page, 0);
        else
            $this->db->limit($data_per_page, ($data_per_page * ($data_pagination["page_no"] - 1)));

        $query = $this->db->get();
        $data_pagination["data_found_in_page"]  =  $query->num_rows();
        $data_pagination["data_shown_to_user"] = ($data_per_page * ($data_pagination["page_no"] - 1)) + 1 . " sampai " . ($data_pagination["data_found_in_page"] + ($data_per_page * ($data_pagination["page_no"] - 1))) . " dari " . $data_pagination["data_found"] . " Hasil";


        // Pagination Configuration
        $this->load->library('pagination');
        $config['base_url'] = '#!';
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['next_link']        = '>';
        $config['prev_link']        = '<';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item d-none"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item d-none"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
        $config['total_rows'] = $data_pagination["data_found"];
        $config['cur_page'] =  $data_pagination["page_no"];
        $config['per_page'] = $data_per_page;
        $config['num_links'] = 6;


        $this->pagination->initialize($config);
        $data_pagination['html_data'] = $this->pagination->create_links();

        if (empty($query->result_array())) {
            return [array('message' => 'Data Tidak Ditemukan')];
        } else
            return [array(
                "filtered_data" => $query->result_array(),
                "pagination_data" => [$data_pagination]
            )];
    }
}
