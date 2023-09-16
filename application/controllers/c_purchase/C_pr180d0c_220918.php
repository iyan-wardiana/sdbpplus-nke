<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 18 Oktober 2017
	* File Name		= C_pr180d0c.php
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
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update (Acc)'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' disabled='disabled'>
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
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update (Acc)'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' onClick='pRn_P0l(".$noU.")'>
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
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update (Acc)'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' onClick='pRn_P0l(".$noU.")'>
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

  	function get_AllDataPRGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$PO_NUM			= $_GET['REFNO'];
		$PR_STAT		= $_GET['DSTAT'];
		$PR_CATEG		= $_GET['SRC'];
		
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
			$num_rows 		= $this->m_purchase_req->get_AllDataGRPC($PRJCODE, $PO_NUM, $PR_STAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_req->get_AllDataGRPL($PRJCODE, $PO_NUM, $PR_STAT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PR_NUM		= $dataI['PR_NUM'];
				$PR_CODE	= $dataI['PR_CODE'];
				$PR_CATEG	= $dataI['PR_CATEG'];
				
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
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update (Acc)'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' disabled='disabled'>
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
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update (Acc)'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' onClick='pRn_P0l(".$noU.")'>
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
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update (Acc)'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' onClick='pRn_P0l(".$noU.")'>
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
				
				$MNCODE	= 'MN018';
				$PRCATD = "SPP Bahan";
				$FAICON = "fa fa-cubes";
				if($PR_CATEG == 1)
				{
					$MNCODE	= 'MN435';
					$PRCATD = "SPP Alat";
					$FAICON = "fa fa-steam";
				}

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.MENU_CODE = '$MNCODE' AND A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PR_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("$noU.",
										  	"<div style='white-space:nowrap'>
												<strong>".$dataI['PR_CODE']."</strong><br>
												<div style='font-style: italic;'><strong><i class='fa ".$FAICON." margin-r-5'></i>".$PRCATD." </strong></div>
											</div>",
										  	$PR_DATEV,
										  	$JOBDESC,
										  	$empName,
										  	"<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
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

  	function get_AllDataIITMDET() // GOOD
	{
		$PRJCODE	= $_GET['id'];
		$PR_NUM		= $_GET['PR_NUM'];
		$task		= $_GET['task'];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
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
			
			$columns_valid 	= array("PR_DESC_ID",
									"ITM_CODE", 
									"JOBCODEID");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}

			if($task == 'add') $PR_STAT = 1;
			
			$s_PRSTAT 	= "SELECT PR_STAT FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM'";
			$r_PRSTAT 	= $this->db->query($s_PRSTAT)->result();
			foreach($r_PRSTAT as $rw_PRSTAT):
				$PR_STAT 	= $rw_PRSTAT->PR_STAT;
			endforeach;

			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataITMDETC($PRJCODE, $PR_NUM, $task, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_req->get_AllDataITMDETL($PRJCODE, $PR_NUM, $task, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PR_ID 			= $dataI['PR_ID'];
				$PR_NUM 		= $dataI['PR_NUM'];
                $PR_CODE 		= $dataI['PR_CODE'];
                $PRJCODE 		= $dataI['PRJCODE'];
                $JOBCODEDET		= $dataI['JOBCODEDET'];
                $JOBCODEID 		= $dataI['JOBCODEID'];
                $JOBPARENT 		= $dataI['JOBPARENT'];
                $ITM_CODE 		= $dataI['ITM_CODE'];
                $ITM_NAME 		= $dataI['ITM_NAME'];

				$ITM_CODE_H		= '';
				$ITM_TYPE 		= '';
				$sqlITMNM		= "SELECT ITM_CODE_H, ITM_NAME, ITM_TYPE FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resITMNM		= $this->db->query($sqlITMNM)->result();
				foreach($resITMNM as $rowITMNM) :
					$ITM_CODE_H	= $rowITMNM->ITM_CODE_H;
					$ITM_NAME	= $rowITMNM->ITM_NAME;
					$ITM_TYPE	= $rowITMNM->ITM_TYPE;
				endforeach;

                $SNCODE 		= "";
                $ITM_UNIT 		= $dataI['ITM_UNIT'];
                $PR_VOLM 		= $dataI['PR_VOLM'];
                $PR_PRICE 		= $dataI['PR_PRICE'];
                $PO_VOLM 		= $dataI['PO_VOLM'];
                $ITM_VOLMBG		= $dataI['ITM_VOLMBG'];
                $ITM_BUDG 		= $dataI['ITM_BUDG'];
                $PR_CVOL 		= $dataI['PR_CVOL'];
                $PR_TOTAL 		= $dataI['PR_TOTAL'];
                $PR_DESC_ID 	= $dataI['PR_DESC_ID'];
                $PR_DESC 		= $dataI['PR_DESC'];
                //$PR_STAT 		= $dataI['PR_STAT'];
                $itemConvertion	= 1;

                //$REM_VOLPR 	= $PR_VOLM - $PO_VOLM;
                $REM_VOLPR 		= $PR_VOLM - $PR_CVOL - $PO_VOLM;

                $JOBPARDESC 	= "";
                $sqlJPAR		= "SELECT A.JOBDESC FROM tbl_joblist_detail_$PRJCODEVW A 
									WHERE A.JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
                $resJPAR		= $this->db->query($sqlJPAR)->result();
                foreach($resJPAR as $rowJPAR) :
                    $JOBPARDESC	= $rowJPAR->JOBDESC;
                endforeach;

		        // GET TOTAL SPP AND REMAIN IN THIS DOCUMENT
		        	$TOTPRQTY1 		= 0;
		        	$TOTPRAMOUNT1 	= 0;
		        	$TOTPRAMOUNT1CNC= 0;
					$s_01 			= "SELECT SUM(PR_VOLM - PR_CVOL) AS TOTPRQTY1, SUM(PR_TOTAL) AS TOTPRAMOUNT1, SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT1CNC
										FROM tbl_pr_detail
										WHERE ITM_CODE = '$ITM_CODE' AND PR_ID != $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
											 AND JOBCODEID = '$JOBCODEID'
										UNION
										SELECT SUM(PR_VOLM) AS TOTPRQTY1, SUM(PR_TOTAL) AS TOTPRAMOUNT1, SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT1CNC
										FROM tbl_pr_detail_tmp
										WHERE ITM_CODE = '$ITM_CODE' AND PR_ID != $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
											 AND JOBCODEID = '$JOBCODEID'";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOTPRQTY1a 	= $rw_01->TOTPRQTY1;
						if($TOTPRQTY1a == '')
							$TOTPRQTY1a = 0;
						$TOTPRAMOUNT1a 	= $rw_01->TOTPRAMOUNT1;
						if($TOTPRAMOUNT1a == '')
							$TOTPRAMOUNT1a = 0;

						$TOTPRQTY1 		= $TOTPRQTY1+$TOTPRQTY1a;
						$TOTPRAMOUNT1 	= $TOTPRAMOUNT1+$TOTPRAMOUNT1a;
					endforeach;
					if($TOTPRQTY1 == '')
						$TOTPRQTY1		= 0;
					if($TOTPRAMOUNT1 == '')
						$TOTPRAMOUNT1 	= 0;
				
				// TOTAL PR CONFIRMED AND APPROVED FROM ANOTHER DOCUMENT
		        	$TOTPRQTY2 		= 0;
		        	$TOTPRAMOUNT2 	= 0;
		        	$TOTPRAMOUNT2CNC= 0;
		        	$test 			= 0;
					$sqlQTY			= "SELECT SUM(REQ_VOLM) AS TOTPRQTY2, SUM(REQ_AMOUNT) AS TOTPRAMOUNT2, SUM(0) AS TOTPRAMOUNT2CNC
										FROM tbl_joblist_detail
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
										UNION
										SELECT SUM(PR_VOLM - PR_CVOL) AS TOTPRQTY2, SUM((PR_VOLM - PR_CVOL) * PR_PRICE) AS TOTPRAMOUNT2,
											SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT2CNC
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE 
											B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT IN (1,2,3,6) AND A.PR_NUM != '$PR_NUM'";
					$resQTY 		= $this->db->query($sqlQTY)->result();
					foreach($resQTY as $row1a) :
						$TOTPRQTY2a 	= $row1a->TOTPRQTY2;
						if($TOTPRQTY2a == '')
							$TOTPRQTY2a = 0;
						$TOTPRAMOUNT2a 	= $row1a->TOTPRAMOUNT2;
						if($TOTPRAMOUNT2a == '')
							$TOTPRAMOUNT2a = 0;

						$test 			= $TOTPRAMOUNT2a;
						$TOTPRQTY2 		= $TOTPRQTY2+$TOTPRQTY2a;
						$TOTPRAMOUNT2 	= $TOTPRAMOUNT2+$TOTPRAMOUNT2a;
					endforeach;
					if($TOTPRQTY2 == '')
						$TOTPRQTY2	= 0;
					if($TOTPRAMOUNT2 == '')
						$TOTPRAMOUNT2	= 0;

				$TOTPRQTY 		= $TOTPRQTY1 + $TOTPRQTY2;
				$TOTPRAMOUNT 	= $TOTPRAMOUNT1 + $TOTPRAMOUNT2;

				if($TOTPRQTY == '')
				{
					$TOTPRAMOUNT	= 0;
					$TOTPRQTY		= 0;
				}
                if($ITM_TYPE == 'SUBS')
                {
                    $REQ_VOLM	= 0;
                    $REQ_AMOUNT	= 0;
                    $sqlREQ		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail 
                                    WHERE PRJCODE = '$PRJCODE'
                                        AND ITM_CODE = '$ITM_CODE_H'";
                    $resREQ		= $this->db->query($sqlREQ)->result();
                    foreach($resREQ as $rowREQ) :
                        $TOTPRQTY		= $rowREQ->REQ_VOLM;
                        $TOTPRAMOUNT	= $rowREQ->REQ_AMOUNT;
                    endforeach;
                }
                
                $TOT_USEBUDG	= $TOTPRAMOUNT;					// 15
                $ITM_BUDG		= $dataI['ITM_BUDG'];			// 16
                if($ITM_BUDG == '')
                    $ITM_BUDG	= 0;										
                
                $ITM_VOLM	= 0;
                $ADD_VOLM	= 0;
                
                $sqlITM		= "SELECT A.ITM_VOLM, A.ADD_VOLM
                                FROM tbl_joblist_detail A
                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                    AND B.PRJCODE = '$PRJCODE'
                                WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','I','T') 
                                AND A.ITM_CODE = '$ITM_CODE'
                                AND A.JOBPARENT = '$JOBCODEID'";
                                
                $BUDG_VOLM		= 0;
                $ITM_BUDQTY		= 0;
                $resITM			= $this->db->query($sqlITM)->result();
                foreach($resITM as $rowITM) :
                    $ITM_VOLM	= $rowITM->ITM_VOLM;
                    $ADD_VOLM	= $rowITM->ADD_VOLM;
                    $BUDG_VOLM	= $ITM_VOLM + $ADD_VOLM;
                endforeach;
                $BUDG_VOLM		= $ITM_VOLMBG + $ADD_VOLM;									
                $ITM_BUDQTY		= $BUDG_VOLM;
                
                // SISA QTY PR
                $REMPRQTY		= $ITM_BUDQTY - $TOTPRQTY;

                /*$s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$r_isLS 	= $this->db->count_all($s_isLS);
				if($r_isLS == 0)
				{
					$PR_VOLM 	= $ITM_BUDQTY - $TOTPRQTY;
				}*/

                $PR_CVOLV 		= "";
				if($PR_CVOL > 0)
				{
					$PR_CVOLV 	= 	"<div style='white-space:nowrap;'>
										<span class='text-red' style='white-space:nowrap;'><i class='glyphicon glyphicon-chevron-down'></i>
								  		".number_format($PR_CVOL, 2)."</span>
								  	</div>";
				}

				if($PR_STAT == 1 || $PR_STAT == 2 || $PR_STAT == 4)
				{
                    $s_DESC		= "SELECT DESC_NOTES FROM tbl_desc WHERE DESC_ID = '$PR_DESC_ID'";
                    $r_DESC		= $this->db->query($s_DESC)->result();
                    foreach($r_DESC as $rw_DESC) :
                        $PR_DESC	= $rw_DESC->DESC_NOTES;
                    endforeach;
				}

				$getPR_R 	= "SELECT IFNULL(SUM(A.PR_VOLM), 0) AS ITMVOLM_R
								FROM tbl_pr_detail A
								WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'
								AND A.ITM_CODE = '$ITM_CODE' AND A.PR_DESC_ID = '$PR_DESC_ID'";
				$resPR_R 	= $this->db->query($getPR_R);
				foreach($resPR_R->result() as $rPR_R):
					$ITMVOLM_R 	= $rPR_R->ITMVOLM_R;
				endforeach;
				
				$secDelROW 	= base_url().'index.php/c_purchase/c_pr180d0c/delROW/?id=';
				$delROW 	= "$secDelROW~$PR_NUM~$PR_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";
				$secCopy 	= base_url().'index.php/c_purchase/c_pr180d0c/copyROW/?id=';
				$copyROW 	= "$secCopy~$PR_NUM~$PR_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";

				// START : CANCEL VOL. PER ITEM
					$secShwD 	= base_url().'index.php/c_purchase/c_pr180d0c/cancelItem/?id=';
					$canShwRow 	= "$secShwD~$PR_NUM~$PRJCODE~$ITM_CODE~$ITM_NAME~$JOBPARDESC~$PR_VOLM~$PO_VOLM~$REM_VOLPR~$ITM_UNIT";
					$secDelD 	= base_url().'index.php/__l1y/cancelItem/?id=';
					$canclRow 	= "$secDelD~PR~$PRJCODE~$PR_ID~$PR_NUM~$JOBCODEID~$ITM_CODE~$ITM_NAME";
				// END : CANCEL VOL. PER ITEM

				$secChgVol 	= base_url().'index.php/c_purchase/c_pr180d0c/chgVol/?id=';
				$chgVOL 	= "$secChgVol~$PR_NUM~$PR_ID~$ITM_CODE~$PR_PRICE~$PRJCODE";

				// $ITM_R = "";
				if($PR_STAT == 1 || $PR_STAT == 4)
				{
					$BTN_DELVW 		= "<a href='#' onClick='delRow(".$noU.")' title='Delete Document' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>";
					$BTN_COPYVW		= "<a onClick='copyRow(".$noU.")' title='Copy' class='btn btn-warning btn-xs'><i class='glyphicon glyphicon-copy'></i></a>";
					$BTN_CNCVW		= "";
					$ITM_VOLVW 		= "<input type='text' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='ITM_VOLMBGx".$noU."' id='ITM_VOLMBGx".$noU."' value='".number_format($ITM_BUDQTY, 2)."' disabled >";
					$ITM_TOTPRQTYVW = "<input type='text' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='TOTPRQTYx".$noU."' id='TOTPRQTYx".$noU."' value='".number_format($TOTPRQTY, 2)."' disabled >";
					$ITM_PRVOLMVW 	= "<input type='text' name='PR_VOLM".$noU."' id='PR_VOLM".$noU."' value='".number_format($PR_VOLM, 2)."' class='form-control' style='min-width:80px; max-width:110px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='chgVOL(this,".$noU.");'>";
					$ITM_DESCVW 	= 	"<div class='input-group'>
		                                    <div class='input-group-btn'>
												<button type='button' class='btn btn-primary' onClick='getDL(".$noU.")'><i class=' fa fa-commenting'></i></button>
		                                    </div>
		                                    <input type='hidden' name='data[".$noU."][PR_DESC_ID]' id='data".$noU."PR_DESC_ID' size='20' value='".$PR_DESC_ID."' class='form-control' style='min-width:110px; max-width:500px; text-align:left' data-toggle='modal' data-target='#mdl_addCMN'>
		                                    <input type='text' name='data[".$noU."][PR_DESC]' id='data".$noU."PR_DESC' size='20' value='".$PR_DESC."' class='form-control' style='min-width:110px; max-width:500px; text-align:left; cursor: auto' readonly onClick='showDesc(this.value)'>
			                            </div>";
					$ITM_R 			= "<span id='ITMVOL_R'".$PR_DESC_ID."".$noU." style='text-align: right;'>".number_format($ITMVOLM_R, 2)."</span>";
				}
				elseif($PR_STAT == 3)
				{
					$BTN_DELVW 		= "";
					$BTN_COPYVW		= "";
					$BTN_CNCVW		= "<a onClick='cancelRow(".$noU.")' title='Batalkan Volume SPP' class='btn btn-danger btn-xs'><i class='fa fa-repeat'></i></a>";
					$ITM_VOLVW 		= "".number_format($ITM_BUDQTY, 2)." <input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='ITM_VOLMBGx".$noU."' id='ITM_VOLMBGx".$noU."' value='".number_format($ITM_BUDQTY, 2)."' disabled >";
					$ITM_TOTPRQTYVW = "".number_format($TOTPRQTY, 2)." <input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='TOTPRQTYx".$noU."' id='TOTPRQTYx".$noU."' value='".number_format($TOTPRQTY, 2)."' disabled >";
					$ITM_PRVOLMVW 	= "".number_format($PR_VOLM, 2)." <input type='hidden' name='PR_VOLM".$noU."' id='PR_VOLM".$noU."' value='".number_format($PR_VOLM, 2)."' class='form-control' style='min-width:110px; max-width:300px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='getConvertion(this,".$noU.");'>
		                                      		<?php } ?>";
					$ITM_DESCVW 	= "".$PR_DESC."
	                                    <input type='hidden' name='data[".$noU."][PR_DESC_ID]' id='data".$noU."PR_DESC_ID' size='20' value='".$PR_DESC_ID."' class='form-control' style='min-width:110px; max-width:500px; text-align:left'>
	                                    <input type='hidden' name='data[".$noU."][PR_DESC]' id='data".$noU."PR_DESC' size='20' value='".$PR_DESC."' class='form-control' style='min-width:110px; max-width:500px; text-align:left'>";
					$ITM_R 			= "<span id='ITMVOL_R'".$PR_DESC_ID."".$noU." style='text-align: right;'>".number_format($ITMVOLM_R, 2)."</span>";
				}
				else
				{
					$BTN_DELVW 		= "";
					$BTN_COPYVW		= "";
					$BTN_CNCVW		= "$noU";
					$ITM_VOLVW 		= "".number_format($ITM_BUDQTY, 2)." <input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='ITM_VOLMBGx".$noU."' id='ITM_VOLMBGx".$noU."' value='".number_format($ITM_BUDQTY, 2)."' disabled >";
					$ITM_TOTPRQTYVW = "".number_format($TOTPRQTY, 2)." <input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='TOTPRQTYx".$noU."' id='TOTPRQTYx".$noU."' value='".number_format($TOTPRQTY, 2)."' disabled >";
					$ITM_PRVOLMVW 	= "".number_format($PR_VOLM, 2)." <input type='hidden' name='PR_VOLM".$noU."' id='PR_VOLM".$noU."' value='".number_format($PR_VOLM, 2)."' class='form-control' style='min-width:110px; max-width:300px; text-align:right' onKeyPress='return isIntOnlyNew(event);' onBlur='getConvertion(this,".$noU.");'>
		                                      		<?php } ?>";
					$ITM_DESCVW 	= "".$PR_DESC."
	                                    <input type='hidden' name='data[".$noU."][PR_DESC_ID]' id='data".$noU."PR_DESC_ID' size='20' value='".$PR_DESC_ID."' class='form-control' style='min-width:110px; max-width:500px; text-align:left'>
	                                    <input type='hidden' name='data[".$noU."][PR_DESC]' id='data".$noU."PR_DESC' size='20' value='".$PR_DESC."' class='form-control' style='min-width:110px; max-width:500px; text-align:left'>";
					$ITM_R 			= "<span id='ITMVOL_R'".$PR_DESC_ID."".$noU." style='text-align: right;'>".number_format($ITMVOLM_R, 2)."</span>";
				}

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delROW."'>
												<input type='hidden' name='urlCopy".$noU."' id='urlCopy".$noU."' value='".$copyROW."'>
		                                        <input type='hidden' name='urlgetID".$noU."' id='urlgetID".$noU."' value='".$canShwRow."'>
		                                        <input type='hidden' name='urlcanD".$noU."' id='urlcanD".$noU."' value='".$canclRow."'>
		                                        <input type='hidden' name='urlChgV".$noU."' id='urlChgV".$noU."' value='".$chgVOL."'>
		                                        $BTN_DELVW
	                                            $BTN_COPYVW
	                                            $BTN_CNCVW
		                                        <input type='hidden' id='chk' name='chk' value='".$noU."' width='10' size='15' readonly class='form-control' style='max-width:350px;text-align:right'>
		                                        </div>",
									  		"$ITM_CODE
		                                      	<input type='hidden' id='data".$noU."PR_ID' name='data[".$noU."][PR_ID]' value='".$PR_ID."' class='form-control' style='max-width:300px;'>
		                                      	<input type='hidden' id='data".$noU."PR_NUM' name='data[".$noU."][PR_NUM]' value='".$PR_NUM."' class='form-control' style='max-width:300px;'>
		                                      	<input type='hidden' id='data".$noU."PR_CODE' name='data[".$noU."][PR_CODE]' value='".$PR_CODE."' class='form-control' style='max-width:300px;'>
		                                      	<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' class='form-control' style='max-width:300px;'>
		                                      	<input type='hidden' id='data".$noU."SNCODE' name='data[".$noU."][SNCODE]' value='".$SNCODE."' class='form-control' style='max-width:300px;'>",
										  	"$ITM_NAME ($JOBCODEID)
		                                      	<input type='hidden' id='data".$noU."ITM_NAME' value='".$ITM_NAME."' class='form-control' style='max-width:300px;'>
										  		<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC." ($JOBPARENT)
											  	</div>",
										  	"$ITM_VOLVW
										  		<input type='hidden' style='text-align:right' name='data[".$noU."][ITM_VOLMBG]' id='ITM_VOLMBG".$noU."' value='".$ITM_BUDQTY."' >
										  		<input type='hidden' id='data".$noU."ITM_BUDG' name='data[".$noU."][ITM_BUDG]' value='".$ITM_BUDG."'>",
										  	"$ITM_TOTPRQTYVW
										  		<input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='ITM_REQUESTED".$noU."' id='ITM_REQUESTED".$noU."' value='".$TOTPRAMOUNT."' >
				                            	<input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='TOTPRQTY".$noU."' id='TOTPRQTY".$noU."' value='".$TOTPRQTY."' >
				                            	<input type='hidden' class='form-control' style='min-width:110px; max-width:300px; text-align:right' name='ITM_REQUESTEDx".$noU."' id='ITM_REQUESTEDx".$noU."' value='".number_format($TOTPRAMOUNT, 2)."' disabled >",
									  		"$ITM_PRVOLMVW
									  			$PR_CVOLV
												<input type='hidden' name='data[".$noU."][PR_VOLM]' id='data".$noU."PR_VOLM' value='".$PR_VOLM."' class='form-control' style='max-width:300px;' ><input type='hidden' name='data[".$noU."][PR_PRICE]' id='data".$noU."PR_PRICE' value='".$PR_PRICE."' class='form-control' style='max-width:300px;' >
												<input type='hidden' name='data[".$noU."][PR_TOTAL]' id='data".$noU."PR_TOTAL' value='".$PR_TOTAL."' class='form-control' style='max-width:300px;' onKeyPress='return isIntOnlyNew(event);' >
												<input type='hidden' style='text-align:right' name='itemConvertion".$noU."' id='itemConvertion".$noU."' value='".$itemConvertion."' >",
											$ITM_R,
									  		"<div style='white-space:nowrap; text-align: center'>
									  			$ITM_UNIT
									  			<input type='hidden' name='data[".$noU."][ITM_UNIT]' id='data".$noU."ITM_UNIT' value='".$ITM_UNIT."' class='form-control' style='max-width:300px;' >
									  		</div>",
									  		"$ITM_DESCVW
									  			<input type='hidden' name='data[".$noU."][JOBCODEDET]' id='data".$noU."JOBCODEDET' value='".$JOBCODEDET."' class='form-control' >
									  			<input type='hidden' name='data[".$noU."][JOBCODEID]' id='data".$noU."JOBCODEID' value='".$JOBCODEID."' class='form-control'>");
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "B",
									  "C",
									  "D",
									  "E",
									  "F",
									  "G",
									  "H");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function copyROW()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PR_NUM		= $colExpl[1];
        $PR_ID		= $colExpl[2];
        $ITM_CODE	= $colExpl[3];
        $ITM_NAME	= $colExpl[4];
        $PRJCODE	= $colExpl[5];
        $task		= $colExpl[6];

		if($task == 'add')
		{
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(PR_ID) AS maxNo FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;
			endforeach;

	        $s_01		= "SELECT A.* FROM tbl_pr_detail_tmp A WHERE A.PR_ID = $PR_ID AND A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE'";
			$r_01 		= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$maxNo 			= $maxNo+1;
				$PR_ID 			= $maxNo;
				$PR_NUM 		= $rw_01->PR_NUM;
				$PR_CODE 		= $rw_01->PR_CODE;
				$PR_DATE 		= $rw_01->PR_DATE;
				$PRJCODE 		= $rw_01->PRJCODE;
				$DEPCODE 		= $rw_01->DEPCODE;
				$JOBCODEDET		= $rw_01->JOBCODEDET;
				$JOBCODEID 		= $rw_01->JOBCODEID;
				$JOBPARENT 		= $rw_01->JOBPARENT;
				$JOBPARDESC 	= $rw_01->JOBPARDESC;
				$ITM_CODE 		= $rw_01->ITM_CODE;
				$ITM_NAME 		= $rw_01->ITM_NAME;
				$ITM_UNIT 		= $rw_01->ITM_UNIT;
				$PR_VOLM 		= $rw_01->PR_VOLM;
				$PR_PRICE 		= $rw_01->PR_PRICE;
				$PR_TOTAL 		= $rw_01->PR_TOTAL;
				$PR_DESC_ID 	= $rw_01->PR_DESC_ID;
				$PR_DESC 		= $rw_01->PR_DESC;
				$ITM_VOLMBG 	= $rw_01->ITM_VOLMBG;
				$ITM_BUDG 		= $rw_01->ITM_BUDG;
				$PR_STAT		= $rw_01->PR_STAT;
			endforeach;
			$MAX_PRIDN 	= $maxNo;
		}
		else
		{
			$maxNo 	= 0;
			$sqlMax = "SELECT MAX(PR_ID) AS maxNo FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;
			endforeach;

			$ISEXIST 	= 0;
	        $s_01		= "SELECT A.* FROM tbl_pr_detail A WHERE A.PR_ID = $PR_ID AND A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE'";
			$r_01 		= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$maxNo 			= $maxNo+1;
				$ISEXIST 		= $ISEXIST+1;
				$PR_CODE 		= $rw_01->PR_CODE;
				$PR_DATE 		= $rw_01->PR_DATE;
				$PRJCODE 		= $rw_01->PRJCODE;
				$DEPCODE 		= $rw_01->DEPCODE;
				$JOBCODEDET		= $rw_01->JOBCODEDET;
				$JOBCODEID 		= $rw_01->JOBCODEID;
				$JOBPARENT 		= $rw_01->JOBPARENT;
				$JOBPARDESC 	= $rw_01->JOBPARDESC;
				$ITM_CODE 		= $rw_01->ITM_CODE;
				$ITM_NAME 		= $rw_01->ITM_NAME;
				$ITM_UNIT 		= $rw_01->ITM_UNIT;
				$PR_VOLM 		= $rw_01->PR_VOLM;
				$PR_PRICE 		= $rw_01->PR_PRICE;
				$PR_TOTAL 		= $rw_01->PR_TOTAL;
				$PR_DESC_ID 	= $rw_01->PR_DESC_ID;
				$PR_DESC 		= $rw_01->PR_DESC;
				$ITM_VOLMBG 	= $rw_01->ITM_VOLMBG;
				$ITM_BUDG 		= $rw_01->ITM_BUDG;
				$PR_STAT		= $rw_01->PR_STAT;
			endforeach;
			if($ISEXIST == 0)
			{
		        $s_01a		= "SELECT A.* FROM tbl_pr_detail_tmp A WHERE A.PR_ID = $PR_ID AND A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE'";
				$r_01 		= $this->db->query($s_01a)->result();
				foreach($r_01 as $rw_01):
					$maxNo 			= $maxNo+1;
					$PR_NUM 		= $rw_01->PR_NUM;
					$PR_CODE 		= $rw_01->PR_CODE;
					$PR_DATE 		= $rw_01->PR_DATE;
					$PRJCODE 		= $rw_01->PRJCODE;
					$DEPCODE 		= $rw_01->DEPCODE;
					$JOBCODEDET		= $rw_01->JOBCODEDET;
					$JOBCODEID 		= $rw_01->JOBCODEID;
					$JOBPARENT 		= $rw_01->JOBPARENT;
					$JOBPARDESC 	= $rw_01->JOBPARDESC;
					$ITM_CODE 		= $rw_01->ITM_CODE;
					$ITM_NAME 		= $rw_01->ITM_NAME;
					$ITM_UNIT 		= $rw_01->ITM_UNIT;
					$PR_VOLM 		= $rw_01->PR_VOLM;
					$PR_PRICE 		= $rw_01->PR_PRICE;
					$PR_TOTAL 		= $rw_01->PR_TOTAL;
					$PR_DESC_ID 	= $rw_01->PR_DESC_ID;
					$PR_DESC 		= $rw_01->PR_DESC;
					$ITM_VOLMBG 	= $rw_01->ITM_VOLMBG;
					$ITM_BUDG 		= $rw_01->ITM_BUDG;
					$PR_STAT		= $rw_01->PR_STAT;
				endforeach;
			}

			// CEK MAX_ID IN TABLE TEMPORARY
			$maxNo2 	= 0;
			$sqlMax2 	= "SELECT MAX(PR_ID) AS maxNo FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resMax2 	= $this->db->query($sqlMax2)->result();
			foreach($resMax2 as $rowMax2) :
				$maxNo2 = $rowMax2->maxNo;
			endforeach;
			if($maxNo2 == '')
				$maxNo2 = 0;

			$MAX_PRID 	= $maxNo;
			if($maxNo2 > $maxNo)
				$MAX_PRID 	= $maxNo2;

			$MAX_PRIDN 	= $MAX_PRID+1;
		}

		// GET TOTAL SPP AND REMAIN
			$s_01 	= "SELECT SUM(PR_VOLM) AS TOT_VOL FROM tbl_pr_detail_tmp
						WHERE ITM_CODE = '$ITM_CODE' AND PR_ID != $PR_ID AND PR_NUM = '$PR_NUM'";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$TOT_VOL = $rw_01->TOT_VOL;
			endforeach;
			if($TOT_VOL == '')
				$TOT_VOL= 0;
		
		// TOTAL PR CONFIRMED AND APPROVED
			$TOT_PRQTY		= 0;
			$sqlQTY		= "SELECT SUM(A.PR_VOLM) AS TOT_PRQTY 
							FROM tbl_pr_detail A
								INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
							WHERE 
								B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
								AND B.PR_STAT IN (1,2,3,6)";
			$resQTY 	= $this->db->query($sqlQTY)->result();
			foreach($resQTY as $row1a) :
				$TOT_PRQTY 	= $row1a->TOT_PRQTY;
			endforeach;
			if($TOT_PRQTY == '')
				$TOT_PRQTY	= 0;

			$ITM_VOLMBGN 	= $ITM_VOLMBG - $TOT_VOL - $TOT_PRQTY;

		// INSERT DATA
			$s_02 	= 	"INSERT INTO tbl_pr_detail_tmp
							(PR_ID, PR_NUM, PR_CODE, PRJCODE, DEPCODE, JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC,
							ITM_CODE, ITM_NAME, ITM_UNIT, PR_VOLM, PR_PRICE, PR_TOTAL, PR_DESC,
							ITM_VOLMBG, ITM_BUDG, PR_STAT)
						VALUES
							('$MAX_PRIDN', '$PR_NUM', '$PR_CODE', '$PRJCODE', '$DEPCODE', '$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', '$JOBPARDESC',
							'$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', 0, '$PR_PRICE', 0, '',
							'$ITM_VOLMBG', '$ITM_BUDG', 1)";
			$this->db->query($s_02);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Item $ITM_NAME telah ditambahkan.";
		}
		else
		{
			$alert1	= "Item $ITM_NAME has been added.";
		}
		echo "$alert1";
	}

	function delROW()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PR_NUM		= $colExpl[1];
        $PR_ID		= $colExpl[2];
        $ITM_CODE	= $colExpl[3];
        $ITM_NAME	= $colExpl[4];
        $PRJCODE	= $colExpl[5];

        $s_00 		= "DELETE FROM tbl_pr_detail_tmp WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
        $this->db->query($s_00);

        $s_01 		= "DELETE FROM tbl_pr_detail WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
        $this->db->query($s_01);

        $LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Item $ITM_NAME telah dihapus.";
		}
		else
		{
			$alert1	= "Item $ITM_NAME has been deleted.";
		}
		echo "$alert1";
	}

	function chgVol()
	{
		date_default_timezone_set("Asia/Jakarta");

		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'greaterBud')$greaterBud = $LangTransl;
		endforeach;

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $PR_NUM		= $colExpl[1];
        $PR_ID		= $colExpl[2];
        $ITM_CODE	= $colExpl[3];
        $ITM_PRICE	= $colExpl[4];
        $PRJCODE	= $colExpl[5];
        $REQ_NOW	= $colExpl[6];
        $ITM_BUDGV	= $colExpl[7];
        $ITM_BUDGA	= $colExpl[8];
        $ITM_UNIT	= strtoupper($colExpl[9]);
        $JOBCODEID	= $colExpl[10];

        $TOT_REQNOW = $REQ_NOW * $ITM_PRICE;

        // GET VOL SPP
        	$TOTPRQTY0 		= 0;
        	$TOTPRAMOUNT0 	= 0;
			$s_00 			= "SELECT SUM(PR_VOLM) AS TOTPRQTY0, SUM(PR_TOTAL) AS TOTPRAMOUNT0 FROM tbl_pr_detail
								WHERE ITM_CODE = '$ITM_CODE' AND PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
								UNION
								SELECT SUM(PR_VOLM) AS TOTPRQTY0, SUM(PR_TOTAL) AS TOTPRAMOUNT0 FROM tbl_pr_detail_tmp
								WHERE ITM_CODE = '$ITM_CODE' AND PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$r_00 			= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00):
				$TOTPRQTY0 		= $TOTPRQTY0 + $rw_00->TOTPRQTY0;
				$TOTPRAMOUNT0 	= $TOTPRAMOUNT0 + $rw_00->TOTPRAMOUNT0;
			endforeach;
			if($TOTPRQTY0 == '')
				$TOTPRQTY0		= 0;
			if($TOTPRAMOUNT0== '')
				$TOTPRAMOUNT0 	= 0;

        // GET TOTAL SPP AND REMAIN IN THIS DOCUMENT
        	$TOTPRQTY1 		= 0;
        	$TOTPRAMOUNT1 	= 0;
        	$TOTPRAMOUNT1CNC= 0;
			$s_01 			= "SELECT SUM(PR_VOLM - PR_CVOL) AS TOTPRQTY1, SUM(PR_TOTAL) AS TOTPRAMOUNT1, SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT1CNC
								FROM tbl_pr_detail
								WHERE ITM_CODE = '$ITM_CODE' AND PR_ID != $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
									 AND JOBCODEID = '$JOBCODEID'
								UNION
								SELECT SUM(PR_VOLM) AS TOTPRQTY1, SUM(PR_TOTAL) AS TOTPRAMOUNT1, SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT1CNC
								FROM tbl_pr_detail_tmp
								WHERE ITM_CODE = '$ITM_CODE' AND PR_ID != $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
									 AND JOBCODEID = '$JOBCODEID'";
			$r_01 			= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01):
				$TOTPRQTY1a 	= $rw_01->TOTPRQTY1;
				if($TOTPRQTY1a == '')
					$TOTPRQTY1a = 0;
				$TOTPRAMOUNT1a 	= $rw_01->TOTPRAMOUNT1;
				if($TOTPRAMOUNT1a == '')
					$TOTPRAMOUNT1a = 0;

				$TOTPRQTY1 		= $TOTPRQTY1+$TOTPRQTY1a;
				$TOTPRAMOUNT1 	= $TOTPRAMOUNT1+$TOTPRAMOUNT1a;
			endforeach;
			if($TOTPRQTY1 == '')
				$TOTPRQTY1		= 0;
			if($TOTPRAMOUNT1 == '')
				$TOTPRAMOUNT1 	= 0;
		
		// TOTAL PR CONFIRMED AND APPROVED FROM ANOTHER DOCUMENT
        	$TOTPRQTY2 		= 0;
        	$TOTPRAMOUNT2 	= 0;
        	$TOTPRAMOUNT2CNC= 0;
        	$test 			= 0;
			$sqlQTY			= "SELECT SUM(PR_VOLM - PR_CVOL) AS TOTPRQTY2, SUM((PR_VOLM - PR_CVOL) * PR_PRICE) AS TOTPRAMOUNT2,
									SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT2CNC
								FROM tbl_pr_detail A
									INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
								WHERE 
									B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND A.PR_ID != $PR_ID
									AND A.JOBCODEID = '$JOBCODEID'
									AND B.PR_STAT IN (1,2,3,6) AND A.PR_NUM != '$PR_NUM'";
			$resQTY 		= $this->db->query($sqlQTY)->result();
			foreach($resQTY as $row1a) :
				$TOTPRQTY2a 	= $row1a->TOTPRQTY2;
				if($TOTPRQTY2a == '')
					$TOTPRQTY2a = 0;
				$TOTPRAMOUNT2a 	= $row1a->TOTPRAMOUNT2;
				if($TOTPRAMOUNT2a == '')
					$TOTPRAMOUNT2a = 0;

				$test 			= $TOTPRAMOUNT2a;
				$TOTPRQTY2 		= $TOTPRQTY2+$TOTPRQTY2a;
				$TOTPRAMOUNT2 	= $TOTPRAMOUNT2+$TOTPRAMOUNT2a;
			endforeach;
			if($TOTPRQTY2 == '')
				$TOTPRQTY2	= 0;
			if($TOTPRAMOUNT2 == '')
				$TOTPRAMOUNT2	= 0;

		/*$TOTPRQTY 	= $TOTPRQTY1 + $TOTPRQTY2 - $TOTPRQTY0;
		$TOTPRAMOUNT 	= $TOTPRAMOUNT1 + $TOTPRAMOUNT2 - $TOTPRAMOUNT0;*/
		$TOTPRQTY 		= $TOTPRQTY1 + $TOTPRQTY2;
		$TOTPRAMOUNT 	= $TOTPRAMOUNT1 + $TOTPRAMOUNT2;

		$TOTPRQTY3 		= $TOTPRQTY + $REQ_NOW;
		$TOTPRAMOUNT3 	= $TOTPRAMOUNT + $TOT_REQNOW;

		$s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
		$r_isLS 	= $this->db->count_all($s_isLS);

		//if(($TOTPRQTY3 > $ITM_BUDGV || $TOTPRAMOUNT3 > $ITM_BUDGA) && $ITM_UNIT != 'LS')
		if(($TOTPRQTY3 > $ITM_BUDGV || $TOTPRAMOUNT3 > $ITM_BUDGA) && $r_isLS == 0)
		{
			$REM_VOLPR 	= $ITM_BUDGV - $TOTPRQTY;

			// UPDATE DATA 0
				$s_02 	= 	"UPDATE tbl_pr_detail_tmp SET PR_VOLM = '$REM_VOLPR', PR_TOTAL = PR_PRICE * $REM_VOLPR
								WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_02);

			// UPDATE DATA 1
				$s_03 	= 	"UPDATE tbl_pr_detail SET PR_VOLM = '$REM_VOLPR', PR_TOTAL = PR_PRICE * $REM_VOLPR
								WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_03);

			$alert1	= "$greaterBud ".number_format($REM_VOLPR, 2);
			echo "1~$alert1~$REM_VOLPR~($ITM_BUDGV:$ITM_BUDGA) :: ($TOTPRQTY0:$TOTPRAMOUNT0 -- $TOTPRQTY1:$TOTPRAMOUNT1 -- $TOTPRQTY2:$TOTPRAMOUNT2 -- $TOTPRQTY3:$TOTPRAMOUNT3";
		}
		else
		{
			// UPDATE DATA 0
				$s_02 	= 	"UPDATE tbl_pr_detail_tmp SET PR_VOLM = '$REQ_NOW', PR_TOTAL = PR_PRICE * $REQ_NOW
								WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_02);

			// UPDATE DATA 1
				$s_03 	= 	"UPDATE tbl_pr_detail SET PR_VOLM = '$REQ_NOW', PR_TOTAL = PR_PRICE * $REQ_NOW
								WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_03);

			$alert1 	= "";
			echo "0~$alert1~$REQ_NOW~($ITM_BUDGV:$ITM_BUDGA) :: ($TOTPRQTY0:$TOTPRAMOUNT0 -- $TOTPRQTY1:$TOTPRAMOUNT1 -- $TOTPRQTY2:$TOTPRAMOUNT2 -- $TOTPRQTY3:$TOTPRAMOUNT3";
		}
		//echo "$s_01";
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
			$this->db->trans_begin();
			
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
			$PR_CODE 		= $this->input->post('PR_CODE');
			$PR_STAT 		= $this->input->post('PR_STAT');
			$PR_CATEG		= $this->input->post('PR_CATEG');
			$PR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PR_DATE'))));
			
			$PR_RECEIPTD	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PR_RECEIPTD'))));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE		= $this->input->post('DEPCODE');
			$PR_MEMO		= $this->input->post('PR_MEMO');
			$PATTCODE 		= $this->input->post('PATTCODE');

			$Reference_Number 		= $this->input->post('Reference_Number');
			
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(PR_ID) AS maxNo FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
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
				$PR_ID			= $d['PR_ID'];
				$d['PR_NUM']	= $PR_NUM;
				$d['PR_CODE']	= $PR_CODE;
				$d['DEPCODE']	= $DEPCODE;

				// GET HEADER
					$JOBID 		= $d['JOBCODEID'];
					//echo "JOBID = $JOBID";
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
				$PR_DESCID 			= $d['PR_DESC_ID'];
				if($PR_DESCID == '')
					$d['PR_DESC_ID']= 0;

				$this->db->insert('tbl_pr_detail',$d);

				$s_tmpdel 	= "DELETE FROM tbl_pr_detail_tmp WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_tmpdel);

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$JOBID 		= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$DOC_VOLM	= $d['PR_VOLM'];
					$DOC_TOTAL	= $d['PR_TOTAL'];
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PR_DATE,
										'JOBID'		=> $JOBID,
										'ITM_CODE'	=> $ITM_CODE,
										'DOC_VOLM'	=> $DOC_VOLM,
										'DOC_TOTAL'	=> $DOC_TOTAL,
										'VAR_VOL_R'	=> "PR_VOL_R",
										'VAR_VAL_R'	=> "PR_VAL_R");
					$this->m_updash->updJOBP($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				if($PR_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$PR_VOLM	= $d['PR_VOLM'];
						$PR_PRICE	= $d['PR_PRICE'];
						$PR_VAL 	= $PR_VOLM * $PR_PRICE;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'FVAL'		=> $PR_VAL,
											'FNAME'		=> "PR_VAL");
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			}
			
			$JOBDESC		= '';
			$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBIDH' AND PRJCODE = '$PRJCODE' LIMIT 1";
			$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
			foreach($resJOBDESC as $rowJOBDESC) :
				$JOBDESC	= $rowJOBDESC->JOBDESC;
			endforeach;

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PATTCODE 		= $this->input->post('PATTCODE');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN017',
										'DOCTYPE' 		=> 'PR',
										'DOCNUM' 		=> $PR_NUM,
										'DOCCODE'		=> $PR_CODE,
										'DOCDATE'		=> $PR_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $PR_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE";
				$config['allowed_types'] 	= "jpg|jpeg|png|gif|pdf";
				// $config['max_size'] 		= 5000;
				$config['overwrite'] 		= false;
				
				for($i = 0; $i < $count; $i++) {
					if(!empty($_FILES['userfile']['name'][$i])) {

						$_FILES['userfile']['name']     = $files['userfile']['name'][$i];
						$_FILES['userfile']['type']     = $files['userfile']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
						$_FILES['userfile']['error']    = $files['userfile']['error'][$i];
						$_FILES['userfile']['size']     = $files['userfile']['size'][$i];

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('userfile')) $data[] = $this->upload->data();
						
					} else {
						$data = null;
					}
				}

				if($data != null)
				{
					foreach($data as $upl_data => $file):
						// $data_upload[] 	= $file['file_name'];
						$UPL_NUM 		= "UPL".date('YmdHis');
						$UPL_DATE 		= date('Y-m-d');
						$uplFile = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $PR_NUM, "REF_CODE" => $PR_CODE,
									"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
									"UPL_FILENAME" => $file['file_name'], "UPL_FILESIZE" => $file['file_size'],
									"UPL_FILETYPE" => $file['file_type'], "UPL_FILEPATH" => $file['file_path'], 
									"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
						$this->m_purchase_req->uplDOC_TRX($uplFile);
					endforeach;
		
					// $fileUpload = join(", ", $data_upload);
				}
			
			// ============================= End Upload File ========================================== //

			$projMatReqH 	= array('PR_NUM' 			=> $PR_NUM,
									'PR_CODE' 			=> $PR_CODE,
									'PR_DATE'			=> $PR_DATE,
									'PR_RECEIPTD'		=> $PR_RECEIPTD,
									'PR_REQUESTER'		=> $this->input->post('PR_REQUESTER'),
									'PR_CATEG'			=> $this->input->post('PR_CATEG'),
									'PRJCODE'			=> $PRJCODE,
									'DEPCODE'			=> $DEPCODE,
									'PR_CREATER'		=> $DefEmp_ID,
									'PR_CREATED'		=> $PR_CREATED,
									'PR_NOTE'			=> addslashes($this->input->post('PR_NOTE')),
									'PR_STAT'			=> $this->input->post('PR_STAT'),
									'JOBCODE'			=> $JOBIDH,
									'JOBDESC'			=> $JOBDESC,
									'PR_REFNO'			=> $COLPRREFNO,
									'PR_MEMO'			=> $PR_MEMO,
									'Reference_Number'	=> $Reference_Number,
									'Patt_Year'			=> $Patt_Year,
									'Patt_Month'		=> $Patt_Month,
									'Patt_Date'			=> $Patt_Date,
									'Patt_Number'		=> $Patt_Number);
			$this->m_purchase_req->add($projMatReqH);
			
			// UPDATE DETAIL
				$this->m_purchase_req->updateDet($PR_NUM, $PRJCODE, $PR_DATE);
			
			if($PR_STAT == 2)
			{
				$AS_MNCODE 		= "MN018";
				if($PR_CATEG == 1)
				{
					$data["MenuApp"] 	= 'MN435';
					$AS_MNCODE 			= 'MN435';
				}
				
				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "PR",
										'AS_MNCODE'		=> $AS_MNCODE,
										'AS_DOCNUM'		=> $PR_NUM,
										'AS_DOCCODE'	=> $PR_CODE,
										'AS_DOCDATE'	=> $PR_DATE,
										'AS_EXPDATE'	=> $PR_DATE);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
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
			$data['default']['Reference_Number']= $getpurreq->Reference_Number;
			$data['default']['PR_CATEG']	= $getpurreq->PR_CATEG;
			$PR_CATEG 						= $getpurreq->PR_CATEG;
			if($PR_CATEG == 1)
				$data["MenuApp"] 			= 'MN435';

			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['DEPCODE']				= $getpurreq->DEPCODE;
			$data['default']['DEPCODE']		= $getpurreq->DEPCODE;
			$data['default']['PR_CATEG'] 	= $getpurreq->PR_CATEG;
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
			$this->db->trans_begin();
			
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
			$PR_CODE 		= $this->input->post('PR_CODE');
			$PR_MEMO		= $this->input->post('PR_MEMO');
			$PR_STAT		= $this->input->post('PR_STAT');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$PR_CATEG		= $this->input->post('PR_CATEG');				

			$Reference_Number		= $this->input->post('Reference_Number');

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
			$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBIDH' AND PRJCODE = '$PRJCODE' LIMIT 1";
			$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
			foreach($resJOBDESC as $rowJOBDESC) :
				$JOBDESC	= $rowJOBDESC->JOBDESC;
			endforeach;

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN017',
										'DOCNUM' 		=> $PR_NUM,
										'DOCCODE'		=> $PR_CODE,
										'DOCDATE'		=> $PR_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE";
				$config['allowed_types'] 	= "jpg|jpeg|png|gif|pdf";
				// $config['max_size'] 		= 5000;
				$config['overwrite'] 		= false;
				
				for($i = 0; $i < $count; $i++) {
					if(!empty($_FILES['userfile']['name'][$i])) {

						$_FILES['userfile']['name']     = $files['userfile']['name'][$i];
						$_FILES['userfile']['type']     = $files['userfile']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
						$_FILES['userfile']['error']    = $files['userfile']['error'][$i];
						$_FILES['userfile']['size']     = $files['userfile']['size'][$i];

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('userfile')) $data[] = $this->upload->data();
						
					} else {
						$data = null;
					}
				}

				if($data != null)
				{
					foreach($data as $upl_data => $file):
						// $data_upload[] 	= $file['file_name'];
						$UPL_NUM 		= "UPL".date('YmdHis');
						$UPL_DATE 		= date('Y-m-d');
						$uplFile = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $PR_NUM, "REF_CODE" => $PR_CODE,
									"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
									"UPL_FILENAME" => $file['file_name'], "UPL_FILESIZE" => $file['file_size'],
									"UPL_FILETYPE" => $file['file_type'], "UPL_FILEPATH" => $file['file_path'], 
									"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
						$this->m_purchase_req->uplDOC_TRX($uplFile);
					endforeach;
		
					// $fileUpload = join(", ", $data_upload);
				}
			
			// ============================= End Upload File ========================================== //

			$projMatReqH 	= array('PR_NUM' 		=> $this->input->post('PR_NUM'),
								'PR_CODE' 			=> $this->input->post('PR_CODE'),
								'PR_DATE'			=> $PR_DATE,
								'PR_RECEIPTD'		=> $PR_RECEIPTD,
								'PR_REQUESTER' 		=> $this->input->post('PR_REQUESTER'),
								'PR_CATEG' 			=> $this->input->post('PR_CATEG'),
								'PRJCODE'			=> $PRJCODE,
								'PR_CREATER'		=> $DefEmp_ID,
								'JOBCODE'			=> $JOBIDH,
								'JOBDESC'			=> $JOBDESC,
								'PR_REFNO'			=> $COLPRREFNO,
								'PR_NOTE'			=> addslashes($this->input->post('PR_NOTE')),
								'PR_STAT'			=> $this->input->post('PR_STAT'), 
								'Reference_Number'	=> $Reference_Number,
								'Patt_Year'			=> $Patt_Year, 
								'Patt_Month'		=> $Patt_Month,
								'Patt_Date'			=> $Patt_Date,
								'Patt_Number'		=> $this->input->post('Patt_Number'));										
			$this->m_purchase_req->update($PR_NUM, $projMatReqH);

			if($PR_STAT == 1 || $PR_STAT == 2)
			{
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PR_DATE,
										'DOC_NUM'	=> $PR_NUM,
										'DOC_CATEG'	=> "PR");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

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
					$PR_ID				= $d['PR_ID'];
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
					$PR_DESCID 			= $d['PR_DESC_ID'];
					if($PR_DESCID == '')
						$d['PR_DESC_ID']= 0;
					
					$this->db->insert('tbl_pr_detail',$d);

					$s_tmpdel 	= "DELETE FROM tbl_pr_detail_tmp WHERE PR_ID = $PR_ID AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_tmpdel);

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$JOBID 		= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$DOC_VOLM	= $d['PR_VOLM'];
						$DOC_TOTAL	= $d['PR_TOTAL'];
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'JOBID'		=> $JOBID,
											'ITM_CODE'	=> $ITM_CODE,
											'DOC_VOLM'	=> $DOC_VOLM,
											'DOC_TOTAL'	=> $DOC_TOTAL,
											'VAR_VOL_R'	=> "PR_VOL_R",
											'VAR_VAL_R'	=> "PR_VAL_R");
						$this->m_updash->updJOBP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL

					// START : UPDATE FINANCIAL DASHBOARD
						$PR_VOLM	= $d['PR_VOLM'];
						$PR_PRICE	= $d['PR_PRICE'];
						$PR_VAL 	= $PR_VOLM * $PR_PRICE;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'FVAL'		=> $PR_VAL,
											'FNAME'		=> "PR_VAL");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			}
			elseif($PR_STAT == 4)
			{
				// TIDAK ADA KONDISI REVISI PADA UPDATE
			}
			elseif($PR_STAT == 5)
			{
				// TIDAK ADA KONDISI REJECT PADA UPDATE
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
				// HARUS DILAKUKAN DARI INDEX
				/*$this->m_purchase_req->updREJECT($PR_NUM, $PRJCODE);

				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $PR_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "PR",
											'DOC_QTY' 		=> "DOC_PRQ",
											'DOC_VAL' 		=> "DOC_PRV",
											'DOC_STAT' 		=> 'VOID');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT

				foreach($_POST['data'] as $d)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$PR_VOLM	= $d['PR_VOLM'];
						$PR_PRICE	= $d['PR_PRICE'];
						$PR_VAL_M 	= $PR_VOLM * $PR_PRICE;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'FVAL'		=> $PR_VAL_M,
											'FNAME'		=> "PR_VAL_M");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}*/
			}
			
			if($PR_STAT == 2)
			{
				// START : CREATE ALERT PROCEDURE
					/*$crtAlert 	= array('PRJCODE'		=> $PRJCODE,
										'ALRT_MNCODE'	=> 'MN018',
										'ALRT_CATEG'	=> "PR",
										'ALRT_NUM'		=> $PR_NUM,
										'ALRT_LEV'		=> 0,
										'ALRT_EMP'		=> $DefEmp_ID);										
					$this->m_updash->crtAlert($crtAlert);*/
				// END : CREATE ALERT PROCEDURE

				$AS_MNCODE 		= "MN018";
				if($PR_CATEG == 1)
				{
					$data["MenuApp"] 	= 'MN435';
					$AS_MNCODE 			= 'MN435';
				}

				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "PR",
										'AS_MNCODE'		=> $AS_MNCODE,
										'AS_DOCNUM'		=> $PR_NUM,
										'AS_DOCCODE'	=> $PR_CODE,
										'AS_DOCDATE'	=> $PR_DATE,
										'AS_EXPDATE'	=> $PR_DATE);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
							
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
	
 	function inbox4lt() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_pr180d0c/pR7_l5t_x14lt/?id='.$this->url_encryption_helper->encode_url($appName));
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
	
	function pR7_l5t_x14lt() // G
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
				$mnCode				= 'MN435';
				$data["MenuApp"] 	= 'MN435';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN435';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_pr180d0c/iN20_x14lt/?id=";
			
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
	
	function iN20_x14lt() // OK
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
				$mnCode				= 'MN435';
				$data["MenuApp"] 	= 'MN435';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['title'] 		= $appName;
			$data["MenuCode"] 	= 'MN435';
			$data['addURL'] 	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_pr180d0c/inbox4lt/');
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN435';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_inb4lt', $data);
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
			$query 			= $this->m_purchase_req->get_AllDataL_1n2($PRJCODE, $search, $length,$start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PR_NUM		= $dataI['PR_NUM'];
				$PR_CODE	= $dataI['PR_CODE'];
				$PR_CATEG	= $dataI['PR_CATEG'];
				
				$PR_DATE	= $dataI['PR_DATE'];
				$PR_DATEV	= date('d M Y', strtotime($PR_DATE));
				
				$JOBCODE	= $dataI['JOBCODE'];
				$JOBDESC	= $dataI['JOBDESC'];
				$PR_NOTE	= $dataI['PR_NOTE'];
				//$JOBDESC	= "$JOBDESC - $PR_NOTE";
				$JOBDESC	= "$PR_NOTE";
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
								   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' onClick='pRn_P0l(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
								</label>";

				$MNCODE	= 'MN018';
				if($PR_CATEG == 1)
					$MNCODE	= 'MN435';

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.MENU_CODE = '$MNCODE' AND A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PR_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] = array("$noU.",
										  "<span style='white-space:nowrap'>".$dataI['PR_CODE']."</span>",
										  $PR_DATEV,
										  $JOBDESC,
										  "<span style='white-space:nowrap'>$empName</span>",
										  "<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllData_1n24lt() // GOOD
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
		
		// START : FOR SERVER-SIDE
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataC_1n24lt($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_req->get_AllDataL_1n24lt($PRJCODE, $search, $length,$start, $order, $dir);
								
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
				//$JOBDESC	= "$JOBDESC - $PR_NOTE";
				$JOBDESC	= "$PR_NOTE";
				$PR_STAT	= $dataI['PR_STAT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				//$empName	= cut_text2 ("$CREATERNM", 15);
				$REQUESTER	= $dataI['PR_REQUESTER'];
				$empName	= cut_text2 ("$REQUESTER", 15);
				
				$CollCode	= "$PRJCODE~$PR_NUM";
				$secUpd		= site_url('c_purchase/c_pr180d0c/update_inb4lt/?id='.$this->url_encryption_helper->encode_url($CollCode));
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
								   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Tampilkan OP' onClick='pRn_P0l(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
								</label>";
				
				$MNCODE	= 'MN435';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.MENU_CODE = '$MNCODE' AND A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PR_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] = array("$noU.",
										  "<span style='white-space:nowrap'>".$dataI['PR_CODE']."</span>",
										  $PR_DATEV,
										  $JOBDESC,
										  "<span style='white-space:nowrap'>$empName</span>",
										  "<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
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
			$data['default']['PR_CATEG'] 	= $getpurreq->PR_CATEG;
			$data['default']['Reference_Number']= $getpurreq->Reference_Number;
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
	
	function update_inb4lt()
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
			$mnCode				= 'MN435';
			$data["MenuApp"] 	= 'MN435';
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
			$data['default']['PR_CATEG'] 	= $getpurreq->PR_CATEG;
			$data['default']['Reference_Number']= $getpurreq->Reference_Number;
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
			
			$MenuCode 			= 'MN435';
			$data["MenuCode"] 	= 'MN435';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getpurreq->PR_NUM;
				$MenuCode 		= 'MN435';
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
			
			$this->load->view('v_purchase/v_purchase_req/purchase_req_inb4lt_form', $data);
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
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$PR_APPROVED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PR_STAT 		= $this->input->post('PR_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_CODE 		= $this->input->post('PR_CODE');
			$PR_CATEG 		= $this->input->post('PR_CATEG');
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
				$s_UPD 	= "UPDATE tbl_dash_transac_emp SET TOT_REQ_W = TOT_REQ_W-1 WHERE PRJ_CODE = '$PRJCODE' AND EMP_ID = '$DefEmp_ID'";
				$this->db->query($s_UPD);

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
					/*$crtAlert 	= array('PRJCODE'		=> $PRJCODE,
										'ALRT_MNCODE'	=> 'MN018',
										'ALRT_CATEG'	=> "PR",
										'ALRT_NUM'		=> $PR_NUM,
										'ALRT_LEV'		=> $AH_APPLEV,
										'ALRT_EMP'		=> $AH_APPROVER);										
					$this->m_updash->crtAlert($crtAlert);*/
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

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'DOC_NUM'	=> $PR_NUM,
											'DOC_CATEG'	=> "PR");
						$this->m_updash->updJOBPAPP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
					
					// UPDATE PEKERJAAN - SUDAH PADA FUNCTION updJOBPAPP
						//$this->m_purchase_req->updateJobDet($PR_NUM, $PRJCODE); // UPDATE JOBD DETAIL DAN PR

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
				
				/*if($AH_ISLAST == 0)
				{*/
					// START : CREATE ALERT LIST
						$APP_LEVEL 	= $this->input->post('APP_LEVEL');
						$alertVar 	= array('PRJCODE'		=> $PRJCODE,
											'AS_CATEG'		=> "PR",
											'AS_MNCODE'		=> "MN018",
											'AS_DOCNUM'		=> $PR_NUM,
											'AS_DOCCODE'	=> $PR_CODE,
											'AS_DOCDATE'	=> $PR_DATE,
											'AS_EXPDATE'	=> $PR_DATE,
											'APP_LEVEL'		=> $APP_LEVEL,
											'C_APPROVER'	=> $DefEmp_ID);
						$this->m_updash->updAALERT($alertVar);
					// END : CREATE ALERT LIST
				//}
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

				foreach($_POST['data'] as $d)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$PR_VOLM	= $d['PR_VOLM'];
						$PR_PRICE	= $d['PR_PRICE'];
						$PR_VAL_M 	= $PR_VOLM * $PR_PRICE;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'FVAL'		=> $PR_VAL_M,
											'FNAME'		=> "PR_VAL_M");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
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
				// END : UPDATE TO TRANS-COUNT

				foreach($_POST['data'] as $d)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$PR_VOLM	= $d['PR_VOLM'];
						$PR_PRICE	= $d['PR_PRICE'];
						$PR_VAL_M 	= $PR_VOLM * $PR_PRICE;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PR_DATE,
											'FVAL'		=> $PR_VAL_M,
											'FNAME'		=> "PR_VAL_M");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
				
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PR_DATE,
										'DOC_NUM'	=> $PR_NUM,
										'DOC_CATEG'	=> "PR");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			if($PR_CATEG == 0)
				$url			= site_url('c_purchase/c_pr180d0c/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			else
				$url			= site_url('c_purchase/c_pr180d0c/iN20_x14lt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

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

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

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
					$s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
					$r_isLS 	= $this->db->count_all($s_isLS);

					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
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
					$s_isLS 		= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
					$r_isLS 		= $this->db->count_all($s_isLS);
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
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
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
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

  	function get_AllDataITMM() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$PR_NUM		= $_GET['PRNUM'];
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
				// $ADDM_VOLM 	= $dataI['ADDM_VOLM'];
				// $ADDM_JOBCOST= $dataI['ADDM_JOBCOST'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_BUDGVOL 	= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_BUDGVAL	= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_TYPE, ITM_UNIT
										FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE' 
											AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;			// 5
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
					endforeach;

				$JOBUNIT 		= strtoupper($ITM_UNIT);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';
				$JOBLEV			= $dataI['IS_LEVEL'];

		        // GET TOTAL SPP AND REMAIN IN THIS DOCUMENT
					// UNTUK SPP YANG DIKOREKSI HANYALAH VOLUME, KECUALI UNTUK GROUP LS (LS, BLN, LOT, DLL) DAN UNTUK NILAI DIAMBIL DARI PO
					// SEHINGGA DARI PR_TOTAL MENJADI PO_AMOUNT
		        	$TOT_PRVOL 		= 0;
		        	$TOT_PRAMN 		= 0;
		        	$TOT_PRAMNCNC	= 0;
					/*$s_01 			= "SELECT SUM(PR_VOLM - PR_CVOL) AS TOT_PRVOL, SUM(PR_TOTAL) AS TOT_PRAMN, SUM(PR_CVOL * PR_PRICE) AS TOT_PRAMNCNC
										FROM tbl_pr_detail
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND PR_STAT IN (1,2,3,4,6)
										UNION
										SELECT SUM(PR_VOLM) AS TOT_PRVOL, SUM(PR_TOTAL) AS TOT_PRAMN, SUM(PR_CVOL * PR_PRICE) AS TOT_PRAMNCNC
										FROM tbl_pr_detail_tmp
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";*/
					$s_01 			= "SELECT SUM(PR_VOLM - PR_CVOL) AS TOT_PRVOL, SUM(PO_AMOUNT) AS TOT_PRAMN, SUM(0) AS TOT_PRAMNCNC
										FROM tbl_pr_detail
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND PR_STAT IN (1,2,3,4,6)
										UNION
										SELECT SUM(PR_VOLM) AS TOT_PRVOL, SUM(PO_AMOUNT) AS TOT_PRAMN, SUM(0) AS TOT_PRAMNCNC
										FROM tbl_pr_detail_tmp
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOT_PRVOLa 	= $rw_01->TOT_PRVOL;
						if($TOT_PRVOLa == '')
							$TOT_PRVOLa = 0;
						$TOT_PRAMNa 	= $rw_01->TOT_PRAMN;
						if($TOT_PRAMNa == '')
							$TOT_PRAMNa = 0;

						$TOT_PRVOL 		= $TOT_PRVOL+$TOT_PRVOLa;
						$TOT_PRAMN 		= $TOT_PRAMN+$TOT_PRAMNa;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL		= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN 		= 0;

				$TOT_REQVOL 	= $TOT_PRVOL;
				$TOT_REQVAL 	= $TOT_PRAMN;

				$s_isLS 		= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
				$r_isLS 		= $this->db->count_all($s_isLS);
				if($r_isLS == 1 && $MAX_REQVAL > 0)
				{
					$MAX_REQVOL		= $TOT_BUDGVOL - $TOT_REQVOL;
					$MAX_REQVAL		= $TOT_BUDGVAL - $TOT_REQVAL;
					$MAX_REQPR 		= round($TOT_BUDGVAL - $TOT_REQVAL,2);

					$disabledB		= 0;
					/*if($MAX_REQVOL <= 0 && $MAX_REQVAL <= 0)*/
					if($MAX_REQPR <= 0)
						$disabledB	= 1;
				}
				else
				{
					$MAX_REQVOL		= $TOT_BUDGVOL - $TOT_REQVOL;
					$MAX_REQVAL		= $TOT_BUDGVAL - $TOT_REQVAL;
					$MAX_REQPR 		= round($TOT_BUDGVOL - $TOT_REQVOL,2);
				
					$disabledB		= 0;
					/*if($MAX_REQVOL <= 0 && $MAX_REQVAL <= 0)*/
					if($MAX_REQPR <= 0)
						$disabledB	= 1;
				}
			
				if($ITM_TYPE == 'SUBS')
				{
					$disabledB	= 0;															
				}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQVOLV 	= "";
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
						$JOBVOLMV	= number_format($TOT_BUDGVOL, 2);
						$TOT_REQVOLV= number_format($TOT_REQVOL, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($MAX_REQVOL, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($MAX_REQVOL, 2)."</span>";
					}

				$JOBPARDESC	= 	"";
				$sqlJPAR	= 	"SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
		        $resJPAR	= $this->db->query($sqlJPAR)->result();
		        foreach($resJPAR as $rowJPAR) :
		            $JOBPARDESC	= $rowJPAR->JOBDESC;
		        endforeach;
				$JOBDESCH 	= $JOBDESC1;
				$strLENH 	= strlen($JOBDESCH);
				$JOBDESCHA	= wordwrap($JOBDESCH, 50, "<br>", TRUE);
				$JOBDESCH 	= $JOBDESCHA;
				if($strLENH > 50)
					$JOBDESCH 	= $JOBDESCHA;

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".number_format($ITM_PRICE, 2, '.', '')."|".number_format($TOT_BUDGVOL, 2, '.', '')."|".number_format($ITM_STOCK, 2, '.', '')."|".number_format($ITM_USED, 2, '.', '')."|".$itemConvertion."|".$TOT_BUDGVAL."|".$MAX_REQVOL."|".$PO_AMOUNT."|".$TOT_BUDGVOL."|".$TOT_REQVOL."|".$ITM_TYPE."|".$JOBDESCH."' onClick='pickThis1(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_BUDGVOL."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_BUDGVAL."|".$MAX_REQVOL."|".$PO_AMOUNT."|".$TOT_BUDGVOL."|".$TOT_REQVOL."|".$ITM_TYPE."' style='display: none' />";
					}

					$JobView		= "$JOBPARENT : $JOBPARDESC";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				if($r_isLS == 1 && $MAX_REQVAL > 0)
				{
					/*$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
												"<div>
											  		<p><span ".$CELL_COL.">".$JobView."</span></p>
											  	</div>
											  	<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;$JOBPARENT : ".$JOBDESCH."
											  	</div>",
												"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQVOLV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
												"<div style='text-align:right;'>".$statRem."</div>");

					$noU			= $noU + 1;*/
				}
				elseif($r_isLS == 0 && ($MAX_REQVOL > 0 && $MAX_REQVAL > 0))
				{
					/*$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
												"<div>
											  		<p><span ".$CELL_COL.">".$JobView."</span></p>
											  	</div>
											  	<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;$JOBPARENT : ".$JOBDESCH."
											  	</div>",
												"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQVOLV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
												"<div style='text-align:right;'>".$statRem."</div>");

					$noU			= $noU + 1;*/
				}
				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div>
										  		<p><span ".$CELL_COL.">$JOBCODEID : $JOBDESC1 ($ITM_CODE)</span></p>
										  	</div>
										  	<div style='font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;$JobView
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQVOLV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
											"<div style='text-align:right;'>".$statRem."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataITMS() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

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
					$s_isLS 		= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
					$r_isLS 		= $this->db->count_all($s_isLS);
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
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
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
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
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
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

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

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
					/*$sqlITMVC		= "SELECT SUM(A.PO_VOL) AS TREQ_VOL, SUM(A.PO_VAL) AS TREQ_VAL, SUM(A.PO_VOL_R) AS TREQ_VOL_R, SUM(A.PO_VAL_R) AS TREQ_VAL_R, 
											SUM(A.PO_CVOL) AS TREQ_VOL_C, SUM(A.PO_CVAL) AS TREQ_VAL_C 
										FROM tbl_pr_detail A
										WHERE A.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.PR_STAT IN (2,3,6)";
					$resITMVC		= $this->db->query($sqlITMVC)->result();
					foreach($resITMVC as $rowITMVC) :
						$TREQ_VOL	= $rowITMVC->TREQ_VOL;
						$TREQ_VAL	= $rowITMVC->TREQ_VAL;
						$TREQ_VOL_R	= $rowITMVC->TREQ_VOL_R;
						$TREQ_VAL_R	= $rowITMVC->TREQ_VAL_R;
						$TREQ_VOL_C	= $rowITMVC->TREQ_VOL_C;
						$TREQ_VAL_C	= $rowITMVC->TREQ_VAL_C;
						$TOT_PRVOL 	= $TREQ_VOL+$TREQ_VOL_R-$TREQ_VOL_C;
						$TOT_PRAMN 	= $TREQ_VAL+$TREQ_VAL_R-$TREQ_VAL_C;
					endforeach;*/
					$sqlITMVC		= "SELECT SUM(A.PR_VOLM-A.PR_CVOL) AS TOT_REQ, 
											SUM(A.PR_TOTAL-A.PR_CTOTAL) AS TOT_REQAM 
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
					$sqlITMVC		= "SELECT SUM(A.PO_VOLM-A.PO_CVOL) AS TOT_POVOL, 
											SUM(A.PO_COST-A.PO_CTOTAL) AS TOT_POAMN 
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
					$s_isLS 		= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
					$r_isLS 		= $this->db->count_all($s_isLS);
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
					{
						/*$TREQ_VOL = $TOT_PRAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TREQ_VOL;		// 14*/
						$TREQ_VOL 	= $TOT_POVOL;
						$TREQ_VAL 	= $TOT_POAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TREQ_VAL;		// 14
						$PO_VOLM	= $TOT_POVOL;
						$PO_VOLM	= $TOT_POAMN;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						/*$TREQ_VOL 	= $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TREQ_VOL;			// 14*/
						$TREQ_VOL 	= $TOT_PRVOL;
						$TREQ_VAL 	= $TOT_PRAMN;
						$MAX_REQ	= $TOT_VOLMBG - $TREQ_VOL;			// 14
						$PO_VOLM	= $TOT_POVOL;
						$PO_VOLM	= $TOT_POAMN;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TREQ_VOL;
					$REMREQ_AMN		= $TOT_AMOUNTBG - $TREQ_VAL;
					
					$disabledB		= 0;
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
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
						$TREQ_VOLV	= number_format(0, 2);
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
						$TREQ_VOLV	= number_format($TREQ_VOL, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($MAX_REQ, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($MAX_REQ, 2)."</span>";
					}

				// OTHER SETT
					if($disabledB == 0)
						$chkBox		= "<input type='radio' name='chkRad' value='".$PRJCODE."|".$ITM_CODE."' onClick='pickThis3(this);'/>";
					else
						$chkBox		= "<input type='radio' name='chkRad' value='' style='display: none' />";

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
									  		<div style='margin-left: 17px; font-style: italic;'>$BudgetQty : ".$JOBVOLMV." $Requested : ".$TREQ_VOLV."
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
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

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
					$TOT_PRVOL1		= 0;
					$TOT_PRAMN1		= 0;
					$sqlITMVC1		= "SELECT SUM(A.PR_VOLM-A.PR_CVOL) AS TOT_REQ, 
											SUM(A.PR_TOTAL-A.PR_CTOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT IN (2,3,6)";
					$resITMVC1		= $this->db->query($sqlITMVC1)->result();
					foreach($resITMVC1 as $rowITMVC1) :
						$TOT_PRVOL1	= $rowITMVC1->TOT_REQ;
						$TOT_PRAMN1	= $rowITMVC1->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL1 == '')
						$TOT_PRVOL1	= 0;
					if($TOT_PRAMN1 == '')
						$TOT_PRAMN1	= 0;

					$TOT_PRVOL2		= 0;
					$TOT_PRAMN2		= 0;
					$sqlITMVC2		= "SELECT SUM(A.PR_VOLM-A.PR_CVOL) AS TOT_REQ, 
											SUM(A.PR_TOTAL-A.PR_CTOTAL) AS TOT_REQAM 
										FROM tbl_pr_detail_tmp A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT IN (2,3,6)";
					$resITMVC2		= $this->db->query($sqlITMVC2)->result();
					foreach($resITMVC2 as $rowITMVC2) :
						$TOT_PRVOL2	= $rowITMVC2->TOT_REQ;
						$TOT_PRAMN2	= $rowITMVC2->TOT_REQAM;
					endforeach;
					if($TOT_PRVOL2 == '')
						$TOT_PRVOL2	= 0;
					if($TOT_PRAMN2 == '')
						$TOT_PRAMN2	= 0;

					$TOT_PRVOL	= $TOT_PRVOL1+$TOT_PRVOL2;
					$TOT_PRAMN	= $TOT_PRAMN1+$TOT_PRAMN2;

				// GET PO DETAIL
					$TOT_POVOL		= 0;
					$TOT_POAMN		= 0;
					$sqlITMVC		= "SELECT SUM(A.PO_VOLM-A.PO_CVOL) AS TOT_POVOL, 
											SUM(A.PO_COST-A.PO_CTOTAL) AS TOT_POAMN 
										FROM tbl_po_detail A
										WHERE A.PRJCODE = '$PRJCODE'
											AND A.ITM_CODE = '$ITM_CODE'
											AND A.JOBCODEID = '$JOBCODEID'
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
					$s_isLS 		= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
					$r_isLS 		= $this->db->count_all($s_isLS);
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
					{
						/*$TREQ_VOL = $TOT_PRAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TREQ_VOL;		// 14*/
						$TREQ_VOL 	= $TOT_POVOL;
						$TREQ_VAL 	= $TOT_POAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TREQ_VAL;		// 14
						$PO_VOLM	= $TOT_POVOL;
						$PO_VOLM	= $TOT_POAMN;
						$TOT_BUDG	= $TOT_AMOUNTBG;
						$TOT_REQ 	= $TOT_POAMN;
					}
					else
					{
						/*$TREQ_VOL 	= $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TREQ_VOL;			// 14*/
						$TREQ_VOL 	= $TOT_PRVOL;
						$TREQ_VAL 	= $TOT_PRAMN;
						$MAX_REQ	= $TOT_VOLMBG - $TREQ_VOL;			// 14
						$PO_VOLM	= $TOT_POVOL;
						$PO_VOLM	= $TOT_POAMN;
						$TOT_BUDG	= $TOT_VOLMBG;
						$TOT_REQ 	= $TOT_PRVOL;
					}
				
					$tempTotMax		= $MAX_REQ;

					//$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ;
					//$REMREQ_AMN		= $TOT_AMOUNTBG - $TOT_BUDG;
					
					$disabledB		= 0;
					//if($JOBUNIT == 'LS')
					if($r_isLS == 1)
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
						$TOT_BUDGV 	= "";
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
						$TOT_BUDGV	= number_format($TOT_BUDG, 2);
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
						$chkBox		= "<input type='checkbox' name='chk4' id='chk4".$noU."' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_BUDG."|".$ITM_TYPE."' onClick='pickThis4(this, ".$noU.");' $chcked />";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk4' value='' style='display: none' />";
					}

					$JOBDESCH		= "";
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
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
									  		<div style='margin-left: 17px; font-style: italic;'>$BudgetQty : $TOT_BUDGV $Requested : $TOT_REQV</div>",
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

  	function get_AllDataITMO() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$PR_NUM		= $_GET['PRNUM'];

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
			$num_rows 		= $this->m_purchase_req->get_AllDataITMOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataITMOL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				// $ADDM_VOLM 	= $dataI['ADDM_VOLM'];
				// $ADDM_JOBCOST= $dataI['ADDM_JOBCOST'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_BUDGVOL 		= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_BUDGVAL		= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_CODE_H, ITM_TYPE, ITM_UNIT
										FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE' 
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

		        // GET TOTAL SPP AND REMAIN IN THIS DOCUMENT
		        	$TOT_PRVOL 		= 0;
		        	$TOT_PRAMN 		= 0;
		        	$TOT_PRAMNCNC	= 0;
					/*$s_01 			= "SELECT SUM(PR_VOLM - PR_CVOL) AS TOT_PRVOL, SUM(PR_TOTAL) AS TOT_PRAMN, SUM(PR_CVOL * PR_PRICE) AS TOT_PRAMNCNC
										FROM tbl_pr_detail
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND PR_STAT IN (1,2,3,4,6)
										UNION
										SELECT SUM(PR_VOLM) AS TOT_PRVOL, SUM(PR_TOTAL) AS TOT_PRAMN, SUM(PR_CVOL * PR_PRICE) AS TOT_PRAMNCNC
										FROM tbl_pr_detail_tmp
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";*/

					$s_01 			= "SELECT SUM(PO_VOLM - PO_CVOL) AS TOT_PRVOL, SUM(PO_COST) AS TOT_PRAMN, SUM(PO_CVOL * PO_PRICE) AS TOT_PRAMNCNC
										FROM tbl_po_detail
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
											AND PRJCODE = '$PRJCODE' AND PO_STAT IN (1,2,3,4,6)";
					$r_01 			= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01):
						$TOT_PRVOLa 	= $rw_01->TOT_PRVOL;
						if($TOT_PRVOLa == '')
							$TOT_PRVOLa = 0;
						$TOT_PRAMNa 	= $rw_01->TOT_PRAMN;
						if($TOT_PRAMNa == '')
							$TOT_PRAMNa = 0;

						$TOT_PRVOL 		= $TOT_PRVOL+$TOT_PRVOLa;
						$TOT_PRAMN 		= $TOT_PRAMN+$TOT_PRAMNa;
					endforeach;
					if($TOT_PRVOL == '')
						$TOT_PRVOL		= 0;
					if($TOT_PRAMN == '')
						$TOT_PRAMN 		= 0;

				$TOT_REQVOL 	= $TOT_PRVOL;
				$TOT_REQVAL 	= $TOT_PRAMN;
				$MAX_REQVOL		= $TOT_BUDGVOL - $TOT_REQVOL;
				$MAX_REQVAL		= $TOT_BUDGVAL - $TOT_REQVAL;

                $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$r_isLS 	= $this->db->count_all($s_isLS);
				if($r_isLS == 1)
				{
					$MAX_REQVOL	= $TOT_BUDGVOL;
					$MAX_REQVAL	= $TOT_BUDGVAL;
				}

				$disabledB		= 0;
				if($MAX_REQVOL <= 0)
					$disabledB	= 1;

				// START : LS PROCEDURE 1
					/*if($JOBUNIT == 'LS')
					{
						$TOT_REQVOL 	= $TOT_PRAMN;
						$MAX_REQVOL	= $TOT_BUDGVAL - $TOT_REQVOL;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDGVOL	= $TOT_BUDGVAL;				// not used in form
						
						if($MAX_REQVAL <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_BUDGVOL		= $TOT_BUDGVAL;
					}
					else
					{
						$TOT_REQVOL 	= $TOT_PRVOL;
						$MAX_REQVOL	= $TOT_BUDGVOL - $TOT_REQVOL;		// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDGVOL= $TOT_BUDGVOL;					// not used in form

						if($MAX_REQVOL <= 0)
							$disabledB	= 1;
					}*/
				// END : LS PROCEDURE 1
			
				if($ITM_TYPE == 'SUBS')
				{
					$disabledB	= 0;															
				}

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						//$disabledB	= 1;
						$JOBDESC1 	= $JOBDESC;
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
						$statRem 	= "";
						$JOBUNITV 	= "";
						$JOBVOLMV 	= "";
						$TOT_REQVOLV 	= "";
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
						$JOBVOLMV	= number_format($TOT_BUDGVOL, 2);
						$TOT_REQVOLV	= number_format($TOT_REQVOL, 2);
						$PO_VOLMV	= number_format($PO_VOLM, 2);
						if($disabledB == 0)
							$statRem = number_format($MAX_REQVOL, 2);
						else
							$statRem = "<span class='label label-danger' style='font-size:12px'>".number_format($MAX_REQVOL, 2)."</span>";
					}

				$JOBDESCH		= "";
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
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
						$chkBox		= "<input type='checkbox' name='chkO' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".number_format($ITM_PRICE, 2, '.', '')."|".number_format($TOT_BUDGVOL, 2, '.', '')."|".number_format($ITM_STOCK, 2, '.', '')."|".number_format($ITM_USED, 2, '.', '')."|".$itemConvertion."|".$TOT_BUDGVAL."|".$MAX_REQVOL."|".$PO_AMOUNT."|".$TOT_BUDGVOL."|".$TOT_REQVOL."|".$ITM_TYPE."|".$JOBDESCH."' onClick='pickThisO(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_BUDGVOL."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_BUDGVAL."|".$MAX_REQVOL."|".$PO_AMOUNT."|".$TOT_BUDGVOL."|".$TOT_REQVOL."|".$ITM_TYPE."' style='display: none' />";
					}

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";
					//$JobView		= "$JOBCODEID - $JOBDESC1";
					$JobView		= "$ITM_CODE - $ITM_NAME";

					$ADDVOL_VW 		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
					}

				//if(($MAX_REQVOL > 0 && $MAX_REQVAL > 0) && ($ITM_UNIT != 'LS' || $ITM_UNIT != 'BLN'))
				// BELUM MENGOREKSI TOTAL NILAI, HANYA VOLUME KECUALI LS DAN BLN DAN LOT
				$s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$JOBUNIT'";
				$r_isLS 	= $this->db->count_all($s_isLS);
				if($r_isLS == 0)
				{
					//$chkBox		= "<input type='checkbox' name='chkO' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".number_format($ITM_PRICE, 2, '.', '')."|".number_format($TOT_BUDGVOL, 2, '.', '')."|".number_format($ITM_STOCK, 2, '.', '')."|".number_format($ITM_USED, 2, '.', '')."|".$itemConvertion."|".$TOT_BUDGVAL."|".$MAX_REQVOL."|".$PO_AMOUNT."|".$TOT_BUDGVOL."|".$TOT_REQVOL."|".$ITM_TYPE."|".$JOBDESCH."' onClick='pickThisO(this);'/>";
					$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
												"<div>
											  		<p><span ".$CELL_COL.">".$JobView."</span></p>
											  	</div>
											  	<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;$JOBPARENT : ".$JOBDESCH."
											  	</div>",
												"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQVOLV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
												"<div style='text-align:right;'>".$statRem."</div>");

					$noU			= $noU + 1;
				}
				else
				{
					$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
												"<div>
											  		<p><span ".$CELL_COL.">".$JobView."</span></p>
											  	</div>
											  	<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;$JOBPARENT : ".$JOBDESCH."
											  	</div>",
												"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$JOBVOLMV.$ADDVOL_VW."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$TOT_REQVOLV."</span></div>",
												"<div style='text-align:right;'><span ".$CELL_COL.">".$PO_VOLMV."</span></div>",
												"<div style='text-align:right;'>".$statRem."</div>");

					$noU			= $noU + 1;
				}
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function addItmTmp()
	{
		//A.03.02-1|A.03.02-1||531080|D010001001|Meja kayu Ukuran : 1/2 Biro||Unit|350000|14|0|0|1|4900000|2|3500000|14|12|PRM|Direksi Keet (Swakelola)

		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$strItem 	= $_POST['collDt'];
		$arrItem 	= explode("|", $strItem);

		$JOBCODEDET 	= $arrItem[0];
		$JOBCODEID 		= $arrItem[1];
		$JOBCODE 		= $arrItem[2];
		$PRJCODE 		= $arrItem[3];
		$ITM_CODE 		= $arrItem[4];
		$ITM_NAME 		= $arrItem[5];
		$ITM_SN			= $arrItem[6];
		$ITM_UNIT 		= $arrItem[7];
		$ITM_PRICE 		= $arrItem[8];
		$ITM_VOLM 		= $arrItem[9];
		$ITM_VOLMBG		= $ITM_VOLM; 
		$ITM_STOCK 		= $arrItem[10];
		$ITM_USED 		= $arrItem[11];
		$itemConvertion	= $arrItem[12];
		$TotPrice		= $arrItem[13];
		$tempTotMax		= $arrItem[14];
		$TOT_USEBUDG	= $arrItem[15];
		//ITM_BUDG		= $arrItem[16];
		$ITM_BUDG		= $TotPrice;
		$TOT_USEDQTY	= $arrItem[17];
		$ITM_TYPE		= $arrItem[18];
		$JOBDESCH 		= $arrItem[19];
		$PR_NUM 		= $arrItem[20];
		$PR_CODE 		= $arrItem[21];
		$REQ_NOW 		= $arrItem[22];			// REQUEST PADA BARIS SEBELUMNYA

		$s_00 			= "SELECT MAX(PR_ID) AS PR_IDMAX FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_00 			= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
            $PR_IDMAX	= $rw_00->PR_IDMAX;
        endforeach;
        if($PR_IDMAX == '')
        	$PR_IDMAX 	= 0;

		$s_00a 			= "tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_00a 			= $this->db->count_all($s_00a);

		$PR_ID 			= $PR_IDMAX + $r_00a + 1;

		// TOTAL YANG DIREQUEST ADALAH TOTAL YANG SUDAH DI PR (1,2,4) + TEMP
			$TOT_PR 	= 0;
			$s_PRT 		= "SELECT SUM(REQ_VOLM) AS TOT_PR
							FROM tbl_joblist_detail
							WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
							UNION
							SELECT SUM(PR_VOLM) AS TOT_PR FROM tbl_pr_detail WHERE PR_STAT IN (1,2,4) AND JOBCODEID = '$JOBCODEID'
							AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$r_PRT 		= $this->db->query($s_PRT)->result();
			foreach($r_PRT as $rw_PRT):
				$TOT_PRX 	= $rw_PRT->TOT_PR;
				$TOT_PR 	= $TOT_PR+$TOT_PRX;
			endforeach;
			if($TOT_PR == '')
				$TOT_PR = 0;

			$TOT_TMPPR 	= 0;		// TOTAL TEMPORARY
			$s_TMPPRT 	= "SELECT SUM(PR_VOLM) AS TOT_PR FROM tbl_pr_detail_tmp WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
							AND PRJCODE = '$PRJCODE' AND PR_ID != $PR_ID";
			$r_TMPPRT 	= $this->db->query($s_TMPPRT)->result();
			foreach($r_TMPPRT as $rw_TMPPRT):
				$TOT_TMPPR = $rw_TMPPRT->TOT_PR;
			endforeach;
			if($TOT_TMPPR == '')
				$TOT_TMPPR = 0;

			$TOT_ITMPR 	= $TOT_PR+$TOT_TMPPR;
			$TOT_ITMREM = doubleval($ITM_VOLMBG)-doubleval($TOT_ITMPR);
			$PR_TOTAL 	= $TOT_ITMREM*$ITM_PRICE;

		$JOBPARENT 	= 	"";
		$sqlJPAR	= 	"SELECT A.JOBPARENT, A.JOBDESC FROM tbl_joblist_detail A 
							WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
								WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')";
        $resJPAR	= $this->db->query($sqlJPAR)->result();
        foreach($resJPAR as $rowJPAR) :
            $JOBPARENT	= $rowJPAR->JOBPARENT;
            $JOBPARDESC	= $rowJPAR->JOBDESC;
        endforeach;

		$s_00 	= 	"INSERT INTO tbl_pr_detail_tmp
						(PR_ID, PR_NUM, PR_CODE, PRJCODE, DEPCODE, JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC,
						ITM_CODE, ITM_NAME, ITM_UNIT, PR_VOLM, PR_PRICE, PR_TOTAL, PR_DESC,
						ITM_VOLMBG, ITM_BUDG, PR_STAT)
						VALUES
						('$PR_ID', '$PR_NUM', '$PR_CODE', '$PRJCODE', '$DEPCODE', '$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', '$JOBPARDESC',
						'$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$TOT_ITMREM', '$ITM_PRICE', '$PR_TOTAL', '',
						'$ITM_VOLMBG', '$ITM_BUDG', 1)";
		$this->db->query($s_00);

		$TOT_ROW 	= 0;
		$s_TROW 	= "SELECT COUNT(*) AS TOT_ROW FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_TROW 	= $this->db->query($s_TROW)->result();
		foreach($r_TROW as $rw_TROW):
			$TOT_ROW = $rw_TROW->TOT_ROW;
		endforeach;
		echo $TOT_ROW;
	}

  	function get_AllDataITMSCUT4SV() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$myarr		= explode("~", $_POST['collDt']);
		$PR_NUM 	= $myarr[0];
		$PR_CODE 	= $myarr[1];
		$PRJCODE 	= $myarr[2];
		$chkVal 	= $myarr[3];
		$REQ_NOW 	= $myarr[4];
		$STATUS 	= $myarr[5];
		$DEPCODE	= $this->session->userdata['DEPCODE'];

		$arrItem		= explode("|", $chkVal);
		$JOBCODEDET 	= $arrItem[0];
		$JOBCODEID 		= $arrItem[1];
		$JOBCODE 		= $arrItem[2];
		$PRJCODE 		= $arrItem[3];
		$ITM_CODE 		= $arrItem[4];
		$ITM_NAME 		= $arrItem[5];
		$ITM_SN			= $arrItem[6];
		$ITM_UNIT 		= $arrItem[7];
		$ITM_PRICE 		= $arrItem[8];
		$ITM_VOLM 		= $arrItem[9];
		$ITM_VOLMBG		= $ITM_VOLM; 
		$ITM_STOCK 		= $arrItem[10];
		$ITM_USED 		= $arrItem[11];
		$itemConvertion	= $arrItem[12];
		$TotPrice		= $arrItem[13];
		$tempTotMax		= $arrItem[14];
		$TOT_USEBUDG	= $arrItem[15];
		//ITM_BUDG		= $arrItem[16];
		$ITM_BUDG		= $TotPrice;
		$TOT_USEDQTY	= $arrItem[17];
		$ITM_TYPE		= $arrItem[18];

		/*if($STATUS == '1')
		{
			$s_01 			= "INSERT INTO tbl_pr_detail_tmp (PR_NUM, PRJCODE, JOBCODEID, ITM_CODE, CREATED)
								VALUES ('$PR_NUM', '$PRJCODE', '$JOBCODEID', '$ITM_CODE', '$DefEmp_ID')";
			$this->db->query($s_01);
		}
		else
		{
			$s_01 			= "DELETE FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'
								AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($s_01);
		}*/

		// TOTAL YANG DIREQUEST ADALAH TOTAL YANG SUDAH DI PR (1,2,4) + TEMP
			$TOT_PR 	= 0;
			$s_PRT 		= "SELECT SUM(REQ_VOLM) AS TOT_PR
							FROM tbl_joblist_detail
							WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
							UNION
							SELECT SUM(PR_VOLM) AS TOT_PR FROM tbl_pr_detail WHERE WHERE PR_STAT IN (1,2,4) AND JOBCODEID = '$JOBCODEID'
							AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$r_PRT 		= $this->db->query($s_PRT)->result();
			foreach($r_PRT as $rw_PRT):
				$TOT_PRX 	= $rw_PRT->TOT_PR;
				$TOT_PR 	= $TOT_PR+$TOT_PRX;
			endforeach;
			if($TOT_PR == '')
				$TOT_PR = 0;

			$TOT_TMPPR 	= 0;		// TOTAL TEMPORARY
			$s_TMPPRT 	= "SELECT SUM(PR_VOLM) AS TOT_PR FROM tbl_pr_detail_tmp WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$r_TMPPRT 	= $this->db->query($s_TMPPRT)->result();
			foreach($r_TMPPRT as $rw_TMPPRT):
				$TOT_TMPPR = $rw_TMPPRT->TOT_PR;
			endforeach;
			if($TOT_TMPPR == '')
				$TOT_TMPPR = 0;

			$TOT_ITMPR 	= $TOT_PR+$TOT_TMPPR;
			$TOT_ITMREM = $ITM_VOLMBG-$TOT_ITMPR;
			$PR_TOTAL 	= $TOT_ITMREM*$ITM_PRICE;

		$JOBPARENT 	= 	"";
		$sqlJPAR	= 	"SELECT A.JOBPARENT, A.JOBDESC FROM tbl_joblist_detail A 
							WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
								WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')";
        $resJPAR	= $this->db->query($sqlJPAR)->result();
        foreach($resJPAR as $rowJPAR) :
            $JOBPARENT	= $rowJPAR->JOBPARENT;
            $JOBPARDESC	= $rowJPAR->JOBDESC;
        endforeach;

		$s_00 			= "SELECT MAX(PR_ID) AS PR_IDMAX FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_00 			= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
            $PR_IDMAX	= $rw_00->PR_IDMAX;
        endforeach;
        if($PR_IDMAX == '')
        	$PR_IDMAX 	= 0;

		$s_00a 			= "tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_00a 			= $this->db->count_all($s_00a);

		$PR_ID 			= $PR_IDMAX + $r_00a + 1;

		$s_00 	= 	"INSERT INTO tbl_pr_detail_tmp
						(PR_ID, PR_NUM, PR_CODE, PR_DATE, PRJCODE, DEPCODE, JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC,
						ITM_CODE, ITM_NAME, ITM_UNIT, PR_VOLM, PR_PRICE, PR_TOTAL, PR_DESC_ID, PR_DESC,
						ITM_VOLMBG, ITM_BUDG, PR_STAT)
						VALUES
						('$PR_ID', '$PR_NUM', '$PR_CODE', '', '$PRJCODE', '$DEPCODE', '$JOBCODEDET', '$JOBCODEID', '$JOBPARENT', '$JOBPARDESC',
						'$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$TOT_ITMREM', '$ITM_PRICE', '$PR_TOTAL', '', '',
						'$ITM_VOLMBG', '$ITM_BUDG', 1)";
		$this->db->query($s_00);

		$TOT_ROW 	= 0;
		$s_TROW 	= "SELECT COUNT(*) AS TOT_ROW FROM tbl_pr_detail_tmp WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_TROW 	= $this->db->query($s_TROW)->result();
		foreach($r_TROW as $rw_TROW):
			$TOT_ROW = $rw_TROW->TOT_ROW;
		endforeach;
		echo $TOT_ROW;
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

	function cancelItem()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$PR_NUM     = $colExpl[1];
		$PRJCODE    = $colExpl[2];
		$ITM_CODE   = $colExpl[3];
		$ITM_NAME   = $colExpl[4];
		$JOBPARDESC = $colExpl[5];
		$PR_VOLM    = $colExpl[6];
		$PO_VOLM    = $colExpl[7];
		$REM_VOLPR  = $colExpl[8];
		$ITM_UNIT   = $colExpl[9];
		$PR_CVOL   	= $colExpl[10];

		$s_01 			= "SELECT PR_CODE, JOBCODEID, PR_VOLM, PR_PRICE, PR_TOTAL, PO_VOLM, PO_AMOUNT
							FROM tbl_pr_detail
							WHERE ITM_CODE = '$ITM_CODE' AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$r_01 			= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01) :
			$PR_CODE 	= $rw_01->PR_CODE;
			$JOBCODEID 	= $rw_01->JOBCODEID;
			$PR_VOLM 	= $rw_01->PR_VOLM;
			$PR_PRICE 	= $rw_01->PR_PRICE;
			$PR_CTOTAL 	= $PR_CVOL * $PR_PRICE;

			// UPDATE STATUS SPP
				/*$s_02 	= 	"UPDATE tbl_pr_detail SET PR_VOLM = PR_VOLM - $PR_CVOL, PR_TOTAL = PR_TOTAL - $PR_CTOTAL, PR_CVOL = PR_CVOL + $PR_CVOL
								WHERE ITM_CODE = '$ITM_CODE' AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";*/
				$s_02 	= 	"UPDATE tbl_pr_detail SET PR_CVOL = PR_CVOL + $PR_CVOL, PR_CTOTAL = PR_CTOTAL + $PR_CTOTAL
								WHERE ITM_CODE = '$ITM_CODE' AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_02);

			// PENGEMBALIAN SISA VOLUME KE BUDGET
				$s_03 	= 	"UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM - $PR_CVOL, REQ_AMOUNT = REQ_AMOUNT - $PR_CTOTAL
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_03);

			// DELETE IF PR_VOLM = 0
				$s_04 	= 	"DELETE FROM tbl_pr_detail WHERE ITM_CODE = '$ITM_CODE' AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($s_04);

			// ADD HISTORY
				$Emp_ID = $this->session->userdata['Emp_ID'];
				$s_05 	= 	"INSERT INTO tbl_pr_detail_canc (PR_ID, PR_NUM, PR_CODE, PR_DATE, PRJCODE, DEPCODE, PR_REFNO,
								JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC, ITM_CODE, SNCODE, ITM_UNIT, PR_VOLM, PR_VOLM2, PR_PRICE,
								PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT, PR_CVOL, PR_TOTAL, PR_DESC, PR_DESC,
								TAXCODE1, TAXCODE2, TAXPRICE1, TAXPRICE2, ITM_VOLMBG, ITM_BUDG, ISCLOSE, PR_ISCANC, PR_STAT, CANC_EMP)
							SELECT PR_ID, PR_NUM, PR_CODE, PR_DATE, PRJCODE, DEPCODE, PR_REFNO,
								JOBCODEDET, JOBCODEID, JOBPARENT, JOBPARDESC, ITM_CODE, SNCODE, ITM_UNIT, PR_VOLM, PR_VOLM2, PR_PRICE,
								PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT, PR_CVOL, PR_TOTAL, PR_DESC, PR_DESC,
								TAXCODE1, TAXCODE2, TAXPRICE1, TAXPRICE2, ITM_VOLMBG, ITM_BUDG, ISCLOSE, PR_ISCANC, PR_STAT, '$Emp_ID'
								FROM tbl_pr_detail WHERE ITM_CODE = '$ITM_CODE' AND PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_05);

			// ADD HISTORY ITEM VOID
				$V_NUM1		= date('ymnHis');
				$V_NUM 		= "PR.".$V_NUM1;
				$CANC_EMP 	= $this->session->userdata['Emp_ID'];
				$CANC_TIME 	= date('Y-m-d H:i:s');
				$s_06 	= 	"INSERT INTO tbl_item_void (V_NUM, V_CODE, V_DATE, V_CATEG, V_DESC, PRJCODE, REF_NUM, REF_CODE, JOBCODEID, JOBDESC,
									ITM_CODE, ITM_NAME, ITM_UNIT, V_VOL, V_PRICE, V_TOTAL, V_ACCID, V_EMPID, V_CREATED)
								VALUES ('$V_NUM', '$PR_CODE', '$CANC_TIME', 'PR', '', '$PRJCODE', '$PR_CODE', '$REF_CODE', '$JOBCODEID', '$ITM_NAME',
									'$ITM_CODE', '$ITM_NAME', '$ITM_UNIT', '$PR_CVOL', '$PR_PRICE', '$PR_CTOTAL', '', '$CANC_EMP', '$CANC_TIME')";
				$this->db->query($s_06);

			// UPDATE ITEM
				$s_07	= "UPDATE tbl_item SET PR_VOLM = PR_VOLM-$PR_CVOL, PR_AMOUNT = PR_AMOUNT-$PR_CTOTAL WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_07);
		endforeach;
		echo "Volume item $ITM_NAME sudah dikembalikan ke budget.";
	}

  	function get_AllDataDESC() // GOOD
	{
		$ITM_CODE		= $_GET['ITMCODE'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
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
									"DESC_NOTES");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_req->get_AllDataDESCC($ITM_CODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_req->get_AllDataDESCL($ITM_CODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$DESC_ID 		= $dataI['DESC_ID'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$DESC_NOTES 	= $dataI['DESC_NOTES'];

				$chkBox			= "<input type='radio' name='chk5' value='".$DESC_ID."|".$DESC_NOTES."' onClick='pickThis5();'/>";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											$DESC_NOTES);

				$noU				= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function updDESC()
	{
		$strItem 	= $_POST['collID'];
		$arrItem 	= explode("~", $strItem);

		$PRJCODE 	= $arrItem[0];
		$PR_NUM 	= $arrItem[1];
		$ITM_CODE 	= $arrItem[2];
		$PR_ID 		= $arrItem[3];
		$DESC_ID 	= $arrItem[4];
		$DESC_NOTES	= $arrItem[5];

		$s_00 		= "UPDATE tbl_pr_detail SET PR_DESC_ID = '$DESC_ID', PR_DESC = '$DESC_NOTES'
						WHERE PR_NUM = '$PR_NUM' AND PR_ID = $PR_ID AND PRJCODE = '$PRJCODE'";
		$this->db->query($s_00);

		$s_01 		= "UPDATE tbl_pr_detail_tmp SET PR_DESC_ID = '$DESC_ID', PR_DESC = '$DESC_NOTES'
						WHERE PR_NUM = '$PR_NUM' AND PR_ID = $PR_ID AND PRJCODE = '$PRJCODE'";
		$this->db->query($s_01);
		echo $s_00;
	}

	function addDesc()
	{
		$strItem 	= $_POST['collID'];
		$arrItem 	= explode("|", $strItem);

		$ITMCODE 	= $arrItem[0];
		$ITMDESC 	= $arrItem[1];

		$MAXID 		= 0;
		$s_01 		= "SELECT MAX(DESC_ID) AS MAXID FROM tbl_desc";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01) :
			$MAXID 	= $rw_01->MAXID;
		endforeach;
		$NMAXID 	= $MAXID+1;

		$s_00 		= "INSERT tbl_desc (DESC_ID, ITM_CODE, DESC_NOTES)
						VALUES
						($NMAXID, '$ITMCODE', '$ITMDESC')";
		$this->db->query($s_00);
		echo $s_00;
	}

	function getITMVOL_R()
	{
		$task 		= $this->input->post('task');
		$PRJCODE 	= $this->input->post('PRJCODE');
		$PR_NUM 	= $this->input->post('PR_NUM');
		$ITM_CODE	= $this->input->post('ITM_CODE');
		$PR_DESC_ID	= $this->input->post('PR_DESC_ID');
		$PR_VOLMBF 	= $this->input->post('PR_VOLMBF');
		$PR_VOLM 	= $this->input->post('PR_VOLM');

		$getPR_R 	= "SELECT A.ITM_CODE, A.PR_DESC_ID AS PO_DESC_ID, IFNULL(SUM(A.PR_VOLM), 0) AS ITMVOLM_R
						FROM tbl_pr_detail A
						WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'
						AND A.ITM_CODE = '$ITM_CODE' AND A.PR_DESC_ID = '$PR_DESC_ID'";

		$resPR_R 	= $this->db->query($getPR_R);
		foreach($resPR_R->result() as $rPR_R):
			$data['ITM_CODE'] 		= $rPR_R->ITM_CODE;
			$data['PO_DESC_ID'] 	= $rPR_R->PO_DESC_ID;
			$ITMVOLM_R1 			= $rPR_R->ITMVOLM_R;
			$data['ITMVOLM_R'] 		= $ITMVOLM_R1 - $PR_VOLMBF + $PR_VOLM;
		endforeach;

		echo json_encode($data);
	}

	function trashFile()
	{
		$PR_NUM		= $this->input->post("PR_NUM");
		$PRJCODE	= $this->input->post("PRJCODE");
		// $index 		= $this->input->post("indexRow");
		$fileName	= $this->input->post("fileName");

		/* Change PR_DOC to array => upd: 2022-09-16
			$arrDOC = explode(", ", $PR_DOC);
			foreach($arrDOC as $r => $val):
				$getarr[] = $val;
			endforeach;

			unset($getarr[$index]); // Delete index array
		---------------------- End Hidden --------------------- */
		
		$this->m_purchase_req->delUPL_DOC($PR_NUM, $PRJCODE, $fileName); // Delete File
		
		// Delete file in path folder
			$path = "assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE/$fileName";
			unlink($path);
	}

	function downloadFile()
	{
		$this->load->helper('download');

		$fileName 	= $_GET['file'];
		$PRJCODE 	= $_GET['prjCode'];
		$path 		= "assets/AdminLTE-2.0.5/doc_center/uploads/PR_Document/$PRJCODE/$fileName";
		force_download($path, NULL);
	}
}