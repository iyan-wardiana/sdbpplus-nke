<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Agustus 2018
 * File Name	= C_1nVR3p07t.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_1nVR3p07t extends CI_Controller  
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

    function index() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_1nVR3p07t/r3ce1p7/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }

	function gr319eMU74at10n()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_1nVR3p07t/gr319eMU74at10nX/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function gr319eMU74at10nX() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_inventory/c_1nVR3p07t/r_vwgr319eMU74at10n/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN343';
				$data["MenuApp"] 	= 'MN343';
				$data["MenuCode"] 	= 'MN343';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME
			
			$this->load->view('v_inventory/v_report/v_itemmutation/v_itemmutation', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_vwgr319eMU74at10n()
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
				$data['h1_title'] 	= 'LAPORAN MUTASI GUDANG GRIGE, PO DAN BARANG JADI';
			}
			else
			{
				$data['h1_title'] 	= 'GRIGE, PO AND FINISH GOOD MUTATION REPORT';
			}
			
			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$PRJCODE1				= $this->input->post('PRJCODE');
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			/*$data['Start_Date'] 	= $this->input->post('Start_Date');	// Month
			$data['End_Date'] 		= $this->input->post('End_Date');	// Month*/
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$data['RepStatus'] 		= $this->input->post('RepStatus');	// View or Excel

			$this->load->view('v_inventory/v_report/v_itemmutation/v_itemmutation_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r3ce1p7() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Laporan";
				$data["h2_title"] 	= "Penerimaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Report";
				$data["h2_title"] 	= "Material Receipt";
			}
			
			$data['form_action'] 	= site_url('c_inventory/c_1nVR3p07t/r3ce1p7_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_inventory/v_report/v_itemreceipt/v_itemreceipt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r3ce1p7_0fv13w() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
						
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Laporan Penerimaan Materil";
			}
			else
			{
				$data["h1_title"] 	= "Material Receipt Report";
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
				$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			
			$packageelementsCB	= $_POST['packageelementsCB'];
			$TOTITMSEL			= count($packageelementsCB);
			if (count($packageelementsCB)>0)
			{
				$Selected_ITM	= $_POST['packageelementsCB'];
				$row		= 0;
				foreach ($Selected_ITM as $ITMSel)
				{
					$row	= $row + 1;
					if($row == 1)
					{
						$ITMSEL1	= $ITMSel;
					}
					else
					{
						$ITMSEL1	= "$ITMSEL1','$ITMSel";
					}
				}
			}
			$data['TOTITMSEL'] 	= $TOTITMSEL;
			$data['ITMSELCOL'] = "'$ITMSEL1'";
						
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['Start_Date'] 	= date('Y-m-d', strtotime($this->input->post('Start_Date')));
			$data['End_Date'] 		= date('Y-m-d', strtotime($this->input->post('End_Date')));
			$data['TYPE'] 			= $this->input->post('TYPE');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_inventory/v_report/v_itemreceipt/v_itemreceipt_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function u_m7rl() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_1nVR3p07t/um7rl/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function um7rl() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Laporan";
				$data["h2_title"] 	= "Penggunaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Report";
				$data["h2_title"] 	= "Material Usage";
			}
			
			$data['form_action'] 	= site_url('c_inventory/c_1nVR3p07t/um7rl_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_inventory/v_report/v_itemusage/v_itemusage', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function um7rl_0fv13w() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
						
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Laporan Penggunaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Material Usage Report";
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
				$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			}
			else
			{
				$data['TOTPROJ'] 	= 0;
				$PRJCODE1			= "";
				$data['PRJCODECOL'] = "'$PRJCODE1'";
			}
			
			$packageelementsCB	= $_POST['packageelementsCB'];
			$TOTITMSEL			= count($packageelementsCB);
			if (count($packageelementsCB)>0)
			{
				$Selected_ITM	= $_POST['packageelementsCB'];
				$row		= 0;
				foreach ($Selected_ITM as $ITMSel)
				{
					$row	= $row + 1;
					if($row == 1)
					{
						$ITMSEL1	= $ITMSel;
					}
					else
					{
						$ITMSEL1	= "$ITMSEL1','$ITMSel";
					}
				}
			}
			$data['TOTITMSEL'] 	= $TOTITMSEL;
			$data['ITMSELCOL'] = "'$ITMSEL1'";
			
			$data['viewProj'] 		= $this->input->post('viewProj');	// All Project or Selected Project
			$data['Start_Date'] 	= date('Y-m-d', strtotime($this->input->post('Start_Date')));
			$data['End_Date'] 		= date('Y-m-d', strtotime($this->input->post('End_Date')));
			$data['TYPE'] 			= $this->input->post('TYPE');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_inventory/v_report/v_itemusage/v_itemusage_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function i7M43t41L() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname LIMIT 1";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_1nVR3p07t/i7M43t41Lx/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function i7M43t41Lx() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['appName'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Detil Budget Item";
				$data["h2_title"] 	= "Laporan";
			}
			else
			{
				$data["h1_title"] 	= "Item Budget Detail";
				$data["h2_title"] 	= "Report";
			}
			
			$data['form_action'] 	= site_url('c_inventory/c_1nVR3p07t/ri7M43t41Lx_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_inventory/v_report/v_itembudet/v_itembudet', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function ri7M43t41Lx_0fv13w() // G
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "LAPORAN BUDGET - DETAIL";
				$data["h2_title"] 	= "Detail Budget";
			}
			else
			{
				$data["h1_title"] 	= "BUDGET REPORT - DETAIL";
				$data["h2_title"] 	= "Budget Detail";
			}
			
			$PRJCODE 			= $this->input->post('PRJCODE');
			$data['PRJCODE'] 	= $PRJCODE;
			$ITM_CODE 			= $this->input->post('ITM_CODE');
			$data['ITM_CODE'] 	= $ITM_CODE;
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			$data['datePeriod'] = $datePeriod1;
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_inventory/v_report/v_itembudet/v_itembudet_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function i73mU53dcHmX()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_1nVR3p07t/i73mU53dcHm/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function i73mU53dcHm() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['form_action']= site_url('c_inventory/c_1nVR3p07t/r_vwi73mU53dcHm/?id='.$this->url_encryption_helper->encode_url($appName));
			$LangID 			= $this->session->userdata['LangID'];
				
			// START : GET MENU NAME
				$mnCode				= 'MN245';
				$data["MenuApp"] 	= 'MN245';
				$data["MenuCode"] 	= 'MN245';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME
			
			$this->load->view('v_inventory/v_report/v_itemused_chm/v_itemused_chm', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function r_vwi73mU53dcHm()
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
				$data['h1_title'] 	= 'LAPORAN PEMAKAIAN OBAT';
			}
			else
			{
				$data['h1_title'] 	= 'CHEMICAL USED REPORT';
			}
			
			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$PRJCODE1				= $this->input->post('PRJCODE');
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			/*$data['Start_Date'] 	= $this->input->post('Start_Date');	// Month
			$data['End_Date'] 		= $this->input->post('End_Date');	// Month*/
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$data['RepStatus'] 		= $this->input->post('RepStatus');	// View or Excel

			$this->load->view('v_inventory/v_report/v_itemused_chm/v_itemused_chm_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}