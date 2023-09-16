<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 08 Maret 2017
 * File Name	= C_r3p0r77l.php
 * Location		= -
*/

class C_r3p0r77l  extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];

		function cut_text2($var, $len = 200, $txt_titik = "") 
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

 	function neraca() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_r3p0r77l/idxb4lsh337/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function idxn3r4c4() 
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
			
			$this->load->view('v_gl/v_report/v_neraca/v_neraca', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
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
			
			$data['showIndex'] 			= site_url('c_gl/c_r3p0r77l/cashflow/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Cash Flow | Cash Flow Report';
			$data['main_view'] 			= 'v_gl/v_report/v_cashflow/v_cashflow';
			$this->load->view('v_gl/v_report/v_cashflow/v_cashflow', $data);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
		}
    }
	
 	function profit_loss() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_r3p0r77l/idxpr0f17l005/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function idxpr0f17l005() 
	{
		$this->load->model('m_gl/m_report/m_report', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$data['showIndex'] 		= site_url('c_gl/c_r3p0r77l/profit_loss/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action'] 	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vw/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['MenuCode']		= 'MN236';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= 'Laba Rugi';
				$data['h2_title'] 	= 'Laporan';
				$data['createLR']	= 'Buat Laba Rugi';
				$data['DownlLR']	= 'Unduh Profit Loss';
				$data['viewLR']		= 'Tampilkan Laba Rugi';
				$data['sure']		= 'Yakin akan membuat Laporan Laba Rugi ... ?';
				$data['cannot1']	= 'Laporan laba rugi untuk bulan ini sudah dibuat.';
				$data['cannot2']	= 'Maaf. Anda tidak dapat membuat laporan laba rugi untuk bulan ini.';
			}
			else
			{
				$data['title'] 		= 'Profit and Loss';
				$data['h2_title'] 	= 'Report';
				$data['createLR']	= 'Create Profit Loss';
				$data['DownlLR']	= 'Donwload Profit Loss';
				$data['viewLR']		= 'View Profit Loss';
				$data['sure']		= 'Are you sure want to create Profit and Loss Report ... ?';
				$data['cannot1']	= 'Profit and Loss Report for this month has been created.';
				$data['cannot2']	= 'Sorry. You can not create Profit and Loss Report for this month.';
			}
			
			$data['viewproject'] 		= $this->m_report->get_proj_detail()->result();
			
			//$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_list', $data);
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataLR() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
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
			
			$columns_valid 	= array("PRJCODE",
									"", 
									"", 
									"", 
									"", 
									"", 
									"", 
									"", 
									"", 
									"", 
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_profit_loss->get_AllDataLRC($PRJCODE);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_profit_loss->get_AllDataLRL($PRJCODE);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $LR_CODE 		= $dataI['LR_CODE'];
                $PERIODEY 		= (INT)date('Y', strtotime($dataI['PERIODE']));
                $PERIODEM 		= (INT)date('m', strtotime($dataI['PERIODE']));
                if($PERIODEM == 1)
                	$PERIODEM 	= "January";
                elseif($PERIODEM == 2)
                	$PERIODEM 	= "Februari";
                elseif($PERIODEM == 3)
                	$PERIODEM 	= "Maret";
                elseif($PERIODEM == 4)
                	$PERIODEM 	= "April";
                elseif($PERIODEM == 5)
                	$PERIODEM 	= "Mei";
                elseif($PERIODEM == 6)
                	$PERIODEM 	= "Juni";
                elseif($PERIODEM == 7)
                	$PERIODEM 	= "Juli";
                elseif($PERIODEM == 8)
                	$PERIODEM 	= "Agustus";
                elseif($PERIODEM == 9)
                	$PERIODEM 	= "September";
                elseif($PERIODEM == 10)
                	$PERIODEM 	= "Oktober";
                elseif($PERIODEM == 11)
                	$PERIODEM 	= "Nopember";
                elseif($PERIODEM == 12)
                	$PERIODEM 	= "Desember";

                $PRJCODE 		= $dataI['PRJCODE'];
                $PRJNAME	 	= $dataI['PRJNAME'];
                $PRJCOST	 	= $dataI['PRJCOST'];
                $PRJADD	 		= $dataI['PRJADD'];
                $BPP_MTR_REAL	= $dataI['BPP_MTR_REAL'];
                $BPP_UPH_REAL	= $dataI['BPP_UPH_REAL'];
                $BPP_ALAT_REAL	= $dataI['BPP_ALAT_REAL'];
                $BPP_SUBK_REAL	= $dataI['BPP_SUBK_REAL'];
                $BPP_BAU_REAL	= $dataI['BPP_BAU_REAL'];
                $BPP_OTH_REAL	= $dataI['BPP_OTH_REAL'];
                $BPP_I_REAL	 	= $dataI['BPP_I_REAL'];
                $BPP_MTR_REAL	= $dataI['BPP_MTR_REAL'];

                $PRJ_LROVH 		= 0;
                $PRJ_LRPPH 		= 0;
                $PRJ_LRBNK 		= 0;
                $sqlPRJ			= "SELECT PRJ_LROVH, PRJ_LRPPH, PRJ_LRBNK FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
				$resPRJ	= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ) :
					$PRJ_LROVH 	= $rowPRJ->PRJ_LROVH;
					$PRJ_LRPPH 	= $rowPRJ->PRJ_LRPPH;
					$PRJ_LRBNK 	= $rowPRJ->PRJ_LRBNK;
				endforeach;

				// RAB
					$TOT_RAB 		= 0;
	                $sqlRAB			= "SELECT SUM(BOQ_JOBCOST) AS TOT_RAB FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
					$resRAB			= $this->db->query($sqlRAB)->result();
					foreach($resRAB as $rowRAB) :
						$TOT_RAB 	= $rowRAB->TOT_RAB;
					endforeach;

				// RAP
					$TOT_RAP 		= 0;
					$TOT_ADD 		= 0;
	                $sqlRAP			= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(ADD_JOBCOST) AS TOT_ADD FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";
					$resRAP			= $this->db->query($sqlRAP)->result();
					foreach($resRAP as $rowRAP) :
						$TOT_RAP 	= $rowRAP->TOT_RAP;
						$TOT_ADD 	= $rowRAP->TOT_ADD;
					endforeach;

				// PENDAPATAN
					$PROG_INCOME 	= 0;
					$sqlLRBEF		= "SELECT SUM(PROGMC_REALA) AS PROGMC_REALA FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
					$reslLRBEF		= $this->db->query($sqlLRBEF)->result();
					foreach($reslLRBEF as $rowLRB):
						$PROG_INCOME 	= $rowLRB->PROGMC_REALA;
					endforeach;

				// BIAYA-BIAYA
                	$TOT_EXP 		= $BPP_MTR_REAL+$BPP_UPH_REAL+$BPP_ALAT_REAL+$BPP_SUBK_REAL+$BPP_BAU_REAL+$BPP_OTH_REAL+$BPP_I_REAL;

				// BEBAN-BEBAN : OVERHEAD, PPH, DAN BUNGA BANK
					$BB_BAU_PLAN	= ($PRJ_LROVH/100) * $PROG_INCOME;
					$BB_BNG_PLAN	= ($PRJ_LRBNK/100) * $PROG_INCOME;
					$BB_PPH_PLAN	= ($PRJ_LRPPH/100) * ($PROG_INCOME);
                	$TOT_OTHEXP 	= $BB_BAU_PLAN+$BB_BNG_PLAN+$BB_PPH_PLAN;

				// BIAYA-BIAYA
                	$TOT_LR 		= $PROG_INCOME - $TOT_EXP - $TOT_OTHEXP;
				
				$vwLR		= site_url('c_purchase/c_pr180d0c/pRn_P0l/?id='.$this->url_encryption_helper->encode_url($LR_CODE));
				$secPrint	= site_url('c_purchase/c_pr180d0c/printdocument/?id='.$this->url_encryption_helper->encode_url($LR_CODE));

				$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   	<label style='white-space:nowrap'>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";
				
				$output['data'][] = array("$noU.",
										  $PERIODEY,
										  $PERIODEM,
										  number_format($TOT_RAB,0),	// RAB
										  number_format($TOT_RAP,0),	// RAP
										  number_format($TOT_ADD,0),
										  number_format($PROG_INCOME,0),
										  number_format($TOT_EXP,0),
										  number_format($TOT_OTHEXP,0),
										  number_format($TOT_LR,0),
										  $secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("A = $PRJCODE",
									  	"B",
									  	"C",
									  	"D",
									  	"E",
									  	"F",
									  	"G",
									  	"G",
									  	"G",
									  	"G",
									  	"G");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
    function idxpr0f17l005vw() 
	{
		$this->load->model('m_updash/m_updash', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		//$PRJCODE	= $data1[0];
		//$PERIODE	= $data1[1];
		
		$COLPRJCODE 	= "";
		$PRJCODECOL		= $this->input->post('PRJCODE');

		/*if($PRJCODECOL != '')
		{
			$refStep	= 0;
			
			foreach ($PRJCODECOL as $PRJCODE)
			{
				$refStep	= $refStep + 1;
				if($refStep == 1)
				{
					$COLPRJCODE		= "$PRJCODE";
					$COLPRJCODE1	= "$PRJCODE";
				}
				else
				{
					$COLPRJCODE	= "$COLPRJCODE','$PRJCODE";
				}
			}
			$TOTPROJ		= $refStep;
		}
		else
		{
			$TOTPROJ		= 1;
			$COLPRJCODE		= '';
		}*/

		$TOTPROJ		= 1;
		$COLPRJCODE		= $PRJCODECOL;

		//$PRJCODE 	= $this->input->post('End_Date');
		//$STARTD 	= date('Y-m-d', strtotime($this->input->post('Start_Date')));
		$PERIODE	= date('Y-m-d', strtotime($this->input->post('End_Date')));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report';
			$data['h2_title'] 		= 'Profit and Loss Report';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Laba Rugi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Profit and Loss Report';
			}
			
			//$this->m_updash->updateLR($PRJCODE);
			//$this->m_updash->updateLRMLTPRJ($COLPRJCODE);
		
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= $COLPRJCODE;
			$data['PERIODE'] 		= $PERIODE;
			//$data['Start_Date'] 	= $STARTD;
			$data['End_Date'] 		= $PERIODE;
			$data['CFType'] 		= 1;								// 1 = Summary
			$data['viewType'] 		= $this->input->post('viewType');	// 0 = Web Viewer
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$PRJSCATEG 				= $this->session->userdata['PRJSCATEG'];
			
			/*if($TOTPROJ == 1)
			{
				$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_view_mnf', $data);
			}
			else
			{
				$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_view_cons', $data);
				//$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_view', $data);
			}*/

			if($PRJSCATEG == 1)
				$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_view_cont', $data);
			else
				$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_view_mnf', $data);

		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function idxpr0f17l005dl() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$PRJCODE	= $data1[0];
		$PERIODE	= $data1[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report';
			$data['h2_title'] 		= 'Profit and Loss Report';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Laba Rugi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Profit and Loss Report';
			}
			
			$TOTPROJ				= 1;
		
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= $PRJCODE;
			$data['PERIODE'] 		= $PERIODE;
			$data['Start_Date'] 	= $PERIODE;
			$data['End_Date'] 		= $PERIODE;
			$data['CFType'] 		= 1;								// 1 = Summary
			$data['viewType'] 		= 0;								// 0 = Web Viewer
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_viewdl', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function idxb4lsh337() 
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
			
			$this->load->view('v_gl/v_report/v_balancesheet/profit_loss_list', $data);
		}
		else
		{
			redirect('__l1y');
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
			//$data['End_Date'] 	= $this->input->post('End_Date');
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
			redirect('__l1y');
		}
    }
	
 	function claim_monitoring() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_r3p0r77l/clm_m0n1tr9/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function clm_m0n1tr9() 
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
				
			// START : GET MENU NAME
				$mnCode				= 'MN333';
				$data["MenuApp"] 	= 'MN333';
				$data["MenuCode"] 	= 'MN333';

				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			// END : GET MENU NAME

			$data['form_action'] 	= site_url('c_gl/c_r3p0r77l/clm_m0n1tr9vw/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['MenuCode']		= 'MN333';
			$this->load->view('v_gl/v_report/v_claim_monitoring/v_claim_monitoring', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function clm_m0n1tr9vw() 
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
		
			$PRJCODE				= $this->input->post('PRJCODE');
			$TOTPROJ				= 1;
			/*$TOTPROJ				= count($packageelements);
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
			}*/
		
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= "'$PRJCODE'";
			//$data['Start_Date'] 	= $this->input->post('Start_Date');
			//$data['End_Date'] 	= $this->input->post('End_Date');
			$data['WEEKTO'] 		= $this->input->post('WEEKTO');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_gl/v_report/v_claim_monitoring/v_claim_monitoring_report', $data);				
		}
		else
		{
			redirect('__l1y');
		}
    }
	
 	function l3dg3r_r3pp0r7() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_r3p0r77l/l3dg3r_r3pp0r7x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function l3dg3r_r3pp0r7x() 
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
			$data['MenuCode']	= 'MN134';
			if($LangID == 'IND')
			{
				$data['h2_title'] 	= 'Buku Besar Rinci';
				$data['h3_title'] 	= 'Laporan';
			}
			else
			{
				$data['h2_title'] 	= 'Detailed Ledger';
				$data['h3_title'] 	= 'Report';
			}
			
			$this->load->view('v_gl/v_report/v_ledger/v_ledger', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function l3dg3r_r3pp0r7vw_XX() 
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

			$TOTPROJ				= 1;
			/*$packageelements		= $_POST['packageelements'];
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
			}*/
		
			// $packageelementsCB	= $_POST['packageelementsCB'];
			// $TOTACCSEL			= count($packageelementsCB);
			/* ----------------------------------------------------------------
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
			----------------------------------------------------------------- */

			$data['sellAccount']= $this->input->post('sellAccount');
			$data['SPLCODE'] 	= $this->input->post('SPLCODE');
			
			$data['PRJCODE1'] 	= $this->input->post('PRJCODE');
			$PRJCODE1			= $this->input->post('PRJCODE');
			$CATEGREP			= $this->input->post('CATEGREP');
			
			$data['TOTPROJ'] 	= $TOTPROJ;
			$data['PRJCODECOL'] = $PRJCODE1;
			$data['Start_Date'] = $this->input->post('Start_Date');
			$data['End_Date'] 	= $this->input->post('End_Date');
			$data['CFType'] 	= $this->input->post('CFType');
			$data['viewType'] 	= $this->input->post('viewType');

			if($CATEGREP == 'TB')
			{
				if($LangID == 'IND')
					$data['h1_title'] 	= 'Neraca Saldo';
				else
					$data['h1_title'] 	= 'Trial Balance';

				$this->load->view('v_gl/v_report/v_ledger/v_ledger_report_tb', $data);
			}
			elseif($CATEGREP == 'GL')
			{
				if($LangID == 'IND')
					$data['h1_title'] 	= 'Buku Besar';
				else
					$data['h1_title'] 	= 'General Ledger';

				$this->load->view('v_gl/v_report/v_ledger/v_ledger_report_gl', $data);
			}
			elseif($CATEGREP == 'DL')
			{
				if($LangID == 'IND')
					$data['h1_title'] 	= 'Buku Besar Rinci';
				else
					$data['h1_title'] 	= 'Detailed Ledger';

				$this->load->view('v_gl/v_report/v_ledger/v_ledger_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function l3dg3r_r3pp0r7vw() 
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

			$TOTPROJ				= 1;

			$data['sellAccount']	= $this->input->post('sellAccount');
			$sellAccount 			= $this->input->post('sellAccount');
			$data['sellAccount2']	= $this->input->post('sellAccount2');
			$sellAccount2 			= $this->input->post('sellAccount2');

			$data['SPLCODE'] 	= $this->input->post('SPLCODE');
			
			$data['PRJCODE1'] 	= $this->input->post('PRJCODE');
			$PRJCODE1			= $this->input->post('PRJCODE');
			$CATEGREP			= $this->input->post('CATEGREP');
			
			$data['TOTPROJ'] 	= $TOTPROJ;
			$data['PRJCODECOL'] = $PRJCODE1;
			$data['Start_Date'] = $this->input->post('Start_Date');
			$data['End_Date'] 	= $this->input->post('End_Date');
			$data['CFType'] 	= $this->input->post('CFType');
			$data['viewType'] 	= $this->input->post('viewType');

			if($CATEGREP == 'TB')
			{
				if($LangID == 'IND')
					$data['h1_title'] 	= 'Neraca Saldo';
				else
					$data['h1_title'] 	= 'Trial Balance';

				$this->load->view('v_gl/v_report/v_ledger/v_ledger_report_tb', $data);
			}
			elseif($CATEGREP == 'GL')
			{
				if($LangID == 'IND')
					$data['h1_title'] 	= 'Buku Besar';
				else
					$data['h1_title'] 	= 'General Ledger';

				$this->load->view('v_gl/v_report/v_ledger/v_ledger_report_gl', $data);
			}
			elseif($CATEGREP == 'DL')
			{
				if($LangID == 'IND')
					$data['h1_title'] 	= 'Buku Besar Rinci';
				else
					$data['h1_title'] 	= 'Detailed Ledger';

				$this->load->view('v_gl/v_report/v_ledger/v_ledger_report', $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
    }

    function getAccNumber()
    {
    	$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $this->input->post('PRJCODE');
			if($PRJCODE[0] != 1)
			{
				$ArrPRJCODE 	= join("','", $PRJCODE);
				$addQPRJACC 	= "AND A.PRJCODE IN ('$ArrPRJCODE')";
			}
			else
			{
				$sqlPRJ 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
				$resPRJ		= $this->db->query($sqlPRJ)->result();
				foreach($resPRJ as $rowPRJ):
					$ISHO_PRJ	= $rowPRJ->PRJCODE;
				endforeach;
				//$addQPRJACC 	= "";
				$addQPRJACC 	= "AND A.PRJCODE IN ('$ISHO_PRJ')";
			}

			$sqlDataACC 	= "SELECT DISTINCT
									A.ID,
									A.Acc_ID,
									A.Account_Number,
									A.Account_Nameen as Account_Name,
									A.Account_Category,
									A.Account_Class,			
									A.currency_ID,
									A.isLast
								FROM tbl_chartaccount A
									INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
								WHERE A.Currency_id = 'IDR' $addQPRJACC AND A.isLast = 1
									Order by A.ID";
			$resDataACC 	= $this->db->query($sqlDataACC)->result();
			echo json_encode($resDataACC);

		}
		else
		{
			redirect('__l1y');
		}
    }

    function getAccNumber2()
    {
    	$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$arrID1		= $this->input->post('collID');
			$arrID 		= explode("~", $arrID1);
			$PRJCODE 	= $arrID[0];
			$ACCCODE	= $arrID[1];

			/*$ID_A 	= 0;
	    	$sAcc 	= "SELECT ID FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND  Account_Number = '$ACCCODE'";
			$rAcc 	= $this->db->query($sAcc)->result();
			foreach($rAcc as $rwAcc) :
				$ID_A = $rwAcc->ID;		
			endforeach;*/
			
			$sqlPRJ 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ):
				$ISHO_PRJ	= $rowPRJ->PRJCODE;
			endforeach;

			if($PRJCODE == 1)
				$ADDQRY 	= "AND A.PRJCODE = '$ISHO_PRJ'";
			else
				$ADDQRY 	= "AND A.PRJCODE = '$PRJCODE'";

			$sqlDataACC 	= "SELECT DISTINCT
									A.ID, 
									A.Acc_ID, 
									A.Account_Number, 
									A.Account_Nameen as Account_Name,
									A.Account_Category,
									A.Account_Class,			
									A.currency_ID,
									A.isLast
								FROM tbl_chartaccount A
									INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
								WHERE A.Currency_id = 'IDR' $ADDQRY AND A.isLast = 1 AND A.ID > $ACCCODE
									Order by A.ID";
			$resDataACC 	= $this->db->query($sqlDataACC)->result();
			echo json_encode($resDataACC);

		}
		else
		{
			redirect('__l1y');
		}
    }
	
 	function r3pN3r4c4() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_r3p0r77l/r3pN3r4c4a/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function r3pN3r4c4a() 
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
			$data['form_action'] 	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vw/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['title'] 		= $appName;
			$data['MenuCode']	= 'MN317';
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
			
			$this->load->view('v_gl/v_report/v_neraca/v_neraca', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function r3pN3r4c4av13w() 
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
		
			/*$packageelements		= $_POST['packageelements'];
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
			}*/
			
			$PRJCODE1				= $_POST['PRJCODE'];
			$TOTPROJ				= 1;
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= "'$PRJCODE1'";
			//$data['Start_Date'] 	= $this->input->post('Start_Date');
			$data['End_Date'] 		= $this->input->post('End_Date');
			$data['WEEKTO'] 		= $this->input->post('WEEKTO');
			$data['CFType'] 		= $this->input->post('CFType');
			$data['viewType'] 		= $this->input->post('viewType');
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$this->load->view('v_gl/v_report/v_neraca/v_neraca_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function idxpr0f17l005vwDB() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$PRJCODE	= $data1[0];
		$END_DATE	= $data1[1];
		$CATEGREP	= $data1[2];
		$PERIODE	= date('Y-m-d', strtotime($END_DATE));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report Detail';
			$data['h2_title'] 		= 'Profit and Loss Report';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Laba Rugi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Profit and Loss Report';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['END_DATE'] 		= $PERIODE;
			$data['CATEGREP'] 		= $CATEGREP;
			
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_viewDB', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function idxpr0f17l005vwDC() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$PRJCODE	= $data1[0];
		$PERIODE	= $data1[1];
		$CATEGREP	= $data1[2];
		$PERIODE	= date('Y-m-d', strtotime($PERIODE));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report Detail';
			$data['h2_title'] 		= 'Profit and Loss Report';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Laba Rugi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Profit and Loss Report';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PERIODE'] 		= $PERIODE;
			$data['CATEGREP'] 		= $CATEGREP;
			
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_viewDC', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
    function idxpr0f17l005vwDC_mnf() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$PRJCODE	= $data1[0];
		$PERIODE	= $data1[1];

		$repCat 	= "";
		if(isset($_GET['c4t']))
		{
			$repCat	= $_GET['c4t'];
		}

		$PERIODE	= date('Y-m-d', strtotime($PERIODE));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['h1_title'] 		= 'Profit and Loss Report Detail';
			$data['h2_title'] 		= 'Profit and Loss Report';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Laba Rugi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Profit and Loss Report';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PERIODE'] 		= $PERIODE;
			$data['CATEGREP'] 		= $repCat;
			
			$this->load->view('v_gl/v_report/v_profit_loss/profit_loss_viewDLR', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
 	function r3phpp() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_r3p0r77l/idxr3phpp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function idxr3phpp() 
	{
		$this->load->model('m_gl/m_report/m_report', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$data['form_action'] 	= site_url('c_gl/c_r3p0r77l/idxr3phppvw/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['MenuCode']		= 'MN236';
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN045';
				$data["MenuApp"] 	= 'MN045';
				$data["MenuCode"] 	= 'MN045';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['viewproject'] 		= $this->m_report->get_proj_detail()->result();
			
			$this->load->view('v_gl/v_report/v_hpp_report/v_hpp_report', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function idxr3phppvw() 
	{
		$this->load->model('m_updash/m_updash', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		$collData	= $_GET['id'];
		$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		//$PRJCODE	= $data1[0];
		//$PERIODE	= $data1[1];
		
		$COLPRJCODE 	= "";
		$PRJCODECOL		= $this->input->post('PRJCODE');

		$TOTPROJ		= 1;
		$COLPRJCODE		= $PRJCODECOL;

		$PERIODE	= date('Y-m-d', strtotime($this->input->post('End_Date')));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Laporan Harga Pokok Produksi';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Cost of Goods Sold Report';
			}
			
			$this->m_updash->updateLRMLTPRJ($COLPRJCODE);
		
			$data['TOTPROJ'] 		= $TOTPROJ;
			$data['PRJCODECOL'] 	= $COLPRJCODE;
			$data['PERIODE'] 		= $PERIODE;
			//$data['Start_Date'] 	= $STARTD;
			$data['End_Date'] 		= $PERIODE;
			$data['CFType'] 		= 1;								// 1 = Summary
			$data['viewType'] 		= $this->input->post('viewType');	// 0 = Web Viewer
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$PRJSCATEG 				= $this->session->userdata['PRJSCATEG'];

			$this->load->view('v_gl/v_report/v_hpp_report/v_hpp_report_view_mnf', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function l3dg3r_4llJrnA() // GEJ
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN070';
				$data["mnCode"] 	= 'MN070';
				$data["MenuApp"] 	= 'MN070';
				$data["jrnCat"] 	= 'JRN';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN070';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
				$url		= site_url($data["secURL"].$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				$this->load->view($data["secVIEW"], $data);
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function l3dg3r_4llJrn() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN070';
				$data["mnCode"] 	= 'MN070';
				$data["MenuApp"] 	= 'MN070';
				$data["jrnCat"] 	= 'JRN';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			// GET MENU DESC
				$BackFunct			 = "iNdJrnl_0x";
				if($this->data['LangID'] == 'IND')
					$data["TrxDesc"] = "Jurnal Umum";
				else
					$data["TrxDesc"] = "General Journal";
			
			$data['title'] 		= $appName;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= "";
				$TTR_REFDOC		= '';
				$MenuCode 		= $mnCode;
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Open list All Journal");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_report/v_jrnlist/v_jrnlist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataJRN() // OK
	{
		$this->load->model('m_gl/m_gej/m_gej', '', TRUE);
		
		$collData	= $_GET['id'];
		$arrVar 	= explode("~", $collData);
		$PRJCODE	= $arrVar[0];
		$ACCID		= $arrVar[1];
		$STARTDA	= $arrVar[2];
		$STARTD		= date('Y-m-d',strtotime(str_replace('/', '-', $arrVar[2])));
		$ENDD		= date('Y-m-d',strtotime(str_replace('/', '-', $arrVar[3])));
		
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
			
			$columns_valid 	= array("Manual_No", 
									"A.JournalH_Date", 
									"JournalH_Desc",
									"Journal_Amount",
									"CREATERNM",
									"STATDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej->get_AllDataAC($PRJCODE, $search, $STARTD, $ENDD);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej->get_AllDataAL($PRJCODE, $search, $length, $start, $order, $dir, $STARTD, $ENDD);
			
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
				$PRJCODE		= $dataI['proj_Code'];
				$isLock			= $dataI['isLock'];
				$jrnType		= $dataI['JournalType'];
				if($isLock == 0)
					$isLockD 	= "";
				else
					$isLockD 	= "<i class='fa fa-lock margin-r-5'></i>";

				/*$sqlJD 		= "SELECT A.JournalH_Code, A.Other_Desc, A.Acc_Id, A.Acc_Name, SUM(A.JournalD_Debet ) AS JournalD_Debet,
									SUM(A.JournalD_Kredit) AS JournalD_Kredit,
									B.Manual_No, B.JournalH_Date, B.JournalH_Desc, B.JournalH_Desc2, B.STATDESC, B.STATCOL, B.CREATERNM
								FROM tbl_journaldetail A
									INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
								WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalH_Code = '$JournalH_Code'
									GROUP BY A.Acc_Id, A.ISRET
									ORDER BY JournalH_Code, Base_Kredit";
				$resJD		= $this->db->query($sqlJD)->result();*/

				// SEHARUSNYA JURNAL DITMAPLKAN APA ADANYA
				$sqlJD 		= "SELECT A.JournalD_Id, A.JournalH_Code, A.Other_Desc, A.Acc_Id, A.Acc_Name, SUM(A.JournalD_Debet ) AS JournalD_Debet,
									SUM(A.JournalD_Kredit) AS JournalD_Kredit,
									B.Manual_No, B.JournalH_Date, B.JournalH_Desc, B.JournalH_Desc2, B.STATDESC, B.STATCOL, B.CREATERNM
								FROM tbl_journaldetail A
									INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
								WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalH_Code = '$JournalH_Code'
									GROUP BY A.JournalD_Id
									ORDER BY JournalH_Code, Base_Kredit";
				$resJD		= $this->db->query($sqlJD)->result();

				$JournalH_Code2 = "";
				$totD 			= 0;
				$totK 			= 0;
				foreach($resJD as $rowJD):
					$JournalD_Id		= $rowJD->JournalD_Id;
					$Manual_No			= $rowJD->Manual_No;
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;

					$JournalH_Desc		= $rowJD->JournalH_Desc;
					$JournalH_Desc2		= $rowJD->JournalH_Desc2;
					$JournalH_Date		= $rowJD->JournalH_Date;
					$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));

					$jrnCode 			= $Manual_No;
					$jrnDate 			= $JournalH_DateV;
	                $linkDel 			= "";
	                $varDel 		 	= "";
	                $isDisabled 		= 1;
					if($JournalH_Code2	!= $JournalH_Code)
					{
						//$jrnCode 		= "";
						//$jrnDate 		= "";
						if($isDisabled == 0)
							$btnDel 	= "onClick='deleteDOC(".$noU.")' title='Delete'";
						else
							$btnDel 	= " title='Delete' disabled";

						$secDelIcut = base_url().'index.php/c_gl/c_r3p0r77l/delJRN/?id=';
						$delID 		= "$secDelIcut~$JournalH_Code~$Manual_No~$jrnType~$PRJCODE";
						$linkDel 	= "<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>";
						$varDel 	= "<a href='javascript:void(null);' class='btn btn-danger btn-xs'".$btnDel.">
											<i class='fa fa-trash-o'></i>
										</a>";
					}
					
					$Acc_Id				= $rowJD->Acc_Id;
					$AccNameId			= $rowJD->Acc_Name;
					if($AccNameId == '')
					{
						$sqlAccNm		= "SELECT Account_NameId, Account_NameEn FROM tbl_chartaccount
											WHERE Account_Number = '$Acc_Id'";
						$resAccNm 		= $this->db->query($sqlAccNm)->result();
						foreach($resAccNm as $rowAccNm) :
							$AccNameId = $rowAccNm->Account_NameId;
							$AccNameEn = $rowAccNm->Account_NameEn;
						endforeach;
						
						if($this->data['LangID'] == 'IND')
							$AccNameId = $AccNameId;
						else
							$AccNameId = $AccNameEn;
					}

					$STATDESC			= $rowJD->STATDESC;
					$STATCOL			= $rowJD->STATCOL;
					$JournalD_Debet		= $rowJD->JournalD_Debet;
					$JournalD_Kredit	= $rowJD->JournalD_Kredit;
					$totD 				= $totD + $JournalD_Debet;
					$totK 				= $totK + $JournalD_Kredit;

					$CREATERNM			= $rowJD->CREATERNM;
					$empName			= wordwrap($CREATERNM, 15, "<br>", true);

					$Other_Desc			= $rowJD->Other_Desc;

					if($Other_Desc == '')
						$JournalH_Desc1	= $JournalH_Desc;
					else
						$JournalH_Desc1	= $Other_Desc;

	                $revDesc 			= "";
	                if($JournalH_Desc2 != '')
	                {
						$revDesc 	= 	"<br><strong><i class='fa  fa-bell margin-r-5'></i>Revise Note </strong>
								  		<div style='margin-left: 15px'>
									  		<p class='text-muted' style='font-style: italic;'>".$JournalH_Desc2."</p>
									  	</div>";
	                }

	                // SATRT : GET APPROVE HIST.
		                $collIMG 		= "";
		                $imgempfNm 		= "-";
		                $imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
		                $sqlAPPH 		= "SELECT AH_APPROVER FROM tbl_approve_hist WHERE AH_CODE = '$JournalH_Code'";
						$resAPPH		= $this->db->query($sqlAPPH)->result();
						foreach($resAPPH as $rowAPPH):
							$APPROVER	= $rowAPPH->AH_APPROVER;

			                $imgempfNmX 	= "username.jpg";
			                $imgempfNm 		= "-";
							$sqlIMGCrt		= "SELECT B.imgemp_filenameX, CONCAT(A.First_Name,' ', A.Last_Name) AS complName
												FROM tbl_employee A LEFT JOIN tbl_employee_img B ON B.imgemp_empid = A.Emp_ID
												WHERE A.Emp_ID = '$APPROVER'";
							$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
							foreach($resIMGCrt as $rowGIMGCrt) :
								$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
								$imgempfNm 	= cut_text2 ($rowGIMGCrt->complName, 15);
							endforeach;
							
							$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$APPROVER.'/'.$imgempfNmX);
							if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$APPROVER))
							{
								$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
							}

							$collIMG_0 		= "<img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>";
							$collIMG 		= $collIMG.$collIMG_0;
						endforeach;

						$collAPPIMG 		= $collIMG;
						if($JournalH_Code2	== $JournalH_Code)
							$collAPPIMG 	= "";
	                // END  : GET APPROVE HIST.

					$ACC_NAMEV 	= addslashes($AccNameId);
					$urlsvAcc 	= site_url('c_gl/c_r3p0r77l/upItmAcc/?id=');
					$svItmAcc 	= "$urlsvAcc~$JournalD_Id~$JournalH_Code~$PRJCODE~$Acc_Id~$ACC_NAMEV~$Other_Desc~$Manual_No";
					$updACC		= "<i class='fa fa-book margin-r-5 text-red' onClick='setAccUM(\"".$svItmAcc."\")' style='cursor: pointer' title='Ganti Akun'></i>";

					$output['data'][] 	= array("$linkDel
												<div style='white-space:nowrap'>$isLockD $jrnCode</div>",
											  	$jrnDate,
											  	"<strong>$updACC ".$AccNameId."</strong>
									  			<div class='text-muted' style='margin-left: 15px'>
											  		".$JournalH_Desc1.$revDesc."
										  		</div>",
											  	"<div style='white-space:nowrap'>".$Acc_Id."</div>",
											  	"<div style='white-space:nowrap'>".number_format($JournalD_Debet,2)."</div>",
											  	"<div style='white-space:nowrap'>".number_format($JournalD_Kredit,2)."</div>",
											  	"<div style='white-space:nowrap'>$imgempfNm</div>",
											  	$varDel);

					$JournalH_Code2	= $JournalH_Code;
				endforeach;
				
				$output['data'][] 	= array("<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='white-space:nowrap'><b>".number_format($totD,2)."</b></div>",
										  	"<div style='white-space:nowrap'><b>".number_format($totK,2)."</b></div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A = $STARTD ($STARTDA) = $ENDD",
									  	"B",
									  	"C",
									  	"D",
									  	"E",
									  	"F",
									  	"G");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function upItmAcc()
	{
		date_default_timezone_set("Asia/Jakarta");
		$this->db->trans_begin();

		$PRJCODE 	= $_POST['PRJCODE'];
		$ITM_CODE 	= $_POST['ITM_CODE'];
		$ACC_ID		= $_POST['ACC_ID'];
		$ACC_ID_UM	= $_POST['ACC_ID_UM'];
		$PROC_STAT 	= $_POST['PROC_STAT'];
		$JRN_NUM 	= $_POST['JRNNUM'];
		$JRNDESC 	= $_POST['JRNDESC'];
		$JRN_ID 	= $_POST['JRN_ID'];
		$JRN_CODE 	= $_POST['JRNCODE'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$Created 	= date('Y-m-d H:i:s');

		$REFCODE 	= "";
		$sqlJRNA	= "SELECT DISTINCT Manual_No, JournalType FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND JournalType != ''";
		$resJRNA	= $this->db->query($sqlJRNA)->result();
		foreach($resJRNA as $rowJRNA) :
			$REFCODE 	= $rowJRNA->Manual_No;
			$jrnTyp 	= $rowJRNA->JournalType;

			if($ACC_ID_UM == '')
			{
				// START : ADD TO HEADER
					$s_00 	= "INSERT INTO tbl_journalheader_revision (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Created,
									LastUpdate, KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
									Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, Manual_No, GEJ_STAT, GEJ_STAT_PD, 
									GEJ_STAT_VCASH, STATDESC, STATCOL, Reference_Number, REF_NUM, REF_CODE, Emp_ID)
								SELECT '$JRN_NUM', 'JREVISION', JournalH_Desc, '$Created', Company_ID, '$Created',
									'$Created', KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
									Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, '$JRN_CODE', GEJ_STAT, GEJ_STAT_PD, 
									GEJ_STAT_VCASH, STATDESC, STATCOL, '$JRN_NUM', '$JRN_NUM', '$REFCODE', '$DefEmp_ID' FROM tbl_journalheader
								WHERE JournalH_Code = '$JRN_NUM'";
					//******$this->db->query($s_00);
				// END : ADD TO HEADER

				$sqlJRN		= "SELECT JournalType, Base_Debet, JournalH_Date, PattNum FROM tbl_journaldetail
								WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $JRN_ID";
				$resJRN		= $this->db->query($sqlJRN)->result();
				foreach($resJRN as $rowJRN) :
					$PattNo = $rowJRN->PattNum;
					$jrnTyp = $rowJRN->JournalType;
					$jrnDeb = $rowJRN->Base_Debet;
					$jrnTgl = $rowJRN->JournalH_Date;
					$accYr	= date('Y', strtotime($jrnTgl));

					$sqlISHO 		= "SELECT Account_NameId, isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$AccName	= $rowISHO->Account_NameId;
						$AccNameB	= $rowISHO->Account_NameId;
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);

					$s_01 		= "INSERT INTO tbl_journaldetail_revision (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
										COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
									SELECT '$JRN_NUM', '$Created', 'JREVISION', '$ACC_ID', '$ACC_ID', '$AccName', proj_Code,
										proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
										COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
										ITM_UNIT, '$JRN_CODE', '$JRNDESC', Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
										FROM tbl_journaldetail
									WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $JRN_ID";
					$this->db->query($s_01);

					if($jrnTyp == 'VCASH')
					{
						$noA 		= 0;
						$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNA	= $this->db->query($sqlJRNA)->result();
						foreach($resJRNA as $rowJRNA) :
							$noA 	= $noA+1;
							$JRDID 	= $rowJRNA->JournalD_Id;
							$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDA);
						endforeach;

						$noB 		= 0;
						$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNB	= $this->db->query($sqlJRNB)->result();
						foreach($resJRNB as $rowJRNB) :
							$noB 	= $noB+1;
							$JRDID 	= $rowJRNB->JournalD_Id;
							$sUPDB 	= "UPDATE tbl_journaldetail_vcash SET PattNum = $noB WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDB);
						endforeach;
					}
					elseif($jrnTyp == 'CPRJ')
					{
						$noA 		= 0;
						$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNA	= $this->db->query($sqlJRNA)->result();
						foreach($resJRNA as $rowJRNA) :
							$noA 	= $noA+1;
							$JRDID 	= $rowJRNA->JournalD_Id;
							$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDA);
						endforeach;

						$noB 		= 0;
						$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_cprj WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNB	= $this->db->query($sqlJRNB)->result();
						foreach($resJRNB as $rowJRNB) :
							$noB 	= $noB+1;
							$JRDID 	= $rowJRNB->JournalD_Id;
							$sUPDB 	= "UPDATE tbl_journaldetail_cprj SET PattNum = $noB WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDB);
						endforeach;
					}
					elseif($jrnTyp == 'CHO-PD')
					{
						$noA 		= 0;
						$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNA	= $this->db->query($sqlJRNA)->result();
						foreach($resJRNA as $rowJRNA) :
							$noA 	= $noA+1;
							$JRDID 	= $rowJRNA->JournalD_Id;
							$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDA);
						endforeach;

						$noB 		= 0;
						$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_pd WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNB	= $this->db->query($sqlJRNB)->result();
						foreach($resJRNB as $rowJRNB) :
							$noB 	= $noB+1;
							$JRDID 	= $rowJRNB->JournalD_Id;
							$sUPDB 	= "UPDATE tbl_journaldetail_pd SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDB);
						endforeach;
					}

					$updJRN		= "UPDATE tbl_journalheader SET JournalH_Desc2 = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM'
									AND proj_Code = '$PRJCODE'";
					$this->db->query($updJRN);

					$updJRND	= "UPDATE tbl_journaldetail SET oth_reason = 'Perubahan deskripsi', Other_Desc = '$JRNDESC'
									WHERE JournalH_Code = '$JRN_NUM' AND JournalD_Id = '$JRN_ID' AND isLock = 0";
					$this->db->query($updJRND);

					if($jrnTyp == 'IR')
					{
						// VCASH
					}
					elseif($jrnTyp == 'OPN')
					{
						//
					}
					elseif($jrnTyp == 'VOC')
					{
						//
					}
					elseif($jrnTyp == 'VCASH')
					{
						$updJRNH	= "UPDATE tbl_journalheader_vcash SET JournalH_Desc2 = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM'
										AND proj_Code = '$PRJCODE'";
						$this->db->query($updJRNH);

						$updJRND	= "UPDATE tbl_journaldetail_vcash SET oth_reason = 'Ada perubahan deskripsi'
										WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
											AND isLock = 0 AND PattNum = $PattNo";
						$this->db->query($updJRND);
					}
					elseif($jrnTyp == 'CPRJ')
					{
						$updJRNH	= "UPDATE tbl_journalheader_cprj SET JournalH_Desc2 = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM'
										AND proj_Code = '$PRJCODE'";
						$this->db->query($updJRNH);

						$updJRND	= "UPDATE tbl_journaldetail_cprj SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
											oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
										WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
											AND isLock = 0 AND PattNum = $PattNo";
						$this->db->query($updJRND);
					}
					elseif($jrnTyp == 'BP')
					{
						$updJRNH	= "UPDATE tbl_bp_header SET CB_MEMO = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJRNH);
					}
					elseif($jrnTyp == 'BR')
					{
						$updJRNH	= "UPDATE tbl_br_header SET BR_MEMO = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updJRNH);
					}
					elseif($jrnTyp == 'PINBUK')
					{
						$updJRNH	= "UPDATE tbl_journalheader_pb SET JournalH_Desc2 = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM'
										AND proj_Code = '$PRJCODE'";
						$this->db->query($updJRNH);

						$updJRND	= "UPDATE tbl_journaldetail_pb SET JournalH_Desc2 = 'Ada perubahan deskripsi' 
										WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE' AND isLock = 0";
						//$this->db->query($updJRND);
					}
					elseif($jrnTyp == 'CHO-PD')
					{
						$updJRNH	= "UPDATE tbl_journalheader_pd SET JournalH_Desc2 = 'Ada perubahan deskripsi' WHERE JournalH_Code = '$JRN_NUM'
										AND proj_Code = '$PRJCODE'";
						$this->db->query($updJRNH);

						$updJRND	= "UPDATE tbl_journaldetail_pd SET JournalH_Desc2 = 'Ada perubahan deskripsi' 
										WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE' AND isLock = 0 AND PattNum = $PattNo";
						$this->db->query($updJRND);
					}
					elseif($jrnTyp == 'JRNREV')
					{
						//
					}
					elseif($jrnTyp == 'PINBUK-V')
					{
						//
					}
				endforeach;

				$LangID 	= $this->session->userdata['LangID'];
				if($LangID == 'IND')
				{
					$alert1 	= "Perubahan deskripsi sudah selesai.";
				}
				else
				{
					$alert1 	= "Change of journal description is done";
				}
			}
			else
			{
				// START : ADD TO HEADER
					$s_00 	= "INSERT INTO tbl_journalheader_revision (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Created,
									LastUpdate, KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
									Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, Manual_No, GEJ_STAT, GEJ_STAT_PD, 
									GEJ_STAT_VCASH, STATDESC, STATCOL, Reference_Number, REF_NUM, REF_CODE, Emp_ID)
								SELECT '$JRN_NUM', 'JREVISION', JournalH_Desc, '$Created', Company_ID, '$Created',
									'$Created', KursAmount_tobase, Wh_id, Reference_Type, ISPERSL, PERSL_EMPID, acc_number, proj_Code, proj_CodeHO, PRJPERIOD, 
									Journal_Amount, Journal_AmountReal, PPNH_Amount, PPHH_Amount, GJournal_Total, '$JRN_CODE', GEJ_STAT, GEJ_STAT_PD, 
									GEJ_STAT_VCASH, STATDESC, STATCOL, '$JRN_NUM', '$JRN_NUM', '$REFCODE', '$DefEmp_ID' FROM tbl_journalheader
								WHERE JournalH_Code = '$JRN_NUM'";
					$this->db->query($s_00);
				// END : ADD TO HEADER

				$PROC_STAT 	= 1;		// DITETAPKAN HANYA UNTUK UPDATE AKUN BARIS TERPILIH UNTUK DOKUMEN TERSEBUT
				if($PROC_STAT == 1)		// hanya detil akun terpilih (1 row)
				{
					if($jrnTyp == 'VCASH')
					{
						$noA 		= 0;
						$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNA	= $this->db->query($sqlJRNA)->result();
						foreach($resJRNA as $rowJRNA) :
							$noA 	= $noA+1;
							$JRDID 	= $rowJRNA->JournalD_Id;
							$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDA);
						endforeach;

						$noB 		= 0;
						$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNB	= $this->db->query($sqlJRNB)->result();
						foreach($resJRNB as $rowJRNB) :
							$noB 	= $noB+1;
							$JRDID 	= $rowJRNB->JournalD_Id;
							$sUPDB 	= "UPDATE tbl_journaldetail_vcash SET PattNum = $noB WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDB);
						endforeach;
					}
					elseif($jrnTyp == 'CPRJ')
					{
						$noA 		= 0;
						$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNA	= $this->db->query($sqlJRNA)->result();
						foreach($resJRNA as $rowJRNA) :
							$noA 	= $noA+1;
							$JRDID 	= $rowJRNA->JournalD_Id;
							$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDA);
						endforeach;

						$noB 		= 0;
						$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_cprj WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNB	= $this->db->query($sqlJRNB)->result();
						foreach($resJRNB as $rowJRNB) :
							$noB 	= $noB+1;
							$JRDID 	= $rowJRNB->JournalD_Id;
							$sUPDB 	= "UPDATE tbl_journaldetail_cprj SET PattNum = $noB WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDB);
						endforeach;
					}
					elseif($jrnTyp == 'CHO-PD')
					{
						$noA 		= 0;
						$sqlJRNA	= "SELECT JournalD_Id FROM tbl_journaldetail WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNA	= $this->db->query($sqlJRNA)->result();
						foreach($resJRNA as $rowJRNA) :
							$noA 	= $noA+1;
							$JRDID 	= $rowJRNA->JournalD_Id;
							$sUPDA 	= "UPDATE tbl_journaldetail SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDA);
						endforeach;

						$noB 		= 0;
						$sqlJRNB	= "SELECT JournalD_Id FROM tbl_journaldetail_pd WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE'";
						$resJRNB	= $this->db->query($sqlJRNB)->result();
						foreach($resJRNB as $rowJRNB) :
							$noB 	= $noB+1;
							$JRDID 	= $rowJRNB->JournalD_Id;
							$sUPDB 	= "UPDATE tbl_journaldetail_pd SET PattNum = $noA WHERE JournalD_Id = $JRDID";
							$this->db->query($sUPDB);
						endforeach;
					}

					$sqlJRN		= "SELECT JournalType, Base_Debet, JournalH_Date, PattNum FROM tbl_journaldetail
									WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $JRN_ID";
					$resJRN		= $this->db->query($sqlJRN)->result();
					foreach($resJRN as $rowJRN) :
						$PattNo = $rowJRN->PattNum;
						$jrnTyp = $rowJRN->JournalType;
						$jrnDeb = $rowJRN->Base_Debet;
						$jrnTgl = $rowJRN->JournalH_Date;
						$accYr	= date('Y', strtotime($jrnTgl));

						$sqlISHO 		= "SELECT Account_NameId, isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_UM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$AccName	= $rowISHO->Account_NameId;
							$AccNameB	= $rowISHO->Account_NameId;
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);

						$s_01 		= "INSERT INTO tbl_journaldetail_revision (JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Id_Cross, Acc_Name, proj_Code,
											proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
											COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
											ITM_UNIT, Manual_No, Other_Desc, Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, LastUpdate, PattNum, isLock)
										SELECT '$JRN_NUM', '$Created', 'JREVISION', '$ACC_ID_UM', '$ACC_ID', '$AccName', proj_Code,
											proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit,
											COA_Debet, COA_Kredit, PPN_Amount, PPH_Amount, curr_rate, isDirect, ITM_CODE, ITM_CATEG, ITM_VOLM, ITM_PRICE,
											ITM_UNIT, '$JRN_CODE', '$JRNDESC', Journal_DK, Journal_Type, GEJ_STAT, GEJ_STAT_PD, ISPERSL, '$Created', PattNum, isLock
											FROM tbl_journaldetail
										WHERE JournalH_Code = '$JRN_NUM' AND proj_Code = '$PRJCODE' AND Acc_Id = '$ACC_ID' AND JournalD_Id = $JRN_ID";
						$this->db->query($s_01);

						// START : MENGURANGI NILAI AKUN
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA1	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$jrnDeb,
														Base_Debet2 = Base_Debet2-$jrnDeb, BaseD_$accYr = BaseD_$accYr-$jrnDeb
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID'";
									$this->db->query($sqlUpdCOA1);
								}
							}
						// END : MENGURANGI NILAI AKUN

						// START : MENAMBAHKAN NILAI AKUN
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_UM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA2	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$jrnDeb,
														Base_Debet2 = Base_Debet2+$jrnDeb, BaseD_$accYr = BaseD_$accYr+$jrnDeb
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_UM'";
									$this->db->query($sqlUpdCOA2);
								}
							}
						// END : MENAMBAHKAN NILAI AKUN

						$updJRN		= "UPDATE tbl_journalheader SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
										AND proj_Code = '$PRJCODE'";
						$this->db->query($updJRN);

						$updJRND	= "UPDATE tbl_journaldetail SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
											oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
										WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
											AND isLock = 0 AND PattNum = $PattNo";
						$this->db->query($updJRND);

						if($jrnTyp == 'IR')
						{
							// VCASH
						}
						elseif($jrnTyp == 'OPN')
						{
							//
						}
						elseif($jrnTyp == 'VOC')
						{
							//
						}
						elseif($jrnTyp == 'VCASH')
						{
							$updJRNH	= "UPDATE tbl_journalheader_vcash SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
											AND proj_Code = '$PRJCODE'";
							$this->db->query($updJRNH);

							$updJRND	= "UPDATE tbl_journaldetail_vcash SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
												oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
											WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
												AND isLock = 0 AND PattNum = $PattNo";
							$this->db->query($updJRND);
						}
						elseif($jrnTyp == 'CPRJ')
						{
							$updJRNH	= "UPDATE tbl_journalheader_cprj SET JournalH_Desc2 = 'Ada perubahan akun' WHERE JournalH_Code = '$JRN_NUM'
											AND proj_Code = '$PRJCODE'";
							$this->db->query($updJRNH);

							$updJRND	= "UPDATE tbl_journaldetail_cprj SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName',
												oth_reason = 'Perubahan akun dari $ACC_ID ke $ACC_ID_UM ($JRN_CODE)'
											WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
												AND isLock = 0 AND PattNum = $PattNo";
							$this->db->query($updJRND);
						}
						elseif($jrnTyp == 'BP')
						{
							$updJRNH	= "UPDATE tbl_bp_header SET CB_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM' AND PRJCODE = '$PRJCODE'";
							$this->db->query($updJRNH);
						}
						elseif($jrnTyp == 'BR')
						{
							$updJRNH	= "UPDATE tbl_br_header SET BR_MEMO = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM' AND PRJCODE = '$PRJCODE'";
							$this->db->query($updJRNH);
						}
						elseif($jrnTyp == 'PINBUK')
						{
							$updJRNH	= "UPDATE tbl_journalheader_pb SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM'
											AND proj_Code = '$PRJCODE'";
							$this->db->query($updJRNH);

							$updJRND	= "UPDATE tbl_journaldetail_pb SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
											WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE' AND isLock = 0";
							//$this->db->query($updJRND);
						}
						elseif($jrnTyp == 'CHO-PD')
						{
							$updJRNH	= "UPDATE tbl_journalheader_pd SET JournalH_Desc2 = 'Ada perubahan akun dari $ACC_ID ke $ACC_ID_UM' WHERE JournalH_Code = '$JRN_NUM'
											AND proj_Code = '$PRJCODE'";
							$this->db->query($updJRNH);

							$updJRND	= "UPDATE tbl_journaldetail_pd SET Acc_Id = '$ACC_ID_UM', Acc_Name = '$AccName'
											WHERE JournalH_Code = '$JRN_NUM' AND Acc_Id = '$ACC_ID' AND proj_Code = '$PRJCODE'
												AND isLock = 0 AND PattNum = $PattNo";
							$this->db->query($updJRND);
						}
						elseif($jrnTyp == 'JRNREV')
						{
							//
						}
						elseif($jrnTyp == 'PINBUK-V')
						{
							//
						}
					endforeach;
				}

				$sqlUpd		= "UPDATE tbl_item SET ACC_ID_UM = '$ACC_ID_UM' WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($sqlUpd);

				$AccNameA 		= "";
				$sqlISHOA 		= "SELECT Account_NameId FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID' LIMIT 1";
				$resISHOA		= $this->db->query($sqlISHOA)->result();
				foreach($resISHOA as $rowISHOA):
					$AccNameA	= $rowISHOA->Account_NameId;
				endforeach;

				$sqlISHO 		= "SELECT Account_NameId, isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_UM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$AccName	= $rowISHO->Account_NameId;
					$AccNameB	= $rowISHO->Account_NameId;
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;

				$LangID 	= $this->session->userdata['LangID'];
				if($LangID == 'IND')
				{
					$alert1 	= "Akun $ACC_ID : $AccNameA sudah diganti ke $ACC_ID_UM : $AccNameB";
				}
				else
				{
					$alert1 	= "Item account has been changed";
				}
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JRN_CODE,
										'DOC_STAT' 		=> 3,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader_revision");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
		endforeach;

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		echo $alert1;
	}

	function delJRN()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $jrnNUM 	= $colExpl[1];
        $jrnCode 	= $colExpl[2];
        $jrnTyp 	= $colExpl[3];
        $projCode 	= $colExpl[4];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$DelCreated	= date('Y-m-d H:i:s');

        $s_00		= "UPDATE tbl_journaldetail A, tbl_journalheader B
							SET A.Manual_No = B.Manual_No, A.JournalH_Date = B.JournalH_Date
						WHERE A.JournalH_Code = B.JournalH_Code AND A.proj_Code = B.proj_Code AND A.proj_Code = '$projCode'";
        $this->db->query($s_00);

        $s_01		= "UPDATE tbl_journalheader SET JournalH_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', JournalH_Desc), GEJ_STAT = 9
        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
        $this->db->query($s_01);

        $s_01		= "UPDATE tbl_journaldetail SET Other_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', Other_Desc), GEJ_STAT = 9
        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
        $this->db->query($s_01);

        $typName 	= "";
		if($jrnTyp == 'IR')
		{
			$typName 	= "LPM / Penerimaan";
        	$s_01		= "UPDATE tbl_ir_header SET REVMEMO = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated', IR_STAT = 9
        					WHERE IR_NUM = '$jrnNUM' AND PRJCODE = '$projCode'";
        	$this->db->query($s_01);

        	// PERHATIKAN STATUS TTK, VOUCHER DAN PEMBAYARAN
		}
		elseif($jrnTyp == 'OPN')
		{
			$typName 	= "Opname";
        	$s_01		= "UPDATE tbl_opn_header SET OPNH_MEMO = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated', OPNH_STAT = 9
        					WHERE OPNH_NUM = '$jrnNUM' AND PRJCODE = '$projCode'";
        	$this->db->query($s_01);

        	// PERHATIKAN STATUS TTK, VOUCHER DAN PEMBAYARAN
		}
		elseif($jrnTyp == 'VOC')
		{
			$typName 	= "Voucher LPM/Opname";
        	$s_01		= "UPDATE tbl_pinv_header SET VOID_REASON = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated', INV_STAT = 9, INV_PAYSTAT = 'NP'
        					WHERE INV_NUM = '$jrnNUM' AND PRJCODE = '$projCode'";
        	$this->db->query($s_01);

        	// PERHATIKAN STATUS TTK, VOUCHER DAN PEMBAYARAN
		}
		elseif($jrnTyp == 'VCASH')
		{
			$typName 	= "Voucher Cash";
	        $s_01		= "UPDATE tbl_journalheader_vcash SET JournalH_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', JournalH_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);

	        $s_01		= "UPDATE tbl_journaldetail_vcash SET Other_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', Other_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);
		}
		elseif($jrnTyp == 'CPRJ')
		{
			$typName 	= "VLK (Voucher Luar Kota)";
	        $s_01		= "UPDATE tbl_journalheader_cprj SET JournalH_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', JournalH_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);

	        $s_01		= "UPDATE tbl_journaldetail_cprj SET Other_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', Other_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);
		}
		elseif($jrnTyp == 'CHO-PD')
		{
			$typName 	= "Voucher PD";
	        $s_01		= "UPDATE tbl_journalheader_pd SET JournalH_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', JournalH_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);

	        $s_01		= "UPDATE tbl_journaldetail_pd SET Other_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', Other_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);
		}
		elseif($jrnTyp == 'BP')
		{
			$typName 	= "Pembayaran";
			$s_01		= "UPDATE tbl_bp_header SET CB_MEMO = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated', VOID_REASON = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated',
								CB_STAT = 9
							WHERE JournalH_Code = '$jrnNUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_01);

        	// PERHATIKAN STATUS VOUCHER YANG BERELASI DENGAN PEMBAYARAN
		}
		elseif($jrnTyp == 'BR')
		{
			$typName 	= "Penerimaan Kas/Bank";
			$s_01		= "UPDATE tbl_br_header SET CB_MEMO = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated', VOID_REASON = 'Dihapus oleh $DefEmp_ID tgl. $DelCreated',
								BR_STAT = 9
							WHERE JournalH_Code = '$jrnNUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_01);
		}
		elseif($jrnTyp == 'PINBUK')
		{
			$typName 	= "PINBUK (Pindak Buku)";
	        $s_01		= "UPDATE tbl_journalheader_pb SET JournalH_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', JournalH_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);

	        $s_01		= "UPDATE tbl_journaldetail_pb SET Other_Desc = CONCAT('PERHATIAN !!! Dihapus oleh $DefEmp_ID tgl. $DelCreated. ', Other_Desc), GEJ_STAT = 9
	        				WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
	        $this->db->query($s_01);
		}
		elseif($jrnTyp == 'JRNREV')
		{
			//
		}
		elseif($jrnTyp == 'PINBUK-V')
		{
			//
		}

		// START : KOREKSI COA / AKUN
			$s_02 		= "SELECT JournalD_Id, JournalH_Date, Acc_Id, Base_Debet, Base_Kredit, PPN_Code, PPN_Amount, PPH_Code, PPH_Amount
							FROM tbl_journaldetail WHERE JournalH_Code = '$jrnNUM' AND proj_Code = '$projCode'";
			$r_02 		= $this->db->query($s_02)->result();
			foreach($r_02 as $rw_02):
				$JournalD_Id 	= $rw_02->JournalD_Id;
				$JournalH_Date 	= $rw_02->JournalH_Date;
				$accYr 			= date('Y', strtotime($JournalH_Date));
				$Acc_Id 		= $rw_02->Acc_Id;
				$Base_Debet 	= $rw_02->Base_Debet;
				$Base_Kredit 	= $rw_02->Base_Kredit;
				$PPN_Code 		= $rw_02->PPN_Code;
				$PPN_Amount 	= $rw_02->PPN_Amount;
				$PPH_Code 		= $rw_02->PPH_Code;
				$PPH_Amount 	= $rw_02->PPH_Amount;
				if($Base_Debet > 0)
				{
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$projCode' AND Account_Number = '$Acc_Id' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA1	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
												Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr-$Base_Debet
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
							$this->db->query($sqlUpdCOA1);
						}
					}
				}
				else
				{
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$projCode' AND Account_Number = '$Acc_Id' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA2	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
												Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr-$Base_Kredit
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
							$this->db->query($sqlUpdCOA2);
						}
					}
				}
			endforeach;
		// END : KOREKSI COA / AKUN

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Jurnal $typName No. $jrnCode telah dihapus.";
		}
		else
		{
			$alert1	= "Journal $typName No. $jrnCode has been deleted.";
		}
		echo "$alert1";
	}
}