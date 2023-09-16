<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= C_j0b0rd3r.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_rej0b0rd3r extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_production/m_rejoborder', '', TRUE);
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
		$this->load->model('m_production/m_joborder', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_rej0b0rd3r/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN417';
				$data["MenuApp"] 	= 'MN371';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN417';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_rej0b0rd3r/glj0b0rd3r/?id=";
			
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

	function glj0b0rd3r() // G
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN417';
			$data["MenuApp"] 	= 'MN371';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
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
				$data["url_search"] = site_url('c_production/c_rej0b0rd3r/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_rejoborder->count_all_JO($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_rejoborder->get_all_JO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN417';
				$data["MenuApp"] 	= 'MN371';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Perintah Kerja";
				$data['h2_title'] 	= 'produksi';
			}
			else
			{
				$data["h1_title"] 	= "Job Order";
				$data['h2_title'] 	= 'prodcution';
			}
			
			$data['addURL'] 	= site_url('c_production/c_rej0b0rd3r/a44_j0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_rej0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN417';
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
			
			$this->load->view('v_production/v_joborder/v_rejoborder', $data);
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
			$url			= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("JO_ID",
									"JO_CODE",
									"JO_DATE",
									"CUST_DESC",
									"JO_DESC",
									"JO_AMOUNT",
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
			$num_rows 		= $this->m_rejoborder->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_rejoborder->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JO_ID		= $dataI['JO_ID'];
				$PRJCODE	= $dataI['PRJCODE'];
				$JO_NUM		= $dataI['JO_NUM'];
				$JO_CODE	= $dataI['JO_CODE'];				
				$JO_DATE	= $dataI['JO_DATE'];
				$JO_DATEV	= date('d M Y', strtotime($JO_DATE));
				
				$CUST_CODE	= $dataI['CUST_CODE'];
				$CUST_DESC	= $dataI['CUST_DESC'];
				$JO_DESC	= $dataI['JO_DESC'];
				$JO_AMOUNT	= $dataI['JO_AMOUNT'];
				$JO_NOTES	= $dataI['JO_NOTES'];
				$JO_STAT	= $dataI['JO_STAT'];
				$MR_NUM		= $dataI['MR_NUM'];
				$ISSELECT	= $dataI['ISSELECT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$JO_NUM";
				$secUpd		= site_url('c_production/c_rej0b0rd3r/u77_j0b0rd3r/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCMR		= site_url('c_production/c_rej0b0rd3r/add_processMR/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_production/c_rej0b0rd3r/printdocument/?id='.$this->url_encryption_helper->encode_url($JO_NUM));
				$secPrintQR	= site_url('c_production/c_rej0b0rd3r/printQR/?id='.$this->url_encryption_helper->encode_url($JO_NUM));
				$CollID		= "JO~$JO_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
                
                $isDis		= "disabled='disabled'";
                $disQRC		= "disabled='disabled'";
                $colMR		= "danger";
				if($JO_STAT == 3)
				{
					$disQRC	= "";
					$colMR	= "warning";
				}
				if($JO_STAT == 3 AND $MR_NUM == '' AND $ISSELECT == 1)
				{
					$isDis	= "";
					$colMR	= "warning";
				}

				$MR_CODE	= '';
				if($MR_NUM != '')
				{
					$sqlMR = "SELECT MR_CODE FROM tbl_mr_header WHERE MR_NUM = '$MR_NUM' LIMIT 1";
					$resMR = $this->db->query($sqlMR)->result();
					foreach($resMR as $rowMR) :
						$MR_CODE 	= $rowMR->MR_CODE;
					endforeach;
				}

				if($JO_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='".$secCMR."' class='btn btn-".$colMR." btn-xs' ".$isDis.">
										<i class='glyphicon glyphicon-duplicate'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' style='display:none'>
										<i class='fa fa-trash-o'></i>
									</a>
									<a href='avascript:void(null);' class='btn btn-".$colMR." btn-xs' title='Show QRC' onClick='printQR(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrintQR".$noU."' id='urlPrintQR".$noU."' value='".$secPrintQR."'>
								   <input type='hidden' name='MR_NUM".$noU."' id='MR_NUM".$noU."' value='".$MR_CODE."'>
								   <input type='hidden' name='urlMR".$noU."' id='urlMR".$noU."' value='".$secCMR."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' class='btn btn-".$colMR." btn-xs' ".$isDis." onClick='showMR(".$noU.")'>
										<i class='glyphicon glyphicon-duplicate'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled' style='display:none'>
										<i class='fa fa-trash-o'></i>
									</a>
									<a href='avascript:void(null);' class='btn btn-".$colMR." btn-xs' ".$disQRC." title='Show QRC' onClick='printQR(".$noU.")'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									</label>";
				}							
				
				$output['data'][] = array($noU,
										  "<div style='white-space:nowrap'>".$dataI['JO_CODE']."</div>",
										  $JO_DATEV,
										  $CUST_DESC,
										  $JO_DESC,
										  number_format($JO_AMOUNT, 2),
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_j0b0rd3r() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joborder', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN417';
			$data["MenuApp"] 	= 'MN371';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			
			$data['form_action']	= site_url('c_production/c_rej0b0rd3r/add_process');
			$cancelURL				= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN417';
			$data["MenuCode"] 		= 'MN417';
			$data['viewDocPattern'] = $this->m_rejoborder->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN417';
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
	
			$this->load->view('v_production/v_joborder/v_rejoborder_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llRM() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_prodprocess', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$SO_NUM		= $_GET['SONUM'];
			$PRODS_STEP	= $_GET['PRODSTEP'];
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
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
			
			$data['SO_NUM'] 		= $SO_NUM;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PRODS_STEP'] 	= $PRODS_STEP;
					
			$this->load->view('v_production/v_joborder/v_prodprocess_selRM', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llQRC() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_prodprocess', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$JONUM		= $_GET['JONUM'];
			$JOSTFNUM	= $_GET['JOSTFNUM'];
			$PSTEP		= $_GET['PSTEP'];
			$CUST_CODE	= $_GET['CUSTCODE'];
			$ITM_CODE	= $_GET['ITMCODE'];
			$SELROW		= $_GET['SELROW'];
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar QR Code";
			}
			else
			{
				$data["h2_title"] 	= "QR Code List";
			}
			
			$data['JO_NUM'] 	= $JONUM;
			$data['JOSTF_NUM'] 	= $JOSTFNUM;
			$data['PRODS_STEP'] = $PSTEP;
			$data['PRJCODE'] 	= $PRJCODE;
			$data['CUST_CODE'] 	= $CUST_CODE;
			$data['ITM_CODE'] 	= $ITM_CODE;
			$data['SELROW'] 	= $SELROW;
					
			$this->load->view('v_production/v_joborder/v_prodprocess_selQRC', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llW1p() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_prodprocess', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$SO_NUM		= $_GET['SONUM'];
			$PRODS_STEP	= $_GET['PRODSTEP'];
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
			
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
			
			$data['SO_NUM'] 		= $SO_NUM;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['PRODS_STEP'] 	= $PRODS_STEP;
			$data['cAllItemPrm']	= $this->m_rejoborder->count_all_prim($PRJCODE, $SO_NUM, $PRODS_STEP);
			$data['vwAllItemPrm'] 	= $this->m_rejoborder->view_all_prim($PRJCODE, $SO_NUM, $PRODS_STEP)->result();
			
			$data['cAllItemSubs']	= $this->m_rejoborder->count_all_subs($PRJCODE, $SO_NUM, $PRODS_STEP);
			$data['vwAllItemSubs'] 	= $this->m_rejoborder->view_all_subs($PRJCODE, $SO_NUM, $PRODS_STEP)->result();
					
			$this->load->view('v_production/v_joborder/v_rejoborder_selitem_req', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llit3m() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_joborder', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
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
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_production/c_rej0b0rd3r/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_rejoborder->count_all_item($PRJCODE);
			$data['vwAllItem'] 		= $this->m_rejoborder->get_all_item($PRJCODE)->result();
					
			$this->load->view('v_production/v_joborder/v_rejoborder_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$JO_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN417';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$JO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_UC   		= substr($JO_NUM, -12);
			$JO_CATEG		= 2;
			$JO_RETYPE 		= $this->input->post('JO_RETYPE');
			$JOSTF_NUM 		= $this->input->post('JOSTF_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_DATE		= date('Y-m-d',strtotime($this->input->post('JO_DATE')));
			$JO_PRODD		= date('Y-m-d',strtotime($this->input->post('JO_PRODD')));
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_DESC 		= $this->input->post('CUST_DESC');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$JO_DESC 		= $this->input->post('JO_DESC');
			//$JO_VOLM 		= $this->input->post('JO_VOLM');
			$JO_VOLM 		= 0;
			$JO_NOTES 		= $this->input->post('JO_NOTES');
			$JO_STAT		= $this->input->post('JO_STAT');
			$Patt_Year		= date('Y',strtotime($this->input->post('JO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('JO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('JO_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$OFF_NUM 		= '';
			$OFF_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlSOH 		= "SELECT OFF_NUM, OFF_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_so_header WHERE SO_NUM = '$SO_NUM' LIMIT 1";
			$resSOH 		= $this->db->query($sqlSOH)->result();
			foreach($resSOH as $rowSOH) :
				$OFF_NUM 	= $rowSOH->OFF_NUM;
				$OFF_CODE 	= $rowSOH->OFF_CODE;
				$CCAL_NUM 	= $rowSOH->CCAL_NUM;
				$CCAL_CODE 	= $rowSOH->CCAL_CODE;
				$BOM_NUM 	= $rowSOH->BOM_NUM;
				$BOM_CODE 	= $rowSOH->BOM_CODE;
			endforeach;

			$JO_NUMREF 		= $this->input->post('JO_NUMREF');
			$JO_CODEREF 	= '';
			$sqlJOH 		= "SELECT JO_CODE FROM tbl_jo_header WHERE JO_NUM = '$JO_NUMREF' LIMIT 1";
			$resJOH 		= $this->db->query($sqlJOH)->result();
			foreach($resJOH as $rowJOH) :
				$JO_CODEREF	= $rowJOH->JO_CODE;
			endforeach;

			$AddJO			= array('JO_NUM' 		=> $JO_NUM,
									'JO_UC'			=> $JO_UC,
									'JO_CATEG'		=> 2,
									'JO_RETYPE' 	=> $JO_RETYPE,
									'JOSTF_NUM'		=> $JOSTF_NUM,
									'JO_CODE' 		=> $JO_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'JO_DATE'		=> $JO_DATE,
									'JO_PRODD'		=> $JO_PRODD,
									'SO_NUM'		=> $SO_NUM,
									'SO_CODE'		=> $SO_CODE,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC' 	=> $CUST_DESC,
									'CUST_ADDRESS' 	=> $CUST_ADDRESS,
									'OFF_NUM'		=> $OFF_NUM,
									'OFF_CODE'		=> $OFF_CODE,
									'CCAL_NUM'		=> $CCAL_NUM,
									'CCAL_CODE'		=> $CCAL_CODE,
									'BOM_NUM'		=> $BOM_NUM,
									'BOM_CODE'		=> $BOM_CODE,
									'JO_DESC' 		=> $JO_NOTES,
									'JO_VOLM' 		=> $JO_VOLM,									
									'JO_NOTES' 		=> $JO_NOTES, 
									'JO_STAT' 		=> $JO_STAT,
									'JO_NUMREF'		=> $JO_NUMREF,
									'JO_CODEREF'	=> $JO_CODEREF,
									'ISREPROC'		=> 1,
									'JO_CREATER'	=> $DefEmp_ID,
									'JO_CREATED'	=> $JO_CREATED,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_rejoborder->add($AddJO);
			
			// START : INSERT DETAIL FG
				$JO_TOTVOLM	= 0;
				$JO_TOTCOST	= 0;
				foreach($_POST['data1'] as $d)
				{
					$d1['JO_NUM']	= $JO_NUM;
					$d1['JO_CODE']	= $JO_CODE;
					$d1['JO_CATEG']	= 2;
					$d1['JO_DATE']	= $JO_DATE;
					$d1['JO_PRODD']	= $JO_PRODD;
					$d1['SO_NUM']	= $SO_NUM;
					$d1['SO_CODE']	= $SO_CODE;
					$d1['PRJCODE']	= $PRJCODE;
					$d1['ITM_CODE']	= $d['ITM_CODE'];
					$d1['ITM_CATEG']= $d['ITM_CATEG'];
					$d1['ITM_UNIT']	= $d['ITM_UNIT'];
					$d1['ITM_QTY']	= $d['ITM_QTY'];
					$ITMVOLM		= $d['ITM_QTY'];
					$d1['ITM_PRICE']= $d['ITM_PRICE'];
					$ITM_PRICE		= $d['ITM_PRICE'];						
					$ITMAMOUNT		= $ITMVOLM * $ITM_PRICE;
					$d1['ITM_TOTAL']= $ITMAMOUNT;
					
					$JO_TOTVOLM		= $JO_TOTVOLM + $ITMVOLM;
					$JO_TOTCOST		= $JO_TOTCOST + $ITMAMOUNT;
					$this->db->insert('tbl_jo_detail',$d1);
				}
			
				$updJOH	= array('JO_VOLM' => $JO_TOTVOLM, 'JO_AMOUNT' => $JO_TOTCOST);
				$this->m_rejoborder->updateJOH($JO_NUM, $updJOH);
			// END : INSERT DETAIL FG
			
			// START : INSERT DETAIL PROCESS
				foreach($_POST['selSTEP'] as $dSTEP)
				{
					$P_STEP		= $dSTEP['P_STEP'];
					$MCN_NUM	= $dSTEP['MCN_NUM'];
					$JOSTF_ORD	= $dSTEP['JOSTF_ORD'];
					$MCCODE 	= '';
					$MCNAME 	= '';
					$MCSCAL		= 1;
					$sqlMCNC	= "tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
					$resMCNC	= $this->db->count_all($sqlMCNC);
					if($resMCNC > 0)
					{
						$sqlMCN		= "SELECT MCN_CODE, MCN_ITMCAL FROM tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
						$resMCN		= $this->db->query($sqlMCN)->result();
						foreach($resMCN as $mcnNm):
							$MCCODE = $mcnNm->MCN_CODE;
							$MCSCAL = $mcnNm->MCN_ITMCAL;
						endforeach;
					}

					$TOTIN_AMN	= 0;
					if(isset($_POST["dataRM$P_STEP"]))
					{
						foreach($_POST["dataRM$P_STEP"] as $dRM1)
						{
							$dRM['JOSTF_NUM']	= $dRM1['JOSTF_NUM'];
							$dRM['PRJCODE']		= $PRJCODE;
							$dRM['JO_NUM']		= $JO_NUM;
							$dRM['JO_CODE']		= $JO_CODE;
							$dRM['JO_CATEG']	= 2;
							$dRM['SO_NUM']		= $SO_NUM;
							$dRM['SO_CODE']		= $SO_CODE;
							$dRM['CCAL_NUM']	= $CCAL_NUM;
							$dRM['CCAL_CODE']	= $CCAL_CODE;
							$dRM['BOM_NUM']		= $BOM_NUM;
							$dRM['BOM_CODE']	= $BOM_CODE;
							$dRM['JOSTF_ORD']	= $JOSTF_ORD;
							$dRM['MCN_NUM']		= $MCN_NUM;
							$dRM['MCN_CODE']	= $MCCODE;
							$dRM['JOSTF_STEP']	= $P_STEP;
							$dRM['JOSTF_TYPE']	= $dRM1['ITM_TYPE'];
							$dRM['ITM_CODE']	= $dRM1['ITM_CODE'];
							$dRM['ITM_GROUP']	= $dRM1['ITM_GROUP'];
							$dRM['ITM_NAME']	= $dRM1['ITM_NAME'];
							$dRM['ITM_UNIT']	= $dRM1['ITM_UNIT'];
							$dRM['BOM_QTY']		= $dRM1['BOM_QTY'];
							$dRM['BOM_PRICE']	= $dRM1['BOM_PRICE'];
							$dRM['ITM_SCALE']	= $MCSCAL;
							$dRM['ITM_QTY']		= $dRM1['ITM_QTY'];
							$dRM['ITM_PRICE']	= $dRM1['ITM_PRICE'];

							$ITMQTY				= $dRM1['ITM_QTY'];
							$ITMPRICE			= $dRM1['ITM_PRICE'];
							$TOTIN_AMN1			= $ITMQTY * $ITMPRICE;
							$TOTIN_AMN 			= $TOTIN_AMN + $TOTIN_AMN1;
							$this->db->insert('tbl_jo_stfdetail',$dRM);
						}
					}
					
					if(isset($_POST["dataFG$P_STEP"]))
					{
						foreach($_POST["dataFG$P_STEP"] as $dFG1)
						{
							$dFG['JOSTF_NUM']	= $dFG1['JOSTF_NUM'];
							$dFG['PRJCODE']		= $PRJCODE;
							$dFG['JO_NUM']		= $JO_NUM;
							$dFG['JO_CODE']		= $JO_CODE;
							$dFG['JO_CATEG']	= 2;
							$dFG['SO_NUM']		= $SO_NUM;
							$dFG['SO_CODE']		= $SO_CODE;
							$dFG['CCAL_NUM']	= $CCAL_NUM;
							$dFG['CCAL_CODE']	= $CCAL_CODE;
							$dFG['BOM_NUM']		= $BOM_NUM;
							$dFG['BOM_CODE']	= $BOM_CODE;
							$dFG['JOSTF_ORD']	= $JOSTF_ORD;
							$dFG['JOSTF_ORD']	= $JOSTF_ORD;
							$dFG['MCN_NUM']		= $MCN_NUM;
							$dFG['MCN_CODE']	= $MCCODE;
							$dFG['JOSTF_STEP']	= $P_STEP;
							$dFG['JOSTF_TYPE']	= $dFG1['ITM_TYPE'];
							$dFG['ITM_CODE']	= $dFG1['ITM_CODE'];
							$dFG['ITM_GROUP']	= $dFG1['ITM_GROUP'];
							$dFG['ITM_NAME']	= $dFG1['ITM_NAME'];
							$dFG['ITM_UNIT']	= $dFG1['ITM_UNIT'];
							$dFG['ITM_QTY']		= $dFG1['ITM_QTY'];
							// QTY OUTPUT WIP, DITETAPKAN KE 1 SAJA UNTUK PENGALI TOTAL BIAYA IN PTAHAP TERSEBUT
							//$dFG['ITM_QTY']		= 1;
							$dFG['ITM_PRICE']	= $TOTIN_AMN;
							$this->db->insert('tbl_jo_stfdetail',$dFG);
						}
					}
				}
			// END : INSERT DETAIL PROCESS

			// UPDATE STATUS PENGGUNAAN QRC PADA JO INI
			
			// UPDATE DETAIL
				//$this->m_rejoborder->updateDet($JO_NUM, $PRJCODE, $JO_DATE, $JO_PRODD);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('JO_STAT');			// IF "ADD" CONDITION ALWAYS = JO_STAT
				$parameters 	= array('DOC_CODE' 		=> $JO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "JO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_jo_header",	// TABLE NAME
										'KEY_NAME'		=> "JO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "JO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $JO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_JO",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_JO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_JO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_JO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_JO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_JO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_JO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JO_NUM",
										'DOC_CODE' 		=> $JO_NUM,
										'DOC_STAT' 		=> $JO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_jo_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SO_NUM;
				$MenuCode 		= 'MN417';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u77_j0b0rd3r() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joborder', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN417';
			$data["MenuApp"] 	= 'MN371';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$PRJCODE	= $splitCode[0];
			$JO_NUM		= $splitCode[1];
			
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Perintah Kerja";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Job Order";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_rej0b0rd3r/update_process');
			$cancelURL				= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN417';
			$data["MenuCode"] 		= 'MN417';
			
			$getJO 							= $this->m_rejoborder->get_jo_by_number($JO_NUM)->row();
			$data['default']['JO_NUM'] 		= $getJO->JO_NUM;
			$data['default']['JO_CODE'] 	= $getJO->JO_CODE;
			$data['default']['JO_RETYPE'] 	= $getJO->JO_RETYPE;
			$data['default']['PRJCODE'] 	= $getJO->PRJCODE;
			$data['default']['JO_DATE'] 	= $getJO->JO_DATE;
			$data['default']['JO_PRODD'] 	= $getJO->JO_PRODD;
			$data['default']['SO_NUM'] 		= $getJO->SO_NUM;
			$data['default']['SO_CODE'] 	= $getJO->SO_CODE;
			$data['default']['CUST_CODE'] 	= $getJO->CUST_CODE;
			$data['default']['CUST_DESC'] 	= $getJO->CUST_DESC;
			$data['default']['CUST_ADDRESS']= $getJO->CUST_ADDRESS;
			$data['default']['JO_DESC'] 	= $getJO->JO_DESC;
			$data['default']['JO_VOLM'] 	= $getJO->JO_VOLM;
			$data['default']['JO_AMOUNT'] 	= $getJO->JO_AMOUNT;
			$data['default']['JO_NOTES'] 	= $getJO->JO_NOTES;
			$data['default']['JO_NOTES2'] 	= $getJO->JO_NOTES2;
			$data['default']['JO_STAT'] 	= $getJO->JO_STAT;
			$data['default']['JO_NUMREF'] 	= $getJO->JO_NUMREF;
			$data['default']['JO_CODEREF'] 	= $getJO->JO_CODEREF;
			$data['default']['Patt_Year'] 	= $getJO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getJO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getJO->Patt_Date;
			$data['default']['Patt_Number'] = $getJO->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '$JO_NUM';
				$MenuCode 		= 'MN417';
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
			
			$this->load->view('v_production/v_joborder/v_rejoborder_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$JO_APPROVED 		= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_UC   		= substr($JO_NUM, -12);
			$JO_CODE 		= $this->input->post('JO_CODE');
			$JO_CATEG		= 2;
			$JO_RETYPE 		= $this->input->post('JO_RETYPE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_DATE		= date('Y-m-d',strtotime($this->input->post('JO_DATE')));
			$JO_PRODD		= date('Y-m-d',strtotime($this->input->post('JO_PRODD')));
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_DESC 		= $this->input->post('CUST_DESC');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$JO_DESC 		= $this->input->post('JO_DESC');
			//$JO_VOLM 		= $this->input->post('JO_VOLM');
			$JO_VOLM 		= 0;
			$JO_NOTES 		= $this->input->post('JO_NOTES');
			$JO_STAT		= $this->input->post('JO_STAT');
			$Patt_Year		= date('Y',strtotime($this->input->post('JO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('JO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('JO_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$OFF_NUM 		= '';
			$OFF_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlSOH 		= "SELECT OFF_NUM, OFF_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_so_header WHERE SO_NUM = '$SO_NUM' LIMIT 1";
			$resSOH 		= $this->db->query($sqlSOH)->result();
			foreach($resSOH as $rowSOH) :
				$OFF_NUM 	= $rowSOH->OFF_NUM;
				$OFF_CODE 	= $rowSOH->OFF_CODE;
				$CCAL_NUM 	= $rowSOH->CCAL_NUM;
				$CCAL_CODE 	= $rowSOH->CCAL_CODE;
				$BOM_NUM 	= $rowSOH->BOM_NUM;
				$BOM_CODE 	= $rowSOH->BOM_CODE;
			endforeach;
			
			$updJO			= array('JO_CODE' 		=> $JO_CODE,
									'JO_UC'			=> $JO_UC,
									'JO_CATEG'		=> 2,
									'JO_RETYPE' 	=> $JO_RETYPE,
									'PRJCODE' 		=> $PRJCODE,
									'JO_DATE'		=> $JO_DATE,
									'JO_PRODD'		=> $JO_PRODD,
									'SO_NUM'		=> $SO_NUM,
									'SO_CODE'		=> $SO_CODE,
									'CUST_CODE'		=> $CUST_CODE,
									'CUST_DESC' 	=> $CUST_DESC,
									'CUST_ADDRESS' 	=> $CUST_ADDRESS,
									'OFF_NUM'		=> $OFF_NUM,
									'OFF_CODE'		=> $OFF_CODE,
									'CCAL_NUM'		=> $CCAL_NUM,
									'CCAL_CODE'		=> $CCAL_CODE,
									'BOM_NUM'		=> $BOM_NUM,
									'BOM_CODE'		=> $BOM_CODE,
									'JO_DESC' 		=> $JO_NOTES,
									'JO_VOLM' 		=> $JO_VOLM,									
									'JO_NOTES' 		=> $JO_NOTES, 
									'JO_STAT' 		=> $JO_STAT,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_rejoborder->updateJO($JO_NUM, $updJO);
			
			// UPDATE JOBDETAIL ITEM
			if($JO_STAT == 6) // HOLD
			{
				foreach($_POST['data'] as $d)
				{
					$JO_NUM		= $d['JO_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					//$this->m_rejoborder->updateVolBud($SO_NUM, $PRJCODE, $ITM_CODE); // HOLD
				}
			}
			elseif($JO_STAT == 9)	// VOID
			{
				foreach($_POST['data'] as $d)
				{
					$ITM_CODE	= $d['ITM_CODE'];
					$ITM_QTY	= $d['ITM_QTY'];
					$ITM_TOTAL	= $d['ITM_TOTAL'];
					
					$prm 		= array('SO_NUM' 	=> $SO_NUM,
										'ITM_CODE'	=> $ITM_CODE,
										'ITM_QTY' 	=> $ITM_QTY,
										'ITM_TOTAL' => $ITM_TOTAL,
										'PRJCODE'	=> $PRJCODE);
					$this->m_rejoborder->updVOID($prm);
				}
			}
			else
			{
				$this->m_rejoborder->deleteJODetail($JO_NUM);
				
				// START : INSERT DETAIL FG
					$JO_TOTVOLM	= 0;
					$JO_TOTCOST	= 0;
					foreach($_POST['data1'] as $d)
					{
						$d1['JO_NUM']	= $JO_NUM;
						$d1['JO_CODE']	= $JO_CODE;
						$d1['JO_CATEG']	= 2;
						$d1['JO_DATE']	= $JO_DATE;
						$d1['JO_PRODD']	= $JO_PRODD;
						$d1['SO_NUM']	= $SO_NUM;
						$d1['SO_CODE']	= $SO_CODE;
						$d1['PRJCODE']	= $PRJCODE;
						$d1['ITM_CODE']	= $d['ITM_CODE'];
						$d1['ITM_CATEG']= $d['ITM_CATEG'];
						$d1['ITM_UNIT']	= $d['ITM_UNIT'];
						$d1['ITM_QTY']	= $d['ITM_QTY'];
						$ITMVOLM		= $d['ITM_QTY'];
						$d1['ITM_PRICE']= $d['ITM_PRICE'];
						$ITM_PRICE		= $d['ITM_PRICE'];						
						$ITMAMOUNT		= $ITMVOLM * $ITM_PRICE;
						$d1['ITM_TOTAL']= $ITMAMOUNT;
						
						$JO_TOTVOLM		= $JO_TOTVOLM + $ITMVOLM;
						$JO_TOTCOST		= $JO_TOTCOST + $ITMAMOUNT;
						$this->db->insert('tbl_jo_detail',$d1);
					}
				
					$updJOH	= array('JO_VOLM' => $JO_TOTVOLM, 'JO_AMOUNT' => $JO_TOTCOST);
					$this->m_rejoborder->updateJOH($JO_NUM, $updJOH);
				// END : INSERT DETAIL FG
				
				// START : INSERT DETAIL PROCESS
					$this->m_rejoborder->deleteJOSTFDetail($JO_NUM);
					
					$TRXTIME1		= date('ymdHis');
					$JOSTF_NUM		= "STFQC".$TRXTIME1;
					$GTOTIN_AMN 	= 0;
					foreach($_POST['selSTEP'] as $dSTEP)
					{
						$P_STEP		= $dSTEP['P_STEP'];
						$MCN_NUM	= $dSTEP['MCN_NUM'];
						$JOSTF_ORD	= $dSTEP['JOSTF_ORD'];
						$MCCODE 	= '';
						$MCNAME 	= '';
						$MCSCAL		= 1;
						$sqlMCNC	= "tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
						$resMCNC	= $this->db->count_all($sqlMCNC);
						if($resMCNC > 0)
						{
							$sqlMCN		= "SELECT MCN_CODE, MCN_ITMCAL FROM tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
							$resMCN		= $this->db->query($sqlMCN)->result();
							foreach($resMCN as $mcnNm):
								$MCCODE = $mcnNm->MCN_CODE;
								$MCSCAL = $mcnNm->MCN_ITMCAL;
							endforeach;
						}

						if(isset($_POST["dataRM$P_STEP"]))
						{
							$TOTIN_AMN	= 0;
							foreach($_POST["dataRM$P_STEP"] as $dRM1)
							{
								$dRM['JOSTF_NUM']	= $JOSTF_NUM;
								$dRM['PRJCODE']		= $PRJCODE;
								$dRM['JO_NUM']		= $JO_NUM;
								$dRM['JO_CODE']		= $JO_CODE;
								$dRM['JO_CATEG']	= 2;
								$dRM['SO_NUM']		= $SO_NUM;
								$dRM['SO_CODE']		= $SO_CODE;
								$dRM['CCAL_NUM']	= $CCAL_NUM;
								$dRM['CCAL_CODE']	= $CCAL_CODE;
								$dRM['BOM_NUM']		= $BOM_NUM;
								$dRM['BOM_CODE']	= $BOM_CODE;
								$dRM['JOSTF_ORD']	= $JOSTF_ORD;
								$dRM['MCN_NUM']		= $MCN_NUM;
								$dRM['MCN_CODE']	= $MCCODE;
								$dRM['JOSTF_STEP']	= $P_STEP;
								$dRM['JOSTF_TYPE']	= $dRM1['ITM_TYPE'];
								$dRM['ITM_CODE']	= $dRM1['ITM_CODE'];
								$dRM['ITM_GROUP']	= $dRM1['ITM_GROUP'];
								$dRM['ITM_NAME']	= $dRM1['ITM_NAME'];
								$dRM['ITM_UNIT']	= $dRM1['ITM_UNIT'];
								$dRM['BOM_QTY']		= $dRM1['BOM_QTY'];
								$dRM['BOM_PRICE']	= $dRM1['BOM_PRICE'];
								$dRM['ITM_SCALE']	= $MCSCAL;
								$dRM['ITM_QTY']		= $dRM1['ITM_QTY'];
								$dRM['ITM_PRICE']	= $dRM1['ITM_PRICE'];

								$ITMQTY				= $dRM1['ITM_QTY'];
								$ITMPRICE			= $dRM1['ITM_PRICE'];
								$TOTIN_AMN1			= $ITMQTY * $ITMPRICE;
								$TOTIN_AMN 			= $TOTIN_AMN + $TOTIN_AMN1;
								$GTOTIN_AMN			= $GTOTIN_AMN + $TOTIN_AMN1;
								$this->db->insert('tbl_jo_stfdetail',$dRM);
							}
						}
						
						if(isset($_POST["dataFG$P_STEP"]))
						{
							foreach($_POST["dataFG$P_STEP"] as $dFG1)
							{
								$dFG['JOSTF_NUM']	= $JOSTF_NUM;
								$dFG['PRJCODE']		= $PRJCODE;
								$dFG['JO_NUM']		= $JO_NUM;
								$dFG['JO_CODE']		= $JO_CODE;
								$dFG['JO_CATEG']	= 2;
								$dFG['SO_NUM']		= $SO_NUM;
								$dFG['SO_CODE']		= $SO_CODE;
								$dFG['CCAL_NUM']	= $CCAL_NUM;
								$dFG['CCAL_CODE']	= $CCAL_CODE;
								$dFG['BOM_NUM']		= $BOM_NUM;
								$dFG['BOM_CODE']	= $BOM_CODE;
								$dFG['JOSTF_ORD']	= $JOSTF_ORD;
								$dFG['MCN_NUM']		= $MCN_NUM;
								$dFG['MCN_CODE']	= $MCCODE;
								$dFG['JOSTF_STEP']	= $P_STEP;
								$dFG['JOSTF_TYPE']	= $dFG1['ITM_TYPE'];
								$dFG['ITM_CODE']	= $dFG1['ITM_CODE'];
								$dFG['ITM_GROUP']	= $dFG1['ITM_GROUP'];
								$dFG['ITM_NAME']	= $dFG1['ITM_NAME'];
								$dFG['ITM_UNIT']	= $dFG1['ITM_UNIT'];
								$dFG['ITM_QTY']		= $dFG1['ITM_QTY'];
								// QTY OUTPUT WIP, DITETAPKAN KE 1 SAJA UNTUK PENGALI TOTAL BIAYA IN PTAHAP TERSEBUT
								//$dFG['ITM_QTY']		= 1;
								$dFG['ITM_PRICE']	= $TOTIN_AMN;
								$this->db->insert('tbl_jo_stfdetail',$dFG);
							}
						}
					}
				// END : INSERT DETAIL PROCESS

				// UPDATE DETAIL JO
					//$this->m_rejoborder->updateDet($JO_NUM, $PRJCODE, $JO_DATE, $JO_PRODD);
					$this->m_rejoborder->updateDetPCost($JO_NUM, $PRJCODE, $GTOTIN_AMN);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('JO_STAT');			// IF "ADD" CONDITION ALWAYS = JO_STAT
				$parameters 	= array('DOC_CODE' 		=> $JO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "JO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_jo_header",	// TABLE NAME
										'KEY_NAME'		=> "JO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "JO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $JO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_JO",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_JO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_JO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_JO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_JO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_JO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_JO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JO_NUM",
										'DOC_CODE' 		=> $JO_NUM,
										'DOC_STAT' 		=> $JO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_jo_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $JO_NUM;
				$MenuCode 		= 'MN417';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function iNb0x() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_rej0b0rd3r/p07_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
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
			
			// GET MENU DESC
				$mnCode				= 'MN417';
				$data["MenuApp"] 	= 'MN371';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Perintah Kerja";
			}
			else
			{
				$data["h1_title"] 	= "Job Order Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN371';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_rej0b0rd3r/glj0b0rd3r_1Nb/?id=";
			
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
	
	function glj0b0rd3r_1Nb() // OK
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN371';
			$data["MenuApp"] 	= 'MN371';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
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
				$data["url_search"] = site_url('c_production/c_rej0b0rd3r/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_rejoborder->count_all_JOInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_rejoborder->get_all_JOInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Perintah Kerja";
				$data["h2_title"] 	= "Perintah Kerja Pembelian";
			}
			else
			{
				$data["h1_title"] 	= "Job Order Approval";
				$data["h2_title"] 	= "Purchase Return";
			}
			
			$data['backURL'] 	= site_url('c_production/c_rej0b0rd3r/iNb0x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$MenuCode 			= 'MN371';
			$data["MenuCode"] 	= 'MN371';
			/*$MaxLimitApp		= $this->m_rejoborder->getMyMaxLimit($MenuCode, $DefEmp_ID);
			$RangeAPP			= explode("~", $MaxLimitApp);
			$RangeMin			= $RangeAPP[0];
			$RangeMax			= $RangeAPP[1];
			$data["RangeMin"] 	= $RangeMin;
			$data["RangeMax"] 	= $RangeMax;*/
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN371';
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
			
			$this->load->view('v_production/v_joborder/v_inb_joborder', $data);
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
			$url			= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r_1Nb/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function up180djinb() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joborder', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN371';
			$data["MenuApp"] 	= 'MN371';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$PRJCODE	= $splitCode[0];
			$JO_NUM		= $splitCode[1];
			
			$EmpID 			= $this->session->userdata('Emp_ID');

			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Perintah Kerja";
			}
			else
			{
				$data["h1_title"] 	= "Job Order Approval";
			}
			
			$data['form_action']	= site_url('c_production/c_rej0b0rd3r/update_process_inb');
			$cancelURL				= site_url('c_production/c_j0b0rd3/glj0b0rd3r_1Nb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN371';
			$data["MenuCode"] 		= 'MN371';
			
			$getJO 							= $this->m_rejoborder->get_jo_by_number($JO_NUM)->row();
			$data['default']['JO_NUM'] 		= $getJO->JO_NUM;
			$data['default']['JO_CODE'] 	= $getJO->JO_CODE;
			$data['default']['PRJCODE'] 	= $getJO->PRJCODE;
			$data['default']['JO_DATE'] 	= $getJO->JO_DATE;
			$data['default']['JO_PRODD'] 	= $getJO->JO_PRODD;
			$data['default']['SO_NUM'] 		= $getJO->SO_NUM;
			$data['default']['SO_CODE'] 	= $getJO->SO_CODE;
			$data['default']['CUST_CODE'] 	= $getJO->CUST_CODE;
			$data['default']['CUST_DESC'] 	= $getJO->CUST_DESC;
			$data['default']['CUST_ADDRESS']= $getJO->CUST_ADDRESS;
			$data['default']['JO_DESC'] 	= $getJO->JO_DESC;
			$data['default']['JO_VOLM'] 	= $getJO->JO_VOLM;
			$data['default']['JO_AMOUNT'] 	= $getJO->JO_AMOUNT;
			$data['default']['JO_NOTES'] 	= $getJO->JO_NOTES;
			$data['default']['JO_NOTES2'] 	= $getJO->JO_NOTES2;
			$data['default']['JO_STAT'] 	= $getJO->JO_STAT;
			$data['default']['Patt_Year'] 	= $getJO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getJO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getJO->Patt_Date;
			$data['default']['Patt_Number'] = $getJO->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getJO->JO_NUM;
				$MenuCode 		= 'MN371';
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
			
			$this->load->view('v_production/v_joborder/v_inb_joborder_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$JO_APPROVED 		= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_DATE		= date('Y-m-d',strtotime($this->input->post('JO_DATE')));
			$JO_PRODD		= date('Y-m-d',strtotime($this->input->post('JO_PRODD')));
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_DESC 		= $this->input->post('CUST_DESC');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$JO_DESC 		= $this->input->post('JO_DESC');
			
			$JO_VOLM 		= $this->input->post('JO_VOLM');
			$JO_NOTES 		= $this->input->post('JO_NOTES');
			$JO_STAT		= $this->input->post('JO_STAT');
			
			if($JO_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $JO_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $JO_APPROVED;
					$AH_NOTES		= $this->input->post('JO_NOTES2');
					$AH_ISLAST		= $this->input->post('IS_LAST');
				
					$updJOH			= array('JO_STAT'	=> 7); // Default step approval
					$this->m_rejoborder->updateJOH($JO_NUM, $updJOH);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'PRJCODE'		=> $PRJCODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JO_NUM",
											'DOC_CODE' 		=> $JO_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_jo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			
			if($AH_ISLAST == 1)
			{
				$updJOH	= array('JO_APPROVER'	=> $DefEmp_ID,
								'JO_APPROVED'	=> $JO_APPROVED,
								'JO_NOTES2'		=> $this->input->post('JO_NOTES2'),
								'JO_STAT'		=> $this->input->post('JO_STAT'));
				$this->m_rejoborder->updateJOH($JO_NUM, $updJOH);
				
				// UPDATE JOBDETAIL ITEM
				if($JO_STAT == 3)
				{
					$JO_TOTAMN	= 0;		
					foreach($_POST['data1'] as $d)
					{
						$d['JO_NUM']	= $JO_NUM;
						$ITM_CODE		= $d['ITM_CODE'];
						$ITM_QTY		= $d['ITM_QTY'];
						$ITM_TOTAL		= $d['ITM_TOTAL'];
						$ITM_PRICE		= $d['ITM_PRICE'];
						$ITMAMOUNT		= $d['ITM_TOTAL'];
						$JO_TOTAMN		= $JO_TOTAMN + $ITMAMOUNT;
						
						// UPDATE SO & ITEM
						$prm 			= array('SO_NUM' 	=> $SO_NUM,
												'ITM_CODE'	=> $ITM_CODE,
												'ITM_QTY' 	=> $ITM_QTY,
												'ITM_PRICE' => $ITM_PRICE,
												'ITM_TOTAL' => $ITM_TOTAL,
												'PRJCODE'	=> $PRJCODE);
						$this->m_rejoborder->updSODET($prm);
					}
					
					// UPDATE SO HEADER
						$this->m_rejoborder->updateSOH($SO_NUM, $PRJCODE);

					// DELETE JOSTF_ORD = 99
						$sqlDEL 	= "DELETE FROM tbl_jo_stfdetail WHERE JOSTF_ORD = 99 AND JO_NUM = '$JO_NUM'";
						$this->db->query($sqlDEL);

					// PROCESS QRCODE PER STEP
						$sqlSTEP	= "SELECT A.PRJCODE, A.JOSTF_NUM, A.JO_CODE, A.JOSTF_STEP, A.JOSTF_TYPE, A.ITM_CODE, B.ICOLL_NUM,
											A.ITM_NAME, B.QRC_NUM, C.ITM_CODE AS ITM_CODEREF, C.IR_NUM, C.IR_CODE, C.QRC_PATT
										FROM tbl_jo_stfdetail A
											INNER JOIN tbl_item_colld B ON A.ITM_CODE = B.ICOLL_CODE
												AND B.PRJCODE = '$PRJCODE'
											INNER JOIN tbl_qrc_detail C ON C.QRC_NUM = B.QRC_NUM
												AND C.PRJCODE = '$PRJCODE'
										WHERE A.JO_NUM = '$JO_NUM'";
						$resSTEP	= $this->db->query($sqlSTEP)->result();
						foreach($resSTEP as $rowSTP):
							$PRJCODE	= $rowSTP->PRJCODE;
							$JOSTF_NUM	= $rowSTP->JOSTF_NUM;
							$JO_NUM		= $JO_NUM;
							$JO_CODE 	= $rowSTP->JO_CODE;
							$JOSTF_STEP	= $rowSTP->JOSTF_STEP;
							$JOSTF_CAT	= $rowSTP->JOSTF_TYPE;
							$QRC_NUM	= $rowSTP->QRC_NUM;
							$ICOLL_NUM	= $rowSTP->ICOLL_NUM;
							$IR_NUM		= $rowSTP->IR_NUM;
							$IR_CODE	= $rowSTP->IR_CODE;
							$ITM_CODE	= $rowSTP->ITM_CODE;
							$ITM_CODEREF= $rowSTP->ITM_CODEREF;
							$ITM_NAME	= $rowSTP->ITM_NAME;
							$QRC_PATT	= $rowSTP->QRC_PATT;

							$sqlINQRC	= "INSERT INTO tbl_jo_stfdetail_qrc (PRJCODE, JOSTF_NUM, JO_NUM, JO_CODE, JOSTF_STEP, 
												JOSTF_CAT, QRC_NUM, IR_NUM, IR_CODE, ITM_CODE, ITM_CODEREF, ITM_NAME, QRC_PATT)
											VALUES
												('$PRJCODE', '$JOSTF_NUM', '$JO_NUM', '$JO_CODE', '$JOSTF_STEP', '$JOSTF_CAT', 
												'$QRC_NUM', '$IR_NUM', '$IR_CODE', '$ITM_CODE', '$ITM_CODEREF', '$ITM_NAME',
												'$QRC_PATT')";
							$this->db->query($sqlINQRC);

							// UPDATE QRC STAT
								$sqlUPQRC	= "UPDATE tbl_qrc_detail SET QRC_STAT = 3, JO_CREATED = 1, JO_NUM = '$JO_NUM', JO_CODE = '$JO_CODE'
													WHERE QRC_NUM = '$QRC_NUM'";
								$this->db->query($sqlUPQRC);

							// UPDATE tbl_item_collh
								$sqlUPcollH	= "UPDATE tbl_item_collh SET JO_NUM = '$JO_NUM', JO_CODE = '$JO_CODE' WHERE ICOLL_NUM = '$ICOLL_NUM'";
								$this->db->query($sqlUPcollH);

							// UPDATE tbl_item_colld
								$sqlUPcollD	= "UPDATE tbl_item_colld SET JO_NUM = '$JO_NUM', JO_CODE = '$JO_CODE' WHERE ICOLL_NUM = '$ICOLL_NUM'";
								$this->db->query($sqlUPcollD);
						endforeach;

					// PROCESS SET PRICE -- belum selesai
						$sqlPRC	= "SELECT A.PRJCODE, A.JOSTF_NUM, A.JO_CODE, A.JOSTF_STEP, A.JOSTF_TYPE, A.ITM_CODE, 
											A.ITM_NAME, B.QRC_NUM, C.ITM_CODE AS ITM_CODEREF, C.IR_NUM, C.IR_CODE, C.QRC_PATT
										FROM tbl_jo_stfdetail A
											INNER JOIN tbl_item_colld B ON A.ITM_CODE = B.ICOLL_CODE
												AND B.PRJCODE = 'BQ19OKT-FBSHO'
											INNER JOIN tbl_qrc_detail C ON C.QRC_NUM = B.QRC_NUM
												AND C.PRJCODE = 'BQ19OKT-FBSHO'
										WHERE A.JO_NUM = '$JO_NUM'";
						$resSTEP	= $this->db->query($sqlPRC)->result();
						foreach($resSTEP as $rowSTP):
							$PRJCODE	= $rowSTP->PRJCODE;
						endforeach;
				}
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
				
					$STAT_BEFORE	= $this->input->post('JO_STAT');			// IF "ADD" CONDITION ALWAYS = JO_STAT
					$parameters 	= array('DOC_CODE' 		=> $JO_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "JO",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_jo_header",	// TABLE NAME
											'KEY_NAME'		=> "JO_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "JO_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $JO_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_JO",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_JO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_JO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_JO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_JO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_JO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_JO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JO_NUM",
											'DOC_CODE' 		=> $JO_NUM,
											'DOC_STAT' 		=> $JO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_jo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $SO_NUM;
					$MenuCode 		= 'MN371';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
							
			$url	= site_url('c_production/c_rej0b0rd3r/glj0b0rd3r_1Nb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallPR()
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		
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
			
			$data['title'] 				= $appName;
			$data['pageFrom']			= 'PR';
			$data['PRJCODE']			= $PRJCODE;
					
			$this->load->view('v_production/v_joborder/v_rejoborder_sel_req', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // OK
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$PODate		= date('Y-m-d',strtotime($this->input->post('PODate')));
		$year		= date('Y',strtotime($this->input->post('PODate')));
		$month 		= (int)date('m',strtotime($this->input->post('PODate')));
		$date 		= (int)date('d',strtotime($this->input->post('PODate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_SO_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_SO_header
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
		
		// OKroup year, month and date
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
	
	function printirlist()
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$SO_NUM		= $_GET['id'];
		$SO_NUM		= $this->url_encryption_helper->decode_url($SO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;
			$SO_DATE 		= "";
			$SO_NOTES 		= "";	
			$sqlPR 			= "SELECT SO_DATE, SO_NOTES FROM tbl_SO_header WHERE SO_NUM = '$SO_NUM'";
			$resultaPR 		= $this->db->query($sqlPR)->result();
			foreach($resultaPR as $rowPR) :
				$SO_DATE 	= $rowPR->SO_DATE;
				$SO_NOTES 	= $rowPR->SO_NOTES;		
			endforeach;
			
			$data['SO_NUM'] 	= $SO_NUM;
			$data['SO_DATE'] 	= $SO_DATE;
			$data['SO_NOTES'] 	= $SO_NOTES;
			
			$data['countIR']	= $this->m_rejoborder->count_all_IR($SO_NUM);
			$data['vwIR'] 		= $this->m_rejoborder->get_all_IR($SO_NUM)->result();	
							
			$this->load->view('v_sales/v_so/print_irlist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdoc()
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$SO_NUM		= $_GET['id'];
		$SO_NUM		= $this->url_encryption_helper->decode_url($SO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getSO 			= $this->m_rejoborder->get_SO_by_number($SO_NUM)->row();
			
			$data['SO_NUM'] 	= $getSO->SO_NUM;
			$data['SO_CODE'] 	= $getSO->SO_CODE;
			$data['PR_CODE'] 	= $getSO->PR_CODE;
			$data['SO_DATE'] 	= $getSO->SO_DATE;
			$data['SO_DUED'] 	= $getSO->SO_DUED;
			$data['PRJCODE'] 	= $getSO->PRJCODE;
			$data['CUST_CODE'] 	= $getSO->CUST_CODE;
			$data['PR_NUM'] 	= $getSO->PR_NUM;
			$data['SO_PAYTYPE'] = $getSO->SO_PAYTYPE;
			$data['SO_TENOR'] 	= $getSO->SO_TENOR;
			$data['SO_TERM'] 	= $getSO->SO_TERM;
			$data['SO_NOTES'] 	= $getSO->SO_NOTES;
			$data['PRJNAME'] 	= $getSO->PRJNAME;
			$data['JO_STAT'] 	= $getSO->JO_STAT;
			$data['SO_RECEIVLOC']= $getSO->SO_RECEIVLOC;
			$data['SO_RECEIVCP'] = $getSO->SO_RECEIVCP;
			$data['SO_SENTROLES']= $getSO->SO_SENTROLES;
			$data['SO_REFRENS']	= $getSO->SO_REFRENS;
							
			$this->load->view('v_sales/v_so/print_po', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function m0N7_j0() // G
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_rej0b0rd3r/prj1_m0N7j0/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj1_m0N7j0() // G
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
				$data["h1_title"] 	= "Perintah Kerja";
			}
			else
			{
				$data["h1_title"] 	= "Job Order";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN417';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_rej0b0rd3r/m0N7_j0X/?id=";
			
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
	
	function m0N7_j0X() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joborder', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $this->data['PRJCODE'];	
			$PRJNAME		= '';		
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE	= $this->session->userdata['proj_Code'];
			}
			$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$result 	= $this->db->query($sql)->result();
			foreach($result as $row) :
				$PRJNAME = $row ->PRJNAME;
			endforeach;
			
			$EmpID 			= $this->session->userdata('Emp_ID');
						
			$data['title'] 			= $appName;
			$data['PRJCODE']		= $PRJCODE;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Monitoring JO";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "JO Monitoring";
				$data['h2_title'] 	= 'Customer';
			}
			
			$MenuCode 				= 'MN397';
			$data["MenuCode"] 		= 'MN397';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN397';
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
	
			$this->load->view('v_production/v_joborder/v_rejoborder_mnt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
        
	function getSTEPLIST($dataColl) // GOOD
	{
		$data		= explode("~", $dataColl);
		$PRJCODE	= $data[0];
		$PRODS_STEP	= $data[1];
		
		// GET ID
		$PRODS_ID	= 11;
		$sqlCURSTF	= "SELECT PRODS_ID FROM tbl_prodstep WHERE PRODS_STEP  = '$PRODS_STEP'";
		$resCURSTF	= $this->db->query($sqlCURSTF)->result();
		foreach($resCURSTF as $rowCSTF) :
			$PRODS_ID	= $rowCSTF->PRODS_ID;
		endforeach;
													
		$sqlDEST	= "SELECT PRODS_CODE, PRODS_STEP, PRODS_NAME FROM tbl_prodstep WHERE PRODS_ID > $PRODS_ID ORDER BY PRODS_ID ASC";
		$resDEST	= $this->db->query($sqlDEST)->result();
		echo json_encode($resDEST);
	}
	
	function printQR()
	{
		$this->load->model('m_production/m_joborder', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$JO_NUM		= $_GET['id'];
		$JO_NUM		= $this->url_encryption_helper->decode_url($JO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getJO 			= $this->m_rejoborder->get_jo_by_number($JO_NUM)->row();
			
			$data['default']['JO_NUM'] 		= $getJO->JO_NUM;
			$data['default']['JO_CODE'] 	= $getJO->JO_CODE;
			$data['default']['PRJCODE'] 	= $getJO->PRJCODE;
			$data['default']['JO_DATE'] 	= $getJO->JO_DATE;
			$data['default']['JO_PRODD'] 	= $getJO->JO_PRODD;
			$data['default']['SO_NUM'] 		= $getJO->SO_NUM;
			$data['default']['SO_CODE'] 	= $getJO->SO_CODE;
			$data['default']['CUST_CODE'] 	= $getJO->CUST_CODE;
			$data['default']['CUST_DESC'] 	= $getJO->CUST_DESC;
			$data['default']['CUST_ADDRESS']= $getJO->CUST_ADDRESS;
			$data['default']['JO_DESC'] 	= $getJO->JO_DESC;
			$data['default']['JO_VOLM'] 	= $getJO->JO_VOLM;
			$data['default']['JO_AMOUNT'] 	= $getJO->JO_AMOUNT;
			$data['default']['JO_NOTES'] 	= $getJO->JO_NOTES;
			$data['default']['JO_NOTES2'] 	= $getJO->JO_NOTES2;
			$data['default']['JO_STAT'] 	= $getJO->JO_STAT;
							
			$this->load->view('v_production/v_joborder/print_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getQRCL()
	{
		echo "test";
	}

	function add_processMR() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];

		date_default_timezone_set("Asia/Jakarta");

		$CollID		= $_GET['id'];
		$CollID		= $this->url_encryption_helper->decode_url($CollID);
		
		$splitCode 	= explode("~", $CollID);
		$PRJCODE	= $splitCode[0];
		$JO_NUM		= $splitCode[1];

		$MR_CREATED 	= date('Y-m-d H:i:s');
		
		// START - PEMBENTUKAN GENERATE CODE
			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			$Pattern_Code	= "XX";
			$MenuCode 		= 'MN374';
			$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
			foreach($vwDocPatt as $row) :
				$Pattern_Code = $row->Pattern_Code;
			endforeach;
			
			//$PRJCODE 		= $this->input->post('PRJCODE');
			$PRJCODE 		= $PRJCODE;
			$TRXTIME1		= date('ymdHis');
			$MR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";

			$sqlMRC 		= "tbl_mr_header";
			$resMRC 		= $this->db->count_all($sqlMRC);
			$lastPattNo		= $resMRC + 1;
		
			$JO_CODE 		= '';
			$JO_PRODD		= '';
			$SO_NUM 		= '';
			$SO_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$JO_DESC		= '';
			$sqlJOH 		= "SELECT JO_CODE, JO_PRODD, SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE, JO_DESC
								FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
			$resJOH 		= $this->db->query($sqlJOH)->result();
			foreach($resJOH as $rowJOH) :
				$JO_CODE 	= $rowJOH->JO_CODE;
				$JO_PRODD 	= $rowJOH->JO_PRODD;
				$SO_NUM 	= $rowJOH->SO_NUM;
				$SO_CODE 	= $rowJOH->SO_CODE;
				$CCAL_NUM 	= $rowJOH->CCAL_NUM;
				$CCAL_CODE 	= $rowJOH->CCAL_CODE;
				$BOM_NUM 	= $rowJOH->BOM_NUM;
				$BOM_CODE 	= $rowJOH->BOM_CODE;
				$JO_DESC 	= $rowJOH->JO_DESC;
			endforeach;

			$sqlJOHC 		= "tbl_mr_header WHERE JO_NUM = '$JO_NUM'";
			$resJOHC 		= $this->db->count_all($sqlJOHC);
			$maxNo			= $resJOHC + 1;

			$PattLength 	= 5;
			$len 			= strlen($maxNo);
			
			if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else if($len==5) $nol="";
			$lastPattNum 	= $nol.$len;
			$newPattNum		= $Pattern_Code-$JO_CODE.$lastPattNum;
			$MR_CODE		= $newPattNum;
		// END - PEMBENTUKAN GENERATE CODE
		
		$MR_CODE 		= $MR_CODE;
		$MR_DATE		= date('Y-m-d');
		$MR_DATEU		= $JO_PRODD;
		$PRJCODE 		= $PRJCODE;
		$JO_NUM 		= $JO_NUM;
		$JO_CODE 		= $JO_CODE;
		$MR_NOTE 		= $JO_DESC;
		$MR_STAT 		= 2;
		$MR_REFNO 		= $JO_NUM;
		$Patt_Year		= date('Y');
		$Patt_Month		= date('m');
		$Patt_Date		= date('d');
		$Patt_Number	= 
		
		$PRREFNO		= $JO_NUM;
		
		$projMatReqH 	= array('MR_NUM' 		=> $MR_NUM,
								'MR_CODE' 		=> $MR_CODE,
								'MR_DATE'		=> $MR_DATE,
								'MR_DATEU'		=> $MR_DATEU,
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
								'MR_REFNO'		=> $MR_REFNO,
								'Patt_Year'		=> $Patt_Year,
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $lastPattNo);
		$this->m_matreq->add($projMatReqH);
		
		$PRMTOTAL	= 0;
		$sqlJOD 	= "SELECT A.ITM_CODE, B.ITM_GROUP, B.ITM_CATEG, A.ITM_UNIT, A.ITM_QTY, A.ITM_PRICE,
							(A.ITM_QTY * A.ITM_PRICE) AS ITM_TOTAL, A.SO_NUM FROM tbl_jo_stfdetail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE' AND B.ISRM = 1
						WHERE A.JO_NUM = '$JO_NUM'";
		$resJOD 	= $this->db->query($sqlJOD)->result();
		foreach($resJOD as $rowJOD) :
			$d['MR_NUM']	= $MR_NUM;
			$d['MR_CODE']	= $MR_CODE;
			$d['MR_DATE']	= $MR_DATE;
			$d['MR_DATEU']	= $JO_PRODD;
			$d['MR_DATER']	= '';
			$d['PRJCODE']	= $PRJCODE;
			$d['JO_NUM']	= $JO_NUM;
			$d['JO_CODE']	= $JO_CODE;
			$d['JOBCODEID']	= '';
			$d['JOBCODEDET']= '';
			$d['ITM_CODE']	= $rowJOD->ITM_CODE;
			$ITM_CODE		= $rowJOD->ITM_CODE;
			$d['SNCODE']	= '';
			$d['ITM_GROUP']	= $rowJOD->ITM_GROUP;
			$d['ITM_CATEG']	= $rowJOD->ITM_CATEG;
			$d['ITM_UNIT']	= $rowJOD->ITM_UNIT;
			$d['MR_VOLM']	= $rowJOD->ITM_QTY;
			$MR_VOLM		= $rowJOD->ITM_QTY;
			$d['MR_PRICE']	= $rowJOD->ITM_PRICE;
			$d['MR_TOTAL']	= $rowJOD->ITM_TOTAL;
			$ITM_TOTAL		= $rowJOD->ITM_TOTAL;
			$d['MR_DESC']	= $rowJOD->SO_NUM;
			$PRMTOTAL		= $PRMTOTAL + $ITM_TOTAL;

			$sqlITMDC 		= "tbl_mr_detail WHERE MR_NUM = '$MR_NUM' AND ITM_CODE = '$ITM_CODE'";
			$resITMDC 		= $this->db->count_all($sqlITMDC);
			if($resITMDC > 0)
			{
				$sqlUPDMRV 	= "UPDATE tbl_mr_detail SET MR_VOLM = MR_VOLM+$MR_VOLM, MR_TOTAL = MR_TOTAL+$ITM_TOTAL
								WHERE MR_NUM = '$MR_NUM' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUPDMRV);
			}
			else
			{
				$this->db->insert('tbl_mr_detail',$d);
			}
		endforeach;

		// UPDATE JO HEADER
			$updJOH = "UPDATE tbl_jo_header SET MR_NUM = '$MR_NUM' WHERE JO_NUM = '$JO_NUM'";
			$this->db->query($updJOH);

		// UPDATE HEADER
			$prmHeader		= array('MR_AMOUNT'	=> $PRMTOTAL);
			$this->m_matreq->updateH($MR_NUM, $PRJCODE, $prmHeader);
			
		// START : UPDATE TO TRANS-COUNT
			$this->load->model('m_updash/m_updash', '', TRUE);
			
			$STAT_BEFORE	= 2;										// IF "ADD" CONDITION ALWAYS = MR_STAT
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
			echo "No. Pemrintaan telah dibuat : $MR_CODE";
	}
	
	function genMcnCal() // OK
	{
		$MCN_NUM 	= $this->input->post('option');
		$MCN_ITMCAL	= 0;
		$sqlMCN		= "SELECT MCN_ITMCAL FROM tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
    	$resMCN 	= $this->db->query($sqlMCN)->result();
    	foreach($resMCN as $rowMCN):
            $MCN_ITMCAL	= $rowMCN->MCN_ITMCAL;
        endforeach;
        echo $MCN_ITMCAL;
	}
}