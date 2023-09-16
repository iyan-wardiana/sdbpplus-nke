<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 16 Juli 2023
	* File Name		= c_qritase.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_qritase extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_qritase/m_qritase', '', TRUE);
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
	
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
		
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}

 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_qritase/c_qritase/pL15t/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function pL15t() // G
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN531';
				$data["MenuApp"] 	= 'MN531';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN531';
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data["secURL"] 	= "c_qritase/c_qritase/qrRitIdx/?id=";
			
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
	
	function qrRitIdx($offset=0)
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
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
			// -------------------- END : SEARCHING METHOD --------------------
			
			// GET MENU DESC
				$mnCode				= 'MN531';
				$data["MenuCode"] 	= 'MN531';
				$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 		= $appName;
			$data['addURL'] 	= site_url('c_qritase/c_qritase/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['backURL'] 	= site_url('c_qritase/c_qritase/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['PRJCODE'] 	= $PRJCODE;
			
			$this->load->view('v_qritase/v_qritase', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function get_AllData()
	{
		$PRJCODE		= $_GET['PRJCODE'];
		$QRIT_NOPOL		= $_GET['QRIT_NOPOL'];
		$QRIT_DRIVER	= $_GET['QRIT_DRIVER'];

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
			
			$columns_valid 	= array("QRIT_DATE",
									"QRIT_DRIVER",
									"QRIT_DEST",
									"QRIT_VOL",
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
			$num_rows 		= $this->m_qritase->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_qritase->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$QRIT_NUM		= $dataI['QRIT_NUM'];
				$QRIT_DATE		= $dataI['QRIT_DATE'];
				$QRIT_DATEV		= strftime('%d %b %Y', strtotime($QRIT_DATE));
				$QRIT_NOPOL		= $dataI['QRIT_NOPOL'];
				$QRIT_DRIVER	= $dataI['QRIT_DRIVER'];
				$QRIT_DEST		= $dataI['QRIT_DEST'];
				$QRIT_VOL		= $dataI['QRIT_VOL'];
				$CREATER		= $dataI['CREATER'];
				$RECEIVER		= $dataI['RECEIVER'];
				$RECEIVED		= $dataI['RECEIVED'];
				$RECEIVEDV		= strftime('%d %b %Y', strtotime($RECEIVED));
				if($RECEIVED == '')
					$RECEIVEDV 	= "Belum Diterima";


				$CollID			= "$PRJCODE~$QRIT_NUM";
				$secUpd			= site_url('c_qritase/c_qritase/u77p180c21o_p0/?id='.$this->url_encryption_helper->encode_url($CollID));
				$printQR		= site_url('c_qritase/c_qritase/prntQRIT/?id='.$this->url_encryption_helper->encode_url($QRIT_NUM));
				$secDelDoc 		= base_url().'c_qritase/c_qritase/trashQRIT/?id=';
				$delID 			= "$secDelDoc~tbl_qritase~QRIT_NUM~$QRIT_NUM~PRJCODE~$PRJCODE";

				$secAction		= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$printQR."'>
									<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update' style='display: none'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
									<a href='javascript:void(null);' class='btn btn-warning btn-xs' onClick='printQR(".$noU.")'>
										<i class='glyphicon glyphicon-qrcode'></i>
									</a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
									</label>";

				$output['data'][] 	= [	$noU,
										"<div style='white-space:nowrap'>
								  			<strong><i class='fa fa-rocket margin-r-5'></i>".$QRIT_DATEV."</strong>
									  		<div style='margin-left: 20px'>
										  		".$CREATER."
										  	</div>
										</div>",
										"<div style='white-space:nowrap'>
								  			<strong><i class='fa fa-cubes margin-r-5'></i>".$RECEIVEDV."</strong>
									  		<div style='margin-left: 20px'>
										  		".$RECEIVER."
										  	</div>
										</div>",
										$QRIT_NOPOL,
										$QRIT_DRIVER,
										$QRIT_DEST,
										number_format($QRIT_VOL,2),
										$secAction
									];
				$noU		= $noU + 1;
			}

			// $output['data'][] 	= ["$PRJCODE", "B", "C", "D", "E", "F", "G"];
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

    function saveRIT()
    {
    	date_default_timezone_set("Asia/Jakarta");

    	$QRIT_TASK		= $_POST['QRIT_TASK'];
    	$PRJCODE		= $_POST['PRJCODE'];
    	$PRJCODE		= $_POST['PRJCODE'];
		$QRIT_NOPOL		= $_POST['QRIT_NOPOL'];
		$QRIT_DRIVER	= $_POST['QRIT_DRIVER'];
		$QRIT_DEST		= $_POST['QRIT_DEST'];
		$QRIT_MATERIAL	= $_POST['QRIT_MATERIAL'];
		$QRIT_DIM		= $_POST['QRIT_DIM'];
		$QRIT_VOL		= $_POST['QRIT_VOL'];
		$QRIT_UNIT		= $_POST['QRIT_UNIT'];
		$QRIT_NOTES		= $_POST['QRIT_NOTES'];

		$QRIT_NUM 		= "QRIT$PRJCODE".date('ymd').".".date('His');
		$QRIT_DATE 		= date('Y-m-d');

		$UNIC_01 		= substr($PRJCODE,0,3);
		$UNIC_02 		= date('y');
		$UNIC_03 		= date('m');
		$UNIC_04 		= date('d');
		$UNIC_05 		= date('His');

		$s_RITC  		= "tbl_qritase WHERE PRJCODE = '$PRJCODE' AND QRIT_DATE = '$QRIT_DATE'";
		$r_RITC  		= $this->db->count_all($s_RITC);
		$myMax 			= $r_RITC+1;
		$len 			= strlen($myMax);
		if($len==1) $nol="00";else if($len==2) $nol="0"; else $nol="";

		$UNIC_05  		= $nol.$myMax;

		$QRIT_CODE 		= $UNIC_01.".".$UNIC_02.$UNIC_03.$UNIC_04."-".$UNIC_05;

		$CREATER 		= $this->session->userdata['completeName'];
		$CREATED 		= date('Y-m-d H:i:s');

		if($QRIT_TASK == 'add')
		{
			$s_00		= "INSERT INTO tbl_qritase 
								(	PRJCODE, QRIT_NUM, QRIT_DATE, QRIT_NOPOL, QRIT_DRIVER, QRIT_DEST, QRIT_MATERIAL,
									QRIT_DIM, QRIT_VOL, QRIT_UNIT, QRIT_NOTES, QRIT_CODE, CREATER, CREATED 	)
							VALUES
								(	'$PRJCODE', '$QRIT_NUM', '$QRIT_DATE', '$QRIT_NOPOL', '$QRIT_DRIVER', '$QRIT_DEST', '$QRIT_MATERIAL',
									'$QRIT_DIM', '$QRIT_VOL', '$QRIT_UNIT', '$QRIT_NOTES', '$QRIT_CODE', '$CREATER', '$CREATED')";
		}
		else
		{
			//
		}
		$this->db->query($s_00);

		echo $s_00;
    }
	
	function prntQRIT()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$QRIT_NUM	= $_GET['id'];
		$QRIT_NUM	= $this->url_encryption_helper->decode_url($QRIT_NUM);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 	= $appName;
			
			$getQRIT				= $this->m_qritase->get_qrit_by_number($QRIT_NUM)->row();
			
	    	$data['PRJCODE']		= $getQRIT->PRJCODE;
	    	$data['QRIT_CODE']		= $getQRIT->QRIT_CODE;
			$data['QRIT_NOPOL']		= $getQRIT->QRIT_NOPOL;
			$data['QRIT_DRIVER']	= $getQRIT->QRIT_DRIVER;
			$data['QRIT_DEST']		= $getQRIT->QRIT_DEST;
			$data['QRIT_MATERIAL']	= $getQRIT->QRIT_MATERIAL;
			$data['QRIT_DIM']		= $getQRIT->QRIT_DIM;
			$data['QRIT_VOL']		= $getQRIT->QRIT_VOL;
			$data['QRIT_UNIT']		= $getQRIT->QRIT_UNIT;
			$data['QRIT_NOTES']		= $getQRIT->QRIT_NOTES;

			$this->load->view('v_qritase/v_qritase_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

    function chkQRIT()
    {
    	date_default_timezone_set("Asia/Jakarta");

    	$QRIT_CODE		= $_POST['QRIT_CODE'];

		$s_RITC0  		= "tbl_qritase WHERE QRIT_CODE = '$QRIT_CODE'";
		$r_RITC0  		= $this->db->count_all($s_RITC0);

		$s_RITC  		= "tbl_qritase WHERE QRIT_CODE = '$QRIT_CODE' AND ISUSED = '0'";
		$r_RITC  		= $this->db->count_all($s_RITC);

		if($r_RITC0 == 0)
		{
			$statUSED 	= "Kode yang Anda masukan tidak ditemukan";
			$isRejected = 2;
		}
		else
		{
			if($r_RITC == 0)
			{
				$IRCODE 	= "";
				$s_RITC  	= "SELECT IR_CODE FROM tbl_qritase WHERE QRIT_CODE = '$QRIT_CODE'";
				$r_RITC		= $this->db->query($s_RITC)->result();
				foreach($r_RITC as $rw_RITC):
					$IRCODE	= $rw_RITC->IR_CODE;
				endforeach;
				$statUSED 	= "Kode ini sudah digunakan oleh $IRCODE";
				$isRejected = 1;
			}
			else
			{
				$statUSED 	= "Good. Kode ini masih tersedia.";
				$isRejected = 0;
			}
		}

		echo "$statUSED~$isRejected";
    }
}