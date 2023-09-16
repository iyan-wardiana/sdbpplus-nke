<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 22 Oktober 2018
 * File Name	= C_pR04uctpr0535.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_pR04uctpr0535 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$this->load->model('m_inventory/m_itemusage/m_itemusage', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
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
	
 	function index() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('c_production/c_pR04uctpr0535/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$data["MenuCode"] 	= 'MN377';
				$mnCode				= 'MN377';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
				
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN377';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_pR04uctpr0535/glpR04uctpr0535/?id=";
			
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

	function glpR04uctpr0535() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$data["MenuCode"] 	= 'MN377';
			$mnCode				= 'MN377';
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
				$data["url_search"] = site_url('c_production/c_pR04uctpr0535/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_prodprocess->count_all_PRD($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_prodprocess->get_all_PRD($PRJCODE, $start, $end, $key)->result();
				$data["MenuApp"] 	= "MN377";
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Proses Produksi";
				$data['h2_title'] 	= 'produksi';
			}
			else
			{
				$data["h1_title"] 	= "Production Process";
				$data['h2_title'] 	= 'prodcution';
			}
			
			//$data['addURL'] 	= site_url('c_production/c_pR04uctpr0535/a44_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN377';
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
			
			$this->load->view('v_production/v_prodprocess/v_prodprocess', $data);
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
			$url			= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 		= $this->input->get("order");

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
			
			$columns_valid 	= array("STF_NUM",
									"STF_DATE",
									"PRODS_ORDER",
									"PRODS_ORDER",
									"CUST_DESC",
									"JO_CODE",
									"SO_CODE",
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
			$num_rows 		= $this->m_prodprocess->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$PRJCODEB		= $PRJCODE;
			$searchB		= $search;
			$lengthB		= $length;
			$startB			= $start;
			$orderB			= $order;
			$dirB			= $dir;
			
			$query 			= $this->m_prodprocess->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$STF_NUM 		= $dataI['STF_NUM'];
                $STF_CODE 		= $dataI['STF_CODE'];
                $PRJCODE 		= $dataI['PRJCODE'];
                $STF_DATE 		= $dataI['STF_DATE'];
				$STF_DATEV		= date('d M Y', strtotime($STF_DATE));
                $PRJCODE 		= $dataI['PRJCODE'];
                $CUST_CODE 		= $dataI['CUST_CODE'];
                $CUST_DESC 		= $dataI['CUST_DESC'];
                $JO_NUM			= $dataI['JO_NUM'];
				$JO_CODE		= $dataI['JO_CODE'];
                $SO_NUM 		= $dataI['SO_NUM'];
                $SO_CODE 		= $dataI['SO_CODE'];
                $CCAL_NUM		= $dataI['CCAL_NUM'];
                $CCAL_CODE		= $dataI['CCAL_CODE'];
                $BOM_NUM		= $dataI['BOM_NUM'];
                $BOM_CODE		= $dataI['BOM_CODE'];
                $STF_FROM		= $dataI['STF_FROM'];
                $STF_DEST		= $dataI['STF_DEST'];
                $STF_NOTES		= $dataI['STF_NOTES'];
                $STF_STAT		= $dataI['STF_STAT'];
                $STATDESC		= $dataI['STATDESC'];
                $STATCOL		= $dataI['STATCOL'];
                $CREATERNM		= $dataI['CREATERNM'];
				
				$ORIG_ID		= 0;
				$ORIG_NAME		= '';
				$sqlSTEP1		= "SELECT PRODS_ID, PRODS_NAME FROM tbl_prodstep WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
				$resSTEP1		= $this->db->query($sqlSTEP1)->result();
				foreach($resSTEP1 as $rowSTP1):
					$ORIG_ID	= $rowSTP1->PRODS_ID;
					$ORIG_NAME	= $rowSTP1->PRODS_NAME;
				endforeach;
				
				$DEST_ID		= 0;
				$DEST_NAME		= '';
				$DEST_DESC		= '';
				if($STF_DEST == "")
				{
					$DEST_DESC	= "-";
				}
				else
				{
					$sqlSTEP2		= "SELECT PRODS_ID, PRODS_NAME FROM tbl_prodstep WHERE PRODS_STEP = '$STF_DEST' LIMIT 1";
					$resSTEP2		= $this->db->query($sqlSTEP2)->result();
					foreach($resSTEP2 as $rowSTP2):
						$DEST_ID	= $rowSTP2->PRODS_ID;
						$DEST_NAME	= $rowSTP2->PRODS_NAME;
					endforeach;
					$DEST_DESC	= "$DEST_ID. $DEST_NAME";
				}
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);

				$CollID		= "$STF_NUM~$JO_NUM~$PRJCODE";
				$secUpd		= site_url('c_production/c_pR04uctpr0535/u775o_pR04/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_production/c_pR04uctpr0535/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($STF_NUM));
				$CollID		= "STF~$STF_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_stf_header~tbl_stf_detail~STF_NUM~$STF_NUM~PRJCODE~$PRJCODE";
                
                $isDis		= "disabled='disabled'";

				if($STF_STAT == 3)
				{
					$isDis	= "";
				}

				if($STF_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' ".$isDis.">
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
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] = array($dataI['STF_CODE'],
										  $STF_DATEV,
										  "$ORIG_ID. $ORIG_NAME",
										  $DEST_DESC,
										  "$CUST_DESC",
										  $dataI['JO_CODE'],
										  $dataI['SO_CODE'],
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
										  
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_pR04uctpr0535() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN377';
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
						
			$data['title'] 			= $appName;
			$data['task']			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Proses Produksi";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Production Process";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/add_process');
			$cancelURL				= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN377';
			$data["MenuCode"] 		= 'MN377';
			$data['viewDocPattern'] = $this->m_prodprocess->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN377';
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
	
			$this->load->view('v_production/v_prodprocess/v_prodprocess_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
 	function s3l4llj0() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$COLLID		= $_GET['id'];
		$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);
		
		$url		= site_url('c_production/c_pR04uctpr0535/s3l4llj0_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		redirect($url);
	}
	
	function s3l4llj0_x1() // GOOD
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
					
			$this->load->view('v_production/v_prodprocess/v_prodprocess_seljo', $data);
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
			$JONUM		= $_GET['JONUM'];
			$JOCODE		= $_GET['JOCODE'];
			$STFFROM	= $_GET['STFFROM'];
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
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/update_process');
			$data['JONUM'] 			= $JONUM;
			$data['JOCODE'] 		= $JOCODE;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['STFFROM'] 		= $STFFROM;
					
			$this->load->view('v_production/v_prodprocess/v_prodprocess_selWIP', $data);
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
			$JONUM		= $_GET['JONUM'];
			$JOCODE		= $_GET['JOCODE'];
			$STFFROM	= $_GET['STFFROM'];
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
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/update_process');
			$data['JONUM'] 			= $JONUM;
			$data['JOCODE'] 		= $JOCODE;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['STFFROM'] 		= $STFFROM;
					
			$this->load->view('v_production/v_prodprocess/v_prodprocess_selRM', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a44QR_pR04uctpr0535() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN377';
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
							
			$data['title'] 			= $appName;
			$data['task']			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Transfer Proses";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Transfer Process";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/add_process');
			$cancelURL				= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			//$data['countCUST']		= $this->m_prodprocess->count_all_CUST();
			//$data['vwCUST'] 		= $this->m_prodprocess->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN377';
			$data["MenuCode"] 		= 'MN377';
			$data['viewDocPattern'] = $this->m_prodprocess->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN377';
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
	
			$this->load->view('v_production/v_prodprocess/v_prodprocess_section', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a44QRA0_pR04uctpr0535() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$PRJCODE	= $splitCode[0];
			$PRODS_STEP	= $splitCode[1];
			
			
			$EmpID 			= $this->session->userdata('Emp_ID');
						
			$data['title'] 			= $appName;
			$data['task']			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Transfer Proses";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Transfer Process";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/add_process_qrc');
			$cancelURL				= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			//$data['countCUST']		= $this->m_prodprocess->count_all_CUST();
			//$data['vwCUST'] 		= $this->m_prodprocess->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			$data['PRODS_STEP']		= $PRODS_STEP;
			
			$MenuCode 				= 'MN377';
			$data["MenuCode"] 		= 'MN377';
			$data['viewDocPattern'] = $this->m_prodprocess->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN377';
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
	
			$this->load->view('v_production/v_prodprocess/v_prodprocess_form_qr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$STF_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN377';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$STF_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$STF_CODE 		= $this->input->post('STF_CODE');
			$STF_DATE		= date('Y-m-d',strtotime($this->input->post('STF_DATE')));
				$Patt_Year	= date('Y',strtotime($STF_DATE));
				$Patt_Month	= date('m',strtotime($STF_DATE));
				$Patt_Date	= date('d',strtotime($STF_DATE));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			
			$CUST_CODE 		= '';
			$CUST_DESC 		= '';
			$SO_NUM 		= '';
			$SO_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlJOH 		= "SELECT CUST_CODE, CUST_DESC, SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
			$resJOH 		= $this->db->query($sqlJOH)->result();
			foreach($resJOH as $rowJOH) :
				$CUST_CODE 	= $rowJOH->CUST_CODE;
				$CUST_DESC 	= $rowJOH->CUST_DESC;
				$SO_NUM 	= $rowJOH->SO_NUM;
				$SO_CODE 	= $rowJOH->SO_CODE;
				$CCAL_NUM 	= $rowJOH->CCAL_NUM;
				$CCAL_CODE 	= $rowJOH->CCAL_CODE;
				$BOM_NUM 	= $rowJOH->BOM_NUM;
				$BOM_CODE 	= $rowJOH->BOM_CODE;
			endforeach;
			
			$STF_TYPE 		= $this->input->post('STF_TYPE');
			$STF_FROM 		= $this->input->post('STF_FROM');
			$STF_DEST 		= $this->input->post('STF_DEST');
			$STF_NOTES 		= addslashes($this->input->post('STF_NOTES'));
			$STF_STAT		= $this->input->post('STF_STAT');
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$AddSTF			= array('STF_NUM' 		=> $STF_NUM,
									'STF_CODE' 		=> $STF_CODE,
									'STF_DATE'		=> $STF_DATE,
									'PRJCODE'		=> $PRJCODE,
									'JO_NUM'		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'SO_NUM' 		=> $SO_NUM,
									'SO_CODE' 		=> $SO_CODE,
									'CCAL_NUM' 		=> $CCAL_NUM,
									'CCAL_CODE' 	=> $CCAL_CODE,
									'BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'CUST_CODE' 	=> $CUST_CODE,
									'CUST_DESC' 	=> $CUST_DESC,
									'STF_TYPE' 		=> $STF_TYPE,
									'STF_FROM' 		=> $STF_FROM,
									'STF_DEST' 		=> $STF_DEST,
									'STF_NOTES' 	=> $STF_NOTES,
									'STF_STAT' 		=> $STF_STAT,
									'STF_CREATER'	=> $DefEmp_ID,
									'STF_CREATED'	=> $STF_CREATED,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_prodprocess->add($AddSTF);
			
			// OUTPUT PROCESS
				foreach($_POST['data'] as $d)
				{
					$d['PRJCODE']	= $PRJCODE;
					$d['STF_NUM']	= $STF_NUM;
					$d['STF_CODE']	= $STF_CODE;
					$d['STF_DATE']	= $STF_DATE;
					$d['JO_NUM']	= $JO_NUM;
					$d['JO_CODE']	= $JO_CODE;
					$d['SO_NUM']	= $SO_NUM;
					$d['SO_CODE']	= $SO_CODE;
					$d['BOM_NUM']	= $BOM_NUM;
					$d['BOM_CODE']	= $BOM_CODE;
					$d['STF_FROM']	= $STF_FROM;
					$d['STF_DEST']	= $STF_DEST;
					$STF_VOLM		= $d['STF_VOLM'];
					$STF_PRICE		= $d['STF_PRICE'];
					$d['STF_TOTAL']	= $STF_VOLM * $STF_PRICE;
					$this->db->insert('tbl_stf_detail',$d);
				}
			
			// UPDATE DETAIL FROM HEADER
				//$this->m_prodprocess->updDETAIL($STF_NUM, $PRJCODE);
			
			// RM NEEDED
				foreach($_POST['dataRM'] as $dRM)
				{
					$dRM['STF_NUM']		= $STF_NUM;
					$dRM['STF_CODE']	= $STF_CODE;
					$dRM['STF_FROM']	= $STF_FROM;
					$dRM['STF_DEST']	= $STF_DEST;
					$STF_VOLM			= $dRM['STF_VOLM'];
					$STF_PRICE			= $dRM['STF_PRICE'];
					$dRM['STF_TOTAL']	= $STF_VOLM * $STF_PRICE;
					$this->db->insert('tbl_stf_detail',$dRM);
				}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STF_STAT');			// IF "ADD" CONDITION ALWAYS = SO_STAT
				$parameters 	= array('DOC_CODE' 		=> $STF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "STF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_stf_header",// TABLE NAME
										'KEY_NAME'		=> "STF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "STF_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $STF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_STF",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_STF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_STF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_STF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_STF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_STF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_STF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				//$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "STF_NUM",
										'DOC_CODE' 		=> $STF_NUM,
										'DOC_STAT' 		=> $STF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_stf_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $STF_NUM;
				$MenuCode 		= 'MN377';
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

				$parameters 	= array('TR_TYPE'		=> "STF",
										'TR_DATE' 		=> $STF_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url	= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_pR04() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN377';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$STF_NUM	= $splitCode[0];
			$JO_NUM		= $splitCode[1];
			$PRJCODE	= $splitCode[2];
					
			$data['title'] 		= $appName;
			$data['task']		= 'edit';		
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Proses Produksi";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Production Process";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/update_process');
			$cancelURL				= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 				= 'MN377';
			$data["MenuCode"] 		= 'MN377';
		
			$getSTF 						= $this->m_prodprocess->get_stf_by_number($STF_NUM)->row();
			$data['default']['STF_NUM'] 	= $getSTF->STF_NUM;
			$data['default']['STF_CODE'] 	= $getSTF->STF_CODE;
			$data['default']['STF_DATE'] 	= $getSTF->STF_DATE;
			$data['default']['PRJCODE'] 	= $getSTF->PRJCODE;
			$data['PRJCODE'] 				= $getSTF->PRJCODE;
			$PRJCODE						= $getSTF->PRJCODE;
			$data['default']['JO_NUM'] 		= $getSTF->JO_NUM;
			$data['default']['JO_CODE'] 	= $getSTF->JO_CODE;
			$data['default']['SO_NUM'] 		= $getSTF->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSTF->SO_CODE;
			$data['default']['CCAL_NUM'] 	= $getSTF->CCAL_NUM;
			$data['default']['CCAL_CODE'] 	= $getSTF->CCAL_CODE;
			$data['default']['BOM_NUM'] 	= $getSTF->BOM_NUM;
			$data['default']['BOM_CODE'] 	= $getSTF->BOM_CODE;
			$data['default']['CUST_CODE'] 	= $getSTF->CUST_CODE;
			$data['default']['CUST_DESC']	= $getSTF->CUST_DESC;
			$data['default']['STF_TYPE'] 	= $getSTF->STF_TYPE;
			$data['default']['STF_FROM'] 	= $getSTF->STF_FROM;
			$data['default']['STF_DEST'] 	= $getSTF->STF_DEST;
			$data['default']['STF_NOTES'] 	= $getSTF->STF_NOTES;
			$data['default']['STF_NOTES1'] 	= $getSTF->STF_NOTES1;
			$data['default']['STF_STAT'] 	= $getSTF->STF_STAT;
			$data['default']['Patt_Year'] 	= $getSTF->Patt_Year;
			$data['default']['Patt_Month'] 	= $getSTF->Patt_Month;
			$data['default']['Patt_Date'] 	= $getSTF->Patt_Date;
			$data['default']['Patt_Number'] = $getSTF->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSTF->STF_NUM;
				$MenuCode 		= 'MN377';
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
			
			$this->load->view('v_production/v_prodprocess/v_prodprocess_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$this->db->trans_begin();
			
			$STF_NUM 		= $this->input->post('STF_NUM');
			$STF_CODE 		= $this->input->post('STF_CODE');
			$STF_DATE		= date('Y-m-d',strtotime($this->input->post('STF_DATE')));
				$Patt_Year	= date('Y',strtotime($STF_DATE));
				$Patt_Month	= date('m',strtotime($STF_DATE));
				$Patt_Date	= date('d',strtotime($STF_DATE));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			
			$CUST_CODE 		= '';
			$CUST_DESC 		= '';
			$SO_NUM 		= '';
			$SO_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlJOH 		= "SELECT CUST_CODE, CUST_DESC, SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
			$resJOH 		= $this->db->query($sqlJOH)->result();
			foreach($resJOH as $rowJOH) :
				$CUST_CODE 	= $rowJOH->CUST_CODE;
				$CUST_DESC 	= $rowJOH->CUST_DESC;
				$SO_NUM 	= $rowJOH->SO_NUM;
				$SO_CODE 	= $rowJOH->SO_CODE;
				$CCAL_NUM 	= $rowJOH->CCAL_NUM;
				$CCAL_CODE 	= $rowJOH->CCAL_CODE;
				$BOM_NUM 	= $rowJOH->BOM_NUM;
				$BOM_CODE 	= $rowJOH->BOM_CODE;
			endforeach;
			
			$STF_TYPE 		= $this->input->post('STF_TYPE');
			$STF_FROM 		= $this->input->post('STF_FROM');
			$STF_DEST 		= $this->input->post('STF_DEST');
			$STF_NOTES 		= addslashes($this->input->post('STF_NOTES'));
			$STF_STAT		= $this->input->post('STF_STAT');
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$UpdSTF			= array('STF_NUM' 		=> $STF_NUM,
									'STF_CODE' 		=> $STF_CODE,
									'STF_DATE'		=> $STF_DATE,
									'PRJCODE'		=> $PRJCODE,
									'JO_NUM'		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'SO_NUM' 		=> $SO_NUM,
									'SO_CODE' 		=> $SO_CODE,
									'CCAL_NUM' 		=> $CCAL_NUM,
									'CCAL_CODE' 	=> $CCAL_CODE,
									'BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'CUST_CODE' 	=> $CUST_CODE,
									'CUST_DESC' 	=> $CUST_DESC,
									'STF_TYPE' 		=> $STF_TYPE,
									'STF_FROM' 		=> $STF_FROM,
									'STF_DEST' 		=> $STF_DEST,
									'STF_NOTES' 	=> $STF_NOTES,
									'STF_STAT' 		=> $STF_STAT,
									'STF_CREATER'	=> $DefEmp_ID,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_prodprocess->updateSTF($STF_NUM, $UpdSTF);
			
			// UPDATE JOBDETAIL ITEM
			if($STF_STAT == 6) // HOLD
			{
				/*foreach($_POST['data'] as $d)
				{
					$SO_NUM		= $d['SO_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					//$this->m_prodprocess->updateVolBud($SO_NUM, $PRJCODE, $ITM_CODE); // HOLD
				}*/
			}
			elseif($STF_STAT == 5) // HOLD
			{
				/*$SO_NUM		= $d['SO_NUM'];
				$ITM_CODE	= $d['ITM_CODE'];
				$this->m_prodprocess->updREJECT($SO_NUM, $PRJCODE, $ITM_CODE);*/
			}
			else
			{
				$this->m_prodprocess->deleteSTFDet($STF_NUM);
				
				// OUTPUT PROCESS
					foreach($_POST['data'] as $d)
					{
						$d['PRJCODE']	= $PRJCODE;
						$d['STF_NUM']	= $STF_NUM;
						$d['STF_CODE']	= $STF_CODE;
						$d['STF_DATE']	= $STF_DATE;
						$d['JO_NUM']	= $JO_NUM;
						$d['JO_CODE']	= $JO_CODE;
						$d['SO_NUM']	= $SO_NUM;
						$d['SO_CODE']	= $SO_CODE;
						$d['BOM_NUM']	= $BOM_NUM;
						$d['BOM_CODE']	= $BOM_CODE;
						$d['STF_FROM']	= $STF_FROM;
						$d['CUST_DESC']	= $CUST_DESC;
						$d['STF_NUM']	= $STF_NUM;
						$d['STF_CODE']	= $STF_CODE;
						$STF_VOLM		= $d['STF_VOLM'];
						$STF_PRICE		= $d['STF_PRICE'];
						$d['STF_TOTAL']	= $STF_VOLM * $STF_PRICE;
						$this->db->insert('tbl_stf_detail',$d);
					}
				
				// RM NEEDED
					foreach($_POST['dataRM'] as $dRM)
					{
						$dRM['STF_NUM']		= $STF_NUM;
						$dRM['STF_CODE']	= $STF_CODE;
						$dRM['STF_FROM']	= $STF_FROM;
						$dRM['CUST_DESC']	= $CUST_DESC;
						$STF_VOLM			= $dRM['STF_VOLM'];
						$STF_PRICE			= $dRM['STF_PRICE'];
						$dRM['STF_TOTAL']	= $STF_VOLM * $STF_PRICE;
						$this->db->insert('tbl_stf_detail',$dRM);
					}
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = STF_STAT
				$parameters 	= array('DOC_CODE' 		=> $STF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "STF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_stf_header",// TABLE NAME
										'KEY_NAME'		=> "STF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "STF_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $STF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_STF",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_STF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_STF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_STF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_STF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_STF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_STF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "STF_NUM",
										'DOC_CODE' 		=> $STF_NUM,
										'DOC_STAT' 		=> $STF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_stf_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $STF_NUM;
				$MenuCode 		= 'MN377';
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

				$parameters 	= array('TR_TYPE'		=> "STF",
										'TR_DATE' 		=> $STF_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function get_all_SO() // HOLD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_production/c_pR04uctpr0535/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_prodprocess->count_all_SO($PRJCODE);
			$data["countSO"] 	= $num_rows;
	 
			$data['vwSO']		= $this->m_prodprocess->get_all_SO($PRJCODE)->result();
			
			$this->load->view('v_production/v_prodprocess/v_prodprocess', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function iNb0x() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_pR04uctpr0535/p07_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function p07_l5t_x1() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Tahapan Produksi";
			}
			else
			{
				$data["h1_title"] 	= "Prod. Stage Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN378';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_pR04uctpr0535/glpr045t3p_1Nb/?id=";
			
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
	
	function glpr045t3p_1Nb() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN378';
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
			$data["h1_title"] 	= "Pers. Tahapan Produksi";
		}
		else
		{
			$data["h1_title"] 	= "Prod. Stage Approval";
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
				$data["url_search"] = site_url('c_production/c_pR04uctpr0535/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_prodprocess->count_all_PRDInb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_prodprocess->get_all_PRDInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['backURL'] 	= site_url('c_production/c_pR04uctpr0535/iNb0x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN378';
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
			
			$this->load->view('v_production/v_prodprocess/v_inb_prodprocess', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB()
		{
			$gEn5rcH	= $_POST['gEn5rcH'];
			$mxLS		= $_POST['maxLimDf'];
			$mxLF		= $_POST['maxLimit'];
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA	= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url		= site_url('c_production/c_pR04uctpr0535/glpr045t3p_1Nb/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------
	
	function u775o_pR041nb() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN378';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$STF_NUM	= $splitCode[0];
			$JO_NUM		= $splitCode[1];
			$PRJCODE	= $splitCode[2];
					
			$data['title'] 		= $appName;
			$data['task']		= 'edit';		
			$LangID 			= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pers. Tahapan Produksi";
			}
			else
			{
				$data["h1_title"] 	= "Prod. Stage Approval";
			}
			
			$data['form_action']	= site_url('c_production/c_pR04uctpr0535/update_process_inb');
			$cancelURL				= site_url('c_production/c_pR04uctpr0535/glpr045t3p_1Nb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['cancelURL'] 		= $cancelURL;
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 				= 'MN378';
			$data["MenuCode"] 		= 'MN378';
		
			$getSTF 						= $this->m_prodprocess->get_stf_by_number($STF_NUM)->row();
			$data['default']['STF_NUM'] 	= $getSTF->STF_NUM;
			$data['default']['STF_CODE'] 	= $getSTF->STF_CODE;
			$data['default']['STF_DATE'] 	= $getSTF->STF_DATE;
			$data['default']['PRJCODE'] 	= $getSTF->PRJCODE;
			$data['PRJCODE'] 				= $getSTF->PRJCODE;
			$PRJCODE						= $getSTF->PRJCODE;
			$data['default']['JO_NUM'] 		= $getSTF->JO_NUM;
			$data['default']['JO_CODE'] 	= $getSTF->JO_CODE;
			$data['default']['SO_NUM'] 		= $getSTF->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSTF->SO_CODE;
			$data['default']['CCAL_NUM'] 	= $getSTF->CCAL_NUM;
			$data['default']['CCAL_CODE'] 	= $getSTF->CCAL_CODE;
			$data['default']['BOM_NUM'] 	= $getSTF->BOM_NUM;
			$data['default']['BOM_CODE'] 	= $getSTF->BOM_CODE;
			$data['default']['CUST_CODE'] 	= $getSTF->CUST_CODE;
			$data['default']['CUST_DESC']	= $getSTF->CUST_DESC;
			$data['default']['STF_TYPE'] 	= $getSTF->STF_TYPE;
			$data['default']['STF_FROM'] 	= $getSTF->STF_FROM;
			$data['default']['STF_DEST'] 	= $getSTF->STF_DEST;
			$data['default']['STF_NOTES'] 	= $getSTF->STF_NOTES;
			$data['default']['STF_NOTES1'] 	= $getSTF->STF_NOTES1;
			$data['default']['STF_STAT'] 	= $getSTF->STF_STAT;
			$data['default']['Patt_Year'] 	= $getSTF->Patt_Year;
			$data['default']['Patt_Month'] 	= $getSTF->Patt_Month;
			$data['default']['Patt_Date'] 	= $getSTF->Patt_Date;
			$data['default']['Patt_Number'] = $getSTF->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSTF->STF_NUM;
				$MenuCode 		= 'MN378';
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
			
			$this->load->view('v_production/v_prodprocess/v_inb_prodprocess_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // GOOD
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$STF_APPROVED 	= date('Y-m-d H:i:s');
			
			$STF_NUM 		= $this->input->post('STF_NUM');
			$STF_CODE 		= $this->input->post('STF_CODE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$JO_CODE 		= $this->input->post('JO_CODE');
			
			$CUST_CODE 		= '';
			$CUST_DESC 		= '';
			$SO_NUM 		= '';
			$SO_CODE 		= '';
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';
			$sqlJOH 		= "SELECT CUST_CODE, CUST_DESC, SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
								FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
			$resJOH 		= $this->db->query($sqlJOH)->result();
			foreach($resJOH as $rowJOH) :
				$CUST_CODE 	= $rowJOH->CUST_CODE;
				$CUST_DESC 	= $rowJOH->CUST_DESC;
				$SO_NUM 	= $rowJOH->SO_NUM;
				$SO_CODE 	= $rowJOH->SO_CODE;
				$CCAL_NUM 	= $rowJOH->CCAL_NUM;
				$CCAL_CODE 	= $rowJOH->CCAL_CODE;
				$BOM_NUM 	= $rowJOH->BOM_NUM;
				$BOM_CODE 	= $rowJOH->BOM_CODE;
			endforeach;
			
			$STF_TYPE 		= $this->input->post('STF_TYPE');
			$STF_FROM 		= $this->input->post('STF_FROM');
			$STF_DEST 		= $this->input->post('STF_DEST');
			$STF_NOTES 		= addslashes($this->input->post('STF_NOTES'));
			$STF_NOTES1		= addslashes($this->input->post('STF_NOTES'));
			$STF_STAT		= $this->input->post('STF_STAT');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $STF_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $STF_APPROVED;
				$AH_NOTES		= addslashes($this->input->post('STF_NOTES1'));
				// Karena dengan adanya regulasi Persetujuan berdasarkan jumlah Total Pembelian, maka siapa yang bisa meng-approve,
				// maka itu pasti Last Step.
			
				$updSTF 		= array('STF_STAT'	=> 7); // Default approval stat
				$this->m_prodprocess->updateSTFH($STF_NUM, $updSTF);
			// END : SAVE APPROVE HISTORY
			
			if($STF_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				if($AH_ISLAST == 1)
				{
					$updSTF	= array('STF_APPROVER'	=> $DefEmp_ID,
									'STF_APPROVED'	=> $STF_APPROVED,
									'STF_NOTES1'	=> $STF_NOTES1,
									'STF_STAT'		=> $STF_STAT);
					$this->m_prodprocess->updateSTFH($STF_NUM, $updSTF);
					
					// UPDATE JOBDETAIL ITEM					
						$PRODS_LAST	= 0;
						$sqlPS 		= "SELECT PRODS_LAST FROM tbl_prodstep WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
						$resPS 		= $this->db->query($sqlPS)->result();
						foreach($resPS as $rowPS) :
							$PRODS_LAST = $rowPS->PRODS_LAST;
						endforeach;
						
					// OUTPUT MATERIAL
						$TOT_VOLM	= 0;
						$TOT_AMOUNT	= 0;
						foreach($_POST['data'] as $d)
						{
							$ITM_CODE	= $d['ITM_CODE'];
							$ITM_NAME	= $d['ITM_NAME'];
							$STF_VOLM	= $d['STF_VOLM'];
							$TOT_VOLM	= $TOT_VOLM + $STF_VOLM;
							$STF_PRICE	= $d['STF_PRICE'];
							$STF_AMOUNT	= $STF_VOLM * $STF_PRICE;
						
							// UPDATE JO DETAIL IF THE STEP PROD IS THE LAST
								if($PRODS_LAST == 1)
								{
									$prmJODet		= array('PRJCODE' 		=> $PRJCODE,
															'JO_NUM' 		=> $JO_NUM,
															'JO_CODE'		=> $JO_CODE,
															'ITM_CODE'		=> $ITM_CODE,
															'STF_VOLM'		=> $STF_VOLM,
															'STF_PRICE'		=> $STF_PRICE,
															'STF_AMOUNT'	=> $STF_AMOUNT);
									$this->m_prodprocess->updateJODetail($prmJODet);
								}
						
							// UPDATE SO DETAIL JIKA SUDAH LAST STEP
								if($PRODS_LAST == 1)
								{
									$prmSODet		= array('PRJCODE' 		=> $PRJCODE,
															'SO_NUM' 		=> $SO_NUM,
															'SO_CODE'		=> $SO_CODE,
															'ITM_CODE'		=> $ITM_CODE,
															'STF_VOLM'		=> $STF_VOLM,
															'STF_PRICE'		=> $STF_PRICE,
															'STF_AMOUNT'	=> $STF_AMOUNT);
									$this->m_prodprocess->updateSODetail($prmSODet);
								}
						
							// UPDATE JUMLAH PER TAHAPAN
								$prmSOConcl		= array('PRJCODE' 		=> $PRJCODE,
														'STF_NUM' 		=> $STF_NUM,
														'STF_CODE' 		=> $STF_CODE,
														'CUST_CODE' 	=> $CUST_CODE,
														'CUST_DESC' 	=> $CUST_DESC,
														'SO_NUM' 		=> $SO_NUM,
														'SO_CODE'		=> $SO_CODE,
														'JO_NUM' 		=> $JO_NUM,
														'JO_CODE'		=> $JO_CODE,
														'CCAL_NUM' 		=> $CCAL_NUM,
														'CCAL_CODE'		=> $CCAL_CODE,
														'BOM_NUM' 		=> $BOM_NUM,
														'BOM_CODE'		=> $BOM_CODE,
														'ITM_CODE' 		=> $ITM_CODE,
														'ITM_NAME'		=> $ITM_NAME,
														'STF_FROM'		=> $STF_FROM,
														'TOT_VOLM'		=> $TOT_VOLM);
								$this->m_prodprocess->updateSOConcl($prmSOConcl);
						}
						
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "STF_NUM",
												'DOC_CODE' 		=> $STF_NUM,
												'DOC_STAT' 		=> $STF_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_stf_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
			
				}
			}
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = STF_STAT
				$parameters 	= array('DOC_CODE' 		=> $STF_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "STF",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_stf_header",// TABLE NAME
										'KEY_NAME'		=> "STF_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "STF_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $STF_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_STF",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_STF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_STF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_STF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_STF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_STF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_STF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "STF_NUM",
										'DOC_CODE' 		=> $STF_NUM,
										'DOC_STAT' 		=> $STF_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_stf_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "STF",
										'TR_DATE' 		=> $STF_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			// UPDATE JOBDETAIL ITEM
			if($STF_STAT == 4 || $STF_STAT == 5 || $STF_STAT == 6)
			{
				$updSTF 	= array('STF_STAT'		=> $STF_STAT,
									'STF_NOTES1'	=> $STF_NOTES1);
				$this->m_prodprocess->updateSTFH($STF_NUM, $updSTF);
			}
							
			$url	= site_url('c_production/c_pR04uctpr0535/glpr045t3p_1Nb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process_qrc() // OK
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$STF_CREATED 	= date('Y-m-d H:i:s');
		
		$LAST_STEP		= 0;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN377';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$STF_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$QRC_NUM 		= $this->input->post('scanned-QRText');
			$QRC_NUMGRP 	= $this->input->post('scanned-QRText');
			$STF_CODE 		= $this->input->post('STF_CODE');
			$STF_DATE		= date('Y-m-d');
				$Patt_Year	= date('Y',strtotime($STF_DATE));
				$Patt_Month	= date('m',strtotime($STF_DATE));
				$Patt_Date	= date('d',strtotime($STF_DATE));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$JO_NUM 		= $this->input->post('JO_NUM');
			$STF_STAT 		= $this->input->post('STF_STAT');
			//$MCN_NUMSEL 	= $this->input->post('MCN_NUM');
			$MCN_NUMSEL 	= "";
			$STF_STAT		= 3; // Always Approve
			
			if($STF_STAT == 3)
			{
				$JO_CODE 		= '';
				$CUST_CODE 		= '';
				$CUST_DESC 		= '';
				$SO_NUM 		= '';
				$SO_CODE 		= '';
				$CCAL_NUM 		= '';
				$CCAL_CODE 		= '';
				$BOM_NUM 		= '';
				$BOM_CODE 		= '';
				$sqlJOH 		= "SELECT JO_CODE, CUST_CODE, CUST_DESC, SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, 
										BOM_NUM, BOM_CODE
									FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
				$resJOH 		= $this->db->query($sqlJOH)->result();
				foreach($resJOH as $rowJOH) :
					$JO_CODE 	= $rowJOH->JO_CODE;
					$CUST_CODE 	= $rowJOH->CUST_CODE;
					$CUST_DESC 	= $rowJOH->CUST_DESC;
					$SO_NUM 	= $rowJOH->SO_NUM;
					$SO_CODE 	= $rowJOH->SO_CODE;
					$CCAL_NUM 	= $rowJOH->CCAL_NUM;
					$CCAL_CODE 	= $rowJOH->CCAL_CODE;
					$BOM_NUM 	= $rowJOH->BOM_NUM;
					$BOM_CODE 	= $rowJOH->BOM_CODE;
				endforeach;
				
				$STF_TYPE 		= 1;
				$STF_FROM 		= $this->input->post('STF_FROM'); // Current PRODS_STEP
				$STF_DEST 		= ''; // .....
				$STF_NOTES 		= addslashes($this->input->post('STF_NOTES'));
				$Patt_Number	= $this->input->post('Patt_Number');

				// FIND THE NEXT STEP
					// CHECK ISLAST STEP
						$ISLAST 	= 0;
						$sqlLSTEP 	= "SELECT ISLAST FROM tbl_bom_stfdetail WHERE BOMSTF_STEP = '$STF_FROM' LIMIT 1";
						$resLSTEP 	= $this->db->query($sqlLSTEP)->result();
						foreach($resLSTEP as $rowLSTEP) :
							$ISLAST = $rowLSTEP->ISLAST;
						endforeach;

					// GET CURRENT STEP
						$PRODS_ORD 	= 0;
						$sqlCSTEP 	= "SELECT PRODS_ORDER FROM tbl_prodstep
										WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
						$resCSTEP 	= $this->db->query($sqlCSTEP)->result();
						foreach($resCSTEP as $rowCSTEP) :
							$PRODS_ORD = $rowCSTEP->PRODS_ORDER;
						endforeach;

					// NEXT STEP
						$JOSTF_STEP	= $STF_FROM;
						$NEXT_STEP	= '';
						if($ISLAST == 0)
						{
							$sqlNSTEP 	= "SELECT DISTINCT A.JOSTF_STEP
											FROM tbl_jo_stfdetail A
												INNER JOIN tbl_prodstep B ON B.PRODS_STEP = A.JOSTF_STEP
											WHERE A.JO_NUM = '$JO_NUM'
												AND B.PRODS_ORDER > $PRODS_ORD
											ORDER BY B.PRODS_ORDER ASC LIMIT 1";
							$resNSTEP 	= $this->db->query($sqlNSTEP)->result();
							foreach($resNSTEP as $rowNSTEP) :
								$NEXT_STEP = $rowNSTEP->JOSTF_STEP;
							endforeach;
						}

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $STF_NUM;
					$AH_APPLEV		= 1;
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $TRXTIME1;
					$AH_NOTES		= $STF_NOTES;

					$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
											'AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> 1);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				// START : SAVE TO HEADER STF
					$AddSTF			= array('STF_NUM' 		=> $STF_NUM,
											'STF_CODE' 		=> $STF_CODE,
											'STF_DATE'		=> $STF_DATE,
											'PRJCODE'		=> $PRJCODE,
											'JO_NUM'		=> $JO_NUM,
											'JO_CODE'		=> $JO_CODE,
											'SO_NUM' 		=> $SO_NUM,
											'SO_CODE' 		=> $SO_CODE,
											'CCAL_NUM' 		=> $CCAL_NUM,
											'CCAL_CODE' 	=> $CCAL_CODE,
											'BOM_NUM' 		=> $BOM_NUM,
											'BOM_CODE' 		=> $BOM_CODE,
											'CUST_CODE' 	=> $CUST_CODE,
											'CUST_DESC' 	=> $CUST_DESC,
											'STF_TYPE' 		=> $STF_TYPE,
											'STF_FROM' 		=> $STF_FROM,
											'STF_DEST' 		=> $NEXT_STEP,
											'QRC_NUM'		=> $QRC_NUM,
											'STF_NOTES' 	=> $STF_NOTES,
											'STF_STAT' 		=> $STF_STAT,
											'STF_CREATER'	=> $DefEmp_ID,
											'STF_CREATED'	=> $STF_CREATED,
											'Patt_Year'		=> $Patt_Year,
											'Patt_Month' 	=> $Patt_Month,
											'Patt_Date'		=> $Patt_Date,
											'Patt_Number'	=> $Patt_Number);
					$this->m_prodprocess->add($AddSTF); 
				// END : SAVE TO HEADER STF

				// CHECK ISLAST PRODS
					$PRODS_LAST	= 0;
					$sqlPS 		= "SELECT PRODS_LAST FROM tbl_prodstep WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
					$resPS 		= $this->db->query($sqlPS)->result();
					foreach($resPS as $rowPS) :
						$PRODS_LAST = $rowPS->PRODS_LAST;
					endforeach;
					$PRODS_LAST		= $ISLAST;

				// IF LAST, UPDATE
					if($PRODS_LAST == 1)
					{
						$sqlQRCD = "SELECT ICOLL_NUM, QRC_NUM FROM tbl_item_colld WHERE ICOLL_CODE = '$QRC_NUM'";
						$resQRCD = $this->db->query($sqlQRCD)->result();
						foreach($resQRCD as $rowQRCD) :
							$ICOLL_NUM 	= $rowQRCD->ICOLL_NUM;
							$QRC_NUM	= $rowQRCD->QRC_NUM;

							$updQRCD1	= "UPDATE tbl_qrc_detail SET PROD_STAT = 1 WHERE QRC_NUM = '$QRC_NUM'";
							$this->db->query($updQRCD1);

							$updQRCD2	= "UPDATE tbl_item_collh SET PROD_STAT = 2 WHERE ICOLL_NUM = '$ICOLL_NUM'";
							$this->db->query($updQRCD2);
						endforeach;

						$LAST_STEP	= 1;
					}

				// GET WH PROD
					$WH_NUM		= '';
					$WH_CODE	= '';
					$sqlWHP 	= "SELECT WH_NUM, WH_CODE FROM tbl_warehouse WHERE ISWHPROD = 1 LIMIT 1";
					$resWHP 	= $this->db->query($sqlWHP)->result();
					foreach($resWHP as $rowWHP) :
						$WH_NUM 	= $rowWHP->WH_NUM;
						$WH_CODE	= $rowWHP->WH_CODE;
					endforeach;
				
				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $STF_NUM;
					$JournalType	= 'STF';
					$JournalH_Date	= $STF_DATE;
					$Company_ID		= $comp_init;
					$DOCSource		= $STF_NUM;
					$LastUpdate		= date('Y-m-d H:i:s');
					$WH_CODE		= $WH_CODE;
					$Refer_Number	= '';
					$RefType		= 'STF';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Desc'		=> $STF_NOTES,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'Manual_No'			=> $STF_CODE,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
				// END : JOURNAL HEADER

				// INPUT DAN OUTPUT BAHAN MATERIAL
				/* START PERHITUNGAN MANUAL
				/* 	KARENA AKAN ADA KEMUNGKINAN PERUBAHAN PENGGUNAAN MESIN SAAT PROSES PRODUKSI,
					MAKA HARUS ADA PROSES PERHITUNGAN ULANG DENGAN MEMPERHATIKAN APAKAH ITU SEBAGAI BAHAN KIMIA ATAU DYESTUFF
					1. 	DYESTUFF. MENGGUNAKAN FORMULA % DARI TOTAL QTY PRODUKSI, TIDAK DIPENGARUHI OLEH MESIN
						X 	= (BOM_QTY * TOT_PROD_FG / 100);

					2. 	BAHAN KIMIA, DIPENGARUHI OLEH MESIN NAMUN HASIL PERKALIAN DENGAN SELECT MCN_ITMCAL FROM tbl_machine
						X 	= MCN_ITMCAL * BOM_QTY * TOT_PROD_FG;
				END */

				// GET QTY PROD
					$TOT_INRM	= 0;
					$TOT_INEXP	= 0;
					$TOT_EXP	= 0;
					$TOT_VOLM	= 0;
					$sql_MTR	= "SELECT A.ITM_CODE, A.ITM_GROUP, B.ITM_CATEG, A.ITM_NAME, A.ITM_UNIT, A.ITM_QTY, A.ITM_PRICE, A.JOSTF_TYPE,
										A.BOM_QTY, A.BOM_PRICE, A.MCN_NUM
									FROM tbl_jo_stfdetail A
										INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
									WHERE A.PRJCODE = '$PRJCODE' AND A.JO_NUM = '$JO_NUM'
										AND A.JOSTF_STEP = '$STF_FROM'";
					$res_MTR	= $this->db->query($sql_MTR)->result();
					foreach($res_MTR as $row_MTR):
						$ITM_CODE		= $row_MTR->ITM_CODE;
						$ITM_GROUP		= $row_MTR->ITM_GROUP;
						$ITM_CATEG		= $row_MTR->ITM_CATEG;
						$ITM_NAME		= $row_MTR->ITM_NAME;
						$ITM_UNIT		= $row_MTR->ITM_UNIT;
						$ITM_QTY		= $row_MTR->ITM_QTY;
						$ITM_PRICE		= $row_MTR->ITM_PRICE;
						$JOSTF_TYPE		= $row_MTR->JOSTF_TYPE;
						$BOM_QTY		= $row_MTR->BOM_QTY;
						$BOM_PRICE		= $row_MTR->BOM_PRICE;
						//$MCN_NUM		= $row_MTR->MCN_NUM;
						$MCN_NUM 		= $MCN_NUMSEL;

						// KALAU DIGUNAKAN PERGANTIAN MESIN SECARA MANUAL. ITM_QTY DAN ITM_PRICE HARUS DIRUBAH SESUAI DENGAN MESIN YANG DIPILIH
							/*$sqlMCN	= "SELECT MCN_ITMCAL FROM tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
		                	$resMCN = $this->db->query($sqlMCN)->result();
		                	foreach($resMCN as $rowMCN) :
		                        $MCN_ITMCAL	= $rowMCN->MCN_ITMCAL;
							endforeach;

							if($ITM_CATEG == 'DY')
							{
								$ITM_QTY 	= ($BOM_QTY * $T_PROD / 100);
								$ITM_PRICE	= $BOM_PRICE;
							}
							else
							{
								$ITM_QTY 	= $MCN_ITMCAL * $BOM_QTY * $T_PROD;
								$ITM_PRICE	= $BOM_PRICE;
							}

							$ACC_IDX 		= '';
							$ACC_ID_UMX		= '';
							if($ITM_NAME == '')
							{
								$sqlUPDDET	= "UPDATE tbl_jo_stfdetail A, tbl_item B
													SET A.ITM_NAME = B.ITM_NAME, A.ITM_GROUP = B.ITM_GROUP, A.ITM_UNIT = B.ITM_UNIT
												WHERE A.ITM_CODE = B.ITM_CODE
													AND A.PRJCODE = B.PRJCODE";
								$this->db->query($sqlUPDDET);
							}*/

						$d['PRJCODE']	= $PRJCODE;
						$d['STF_NUM']	= $STF_NUM;
						$d['STF_CODE']	= $STF_CODE;
						$d['STF_DATE']	= $STF_DATE;
						$d['JO_NUM']	= $JO_NUM;
						$d['JO_CODE']	= $JO_CODE;
						$d['SO_NUM']	= $SO_NUM;
						$d['SO_CODE']	= $SO_CODE;
						$d['BOM_NUM']	= $BOM_NUM;
						$d['BOM_CODE']	= $BOM_CODE;
						$d['STF_FROM']	= $STF_FROM;
						$d['STF_DEST']	= $NEXT_STEP;
						$d['ITM_TYPE']	= $JOSTF_TYPE;
						$d['ITM_CODE']	= $ITM_CODE;
						$d['ITM_GROUP']	= $ITM_GROUP;
						$d['ITM_CATEG']	= $ITM_CATEG;
						$d['ITM_NAME']	= $ITM_NAME;
						$d['ITM_UNIT']	= $ITM_UNIT;
						$d['STF_VOLM']	= $ITM_QTY;
						$d['STF_PRICE']	= $ITM_PRICE;
						$TOT_VOLM		= $TOT_VOLM + $ITM_QTY;
						$d['STF_TOTAL']	= $ITM_QTY * $ITM_PRICE;
						if($JOSTF_TYPE == 'IN')
							$TOT_EXP1	= $ITM_QTY * $ITM_PRICE;
						else
							$TOT_EXP1	= $ITM_PRICE;

						if($JOSTF_TYPE == 'OUT')
							$d['STF_TOTAL']	= $ITM_PRICE;			// KARENA PADA JO DETAIL, ITM_PRICE DI OUTPUT SUDAH TOTAL NILAI

						$TOT_EXP 		= $TOT_EXP1;

						$ACC_ID 		= '';
						$ACC_ID_UM		= '';
						$NEEDQRCX		= 0;
						$ITM_TYPE		= '';
						$ISWIP 			= 0;
						$ITM_NAME		= "";
						$sql_ITM		= "SELECT ITM_GROUP, ITM_NAME, ITM_UNIT, ACC_ID, ACC_ID_UM, NEEDQRC, ITM_TYPE, ISWIP
											FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$res_ITM		= $this->db->query($sql_ITM)->result();
						foreach($res_ITM as $row_ITM):
							$ITM_GROUP		= $row_ITM->ITM_GROUP;
							$ITM_NAME		= $row_ITM->ITM_NAME;
							$ITM_UNIT		= $row_ITM->ITM_UNIT;
							$ACC_ID			= $row_ITM->ACC_ID;
							$ACC_ID_UM		= $row_ITM->ACC_ID_UM;
							$NEEDQRCX		= $row_ITM->NEEDQRC;
							$ITM_TYPE		= $row_ITM->ITM_TYPE;
							$ISWIP			= $row_ITM->ISWIP;
						endforeach;

						$d['ACC_ID']	= $ACC_ID;
						$d['ACC_ID_UM']	= $ACC_ID_UM;
						$d['STF_STAT']	= $STF_STAT;
						$d['QRC_NUM']	= $QRC_NUM;

						$this->db->insert('tbl_stf_detail',$d);

						$JOBCODEID		= '';
						$sql_JOBD		= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$res_JOBD		= $this->db->query($sql_JOBD)->result();
						foreach($res_JOBD as $row_JOBD):
							$JOBCODEID		= $row_JOBD->JOBCODEID;
						endforeach;
						
						if($JOSTF_TYPE == 'IN')	// PENJURNALAN BEBAN
						{
							if($ITM_CATEG == 'WIP')
							{
								// CARI APAKAH ADA OUTPUT DGN KODE YANG DIMAKSUD
									$sqlITMC 	= "tbl_stf_detail WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM'
													AND ITM_CODE = '$ITM_CODE' AND ITM_TYPE = 'OUT' AND ITM_CATEG = 'WIP'";
									$resITMC 	= $this->db->count_all($sqlITMC);
									if($resITMC > 0)
									{
										$ITMTOTAL 	= $ITM_QTY * $ITM_PRICE;

										// GET LAST PRICE OUTPUT
											$VOLITM 	= $ITM_QTY;
											$PRCITM 	= $ITM_PRICE;
											$TOTITM 	= $ITMTOTAL;
											$sqlPRC		= "SELECT STF_VOLM,	STF_PRICE, STF_TOTAL
															FROM tbl_stf_detail WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM'
															AND ITM_CODE = '$ITM_CODE' AND ITM_TYPE = 'OUT' AND ITM_CATEG = 'WIP'";
											$resPRC		= $this->db->query($sqlPRC)->result();
											foreach($resPRC as $rowPRC):
												$VOLITM = $rowPRC->STF_VOLM;
												$PRCITM = $rowPRC->STF_PRICE;
												$TOTITM = $rowPRC->STF_TOTAL;
											endforeach;
											$ITMTOTAL 	= $TOTITM;

										// START : UPDATE PROFIT AND LOSS
											$this->load->model('m_updash/m_updash', '', TRUE);

											$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
											$PERIODED	= $STF_DATE;
											$FIELDNME	= "";
											$FIELDVOL	= 1;			// dalam WIP, STF_PRICE = SUDAH TOTAL VOL * PRICE
											$FIELDPRC	= $PRCITM;
											$ADDTYPE	= "MIN";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

											$parameters = array('PERIODED' 		=> $PERIODED,
																'FIELDNME'		=> $FIELDNME,
																'FIELDVOL' 		=> $FIELDVOL,
																'FIELDPRC' 		=> $FIELDPRC,
																'ADDTYPE' 		=> $ADDTYPE,
																'ITM_CODE'		=> $ITM_CODE,
																'ITM_TYPE'		=> $ITM_TYPE);
											$this->m_updash->updateLR_NForm($PRJCODE, $parameters);
										// END : UPDATE PROFIT AND LOSS

										// CREATE JOURNAL PENGURANGAN PERSEDIAAN WIP
											// START : JOURNAL DETAIL
												$ITM_CODE 		= $ITM_CODE;
												$JOBCODEID 		= $JOBCODEID;
												$ACC_ID 		= $ACC_ID;		// YANG DIGUNAKAN ADALAH AKUN IN UNTUK INSERT COA
												$ITM_UNIT 		= $ITM_UNIT;
												$ITM_GROUP 		= $ITM_GROUP;
												$ITM_TYPE 		= $ITM_TYPE;
												$ITM_QTY 		= $VOLITM;
												$ITM_PRICE 		= $ITMTOTAL;
												$Notes 			= "($STF_FROM) $ITM_NAME";
												$ITM_DISC 		= 0;
												$TAXCODE1 		= '';
												$TAXPRICE1 		= 0;
												$JournalH_Code	= $STF_NUM;
												$JournalType	= 'STF_INWIP';
												$JournalH_Date	= $STF_DATE;
												$Company_ID		= $comp_init;
												$Currency_ID	= 'IDR';
												$LastUpdate		= $LastUpdate;
												$WH_CODE		= $WH_CODE;
												$Refer_Number	= '$JO_NUM';
												$RefType		= 'STF_INWIP';
												$JSource		= 'STF_INWIP';
												$PRJCODE		= $PRJCODE;

												// CHEK ITM TYPE
													$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
													
												$parameters = array('JournalH_Code' 	=> $JournalH_Code,
																	'JournalType'		=> $JournalType,
																	'JournalH_Date' 	=> $JournalH_Date,
																	'Company_ID' 		=> $Company_ID,
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
																	'TRANS_CATEG' 		=> 'STF_INWIP',			// Membuang hasil proses sebelumnya
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
																	'JO_CODE'			=> $JO_CODE,
																	'Notes'				=> $Notes,
																	'ITM_NAME'			=> $ITM_NAME);
												$this->m_journal->createJournalD($JournalH_Code, $parameters);
											// END : JOURNAL DETAIL

										// START : UPDATE STOCK WIP
											$parameters1 	= array('PRJCODE' 	=> $PRJCODE,
																	'WH_CODE'	=> $WH_CODE,
																	'JOBCODEID'	=> "",
																	'UM_NUM' 	=> $STF_NUM,
																	'UM_CODE' 	=> $STF_CODE,
																	'ITM_CODE' 	=> $ITM_CODE,
																	'ITM_GROUP'	=> $ITM_GROUP,
																	'ITM_QTY' 	=> $ITM_QTY,
																	'ITM_PRICE' => $ITM_PRICE);
											$this->m_itemusage->updateITM_MinWIP($parameters1);
										// START : UPDATE STOCK WIP
									}
									else
									{
										// START : UPDATE STOCK WIP
											$parameters1 	= array('PRJCODE' 	=> $PRJCODE,
																	'WH_CODE'	=> $WH_CODE,
																	'JOBCODEID'	=> "",
																	'UM_NUM' 	=> $STF_NUM,
																	'UM_CODE' 	=> $STF_CODE,
																	'ITM_CODE' 	=> $ITM_CODE,
																	'ITM_GROUP'	=> $ITM_GROUP,
																	'ITM_QTY' 	=> $ITM_QTY,
																	'ITM_PRICE' => $ITM_PRICE);
											$this->m_itemusage->updateITM_MinWIP($parameters1);
										// START : UPDATE STOCK WIP
								
										// START : RECORD TO ITEM HISTORY
											$ITM_CODE 		= $ITM_CODE;
											$JOBCODEID 		= "";
											$ACC_ID 		= $ACC_ID;		// YANG DIGUNAKAN ADALAH AKUN IN UNTUK INSERT COA
											$ITM_UNIT 		= $ITM_UNIT;
											$ITM_GROUP 		= $ITM_GROUP;
											$ITM_TYPE 		= $ITM_TYPE;
											$ITM_QTY 		= $ITM_QTY;
											$ITM_PRICE 		= $TOT_EXP1;
											$Notes 			= "($STF_FROM) $ITM_NAME";
											$ITM_DISC 		= 0;
											$TAXCODE1 		= '';
											$TAXPRICE1 		= 0;
											$JournalH_Code	= $STF_NUM;
											$JournalType	= 'STF_IN';
											$JournalH_Date	= $STF_DATE;
											$Company_ID		= $comp_init;
											$Currency_ID	= 'IDR';
											$LastUpdate		= $LastUpdate;
											$WH_CODE		= $WH_CODE;
											$Refer_Number	= '$JO_NUM';
											$RefType		= 'STF_IN';
											$JSource		= 'STF_IN';
											$PRJCODE		= $PRJCODE;

											// CHEK ITM TYPE
												$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
												
											$parameters = array('JournalH_Code' 	=> $JournalH_Code,
																'JournalType'		=> $JournalType,
																'JournalH_Date' 	=> $JournalH_Date,
																'Company_ID' 		=> $Company_ID,
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
																'TRANS_CATEG' 		=> 'STF_IN',			// STF = Section Transfer
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
																'JO_CODE'			=> $JO_CODE,
																'Notes'				=> $Notes,
																'ITM_NAME'			=> $ITM_NAME);
											$this->m_journal->createITMHistMinSTF($JournalH_Code, $parameters);
										// START : RECORD TO ITEM HISTORY
									}
							}
							else
							{
								// START : JOURNAL DETAIL
									$this->load->model('m_journal/m_journal', '', TRUE);
									
									$ITM_CODE 		= $ITM_CODE;
									$JOBCODEID 		= $JOBCODEID;
									$ACC_ID 		= $ACC_ID_UM;
									$ITM_UNIT 		= $ITM_UNIT;
									$ITM_GROUP 		= $ITM_GROUP;
									$ITM_TYPE 		= $ITM_TYPE;
									$ITM_QTY 		= $ITM_QTY;
									$ITM_PRICE 		= $ITM_PRICE;
									$Notes 			= "($STF_FROM) $ITM_NAME";
									$ITM_DISC 		= 0;
									$TAXCODE1 		= '';
									$TAXPRICE1 		= 0;
									
									$JournalH_Code	= $STF_NUM;
									$JournalType	= 'STF';
									$JournalH_Date	= $STF_DATE;
									$Company_ID		= $comp_init;
									$Currency_ID	= 'IDR';
									$LastUpdate		= $LastUpdate;
									$WH_CODE		= $WH_CODE;
									$Refer_Number	= '$JO_NUM';
									$RefType		= 'STF';
									$JSource		= 'STF';
									$PRJCODE		= $PRJCODE;

									// CHEK ITM TYPE
										$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
										
									$parameters = array('JournalH_Code' 	=> $JournalH_Code,
														'JournalType'		=> $JournalType,
														'JournalH_Date' 	=> $JournalH_Date,
														'Company_ID' 		=> $Company_ID,
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
														'TRANS_CATEG' 		=> 'STF',			// STF = Section Transfer
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
														'ITM_NAME' 			=> $ITM_NAME,
														'JO_CODE'			=> $JO_CODE,
														'JOBCODEID'			=> $JOBCODEID);
									$this->m_journal->createJournalD($JournalH_Code, $parameters);
								// END : JOURNAL DETAIL

								// START : UPDATE PROFIT AND LOSS
									$this->load->model('m_updash/m_updash', '', TRUE);

									$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
									$PERIODED	= $STF_DATE;
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
									$this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
								// END : UPDATE PROFIT AND LOSS

								// START : UPDATE STOCK
									$parameters1 = array('PRJCODE' 	=> $PRJCODE,
														'WH_CODE'	=> $WH_CODE,
														'JOBCODEID'	=> $JOBCODEID,
														'UM_NUM' 	=> $STF_NUM,
														'UM_CODE' 	=> $STF_CODE,
														'ITM_CODE' 	=> $ITM_CODE,
														'ITM_GROUP'	=> $ITM_GROUP,
														'ITM_QTY' 	=> $ITM_QTY,
														'ITM_PRICE' => $ITM_PRICE);
									$this->m_itemusage->updateITM_Min($parameters1);
								// START : UPDATE STOCK
								
								// START : RECORD TO ITEM HISTORY
									$this->m_journal->createITMHistMinSTF($JournalH_Code, $parameters);
								// START : RECORD TO ITEM HISTORY
							}
						}
						
						if($PRODS_LAST == 1 AND $JOSTF_TYPE == 'OUT')
						{
							// BUATKAN JURNAL UNTUK PENAMBAHAN WIP
							// SAMPLE JOURNAL
							// yang dilakukan pada tahapan ini hanya yang diberi tanda // Y
							/*
								BEB. PROD 				1000
									INVENT. RM 					1000
								INVENT.WIP1				TOT_EXP				// Y
									HPP 						TOT_EXP		// Y
								-----------------------------------------------------
								-----------------------------------------------------
								BEB. PROD 				1200
									INVENT. RM 					1200
									INVENT.WIP1					1000
								INVENT.WIP2				TOT_EXP				// Y
									HPP 						TOT_EXP		// Y
								-----------------------------------------------------
								-----------------------------------------------------
								BEB. PROD 				1100
									INVENT. RM 					1100
									INVENT.WIP2					2200
								INVENT.WIP2				3300				// Y
									HPP 						1100		// Y
							*/

							// GET TOTAL PRODUKSI
								$PROD_QTY	= 0;
								$sqlJD 		= "SELECT ITM_QTY FROM tbl_jo_detail WHERE JO_NUM = '$JO_NUM' AND ITM_CODE = '$ITM_CODE'";
								$resJD 		= $this->db->query($sqlJD)->result();
								foreach($resJD as $rowJD) :
									$PROD_QTY = $rowJD->ITM_QTY;
								endforeach;

							// UPDATE JO DETAIL VOLM PRODUCTION IF THE STEP PROD IS THE LAST
								$STF_PRICE	= $TOT_EXP / $PROD_QTY;
								//echo "$STF_PRICE	= $TOT_EXP / $PROD_QTY<br>";
								$prmJODet	= array('PRJCODE' 		=> $PRJCODE,
													'JO_NUM' 		=> $JO_NUM,
													'JO_CODE'		=> $JO_CODE,
													'ITM_CODE'		=> $ITM_CODE,
													'STF_VOLM'		=> $PROD_QTY,
													'STF_PRICE'		=> $STF_PRICE,
													'STF_AMOUNT'	=> $TOT_EXP);
													//'STF_AMOUNT'	=> $ITM_QTY * $ITM_PRICE);
								$this->m_prodprocess->updateJODetail($prmJODet);

							// UPDATE SO DETAIL JIKA SUDAH LAST STEP
								$prmSODet		= array('PRJCODE' 		=> $PRJCODE,
														'SO_NUM' 		=> $SO_NUM,
														'SO_CODE'		=> $SO_CODE,
														'ITM_CODE'		=> $ITM_CODE,
														'STF_VOLM'		=> $PROD_QTY,
														'STF_PRICE'		=> $STF_PRICE,
														'STF_AMOUNT'	=> $TOT_EXP);
														//'STF_AMOUNT'	=> $ITM_QTY * $ITM_PRICE);
								$this->m_prodprocess->updateSODetail($prmSODet);

							// UPDATE TO ITEM STOCK
								$prmItem		= array('PRJCODE' 		=> $PRJCODE,
														'SO_NUM' 		=> $SO_NUM,
														'SO_CODE'		=> $SO_CODE,
														'ITM_CODE'		=> $ITM_CODE,
														'STF_VOLM'		=> $PROD_QTY,
														'STF_PRICE'		=> $STF_PRICE,
														'STF_AMOUNT'	=> $TOT_EXP);
														//'STF_AMOUNT'	=> $ITM_QTY * $ITM_PRICE);
								$this->m_prodprocess->updateItem($prmItem);

							$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);

							// START : JOURNAL DETAIL
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $ITM_CODE;
								$JOBCODEID 		= $JOBCODEID;
								$ACC_ID 		= $ACC_ID;
								$ITM_UNIT 		= $ITM_UNIT;
								$ITM_GROUP 		= $ITM_GROUP;
								$ITM_TYPE 		= $ITM_TYPE;
								$ITM_QTY 		= $PROD_QTY;
								$ITM_PRICE 		= $STF_PRICE;
								$Notes 			= "($STF_FROM) $ITM_NAME";
								$ITM_DISC 		= 0;
								$TAXCODE1 		= '';
								$TAXPRICE1 		= 0;
								
								$JournalH_Code	= $STF_NUM;
								$JournalType	= 'STF';
								$JournalH_Date	= $STF_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= $LastUpdate;
								$WH_CODE		= $WH_CODE;
								$Refer_Number	= '$JO_NUM';
								$RefType		= 'STF';
								$JSource		= 'STF_OUTPUT';
								$PRJCODE		= $PRJCODE;
									
								$parameters = array('JournalH_Code' 	=> $JournalH_Code,
													'JournalType'		=> $JournalType,
													'JournalH_Date' 	=> $JournalH_Date,
													'Company_ID' 		=> $Company_ID,
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
													'TRANS_CATEG' 		=> 'STF_OUTPUT',			// STF = Section Transfer - OUTPUT FG
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
													'JO_CODE'			=> $JO_CODE);
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							// END : JOURNAL DETAIL

							// START : UPDATE ITEM
								// DI DI $this->m_prodprocess->updateItem($prmItem);
								/*$sqlUpd2	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITM_QTY,
													PROD_VOLM = PROD_VOLM + $ITM_QTY, PROD_AMOUNT = PROD_AMOUNT + $TOT_EXP1,
													ITM_IN = ITM_IN + $ITM_QTY, ITM_INP = ITM_INP + $TOT_EXP1
												WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
								$this->db->query($sqlUpd2);*/
							// END : UPDATE ITEM

							// START : UPDATE PROFIT AND LOSS
								$this->load->model('m_updash/m_updash', '', TRUE);

								$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
								$PERIODED	= $STF_DATE;
								$FIELDNME	= "";
								$FIELDVOL	= 1;			// dalam FG, STF_PRICE = SUDAH TOTAL VOL * PRICE
								$FIELDPRC	= $ITM_PRICE;
								$ADDTYPE	= "PLUS";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

								$parameters = array('PERIODED' 		=> $PERIODED,
													'FIELDNME'		=> $FIELDNME,
													'FIELDVOL' 		=> $FIELDVOL,
													'FIELDPRC' 		=> $FIELDPRC,
													'ADDTYPE' 		=> $ADDTYPE,
													'ITM_CODE'		=> $ITM_CODE,
													'ITM_TYPE'		=> $ITM_TYPE);
								$this->m_updash->updateLR_NForm($PRJCODE, $parameters);
							// END : UPDATE PROFIT AND LOSS

							$LAST_STEP	= 1;

							$sqlUPLST	= "UPDATE tbl_stf_detail SET STF_ISLAST = 1 WHERE STF_NUM = '$STF_NUM'";
							$this->db->query($sqlUPLST);
						}
						elseif($JOSTF_TYPE == 'OUT') // JOURNAL WIP STOCK TO COA
						{
							if($ISWIP == 1)
							{
								// START : JOURNAL DETAIL
									$ITM_CODE 		= $ITM_CODE;
									$JOBCODEID 		= $JOBCODEID;
									$ACC_ID 		= $ACC_ID;		// YANG DIGUNAKAN ADALAH AKUN IN UNTUK INSERT COA
									$ITM_UNIT 		= $ITM_UNIT;
									$ITM_GROUP 		= $ITM_GROUP;
									$ITM_TYPE 		= $ITM_TYPE;
									$ITM_QTY 		= $ITM_QTY;
									$ITM_PRICE 		= $ITM_PRICE;
									$Notes 			= "($STF_FROM) $ITM_NAME";
									$ITM_DISC 		= 0;
									$TAXCODE1 		= '';
									$TAXPRICE1 		= 0;
									$JournalH_Code	= $STF_NUM;
									$JournalType	= 'STF_OUTWIP';
									$JournalH_Date	= $STF_DATE;
									$Company_ID		= $comp_init;
									$Currency_ID	= 'IDR';
									$LastUpdate		= $LastUpdate;
									$WH_CODE		= $WH_CODE;
									$Refer_Number	= '$JO_NUM';
									$RefType		= 'STF_OUTWIP';
									$JSource		= 'STF_OUTWIP';
									$PRJCODE		= $PRJCODE;

									// CHEK ITM TYPE
										$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
										
									$parameters = array('JournalH_Code' 	=> $JournalH_Code,
														'JournalType'		=> $JournalType,
														'JournalH_Date' 	=> $JournalH_Date,
														'Company_ID' 		=> $Company_ID,
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
														'TRANS_CATEG' 		=> 'STF_OUTWIP',			// STF Khusus WIP
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
														'JO_CODE'			=> $JO_CODE,
														'Notes'				=> $Notes);
									$this->m_journal->createJournalD($JournalH_Code, $parameters);
								// END : JOURNAL DETAIL

								// START : UPDATE PROFIT AND LOSS
									$this->load->model('m_updash/m_updash', '', TRUE);

									$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
									$PERIODED	= $STF_DATE;
									$FIELDNME	= "";
									$FIELDVOL	= 1;			// dalam WIP, STF_PRICE = SUDAH TOTAL VOL * PRICE
									$FIELDPRC	= $ITM_PRICE;
									$ADDTYPE	= "PLUS";		// PENGURANGAN KARENA SEBAGAI BAHAN MASUKAN

									$parameters = array('PERIODED' 		=> $PERIODED,
														'FIELDNME'		=> $FIELDNME,
														'FIELDVOL' 		=> $FIELDVOL,
														'FIELDPRC' 		=> $FIELDPRC,
														'ADDTYPE' 		=> $ADDTYPE,
														'ITM_CODE'		=> $ITM_CODE,
														'ITM_TYPE'		=> $ITM_TYPE);
									$this->m_updash->updateLR_NForm($PRJCODE, $parameters);
								// END : UPDATE PROFIT AND LOSS

								// UPDATE TO ITEM STOCK
									$prmItem		= array('PRJCODE' 		=> $PRJCODE,
															'SO_NUM' 		=> $SO_NUM,
															'SO_CODE'		=> $SO_CODE,
															'ITM_CODE'		=> $ITM_CODE,
															'STF_VOLM'		=> $ITM_QTY,
															'STF_PRICE'		=> $ITM_PRICE,
															'STF_AMOUNT'	=> $ITM_PRICE);
															//'STF_AMOUNT'	=> $ITM_QTY * $ITM_PRICE);
									$this->m_prodprocess->updateItem($prmItem);
							}
						}
						
						// START : UPDATE JUMLAH PER TAHAPAN
							$prmSOConcl		= array('PRJCODE' 		=> $PRJCODE,
													'STF_NUM' 		=> $STF_NUM,
													'STF_CODE' 		=> $STF_CODE,
													'CUST_CODE' 	=> $CUST_CODE,
													'CUST_DESC' 	=> $CUST_DESC,
													'SO_NUM' 		=> $SO_NUM,
													'SO_CODE'		=> $SO_CODE,
													'JO_NUM' 		=> $JO_NUM,
													'JO_CODE'		=> $JO_CODE,
													'CCAL_NUM' 		=> $CCAL_NUM,
													'CCAL_CODE'		=> $CCAL_CODE,
													'BOM_NUM' 		=> $BOM_NUM,
													'BOM_CODE'		=> $BOM_CODE,
													'ITM_CODE' 		=> $ITM_CODE,
													'ITM_NAME'		=> $ITM_NAME,
													'STF_FROM'		=> $STF_FROM,
													'TOT_VOLM'		=> $ITM_QTY);
							$this->m_prodprocess->updateSOConcl($prmSOConcl);
						// END : UPDATE JUMLAH PER TAHAPAN

						// UPDATE QTY PRODUCT
							$sqlUPDPROD	= "UPDATE tbl_jo_stfdetail SET ITM_QTY_PROC = ITM_QTY_PROC + $ITM_QTY, STF_STAT = $STF_STAT
											WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' AND JOSTF_TYPE = 'OUT' 
												AND ITM_CODE = '$ITM_CODE' AND JOSTF_STEP = '$STF_FROM'";
							$this->db->query($sqlUPDPROD);

						// UPDATE ISPROC
							$sqlISPROC	= "UPDATE tbl_jo_stfdetail SET ISPROC = 1, ISPROCD = '$STF_CREATED', STF_STAT = $STF_STAT
											WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' AND JOSTF_STEP = '$STF_FROM'";
							$this->db->query($sqlISPROC);
					endforeach;

				// GET PRICE - SO
					$SOPRC		= 0;
					$sqlSOPRC	= "SELECT A.SO_PRICE AS SOPRC FROM tbl_so_detail A INNER JOIN tbl_jo_detail B ON A.ITM_CODE = B.ITM_CODE
									WHERE B.JO_NUM = '$JO_NUM';";
					$resSOPRC	= $this->db->query($sqlSOPRC)->result();
					foreach($resSOPRC as $rowSOPRC):
						$SOPRC	= $rowSOPRC->SOPRC;
					endforeach;

				// GET PRICE - PRODUCTION
					$PRODPRC	= 0;
					$sqlPRDPRC	= "SELECT STF_PRICE AS PRODPRC FROM tbl_stf_detail 
									WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' AND STF_FROM = '$STF_FROM' AND ITM_TYPE = 'OUT'";
					$resPRDPRC	= $this->db->query($sqlPRDPRC)->result();
					foreach($resPRDPRC as $rowPRDPRC):
						$PRODPRC	= $rowPRDPRC->PRODPRC;
					endforeach;

				// CARI ITEM YANG MERUPAKAN GROUPING DARI QRC DI JO TERSEBUT
					/*$ITM_GROUP		= '';
					$sql_ITMG		= "SELECT A.ITM_CODE FROM tbl_jo_stfdetail A
										INNER JOIN tbl_item_collh B ON A.ITM_CODE = B.ICOLL_CODE
										WHERE A.JO_NUM = '$JO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$res_ITMG		= $this->db->query($sql_ITMG)->result();
					foreach($res_ITMG as $row_ITMG):
						$ITM_GROUP	= $row_ITMG->ITM_CODE;*/
						if($PRODS_LAST == 1)
						{
							$ITM_GROUP	= $QRC_NUMGRP;
						}
						else
						{
							$ITM_GROUP	= $QRC_NUM;
						}

						$PRODSTEP	= "";
						$sqlPRD1	= "SELECT PROD_STEP FROM tbl_qrc_detail WHERE GRP_CODE = '$ITM_GROUP'";
						$resPRD1	= $this->db->query($sqlPRD1)->result();
						foreach($resPRD1 as $rowPRD1):
							$PRODSTEP	= $rowPRD1->PROD_STEP;
						endforeach;
						if($PRODSTEP == "")
							$PRODSTEP1 	= "$STF_FROM";
						else
							$PRODSTEP1 	= "$PRODSTEP~$STF_FROM";

						$sqlUPQRC	= "UPDATE tbl_qrc_detail SET QRC_STAT = 3, QRC_STATU = 2, PROD_STEP = '$PRODSTEP1',
											QRC_SOPRC = $SOPRC, QRC_PRODPRC = $PRODPRC
										WHERE GRP_CODE = '$ITM_GROUP'";
						$this->db->query($sqlUPQRC);

						$sqlUPQRC	= "UPDATE tbl_jo_stfdetail_qrc SET STEP_PROC = 1, STEP_STAT = 1 WHERE ITM_CODE = '$ITM_GROUP'";
						$this->db->query($sqlUPQRC);

						$sqlUPQRC	= "UPDATE tbl_item_collh SET LAST_PROC = '$STF_FROM', LAST_PROCD = '$STF_CREATED'
										WHERE ICOLL_CODE = '$ITM_GROUP'";
						$this->db->query($sqlUPQRC);
					/*endforeach;*/

				// UPDATE QRC STAT
					/*$sql_QRC	= "SELECT QRC_NUM, QRC_PATT
									FROM tbl_jo_stfdetail_qrc WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM'
										AND JOSTF_STEP = '$STF_FROM'";
					$res_QRC	= $this->db->query($sql_QRC)->result();
					foreach($res_QRC as $row_QRC):
						$QRC_NUM	= $row_QRC->QRC_NUM;
						$QRC_PATT	= $row_QRC->QRC_PATT;

						$sqlUPQRC	= "UPDATE tbl_qrc_detail SET QRC_STAT = 3 WHERE QRC_NUM = '$QRC_NUM'";
						$this->db->query($sqlUPQRC);
					endforeach;*/
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STF_STAT');			// IF "ADD" CONDITION ALWAYS = SO_STAT
					$parameters 	= array('DOC_CODE' 		=> $STF_NUM,		// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "STF",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_stf_header",// TABLE NAME
											'KEY_NAME'		=> "STF_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "STF_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $STF_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STF_STAT,		// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_STF",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_STF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_STF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_STF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_STF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_STF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_STF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					//$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "STF_NUM",
											'DOC_CODE' 		=> $STF_NUM,
											'DOC_STAT' 		=> $STF_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_stf_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $STF_NUM;
					$MenuCode 		= 'MN378';
					$TTR_CATEG		= 'C-QRC';
					
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
											'TTR_DATE' 		=> date('Y-m-d H:i:s'),
											'TTR_MNCODE'	=> $MenuCode,
											'TTR_CATEG'		=> $TTR_CATEG,
											'TTR_PRJCODE'	=> $TTR_PRJCODE,
											'TTR_REFDOC'	=> $TTR_REFDOC);
					$this->m_updash->updateTrack($paramTrack);
				// END : UPDATE TO T-TRACK
				
				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $STF_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "STF",
											'DOC_QTY' 		=> "DOC_PRODQ",
											'DOC_VAL' 		=> "DOC_PRODV",
											'DOC_STAT' 		=> 'ADD');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT
			}
			else if($STF_STAT == 5)
			{
				$JO_CODE 		= '';
				$CUST_CODE 		= '';
				$CUST_DESC 		= '';
				$SO_NUM 		= '';
				$SO_CODE 		= '';
				$CCAL_NUM 		= '';
				$CCAL_CODE 		= '';
				$BOM_NUM 		= '';
				$BOM_CODE 		= '';
				$sqlJOH 		= "SELECT JO_CODE, CUST_CODE, CUST_DESC, SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, 
										BOM_NUM, BOM_CODE
									FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
				$resJOH 		= $this->db->query($sqlJOH)->result();
				foreach($resJOH as $rowJOH) :
					$JO_CODE 	= $rowJOH->JO_CODE;
					$CUST_CODE 	= $rowJOH->CUST_CODE;
					$CUST_DESC 	= $rowJOH->CUST_DESC;
					$SO_NUM 	= $rowJOH->SO_NUM;
					$SO_CODE 	= $rowJOH->SO_CODE;
					$CCAL_NUM 	= $rowJOH->CCAL_NUM;
					$CCAL_CODE 	= $rowJOH->CCAL_CODE;
					$BOM_NUM 	= $rowJOH->BOM_NUM;
					$BOM_CODE 	= $rowJOH->BOM_CODE;
				endforeach;
				
				$STF_TYPE 		= 1;
				$STF_FROM 		= $this->input->post('STF_FROM'); // Current PRODS_STEP
				$STF_DEST 		= ''; // .....
				$STF_NOTESX 	= addslashes($this->input->post('STF_NOTES'));
				$STF_NOTES 		= "REJECTED : $STF_NOTESX";
				$STF_STAT		= 5;
				$Patt_Number	= $this->input->post('Patt_Number');

				// FIND THE NEXT STEP
					// CHECK ISLAST STEP
						$ISLAST 	= 0;
						$sqlLSTEP 	= "SELECT ISLAST FROM tbl_bom_stfdetail WHERE BOMSTF_STEP = '$STF_FROM' LIMIT 1";
						$resLSTEP 	= $this->db->query($sqlLSTEP)->result();
						foreach($resLSTEP as $rowLSTEP) :
							$ISLAST = $rowLSTEP->ISLAST;
						endforeach;

					// GET CURRENT STEP
						$PRODS_ORD 	= 0;
						$sqlCSTEP 	= "SELECT PRODS_ORDER FROM tbl_prodstep
										WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
						$resCSTEP 	= $this->db->query($sqlCSTEP)->result();
						foreach($resCSTEP as $rowCSTEP) :
							$PRODS_ORD = $rowCSTEP->PRODS_ORDER;
						endforeach;

					// NEXT STEP
						$JOSTF_STEP	= $STF_FROM;
						$NEXT_STEP	= '';
						if($ISLAST == 0)
						{
							$sqlNSTEP 	= "SELECT DISTINCT A.JOSTF_STEP
											FROM tbl_jo_stfdetail A
												INNER JOIN tbl_prodstep B ON B.PRODS_STEP = A.JOSTF_STEP
											WHERE A.JO_NUM = '$JO_NUM'
												AND B.PRODS_ORDER > $PRODS_ORD
											ORDER BY B.PRODS_ORDER ASC LIMIT 1";
							$resNSTEP 	= $this->db->query($sqlNSTEP)->result();
							foreach($resNSTEP as $rowNSTEP) :
								$NEXT_STEP = $rowNSTEP->JOSTF_STEP;
							endforeach;
						}

				// DISABLED ----
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $STF_NUM;
					$AH_APPLEV		= 1;
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $TRXTIME1;
					$AH_NOTES		= $STF_NOTES;

					$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
											'AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> 1);										
					//$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY
				
				// START : SAVE TO HEADER STF
					$AddSTF			= array('STF_NUM' 		=> $STF_NUM,
											'STF_CODE' 		=> $STF_CODE,
											'STF_DATE'		=> $STF_DATE,
											'PRJCODE'		=> $PRJCODE,
											'JO_NUM'		=> $JO_NUM,
											'JO_CODE'		=> $JO_CODE,
											'SO_NUM' 		=> $SO_NUM,
											'SO_CODE' 		=> $SO_CODE,
											'CCAL_NUM' 		=> $CCAL_NUM,
											'CCAL_CODE' 	=> $CCAL_CODE,
											'BOM_NUM' 		=> $BOM_NUM,
											'BOM_CODE' 		=> $BOM_CODE,
											'CUST_CODE' 	=> $CUST_CODE,
											'CUST_DESC' 	=> $CUST_DESC,
											'STF_TYPE' 		=> $STF_TYPE,
											'STF_FROM' 		=> $STF_FROM,
											'STF_DEST' 		=> $NEXT_STEP,
											'QRC_NUM'		=> $QRC_NUM,
											'STF_NOTES' 	=> $STF_NOTES,
											'STF_STAT' 		=> $STF_STAT,
											'STF_CREATER'	=> $DefEmp_ID,
											'STF_CREATED'	=> $STF_CREATED,
											'Patt_Year'		=> $Patt_Year,
											'Patt_Month' 	=> $Patt_Month,
											'Patt_Date'		=> $Patt_Date,
											'Patt_Number'	=> $Patt_Number);
					$this->m_prodprocess->add($AddSTF);
				// END : SAVE TO HEADER STF

				// CHECK ISLAST PRODS
					$PRODS_LAST	= 0;
					$sqlPS 		= "SELECT PRODS_LAST FROM tbl_prodstep WHERE PRODS_STEP = '$STF_FROM' LIMIT 1";
					$resPS 		= $this->db->query($sqlPS)->result();
					foreach($resPS as $rowPS) :
						$PRODS_LAST = $rowPS->PRODS_LAST;
					endforeach;
					$PRODS_LAST		= $ISLAST;

				// GET WH PROD
					$WH_NUM		= '';
					$WH_CODE	= '';
					$sqlWHP 	= "SELECT WH_NUM, WH_CODE FROM tbl_warehouse WHERE ISWHPROD = 1 LIMIT 1";
					$resWHP 	= $this->db->query($sqlWHP)->result();
					foreach($resWHP as $rowWHP) :
						$WH_NUM 	= $rowWHP->WH_NUM;
						$WH_CODE	= $rowWHP->WH_CODE;
					endforeach;
				
				// START : JOURNAL HEADER
					$this->load->model('m_journal/m_journal', '', TRUE);
					
					$JournalH_Code	= $STF_NUM;
					$JournalType	= 'STF';
					$JournalH_Date	= $STF_DATE;
					$Company_ID		= $comp_init;
					$DOCSource		= $STF_NUM;
					$LastUpdate		= date('Y-m-d H:i:s');
					$WH_CODE		= $WH_CODE;
					$Refer_Number	= '';
					$RefType		= 'STF';
					$PRJCODE		= $PRJCODE;
					
					$parameters = array('JournalH_Code' 	=> $JournalH_Code,
										'JournalType'		=> $JournalType,
										'JournalH_Desc'		=> $STF_NOTES,
										'JournalH_Date' 	=> $JournalH_Date,
										'Company_ID' 		=> $Company_ID,
										'Source'			=> $DOCSource,
										'Emp_ID'			=> $DefEmp_ID,
										'LastUpdate'		=> $LastUpdate,	
										'KursAmount_tobase'	=> 1,
										'WHCODE'			=> $WH_CODE,
										'Reference_Number'	=> $Refer_Number,
										'Manual_No'			=> $STF_CODE,
										'RefType'			=> $RefType,
										'PRJCODE'			=> $PRJCODE);
					$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
				// END : JOURNAL HEADER

				// INPUT DAN OUTPUT BAHAN MATERIAL
					$TOT_EXP	= 0;
					$TOT_VOLM	= 0;
					$sql_MTR	= "SELECT ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT, ITM_QTY, ITM_PRICE, JOSTF_TYPE
									FROM tbl_jo_stfdetail WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM'
										AND JOSTF_STEP = '$STF_FROM'";
					$res_MTR	= $this->db->query($sql_MTR)->result();
					foreach($res_MTR as $row_MTR):
						$ITM_CODE		= $row_MTR->ITM_CODE;
						$ITM_GROUP		= $row_MTR->ITM_GROUP;
						$ITM_NAME		= $row_MTR->ITM_NAME;
						$ITM_UNIT		= $row_MTR->ITM_UNIT;
						$ITM_QTY		= $row_MTR->ITM_QTY;
						$ITM_PRICE		= $row_MTR->ITM_PRICE;
						$JOSTF_TYPE		= $row_MTR->JOSTF_TYPE;

						$ACC_IDX 		= '';
						$ACC_ID_UMX		= '';
						if($ITM_NAME == '')
						{
							$sqlUPDDET	= "UPDATE tbl_jo_stfdetail A, tbl_item B
												SET A.ITM_NAME = B.ITM_NAME, A.ITM_GROUP = B.ITM_GROUP, A.ITM_UNIT = B.ITM_UNIT
											WHERE A.ITM_CODE = B.ITM_CODE
												AND A.PRJCODE = B.PRJCODE";
							$this->db->query($sqlUPDDET);
						}

						$d['PRJCODE']	= $PRJCODE;
						$d['STF_NUM']	= $STF_NUM;
						$d['STF_CODE']	= $STF_CODE;
						$d['STF_DATE']	= $STF_DATE;
						$d['JO_NUM']	= $JO_NUM;
						$d['JO_CODE']	= $JO_CODE;
						$d['SO_NUM']	= $SO_NUM;
						$d['SO_CODE']	= $SO_CODE;
						$d['BOM_NUM']	= $BOM_NUM;
						$d['BOM_CODE']	= $BOM_CODE;
						$d['STF_FROM']	= $STF_FROM;
						$d['STF_DEST']	= $STF_DEST;
						$d['ITM_TYPE']	= $JOSTF_TYPE;
						$d['ITM_CODE']	= $ITM_CODE;
						$d['ITM_GROUP']	= $ITM_GROUP;
						$d['ITM_NAME']	= $ITM_NAME;
						$d['ITM_UNIT']	= $ITM_UNIT;
						$d['STF_VOLM']	= $ITM_QTY;
						$d['STF_PRICE']	= $ITM_PRICE;
						$TOT_VOLM		= $TOT_VOLM + $ITM_QTY;
						$d['STF_TOTAL']	= $ITM_QTY * $ITM_PRICE;
						$TOT_EXP1		= $ITM_QTY * $ITM_PRICE;
						$TOT_EXP 		= $TOT_EXP + $TOT_EXP1;

						$ACC_ID 		= '';
						$ACC_ID_UM		= '';
						$NEEDQRCX		= 0;
						$ITM_TYPE		= '';
						$sql_ITM		= "SELECT ITM_GROUP, ITM_NAME, ITM_UNIT, ACC_ID, ACC_ID_UM, NEEDQRC, ITM_TYPE
											FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$res_ITM		= $this->db->query($sql_ITM)->result();
						foreach($res_ITM as $row_ITM):
							$ITM_GROUP		= $row_ITM->ITM_GROUP;
							$ITM_NAME		= $row_ITM->ITM_NAME;
							$ITM_UNIT		= $row_ITM->ITM_UNIT;
							$ACC_ID			= $row_ITM->ACC_ID;
							$ACC_ID_UM		= $row_ITM->ACC_ID_UM;
							$NEEDQRCX		= $row_ITM->NEEDQRC;
							$ITM_TYPE		= $row_ITM->ITM_TYPE;
						endforeach;

						$d['ACC_ID']	= $ACC_ID;
						$d['ACC_ID_UM']	= $ACC_ID_UM;
						$d['STF_STAT']	= $STF_STAT;
						$d['QRC_NUM']	= $QRC_NUM;

						$this->db->insert('tbl_stf_detail',$d);

						if($LAST_STEP == 1)
						{
							$sqlUPLST	= "UPDATE tbl_stf_detail SET STF_ISLAST = 1 WHERE STF_NUM = '$STF_NUM'";
							$this->db->query($sqlUPLST);
						}

						$JOBCODEID		= '';
						$sql_JOBD		= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$res_JOBD		= $this->db->query($sql_JOBD)->result();
						foreach($res_JOBD as $row_JOBD):
							$JOBCODEID		= $row_JOBD->JOBCODEID;
						endforeach;
						
						if($JOSTF_TYPE == 'IN')
						{
							// START : JOURNAL DETAIL
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $ITM_CODE;
								$JOBCODEID 		= $JOBCODEID;
								$ACC_ID 		= $ACC_ID_UM;
								$ITM_UNIT 		= $ITM_UNIT;
								$ITM_GROUP 		= $ITM_GROUP;
								$ITM_TYPE 		= $ITM_TYPE;
								$ITM_QTY 		= $ITM_QTY;
								$ITM_PRICE 		= $ITM_PRICE;
								$Notes 			= "($STF_FROM) $ITM_NAME";
								$ITM_DISC 		= 0;
								$TAXCODE1 		= '';
								$TAXPRICE1 		= 0;
								
								$JournalH_Code	= $STF_NUM;
								$JournalType	= 'STF';
								$JournalH_Date	= $STF_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= $LastUpdate;
								$WH_CODE		= $WH_CODE;
								$Refer_Number	= '$JO_NUM';
								$RefType		= 'STF';
								$JSource		= 'STF';
								$PRJCODE		= $PRJCODE;

								// CHEK ITM TYPE
									$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
									
								$parameters = array('JournalH_Code' 	=> $JournalH_Code,
													'JournalType'		=> $JournalType,
													'JournalH_Date' 	=> $JournalH_Date,
													'Company_ID' 		=> $Company_ID,
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
													'TRANS_CATEG' 		=> 'STF',			// STF = Section Transfer
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
													'Notes'				=> $Notes);
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							// END : JOURNAL DETAIL

							// START : UPDATE STOCK
								$parameters1 = array('PRJCODE' 	=> $PRJCODE,
													'WH_CODE'	=> $WH_CODE,
													'JOBCODEID'	=> $JOBCODEID,
													'UM_NUM' 	=> $STF_NUM,
													'UM_CODE' 	=> $STF_CODE,
													'ITM_CODE' 	=> $ITM_CODE,
													'ITM_GROUP'	=> $ITM_GROUP,
													'ITM_QTY' 	=> $ITM_QTY,
													'ITM_PRICE' => $ITM_PRICE);
								$this->m_itemusage->updateITM_Min($parameters1);
							// START : UPDATE STOCK
							
							// START : RECORD TO ITEM HISTORY
								$this->m_journal->createITMHistMinSTF($JournalH_Code, $parameters);
							// START : RECORD TO ITEM HISTORY
						}
						
						// DISABLED ----
						// START : UPDATE JUMLAH PER TAHAPAN
							$prmSOConcl		= array('PRJCODE' 		=> $PRJCODE,
													'STF_NUM' 		=> $STF_NUM,
													'STF_CODE' 		=> $STF_CODE,
													'CUST_CODE' 	=> $CUST_CODE,
													'CUST_DESC' 	=> $CUST_DESC,
													'SO_NUM' 		=> $SO_NUM,
													'SO_CODE'		=> $SO_CODE,
													'JO_NUM' 		=> $JO_NUM,
													'JO_CODE'		=> $JO_CODE,
													'CCAL_NUM' 		=> $CCAL_NUM,
													'CCAL_CODE'		=> $CCAL_CODE,
													'BOM_NUM' 		=> $BOM_NUM,
													'BOM_CODE'		=> $BOM_CODE,
													'ITM_CODE' 		=> $ITM_CODE,
													'ITM_NAME'		=> $ITM_NAME,
													'STF_FROM'		=> $STF_FROM,
													'TOT_VOLM'		=> $ITM_QTY);
							//$this->m_prodprocess->updateSOConcl($prmSOConcl);
						// END : UPDATE JUMLAH PER TAHAPAN

						// DISABLED ----
						// UPDATE QTY PRODUCT
							$sqlUPDPROD	= "UPDATE tbl_jo_stfdetail SET STF_STAT = $STF_STAT
											WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM'
												AND ITM_CODE = '$ITM_CODE' AND JOSTF_STEP = '$STF_FROM'";
							//$this->db->query($sqlUPDPROD);

						// UPDATE ISPROC
							$sqlISPROC	= "UPDATE tbl_jo_stfdetail SET ISPROC = 1, STF_STAT = $STF_STAT
											WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' AND JOSTF_STEP = '$STF_FROM'";
							$this->db->query($sqlISPROC);
					endforeach;

				// CARI ITEM YANG MERUPAKAN GROUPING DARI QRC DI JO TERSEBUT
					$ITM_GROUP		= '';
					$sql_ITMG		= "SELECT A.ITM_CODE FROM tbl_jo_stfdetail A
										INNER JOIN tbl_item_collh B ON A.ITM_CODE = B.ICOLL_CODE
										WHERE A.JO_NUM = '$JO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$res_ITMG		= $this->db->query($sql_ITMG)->result();
					foreach($res_ITMG as $row_ITMG):
						$ITM_GROUP	= $row_ITMG->ITM_CODE;

						$PRODSTEP	= "";
						$sqlPRD1	= "SELECT PROD_STEP FROM tbl_qrc_detail WHERE GRP_CODE = '$ITM_GROUP'";
						$resPRD1	= $this->db->query($sqlPRD1)->result();
						foreach($resPRD1 as $rowPRD1):
							$PRODSTEP	= $rowPRD1->PROD_STEP;
						endforeach;
						if($PRODSTEP == "")
							$PRODSTEP1 	= "$STF_FROM";
						else
							$PRODSTEP1 	= "$PRODSTEP~$STF_FROM";

						$sqlUPQRC	= "UPDATE tbl_qrc_detail SET QRC_STAT = 3, QRC_STATU = 2, PROD_STEP = '$PRODSTEP1'
										WHERE GRP_CODE = '$ITM_GROUP' c";
						$this->db->query($sqlUPQRC);

						$sqlUPQRC	= "UPDATE tbl_jo_stfdetail_qrc SET STEP_PROC = 1, STEP_STAT = 1 WHERE ITM_CODE = '$ITM_GROUP'";
						$this->db->query($sqlUPQRC);

						$sqlUPQRC	= "UPDATE tbl_item_collh SET LAST_PROC = '$STF_FROM', LAST_PROCD = '$STF_CREATED' WHERE ICOLL_CODE = '$ITM_GROUP'";
						$this->db->query($sqlUPQRC);
					endforeach;

				// UPDATE JO TO NEED RE-PROCESS
					/*$sqlUPJOH	= "UPDATE tbl_jo_header SET JO_STAT = 5, STATDESC = 'Rejected',
										STATCOL = 'danger', ISREPROC = 1
									WHERE JO_NUM = '$JO_NUM'";*/
					$sqlUPJOH	= "UPDATE tbl_jo_header SET JO_STAT = 5, STATDESC = 'Rejected', STATCOL = 'danger'
									WHERE JO_NUM = '$JO_NUM'";
					$this->db->query($sqlUPJOH);

				// UPDATE JO QTY IN SO
					$sqlJDET 	= "SELECT SO_NUM, ITM_CODE, ITM_QTY, ITM_PRICE, ITM_TOTAL
									FROM tbl_jo_detail WHERE JO_NUM = '$JO_NUM'";
					$resJDET 	= $this->db->query($sqlJDET)->result();
					foreach($resJDET as $rowJDET) :
						$SO_NUM 	= $rowJDET->SO_NUM;
						$ITM_CODE 	= $rowJDET->ITM_CODE;
						$ITM_QTY 	= $rowJDET->ITM_QTY;
						$ITM_PRICE 	= $rowJDET->ITM_PRICE;
						$ITM_TOTAL 	= $rowJDET->ITM_TOTAL;

						$sqlUPSOD	= "UPDATE tbl_so_detail SET JO_VOLM = JO_VOLM - $ITM_QTY, 
											JO_AMOUNT = JO_AMOUNT - $ITM_TOTAL 
										WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sqlUPSOD);
					endforeach;
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STF_STAT');			// IF "ADD" CONDITION ALWAYS = SO_STAT
					$parameters 	= array('DOC_CODE' 		=> $STF_NUM,		// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "STF",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_stf_header",// TABLE NAME
											'KEY_NAME'		=> "STF_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "STF_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $STF_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STF_STAT,		// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_STF",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_STF_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_STF_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_STF_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_STF_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_STF_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_STF_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					//$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "STF_NUM",
											'DOC_CODE' 		=> $STF_NUM,
											'DOC_STAT' 		=> $STF_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_stf_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $STF_NUM;
					$MenuCode 		= 'MN378';
					$TTR_CATEG		= 'C-QRC';
					
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
			
			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "STF",
										'TR_DATE' 		=> $STF_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url	= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function Get_Data() // GOOD
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$QRCode		= $this->input->post('scanned-QRText');
		$CSTEP		= $this->input->post('CSTEP');
		
		$ISREADY	= 0;
		$collDATA	= explode('~', $QRCode);
		$JONUM 		= $collDATA[0];
		//$SO_NUM 	= $collDATA[1];

		// GET SO NUMBER
			$JO_NUM	= '';
			$SO_NUM = '';
			$sqlSO 	= "SELECT JO_NUM, SO_NUM FROM tbl_jo_header WHERE JO_UC = '$JONUM' LIMIT 1";
			$resSO 	= $this->db->query($sqlSO)->result();
			foreach($resSO as $rowSO) :
				$JO_NUM = $rowSO->JO_NUM;
				$SO_NUM = $rowSO->SO_NUM;
			endforeach;

		if($JO_NUM == '')
		{
			// GET JO NUMBER FROM GREIGE GROUP
				$JO_NUM	= '';
				$sqlJO 	= "SELECT JO_NUM FROM tbl_item_collh WHERE ICOLL_CODE = '$JONUM' LIMIT 1";
				$resJO 	= $this->db->query($sqlJO)->result();
				foreach($resJO as $rowJO) :
					$JO_NUM = $rowJO->JO_NUM;
				endforeach;
		}

		if($JO_NUM == '')
		{
			echo "~";
		}
		else
		{
			// FIND THE BEFORE STEP
			// GET EXIST PROCESS
				$sqlOSTEPC 	= "tbl_jo_stfdetail WHERE JO_NUM = '$JO_NUM' AND JOSTF_STEP = '$CSTEP'";
				$resOSTEPC 	= $this->db->count_all($sqlOSTEPC);

				if($resOSTEPC > 0)
				{
					// 1. GET POSITION STEP ORDER / URUTAN PROSES
						$STEP_ORD 	= 0;
						$sqlOSTEP 	= "SELECT JOSTF_ORD FROM tbl_jo_stfdetail WHERE JO_NUM = '$JO_NUM' AND JOSTF_STEP = '$CSTEP' LIMIT 1";
						$resOSTEP 	= $this->db->query($sqlOSTEP)->result();
						foreach($resOSTEP as $rowOSTEP) :
							$JOSTF_ORD = $rowOSTEP->JOSTF_ORD;
						endforeach;

					// 2. GET CURRENT STEP
						$PRODS_ORD 	= 0;
						$sqlCSTEP 	= "SELECT PRODS_ORDER FROM tbl_prodstep WHERE PRODS_STEP = '$CSTEP' LIMIT 1";
						$resCSTEP 	= $this->db->query($sqlCSTEP)->result();
						foreach($resCSTEP as $rowCSTEP) :
							$PRODS_ORD = $rowCSTEP->PRODS_ORDER;
						endforeach;
						$PRODS_ORD = $JOSTF_ORD;

					// 3. COUNT ALL PROCESS BEFORE STEP
						$TOT_STEP	= 0;
						$sqlBSTEPC 	= "SELECT COUNT(DISTINCT JOSTF_STEP) AS TOT_STEP FROM tbl_jo_stfdetail A
											INNER JOIN tbl_prodstep B ON B.PRODS_STEP = A.JOSTF_STEP
										WHERE A.JO_NUM = '$JO_NUM'
											AND A.JOSTF_ORD < $PRODS_ORD";
						$resBSTEPC 	= $this->db->query($sqlBSTEPC)->result();
						foreach ($resBSTEPC as $TSTEP):
							$TOT_STEP	= $TSTEP->TOT_STEP;
						endforeach;

						// IF TOT_STEP = 0, THE PROCESS IS READY. BECAUSE IT'S THE FIRST PROCESS
						if($TOT_STEP > 0)
						{
							// 4. GET BEFORE STEP
								$BEF_STEP	= '';
								$BEF_NAME	= '';
								$sqlBSTEP 	= "SELECT DISTINCT A.JOSTF_STEP, B.PRODS_NAME
												FROM tbl_jo_stfdetail A
													INNER JOIN tbl_prodstep B ON B.PRODS_STEP = A.JOSTF_STEP
												WHERE A.JO_NUM = '$JO_NUM'
													AND A.JOSTF_ORD < $PRODS_ORD
												ORDER BY B.PRODS_ORDER DESC LIMIT 1";
								$resBSTEP 	= $this->db->query($sqlBSTEP)->result();
								foreach($resBSTEP as $rowBSTEP) :
									$BEF_STEP = $rowBSTEP->JOSTF_STEP;
									$BEF_NAME = $rowBSTEP->PRODS_NAME;
								endforeach;

							// CHECK EXIST BEFORE STEP BY STEP_CODE. IF EXIST, PROCESS IS READY
								$sqlCBSTEPC	= "tbl_stf_header WHERE JO_NUM = '$JO_NUM' AND STF_FROM = '$BEF_STEP'";
								$resCBSTEPC = $this->db->count_all($sqlCBSTEPC);

							if($resCBSTEPC == 0)
							{
								$ISREADY	= 0;
							}
							else
							{
								$ISREADY	= 1;
							}
						}
						else
						{
							// CHECK 
							$ISREADY	= 1;
						}

					// CHECK PROCESS STATUS
						$ISPROC 	= 0;
						$ISPROCD	= '';
						$sqlISPROC 	= "SELECT ISPROC, ISPROCD FROM tbl_jo_stfdetail WHERE JO_NUM = '$JO_NUM' AND JOSTF_STEP = '$CSTEP'";
						$resISPROC 	= $this->db->query($sqlISPROC)->result();
						foreach ($resISPROC as $isProc):
							$ISPROC		= $isProc->ISPROC;
							$ISPROCD	= $isProc->ISPROCD;
						endforeach;

					// GET DETAIL
						$sqlJOH 	= "SELECT A.PRJCODE, A.JO_ID, A.JO_NUM, A.JO_CODE, A.JO_DATE, A.CUST_CODE, A.CUST_DESC, A.JO_DESC,
											A.SO_NUM, A.SO_CODE, B.SO_DATE
										FROM tbl_jo_header A
											INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
										WHERE JO_NUM = '$JO_NUM'";
						$resJOH		= $this->db->query($sqlJOH)->result();
						foreach ($resJOH as $key):
							$PRJCODE 	= $key->PRJCODE;
							$JO_NUM 	= $key->JO_NUM;
							$JO_CODE 	= $key->JO_CODE;
							$JO_DATE 	= $key->JO_DATE;
							$JO_DATE	= date('d-m-Y', strtotime($JO_DATE));
							$SO_NUM 	= $key->SO_NUM;
							$SO_CODE 	= $key->SO_CODE;
							$SO_DATE 	= $key->SO_DATE;
							$SO_DATE	= date('d-m-Y', strtotime($SO_DATE));
							$JO_DESC 	= $key->JO_DESC;
							$CUST_CODE 	= $key->CUST_CODE;
							$CUST_DESC 	= $key->CUST_DESC;
						endforeach;
					
					// CHECK KETERSEDIAAN MATERIAL
						$ITMPRJ 	= $PRJCODE;
						$ITMCOD 	= "";
						$ITMQTY		= 0;
						$ITMCAT 	= "";
						$ITMNM 		= "";
						$tRow 		= 0;
						$mSTCKD 	= "";
						$mSTCKDD 	= "";
						$isNeedMTR 	= 0;
						$ITMLR 		= "";
						$tRowLR 	= 0;
						$mLRD		= "";
						$mLRDD 		= "";
						$sITMQTY 	= "SELECT A.PRJCODE, A.ITM_CODE, A.ITM_QTY, A.ITM_CATEG, B.ITM_NAME, B.ITM_LR
										FROM tbl_jo_stfdetail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
										WHERE A.PRJCODE = '$PRJCODE' AND A.JO_NUM = '$JO_NUM'
											AND A.JOSTF_STEP = '$CSTEP' AND A.JOSTF_TYPE = 'IN'";
						$rITMQTY 	= $this->db->query($sITMQTY)->result();
						foreach($rITMQTY as $rwITMQTY) :
							$ITMPRJ = $rwITMQTY->PRJCODE;
							$ITMCOD = $rwITMQTY->ITM_CODE;
							$ITMQTY = $rwITMQTY->ITM_QTY;
							$ITMCAT = $rwITMQTY->ITM_CATEG;
							$ITMNM 	= $rwITMQTY->ITM_NAME;
							$ITMLR 	= $rwITMQTY->ITM_LR;

							if($ITMCAT == 'WIP')
							{
								$ITMSTK 	= 0;
								$ITM_LR 	= "";
								$sISTCK 	= "SELECT A.ITM_VOLM FROM tbl_item A WHERE A.ITM_CODE = '$ITMCOD' AND A.PRJCODE = '$PRJCODE'";
								$rITMQTY 	= $this->db->query($sISTCK)->result();
								foreach($rITMQTY as $rwITMQTY) :
									$ITMSTK = $rwITMQTY->ITM_VOLM;
								endforeach;
								if($ITMSTK == '') $ITMSTK = 0;
							}
							else
							{
								$ITMSTK = 0;
								$sISTCK = "SELECT A.ITM_VOLM FROM tbl_item_whqty A
											INNER JOIN tbl_warehouse B ON B.WH_NUM = A.WH_CODE AND B.ISWHPROD = 1
											WHERE A.ITM_CODE = '$ITMCOD'";
								$rITMQTY 	= $this->db->query($sISTCK)->result();
								foreach($rITMQTY as $rwITMQTY) :
									$ITMSTK = $rwITMQTY->ITM_VOLM;
								endforeach;
								if($ITMSTK == '') $ITMSTK = 0;

								if($ITMLR == "")
								{
									$tRowLR 	= $tRowLR+1;
									if($tRowLR == 1)
										$mLRD 	= "$ITMNM";
									else
										$mLRD 	= $mLRD.", $ITMNM";
								}
							}

							if($ITMSTK < $ITMQTY)
							{
								$tRow 		= $tRow+1;
								$isNeedMTR 	= 1;
								$ITMNEED	= number_format($ITMQTY - $ITMSTK, 2);
								if($tRow == 1)
									$mSTCKD = "$ITMNM ($ITMNEED)";
								else
									$mSTCKD = $mSTCKD.", $ITMNM ($ITMNEED)";
							}
						endforeach;
						if($tRow > 0)
							$mSTCKDD = "RM Required : $mSTCKD";

						if($tRowLR > 0)
							$mLRDD = "Not Setting LR : $mLRD";

					// CHECK OTORISASI
						$sqlOAPPC 	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN378' AND APPROVER_1 = '$DefEmp_ID';";
						$resOAPPC 	= $this->db->count_all($sqlOAPPC);

					// COLLECTING DATA
						echo "$PRJCODE~$JO_NUM~$JO_CODE~$JO_DATE~$SO_NUM~$SO_CODE~$SO_DATE~$JO_DESC~$CUST_CODE~$CUST_DESC~$ISREADY~$ISPROC~$ISPROCD~$resOAPPC~$tRow~$mSTCKDD~$tRowLR~$mLRDD";
						//echo "$sqlBSTEP";
				}
				else
				{
					echo "NoP";	// No Process
				}
		}
	}
	
	function prnt180d0bdoc()
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
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
			
			$getSO 			= $this->m_prodprocess->get_stf_by_number($SO_NUM)->row();
			
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
			$data['SO_STAT'] 	= $getSO->SO_STAT;
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
}