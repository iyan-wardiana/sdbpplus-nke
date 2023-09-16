<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2017
 * File Name	= C_jobopname.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_jobopname  extends CI_Controller
{
 	public function index() // OK
	{
		$MenuCode		= 'MN220';
		$url			= site_url('c_finance/c_jobopname/projectlist/?id='.$this->url_encryption_helper->encode_url($MenuCode));
		redirect($url);
	}
	
    function projectlist()  // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$MenuCode	= $_GET['id'];
		$MenuCode	= $this->url_encryption_helper->decode_url($MenuCode);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['subTitle'] 	= "job opname";
			$data['MenuCode'] 	= $MenuCode;
			$data['secURL'] 	= "c_finance/c_jobopname/jobopnameList/?id=";
			
			$data["CountProj"]	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["viewProj"] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('login');
		}
    }
	
	function jobopnameList() // OK
	{
		$this->load->model('m_finance/m_print_journal/m_jobopname', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['subTitle'] 	= "Job Opname";
			$data['PRJCODE'] 	= $PRJCODE;
			$data['secURL'] 	= "c_finance/c_jobopname/update/?id=";
			$data['addURL'] 	= site_url('c_finance/c_jobopname/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_jobopname/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['addOPN']		= site_url('c_finance/c_jobopname/addOpname1');
			$data['printOPN']	= site_url('c_finance/c_jobopname/printOpname1');
			
			$data['countOPN'] 	= $this->m_jobopname->count_all_OPN($PRJCODE);
			$data['viewOPN'] 	= $this->m_jobopname->get_all_OPN($PRJCODE)->result();
			
			$this->load->view('v_finance/v_jobopname/jobopname', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
 	function addOpname1($SPKCODE) // OK
	{
		$url		= site_url('c_finance/c_jobopname/addOpname/?id='.$this->url_encryption_helper->encode_url($SPKCODE));
		redirect($url);
	}
	
	function addOpname() // OK
	{
		$this->load->model('m_finance/m_print_journal/m_jobopname', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SPKCODE			= $_GET['id'];
			$SPKCODE			= $this->url_encryption_helper->decode_url($SPKCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= "Tambah Job Opname";
				$data['subTitle'] 	= "job opname";
			}
			else
			{
				$data['title'] 		= "Add Job Opname";
				$data['subTitle'] 	= "job opname";
			}
			$data['SPKCODE'] 	= $SPKCODE;
			$data['MenuCode'] 	= 'MN220';
			$MenuCode		= 'MN220';
			$data['addOPN_PROC']= site_url('c_finance/c_jobopname/addOpname_process');
			
			$this->load->view('v_finance/v_jobopname/jobopname_from', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function printOpname1($SPKCODE)
	{
		$url		= site_url('c_finance/c_jobopname/printOpname/?id='.$this->url_encryption_helper->encode_url($SPKCODE));
		redirect($url);
	}
	
	function printOpname()
	{
		$this->load->model('m_finance/m_print_journal/m_jobopname', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SPKCOD_STEP	= $_GET['id'];
			$SPKCOD_STEP	= $this->url_encryption_helper->decode_url($SPKCOD_STEP);
			$EmpID 			= $this->session->userdata('Emp_ID');
			$LangID 		= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= "Tambah Job Opname";
				$data['subTitle'] 	= "job opname";
			}
			else
			{
				$data['title'] 		= "Add Job Opname";
				$data['subTitle'] 	= "job opname";
			}
			$SPK_STRING = explode("~", $SPKCOD_STEP);
			$SPKCODE 	= $SPK_STRING[0];
			$OPN_STEP 	= $SPK_STRING[1];
			
			$data['SPKCODE'] 	= $SPKCODE;
			$data['OPN_STEP'] 	= $OPN_STEP;
			$data['MenuCode'] 	= 'MN220';
			
			$this->load->view('v_finance/v_jobopname/jobopname_print', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}