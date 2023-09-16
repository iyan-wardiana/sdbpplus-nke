<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 30 Januari 2018
	* File Name	= C_gej_entry.php
	* Location		= -
*/

class Cgeje0b28t18 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
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
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
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
		
		$url			= site_url('c_gl/cgeje0b28t18/prjl0b28t18/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjl0b28t18() // OK - project list
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN106';
				$data["MenuApp"] 	= 'MN106';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN106';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/gej0b28t18/?id=";
			
			$data["secVIEW"]	= 'v_projectlist/project_list';
			if($this->session->userdata['nSELP'] == 0)
			{
				$PRJCODE1	= $this->session->userdata['proj_Code'];
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
	
	function gej0b28t18() // OK - gej list
	{
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
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
				$data["url_search"] = site_url('c_gl/cgeje0b28t18/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN106';
				$data["MenuApp"] 	= 'MN106';
				$data["MenuCode"] 	= 'MN106';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$SOURCEDOC			= "";
			$COLLDATA			= "$PRJCODE~$SOURCEDOC";
			$data['addURL'] 	= site_url('c_gl/cgeje0b28t18/add0b28t18/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
			$data['backURL'] 	= site_url('c_gl/cgeje0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN106';
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
			
			$this->load->view('v_gl/v_gej_entry/gej_entry', $data);
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
			$PRJCODE		= $_GET['id'];
			$PRJCODE		= $this->url_encryption_helper->decode_url($PRJCODE);
			$collDATA		= "$gEn5rcH~$PRJCODE";
			$url			= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

  	function get_AllData() // GOOD
	{
		$this->load->model('m_finance/m_fpa/m_fpa', '', TRUE);
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
		
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
			$num_rows 		= $this->m_gej_entry->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej_entry->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				
				$isLock				= $dataI['isLock'];
				
				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd				= site_url('c_gl/cgeje0b28t18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPrint1			= site_url('c_gl/cgeje0b28t18/prN7_d0c/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secDelIcut 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 				= "$secDelIcut~tbl_journalheader_gj~tbl_journaldetail_gj~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
				//$secVoid 			= base_url().'index.php/__l1y/trashCHO/?id=';
				$secVoid 			= base_url().'index.php/__l1y/voidDoc_CJRN/?id=';
				$voidID 			= "$secVoid~tbl_journalheader_gj~tbl_journaldetail_gj~JournalH_Code~$JournalH_Code~proj_Code~$PRJCODE";
                
                $revDesc 			= "";
                if($JournalH_Desc2 != '')
                {
					$revDesc 	= 	"<br><strong><i class='fa  fa-bell margin-r-5'></i>Revise Note </strong>
							  		<div style='margin-left: 15px'>
								  		<p class='text-muted' style='font-style: italic;'>".$JournalH_Desc2."</p>
								  	</div>";
                }

				$isLockD = "";
				$isdisabledVw = "";
				if($isLock == 1)
				{
					$isLockD 		= "<i class='fa fa-lock margin-r-5'></i>";
					$isdisabledVw 	= "disabled='disabled'";
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
								   	<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
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

					$collIMGCrt 	= "<img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>";
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
	
	function add0b28t18() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
		
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
				$mnCode				= 'MN106';
				$data["MenuApp"] 	= 'MN106';
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
			$data['form_action']= site_url('c_gl/cgeje0b28t18/add_process');
			$data['backURL'] 	= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN106';
			$data["MenuCode"] 	= 'MN106';
			$data['vwDocPatt'] 	= $this->m_gej_entry->getDataDocPat($MenuCode)->result();
			$data["SOURCEDOC"] 	= $SOURCEDOC;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN106';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Open form gej");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_gej_entry/gej_entry_form', $data);
			//$this->load->view('page_uc', $data);
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
		
		$PRDate		= date('Y-m-d',strtotime($this->input->post('PRDate')));
		$year		= date('Y',strtotime($this->input->post('PRDate')));
		$month 		= (int)date('m',strtotime($this->input->post('PRDate')));
		$date 		= (int)date('d',strtotime($this->input->post('PRDate')));
		
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
	
	function puSA0b28t18() // OK
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$THEROW		= $_GET['theRow'];

			if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			{
				$sqlPRJ 	= "SELECT A.PRJCODE_HO FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE'";
				$resPRJ 	= $this->db->query($sqlPRJ)->result();
				foreach ($resPRJ as $key) {
					$PRJCODEX = $key->PRJCODE_HO;
				}
			}
			else
			{
				$PRJCODEX 	= $PRJCODE;
				$ADDQRY		= "A.PRJCODE";
			}
			
			// GET PERIOD ACT
				/*$PRJPERIOD			= $PRJCODE;
				$getPRJC 			= $this->m_updash->get_PRJC($PRJCODE);
				if($getPRJC > 0)
				{
					$getPRJ 		= $this->m_updash->get_PRJ($PRJCODE)->row();
					$PRJPERIOD		= $getPRJ->PRJPERIOD;
				}*/
			
			// GET PRJPERIODE ACTIVE
				$getGPRJP 			= $this->m_updash->get_PRJPER($PRJCODE)->row();
				$PRJPERIOD 			= $getGPRJP->PRJPERIOD;
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['form_action']	= site_url('c_gl/cgeje0b28t18/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['THEROW'] 		= $THEROW;
			$data['secShowAll']		= site_url('c_gl/cgeje0b28t18/puSA0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllCOA']	= $this->m_gej_entry->count_all_COA($PRJCODEX, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_gej_entry->view_all_COA($PRJCODEX, $DefEmp_ID)->result();
			
			$data['countAllItem']	= $this->m_gej_entry->count_all_Account($PRJCODEX, $PRJPERIOD);
			$data['vwAllItem'] 		= $this->m_gej_entry->view_all_Account($PRJCODEX, $PRJPERIOD)->result();
					
			$this->load->view('v_gl/v_gej_entry/gej_entry_selacc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
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
			
			//$JournalH_Date	= date('Y-m-d',strtotime($this->input->post('JournalH_Date')));
			$JournalH_Date	= date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$Manual_No		= $this->input->post('Manual_No');
			$REF_NUM		= $this->input->post('REF_NUM');
			$Pattern_Type	= $this->input->post('Pattern_Type');
			$SPLCODE		= $this->input->post('SPLCODE');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$proj_CodeHO	= $this->input->post('proj_CodeHO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			$isManual		= $this->input->post('isManual');
			// if($isManual  == 1)
			// 	$Manual_No	= $this->input->post('Manual_No1');

			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier
								WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= htmlspecialchars($rowSPL->SPLDESC, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			endforeach;

			$PERIODED		= $JournalH_Date;
			$PERIODM		= date('m', strtotime($PERIODED));
			$PERIODY		= date('Y', strtotime($PERIODED));
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN106';
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
			$AH_NOTES		= htmlspecialchars($this->input->post('JournalH_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			$AH_ISLAST		= $this->input->post('IS_LAST');

			// START : MEMBUAT LIST NUMBER / tbl_doclist
				$this->load->model('m_updash/m_updash', '', TRUE);
				$PATTCODE 		= $this->input->post('PATTCODE');
				$paramStat 		= array('YEAR' 			=> $Patt_Year,
										'PRJCODE' 		=> $PRJCODE,
										'MNCODE' 		=> 'MN106',
										'DOCTYPE' 		=> 'GEJ',
										'DOCNUM' 		=> $JournalH_Code,
										'DOCCODE'		=> $Manual_No,
										'DOCDATE'		=> $JournalH_Date,
										'DOCDATE'		=> $JournalH_Date,
										'ACC_ID'		=> "",
										'isManual'		=> $isManual,
										'CREATER'		=> $DefEmp_ID);
				$collDATA		= $this->m_updash->addDocNo($paramStat);
				$colExpl		= explode("~", $collDATA);
				$Patt_Number 	= $colExpl[0];
		        $Manual_No 		= $colExpl[1];
			// END : MEMBUAT LIST NUMBER / tbl_doclist
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'Manual_No' 		=> $Manual_No,
									'REF_NUM' 			=> $REF_NUM,
									'JournalType' 		=> 'GEJ',	// Cash Project
									'JournalH_Desc'		=> htmlspecialchars($this->input->post('JournalH_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'GEJ',
									'Emp_ID'			=> $DefEmp_ID,
									'Created'			=> $GEJ_CREATED,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
									'proj_CodeHO'		=> $proj_CodeHO,
									'PRJPERIOD'			=> $PRJPERIOD,
									'Pattern_Type'		=> $Pattern_Type,
									'SPLCODE'			=> $SPLCODE,
									'SPLDESC'			=> $SPLDESC,
									'GEJ_STAT'			=> $GEJ_STAT);
			$this->m_gej_entry->add($projGEJH);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader_gj");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : DETAIL JOURNAL
				$Base_DebetTOT		= 0;
				$Base_KreditTOT		= 0;
				$Base_DebetTOT_Tax	= 0;
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$ITM_CATEG		= $d['ITM_CATEG'];
					$JOBCODEID		= $d['JOBCODEID'];
					$proj_Code		= $d['proj_Code'];
					$proj_CodeHO	= $d['proj_CodeHO'];
					$PRJPERIOD		= $d['PRJPERIOD'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$journalAmount	= $d['JournalD_Amount'];
					
					$proj_Code		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 		= explode("~",$syncPRJ);
					$jmD 			= count($dataPecah);

					$Journal_Type		= '';
					if($JournalD_Pos == 'D')
					{
						$JournalD_Debet	= $d['JournalD_Amount'];
						$Base_Debet		= $d['JournalD_Amount'];
						$COA_Debet		= $d['JournalD_Amount'];
						$JournalD_Kredit= 0;
						$Base_Kredit	= 0;
						$COA_Kredit		= 0;

						$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
					}
					elseif($JournalD_Pos == 'K')
					{
						$JournalD_Debet	= 0;
						$Base_Debet		= 0;
						$COA_Debet		= 0;
						$JournalD_Kredit= $d['JournalD_Amount'];
						$Base_Kredit	= $d['JournalD_Amount'];
						$COA_Kredit		= $d['JournalD_Amount'];

						$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
					}
					
					$JournalD_Debet_tax		= 0;
					$Base_Debet_tax			= 0;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;
					
					
					$curr_rate			= 1;
					$isDirect			= 1;
					$Ref_Number			= $d['Ref_Number'];
					$Other_Desc			= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
					if($Other_Desc == '')
					{
						$Other_Desc		= $AH_NOTES;
					}
					$Journal_DK			= $JournalD_Pos;
					$Journal_Type		= $Journal_Type;
					$isTax				= $isTax;

					$Acc_Name 			= "-";
					$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Id' LIMIT 1";
					$resNm				= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name		= $rowNm->Account_NameId;
					endforeach;
					
					$insSQL	= "INSERT INTO tbl_journaldetail_gj (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
									JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
									curr_rate, isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
									GEJ_STAT, JournalH_Date, Acc_Name, Manual_No)
								VALUE ('$JournalH_Code', 'GEJ', '$Acc_Id', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '$JOBCODEID', 'IDR', 
									$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
									$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 
									1, 1, '$ITM_CODE', '$ITM_CATEG', '$Ref_Number', '$Other_Desc', '$Journal_DK', '$Journal_Type', $isTax,
									$GEJ_STAT, '$JournalH_Date', '$Acc_Name', '$Manual_No')";
					$this->db->query($insSQL);
				}
			// END : DETAIL JOURNAL
			
			// UPDATE AMOUNT JOURNAL HEADER
				$sqlUpdJH	= "UPDATE tbl_journalheader_gj SET Journal_Amount = $Base_DebetTOT
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
										'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_journalheader_gj",	// TABLE NAME
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
				$MenuCode 		= 'MN106';
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
			
			$url			= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up0b28t18() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN106';
			$data["MenuApp"] 	= 'MN106';
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
			
			$getGEJ 		= $this->m_gej_entry->get_GEJ_by_number($JournalH_Code)->row();
			$data['default']['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['default']['Manual_No'] 		= $getGEJ->Manual_No;
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
			$data['default']['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			
			
			$data['proj_Code'] 		= $PRJCODE;	
			$data['proj_CodeHO'] 	= $PRJCODE_HO;
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';
			$data['form_action']= site_url('c_gl/cgeje0b28t18/update_process');
			$data['backURL'] 	= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$data['countPRJ']	= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 		= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN106';
			$data["MenuCode"] 	= 'MN106';
			$data['vwDocPatt'] 	= $this->m_gej_entry->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $JournalH_Code;
				$MenuCode 		= 'MN106';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC,
										'TTR_NOTES'		=> "Opn_upd form _");
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_gej_entry/gej_entry_form', $data);
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
			
			$JournalH_Date	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Year		= date('Y',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Month		= date('m',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$Patt_Date		= date('d',strtotime(str_replace('/', '-', $this->input->post('JournalH_Date'))));
			$accYr			= date('Y', strtotime($JournalH_Date));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$Manual_No		= $this->input->post('Manual_No');
			$REF_NUM		= $this->input->post('REF_NUM');
			$Pattern_Type	= $this->input->post('Pattern_Type');
			$SPLCODE		= $this->input->post('SPLCODE');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			
			$proj_CodeHO	= $this->input->post('proj_CodeHO');
			$PRJPERIOD 		= $this->input->post('PRJPERIOD');
			
			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier
								WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= htmlspecialchars($rowSPL->SPLDESC, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
			endforeach;

			$PERIODED		= $JournalH_Date;
			$PERIODM		= date('m', strtotime($PERIODED));
			$PERIODY		= date('Y', strtotime($PERIODED));
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= htmlspecialchars($this->input->post('JournalH_Desc'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			$STAT_BEFORE	= $this->input->post('STAT_BEFORE');			// IF "ADD" CONDITION ALWAYS = PR_STAT
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'Manual_No' 		=> $Manual_No,
									'REF_NUM' 			=> $REF_NUM,
									'JournalType' 		=> 'GEJ',	// Cash Project
									'JournalH_Desc'		=> htmlspecialchars($this->input->post('JournalH_Desc'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'JournalH_Desc2'	=> htmlspecialchars($this->input->post('JournalH_Desc2'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'GEJ',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'SPLCODE'			=> $SPLCODE,
									'SPLDESC'			=> $SPLDESC,
									'GEJ_STAT'			=> $GEJ_STAT);
			$this->m_gej_entry->updateGEJ($JournalH_Code, $projGEJH);

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
					$upJH		= "UPDATE tbl_journalheader_gj SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
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
											'TBLNAME'		=> "tbl_journalheader_gj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_journalheader_gj",	// TABLE NAME
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
					$upJH2	= "UPDATE tbl_journalheader_gj SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH2);
		
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> $GEJ_STAT,
												'PRJCODE' 		=> $PRJCODE,
												//'CREATERNM'	=> $completeName,
												'TBLNAME'		=> "tbl_journalheader_gj");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
					
					// START : UPDATE TO TRANS-COUNT
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
						$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
												'PRJCODE' 		=> $PRJCODE,		// PROJECT
												'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
												'TBL_NAME' 		=> "tbl_journalheader_gj",	// TABLE NAME
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

				// RESET DETAIL
					$this->m_gej_entry->deleteGEJDetail($JournalH_Code);

				// START : DETAIL JOURNAL
					$Base_DebetTOT		= 0;
					$Base_KreditTOT		= 0;
					$Base_DebetTOT_Tax	= 0;			
					foreach($_POST['data'] as $d)
					{
						$JournalH_Code	= $d['JournalH_Code'];
						$Acc_Id			= $d['Acc_Id'];
						$ITM_CODE		= $d['ITM_CODE'];
						$JOBCODEID		= $d['JOBCODEID'];
						$ITM_CATEG		= $d['ITM_CATEG'];
						$proj_Code		= $d['proj_Code'];
						$JournalD_Pos	= $d['JournalD_Pos'];
						$isTax			= $d['isTax'];
						$journalAmount	= $d['JournalD_Amount'];
							
						$proj_Code		= $d['proj_Code'];
						$ACC_NUM		= $d['Acc_Id'];			// Detail Account
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 		= explode("~",$syncPRJ);
						$jmD 			= count($dataPecah);

						$Journal_Type	= '';
						if($JournalD_Pos == 'D')
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;
								
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit

								if($ITM_CODE != '')
								{
									$ITM_GROUP 	= '';
									$ITM_CATEG 	= '';
									$ITM_LR 	= '';
									$sqlLITMLR	= "SELECT ITM_GROUP, ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
									$resLITMLR = $this->db->query($sqlLITMLR)->result();					
									foreach($resLITMLR as $rowLITMLR):
										$ITM_GROUP	= $rowLITMLR->ITM_GROUP;
										$ITM_CATEG	= $rowLITMLR->ITM_CATEG;
										$ITM_LR		= $rowLITMLR->ITM_LR;
									endforeach;

									// START : ITEM HISTORY
										$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
																QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
																JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
															VALUES ('$JournalH_Code', '$proj_Code', '$JournalH_Date', '$ITM_CODE', $Base_Debet, 0, 
																0, 0, 'GEJ', $Base_Debet, '$comp_init', 'IDR', 
																'$JOBCODEID', 3, '$Base_Debet', '$ITM_CATEG', '$AH_NOTES')";
										$this->db->query($sqlHist);
									// END : ITEM HISTORY

									// L/R MANUFACTUR
										if($ITM_LR != '')
										{
											$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$Base_Debet 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
									
									// L/R CONTRACTOR
										if($ITM_GROUP == 'ADM')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'GE')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet  
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'I')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet  
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'O')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'SC')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet  
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'T')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet  
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'U')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet  
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}

									// START : Update ITM Used
										// 1. UPDATE JOBLIST
											$ITM_VOLM	= 0;
											$ITM_PRICE	= 0;
											$ITM_USED	= 0;
											$ITM_USEDAM	= 0;
											$sqlUSED1	= "SELECT ITM_VOLM, ITM_PRICE, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
																WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
																	AND ITM_CODE = '$ITM_CODE'";
											$resUSED1	= $this->db->query($sqlUSED1)->result();
											foreach($resUSED1 as $rowUSED1):
												$ITM_VOLM	= $rowUSED1->ITM_VOLM;
												$ITM_PRICE	= $rowUSED1->ITM_PRICE;
												$ITM_USED	= $rowUSED1->ITM_USED;
												$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
											endforeach;

											// CARI VOLUME DARI HARGA PER ITEM
												$ITMVOLM 	= $Base_Debet / $ITM_PRICE;
											
											$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
																ITM_LASTP	= $ITM_PRICE,
																ITM_USED 	= $ITM_USED+$ITMVOLM, 
																ITM_USED_AM = $ITM_USEDAM+$Base_Debet
															WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
											$this->db->query($sqlUpdJOBL);
											
										// 2. UPDATE ITEM LIST
											$ITM_OUT	= 0;
											$UM_VOLM	= 0;
											$UM_AMOUNT	= 0;
											$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
																WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
																	AND ITM_CODE = '$ITM_CODE'";
											$resUSED1	= $this->db->query($sqlUSED1)->result();
											foreach($resUSED1 as $rowUSED1):
												$ITM_USED	= $rowUSED1->ITM_USED;
												$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
											endforeach;
											$sqlUpdITML	= "UPDATE tbl_item SET
																ITM_LASTP	= $ITM_PRICE,
																ITM_OUT 	= $ITM_OUT+$ITMVOLM,
																UM_VOLM 	= $UM_VOLM+$ITMVOLM,
																UM_AMOUNT 	= $UM_AMOUNT+$Base_Debet
															WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
											$this->db->query($sqlUpdITML);
									// END : Update ITM Used
								}
							}
							$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;	
						}
						elseif($JournalD_Pos == 'K')
						{
							$JournalD_Debet	= 0;
							$Base_Debet		= 0;
							$COA_Debet		= 0;
							$JournalD_Kredit= $d['JournalD_Amount'];
							$Base_Kredit	= $d['JournalD_Amount'];
							$COA_Kredit		= $d['JournalD_Amount'];

							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Kredit
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
																Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Kredit

								if($ITM_CODE != '')
								{
									$ITM_GROUP 	= '';
									$ITM_CATEG 	= '';
									$ITM_LR 	= '';
									$sqlLITMLR	= "SELECT ITM_GROUP, ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
									$resLITMLR = $this->db->query($sqlLITMLR)->result();					
									foreach($resLITMLR as $rowLITMLR):
										$ITM_GROUP	= $rowLITMLR->ITM_GROUP;
										$ITM_CATEG	= $rowLITMLR->ITM_CATEG;
										$ITM_LR		= $rowLITMLR->ITM_LR;
									endforeach;

									// START : ITEM HISTORY
										$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
																QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
																JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
															VALUES ('$JournalH_Code', '$proj_Code', '$JournalH_Date', '$ITM_CODE', 0, $Base_Kredit, 
																0, 0, 'GEJ', $Base_Debet, '$comp_init', 'IDR', 
																'$JOBCODEID', 3, '$Base_Debet', '$ITM_CATEG', '$AH_NOTES')";
										$this->db->query($sqlHist);
									// END : ITEM HISTORY

									// L/R MANUFACTUR
										if($ITM_LR != '')
										{
											$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR-$Base_Kredit 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
								
									// L/R CONTRACTOR
										if($ITM_GROUP == 'ADM')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'GE')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'I')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$Base_Kredit 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'O')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'SC')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'T')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'U')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit 
														WHERE PRJCODE = '$proj_Code' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}

									// START : Update ITM Used
										// 1. UPDATE JOBLIST
											$ITM_VOLM	= 0;
											$ITM_PRICE	= 0;
											$ITM_USED	= 0;
											$ITM_USEDAM	= 0;
											$sqlUSED1	= "SELECT ITM_VOLM, ITM_PRICE, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
																WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
																	AND ITM_CODE = '$ITM_CODE'";
											$resUSED1	= $this->db->query($sqlUSED1)->result();
											foreach($resUSED1 as $rowUSED1):
												$ITM_VOLM	= $rowUSED1->ITM_VOLM;
												$ITM_PRICE	= $rowUSED1->ITM_PRICE;
												$ITM_USED	= $rowUSED1->ITM_USED;
												$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
											endforeach;

											// CARI VOLUME DARI HARGA PER ITEM
												$ITMVOLM 	= $Base_Debet / $ITM_PRICE;
											
											$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
																ITM_LASTP	= $ITM_PRICE,
																ITM_USED 	= $ITM_USED+$ITMVOLM, 
																ITM_USED_AM = $ITM_USEDAM+$Base_Debet
															WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
																AND ITM_CODE = '$ITM_CODE'";
											$this->db->query($sqlUpdJOBL);
											
										// 2. UPDATE ITEM LIST
											$ITM_OUT	= 0;
											$UM_VOLM	= 0;
											$UM_AMOUNT	= 0;
											$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
																WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
																	AND ITM_CODE = '$ITM_CODE'";
											$resUSED1	= $this->db->query($sqlUSED1)->result();
											foreach($resUSED1 as $rowUSED1):
												$ITM_USED	= $rowUSED1->ITM_USED;
												$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
											endforeach;
											$sqlUpdITML	= "UPDATE tbl_item SET
																ITM_LASTP	= $ITM_PRICE,
																ITM_OUT 	= $ITM_OUT+$ITMVOLM,
																UM_VOLM 	= $UM_VOLM+$ITMVOLM,
																UM_AMOUNT 	= $UM_AMOUNT+$Base_Debet
															WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
											$this->db->query($sqlUpdITML);
									// END : Update ITM Used
								}
							}
							$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						
						$curr_rate				= 1;
						$isDirect				= 1;
						$Ref_Number				= $d['Ref_Number'];
						$Other_Desc				= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
						$Journal_DK				= $JournalD_Pos;
						$Journal_Type			= $Journal_Type;
						$isTax					= $isTax;
						
						$insSQL	= "INSERT INTO tbl_journaldetail_gj (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
										JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
										Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
										curr_rate,isDirect,ITM_CODE,ITM_CATEG,Ref_Number,Other_Desc,Journal_DK,Journal_Type,isTax,GEJ_STAT,JournalH_Date)
									VALUE ('$JournalH_Code', 'GEJ', '$Acc_Id', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '$JOBCODEID', 'IDR', 
										$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, 
										$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE',
										'$ITM_CATEG', '$Ref_Number', '$Other_Desc','$Journal_DK','$Journal_Type',$isTax,$GEJ_STAT,'$JournalH_Date')";
						$this->db->query($insSQL);

						// UPDATE REALIZ Voucher TLK
						$TLK_USTATUS = 0;
						if($Ref_Number != '' && $JournalD_Pos == 'K')
						{
							// get Ref_Number
							$this->db->from("tbl_tsflk");
							$this->db->where(["TLK_NUM" => $Ref_Number, "PRJCODE" => $proj_Code, "TLK_STATUS" => 3]);
							$this->db->where_in("TLK_USTATUS", [0,1]);
							$getREFNUM = $this->db->get();
							if($getREFNUM->num_rows() > 0)
							{
								$TTLK_REALIZ = 0;
								foreach($getREFNUM->result() as $rTLK):
									$TLK_NUM 		= $rTLK->TLK_NUM;
									$TLK_AMOUNT 	= $rTLK->TLK_AMOUNT;
									$TLK_REALIZ 	= $rTLK->TLK_REALIZ;
									$TTLK_REALIZ 	= $TTLK_REALIZ + $TLK_REALIZ + $JournalD_Kredit;
								endforeach;

								if($TLK_AMOUNT == $TTLK_REALIZ)
									$TLK_USTATUS = 2;
								else
									$TLK_USTATUS = 1;

								$sqlUpdTLK	= "UPDATE tbl_tsflk SET TLK_REALIZ = $TTLK_REALIZ, TLK_USTATUS = $TLK_USTATUS
										WHERE TLK_NUM = '$Ref_Number' AND PRJCODE = '$proj_Code' AND TLK_STATUS = 3";
								$this->db->query($sqlUpdTLK);
							}
						}
					}
				// END : DETAIL JOURNAL
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
											'TBLNAME'		=> "tbl_journalheader_gj");
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
											'TBL_NAME' 		=> "tbl_journalheader_gj",	// TABLE NAME
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
											'TBLNAME'		=> "tbl_journalheader_gj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('GEJ_STAT');			// IF "ADD" CONDITION ALWAYS = PR_STAT
					$parameters 	= array('DOC_CODE' 		=> $JournalH_Code,	// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "GEJ",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_journalheader_gj",	// TABLE NAME
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
					$upJH		= "UPDATE tbl_journalheader_gj SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											//'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader_gj");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// RESET DETAIL
					$this->m_gej_entry->deleteGEJDetail($JournalH_Code);
				
				// START : DETAIL JOURNAL
					$Base_DebetTOT		= 0;
					$Base_KreditTOT		= 0;
					$Base_DebetTOT_Tax	= 0;
					foreach($_POST['data'] as $d)
					{
						$JournalH_Code	= $JournalH_Code;
						$Acc_Id			= $d['Acc_Id'];
						$ITM_CODE		= $d['ITM_CODE'];
						$ITM_CATEG		= $d['ITM_CATEG'];
						$JOBCODEID		= $d['JOBCODEID'];
						$proj_Code		= $d['proj_Code'];
						$proj_CodeHO	= $d['proj_CodeHO'];
						$PRJPERIOD		= $d['PRJPERIOD'];
						$JournalD_Pos	= $d['JournalD_Pos'];
						$isTax			= $d['isTax'];
						$journalAmount	= $d['JournalD_Amount'];
						
						$proj_Code		= $d['proj_Code'];
						$ACC_NUM		= $d['Acc_Id'];			// Detail Account
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 		= explode("~",$syncPRJ);
						$jmD 			= count($dataPecah);

						$Journal_Type		= '';
						if($JournalD_Pos == 'D')
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;

							$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
						}
						elseif($JournalD_Pos == 'K')
						{
							$JournalD_Debet	= 0;
							$Base_Debet		= 0;
							$COA_Debet		= 0;
							$JournalD_Kredit= $d['JournalD_Amount'];
							$Base_Kredit	= $d['JournalD_Amount'];
							$COA_Kredit		= $d['JournalD_Amount'];

							$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						
						
						$curr_rate			= 1;
						$isDirect			= 1;
						$Ref_Number			= $d['Ref_Number'];
						$Other_Desc			= htmlspecialchars($d['Other_Desc'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
						if($Other_Desc == '')
						{
							$Other_Desc		= $AH_NOTES;
						}
						$Journal_DK			= $JournalD_Pos;
						$Journal_Type		= $Journal_Type;
						$isTax				= $isTax;

						$Acc_Name 			= "-";
						$sqlNm 				= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Id' LIMIT 1";
						$resNm				= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name		= $rowNm->Account_NameId;
						endforeach;
						
						$insSQL	= "INSERT INTO tbl_journaldetail_gj (JournalH_Code, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD, JOBCODEID, Currency_id,
										JournalD_Debet, JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
										Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
										curr_rate,isDirect, ITM_CODE, ITM_CATEG, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										GEJ_STAT, JournalH_Date, Acc_Name)
									VALUE ('$JournalH_Code', 'GEJ', '$Acc_Id', '$proj_Code', '$proj_CodeHO', '$PRJPERIOD', '$JOBCODEID', 'IDR', 
										$JournalD_Debet, $JournalD_Debet_tax, $JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax,
										$Base_Kredit, $Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 
										1, 1, '$ITM_CODE', '$ITM_CATEG', '$Ref_Number', '$Other_Desc', '$Journal_DK', '$Journal_Type', $isTax,
										$GEJ_STAT, '$JournalH_Date', '$Acc_Name')";
						$this->db->query($insSQL);
					}
				// END : DETAIL JOURNAL
			}

			// START : UPDATE AKUN LAWAN
				$Acc_Cr 		= "";
				$sqlACC_Cr 		= "SELECT Acc_Id FROM tbl_journaldetail_gj WHERE proj_Code = '$proj_Code' AND Journal_DK = 'K' AND JournalH_Code = '$JournalH_Code'";
				$resACC_Cr		= $this->db->query($sqlACC_Cr)->result();
				foreach($resACC_Cr as $row_Cr):
					$Acc_Cr		= $row_Cr->Acc_Id;
				endforeach;

				$updAcc_Db		= "UPDATE tbl_journaldetail_gj SET Acc_Id_Cross = '$Acc_Cr' WHERE proj_Code = '$proj_Code' AND JournalH_Code = '$JournalH_Code'";
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
				$MenuCode 		= 'MN106';
				$TTR_CATEG		= 'UP';
				
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
		
			if($GEJ_STAT == 3 && $AH_ISLAST == 1)
			{
				$JRNH_CODE 		= $JournalH_Code;
				// SART : GET ALL ROW DATA AND INSERT INTO TABLE
					$patNAll 	= 0;
					$s_01		= "SELECT proj_Code FROM tbl_journaldetail_gj WHERE JournalH_Code = '$JRNH_CODE' GROUP BY proj_Code ORDER BY proj_Code";
					$r_01 		= $this->db->query($s_01)->result();
					foreach($r_01 as $rw_01) :
						$projCode 	= $rw_01->proj_Code;
						$LastUpdate = date('Y-m-d H:i:s');

						$s_ISHO 	= "SELECT PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$projCode' LIMIT 1";
						$r_ISHO	= $this->db->query($s_ISHO)->result();
						foreach($r_ISHO as $rowISHO):
							$PRJCODE_HO		= $rowISHO->PRJCODE_HO;
						endforeach;

						$s_CPY 		= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Emp_ID,
											Created, LastUpdate, Pattern_Type, Wh_id, Reference_Type, proj_Code, proj_CodeHO, PRJPERIOD, Journal_Amount, Manual_No,
											GEJ_STAT, STATDESC, STATCOL, CREATERNM)
											SELECT JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Emp_ID,
											Created, LastUpdate, Pattern_Type, Wh_id, Reference_Type, '$projCode', '$PRJCODE_HO', PRJPERIOD, Journal_Amount, Manual_No,
											GEJ_STAT, STATDESC, STATCOL, CREATERNM
											FROM tbl_journalheader_gj
											WHERE JournalH_Code = '$JRNH_CODE'";
						$this->db->query($s_CPY);

						$proj_Code2	= "";
						$Manual_No2 = "";
						$PattN 		= 0;
						$TOT_DEBET 	= 0;
						$TOT_KREDIT = 0;
						$s_02		= "SELECT JournalD_Id, proj_Code, Journal_DK, Acc_Id, Base_Debet, Base_Kredit FROM tbl_journaldetail_gj
										WHERE JournalH_Code = '$JRNH_CODE' AND proj_Code = '$projCode' ORDER BY Journal_DK";
						$r_02 		= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02) :
							$patNAll 		= $patNAll+1;
							$PattN 			= $PattN+1;
							$JournalD_Id 	= $rw_02->JournalD_Id;
							$proj_Code 		= $rw_02->proj_Code;
							$Journal_DK 	= $rw_02->Journal_DK;
							$Acc_Id 		= $rw_02->Acc_Id;
							$Base_Debet 	= $rw_02->Base_Debet;
							$Base_Kredit 	= $rw_02->Base_Kredit;

							$TOT_DEBET 		= $TOT_DEBET+$Base_Kredit;
							$TOT_KREDIT 	= $TOT_KREDIT+$Base_Debet;

							$s_HO 		= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$proj_Code' LIMIT 1";
							$r_HO 			= $this->db->query($s_HO)->result();
							foreach($r_HO as $rW_HO) :
								$PRJCODE_HO = $rW_HO ->PRJCODE_HO;
							endforeach;

							$s_CPY 			= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD,
													Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit, Other_Desc,
													Journal_DK, GEJ_STAT, Created)
												SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD,
													Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit, Other_Desc,
													Journal_DK, GEJ_STAT, Created
												FROM tbl_journaldetail_gj
												WHERE JournalD_Id = $JournalD_Id";
							$this->db->query($s_CPY);

							// START : CREATE JOURNAL DETAIL DEBET
				                $syncPRJ	= '';
				                $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$Acc_Id' LIMIT 1";
				                $resISHO	= $this->db->query($sqlISHO)->result();
				                foreach($resISHO as $rowISHO):
				                    $syncPRJ= $rowISHO->syncPRJ;
				                endforeach;
				                $dataPecah 	= explode("~",$syncPRJ);
				                $jmD 		= count($dataPecah);
				            	
				                if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
						            	if($Journal_DK == 'D')
						            	{

											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																Base_Debet2 = Base_Debet2+$Base_Debet, BaseD_$accYr = BaseD_$accYr+$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
										else
										{
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
																Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								}
							// END : CREATE JOURNAL DETAIL DEBET

							// START : CARI LAWANNYA
								$s_vsC		= "tbl_journaldetail_gj WHERE JournalH_Code = '$JRNH_CODE' AND Journal_DK != '$Journal_DK'";
								$r_vsC 		= $this->db->count_all($s_vsC);
								if($r_vsC == 1)
								{
									$s_vs		= "SELECT JournalD_Id, proj_Code, Journal_DK, Acc_Id, Base_Debet, Base_Kredit FROM tbl_journaldetail_gj
													WHERE JournalH_Code = '$JRNH_CODE' AND Journal_DK != '$Journal_DK'";
									$r_vs 		= $this->db->query($s_vs)->result();
									foreach($r_vs as $rw_vs) :
										$patNAll 		= $patNAll+1;
										$PattN 			= $PattN+1;
										$JournalD_Id2 	= $rw_vs->JournalD_Id;
										$proj_Code2 	= $rw_vs->proj_Code;
										$Journal_DK2 	= $rw_vs->Journal_DK;
										$Acc_Id2 		= $rw_vs->Acc_Id;
										$Base_Debet2 	= $rw_vs->Base_Debet;
										$Base_Kredit2 	= $rw_vs->Base_Kredit;

										/*$s_HO2 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$proj_Code2' LIMIT 1";
										$r_HO2 			= $this->db->query($s_HO2)->result();
										foreach($r_HO2 as $rW_HO2) :
											$PRJCODE_HO = $rW_HO2 ->PRJCODE_HO;
										endforeach;*/

										$s_CPY2 		= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD,
																Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit, Other_Desc,
																Journal_DK, GEJ_STAT, Created)
															SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, '$proj_Code', '$PRJCODE_HO', PRJPERIOD,
																Currency_id, '$Base_Kredit', '$Base_Debet', '$Base_Kredit', '$Base_Debet', '$Base_Kredit', '$Base_Debet', Other_Desc,
																Journal_DK, GEJ_STAT, Created
															FROM tbl_journaldetail_gj
															WHERE JournalD_Id = $JournalD_Id2";
										$this->db->query($s_CPY2);
										ECHO "$s_CPY2<br>";

										// START : CREATE JOURNAL DETAIL DEBET
							                $syncPRJ	= '';
							                $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code2' AND Account_Number = '$Acc_Id2' LIMIT 1";
							                $resISHO	= $this->db->query($sqlISHO)->result();
							                foreach($resISHO as $rowISHO):
							                    $syncPRJ= $rowISHO->syncPRJ;
							                endforeach;
							                $dataPecah 	= explode("~",$syncPRJ);
							                $jmD 		= count($dataPecah);
							            	
							                if($jmD > 0)
											{
												$SYNC_PRJ	= '';
												for($i=0; $i < $jmD; $i++)
												{
													$SYNC_PRJ	= $dataPecah[$i];
									            	if($Journal_DK == 'D')
									            	{

														$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet2, 
																			Base_Debet2 = Base_Debet2+$Base_Debet2, BaseD_$accYr = BaseD_$accYr+$Base_Debet2
																		WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id2'";
														$this->db->query($sqlUpdCOA);
													}
													else
													{
														$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit2, 
																			Base_Kredit2 = Base_Kredit2+$Base_Kredit2, BaseK_$accYr = BaseK_$accYr+$Base_Kredit2
																		WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id2'";
														$this->db->query($sqlUpdCOA);
													}
												}
											}
										// END : CREATE JOURNAL DETAIL DEBET
									endforeach;
								}
								else
								{
									$s_vs		= "SELECT JournalD_Id, proj_Code, Journal_DK, Acc_Id, Base_Debet, Base_Kredit FROM tbl_journaldetail_gj
													WHERE JournalH_Code = '$JRNH_CODE' AND Journal_DK != '$Journal_DK'";
									$r_vs 		= $this->db->query($s_vs)->result();
									foreach($r_vs as $rw_vs) :
										$patNAll 		= $patNAll+1;
										$PattN 			= $PattN+1;
										$JournalD_Id2 	= $rw_vs->JournalD_Id;
										$proj_Code2 	= $rw_vs->proj_Code;
										$Journal_DK2 	= $rw_vs->Journal_DK;
										$Acc_Id2 		= $rw_vs->Acc_Id;
										$Base_Debet2 	= $rw_vs->Base_Debet;
										$Base_Kredit2 	= $rw_vs->Base_Kredit;

										/*$s_HO2 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$proj_Code2' LIMIT 1";
										$r_HO2 			= $this->db->query($s_HO2)->result();
										foreach($r_HO2 as $rW_HO2) :
											$PRJCODE_HO = $rW_HO2 ->PRJCODE_HO;
										endforeach;*/

										$s_CPY2 		= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalH_Date, JournalType, Acc_Id, proj_Code, proj_CodeHO, PRJPERIOD,
																Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit, Other_Desc,
																Journal_DK, GEJ_STAT, Created)
															SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, '$proj_Code', '$PRJCODE_HO', PRJPERIOD,
																Currency_id, JournalD_Debet, JournalD_Kredit, Base_Debet, Base_Kredit, COA_Debet, COA_Kredit, Other_Desc,
																Journal_DK, GEJ_STAT, Created
															FROM tbl_journaldetail_gj
															WHERE JournalD_Id = $JournalD_Id2";
										$this->db->query($s_CPY2);
										ECHO "$s_CPY2<br>";

										// START : CREATE JOURNAL DETAIL DEBET
							                $syncPRJ	= '';
							                $sqlISHO 	= "SELECT syncPRJ FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code2' AND Account_Number = '$Acc_Id2' LIMIT 1";
							                $resISHO	= $this->db->query($sqlISHO)->result();
							                foreach($resISHO as $rowISHO):
							                    $syncPRJ= $rowISHO->syncPRJ;
							                endforeach;
							                $dataPecah 	= explode("~",$syncPRJ);
							                $jmD 		= count($dataPecah);
							            	
							                if($jmD > 0)
											{
												$SYNC_PRJ	= '';
												for($i=0; $i < $jmD; $i++)
												{
													$SYNC_PRJ	= $dataPecah[$i];
									            	if($Journal_DK == 'D')
									            	{

														$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet2, 
																			Base_Debet2 = Base_Debet2+$Base_Debet2, BaseD_$accYr = BaseD_$accYr+$Base_Debet2
																		WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id2'";
														$this->db->query($sqlUpdCOA);
													}
													else
													{
														$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit2, 
																			Base_Kredit2 = Base_Kredit2+$Base_Kredit2, BaseK_$accYr = BaseK_$accYr+$Base_Kredit2
																		WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id2'";
														$this->db->query($sqlUpdCOA);
													}
												}
											}
										// END : CREATE JOURNAL DETAIL DEBET
									endforeach;
								}
							// END : CARI LAWANNYA

							$proj_Code2	= $proj_Code;
							$Manual_No2 = $Manual_No;
						endforeach;
					endforeach;
				// END : GET ALL ROW DATA AND INSERT INTO TABLE
			}

			$url			= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
			$MenuCode 		= 'MN106';
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
			
			$this->load->view('v_gl/v_gej_entry/gej_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataITMS() // GOOD
	{
		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$collData	= $_GET['id'];
		$explData	= explode("~", $collData);
		$PRJCODE 	= $explData[0];
		$THEROW 	= $explData[1];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Account')$Account = $LangTransl;
    		if($TranslCode == 'JobNm')$JobNm = $LangTransl;
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
			
			$columns_valid 	= array("A.JOBCODEID", 
									"A.ITM_CODE", 
									"A.JOBDESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej_entry->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_gej_entry->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$PRJCODE        = $dataI['PRJCODE'];
                $PRJCODE_HO     = $dataI['PRJCODE_HO'];
                $JOBCODEID      = $dataI['JOBCODEID'];
                $ITM_CODE       = $dataI['ITM_CODE'];
                $JOBPARENT      = $dataI['JOBPARENT'];
                $Account_Number = $dataI['ACC_ID_UM'];
                //$ACCNUM           = $dataI['ACC_ID'];             // 0
                $ACCNUM         = $dataI['ACC_ID_UM'];          // 0
                $ITM_NAME       = $dataI['ITM_NAME'];           // 1
                $serialNumber   = '';
                $ITM_UNIT       = $dataI['ITM_UNIT'];
                $ITM_GROUP      = $dataI['ITM_GROUP'];
                $ITM_CATEG      = $dataI['ITM_CATEG'];
                $ITM_PRICE      = $dataI['ITM_PRICE'];
                $ITM_VOLM       = $dataI['ITM_VOLM'];
                $ITM_STOCK      = $dataI['ITM_STOCK'];
                $ITM_USED       = $dataI['ITM_USED'];
                $itemConvertion = 1;
                $ITM_AMOUNT     = $ITM_PRICE * $ITM_VOLM;
                $tempTotMax     = $ITM_VOLM - $ITM_USED;
                $REQ_VOLM       = $dataI['REQ_VOLM'];
                $REQ_AMOUNT     = $dataI['REQ_AMOUNT'];
                $PO_AMOUNT      = $dataI['PO_AMOUNT'];
                $PO_VOLM        = $dataI['PO_VOLM'];
                $IR_VOLM        = $dataI['IR_VOLM'];
                $IR_AMOUNT      = $dataI['IR_AMOUNT'];                          
                $ITM_BUDG       = $ITM_VOLM;
                if($ITM_BUDG == '')
                    $ITM_BUDG   = 0;
                $PO_VOLM        = $dataI['PO_VOLM'];
                $PO_AMOUNT      = $dataI['PO_AMOUNT'];
                $ITM_BUDG       = $ITM_AMOUNT;
                $TOT_USEDQTY    = $REQ_VOLM;
                if($TOT_USEDQTY == '')
                    $TOT_USEDQTY = 0;

                $JOBDESCPR      = "";
                $sqlJOBPAR      = "SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBPARENT'";
                $resJOBPAR      = $this->db->query($sqlJOBPAR)->result();
                foreach($resJOBPAR as $rowJP) :
                    $JOBDESCPR  = strtoupper($rowJP->JOBDESC);
                endforeach;
                    
                $REMREQ_QTY     = $ITM_VOLM - $REQ_VOLM;
                $REMREQ_AMN     = ($ITM_VOLM * $ITM_PRICE) - ($REQ_AMOUNT);
                //echo "REMREQ_QTY = $REMREQ_QTY, REMREQ_AMN = $REMREQ_AMN<br>";
                $disabledB      = 0;
                if($REMREQ_QTY <= 0)
                    $disabledB  = 1;
                if($REMREQ_AMN <= $PO_AMOUNT)
                    $disabledB  = 1;

				// OTHER SETT
					if($ACCNUM == '')
					{
						$JobV	= "___ not_set___";
						$chkBox	= "<input type='radio' name='chk_' id='chk_".$noU."' value='".$Account_Number."|".$ITM_NAME."|".$ITM_CODE."|".$ITM_GROUP."|".$THEROW."|".$JOBCODEID." onClick='pickThis1(".$noU.")' disabled />";
						$accNo 	= "<span class='label label-danger' style='font-size:12px'>".$JobV."</span>";
					}
					else
					{
						//$JobV	= "$PRJCODE - $Account_Number - $ITM_GROUP";
						$JobV	= "$Account_Number";
						$chkBox	= "<input type='radio' name='chk_' id='chk_".$noU."' value='".$Account_Number."|".$ITM_NAME."|".$ITM_CODE."|".$ITM_GROUP."|".$THEROW."|".$JOBCODEID."' onClick='pickThis1(".$noU.")' />";
						$accNo 	= $JobV;
					}

				$ITM_NAME 		= wordwrap($ITM_NAME, 60, "<br>", TRUE);

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"$ITM_CODE
										  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>$Account : ".$accNo."</label>
										  	</div>",
											$JOBCODEID." : ".ucwords(strtolower($ITM_NAME))."
										  	<div style='margin-left: 10px; font-style: italic; white-space:nowrap'>
										  		<label style='white-space:nowrap'>$JobNm : $JOBPARENT $JOBDESCPR</label>
										  	</div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataCOA() // GOOD
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$collData	= $_GET['id'];
		$explData	= explode("~", $collData);
		$PRJCODE 	= $explData[0];
		$THEROW 	= $explData[1];

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
		{
			$sqlPRJ 	= "SELECT A.PRJCODE_HO FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE'";
			$resPRJ 	= $this->db->query($sqlPRJ)->result();
			foreach ($resPRJ as $key) {
				$PRJCODEX = $key->PRJCODE_HO;
			}
		}
		else
		{
			$PRJCODEX 	= $PRJCODE;
			$ADDQRY		= "A.PRJCODE";
		}

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
			
			$columns_valid 	= array("B.Account_Number", 
									"B.Account_NameId", 
									"B.Account_NameEn");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej_entry->get_AllDataCOAC($PRJCODEX, $DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_gej_entry->get_AllDataCOAL($PRJCODEX, $DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $isDisabled         = 0;
                $JOBCODEID          = '';
                $Account_Number     = $dataI['Account_Number'];     // 0
                $Account_NameEn     = $dataI['Account_NameEn'];
                $Account_NameId     = $dataI['Account_NameId'];     // 1
                $Account_Class      = $dataI['Account_Class'];
                $ITM_CATEG          = '';
                
                $Base_OpeningBalance= $dataI['Base_OpeningBalance'];
                $Base_Debet         = $dataI['Base_Debet'];
                $Base_Kredit        = $dataI['Base_Kredit'];
                $balanceVal         = $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
                
                //if($Account_Class == 4 && $balanceVal == 0)
                    //$isDisabled       = 1;
                
                $ITM_CODE           = '';

				// OTHER SETT
					if($isDisabled == 1)
					{
						$chkBox	= "<input type='radio' name='chk_' id='chk_".$noU."' value='".$Account_Number."|".$Account_NameId."|".$ITM_CODE."|".$ITM_CATEG."|".$THEROW."|".$JOBCODEID."' onClick='pickThis1(".$noU.")' disabled />";
					}
					else
					{
						$chkBox	= "<input type='radio' name='chk_' id='chk_".$noU."' value='".$Account_Number."|".$Account_NameId."|".$ITM_CODE."|".$ITM_CATEG."|".$THEROW."|".$JOBCODEID."' onClick='pickThis1(".$noU.")' />";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='white-space:nowrap'>$Account_Number</div>",
											"<div style='white-space:nowrap'>".wordwrap($Account_NameId, 60, "<br>", TRUE)."</div>",
											number_format($balanceVal, 2));

				$noU			= $noU + 1;
			}
				/*$output['data'][] 	= array("",
											"",
											"",
											"");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

  	function get_AllDataACC() // GOOD
	{
		$this->db->select('APPLEV');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
		    $APPLEV = $row->APPLEV;
		endforeach;

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
			$ADDQRY		= "A.PRJCODE_HO";
		else
			$ADDQRY		= "A.PRJCODE";

		$PRJTYPE    	= "3";
		if($APPLEV == 'PRJ')
		    $PRJTYPE    = "2";

		//$PRJCODE		= $_GET['id'];

		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$collData	= $_GET['id'];
		$explData	= explode("~", $collData);
		$PRJCODE 	= $explData[0];
		$THEROW 	= $explData[1];

		if($APPLEV == 'HO') // BERARTI HARUS MENAMPILKAN SEMUA TURUNAN DARI INDUK PERUSAHAAN YANG DISETTING PADA USER OTORISASI, DENGAN PRJTYPE TETAP 3
		{
			$sqlPRJ 	= "SELECT A.PRJCODE_HO FROM tbl_project A WHERE A.PRJCODE = '$PRJCODE'";
			$resPRJ 	= $this->db->query($sqlPRJ)->result();
			foreach ($resPRJ as $key) {
				$PRJCODEX = $key->PRJCODE_HO;
			}
		}
		else
		{
			$PRJCODEX 	= $PRJCODE;
			$ADDQRY		= "A.PRJCODE";
		}

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
			
			$columns_valid 	= array("B.Account_Number", 
									"B.Account_NameId", 
									"B.Account_NameEn");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej_entry->get_AllDataACCBRC($PRJCODEX, $DefEmp_ID, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_gej_entry->get_AllDataACCBRL($PRJCODEX, $DefEmp_ID, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $isDisabled         = 0;
                $JOBCODEID          = '';
                $Account_Number     = $dataI['Account_Number'];     // 0
                $Account_NameEn     = $dataI['Account_NameEn'];
                $Account_NameId     = $dataI['Account_NameId'];     // 1
                $Account_Class      = $dataI['Account_Class'];
                $ITM_CATEG          = '';
                
                $Base_OpeningBalance= $dataI['Base_OpeningBalance'];
                $Base_Debet         = $dataI['Base_Debet'];
                $Base_Kredit        = $dataI['Base_Kredit'];
                $balanceVal         = $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
                
                //if($Account_Class == 4 && $balanceVal == 0)
                    //$isDisabled       = 1;
                
                $ITM_CODE           = '';

				// OTHER SETT
					if($isDisabled == 1)
					{
						$chkBox	= "<input type='radio' name='chk_' id='chk_".$noU."' value='".$Account_Number."|".$Account_NameId."|".$ITM_CODE."|".$ITM_CATEG."|".$THEROW."|".$JOBCODEID."' onClick='pickThis4(".$noU.")' disabled />";
					}
					else
					{
						$chkBox	= "<input type='radio' name='chk_' id='chk_".$noU."' value='".$Account_Number."|".$Account_NameId."|".$THEROW."' onClick='pickThis4(".$noU.")' />";
					}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
											"<div style='white-space:nowrap'>$Account_Number</div>",
											"<div style='white-space:nowrap'>".wordwrap($Account_NameId, 60, "<br>", TRUE)."</div>",
											number_format($balanceVal, 2));

				$noU			= $noU + 1;
			}
				/*$output['data'][] 	= array("",
											"",
											"",
											"");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function get_AllDataTLK()
	{
		// $PRJCODE		= $_GET['id'];
		$collData		= $_GET['id'];
		$explData		= explode("~", $collData);
		$PRJCODE 		= $explData[0];
		$JournalH_Code 	= $explData[1];
		$selectRow 		= $explData[2];
			
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'srcPayment')$srcPayment = $LangTransl;
			if($TranslCode == 'RealizationValue')$RealizationValue = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
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
			
			$columns_valid 	= array("TLK_NUM",
									"TLK_CODE",
									"TLK_DATE",
									"TLK_DESC",
									"TLK_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$endord			= $start + $length;
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gej_entry->get_AllData_VTLKC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej_entry->get_AllData_VTLKL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			$TREM_AMOUNT 	= 0;
			$isDisabled 	= 0;
			foreach ($query->result_array() as $dataI) 
			{
				$TLK_NUM 			= $dataI['TLK_NUM'];
				$TLK_CODE 			= $dataI['TLK_CODE'];
				$TLK_DATE 			= date('d M Y', strtotime($dataI['TLK_DATE']));
				$PRJCODE 			= $dataI['PRJCODE'];
				$TLK_DESC 			= htmlspecialchars($dataI['TLK_DESC']);
				$TLK_DESC2 			= htmlspecialchars($dataI['TLK_DESC2']);
				$TLK_AMOUNT 		= $dataI['TLK_AMOUNT'];
				$realizD_Amn 		= $dataI['TLK_REALIZ'];
				$TLK_USTATUS 		= $dataI['TLK_USTATUS'];
				$TLK_STATUS 		= $dataI['TLK_STATUS'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);
				$TLK_CREATED 		= $dataI['TLK_CREATED'];
				$TLK_CREATER 		= $dataI['TLK_CREATER'];
				$theRow 			= $noU;
				$isDisabled         = 0;

				$this->db->select("B.Base_Kredit");
				$this->db->from("tbl_journalheader_gj A");
				$this->db->join("tbl_journaldetail_gj B", "B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code", "INNER");
				$this->db->join("tbl_tsflk C", "C.TLK_NUM = B.Ref_Number AND C.PRJCODE = B.proj_Code", "INNER");
				$this->db->where_in("A.GEJ_STAT", [1,2]);
				$this->db->where(["A.JournalH_Code !=" => $JournalH_Code, "A.proj_Code" => $PRJCODE, "C.TLK_NUM" => $TLK_NUM]);
				$getREALIZ = $this->db->get();
				if($getREALIZ->num_rows() > 0)
				{
					$realizD_Amn = 0;
					foreach($getREALIZ->result() as $r):
						$realizD_Amn1 	= $r->Base_Kredit;
						$realizD_Amn 	= $realizD_Amn + $realizD_Amn1;
					endforeach;
				}

				$TREM_AMOUNT = $TLK_AMOUNT - $realizD_Amn;

				if($isDisabled == 1)
				{
					$chkBox	= "<input type='radio' name='chk_tlk' id='chk_tlk".$noU."' value='".$TLK_NUM."|".$TLK_CODE."|".$TLK_DATE."|".$TLK_DESC."|".$TREM_AMOUNT."|".$realizD_Amn."|".$theRow."|".$selectRow."' onClick='pickThis3(".$noU.")' disabled />";
				}
				else
				{
					$chkBox	= "<input type='radio' name='chk_tlk' id='chk_tlk".$noU."' value='".$TLK_NUM."|".$TLK_CODE."|".$TLK_DATE."|".$TLK_DESC."|".$TREM_AMOUNT."|".$realizD_Amn."|".$theRow."|".$selectRow."' onClick='pickThis3(".$noU.")' />";
				}

				$output['data'][] 	= array("<div style='text-align:center;'>".$chkBox."</div>",
										"<div style='white-space:nowrap'>$TLK_CODE</div>",
										"<div style='white-space:nowrap'>$TLK_DATE</div>",
										"<div style='white-space:nowrap'>$TLK_DESC</div>",
										"<div style='white-space:nowrap'>".number_format($TLK_AMOUNT, 2)."</div>",
										"<div style='white-space:nowrap'>".number_format($TREM_AMOUNT, 2)."</div>");
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function iNdJrnl_0x() // GEJ
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN107';
				$data["mnCode"] 	= 'MN107';
				$data["MenuApp"] 	= 'MN107';
				$data["jrnCat"] 	= 'GEJ';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Umum";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"] = "General Journal";
					////$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN107';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_1x() // PRJ
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN128';
				$data["mnCode"] 	= 'MN128';
				$data["MenuApp"] 	= 'MN128';
				$data["jrnCat"] 	= 'PRJ';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Proyek";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Project";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN128';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_2x() // SAL
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN029';
				$data["mnCode"] 	= 'MN029';
				$data["MenuApp"] 	= 'MN029';
				$data["jrnCat"] 	= 'SAL';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Penjualan";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Sales";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN029';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_3x() // INVT
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN011';
				$data["mnCode"] 	= 'MN011';
				$data["MenuApp"] 	= 'MN011';
				$data["jrnCat"] 	= 'INVT';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Penerimaan / Penggunaan Material";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Acceptance / Use of Materials";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN011';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_4x() // PROD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN204';
				$data["mnCode"] 	= 'MN204';
				$data["MenuApp"] 	= 'MN204';
				$data["jrnCat"] 	= 'PROD';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Produksi";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Production";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN204';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_5x() // FIN
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN217';
				$data["mnCode"] 	= 'MN217';
				$data["MenuApp"] 	= 'MN217';
				$data["jrnCat"] 	= 'FIN';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Keuangan";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Fin & Acc.";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN217';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_6x() // OPN
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN427';
				$data["mnCode"] 	= 'MN427';
				$data["MenuApp"] 	= 'MN427';
				$data["jrnCat"] 	= 'OPN';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Transaksi";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Opn and LPM";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN427';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_7x() // OPN
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;

		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN428';
				$data["mnCode"] 	= 'MN428';
				$data["MenuApp"] 	= 'MN428';
				$data["jrnCat"] 	= 'LPM';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
				{
					$data["mnName"]= "Jurnal Transaksi";
					//$data["mnName"] = $getMN->menu_name_IND;
				}
				else
				{
					$data["mnName"]= "Journal of Opn and LPM";
					//$data["mnName"] = $getMN->menu_name_ENG;
				}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN427';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/iNdJrnl_zx/?id=";
			
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
	
	function iNdJrnl_zx() // OK
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
				$mnCode			= $_GET['mnCode'];
				$MenuApp		= $_GET['MenuApp'];
				$jrnCat			= $_GET['jrnCat'];
				$data["jrnCat"] = $jrnCat;
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
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				if($jrnCat == 'GEJ')					// General Journal
				{
					$BackFunct			 = "iNdJrnl_0x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Umum";
					else
						$data["TrxDesc"] = "General Journal";
				}
				elseif($jrnCat == 'PRJ')				// Project
				{
					$BackFunct			 = "iNdJrnl_1x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Proyek";
					else
						$data["TrxDesc"] = "Journal of Project";
				}
				elseif($jrnCat == 'SAL')				// Sales
				{
					$BackFunct			 = "iNdJrnl_2x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Penjualan";
					else
						$data["TrxDesc"] = "Journal of Sales";
				}
				elseif($jrnCat == 'INVT')				// Acceptance / Use of Materials
				{
					$BackFunct			 = "iNdJrnl_3x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Penerimaan / Penggunaan Material";
					else
						$data["TrxDesc"] = "Journal of Acceptance / Use of Materials";
				}
				elseif($jrnCat == 'PROD')				// Production
				{
					$BackFunct			 = "iNdJrnl_4x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Produksi";
					else
						$data["TrxDesc"] = "Journal of Production";
				}
				elseif($jrnCat == 'FIN')				// Acceptance / Use of MaterialsFinance and Accounting
				{
					$BackFunct			 = "iNdJrnl_5x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Keuangan";
					else
						$data["TrxDesc"] = "Journal of Fin & Acc.";
				}
				elseif($jrnCat == 'OPN')
				{
					$BackFunct			 = "iNdJrnl_6x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "Jurnal Opname";
					else
						$data["TrxDesc"] = "Journal of Opname";
				}
				elseif($jrnCat == 'LPM')
				{
					$BackFunct			 = "iNdJrnl_7x";
					if($this->data['LangID'] == 'IND')
						$data["TrxDesc"] = "LPM - A";
					else
						$data["TrxDesc"] = "Journal of LPM - A";
				}

				$mnCode				= $mnCode;
				$data["MenuApp"] 	= $MenuApp;
				$data["MenuCode"] 	= $mnCode;
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$SOURCEDOC			= "";
			$COLLDATA			= "$PRJCODE~$SOURCEDOC";
			/*$data['addURL'] 	= site_url('c_gl/cgeje0b28t18/iNdJrnl_0x/?id='.$this->url_encryption_helper->encode_url($COLLDATA));*/
			$data['backURL'] 	= site_url('c_gl/cgeje0b28t18/'.$BackFunct.'/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= $mnCode;
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
			
			$this->load->view('v_gl/v_gej/gej', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataJRN() // OK
	{
		$this->load->model('m_gl/m_gej_entry/m_gej_entry', '', TRUE);
		
		$PRJCODE		= $_GET['id'];
		$jrnCat			= $_GET['jrnCat'];
		
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
									"A.JournalH_Date", 
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
			$num_rows 		= $this->m_gej->get_AllDataC($PRJCODE, $search, $jrnCat);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gej->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir, $jrnCat);
			
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$JournalH_Code		= $dataI['JournalH_Code'];
				
				/*$sqlJD 		= "SELECT A.JournalH_Code, A.Other_Desc, A.Acc_Id, A.Acc_Name, A.JournalD_Debet, A.JournalD_Kredit,
									B.Manual_No, B.JournalH_Date, B.JournalH_Desc, B.JournalH_Desc2, B.STATDESC, B.STATCOL, B.CREATERNM
								FROM tbl_journaldetail A
									INNER JOIN tbl_journalheader_gj B ON B.JournalH_Code = A.JournalH_Code
								WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalH_Code = '$JournalH_Code'
									ORDER BY JournalH_Code, Base_Kredit";*/
				$sqlJD 		= "SELECT A.JournalH_Code, A.Other_Desc, A.Acc_Id, A.Acc_Name, SUM(A.JournalD_Debet ) AS JournalD_Debet,
									SUM(A.JournalD_Kredit) AS JournalD_Kredit,
									B.Manual_No, B.JournalH_Date, B.JournalH_Desc, B.JournalH_Desc2, B.STATDESC, B.STATCOL, B.CREATERNM
								FROM tbl_journaldetail_gj A
									INNER JOIN tbl_journalheader_gj B ON B.JournalH_Code = A.JournalH_Code
								WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalH_Code = '$JournalH_Code'
									GROUP BY Acc_Id, A.ISRET
									ORDER BY JournalH_Code, Base_Kredit";
				$resJD		= $this->db->query($sqlJD)->result();

				$JournalH_Code2 = "";
				$totD 			= 0;
				$totK 			= 0;
				foreach($resJD as $rowJD):
					$Manual_No			= $rowJD->Manual_No;
					if($Manual_No == '')
						$Manual_No		= $JournalH_Code;

					$JournalH_Desc		= $rowJD->JournalH_Desc;
					$JournalH_Desc2		= $rowJD->JournalH_Desc2;
					$JournalH_Date		= $rowJD->JournalH_Date;
					$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));

					$jrnCode 			= $Manual_No;
					$jrnDate 			= $JournalH_DateV;
					if($JournalH_Code2	== $JournalH_Code)
					{
						$jrnCode 		= "";
						$jrnDate 		= "";
					}
					
					$Acc_Id				= $rowJD->Acc_Id;
					$AccNameId			= $rowJD->Acc_Name;
					if($AccNameId == '')
					{
						$sqlAccNm		= "SELECT Account_NameId, Account_NameEn FROM tbl_chartaccount
											WHERE Account_Number = '$Acc_Id'";
						$resAccNm 		= $this->db->query($sqlAccNm)->result();
						foreach($resAccNm as $rowAccNm) :
							$AccNameId = $rowAccNm->Account_NameId;
							$AccNameEn = $rowAccNm->Account_NameEn;
						endforeach;
						
						if($this->data['LangID'] == 'IND')
							$AccNameId = $AccNameId;
						else
							$AccNameId = $AccNameEn;
					}

					$STATDESC			= $rowJD->STATDESC;
					$STATCOL			= $rowJD->STATCOL;
					$JournalD_Debet		= $rowJD->JournalD_Debet;
					$JournalD_Kredit	= $rowJD->JournalD_Kredit;
					$totD 				= $totD + $JournalD_Debet;
					$totK 				= $totK + $JournalD_Kredit;

					$CREATERNM			= $rowJD->CREATERNM;
					$empName			= cut_text2 ("$CREATERNM", 15);

					$Other_Desc			= $rowJD->Other_Desc;

					/*if($JournalH_Desc == '')
						$JournalH_Desc1	= $Other_Desc;
					else
						$JournalH_Desc1	= $JournalH_Desc.". ".$Other_Desc;*/

					if($Other_Desc == '')
						$JournalH_Desc1	= $JournalH_Desc;
					else
						$JournalH_Desc1	= $Other_Desc;

	                $revDesc 			= "";
	                if($JournalH_Desc2 != '')
	                {
						$revDesc 	= 	"<br><strong><i class='fa  fa-bell margin-r-5'></i>Revise Note </strong>
								  		<div style='margin-left: 15px'>
									  		<p class='text-muted' style='font-style: italic;'>".$JournalH_Desc2."</p>
									  	</div>";
	                }

	                // SATRT : GET APPROVE HIST.
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
	                // END  : GET APPROVE HIST.

					$output['data'][] 	= array("<div style='white-space:nowrap'>$jrnCode</div>",
											  	$jrnDate,
											  	"<strong><i class='fa fa-book margin-r-5'></i>".$AccNameId." </strong>
									  			<div class='text-muted' style='margin-left: 15px'>
											  		".$JournalH_Desc1.$revDesc."
										  		</div>",
											  	"<div style='white-space:nowrap'>".$Acc_Id."</div>",
											  	"<div style='white-space:nowrap'>".number_format($JournalD_Debet,2)."</div>",
											  	"<div style='white-space:nowrap'>".number_format($JournalD_Kredit,2)."</div>",
											  	"<div style='white-space:nowrap'>".$collAPPIMG."</div>");

					$JournalH_Code2	= $JournalH_Code;
				endforeach;

				/*$output['data'][] 	= array("<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center; background: #EDBB99'>&nbsp;</div>");*/

				$output['data'][] 	= array("<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>",
										  	"<div style='white-space:nowrap'><b>".number_format($totD,2)."</b></div>",
										  	"<div style='white-space:nowrap'><b>".number_format($totK,2)."</b></div>",
										  	"<div style='vertical-align:middle; text-align:center;'>&nbsp;</div>");

				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function getManualNo()
	{
		$ACCID		= $this->input->post('Acc_Id', TRUE);
		$PRJCODE	= $this->input->post('PRJCODE', TRUE);
		$JH_DATE 	= date('Y-m-d');
		$YEAR 		= date('Y');
		$MONTH 		= date('m');
		$DATE 		= date('d');

		$Pattern_Length	= 3;
		$this->db->select("RIGHT(B.Manual_No, $Pattern_Length) AS kode", FALSE);
		$this->db->order_by('B.Manual_No', 'DESC');
		$this->db->limit(1);
		$this->db->from("tbl_journaldetail_gj A");
		$this->db->join("tbl_journalheader_gj B", "B.JournalH_Code = A.JournalH_Code AND B.proj_Code =  A.proj_Code", "INNER");
		$this->db->where(["A.Acc_Id" => $ACCID, "B.proj_Code" => $PRJCODE, "B.JournalH_Date" => $JH_DATE, "B.JournalType" => 'GEJ']);
		$query	= $this->db->get();
		if($query->num_rows() != 0)
		{
			$data	= $query->row();
			$kode	= intval($data->kode) + 1;
		}
		else
		{
			$kode	= 1;
		}

		$lastPatt	= str_pad($kode, $Pattern_Length, "0", STR_PAD_LEFT);

		$BANK_PATT 	= "";
		$this->db->select("LEFT(Account_NameId, 3) AS BANK_PATT", FALSE);
		$this->db->where(["Account_Number" => $ACCID, "PRJCODE" => $PRJCODE]);
		$BANK_PATT 	= $this->db->get("tbl_chartaccount")->row("BANK_PATT");

		$DocNo 		= "$BANK_PATT$YEAR$MONTH$DATE$lastPatt";

		echo $DocNo;
	}

	function chkAMNTLK()
	{
		$TLK_NUM	= $this->input->post('TLK_NUM', TRUE);
		$PRJCODE	= $this->input->post('PRJCODE', TRUE);

		$this->db->select("TLK_NUM, TLK_CODE, PRJCODE, TLK_DESC, TLK_AMOUNT, TLK_REALIZ");
		$this->db->from("tbl_tsflk");
		$this->db->where(["TLK_NUM" => $TLK_NUM, "PRJCODE" => $PRJCODE]);
		$data = $this->db->get()->result();
		echo json_encode($data);
	}
}