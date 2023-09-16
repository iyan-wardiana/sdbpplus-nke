<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Mei 2017
 * File Name	= C_purchase_report.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_purchase_report extends CI_Controller  
{
    function r_supplierlist()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_purchase/c_purchase_report/r_5uppl15t/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data['form_action']= site_url('c_purchase/c_purchase_report/r_supplierlist_view/?id='.$this->url_encryption_helper->encode_url($appName));
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
			
			$url			= site_url('c_purchase/c_purchase_report/r3pR3q/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data['form_action']= site_url('c_purchase/c_purchase_report/r_requisition_view/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data['PRJCODECOL'] 	= "$PRJCODE1";;	
			$data['StartDate'] 		= $StartDate;
			$data['EndDate'] 		= $EndDate;
			$data['PR_NUM'] 		= $this->input->post('PR_NUM');
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

	function getData_PR()
	{
		$POST 			= $this->input->post();
		$PRJCODE 		= $POST['PRJCODE'];
		$period 		= $POST['period'];
		$datePeriod2	= explode(" - ", $period);
		$Start_Date		= date('Y-m-d', strtotime($datePeriod2[0]));
		$End_Date		= date('Y-m-d', strtotime($datePeriod2[1]));
		$s_01 			= "SELECT PR_NUM, PR_CODE FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE' AND PR_DATE BETWEEN '$Start_Date' AND '$End_Date'";
		$r_01 			= $this->db->query($s_01);
		$data 			= $r_01->result();
		echo json_encode($data);
	}
	
    function r_purchaseorder()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_purchase/c_purchase_report/r_D4f7p0/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function r_D4f7p0() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_purchase/c_purchase_report/r_purchaseorder_view/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'Pesanan Pembelian';
				$data['h2_title'] 	= 'Laporan';
			}
			else
			{
				$data['h1_title'] 	= 'Purchase Order';
				$data['h2_title'] 	= 'Report';
			}
			
			$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_purchaseorder_view()
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
			
		
			$viewProj 			= $this->input->post('viewProj');	// All Project or Selected Project
			/*if($viewProj == 0)
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
			
			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$PRJCODE1				= $this->input->post('PRJCODE');
			$data['viewProj'] 		= $viewProj;
			$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			$data['Start_Date'] 	= $this->input->post('Start_Date');	// Month
			$data['End_Date'] 		= $this->input->post('End_Date');	// Month
			$CFType					= $this->input->post('CFType');		// Summary or Detail
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$data['RepStatus'] 		= $this->input->post('RepStatus');	// View or Excel
			
			if($CFType == 1)
				$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder_report_det', $data);
			if($CFType == 2)
				$this->load->view('v_purchase/v_purchase_report/r_purchaseorder/r_purchaseorder_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
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
			
			$url			= site_url('c_purchase/c_purchase_report/r_invorderx/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data['form_action']= site_url('c_purchase/c_purchase_report/r_invorder_view/?id='.$this->url_encryption_helper->encode_url($appName));
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
}