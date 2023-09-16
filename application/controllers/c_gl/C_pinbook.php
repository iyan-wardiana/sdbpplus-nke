<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 30 Januari 2018
	* File Name		= C_pinbook.php
	* Location		= -
*/

class C_pinbook extends CI_Controller  
{
    function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_gl/m_gej_pinbook/m_gej_pinbook', '', TRUE);
		$this->load->model('m_gl/m_gej/m_gej', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
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
		
			//$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}

    function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_gl/c_pinbook/prjl0b28t18PB/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18PB() // OK - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN165';
				$data["MenuApp"] 	= 'MN165';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN165';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/c_pinbook/gejpinbook/?id=";
			
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

    function gejpinbook() // OK - gej list
	{
		$this->load->model('m_gl/m_gej_pinbook/m_gej_pinbook', '', TRUE);
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
					$PRJCODE	= $this->data['PRJCODE'];
					$start		= 0;
					$end		= 30;
				}
				else
				{
					$key		= '';
					$PRJCODE	= $EXP_COLLD1;
					$start		= 0;
					$end		= 50;
				}
				$data["url_search"] = site_url('c_gl/c_pinbook/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN165';
				$data["MenuApp"] 	= 'MN165';
				$data["MenuCode"] 	= 'MN165';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$SOURCEDOC			= "";
			$COLLDATA			= "$PRJCODE~$SOURCEDOC";
			$data['addURL'] 	= site_url('c_gl/c_pinbook/add0b28t18PB/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
			$data['backURL'] 	= site_url('c_gl/c_pinbook/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN165';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Open list gej");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_gej_pinbook/gej_pinbook', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		$this->load->model('m_finance/m_fpa/m_fpa', '', TRUE);
		$this->load->model('m_gl/m_gej_pinbook/m_gej_pinbook', '', TRUE);
		
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
			
			$columns_valid 	= array("Manual_No", 
									"JournalH_Date", 
									"JournalH_Desc",
									"Journal_Amount",
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
			$num_rows 		= $this->m_gej_pinbook->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej_pinbook->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				$Manual_No			= $dataI['Manual_No'];
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;
				
				$JournalH_Desc		= $dataI['JournalH_Desc'];
				$JournalH_Desc2		= $dataI['JournalH_Desc2'];
				$JournalType		= $dataI['JournalType'];
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$Journal_Amount		= $dataI['Journal_Amount'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);
				$isLock 			= $dataI['isLock'];

				$isLockD = "";
				$isdisabledVw = "";
				if($isLock == 1)
				{
					$isLockD 		= "<i class='fa fa-lock margin-r-5'></i>";
					$isdisabledVw 	= "disabled='disabled'";
				}
				
				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_gl/c_pinbook/up0b28t18/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPrint1			= site_url('c_gl/c_pinbook/prN7_d0c/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader~tbl_journaldetail~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$voidID 			= "$secVoid~tbl_journalheader_pb~tbl_journaldetail_pb~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
                
                $revDesc 			= "";
                if($JournalH_Desc2 != '')
                {
					$revDesc 	= 	"<br><strong><i class='fa  fa-bell margin-r-5'></i>Revise Note </strong>
							  		<div style='margin-left: 15px'>
								  		<p class='text-muted' style='font-style: italic;'>".$JournalH_Desc2."</p>
								  	</div>";
                }

				if($GEJ_STAT == 1 || $GEJ_STAT == 4) 
				{
					$secPrint	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-primary btn-xs' disabled='disabled'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($GEJ_STAT == 3)
				{
					$secPrint	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' $isdisabledVw>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='#' title='Delete' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secPrint	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")' disabled='disabled'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDelIcut."')' title='Delete' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

                // SATRT : GET CREATER
					$Emp_ID			= $dataI['Emp_ID'];
	                $imgempCrt 		= "username.jpg";
					$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
					$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
					foreach($resIMGCrt as $rowGIMGCrt) :
						$imgempCrt = $rowGIMGCrt->imgemp_filenameX;
					endforeach;
					
					$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgempCrt);
					if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
					{
						$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
					}

					//$collIMGCrt 	= "<img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>";
					$collIMGCrt 	= $empName; // sementara tidak perlu emnampilkan foto creater
                // END  : GET CREATER

                // SATRT : GET APPROVE HIST.
                	$JournalH_Code2 = "";
	                $collIMG 		= "";
	                $imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
	                $sqlAPPH 		= "SELECT AH_APPROVER FROM tbl_approve_hist WHERE AH_CODE = '$JournalH_Code'";
					$resAPPH		= $this->db->query($sqlAPPH)->result();
					foreach($resAPPH as $rowAPPH):
						$APPROVER	= $rowAPPH->AH_APPROVER;

		                $imgempfNmX 	= "username.jpg";
						$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$APPROVER'";
						$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
						foreach($resIMGCrt as $rowGIMGCrt) :
							$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
						endforeach;
						
						$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$APPROVER.'/'.$imgempfNmX);
						if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$APPROVER))
						{
							$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
						}

						$collIMG_0 		= "<img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>";
						$collIMG 		= $collIMG.$collIMG_0;
					endforeach;

					$collAPPIMG 		= $collIMG;
					if($JournalH_Code2	== $JournalH_Code)
						$collAPPIMG 	= "";
					$collAPPIMG 		= ""; // sementara tidak perlu emnampilkan foto approver
                // END  : GET APPROVE HIST.
				
				$output['data'][] = array("<div style='white-space:nowrap'>$isLockD$Manual_No</div>",
										  $JournalH_DateV,
										  $dataI['JournalH_Desc'].$revDesc,
										  "<div style='white-space:nowrap'>".number_format($Journal_Amount,2)."</div>",
										  "<div style='white-space:nowrap'>".$collIMGCrt."</div>",
										  "<div style='white-space:nowrap'><span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span></div><br><div style='white-space:nowrap'>".$collAPPIMG."</div>",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

    function add0b28t18PB() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_gl/m_gej_pinbook/m_gej_pinbook', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$SOURCEDOC	= $EXTRACTCOL[1];
			
			// GET MENU DESC
				$mnCode				= 'MN165';
				$data["MenuApp"] 	= 'MN165';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$data['PRJCODE_HO'] = $this->data['PRJCODE_HO'];
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_gl/c_pinbook/add_process');
			$data['backURL'] 	= site_url('c_gl/c_pinbook/gejpinbook/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$proj_Currency		= 'IDR';
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$data['countAcc'] 	= $this->m_gej_pinbook->count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE);
			$data['vwAcc'] 		= $this->m_gej_pinbook->view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE)->result();
			
			$MenuCode 			= 'MN165';
			$data["MenuCode"] 	= 'MN165';
			$data['vwDocPatt'] 	= $this->m_gej_pinbook->getDataDocPat($MenuCode)->result();
			$data["SOURCEDOC"] 	= $SOURCEDOC;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN165';
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
			
			$this->load->view('v_gl/v_gej_pinbook/gej_pinbook_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

    function add_process() // OK
	{
		$this->load->model('m_gl/m_gej_pinbook/m_gej_pinbook', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$JournalH_Date	= date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Manual_No		= $this->input->post('Manual_No');
			$Pattern_Type	= $this->input->post('Pattern_Type');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			$proj_Code 		= $this->input->post('proj_Code');
			$proj_CodeHO	= $this->input->post('proj_CodeHO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$GEJ_STAT 		= $this->input->post('GEJ_STAT');
			$Source 		= $this->input->post('Source');
			$REF_NUM 		= $this->input->post('REF_NUM');
			$REF_CODE 		= $this->input->post('REF_CODE');

			$PERIODED		= $JournalH_Date;
			$PERIODM		= date('m', strtotime($PERIODED));
			$PERIODY		= date('Y', strtotime($PERIODED));
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN165';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				//$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc');
			$AH_ISLAST		= $this->input->post('IS_LAST');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$AccId_K 		= $this->input->post('AccId_K');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN165',
										'DOCTYPE' 		=> 'PINBUK',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> $AccId_K,
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $Manual_No 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			$projPBH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'Manual_No' 		=> $Manual_No,
									'Source' 			=> $Source,
									'REF_NUM' 			=> $REF_NUM,
									'REF_CODE' 			=> $REF_CODE,
									'JournalType' 		=> 'PINBUK',
									'JournalH_Desc'		=> htmlspecialchars($this->input->post('JournalH_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'PINBUK',
									'Emp_ID'			=> $DefEmp_ID,
									'Created'			=> $GEJ_CREATED,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'proj_CodeHO'		=> $proj_CodeHO,
									'PRJPERIOD'			=> $PRJPERIOD,
									'Pattern_Type'		=> $Pattern_Type,
									'GEJ_STAT'			=> $GEJ_STAT);
			$this->m_gej_pinbook->add($projPBH);
				
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader_pb");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : DETAIL JOURNAL DEBET
				$AccId_D 	= $this->input->post('AccId_D');

				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_D' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 		= explode("~",$syncPRJ);
				$jmD 			= count($dataPecah);

				
				$JournalD_Debet 		= $this->input->post('JournalD_Amount');
				$JournalD_Kredit 		= 0;
				$JournalD_Debet_tax		= 0;
				$Base_Debet 			= $JournalD_Debet;
				$Base_Kredit 			= $JournalD_Kredit;
				$Base_Debet_tax			= 0;
				$COA_Debet 				= $JournalD_Debet;
				$COA_Kredit 			= $JournalD_Kredit;
				$COA_Debet_tax			= 0;
				$JournalD_Kredit_tax	= 0;
				$Base_Kredit_tax		= 0;
				$COA_Kredit_tax			= 0;

				$Ref_Number 		= '';
				$Notes_D	 		= htmlspecialchars($this->input->post('JournalD_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				$OtherD_Desc 		= htmlspecialchars($this->input->post('OtherD_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
				$Journal_DK			= 'D';
				$Journal_Type 		= 'PINBUK';
				$isTax 				= 0;

				$curr_rate			= 1;
				$isDirect			= 1;

				$Acc_Name 			= "-";
				$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccId_D' LIMIT 1";
				$resNm				= $this->db->query($sqlNm)->result();
				foreach($resNm as $rowNm):
					$Acc_Name		= $rowNm->Account_NameId;
				endforeach;
				
				$insDSQL	= "INSERT INTO tbl_journaldetail_pb (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
								JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
								curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
								GEJ_STAT, JournalH_Date, Acc_Name, Notes)
							VALUE ('$JournalH_Code', 'PINBUK', '$AccId_D', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '', 'IDR', 
								$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
								$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 
								1, 1, '', '', '$Ref_Number', '$OtherD_Desc', '$Journal_DK', '$Journal_Type', $isTax,
								$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_D')";
				$this->db->query($insDSQL);
			// END : DETAIL JOURNAL DEBET

			// START : DETAIL JOURNAL KREDIT

				$AccId_K 	= $this->input->post('AccId_K');

				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_K' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 		= explode("~",$syncPRJ);
				$jmD 			= count($dataPecah);

				
				$JournalD_Debet 		= 0;
				$JournalD_Kredit 		= $this->input->post('JournalK_Amount');
				$JournalD_Debet_tax		= 0;
				$Base_Debet 			= $JournalD_Debet;
				$Base_Kredit 			= $JournalD_Kredit;
				$Base_Debet_tax			= 0;
				$COA_Debet 				= $JournalD_Debet;
				$COA_Kredit 			= $JournalD_Kredit;
				$COA_Debet_tax			= 0;
				$JournalD_Kredit_tax	= 0;
				$Base_Kredit_tax		= 0;
				$COA_Kredit_tax			= 0;

				$Ref_Number 		= '';
				$Notes_K	 		= $this->input->post('JournalK_Desc');
				$OtherK_Desc 		= $this->input->post('OtherK_Desc');
				$Journal_DK			= 'K';
				$Journal_Type 		= 'PINBUK';
				$isTax 				= 0;

				$curr_rate			= 1;
				$isDirect			= 1;

				$Acc_Name 			= "-";
				$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccId_K' LIMIT 1";
				$resNm				= $this->db->query($sqlNm)->result();
				foreach($resNm as $rowNm):
					$Acc_Name		= $rowNm->Account_NameId;
				endforeach;
				
				$insKSQL	= "INSERT INTO tbl_journaldetail_pb (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
								JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
								curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
								GEJ_STAT, JournalH_Date, Acc_Name, Notes)
							VALUE ('$JournalH_Code', 'PINBUK', '$AccId_K', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '', 'IDR', 
								$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
								$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 
								1, 1, '', '', '$Ref_Number', '$OtherK_Desc', '$Journal_DK', '$Journal_Type', $isTax,
								$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_K')";
				$this->db->query($insKSQL);
			// END : DETAIL JOURNAL KREDIT
			
			// UPDATE AMOUNT JOURNAL HEADER
				$Base_DebetTOT = $this->input->post('JournalD_Amount');
				$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = $Base_DebetTOT
								WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlUpdJH);
			
			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
				$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PINBUK",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
										'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
										'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $GEJ_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_GEJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_GEJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_GEJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_GEJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_GEJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_GEJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_GEJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN165';
				$TTR_CATEG		= 'C';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Sv_proc gej_frm $STAT_BEFORE to $GEJ_STAT _");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_gl/c_pinbook/gejpinbook/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN165';
			$data["MenuApp"] 	= 'MN165';
			$data['PRJCODE']	= $this->data['PRJCODE'];
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA		= $_GET['id'];
			$COLLDATA		= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL		= explode("~", $COLLDATA);
			$PRJCODE		= $EXTRACTCOL[0];
			$JournalH_Code	= $EXTRACTCOL[1];
			
			$getGEJ 		= $this->m_gej_pinbook->get_GEJ_by_number($JournalH_Code)->row();
			$data['default']['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['default']['Manual_No'] 		= $getGEJ->Manual_No;
			$Manual_No 							= $getGEJ->Manual_No;
			$data['default']['REF_NUM'] 		= $getGEJ->REF_NUM;
			$data['default']['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['default']['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['default']['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['default']['proj_Code'] 		= $getGEJ->proj_Code;
			$data['default']['PRJCODE'] 		= $getGEJ->proj_Code;
			$data['default']['proj_CodeHO'] 	= $getGEJ->proj_CodeHO;
			$data['default']['PRJPERIOD'] 		= $getGEJ->PRJPERIOD;
			$PRJCODE							= $getGEJ->proj_Code;
			$PRJCODE_HO							= $getGEJ->proj_CodeHO;
			$data['default']['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['default']['Pattern_Type'] 	= $getGEJ->Pattern_Type;
			$data['default']['SPLCODE'] 		= $getGEJ->SPLCODE;
			$data['default']['Source'] 			= $getGEJ->Source;
			$data['default']['REF_NUM'] 		= $getGEJ->REF_NUM;
			$data['default']['REF_CODE'] 		= $getGEJ->REF_CODE;
			$data['default']['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			
			
			$data['proj_Code'] 		= $PRJCODE;	
			$data['proj_CodeHO'] 	= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_gl/c_pinbook/update_process');
			$data['backURL'] 	= site_url('c_gl/c_pinbook/gejpinbook/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			$proj_Currency		= 'IDR';
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();

			$data['countAcc'] 	= $this->m_gej_pinbook->count_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE);
			$data['vwAcc'] 		= $this->m_gej_pinbook->view_all_Acc($proj_Currency, $DefEmp_ID, $PRJCODE)->result();
			
			$MenuCode 			= 'MN165';
			$data["MenuCode"] 	= 'MN165';
			$data['vwDocPatt'] 	= $this->m_gej_pinbook->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN165';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Open pinbuk $Manual_No");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_gej_pinbook/gej_pinbook_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$comp_init 	= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			
			$GEJ_CREATED 	= date('Y-m-d H:i:s');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$JournalH_Date	= date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr 			= $Patt_Year;
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Manual_No		= $this->input->post('Manual_No');
			$Pattern_Type	= $this->input->post('Pattern_Type');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			$proj_CodeHO	= $this->input->post('proj_CodeHO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$GEJ_STAT 		= $this->input->post('GEJ_STAT');
			$Source 		= $this->input->post('Source');
			$REF_NUM 		= $this->input->post('REF_NUM');
			$REF_CODE 		= $this->input->post('REF_CODE');

			$PERIODED		= $JournalH_Date;
			$PERIODM		= date('m', strtotime($PERIODED));
			$PERIODY		= date('Y', strtotime($PERIODED));
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc');
			$AH_ISLAST		= $this->input->post('IS_LAST');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$AccId_K 		= $this->input->post('AccId_K');
				$paramStat 		= array('PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN165',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> $AccId_K,
										'CREATER'		=> $DefEmp_ID);
				$Patt_Number	= $this->m_updash->updDocNo($paramStat);
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			$projGEJH 		= array('Manual_No' 		=> $Manual_No,
									'Source' 			=> $Source,
									'REF_NUM' 			=> $REF_NUM,
									'REF_CODE' 			=> $REF_CODE,
									'JournalType' 		=> 'PINBUK',
									'JournalH_Desc'		=> htmlspecialchars($this->input->post('JournalH_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'PINBUK',
									'Emp_ID'			=> $DefEmp_ID,
									'Created'			=> $GEJ_CREATED,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'proj_CodeHO'		=> $proj_CodeHO,
									'PRJPERIOD'			=> $PRJPERIOD,
									'Pattern_Type'		=> $Pattern_Type,
									'GEJ_STAT'			=> $GEJ_STAT);
			$this->m_gej_pinbook->updateGEJ($JournalH_Code, $projGEJH);

			// START : SETTING L/R
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PERIODED	= $JournalH_Date;
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

			if($GEJ_STAT == 3)
			{
				// UPDATE DEFAULT STATUS
					$upJH		= "UPDATE tbl_journalheader_pb SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

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

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader_pd");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
											'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
											'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> 7,				// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_GEJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_GEJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_GEJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_GEJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_GEJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_GEJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_GEJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT

				if($AH_ISLAST == 1)
				{
					$projPBH 		= array('JournalH_Code' 	=> $JournalH_Code,
											'Manual_No' 		=> $Manual_No,
											'JournalType' 		=> 'PINBUK',
											'JournalH_Desc'		=> htmlspecialchars($this->input->post('JournalH_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
											'JournalH_Date'		=> $JournalH_Date,
											'Company_ID'		=> $comp_init,
											'Reference_Type'	=> 'PINBUK',
											'Emp_ID'			=> $DefEmp_ID,
											'Created'			=> $GEJ_CREATED,
											'LastUpdate'		=> $GEJ_CREATED,
											'Wh_id'				=> $PRJCODE,
											'proj_Code'			=> $PRJCODE,
											'proj_CodeHO'		=> $proj_CodeHO,
											'PRJPERIOD'			=> $PRJPERIOD,
											'Pattern_Type'		=> $Pattern_Type,
											'GEJ_STAT'			=> $GEJ_STAT);
					$this->m_gej_pinbook->addJRN($projPBH);

					$upJH2	= "UPDATE tbl_journalheader_pb SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH2);
		
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'	=> $completeName,
												'TBLNAME'		=> "tbl_journalheader_pb");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					// START : UPDATE TO TRANS-COUNT
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
						$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
												'PRJCODE' 		=> $PRJCODE,		// PROJECT
												'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
												'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
												'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
												'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
												'STATDOC' 		=> $GEJ_STAT,		// TRANSACTION STATUS
												'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
												'FIELD_NM_ALL'	=> "TOT_GEJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
												'FIELD_NM_N'	=> "TOT_GEJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
												'FIELD_NM_C'	=> "TOT_GEJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
												'FIELD_NM_A'	=> "TOT_GEJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_R'	=> "TOT_GEJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_RJ'	=> "TOT_GEJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
												'FIELD_NM_CL'	=> "TOT_GEJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
						$this->m_updash->updateDashData($parameters);
					// END : UPDATE TO TRANS-COUNT

					// RESET DETAIL
						$this->m_gej_pinbook->deleteGEJDetail($JournalH_Code);
				
					// START : DETAIL JOURNAL DEBET
						$AccId_D 	= $this->input->post('AccId_D');

						$JournalD_Debet 		= $this->input->post('JournalD_Amount');
						$JournalD_Kredit 		= 0;
						$JournalD_Debet_tax		= 0;
						$Base_Debet 			= $JournalD_Debet;
						$Base_Kredit 			= $JournalD_Kredit;
						$Base_Debet_tax			= 0;
						$COA_Debet 				= $JournalD_Debet;
						$COA_Kredit 			= $JournalD_Kredit;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;

						$Ref_Number 		= '';
						$Notes_D	 		= $this->input->post('JournalD_Desc');
						$OtherD_Desc 		= $this->input->post('OtherD_Desc');
						$Journal_DK			= 'D';
						$Journal_Type 		= 'PINBUK';
						$isTax 				= 0;

						$curr_rate			= 1;
						$isDirect			= 1;

						$Acc_Name 			= "-";
						$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccId_D' LIMIT 1";
						$resNm				= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name		= $rowNm->Account_NameId;
						endforeach;
						
						$insDSQL	= "INSERT INTO tbl_journaldetail_pb (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
										JournalD_Debet, Base_Debet, COA_Debet,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, Acc_Name, Notes)
									VALUE ('$JournalH_Code', 'PINBUK', '$AccId_D', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', 'IDR', 
										$JournalD_Debet, $Base_Debet, $COA_Debet, 
										1, 1, '$Ref_Number', '$OtherD_Desc', 'D', '$Journal_Type', $isTax,
										$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_D')";
						$this->db->query($insDSQL);
						
						$insDSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
										JournalD_Debet, Base_Debet, COA_Debet,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, Acc_Name, Notes)
									VALUE ('$JournalH_Code', 'PINBUK', '$AccId_D', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', 'IDR', 
										$JournalD_Debet, $Base_Debet, $COA_Debet, 
										1, 1, '$Ref_Number', '$OtherD_Desc', 'D', '$Journal_Type', $isTax,
										$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_D')";
						$this->db->query($insDSQL);

						// START : UPDATE LAWAN KAS/BANK
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_D' LIMIT 1";
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
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
														Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$AccId_D'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : UPDATE KAS/BANK
					// END : DETAIL JOURNAL DEBET

					// START : DETAIL JOURNAL KREDIT
						$AccId_K 	= $this->input->post('AccId_K');

						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_K' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 		= explode("~",$syncPRJ);
						$jmD 			= count($dataPecah);

						
						$JournalD_Debet 		= 0;
						$JournalD_Kredit 		= $this->input->post('JournalK_Amount');
						$JournalD_Debet_tax		= 0;
						$Base_Debet 			= $JournalD_Debet;
						$Base_Kredit 			= $JournalD_Kredit;
						$Base_Debet_tax			= 0;
						$COA_Debet 				= $JournalD_Debet;
						$COA_Kredit 			= $JournalD_Kredit;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;

						$Ref_Number 		= '';
						$Notes_K	 		= $this->input->post('JournalK_Desc');
						$OtherK_Desc 		= $this->input->post('OtherK_Desc');
						$Journal_DK			= 'K';
						$Journal_Type 		= 'PINBUK';
						$isTax 				= 0;

						$curr_rate			= 1;
						$isDirect			= 1;

						$Acc_Name 			= "-";
						$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccId_K' LIMIT 1";
						$resNm				= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name		= $rowNm->Account_NameId;
						endforeach;
						
						$insKSQL	= "INSERT INTO tbl_journaldetail_pb (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
										JournalD_Kredit, Base_Kredit, COA_Kredit,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, Acc_Name, Notes)
									VALUE ('$JournalH_Code', 'PINBUK', '$AccId_K', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', 'IDR', 
										$JournalD_Kredit, $Base_Kredit, $COA_Kredit,
										1, 1, '$Ref_Number', '$OtherK_Desc', 'K', '$Journal_Type', $isTax,
										$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_K')";
						$this->db->query($insKSQL);
						
						$insKSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, Currency_id,
										JournalD_Kredit, Base_Kredit, COA_Kredit,
										curr_rate, isDirect, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, Acc_Name, Notes)
									VALUE ('$JournalH_Code', 'PINBUK', '$AccId_K', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', 'IDR', 
										$JournalD_Kredit, $Base_Kredit, $COA_Kredit,
										1, 1, '$Ref_Number', '$OtherK_Desc', 'K', '$Journal_Type', $isTax,
										$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_K')";
						$this->db->query($insKSQL);

						// START : UPDATE LAWAN KAS/BANK
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_K' LIMIT 1";
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
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
														Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$AccId_K'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : UPDATE KAS/BANK
					// END : DETAIL JOURNAL KREDIT
					
					// UPDATE AMOUNT JOURNAL HEADER
						$Base_DebetTOT = $this->input->post('JournalD_Amount');
						$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = $Base_DebetTOT
										WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($sqlUpdJH);

						$sqlUpdJH	= "UPDATE tbl_journalheader_pb SET Journal_Amount = $Base_DebetTOT
										WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($sqlUpdJH);
					
					// START : UPDATE STAT DET
						$this->load->model('m_updash/m_updash', '', TRUE);				
						$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
						$this->m_updash->updSTATJD($paramSTAT);
					// END : UPDATE STAT DET

					// START : UPDATE STATUS
						$s_0X 	= "UPDATE tbl_bprop_header SET PROP_STAT = 6, STATDESC = 'Close', STATCOL = 'info'
   									WHERE PROP_VALUEAPP >= PROP_TRANSFERED AND PRJCODE = '$PRJCODE' AND PROP_NUM = '$REF_NUM'";
   						$this->db->query($s_0X);
					// END : UPDATE STATUS
				}

			}
			elseif($GEJ_STAT == 4)
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
											'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
											'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $GEJ_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_GEJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_GEJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_GEJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_GEJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_GEJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_GEJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_GEJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			}
			elseif($GEJ_STAT == 5)
			{
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_journalheader",	// TABLE NAME
											'KEY_NAME'		=> "JournalH_Code",	// KEY OF THE TABLE
											'STAT_NAME' 	=> "GEJ_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $GEJ_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_GEJ",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_GEJ_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_GEJ_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_GEJ_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_GEJ_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_GEJ_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_GEJ_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
			}
			else
			{
				// UPDATE DEFAULT STATUS
					$upJH		= "UPDATE tbl_journalheader_pb SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader_pb");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// RESET DETAIL
					$this->m_gej_pinbook->deleteGEJDetail($JournalH_Code);

				// START : DETAIL JOURNAL DEBET
					$AccId_D 	= $this->input->post('AccId_D');

					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_D' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 		= explode("~",$syncPRJ);
					$jmD 			= count($dataPecah);

					
					$JournalD_Debet 		= $this->input->post('JournalD_Amount');
					$JournalD_Kredit 		= 0;
					$JournalD_Debet_tax		= 0;
					$Base_Debet 			= $JournalD_Debet;
					$Base_Kredit 			= $JournalD_Kredit;
					$Base_Debet_tax			= 0;
					$COA_Debet 				= $JournalD_Debet;
					$COA_Kredit 			= $JournalD_Kredit;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;

					$Ref_Number 		= '';
					$Notes_D	 		= $this->input->post('JournalD_Desc');
					$OtherD_Desc 		= $this->input->post('OtherD_Desc');
					$Journal_DK			= 'D';
					$Journal_Type 		= 'PINBUK';
					$isTax 				= 0;

					$curr_rate			= 1;
					$isDirect			= 1;

					$Acc_Name 			= "-";
					$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccId_D' LIMIT 1";
					$resNm				= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name		= $rowNm->Account_NameId;
					endforeach;
					
					$insDSQL	= "INSERT INTO tbl_journaldetail_pb (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
									JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
									GEJ_STAT, JournalH_Date, Acc_Name, Notes)
								VALUE ('$JournalH_Code', 'PINBUK', '$AccId_D', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '', 'IDR', 
									$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
									$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 
									1, 1, '', '', '$Ref_Number', '$OtherD_Desc', '$Journal_DK', '$Journal_Type', $isTax,
									$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_D')";
					$this->db->query($insDSQL);
				// END : DETAIL JOURNAL DEBET

				// START : DETAIL JOURNAL KREDIT

					$AccId_K 	= $this->input->post('AccId_K');

					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$AccId_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 		= explode("~",$syncPRJ);
					$jmD 			= count($dataPecah);

					
					$JournalD_Debet 		= 0;
					$JournalD_Kredit 		= $this->input->post('JournalK_Amount');
					$JournalD_Debet_tax		= 0;
					$Base_Debet 			= $JournalD_Debet;
					$Base_Kredit 			= $JournalD_Kredit;
					$Base_Debet_tax			= 0;
					$COA_Debet 				= $JournalD_Debet;
					$COA_Kredit 			= $JournalD_Kredit;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;

					$Ref_Number 		= '';
					$Notes_K	 		= $this->input->post('JournalK_Desc');
					$OtherK_Desc 		= $this->input->post('OtherK_Desc');
					$Journal_DK			= 'K';
					$Journal_Type 		= 'PINBUK';
					$isTax 				= 0;

					$curr_rate			= 1;
					$isDirect			= 1;

					$Acc_Name 			= "-";
					$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$AccId_K' LIMIT 1";
					$resNm				= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name		= $rowNm->Account_NameId;
					endforeach;
					
					$insKSQL	= "INSERT INTO tbl_journaldetail_pb (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
									JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
									GEJ_STAT, JournalH_Date, Acc_Name, Notes)
								VALUE ('$JournalH_Code', 'PINBUK', '$AccId_K', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '', 'IDR', 
									$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
									$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 
									1, 1, '', '', '$Ref_Number', '$OtherK_Desc', '$Journal_DK', '$Journal_Type', $isTax,
									$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Notes_K')";
					$this->db->query($insKSQL);
				// END : DETAIL JOURNAL KREDIT
				
				// UPDATE AMOUNT JOURNAL HEADER
					$Base_DebetTOT = $this->input->post('JournalD_Amount');
					$sqlUpdJH	= "UPDATE tbl_journalheader_pb SET Journal_Amount = $Base_DebetTOT
									WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($sqlUpdJH);
				
				// START : UPDATE STAT DET
					$this->load->model('m_updash/m_updash', '', TRUE);				
					$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
					$this->m_updash->updSTATJD($paramSTAT);
				// END : UPDATE STAT DET
			}

			// START : UPDATE AKUN LAWAN
				$Acc_Cr 		= "";
				$sqlACC_Cr 		= "SELECT Acc_Id FROM tbl_journaldetail_pb WHERE proj_Code = '$PRJCODE' AND Journal_DK = 'K' AND JournalH_Code = '$JournalH_Code'";
				$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
				foreach($resACC_Cr as $row_Cr):
					$Acc_Cr		= $row_Cr->Acc_Id;
				endforeach;

				$updAcc_Db		= "UPDATE tbl_journaldetail_pb SET Acc_Id_Cross = '$Acc_Cr' WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$JournalH_Code'";
				$this->db->query($updAcc_Db);
			// END : UPDATE AKUN LAWAN

			// START : UPDATE STAT DET
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramSTAT 	= array('JournalHCode' 	=> $JournalH_Code);
				$this->m_updash->updSTATJD($paramSTAT);
			// END : UPDATE STAT DET
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $proj_Code;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN165';
				$TTR_CATEG		= 'UP';
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Upd_proc from $STAT_BEFORE to $GEJ_STAT _");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_gl/c_pinbook/gejpinbook/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prN7_d0c()
	{
		//$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$COLLDATA	= $_GET['id'];
		$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
		$EXTRACTCOL	= explode("~", $COLLDATA);
		$PRJCODE	= $EXTRACTCOL[0];
		$CB_NUM		= $EXTRACTCOL[1];
			
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $PRJCODE;
			$TTR_REFDOC		= $CB_NUM;
			$MenuCode 		= 'MN165';
			$TTR_CATEG		= 'PRINT';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC,
									'TTR_NOTES'		=> "prnt_doc _");
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK
			
		if ($this->session->userdata('login') == TRUE)
		{
			$data['PRJCODE'] 	= $PRJCODE;
			$data['CB_NUM'] 	= $CB_NUM;
			$data['title'] 		= $appName;
			
			$this->load->view('v_gl/v_gej_pinbook/gej_pinbook_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataPDS() // GOOD
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
			
			$columns_valid 	= array("",
									"PROP_CODE",
									"PROP_NOTE",
									"EMP_NAME",
									"PROP_VALUE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej_pinbook->get_AllDataPDSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej_pinbook->get_AllDataPDSL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PROP_NUM			= $dataI['PROP_NUM'];
				$PROP_CODE			= $dataI['PROP_CODE'];
				$PROP_DATE			= $dataI['PROP_DATE'];
				$PROP_DATEV			= date('d M Y', strtotime($PROP_DATE));
				$PRJCODE			= $dataI['PRJCODE'];
				$EMP_ID				= $dataI['EMP_ID'];
				$PROP_NOTE 			= wordwrap($dataI['PROP_NOTE'], 50, "<br>", TRUE);
				$PROP_VALUE			= $dataI['PROP_VALUE'];
				$PROP_TRANSFERED	= $dataI['PROP_TRANSFERED'];
				$EMP_NAME			= $dataI['EMP_NAME'];

				$RSVVW 		= "";
				if($PROP_TRANSFERED > 0)
				{
					$RSVVW 	= "<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i>Transfer</strong>
									</div>
								  	<div style='margin-left: 18px;'>
								  		".number_format($PROP_TRANSFERED, 2)."
								  	</div>";
				}

				$chkBox			= "<input type='radio' name='chk1' value='".$PROP_NUM."|".$PROP_CODE."|".$PRJCODE."|".$EMP_ID."|".$PROP_NOTE."|".$PROP_VALUE."|".$PROP_TRANSFERED."' onClick='pickThis1(this);'/>";

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$PROP_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$PROP_NOTE<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$PROP_DATEV."
										  	</div>",
										  	"<div style='white-space:nowrap'>
										  		<strong><i class='fa fa-user margin-r-5'></i>".$EMP_NAME."</strong>
											  	<div style='margin-left: 15px;'>
											  		".$EMP_ID."
											  	</div>
										  	</div>",
											number_format($PROP_VALUE, 2).
										  	$RSVVW);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataVTLK() // GOOD
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
			
			$columns_valid 	= array("",
									"TLK_CODE",
									"TLK_DESC",
									"",
									"TLK_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej_pinbook->get_AllDataVTLKC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej_pinbook->get_AllDataVTLKL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PRJCODE		= $dataI['PRJCODE'];
				$PROP_NUM		= $dataI['PROP_NUM'];
				$PROP_CODE		= $dataI['PROP_CODE'];
				$PROP_VALUE		= $dataI['PROP_VALUE'];
				$TLK_NUM		= $dataI['TLK_NUM'];
				$TLK_CODE		= $dataI['TLK_CODE'];
				$TLK_CATEG		= $dataI['TLK_CATEG'];
				$TLK_DATE		= $dataI['TLK_DATE'];
				$TLK_DATEV		= date('d M Y', strtotime($TLK_DATE));
				$TLK_DATES		= $dataI['TLK_DATES'];
				$TLK_DATESV		= date('d M Y', strtotime($TLK_DATES));
				$TLK_DATEE		= $dataI['TLK_DATEE'];
				$TLK_DATEEV		= date('d M Y', strtotime($TLK_DATEE));
				$TLK_DATSEUS	= $dataI['TLK_DATSEUS'];
				$TLK_DATSEUSV	= date('d M Y', strtotime($TLK_DATSEUS));
				$TLK_DATSEUE	= $dataI['TLK_DATSEUE'];
				$TLK_DATSEUEV	= date('d M Y', strtotime($TLK_DATSEUE));
				$TLK_DESC 		= wordwrap(str_replace("'"," ",$dataI['TLK_DESC']), 50, "<br>", TRUE);
				$TLK_AMOUNT		= $dataI['TLK_AMOUNT'];
				$TLK_AMOUNTU	= $dataI['TLK_AMOUNTU'];
				$EMP_ID			= $dataI['EMP_ID'];

				$complName 		= "-";
				$s_EMP 			= "SELECT CONCAT(First_Name,' ', Last_Name) AS complName FROM tbl_employee WHERE Emp_ID = '$EMP_ID'";
				$r_EMP 			= $this->db->query($s_EMP)->result();
				foreach($r_EMP as $rw_EMP) :
					$complName 	= $rw_EMP->complName;		
				endforeach;

				$RSVVW 		= "";
				if($TLK_AMOUNTU > 0)
				{
					$RSVVW 	= "<div style='white-space:nowrap'>
										<strong><i class='fa fa-check-square-o margin-r-5'></i>Transfer</strong>
									</div>
								  	<div style='margin-left: 18px;'>
								  		".number_format($TLK_AMOUNTU, 2)."
								  	</div>";
				}

				$chkBox			= "<input type='radio' name='chk1' value='".$TLK_NUM."|".$TLK_CODE."|".$PRJCODE."|".$EMP_ID."|".$TLK_DESC."|".$TLK_AMOUNT."|".$TLK_AMOUNTU."' onClick='pickThis1(this);'/>";

				$output['data'][] 	= array($chkBox,
										  	"<div style='white-space:nowrap'>".$TLK_CODE."</div>",
										  	"<div style='white-space:nowrap'>
										  		$TLK_DESC<br>
												<strong><i class='fa fa-calendar margin-r-5'></i>Tanggal</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$TLK_DATEV."
										  	</div>",
										  	"<div style='white-space:nowrap'>
										  		<strong><i class='fa fa-user margin-r-5'></i>".$complName."</strong>
											  	<div style='margin-left: 15px;'>
											  		".$EMP_ID."
											  	</div>
										  	</div>",
											number_format($TLK_AMOUNT, 2).
										  	$RSVVW);
				$noU		= $noU + 1;
			}

			/*$output['data'][] = array("A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A",
									  "A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}