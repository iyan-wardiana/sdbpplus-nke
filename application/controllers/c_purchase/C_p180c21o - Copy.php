<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 26 Mei 2017
 * Updated		= Dian Hermanto - 11 November 2017
 * File Name	= C_p180c21o.php
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
				
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
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
									<a href='javascript:void(null);' class='btn bg-purple btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
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
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
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
					$secAction	= 	"<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
								   	<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
										<i class='glyphicon glyphicon-list'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
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
							$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$ITM_NAME."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."|".$ITM_CATEG."' onClick='pickThis2(this);'/>";
						}
						else
						{
							$chkBox		= "<input type='checkbox' name='chk2' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBPARENT."|".$PRJCODE."|".$ITM_CODE."|".$ITM_NAME."|".$JOBUNIT."|".$ITM_PRICE."|".$JOBDESCH."|".$ITM_CATEG."' style='display: none' />";
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
				$PO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$DEPCODE		= $this->input->post('DEPCODE');
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
			$PO_PAYNOTES 	= $this->input->post('PO_PAYNOTES');
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
				$this->db->insert('tbl_po_detail',$d);

				// CEK TAX
					$TAXPRICE1	= $d['TAXPRICE1'];
					$PO_TAX 	= $PO_TAX + $TAXPRICE1;
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
			$data['default']['PO_TYPE'] 	= $getPO->PO_TYPE;
			$data['default']['PO_CAT'] 		= $getPO->PO_CAT;
			$data['default']['PO_DATE'] 	= $getPO->PO_DATE;
			$data['default']['PO_DUED'] 	= $getPO->PO_DUED;
			$data['default']['PRJCODE'] 	= $getPO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getPO->DEPCODE;
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
			$data['default']['PO_PAYNOTES'] = $getPO->PO_PAYNOTES;
			$data['default']['PO_MEMO'] 	= $getPO->PO_MEMO;
			$data['default']['PRJNAME'] 	= $getPO->PRJNAME;
			$data['default']['PO_STAT'] 	= $getPO->PO_STAT;
			$data['default']['PO_RECEIVLOC']= $getPO->PO_RECEIVLOC;
			$data['default']['PO_RECEIVCP'] = $getPO->PO_RECEIVCP;
			$data['default']['PO_SENTROLES']= $getPO->PO_SENTROLES;
			$data['default']['PO_REFRENS'] 	= $getPO->PO_REFRENS;
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
			$JOBCODE 		= $this->input->post('JOBCODE');
			$PO_STAT		= $this->input->post('PO_STAT'); // New, Confirm, Approve
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_RECEIVLOC	= $this->input->post('PO_RECEIVLOC');
			$PO_RECEIVCP	= $this->input->post('PO_RECEIVCP');
			$PO_SENTROLES	= $this->input->post('PO_SENTROLES');
			$PO_REFRENS		= $this->input->post('PO_REFRENS');
			
			$updPO 			= array('PO_NUM' 		=> $PO_NUM,
									'PO_CODE' 		=> $PO_CODE,
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
									'PO_STAT'		=> $PO_STAT,
									'PO_RECEIVLOC'	=> $PO_RECEIVLOC,
									'PO_RECEIVCP'	=> $PO_RECEIVCP,
									'PO_SENTROLES'	=> $PO_SENTROLES,
									'PO_REFRENS'	=> $PO_REFRENS,
									'PO_INVSTAT'	=> 0);
			$this->m_purchase_po->updatePO($PO_NUM, $updPO);
			
			// UPDATE JOBDETAIL ITEM
			$PO_TAX 	= 0;
			if($PO_STAT == 6)
			{
				foreach($_POST['data'] as $d)
				{
					$PO_NUM		= $d['PO_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					$this->m_purchase_po->updateVolBud($PO_NUM, $PRJCODE, $ITM_CODE);
				}
			}
			elseif($PO_STAT == 5)	// REJECTED
			{
				// Cek IR with PO Source Code
				//$this->m_purchase_po->updREJECT($PO_NUM, $PRJCODE);
			}
			elseif($PO_STAT == 9)	// VOID
			{
				// SAMA DENGAN REJECTED
				$this->m_purchase_po->updREJECT($PO_NUM, $PRJCODE);

				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $PO_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "PO",
											'DOC_QTY' 		=> "DOC_POQ",
											'DOC_VAL' 		=> "DOC_POV",
											'DOC_STAT' 		=> 'VOID');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT
			}
			else
			{
				$this->m_purchase_po->deletePODetail($PO_NUM);

				$PO_TOTCOST	= 0;
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
					$this->db->insert('tbl_po_detail',$d);

					// CEK TAX
						$TAXPRICE1	= $d['TAXPRICE1'];
						$PO_TAX 	= $PO_TAX + $TAXPRICE1;
				}
				
				$updPOH			= array('PO_TOTCOST' => $PO_TOTCOST,
										'PO_TAXAMN'	 => $PO_TAX);
				$this->m_purchase_po->updatePOH($PO_NUM, $updPOH);
				
				// UPDATE DETAIL
					$this->m_purchase_po->updateDet($PO_NUM, $PRJCODE, $PO_DATE);
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
		
		// START : FOR SERVER-SIDE
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
			$query 			= $this->m_purchase_po->get_AllDataL_1n2($PRJCODE, $search, $length,$start);
								
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
				
				$CollID		= "$PRJCODE~$PO_NUM";
				$secUpd		= site_url('c_purchase/c_p180c21o/up180djinb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secView	= site_url('c_purchase/c_p180c21o/up180djinbVw/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_purchase/c_p180c21o/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
				$secPIRList	= site_url('c_purchase/c_p180c21o/pRn_1rl/?id='.$this->url_encryption_helper->encode_url($PO_NUM));
                                        
				$secAction	= "<input type='hidden' name='urlIRList".$noU."' id='urlIRList".$noU."' value='".$secPIRList."'>
							   <input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-warning btn-xs' title='View Receipt' onClick='pRn_1rl(".$noU.")'>
									<i class='glyphicon glyphicon-list'></i>
								</a>
								<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
									<i class='glyphicon glyphicon-print'></i>
								</a>
								</label>";								
				
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
			$data['default']['PO_CURR'] 	= $getPO->PO_CURR;
			$data['default']['PO_CURRATE'] 	= $getPO->PO_CURRATE;
			$data['default']['PO_PAYTYPE'] 	= $getPO->PO_PAYTYPE;
			$data['default']['PO_TENOR'] 	= $getPO->PO_TENOR;
			$data['default']['PO_PLANIR'] 	= $getPO->PO_PLANIR;
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
			$PO_PAYTYPE 	= $this->input->post('PO_PAYTYPE'); //Cash or Credit
			$PO_TENOR 		= $this->input->post('PO_TENOR'); // Jangka Waktu Bayar
			$PO_DATE 		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('PO_DATE'))));
			$PO_DUED1		= strtotime ("+$PO_TENOR day", strtotime ($PO_DATE));
			$PO_DUED		= date('Y-m-d', $PO_DUED1);
			$PR_NUM 		= $this->input->post('PR_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			//$PO_TOTCOST		= $this->input->post('PO_TOTCOST');
			$PO_STAT 		= $this->input->post('PO_STAT');
			$ISDIRECT 		= $this->input->post('ISDIRECT');
			$PO_NOTES1 		= addslashes($this->input->post('PO_NOTES1'));
			$PO_MEMO 		= addslashes($this->input->post('PO_MEMO'));
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
				$updPO 	= array('PO_APPROVER'	=> $DefEmp_ID,
								'PO_APPROVED'	=> $PO_APPROVED,
								'PO_NOTES1'		=> addslashes($this->input->post('PO_NOTES1')),
								'PO_MEMO'		=> addslashes($this->input->post('PO_MEMO')),
								'PO_STAT'		=> $this->input->post('PO_STAT'));
				$this->m_purchase_po->updatePOInb($PO_NUM, $updPO);
				
				// UPDATE JOBDETAIL ITEM
				if($PO_STAT == 5)	// REJECTED
				{
					// Cek IR with PO Source Code
					//_____$this->m_purchase_po->updREJECT($PO_NUM, $PRJCODE);
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
					// SAMA DENGAN REJECTED
					$this->m_purchase_po->updREJECT($PO_NUM, $PRJCODE);
				}
				elseif($PO_STAT == 3)
				{
					$PO_TOTCOST	= 0;		
					foreach($_POST['data'] as $d)
					{
						$ITMAMOUNT	= $d['PO_COST'];
						$PO_TOTCOST	= $PO_TOTCOST + $ITMAMOUNT;
						//$this->db->insert('tbl_po_detail',$d);
					}
					/*$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);*/
					
					$this->m_purchase_po->updatePODet($PO_NUM, $PRJCODE, $PR_NUM, $ISDIRECT); // UPDATE JOB DETAIL DAN PR
					
					// START : TRACK FINANCIAL TRACK
						$this->load->model('m_updash/m_updash', '', TRUE);
						$paramFT = array('DOC_NUM' 		=> $PO_NUM,
										'DOC_DATE' 		=> $PO_DATE,
										'DOC_EDATE' 	=> $PO_DUED,
										'PRJCODE' 		=> $PRJCODE,
										'FIELD_NAME1' 	=> 'FT_COP',
										'FIELD_NAME2' 	=> 'FM_COP',
										'TOT_AMOUNT'	=> $PO_TOTCOST);
						//$this->m_updash->finTrack($paramFT); hanya saat TTK
					// END : TRACK FINANCIAL TRACK
				}
				
				// CHECK TOTAL PR AND PO
				//$this->m_purchase_po->updatePRPO($PO_NUM, $PR_NUM);
				
				
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
			}
			else
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
			}
			
			if($PO_STAT == 4)
			{
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($PO_NUM);
				// END : DELETE HISTORY
			}

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "PO",
										'TR_DATE' 		=> $PO_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
							
			$url			= site_url('c_purchase/c_p180c21o/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$PO_NUM		= $_GET['id'];
		$PO_NUM		= $this->url_encryption_helper->decode_url($PO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;
			$PO_CODE 		= "";
			$PO_DATE 		= "";
			$PO_NOTES 		= "";	
			$sqlPR 			= "SELECT PO_CODE, PO_DATE, PO_NOTES FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
			$resultaPR 		= $this->db->query($sqlPR)->result();
			foreach($resultaPR as $rowPR) :
				$PO_CODE 	= $rowPR->PO_CODE;
				$PO_DATE 	= $rowPR->PO_DATE;
				$PO_NOTES 	= $rowPR->PO_NOTES;		
			endforeach;
			
			$data['PO_CODE'] 	= $PO_CODE;
			$data['PO_NUM'] 	= $PO_NUM;
			$data['PO_DATE'] 	= $PO_DATE;
			$data['PO_NOTES'] 	= $PO_NOTES;
			
			$data['countIR']	= $this->m_purchase_po->count_all_IR($PO_NUM);
			$data['vwIR'] 		= $this->m_purchase_po->get_all_IR($PO_NUM)->result();	
							
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
			
			// BEDAKAN ANTARA YANG ADA PPN DAN TIDAK
			
			if($PO_TAXAMN > 0)
				$this->load->view('v_purchase/v_purchase_po/print_po_ppn', $data);
			else
				$this->load->view('v_purchase/v_purchase_po/print_po', $data);
		}
		else
		{
			redirect('__l1y');
		}
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
		setlocale(LC_ALL, 'id-ID', 'id_ID');

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
											"$PR_CODE",
											"$PR_DATE",
											"$newtext",
											"$PR_RECEIPTD");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}