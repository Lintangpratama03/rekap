<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
	}

	function set_header()
	{
		$this->output->set_header('HTTP/1.0 200 OK');
	}

	public function index()
	{
		if (!$this->session->userdata('user_name')) {
			$data['title']  = "In Kelud - Mas Bup";
			$this->load->view('home/login', $data);
		} else {
			$this->load->database();
			$this->load->model('api_model');

			$list_kecamatan = $this->api_model->list_kecamatan();
			$data['list_kecamatan'] = ($list_kecamatan);

			$list_desa = $this->api_model->list_desa('');
			$data['list_desa'] = ($list_desa);

			$list_dusun = $this->api_model->list_dusun('');
			$data['list_dusun'] = ($list_dusun);

			$list_status_keluarga = $this->api_model->list_status_keluarga();
			$data['list_status_keluarga'] = ($list_status_keluarga);

			$data['title']  = "In Kelud - Mas Bup";
			$data['title_h1']  = "In Kelud - Mas Bup";
			$data['active_sidebar']  = "In Kelud";
			$this->load->view('admin/dashboard', $data);
		}
	}

	public function grafik_sejahtera()
	{
		$this->load->database();

		$kecamatan = $this->input->post('kecamatan');
		$desa = $this->input->post('desa');
		$dusun = $this->input->post('dusun');
		$rt = (int)$this->input->post('rt');
		$rw = (int)$this->input->post('rw');

		$this->db->select(
			'sum(rekap.kk_pria_sejahtera) as kk_pria_sejahtera, '
				. 'sum(rekap.kk_wanita_sejahtera) as kk_wanita_sejahtera,'
				. 'sum(rekap.sejahtera) as total, '
		);
		$this->db->from('rekap');
		$this->db->join('kecamatan', 'rekap.id_kecamatan = kecamatan.id');
		$this->db->join('desa', 'rekap.id_desa = desa.id');
		$this->db->join('dusun', 'rekap.id_dusun = dusun.id');

		// add filter on kecamatan
		if ($kecamatan != '') {
			$this->db->where('rekap.id_kecamatan', $kecamatan);
		}

		// add filter on desa
		if ($desa != '') {
			$this->db->where('rekap.id_desa', $desa);
		}

		// add filter on dusun
		if ($dusun != '') {
			$this->db->where('rekap.id_dusun', $dusun);
		}

		// add filter on RT
		if ($rt != 0) {
			$this->db->where('rekap.rt', $rt);
		}

		// add filter on RW
		if ($rw != 0) {
			$this->db->where('rekap.rw', $rw);
		}

		$query = $this->db->get();
		echo json_encode($query->row_array());
	}

	public function grafik_pra()
	{
		$this->load->database();

		$kecamatan = $this->input->post('kecamatan');
		$desa = $this->input->post('desa');
		$dusun = $this->input->post('dusun');
		$rt = (int)$this->input->post('rt');
		$rw = (int)$this->input->post('rw');

		$this->db->select(
			'sum(rekap.kk_wanita_pra) as kk_wanita_pra, '
				. 'sum(rekap.kk_pria_pra) as kk_pria_pra, '
				. 'sum(rekap.pra_sejahtera) as total,'
		);
		$this->db->from('rekap');
		$this->db->join('kecamatan', 'rekap.id_kecamatan = kecamatan.id');
		$this->db->join('desa', 'rekap.id_desa = desa.id');
		$this->db->join('dusun', 'rekap.id_dusun = dusun.id');

		// add filter on kecamatan
		if ($kecamatan != '') {
			$this->db->where('rekap.id_kecamatan', $kecamatan);
		}

		// add filter on desa
		if ($desa != '') {
			$this->db->where('rekap.id_desa', $desa);
		}

		// add filter on dusun
		if ($dusun != '') {
			$this->db->where('rekap.id_dusun', $dusun);
		}

		// add filter on RT
		if ($rt != 0) {
			$this->db->where('rekap.rt', $rt);
		}

		// add filter on RW
		if ($rw != 0) {
			$this->db->where('rekap.rw', $rw);
		}

		$query = $this->db->get();
		echo json_encode($query->row_array());
	}

	public function logout()
	{
		$this->session->sess_destroy();

		$data['title']  = "In Kelud - Mas Bup";
		$this->load->view('home/login', $data);
	}

	public function auth()
	{
		$this->load->database();
		$this->load->model('auth_model');

		$data = array(
			'name' => $this->input->post('name'),
			'pass' => $this->input->post('pass'),
		);

		// check and verivy with same api
		$r = $this->auth_model->login_web($data);
		$data_r = ($r[0]);

		// Check the "code" value
		if ($data_r && isset($data_r['code']) && $data_r['code'] === '200') {
			redirect();
		} else {
			// if not good return wrong combination and redirect to login page
			$data['title']  = "In Kelud - Mas Bup";
			$data['message']  = $data_r['message'];
			$this->load->view('home/login', $data);
		}
	}


	public function inkelud()
	{

		if (!$this->session->userdata('user_name')) {
			redirect();
		}

		$data['title']  = "In Kelud - Mas Bup";
		$data['title_h1']  = "In Kelud - Mas Bup";
		$data['active_sidebar']  = "In Kelud";
		$this->load->view('admin/dashboard', $data);
	}

	public function rekapitulasi_kskps()
	{
		if (!$this->session->userdata('user_name')) {
			redirect();
		}

		$this->load->library('pagination');
		$this->load->database();
		$this->load->model('api_model');

		$list_kecamatan = $this->api_model->list_kecamatan();
		$data['list_kecamatan'] = ($list_kecamatan);

		$list_desa = $this->api_model->list_desa('');
		$data['list_desa'] = ($list_desa);

		$list_dusun = $this->api_model->list_dusun('');
		$data['list_dusun'] = ($list_dusun);

		$list_status_keluarga = $this->api_model->list_status_keluarga();
		$data['list_status_keluarga'] = ($list_status_keluarga);

		$data['title']  = "Rekapitulasi KS/KPS";
		$data['title_h1']  = "Rekapitulasi KS/KPS Kabupaten Kediri";
		$data['active_sidebar']  = "Rekapitulasi KS/KPS";
		$this->load->view('admin/rekapitulasi_kskps', $data);
	}

	public function detail_question()
	{
		if (!$this->session->userdata('user_name')) {
			redirect();
		}
		$this->load->model('api_model');
		$this->load->database();

		$list_kecamatan = $this->api_model->list_kecamatan();
		$data['list_kecamatan'] = ($list_kecamatan);

		$list_desa = $this->api_model->list_desa('');
		$data['list_desa'] = ($list_desa);

		$list_dusun = $this->api_model->list_dusun('');
		$data['list_dusun'] = ($list_dusun);

		$encodedId = $this->uri->segment(3);
		$data['id_question'] = base64_decode($encodedId);

		$data['pertanyaan'] = $this->api_model->list_pertanyaan_id($data['id_question']);

		$data['title']  = "Detail pertanyaan";
		$data['title_h1']  = "Detail pertanyaan";
		$data['active_sidebar']  = "Detail pertanyaan";
		$this->load->view('admin/detail_question', $data);
	}

	public function detail_kskps()
	{
		if (!$this->session->userdata('user_name')) {
			redirect();
		}

		$data['id_keluarga'] = $this->uri->segment(3);

		$data['title']  = "Detail Data Keluarga";
		$data['title_h1']  = "Detail Data Keluarga";
		$data['active_sidebar']  = "Tabel KS/KPS";
		$this->load->view('admin/detail_keluarga', $data);
	}

	public function table_kskps()
	{
		if (!$this->session->userdata('user_name')) {
			redirect();
		}

		$this->load->database();
		$this->load->model('api_model');
		$list_kecamatan = $this->api_model->list_kecamatan();
		$data['list_kecamatan'] = ($list_kecamatan);

		$list_desa = $this->api_model->list_desa('');
		$data['list_desa'] = ($list_desa);

		$list_dusun = $this->api_model->list_dusun('');
		$data['list_dusun'] = ($list_dusun);

		$list_status_keluarga = $this->api_model->list_status_keluarga();
		$data['list_status_keluarga'] = ($list_status_keluarga);

		$data['title']  = "Tabel KS/KPS";
		$data['title_h1']  = "Tabel KS/KPS Kabupaten Kediri";
		$data['active_sidebar']  = "Tabel KS/KPS";
		$this->load->view('admin/table_kskps', $data);
	}

	public function detail_pertanyaan()
	{
		if (!$this->session->userdata('user_name')) {
			redirect();
		}
		$this->load->library('pagination');
		$this->load->database();
		$this->load->model('api_model');

		$list_kecamatan = $this->api_model->list_kecamatan();
		$data['list_kecamatan'] = ($list_kecamatan);

		$data['title']  = "Detail Data Jawaban";
		$data['title_h1']  = "Detail Data Jawaban";
		$data['active_sidebar']  = "Tabel Jawaban";
		// var_dump($data);
		$this->load->view('admin/rekapitulasi_jawaban', $data);
	}
}
