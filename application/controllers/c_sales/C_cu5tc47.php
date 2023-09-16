<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 18 Oktober 2018
 * File Name	= C_cu5tc47.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_cu5tc47 extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_sales/m_custcat', '', TRUE);
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

 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_cu5tc47/g37_4ll_cu5tc47/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function g37_4ll_cu5tc47() // G
	{
		$this->load->model('m_sales/m_custcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName				= $_GET['id'];
			$appName				= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;			
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Kategori";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Category";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['secAddURL'] 		= site_url('c_sales/c_cu5tc47/a44/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN035';
			$num_rows 				= $this->m_custcat->count_all();
			$data['countsalecat'] 	= $num_rows;
	 
			$data['vwsalecat'] 		= $this->m_custcat->g37_4ll_cu5tc47()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN035';
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
			
			$this->load->view('v_sales/v_custcat/v_custcat', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		//$PRJCODE		= $_GET['id'];
		$PRJCODE		= "";
		
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

			$columns_valid 	= array("CUSTC_CODE",
									"CUSTC_NAME",
									"CUSTC_DESC");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_custcat->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_custcat->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$CUSTC_CODE	= $dataI['CUSTC_CODE'];
				$CUSTC_NAME	= $dataI['CUSTC_NAME'];
				$CUSTC_DESC	= $dataI['CUSTC_DESC'];
				
				$isUse 		= 0;
				$sqlC		= "tbl_customer WHERE CUST_CAT = '$CUSTC_CODE'";
				$resC 		= $this->db->count_all($sqlC);
				if($resC > 0)
					$isUse 	= 1;
				
                $secUpd		= site_url('c_sales/c_cu5tc47/up4/?id='.$this->url_encryption_helper->encode_url($CUSTC_CODE));
				$secDel 	= base_url().'index.php/__l1y/trashCAT/?id='.$CUSTC_CODE;

				$LangID 	= $this->session->userdata['LangID'];
				if($LangID == 'IND')
				{
					$alert1	= "Kategori $CUSTC_CODE : $CUSTC_NAME telah dihapus.";
				}
				else
				{
					$alert1	= "Category $CUSTC_CODE : $CUSTC_NAME has been deleted.";
				}

				$delID 		= "$secDel~tbl_custcat~CUSTC_CODE~$CUSTC_CODE~$alert1";

				if($isUse == 0) 
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label class='pull-right' style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteCAT(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								  	<label class='pull-right' style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled='disabled'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";
				}
												
				
				$output['data'][] = array("$noU.",
										  $dataI['CUSTC_CODE'],
										  $CUSTC_NAME,
										  $CUSTC_DESC.$secAction);
				/*$output['data'][] = array("$isUse",
										  "",
										 "",
										  "");*/
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44() // G
	{
		$this->load->model('m_sales/m_custcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Tambah Kategori";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Add Category";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_sales/c_cu5tc47/add_process');
			$data['backURL'] 		= site_url('c_sales/c_cu5tc47/');
			$data["MenuCode"] 		= 'MN035';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN035';
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
			
			$this->load->view('v_sales/v_custcat/v_custcat_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getCUSTCAT($cinta) // G
	{ 
		$this->load->model('m_sales/m_custcat', '', TRUE);
		$recordcountSCAT 	= $this->m_custcat->count_all_num_rowsSCAT($cinta);
		echo $recordcountSCAT;
	}
	
	function add_process() // G
	{
		$this->load->model('m_sales/m_custcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CUSTC_CODE		= $this->input->post('CUSTC_CODE');
			$Acc_DirParentA	= $this->input->post('Acc_DirParentA');
			$Acc_DirParentB	= $this->input->post('Acc_DirParentB');
			$Acc_DirParentC	= $this->input->post('Acc_DirParentC');
			
			$sqlAccDEL1	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'SINV'";
			$this->db->query($sqlAccDEL1);
			
			$sqlAccDEL2	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'BR'";
			$this->db->query($sqlAccDEL2);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'SINV'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'SINV',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'D');
				$this->m_custcat->add1($LinkAcc1);
				
				$LinkAcc1a 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'SINV',
									'LA_ACCID'		=> $Acc_DirParentC,
									'LA_DK'			=> 'K');
				$this->m_custcat->add1($LinkAcc1a);
				
				$LinkAcc2 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'BR',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'K');
				$this->m_custcat->add2($LinkAcc2);
			}
			else
			{
				$LA_CATEG1	= 'SINV';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_custcat->upd1($LinkAcc1, $CUSTC_CODE, $LA_CATEG1);
				
				$LA_CATEG1a	= 'SINV';
				$LinkAcc1a 	= array('LA_ACCID'	=> $Acc_DirParentC);
				$this->m_custcat->upd1a($LinkAcc1a, $CUSTC_CODE, $LA_CATEG1a);
				
				$LA_CATEG2	= 'SINV';
				$LinkAcc2 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_custcat->upd2($LinkAcc2, $CUSTC_CODE, $LA_CATEG2);
			}
			
			$sqlAccDEL3	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'DP'";
			$this->db->query($sqlAccDEL3);
			
			$sqlAcc2 	= "tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'DP'";
			$resAcc2 	= $this->db->count_all($sqlAcc2);
			if($resAcc2 == 0)
			{
				$LinkAcc3 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'DP',
									'LA_ACCID'		=> $Acc_DirParentB,
									'LA_DK'			=> 'D');
				$this->m_custcat->add1($LinkAcc3);
			}
			else
			{
				$LA_CATEG1	= 'DP';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentB);
				$this->m_custcat->upd1($LinkAcc1, $CUSTC_CODE, $LA_CATEG1);
			}
			
			$custcat 	= array('CUSTC_CODE' 	=> $this->input->post('CUSTC_CODE'),
								'CUSTC_NAME'	=> addslashes($this->input->post('CUSTC_NAME')),
								'CUSTC_DESC'	=> addslashes($this->input->post('CUSTC_DESC')),
								'CC_LA_CINV'	=> $Acc_DirParentA,
								'CC_LA_CINVK'	=> $this->input->post('Acc_DirParentAK'),
								'CC_LA_RECDP'	=> $Acc_DirParentB);

			$this->m_custcat->add($custcat);
						
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('CUSTC_CODE');
				$MenuCode 		= 'MN035';
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
			
			$url			= site_url('c_sales/c_cu5tc47/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function up4() // G
	{
		$this->load->model('m_sales/m_custcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		$CUSTC_CODE	= $_GET['id'];
		$CUSTC_CODE	= $this->url_encryption_helper->decode_url($CUSTC_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Ubah Kategori";
				$data['h2_title'] 	= 'Pelanggan';
			}
			else
			{
				$data["h1_title"] 	= "Edit Category";
				$data['h2_title'] 	= 'Customer';
			}
			
			$data['form_action']	= site_url('c_sales/c_cu5tc47/update_process');
			$data['link'] 			= array('link_back' => anchor('c_sales/c_cu5tc47/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_sales/c_cu5tc47/');
			$data["MenuCode"] 		= 'MN035';
			$getvendcat 			= $this->m_custcat->get_custcat_by_code($CUSTC_CODE)->row();
			
			$data['default']['CUSTC_CODE']	 	= $getvendcat->CUSTC_CODE;
			$data['default']['CUSTC_NAME']	 	= $getvendcat->CUSTC_NAME;
			$data['default']['CUSTC_DESC']	 	= $getvendcat->CUSTC_DESC;
			$data['default']['CC_LA_CINV'] 		= $getvendcat->CC_LA_CINV;
			$data['default']['CC_LA_CINVK'] 	= $getvendcat->CC_LA_CINVK;
			$data['default']['CC_LA_RECDP'] 	= $getvendcat->CC_LA_RECDP;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendcat->CUSTC_CODE;
				$MenuCode 		= 'MN035';
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
			
			$this->load->view('v_sales/v_custcat/v_custcat_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_sales/m_custcat', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CUSTC_CODE		= $this->input->post('CUSTC_CODE');
			$Acc_DirParentA	= $this->input->post('Acc_DirParentA');
			$Acc_DirParentB	= $this->input->post('Acc_DirParentB');
			
			$sqlAccDEL1	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'SINV'";
			$this->db->query($sqlAccDEL1);
			
			$sqlAccDEL2	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'BR'";
			$this->db->query($sqlAccDEL2);
			
			$sqlAcc 	= "tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'SINV'";
			$resAcc 	= $this->db->count_all($sqlAcc);
			if($resAcc == 0)
			{
				$LinkAcc1 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'SINV',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'D');
				$this->m_custcat->add1($LinkAcc1);
				
				$LinkAcc2 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'BR',
									'LA_ACCID'		=> $Acc_DirParentA,
									'LA_DK'			=> 'K');
				$this->m_custcat->add2($LinkAcc2);
			}
			else
			{
				$LA_CATEG1	= 'SINV';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_custcat->upd1($LinkAcc1, $CUSTC_CODE, $LA_CATEG1);
				
				$LA_CATEG2	= 'SINV';
				$LinkAcc2 	= array('LA_ACCID'	=> $Acc_DirParentA);
				$this->m_custcat->upd2($LinkAcc2, $CUSTC_CODE, $LA_CATEG2);
			}
			
			$sqlAccDEL3	= "DELETE FROM tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'DP'";
			$this->db->query($sqlAccDEL3);
			
			$sqlAcc2 	= "tbl_link_account WHERE LA_ITM_CODE = '$CUSTC_CODE' AND LA_CATEG = 'DP'";
			$resAcc2 	= $this->db->count_all($sqlAcc2);
			if($resAcc2 == 0)
			{
				$LinkAcc3 	= array('LA_ITM_CODE' 	=> $CUSTC_CODE,
									'LA_CATEG'		=> 'DP',
									'LA_ACCID'		=> $Acc_DirParentB,
									'LA_DK'			=> 'D');
				$this->m_custcat->add1($LinkAcc3);
			}
			else
			{
				$LA_CATEG1	= 'DP';
				$LinkAcc1 	= array('LA_ACCID'	=> $Acc_DirParentB);
				$this->m_custcat->upd1($LinkAcc1, $CUSTC_CODE, $LA_CATEG1);
			}
			
			$custcat 	= array('CUSTC_CODE' 	=> $this->input->post('CUSTC_CODE'),
								'CUSTC_NAME'	=> addslashes($this->input->post('CUSTC_NAME')),
								'CUSTC_DESC'	=> addslashes($this->input->post('CUSTC_DESC')),
								'CC_LA_CINV'	=> $Acc_DirParentA,
								'CC_LA_CINVK'	=> $this->input->post('Acc_DirParentAK'),
								'CC_LA_RECDP'	=> $Acc_DirParentB);
										
			$this->m_custcat->update($CUSTC_CODE, $custcat);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $CUSTC_CODE;
				$MenuCode 		= 'MN035';
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
			
			$url			= site_url('c_sales/c_cu5tc47/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
}