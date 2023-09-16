<?php
/*  
	* Author		= Hendar Permana
	* Create Date	= 26 Mei 2017
	* Updated		= Dian Hermanto - 11 November 2017
	* File Name		= C_p180c21o.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_p180c21o extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
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
	
 	function index() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_p180c21o/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN019';
				$data["MenuApp"] 	= 'MN020';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN019';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_p180c21o/gl180c21po/?id=";
			
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

	function gl180c21po() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
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
				$data["url_search"] = site_url('c_purchase/c_p180c21o/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_purchase_po->count_all_num_rowsPO($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_po->get_all_PO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN019';
				$data["MenuCode"] 	= 'MN019';
				$data["MenuApp"] 	= 'MN020';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_purchase/c_p180c21o/a44p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_p180c21o/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
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
			
			$this->load->view('v_purchase/v_purchase_po/v_po_list', $data);
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
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData() // GOOD 
	{
		$PRJCODE	= $_GET['id'];
		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl 	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl 	= $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Description')$Description = $LangTransl;
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				if($PO_NOTES == '') $PO_NOTES = "-";
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= $dataI['SPLDESC'];
				/*$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;*/
				
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];
				$PO_STAT	= $dataI['PO_STAT'];
				
				$CollID		= "$PRJCODE~$PO_NUM";
				if($PO_TYPE == 1)
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				}
				else
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($CollID));		
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($PO_NUM));							
				}
				
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDel		= site_url('c_purchase/c_p180c21o/d3l180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";

				// CEK IR
					$sqlIRC	= "tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9)";
					$resIRC = $this->db->count_all($sqlIRC);

					if($resIRC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
				
				if($PO_STAT == 1 || $PO_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PO_STAT == 2 || $PO_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Batal' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN020';
				$APPL 	= "";
				/*$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.MENU_CODE = '$MNCODE' AND A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";*/
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>".$dataI['PO_CODE']."</div>",
										  	$PO_DATEV,
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-opencart margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
											</div>
											<strong><i class='fa fa-pencil-square-o margin-r-5'></i>$Description</strong>
											<div style='margin-left: 20px; font-style: italic'>
											  	".$PO_NOTES."
											</div>
										  	",
										  	"<div style='white-space:nowrap'>".$dataI['PR_CODE']."</div>",
										 	$PO_PLANIRV,
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

  	function get_AllDataPOGRP() // GOOD 
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$PR_NUM			= $_GET['SPNO'];
		$PO_STAT		= $_GET['DSTAT'];
		$PO_CATEG		= $_GET['SRC'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$PRJHO 			= "";
		$s_HO 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_HO 			= $this->db->query($s_HO)->result();
		foreach($r_HO as $rw_HO) :
			$PRJHO 	= $rw_HO ->PRJCODE_HO;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl 	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl 	= $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataGRPC($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataGRPL($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				if($PO_NOTES == '') $PO_NOTES = "-";
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));

				$MenuApp	= $dataI['PO_DIVID'];
				$MNCODE		= $dataI['PO_DIVID'];

				$PODIVD 	= "Proyek";
				if($PRJHO = 'AB')
					$PODIVD = "Peralatan";

				if($PRJHO == 'KTR')
				{
					if($MenuApp == 'MN447')
						$PODIVD 	= "Sekretaris";
					elseif($MenuApp == 'MN448')
						$PODIVD 	= "Audit Internal";
					elseif($MenuApp == 'MN449')
						$PODIVD 	= "Corp. Legal";
					elseif($MenuApp == 'MN450')
						$PODIVD 	= "Corp. Adm. Kontrak";
					elseif($MenuApp == 'MN451')
						$PODIVD 	= "QHSSE - SI";
					elseif($MenuApp == 'MN452')
						$PODIVD 	= "Marketing";
					elseif($MenuApp == 'MN453')
						$PODIVD 	= "Operasi";
					elseif($MenuApp == 'MN454')
						$PODIVD 	= "Keuangan";
					elseif($MenuApp == 'MN455')
						$PODIVD 	= "Human Capital";
					elseif($MenuApp == 'MN456')
						$PODIVD 	= "Anak Usaha";
					else
						$PODIVD 	= "--";
				}

				$isPrint	= $dataI['isPrint'];
				if($isPrint == 1)
					$PRINTCOL 	= "success";
				else
					$PRINTCOL 	= "primary";
				
				$SPLDESC	= $dataI['SPLDESC'];
				/*$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;*/
				
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];
				$PO_STAT	= $dataI['PO_STAT'];
				
				$CollID		= "$PRJCODE~$PO_NUM";
				if($PO_TYPE == 1)
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				}
				else
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($CollID));		
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($PO_NUM));							
				}
				
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDel		= site_url('c_purchase/c_p180c21o/d3l180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";

				// CEK IR
					$sqlIRC	= "tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9)";
					$resIRC = $this->db->count_all($sqlIRC);

					if($resIRC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
				
				if($PO_STAT == 1 || $PO_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-".$PRINTCOL." btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PO_STAT == 2 || $PO_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-".$PRINTCOL." btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Batal' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-".$PRINTCOL." btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN020';
				$POCATD = "OP Bahan";
				$FAICON = "fa fa-cubes";
				if($PO_CATEG == 1)
				{
					$MNCODE	= 'MN436';
					$POCATD = "OP Alat";
					$FAICON = "fa fa-steam";
				}

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>".$dataI['PO_CODE']."</strong><br>
												<div style='font-style: italic;'><strong><i class='fa ".$FAICON." margin-r-5'></i>".$POCATD." </strong></div>
											</div>",
											"<div style='white-space:nowrap'>
												<strong>".$PO_DATEV."</strong><br>
												<i class='fa fa-calendar margin-r-5'></i>".$ReceivePlan."
												<strong><div style='margin-left: 17px; font-style: italic'>
												  	".$PO_PLANIRV."
												</div> </strong>
											</div>",
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-money margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
											</div>
											<strong><i class='fa fa-pencil-square-o margin-r-5'></i>$Description</strong>
											<div style='margin-left: 20px; font-style: italic'>
											  	".$PO_NOTES."
											</div>
										  	",
										  	"<strong>".$dataI['PR_CODE']."</strong><br>
									  		<div>
										  		<p class='text-muted'>
										  			<i class='fa fa-user margin-r-5'></i> ".$PODIVD."
										  		</p>
										  	</div>",
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

  	function get_AllDataSH() // GOOD 
	{
		$PRJCODE	= $_GET['id'];
		$ISCLS		= $_GET['ISCLS'];
		
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataSHC($PRJCODE, $ISCLS, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataSHL($PRJCODE, $ISCLS, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= $dataI['SPLDESC'];
				/*$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;*/
				
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];
				$PO_STAT	= $dataI['PO_STAT'];
				
				$CollID		= "$PRJCODE~$PO_NUM";
				if($PO_TYPE == 1)
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				}
				else
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($CollID));		
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($PO_NUM));							
				}
				
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDel		= site_url('c_purchase/c_p180c21o/d3l180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";

				// CEK IR
					$sqlIRC	= "tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9)";
					$resIRC = $this->db->count_all($sqlIRC);

					if($resIRC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
				
				if($PO_STAT == 1 || $PO_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PO_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Batal' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array($dataI['PO_CODE'],
										  $PO_DATEV,
										  $SPLDESC,
										  $dataI['PO_NOTES'],
										  $dataI['PR_CODE'],
										  $PO_PLANIRV,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function get_AllDataITMU() // GOOD
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
			$num_rows 		= $this->m_purchase_po->get_AllDataITMUC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_po->get_AllDataITMUL($PRJCODE, $search, $length, $start, $order, $dir);
								
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

				// GET PO DETAIL utk prosedure upah angkut
					$TOT_POVOL 		= 0;
					$TOT_POAMOUNT	= 0;
					$this->db->select_sum("A.PO_VOLM","TOT_POVOL");
					$this->db->select_sum("(A.PO_VOLM * A.PO_PRICE)","TOT_POAMOUNT");
					$this->db->from("tbl_po_detail A");
					$this->db->join("tbl_po_header B","B.PO_NUM = A.PO_NUM","inner");
					$this->db->where(["B.PRJCODE" => $PRJCODE, "A.ITM_CODE" => $ITM_CODE,
									  "A.JOBCODEID" => $JOBCODEID, "A.ISCOST" => 1, "B.PO_STAT" => 2]);
					$sqlGETPO 		= $this->db->get();
					foreach($sqlGETPO->result() as $rowGETPO):
						$TOT_POVOL 		= $rowGETPO->TOT_POVOL;
						$TOT_POAMOUNT	= $rowGETPO->TOT_POAMOUNT;
					endforeach;

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT + $TOT_POAMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						// $PO_VOLM	= $PO_VOLM;
						$PO_VOLM 	= $PO_VOLM + $TOT_POVOL;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ - $PO_VOLM;
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
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";

					// Update [iyan] - GET ITM_CATEG
                    $ITM_CATEG = $this->db->get_where('tbl_item', ['ITM_CODE' => $ITM_CODE])->row('ITM_CATEG');
                    // End Update [iyan]

				// OTHER SETT
						if($disabledB == 0)
						{
							$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."|".$ITM_CATEG."' onClick='pickThis2(this);'/>";
						}
						else
						{
							$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."|".$ITM_CATEG."' style='display: none' />";
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
			$num_rows 		= $this->m_purchase_po->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_po->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
						$disabledB	= 1;
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
					$JOBDESCHA	= substr("$JOBDESCH", 0, 50);
					$JOBDESCH 	= $JOBDESCHA;
					if($strLENH > 50)
						$JOBDESCH 	= $JOBDESCHA."...";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."' onClick='pickThis2(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."' style='display: none' />";
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
	
	function a44p180c21o_p0() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN019';
			$data["MenuApp"] 	= 'MN020';
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
			$data['form_action']	= site_url('c_purchase/c_p180c21o/add_process');
			$cancelURL				= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
			$data["MenuCode1"] 		= 'MN020';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$data['viewDocPattern'] = $this->m_purchase_po->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
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
	
			$this->load->view('v_purchase/v_purchase_po/v_po_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN019';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				//$PO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$PRJHO			= $this->input->post('PRJHO');
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			$DEPCODE		= $this->input->post('DEPCODE');
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_CATEG 		= $this->input->post('PO_CATEG');
			$PO_TYPE 		= $this->input->post('PO_TYPE');
			$PO_CAT			= 1;								// 1. In Direct, 2. Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_CODE 		= $this->input->post('PR_CODE');			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_PLANIR')))); // Tanggal Terima
			$PO_NOTESIR 	= $this->input->post('PO_NOTESIR');
			$PO_NOTES 		= addslashes($this->input->post('PO_NOTES'));
			$PO_PAYNOTES 	= $this->input->post('PO_PAYNOTES');
			$JOBCODE 		= $this->input->post('JOBCODE');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_DPPER		= $this->input->post('PO_DPPER');
			$PO_DPVAL		= $this->input->post('PO_DPVAL');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');
			$PO_CONTRNO		= $this->input->post('PO_CONTRNO');

			$PO_TAXCODE1 	= $this->input->post('PO_TAXCODE1');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PATTCODE 		= $this->input->post('PATTCODE');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN019',
										'DOCTYPE' 		=> 'PO',
										'DOCNUM' 		=> $PO_NUM,
										'DOCCODE'		=> $PO_CODE,
										'DOCDATE'		=> $PO_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $PO_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE";
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
						$uplFile = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $PO_NUM, "REF_CODE" => $PO_CODE,
									"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
									"UPL_FILENAME" => $file['file_name'], "UPL_FILESIZE" => $file['file_size'],
									"UPL_FILETYPE" => $file['file_type'], "UPL_FILEPATH" => $file['file_path'], 
									"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
						$this->m_purchase_po->uplDOC_TRX($uplFile);
					endforeach;
		
					// $fileUpload = join(", ", $data_upload);
				}
			
			// ============================= End Upload File ========================================== //
			
			$PO_TOTCOST	= 0;
			$PO_TAX 	= 0;
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(PO_ID) AS maxNo FROM tbl_po_detail";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;		
			endforeach;
			foreach($_POST['data'] as $d)
			{
				$maxNo			= $maxNo + 1;
				$d['PO_ID']		= $maxNo;
				$d['PO_NUM']	= $PO_NUM; 
				$d['DEPCODE']	= $DEPCODE;
				$ITMAMOUNT		= $d['PO_COST'];
				$PO_TOTCOST		= $PO_TOTCOST + $ITMAMOUNT;

				// GET HEADER
					$JOBID 		= $d['JOBCODEID'];
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

				$DIVID 	= "";
				if($PRJHO == 'KTR')
				{
					$s_MN 	= "SELECT JOBCOD1 FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_MN	= $this->db->query($s_MN)->result();
					foreach($r_MN as $rw_MN) :
						$DIVID	= $rw_MN->JOBCOD1;
					endforeach;
				}

				$d['JOBPARENT']		= $JOBIDH;
				$d['JOBPARDESC']	= $JOBDSH;
				$d['PO_DIVID']		= $DIVID;

				$ITMCODE			= $d['ITM_CODE'];
				$ITM_CATEG 			= "M";
				$sqlITMNM			= "SELECT ITM_CATEG FROM tbl_item WHERE ITM_CODE = '$ITMCODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resITMNM			= $this->db->query($sqlITMNM)->result();
				foreach($resITMNM as $rowITMNM) :
					$ITM_CATEG		= $rowITMNM->ITM_CATEG;
				endforeach;

				$ISCOST 			= 0;
				if($ITM_CATEG == 'UA')
					$ISCOST 		= 1;

				$d['ISCOST']		= $ISCOST;

				$this->db->insert('tbl_po_detail',$d);

				// CEK TAX
					$TAXPRICE1	= $d['TAXPRICE1'];
					$PO_TAX 	= $PO_TAX + $TAXPRICE1;

				// UPDATE LASTP
					if($PO_STAT == 2)
					{
						$JOBID 	= $d['JOBCODEID'];
						$ITMC  	= $d['ITM_CODE'];
						$LASTP 	= $d['PO_PRICE'];
						$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $LASTP
									WHERE JOBCODEID = '$JOBID' AND ITM_CODE = '$ITMC' AND PRJCODE = '$PRJCODE'";
						//$this->db->query($updLP);
					}

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$JOBID 		= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$DOC_VOLM	= $d['PO_VOLM'];
					$DOC_TOTAL	= $d['PO_COST'];
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PO_DATE,
										'JOBID'		=> $JOBID,
										'ITM_CODE'	=> $ITM_CODE,
										'DOC_VOLM'	=> $DOC_VOLM,
										'DOC_TOTAL'	=> $DOC_TOTAL,
										'VAR_VOL_R'	=> "PO_VOL_R",
										'VAR_VAL_R'	=> "PO_VAL_R");
					$this->m_updash->updJOBP($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL
			}

			if($PO_CATEG == 1)
			{
				$MenuApp 	= "MN436";
			}
			else
			{
				$MenuApp 	= "MN020";
				if($PRJHO == 'KTR')
				{
					$s_DIV 	= "SELECT DISTINCT PO_DIVID FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND PO_DIVID != '' AND PO_DIVID IS NOT NULL";
					$r_DIV 	= $this->db->query($s_DIV)->result();
					foreach($r_DIV as $rw_DIV):
						$MenuAppA 	= $rw_DIV->PO_DIVID;

						if($MenuAppA == 'MN437')
							$MenuApp 	= "MN447";
						elseif($MenuAppA == 'MN438')
							$MenuApp 	= "MN448";
						elseif($MenuAppA == 'MN439')
							$MenuApp 	= "MN449";
						elseif($MenuAppA == 'MN440')
							$MenuApp 	= "MN450";
						elseif($MenuAppA == 'MN441')
							$MenuApp 	= "MN451";
						elseif($MenuAppA == 'MN442')
							$MenuApp 	= "MN452";
						elseif($MenuAppA == 'MN443')
							$MenuApp 	= "MN453";
						elseif($MenuAppA == 'MN444')
							$MenuApp 	= "MN454";
						elseif($MenuAppA == 'MN445')
							$MenuApp 	= "MN455";
						elseif($MenuAppA == 'MN446')
							$MenuApp 	= "MN456";
						else
							$MenuApp 	= "MN020";
					endforeach;
				}
			}

			$AddPO			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_CATEG' 		=> $PO_CATEG,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'SPLCODE' 		=> $SPLCODE,
									'JOBCODE'		=> $JOBCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PR_CODE'		=> $PR_CODE,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_PAYNOTES'	=> $PO_PAYNOTES,
									'PO_NOTESIR' 	=> $PO_NOTESIR,
									'PO_DPPER' 		=> $PO_DPPER,
									'PO_DPVAL' 		=> $PO_DPVAL,
									'PO_TOTCOST' 	=> $PO_TOTCOST,
									'PO_TAXAMN'	 	=> $PO_TAX,
									'PO_TAXCODE1'	=> $PO_TAXCODE1,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_DIVID' 		=> $MenuApp,
									'PO_INVSTAT'	=> 0,
									'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
									'PO_RECEIVCP'	=> $PO_RECEIVCP,
									'PO_SENTROLES'	=> $PO_SENTROLES,
									'PO_REFRENS'	=> $PO_REFRENS,
									'PO_CONTRNO'	=> $PO_CONTRNO,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_purchase_po->add($AddPO); // Insert tb_po_header

			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);

			if($PO_STAT == 2)
			{
				// START : UPDATE FINANCIAL DASHBOARD
					$PO_VAL 	= $PO_TOTCOST;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PO_DATE,
										'FVAL'		=> $PO_VAL,
										'FNAME'		=> "PO_VAL");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD

				$AS_MNCODE 		= $MenuApp;
				if($PO_CATEG == 1)
				{
					$data["MenuApp"] 	= 'MN436';
					$AS_MNCODE 			= 'MN436';
				}
				
				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "PO",
										'AS_MNCODE'		=> $AS_MNCODE,
										'AS_DOCNUM'		=> $PO_NUM,
										'AS_DOCCODE'	=> $PO_CODE,
										'AS_DOCDATE'	=> $PO_DATE,
										'AS_EXPDATE'	=> $PO_DATE);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PO_STAT');			// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
										'DOC_CODE' 		=> $PO_NUM,
										'DOC_STAT' 		=> $PO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_po_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
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

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
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
			
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addDir() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['form_action']	= site_url('c_purchase/c_p180c21o/addDir_process');
			$cancelURL				= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
			$data['viewDocPattern'] = $this->m_purchase_po->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
				$TTR_CATEG		= 'L-DIR';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
	
			$this->load->view('v_purchase/v_purchase_po/v_podir_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addDir_process()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 		= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN019';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$PO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1-D";
			// END - PEMBENTUKAN GENERATE CODE
			
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START : CHECK THE CODE	
				/*$DOC_NUM		= $PO_NUM;
				$DOC_CODE		= $PO_CODE;
				$DOC_DATE		= $PO_DATE;
				$MenuCode 		= 'MN019';
				$TABLE_NAME		= 'tbl_po_header';
				$FIELD_NAME		= 'PO_NUM';
				
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$paramCODE 	= array('DOC_NUM' 		=> $DOC_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'TABLE_NAME' 	=> $TABLE_NAME,
									'FIELD_NAME' 	=> $FIELD_NAME);
				$countCode	= $this->m_updash->count_CODE($paramCODE);
				
				if($countCode > 0)
				{
					$getSetting	= $this->m_updash->getDataDocPat($MenuCode)->row();				
					$PattCode	= $getSetting->Pattern_Code;
					$PattLength	= $getSetting->Pattern_Length;
					$useYear	= $getSetting->useYear;
					$useMonth	= $getSetting->useMonth;
					$useDate	= $getSetting->useDate;
					
					$SettCode	= array('PRJCODE'		=> $PRJCODE,
										'DOC_DATE'		=> $DOC_DATE,
										'TABLE_NAME' 	=> $TABLE_NAME,
										'PattCode' 		=> $PattCode,
										'PattLength' 	=> $PattLength,
										'useYear'		=> $useYear,
										'useMonth'		=> $useMonth,
										'useDate'		=> $useDate);
					$getNewCode	= $this->m_updash->get_NewCode($SettCode);
					$splitCode 	= explode("~", $getNewCode);
					$DOC_NUM	= $splitCode[0];
					if($DOC_CODE == '')
					{
						$DOC_CODE	= $splitCode[1];
					}
					$Patt_Number= $splitCode[2];
				}
				
				$PO_NUM			= $DOC_NUM;
				$PO_CODE		= $DOC_CODE;*/
			// END : CHECK CODE
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_TYPE 		= $this->input->post('PO_TYPE');
			$PO_CAT			= 1;								// Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= "Direct";			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_PLANIR')))); // Tanggal Terima
			$PO_NOTES 		= addslashes($this->input->post('PO_NOTES'));
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$PO_TOTCOST		= $this->input->post('PO_TOTCOST');
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');
			$PO_CONTRNO		= $this->input->post('PO_CONTRNO');
			
			$AddPOD			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,
									'PO_TOTCOST'	=> $PO_TOTCOST,
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,
									'ISDIRECT'		=> 1,
									'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
									'PO_RECEIVCP'	=> $PO_RECEIVCP,
									'PO_SENTROLES'	=> $PO_SENTROLES,
									'PO_REFRENS'	=> $PO_REFRENS,
									'PO_CONTRNO' 	=> $PO_CONTRNO,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_purchase_po->add($AddPOD); // Insert tb_po_header
			
			foreach($_POST['data'] as $d)
			{
				$d['PO_NUM']	= $PO_NUM;
				$this->db->insert('tbl_po_detail',$d);
			}
			
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PO_STAT');			// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "POD",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
										'DOC_CODE' 		=> $PO_NUM,
										'DOC_STAT' 		=> $PO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_po_header");
				$this->m_updash->updateStatus($paramStat);			
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
				$TTR_CATEG		= 'C-DIR';
				
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

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
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
			
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function get_all_PO() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
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
			$data['addURL'] 	= site_url('c_purchase/c_p180c21o/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_p180c21o/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_purchase_po->count_all_num_rowsPO($PRJCODE);
			$data["cData"] 	= $num_rows;
	 
			$data['vData'] = $this->m_purchase_po->get_all_PO($PRJCODE)->result();
			
			$this->load->view('v_purchase/v_purchase_po/v_po_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u77p180c21o_p0() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN019';
			$data["MenuApp"] 	= 'MN020';
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
			$PO_NUM		= $EXTRACTCOL[1];
			
			$getpo_head				= $this->m_purchase_po->get_po_by_number($PO_NUM)->row();
			$PRJCODE				= $getpo_head->PRJCODE;
			$data["MenuCode"] 		= 'MN019';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');

			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			$cancelURL				= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
			$data["MenuCode1"] 		= 'MN020';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
		
			$getPO 							= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['ISDIRECT'] 	= $getPO->ISDIRECT;
			$ISDIRECT						= $getPO->ISDIRECT;
			$data['default']['PO_CATEG'] 	= $getPO->PO_CATEG;
			$PO_CATEG 						= $getPO->PO_CATEG;

			if($PO_CATEG == 1)
			{
				$data["MenuApp"] 			= 'MN436';
				$data["MenuCode1"] 			= 'MN436';
			}

			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DIVID'] 	= $getPO->PO_DIVID;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getPO->DEPCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_TAXCODE1'] = $getPO->PO_TAXCODE1;
			$data['default']['PO_TOTCOST'] 	= $getPO->PO_TOTCOST;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTESIR'] 	= $getPO->PO_NOTESIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PO_NOTES1'] 	= $getPO->PO_NOTES1;
			$data['default']['PO_PAYNOTES'] = $getPO->PO_PAYNOTES;
			$data['default']['PO_MEMO'] 	= $getPO->PO_MEMO;
			$data['default']['PO_DPPER'] 	= $getPO->PO_DPPER;
			$data['default']['PO_DPVAL'] 	= $getPO->PO_DPVAL;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['default']['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['default']['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['default']['PO_REFRENS'] 	= $getPO->PO_REFRENS;
			$data['default']['PO_CONTRNO'] 	= $getPO->PO_CONTRNO;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN019';
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

			if($ISDIRECT == 0)
			{
				$data['form_action']		= site_url('c_purchase/c_p180c21o/update_process');
				$this->load->view('v_purchase/v_purchase_po/v_po_form', $data);	
			}
			else
			{
				$data['form_action']		= site_url('c_purchase/c_p180c21o/updateDir_process');
				$this->load->view('v_purchase/v_purchase_po/v_podir_form', $data);	
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
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
			
			$PO_NUM 	= $this->input->post('PO_NUM');
			$PO_CODE 	= $this->input->post('PO_CODE');
			$PO_DATE	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PRJHO 		= $this->input->post('PRJHO');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$DEPCODE	= $this->input->post('DEPCODE');
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_TYPE 		= $this->input->post('PO_TYPE');
			$PO_CATEG 		= $this->input->post('PO_CATEG');
			$PO_CAT			= 1;								// In Direct					
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');			
			$PO_CURR 		= $this->input->post('PO_CURR');	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar 
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_PLANIR')))); // Tanggal Terima
			$PO_NOTES 		= addslashes($this->input->post('PO_NOTES'));
			$PO_PAYNOTES 	= $this->input->post('PO_PAYNOTES');
			$PO_NOTESIR 	= $this->input->post('PO_NOTESIR');
			$JOBCODE 		= $this->input->post('JOBCODE');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_TOTCOST		= $this->input->post('PO_TOTCOST');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_DPPER		= $this->input->post('PO_DPPER');
			$PO_DPVAL		= $this->input->post('PO_DPVAL');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');

			$PO_TAXCODE1 	= $this->input->post('PO_TAXCODE1');

			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN019',
										'DOCNUM' 		=> $PO_NUM,
										'DOCCODE'		=> $PO_CODE,
										'DOCDATE'		=> $PO_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE";
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
						$uplFile = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $PO_NUM, "REF_CODE" => $PO_CODE,
									"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
									"UPL_FILENAME" => $file['file_name'], "UPL_FILESIZE" => $file['file_size'],
									"UPL_FILETYPE" => $file['file_type'], "UPL_FILEPATH" => $file['file_path'], 
									"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
						$this->m_purchase_po->uplDOC_TRX($uplFile);
					endforeach;
		
					// $fileUpload = join(", ", $data_upload);
				}
			
			// ============================= End Upload File ========================================== //
			
			// UPDATE JOBDETAIL ITEM
			$PO_TAX 	= 0;
			if($PO_STAT == 4)
			{
				// TIDAK ADA KONDISI REVISI PADA UPDATE
			}
			elseif($PO_STAT == 5)	// REJECTED
			{
				// TIDAK ADA KONDISI REJECTED PADA UPDATE
			}
			elseif($PO_STAT == 6)
			{
				foreach($_POST['data'] as $d)
				{
					$PO_NUM		= $d['PO_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					$this->m_purchase_po->updateVolBud($PO_NUM, $PRJCODE, $ITM_CODE);
				}
			}
			elseif($PO_STAT == 9)	// VOID
			{
				// HARUS DILAKUKAN DARI INDEX
				// SAMA DENGAN REJECTED
				/*$this->m_purchase_po->updREJECT($PO_NUM, $PRJCODE);

				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $PO_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "PO",
											'DOC_QTY' 		=> "DOC_POQ",
											'DOC_VAL' 		=> "DOC_POV",
											'DOC_STAT' 		=> 'VOID');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT

				// START : UPDATE FINANCIAL DASHBOARD
					$PO_VAL_M 	= $PO_TOTCOST;
					$finDASH 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PO_DATE,
										'FVAL'		=> $PO_VAL_M,
										'FNAME'		=> "PO_VAL_M");										
					$this->m_updash->updFINDASH($finDASH);
				// END : UPDATE FINANCIAL DASHBOARD*/
			}
			else
			{
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $PO_DATE,
										'DOC_NUM'	=> $PO_NUM,
										'DOC_CATEG'	=> "PO");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				$this->m_purchase_po->deletePODetail($PO_NUM);

				$maxNo	= 0;
				$sqlMax = "SELECT MAX(PO_ID) AS maxNo FROM tbl_po_detail";
				$resMax = $this->db->query($sqlMax)->result();
				foreach($resMax as $rowMax) :
					$maxNo = $rowMax->maxNo;		
				endforeach;
				foreach($_POST['data'] as $d)
				{
					$maxNo			= $maxNo + 1;
					$d['PO_ID']		= $maxNo;
					$d['DEPCODE']	= $DEPCODE;
					$ITMAMOUNT		= $d['PO_COST'];
					//$PO_TOTCOST	= $PO_TOTCOST + $ITMAMOUNT;
					
					// GET HEADER
						$JOBID 		= $d['JOBCODEID'];
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

					$DIVID 	= "";
					if($PRJHO == 'KTR')
					{
						$s_MN 	= "SELECT JOBCOD1 FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1";
						$r_MN	= $this->db->query($s_MN)->result();
						foreach($r_MN as $rw_MN) :
							$DIVID	= $rw_MN->JOBCOD1;
						endforeach;
					}

					$d['JOBPARENT']		= $JOBIDH;
					$d['JOBPARDESC']	= $JOBDSH;
					$d['PO_DIVID']		= $DIVID;
					$this->db->insert('tbl_po_detail',$d);

					// CEK TAX
						$TAXPRICE1	= $d['TAXPRICE1'];
						$PO_TAX 	= $PO_TAX + $TAXPRICE1;

					// UPDATE LASTP
						if($PO_STAT == 2)
						{
							$JOBID 	= $d['JOBCODEID'];
							$ITMC  	= $d['ITM_CODE'];
							$LASTP 	= $d['PO_PRICE'];
							$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $LASTP
										WHERE JOBCODEID = '$JOBID' AND ITM_CODE = '$ITMC' AND PRJCODE = '$PRJCODE'";
							//$this->db->query($updLP);
						}

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$JOBID 		= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$DOC_VOLM	= $d['PO_VOLM'];
						$DOC_TOTAL	= $d['PO_COST'];
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PO_DATE,
											'JOBID'		=> $JOBID,
											'ITM_CODE'	=> $ITM_CODE,
											'DOC_VOLM'	=> $DOC_VOLM,
											'DOC_TOTAL'	=> $DOC_TOTAL,
											'VAR_VOL_R'	=> "PO_VOL_R",
											'VAR_VAL_R'	=> "PO_VAL_R");
						$this->m_updash->updJOBP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
				}

				if($PO_CATEG == 1)
				{
					$MenuApp 	= "MN436";
				}
				else
				{
					$MenuApp 	= "MN020";
					if($PRJHO == 'KTR')
					{
						$s_DIV 	= "SELECT DISTINCT PO_DIVID FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND PO_DIVID != '' AND PO_DIVID IS NOT NULL";
						$r_DIV 	= $this->db->query($s_DIV)->result();
						foreach($r_DIV as $rw_DIV):
							$MenuAppA 	= $rw_DIV->PO_DIVID;

							if($MenuAppA == 'MN437')
								$MenuApp 	= "MN447";
							elseif($MenuAppA == 'MN438')
								$MenuApp 	= "MN448";
							elseif($MenuAppA == 'MN439')
								$MenuApp 	= "MN449";
							elseif($MenuAppA == 'MN440')
								$MenuApp 	= "MN450";
							elseif($MenuAppA == 'MN441')
								$MenuApp 	= "MN451";
							elseif($MenuAppA == 'MN442')
								$MenuApp 	= "MN452";
							elseif($MenuAppA == 'MN443')
								$MenuApp 	= "MN453";
							elseif($MenuAppA == 'MN444')
								$MenuApp 	= "MN454";
							elseif($MenuAppA == 'MN445')
								$MenuApp 	= "MN455";
							elseif($MenuAppA == 'MN446')
								$MenuApp 	= "MN456";
							else
								$MenuApp 	= "MN020";
						endforeach;
					}
				}
			
				$updPO 			= array('PO_NUM' 		=> $PO_NUM,
										'PO_CODE' 		=> $PO_CODE,
										'PO_CATEG' 		=> $PO_CATEG,
										'PO_TYPE' 		=> $PO_TYPE,
										'PO_CAT'		=> $PO_CAT,
										'PO_DATE'		=> $PO_DATE,
										'PO_DUED'		=> $PO_DUED,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE' 		=> $SPLCODE,
										'JOBCODE' 		=> $JOBCODE,
										'PR_NUM' 		=> $PR_NUM,
										'PO_CURR' 		=> $PO_CURR, 
										'PO_CURRATE' 	=> $PO_CURRATE,									
										'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
										'PO_TENOR' 		=> $PO_TENOR, 
										'PO_PLANIR'		=> $PO_PLANIR,
										'PO_NOTES' 		=> $PO_NOTES,
										'PO_PAYNOTES'	=> $PO_PAYNOTES,
										'PO_NOTESIR' 	=> $PO_NOTESIR,
										'PO_DPPER' 		=> $PO_DPPER,
										'PO_DPVAL' 		=> $PO_DPVAL,
										'PO_STAT'		=> $PO_STAT,
										'PO_DIVID' 		=> $MenuApp,
										'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
										'PO_RECEIVCP'	=> $PO_RECEIVCP,
										'PO_SENTROLES'	=> $PO_SENTROLES,
										'PO_REFRENS'	=> $PO_REFRENS,
										'PO_TOTCOST'	=> $PO_TOTCOST,
										'PO_TAXAMN'		=> $PO_TAX,
										'PO_TAXCODE1' 	=> $PO_TAXCODE1,
										'PO_INVSTAT'	=> 0);
				$this->m_purchase_po->updatePO($PO_NUM, $updPO);
				
				// UPDATE DETAIL
					$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);

				if($PO_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$PO_VAL 	= $PO_TOTCOST;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PO_DATE,
											'FVAL'		=> $PO_VAL,
											'FNAME'		=> "PO_VAL");
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD

					$AS_MNCODE 		= $MenuApp;
					if($PO_CATEG == 1)
					{
						$data["MenuApp"] 	= 'MN436';
						$AS_MNCODE 			= 'MN436';
					}

					// START : CREATE ALERT LIST
						$alertVar 	= array('PRJCODE'		=> $PRJCODE,
											'AS_CATEG'		=> "PO",
											'AS_MNCODE'		=> $AS_MNCODE,
											'AS_DOCNUM'		=> $PO_NUM,
											'AS_DOCCODE'	=> $PO_CODE,
											'AS_DOCDATE'	=> $PO_DATE,
											'AS_EXPDATE'	=> $PO_DATE);
						$this->m_updash->addALERT($alertVar);
					// END : CREATE ALERT LIST
				}
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
										'DOC_CODE' 		=> $PO_NUM,
										'DOC_STAT' 		=> $PO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_po_header");
				$this->m_updash->updateStatus($paramStat);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
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

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
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
			
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function updateDir_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 		= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');
				
			$this->load->model('m_updash/m_updash', '', TRUE);
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}
			$PO_TYPE 		= $this->input->post('PO_TYPE');
			$PO_CAT			= 1;								// Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= "Direct";			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_PLANIR')))); // Tanggal Terima
			$PO_NOTES 		= addslashes($this->input->post('PO_NOTES'));
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$PO_TOTCOST		= $this->input->post('PO_TOTCOST');
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');
			
			$updPO			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,
									'PO_TOTCOST'	=> $PO_TOTCOST,
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,
									'ISDIRECT'		=> 1,
									'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
									'PO_RECEIVCP'	=> $PO_RECEIVCP,
									'PO_SENTROLES'	=> $PO_SENTROLES,
									'PO_REFRENS'	=> $PO_REFRENS,								
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_purchase_po->updatePO($PO_NUM, $updPO);
			
			$this->m_purchase_po->deletePODetail($PO_NUM);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_po_detail', $d);
			}
			
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "POD",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
										'DOC_CODE' 		=> $PO_NUM,
										'DOC_STAT' 		=> $PO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_po_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
				$TTR_CATEG		= 'C-DIR';
				
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

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
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
			
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$url			= site_url('c_purchase/c_p180c21o/p07_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN020';
				$data["MenuApp"] 	= 'MN020';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Pembelian";
			}
			else
			{
				$data["h1_title"] 	= "Purchase Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN020';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_p180c21o/iN20_x1/?id=";
			
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
	
	function inbox4lt() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_p180c21o/p07_l5t_x14lt/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iN20_x1() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
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
				$data["url_search"] = site_url('c_purchase/c_p180c21o/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_purchase_po->count_all_num_rowsPOInb($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_po->get_all_POInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
		// GET MENU DESC
			$mnCode				= 'MN020';
			$data["MenuApp"] 	= 'MN020';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['title'] 		= $appName;
			//$data['addURL'] 	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_p180c21o/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;

			$MenuCode 			= 'MN020';
			$data["MenuCode"] 	= 'MN020';
			/*$MaxLimitApp		= $this->m_purchase_po->getMyMaxLimit($MenuCode, $DefEmp_ID);
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
				$MenuCode 		= 'MN020';
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
			
			$this->load->view('v_purchase/v_purchase_po/v_inb_po_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p07_l5t_x14lt() // G
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
				$mnCode				= 'MN436';
				$data["MenuApp"] 	= 'MN436';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Pembelian";
			}
			else
			{
				$data["h1_title"] 	= "Purchase Approval";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN436';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_p180c21o/iN20_x14lt/?id=";
			
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
	
	function iN20_x14lt() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
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
				$data["url_search"] = site_url('c_purchase/c_p180c21o/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_purchase_po->count_all_num_rowsPOInb($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_po->get_all_POInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
		// GET MENU DESC
			$mnCode				= 'MN436';
			$data["MenuApp"] 	= 'MN436';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;

			$data['title'] 		= $appName;
			//$data['addURL'] 	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_p180c21o/inbox4lt/');
			$data['PRJCODE'] 	= $PRJCODE;

			$MenuCode 			= 'MN436';
			$data["MenuCode"] 	= 'MN436';
			/*$MaxLimitApp		= $this->m_purchase_po->getMyMaxLimit($MenuCode, $DefEmp_ID);
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
				$MenuCode 		= 'MN436';
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
			
			$this->load->view('v_purchase/v_purchase_po/v_inb_po4lt_list', $data);
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
			$url			= site_url('c_purchase/c_p180c21o/iN20_x1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$PRJHO 			= "";
		$s_HO 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_HO 			= $this->db->query($s_HO)->result();
		foreach($r_HO as $rw_HO) :
			$PRJHO 	= $rw_HO ->PRJCODE_HO;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl 	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl 	= $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_CATEG	= $dataI['PO_CATEG'];
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= '';
				$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];

				$MenuApp	= $dataI['PO_DIVID'];
				$MNCODE		= $dataI['PO_DIVID'];

				$PODIVD 	= "Proyek";

				if($PRJHO == 'KTR')
				{
					if($MenuApp == 'MN447')
						$PODIVD 	= "Sekretaris";
					elseif($MenuApp == 'MN448')
						$PODIVD 	= "Audit Internal";
					elseif($MenuApp == 'MN449')
						$PODIVD 	= "Corp. Legal";
					elseif($MenuApp == 'MN450')
						$PODIVD 	= "Corp. Adm. Kontrak";
					elseif($MenuApp == 'MN451')
						$PODIVD 	= "QHSSE - SI";
					elseif($MenuApp == 'MN452')
						$PODIVD 	= "Marketing";
					elseif($MenuApp == 'MN453')
						$PODIVD 	= "Operasi";
					elseif($MenuApp == 'MN454')
						$PODIVD 	= "Keuangan";
					elseif($MenuApp == 'MN455')
						$PODIVD 	= "Human Capital";
					elseif($MenuApp == 'MN456')
						$PODIVD 	= "Anak Usaha";
					else
						$PODIVD 	= "--";
				}
				
				$CollID		= "$PRJCODE~$PO_NUM";
				$secUpd		= site_url('c_purchase/c_p180c21o/up180djinb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secView	= site_url('c_purchase/c_p180c21o/up180djinbVw/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
                                        
				$secAction	= "<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
							   <input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-check'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
									<i class='glyphicon glyphicon-list'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";
				
				$MNCODE	= 'MN020';
				$POCATD = "OP Bahan";
				$FAICON = "fa fa-cubes";
				if($PO_CATEG == 1)
				{
					$MNCODE	= 'MN436';
					$POCATD = "OP Alat";
					$FAICON = "fa fa-steam";
				}

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>".$dataI['PO_CODE']."</strong><br>
												<div style='font-style: italic;'><strong><i class='fa ".$FAICON." margin-r-5'></i>".$POCATD." </strong></div>
											</div>",
											"<div style='white-space:nowrap'>
												<strong>".$PO_DATEV."</strong><br>
												<i class='fa fa-calendar margin-r-5'></i>".$ReceivePlan."
												<strong><div style='margin-left: 17px; font-style: italic'>
												  	".$PO_PLANIRV."
												</div> </strong>
											</div>",
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-money margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
											</div>
											<strong><i class='fa fa-pencil-square-o margin-r-5'></i>$Description</strong>
											<div style='margin-left: 20px; font-style: italic'>
											  	".$PO_NOTES."
											</div>
										  	",
										  	"<strong>".$dataI['PR_CODE']."</strong><br>
									  		<div>
										  		<p class='text-muted'>
										  			<i class='fa fa-user margin-r-5'></i> ".$PODIVD."
										  		</p>
										  	</div>",
										  	"<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
										  	$secAction);
				$noU		= $noU + 1;
			}
				
			/*$output['data'][] 	= array("A",
										"A",
									  	"A",
									  	"A",
									  	"A",
									  	"A",
									  	"A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllData_1n2GRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$PR_NUM			= $_GET['SPNO'];
		$PO_STAT		= $_GET['DSTAT'];
		$PO_CATEG		= $_GET['SRC'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$PRJHO 			= "";
		$s_HO 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_HO 			= $this->db->query($s_HO)->result();
		foreach($r_HO as $rw_HO) :
			$PRJHO 	= $rw_HO ->PRJCODE_HO;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl 	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl 	= $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataC_1n2GRP($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataL_1n2GRP($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG,  $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= '';
				$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];

				$MenuApp	= $dataI['PO_DIVID'];
				$MNCODE		= $dataI['PO_DIVID'];

				$PODIVD 	= "Proyek";

				if($PRJHO == 'KTR')
				{
					if($MenuApp == 'MN447')
						$PODIVD 	= "Sekretaris";
					elseif($MenuApp == 'MN448')
						$PODIVD 	= "Audit Internal";
					elseif($MenuApp == 'MN449')
						$PODIVD 	= "Corp. Legal";
					elseif($MenuApp == 'MN450')
						$PODIVD 	= "Corp. Adm. Kontrak";
					elseif($MenuApp == 'MN451')
						$PODIVD 	= "QHSSE - SI";
					elseif($MenuApp == 'MN452')
						$PODIVD 	= "Marketing";
					elseif($MenuApp == 'MN453')
						$PODIVD 	= "Operasi";
					elseif($MenuApp == 'MN454')
						$PODIVD 	= "Keuangan";
					elseif($MenuApp == 'MN455')
						$PODIVD 	= "Human Capital";
					elseif($MenuApp == 'MN456')
						$PODIVD 	= "Anak Usaha";
					else
						$PODIVD 	= "--";
				}
				
				$CollID		= "$PRJCODE~$PO_NUM";
				$secUpd		= site_url('c_purchase/c_p180c21o/up180djinb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secView	= site_url('c_purchase/c_p180c21o/up180djinbVw/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
                                        
				$secAction	= "<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
							   <input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-check'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
									<i class='glyphicon glyphicon-list'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";
				
				$MNCODE	= 'MN020';
				$POCATD = "OP Bahan";
				$FAICON = "fa fa-cubes";
				if($PO_CATEG == 1)
				{
					$MNCODE	= 'MN436';
					$POCATD = "OP Alat";
					$FAICON = "fa fa-steam";
				}

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>".$dataI['PO_CODE']."</strong><br>
												<div style='font-style: italic;'><strong><i class='fa ".$FAICON." margin-r-5'></i>".$POCATD." </strong></div>
											</div>",
											"<div style='white-space:nowrap'>
												<strong>".$PO_DATEV."</strong><br>
												<i class='fa fa-calendar margin-r-5'></i>".$ReceivePlan."
												<strong><div style='margin-left: 17px; font-style: italic'>
												  	".$PO_PLANIRV."
												</div> </strong>
											</div>",
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-money margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
											</div>
											<strong><i class='fa fa-pencil-square-o margin-r-5'></i>$Description</strong>
											<div style='margin-left: 20px; font-style: italic'>
											  	".$PO_NOTES."
											</div>
										  	",
										  	"<strong>".$dataI['PR_CODE']."</strong><br>
									  		<div>
										  		<p class='text-muted'>
										  			<i class='fa fa-user margin-r-5'></i> ".$PODIVD."
										  		</p>
										  	</div>",
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

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$PRJHO 			= "";
		$s_HO 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_HO 			= $this->db->query($s_HO)->result();
		foreach($r_HO as $rw_HO) :
			$PRJHO 	= $rw_HO ->PRJCODE_HO;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl 	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl 	= $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataC_1n24lt($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataL_1n24lt($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= '';
				$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];

				$MenuApp	= $dataI['PO_DIVID'];
				$MNCODE		= $dataI['PO_DIVID'];

				$PODIVD 	= "Peralatan";

				if($PRJHO == 'KTR')
				{
					if($MenuApp == 'MN447')
						$PODIVD 	= "Sekretaris";
					elseif($MenuApp == 'MN448')
						$PODIVD 	= "Audit Internal";
					elseif($MenuApp == 'MN449')
						$PODIVD 	= "Corp. Legal";
					elseif($MenuApp == 'MN450')
						$PODIVD 	= "Corp. Adm. Kontrak";
					elseif($MenuApp == 'MN451')
						$PODIVD 	= "QHSSE - SI";
					elseif($MenuApp == 'MN452')
						$PODIVD 	= "Marketing";
					elseif($MenuApp == 'MN453')
						$PODIVD 	= "Operasi";
					elseif($MenuApp == 'MN454')
						$PODIVD 	= "Keuangan";
					elseif($MenuApp == 'MN455')
						$PODIVD 	= "Human Capital";
					elseif($MenuApp == 'MN456')
						$PODIVD 	= "Anak Usaha";
					else
						$PODIVD 	= "--";
				}
				
				$CollID		= "$PRJCODE~$PO_NUM";
				$secUpd		= site_url('c_purchase/c_p180c21o/up180djinb4lt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secView	= site_url('c_purchase/c_p180c21o/up180djinbVw/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
                                        
				$secAction	= "<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
							   <input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-check'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
									<i class='glyphicon glyphicon-list'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";

				$MNCODE	= 'MN436';
				$POCATD = "OP Alat";
				$FAICON = "fa fa-steam";

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>".$dataI['PO_CODE']."</strong><br>
												<div style='font-style: italic;'><strong><i class='fa ".$FAICON." margin-r-5'></i>".$POCATD." </strong></div>
											</div>",
											"<div style='white-space:nowrap'>
												<strong>".$PO_DATEV."</strong><br>
												<i class='fa fa-calendar margin-r-5'></i>".$ReceivePlan."
												<strong><div style='margin-left: 17px; font-style: italic'>
												  	".$PO_PLANIRV."
												</div> </strong>
											</div>",
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-money margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
											</div>
											<strong><i class='fa fa-pencil-square-o margin-r-5'></i>$Description</strong>
											<div style='margin-left: 20px; font-style: italic'>
											  	".$PO_NOTES."
											</div>
										  	",
										  	"<strong>".$dataI['PR_CODE']."</strong><br>
									  		<div>
										  		<p class='text-muted'>
										  			<i class='fa fa-user margin-r-5'></i> ".$PODIVD."
										  		</p>
										  	</div>",
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

  	function get_AllData_1n2GRP4lt() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$PR_NUM			= $_GET['SPNO'];
		$PO_STAT		= $_GET['DSTAT'];
		$PO_CATEG		= $_GET['SRC'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$PRJHO 			= "";
		$s_HO 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$r_HO 			= $this->db->query($s_HO)->result();
		foreach($r_HO as $rw_HO) :
			$PRJHO 	= $rw_HO ->PRJCODE_HO;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl 	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl 	= $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
        endforeach;
		
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataC_1n2GRP4lt($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataL_1n2GRP4lt($PRJCODE, $PR_NUM, $SPLCODE, $PO_STAT, $PO_CATEG,  $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= '';
				$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];

				$MenuApp	= $dataI['PO_DIVID'];
				$MNCODE		= $dataI['PO_DIVID'];

				$PODIVD 	= "Peralatan";

				if($PRJHO == 'KTR')
				{
					if($MenuApp == 'MN447')
						$PODIVD 	= "Sekretaris";
					elseif($MenuApp == 'MN448')
						$PODIVD 	= "Audit Internal";
					elseif($MenuApp == 'MN449')
						$PODIVD 	= "Corp. Legal";
					elseif($MenuApp == 'MN450')
						$PODIVD 	= "Corp. Adm. Kontrak";
					elseif($MenuApp == 'MN451')
						$PODIVD 	= "QHSSE - SI";
					elseif($MenuApp == 'MN452')
						$PODIVD 	= "Marketing";
					elseif($MenuApp == 'MN453')
						$PODIVD 	= "Operasi";
					elseif($MenuApp == 'MN454')
						$PODIVD 	= "Keuangan";
					elseif($MenuApp == 'MN455')
						$PODIVD 	= "Human Capital";
					elseif($MenuApp == 'MN456')
						$PODIVD 	= "Anak Usaha";
					else
						$PODIVD 	= "--";
				}
				
				$CollID		= "$PRJCODE~$PO_NUM";
				$secUpd		= site_url('c_purchase/c_p180c21o/up180djinb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secView	= site_url('c_purchase/c_p180c21o/up180djinbVw/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
                                        
				$secAction	= "<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
							   <input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-check'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
									<i class='glyphicon glyphicon-list'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";

				$MNCODE	= 'MN436';
				$POCATD = "OP Alat";
				$FAICON = "fa fa-steam";

				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$PO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>".$dataI['PO_CODE']."</strong><br>
												<div style='font-style: italic;'><strong><i class='fa ".$FAICON." margin-r-5'></i>".$POCATD." </strong></div>
											</div>",
											"<div style='white-space:nowrap'>
												<strong>".$PO_DATEV."</strong><br>
												<i class='fa fa-calendar margin-r-5'></i>".$ReceivePlan."
												<strong><div style='margin-left: 17px; font-style: italic'>
												  	".$PO_PLANIRV."
												</div> </strong>
											</div>",
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-money margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
											</div>
											<strong><i class='fa fa-pencil-square-o margin-r-5'></i>$Description</strong>
											<div style='margin-left: 20px; font-style: italic'>
											  	".$PO_NOTES."
											</div>
										  	",
										  	"<strong>".$dataI['PR_CODE']."</strong><br>
									  		<div>
										  		<p class='text-muted'>
										  			<i class='fa fa-user margin-r-5'></i> ".$PODIVD."
										  		</p>
										  	</div>",
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
	
	function up180djinb() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN020';
			$data["MenuApp"] 	= 'MN020';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$PO_NUM		= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 				= 'MN020';
			$data["MenuCode"] 		= 'MN020';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			
			$getPO	 						= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();			
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['ISDIRECT'] 	= $getPO->ISDIRECT;
			$ISDIRECT						= $getPO->ISDIRECT;
			$data['default']['PO_CATEG'] 	= $getPO->PO_CATEG;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getPO->DEPCODE;
			$PRJCODE						= $getPO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getPO->DEPCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_DPPER'] 	= $getPO->PO_DPPER;
			$data['default']['PO_DPVAL'] 	= $getPO->PO_DPVAL;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTESIR'] 	= $getPO->PO_NOTESIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PO_NOTES1'] 	= $getPO->PO_NOTES1;
			$data['default']['PO_PAYNOTES'] = $getPO->PO_PAYNOTES;
			$data['default']['PO_MEMO'] 	= $getPO->PO_MEMO;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_TOTCOST'] 	= $getPO->PO_TOTCOST;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			$data['default']['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['default']['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['default']['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['default']['PO_REFRENS'] 	= $getPO->PO_REFRENS;
			$data['default']['PO_CONTRNO'] 	= $getPO->PO_CONTRNO;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN020';
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
			
			if($ISDIRECT == 0)
			{
				$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
				$this->load->view('v_purchase/v_purchase_po/v_inb_po_form', $data);
			}
			else
			{
				$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
				//$this->load->view('v_purchase/v_purchase_po/v_inb_poDir_form', $data);	
				$this->load->view('v_purchase/v_purchase_po/v_inb_po_form', $data);	
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up180djinb4lt() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN436';
			$data["MenuApp"] 	= 'MN436';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
				
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$PO_NUM		= $EXTRACTCOL[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 				= 'MN436';
			$data["MenuCode"] 		= 'MN436';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			
			$getPO	 						= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();			
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['ISDIRECT'] 	= $getPO->ISDIRECT;
			$ISDIRECT						= $getPO->ISDIRECT;
			$data['default']['PO_CATEG'] 	= $getPO->PO_CATEG;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getPO->DEPCODE;
			$PRJCODE						= $getPO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getPO->DEPCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_DPPER'] 	= $getPO->PO_DPPER;
			$data['default']['PO_DPVAL'] 	= $getPO->PO_DPVAL;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTESIR'] 	= $getPO->PO_NOTESIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PO_NOTES1'] 	= $getPO->PO_NOTES1;
			$data['default']['PO_PAYNOTES'] = $getPO->PO_PAYNOTES;
			$data['default']['PO_MEMO'] 	= $getPO->PO_MEMO;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_TOTCOST'] 	= $getPO->PO_TOTCOST;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			$data['default']['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['default']['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['default']['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['default']['PO_REFRENS'] 	= $getPO->PO_REFRENS;
			$data['default']['PO_CONTRNO'] 	= $getPO->PO_CONTRNO;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN436';
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
			
			if($ISDIRECT == 0)
			{
				$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
				$this->load->view('v_purchase/v_purchase_po/v_inb_po4lt_form', $data);
			}
			else
			{
				$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
				//$this->load->view('v_purchase/v_purchase_po/v_inb_poDir_form', $data);	
				$this->load->view('v_purchase/v_purchase_po/v_inb_po4lt_form', $data);	
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up180djinbVw() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$CollID			= $_GET['id'];
		$CollID			= $this->url_encryption_helper->decode_url($CollID);
		
		$splitCode 		= explode("~", $CollID);
		$PO_NUM			= $splitCode[0];
		$ISDIRECT		= $splitCode[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $this->input->post('PRJCODE');
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 				= 'MN020';
			$data["MenuCode"] 		= 'MN020';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			
			$getPO	 						= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();			
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['PO_CATEG'] 	= $getPO->PO_CATEG;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$PRJCODE						= $getPO->PRJCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTESIR'] 	= $getPO->PO_NOTESIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PO_NOTES1'] 	= $getPO->PO_NOTES1;
			$data['default']['PO_PAYNOTES'] = $getPO->PO_PAYNOTES;
			$data['default']['PO_MEMO'] 	= $getPO->PO_MEMO;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_TOTCOST'] 	= $getPO->PO_TOTCOST;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			$data['default']['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['default']['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['default']['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['default']['PO_REFRENS'] 	= $getPO->PO_REFRENS;
			$data['default']['PO_CONTRNO'] 	= $getPO->PO_CONTRNO;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN020';
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
			
			if($ISDIRECT == 0)
			{
				$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
				$this->load->view('v_purchase/v_purchase_po/v_inb_po_formVw', $data);
			}
			else
			{
				$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process_inb');
				$this->load->view('v_purchase/v_purchase_po/v_inb_poDir_formVw', $data);	
			}
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$PO_APPROVED 	= date('Y-m-d H:i:s');
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$PO_NUM 		= $this->input->post('PO_NUM');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DATE 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$PO_CATEG 		= $this->input->post('PO_CATEG');
			$PO_TOTCOST		= $this->input->post('PO_TOTCOST');
			$PO_STAT 		= $this->input->post('PO_STAT');
			$ISDIRECT 		= $this->input->post('ISDIRECT');
			$PO_NOTES1 		= addslashes($this->input->post('PO_NOTES1'));
			$PO_MEMO 		= addslashes($this->input->post('PO_MEMO'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			
			$PRJHO 			= "";
			$s_HO 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$r_HO 			= $this->db->query($s_HO)->result();
			foreach($r_HO as $rw_HO) :
				$PRJHO 	= $rw_HO ->PRJCODE_HO;
			endforeach;
			
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
						$yesAPP		= 1;
						$AH_ISLAST	= 1;
					}
					else
					{
						$yesAPP	= 0;
					}
				}
				else
				{
					$yesAPP		= 1;
				}
				//echo "Emp_ID1 = $Emp_ID1 AND TOTMAJOR = $TOTMAJOR AND yesAPP = $yesAPP";
				//return false;
			// END : SPECIAL FOR SASMITO
			
			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_CODE		= $PO_NUM;
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_APPROVER	= $DefEmp_ID;
				$AH_APPROVED	= $PO_APPROVED;
				$AH_NOTES		= addslashes($this->input->post('PO_NOTES1'));
				// Karena dengan adanya regulasi Persetujuan berdasarkan jumlah Total Pembelian, maka siapa yang bisa meng-approve,
				// maka itu pasti Last Step.
			
				$updPO 			= array('PO_STAT'	=> 7,
										'PO_NOTES1'	=> $PO_NOTES1); // Default step approval
				$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);
			// END : SAVE APPROVE HISTORY
			
			if($PO_CURR == 'IDR')
			{
				if($PO_STAT == 3)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
												'DOC_CODE' 		=> $PO_NUM,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_po_header");
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

					if($AH_ISLAST == 0)
					{
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
													'DOC_CODE' 		=> $PO_NUM,
													'DOC_STAT' 		=> 7,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_po_header");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS

						$AS_MNCODE 		= "MN020";
						if($PO_CATEG == 1)
						{
							$data["MenuApp"] 	= 'MN436';
							$MenuApp 			= 'MN436';
							$AS_MNCODE 			= 'MN436';
						}
						else
						{
							$MenuApp 	= "MN020";
							if($PRJHO == 'KTR')
							{
								$s_DIV 	= "SELECT DISTINCT PO_DIVID FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND PO_DIVID != '' AND PO_DIVID IS NOT NULL";
								$r_DIV 	= $this->db->query($s_DIV)->result();
								foreach($r_DIV as $rw_DIV):
									$MenuAppA 	= $rw_DIV->PO_DIVID;

									if($MenuAppA == 'MN437')
										$MenuApp 	= "MN447";
									elseif($MenuAppA == 'MN438')
										$MenuApp 	= "MN448";
									elseif($MenuAppA == 'MN439')
										$MenuApp 	= "MN449";
									elseif($MenuAppA == 'MN440')
										$MenuApp 	= "MN450";
									elseif($MenuAppA == 'MN441')
										$MenuApp 	= "MN451";
									elseif($MenuAppA == 'MN442')
										$MenuApp 	= "MN452";
									elseif($MenuAppA == 'MN443')
										$MenuApp 	= "MN453";
									elseif($MenuAppA == 'MN444')
										$MenuApp 	= "MN454";
									elseif($MenuAppA == 'MN445')
										$MenuApp 	= "MN455";
									elseif($MenuAppA == 'MN446')
										$MenuApp 	= "MN456";
									else
										$MenuApp 	= "MN020";
								endforeach;
							}
						}

						// START : CREATE ALERT LIST
							$APP_LEVEL 	= $this->input->post('APP_LEVEL');
							$alertVar 	= array('PRJCODE'		=> $PRJCODE,
												'AS_CATEG'		=> "PO",
												'AS_MNCODE'		=> $MenuApp,
												'AS_DOCNUM'		=> $PO_NUM,
												'AS_DOCCODE'	=> $PO_CODE,
												'AS_DOCDATE'	=> $PO_DATE,
												'AS_EXPDATE'	=> $PO_DATE,
												'APP_LEVEL'		=> $APP_LEVEL);
							$this->m_updash->updAALERT($alertVar);
						// END : CREATE ALERT LIST
					}
					elseif($AH_ISLAST == 1 && $yesAPP == 1)
					{
						$updPO 	= array('PO_APPROVER'	=> $DefEmp_ID,
										'PO_APPROVED'	=> $PO_APPROVED,
										'PO_NOTES1'		=> addslashes($this->input->post('PO_NOTES1')),
										'PO_MEMO'		=> addslashes($this->input->post('PO_MEMO')),
										'PO_STAT'		=> $this->input->post('PO_STAT'));
						$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);

						$PO_TOTCOST		= 0;		
						foreach($_POST['data'] as $d)
						{
							$ITMAMOUNT	= $d['PO_COST'];
							$PO_TOTCOST	= $PO_TOTCOST + $ITMAMOUNT;

							// UPDATE LASTP
								$JOBID 	= $d['JOBCODEID'];
								$ITMC  	= $d['ITM_CODE'];
								$LASTP 	= $d['PO_PRICE'];
								$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $LASTP
											WHERE JOBCODEID = '$JOBID' AND ITM_CODE = '$ITMC' AND PRJCODE = '$PRJCODE'";
								$this->db->query($updLP);
						}

						// START : PROCEDURE UPDATE JOBLISTDETAIL
							$compVAR 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $PO_DATE,
												'DOC_NUM'	=> $PO_NUM,
												'DOC_CATEG'	=> "PO");
							$this->m_updash->updJOBPAPP($compVAR);
						// END : PROCEDURE UPDATE JOBLISTDETAIL
						
						$this->m_purchase_po->updatePODet($PO_NUM, $PRJCODE, $PR_NUM, $ISDIRECT); // UPDATE JOB DETAIL DAN PR

						// START : UPDATE TO TRANS-COUNT
							$this->load->model('m_updash/m_updash', '', TRUE);
							
							$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PO_STAT
							$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
													'PRJCODE' 		=> $PRJCODE,		// PROJECT
													'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
													'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
													'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
													'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
													'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
													'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
													'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
													'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
													'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
													'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
													'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
													'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
													'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
							$this->m_updash->updateDashData($parameters);
						// END : UPDATE TO TRANS-COUNT
					
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
													'DOC_CODE' 		=> $PO_NUM,
													'DOC_STAT' 		=> $PO_STAT,
													'PRJCODE' 		=> $PRJCODE,
													//'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_po_header");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
						
						// START : UPDATE TO T-TRACK
							date_default_timezone_set("Asia/Jakarta");
							$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
							$TTR_PRJCODE	= $PRJCODE;
							$TTR_REFDOC		= $PO_NUM;
							$MenuCode 		= 'MN020';
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

						// START : UPDATE TO DOC. COUNT
							$parameters 	= array('DOC_CODE' 		=> $PO_NUM,
													'PRJCODE' 		=> $PRJCODE,
													'DOC_TYPE'		=> "PO",
													'DOC_QTY' 		=> "DOC_POQ",
													'DOC_VAL' 		=> "DOC_POV",
													'DOC_STAT' 		=> 'ADD');
							$this->m_updash->updateDocC($parameters);
						// END : UPDATE TO DOC. COUNT

						$AS_MNCODE 		= "MN020";
						if($PO_CATEG == 1)
						{
							$data["MenuApp"] 	= 'MN436';
							$AS_MNCODE 			= 'MN436';
							$MenuApp 			= "MN436";
						}
						else
						{
							$MenuApp 	= "MN020";
							if($PRJHO == 'KTR')
							{
								$s_DIV 	= "SELECT DISTINCT PO_DIVID FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND PO_DIVID != '' AND PO_DIVID IS NOT NULL";
								$r_DIV 	= $this->db->query($s_DIV)->result();
								foreach($r_DIV as $rw_DIV):
									$MenuAppA 	= $rw_DIV->PO_DIVID;

									if($MenuAppA == 'MN437')
										$MenuApp 	= "MN447";
									elseif($MenuAppA == 'MN438')
										$MenuApp 	= "MN448";
									elseif($MenuAppA == 'MN439')
										$MenuApp 	= "MN449";
									elseif($MenuAppA == 'MN440')
										$MenuApp 	= "MN450";
									elseif($MenuAppA == 'MN441')
										$MenuApp 	= "MN451";
									elseif($MenuAppA == 'MN442')
										$MenuApp 	= "MN452";
									elseif($MenuAppA == 'MN443')
										$MenuApp 	= "MN453";
									elseif($MenuAppA == 'MN444')
										$MenuApp 	= "MN454";
									elseif($MenuAppA == 'MN445')
										$MenuApp 	= "MN455";
									elseif($MenuAppA == 'MN446')
										$MenuApp 	= "MN456";
									else
										$MenuApp 	= "MN020";
								endforeach;
							}
						}

						// START : CREATE ALERT LIST
							$APP_LEVEL 	= $this->input->post('APP_LEVEL');
							$alertVar 	= array('PRJCODE'		=> $PRJCODE,
												'AS_CATEG'		=> "PO",
												'AS_MNCODE'		=> $MenuApp,
												'AS_DOCNUM'		=> $PO_NUM,
												'AS_DOCCODE'	=> $PO_CODE,
												'AS_DOCDATE'	=> $PO_DATE,
												'AS_EXPDATE'	=> $PO_DATE,
												'APP_LEVEL'		=> $APP_LEVEL);
							$this->m_updash->updAALERT($alertVar);
						// END : CREATE ALERT LIST
					}
				}
				
				// UPDATE JOBDETAIL ITEM
				if($PO_STAT == 4 || $PO_STAT == 5 || $PO_STAT == 6)
				{
					$updPO 		= array('PO_STAT'	=> $PO_STAT,
										'PO_MEMO'	=> $PO_MEMO);
					$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
												'DOC_CODE' 		=> $PO_NUM,
												'DOC_STAT' 		=> $PO_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_po_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS

					// START : UPDATE FINANCIAL DASHBOARD
						$PO_VAL_M 	= $PO_TOTCOST;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PO_DATE,
											'FVAL'		=> $PO_VAL_M,
											'FNAME'		=> "PO_VAL_M");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD

					// START : DELETE HISTORY
						$this->m_updash->delAppHist($PO_NUM);
					// END : DELETE HISTORY
				}

				if($PO_STAT == 9)	// VOID
				{
					// SAMA DENGAN REJECTED
					$this->m_purchase_po->updREJECT($PO_NUM, $PRJCODE);
				}

				// START : UPDATE QTY_COLLECTIVE
					$this->load->model('m_updash/m_updash', '', TRUE);

					$parameters 	= array('TR_TYPE'		=> "PO",
											'TR_DATE' 		=> $PO_DATE,
											'PRJCODE'		=> $PRJCODE);
					$this->m_updash->updateQtyColl($parameters);
				// END : UPDATE QTY_COLLECTIVE
			}
			else
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
											'DOC_CODE' 		=> $PO_NUM,
											'DOC_STAT' 		=> $PO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_po_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($PO_STAT == 3)
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
				}
				
				if($AH_ISLAST == 1 && $yesAPP == 1)
				{
					$AS_MNCODE 		= "MN020";
					if($PO_CATEG == 1)
					{
						$data["MenuApp"] 	= 'MN436';
						$AS_MNCODE 			= 'MN436';
						$MenuApp 			= "MN436";
					}
					else
					{
						$MenuApp 	= "MN020";
						if($PRJHO == 'KTR')
						{
							$s_DIV 	= "SELECT DISTINCT PO_DIVID FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND PO_DIVID != '' AND PO_DIVID IS NOT NULL";
							$r_DIV 	= $this->db->query($s_DIV)->result();
							foreach($r_DIV as $rw_DIV):
								$MenuAppA 	= $rw_DIV->PO_DIVID;

								if($MenuAppA == 'MN437')
									$MenuApp 	= "MN447";
								elseif($MenuAppA == 'MN438')
									$MenuApp 	= "MN448";
								elseif($MenuAppA == 'MN439')
									$MenuApp 	= "MN449";
								elseif($MenuAppA == 'MN440')
									$MenuApp 	= "MN450";
								elseif($MenuAppA == 'MN441')
									$MenuApp 	= "MN451";
								elseif($MenuAppA == 'MN442')
									$MenuApp 	= "MN452";
								elseif($MenuAppA == 'MN443')
									$MenuApp 	= "MN453";
								elseif($MenuAppA == 'MN444')
									$MenuApp 	= "MN454";
								elseif($MenuAppA == 'MN445')
									$MenuApp 	= "MN455";
								elseif($MenuAppA == 'MN446')
									$MenuApp 	= "MN456";
								else
									$MenuApp 	= "MN020";
							endforeach;
						}
					}

					// START : CREATE ALERT LIST
						$APP_LEVEL 	= $this->input->post('APP_LEVEL');
						$alertVar 	= array('PRJCODE'		=> $PRJCODE,
											'AS_CATEG'		=> "PO",
											'AS_MNCODE'		=> $MenuApp,
											'AS_DOCNUM'		=> $PO_NUM,
											'AS_DOCCODE'	=> $PO_CODE,
											'AS_DOCDATE'	=> $PO_DATE,
											'AS_EXPDATE'	=> $PO_DATE,
											'APP_LEVEL'		=> $APP_LEVEL);
						$this->m_updash->updAALERT($alertVar);
					// END : CREATE ALERT LIST

					$updPO 	= array('PO_APPROVER'	=> $DefEmp_ID,
									'PO_APPROVED'	=> $PO_APPROVED,
									'PO_NOTES1'		=> addslashes($this->input->post('PO_NOTES1')),
									'PO_MEMO'		=> addslashes($this->input->post('PO_MEMO')),
									'PO_STAT'		=> $this->input->post('PO_STAT'));
					$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);
					
					// START : UPDATE TO TRANS-COUNT
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PO_STAT
						$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
												'PRJCODE' 		=> $PRJCODE,		// PROJECT
												'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
												'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
												'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
												'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
												'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
												'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
												'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
												'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
												'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
												'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
												'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
						$this->m_updash->updateDashData($parameters);
					// END : UPDATE TO TRANS-COUNT
					
					// START : UPDATE TO T-TRACK
						date_default_timezone_set("Asia/Jakarta");
						$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
						$TTR_PRJCODE	= $PRJCODE;
						$TTR_REFDOC		= $PO_NUM;
						$MenuCode 		= 'MN020';
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
				
				if($PO_STAT == 3 && $AH_ISLAST == 0)
				{
					$AS_MNCODE 		= "MN020";
					if($PO_CATEG == 1)
					{
						$data["MenuApp"] 	= 'MN436';
						$AS_MNCODE 			= 'MN436';
					}
					else
					{
						$MenuApp 	= "MN020";
						if($PRJHO == 'KTR')
						{
							$s_DIV 	= "SELECT DISTINCT PO_DIVID FROM tbl_po_detail WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND PO_DIVID != '' AND PO_DIVID IS NOT NULL";
							$r_DIV 	= $this->db->query($s_DIV)->result();
							foreach($r_DIV as $rw_DIV):
								$MenuAppA 	= $rw_DIV->PO_DIVID;

								if($MenuAppA == 'MN437')
									$MenuApp 	= "MN447";
								elseif($MenuAppA == 'MN438')
									$MenuApp 	= "MN448";
								elseif($MenuAppA == 'MN439')
									$MenuApp 	= "MN449";
								elseif($MenuAppA == 'MN440')
									$MenuApp 	= "MN450";
								elseif($MenuAppA == 'MN441')
									$MenuApp 	= "MN451";
								elseif($MenuAppA == 'MN442')
									$MenuApp 	= "MN452";
								elseif($MenuAppA == 'MN443')
									$MenuApp 	= "MN453";
								elseif($MenuAppA == 'MN444')
									$MenuApp 	= "MN454";
								elseif($MenuAppA == 'MN445')
									$MenuApp 	= "MN455";
								elseif($MenuAppA == 'MN446')
									$MenuApp 	= "MN456";
								else
									$MenuApp 	= "MN020";
							endforeach;
						}
					}

					// START : CREATE ALERT LIST
						$APP_LEVEL 	= $this->input->post('APP_LEVEL');
						$alertVar 	= array('PRJCODE'		=> $PRJCODE,
											'AS_CATEG'		=> "PO",
											'AS_MNCODE'		=> $MenuApp,
											'AS_DOCNUM'		=> $PO_NUM,
											'AS_DOCCODE'	=> $PO_CODE,
											'AS_DOCDATE'	=> $PO_DATE,
											'AS_EXPDATE'	=> $PO_DATE,
											'APP_LEVEL'		=> $APP_LEVEL);
						$this->m_updash->updAALERT($alertVar);
					// END : CREATE ALERT LIST
				}
				
				if($PO_STAT == 4)
				{
					// START : DELETE HISTORY
						$this->m_updash->delAppHist($PO_NUM);
					// END : DELETE HISTORY
						
					// START : UPDATE FINANCIAL DASHBOARD
						$PO_VAL_M 	= $PO_TOTCOST;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $PO_DATE,
											'FVAL'		=> $PO_VAL_M,
											'FNAME'		=> "PO_VAL_M");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			}

			if($PO_STAT == 3)
			{
				$s_UPD 	= "UPDATE tbl_dash_transac_emp SET TOT_PO_W = TOT_PO_W-1 WHERE PRJ_CODE = '$PRJCODE' AND EMP_ID = '$DefEmp_ID'";
				$this->db->query($s_UPD);
			}
			
			if($PO_CATEG == 0)
				$url			= site_url('c_purchase/c_p180c21o/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			else
				$url			= site_url('c_purchase/c_p180c21o/iN20_x14lt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallPR()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
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
					
			$this->load->view('v_purchase/v_purchase_po/v_po_sel_req', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
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
			$data['form_action']	= site_url('c_purchase/c_p180c21o/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_purchase/c_p180c21o/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_purchase_po->count_all_num_rowsAllItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_purchase_po->viewAllItemMatBudget($PRJCODE)->result();
					
			$this->load->view('v_purchase/v_purchase_po/v_podir_selitem', $data);
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
		$myCount = $this->db->count_all('tbl_po_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_po_header
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
	
	function pRn_1rl()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$CollID		= $_GET['id'];
		$CollID		= $this->url_encryption_helper->decode_url($CollID);
		$ArrID      = explode('~', $CollID);
		$PRJCODE    = $ArrID[0];
		$PO_NUM     = $ArrID[1];

		$getPO 		= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();
			
		$PO_CODE	= $getPO->PO_CODE;
		$PO_DATE	= $getPO->PO_DATE;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	    = $appName;
			$data['PO_NUM']     = $PO_NUM;
			$data['PO_CODE']    = $PO_CODE;
			$data['PRJCODE']    = $PRJCODE;
			$data['PO_DATE']    = $PO_DATE;
			
			// $data['countIR']	= $this->m_purchase_po->count_all_IR($PO_NUM);
			// $data['vwIR'] 		= $this->m_purchase_po->get_all_IR($PO_NUM)->result();	
							
			$this->load->view('v_purchase/v_purchase_po/print_irlist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdoc()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PO_NUM		= $_GET['id'];
		$PO_NUM		= $this->url_encryption_helper->decode_url($PO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getPO 			= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();
			
			$data['PO_NUM'] 	= $getPO->PO_NUM;
			$data['PO_CODE'] 	= $getPO->PO_CODE;
			$data['PR_CODE'] 	= $getPO->PR_CODE;
			$data['PO_DATE'] 	= $getPO->PO_DATE;
			$data['PO_DUED'] 	= $getPO->PO_DUED;
			$data['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['PO_NOTESIR'] = $getPO->PO_NOTESIR;
			$data['PRJCODE'] 	= $getPO->PRJCODE;
			$data['SPLCODE'] 	= $getPO->SPLCODE;
			$data['PR_NUM'] 	= $getPO->PR_NUM;
			$data['PO_PAYTYPE'] = $getPO->PO_PAYTYPE;
			$data['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['PO_TERM'] 	= $getPO->PO_TERM;
			$data['PO_PAYNOTES']= $getPO->PO_PAYNOTES;
			$data['PO_TAXAMN']	= $getPO->PO_TAXAMN;
			$PO_TAXAMN			= $getPO->PO_TAXAMN;
			$data['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['PRJNAME'] 	= $getPO->PRJNAME;
			$data['PO_STAT'] 	= $getPO->PO_STAT;
			$data['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['PO_REFRENS']	= $getPO->PO_REFRENS;
			$data['PO_CONTRNO']	= $getPO->PO_CONTRNO;
			$data['isPrint'] 	= $getPO->isPrint;
			$data['PO_CURR'] 	= $getPO->PO_CURR;
			
			// BEDAKAN ANTARA YANG ADA PPN DAN TIDAK
			
// 			if($PO_TAXAMN > 0)
// 				$this->load->view('v_purchase/v_purchase_po/print_po_ppn', $data);
// 			else
				$this->load->view('v_purchase/v_purchase_po/print_po', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function watermark_upd()
	{
		$PO_NUM		= $_GET['id'];
		$PO_NUM		= $this->url_encryption_helper->decode_url($PO_NUM);

		$updW 		= ["isPrint" => 1];
		$this->db->where("PO_NUM", $PO_NUM);
		$this->db->update("tbl_po_header", $updW);
	}
	
	function a44p180c21o_r0() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 			= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['form_action']	= site_url('c_purchase/c_p180c21o/addro_process');
			$cancelURL				= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
			$data['viewDocPattern'] = $this->m_purchase_po->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
				$TTR_CATEG		= 'A-RO';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
	
			$this->load->view('v_purchase/v_purchase_po/v_ro_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallSPK()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
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
			$data['pageFrom']			= 'SPK';
			$data['PRJCODE']			= $PRJCODE;
					
			$this->load->view('v_purchase/v_purchase_po/v_po_sel_req', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function addro_process() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN019';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$PO_NUM			= "RO$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			// START : CHECK THE CODE	
				/*$DOC_NUM		= $PO_NUM;
				$DOC_CODE		= $PO_CODE;
				$DOC_DATE		= $PO_DATE;
				$MenuCode 		= 'MN019';
				$TABLE_NAME		= 'tbl_po_header';
				$FIELD_NAME		= 'PO_NUM';
				
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$paramCODE 	= array('DOC_NUM' 		=> $DOC_NUM,
									'PRJCODE' 		=> $PRJCODE,
									'TABLE_NAME' 	=> $TABLE_NAME,
									'FIELD_NAME' 	=> $FIELD_NAME);
				$countCode	= $this->m_updash->count_CODE($paramCODE);
				
				if($countCode > 0)
				{
					$getSetting	= $this->m_updash->getDataDocPat($MenuCode)->row();				
					$PattCode	= $getSetting->Pattern_Code;
					$PattLength	= $getSetting->Pattern_Length;
					$useYear	= $getSetting->useYear;
					$useMonth	= $getSetting->useMonth;
					$useDate	= $getSetting->useDate;
					
					$SettCode	= array('PRJCODE'		=> $PRJCODE,
										'DOC_DATE'		=> $DOC_DATE,
										'TABLE_NAME' 	=> $TABLE_NAME,
										'PattCode' 		=> $PattCode,
										'PattLength' 	=> $PattLength,
										'useYear'		=> $useYear,
										'useMonth'		=> $useMonth,
										'useDate'		=> $useDate);
					$getNewCode	= $this->m_updash->get_NewCode($SettCode);
					$splitCode 	= explode("~", $getNewCode);
					$DOC_NUM	= $splitCode[0];
					if($DOC_CODE == '')
					{
						$DOC_CODE	= $splitCode[1];
					}
					$Patt_Number= $splitCode[2];
				}
				
				$PO_NUM			= $DOC_NUM;
				$PO_CODE		= $DOC_CODE;*/
			// END : CHECK CODE
			
			if($PRJCODE == 'KTR')
			{
				$PO_TYPE	= 1;
			}
			else
			{
				$PO_TYPE	= 2;
			}			
			$PO_TYPE 		= $this->input->post('PO_TYPE');
			$PO_CAT			= 0;								// In Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_CODE 		= $this->input->post('PR_CODE');			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_PLANIR')))); // Tanggal Terima
			$PO_NOTES 		= addslashes($this->input->post('PO_NOTES'));
			$JOBCODE 		= $this->input->post('JOBCODE');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');
			
			$AddPO			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE' 		=> $SPLCODE,
									'JOBCODE'		=> $JOBCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PR_CODE'		=> $PR_CODE,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,
									'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
									'PO_RECEIVCP'	=> $PO_RECEIVCP,
									'PO_SENTROLES'	=> $PO_SENTROLES,
									'PO_REFRENS'	=> $PO_REFRENS,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_purchase_po->add($AddPO); // Insert tb_po_header
			
			$PO_TOTCOST	= 0;		
			foreach($_POST['data'] as $d)
			{
				$d['PO_NUM']	= $PO_NUM;
				$ITMAMOUNT		= $d['PO_COST'];
				$PO_TOTCOST		= $PO_TOTCOST + $ITMAMOUNT;
				$this->db->insert('tbl_po_detail',$d);
			}
			
			$updPOH			= array('PO_TOTCOST' => $PO_TOTCOST);
			$this->m_purchase_po->updatePOH($PO_NUM, $updPOH);
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PO_STAT');			// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN019';
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

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
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
			
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u77p180c21o_r0() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$PO_NUM		= $splitCode[0];
			$ISDIRECT	= $splitCode[1];
			
			$getPO					= $this->m_purchase_po->get_ro_by_number($PO_NUM)->row();
			$PRJCODE				= $getPO->PRJCODE;
			$data["MenuCode"] 		= 'MN019';
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			$cancelURL				= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN019';
			$data["MenuCode"] 		= 'MN019';
		
			$data['default']['PO_NUM'] 		= $getPO->PO_NUM;
			$data['default']['PO_CODE'] 	= $getPO->PO_CODE;
			$data['default']['PO_CATEG'] 	= $getPO->PO_CATEG;
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['SPLCODE'] 	= $getPO->SPLCODE;
			$data['default']['PR_NUM'] 		= $getPO->PR_NUM;
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_TOTCOST'] 	= $getPO->PO_TOTCOST;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
			$data['default']['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['default']['PO_NOTES1'] 	= $getPO->PO_NOTES1;
			$data['default']['PO_MEMO'] 	= $getPO->PO_MEMO;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['default']['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['default']['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['default']['PO_REFRENS'] 	= $getPO->PO_REFRENS;
			$data['default']['PO_CONTRNO'] 	= $getPO->PO_CONTRNO;
			$data['default']['Patt_Year'] 	= $getPO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getPO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getPO->Patt_Date;
			$data['default']['Patt_Number'] = $getPO->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getPO->PO_NUM;
				$MenuCode 		= 'MN019';
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
			
			$data['form_action']		= site_url('c_purchase/c_p180c21o/update_process');
			$this->load->view('v_purchase/v_purchase_po/v_ro_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdocro()
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$PO_NUM		= $_GET['id'];
		$PO_NUM		= $this->url_encryption_helper->decode_url($PO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getPO 			= $this->m_purchase_po->get_ro_by_number($PO_NUM)->row();
			
			$data['PO_NUM'] 	= $getPO->PO_NUM;
			$data['PO_CODE'] 	= $getPO->PO_CODE;
			$data['PR_CODE'] 	= $getPO->WO_CODE;
			$data['PO_DATE'] 	= $getPO->PO_DATE;
			$data['PO_DUED'] 	= $getPO->PO_DUED;
			$data['PRJCODE'] 	= $getPO->PRJCODE;
			$data['SPLCODE'] 	= $getPO->SPLCODE;
			$data['PR_NUM'] 	= $getPO->PR_NUM;
			$data['PO_PAYTYPE'] = $getPO->PO_PAYTYPE;
			$data['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['PO_TERM'] 	= $getPO->PO_TERM;
			$data['PO_NOTES'] 	= $getPO->PO_NOTES;
			$data['PRJNAME'] 	= $getPO->PRJNAME;
			$data['PO_STAT'] 	= $getPO->PO_STAT;
			$data['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['PO_REFRENS']	= $getPO->PO_REFRENS;
							
			$this->load->view('v_purchase/v_purchase_po/print_ro', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function d3l180c21o_p0() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$PO_NUM		= $splitCode[0];
			$ISDIRECT	= $splitCode[1];
			
			$getPO 		= $this->m_purchase_po->get_PO_by_number($PO_NUM)->row();
			$PRJCODE 	= $getPO->PRJCODE;
			
			$this->m_purchase_po->deletePOH($PO_NUM);
			$this->m_purchase_po->deletePOD($PO_NUM);
			
			$url		= site_url('c_purchase/c_p180c21o/gl180c21po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getDetTOP($SPLCODE) // OK
	{
		$SPLTOP 	= 0;
		$SPLTOPD 	= 0;
		$sqlSPLC	= "tbl_supplier WHERE SPLCODE = '$SPLCODE'";
		$resSPLC 	= $this->db->count_all($sqlSPLC);
		if($resSPLC > 0)
		{
			$sqlSPL		= "SELECT SPLTOP, SPLTOPD FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
			$resSPL 	= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $row) :
				$SPLTOP 	= $row->SPLTOP;
				$SPLTOPD 	= $row->SPLTOPD;
			endforeach;
		}
		$SPLTOPCOL	= "$SPLTOP~$SPLTOPD";
		echo $SPLTOPCOL;
	}

  	function get_AllDataPREQ() // GOOD
	{
		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];

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
			
			$columns_valid 	= array("PR_CODE", 
									"PR_CODE", 
									"PR_DATE",
									"PR_NOTE",
									"PR_RECEIPTD");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_po->get_AllDataPR($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_po->get_AllDataPRL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PR_NUM 		= $dataI['PR_NUM'];
				$PR_CODE 		= $dataI['PR_CODE'];
				$PR_DATE 		= $dataI['PR_DATE'];
				$PR_DATE		= date('d M Y', strtotime($PR_DATE));
				$PR_RECEIPTD 	= $dataI['PR_RECEIPTD'];
				$PR_RECEIPTD	= date('d M Y', strtotime($PR_RECEIPTD));
				$PR_CREATER 	= $dataI['PR_CREATER'];
				$JOBCODE		= $dataI['JOBCODE'];
				$PR_NOTE		= $dataI['PR_NOTE'];
				$PR_MEMO		= $dataI['PR_MEMO'];
				$PR_PLAN_IR		= $dataI['PR_PLAN_IR'];
				$PR_PLAN_IR		= date('d M Y', strtotime($PR_PLAN_IR));
				$reQName		= $dataI['reQName'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PRJNAME		= $dataI['PRJNAME'];

				$newtext 		= wordwrap($PR_NOTE, 60, "<br>", TRUE);

				$chkBox			= "<input type='radio' name='chk1' value='".$PR_NUM."|".$PR_CODE."' onClick='pickThis1(this);' />";

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<spin style='white-space:nowrap'>".$PR_CODE."</spin>",
											"$PR_DATE",
											"<spin style='white-space:nowrap'>$newtext</spin>",
											"$PR_RECEIPTD");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
 	function c_p180c21o_ovh() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_p180c21o/prj180c21l_ovh/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l_ovh() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN069';
				$data["MenuApp"] 	= 'MN020';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN069';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_purchase/c_p180c21o/gl180c21po_ovh/?id=";
			
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

	function gl180c21po_ovh() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
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
				$data["url_search"] = site_url('c_purchase/c_p180c21o/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_purchase_po->count_all_num_rowsPO($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_purchase_po->get_all_PO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN069';
				$data["MenuCode"] 	= 'MN069';
				$data["MenuApp"] 	= 'MN020';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_purchase/c_p180c21o/a44p180c21o_p0_ovh/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_purchase/c_p180c21o/c_p180c21o_ovh/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN069';
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
			
			$this->load->view('v_purchase/v_purchase_po/v_po_list_ovh', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_ovh() // GOOD 
	{
		$PRJCODE	= $_GET['id'];
		
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllData_ovhC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllData_ovhL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= $dataI['SPLDESC'];
				/*$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;*/
				
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];
				$PO_STAT	= $dataI['PO_STAT'];
				
				$CollID		= "$PRJCODE~$PO_NUM";
				if($PO_TYPE == 1)
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_p0_ovh/?id='.$this->url_encryption_helper->encode_url($CollID));
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				}
				else
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($CollID));		
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($PO_NUM));							
				}
				
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDel		= site_url('c_purchase/c_p180c21o/d3l180c21o_p0_ovh/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";

				// CEK IR
					$sqlIRC	= "tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9)";
					$resIRC = $this->db->count_all($sqlIRC);

					if($resIRC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
				
				if($PO_STAT == 1 || $PO_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PO_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Batal' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] 	= array($dataI['PO_CODE'],
										  	$PO_DATEV,
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-opencart margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
												</div>
											  	<div style='margin-left: 20px; font-style: italic'>
											  		".$dataI['PO_NOTES']."
											</div>
										  	",
										  	$dataI['PR_CODE'],
										 	$PO_PLANIRV,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataPOGRP_ovh() // GOOD 
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$PO_STAT		= $_GET['DSTAT'];
		$PO_CATEG		= $_GET['SRC'];
		
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
			
			$columns_valid 	= array("PO_CODE",
									"PO_DATE",
									"SPLDESC",
									"PO_NOTES",
									"PR_CODE",
									"PO_PLANIR",
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
			$num_rows 		= $this->m_purchase_po->get_AllDataGRP_ovhC($PRJCODE, $SPLCODE, $PO_STAT, $PO_CATEG, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataGRP_ovhL($PRJCODE, $SPLCODE, $PO_STAT, $PO_CATEG, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_NUM		= $dataI['PO_NUM'];
				$PO_CODE	= $dataI['PO_CODE'];
				
				$PO_DATE	= $dataI['PO_DATE'];
				$PO_DATEV	= date('d M Y', strtotime($PO_DATE));
				
				$ISDIRECT	= $dataI['ISDIRECT'];
				$PO_NOTES	= $dataI['PO_NOTES'];
				$PR_CODE	= $dataI['PR_CODE'];
				$SPLCODE	= $dataI['SPLCODE'];
				$PO_TOTCOST	= $dataI['PO_TOTCOST'];
				$PO_PLANIR	= $dataI['PO_PLANIR'];
				$PO_PLANIRV	= date('d M Y', strtotime($PO_PLANIR));
				
				$SPLDESC	= $dataI['SPLDESC'];
				/*$sqlS		= "SELECT SPLDESC FROM tbl_supplier where SPLCODE = '$SPLCODE' LIMIT 1";
				$resultS 	= $this->db->query($sqlS)->result();
				foreach($resultS as $rowS) :
					$SPLDESC = $rowS->SPLDESC;
				endforeach;*/
				
				$PO_DESC	= "$SPLCODE - $SPLDESC";
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$PO_TYPE	= $dataI['PO_TYPE'];
				$PO_STAT	= $dataI['PO_STAT'];
				
				$CollID		= "$PRJCODE~$PO_NUM";
				if($PO_TYPE == 1)
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				}
				else
				{
					$secUpd		= site_url('c_purchase/c_p180c21o/u77p180c21o_r0/?id='.$this->url_encryption_helper->encode_url($CollID));		
					$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdocro/?id='.$this->url_encryption_helper->encode_url($PO_NUM));							
				}
				
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDel		= site_url('c_purchase/c_p180c21o/d3l180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";
				$secVoid 	= base_url().'index.php/__l1y/trashPO/?id=';
				$voidID 	= "$secVoid~tbl_po_header~tbl_po_detail~PO_NUM~$PO_NUM~PRJCODE~$PRJCODE";

				// CEK IR
					$sqlIRC	= "tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$PO_NUM' AND IR_STAT NOT IN (5,9)";
					$resIRC = $this->db->count_all($sqlIRC);

					if($resIRC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
				
				if($PO_STAT == 1 || $PO_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($PO_STAT == 3)
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='".$disCl."(".$noU.")' title='Batal' ".$disV.">
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-check'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Batal' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] 	= array($dataI['PO_CODE'],
										  	$PO_DATEV,
										  	$SPLDESC,
										  	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-opencart margin-r-5'></i>".number_format($PO_TOTCOST,2)."</strong>
												</div>
											  	<div style='margin-left: 20px; font-style: italic'>
											  		".$dataI['PO_NOTES']."
											</div>
										  	",
										  	$dataI['PR_CODE'],
										 	$PO_PLANIRV,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44p180c21o_p0_ovh() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN069';
			$data["MenuApp"] 	= 'MN020';
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
			$data['form_action']	= site_url('c_purchase/c_p180c21o/add_process_ovh');
			$cancelURL				= site_url('c_purchase/c_p180c21o/gl180c21po_ovh/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); // back to data list PO
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// Untuk penomoran secara systemik
			$data['countVend']		= $this->m_purchase_po->count_all_num_rowsVend();
			$data['vwvendor'] 		= $this->m_purchase_po->viewvendor()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN069';
			$data["MenuCode"] 		= 'MN069';
			$data["MenuCode1"] 		= 'MN020';
			$data['PRJCODE_HO']		= $this->data['PRJCODE_HO'];
			$data['viewDocPattern'] = $this->m_purchase_po->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN019';
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
	
			$this->load->view('v_purchase/v_purchase_po/v_po_ovh_form', $data);
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
			$num_rows 		= $this->m_purchase_po->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_po->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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

				// GET PO DETAIL utk prosedure upah angkut
					$TOT_POVOL 		= 0;
					$TOT_POAMOUNT	= 0;
					$this->db->select_sum("A.PO_VOLM","TOT_POVOL");
					$this->db->select_sum("(A.PO_VOLM * A.PO_PRICE)","TOT_POAMOUNT");
					$this->db->from("tbl_po_detail A");
					$this->db->join("tbl_po_header B","B.PO_NUM = A.PO_NUM","inner");
					$this->db->where(["B.PRJCODE" => $PRJCODE, "A.ITM_CODE" => $ITM_CODE,
									  "A.JOBCODEID" => $JOBCODEID, "A.ISCOST" => 1, "B.PO_STAT" => 2]);
					$sqlGETPO 		= $this->db->get();
					foreach($sqlGETPO->result() as $rowGETPO):
						$TOT_POVOL 		= $rowGETPO->TOT_POVOL;
						$TOT_POAMOUNT	= $rowGETPO->TOT_POAMOUNT;
					endforeach;

				// LS PROCEDURE 1
					if($JOBUNIT == 'LS')
					{
						$TOT_REQ 	= $REQ_AMOUNT + $TOT_PAMN;
						$MAX_REQ	= $TOT_AMOUNTBG - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT + $TOT_POAMOUNT;
						$TOT_BUDG	= $TOT_AMOUNTBG;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLMBG - $TOT_REQ;			// 14
						// $PO_VOLM	= $PO_VOLM;
						$PO_VOLM 	= $PO_VOLM + $TOT_POVOL;
						$TOT_BUDG	= $TOT_VOLMBG;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLMBG - $TOT_REQ - $PO_VOLM;
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

					// Update [iyan] - GET ITM_CATEG
                    $ITM_CATEG = $this->db->get_where('tbl_item', ['ITM_CODE' => $ITM_CODE])->row('ITM_CATEG');
                    // End Update [iyan]

				// OTHER SETT
						if($disabledB == 0)
						{
							$chkBox		= "<input type='checkbox' name='chk3' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."|".$ITM_CATEG."' onClick='pickThis3(this);'/>";
						}
						else
						{
							$chkBox		= "<input type='checkbox' name='chk3' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."|".$ITM_CATEG."' style='display: none' />";
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
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add_process_ovh() // OK
	{
		$this->load->model('m_purchase/m_purchase_po/m_purchase_po', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$PO_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$completeName 	= $this->session->userdata['completeName'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN069';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$PO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$DEPCODE		= $this->input->post('DEPCODE');
			$PO_CODE 		= $this->input->post('PO_CODE');
			$PO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			if($PRJCODE == 'KTR')
				$PO_TYPE	= 1;
			else
				$PO_TYPE	= 2;

			$PO_TYPE 		= $this->input->post('PO_TYPE');
			$PO_CAT			= 2;								// Direct
			$PO_CAT			= $PO_CAT;						
			$SPLCODE 		= $this->input->post('SPLCODE');
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PR_CODE 		= $this->input->post('PR_CODE');			
			$PO_CURR 		= $this->input->post('PO_CURR'); 	// IDR or USD
			if($PO_CURR == 'IDR')
			{
				$PO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_purchase_po->get_Rate($PO_CURR);
				$PO_CURRATE	= $getRate;
			}
			
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PO_PLANIR		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_PLANIR')))); // Tanggal Terima
			$PO_NOTESIR 	= $this->input->post('PO_NOTESIR');
			$PO_NOTES 		= addslashes($this->input->post('PO_NOTES'));
			$PO_PAYNOTES 	= $this->input->post('PO_PAYNOTES');
			$JOBCODE 		= $this->input->post('JOBCODE');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_DPPER		= $this->input->post('PO_DPPER');
			$PO_DPVAL		= $this->input->post('PO_DPVAL');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');
			$PO_CONTRNO		= $this->input->post('PO_CONTRNO');
			
			$AddPO			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
									'PO_TYPE' 		=> $PO_TYPE,
									'PO_CAT'		=> $PO_CAT,
									'PO_DATE'		=> $PO_DATE,
									'PO_DUED'		=> $PO_DUED,
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'SPLCODE' 		=> $SPLCODE,
									'JOBCODE'		=> $JOBCODE,
									'PR_NUM' 		=> $PR_NUM,
									'PR_CODE'		=> $PR_CODE,
									'PO_CURR' 		=> $PO_CURR, 
									'PO_CURRATE' 	=> $PO_CURRATE,									
									'PO_PAYTYPE' 	=> $PO_PAYTYPE, 
									'PO_TENOR' 		=> $PO_TENOR, 
									'PO_PLANIR'		=> $PO_PLANIR,
									'PO_NOTES' 		=> $PO_NOTES,
									'PO_PAYNOTES'	=> $PO_PAYNOTES,
									'PO_NOTESIR' 	=> $PO_NOTESIR,
									'PO_DPPER' 		=> $PO_DPPER,
									'PO_DPVAL' 		=> $PO_DPVAL,
									'PO_CREATER'	=> $DefEmp_ID,
									'PO_CREATED'	=> $PO_CREATED,
									'PO_STAT'		=> $PO_STAT,
									'PO_INVSTAT'	=> 0,
									'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
									'PO_RECEIVCP'	=> $PO_RECEIVCP,
									'PO_SENTROLES'	=> $PO_SENTROLES,
									'PO_REFRENS'	=> $PO_REFRENS,
									'PO_CONTRNO'	=> $PO_CONTRNO,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_purchase_po->add($AddPO); // Insert tb_po_header
			
			$PO_TOTCOST	= 0;
			$PO_TAX 	= 0;
			$maxNo	= 0;
			$sqlMax = "SELECT MAX(PO_ID) AS maxNo FROM tbl_po_detail";
			$resMax = $this->db->query($sqlMax)->result();
			foreach($resMax as $rowMax) :
				$maxNo = $rowMax->maxNo;		
			endforeach;
			foreach($_POST['data'] as $d)
			{
				$maxNo			= $maxNo + 1;
				$d['PO_ID']		= $maxNo;
				$d['PO_NUM']	= $PO_NUM; 
				$d['DEPCODE']	= $DEPCODE;
				$ITMAMOUNT		= $d['PO_COST'];
				$PO_TOTCOST		= $PO_TOTCOST + $ITMAMOUNT;

				// GET HEADER
					$JOBID 		= $d['JOBCODEID'];
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

				$d['JOBPARENT']		= $JOBIDH;
				$d['JOBPARDESC']	= $JOBDSH;

				$ITMCODE			= $d['ITM_CODE'];
				$ITM_CATEG 			= "M";
				$sqlITMNM			= "SELECT ITM_CATEG FROM tbl_item WHERE ITM_CODE = '$ITMCODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resITMNM			= $this->db->query($sqlITMNM)->result();
				foreach($resITMNM as $rowITMNM) :
					$ITM_CATEG		= $rowITMNM->ITM_CATEG;
				endforeach;

				$ISCOST 			= 0;
				if($ITM_CATEG == 'UA')
					$ISCOST 		= 1;

				$d['ISCOST']		= $ISCOST;

				$this->db->insert('tbl_po_detail',$d);

				// CEK TAX
					$TAXPRICE1	= $d['TAXPRICE1'];
					$PO_TAX 	= $PO_TAX + $TAXPRICE1;

				// UPDATE LASTP
					if($PO_STAT == 2)
					{
						$JOBID 	= $d['JOBCODEID'];
						$ITMC  	= $d['ITM_CODE'];
						$LASTP 	= $d['PO_PRICE'];
						$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $LASTP
									WHERE JOBCODEID = '$JOBID' AND ITM_CODE = '$ITMC' AND PRJCODE = '$PRJCODE'";
						//$this->db->query($updLP);
					}
			}
			
			$updPOH			= array('PO_TOTCOST' => $PO_TOTCOST,
									'PO_TAXAMN'	 => $PO_TAX);
			$this->m_purchase_po->updatePOH($PO_NUM, $updPOH);
			// UPDATE DETAIL
				$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('PO_STAT');			// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $PO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_po_header",	// TABLE NAME
										'KEY_NAME'		=> "PO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "PO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $PO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_PO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_PO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_PO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_PO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_PO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_PO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "PO_NUM",
										'DOC_CODE' 		=> $PO_NUM,
										'DOC_STAT' 		=> $PO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_po_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN069';
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

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
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
			
			$url			= site_url('c_purchase/c_p180c21o/gl180c21po_ovh/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_AllDataIITMDET()
	{
		$PRJCODE	= $_GET['id'];
		$PO_NUM		= $_GET['PO_NUM'];
		$PR_NUMX 	= $_GET['PR_NUM'];
		$PO_STAT 	= $_GET['PO_STAT'];

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;

			if($TranslCode == 'usedItm')$usedItm = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
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
									"ITM_NAME", 
									"PO_DESC");

			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_po->get_AllDataITMDETC($PRJCODE, $PO_NUM, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataITMDETL($PRJCODE, $PO_NUM, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$GT_ITMPRICE	= 0;
			$POID 			= 0;
			$ITMVOL_R 		= 0;
			foreach ($query->result_array() as $dataI) 
			{
				$PO_ID 			= $dataI['PO_ID'];
				$PO_NUM 		= $dataI['PO_NUM'];
				$DocNumber 		= $PO_NUM;
				$PO_CODE 		= $dataI['PO_CODE'];
				$PRJCODE		= $dataI['PRJCODE'];
				$PR_NUM			= $dataI['PR_NUM'];
				$JOBCODEDET		= $dataI['JOBCODEDET'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBPARDESC		= $dataI['JOBPARDESC'];
				$PRD_ID 		= $dataI['PRD_ID'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$ITM_NAME 		= $dataI['ITM_NAME'];
				$ITM_NAME 		= wordwrap($ITM_NAME, 60, "<br>", TRUE);
				$ITM_GROUP 		= $dataI['ITM_GROUP'];
				$ITM_UNIT 		= strtoupper($dataI['ITM_UNIT']);
				$ITM_PRICE 		= $dataI['ITM_PRICE'];
				$PR_VOLM 		= $dataI['PR_VOLM'];
				$PR_AMOUNT		= $PR_VOLM * $ITM_PRICE;
				$PO_VOLM 		= $dataI['PO_VOLM'];
				$PO_CVOL 		= $dataI['PO_CVOL'];
				$PO_CTOTAL 		= $dataI['PO_CTOTAL'];
				$PO_PRICE 		= $dataI['PO_PRICE'];
				$PO_COST 		= $dataI['PO_COST'];

				$IR_VOLM 		= $dataI['IR_VOLM'];
				$IR_PRICE 		= $dataI['IR_AMOUNT'];
				$PO_DISP 		= $dataI['PO_DISP'];
				$PO_DISC 		= $dataI['PO_DISC'];

				// START : GET BUDGET
					$s_BUDGVAL		= "SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
										PR_VOL, PR_VOL_R, PR_CVOL, PO_VOL, PO_VOL_R, PO_CVOL, PO_VAL, PO_VAL_R, PO_CVAL,
										UM_VOL, UM_VAL, UM_VOL_R, UM_VAL_R,
										WO_VOL, WO_VAL, WO_VOL_R, WO_VAL_R, WO_CVOL, WO_CVAL, OPN_VOL, OPN_VAL, OPN_VOL_R, OPN_VAL_R,
										VCASH_VOL, VCASH_VAL, VCASH_VOL_R, VCASH_VAL_R, VLK_VOL, VLK_VAL, VLK_VOL_R, VLK_VAL_R,
										PPD_VOL, PPD_VAL, PPD_VOL_R, PPD_VAL_R,
										AMD_VOL, AMD_VOL_R, AMD_VAL, AMD_VAL_R, AMDM_VOL, AMDM_VAL
										FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$r_BUDGVAL		= $this->db->query($s_BUDGVAL)->result();
					foreach($r_BUDGVAL as $rowJOBDR) :
						$ITM_VOLM 		= $rowJOBDR->ITM_VOLM;		// BUDG VOL
						$RAP_PRICE 		= $rowJOBDR->ITM_PRICE;		// BUDG PRICE
						$ITM_LASTP 		= $rowJOBDR->ITM_LASTP;		// LAST PRICE
						$ITM_BUDG 		= $rowJOBDR->ITM_BUDG;		// BUDG VAL
						$PR_VOL 		= $rowJOBDR->PR_VOL;		// SPP VOL
						$PR_VOL_R 		= $rowJOBDR->PR_VOL_R;		// SPP VOL_R
						$PR_CVOL 		= $rowJOBDR->PR_CVOL;		// SPP CANCEL VOL

						$PO_VOL 		= $rowJOBDR->PO_VOL; 		// PO VOL
						$PO_VOL_R 		= $rowJOBDR->PO_VOL_R; 		// PO VOL_R
						$PO_VAL 		= $rowJOBDR->PO_VAL; 		// PO VAL
						$PO_VAL_R 		= $rowJOBDR->PO_VAL_R; 		// PO VAL_R
						$PO_CVOL 		= $rowJOBDR->PO_CVOL; 		// PO CANCEL VOL
						$PO_CVAL 		= $rowJOBDR->PO_CVAL; 		// PO CANCEL VAL

						$UM_VOL 		= $rowJOBDR->UM_VOL; 		// UM VOL
						$UM_VOL_R 		= $rowJOBDR->UM_VOL_R; 		// UM VOL_R
						$UM_VAL 		= $rowJOBDR->UM_VAL; 		// UM VAL
						$UM_VAL_R 		= $rowJOBDR->UM_VAL_R; 		// UM VAL_R

						$WO_VOL 		= $rowJOBDR->WO_VOL; 		// SPK VOL
						$WO_VOL_R 		= $rowJOBDR->WO_VOL_R; 		// SPK VOL_R
						$WO_VAL 		= $rowJOBDR->WO_VAL; 		// SPK VAL
						$WO_VAL_R 		= $rowJOBDR->WO_VAL_R; 		// SPK VAL_R
						$WO_CVOL 		= $rowJOBDR->WO_CVOL; 		// SPK CANCEL VOL
						$WO_CVAL 		= $rowJOBDR->WO_CVAL; 		// SPK CANCEL VAL_R

						$OPN_VOL 		= $rowJOBDR->OPN_VOL; 		// OPN VOL
						$OPN_VOL_R 		= $rowJOBDR->OPN_VOL_R; 	// OPN VOL_R
						$OPN_VAL 		= $rowJOBDR->OPN_VAL; 		// OPN VAL
						$OPN_VAL_R 		= $rowJOBDR->OPN_VAL_R; 	// OPN VAL_R

						$VCASH_VOL 		= $rowJOBDR->VCASH_VOL; 	// VCASH VOL
						$VCASH_VOL_R 	= $rowJOBDR->VCASH_VOL_R; 	// VCASH VOL_R
						$VCASH_VAL 		= $rowJOBDR->VCASH_VAL; 	// VCASH VAL
						$VCASH_VAL_R 	= $rowJOBDR->VCASH_VAL_R; 	// VCASH VAL_R

						$VLK_VOL 		= $rowJOBDR->VLK_VOL; 		// VLK VOL
						$VLK_VOL_R 		= $rowJOBDR->VLK_VOL_R; 	// VLK VOL_R
						$VLK_VAL 		= $rowJOBDR->VLK_VAL; 		// VLK VAL
						$VLK_VAL_R 		= $rowJOBDR->VLK_VAL_R; 	// VLK VAL_R

						$PPD_VOL 		= $rowJOBDR->PPD_VOL; 		// PPD VOL
						$PPD_VOL_R 		= $rowJOBDR->PPD_VOL_R; 	// PPD VAL_R
						$PPD_VAL 		= $rowJOBDR->PPD_VAL; 		// PPD VOL
						$PPD_VAL_R 		= $rowJOBDR->PPD_VAL_R; 	// PPD VAL_R

						$AMD_VOL 		= $rowJOBDR->AMD_VOL; 		// AMD VOL
						$AMD_VOL_R 		= $rowJOBDR->AMD_VOL_R; 	// AMD VOL_R
						$AMD_VAL 		= $rowJOBDR->AMD_VAL; 		// AMD VAL
						$AMD_VAL_R 		= $rowJOBDR->AMD_VAL_R; 	// AMD VAL_R
						$AMDM_VOL 		= $rowJOBDR->AMDM_VOL; 		// AMD MIN VOL
						$AMDM_VAL 		= $rowJOBDR->AMDM_VAL; 		// AMD MIN VAL

						$TREQ_VOL 		= ($PR_VOL - $PR_CVOL) + ($WO_VOL - $PO_CVOL) + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
						$TREQ_VAL 		= ($PO_VAL - $PO_CVAL) + ($WO_VAL - $WO_CVAL) + $VCASH_VAL + $VLK_VAL + $PPD_VAL;

						$TREQ_VOL_R		= $PR_VOL_R + $WO_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;		// VOL REQUEST NEW, CONFIRMED &  WAITING
						$TREQ_VAL_R		= $PO_VAL_R + $WO_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;		// VAL REQUEST NEW, CONFIRMED &  WAITING

						$TUSED_VOL 		= $UM_VOL + $OPN_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
						$TUSED_VOL_R	= $UM_VOL_R + $OPN_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;		// VAL PENGGUNAAN NEW, CONFIRMED &  WAITING
						$TUSED_VAL 		= $UM_VAL + $OPN_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
						$TUSED_VAL_R	= $UM_VAL_R + $OPN_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;		// VAL PENGGUNAAN NEW, CONFIRMED &  WAITING
					endforeach;

					$RAP_PRICE 		= $RAP_PRICE;
					$TBUDG_VAL		= $ITM_BUDG + $AMD_VAL - $AMDM_VAL;
					if($TBUDG_VAL == '')
						$TBUDG_VAL	= 0;
				
				// START : GET TOTAL ORDERED VOLUME
					// 1. FROM CURRENT DOCUMENT AND OTHERS ROW
						$ORDERED_VALX		= 0;
						$s_TOTPOVOL0		= "SELECT IFNULL(SUM(A.PO_COST), 0) AS ORDERED_VALX
													FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.PO_NUM = '$PO_NUM'
													AND A.PR_NUM = '$PR_NUM'";
						$r_TOTPOVOL0		= $this->db->query($s_TOTPOVOL0)->result();
						foreach($r_TOTPOVOL0 as $rw_TOTPOVOL0) :
							$ORDERED_VALX	= $rw_TOTPOVOL0->ORDERED_VALX;
						endforeach;

					// 1. FROM CURRENT DOCUMENT AND OTHERS ROW
						$ORDERED_VOL0		= 0;
						$ORDERED_VAL0		= 0;
						$ORDERED_CVOL0		= 0;
						$ORDERED_CVAL0		= 0;
						$s_TOTPOVOL0		= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL, IFNULL(SUM(A.PO_COST), 0) AS ORDERED_VAL,
													IFNULL(SUM(A.PO_CVOL), 0) AS ORDERED_CVOL, IFNULL(SUM(A.PO_CTOTAL), 0) AS ORDERED_CVAL
													FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.PO_NUM = '$PO_NUM'
													AND A.PR_NUM = '$PR_NUM'
													AND A.ITM_CODE = '$ITM_CODE'
													AND A.PRD_ID = $PRD_ID";
						$r_TOTPOVOL0		= $this->db->query($s_TOTPOVOL0)->result();
						foreach($r_TOTPOVOL0 as $rw_TOTPOVOL0) :
							$ORDERED_VOL0	= $rw_TOTPOVOL0->ORDERED_VOL;
							$ORDERED_VAL0	= $rw_TOTPOVOL0->ORDERED_VAL;
							$ORDERED_CVOL0 	= $rw_TOTPOVOL0->ORDERED_CVOL;
							$ORDERED_CVAL0 	= $rw_TOTPOVOL0->ORDERED_CVAL;
						endforeach;

					// 2. FROM OTHERS DOCUMENT
						$ORDERED_VOL1		= 0;
						$ORDERED_VAL1		= 0;
						$ORDERED_CVOL1		= 0;
						$ORDERED_CVAL1		= 0;
						$s_TOTPOVOL1	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL, IFNULL(SUM(A.PO_COST), 0) AS ORDERED_VAL,
												IFNULL(SUM(A.PO_CVOL), 0) AS ORDERED_CVOL, IFNULL(SUM(A.PO_CTOTAL), 0) AS ORDERED_CVAL
											FROM tbl_po_detail A
												INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
											WHERE B.PRJCODE = '$PRJCODE'
												AND A.JOBCODEID = '$JOBCODEID'
												AND A.PO_NUM != '$PO_NUM'
												AND A.ITM_CODE = '$ITM_CODE'
												AND A.PR_NUM = '$PR_NUM' AND B.PO_STAT NOT IN (5,9)
												AND A.PRD_ID = $PRD_ID";
						$r_TOTPOVOL1	= $this->db->query($s_TOTPOVOL1)->result();
						foreach($r_TOTPOVOL1 as $rw_TOTPOVOL1) :
							$ORDERED_VOL1	= $rw_TOTPOVOL1->ORDERED_VOL;
							$ORDERED_VAL1	= $rw_TOTPOVOL1->ORDERED_VAL;
							$ORDERED_CVOL1 	= $rw_TOTPOVOL1->ORDERED_CVOL;
							$ORDERED_CVAL1 	= $rw_TOTPOVOL1->ORDERED_CVAL;
						endforeach;

				// END : GET TOTAL ORDERED VOLUME
			
				//$TOTVAL_OTHDOC 	= ($ORDERED_VAL1 + $ORDERED_VAL1A - $ORDERED_CVAL1) + $ORDERED_VAL2 + $ORDERED_VAL3 + $ORDERED_VAL4;

				$TOTVAL_OTHDOC 		= $TREQ_VAL - $PO_COST;

				// IS LS UNIT
					$s_ISLS 		= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
					$isLS 			= $this->db->count_all($s_ISLS);

				// ADA REGULASI BARU UNTUK OP OVERHEAD
					$ITM_AVAIL 		= 0;
					$ITM_REMVOL		= $PR_VOLM - $ORDERED_VOL0 - $ORDERED_VOL1 + $PO_VOLM;
					$ITM_REMVAL		= $TBUDG_VAL - $TREQ_VAL - $ORDERED_VALX - $PO_COST;
					$ITM_REMVAL_JOB	= $TBUDG_VAL - $TREQ_VAL;

					/*if($isLS == 1 && $ITM_REMVAL > 0)				// ITEM IS LS TYPE, SO JUST VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;
					elseif($ITM_REMVOL > 0 && $ITM_REMVAL > 0) 		// ITEM IS NON-LS TYPE, SO VOL AND VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;*/

					if($ITM_REMVOL > 0 && $ITM_REMVAL > 0) 			// ITEM LS AND NON-LS TYPE, VOL AND VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;

				// GET JODESC
				// $JOBPARENT		= '';
				$JOBDESC		= $JOBPARDESC;
				if($JOBPARDESC == '')
				{
					$sqlJPAR	= "SELECT JOBCODEID AS JOBPARENT, A.JOBDESC FROM tbl_joblist_detail A 
									WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
										WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE') AND PRJCODE = '$PRJCODE'";
					$resJPAR	= $this->db->query($sqlJPAR)->result();
					foreach($resJPAR as $rowJPAR) :
						// $JOBPARENT	= $rowJPAR->JOBPARENT;
						$JOBDESC	= $rowJPAR->JOBDESC;
					endforeach;
				}

				$ITM_LASTP 	= 0;
				$s_lASTP	= "SELECT ITM_LASTP FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$r_lASTP	= $this->db->query($s_lASTP)->result();
				foreach($r_lASTP as $rw_lASTP) :
					$ITM_LASTP	= $rw_lASTP->ITM_LASTP;
				endforeach;

				$PR_DESC_ID		= $dataI['PR_DESC_ID'];
				if($PR_DESC_ID == '')
					$PR_DESC_ID = 0;
				
				$PO_DESC_ID		= $dataI['PO_DESC_ID'];
				if($PO_DESC_ID == '')
					$PO_DESC_ID = 0;

				$PO_DESC		= $dataI['PO_DESC'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];

				$PO_COST 		= $dataI['PO_COST'];			// Non-PPn
				$itemConvertion	= 1;

				// GET PO_VOLM GROUP BY ITM_CODE, PO_DESC_ID
					$getPO_R 	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ITMVOLM_R
										FROM tbl_po_detail A
									WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'
									AND A.ITM_CODE = '$ITM_CODE' AND A.PO_DESC_ID = '$PO_DESC_ID'";

					$resPO_R 	= $this->db->query($getPO_R);
					foreach($resPO_R->result() as $rPO_R):
						$ITMVOLM_R 	= $rPO_R->ITMVOLM_R;
					endforeach;

				// Update [iyan] - GET ITM_CATEG
				$ITM_CATEG = $this->db->get_where('tbl_item', ['ITM_CODE' => $ITM_CODE])->row('ITM_CATEG');
				// End Update [iyan]

				$PO_COSTnPPn	= $PO_COST + $TAXPRICE1 - $TAXPRICE2;

				$TAXLA_PERC = 0;
				if($TAXCODE1 != '')
				{
					$TAXLA_DESC = "-";
					$sPPN		= "SELECT TAXLA_NUM, TAXLA_CODE, TAXLA_DESC
									FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
					$rPPN		= $this->db->query($sPPN)->result();
					foreach($rPPN as $rwPPN) :
						$TAXLA_NUM	= $rwPPN->TAXLA_NUM;
						$TAXLA_DESC	= $rwPPN->TAXLA_DESC;
					endforeach;
				}
				
				$isDisabled 	= 1;
				$vWPO_ORDER		= "";
				$PO_CTOTALvW 	= "";
				if($PO_STAT == 1 || $PO_STAT == 4)
				{
					$isDisabled = 0;
					$BTN_CNCVW = "<a href='#' onClick='deleteRow(".$noU.")' title='Delete Document' class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a>";

					$vWPO_ORDER 	= "<input type='text' class='form-control' style='min-width:90px; max-width:90px; text-align:right' name='PO_VOLMX".$noU."' id='PO_VOLMX".$noU."' value='".number_format($PO_VOLM, 2)."' onKeyPress='return isIntOnlyNew(event);' onBlur='getValuePO(".$noU.");' ".$isDisabled.">";
					$vWITM_PRICE 	= "<input type='text' class='form-control' style='text-align:right; min-width:90px' name='PO_PRICEX".$noU."' id='PO_PRICEX".$noU."' size='10' value='".number_format($ITM_PRICE, 2)."' onKeyPress='return isIntOnlyNew(event);' onBlur='getValuePO(".$noU.");'>";

					if($ITM_CATEG != 'UA') $PR_VOLMv = number_format($PR_VOLM, 2);
					else $PR_VOLMv = "-";
					if($ITM_CATEG != 'UA') $ITM_REMVOLv = number_format($ITM_REMVOL, 2);
					else $ITM_REMVOLv = "-";
					if($ITM_CATEG != 'UA') $PO_DISPv = "<input type='text' class='form-control' size='10'  style=' min-width:70px; max-width:150px; text-align:right' name='PO_DISPx".$noU."' id='PO_DISPx".$noU."' value='".number_format($PO_DISP, 2)."' onBlur='countDisp(this.value, ".$noU.");' >";
					else $PO_DISPv = "-";
					if($ITM_CATEG != 'UA') $PO_DISCv = "<input type='text' class='form-control' style='min-width:90px; max-width:350px; text-align:right' name='PO_DISC".$noU."' id='PO_DISC".$noU."' value='".number_format($PO_DISC, 2)."' onBlur='countDisc(this.value, ".$noU.");' >";
					else $PO_DISCv = "-";

					$sPPN		= "SELECT TAXLA_NUM, TAXLA_DESC, 'PPN' AS TAXTYPE FROM tbl_tax_ppn
									UNION ALL
									SELECT TAXLA_NUM, TAXLA_DESC, 'PPH' AS TAXTYPE FROM tbl_tax_la";
					$rPPN		= $this->db->query($sPPN)->result();
					$opt_TAX1 	= [];
					foreach($rPPN as $rwPPN) :
						$TAXLA_NUM	= $rwPPN->TAXLA_NUM;
						$TAXLA_DESC	= $rwPPN->TAXLA_DESC;
						$TAXTYPE	= $rwPPN->TAXTYPE;
						if ($TAXLA_NUM == $TAXCODE1) $selected = "selected";
						else $selected = "";
						$opt_TAX1[] = "<option value='".$TAXLA_NUM."' $selected>".$TAXLA_DESC."</option>";
					endforeach;

					$opt_TAXv1 	= join("", $opt_TAX1);

					if($ITM_CATEG != 'UA') $ITM_TAX1v = "<select name='data[".$noU."][TAXCODE1]' class='form-control select2' id='data".$noU."TAXCODE1'  onChange='getValuePO(".$noU.");' style='width:150px;'><option value='0'> --- </option>".$opt_TAXv1."</select>";
					else $ITM_TAX1v = "-<input type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1'  value='0'>";

					$vWPO_COSTnPPn 	= "<input type='text' class='form-control' style='min-width:110px; max-width:350px; text-align:right' name='PO_COSTnPPn".$noU."' id='PO_COSTnPPn".$noU."' value='".number_format($PO_COSTnPPn, 2)."' size='5' >";

					$PO_DESCv 	= "<div class='input-group'>
										<div class='input-group-btn'>
											<button type='button' class='btn btn-primary' onClick='getDL(".$noU.")'><i class=' fa fa-commenting'></i></button>
										</div>
										<input type='text' name='data[".$noU."][PO_DESC]' id='data".$noU."PO_DESC' size='20' value='".$PO_DESC."' class='form-control' style='min-width:110px; max-width:500px; text-align:left' readonly>
									</div>";
					
				}
				elseif($PO_STAT == 3)
				{
					$BTN_CNCVW	= "<a onClick='cancelRow(".$noU.")' title='Batalkan Volume' class='btn btn-danger btn-xs'><i class='fa fa-repeat'></i></a>";

					if($PO_CVOL != 0) $PO_CVOLv = "<div style='color:red;'><i class='fa fa-sort-down (alias)'></i>".number_format($PO_CVOL, 2)."</div>";
					else $PO_CVOLv = "";

					$vWPO_ORDER 	= number_format($PO_VOLM, 2)."$PO_CVOLv";
					$vWITM_PRICE 	= number_format($ITM_PRICE, 2);

					if($ITM_CATEG != 'UA') $PR_VOLMv = number_format($PR_VOLM, 2);
					else $PR_VOLMv = "-";
					if($ITM_CATEG != 'UA') $ITM_REMVOLv = number_format($ITM_REMVOL, 2);
					else $ITM_REMVOLv = "-";
					if($ITM_CATEG != 'UA') $PO_DISPv = number_format($PO_DISP, 2);
					else $PO_DISPv = "-";
					if($ITM_CATEG != 'UA') $PO_DISCv = number_format($PO_DISC, 2);
					else $PO_DISCv = "-";

					$ITM_TAX1v = "-<input type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1'  value='0'>";
					if($ITM_CATEG != 'UA') 
					{
						if($TAXCODE1 != '') $ITM_TAX1v = "$TAXLA_DESC<input style='text-align:right' type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1' value='".$TAXCODE1."'>";
					}
					
					$PO_CTOTALnPPn	= $PO_CTOTAL + ($PO_CTOTAL * $TAXLA_PERC/100);
					if($PO_CTOTALnPPn != 0) $PO_CTOTALnPPnv = "<div style='color:red;'><i class='fa fa-sort-down (alias)'></i> ".number_format($PO_CTOTALnPPn, 2)."</div>";
					else $PO_CTOTALnPPnv = "";

					$vWPO_COSTnPPn	= number_format($PO_COSTnPPn, 2)."$PO_CTOTALnPPnv";

					$PO_DESCv 	= $PO_DESC;
				}
				else
				{
					$BTN_CNCVW = $noU;

					if($PO_CVOL != 0) $PO_CVOLv = "<div style='color:red;'><i class='fa fa-sort-down (alias)'></i>".number_format($PO_CVOL, 2)."</div>";
					else $PO_CVOLv = "";

					$vWPO_ORDER 	= number_format($PO_VOLM, 2)."$PO_CVOLv";
					$vWITM_PRICE 	= number_format($ITM_PRICE, 2);

					if($ITM_CATEG != 'UA') $PR_VOLMv = number_format($PR_VOLM, 2);
					else $PR_VOLMv = "-";
					if($ITM_CATEG != 'UA') $ITM_REMVOLv = number_format($ITM_REMVOL, 2);
					else $ITM_REMVOLv = "-";
					if($ITM_CATEG != 'UA') $PO_DISPv = number_format($PO_DISP, 2);
					else $PO_DISPv = "-";
					if($ITM_CATEG != 'UA') $PO_DISCv = number_format($PO_DISC, 2);
					else $PO_DISCv = "-";

					$ITM_TAX1v = "-<input type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1'  value='0'>";
					if($ITM_CATEG != 'UA') 
					{
						if($TAXCODE1 != '') $ITM_TAX1v = "$TAXLA_DESC<input style='text-align:right' type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1' value='".$TAXCODE1."'>";
					}

					$PO_CTOTALnPPn	= $PO_CTOTAL + ($PO_CTOTAL * $TAXLA_PERC/100);
					if($PO_CTOTALnPPn != 0) $PO_CTOTALnPPnv = "<div style='color:red;'><i class='fa fa-sort-down (alias)'></i> ".number_format($PO_CTOTALnPPn, 2)."</div>";
					else $PO_CTOTALnPPnv = "";

					$vWPO_COSTnPPn	= number_format($PO_COSTnPPn, 2)."$PO_CTOTALnPPnv";

					$PO_DESCv 	= $PO_DESC;
				}

				$REM_VOLPO 		= $PO_VOLM - $PO_CVOL - $IR_VOLM;
				// START : CANCEL VOL. PER ITEM
					$secShwD 	= base_url().'index.php/c_purchase/c_pr180d0c/cancelItem/?id=';
					$canShwRow 	= "$secShwD~$PO_NUM~$PRJCODE~$ITM_CODE~$ITM_NAME~$JOBPARDESC~$PO_VOLM~$IR_VOLM~$REM_VOLPO~$ITM_UNIT";
					$secDelD 	= base_url().'index.php/__l1y/cancelItem/?id=';
					$canclRow 	= "$secDelD~PO~$PRJCODE~$PO_ID~$PO_NUM~$JOBCODEID~$ITM_CODE~$ITM_NAME";
				// END : CANCEL VOL. PER ITEM

				$output['data'][] 	= ["$BTN_CNCVW
										<input style='display:none' type='Checkbox' id='data[".$noU."][chk]' name='data[".$noU."][chk]' value='".$noU."' onClick='pickThis(this,".$noU.")'>
										<input type='Checkbox' style='display:none' id='chk' name='chk' value=''>
										<input type='hidden' name='urlgetID".$noU."' id='urlgetID".$noU."' value='".$canShwRow."'>
										<input type='hidden' name='urlcanD".$noU."' id='urlcanD".$noU."' value='".$canclRow."'>
										<input type='hidden' id='data".$noU."PO_ID' name='data[".$noU."][PO_ID]' value='".$PO_ID."' width='10' size='15'>
										<input type='hidden' id='data".$noU."PRD_ID' name='data[".$noU."][PRD_ID]' value='".$PRD_ID."' width='10' size='15'>
										<input type='hidden' id='data".$noU."ITM_CODE' name='data[".$noU."][ITM_CODE]' value='".$ITM_CODE."' width='10' size='15'>
										<input type='hidden' id='data".$noU."ITM_NAME' value='".$ITM_NAME."' >
										<input type='hidden' id='data".$noU."PO_NUM' name='data[".$noU."][PO_NUM]' value='".$DocNumber."' width='10' size='15'>
										<input type='hidden' id='data".$noU."PO_CODE' name='data[".$noU."][PO_CODE]' value='".$PO_CODE."' width='10' size='15'>
										<input type='hidden' id='data".$noU."PRJCODE' name='data[".$noU."][PRJCODE]' value='".$PRJCODE."' width='10' size='15'>
										<input type='hidden' id='data".$noU."PR_NUM' name='data[".$noU."][PR_NUM]' value='".$PR_NUMX."' width='10' size='15'>
										<input type='hidden' id='data".$noU."JOBCODEDET' name='data[".$noU."][JOBCODEDET]' value='".$JOBCODEDET."' width='10' size='15'>
										<input type='hidden' id='data".$noU."JOBCODEID' name='data[".$noU."][JOBCODEID]' value='".$JOBCODEID."' width='10' size='15'>
										<input type='hidden' id='data".$noU."JOBPARENT' name='data[".$noU."][JOBPARENT]' value='".$JOBPARENT."' width='10' size='15'>
										<input type='hidden' id='data".$noU."JOBPARDESC' name='data[".$noU."][JOBPARDESC]' value='".$JOBDESC."' width='10' size='15'>",
										$ITM_CODE,
										"$ITM_CODE : $ITM_NAME <span class='text-red' style='font-style: italic; font-weight: bold;'>(Hrg.RAP : ".number_format($RAP_PRICE,2)." )</span>
										<div style='font-style: italic;'>
											<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;$JOBDESC ($JOBPARENT)
										</div>
										<div style='font-style: italic;'>
											<i class='text-muted fa fa-commenting-o'></i>&nbsp;&nbsp;$PO_DESC
										</div>
										<div style='font-style: italic; margin-left: 15px;'>
											<span style='font-style: italic; font-weight: bold;'>
												RAP : &nbsp;&nbsp;".number_format($TBUDG_VAL,2)."
											</span>
											<span id='usedValue_".$noU."' style='font-style: italic; font-weight: bold; display: none;'>
												R : &nbsp;&nbsp;".number_format($TREQ_VAL,2)."
											</span>&nbsp;&nbsp;
											<span id='remValue_".$noU."' style='font-style: italic; font-weight: bold;'>
												S : ".number_format($ITM_REMVAL,2)."
											</span>
										</div>
										<input type='hidden' name='ITM_CATEG".$noU."' id='ITM_CATEG".$noU."' value='".$ITM_CATEG."'>",
										$PR_VOLMv,
										"$ITM_REMVOLv
										<input type='hidden' class='form-control' style='min-width:130px; max-width:200px; text-align:right' name='ITM_REMAIN".$noU."' id='ITM_REMAIN".$noU."' value='".$ITM_REMVOL."' >
										<input type='hidden' class='form-control' style='min-width:130px; max-width:200px; text-align:right' name='ITM_BUDGVAL".$noU."' id='ITM_BUDGVAL".$noU."' value='".$TBUDG_VAL."' >
										<input type='hidden' class='form-control' style='min-width:130px; max-width:200px; text-align:right' name='TOTVAL_OTHDOC".$noU."' id='TOTVAL_OTHDOC".$noU."' value='".$TOTVAL_OTHDOC."' >
										<input type='hidden' class='form-control' style='min-width:130px; max-width:200px; text-align:right' name='ITM_MAXREQ_AMN".$noU."' id='ITM_MAXREQ_AMN".$noU."' value='".$ITM_REMVAL."' >
										<input type='hidden' class='form-control' style='min-width:130px; max-width:200px; text-align:right' name='JOB_MAXREQ_AMN".$noU."' id='JOB_MAXREQ_AMN".$noU."' value='".$ITM_REMVAL_JOB."' >",
										"$vWPO_ORDER
										<input type='hidden' id='data".$noU."PR_VOLM' name='data[".$noU."][PR_VOLM]' value='".$PR_VOLM."'>
										<input type='hidden' id='data".$noU."PR_AMOUNT' name='data[".$noU."][PR_AMOUNT]' value='".$PR_AMOUNT."'>
										<input type='hidden' id='data".$noU."PO_VOLM' name='data[".$noU."][PO_VOLM]' value='".$PO_VOLM."'>",
										"<span id='ITMVOL_R".$PO_DESC_ID."".$noU."' style='text-align: right;'>".number_format($ITMVOLM_R, 2)."</span>",
										"<input type='text' class='form-control' style='text-align:center; max-width:90px' id='data".$noU."ITM_UNIT' name='data[".$noU."][ITM_UNIT]' value='".$ITM_UNIT."'>",
										"$vWITM_PRICE
										<input style='text-align:right' type='hidden' name='data[".$noU."][PO_PRICE]' id='data".$noU."PO_PRICE' size='6' value='".$ITM_PRICE."'>",
										"$PO_DISPv
										<input style='text-align:right' type='hidden' name='data[".$noU."][PO_DISP]' id='data".$noU."PO_DISP' value='".$PO_DISP."'>",
										"$PO_DISCv
										<input style='text-align:right' type='hidden' name='data[".$noU."][PO_DISC]' id='data".$noU."PO_DISC' value='".$PO_DISC."'>",
										"$ITM_TAX1v
										<input style='text-align:right' type='hidden' name='data[".$noU."][TAXPRICE1]' id='data".$noU."TAXPRICE1' value='".$TAXPRICE1."'>",
										"$vWPO_COSTnPPn
										<input style='text-align:right' type='hidden' name='data[".$noU."][PO_COST]' id='data".$noU."PO_COST' value='".$PO_COST."'>",
										"$PO_DESCv
										<input type='hidden' name='data[".$noU."][PR_DESC_ID]' id='data".$noU."PR_DESC_ID' size='20' value='".$PR_DESC_ID."' class='form-control' style='min-width:110px; max-width:500px; text-align:left'>
										<input type='hidden' name='data[".$noU."][PO_DESC_ID]' id='data".$noU."PO_DESC_ID' size='20' value='".$PO_DESC_ID."' class='form-control' style='min-width:110px; max-width:500px; text-align:left' data-toggle='modal' data-target='#mdl_addCMN'>"
									];

				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function getITMVOL_R()
	{
		$task 		= $this->input->post('task');
		$PRJCODE 	= $this->input->post('PRJCODE');
		$PR_NUM 	= $this->input->post('PR_NUM');
		$PO_NUM 	= $this->input->post('PO_NUM');
		$ITM_CODE	= $this->input->post('ITM_CODE');
		$PO_DESC_ID	= $this->input->post('PO_DESC_ID');
		$PO_VOLMBF 	= $this->input->post('PO_VOLMBF');
		$PO_VOLM 	= $this->input->post('PO_VOLM');

		if($task == 'add')
		{
			$getPO_R 	= "SELECT A.ITM_CODE, A.PR_DESC_ID AS PO_DESC_ID, IFNULL(SUM(A.PR_VOLM), 0) AS ITMVOLM_R
							FROM tbl_pr_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'
							AND A.ITM_CODE = '$ITM_CODE' AND A.PR_DESC_ID = '$PO_DESC_ID'";
		}
		else
		{
			$getPO_R 	= "SELECT A.ITM_CODE, A.PO_DESC_ID, IFNULL(SUM(A.PO_VOLM), 0) AS ITMVOLM_R
								FROM tbl_po_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'
							AND A.ITM_CODE = '$ITM_CODE' AND A.PO_DESC_ID = '$PO_DESC_ID'";
		}

		$resPO_R 	= $this->db->query($getPO_R);
		foreach($resPO_R->result() as $rPO_R):
			$data['ITM_CODE'] 		= $rPO_R->ITM_CODE;
			$data['PO_DESC_ID'] 	= $rPO_R->PO_DESC_ID;
			$ITMVOLM_R1 			= $rPO_R->ITMVOLM_R;
			$data['ITMVOLM_R'] 		= $ITMVOLM_R1 - $PO_VOLMBF + $PO_VOLM;
		endforeach;

		echo json_encode($data);
	}

	function trashFile()
	{
		$PO_NUM		= $this->input->post("PO_NUM");
		$PRJCODE	= $this->input->post("PRJCODE");
		// $index 		= $this->input->post("indexRow");
		$fileName	= $this->input->post("fileName");

		/* Change PO_DOC to array => upd: 2022-09-16
			$arrDOC = explode(", ", $PO_DOC);
			foreach($arrDOC as $r => $val):
				$getarr[] = $val;
			endforeach;

			unset($getarr[$index]); // Delete index array
		---------------------- End Hidden --------------------- */
		
		$this->m_purchase_po->delUPL_DOC($PO_NUM, $PRJCODE, $fileName); // Delete File
		
		// Delete file in path folder
			$path = "assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE/$fileName";
			unlink($path);
	}

	function downloadFile()
	{
		$this->load->helper('download');

		$fileName 	= $_GET['file'];
		$PRJCODE 	= $_GET['prjCode'];
		$path 		= "assets/AdminLTE-2.0.5/doc_center/uploads/PO_Document/$PRJCODE/$fileName";
		force_download($path, NULL);
	}

	function get_AllDataITMPR() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$THEROW			= 1;

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');
		date_default_timezone_set("Asia/Jakarta");

		$MonthAgo 		= date("Y-m-t", strtotime ('-1 month'));
		$C_YEAR 		= date("Y");
		$C_MONTH 		= date("m");

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

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
			$num_rows 		= $this->m_purchase_po->get_AllDataITMPRC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_purchase_po->get_AllDataITMPRL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$ITM_USED1		= $dataI['ITM_USED'];
				$ITM_USED_AM1	= $dataI['ITM_USED_AM'];
				$ITM_STOCK		= $dataI['ITM_STOCK'];
				$ITM_STOCK_AM	= $dataI['ITM_STOCK_AM'];
				$JOBCOST		= $dataI['ITM_BUDG'];
				$IS_LEVEL		= $dataI['IS_LEVEL'];
				$ISLAST			= $dataI['ISLAST'];

				$serialNumber		= '';
				$itemConvertion		= 1;
				$TOT_VOLMBG 		= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNTBG		= ($JOBVOLM * $JOBPRICE) + ($ADD_VOLM * $ADD_PRICE);
															
				$JOBDESCPAR		= '';
				/*$sqlJOBPAR		= "SELECT JOBDESC from tbl_joblist_detail
										WHERE JOBCODEID IN (SELECT X.JOBPARENT from tbl_joblist_detail X 
											WHERE X.JOBCODEID = '$JOBCODEID' AND X.PRJCODE = '$PRJCODE') LIMIT 1";*/
				$sqlJOBPAR		= "SELECT JOBDESC from tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJOBPAR		= $this->db->query($sqlJOBPAR)->result();
				foreach($resJOBPAR as $rowJOBPAR) :
					$JOBDESCPAR	= $rowJOBPAR->JOBDESC;
				endforeach;

				// GET ITM_NAME
					$ITM_NAME		= '';
					$ITM_CODE_H		= '';
					$ITM_TYPE		= '';
					$ACC_ID 		= '';
					$ITM_GROUP 		= '';
					$ITM_CATEG 		= '';
					$sqlITMNM		= "SELECT ITM_NAME, ITM_TYPE, ITM_UNIT, ACC_ID_UM AS ACC_ID, ITM_GROUP
										FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$resITMNM		= $this->db->query($sqlITMNM)->result();
					foreach($resITMNM as $rowITMNM) :
						$ITM_NAME	= $rowITMNM->ITM_NAME;
						$ITM_TYPE	= $rowITMNM->ITM_TYPE;
						$ITM_UNIT	= $rowITMNM->ITM_UNIT;
						$ACC_ID		= $rowITMNM->ACC_ID;
						$ITM_GROUP	= $rowITMNM->ITM_GROUP;
					endforeach;

				$JOBUNITV 		= strtoupper($ITM_UNIT);
				$JOBLEV			= $dataI['IS_LEVEL'];

				// USED AND RESERVE
					$ITM_USED1			= 0;
					$ITM_USED_AM1		= 0;
					$ITM_USEDR			= 0;
					$ITM_USEDR_AM		= 0;
					/*$sqlJOBDR			= "SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
											FROM tbl_journaldetail_pd
											WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
												AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)
											UNION
											SELECT SUM(ITM_VOLM) TOTVOL, SUM(ITM_VOLM * ITM_PRICE) AS TOTAMN
											FROM tbl_journaldetail_vcash
											WHERE JOBCODEID = '$JOBCODEID' AND proj_Code = '$PRJCODE'
												AND ITM_CODE = '$ITM_CODE' AND GEJ_STAT IN (1,2,7)";*/
					$sqlJOBDR			= "SELECT SUM(PR_VOL) AS TOTPRVOL, SUM(PR_VOL_R) AS TOTPRVOL_R, SUM(PR_CVOL) AS TOTPRCVOL,
											SUM(PO_VAL) AS TOTPOAMN, SUM(PO_VAL_R) AS TOTPOAMN_R, SUM(PO_CVAL) AS TOTPOCAMN,
											SUM(WO_VOL) AS TOTWOVOL, SUM(WO_VAL) AS TOTWOAMN, SUM(WO_VOL_R) AS TOTWOVOL_R, SUM(WO_VAL_R) AS TOTWOAMN_R, 
											SUM(WO_CVOL) AS TOTWOCVOL, SUM(WO_CVAL) AS TOTWOCAMN,
											SUM(VCASH_VOL) AS TOTVCVOL, SUM(VCASH_VAL) AS TOTVCAMN, SUM(VCASH_VOL_R) AS TOTVCVOL_R, SUM(VCASH_VAL_R) AS TOTVCAMN_R,
											SUM(VLK_VOL) AS TOTVLKVOL, SUM(VLK_VAL) AS TOTVLKAMN, SUM(VLK_VOL_R) AS TOTVLKVOL_R, SUM(VLK_VAL_R) AS TOTVLKAMN_R,
											SUM(PPD_VOL) AS TOTPPDVOL, SUM(PPD_VAL) AS TOTPPDAMN, SUM(PPD_VOL_R) AS TOTPPDVOL_R, SUM(PPD_VAL_R) AS TOTPPDAMN_R
											-- SUM(VCASH_VOL) AS TOTVOL, SUM(VCASH_VAL) AS TOTAMN, SUM(VCASH_VOL_R) TOTVOL_R, SUM(VCASH_VAL_R) AS TOTAMN_R
											FROM tbl_joblist_detail
											WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$resJOBDR			= $this->db->query($sqlJOBDR)->result();
					foreach($resJOBDR as $rowJOBDR) :
						/*$ITM_USEDR		= $ITM_USEDR+$rowJOBDR->TOTVOL;
						$ITM_USEDR_AM	= $ITM_USEDR_AM+$rowJOBDR->TOTAMN;*/
						// $ITM_USED1		= $rowJOBDR->TOTVOL;
						// $ITM_USED_AM1	= $rowJOBDR->TOTAMN;
						// $ITM_USEDR		= $rowJOBDR->TOTVOL_R;
						// $ITM_USEDR_AM	= $rowJOBDR->TOTAMN_R;

						// USED RAP SPP/SPK/VCASH/VLK/PPD => JOBCODEID --------------------------------
							$TOTPRVOL 		= $rowJOBDR->TOTPRVOL;		// SPP VOLM
							$TOTPRVOL_R 	= $rowJOBDR->TOTPRVOL_R;	// SPP VOLM_R
							$TOTPRCVOL 		= $rowJOBDR->TOTPRCVOL;		// SPP CANCEL VOLM
							$TOTPOAMN 		= $rowJOBDR->TOTPOAMN; 		// PO AMOUNT
							$TOTPOAMN_R 	= $rowJOBDR->TOTPOAMN_R; 	// PO AMOUNT_R
							$TOTPOCAMN 		= $rowJOBDR->TOTPOCAMN; 	// PO CANCEL AMOUNT
							$TOTWOVOL 		= $rowJOBDR->TOTWOVOL; 		// SPK VOLM
							$TOTWOAMN 		= $rowJOBDR->TOTWOAMN; 		// SPK AMOUNT
							$TOTWOVOL_R 	= $rowJOBDR->TOTWOVOL_R; 	// SPK VOLM_R
							$TOTWOAMN_R 	= $rowJOBDR->TOTWOAMN_R; 	// SPK AMOUNT_R
							$TOTWOCVOL 		= $rowJOBDR->TOTWOCVOL; 	// SPK CANCEL VOLM
							$TOTWOCAMN 		= $rowJOBDR->TOTWOCAMN; 	// SPK CANCEL AMOUNT
							$TOTVCVOL 		= $rowJOBDR->TOTVCVOL; 		// VCASH VOLM
							$TOTVCAMN 		= $rowJOBDR->TOTVCAMN; 		// VCASH AMOUNT
							$TOTVCVOL_R 	= $rowJOBDR->TOTVCVOL_R; 	// VCASH VOLM_R
							$TOTVCAMN_R 	= $rowJOBDR->TOTVCAMN_R; 	// VCASH AMOUNT_R
							$TOTVLKVOL 		= $rowJOBDR->TOTVLKVOL; 	// VLK VOLM
							$TOTVLKAMN 		= $rowJOBDR->TOTVLKAMN; 	// VLK AMOUNT
							$TOTVLKVOL_R 	= $rowJOBDR->TOTVLKVOL_R; 	// VLK VOLM_R
							$TOTVLKAMN_R 	= $rowJOBDR->TOTVLKAMN_R; 	// VLK AMOUNT_R
							$TOTPPDVOL 		= $rowJOBDR->TOTPPDVOL; 	// PPD VOLM
							$TOTPPDAMN 		= $rowJOBDR->TOTPPDAMN; 	// PPD AMOUNT
							$TOTPPDVOL_R 	= $rowJOBDR->TOTPPDVOL_R; 	// PPD VOLM_R
							$TOTPPDAMN_R 	= $rowJOBDR->TOTPPDAMN_R; 	// PPD AMOUNT_R

							$ITM_USED1 		= ($TOTPRVOL - $TOTPRCVOL) + ($TOTWOVOL - $TOTWOCVOL) + $TOTVCVOL + $TOTVLKVOL + $TOTPPDVOL;
							$ITM_USEDR 		= $TOTPRVOL_R + $TOTWOVOL_R + $TOTVCVOL_R + $TOTVLKVOL_R + $TOTPPDVOL_R;
							$ITM_USED_AM1 	= ($TOTPOAMN - $TOTPOCAMN) + ($TOTWOAMN - $TOTWOCAMN) + $TOTVCAMN + $TOTVLKAMN + $TOTPPDAMN;
							$ITM_USEDR_AM 	= $TOTPOAMN_R + $TOTWOAMN_R + $TOTVCAMN_R + $TOTVLKAMN_R + $TOTPPDAMN_R;
						// END USED RAP => JOBCODEID ----------------------------------------------------------------------
					endforeach;

					$RES_VW 		= "";
					if($ITM_USEDR_AM > 0)
					{
						$RES_VW		= 	"<div style='white-space:nowrap;'>
											<span class='text-red' style='white-space:nowrap;'><i class='glyphicon glyphicon-chevron-down'></i>
									  		".number_format($ITM_USEDR_AM, 2)."</span>
									  	</div>";
					}

				$s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				$r_isLS 	= $this->db->count_all($s_isLS);

				if($r_isLS > 0)
				{
					$ITM_LASTP 		= 0;
					// $ITM_USED 		= 1;
					$ITM_USED 		= $ITM_VOLMBG;
					$ITM_USED_AM 	= $ITM_USED_AM1 + $ITM_USEDR_AM;
					// $BUDG_REMVOLM	= 1;
					$BUDG_REMVOLM	= $ITM_VOLMBG;
					$BUDG_REMAMNT	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				}
				else
				{
					$ITM_USED 		= $ITM_USED1 + $ITM_USEDR;
					$ITM_USED_AM 	= $ITM_USED_AM1 + $ITM_USEDR_AM;
					$BUDG_REMVOLM	= $ITM_VOLMBG + $ADD_VOLM - $ITM_USED;
					$BUDG_REMAMNT	= $JOBCOST + $ADD_JOBCOST - $ITM_USED_AM;
				}

				$disabledB		= 0;
				$VOLM_DESC		= "success";
				$AMN_DESC		= "success";
               	if($BUDG_REMVOLM <= 0 && $BUDG_REMAMNT <= 0)
				{
                    $disabledB	= 1;
					$VOLM_DESC	= "danger";
					$AMN_DESC	= "danger";
				} 
				elseif($BUDG_REMAMNT <= 0)
				{
                    $disabledB	= 1;
					$AMN_DESC	= "danger";
				}
               	elseif($BUDG_REMVOLM <= 0)
				{
					$VOLM_DESC	= "danger";
				}                                                            
				
				$JONDESCRIP	= "$JOBDESCPAR : $ITM_NAME";

				/*$REMREQ_QTY	= $TOT_VOLMBG - $ITM_USED;
				$REMREQ_AMN		= $TOT_AMOUNTBG - $ITM_USED_AM;*/
				$REMREQ_QTY		= $BUDG_REMVOLM;
				$REMREQ_AMN		= $BUDG_REMAMNT;
			
				if($ITM_TYPE == 'SUBS')
				{
					$disabledB	= 0;															
				}

				$STATCOL		= 'success';
				$CELL_COL		= "style='white-space:nowrap'";

				$TOT_VOLMBGV	= number_format($TOT_VOLMBG, 2);
				$TOT_AMNBGV		= number_format($TOT_AMOUNTBG, 2);
				$JOBVOLMV		= number_format($JOBVOLM, 2);
				$TOT_REQV		= number_format(0, 2);
				$UM_VOLMV		= number_format($ITM_USED, 2);
				$UM_AMOUNTV		= number_format($ITM_USED_AM, 2);
				if($disabledB == 0)
				{
					$REM_VOL = number_format($REMREQ_QTY, 2);
					$REM_AMN = number_format($REMREQ_AMN, 2);
				}
				else
				{
					$REM_VOL = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_QTY, 2)."</span>";
					$REM_AMN = "<span class='label label-danger' style='font-size:12px'>".number_format($REMREQ_AMN, 2)."</span>";
				}

				// OTHER SETT
					$ISVERIFY 	= 1;
					if($disabledB == 0)
					{
						$chkBox		= "<input type='radio' name='chk0' id='chk0".$noU."' value='".$ACC_ID."|".$JONDESCRIP."|".$ITM_CODE."|".$ITM_GROUP."|".$JOBCODEID."|".$BUDG_REMVOLM."|".$BUDG_REMAMNT."|".$ITM_UNIT."|".$ITM_LASTP."|".$THEROW."|".$ISVERIFY."' onClick='pickThis0(".$noU.");'/>";
						/*$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis(this);'/>";*/
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$ACC_ID."|".$JONDESCRIP."|".$ITM_CODE."|".$ITM_GROUP."|".$JOBCODEID."|".$BUDG_REMVOLM."|".$BUDG_REMAMNT."|".$ITM_UNIT."|".$ITM_LASTP."|".$noU."' style='display: none' />";
						/*$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";*/
					}

					$JOBDESCH		= $JOBDESCPAR;
					/*$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESCH	= $rowJOBDESC->JOBDESC;
					endforeach;*/

					$JOBDESCHV 		= wordwrap($JOBDESCH, 30, "<br>", TRUE);

					//$JobView		= "$spaceLev $JOBCODEID - $JOBDESC";
					$JobView		= "$JOBCODEID - $JOBDESC";
					$JobViewV 		= wordwrap($JobView, 45, "<br>", TRUE);

					$ADDVOL_VW 		= "";
					$ADDAMN_VW		= "";
					if($ADD_VOLM > 0 || $ADD_JOBCOST > 0)
					{
						$ADDVOL_VW 	= 	"<div>
											<p class='text-yellow'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_VOLM, 2)."</p>
									  	</div>";
						$ADDAMN_VW 	= 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='glyphicon glyphicon-triangle-top'></i>
									  		".number_format($ADD_JOBCOST, 2)."</p>
									  	</div>";
					}

				//$UM_AMOUNTV		= number_format($ITM_USED_AM, 2);
				$UM_AMOUNT 			= $ITM_USED_AM - $ITM_USEDR_AM;
				$UM_AMOUNTV			= number_format($UM_AMOUNT, 2);
				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='text-align:center;'>".$ACC_ID."</span></div>",
											"<div>
										  		<p><span ".$CELL_COL.">".$JobViewV."</span></p>
										  	</div>
										  	<div style='margin-left: 15px; font-style: italic;'>
										  		<i class='text-muted fa fa-rss'>&nbsp;&nbsp;".$JOBDESCHV."
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNITV."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span ".$CELL_COL.">".$TOT_VOLMBGV.$ADDVOL_VW."</span></div>",
											"<div style='text-align:right; white-space:nowrap'><span ".$CELL_COL.">".$TOT_AMNBGV.$ADDAMN_VW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$UM_VOLMV."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".$UM_AMOUNTV.$RES_VW."</span></div>",
											"<div style='text-align:right;'>".$REM_VOL."</div>",
											"<div style='text-align:right;'>".$REM_AMN."</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function get_AllDataIITMREQDET_221009() // GOOD
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
			$num_rows 		= $this->m_purchase_po->get_AllDataITMREQDETC($PRJCODE, $PR_NUM, $task, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataITMREQDETL($PRJCODE, $PR_NUM, $task, $search, $length, $start, $order, $dir);
								
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
				$sqlITMNM		= "SELECT ITM_CODE_H, ITM_NAME, ITM_TYPE FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
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
										FROM tbl_joblist_detail_$PRJCODEVW
										WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'
										UNION
										SELECT SUM(PR_VOLM - PR_CVOL) AS TOTPRQTY2, SUM((PR_VOLM - PR_CVOL) * PR_PRICE) AS TOTPRAMOUNT2,
											SUM(PR_CVOL * PR_PRICE) AS TOTPRAMOUNT2CNC
										FROM tbl_pr_detail A
											INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
										WHERE 
											B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID'
											AND B.PR_STAT NOT IN (5,9) AND A.PR_NUM != '$PR_NUM'";
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
                    $sqlREQ		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail_$PRJCODEVW 
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
                                FROM tbl_joblist_detail_$PRJCODEVW A
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

                $PR_CVOLV 		= "";
				if($PR_CVOL > 0)
				{
					$PR_CVOLV 	= 	"<div style='white-space:nowrap;'>
										<span class='text-red' style='white-space:nowrap;'><i class='glyphicon glyphicon-chevron-down'></i>
								  		".number_format($PR_CVOL, 2)."</span>
								  	</div>";
				}

				$disabledB 	= 0;
				$selRow 	= $noU;
				if($disabledB == 0)
				{
					$chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='".$JOBCODEDET."|".$JOBCODEID."|".$PRJCODE."|".$ITM_CODE."|".$ITM_UNIT."|".$selRow."' onClick='pickThis4(this);'/>";
				}
				else
				{
					$chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='' style='display: none' />";
				}

				$output['data'][] 	= array("$chkBox",
									  		$ITM_CODE,
										  	"$ITM_CODE : $ITM_NAME ($JOBCODEID)
										  		<div style='font-style: italic;'>
											  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC." ($JOBPARENT)
											  	</div>
												<div style='font-style: italic;'>
													<i class='text-muted fa fa-commenting-o'></i>&nbsp;&nbsp;".$PR_DESC."
											  	</div>",
											"<div style='white-space:nowrap; text-align: center'>
									  			$ITM_UNIT
									  		</div>",
										  	$ITM_BUDQTY,
										  	$TOTPRQTY,
											number_format($PR_VOLM, 2)."$PR_CVOLV",
											$PR_VOLM,
											$PR_CVOL,
											$REMPRQTY,
											"<input type='text' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REQVOLMx".$noU."' id='PO_REQVOLMx".$noU."' value='0.00' onBlur='chgReqVol(".$noU.")' disabled>
											<input type='hidden' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REQVOLM".$noU."' id='PO_REQVOLM".$noU."' value=''>");
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

	function get_AllDataIITMREQDET_221114() // GOOD
	{
		$PRJCODE	= $_GET['id'];
		$PO_NUM		= $_GET['PO_NUM'];
		$PO_CODE	= $_GET['PO_CODE'];
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
			
			$columns_valid 	= array("PO_DESC_ID",
									"ITM_CODE", 
									"JOBCODEID");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}

			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_po->get_AllDataITMREQDETC($PRJCODE, $PR_NUM, $task, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataITMREQDETL($PRJCODE, $PR_NUM, $task, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$autoPOID 		= 1;
			$GT_ITMPRICE	= 0;
			$POID 			= 0;
			$ITMVOL_R 		= 0;
			foreach ($query->result_array() as $dataI) 
			{
				$POID 		= $POID+1;
				if($autoPOID == 0)
					$PO_ID 		= $dataI['PO_ID'];
				else
					$PO_ID 		= $POID;

				$PR_NUM			= $dataI['PR_NUM'];
				$JOBCODEDET		= $dataI['JOBCODEDET'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBPARDESC		= $dataI['JOBPARDESC'];
				$PRD_ID 		= $dataI['PRD_ID'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$ITM_NAME 		= htmlspecialchars($dataI['ITM_NAME'], ENT_QUOTES);
				$ITM_NAME 		= wordwrap($ITM_NAME, 60, "<br>", TRUE);
				$ITM_GROUP 		= $dataI['ITM_GROUP'];
				$ITM_UNIT 		= strtoupper($dataI['ITM_UNIT']);
				$ITM_PRICE 		= $dataI['ITM_PRICE'];
				$PR_VOLM 		= $dataI['PR_VOLM'];
				$PR_AMOUNT		= $PR_VOLM * $ITM_PRICE;
				$PO_VOLM 		= $dataI['PO_VOLM'];
				$PO_PRICE 		= $dataI['PO_PRICE'];
				if($task == 'add')
				{
					$PO_PRICE	= 0;
					$ITM_PRICE	= 0;
				}

				$IR_VOLM 		= $dataI['IR_VOLM'];
				$IR_PRICE 		= $dataI['IR_AMOUNT'];
				$PO_DISP 		= $dataI['PO_DISP'];
				$PO_DISC 		= $dataI['PO_DISC'];
				
				// START : GET TOTAL ORDERED VOLUME
					// 1. FROM CURRENT DOCUMENT AND OTHERS ROW
							$ORDERED_VOL0	= 0;
							$s_TOTPOVOL0		= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL,
														SUM(A.PO_CVOL) AS ORDERED_CVOL
														FROM tbl_po_detail A
														INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
													WHERE B.PRJCODE = '$PRJCODE'
														AND A.JOBCODEID = '$JOBCODEID'
														AND A.PO_NUM = '$PO_NUM'
														AND A.PR_NUM = '$PR_NUM'
														AND A.ITM_CODE = '$ITM_CODE'
														-- AND A.PO_ID != $PO_ID
														AND A.PRD_ID = $PRD_ID";
							$r_TOTPOVOL0		= $this->db->query($s_TOTPOVOL0)->result();
							foreach($r_TOTPOVOL0 as $rw_TOTPOVOL0) :
								$ORDERED_VOL0	= $rw_TOTPOVOL0->ORDERED_VOL;
								$ORDERED_CVOL0 	= $rw_TOTPOVOL0->ORDERED_CVOL;
								if($ORDERED_VOL0 == '')
									$ORDERED_VOL0 	= 0;
								
								if($ORDERED_CVOL0 == '')
									$ORDERED_CVOL0 	= 0;
							endforeach;

					// 2. FROM OTHERS DOCUMENT
							$ORDERED_VOL1	= 0;
							$s_TOTPOVOL1	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL,
												SUM(A.PO_CVOL) AS ORDERED_CVOL 
												FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.PO_NUM != '$PO_NUM'
													AND A.ITM_CODE = '$ITM_CODE'
													AND A.PR_NUM = '$PR_NUM' AND B.PO_STAT NOT IN (5,9)
													AND A.PRD_ID = $PRD_ID";
							$r_TOTPOVOL1	= $this->db->query($s_TOTPOVOL1)->result();
							foreach($r_TOTPOVOL1 as $rw_TOTPOVOL1) :
								$ORDERED_VOL1	= $rw_TOTPOVOL1->ORDERED_VOL;
								$ORDERED_CVOL1	= $rw_TOTPOVOL1->ORDERED_CVOL;
								if($ORDERED_VOL1 == '')
									$ORDERED_VOL1 	= 0;

								if($ORDERED_CVOL1 == '')
									$ORDERED_CVOL1 	= 0;
							endforeach;

					// 3. TOTAL VOLUME PO
							$ORDERED_VOL 	= ($ORDERED_VOL0 - $ORDERED_CVOL0) + ($ORDERED_VOL1 - $ORDERED_CVOL1);
				// END : GET TOTAL ORDERED VOLUME
				
				// START : GET TOTAL ORDERED VALUE
					// 1. FROM CURRENT DOCUMENT AND OTHERS ROW
							$ORDERED_VAL0	= 0;
							$s_TOTPOVAL		= "SELECT SUM(A.PO_COST) AS ORDERED_VAL,
												SUM(A.PO_CTOTAL) AS ORDERED_CVAL
												FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.ITM_CODE = '$ITM_CODE'
													AND A.PO_NUM = '$PO_NUM'
													AND A.PR_NUM = '$PR_NUM'
													AND A.PRD_ID != $PRD_ID";
							$r_TOTPOVAL		= $this->db->query($s_TOTPOVAL)->result();
							foreach($r_TOTPOVAL as $rw_TOTPOVAL) :
								$ORDERED_VAL0	= $rw_TOTPOVAL->ORDERED_VAL;
								$ORDERED_CVAL0	= $rw_TOTPOVAL->ORDERED_CVAL;
								if($ORDERED_VAL0 == '')
									$ORDERED_VAL0 	= 0;
								
								if($ORDERED_CVAL0 == '')
									$ORDERED_CVAL0 	= 0;
							endforeach;

					// 2. FROM OTHERS DOCUMENT
							$ORDERED_VAL1	= 0;
							$s_TOTPOVAL		= "SELECT SUM(A.PO_COST) AS ORDERED_VAL,
												SUM(A.PO_CTOTAL) AS ORDERED_CVAL
												FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.ITM_CODE = '$ITM_CODE'
													AND A.PO_NUM != '$PO_NUM'
													AND A.PR_NUM = '$PR_NUM' AND B.PO_STAT NOT IN (5,9)
													AND A.PRD_ID = $PRD_ID";
							$r_TOTPOVAL		= $this->db->query($s_TOTPOVAL)->result();
							foreach($r_TOTPOVAL as $rw_TOTPOVAL) :
								$ORDERED_VAL1	= $rw_TOTPOVAL->ORDERED_VAL;
								$ORDERED_CVAL1	= $rw_TOTPOVAL->ORDERED_CVAL;
								if($ORDERED_VAL1 == '')
									$ORDERED_VAL1 	= 0;

								if($ORDERED_CVAL1 == '')
									$ORDERED_CVAL1 	= 0;
							endforeach;

					// 3. FROM VOUCHER CASH
							$ORDERED_VAL2	= 0;
							$s_TOTPOVAL		= "SELECT SUM(A.Base_Debet - A.Base_Kredit) AS ORDERED_VAL 
												FROM tbl_journaldetail_vcash A
													INNER JOIN tbl_journalheader_vcash B ON A.JournalH_Code = B.JournalH_Code
												WHERE B.proj_Code = '$PRJCODE' AND A.GEJ_STAT NOT IN (5,9)
													AND A.JOBCODEID = '$JOBCODEID' AND A.ITM_CODE = '$ITM_CODE'";
							$r_TOTPOVAL		= $this->db->query($s_TOTPOVAL)->result();
							foreach($r_TOTPOVAL as $rw_TOTPOVAL) :
								$ORDERED_VAL2= $rw_TOTPOVAL->ORDERED_VAL;
								if($ORDERED_VAL2 == '')
									$ORDERED_VAL2 	= 0;
							endforeach;

					// 4. FROM VOUCHER LUAR KOTA
							$ORDERED_VAL3	= 0;
							$s_TOTPOVAL		= "SELECT SUM(A.Base_Debet - A.Base_Kredit) AS ORDERED_VAL 
												FROM tbl_journaldetail_cprj A
													INNER JOIN tbl_journalheader_cprj B ON A.JournalH_Code = B.JournalH_Code
												WHERE B.proj_Code = '$PRJCODE' AND A.GEJ_STAT NOT IN (5,9)
													AND A.JOBCODEID = '$JOBCODEID' AND A.ITM_CODE = '$ITM_CODE'";
							$r_TOTPOVAL		= $this->db->query($s_TOTPOVAL)->result();
							foreach($r_TOTPOVAL as $rw_TOTPOVAL) :
								$ORDERED_VAL3= $rw_TOTPOVAL->ORDERED_VAL;
								if($ORDERED_VAL3 == '')
									$ORDERED_VAL3 	= 0;
							endforeach;

					// 5. FROM SPK
							$ORDERED_VAL4	= 0;
							$s_TOTWOVAL		= "SELECT SUM(A.WO_TOTAL) AS ORDERED_VAL,
												SUM(A.WO_CAMN) AS ORDERED_CVAL
												FROM tbl_wo_detail A
													INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.ITM_CODE = '$ITM_CODE'
													AND B.WO_STAT NOT IN (5,9)";
							$r_TOTWOVAL		= $this->db->query($s_TOTWOVAL)->result();
							foreach($r_TOTWOVAL as $rw_TOTWOVAL) :
								$ORDERED_VAL4= $rw_TOTWOVAL->ORDERED_VAL;
								if($ORDERED_VAL4 == '')
									$ORDERED_VAL4 	= 0;
							endforeach;

					// 4. TOTAL VOLUME PO
							$ORDERED_VAL 	= ($ORDERED_VAL0 - $ORDERED_CVAL0) + ($ORDERED_VAL1 - $ORDERED_CVAL1) + $ORDERED_VAL2 + $ORDERED_VAL3 + $ORDERED_VAL4;
							$TOTVAL_OTHDOC 	= ($ORDERED_VAL1 - $ORDERED_CVAL1) + $ORDERED_VAL2 + $ORDERED_VAL3 + $ORDERED_VAL4;
				// END : GET TOTAL ORDERED VALUE
				
				// GET BUDGET
					$ITM_BUDGVOL 	= 0;
					$ITM_BUDGVAL	= 0;
					$RAP_PRICE 		= $ITM_PRICE;
					$s_BUDGVAL		= "SELECT (ITM_VOLM + (ADD_VOLM + ADDM_VOLM)) AS ITM_BUDGVOL,
										(ITM_BUDG + (ADD_JOBCOST + ADDM_JOBCOST)) AS ITM_BUDG, ITM_PRICE AS RAP_PRICE
										FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
					$r_BUDGVAL		= $this->db->query($s_BUDGVAL)->result();
					foreach($r_BUDGVAL as $rw_BUDGVAL) :
						$ITM_BUDGVOL= $rw_BUDGVAL->ITM_BUDGVOL;
						$ITM_BUDGVAL= $rw_BUDGVAL->ITM_BUDG;
						$RAP_PRICE 	= $rw_BUDGVAL->RAP_PRICE;
					endforeach;

				// IS LS UNIT
					$s_ISLS 		= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
					$isLS 			= $this->db->count_all($s_ISLS);

				// ADA REGULASI BARU UNTUK OP OVERHEAD
					$ITM_AVAIL 		= 0;
					$ITM_REMVOL		= $PR_VOLM - $ORDERED_VOL;
					$ITM_REMVAL		= $ITM_BUDGVAL - $ORDERED_VAL;

					/*if($isLS == 1 && $ITM_REMVAL > 0)				// ITEM IS LS TYPE, SO JUST VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;
					elseif($ITM_REMVOL > 0 && $ITM_REMVAL > 0) 		// ITEM IS NON-LS TYPE, SO VOL AND VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;*/

					if($ITM_REMVOL > 0 && $ITM_REMVAL > 0) 			// ITEM LS AND NON-LS TYPE, VOL AND VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;

				// GET JODESC
				// $JOBPARENT		= '';
				$JOBDESC		= $JOBPARDESC;
				if($JOBPARDESC == '')
				{
					$sqlJPAR	= "SELECT JOBCODEID AS JOBPARENT, A.JOBDESC FROM tbl_joblist_detail A 
									WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
										WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE') AND PRJCODE = '$PRJCODE'";
					$resJPAR	= $this->db->query($sqlJPAR)->result();
					foreach($resJPAR as $rowJPAR) :
						// $JOBPARENT	= $rowJPAR->JOBPARENT;
						$JOBDESC	= $rowJPAR->JOBDESC;
					endforeach;
				}

				$ITM_LASTP 	= 0;
				$s_lASTP	= "SELECT ITM_LASTP FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$r_lASTP	= $this->db->query($s_lASTP)->result();
				foreach($r_lASTP as $rw_lASTP) :
					$ITM_LASTP	= $rw_lASTP->ITM_LASTP;
				endforeach;

				/*$ITM_NAME1		= $ITM_NAME;
				if($JOBDESC != '' AND $JOBDESC != $ITM_NAME)
					$ITM_NAME1	= "$ITM_NAME - $JOBDESC";*/
				
				$PO_DESC_ID		= $dataI['PO_DESC_ID'];
				if($PO_DESC_ID == '')
					$PO_DESC_ID = 0;

				$PO_DESC		= $dataI['PO_DESC'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];
				$itemConvertion	= 1;

				//echo "$ITM_REMVOL		= $PR_VOLM - $ORDERED_VOL;";
				if($task == 'add')
					$PO_VOLM	= $ITM_REMVOL;
				else
					$PO_VOLM	= $PO_VOLM;
				
				$ORDERED_VOL	= $ORDERED_VOL;

				$PO_COST 	= $PO_VOLM * $ITM_PRICE;
				
				/*if($TAXCODE1 == 'TAX01')
				{
					$GT_ITMPRICE= $GT_ITMPRICE + $PO_COST + $TAXPRICE1;
				}
				if($TAXCODE1 == 'TAX02')
				{
					$GT_ITMPRICE= $GT_ITMPRICE + $PO_COST - $TAXPRICE1;
				}*/

				// GET PO_VOLM GROUP BY ITM_CODE, PO_DESC_ID
					if($task == 'add')
					{
						$getPO_R 	= "SELECT IFNULL(SUM(A.PR_VOLM), 0) AS ITMVOLM_R
										FROM tbl_pr_detail A
										WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'
										AND A.ITM_CODE = '$ITM_CODE' AND A.PR_DESC_ID = '$PO_DESC_ID'";
					}
					else
					{
						$getPO_R 	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ITMVOLM_R
											FROM tbl_po_detail A
										WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'
										AND A.ITM_CODE = '$ITM_CODE' AND A.PO_DESC_ID = '$PO_DESC_ID'";
					}

					$resPO_R 	= $this->db->query($getPO_R);
					foreach($resPO_R->result() as $rPO_R):
						$ITMVOLM_R 	= $rPO_R->ITMVOLM_R;
					endforeach;

				// Update [iyan] - GET ITM_CATEG
				$ITM_CATEG = $this->db->get_where('tbl_item', ['ITM_CODE' => $ITM_CODE])->row('ITM_CATEG');
				// End Update [iyan]
				
				$PO_COSTnPPn	= $PO_COST + $TAXPRICE1 - $TAXPRICE2;

				$disabledB 	= 0;
				if($ITM_REMVOL == 0) $disabledB = 1;
				
				$selRow 	= $noU;

				if($disabledB == 0)
				{
					// $chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='".$JOBCODEDET."|".$JOBCODEID."|".$PRJCODE."|".$ITM_CODE."|".$ITM_UNIT."|".$selRow."' onClick='pickThis4(this);'/>";
					$chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='".$PO_ID."|".$PRD_ID."|".$ITM_CODE."|".$ITM_NAME."|".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$JOBPARDESC."|".$JOBDESC."|".$RAP_PRICE."|".$ITM_BUDGVAL."|".$ORDERED_VAL."|".$ITM_REMVOL."|".$ITM_REMVAL."|".$ITM_CATEG."|".$PR_VOLM."|".$PR_AMOUNT."|".$TOTVAL_OTHDOC."|".$PO_VOLM."|".$ITMVOLM_R."|".$ITM_UNIT."|".$PO_DESC_ID."|".$PO_DESC."|".$selRow."' onChange='pickThis4(this);'/>";
				}
				else
				{
					$chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='' style='display: none' />";
				}

				if($ITM_REMVOL > 0)
				{
					$output['data'][] 	= array("$chkBox",
												  $ITM_CODE,
												  "$ITM_CODE : $ITM_NAME ($JOBCODEID)
													<div style='font-style: italic;'>
														<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC." ($JOBPARENT)
													  	</div>
													<div style='font-style: italic;'>
														<i class='text-muted fa fa-commenting-o'></i>&nbsp;&nbsp;".$PO_DESC."
													</div>",
												"<div style='white-space:nowrap; text-align: center'>
													$ITM_UNIT
												</div>",
												$ITM_BUDGVOL,
												$PR_VOLM,
												$ORDERED_VOL,
												$ITM_REMVOL,
												"<input type='text' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REQVOLMX".$noU."' id='PO_REQVOLMX".$noU."' value='".number_format($ITM_REMVOL, 2)."' onBlur='chgReqVol(this.value, ".$noU.")' disabled>
												<input type='hidden' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REQVOLM".$noU."' id='PO_REQVOLM".$noU."' value='".$ITM_REMVOL."'>
												<input type='hidden' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REMVOLM".$noU."' id='PO_REMVOLM".$noU."' value='".$ITM_REMVOL."'>");
					$noU		= $noU + 1;
				}

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

	function get_AllDataIITMREQDET() // GOOD
	{
		$PRJCODE	= $_GET['id'];
		$PO_NUM		= $_GET['PO_NUM'];
		$PO_CODE	= $_GET['PO_CODE'];
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
			
			$columns_valid 	= array("PO_DESC_ID",
									"ITM_CODE", 
									"JOBCODEID");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}

			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_purchase_po->get_AllDataITMREQDETC($PRJCODE, $PR_NUM, $task, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_purchase_po->get_AllDataITMREQDETL($PRJCODE, $PR_NUM, $task, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$autoPOID 		= 1;
			$GT_ITMPRICE	= 0;
			$POID 			= 0;
			$ITMVOL_R 		= 0;
			foreach ($query->result_array() as $dataI) 
			{
				$POID 		= $POID+1;
				if($autoPOID == 0)
					$PO_ID 		= $dataI['PO_ID'];
				else
					$PO_ID 		= $POID;

				$PR_NUM			= $dataI['PR_NUM'];
				$JOBCODEDET		= $dataI['JOBCODEDET'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBPARDESC		= $dataI['JOBPARDESC'];
				$PRD_ID 		= $dataI['PRD_ID'];
				$ITM_CODE 		= $dataI['ITM_CODE'];
				$ITM_NAME 		= htmlspecialchars($dataI['ITM_NAME'], ENT_QUOTES);
				$ITM_NAME 		= wordwrap($ITM_NAME, 60, "<br>", TRUE);
				$ITM_GROUP 		= $dataI['ITM_GROUP'];
				$ITM_UNIT 		= strtoupper($dataI['ITM_UNIT']);
				$ITM_CATEG 		= $dataI['ITM_CATEG'];
				$ITM_PRICE 		= $dataI['ITM_PRICE'];
				$PR_VOLM 		= $dataI['PR_VOLM'];
				$PR_AMOUNT		= $PR_VOLM * $ITM_PRICE;
				$PO_VOLM 		= $dataI['PO_VOLM'];
				$PO_PRICE 		= $dataI['PO_PRICE'];
				if($task == 'add')
				{
					$PO_PRICE	= 0;
					$ITM_PRICE	= 0;
				}

				$IR_VOLM 		= $dataI['IR_VOLM'];
				$IR_PRICE 		= $dataI['IR_AMOUNT'];
				$PO_DISP 		= $dataI['PO_DISP'];
				$PO_DISC 		= $dataI['PO_DISC'];

				// START : GET BUDGET
					$ITM_PRICE 		= $ITM_PRICE;
					$s_BUDGVAL		= "SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
										PR_VOL, PR_VOL_R, PR_CVOL, PO_VOL, PO_VOL_R, PO_CVOL, PO_VAL, PO_VAL_R, PO_CVAL,
										UM_VOL, UM_VAL, UM_VOL_R, UM_VAL_R,
										WO_VOL, WO_VAL, WO_VOL_R, WO_VAL_R, WO_CVOL, WO_CVAL, OPN_VOL, OPN_VAL, OPN_VOL_R, OPN_VAL_R,
										VCASH_VOL, VCASH_VAL, VCASH_VOL_R, VCASH_VAL_R, VLK_VOL, VLK_VAL, VLK_VOL_R, VLK_VAL_R,
										PPD_VOL, PPD_VAL, PPD_VOL_R, PPD_VAL_R,
										AMD_VOL, AMD_VOL_R, AMD_VAL, AMD_VAL_R, AMDM_VOL, AMDM_VAL
										FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$r_BUDGVAL		= $this->db->query($s_BUDGVAL)->result();
					foreach($r_BUDGVAL as $rowJOBDR) :
						$ITM_VOLM 		= $rowJOBDR->ITM_VOLM;		// BUDG VOL
						$ITM_PRICE 		= $rowJOBDR->ITM_PRICE;		// BUDG PRICE
						$ITM_LASTP 		= $rowJOBDR->ITM_LASTP;		// LAST PRICE
						$ITM_BUDG 		= $rowJOBDR->ITM_BUDG;		// BUDG VAL
						$PR_VOL 		= $rowJOBDR->PR_VOL;		// SPP VOL
						$PR_VOL_R 		= $rowJOBDR->PR_VOL_R;		// SPP VOL_R
						$PR_CVOL 		= $rowJOBDR->PR_CVOL;		// SPP CANCEL VOL

						$PO_VOL 		= $rowJOBDR->PO_VOL; 		// PO VOL
						$PO_VOL_R 		= $rowJOBDR->PO_VOL_R; 		// PO VOL_R
						$PO_VAL 		= $rowJOBDR->PO_VAL; 		// PO VAL
						$PO_VAL_R 		= $rowJOBDR->PO_VAL_R; 		// PO VAL_R
						$PO_CVOL 		= $rowJOBDR->PO_CVOL; 		// PO CANCEL VOL
						$PO_CVAL 		= $rowJOBDR->PO_CVAL; 		// PO CANCEL VAL

						$UM_VOL 		= $rowJOBDR->UM_VOL; 		// UM VOL
						$UM_VOL_R 		= $rowJOBDR->UM_VOL_R; 		// UM VOL_R
						$UM_VAL 		= $rowJOBDR->UM_VAL; 		// UM VAL
						$UM_VAL_R 		= $rowJOBDR->UM_VAL_R; 		// UM VAL_R

						$WO_VOL 		= $rowJOBDR->WO_VOL; 		// SPK VOL
						$WO_VOL_R 		= $rowJOBDR->WO_VOL_R; 		// SPK VOL_R
						$WO_VAL 		= $rowJOBDR->WO_VAL; 		// SPK VAL
						$WO_VAL_R 		= $rowJOBDR->WO_VAL_R; 		// SPK VAL_R
						$WO_CVOL 		= $rowJOBDR->WO_CVOL; 		// SPK CANCEL VOL
						$WO_CVAL 		= $rowJOBDR->WO_CVAL; 		// SPK CANCEL VAL_R

						$OPN_VOL 		= $rowJOBDR->OPN_VOL; 		// OPN VOL
						$OPN_VOL_R 		= $rowJOBDR->OPN_VOL_R; 	// OPN VOL_R
						$OPN_VAL 		= $rowJOBDR->OPN_VAL; 		// OPN VAL
						$OPN_VAL_R 		= $rowJOBDR->OPN_VAL_R; 	// OPN VAL_R

						$VCASH_VOL 		= $rowJOBDR->VCASH_VOL; 	// VCASH VOL
						$VCASH_VOL_R 	= $rowJOBDR->VCASH_VOL_R; 	// VCASH VOL_R
						$VCASH_VAL 		= $rowJOBDR->VCASH_VAL; 	// VCASH VAL
						$VCASH_VAL_R 	= $rowJOBDR->VCASH_VAL_R; 	// VCASH VAL_R

						$VLK_VOL 		= $rowJOBDR->VLK_VOL; 		// VLK VOL
						$VLK_VOL_R 		= $rowJOBDR->VLK_VOL_R; 	// VLK VOL_R
						$VLK_VAL 		= $rowJOBDR->VLK_VAL; 		// VLK VAL
						$VLK_VAL_R 		= $rowJOBDR->VLK_VAL_R; 	// VLK VAL_R

						$PPD_VOL 		= $rowJOBDR->PPD_VOL; 		// PPD VOL
						$PPD_VOL_R 		= $rowJOBDR->PPD_VOL_R; 	// PPD VAL_R
						$PPD_VAL 		= $rowJOBDR->PPD_VAL; 		// PPD VOL
						$PPD_VAL_R 		= $rowJOBDR->PPD_VAL_R; 	// PPD VAL_R

						$AMD_VOL 		= $rowJOBDR->AMD_VOL; 		// AMD VOL
						$AMD_VOL_R 		= $rowJOBDR->AMD_VOL_R; 	// AMD VOL_R
						$AMD_VAL 		= $rowJOBDR->AMD_VAL; 		// AMD VAL
						$AMD_VAL_R 		= $rowJOBDR->AMD_VAL_R; 	// AMD VAL_R
						$AMDM_VOL 		= $rowJOBDR->AMDM_VOL; 		// AMD MIN VOL
						$AMDM_VAL 		= $rowJOBDR->AMDM_VAL; 		// AMD MIN VAL

						$TREQ_VOL 		= ($PR_VOL - $PR_CVOL) + ($WO_VOL - $PO_CVOL) + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
						$TREQ_VAL 		= ($PO_VAL - $PO_CVAL) + ($WO_VAL - $WO_CVAL) + $VCASH_VAL + $VLK_VAL + $PPD_VAL;

						$TREQ_VOL_R		= $PR_VOL_R + $WO_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;		// VOL REQUEST NEW, CONFIRMED &  WAITING
						$TREQ_VAL_R		= $PO_VAL_R + $WO_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;		// VAL REQUEST NEW, CONFIRMED &  WAITING

						$TUSED_VOL 		= $UM_VOL + $OPN_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
						$TUSED_VOL_R	= $UM_VOL_R + $OPN_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;		// VAL PENGGUNAAN NEW, CONFIRMED &  WAITING
						$TUSED_VAL 		= $UM_VAL + $OPN_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
						$TUSED_VAL_R	= $UM_VAL_R + $OPN_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;		// VAL PENGGUNAAN NEW, CONFIRMED &  WAITING
					endforeach;

					$TBUDG_VOL	= $ITM_VOLM + $AMD_VOL - $AMDM_VOL;
					if($TBUDG_VOL == '')
						$TBUDG_VOL	= 0;

					$TBUDG_VAL	= $ITM_BUDG + $AMD_VAL - $AMDM_VAL;
					if($TBUDG_VAL == '')
						$TBUDG_VAL	= 0;

					$ITM_REMVOL		= $TBUDG_VOL - $TREQ_VOL - $TREQ_VOL_R - $TUSED_VOL_R;
					$ITM_REMVAL		= $TBUDG_VAL - $TREQ_VAL - $TREQ_VAL_R - $TUSED_VAL_R;
				// END : GET BUDGET
				
				// START : GET TOTAL ORDERED VOLUME
					// 1. FROM CURRENT DOCUMENT AND OTHERS ROW
						$ORDERED_VOL0		= 0;
						$ORDERED_VAL0		= 0;
						$ORDERED_CVOL0		= 0;
						$ORDERED_CVAL0		= 0;
						$s_TOTPOVOL0		= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL, IFNULL(SUM(A.PO_COST), 0) AS ORDERED_VAL,
													IFNULL(SUM(A.PO_CVOL), 0) AS ORDERED_CVOL, IFNULL(SUM(A.PO_CTOTAL), 0) AS ORDERED_CVAL
													FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE B.PRJCODE = '$PRJCODE'
													AND A.JOBCODEID = '$JOBCODEID'
													AND A.PO_NUM = '$PO_NUM'
													AND A.PR_NUM = '$PR_NUM'
													AND A.ITM_CODE = '$ITM_CODE'
													AND A.PRD_ID = $PRD_ID";
						$r_TOTPOVOL0		= $this->db->query($s_TOTPOVOL0)->result();
						foreach($r_TOTPOVOL0 as $rw_TOTPOVOL0) :
							$ORDERED_VOL0	= $rw_TOTPOVOL0->ORDERED_VOL;
							$ORDERED_VAL0	= $rw_TOTPOVOL0->ORDERED_VAL;
							$ORDERED_CVOL0 	= $rw_TOTPOVOL0->ORDERED_CVOL;
							$ORDERED_CVAL0 	= $rw_TOTPOVOL0->ORDERED_CVAL;
						endforeach;

					// 2. FROM OTHERS DOCUMENT
						$ORDERED_VOL1		= 0;
						$ORDERED_VAL1		= 0;
						$ORDERED_CVOL1		= 0;
						$ORDERED_CVAL1		= 0;
						$s_TOTPOVOL1	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL, IFNULL(SUM(A.PO_COST), 0) AS ORDERED_VAL,
												IFNULL(SUM(A.PO_CVOL), 0) AS ORDERED_CVOL, IFNULL(SUM(A.PO_CTOTAL), 0) AS ORDERED_CVAL
											FROM tbl_po_detail A
												INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
											WHERE B.PRJCODE = '$PRJCODE'
												AND A.JOBCODEID = '$JOBCODEID'
												AND A.PO_NUM != '$PO_NUM'
												AND A.ITM_CODE = '$ITM_CODE'
												AND A.PR_NUM = '$PR_NUM' AND B.PO_STAT NOT IN (5,9)
												AND A.PRD_ID = $PRD_ID";
						$r_TOTPOVOL1	= $this->db->query($s_TOTPOVOL1)->result();
						foreach($r_TOTPOVOL1 as $rw_TOTPOVOL1) :
							$ORDERED_VOL1	= $rw_TOTPOVOL1->ORDERED_VOL;
							$ORDERED_VAL1	= $rw_TOTPOVOL1->ORDERED_VAL;
							$ORDERED_CVOL1 	= $rw_TOTPOVOL1->ORDERED_CVOL;
							$ORDERED_CVAL1 	= $rw_TOTPOVOL1->ORDERED_CVAL;
						endforeach;

					// 3. TOTAL VOLUME PO
						$ORDERED_VOL 	= ($ORDERED_VOL0 - $ORDERED_CVOL0) + ($ORDERED_VOL1 - $ORDERED_CVOL1);
						$ORDERED_VOLVW 	= "($ORDERED_VOL0 - $ORDERED_CVOL0) + ($ORDERED_VOL1 - $ORDERED_CVOL1)";
				// END : GET TOTAL ORDERED VOLUME
				
				// START : GET TOTAL ORDERED VALUE

					// 4. TOTAL VOLUME PO -- TIDAK DIPERLUKAN LAGI KARENA SUDAH DIAMBIL DARI tbl_joblist_detail
						$ORDERED_VAL 	= ($ORDERED_VAL0 - $ORDERED_CVAL0) + ($ORDERED_VAL1 - $ORDERED_CVAL1);
						$TOTVAL_OTHDOC 	= ($ORDERED_VAL1 - $ORDERED_CVAL1);
				// END : GET TOTAL ORDERED VALUE

				// START : REMAIN BUDGET SPP PER PR ID
						$REM_SPP_VOL 	= $PR_VOLM - $ORDERED_VOL;
				// END : REMAIN BUDGET SPP PER PR ID

				// IS LS UNIT
					$s_ISLS 		= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
					$isLS 			= $this->db->count_all($s_ISLS);

				// ADA REGULASI BARU UNTUK OP OVERHEAD
					$ITM_AVAIL 		= 0;
					$ITM_REMVOL		= $PR_VOLM - $ORDERED_VOL;
					//$ITM_REMVAL	= $TBUDG_VOL - $ORDERED_VAL;

					/*if($isLS == 1 && $ITM_REMVAL > 0)				// ITEM IS LS TYPE, SO JUST VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;
					elseif($ITM_REMVOL > 0 && $ITM_REMVAL > 0) 		// ITEM IS NON-LS TYPE, SO VOL AND VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;*/

					if($ITM_REMVOL > 0 && $ITM_REMVAL > 0) 			// ITEM LS AND NON-LS TYPE, VOL AND VAL MUST BE GREATHER
						$ITM_AVAIL	= 1;

				// GET JODESC
				// $JOBPARENT		= '';
				$JOBDESC		= $JOBPARDESC;

				$PR_DESC_ID		= $dataI['PR_DESC_ID'];
				if($PR_DESC_ID == '')
					$PR_DESC_ID = 0;
				
				$PO_DESC_ID		= $dataI['PO_DESC_ID'];
				if($PO_DESC_ID == '')
					$PO_DESC_ID = 0;

				$PO_DESC		= $dataI['PO_DESC'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];
				$itemConvertion	= 1;

				if($task == 'add')
					$PO_VOLM	= $ITM_REMVOL;
				else
					$PO_VOLM	= $PO_VOLM;

				$PO_COST 	= $PO_VOLM * $ITM_PRICE;

				// GET PO_VOLM GROUP BY ITM_CODE, PO_DESC_ID
					if($task == 'add')
					{
						$getPO_R 	= "SELECT IFNULL(SUM(A.PR_VOLM), 0) AS ITMVOLM_R
										FROM tbl_pr_detail A
										WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'
										AND A.ITM_CODE = '$ITM_CODE' AND A.PR_DESC_ID = '$PO_DESC_ID'";
					}
					else
					{
						$getPO_R 	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ITMVOLM_R
											FROM tbl_po_detail A
										WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'
										AND A.ITM_CODE = '$ITM_CODE' AND A.PO_DESC_ID = '$PO_DESC_ID'";
					}

					$resPO_R 	= $this->db->query($getPO_R);
					foreach($resPO_R->result() as $rPO_R):
						$ITMVOLM_R 	= $rPO_R->ITMVOLM_R;
					endforeach;

				// Update [iyan] - GET ITM_CATEG
				//$ITM_CATEG = $this->db->get_where('tbl_item', ['ITM_CODE' => $ITM_CODE])->row('ITM_CATEG');
				// End Update [iyan]
				
				//$PO_COSTnPPn	= $PO_COST + $TAXPRICE1 - $TAXPRICE2;

				$disabledB 	= 0;
				if($ITM_REMVOL == 0) $disabledB = 1;
				
				$selRow 	= $noU;

				if($disabledB == 0)
				{
					$chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='".$PO_ID."|".$PRD_ID."|".$ITM_CODE."|".$ITM_NAME."|".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$JOBPARDESC."|".$JOBDESC."|".$ITM_PRICE."|".$TBUDG_VAL."|".$ORDERED_VAL."|".$ITM_REMVOL."|".$ITM_REMVAL."|".$ITM_CATEG."|".$PR_VOLM."|".$PR_AMOUNT."|".$TOTVAL_OTHDOC."|".$PO_VOLM."|".$ITMVOLM_R."|".$ITM_UNIT."|".$PR_DESC_ID."|".$PO_DESC_ID."|".$PO_DESC."|".$selRow."' onChange='pickThis4(this);'/>";
				}
				else
				{
					$chkBox	= "<input type='checkbox' name='chk4' id='chkRow_".$noU."' value='' style='display: none' />";
				}

				$vw 		= "$PO_ID :  $PRD_ID :  $ITM_CODE :  $ITM_NAME :  $JOBCODEDET :  $JOBCODEID :  $JOBPARENT :  $JOBPARDESC :  $JOBDESC :  $ITM_PRICE :  $TBUDG_VAL :  $ORDERED_VAL :  $ITM_REMVOL :  $ITM_REMVAL :  $ITM_CATEG :  $PR_VOLM :  $PR_AMOUNT :  $TOTVAL_OTHDOC :  $PO_VOLM :  $ITMVOLM_R :  $ITM_UNIT :  $PR_DESC_ID :  $PO_DESC_ID :  $PO_DESC : $selRow";
				if($ITM_AVAIL == 1)
				{
					$output['data'][] 	= array("$chkBox",
												  $ITM_CODE,
												  "$ITM_CODE : $ITM_NAME ($JOBCODEID)
													<div style='font-style: italic;'>
														<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;".$JOBPARDESC." ($JOBPARENT)
													  	</div>
													<div style='font-style: italic;'>
														<i class='text-muted fa fa-commenting-o'></i>&nbsp;&nbsp;".$PO_DESC."
													</div>",
												"<div style='white-space:nowrap; text-align: center'>
													$ITM_UNIT
												</div>",
												$TBUDG_VOL,
												$PR_VOLM,
												$ORDERED_VOL,
												$ITM_REMVOL,
												"<input type='text' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REQVOLMX".$noU."' id='PO_REQVOLMX".$noU."' value='".number_format($ITM_REMVOL, 2)."' onBlur='chgReqVol(this.value, ".$noU.")' disabled>
												<input type='hidden' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REQVOLM".$noU."' id='PO_REQVOLM".$noU."' value='".$ITM_REMVOL."'>
												<input type='hidden' class='form-control' style='min-width:80px; max-width:110px; text-align:right' name='PO_REMVOLM".$noU."' id='PO_REMVOLM".$noU."' value='".$ITM_REMVOL."'>");
					$noU		= $noU + 1;
				}

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

	function get_ITMSRC()
	{
		$PRJCODE 	= $this->input->post("PRJCODE");
		$PR_NUM 	= $this->input->post("PR_NUM");

		$data 		= $this->m_purchase_po->getITM_SRC($PRJCODE, $PR_NUM)->result();
		echo json_encode($data);
	}

	function get_PRDESCSRC()
	{
		$PRJCODE 	= $this->input->post("PRJCODE");
		$PR_NUM 	= $this->input->post("PR_NUM");
		$ITM_CODE 	= $this->input->post("ITM_CODE");

		$data 		= $this->m_purchase_po->getPRDESC_SRC($PRJCODE, $PR_NUM, $ITM_CODE)->result();
		echo json_encode($data);
	}

	function getColumnData()
	{
		// $ITM_CODE = $this->input->post("ITM_CODE");
		// $ITM_CODE = $_GET['ITMCODE'];
		// echo "Kode Item : $ITM_CODE";

		$PRJCODE = $this->input->post("PRJCODE");
		$JOBCODEID = $this->input->post("JOBCODEID");
		$JOBPARENT = $this->db->get_where("tbl_joblist_detail", ["PRJCODE" => $PRJCODE, "JOBCODEID" => $JOBCODEID])->row("JOBPARENT");
		echo $JOBPARENT;
	}

	function getTAXCODE()
	{
		$sPPN		= "SELECT TAXLA_NUM, TAXLA_DESC, 'PPN' AS TAXTYPE FROM tbl_tax_ppn
						UNION ALL
						SELECT TAXLA_NUM, TAXLA_DESC, 'PPH' AS TAXTYPE FROM tbl_tax_la";
		$data		= $this->db->query($sPPN)->result();
		echo json_encode($data);
	}
}