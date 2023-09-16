<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 3 November 2019
 * File Name	= C_s4l3InV.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_s4l3InV extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_saleinv', '', TRUE);
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

 	function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_s4l3InV/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN415';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN415';	
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_s4l3InV/gls4l3Inv/?id=";
			
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

	function gls4l3Inv() // G
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_sales/c_s4l3InV/gls4l3Inv__/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function gls4l3Inv__() // G
	{
		$sqlApp 	= "SELECT * FROM tappname";
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
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN415';
				$data["MenuCode"] 	= 'MN415';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['addURL'] 	= site_url('c_sales/c_s4l3InV/a44_sInv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_s4l3InV/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_saleinv->count_all_sinv($PRJCODE);
			$data["countSINV"] 	= $num_rows;
	 
			$data['vwSINV'] 	= $this->m_saleinv->get_all_sinv($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN415';
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
			
			$this->load->view('v_sales/v_saleinv/v_saleinv', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'DestAdd')$DestAdd = $LangTransl;
    		if($TranslCode == 'NotReceipt')$NotReceipt = $LangTransl;
    		if($TranslCode == 'HalfReceipt')$HalfReceipt = $LangTransl;
    		if($TranslCode == 'FullReceipt')$FullReceipt = $LangTransl;
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
			
			$columns_valid 	= array("SINV_ID",
									"SINV_CODE", 
									"SINV_DATE", 
									"SINV_DUEDATE", 
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
			$num_rows 		= $this->m_saleinv->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_saleinv->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SINV_NUM		= $dataI['SINV_NUM'];
				$SINV_CODE		= $dataI['SINV_CODE'];
				
				$SINV_DATE		= $dataI['SINV_DATE'];
				$SINV_DATEV		= date('d M Y', strtotime($SINV_DATE));
				
				$SINV_DUEDATE	= $dataI['SINV_DUEDATE'];
				$SINV_DUEDATEV	= date('d M Y', strtotime($SINV_DUEDATE));
				
				$CUST_CODE		= $dataI['CUST_CODE'];
				$CUST_DESC		= $dataI['CUST_DESC'];
				$SINV_TOTAM		= $dataI['SINV_TOTAM'];
				$SINV_NOTES		= $dataI['SINV_NOTES'];
				$SINV_PAYSTAT	= $dataI['SINV_PAYSTAT'];
				$invPayStat 	= $SINV_PAYSTAT;
				if($invPayStat == 'NR')
				{
                    $invPayStatDesc = $NotReceipt;
					$invPayStatCol	= 'danger';
				}
                elseif($invPayStat == 'HR')
				{
                    $invPayStatDesc = $HalfReceipt;
					$invPayStatCol	= 'warning';
				}
                elseif($invPayStat == 'FR')
				{
                    $invPayStatDesc = $FullReceipt;
					$invPayStatCol	= 'success';
				}
                else
				{
                    $invPayStatDesc = "-";
					$invPayStatCol	= 'danger';
				}

				// CHECK EXIST RETURN
					// hold

				$SINV_STAT		= $dataI['SINV_STAT'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$SINV_ADD		= $dataI['SINV_ADDRESS'];
				
				$CollCode		= "$PRJCODE~$SINV_NUM";
				$secUpd			= site_url('c_sales/c_s4l3InV/up_s4l3Inv/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPrint		= site_url('c_sales/c_s4l3InV/prnt_40c/?id='.$this->url_encryption_helper->encode_url($SINV_NUM));
				$CollID			= "SINV~$SINV_NUM~$PRJCODE";
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_sinv_header~tbl_sinv_detail~SINV_NUM~$SINV_NUM~PRJCODE~$PRJCODE";
				
				$secVoid 		= base_url().'index.php/__l1y/trashSINV/?id=';
				$voidID 		= "$secVoid~tbl_sinv_header~tbl_sinv_detail~SINV_NUM~$SINV_NUM~PRJCODE~$PRJCODE";
                                    
				if($SINV_STAT == 1 || $SINV_STAT == 4)
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
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($SINV_STAT == 3 && $invPayStat == 'NR') 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   	<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOC(".$noU.")' title='Void'>
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
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
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
								
				
				$output['data'][] 	= array("<div style='white-space:nowrap'>".$dataI['SINV_CODE']."</div>",
										  	$SINV_DATEV,
										  	$SINV_DUEDATEV,
										  	"<strong>$CUST_DESC</strong><br>
										  	<i class='glyphicon glyphicon-map-marker margin-r-5'></i> ".$SINV_ADD,
										  	number_format($SINV_TOTAM,2),
										  	"<span class='label label-".$invPayStatCol."' style='font-size:12px'>".$invPayStatDesc."</span>",
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_sInv() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		
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
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN415';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['form_action']	= site_url('c_sales/c_s4l3InV/add_process');
			$cancelURL				= site_url('c_sales/c_s4l3InV/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_saleinv->count_all_CUST($PRJCODE);
			$data['vwCUST'] 		= $this->m_saleinv->get_all_CUST($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN415';
			$data["MenuCode"] 		= 'MN415';
			$data['viewDocPattern'] = $this->m_saleinv->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN415';
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
	
			$this->load->view('v_sales/v_saleinv/v_saleinv_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function genCode() // G
	{
		$PRJCODEX	= $this->input->post('PRJCODEX');
		$PattCode	= $this->input->post('Pattern_Code');
		$PattLength	= $this->input->post('Pattern_Length');
		$useYear	= $this->input->post('useYear');
		$useMonth	= $this->input->post('useMonth');
		$useDate	= $this->input->post('useDate');
		
		$SNDate		= date('Y-m-d',strtotime($this->input->post('SNDate')));
		$year		= date('Y',strtotime($this->input->post('SNDate')));
		$month 		= (int)date('m',strtotime($this->input->post('SNDate')));
		$date 		= (int)date('d',strtotime($this->input->post('SNDate')));
		
		$this->db->where('Patt_Year', $year);
		$myCount = $this->db->count_all('tbl_sn_header');
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_sn_header
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
		
		// OKroup year, month and date
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
	
	function s3l4llit3m() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_sales/m_saleinv', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$COLLID		= $_GET['id'];
			$CUST_CODE	= $_GET['cu57c0d3'];
			$PRJCODE	= $_GET['pr1h0ec0c0d3'];
			$SO_NUM		= $_GET['s0n_0'];
			
			$data['title'] 			= $appName;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar Pengiriman";
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h1_title"] 	= "Shipping List";
				$data["h2_title"] 	= "Daftar Material";
			}
			
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_sales/c_s4l3InV/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllSN'] 	= $this->m_saleinv->count_all_sn($PRJCODE, $SO_NUM, $CUST_CODE);
			$data['vwAllSN'] 		= $this->m_saleinv->get_all_sn($PRJCODE, $SO_NUM, $CUST_CODE)->result();
					
			$this->load->view('v_sales/v_saleinv/v_saleinv_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SINV_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN415';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$SINV_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SINV_CODE 		= $this->input->post('SINV_CODE');
			$SINV_TYPE 		= $this->input->post('SINV_TYPE');
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SINV_DATE		= date('Y-m-d',strtotime($this->input->post('SINV_DATE')));
			$SINV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('SINV_DUEDATE')));
			$CUST_CODE		= $this->input->post('CUST_CODE');
			$SINV_ADDRESS	= $this->input->post('SINV_ADDRESS');
			$SINV_CURRENCY	= "IDR";
			$SINV_TAXCURR	= 1;
			$SINV_AMOUNT	= $this->input->post('SINV_AMOUNT');
			$SINV_AMOUNT_PPN= $this->input->post('SINV_AMOUNT_PPN');
			$SINV_AMOUNT_PPH= $this->input->post('SINV_AMOUNT_PPH');
			$SINV_PPH		= $this->input->post('SINV_PPH');
			$SINV_PPHVAL	= $this->input->post('SINV_PPHVAL');
			$SINV_NOTES		= addslashes($this->input->post('SINV_NOTES'));
			$SINV_STAT		= $this->input->post('SINV_STAT');
			$COMPANY_ID		= $comp_init;
			$CREATED		= date('Y-m-d H:i:s');
			$CREATER		= $DefEmp_ID;
			$Patt_Year		= date('Y',strtotime($this->input->post('SINV_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$SINV_TOTAM		= $SINV_AMOUNT + $SINV_AMOUNT_PPN - $SINV_PPHVAL;
			
			$TAXCODE1		= "";
			if($SINV_AMOUNT_PPN > 0)
				$TAXCODE1	= "TAX01";
			
			// GET CUSTOMER CATEG
				$CUST_DESC	= '';
				$CUST_CAT	= '';
				$sqlCUST	= "SELECT CUST_DESC, CUST_CAT FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
				$resCUST	= $this->db->query($sqlCUST)->result();
				foreach($resCUST as $rowCUST) :
					$CUST_DESC 	= $rowCUST->CUST_DESC;
					$CUSTCAT 	= $rowCUST->CUST_CAT;
				endforeach;
			
				if($SO_NUM != '')
				{
					$refStep	= 0;
					foreach ($SO_NUM as $SO_REFNO)
					{
						$refStep	= $refStep + 1;
						if($refStep == 1)
						{
							$COLSO_REFNO	= "$SO_REFNO";

							// GET SO_CODE
								$SO_CODE	= '';
								$sqlSOH		= "SELECT SO_CODE FROM tbl_so_header WHERE SO_NUM = '$SO_NUM'";
								$resSOH		= $this->db->query($sqlSOH)->result();
								foreach($resSOH as $rowSOH) :
									$SO_CODE 	= $rowSOH->SO_CODE;
								endforeach;
								$COLSO_CODE		= "$SO_CODE";
						}
						else
						{
							$COLSO_REFNO	= "$COLSO_REFNO~$SO_REFNO";

							// GET SO_CODE
								$SO_CODE	= '';
								$sqlSOH		= "SELECT SO_CODE FROM tbl_so_header WHERE SO_NUM = '$SO_NUM'";
								$resSOH		= $this->db->query($sqlSOH)->result();
								foreach($resSOH as $rowSOH) :
									$SO_CODE 	= $rowSOH->SO_CODE;
								endforeach;
								$COLSO_CODE		= "$COLSO_CODE~$SO_CODE";
						}
					}
				}
				else
				{
					$COLSO_REFNO	= '';
					$COLSO_CODE		= '';
				}
			
			// SAVE TO SINV					
				$insertINV 	= array('SINV_NUM' 			=> $SINV_NUM,
									'SINV_CODE'			=> $SINV_CODE,
									'SINV_TYPE'			=> $SINV_TYPE,
									'PRJCODE'			=> $PRJCODE,
									'SO_NUM'			=> $COLSO_REFNO,
									'SO_CODE'			=> $COLSO_CODE,
									'SINV_DATE'			=> $SINV_DATE,
									'SINV_DUEDATE'		=> $SINV_DUEDATE,
									'CUST_CODE'			=> $CUST_CODE,
									'CUST_DESC'			=> $CUST_DESC,
									'SINV_ADDRESS'		=> $SINV_ADDRESS,
									'SINV_CURRENCY'		=> $SINV_CURRENCY,
									'SINV_TAXCURR'		=> $SINV_TAXCURR,
									'SINV_AMOUNT'		=> $SINV_AMOUNT,
									'SINV_AMOUNT_PPN'	=> $SINV_AMOUNT_PPN,
									'SINV_PPH'			=> $SINV_PPH,
									'SINV_PPHVAL'		=> $SINV_PPHVAL,
									'SINV_TOTAM'		=> $SINV_TOTAM,
									'SINV_NOTES'		=> $SINV_NOTES,
									'SINV_STAT'			=> $SINV_STAT,
									'COMPANY_ID'		=> $COMPANY_ID,
									'CREATED'			=> $CREATED,
									'CREATER'			=> $CREATER,
									'Patt_Year'			=> $Patt_Year,
									'Patt_Number'		=> $Patt_Number);
				$this->m_saleinv->add($insertINV);

				foreach($_POST['data'] as $d)
				{
					$d['SINV_NUM']	= $SINV_NUM;
					$d['SINV_CODE']	= $SINV_CODE;
					$this->db->insert('tbl_sinv_detail',$d);
					
					if($SINV_STAT == 2)
					{
						$SN_NUM	= $d['SN_NUM'];
						$this->m_saleinv->updateSN($SN_NUM, $PRJCODE);
					}
				}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('SINV_STAT');			// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $SINV_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SINV",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sinv_header",	// TABLE NAME
										'KEY_NAME'		=> "SINV_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SINV_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SINV_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SINV",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SINV_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SINV_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SINV_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SINV_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SINV_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SINV_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN415';
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
			
			$url			= site_url('c_sales/c_s4l3InV/gls4l3Inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up_s4l3Inv() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		
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
			$PRJCODE	= $splitCode[0];
			$SINV_NUM	= $splitCode[1];
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';	
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN415';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 			= 'MN415';
			$data["MenuCode"] 	= 'MN415';
			
			$getSINV			= $this->m_saleinv->get_sinv_by_number($SINV_NUM)->row();
			
			$data['default']['SINV_NUM'] 		= $getSINV->SINV_NUM;
			$data['default']['SINV_CODE']		= $getSINV->SINV_CODE;
			$data['default']['SINV_TYPE']		= $getSINV->SINV_TYPE;
			$data['default']['SINV_CATEG']		= $getSINV->SINV_CATEG;
			$data['default']['SO_NUM']			= $getSINV->SO_NUM;
			$data['default']['SO_CODE']			= $getSINV->SO_CODE;
			$data['default']['PRJCODE']			= $getSINV->PRJCODE;
			$data['PRJCODE']					= $getSINV->PRJCODE;
			$PRJCODE							= $getSINV->PRJCODE;
			$data['default']['SINV_DATE']		= $getSINV->SINV_DATE;
			$data['default']['SINV_DUEDATE']	= $getSINV->SINV_DUEDATE;
			$data['default']['CUST_CODE']		= $getSINV->CUST_CODE;
			$data['CUST_CODE']					= $getSINV->CUST_CODE;
			$data['default']['SINV_ADDRESS']	= $getSINV->SINV_ADDRESS;
			$data['default']['SINV_CURRENCY']	= $getSINV->SINV_CURRENCY;
			$data['default']['SINV_TAXCURR']	= $getSINV->SINV_TAXCURR;
			$data['default']['SINV_AMOUNT']		= $getSINV->SINV_AMOUNT;
			$data['default']['SINV_AMOUNT_PPN']	= $getSINV->SINV_AMOUNT_PPN;
			$data['default']['SINV_AMOUNT_PPH']	= $getSINV->SINV_AMOUNT_PPH;
			$data['default']['SINV_AMOUNT_PAID']= $getSINV->SINV_AMOUNT_PAID;
			$data['default']['SINV_AMOUNT_FINAL']	= $getSINV->SINV_AMOUNT_FINAL;
			$data['default']['SINV_LISTTAX']	= $getSINV->SINV_LISTTAX;
			$data['default']['SINV_LISTTAXVAL']	= $getSINV->SINV_LISTTAXVAL;
			$data['default']['SINV_PPH']		= $getSINV->SINV_PPH;
			$data['default']['SINV_PPHVAL']		= $getSINV->SINV_PPHVAL;
			$data['default']['DP_NUM']			= $getSINV->DP_NUM;
			$data['default']['DP_AMOUNT']		= $getSINV->DP_AMOUNT;
			$data['default']['SINV_NOTES']		= $getSINV->SINV_NOTES;
			$data['default']['SINV_NOTES2']		= $getSINV->SINV_NOTES2;
			$data['default']['SINV_STAT']		= $getSINV->SINV_STAT;
			$data['default']['COMPANY_ID']		= $getSINV->COMPANY_ID;
			$data['default']['CREATED']			= $getSINV->CREATED;
			$data['default']['CREATER']			= $getSINV->CREATER;
			$data['default']['Patt_Year']		= $getSINV->Patt_Year;
			$data['default']['Patt_Number']		= $getSINV->Patt_Number;
			
			
			$data['form_action']= site_url('c_sales/c_s4l3InV/update_process');
			$cancelURL			= site_url('c_sales/c_s4l3InV/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSINV->SINV_NUM;
				$MenuCode 		= 'MN415';
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
			
			$data['countCUST']		= $this->m_saleinv->count_all_CUST($PRJCODE);
			$data['vwCUST'] 		= $this->m_saleinv->get_all_CUST($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_sales/v_saleinv/v_saleinv_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			$SINV_NUM 		= $this->input->post('SINV_NUM');
			$SINV_CODE 		= $this->input->post('SINV_CODE');
			$SINV_TYPE 		= $this->input->post('SINV_TYPE');
			// SINV_CATEG
			$SO_NUM 		= $this->input->post('SO_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SINV_DATE		= date('Y-m-d',strtotime($this->input->post('SINV_DATE')));
			$SINV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('SINV_DUEDATE')));
			$CUST_CODE		= $this->input->post('CUST_CODE');
			$SINV_ADDRESS	= $this->input->post('SINV_ADDRESS');
			$SINV_CURRENCY	= "IDR";
			$SINV_TAXCURR	= 1;
			$SINV_AMOUNT	= $this->input->post('SINV_AMOUNT');
			$SINV_AMOUNT_PPN= $this->input->post('SINV_AMOUNT_PPN');
			$SINV_AMOUNT_PPH= $this->input->post('SINV_AMOUNT_PPH');
			$SINV_PPH		= $this->input->post('SINV_PPH');
			$SINV_PPHVAL	= $this->input->post('SINV_PPHVAL');
			$SINV_NOTES		= addslashes($this->input->post('SINV_NOTES'));
			$SINV_STAT		= $this->input->post('SINV_STAT');
			$COMPANY_ID		= $comp_init;
			$CREATED		= date('Y-m-d H:i:s');
			$CREATER		= $DefEmp_ID;
			$Patt_Year		= date('Y',strtotime($this->input->post('SINV_DATE')));

			$REF_NOTES 		= $this->input->post('REF_NOTES');

			// UPDATE HEADER
				$updSINV 	= array(/*'SINV_CODE' 		=> $SINV_CODE,
									'SINV_TYPE'			=> $SINV_TYPE,
									'SINV_CATEG'		=> 'SO',
									'SO_NUM'			=> $SO_NUM,
									'SO_CODE'			=> $SO_CODE,
									'PRJCODE'			=> $PRJCODE,*/
									'SINV_DATE'			=> $SINV_DATE,
									'SINV_DUEDATE'		=> $SINV_DUEDATE,
									/*'CUST_CODE'			=> $CUST_CODE,
									'CUST_DESC'			=> $CUST_DESC,*/
									'SINV_ADDRESS'		=> $SINV_ADDRESS,
									/*'SINV_CURRENCY'		=> $SINV_CURRENCY,
									'SINV_TAXCURR'		=> $SINV_TAXCURR,
									'SINV_AMOUNT'		=> $TOTAL_SN,
									'SINV_AMOUNT_DISC'	=> $GTOTAL_SNDISC,
									'SINV_AMOUNT_PPN'	=> $GTOTAL_SNPPN,
									'SINV_AMOUNT_PPH'	=> $GTOTAL_SNPPH,
									'SINV_AMOUNT_BASE'	=> $GTOTAL_SN,
									'SINV_AMOUNT_PAID'	=> $SINV_AMOUNT_PAID,
									'SINV_TERM'			=> $SINV_TERM,
									'SINV_PPNNUM'		=> $SINV_PPNNUM,
									'SINV_PPHNUM'		=> $SINV_PPHNUM,
									'SINV_LISTTAX'		=> $SINV_LISTTAX,
									'SINV_LISTTAXVAL'	=> $GTOTAL_SNPPN,
									'SINV_PPH'			=> $SINV_PPH,
									'SINV_PPHVAL'		=> $GTOTAL_SNPPH,
									'SINV_TOTAM'		=> $GTOTAL_SN,*/
									'SINV_NOTES'		=> $SINV_NOTES,
									'SINV_STAT'			=> $SINV_STAT,
									/*'SINV_PAYSTAT'		=> $SINV_PAYSTAT,*/
									'COMPANY_ID'		=> $COMPANY_ID
									/*'REF_NOTES'			=> $REF_NOTES,
									'CREATED'			=> $SINV_CREATED,
									'CREATER'			=> $CREATER,
									'Patt_Year'			=> $Patt_Year,
									'Patt_Number'		=> $Patt_Number*/);
				$this->m_saleinv->updateSINV($SINV_NUM, $updSINV);

			// UPDATE JO HEADER
				$updSNH = "UPDATE tbl_sn_header SET SINV_CREATED = 1, SINV_NUM = '$SINV_NUM', SINV_CODE = '$SINV_CODE' WHERE SN_NUM = '$SN_NUM'";
				$this->db->query($updSNH);
			
			// UPDATE JOBDETAIL ITEM
			if($SINV_STAT == 5)
			{
				//$this->m_saleinv->updREJECT($PO_NUM, $PRJCODE);
			}
			elseif($SINV_STAT == 6)	// REJECTED
			{
				// Cek IR with PO Source Code
			}
			elseif($SINV_STAT == 9)	// VOID
			{
				// UPDATE JOURNAL
					$this->m_journal->updVOID($SINV_NUM);

				// UPDATE SHIPMENT
					$this->m_saleinv->updSHIPM($SINV_NUM);
			}
			else
			{
				//$this->m_saleinv->deleteSINDetail($SINV_NUM);
				
				/*foreach($_POST['data'] as $d)
				{
					$d['SINV_NUM']	= $SINV_NUM;
					$d['SINV_CODE']	= $SINV_CODE;
					$this->db->insert('tbl_sinv_detail',$d);
					
					if($SINV_STAT == 2)
					{
						$SN_NUM	= $d['SN_NUM'];
						$this->m_saleinv->updateSN($SN_NUM, $PRJCODE);
					}
				}*/
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('SINV_STAT');			// IF "ADD" CONDITION ALWAYS = PO_STAT
				$parameters 	= array('DOC_CODE' 		=> $SINV_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SINV",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sinv_header",	// TABLE NAME
										'KEY_NAME'		=> "SINV_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SINV_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SINV_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SINV",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SINV_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SINV_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SINV_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SINV_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SINV_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SINV_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SINV_NUM",
										'DOC_CODE' 		=> $SINV_NUM,
										'DOC_STAT' 		=> $SINV_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_sinv_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $PO_NUM;
				$MenuCode 		= 'MN415';
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
			
			$url			= site_url('c_sales/c_s4l3InV/gls4l3Inv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}

 	function InbX() // G
	{
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_s4l3InV/s1N_l5t_x1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function s1N_l5t_x1() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN416';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN416';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_s4l3InV/gls4l3Inv1nB/?id=";
			
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

	function gls4l3Inv1nB() // OK
	{
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		$sqlApp 	= "SELECT * FROM tappname";
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
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN416';
				$data["MenuCode"] 	= 'MN416';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['addURL'] 	= site_url('c_sales/c_s4l3InV/a44_sInv/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_s4l3InV/s1N_l5t_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN416';
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
			
			$this->load->view('v_sales/v_saleinv/v_saleinv_inb', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData_1n2() // GOOD
	{
		$PRJCODE		= $_GET['id'];

		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'DestAdd')$DestAdd = $LangTransl;
    		if($TranslCode == 'NotReceipt')$NotReceipt = $LangTransl;
    		if($TranslCode == 'HalfReceipt')$HalfReceipt = $LangTransl;
    		if($TranslCode == 'FullReceipt')$FullReceipt = $LangTransl;
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
			
			$columns_valid 	= array("SINV_ID",
									"SINV_CODE", 
									"SINV_DATE", 
									"SINV_DUEDATE", 
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
			$num_rows 		= $this->m_saleinv->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_saleinv->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SINV_NUM		= $dataI['SINV_NUM'];
				$SINV_CODE		= $dataI['SINV_CODE'];
				
				$SINV_DATE		= $dataI['SINV_DATE'];
				$SINV_DATEV		= date('d M Y', strtotime($SINV_DATE));
				
				$SINV_DUEDATE	= $dataI['SINV_DUEDATE'];
				$SINV_DUEDATEV	= date('d M Y', strtotime($SINV_DUEDATE));
				
				$CUST_CODE		= $dataI['CUST_CODE'];
				$CUST_DESC		= $dataI['CUST_DESC'];
				$SINV_TOTAM		= $dataI['SINV_TOTAM'];
				$SINV_NOTES		= $dataI['SINV_NOTES'];
				$SINV_PAYSTAT	= $dataI['SINV_PAYSTAT'];
				$invPayStat 	= $SINV_PAYSTAT;
				if($invPayStat == 'NR')
				{
                    $invPayStatDesc = $NotReceipt;
					$invPayStatCol	= 'danger';
				}
                elseif($invPayStat == 'HR')
				{
                    $invPayStatDesc = $HalfReceipt;
					$invPayStatCol	= 'warning';
				}
                elseif($invPayStat == 'FR')
				{
                    $invPayStatDesc = $FullReceipt;
					$invPayStatCol	= 'success';
				}
                else
				{
                    $invPayStatDesc = "-";
					$invPayStatCol	= 'danger';
				}

				$SINV_STAT		= $dataI['SINV_STAT'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);
				$SINV_ADD		= $dataI['SINV_ADDRESS'];
				
				$CollCode		= "$PRJCODE~$SINV_NUM";
				$secUpd			= site_url('c_sales/c_s4l3InV/up180djinb/?id='.$this->url_encryption_helper->encode_url($CollCode));
				$secPrint		= site_url('c_sales/c_s4l3InV/prnt_40c/?id='.$this->url_encryption_helper->encode_url($SINV_NUM));
				$CollID			= "SINV~$SINV_NUM~$PRJCODE";
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_sinv_header~tbl_sinv_detail~SINV_NUM~$SINV_NUM~PRJCODE~$PRJCODE";
                                    
				if($SINV_STAT == 1) 
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
								
				
				$output['data'][] = array("<div style='white-space:nowrap'>".$dataI['SINV_CODE']."</div>",
										  $SINV_DATEV,
										  $SINV_DUEDATEV,
										  "<strong>$CUST_DESC</strong><br>
										  	<i class='glyphicon glyphicon-map-marker margin-r-5'></i> ".$SINV_ADD,
										  number_format($SINV_TOTAM,2),
										  "<span class='label label-".$invPayStatCol."' style='font-size:12px'>".$invPayStatDesc."</span>",
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
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		
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
			$PRJCODE	= $splitCode[0];
			$SINV_NUM	= $splitCode[1];
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'edit';	
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN416';
				$data["MenuApp"] 	= 'MN416';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 			= 'MN416';
			$data["MenuCode"] 	= 'MN416';
			
			$getSINV			= $this->m_saleinv->get_sinv_by_number($SINV_NUM)->row();
			
			$data['default']['SINV_NUM'] 		= $getSINV->SINV_NUM;
			$data['default']['SINV_CODE']		= $getSINV->SINV_CODE;
			$data['default']['SINV_TYPE']		= $getSINV->SINV_TYPE;
			$data['default']['SINV_CATEG']		= $getSINV->SINV_CATEG;
			$data['default']['SO_NUM']			= $getSINV->SO_NUM;
			$data['default']['SO_CODE']			= $getSINV->SO_CODE;
			$data['default']['PRJCODE']			= $getSINV->PRJCODE;
			$data['PRJCODE']					= $getSINV->PRJCODE;
			$PRJCODE							= $getSINV->PRJCODE;
			$data['default']['SINV_DATE']		= $getSINV->SINV_DATE;
			$data['default']['SINV_DUEDATE']	= $getSINV->SINV_DUEDATE;
			$data['default']['CUST_CODE']		= $getSINV->CUST_CODE;
			$data['CUST_CODE']					= $getSINV->CUST_CODE;
			$data['default']['SINV_ADDRESS']	= $getSINV->SINV_ADDRESS;
			$data['default']['SINV_CURRENCY']	= $getSINV->SINV_CURRENCY;
			$data['default']['SINV_TAXCURR']	= $getSINV->SINV_TAXCURR;
			$data['default']['SINV_AMOUNT']		= $getSINV->SINV_AMOUNT;
			$data['default']['SINV_AMOUNT_PPN']	= $getSINV->SINV_AMOUNT_PPN;
			$data['default']['SINV_AMOUNT_PPH']	= $getSINV->SINV_AMOUNT_PPH;
			$data['default']['SINV_AMOUNT_PAID']= $getSINV->SINV_AMOUNT_PAID;
			$data['default']['SINV_AMOUNT_FINAL']	= $getSINV->SINV_AMOUNT_FINAL;
			$data['default']['SINV_LISTTAX']	= $getSINV->SINV_LISTTAX;
			$data['default']['SINV_LISTTAXVAL']	= $getSINV->SINV_LISTTAXVAL;
			$data['default']['SINV_PPH']		= $getSINV->SINV_PPH;
			$data['default']['SINV_PPHVAL']		= $getSINV->SINV_PPHVAL;
			$data['default']['DP_NUM']			= $getSINV->DP_NUM;
			$data['default']['DP_AMOUNT']		= $getSINV->DP_AMOUNT;
			$data['default']['SINV_NOTES']		= $getSINV->SINV_NOTES;
			$data['default']['SINV_NOTES2']		= $getSINV->SINV_NOTES2;
			$data['default']['SINV_STAT']		= $getSINV->SINV_STAT;
			$data['default']['COMPANY_ID']		= $getSINV->COMPANY_ID;
			$data['default']['CREATED']			= $getSINV->CREATED;
			$data['default']['CREATER']			= $getSINV->CREATER;
			$data['default']['Patt_Year']		= $getSINV->Patt_Year;
			$data['default']['Patt_Number']		= $getSINV->Patt_Number;

			$data['form_action']= site_url('c_sales/c_s4l3InV/update_process_inb');
			$cancelURL			= site_url('c_sales/c_s4l3InV/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSINV->SINV_NUM;
				$MenuCode 		= 'MN416';
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
			
			$data['countCUST']		= $this->m_saleinv->count_all_CUST($PRJCODE);
			$data['vwCUST'] 		= $this->m_saleinv->get_all_CUST($PRJCODE)->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_sales/v_saleinv/v_saleinv_inb_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // OK
	{
		$this->load->model('m_sales/m_saleinv', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SINV_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$comp_init 		= $this->session->userdata('comp_init');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SINV_NUM 		= $this->input->post('SINV_NUM');
			$SINV_CODE 		= $this->input->post('SINV_CODE');
			$SINV_TYPE 		= $this->input->post('SINV_TYPE');
			$SO_NUM 		= $this->input->post('SO_NUM');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SINV_DATE		= date('Y-m-d',strtotime($this->input->post('SINV_DATE')));
			$SINV_DUEDATE	= date('Y-m-d',strtotime($this->input->post('SINV_DUEDATE')));
			$CUST_CODE		= $this->input->post('CUST_CODE');
			$SINV_ADDRESS	= $this->input->post('SINV_ADDRESS');
			$SINV_CURRENCY	= "IDR";
			$SINV_TAXCURR	= 1;
			$SINV_AMOUNT	= $this->input->post('SINV_AMOUNT');
			$SINV_AMOUNT_PPN= $this->input->post('SINV_AMOUNT_PPN');
			$SINV_PPH		= $this->input->post('SINV_PPH');
			$SINV_PPHVAL	= $this->input->post('SINV_PPHVAL');
			$SINV_NOTES		= addslashes($this->input->post('SINV_NOTES'));
			$SINV_NOTES2	= addslashes($this->input->post('SINV_NOTES2'));
			$SINV_STAT		= $this->input->post('SINV_STAT');
			$COMPANY_ID		= $comp_init;
			$APPROVED		= date('Y-m-d H:i:s');
			$APPROVER		= $DefEmp_ID;
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$SINV_TOTAM		= $SINV_AMOUNT + $SINV_AMOUNT_PPN - $SINV_PPHVAL;
			
			$TAXCODE1		= "";
			if($SINV_AMOUNT_PPN > 0)
				$TAXCODE1	= "TAX01";
			
			// GET CUSTOMER CATEG
				$CUST_CAT	= '';
				$CUST_DESC	= '';
				$sqlCUST	= "SELECT CUST_CAT, CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
				$resCUST	= $this->db->query($sqlCUST)->result();
				foreach($resCUST as $rowCUST) :
					$CUSTCAT 	= $rowCUST->CUST_CAT;
					$CUST_DESC = $rowCUST->CUST_DESC;
				endforeach;
			
				if($SO_NUM != '')
				{
					$refStep	= 0;
					foreach ($SO_NUM as $SO_REFNO)
					{
						$refStep	= $refStep + 1;
						if($refStep == 1)
						{
							$COLSO_REFNO	= "$SO_REFNO";
						}
						else
						{
							$COLSO_REFNO	= "$COLSO_REFNO~$SO_REFNO";
						}
					}
				}
				else
				{
					$COLSO_REFNO	= '';
				}
			
			if($SINV_STAT == 3)
			{
				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$AH_CODE		= $SINV_NUM;
					$AH_APPLEV		= $this->input->post('APP_LEVEL');
					$AH_APPROVER	= $DefEmp_ID;
					$AH_APPROVED	= $APPROVED;
					$AH_NOTES		= addslashes($this->input->post('SINV_NOTES2'));
				
					$updSINV 		= array('SINV_STAT'	=> 7); // Default step approval
					$this->m_saleinv->updateSINV($SINV_NUM, $updSINV);
					
					$insAppHist 	= array('AH_CODE'		=> $AH_CODE,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											'AH_NOTES'		=> $AH_NOTES,
											'PRJCODE' 		=> $PRJCODE,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				if($AH_ISLAST == 1)
				{
					$updSINV 		= array('SINV_STAT'		=> $SINV_STAT,
											'SINV_NOTES2'	=> addslashes($this->input->post('SINV_NOTES2'))); // Default step approval
					$this->m_saleinv->updateSINV($SINV_NUM, $updSINV);
					
					// UPDATE SN DETAIL
						$TOTINV_AMN	= 0;
						$TOTINV_POT	= 0;
						$TOTINV_PPN	= 0;
						$TOTINV_PPH	= 0;
						foreach($_POST['data'] as $d)
						{
							$SN_NUM		= $d['SN_NUM'];
							$SN_TOTAL	= $d['ITM_AMOUNT'];
							$SN_DISC	= $d['ITM_DISC'];
							$TAXPRICE1	= $d['TAX_AMOUNT_PPn1'];
							$TAXPRICE2	= $d['TAX_AMOUNT_PPh1'];
							$TOTINV_AMN	= $TOTINV_AMN + $SN_TOTAL;
							$TOTINV_POT	= $TOTINV_POT + $SN_DISC;
							$TOTINV_PPN	= $TOTINV_PPN + $TAXPRICE1;
							$TOTINV_PPH	= $TOTINV_PPH + $TAXPRICE2;
							$TOTAMN 	= $TOTINV_AMN - $TOTINV_POT + $TAXPRICE1 - $TAXPRICE2;
							$this->m_saleinv->updateSN2($SN_NUM, $PRJCODE, $SINV_CODE);
						}
					
					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $SINV_NUM;
						$JournalType	= 'SINV';
						$JournalH_Date	= $SINV_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $SO_NUM;
						$LastUpdate		= $APPROVED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= '';
						$RefType		= 'SN';
						$PRJCODE		= $PRJCODE;
						$Journal_Amount	= $TOTAMN;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $comp_init,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE,
											'SPLCODE'			=> $CUST_CODE,
											'SPLDESC'			=> $CUST_DESC,
											'Journal_Amount'	=> $Journal_Amount);
						$this->m_journal->createJournalH_NEW($JournalH_Code, $parameters);
					// END : JOURNAL HEADER
					
					// START : JOURNAL DETAIL : 1102010 PIUTANG USAHA JASA/DAGANG (D) dan 110205020 PIUTANG LAIN-LAIN (K)
						$JournalH_Code	= $SINV_NUM;
						$JournalType	= 'SINV';
						$JournalH_Date	= $SINV_DATE;
						$Company_ID		= $comp_init;
						$Currency_ID	= 'IDR';
						$DOCSource		= $SO_NUM;
						$LastUpdate		= $APPROVED;
						$WH_CODE		= $PRJCODE;
						$Refer_Number	= '';
						$RefType		= 'SINV';
						$JSource		= 'SINV';
						$PRJCODE		= $PRJCODE;
						
						$ITM_CODE 		= $SINV_NUM;
						$ACC_ID 		= '';
						$ITM_UNIT 		= '';
						$ITM_QTY 		= 1;
						//$ITM_PRICE	S= $INV_AMOUNT;
						$ITM_PRICE		= $TOTINV_AMN;		// NILAI ASLI SEBELUM DIPOTONG DISC N PPH N DITAMBAH PPN
						$ITM_DISC 		= 0;
						$TAXCODE1 		= '';
						$TAXPRICE1		= 0;
						//$TAXCODE1 	= "";
						//$TAXPRICE1	= 0;
						$ITM_PRICE1		= $TOTINV_POT;
						$ITM_PRICE2		= $TOTINV_PPN;
						$ITM_PRICE3		= $TOTINV_PPH;
						
						$TRANS_CATEG 	= "SINV~$CUST_CODE~$CUSTCAT";
						
						// UNTUK KEPERLUAN FINANCIAL TRACK
						// NILAI HUTANG SEBENARNYA = NILAI INVOICE (SBL DIPOTONG) - POTONGAN + PPN;
						// SAMA DENGAN FM_TOTVAL = (INV_VAL + PPN - POT - RET - PPH) + RET + PPH;
							$FM_TOTVAL	= $TOTINV_AMN;
							
						// START : TRACK FINANCIAL TRACK - OK ON 10 JAN 19
							$this->load->model('m_updash/m_updash', '', TRUE);
							$paramFT = array('DOC_NUM' 		=> $SINV_NUM,
											'DOC_DATE' 		=> $SINV_DATE,
											'DOC_EDATE' 	=> $SINV_DUEDATE,
											'PRJCODE' 		=> $PRJCODE,
											'FIELD_NAME1' 	=> 'FT_AR',
											'FIELD_NAME2' 	=> 'FM_AR',
											'TOT_AMOUNT'	=> $FM_TOTVAL);
							$this->m_updash->finTrack($paramFT);
						// END : TRACK FINANCIAL TRACK

						foreach($_POST['data'] as $d)
						{
							$SN_NUM		= $d['SN_NUM'];
							$SN_TOTAL	= $d['ITM_AMOUNT'];
							$SN_DISC	= $d['ITM_DISC'];
							$TAXCODE1	= $d['TAXCODE1'];
							$TAXPRICE1	= $d['TAX_AMOUNT_PPn1'];
							$TAXPRICE2	= $d['TAX_AMOUNT_PPh1'];

							$parameters = array('JournalH_Code' 	=> $JournalH_Code,
												'JournalType'		=> $JournalType,
												'JournalH_Date' 	=> $JournalH_Date,
												'Company_ID' 		=> $comp_init,
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
												'TRANS_CATEG' 		=> $TRANS_CATEG,			// SINV = SALES INVOICE
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_PRICE1' 		=> $ITM_PRICE1,
												'ITM_PRICE2' 		=> $ITM_PRICE2,
												'ITM_PRICE3' 		=> $ITM_PRICE3,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'SINV_NOTES'		=> $SINV_NOTES);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						}
					// END : JOURNAL DETAIL
						
					// START : UPDATE TO TRANS-COUNT
						$this->load->model('m_updash/m_updash', '', TRUE);
						
						$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = PO_STAT
						$parameters 	= array('DOC_CODE' 		=> $SINV_NUM,		// TRANSACTION CODE
												'PRJCODE' 		=> $PRJCODE,		// PROJECT
												'TR_TYPE'		=> "SINV",			// TRANSACTION TYPE
												'TBL_NAME' 		=> "tbl_sinv_header",	// TABLE NAME
												'KEY_NAME'		=> "SINV_NUM",		// KEY OF THE TABLE
												'STAT_NAME' 	=> "SINV_STAT",		// NAMA FIELD STATUS
												'STATDOC' 		=> $SINV_STAT,		// TRANSACTION STATUS
												'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
												'FIELD_NM_ALL'	=> "TOT_SINV",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
												'FIELD_NM_N'	=> "TOT_SINV_N",	// TOTAL NEW TRANSACTION FOR tbl_dash_data
												'FIELD_NM_C'	=> "TOT_SINV_C",	// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
												'FIELD_NM_A'	=> "TOT_SINV_A",	// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_R'	=> "TOT_SINV_R",	// TOTAL REVISE TRANSACTION FOR tbl_dash_data
												'FIELD_NM_RJ'	=> "TOT_SINV_RJ",	// TOTAL REJECT TRANSACTION FOR tbl_dash_data
												'FIELD_NM_CL'	=> "TOT_SINV_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
						$this->m_updash->updateDashData($parameters);
					// END : UPDATE TO TRANS-COUNT
					
					// START : UPDATE TO T-TRACK
						date_default_timezone_set("Asia/Jakarta");
						$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
						$TTR_PRJCODE	= $PRJCODE;
						$TTR_REFDOC		= $SINV_NUM;
						$MenuCode 		= 'MN416';
						$TTR_CATEG		= 'APP-UP';
						
						$this->load->model('m_updash/m_updash', '', TRUE);				
						$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
												'TTR_DATE' 		=> date('Y-m-d H:i:s'),
												'TTR_MNCODE'	=> $MenuCode,
												'TTR_CATEG'		=> $TTR_CATEG,
												'TTR_PRJCODE'	=> $TTR_PRJCODE,
												'PRJCODE' 		=> $PRJCODE,
												'TTR_REFDOC'	=> $TTR_REFDOC);
						$this->m_updash->updateTrack($paramTrack);
					// END : UPDATE TO T-TRACK
				}
			}
			
			// UPDATE JOBDETAIL ITEM
			if($SINV_STAT == 4 || $SINV_STAT == 5 || $SINV_STAT == 6)
			{
				$updSINV 		= array('SINV_STAT'		=> $SINV_STAT,
										'SINV_NOTES2'	=> addslashes($this->input->post('SINV_NOTES2'))); // Default step approval
				$this->m_saleinv->updateSINV($SINV_NUM, $updSINV);
			}

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SINV_NUM",
										'DOC_CODE' 		=> $SINV_NUM,
										'DOC_STAT' 		=> $SINV_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_sinv_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			$url		= site_url('c_sales/c_s4l3InV/gls4l3Inv1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt_40c()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$username 				= $this->session->userdata('username');
			
			$data['title'] 			= $appName;
			$data['username'] 		= $username;
			$data['appName'] 		= $appName;
			$data['h2_title'] 		= 'Page Not Found';

			$compName 				= "";
			$sqlEMPNM				= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
			$resultEMPNM 			= $this->db->query($sqlEMPNM)->result();
			foreach($resultEMPNM as $rowEMPNM) :
				$First_Name			= $rowEMPNM->First_Name;
				$Last_Name			= $rowEMPNM->Last_Name;
			endforeach;
			$compName 				= $First_Name." ".$Last_Name;
			$data['compName'] 		= $compName;
			
			$this->load->view('blank_', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
}