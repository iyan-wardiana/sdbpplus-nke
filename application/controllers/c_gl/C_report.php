<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 08 Maret 2017
 * File Name	= C_report.php
 * Location		= -
*/

class C_report  extends CI_Controller  
{
    function cashflow() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			
			$data['showIndex'] 			= site_url('c_gl/c_report/cashflow/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Cash Flow | Cash Flow Report';
			$data['main_view'] 			= 'v_gl/v_report/v_cashflow/v_cashflow';
			$this->load->view('v_gl/v_report/v_cashflow/v_cashflow', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function cashflow_view() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Cash Flow | Cash Flow Report';
			$data['h2_title'] 		= 'Cash Flow | Cash Flow Report';
		
			$packageelements		= $_POST['packageelements'];
			$TOTPROJ				= count($packageelements);
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
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_gl/v_report/v_cashflow/v_cashflow_report_adm', $data);
			}
			else
			{
				$this->load->view('v_gl/v_report/v_cashflow/v_cashflow_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function profit_loss() 
	{
		$this->load->model('m_gl/m_report/m_report', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			
			$data['showIndex'] 			= site_url('c_gl/c_report/profit_loss/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action'] 		= site_url('c_gl/c_report/profit_loss_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Profit and Loss';
			
			$data['viewproject'] 		= $this->m_report->get_proj_detail()->result();
			
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
    function profit_loss_view() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report';
			$data['h2_title'] 		= 'Profit and Loss Report';
		
			$packageelements		= $_POST['packageelements'];
			$TOTPROJ				= count($packageelements);
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
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_report', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function balancesheet() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$LangID 			= $this->session->userdata['LangID'];
			$data['title'] 		= $appName;
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Neraca';
				$data['h3_title'] 	= 'Laporan';
			}
			else
			{
				$data['h2_title'] 	= 'Balance Sheet';
				$data['h3_title'] 	= 'Report';
			}
			
			$this->load->view('v_gl/v_report/v_balancesheet/v_balancesheet', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function balancesheet_view() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 			= $this->session->userdata['LangID'];
			$data['title'] 		= $appName;
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Neraca';
				$data['h3_title'] 	= 'Laporan';
			}
			else
			{
				$data['h2_title'] 	= 'Balance Sheet';
				$data['h3_title'] 	= 'Report';
			}
		
			$packageelements		= $_POST['packageelements'];
			$TOTPROJ				= count($packageelements);
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
			//$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['WEEKTO'] 		= $this->input->post('WEEKTO');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_gl/v_report/v_balancesheet/v_balancesheet_report_adm', $data);
			}
			else
			{
				$this->load->view('v_gl/v_report/v_balancesheet/v_balancesheet_report', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function claim_monitoring() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$LangID 			= $this->session->userdata['LangID'];
			$data['title'] 		= $appName;
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Monitoring Tagihan';
				$data['h3_title'] 	= 'Laporan';
			}
			else
			{
				$data['h2_title'] 	= 'Claim Monitoring';
				$data['h3_title'] 	= 'Report';
			}
			
			$this->load->view('v_gl/v_report/v_claim_monitoring/v_claim_monitoring', $data);
		}
		else
		{
			redirect('Auth');
		}
    }
	
    function claim_monitoring_view() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 			= $this->session->userdata['LangID'];
			$data['title'] 		= $appName;
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Neraca';
				$data['h3_title'] 	= 'Laporan';
			}
			else
			{
				$data['h2_title'] 	= 'Balance Sheet';
				$data['h3_title'] 	= 'Report';
			}
		
			$packageelements		= $_POST['packageelements'];
			$TOTPROJ				= count($packageelements);
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
			//$data['Start_Date'] 	= $this->input->post('Start_Date');
			//$data['End_Date'] 	= $this->input->post('End_Date');
			$data['WEEKTO'] 		= $this->input->post('WEEKTO');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			if($DefEmp_ID == 'D15040004221')
			{
				$this->load->view('v_gl/v_report/v_claim_monitoring/v_claim_monitoring_report_adm', $data);
			}
			else
			{
				$this->load->view('v_gl/v_report/v_claim_monitoring/v_claim_monitoring_report_adm', $data);
			}
		}
		else
		{
			redirect('Auth');
		}
    }
}