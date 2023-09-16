<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= C_s4l350r4.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_s4l350r4 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_sales/m_so', '', TRUE);
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
	
 	function index() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_s4l350r4/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
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
				$mnCode				= 'MN039';
				$data["MenuApp"] 	= 'MN040';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN039';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_s4l350r4/g4Ll50/?id=";
			
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

	function g4Ll50() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN039';
			$data["MenuCode"] 	= 'MN039';
			$data["MenuApp"] 	= 'MN040';
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
				$data["url_search"] = site_url('c_sales/c_s4l350r4/f4n7_5rcH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_so->count_all_SO($PRJCODE, $key);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_so->get_all_SO($PRJCODE, $start, $end, $key)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar Penjualan";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Sales Order List";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['addURL'] 	= site_url('c_sales/c_s4l350r4/a44_s0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_s4l350r4/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN039';
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
			
			$this->load->view('v_sales/v_so/v_so', $data);
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
			$url			= site_url('c_sales/c_s4l350r4/g4Ll50/?id='.$this->url_encryption_helper->encode_url($collDATA));
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
			
			$columns_valid 	= array("SO_NUM",
									"SO_CODE", 
									"SO_DATE", 
									"CUST_CODE", 
									"OFF_CODE",
									"SO_NOTES", 
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
			$num_rows 		= $this->m_so->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_so->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SO_NUM 		= $dataI['SO_NUM'];
                $SO_CODE 		= $dataI['SO_CODE'];
                $SO_TYPE 		= $dataI['SO_TYPE'];
                $SO_CAT 		= $dataI['SO_CAT'];
                $SO_DATE 		= $dataI['SO_DATE'];
				$SO_DATEV		= date('d M Y', strtotime($SO_DATE));
                $PRJCODE 		= $dataI['PRJCODE'];
                $CUST_CODE 		= $dataI['CUST_CODE'];
                $CUST_DESC 		= $dataI['CUST_DESC'];
                $OFF_NUM 		= $dataI['OFF_NUM'];
                $SO_TOTCOST		= $dataI['SO_TOTCOST'];
				$SO_CREATER		= $dataI['SO_CREATER'];
                $SO_TERM 		= $dataI['SO_TERM'];
                $SO_PAYTYPE 	= $dataI['SO_PAYTYPE'];
                $SO_STAT 		= $dataI['SO_STAT'];
                $SO_INVSTAT		= $dataI['SO_INVSTAT'];
                $ISDIRECT		= $dataI['ISDIRECT'];
                $SO_NOTES		= $dataI['SO_NOTES'];
                $SO_MEMO		= $dataI['SO_MEMO'];
                $SO_PRODD		= $dataI['SO_PRODD'];
				$SO_PRODD		= date('d M Y', strtotime($SO_PRODD));
                $SO_REFRENS		= $dataI['SO_REFRENS'];
                $SO_CREATER		= $dataI['SO_CREATER'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);

				
				$CollID			= "$SO_NUM~$PRJCODE";
				$secUpd			= site_url('c_sales/c_s4l350r4/u775o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_sales/c_s4l350r4/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($SO_NUM));

				$secDel_DOC 	= base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
				$secDelIcut 	= base_url().'index.php/__l1y/trashDOC/?id=';
				$delID 			= "$secDelIcut~tbl_so_header~tbl_so_detail~SO_NUM~$SO_NUM~PRJCODE~$PRJCODE";
				$secVoid 		= base_url().'index.php/__l1y/trashSO/?id=';
				$voidID 		= "$secVoid~tbl_so_header~tbl_so_detail~SO_NUM~$SO_NUM~PRJCODE~$PRJCODE";

				// CEK JO
					$sqlJOC		= "tbl_jo_header WHERE PRJCODE = '$PRJCODE' AND SO_NUM = '$SO_NUM' AND JO_STAT NOT IN (5,9)";
					$resJOC 	= $this->db->count_all($sqlJOC);

					if($resJOC > 0)
					{
						$disCl 	= "voidDOCX";
						$disV 	= "disabled='disabled'";
					}
					else
					{
						$disCl 	= "voidDOC";
						$disV 	= "";
					}
                                    
				if($SO_STAT == 1 || $SO_STAT == 4) 
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
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				elseif($SO_STAT == 3)
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
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='".$disCl."(".$noU.")' title='Void' ".$disV.">
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
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='voidDOCX(".$noU.")' title='Void' disabled='disabled'>
										<i class='glyphicon glyphicon-off'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['SO_CODE']."</div>",
										  "<div style='white-space:nowrap; text-align: center'>".$SO_DATEV."</div>",
										  $CUST_DESC,
										  "<div style='white-space:nowrap; text-align: right'>".number_format($SO_TOTCOST, 2)."</div>",
										 "<div style='white-space:nowrap; text-align: center'>". $SO_PRODD."</div>",
										  $SO_REFRENS,
										  "<div style='white-space:nowrap; text-align: center'>".$empName."</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px; text-align: center'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44_s0() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_so', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN039';
			$data["MenuApp"] 	= 'MN040';
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
			$data['tblName'] 		= "tbl_so_header";
			$data['task']			= 'add';	
			$task					= 'add';	
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Penjualan";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Sales Order";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_sales/c_s4l350r4/add_process');
			$cancelURL				= site_url('c_sales/c_s4l350r4/g4Ll50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$CUST_CODE				= '';
			$data['countCUST']		= $this->m_so->count_all_CUST($PRJCODE, $CUST_CODE);
			$data['vwCUST'] 		= $this->m_so->get_all_CUST($PRJCODE, $CUST_CODE)->result();
			
			$MenuCode 				= 'MN039';
			$data["MenuCode"] 		= 'MN039';
			$data["MenuCode1"] 		= 'MN040';
			$data['viewDocPattern'] = $this->m_so->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN039';
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
	
			$this->load->view('v_sales/v_so/v_so_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function all10ff3r() // OK
	{
		$this->load->model('m_inventory/m_itemreceipt/m_itemreceipt', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CST		= $_GET['CST'];
			$PRJCODE1	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE1);
			$EmpID 		= $this->session->userdata('Emp_ID');
			
			$data['title'] 		= $appName;
			$data['PRJCODE']	= $PRJCODE;
			$data['CUST_CODE']	= $CST;
					
			$this->load->view('v_sales/v_so/v_so_sel_source', $data);
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
			$this->load->model('m_sales/m_so', '', TRUE);
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
			
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllItem'] 	= $this->m_so->count_all_itmOFF($PRJCODE);
			$data['vwAllItem'] 		= $this->m_so->get_all_itmOFF($PRJCODE)->result();
					
			$this->load->view('v_sales/v_so/v_so_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function s3l4llit3mSales() // G
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_sales/m_so', '', TRUE);
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
			
			$data['PRJCODE'] 		= $PRJCODE;			
			$data['countAllItem'] 	= $this->m_so->count_all_itmOFF($PRJCODE);
			$data['vwAllItem'] 		= $this->m_so->get_all_itmOFF($PRJCODE)->result();
					
			$this->load->view('v_sales/v_so/v_so_selitemDir', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		date_default_timezone_set("Asia/Jakarta");
		
		$SO_CREATED 	= date('Y-m-d H:i:s');
			
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			// START - PEMBENTUKAN GENERATE CODE
				$this->load->model('m_projectlist/m_projectlist', '', TRUE);
				$Pattern_Code	= "XX";
				$MenuCode 		= 'MN039';
				$vwDocPatt		= $this->m_projectlist->getDataDocPat($MenuCode)->result();
				foreach($vwDocPatt as $row) :
					$Pattern_Code = $row->Pattern_Code;
				endforeach;
				
				$PRJCODE 		= $this->input->post('PRJCODE');
				$TRXTIME1		= date('ymdHis');
				$SO_NUM			= "$Pattern_Code$PRJCODE-$TRXTIME1";
			// END - PEMBENTUKAN GENERATE CODE
			
			$SO_CODE 		= $this->input->post('SO_CODE');
			$SO_TYPE		= 1;
			//$SO_CAT		= 2;
			$SO_CAT 		= $this->input->post('SO_CAT');
			$SO_DATE		= date('Y-m-d',strtotime($this->input->post('SO_DATE')));
			$SO_PRODD		= date('Y-m-d',strtotime($this->input->post('SO_PRODD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE 		= $this->input->post('DEPCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');			
			$SO_CURR 		= $this->input->post('SO_CURR'); 	// IDR or USD
			if($SO_CURR == 'IDR')
			{
				$SO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_so->get_Rate($SO_CURR);
				$SO_CURRATE	= $getRate;
			}
			
			$OFF_NUM 		= $this->input->post('OFF_NUM');
			$OFF_CODE 		= $this->input->post('OFF_CODE');
			
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';			
			$sqlBOM 		= "SELECT BOM_NUM, BOM_CODE, CCAL_NUM, CCAL_CODE
								FROM tbl_offering_h WHERE OFF_NUM = '$OFF_NUM' AND PRJCODE  = '$PRJCODE' LIMIT 1";
			$resBOM 		= $this->db->query($sqlBOM)->result();
			foreach($resBOM as $rowBOM) :
				$CCAL_NUM 	= $rowBOM->CCAL_NUM;
				$CCAL_CODE 	= $rowBOM->CCAL_CODE;
				$BOM_NUM 	= $rowBOM->BOM_NUM;
				$BOM_CODE 	= $rowBOM->BOM_CODE;		
			endforeach;
			
			$SO_PAYTYPE 	= $this->input->post('SO_PAYTYPE'); //Cash or Credit
			$SO_TENOR 		= $this->input->post('SO_TENOR'); // Jangka Waktu Bayar
			$SO_DUED1		= strtotime ("+$SO_TENOR day", strtotime ($SO_DATE));
			$SO_DUED		= date('Y-m-d', $SO_DUED1);
			$SO_NOTES 		= addslashes($this->input->post('SO_NOTES'));
			$SO_STAT		= $this->input->post('SO_STAT'); // New, Confirm, Approve
			$SO_REFRENS		= $this->input->post('SO_REFRENS');
			$Patt_Year		= date('Y',strtotime($this->input->post('SO_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('SO_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('SO_DATE')));
			$Patt_Number	= $this->input->post('Patt_Number');
			
			$AddSO			= array('SO_NUM' 		=> $SO_NUM,
									'SO_CODE' 		=> $SO_CODE,
									'SO_TYPE' 		=> $SO_TYPE,
									'SO_CAT'		=> $SO_CAT,
									'SO_DATE'		=> $SO_DATE,
									'SO_DUED'		=> $SO_DUED,
									'SO_PRODD'		=> $SO_PRODD,
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'CUST_CODE' 	=> $CUST_CODE,
									'CUST_ADDRESS' 	=> $CUST_ADDRESS,
									'OFF_NUM' 		=> $OFF_NUM,
									'OFF_CODE' 		=> $OFF_CODE,
									'CCAL_NUM' 		=> $CCAL_NUM,
									'CCAL_CODE' 	=> $CCAL_CODE,
									'BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'SO_CURR' 		=> $SO_CURR,
									'SO_CURRATE' 	=> $SO_CURRATE,									
									'SO_PAYTYPE' 	=> $SO_PAYTYPE, 
									'SO_TENOR' 		=> $SO_TENOR,
									'SO_NOTES' 		=> $SO_NOTES,
									'SO_CREATER'	=> $DefEmp_ID,
									'SO_CREATED'	=> $SO_CREATED,
									'SO_STAT'		=> $SO_STAT,
									'SO_INVSTAT'	=> 0,
									'SO_REFRENS'	=> $SO_REFRENS,
									'Patt_Year'		=> $Patt_Year, 
									'Patt_Month' 	=> $Patt_Month,
									'Patt_Date'		=> $Patt_Date,
									'Patt_Number'	=> $Patt_Number);								
			$this->m_so->add($AddSO); // Insert tb_SO_header
			
			$SO_TOTCOST	= 0;	
			$SO_TOTPPN	= 0;	
			foreach($_POST['data'] as $d)
			{
				$d['SO_NUM']	= $SO_NUM;
				$d['PRJCODE']	= $PRJCODE;
				$d['SO_DATE']	= $SO_DATE;
				$d['OFF_NUM']	= $OFF_NUM;
				$d['OFF_CODE']	= $OFF_CODE;
				$ITMAMOUNT		= $d['SO_COST'];
				$TAXPRICE1		= $d['TAXPRICE1'];
				$SO_TOTCOST		= $SO_TOTCOST + $ITMAMOUNT;
				$SO_TOTPPN		= $SO_TOTPPN + $TAXPRICE1;
				$this->db->insert('tbl_so_detail',$d);
			}
			
			$updSOH	= array('SO_TOTCOST' => $SO_TOTCOST, 'SO_TOTPPN' => $SO_TOTPPN);
			$this->m_so->updateSOH($SO_NUM, $updSOH);
			
			// UPDATE DETAIL
				//$this->m_so->updateDet($SO_NUM, $PRJCODE, $SO_DATE);
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('SO_STAT');			// IF "ADD" CONDITION ALWAYS = SO_STAT
				$parameters 	= array('DOC_CODE' 		=> $SO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "SO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_so_header",	// TABLE NAME
										'KEY_NAME'		=> "SO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_SO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SO_NUM",
										'DOC_CODE' 		=> $SO_NUM,
										'DOC_STAT' 		=> $SO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_so_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SO_NUM;
				$MenuCode 		= 'MN039';
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

				$parameters 	= array('TR_TYPE'		=> "SO",
										'TR_DATE' 		=> $SO_DATE,
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
			
			$url			= site_url('c_sales/c_s4l350r4/g4Ll50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function u775o_p0() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_so', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN039';
			$data["MenuApp"] 	= 'MN040';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$SO_NUM		= $splitCode[0];
			$PRJCODE	= $splitCode[1];
								
			$getSO_head				= $this->m_so->get_so_by_number($SO_NUM)->row();
			$PRJCODE				= $getSO_head->PRJCODE;
			$data["MenuCode"] 		= 'MN039';
			$data["MenuCode1"] 		= 'MN040';
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			$task					= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Penjualan";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Sales Order";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_sales/c_s4l350r4/update_process');
			$cancelURL				= site_url('c_sales/c_s4l350r4/g4Ll50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN039';
			$data["MenuCode"] 		= 'MN039';
		
			$getSO 							= $this->m_so->get_so_by_number($SO_NUM)->row();
			$data['default']['SO_NUM'] 		= $getSO->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSO->SO_CODE;
			$data['default']['SO_TYPE'] 	= $getSO->SO_TYPE;
			$data['default']['SO_CAT'] 		= $getSO->SO_CAT;
			$data['default']['SO_DATE'] 	= $getSO->SO_DATE;
			$data['default']['SO_DUED'] 	= $getSO->SO_DUED;
			$data['default']['SO_PRODD'] 	= $getSO->SO_PRODD;
			$data['default']['PRJCODE'] 	= $getSO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getSO->DEPCODE;
			$data['default']['CUST_CODE'] 	= $getSO->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getSO->CUST_ADDRESS;
			$data['default']['OFF_NUM'] 	= $getSO->OFF_NUM;
			$data['default']['OFF_CODE'] 	= $getSO->OFF_CODE;
			$data['default']['SO_CURR'] 	= $getSO->SO_CURR;
			$data['default']['SO_CURRATE'] 	= $getSO->SO_CURRATE;
			$data['default']['SO_TOTCOST'] 	= $getSO->SO_TOTCOST;
			$data['default']['SO_TOTPPN'] 	= $getSO->SO_TOTPPN;
			$data['default']['SO_PAYTYPE'] 	= $getSO->SO_PAYTYPE;
			$data['default']['SO_TENOR'] 	= $getSO->SO_TENOR;
			$data['default']['SO_NOTES'] 	= $getSO->SO_NOTES;
			$data['default']['SO_NOTES1'] 	= $getSO->SO_NOTES1;
			$data['default']['SO_MEMO'] 	= $getSO->SO_MEMO;
			$data['default']['PRJNAME'] 	= $getSO->PRJNAME;
			$data['default']['SO_STAT'] 	= $getSO->SO_STAT;
			$data['default']['SO_REFRENS'] 	= $getSO->SO_REFRENS;
			$data['default']['Patt_Year'] 	= $getSO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getSO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getSO->Patt_Date;
			$data['default']['Patt_Number'] = $getSO->Patt_Number;
			
			$CUST_CODE						= $getSO->CUST_CODE;
			$data['countCUST']				= $this->m_so->count_all_CUST($PRJCODE, $CUST_CODE);
			$data['vwCUST'] 				= $this->m_so->get_all_CUST($PRJCODE, $CUST_CODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSO->SO_NUM;
				$MenuCode 		= 'MN039';
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
			
			$this->load->view('v_sales/v_so/v_so_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		
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
			
			$SO_NUM 	= $this->input->post('SO_NUM');
			$SO_CODE 		= $this->input->post('SO_CODE');
			$SO_TYPE		= 1;
			$SO_CAT			= 2;
			$SO_DATE		= date('Y-m-d',strtotime($this->input->post('SO_DATE')));
			$SO_PRODD		= date('Y-m-d',strtotime($this->input->post('SO_PRODD')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$DEPCODE 		= $this->input->post('DEPCODE');
			$CUST_CODE 		= $this->input->post('CUST_CODE');
			$CUST_ADDRESS	= $this->input->post('CUST_ADDRESS');			
			$SO_CURR 		= $this->input->post('SO_CURR'); 	// IDR or USD
			if($SO_CURR == 'IDR')
			{
				$SO_CURRATE	= 1;
			}
			else
			{
				$getRate	= $this->m_so->get_Rate($SO_CURR);
				$SO_CURRATE	= $getRate;
			}
			$OFF_NUM 		= $this->input->post('OFF_NUM');
			$OFF_CODE 		= $this->input->post('OFF_CODE');
			
			$CCAL_NUM 		= '';
			$CCAL_CODE 		= '';
			$BOM_NUM 		= '';
			$BOM_CODE 		= '';			
			$sqlBOM 		= "SELECT BOM_NUM, BOM_CODE, CCAL_NUM, CCAL_CODE
								FROM tbl_offering_h WHERE OFF_NUM = '$OFF_NUM' AND PRJCODE  = '$PRJCODE' LIMIT 1";
			$resBOM 		= $this->db->query($sqlBOM)->result();
			foreach($resBOM as $rowBOM) :
				$CCAL_NUM 	= $rowBOM->CCAL_NUM;
				$CCAL_CODE 	= $rowBOM->CCAL_CODE;
				$BOM_NUM 	= $rowBOM->BOM_NUM;
				$BOM_CODE 	= $rowBOM->BOM_CODE;		
			endforeach;
			
			$SO_PAYTYPE 	= $this->input->post('SO_PAYTYPE'); //Cash or Credit
			$SO_TENOR 		= $this->input->post('SO_TENOR'); // Jangka Waktu Bayar
			$SO_DUED1		= strtotime ("+$SO_TENOR day", strtotime ($SO_DATE));
			$SO_DUED		= date('Y-m-d', $SO_DUED1);
			$SO_NOTES 		= addslashes($this->input->post('SO_NOTES'));
			$SO_STAT		= $this->input->post('SO_STAT'); // New, Confirm, Approve
			$SO_REFRENS		= $this->input->post('SO_REFRENS');
			
			$updSO			= array('SO_NUM' 		=> $SO_NUM,
									'SO_CODE' 		=> $SO_CODE,
									'SO_TYPE' 		=> $SO_TYPE,
									'SO_CAT'		=> $SO_CAT,
									'SO_DATE'		=> $SO_DATE,
									'SO_DUED'		=> $SO_DUED,
									'SO_PRODD'		=> $SO_PRODD,
									'PRJCODE'		=> $PRJCODE,
									'DEPCODE'		=> $DEPCODE,
									'CUST_CODE' 	=> $CUST_CODE,
									'CUST_ADDRESS' 	=> $CUST_ADDRESS,
									'OFF_NUM' 		=> $OFF_NUM,
									'OFF_CODE' 		=> $OFF_CODE,
									'CCAL_NUM' 		=> $CCAL_NUM,
									'CCAL_CODE' 	=> $CCAL_CODE,
									'BOM_NUM' 		=> $BOM_NUM,
									'BOM_CODE' 		=> $BOM_CODE,
									'SO_CURR' 		=> $SO_CURR, 
									'SO_CURRATE' 	=> $SO_CURRATE,									
									'SO_PAYTYPE' 	=> $SO_PAYTYPE, 
									'SO_TENOR' 		=> $SO_TENOR,
									'SO_NOTES' 		=> $SO_NOTES,
									'SO_STAT'		=> $SO_STAT,
									'SO_INVSTAT'	=> 0,
									'SO_REFRENS'	=> $SO_REFRENS);
			$this->m_so->updateSO($SO_NUM, $updSO);
			
			// UPDATE JOBDETAIL ITEM
			if($SO_STAT == 6)
			{
				foreach($_POST['data'] as $d)
				{
					$SO_NUM		= $d['SO_NUM'];
					$ITM_CODE	= $d['ITM_CODE'];
					//$this->m_so->updateVolBud($SO_NUM, $PRJCODE, $ITM_CODE); // HOLD
				}
			}
			elseif($SO_STAT == 9)	// VOID
			{
				$SO_NUM		= $d['SO_NUM'];
				$ITM_CODE	= $d['ITM_CODE'];
				$this->m_so->updVOID($SO_NUM, $PRJCODE, $ITM_CODE);
				
				// START : UPDATE TO DOC. COUNT
					$parameters 	= array('DOC_CODE' 		=> $SO_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "SO",
											'DOC_QTY' 		=> "DOC_SOQ",
											'DOC_VAL' 		=> "DOC_SOV",
											'DOC_STAT' 		=> 'VOID');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT
			}
			else
			{
				$this->m_so->deleteSODetail($SO_NUM);
				
				$SO_TOTCOST	= 0;	
				$SO_TOTPPN	= 0;	
				foreach($_POST['data'] as $d)
				{
					$d['SO_NUM']	= $SO_NUM;
					$d['PRJCODE']	= $PRJCODE;
					$d['SO_DATE']	= $SO_DATE;
					$d['OFF_NUM']	= $OFF_NUM;
					$d['OFF_CODE']	= $OFF_CODE;
					$ITMAMOUNT		= $d['SO_COST'];
					$TAXPRICE1		= $d['TAXPRICE1'];
					$SO_TOTCOST		= $SO_TOTCOST + $ITMAMOUNT;
					$SO_TOTPPN		= $SO_TOTPPN + $TAXPRICE1;
					$this->db->insert('tbl_so_detail',$d);
				}
				
				$updSOH	= array('SO_TOTCOST' => $SO_TOTCOST, 'SO_TOTPPN' => $SO_TOTPPN);
				$this->m_so->updateSOH($SO_NUM, $updSOH);
				
				// UPDATE DETAIL
					//$this->m_so->updateDet($SO_NUM, $PRJCODE, $SO_DATE);
			}
			
			// START : UPDATE TO TRANS-COUNT
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = SO_STAT
				$parameters 	= array('DOC_CODE' 		=> $SO_NUM,			// TRANSACTION CODE
										'PRJCODE' 		=> $PRJCODE,		// PROJECT
										'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
										'TBL_NAME' 		=> "tbl_SO_header",	// TABLE NAME
										'KEY_NAME'		=> "SO_NUM",		// KEY OF THE TABLE
										'STAT_NAME' 	=> "SO_STAT",		// NAMA FIELD STATUS
										'STATDOC' 		=> $SO_STAT,		// TRANSACTION STATUS
										'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
										'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
										'FIELD_NM_N'	=> "TOT_SO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
										'FIELD_NM_C'	=> "TOT_SO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
										'FIELD_NM_A'	=> "TOT_SO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_R'	=> "TOT_SO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
										'FIELD_NM_RJ'	=> "TOT_SO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
										'FIELD_NM_CL'	=> "TOT_SO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
				$this->m_updash->updateDashData($parameters);
			// END : UPDATE TO TRANS-COUNT
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SO_NUM",
										'DOC_CODE' 		=> $SO_NUM,
										'DOC_STAT' 		=> $SO_STAT,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_so_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $SO_NUM;
				$MenuCode 		= 'MN039';
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

				$parameters 	= array('TR_TYPE'		=> "SO",
										'TR_DATE' 		=> $SO_DATE,
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
			
			$url			= site_url('c_sales/c_s4l350r4/g4Ll50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
		
		$url			= site_url('c_sales/c_s4l350r4/iN2_0xpl/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function iN2_0xpl() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN040';
				$data["MenuApp"] 	= 'MN040';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN040';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_sales/c_s4l350r4/gls0iN2_0x/?id=";
			
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
	
	function gls0iN2_0x() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN040';
			$data["MenuCode"] 	= 'MN040';
			$data["MenuApp"] 	= 'MN040';
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
				$data["url_search"] = site_url('c_sales/c_s4l350r4/f4n7_5rcH1nB/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
				$num_rows 			= $this->m_so->count_all_soinb($PRJCODE, $key, $DefEmp_ID);
				$data["cData"] 		= $num_rows;	 
				$data['vData']		= $this->m_so->get_all_soinb($PRJCODE, $start, $end, $key, $DefEmp_ID)->result();
			// -------------------- END : SEARCHING METHOD --------------------
			
			$data['title'] 		= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Penjualan";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Sales Order Approval";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['addURL'] 	= site_url('c_sales/c_s4l350r4/a44_s0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_sales/c_s4l350r4/inbox/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN040';
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
			
			$this->load->view('v_sales/v_so/v_inb_so', $data);
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
			$url			= site_url('c_sales/c_s4l350r4/gls0iN2_0x/?id='.$this->url_encryption_helper->encode_url($collDATA));
			redirect($url);
		}
	// -------------------- END : SEARCHING METHOD --------------------

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
			
			$columns_valid 	= array("SO_NUM",
									"SO_CODE", 
									"SO_DATE", 
									"CUST_CODE", 
									"OFF_CODE",
									"SO_NOTES", 
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
			$num_rows 		= $this->m_so->get_AllDataC_1n2($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_so->get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SO_NUM 		= $dataI['SO_NUM'];
                $SO_CODE 		= $dataI['SO_CODE'];
                $SO_TYPE 		= $dataI['SO_TYPE'];
                $SO_CAT 		= $dataI['SO_CAT'];
                $SO_DATE 		= $dataI['SO_DATE'];
				$SO_DATEV		= date('d M Y', strtotime($SO_DATE));
                $PRJCODE 		= $dataI['PRJCODE'];
                $CUST_CODE 		= $dataI['CUST_CODE'];
                $CUST_DESC 		= $dataI['CUST_DESC'];
                $OFF_NUM 		= $dataI['OFF_NUM'];
                $SO_TOTCOST		= $dataI['SO_TOTCOST'];
				$SO_CREATER		= $dataI['SO_CREATER'];
                $SO_TERM 		= $dataI['SO_TERM'];
                $SO_PAYTYPE 	= $dataI['SO_PAYTYPE'];
                $SO_STAT 		= $dataI['SO_STAT'];
                $SO_INVSTAT		= $dataI['SO_INVSTAT'];
                $ISDIRECT		= $dataI['ISDIRECT'];
                $SO_NOTES		= $dataI['SO_NOTES'];
                $SO_MEMO		= $dataI['SO_MEMO'];
                $SO_PRODD		= $dataI['SO_PRODD'];
				$SO_PRODD		= date('d M Y', strtotime($SO_PRODD));
                $SO_REFRENS		= $dataI['SO_REFRENS'];
                $SO_CREATER		= $dataI['SO_CREATER'];
				$STATDESC		= $dataI['STATDESC'];
				$STATCOL		= $dataI['STATCOL'];
				$CREATERNM		= $dataI['CREATERNM'];
				$empName		= cut_text2 ("$CREATERNM", 15);

				
				$CollID			= "$SO_NUM~$PRJCODE";
				$secUpd			= site_url('c_sales/c_s4l350r4/up1N2_0x/?id='.$this->url_encryption_helper->encode_url($CollID));
				$secPrint		= site_url('c_sales/c_s4l350r4/prnt180d0bdoc/?id='.$this->url_encryption_helper->encode_url($SO_NUM));

				$secDel_DOC 	= base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
                                    
				if($SO_STAT == 1) 
				{
					$secAction	= "<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
								   <label style='white-space:nowrap'>
								   <a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   </a>
									<a href='javascript:void(null);' class='btn btn-primary btn-xs' onClick='printD(".$noU.")'>
										<i class='glyphicon glyphicon-print'></i>
									</a>
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs'>
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
									<a href='#' onClick='deleteDOC('".$secDel_DOC."')' title='Delete file' class='btn btn-danger btn-xs' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}

				$output['data'][] = array("$noU.",
										  "<div style='white-space:nowrap'>".$dataI['SO_CODE']."</div>",
										  "<div style='white-space:nowrap; text-align: center'>".$SO_DATEV."</div>",
										  $CUST_DESC,
										  "<div style='white-space:nowrap; text-align: right'>".number_format($SO_TOTCOST, 2)."</div>",
										 "<div style='white-space:nowrap; text-align: center'>". $SO_PRODD."</div>",
										  $SO_REFRENS,
										  "<div style='white-space:nowrap; text-align: center'>".$empName."</div>",
										  "<span class='label label-".$STATCOL."' style='font-size:12px; text-align: center'>".$STATDESC."</span>",
										  $secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function up1N2_0x() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_sales/m_so', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
			
		// GET MENU DESC
			$mnCode				= 'MN039';
			$data["MenuApp"] 	= 'MN040';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CollID		= $_GET['id'];
			$CollID		= $this->url_encryption_helper->decode_url($CollID);
			
			$splitCode 	= explode("~", $CollID);
			$SO_NUM		= $splitCode[0];
			$PRJCODE	= $splitCode[1];
								
			$getSO_head				= $this->m_so->get_so_by_number($SO_NUM)->row();
			$PRJCODE				= $getSO_head->PRJCODE;
			$data["MenuCode"] 		= 'MN040';
			
			$docPatternPosition 	= 'Especially';				
			$data['title'] 			= $appName;
			$data['task']			= 'edit';	
			$task					= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Persetujuan Penjualan";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Sales Order Approval";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_sales/c_s4l350r4/update_process_inb');
			$cancelURL				= site_url('c_sales/c_s4l350r4/gls0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['countPRJ']		= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data['vwPRJ'] 			= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['PRJCODE']		= $PRJCODE;
			
			$MenuCode 				= 'MN040';
			$data["MenuCode"] 		= 'MN040';
		
			$getSO 							= $this->m_so->get_so_by_number($SO_NUM)->row();
			$data['default']['SO_NUM'] 		= $getSO->SO_NUM;
			$data['default']['SO_CODE'] 	= $getSO->SO_CODE;
			$data['default']['SO_TYPE'] 	= $getSO->SO_TYPE;
			$data['default']['SO_CAT'] 		= $getSO->SO_CAT;
			$data['default']['SO_DATE'] 	= $getSO->SO_DATE;
			$data['default']['SO_DUED'] 	= $getSO->SO_DUED;
			$data['default']['SO_PRODD'] 	= $getSO->SO_PRODD;
			$data['default']['PRJCODE'] 	= $getSO->PRJCODE;
			$data['default']['DEPCODE'] 	= $getSO->DEPCODE;
			$data['default']['CUST_CODE'] 	= $getSO->CUST_CODE;
			$data['default']['CUST_ADDRESS']= $getSO->CUST_ADDRESS;
			$data['default']['OFF_NUM'] 	= $getSO->OFF_NUM;
			$data['default']['OFF_CODE'] 	= $getSO->OFF_CODE;
			$data['default']['SO_CURR'] 	= $getSO->SO_CURR;
			$data['default']['SO_CURRATE'] 	= $getSO->SO_CURRATE;
			$data['default']['SO_TOTCOST'] 	= $getSO->SO_TOTCOST;
			$data['default']['SO_TOTPPN'] 	= $getSO->SO_TOTPPN;
			$data['default']['SO_PAYTYPE'] 	= $getSO->SO_PAYTYPE;
			$data['default']['SO_TENOR'] 	= $getSO->SO_TENOR;
			$data['default']['SO_NOTES'] 	= $getSO->SO_NOTES;
			$data['default']['SO_NOTES1'] 	= $getSO->SO_NOTES1;
			$data['default']['SO_MEMO'] 	= $getSO->SO_MEMO;
			$data['default']['PRJNAME'] 	= $getSO->PRJNAME;
			$data['default']['SO_STAT'] 	= $getSO->SO_STAT;
			$data['default']['SO_REFRENS'] 	= $getSO->SO_REFRENS;
			$data['default']['Patt_Year'] 	= $getSO->Patt_Year;
			$data['default']['Patt_Month'] 	= $getSO->Patt_Month;
			$data['default']['Patt_Date'] 	= $getSO->Patt_Date;
			$data['default']['Patt_Number'] = $getSO->Patt_Number;
			
			$CUST_CODE						= $getSO->CUST_CODE;
			$data['countCUST']				= $this->m_so->count_all_CUST($PRJCODE, $CUST_CODE);
			$data['vwCUST'] 				= $this->m_so->get_all_CUST($PRJCODE, $CUST_CODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $getSO->SO_NUM;
				$MenuCode 		= 'MN040';
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
			
			$this->load->view('v_sales/v_so/v_inb_so_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process_inb() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
			$SO_APPROVED 	= date('Y-m-d H:i:s');
			
			$SO_NUM 		= $this->input->post('SO_NUM');
			$SO_DATE		= date('Y-m-d',strtotime($this->input->post('SO_DATE')));
			$PRJCODE 		= $this->input->post('PRJCODE');
			$SO_NOTES1 		= addslashes($this->input->post('SO_NOTES1'));
			$SO_STAT		= $this->input->post('SO_STAT');
			$OFF_NUM		= $this->input->post('OFF_NUM');
			
			$AH_CODE		= $SO_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_NOTES		= addslashes($this->input->post('SO_NOTES1'));
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			$updSO 			= array('SO_STAT'	=> 7,
									'SO_NOTES1'	=> $SO_NOTES1);
			$this->m_so->updateSOInb($SO_NUM, $updSO);
			
			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "SO_NUM",
										'DOC_CODE' 		=> $SO_NUM,
										'DOC_STAT' 		=> 7,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> '',
										'TBLNAME'		=> "tbl_so_header");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			if($SO_STAT == 3)
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
			
			if($AH_ISLAST == 1 && $SO_STAT == 3)
			{
				$updSO 		= array('SO_STAT'	=> $SO_STAT);
				$this->m_so->updateSOInb($SO_NUM, $updSO);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "SO_NUM",
											'DOC_CODE' 		=> $SO_NUM,
											'DOC_STAT' 		=> $SO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_so_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
				
				$SO_TOTCOST	= 0;		
				foreach($_POST['data'] as $d)
				{
					$ITMAMOUNT	= $d['SO_COST'];
					$SO_TOTCOST	= $SO_TOTCOST + $ITMAMOUNT;
				}
				
				$this->m_so->updateSODet($SO_NUM, $PRJCODE);
				
				// UPDATE QUOTATION
					$this->m_so->updateOFFDet($OFF_NUM, $PRJCODE);				
				
				// START : TRACK FINANCIAL TRACK
					$this->load->model('m_updash/m_updash', '', TRUE);
					$paramFT = array('DOC_NUM' 		=> $SO_NUM,
									'DOC_DATE' 		=> $SO_DATE,
									'DOC_EDATE' 	=> $SO_DUED,
									'PRJCODE' 		=> $PRJCODE,
									'FIELD_NAME1' 	=> 'FT_COP',
									'FIELD_NAME2' 	=> 'FM_COP',
									'TOT_AMOUNT'	=> $SO_TOTCOST);
					$this->m_updash->finTrack($paramFT);
				// END : TRACK FINANCIAL TRACK
				
				// START : UPDATE TO TRANS-COUNT
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$STAT_BEFORE	= $this->input->post('STAT_BEFORE');		// IF "ADD" CONDITION ALWAYS = SO_STAT
					$parameters 	= array('DOC_CODE' 		=> $SO_NUM,			// TRANSACTION CODE
											'PRJCODE' 		=> $PRJCODE,		// PROJECT
											'TR_TYPE'		=> "PO",			// TRANSACTION TYPE
											'TBL_NAME' 		=> "tbl_SO_header",	// TABLE NAME
											'KEY_NAME'		=> "SO_NUM",		// KEY OF THE TABLE
											'STAT_NAME' 	=> "SO_STAT",		// NAMA FIELD STATUS
											'STATDOC' 		=> $SO_STAT,		// TRANSACTION STATUS
											'STATDOCBEF'	=> $STAT_BEFORE,	// TRANSACTION STATUS
											'FIELD_NM_ALL'	=> "TOT_PO",		// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
											'FIELD_NM_N'	=> "TOT_SO_N",		// TOTAL NEW TRANSACTION FOR tbl_dash_data
											'FIELD_NM_C'	=> "TOT_SO_C",		// TOTAL CONFIRM TRANSACTION FOR tbl_dash_data
											'FIELD_NM_A'	=> "TOT_SO_A",		// TOTAL APPROVE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_R'	=> "TOT_SO_R",		// TOTAL REVISE TRANSACTION FOR tbl_dash_data
											'FIELD_NM_RJ'	=> "TOT_SO_RJ",		// TOTAL REJECT TRANSACTION FOR tbl_dash_data
											'FIELD_NM_CL'	=> "TOT_SO_CL");	// TOTAL CLOSE TRANSACTION FOR tbl_dash_data
					$this->m_updash->updateDashData($parameters);
				// END : UPDATE TO TRANS-COUNT
				
				// START : UPDATE TO T-TRACK
					date_default_timezone_set("Asia/Jakarta");
					$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
					$TTR_PRJCODE	= $PRJCODE;
					$TTR_REFDOC		= $SO_NUM;
					$MenuCode 		= 'MN040';
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
					$parameters 	= array('DOC_CODE' 		=> $SO_NUM,
											'PRJCODE' 		=> $PRJCODE,
											'DOC_TYPE'		=> "SO",
											'DOC_QTY' 		=> "DOC_SOQ",
											'DOC_VAL' 		=> "DOC_SOV",
											'DOC_STAT' 		=> 'ADD');
					$this->m_updash->updateDocC($parameters);
				// END : UPDATE TO DOC. COUNT
			}
			
			// UPDATE JOBDETAIL ITEM
			if($SO_STAT == 4 || $SO_STAT == 5 || $SO_STAT == 6)
			{
				$updSO 		= array('SO_STAT'	=> $SO_STAT,
									'SO_MEMO'	=> $SO_MEMO);
				$this->m_so->updateSOInb($SO_NUM, $updSO);
			
				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "SO_NUM",
											'DOC_CODE' 		=> $SO_NUM,
											'DOC_STAT' 		=> $SO_STAT,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_so_header");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS
			}

			// START : UPDATE QTY_COLLECTIVE
				$this->load->model('m_updash/m_updash', '', TRUE);

				$parameters 	= array('TR_TYPE'		=> "SO",
										'TR_DATE' 		=> $SO_DATE,
										'PRJCODE'		=> $PRJCODE);
				$this->m_updash->updateQtyColl($parameters);
			// END : UPDATE QTY_COLLECTIVE
			
			$url			= site_url('c_sales/c_s4l350r4/gls0iN2_0x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prnt180d0bdoc() // G
	{
		$this->load->model('m_sales/m_so', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		/*$SO_NUM		= $_GET['id'];
		$SO_NUM		= $this->url_encryption_helper->decode_url($SO_NUM);*/
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			/*$getSO 			= $this->m_so->get_so_by_number($SO_NUM)->row();
			
			$data['SO_NUM'] 	= $getSO->SO_NUM;
			$data['SO_CODE'] 	= $getSO->SO_CODE;
			$data['PR_CODE'] 	= $getSO->PR_CODE;
			$data['SO_DATE'] 	= $getSO->SO_DATE;
			$data['SO_DUED'] 	= $getSO->SO_DUED;
			$data['PRJCODE'] 	= $getSO->PRJCODE;
			$data['CUST_CODE'] 	= $getSO->CUST_CODE;
			$data['PR_NUM'] 	= $getSO->PR_NUM;
			$data['SO_PAYTYPE'] = $getSO->SO_PAYTYPE;
			$data['SO_TENOR'] 	= $getSO->SO_TENOR;
			$data['SO_TERM'] 	= $getSO->SO_TERM;
			$data['SO_NOTES'] 	= $getSO->SO_NOTES;
			$data['PRJNAME'] 	= $getSO->PRJNAME;
			$data['SO_STAT'] 	= $getSO->SO_STAT;
			$data['SO_RECEIVLOC']= $getSO->SO_RECEIVLOC;
			$data['SO_RECEIVCP'] = $getSO->SO_RECEIVCP;
			$data['SO_SENTROLES']= $getSO->SO_SENTROLES;
			$data['SO_REFRENS']	= $getSO->SO_REFRENS;
							
			$this->load->view('v_sales/v_so/print_po', $data);*/
							
			$this->load->view('blank', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getDetTOP($CUST_CODE) // OK
	{
		$CUSTTOP 	= 0;
		$CUSTTOPD 	= 0;
		$CUST_ADD1	= '';
		$sqlCUSTC	= "tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
		$resCUSTC 	= $this->db->count_all($sqlCUSTC);
		if($resCUSTC > 0)
		{
			$sqlCUST		= "SELECT CUST_TOP, CUST_TOPD, CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
			$resCUST 		= $this->db->query($sqlCUST)->result();
			foreach($resCUST as $row) :
				$CUSTTOP 	= $row->CUST_TOP;
				$CUSTTOPD 	= $row->CUST_TOPD;
				$CUST_ADD1 	= $row->CUST_ADD1;
			endforeach;
		}
		$CUSTTOPCOL	= "$CUSTTOP~$CUSTTOPD~$CUST_ADD1";
		echo $CUSTTOPCOL;
	}

  	function get_AllDataITM() // GOOD
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
									"JOBDESC",
									"ITM_UNIT",
									"ITM_VOLM",
									"",
									"",
									"",
									"ITM_STOCK");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_so->get_AllDataITMC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_so->get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir);
								
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

				// OTHER SETT
					if($disabledB == 0)
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."|".$ITM_NAME."' onClick='pickThis(this);'/>";
					}
					else
					{
						$chkBox		= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$JOBDESC1."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLMBG."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNTBG."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";
					}

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

				// STOCK
					$TOT_STOCK		= 0;
					$sqlSTOCK		= "SELECT SUM(ITM_IN - ITM_OUT) AS TOT_STOCK FROM tbl_item_whqty
										WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$resSTOCK		= $this->db->query($sqlSTOCK)->result();
					foreach($resSTOCK as $rowSTOCK) :
						$TOT_STOCK	= $rowSTOCK->TOT_STOCK;
					endforeach;
					$TOTSTOCK 		= number_format($TOT_STOCK, 2);

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
											"<div style='text-align:right;'>".$statRem."</div>",
											"<div style='text-align:right;'>".$TOTSTOCK."</div>");

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
			$num_rows 		= $this->m_so->get_AllDataITMSC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_so->get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir);
								
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
				$ITM_NAME		= $dataI['JOBDESC'];
				$ITM_GROUP		= $dataI['ITM_GROUP'];
				$ITM_CODE		= $dataI['ITM_CODE'];
				$ITM_UNIT		= $dataI['ITM_UNIT'];
				$JOBUNIT 		= strtoupper($dataI['ITM_UNIT']);
				if($JOBUNIT == '')
					$JOBUNIT= 'LS';

				$JOBVOLM		= $dataI['ITM_BUDG'];
				$ITM_VOLMBG		= $dataI['ITM_BUDG'];
				$JOBPRICE		= $dataI['ITM_PRICE'];
				$ITM_LASTP		= $dataI['ITM_LASTP'];
				$ADD_VOLM 		= $dataI['ADD_VOLM'];
				$ADD_PRICE		= $dataI['ADD_PRICE'];
				//$ADD_JOBCOST	= $dataI['ADD_JOBCOST'];
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
				$ITM_TYPE		= $dataI['ITM_TYPE'];
				$ITM_CODE_H		= $dataI['ITM_CODE_H'];

				$serialNumber	= '';
				$itemConvertion	= 1;
				$TOT_VOLM 		= $ITM_VOLMBG + $ADD_VOLM;
				$TOT_AMOUNT		= ($JOBPRICE * $ITM_VOLMBG) + ($ADD_VOLM * $ADD_PRICE);

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
						$MAX_REQ	= $TOT_AMOUNT - $TOT_REQ;		// 14
						$PO_VOLM	= $PO_AMOUNT;
						$TOT_BUDG	= $TOT_AMOUNT;
					}
					else
					{
						$TOT_REQ 	= $REQ_VOLM + $TOT_PRVOL;
						$MAX_REQ	= $TOT_VOLM - $TOT_REQ;			// 14
						$PO_VOLM	= $PO_VOLM;
						$TOT_BUDG	= $TOT_VOLM;
					}
				
					$tempTotMax		= $MAX_REQ;

					$REMREQ_QTY		= $TOT_VOLM - $TOT_REQ;
					$REMREQ_AMN		= $TOT_AMOUNT - $TOT_BUDG;
					
					$disabledB		= 0;
					if($JOBUNIT == 'LS')
					{
						if($REMREQ_AMN <= 0)
							$disabledB	= 1;
						
						$ITM_PRICE		= 1;
						$TOT_VOLM		= $TOT_AMOUNT;
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

					$strLEN 	= strlen($JOBDESC);
					$JOBDESCA	= substr("$JOBDESC", 0, 60);
					$JOBDESC1 	= $JOBDESCA;
					if($strLEN > 60)
						$JOBDESC1 	= $JOBDESCA."...";
					$STATCOL	= 'success';
					$CELL_COL	= "style='white-space:nowrap'";

				// SPACE
					$spaceLev 	= "";

				// OTHER SETT
						if($disabledB == 0)
						{
							$chkBox	= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$ITM_NAME."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLM."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNT."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' onClick='pickThis(this);'/>";
						}
						else
						{
							$chkBox	= "<input type='checkbox' name='chk' value='".$JOBCODEDET."|".$JOBCODEID."|".$JOBCODE."|".$PRJCODE."|".$ITM_CODE."|".$ITM_NAME."|".$serialNumber."|".$ITM_UNIT."|".$ITM_PRICE."|".$TOT_VOLM."|".$ITM_STOCK."|".$ITM_USED."|".$itemConvertion."|".$TOT_AMOUNT."|".$tempTotMax."|".$PO_AMOUNT."|".$TOT_BUDG."|".$TOT_REQ."|".$ITM_TYPE."' style='display: none' />";
						}

					$secUpd			= site_url('c_comprof/c_bUd93tL15t/update/?id='.$this->url_encryption_helper->encode_url($JOBCODEID));
	                
					$secPrint		= 	"<label style='white-space:nowrap'>
										   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
												<i class='glyphicon glyphicon-pencil'></i>
										   	</a>
										</label>";
					$JobView		= "$spaceLev $JOBCODEID - $JOBDESC1";

				// STOCK
					$TOT_STOCK		= 0;
					$sqlSTOCK		= "SELECT SUM(ITM_IN - ITM_OUT) AS TOT_STOCK FROM tbl_item_whqty
										WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$resSTOCK		= $this->db->query($sqlSTOCK)->result();
					foreach($resSTOCK as $rowSTOCK) :
						$TOT_STOCK	= $rowSTOCK->TOT_STOCK;
					endforeach;

				$output['data'][] 	= array($chkBox,
										"<span ".$CELL_COL.">".$JobView."</span>",
										"<div style='text-align:center;'><span ".$CELL_COL.">".$JOBUNIT."</span></div>",
										"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($JOBVOLM, 2)."</span></div>",
										"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($TOT_REQ, 2)."</span></div>",
										"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($PO_VOLM, 2)."</span></div>",
										"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($REMREQ_QTY, 2)."</span></div>",
										"<div style='text-align:right;'><span ".$CELL_COL.">".number_format($TOT_STOCK, 2)."</span></div>");

				$noU			= $noU + 1;
			}
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}