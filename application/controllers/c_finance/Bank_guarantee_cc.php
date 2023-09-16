<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Agustus 2014
 * File Name	= bank_guarantee_cc.php
 * Location		= 
 * Notes		= 
*/
?> 
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bank_guarantee_cc  extends CI_Controller  
{
 	function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/bank_guarantee_cc/get_last_ten_bguarantee/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function get_last_ten_bguarantee($offset=0) // OK
	{
		$this->load->model('m_finance/m_bank_guarantee_cc/m_bank_guarantee_cc', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$empID 					= $this->session->userdata('Emp_ID');
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Bank Guarantee';
			$data['h3_title'] 		= 'finance';
			$num_rows 				= $this->m_bank_guarantee_cc->count_all_num_rows();
        	$data["recordcount"] 	= $num_rows;
 
			$data['viewbankguar'] 	= $this->m_bank_guarantee_cc->get_last_ten_bguarantee($empID)->result();
			
			$this->load->view('v_finance/v_bank_guarantee_cc/bank_guarantee_cc', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}