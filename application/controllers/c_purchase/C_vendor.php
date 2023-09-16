<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 November 2017
 * File Name	= C_vendor.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_vendor extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}
	}
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_vendor/gL5upL/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function gL5upL() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName				= $_GET['id'];
			$appName				= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Vendor';
			$data['h3_title'] 		= 'purchase';
			$data['secAddURL'] 		= site_url('c_purchase/c_vendor/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN008';
			$num_rows 				= $this->m_vendor->count_all_supplier();
			$data['countSUPL'] 		= $num_rows;	 
			$data['vwSUPL'] 		= $this->m_vendor->get_all_supplier()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN008';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_vendor/v_vendor', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

  	function get_AllData() // GOOD
	{
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("",
									"SPLCODE",
									"SPLDESC", 
									"SPLADD1", 
									"SPLKOTA", 
									"SPLTELP");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_vendor->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_vendor->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{										
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= $dataI['SPLDESC'];
				$SPLADD1	= $dataI['SPLADD1'];
				$SPLKOTA	= $dataI['SPLKOTA'];
				$SPLTELP	= $dataI['SPLTELP'];
				
				$secUpd		= site_url('c_purchase/c_vendor/update/?id='.$this->url_encryption_helper->encode_url($SPLCODE));
				
				$secAction	= "<label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   </label>";
							   
				$output['data'][] = array("$noU.",
										  $dataI['SPLCODE'],
										  $dataI['SPLDESC'],
										  $dataI['SPLADD1'],
										  "<label style='white-space:nowrap'> s".$dataI['SPLKOTA']."</label>",
										  $dataI['SPLTELP'],
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_vendor/add_process');
			$data['backURL'] 		= site_url('c_purchase/c_vendor/');
			
			$MenuCode 				= 'MN008';
			$data['MenuCode'] 		= 'MN008';
			$data['viewDocPattern'] = $this->m_vendor->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN008';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_vendor/v_vendor_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getVENDCATCODE($cinta) // OK
	{ 
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		$recordcountVCAT 	= $this->m_vendor->count_all_num_rowsVCAT($cinta);
		echo $recordcountVCAT;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$vendcat = array('SPLCODE' 		=> $this->input->post('SPLCODE'),
							'SPLDESC'		=> $this->input->post('SPLDESC'),
							'SPLCAT'		=> $this->input->post('SPLCAT'),
							'SPLADD1'		=> $this->input->post('SPLADD1'),
							'SPLKOTA'		=> $this->input->post('SPLKOTA'),
							'SPLNPWP'		=> $this->input->post('SPLNPWP'),
							'SPLPERS'		=> $this->input->post('SPLPERS'),
							'SPLTELP'		=> $this->input->post('SPLTELP'),
							'SPLMAIL'		=> $this->input->post('SPLMAIL'),
							'SPLNOREK'		=> $this->input->post('SPLNOREK'),
							'SPLNMREK'		=> $this->input->post('SPLNMREK'),
							'SPLBANK'		=> $this->input->post('SPLBANK'),
							'SPLOTHR'		=> $this->input->post('SPLOTHR'),
							'SPLSTAT'		=> $this->input->post('SPLSTAT'));
			$this->m_vendor->add($vendcat);
						
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('SPLCODE');
				$MenuCode 		= 'MN008';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_purchase/c_vendor/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		$SPLCODE	= $_GET['id'];
		$SPLCODE	= $this->url_encryption_helper->decode_url($SPLCODE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_purchase/c_vendor/update_process');
			$data['backURL'] 		= site_url('c_purchase/c_vendor/');
			
			$MenuCode 				= 'MN008';
			$data['MenuCode'] 		= 'MN008';
			$getvendor 				= $this->m_vendor->get_vendor_by_code($SPLCODE)->row();
			
			$data['default']['SPLCODE']	= $getvendor->SPLCODE;
			$data['default']['SPLDESC']	= $getvendor->SPLDESC;
			$data['default']['SPLCAT']	= $getvendor->SPLCAT;
			$data['default']['SPLADD1']	= $getvendor->SPLADD1;
			$data['default']['SPLKOTA']	= $getvendor->SPLKOTA;
			$data['default']['SPLNPWP']	= $getvendor->SPLNPWP;
			$data['default']['SPLPERS']	= $getvendor->SPLPERS;
			$data['default']['SPLTELP']	= $getvendor->SPLTELP;
			$data['default']['SPLMAIL']	= $getvendor->SPLMAIL;
			$data['default']['SPLNOREK']= $getvendor->SPLNOREK;
			$data['default']['SPLNMREK']= $getvendor->SPLNMREK;
			$data['default']['SPLBANK']	= $getvendor->SPLBANK;
			$data['default']['SPLOTHR']	= $getvendor->SPLOTHR;
			$data['default']['SPLSTAT']	= $getvendor->SPLSTAT;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendor->SPLCODE;
				$MenuCode 		= 'MN008';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_vendor/v_vendor_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SPLCODE	= $this->input->post('SPLCODE');
			
			$vendor 	= array('SPLDESC'		=> $this->input->post('SPLDESC'),
								'SPLCAT'		=> $this->input->post('SPLCAT'),
								'SPLADD1'		=> $this->input->post('SPLADD1'),
								'SPLKOTA'		=> $this->input->post('SPLKOTA'),
								'SPLNPWP'		=> $this->input->post('SPLNPWP'),
								'SPLPERS'		=> $this->input->post('SPLPERS'),
								'SPLTELP'		=> $this->input->post('SPLTELP'),
								'SPLMAIL'		=> $this->input->post('SPLMAIL'),
								'SPLNOREK'		=> $this->input->post('SPLNOREK'),
								'SPLNMREK'		=> $this->input->post('SPLNMREK'),
								'SPLBANK'		=> $this->input->post('SPLBANK'),
								'SPLOTHR'		=> $this->input->post('SPLOTHR'),
								'SPLSTAT'		=> $this->input->post('SPLSTAT'));
			$this->m_vendor->update($SPLCODE, $vendor);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $SPLCODE;
				$MenuCode 		= 'MN008';
				$TTR_CATEG		= 'UP';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_purchase/c_vendor/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}