<?php
	/* 
		* Author		= Dian Hermanto
		* Create Date	= 11 Desember 2017
		* File Name		= C_iu180c16.php
		* Location		= -
	*/

	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class C_iu180c16  extends CI_Controller
	{
	  	function __construct() // GOOD
		{
			parent::__construct(); 
			
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			$this->load->model('m_updash/m_updash', '', TRUE);
			$this->load->model('m_journal/m_journal', '', TRUE);
		
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
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_iu180c16/prjl180c17/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		
		function prjl180c17() // G
		{
			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			if ($this->session->userdata('login') == TRUE)
			{
				$idAppName	= $_GET['id'];
				$appName	= $this->url_encryption_helper->decode_url($idAppName);
				
				$LangID 	= $this->session->userdata['LangID'];
				if($LangID == 'IND')
				{
					$data["h1_title"] 	= "Penggunaan Material";
				}
				else
				{
					$data["h1_title"] 	= "Use Material";
				}
				
				$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
				$data["countPRJ"] 	= $num_rows;
				$data["MenuCode"] 	= 'MN017';
				$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
				$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
				$data["secURL"] 	= "c_inventory/c_iu180c16/gum180c16/?id=";

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

		function gum180c16() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);

			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
				
			// GET MENU DESC
				$mnCode				= 'MN189';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			if ($this->session->userdata('login') == TRUE)
			{
				// -------------------- START : SEARCHING METHOD --------------------

					$collDATA		= $_GET['id'];
					$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
					$EXP_COLLD		= explode('~', $EXP_COLLD1);
					$C_COLLD1		= count($EXP_COLLD);

					if($C_COLLD1 > 1)
					{
						$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
						$key		= $EXP_COLLD[0];
						$PRJCODE	= $EXP_COLLD[1];
						$mxLS		= $EXP_COLLD[2];
						$end		= $EXP_COLLD[3];
						$start		= 0;
					}
					else
					{
						$key		= '';
						$PRJCODE	= $EXP_COLLD1;
						$start		= 0;
						$end		= 50;
					}
					$data["url_search"] = site_url('c_inventory/c_iu180c16/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

					//$num_rows 			= $this->m_itemusage->count_all_UM($PRJCODE, $key);
					//$data["cData"] 		= $num_rows;
					//$data['vData']		= $this->m_itemusage->get_last_ten_UM($PRJCODE, $start, $end, $key)->result();
				// -------------------- END : SEARCHING METHOD --------------------

				// Secure URL
				$DefEmp_ID 				= $this->session->userdata['Emp_ID'];

				$data['title'] 			= $appName;
				$data['backURL'] 		= site_url('c_inventory/c_iu180c16/prjl180c17/?id='.$this->url_encryption_helper->encode_url($appName));

				$data["PRJCODE"] 		= $PRJCODE;
		 		$data["MenuCode"] 		= 'MN189';
				
				$this->load->view('v_inventory/v_itemusage/itemusage', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
		// -------------------- START : SEARCHING METHOD --------------------
			function f4n7_5rcH()
			{
				$gEn5rcH		= $_POST['gEn5rcH'];
				$mxLS			= $_POST['maxLimDf'];
				$mxLF			= $_POST['maxLimit'];
				$PRJCODE		= $_GET['id'];
				$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
				$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
				$url			= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($collDATA));
				redirect($url);
			}
		// -------------------- END : SEARCHING METHOD --------------------

	  	function get_AllData() // GOOD
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
				
				$columns_valid 	= array("",
										"UM_CODE", 
										"UM_DATE", 
										"JOBDESC", 
										"UM_NOTE", 
										"STATDESC",
										"ISVOID");
		
				if(!isset($columns_valid[$col])) {
					$order = null;
				} else {
					$order = $columns_valid[$col];
				}
				
				$draw			= $_REQUEST['draw'];
				$length			= $_REQUEST['length'];
				$start			= $_REQUEST['start'];
				$search			= $_REQUEST['search']["value"];
				$num_rows 		= $this->m_itemusage->get_AllDataC($PRJCODE, $search);
				$total			= $num_rows;
				$output			= array();
				$output['draw']	= $draw;
				$output['recordsTotal'] = $output['recordsFiltered']= $total;
				$output['data']	= array();
				$query 			= $this->m_itemusage->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
									
				$noU			= $start + 1;
				foreach ($query->result_array() as $dataI) 
				{
					$UM_NUM		= $dataI['UM_NUM'];
					$UM_CODE	= $dataI['UM_CODE'];
					
					$UM_DATE	= $dataI['UM_DATE'];
					$UM_DATEV	= date('d M Y', strtotime($UM_DATE));
					
					$PRJCODE	= $dataI['PRJCODE'];
					$JOBCODEID	= $dataI['JOBCODEID'];
					$JOBDESC	= $dataI['JOBDESC'];
					$UM_NOTE	= $dataI['UM_NOTE'];
					$UM_STAT	= $dataI['UM_STAT'];
					$REVMEMO	= $dataI['REVMEMO'];
					$STATDESC	= $dataI['STATDESC'];
					$STATCOL	= $dataI['STATCOL'];
					$CREATERNM	= $dataI['CREATERNM'];
					$empName	= cut_text2 ("$CREATERNM", 15);
								
					$ISVOID		=  $dataI['ISVOID'];														
					if($ISVOID == 0)
					{
						$ISVOIDDes	= 'No';
						$STATCOLV	= 'success';
					}
					elseif($ISVOID == 1)
					{
						$ISVOIDDes	= 'Yes';
						$STATCOLV	= 'danger';
					}
					
					$CollID		= "$PRJCODE~$UM_NUM";
					$secUpd		= site_url('c_inventory/c_iu180c16/update/?id='.$this->url_encryption_helper->encode_url($UM_NUM));
					$secPrint	= site_url('c_inventory/c_iu180c16/printdocument/?id='.$this->url_encryption_helper->encode_url($CollID));
					$CollID1	= "UM~$UM_NUM~$PRJCODE";
					$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID1;
	                                    
					if($UM_STAT == 1) 
					{
						$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									   <label style='white-space:nowrap'>
									   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   </a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
											<i class='fa fa-trash-o'></i>
										</a>
										</label>";
					}                                            
					else
					{
						$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									   <label style='white-space:nowrap'>
									   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   </a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
											<i class='fa fa-trash-o'></i>
										</a>
										</label>";
					}
									
					
					$output['data'][] = array("$noU.",
											  $dataI['UM_CODE'],
											  $UM_DATEV,
											  $JOBDESC,
											  $UM_NOTE,
											  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											  "<span class='label label-".$STATCOLV."' style='font-size:12px'>".$ISVOIDDes."</span>",
											  $secAction);
					$noU		= $noU + 1;
				}

				echo json_encode($output);
			// END : FOR SERVER-SIDE
		}
		
		function add() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
				
			// GET MENU DESC
				$mnCode				= 'MN189';
				$data["MenuApp"] 	= 'MN190';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			if ($this->session->userdata('login') == TRUE)
			{
				$PRJCODE	= $_GET['id'];
				$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
				
				$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
				
				$docPatternPosition 	= 'Especially';	
				$data['title'] 			= $appName;
				$data['task'] 			= 'add';
				$data['form_action']	= site_url('c_inventory/c_iu180c16/add_process');
				
				$data['backURL'] 		= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$data["MenuCode"] 		= 'MN189';
				$data["MenuCode1"] 		= 'MN190';
				$data['PRJCODE'] 		= $PRJCODE;
				$data['countSUPL']		= $this->m_itemusage->count_all_num_rowsVend();
				$data['vwSUPL'] 		= $this->m_itemusage->viewvendor()->result();
				
				$MenuCode 				= 'MN189';
				$data['vwDocPatt']		= $this->m_itemusage->getDataDocPat($MenuCode)->result();
				
				$this->load->view('v_inventory/v_itemusage/itemusage_form', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
		function add_process() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			date_default_timezone_set("Asia/Jakarta");
			
			if ($this->session->userdata('login') == TRUE)
			{
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
				$this->db->trans_begin();

				//$UM_NUM		= $this->input->post('UM_NUM');			
				$UM_CODE		= $this->input->post('UM_CODE');
				$UM_TYPE		= $this->input->post('UM_TYPE');
				$UM_USER		= $this->input->post('UM_USER');
				$SPLCODE		= $this->input->post('SPLCODE');
				$UM_NOREF		= $this->input->post('UM_NOREF');
				$UM_SPLNOTES	= addslashes($this->input->post('UM_SPLNOTES'));
				$UM_DATE		= date('Y-m-d',strtotime($this->input->post('UM_DATE')));
				$Patt_Date		= date('d',strtotime($this->input->post('UM_DATE')));
				$Patt_Month		= date('m',strtotime($this->input->post('UM_DATE')));
				$Patt_Year		= date('Y',strtotime($this->input->post('UM_DATE')));
				$PRJCODE		= $this->input->post('PRJCODE');
				
				$JCODEID		= $this->input->post('JCODEID');

				/*if($JCODEID != '')
				{
					$refStep	= 0;
					foreach ($JCODEID as $UM_REFNO)
					{
						$refStep	= $refStep + 1;
						if($refStep == 1)
						{
							$COL_JCODEID	= "$UM_REFNO";
						}
						else
						{
							$COL_JCODEID	= "$COL_JCODEID$UM_REFNO";
						}
					}
				}
				else
				{
					$COL_JCODEID	= '';
				}*/
				$COL_JCODEID 	= $JCODEID;
				
				// START - PEMBENTUKAN GENERATE CODE
					$this->load->model('m_projectlist/m_projectlist', '', TRUE);
					$Pattern_Code	= "XX";
					$MenuCode 		= 'MN189';
					$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
					foreach($vwDocPatt as $row) :
						$Pattern_Code = $row->Pattern_Code;
					endforeach;
					
					$PRJCODE 		= $this->input->post('PRJCODE');
					$TRXTIME1		= date('ymdHis');
					$UM_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
				// END - PEMBENTUKAN GENERATE CODE
				
				$UM_STAT		= $this->input->post('UM_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
				$UM_NOTE		= addslashes($this->input->post('UM_NOTE'));
				$WH_CODE		= $this->input->post('WH_CODE');
				//$WH_CODE		= $PRJCODE;
				$UM_CREATED		= date('Y-m-d H:i:s');
				$UM_CREATER		= $DefEmp_ID;
				
				$insUM = array('UM_NUM' 		=> $UM_NUM,
								'UM_CODE' 		=> $UM_CODE,
								'UM_TYPE' 		=> $UM_TYPE,
								'UM_USER' 		=> $UM_USER,
								'SPLCODE' 		=> $SPLCODE,
								'UM_NOREF' 		=> $UM_NOREF,
								'UM_SPLNOTES' 	=> $UM_SPLNOTES,
								'UM_DATE'		=> $UM_DATE,
								'PRJCODE'		=> $PRJCODE,
								'JOBCODEID'		=> $COL_JCODEID,
								'UM_STAT'		=> $UM_STAT,
								'UM_NOTE'		=> $UM_NOTE,
								'WH_CODE'		=> $WH_CODE,
								'UM_CREATED'	=> $UM_CREATED,
								'UM_CREATER'	=> $UM_CREATER,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Month'	=> $Patt_Month,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Number'	=> $this->input->post('Patt_Number'));
				$this->m_itemusage->add($insUM);

				foreach($_POST['data'] as $d)
				{
					$ITM_CODE		= $d['ITM_CODE'];
					$getITMD 		= $this->m_updash->get_itmGroup($PRJCODE, $ITM_CODE);
					$collDATA		= explode("~", $getITMD);
					$ITM_GROUP		= $collDATA[0];
					$ITM_TYPE		= $collDATA[1];

					// UNTUK HARGA CARI DARI RATA2 HARGA (TOTAL IN / VOL IN)
					$ITM_PRICE 		= $this->m_updash->get_itmAVG($PRJCODE, $ITM_CODE);

					$d['UM_NUM']	= $UM_NUM;
					$d['PRJCODE']	= $PRJCODE;
					$d['ITM_GROUP']	= $ITM_GROUP;
					$d['ITM_TYPE']	= $ITM_TYPE;
					$d['ITM_PRICE']	= $ITM_PRICE;
					$ITM_QTY		= $d['ITM_QTY'];
					$d['ITM_TOTAL']	= $ITM_QTY * $ITM_PRICE;
					$d['JOBPARENT']	= $COL_JCODEID;
					$this->db->insert('tbl_um_detail',$d);
				}
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('UM_STAT');			// IF "ADD" CONDITION ALWAYS = IR_STAT
					$parameters 	= array('DOC_CODE' 		=> $UM_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "UM",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_um_header",	// TABLE NAME
											'KEY_NAME'		=> "UM_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "UM_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $UM_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_UM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
											'FIELD_NM_N'	=> "TOT_UM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_C'	=> "TOT_UM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_A'	=> "TOT_UM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_R'	=> "TOT_UM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_RJ'	=> "TOT_UM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_CL'	=> "TOT_UM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
											'DOC_CODE' 		=> $UM_NUM,
											'DOC_STAT' 		=> $UM_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_um_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
				
				$url			= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
		function update() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
				
			// GET MENU DESC
				$mnCode				= 'MN189';
				$data["MenuApp"] 	= 'MN190';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			if ($this->session->userdata('login') == TRUE)
			{
				$UM_NUM	= $_GET['id'];
				$UM_NUM	= $this->url_encryption_helper->decode_url($UM_NUM);
					
				$getrr 					= $this->m_itemusage->get_um_by_number($UM_NUM)->row();
				$PRJCODE				= $getrr->PRJCODE;
				$data["MenuCode"] 		= 'MN189';
				$data["MenuCode1"] 		= 'MN190';
				// Secure URL
				$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
				
				$docPatternPosition 	= 'Especially';	
				$data['title'] 			= $appName;
				$data['task'] 			= 'edit';
				$data['h2_title']		= 'Edit Use Material';
				$data['h3_title']		= 'inventory';
				$data['form_action']	= site_url('c_inventory/c_iu180c16/update_process');
				$data['backURL'] 		= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$data['PRJCODE'] 		= $PRJCODE;

				$data['default']['UM_NUM'] 		= $getrr->UM_NUM;
				$data['default']['UM_CODE'] 	= $getrr->UM_CODE;
				$data['default']['UM_TYPE'] 	= $getrr->UM_TYPE;
				$data['default']['UM_USER'] 	= $getrr->UM_USER;
				$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
				$data['default']['UM_NOREF'] 	= $getrr->UM_NOREF;
				$data['default']['UM_SPLNOTES'] = $getrr->UM_SPLNOTES;
				$data['default']['UM_DATE'] 	= $getrr->UM_DATE;
				$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
				$data['default']['JOBCODEID'] 	= $getrr->JOBCODEID;
				$data['default']['UM_STAT'] 	= $getrr->UM_STAT;
				$data['default']['UM_NOTE'] 	= $getrr->UM_NOTE;
				$data['default']['UM_NOTE2'] 	= $getrr->UM_NOTE2;
				$data['default']['REVMEMO']		= $getrr->REVMEMO;
				$data['default']['WH_CODE']		= $getrr->WH_CODE;
				$data['default']['Patt_Date'] 	= $getrr->Patt_Date;
				$data['default']['Patt_Month'] 	= $getrr->Patt_Month;
				$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
				$data['default']['Patt_Number'] = $getrr->Patt_Number;
				
				$this->load->view('v_inventory/v_itemusage/itemusage_form', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
		function update_process() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$comp_init 	= $this->session->userdata('comp_init');
			
			date_default_timezone_set("Asia/Jakarta");
			
			if ($this->session->userdata('login') == TRUE)
			{
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
				$this->db->trans_begin();
				
				$UM_NUM			= $this->input->post('UM_NUM');			
				$UM_CODE		= $this->input->post('UM_CODE');
				$UM_TYPE		= $this->input->post('UM_TYPE');
				$UM_USER		= $this->input->post('UM_USER');
				$SPLCODE		= $this->input->post('SPLCODE');
				$UM_NOREF		= $this->input->post('UM_NOREF');
				$UM_SPLNOTES	= addslashes($this->input->post('UM_SPLNOTES'));
				$UM_DATE		= date('Y-m-d',strtotime($this->input->post('UM_DATE')));
				$Patt_Date		= date('d',strtotime($this->input->post('UM_DATE')));
				$Patt_Month		= date('m',strtotime($this->input->post('UM_DATE')));
				$Patt_Year		= date('Y',strtotime($this->input->post('UM_DATE')));
				$accYr			= date('Y', strtotime($UM_DATE));
				$PRJCODE		= $this->input->post('PRJCODE');
				
				$JCODEID		= $this->input->post('JCODEID');
				
				/*if($JCODEID != '')
				{
					$refStep	= 0;
					foreach ($JCODEID as $UM_REFNO)
					{
						$refStep	= $refStep + 1;
						if($refStep == 1)
						{
							$COL_JCODEID	= "$UM_REFNO";
						}
						else
						{
							$COL_JCODEID	= "$COL_JCODEID$UM_REFNO";
						}
					}
				}
				else
				{
					$COL_JCODEID	= '';
				}*/
				$COL_JCODEID 	= $JCODEID;

				$UM_STAT		= $this->input->post('UM_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
				$UM_NOTE		= addslashes($this->input->post('UM_NOTE'));
				$WH_CODE		= $this->input->post('WH_CODE');

				//$WH_CODE		= $PRJCODE;
				$UM_CREATED		= date('Y-m-d H:i:s');
				$UM_CREATER		= $DefEmp_ID;
				
				if($UM_STAT == 9)
				{
					// 1. UPDATE STATUS
						$updUM = array('UM_STAT'	=> $UM_STAT);
						$this->m_itemusage->updateUM($UM_NUM, $updUM);
				
						$paramSTAT 	= array('JournalHCode' 	=> $UM_NUM);
						$this->m_updash->updSTATJD($paramSTAT);

						$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 9, STATCOL = 'danger', STATDESC = 'Void' WHERE JournalH_Code = '$UM_NUM'";
						$this->db->query($upJH);

						$upJD	= "UPDATE tbl_journaldetail SET GEJ_STAT = 9, isVoid = 1 WHERE JournalH_Code = '$UM_NUM'";
						$this->db->query($upJD);

					// 2. MEMBUAT JURNAL PEMBALIK
						$sqlDET 	= "SELECT JournalH_Date, Acc_Id, proj_Code, JOBCODEID, ITM_CODE, ITM_GROUP, ITM_VOLM, ITM_PRICE,
											Base_Debet, Base_Kredit, Journal_DK
										FROM tbl_journaldetail WHERE JournalH_Code = '$UM_NUM'";
						$resDET 	= $this->db->query($sqlDET)->result();
						foreach($resDET as $rowDET) :
							$JournalH_Date 	= $rowDET->JournalH_Date;
							$ACC_NUM 		= $rowDET->Acc_Id;
							$PRJCODE 		= $rowDET->proj_Code;
							$JOBCODEID 		= $rowDET->JOBCODEID;
							$ITM_CODE 		= $rowDET->ITM_CODE;
							$ITM_GROUP 		= $rowDET->ITM_GROUP;
							$ITM_VOLM 		= $rowDET->ITM_VOLM;
							$ITM_PRICE 		= $rowDET->ITM_PRICE;
							$Base_Debet 	= $rowDET->Base_Debet;
							$Base_Kredit 	= $rowDET->Base_Kredit;
							$Journal_DK 	= $rowDET->Journal_DK;

							$ITM_TYPE 	= $this->m_updash->get_itmType($PRJCODE, $ITM_CODE);
							if($ITM_TYPE == 0)
								$ITM_TYPE	= 1;

							$PRJCODE		= $PRJCODE;
							$JOURN_DATE		= $UM_DATE;
							$ITM_GROUP		= $ITM_GROUP;
							$ITM_TYPE		= $ITM_TYPE;
							$ITM_QTY 		= $ITM_VOLM;
							if($ITM_QTY == 0 || $ITM_QTY == '')
								$ITM_QTY	= 1;

							if($Journal_DK == 'D')
							{
								$JOURN_VAL	= $Base_Debet;

								$parameters = array('PRJCODE' 		=> $PRJCODE,
													'JOURN_DATE' 	=> $JOURN_DATE,
													'ITM_GROUP' 	=> $ITM_GROUP,
													'ITM_TYPE' 		=> $ITM_TYPE,
													'ITM_QTY' 		=> $ITM_QTY,
													'JOURN_VAL' 	=> $JOURN_VAL);
								//$this->m_journal->updateLR_VUM($parameters);
								// TIDAK DIPERLUKAN KARENA SUDAH DILAKUKAN PADA FUNCTION updateITM_PlusV()
							}
							else
							{
								$JOURN_VAL	= $Base_Kredit;
							}

							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
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
									if($Journal_DK == 'D')
									{
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet,
															Base_Debet2 = Base_Debet2-$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									}
									else
									{
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit,
															Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Debet
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									}
									$this->db->query($sqlUpdCOA);
								}
							}

							// START : UPDATE STOCK
								if($Journal_DK == 'D')
								{
									$parameters1 = array('PRJCODE' 	=> $PRJCODE,
														'WH_CODE'	=> $WH_CODE,
														'JOBCODEID'	=> $JOBCODEID,
														'UM_NUM' 	=> $UM_NUM,
														'UM_CODE' 	=> $UM_CODE,
														'ITM_CODE' 	=> $ITM_CODE,
														'ITM_GROUP'	=> $ITM_GROUP,
														'ITM_QTY' 	=> $ITM_QTY,
														'ITM_PRICE' => $ITM_PRICE,
														'JD_Date'	=> $JournalH_Date,
														'ITM_TYPE'	=> $ITM_TYPE,
														'JOURN_VAL'	=> $JOURN_VAL);
									$this->m_itemusage->updateITM_PlusV($parameters1);
								}
							// END : UPDATE STOCK
						endforeach;

					// START : UPDATE TO DOC. COUNT
						$parameters 	= array('DOC_CODE' 		=> $UM_NUM,
												'PRJCODE' 		=> $PRJCODE,
												'DOC_TYPE'		=> "UM",
												'DOC_QTY' 		=> "DOC_UMQ",
												'DOC_VAL' 		=> "DOC_UMV",
												'DOC_STAT' 		=> 'VOID');
						$this->m_updash->updateDocC($parameters);
					// END : UPDATE TO DOC. COUNT
				}
				else
				{
					$updUM 	= array('UM_CODE' 		=> $UM_CODE,
									'UM_TYPE' 		=> $UM_TYPE,
									'UM_USER' 		=> $UM_USER,
									'SPLCODE' 		=> $SPLCODE,
									'UM_NOREF' 		=> $UM_NOREF,
									'UM_SPLNOTES' 	=> $UM_SPLNOTES,
									'UM_DATE'		=> $UM_DATE,
									'PRJCODE'		=> $PRJCODE,
									'JOBCODEID'		=> $COL_JCODEID,
									'UM_STAT'		=> $UM_STAT,
									'UM_NOTE'		=> $UM_NOTE,
									'WH_CODE'		=> $WH_CODE);
					$this->m_itemusage->updateUM($UM_NUM, $updUM);
					
					$this->m_itemusage->deleteUMDetail($UM_NUM);

					foreach($_POST['data'] as $d)
					{
						$ITM_CODE		= $d['ITM_CODE'];
						$getITMD 		= $this->m_updash->get_itmGroup($PRJCODE, $ITM_CODE);
						$collDATA		= explode("~", $getITMD);
						$ITM_GROUP		= $collDATA[0];
						$ITM_TYPE		= $collDATA[1];

						// UNTUK HARGA CARI DARI RATA2 HARGA (TOTAL IN / VOL IN)
						$ITM_PRICE 		= $this->m_updash->get_itmAVG($PRJCODE, $ITM_CODE);

						$d['UM_NUM']	= $UM_NUM;
						$d['PRJCODE']	= $PRJCODE;
						$d['ITM_GROUP']	= $ITM_GROUP;
						$d['ITM_TYPE']	= $ITM_TYPE;
						$d['ITM_PRICE']	= $ITM_PRICE;
						$ITM_QTY		= $d['ITM_QTY'];
						$d['ITM_TOTAL']	= $ITM_QTY * $ITM_PRICE;
						$d['JOBPARENT']	= $COL_JCODEID;
						$this->db->insert('tbl_um_detail',$d);
					}
				}

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
					$parameters 	= array('DOC_CODE' 		=> $UM_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "UM",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_um_header",	// TABLE NAME
											'KEY_NAME'		=> "UM_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "UM_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $UM_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_UM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
											'FIELD_NM_N'	=> "TOT_UM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_C'	=> "TOT_UM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_A'	=> "TOT_UM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_R'	=> "TOT_UM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_RJ'	=> "TOT_UM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_CL'	=> "TOT_UM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
											'DOC_CODE' 		=> $UM_NUM,
											'DOC_STAT' 		=> $UM_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_um_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}

				$url			= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
		function popupallpo() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
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
				
				$data['title'] 		= $appName;
				$data['pageFrom']	= 'PO';
				$data['PRJCODE']	= $PRJCODE;
						
				$this->load->view('v_inventory/v_itemusage/itemusage_sel_po', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
		function genCode() // G
		{
			$PRJCODEX	= $this->input->post('PRJCODEX');
			$PattCode	= $this->input->post('Pattern_Code');
			$PattLength	= $this->input->post('Pattern_Length');
			$useYear	= $this->input->post('useYear');
			$useMonth	= $this->input->post('useMonth');
			$useDate	= $this->input->post('useDate');
			$ISDIRECT	= $this->input->post('ISDIRECT');
			
			$IRDate		= date('Y-m-d',strtotime($this->input->post('IRDate')));
			$year		= date('Y',strtotime($this->input->post('IRDate')));
			$month 		= (int)date('m',strtotime($this->input->post('IRDate')));
			$date 		= (int)date('d',strtotime($this->input->post('IRDate')));
			
			$this->db->where('Patt_Year', $year);
			$myCount = $this->db->count_all('tbl_UM_header');
			
			$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_um_header
						WHERE Patt_Year = $year AND PRJCODE = '$PRJCODEX'";
			$result = $this->db->query($sql)->result();
			if($myCount>0)
			{
				$myMax	= 0;
				foreach($result as $row) :
					$myMax = $row->maxNumber;
					$myMax = $myMax+1;
				endforeach;
			}	
			else
			{
				$myMax = 1;
			}
			
			$thisMonth = $month;
		
			$lenMonth = strlen($thisMonth);
			if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
			$pattMonth = $nolMonth.$thisMonth;
			
			$thisDate = $date;
			$lenDate = strlen($thisDate);
			if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
			$pattDate = $nolDate.$thisDate;
			
			// group year, month and date
			$year = substr($year,2,2);
			if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
				$groupPattern = "$year$pattMonth$pattDate";
			elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
				$groupPattern = "$year$pattMonth";
			elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
				$groupPattern = "$year$pattDate";
			elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
				$groupPattern = "$pattMonth$pattDate";
			elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
				$groupPattern = "$year";
			elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
				$groupPattern = "$pattMonth";
			elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
				$groupPattern = "$pattDate";
			elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
				$groupPattern = "";
				
			$lastPatternNumb = $myMax;
			$lastPatternNumb1 = $myMax;
			$len = strlen($lastPatternNumb);
			
			if($PattLength==2)
			{
				if($len==1) $nol="0";
			}
			elseif($PattLength==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($PattLength==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($PattLength==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($PattLength==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($PattLength==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			$lastPatternNumb	= $nol.$lastPatternNumb;
			if($ISDIRECT == 1)
				$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb"."-D";
			else
				$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb";
				
			echo "$DocNumber~$lastPatternNumb";
		}
		
		function pall180c16itm() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			if ($this->session->userdata('login') == TRUE)
			{			
				$sqlApp 		= "SELECT * FROM tappname";
				$resultaApp = $this->db->query($sqlApp)->result();
				foreach($resultaApp as $therow) :
					$appName = $therow->app_name;		
				endforeach;
				$PRJCODE	= $_GET['id'];
				$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
				
				$data['title'] 			= $appName;
				$data['h2_title'] 		= 'List Item';
				$data['h2_title'] 		= 'item receipt';
				$data['PRJCODE'] 		= $PRJCODE;
				
				$data['countAllItem']	= $this->m_itemusage->count_allItem($PRJCODE);
				$data['vwAllItem'] 		= $this->m_itemusage->viewAllItem($PRJCODE)->result();
						
				$this->load->view('v_inventory/v_itemusage/item_list_selectitem', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}
		
	    function in180c18b0x() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_inventory/c_iu180c16/uM7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		
		function uM7_l5t_x1() // G
		{
			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			if ($this->session->userdata('login') == TRUE)
			{
				$idAppName	= $_GET['id'];
				$appName	= $this->url_encryption_helper->decode_url($idAppName);
				
				// GET MENU DESC
					$mnCode				= 'MN190';
					$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
					$getMN				= $this->m_updash->get_menunm($mnCode)->row();
					if($this->data['LangID'] == 'IND')
						$data["mnName"] = $getMN->menu_name_IND;
					else
						$data["mnName"] = $getMN->menu_name_ENG;
				
				$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
				$data["countPRJ"] 	= $num_rows;
				$data["MenuCode"] 	= 'MN017';	 
				$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
				$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
				$data["secURL"] 	= "c_inventory/c_iu180c16/in180c18b0xx/?id=";
				
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

	    function in180c18b0xx() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
				
			// GET MENU DESC
				$mnCode				= 'MN190';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			if ($this->session->userdata('login') == TRUE)
			{
				$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
				
				// -------------------- START : SEARCHING METHOD --------------------
					
					$collDATA		= $_GET['id'];
					$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);	
					$EXP_COLLD		= explode('~', $EXP_COLLD1);	
					$C_COLLD1		= count($EXP_COLLD);
					
					if($C_COLLD1 > 1)
					{
						$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
						$key		= $EXP_COLLD[0];
						$PRJCODE	= $EXP_COLLD[1];
						$mxLS		= $EXP_COLLD[2];
						$end		= $EXP_COLLD[3];
						$start		= 0;
					}
					else
					{
						$key		= '';
						$PRJCODE	= $EXP_COLLD1;
						$start		= 0;
						$end		= 50;
					}
					$data["url_search"]= site_url('c_inventory/c_ir180c15/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				
					//$num_rows 			= $this->m_itemusage->count_all_UM_OUT($PRJCODE, $key, $DefEmp_ID);
					//$data["cData"] 		= $num_rows;	 
					//$data['vData']		= $this->m_itemusage->get_all_UM_OUT($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
				// -------------------- END : SEARCHING METHOD --------------------
				
				$DefEmp_ID 			= $this->session->userdata['Emp_ID'];

				$data['backURL'] 	= site_url('c_inventory/c_iu180c16/uM7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));

				$data["PRJCODE"] 	= $PRJCODE;
				$data['h2_title'] 	= 'Use Material';
				$data['h3_title'] 	= 'approval';
		 		$data["MenuCode"] 	= 'MN190';
				
				$this->load->view('v_inventory/v_itemusage/inb_itemusage', $data);
			}
			else
			{
				redirect('__l1y');
			}
	    }
		
		// -------------------- START : SEARCHING METHOD --------------------
			function f4n7_5rcH1nB()
			{
				$gEn5rcH		= $_POST['gEn5rcH'];
				$mxLS			= $_POST['maxLimDf'];
				$mxLF			= $_POST['maxLimit'];
				$PRJCODE		= $_GET['id'];
				$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
				$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
				$url			= site_url('c_inventory/c_iu180c16/in180c18b0xx/?id='.$this->url_encryption_helper->encode_url($collDATA));
				redirect($url);
			}
		// -------------------- END : SEARCHING METHOD --------------------

	  	function get_AllData_1n2() // GOOD
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
				
				$columns_valid 	= array("",
										"UM_CODE", 
										"UM_DATE", 
										"JOBDESC", 
										"UM_NOTE", 
										"STATDESC",
										"ISVOID");
		
				if(!isset($columns_valid[$col])) {
					$order = null;
				} else {
					$order = $columns_valid[$col];
				}
				
				$draw			= $_REQUEST['draw'];
				$length			= $_REQUEST['length'];
				$start			= $_REQUEST['start'];
				$search			= $_REQUEST['search']["value"];
				$num_rows 		= $this->m_itemusage->get_AllDataC_1n2($PRJCODE, $search);
				$total			= $num_rows;
				$output			= array();
				$output['draw']	= $draw;
				$output['recordsTotal'] = $output['recordsFiltered']= $total;
				$output['data']	= array();
				$query 			= $this->m_itemusage->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
									
				$noU			= $start + 1;
				foreach ($query->result_array() as $dataI) 
				{
					$UM_NUM		= $dataI['UM_NUM'];
					$UM_CODE	= $dataI['UM_CODE'];
					
					$UM_DATE	= $dataI['UM_DATE'];
					$UM_DATEV	= date('d M Y', strtotime($UM_DATE));
					
					$PRJCODE	= $dataI['PRJCODE'];
					$JOBCODEID	= $dataI['JOBCODEID'];
					$JOBDESC	= $dataI['JOBDESC'];
					$UM_NOTE	= $dataI['UM_NOTE'];
					$UM_STAT	= $dataI['UM_STAT'];
					$REVMEMO	= $dataI['REVMEMO'];
					$STATDESC	= $dataI['STATDESC'];
					$STATCOL	= $dataI['STATCOL'];
					$CREATERNM	= $dataI['CREATERNM'];
					$empName	= cut_text2 ("$CREATERNM", 15);
								
					$ISVOID		=  $dataI['ISVOID'];														
					if($ISVOID == 0)
					{
						$ISVOIDDes	= 'No';
						$STATCOLV	= 'success';
					}
					elseif($ISVOID == 1)
					{
						$ISVOIDDes	= 'Yes';
						$STATCOLV	= 'danger';
					}
					
					$CollID		= "$PRJCODE~$UM_NUM";
					$secUpd		= site_url('c_inventory/c_iu180c16/ui180c18box/?id='.$this->url_encryption_helper->encode_url($CollID));
					$secPrint	= site_url('c_inventory/c_iu180c16/printdocument/?id='.$this->url_encryption_helper->encode_url($CollID));
					$CollID1	= "UM~$UM_NUM~$PRJCODE";
					$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID1;
	                                    
					if($UM_STAT == 1) 
					{
						$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									   <label style='white-space:nowrap'>
									   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   </a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
											<i class='fa fa-trash-o'></i>
										</a>
										</label>";
					}                                            
					else
					{
						$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									   <label style='white-space:nowrap'>
									   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   </a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
											<i class='fa fa-trash-o'></i>
										</a>
										</label>";
					}
									
					
					$output['data'][] = array("$noU.",
											  $dataI['UM_CODE'],
											  $UM_DATEV,
											  $JOBDESC,
											  $UM_NOTE,
											  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											  "<span class='label label-".$STATCOLV."' style='font-size:12px'>".$ISVOIDDes."</span>",
											  $secAction);
					$noU		= $noU + 1;
				}

				echo json_encode($output);
			// END : FOR SERVER-SIDE
		}
		
		function ui180c18box() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
				
			// GET MENU DESC
				$mnCode				= 'MN190';
				$data["MenuCode"] 	= 'MN190';
				$data["MenuApp"] 	= 'MN190';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			if ($this->session->userdata('login') == TRUE)
			{
				$COLLDATA	= $_GET['id'];
				$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
				$EXTRACTCOL	= explode("~", $COLLDATA);
				$PRJCODE	= $EXTRACTCOL[0];
				$UM_NUM		= $EXTRACTCOL[1];
					
				$getrr 					= $this->m_itemusage->get_um_by_number($UM_NUM)->row();
				$PRJCODE				= $getrr->PRJCODE;
				$data["MenuCode"] 		= 'MN190';
				$data["MenuCode1"] 		= 'MN190';
				// Secure URL
				$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
				
				$docPatternPosition 	= 'Especially';	
				$data['title'] 			= $appName;
				$data['task'] 			= 'edit';
				$data['h2_title']		= 'Edit Use Material';
				$data['h3_title']		= 'inventory';
				$data['form_action']	= site_url('c_inventory/c_iu180c16/update_inbox_process');
				$data['backURL'] 		= site_url('c_inventory/c_iu180c16/in180c18b0xx/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				$data['PRJCODE'] 		= $PRJCODE;

				$data['default']['UM_NUM'] 		= $getrr->UM_NUM;
				$data['default']['UM_CODE'] 	= $getrr->UM_CODE;
				$data['default']['UM_TYPE'] 	= $getrr->UM_TYPE;
				$data['default']['UM_USER'] 	= $getrr->UM_USER;
				$data['default']['SPLCODE'] 	= $getrr->SPLCODE;
				$data['default']['UM_NOREF'] 	= $getrr->UM_NOREF;
				$data['default']['UM_SPLNOTES'] = $getrr->UM_SPLNOTES;
				$data['default']['UM_DATE'] 	= $getrr->UM_DATE;
				$UM_DATE 						= $getrr->UM_DATE;
				$data['default']['PRJCODE'] 	= $getrr->PRJCODE;
				$data['default']['JOBCODEID'] 	= $getrr->JOBCODEID;
				$data['default']['UM_STAT'] 	= $getrr->UM_STAT;
				$data['default']['UM_NOTE'] 	= $getrr->UM_NOTE;
				$data['default']['UM_NOTE2'] 	= $getrr->UM_NOTE2;
				$data['default']['REVMEMO']		= $getrr->REVMEMO;
				$data['default']['WH_CODE']		= $getrr->WH_CODE;
				$data['default']['Patt_Date'] 	= $getrr->Patt_Date;
				$data['default']['Patt_Month'] 	= $getrr->Patt_Month;
				$data['default']['Patt_Year'] 	= $getrr->Patt_Year;
				$data['default']['Patt_Number'] = $getrr->Patt_Number;

				// START : SETTING L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $UM_DATE;
					$PERIODM	= date('m', strtotime($PERIODED));
					$PERIODY	= date('Y', strtotime($PERIODED));
					$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
									AND YEAR(PERIODE) = '$PERIODY'";
					$resLR		= $this->db->count_all($getLR);
					if($resLR	== 0)
					{
						$this->m_updash->createLR($PRJCODE, $PERIODED);
					}
				// END : SETTING L/R
				
				$this->load->view('v_inventory/v_itemusage/inb_itemusage_form', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}

	  	function printdocument()
	  	{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);

			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
			endforeach;

			if ($this->session->userdata('login') == TRUE)
			{
			$callData	= $_GET['id'];
			$callData	= $this->url_encryption_helper->decode_url($callData);

			$explDATA	= explode('~', $callData);
			$PRJCODE	= $explDATA[0];
			$UM_NUM	= $explDATA[1];

			$data['PRJCODE'] 	= $PRJCODE;
			$data['UM_NUM'] 	= $UM_NUM;

			$this->load->view('v_inventory/v_itemusage/print_itemuseage', $data);
			}
			else
			{
			redirect('__l1y');
			}
	  	}

		function update_inbox_process() // G
		{
			$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$comp_init 	= $this->session->userdata('comp_init');
			
			date_default_timezone_set("Asia/Jakarta");
			$APPDATE 				= date('Y-m-d H:i:s');
				
			if ($this->session->userdata('login') == TRUE)
			{
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
				$this->db->trans_begin();
						
				$UM_NUM			= $this->input->post('UM_NUM');			
				$UM_CODE		= $this->input->post('UM_CODE');
				$UM_TYPE		= $this->input->post('UM_TYPE');
				$UM_USER		= $this->input->post('UM_USER');
				$SPLCODE		= $this->input->post('SPLCODE');
				$UM_NOREF		= $this->input->post('UM_NOREF');
				$UM_SPLNOTES	= addslashes($this->input->post('UM_SPLNOTES'));
				$UM_DATE		= date('Y-m-d',strtotime($this->input->post('UM_DATE')));
				$Patt_Date		= date('d',strtotime($this->input->post('UM_DATE')));
				$Patt_Month		= date('m',strtotime($this->input->post('UM_DATE')));
				$Patt_Year		= date('Y',strtotime($this->input->post('UM_DATE')));
				$PRJCODE		= $this->input->post('PRJCODE');
				
				$JCODEID		= $this->input->post('JCODEID');

				$COL_JCODEID 	= $JCODEID;
				
				$UM_STAT		= $this->input->post('UM_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
				$UM_NOTE		= addslashes($this->input->post('UM_NOTE'));
				$UM_NOTE2		= addslashes($this->input->post('UM_NOTE2'));
				$WH_CODE		= $this->input->post('WH_CODE');
				
				$UM_APPROVED	= date('Y-m-d H:i:s');
				$UM_APPROVER	= $DefEmp_ID;
				$AH_ISLAST		= $this->input->post('IS_LAST');
				
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $UM_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $APPDATE;
					$AH_NOTES		= addslashes($this->input->post('UM_NOTE2'));
					
					// UPDATE JOBDETAIL ITEM
					if($UM_STAT == 3)
					{
						// START : SAVE APPROVE HISTORY
							$this->load->model('m_updash/m_updash', '', TRUE);
							
							$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
													'AH_CODE'		=> $AH_CODE,
													'AH_APPLEV'		=> $AH_APPLEV,
													'AH_APPROVER'	=> $AH_APPROVER,
													'AH_APPROVED'	=> $AH_APPROVED,
													'AH_NOTES'		=> $AH_NOTES,
													'AH_ISLAST'		=> $AH_ISLAST);										
							//******$this->m_updash->insAppHist($insAppHist);
						// END : SAVE APPROVE HISTORY
					}				
					
					$updUM = array('UM_STAT'		=> 7);
					//******$this->m_itemusage->updateUM($UM_NUM, $updUM);
				// END : SAVE APPROVE HISTORY
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
											'DOC_CODE' 		=> $UM_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_um_header");
					//******$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($AH_ISLAST == 1)
				{
					$updUM = array('UM_STAT'		=> $UM_STAT);
					//******$this->m_itemusage->updateUM($UM_NUM, $updUM);
					
					if($UM_STAT == 3)
					{
						$UM_AMOUNT 		= $this->m_itemusage->get_totam($UM_NUM);
						
						// START : TRACK FINANCIAL TRACK
							$this->load->model('m_updash/m_updash', '', TRUE);
							$paramFT = array('DOC_NUM' 		=> $UM_NUM,
											'DOC_DATE' 		=> $UM_DATE,
											'DOC_EDATE' 	=> $UM_DATE,
											'PRJCODE' 		=> $PRJCODE,
											'FIELD_NAME1' 	=> 'FT_UM',
											'FIELD_NAME2' 	=> 'FM_UM',
											'TOT_AMOUNT'	=> $UM_AMOUNT);
							//******$this->m_updash->finTrack($paramFT);
						// END : TRACK FINANCIAL TRACK

						if($UM_TYPE == 1)
							$TRANS_CATEG 	= 'UM';
						else
							$TRANS_CATEG 	= 'UM-SUB';

						// START : JOURNAL HEADER
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$JournalH_Code	= $UM_NUM;
							$JournalType	= $TRANS_CATEG;
							$JournalH_Date	= $UM_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $UM_NUM;
							$LastUpdate		= $UM_APPROVED;
							$WH_CODE		= $WH_CODE;
							$Refer_Number	= '';
							$RefType		= 'WBSD';
							$PRJCODE		= $PRJCODE;
							
							// START : NEW CONDITION
								/*	APABILA TIPE PENGGUNAAN 1, MAKA LAKUKAN SECARA NORMAL
									APABILA TIPE PENGGUNAAN 2 (PEMINJAMAN MATERIAL OLEH SUPLIER/SUBKON)
									MAKA, JURNAL YANG BERLAKU ADALAH
									(D) Piutang Supl/Sub.
										(K) STOCK
								*/
							// END : NEW CONDITION

							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Desc'		=> $UM_NOTE2."-".$UM_SPLNOTES,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'Manual_No'			=> $UM_CODE,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE);
							//******$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
						// END : JOURNAL HEADER
						
						// START : JOURNAL DETAIL
							foreach($_POST['data'] as $d)
							{
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $d['ITM_CODE'];
								$JOBCODEID 		= $d['JOBCODEID'];
								$ACC_ID 		= $d['ACC_ID'];
								$ITM_UNIT 		= $d['ITM_UNIT'];
								$ITM_GROUP 		= $d['ITM_GROUP'];
								$ITM_TYPE 		= $d['ITM_TYPE'];
								$ITM_QTY 		= $d['ITM_QTY'];
								$ITM_PRICE 		= $d['ITM_PRICE'];

								// UNTUK HARGA CARI DARI RATA2 HARGA (TOTAL IN / VOL IN)
								$ITM_PRICE 		= $this->m_updash->get_itmAVG($PRJCODE, $ITM_CODE);

								$Notes 			= $d['UM_DESC'];
								$ITM_DISC 		= 0;
								$TAXCODE1 		= '';
								$TAXPRICE1 		= 0;
								
								$JournalH_Code	= $UM_NUM;
								$JournalType	= 'UM';
								$JournalH_Date	= $UM_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= $UM_APPROVED;
								$WH_CODE		= $WH_CODE;
								$Refer_Number	= '';
								$RefType		= $TRANS_CATEG;
								$JSource		= $TRANS_CATEG;
								$PRJCODE		= $PRJCODE;

								$parameters = array('JournalH_Code' 	=> $JournalH_Code,
													'JournalType'		=> $JournalType,
													'JournalH_Date' 	=> $JournalH_Date,
													'Company_ID' 		=> $Company_ID,
													'JOBCODEID'			=> $JOBCODEID,
													'Currency_ID' 		=> $Currency_ID,
													'Source'			=> $DOCSource,
													'Emp_ID'			=> $DefEmp_ID,
													'LastUpdate'		=> $LastUpdate,	
													'KursAmount_tobase'	=> 1,
													'WHCODE'			=> $WH_CODE,
													'Reference_Number'	=> $Refer_Number,
													'RefType'			=> $RefType,
													'PRJCODE'			=> $PRJCODE,
													'JSource'			=> $JSource,
													'TRANS_CATEG' 		=> $TRANS_CATEG,	// UM = Use Material, UM-SUB = Penggunaan Material oleh Pihak 3
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_GROUP' 		=> $ITM_GROUP,
													'ITM_TYPE' 			=> $ITM_TYPE,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes'				=> $Notes,
													'JOBCODEID'			=> $JOBCODEID);
								//******$this->m_journal->createJournalD($JournalH_Code, $parameters);

								if($UM_TYPE == 1)
								{
									// START : UPDATE PROFIT AND LOSS
										$this->load->model('m_updash/m_updash', '', TRUE);

										$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
										$PERIODED	= $JournalH_Date;
										$FIELDNME	= "";
										$FIELDVOL	= $ITM_QTY;
										$FIELDPRC	= $ITM_PRICE;
										$ADDTYPE	= "MIN";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

										$parameters1 = array('PERIODED' 	=> $PERIODED,
															'FIELDNME'		=> $FIELDNME,
															'FIELDVOL' 		=> $FIELDVOL,
															'FIELDPRC' 		=> $FIELDPRC,
															'ADDTYPE' 		=> $ADDTYPE,
															'ITM_CODE'		=> $ITM_CODE,
															'ITM_TYPE'		=> $ITM_TYPE);
										//******$this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
									// END : UPDATE PROFIT AND LOSS

									// START : UPDATE STOCK
										$parameters1 = array('PRJCODE' 	=> $PRJCODE,
															'WH_CODE'	=> $WH_CODE,
															'JOBCODEID'	=> $JOBCODEID,
															'UM_NUM' 	=> $UM_NUM,
															'UM_CODE' 	=> $UM_CODE,
															'ITM_CODE' 	=> $ITM_CODE,
															'ITM_GROUP'	=> $ITM_GROUP,
															'ITM_QTY' 	=> $ITM_QTY,
															'ITM_PRICE' => $ITM_PRICE);
										$this->m_itemusage->updateITM_Min($parameters1);
									// START : UPDATE STOCK
								}
								else
								{
									// START : UPDATE STOCK
										$parameters1 = array('PRJCODE' 	=> $PRJCODE,
															'WH_CODE'	=> $WH_CODE,
															'JOBCODEID'	=> $JOBCODEID,
															'UM_NUM' 	=> $UM_NUM,
															'UM_CODE' 	=> $UM_CODE,
															'ITM_CODE' 	=> $ITM_CODE,
															'ITM_GROUP'	=> $ITM_GROUP,
															'ITM_QTY' 	=> $ITM_QTY,
															'ITM_PRICE' => $ITM_PRICE);
										//******$this->m_itemusage->updateITM_MinUMSUB($parameters1);
									// START : UPDATE STOCK
								}
								
								// START : RECORD TO ITEM HISTORY
									//$this->m_journal->createITMHistMin($JournalH_Code, $parameters); // Melalui createJournalD()
								// START : RECORD TO ITEM HISTORY
							}
						// END : JOURNAL DETAIL
						
						// START : UPDATE STAT DET
							$this->load->model('m_updash/m_updash', '', TRUE);				
							$paramSTAT 	= array('JournalHCode' 	=> $UM_NUM);
							//******$this->m_updash->updSTATJD($paramSTAT);
						// END : UPDATE STAT DET
				
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
													'DOC_CODE' 		=> $UM_NUM,
													'DOC_STAT' 		=> $UM_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> '',
													'TBLNAME'		=> "tbl_um_header");
							//******$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					
						// START : UPDATE TO DOC. COUNT
							$parameters 	= array('DOC_CODE' 		=> $UM_NUM,
													'PRJCODE' 		=> $PRJCODE,
													'DOC_TYPE'		=> "UM",
													'DOC_QTY' 		=> "DOC_UMQ",
													'DOC_VAL' 		=> "DOC_UMV",
													'DOC_STAT' 		=> 'ADD');
							//******$this->m_updash->updateDocC($parameters);
						// END : UPDATE TO DOC. COUNT
					}
				}
				return false;
				if($UM_STAT == 4)
				{
					// START : DELETE HISTORY
						$this->m_updash->delAppHist($UM_NUM);
					// END : DELETE HISTORY

					$updUM = array('UM_STAT'		=> $UM_STAT,
									'UM_NOTE2'		=> $AH_NOTES,
									'UM_APPROVED'	=> $UM_APPROVED,
									'UM_APPROVER'	=> $UM_APPROVER);
					$this->m_itemusage->updateUM($UM_NUM, $updUM); // Update Status
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
												'DOC_CODE' 		=> $UM_NUM,
												'DOC_STAT' 		=> $UM_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_um_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				
				if($UM_STAT == 5)
				{
					$updUM = array('UM_STAT'		=> $UM_STAT,
									'UM_NOTE2'		=> $AH_NOTES,
									'UM_APPROVED'	=> $UM_APPROVED,
									'UM_APPROVER'	=> $UM_APPROVER);
					$this->m_itemusage->updateUM($UM_NUM, $updUM); // Update Status
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "UM_NUM",
												'DOC_CODE' 		=> $UM_NUM,
												'DOC_STAT' 		=> $UM_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_um_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}

				$updDesc	= "UPDATE tbl_journalheader A, tbl_um_header B SET A.JournalH_Desc = B.UM_NOTE
								WHERE A.JournalH_Code = B.UM_NUM AND A.JournalH_Code = '$UM_NUM'";
				$this->db->query($updDesc);
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = IR_STAT
					$parameters 	= array('DOC_CODE' 		=> $UM_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "UM",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_um_header",	// TABLE NAME
											'KEY_NAME'		=> "UM_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "UM_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $UM_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_UM",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
											'FIELD_NM_N'	=> "TOT_UM_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_C'	=> "TOT_UM_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_A'	=> "TOT_UM_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_R'	=> "TOT_UM_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_RJ'	=> "TOT_UM_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
											'FIELD_NM_CL'	=> "TOT_UM_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
				
				$url			= site_url('c_inventory/c_iu180c16/in180c18b0xx/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				redirect($url);
			}
			else
			{
				redirect('__l1y');
			}
		}
			
		function getProjName($myLove_the_an) // G
		{ 
			// check exixtensi projcewt code
			$getProj_Name 	= $this->m_itemusage_sd->getProjName($myLove_the_an);
			echo $getProj_Name;
		}
		
		function pall180c16Jb() // G
		{
			if ($this->session->userdata('login') == TRUE)
			{
				$sqlApp 		= "SELECT * FROM tappname";
				$resultaApp = $this->db->query($sqlApp)->result();
				foreach($resultaApp as $therow) :
					$appName = $therow->app_name;		
				endforeach;
							
				$COLLID		= $_GET['id'];
				$PRJCODE	= $_GET['pr1h0ec0JcoDe'];
				$JIDExplode = explode('~', $COLLID);
				$JOBCODE	= '';
				foreach($JIDExplode as $i => $key)
				{
					if($i == 0)
					{
						$JOBCODE1	= $key;
						$JOBCODE	= "'$key'";
					}
					elseif($i > 0)
					{
						$JOBCODE	= "$JOBCODE,'$key'";
					}
				}

				$data['PRJCODE'] 		= $PRJCODE;
				$data['JOBCODE'] 		= $JOBCODE;
						
				$this->load->view('v_inventory/v_itemusage/itemusage_seljob', $data);
			}
			else
			{
				redirect('__l1y');
			}
		}

	  	function get_AllDataSRV() // GOOD
		{
			//$PRJCODE		= $_GET['id'];

			setlocale(LC_ALL, 'id-ID', 'id_ID');

			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$PRJCODE	= $_GET['id'];

			$LangID     = $this->session->userdata['LangID'];
	        
	        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	        $resTransl      = $this->db->query($sqlTransl)->result();
	        foreach($resTransl as $rowTransl) :
	            $TranslCode = $rowTransl->MLANG_CODE;
	            $LangTransl = $rowTransl->LangTransl;

	    		if($TranslCode == 'Director')$Director = $LangTransl;
	            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
	            if($TranslCode == 'Location')$Location = $LangTransl;
	    		if($TranslCode == 'Active')$Active = $LangTransl;
	    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
	    		if($TranslCode == 'Contact')$Contact = $LangTransl;
	    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
	    		if($TranslCode == 'Budget')$Budget = $LangTransl;
	    		if($TranslCode == 'Phone')$Phone = $LangTransl;
	    		if($TranslCode == 'Notes')$Notes = $LangTransl;
	        endforeach;
			
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
				
				$columns_valid 	= array("JOBCODEID",
										"JOBDESC");
		
				if(!isset($columns_valid[$col])) {
					$order = null;
				} else {
					$order = $columns_valid[$col];
				}
				
				$draw			= $_REQUEST['draw'];
				$length			= $_REQUEST['length'];
				$start			= $_REQUEST['start'];
				$search			= $_REQUEST['search']["value"];
				$num_rows 		= $this->m_itemusage->get_AllDataSRVCX($PRJCODE, $search);
				$total			= $num_rows;
				$output			= array();
				$output['draw']	= $draw;
				$output['recordsTotal'] = $output['recordsFiltered']= $total;
				$output['data']	= array();
				
				$query 			= $this->m_itemusage->get_AllDataSRVLX($PRJCODE, $search, $length, $start, $order, $dir);
									
				$noU			= $start + 1;
				$TotBOQ			= 0;
				$TotBUD			= 0;
				$TotADD			= 0;
				$TotADD2		= 0;
				$TotADD3		= 0;
				$TotADD4		= 0;
				$TotADD5		= 0;
				$TotALL			= 0;
				$TotREM			= 0;
				$REMAIN2		= 0;
				$TotUSE			= 0;
				$TotPC			= 0;	// Total Project Complete
				foreach ($query->result_array() as $dataI) 
				{
					$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
					$JOBDESC		= $dataI['JOBDESC'];
					$ITM_UNIT		= $dataI['JOBUNIT'];
					$JOBLEV			= $dataI['JOBLEV'];

					// IS LAST SETT
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";

					/*$s_01 			= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID' AND ISLAST = '1'";
					$q_01 			= $this->db->count_all($s_01);
					if($q_01 == 0)*/
						$chkBox		= "<input type='radio' name='chk0' value='".$JOBCODEID."|".$PRJCODE."|".$JOBDESC."' onClick='pickThis0(this);'/>";
					/*else
						$chkBox		= "<input type='radio' name='chk' value='' disabled='disabled'/>";*/


					// SPACE
						if($JOBLEV == 1)
							$spaceLev 	= "";
						elseif($JOBLEV == 2)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 3)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 4)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 5)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 6)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 7)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 8)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 9)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 10)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 11)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						elseif($JOBLEV == 12)
							$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

					$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

					$output['data'][] 	= array($chkBox,
												"<span>".$JobView."</span>"
											);

					$noU			= $noU + 1;
				}

				/*$output['data'][] 	= array("A",
											"B"
										);*/
				echo json_encode($output);
			// END : FOR SERVER-SIDE
		}

	  	function get_AllDataJL() // GOOD
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
				
				$columns_valid 	= array("",
										"JOBDESC");
		
				if(!isset($columns_valid[$col])) {
					$order = null;
				} else {
					$order = $columns_valid[$col];
				}
				
				$draw			= $_REQUEST['draw'];
				$length			= $_REQUEST['length'];
				$start			= $_REQUEST['start'];
				$search			= $_REQUEST['search']["value"];
				$num_rows 		= $this->m_itemusage->get_AllDataJLC($PRJCODE, $search);
				$total			= $num_rows;
				$output			= array();
				$output['draw']	= $draw;
				$output['recordsTotal'] = $output['recordsFiltered']= $total;
				$output['data']	= array();
				$query 			= $this->m_itemusage->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
									
				$noU			= $start + 1;
				foreach ($query->result_array() as $dataI) 
				{
					$UM_NUM		= $dataI['UM_NUM'];
					$UM_CODE	= $dataI['UM_CODE'];
					
					$UM_DATE	= $dataI['UM_DATE'];
					$UM_DATEV	= date('d M Y', strtotime($UM_DATE));
					
					$PRJCODE	= $dataI['PRJCODE'];
					$JOBCODEID	= $dataI['JOBCODEID'];
					$JOBDESC	= $dataI['JOBDESC'];
					$UM_NOTE	= $dataI['UM_NOTE'];
					$UM_STAT	= $dataI['UM_STAT'];
					$REVMEMO	= $dataI['REVMEMO'];
					$STATDESC	= $dataI['STATDESC'];
					$STATCOL	= $dataI['STATCOL'];
					$CREATERNM	= $dataI['CREATERNM'];
					$empName	= cut_text2 ("$CREATERNM", 15);
								
					$ISVOID		=  $dataI['ISVOID'];														
					if($ISVOID == 0)
					{
						$ISVOIDDes	= 'No';
						$STATCOLV	= 'success';
					}
					elseif($ISVOID == 1)
					{
						$ISVOIDDes	= 'Yes';
						$STATCOLV	= 'danger';
					}
					
					$CollID		= "$PRJCODE~$UM_NUM";
					$secUpd		= site_url('c_inventory/c_iu180c16/update/?id='.$this->url_encryption_helper->encode_url($UM_NUM));
					$secPrint	= site_url('c_inventory/c_iu180c16/printdocument/?id='.$this->url_encryption_helper->encode_url($CollID));
					$CollID1	= "UM~$UM_NUM~$PRJCODE";
					$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID1;
	                                    
					if($UM_STAT == 1) 
					{
						$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									   <label style='white-space:nowrap'>
									   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   </a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
											<i class='fa fa-trash-o'></i>
										</a>
										</label>";
					}                                            
					else
					{
						$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									   <label style='white-space:nowrap'>
									   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   </a>
										<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
											<i class='glyphicon glyphicon-print'></i>
										</a>
										<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
											<i class='fa fa-trash-o'></i>
										</a>
										</label>";
					}
									
					
					$output['data'][] = array("$noU.",
											  $dataI['UM_CODE'],
											  $UM_DATEV,
											  $JOBDESC,
											  $UM_NOTE,
											  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											  "<span class='label label-".$STATCOLV."' style='font-size:12px'>".$ISVOIDDes."</span>",
											  $secAction);
					$noU		= $noU + 1;
				}

				echo json_encode($output);
			// END : FOR SERVER-SIDE
		}

	  	function get_AllDataITM() // GOOD
		{
			//$PRJCODE		= $_GET['id'];

			setlocale(LC_ALL, 'id-ID', 'id_ID');

			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$PRJCODE	= $_GET['id'];
			$JOBPAR		= $_GET['JPAR'];
			//echo "JPAR = $PRJCODE";

			$LangID     = $this->session->userdata['LangID'];

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
				$num_rows 		= $this->m_itemusage->get_AllDataITMC($PRJCODE, $JOBPAR, $search);
				$total			= $num_rows;
				$output			= array();
				$output['draw']	= $draw;
				$output['recordsTotal'] = $output['recordsFiltered']= $total;
				$output['data']	= array();
				
				$query 			= $this->m_itemusage->get_AllDataITML($PRJCODE, $JOBPAR, $search, $length, $start, $order, $dir);
									
				$noU			= $start + 1;
				$TotBOQ			= 0;
				$TotBUD			= 0;
				$TotADD			= 0;
				$TotADD2		= 0;
				$TotADD3		= 0;
				$TotADD4		= 0;
				$TotADD5		= 0;
				$TotALL			= 0;
				$TotREM			= 0;
				$REMAIN2		= 0;
				$TotUSE			= 0;
				$TotPC			= 0;	// Total Project Complete
				foreach ($query->result_array() as $dataI) 
				{

					$JOBCODEDET 	= $dataI['JOBCODEDET'];				// 0
					$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
					$JOBCODE 		= $dataI['JOBCODE'];				// 2
					$JOBPARENT 		= $dataI['JOBPARENT'];				// 2
					$PRJCODE 		= $dataI['PRJCODE'];				// 3
					$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
					$ACC_ID 		= $dataI['ACC_ID'];
					$ACC_ID_UM 		= $dataI['ACC_ID_UM'];
					$ITM_NAME 		= $dataI['ITM_NAME'];				// 5
					$serialNumber	= '';								// 6
					$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
					$ITM_PRICE 		= $dataI['ITM_PRICE'];				// 8
					$ITM_PRICE 		= $dataI['ITM_LASTP'];				// 8	// FROM LAST PRICE
					$ITM_PRICE		= $dataI['ITM_AVGP'];				// 		// RATA - RATA
					$ITM_VOLM 		= $dataI['ITM_VOLM'];				// 9	// VOLUME BUDGET
					$ITM_STOCK 		= $dataI['ITM_STOCK'];				// 10
					$ITM_USED 		= $dataI['ITM_USED'];				// 11
					$itemConvertion	= 1;								// 12
					$ITM_AMOUNT		= $ITM_PRICE * $ITM_VOLM;			// 13
					$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 14
					$REQ_VOLM 		= $dataI['REQ_VOLM'];
					$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
					$PO_AMOUNT		= $dataI['PO_AMOUNT'];
					$PO_VOLM 		= $dataI['PO_VOLM'];
					$IR_VOLM 		= $dataI['IR_VOLM'];
					$IR_AMOUNT		= $dataI['IR_AMOUNT'];
					$ITM_BUDG		= $ITM_VOLM;
					if($ITM_BUDG == '')
						$ITM_BUDG	= 0;
					$ITM_IN			= $dataI['ITM_IN'];
					$ITM_OUT		= $dataI['ITM_OUT'];
					$PO_VOLM		= $dataI['PO_VOLM'];
					$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
                    $ITM_BUDG		= $ITM_AMOUNT;						// 16
					$TOT_USEDQTY	= $REQ_VOLM;						// 17
					if($TOT_USEDQTY == '')
						$TOT_USEDQTY = 0;
												
					// GET JOB DETAIL
						$JOBDESC		= '';
						$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
						$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
						foreach($resJOBDESC as $rowJOBDESC) :
							$JOBDESC	= $rowJOBDESC->JOBDESC;
						endforeach;
						$REMREQ_QTY		= $ITM_VOLM - $REQ_VOLM;
						$REMREQ_AMN		= ($ITM_VOLM * $ITM_PRICE) - ($REQ_AMOUNT);
					
					// GET RESERVE ITEM
						$ITMRSV		= 0;
						$sqlITMRSV	= "SELECT SUM(A.ITM_QTY) AS ITMRESERVE FROM tbl_um_detail A
										INNER JOIN tbl_um_header B ON A.UM_NUM = B.UM_NUM
										WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND B.UM_STAT IN (2,7)";
						$resITMRSV	= $this->db->query($sqlITMRSV)->result();
						foreach($resITMRSV as $rowITMRSV) :
							$ITMRSV	= $rowITMRSV->ITMRESERVE;
							if($ITMRSV == '')
								$ITMRSV	= 0;
						endforeach;
						$ITM_STOCK	= $ITM_IN - $ITM_OUT;
						$STOCK_READY= $ITM_IN - $ITM_OUT - $ITMRSV;
						$ITM_READY 	= $ITM_VOLM - $REQ_VOLM - $ITM_USED;

					// OTHER SETT
						if($ITM_READY > 0)
						{
							$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$ITM_NAME."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM."|".$ITM_STOCK."|".$ACC_ID."|".$ACC_ID_UM."' onClick='pickThis1(this);'/>";
						}
						else
						{
							$chkBox		= "<input type='checkbox' name='chk' value='' style='display: none' />";
						}

					$output['data'][] 	= array($chkBox,
												"<div style='font-style: italic;'>
											  		<span style='white-space:nowrap'><i class='text-muted'>$ITM_NAME - $JOBDESC</span>
											  	</div>
											  	<div>
											  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;$ITM_CODE</i>
											  	</div>",
												number_format($ITM_VOLM, 2),
												number_format($REQ_VOLM, 2),
												number_format($IR_VOLM, 2),
												number_format($ITM_USED, 2),
												number_format($ITMRSV, 2),
												number_format($ITM_STOCK, 2),
												"$ITM_UNIT"
											);

					$noU			= $noU + 1;
				}

				/*$output['data'][] 	= array("A",
											"A",
											"A",
											"A",
											"A",
											"A",
											"A",
											"A",
											"A"
										);*/
				echo json_encode($output);
			// END : FOR SERVER-SIDE
		}
	}
?>