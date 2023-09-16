<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 1 Februari 2018
	* File Name		= C_o180d0bpnm.php
	* Location		= -
*/

class C_o180d0bpnm extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
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
	
 	public function index() // OK
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url		= site_url('c_project/c_o180d0bpnm/prj180d0blst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180d0blst() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN241';
				$MenuCode			= 'MN241';
				$data["MenuCode"] 	= 'MN241';
				$data["MenuApp"] 	= 'MN242';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN241';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_o180d0bpnm/gal180d0bopn/?id=";
			
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
	
	function gal180d0bopn() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN241';
			$MenuCode			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data["MenuApp"] 	= 'MN242';
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
				$data["url_search"] = site_url('c_project/c_o180d0bpnm/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				//$num_rows 			= $this->m_opname->count_all_OPN($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;	 
				//$data['vData']		= $this->m_opname->get_all_OPN($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_o180d0bpnm/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_o180d0bpnm/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN241';
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
			
			$this->load->view('v_project/v_opname/opname_list', $data);
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
			$url			= site_url('c_project/c_o180d0bpnm/gal180d0bopn/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     	= $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
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
			
			$columns_valid 	= array("OPNH_CODE",
									"OPNH_DATE",
									"WO_CODE", 
									"OPNH_NOTE", 
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
			$num_rows 		= $this->m_opname->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_opname->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$OPNH_NUM		= $dataI['OPNH_NUM'];
				$OPNH_CODE		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				//$OPNH_DATEV		= date('d M Y', strtotime($OPNH_DATE));
				$OPNH_DATEV		= strftime('%d %b %Y', strtotime($OPNH_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$OPNH_NOTE		= $dataI['OPNH_NOTE'];
				$OPNH_STAT		= $dataI['OPNH_STAT'];
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$OPNH_CREATER	= $dataI['OPNH_CREATER'];
				$OPNH_ISCLOSE	= $dataI['OPNH_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];

				// GET isLock in Journal
					$isLock = 0;
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
					$getJ 	= "SELECT IFNULL(isLock,0) AS isLock FROM tbl_journalheader_$PRJCODEVW 
								WHERE GEJ_STAT = 3 AND JournalH_Code = '$OPNH_NUM'";
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
					
				$splitJOB 		= explode("~", $JOBCODEID);
				$JOBCODEID		= $splitJOB[0];
				$JOBDESC		= '';
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESC	= $rowJOBDESC->JOBDESC;
				endforeach;
				if($JOBDESC == '')
					$JOBDESC	= $OPNH_NOTE;
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$WO_CATEG		= $dataI['WO_CATEG'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$WO_STARTD		= '';
				$WO_ENDD		= '';
				$sqlWODT		= "SELECT WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWODT		= $this->db->query($sqlWODT)->result();
				foreach($resWODT as $rowWODT) :
					$WO_STARTD	= $rowWODT->WO_STARTD;
					$WO_ENDD	= $rowWODT->WO_ENDD;
				endforeach;
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				
				$WO_CATEGD		= '';
				if($WO_CATEG == 'SALT')
					$WO_CATEGD	= "Alat / Tools";
				elseif($WO_CATEG == 'SUB')
					$WO_CATEGD	= "Subkon";
				else
					$WO_CATEGD	= "Mandor";
				
				$CollID			= "$PRJCODE~$OPNH_NUM";
				$secUpd			= site_url('c_project/c_o180d0bpnm/update/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b4/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint3		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b43/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secDel_OPN 	= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id='.$OPNH_NUM;
				$secVoid 		= base_url().'index.php/__l1y/voidDoc_OPN/?id=';
				$voidID 		= "$secVoid~tbl_opn_header~tbl_opn_detail~OPNH_NUM~$OPNH_NUM~PRJCODE~$PRJCODE";
				$secDelID 		= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id=';
				$secDel_OPN		= "$secDelID~$OPNH_NUM~$OPNH_CODE";
                                    
				if($OPNH_STAT == 1 || $OPNH_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								 	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$secDel_OPN."'>
								   	<input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")' title='Lap. Kemajuan Pekerjaan'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($OPNH_STAT == 3) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")' title='Lap. Kemajuan Pekerjaan'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' $isdisabledVw>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")' title='Lap. Kemajuan Pekerjaan'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN242';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$OPNH_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<div>$isLockD<strong>".$OPNH_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$OPNH_DATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i>$Name ($WO_CATEGD)</strong>
										  		<div style='white-space:nowrap'>
											  		<p class='text-muted' style='margin-left: 17px; white-space:nowrap'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div><strong>".$WO_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$WO_STARTDV." - ".$WO_ENDDV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  			$OPNH_NOTE
										  	</div>",
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

  	function get_AllDataGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$OPNH_STAT		= $_GET['DSTAT'];
		$OPNH_CATEG		= $_GET['SRC'];
		$WO_CODE		= $_GET['WOCODE'];		
		$SELPRJ			= $_GET['PROJECT'];
		if(!empty($SELPRJ))
			$PRJCODE 	= $SELPRJ;
		
		$LangID     	= $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
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
			
			$columns_valid 	= array("OPNH_CODE",
									"OPNH_DATE",
									"WO_CODE", 
									"OPNH_NOTE", 
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
			$num_rows 		= $this->m_opname->get_AllDataGRPC($PRJCODE, $SPLCODE, $OPNH_STAT, $OPNH_CATEG, $WO_CODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_opname->get_AllDataGRPL($PRJCODE, $SPLCODE, $OPNH_STAT, $OPNH_CATEG, $WO_CODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$OPNH_NUM		= $dataI['OPNH_NUM'];
				$OPNH_CODE		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				//$OPNH_DATEV		= date('d M Y', strtotime($OPNH_DATE));
				$OPNH_DATEV		= strftime('%d %b %Y', strtotime($OPNH_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$OPNH_NOTE		= $dataI['OPNH_NOTE'];
				$OPNH_STAT		= $dataI['OPNH_STAT'];
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$OPNH_CREATER	= $dataI['OPNH_CREATER'];
				$OPNH_ISCLOSE	= $dataI['OPNH_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];

				// GET isLock in Journal
					$isLock = 0;
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
					$getJ 	= "SELECT IFNULL(isLock,0) AS isLock FROM tbl_journalheader_$PRJCODEVW 
								WHERE GEJ_STAT = 3 AND JournalH_Code = '$OPNH_NUM'";
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
					
				$splitJOB 		= explode("~", $JOBCODEID);
				$JOBCODEID		= $splitJOB[0];
				$JOBDESC		= '';
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESC	= $rowJOBDESC->JOBDESC;
				endforeach;
				if($JOBDESC == '')
					$JOBDESC	= $OPNH_NOTE;
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$WO_CATEG		= $dataI['WO_CATEG'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$WO_STARTD		= '';
				$WO_ENDD		= '';
				$sqlWODT		= "SELECT WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWODT		= $this->db->query($sqlWODT)->result();
				foreach($resWODT as $rowWODT) :
					$WO_STARTD	= $rowWODT->WO_STARTD;
					$WO_ENDD	= $rowWODT->WO_ENDD;
				endforeach;
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				
				$WO_CATEGD		= '';
				if($WO_CATEG == 'SALT')
					$WO_CATEGD	= "Alat / Tools";
				elseif($WO_CATEG == 'SUB')
					$WO_CATEGD	= "Subkon";
				else
					$WO_CATEGD	= "Mandor";
				
				$CollID			= "$PRJCODE~$OPNH_NUM";
				$secUpd			= site_url('c_project/c_o180d0bpnm/update/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b4/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint3		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b43/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secDel_OPN 	= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id='.$OPNH_NUM;
				$secVoid 		= base_url().'index.php/__l1y/voidDoc_OPN/?id=';
				$voidID 		= "$secVoid~tbl_opn_header~tbl_opn_detail~OPNH_NUM~$OPNH_NUM~PRJCODE~$PRJCODE";
				$secDelID 		= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id=';
				$secDel_OPN		= "$secDelID~$OPNH_NUM~$OPNH_CODE";

				if($OPNH_STAT == 1 || $OPNH_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								 	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$secDel_OPN."'>
								   	<input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")' title='Lap. Kemajuan Pekerjaan'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($OPNH_STAT == 3) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")' title='Lap. Kemajuan Pekerjaan'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' $isdisabledVw>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")' title='Lap. Kemajuan Pekerjaan'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN242';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$OPNH_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<div>$isLockD<strong>".$OPNH_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$OPNH_DATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i>$Name ($WO_CATEGD)</strong>
										  		<div style='white-space:nowrap'>
											  		<p class='text-muted' style='margin-left: 17px; white-space:nowrap'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div><strong>".$WO_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$WO_STARTDV." - ".$WO_ENDDV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  			$OPNH_NOTE
										  	</div>",
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

  	function get_AllDataSH() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$ISCLS			= $_GET['ISCLS'];

		$LangID     	= $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
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
			
			$columns_valid 	= array("OPNH_CODE",
									"OPNH_DATE",
									"WO_CODE", 
									"OPNH_NOTE", 
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
			$num_rows 		= $this->m_opname->get_AllDataSHC($PRJCODE, $ISCLS, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_opname->get_AllDataSHL($PRJCODE, $ISCLS, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$OPNH_NUM		= $dataI['OPNH_NUM'];
				$OPNH_CODE		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				//$OPNH_DATEV		= date('d M Y', strtotime($OPNH_DATE));
				$OPNH_DATEV		= strftime('%d %b %Y', strtotime($OPNH_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$OPNH_NOTE		= $dataI['OPNH_NOTE'];
				$OPNH_STAT		= $dataI['OPNH_STAT'];
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$OPNH_CREATER	= $dataI['OPNH_CREATER'];
				$OPNH_ISCLOSE	= $dataI['OPNH_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];

				// GET isLock in Journal
					$isLock = 0;
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
					$getJ 	= "SELECT IFNULL(isLock,0) AS isLock FROM tbl_journalheader_$PRJCODEVW 
								WHERE GEJ_STAT = 3 AND JournalH_Code = '$OPNH_NUM'";
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
					
				$splitJOB 		= explode("~", $JOBCODEID);
				$JOBCODEID		= $splitJOB[0];
				$JOBDESC		= '';
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESC	= $rowJOBDESC->JOBDESC;
				endforeach;
				if($JOBDESC == '')
					$JOBDESC	= $OPNH_NOTE;
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$WO_CATEG		= $dataI['WO_CATEG'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$WO_STARTD		= '';
				$WO_ENDD		= '';
				$sqlWODT		= "SELECT WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWODT		= $this->db->query($sqlWODT)->result();
				foreach($resWODT as $rowWODT) :
					$WO_STARTD	= $rowWODT->WO_STARTD;
					$WO_ENDD	= $rowWODT->WO_ENDD;
				endforeach;
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				
				$WO_CATEGD		= '';
				if($WO_CATEG == 'SALT')
					$WO_CATEGD	= "Alat / Tools";
				elseif($WO_CATEG == 'SUB')
					$WO_CATEGD	= "Subkon";
				else
					$WO_CATEGD	= "Mandor";
				
				$CollID			= "$PRJCODE~$OPNH_NUM";
				$secUpd			= site_url('c_project/c_o180d0bpnm/update/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b4/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint3		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b43/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secDelID 		= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id=';
				$secDel_OPN		= "$secDelID~$OPNH_NUM~$OPNH_CODE";
                                    
				if($OPNH_STAT == 1 || $OPNH_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$secDel_OPN."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_OPN."')' title='Delete file' class='btn btn-danger btn-xs' style='display:none' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<div>$isLockD<strong>".$OPNH_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$OPNH_DATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i>$Name ($WO_CATEGD)</strong>
										  		<div style='white-space:nowrap'>
											  		<p class='text-muted' style='margin-left: 17px; white-space:nowrap'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div><strong>".$WO_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$WO_STARTDV." - ".$WO_ENDDV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  			$OPNH_NOTE
										  	</div>",
										  	$empName,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataWO() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'Periode')$Periode = $LangTransl;
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
									"WO_CODE",
									"WO_DATE", 
									"WO_NOTE", 
									"", 
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
			$num_rows 		= $this->m_opname->get_AllDataWOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_opname->get_AllDataWOL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_DATEV		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_STARTD		= $dataI['WO_STARTD'];
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDD		= $dataI['WO_ENDD'];
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				$WO_NOTE		= $dataI['WO_NOTE'];
				$PRJNAME		= $dataI['PRJNAME'];
				$complName		= $dataI['complName'];

				if($WO_NOTE == '')
					$WO_NOTE 	= "-";
				
				// GET TOTAL SPK QTY
					$TOTWO_VOL		= 0;
					$TOTWO_AMN		= 0;
					$TOTWO_PPN		= 0;
					$TOTWO_PPH		= 0;
					$TOTOPN_VOL		= 0;
					$TOTOPN_AMN		= 0;
					$TOTREM_VOL		= 0;
					$TOTREM_VOL		= 0;
					$sqlQtyWO 		= "SELECT 	SUM(WO_VOLM-WO_CVOL) 	AS TOTWO_VOL, 
												SUM(WO_TOTAL-WO_CAMN) 	AS TOTWO_AMN, 
												SUM(TAXPRICE1) 	AS TOTWO_PPN,
												SUM(TAXPRICE2) 	AS TOTWO_PPH,
												SUM(OPN_VOLM) 	AS TOTOPN_VOL,
												SUM(OPN_AMOUNT) AS TOTOPN_AMN
										FROM tbl_wo_detail
										WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
					$resQtyWO 		= $this->db->query($sqlQtyWO)->result();
					foreach($resQtyWO as $rowWOQty):
						$TOTWO_VOL		= $rowWOQty->TOTWO_VOL;
						$TOTWO_AMN		= $rowWOQty->TOTWO_AMN;
						$TOTWO_PPN		= $rowWOQty->TOTWO_PPN;
						$TOTWO_PPH		= $rowWOQty->TOTWO_PPH;
						$TOTOPN_VOL		= $rowWOQty->TOTOPN_VOL;
						$TOTOPN_AMN		= $rowWOQty->TOTOPN_AMN;
					endforeach;

					// OPN - CONFIRMED
						$TOTOPNAMN	= 0;
						$TOTOPNVOL	= 0;
						$sqlTOT_OPN	= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
											SUM(A.OPND_VOLM) AS TOTOPN_VOL
										FROM tbl_opn_detail A
										INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
											AND B.PRJCODE = '$PRJCODE'
										WHERE B.WO_NUM = '$WO_NUM'
											AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT = 2";
						$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
						foreach($resTOT_OPN as $rowTOT_OPN) :
							$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
							$TOTOPNVOL	= $rowTOT_OPN->TOTOPN_VOL;
							if($TOTOPNAMN == '')
								$TOTOPNAMN	= 0;
							if($TOTOPNVOL == '')
								$TOTOPNVOL	= 0;
						endforeach;

					$confDesc 		= "";
					if($TOTOPNAMN > 0)
					{
						$confDesc 	= "<br><div style='white-space:nowrap' title='Confirmed'>
										  		<span class='label label-primary' style='font-size:12px'>".number_format($TOTOPNVOL,2)."</span>&nbsp;
										  		<span class='label label-warning' style='font-size:12px'>".number_format($TOTOPNAMN,2)."</span>
										  	</div>";
					}

					$TOTREM_VOL		= $TOTWO_VOL - $TOTOPN_VOL - $TOTOPNVOL;
					$TOTREM_AMN		= $TOTWO_AMN - $TOTOPN_AMN - $TOTOPNAMN;

					$disabledB 			= 0;
					if($TOTREM_VOL <= 0 || $TOTREM_AMN <= 0)
						$disabledB 		= 1;

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='radio' name='chk0' value='".$WO_NUM."|".$WO_CODE."' onClick='pickThis0(this);'/>";
					}
					else
					{
						$chkBox	= "<input type='radio' name='chk0' value='' style='display: none' />";
					}

				$WO_NOTE 		= wordwrap($WO_NOTE, 60, "<br>", TRUE);
				
				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>
										  		".$WO_CODE."
										  	</div>
										  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
									  		<p class='text-muted' style='margin-left: 20px; white-space:nowrap'>
									  			".$WO_DATEV."
									  		</p>
										  	",
										  	"<strong><i class='fa fa-calendar margin-r-5'></i> ".$Periode." </strong>
									  		<p class='text-muted' style='margin-left: 20px; white-space:nowrap'>
									  			".$WO_STARTDV." - ".$WO_ENDDV."
									  		</p>
									  		<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  		$WO_NOTE
										  	</div>",
										  	"<div style='white-space:nowrap'>
										  		<span class='label label-success' style='font-size:12px; display: block;'>".number_format($TOTWO_VOL,2)."</span>&nbsp;
										  		<span class='label label-warning' style='font-size:12px; display: block;'>".number_format($TOTWO_AMN,2)."</span>
										  	</div>",
										  	"<div style='white-space:nowrap' title='Approved'>
										  		<span class='label label-success' style='font-size:12px; display: block;'>".number_format($TOTOPN_VOL,2)."</span>&nbsp;
										  		<span class='label label-warning' style='font-size:12px; display: block;'>".number_format($TOTOPN_AMN,2)."</span>
										  	</div>".$confDesc."
										  	",
										  	"<div style='white-space:nowrap'>
										  		<span class='label label-success' style='font-size:12px; display: block;'>".number_format($TOTREM_VOL,2)."</span>&nbsp;
										  		<span class='label label-warning' style='font-size:12px; display: block;'>".number_format($TOTREM_AMN,2)."</span>
										  	</div>");
				$noU		= $noU + 1;
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
	
	function add() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN241';
			$MenuCode			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data["MenuApp"] 	= 'MN242';
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
			$data['form_action']= site_url('c_project/c_o180d0bpnm/add_process');
			$data['backURL'] 	= site_url('c_project/c_o180d0bpnm/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data["MenuCode1"] 	= 'MN242';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data['vwDocPatt'] 	= $this->m_opname->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN241';
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
			
			$this->load->view('v_project/v_opname/opname_form', $data);
			//$this->load->view('page_uc');
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
		
		$PRDate		= date('Y-m-d',strtotime($this->input->post('WODate')));
		$year		= date('Y',strtotime($this->input->post('WODate')));
		$month 		= (int)date('m',strtotime($this->input->post('WODate')));
		$date 		= (int)date('d',strtotime($this->input->post('WODate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_opn_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_opn_header
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
	
	function popupallOPNH()
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
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
					
			$this->load->view('v_project/v_opname/opname_sel_spk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function p0p4llw0()
	{
		$colD = $_GET['id'];
		$url  = site_url('c_project/c_o180d0bpnm/p0pw0x/?id='.$this->url_encryption_helper->encode_url($colD));
		redirect($url);
	}
	
	function p0pw0x()
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$colD1		= $_GET['id'];
			$colD		= $this->url_encryption_helper->decode_url($colD1);
			$splt 		= explode("~", $colD);
			$WOCAT		= $splt[0];
			$PRJCODE	= $splt[1];
			
			$data['title'] 		= $appName;
			$data['pageFrom']	= 'PR';
			$data['WOCAT']		= $WOCAT;
			$data['PRJCODE']	= $PRJCODE;
					
			$this->load->view('v_project/v_opname/opname_sel_spk', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function popupallitem() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_opname/m_opname', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_o180d0bpnm/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem']	= $this->m_opname->count_all_num_rowsAllItem($PRJCODE);
			$data['vwAllItem'] 		= $this->m_opname->viewAllItemMatBudget($PRJCODE)->result();
					
			$this->load->view('v_project/v_opname/opname_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$OPNH_STAT 		= $this->input->post('OPNH_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$WO_NUM 		= $this->input->post('WO_NUM');
			$OPNH_NUM 		= $this->input->post('OPNH_NUM');
			$OPNH_CODE 		= $this->input->post('OPNH_CODE');
			//setting WO Date
			$OPNH_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$Patt_Year	= date('Y',strtotime($OPNH_DATE));
				$Patt_Month	= date('m',strtotime($OPNH_DATE));
				$Patt_Date	= date('d',strtotime($OPNH_DATE));
			$OPNH_DATESP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATESP'))));
			$OPNH_DATEEP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATEEP'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$OPNH_NOTE 		= htmlspecialchars($this->input->post('OPNH_NOTE'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$OPNH_AMOUNT	= $this->input->post('OPNH_AMOUNT');
			$OPNH_AMOUNTPPNP= $this->input->post('OPNH_AMOUNTPPNP');
			$OPNH_AMOUNTPPN	= $this->input->post('OPNH_AMOUNTPPN');
			$OPNH_AMOUNTPPHP= $this->input->post('OPNH_AMOUNTPPHP');
			$OPNH_AMOUNTPPH	= $this->input->post('OPNH_AMOUNTPPH');
			$OPNH_RETPERC	= $this->input->post('OPNH_RETPERC');
			$OPNH_RETAMN	= $this->input->post('OPNH_RETAMN');
			$OPNH_RETNO		= $this->input->post('OPNH_RETNO');
			$OPNH_DPPER		= $this->input->post('OPNH_DPPER');
			$OPNH_DPVAL		= $this->input->post('OPNH_DPVAL');
			$OPNH_POT		= $this->input->post('OPNH_POT');
			$OPNH_POTREF	= $this->input->post('OPNH_POTREF');
			$OPNH_POTREF1	= $this->input->post('OPNH_POTREF1');
			$OPNH_POTACCID	= $this->input->post('OPNH_POTACCID');
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN241';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				//$OPNH_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			$JOBCODEID		= $this->input->post('JOBCODEID');
			$Patt_Number	= $this->input->post('Patt_Number');
			
			foreach($_POST['data'] as $d)
			{
				$d['OPNH_NUM']		= $OPNH_NUM;
				$ITM_QTY 			= $d['OPND_VOLM'];
				//$OPNH_AMOUNT		= $OPNH_AMOUNT + $d['OPND_ITMTOTAL'];
				//$OPNH_AMOUNTPPN		= $OPNH_AMOUNTPPN + $d['TAXPRICE1'];
				if($ITM_QTY > 0)
					$this->db->insert('tbl_opn_detail',$d);

				if($OPNH_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$OPN_VAL	= $d['OPND_ITMTOTAL'];
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $OPNH_DATE,
											'FVAL'		=> $OPN_VAL,
											'FNAME'		=> "OPN_VAL");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$JOBID 		= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$DOC_VOLM	= $d['OPND_VOLM'];
					$DOC_TOTAL	= $d['OPND_ITMTOTAL'];
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $OPNH_DATE,
										'JOBID'		=> $JOBID,
										'ITM_CODE'	=> $ITM_CODE,
										'DOC_VOLM'	=> $DOC_VOLM,
										'DOC_TOTAL'	=> $DOC_TOTAL,
										'VAR_VOL_R'	=> "OPN_VOL_R",
										'VAR_VAL_R'	=> "OPN_VAL_R");
					//$this->m_updash->updJOBP($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL
			}

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'DOCCAT'		=> $WO_CATEG,
										'MNCODE' 		=> $MenuCode,
										'DOCTYPE' 		=> 'OPN',
										'DOCNUM' 		=> $OPNH_NUM,
										'DOCCODE'		=> $OPNH_CODE,
										'DOCDATE'		=> $OPNH_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $OPNH_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE";
				$config['allowed_types'] 	= "jpg|jpeg|png|gif|pdf";
				// $config['max_size'] 		= 0; // Unlimited
				$config['overwrite'] 		= false;
				
				for($i = 0; $i < $count; $i++) {
					if(!empty($_FILES['userfile']['name'][$i])) {

						$_FILES['userfile']['name']     = $files['userfile']['name'][$i];
						$_FILES['userfile']['type']     = $files['userfile']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
						$_FILES['userfile']['error']    = $files['userfile']['error'][$i];
						$_FILES['userfile']['size']     = $files['userfile']['size'][$i];

						$this->load->library('upload', $config);

						if($this->upload->do_upload('userfile'))
						{
							// $data[] 		= $this->upload->data();
							$upl_data 		= $this->upload->data();
							$fileName 		= $upl_data['file_name'];
							$srvURL 		= $_SERVER['SERVER_ADDR'];
							// $fileName 	= $file['file_name'];
							$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/$fileName";
	
							if($srvURL == '10.0.0.144')
							{
								$this->load->library('ftp');
				
								$config['hostname'] = 'sdbpplus.nke.co.id';
								$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
								$config['password'] = 'NKE@dmin2021';
								$config['debug']    = TRUE;
								
								$this->ftp->connect($config);
								
								$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/$fileName";
	
								if($this->ftp->list_files("./assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/") == FALSE) 
								{
									$this->ftp->mkdir("./assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/", 0777);
								}
								
								$this->ftp->upload($source, ".".$destination);
								
								$this->ftp->close();
							}
	
							$UPL_NUM 	= "UPL".date('YmdHis');
							$UPL_DATE 	= date('Y-m-d');
							$uplFile 	= ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $OPNH_NUM, "REF_CODE" => $OPNH_CODE,
										"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
										"UPL_FILENAME" => $upl_data['file_name'], "UPL_FILESIZE" => $upl_data['file_size'],
										"UPL_FILETYPE" => $upl_data['file_type'], "UPL_FILEPATH" => $upl_data['file_path'], 
										"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_opname->uplDOC_TRX($uplFile);
						}					
					}
				}
			
			// ============================= End Upload File ========================================== //
		        
			$projOPNH 		= array('OPNH_NUM' 		=> $OPNH_NUM,
									'OPNH_CODE' 	=> $OPNH_CODE,
									'OPNH_DATE'		=> $OPNH_DATE,
									'OPNH_DATESP'	=> $OPNH_DATESP,
									'OPNH_DATEEP' 	=> $OPNH_DATEEP,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NUM'		=> $WO_NUM,
									'WO_CODE'		=> $WO_CODE,
									'OPNH_AMOUNT'	=> $OPNH_AMOUNT,
									'OPNH_AMOUNTPPNP'=> $OPNH_AMOUNTPPNP,
									'OPNH_AMOUNTPPN'=> $OPNH_AMOUNTPPN,
									'OPNH_AMOUNTPPHP'=> $OPNH_AMOUNTPPHP,
									'OPNH_AMOUNTPPH'=> $OPNH_AMOUNTPPH,
									'OPNH_RETPERC'	=> $OPNH_RETPERC,
									'OPNH_RETAMN'	=> $OPNH_RETAMN,
									'OPNH_RETNO' 	=> $OPNH_RETNO,
									'OPNH_NOTE'		=> $OPNH_NOTE,
									'OPNH_DPPER'	=> $OPNH_DPPER,
									'OPNH_DPVAL'	=> $OPNH_DPVAL,
									'OPNH_POT'		=> $OPNH_POT,
									'OPNH_POTREF'	=> $OPNH_POTREF,
									'OPNH_POTREF1'	=> $OPNH_POTREF1,
									'OPNH_POTACCID'	=> $OPNH_POTACCID,
									'OPNH_STAT'		=> $OPNH_STAT,
									'OPNH_CREATER'	=> $DefEmp_ID,
									'OPNH_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_opname->add($projOPNH);
			
			// UPDATE DETAIL
				$this->m_opname->updateDet($OPNH_NUM, $PRJCODE, $OPNH_DATE);
				
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('OPNH_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $OPNH_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "OPN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_opn_header",// TABLE NAME
										'KEY_NAME'		=> "OPNH_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "OPNH_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $OPNH_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_OPN",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_OPN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_OPN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_OPN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_OPN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_OPN_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_OPN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
										'DOC_CODE' 		=> $OPNH_NUM,
										'DOC_STAT' 		=> $OPNH_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_opn_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			if($OPNH_STAT == 2)
			{
				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "OPN",
										'AS_MNCODE'		=> "MN242",
										'AS_DOCNUM'		=> $OPNH_NUM,
										'AS_DOCCODE'	=> $OPNH_CODE,
										'AS_DOCDATE'	=> $OPNH_DATESP,
										'AS_EXPDATE'	=> $OPNH_DATEEP);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN241';
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

				$parameters 	= array('TR_TYPE'		=> "OPN",
										'TR_DATE' 		=> $OPNH_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE

			// START : UPDATE ITEM_LOG
				$parIL 	= array('DOC_CATEG'		=> "OPN",
								'DOC_NUM'		=> $OPNH_NUM,
								'DOC_STAT'		=> $OPNH_STAT,
								'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updIL($parIL);
			// END : UPDATE ITEM_LOG

			// START : ADD DOC HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				date_default_timezone_set("Asia/Jakarta");
				$paramTrack 	= array('REF_NUM' 		=> $OPNH_NUM,
										'TBLNAME' 		=> "tbl_opn_header",
										'FLDCODE'		=> "OPNH_CODE",
										'FLDNAME'		=> "OPNH_NUM",
										'HISTTYPE'		=> "Penambahan ($OPNH_STAT)");
				$this->m_updash->uDocH($paramTrack);
			// END : ADD DOC HISTORY
			
			$url			= site_url('c_project/c_o180d0bpnm/gal180d0bopn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN241';
			$MenuCode			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data["MenuApp"] 	= 'MN242';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$OPNHNUM	= $_GET['id'];
		$OPNHNUM	= $this->url_encryption_helper->decode_url($OPNHNUM);

		$collData	= explode("~", $OPNHNUM);
		$PRJCODE	= $collData[0];
		$OPNH_NUM	= $collData[1];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';

			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$data['form_action']= site_url('c_project/c_o180d0bpnm/update_process');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
									
			$getOPN 						= $this->m_opname->get_opn_by_number($OPNH_NUM)->row();
			$data['default']['OPNH_NUM'] 	= $getOPN->OPNH_NUM;
			$data['default']['OPNH_CODE'] 	= $getOPN->OPNH_CODE;
			$data['default']['OPNH_DATE'] 	= $getOPN->OPNH_DATE;
			$data['default']['OPNH_DATESP'] = $getOPN->OPNH_DATESP;
			$data['default']['OPNH_DATEEP'] = $getOPN->OPNH_DATEEP;
			$data['default']['PRJCODE']		= $getOPN->PRJCODE;
			$data['PRJCODE']				= $getOPN->PRJCODE;
			$PRJCODE 						= $getOPN->PRJCODE;
			$data['default']['SPLCODE'] 	= $getOPN->SPLCODE;
			$data['default']['JOBCODEID'] 	= $getOPN->JOBCODEID;
			$data['default']['OPNH_NOTE'] 	= $getOPN->OPNH_NOTE;
			$data['default']['OPNH_NOTE2'] 	= $getOPN->OPNH_NOTE2;
			$data['default']['OPNH_STAT'] 	= $getOPN->OPNH_STAT;
			$data['default']['WO_NUM'] 		= $getOPN->WO_NUM;
			$data['default']['OPNH_AMOUNT'] = $getOPN->OPNH_AMOUNT;
			$data['default']['OPNH_AMOUNTPPNP'] = $getOPN->OPNH_AMOUNTPPNP;
			$data['default']['OPNH_AMOUNTPPN'] = $getOPN->OPNH_AMOUNTPPN;
			$data['default']['OPNH_AMOUNTPPHP'] = $getOPN->OPNH_AMOUNTPPHP;
			$data['default']['OPNH_AMOUNTPPH'] = $getOPN->OPNH_AMOUNTPPH;
			$data['default']['OPNH_RETPERC']= $getOPN->OPNH_RETPERC;
			$data['default']['OPNH_RETAMN'] = $getOPN->OPNH_RETAMN;
			$data['default']['OPNH_RETNO'] = $getOPN->OPNH_RETNO;
			$data['default']['OPNH_MEMO'] 	= $getOPN->OPNH_MEMO;
			$data['default']['PRJNAME'] 	= $getOPN->PRJNAME;
			$data['default']['OPNH_DPPER'] 	= $getOPN->OPNH_DPPER;
			$data['default']['OPNH_DPVAL'] 	= $getOPN->OPNH_DPVAL;
			$data['default']['OPNH_POT'] 	= $getOPN->OPNH_POT;
			$data['default']['OPNH_POTREF'] = $getOPN->OPNH_POTREF;
			$data['default']['OPNH_POTREF1'] = $getOPN->OPNH_POTREF1;
			$data['default']['OPNH_POTACCID'] = $getOPN->OPNH_POTACCID;
			$data['default']['Patt_Year'] 	= $getOPN->Patt_Year;
			$data['default']['Patt_Month'] 	= $getOPN->Patt_Month;
			$data['default']['Patt_Date'] 	= $getOPN->Patt_Date;
			$data['default']['Patt_Number']	= $getOPN->Patt_Number;
			
			$MenuCode 			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data['vwDocPatt'] 	= $this->m_opname->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getOPN->OPNH_NUM;
				$MenuCode 		= 'MN241';
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
			
			$this->load->view('v_project/v_opname/opname_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$OPNH_STAT 		= $this->input->post('OPNH_STAT'); // 1 = New, 2 = confirm, 3 = Close
			
			$WO_NUM 		= $this->input->post('WO_NUM');
			$OPNH_NUM 		= $this->input->post('OPNH_NUM');
			$OPNH_CODE 		= $this->input->post('OPNH_CODE');
			//setting WO Date
			$OPNH_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
			$OPNH_DATESP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATESP'))));
			$OPNH_DATEEP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATEEP'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$OPNH_NOTE 		= htmlspecialchars($this->input->post('OPNH_NOTE'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$OPNH_NOTE2		= htmlspecialchars($this->input->post('OPNH_MEMO'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$OPNH_AMOUNT	= $this->input->post('OPNH_AMOUNT');
			$OPNH_AMOUNTPPNP	= $this->input->post('OPNH_AMOUNTPPNP');
			$OPNH_AMOUNTPPN	= $this->input->post('OPNH_AMOUNTPPN');
			$OPNH_AMOUNTPPHP= $this->input->post('OPNH_AMOUNTPPHP');
			$OPNH_AMOUNTPPH	= $this->input->post('OPNH_AMOUNTPPH');
			$OPNH_RETPERC	= $this->input->post('OPNH_RETPERC');
			$OPNH_RETAMN	= $this->input->post('OPNH_RETAMN');
			$OPNH_RETNO 	= $this->input->post('OPNH_RETNO');
			$OPNH_DPPER		= $this->input->post('OPNH_DPPER');
			$OPNH_DPVAL		= $this->input->post('OPNH_DPVAL');
			$OPNH_POT		= $this->input->post('OPNH_POT');
			$OPNH_POTREF	= $this->input->post('OPNH_POTREF');
			$OPNH_POTREF1	= $this->input->post('OPNH_POTREF1');
			$OPNH_POTACCID	= $this->input->post('OPNH_POTACCID');
			
			$JOBCODEID		= $this->input->post('JOBCODEID');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN241',
										'DOCNUM' 		=> $OPNH_NUM,
										'DOCCODE'		=> $OPNH_CODE,
										'DOCDATE'		=> $OPNH_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist			

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE";
				$config['allowed_types'] 	= "jpg|jpeg|png|gif|pdf";
				// $config['max_size'] 		= 0; // Unlimited
				$config['overwrite'] 		= false;
				
				for($i = 0; $i < $count; $i++) {
					if(!empty($_FILES['userfile']['name'][$i])) {

						$_FILES['userfile']['name']     = $files['userfile']['name'][$i];
						$_FILES['userfile']['type']     = $files['userfile']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
						$_FILES['userfile']['error']    = $files['userfile']['error'][$i];
						$_FILES['userfile']['size']     = $files['userfile']['size'][$i];

						$this->load->library('upload', $config);

						if($this->upload->do_upload('userfile'))
						{
							// $data[] 		= $this->upload->data();
							$upl_data 		= $this->upload->data();
							$fileName 		= $upl_data['file_name'];
							$srvURL 		= $_SERVER['SERVER_ADDR'];
							// $fileName 	= $file['file_name'];
							$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/$fileName";
	
							if($srvURL == '10.0.0.144')
							{
								$this->load->library('ftp');
				
								$config['hostname'] = 'sdbpplus.nke.co.id';
								$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
								$config['password'] = 'NKE@dmin2021';
								$config['debug']    = TRUE;
								
								$this->ftp->connect($config);
								
								$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/$fileName";
	
								if($this->ftp->list_files("./assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/") == FALSE) 
								{
									$this->ftp->mkdir("./assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/", 0777);
								}
								
								$this->ftp->upload($source, ".".$destination);
								
								$this->ftp->close();
							}
	
							$UPL_NUM 	= "UPL".date('YmdHis');
							$UPL_DATE 	= date('Y-m-d');
							$uplFile 	= ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $OPNH_NUM, "REF_CODE" => $OPNH_CODE,
										"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
										"UPL_FILENAME" => $upl_data['file_name'], "UPL_FILESIZE" => $upl_data['file_size'],
										"UPL_FILETYPE" => $upl_data['file_type'], "UPL_FILEPATH" => $upl_data['file_path'], 
										"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_opname->uplDOC_TRX($uplFile);
						}
					}
				}
			
			// ============================= End Upload File ========================================== //
			
			if($OPNH_STAT == 9)
			{
				// SUDAH ADA DI CONTROLLER __l1y.php
			}
			else
			{
				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $OPNH_DATE,
										'DOC_NUM'	=> $OPNH_NUM,
										'DOC_CATEG'	=> "OPN");
					//$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				$this->m_opname->deleteDetail($OPNH_NUM);

				foreach($_POST['data'] as $d)
				{
					$ITM_QTY 		= $d['OPND_VOLM'];
					if($ITM_QTY > 0) $this->db->insert('tbl_opn_detail',$d);

					if($OPNH_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$OPN_VAL	= $d['OPND_ITMTOTAL'];
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $OPNH_DATE,
												'FVAL'		=> $OPN_VAL,
												'FNAME'		=> "OPN_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$JOBID 		= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$DOC_VOLM	= $d['OPND_VOLM'];
						$DOC_TOTAL	= $d['OPND_ITMTOTAL'];
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $OPNH_DATE,
											'JOBID'		=> $JOBID,
											'ITM_CODE'	=> $ITM_CODE,
											'DOC_VOLM'	=> $DOC_VOLM,
											'DOC_TOTAL'	=> $DOC_TOTAL,
											'VAR_VOL_R'	=> "OPN_VOL_R",
											'VAR_VAL_R'	=> "OPN_VAL_R");
						//$this->m_updash->updJOBP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
				}
				
				$projOPNH 		= array('OPNH_NUM' 		=> $OPNH_NUM,
										'OPNH_CODE' 	=> $OPNH_CODE,
										'OPNH_DATE'		=> $OPNH_DATE,
										'OPNH_DATESP'	=> $OPNH_DATESP,
										'OPNH_DATEEP' 	=> $OPNH_DATEEP,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE'		=> $SPLCODE,
										'JOBCODEID'		=> $JOBCODEID,
										'WO_NUM'		=> $WO_NUM,
										'WO_CODE'		=> $WO_CODE,
										'WO_CATEG'		=> $WO_CATEG,
										'OPNH_NOTE'		=> $OPNH_NOTE,
										'OPNH_AMOUNT'	=> $OPNH_AMOUNT,
										'OPNH_AMOUNTPPNP'=> $OPNH_AMOUNTPPNP,
										'OPNH_AMOUNTPPN'=> $OPNH_AMOUNTPPN,
										'OPNH_AMOUNTPPHP'=> $OPNH_AMOUNTPPHP,
										'OPNH_AMOUNTPPH'=> $OPNH_AMOUNTPPH,
										'OPNH_RETPERC'	=> $OPNH_RETPERC,
										'OPNH_RETAMN'	=> $OPNH_RETAMN,
										'OPNH_RETNO' 	=> $OPNH_RETNO,
										'OPNH_DPPER'	=> $OPNH_DPPER,
										'OPNH_DPVAL'	=> $OPNH_DPVAL,
										'OPNH_POT'		=> $OPNH_POT,
										'OPNH_POTREF'	=> $OPNH_POTREF,
										'OPNH_POTREF1'	=> $OPNH_POTREF1,
										'OPNH_POTACCID'	=> $OPNH_POTACCID,
										'OPNH_STAT'		=> $OPNH_STAT,
										'OPNH_CREATER'	=> $DefEmp_ID,
										'OPNH_CREATED'	=> date('Y-m-d H:i:s'),
										'Patt_Year'		=> $Patt_Year, 
										'Patt_Month'	=> $Patt_Month,
										'Patt_Date'		=> $Patt_Date,
										'Patt_Number'	=> $Patt_Number);
				$this->m_opname->update($OPNH_NUM, $projOPNH);
				
				// UPDATE DETAIL
					$this->m_opname->updateDet($OPNH_NUM, $PRJCODE, $OPNH_DATE);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $OPNH_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "OPN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_opn_header",// TABLE NAME
										'KEY_NAME'		=> "OPNH_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "OPNH_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $OPNH_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_OPN",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_OPN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_OPN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_OPN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_OPN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_OPN_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_OPN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
										'DOC_CODE' 		=> $OPNH_NUM,
										'DOC_STAT' 		=> $OPNH_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_opn_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			if($OPNH_STAT == 2)
			{
				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "OPN",
										'AS_MNCODE'		=> "MN242",
										'AS_DOCNUM'		=> $OPNH_NUM,
										'AS_DOCCODE'	=> $OPNH_CODE,
										'AS_DOCDATE'	=> $OPNH_DATESP,
										'AS_EXPDATE'	=> $OPNH_DATEEP);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}
			
			// START : UPDATE TO T-TRACK				
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN241';
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

				$parameters 	= array('TR_TYPE'		=> "OPN",
										'TR_DATE' 		=> $OPNH_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE

			// START : UPDATE ITEM_LOG
				$parIL 	= array('DOC_CATEG'		=> "OPN",
								'DOC_NUM'		=> $OPNH_NUM,
								'DOC_STAT'		=> $OPNH_STAT,
								'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updIL($parIL);
			// END : UPDATE ITEM_LOG

			// START : ADD DOC HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				date_default_timezone_set("Asia/Jakarta");
				$paramTrack 	= array('REF_NUM' 		=> $OPNH_NUM,
										'TBLNAME' 		=> "tbl_opn_header",
										'FLDCODE'		=> "OPNH_CODE",
										'FLDNAME'		=> "OPNH_NUM",
										'HISTTYPE'		=> "Update ($OPNH_STAT)");
				$this->m_updash->uDocH($paramTrack);
			// END : ADD DOC HISTORY
			
			$url		= site_url('c_project/c_o180d0bpnm/gal180d0bopn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function trash_OPN() // U
	{
		$this->load->model('m_updash/m_updash', '', TRUE);

		$collID		= $_POST['collID'];
		$splitDATA 	= explode("~", $collID);
		$OPNH_NUM	= $splitDATA[1];
		$OPNH_CODE	= $splitDATA[2];

		$PRJCODE 	= "";
		$s_OPNH		= "SELECT PRJCODE FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM'";
		$r_OPNH		= $this->db->query($s_OPNH)->result();
		foreach($r_OPNH as $rw_OPNH) :
			$PRJCODE= $rw_OPNH->PRJCODE;
		endforeach;

		$s_DELH		= "DELETE FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM'";
		$this->db->query($s_DELH);

		$s_DELD		= "DELETE FROM tbl_opn_detail WHERE OPNH_NUM = '$OPNH_NUM'";
		$this->db->query($s_DELD);

		$s_UPDWO	= "UPDATE tbl_wo_detail A SET A.OPN_VOLM = IFNULL((SELECT SUM(C.OPND_VOLM) FROM tbl_opn_detail C WHERE C.WO_NUM = A.WO_NUM AND C.JOBCODEID = A.JOBCODEID AND C.ITM_CODE = A.ITM_CODE AND A.WO_ID = C.WO_ID), 0), A.OPN_AMOUNT = IFNULL((SELECT SUM(C.OPND_ITMTOTAL) FROM tbl_opn_detail C WHERE C.WO_NUM = A.WO_NUM AND C.JOBCODEID = A.JOBCODEID AND C.ITM_CODE = A.ITM_CODE AND A.WO_ID = C.WO_ID), 0) WHERE A.WO_NUM = '$OPNH_NUM'";
		$this->db->query($s_UPDWO);

		$delIL 		= "DELETE FROM tbl_item_log WHERE DOC_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
		$this->db->query($delIL);

		// START : ADD DOC HISTORY
			$this->load->model('m_updash/m_updash', '', TRUE);
			date_default_timezone_set("Asia/Jakarta");
			$paramTrack 	= array('REF_NUM' 		=> $OPNH_NUM,
									'TBLNAME' 		=> "tbl_opn_header",
									'FLDCODE'		=> "OPNH_CODE",
									'FLDNAME'		=> "OPNH_NUM",
									'HISTTYPE'		=> "Dihapus");
			$this->m_updash->uDocH($paramTrack);
		// END : ADD DOC HISTORY

		echo $OPNH_CODE;
	}
	
 	function inbox() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_o180d0bpnm/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN242';
				$MenuCode			= 'MN242';
				$data["MenuCode"] 	= 'MN242';
				$data["MenuApp"] 	= 'MN242';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN242';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_o180d0bpnm/inb1a1/?id=";
			
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
	
	function inb1a1() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN242';
			$MenuCode			= 'MN242';
			$data["MenuCode"] 	= 'MN242';
			$data["MenuApp"] 	= 'MN242';
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
				$data["url_search"]= site_url('c_project/c_o180d0bpnm/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_opname->count_all_OPNInx($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_opname->get_all_OPNInb($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_o180d0bpnm/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_o180d0bpnm/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN242';
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
			
			$this->load->view('v_project/v_opname/opname_inb', $data);
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
			$url			= site_url('c_project/c_o180d0bpnm/inb1a1/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
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
			
			$columns_valid 	= array("OPNH_DATE",
									"OPNH_DATE",
									"WO_CODE", 
									"OPNH_NOTE", 
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
			$num_rows 		= $this->m_opname->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_opname->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$OPNH_NUM		= $dataI['OPNH_NUM'];
				$OPNH_CODE		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				//$OPNH_DATEV		= date('d M Y', strtotime($OPNH_DATE));
				$OPNH_DATEV		= strftime('%d %b %Y', strtotime($OPNH_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$OPNH_NOTE		= $dataI['OPNH_NOTE'];
				$OPNH_STAT		= $dataI['OPNH_STAT'];
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$OPNH_CREATER	= $dataI['OPNH_CREATER'];
				$OPNH_ISCLOSE	= $dataI['OPNH_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
					
				$splitJOB 		= explode("~", $JOBCODEID);
				$JOBCODEID		= $splitJOB[0];
				$JOBDESC		= '';
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESC	= $rowJOBDESC->JOBDESC;
				endforeach;
				if($JOBDESC == '')
					$JOBDESC	= $OPNH_NOTE;
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$WO_CATEG		= $dataI['WO_CATEG'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$WO_STARTD		= '';
				$WO_ENDD		= '';
				$sqlWODT		= "SELECT WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWODT		= $this->db->query($sqlWODT)->result();
				foreach($resWODT as $rowWODT) :
					$WO_STARTD	= $rowWODT->WO_STARTD;
					$WO_ENDD	= $rowWODT->WO_ENDD;
				endforeach;
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				
				$WO_CATEGD		= '';
				if($WO_CATEG == 'SALT')
					$WO_CATEGD	= "Alat / Tools";
				elseif($WO_CATEG == 'SUB')
					$WO_CATEGD	= "Subkon";
				else
					$WO_CATEGD	= "Mandor";
				
				$secUpd			= site_url('c_project/c_o180d0bpnm/uinb1A1/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b4/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint3		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b43/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$CollID			= "$OPNH_NUM~$PRJCODE";
				$secDel_OPN 	= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id='.$OPNH_NUM;
                                    
				if($OPNH_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' style='display: none'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_OPN."')' title='Delete file' class='btn btn-danger btn-xs' style='display:none'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' style='display: none'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_OPN."')' title='Delete file' class='btn btn-danger btn-xs'  style='display:none' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN242';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$OPNH_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<div><strong>".$OPNH_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$OPNH_DATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i>$Name ($WO_CATEGD)</strong>
										  		<div style='white-space:nowrap'>
											  		<p class='text-muted' style='margin-left: 17px; white-space:nowrap'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div><strong>".$WO_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$WO_STARTDV." - ".$WO_ENDDV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  			$JOBDESC - $OPNH_NOTE
										  	</div>",
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
	
	function uinb1A1()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN242';
			$MenuCode			= 'MN242';
			$data["MenuCode"] 	= 'MN242';
			$data["MenuApp"] 	= 'MN242';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		
		$OPNH_NUM	= $_GET['id'];
		$OPNH_NUM	= $this->url_encryption_helper->decode_url($OPNH_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_o180d0bpnm/update_process_inb');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
									
			$getOPN 						= $this->m_opname->get_opn_by_number($OPNH_NUM)->row();
			$data['default']['OPNH_NUM'] 	= $getOPN->OPNH_NUM;
			$data['default']['OPNH_CODE'] 	= $getOPN->OPNH_CODE;
			$data['default']['OPNH_DATE'] 	= $getOPN->OPNH_DATE;
			$data['default']['OPNH_DATESP'] = $getOPN->OPNH_DATESP;
			$data['default']['OPNH_DATEEP'] = $getOPN->OPNH_DATEEP;
			$data['default']['PRJCODE']		= $getOPN->PRJCODE;
			$data['PRJCODE']				= $getOPN->PRJCODE;
			$PRJCODE 						= $getOPN->PRJCODE;
			$WO_CATEG 						= $getOPN->WO_CATEG;
			$data['default']['SPLCODE'] 	= $getOPN->SPLCODE;
			$data['default']['JOBCODEID'] 	= $getOPN->JOBCODEID;
			$data['default']['OPNH_NOTE'] 	= $getOPN->OPNH_NOTE;
			$data['default']['OPNH_NOTE2'] 	= $getOPN->OPNH_NOTE2;
			$data['default']['OPNH_STAT'] 	= $getOPN->OPNH_STAT;
			$data['default']['WO_NUM'] 		= $getOPN->WO_NUM;
			$data['default']['WO_CODE'] 	= $getOPN->WO_CODE;
			$data['default']['OPNH_MEMO'] 	= $getOPN->OPNH_MEMO;
			$data['default']['OPNH_AMOUNT'] = $getOPN->OPNH_AMOUNT;
			$data['default']['OPNH_AMOUNTPPNP'] = $getOPN->OPNH_AMOUNTPPNP;
			$data['default']['OPNH_AMOUNTPPN'] = $getOPN->OPNH_AMOUNTPPN;
			$data['default']['OPNH_AMOUNTPPHP'] = $getOPN->OPNH_AMOUNTPPHP;
			$data['default']['OPNH_AMOUNTPPH'] = $getOPN->OPNH_AMOUNTPPH;
			$data['default']['OPNH_RETPERC']= $getOPN->OPNH_RETPERC;
			$data['default']['OPNH_RETAMN'] = $getOPN->OPNH_RETAMN;
			$data['default']['OPNH_RETNO'] = $getOPN->OPNH_RETNO;
			$data['default']['PRJNAME'] 	= $getOPN->PRJNAME;
			$data['default']['OPNH_DPPER'] 	= $getOPN->OPNH_DPPER;
			$data['default']['OPNH_DPVAL'] 	= $getOPN->OPNH_DPVAL;
			$data['default']['OPNH_POT'] 	= $getOPN->OPNH_POT;
			$data['default']['OPNH_POTREF'] = $getOPN->OPNH_POTREF;
			$data['default']['OPNH_POTREF1'] = $getOPN->OPNH_POTREF1;
			$data['default']['OPNH_POTACCID'] = $getOPN->OPNH_POTACCID;
			$data['default']['Patt_Year'] 	= $getOPN->Patt_Year;
			$data['default']['Patt_Month'] 	= $getOPN->Patt_Month;
			$data['default']['Patt_Date'] 	= $getOPN->Patt_Date;
			$data['default']['Patt_Number']	= $getOPN->Patt_Number;
			
			$MenuCode 			= 'MN242';
			$data["MenuCode"] 	= 'MN242';
			if($WO_CATEG == 'T')
			{
				// GET MENU DESC
					$mnCode				= 'MN471';
					$MenuCode			= 'MN471';
					$data["MenuCode"] 	= 'MN471';
					$data["MenuApp"] 	= 'MN471';
					$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
					$getMN				= $this->m_updash->get_menunm($mnCode)->row();
					if($this->data['LangID'] == 'IND')
						$data["mnName"] = $getMN->menu_name_IND;
					else
						$data["mnName"] = $getMN->menu_name_ENG;
			}
			$data['vwDocPatt'] 	= $this->m_opname->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getOPN->OPNH_NUM;
				$MenuCode 		= $MenuCode;
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
			
			$this->load->view('v_project/v_opname/opname_inb_form', $data);
			//$this->load->view('dashboard1_uc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();

				date_default_timezone_set("Asia/Jakarta");
							
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
				$OPNH_STAT 		= $this->input->post('OPNH_STAT');
				
				$WO_NUM 		= $this->input->post('WO_NUM');
				$OPNH_NUM 		= $this->input->post('OPNH_NUM');
				$OPNH_CODE 		= $this->input->post('OPNH_CODE');
				
				$OPNH_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				
				$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$OPNH_DATESP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATESP'))));
				$OPNH_DATEEP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATEEP'))));
				$PRJCODE 		= $this->input->post('PRJCODE');
				$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
				$SPLCODE 		= $this->input->post('SPLCODE');
				$JOBCODEID		= $this->input->post('JOBCODEID');
				$WO_NUM 		= $this->input->post('WO_NUM');
				$WO_CODE 		= $this->input->post('WO_CODE');
				$OPNH_STAT 		= $this->input->post('OPNH_STAT');
				$OPNH_NOTE 		= htmlspecialchars($this->input->post('OPNH_NOTE'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				$OPNH_NOTE2		= htmlspecialchars($this->input->post('OPNH_NOTE2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				$OPNH_AMOUNT	= $this->input->post('OPNH_AMOUNT');
				$OPNH_AMOUNTPPNP= $this->input->post('OPNH_AMOUNTPPNP');
				$OPNH_AMOUNTPPN	= $this->input->post('OPNH_AMOUNTPPN');
				$OPNH_AMOUNTPPHP= $this->input->post('OPNH_AMOUNTPPHP');
				$OPNH_AMOUNTPPH	= $this->input->post('OPNH_AMOUNTPPH');
				$OPNH_RETPERC	= $this->input->post('OPNH_RETPERC');
				$OPNH_RETAMN	= $this->input->post('OPNH_RETAMN');
				$OPNH_RETNO		= $this->input->post('OPNH_RETNO');
				$OPNH_DPPER		= $this->input->post('OPNH_DPPER');
				$OPNH_DPVAL		= $this->input->post('OPNH_DPVAL');
				$OPNH_POT		= $this->input->post('OPNH_POT');
				$OPNH_POTREF	= $this->input->post('OPNH_POTREF');
				$OPNH_POTREF1	= $this->input->post('OPNH_POTREF1');
				$OPNH_POTACCID	= $this->input->post('OPNH_POTACCID');
				$TAXCODE_PPN	= $this->input->post('TAXCODE_PPN');
				$TAXCODE_PPH	= $this->input->post('TAXCODE_PPH');
				$Patt_Number	= $this->input->post('Patt_Number');
				
				$WO_CATEG	= '';
				$sqlSPK 	= "SELECT WO_CATEG FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resSPK 	= $this->db->query($sqlSPK)->result();
				foreach($resSPK as $rowSPK) :
					$WO_CATEG = $rowSPK->WO_CATEG;		
				endforeach;

				$mnAppCode 		= "MN242";
				
				//GET SUPPLIER CATEG
					$SPLCAT		= '';
					$SPLDESC 	= '';
					$sqlSPLC	= "SELECT SPLCAT, SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
					$resSPLC	= $this->db->query($sqlSPLC)->result();
					foreach($resSPLC as $rowSPLC) :
						$SPLCAT 	= $rowSPLC->SPLCAT;
						$SPLDESC 	= $rowSPLC->SPLDESC;
					endforeach;
				
				// START : SETTING L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $OPNH_DATE;
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

				// UPDATE JOBDETAIL ITEM
				if($OPNH_STAT == 3)
				{
					// START : SAVE APPROVE HISTORY
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$AH_CODE		= $OPNH_NUM;
						$AH_APPLEV		= $this->input->post('APP_LEVEL');
						$AH_APPROVER	= $DefEmp_ID;
						$AH_APPROVED	= date('Y-m-d H:i:s');
						$AH_NOTES		= htmlspecialchars($this->input->post('OPNH_NOTE2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
						$AH_ISLAST		= $this->input->post('IS_LAST');
						
						$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
												'AH_APPLEV'		=> $AH_APPLEV,
												'AH_APPROVER'	=> $AH_APPROVER,
												'AH_APPROVED'	=> $AH_APPROVED,
												'AH_NOTES'		=> $AH_NOTES,
												'AH_ISLAST'		=> $AH_ISLAST);										
						$this->m_updash->insAppHist($insAppHist);
					
						$projOPH		= array('OPNH_STAT'		=> 7,
												'TAXCODE_PPN'	=> $TAXCODE_PPN,
												'TAXCODE_PPH'	=> $TAXCODE_PPH,
												'OPNH_NOTE2'	=> $OPNH_NOTE2);	// Default ke waiting jika masih ada approver yang lain
						$this->m_opname->update($OPNH_NUM, $projOPH);
					// END : SAVE APPROVE HISTORY
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
												'DOC_CODE' 		=> $OPNH_NUM,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_opn_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					if($AH_ISLAST == 1)
					{
						$projWOH 		= array('OPNH_APPROVER'	=> $DefEmp_ID,
												'OPNH_APPROVED'	=> date('Y-m-d H:i:s'),
												'OPNH_NOTE2'	=> $AH_NOTES,
												'OPNH_STAT'		=> $OPNH_STAT);										
						$this->m_opname->update($OPNH_NUM, $projWOH);
									
						//$this->m_opname->updateWODet($OPNH_NUM, $WO_NUM, $PRJCODE);
						
						// START : JOURNAL HEADER
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$JournalH_Code	= $OPNH_NUM;
							$JournalType	= 'OPN';
							$JournalH_Date	= $OPNH_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $OPNH_NUM;
							$LastUpdate		= date('Y-m-d H:i:s');;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= '';
							$RefType		= 'WBSD';
							$PRJCODE		= $PRJCODE;
							$Manual_No 		= "J".$OPNH_CODE;
							$PERSL_EMPID 	= $SPLCODE;
							$SPLCODE 		= $SPLCODE;
							$SPLDESC	 	= $this->db->get_where("tbl_supplier", ["SPLCODE" => $SPLCODE])->row("SPLDESC");
							$SPLDESC 		= $this->db->escape_str($SPLDESC);
							
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
												'Manual_No'			=> $Manual_No,
												'PPNH_Amount' 		=> $OPNH_AMOUNTPPN,
												'PPHH_Amount'		=> $OPNH_AMOUNTPPH,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);

							$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
						// END : JOURNAL HEADER
				
						// 001. START : JOURNAL DETAIL - COGS (DEBIT)
							$TOTALD		= 0;
							$TOTPPHNFIN = 0;
							foreach($_POST['data'] as $d)
							{
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $d['ITM_CODE'];
								$JOBCODEID 		= $d['JOBCODEID'];
								$ACC_ID 		= $d['ACC_ID_UM'];
								$ITM_UNIT 		= $d['ITM_UNIT'];
								$ITM_QTY 		= $d['OPND_VOLM'];
								$ITM_PRICE 		= $d['OPND_ITMPRICE'];
								$ITM_DISC 		= 0;
								$TAXCODE1 		= $d['TAXCODE1'];
								$TAXPRICE1 		= $d['TAXPRICE1'];
								$TAXCODE2 		= $d['TAXCODE2'];
								$TAXPRICE2 		= $d['TAXPRICE2'];
								$ITM_GROUP 		= $d['ITM_GROUP'];
								$OPND_DESC		= $this->db->escape_str($d['OPND_DESC']);
								$Notes 			= $this->db->escape_str($d['OPND_DESC']);
								$OPNVAL			= $ITM_QTY * $ITM_PRICE;
								$TOTALD			= $TOTALD + $OPNVAL;

								// START : PEMBENTUKAN JURNAL PPH JIKA ADA PPH NON-FINAL (SESUAI HASIL DISKUSI DENGAN BU LYTA TGL 21 OKT. 2022)
									// HASIL DISKUSI DENGAN BU LYTA TGL 21 OKT. 2022 BAHWA PPH NON-FINAL DIBENTUK SAAT PEMBENTUKAN JURNAL HUTANG
									// ARTINYA APABILA HUTANG TERBENTUK SAAT OPNAME, MAKA PPH NON-FINAL JUGA TERBENTUK SAAT OPNAME

									// HASIL DISKUSI DENGAN BU LYTA DNA PAK DEDE TGL. 25 OKT. 22
									// 1. PPH YANG DIPILIH PADA SAAT SPK, HARUS PPH FINAL SEHINGGA TIDAK ADA PEMOTONGAN HUTANG OLEH PPH
									// 2. TIDAK AKAN ADA PEMBENTUKAN JURNAL PPH BAIK SAAT OPNAME MAUPUN SAAT VOUCHER
									
									/*221025 if($TAXPRICE2 > 0)
									{
										$isPPhFin 		= 0;
										$sqlISHO 		= "SELECT isHO, syncPRJ, Account_Number, Account_NameId AS Acc_Name, isPPhFinal FROM tbl_chartaccount_$PRJCODEVW
															WHERE PRJCODE = '$PRJCODE' AND Account_Number IN (SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE2') LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isPPhFin	= $rowISHO->isPPhFinal;
											if($isPPhFin == 0)
											{
												$TOTPPHNFIN = $TOTPPHNFIN+$TAXPRICE2;
											}
										endforeach;
									}*/
								// END
								
								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'OPN';
								$JSource		= 'OPN';
								$PRJCODE		= $PRJCODE;
								
								$TRANS_CATEG 	= "OPN~$SPLCAT";
								
								$parameters = array('JournalH_Code' => $JournalH_Code,
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN = Opname
												'WO_CATEG'			=> $WO_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> '',
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'TAXCODE2' 			=> $TAXCODE2,
												'TAXPRICE2' 		=> $TAXPRICE2,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC,
												'Notes' 			=> "$SPLDESC. Opn. : $OPNH_CODE _ $WO_CODE. $OPND_DESC",
												'JOBCODEID' 		=> $JOBCODEID);

								$this->m_journal->createJournalD($JournalH_Code, $parameters);

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

									$this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
								// END : UPDATE PROFIT AND LOSS
								
								// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
								// DI HOLDED KARENA AKAN DILAKUKAN DISAAT TTK
									$this->load->model('m_updash/m_updash', '', TRUE);
									$paramFT = array('DOC_NUM' 		=> $OPNH_NUM,
													'DOC_DATE' 		=> $OPNH_DATE,
													'DOC_EDATE' 	=> $OPNH_DATE,
													'PRJCODE' 		=> $PRJCODE,
													'FIELD_NAME1' 	=> 'FT_COP',
													'FIELD_NAME2' 	=> 'FM_COP',
													'TOT_AMOUNT'	=> $ITM_PRICE);
									//$this->m_updash->finTrack($paramFT); HOLDED ON 11 JAN 19
								// END : TRACK FINANCIAL TRACK
							}
							$this->m_opname->updateWODet($OPNH_NUM, $WO_NUM, $PRJCODE);

						// START : JOURNAL DETAIL - HUTANG (KREDIT)
							// SISA DARI TOTAL SISI DEBET DIPOTONG DENGAN POTONGAN LAINNYA (KREDIT) ---> POINT 001 - 004
								$TOTALK	= 0;
								if($TOTALD > 0)
								{
									//$TOTALK	= $TOTALD - $OPNH_POT;
									//$TOTALK	= $TOTALD - $OPNH_RETAMN - $TOTPPHNFIN;
									$TOTALK	= $TOTALD - $OPNH_RETAMN;
								}
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= '';
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								/*$ITM_PRICE 		= $OPNH_AMOUNT;
								$ITM_DISC 		= $OPNH_POT;*/
								$ITM_PRICE 		= $TOTALK;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= $OPNH_AMOUNTPPN;
								
								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN2';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'OPN2';
								$JSource		= 'OPN2';
								$PRJCODE		= $PRJCODE;
								
								$TRANS_CATEG 	= "OPN2";
													
								$parameters = array('JournalH_Code' => $JournalH_Code,
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN = Opname hutang lain-lain
												'WO_CATEG'			=> $WO_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> '',
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC,
												'Other_Desc'		=> "$SPLDESC. $OPNH_NOTE $OPNH_CODE - $WO_CODE");

								$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL - HUTANG BELUM DIFAKTUR (KREDIT)

						// 002. START : JOURNAL DETAIL - PENGEMBALIAN DP (KREDIT) 	HOLDED ON 27 DES. 2018
						// UPDATE 21-10-31 : TIDAK ADA PENJURNALAN PENGEMBALIAN DP. PENJURNALAN DILAKUKAN PD SAAT INVOICING
							/*if($OPNH_DPVAL > 0)
							{
								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN3';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $WO_NUM;
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'WO';
								$JSource		= 'OPN3';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Pengembalian DP";
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= '';
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $OPNH_DPVAL;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "OPN3~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN3 = Pengembalian DP
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);
								$this->m_journal->createJournalD($JournalH_Code, $parameters); 	// DIHOLD ON 27 DES 2018
							}*/
						// END : JOURNAL DETAIL
						
						// 003. START : JOURNAL DETAIL - RETENSI (KREDIT)
						// HASIL MEETING DGN PAK DUA & ES, JURNAL RETENSI TERBENTUK SAAT OPNAME
							if($OPNH_RETAMN > 0)
							{
								// membuat dokumen opn retensi
								// START : MEMBUAT DOK. OPNAMA RETENSI
									$OPNH_CODERET 	= $OPNH_CODE."-RET";
									$projOPNHRET 	= array('OPNH_NUM' 			=> $OPNH_NUM,
															'OPNH_CODE' 		=> $OPNH_CODERET,
															'OPNH_DATE'			=> $OPNH_DATE,
															'OPNH_DATESP'		=> $OPNH_DATESP,
															'OPNH_DATEEP' 		=> $OPNH_DATEEP,
															'PRJCODE'			=> $PRJCODE,
															'SPLCODE'			=> $SPLCODE,
															'JOBCODEID'			=> "",
															'WO_NUM'			=> $WO_NUM,
															'WO_CODE'			=> $WO_CODE,
															'OPNH_AMOUNT'		=> $OPNH_RETAMN,
															'OPNH_AMOUNTPPNP'	=> 0,
															'OPNH_AMOUNTPPN'	=> 0,
															'OPNH_AMOUNTPPHP'	=> 0,
															'OPNH_AMOUNTPPH'	=> 0,
															'OPNH_RETPERC'		=> $OPNH_RETPERC,
															'OPNH_RETAMN'		=> 0,
															'OPNH_RETNO' 		=> $OPNH_RETNO,
															'OPNH_NOTE'			=> "(Retensi) : $OPNH_NOTE. $OPNH_NOTE2",
															'OPNH_NOTE2'		=> $OPNH_NOTE2,
															'OPNH_DPPER'		=> 0,
															'OPNH_DPVAL'		=> 0,
															'OPNH_POT'			=> 0,
															'OPNH_POTREF'		=> $OPNH_POTREF,
															'OPNH_POTREF1'		=> $OPNH_POTREF1,
															'OPNH_POTACCID'		=> "",
															'OPNH_STAT'			=> $OPNH_STAT,
															'OPNH_CREATER'		=> $DefEmp_ID,
															'OPNH_CREATED'		=> date('Y-m-d H:i:s'),
															'OPNH_APPROVER'		=> $DefEmp_ID,
															'OPNH_APPROVED' 	=> date('Y-m-d H:i:s'),
															'OPNH_TYPE'			=> 1,
															'Patt_Year'			=> $Patt_Year, 
															'Patt_Month'		=> $Patt_Month,
															'Patt_Date'			=> $Patt_Date,
															'Patt_Number'		=> $Patt_Number,
															'SPLCODE' 			=> $SPLCODE,
															'SPLDESC' 			=> $SPLDESC);

									$this->m_opname->add($projOPNHRET);
					
									// START : UPDATE STATUS
										$completeName 	= $this->session->userdata['completeName'];
										$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
																'DOC_CODE' 		=> $OPNH_NUM,
																'DOC_STAT' 		=> $OPNH_STAT,
																'PRJCODE' 		=> $PRJCODE,
																'CREATERNM'		=> $completeName,
																'TBLNAME'		=> "tbl_opn_header");
										$this->m_updash->updateStatus($paramStat);
									// END : UPDATE STATUS
								// END : MEMBUAT DOK. OPNAMA RETENSI

								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN-RET';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $WO_NUM;
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'WO';
								$JSource		= 'OPN-RET';
								$PRJCODE		= $PRJCODE;
								$Notes			= "RETENSI $OPNH_NOTE. $SPLDESC ($OPNH_CODE - $WO_CODE)";
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= '';
								$ITM_UNIT 		= 'Opname';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $OPNH_RETAMN;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "OPN-RET~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
								
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
													'ITM_CODE' 			=> "",
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes,
													'SPLCODE' 			=> $SPLCODE,
													'SPLDESC' 			=> $SPLDESC,
													'Other_Desc'		=> "$SPLDESC. (Retensi) $OPNH_NOTE $OPNH_CODE - $WO_CODE");

								if($WO_CATEG != 'T')
									$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
						// END : JOURNAL DETAIL - RETENSI (KREDIT)

						// 004. START : JOURNAL DETAIL - POTONGAN LAINNYA (KREDIT)
						// UPDATE 21-10-31 : TIDAK ADA PENJURNALAN PENGEMBALIAN DP. PENJURNALAN DILAKUKAN PD SAAT INVOICING
							/*if($OPNH_POT > 0)
							{
								// DAPATKAN AKUN SAAT JURNAL PIUTANG MELALUI GEJ
								// AKUNNYA ITU ADALAH OPNH_POTACCID
									
								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN5';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $WO_NUM;
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'WO';
								$JSource		= 'OPN5';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Pengembalian DP";
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= $OPNH_POTACCID;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $OPNH_POT;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "OPN5~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN5 = Potongan Lainnya
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);
								$this->m_journal->createJournalD($JournalH_Code, $parameters);
							}*/
						// END : JOURNAL DETAIL
						
						// START : UPDATE STAT DET
							$this->load->model('m_updash/m_updash', '', TRUE);				
							$paramSTAT 	= array('JournalHCode' 	=> $OPNH_NUM);
							$this->m_updash->updSTATJD($paramSTAT);
						// END : UPDATE STAT DET
					
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
													'DOC_CODE' 		=> $OPNH_NUM,
													'DOC_STAT' 		=> $OPNH_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_opn_header");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
					/*else
					{*/
						// START : CREATE ALERT LIST
							$APP_LEVEL 	= $this->input->post('APP_LEVEL');
							$alertVar 	= array('PRJCODE'		=> $PRJCODE,
												'AS_CATEG'		=> "OPN",
												'AS_MNCODE'		=> $mnAppCode,
												'AS_DOCNUM'		=> $OPNH_NUM,
												'AS_DOCCODE'	=> $OPNH_CODE,
												'AS_DOCDATE'	=> $OPNH_DATESP,
												'AS_EXPDATE'	=> $OPNH_DATEEP,
												'APP_LEVEL'		=> $APP_LEVEL);
						$this->m_updash->updAALERT($alertVar);
						// END : CREATE ALERT LIST
					//}
				}
				else if($OPNH_STAT == 4)
				{
					// CLEAR APROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					$this->m_updash->delAppHist($OPNH_NUM);
					
					$projOPH		= array('OPNH_STAT'		=> $OPNH_STAT,
											'OPNH_NOTE2'	=> $OPNH_NOTE2);	// Default ke waiting jika masih ada approver yang lain
					$this->m_opname->update($OPNH_NUM, $projOPH);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
												'DOC_CODE' 		=> $OPNH_NUM,
												'DOC_STAT' 		=> $OPNH_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_opn_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				else // IF STAT IN (5,6)
				{
					$projOPH		= array('OPNH_STAT'		=> $OPNH_STAT);	// Default ke waiting jika masih ada approver yang lain
					$this->m_opname->update($OPNH_NUM, $projOPH);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
												'DOC_CODE' 		=> $OPNH_NUM,
												'DOC_STAT' 		=> $OPNH_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_opn_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				
				$updDesc	= "UPDATE tbl_journalheader A, tbl_opn_header B SET A.JournalH_Desc = B.OPNH_NOTE
								WHERE A.JournalH_Code = B.OPNH_NUM AND A.JournalH_Code = '$OPNH_NUM'";
				$this->db->query($updDesc);
				
				// CHECK WO TO CLOSE
				$this->m_opname->updateWOH($WO_NUM, $PRJCODE);
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');	// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 	=> $OPNH_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "OPN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_opn_header",// TABLE NAME
										'KEY_NAME'		=> "OPNH_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "OPNH_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $OPNH_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_OPN",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_OPN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_OPN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_OPN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_OPN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_OPN_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_OPN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
					
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $WO_NUM;
					$MenuCode 		= $mnAppCode;
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

					$parameters 	= array('TR_TYPE'		=> "OPN",
											'TR_DATE' 		=> $OPNH_DATE,
											'PRJCODE'		=> $PRJCODE);
					$this->m_updash->updateQtyColl($parameters);
				// END : UPDATE QTY_COLLECTIVE

				// START : UPDATE ITEM_LOG
					$parIL 	= array('DOC_CATEG'		=> "OPN",
									'DOC_NUM'		=> $OPNH_NUM,
									'DOC_STAT'		=> $OPNH_STAT,
									'PRJCODE'		=> $PRJCODE);
					$this->m_updash->updIL($parIL);
				// END : UPDATE ITEM_LOG

			// START : ADD DOC HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				date_default_timezone_set("Asia/Jakarta");
				$paramTrack 	= array('REF_NUM' 		=> $OPNH_NUM,
										'TBLNAME' 		=> "tbl_opn_header",
										'FLDCODE'		=> "OPNH_CODE",
										'FLDNAME'		=> "OPNH_NUM",
										'HISTTYPE'		=> "Update approve ($OPNH_STAT)");
				$this->m_updash->uDocH($paramTrack);
			// END : ADD DOC HISTORY

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_o180d0bpnm/inb1a1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pr1n7d0c_b4()
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 			= $therow->app_name;
			$data['comp_add']	= $therow->comp_add;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$OPNH_NUM	= $_GET['id'];
		$OPNH_NUM	= $this->url_encryption_helper->decode_url($OPNH_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 					= $appName;
			$getwodata 						= $this->m_opname->get_opnprint_by_number($OPNH_NUM)->row();
			$data['default']['OPNH_NUM'] 	= $OPNH_NUM;
			$data['default']['OPNH_CODE']	= $getwodata->OPNH_CODE;
			$data['default']['OPNH_DATE'] 	= $getwodata->OPNH_DATE;
			$data['default']['OPNH_DATESP'] = $getwodata->OPNH_DATESP;
			$data['default']['OPNH_DATEEP'] = $getwodata->OPNH_DATEEP;
			$data['default']['OPNH_TYPE'] 	= $getwodata->OPNH_TYPE;
			$data['default']['OPNH_NOTE'] 	= $getwodata->OPNH_NOTE;
			$data['default']['OPNH_STAT'] 	= $getwodata->OPNH_STAT;
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG 						= $getwodata->WO_CATEG;
			$data["WO_CATEG"] 				= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$data['countopndet'] = $this->m_opname->count_OPNDET_by_number($OPNH_NUM);
	      	$data['vopndet']   = $this->m_opname->get_OPNDET_by_number($OPNH_NUM);
			
			$MenuCode 			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data['vwDocPatt'] 	= $this->m_opname->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $OPNH_NUM;
				$MenuCode 		= 'MN241';
				$TTR_CATEG		= 'PRINT';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			//$this->load->view('v_project/v_opname/opname_printdoc', $data);
			$this->load->view('v_project/v_opname/opname_printdoc_adm', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function pr1n7d0c_b43()
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$OPNH_NUM	= $_GET['id'];
		$OPNH_NUM	= $this->url_encryption_helper->decode_url($OPNH_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$getwodata 						= $this->m_opname->get_opnprint_by_number($OPNH_NUM)->row();
			$data['default']['OPNH_NUM'] 	= $OPNH_NUM;
			$data['default']['WO_NUM'] 		= $getwodata->WO_NUM;
			$data['default']['WO_CODE'] 	= $getwodata->WO_CODE;
			$data['default']['WO_DATE'] 	= $getwodata->WO_DATE;
			$data['default']['WO_STARTD'] 	= $getwodata->WO_STARTD;
			$data['default']['WO_ENDD'] 	= $getwodata->WO_ENDD;
			$data['default']['PRJCODE']		= $getwodata->PRJCODE;
			$data['PRJCODE']				= $getwodata->PRJCODE;
			$PRJCODE 						= $getwodata->PRJCODE;
			$data['default']['SPLCODE'] 	= $getwodata->SPLCODE;
			$data['default']['WO_DEPT'] 	= $getwodata->WO_DEPT;
			$data['default']['WO_CATEG'] 	= $getwodata->WO_CATEG;
			$WO_CATEG 						= $getwodata->WO_CATEG;
			$data["WO_CATEG"] 				= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;
			
			$MenuCode 			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			$data['vwDocPatt'] 	= $this->m_opname->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $OPNH_NUM;
				$MenuCode 		= 'MN241';
				$TTR_CATEG		= 'PRINT';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_opname/opname_printdoc3', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function cRI180d0eNV()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$getOPN 						= $this->m_opname->get_opn_by_number($WO_NUM)->row();
			$data['default']['OPNH_NUM'] 	= $getOPN->OPNH_NUM;
			$data['default']['OPNH_CODE'] 	= $getOPN->OPNH_CODE;
			$data['default']['OPNH_DATE'] 	= $getOPN->OPNH_DATE;
			$data['default']['PRJCODE']		= $getOPN->PRJCODE;
			$data['PRJCODE']				= $getOPN->PRJCODE;
			$PRJCODE 						= $getOPN->PRJCODE;
			$data['default']['SPLCODE'] 	= $getOPN->SPLCODE;
			$data['default']['JOBCODEID'] 	= $getOPN->JOBCODEID;
			$data['default']['OPNH_NOTE'] 	= $getOPN->OPNH_NOTE;
			$data['default']['OPNH_NOTE2'] 	= $getOPN->OPNH_NOTE2;
			$data['default']['OPNH_STAT'] 	= $getOPN->OPNH_STAT;
			$data['default']['WO_NUM'] 		= $getOPN->WO_NUM;
			$data['default']['OPNH_MEMO'] 	= $getOPN->OPNH_MEMO;
			$data['default']['PRJNAME'] 	= $getOPN->PRJNAME;
			
			$MenuCode 			= 'MN241';
			$data["MenuCode"] 	= 'MN241';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getOPN->OPNH_NUM;
				$MenuCode 		= 'MN241';
				$TTR_CATEG		= 'P';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_project/v_opname/opname_inv_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function ll_4p() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PRJCODE	= $_GET['id'];
			$SPLCODE	= $_GET['SPLCODE'];
			$collData	= "$PRJCODE~$SPLCODE";
			
			$url		= site_url('c_project/C_o180d0bpnm/ll_4p1/?id='.$this->url_encryption_helper->encode_url($collData));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function ll_4p1() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		if ($this->session->userdata('login') == TRUE)
		{	
			$PRJCODE				= $_GET['id'];
			$collData				= $this->url_encryption_helper->decode_url($PRJCODE);
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
			$DATExplode 			= explode('~', $collData);
			$PRJCODE				= $DATExplode[0];
			$SPLCODE				= $DATExplode[1];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Number';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'OPN';
			$data['PRJCODE']		= $PRJCODE;
			$data['SPLCODE']		= $SPLCODE;
			
			$this->load->view('v_project/v_opname/opname_selpinj', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function trashFile()
	{
		$OPNH_NUM	= $this->input->post("OPNH_NUM");
		$PRJCODE	= $this->input->post("PRJCODE");
		// $index 		= $this->input->post("indexRow");
		$fileName	= $this->input->post("fileName");

		/* Change OPNH_DOC to array => upd: 2022-09-16
			$arrDOC = explode(", ", $OPNH_DOC);
			foreach($arrDOC as $r => $val):
				$getarr[] = $val;
			endforeach;

			unset($getarr[$index]); // Delete index array
		---------------------- End Hidden --------------------- */
		
		$this->m_opname->delUPL_DOC($OPNH_NUM, $PRJCODE, $fileName); // Delete File
		
		// Delete file in path folder
			$path = "assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/$fileName";
			unlink($path);
	}

	function downloadFile()
	{
		$this->load->helper('download');

		$fileName 	= $_GET['file'];
		$PRJCODE 	= $_GET['prjCode'];
		$path 		= "assets/AdminLTE-2.0.5/doc_center/uploads/OPN_Document/$PRJCODE/$fileName";
		force_download($path, NULL);
	}
	
 	function inbox_int() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_o180d0bpnm/pR7_l5t_x1_int/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pR7_l5t_x1_int() // G
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
				$mnCode				= 'MN472';
				$MenuCode			= 'MN472';
				$data["MenuCode"] 	= 'MN472';
				$data["MenuApp"] 	= 'MN472';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN472';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_project/c_o180d0bpnm/inb1a1_int/?id=";
			
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
	
	function inb1a1_int() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN472';
			$MenuCode			= 'MN472';
			$data["MenuCode"] 	= 'MN472';
			$data["MenuApp"] 	= 'MN472';
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
			
				$num_rows 			= $this->m_opname->count_all_OPNInx($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_opname->get_all_OPNInb($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_o180d0bpnm/inbox_int/');
			$data['PRJCODE'] 	= $PRJCODE;
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN472';
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
			
			$this->load->view('v_project/v_opname/int_opname_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n2_int() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
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
			
			$columns_valid 	= array("OPNH_DATE",
									"OPNH_DATE",
									"WO_CODE", 
									"OPNH_NOTE", 
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
			$num_rows 		= $this->m_opname->get_AllDataC_1n2_int($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_opname->get_AllDataL_1n2_int($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{							
				$OPNH_NUM		= $dataI['OPNH_NUM'];
				$OPNH_CODE		= $dataI['OPNH_CODE'];
				$OPNH_DATE		= $dataI['OPNH_DATE'];
				//$OPNH_DATEV		= date('d M Y', strtotime($OPNH_DATE));
				$OPNH_DATEV		= strftime('%d %b %Y', strtotime($OPNH_DATE));
				$PRJCODE		= $dataI['PRJCODE'];
				$SPLCODE		= $dataI['SPLCODE'];
				$OPNH_NOTE		= $dataI['OPNH_NOTE'];
				$OPNH_STAT		= $dataI['OPNH_STAT'];
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$OPNH_CREATER	= $dataI['OPNH_CREATER'];
				$OPNH_ISCLOSE	= $dataI['OPNH_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
					
				$splitJOB 		= explode("~", $JOBCODEID);
				$JOBCODEID		= $splitJOB[0];
				$JOBDESC		= '';
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESC	= $rowJOBDESC->JOBDESC;
				endforeach;
				if($JOBDESC == '')
					$JOBDESC	= $OPNH_NOTE;
					
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$WO_CATEG		= $dataI['WO_CATEG'];
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$WO_STARTD		= '';
				$WO_ENDD		= '';
				$sqlWODT		= "SELECT WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resWODT		= $this->db->query($sqlWODT)->result();
				foreach($resWODT as $rowWODT) :
					$WO_STARTD	= $rowWODT->WO_STARTD;
					$WO_ENDD	= $rowWODT->WO_ENDD;
				endforeach;
				$WO_STARTDV		= strftime('%d %b %Y', strtotime($WO_STARTD));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));
				
				$WO_CATEGD		= 'Sewa Alat Internal';
				/*if($WO_CATEG == 'SALT')
					$WO_CATEGD	= "Alat / Tools";
				elseif($WO_CATEG == 'SUB')
					$WO_CATEGD	= "Subkon";
				else
					$WO_CATEGD	= "Mandor";*/
				
				$secUpd			= site_url('c_project/c_o180d0bpnm/uinb1A1_int/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b4/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$secPrint3		= site_url('c_project/c_o180d0bpnm/pr1n7d0c_b43/?id='.$this->url_encryption_helper->encode_url($OPNH_NUM));
				$CollID			= "$OPNH_NUM~$PRJCODE";
				$secDel_OPN 	= base_url().'index.php/c_project/c_o180d0bpnm/trash_OPN/?id='.$OPNH_NUM;
                                    
				if($OPNH_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' style='display: none'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_OPN."')' title='Delete file' class='btn btn-danger btn-xs' style='display:none'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}                                            
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <input type='hidden' name='urlPrint3".$noU."' id='urlPrint3".$noU."' value='".$secPrint3."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' style='display: none'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-success btn-xs' onClick='printD3(".$noU.")'>
										<i class='glyphicon glyphicon-new-window'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_OPN."')' title='Delete file' class='btn btn-danger btn-xs'  style='display:none' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN472';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$OPNH_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>
											  	<div><strong>".$OPNH_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$OPNH_DATEV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i>$Name ($WO_CATEGD)</strong>
										  		<div style='white-space:nowrap'>
											  		<p class='text-muted' style='margin-left: 17px; white-space:nowrap'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
										  	</div>",
										  	"<div style='white-space:nowrap'>
											  	<div><strong>".$WO_CODE." </strong><div>
											  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 20px'>
											  			".$WO_STARTDV." - ".$WO_ENDDV."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  			$JOBDESC - $OPNH_NOTE
										  	</div>",
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
	
	function uinb1A1_int()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN472';
			$MenuCode			= 'MN472';
			$data["MenuCode"] 	= 'MN472';
			$data["MenuApp"] 	= 'MN472';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		
		$OPNH_NUM	= $_GET['id'];
		$OPNH_NUM	= $this->url_encryption_helper->decode_url($OPNH_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_o180d0bpnm/update_process_inb_int');
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
									
			$getOPN 						= $this->m_opname->get_opn_by_number($OPNH_NUM)->row();
			$data['default']['OPNH_NUM'] 	= $getOPN->OPNH_NUM;
			$data['default']['OPNH_CODE'] 	= $getOPN->OPNH_CODE;
			$data['default']['OPNH_DATE'] 	= $getOPN->OPNH_DATE;
			$data['default']['OPNH_DATESP'] = $getOPN->OPNH_DATESP;
			$data['default']['OPNH_DATEEP'] = $getOPN->OPNH_DATEEP;
			$data['default']['PRJCODE']		= $getOPN->PRJCODE;
			$data['PRJCODE']				= $getOPN->PRJCODE;
			$PRJCODE 						= $getOPN->PRJCODE;
			$WO_CATEG 						= $getOPN->WO_CATEG;
			$data['default']['SPLCODE'] 	= $getOPN->SPLCODE;
			$data['default']['JOBCODEID'] 	= $getOPN->JOBCODEID;
			$data['default']['OPNH_NOTE'] 	= $getOPN->OPNH_NOTE;
			$data['default']['OPNH_NOTE2'] 	= $getOPN->OPNH_NOTE2;
			$data['default']['OPNH_STAT'] 	= $getOPN->OPNH_STAT;
			$data['default']['WO_NUM'] 		= $getOPN->WO_NUM;
			$data['default']['WO_CODE'] 	= $getOPN->WO_CODE;
			$data['default']['OPNH_MEMO'] 	= $getOPN->OPNH_MEMO;
			$data['default']['OPNH_AMOUNT'] = $getOPN->OPNH_AMOUNT;
			$data['default']['OPNH_AMOUNTPPNP'] = $getOPN->OPNH_AMOUNTPPNP;
			$data['default']['OPNH_AMOUNTPPN'] = $getOPN->OPNH_AMOUNTPPN;
			$data['default']['OPNH_AMOUNTPPHP'] = $getOPN->OPNH_AMOUNTPPHP;
			$data['default']['OPNH_AMOUNTPPH'] = $getOPN->OPNH_AMOUNTPPH;
			$data['default']['OPNH_RETPERC']= $getOPN->OPNH_RETPERC;
			$data['default']['OPNH_RETAMN'] = $getOPN->OPNH_RETAMN;
			$data['default']['OPNH_RETNO'] = $getOPN->OPNH_RETNO;
			$data['default']['PRJNAME'] 	= $getOPN->PRJNAME;
			$data['default']['OPNH_DPPER'] 	= $getOPN->OPNH_DPPER;
			$data['default']['OPNH_DPVAL'] 	= $getOPN->OPNH_DPVAL;
			$data['default']['OPNH_POT'] 	= $getOPN->OPNH_POT;
			$data['default']['OPNH_POTREF'] = $getOPN->OPNH_POTREF;
			$data['default']['OPNH_POTREF1'] = $getOPN->OPNH_POTREF1;
			$data['default']['OPNH_POTACCID'] = $getOPN->OPNH_POTACCID;
			$data['default']['Patt_Year'] 	= $getOPN->Patt_Year;
			$data['default']['Patt_Month'] 	= $getOPN->Patt_Month;
			$data['default']['Patt_Date'] 	= $getOPN->Patt_Date;
			$data['default']['Patt_Number']	= $getOPN->Patt_Number;

			// GET MENU DESC
				$mnCode				= 'MN472';
				$MenuCode			= 'MN472';
				$data["MenuCode"] 	= 'MN472';
				$data["MenuApp"] 	= 'MN472';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			$data['vwDocPatt'] 	= $this->m_opname->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getOPN->OPNH_NUM;
				$MenuCode 		= $MenuCode;
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
			
			$this->load->view('v_project/v_opname/opname_inb_form', $data);
			//$this->load->view('dashboard1_uc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb_int() // OK
	{
		$this->load->model('m_project/m_opname/m_opname', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$comp_init 	= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();

				date_default_timezone_set("Asia/Jakarta");
							
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				
				$OPNH_STAT 		= $this->input->post('OPNH_STAT');
				
				$WO_NUM 		= $this->input->post('WO_NUM');
				$OPNH_NUM 		= $this->input->post('OPNH_NUM');
				$OPNH_CODE 		= $this->input->post('OPNH_CODE');
				
				$OPNH_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				
				$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATE'))));
				$OPNH_DATESP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATESP'))));
				$OPNH_DATEEP	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('OPNH_DATEEP'))));
				$PRJCODE 		= $this->input->post('PRJCODE');
				$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
				$SPLCODE 		= $this->input->post('SPLCODE');
				$JOBCODEID		= $this->input->post('JOBCODEID');
				$WO_NUM 		= $this->input->post('WO_NUM');
				$WO_CODE 		= $this->input->post('WO_CODE');
				$OPNH_STAT 		= $this->input->post('OPNH_STAT');
				$OPNH_NOTE 		= htmlspecialchars($this->input->post('OPNH_NOTE'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				$OPNH_NOTE2		= htmlspecialchars($this->input->post('OPNH_NOTE2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				$OPNH_AMOUNT	= $this->input->post('OPNH_AMOUNT');
				$OPNH_AMOUNTPPNP= $this->input->post('OPNH_AMOUNTPPNP');
				$OPNH_AMOUNTPPN	= $this->input->post('OPNH_AMOUNTPPN');
				$OPNH_AMOUNTPPHP= $this->input->post('OPNH_AMOUNTPPHP');
				$OPNH_AMOUNTPPH	= $this->input->post('OPNH_AMOUNTPPH');
				$OPNH_RETPERC	= $this->input->post('OPNH_RETPERC');
				$OPNH_RETAMN	= $this->input->post('OPNH_RETAMN');
				$OPNH_RETNO		= $this->input->post('OPNH_RETNO');
				$OPNH_DPPER		= $this->input->post('OPNH_DPPER');
				$OPNH_DPVAL		= $this->input->post('OPNH_DPVAL');
				$OPNH_POT		= $this->input->post('OPNH_POT');
				$OPNH_POTREF	= $this->input->post('OPNH_POTREF');
				$OPNH_POTREF1	= $this->input->post('OPNH_POTREF1');
				$OPNH_POTACCID	= $this->input->post('OPNH_POTACCID');
				$TAXCODE_PPN	= $this->input->post('TAXCODE_PPN');
				$TAXCODE_PPH	= $this->input->post('TAXCODE_PPH');
				$Patt_Number	= $this->input->post('Patt_Number');
				
				$WO_CATEG	= '';
				$sqlSPK 	= "SELECT WO_CATEG FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM'";
				$resSPK 	= $this->db->query($sqlSPK)->result();
				foreach($resSPK as $rowSPK) :
					$WO_CATEG = $rowSPK->WO_CATEG;		
				endforeach;

				$mnAppCode	= 'MN472';
				
				//GET SUPPLIER CATEG
					$SPLCAT		= '';
					$SPLDESC 	= '';
					$sqlSPLC	= "SELECT SPLCAT, SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
					$resSPLC	= $this->db->query($sqlSPLC)->result();
					foreach($resSPLC as $rowSPLC) :
						$SPLCAT 	= $rowSPLC->SPLCAT;
						$SPLDESC 	= $rowSPLC->SPLDESC;
					endforeach;
				
				// START : SETTING L/R
					$this->load->model('m_updash/m_updash', '', TRUE);
					$PERIODED	= $OPNH_DATE;
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

				// UPDATE JOBDETAIL ITEM
				if($OPNH_STAT == 3)
				{
					// START : SAVE APPROVE HISTORY
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$AH_CODE		= $OPNH_NUM;
						$AH_APPLEV		= $this->input->post('APP_LEVEL');
						$AH_APPROVER	= $DefEmp_ID;
						$AH_APPROVED	= date('Y-m-d H:i:s');
						$AH_NOTES		= htmlspecialchars($this->input->post('OPNH_NOTE2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
						$AH_ISLAST		= $this->input->post('IS_LAST');
						
						$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
												'AH_APPLEV'		=> $AH_APPLEV,
												'AH_APPROVER'	=> $AH_APPROVER,
												'AH_APPROVED'	=> $AH_APPROVED,
												'AH_NOTES'		=> $AH_NOTES,
												'AH_ISLAST'		=> $AH_ISLAST);										
						$this->m_updash->insAppHist($insAppHist);
					
						$projOPH		= array('OPNH_STAT'		=> 7,
												'TAXCODE_PPN'	=> $TAXCODE_PPN,
												'TAXCODE_PPH'	=> $TAXCODE_PPH,
												'OPNH_NOTE2'	=> $OPNH_NOTE2);	// Default ke waiting jika masih ada approver yang lain
						$this->m_opname->update($OPNH_NUM, $projOPH);
					// END : SAVE APPROVE HISTORY
					
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
												'DOC_CODE' 		=> $OPNH_NUM,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_opn_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					if($AH_ISLAST == 1)
					{
						$projWOH 		= array('OPNH_APPROVER'	=> $DefEmp_ID,
												'OPNH_APPROVED'	=> date('Y-m-d H:i:s'),
												'OPNH_NOTE2'	=> $AH_NOTES,
												'OPNH_STAT'		=> $OPNH_STAT);										
						$this->m_opname->update($OPNH_NUM, $projWOH);
									
						//$this->m_opname->updateWODet($OPNH_NUM, $WO_NUM, $PRJCODE);
						
						// START : JOURNAL HEADER
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$JournalH_Code	= $OPNH_NUM;
							$JournalType	= 'OPN';
							$JournalH_Date	= $OPNH_DATE;
							$Company_ID		= $comp_init;
							$DOCSource		= $OPNH_NUM;
							$LastUpdate		= date('Y-m-d H:i:s');;
							$WH_CODE		= $PRJCODE;
							$Refer_Number	= '';
							$RefType		= 'WBSD';
							$PRJCODE		= $PRJCODE;
							$Manual_No 		= "J".$OPNH_CODE;
							$PERSL_EMPID 	= $SPLCODE;
							$SPLCODE 		= $SPLCODE;
							$SPLDESC	 	= $this->db->get_where("tbl_supplier", ["SPLCODE" => $SPLCODE])->row("SPLDESC");
							
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
												'Manual_No'			=> $Manual_No,
												'PPNH_Amount' 		=> $OPNH_AMOUNTPPN,
												'PPHH_Amount'		=> $OPNH_AMOUNTPPH,
												'PERSL_EMPID' 		=> $PERSL_EMPID,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC);

							//221025 $this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
						// END : JOURNAL HEADER
				
						// 001. START : JOURNAL DETAIL - COGS (DEBIT)
							$TOTALD		= 0;
							$TOTPPHNFIN = 0;
							foreach($_POST['data'] as $d)
							{
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $d['ITM_CODE'];
								$JOBCODEID 		= $d['JOBCODEID'];
								$ACC_ID 		= $d['ACC_ID_UM'];
								$ITM_UNIT 		= $d['ITM_UNIT'];
								$ITM_QTY 		= $d['OPND_VOLM'];
								$ITM_PRICE 		= $d['OPND_ITMPRICE'];
								$ITM_DISC 		= 0;
								$TAXCODE1 		= $d['TAXCODE1'];
								$TAXPRICE1 		= $d['TAXPRICE1'];
								$TAXCODE2 		= $d['TAXCODE2'];
								$TAXPRICE2 		= $d['TAXPRICE2'];
								$ITM_GROUP 		= $d['ITM_GROUP'];
								$OPND_DESC		= htmlentities($d['OPND_DESC'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
								$Notes 			= htmlentities($d['OPND_DESC'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
								$OPNVAL			= $ITM_QTY * $ITM_PRICE;
								$TOTALD			= $TOTALD + $OPNVAL;

								// START : PEMBENTUKAN JURNAL PPH JIKA ADA PPH NON-FINAL (SESUAI HASIL DISKUSI DENGAN BU LYTA TGL 21 OKT. 2022)
									// HASIL DISKUSI DENGAN BU LYTA TGL 21 OKT. 2022 BAHWA PPH NON-FINAL DIBENTUK SAAT PEMBENTUKAN JURNAL HUTANG
									// ARTINYA APABILA HUTANG TERBENTUK SAAT OPNAME, MAKA PPH NON-FINAL JUGA TERBENTUK SAAT OPNAME

									// HASIL DISKUSI DENGAN BU LYTA DNA PAK DEDE TGL. 25 OKT. 22
									// 1. PPH YANG DIPILIH PADA SAAT SPK, HARUS PPH FINAL
									// 2. TIDAK AKAN ADA PEMBENTUKAN JURNAL PPH BAIK SAAT OPNAME MAUPUN SAAT VOUCHER
									
									if($TAXPRICE2 > 0)
									{
										$TOTPPHNFIN = $TOTPPHNFIN+$TAXPRICE2;
										/*$isPPhFin 		= 0;
										$sqlISHO 		= "SELECT isHO, syncPRJ, Account_Number, Account_NameId AS Acc_Name, isPPhFinal FROM tbl_chartaccount_$PRJCODEVW
															WHERE PRJCODE = '$PRJCODE' AND Account_Number IN (SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE2') LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isPPhFin	= $rowISHO->isPPhFinal;
											if($isPPhFin == 0)
											{
												$TOTPPHNFIN = $TOTPPHNFIN+$TAXPRICE2;
											}
										endforeach;*/
									}
								// END
								
								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'OPN';
								$JSource		= 'OPN';
								$PRJCODE		= $PRJCODE;
								
								$TRANS_CATEG 	= "OPN~$SPLCAT";
								
								$parameters = array('JournalH_Code' => $JournalH_Code,
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN = Opname
												'WO_CATEG'			=> $WO_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> '',
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'TAXCODE2' 			=> $TAXCODE2,
												'TAXPRICE2' 		=> $TAXPRICE2,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC,
												'Notes' 			=> "$SPLDESC. Opn. : $OPNH_CODE _ $WO_CODE. $OPND_DESC",
												'JOBCODEID' 		=> $JOBCODEID);
								//221025 $this->m_journal->createJournalD($JournalH_Code, $parameters);

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
									//221025 $this->m_updash->updateLR_NForm($PRJCODE, $parameters1);
								// END : UPDATE PROFIT AND LOSS
								
								// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
								// DI HOLDED KARENA AKAN DILAKUKAN DISAAT TTK
									$this->load->model('m_updash/m_updash', '', TRUE);
									$paramFT = array('DOC_NUM' 		=> $OPNH_NUM,
													'DOC_DATE' 		=> $OPNH_DATE,
													'DOC_EDATE' 	=> $OPNH_DATE,
													'PRJCODE' 		=> $PRJCODE,
													'FIELD_NAME1' 	=> 'FT_COP',
													'FIELD_NAME2' 	=> 'FM_COP',
													'TOT_AMOUNT'	=> $ITM_PRICE);
									//$this->m_updash->finTrack($paramFT); HOLDED ON 11 JAN 19
								// END : TRACK FINANCIAL TRACK
							}
							$this->m_opname->updateWODet($OPNH_NUM, $WO_NUM, $PRJCODE);

						// START : JOURNAL DETAIL - HUTANG (KREDIT)
							// SISA DARI TOTAL SISI DEBET DIPOTONG DENGAN POTONGAN LAINNYA (KREDIT) ---> POINT 001 - 004
								$TOTALK	= 0;
								if($TOTALD > 0)
								{
									//$TOTALK	= $TOTALD - $OPNH_POT;
									//$TOTALK	= $TOTALD - $OPNH_RETAMN - $TOTPPHNFIN;
									$TOTALK	= $TOTALD - $OPNH_RETAMN;
								}
								$this->load->model('m_journal/m_journal', '', TRUE);
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= '';
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								/*$ITM_PRICE 		= $OPNH_AMOUNT;
								$ITM_DISC 		= $OPNH_POT;*/
								$ITM_PRICE 		= $TOTALK;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= $OPNH_AMOUNTPPN;
								
								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN2';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'OPN2';
								$JSource		= 'OPN2';
								$PRJCODE		= $PRJCODE;
								
								$TRANS_CATEG 	= "OPN2";
													
								$parameters = array('JournalH_Code' => $JournalH_Code,
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN = Opname hutang lain-lain
												'WO_CATEG'			=> $WO_CATEG,
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> '',
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'SPLCODE' 			=> $SPLCODE,
												'SPLDESC' 			=> $SPLDESC,
												'Other_Desc'		=> "$SPLDESC. $OPNH_NOTE $OPNH_CODE - $WO_CODE");

								//221025 $this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL - HUTANG BELUM DIFAKTUR (KREDIT)

						// 002. START : JOURNAL DETAIL - PENGEMBALIAN DP (KREDIT) 	HOLDED ON 27 DES. 2018
						// UPDATE 21-10-31 : TIDAK ADA PENJURNALAN PENGEMBALIAN DP. PENJURNALAN DILAKUKAN PD SAAT INVOICING
							if($OPNH_DPVAL > 0)
							{
								/*$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN3';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $WO_NUM;
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'WO';
								$JSource		= 'OPN3';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Pengembalian DP";
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= '';
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $OPNH_DPVAL;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "OPN3~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN3 = Pengembalian DP
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);
								$this->m_journal->createJournalD($JournalH_Code, $parameters);*/ 	// DIHOLD ON 27 DES 2018
							}
						// END : JOURNAL DETAIL
						
						// 003. START : JOURNAL DETAIL - RETENSI (KREDIT)
						// HASIL MEETING DGN PAK DUA & ES, JURNAL RETENSI TERBENTUK SAAT OPNAME
							if($OPNH_RETAMN > 0)
							{
								// membuat dokumen opn retensi
								// START : MEMBUAT DOK. OPNAMA RETENSI
									$OPNH_CODERET 	= $OPNH_CODE."-RET";
									$projOPNHRET 	= array('OPNH_NUM' 			=> $OPNH_NUM,
															'OPNH_CODE' 		=> $OPNH_CODERET,
															'OPNH_DATE'			=> $OPNH_DATE,
															'OPNH_DATESP'		=> $OPNH_DATESP,
															'OPNH_DATEEP' 		=> $OPNH_DATEEP,
															'PRJCODE'			=> $PRJCODE,
															'SPLCODE'			=> $SPLCODE,
															'JOBCODEID'			=> "",
															'WO_NUM'			=> $WO_NUM,
															'WO_CODE'			=> $WO_CODE,
															'OPNH_AMOUNT'		=> $OPNH_RETAMN,
															'OPNH_AMOUNTPPNP'	=> 0,
															'OPNH_AMOUNTPPN'	=> 0,
															'OPNH_AMOUNTPPHP'	=> 0,
															'OPNH_AMOUNTPPH'	=> 0,
															'OPNH_RETPERC'		=> $OPNH_RETPERC,
															'OPNH_RETAMN'		=> 0,
															'OPNH_RETNO' 		=> $OPNH_RETNO,
															'OPNH_NOTE'			=> "(Retensi) : $OPNH_NOTE. $OPNH_NOTE2",
															'OPNH_NOTE2'		=> $OPNH_NOTE2,
															'OPNH_DPPER'		=> 0,
															'OPNH_DPVAL'		=> 0,
															'OPNH_POT'			=> 0,
															'OPNH_POTREF'		=> $OPNH_POTREF,
															'OPNH_POTREF1'		=> $OPNH_POTREF1,
															'OPNH_POTACCID'		=> "",
															'OPNH_STAT'			=> $OPNH_STAT,
															'OPNH_CREATER'		=> $DefEmp_ID,
															'OPNH_CREATED'		=> date('Y-m-d H:i:s'),
															'OPNH_APPROVER'		=> $DefEmp_ID,
															'OPNH_APPROVED' 	=> date('Y-m-d H:i:s'),
															'OPNH_TYPE'			=> 1,
															'Patt_Year'			=> $Patt_Year, 
															'Patt_Month'		=> $Patt_Month,
															'Patt_Date'			=> $Patt_Date,
															'Patt_Number'		=> $Patt_Number,
															'SPLCODE' 			=> $SPLCODE,
															'SPLDESC' 			=> $SPLDESC);

									//221025 $this->m_opname->add($projOPNHRET);
					
									// START : UPDATE STATUS
										$completeName 	= $this->session->userdata['completeName'];
										$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
																'DOC_CODE' 		=> $OPNH_NUM,
																'DOC_STAT' 		=> $OPNH_STAT,
																'PRJCODE' 		=> $PRJCODE,
																'CREATERNM'		=> $completeName,
																'TBLNAME'		=> "tbl_opn_header");
										$this->m_updash->updateStatus($paramStat);
									// END : UPDATE STATUS
								// END : MEMBUAT DOK. OPNAMA RETENSI

								$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN-RET';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $WO_NUM;
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'WO';
								$JSource		= 'OPN-RET';
								$PRJCODE		= $PRJCODE;
								$Notes			= "RETENSI $OPNH_NOTE. $SPLDESC ($OPNH_CODE - $WO_CODE)";
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= '';
								$ITM_UNIT 		= 'Opname';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $OPNH_RETAMN;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "OPN-RET~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
								
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
													'ITM_CODE' 			=> "",
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes,
													'SPLCODE' 			=> $SPLCODE,
													'SPLDESC' 			=> $SPLDESC,
													'Other_Desc'		=> "$SPLDESC. (Retensi) $OPNH_NOTE $OPNH_CODE - $WO_CODE");
								//221025 $this->m_journal->createJournalD($JournalH_Code, $parameters);
							}
						// END : JOURNAL DETAIL - RETENSI (KREDIT)

						// 004. START : JOURNAL DETAIL - POTONGAN LAINNYA (KREDIT)
						// UPDATE 21-10-31 : TIDAK ADA PENJURNALAN PENGEMBALIAN DP. PENJURNALAN DILAKUKAN PD SAAT INVOICING
							if($OPNH_POT > 0)
							{
								// DAPATKAN AKUN SAAT JURNAL PIUTANG MELALUI GEJ
								// AKUNNYA ITU ADALAH OPNH_POTACCID
									
								/*$JournalH_Code	= $OPNH_NUM;
								$JournalType	= 'OPN5';
								$JournalH_Date	= $OPNH_DATE;
								$Company_ID		= $comp_init;
								$Currency_ID	= 'IDR';
								$DOCSource		= $WO_NUM;
								$LastUpdate		= date('Y-m-d H:i:s');
								$WH_CODE		= $PRJCODE;
								$Refer_Number	= $WO_NUM;
								$RefType		= 'WO';
								$JSource		= 'OPN5';
								$PRJCODE		= $PRJCODE;
								$Notes			= "Pengembalian DP";
								
								$ITM_CODE 		= $OPNH_NUM;
								$ACC_ID 		= $OPNH_POTACCID;
								$ITM_UNIT 		= '';
								$ITM_QTY 		= 1;
								$ITM_PRICE 		= $OPNH_POT;
								$ITM_DISC 		= 0;
								$TAXCODE1 		= "";
								$TAXPRICE1		= 0;
								
								$TRANS_CATEG 	= "OPN5~$SPLCAT";									// PERHATIKAN DI SINI. PENTING
								
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
													'TRANS_CATEG' 		=> $TRANS_CATEG,			// OPN5 = Potongan Lainnya
													'ITM_CODE' 			=> $ITM_CODE,
													'ACC_ID' 			=> $ACC_ID,
													'ITM_UNIT' 			=> $ITM_UNIT,
													'ITM_QTY' 			=> $ITM_QTY,
													'ITM_PRICE' 		=> $ITM_PRICE,
													'ITM_DISC' 			=> $ITM_DISC,
													'TAXCODE1' 			=> $TAXCODE1,
													'TAXPRICE1' 		=> $TAXPRICE1,
													'Notes' 			=> $Notes);
								$this->m_journal->createJournalD($JournalH_Code, $parameters);*/
							}
						// END : JOURNAL DETAIL
						
						// START : UPDATE STAT DET
							$this->load->model('m_updash/m_updash', '', TRUE);				
							$paramSTAT 	= array('JournalHCode' 	=> $OPNH_NUM);
							$this->m_updash->updSTATJD($paramSTAT);
						// END : UPDATE STAT DET
					
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
													'DOC_CODE' 		=> $OPNH_NUM,
													'DOC_STAT' 		=> $OPNH_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_opn_header");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
					/*else
					{*/
						// START : CREATE ALERT LIST
							$APP_LEVEL 	= $this->input->post('APP_LEVEL');
							$alertVar 	= array('PRJCODE'		=> $PRJCODE,
												'AS_CATEG'		=> "OPN",
												'AS_MNCODE'		=> $mnAppCode,
												'AS_DOCNUM'		=> $OPNH_NUM,
												'AS_DOCCODE'	=> $OPNH_CODE,
												'AS_DOCDATE'	=> $OPNH_DATESP,
												'AS_EXPDATE'	=> $OPNH_DATEEP,
												'APP_LEVEL'		=> $APP_LEVEL);
						$this->m_updash->updAALERT($alertVar);
						// END : CREATE ALERT LIST
					//}
				}
				else if($OPNH_STAT == 4)
				{
					// CLEAR APROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					$this->m_updash->delAppHist($OPNH_NUM);
					
					$projOPH		= array('OPNH_STAT'		=> $OPNH_STAT,
											'OPNH_NOTE2'	=> $OPNH_NOTE2);	// Default ke waiting jika masih ada approver yang lain
					$this->m_opname->update($OPNH_NUM, $projOPH);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
												'DOC_CODE' 		=> $OPNH_NUM,
												'DOC_STAT' 		=> $OPNH_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_opn_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				else // IF STAT IN (5,6)
				{
					$projOPH		= array('OPNH_STAT'		=> $OPNH_STAT);	// Default ke waiting jika masih ada approver yang lain
					$this->m_opname->update($OPNH_NUM, $projOPH);
				
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "OPNH_NUM",
												'DOC_CODE' 		=> $OPNH_NUM,
												'DOC_STAT' 		=> $OPNH_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_opn_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
				
				$updDesc	= "UPDATE tbl_journalheader A, tbl_opn_header B SET A.JournalH_Desc = B.OPNH_NOTE
								WHERE A.JournalH_Code = B.OPNH_NUM AND A.JournalH_Code = '$OPNH_NUM'";
				$this->db->query($updDesc);
				
				// CHECK WO TO CLOSE
				$this->m_opname->updateWOH($WO_NUM, $PRJCODE);
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');	// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 	=> $OPNH_NUM,		// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "OPN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_opn_header",// TABLE NAME
										'KEY_NAME'		=> "OPNH_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "OPNH_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $OPNH_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_OPN",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_OPN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_OPN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_OPN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_OPN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_OPN_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_OPN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
					
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $WO_NUM;
					$MenuCode 		= $mnAppCode;
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

					$parameters 	= array('TR_TYPE'		=> "OPN",
											'TR_DATE' 		=> $OPNH_DATE,
											'PRJCODE'		=> $PRJCODE);
					$this->m_updash->updateQtyColl($parameters);
				// END : UPDATE QTY_COLLECTIVE

				// START : ADD DOC HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					date_default_timezone_set("Asia/Jakarta");
					$paramTrack 	= array('REF_NUM' 		=> $OPNH_NUM,
											'TBLNAME' 		=> "tbl_opn_header",
											'FLDCODE'		=> "OPNH_CODE",
											'FLDNAME'		=> "OPNH_NUM",
											'HISTTYPE'		=> "Update approve ($OPNH_STAT)");
					$this->m_updash->uDocH($paramTrack);
				// END : ADD DOC HISTORY

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_o180d0bpnm/inb1a1_int/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function chkRETNO()
	{
		$RET_NO = $this->input->post("RET_NO");
		$data 	= $this->m_opname->chkRETNO($RET_NO)->result();
		echo json_encode($data);
	}

  	function get_AllDataITMWO() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$WO_NUM		= $_GET['WONUM'];
		$OPNH_NUM	= $_GET['OPNHNUM'];

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
			
			$columns_valid 	= array("ITM_CODE", 
									"WO_DESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_opname->get_AllDataITMC($PRJCODE, $WO_NUM, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_opname->get_AllDataITML($PRJCODE, $WO_NUM, $search, $length, $start, $order, $dir);
								
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
				$disabledB		= 0;
				$JOBCODEDET 	= $dataI['JOBCODEDET'];				// 0
				$WO_ID 			= $dataI['WO_ID'];
				$WO_NUM 		= $dataI['WO_NUM'];
				$PRJCODE 		= $dataI['PRJCODE'];
				$JOBCODEDET		= $dataI['JOBCODEDET'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$ITM_CODE 		= $dataI['ITM_CODE'];

				$ITM_NAME 		= '';
				$ACC_ID_UM 		= '';
				$ITM_GROUP 		= '';

				$sqlDETITM		= "SELECT A.ITM_NAME, A.ACC_ID_UM, A.ITM_GROUP
									FROM tbl_item_$PRJCODEVW A
									WHERE A.ITM_CODE = '$ITM_CODE'
										AND A.PRJCODE = '$PRJCODE'";
				$resDETITM 		= $this->db->query($sqlDETITM)->result();
				foreach($resDETITM as $detITM) :
					$ITM_NAME 		= $detITM->ITM_NAME;
					$ACC_ID_UM 		= $detITM->ACC_ID_UM;
					$ITM_GROUP 		= $detITM->ITM_GROUP;
				endforeach;

				$ITM_UNIT 		= $dataI['ITM_UNIT'];
				$ITM_UNIT		= strtoupper($ITM_UNIT);
				$OPND_PERC 		= $dataI['OPND_PERC'];
				$OPND_VOLM 		= $dataI['OPND_VOLM'];
				$ITM_PRICE 		= $dataI['OPND_ITMPRICE'];
				$OPND_ITMTOTAL	= $dataI['OPND_ITMTOTAL'];
				$TAXCODE1		= $dataI['TAXCODE1'];
				$TAXPERC1		= $dataI['TAXPERC1'];
				$TAXPRICE1		= $dataI['TAXPRICE1'];
				$TAXCODE2		= $dataI['TAXCODE2'];
				$TAXPERC2		= $dataI['TAXPERC2'];
				$TAXPRICE2		= $dataI['TAXPRICE2'];
				$OPND_DESC 		= $dataI['OPND_DESC'];
				$WO_DPPER 		= $dataI['WO_DPPER'];
				$OPNH_DPPER		= $WO_DPPER;
				$WO_RETP 		= $dataI['WO_RETP'];
				$OPNH_RETPERC 	= $WO_RETP;
				$itemConvertion	= 1;

				// TOTAL SPK YANG DIPIIH
					$TOTWO_AMN	= 0;
					$TOTWO_VOL	= 0;
					$sqlTOTWO	= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWO_AMN, 
									SUM(A.WO_VOLM) AS TOTWO_VOL
									FROM tbl_wo_detail A
									INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
										AND B.PRJCODE = '$PRJCODE'
									WHERE A.WO_NUM = '$WO_NUM' AND A.ITM_CODE = '$ITM_CODE'
										AND A.PRJCODE = '$PRJCODE'
										AND A.JOBCODEID = '$JOBCODEID' AND A.WO_ID = $WO_ID";
					$resTOTWO	= $this->db->query($sqlTOTWO)->result();
					foreach($resTOTWO as $rowTOTWO) :
						$TOTWO_AMN	= $rowTOTWO->TOTWO_AMN;
						$TOTWO_VOL	= $rowTOTWO->TOTWO_VOL;
					endforeach;
					
					$TOTWO_VW 		= $TOTWO_VOL;

					$isLSQ			= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
					$isLSR			= $this->db->count_all($isLSQ);

					if($isLSR > 0)
					{
						//$TOTWO_VOL 	= 1;
						$TOTWO_VOL 	= $TOTWO_VOL;
						$TOTWO_AMN 	= $TOTWO_AMN;
					}

				// TOTAL OPN APPROVED
					$TOTOPN_AMN	= 0;
					$TOTOPN_VOL	= 0;
					$sqlTOTOPN	= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
										SUM(A.OPND_VOLM) AS TOTOPN_VOL
									FROM tbl_opn_detail A
									INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
										AND B.PRJCODE = '$PRJCODE'
									WHERE B.WO_NUM = '$WO_NUM' AND A.ITM_CODE = '$ITM_CODE'
										AND A.PRJCODE = '$PRJCODE'
										AND A.JOBCODEID = '$JOBCODEID' AND B.OPNH_STAT IN (2,3,6)
										AND B.OPNH_TYPE = 0
										AND A.OPNH_NUM != '$OPNH_NUM' AND A.WO_ID = $WO_ID";
					$resTOTOPN		= $this->db->query($sqlTOTOPN)->result();
					foreach($resTOTOPN as $rowTOTOPN) :
						$TOTOPN_AMN		= $rowTOTOPN->TOTOPN_AMN;
						$TOTOPN_VOL		= $rowTOTOPN->TOTOPN_VOL;
						if($TOTOPN_AMN == '')
							$TOTOPN_AMN	= 0;
						if($TOTOPN_VOL == '')
							$TOTOPN_VOL	= 0;
					endforeach;

					$TOTOPN_VW 		= $TOTOPN_VOL;
					if($isLSR > 0)
					{
						//$TOTOPN_VOL = 1;
						$TOTOPN_VOL = $TOTOPN_VOL;
						$TOTOPN_AMN = $TOTOPN_AMN;
					}

				// SISA OPNAME
					if($isLSR > 0)
					{
						$REMOPN_VOL = $TOTWO_VOL - $TOTOPN_VOL;
						$REMOPN_AMN = $TOTWO_AMN - $TOTOPN_AMN;
					}
					else
					{
						$REMOPN_VOL = $TOTWO_VOL - $TOTOPN_VOL;
						$REMOPN_AMN = $TOTWO_AMN - $TOTOPN_AMN;
					}

					$OPND_ITMTOTAL	= $OPND_VOLM * $ITM_PRICE;

					$TOTWO_VOLP 	= $TOTWO_VOL;
					if($TOTWO_VOL == 0 || $TOTWO_VOL == '')
						$TOTWO_VOLP	= 1;

					$disableInp 	= 0;

					if($isLSR > 0)
					{
						if($TOTOPN_AMN >= $TOTWO_AMN)
						{
							$disableInp = 1;
							$REMOPN_VOL	= 0;
						}
					}
					else
					{
						if($TOTOPN_VOL >= $TOTWO_VOL)
						{
							$disableInp = 1;
							$REMOPN_VOL	= 0;
						}
					}
					
				$TOTWO_VOLP 	= $TOTWO_VOL;
				if($TOTWO_VOLP == 0 || $TOTWO_VOLP == '')
					$TOTWO_VOLP	= 1;

				$REMOPN_PERC 	= $REMOPN_VOL / $TOTWO_VOLP * 100;

				$OPND_POTDP 	= $OPNH_DPPER * $OPND_ITMTOTAL / 100;
				$OPND_POTRET 	= $OPNH_RETPERC * $OPND_ITMTOTAL / 100;

				$OPND_ITMTOTAL2 = $OPND_ITMTOTAL - $OPND_POTDP - $OPND_POTRET;
				$TAXPRICE1 		= $TAXPERC1 * $OPND_ITMTOTAL2 / 100;
				$TAXPRICE2 		= $TAXPERC2 * $OPND_ITMTOTAL2 / 100;
				$OPND_TOTAL 	= $OPND_ITMTOTAL2 + $TAXPRICE1 - $TAXPRICE2;;

				// GET HEADER JOB
					$TOT_BUDVOL 	= 0;
					$TOT_BUDVAL		= 0;
					$JOBHCODE 		= "";
					$JOBHDESC		= "";
					$sqlHDESC		= "SELECT A.JOBPARENT, B.JOBDESC FROM tbl_joblist_detail_$PRJCODEVW A
										INNER JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID
											AND B.PRJCODE = '$PRJCODE'
										WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'";
					$resHDESC		= $this->db->query($sqlHDESC)->result();
					foreach($resHDESC as $rowHDESC) :
						$JOBHCODE	= $rowHDESC->JOBPARENT;
						$JOBHDESC	= $rowHDESC->JOBDESC;
					endforeach;

				// CEK USAGE ACCOUNT
					$ItmCol0	= '';
					$ItmCol1	= '';
					$ItmCol2	= '';
					$ttl 		= '';
					$divDesc 	= '';
					if($ACC_ID_UM == '')
					{
						$disBtn 	= 1;
						$ItmCol0	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
						$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
						$ItmCol2	= '</span>';
						$ttl 		= 'Belum disetting kode akun penggunaan';
						$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
						$isDisabled = 1;
					}

				$disRow 			= 0;

				if($REMOPN_VOL == 0)
				{
					$disRow 		= 1;
				}

				// GET MAXIMUM OPNAME
					$PROG_PERC 		= 0;
					$MAX_OPN_VOL 	= $TOTWO_VOL;
					$MAX_OPN_VAL 	= $TOTWO_AMN;
					if($ITM_GROUP == 'S')
					{
						$JOBHCODELEV1 	= explode(".", $JOBHCODE);
						$JOBIDLEV1 		= $JOBHCODELEV1[0];
						$JOBHBOBOT 		= 0;
						$TOT_BUDVAL 	= 0;
						$JOBH_BUDG 		= 0;
						$s_BOBOT		= "SELECT (A.ITM_VOLM + A.AMD_VOL) AS TOT_BUDVOL, (A.ITM_BUDG + A.AMD_VAL) AS TOT_BUDVAL,
												A.ITM_BUDG, A.BOQ_BOBOT FROM tbl_joblist_detail_$PRJCODEVW A
											WHERE A.JOBCODEID = '$JOBIDLEV1' AND A.PRJCODE = '$PRJCODE'";
						$r_BOBOT		= $this->db->query($s_BOBOT)->result();
						foreach($r_BOBOT as $rw_BOBOT) :
							$TOT_BUDVOL	= $rw_BOBOT->TOT_BUDVOL;
							$TOT_BUDVAL	= $rw_BOBOT->TOT_BUDVAL;
							$JOBH_BUDG	= $rw_BOBOT->ITM_BUDG;
							$JOBHBOBOT	= $rw_BOBOT->BOQ_BOBOT;
						endforeach;

						$TOT_BUDVALP = $TOT_BUDVAL;
						if($TOT_BUDVAL == 0)
							$TOT_BUDVALP = 1;

						$s_PROG		= "SELECT SUM(PROG_VAL_EKS) AS TOT_PROG_VAL FROM tbl_project_progress_det
											WHERE JOBCODEID = '$JOBIDLEV1' AND PRJCODE = '$PRJCODE'";
						$r_PROG		= $this->db->query($s_PROG)->result();
						foreach($r_PROG as $rw_PROG) :
							$TOT_PROGVAL	= $rw_PROG->TOT_PROG_VAL;

							$PROG_PERC 		= $TOT_BUDVOL / $TOT_BUDVALP * 100;
							if($isLSR > 0)
								$PROG_PERC 	= $TOT_PROGVAL / $TOT_BUDVALP * 100;

							$MAX_OPN_VOL 	= $PROG_PERC * $TOTWO_VOL / 100;
							$MAX_OPN_VAL 	= $PROG_PERC * $TOTWO_AMN / 100;
						endforeach;
						
					}
					
				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TBUDG_VOL."|".$TBUDG_VAL."|".$TUSED_VOL."|".$TUSED_VAL."|".$tempTotMax."|".$PO_VOL."|".$PO_VAL."|".$WO_VOL."|".$WO_VAL."|".$OPN_VOL."|".$OPN_VAL."|".$TUSED_VOL."|".$TUSED_VAL."|".$ITM_REMVOL."|".$ITM_REMVAL."|".$ITM_REMVOL."|".$ITM_REMVAL."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
					}
					else
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='' style='display: none' />";
					}

				$secUpd			= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
                
				$secPrint		= 	"<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   	</a>
									</label>";
				$JobView		= "$JOBCODEID $JOBDESC1";

				$ITM_NAME 		= $JOBDESC1;
				$secRecount		= base_url().'index.php/__l1y/recItem/?id=';
				$RecItem 		= "$secRecount~$PRJCODE~$JOBCODEID~$ITM_CODE~$ITM_NAME";
				$RecItemV 		= "<input type='hidden' name='urlRecU".$noU."' id='urlRecU".$noU."' value='".$RecItem."'>
									<a onClick='recountItmU(".$noU.");' title='Hitung ulang permintaan' class='pull-left' style='cursor: pointer'><i class='fa fa-repeat'></i></a>";
									
				$ADDM_VW 		= "";
				if($AMDM_VAL != 0)
				{
					$ADDM_VW 	= 	"<div>
										<p class='text-red' style='white-space:nowrap'><i class='glyphicon glyphicon-triangle-bottom'></i>
								  		".number_format($AMDM_VAL, 2)."</p>
								  	</div>";
				}

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right; white-space: nowrap'><span ".$CELL_COL.">".number_format($ITM_BUDGVW, 2).$ADD_VW.$ADDM_VW."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($ITM_REQVW, 2).$TREQ_RVW."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_USEDVW, 2).$TUSED_RVW."</span></div>",
											"<div style='text-align:right;'>".$RecItemV."&nbsp;&nbsp;&nbsp;<span ".$CELL_COL.">".number_format($ITM_REMVW, 2)."</span></div>");

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
}