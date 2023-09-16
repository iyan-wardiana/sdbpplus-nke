<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2017
 * File Name	= C_f1nR3p07t.php
 * Location		= -
*/

class C_f1nR3p07t extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_company/m_comprof/m_comprof', '', TRUE);
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

    function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			//$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_gl/c_report/'),'get_form_report_PL'); // Pertama dipanggil
			//redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
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
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title']	= 'TTK Tertunda';
				$data['h3_title']	= 'laporan';
			}
			else
			{
				$data['h2_title']	= 'TTK Outstanding';
				$data['h3_title']	= 'report';
			}
			
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_ttkoutstanding_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_ttkoutstanding/r_ttkoutstanding', $data);
		}
		else
		{
			redirect('__l1y');
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
						$row		= $row + 1;
						$PRJCODE1 	= $projCode;
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
			redirect('__l1y');
		}
	}

	function r_1h0g0bop4ym3nt() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_1h0g0bop4ym3nt1/?id='.$this->url_encryption_helper->encode_url($appName));
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
				
			// START : GET MENU NAME
				$mnCode				= 'MN260';
				$data["MenuApp"] 	= 'MN260';
				$data["MenuCode"] 	= 'MN260';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vw1h0g0bop4ym3ntv/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vw1h0g0bop4ym3ntv() // OK
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

			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}

			$SPLCODE		= $this->input->post('SPLCODE');
			$COLREFSPL 		= '';
			if($SPLCODE != '')
			{
				$refStep	= 0;
				
				foreach ($SPLCODE as $REFSPL)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFSPL	= "'$REFSPL'";
					}
					else
					{
						$COLREFSPL	= "$COLREFSPL,'$REFSPL'";
					}
				}
			}

			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['COLREFSPL'] 		= $COLREFSPL;
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['ISCATEG'] 		= $this->input->post('ISCATEG');
			$ISCATEG				= $this->input->post('ISCATEG');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			if($ISCATEG == 1)
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_report', $data);	// Rincian Umur hutang
			elseif($ISCATEG == 2)
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_ruh_report', $data);// Ringkasan Umur Hutang
			elseif($ISCATEG == 3)
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_bbp_report', $data);// Buku besar pembantu hutang
			elseif($ISCATEG == 4)
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_bbr_report', $data);// Buku besar pembantu hutang	
			elseif($ISCATEG == 5)
				$this->load->view('v_finance/v_report/r_outvoucpayment/r_outvoucpayment_perspl', $data);// Laporan Hutang Supplier
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_4pr3p0rt() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_4pr3p0rt1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_4pr3p0rt1() // OK
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
				$mnCode				= 'MN266';
				$data["MenuApp"] 	= 'MN266';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vw4pr3p0rtav/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_dpreport/r_dpreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vw4pr3p0rtav() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
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
				$mnCode				= 'MN266';
				$data["MenuApp"] 	= 'MN266';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$PRJCODE		= $this->input->post('PRJCODE');
			/*$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$PRJCODEPRJCODE	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}*/
			$COLREFPRJ 		= $PRJCODE;
			$SPLCODE		= $this->input->post('SPLCODE');
			$COLREFSPL 		= '';
			if($SPLCODE != '')
			{
				$refStep	= 0;
				echo "SPLCODE = $SPLCODE";
				if($SPLCODE == 1)
				{
					$data['PRJNAME'] 	= "Semua Proyek";
					$data['PRJCODE'] 	= "";
				}
				else
				{
					foreach ($SPLCODE as $REFSPL)
					{
						$refStep	= $refStep + 1;
						if($refStep == 1)
						{
							$COLREFSPL	= "'$REFSPL'";
						}
						else
						{
							$COLREFSPL	= "$COLREFSPL,'$REFSPL'";
						}
					}
				}
			}
			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['COLREFSPL'] 		= $COLREFSPL;

			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$CFType					= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			if($COLREFPRJ == 1)
			{
				$data['PRJNAME'] 	= "Semua Proyek";
				$data['PRJCODE'] 	= "";
			}
			else
			{
				$PRJNAME			= '';
				$getproject 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
				$qProject 			= $this->db->query($getproject)->result();
				foreach($qProject as $rowPRJ) :
					$PRJNAME		= $rowPRJ->PRJNAME;
				endforeach;
				$data['PRJNAME'] 	= $rowPRJ->PRJNAME;
				$data['PRJCODE'] 	= $PRJCODE;
			}


			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'LAPORAN UANG MUKA PEMASOK (Summary)';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'SUPPLIER ADVANCE REPORT';
				$data['h2_title']	= 'Report';
			}

			if($CFType == '1') // Summary
			{
				$this->load->view('v_finance/v_report/r_dpreport/r_dpreport_report_sum', $data); // Summary
			}
			else
			{
				$this->load->view('v_finance/v_report/r_dpreport/r_dpreport_report_det', $data); //Detail
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_77kv0uch3R() // OK
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
				$mnCode				= 'MN394';
				$data["MenuApp"] 	= 'MN394';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['h3_title']		= 'report';
			
			$this->db->select("DROP_REF2NUM");
			$qDrop = $this->db->get_where("tbl_drop_document", ["DROP_STATD" => 3]);
			$cDrop = $qDrop->num_rows();
			$data['cDrop'] = $cDrop;
			if($cDrop > 0)
			{
				foreach ($qDrop->result() as $rD):
					$data['DROP_REF2NUM'][] = $rD->DROP_REF2NUM;
				endforeach;
			}

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vw77kv0uch3R/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_ttkreport/r_ttkreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vw77kv0uch3R() // OK
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN394';
				$data["MenuApp"] 	= 'MN394';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['EmpID'] 			= $DefEmp_ID;
			$data['PRJCODE']		= $this->input->post('PRJCODE');
			// $data['SPLCODE']		= $this->input->post('SPLCODE');
			$DROP_CATEG				= $this->input->post('DROP_CATEG');
			$DROP_No 				= $this->input->post('DROP_No');

			$SPLCODE = [];
			$this->db->select("A.SPLCODE");
			$this->db->from("tbl_supplier A");
			$this->db->join("tbl_vendcat B", "B.VendCat_Code = A.SPLCAT", "INNER");
			$this->db->where_in("B.VendCatG_Code", $DROP_CATEG);
			$getSupplier = $this->db->get();

			if($getSupplier->num_rows() > 0)
			{
				foreach($getSupplier->result() as $rSUP):
					$SPLCODE[] = $rSUP->SPLCODE;
				endforeach;
			}

			$this->db->select("DROP_REF2NUM");
			$qDrop = $this->db->get_where("tbl_drop_document", ["DROP_STATD" => 3]);
			$cDrop = $qDrop->num_rows();
			$data['cDrop'] = $cDrop;
			if($cDrop > 0)
			{
				foreach ($qDrop->result() as $rD):
					$data['DROP_REF2NUM'][] = $rD->DROP_REF2NUM;
				endforeach;
			}

			$data['SPLCODE'] 		= $SPLCODE;
			$data['DROP_CATEG'] 	= $DROP_CATEG;
			$data['DROP_No']		= $DROP_No;
			$data['DROP_STAT']		= $this->input->post('DROP_STAT');
			$data['VOUCHER'] 		= $this->input->post('VOUCHER');
			$data['VOUCHER_DROP']	= $this->input->post('VOUCHER_DROP');

			$data['Start_Date'] 	= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('End_Date'))));
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$CFType					= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'TANDA TERIMA';
				$data['h2_title']	= 'Laporan';
			}
			else
			{
				$data['h1_title']	= 'TTK REPORT';
				$data['h2_title']	= 'Report';
			}

			if($CFType == '1') // Summary
			{
				$this->load->view('v_finance/v_report/r_ttkreport/r_ttkreport_sum', $data); // Summary
			}
			else
			{
				$this->load->view('v_finance/v_report/r_ttkreport/r_ttkreport_det', $data); //Detail
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getVoucher()
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PRJCODE		= $this->input->post('PRJCODE');
			$arrPRJ 		= implode("','", $PRJCODE);
			// $DROP_CATEG		= $this->input->post('DROP_CATEG');

			if($PRJCODE[0] != 1)
			{
				$addQPRJ 		= "AND A.proj_Code IN ('$arrPRJ')";
				$addQPRJ_INV 	= "AND A.PRJCODE IN ('$arrPRJ')";
			}
			else 
			{
				$addQPRJ 		= "";
				$addQPRJ_INV	= "";
			}
			

			/* -------------- hidden => untuk sementara tidak berdasarkan supplier ------
			$SPLCODE = [];
			$this->db->select("A.SPLCODE");
			$this->db->from("tbl_supplier A");
			$this->db->join("tbl_vendcat B", "B.VendCat_Code = A.SPLCAT", "INNER");
			if($DROP_CATEG[0] != 1) $this->db->where_in("B.VendCatG_Code", $DROP_CATEG);
			$getSupplier = $this->db->get();

			if($getSupplier->num_rows() > 0)
			{
				foreach($getSupplier->result() as $rSUP):
					$SPLCODE[] = $rSUP->SPLCODE;
				endforeach;

				$arrSPL 	= implode("','", $SPLCODE);
				$addQSPL 	= "AND A.PERSL_EMPID IN ('$arrSPL')";
			}
			------------------------- end hidden ---------------------------------- */

			$this->db->select("DROP_REF2NUM");
			$qDrop = $this->db->get_where("tbl_drop_document", ["DROP_STATD" => 3]);
			$cDrop = $qDrop->num_rows();
			if($cDrop > 0)
			{
				foreach ($qDrop->result() as $rD):
					$DROP_REF2NUM[] = $rD->DROP_REF2NUM;
				endforeach;

				$arrD_NUM 		= implode("','", $DROP_REF2NUM);
				$addQD_NUM 		= "AND A.JournalH_Code NOT IN ('$arrD_NUM')";
				$addQDINV_NUM 	= "AND A.INV_NUM NOT IN ('$arrD_NUM')";
			}
			else
			{
				$addQD_NUM 		= "";
				$addQDINV_NUM	= "";
			}

			$Start_Date 	= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('Start_Date'))));
			$End_Date 		= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('End_Date'))));

			$get_VOC 		= "SELECT A.JournalH_Code AS VOC_NUM, A.Manual_No AS VOC_CODE
								FROM tbl_journalheader_vcash A
								WHERE A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
								$addQPRJ $addQD_NUM
								AND A.GEJ_STAT IN (2,3)
								UNION
								SELECT DISTINCT A.JournalH_Code AS VOC_NUM, A.Manual_No AS VOC_CODE
								FROM tbl_journaldetail_pd A
								WHERE A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
								$addQPRJ $addQD_NUM
								AND A.GEJ_STAT IN (2,3) AND A.ISPERSL_REALIZ = 0
								UNION
								SELECT DISTINCT A.JournalH_Code AS VOC_NUM, A.Manual_No AS VOC_CODE
								FROM tbl_journaldetail_pd A
								WHERE A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
								$addQPRJ $addQD_NUM
								AND A.GEJ_STAT IN (2,3) AND A.ISPERSL_REALIZ = 1
								UNION
								SELECT A.INV_NUM AS VOC_NUM, A.INV_CODE AS VOC_CODE
								FROM tbl_pinv_header A
								WHERE A.INV_DATE BETWEEN '$Start_Date' AND '$End_Date'
								$addQPRJ_INV $addQDINV_NUM
								AND A.INV_STAT IN (2,3)";
			$data 			= $this->db->query($get_VOC)->result();

			// $this->db->distinct("A.JournalH_Code, A.Manual_No");
            // $this->db->from("tbl_journalheader_vcash A");
            // // $this->db->where_in("A.SPLCODE", $SPLCODE);
			// $this->db->where_in("A.PERSL_EMPID", $SPLCODE);
			// if($PRJCODE[0] != 1) $this->db->where_in("A.proj_Code", $PRJCODE);
			// if($cDrop > 0) $this->db->where_not_in("A.JournalH_Code", $DROP_REF2NUM);
			// $this->db->where("A.JournalH_Date >=", date('Y-m-d', strtotime($Start_Date)));
			// $this->db->where("A.JournalH_Date <=", date('Y-m-d', strtotime($End_Date)));
			// // $this->db->where("A.Emp_ID", $DefEmp_ID);
			// // $this->db->where_not_in("A.JournalType", ["BP","GEJ"]);
			// $this->db->where_in("A.GEJ_STAT", [2,3]);
            // $getVOC = $this->db->get();
            // $data 	= $get_VOC->result();
            echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getVoucherDROP()
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PRJCODE		= $this->input->post('PRJCODE');
			$arrPRJ 		= implode("','", $PRJCODE);
			// $DROP_CATEG		= $this->input->post('DROP_CATEG');
			if($PRJCODE[0] != 1) 
			{
				$addQPRJ 		= "AND A.proj_Code IN ('$arrPRJ')";
				$addQPRJ_INV 	= "AND A.PRJCODE IN ('$arrPRJ')";
			}
			else 
			{
				$addQPRJ 		= "";
				$addQPRJ_INV 	= "";
			}

			$Start_Date 	= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('Start_Date'))));
			$End_Date 		= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('End_Date'))));

			/* -------------- hidden => untuk sementara tidak berdasarkan supplier ------
			$SPLCAT = [];
			$this->db->distinct("A.SPLCAT");
			$this->db->select("A.SPLCAT");
			$this->db->from("tbl_supplier A");
			$this->db->join("tbl_vendcat B", "B.VendCat_Code = A.SPLCAT", "INNER");
			if($DROP_CATEG[0] != 1) $this->db->where_in("B.VendCatG_Code", $DROP_CATEG);
			$getVendCat = $this->db->get();

			if($getVendCat->num_rows() > 0)
			{
				foreach($getVendCat->result() as $rVC):
					$SPLCAT[] = $rVC->SPLCAT;
				endforeach;
			}
			------------------------- end hidden ---------------------------------- */

			$DROP_CODE 		= $this->input->post('DROP_CODE');
			$this->db->select("DROP_REF2NUM");
			$this->db->where("DROP_DATE >=", date('Y-m-d', strtotime($Start_Date)));
			$this->db->where("DROP_DATE <=", date('Y-m-d', strtotime($End_Date)));
			$this->db->where(["DROP_CODE" => $DROP_CODE, "DROP_STATD" => 3]);
			if($PRJCODE[0] != 1) $this->db->where_in("PRJCODE", $PRJCODE);
			// if($DROP_CATEG[0] != 1) $this->db->where_in("DROP_CATEG", $SPLCAT);
			$qDrop = $this->db->get("tbl_drop_document");
			$cDrop = $qDrop->num_rows();
			if($cDrop > 0)
			{
				foreach ($qDrop->result() as $rD):
					$DROP_REF2NUM[] = $rD->DROP_REF2NUM;
				endforeach;

				$arrD_NUM 		= implode("','", $DROP_REF2NUM);
				$addQD_NUM 		= "AND A.JournalH_Code IN ('$arrD_NUM')";
				$addQDINV_NUM 	= "AND A.INV_NUM IN ('$arrD_NUM')";
			}
			else
			{
				$addQD_NUM 		= "";
				$addQDINV_NUM 	= "";
			}

			// $Start_Date 	= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('Start_Date'))));
			// $End_Date 		= date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('End_Date'))));

			$data = '';
			if($cDrop > 0)
			{
				$get_VOC 		= "SELECT A.JournalH_Code AS VOC_NUM, A.Manual_No AS VOC_CODE
									FROM tbl_journalheader_vcash A
									WHERE A.GEJ_STAT IN (2,3)
									$addQPRJ $addQD_NUM
									UNION
									SELECT A.JournalH_Code AS VOC_NUM, A.Manual_No AS VOC_CODE
									FROM tbl_journalheader_pd A
									WHERE A.GEJ_STAT IN (2,3)
									$addQPRJ $addQD_NUM
									UNION
									SELECT A.INV_NUM AS VOC_NUM, A.INV_CODE AS VOC_CODE
									FROM tbl_pinv_header A
									WHERE A.INV_DATE BETWEEN '$Start_Date' AND '$End_Date'
									$addQPRJ_INV $addQDINV_NUM
									AND A.INV_STAT IN (2,3)";
				$data 			= $this->db->query($get_VOC)->result();

				// $this->db->distinct("A.JournalH_Code, A.Manual_No");
	            // $this->db->from("tbl_journalheader_vcash A");
				// $this->db->where_in("A.JournalH_Code", $DROP_REF2NUM);
				// // $this->db->where("A.JournalH_Date >=", date('Y-m-d', strtotime($Start_Date)));
				// // $this->db->where("A.JournalH_Date <=", date('Y-m-d', strtotime($End_Date)));
				// $this->db->where_not_in("A.JournalType", ["BP","GEJ"]);
				// $this->db->where_in("A.GEJ_STAT", [2,3]);
	            // $getVOC = $this->db->get();
	            // $data 	= $getVOC->result();
			}
            echo json_encode($data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function pushDropDoc()
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			foreach($_POST['data'] as $d):
				$DROP_NUM 		= $d['DROP_NUM'];
				$DROP_REF1NUM	= $d['DROP_REF1NUM'];
				$DROP_REF2NUM 	= $d['DROP_REF2NUM'];
				$query = $this->db->get_where("tbl_drop_document", ["DROP_REF1NUM" => $DROP_REF1NUM, "DROP_REF2NUM" => $DROP_REF2NUM]);
				if($query->num_rows() == 0)
					$this->db->insert("tbl_drop_document", $d);
			endforeach;
		}
		else
		{
			redirect('__l1y');
		}
	}

	function getDROP_NO()
	{
		$this->load->model('m_project/m_project_sicer/m_project_sicer', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PRJCODE 		= $this->input->post('PRJCODE');
			$this->db->distinct("A.DROP_CODE");
			$this->db->select("A.DROP_CODE, RIGHT(A.DROP_CODE, 4) AS DROP_NO", FALSE);
			$this->db->where(["A.DROP_STATD" => 3]);
			$this->db->where_in("A.PRJCODE", $PRJCODE);
			$query = $this->db->get("tbl_drop_document A");
			$data = "";
			if($query->num_rows() > 0) $data = $query->result();
			echo json_encode($data);
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
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_paymentplan_view/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_paymentplan/r_paymentplan', $data);
		}
		else
		{
			redirect('__l1y');
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
						$row		= $row + 1;
						$PRJCODE1 	= $projCode;
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
			redirect('__l1y');
		}
	}
	
    function r_receiveguarantee() 
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$secIndex 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/c_f1nR3p07t_sd/'),'r_receiveguarantee_idx');
			redirect($secIndex);
		}
		else
		{
			redirect('__l1y');
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
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/c_f1nR3p07t_sd'),'index');
						
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
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
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/c_f1nR3p07t_sd'),'r_bankrekonsil_view');
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_finance/c_f1nR3p07t_sd'),'r_bankrekonsil');
						
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
		}
	}

	function r_cb1nkr3p_0rt() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_cb1nkr3p_0rta/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r_cb1nkr3p_0rta() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN265';
				$data["MenuApp"] 	= 'MN265';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['h2_title']		= 'JURNAL KEUANGAN';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vwcb1nkr3p_0rtav/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cashbankreport/r_cashbankreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwcb1nkr3p_0rtav() // OK
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

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN346';
				$data["MenuApp"] 	= 'MN346';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}
		
			$selAccount		= $this->input->post('selAccount');
			$COLREFACC 		= '';
			if($selAccount != '')
			{
				$refStep	= 0;
				
				foreach ($selAccount as $REFACC)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFACC	= "'$REFACC'";
					}
					else
					{
						$COLREFACC	= "$COLREFACC,'$REFACC'";
					}
				}
			}
				
			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['COLREFACC'] 		= $COLREFACC;
			$data['ACCSELCOL'] 		= $COLREFACC;

			// $data['Start_Date'] 	= $this->input->post('Start_Date');
			// $data['End_Date'] 		= $this->input->post('End_Date');
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			$this->load->view('v_finance/v_report/r_cashbankreport/r_cashbankreport_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_c3kand71r0() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_c3kand71r0a/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r_c3kand71r0a() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Daftar Pengeluaran Cek';
			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vc3kand71r0av/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cekgiro/r_cekgiro', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vc3kand71r0av() // OK
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
						$row		= $row + 1;
						$SPLCODE1	= $suppCode;
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
			redirect('__l1y');
		}
	}

	function r_p1y13e14t() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_p1y13e14t1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r_p1y13e14t1() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN274';
				$data["MenuApp"] 	= 'MN274';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vwp1y13e14tv/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_paymentreport/r_paymentreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwp1y13e14tv() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN274';
				$data["MenuApp"] 	= 'MN274';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}
				
			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['SPLCODE']		= $this->input->post('SPLCODE');
			$data['selAccount']		= $this->input->post('selAccount');

			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');		// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$viewType 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			if($viewType == 1)
				$this->load->view('v_finance/v_report/r_paymentreport/r_paymentreport_report_xlxs', $data);
			else
				$this->load->view('v_finance/v_report/r_paymentreport/r_paymentreport_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_c15h14v1n3e() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_c15h14v1n3ea/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r_c15h14v1n3ea() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN346';
				$data["MenuApp"] 	= 'MN346';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['h3_title']		= 'report';
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vwc15h14v1n3ew/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwc15h14v1n3ew() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN346';
				$data["MenuApp"] 	= 'MN346';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}
				
			$data['COLREFPRJ'] 		= $COLREFPRJ;

			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['selAccount'] 	= $this->input->post('selAccount');	// Payed or Outstanding
			$data['ISPAYED'] 		= $this->input->post('ISPAYED');	// Payed or Outstanding
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$CFType					= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			if($CFType == 1)
			{
				$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance_reportD', $data);
			}
			else
			{
				$this->load->view('v_finance/v_report/r_cashadvance/r_cashadvance_reportS', $data);
			}

		}
		else
		{
			redirect('__l1y');
		}
	}

 	function r_1nvs3l3ct10n() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_1nvs3l3ct10n1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r_1nvs3l3ct10n1() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$data['title'] 			= $appName;

			// GET MENU DESC
				$LangID 			= $this->session->userdata['LangID'];
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN360';
				$data["MenuApp"] 	= 'MN360';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vw1nvs3l3ct10n/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_invselect/r_invselect', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vw1nvs3l3ct10n() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 	= $appName;
			$LangID 		= $this->session->userdata['LangID'];
			$CFType			= $this->input->post('CFType');

			// GET MENU DESC
				$this->load->model('m_updash/m_updash', '', TRUE);
				$mnCode				= 'MN360';
				$data["MenuApp"] 	= 'MN360';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($LangID == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
		
			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}

			$SPLCODE		= $this->input->post('SPLCODE');
			$COLREFSPL 		= '';
			if($SPLCODE != '')
			{
				$refStep	= 0;
				
				foreach ($SPLCODE as $REFSPL)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFSPL	= "'$REFSPL'";
					}
					else
					{
						$COLREFSPL	= "$COLREFSPL,'$REFSPL'";
					}
				}
			}
				
			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['COLREFSPL'] 		= $COLREFSPL;

			$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['CFType'] 		= $this->input->post('CFType');		// Summary or Detail
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_finance/v_report/r_invselect/r_invselect_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_p3rM14v1n3e() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_p3rM14v1n3ea/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function r_p3rM14v1n3ea() // OK
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
				$data['h2_title']	= 'Perm. Pengeluaran';
				$data['h3_title']	= 'laporan';
			}
			else
			{
				$data['h2_title']	= 'Cash Book Request';
				$data['h3_title']	= 'report';
			}

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vwp3rM14v1n3ew/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_outrequest/r_outrequest', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwp3rM14v1n3ew() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title']	= 'LAPORAN PERMINTAAN PENGELUARAN';
				$data['h3_title']	= 'laporan';
			}
			else
			{
				$data['h2_title']	= 'CASH OUT REQUEST';
				$data['h3_title']	= 'report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('viewProj');
			$PRJCODE			= $this->input->post('viewProj');
			$getproject = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
							WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') 
							AND PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			$data['Start_Date'] = $Start_Date;
			$data['End_Date'] 	= $End_Date;
			$data['CFStat'] 	= $this->input->post('CFStat');
			$data['CFType'] 	= $this->input->post('CFType');		// 1. Summary or 2.Detail
			$CFType				= $this->input->post('CFType');		// 1. Summary or 2.Detail
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			if($CFType == 1)
				$this->load->view('v_finance/v_report/r_outrequest/r_outrequest_report_sum', $data);
			else
				$this->load->view('v_finance/v_report/r_outrequest/r_outrequest_report_det', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function aR45_k45() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_aR45_k45/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_aR45_k45() // OK
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
				$data['h2_title']	= 'Arus Kas';
				$data['h3_title']	= 'laporan';
			}
			else
			{
				$data['h2_title']	= 'Cash Flow';
				$data['h3_title']	= 'report';
			}

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vwaR45_k45/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cashflow/r_cashflow', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_vwaR45_k45() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;
			$comp_name	= $therow->comp_name;
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 		= $appName;
			$data['comp_name']	= $comp_name;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h2_title']	= 'LAPORAN ARUS KAS';
				$data['h3_title']	= 'laporan';
			}
			else
			{
				$data['h2_title']	= 'CASH FLOW';
				$data['h3_title']	= 'report';
			}
		
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			$data['PRJCODE'] 	= $this->input->post('PRJCODE');
			$PRJCODE			= $this->input->post('PRJCODE');
			$getproject = "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
							WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') 
							AND PRJCODE = '$PRJCODE' ORDER BY A.PRJCODE";
			$qProject 		= $this->db->query($getproject)->result();
			foreach($qProject as $rowPRJ) :
				$data['PRJNAME'] = $rowPRJ->PRJNAME;
			endforeach;
			
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			//$Start_Date			= date('Y-m-d', strtotime($datePeriod2[0]));
			//$End_Date			= date('Y-m-d', strtotime($datePeriod2[1]));
			
			//$data['Start_Date'] = $Start_Date;
			//$data['End_Date'] 	= $End_Date;
			$data['viewType'] 	= $this->input->post('viewType');	// View or Excel
			
			$this->load->view('v_finance/v_report/r_cashflow/r_cashflow_report_det', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_1h0g0bor3c31v4bl3() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_1h0g0bor3c31v4bl3x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_1h0g0bor3c31v4bl3x() // OK
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
				
			// START : GET MENU NAME
				$mnCode				= 'MN206';
				$data["MenuApp"] 	= 'MN206';
				$data["MenuCode"] 	= 'MN206';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vw1h0g0bor3c31v4bl3x/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_outvoucreceiv/r_outvoucreceiv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vw1h0g0bor3c31v4bl3x() // OK
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
				$data['h1_title'] 	= 'Laporan Piutang';
			}
			else
			{
				$data['h1_title'] 	= 'Account Receivable Report';
			}

			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}

			$CUST_CODE		= $this->input->post('CUST_CODE');
			$COLREFCUST 		= '';
			if($CUST_CODE != '')
			{
				$refStep	= 0;
				
				foreach ($CUST_CODE as $REFSPL)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFCUST	= "'$REFSPL'";
					}
					else
					{
						$COLREFCUST	= "$COLREFCUST,'$REFSPL'";
					}
				}
			}

			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['COLREFCUST'] 	= $COLREFCUST;
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$this->load->view('v_finance/v_report/r_outvoucreceiv/r_outvoucreceiv_percust', $data);// Laporan Hutang Supplier
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r_c45Hb4NkmU74710n() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_c45Hb4NkmU74710nx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_c45Hb4NkmU74710nx() // OK
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
				
			// START : GET MENU NAME
				$mnCode				= 'MN262';
				$data["MenuApp"] 	= 'MN262';
				$data["MenuCode"] 	= 'MN262';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/r_vwc45Hb4NkmU74710nx/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_cashbankmutation/r_cashbankmutation', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function r_vwc45Hb4NkmU74710nx() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$updGEJH 		= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.GEJ_STAT = B.GEJ_STAT, A.JournalH_Date = B.JournalH_Date
							WHERE A.JournalH_Code = B.JournalH_Code";
		$this->db->query($updGEJH);

		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title'] 	= 'SALDO AKHIR BANK DAN MUTASI HARIAN';
			}
			else
			{
				$data['h1_title'] 	= 'DAILY MUTATION BANK REPORT';
			}

			$PRJCODE		= $this->input->post('PRJCODE');
			$COLREFPRJ 		= '';
			if($PRJCODE != '')
			{
				$refStep	= 0;
				
				foreach ($PRJCODE as $REFPRJ)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLREFPRJ	= "'$REFPRJ'";
					}
					else
					{
						$COLREFPRJ	= "$COLREFPRJ,'$REFPRJ'";
					}
				}
			}

			$data['COLREFPRJ'] 		= $COLREFPRJ;
			$data['Start_Date'] 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('Start_Date'))));
			$data['End_Date'] 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('End_Date'))));
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');	// View or Excel
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$this->load->view('v_finance/v_report/r_cashbankmutation/r_cashbankmutation_report', $data);// Laporan Hutang Supplier
		}
		else
		{
			redirect('__l1y');
		}
	}

	function v0uCh3rRepidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_v0uCh3rRep/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_v0uCh3rRep()
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
				$data["h1_title"] 	= "Lap. Voucher Per Supplier";
				$data["h2_title"] 	= "";
			}
			else
			{
				$data["h1_title"] 	= "Lap. Voucher Per Supplier";
				$data["h2_title"] 	= "";
			}

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/v0uCh3rRep_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_voucherreport/r_voucherreport', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function v0uCh3rRep_0fv13w()
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];

			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Laporan Voucher Per Supplier";
				$data["h2_title"] 	= "";
			}
			else
			{
				$data["h1_title"] 	= "Laporan Voucher Per Supplier";
				$data["h2_title"] 	= "";
			}

			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$data['SPLCODE']	= $this->input->post('SPLCODE');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');

			$this->load->view('v_finance/v_report/r_voucherreport/r_voucherreport_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function k45b4nkPRYR3pidx()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_k45b4nkPRYR3p/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_k45b4nkPRYR3p()
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
				$data["h1_title"] 	= "Lap. Kas & Bank Proyek";
				$data["h2_title"] 	= "";
			}
			else
			{
				$data["h1_title"] 	= "Lap. Kas & Bank Proyek";
				$data["h2_title"] 	= "";
			}

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/k45b4nkPRYR3p_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_kasbankPRY/r_kasbankPRY', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function k45b4nkPRYR3p_0fv13w()
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];

			

			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');
			$Type 				= $this->input->post('Type');

			if($Type == 0)
			{
				if($LangID == 'IND')
				{
					$data["h1_title"] 	= "LAPORAN PENERIMAAN, PENGELUARAN, BANK & KAS";
					$data["h2_title"] 	= "";
				}
				else
				{
					$data["h1_title"] 	= "LAPORAN PENERIMAAN, PENGELUARAN, BANK & KAS";
					$data["h2_title"] 	= "";
				}

				$this->load->view('v_finance/v_report/r_kasbankPRY/r_kasbankPRY_report', $data);
			}
			else
			{
				if($LangID == 'IND')
				{
					$data["h1_title"] 	= "LAPORAN TRANSAKSI PENERIMAAN - PENGELUARAN BANK & KAS";
					$data["h2_title"] 	= "";
				}
				else
				{
					$data["h1_title"] 	= "LAPORAN TRANSAKSI PENERIMAAN - PENGELUARAN BANK & KAS";
					$data["h2_title"] 	= "";
				}
				
				$this->load->view('v_finance/v_report/r_kasbankPRY/r_kasbankPRY_reportD', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}

	function out5t4nd1n9pd()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_f1nR3p07t/r_out5t4nd1n9pd/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r_out5t4nd1n9pd()
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
				$data["h1_title"] 	= "Lap. Outstanding PD";
				$data["h2_title"] 	= "";
			}
			else
			{
				$data["h1_title"] 	= "Lap. Outstanding PD";
				$data["h2_title"] 	= "";
			}

			$data['form_action'] 	= site_url('c_finance/c_f1nR3p07t/out5t4nd1n9pd_0fv13w/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$this->load->view('v_finance/v_report/r_outstandingPD/r_outstandingPD', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function out5t4nd1n9pd_0fv13w()
	{
		$idAppName	= $_GET['id'];
		$appName	= $this->url_encryption_helper->decode_url($idAppName);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "LAPORAN OUTSTANDING PINJAMAN DINAS";
				$data["h2_title"] 	= "";
			}
			else
			{
				$data["h1_title"] 	= "LAPORAN OUTSTANDING PINJAMAN DINAS";
				$data["h2_title"] 	= "";
			}
			

			$data['PRJCODE']	= $this->input->post('PRJCODE');
			$data['SPLCODE']	= $this->input->post('SPLCODE');
			$datePeriod1		= $this->input->post('datePeriod');
			$datePeriod2		= explode(" - ", $datePeriod1);
			$data['datePeriod']	= $datePeriod1;
			$data['Start_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[0])));
			$data['End_Date']	= date('Y-m-d', strtotime(str_replace('/', '-', $datePeriod2[1])));
			$data['viewType'] 	= $this->input->post('viewType');

			$this->load->view('v_finance/v_report/r_outstandingPD/r_outstandingPD_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}