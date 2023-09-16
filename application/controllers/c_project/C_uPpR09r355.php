<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= C_uPpR09r355.php
 * Location		= -
*/

class C_uPpR09r355 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
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
				$PRJCODE_HO	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE_HO	= $EXP_COLLD1;
			}
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE_HO = '$PRJCODE_HO' AND PRJSTAT = 1";
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
		
		$url			= site_url('c_project/c_uPpR09r355/prjl0b28t18/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18() // G - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN225';
				$data["MenuApp"] 	= 'MN225';
				$data["MenuCode"] 	= 'MN225';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN225';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_uPpR09r355/pR09r355Lst/?id=";
			
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
	
	function pR09r355Lst() // G - List
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN225';
			$data["MenuApp"] 	= 'MN225';
			$data["MenuCode"] 	= 'MN225';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_uPpR09r355/addpR09r355/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_uPpR09r355/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			//$num_rows 			= $this->m_progress_up->count_all_WP($PRJCODE);
			//$data["countWK"] 	= $num_rows;
	 
			//$data['vwWP']		= $this->m_progress_up->get_all_WP($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN225';
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
			
			$this->load->view('v_project/v_project_progress/v_weekly_prog', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

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
			
			$columns_valid 	= array("PRJP_NUM",
									"PRJP_DATE", 
									"PRJP_STEP", 
									"", 
									"PRJP_DESC", 
									"STATDESC", 
									"CREATERNM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_progress_up->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_progress_up->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{						
				$PRJP_NUM		= $dataI['PRJP_NUM'];
				$PRJP_DATE		= $dataI['PRJP_DATE'];
				$PRJP_DATEV		= date('d M Y', strtotime($PRJP_DATE));
				$PRJP_STEP		= $dataI['PRJP_STEP'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PRJP_DESC		= $dataI['PRJP_DESC'];
				$PRJP_TOT		= $dataI['PRJP_TOT'];
				$PRJP_GTOT		= $dataI['PRJP_GTOT'];
				$PRJP_TOT_EKS	= $dataI['PRJP_TOT_EKS'];
				$PRJP_PERC_PI	= $dataI['PRJP_PERC_PI'];
				$PRJP_PERC_PEKS	= $dataI['PRJP_PERC_PEKS'];
				$PRJP_STAT		= $dataI['PRJP_STAT'];
				$PRJP_CREATER	= $dataI['PRJP_CREATER'];
				$PRJP_CREATED	= $dataI['PRJP_CREATED'];
				$PRJPDate		= date('d M Y', strtotime($PRJP_CREATED));
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				
				$Prg_Plan 		= 0;
				$Prg_PlanAkum 	= 0;
				$Prg_Step 		= '';
				$Prg_Date1 		= '';
				$Prg_Date2 		= '';
				
				$sqlProjStep	= "SELECT Prg_Plan, Prg_PlanAkum, Prg_Step, Prg_Date1, Prg_Date2
									FROM tbl_projprogres 
									WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";
				$resProjStep	= $this->db->query($sqlProjStep)->result();		
				foreach($resProjStep as $rowProjStep) :
					$Prg_Plan 		= $rowProjStep->Prg_Plan;
					$Prg_PlanAkum 	= $rowProjStep->Prg_PlanAkum;
					$Prg_Step 		= $rowProjStep->Prg_Step;
					$Prg_Date1 		= $rowProjStep->Prg_Date1;
					$Prg_Date2 		= $rowProjStep->Prg_Date2;
				endforeach;
				if($Prg_Step == '')
					$Prg_Step	= 1;
				if($Prg_Date1 == '')
					$Prg_Date1	= $PRJP_DATE;
				if($Prg_Date2 == '')
					$Prg_Date2	= $PRJP_DATE;
				
				$Start_Date = date('d M Y', strtotime($Prg_Date1));
				$End_Date	= date('d M Y', strtotime($Prg_Date2));
				$prgsPeriod	= "$Start_Date s.d<br>$End_Date";
					
				
				$secUpd		= site_url('c_project/c_uPpR09r355/up0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJP_NUM));
				$secPrint	= site_url('c_project/c_uPpR09r355/printdocument/?id='.$this->url_encryption_helper->encode_url($PRJP_NUM));
				$CollID		= "PRJP~$PRJP_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_project_progress~tbl_project_progress_det~PRJP_NUM~$PRJP_NUM~PRJCODE~$PRJCODE";
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_project_progress~tbl_project_progress_det~PRJP_NUM~$PRJP_NUM~PRJCODE~$PRJCODE";
                                    
				if($PRJP_STAT == 1 || $PRJP_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOCX(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array(//"<div style='white-space:nowrap'>".$dataI['PRJP_NUM']."</div>",
										  	$PRJP_DATEV,
										  	$PRJP_STEP,
										  	"<div style='white-space:nowrap'>".$prgsPeriod."</div>",
										  	$PRJP_DESC,
										  	"<div style='white-space:nowrap'>
										  		<span class='label label-warning' style='font-size:12px' title='Prog. Rencana'>
													".number_format($Prg_Plan, 3)."
												</span>&nbsp;
										  		<span class='label label-primary' style='font-size:12px' title='Prog. Internal'>
													".number_format($PRJP_TOT*100, 3)."
												</span>&nbsp;
												<span class='label label-success' style='font-size:12px' title='Prog. Pengakuan'>
													". number_format($PRJP_TOT_EKS*100, 3)."
												</span>
											</div>",
										  	"<div style='white-space:nowrap'>
										  		<span class='label label-warning' style='font-size:12px' title='Prog. Rencana (%)'>
													".number_format($Prg_PlanAkum, 3)."
												</span>&nbsp;
										  		<span class='label label-primary' style='font-size:12px' title='Prog. Internal (%)'>
													".number_format($PRJP_PERC_PI, 3)."
												</span>&nbsp;
												<span class='label label-success' style='font-size:12px' title='Prog. Pengakuan (%)'>
													". number_format($PRJP_PERC_PEKS, 3)."
												</span>
											</div>",
										  	$empName,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function puSA0b28t18() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['PRJCODE'];
			$THEROW		= $_GET['theRow'];
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['form_action']	= site_url('c_project/c_uPpR09r355/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['THEROW'] 		= $THEROW;
			$data['secShowAll']		= site_url('c_project/c_uPpR09r355/puSA0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllJob']	= $this->m_progress_up->count_all_Job($PRJCODE);
			$data['vwAllJob'] 		= $this->m_progress_up->view_all_Job($PRJCODE)->result();
					
			$this->load->view('v_project/v_project_progress/v_weekly_prog_seljob', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addpR09r355() 			// G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN225';
			$data["MenuApp"] 	= 'MN225';
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
			$data['form_action']= site_url('c_project/c_uPpR09r355/add_process');
			$data['backURL'] 	= site_url('c_project/c_uPpR09r355/pR09r355Lst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$data['PRJP_STEP'] 	= 1;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN225';
			$data["MenuCode"] 	= 'MN225';
			$data['vwDocPatt'] 	= $this->m_progress_up->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN225';
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
			
			$this->load->view('v_project/v_project_progress/v_weekly_prog_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PRJP_NUM		= $this->input->post('PRJP_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			
			$PRJP_DATE		= date('Y-m-d',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$PRJP_DATE_S	= date('Y-m-d',strtotime($this->input->post('PRJP_DATE_S')));
			$PRJP_DATE_E	= date('Y-m-d',strtotime($this->input->post('PRJP_DATE_E')));

			$PRJP_STEP		= $this->input->post('PRJP_STEP');
			$PRJP_DESC		= $this->input->post('PRJP_DESC');
			$PRJP_STAT 		= $this->input->post('PRJP_STAT');
			$PRJP_TOT 		= $this->input->post('PRJP_TOT');
			$PRJP_GTOT 		= $this->input->post('PRJP_GTOT');
			$PRJP_PERC_PI	= $this->input->post('PRJPRGS');
			$PRJP_CREATER	= $DefEmp_ID;
			$PRJP_CREATED 	= date('Y-m-d H:i:s');
			
			$paramPRJP 		= array('PRJP_NUM' 		=> $PRJP_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'PRJP_STEP' 	=> $PRJP_STEP,
									'PRJP_DATE' 	=> $PRJP_DATE,
									'PRJP_DATE_S' 	=> $PRJP_DATE_S,
									'PRJP_DATE_E' 	=> $PRJP_DATE_E,
									'PRJP_DESC' 	=> $PRJP_DESC,
									'PRJP_STAT' 	=> $PRJP_STAT,
									'PRJP_TOT' 		=> $PRJP_TOT,
									'PRJP_GTOT'		=> $PRJP_GTOT,
									'PRJP_PERC_PI'	=> $PRJP_PERC_PI,
									'PRJP_CREATER'	=> $PRJP_CREATER,
									'PRJP_CREATED'	=> $PRJP_CREATED,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_progress_up->add($paramPRJP);
			
			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					$JOBCODEID	= $d['JOBCODEID'];
					$PROG_PERC	= $d['PROG_PERC'];
					$PROG_VAL	= $d['PROG_VAL'];
					if($PROG_PERC != 0)
					{
						$this->db->insert('tbl_project_progress_det', $d);
					}
					
					if($PRJP_STAT == 3)
					{
						// UPDATE PROGRESS
						$this->m_progress_up->updateBOQPROG($PRJCODE, $JOBCODEID, $PRJP_TOT, $PRJP_STEP, $PROG_PERC, $PROG_VAL);
					}
				}
			}
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PRJP_NUM",
										'DOC_CODE' 		=> $PRJP_NUM,
										'DOC_STAT' 		=> $PRJP_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_project_progress");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJP_NUM;
				$MenuCode 		= 'MN225';
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
			
			$url			= site_url('c_project/c_uPpR09r355/pR09r355Lst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN226';
			$data["MenuApp"] 	= 'MN226';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJP_NUM	= $_GET['id'];
			$PRJP_NUM	= $this->url_encryption_helper->decode_url($PRJP_NUM);

			$getGEJ 		= $this->m_progress_up->get_PRJP_by_number($PRJP_NUM)->row();
			$data['default']['PRJP_NUM'] 	= $getGEJ->PRJP_NUM;
			$data['default']['PRJCODE'] 	= $getGEJ->PRJCODE;
			$PRJCODE 						= $getGEJ->PRJCODE;
			$data['default']['PRJP_DATE']	= $getGEJ->PRJP_DATE;
			$data['default']['PRJP_DATE_S']	= $getGEJ->PRJP_DATE_S;
			$data['default']['PRJP_DATE_E']	= $getGEJ->PRJP_DATE_E;
			$data['default']['PRJP_DESC'] 	= $getGEJ->PRJP_DESC;
			$data['default']['PRJP_STEP']	= $getGEJ->PRJP_STEP;
			$data['default']['PRJP_TOT'] 	= $getGEJ->PRJP_TOT;
			$data['default']['PRJP_GTOT'] 	= $getGEJ->PRJP_GTOT;
			$data['default']['PRJP_PERC_PI']= $getGEJ->PRJP_PERC_PI;
			$data['default']['PRJP_STAT'] 	= $getGEJ->PRJP_STAT;
			$data['default']['Patt_Number'] = $getGEJ->Patt_Number;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_uPpR09r355/update_process');
			$data['backURL'] 	= site_url('c_project/c_uPpR09r355/pR09r355Lst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN225';
			$data["MenuCode"] 	= 'MN225';
			$data['vwDocPatt'] 	= $this->m_progress_up->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN225';
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
			
			$this->load->view('v_project/v_project_progress/v_weekly_prog_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PRJP_NUM		= $this->input->post('PRJP_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			
			$PRJP_DATE		= date('Y-m-d',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$PRJP_DATE_S	= date('Y-m-d',strtotime($this->input->post('PRJP_DATE_S')));
			$PRJP_DATE_E	= date('Y-m-d',strtotime($this->input->post('PRJP_DATE_E')));
			
			$PRJP_STEP		= $this->input->post('PRJP_STEP');
			$PRJP_DESC		= $this->input->post('PRJP_DESC');
			$PRJP_STAT 		= $this->input->post('PRJP_STAT');
			$PRJP_TOT 		= $this->input->post('PRJP_TOT');
			$PRJP_GTOT 		= $this->input->post('PRJP_GTOT');
			$PRJPRGS 		= $this->input->post('PRJPRGS');
			$PRJP_PERC_PI	= $this->input->post('PRJPRGS');
			$PRJP_APPROVER	= $DefEmp_ID;
			$PRJP_APPROVED 	= date('Y-m-d H:i:s');
			
			$AH_CODE		= $PRJP_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $PRJP_APPROVED;
			$AH_NOTES		= $this->input->post('PRJP_DESC');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// UPDATE JOBDETAIL ITEM
			if($PRJP_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
							
				$paramPRJP 		= array('PRJP_STAT' => 7);
				$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PRJP_NUM",
											'DOC_CODE' 		=> $PRJP_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_project_progress");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				if($AH_ISLAST == 1)
				{
					$paramPRJP 		= array('PRJP_STAT' => $PRJP_STAT);
					$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "PRJP_NUM",
												'DOC_CODE' 		=> $PRJP_NUM,
												'DOC_STAT' 		=> $PRJP_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_project_progress");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS

					// UPDATE PROGRESS
						foreach($_POST['data'] as $d)
						{
							$JOBCODEID	= $d['JOBCODEID'];
							$PROG_BEF	= $d['PROG_BEF'];
							$PROG_PERC	= $d['PROG_PERC'];
							$PROG_VAL	= $d['PROG_VAL'];
							$BOQ_BOBOT	= $d['BOQ_BOBOT'];
							$PROG_AKUM 	= $PROG_BEF + $PROG_PERC;
							$PROG_AKUMP = $PROG_AKUM / $BOQ_BOBOT * 100;

							$this->m_progress_up->updateBOQPROG($PRJCODE, $JOBCODEID, $PROG_AKUM, $PROG_AKUMP, $PRJP_STEP, $PROG_BEF, $PROG_PERC, $PROG_VAL);
						}
		
					$Prg_LastUpdate	= date('Y-m-d H:i:s');
					/*$TOT_RAKUM 		= 0;
					$s_rAkum		= "SELECT SUM(Prg_Real) AS TOT_RAKUM FROM tbl_projprogres WHERE proj_Code = '$PRJCODE' AND Prg_Step <= $PRJP_STEP";
					$r_rAkum 	= $this->db->query($s_rAkum)->result();
					foreach($r_rAkum as $rw_rAkum) :
						$TOT_RAKUM = $rw_rAkum->TOT_RAKUM;
					endforeach;*/
					
					/*$s_00 = "UPDATE tbl_projprogres SET Prg_Real = $PRJP_TOT*100, Prg_RealAkum = $PRJP_GTOT*100, Prg_Dev = Prg_RealAkum - Prg_PlanAkum,
								isShowRA = 1, isShowDev = 1, lastStepPS = 1, Prg_LastUpdate = '$Prg_LastUpdate'
							WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";*/
					
					$s_00 = "UPDATE tbl_project_progress SET PRJP_TOT = $PRJP_TOT, PRJP_GTOT = $PRJP_GTOT
								WHERE PRJCODE = '$PRJCODE' AND PRJP_STEP = $PRJP_STEP";
					$this->db->query($s_00);
					
					$s_00 = "UPDATE tbl_projprogres SET Prg_Real = $PRJP_TOT, Prg_RealAkum = $PRJP_GTOT, Prg_Dev = Prg_RealAkum - Prg_PlanAkum,
									isShowRA = 1, isShowDev = 1, lastStepPS = 1, Prg_LastUpdate = '$Prg_LastUpdate'
								WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";
					$this->db->query($s_00);

					$s_01 		= "UPDATE tbl_project SET PRJPROG = $PRJPRGS WHERE PRJCODE = '$PRJCODE'";
					$this->db->query($s_01);
				}
			}
			elseif($PRJP_STAT == 1 || $PRJP_STAT == 2)
			{
				$paramPRJP 		= array('PRJP_STEP' 	=> $PRJP_STEP,
										'PRJP_DATE' 	=> $PRJP_DATE,
										'PRJP_DATE_S' 	=> $PRJP_DATE_S,
										'PRJP_DATE_E' 	=> $PRJP_DATE_E,
										'PRJP_DESC' 	=> $PRJP_DESC,
										'PRJP_STAT' 	=> $PRJP_STAT,
										'PRJP_TOT' 		=> $PRJP_TOT,
										'PRJP_GTOT'		=> $PRJP_GTOT,
										'PRJP_PERC_PI' 	=> $PRJP_PERC_PI);
				$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PRJP_NUM",
											'DOC_CODE' 		=> $PRJP_NUM,
											'DOC_STAT' 		=> $PRJP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_project_progress");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				$this->m_progress_up->deletePRPJDet($PRJP_NUM);
				
				if(isset($_POST['data']))
				{
					foreach($_POST['data'] as $d)
					{
						$JOBCODEID	= $d['JOBCODEID'];
						$PROG_PERC	= $d['PROG_PERC'];
						if($PROG_PERC != 0)
						{
							$this->db->insert('tbl_project_progress_det', $d);
						}
					}
				}
			}
			else
			{
				$paramPRJP 		= array('PRJP_STEP' 	=> $PRJP_STEP,
										'PRJP_DATE' 	=> $PRJP_DATE,
										'PRJP_DATE_S' 	=> $PRJP_DATE_S,
										'PRJP_DATE_E' 	=> $PRJP_DATE_E,
										'PRJP_DESC' 	=> $PRJP_DESC,
										'PRJP_STAT' 	=> $PRJP_STAT,
										'PRJP_TOT' 		=> $PRJP_TOT,
										'PRJP_GTOT'		=> $PRJP_GTOT,
										'PRJP_PERC_PI'	=> $PRJP_PERC_PI);
				$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PRJP_NUM",
											'DOC_CODE' 		=> $PRJP_NUM,
											'DOC_STAT' 		=> $PRJP_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_project_progress");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			$s_02 		= "UPDATE tbl_project_progress SET PRJP_PERC_PI = $PRJPRGS WHERE PRJCODE = '$PRJCODE' AND PRJP_NUM = '$PRJP_NUM'";
			$this->db->query($s_02);
		
			$s_03 		= "UPDATE tbl_projprogres SET isShowRA = 1, isShowDev = 1, lastStepPS = 1 WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";
			$this->db->query($s_03);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJP_NUM;
				$MenuCode 		= 'MN225';
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
			
			$url			= site_url('c_project/c_uPpR09r355/pR09r355Lst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function gRpR09r355()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_uPpR09r355/gRpR09r355x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function gRpR09r355x()
	{
		$this->load->model('m_project/m_listproject/m_listproject', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			// GET MENU DESC
				$mnCode				= 'MN226';
				$data["MenuApp"] 	= 'MN226';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['title'] 			= $appName;
			$data['h2_title']		= 'Project Progress';
			$data['h3_title']		= 'Project';
			$data['form_action1']	= site_url('c_project/c_uPpR09r355/add_process/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action2'] 	= site_url('c_project/c_uPpR09r355/showImages/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['form_action3'] 	= site_url('c_project/c_uPpR09r355/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$projCode			= '';
			$getCount			= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$EmpID')";
			$resGetCount		= $this->db->count_all($getCount);		
			if($resGetCount > 0)
			{
				$getData		= "SELECT PRJCODE FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$EmpID')";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->PRJCODE;
				endforeach;
			}
			
			$MenuCode 				= 'MN226';
			$data['MenuCode'] 		= 'MN226';
			
			$data['projCode'] 		= $projCode;	// PRJCODE FEAULL
			$data['progressType'] 	= 3;
			$data['progress_Step'] 	= 1;
			$data['Emp_ID'] 		= $EmpID;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $projCode;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN226';
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
			
			$this->load->view('v_project/v_project_progress/v_project_progress', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function UpL_f1l3()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_uPpR09r355/UpL_f1l3x/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function UpL_f1l3x() // G - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN332';
			$data["MenuApp"] 	= 'MN332';
			$data["MenuCode"] 	= 'MN332';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

		if ($this->session->userdata('login') == TRUE)
		{			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= $getMN->menu_name_IND;
			}
			else
			{
				$data["h1_title"] 	= $getMN->menu_name_ENG;
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN332';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_uPpR09r355/UpL_f1l31/?id=";
			
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
	
	function UpL_f1l31() // GOOD
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
			
		// GET MENU DESC
			$mnCode				= 'MN422';
			$data["MenuApp"] 	= 'MN422';
			$data["MenuCode"] 	= 'MN422';
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
			
			$backURL	= site_url('c_project/c_uPpR09r355/UpL_f1l3/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['PROGG_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master progress';
			$data['form_action']	= site_url('c_project/c_uPpR09r355/do_upload');
			$data['backURL'] 		= $backURL;
			
			$this->load->view('v_project/v_project_progress/v_prog_upload_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload() // GOOD
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$PROGG_DATE		= date('Y-m-d H:i:s');
			$PROGG_DATEY	= date('Y');
			$PROGG_DATEM	= date('m');
			$PROGG_DATED	= date('d');
			$PROGG_DATEH	= date('H');
			$PROGG_DATEm	= date('i');
			$PROGG_DATES	= date('s');
			
			$PROGG_CODE		= "PROGG$PROGG_DATEY$PROGG_DATEM$PROGG_DATED-$PROGG_DATEH$PROGG_DATEm$PROGG_DATES";
			$PROGG_DATE		= date('Y-m-d H:i:s');
			$PROGG_PRJCODE	= $this->input->post('PRJCODE');
			$PROGG_DESC		= $this->input->post('PROGG_DESC');
			$PROGG_USER		= $DefEmp_ID;
			$PROGG_STAT		= 1;
			
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
					
			$filename 	= $_FILES["userfile"]["name"];
			$source 	= $_FILES["userfile"]["tmp_name"];
			$type 		= $_FILES["userfile"]["type"];
			
			$name 		= explode(".", $filename);
			$fileExt	= $name[1];
			
			//$target_path = "import_excel/import_proggress/".$filename;  // change this to the correct site path
			//$myPath 	= "import_excel/import_proggress/$filename";

			$target_path 	= "application/xlsxfile/import_scurve/".$filename;  // change this to the correct site path					
			$myPath 		= "application/xlsxfile/import_scurve/$filename";
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
			
			$data['isUploaded']	= 1;	
			if(move_uploaded_file($source, $target_path))
			{
				$message = "Your file was uploaded";
				$data['message'] 	= $message;
				$data['isSuccess']	= 1;
				$data['PROGG_DESC']	= $PROGG_DESC;
				
				$ProggHist 	= array('PROGG_CODE' 	=> $PROGG_CODE,
									'PROGG_DATE'	=> $PROGG_DATE,
									'PROGG_PRJCODE'	=> $PROGG_PRJCODE,
									'PROGG_DESC'	=> $PROGG_DESC,
									'PROGG_FN'		=> $filename,
									'PROGG_USER'	=> $PROGG_USER,
									'PROGG_STAT'	=> $PROGG_STAT);
				$this->m_progress_up->add_importprogg($ProggHist);
			} 
			else 
			{	
				$message = "There was a problem with the upload. Please try again.";
				$data['message'] 	= $message;
				$data['isSuccess']	= 0;
				$data['PROGG_DESC']	= $PROGG_DESC;
			}
			
			$backURL	= site_url('c_project/c_uPpR09r355/UpL_f1l3/?id='.$this->url_encryption_helper->encode_url($PROGG_PRJCODE));
			$data['isProcess'] 		= 1;
			$data['PRJCODE']		= $PROGG_PRJCODE;
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master item';
			$data['form_action']	= site_url('c_project/c_uPpR09r355/do_upload');
			$data['backURL'] 		= $backURL;
						
			$url	= site_url('c_project/c_uPpR09r355/UpL_f1l31/?id='.$this->url_encryption_helper->encode_url($PROGG_PRJCODE));
			redirect($url);
		}
	}
	
	function view_itemup() // OK
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PROGG_CODE	= $_GET['id'];
			$PROGG_CODE	= $this->url_encryption_helper->decode_url($PROGG_CODE);
			
			$sqlPRJ		= "SELECT PROGG_PRJCODE FROM tbl_progg_uphist WHERE PROGG_CODE = '$PROGG_CODE'";
			$sqlPRJR	= $this->db->query($sqlPRJ)->result();
			foreach($sqlPRJR as $rowPRJ) :
				$PROGG_PRJ		= $rowPRJ->PROGG_PRJCODE;
			endforeach;
	
			$data['PRJCODE']		= $PROGG_PRJ;
			$data['PROGG_CODE']		= $PROGG_CODE;
			$data['title'] 			= $this->data['appName'];
			$data['h2_title']		= 'View';
			$data['h3_title'] 		= 'master item';
			
			$this->load->view('v_inventory/v_itemlist/v_item_view_xl', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function downloadFile()
	{
		$this->load->helper('download');

		$collLink	= $_GET['id'];
		$collLink	= $this->url_encryption_helper->decode_url($collLink);
		$collLink1	= explode('~', $collLink);
		$theLink	= $collLink1[0];
		$FileUpName	= $collLink1[1];
		header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);
	}
	
 	function ekst() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_uPpR09r355/prjl0b28t18_ekst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18_ekst() // G - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN111';
				$data["MenuApp"] 	= 'MN111';
				$data["MenuCode"] 	= 'MN111';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN111';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_uPpR09r355/pR09r355Lst_ekst/?id=";
			
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
	
	function pR09r355Lst_ekst() // G - List
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN111';
			$data["MenuApp"] 	= 'MN111';
			$data["MenuCode"] 	= 'MN111';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_uPpR09r355/addpR09r355/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_uPpR09r355/ekst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			//$num_rows 			= $this->m_progress_up->count_all_WP($PRJCODE);
			//$data["countWK"] 	= $num_rows;
	 
			//$data['vwWP']		= $this->m_progress_up->get_all_WP($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN111';
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
			
			$this->load->view('v_project/v_project_progress/v_weekly_prog_ekst', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataEkst() // GOOD
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
			
			$columns_valid 	= array("PRJP_NUM",
									"PRJP_DATE", 
									"PRJP_STEP", 
									"", 
									"PRJP_DESC", 
									"STATDESC", 
									"CREATERNM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_progress_up->get_AllDataEkstC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_progress_up->get_AllDataEkstL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{						
				$PRJP_NUM		= $dataI['PRJP_NUM'];
				$PRJP_DATE		= $dataI['PRJP_DATE'];
				$PRJP_DATEV		= date('d M Y', strtotime($PRJP_DATE));
				$PRJP_STEP		= $dataI['PRJP_STEP'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PRJP_DESC		= $dataI['PRJP_DESC'];
				$PRJP_TOT		= $dataI['PRJP_TOT'];
				$PRJP_GTOT		= $dataI['PRJP_GTOT'];
				$PRJP_STAT		= $dataI['PRJP_STAT'];
				$PRJP_STAT_EKS	= $dataI['PRJP_STAT_EKS'];
				$PRJP_CREATER	= $dataI['PRJP_CREATER'];
				$PRJP_CREATED	= $dataI['PRJP_CREATED'];
				$PRJPDate		= date('d M Y', strtotime($PRJP_CREATED));
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);

				if($PRJP_STAT_EKS == 1)
				{
					$STATDESC	= "New";
					$STATCOL	= "warning";
				}
				elseif($PRJP_STAT_EKS == 2)
				{
					$STATDESC	= "Confirmed";
					$STATCOL	= "primary";
				}
				elseif($PRJP_STAT_EKS == 3)
				{
					$STATDESC	= "Approved";
					$STATCOL	= "success";
				}
				elseif($PRJP_STAT_EKS == 4)
				{
					$STATDESC	= "Revised";
					$STATCOL	= "danger";
				}
				elseif($PRJP_STAT_EKS == 5)
				{
					$STATDESC	= "Rejected";
					$STATCOL	= "danger";
				}
				elseif($PRJP_STAT_EKS == 6)
				{
					$STATDESC	= "Closed";
					$STATCOL	= "info";
				}
				elseif($PRJP_STAT_EKS == 7)
				{
					$STATDESC	= "Waiting";
					$STATCOL	= "warning";
				}
				elseif($PRJP_STAT_EKS == 9)
				{
					$STATDESC	= "Void";
					$STATCOL	= "danger";
				}
				else
				{
					$STATDESC	= "fake";
					$STATCOL	= "danger";
				}
				
				$Prg_Step 		= '';
				$Prg_Date1 		= '';
				$Prg_Date2 		= '';
				
				$sqlProjStep	= "SELECT Prg_Step, Prg_Date1, Prg_Date2
									FROM tbl_projprogres 
									WHERE proj_Code = '$PRJCODE' AND Prg_Step = $PRJP_STEP";
				$resProjStep	= $this->db->query($sqlProjStep)->result();		
				foreach($resProjStep as $rowProjStep) :
					$Prg_Step 		= $rowProjStep->Prg_Step;
					$Prg_Date1 		= $rowProjStep->Prg_Date1;
					$Prg_Date2 		= $rowProjStep->Prg_Date2;
				endforeach;
				if($Prg_Step == '')
					$Prg_Step	= 1;
				if($Prg_Date1 == '')
					$Prg_Date1	= $PRJP_DATE;
				if($Prg_Date2 == '')
					$Prg_Date2	= $PRJP_DATE;
				
				$Start_Date = date('d M Y', strtotime($Prg_Date1));
				$End_Date	= date('d M Y', strtotime($Prg_Date2));
				$prgsPeriod	= "$Start_Date s.d<br>$End_Date";
					
				
				$secUpd		= site_url('c_project/c_uPpR09r355/up0b28t18_ekst/?id='.$this->url_encryption_helper->encode_url($PRJP_NUM));
				$secPrint	= site_url('c_project/c_uPpR09r355/printdocument/?id='.$this->url_encryption_helper->encode_url($PRJP_NUM));
				$CollID		= "PRJP~$PRJP_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_project_progress~tbl_project_progress_det~PRJP_NUM~$PRJP_NUM~PRJCODE~$PRJCODE";
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_project_progress~tbl_project_progress_det~PRJP_NUM~$PRJP_NUM~PRJCODE~$PRJCODE";
                                    
				if($PRJP_STAT == 1 || $PRJP_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOCX(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array(//"<div style='white-space:nowrap'>".$dataI['PRJP_NUM']."</div>",
										  $PRJP_DATEV,
										  $PRJP_STEP,
										  "<div style='white-space:nowrap'>".$prgsPeriod."</div>",
										  $PRJP_DESC,
										  number_format($PRJP_TOT, 3),
										  number_format($PRJP_GTOT, 3),
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function up0b28t18_ekst() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN111';
			$data["MenuApp"] 	= 'MN111';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJP_NUM	= $_GET['id'];
			$PRJP_NUM	= $this->url_encryption_helper->decode_url($PRJP_NUM);

			$getGEJ 		= $this->m_progress_up->get_PRJP_by_number($PRJP_NUM)->row();
			$data['default']['PRJP_NUM'] 		= $getGEJ->PRJP_NUM;
			$data['default']['PRJCODE'] 		= $getGEJ->PRJCODE;
			$PRJCODE 							= $getGEJ->PRJCODE;
			$data['default']['PRJP_DATE']		= $getGEJ->PRJP_DATE;
			$data['default']['PRJP_DATE_S']		= $getGEJ->PRJP_DATE_S;
			$data['default']['PRJP_DATE_E']		= $getGEJ->PRJP_DATE_E;
			$data['default']['PRJP_DESC'] 		= $getGEJ->PRJP_DESC;
			$data['default']['PRJP_STEP']		= $getGEJ->PRJP_STEP;
			$data['default']['PRJP_TOT'] 		= $getGEJ->PRJP_TOT;
			$data['default']['PRJP_GTOT'] 		= $getGEJ->PRJP_GTOT;
			$data['default']['PRJP_TOT_EKS'] 	= $getGEJ->PRJP_TOT_EKS;
			$data['default']['PRJP_GTOT_EKS'] 	= $getGEJ->PRJP_GTOT_EKS;
			$data['default']['PRJP_STAT'] 		= $getGEJ->PRJP_STAT;
			$data['default']['PRJP_STAT_EKS'] 	= $getGEJ->PRJP_STAT_EKS;
			$data['default']['PRJP_PERC_PI'] 	= $getGEJ->PRJP_PERC_PI;
			$data['default']['PRJP_PERC_PEKS'] 	= $getGEJ->PRJP_PERC_PEKS;
			$data['default']['Patt_Number'] 	= $getGEJ->Patt_Number;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_uPpR09r355/update_process_ekst');
			$data['backURL'] 	= site_url('c_project/c_uPpR09r355/pR09r355Lst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN111';
			$data["MenuCode"] 	= 'MN111';
			$data['vwDocPatt'] 	= $this->m_progress_up->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN111';
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
			
			$this->load->view('v_project/v_project_progress/v_weekly_prog_ekst_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_ekst() // G
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PRJP_NUM		= $this->input->post('PRJP_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			
			$PRJP_DATE		= date('Y-m-d',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('PRJP_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$PRJP_DATE_S	= date('Y-m-d',strtotime($this->input->post('PRJP_DATE_S')));
			$PRJP_DATE_E	= date('Y-m-d',strtotime($this->input->post('PRJP_DATE_E')));
			
			$PRJP_STEP		= $this->input->post('PRJP_STEP');
			$PRJP_DESC		= $this->input->post('PRJP_DESC');
			$PRJP_STAT 		= $this->input->post('PRJP_STAT');
			$PRJP_STAT_EKS 	= $this->input->post('PRJP_STAT_EKS');
			$PRJP_TOT 		= $this->input->post('PRJP_TOT');
			$PRJP_GTOT 		= $this->input->post('PRJP_GTOT');
			$PRJP_TOT_EKS 	= $this->input->post('PRJP_TOT_EKS');
			$PRJP_GTOT_EKS 	= $this->input->post('PRJP_GTOT_EKS');
			$PRJPRGS 		= $this->input->post('PRJPRGS');
			$PRJP_PERC_PEKS = $this->input->post('PRJPRGS');
			$PRJP_APPROVER	= $DefEmp_ID;
			$PRJP_APPROVED 	= date('Y-m-d H:i:s');
			
			$AH_CODE		= $PRJP_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $PRJP_APPROVED;
			$AH_NOTES		= $this->input->post('PRJP_DESC');
			$AH_ISLAST		= $this->input->post('IS_LAST');
		
			/*$paramPRJP 		= array('PRJP_STEP' 	=> $PRJP_STEP,
									'PRJP_DATE' 	=> $PRJP_DATE,
									'PRJP_DATE_S' 	=> $PRJP_DATE_S,
									'PRJP_DATE_E' 	=> $PRJP_DATE_E,
									'PRJP_DESC' 	=> $PRJP_DESC,
									'PRJP_STAT_EKS' => $PRJP_STAT_EKS,
									'PRJP_TOT' 		=> $PRJP_TOT,
									'PRJP_GTOT'		=> $PRJP_GTOT,
									'PRJP_TOT_EKS' 	=> $PRJP_TOT_EKS,
									'PRJP_GTOT_EKS'	=> $PRJP_GTOT_EKS);*/
			$paramPRJP 		= array('PRJP_STAT_EKS' => $PRJP_STAT_EKS,
									'PRJP_TOT_EKS' 	=> $PRJP_TOT,
									'PRJP_GTOT_EKS'	=> $PRJP_GTOT,
									'PRJP_PERC_PEKS'=> $PRJP_PERC_PEKS);
			$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);

			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					$JOBCODEID		= $d['JOBCODEID'];
					$PROG_VAL_EKS	= $d['PROG_VAL_EKS'];
					$PROG_PERC_EKS	= $d['PROG_PERC_EKS'];
					if($PROG_PERC_EKS != 0)
					{
						$s_00 	= "UPDATE tbl_project_progress_det SET PROG_VAL_EKS = $PROG_VAL_EKS, PROG_PERC_EKS = $PROG_PERC_EKS
									WHERE JOBCODEID = '$JOBCODEID' AND PRJP_NUM = '$PRJP_NUM' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_00);
					}
				}
			}

			$s_01 	= "UPDATE tbl_project_progress SET PRJP_TOT_EKS = $PRJP_TOT, PRJP_GTOT_EKS = $PRJP_GTOT
						WHERE PRJP_NUM = '$PRJP_NUM' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_01);

			// UPDATE JOBDETAIL ITEM
			if($PRJP_STAT_EKS == 3)
			{
				$paramPRJP 		= array('PRJP_STAT_EKS' => 7);
				$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);

				if($AH_ISLAST == 1)
				{
					$paramPRJP 		= array('PRJP_STAT_EKS' => $PRJP_STAT_EKS);
					$this->m_progress_up->updatePRPJ($PRJP_NUM, $paramPRJP);

					// UPDATE PROGRESS
						if(isset($_POST['data']))
						{
							foreach($_POST['data'] as $d)
							{
								$JOBCODEID		= $d['JOBCODEID'];
								$PROG_PERC_EKS	= $d['PROG_PERC_EKS'];
								$PROG_VAL_EKS	= $d['PROG_VAL_EKS'];

								$this->m_progress_up->updateBOQPROG_EKS($PRJCODE, $JOBCODEID, $PROG_PERC_EKS, $PROG_VAL_EKS);
							}
						}
				}
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PRJP_NUM;
				$MenuCode 		= 'MN111';
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
			
			$url			= site_url('c_project/c_uPpR09r355/pR09r355Lst_ekst/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataJL_230121() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$PRJP_NUM	= $_GET['PRNUM'];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

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
									"ITM_UNIT", 
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
			$num_rows 		= $this->m_progress_up->get_AllDataJLC($PRJCODE, $search, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_progress_up->get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= htmlspecialchars($dataI['JOBDESC'], ENT_QUOTES);
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$ISLASTH		= $dataI['ISLASTH'];

				/*$JOBVOLM		= $dataI['ITM_VOLM'];
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBCOST		= $dataI['ITM_BUDG'];*/
				$JOBVOLM		= $dataI['BOQ_VOLM'];
				//$ITM_VOLMBG	= $dataI['BOQ_VOLM'];
				$JOBCOST		= $dataI['BOQ_JOBCOST'];
				//$PROG_VAL		= $dataI['PROG_VAL'];
				//$PROG_PERC	= $dataI['PROG_PERC'];
				$BOQ_BOBOT		= $dataI['BOQ_BOBOT'];

				// if($JOBVOLM == 0)
				// 	$JOBVOLM	= $BOQ_BOBOT;

				$JOBPARDESC	= 	"";
				$sqlJPAR	= 	"SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
		        $resJPAR	= $this->db->query($sqlJPAR)->result();
		        foreach($resJPAR as $rowJPAR) :
		            $JOBPARDESC	= $rowJPAR->JOBDESC;
		        endforeach;
				$JOBDESCH 	= $JOBPARDESC;
				$strLENH 	= strlen($JOBDESCH);
				$JOBDESCHA	= wordwrap($JOBDESCH, 50, "<br>", TRUE);
				$JOBDESCH 	= $JOBDESCHA;
				if($strLENH > 50)
					$JOBDESCH 	= $JOBDESCHA;

				// IS LAST SETT
					if($ISLASTH == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1	= wordwrap($JOBDESC, 50, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1	= wordwrap($JOBDESC, 50, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
					}
											
				$TOT_PINT		= 0;
				$TOT_PERCI 		= 0;
				$TOT_PEKS 		= 0;
				$sqlTOTBEF		= "SELECT SUM(PROG_VAL) AS TOT_PINT, SUM(PROG_PERC) AS TOT_PERCI, SUM(PROG_VAL_EKS) AS TOT_PEKS  FROM tbl_project_progress_det A 
									INNER JOIN tbl_project_progress B ON A.PRJP_NUM = B.PRJP_NUM
									WHERE JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.PRJP_STAT = 3
									AND A.PRJP_NUM != '$PRJP_NUM'";
				$resTOTBEF 		= $this->db->query($sqlTOTBEF)->result();
				foreach($resTOTBEF as $rowTOTBEF) :
					$TOT_PINT 	= $rowTOTBEF->TOT_PINT;
					$TOT_PERCI 	= $rowTOTBEF->TOT_PERCI;
					$TOT_PEKS 	= $rowTOTBEF->TOT_PEKS;
				endforeach;
				if($TOT_PINT == '')
					$TOT_PINT	= 0;
				if($TOT_PERCI == '')
					$TOT_PERCI	= 0;
				if($TOT_PEKS == '')
					$TOT_PEKS	= 0;
				
				$isLSQ		= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$isLSR		= $this->db->count_all($isLSQ);

				$JOBVOLV 	= $JOBVOLM;
				if($isLSR > 0)
				{
					$JOBVOLV	= $JOBCOST;
				}

				// OTHER SETT
					/*if($disabledB == 0)
					{*/
						/*$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEID."|".$JOBDESC."|".$JOBPARENT."|".$JOBDESCHA."|".$PRJCODE."|".$ITM_UNIT."|".$JOBVOLM."|".$JOBCOST."|".$TOT_PINT."|".$PROG_PERC."|".$BOQ_BOBOT."|".number_format($JOBVOLM, 2, '.', '')."|".number_format($JOBCOST, 2, '.', '')."|".number_format($PROG_VAL, 2, '.', '')."' onClick='pickThis1(this);'/>";*/
						$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEID."|".$JOBDESC."|".$JOBPARENT."|".$JOBDESCHA."|".$PRJCODE."|".$ITM_UNIT."|".$JOBVOLV."|".$JOBCOST."|".$TOT_PINT."|".$TOT_PERCI."|".$BOQ_BOBOT."|".number_format($JOBVOLM, 2, '.', '')."|".number_format($JOBCOST, 2, '.', '')."|".number_format($TOT_PINT, 2, '.', '')."' onClick='pickThis1(this);'/>";
					/*}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='' style='display: none' />";
					}*/

					$JobView		= "$JOBPARENT : $JOBPARDESC";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">$JOBCODEID : $JOBDESC1</span></p>
										  	</div>
										  	<div style='font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;".strtoupper($JobView)."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($BOQ_BOBOT, 6)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($TOT_PINT, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($TOT_PEKS, 2)."</span></div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataJL() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$PRJP_NUM	= $_GET['PRNUM'];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

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
									"ITM_UNIT", 
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
			$num_rows 		= $this->m_progress_up->get_AllDataJLC($PRJCODE, $search, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_progress_up->get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= htmlspecialchars($dataI['JOBDESC'], ENT_QUOTES);
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_UNIT		= htmlspecialchars($dataI['ITM_UNIT'], ENT_QUOTES);
				$ISLASTH		= $dataI['ISLASTH'];

				$JOBVOLM		= $dataI['BOQ_VOLM'];
				$JOBCOST		= $dataI['BOQ_JOBCOST'];
				$BOQ_BOBOT		= $dataI['BOQ_BOBOT'];
				$AMD_VOL 		= $dataI['AMD_VOL'];
				$AMD_VAL		= $dataI['AMD_VAL'];

				$JOBPARDESC	= 	"";
				$sqlJPAR	= 	"SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
		        $resJPAR	= $this->db->query($sqlJPAR)->result();
		        foreach($resJPAR as $rowJPAR) :
		            $JOBPARDESC	= $rowJPAR->JOBDESC;
		        endforeach;
				$JOBDESCH 	= $JOBPARDESC;
				$strLENH 	= strlen($JOBDESCH);
				$JOBDESCHA	= wordwrap($JOBDESCH, 50, "<br>", TRUE);
				$JOBDESCH 	= $JOBDESCHA;
				if($strLENH > 50)
					$JOBDESCH 	= $JOBDESCHA;

				// IS LAST SETT
					if($ISLASTH == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1	= wordwrap($JOBDESC, 50, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1	= wordwrap($JOBDESC, 50, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
					}
											
				$TOT_PINT		= 0;
				$TOT_PERCI 		= 0;
				$TOT_PEKS 		= 0;
				$sqlTOTBEF		= "SELECT SUM(PROG_VAL) AS TOT_PINT, SUM(PROG_PERC) AS TOT_PERCI, SUM(PROG_VAL_EKS) AS TOT_PEKS  FROM tbl_project_progress_det A 
									INNER JOIN tbl_project_progress B ON A.PRJP_NUM = B.PRJP_NUM
									WHERE JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' AND B.PRJP_STAT = 3
									AND A.PRJP_NUM != '$PRJP_NUM'";
				$resTOTBEF 		= $this->db->query($sqlTOTBEF)->result();
				foreach($resTOTBEF as $rowTOTBEF) :
					$TOT_PINT 	= $rowTOTBEF->TOT_PINT;
					$TOT_PERCI 	= $rowTOTBEF->TOT_PERCI;
					$TOT_PEKS 	= $rowTOTBEF->TOT_PEKS;
				endforeach;
				if($TOT_PINT == '')
					$TOT_PINT	= 0;
				if($TOT_PERCI == '')
					$TOT_PERCI	= 0;
				if($TOT_PEKS == '')
					$TOT_PEKS	= 0;
				
				$isLSQ		= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$isLSR		= $this->db->count_all($isLSQ);

				$ADDVOL_VW 		= "";
				if($AMD_VOL > 0 || $AMD_VAL > 0)
				{
					$ADDVOL_VW 	= 	"<div>
										<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
										".number_format($AMD_VOL, 2)."</p>
									</div>";
				}

				$JOBVOLV 	= $JOBVOLM;
				if($isLSR > 0)
				{
					$JOBVOLV	= $JOBCOST;
				}

				$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEID."|".$JOBDESC."|".$JOBPARENT."|".$JOBDESCHA."|".$PRJCODE."|".$ITM_UNIT."|".$JOBVOLV."|".$JOBCOST."|".$TOT_PINT."|".$TOT_PERCI."|".$BOQ_BOBOT."|".number_format($JOBVOLM, 2, '.', '')."|".number_format($JOBCOST, 2, '.', '')."|".number_format($TOT_PINT, 2, '.', '')."|".$AMD_VOL."|".$AMD_VAL."' onClick='pickThis1(this);'/>";

				$JobView		= "$JOBPARENT : $JOBPARDESC";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">$JOBCODEID : $JOBDESC1</span></p>
										  	</div>
										  	<div style='font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;".strtoupper($JobView)."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($BOQ_BOBOT, 6)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLV, 2)."</span>".$ADDVOL_VW."</div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($TOT_PINT, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($TOT_PEKS, 2)."</span></div>");

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A = $isLSQ",
										"B",
										"C",
										"D",
										"E",
										"E",
										"E");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}