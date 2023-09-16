<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2017
 * File Name	= C_admin_ttk.php
 * Location		= -
*/
?>
<?php
class C_admin_ttk extends CI_Controller
{
 	function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_admin_ttk/uploadIndex/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function uploadIndex($offset=0) // OK
	{
		$this->load->model('m_finance/m_admin_ttk/m_admin_ttk', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);			
			
			$data['myFormAction']	= site_url('c_finance/c_admin_ttk/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['error'] 		= '';
			$data['title'] 		= $appName;
			$data['isUploaded']	= 0;
			$data['h1_title']	= 'Export File';
			$data['h2_title']	= 'Upload .txt to .xls';
			
			$this->load->view('v_finance/v_admin_ttk/admin_ttk', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
 
	function do_upload() // OK
	{
		$this->load->model('m_finance/m_admin_ttk/m_admin_ttk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		// Secure URL
		$data['myFormAction'] 		= site_url('c_finance/c_admin_ttk/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
		
		$config['upload_path'] 		= 'ttk_upload/';
		$config['allowed_types'] 	= 'txt';
		$config['max_size']			= '1000000';
				
		$this->load->library('upload', $config);
		
		$files 			= $_FILES;
    	$cpt 			= count($_FILES['userfile']['name']);
		$cptxy 			= 0;
		echo "hix $cpt";
			
		for($i=0; $i<$cpt; $i++)
		{
			$cptyess = $_FILES['userfile']['name'][$i];
			$cptyessx = strlen($cptyess);
			echo "hahaha $cptyessx";
			if($cptyessx > 0)
			{
				// hapus file
				//$myPath = "ttk_upload/$cptyess";
				//unlink($myPath);
				$cptxy = $cptxy + 1;
			}
		}
		
		$cpt = $cptxy;
		$ipaddress = 'UNKNOWN';
		$empID	= $this->session->userdata('Emp_ID');
		$insertFileName = "DELETE FROM tbl_currentupreceipt WHERE empID = '$empID'";
		$this->db->query($insertFileName);
			
		for($i=0; $i<$cpt; $i++)
		{
			$myFileName = $files['userfile']['name'][$i];
			$myFileNamex[$i] = $files['userfile']['name'][$i];
			$dateNow = date('Y-m-d');
			
			$insertFileName = "INSERT INTO tbl_uploadreceipt(FileUpName, dateUplaod, empID) values ('$myFileName', '$dateNow', '$empID')";
			$this->db->query($insertFileName);
			
			$insertVurrFileName = "INSERT INTO tbl_currentupreceipt(FileUpName, empID, IPAddress) values ('$myFileName', '$empID', '$ipaddress')";
			$this->db->query($insertVurrFileName);
			
			$myFileNameShow = implode(", ", $myFileNamex);
			$_FILES['userfile']['name']= $files['userfile']['name'][$i];
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];    
	
			$this->upload->initialize($this->set_upload_options());
			$this->upload->do_upload();
		}
		
		$data['isUploaded']		= 1;
		$data['myFileNameShow'] = $myFileNameShow;
		$data['error'] 			= 'Sukses';
		$error 				= 'Sukses';
		$data['title'] 			= $appName;
		$data['h1_title']		= 'Upload and Export File';
		$data['h2_title']		= 'Upload Succesfully.';
		
		$this->load->view('v_finance/v_admin_ttk/admin_ttk', $data);
	}
	
	private function set_upload_options()
	{
		$config = array();
		$config['upload_path'] 		= 'ttk_upload/';
		$config['allowed_types'] 	= 'txt';
		$config['max_size']      	= '1000000';
		$config['overwrite']     	= FALSE;
	
		return $config;
	}

	function export_txt($isExcel)
	{
		$this->load->model('m_finance/m_admin_ttk/m_admin_ttk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$empID	= $this->session->userdata('Emp_ID');
		$data['isExcel'] = $isExcel;
		$data['selAllFile'] = $this->m_admin_ttk->getAllFile($empID);
		//$selLastFile = $this->m_admin_ttk->getLastFile()->row();
		//$data['FileUpName'] = $selLastFile->FileUpName;
		
		$data['viewAllData'] 	= $this->m_admin_ttk->viewAllData()->result();
		$this->load->view('v_finance/v_admin_ttk/admin_ttk_report', $data);
	}
}
?>