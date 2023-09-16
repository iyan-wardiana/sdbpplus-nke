<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Oktober 2018
 * File Name	= C_mr180d0c.php
 * Location		= -
*/
class C_mr180d0c extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_production/m_matreq', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
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
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_mr180d0c/projectlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function projectlist() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_matreq', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN374';
				$data["MenuApp"] 	= 'MN375';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN374';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();		
			$data["secURL"] 	= "c_production/c_mr180d0c/g4ll_m4tr3q/?id=";
			
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
	
	function g4ll_m4tr3q() // G
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data["MenuCode"] 	= 'MN374';
			$mnCode				= 'MN374';
			$data["MenuApp"] 	= 'MN375';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		date_default_timezone_set("Asia/Jakarta");
		
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
				$data["url_search"] = site_url('c_production/c_mr180d0c/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_matreq->count_all_PRM($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_matreq->get_all_PRM($PRJCODE, $start, $end, $key)->result();
				$data["MenuApp"] 	= "MN374";
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Material Request";
			}
			
			$data['addURL'] 	= site_url('c_production/c_mr180d0c/a44_m4tr3q/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_mr180d0c/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN374';
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
			
			$this->load->view('v_production/v_material_req/v_material_req', $data);
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
			$url			= site_url('c_production/c_mr180d0c/g4ll_m4tr3q/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("MR_ID",
									"MR_CODE",
									"MR_DATE",
									"MR_NOTE",
									"MR_NOTE",
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
			$num_rows 		= $this->m_matreq->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_matreq->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$MR_ID		= $dataI['MR_ID'];
				$PRJCODE	= $dataI['PRJCODE'];
				$MR_NUM		= $dataI['MR_NUM'];
				$MR_CODE	= $dataI['MR_CODE'];				
				$MR_DATE	= $dataI['MR_DATE'];
				$MR_DATEV	= date('d M Y', strtotime($MR_DATE));
				
				$MR_NOTE	= $dataI['MR_NOTE'];
				$ITM_CODE	= $dataI['ITM_CODE'];
				$ITM_NAME	= '';
				$MR_STAT	= $dataI['MR_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$MR_NUM~$PRJCODE";
				$secUpd		= site_url('c_production/c_mr180d0c/u77_mr180d0c/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_production/c_mr180d0c/printdocument/?id='.$this->url_encryption_helper->encode_url($MR_NUM));
				$CollID		= "MR~$MR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_mr_header~tbl_mr_detail~MR_NUM~$MR_NUM~PRJCODE~$PRJCODE";
                                    
				if($MR_STAT == 1)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}							
				
				$output['data'][] = array($noU,
										  $dataI['MR_CODE'],
										  $MR_DATEV,
										  $MR_NOTE,
										  $ITM_NAME,
										  $CREATERNM,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_m4tr3q() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_matreq', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN374';
			$data["MenuApp"] 	= 'MN375';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Material Request";
			}
			
			$data['form_action']= site_url('c_production/c_mr180d0c/add_process');
			$data['backURL'] 	= site_url('c_production/c_mr180d0c/g4ll_m4tr3q/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN374';
			$data["MenuCode"] 	= 'MN374';
			$data['vwDocPatt'] 	= $this->m_matreq->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN374';
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
			
			$this->load->view('v_production/v_material_req/v_material_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function s3l4llj0() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$COLLID		= $_GET['id'];
		$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
		
		$url		= site_url('c_production/c_mr180d0c/s3l4llj0_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function sH0w4llI73M() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_matreq', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$JO_NUM		= $_GET['JO_NUM'];
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$JOBCODE	= $plitWord[1];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h2_title"] 	= "List Item";
			}
			
			$data['form_action']	= site_url('c_purchase/c_pr180d0c/update_process');
			$data['JO_NUM'] 		= $JO_NUM;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['JOBCODE'] 		= $JOBCODE;
			$data['secShowAll']		= site_url('c_purchase/c_pr180d0c/sH0w4llI73M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['cAllItemPrm']	= $this->m_matreq->count_all_prim($PRJCODE, $JOBCODE, $JO_NUM);
			$data['vwAllItemPrm'] 	= $this->m_matreq->view_all_prim($PRJCODE, $JOBCODE, $JO_NUM)->result();
			
			$data['cAllItemSubs']	= $this->m_matreq->count_all_subs($PRJCODE, $JOBCODE);
			$data['vwAllItemSubs'] 	= $this->m_matreq->view_all_subs($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_production/v_material_req/v_material_req_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llj0_x1() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_matreq', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
			$data['title'] 		= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar JO";
			}
			else
			{
				$data["h1_title"] 	= "Job Order List";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['cAllJO']			= $this->m_matreq->count_all_JO($PRJCODE);
			$data['vAllJO'] 		= $this->m_matreq->view_all_JO($PRJCODE)->result();
					
			$this->load->view('v_production/v_material_req/v_material_req_seljo', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$MR_CREATED 	= date('Y-m-d H:i:s');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN374';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$MR_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$MR_CODE 		= $this->input->post('MR_CODE');
			$MR_DATE		= date('Y-m-d',strtotime($this->input->post('MR_DATE')));
			$MR_DATEU		= date('Y-m-d',strtotime($this->input->post('MR_DATEU')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			$MR_NOTE 		= $this->input->post('MR_NOTE');
			$MR_STAT 		= $this->input->post('MR_STAT');
			$MR_REFNO 		= $this->input->post('MR_REFNO');
			$Patt_Year		= date('Y',strtotime($this->input->post('MR_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('MR_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('MR_DATE')));
			
			$SO_NUM 		= '';
			$SO_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlSOH 		= "SELECT SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
			$resSOH 		= $this->db->query($sqlSOH)->result();
			foreach($resSOH as $rowSOH) :
				$SO_NUM 	= $rowSOH->SO_NUM;
				$SO_CODE 	= $rowSOH->SO_CODE;
				$CCAL_NUM 	= $rowSOH->CCAL_NUM;
				$CCAL_CODE 	= $rowSOH->CCAL_CODE;
				$BOM_NUM 	= $rowSOH->BOM_NUM;
				$BOM_CODE 	= $rowSOH->BOM_CODE;
			endforeach;
			
			$PRREFNO		= $this->input->post('MR_REFNO');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				/*foreach ($PRREFNO as $MR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLMR_REFNO	= "$MR_REFNO";
					}
					else
					{
						$COLMR_REFNO	= "$COLMR_REFNO$MR_REFNO";
					}
				}*/
				$COLMR_REFNO	= "$PRREFNO";
			}
			else
			{
				$COLMR_REFNO	= '';
			}
			
			$projMatReqH 	= array('MR_NUM' 		=> $MR_NUM,
									'MR_CODE' 		=> $MR_CODE,
									'MR_DATE'		=> $MR_DATE,
									'MR_DATEU'	=> $MR_DATEU,
									'PRJCODE'		=> $PRJCODE,
									'JO_NUM'		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'SO_NUM'		=> $SO_NUM,
									'SO_CODE'		=> $SO_CODE,
									'CCAL_NUM'		=> $CCAL_NUM,
									'CCAL_CODE'		=> $CCAL_CODE,
									'BOM_NUM'		=> $BOM_NUM,
									'BOM_CODE'		=> $BOM_CODE,
									'MR_NOTE'		=> $MR_NOTE,
									'MR_STAT'		=> $MR_STAT,
									'MR_CREATER'	=> $DefEmp_ID,
									'MR_CREATED'	=> $MR_CREATED,
									'MR_REFNO'		=> $COLMR_REFNO,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_matreq->add($projMatReqH);
			
			$PRMTOTAL	= 0;
			foreach($_POST['data'] as $d)
			{
				$d['MR_NUM']	= $MR_NUM;
				$d['MR_CODE']	= $MR_CODE;
				$MR_TOTAL		= $d['MR_TOTAL'];
				$PRMTOTAL		= $PRMTOTAL + $MR_TOTAL;
				$this->db->insert('tbl_mr_detail',$d);
			}
			
			// UPDATE HEADER
				$prmHeader		= array('MR_AMOUNT'	=> $PRMTOTAL);
				$this->m_matreq->updateH($MR_NUM, $PRJCODE, $prmHeader);
			
			// UPDATE DETAIL
				$prmDetail		= array('MR_DATE'		=> $MR_DATE,
										'MR_DATEU'	=> $MR_DATEU,
										'JO_NUM'		=> $JO_NUM,
										'JO_CODE'		=> $JO_CODE);
				$this->m_matreq->updateD($MR_NUM, $PRJCODE, $prmDetail);
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('MR_STAT');			// IF "ADD" CONDITION ALWAYS = MR_STAT
				$parameters 	= array('DOC_CODE' 		=> $MR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PRM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_mr_header",	// TABLE NAME
										'KEY_NAME'		=> "MR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "MR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $MR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_MR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_MR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_MR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_MR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_MR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_MR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_MR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "MR_NUM",
										'DOC_CODE' 		=> $MR_NUM,
										'DOC_STAT' 		=> $MR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_mr_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MR_NUM;
				$MenuCode 		= 'MN374';
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
			
			$url			= site_url('c_production/c_mr180d0c/g4ll_m4tr3q/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u77_mr180d0c() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN374';
			$data["MenuApp"] 	= 'MN375';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$CollID		= $_GET['id'];
		$CollID		= $this->url_encryption_helper->decode_url($CollID);
		
		$splitCode 	= explode("~", $CollID);
		$MR_NUM		= $splitCode[0];
		$PRJCODE	= $splitCode[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Material";
			}
			else
			{
				$data["h1_title"] 	= "Material Request";
			}
			
			$data['form_action']= site_url('c_production/c_mr180d0c/update_process');			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
								
			$getmrreq 						= $this->m_matreq->get_mr_by_number($MR_NUM)->row();
			$data['default']['MR_NUM'] 		= $getmrreq->MR_NUM;
			$data['default']['MR_CODE'] 	= $getmrreq->MR_CODE;
			$data['default']['MR_DATE'] 	= $getmrreq->MR_DATE;
			$data['default']['MR_DATEU']	= $getmrreq->MR_DATEU;
			$data['default']['PRJCODE']		= $getmrreq->PRJCODE;
			$data['PRJCODE']				= $getmrreq->PRJCODE;
			$PRJCODE 						= $getmrreq->PRJCODE;
			$data['default']['PRJNAME'] 	= $getmrreq->PRJNAME;
			$data['default']['JO_NUM'] 		= $getmrreq->JO_NUM;
			$data['default']['JO_CODE'] 	= $getmrreq->JO_CODE;
			$data['default']['MR_NOTE'] 	= $getmrreq->MR_NOTE;
			$data['default']['MR_NOTE1'] 	= $getmrreq->MR_NOTE1;
			$data['default']['MR_AMOUNT']	= $getmrreq->MR_AMOUNT;
			$data['default']['MR_REFNO']	= $getmrreq->MR_REFNO;
			$data['default']['MR_STAT'] 	= $getmrreq->MR_STAT;
			$data['default']['Patt_Year'] 	= $getmrreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getmrreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getmrreq->Patt_Date;
			$data['default']['Patt_Number']	= $getmrreq->Patt_Number;
			
			$MenuCode 			= 'MN374';
			$data["MenuCode"] 	= 'MN374';
			$data['vwDocPatt'] 	= $this->m_matreq->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getmrreq->MR_NUM;
				$MenuCode 		= 'MN374';
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
			
			$this->load->view('v_production/v_material_req/v_material_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$MR_CREATED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$MR_NUM			= $this->input->post('MR_NUM');
			$MR_CODE 		= $this->input->post('MR_CODE');
			$MR_DATE		= date('Y-m-d',strtotime($this->input->post('MR_DATE')));
			$MR_DATEU	= date('Y-m-d',strtotime($this->input->post('MR_DATEU')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			$MR_NOTE 		= $this->input->post('MR_NOTE');
			$MR_STAT 		= $this->input->post('MR_STAT');
			$MR_REFNO 		= $this->input->post('MR_REFNO');
			$Patt_Year		= date('Y',strtotime($this->input->post('MR_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('MR_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('MR_DATE')));
			
			$SO_NUM 		= '';
			$SO_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlSOH 		= "SELECT SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
			$resSOH 		= $this->db->query($sqlSOH)->result();
			foreach($resSOH as $rowSOH) :
				$SO_NUM 	= $rowSOH->SO_NUM;
				$SO_CODE 	= $rowSOH->SO_CODE;
				$CCAL_NUM 	= $rowSOH->CCAL_NUM;
				$CCAL_CODE 	= $rowSOH->CCAL_CODE;
				$BOM_NUM 	= $rowSOH->BOM_NUM;
				$BOM_CODE 	= $rowSOH->BOM_CODE;
			endforeach;
			
			$PRREFNO		= $this->input->post('MR_REFNO');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				/*foreach ($PRREFNO as $MR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLMR_REFNO	= "$MR_REFNO";
					}
					else
					{
						$COLMR_REFNO	= "$COLMR_REFNO$MR_REFNO";
					}
				}*/
				$COLMR_REFNO	= "$PRREFNO";
			}
			else
			{
				$COLMR_REFNO	= '';
			}
			
			$projMatReqH 	= array('MR_NUM' 		=> $MR_NUM,
									'MR_CODE' 		=> $MR_CODE,
									'MR_DATE'		=> $MR_DATE,
									'MR_DATEU'	=> $MR_DATEU,
									'PRJCODE'		=> $PRJCODE,
									'JO_NUM'		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'SO_NUM'		=> $SO_NUM,
									'SO_CODE'		=> $SO_CODE,
									'CCAL_NUM'		=> $CCAL_NUM,
									'CCAL_CODE'		=> $CCAL_CODE,
									'BOM_NUM'		=> $BOM_NUM,
									'BOM_CODE'		=> $BOM_CODE,
									'MR_NOTE'		=> $MR_NOTE,
									'MR_STAT'		=> $MR_STAT,
									'MR_CREATER'	=> $DefEmp_ID,
									'MR_CREATED'	=> $MR_CREATED,
									'MR_REFNO'		=> $COLMR_REFNO,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_matreq->update($MR_NUM, $projMatReqH);
			
			if($MR_STAT == 6) // IF CLOSE
			{
				foreach($_POST['data'] as $d)
				{
					$MR_NUM		= $d['MR_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					//$this->m_matreq->updateVolBud($MR_NUM, $PRJCODE, $ITM_CODE);
				}
			}
			elseif($MR_STAT == 5)	// REJECTED
			{
				// Cek IR with PO Source Code
				//$this->m_matreq->updREJECT($MR_NUM, $PRJCODE);
			}
			else
			{
				$this->m_matreq->deleteDetail($MR_NUM);
				
				$PRMTOTAL	= 0;
				foreach($_POST['data'] as $d)
				{
					$d['MR_NUM']	= $MR_NUM;
					$d['MR_CODE']	= $MR_CODE;
					$MR_TOTAL		= $d['MR_TOTAL'];
					$PRMTOTAL		= $PRMTOTAL + $MR_TOTAL;
					$this->db->insert('tbl_mr_detail',$d);
				}
			
				// UPDATE HEADER
					$prmHeader		= array('MR_AMOUNT'	=> $PRMTOTAL);
					$this->m_matreq->updateH($MR_NUM, $PRJCODE, $prmHeader);
				
				// UPDATE DETAIL
					$prmDetail		= array('MR_DATE'		=> $MR_DATE,
											'MR_DATEU'	=> $MR_DATEU,
											'JO_NUM'		=> $JO_NUM,
											'JO_CODE'		=> $JO_CODE);
					$this->m_matreq->updateD($MR_NUM, $PRJCODE, $prmDetail);
			}
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('MR_STAT');			// IF "ADD" CONDITION ALWAYS = MR_STAT
				$parameters 	= array('DOC_CODE' 		=> $MR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PRM",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_mr_header",	// TABLE NAME
										'KEY_NAME'		=> "MR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "MR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $MR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_MR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_MR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_MR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_MR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_MR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_MR_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_MR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "MR_NUM",
										'DOC_CODE' 		=> $MR_NUM,
										'DOC_STAT' 		=> $MR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_mr_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $MR_NUM;
				$MenuCode 		= 'MN374';
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
							
			$url			= site_url('c_production/c_mr180d0c/g4ll_m4tr3q/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function inbox() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_mr180d0c/p07_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function p07_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN374';
				$data["MenuApp"] 	= 'MN375';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN375';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_mr180d0c/glMr_1Nb/?id=";
			
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
	
	function glMr_1Nb() // OK
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data["MenuCode"] 	= 'MN375';
			$mnCode				= 'MN375';
			$data["MenuApp"] 	= 'MN375';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$LangID 		= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$data["h1_title"] 	= "Pers. Permintaan";
		}
		else
		{
			$data["h1_title"] 	= "Request Approval";
		}
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
				$data["url_search"] = site_url('c_production/c_mr180d0c/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_matreq->count_all_MRInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_matreq->get_all_MRInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_production/c_mr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_mr180d0c/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN375';
				$TTR_CATEG		= 'APP-L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_production/v_material_req/v_inb_material_req', $data);
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
			$url			= site_url('c_production/c_mr180d0c/glMr_1Nb/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function up180djinb()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN374';
			$data["MenuApp"] 	= 'MN375';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$MR_NUM		= $_GET['id'];
		$MR_NUM		= $this->url_encryption_helper->decode_url($MR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Permintaan";
			}
			else
			{
				$data["h1_title"] 	= "Request Approval";
			}
			
			$data['form_action']= site_url('c_production/c_mr180d0c/update_process_inb');			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
								
			$getmrreq 						= $this->m_matreq->get_mr_by_number($MR_NUM)->row();
			$data['default']['MR_NUM'] 		= $getmrreq->MR_NUM;
			$data['default']['MR_CODE'] 	= $getmrreq->MR_CODE;
			$data['default']['MR_DATE'] 	= $getmrreq->MR_DATE;
			$data['default']['MR_DATEU']	= $getmrreq->MR_DATEU;
			$data['default']['PRJCODE']		= $getmrreq->PRJCODE;
			$data['PRJCODE']				= $getmrreq->PRJCODE;
			$PRJCODE 						= $getmrreq->PRJCODE;
			$data['default']['PRJNAME'] 	= $getmrreq->PRJNAME;
			$data['default']['JO_NUM'] 		= $getmrreq->JO_NUM;
			$data['default']['JO_CODE'] 	= $getmrreq->JO_CODE;
			$data['default']['MR_NOTE'] 	= $getmrreq->MR_NOTE;
			$data['default']['MR_NOTE1'] 	= $getmrreq->MR_NOTE1;
			$data['default']['MR_AMOUNT']	= $getmrreq->MR_AMOUNT;
			$data['default']['MR_REFNO']	= $getmrreq->MR_REFNO;
			$data['default']['MR_STAT'] 	= $getmrreq->MR_STAT;
			$data['default']['Patt_Year'] 	= $getmrreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getmrreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getmrreq->Patt_Date;
			$data['default']['Patt_Number']	= $getmrreq->Patt_Number;
			
			$MenuCode 			= 'MN375';
			$data["MenuCode"] 	= 'MN375';
			$data['vwDocPatt'] 	= $this->m_matreq->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getmrreq->MR_NUM;
				$MenuCode 		= 'MN375';
				$TTR_CATEG		= 'APP-U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_production/v_material_req/v_inb_material_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$MR_APPROVED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$MR_NUM			= $this->input->post('MR_NUM');
			$MR_STAT 		= $this->input->post('MR_STAT');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$MR_NOTE1 		= $this->input->post('MR_NOTE1');
			
			$AH_CODE		= $MR_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $MR_APPROVED;
			$AH_NOTES		= $this->input->post('MR_NOTE1');
			$MR_NOTE1		= $this->input->post('MR_NOTE1');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projMatReqH 	= array('MR_NOTE1'	=> $MR_NOTE1,
									'MR_STAT'	=> 7);										
			$this->m_matreq->update($MR_NUM, $projMatReqH);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "MR_NUM",
										'DOC_CODE' 		=> $MR_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_mr_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// UPDATE JOBDETAIL ITEM
			if($MR_STAT == 3)
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
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				//$this->m_matreq->updateJobDet($MR_NUM, $PRJCODE); // UPDATE JOBD ETAIL DAN PR
			}
			
			if($AH_ISLAST == 1)
			{
				if($MR_STAT == 3)
				{
					$projMatReqH 	= array('MR_STAT'	=> $MR_STAT,
											'MR_NOTE1'	=> $MR_NOTE1);
					$this->m_matreq->update($MR_NUM, $projMatReqH);
					
					$this->m_matreq->updateRelDet($MR_NUM, $JO_NUM, $PRJCODE); // UPDATE RELATION DETAIL
				}
				
				$projMatReqH 	= array('MR_APPROVER'	=> $DefEmp_ID,
										'MR_APPROVED'	=> $MR_APPROVED,
										'MR_NOTE1'		=> $MR_NOTE1,
										'MR_STAT'		=> $MR_STAT);										
				$this->m_matreq->update($MR_NUM, $projMatReqH);
			
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = MR_STAT
					$parameters 	= array('DOC_CODE' 		=> $MR_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "PRM",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_mr_header",	// TABLE NAME
											'KEY_NAME'		=> "MR_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "MR_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $MR_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_MR",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_MR_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_MR_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_MR_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_MR_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_MR_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_MR_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "MR_NUM",
											'DOC_CODE' 		=> $MR_NUM,
											'DOC_STAT' 		=> $MR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_mr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $MR_NUM;
					$MenuCode 		= 'MN375';
					$TTR_CATEG		= 'APP-UP';
					
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
											'TTR_DATE' 		=> date('Y-m-d H:i:s'),
											'TTR_MNCODE'	=> $MenuCode,
											'TTR_CATEG'		=> $TTR_CATEG,
											'TTR_PRJCODE'	=> $TTR_PRJCODE,
											'TTR_REFDOC'	=> $TTR_REFDOC);
					$this->m_updash->updateTrack($paramTrack);
				// END : UPDATE TO T-TRACK
			}
			
			$url			= site_url('c_production/c_mr180d0c/glMr_1Nb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printpolist()
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$MR_NUM		= $_GET['id'];
		$MR_NUM		= $this->url_encryption_helper->decode_url($MR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$sqlPR 	= "SELECT MR_DATE, MR_NOTE FROM tbl_mr_header WHERE MR_NUM = '$MR_NUM'";
			$resultaPR = $this->db->query($sqlPR)->result();
			foreach($resultaPR as $rowPR) :
				$MR_DATE = $rowPR->MR_DATE;
				$MR_NOTE = $rowPR->MR_NOTE;		
			endforeach;
			$data['MR_NUM'] 	= $MR_NUM;
			$data['MR_DATE'] 	= $MR_DATE;
			$data['MR_NOTE'] 	= $MR_NOTE;
			
			$data['countPO']	= $this->m_matreq->count_all_PO($MR_NUM);
			$data['vwPO'] 		= $this->m_matreq->get_all_PO($MR_NUM)->result();	
							
			$this->load->view('v_purchase/v_purchase_req/print_polist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$MR_NUM		= $_GET['id'];
		$MR_NUM		= $this->url_encryption_helper->decode_url($MR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 		= $appName;
			$data['MR_NUM'] 	= $MR_NUM;
			
			$getmrreq 			= $this->m_matreq->get_mr_by_number($MR_NUM)->row();
			
			$data['MR_NUM'] 	= $getmrreq->MR_NUM;
			$data['MR_DATE'] 	= $getmrreq->MR_DATE;
			$data['PRJCODE'] 	= $getmrreq->PRJCODE;
			
			$this->load->view('v_production/v_material_req/v_material_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printQR()
	{
		$this->load->model('m_production/m_matreq', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$MR_NUM		= $_GET['id'];
		$MR_NUM		= $this->url_encryption_helper->decode_url($MR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getmrreq = $this->m_matreq->get_mr_by_number($MR_NUM)->row();
			
			$data['MR_NUM'] 		= $getmrreq->MR_NUM;
			$data['MR_STAT'] 	= $getmrreq->MR_STAT;
			$data['MR_CODE'] 	= $getmrreq->MR_CODE;
			$data['MR_DATE'] 	= $getmrreq->MR_DATE;
			$data['PRJCODE'] 	= $getmrreq->PRJCODE;
			$data['JO_NUM']		= $getmrreq->JO_NUM;
			$data['JO_CODE']		= $getmrreq->JO_CODE;
			$data['MR_NOTE'] 	= $getmrreq->MR_NOTE;
			$data['MR_NOTE1']	= $getmrreq->MR_NOTE1;
							
			$this->load->view('v_production/v_material_req/v_print_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}