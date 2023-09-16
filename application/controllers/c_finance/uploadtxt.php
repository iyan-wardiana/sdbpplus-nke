<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 April 2015
 * File Name	= uploadtxt.php
 * Function		= -
 * Location		= ./system/application/controllers/c_itmng/uploadtxt.php
*/
?>
<?php 
class uploadtxt extends Controller {
 
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('Menu_model', '', TRUE);
		$this->load->model('m_itmng/m_uploadtxt/uploadtxt_model', '', TRUE);
	}
 	
	var $limit = 2;
	var $title = 'NKE ITSys';
	
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$data['error'] 		= '';
			$data['title'] 		= '';
			$data['h1_title']	= 'Upload and Export File';
			$data['h2_title']	= 'Upload .txt to .csv';
			$data['main_view'] = 'v_itmng/v_uploadtxt/uploadtxt';
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
 
	function do_upload()
	{
		$config['upload_path'] = 'system/application/views/v_itmng/v_uploadtxt/Uploads/';
		$config['allowed_types'] = 'txt';
		$config['max_size']	= '1000';
 
		$this->load->library('upload', $config);
 
		if ( ! $this->upload->do_upload())
		{
			$data['fileName'] = '';	
			$data['selAllFile'] = $this->uploadtxt_model->getAllFile();
			$data['FileUpName1'] = '';
			
			$data['error'] 		= 'Error';
			$data['title'] 		= $this->title;
			$data['h1_title']	= 'Upload and Export File';
			$data['h2_title']	= 'Error Upload .txt to .csv';
			$data['main_view'] 	= 'v_itmng/v_uploadtxt/uploadtxt';
 			$this->load->view('template', $data);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			
			$countData = $this->uploadtxt_model->getAllFile();
            $data['fileName'] = $this->upload->file_name;
			$data['selAllFile'] = $this->uploadtxt_model->getAllFile();
			$selLastFile = $this->uploadtxt_model->getLastFile()->row();
			if($countData == 0)
			{
				$data['FileUpName1'] = 0;
			}
			else
			{
				$data['FileUpName1'] = $selLastFile->FileUpName;
			}

			$data['error'] 		= 'Sukses';
			$data['title'] 		= $this->title;
			$data['h1_title']	= 'Upload and Export File';
			$data['h2_title']	= 'Sukse Upload .txt to .csv';
			$data['main_view'] 	= 'v_itmng/v_uploadtxt/uploadtxt';
 			$this->load->view('template', $data);
			//$this->load->view('sukses', $data);
		}
	}
	
	function export_txt()
	{
		$data['selAllFile'] = $this->uploadtxt_model->getAllFile();
		$selLastFile = $this->uploadtxt_model->getLastFile()->row();
		$data['FileUpName'] = $selLastFile->FileUpName;
			
		$data['selectAllData'] = $this->uploadtxt_model->count_all_num_rowsAllData();
		$data['viewAllData'] 	= $this->uploadtxt_model->viewAllData()->result();
		$this->load->view('v_itmng/v_uploadtxt/uploadtxt_export', $data);
	}
}
?>