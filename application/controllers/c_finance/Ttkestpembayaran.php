<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Maret 2017
 * File Name	= Ttkestpembayaran.php
 * Location		= -
*/
?>
<?php 
class Ttkestpembayaran extends CI_Controller
{
 	// Start : Index tiap halaman
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/ttkestpembayaran/uploadIndex/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function uploadIndex()
	{
		$this->load->model('m_finance/m_ttkestpembayaran/m_ttkestpembayaran', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['myFormAction'] 	= site_url('c_finance/ttkestpembayaran/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Export File';
			$data['h3_title']	= 'TTK';
			$data['error'] 		= '';
			$data['isUploaded']	= 0;
			
			$this->load->view('v_finance/v_ttkestpembayaran/ttkestpembayaran', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
 
	function do_upload()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		// Secure URL
		$data['myFormAction'] 	= site_url('c_finance/ttkestpembayaran/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		
		$config['upload_path'] 		= 'ttk_upload/';
		$config['allowed_types']	= 'txt';
		$config['max_size']			= '1000000';
		
		$this->load->library('upload', $config);
		
		$files 			= $_FILES;
    	$cpt 			= count($_FILES['userfile']['name']);
		$cptxy 			= 0;
					
		for($i=0; $i<$cpt; $i++)
		{
			$cptyess = $_FILES['userfile']['name'][$i];
			$cptyessx = strlen($cptyess);
			if($cptyessx > 0)
			{
				// hapus file
				$myPath = "ttk_upload/$cptyess";
				if(file_exists("ttk_upload/$cptyess"))
				{
					unlink($myPath);
				}
				$cptxy = $cptxy + 1;
			}
		}
		
		$cpt = $cptxy;
		$ipaddress = 'UNKNOWN';
		$empID	= $this->session->userdata('Emp_ID');
		$insertFileName = "DELETE FROM tbl_currentupttkest WHERE empID = '$empID'";
		$this->db->query($insertFileName);
			
		for($i=0; $i<$cpt; $i++)
		{
			$myFileName 		= $files['userfile']['name'][$i];
			
			$myFileNamex[$i] 	= $files['userfile']['name'][$i];
			$dateNow 			= date('Y-m-d');
			
			$insertFileName = "INSERT INTO tbl_uploadttkest(FileUpName, dateUplaod, empID) values ('$myFileName', '$dateNow', '$empID')";
			$this->db->query($insertFileName);
			
			$insertVurrFileName = "INSERT INTO tbl_currentupttkest(FileUpName, empID, IPAddress) values ('$myFileName', '$empID', '$ipaddress')";
			$this->db->query($insertVurrFileName);
			
			$myFileNameShow = implode(", ", $myFileNamex);
			$_FILES['userfile']['name']= $files['userfile']['name'][$i];
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];    
	
			//$this->upload->initialize($this->set_upload_options());
			$this->upload->do_upload('userfile');
		}
		
		$data['isUploaded']		= 1;
		$data['myFileNameShow'] = $myFileNameShow;
		$data['title'] 			= $appName;
		$data['h2_title']		= 'Export File';
		$data['h3_title']		= 'TTK';
		$data['error'] 			= 'Sukses';
		
		$this->load->view('v_finance/v_ttkestpembayaran/ttkestpembayaran', $data);
	}
	
	function set_upload_options()
	{
		$config = array();
		$config['upload_path'] = 'system/application/views/v_finance/v_ttkestpembayaran/Uploads/';
		$config['allowed_types'] = 'txt';
		$config['max_size']      = '10000';
		$config['overwrite']     = FALSE;
	
		return $config;
	}

	function export_txt($isExcel)
	{
		$this->load->model('m_finance/m_ttkestpembayaran/m_ttkestpembayaran', '', TRUE);
		
		//$isExcel	= $_GET['id'];
		//$isExcel	= $this->url_encryption_helper->decode_url($isExcel);
			
		$empID		= $this->session->userdata('Emp_ID');
		$data['isExcel'] = $isExcel;
		$data['selAllFile'] = $this->m_ttkestpembayaran->getAllFile($empID);
		//$selLastFile = $this->decreaseinvoice_model->getLastFile()->row();
		//$data['FileUpName'] = $selLastFile->FileUpName;
		
		$data['viewAllData'] 	= $this->m_ttkestpembayaran->viewAllData()->result();
		$this->load->view('v_finance/v_ttkestpembayaran/ttkestpembayaran_report', $data);
	}
}
?>