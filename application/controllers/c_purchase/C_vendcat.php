<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= C_vendcat.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_vendcat extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	}

 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_vendcat/get_last_ten_vendcat/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_last_ten_vendcat($offset=0) // OK
	{
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		
		{
			$idAppName					= $_GET['id'];
			$appName					= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 		= "Kategori Pemasok";
				$data['h3_title'] 		= 'pembelian';
			}
			else
			{
				$data["h2_title"] 		= "Vendor Category";
				$data['h3_title'] 		= 'purchase';
			}
			
			$data['secAddURL'] 			= site_url('c_purchase/c_vendcat/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 			= 'MN007';
			$num_rows 					= $this->m_vendcat->count_all_num_rows();
			$data['countvendcat'] 		= $num_rows;
	 
			$data['viewvendcat'] = $this->m_vendcat->get_last_ten_vendcat()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN007';
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
			
			$this->load->view('v_purchase/v_vendcat/vendor_category', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // OK
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
			
			$columns_valid 	= array("VendCat_Code", 
									"VendCat_Name", 
									"VendCat_Desc");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_vendcat->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_vendcat->get_AllDataL($search, $length, $start, $order, $dir);
			
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$VCatCode	= $dataI['VendCat_Code'];
				$VCatName	= $dataI['VendCat_Name'];
				$VCatDesc	= $dataI['VendCat_Desc'];
				
                $secUpd		= site_url('c_purchase/c_vendcat/update/?id='.$this->url_encryption_helper->encode_url($VCatCode));
				$secDel 	= base_url().'index.php/__l1y/trashVCat/?id=';
				$delID 		= "$secDel~tbl_vendcat~VendCat_Code~$VCatCode";

				$sqlSPL	 	= "tbl_supplier WHERE SPLCAT = '$VCatCode'";
				$resSPL 	= $this->db->count_all($sqlSPL);

				if($resSPL == 0)
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array($noU,
											"<div style='white-space:nowrap'>$VCatCode</div>",
										  	"<div style='white-space:nowrap'>".$VCatName."</div>",
										  	$VCatDesc,
										  	"<div style='white-space:nowrap'>".$secAction."</div>");

				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Kategori Pemasok";
				$data['h3_title'] 	= 'pembelian';
			}
			else
			{
				$data["h2_title"] 	= "Vendor Category";
				$data['h3_title'] 	= 'purchase';
			}
			
			$data['form_action']	= site_url('c_purchase/c_vendcat/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_purchase/c_vendcat/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_purchase/c_vendcat/');
			$data["MenuCode"] 		= 'MN007';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN007';
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
			
			$this->load->view('v_purchase/v_vendcat/vendor_category_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getVENDCATCODE($cinta) // OK
	{ 
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$recordcountVCAT 	= $this->m_vendcat->count_all_num_rowsVCAT($cinta);
		echo $recordcountVCAT;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$VendCat_Code	= $this->input->post('VendCat_Code');
			$Acc_DirParentA	= $this->input->post('Acc_DirParentA');
			$Acc_DirParentB	= $this->input->post('Acc_DirParentB');
			$Acc_DirParentC	= $this->input->post('Acc_DirParentC');
			$Acc_DirParentD	= $this->input->post('Acc_DirParentD');
			
			$sqlAccDEL1	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'PINV'";
			$this->db->query($sqlAccDEL1);
			
			$sqlAccDEL2	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'BP'";
			$this->db->query($sqlAccDEL2);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'PINV'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'PINV',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'K');
				$this->m_vendcat->add1($LinkAcc1);
				
				$LinkAcc2 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'BP',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'D');
				$this->m_vendcat->add2($LinkAcc2);
			}
			else
			{
				$LA_CATEG1	= 'PINV';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
				
				$LA_CATEG2	= 'PINV';
				$LinkAcc2 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_vendcat->upd2($LinkAcc2, $VendCat_Code, $LA_CATEG2);
			}
			
			$sqlAccDEL3	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'DP'";
			$this->db->query($sqlAccDEL3);
			
			$sqlAcc2 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'DP'";
			$resAcc2 	= $this->db->count_all($sqlAcc2);
			if($resAcc2 == 0)
			{
				$LinkAcc3 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'DP',
									'LA_ACCID'		=> $Acc_DirParentB,
									'LA_DK'			=> 'D');
				$this->m_vendcat->add1($LinkAcc3);
			}
			else
			{
				$LA_CATEG1	= 'DP';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentB);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
			}
			
			$sqlAccDEL1	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'RET'";
			$this->db->query($sqlAccDEL1);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'RET'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'RET',
									'LA_ACCID'		=> $Acc_DirParentC,
									'LA_DK'			=> 'K');
				$this->m_vendcat->add1($LinkAcc1);
			}
			else
			{
				$LA_CATEG1	= 'RET';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentC);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
			}
			
			$sqlOPNRET	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'OPN-RET'";
			$this->db->query($sqlOPNRET);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'OPN-RET'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'OPN-RET',
									'LA_ACCID'		=> $Acc_DirParentD,
									'LA_DK'			=> 'K');
				$this->m_vendcat->add1($LinkAcc1);
			}
			else
			{
				$LA_CATEG1	= 'OPN-RET';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentD);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
			}
			
			$vendcat = array('VendCat_Code' 	=> $this->input->post('VendCat_Code'),
							'VendCat_Name'		=> addslashes($this->input->post('VendCat_Name')),
							'VendCat_Desc'		=> addslashes($this->input->post('VendCat_Desc')),
							'VC_LA_PAYINV'		=> $Acc_DirParentA,
							'VC_LA_PAYDP'		=> $Acc_DirParentB,
							'VC_LA_RET'			=> $Acc_DirParentC,
							'VC_LA_OPNRET'		=> $Acc_DirParentD);

			$this->m_vendcat->add($vendcat);
						
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('VendCat_Code');
				$MenuCode 		= 'MN007';
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
			
			$url			= site_url('c_purchase/c_vendcat/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		$VendCat_Code	= $_GET['id'];
		$VendCat_Code	= $this->url_encryption_helper->decode_url($VendCat_Code);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Kategori Pemasok";
				$data['h3_title'] 	= 'pembelian';
			}
			else
			{
				$data["h2_title"] 	= "Vendor Category";
				$data['h3_title'] 	= 'purchase';
			}
			
			$data['form_action']	= site_url('c_purchase/c_vendcat/update_process');
			$data['link'] 			= array('link_back' => anchor('c_purchase/c_vendcat/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_purchase/c_vendcat/');
			$data["MenuCode"] 		= 'MN007';
			$getvendcat 			= $this->m_vendcat->get_vendcat_by_code($VendCat_Code)->row();
			
			$data['default']['VendCat_Code'] = $getvendcat->VendCat_Code;
			$data['default']['VendCat_Name'] = $getvendcat->VendCat_Name;		
			$data['default']['VendCat_Desc'] = $getvendcat->VendCat_Desc;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendcat->VendCat_Code;
				$MenuCode 		= 'MN007';
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
			
			$this->load->view('v_purchase/v_vendcat/vendor_category_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_vendcat/m_vendcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$VendCat_Code	= $this->input->post('VendCat_Code');
			$Acc_DirParentA	= $this->input->post('Acc_DirParentA');
			$Acc_DirParentB	= $this->input->post('Acc_DirParentB');
			$Acc_DirParentC	= $this->input->post('Acc_DirParentC');
			$Acc_DirParentD	= $this->input->post('Acc_DirParentD');
			
			$sqlAccDEL1	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'PINV'";
			$this->db->query($sqlAccDEL1);
			
			$sqlAccDEL2	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'BP'";
			$this->db->query($sqlAccDEL2);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'PINV'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'PINV',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'K');
				$this->m_vendcat->add1($LinkAcc1);
				
				$LinkAcc2 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'BP',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'D');
				$this->m_vendcat->add2($LinkAcc2);
			}
			else
			{
				$LA_CATEG1	= 'PINV';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
				
				$LA_CATEG2	= 'PINV';
				$LinkAcc2 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_vendcat->upd2($LinkAcc2, $VendCat_Code, $LA_CATEG2);
			}
			
			$sqlAccDEL3	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'DP'";
			$this->db->query($sqlAccDEL3);
			
			$sqlAcc2 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'DP'";
			$resAcc2 	= $this->db->count_all($sqlAcc2);
			if($resAcc2 == 0)
			{
				$LinkAcc3 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'DP',
									'LA_ACCID'		=> $Acc_DirParentB,
									'LA_DK'			=> 'D');
				$this->m_vendcat->add1($LinkAcc3);
			}
			else
			{
				$LA_CATEG1	= 'DP';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentB);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
			}
			
			$sqlAccDEL1	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'RET'";
			$this->db->query($sqlAccDEL1);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'RET'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'RET',
									'LA_ACCID'		=> $Acc_DirParentC,
									'LA_DK'			=> 'K');
				$this->m_vendcat->add1($LinkAcc1);
			}
			else
			{
				$LA_CATEG1	= 'RET';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentC);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
			}
			
			$sqlOPNRET	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'OPN-RET'";
			$this->db->query($sqlOPNRET);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'OPN-RET'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $VendCat_Code,
									'LA_CATEG'		=> 'OPN-RET',
									'LA_ACCID'		=> $Acc_DirParentD,
									'LA_DK'			=> 'K');
				$this->m_vendcat->add1($LinkAcc1);
			}
			else
			{
				$LA_CATEG1	= 'OPN-RET';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentD);
				$this->m_vendcat->upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1);
			}
			
			$vendcat = array('VendCat_Code' 	=> $this->input->post('VendCat_Code'),
							'VendCat_Name'		=> addslashes($this->input->post('VendCat_Name')),
							'VendCat_Desc'		=> addslashes($this->input->post('VendCat_Desc')),
							'VC_LA_PAYINV'		=> $Acc_DirParentA,
							'VC_LA_PAYDP'		=> $Acc_DirParentB,
							'VC_LA_RET'			=> $Acc_DirParentC,
							'VC_LA_OPNRET'		=> $Acc_DirParentD);
										
			$this->m_vendcat->update($VendCat_Code, $vendcat);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $VendCat_Code;
				$MenuCode 		= 'MN007';
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
			
			$url			= site_url('c_purchase/c_vendcat/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}