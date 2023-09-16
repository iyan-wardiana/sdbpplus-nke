<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 02 Januari 2018
	* File Name	= C_s180d0bpk.php
	* Location		= -
*/

class C_s180d0bpk extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN238';
				$MenuCode			= 'MN238';
				$data["MenuCode"] 	= 'MN238';
				$data["MenuApp"] 	= 'MN239';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN238';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/gallS180d0bpk/?id=";

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

	function gallS180d0bpk() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN238';
			$MenuCode			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuApp"] 	= 'MN239';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH5pK/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WO($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_s180d0bpk/s180d0bpk_144n3/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN238';
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

			$this->load->view('v_project/v_spk/spk_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH5pK()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
    		if($TranslCode == 'cancled')$cancled = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order1 	= $this->input->get("order");
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataC($PRJCODE, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				//$WO_DATEV 	= $WO_DATEV1." - ".$WO_ENDDV;
				$WO_DATEV 		= $WO_DATEV1;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC']; 
				$SPLCODE		= $dataI['SPLCODE'];
				$WO_VALUE		= $dataI['WO_VALUE'];
				$WO_TCAMN		= $dataI['WO_TCAMN'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-money margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";

				$spkCanc		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-times-circle margin-r-5'></i> ".$cancled."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_TCAMN, 2)."
									  	</div>";

				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				if($JOBDESC == '') $JOBDESC = "";
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCreate	= site_url('c_project/c_s180d0bpk/cr3473d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_wo_header~tbl_wo_detail~WO_NUM~$WO_NUM~PRJCODE~$PRJCODE";

				if($WO_STAT == 1 || $WO_STAT == 4)
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
				else if($WO_STAT == 3 || $WO_STAT == 6)
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
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
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN239';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$WO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  	".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											"$spkCanc",
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

  	function get_AllDataWOGRP() // GOOD
	{
		$PRJCODE		= $_GET['id'];
		$SPLCODE		= $_GET['SPLC'];
		$WO_STAT		= $_GET['GSTAT'];
		$WO_CATEG		= $_GET['SRC'];
		
		$LangID     	= $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
    		if($TranslCode == 'cancled')$cancled = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order1 	= $this->input->get("order");
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataGRPC($PRJCODE, $SPLCODE, $WO_STAT, $WO_CATEG, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataGRPL($PRJCODE, $SPLCODE, $WO_STAT, $WO_CATEG, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				//$WO_DATEV 	= $WO_DATEV1." - ".$WO_ENDDV;
				$WO_DATEV 		= $WO_DATEV1;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC']; 
				$SPLCODE		= $dataI['SPLCODE'];
				$WO_VALUE		= $dataI['WO_VALUE'];
				$WO_TCAMN		= $dataI['WO_TCAMN'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-money margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";

				$spkCanc		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-times-circle margin-r-5'></i> ".$cancled."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_TCAMN, 2)."
									  	</div>";

				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				if($JOBDESC == '') $JOBDESC = "";
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCreate	= site_url('c_project/c_s180d0bpk/cr3473d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_wo_header~tbl_wo_detail~WO_NUM~$WO_NUM~PRJCODE~$PRJCODE";

				if($WO_STAT == 1 || $WO_STAT == 4)
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
				else if($WO_STAT == 3 || $WO_STAT == 6)
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
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
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN239';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$WO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  	".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											"$spkCanc",
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
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'NegotNo')$NegotNo = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order1 	= $this->input->get("order");
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataSHC($PRJCODE, $ISCLS, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataSHL($PRJCODE, $ISCLS, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				//$WO_DATEV 	= $WO_DATEV1." - ".$WO_ENDDV;
				$WO_DATEV 		= $WO_DATEV1;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";
				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC']; 
				$SPLCODE		= $dataI['SPLCODE'];
				$WO_VALUE		= $dataI['WO_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				if($JOBDESC == '') $JOBDESC = "";
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up180d0bdt/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secCreate	= site_url('c_project/c_s180d0bpk/cr3473d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;
				$secDelIcut = base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 		= "$secDelIcut~tbl_wo_header~tbl_wo_detail~WO_NUM~$WO_NUM~PRJCODE~$PRJCODE";

				if($WO_STAT == 1 || $WO_STAT == 4)
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
				else if($WO_STAT == 2)
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
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
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
										  	".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
											"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
											$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function s180d0bpk_144n3() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN238';
			$MenuCode			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuApp"] 	= 'MN239';
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
			$data['form_action']= site_url('c_project/c_s180d0bpk/add_process');
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuCode1"] 	= 'MN342';
			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN238';
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

			$this->load->view('v_project/v_spk/spk_form', $data);
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
		$myCount = $this->db->count_all('tbl_wo_header');

		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_wo_header
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

	function popupallitem() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $_GET['pr1h0ec0JcoDe'];
			$PGFROM		= $_GET['pgfrm'];
			//$COLLID	= $this->url_encryption_helper->decode_url($COLLID);
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
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_s180d0bpk/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$data['countAllItem']	= $this->m_spk->count_all_ItemServ($PRJCODE, $JOBCODE, $PGFROM);
			$data['vwAllItem'] 		= $this->m_spk->viewAllItemServ($PRJCODE, $JOBCODE, $PGFROM)->result();

			$this->load->view('v_project/v_spk/spk_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function p0_fp4pUp4llItm() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $_GET['pr1h0ec0JcoDe'];
			$PGFROM		= $_GET['pgfrm'];
			//$COLLID	= $this->url_encryption_helper->decode_url($COLLID);
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
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_project/c_s180d0bpk/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

			$data['countAllItem']	= $this->m_spk->count_all_ItemFPA($PRJCODE, $JOBCODE, $PGFROM);
			$data['vwAllItem'] 		= $this->m_spk->viewAllItemFPA($PRJCODE, $JOBCODE, $PGFROM)->result();

			$this->load->view('v_project/v_spk/spk_selitem_fpa', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4llFP4MDR() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countFPA']	= $this->m_spk->count_all_FPAMDR($PRJCODE);
			$data['vwFPA'] 		= $this->m_spk->view_all_FPAMDR($PRJCODE)->result();

			$this->load->view('v_project/v_spk/spk_selFPAMDR', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4llFP4SUB() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countFPA']	= $this->m_spk->count_all_FPASUB($PRJCODE);
			$data['vwFPA'] 		= $this->m_spk->view_all_FPASUB($PRJCODE)->result();

			$this->load->view('v_project/v_spk/spk_selFPAMDR', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4llFP4() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countFPA']	= $this->m_spk->count_all_FPA($PRJCODE);
			$data['vwFPA'] 		= $this->m_spk->view_all_FPA($PRJCODE)->result();

			$this->load->view('v_project/v_spk/spk_selFPA', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function s3l4ll5PK() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_spk/m_spk', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;
			endforeach;
			$COLLID		= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($COLLID);

			$data['title'] 		= $appName;

			$data['countFPA']	= $this->m_spk->count_all_ASTSPK($DefEmp_ID);
			$data['vwFPA'] 		= $this->m_spk->view_all_ASTFPA($DefEmp_ID)->result();

			$this->load->view('v_project/v_spk/spk_selASTFPA', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function add_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$WO_STARTD		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_STARTD'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$WO_ENDD		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_ENDD'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			//$WO_PAYTYPE 	= $this->input->post('WO_PAYTYPE');
			$WO_NOTE 		= $this->input->post('WO_NOTE');
			$WO_NOTE2 		= $this->input->post('WO_NOTE2');
			$WO_PAYNOTE     = $this->input->post('WO_PAYNOTE');
			$FPA_NUM 		= $this->input->post('FPA_NUM');
			$WO_MEMO 		= $this->input->post('WO_MEMO');
			$WO_REFNO 		= $this->input->post('WO_REFNO');
			$WO_STAT 		= $this->input->post('WO_STAT');
			$WO_QUOT 		= $this->input->post('WO_QUOT');
			$WO_NEGO 		= $this->input->post('WO_NEGO');

			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
			$WO_DPPER		= $this->input->post('WO_DPPER');
			$WO_DPREF		= $this->input->post('WO_DPREF');
			$WO_DPREF1		= $this->input->post('WO_DPREF1');
			$WO_VALPPN		= $this->input->post('WO_VALPPN');
			$WO_VALPPH		= $this->input->post('WO_VALPPH');
			$WO_DPVAL		= $this->input->post('WO_DPVAL');
			$WO_RETP		= $this->input->post('WO_RETP');
			$WO_RETVAL		= $this->input->post('WO_RETVAL');
			$WO_GTOTAL		= $this->input->post('WO_GTOTAL');
			$WO_PAYTYPE		= $this->input->post('WO_PAYTYPE');
			$WO_TENOR		= $this->input->post('WO_TENOR');

			$WO_VALUEP 		= $WO_VALUE;
			if($WO_VALUE == 0 || $WO_VALUE == '')
				$WO_VALUEP 	= 1;

			$WO_VALPPHP		= $WO_VALPPH / $WO_VALUEP;

			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLPR_REFNO	= "$PR_REFNO";
					}
					else
					{
						$COLPR_REFNO	= "$COLPR_REFNO~$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;
			$FPA_NUM		= $this->input->post('FPA_NUM');
			$FPA_CODE		= $this->input->post('FPA_CODE1');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$MenuCode 		= 'MN238';
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'DOCCAT'		=> $WO_CATEG,
										'MNCODE' 		=> $MenuCode,
										'DOCTYPE' 		=> 'WO',
										'DOCNUM' 		=> $WO_NUM,
										'DOCCODE'		=> $WO_CODE,
										'DOCDATE'		=> $WO_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $WO_CODE 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE";
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
						$uplFile = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $WO_NUM, "REF_CODE" => $WO_CODE,
									"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
									"UPL_FILENAME" => $file['file_name'], "UPL_FILESIZE" => $file['file_size'],
									"UPL_FILETYPE" => $file['file_type'], "UPL_FILEPATH" => $file['file_path'], 
									"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
						$this->m_spk->uplDOC_TRX($uplFile);
					endforeach;
		
					// $fileUpload = join(", ", $data_upload);
				}
			
			// ============================= End Upload File ========================================== //

			$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
									'WO_CODE' 		=> $WO_CODE,
									'WO_DATE'		=> $WO_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									//'WO_PAYTYPE'	=> $WO_PAYTYPE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NOTE'		=> $WO_NOTE,
									'WO_NOTE2'		=> $WO_NOTE2,
									'WO_PAYNOTE'    => $WO_PAYNOTE,
									'WO_MEMO'		=> $WO_MEMO,
									'WO_VALUE'		=> $WO_VALUE,
									'WO_VALUEAPP'	=> $WO_VALUE,
									'WO_VALPPN'		=> $WO_VALPPN,
									'WO_VALPPH'		=> $WO_VALPPH,
									'WO_VALPPHP'	=> $WO_VALPPHP,
									'WO_DPVAL'		=> $WO_DPVAL,
									'WO_GTOTAL'		=> $WO_GTOTAL,
									'WO_STAT'		=> $WO_STAT,
									'WO_CREATER'	=> $DefEmp_ID,
									'WO_CREATED'	=> date('Y-m-d H:i:s'),
									'WO_REFNO'		=> $WO_REFNO,
									'FPA_NUM'		=> $FPA_NUM,
									'FPA_CODE'		=> $FPA_CODE,
									'WO_QUOT'		=> $WO_QUOT,
									'WO_NEGO'		=> $WO_NEGO,
									'WO_DPPER'		=> $WO_DPPER,
									'WO_RETP'		=> $WO_RETP,
									'WO_RETVAL'		=> $WO_RETVAL,
									'WO_DPREF'		=> $WO_DPREF,
									'WO_DPREF1'		=> $WO_DPREF1,
									'WO_PAYTYPE'	=> $WO_PAYTYPE,
									'WO_TENOR'		=> $WO_TENOR,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);
			$this->m_spk->add($projWOH);

			$TOT_PPN	= 0;
      		$TOT_PPH    = 0;
      		$TOT_DISC   = 0;
			$WO_GTOTAL	= 0;
      		$WOID 		= 0;
			foreach($_POST['data'] as $d)
			{
				$WOID 			= $WOID+1;
				$d['WO_ID']		= $WOID;
				$d['WO_NUM']	= $WO_NUM;
				$d['WO_CODE']	= $WO_CODE;
				$ITMUNIT		= $d['ITM_UNIT'];
				$TAXCODE1		= $d['TAXCODE1'];
				$TAXCODE2		= $d['TAXCODE2'];

				$TAX_PPN		= $d['TAXPRICE1'];
				$TOT_PPN		= $TOT_PPN + $TAX_PPN;
				$TAX_PPH		= $d['TAXPRICE2'];
				$TOT_PPH		= $TOT_PPH + $TAX_PPH;

				//$WO_DISCP   	= $d['WO_DISCP'];
			    //$TOT_DISC   	= $TOT_DISC + $WO_DISCP;

				$WOTOTAL		= $d['WO_TOTAL'];
				//$WO_GTOTAL		= $WO_GTOTAL + $WOTOTAL + $TOT_PPN - $TOT_PPH;

				/*$WO_DISCP   	= $d['WO_DISCP'];
			    $TOT_DISC   = $TOT_DISC + $WO_DISCP;*/
				
				$UNITTYPE		= strtoupper($ITMUNIT);

				// UPDATE LASTP
				if($WO_STAT == 2)
				{
					$JOBID 	= $d['JOBCODEID'];
					$ITMC  	= $d['ITM_CODE'];
					$LASTP 	= $d['ITM_PRICE'];
					$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $LASTP
								WHERE JOBCODEID = '$JOBID' AND ITM_CODE = '$ITMC' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updLP);
				}

				$d['WO_DIVID']	= "";
				$PRJHO			= $this->input->post('PRJHO');
				if($PRJHO == 'KTR')
				{
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

					$JOBID 	= $d['JOBCODEID'];
					$s_MN 	= "SELECT JOBCOD1 FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_MN	= $this->db->query($s_MN)->result();
					foreach($r_MN as $rw_MN) :
						$DIVID			= $rw_MN->JOBCOD1;
						$d['WO_DIVID']	= $DIVID;
					endforeach;
				}

				$this->db->insert('tbl_wo_detail',$d);

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$JOBID 		= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$DOC_VOLM	= $d['WO_VOLM'];
					$DOC_TOTAL	= $d['WO_TOTAL'];
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $WO_DATE,
										'JOBID'		=> $JOBID,
										'ITM_CODE'	=> $ITM_CODE,
										'DOC_VOLM'	=> $DOC_VOLM,
										'DOC_TOTAL'	=> $DOC_TOTAL,
										'VAR_VOL_R'	=> "WO_VOL_R",
										'VAR_VAL_R'	=> "WO_VAL_R");
					$this->m_updash->updJOBP($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				if($WO_STAT == 2)
				{
					// START : UPDATE FINANCIAL DASHBOARD
						$WO_VOLM	= $d['WO_VOLM'];
						$ITM_PRICE	= $d['ITM_PRICE'];
						$WO_VAL 	= $WO_VOLM * $ITM_PRICE;
						$finDASH 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $WO_DATE,
											'FVAL'		=> $WO_VAL,
											'FNAME'		=> "WO_VAL");										
						$this->m_updash->updFINDASH($finDASH);
					// END : UPDATE FINANCIAL DASHBOARD
				}
			}

			if($WO_STAT == 2)
			{
				if($WO_CATEG == 'U')
					$AS_MNCODE 	= "MN239";
				elseif($WO_CATEG == 'S')
					$AS_MNCODE 	= "MN342";
				elseif($WO_CATEG == 'O')
					$AS_MNCODE 	= "MN457";
				else
					$AS_MNCODE 	= "MN354";

				$PRJHO			= $this->input->post('PRJHO');
				if($PRJHO == 'KTR')
				{
					$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_wo_detail A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$PRJCODE' AND A.WO_NUM = '$WO_NUM'";
					$r_DIV 	= $this->db->query($s_DIV)->result();
					foreach($r_DIV as $rw_DIV):
						$AS_MNCODEA 	= $rw_DIV->JOBCOD1;
						if($AS_MNCODEA == 'MN437')				// Sekret
							$AS_MNCODE 	= "MN458";
						elseif($AS_MNCODEA == 'MN438')			// Audit
							$AS_MNCODE 	= "MN459";
						elseif($AS_MNCODEA == 'MN439')			// Corp. L1
							$AS_MNCODE 	= "MN460";
						elseif($AS_MNCODEA == 'MN440')			// Corp. L2
							$AS_MNCODE 	= "MN461";
						elseif($AS_MNCODEA == 'MN441')			// QHSSE-SI
							$AS_MNCODE 	= "MN462";
						elseif($AS_MNCODEA == 'MN442')			// Marketing
							$AS_MNCODE 	= "MN463";
						elseif($AS_MNCODEA == 'MN443')			// SPKerasi
							$AS_MNCODE 	= "MN464";
						elseif($AS_MNCODEA == 'MN444')			// Keuangan
							$AS_MNCODE 	= "MN465";
						elseif($AS_MNCODEA == 'MN445')			// HRD
							$AS_MNCODE 	= "MN466";
						elseif($AS_MNCODEA == 'MN446')			// SPK Anak Usaha
							$AS_MNCODE 	= "MN467";
					endforeach;
				}

				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "WO",
										'AS_MNCODE'		=> $AS_MNCODE,
										'AS_DOCNUM'		=> $WO_NUM,
										'AS_DOCCODE'	=> $WO_CODE,
										'AS_DOCDATE'	=> $WO_STARTD,
										'AS_EXPDATE'	=> $WO_ENDD);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}

			// UPDATE HEADER : TOTAL PPN
				/*$projWOH 		= array('WO_VALUE' 	=> $WO_GTOTAL,
										'WO_VALPPN' => $TOT_PPN, 'WO_VALPPH' => $TOT_PPH); 
				$this->m_spk->update($WO_NUM, $projWOH);*/

			// UPDATE DETAIL
				$this->m_spk->updateDet($WO_NUM, $PRJCODE, $WO_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('WO_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_wo_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN238';
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

				$parameters 	= array('TR_TYPE'		=> "WO",
										'TR_DATE' 		=> $WO_DATE,
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

			$url			= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function up180d0bdt() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN238';
			$MenuCode			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			$data["MenuApp"] 	= 'MN239';
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
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_PAYNOTE'] 	= $getwodata->WO_PAYNOTE;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['WO_RETP'] 	= $getwodata->WO_RETP;
			$data['default']['WO_RETVAL'] 	= $getwodata->WO_RETVAL;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['WO_GTOTAL'] 	= $getwodata->WO_GTOTAL;
			$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['WO_TENOR'] 	= $getwodata->WO_TENOR;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';
			if($WO_CATEG == 'U')
				$data["MenuApp"] 	= 'MN239';
			elseif($WO_CATEG == 'S')
				$data["MenuApp"] 	= 'MN342';
			elseif($WO_CATEG == 'O')
				$data["MenuApp"] 	= 'MN457';
			else
				$data["MenuApp"] 	=  'MN354';

			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN238';
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

			$this->load->view('v_project/v_spk/spk_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$WO_STARTD		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_STARTD'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$WO_ENDD		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_ENDD'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$WO_NOTE 		= $this->input->post('WO_NOTE');
			$WO_NOTE2 		= $this->input->post('WO_NOTE2');
			$WO_PAYNOTE     = $this->input->post('WO_PAYNOTE');
			$WO_STAT 		= $this->input->post('WO_STAT');	
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_REFNO		= $this->input->post('WO_REFNO');

			$WO_DPPER		= $this->input->post('WO_DPPER');
			$WO_DPREF		= $this->input->post('WO_DPREF');
			$WO_DPREF1		= $this->input->post('WO_DPREF1');
			$WO_VALUE		= $this->input->post('WO_VALUE');
			$WO_VALPPN		= $this->input->post('WO_VALPPN');
			$WO_VALPPH		= $this->input->post('WO_VALPPH');
			$WO_DPVAL		= $this->input->post('WO_DPVAL');
			$WO_RETP		= $this->input->post('WO_RETP');
			$WO_RETVAL		= $this->input->post('WO_RETVAL');
			$WO_GTOTAL		= $this->input->post('WO_GTOTAL');
			$WO_PAYTYPE		= $this->input->post('WO_PAYTYPE');
			$WO_TENOR		= $this->input->post('WO_TENOR');

			$WO_VALPPHP		= $WO_VALPPH / $WO_VALUE;

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN238',
										'DOCNUM' 		=> $WO_NUM,
										'DOCCODE'		=> $WO_CODE,
										'DOCDATE'		=> $WO_DATE,
										'ACC_ID'		=> "",
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist

			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLPR_REFNO	= "$PR_REFNO";
					}
					else
					{
						$COLPR_REFNO	= "$COLPR_REFNO~$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;
			$FPA_NUM		= $this->input->post('FPA_NUM');
			$FPA_CODE		= $this->input->post('FPA_CODE1');
			$WO_QUOT 		= $this->input->post('WO_QUOT');
			$WO_NEGO 		= $this->input->post('WO_NEGO');
			$WO_MEMO 		= $this->input->post('WO_MEMO');

			/*$WO_GTOTAL	= 0;
			$TOT_PPN	= 0;
      		$TOT_PPH    = 0;
      		$TOT_DISC   = 0;
			foreach($_POST['data'] as $d)
			{
				$TAX_PPN		= $d['TAXPRICE1'];
				$TOT_PPN		= $TOT_PPN + $TAX_PPN;
				$TAX_PPH		= $d['TAXPRICE2'];
				$TOT_PPH		= $TOT_PPH + $TAX_PPH;

				//$WO_DISCP   	= $d['WO_DISCP'];
			    //$TOT_DISC   	= $TOT_DISC + $WO_DISCP;

				$WOTOTAL		= $d['WO_TOTAL'];
				$WO_GTOTAL	= $WO_GTOTAL + $WOTOTAL + $TOT_PPN - $TOT_PPH;
			}*/

			if($WO_STAT == 6)
			{
				$projWOH 		= array('WO_STAT'		=> $WO_STAT,
										'WO_MEMO'		=> $WO_MEMO);
				$this->m_spk->update($WO_NUM, $projWOH);

				foreach($_POST['data'] as $d)
				{
					$WO_NUM		= $d['WO_NUM'];
					$JOBCODEID1	= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$WO_VOLM	= $d['WO_VOLM'];
					$WO_TOTAL	= $d['WO_TOTAL'];
					$param 		= array('WO_NUM' 		=> $WO_NUM,
										'JOBCODEID' 	=> $JOBCODEID1,
										'ITM_CODE' 		=> $ITM_CODE,
										'WO_VOLM'		=> $WO_VOLM,
										'WO_TOTAL'		=> $WO_TOTAL);
					$this->m_spk->closedUPDWO($WO_NUM, $PRJCODE, $param);
				}

				// SEKALIAN SYNC ALL
					$this->m_spk->closedWO($WO_NUM, $PRJCODE);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'	=> $completeName,
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			elseif($WO_STAT == 9)
			{
				$projWOH 		= array('WO_STAT'		=> $WO_STAT,
										'WO_MEMO'		=> $WO_MEMO);
				$this->m_spk->update($WO_NUM, $projWOH);
				
				foreach($_POST['data'] as $d)
				{
					$WO_NUM		= $d['WO_NUM'];
					$JOBCODEID1	= $d['JOBCODEID'];
					$ITM_CODE	= $d['ITM_CODE'];
					$WO_VOLM	= $d['WO_VOLM'];
					$WO_TOTAL	= $d['WO_TOTAL'];
					$param 		= array('WO_NUM' 		=> $WO_NUM,
										'JOBCODEID' 	=> $JOBCODEID1,
										'ITM_CODE' 		=> $ITM_CODE,
										'WO_VOLM'		=> $WO_VOLM,
										'WO_TOTAL'		=> $WO_TOTAL);
					$this->m_spk->voidUPDJO($WO_NUM, $PRJCODE, $param);
				}
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				// ============================= Start Upload File ========================================== //
					$fileUpload	= null;
					$files 		= $_FILES;
					$count 		= count($_FILES['userfile']['name']);

					if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE")) {
						mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE", 0777, true);
					}
					
					$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE";
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
							$uplFile = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $WO_NUM, "REF_CODE" => $WO_CODE,
										"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
										"UPL_FILENAME" => $file['file_name'], "UPL_FILESIZE" => $file['file_size'],
										"UPL_FILETYPE" => $file['file_type'], "UPL_FILEPATH" => $file['file_path'], 
										"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_spk->uplDOC_TRX($uplFile);
						endforeach;
			
						// $fileUpload = join(", ", $data_upload);
					}
				
				// ============================= End Upload File ========================================== //

				$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
										'WO_CODE' 		=> $WO_CODE,
										'WO_DATE'		=> $WO_DATE,
										'WO_STARTD'		=> $WO_STARTD,
										'WO_ENDD'		=> $WO_ENDD,
										'PRJCODE'		=> $PRJCODE,
										'SPLCODE'		=> $SPLCODE,
										'WO_DEPT'		=> $WO_DEPT,
										'WO_CATEG'		=> $WO_CATEG,
										'WO_TYPE'		=> $WO_TYPE,
										//'WO_PAYTYPE'	=> $WO_PAYTYPE,
										'JOBCODEID'		=> $JOBCODEID,
										'WO_NOTE'		=> $WO_NOTE,
										'WO_NOTE2'		=> $WO_NOTE2,
										'WO_PAYNOTE'    => $WO_PAYNOTE,
										'WO_MEMO'		=> $WO_MEMO,
										'WO_STAT'		=> $WO_STAT,
										'FPA_NUM'		=> $FPA_NUM,
										'WO_REFNO'		=> $WO_REFNO,
										'FPA_CODE'		=> $FPA_CODE,
										'WO_QUOT'		=> $WO_QUOT,
										'WO_NEGO'		=> $WO_NEGO,
										'WO_DPPER'		=> $WO_DPPER,
										'WO_DPREF'		=> $WO_DPREF,
										'WO_DPREF1'		=> $WO_DPREF1,
										'WO_VALUE'		=> $WO_VALUE,
										'WO_VALUEAPP'	=> $WO_VALUE,
										'WO_VALPPN'		=> $WO_VALPPN,
										'WO_VALPPH'		=> $WO_VALPPH,
										'WO_VALPPHP'	=> $WO_VALPPHP,
										'WO_DPVAL'		=> $WO_DPVAL,
										'WO_RETP'		=> $WO_RETP,
										'WO_RETVAL'		=> $WO_RETVAL,
										'WO_GTOTAL'		=> $WO_GTOTAL,
										'WO_PAYTYPE'	=> $WO_PAYTYPE,
										'WO_TENOR'		=> $WO_TENOR,
										'Patt_Year'		=> $Patt_Year, 
										'Patt_Month'	=> $Patt_Month,
										'Patt_Date'		=> $Patt_Date,
										'Patt_Number'	=> $this->input->post('Patt_Number'));
				$this->m_spk->update($WO_NUM, $projWOH);

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $WO_DATE,
										'DOC_NUM'	=> $WO_NUM,
										'DOC_CATEG'	=> "WO");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL

				$this->m_spk->deleteDetail($WO_NUM);
				
				$WOID 			= 0;
				foreach($_POST['data'] as $d)
				{
					$WOID 			= $WOID+1;
					$d['WO_ID']		= $WOID;

					if($WO_STAT == 2)
					{
						$JOBID 			= $d['JOBCODEID'];
						$ITMC  			= $d['ITM_CODE'];
						$LASTP 			= $d['ITM_PRICE'];
						$d['WO_DATE'] 	= $WO_DATE;

						$updLP 	= "UPDATE tbl_joblist_detail SET ITM_LASTP = $LASTP
									WHERE JOBCODEID = '$JOBID' AND ITM_CODE = '$ITMC' AND PRJCODE = '$PRJCODE'";
						$this->db->query($updLP);
					}

					$d['WO_DIVID']	= "";
					$PRJHO			= $this->input->post('PRJHO');
					if($PRJHO == 'KTR')
					{
						$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
						$JOBID 	= $d['JOBCODEID'];
						$s_MN 	= "SELECT JOBCOD1 FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBID' AND PRJCODE = '$PRJCODE' LIMIT 1";
						$r_MN	= $this->db->query($s_MN)->result();
						foreach($r_MN as $rw_MN) :
							$DIVID			= $rw_MN->JOBCOD1;
							$d['WO_DIVID']	= $DIVID;
						endforeach;
					}
					
					$this->db->insert('tbl_wo_detail',$d);

					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$JOBID 		= $d['JOBCODEID'];
						$ITM_CODE	= $d['ITM_CODE'];
						$DOC_VOLM	= $d['WO_VOLM'];
						$DOC_TOTAL	= $d['WO_TOTAL'];
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $WO_DATE,
											'JOBID'		=> $JOBID,
											'ITM_CODE'	=> $ITM_CODE,
											'DOC_VOLM'	=> $DOC_VOLM,
											'DOC_TOTAL'	=> $DOC_TOTAL,
											'VAR_VOL_R'	=> "WO_VOL_R",
											'VAR_VAL_R'	=> "WO_VAL_R");
						$this->m_updash->updJOBP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL

					if($WO_STAT == 2)
					{
						// START : UPDATE FINANCIAL DASHBOARD
							$WO_VOLM	= $d['WO_VOLM'];
							$ITM_PRICE	= $d['ITM_PRICE'];
							$WO_VAL 	= $WO_VOLM * $ITM_PRICE;
							$finDASH 	= array('PRJCODE'	=> $PRJCODE,
												'PERIODE'	=> $WO_DATE,
												'FVAL'		=> $WO_VAL,
												'FNAME'		=> "WO_VAL");										
							$this->m_updash->updFINDASH($finDASH);
						// END : UPDATE FINANCIAL DASHBOARD
					}
				}
				
				// UPDATE HEADER : TOTAL PPN
					/*$projWOH 		= array('WO_VALUE' 	=> $WO_GTOTAL,
											'WO_VALPPN' => $TOT_PPN, 'WO_VALPPH' => $TOT_PPH);
					$this->m_spk->update($WO_NUM, $projWOH);*/

				// UPDATE DETAIL
					$this->m_spk->updateDet($WO_NUM, $PRJCODE, $WO_DATE);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			if($WO_STAT == 2)
			{
				if($WO_CATEG == 'U')
					$AS_MNCODE 	= "MN239";
				elseif($WO_CATEG == 'S')
					$AS_MNCODE 	= "MN342";
				elseif($WO_CATEG == 'O')
					$AS_MNCODE 	= "MN457";
				else
					$AS_MNCODE 	= "MN354";

				$PRJHO			= $this->input->post('PRJHO');
				if($PRJHO == 'KTR')
				{
					$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_wo_detail A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
								WHERE A.PRJCODE = '$PRJCODE' AND A.WO_NUM = '$WO_NUM'";
					$r_DIV 	= $this->db->query($s_DIV)->result();
					foreach($r_DIV as $rw_DIV):
						$AS_MNCODEA 	= $rw_DIV->JOBCOD1;
						if($AS_MNCODEA == 'MN437')				// Sekret
							$AS_MNCODE 	= "MN458";
						elseif($AS_MNCODEA == 'MN438')			// Audit
							$AS_MNCODE 	= "MN459";
						elseif($AS_MNCODEA == 'MN439')			// Corp. L1
							$AS_MNCODE 	= "MN460";
						elseif($AS_MNCODEA == 'MN440')			// Corp. L2
							$AS_MNCODE 	= "MN461";
						elseif($AS_MNCODEA == 'MN441')			// QHSSE-SI
							$AS_MNCODE 	= "MN462";
						elseif($AS_MNCODEA == 'MN442')			// Marketing
							$AS_MNCODE 	= "MN463";
						elseif($AS_MNCODEA == 'MN443')			// SPKerasi
							$AS_MNCODE 	= "MN464";
						elseif($AS_MNCODEA == 'MN444')			// Keuangan
							$AS_MNCODE 	= "MN465";
						elseif($AS_MNCODEA == 'MN445')			// HRD
							$AS_MNCODE 	= "MN466";
						elseif($AS_MNCODEA == 'MN446')			// SPK Anak Usaha
							$AS_MNCODE 	= "MN467";
					endforeach;
				}

				// START : CREATE ALERT LIST
					$alertVar 	= array('PRJCODE'		=> $PRJCODE,
										'AS_CATEG'		=> "WO",
										'AS_MNCODE'		=> $AS_MNCODE,
										'AS_DOCNUM'		=> $WO_NUM,
										'AS_DOCCODE'	=> $WO_CODE,
										'AS_DOCDATE'	=> $WO_STARTD,
										'AS_EXPDATE'	=> $WO_ENDD);
					$this->m_updash->addALERT($alertVar);
				// END : CREATE ALERT LIST
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_wo_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN238';
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

				$parameters 	= array('TR_TYPE'		=> "WO",
										'TR_DATE' 		=> $WO_DATE,
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
			$url			= site_url('c_project/c_s180d0bpk/gallS180d0bpk/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function trash_WO() // U
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		/*$CollID		= $_GET['id'];
		$splitCode 	= explode("~", $CollID);
		$PR_NUM		= $splitCode[0];
		$PRJCODE	= $splitCode[1];*/
		$WO_NUM		= $_GET['id'];
		$PRJCODE	= 999;

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$this->m_spk->deleteWO($WO_NUM);

			// START : UPDATE TO TRANS-COUNT
				/*$this->load->model('m_updash/m_updash', '', TRUE);

				$PR_STAT		= 1;
				$STAT_BEFORE	= 1;										// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $PR_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PR",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
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
				$this->m_updash->updateDashData($parameters);*/
			// END : UPDATE TO TRANS-COUNT

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			return $this->m_mailbox->get_all_mail_inbox($DefEmp_ID)->result();
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

		$url			= site_url('c_project/c_s180d0bpk/pR7_l5t_5pKx1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function pR7_l5t_5pKx1() // G
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
				$mnCode				= 'MN239';
				$data["MenuApp"] 	= 'MN239';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN239';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/s5pK_1nb_5pKa/?id=";

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

	function s5pK_1nb_5pKa() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		// GET MENU DESC
			$mnCode				= 'MN239';
			$MenuCode			= 'MN239';
			$data["MenuCode"] 	= 'MN239';
			$data["MenuApp"] 	= 'MN239';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN239';
				$data["MenuApp"] 	= 'MN239';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_s180d0bpk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode']	= 'MN239';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN239';
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

			$this->load->view('v_project/v_spk/spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB5pK()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/s5pK_1nb_5pKa/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";

				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				$WO_VALUE		= $dataI['WO_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '-';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;	
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$WO_REFNO	= $dataI['WO_REFNO'];

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/update_inb/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/printdocument/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1 || $WO_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN239';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$WO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."<br>
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
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

	function update_inb()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN239';
			$MenuCode			= 'MN239';
			$data["MenuCode"] 	= 'MN239';
			$data["MenuApp"] 	= 'MN239';
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
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['WO_RETP'] 	= $getwodata->WO_RETP;
			$data['default']['WO_RETVAL'] 	= $getwodata->WO_RETVAL;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['WO_GTOTAL'] 	= $getwodata->WO_GTOTAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN239';
			$data["MenuCode"] 	= 'MN239';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
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

			$this->load->view('v_project/v_spk/spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_process_inb() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			$WO_DATE		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_DATE'))));
			$WO_STARTD		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_STARTD'))));
			$WO_ENDD		= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('WO_ENDD'))));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$WO_STAT 		= $this->input->post('WO_STAT');
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_NOTE2		= addslashes($this->input->post('WO_NOTE2'));

			if($WO_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);

					$AH_CODE		= $WO_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= date('Y-m-d H:i:s');
					$AH_NOTES		= addslashes($this->input->post('WO_NOTE2'));
					$AH_ISLAST		= $this->input->post('IS_LAST');

					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);
					$this->m_updash->insAppHist($insAppHist);

					$projWOH		= array('WO_STAT'	=> 7,
											'WO_NOTE2'	=> $WO_NOTE2);		// Default ke waiting jika masih ada approver yang lain
					$this->m_spk->update($WO_NUM, $projWOH);
				// END : SAVE APPROVE HISTORY

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				if($AH_ISLAST == 1)
				{
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
												'DOC_CODE' 		=> $WO_NUM,
												'DOC_STAT' 		=> $WO_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_wo_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS

					$projWOH 		= array('WO_APPROVER'	=> $DefEmp_ID,
											'WO_APPROVED'	=> date('Y-m-d H:i:s'),
											'WO_NOTE2'		=> addslashes($this->input->post('WO_NOTE2')),
											'WO_STAT'		=> $this->input->post('WO_STAT'));
					$this->m_spk->update($WO_NUM, $projWOH);

					$this->m_spk->updateWODet($WO_NUM, $PRJCODE);


					// START : PROCEDURE UPDATE JOBLISTDETAIL
						$compVAR 	= array('PRJCODE'	=> $PRJCODE,
											'PERIODE'	=> $WO_DATE,
											'DOC_NUM'	=> $WO_NUM,
											'DOC_CATEG'	=> "WO");
						$this->m_updash->updJOBPAPP($compVAR);
					// END : PROCEDURE UPDATE JOBLISTDETAIL
				}
				/*else
				{*/
					if($WO_CATEG == 'U')
						$AS_MNCODE 	= "MN239";
					elseif($WO_CATEG == 'S')
						$AS_MNCODE 	= "MN342";
					elseif($WO_CATEG == 'O')
						$AS_MNCODE 	= "MN457";
					else
						$AS_MNCODE 	= "MN354";

					$PRJHO			= $this->input->post('PRJHO');
					if($PRJHO == 'KTR')
					{
						$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_wo_detail A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
									WHERE A.PRJCODE = '$PRJCODE' AND A.WO_NUM = '$WO_NUM'";
						$r_DIV 	= $this->db->query($s_DIV)->result();
						foreach($r_DIV as $rw_DIV):
							$AS_MNCODEA 	= $rw_DIV->JOBCOD1;
							if($AS_MNCODEA == 'MN437')				// Sekret
								$AS_MNCODE 	= "MN458";
							elseif($AS_MNCODEA == 'MN438')			// Audit
								$AS_MNCODE 	= "MN459";
							elseif($AS_MNCODEA == 'MN439')			// Corp. L1
								$AS_MNCODE 	= "MN460";
							elseif($AS_MNCODEA == 'MN440')			// Corp. L2
								$AS_MNCODE 	= "MN461";
							elseif($AS_MNCODEA == 'MN441')			// QHSSE-SI
								$AS_MNCODE 	= "MN462";
							elseif($AS_MNCODEA == 'MN442')			// Marketing
								$AS_MNCODE 	= "MN463";
							elseif($AS_MNCODEA == 'MN443')			// SPKerasi
								$AS_MNCODE 	= "MN464";
							elseif($AS_MNCODEA == 'MN444')			// Keuangan
								$AS_MNCODE 	= "MN465";
							elseif($AS_MNCODEA == 'MN445')			// HRD
								$AS_MNCODE 	= "MN466";
							elseif($AS_MNCODEA == 'MN446')			// SPK Anak Usaha
								$AS_MNCODE 	= "MN467";
						endforeach;
					}

					// START : CREATE ALERT LIST
						$APP_LEVEL 	= $this->input->post('APP_LEVEL');
						$alertVar 	= array('PRJCODE'	=> $PRJCODE,
										'AS_CATEG'		=> "WO",
										'AS_MNCODE'		=> $AS_MNCODE,
										'AS_DOCNUM'		=> $WO_NUM,
										'AS_DOCCODE'	=> $WO_CODE,
										'AS_DOCDATE'	=> $WO_STARTD,
										'AS_EXPDATE'	=> $WO_ENDD,
										'APP_LEVEL'		=> $APP_LEVEL);
					$this->m_updash->updAALERT($alertVar);
					// END : CREATE ALERT LIST
				//}
			}
			elseif($WO_STAT == 4)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);
				// START : CLEAR HISTORY
					$cllPar = array('AH_CODE' 		=> $WO_NUM,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);

					$projWOH 		= array('WO_NOTE2'		=> $WO_NOTE2,
											'WO_STAT'		=> $WO_STAT);
					$this->m_spk->update($WO_NUM, $projWOH);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
												'DOC_CODE' 		=> $WO_NUM,
												'DOC_STAT' 		=> $WO_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_wo_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				// END : CLEAR HISTORY
			}
			elseif($WO_STAT == 5)
			{
				$this->load->model('m_updash/m_updash', '', TRUE);

				$projWOH 		= array('WO_NOTE2'		=> $WO_NOTE2,
										'WO_STAT'		=> $WO_STAT);
				$this->m_spk->update($WO_NUM, $projWOH);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_wo_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : PROCEDURE UPDATE JOBLISTDETAIL
					$compVAR 	= array('PRJCODE'	=> $PRJCODE,
										'PERIODE'	=> $WO_DATE,
										'DOC_NUM'	=> $WO_NUM,
										'DOC_CATEG'	=> "WO");
					$this->m_updash->updJOBM($compVAR);
				// END : PROCEDURE UPDATE JOBLISTDETAIL
			}

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN239';
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

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "WO",
										'TR_DATE' 		=> $WO_DATE,
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

			if($WO_CATEG == 'A')
				$url	= site_url('c_project/c_s180d0bpk/ibx1_er/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			elseif($WO_CATEG == 'S')
				$url	= site_url('c_project/c_s180d0bpk/s5uB_1nb_5pK5uB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			elseif($WO_CATEG == 'O')
				$url	= site_url('c_project/c_s180d0bpk/ibx1_ovh/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			else
				$url	= site_url('c_project/c_s180d0bpk/s5pK_1nb_5pKa/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function inbox_sub() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst_5uB/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_5uB() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		// GET MENU DESC
			$mnCode				= 'MN342';
			$MenuCode			= 'MN342';
			$data["MenuCode"] 	= 'MN342';
			$data["MenuApp"] 	= 'MN342';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan SPK Sub.";
			}
			else
			{
				$data["h1_title"] 	= "Approval Sub. WO";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN342';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/s5uB_1nb_5pK5uB/?id=";

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

	function s5uB_1nb_5pK5uB() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN342';
			$MenuCode			= 'MN342';
			$data["MenuCode"] 	= 'MN342';
			$data["MenuApp"] 	= 'MN342';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK5Ub/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx_sub($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb_sub($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_sub/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode']	= 'MN342';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN342';
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

			$this->load->view('v_project/v_spk/sub_spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB5pK5Ub()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/s5uB_1nb_5pK5uB/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataSC_1n2() // GOOD
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
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataCSC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLSC_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_DATEV 		= $WO_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";

				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
				$WO_VALUE		= $dataI['WO_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/update_inb_sub/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/printdocument/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1 || $WO_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else if($WO_STAT == 2)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN342';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$WO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px; white-space:nowrap'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
										  	"<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
											$secAction);

				/*$output['data'][] 	= array("<strong>$WO_CODE</strong>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SupplierName." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$SPLDESC."
											  		</p>
											  	</div>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$WO_DATEV."
										  		</p>
										  	</div>
										  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  			".$JOBDESCD."
										  	</div>",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);*/
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function update_inb_sub()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN342';
			$MenuCode			= 'MN342';
			$data["MenuCode"] 	= 'MN342';
			$data["MenuApp"] 	= 'MN342';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_GTOTAL'] 	= $getwodata->WO_GTOTAL;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['WO_RETP'] 	= $getwodata->WO_RETP;
			$data['default']['WO_RETVAL'] 	= $getwodata->WO_RETVAL;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['WO_GTOTAL'] 	= $getwodata->WO_GTOTAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN342';
			$data["MenuCode"] 	= 'MN342';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN342';
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

			$this->load->view('v_project/v_spk/sub_spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function cr3473d0c_m4d()	// Create Document
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Buat Draft Baru";
				$data["page"] 		= "Halaman";
			}
			else
			{
				$data["h1_title"] 	= "Create New Draft";
				$data["page"] 		= "Page";
			}

			$getwodata 					= $this->m_spk->get_WO_by_number($WO_NUM)->row();
			$PRJCODE					= $getwodata->PRJCODE;
			$data['def']['PRJCODE'] 	= $getwodata->PRJCODE;
			$data['def']['WOP_NUM'] 	= $getwodata->WO_NUM;
			$data['def']['WOP_CODE'] 	= $getwodata->WO_CODE;

			$num_rows 						= $this->m_spk->get_WOP_count($WO_NUM);
			if($num_rows > 0)
			{
				$getwopdata 				= $this->m_spk->get_WOP_by_number($WO_NUM)->row();
				$PRJCODE					= $getwopdata->PRJCODE;
				$data['def']['PRJCODE'] 	= $getwopdata->PRJCODE;
				$data['def']['WOP_NUM'] 	= $getwopdata->WOP_NUM;
				$data['def']['WOP_CODE'] 	= $getwopdata->WOP_CODE;
				$data['def']['WOP_TITLE'] 	= $getwopdata->WOP_TITLE;
				$data['def']['WOP_PAGE1'] 	= $getwopdata->WOP_PAGE1;
				$data['def']['WOP_PAGE2'] 	= $getwopdata->WOP_PAGE2;
				$data['def']['WOP_PAGE3'] 	= $getwopdata->WOP_PAGE3;
				$data['def']['WOP_PAGE4']	= $getwopdata->WOP_PAGE4;
				$data['def']['WOP_PAGE5']	= $getwopdata->WOP_PAGE5;
			}
			else
			{
				$data['def']['WOP_TITLE'] 	= '';
				$data['def']['WOP_PAGE1'] 	= '';
				$data['def']['WOP_PAGE2'] 	= '';
				$data['def']['WOP_PAGE3'] 	= '';
				$data['def']['WOP_PAGE4']	= '';
				$data['def']['WOP_PAGE5']	= '';
			}

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
				$TTR_CATEG		= 'C-DOC';

				$this->load->model('m_updash/m_updash', '', TRUE);
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_project/v_spk/spk_createdoc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function pr1n7d0c_e()
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
      	$data['appName'] = $appName;
      	$data['comp_add'] = $therow->comp_add;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$WO_NUM		= $_GET['id'];
		$WO_NUM		= $this->url_encryption_helper->decode_url($WO_NUM);

		if ($this->session->userdata('login') == TRUE)
		{
			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_PAYNOTE'] 	= $getwodata->WO_PAYNOTE;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

      		$data['countwodet'] = $this->m_spk->count_WODET_by_number($WO_NUM);
      		$data['vwodet']   = $this->m_spk->get_WODET_by_number($WO_NUM);

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
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
			
			// if($WO_CATEG == 'SALT')
			// 	$this->load->view('v_project/v_spk/spk_printdoc_salt', $data);
			// else
			// 	$this->load->view('v_project/v_spk/spk_printdoc_sub', $data);

			$this->load->view('v_project/v_spk/spk_printdoc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function pr1n7d0c_m4d()		// Khusus mandor
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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
			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
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

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
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

			$this->load->view('v_project/v_spk/spk_printdoc_mdr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r3q_70ls_pr1n7()
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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
			$getwodata 						= $this->m_spk->get_WOTLS_by_number($WO_NUM)->row();
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
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			//$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			//$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN238';
			$data["MenuCode"] 	= 'MN238';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN239';
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

			$this->load->view('v_project/v_spk/spk_req_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function inbox_er() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst_4L7/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_4L7() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN354';
				$MenuCode			= 'MN354';
				$data["MenuCode"] 	= 'MN354';
				$data["MenuApp"] 	= 'MN354';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN354';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/ibx1_er/?id=";

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

	function ibx1_er() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN354';
			$MenuCode			= 'MN354';
			$data["MenuCode"] 	= 'MN354';
			$data["MenuApp"] 	= 'MN354';
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK4L7/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx_er($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb_er($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_er/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 		= 'MN354';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN354';
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

			$this->load->view('v_project/v_spk/er_spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : SEARCHING METHOD --------------------
		function f4n7_5rcH1nB5pK4L7()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/ibx1_er/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataTLS_1n2() // GOOD
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
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataCTLS_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLTLS_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_DATEV 		= $WO_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";

				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
    		
				$WO_VALUE		= $dataI['WO_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up4t_ibx1_er/?id='.$this->url_encryption_helper->encode_url($CollID));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1 || $WO_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN354';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$WO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
										  	"<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
											$secAction);

				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("<strong>$WO_CODE</strong>",
									  	"<div style='white-space:nowrap'>
										  	<strong><i class='fa fa-user margin-r-5'></i> ".$SupplierName." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$SPLDESC."
										  		</p>
										  	</div>
										  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$WO_CATEGD."
										  		</p>
										  	</div>
									  	</div>",
									  	"<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
								  		<div>
									  		<p class='text-muted' style='margin-left: 15px'>
									  			".$WO_DATEV."
									  		</p>
									  	</div>
									  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
								  		<div class='text-muted' style='margin-left: 18px'>
									  			".$JOBDESCD."
									  	</div>",
									  $empName,
									  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
									  $secAction);*/


			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function up4t_ibx1_er()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN354';
			$MenuCode			= 'MN354';
			$data["MenuCode"] 	= 'MN354';
			$data["MenuApp"] 	= 'MN354';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['WO_RETP'] 	= $getwodata->WO_RETP;
			$data['default']['WO_RETVAL'] 	= $getwodata->WO_RETVAL;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['WO_GTOTAL'] 	= $getwodata->WO_GTOTAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN354';
			$data["MenuCode"] 	= 'MN354';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN354';
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

			$this->load->view('v_project/v_spk/er_spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function r3q_t0ls_1a() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1a/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function r3q_70ls_1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Request - Tools";
			}
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN355';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id=";

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

	function r3q_70ls_1ll1a() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOTLS($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOTLS($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Alat";
			}
			else
			{
				$data["h1_title"] 	= "Request - Tools";
			}

			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_project/c_s180d0bpk/r3q_70ls_1dd1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/r3q_70ls_1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN355';
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

			$this->load->view('v_project/v_spk/spk_req_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	// -------------------- START : FUNCTION TO SEARCH ENGINE --------------------
		function f4n7_5rcH()
		{
			$gEn5rcH		= $_POST['gEn5rcH'];
			$mxLS			= $_POST['maxLimDf'];
			$mxLF			= $_POST['maxLimit'];
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE~$mxLS~$mxLF";
			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : FUNCTION TO SEARCH ENGINE --------------------

  	function get_AllDataRT() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		// START : FOR SERVER-SIDE
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_spk->get_AllDataCRT($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLRT($PRJCODE, $search, $length,$start);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM		= $dataI['WO_NUM'];
				$WO_CODE	= $dataI['WO_CODE'];

				$WO_DATE	= $dataI['WO_DATE'];
				$WO_DATEV	= date('d M Y', strtotime($WO_DATE));

				$WO_NOTE	= $dataI['WO_NOTE'];
				$WO_NOTED	= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";

				$WO_STAT	= $dataI['WO_STAT'];
				$WO_REFNO	= $dataI['WO_REFNO'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$JOBCODEID	= $dataI['JOBCODEID'];

				// CARI TOTAL REGUSEST BUDGET APPROVED
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/r3q_70ls_up1a/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/r3q_70ls_pr1n7/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   </label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
							   		<i class='glyphicon glyphicon-print'></i>
							   </a>
							   </label>";
				}

				$output['data'][] = array("<label style='white-space:nowrap'>".$dataI['WO_CODE']."</label>",
										  $WO_DATEV,
										  "$JOBDESC$WO_NOTED",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function r3q_70ls_1dd1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

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

			$data['PRJCODE'] 	= $PRJCODE;
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_project/c_s180d0bpk/add_req_process');
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/get_all_PR/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';
			$data["MenuCode1"] 	= 'MN356';
			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN355';
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

			$this->load->view('v_project/v_spk/spk_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function add_req_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_STARTD')));
				$Patt_Year	= date('Y',strtotime($this->input->post('WO_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('WO_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('WO_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$WO_NOTE 		= addslashes($this->input->post('WO_NOTE'));
			$WO_STAT 		= $this->input->post('WO_STAT');

			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLPR_REFNO	= "$PR_REFNO";
					}
					else
					{
						$COLPR_REFNO	= "$COLPR_REFNO~$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;

			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN355';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;

				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$WO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE

			$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
									'WO_CODE' 		=> $WO_CODE,
									'WO_DATE'		=> $WO_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NOTE'		=> $WO_NOTE,
									'WO_VALUE'		=> $WO_VALUE,
									'WO_STAT'		=> $WO_STAT,
									'WO_CREATER'	=> $DefEmp_ID,
									'WO_CREATED'	=> date('Y-m-d H:i:s'),
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_spk->addWOTLS($projWOH);

			foreach($_POST['data'] as $d)
			{
				$d['WO_NUM']	= $WO_NUM;
				$WO_TOTAL2	= $d['WO_TOTAL2'];
				if($WO_TOTAL2 == '')
					$d['WO_TOTAL2']	= 0;

				$this->db->insert('tbl_woreq_detail',$d);
			}

			// UPDATE DETAIL
				$this->m_spk->updateDetWOTLS($WO_NUM, $PRJCODE, $WO_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('WO_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_woreq_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				//$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_woreq_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN355';
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

			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function r3q_70ls_up1a() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
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
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_req_process');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WOTLS_by_number($WO_NUM)->row();
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
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN355';
			$data["MenuCode"] 	= 'MN355';
			$data["MenuCode1"] 	= 'MN356';
			$data['vwDocPatt'] 	= $this->m_spk->getDataDocPat($MenuCode)->result();

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN355';
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

			$this->load->view('v_project/v_spk/spk_req_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_req_process() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$WO_STAT 		= $this->input->post('WO_STAT'); // 1 = New, 2 = confirm, 3 = Close

			$WO_NUM 		= $this->input->post('WO_NUM');
			$WO_CODE 		= $this->input->post('WO_CODE');
			//setting WO Date
			$WO_DATE		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
			$WO_STARTD		= date('Y-m-d',strtotime($this->input->post('WO_DATE')));
				$Patt_Year	= date('Y',strtotime($this->input->post('WO_DATE')));
				$Patt_Month	= date('m',strtotime($this->input->post('WO_DATE')));
				$Patt_Date	= date('d',strtotime($this->input->post('WO_DATE')));
			$WO_ENDD		= date('Y-m-d',strtotime($this->input->post('WO_ENDD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SPLCODE 		= $this->input->post('SPLCODE');
			$WO_DEPT 		= '';
			$WO_CATEG 		= $this->input->post('WO_CATEG');
			$WO_TYPE 		= $this->input->post('WO_TYPE');
			$WO_NOTE 		= addslashes($this->input->post('WO_NOTE'));
			$WO_STAT 		= $this->input->post('WO_STAT');
			//$JOBCODEID	= $this->input->post('JOBCODEID');
			$PRREFNO		= $this->input->post('PR_REFNO');
			$WO_VALUE		= $this->input->post('WO_VALUE');
			if($PRREFNO != '')
			{
				$refStep	= 0;
				foreach ($PRREFNO as $PR_REFNO)
				{
					$refStep	= $refStep + 1;
					if($refStep == 1)
					{
						$COLPR_REFNO	= "$PR_REFNO";
					}
					else
					{
						$COLPR_REFNO	= "$COLPR_REFNO$PR_REFNO";
					}
				}
			}
			else
			{
				$COLPR_REFNO	= '';
			}
			$JOBCODEID		= $COLPR_REFNO;

			$projWOH 		= array('WO_NUM' 		=> $WO_NUM,
									'WO_CODE' 		=> $WO_CODE,
									'WO_DATE'		=> $WO_DATE,
									'WO_STARTD'		=> $WO_STARTD,
									'WO_ENDD'		=> $WO_ENDD,
									'PRJCODE'		=> $PRJCODE,
									'SPLCODE'		=> $SPLCODE,
									'WO_DEPT'		=> $WO_DEPT,
									'WO_CATEG'		=> $WO_CATEG,
									'WO_TYPE'		=> $WO_TYPE,
									'WO_VALUE'		=> $WO_VALUE,
									'JOBCODEID'		=> $JOBCODEID,
									'WO_NOTE'		=> $WO_NOTE,
									'WO_STAT'		=> $WO_STAT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_spk->updateWOTLS($WO_NUM, $projWOH);

			$this->m_spk->deleteDetailWOTLS($WO_NUM);

			foreach($_POST['data'] as $d)
			{
				$WO_TOTAL2	= $d['WO_TOTAL2'];
				if($WO_TOTAL2 == '')
					$d['WO_TOTAL2']	= 0;

				$this->db->insert('tbl_woreq_detail',$d);
			}

			// UPDATE DETAIL
				$this->m_spk->updateDetWOTLS($WO_NUM, $PRJCODE, $WO_DATE);

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);

				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_wo_header",	// TABLE NAME
										'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				//$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
										'DOC_CODE' 		=> $WO_NUM,
										'DOC_STAT' 		=> $WO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_woreq_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN238';
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

			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1ll1a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function r3q_70ls_1nb() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/pR7_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
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
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Permintaan";
			}
			else
			{
				$data["h1_title"] 	= "Request Approval";
			}

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN356';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/r3q_70ls_1nb_a/?id=";

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

	function r3q_70ls_1nb_a() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;
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
				$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOTLSInx_er($PRJCODE, $key, $DefEmp_ID);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOTLSInb_er($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Permintaan Alat";
				$data["h2_title"] 	= "Persetujuan";
			}
			else
			{
				$data["h1_title"] 	= "Request - Tools";
				$data["h2_title"] 	= "Approval";
			}

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_er/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 		= 'MN356';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN356';
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

			$this->load->view('v_project/v_spk/spk_req_inb', $data);
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
			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1nb_a/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllDataRT_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		// START : FOR SERVER-SIDE
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_spk->get_AllDataCRT_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLRT_1n2($PRJCODE, $search, $length,$start);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM		= $dataI['WO_NUM'];
				$WO_CODE	= $dataI['WO_CODE'];

				$WO_DATE	= $dataI['WO_DATE'];
				$WO_DATEV	= date('d M Y', strtotime($WO_DATE));

				$WO_NOTE	= $dataI['WO_NOTE'];
				$WO_NOTED	= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";

				$WO_STAT	= $dataI['WO_STAT'];
				$WO_REFNO	= $dataI['WO_REFNO'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				$JOBCODEID	= $dataI['JOBCODEID'];

				// CARI TOTAL REGUSEST BUDGET APPROVED
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;				
				}

				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/r3q_70ls_1nbup4t/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint	= site_url('c_project/c_s180d0bpk/r3q_70ls_pr1n7/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_PO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_PO/?id='.$WO_NUM;

				$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   <label style='white-space:nowrap'>
							   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
									<i class='glyphicon glyphicon-pencil'></i>
							   </a>
							   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printDocument(".$noU.")'>
							   		<i class='glyphicon glyphicon-print'></i>
							   </a>
							   </label>";

				$output['data'][] = array($dataI['WO_CODE'],
										  $WO_DATEV,
										  "$JOBDESC$WO_NOTED",
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function r3q_70ls_1nbup4t()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_req_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WOTLS_by_number($WO_NUM)->row();
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
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN356';
			$data["MenuCode"] 	= 'MN356';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN356';
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

			$this->load->view('v_project/v_spk/spk_req_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function update_req_process_inb() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
    	$this->load->model('m_updash/m_updash', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			date_default_timezone_set("Asia/Jakarta");

			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

			$WO_NUM 		= $this->input->post('WO_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$WO_STAT 		= $this->input->post('WO_STAT');
			
			$AH_CODE		= $WO_NUM;
  			$AH_APPLEV		= $this->input->post('APP_LEVEL');
  			$AH_APPROVER	= $DefEmp_ID;
  			$AH_APPROVED	= date('Y-m-d H:i:s');
  			$AH_NOTES		= addslashes($this->input->post('WO_NOTE2'));
  			$AH_ISLAST		= $this->input->post('IS_LAST');

			if($WO_STAT == 3)
			{
        // START : SAVE APPROVE HISTORY

  				$AH_CODE		= $WO_NUM;
  				$AH_APPLEV		= $this->input->post('APP_LEVEL');
  				$AH_APPROVER	= $DefEmp_ID;
  				$AH_APPROVED	= date('Y-m-d H:i:s');
  				$AH_NOTES		= $this->input->post('WO_NOTE2');
  				$AH_ISLAST		= $this->input->post('IS_LAST');

  				$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
  										'AH_APPLEV'		=> $AH_APPLEV,
  										'AH_APPROVER'	=> $AH_APPROVER,
  										'AH_APPROVED'	=> $AH_APPROVED,
  										'AH_NOTES'		=> $AH_NOTES,
  										'AH_ISLAST'		=> $AH_ISLAST);
  				$this->m_updash->insAppHist($insAppHist);

  			// END : SAVE APPROVE HISTORY

				$projWOH		= array('WO_STAT'	=> 7);		// Default ke waiting jika masih ada approver yang lain
				$this->m_spk->updateWOTLS($WO_NUM, $projWOH);
			}
			else
			{
				$projWOH		= array('WO_STAT'	=> $WO_STAT);		// Default ke waiting jika masih ada approver yang lain
				$this->m_spk->updateWOTLS($WO_NUM, $projWOH);
			}

			if($WO_STAT == 3 && $AH_ISLAST == 1)
			{
				$projWOH 		= array('WO_APPROVER'	=> $DefEmp_ID,
										'WO_APPROVED'	=> date('Y-m-d H:i:s'),
										'WO_NOTE2'		=> addslashes($this->input->post('WO_NOTE2')),
										'WO_STAT'		=> $WO_STAT);
				$this->m_spk->updateWOTLS($WO_NUM, $projWOH);

				// UPDATE JOBDETAIL ITEM
				// if($WO_STAT == 3)
				// {
				// 	//$this->m_spk->updateWOTLSDet($WO_NUM, $PRJCODE); hidden by DIAN on Aug/7/18
				// }

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);

					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $WO_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "WO",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_woreq_header",	// TABLE NAME
											'KEY_NAME'		=> "WO_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "WO_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $WO_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_WO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_WO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_WO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_WO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_WO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_WO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_WO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					//$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> $WO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_woreq_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
											'DOC_CODE' 		=> $WO_NUM,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_woreq_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : CLEAR HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				if($WO_STAT == 4)
				{
					$cllPar = array('AH_CODE' 		=> $WO_NUM,
									'AH_APPROVER'	=> $DefEmp_ID);
					$this->m_updash->clearHist($cllPar);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "WO_NUM",
												'DOC_CODE' 		=> $WO_NUM,
												'DOC_STAT' 		=> $WO_STAT,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_woreq_header");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}
			// END : CLEAR HISTORY

      if($WO_STAT == 5)
      {
        $cllPar = array('AH_CODE' 		=> $WO_NUM,
                'AH_APPROVER'	=> $DefEmp_ID);

        // START : UPDATE STATUS
          $completeName 	= $this->session->userdata['completeName'];
          $paramStat 		= array('PM_KEY' 		=> "WO_NUM",
                      'DOC_CODE' 		=> $WO_NUM,
                      'DOC_STAT' 		=> $WO_STAT,
                      'PRJCODE' 		=> $PRJCODE,
                      'CREATERNM'		=> $completeName,
                      'TBLNAME'		=> "tbl_woreq_header");
          $this->m_updash->updateStatus($paramStat);
        // END : UPDATE STATUS
      }

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $WO_NUM;
				$MenuCode 		= 'MN239';
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

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}

			$url			= site_url('c_project/c_s180d0bpk/r3q_70ls_1nb_a/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
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

			$url		= site_url('c_project/c_s180d0bpk/ll_4p1/?id='.$this->url_encryption_helper->encode_url($collData));
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
			$data['h2_title'] 		= 'Select DP Number';
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'SPK';
			$data['PRJCODE']		= $PRJCODE;
			$data['SPLCODE']		= $SPLCODE;

			$this->load->view('v_project/v_spk/spk_seldp', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataSRV() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataSRVC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataSRVL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$disabledB	= 0;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
					}

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= "";
					elseif($JOBLEV == 2)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 3)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 4)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 5)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 6)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 7)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 8)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 9)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 10)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 11)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					elseif($JOBLEV == 12)
						$spaceLev 	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					else
						$spaceLev 	= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."' onClick='pickThis1(this);'/>";
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
				$JobView		= "$spaceLev $JOBCODEID $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<span ".$CELL_COL.">".$JobView."</span>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

  	function get_AllDataH() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataHC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataHL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBDESC		= $dataI['JOBDESC'];
				$JOBLEV 		= $dataI['JOBLEV'];
				$ISLAST			= $dataI['ISLAST'];
				$ISLAST_BOQ		= $dataI['ISLAST_BOQ'];

				// IS LAST SETT
					if($ISLAST_BOQ == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 40, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$disabledB	= 0;
						$JOBDESC1 	= wordwrap($JOBDESC, 40, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
					}

				// SPACE
					if($JOBLEV == 1)
						$spaceLev 	= 0;
					elseif($JOBLEV == 2)
						$spaceLev 	= 12;
					elseif($JOBLEV == 3)
						$spaceLev 	= 24;
					elseif($JOBLEV == 4)
						$spaceLev 	= 36;
					elseif($JOBLEV == 5)
						$spaceLev 	= 48;
					elseif($JOBLEV == 6)
						$spaceLev 	= 60;
					elseif($JOBLEV == 7)
						$spaceLev 	= 72;
					elseif($JOBLEV == 8)
						$spaceLev 	= 84;
					elseif($JOBLEV == 9)
						$spaceLev 	= 96;
					elseif($JOBLEV == 10)
						$spaceLev 	= 108;
					elseif($JOBLEV == 11)
						$spaceLev 	= 120;
					elseif($JOBLEV == 12)
						$spaceLev 	= 132;
					else
						$spaceLev 	= "";

					$JOBLEV 	= 18;
				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='radio' name='chkJH' value='".$JOBCODEID."|".$JOBDESC."|".$PRJCODE."' onClick='pickThisJH(this);'/>";
					}
					else
					{
						$chkBox	= "<input type='radio' name='chkJH' value='' style='display: none' />";
					}

				$secUpd			= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
                
				$secPrint		= 	"<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   	</a>
									</label>";
				
				$JobView		= "<div style='margin-left: ".$spaceLev."px; font-style: italic'>$JOBCODEID $JOBDESC1</div>";

				$output['data'][] 	= array($chkBox,
											"<span ".$CELL_COL.">".$JobView."</span>");

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

  	function get_AllDataD() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$JOBCODEID	= $_GET['JOBCODEID'];

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
			$num_rows 		= $this->m_spk->get_AllDataDC($PRJCODE, $JOBCODEID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataDL($PRJCODE, $JOBCODEID, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 40, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 40, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 40, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chkJD' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."' onClick='pickThisJD(this);'/>";
					}
					else
					{
						$chkBox	= "<input type='checkbox' name='chkJD' value='' style='display: none' />";
					}

				$secUpd			= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
                
				$secPrint		= 	"<label style='white-space:nowrap'>
									   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
									   	</a>
									</label>";
				$JobView		= "$JOBCODEID $JOBDESC1";

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

  	function get_AllDataM() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITMML($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

  	function get_AllDataS() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

  	function get_AllDataU() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMUC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITMUL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				$disabledB 		= 1;
				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

  	function get_AllDataOVH() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMOVHC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITMOVHL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

	function getPPN()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$TAX_NUM 	= $_POST['TAX_NUM'];

		$sqlTax 	= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAX_NUM'";
	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxPerc = $rowTax->TAXLA_PERC;
	    endforeach;

		echo $taxPerc;
	}

	function getPPH()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$TAX_NUM 	= $_POST['TAX_NUM'];

		$sqlTax 	= "SELECT TAXLA_PERC FROM tbl_tax_la WHERE TAXLA_NUM = '$TAX_NUM'";
	    $resTax     = $this->db->query($sqlTax)->result();
	    foreach($resTax as $rowTax) :
	        $taxPerc = $rowTax->TAXLA_PERC;
	    endforeach;

		echo $taxPerc;
	}

	function cancelItem()
	{
		date_default_timezone_set("Asia/Jakarta");

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$WO_NUM     = $colExpl[1];
		$PRJCODE    = $colExpl[2];
		$ITM_CODE   = $colExpl[3];
		$ITM_NAME   = $colExpl[4];
		$JOBPARDESC = $colExpl[5];
		$WO_VOLM    = $colExpl[6];
		$OPN_VOLM   = $colExpl[7];
		$REM_VOLWO  = $colExpl[8];
		$ITM_UNIT   = $colExpl[9];
		$WO_ID   	= $colExpl[10];
		$WO_CVOL   	= $colExpl[11];		// Vol. yang akan dibatalkan

		$s_01 			= "SELECT JOBCODEID, WO_VOLM, ITM_PRICE, WO_TOTAL, OPN_VOLM, OPN_AMOUNT, TAXPERC1, TAXPERC2
							FROM tbl_wo_detail
							WHERE WO_ID = $WO_ID AND ITM_CODE = '$ITM_CODE' AND WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
		$r_01 			= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01) :
			$JOBCODEID 	= $rw_01->JOBCODEID;
			$WO_VOLM 	= $rw_01->WO_VOLM;
			$WO_VOLMN 	= $WO_VOLM - $WO_CVOL;				// VOLUME BARU

			$ITM_PRICE 	= $rw_01->ITM_PRICE;
			$WO_CTOTAL 	= $WO_CVOL * $ITM_PRICE;			// NILAI PEMBATALAN

			$WO_TOTAL 	= $rw_01->WO_TOTAL;
			$WO_TOTALN 	= $WO_TOTAL - $WO_CTOTAL;			// NILAI WO BARU PER ITEM

			$TAXPERC1 	= $rw_01->TAXPERC1;
			$TAXPERC2 	= $rw_01->TAXPERC2;

			$TAXPRICE1 	= $TAXPERC1 * $WO_TOTALN / 100;
			$TAXPRICE2 	= $TAXPERC2 * $WO_TOTALN / 100;

			$WO_TOTAL2 	= $WO_TOTALN + $TAXPRICE1 - $TAXPRICE2;
			// UPDATE STATUS SPK
				$s_02 	= 	"UPDATE tbl_wo_detail SET WO_VOLM = $WO_VOLMN, WO_TOTAL = $WO_TOTALN, WO_CVOL = WO_CVOL + $WO_CVOL,
									WO_CAMN = WO_CAMN + $WO_CTOTAL,TAXPRICE1 = $TAXPRICE1, TAXPRICE2 = $TAXPRICE2, WO_TOTAL2 = $WO_TOTAL2
								WHERE WO_ID = $WO_ID AND ITM_CODE = '$ITM_CODE' AND WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_02);

			// PENGEMBALIAN SISA VOLUME KE BUDGET
				$s_03 	= 	"UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM - $WO_CVOL, REQ_AMOUNT = REQ_AMOUNT - $WO_CTOTAL
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_03);

			// DELETE IF WO_VOLM = 0
				$s_04 	= 	"DELETE FROM tbl_wo_detail WHERE ITM_CODE = '$ITM_CODE' AND WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($s_04);

			// ADD HISTORY
				$Emp_ID = $this->session->userdata['Emp_ID'];
				$s_05 	= 	"INSERT INTO tbl_wo_detail_canc (WO_ID, WO_NUM, WO_CODE, WO_DATE, PRJCODE, WO_REFNO, JOBCODEDET, JOBCODEID, ITM_CODE, SNCODE, ITM_UNIT, 
								WO_VOLM, ITM_PRICE, WO_DISC, WO_DISCP, WO_TOTAL, WO_CVOL, WO_CAMN, WO_DESC, TAXCODE1, TAXPERC1, TAXPRICE1,
								TAXCODE2, TAXPERC2, TAXPRICE2, WO_TOTAL2, ITM_BUDG_VOL, ITM_BUDG_AMN, OPN_VOLM, OPN_VOLM1, OPN_AMOUNT,
								WO_VOLMB, WO_VOLMB1, WO_TOTALB, ISCLOSE, CANC_EMP)
							SELECT WO_ID, WO_NUM, WO_CODE, WO_DATE, PRJCODE, WO_REFNO, JOBCODEDET, JOBCODEID, ITM_CODE, SNCODE, ITM_UNIT, 
								WO_VOLM, ITM_PRICE, WO_DISC, WO_DISCP, WO_TOTAL, WO_CVOL, WO_CAMN, WO_DESC, TAXCODE1, TAXPERC1, TAXPRICE1,
								TAXCODE2, TAXPERC2, TAXPRICE2, WO_TOTAL2, ITM_BUDG_VOL, ITM_BUDG_AMN, OPN_VOLM, OPN_VOLM1, OPN_AMOUNT,
								WO_VOLMB, WO_VOLMB1, WO_TOTALB, ISCLOSE, '$Emp_ID'
								FROM tbl_wo_detail WHERE WO_ID = $WO_ID AND ITM_CODE = '$ITM_CODE' AND WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_05);
		endforeach;

		// RECOUNT AMOUNT FOR THIS SPK
		$TOT_WO 		= 0;
		$TOT_TAX1 		= 0;
		$TOT_TAX2 		= 0;
		$TOT_CAMN 		= 0;
		$s_06 			= "SELECT WO_TOTAL, TAXPERC1, TAXPERC2, WO_CAMN FROM tbl_wo_detail WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
		$r_06 			= $this->db->query($s_06)->result();
		foreach($r_06 as $rw_06) :
			$WO_TOTAL 	= $rw_06->WO_TOTAL;
			$TAXPERC1 	= $rw_06->TAXPERC1;
			$TAXPERC2 	= $rw_06->TAXPERC2;
			$WO_CAMN 	= $rw_06->WO_CAMN;
			$TOT_CAMN 	= $TOT_CAMN + $WO_CAMN;

			$TOT_WO 	= $TOT_WO + $WO_TOTAL;
			$TOT_TAX1 	= $TOT_TAX1 + $TAXPRICE1;
			$TOT_TAX2 	= $TOT_TAX2 + $TAXPRICE2;
		endforeach;
		$WO_GTOTAL 	= $TOT_WO + $TOT_TAX1 - $TOT_TAX2;

		$s_07 			= "UPDATE tbl_wo_header SET WO_VALUE = $TOT_WO, WO_VALUEAPP = $TOT_WO,
								WO_VALPPN = $TOT_TAX1, WO_VALPPH = $TOT_TAX2, WO_GTOTAL = $WO_GTOTAL, WO_TCAMN = $TOT_CAMN
							WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE'";
		$this->db->query($s_07);

		echo "Volume item $ITM_NAME sudah dikembalikan ke budget.";
	}

	function get_AllDataA() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMAC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITMAL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

	function get_AllDataO() // GOOD
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
			$num_rows 		= $this->m_spk->get_AllDataITMOC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITMOL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

	function get_AllDataITM() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		//setlocale(LC_ALL, 'id-ID', 'id_ID');
		setlocale(LC_ALL, 'en_EN');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$ITMCAT 	= $_GET['ITMCAT'];

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
			$num_rows 		= $this->m_spk->get_AllDataITMC($PRJCODE, $ITMCAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_spk->get_AllDataITML($PRJCODE, $ITMCAT, $search, $length, $start, $order, $dir);
								
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
				$JOBCODEID 		= $dataI['JOBCODEID'];				// 1
				$JOBPARENT 		= $dataI['JOBPARENT'];
				$JOBCODE 		= $dataI['JOBCODE'];				// 2
				$ITM_CODE 		= $dataI['ITM_CODE'];				// 4
				$JOBDESC		= $dataI['JOBDESC'];				// 5
				$serialNumber	= '';								// 6
				$ITM_UNIT 		= $dataI['ITM_UNIT'];				// 7
				$UNITTYPE		= strtoupper($ITM_UNIT);			// 
				$ITM_PRICE		= $dataI['ITM_PRICE'];				// 8
				$ITM_VOLM 		= $dataI['ITM_VOLM'];				//
				$ADD_VOLM		= $dataI['ADD_VOLM'];				// 
				$ITM_VOLM_QTY	= $ITM_VOLM + $ADD_VOLM;			// 9
				if($ITM_VOLM_QTY == '')
					$ITM_VOLM_QTY	= 0;
				$ITM_BUDG 		= $dataI['ITM_BUDG'];				//
				$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];			//
				$ITM_VOLM_AMN	= $ITM_BUDG + $ADD_JOBCOST;			// 10
				if($ITM_VOLM_AMN == '')
					$ITM_VOLM_AMN	= 0;
				$ITM_STOCK 		= $dataI['ITM_STOCK'];				//
				$ITM_STOCK_AM 	= $dataI['ITM_STOCK_AM'];			//
				$ITM_USED 		= $dataI['ITM_USED'];				// 11
				$ITM_USED_AM 	= $dataI['ITM_USED_AM'];			// 12
				$tempTotMax		= $ITM_VOLM - $ITM_USED;			// 13
				
				if($UNITTYPE == 'LS')
					$tempTotMax	= $ITM_BUDG - $ITM_USED_AM;			// 13

				$PO_VOLM 		= $dataI['PO_VOLM'];				// 14
				$PO_AMOUNT		= $dataI['PO_AMOUNT'];				// 15
				$WO_QTY			= $dataI['WO_QTY'];					// 16
				$WO_AMOUNT		= $dataI['WO_AMOUNT'];				// 17
				$OPN_QTY		= $dataI['OPN_QTY'];				// 18
				$OPN_AMOUNT		= $dataI['OPN_AMOUNT'];				// 19

				// GET WO TOTAL IN (2,3)
					$WO_QTY2		= 0;
					$WO_AMOUNT2		= 0;
					$sqlTOTBUDG		= "SELECT SUM(A.WO_VOLM) AS TOTWOQTY,
											SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT
										FROM tbl_wo_detail A
											INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
												AND B.PRJCODE = '$PRJCODE'
										WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
											AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT IN (2)";
					$resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
					foreach($resTOTBUDG as $rowTOTBUDG) :
						$WO_QTY2	= $rowTOTBUDG->TOTWOQTY;
						$WO_AMOUNT2	= $rowTOTBUDG->TOTWOAMOUNT;
					endforeach;
					$TOT_USED_QTY 	= $WO_QTY + $WO_QTY2;			// 20
					$TOT_USED_AMN 	= $WO_AMOUNT + $WO_AMOUNT2;		// 21

					$REMREQ_QTY		= $ITM_VOLM_QTY - $TOT_USED_QTY;
					$REMREQ_AMN		= $ITM_VOLM_AMN - $TOT_USED_AMN;
					$ITM_STOCK 		= $REMREQ_QTY;					// 22
					$ITM_STOCK_AM 	= $REMREQ_AMN;					// 23
					
					if($UNITTYPE == 'LS')
					{
						$REMREQ_QTY 	= 0;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}
					else
					{
						if($REMREQ_QTY <= 0)
							$disabledB	= 1;
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
					}

					$ITM_REMVOL		= $REMREQ_QTY;
					$ITM_REMAMN		= $REMREQ_AMN;

				$ITM_GROUP		= $dataI['ITM_GROUP'];
				if($ITM_GROUP == 'M')
					$disabledB	= 1;

				$itemConvertion	= 1;
				$REQ_VOLM 		= $dataI['REQ_VOLM'];
				$REQ_AMOUNT		= $dataI['REQ_AMOUNT'];
				if($UNITTYPE == 'LS')
				{
					$ITM_BUDGV	= $ITM_VOLM_AMN;
					$REQ_VOLMV	= $REQ_AMOUNT;
					$TOTWOQTY_V = "";
					if($WO_AMOUNT2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_AMOUNT2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_AMOUNT;
					$ITM_STOCKV	= $REMREQ_AMN;
				}
				else
				{
					$ITM_BUDGV	= $ITM_VOLM_QTY;
					$REQ_VOLMV	= $REQ_VOLM;
					$TOTWOQTY_V = "";
					if($WO_QTY2 > 0)
					{
						$TOTWOQTY_V = 	"<div>
											<p class='text-yellow' style='white-space:nowrap'><i class='fa fa-exclamation-triangle'></i>
									  		".number_format($WO_QTY2, 2)."</p>
									  	</div>";
					}
					$PO_VOLMV	= $PO_VOLM;
					$ITM_STOCKV	= $REMREQ_QTY;
				}

				$ISLAST			= $dataI['ISLAST'];
				$JOBLEV			= $dataI['IS_LEVEL'];

				$ITM_LASTP 		= $dataI['ITM_LASTP'];

				// IS LAST SETT
					if($ISLAST == 0)	// HEADER
					{
						$disabledB	= 1;
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'primary';
						$CELL_COL	= "style='font-weight:bold; white-space:nowrap'";
					}
					else
					{
						$JOBDESC1 	= wordwrap($JOBDESC, 60, "<br>", TRUE);
						$STATCOL	= 'success';
						$CELL_COL	= "style='white-space:nowrap'";
						$disabledB	= 0;
					}

				$JOBPARDESC = "";
				$sqlJDP 	= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$resJDP 	= $this->db->query($sqlJDP)->result();
				foreach($resJDP as $rowJDP) :
					$JOBPARDESC = $rowJDP->JOBDESC;
				endforeach;
				$JOBPARDESC = wordwrap($JOBPARDESC, 60, "<br>", TRUE);

				$ACC_ID_UM		= "";
				$s_ACCID		= "SELECT ACC_ID_UM FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_ACCID		= $this->db->query($s_ACCID)->result();
				foreach($r_ACCID as $rw_ACCID) :
					$ACC_ID_UM	= $rw_ACCID->ACC_ID_UM;
				endforeach;

				// SPACE
					$spaceLev 		= "";

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox	= "<input type='checkbox' name='chk1' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$ITM_VOLM_QTY."|".$ITM_VOLM_AMN."|".$ITM_USED."|".$ITM_USED_AM."|".$tempTotMax."|".$PO_VOLM."|".$PO_AMOUNT."|".$WO_QTY."|".$WO_AMOUNT."|".$OPN_QTY."|".$OPN_AMOUNT."|".$TOT_USED_QTY."|".$TOT_USED_AMN."|".$ITM_STOCK."|".$ITM_STOCK_AM."|".$ITM_REMVOL."|".$ITM_REMAMN."|".$JOBPARDESC."|".$ACC_ID_UM."|".$ITM_LASTP."' onClick='pickThis1(this);'/>";
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

				$output['data'][] 	= array($chkBox,
											"<div style='white-space:nowrap'>
												".$JobView."
											</div>
										  	<div style='margin-left: 18px; font-style: italic'>
										  		<strong><i class='fa fa-feed margin-r-5'></i> ".$JOBPARDESC."</strong>
										  	</div>",
											"<div style='text-align:center;'><span ".$CELL_COL.">".$ITM_UNIT."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_BUDGV, 2)."</span></div>",
											"<div style='text-align:right'><span ".$CELL_COL.">".number_format($REQ_VOLMV, 2).$TOTWOQTY_V."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLMV, 2)."</span></div>",
											"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($ITM_STOCKV, 2)."</span></div>");

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

	function trashFile()
	{
		$WO_NUM		= $this->input->post("WO_NUM");
		$PRJCODE	= $this->input->post("PRJCODE");
		// $index 		= $this->input->post("indexRow");
		$fileName	= $this->input->post("fileName");

		/* Change WO_DOC to array => upd: 2022-09-16
			$arrDOC = explode(", ", $WO_DOC);
			foreach($arrDOC as $r => $val):
				$getarr[] = $val;
			endforeach;

			unset($getarr[$index]); // Delete index array
		---------------------- End Hidden --------------------- */

		$this->m_spk->delUPL_DOC($WO_NUM, $PRJCODE, $fileName); // Delete File
		
		// Delete file in path folder
			$path = "assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE/$fileName";
			unlink($path);
	}

	function downloadFile()
	{
		$this->load->helper('download');

		$fileName 	= $_GET['file'];
		$PRJCODE 	= $_GET['prjCode'];
		$path 		= "assets/AdminLTE-2.0.5/doc_center/uploads/WO_Document/$PRJCODE/$fileName";
		force_download($path, NULL);
	}

 	function inbox_ovh() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		$url			= site_url('c_project/c_s180d0bpk/prj180d0blst_ovh/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function prj180d0blst_ovh() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);

		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$LangID 	= $this->session->userdata['LangID'];
			// GET MENU DESC
				$mnCode				= 'MN457';
				$MenuCode			= 'MN457';
				$data["MenuCode"] 	= 'MN457';
				$data["MenuApp"] 	= 'MN457';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN354';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_project/c_s180d0bpk/ibx1_ovh/?id=";

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

	function ibx1_ovh() // OK
	{
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN457';
			$MenuCode			= 'MN457';
			$data["MenuCode"] 	= 'MN457';
			$data["MenuApp"] 	= 'MN457';
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
				//$data["url_search"] = site_url('c_project/c_s180d0bpk/f4n7_5rcH1nB5pK4L7/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

				//$num_rows 			= $this->m_spk->count_all_WOInx_er($PRJCODE, $key);
				//$data["cData"] 		= $num_rows;
				//$data['vData']		= $this->m_spk->get_all_WOInb_er($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------

			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_project/c_s180d0bpk/inbox_ovh/');
			$data['PRJCODE'] 	= $PRJCODE;
			$data['MenuCode'] 	= 'MN457';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN457';
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

			$this->load->view('v_project/v_spk/ovh_spk_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataovh_1n2() // GOOD
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
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
    		if($TranslCode == 'SPKVal')$SPKVal = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
        endforeach;

		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}

			if($dir != "asc" && $dir != "desc")
			{
            	$dir = "desc";
        	}

			$columns_valid 	= array("WO_CODE",
									"WO_CATEG", 
									"WO_DATE", 
									"WO_NOTE", 
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
			$num_rows 		= $this->m_spk->get_AllDataCovh_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_spk->get_AllDataLovh_1n2($PRJCODE, $search, $length, $start, $order, $dir);

			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$WO_NUM			= $dataI['WO_NUM'];
				$WO_CODE		= $dataI['WO_CODE'];
				$WO_DATE		= $dataI['WO_DATE'];
				$WO_ENDD		= $dataI['WO_ENDD'];
				//$WO_DATEV		= date('d M Y', strtotime($WO_DATE));
				$WO_DATEV1		= strftime('%d %b %Y', strtotime($WO_DATE));
				$WO_ENDDV		= strftime('%d %b %Y', strtotime($WO_ENDD));

				$WO_DATEV 		= $WO_DATEV1." - ".$WO_ENDDV;

				$WO_CATEG		= $dataI['WO_CATEG'];
				$PRJCODE		= $dataI['PRJCODE'];
				$WO_NOTE		= $dataI['WO_NOTE'];
				$WO_NOTED		= '';
				if($WO_NOTE != '')
					$WO_NOTED	= "$WO_NOTE";

				$WO_STAT		= $dataI['WO_STAT'];
				$WO_REFNO		= $dataI['WO_REFNO'];
				$WO_CREATER		= $dataI['WO_CREATER'];
				$WO_ISCLOSE		= $dataI['WO_ISCLOSE'];
				$JOBCODEID		= $dataI['JOBCODEID'];
				$WO_CDOC		= $dataI['WO_CDOC'];
				$JOBDESC		= $dataI['JOBDESC'];
				$SPLCODE		= $dataI['SPLCODE'];
    		
				$WO_VALUE		= $dataI['WO_VALUE'];

				// OPN TOTAL
					$TOTOPNAMN	= 0;
					$sqlTOT_OPN	= "SELECT SUM(A.OPN_AMOUNT) AS TOTOPN_AMN FROM tbl_wo_detail A
									WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
					foreach($resTOT_OPN as $rowTOT_OPN) :
						$TOTOPNAMN	= $rowTOT_OPN->TOTOPN_AMN;
						if($TOTOPNAMN == '')
							$TOTOPNAMN	= 0;
					endforeach;

				$spkD 			= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-bullhorn margin-r-5'></i> ".$SPKVal."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($WO_VALUE, 2)."
									  	</div>";

				$opnameD 		= 	"<div style='white-space:nowrap'>
											<strong><i class='fa fa-check-square-o margin-r-5'></i> ".$QtyOpnamed."</strong>
										</div>
									  	<div style='margin-left: 20px;'>
									  		".number_format($TOTOPNAMN, 2)."
									  	</div>";
				
				$SPLDESC		= '';
				$sqlSPLD		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$resSPLD		= $this->db->query($sqlSPLD)->result();
				foreach($resSPLD as $rowSPLD) :
					$SPLDESC	= $rowSPLD->SPLDESC;		
				endforeach;
				
				$JOBDESCD		= "$JOBDESC$WO_NOTED";
				if($JOBDESCD == '')
				{
					/*$expJOB		= explode("~", $JOBCODEID);
					$JOBCODEID1	= $expJOB[0];
					$JOBDESC1	= '';
					$sqlJOB		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID1'";
					$resJOB		= $this->db->query($sqlJOB)->result();
					foreach($resJOB as $rowJOB) :
						$JOBDESC1 = $rowJOB->JOBDESC;		
					endforeach;
					$JOBDESCD	= $JOBDESC1;*/
					$JOBDESCDV 	= "";
				}
				else
				{
					$JOBDESCDV 	= "<div class='text-muted' style='margin-left: 18px'>
										  		".$JOBDESCD."
										  	</div>";
				}
								
				if($WO_CATEG == 'U')
				{
					$WO_CATEGD	= 'Upah';
				}
				elseif($WO_CATEG == 'S')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				elseif($WO_CATEG == 'A')
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				else
				{
					$WO_CATEGD 	= 'Overhead';
				}

				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "$PRJCODE~$WO_NUM";
				$secUpd		= site_url('c_project/c_s180d0bpk/up4t_ibx1_ovh/?id='.$this->url_encryption_helper->encode_url($CollID));
				// if($WO_CATEG == 'MDR')
				// 	$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_m4d/?id='.$this->url_encryption_helper->encode_url($WO_NUM));
				// else
					$secPrint	= site_url('c_project/c_s180d0bpk/pr1n7d0c_e/?id='.$this->url_encryption_helper->encode_url($WO_NUM));

				$CollID		= "$WO_NUM~$PRJCODE";
				$secDel_WO 	= base_url().'index.php/c_project/c_s180d0bpk/trash_WO/?id='.$WO_NUM;

				if($WO_STAT == 1 || $WO_STAT == 4)
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_WO."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$MNCODE	= 'MN354';
				$APPL 	= "";
				$s_00	= "SELECT DISTINCT A.APPROVER_1, A.APP_STEP, B.AS_APPEMPNM FROM tbl_docstepapp_det A
							INNER JOIN tbl_alert_schedzule B ON A.APPROVER_1 = B.AS_APPEMP
							WHERE A.PRJCODE = '$PRJCODE' AND B.AS_DOCCODE = '$WO_CODE' GROUP BY A.APP_STEP ORDER BY A.APP_STEP";
				$r_00 	= $this->db->query($s_00)->result();
				foreach($r_00 as $rw_00) :
					$APPROVER 	= $rw_00->APPROVER_1;
					$arr 		= explode(' ',trim($rw_00->AS_APPEMPNM));
					//$AS_APPEMP	= $arr[0];
					$AS_APPEMP	= $rw_00->AS_APPEMPNM;
					$APPL 		= "<small class='label bg-blue' style='font-style: italic'><i class='fa fa-spinner'></i> $AS_APPEMP</small>";
				endforeach;

				$output['data'][] 	= array("<div style='white-space:nowrap'>
												<strong>$WO_CODE</strong><br>
												<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 15px'>
											  			".$WO_DATEV."
											  		</p>
											  	</div>
											</div>",
										  	"<div style='white-space:nowrap'>
											  	<strong><i class='fa fa-user margin-r-5'></i> ".$SPLDESC." / ".$SPLCODE." </strong><br>
											  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
										  		<div>
											  		<p class='text-muted' style='margin-left: 18px'>
											  			".$WO_CATEGD."
											  		</p>
											  	</div>
										  	</div>",
										  	"<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
									  		".$JOBDESCDV."
										  	<strong><i class='fa fa-paperclip margin-r-5'></i> No. SK </strong>
									  		<div class='text-muted' style='margin-left: 18px'>
										  		".$WO_REFNO."
										  	</div>",
											"$spkD $opnameD",
											$empName,
										  	"<div style='white-space:nowrap'>
												<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>
											</div>
											<strong></i>$APPL</strong>",
											$secAction);

				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("<strong>$WO_CODE</strong>",
									  	"<div style='white-space:nowrap'>
										  	<strong><i class='fa fa-user margin-r-5'></i> ".$SupplierName." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$SPLDESC."
										  		</p>
										  	</div>
										  	<strong><i class='fa fa-cog margin-r-5'></i> ".$Category." </strong>
									  		<div>
										  		<p class='text-muted' style='margin-left: 15px'>
										  			".$WO_CATEGD."
										  		</p>
										  	</div>
									  	</div>",
									  	"<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
								  		<div>
									  		<p class='text-muted' style='margin-left: 15px'>
									  			".$WO_DATEV."
									  		</p>
									  	</div>
									  	<strong><i class='fa fa-pencil margin-r-5'></i> ".$Description." </strong>
								  		<div class='text-muted' style='margin-left: 18px'>
									  			".$JOBDESCD."
									  	</div>",
									  $empName,
									  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
									  $secAction);*/


			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function up4t_ibx1_ovh()
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_project/m_spk/m_spk', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		// GET MENU DESC
			$mnCode				= 'MN457';
			$MenuCode			= 'MN457';
			$data["MenuCode"] 	= 'MN457';
			$data["MenuApp"] 	= 'MN457';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$WO_NUM		= $EXTRACTCOL[1];
			
			$docPatternPosition = 'Especially';
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_project/c_s180d0bpk/update_process_inb');

			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$getwodata 						= $this->m_spk->get_WO_by_number($WO_NUM)->row();
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
			$WO_CATEG						= $getwodata->WO_CATEG;
			$data['default']['WO_TYPE'] 	= $getwodata->WO_TYPE;
			//$data['default']['WO_PAYTYPE'] 	= $getwodata->WO_PAYTYPE;
			$data['default']['JOBCODEID'] 	= $getwodata->JOBCODEID;
			$data['default']['WO_NOTE'] 	= $getwodata->WO_NOTE;
			$data['default']['WO_NOTE2'] 	= $getwodata->WO_NOTE2;
			$data['default']['WO_STAT'] 	= $getwodata->WO_STAT;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_MEMO'] 	= $getwodata->WO_MEMO;
			$data['default']['WO_REFNO'] 	= $getwodata->WO_REFNO;
			$data['default']['PRJNAME'] 	= $getwodata->PRJNAME;
			$data['default']['FPA_NUM'] 	= $getwodata->FPA_NUM;
			$data['default']['FPA_CODE'] 	= $getwodata->FPA_CODE;
			$data['default']['WO_QUOT'] 	= $getwodata->WO_QUOT;
			$data['default']['WO_NEGO'] 	= $getwodata->WO_NEGO;
			$data['default']['WO_DPPER'] 	= $getwodata->WO_DPPER;
			$data['default']['WO_DPREF'] 	= $getwodata->WO_DPREF;
			$data['default']['WO_DPREF1'] 	= $getwodata->WO_DPREF1;
			$data['default']['WO_DPVAL'] 	= $getwodata->WO_DPVAL;
			$data['default']['WO_RETP'] 	= $getwodata->WO_RETP;
			$data['default']['WO_RETVAL'] 	= $getwodata->WO_RETVAL;
			$data['default']['WO_VALUE'] 	= $getwodata->WO_VALUE;
			$data['default']['WO_VALPPN'] 	= $getwodata->WO_VALPPN;
			$data['default']['WO_VALPPH'] 	= $getwodata->WO_VALPPH;
			$data['default']['WO_GTOTAL'] 	= $getwodata->WO_GTOTAL;
			$data['default']['Patt_Year'] 	= $getwodata->Patt_Year;
			$data['default']['Patt_Month'] 	= $getwodata->Patt_Month;
			$data['default']['Patt_Date'] 	= $getwodata->Patt_Date;
			$data['default']['Patt_Number']	= $getwodata->Patt_Number;

			$MenuCode 			= 'MN457';
			$data["MenuCode"] 	= 'MN457';

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getwodata->WO_NUM;
				$MenuCode 		= 'MN354';
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

			$this->load->view('v_project/v_spk/ovh_spk_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}