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
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Buat Jurnal";
			}
			else
			{
				$data["h1_title"] 	= "Input Jurnal";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN106';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_gl/cgeje0b28t18/gej0b28t18/?id=";
			
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
					$PRJCODE	= $EXP_COLLD[1];
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
			
				$num_rows 			= $this->m_gej_entry->count_all_GEJ($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_gej_entry->get_all_GEJ($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
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
										'TTR_REFDOC'	=> $TTR_REFDOC);
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
				$JournalType		= $dataI['JournalType'];
				$JournalH_Date		= $dataI['JournalH_Date'];
				$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
				
				$GEJ_STAT			= $dataI['GEJ_STAT'];
				$STATDESC			= $dataI['STATDESC'];
				$STATCOL			= $dataI['STATCOL'];
				$CREATERNM			= $dataI['CREATERNM'];
				$empName			= cut_text2 ("$CREATERNM", 15);
				
				$CollCode			= "$PRJCODE~$JournalH_Code";
				$secUpd		= site_url('c_gl/cgeje0b28t18/up0b28t18/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
				$secPrint1	= site_url('c_gl/cgeje0b28t18/prN7_d0c/?id='.$this->url_encryption_helper->encode_url($CollCode));
                                    
				if($GEJ_STAT == 1) 
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   </a>";
				}
				else
				{
					$secPrint	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint1."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   </a>
								   <a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
                                        <i class='glyphicon glyphicon-print'></i>
                                    </a>
									</label>";
				}
				
				$output['data'][] = array("<div style='white-space:nowrap'>$Manual_No</div>",
										  $JournalH_DateV,
										  $dataI['JournalH_Desc'],
										  $empName,
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
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
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['form_action']= site_url('c_gl/cgeje0b28t18/add_process');
			$data['backURL'] 	= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
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
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_gej_entry/gej_entry_form', $data);
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
			
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['form_action']	= site_url('c_gl/cgeje0b28t18/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['THEROW'] 		= $THEROW;
			$data['secShowAll']		= site_url('c_gl/cgeje0b28t18/puSA0b28t18/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllCOA']	= $this->m_gej_entry->count_all_COA($PRJCODE, $DefEmp_ID);
			$data['vwAllCOA'] 		= $this->m_gej_entry->view_all_COA($PRJCODE, $DefEmp_ID)->result();
			
			$data['countAllItem']	= $this->m_gej_entry->count_all_Account($PRJCODE);
			$data['vwAllItem'] 		= $this->m_gej_entry->view_all_Account($PRJCODE)->result();
					
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
			
			$JournalH_Date	= date('Y-m-d',strtotime($this->input->post('JournalH_Date')));
			$Patt_Year		= date('Y',strtotime($this->input->post('JournalH_Date')));
			$Patt_Month		= date('m',strtotime($this->input->post('JournalH_Date')));
			$Patt_Date		= date('d',strtotime($this->input->post('JournalH_Date')));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$Manual_No		= $this->input->post('Manual_No');
			$REF_NUM		= $this->input->post('REF_NUM');
			$Pattern_Type	= $this->input->post('Pattern_Type');
			$SPLCODE		= $this->input->post('SPLCODE');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			
			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier
								WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN106';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('proj_Code');
				$TRXTIME1		= date('ymdHis');
				$JournalH_Code	= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'Manual_No' 		=> $Manual_No,
									'REF_NUM' 			=> $REF_NUM,
									'JournalType' 		=> 'GEJ',	// Cash Project
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'GEJ',
									'Emp_ID'			=> $DefEmp_ID,
									'Created'			=> $GEJ_CREATED,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $PRJCODE,
									'proj_Code'			=> $PRJCODE,
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
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// UPDATE JOBDETAIL ITEM
				if($GEJ_STAT == 3)
				{
					$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
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
						$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH2);
					}
				}
			
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
			
			$Base_DebetTOT		= 0;
			$Base_KreditTOT		= 0;
			$Base_DebetTOT_Tax	= 0;
			foreach($_POST['data'] as $d)
			{
				$JournalH_Code	= $JournalH_Code;
				$Acc_Id			= $d['Acc_Id'];
				$ITM_CODE		= $d['ITM_CODE'];
				$ITM_CATEG		= $d['ITM_CATEG'];
				$proj_Code		= $d['proj_Code'];
				$JournalD_Pos	= $d['JournalD_Pos'];
				$isTax			= $d['isTax'];
				$journalAmount	= $d['JournalD_Amount'];
				
				$PRJCODE		= $d['proj_Code'];
				$ACC_NUM		= $d['Acc_Id'];			// Detail Account
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 		= explode("~",$syncPRJ);
				$jmD 			= count($dataPecah);
				
				if($isTax == 0)
				{
					$Journal_Type		= '';
					//$isTax			= 0;
					if($JournalD_Pos == 'D')
					{
						$JournalD_Debet	= $d['JournalD_Amount'];
						$Base_Debet		= $d['JournalD_Amount'];
						$COA_Debet		= $d['JournalD_Amount'];
						$JournalD_Kredit= 0;
						$Base_Kredit	= 0;
						$COA_Kredit		= 0;
						
						// UPDATE AMOUNT JOURNAL HEADER
							$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$Base_Debet
											WHERE JournalH_Code = '$JournalH_Code'";
							$this->db->query($sqlUpdJH);
						
						if($GEJ_STAT == 3 && $AH_ISLAST == 1)
						{
							// START : Update to COA - Debit
								/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
														Base_Debet2 = Base_Debet2+$Base_Debet
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
								$this->db->query($sqlUpdCOA);*/
								
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							
							// START : UPDATE L/R								
								if($ITM_CATEG == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'SUBK')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'ADM')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
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
								/*$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
													Base_Kredit2 = Base_Kredit2+$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
								$this->db->query($sqlUpdCOA);*/
								
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
															Base_Kredit2 = Base_Kredit2+$Base_Kredit
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Kredit
							
							// START : UPDATE L/R								
								if($ITM_CATEG == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'SUBK')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'ADM')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
						}
						$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
					}
					
					$JournalD_Debet_tax		= 0;
					$Base_Debet_tax			= 0;
					$COA_Debet_tax			= 0;
					$JournalD_Kredit_tax	= 0;
					$Base_Kredit_tax		= 0;
					$COA_Kredit_tax			= 0;
				}
				else // not use / hold
				{
					$Journal_Type		= 'TAX';
					//$isTax			= 1;
					if($JournalD_Pos = 'D')
					{
						$JournalD_Debet_tax		= $d['JournalD_Amount'];
						$Base_Debet_tax			= $d['JournalD_Amount'];
						$COA_Debet_tax			= $d['JournalD_Amount'];
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						if($GEJ_STAT == 3 && $AH_ISLAST == 1)
						{
							// START : Update to COA - Debit
								/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Debit = Base_Debit+$COA_Debet_tax, 
														Base_Debit2 = Base_Debit2+$COA_Debet_tax
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
								$this->db->query($sqlUpdCOAD);*/
								
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debit = Base_Debit+$COA_Debet_tax, 
															Base_Debit2 = Base_Debit2+$COA_Debet_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
							
							// START : UPDATE L/R								
								if($ITM_CATEG == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$COA_Debet_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$COA_Debet_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'SUBK')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$COA_Debet_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'ADM')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$COA_Debet_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$COA_Debet_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$COA_Debet_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
						}
						$Base_DebetTOT_Tax	= $Base_DebetTOT_Tax + $Base_Debet_tax;
					}
					elseif($JournalD_Pos = 'K')
					{
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= $d['JournalD_Amount'];
						$Base_Kredit_tax		= $d['JournalD_Amount'];
						$COA_Kredit_tax			= $d['JournalD_Amount'];
						if($GEJ_STAT == 3)
						{
							// START : Update to COA - Kredit
								/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$COA_Kredit_tax, 
														Base_Kredit2 = Base_Kredit2+$COA_Kredit_tax
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
								$this->db->query($sqlUpdCOA);*/
								
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$COA_Kredit_tax, 
															Base_Kredit2 = Base_Kredit2+$COA_Kredit_tax
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Kredit						
							
							// START : UPDATE L/R								
								if($ITM_CATEG == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$COA_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$COA_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'SUBK')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$COA_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'ADM')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$COA_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$COA_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_CATEG == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$COA_Kredit_tax 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							// END : UPDATE L/R
						}
					}
					
					$JournalD_Debet		= 0;
					$Base_Debet			= 0;
					$COA_Debet			= 0;
					$JournalD_Kredit	= 0;
					$Base_Kredit		= 0;
					$COA_Kredit			= 0;
				}
				
				$curr_rate			= 1;
				$isDirect			= 1;
				$Ref_Number			= $d['Ref_Number'];
				$Other_Desc			= $d['Other_Desc'];
				$Journal_DK			= $JournalD_Pos;
				$Journal_Type		= $Journal_Type;
				$isTax				= $isTax;
				
				$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
								JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
								Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
								curr_rate,isDirect,ITM_CODE,ITM_CATEG,Ref_Number,Other_Desc,Journal_DK,Journal_Type,isTax)
							VALUE ('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax,
								$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit, 
								$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE',
								'$ITM_CATEG', '$Ref_Number', '$Other_Desc','$Journal_DK','$Journal_Type',$isTax)";
				$this->db->query($insSQL);
			}
			
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
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($proj_Code));
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
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$JournalH_Code	= $_GET['id'];
			$JournalH_Code	= $this->url_encryption_helper->decode_url($JournalH_Code);
			
			$getGEJ 		= $this->m_gej_entry->get_GEJ_by_number($JournalH_Code)->row();
			$data['default']['JournalH_Code'] 	= $getGEJ->JournalH_Code;
			$data['default']['Manual_No'] 		= $getGEJ->Manual_No;
			$data['default']['REF_NUM'] 		= $getGEJ->REF_NUM;
			$data['default']['JournalH_Date'] 	= $getGEJ->JournalH_Date;
			$data['default']['JournalH_Desc']	= $getGEJ->JournalH_Desc;
			$data['default']['JournalH_Desc2']	= $getGEJ->JournalH_Desc2;
			$data['default']['proj_Code'] 		= $getGEJ->proj_Code;
			$data['default']['PRJCODE'] 		= $getGEJ->proj_Code;
			$PRJCODE							= $getGEJ->proj_Code;
			$data['default']['GEJ_STAT'] 		= $getGEJ->GEJ_STAT;
			$data['default']['Pattern_Type'] 	= $getGEJ->Pattern_Type;
			$data['default']['SPLCODE'] 		= $getGEJ->SPLCODE;
			$data['default']['Journal_Amount'] 	= $getGEJ->Journal_Amount;
			
			
			$data['PRJCODE'] 	= $PRJCODE;	
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
										'TTR_REFDOC'	=> $TTR_REFDOC);
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
			
			$JournalH_Date	= date('Y-m-d',strtotime($this->input->post('JournalH_Date')));
			$Patt_Year		= date('Y',strtotime($this->input->post('JournalH_Date')));
			$Patt_Month		= date('m',strtotime($this->input->post('JournalH_Date')));
			$Patt_Date		= date('d',strtotime($this->input->post('JournalH_Date')));
			$GEJ_STAT		= $this->input->post('GEJ_STAT');
			$Manual_No		= $this->input->post('Manual_No');
			$REF_NUM		= $this->input->post('REF_NUM');
			$Pattern_Type	= $this->input->post('Pattern_Type');
			$SPLCODE		= $this->input->post('SPLCODE');
			$Journal_Amount	= $this->input->post('Journal_Amount');
			
			$JournalH_Code 	= $this->input->post('JournalH_Code');
			$proj_Code 		= $this->input->post('proj_Code');
			$PRJCODE 		= $this->input->post('proj_Code');
			
			$SPLDESC		= '';
			$sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier
								WHERE SPLCODE = '$SPLCODE' LIMIT 1";
			$resSPL			= $this->db->query($sqlSPL)->result();
			foreach($resSPL as $rowSPL):
				$SPLDESC	= $rowSPL->SPLDESC;
			endforeach;
			
			$AH_CODE		= $JournalH_Code;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= $this->input->post('JournalH_Desc');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code,
									'Manual_No' 		=> $Manual_No,
									'REF_NUM' 			=> $REF_NUM,
									'JournalType' 		=> 'GEJ',	// Cash Project
									'JournalH_Desc'		=> $this->input->post('JournalH_Desc'),
									'JournalH_Desc2'	=> $this->input->post('JournalH_Desc2'),
									'JournalH_Date'		=> $JournalH_Date,
									'Company_ID'		=> $comp_init,
									'Reference_Type'	=> 'GEJ',
									'Emp_ID'			=> $DefEmp_ID,
									'LastUpdate'		=> $GEJ_CREATED,
									'Wh_id'				=> $proj_Code,
									'proj_Code'			=> $proj_Code,
									'SPLCODE'			=> $SPLCODE,
									'SPLDESC'			=> $SPLDESC,
									'GEJ_STAT'			=> $GEJ_STAT);
			$this->m_gej_entry->updateGEJ($JournalH_Code, $projGEJH);
			
			if($GEJ_STAT != 9)
			{
				$this->m_gej_entry->deleteGEJDetail($JournalH_Code);
			}
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
										'DOC_CODE' 		=> $JournalH_Code,
										'DOC_STAT' 		=> $GEJ_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_journalheader");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// UPDATE JOBDETAIL ITEM
				if($GEJ_STAT == 3)
				{
					$upJH		= "UPDATE tbl_journalheader SET GEJ_STAT = 7 WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
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
			
					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
												'DOC_CODE' 		=> $JournalH_Code,
												'DOC_STAT' 		=> 7,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> $completeName,
												'TBLNAME'		=> "tbl_journalheader");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
			
					if($AH_ISLAST == 1)
					{
						$upJH2	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
						$this->db->query($upJH2);
			
						// START : UPDATE STATUS
							$completeName 	= $this->session->userdata['completeName'];
							$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
													'DOC_CODE' 		=> $JournalH_Code,
													'DOC_STAT' 		=> $GEJ_STAT,
													'PRJCODE' 		=> $PRJCODE,
													'CREATERNM'		=> $completeName,
													'TBLNAME'		=> "tbl_journalheader");
							$this->m_updash->updateStatus($paramStat);
						// END : UPDATE STATUS
					}
				}
				else
				{
					$upJH			= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT' WHERE JournalH_Code = '$JournalH_Code'";
					$this->db->query($upJH);
				}
			
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
			
			// NOL KAN  AMOUNT JOURNAL HEADER
				$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = 0 WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlUpdJH);
			
			if($GEJ_STAT == 9)
			{
				/*$upJH	= "UPDATE tbl_journalheader SET GEJ_STAT = '$GEJ_STAT',
								JournalH_Desc2 = 'Void by $DefEmp_ID on $AH_APPROVED'
							WHERE JournalH_Code = '$JournalH_Code'";
				$this->db->query($upJH);*/
				$JournalH_Code2	= "V$JournalH_Code";
				$projGEJH 		= array('JournalH_Code' 	=> $JournalH_Code2,
										'Manual_No' 		=> $Manual_No,
										'REF_NUM' 			=> $REF_NUM,
										'JournalType' 		=> 'VGEJ',	// Cash Project
										'JournalH_Desc'		=> $this->input->post('JournalH_Desc2'),
										'JournalH_Date'		=> $GEJ_CREATED,
										'Company_ID'		=> $comp_init,
										'Reference_Type'	=> 'VGEJ',
										'Emp_ID'			=> $DefEmp_ID,
										'Created'			=> $GEJ_CREATED,
										'LastUpdate'		=> $GEJ_CREATED,
										'Wh_id'				=> $PRJCODE,
										'proj_Code'			=> $PRJCODE,
										'Pattern_Type'		=> $Pattern_Type,
										'SPLCODE'			=> $SPLCODE,
										'GEJ_STAT'			=> $GEJ_STAT);
				$this->m_gej_entry->add($projGEJH);
				
				$Base_DebetTOT		= 0;
				$Base_KreditTOT		= 0;
				$Base_DebetTOT_Tax	= 0;			
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $JournalH_Code;
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$ITM_CATEG		= $d['ITM_CATEG'];
					$proj_Code		= $d['proj_Code'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$journalAmount	= $d['JournalD_Amount'];
						
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($isTax == 0)
					{
						$Journal_Type		= '';
						//$isTax			= 0;
						if($JournalD_Pos == 'D') // KEBALIKAN
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;
							
							$Base_DebetV	= 0;
							$Base_KreditV	= $d['JournalD_Amount'];
							
							if($GEJ_STAT == 9)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
										
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet, 
																Base_Debet2 = Base_Debet2-$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R								
									if($ITM_CATEG == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'SUBK')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'ADM')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
							}
							$Base_DebetTOT	= $Base_DebetTOT - $Base_Debet;
						}
						elseif($JournalD_Pos == 'K')
						{
							$JournalD_Debet	= 0;
							$Base_Debet		= 0;
							$COA_Debet		= 0;
							$JournalD_Kredit= $d['JournalD_Amount'];
							$Base_Kredit	= $d['JournalD_Amount'];
							$COA_Kredit		= $d['JournalD_Amount'];
							
							$Base_DebetV	= $d['JournalD_Amount'];
							$Base_KreditV	= 0;
							
							if($GEJ_STAT == 9)
							{
								// START : Update to COA - Kredit
									/*$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
														Base_Kredit2 = Base_Kredit2+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
										
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit-$Base_Kredit, 
																Base_Kredit2 = Base_Kredit2+$Base_Kredit
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Kredit
								
								// START : UPDATE L/R								
									if($ITM_CATEG == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'SUBK')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'ADM')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
							}
							$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
						
						
						$Base_Debet_taxV		= 0;
						$Base_Kredit_taxV		= 0;
					}
					else
					{					
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
						
						$Base_DebetV		= 0;
						$Base_KreditV		= 0;
						$Base_Debet_taxV	= 0;
						$Base_Kredit_taxV	= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
									curr_rate,isDirect,ITM_CODE,ITM_CATEG,Ref_Number,Other_Desc,Journal_DK,Journal_Type,isTax)
								VALUE ('$JournalH_Code2', '$Acc_Id', '$proj_Code', 'IDR', $Base_DebetV, $Base_Debet_taxV,
									$Base_KreditV, $Base_Kredit_taxV, $Base_DebetV, $Base_Debet_taxV, $Base_KreditV, 
									$Base_Kredit_taxV, $Base_DebetV, $Base_Debet_taxV, $Base_KreditV, $Base_Kredit_taxV, 1, 1,
									'$ITM_CODE', '$ITM_CATEG', '$Ref_Number', '$Other_Desc','$Journal_DK','$Journal_Type',$isTax)";
					$this->db->query($insSQL);
				}
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "JournalH_Code",
											'DOC_CODE' 		=> $JournalH_Code,
											'DOC_STAT' 		=> $GEJ_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> $completeName,
											'TBLNAME'		=> "tbl_journalheader");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}
			else
			{
				$Base_DebetTOT		= 0;
				$Base_KreditTOT		= 0;
				$Base_DebetTOT_Tax	= 0;			
				foreach($_POST['data'] as $d)
				{
					$JournalH_Code	= $d['JournalH_Code'];
					$Acc_Id			= $d['Acc_Id'];
					$ITM_CODE		= $d['ITM_CODE'];
					$ITM_CATEG		= $d['ITM_CATEG'];
					$proj_Code		= $d['proj_Code'];
					$JournalD_Pos	= $d['JournalD_Pos'];
					$isTax			= $d['isTax'];
					$journalAmount	= $d['JournalD_Amount'];
						
					$PRJCODE		= $d['proj_Code'];
					$ACC_NUM		= $d['Acc_Id'];			// Detail Account
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
					
					if($isTax == 0)
					{
						$Journal_Type		= '';
						//$isTax			= 0;
						if($JournalD_Pos == 'D')
						{
							$JournalD_Debet	= $d['JournalD_Amount'];
							$Base_Debet		= $d['JournalD_Amount'];
							$COA_Debet		= $d['JournalD_Amount'];
							$JournalD_Kredit= 0;
							$Base_Kredit	= 0;
							$COA_Kredit		= 0;
						
							// UPDATE AMOUNT JOURNAL HEADER
								$sqlUpdJH	= "UPDATE tbl_journalheader SET Journal_Amount = Journal_Amount+$Base_Debet
												WHERE JournalH_Code = '$JournalH_Code'";
								$this->db->query($sqlUpdJH);
								
							if($GEJ_STAT == 3 && $AH_ISLAST == 1)
							{
								// START : Update to COA - Debit
									/*$sqlUpdCOA		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
															Base_Debet2 = Base_Debet2+$Base_Debet
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
										
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Debet, 
																Base_Debet2 = Base_Debet2+$Base_Debet
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
								
								// START : UPDATE L/R								
									if($ITM_CATEG == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'SUBK')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'ADM')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
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
									/*$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
														Base_Kredit2 = Base_Kredit2+$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id'";
									$this->db->query($sqlUpdCOA);*/
										
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
																Base_Kredit2 = Base_Kredit2+$Base_Kredit
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$Acc_Id'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Kredit
								
								// START : UPDATE L/R								
									if($ITM_CATEG == 'U')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'T')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'SUBK')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'ADM')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'I')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_CATEG == 'O')
									{
										$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$Base_Kredit
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
														AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								// END : UPDATE L/R
							}
							$Base_DebetTOT	= $Base_DebetTOT + $Base_Debet;
						}
						
						$JournalD_Debet_tax		= 0;
						$Base_Debet_tax			= 0;
						$COA_Debet_tax			= 0;
						$JournalD_Kredit_tax	= 0;
						$Base_Kredit_tax		= 0;
						$COA_Kredit_tax			= 0;
					}
					else
					{					
						$JournalD_Debet		= 0;
						$Base_Debet			= 0;
						$COA_Debet			= 0;
						$JournalD_Kredit	= 0;
						$Base_Kredit		= 0;
						$COA_Kredit			= 0;
					}
					
					$curr_rate				= 1;
					$isDirect				= 1;
					$Ref_Number				= $d['Ref_Number'];
					$Other_Desc				= $d['Other_Desc'];
					$Journal_DK				= $JournalD_Pos;
					$Journal_Type			= $Journal_Type;
					$isTax					= $isTax;
					
					$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, 
									JournalD_Debet_tax,JournalD_Kredit, JournalD_Kredit_tax , Base_Debet, Base_Debet_tax, 
									Base_Kredit, Base_Kredit_tax,COA_Debet,COA_Debet_tax,COA_Kredit,COA_Kredit_tax,
									curr_rate,isDirect,ITM_CODE,ITM_CATEG,Ref_Number,Other_Desc,Journal_DK,Journal_Type,isTax)
								VALUE ('$JournalH_Code', '$Acc_Id', '$proj_Code', 'IDR', $JournalD_Debet, $JournalD_Debet_tax,
									$JournalD_Kredit, $JournalD_Kredit_tax, $Base_Debet, $Base_Debet_tax, $Base_Kredit, 
									$Base_Kredit_tax, $COA_Debet, $COA_Debet_tax, $COA_Kredit, $COA_Kredit_tax, 1, 1, '$ITM_CODE',
									'$ITM_CATEG', '$Ref_Number', '$Other_Desc','$Journal_DK','$Journal_Type',$isTax)";
					$this->db->query($insSQL);
				}
			}
			
			if($GEJ_STAT == 4)
			{
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($JournalH_Code);
				// END : DELETE HISTORY
			}
			
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
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$url			= site_url('c_gl/cgeje0b28t18/gej0b28t18/?id='.$this->url_encryption_helper->encode_url($proj_Code));
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
}