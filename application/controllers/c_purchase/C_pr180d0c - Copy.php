<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Oktober 2017
 * File Name	= C_pr180d0c.php
 * Location		= -
*/
class C_pr180d0c extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		$this->data['nSELP']		= $this->session->userdata['nSELP'];
		
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
		
		$url			= site_url('c_purchase/c_pr180d0c/pL15t/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pL15t() // G
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
				$mnCode				= 'MN017';
				$data["MenuApp"] 	= 'MN018';
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
			$data["secURL"] 	= "c_purchase/c_pr180d0c/pRQ_l5t_x/?id=";
			
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
	
	function pRQ_l5t_x() // G
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
				$data["url_search"] = site_url('c_purchase/c_pr180d0c/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_purchase_req->count_all_num_rowsPR($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_req->get_all_PR($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN017';
				$data["MenuCode"] 	= 'MN017';
				$data["MenuApp"] 	= 'MN018';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_pr180d0c/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN017';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req', $data);
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
			$url			= site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("PR_ID",
									"PR_CODE", 
									"PR_DATE", 
									"PR_NOTE", 
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
			$num_rows 		= $this->m_purchase_req->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_req->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PR_NUM		= $dataI['PR_NUM'];
				$PR_CODE	= $dataI['PR_CODE'];
				
				$PR_DATE	= $dataI['PR_DATE'];
				$PR_DATEV	= date('d M Y', strtotime($PR_DATE));
				
				$JOBCODE	= $dataI['JOBCODE'];
				$JOBDESC	= $dataI['JOBDESC'];
				$PR_NOTE	= $dataI['PR_NOTE'];
				$JOBDESC	= "$PR_NOTE";
				$PR_STAT	= $dataI['PR_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				//$empName	= cut_text2 ("$CREATERNM", 15);
				$REQUESTER	= $dataI['PR_REQUESTER'];
				$empName	= cut_text2 ("$REQUESTER", 15);
				if($empName == '')
					$empName= "-";
				
				$CollCode	= "$PRJCODE~$PR_NUM";
				$secUpd		= site_url('c_purchase/c_pr180d0c/update/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPPOList	= site_url('c_purchase/c_pr180d0c/pRn_P0l/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
				$secPrint	= site_url('c_purchase/c_pr180d0c/printdocument/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
				$CollID		= "PR~$PR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_pr_header~tbl_pr_detail~PR_NUM~$PR_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPR/?id=';
				$voidID 	= "$secVoid~tbl_pr_header~tbl_pr_detail~PR_NUM~$PR_NUM~PRJCODE~$PRJCODE";

				// CEK PO
					$sqlPOC	= "tbl_po_header WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM' AND PO_STAT NOT IN (5,9)";
					$resPOC = $this->db->count_all($sqlPOC);
 
					if($resPOC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
                                    
				if($PR_STAT == 1 || $PR_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PR_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' onClick='pRn_P0l(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Void' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' onClick='pRn_P0l(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['PR_CODE']."</div>",
										  $PR_DATEV,
										  $JOBDESC,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
			
		// GET MENU DESC
			$mnCode				= 'MN017';
			$data["MenuApp"] 	= 'MN018';
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
			$data['form_action']= site_url('c_purchase/c_pr180d0c/add_process');
			$data['backURL'] 	= site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN017';
			$data["MenuCode"] 	= 'MN017';
			$data["MenuCode1"] 	= 'MN018';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data['vwDocPatt'] 	= $this->m_purchase_req->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN017';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // X
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$PRDate		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PRDate'))));
		$year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PRDate'))));
		$month 		= (int)date('m',strtotime(str_replace('/', '-', $this->input->post('PRDate'))));
		$date 		= (int)date('d',strtotime(str_replace('/', '-', $this->input->post('PRDate'))));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_pr_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_pr_header
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
		$DocNumber 			= "$PattCode$PRJCODEX$groupPattern-$lastPatternNumb";
		echo "$DocNumber~$lastPatternNumb";
	}
	
	function popupallitem() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			//$COLLID		= $_GET['id'];
			//$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
						
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
			$data['PRJCODE'] 		= $PRJCODE;
			$data['JOBCODE'] 		= $JOBCODE;
			$data['secShowAll']		= site_url('c_purchase/c_pr180d0c/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			//$data['cAllItemPrm']	= $this->m_purchase_req->count_all_prim($PRJCODE, $JOBCODE);
			//$data['vwAllItemPrm'] 	= $this->m_purchase_req->view_all_prim($PRJCODE, $JOBCODE)->result();
			
			//$data['cAllItemSubs']	= $this->m_purchase_req->count_all_subs($PRJCODE, $JOBCODE);
			//$data['vwAllItemSubs'] 	= $this->m_purchase_req->view_all_subs($PRJCODE, $JOBCODE)->result();
					
			$this->load->view('v_purchase/v_purchase_req/purchase_req_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PR_CREATED 	= date('Y-m-d H:i:s');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN017';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				//$PR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
				
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_STAT 		= $this->input->post('PR_STAT');
			$PR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			
			$PR_RECEIPTD	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_RECEIPTD'))));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE		= $this->input->post('DEPCODE');
			$PR_MEMO		= $this->input->post('PR_MEMO');
			
			$PR_CODE 		= $this->input->post('PR_CODE');
			
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(PR_ID) AS maxNo FROM tbl_pr_detail";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;		
			endforeach;

			$COLPRREFNO 		= "";
			$rowDet 			= 0;
			$JOBIDH 			= "";
			foreach($_POST['data'] as $d)
			{
				$rowDet 		= $rowDet + 1;
				$maxNo			= $maxNo + 1;
				$d['PR_ID']		= $maxNo;
				$d['PR_NUM']	= $PR_NUM;
				$d['PR_CODE']	= $PR_CODE;
				$d['DEPCODE']	= $DEPCODE;

				// GET HEADER
					$JOBID 		= $d['JOBCODEID'];
					echo "JOBID = $JOBID";
					$JOBIDH		= "";
					$JOBDSH 	= "";
						$sqlJID = "SELECT JOBCODEID AS JOBPARENT, JOBDESC FROM tbl_joblist_detail 
                					WHERE JOBCODEID = (SELECT JOBPARENT FROM tbl_joblist_detail 
                						WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1)
                					AND PRJCODE = '$PRJCODE' LIMIT 1";
						$reJID	= $this->db->query($sqlJID)->result();
						foreach($reJID as $rowID) :
							$JOBIDH	= $rowID->JOBPARENT;
							$JOBDSH	= $rowID->JOBDESC;
						endforeach;

					if($rowDet == 1)
						$COLPRREFNO = $JOBIDH;
					else
						$COLPRREFNO = "$COLPRREFNO~$JOBIDH";

				$d['JOBPARENT']		= $JOBIDH;
				$d['JOBPARDESC']	= $JOBDSH;
				$this->db->insert('tbl_pr_detail',$d);
			}
			
			$JOBDESC		= '';
			$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBIDH' LIMIT 1";
			$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
			foreach($resJOBDESC as $rowJOBDESC) :
				$JOBDESC	= $rowJOBDESC->JOBDESC;
			endforeach;
			
			$projMatReqH 	= array('PR_NUM' 		=> $PR_NUM,
									'PR_CODE' 		=> $PR_CODE,
									'PR_DATE'		=> $PR_DATE,
									'PR_RECEIPTD'	=> $PR_RECEIPTD,
									'PR_REQUESTER'	=> $this->input->post('PR_REQUESTER'),
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'PR_CREATER'	=> $DefEmp_ID,
									'PR_CREATED'	=> $PR_CREATED,
									'PR_NOTE'		=> addslashes($this->input->post('PR_NOTE')),
									'PR_STAT'		=> $this->input->post('PR_STAT'),
									'JOBCODE'		=> $COLPR_REFNO,
									'JOBDESC'		=> $JOBDESC,
									'PR_REFNO'		=> $COLPRREFNO,
									'PR_MEMO'		=> $PR_MEMO,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_purchase_req->add($projMatReqH);
			
			// UPDATE DETAIL
				$this->m_purchase_req->updateDet($PR_NUM, $PRJCODE, $PR_DATE);
			
			if($PR_STAT == 2)
			{
				// START : CREATE ALERT PROCEDURE
					$crtAlert 	= array('PRJCODE'		=> $this->data['PRJCODE_HO'],
										'ALRT_MNCODE'	=> 'MN018',
										'ALRT_CATEG'	=> "PR",
										'ALRT_NUM'		=> $PR_NUM,
										'ALRT_LEV'		=> 0,
										'ALRT_EMP'		=> $DefEmp_ID);										
					$this->m_updash->crtAlert($crtAlert);
				// END : CREATE ALERT PROCEDURE
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PR_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_pr_header",	// TABLE NAME
										'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
										'DOC_CODE' 		=> $PR_NUM,
										'DOC_STAT' 		=> $PR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_pr_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PR_NUM;
				$MenuCode 		= 'MN017';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "PR",
										'TR_DATE' 		=> $PR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			$url			= site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$PR_NUM		= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			// GET MENU DESC
				$mnCode				= 'MN017';
				$data["MenuApp"] 	= 'MN018';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
				
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pr180d0c/update_process');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			
			$getpurreq 						= $this->m_purchase_req->get_MR_by_number($PR_NUM)->row();
			$data['default']['PR_NUM'] 		= $getpurreq->PR_NUM;
			$data['default']['PR_CODE'] 	= $getpurreq->PR_CODE;
			$data['default']['PR_DATE'] 	= $getpurreq->PR_DATE;
			$data['default']['PR_RECEIPTD'] = $getpurreq->PR_RECEIPTD;
			$data['default']['PR_REQUESTER']= $getpurreq->PR_REQUESTER;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['DEPCODE']				= $getpurreq->DEPCODE;
			$data['default']['DEPCODE']		= $getpurreq->DEPCODE;
			$data['PRJCODE_HO']				= $this->data['PRJCODE_HO'];
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['SPLCODE'] 	= $getpurreq->SPLCODE;
			$data['default']['PR_DEPT'] 	= $getpurreq->PR_DEPT;
			$data['default']['PR_NOTE'] 	= $getpurreq->PR_NOTE;
			$data['default']['PR_NOTE2'] 	= $getpurreq->PR_NOTE2;
			$data['default']['PR_STAT'] 	= $getpurreq->PR_STAT;
			$data['default']['PR_MEMO'] 	= $getpurreq->PR_MEMO;
			//$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['PR_VALUE']	= $getpurreq->PR_VALUE;
			$data['default']['PR_VALUEAPP']	= $getpurreq->PR_VALUEAPP;
			$data['default']['PR_REFNO']	= $getpurreq->PR_REFNO;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN017';
			$data["MenuCode"] 	= 'MN017';
			$data["MenuCode1"] 	= 'MN018';
			$data['vwDocPatt'] 	= $this->m_purchase_req->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->PR_NUM;
				$MenuCode 		= 'MN017';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PR_CREATED 	= date('Y-m-d H:i:s');
			
			$PR_STAT 		= $this->input->post('PR_STAT'); // 1 = New, 2 = confirm, 3 = Close
			//$Doc_Status 	= 1; // 1 = Open, 2 = confirm, 3 = Invoice, 4 = Close
			
			//setting MR Date
			$PR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			
			$PR_RECEIPTD	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_RECEIPTD'))));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE		= $this->input->post('DEPCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_MEMO		= $this->input->post('PR_MEMO');
			$PR_STAT		= $this->input->post('PR_STAT');
			$PRREFNO		= $this->input->post('PR_REFNO');

			$COLPRREFNO 	= "";
			$JOBIDH			= "";
			$rowDet 		= 0;

			foreach($_POST['data'] as $d)
			{
				$rowDet 	= $rowDet + 1;

				$JOBID 		= $d['JOBCODEID'];
				$JOBIDH		= "";
					$sqlJID = "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBID' LIMIT 1";
					$reJID	= $this->db->query($sqlJID)->result();
					foreach($reJID as $rowID) :
						$JOBIDH	= $rowID->JOBPARENT;
					endforeach;

				if($rowDet == 1)
					$COLPRREFNO = $JOBIDH;
				else
					$COLPRREFNO = "$COLPRREFNO~$JOBIDH";
			}

			$JOBDESC		= '';
			$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBIDH' LIMIT 1";
			$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
			foreach($resJOBDESC as $rowJOBDESC) :
				$JOBDESC	= $rowJOBDESC->JOBDESC;
			endforeach;

			$projMatReqH 	= array('PR_NUM' 	=> $this->input->post('PR_NUM'),
								'PR_CODE' 		=> $this->input->post('PR_CODE'),
								'PR_DATE'		=> $PR_DATE,
								'PR_RECEIPTD'	=> $PR_RECEIPTD,
								'PR_REQUESTER' 	=> $this->input->post('PR_REQUESTER'),
								'PRJCODE'		=> $PRJCODE,
								'PR_CREATER'	=> $DefEmp_ID,
								'JOBCODE'		=> $COLPR_REFNO,
								'JOBDESC'		=> $JOBDESC,
								'PR_REFNO'		=> $COLPRREFNO,
								'PR_NOTE'		=> addslashes($this->input->post('PR_NOTE')),
								'PR_STAT'		=> $this->input->post('PR_STAT'), 
								'Patt_Year'		=> $Patt_Year, 
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $this->input->post('Patt_Number'));										
			$this->m_purchase_req->update($PR_NUM, $projMatReqH);

			if($PR_STAT == 1 || $PR_STAT == 2)
			{
				$this->m_purchase_req->deleteDetail($PR_NUM);
				
				$maxNo	= 0;
				$sqlMax = "SELECT MAX(PR_ID) AS maxNo FROM tbl_pr_detail";
				$resMax = $this->db->query($sqlMax)->result();
				foreach($resMax as $rowMax) :
					$maxNo = $rowMax->maxNo;		
				endforeach;
				foreach($_POST['data'] as $d)
				{
					$maxNo				= $maxNo + 1;
					$d['PR_ID']			= $maxNo;
					$d['DEPCODE']		= $DEPCODE;

					// GET HEADER
						$JOBID 			= $d['JOBCODEID'];
						$JOBIDH			= "";
						$JOBDSH 		= "";
							$sqlJID 	= "SELECT JOBCODEID AS JOBPARENT, JOBDESC FROM tbl_joblist_detail 
                        					WHERE JOBCODEID = (SELECT JOBPARENT FROM tbl_joblist_detail 
                        						WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1)
                        					AND PRJCODE = '$PRJCODE' LIMIT 1";
							$reJID		= $this->db->query($sqlJID)->result();
							foreach($reJID as $rowID) :
								$JOBIDH	= $rowID->JOBPARENT;
								$JOBDSH	= $rowID->JOBDESC;
							endforeach;

					$d['JOBPARENT']		= $JOBIDH;
					$d['JOBPARDESC']	= $JOBDSH;
					$this->db->insert('tbl_pr_detail',$d);
				}
			}
			elseif($PR_STAT == 6)
			{
				foreach($_POST['data'] as $d)
				{
					$PR_NUM		= $d['PR_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					$this->m_purchase_req->updateVolBud($PR_NUM, $PRJCODE, $ITM_CODE);
				}
			}
			elseif($PR_STAT == 9)
			{
				$this->m_purchase_req->updREJECT($PR_NUM, $PRJCODE);

				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $PR_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "PR",
											'DOC_QTY' 		=> "DOC_PRQ",
											'DOC_VAL' 		=> "DOC_PRV",
											'DOC_STAT' 		=> 'VOID');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT
			}
			
			if($PR_STAT == 2)
			{
				// START : CREATE ALERT PROCEDURE
					$crtAlert 	= array('PRJCODE'		=> $PRJCODE,
										'ALRT_MNCODE'	=> 'MN018',
										'ALRT_CATEG'	=> "PR",
										'ALRT_NUM'		=> $PR_NUM,
										'ALRT_LEV'		=> 0,
										'ALRT_EMP'		=> $DefEmp_ID);										
					$this->m_updash->crtAlert($crtAlert);
				// END : CREATE ALERT PROCEDURE
			}

			// UPDATE DETAIL
				$this->m_purchase_req->updateDet($PR_NUM, $PRJCODE, $PR_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_pr_header",	// TABLE NAME
										'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
										'DOC_CODE' 		=> $PR_NUM,
										'DOC_STAT' 		=> $PR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										//'CREATERNM'	=> $completeName,
										'TBLNAME'		=> "tbl_pr_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PR_NUM;
				$MenuCode 		= 'MN017';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "PR",
										'TR_DATE' 		=> $PR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
							
			$url			= site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function trash_PO() // U
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$CollID		= $_GET['id'];
		$splitCode 	= explode("~", $CollID);
		$PR_NUM		= $splitCode[0];
		$PRJCODE	= $splitCode[1];
		$key		= '';
		$start		= 0;
		$end		= 50;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_purchase_req->deletePO($PR_NUM);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PR_NUM;
				$MenuCode 		= 'MN017';
				$TTR_CATEG		= 'D';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			return $this->m_purchase_req->get_all_PR($PRJCODE, $start, $end, $key)->result();
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function trash_POX() // U
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$CollID		= $_GET['id'];
		$splitCode 	= explode("~", $CollID);
		$PR_NUM		= $splitCode[0];
		$PRJCODE	= $splitCode[1];
		$key		= '';
		$start		= 0;
		$end		= 50;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$goIdx	= $this->m_purchase_req->deletePO($CollID);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PR_NUM;
				$MenuCode 		= 'MN017';
				$TTR_CATEG		= 'D';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			return $goIdx;
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
		
		$url			= site_url('c_purchase/c_pr180d0c/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

 	function inbox1() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_pr180d0c/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pR7_l5t_x1() // G
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
				$mnCode				= 'MN018';
				$data["MenuApp"] 	= 'MN018';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN018';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pr180d0c/iN20_x1/?id=";
			
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
	
	function iN20_x1() // OK
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			// -------------------- START : SEARCHING METHOD --------------------
				// $chg_url		= 'c_finance/c_f180p0/cp2b0d18_all'
				
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
				//$data["url_search"] = site_url('c_purchase/c_pr180d0c/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_purchase_req->count_all_num_rowsPRInx($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_req->get_all_PRInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN018';
				$data["MenuApp"] 	= 'MN018';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN018';
			$data['addURL'] 	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_pr180d0c/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN018';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_inb', $data);
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
			$url			= site_url('c_purchase/c_pr180d0c/iN20_x1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_req->get_AllDataL_1n2($PRJCODE, $search, $length,$start);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PR_NUM		= $dataI['PR_NUM'];
				$PR_CODE	= $dataI['PR_CODE'];
				
				$PR_DATE	= $dataI['PR_DATE'];
				$PR_DATEV	= date('d M Y', strtotime($PR_DATE));
				
				$JOBCODE	= $dataI['JOBCODE'];
				$JOBDESC	= $dataI['JOBDESC'];
				$PR_NOTE	= $dataI['PR_NOTE'];
				$JOBDESC	= "$JOBDESC - $PR_NOTE";
				$PR_STAT	= $dataI['PR_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				//$empName	= cut_text2 ("$CREATERNM", 15);
				$REQUESTER	= $dataI['PR_REQUESTER'];
				$empName	= cut_text2 ("$REQUESTER", 15);
				
				$CollCode	= "$PRJCODE~$PR_NUM";
				$secUpd		= site_url('c_purchase/c_pr180d0c/update_inb/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPPOList	= site_url('c_purchase/c_pr180d0c/pRn_P0l/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
                $secPrint	= site_url('c_purchase/c_pr180d0c/printdocument/?id='.$this->url_encryption_helper->encode_url($PR_NUM));
				$CollID		= "PR~$PR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
                                    
				$secAction	= "<input type='hidden' name='urlPOList".$noU."' id='urlPOList".$noU."' value='".$secPPOList."'>
							   <input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-check'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Order' onClick='pRn_P0l(".$noU.")'>
									<i class='glyphicon glyphicon-list'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";
								
				
				$output['data'][] = array("$noU.",
										  $dataI['PR_CODE'],
										  $PR_DATEV,
										  $JOBDESC,
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function update_inb()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN018';
			$data["MenuApp"] 	= 'MN018';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$PR_NUM		= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pr180d0c/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_purchase_req->get_MR_by_number($PR_NUM)->row();
			$data['default']['PR_NUM'] 		= $getpurreq->PR_NUM;
			$data['default']['PR_CODE'] 	= $getpurreq->PR_CODE;
			$data['default']['PR_DATE'] 	= $getpurreq->PR_DATE;
			$data['default']['PR_RECEIPTD'] = $getpurreq->PR_RECEIPTD;
			$data['default']['PR_REQUESTER']= $getpurreq->PR_REQUESTER;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['DEPCODE']				= $getpurreq->DEPCODE;
			$data['default']['DEPCODE']		= $getpurreq->DEPCODE;
			$data['PRJCODE_HO']				= $this->data['PRJCODE_HO'];
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['SPLCODE'] 	= $getpurreq->SPLCODE;
			$data['default']['PR_DEPT'] 	= $getpurreq->PR_DEPT;
			$data['default']['PR_NOTE'] 	= $getpurreq->PR_NOTE;
			$data['default']['PR_NOTE2'] 	= $getpurreq->PR_NOTE2;
			$data['default']['PR_STAT'] 	= $getpurreq->PR_STAT;
			$data['default']['PR_MEMO'] 	= $getpurreq->PR_MEMO;
			//$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['PR_VALUE']	= $getpurreq->PR_VALUE;
			$data['default']['PR_VALUEAPP']	= $getpurreq->PR_VALUEAPP;
			$data['default']['PR_REFNO']	= $getpurreq->PR_REFNO;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN018';
			$data["MenuCode"] 	= 'MN018';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->PR_NUM;
				$MenuCode 		= 'MN018';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_inbbyWA()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN018';
			$data["MenuApp"] 	= 'MN018';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$PR_NUM		= $EXTRACTCOL[1];
		$AppEMP		= $EXTRACTCOL[2];

		// START : AUTO LOGIN PROCEDURE
			$autoLog 	= array('AppEMP'		=> $AppEMP);										
			$this->m_updash->autoLog($autoLog);
		// END : AUTO LOGIN PROCEDURE
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_purchase/c_pr180d0c/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getpurreq 						= $this->m_purchase_req->get_MR_by_number($PR_NUM)->row();
			$data['default']['PR_NUM'] 		= $getpurreq->PR_NUM;
			$data['default']['PR_CODE'] 	= $getpurreq->PR_CODE;
			$data['default']['PR_DATE'] 	= $getpurreq->PR_DATE;
			$data['default']['PR_RECEIPTD'] = $getpurreq->PR_RECEIPTD;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['DEPCODE']				= $getpurreq->DEPCODE;
			$data['default']['DEPCODE']		= $getpurreq->DEPCODE;
			$data['PRJCODE_HO']				= $this->data['PRJCODE_HO'];
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['default']['SPLCODE'] 	= $getpurreq->SPLCODE;
			$data['default']['PR_DEPT'] 	= $getpurreq->PR_DEPT;
			$data['default']['PR_NOTE'] 	= $getpurreq->PR_NOTE;
			$data['default']['PR_NOTE2'] 	= $getpurreq->PR_NOTE2;
			$data['default']['PR_STAT'] 	= $getpurreq->PR_STAT;
			$data['default']['PR_MEMO'] 	= $getpurreq->PR_MEMO;
			//$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['PR_VALUE']	= $getpurreq->PR_VALUE;
			$data['default']['PR_VALUEAPP']	= $getpurreq->PR_VALUEAPP;
			$data['default']['PR_REFNO']	= $getpurreq->PR_REFNO;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			$MenuCode 			= 'MN018';
			$data["MenuCode"] 	= 'MN018';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->PR_NUM;
				$MenuCode 		= 'MN018';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PR_APPROVED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PR_STAT 		= $this->input->post('PR_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PR_STAT		= $this->input->post('PR_STAT');
			$PR_NOTE2		= addslashes($this->input->post('PR_NOTE2'));
			
			$AH_CODE		= $PR_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= $PR_APPROVED;
			$AH_NOTES		= addslashes($this->input->post('PR_NOTE2'));
			$PR_MEMO		= $this->input->post('PR_MEMO');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// START : SPECIAL FOR SASMITO
				$TOTMAJOR	= $this->input->post('TOTMAJOR');
				// IF TOTMAJOR > 0, MAKA HARUS ADA STEP APPROVAL KHUSUS DARI MENU SETTING
				// MELALUI TABEL tbl_major_app
				$sqlMJREMP	= "SELECT * FROM tbl_major_app";
				$resMJREMP	= $this->db->query($sqlMJREMP)->result();
				foreach($resMJREMP as $rowMJR) :
					$Emp_ID1	= $rowMJR->Emp_ID1;
					$Emp_ID2	= $rowMJR->Emp_ID2;
				endforeach;
				$yesAPP		= 0;
				if($TOTMAJOR > 0)
				{
					if(($DefEmp_ID == $Emp_ID1) || ($DefEmp_ID == $Emp_ID2))
					{
						$AH_ISLAST 	= 1;
						$yesAPP		= 1;
					}
					else
					{
						$AH_ISLAST 	= 0;
						$yesAPP		= 0;
					}
				}
				else
				{
					$yesAPP		= 1;
				}
			// END : SPECIAL FOR SASMITO

			// UPDATE JOBDETAIL ITEM
			if($PR_STAT == 3)
			{
				// DEFAULT STATUS
					$projMatReqH 	= array('PR_STAT'	=> 7,
											'PR_NOTE2'	=> $PR_NOTE2);					
					$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
				// START : UPDATE STATUS
					$this->load->model('m_updash/m_updash', '', TRUE);
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
											'DOC_CODE' 		=> $PR_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_pr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

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

				// START : CREATE ALERT PROCEDURE
					$crtAlert 	= array('PRJCODE'		=> $PRJCODE,
										'ALRT_MNCODE'	=> 'MN018',
										'ALRT_CATEG'	=> "PR",
										'ALRT_NUM'		=> $PR_NUM,
										'ALRT_LEV'		=> $AH_APPLEV,
										'ALRT_EMP'		=> $AH_APPROVER);										
					$this->m_updash->crtAlert($crtAlert);
				// END : CREATE ALERT PROCEDURE

				if($AH_ISLAST == 1 && $yesAPP == 1)
				{
					// DEFAULT STATUS
						$projMatReqH 	= array('PR_APPROVER1'	=> $DefEmp_ID,
												'PR_APPROVED1'	=> $PR_APPROVED,
												'PR_NOTE2'		=> $PR_NOTE2,
												'PR_MEMO'		=> $PR_MEMO,
												'PR_STAT'		=> $PR_STAT);										
						$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
					// START : UPDATE STATUS
						$this->load->model('m_updash/m_updash', '', TRUE);
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
												'DOC_CODE' 		=> $PR_NUM,
												'DOC_STAT' 		=> $PR_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'	=> $completeName,
												'TBLNAME'		=> "tbl_pr_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					// UPDATE PEKERJAAN
						$this->m_purchase_req->updateJobDet($PR_NUM, $PRJCODE); // UPDATE JOBD DETAIL DAN PR

					// START : UPDATE TO TRANS-COUNT
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
						$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
												'PRJCODE' 		=> $PRJCODE,		// PROJECT
												'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
												'TBL_NAME' 		=> "tbl_pr_header",	// TABLE NAME
												'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
												'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
												'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
												'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
												'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
												'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
												'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
												'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
												'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
						$this->m_updash->updateDashData($parameters);
					// END : UPDATE TO TRANS-COUNT

					// START : UPDATE TO DOC. COUNT
						$parameters 	= array('DOC_CODE' 		=> $PR_NUM,
												'PRJCODE' 		=> $PRJCODE,
												'DOC_TYPE'		=> "PR",
												'DOC_QTY' 		=> "DOC_PRQ",
												'DOC_VAL' 		=> "DOC_PRV",
												'DOC_STAT' 		=> 'ADD');
						$this->m_updash->updateDocC($parameters);
					// END : UPDATE TO DOC. COUNT

					// START : RESET PR TMP
						$s_delPR 	= "DELETE FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_delPR);
					// END : RESET PR TMP
				}
				//return false;
			}
			elseif($PR_STAT == 4)
			{
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($PR_NUM);
				// END : DELETE HISTORY

				// UPDATE HEADER
					$projMatReqH 	= array('PR_STAT'	=> $PR_STAT,
											'PR_NOTE2'	=> $PR_NOTE2,
											'PR_MEMO'	=> $PR_MEMO);
					$this->m_purchase_req->update($PR_NUM, $projMatReqH);
			
				// START : UPDATE STATUS
					$this->load->model('m_updash/m_updash', '', TRUE);
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
											'DOC_CODE' 		=> $PR_NUM,
											'DOC_STAT' 		=> $PR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_pr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_pr_header",	// TABLE NAME
											'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			}
			elseif($PR_STAT == 5)
			{
				// UPDATE HEADER
					$projMatReqH 	= array('PR_STAT'	=> $PR_STAT,
											'PR_MEMO'	=> $PR_MEMO);
					$this->m_purchase_req->update($PR_NUM, $projMatReqH);

				// START : UPDATE STATUS
					$this->load->model('m_updash/m_updash', '', TRUE);
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PR_NUM",
											'DOC_CODE' 		=> $PR_NUM,
											'DOC_STAT' 		=> $PR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_pr_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_pr_header",	// TABLE NAME
											'KEY_NAME'		=> "PR_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "PR_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $PR_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_REQ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_REQ_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_REQ_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_REQ_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_REQ_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_REQ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_REQ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PR_NUM;
				$MenuCode 		= 'MN018';
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

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "PR",
										'TR_DATE' 		=> $PR_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			$url			= site_url('c_purchase/c_pr180d0c/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pRn_P0l()
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PR_NUM		= $_GET['id'];
		$PR_NUM		= $this->url_encryption_helper->decode_url($PR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;
			$PR_CODE 		= "";
			$PR_DATE 		= "";
			$PR_NOTE 		= "";
			$sqlPR 			= "SELECT PR_CODE, PR_DATE, PR_NOTE FROM tbl_pr_header WHERE PR_NUM = '$PR_NUM'";
			$resultaPR 		= $this->db->query($sqlPR)->result();
			foreach($resultaPR as $rowPR) :
				$PR_CODE 	= $rowPR->PR_CODE;	
				$PR_DATE 	= $rowPR->PR_DATE;
				$PR_NOTE 	= $rowPR->PR_NOTE;		
			endforeach;
			$data['PR_NUM'] 	= $PR_NUM;
			$data['PR_CODE'] 	= $PR_CODE;
			$data['PR_DATE'] 	= $PR_DATE;
			$data['PR_NOTE'] 	= $PR_NOTE;
			
			$data['countPO']	= $this->m_purchase_req->count_all_PO($PR_NUM);
			$data['vwPO'] 		= $this->m_purchase_req->get_all_PO($PR_NUM)->result();	
							
			$this->load->view('v_purchase/v_purchase_req/print_polist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function printdocument()
	{
		$this->load->model('m_purchase/m_purchase_req/m_purchase_req', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PR_NUM		= $_GET['id'];
		$PR_NUM		= $this->url_encryption_helper->decode_url($PR_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getpurreq = $this->m_purchase_req->get_MR_by_number($PR_NUM)->row();
			
			$data['default']['PR_NUM'] 		= $getpurreq->PR_NUM;
			$data['default']['PR_STAT'] 	= $getpurreq->PR_STAT;
			$data['default']['PR_CODE'] 	= $getpurreq->PR_CODE;
			$data['default']['PR_DATE'] 	= $getpurreq->PR_DATE;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['SPLCODE']		= $getpurreq->SPLCODE;
			$data['default']['PR_DEPT']		= $getpurreq->PR_DEPT;
			$data['default']['PR_NOTE'] 	= $getpurreq->PR_NOTE;
			$data['default']['PR_NOTE2']	= $getpurreq->PR_NOTE2;
			$data['default']['PR_PLAN_IR'] 	= $getpurreq->PR_RECEIPTD;
			$data['default']['PR_MEMO'] 	= $getpurreq->PR_MEMO;
			$data['default']['PR_CREATER'] 	= $getpurreq->PR_CREATER;
							
			$this->load->view('v_purchase/v_purchase_req/print_matreq', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataITM() // GOOD
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
			$num_rows 		= $this->m_purchase_req->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];

				$JOBVOLM		= $dataI['ITM_VOLM'];
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				// $ADDM_VOLM 		= $dataI['ADDM_VOLM'];
				// $ADDM_JOBCOST	= $dataI['ADDM_JOBCOST'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG		= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
					endforeach;

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

				// GET PR DETAIL
					$TOT_PRVOL		= 0;
					$TOT_PRAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT = 2";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLMBG		= $TOT_AMOUNTBG;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
					}
				
					if($ITM_TYPE == 'SUBS')
					{
						$disabledB	= 0;															
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQV 	= "";
						$PO_VOLMV	= "";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 50);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($REMREQ_QTY, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					}

				// SPACE
					/*if($JOBLEV == 1)
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
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";*/

				$JOBDESCH		= "";
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESCH	= $rowJOBDESC->JOBDESC;
				endforeach;
				$strLENH 	= strlen($JOBDESCH);
				$JOBDESCHA	= wordwrap($JOBDESCH, 50, "<br>", TRUE);
				$JOBDESCH 	= $JOBDESCHA;
				if($strLENH > 50)
					$JOBDESCH 	= $JOBDESCHA;

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."|".$JOBDESCH."' onClick='pickThis1(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";
					}

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$JOBCODEID - $JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				if($REMREQ_QTY > 0 && $REMREQ_AMN > 0)
				{
					$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
												"<div>
											  		<p><span ".$CELL_COL.">".$JobView."</span></p>
											  	</div>
											  	<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCH."
											  	</div>",
												"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
												"<div style='text-align:right;'>".$statRem."</div>");

					$noU			= $noU + 1;
				}
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMS() // GOOD
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
			
			$columns_valid 	= array("ITM_CODE", 
									"ITM_UNIT", 
									"ITM_NAME");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];

				$JOBVOLM		= $dataI['ITM_VOLM'];
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				// $ADDM_VOLM 		= $dataI['ADDM_VOLM'];
				// $ADDM_JOBCOST	= $dataI['ADDM_JOBCOST'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$ITMBUDGVOL 	= 0;
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT, ITM_VOLMBG
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
						$ITMBUDGVOL	= $rowITMNM->ITM_VOLMBG;
					endforeach;

				// 21-03-27 ITEM STATUS SUBPUN TETAP AKAN MENGIKUTI BUDGET INDUKNYA
				// 21-06-27 DIBUKA LAGI
				if($ITM_TYPE == 'SUBS')
				{
					$disabledB	= 0;
					$JOBVOLM 	= $ITMBUDGVOL;
					$ITM_VOLMBG = $ITMBUDGVOL;
				}

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG	= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

				// GET PR DETAIL
					$TOT_PRVOL		= 0;
					$TOT_PRAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT = 2";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLMBG		= $TOT_AMOUNTBG;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQV 	= "";
						$PO_VOLMV	= "";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 50);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($REMREQ_QTY, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					}

				// SPACE
					/*if($JOBLEV == 1)
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
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";*/

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis2(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";
					}

					$JOBDESCH		= "";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;
					$strLENH 	= strlen($JOBDESCH);
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$JOBCODEID - $JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCH."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMSCUT() // GOOD
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
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'Requested')$Requested = $LangTransl;
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
			
			$columns_valid 	= array("", 
									"ITM_NAME", 
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
			$num_rows 		= $this->m_purchase_req->get_AllDataITMSCUTC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataITMSCUTL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				// RENDER
					$ORD_ID 		= "";
					$JOBCODEDET 	= "";
					$JOBCODEID 		= "";
					$JOBPARENT 		= "";
					$JOBCODE 		= "";
					$ITM_CODE		= $dataI['ITM_CODE'];
					$ITM_NAME		= $dataI['ITM_NAME'];
					$JOBDESC		= $ITM_NAME;
					$strLEN 		= strlen($JOBDESC);
					$JOBDESCA		= substr("$JOBDESC", 0, 50);
					$JOBDESC1 		= $JOBDESCA;
					if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";

					$ITM_GROUP		= $dataI['ITM_GROUP'];
					$ITM_UNIT		= $dataI['ITM_UNIT'];
					$ITM_CODE_H		= $dataI['ITM_CODE_H'];
					$ITM_TYPE		= $dataI['ITM_TYPE'];
					$JOBVOLM		= $dataI['ITM_VOLM'];
					$ITM_VOLMBG		= $dataI['ITM_VOLM'];
					$JOBPRICE		= $dataI['ITM_PRICE'];
					$ITM_PRICE		= $dataI['ITM_PRICE'];
					$ITM_LASTP		= $dataI['ITM_LASTP'];
					$JOBCOST		= $JOBVOLM * $JOBPRICE;
					$ADD_VOLM 		= $dataI['ADD_VOLM'];
					$ADD_VOLMP 		= $ADD_VOLM;
					if($ADD_VOLM == 0)
						$ADD_VOLMP 	= 1;
					$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
					$ADD_PRICE		= $ADD_JOBCOST / $ADD_VOLMP;
					$REQ_VOLM		= $dataI['REQ_VOLM'];
					$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
					$PO_VOLM		= $dataI['PO_VOLM'];
					$PO_AMOUNT		= $dataI['PO_AMOUNT'];
					$IR_VOLM		= $dataI['IR_VOLM'];
					$IR_AMOUNT		= $dataI['IR_AMOUNT'];
					$ITM_USED		= $dataI['ITM_USED'];
					$ITM_USED_AM	= $dataI['ITM_USED_AM'];
					$ITM_STOCK		= $dataI['ITM_STOCK'];
					$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
					$IS_LEVEL		= 0;
					$ISLAST			= 1;

					$serialNumber	= '';
					$itemConvertion	= 1;
					$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
					$TOT_AMOUNTBG	= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

					$JOBUNIT 		= strtoupper($ITM_UNIT);
					if($JOBUNIT == '')
						$JOBUNIT= 'LS';

				// GET PR DETAIL
					$TOT_PRVOL		= 0;
					$TOT_PRAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
										WHERE A.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.PR_STAT IN (2,3,6)";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PRAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;

				// GET PO DETAIL
					$TOT_POVOL		= 0;
					$TOT_POAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PO_VOLM) AS TOT_POVOL, 
											SUM(A.PO_COST) AS TOT_POAMN 
										FROM tbl_po_detail A
										WHERE A.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.PO_STAT IN (2,3,6)";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_POVOL	= $rowITMVC->TOT_POVOL;
						$TOT_POAMN	= $rowITMVC->TOT_POAMN;
					endforeach;
					if($TOT_POVOL == '')
						$TOT_POVOL	= 0;
					if($TOT_POAMN == '')
						$TOT_POAMN	= 0;

				// LS PROCEDURE
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $TOT_PRAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $TOT_POAMN;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						$PO_VOLM	= $TOT_POVOL;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLMBG		= $TOT_AMOUNTBG;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
					}
				
					if($ITM_TYPE == 'SUBS')
					{
						$disabledB	= 0;															
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV	= number_format(0, 2);
						$TOT_REQV	= number_format(0, 2);
						$PO_VOLMV	= number_format(0, 2);
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 60);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 60)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($REMREQ_QTY, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					}

				// OTHER SETT
					if($disabledB == 0)
						$chkBox		= "<input type='radio' name='chkRad' value='".$PRJCODE."|".$ITM_CODE."' onClick='pickThis3(this);'/>";
					else
						$chkBox		= "<input type='radio' name='chkRad' value='' style='display: none' />";

					/*$JOBDESCH		= "";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;
					$strLENH 	= strlen($JOBDESCH);
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";*/

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div class='text-wrap'>
										  		<p><span class='text-wrap'>".$JobView."</span></p>
										  	</div>
										  	<i class='text-muted fa fa-cube'></i>&nbsp;&nbsp;$ITM_CODE ($JOBUNITV)
									  		<div style='margin-left: 17px; font-style: italic;'>$BudgetQty : ".$JOBVOLMV." $Requested : ".$TOT_REQV."
									  		</div>",
											"<div style='text-align:right;'>".$statRem."</div>");
				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E",
										"F",
										"G");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMSCUT4() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$myarr		= explode("|", $_GET['id']);
		$PRJCODE 	= $myarr[0];
		$ITM_CODE 	= $myarr[1];
		$PR_NUM 	= $myarr[2];

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
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'Requested')$Requested = $LangTransl;
        endforeach;
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
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
			
			$columns_valid 	= array("", 
									"JOBDESC", 
									"ITM_BUDG");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataITMSCUT4C($PRJCODE, $ITM_CODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataITMSCUT4L($PRJCODE, $ITM_CODE, $search, $length, $start, $order, $dir);
								
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
				$ORD_ID 		= $dataI['ORD_ID'];
				$JOBCODEDET 	= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];
				$JOBDESC		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];

				$JOBVOLM		= $dataI['ITM_VOLM'];
				$ITM_VOLMBG		= $dataI['ITM_VOLM'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_PRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
				$REQ_VOLM		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				$PO_VOLM		= $dataI['PO_VOLM'];
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];
				$IR_VOLM		= $dataI['IR_VOLM'];
				$IR_AMOUNT		= $dataI['IR_AMOUNT'];
				$ITM_USED		= $dataI['ITM_USED'];
				$ITM_USED_AM	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLMBG 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG		= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
					endforeach;

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

				// GET PR DETAIL
					$TOT_PRVOL		= 0;
					$TOT_PRAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM) AS TOT_REQ, 
											SUM(A.PR_TOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT = 2";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TOT_PRVOL	= $rowITMVC->TOT_REQ;
						$TOT_PRAMN	= $rowITMVC->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL	= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN	= 0;

				// LS PROCEDURE
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PRAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					//$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					//$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($tempTotMax <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLMBG		= $TOT_AMOUNTBG;
					}
					else
					{
						if($tempTotMax <= 0)
							$disabledB	= 1;
					}
				
					if($ITM_TYPE == 'SUBS')
					{
						$disabledB	= 0;															
					}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQV 	= "";
						$PO_VOLMV	= "";
					}
					else
					{
						$strLEN 	= strlen($JOBDESC);
						$JOBDESCA	= substr("$JOBDESC", 0, 40);
						$JOBDESC1 	= $JOBDESCA;
						if($strLEN > 40)
							$JOBDESC1 	= $JOBDESCA."...";
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";

						$JOBUNITV 	= $JOBUNIT;
						$JOBVOLMV	= number_format($JOBVOLM, 2);
						$TOT_REQV	= number_format($TOT_REQ, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($tempTotMax, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($tempTotMax, 2)."</span>";
					}

				// OTHER SETT
					$chcked 		= "";
					$s_c01 			= "tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
					$r_c01 			= $this->db->count_all($s_c01);
					if($r_c01 > 0)
						$chcked 	= "checked";

					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk4' id='chk4".$noU."' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis4(this, ".$noU.");' $chcked />";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk4' value='' style='display: none' />";
					}

					$JOBDESCH		= "";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;
					$strLENH 	= strlen($JOBDESCH);
					$JOBDESCHA	= substr("$JOBDESCH", 0, 100);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 100)
						$JOBDESCH 	= $JOBDESCHA."...";

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					$JobView		= "$JOBCODEID - $JOBDESC1";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>
											<input type='hidden' id='ITM_UNIT".$noU."' name='data[".$noU."][ITM_UNIT]' value='".$ITM_UNIT."' class='form-control' style='max-width: 100px; text-align: right'>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobView."</span></p>
										  	</div>
										  	<i class='text-muted fa fa-rss'></i>&nbsp;&nbsp;$JOBDESCH
									  		<div style='margin-left: 17px; font-style: italic;'>$BudgetQty : $JOBVOLMV $Requested : $TOT_REQV</div>",
											"<input type='text' id='PRJOB_VOLMX".$noU."' value='".number_format($tempTotMax, 2)."' class='form-control' style='max-width: 100px; text-align: right' onBlur='chgQty(this, ".$noU.")'>
											<input type='hidden' id='PRJOB_VOLMMAX".$noU."' value='".$tempTotMax."' class='form-control' style='max-width: 100px; text-align: right'>
											<input type='hidden' id='PRJOB_VOLM".$noU."' name='data[".$noU."][PRJOB_VOLM]' value='".$tempTotMax."' class='form-control' style='max-width: 100px; text-align: right'>"
											);

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("$PRJCODE",
										"$ITM_CODE",
										"C");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMSCUT4SV() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$myarr		= explode("~", $_POST['collDt']);
		$PR_NUM 	= $myarr[0];
		$PRJCODE 	= $myarr[1];
		$chkVal 	= $myarr[2];
		$REQ_NOW 	= $myarr[3];
		$STATUS 	= $myarr[4];

		$arrItem		= explode("|", $chkVal);
		$JOBCODEDET 	= $arrItem[0];
		$JOBCODEID 		= $arrItem[1];
		$JOBCODE 		= $arrItem[2];
		$PRJCODE 		= $arrItem[3];
		$ITM_CODE 		= $arrItem[4];

		if($STATUS == '1')
		{
			$s_01 			= "INSERT INTO tbl_pr_detail_tmp (PR_NUM, PRJCODE, JOBCODEID, ITM_CODE, CREATED)
								VALUES ('$PR_NUM', '$PRJCODE', '$JOBCODEID', '$ITM_CODE', '$DefEmp_ID')";
			$this->db->query($s_01);
		}
		else
		{
			$s_01 			= "DELETE FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
								AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_01);
		}

		echo $REQ_NOW;
	}
	
	function JList()
	{
		$this->load->model('login_model', '', TRUE);
		date_default_timezone_set("Asia/Jakarta");
		$DNOW		= date('Y-m-d H:i:s');
		$collID		= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$PRJCODE 	= $colExpl[1];

		$JList 		= $this->m_purchase_req->get_AllJob($PRJCODE);
        
        $Disabled_1	= 0;
        $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE' LIMIT 1000";
        $resJob_1	= $this->db->query($sqlJob_1)->result();
        foreach($resJob_1 as $row_1) :
            $JOBCODEID_1	= $row_1->JOBCODEID;
            $JOBPARENT_1	= $row_1->JOBPARENT;
            $JOBLEV_1		= $row_1->JOBLEV;
            $JOBDESC_1		= $row_1->JOBDESC;
            
            if($JOBLEV_1 == 1)
            {
                $space_level_1	= "";
            }
            elseif($JOBLEV_1 == 2)
            {
                $space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            elseif($JOBLEV_1 == 3)
            {
                $space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            elseif($JOBLEV_1 == 4)
            {
                $space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            elseif($JOBLEV_1 == 5)
            {
                $space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            
            $JIDExplode = explode('~', $PR_REFNO);
            $JOBCODE1	= '';
            $SELECTED	= 0;
            foreach($JIDExplode as $i => $key)
            {
                $JOBCODE1	= $key;
                if($JOBCODEID_1 == $JOBCODE1)
                {
                    $SELECTED	= 1;
                }
            }
            
            //$sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
            // seharusnya mendeteksi apakah header ini memiliki turunan detail atau tidak, kalau ada wajib di enabled.
            //$sqlC_2			= "tbl_joblist_detail WHERE JOBPARENT = '$JOBCODEID_1' AND  AND PRJCODE = '$PRJCODE'";
			$sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISLAST = 1 AND PRJCODE = '$PRJCODE'";
            $resC_2 		= $this->db->count_all($sqlC_2);
            if($resC_2 == 0)
                $Disabled_1 = 1;
            else
                $Disabled_1 = 0;
            ?>
            <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
                <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
            </option>
            <?php
        endforeach;

		echo "testig";
	}
}