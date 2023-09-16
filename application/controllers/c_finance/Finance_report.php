<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2017
 * File Name	= Finance_report.php
 * Location		= -
*/

class Finance_report extends CI_Controller  
{	
    function index()
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			//$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_gl/c_report/'),'get_form_report_PL'); // Pertama dipanggil
			//redirect($secIndex);
		}
		else
		{
			redirect('Auth');
		}
    }

	function r_ttkoutstanding() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'TTK Outstanding';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_ttkoutstanding_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_ttkoutstanding/r_ttkoutstanding', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_ttkoutstanding_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'TTK Outstanding Report';
			$data['h3_title'] 	= 'Finance Report';
		
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
				$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_ttkoutstanding/r_ttkoutstanding_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_ttkoutstanding/r_ttkoutstanding_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

	function r_1h0g0bop4ym3nt() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/finance_report/r_1h0g0bop4ym3nt1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_1h0g0bop4ym3nt1() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$appName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($appName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Outstanding Voucher';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_outvoucpayment_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_outvoucpayment_view() // OK
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
				$data['h1_title'] 	= 'Laporan Faktur Tertunda';
			}
			else
			{
				$data['h1_title'] 	= 'Outstanding Payment Report';
			}
		
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
		
			$viewSupp 			= $this->input->post('viewSupl');	// All Supplier or Selected Supplier			
			if($viewSupp == 0)
			{
				$packageelementsSpl	= $_POST['packageelementsSpl'];
				$TOTSPL				= count($packageelementsSpl);
				if (count($packageelementsSpl)>0)
				{
					$mySelSupp	= $_POST['packageelementsSpl'];
					$row		= 0;
					foreach ($mySelSupp as $suppCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$SPLCODE1	= $suppCode;
						}
						else
						{
							$SPLCODE1	= "$SPLCODE1','$suppCode";
						}
					}
				}
				$data['TOTSPL'] 	= $TOTSPL;
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			else
			{
				$data['TOTSPL'] 	= 0;
				$SPLCODE1			= "";
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewSupl'] 		= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');		// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

	function r_dpreport() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'DP Balanced';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_dpreport_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_dpreport/r_dpreport', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_dpreport_view() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'DP Balanced Report';
			$data['h3_title'] 	= 'report';
		
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
		
			$viewSupp 			= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			//return false;
			
			if($viewSupp == 0)
			{
				$packageelementsSpl	= $_POST['packageelementsSpl'];
				$TOTSPL				= count($packageelementsSpl);
				if (count($packageelementsSpl)>0)
				{
					$mySelSupp	= $_POST['packageelementsSpl'];
					$row		= 0;
					foreach ($mySelSupp as $suppCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$SPLCODE1	= $suppCode;
						}
						else
						{
							$SPLCODE1	= "$SPLCODE1','$suppCode";
						}
					}
				}
				$data['TOTSPL'] 	= $TOTSPL;
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			else
			{
				$data['TOTSPL'] 	= 0;
				$SPLCODE1			= "";
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			//echo "test $SPLCODE1";
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewSupl'] 		= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_dpreport/r_dpreport_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_dpreport/r_dpreport_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}

	function r_paymentplan() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Payment Planning';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_paymentplan_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_paymentplan/r_paymentplan', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_paymentplan_view() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Payment Plan Report';
			$data['h3_title'] 	= 'Finance Report';
		
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
				$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_paymentplan/r_paymentplan_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_paymentplan/r_paymentplan_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}
	
    function r_receiveguarantee() 
	{		
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/finance_report_sd/'),'r_receiveguarantee_idx');
			redirect($secIndex);
		}
		else
		{
			redirect('Auth');
		}
    }

	function r_receiveguarantee_idx()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Receive Guarantee Report';
			$data['main_view'] 		= 'v_finance/v_report/r_bankguarante/r_bankguarante';
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/finance_report_sd'),'index');
						
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_receiveguarantee_view()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Bank Guarantee | Bank Guarantee Report';
		
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
		
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');
			
			$this->load->view('v_finance/v_report/r_bankguarante/r_bankguarante_report', $data);
		}
		else
		{
			redirect('Auth');
		}
	}	

	function r_bankrekonsil()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Bank Reconsiliation Report';
			$data['main_view'] 		= 'v_finance/v_report/r_bankrekonsil/r_bankrekonsil';
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/finance_report_sd'),'r_bankrekonsil_view');
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/finance_report_sd'),'r_bankrekonsil');
						
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_bankrekonsil_view()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$getAppName = $this->Menu_model->getAppName()->result();
			foreach($getAppName as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Finance Report | Outstanding Voucher Payment Report';
		
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
		
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');
			
			$this->load->view('v_finance/v_report/r_bankrekonsil/r_bankrekonsil_report', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_cashbankreport() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;
			$data['h2_title']		= 'JURNAL KEUANGAN';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_cashbankreport_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cashbankreport/r_cashbankreport', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_cashbankreport_view() // OK
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
				$data['h1_title'] 	= 'JURNAL KEUANGAN';
			}
			else
			{
				$data['h1_title'] 	= 'JURNAL FINANCE';
			}
		
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
		
			/*$viewSupp 			= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			//return false;
			
			if($viewSupp == 0)
			{
				$packageelementsSpl	= $_POST['packageelementsSpl'];
				$TOTSPL				= count($packageelementsSpl);
				if (count($packageelementsSpl)>0)
				{
					$mySelSupp	= $_POST['packageelementsSpl'];
					$row		= 0;
					foreach ($mySelSupp as $suppCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$SPLCODE1	= $suppCode;
						}
						else
						{
							$SPLCODE1	= "$SPLCODE1','$suppCode";
						}
					}
				}
				$data['TOTSPL'] 	= $TOTSPL;
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			else
			{
				$data['TOTSPL'] 	= 0;
				$SPLCODE1			= "";
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}*/
			//echo "test $SPLCODE1";
			
			$packageelementsCB	= $_POST['packageelementsCB'];
			$TOTACCSEL			= count($packageelementsCB);
			if (count($packageelementsCB)>0)
			{
				$mySelected_Acc	= $_POST['packageelementsCB'];
				$row		= 0;
				foreach ($mySelected_Acc as $AccSel)
				{
					$row	= $row + 1;
					if($row == 1)
					{
						$ACCSEL1	= $AccSel;
					}
					else
					{
						$ACCSEL1	= "$ACCSEL1','$AccSel";
					}
				}
			}
			$data['TOTACCSEL'] 	= $TOTACCSEL;
			$data['ACCSELCOL'] = "'$ACCSEL1'";
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewSupl'] 		= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');		// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_cashbankreport/r_cashbankreport_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_cashbankreport/r_cashbankreport_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_cekgiro() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;
			$data['h2_title']		= 'DAFTAR PENGELUARAN CEK';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_cekgiro_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cekgiro/r_cekgiro', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_cekgiro_view() // OK
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
				$data['h1_title'] 	= 'DAFTAR PENGELUARAN CEK';
			}
			else
			{
				$data['h1_title'] 	= 'CHECK LIST EXPENSES';
			}
		
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
		
			$viewSupp 			= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			//return false;
			
			if($viewSupp == 0)
			{
				$packageelementsSpl	= $_POST['packageelementsSpl'];
				$TOTSPL				= count($packageelementsSpl);
				if (count($packageelementsSpl)>0)
				{
					$mySelSupp	= $_POST['packageelementsSpl'];
					$row		= 0;
					foreach ($mySelSupp as $suppCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$SPLCODE1	= $suppCode;
						}
						else
						{
							$SPLCODE1	= "$SPLCODE1','$suppCode";
						}
					}
				}
				$data['TOTSPL'] 	= $TOTSPL;
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			else
			{
				$data['TOTSPL'] 	= 0;
				$SPLCODE1			= "";
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			//echo "test $SPLCODE1";
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewSupl'] 		= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');		// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_cekgiro/r_cekgiro_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_cekgiro/r_cekgiro_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_payment() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;
			$data['h2_title']		= 'PAYMENT';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_paymentreport_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_paymentreport/r_paymentreport', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_paymentreport_view() // OK
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
				$data['h1_title'] 	= 'Bukti Pengeluaran Kas / Bank';
			}
			else
			{
				$data['h1_title'] 	= 'Bukti Pengeluaran Kas / Bank';
			}
		
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
		
			/*$viewSupp 			= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			//return false;
			
			if($viewSupp == 0)
			{
				$packageelementsSpl	= $_POST['packageelementsSpl'];
				$TOTSPL				= count($packageelementsSpl);
				if (count($packageelementsSpl)>0)
				{
					$mySelSupp	= $_POST['packageelementsSpl'];
					$row		= 0;
					foreach ($mySelSupp as $suppCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$SPLCODE1	= $suppCode;
						}
						else
						{
							$SPLCODE1	= "$SPLCODE1','$suppCode";
						}
					}
				}
				$data['TOTSPL'] 	= $TOTSPL;
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			else
			{
				$data['TOTSPL'] 	= 0;
				$SPLCODE1			= "";
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}*/
			//echo "test $SPLCODE1";
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewSupl'] 		= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');		// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_finance/v_report/r_paymentreport/r_paymentreport_report_adm', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_paymentreport/r_paymentreport_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_cashadvance() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;
			$data['h2_title']		= 'CASH ADVANCE';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/finance_report/r_cashadvance_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function r_cashadvance_view() // OK
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
			$CFType			= $this->input->post('CFType');
			
			if($LangID == 'IND')
			{
				if($CFType == 1)
				{
					$data['h1_title'] 	= 'LAPORAN CASH ADVANCE';
				}
				else
				{
					$data['h1_title'] 	= 'REKAPITULASI LAPORAN CASH ADVANCE PROYEK';
				}
			}
			else
			{
				if($CFType == 1)
				{
					$data['h1_title'] 	= 'CASH ADVANCE REPORT';
				}
				else
				{
					$data['h1_title'] 	= 'REKAPITULASI LAPORAN CASH ADVANCE PROYEK';
				}
			}
		
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
		
			$viewSupp 			= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			//return false;
			
			if($viewSupp == 0)
			{
				$packageelementsSpl	= $_POST['packageelementsSpl'];
				$TOTSPL				= count($packageelementsSpl);
				if (count($packageelementsSpl)>0)
				{
					$mySelSupp	= $_POST['packageelementsSpl'];
					$row		= 0;
					foreach ($mySelSupp as $suppCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$SPLCODE1	= $suppCode;
						}
						else
						{
							$SPLCODE1	= "$SPLCODE1','$suppCode";
						}
					}
				}
				$data['TOTSPL'] 	= $TOTSPL;
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			else
			{
				$data['TOTSPL'] 	= 0;
				$SPLCODE1			= "";
				$data['SPLCODECOL'] = "'$SPLCODE1'";
			}
			//echo "test $SPLCODE1";
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['viewSupl'] 		= $this->input->post('viewSupl');	// All Supplier or Selected Supplier
			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');		// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			//echo $CFType;
			//return false;
			//echo "hahah $DefEmp_ID";
			if($DefEmp_ID == 'D15040004221')
			{
				if($CFType == 1)
				{
					$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance_reportD_adm', $data);
				}
				else
				{
					$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance_reportS_adm', $data);
				}
			}
			else
			{
				if($CFType == 1)
				{
					$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance_reportD', $data);
				}
				else
				{
					$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance_reportS', $data);
				}
			}
		}
		else
		{
			redirect('Auth');
		}
	}
}