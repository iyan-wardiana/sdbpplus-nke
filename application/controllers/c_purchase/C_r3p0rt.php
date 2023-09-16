<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Mei 2017
 * File Name	= C_r3p0rt.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_r3p0rt extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}

			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}

    function r_supplierlist()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_purchase/c_r3p0rt/r_5uppl15t/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function r_5uppl15t() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_purchase/c_r3p0rt/r_supplierlist_view/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Daftar Supplier';
				$data['h2_title'] 	= 'Laporan';
			}
			else
			{
				$data['h1_title'] 	= 'Supplier List';
				$data['h2_title'] 	= 'Report';
			}
			
			$this->load->view('v_purchase/v_purchase_report/r_supplier/r_supplier', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_supplierlist_view()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['SuppStat'] 	= $this->input->post('SuppStat');
			$data['SortBy'] 	= $this->input->post('SortBy');
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$SortBy		= $data['SortBy'];
			
			$comp_name 	= $this->session->userdata['comp_name'];
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				if($SortBy == 'Mandor')
				{
					$data['h1_title'] 	= "Daftar Mandor Rekanan<br>$comp_name";
				}
				else
				{
					$data['h1_title'] 	= "Daftar Nama Suplier<br>$comp_name";
				}
			}
			else
			{
				if($SortBy == 'Mandor')
				{
					$data['h1_title'] 	= "Mandor List <br>$comp_name";
				}
				else
				{
					$data['h1_title'] 	= "List Supplier Name <br>$comp_name";
				}
			}
			
			//echo $SortBy;
			//return false;
			
			if($SortBy == 'Mandor')
			{
				$this->load->view('v_purchase/v_purchase_report/r_supplier/r_mandor_report', $data);
			}
			else
			{
				$this->load->view('v_purchase/v_purchase_report/r_supplier/r_supplier_report', $data);		
			}
			//$this->load->view('v_purchase/v_purchase_report/r_supplier/r_supplier_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_requisition()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_purchase/c_r3p0rt/r3pR3q/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function r3pR3q() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_purchase/c_r3p0rt/r_requisition_view/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Permintaan Pembelian';
				$data['h2_title'] 	= 'Laporan';
			}
			else
			{
				$data['h1_title'] 	= 'Purchase Request';
				$data['h2_title'] 	= 'Report';
			}
			
			$this->load->view('v_purchase/v_purchase_report/r_purchasereq/r_purchasereq', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_requisition_view()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 	= $appName;
			$LangID 		= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'LAPORAN PERMINTAAN PEMBELIAN';
			}
			else
			{
				$data['h1_title'] 	= 'PURCHASE REQUEST REPORT';
			}
			
			$datePeriod1	= $this->input->post('datePeriod');
			$datePeriod2	= explode(" - ", $datePeriod1);
			$Start_Date		= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date		= date('Y-m-d', strtotime($datePeriod2[1]));	
			
			//$StartDate 		= date('Y-m-d', strtotime('-1 days', strtotime($Start_Date)));
			//$EndDate 		= date('Y-m-d', strtotime('+1 days', strtotime($End_Date)));
			$StartDate 		= date('Y-m-d', strtotime($Start_Date));
			$EndDate 		= date('Y-m-d', strtotime($End_Date));
		
			/*$viewProj 			= $this->input->post('viewProj');	// All Project or Selected Project
			if($viewProj == 0)
			{
				$packageelements	= $_POST['packageelements'];
				$TOTPROJ			= count($packageelements);
				if (count($packageelements)>0)
				{
					$mySelected	= $_POST['packageelements'];
					$row		= 0;
					foreach ($mySelected as $projCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$PRJCODE1	= $projCode;
						}
						else
						{
							$PRJCODE1	= "$PRJCODE1','$projCode";
						}
					}
				}
				$data['TOTPROJ'] 	= $TOTPROJ;
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}*/
			$PRJCODE1 				= $this->input->post('PRJCODE');	
			$data['PRJCODECOL'] 	= "'$PRJCODE1'";;	
			$data['StartDate'] 		= $StartDate;
			$data['EndDate'] 		= $EndDate;
			$viewProj 				= 0;
			$data['viewProj'] 		= $viewProj;
			//$data['VMonth'] 		= $this->input->post('VMonth');		// Month
			$data['CFStat'] 		= $this->input->post('CFStat');		// Status
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_purchase/v_purchase_report/r_purchasereq/r_purchasereq_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function r_D4f7p0()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_purchase/c_r3p0rt/r_D4f7p0x/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function r_D4f7p0x() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_purchase/c_r3p0rt/r_vwD4f7p0x/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN025';
				$data["MenuApp"] 	= 'MN025';
				$data["MenuCode"] 	= 'MN025';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME
			
			$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_vwD4f7p0x()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'LAPORAN PEMBELIAN';
			}
			else
			{
				$data['h1_title'] 	= 'PURCHASE ORDER REPORT';
			}
			
			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$PRJCODE1				= $this->input->post('PRJCODE');
			$data['Start_Date'] 	= $this->input->post('Start_Date');	// Month
			$data['End_Date'] 		= $this->input->post('End_Date');	// Month/**/
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$data['RepStatus'] 		= $this->input->post('RepStatus');	// View or Excel
			
			/*if($CFType == 1)
				$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder_report_det', $data);
			if($CFType == 2)
				$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder_report', $data);*/
			
			$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_poR3p()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_r3p0rt/r_poR3pidx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_poR3pidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();

		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN514';
				$data["MenuApp"] 	= 'MN514';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_purchase/c_r3p0rt/r_poR3pidx_v13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_purchase/v_purchase_report/r_poreport/r_poreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_poR3pidx_v13w()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN514';
				$data["MenuApp"] 	= 'MN514';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$data['SPLCODE']	= $this->input->post('SPLCODE');
			$data['PO_NUM']		= $this->input->post('PO_NUM');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			$data['CFType'] 	= $this->input->post('CFType');		// Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			if($CFType == '1') // Summary
			{
				$this->load->view('v_purchase/v_purchase_report/r_poreport/r_poreport_sum', $data); // Summary
			}
			else
			{
				$this->load->view('v_purchase/v_purchase_report/r_poreport/r_poreport_det', $data); //Detail
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getPO()
	{
		$PRJCODE		= $this->input->post('PRJCODE');
		$SPLCODE		= $this->input->post('SPLCODE');
		$datePeriod1	= $this->input->post('datePeriod');
		$datePeriod2	= explode(" - ", $datePeriod1);
		$Start_Date		= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
		$End_Date		= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

		$addQuery 		= "";
		if($SPLCODE[0] != 1)
		{
			$SPLJoin = join("','", $SPLCODE);
			$addQuery = "AND SPLCODE IN ('$SPLJoin')";
		}

		$get_PO 		= "SELECT PO_NUM, PO_CODE 
							FROM tbl_po_header
							WHERE PRJCODE = '$PRJCODE' $addQuery 
							AND PO_DATE BETWEEN '$Start_Date' AND '$End_Date' AND PO_STAT IN (3,6)";
		$data 			= $this->db->query($get_PO)->result();
		echo json_encode($data);
	}
	
	function r_invorder()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_purchase/c_r3p0rt/r_invorderx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function r_invorderx() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_purchase/c_r3p0rt/r_invorder_view/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Faktur Pembelian';
				$data['h2_title'] 	= 'Laporan';
			}
			else
			{
				$data['h1_title'] 	= 'Purchase Invoice';
				$data['h2_title'] 	= 'Report';
			}
			
			$this->load->view('v_purchase/v_purchase_report/r_invorder/r_invorder', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_invorder_view()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'LAPORAN FAKTUR';
			}
			else
			{
				$data['h1_title'] 	= 'PURCHASE INVOICE REPORT';
			} 
			
			$datePeriod1	= $this->input->post('datePeriod');
			$datePeriod2	= explode(" - ", $datePeriod1);
			$Start_Date		= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date		= date('Y-m-d', strtotime($datePeriod2[1]));	
			
			//$StartDate 		= date('Y-m-d', strtotime('-1 days', strtotime($Start_Date)));
			//$EndDate 		= date('Y-m-d', strtotime('+1 days', strtotime($End_Date)));
			$StartDate 		= date('Y-m-d', strtotime($Start_Date));
			$EndDate 		= date('Y-m-d', strtotime($End_Date));
			
			$viewProj 			= $this->input->post('viewProj');	// All Project or Selected Project
			if($viewProj == 0)
			{
				$packageelements	= $_POST['packageelements'];
				$TOTPROJ			= count($packageelements);
				if (count($packageelements)>0)
				{
					$mySelected	= $_POST['packageelements'];
					$row		= 0;
					foreach ($mySelected as $projCode)
					{
						$row		= $row + 1;
						$PRJCODE1	= $projCode;
						if($row == 1)
						{
							$PRJCODE1	= $projCode;
						}
						else
						{
							$PRJCODE1	= "$PRJCODE1','$projCode";
						}
					}
				}
				$data['TOTPROJ'] 	= $TOTPROJ;
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			
			$data['StartDate'] 		= $StartDate;
			$data['EndDate'] 		= $EndDate;
			$data['viewProj'] 		= $viewProj;
			$data['VMonth'] 		= $this->input->post('VMonth');		// Month
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_purchase/v_purchase_report/r_invorder/r_invorder_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_monitoringspk()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_purchase/c_r3p0rt/r_monitoringspk_view/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Monitoring SPK/Opname';
				$data['h2_title'] 	= 'Laporan';
			}
			else
			{
				$data['h1_title'] 	= 'Monitoring SPK/Opname';
				$data['h2_title'] 	= 'Report';
			}

			$this->load->view('v_purchase/v_purchase_report/r_monitoringspk/r_monitoringspk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_monitoringspk_view()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Laporan Monitoring SPK/Opname';
			}
			else
			{
				$data['h1_title'] 	= 'Laporan Monitoring SPK/Opname';
			}
			
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');

			$this->load->view('v_purchase/v_purchase_report/r_monitoringspk/r_monitoringspk_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_prdochist()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_r3p0rt/r_prdochistidx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_prdochistidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();

		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN516';
				$data["MenuApp"] 	= 'MN516';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_purchase/c_r3p0rt/r_prdochistidx_v13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_purchase/v_purchase_report/r_prdochist/r_prdochist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_prdochistidx_v13w()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN516';
				$data["MenuApp"] 	= 'MN516';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			//$data['SPLCODE']	= $this->input->post('SPLCODE');
			$SPLCODE			= $this->input->post('SPLCODE');

			$addQuery 		= "";
			if($SPLCODE[0] != 1)
			{
				$SPLJoin 			= join("','", $SPLCODE);
				$data['addQuery'] 	= "AND B.SPLCODE IN ('$SPLJoin')";
			}
			else
			{
				$data['addQuery'] 	= "";
			}

			//$data['PO_NUM']	= $this->input->post('PO_NUM');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			$data['CFType'] 	= $this->input->post('CFType');		// Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			$this->load->view('v_purchase/v_purchase_report/r_prdochist/r_prdochist_sum', $data);
			/*if($CFType == '1') // Summary
			{
				$this->load->view('v_purchase/v_purchase_report/r_poreport/r_poreport_sum', $data); // Summary
			}
			else
			{
				$this->load->view('v_purchase/v_purchase_report/r_poreport/r_poreport_det', $data); //Detail
			}*/
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_trxjobreport()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_r3p0rt/r_trxjobreportidx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_trxjobreportidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();

		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN528';
				$data["MenuApp"] 	= 'MN528';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_purchase/c_r3p0rt/r_trxjobreportidx_v13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_purchase/v_purchase_report/r_trxjobreport/r_trxjobreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_trxjobreportidx_v13w()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN TRANSAKSI PER PEKERJAAN';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'TRANSACTION JOB REPORT';
				$data['h2_title']	= 'Report';
			}

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN516';
				$data["MenuApp"] 	= 'MN516';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$PRJNAME			= '';
			$getproject 		= "SELECT A.PRJCODE, A.PRJNAME, A.PRJBOQ FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 			= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
				$data['PRJBOQ'] 	= $rowPRJ->PRJBOQ;
			endforeach;

			
			$data['JOBPARENT'] 	= $this->input->post('JOBPARENT');

			$data['CFType'] 	= $this->input->post('CFType');		// Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			$this->load->view('v_purchase/v_purchase_report/r_trxjobreport/r_trxjobreport_sum', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_trxsplR3p()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_r3p0rt/r_trxsplR3pidx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_trxsplR3pidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();

		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN093';
				$data["MenuApp"] 	= 'MN093';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_purchase/c_r3p0rt/r_trxsplR3p_v13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_purchase/v_purchase_report/r_trxperspl/r_trxperspl', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_trxsplR3p_v13w()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN093';
				$data["MenuApp"] 	= 'MN093';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$data['SPLCODE']	= $this->input->post('SPLCODE');
			$data['REPCAT']		= $this->input->post('REPCAT');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			$data['CFType'] 	= $this->input->post('CFType');		// Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			/*if($CFType == '1') // Summary
			{*/
				$this->load->view('v_purchase/v_purchase_report/r_trxperspl/r_trxperspl_sum', $data); // Summary
			/*}
			else
			{
				$this->load->view('v_purchase/v_purchase_report/r_trxperspl/r_trxperspl_det', $data); //Detail
			}*/
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_opintR3p()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_r3p0rt/r_opintR3pidx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_opintR3pidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();

		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN562';
				$data["MenuApp"] 	= 'MN562';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_purchase/c_r3p0rt/r_opintR3pidx_v13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_purchase/v_purchase_report/r_opintreport/r_opintreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_opintR3pidx_v13w()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN562';
				$data["MenuApp"] 	= 'MN562';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$data['SPLCODE']	= $this->input->post('SPLCODE');
			$data['WO_NUM']		= $this->input->post('WO_NUM');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

			$data['CFType'] 	= $this->input->post('CFType');		// Summary or Detail
			$CFType				= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel

			if($CFType == '1') // Summary
			{
				$this->load->view('v_purchase/v_purchase_report/r_opnreport/r_opintreport_sum', $data); // Summary
			}
			else
			{
				$this->load->view('v_purchase/v_purchase_report/r_opintreport/r_opintreport_det', $data); //Detail
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getWO()
	{
		$PRJCODE		= $this->input->post('PRJCODE');
		$SPLCODE		= $this->input->post('SPLCODE');
		$datePeriod1	= $this->input->post('datePeriod');
		$datePeriod2	= explode(" - ", $datePeriod1);
		$Start_Date		= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
		$End_Date		= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));

		$addQry1 		= "";
		if($PRJCODE != 0)
			$addQry1 = "AND PRJCODE = '$PRJCODE'";

		$addQry2 		= "";
		if($SPLCODE[0] != 1)
		{
			$SPLJoin = join("','", $SPLCODE);
			$addQry2 = "AND SPLCODE IN ('$SPLJoin')";
		}

		$get_WO 		= "SELECT WO_NUM, WO_CODE 
							FROM tbl_wo_header
							WHERE (WO_DATE BETWEEN '$Start_Date' AND '$End_Date') AND WO_CATEG = 'T' AND WO_STAT NOT IN (5,9) $addQry1 $addQry2";
		$data 			= $this->db->query($get_WO)->result();
		echo json_encode($data);
	}
}