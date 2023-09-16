<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 21 Oktober 2018
 * File Name	= C_j0rd3rsel.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_j0rd3rsel extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_production/m_joselect', '', TRUE);
		$this->load->model('m_production/m_joborder', '', TRUE);
		$this->load->model('m_production/m_matreq', '', TRUE);
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
		$this->load->model('m_production/m_joselect', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_production/c_j0rd3rsel/prj180c21l/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prj180c21l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			$mnCode				= 'MN372';
			$data["MenuCode"] 	= 'MN372';
			$data["MenuApp"] 	= 'MN372';
			$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
			$getMN				= $this->m_updash->get_menunm($mnCode)->row();
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = $getMN->menu_name_IND;
			else
				$data["mnName"] = $getMN->menu_name_ENG;
			
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Seleksi Job Order";
			}
			else
			{
				$data["h1_title"] 	= "Job Order Selection";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN372';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_production/c_j0rd3rsel/glj0b0rd3rs3l/?id=";
			
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
	
	function glj0b0rd3rs3l() // G
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joselect', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');

			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN372';
				$data["MenuApp"] 	= 'MN372';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data["MenuCode"] 	= 'MN372';
			$data["PRJCODE"] 	= $PRJCODE;
			$data['procURL'] 	= site_url('c_production/c_j0rd3rsel/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_production/c_j0rd3rsel/');
			$data['form_action']= site_url('c_production/c_j0rd3rsel/add_process');
			
			$data["countJO"] 	= $this->m_joselect->count_all_JO($PRJCODE);
			$data['vwJO'] 		= $this->m_joselect->get_all_JO($PRJCODE)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN372';
				$TTR_CATEG		= 'INV-ALL';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_production/v_jobselect/v_jobselect', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	/*function add_process() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joselect', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$ISSELECTED		= date('Y-m-d H:i:s');
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$colSelINV 		= $this->input->post('colSelINV');
			
			$cArray			= count(explode("~", $colSelINV));
			$JO_NUMX		= explode("~", $colSelINV);
			$JO_NUM0		= $JO_NUMX[0];
			$PRJCODE		= $JO_NUMX[$cArray-1];
			
			$collData		= '';
			if($cArray == 2)
			{
				$updJO 	= "UPDATE tbl_jo_header SET ISSELECT = 1, ISSELECTER = '$DefEmp_ID', ISSELECTED = '$ISSELECTED'
							WHERE JO_NUM = '$JO_NUM0' AND PRJCODE = '$PRJCODE'";
				$this->db->query($updJO);
				$collData	= $INV_ID0;	
			}
			else
			{
				$theRow		= 0;
				foreach ($JO_NUMX as $valIJOD) :
					$theRow		= $theRow + 1;
					$JO_NUM		= $valIJOD;
					
					if($theRow == 1)
						$collData	= $JO_NUM;
					else
						$collData	= "$collData~$JO_NUM";
						
					$updJO 	= "UPDATE tbl_jo_header SET ISSELECT = 1, ISSELECTER = '$DefEmp_ID', ISSELECTED = '$ISSELECTED'
								WHERE JO_NUM = '$JO_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($updJO);
				endforeach;
			}
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $collData;
				$MenuCode 		= 'MN372';
				$TTR_CATEG		= 'PROC';
				
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
			
			$url	= site_url('c_production/c_j0rd3rsel/glj0b0rd3rs3l/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}*/

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
			
			$columns_valid 	= array("JO_CODE",
									"JO_DATE",
									"JO_PRODD",
									"CUST_DESC",
									"JO_DESC",
									"JO_VOLM",
									"JO_AMOUNT");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_joselect->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_joselect->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI)
			{
				$JO_ID		= $dataI['JO_ID'];
				$PRJCODE	= $dataI['PRJCODE'];
				$JO_NUM		= $dataI['JO_NUM'];
				$JO_CODE	= $dataI['JO_CODE'];
				$JO_DATE	= $dataI['JO_DATE'];
				$JO_DATEV	= date('d M Y', strtotime($JO_DATE));
				$JO_PRODD	= $dataI['JO_PRODD'];
				$JO_PRODDV	= date('d M Y', strtotime($JO_PRODD));
				
				$CUST_CODE	= $dataI['CUST_CODE'];
				$CUST_DESC	= $dataI['CUST_DESC'];
				$JO_DESC	= $dataI['JO_DESC'];
				$JO_VOLM	= $dataI['JO_VOLM'];
				$JO_AMOUNT	= $dataI['JO_AMOUNT'];
				$JO_NOTES	= $dataI['JO_NOTES'];
				$JO_NOTES2	= $dataI['JO_NOTES2'];
				$JO_STAT	= $dataI['JO_STAT'];
				$ISSELECT	= $dataI['ISSELECT'];
				$STATDESC	= $dataI['STATDESC'];
				$STATCOL	= $dataI['STATCOL'];
				$CREATERNM	= $dataI['CREATERNM'];
				$empName	= cut_text2 ("$CREATERNM", 15);
				
				$CollID		= "JO~$JO_NUM~$PRJCODE";
				$secDelIcut = base_url().'index.php/c_production/c_j0rd3rsel/procJO/?id=';
				$procD 		= "$secDelIcut~tbl_jo_header~tbl_jo_detail~JO_NUM~$JO_NUM~PRJCODE~$PRJCODE";

				$secAction	= 	"<input type='hidden' name='urlProc".$noU."' id='urlProc".$noU."' value='".$procD."'>
							   	<label style='white-space:nowrap'>
								<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='procJO(".$noU.")' title='Proses'>
									<i class='glyphicon glyphicon-ok'></i>
								</a>
								</label>";

				$output['data'][] = array($secAction,
										  $JO_CODE,
										  $JO_DATEV,
										  $JO_PRODDV,
										  $CUST_DESC,
										  $JO_NOTES,
										  number_format($JO_VOLM, 2),
										  number_format($JO_AMOUNT, 2));
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function procJO() // OK
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$this->load->model('m_production/m_joselect', '', TRUE);

		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $tblNameH 	= $colExpl[1];
        $tblNameD 	= $colExpl[2];
        $DocNm		= $colExpl[3];	// JO_NUM
        $DocNum		= $colExpl[4];
        $PrjNm		= $colExpl[5];
        $PrjCode	= $colExpl[6];
		
		date_default_timezone_set("Asia/Jakarta");
		
		$ISSELECTED		= date('Y-m-d H:i:s');
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$JO_NUM0		= $DocNum;
		$PRJCODE		= $PrjCode;

		$updJO 			= "UPDATE tbl_jo_header SET ISSELECT = 1, ISSELECTER = '$DefEmp_ID', ISSELECTED = '$ISSELECTED'
							WHERE JO_NUM = '$JO_NUM0' AND PRJCODE = '$PRJCODE'";
		$this->db->query($updJO);

		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= $PRJCODE;
			$TTR_REFDOC		= $JO_NUM0;
			$MenuCode 		= 'MN372';
			$TTR_CATEG		= 'PROC_JO';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "No. Dokumen : $DocNum telah diproses.";
		}
		else
		{
			$alert1	= "Document no. $DocNum has been processed.";
		}
		echo "$alert1";
	}
}