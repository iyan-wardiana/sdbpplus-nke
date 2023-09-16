<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 20 Desember 2017
 * File Name	= C_br180c2cd0d.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_br180c2cd0d extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
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

 	function index() // OK
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('c_finance/c_br180c2cd0d/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN148';
				$data["MenuApp"] 	= 'MN149';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN126';		// MN126 = Daftar Proyek 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_br180c2cd0d/gall180c2c04a0br/?id=";
			
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

	function gall180c2c04a0br() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN148';
			$data["MenuCode"] 	= 'MN148';
			$data["MenuApp"] 	= 'MN149';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
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
			// -------------------- END : SEARCHING METHOD --------------------

			$data['PRJCODE'] 	= $PRJCODE;
			$data['addURL'] 	= site_url('c_finance/c_br180c2cd0d/a180c2c0d11dd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_finance/c_br180c2cd0d/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['form_action']= site_url('c_finance/c_br180c2cd0d/add_process');
			//$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			//$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			//$data["countBR"] 	= $this->m_bank_receipt->count_all_BR();
			//$data['vwBR'] 		= $this->m_bank_receipt->get_last_BR()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Bank-Receipt';
				$MenuCode 		= 'MN148';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_bank_receipt', $data);
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
			
			$columns_valid 	= array("BR_ID",
									"BR_CODE",
									"BR_DATE",
									"Account_Name",
									"BR_TOTAM",
									"BR_NOTES",
									"STATDESC",
									"INVSTAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_receipt->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_receipt->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
                $BR_NUM        	= $dataI['BR_NUM'];
                $BR_CODE        = $dataI['BR_CODE'];
                $BR_DATE        = $dataI['BR_DATE'];
                $BR_DATEV		= date('d M Y', strtotime($BR_DATE));
                $BR_RECTYPE     = $dataI['BR_RECTYPE'];
				$BR_TYPE		= $dataI['BR_TYPE'];
				$Account_Name	= $dataI['Account_NameEn'];
				$BR_PAYFROM		= $dataI['BR_PAYFROM'];	
				$own_Name       = '';

				// GET isLock in Journal
					$isLock = 0;
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
					$getJ 	= "SELECT IFNULL(isLock,0) AS isLock FROM tbl_journalheader_$PRJCODEVW 
								WHERE GEJ_STAT = 3 AND JournalH_Code = '$BR_NUM'";
					$resJ 	= $this->db->query($getJ);
					if($resJ->num_rows() > 0)
					{
						foreach($resJ->result() as $rJ):
							$isLock = $rJ->isLock;
						endforeach;
					}

					$isLockD = "";
					$isdisabledVw = "";
					if($isLock == 1)
					{
						$isLockD 		= "<i class='fa fa-lock margin-r-5'></i>";
						$isdisabledVw 	= "disabled='disabled'";
					}
				// END GET isLock in Journal

                if($BR_RECTYPE == 'SAL')
                {
                    $sqlOWN     = "SELECT '' AS own_Title, CUST_DESC AS own_Name FROM tbl_customer
                                    WHERE CUST_CODE = '$BR_PAYFROM'";
                    $resOWN     = $this->db->query($sqlOWN)->result();
                    foreach($resOWN as $rowOWN) :
                        $own_Title  = $rowOWN->own_Title;
                        if($own_Title != '')
                            $own_Title  = ", $own_Title";
                        else
                            $own_Title  = "";
                        $own_Name   = $rowOWN->own_Name;
                    endforeach;
                }
                elseif($BR_RECTYPE == 'PRJ' || $BR_RECTYPE == 'DP')
                {
                    $sqlOWN     = "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
                    $resOWN     = $this->db->query($sqlOWN)->result();
                    foreach($resOWN as $rowOWN) :
                        $own_Title  = $rowOWN->own_Title;
                        if($own_Title != '')
                            $own_Title  = ", $own_Title";
                        else
                            $own_Title  = "";
                        $own_Name   = $rowOWN->own_Name;
                    endforeach;
                }
                elseif($BR_RECTYPE == 'PPD')
                {
                    $sqlOWN     = "SELECT '' AS own_Title, CONCAT(First_Name, ' ', Last_Name) AS own_Name
									FROM tbl_employee WHERE Emp_ID = '$BR_PAYFROM'";
                    $resOWN     = $this->db->query($sqlOWN)->result();
                    foreach($resOWN as $rowOWN) :
                        $own_Title  = $rowOWN->own_Title;
                        if($own_Title != '')
                            $own_Title  = ", $own_Title";
                        else
                            $own_Title  = "";
                        $own_Name   = $rowOWN->own_Name;
                    endforeach;
                }
				
				$BR_NOTES	= $dataI['BR_NOTES'];
				$BR_TOTAM	= $dataI['BR_TOTAM'];
				$BR_STAT	= $dataI['BR_STAT'];
				$ISVOID		= $dataI['ISVOID'];
				$PRJCODE	= $dataI['PRJCODE'];
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
							
				if($ISVOID == 1)
				{
					$CISVOIDD 		= 'yes';
					$STATVCOL		= 'danger';
				}
				else
				{
					$CISVOIDD 		= 'no';
					$STATVCOL		= 'success';
				}
				
				$CollID1	= "$PRJCODE~$BR_NUM";
				$secUpd		= site_url('c_finance/c_br180c2cd0d/u180c2gdt//?id='.$this->url_encryption_helper->encode_url($CollID1));
				$secPrint	= site_url('c_finance/c_br180c2cd0d/prntD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$CollID		= "BR~$BR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDel 	= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 	= base_url().'index.php/__l1y/trashBR/?id=';
				$secVoid 	= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 		= "$secDel~tbl_br_header~tbl_br_detail~BR_NUM~$BR_NUM~PRJCODE~$PRJCODE";
				$voidID 	= "$secVoid~tbl_br_header~tbl_br_detail~BR_NUM~$JournalH_Code~PRJCODE~$PRJCODE";

				if($BR_STAT == 1 || $BR_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($BR_STAT == 3 || $BR_STAT == 6) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' $isdisabledVw>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
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
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
												
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>$isLockD
												".$dataI['BR_CODE']."
											</div>
											",
											$BR_DATEV,
											$own_Name,
											"<div style='white-space:nowrap'>".wordwrap($Account_Name,50,"<br>\n")."</div>",
											number_format($BR_TOTAM, 2),
											$BR_NOTES,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
										  
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataBPGRP() // GOOD
	{
		//$PRJCODE		= $_GET['id'];
		$CUSTCODE		= $_GET['SPLC'];
		$CB_STAT		= $_GET['GSTAT'];
		$CB_SOURCE		= $_GET['SRC'];
		$PROJECT		= $_GET['PROJECT'];
		$SELPRJ			= $_GET['PROJECT'];
		if(!empty($SELPRJ))
			$PRJCODE 	= $SELPRJ;
		
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
			
			$columns_valid 	= array("BR_ID",
									"BR_CODE",
									"BR_DATE",
									"Account_Name",
									"BR_NOTES",
									"STATDESC",
									"INVSTAT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bank_receipt->get_AllDataGRPC($PRJCODE, $CUSTCODE, $CB_STAT, $CB_SOURCE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bank_receipt->get_AllDataGRPL($PRJCODE, $CUSTCODE, $CB_STAT, $CB_SOURCE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code	= $dataI['JournalH_Code'];
                $BR_NUM        	= $dataI['BR_NUM'];
                $BR_CODE        = $dataI['BR_CODE'];
                $BR_DATE        = $dataI['BR_DATE'];
                $BR_DATEV		= date('d M Y', strtotime($BR_DATE));
                $BR_RECTYPE     = $dataI['BR_RECTYPE'];
				$BR_TYPE		= $dataI['BR_TYPE'];
				$Account_Name	= $dataI['Account_NameEn'];
				$BR_PAYFROM		= $dataI['BR_PAYFROM'];	
				$own_Name       = '';

				// GET isLock in Journal
					$isLock = 0;
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
					$getJ 	= "SELECT IFNULL(isLock,0) AS isLock FROM tbl_journalheader_$PRJCODEVW 
								WHERE GEJ_STAT = 3 AND JournalH_Code = '$BR_NUM'";
					$resJ 	= $this->db->query($getJ);
					if($resJ->num_rows() > 0)
					{
						foreach($resJ->result() as $rJ):
							$isLock = $rJ->isLock;
						endforeach;
					}

					$isLockD = "";
					$isdisabledVw = "";
					if($isLock == 1)
					{
						$isLockD 		= "<i class='fa fa-lock margin-r-5'></i>";
						$isdisabledVw 	= "disabled='disabled'";
					}
				// END GET isLock in Journal

                if($BR_RECTYPE == 'SAL')
                {
                    $sqlOWN     = "SELECT '' AS own_Title, CUST_DESC AS own_Name FROM tbl_customer
                                    WHERE CUST_CODE = '$BR_PAYFROM'";
                    $resOWN     = $this->db->query($sqlOWN)->result();
                    foreach($resOWN as $rowOWN) :
                        $own_Title  = $rowOWN->own_Title;
                        if($own_Title != '')
                            $own_Title  = ", $own_Title";
                        else
                            $own_Title  = "";
                        $own_Name   = $rowOWN->own_Name;
                    endforeach;
                }
                elseif($BR_RECTYPE == 'PRJ')
                {
                    $sqlOWN     = "SELECT own_Title, own_Name FROM tbl_owner WHERE own_Code = '$BR_PAYFROM'";
                    $resOWN     = $this->db->query($sqlOWN)->result();
                    foreach($resOWN as $rowOWN) :
                        $own_Title  = $rowOWN->own_Title;
                        if($own_Title != '')
                            $own_Title  = ", $own_Title";
                        else
                            $own_Title  = "";
                        $own_Name   = $rowOWN->own_Name;
                    endforeach;
                }
                elseif($BR_RECTYPE == 'PPD')
                {
                    $sqlOWN     = "SELECT '' AS own_Title, CONCAT(First_Name, ' ', Last_Name) AS own_Name
									FROM tbl_employee WHERE Emp_ID = '$BR_PAYFROM'";
                    $resOWN     = $this->db->query($sqlOWN)->result();
                    foreach($resOWN as $rowOWN) :
                        $own_Title  = $rowOWN->own_Title;
                        if($own_Title != '')
                            $own_Title  = ", $own_Title";
                        else
                            $own_Title  = "";
                        $own_Name   = $rowOWN->own_Name;
                    endforeach;
                }
				
				$BR_NOTES	= $dataI['BR_NOTES'];
				$BR_TOTAM	= $dataI['BR_TOTAM'];
				$BR_STAT	= $dataI['BR_STAT'];
				$ISVOID		= $dataI['ISVOID'];
				$PRJCODE	= $dataI['PRJCODE'];
				
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
							
				if($ISVOID == 1)
				{
					$CISVOIDD 		= 'yes';
					$STATVCOL		= 'danger';
				}
				else
				{
					$CISVOIDD 		= 'no';
					$STATVCOL		= 'success';
				}
				
				$CollID1	= "$PRJCODE~$BR_NUM";
				$secUpd		= site_url('c_finance/c_br180c2cd0d/u180c2gdt//?id='.$this->url_encryption_helper->encode_url($CollID1));
				$secPrint	= site_url('c_finance/c_br180c2cd0d/prntD/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$CollID		= "BR~$BR_NUM~$PRJCODE";
				$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDel 	= base_url().'index.php/__l1y/trashDOC/?id=';
				//$secVoid 	= base_url().'index.php/__l1y/trashBR/?id=';
				$secVoid 	= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$delID 		= "$secDel~tbl_br_header~tbl_br_detail~BR_NUM~$BR_NUM~PRJCODE~$PRJCODE";
				$voidID 	= "$secVoid~tbl_br_header~tbl_br_detail~BR_NUM~$JournalH_Code~PRJCODE~$PRJCODE";

				if($BR_STAT == 1 || $BR_STAT == 4)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($BR_STAT == 3 || $BR_STAT == 6) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' $isdisabledVw>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
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
								   <a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
												
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>$isLockD
												".$dataI['BR_CODE']."
											</div>
											",
											$BR_DATEV,
											$own_Name,
											"<div style='white-space:nowrap'>".wordwrap($Account_Name,50,"<br>\n")."</div>",
											number_format($BR_TOTAM, 2),
											$BR_NOTES,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
										  
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a180c2c0d11dd() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN148';
			$data["MenuCode"] 	= 'MN148';
			$data["MenuApp"] 	= 'MN149';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			/*$appName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($appName);*/
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$docPatternPosition = 'Especially';
			$data['PRJCODE'] 	= $PRJCODE;
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_finance/c_br180c2cd0d/add_process');
			$data['backURL'] 	= site_url('c_finance/c_br180c2cd0d/gall180c2c04a0br/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_receipt->count_all_Acc($proj_Currency, $DefEmp_ID);
			$data['vwAcc'] 		= $this->m_bank_receipt->view_all_Acc($proj_Currency, $DefEmp_ID)->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN148';
			$data["MenuCode"] 	= 'MN148';
			$data['vwDocPatt'] 	= $this->m_bank_receipt->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Bank-Receipt';
				$MenuCode 		= 'MN148';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_bank_receipt_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pall180c2ginvr() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			
			$collID	= $_GET['id'];
			$collID	= $this->url_encryption_helper->decode_url($collID);
			
			$splitCode 	= explode("~", $collID);
			$BR_RECTYPE	= $splitCode[0];
			$BR_PAYFROM	= $splitCode[1];
			$PRJCODE	= $splitCode[2];
			
			$data['title'] 		= $appName;
			
			$data['BR_RECTYPE'] = $BR_RECTYPE;	
			if($BR_RECTYPE == 'SAL')
			{
				$data['BR_PAYFROM'] = $BR_PAYFROM;			
				$data['countINV'] 	= $this->m_bank_receipt->count_all_INVR_CUST($BR_PAYFROM);
				$data['vwINV'] 		= $this->m_bank_receipt->view_all_INVR_CUST($BR_PAYFROM)->result();
			}
			elseif($BR_RECTYPE == 'PRJ')
			{
				$data['BR_PAYFROM'] = $BR_PAYFROM;			
				$data['countINV'] 	= $this->m_bank_receipt->count_all_INVR_OWN($BR_PAYFROM);
				$data['vwINV'] 		= $this->m_bank_receipt->view_all_INVR_OWN($BR_PAYFROM)->result();
			}
			elseif($BR_RECTYPE == 'DP')
			{
				$data['BR_PAYFROM'] = $BR_PAYFROM;			
				$data['countINV'] 	= $this->m_bank_receipt->count_all_INVR_OWNDP($BR_PAYFROM);
				$data['vwINV'] 		= $this->m_bank_receipt->view_all_INVR_OWNDP($BR_PAYFROM)->result();
			}
			elseif($BR_RECTYPE == 'PPD')
			{
				$data['BR_PAYFROM'] = $BR_PAYFROM;			
				$data['countINV'] 	= $this->m_bank_receipt->count_all_INVR_OWNPD($BR_PAYFROM, $PRJCODE);
				$data['vwINV'] 		= $this->m_bank_receipt->view_all_INVR_OWNPD($BR_PAYFROM, $PRJCODE)->result();
			}
			else
			{
				$data['BR_PAYFROM'] = '';			
				$data['countINV'] 	= '';
				$data['vwINV'] 		= '';
			}
					
			$this->load->view('v_finance/v_bank_receipt/v_select_invr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // OK
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$BR_TYPE	= $this->input->post('BR_TYPE');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$CBDate		= date('Y-m-d',strtotime($this->input->post('CBDate')));
		$year		= date('Y',strtotime($this->input->post('CBDate')));
		$month 		= (int)date('m',strtotime($this->input->post('CBDate')));
		$date 		= (int)date('d',strtotime($this->input->post('CBDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_pr_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_br_header
					WHERE Patt_Year = $year AND BR_TYPE = '$BR_TYPE'";
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
	
	function add_process() // OK
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CREATED 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$JournalH_Code	= $this->input->post('BR_NUM');
			$BR_NUM	 		= $this->input->post('BR_NUM');
			$BR_CODE	 	= $this->input->post('BR_CODE');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$BR_CURRID	 	= $this->input->post('BR_CURRID');
			$BR_CURRCONV 	= $this->input->post('BR_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$BR_ACCID 		= $this->input->post('selAccount');
			$BR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('BR_DATE'))));
			$BR_TYPE		= 'BR';
			$BR_RECTYPE	 	= $this->input->post('BR_RECTYPE');
			$BR_PAYFROM	 	= $this->input->post('BR_PAYFROM');
			$BR_PAYEE	 	= $this->input->post('BR_PAYFROM');
			/*$BR_CHEQNO	 	= $this->input->post('BR_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$BR_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$BR_CHEQDAT		= $BGDate;*/
			$BR_DOCTYPE		= 'PINV';
			$BR_TOTAM	 	= $this->input->post('BR_TOTAM');
			$BR_TOTAM_PPn 	= $this->input->post('BR_TOTAM_PPn');
			// $BR_NOTES	 	= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$BR_NOTES	 	= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$BR_STAT		= $this->input->post('BR_STAT');
			$BR_APPSTAT		= 0;
			$BR_CREATER		= $DefEmp_ID;
			$BR_CREATED		= $CREATED;
			$Company_ID		= $comp_init;
			
			$Patt_Year		= date('Y',strtotime($BR_DATE));
			$Patt_Month		= date('m',strtotime($BR_DATE));
			$Patt_Date		= date('d',strtotime($BR_DATE));
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN148';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$BR_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> $MenuCode,
										'DOCTYPE' 		=> 'BR',
										'DOCNUM' 		=> $BR_NUM,
										'DOCCODE'		=> $BR_CODE,
										'DOCDATE'		=> $BR_DATE,
										'ACC_ID'		=> $BR_ACCID,
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $BR_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			$inBankRec 		= array('JournalH_Code' => $BR_NUM,
									'BR_NUM' 		=> $BR_NUM,
									'BR_CODE' 		=> $BR_CODE,
									'PRJCODE' 		=> $PRJCODE,
									'BR_DATE' 		=> $BR_DATE,
									'BR_TYPE' 		=> $BR_TYPE,
									'BR_RECTYPE'	=> $BR_RECTYPE,
									'BR_CURRID' 	=> $BR_CURRID,
									'BR_CURRCONV' 	=> $BR_CURRCONV,
									'Acc_ID' 		=> $Acc_ID,
									'BR_PAYFROM' 	=> $BR_PAYFROM,
									'BR_PAYEE' 		=> $BR_PAYEE,
									//'BR_CHEQNO' 	=> $BR_CHEQNO,
									//'BR_CHEQDAT' 	=> $BR_CHEQDAT,
									'BR_DOCTYPE' 	=> $BR_DOCTYPE,
									'BR_STAT' 		=> $BR_STAT,
									'BR_TOTAM' 		=> $BR_TOTAM,
									'BR_TOTAM_PPn' 	=> $BR_TOTAM_PPn,
									'BR_NOTES' 		=> $BR_NOTES,
									'BR_CREATER' 	=> $BR_CREATER,
									'BR_CREATED' 	=> $BR_CREATED,
									'Company_ID' 	=> $Company_ID,
									'Patt_Year' 	=> $Patt_Year,
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date' 	=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_bank_receipt->add($inBankRec);
			
			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					$d['JournalH_Code']		= $BR_NUM;
					$d['BR_NUM']			= $BR_NUM;
					$d['BR_CODE']			= $BR_CODE;
					$this->db->insert('tbl_br_detail',$d);

					if($BR_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$BR_VAL 	= $d['GAmount'];
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $BR_DATE,
												'FVAL'		=> $BR_VAL,
												'FNAME'		=> "BR_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				}
			}

			$PRJPERIOD			= $PRJCODE;
			$proj_CodeHO		= $PRJCODE;
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO, PRJPERIOD FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
				$PRJPERIOD		= $rowPRJHO->PRJPERIOD;
			endforeach;
			
			if(isset($_POST['dataACC']))
			{
				$jrnRow 		= 0;
				foreach($_POST['dataACC'] as $d)
				{
					$jrnRow 		= $jrnRow+1;
					$Acc_Id 		= $d['Acc_Id'];
					// $Acc_Name 		= htmlspecialchars($d['JournalD_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$Acc_Name 		= htmlspecialchars($d['JournalD_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$ITM_PRICE 		= $d['JournalD_Amount'];
					$Journal_DK		= $d['Journal_DK'];
					if($Journal_DK == 'D')
					{
						$Base_D 	= $d['JournalD_Amount'];
						$Base_K 	= 0;
					}
					else
					{
						$Base_D 	= 0;
						$Base_K 	= $d['JournalD_Amount'];
					}

					// $Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					// $Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);

					$insSQL	= "INSERT INTO tbl_journaldetail_br (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code, Currency_id,
									JournalD_Debet, Base_Debet, COA_Debet, JournalD_Kredit, Base_Kredit, COA_Kredit,
									curr_rate, isDirect, ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
									GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, Manual_No) VALUES
									('$BR_NUM', '$Acc_Id', '$BR_ACCID', 'BR', '$PRJCODE', 'IDR', 
									$Base_D, $Base_D, $Base_D, $Base_K, $Base_K, $Base_K,
									1, 1, 1, '$ITM_PRICE', '$Ref_Number', '$Other_Desc', '$Journal_DK', 'BR', 0, 
									'$BR_STAT', '$BR_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow, '$BR_CODE')";
					$this->db->query($insSQL);
				}
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $BR_NUM;
				$MenuCode 		= 'MN148';
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "BR_NUM",
										'DOC_CODE' 		=> $BR_NUM,
										'DOC_STAT' 		=> $BR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_br_header");
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

			$url	= site_url('c_finance/c_br180c2cd0d/gall180c2c04a0br/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function u180c2gdt() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA		= $_GET['id'];
			$COLLDATA		= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL		= explode("~", $COLLDATA);
			$PRJCODE		= $EXTRACTCOL[0];
			$JournalH_Code	= $EXTRACTCOL[1];
			
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_br180c2cd0d/update_process');
			$data['backURL'] 	= site_url('c_finance/c_br180c2cd0d/gall180c2c04a0br/?id='.$this->url_encryption_helper->encode_url($PRJCODE));		
			
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_receipt->count_all_Acc($proj_Currency, $DefEmp_ID);
			$data['vwAcc'] 		= $this->m_bank_receipt->view_all_Acc($proj_Currency, $DefEmp_ID)->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN148';
			$data["MenuCode"] 	= 'MN148';
			$data["PRJCODE"] 	= $PRJCODE;
			
			$getpurreq 						= $this->m_bank_receipt->get_BR_by_number($JournalH_Code)->row();
			$data['default']['BR_NUM'] 		= $getpurreq->BR_NUM;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['BR_CODE'] 	= $getpurreq->BR_CODE;
			$data['default']['BR_DATE']		= $getpurreq->BR_DATE;
			$data['default']['BR_TYPE'] 	= $getpurreq->BR_TYPE;
			$data['default']['BR_RECTYPE'] 	= $getpurreq->BR_RECTYPE;
			$data['default']['BR_CURRID'] 	= $getpurreq->BR_CURRID;
			$data['default']['BR_CURRCONV'] = $getpurreq->BR_CURRCONV;
			$data['default']['Acc_ID'] 		= $getpurreq->Acc_ID;
			$data['default']['BR_PAYFROM'] 	= $getpurreq->BR_PAYFROM;
			$data['default']['BR_CHEQNO'] 	= $getpurreq->BR_CHEQNO;
			$data['BR_CHEQNO'] 				= $getpurreq->BR_CHEQNO;
			$data['default']['BR_DOCTYPE']	= $getpurreq->BR_DOCTYPE;
			$data['default']['BR_STAT']		= $getpurreq->BR_STAT;
			$data['default']['BR_TOTAM'] 	= $getpurreq->BR_TOTAM;
			$data['default']['BR_TOTAM_PPn']= $getpurreq->BR_TOTAM_PPn;
			$data['default']['BR_NOTES'] 	= $getpurreq->BR_NOTES;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN148';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_bank_receipt_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CREATED 		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$JournalH_Code	= $this->input->post('BR_NUM');
			$BR_NUM	 		= $this->input->post('BR_NUM');
			$BR_CODE	 	= $this->input->post('BR_CODE');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$BR_CURRID	 	= $this->input->post('BR_CURRID');
			$BR_CURRCONV 	= $this->input->post('BR_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$BR_ACCID	 	= $this->input->post('selAccount');
			$BR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('BR_DATE'))));
			$BR_TYPE		= 'BR';
			$BR_RECTYPE	 	= $this->input->post('BR_RECTYPE');
			$BR_PAYFROM	 	= $this->input->post('BR_PAYFROM');
			$BR_PAYEE	 	= $this->input->post('BR_PAYFROM');
			/*$BR_CHEQNO	 	= $this->input->post('BR_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$BR_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$BR_CHEQDAT		= $BGDate;*/
			$BR_DOCTYPE		= 'PINV';
			$BR_TOTAM	 	= $this->input->post('BR_TOTAM');
			$BR_TOTAM_PPn 	= $this->input->post('BR_TOTAM_PPn');
			// $BR_NOTES	 	= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$BR_NOTES	 	= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$BR_STAT		= $this->input->post('BR_STAT');
			$BR_APPSTAT		= 0;
			$BR_CREATER		= $DefEmp_ID;
			$BR_CREATED		= $CREATED;
			$Company_ID		= $comp_init;

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN148',
										'DOCNUM' 		=> $BR_NUM,
										'DOCCODE'		=> $BR_CODE,
										'DOCDATE'		=> $BR_DATE,
										'ACC_ID'		=> $BR_ACCID,
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			$inBankRec 		= array('BR_DATE' 		=> $BR_DATE,
									'BR_CODE' 		=> $BR_CODE,
									'BR_RECTYPE' 	=> $BR_RECTYPE,
									'BR_TYPE' 		=> $BR_TYPE,
									'BR_CURRID' 	=> $BR_CURRID,
									'BR_CURRCONV' 	=> $BR_CURRCONV,
									'Acc_ID' 		=> $Acc_ID,
									'BR_PAYFROM' 	=> $BR_PAYFROM,
									'BR_PAYEE' 		=> $BR_PAYEE,
									//'BR_CHEQNO' 	=> $BR_CHEQNO,
									//'BR_CHEQDAT' 	=> $BR_CHEQDAT,
									'BR_DOCTYPE' 	=> $BR_DOCTYPE,
									'BR_STAT' 		=> $BR_STAT,
									'BR_TOTAM' 		=> $BR_TOTAM,
									'BR_TOTAM_PPn' 	=> $BR_TOTAM_PPn,
									'BR_NOTES' 		=> $BR_NOTES);									
			$this->m_bank_receipt->update($JournalH_Code, $inBankRec);
			
			$this->m_bank_receipt->deleteDetail($JournalH_Code);

			if(isset($_POST['data']))
			{
				foreach($_POST['data'] as $d)
				{
					$d['BR_CODE']	= $BR_CODE;
					$this->db->insert('tbl_br_detail',$d);

					if($BR_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$BR_VAL 	= $d['GAmount'];
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $BR_DATE,
												'FVAL'		=> $BR_VAL,
												'FNAME'		=> "BR_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				}
			}

			$PRJPERIOD			= $PRJCODE;
			$proj_CodeHO		= $PRJCODE;
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO, PRJPERIOD FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
				$PRJPERIOD		= $rowPRJHO->PRJPERIOD;
			endforeach;
			
			if(isset($_POST['dataACC']))
			{
				$s_DELJRN 		= "DELETE FROM tbl_journaldetail_br WHERE JournalH_Code = '$BR_NUM' AND proj_Code = '$PRJCODE'";
				$this->db->query($s_DELJRN);

				$jrnRow 		= 0;
				foreach($_POST['dataACC'] as $d)
				{
					$jrnRow 		= $jrnRow+1;
					$Acc_Id 		= $d['Acc_Id'];
					$Acc_Name 		= $d['JournalD_Desc'];
					$ITM_PRICE 		= $d['JournalD_Amount'];
					$Journal_DK		= $d['Journal_DK'];
					if($Journal_DK == 'D')
					{
						$Base_D 	= $d['JournalD_Amount'];
						$Base_K 	= 0;
					}
					else
					{
						$Base_D 	= 0;
						$Base_K 	= $d['JournalD_Amount'];
					}

					// $Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					// $Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);

					$insSQL	= "INSERT INTO tbl_journaldetail_br (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code, Currency_id,
									JournalD_Debet, Base_Debet, COA_Debet, JournalD_Kredit, Base_Kredit, COA_Kredit,
									curr_rate, isDirect, ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
									GEJ_STAT, JournalH_Date, proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, PattNum, Manual_No) VALUES
									('$BR_NUM', '$Acc_Id', '$BR_ACCID', 'BR', '$PRJCODE', 'IDR', 
									$Base_D, $Base_D, $Base_D, $Base_K, $Base_K, $Base_K,
									1, 1, 1, '$ITM_PRICE', '$Ref_Number', '$Other_Desc', '$Journal_DK', 'BR', 0, 
									'$BR_STAT', '$BR_DATE', '$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, $jrnRow, '$BR_CODE')";
					$this->db->query($insSQL);
				}
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN148';
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
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "BR_NUM",
										'DOC_CODE' 		=> $BR_NUM,
										'DOC_STAT' 		=> $BR_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_br_header");
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
			
			$url	= site_url('c_finance/c_br180c2cd0d/gall180c2c04a0br/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function inbox() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_br180c2cd0d/br7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function br7_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN149';
				$data["MenuApp"] 	= 'MN149';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN149';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_finance/c_br180c2cd0d/inb180c2g/?id=";
			
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
	
 	function inb180c2ga() // OK
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_br180c2cd0d/inb180c2g/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function inb180c2g() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$EmpID 				= $this->session->userdata('Emp_ID');
			
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
			// -------------------- END : SEARCHING METHOD --------------------

			//$appName			= $_GET['id'];
			//$appName			= $this->url_encryption_helper->decode_url($appName);
			//$EmpID 				= $this->session->userdata('Emp_ID');
			
			// GET MENU DESC
				$mnCode				= 'MN149';
				$data["MenuApp"] 	= 'MN149';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['appName'] 	= $appName;
			$data['PRJCODE'] 	= $PRJCODE;
			$data["MenuCode"] 	= 'MN149';
			$data["countPRJ"]	= $this->m_projectlist->count_all_project($EmpID);
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($EmpID)->result();
			
			$data["countBP"] 	= $this->m_bank_receipt->count_all_BR_inb($PRJCODE);
			$data['vwBP'] 		= $this->m_bank_receipt->get_last_BR_inb($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= 'Bank-Receipt';
				$MenuCode 		= 'MN149';
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
			
			$data['backURL'] 	= site_url('c_finance/c_br180c2cd0d/inbox');
			$this->load->view('v_finance/v_bank_receipt/v_inb_bank_receipt', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function uin180c2gdt() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_finance/c_br180c2cd0d/update_process_inb');
			
			$proj_Currency		= 'IDR';
			$data['countAcc'] 	= $this->m_bank_receipt->count_all_Acc($proj_Currency, $DefEmp_ID);
			$data['vwAcc'] 		= $this->m_bank_receipt->view_all_Acc($proj_Currency, $DefEmp_ID)->result();
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN149';
			$data["MenuCode"] 	= 'MN149';
			
			$getpurreq 						= $this->m_bank_receipt->get_BR_by_number($JournalH_Code)->row();
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data["PRJCODE"] 				= $PRJCODE;
			$data['default']['BR_NUM'] 		= $getpurreq->BR_NUM;
			$data['default']['BR_CODE'] 	= $getpurreq->BR_CODE;
			$data['default']['BR_DATE']		= $getpurreq->BR_DATE;
			$data['default']['BR_TYPE'] 	= $getpurreq->BR_TYPE;
			$data['default']['BR_RECTYPE'] 	= $getpurreq->BR_RECTYPE;
			$data['default']['BR_CURRID'] 	= $getpurreq->BR_CURRID;
			$data['default']['BR_CURRCONV'] = $getpurreq->BR_CURRCONV;
			$data['default']['Acc_ID'] 		= $getpurreq->Acc_ID;
			$data['default']['BR_PAYFROM'] 	= $getpurreq->BR_PAYFROM;
			$data['default']['BR_CHEQNO'] 	= $getpurreq->BR_CHEQNO;
			$data['BR_CHEQNO'] 				= $getpurreq->BR_CHEQNO;
			$data['default']['BR_DOCTYPE']	= $getpurreq->BR_DOCTYPE;
			$data['default']['BR_STAT']		= $getpurreq->BR_STAT;
			$data['default']['BR_TOTAM'] 	= $getpurreq->BR_TOTAM;
			$data['default']['BR_TOTAM_PPn']= $getpurreq->BR_TOTAM_PPn;
			$data['default']['BR_NOTES'] 	= $getpurreq->BR_NOTES;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			$data['backURL'] 	= site_url('c_finance/c_br180c2cd0d/gall180c2c04a0br/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN149';
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
			
			$this->load->view('v_finance/v_bank_receipt/v_inb_bank_receipt_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_finance/m_bank_receipt/m_bank_receipt', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$CB_LASTUPD		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$JournalH_Code	= $this->input->post('BR_NUM');
			$BR_NUM	 		= $this->input->post('BR_NUM');
			$BR_CODE	 	= $this->input->post('BR_CODE');
			$PRJCODE	 	= $this->input->post('PRJCODE');
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			$BR_CURRID	 	= $this->input->post('BR_CURRID');
			$BR_CURRCONV 	= $this->input->post('BR_CURRCONV');
			$Acc_ID	 		= $this->input->post('selAccount');
			$BR_ACCID 		= $this->input->post('selAccount');
			$BR_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('BR_DATE'))));
			$accYr			= date('Y', strtotime($BR_DATE));
			$BR_TYPE		= 'BR';
			$BR_RECTYPE	 	= $this->input->post('BR_RECTYPE');

			$BR_PAYFROM	 	= $this->input->post('BR_PAYFROM');
			$BR_PAYEE	 	= $this->input->post('BR_PAYFROM');
			/*$BR_CHEQNO	 	= $this->input->post('BR_CHEQNO');
				$BGDate		= '';
				$sqlBG		= "SELECT BGDate FROM tbl_bgheader WHERE BGNumber = '$BR_CHEQNO'";
				$resBG		= $this->db->query($sqlBG)->result();
				foreach($resBG as $rowBG) :
					$BGDate = $rowBG->BGDate;
				endforeach;
			$BR_CHEQDAT		= $BGDate;*/
			$BR_DOCTYPE		= 'PINV';
			$BR_TOTAM	 	= $this->input->post('BR_TOTAM');
			$BR_TOTAM_PPn 	= $this->input->post('BR_TOTAM_PPn');
			// $BR_NOTES	 	= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$BR_NOTES	 	= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$BR_STAT		= $this->input->post('BR_STAT');
			$PINV_OWNER		= $this->input->post('BR_PAYFROM');
			$BR_APPSTAT		= 0;
			$BR_CREATER		= $DefEmp_ID;
			$BR_CREATED		= $CB_LASTUPD;
			$Company_ID		= $comp_init;
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
		
			$AH_CODE		= $BR_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= htmlspecialchars($this->input->post('BR_NOTES'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$PR_MEMO		= '';
			$AH_ISLAST		= $this->input->post('IS_LAST');

			$SPLCODE 		= $PINV_OWNER;
			$SPLDESC 		= "";
			$ACC_NUM 		= "";
			if($BR_RECTYPE == 'DP' || $BR_RECTYPE == 'PRJ')
			{
				$ACC_NUM		= "110430";
				$sqlL_D			= "SELECT own_Name, own_ACC_ID AS ACC_ID FROM tbl_owner WHERE own_Code = '$PINV_OWNER'";
				$resL_D = $this->db->query($sqlL_D)->result();					
				foreach($resL_D as $rowL_D):
					$SPLDESC	= htmlspecialchars($rowL_D->own_Name, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					$ACC_NUM	= $rowL_D->ACC_ID;
				endforeach;
			}
			elseif($BR_RECTYPE == 'PPD' || $BR_RECTYPE == 'OTH')
			{
				$s_emp			=  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PINV_OWNER'
									UNION
									SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PINV_OWNER'";
				$r_emp 			= $this->db->query($s_emp)->result();
				foreach($r_emp as $rw_emp) :
					$SPLDESC	= htmlspecialchars($rw_emp->EMP_NAME, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				endforeach;
			}
			
			if($BR_STAT == 3)
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
			
					$inBankRec 		= array('BR_UPDATER' 	=> $DefEmp_ID,
											'CB_LASTUPD' 	=> $CB_LASTUPD,
											'BR_STAT' 		=> 7);
					$this->m_bank_receipt->update($BR_NUM, $inBankRec);
				// END : SAVE APPROVE HISTORY
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "BR_NUM",
											'DOC_CODE' 		=> $BR_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_br_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($AH_ISLAST == 1)
				{
					$inBankRec 		= array('BR_UPDATER' 	=> $DefEmp_ID,
											'CB_LASTUPD' 	=> $CB_LASTUPD,
											'BR_STAT' 		=> $BR_STAT);
					$this->m_bank_receipt->update($JournalH_Code, $inBankRec);

					$newNo 		= 0;
					$totPaid 	= 0;
					if(isset($_POST['data']))
					{
						foreach($_POST['data'] as $d)
						{
							$newNo			= $newNo + 1;
							$DocumentNo		= $d['DocumentNo'];		// Realization No. 
							$DocumentRef	= $d['DocumentRef'];	// Invoice No.
							$Inv_Amount		= $d['Inv_Amount'];		// Invoice Amount
							$Inv_Amount_PPn	= $d['Inv_Amount_PPn'];	// Invoice Amount PPn
							$GInv_Amount	= $d['GInv_Amount'];	// Invoice TOTAL
							$Amount			= $d['Amount'];			// Realization Amount
							$Amount_PPn		= $d['Amount_PPn'];		// Realization Amount PPn
							$GAmount		= $d['GAmount'];		// Realization TOTAL

							$totPaid 		= $totPaid + $GAmount;
							
							if($newNo == 1)
								$collINV	= $DocumentRef;
							else
								$collINV	= "$collINV, $DocumentRef";
							
							if($BR_RECTYPE == 'SAL')
							{
								$this->m_bank_receipt->updateSRINV($PRJCODE, $DocumentNo, $DocumentRef, $Inv_Amount, $Inv_Amount_PPn, $Amount, $Amount_PPn, $GAmount, $BR_NUM);
							}
							elseif($BR_RECTYPE == 'PPD')
							{
								// $sql0		= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = Journal_AmountReal + $GAmount,
								// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
								// 				WHERE JournalH_Code = '$DocumentRef'";
								// $this->db->query($sql0);

								// $sql1		= "UPDATE tbl_journalheader SET Journal_AmountReal = Journal_AmountReal + $GAmount,
								// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
								// 				WHERE JournalH_Code = '$DocumentRef'";
								// $this->db->query($sql1);
							}
							else
							{
								$this->m_bank_receipt->updatePRINV($PRJCODE, $DocumentNo, $DocumentRef, $Inv_Amount, $Inv_Amount_PPn, $Amount, $Amount_PPn, $GAmount, $BR_NUM);
							}
						}
					}

					$this->load->model('m_journal/m_journal', '', TRUE);
					// JIKA TASK REQUEST MS.201962000018 disetujui

					$totD 		= 0;
					$totK 		= 0;
					if($BR_RECTYPE != 'OTH' && isset($_POST['dataACC']))
					{
						$totD 	= 0;
						$totK 	= 0;
						$jrnRow = 0;
						foreach($_POST['dataACC'] as $d)
						{
							$jrnRow 		= $jrnRow+1;
							$Acc_Id 		= $d['Acc_Id'];
							$ITM_PRICE 		= $d['JournalD_Amount'];
							$Journal_DK		= $d['Journal_DK'];
							if($Journal_DK == 'D')
							{
								$Base_D 	= $d['JournalD_Amount'];
								$totD 		= $totD+$Base_D;
							}
							else
							{
								$Base_K 	= $d['JournalD_Amount'];
								$totK 		= $totK+$Base_K;
							}
						}
					}

					if($BR_RECTYPE == 'DP') // IF DP PAYMENT
					{
						// START : JOURNAL HEADER
							$collDOC1	= '';
							$collDOC2	= '';
							if(isset($_POST['data']))
							{
								foreach($_POST['data'] as $d)
								{
									$DocumentNo	= $d['DocumentNo'];
									$invNum		= $d['DocumentRef'];
									$collDOC1	= $collDOC1.$DocumentNo;
									$collDOC2	= $collDOC2.$invNum;
								}
							}
							else
							{
								$DocumentNo	= "";
								$invNum		= "";
								$collDOC1	= "";
								$collDOC2	= "";
							}

							$REF_DESC 		= "";
							$PINV_MANNO 	= "";
							$PINV_KWITNO 	= "";
							$PINV_INVOICE 	= "";
							$s_INV			=  "SELECT PINV_MANNO, PINV_KWITNO, PINV_INVOICE FROM tbl_projinv_header WHERE PINV_CODE = '$DocumentNo'";
							$r_INV 			= $this->db->query($s_INV)->result();
							foreach($r_INV as $rw_INV) :
								$PINV_MANNO		= $rw_INV->PINV_MANNO;
								$PINV_KWITNO	= $rw_INV->PINV_KWITNO;
								$PINV_INVOICE	= $rw_INV->PINV_INVOICE;
								$REF_DESC 		= "No. Jurnal : $PINV_MANNO. Kwit. : $PINV_KWITNO. Inv. : $PINV_INVOICE";
							endforeach;
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $collDOC1;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collDOC2;
							$RefType		= 'DP';
							$PRJCODE		= $PRJCODE;
							$PERSL_EMPID 	= $BR_PAYFROM;
							$SPLCODE 		= $BR_PAYFROM;
							//$SPLDESC	 	= '';
							
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'Manual_No'			=> $BR_CODE,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// END : JOURNAL HEADER
						
						// START : JOURNAL DETAIL
							$ACC_NUM	= '';
							$sqlL_D		= "SELECT ACC_ID_RDP AS ACC_ID FROM tglobalsetting";
							$resL_D 	= $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
							endforeach;
							
							$sqlCL_D	= "tbl_owner WHERE own_Code = '$PINV_OWNER'";
							$resCL_D	= $this->db->count_all($sqlCL_D);
							if($resCL_D > 0)
							{
								/*$sqlL_D	= "SELECT own_ACC_ID AS ACC_ID FROM tbl_owner 
											WHERE own_Code = '$PINV_OWNER'";
								$resL_D = $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ACC_NUM	= $rowL_D->ACC_ID;
								endforeach;*/
								$ACC_NUM		= "110430";
								$sqlL_D			= "SELECT own_ACC_ID AS ACC_ID FROM tbl_owner WHERE own_Code = '$PINV_OWNER'";
								$resL_D = $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ACC_NUM	= $rowL_D->ACC_ID;
								endforeach;
							}
							else
							{
								$ACC_NUM		= "110430";
							}
							
							if($ACC_NUM == '')
							{
								$ACC_NUM	= "XXXX_RDP";
							}
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $DocumentNo;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collINV;
							$RefType		= 'BR';
							$JSource		= 'PINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $DocumentNo;
							$ACC_ID 		= $Acc_ID;		// Bank Account
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $GAmount;
							$ITM_DISC 		= 0;
							$TAXCODE1 		= "TAX01";
							$TAXPRICE1		= $BR_TOTAM_PPn;
							$TRANS_CATEG	= "BRDP~$ACC_NUM";
							
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'oth_reason' 		=> $BR_NOTES,
												'Ref_Number'		=> $BR_CODE,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC,
												'REF_DESC' 			=> $REF_DESC,
												'Manual_No' 		=> $BR_CODE,
												'PINV_MANNO' 		=> $PINV_MANNO,
												'PINV_KWITNO' 		=> $PINV_KWITNO,
												'PINV_INVOICE' 		=> $PINV_INVOICE,
												'totD'				=> $totD,
												'totK'				=> $totK);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL
					}
					elseif($BR_RECTYPE == 'SAL')
					{
						// START : JOURNAL HEADER
							$collDOC1	= '';
							$collDOC2	= '';
							if(isset($_POST['data']))
							{
								foreach($_POST['data'] as $d)
								{
									$DocumentNo	= $d['DocumentNo'];
									$invNum		= $d['DocumentRef'];
									$collDOC1	= $collDOC1.$DocumentNo;
									$collDOC2	= $collDOC2.$invNum;
								}
							}
							else
							{
								$DocumentNo	= "";
								$invNum		= "";
								$collDOC1	= "";
								$collDOC2	= "";
							}
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $collDOC1;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collDOC2;
							$RefType		= 'BR';
							$PRJCODE		= $PRJCODE;
							$PERSL_EMPID 	= $BR_PAYFROM;
							$SPLCODE 		= $BR_PAYFROM;
							$SPLDESC	 	= '';
							
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'Manual_No'			=> $BR_CODE,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// END : JOURNAL HEADER
						
						// START : JOURNAL DETAIL
							$CUST_CAT		= '';
							$sqlCCAT     	= "SELECT CUST_CAT FROM tbl_customer WHERE CUST_CODE = '$BR_PAYFROM'";
	                        $resCCAT     	= $this->db->query($sqlCCAT)->result();
	                        foreach($resCCAT as $rowCCAT) :
	                            $CUST_CAT  	= $rowCCAT->CUST_CAT;
	                        endforeach;

							$sqlL_D	= "SELECT CC_LA_CINV AS ACC_ID FROM tbl_custcat WHERE CUSTC_CODE = '$CUST_CAT'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
							endforeach;
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $DocumentNo;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collINV;
							$RefType		= 'BR';
							$JSource		= 'SINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $DocumentNo;
							$ACC_ID 		= $Acc_ID;		// Bank Account
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $GAmount;
							$ITM_DISC 		= '';
							$TAXCODE1 		= "TAX01";
							$TAXPRICE1		= $BR_TOTAM_PPn;
							$TRANS_CATEG	= "BR~$ACC_NUM";
						
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'oth_reason' 		=> $BR_NOTES,
												'Ref_Number'		=> $BR_CODE);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL
					}
					elseif($BR_RECTYPE == 'PPD')
					{
						// START : JOURNAL HEADER
							$collDOC1	= '';
							$collDOC2	= '';
							if(isset($_POST['data']))
							{
								foreach($_POST['data'] as $d)
								{
									$DocumentNo	= $d['DocumentNo'];
									$invNum		= $d['DocumentRef'];
									$collDOC1	= $collDOC1.$DocumentNo;
									$collDOC2	= $collDOC2.$invNum;
								}
							}
							else
							{
								$DocumentNo	= "";
								$invNum		= "";
								$collDOC1	= "";
								$collDOC2	= "";
							}
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $collDOC1;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collDOC2;
							$RefType		= 'BR';
							$PRJCODE		= $PRJCODE;
							$PERSL_EMPID 	= $BR_PAYFROM;
							$SPLCODE 		= $BR_PAYFROM;
							/*$SPLDESC	 	= $this->db->get_where("tbl_supplier", ["SPLCODE" => $SPLCODE])->row("SPLDESC");*/
							
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'Manual_No'			=> $BR_CODE,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// END : JOURNAL HEADER

						// START : JOURNAL DETAIL
							$TOT_RECEIPT 	= 0;
							$rwDet 			= 0;
							// START : KREDIT
								foreach($_POST['data'] as $d)
								{
									$DocumentNo		= $d['DocumentNo'];
									$GAmount		= $d['GAmount'];
									$TOT_RECEIPT 	= $TOT_RECEIPT + $GAmount;

									$PD_CODE 		= "";
									$sqlL_MN		= "SELECT Manual_No FROM tbl_journalheader_pd WHERE JournalH_Code = '$DocumentNo'";
									$resL_MN 		= $this->db->query($sqlL_MN)->result();					
									foreach($resL_MN as $rowL_MN):
										$PD_CODE	= $rowL_MN->Manual_No;
									endforeach;

									$sqlL_D			= "SELECT Acc_Id, Other_Desc, Manual_No FROM tbl_journaldetail_pd
														WHERE JournalH_Code = '$DocumentNo' AND Journal_DK = 'K'";
									$resL_D 		= $this->db->query($sqlL_D)->result();					
									foreach($resL_D as $rowL_D):
										$rwDet 		= $rwDet+1;
										$ACC_NUM	= $rowL_D->Acc_Id;
										$Manual_No	= $rowL_D->Manual_No;
										if($rwDet == 1)
											$collManNo	= $Manual_No;
										else
											$collManNo	= $collManNo.", $Manual_No";
									endforeach;

									$JournalH_Code	= $JournalH_Code;
									$JournalType	= 'BR';
									$JournalH_Date	= $BR_DATE;
									$Company_ID		= $comp_init;
									$Currency_ID	= 'IDR';
									$DOCSource		= $DocumentNo;
									$LastUpdate		= $CB_LASTUPD;
									$WH_CODE		= $PRJCODE;
									$Refer_Number	= $collINV;
									$RefType		= 'BR';
									$JSource		= 'PINV';
									$PRJCODE		= $PRJCODE;
									
									$ITM_CODE 		= $DocumentNo;
									$ACC_ID 		= $Acc_ID;		// Bank Account
									$ITM_UNIT 		= '';
									$ITM_QTY 		= 1;
									$ITM_PRICE 		= $GAmount;
									$ITM_DISC 		= '';
									$TAXCODE1 		= "TAX01";
									$TAXPRICE1		= $BR_TOTAM_PPn;
									$TRANS_CATEG	= "BR-PD~$ACC_NUM";
								
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
														'TRANS_CATEG' 		=> $TRANS_CATEG,
														'ITM_CODE' 			=> $ITM_CODE,
														'ACC_ID' 			=> $ACC_ID,
														'ITM_UNIT' 			=> $ITM_UNIT,
														'ITM_QTY' 			=> $ITM_QTY,
														'ITM_PRICE' 		=> $ITM_PRICE,
														'ITM_DISC' 			=> $ITM_DISC,
														'TAXCODE1' 			=> $TAXCODE1,
														'TAXPRICE1' 		=> $TAXPRICE1,
														'oth_reason' 		=> $BR_NOTES,
														'Ref_Number'		=> $BR_CODE,
														'PD_CODE'			=> $PD_CODE,
														'SPLCODE' 			=> $SPLCODE,
														'SPLDESC' 			=> $SPLDESC);												
									$this->m_journal->createJournalD($JournalH_Code, $parameters);

									// $sql0		= "UPDATE tbl_journalheader_pd SET Journal_AmountReal = Journal_AmountReal + $GAmount,
									// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
									// 				WHERE JournalH_Code = '$DOCSource'";

									// Update date 16-05-2022 by Iyan:
									$sql0		= "UPDATE tbl_journalheader_pd SET PDPaid_Amount = PDPaid_Amount + $GAmount,
												GEJ_STAT_PD = IF((Journal_AmountReal+PDPaid_Amount) = Journal_Amount, 6, 3),
												GEJ_STAT = IF((Journal_AmountReal+PDPaid_Amount) = Journal_Amount, 6, 3),
												STATDESC = IF(GEJ_STAT = 6 OR GEJ_STAT_PD = 6, 'Closed', 'Approved'),
												STATCOL = IF(GEJ_STAT = 6 OR GEJ_STAT_PD = 6, 'info', 'success')
												WHERE JournalH_Code = '$DOCSource'";
									$this->db->query($sql0);

									// $sql1		= "UPDATE tbl_journalheader SET Journal_AmountReal = Journal_AmountReal + $GAmount,
									// 				GEJ_STAT_PPD = IF((Journal_Amount+PDPaid_Amount) > Journal_AmountReal, 1, IF((Journal_Amount+PDPaid_Amount) < Journal_AmountReal, 2, 0))
									// 				WHERE JournalH_Code = '$DOCSource'";

									// Update date 16-05-2022 by Iyan:
									$sql1		= "UPDATE tbl_journalheader SET PDPaid_Amount = PDPaid_Amount + $GAmount, 
												GEJ_STAT_PD = IF((Journal_AmountReal+PDPaid_Amount) = Journal_Amount, 6, 3)
												WHERE JournalH_Code = '$DOCSource'";
									$this->db->query($sql1);
								}
							// END : KREDIT

							// START : DEBET
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$PRJCODE'
												AND Account_Number = '$Acc_ID' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= htmlspecialchars($rowNm->Account_NameId, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
								endforeach;

								$proj_CodeHO	= $PRJCODE;
								$sqlPRJHO 		= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
								$resPRJHO		= $this->db->query($sqlPRJHO)->result();
								foreach($resPRJHO as $rowPRJHO):
									$proj_CodeHO= $rowPRJHO->PRJCODE_HO;
								endforeach;

								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
												Journal_DK, Other_Desc, oth_reason, Ref_Number,
												Acc_Name, proj_CodeHO, JournalType, LastUpdate, SPLCODE, SPLDESC)
											VALUES ('$JournalH_Code', '$JournalH_Date', '$Acc_ID', '$PRJCODE', 'IDR', $TOT_RECEIPT,
												$TOT_RECEIPT, $TOT_RECEIPT, 'Default', 1, 0, 'D', '$BR_NOTES', '$SPLCODE. Pengembalian PD $collManNo', '$Manual_No',
												'$Acc_Name', '$proj_CodeHO', 'BR', '$AH_APPROVED', '$SPLCODE', '$SPLDESC')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_ID' LIMIT 1";
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
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$TOT_RECEIPT,
																Base_Debet2 = Base_Debet2+$TOT_RECEIPT, BaseD_$accYr = BaseD_$accYr+$TOT_RECEIPT
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_ID'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							// END : DEBET
						// END : JOURNAL DETAIL

						$sqlUpdH	= "UPDATE tbl_journalheader SET JournalH_Desc = '$BR_NOTES',
											PERSL_EMPID = '$BR_PAYFROM', acc_number = '$Acc_ID', proj_CodeHO = '$proj_CodeHO',
											Journal_Amount = $TOT_RECEIPT, GJournal_Total = $TOT_RECEIPT,
											STATDESC = 'Approved', STATCOL = 'success'
										WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
						$this->db->query($sqlUpdH);
					}
					elseif($BR_RECTYPE == 'OTH')
					{
						// START : JOURNAL HEADER
							$collDOC1	= '';
							$collDOC2	= '';
							$collINV 	= "";
							if(isset($_POST['data']))
							{
								foreach($_POST['data'] as $d)
								{
									$DocumentNo	= $d['DocumentNo'];
									$invNum		= $d['DocumentRef'];
									$collDOC1	= $collDOC1.$DocumentNo;
									$collDOC2	= $collDOC2.$invNum;
								}
							}
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $collDOC1;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collDOC2;
							$RefType		= 'BR';
							$PRJCODE		= $PRJCODE;
							$PERSL_EMPID 	= $BR_PAYFROM;
							$SPLCODE 		= $BR_PAYFROM;
							$SPLDESC	 	= '';
							
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'Manual_No'			=> $BR_CODE,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// END : JOURNAL HEADER
						
						// START : JOURNAL DETAIL
							if(isset($_POST['dataACC']))
							{
								$jrnRow 		= 0;
								$totBR_AMN 		= 0;
								$totBR_D 		= 0;
								$totBR_K 		= 0;
								foreach($_POST['dataACC'] as $d)
								{
									$jrnRow 		= $jrnRow+1;
									$Acc_Id 		= $d['Acc_Id'];
									$Acc_Name 		= htmlspecialchars($d['JournalD_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									$Base_Kredit	= $d['JournalD_Amount'];
									// $Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									$Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									// $Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									$Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									$proj_CodeHO	= $d['proj_CodeHO'];
									$PRJPERIOD		= $d['PRJPERIOD'];
									$Journal_DK		= $d['Journal_DK'];

									if($Journal_DK == 'D')
										$totBR_D 	= $totBR_D + $Base_Kredit;
									else
										$totBR_K 	= $totBR_K + $Base_Kredit;

									$JournalH_Code	= $JournalH_Code;
									$JournalType	= 'BR';
									$JournalH_Date	= $BR_DATE;
									$Company_ID		= $comp_init;
									$Currency_ID	= 'IDR';
									$DOCSource		= $collDOC1;
									$LastUpdate		= $CB_LASTUPD;
									$WH_CODE		= $PRJCODE;
									$Refer_Number	= $collINV;
									$RefType		= 'BR';
									$JSource		= 'PINV';
									$PRJCODE		= $PRJCODE;
									
									$ITM_CODE 		= $collDOC1;
									$ACC_ID 		= $Acc_Id;
									$ITM_UNIT 		= '';
									$ITM_QTY 		= 1;
									$ITM_PRICE 		= $Base_Kredit;
									$ITM_DISC 		= '';
									$TAXCODE1 		= "";
									$TAXPRICE1		= $BR_TOTAM_PPn;
									$TRANS_CATEG	= "BR-OTH";
								
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
														'TRANS_CATEG' 		=> $TRANS_CATEG,
														'ITM_CODE' 			=> $ITM_CODE,
														'ACC_ID' 			=> $Acc_Id,
														'Acc_Name' 			=> $Acc_Name,
														'Base_Kredit' 		=> $Base_Kredit,
														'Ref_Number' 		=> $Ref_Number,
														'Other_Desc' 		=> $Other_Desc,
														'proj_CodeHO' 		=> $proj_CodeHO,
														'PRJPERIOD' 		=> $PRJPERIOD,
														'BR_ACCID' 			=> $BR_ACCID,
														'ITM_UNIT' 			=> $ITM_UNIT,
														'ITM_QTY' 			=> $ITM_QTY,
														'ITM_PRICE' 		=> $Base_Kredit,
														'ITM_DISC' 			=> $ITM_DISC,
														'TAXCODE1' 			=> $TAXCODE1,
														'TAXPRICE1' 		=> $TAXPRICE1,
														'oth_reason' 		=> $BR_NOTES,
														'Ref_Number'		=> $BR_CODE,
														'SPLCODE' 			=> $SPLCODE,
														'SPLDESC' 			=> $SPLDESC,
														'Journal_DK' 		=> $Journal_DK);												
									$this->m_journal->createJournalD($JournalH_Code, $parameters);

									$totBR_AMN 		= $totBR_K - $totBR_D;
								}

								// ------------------------------- START : D E B E T  KAS/BANK -------------------------------

									$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$PRJCODE'
													AND Account_Number = '$BR_ACCID' LIMIT 1";
									$resNm		= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= htmlspecialchars($rowNm->Account_NameId, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
									endforeach;

									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$BR_ACCID'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);					
									if($resCGEJ == 0)
									{
										$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect,
														ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
														proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, SPLCODE, SPLDESC) VALUES
														('$JournalH_Code', '$BR_ACCID', '', 'BR', '$PRJCODE',
														'IDR', $totBR_AMN, $totBR_AMN, $totBR_AMN, 1, 1, 
														1, '$totBR_AMN', '$Ref_Number', '$SPLDESC. $AH_NOTES', 'D', 'BR', 0, 
														'$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, '$SPLCODE', '$SPLDESC')";
										$this->db->query($insSQL);
									}
									else
									{
										// START : UPDATE Journal Detail - Debit
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$totBR_AMN,
																Base_Debet = Base_Debet+$totBR_AMN, COA_Debet = COA_Debet+$totBR_AMN
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$BR_ACCID'";
											$this->db->query($sqlUpdCOAD);
										// END : UPDATE Journal Detail - Debit
									}
									
									// START : Update to COA - Debit
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$BR_ACCID' LIMIT 1";
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
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$totBR_AMN, 
																	Base_Debet2 = Base_Debet2+$totBR_AMN, BaseK_$accYr = BaseK_$accYr+$totBR_AMN
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$BR_ACCID'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
							}
						// ------------------------------- START : D E B E T  KAS/BANK -------------------------------
					}
					else
					{
						// START : JOURNAL HEADER
							$collDOC1	= '';
							$collDOC2	= '';
							if(isset($_POST['data']))
							{
								foreach($_POST['data'] as $d)
								{
									$DocumentNo	= $d['DocumentNo'];
									$invNum		= $d['DocumentRef'];
									$collDOC1	= $collDOC1.$DocumentNo;
									$collDOC2	= $collDOC2.$invNum;
								}
							}
							else
							{
								$DocumentNo	= "";
								$invNum		= "";
								$collDOC1	= "";
								$collDOC2	= "";
							}
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $collDOC1;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collDOC2;
							$RefType		= 'BR';
							$PRJCODE		= $PRJCODE;
							$PERSL_EMPID 	= $BR_PAYFROM;
							$SPLCODE 		= $BR_PAYFROM;
							$SPLDESC	 	= '';
							
							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $Company_ID,
												'Source'			=> $DOCSource,
												'Emp_ID'			=> $DefEmp_ID,
												'LastUpdate'		=> $LastUpdate,	
												'KursAmount_tobase'	=> 1,
												'WHCODE'			=> $WH_CODE,
												'Reference_Number'	=> $Refer_Number,
												'RefType'			=> $RefType,
												'PRJCODE'			=> $PRJCODE,
												'Manual_No'			=> $BR_CODE,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);
							$this->m_journal->createJournalH($JournalH_Code, $parameters);
						// END : JOURNAL HEADER
						
						// START : JOURNAL DETAIL
							$sqlCL_D	= "tbl_owner WHERE own_Code = '$PINV_OWNER'";
							$resCL_D	= $this->db->count_all($sqlCL_D);
							if($resCL_D > 0)
							{
								$sqlL_D	= "SELECT own_ACC_ID AS ACC_ID FROM tbl_owner 
											WHERE own_Code = '$PINV_OWNER'";
								$resL_D = $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ACC_NUM	= $rowL_D->ACC_ID;
								endforeach;
							}
							else
							{
								$ACC_NUM		= "110430";
							}
							
							$JournalH_Code	= $JournalH_Code;
							$JournalType	= 'BR';
							$JournalH_Date	= $BR_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$DOCSource		= $DocumentNo;
							$LastUpdate		= $CB_LASTUPD;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= $collINV;
							$RefType		= 'BR';
							$JSource		= 'PINV';
							$PRJCODE		= $PRJCODE;
							
							$ITM_CODE 		= $DocumentNo;
							$ACC_ID 		= $Acc_ID;		// Bank Account
							$ITM_UNIT 		= '';
							$ITM_QTY 		= 1;
							$ITM_PRICE 		= $GAmount;
							$ITM_DISC 		= '';
							$TAXCODE1 		= "TAX01";
							$TAXPRICE1		= $BR_TOTAM_PPn;
							$TRANS_CATEG	= "BR~$ACC_NUM";
						
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'oth_reason' 		=> $BR_NOTES,
												'Ref_Number'		=> $BR_CODE,
												'totD'				=> $totD,
												'totK'				=> $totK);												
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL
					}
					
					if($BR_RECTYPE != 'OTH' && isset($_POST['dataACC']))
					{
						$jrnRow 		= 0;
						foreach($_POST['dataACC'] as $d)
						{
							$jrnRow 		= $jrnRow+1;
							$Acc_Id 		= $d['Acc_Id'];
							// $Acc_Name 		= htmlspecialchars($d['JournalD_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
							$Acc_Name 		= htmlspecialchars($d['JournalD_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
							$ITM_PRICE 		= $d['JournalD_Amount'];
							// $Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
							$Ref_Number		= htmlspecialchars($d['Ref_Number'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
							// $Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
							$Other_Desc		= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);

							$Acc_Name 		= "-";
							$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$PRJCODE'
												AND Account_Number = '$Acc_Id' LIMIT 1";
							$resNm			= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= htmlspecialchars($rowNm->Account_NameId, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
							endforeach;

							$Journal_DK		= $d['Journal_DK'];
							if($Journal_DK == 'D')
							{
								$Base_D 		= $d['JournalD_Amount'];
								$Base_K 		= 0;

								$proj_CodeHO	= $PRJCODE;
								$sqlPRJHO 		= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
								$resPRJHO		= $this->db->query($sqlPRJHO)->result();
								foreach($resPRJHO as $rowPRJHO):
									$proj_CodeHO= $rowPRJHO->PRJCODE_HO;
								endforeach;

								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, Acc_Id, Acc_Name, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, ITM_VOLM, ITM_PRICE, 
												Journal_DK, Other_Desc, oth_reason, Ref_Number, proj_CodeHO, Journal_Type, JournalType,
												GEJ_STAT, Manual_No, LastUpdate)
											VALUES ('$JournalH_Code', '$BR_DATE', '$Acc_Id', '$Acc_Name', '$PRJCODE', 'IDR',
												$Base_D, $Base_D, $Base_D, 'Default', 1, 0, 1, $Base_D,
												'$Journal_DK', '$Other_Desc', '$Other_Desc', '$Ref_Number','$proj_CodeHO', 'BR', 'BR',
												3, '$BR_CODE', '$AH_APPROVED')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
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
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_D,
																Base_Debet2 = Base_Debet2+$Base_D, BaseD_$accYr = BaseD_$accYr+$Base_D
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit

								// JIKA HANYA MENAMBAHKAN 1 BARIS DI DETIAL AKUN, MAKA HARUS ADA LAWANNYA
								// BAGIAN KREDIT TIDKA PERLU MEMBENTUK JURNAL. BERDASARKAN DISKUSI DNEGAN IBU LYTA TGL 2022-08-23
								// SAAT DISKUSI TTG PPH PADA PENERIMAAN KAS/BANK DARI FAKTUR PROYEK. PPH TSB TIDAK BOLEH DILAWANKAN KEMBALI DGN KAS/BANK 
									/* START : 2022-08-23
										$Acc_Name 		= "-";
										$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$PRJCODE'
															AND Account_Number = '$BR_ACCID' LIMIT 1";
										$resNm			= $this->db->query($sqlNm)->result();
										foreach($resNm as $rowNm):
											$Acc_Name	= $rowNm->Account_NameId;
										endforeach;

										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, Acc_Id, Acc_Name, proj_Code, Currency_id, 
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, ITM_VOLM, ITM_PRICE, 
														Journal_DK, Other_Desc, oth_reason, Ref_Number, proj_CodeHO, Journal_Type, JournalType,
														GEJ_STAT, Manual_No, LastUpdate)
													VALUES ('$JournalH_Code', '$BR_DATE', '$BR_ACCID', '$Acc_Name', '$PRJCODE', 'IDR',
														$Base_D, $Base_D, $Base_D, 'Default', 1, 0, 1, $Base_D,
														'$Journal_DK', '$Other_Desc', '$Other_Desc', '$Ref_Number','$proj_CodeHO', 'BR', 'BR',
														3, '$BR_CODE', '$AH_APPROVED')";
										$this->db->query($sqlGEJDD);

										// START : Update to COA - Debit
											$isHO			= 0;
											$syncPRJ		= '';
											$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
																WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$BR_ACCID' LIMIT 1";
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
													$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_D, 
																		Base_Kredit2 = Base_Kredit2+$Base_D, BaseK_$accYr = BaseK_$accYr+$Base_D
																	WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$BR_ACCID'";
													$this->db->query($sqlUpdCOA);
												}
											}
										// END : Update to COA - Debit
									END : 2022-08-23 */
							}
							else
							{
								$Base_D 		= 0;
								$Base_K 		= $d['JournalD_Amount'];

								$proj_CodeHO	= $PRJCODE;
								$sqlPRJHO 		= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
								$resPRJHO		= $this->db->query($sqlPRJHO)->result();
								foreach($resPRJHO as $rowPRJHO):
									$proj_CodeHO= $rowPRJHO->PRJCODE_HO;
								endforeach;

								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, Acc_Id, Acc_Name, proj_Code, Currency_id, 
												JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, ITM_VOLM, ITM_PRICE, 
												Journal_DK, Other_Desc, oth_reason, Ref_Number, proj_CodeHO, Journal_Type, JournalType,
												GEJ_STAT, Manual_No, LastUpdate)
											VALUES ('$JournalH_Code', '$BR_DATE', '$Acc_Id', '$Acc_Name', '$PRJCODE', 'IDR',
												$Base_K, $Base_K, $Base_K, 'Default', 1, 0, 1, $Base_K,
												'$Journal_DK', '$Other_Desc', '$Other_Desc', '$Ref_Number','$proj_CodeHO', 'BR', 'BR',
												3, '$BR_CODE', '$AH_APPROVED')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
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
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_K, 
																Base_Kredit2 = Base_Kredit2+$Base_K, BaseK_$accYr = BaseK_$accYr+$Base_K
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							}
						}
					}
					
					// SYNC STAT AND DATE
						$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
											A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
										WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
						$this->db->query($sqlSYNCHD);
			
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "BR_NUM",
												'DOC_CODE' 		=> $BR_NUM,
												'DOC_STAT' 		=> $BR_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'	=> $completeName,
												'TBLNAME'		=> "tbl_br_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			}
			elseif($BR_STAT == 4 || $BR_STAT == 5)
			{
				$inBankRec 		= array('BR_UPDATER' 	=> $DefEmp_ID,
										'CB_LASTUPD' 	=> $CB_LASTUPD,
										'BR_STAT' 		=> $BR_STAT);
				$this->m_bank_receipt->update($BR_NUM, $inBankRec);

				if(count($_POST['data']) > 0)
				{
					foreach($_POST['data'] as $d)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$BR_VAL_M 	= $d['GAmount'];
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $BR_DATE,
												'FVAL'		=> $BR_VAL_M,
												'FNAME'		=> "BR_VAL_M");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				}

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "BR_NUM",
											'DOC_CODE' 		=> $BR_NUM,
											'DOC_STAT' 		=> $BR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_br_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				$inBankRec 		= array('BR_UPDATER' 	=> $DefEmp_ID,
										'CB_LASTUPD' 	=> $CB_LASTUPD,
										'BR_STAT' 		=> $BR_STAT);
				$this->m_bank_receipt->update($BR_NUM, $inBankRec);	
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "BR_NUM",
											'DOC_CODE' 		=> $BR_NUM,
											'DOC_STAT' 		=> $BR_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_br_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN148';
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
			
			$url	= site_url('c_finance/c_br180c2cd0d/inb180c2g/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function prntD()
	{
		$this->load->model('m_production/m_prodprocess', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$JournalH_Code		= $_GET['id'];
		$JournalH_Code		= $this->url_encryption_helper->decode_url($JournalH_Code);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
      		$proj_Currency  		= 'IDR';

			$getbankreceipt			= $this->m_bank_receipt->get_BR_by_number($JournalH_Code)->row();
																		
			$data['JournalH_Code'] 	= $getbankreceipt->JournalH_Code;
			$data['BR_NUM'] 		= $getbankreceipt->BR_NUM;
      		$BR_NUM             	= $getbankreceipt->BR_NUM;
			$data['BR_CODE'] 		= $getbankreceipt->BR_CODE;
			$data['PRJCODE'] 		= $getbankreceipt->PRJCODE;
      		$PRJCODE            	= $getbankreceipt->PRJCODE;
      		$data['BR_DOCTYPE']     = $getbankreceipt->BR_DOCTYPE;
			$data['BR_DATE']		= $getbankreceipt->BR_DATE;
      		$data['BR_DATEY']   	= date('Y', strtotime($getbankreceipt->BR_DATE));
      		$data['BR_DATEM']   	= date('m', strtotime($getbankreceipt->BR_DATE));
			$data['BR_CATEG'] 		= $getbankreceipt->BR_CATEG;
			$data['BR_TYPE'] 		= $getbankreceipt->BR_TYPE;
			$data['BR_RECTYPE'] 	= $getbankreceipt->BR_RECTYPE;
			$BR_RECTYPE 			= $getbankreceipt->BR_RECTYPE;
			$data['BR_CURRID'] 		= $getbankreceipt->BR_CURRID;
			$data['Acc_ID'] 		= $getbankreceipt->Acc_ID;
      		$Acc_ID            		= $getbankreceipt->Acc_ID;
			$data['BR_PAYFROM'] 	= $getbankreceipt->BR_PAYFROM;
			$data['BR_PAYEE'] 		= $getbankreceipt->BR_PAYEE;
      		$BR_PAYFROM            	= $getbankreceipt->BR_PAYFROM;
			$data['BR_STAT']		= $getbankreceipt->BR_STAT;
			$data['BR_TOTAM'] 		= $getbankreceipt->BR_TOTAM;
			$data['BR_TOTAM_PPn']	= $getbankreceipt->BR_TOTAM_PPn;
			$data['BR_NOTES'] 		= $getbankreceipt->BR_NOTES;
			$data['Patt_Year'] 		= $getbankreceipt->Patt_Year;
			$data['Patt_Month'] 	= $getbankreceipt->Patt_Month;
			$data['Patt_Date'] 		= $getbankreceipt->Patt_Date;
			$data['Patt_Number']	= $getbankreceipt->Patt_Number;

			if($BR_RECTYPE == 'PD' || $BR_RECTYPE == 'PPD')
				$this->load->view('v_finance/v_bank_receipt/v_print_breceipt', $data);
			else
				$this->load->view('v_finance/v_bank_receipt/v_print_breceipt_oth', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}