<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= C_sh1pn0t35.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_sh1pn0t35 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_sales/m_shipment', '', TRUE);
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
		$this->load->model('m_sales/m_shipment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_sh1pn0t35/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN041';
				$data["MenuApp"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN041';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_sh1pn0t35/gl5h1pm3nt/?id=";
			
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

	function gl5h1pm3nt() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);
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
			
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN041';
				$data["MenuCode"] 	= 'MN041';
				$data["MenuApp"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['addURL'] 	= site_url('c_sales/c_sh1pn0t35/a44_sN0t35/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_sh1pn0t35/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_shipment->count_all_sn($PRJCODE);
			$data["countSO"] 	= $num_rows;
	 
			$data['vwSO'] = $this->m_shipment->get_all_sn($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN041';
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
			
			$this->load->view('v_sales/v_shipment/v_shipment', $data);
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
			
			$columns_valid 	= array("ID",
									"SN_CODE",
									"SN_DATE",
									"CUST_DESC",
									"SN_NOTE",
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
			$num_rows 		= $this->m_shipment->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_shipment->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SN_NUM 		= $dataI['SN_NUM'];
                $SN_CODE 		= $dataI['SN_CODE'];
                $SN_TYPE 		= $dataI['SN_TYPE'];
                $SN_DATE 		= $dataI['SN_DATE'];
                $SN_DATEV		= date('d M Y', strtotime($SN_DATE));
                $PRJCODE 		= $dataI['PRJCODE'];
                $SO_CODE 		= $dataI['SO_CODE'];
                $CUST_CODE 		= $dataI['CUST_CODE'];
                $CUST_DESC 		= $dataI['CUST_DESC'];
                if($CUST_DESC == '')
                {
                	$sqlCUST 	= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
					$resCUST 	= $this->db->query($sqlCUST)->result();
					foreach($resCUST as $rowCUST) :															
						$CUST_DESC	= $rowCUST->CUST_DESC;
					endforeach;
				}

				$CUST_ADDRESS	= $dataI['CUST_ADDRESS'];
                $SN_TOTCOST		= $dataI['SN_TOTCOST'];
                $SN_STAT 		= $dataI['SN_STAT'];
                $SN_NOTES		= $dataI['SN_NOTES'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);

				$CollID1		= "$PRJCODE~$SN_NUM~$SN_CODE";
				$secUpd			= site_url('c_sales/c_sh1pn0t35/u44_sN0t35/?id='.$this->url_encryption_helper->encode_url($CollID1));
				$secCINV		= site_url('c_sales/c_sh1pn0t35/add_processINV/?id='.$this->url_encryption_helper->encode_url($CollID1));
				$secPrint		= site_url('c_sales/c_sh1pn0t35/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($SN_NUM));
				$secPrintInv	= site_url('c_sales/c_sh1pn0t35/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($SN_NUM));
				$secDel 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDel~tbl_sn_header~tbl_sn_detail~SN_NUM~$SN_NUM~PRJCODE~$PRJCODE";
				$secVoid 		= base_url().'index.php/__l1y/trashSN/?id=';
				$voidID 		= "$secVoid~tbl_sn_header~tbl_sn_detail~SN_NUM~$SN_NUM~PRJCODE~$PRJCODE";

				$sqlInv			= "tbl_sinv_detail A INNER JOIN tbl_sinv_header B ON A.SINV_NUM = B.SINV_NUM WHERE A.SN_NUM = '$SN_NUM' AND A.PRJCODE = '$PRJCODE'
									AND B.SINV_STAT != '9'";
				$resInv 		= $this->db->count_all($sqlInv);

				$colSINV		= "danger";
				if($resInv > 0)
				{
					$colSINV	= "success";
					$isDis		= "";
					$icN 		= "fa fa-eye";
					$shwTitle	= "Tampilkan No. Faktur";
				}
				else
				{
					$icN 		= "fa fa-clone";
					$shwTitle	= "Buat Faktur";

					if($SN_STAT == 3)
					{
						$isDis		= "";
					}
					else
					{
						$isDis		= "disabled='disabled'";
					}
				}

				if($SN_STAT == 1 || $SN_STAT == 4) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='avascript:void(null);' class='btn btn-".$colSINV." btn-xs' ".$isDis." title='".$shwTitle."'>
										<i class='".$icN."'></i>
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
				elseif($SN_STAT == 3) 
				{
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlPrintINV".$noU."' id='urlPrintINV".$noU."' value='".$secPrintInv."'>
									<input type='hidden' name='urlSINV".$noU."' id='urlSINV".$noU."' value='".$secCINV."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlVoid".$noU."' id='urlVoid".$noU."' value='".$voidID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='#' class='btn btn-".$colSINV." btn-xs' ".$isDis."  title='".$shwTitle."' onClick='createINV(".$noU.")'>
										<i class='".$icN."'></i>
									</a>
								   	<a href='avascript:void(null);' class='btn btn-primary btn-xs' title='Print' onClick='printD(".$noU.")'>
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
					$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
									<input type='hidden' name='urlPrintINV".$noU."' id='urlPrintINV".$noU."' value='".$secPrintInv."'>
									<input type='hidden' name='urlSINV".$noU."' id='urlSINV".$noU."' value='".$secCINV."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='#' class='btn btn-".$colSINV." btn-xs' ".$isDis."  title='".$shwTitle."' onClick='createINV(".$noU.")'>
										<i class='".$icN."'></i>
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
				
				$output['data'][] = array("$noU.",
										  $dataI['SN_CODE'],
										  $SN_DATEV,
										  $SO_CODE,
										  $CUST_DESC,
										  $CUST_ADDRESS,
										  "<span style='white-space:nowrap'>".$CREATERNM."</span>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_sN0t35() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_shipment', '', TRUE);
		
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
				$mnCode				= 'MN041';
				$data["MenuApp"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
						
			$data['form_action']	= site_url('c_sales/c_sh1pn0t35/add_process');
			$cancelURL				= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_shipment->count_all_CUST();
			$data['vwCUST'] 		= $this->m_shipment->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN041';
			$data["MenuCode"] 		= 'MN041';
			$data['viewDocPattern'] = $this->m_shipment->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN041';
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
	
			$this->load->view('v_sales/v_shipment/v_shipment_form', $data);
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
	
	function s3l4llS0() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$SN_TYPE			= $_GET['SNTYPE'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');

			if($SN_TYPE == 4)
			{
				$data['countCUST']	= $this->m_shipment->count_all_CUSTSNDIR($PRJCODE);
				$data['vwCUST'] 	= $this->m_shipment->get_all_CUSTSNDIR($PRJCODE)->result();
			}
			else
			{
				$data['countCUST']	= $this->m_shipment->count_all_CUSTSN($PRJCODE);
				$data['vwCUST'] 	= $this->m_shipment->get_all_CUSTSN($PRJCODE)->result();
			}
			$data['title'] 		= $appName;
			$data['PRJCODE']	= $PRJCODE;
					
			$this->load->view('v_sales/v_shipment/v_shipment_selso', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llit3m() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_sales/m_shipment', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
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
			$data['form_action']	= site_url('c_sales/c_sh1pn0t35/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_sales/c_sh1pn0t35/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['countAllItem'] 	= $this->m_shipment->count_all_item($PRJCODE);
			$data['vwAllItem'] 		= $this->m_shipment->get_all_item($PRJCODE)->result();
					
			$this->load->view('v_sales/v_shipment/v_shipment_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);

		$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SN_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN041';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$SN_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SN_CODE 		= $this->input->post('SN_CODE');
			$SN_TYPE 		= $this->input->post('SN_TYPE');
			$SN_DATE		= date('Y-m-d',strtotime($this->input->post('SN_DATE')));
			
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_NUM1 		= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			$SO_DATE		= $this->input->post('SO_DATE');

			if($SN_TYPE == 4)
			{
				$JO_NUM		= $SO_NUM;
				$JO_CODE	= $SO_CODE;
			}
			else
			{
				if($SO_NUM == '')
				{
					foreach($_POST['dataQRC'] as $dQRC)
					{
						$JO_NUM	= $dQRC['JO_NUM'];
					}
					if($JO_NUM != '')
					{
						$sqlSO		= "SELECT A.CUST_CODE, A.CUST_DESC, A.JO_CODE, A.JO_DATE, A.SO_NUM, A.SO_CODE, B.CUST_ADD1, C.SO_DATE
										FROM tbl_jo_header A
											LEFT JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
											LEFT JOIN tbl_so_header C ON A.SO_NUM = C.SO_NUM
										WHERE JO_NUM = '$JO_NUM' LIMIT 1";
						$resSO		= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO) :
							$CUST_CODE		= $rowSO->CUST_CODE;
							$CUST_DESC		= $rowSO->CUST_DESC;
							$CUST_ADDRESS	= $rowSO->CUST_ADD1;
							$JO_CODE		= $rowSO->JO_CODE;
							$SO_NUM			= $rowSO->SO_NUM;
							$SO_CODE		= $rowSO->SO_CODE;
							$SO_DATE		= $rowSO->SO_DATE;
						endforeach;
					}
				}
				else
				{
					$sqlSO		= "SELECT A.JO_NUM, A.CUST_CODE, A.CUST_DESC, A.JO_CODE, A.JO_DATE, A.SO_NUM, A.SO_CODE, B.CUST_ADD1, C.SO_DATE
									FROM tbl_jo_header A
										LEFT JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
										LEFT JOIN tbl_so_header C ON A.SO_NUM = C.SO_NUM
									WHERE A.SO_NUM = '$SO_NUM' LIMIT 1";
					$resSO		= $this->db->query($sqlSO)->result();
					foreach($resSO as $rowSO) :
						$CUST_CODE		= $rowSO->CUST_CODE;
						$CUST_DESC		= $rowSO->CUST_DESC;
						$CUST_ADDRESS	= $rowSO->CUST_ADD1;
						$JO_NUM			= $rowSO->JO_NUM;
						$JO_CODE		= $rowSO->JO_CODE;
						$SO_NUM			= $rowSO->SO_NUM;
						$SO_CODE		= $rowSO->SO_CODE;
						$SO_DATE		= $rowSO->SO_DATE;
					endforeach;
				}
			}
			
			$SN_DRIVER 		= $this->input->post('SN_DRIVER');
			$VEH_CODE 		= $this->input->post('VEH_CODE');
			$VEH_NOPOL 		= $this->input->post('VEH_NOPOL');
			$SN_NOTES 		= addslashes($this->input->post('SN_NOTES'));
			$SN_TOTVOLM 	= $this->input->post('SN_TOTVOLM');
			$SN_CREATER		= $DefEmp_ID;
			$SN_CREATED		= date('Y-m-d');
			$SN_STAT		= $this->input->post('SN_STAT');
			$Patt_Year		= date('Y',strtotime($this->input->post('SN_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('SN_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('SN_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');

			$CUST_DESC		= '';
			$sqlCUST		= "SELECT CUST_DESC FROM tbl_customer where CUST_CODE = '$CUST_CODE' LIMIT 1";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST) :
				$CUST_DESC = $rowCUST->CUST_DESC;
			endforeach;
			if($CUST_DESC == '')
				$CUST_DESC	= "-";

			$AddSN			= array('SN_NUM' 		=> $SN_NUM,
									'SN_CODE' 		=> $SN_CODE,
									'SN_TYPE' 		=> $SN_TYPE,
									'SN_DATE'		=> $SN_DATE,
									'PRJCODE'		=> $PRJCODE,
									'CUST_CODE' 	=> $CUST_CODE,
									'CUST_DESC' 	=> $CUST_DESC,
									'CUST_ADDRESS'	=> $CUST_ADDRESS,
									'SO_NUM' 		=> $SO_NUM,
									'SO_CODE'		=> $SO_CODE,
									'SO_DATE'		=> $SO_DATE,
									'JO_NUM' 		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'SN_DRIVER' 	=> $SN_DRIVER, 
									'VEH_CODE' 		=> $VEH_CODE,									
									'VEH_NOPOL' 	=> $VEH_NOPOL, 
									'SN_NOTES' 		=> $SN_NOTES,
									'SN_TOTVOLM'	=> $SN_TOTVOLM,
									'SN_CREATER'	=> $SN_CREATER,
									'SN_CREATED' 	=> $SN_CREATED,
									'SN_STAT'		=> $SN_STAT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_shipment->add($AddSN);

			$SN_TOTVOLM	= 0;
			$SN_TOTCOST	= 0;
			$SN_TOTPPN	= 0;
			$SN_TOTDISC	= 0;

			if($SN_TYPE == 4)	// LAINNYA
			{
				foreach($_POST['data'] as $d)
				{
					$ITMCODE1		= $d['ITM_CODE'];
					$d['SN_NUM']	= $SN_NUM;
					$d['SN_CODE']	= $SN_CODE;
					$d['SN_DATE']	= $SN_DATE;
					$d['JO_NUM']	= $JO_NUM;
					$d['JO_CODE']	= $JO_CODE;
					$d['PRJCODE']	= $PRJCODE;
					$d['CUST_CODE']	= $CUST_CODE;

					$TOT_SNVOLM		= $d['SN_VOLM'];
					$SNPRICE		= $d['SN_PRICE'];

					$SO_VOLM 		= 0;
					$SO_PRICE 		= 0;
					$SO_DISC 		= 0;
					$TAXCODE1 		= "";
					$TAXPRICE1 		= 0;
					$sqlSOD 		= "SELECT A.SO_VOLM, A.SO_PRICE, A.SO_DISP, A.SO_DISC, A.SO_COST, A.TAXCODE1, A.TAXPRICE1
	                                    FROM tbl_so_detail A
	                                    WHERE A.SO_NUM = '$SO_NUM' AND A.ITM_CODE = '$ITMCODE1'";
                    $resSOD 		= $this->db->query($sqlSOD)->result();
                    foreach($resSOD as $row) :
                        $SO_VOLM 		= $row->SO_VOLM;
                        $SO_PRICE 		= $row->SO_PRICE;
                        $SO_DISP 		= $row->SO_DISP;
                        if($SO_DISP == '')
                            $SO_DISP	= 0;
                        $SO_DISC 		= $row->SO_DISC;
                        if($SO_DISC == '')
                            $SO_DISC	= 0;
                        $SO_COST 		= $row->SO_COST;
                        if($SO_COST == '')
                            $SO_COST	= 0;
                        $TAXCODE1 		= $row->TAXCODE1;
                        $TAXPRICE1 		= $row->TAXPRICE1;
                    endforeach;
                    $AVGDISC		= $SO_DISC / $SO_VOLM;
                    $AVGTAX1		= $TAXPRICE1 / $SO_VOLM;

                    if($PRJSCATEG == 2)
                    	$SNPRICE 	= $SO_PRICE;

                    $d['SN_PRICE']	= $SNPRICE;
					$d['SN_DISC']	= $AVGDISC * $TOT_SNVOLM;
					$SN_DISC		= $AVGDISC * $TOT_SNVOLM;
					$d['SN_TOTAL']	= $TOT_SNVOLM * $SNPRICE;
					$d['TAXCODE1']	= $TAXCODE1;
					$d['TAXPRICE1']	= $AVGTAX1 * $TOT_SNVOLM;
					$TAXPRICE1		= $AVGTAX1 * $TOT_SNVOLM;

					$SN_VOLM		= $TOT_SNVOLM;
					$SNTOTAL 		= $SN_VOLM * $SNPRICE;
					//$SN_TOTCOST	= $SN_TOTCOST + $d['SN_TOTAL'];
					$SN_TOTVOLM 	= $SN_TOTVOLM + $TOT_SNVOLM;
					$SN_TOTCOST		= $SN_TOTCOST + $SNTOTAL;
					$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
					$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;

					$this->db->insert('tbl_sn_detail',$d);
				}
			}
			else
			{
				if($SO_NUM1 == '')
				{
					foreach($_POST['dataQRC'] as $dQRC)
					{
						$JO_NUM	= $dQRC['JO_NUM'];
					}

					if($JO_NUM != '')
					{
						$sqlDETC	= "tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE'";
	                    $resultC 	= $this->db->count_all($sqlDETC);

	                    $sqlSOD 	= "SELECT A.ITM_CODE, A.ITM_UNIT, A.SO_VOLM, 0 AS SN_VOLM,
	                                        A.SO_PRICE AS SN_PRICE, A.SO_DISP AS SN_DISP, A.SO_DISC AS SN_DISC,
	                                        A.SO_COST AS SN_TOTAL, A.SO_DESC AS SN_DESC, A.TAXCODE1, A.TAXPRICE1,
	                                        B.ITM_NAME
	                                    FROM tbl_so_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                     WHERE SO_NUM = '$SO_NUM'";
	                    $resSOD 	= $this->db->query($sqlSOD)->result();
	                    foreach($resSOD as $row) :
	                        $ITM_CODE 		= $row->ITM_CODE;
	                        $ITM_CODE1		= $d['ITM_CODE'];
	                        $ITM_NAME 		= $row->ITM_NAME;
	                        $ITM_UNIT 		= $row->ITM_UNIT;
	                        $SO_VOLM 		= $row->SO_VOLM;
	                        $SN_VOLM 		= $row->SN_VOLM;
	                        $SN_PRICE 		= $row->SN_PRICE;
	                        $SN_DISP 		= $row->SN_DISP;
	                        if($SN_DISP == '')
	                            $SN_DISP	= 0;
	                        $SN_DISC 		= $row->SN_DISC;
	                        if($SN_DISC == '')
	                            $SN_DISC	= 0;
	                        $SN_TOTAL 		= $row->SN_TOTAL;
	                        if($SN_TOTAL == '')
	                            $SN_TOTAL	= 0;
	                        $SN_DESC 		= $row->SN_DESC;
	                        $TAXCODE1 		= $row->TAXCODE1;
	                        $TAXPRICE1 		= $row->TAXPRICE1;

	                        $AVGDISC		= $SN_DISC / $SO_VOLM;
	                        $AVGTAX1		= $TAXPRICE1 / $SO_VOLM;

	                        $d['SN_NUM']	= $SN_NUM;
							$d['SN_CODE']	= $SN_CODE;
							$d['SN_DATE']	= $SN_DATE;
							$d['JO_NUM']	= $JO_NUM;
							$d['JO_CODE']	= $JO_CODE;
							$d['PRJCODE']	= $PRJCODE;
							$d['CUST_CODE']	= $CUST_CODE;
							$d['ITM_CODE']	= $ITM_CODE;
							$d['ITM_UNIT']	= $ITM_UNIT;
							$d['SO_VOLM']	= $SO_VOLM;

							//$d['SN_VOLM']	= $SN_VOLM;
							$TOT_SNVOLM		= 0;
							foreach($_POST['dataQRC'] as $dQRC)
							{
								$ITMCODE	= $dQRC['ITM_CODE'];
								$QRC_VOLM	= $dQRC['QRC_VOLM'];
								if($ITM_CODE1 != $ITMCODE)
									$TOT_SNVOLM	= $TOT_SNVOLM + $QRC_VOLM;
							}
							$d['SN_VOLM']	= $TOT_SNVOLM;

							$d['SN_PRICE']	= $SN_PRICE;
							$d['SO_PRICE']	= $SN_PRICE;
							$d['SN_DISP']	= $SN_DISP;
							$d['SN_DISC']	= $AVGDISC * $TOT_SNVOLM;
							$SN_DISC		= $AVGDISC * $TOT_SNVOLM;
							$d['SN_TOTAL']	= $TOT_SNVOLM * $SN_PRICE;
							$d['SN_DESC']	= $SN_DESC;
							$d['SN_DESC1']	= '';
							$d['TAXCODE1']	= $TAXCODE1;
							$d['TAXCODE2']	= '';
							$d['TAXPRICE1']	= $AVGTAX1 * $TOT_SNVOLM;
							$TAXPRICE1		= $AVGTAX1 * $TOT_SNVOLM;
							$d['TAXPRICE2']	= '';
							$d['SR_VOLM']	= 0;
							$d['SR_PRICE']	= 0;
							$d['SR_TOTAL']	= 0;
							$SNTOTAL 		= $TOT_SNVOLM * $SN_PRICE;
							$SN_TOTVOLM 	= $SN_TOTVOLM + $TOT_SNVOLM;
							$SN_TOTCOST		= $SN_TOTCOST + $SNTOTAL;
							$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
							$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;

							$this->db->insert('tbl_sn_detail',$d);
	                    endforeach;
					}
				}
				else
				{
					foreach($_POST['data'] as $d)
					{
						$ITMCODE1		= $d['ITM_CODE'];
						$d['SN_NUM']	= $SN_NUM;
						$d['SN_CODE']	= $SN_CODE;
						$d['SN_DATE']	= $SN_DATE;
						$d['JO_NUM']	= $JO_NUM;
						$d['JO_CODE']	= $JO_CODE;
						$d['PRJCODE']	= $PRJCODE;
						$d['CUST_CODE']	= $CUST_CODE;

						$TOT_SNVOLM		= 0;
						foreach($_POST['dataQRC'] as $dQRC)
						{
							$ITMCODE	= $dQRC['ITM_CODE'];
							$QRC_VOLM	= $dQRC['QRC_VOLM'];
							if($ITMCODE1 != $ITMCODE)
								$TOT_SNVOLM	= $TOT_SNVOLM + $QRC_VOLM;
						}

						$SO_VOLM 		= 0;
						$SO_PRICE 		= 0;
						$SO_DISP 		= 0;
						$SO_DISC 		= 0;
						$TAXCODE1 		= "";
						$TAXPRICE1 		= 0;
						$sqlSOD 		= "SELECT A.SO_VOLM, A.SO_PRICE, A.SO_DISP, A.SO_DISC, A.SO_COST, A.TAXCODE1, A.TAXPRICE1
		                                    FROM tbl_so_detail A
		                                    WHERE A.SO_NUM = '$SO_NUM' AND A.ITM_CODE = '$ITMCODE1'";
	                    $resSOD 		= $this->db->query($sqlSOD)->result();
	                    foreach($resSOD as $row) :
	                        $SO_VOLM 		= $row->SO_VOLM;
	                        $SO_PRICE 		= $row->SO_PRICE;
	                        $SO_DISP 		= $row->SO_DISP;
	                        if($SO_DISP == '')
	                            $SO_DISP	= 0;
	                        $SO_DISC 		= $row->SO_DISC;
	                        if($SO_DISC == '')
	                            $SO_DISC	= 0;
	                        $SO_COST 		= $row->SO_COST;
	                        if($SO_COST == '')
	                            $SO_COST	= 0;
	                        $TAXCODE1 		= $row->TAXCODE1;
	                        $TAXPRICE1 		= $row->TAXPRICE1;
	                    endforeach;

	                    $d['SO_VOLM']	= $SO_VOLM;
	                    $d['SO_PRICE']	= $SO_PRICE;

						$d['SN_VOLM']	= $TOT_SNVOLM;
						$SN_PRICE		= $d['SN_PRICE'];
						$d['SN_PRICE']	= $SN_PRICE;
	                    $d['SN_DISP'] 	= $SO_DISP;
						$SN_DISC		= $SO_DISP * $TOT_SNVOLM * $SN_PRICE / 100;
	                    $d['SN_DISC'] 	= $SN_DISC;
						$SN_TOTAL		= $TOT_SNVOLM * $SN_PRICE;
						$d['SN_TOTAL']	= $TOT_SNVOLM * $SN_PRICE;

	                    $TAXLA_PERC 	= 0;
	                    $sPPN			= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
                        $rPPN			= $this->db->query($sPPN)->result();
                        foreach($rPPN as $rwPPN) :
                            $TAXLA_PERC	= $rwPPN->TAXLA_PERC;
                        endforeach;
	                    if($TAXLA_PERC == 0 || $TAXLA_PERC == '')
	                    	$TAXLA_PERC = 0;

						$d['TAXCODE1']	= $TAXCODE1;
						$TAXPRICE1		= $TAXLA_PERC * $TOT_SNVOLM * $SN_PRICE / 100;
						$d['TAXPRICE1']	= $TAXPRICE1;

						$SN_TOTCOST		= $SN_TOTCOST + $SN_TOTAL;
						$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
						$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;
						$this->db->insert('tbl_sn_detail',$d);
					}
				}

				foreach($_POST['dataQRC'] as $dQRC)
				{
					$dQRC['SN_NUM']		= $SN_NUM;
					$dQRC['SN_CODE']	= $SN_CODE;
					$dQRC['SN_DATE']	= $SN_DATE;
					$dQRC['PRJCODE']	= $PRJCODE;
					$dQRC['CUST_CODE']	= $CUST_CODE;
					$dQRC['SO_NUM']		= $SO_NUM;
					$dQRC['SO_CODE']	= $SO_CODE;

					// GET FG CODE
						$QRCNUM 			= $dQRC['QRC_NUM'];
						$ITMCODEFG			= "";
						$sqlFG 				= "SELECT A.ICOLL_FG FROM tbl_item_collh A 
												INNER JOIN tbl_item_colld B ON A.ICOLL_NUM = B.ICOLL_NUM WHERE B.QRC_NUM = '$QRCNUM'";
	                    $resFG 				= $this->db->query($sqlFG)->result();
	                    foreach($resFG as $rowFG) :
							$ITMCODEFG 		= $rowFG->ICOLL_FG;
						endforeach;
						$dQRC['ITM_CODEFG']	= $ITMCODEFG;

					$this->db->insert('tbl_sn_detail_qrc',$dQRC);
				}
			}

			$updSNH			= array('SN_TOTCOST' 	=> $SN_TOTVOLM,
									'SN_TOTCOST' 	=> $SN_TOTCOST,
									'SN_TOTPPN' 	=> $SN_TOTPPN,
									'SN_TOTDISC' 	=> $SN_TOTDISC);
			$this->m_shipment->updateSNH($SN_NUM, $updSNH);

			if($SO_NUM1 == '')
			{
				// UPDATE DETAIL
					$this->m_shipment->updateDet($SN_NUM, $SO_NUM, $PRJCODE, $SN_DATE);
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('SN_STAT');			// IF "ADD" CONDITION ALWAYS = SN_STAT
				$parameters 	= array('DOC_CODE' 		=> $SN_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sn_header",	// TABLE NAME
										'KEY_NAME'		=> "SN_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SN_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SN_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SN",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SN_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SN_NUM",
										'DOC_CODE' 		=> $SN_NUM,
										'DOC_STAT' 		=> $SN_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_sn_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SN_NUM;
				$MenuCode 		= 'MN041';
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

				$parameters 	= array('TR_TYPE'		=> "SN",
										'TR_DATE' 		=> $SN_DATE,
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
			
			$url			= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u44_sN0t35() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_shipment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$SN_NUM		= $EXTRACTCOL[1];
								
			$getsn_head				= $this->m_shipment->get_sn_by_number($SN_NUM)->row();
			$PRJCODE				= $getsn_head->PRJCODE;
			
			// GET MENU DESC
				$mnCode				= 'MN041';
				$data["MenuApp"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pengiriman";
				$data["h2_title"] 	= "penjualan";
			}
			else
			{
				$data["h1_title"] 	= "Shipment";
				$data["h2_title"] 	= "penjualan";
			}
			
			$data['form_action']	= site_url('c_sales/c_sh1pn0t35/update_process');
			$cancelURL				= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_shipment->count_all_CUST();
			$data['vwCUST'] 		= $this->m_shipment->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN041';
			$data["MenuCode"] 		= 'MN041';
			
			$getSN 							= $this->m_shipment->get_sn_by_number($SN_NUM)->row();
			$data['default']['SN_NUM'] 		= $getSN->SN_NUM;
			$data['default']['SN_CODE'] 	= $getSN->SN_CODE;
			$data['default']['SN_TYPE'] 	= $getSN->SN_TYPE;
			$data['default']['SN_DATE'] 	= $getSN->SN_DATE;
			$data['default']['SN_RECEIVED'] = $getSN->SN_RECEIVED;
			$data['default']['PRJCODE'] 	= $getSN->PRJCODE;
			$data['default']['CUST_CODE'] 	= $getSN->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getSN->CUST_ADDRESS;
			$data['default']['SO_NUM'] 		= $getSN->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSN->SO_CODE;
			$data['default']['SO_DATE'] 	= $getSN->SO_DATE;
			$data['default']['SN_TOTVOLM'] 	= $getSN->SN_TOTVOLM;
			$data['default']['SN_TOTCOST'] 	= $getSN->SN_TOTCOST;
			$data['default']['SN_TOTPPN'] 	= $getSN->SN_TOTPPN;
			$data['default']['SN_DRIVER'] 	= $getSN->SN_DRIVER;
			$data['default']['VEH_CODE'] 	= $getSN->VEH_CODE;
			$data['default']['VEH_NOPOL'] 	= $getSN->VEH_NOPOL;
			$data['default']['SN_RECEIVER'] = $getSN->SN_RECEIVER;
			$data['default']['SN_NOTES'] 	= $getSN->SN_NOTES;
			$data['default']['SN_NOTES1']	= $getSN->SN_NOTES1;
			$data['default']['SN_STAT'] 	= $getSN->SN_STAT;
			$data['default']['SN_REFNO']	= $getSN->SN_REFNO;
			$data['default']['Patt_Year'] 	= $getSN->Patt_Year;
			$data['default']['Patt_Month'] 	= $getSN->Patt_Month;
			$data['default']['Patt_Date'] 	= $getSN->Patt_Date;
			$data['default']['Patt_Number'] = $getSN->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSN->SN_NUM;
				$MenuCode 		= 'MN041';
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
			
			$this->load->view('v_sales/v_shipment/v_shipment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);
		
		$PRJSCATEG  = $this->session->userdata['PRJSCATEG'];

		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SN_CREATED 	= date('Y-m-d H:i:s');
		
		$comp_init 		= $this->session->userdata('comp_init');
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();

			$SN_NUM 		= $this->input->post('SN_NUM');
			$SN_CODE 		= $this->input->post('SN_CODE');
			$SN_TYPE 		= $this->input->post('SN_TYPE');
			$SN_DATE		= date('Y-m-d',strtotime($this->input->post('SN_DATE')));
			
			$PRJCODE 		= $this->input->post('PRJCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_NUM1 		= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			$SO_DATE		= $this->input->post('SO_DATE');

			if($SN_TYPE == 4)
			{
				$JO_NUM		= $SO_NUM;
				$JO_CODE	= $SO_CODE;
			}
			else
			{
				if($SO_NUM == '')
				{
					foreach($_POST['dataQRC'] as $dQRC)
					{
						$JO_NUM	= $dQRC['JO_NUM'];
					}
					if($JO_NUM != '')
					{
						$sqlSO		= "SELECT A.CUST_CODE, A.CUST_DESC, A.JO_CODE, A.JO_DATE, A.SO_NUM, A.SO_CODE, B.CUST_ADD1, C.SO_DATE
										FROM tbl_jo_header A
											LEFT JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
											LEFT JOIN tbl_so_header C ON A.SO_NUM = C.SO_NUM
										WHERE JO_NUM = '$JO_NUM' LIMIT 1";
						$resSO		= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO) :
							$CUST_CODE		= $rowSO->CUST_CODE;
							$CUST_DESC		= $rowSO->CUST_DESC;
							$CUST_ADDRESS	= $rowSO->CUST_ADD1;
							$JO_CODE		= $rowSO->JO_CODE;
							$SO_NUM			= $rowSO->SO_NUM;
							$SO_CODE		= $rowSO->SO_CODE;
							$SO_DATE		= $rowSO->SO_DATE;
						endforeach;
					}
				}
				else
				{
					$JO_NUM 	= "";
					$JO_CODE 	= "";
					foreach($_POST['dataQRC'] as $dQRC)
					{
						$JO_NUM	= $dQRC['JO_NUM'];
					}
					if($JO_NUM != '')
					{
						$sqlSO		= "SELECT A.JO_CODE FROM tbl_jo_header A WHERE JO_NUM = '$JO_NUM' LIMIT 1";
						$resSO		= $this->db->query($sqlSO)->result();
						foreach($resSO as $rowSO) :
							$JO_CODE		= $rowSO->JO_CODE;
						endforeach;
					}
				}
			}
			
			$SN_DRIVER 		= $this->input->post('SN_DRIVER');
			$VEH_CODE 		= $this->input->post('VEH_CODE');
			$VEH_NOPOL 		= $this->input->post('VEH_NOPOL');
			$SN_NOTES 		= addslashes($this->input->post('SN_NOTES'));
			$SN_TOTVOLM 	= $this->input->post('SN_TOTVOLM');
			$SN_STAT		= $this->input->post('SN_STAT');
			$Patt_Year		= date('Y',strtotime($this->input->post('SN_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('SN_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('SN_DATE')));

			$CUST_DESC		= '';
			$sqlCUST		= "SELECT CUST_DESC FROM tbl_customer where CUST_CODE = '$CUST_CODE' LIMIT 1";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $rowCUST) :
				$CUST_DESC = $rowCUST->CUST_DESC;
			endforeach;
			if($CUST_DESC == '')
				$CUST_DESC	= "-";
			
			$UpdSN			= array('SN_NUM' 		=> $SN_NUM,
									'SN_CODE' 		=> $SN_CODE,
									'SN_TYPE' 		=> $SN_TYPE,
									'SN_DATE'		=> $SN_DATE,
									'PRJCODE'		=> $PRJCODE,
									'CUST_CODE' 	=> $CUST_CODE,
									'CUST_DESC' 	=> $CUST_DESC,
									'CUST_ADDRESS'	=> $CUST_ADDRESS,
									'SO_NUM' 		=> $SO_NUM,
									'SO_CODE'		=> $SO_CODE,
									'SO_DATE'		=> $SO_DATE,
									'JO_NUM' 		=> $JO_NUM,
									'JO_CODE'		=> $JO_CODE,
									'SN_DRIVER' 	=> $SN_DRIVER, 
									'VEH_CODE' 		=> $VEH_CODE,									
									'VEH_NOPOL' 	=> $VEH_NOPOL, 
									'SN_NOTES' 		=> $SN_NOTES,
									'SN_TOTVOLM'	=> $SN_TOTVOLM,
									'SN_STAT'		=> $SN_STAT,
									'Patt_Year'		=> $Patt_Year,
									'Patt_Month'	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date);
			$this->m_shipment->updateSN($SN_NUM, $UpdSN);

			if($SN_STAT == 1 || $SN_STAT == 2)
			{
				if($SN_TYPE == 4)
				{
					$this->m_shipment->deleteSNDetail($SN_NUM);

					$SN_TOTVOLM	= 0;
					$SN_TOTCOST	= 0;
					$SN_TOTPPN	= 0;
					$SN_TOTDISC	= 0;

					foreach($_POST['data'] as $d)
					{
						$ITMCODE1		= $d['ITM_CODE'];
						$d['SN_NUM']	= $SN_NUM;
						$d['SN_CODE']	= $SN_CODE;
						$d['SN_DATE']	= $SN_DATE;
						$d['JO_NUM']	= $JO_NUM;
						$d['JO_CODE']	= $JO_CODE;
						$d['PRJCODE']	= $PRJCODE;
						$d['CUST_CODE']	= $CUST_CODE;

						$TOT_SNVOLM		= $d['SN_VOLM'];
						$SNPRICE		= $d['SN_PRICE'];

						$SO_VOLM 		= 0;
						$SO_PRICE 		= 0;
						$SO_DISC 		= 0;
						$TAXCODE1		= "";
						$TAXPRICE1 		= 0;
						$sqlSOD 		= "SELECT A.SO_VOLM, A.SO_PRICE, A.SO_DISP, A.SO_DISC, A.SO_COST, A.TAXCODE1, A.TAXPRICE1
		                                    FROM tbl_so_detail A
		                                    WHERE A.SO_NUM = '$SO_NUM' AND A.ITM_CODE = '$ITMCODE1'";
	                    $resSOD 		= $this->db->query($sqlSOD)->result();
	                    foreach($resSOD as $row) :
	                        $SO_VOLM 		= $row->SO_VOLM;
	                        $SO_PRICE 		= $row->SO_PRICE;
	                        $SO_DISP 		= $row->SO_DISP;
	                        if($SO_DISP == '')
	                            $SO_DISP	= 0;
	                        $SO_DISC 		= $row->SO_DISC;
	                        if($SO_DISC == '')
	                            $SO_DISC	= 0;
	                        $SO_COST 		= $row->SO_COST;
	                        if($SO_COST == '')
	                            $SO_COST	= 0;
	                        $TAXCODE1 		= $row->TAXCODE1;
	                        $TAXPRICE1 		= $row->TAXPRICE1;
	                    endforeach;
	                    $AVGDISC		= $SO_DISC / $SO_VOLM;
	                    $AVGTAX1		= $TAXPRICE1 / $SO_VOLM;

	                    if($PRJSCATEG == 2)
	                    	$SNPRICE 	= $SO_PRICE;

	                    $d['SN_PRICE']	= $SNPRICE;
						$d['SN_DISC']	= $AVGDISC * $TOT_SNVOLM;
						$SN_DISC		= $AVGDISC * $TOT_SNVOLM;
						$d['SN_TOTAL']	= $TOT_SNVOLM * $SNPRICE;
						$d['TAXCODE1']	= $TAXCODE1;
						$d['TAXPRICE1']	= $AVGTAX1 * $TOT_SNVOLM;
						$TAXPRICE1		= $AVGTAX1 * $TOT_SNVOLM;

						$SN_VOLM		= $TOT_SNVOLM;
						$SNTOTAL 		= $SN_VOLM * $SNPRICE;
						//$SN_TOTCOST	= $SN_TOTCOST + $d['SN_TOTAL'];
						$SN_TOTVOLM 	= $SN_TOTVOLM + $TOT_SNVOLM;
						$SN_TOTCOST		= $SN_TOTCOST + $SNTOTAL;
						$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
						$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;

						$this->db->insert('tbl_sn_detail',$d);
					}

					$updSNH			= array('SN_TOTVOLM'	=> $SN_TOTVOLM,
											'SN_TOTCOST' 	=> $SN_TOTCOST,
											'SN_TOTPPN' 	=> $SN_TOTPPN,
											'SN_TOTDISC' 	=> $SN_TOTDISC);
					$this->m_shipment->updateSNH($SN_NUM, $updSNH);
				}
				else
				{
					$this->m_shipment->deleteSNDetail($SN_NUM);
					$this->m_shipment->deleteSNDetailQRC($SN_NUM);

					$SN_TOTVOLM	= 0;
					$SN_TOTCOST	= 0;
					$SN_TOTPPN	= 0;
					$SN_TOTDISC	= 0;

					foreach($_POST['dataQRC'] as $dQRC)
					{
						$dQRC['SN_NUM']		= $SN_NUM;
						$dQRC['SN_CODE']	= $SN_CODE;
						$dQRC['SN_DATE']	= $SN_DATE;
						$dQRC['PRJCODE']	= $PRJCODE;
						$dQRC['CUST_CODE']	= $CUST_CODE;
						$dQRC['SO_NUM']		= $SO_NUM;
						$dQRC['SO_CODE']	= $SO_CODE;
						$QRC_NUM			= $dQRC['QRC_NUM'];

						// GET FG CODE
							$ITM_CODEFG		= "";
							$sqlFG 			= "SELECT A.ICOLL_FG FROM tbl_item_collh A 
												INNER JOIN tbl_item_colld B ON A.ICOLL_NUM = B.ICOLL_NUM WHERE B.QRC_NUM = '$QRC_NUM'";
		                    $resFG 			= $this->db->query($sqlFG)->result();
		                    foreach($resFG as $rowFG) :
								$ITM_CODEFG = $rowFG->ICOLL_FG;
							endforeach;

						$dQRC['ITM_CODEFG']	= $ITM_CODEFG;
						$ITM_CODERCC		= $dQRC['ITM_CODE'];

						// GET PRICE ROLL
							$SOPRICE 		= 0;
							$sqlSOD 		= "SELECT DISTINCT A.SO_PRICE FROM tbl_so_detail A
													INNER JOIN tbl_jo_detail B ON A.SO_NUM = B.SO_NUM AND A.ITM_CODE = B.ITM_CODE
												WHERE A.SO_NUM = '$SO_NUM'";
		                    $resSOD 	= $this->db->query($sqlSOD)->result();
		                    foreach($resSOD as $rowSOPRC) :
								$SOPRICE 	= $rowSOPRC->SO_PRICE;
							endforeach;
							if($SOPRICE == '')
								$SOPRICE 	= 0;

						$dQRC['QRC_PRICE']	= $SOPRICE;
						$dQRC['QRC_SOPRC']	= $SOPRICE;
						$this->db->insert('tbl_sn_detail_qrc',$dQRC);
					}

					$SN_TOTCOST	= 0;
					$SN_TOTPPN	= 0;
					$SN_TOTDISC	= 0;
					if($SO_NUM1 == '')
					{
						foreach($_POST['dataQRC'] as $dQRC)
						{
							$JO_NUM	= $dQRC['JO_NUM'];
						}

						if($JO_NUM != '')
						{
							$sqlDETC	= "tbl_so_detail WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE'";
		                    $resultC 	= $this->db->count_all($sqlDETC);

		                    $sqlSOD 	= "SELECT A.ITM_CODE, A.ITM_UNIT, A.SO_VOLM, 0 AS SN_VOLM,
		                                        A.SO_PRICE AS SN_PRICE, A.SO_DISP AS SN_DISP, A.SO_DISC AS SN_DISC,
		                                        A.SO_COST AS SN_TOTAL, A.SO_DESC AS SN_DESC, A.TAXCODE1, A.TAXPRICE1,
		                                        B.ITM_NAME
		                                    FROM tbl_so_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
		                                     WHERE SO_NUM = '$SO_NUM'";
		                    $resSOD 	= $this->db->query($sqlSOD)->result();
		                    foreach($resSOD as $row) :
		                        $ITM_CODE 		= $row->ITM_CODE;
		                        $ITM_CODE1		= $d['ITM_CODE'];
		                        $ITM_NAME 		= $row->ITM_NAME;
		                        $ITM_UNIT 		= $row->ITM_UNIT;
		                        $SO_VOLM 		= $row->SO_VOLM;
		                        $SN_VOLM 		= $row->SN_VOLM;
		                        $SN_PRICE 		= $row->SN_PRICE;
		                        $SN_DISP 		= $row->SN_DISP;
		                        if($SN_DISP == '')
		                            $SN_DISP	= 0;
		                        $SN_DISC 		= $row->SN_DISC;
		                        if($SN_DISC == '')
		                            $SN_DISC	= 0;
		                        $SN_TOTAL 		= $row->SN_TOTAL;
		                        if($SN_TOTAL == '')
		                            $SN_TOTAL	= 0;
		                        $SN_DESC 		= $row->SN_DESC;
		                        $TAXCODE1 		= $row->TAXCODE1;
		                        $TAXPRICE1 		= $row->TAXPRICE1;

		                        $AVGDISC		= $SN_DISC / $SO_VOLM;
		                        $AVGTAX1		= $TAXPRICE1 / $SO_VOLM;

		                        $d['SN_NUM']	= $SN_NUM;
								$d['SN_CODE']	= $SN_CODE;
								$d['SN_DATE']	= $SN_DATE;
								$d['JO_NUM']	= $JO_NUM;
								$d['JO_CODE']	= $JO_CODE;
								$d['PRJCODE']	= $PRJCODE;
								$d['CUST_CODE']	= $CUST_CODE;
								$d['ITM_CODE']	= $ITM_CODE;
								$d['ITM_UNIT']	= $ITM_UNIT;
								$d['SO_VOLM']	= $SO_VOLM;

								//$d['SN_VOLM']	= $SN_VOLM;
								$TOT_SNVOLM		= 0;
								foreach($_POST['dataQRC'] as $dQRC)
								{
									$ITMCODE	= $dQRC['ITM_CODE'];
									$QRC_VOLM	= $dQRC['QRC_VOLM'];
									if($ITM_CODE1 != $ITMCODE)
										$TOT_SNVOLM	= $TOT_SNVOLM + $QRC_VOLM;
								}
								$d['SN_VOLM']	= $TOT_SNVOLM;

								$d['SN_PRICE']	= $SN_PRICE;
								$d['SO_PRICE']	= $SN_PRICE;
								$d['SN_DISP']	= $SN_DISP;
								$d['SN_DISC']	= $AVGDISC * $TOT_SNVOLM;
								$SN_DISC		= $AVGDISC * $TOT_SNVOLM;
								$d['SN_TOTAL']	= $TOT_SNVOLM * $SN_PRICE;
								$d['SN_DESC']	= $SN_DESC;
								$d['SN_DESC1']	= '';
								$d['TAXCODE1']	= $TAXCODE1;
								$d['TAXCODE2']	= '';
								$d['TAXPRICE1']	= $AVGTAX1 * $TOT_SNVOLM;
								$TAXPRICE1		= $AVGTAX1 * $TOT_SNVOLM;
								$d['TAXPRICE2']	= '';
								$d['SR_VOLM']	= 0;
								$d['SR_PRICE']	= 0;
								$d['SR_TOTAL']	= 0;
								$SNTOTAL 		= $TOT_SNVOLM * $SN_PRICE;
								$SN_TOTVOLM 	= $SN_TOTVOLM + $TOT_SNVOLM;
								$SN_TOTCOST		= $SN_TOTCOST + $SNTOTAL;
								$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
								$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;

								$this->db->insert('tbl_sn_detail',$d);
		                    endforeach;
						}
					}
					else
					{
						foreach($_POST['data'] as $d)
						{
							$ITMCODE1		= $d['ITM_CODE'];
							$d['SN_NUM']	= $SN_NUM;
							$d['SN_CODE']	= $SN_CODE;
							$d['SN_DATE']	= $SN_DATE;
							$d['JO_NUM']	= $JO_NUM;
							$d['JO_CODE']	= $JO_CODE;
							$d['PRJCODE']	= $PRJCODE;
							$d['CUST_CODE']	= $CUST_CODE;

							$TOT_SNVOLM		= 0;
							foreach($_POST['dataQRC'] as $dQRC)
							{
								$ITMCODE	= $dQRC['ITM_CODE'];
								$QRC_VOLM	= $dQRC['QRC_VOLM'];
								if($ITMCODE1 != $ITMCODE)
									$TOT_SNVOLM	= $TOT_SNVOLM + $QRC_VOLM;
							}

							$SO_VOLM 		= 0;
							$SO_PRICE 		= 0;
							$SO_DISP 		= 0;
							$SO_DISC 		= 0;
							$TAXCODE1 		= "";
							$TAXPRICE1 		= 0;
							$sqlSOD 		= "SELECT A.SO_VOLM, A.SO_PRICE, A.SO_DISP, A.SO_DISC, A.SO_COST, A.TAXCODE1, A.TAXPRICE1
			                                    FROM tbl_so_detail A
			                                    WHERE A.SO_NUM = '$SO_NUM' AND A.ITM_CODE = '$ITMCODE1'";
		                    $resSOD 		= $this->db->query($sqlSOD)->result();
		                    foreach($resSOD as $row) :
		                        $SO_VOLM 		= $row->SO_VOLM;
		                        $SO_PRICE 		= $row->SO_PRICE;
		                        $SO_DISP 		= $row->SO_DISP;
		                        if($SO_DISP == '')
		                            $SO_DISP	= 0;
		                        $SO_DISC 		= $row->SO_DISC;
		                        if($SO_DISC == '')
		                            $SO_DISC	= 0;
		                        $SO_COST 		= $row->SO_COST;
		                        if($SO_COST == '')
		                            $SO_COST	= 0;
		                        $TAXCODE1 		= $row->TAXCODE1;
		                        $TAXPRICE1 		= $row->TAXPRICE1;
		                    endforeach;

		                    $d['SO_VOLM']	= $SO_VOLM;
		                    $d['SO_PRICE']	= $SO_PRICE;

							$d['SN_VOLM']	= $TOT_SNVOLM;
							$SN_PRICE		= $d['SN_PRICE'];
							$d['SN_PRICE']	= $SN_PRICE;
		                    $d['SN_DISP'] 	= $SO_DISP;
							$SN_DISC		= $SO_DISP * $TOT_SNVOLM * $SN_PRICE / 100;
		                    $d['SN_DISC'] 	= $SN_DISC;
							$SN_TOTAL		= $TOT_SNVOLM * $SN_PRICE;
							$d['SN_TOTAL']	= $TOT_SNVOLM * $SN_PRICE;

		                    $TAXLA_PERC 	= 0;
		                    $sPPN			= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
	                        $rPPN			= $this->db->query($sPPN)->result();
	                        foreach($rPPN as $rwPPN) :
	                            $TAXLA_PERC	= $rwPPN->TAXLA_PERC;
	                        endforeach;
		                    if($TAXLA_PERC == 0 || $TAXLA_PERC == '')
		                    	$TAXLA_PERC = 0;

							$d['TAXCODE1']	= $TAXCODE1;
							$TAXPRICE1		= $TAXLA_PERC * $TOT_SNVOLM * $SN_PRICE / 100;
							$d['TAXPRICE1']	= $TAXPRICE1;

							$SN_TOTCOST		= $SN_TOTCOST + $SN_TOTAL;
							$SN_TOTPPN		= $SN_TOTPPN + $TAXPRICE1;
							$SN_TOTDISC		= $SN_TOTDISC + $SN_DISC;
							$this->db->insert('tbl_sn_detail',$d);
						}
					}
					
					$updSNH			= array('SN_TOTCOST' 	=> $SN_TOTCOST,
											'SN_TOTPPN' 	=> $SN_TOTPPN,
											'SN_TOTDISC' 	=> $SN_TOTDISC);
					$this->m_shipment->updateSNH($SN_NUM, $updSNH);

					// UPDATE DETAIL
						$this->m_shipment->updateDet($SN_NUM, $SO_NUM, $PRJCODE, $SN_DATE);
				}
			}
			elseif($SN_STAT == 6)
			{
				/*foreach($_POST['data'] as $d)
				{
					$SN_NUM		= $d['SN_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					//$this->m_shipment->updateVolBud($SN_NUM, $PRJCODE, $ITM_CODE);
				}*/
			}
			elseif($SN_STAT == 9)
			{
				$SN_NOTES1 		= addslashes($this->input->post('SN_NOTES1'));
				// UPDATE HEADER
					$updSN 		= array('SN_STAT'	=> $SN_STAT,
										'SN_NOTES1'	=> $SN_NOTES1);
					$this->m_shipment->updateSNInb($SN_NUM, $updSN);

				// UPDATE DETAIL : PENGEMBALIAN STOCK
					$SN_TOTVOLM	= 0;
					$SN_TOTCOST	= 0;
					$SN_TOTDISC	= 0;
					$SN_TOTPPN	= 0;
					$SN_TOTPPH	= 0;
					$sqlGetSN	= "SELECT A.SN_NUM, A.SN_CODE, A.ITM_CODE, A.SN_VOLM, A.SN_PRICE, A.SN_DISC, A.SN_TOTAL,
										A.TAXPRICE1, A.TAXPRICE2, A.JOBCODEID,
										B.SO_NUM, B.SO_CODE
									FROM tbl_sn_detail A
										INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
									WHERE A.SN_NUM = '$SN_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resGetSN	= $this->db->query($sqlGetSN)->result();
					foreach($resGetSN as $rowSN) :
						$SN_NUM 		= $rowSN->SN_NUM;
						$SN_CODE 		= $rowSN->SN_CODE;
						$SO_NUM 		= $rowSN->SO_NUM;
						$SO_CODE 		= $rowSN->SO_CODE;
						$JOBCODEID 		= $rowSN->JOBCODEID;
						$ITM_CODE 		= $rowSN->ITM_CODE;
						$SN_VOLM		= $rowSN->SN_VOLM;
						$SN_PRICE		= $rowSN->SN_PRICE;
						$SN_DISC		= $rowSN->SN_DISC;
						$SN_TOTAL		= $rowSN->SN_TOTAL;
						$TAXPRICE1		= $rowSN->TAXPRICE1;
						$TAXPRICE2		= $rowSN->TAXPRICE2;
						
						$SN_VOLM_NOW	= $rowSN->SN_VOLM;
						$SN_PRICE_NOW	= $rowSN->SN_PRICE;
						$SN_COST_NOW	= $SN_VOLM_NOW * $SN_PRICE_NOW;

						$ITM_GROUP 		= 'M';
						$ITM_CATEG 		= 'M';
						$sqlITMCAT		= "SELECT ITM_GROUP, ITM_CATEG FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
						$resITMCAT		= $this->db->query($sqlITMCAT)->result();
						foreach($resITMCAT as $rowicat) :
							$ITM_GROUP 	= $rowicat->ITM_GROUP;
							$ITM_CATEG 	= $rowicat->ITM_CATEG;
						endforeach;

						$sqlUpd			= "UPDATE tbl_so_detail SET SN_VOLM = SN_VOLM - $SN_VOLM_NOW, SN_AMOUNT = SN_AMOUNT - $SN_COST_NOW
											WHERE SO_NUM = '$SO_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
						$this->db->query($sqlUpd);

						$sqlUpd2		= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $SN_VOLM_NOW, SN_VOLM = SN_VOLM - $SN_VOLM_NOW,
												SN_AMOUNT = SN_AMOUNT - $SN_COST_NOW, ITM_OUT = ITM_OUT - $SN_VOLM_NOW, ITM_OUTP = ITM_OUTP - $SN_COST_NOW
											WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$this->db->query($sqlUpd2);

						// PEREKAMAN JEJAK KE tbl_itemhistory
							$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
												QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
												JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
											VALUES ('$SN_NUM', '$PRJCODE', '$SN_CREATED', '$ITM_CODE', $SN_VOLM_NOW, 0, 
												0, 0, $SN_VOLM_NOW, 0, 'V-SN', $SN_COST_NOW, '$comp_init', 'IDR', 
												'$JOBCODEID', 9, '$SN_PRICE', '$ITM_CATEG', 'Pengembalian Pengiriman : $SN_CODE ($SO_CODE)')";
							$this->db->query($sqlHist);

						// PROFIT AND LOSS
							$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);

							$PERIODM	= date('m', strtotime($SN_DATE));
							$PERIODY	= date('Y', strtotime($SN_DATE));
							if($ITM_GROUP == 'M')
							{
								// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
								if($ITM_TYPE == 1)
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$SN_COST_NOW 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$SN_COST_NOW 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$SN_COST_NOW
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_TYPE == 9)
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$SN_COST_NOW
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_TYPE == 10)
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$SN_COST_NOW
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
							}
							elseif($ITM_GROUP == 'T')
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$SN_COST_NOW 
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
					endforeach;

				// UPDATE QRCODE
					if($SN_TYPE == 1)
					{
						$sqlQRCSN 	= "SELECT QRC_NUM, SN_NUM, SN_CODE FROM tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM'";
						$resQRCSN	= $this->db->query($sqlQRCSN)->result();
						foreach($resQRCSN as $rowQRCSN) :
							$QRC_NUM 	= $rowQRCSN->QRC_NUM;
							$SN_NUM 	= $rowQRCSN->SN_NUM;
							$SN_CODE 	= $rowQRCSN->SN_CODE;
							// UPDATE
								$updQRC	= "UPDATE tbl_qrc_detail SET QRC_STATS = 0, SN_NUM = '', SN_CODE = '' WHERE QRC_NUM = '$QRC_NUM'";
								$this->db->query($updQRC);

								$updGRP	= "UPDATE tbl_item_colld SET QRC_STATS = 0, SN_NUM = '', SN_CODE = '' WHERE QRC_NUM = '$QRC_NUM'";
								$this->db->query($updGRP);		
						endforeach;
					}

				// UPDATE JOURNAL HEADER
					$sqlUpdJH		= "UPDATE tbl_journalheader SET JournalH_Desc3 = 'Void by $DefEmp_ID', LastUpdate = '$SN_CREATED', isCanceled = 1,
											GEJ_STAT = 9, STATDESC = 'Void', STATCOL = 'danger'
										WHERE proj_Code = '$PRJCODE' AND JournalH_Code = '$SN_NUM'";
					$this->db->query($sqlUpdJH);

				// UPDATE JOURNAL DETAIL
					$sqlJD			= "SELECT JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, Base_Debet, Base_Kredit,
											Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE
										FROM tbl_journaldetail WHERE JournalH_Code = '$SN_NUM' AND proj_Code = '$PRJCODE'";
					$resJD			= $this->db->query($sqlJD)->result();
					foreach($resJD as $rowJD) :
						$JD_NUM 	= $rowJD->JournalH_Code;
						$ACC_NUM 	= $rowJD->Acc_Id;
						$proj_Code 	= $rowJD->proj_Code;
						$JD_Debet 	= $rowJD->Base_Debet;
						$JD_Kredit 	= $rowJD->Base_Kredit;
						$Journal_DK = $rowJD->Journal_DK;

						if($Journal_DK == 'D')
						{
							$transacValue 		= $JD_Debet;
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
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
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
						else
						{
							$transacValue 		= $JD_Kredit;
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
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
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					endforeach;
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = SN_STAT
				$parameters 	= array('DOC_CODE' 		=> $SN_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sn_header",	// TABLE NAME
										'KEY_NAME'		=> "SN_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SN_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SN_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SN",		// OKRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SN_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SN_NUM",
										'DOC_CODE' 		=> $SN_NUM,
										'DOC_STAT' 		=> $SN_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'TBLNAME'		=> "tbl_sn_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SN_NUM;
				$MenuCode 		= 'MN041';
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

				$parameters 	= array('TR_TYPE'		=> "SN",
										'TR_DATE' 		=> $SN_DATE,
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
			
			$url			= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function inbox() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_sh1pn0t35/prj180c21l_1n2/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l_1n2() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN042';
				$data["MenuApp"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN042';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_sh1pn0t35/gl5h1pm3nt_1n2/?id=";
			
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
	
	function gl5h1pm3nt_1n2() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);
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
				$mnCode				= 'MN042';
				$data["MenuApp"] 	= 'MN042';
				$data["MenuCode"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['addURL'] 	= site_url('c_sales/c_sh1pn0t35/u44_sN0t35_1n2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_sh1pn0t35/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$num_rows 			= $this->m_shipment->count_all_sn_inb($PRJCODE);
			$data["countSO"] 	= $num_rows;
	 
			$data['vwSO'] = $this->m_shipment->get_all_sn_inb($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN042';
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
			
			$this->load->view('v_sales/v_shipment/v_inb_shipment', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

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
			
			$columns_valid 	= array("ID",
									"SN_CODE",
									"SN_DATE",
									"CUST_DESC",
									"SN_NOTE",
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
			$num_rows 		= $this->m_shipment->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_shipment->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SN_NUM 		= $dataI['SN_NUM'];
                $SN_CODE 		= $dataI['SN_CODE'];
                $SN_TYPE 		= $dataI['SN_TYPE'];
                $SN_DATE 		= $dataI['SN_DATE'];
                $SN_DATEV		= date('d M Y', strtotime($SN_DATE));
                $PRJCODE 		= $dataI['PRJCODE'];
                $SO_CODE 		= $dataI['SO_CODE'];
                $CUST_CODE 		= $dataI['CUST_CODE'];
                $CUST_DESC 		= $dataI['CUST_DESC'];
                if($CUST_DESC == '')
                {
                	$sqlCUST 	= "SELECT CUST_DESC FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
					$resCUST 	= $this->db->query($sqlCUST)->result();
					foreach($resCUST as $rowCUST) :															
						$CUST_DESC	= $rowCUST->CUST_DESC;
					endforeach;
				}

				$CUST_ADDRESS	= $dataI['CUST_ADDRESS'];
                $SN_TOTCOST		= $dataI['SN_TOTCOST'];
                $SN_STAT 		= $dataI['SN_STAT'];
                $SN_NOTES		= $dataI['SN_NOTES'];
				
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);

				$CollID1		= "$PRJCODE~$SN_NUM";
				$secUpd			= site_url('c_sales/c_sh1pn0t35/u44_sN0t35_1n2/?id='.$this->url_encryption_helper->encode_url($CollID1));
				$secPrint		= site_url('c_sales/c_sh1pn0t35/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($SN_NUM));
				$secDel 		= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDel~tbl_sn_header~tbl_sn_detail~SN_NUM~$SN_NUM~PRJCODE~$PRJCODE";

				if($SN_STAT == 1) 
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
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
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				
				$output['data'][] = array("$noU.",
										  $dataI['SN_CODE'],
										  $SN_DATEV,
										  $SO_CODE,
										  $CUST_DESC,
										  $CUST_ADDRESS,
										  "<span style='white-space:nowrap'>".$CREATERNM."</span>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function u44_sN0t35_1n2() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_shipment', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$SN_NUM		= $EXTRACTCOL[1];
								
			$getsn_head				= $this->m_shipment->get_sn_by_number($SN_NUM)->row();
			$PRJCODE				= $getsn_head->PRJCODE;
			
			// GET MENU DESC
				$mnCode				= 'MN042';
				$data["MenuApp"] 	= 'MN042';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$EmpID 					= $this->session->userdata('Emp_ID');
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Pengiriman";
				$data["h2_title"] 	= "persetujuan dokumen";
			}
			else
			{
				$data["h1_title"] 	= "Shipment";
				$data["h2_title"] 	= "document approval";
			}
			
			$data['form_action']	= site_url('c_sales/c_sh1pn0t35/update_process_inb');
			$cancelURL				= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countCUST']		= $this->m_shipment->count_all_CUST();
			$data['vwCUST'] 		= $this->m_shipment->get_all_CUST()->result();
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN042';
			$data["MenuCode"] 		= 'MN042';
			
			$getSN 							= $this->m_shipment->get_sn_by_number($SN_NUM)->row();
			$data['default']['SN_NUM'] 		= $getSN->SN_NUM;
			$data['default']['SN_CODE'] 	= $getSN->SN_CODE;
			$data['default']['SN_TYPE'] 	= $getSN->SN_TYPE;
			$data['default']['SN_DATE'] 	= $getSN->SN_DATE;
			$data['default']['SN_RECEIVED'] = $getSN->SN_RECEIVED;
			$data['default']['PRJCODE'] 	= $getSN->PRJCODE;
			$data['default']['CUST_CODE'] 	= $getSN->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getSN->CUST_ADDRESS;
			$data['default']['SO_NUM'] 		= $getSN->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSN->SO_CODE;
			$data['default']['SO_DATE'] 	= $getSN->SO_DATE;
			$data['default']['SN_TOTVOLM'] 	= $getSN->SN_TOTVOLM;
			$data['default']['SN_TOTCOST'] 	= $getSN->SN_TOTCOST;
			$data['default']['SN_TOTPPN'] 	= $getSN->SN_TOTPPN;
			$data['default']['SN_DRIVER'] 	= $getSN->SN_DRIVER;
			$data['default']['VEH_CODE'] 	= $getSN->VEH_CODE;
			$data['default']['VEH_NOPOL'] 	= $getSN->VEH_NOPOL;
			$data['default']['SN_RECEIVER'] = $getSN->SN_RECEIVER;
			$data['default']['SN_NOTES'] 	= $getSN->SN_NOTES;
			$data['default']['SN_NOTES1']	= $getSN->SN_NOTES1;
			$data['default']['SN_STAT'] 	= $getSN->SN_STAT;
			$data['default']['SN_REFNO']	= $getSN->SN_REFNO;
			$data['default']['Patt_Year'] 	= $getSN->Patt_Year;
			$data['default']['Patt_Month'] 	= $getSN->Patt_Month;
			$data['default']['Patt_Date'] 	= $getSN->Patt_Date;
			$data['default']['Patt_Number'] = $getSN->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSN->SN_NUM;
				$MenuCode 		= 'MN042';
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
			
			$this->load->view('v_sales/v_shipment/v_inb_shipment_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$comp_init 		= $this->session->userdata('comp_init');
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$SN_APPROVED 	= date('Y-m-d H:i:s');
			
			$SN_NUM 		= $this->input->post('SN_NUM');
			$SN_CODE 		= $this->input->post('SN_CODE');
			$SN_DATE		= date('Y-m-d',strtotime($this->input->post('SN_DATE')));
			$SN_TYPE 		= $this->input->post('SN_TYPE');
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SN_NOTES 		= addslashes($this->input->post('SN_NOTES'));
			$SN_NOTES1 		= addslashes($this->input->post('SN_NOTES1'));
			if($SN_NOTES1 == '')
				$SN_NOTES1	= $SN_NOTES;

			$SN_STAT		= $this->input->post('SN_STAT');
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			
			$AH_CODE		= $SN_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('SN_NOTES1'));
			$AH_ISLAST		= $this->input->post('IS_LAST');

			if($SN_STAT == 3)
			{
				// DEFAULT STATUS
					$updSN 			= array('SN_STAT'	=> 7,
											'SN_NOTES1'	=> $SN_NOTES1);
					$this->m_shipment->updateSNInb($SN_NUM, $updSN);

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

				if($AH_ISLAST == 1)
				{
					$updSN 		= array('SN_STAT'	=> $SN_STAT);
					$this->m_shipment->updateSNInb($SN_NUM, $updSN);
					
					$this->m_shipment->updateSNDet($SN_NUM, $PRJCODE);

					// GET WH PROD
						$WH_NUM		= '';
						$WH_CODE	= '';
						$sqlWHP 	= "SELECT WH_NUM, WH_CODE FROM tbl_warehouse WHERE ISWHPROD = 1 LIMIT 1";
						$resWHP 	= $this->db->query($sqlWHP)->result();
						foreach($resWHP as $rowWHP) :
							$WH_NUM 	= $rowWHP->WH_NUM;
							$WH_CODE	= $rowWHP->WH_CODE;
						endforeach;

					// START : JOURNAL HEADER
						$this->load->model('m_journal/m_journal', '', TRUE);
						
						$JournalH_Code	= $SN_NUM;
						$JournalType	= 'SN';
						$JournalH_Date	= $SN_DATE;
						$Company_ID		= $comp_init;
						$DOCSource		= $SN_NUM;
						$LastUpdate		= date('Y-m-d H:i:s');
						$WH_CODE		= $WH_CODE;
						$Refer_Number	= '';
						$RefType		= 'SN';
						$PRJCODE		= $PRJCODE;
						
						$parameters = array('JournalH_Code' 	=> $JournalH_Code,
											'JournalType'		=> $JournalType,
											'JournalH_Desc'		=> $SN_NOTES1,
											'JournalH_Date' 	=> $JournalH_Date,
											'Company_ID' 		=> $Company_ID,
											'Source'			=> $DOCSource,
											'Emp_ID'			=> $DefEmp_ID,
											'LastUpdate'		=> $LastUpdate,	
											'KursAmount_tobase'	=> 1,
											'WHCODE'			=> $WH_CODE,
											'Reference_Number'	=> $Refer_Number,
											'Manual_No'			=> $SN_CODE,
											'RefType'			=> $RefType,
											'PRJCODE'			=> $PRJCODE);
						$this->m_journal->createJournalH($JournalH_Code, $parameters); // OK
					// END : JOURNAL HEADER

					$sqlGetSN	= "SELECT A.SN_NUM, A.SN_CODE, A.SN_DATE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.SO_VOLM, A.SN_VOLM, A.SN_PRICE,
										A.SN_DISP, A.SN_DISC, A.SN_TOTAL, A.SN_DESC, A.SN_DESC1, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1,
										A.TAXPRICE2, B.ITM_GROUP, A.JOBCODEID
									FROM tbl_sn_detail A
										INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
											AND B.PRJCODE = '$PRJCODE'
									WHERE A.SN_NUM = '$SN_NUM' AND A.PRJCODE = '$PRJCODE'";
					$resGetSN	= $this->db->query($sqlGetSN)->result();
					foreach($resGetSN as $rowSN) :
						$SN_NUM 		= $rowSN->SN_NUM;
						$SN_CODE 		= $rowSN->SN_CODE;
						$JOBCODEID 		= $rowSN->JOBCODEID;
						$ITM_CODE 		= $rowSN->ITM_CODE;
						$ITM_UNIT 		= $rowSN->ITM_UNIT;
						$ITM_GROUP 		= $rowSN->ITM_GROUP;
						$SN_VOLM		= $rowSN->SN_VOLM;
						$SN_PRICE		= $rowSN->SN_PRICE;
						$SN_DISC		= $rowSN->SN_DISC;
						$TAXPRICE1		= $rowSN->TAXPRICE1;
						$TAXPRICE2		= $rowSN->TAXPRICE2;
						$SN_TOTAL		= $rowSN->SN_TOTAL;
						$SN_DESC		= $rowSN->SN_DESC;

						// CHEK ITM TYPE
							$ITM_TYPE 	= $this->m_updash->getItmType($PRJCODE, $ITM_CODE);
						
						// START : JOURNAL DETAIL
							$this->load->model('m_journal/m_journal', '', TRUE);
							
							$ITM_CODE 		= $ITM_CODE;
							$JOBCODEID 		= $JOBCODEID;
							$ACC_ID 		= '';
							$ITM_UNIT 		= $ITM_UNIT;
							$ITM_GROUP 		= $ITM_GROUP;
							$ITM_TYPE 		= $ITM_TYPE;
							$ITM_QTY 		= $SN_VOLM;
							$ITM_PRICE 		= $SN_PRICE;
							$Notes 			= $SN_DESC;
							$ITM_DISC 		= $SN_DISC;
							$TAXCODE1 		= $TAXPRICE1;
							$TAXPRICE1 		= $TAXPRICE1;
							
							$JournalH_Code	= $SN_NUM;
							$JournalType	= 'SN';
							$JournalH_Date	= $SN_DATE;
							$Company_ID		= $comp_init;
							$Currency_ID	= 'IDR';
							$LastUpdate		= $LastUpdate;
							$WH_CODE		= $WH_CODE;
							$Refer_Number	= '$SO_CODE';
							$RefType		= 'SN';
							$JSource		= 'SN';
							$PRJCODE		= $PRJCODE;
								
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
												'TRANS_CATEG' 		=> 'SN',			// SN = Shipment Notes
												'ITM_CODE' 			=> $ITM_CODE,
												'ACC_ID' 			=> $ACC_ID,
												'ITM_UNIT' 			=> $ITM_UNIT,
												'ITM_GROUP' 		=> $ITM_GROUP,
												'ITM_TYPE' 			=> $ITM_TYPE,
												'ITM_QTY' 			=> $ITM_QTY,
												'ITM_PRICE' 		=> $ITM_PRICE,
												'ITM_DISC' 			=> $ITM_DISC,
												'TAXCODE1' 			=> $TAXCODE1,
												'TAXPRICE1' 		=> $TAXPRICE1,
												'Notes'				=> $Notes,
												'JOBCODEID'			=> $JOBCODEID);
							$this->m_journal->createJournalD($JournalH_Code, $parameters);
						// END : JOURNAL DETAIL
					endforeach;

					if($SN_TYPE == 1)
					{
						// UPDATE QRC STATUS
							$this->m_shipment->updQRCSTAT($SN_NUM);
					}

					// UPDATE SO STATUS
						$this->m_shipment->updSOSTAT($SO_NUM);
				}
			}
			elseif($SN_STAT == 4)
			{
				// START : DELETE HISTORY
					$this->m_updash->delAppHist($SN_NUM);
				// END : DELETE HISTORY

				$updSN 		= array('SN_STAT'	=> $SN_STAT,
									'SN_MEMO'	=> $SN_NOTES1);
				$this->m_shipment->updateSNInb($SN_NUM, $updSN);
			}
			elseif($SN_STAT == 5 || $SN_STAT == 6)
			{
				$updSN 		= array('SN_STAT'	=> $SN_STAT,
									'SN_MEMO'	=> $SN_NOTES1);
				$this->m_shipment->updateSNInb($SN_NUM, $updSN);
			}

			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = SO_STAT
				$parameters 	= array('DOC_CODE' 		=> $SN_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SN",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_sn_header",	// TABLE NAME
										'KEY_NAME'		=> "SN_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SN_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SN_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SN",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SN_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SN_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SN_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SN_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SN_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SN_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
				
				$parameters 	= array('PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_so_header",	// TABLE NAME
										'KEY_NAME'		=> "SO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SO_STAT",		// NAMA FIELD STATUS
										'FIELD_NM_ALL'	=> "TOT_SO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_transac
										'FIELD_NM_N'	=> "TOT_SO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_C'	=> "TOT_SO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_A'	=> "TOT_SO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_R'	=> "TOT_SO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_RJ'	=> "TOT_SO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_transac
										'FIELD_NM_CL'	=> "TOT_SO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_transac
				$this->m_updash->updateDashStatDoc($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SN_NUM",
										'DOC_CODE' 		=> $SN_NUM,
										'DOC_STAT' 		=> $SN_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_sn_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SN_NUM;
				$MenuCode 		= 'MN042';
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

				$parameters 	= array('TR_TYPE'		=> "SN",
										'TR_DATE' 		=> $SN_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			$url			= site_url('c_sales/c_sh1pn0t35/gl5h1pm3nt_1n2/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdoc() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$SN_NUM		= $_GET['id'];
		$SN_NUM		= $this->url_encryption_helper->decode_url($SN_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 		= $appName;
			
			$getSN 				= $this->m_shipment->get_sn_by_number($SN_NUM)->row();
			
			$data['SN_NUM'] 	= $getSN->SN_NUM;
			$data['SN_DATE'] 	= $getSN->SN_DATE;
			$data['PRJCODE'] 	= $getSN->PRJCODE;
			
			$this->load->view('v_sales/v_shipment/v_shipment_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llQRC() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_production/m_bom', '', TRUE);
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;

			$COLLDATA	= $_GET['id'];
			$COLLDATA	= $this->url_encryption_helper->decode_url($COLLDATA);
			$EXTRACTCOL	= explode("~", $COLLDATA);
			$PRJCODE	= $EXTRACTCOL[0];
			$SO_NUM		= $EXTRACTCOL[1];
			
			$data['title'] 			= $appName;
			$data['PRJCODE'] 		= $PRJCODE;
			$data['SONUM'] 			= $SO_NUM;
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar QRC";
			}
			else
			{
				$data["h2_title"] 	= "QRC List";
			}
			
			$this->load->view('v_sales/v_shipment/v_shipment_selectqr', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function add_processINV() // G
	{
		$this->load->model('m_sales/m_shipment', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$comp_init 		= $this->session->userdata('comp_init');
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

		date_default_timezone_set("Asia/Jakarta");

		$CollID		= $_GET['id'];
		$CollID		= $this->url_encryption_helper->decode_url($CollID);
		
		$splitCode 	= explode("~", $CollID);
		$PRJCODE	= $splitCode[0];
		$SN_NUM		= $splitCode[1];
		$SN_CODE 	= $splitCode[2];

		$SINV_CREATED 	= date('Y-m-d H:i:s');

		$sqlInv			= "tbl_sinv_detail A INNER JOIN tbl_sinv_header B ON A.SINV_NUM = B.SINV_NUM WHERE A.SN_NUM = '$SN_NUM'
								AND A.PRJCODE = '$PRJCODE'
								AND B.SINV_STAT != '9'";
		$resInv 		= $this->db->count_all($sqlInv);

		if($resInv == 0)
		{
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN415';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;

				$TRXTIME1		= date('ymdHis');
				$SINV_NUM		= "$Pattern_Code$PRJCODE-$TRXTIME1";

				$sqlSINVC 		= "tbl_sinv_header WHERE PRJCODE = '$PRJCODE'";
				$resSINVC 		= $this->db->count_all($sqlSINVC);
				$maxNo			= $resSINVC + 1;

				$PattLength 	= 5;
				$len 			= strlen($maxNo);
				
				if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else if($len==5) $nol="";
				$lastPattNum 	= $nol.$len;
				//$newPattNum	= $Pattern_Code-$SN_CODE.$lastPattNum;
				$newPattNum		= $Pattern_Code."-".$SN_CODE;
				$SINV_CODE		= $newPattNum;
			// END - PEMBENTUKAN GENERATE CODE
			
			// START
				$CUST_TOPD		= 0;
				$CUST_CODE 		= '';
				$CUST_DESC 		= '';
				$CUST_ADDRESS 	= '';
				$CUST_TOPD 		= 0;
				$SN_TOTVOLM 	= 0;
				$SN_TOTCOST 	= 0;
				$SN_TOTDISC 	= 0;
				$SN_TOTPPN 		= 0;
				$SN_TOTPPH 		= 0;
				$SO_NUM 		= '';
				$SO_CODE 		= '';
				$sqlSOH 		= "SELECT A.CUST_CODE, A.CUST_DESC, A.CUST_ADDRESS, A.SO_NUM, A.SO_CODE,
										A.SN_TOTVOLM, A.SN_TOTCOST, A.SN_TOTDISC, A.SN_TOTPPN, A.SN_TOTPPH,
										B.CUST_TOPD
									FROM tbl_sn_header A INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
									WHERE A.SN_NUM = '$SN_NUM' LIMIT 1";
				$resSOH 		= $this->db->query($sqlSOH)->result();
				foreach($resSOH as $rowSOH) :
					$CUST_CODE 		= $rowSOH->CUST_CODE;
					$CUST_DESC 		= $rowSOH->CUST_DESC;
					$CUST_ADDRESS 	= $rowSOH->CUST_ADDRESS;
					$CUST_TOPD 		= $rowSOH->CUST_TOPD;
					$SN_TOTVOLM 	= $rowSOH->SN_TOTVOLM;
					$SN_TOTCOST 	= $rowSOH->SN_TOTCOST;
					$SN_TOTDISC 	= $rowSOH->SN_TOTDISC;
					$SN_TOTPPN 		= $rowSOH->SN_TOTPPN;
					$SN_TOTPPH 		= $rowSOH->SN_TOTPPH;
					$SO_NUM 		= $rowSOH->SO_NUM;
					$SO_CODE 		= $rowSOH->SO_CODE;
				endforeach;

				$ADDDAY				= "+$CUST_TOPD days";
				$SINV_TYPE			= 1;
				$SO_NUM 			= $SO_NUM;
				$SINV_DATE			= date('Y-m-d');
				$SINV_DUEDATE		= date('Y-m-d', strtotime(''.$ADDDAY.'', strtotime($SINV_DATE)));
				$CUST_CODE 			= $CUST_CODE;
				$SINV_ADDRESS 		= $CUST_ADDRESS;
				$SINV_CURRENCY 		= 'IDR';
				$SINV_TAXCURR 		= 1;
				$DP_NUM 			= 0;
				$DP_AMOUNT 			= 0;
				$SINV_AMOUNT 		= $SN_TOTCOST;
				$SINV_AMOUNT_DISC	= $SN_TOTDISC;
				$SINV_AMOUNT_PPN	= $SN_TOTPPN;
				$SINV_LISTTAX 		= '';
				if($SN_TOTPPN > 0)
					$SINV_LISTTAX 	= 'TAX01';

				$SINV_AMOUNT_PPH	= $SN_TOTPPH;
				$SINV_PPH 			= '';
				if($SN_TOTPPH > 0)
					$SINV_PPH 		= 'TAX02';

				$SINV_LISTTAXVAL 	= 0;
				if($SN_TOTPPN > 0 && $SN_TOTPPH > 0)
				{
					$SINV_LISTTAX 	= 'TAX01;TAX02';
					$SINV_LISTTAXVAL= $SN_TOTPPN.';'.$SN_TOTPPH;
				}

				$SINV_AMOUNT_BASE 	= $SN_TOTCOST;
				$SINV_AMOUNT_FINAL	= $SINV_AMOUNT - $SINV_AMOUNT_DISC + $SINV_AMOUNT_PPN - $SINV_AMOUNT_PPH;
				$SINV_AMOUNT_PAID 	= 0;
				$SINV_TERM 			= $CUST_TOPD;
				$SINV_PPNNUM 		= '';
				$SINV_PPHNUM 		= '';
				$SINV_LISTTAX 		= $SINV_LISTTAX;
				$SINV_LISTTAXVAL 	= $SINV_LISTTAXVAL;
				$SINV_PPH 			= $SINV_PPH;
				$SINV_PPHVAL 		= $SN_TOTPPH;
				$SINV_TOTAM 		= $SN_TOTCOST;
				$SINV_STAT 			= 1;
				$SINV_PAYSTAT 		= 'NR';
				$COMPANY_ID 		= $comp_init;
				$SINV_NOTES 		= '';
				$SINV_NOTES2 		= '';
				$REF_NOTES 			= $SN_NUM;
				$CREATER 			= $DefEmp_ID;
				$UPDATED 			= date('Y-m-d H:i:s');
				$UPDATER 			= '';
				$Patt_Year 			= date('Y');
				$Patt_Number 		= $maxNo;
				
				// ADD DETAIL
					$GTOTAL_SNDISC	= 0;
					$GTOTAL_SNPPN	= 0;
					$GTOTAL_SNPPH	= 0;
					$GTOTAL_SN		= 0;
					$TOTAL_SN 		= 0;
					$TOTAL_SN_NET	= 0;
					$PRMTOTAL		= 0;
					$sqlSND 		= "SELECT SN_NUM, SN_CODE, SN_DATE, PRJCODE, CUST_CODE, ITM_CODE, ITM_UNIT, SO_VOLM, SN_VOLM,
											SN_PRICE, SO_PRICE, SN_DISP, SN_DISC,
											SN_TOTAL, SN_DESC, SN_DESC1, TAXCODE1, TAXCODE2, TAXPRICE1, TAXPRICE2, SR_VOLM, SR_PRICE, SR_TOTAL, ISCLOSE
										FROM tbl_sn_detail A
										WHERE SN_NUM = '$SN_NUM'";
					$resSND 		= $this->db->query($sqlSND)->result();
					foreach($resSND as $rowSND) :
						$d['SINV_NUM']			= $SINV_NUM;
						$d['SINV_CODE']			= $SINV_CODE;
						$d['SN_NUM']			= $rowSND->SN_NUM;
						$d['REF_NUM']			= $SO_NUM;
						$d['REF_CODE']			= $SO_CODE;
						$d['PRJCODE']			= $PRJCODE;
						$d['ITM_CODE']			= $rowSND->ITM_CODE;
						$ITMCODE 				= $rowSND->ITM_CODE; // ITEM CODE FG

						// ADD DETAIL QRC
							$QRC_SOPRC 	= 0;
							$TOTITM_QTY	= 0;
							$sqlQRC 	= "SELECT SN_NUM, SN_CODE, SN_DATE, CUST_CODE, SO_NUM, SO_CODE, JO_NUM, JO_CODE, 
												ITM_CODEFG, ITM_CODE, ITM_UNIT,
												QRC_NUM, QRC_CODEV, QRC_VOLM, QRC_PRICE, QRC_SOPRC, QRC_NOTE
											FROM tbl_sn_detail_qrc A
											WHERE SN_NUM = '$SN_NUM' AND ITM_CODEFG = '$ITMCODE' AND QRC_ISRET = 0";
							$resQRC 	= $this->db->query($sqlQRC)->result();
							foreach($resQRC as $rowQRC) :
								$dQRC['SINV_NUM']		= $SINV_NUM;
								$dQRC['SINV_CODE']		= $SINV_CODE;
								$dQRC['SINV_DATE']		= $SINV_DATE;
								$dQRC['SINV_CODE']		= $SINV_CODE;
								$dQRC['SN_NUM']			= $rowQRC->SN_NUM;
								$dQRC['SN_CODE']		= $rowQRC->SN_CODE;
								$dQRC['SN_DATE']		= $rowQRC->SN_DATE;
								$dQRC['PRJCODE']		= $PRJCODE;
								$dQRC['CUST_CODE']		= $rowQRC->CUST_CODE;
								$dQRC['SO_NUM']			= $rowQRC->SO_NUM;
								$dQRC['SO_CODE']		= $rowQRC->SO_CODE;
								$dQRC['JO_NUM']			= $rowQRC->JO_NUM;
								$dQRC['JO_NUM']			= $rowQRC->JO_NUM;
								$dQRC['JO_CODE']		= $rowQRC->JO_CODE;
								$dQRC['ITM_CODEFG']		= $rowQRC->ITM_CODEFG;
								$dQRC['ITM_CODE']		= $rowQRC->ITM_CODE;
								$dQRC['ITM_UNIT']		= $rowQRC->ITM_UNIT;
								$dQRC['QRC_NUM']		= $rowQRC->QRC_NUM;
								$dQRC['QRC_CODEV']		= $rowQRC->QRC_CODEV;
								$dQRC['QRC_VOLM']		= $rowQRC->QRC_VOLM;
								$dQRC['QRC_PRICE']		= $rowQRC->QRC_PRICE;
								$dQRC['QRC_SOPRC']		= $rowQRC->QRC_SOPRC;
								$dQRC['QRC_NOTE']		= $rowQRC->QRC_NOTE;

								$QRC_VOLM 				= $rowQRC->QRC_VOLM;
								$QRC_SOPRC 				= $rowQRC->QRC_SOPRC;

								$TOTITM_QTY				= $TOTITM_QTY + $QRC_VOLM;

								$this->db->insert('tbl_sinv_detail_qrc',$dQRC);
							endforeach;

						// IF NOT FROM QRC
							$sqlQRC 	= "tbl_sn_detail_qrc WHERE SN_NUM = '$SN_NUM'";
							$resQRC 	= $this->db->count_all($sqlQRC);
							if($resQRC == 0)
							{
								$d['ITM_QTY']	= $rowSND->SN_VOLM;
								$d['ITM_QTY2']	= $rowSND->SN_VOLM;
								$QRC_VOLM 		= $rowSND->SN_VOLM;
								$QRC_SOPRC 		= $rowSND->SO_PRICE;
								$TOTITM_QTY		= $TOTITM_QTY + $QRC_VOLM;
							}
							else
							{
								$d['ITM_QTY']	= $TOTITM_QTY;
								$d['ITM_QTY2']	= $TOTITM_QTY;
							}

						$d['ITM_UNIT']			= $rowSND->ITM_UNIT;
						$d['ITM_CONV']			= 1;
						$d['ITM_UNITP']			= $QRC_SOPRC;
						$d['ITM_UNITP_BASE']	= $QRC_SOPRC;
						$TOTAL_AMN				= $TOTITM_QTY * $QRC_SOPRC;		// TOTAL SO
						$d['ITM_DISP']			= $rowSND->SN_DISP;
						$ITM_DISP 				= $rowSND->SN_DISP;
						$ITM_DISC				= $ITM_DISP * $TOTAL_AMN / 100;	// DIKALI ULANG KARENA FAKTU BERDASARKAN SN
						$d['ITM_DISC']			= $ITM_DISC;
						$SUBTOTAL_SN			= $TOTAL_AMN - $ITM_DISC;		// TOTAL SETELAH DIKURANGI POTONGAN

						$d['TAXCODE1']			= $rowSND->TAXCODE1;
						$TAXCODE1				= $rowSND->TAXCODE1;

						$TAX_AMOUNT_PPn1 		= 0;
						$TAX_AMOUNT_PPh1 		= 0;
						$TAX_AMOUNT_PPn2 		= 0;
						$TAX_AMOUNT_PPh2 		= 0;

						$TAXLA_PERC 	= 0;
	                    $sPPN			= "SELECT TAXLA_PERC FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
                        $rPPN			= $this->db->query($sPPN)->result();
                        foreach($rPPN as $rwPPN) :
                            $TAXLA_PERC	= $rwPPN->TAXLA_PERC;
                        endforeach;
	                    if($TAXLA_PERC == 0 || $TAXLA_PERC == '')
	                    	$TAXLA_PERC = 0;
	                    
						$d['TAX_AMOUNT_PPn1']	= $TAXLA_PERC * $SUBTOTAL_SN / 100;
						$d['TAX_AMOUNT_PPh1']	= 0;

						$TAX_AMOUNT_PPn1 		= $TAXLA_PERC * $SUBTOTAL_SN / 100;
						$TAX_AMOUNT_PPh1 		= 0;

						/*if($TAXCODE1 == 'TAX01')
						{
							$d['TAX_AMOUNT_PPn1']	= 0.1 * $SUBTOTAL_SN;
							$d['TAX_AMOUNT_PPh1']	= 0;

							$TAX_AMOUNT_PPn1 		= 0.1 * $SUBTOTAL_SN;
							$TAX_AMOUNT_PPh1 		= 0;
						}
						elseif($TAXCODE1 == 'TAX02')
						{
							$d['TAX_AMOUNT_PPn1']	= 0;
							$d['TAX_AMOUNT_PPh1']	= 0.03 * $SUBTOTAL_SN;

							$TAX_AMOUNT_PPn1 		= 0;
							$TAX_AMOUNT_PPh1 		= 0.03 * $SUBTOTAL_SN;
						}*/

						$d['TAXCODE2']			= $rowSND->TAXCODE2;
						$TAXCODE2				= $rowSND->TAXCODE2;

						$d['TAX_AMOUNT_PPn2']	= 0;
						$d['TAX_AMOUNT_PPh2']	= 0;
						$TAX_AMOUNT_PPn2 		= 0;
						$TAX_AMOUNT_PPh2 		= 0;

						/*if($TAXCODE2 == 'TAX01')
						{
							$d['TAX_AMOUNT_PPn2']	= 0.1 * $SUBTOTAL_SN;
							$d['TAX_AMOUNT_PPh2']	= 0;

							$TAX_AMOUNT_PPn2 		= 0.1 * $SUBTOTAL_SN;
							$TAX_AMOUNT_PPh2 		= 0;
						}
						elseif($TAXCODE2 == 'TAX02')
						{
							$d['TAX_AMOUNT_PPn2']	= 0;
							$d['TAX_AMOUNT_PPh2']	= 0.03 * $SUBTOTAL_SN;

							$TAX_AMOUNT_PPn2 		= 0;
							$TAX_AMOUNT_PPh2 		= 0.03 * $SUBTOTAL_SN;
						}*/

						$TOTAL_SN				= $TOTAL_SN + $TOTAL_AMN;		// TOTAL SN KOTOR (SEBELUM DIKURANGI DISKON DAN FAKTOR PAJAK)

						// BY DH ON 2020 08 18, TIDAK PERLU DI NETKAN, TETAP TAMPIKAN NILAI ASLI TANPA DIPOTONG ATAU DITAMBAH PPN
						//$TOTAL_SN_NET			= $TOTAL_AMN - $ITM_DISC + $TAX_AMOUNT_PPn1 + $TAX_AMOUNT_PPn1 - $TAX_AMOUNT_PPh1 - $TAX_AMOUNT_PPh2;
						$TOTAL_SN_NET			= $TOTAL_AMN - $ITM_DISC + $TAX_AMOUNT_PPn1 + $TAX_AMOUNT_PPn2 - $TAX_AMOUNT_PPh1 - $TAX_AMOUNT_PPh2;
						$d['ITM_AMOUNT']		= $TOTAL_AMN;					// TOTAL SN KOTOR (SEBELUM DIKURANGI DISKON DAN FAKTOR PAJAK)
						$d['ITM_AMOUNT_BASE']	= $TOTAL_AMN;					// TOTAL SN KOTOR (SEBELUM DIKURANGI DISKON DAN FAKTOR PAJAK)
						$d['ITM_AMOUNT_FINAL']	= $TOTAL_SN_NET;

						$GTOTAL_SNDISC			= $GTOTAL_SNDISC + $ITM_DISC;
						$GTOTAL_SNPPN			= $GTOTAL_SNPPN + $TAX_AMOUNT_PPn1 + $TAX_AMOUNT_PPn2;
						$GTOTAL_SNPPH			= $GTOTAL_SNPPH + $TAX_AMOUNT_PPh1 + $TAX_AMOUNT_PPh2;
						$GTOTAL_SN				= $GTOTAL_SN + $TOTAL_SN_NET;	// TOTAL BERSIH SETELAH DIKURANGI DISKON DAN DIPENGARUHI FAKTOR PAJAK

						$this->db->insert('tbl_sinv_detail',$d);
					endforeach;

				// ADD HEADER
					$inSINVH 	= array('SINV_NUM' 			=> $SINV_NUM,
										'SINV_CODE' 		=> $SINV_CODE,
										'SINV_TYPE'			=> $SINV_TYPE,
										'SINV_CATEG'		=> 'SO',
										'SO_NUM'			=> $SO_NUM,
										'SO_CODE'			=> $SO_CODE,
										'PRJCODE'			=> $PRJCODE,
										'SINV_DATE'			=> $SINV_DATE,
										'SINV_DUEDATE'		=> $SINV_DUEDATE,
										'CUST_CODE'			=> $CUST_CODE,
										'CUST_DESC'			=> $CUST_DESC,
										'SINV_ADDRESS'		=> $SINV_ADDRESS,
										'SINV_CURRENCY'		=> $SINV_CURRENCY,
										'SINV_TAXCURR'		=> $SINV_TAXCURR,
										'SINV_AMOUNT'		=> $TOTAL_SN,
										'SINV_AMOUNT_DISC'	=> $GTOTAL_SNDISC,
										'SINV_AMOUNT_PPN'	=> $GTOTAL_SNPPN,
										'SINV_AMOUNT_PPH'	=> $GTOTAL_SNPPH,
										'SINV_AMOUNT_BASE'	=> $TOTAL_AMN,
										'SINV_AMOUNT_FINAL'	=> $GTOTAL_SN,
										'SINV_AMOUNT_PAID'	=> $SINV_AMOUNT_PAID,
										'SINV_TERM'			=> $SINV_TERM,
										'SINV_PPNNUM'		=> $SINV_PPNNUM,
										'SINV_PPHNUM'		=> $SINV_PPHNUM,
										'SINV_LISTTAX'		=> $SINV_LISTTAX,
										'SINV_LISTTAXVAL'	=> $GTOTAL_SNPPN,
										'SINV_PPH'			=> $SINV_PPH,
										'SINV_PPHVAL'		=> $GTOTAL_SNPPH,
										'SINV_TOTAM'		=> $GTOTAL_SN,
										'SINV_STAT'			=> $SINV_STAT,
										'SINV_PAYSTAT'		=> $SINV_PAYSTAT,
										'COMPANY_ID'		=> $COMPANY_ID,
										'REF_NOTES'			=> $REF_NOTES,
										'CREATED'			=> $SINV_CREATED,
										'CREATER'			=> $CREATER,
										'Patt_Year'			=> $Patt_Year,
										'Patt_Number'		=> $Patt_Number);
					$this->m_shipment->addSINVH($inSINVH);

				// UPDATE JO HEADER
					$updSNH = "UPDATE tbl_sn_header SET SINV_CREATED = 1, SINV_NUM = '$SINV_NUM', SINV_CODE = '$SINV_CODE' WHERE SN_NUM = '$SN_NUM'";
					$this->db->query($updSNH);
					
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= 2;											// IF "ADD" CONDITION ALWAYS = SINV_STAT
					$parameters 	= array('DOC_CODE' 		=> $SINV_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,			// PROJECT
											'TR_TYPE'		=> "SINV",				// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_sinv_header",	// TABLE NAME
											'KEY_NAME'		=> "SINV_NUM",			// KEY OF THE TABLE
											'STAT_NAME' 	=> "SINV_STAT",			// NAMA FIELD STATUS
											'STATDOC' 		=> $SINV_STAT,			// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,		// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_SINV",			// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_SINV_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_SINV_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_SINV_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_SINV_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_SINV_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_SINV_CL");		// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
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
					$TTR_REFDOC		= $SINV_NUM;
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
			// END
			
			$SINV_DATE 	= date('d M Y', strtotime($SINV_DATE));
			echo "No. Faktur Penjualan sudah dibuat : $SINV_CODE ( $SINV_DATE )";
		}
		else
		{
			$sqlInv			= "SELECT A.SINV_CODE, B.SINV_DATE FROM tbl_sinv_detail A
									INNER JOIN tbl_sinv_header B ON A.SINV_NUM = B.SINV_NUM
								 WHERE A.SN_NUM = '$SN_NUM' AND B.PRJCODE = '$PRJCODE'";
			$resInv 		= $this->db->query($sqlInv)->result();
			foreach($resInv as $rowInv):
				$SINV_CODE	= $rowInv->SINV_CODE;
				$SINV_DATE	= $rowInv->SINV_DATE;
				$SINV_DATE 	= date('d M Y', strtotime($SINV_DATE));
			endforeach;
			echo "No. Faktur Penjualan : $SINV_CODE ( $SINV_DATE )";
		}
	}
}