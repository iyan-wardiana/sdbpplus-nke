<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Juli 2017
 * File Name	= C_pinv.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_pinv  extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_purchase/m_pinv/m_pinv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_purchase/c_pinv/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject() // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project';
			$data['h3_title']			= 'Purchase Invoice';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			
			$this->load->view('v_purchase/v_pinv/v_pinv_listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_all_pinv() // OK
	{
		$this->load->model('m_purchase/m_pinv/m_pinv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 		= $this->session->userdata('Emp_ID');
					
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Purchase Invoice';
			$data['h3_title']	= 'Purchase';
			$data['PRJCODE']	= $PRJCODE;
			$data["MenuCode"] 		= 'MN009';
			
			$num_rows 			= $this->m_pinv->count_all_pinv($PRJCODE);
			$data["reccount"]	= $num_rows;	 
			$data['viewpinv']	= $this->m_pinv->get_all_pinv($PRJCODE)->result();
			
			$this->load->view('v_purchase/v_pinv/v_pinv_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_purchase/m_pinv/m_pinv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Invoice';
			$data['h3_title']		= 'Purchase';
			$data['main_view'] 		= 'v_purchase/v_purchase_invoice/purchase_invoice_sd_form';
			$data['form_action']	= site_url('c_purchase/purchase_invoice_sd/add_process');
			$data['link'] 			= array('link_back' => anchor('c_purchase/c_pinv/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['PRJCODE'] 		= $PRJCODE;
			$data['countVend'] 		= $this->m_pinv->count_all_vend($PRJCODE);
			$data['viewvendor'] 	= $this->m_pinv->view_all_vend($PRJCODE)->result();
			
			$MenuCode 					= 'MN009';
			$data["MenuCode"] 			= 'MN009';
			$data['viewDocPattern'] 	= $this->m_pinv->getDataDocPat($MenuCode)->result();
	
			$this->load->view('v_purchase/v_pinv/v_pinv_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // USE
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$Currency_ID			= 'IDR';
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$DefProj_Code			= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			$selSearchType 			= $this->session->userdata['dtSessSrc1']['selSearchType'];
			$txtSearch 				= $this->session->userdata['dtSessSrc1']['txtSearch'];
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $selSearchType,
					'txtSearch' => $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			//setting INV Date		
			$Patt_Year 		= date('Y');
			$INV_DATE		= date('Y-m-d',strtotime($this->input->post('INV_DATE')));
			$INV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('INV_DUEDATE')));
			
			$INVDateY 		= date('Y');
			$INVDateM 		= date('m');
			$INVDateD 		= date('d');
			$CreateDate 	= date('Y-m-d H:i:s');
			
			// Check setting WH_ID
			$sqlGS		= "SELECT whIsPrjCode FROM tglobalsetting WHERE ID = 1";
			$ressqlGS 	= $this->db->query($sqlGS)->result();
			
			foreach($ressqlGS as $rowGS) :
				$whIsPrjCode 	= $rowGS->whIsPrjCode;
			endforeach;
			
			if($whIsPrjCode == 1)
			{
				$wh_id = $this->input->post('PRJCODE');
			}
			else
			{
				// HARUS ADA SETTINGAN SUSULAN
				$wh_id = $this->input->post('PRJCODE');
			}
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
					
			$INV_TERM = ($e - $s)/ (24 * 3600);
			
			$InvStatus 	= $this->input->post('INV_STATUS');
			$INVNumber 	= $this->input->post('INV_NUMBER');
			$PONumber	= $this->input->post('PONumberRef');
			$RRNumber	= $this->input->post('RR_NumberRef');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$Curr_ID 	= $this->input->post('Currency_ID');
			
			$insertINV = array('TrxNo'					=> $this->input->post('INV_NUMBER'),
								'INV_NUMBER' 			=> $this->input->post('INV_NUMBER'),
								'PO_Number'				=> $this->input->post('PONumberRef'),
								'RR_Number'				=> $this->input->post('RR_NumberRef'),
								'PRJCODE'				=> $this->input->post('PRJCODE'),
								'INV_DATE'				=> $INV_DATE,
								'INV_DUEDATE'			=> $INV_DUEDATE,
								'SPLCODE'				=> $this->input->post('selSPLCODE'),
								'INV_CURRENCY'			=> 'IDR',
								'INV_TAXCURR'			=> 'IDR',
								'INV_AMOUNT'			=> $this->input->post('totalAmountAfDisc'),
								'INV_AMOUNT_BASE'		=> $this->input->post('totalAmountAfDisc'),
								'INV_TERM'				=> $INV_TERM,
								'INV_STATUS'			=> $this->input->post('INV_STATUS'),
								'INV_NOTES'				=> $this->input->post('INV_NOTES'),								
								'INV_LISTTAXVAL'		=> $this->input->post('TaxPPn_Amount'),
								'CREATED_DATE'			=> $CreateDate,
								'CREATED_BY'			=> $DefEmp_ID,
								'wh_id'					=> $wh_id,
								'Patt_Number'			=> $this->input->post('Patt_Number'),
								'Patt_Year'				=> $this->input->post('Patt_Year'));
							
			$this->M_purchase_invoice_sd->add($insertINV);			
			
			// Pembuatan Journal Header
			if($InvStatus == 2 || $InvStatus == 3)
			{
				$parameters = array(
					'INV_NUMBER'		=> $INVNumber,
					'RRSource' 			=> $RRNumber, // IR Number
					'Reference_Number' 	=> $PONumber, // PO Number
					'proj_Code' 		=> $PRJCODE,
					'Transaction_Type' 	=> 'INV',
					'GEJDate' 			=> $CreateDate,
					'wh_id' 			=> $wh_id
				);
				$this->M_purchase_invoice_sd->createJournalH($INVNumber, $parameters);
			}
				
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('sd_tinv_detail',$d);
				
				// Pembuatan Journal Detail antara Invoice dengan lawannya
				if($InvStatus == 2 || $InvStatus == 3)
				{
					$INV_NUMBER 	= $INVNumber;
					$Item_code 		= $d['Item_code'];
					$Qty_RR 		= $d['Qty_RR'];
					$Qty_RR2 		= $d['Qty_RR2'];
					$UnitPrice 		= $d['UnitPrice'];
					$BUnitPrice 	= $d['Base_UnitPrice'];
					$Tax_Code1 		= $d['Tax_Code1'];
					$Tax_Code2 		= $d['Tax_Code2'];
					
					//echo "aga $BUnitPrice<br>";
					
					$parameters = array(
						'INV_NUMBER' 		=> $INV_NUMBER,
						'Item_code' 		=> $Item_code,
						'proj_Code'			=> $PRJCODE,
						'Qty_RR' 			=> $Qty_RR,
						'Qty_RR2' 			=> $Qty_RR2,
						'INV_DATE' 			=> $INV_DATE, // Invoice Date
						'Transaction_Type' 	=> 'INV',
						'Transaction_Value' => $Qty_RR,
						'Currency_ID' 		=> 'IDR',
						'Tax_Code1' 		=> $Tax_Code1,
						'Tax_Code2' 		=> $Tax_Code2,
						'WH_ID' 			=> $wh_id,
						'RRSource' 			=> $INVNumber,
						'UnitPrice' 		=> $UnitPrice,
						'BUnitPrice' 		=> $BUnitPrice
					);
					$this->M_purchase_invoice_sd->addJourDet($parameters);
				}
			}
			
			// Update status PO dan RR bahwa sudah dibuatkan invoice
			if($InvStatus == 2 || $InvStatus == 3)
			{
				$parameters = array(
					'InvStatus' 	=> $InvStatus,
					'PONumber' 		=> $PONumber,
					'RRNumber' 		=> $RRNumber,
					'proj_Code' 	=> $PRJCODE
				);
				$this->M_purchase_invoice_sd->updatePO_RR($INVNumber, $parameters);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$this->session->set_flashdata('message', 'Data succesfull to insert.!');
			redirect('c_purchase/purchase_invoice_sd/get_last_ten_purinv_src');
		}
		else
		{
			redirect('login');
		}
	}	
	
	function update($INV_NUMBER) // USE
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    				= $this->session->userdata['SessAppTitle']['app_title_name'];
			
			$data['title'] 				= $MyAppName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Purchase Invoice | Edit Purchase Invoice';			
			$data['main_view'] 			= 'v_purchase/v_purchase_invoice/purchase_invoice_sd_form';
			$data['form_action']		= site_url('c_purchase/purchase_invoice_sd/update_process');
			$data['link'] 				= array('link_back' => anchor('c_purchase/purchase_invoice_sd/','<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$DefProj_Code    			= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			$data["MenuCode"] 			= 'MN009';
			$data['recordcountVend'] 	= $this->M_purchase_invoice_sd->viewvendorCOEdit($DefProj_Code);			
			$data['viewvendor'] 		= $this->M_purchase_invoice_sd->viewvendorEdit($DefProj_Code)->result();			
			$getINVNo 					= $this->M_purchase_invoice_sd->get_INV_by_number($INV_NUMBER)->row();
							
			$data['default']['INV_NUMBER'] = $getINVNo->INV_NUMBER;			
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process() // USE
	{
		if ($this->session->userdata('login') == TRUE)
		{		
			$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$Currency_ID			= 'IDR';
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$DefProj_Code			= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			$selSearchType 			= $this->session->userdata['dtSessSrc1']['selSearchType'];
			$txtSearch 				= $this->session->userdata['dtSessSrc1']['txtSearch'];
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $selSearchType,
					'txtSearch' => $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			//setting INV Date		
			$Patt_Year 		= date('Y');
			$INV_DATE		= date('Y-m-d',strtotime($this->input->post('INV_DATE')));
			$INV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('INV_DUEDATE')));
			
			$INVDateY 		= date('Y');
			$INVDateM 		= date('m');
			$INVDateD 		= date('d');
			$CreateDate 	= date('Y-m-d H:i:s');
			
			// Check setting WH_ID
			$sqlGS		= "SELECT whIsPrjCode FROM tglobalsetting WHERE ID = 1";
			$ressqlGS 	= $this->db->query($sqlGS)->result();
			
			foreach($ressqlGS as $rowGS) :
				$whIsPrjCode 	= $rowGS->whIsPrjCode;
			endforeach;
			
			if($whIsPrjCode == 1)
			{
				$wh_id = $this->input->post('PRJCODE');
			}
			else
			{
				// HARUS ADA SETTINGAN SUSULAN
				$wh_id = $this->input->post('PRJCODE');
			}
			
			$s = strtotime($INV_DATE);
			$e = strtotime($INV_DUEDATE);
					
			$INV_TERM = ($e - $s)/ (24 * 3600);
			
			$InvStatus 	= $this->input->post('INV_STATUS');
			$INVNumber 	= $this->input->post('INV_NUMBER');
			$PONumber	= $this->input->post('PONumberRef');
			$RRNumber	= $this->input->post('RR_NumberRef');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$Curr_ID 	= $this->input->post('Currency_ID');
			
			$updateINVH = array('TrxNo'					=> $this->input->post('INV_NUMBER'),
								'INV_NUMBER' 			=> $this->input->post('INV_NUMBER'),
								'PO_Number'				=> $this->input->post('PONumberRef'),
								'RR_Number'				=> $this->input->post('RR_NumberRef'),
								'PRJCODE'				=> $this->input->post('PRJCODE'),
								'INV_DATE'				=> $INV_DATE,
								'INV_DUEDATE'			=> $INV_DUEDATE,
								'SPLCODE'				=> $this->input->post('selSPLCODE'),
								'INV_CURRENCY'			=> 'IDR',
								'INV_TAXCURR'			=> 'IDR',
								'INV_AMOUNT'			=> $this->input->post('totalAmountAfDisc'),
								'INV_AMOUNT_BASE'		=> $this->input->post('totalAmountAfDisc'),
								'INV_TERM'				=> $INV_TERM,
								'INV_STATUS'			=> $this->input->post('INV_STATUS'),
								'INV_NOTES'				=> $this->input->post('INV_NOTES'),								
								'INV_LISTTAXVAL'		=> $this->input->post('TaxPPn_Amount'),
								'CREATED_DATE'			=> $CreateDate,
								'CREATED_BY'			=> $DefEmp_ID,
								'wh_id'					=> $wh_id,
								'Patt_Number'			=> $this->input->post('Patt_Number'),
								'Patt_Year'				=> $this->input->post('Patt_Year'));
							
			$this->M_purchase_invoice_sd->updateINV($INVNumber, $updateINVH);
			
			$this->M_purchase_invoice_sd->deleteINVDet($INVNumber);
					
			// Pembuatan Journal Header
			if($InvStatus == 2 || $InvStatus == 3)
			{
				$parameters = array(
					'INV_NUMBER'		=> $INVNumber,
					'RRSource' 			=> $RRNumber, // IR Number
					'Reference_Number' 	=> $PONumber, // PO Number
					'proj_Code' 		=> $PRJCODE,
					'Transaction_Type' 	=> 'INV',
					'GEJDate' 			=> $CreateDate,
					'wh_id'				=> $wh_id
				);				
				$this->M_purchase_invoice_sd->createJournalH($INVNumber, $parameters);
			}
				
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('sd_tinv_detail',$d);
				
				// Pembuatan Journal Detail antara Invoice dengan lawannya
				if($InvStatus == 2 || $InvStatus == 3)
				{
					$INV_NUMBER 	= $INVNumber;
					$Item_code 		= $d['Item_code'];
					$Qty_RR 		= $d['Qty_RR'];
					$Qty_RR2 		= $d['Qty_RR2'];
					$UnitPrice 		= $d['UnitPrice'];
					$BUnitPrice 	= $d['Base_UnitPrice'];
					$Tax_Code1 		= $d['Tax_Code1'];
					$Tax_Code2 		= $d['Tax_Code2'];
					
					//echo "aga $BUnitPrice<br>";
					
					$parameters = array(
						'INV_NUMBER' 		=> $INV_NUMBER,
						'Item_code' 		=> $Item_code,
						'proj_Code'			=> $PRJCODE,
						'Qty_RR' 			=> $Qty_RR,
						'Qty_RR2' 			=> $Qty_RR2,
						'INV_DATE' 			=> $INV_DATE, // Invoice Date
						'Transaction_Type' 	=> 'INV',
						'Transaction_Value' => $Qty_RR,
						'Currency_ID' 		=> 'IDR',
						'Tax_Code1' 		=> $Tax_Code1,
						'Tax_Code2' 		=> $Tax_Code2,
						'WH_ID' 			=> $wh_id,
						'RRSource' 			=> $INVNumber,
						'UnitPrice' 		=> $UnitPrice,
						'BUnitPrice' 		=> $BUnitPrice
					);
					$this->M_purchase_invoice_sd->addJourDet($parameters);
				}
			}
			
			// Update status PO dan RR bahwa sudah dibuatkan invoice
			if($InvStatus == 2 || $InvStatus == 3)
			{
				$parameters = array(
					'InvStatus' 	=> $InvStatus,
					'PONumber' 		=> $PONumber,
					'RRNumber' 		=> $RRNumber,
					'proj_Code' 	=> $PRJCODE
				);
				$this->M_purchase_invoice_sd->updatePO_RR($INVNumber, $parameters);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$this->session->set_flashdata('message', 'Data succesfull to insert.!');
			redirect('c_purchase/purchase_invoice_sd/get_last_ten_purinv_src');
		}
		else
		{
			redirect('login');
		}
	}
		
	function popupallLPM($VendCode)
	{
		$MyAppName    		= $this->session->userdata['SessAppTitle']['app_title_name'];
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		$DefProj_Code    	= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
		$selSearchType1     = $this->session->userdata['dtSessSrc1']['selSearchType'];
		$txtSearch1        	= $this->session->userdata['dtSessSrc1']['txtSearch'];	
		$data['title'] 			= $MyAppName;
		$data['h2_title'] 		= 'Select Receipt / LPM';
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'IR';
		$data['SPLCODE']		= $VendCode;
		$data['PRJCODE']		= $DefProj_Code;
		
		$data['recordcountAllIR'] 	= $this->M_purchase_invoice_sd->count_all_num_rowsAllIR($VendCode, $DefProj_Code);
		$data['viewAllIR'] 			= $this->M_purchase_invoice_sd->viewAllIR($VendCode, $DefProj_Code)->result();
				
		$this->load->view('v_purchase/v_purchase_invoice/purchase_selectIR', $data);
	}
}