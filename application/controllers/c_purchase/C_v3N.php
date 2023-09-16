<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 25 November 2017
	* File Name	= C_v3N.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_v3N extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
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
	
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_purchase/c_v3N/gL5upL/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function gL5upL() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName				= $_GET['id'];
			$appName				= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Vendor';
			$data['h3_title'] 		= 'purchase';
			$data['secAddURL'] 		= site_url('c_purchase/c_v3N/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN008';
			$num_rows 				= $this->m_vendor->count_all_supplier();
			$data['countSUPL'] 		= $num_rows;	 
			$data['vwSUPL'] 		= $this->m_vendor->get_all_supplier()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN008';
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
			
			$this->load->view('v_purchase/v_vendor/v_vendor', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

  	function get_AllData() // GOOD
	{
		$LangID 		= $this->session->userdata['LangID'];
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			if($TranslCode == 'payableList')$payableList = $LangTransl;
			if($TranslCode == 'Contact')$Contact = $LangTransl;
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
			
			$columns_valid 	= array("SPLDESC",
									"SPLCODE",
									"SPLDESC",
									"SPLADD1",
									"SPLSCOPE", 
									"SPLKOTA", 
									"SPLTELP");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_vendor->get_AllDataC($length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_vendor->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$SPLCODE	= $dataI['SPLCODE'];
				$SPLDESC	= stripslashes($dataI['SPLDESC']);
				$SPLADD1	= $dataI['SPLADD1'];
				$SPLSCOPE	= $dataI['SPLSCOPE'];
				$SPLKOTA	= $dataI['SPLKOTA'];
				$SPLTELP	= $dataI['SPLTELP'] ?: " - ";
				$SPLMAIL	= $dataI['SPLMAIL'] ?: " - ";
				$SPLPERS	= $dataI['SPLPERS'] ?: " - ";
				$SPLNPWP	= $dataI['SPLNPWP'] ?: " - ";
				$SPLSTAT	= $dataI['SPLSTAT'];
				
				$secUpd		= site_url('c_purchase/c_v3N/update/?id='.$this->url_encryption_helper->encode_url($SPLCODE));
				$secAPList	= site_url('c_purchase/c_v3N/aP_L15t/?id='.$this->url_encryption_helper->encode_url($SPLCODE));

				$secNActSpl = base_url().'index.php/__l1y/trashSpl/?id=';
				$NactID 	= "$secNActSpl~$SPLCODE";
				$secActSpl 	= base_url().'index.php/__l1y/acthSpl/?id=';
				$actID 		= "$secActSpl~$SPLCODE";

				$secDelSpl 	= base_url().'index.php/__l1y/delSpl/?id=';
				$delID 		= "$secDelSpl~$SPLCODE";

				$isDis 		= "disabled";
				$s_01 		= "SELECT APPROVER_1, APPROVER_2 FROM tbl_supplier_app WHERE SPLCODE = '$SPLCODE' LIMIT 1";
				$r_01 		= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$APPROVER_1 = $rw_01->APPROVER_1;
					$APPROVER_2 = $rw_01->APPROVER_2;

					if($APPROVER_1 != '' && $APPROVER_2 != '')
						$isDis = "";
				endforeach;
                                    
				if($SPLSTAT == 0) 
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlNact".$noU."' id='urlNact".$noU."' value='".$NactID."'>
									<input type='hidden' name='urlAct".$noU."' id='urlAct".$noU."' value='".$actID."'>
									<input type='hidden' name='urlAPList".$noU."' id='urlAPList".$noU."' value='".$secAPList."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='".$payableList."' onClick='APList(".$noU.")'>
										<i class='glyphicon glyphicon-time'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Aktifkan' onClick='reActivated(".$noU.")' ".$isDis.">
	                                    <i class='glyphicon glyphicon-repeat'></i>
	                                </a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='deleteDOC(".$noU.")' title='Delete'>
										<i class='fa fa-trash-o'></i>
									</a>
								   	</label>";
				}
				else
				{
					$secAction	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlNact".$noU."' id='urlNact".$noU."' value='".$NactID."'>
									<input type='hidden' name='urlAct".$noU."' id='urlAct".$noU."' value='".$actID."'>
									<input type='hidden' name='urlAPList".$noU."' id='urlAPList".$noU."' value='".$secAPList."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
										<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-warning btn-xs' title='".$payableList."' onClick='APList(".$noU.")'>
										<i class='glyphicon glyphicon-time'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-success btn-xs' title='Non aktifkan' onClick='NActivated(".$noU.")' ".$isDis.">
                                        <i class='glyphicon glyphicon-ok'></i>
                                    </a>
									<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Delete' disabled>
										<i class='fa fa-trash-o'></i>
									</a>
								   	</label>";
				}
							   
				$output['data'][] 	= array("$noU.",
										  	$dataI['SPLCODE'],
										  	"<strong>".stripslashes($dataI['SPLDESC'])."</strong>
									  		<div>
										  		<p class='text-muted'>
										  			".$SPLNPWP."
										  		</p>
										  	</div>",
										  	$dataI['SPLADD1']."<br>
									  		<strong><i class='fa fa-phone margin-r-5'></i> ".$Contact." </strong>
									  		<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			Telp. ".$SPLTELP. ", HP : ".$SPLPERS.", e-Mail : ".$SPLMAIL."
										  		</p>
										  	</div>",
										  	$dataI['SPLSCOPE'],
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_purchase/c_v3N/add_process');
			$data['backURL'] 		= site_url('c_purchase/c_v3N/');
			
			// GET MENU DESC
				$mnCode				= 'MN008';
				$data["MenuApp"] 	= 'MN008';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;

			$data['viewDocPattern'] = $this->m_vendor->getDataDocPat($mnCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN008';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $mnCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_purchase/v_vendor/v_vendor_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getVENDCATCODE($cinta) // OK
	{ 
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		$recordcountVCAT 	= $this->m_vendor->count_all_num_rowsVCAT($cinta);
		echo $recordcountVCAT;
	}
	
	function add_process() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SPLCODE = $this->input->post('SPLCODE');
			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE";
				$config['allowed_types'] 	= "pdf";
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

						if ($this->upload->do_upload('userfile'))
						{
							// $data[] 		= $this->upload->data();
							$upl_data 		= $this->upload->data();
							$fileName 		= $upl_data['file_name'];
							$srvURL 		= $_SERVER['SERVER_ADDR'];
							// $fileName 	= $file['file_name'];
							$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/$fileName";

							if($srvURL == '10.0.0.144')
							{
								$this->load->library('ftp');
				
								$config['hostname'] = 'sdbpplus.nke.co.id';
								$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
								$config['password'] = 'NKE@dmin2021';
								$config['debug']    = TRUE;
								
								$this->ftp->connect($config);
								
								$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/$fileName";

								if($this->ftp->list_files("./assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/") == FALSE) 
								{
									$this->ftp->mkdir("./assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/", 0777);
								}
								
								$this->ftp->upload($source, ".".$destination);
								
								$this->ftp->close();
							}

							$UPL_NUM 	= "USPL".date('YmdHis');
							$UPL_DATE 	= date('Y-m-d');
							$uplFile 	= ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $SPLCODE, "UPL_DATE" => $UPL_DATE, 
											"UPL_FILENAME" => $upl_data['file_name'], "UPL_FILESIZE" => $upl_data['file_size'],
											"UPL_FILETYPE" => $upl_data['file_type'], "UPL_FILEPATH" => $upl_data['file_path'], 
											"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_vendor->uplDOC_SPL($uplFile);
						}
						
					}
				}
			
			// ============================= End Upload File ========================================== //

			$vendcat = array('SPLCODE' 		=> $this->input->post('SPLCODE'),
							'SPLDESC'		=> htmlspecialchars($this->input->post('SPLDESC'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'SPLCAT'		=> $this->input->post('SPLCAT'),
							'SPLADD1'		=> htmlspecialchars($this->input->post('SPLADD1'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'SPLKOTA'		=> htmlspecialchars($this->input->post('SPLKOTA'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'SPLNPWP'		=> $this->input->post('SPLNPWP'),
							'SPLPERS'		=> $this->input->post('SPLPERS'),
							'SPLTELP'		=> $this->input->post('SPLTELP'),
							'SPLMAIL'		=> $this->input->post('SPLMAIL'),
							'SPLNOREK'		=> $this->input->post('SPLNOREK'),
							'SPLSCOPE'		=> $this->input->post('SPLSCOPE'),
							'SPLNMREK'		=> $this->input->post('SPLNMREK'),
							'SPLBANK'		=> $this->input->post('SPLBANK'),
							'SPLOTHR'		=> $this->input->post('SPLOTHR'),
							'SPLOTHR2'		=> $this->input->post('SPLOTHR2'),
							'SPLTOP'		=> $this->input->post('SPLTOP'),
							'SPLTOPD'		=> $this->input->post('SPLTOPD'),
							'SPLBNKBI'		=> $this->input->post('SPLBNKBI'),
							'SPLKTP'		=> $this->input->post('SPLKTP'),
							'SPLUSERN'		=> $this->input->post('SPLUSERN'),
							'SPLPASSW'		=> $this->input->post('SPLPASSW'),
							'SPL_INVACC'	=> $this->input->post('SPL_INVACC'),
							'SPLSTAT'		=> $this->input->post('SPLSTAT'));
			$this->m_vendor->add($vendcat);
						
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('SPLCODE');
				$MenuCode 		= 'MN008';
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
			
			$url			= site_url('c_purchase/c_v3N/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		$SPLCODE	= $_GET['id'];
		$SPLCODE	= $this->url_encryption_helper->decode_url($SPLCODE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_purchase/c_v3N/update_process');
			$data['backURL'] 		= site_url('c_purchase/c_v3N/');
			
			// GET MENU DESC
				$mnCode				= 'MN008';
				$data["MenuApp"] 	= 'MN008';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$MenuCode 				= 'MN008';
			$data['MenuCode'] 		= 'MN008';
			$getvendor 				= $this->m_vendor->get_vendor_by_code($SPLCODE)->row();
			
			$data['default']['SPLCODE']	= $getvendor->SPLCODE;
			$data['default']['SPLDESC']	= $getvendor->SPLDESC;
			$data['default']['SPLCAT']	= $getvendor->SPLCAT;
			$data['default']['SPLADD1']	= $getvendor->SPLADD1;
			$data['default']['SPLKOTA']	= $getvendor->SPLKOTA;
			$data['default']['SPLNPWP']	= $getvendor->SPLNPWP;
			$data['default']['SPLPERS']	= $getvendor->SPLPERS;
			$data['default']['SPLTELP']	= $getvendor->SPLTELP;
			$data['default']['SPLMAIL']	= $getvendor->SPLMAIL;
			$data['default']['SPLNOREK']= $getvendor->SPLNOREK;
			$data['default']['SPLSCOPE']= $getvendor->SPLSCOPE;
			$data['default']['SPLNMREK']= $getvendor->SPLNMREK;
			$data['default']['SPLBANK']	= $getvendor->SPLBANK;
			$data['default']['SPLOTHR']	= $getvendor->SPLOTHR;
			$data['default']['SPLOTHR2']= $getvendor->SPLOTHR2;
			$data['default']['SPLTOP']	= $getvendor->SPLTOP;
			$data['default']['SPLTOPD']	= $getvendor->SPLTOPD;
			$data['default']['SPLSTAT']	= $getvendor->SPLSTAT;
			$data['default']['SPLBNKBI']= $getvendor->SPLBNKBI;
			$data['default']['SPLKTP']	= $getvendor->SPLKTP;
			$data['default']['SPLUSERN']= $getvendor->SPLUSERN;
			$data['default']['SPLPASSW']= $getvendor->SPLPASSW;
			$data['default']['TOT_PO']	= $getvendor->TOT_PO;
			$data['default']['SPL_INVACC']	= $getvendor->SPL_INVACC;
			$data['default']['Patt_Number']	= $getvendor->Patt_Number;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getvendor->SPLCODE;
				$MenuCode 		= 'MN008';
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
			
			$this->load->view('v_purchase/v_vendor/v_vendor_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // OK
	{
		$this->load->model('m_purchase/m_vendor/m_vendor', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$SPLCODE	= $this->input->post('SPLCODE');

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE";
				$config['allowed_types'] 	= "pdf";
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

						if ($this->upload->do_upload('userfile'))
						{
							// $data[] 		= $this->upload->data();
							$upl_data 		= $this->upload->data();
							$fileName 		= $upl_data['file_name'];
							$srvURL 		= $_SERVER['SERVER_ADDR'];
							// $fileName 	= $file['file_name'];
							$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/$fileName";

							if($srvURL == '10.0.0.144')
							{
								$this->load->library('ftp');
				
								$config['hostname'] = 'sdbpplus.nke.co.id';
								$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
								$config['password'] = 'NKE@dmin2021';
								$config['debug']    = TRUE;
								
								$this->ftp->connect($config);
								
								$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/$fileName";

								if($this->ftp->list_files("./assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/") == FALSE) 
								{
									$this->ftp->mkdir("./assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/", 0777);
								}
								
								$this->ftp->upload($source, ".".$destination);
								
								$this->ftp->close();
							}

							$UPL_NUM 	= "USPL".date('YmdHis');
							$UPL_DATE 	= date('Y-m-d');
							$uplFile 	= ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $SPLCODE, "UPL_DATE" => $UPL_DATE, 
											"UPL_FILENAME" => $upl_data['file_name'], "UPL_FILESIZE" => $upl_data['file_size'],
											"UPL_FILETYPE" => $upl_data['file_type'], "UPL_FILEPATH" => $upl_data['file_path'], 
											"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_vendor->uplDOC_SPL($uplFile);
						}
						
					}
				}
			
			// ============================= End Upload File ========================================== //
			
			$vendor 	= array('SPLDESC'		=> htmlspecialchars($this->input->post('SPLDESC'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
								'SPLCAT'		=> $this->input->post('SPLCAT'),
								'SPLADD1'		=> htmlspecialchars($this->input->post('SPLADD1'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
								'SPLKOTA'		=> htmlspecialchars($this->input->post('SPLKOTA'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
								'SPLNPWP'		=> $this->input->post('SPLNPWP'),
								'SPLPERS'		=> $this->input->post('SPLPERS'),
								'SPLTELP'		=> $this->input->post('SPLTELP'),
								'SPLMAIL'		=> $this->input->post('SPLMAIL'),
								'SPLNOREK'		=> $this->input->post('SPLNOREK'),
								'SPLSCOPE'		=> $this->input->post('SPLSCOPE'),
								'SPLNMREK'		=> $this->input->post('SPLNMREK'),
								'SPLBANK'		=> $this->input->post('SPLBANK'),
								'SPLOTHR'		=> $this->input->post('SPLOTHR'),
								'SPLOTHR2'		=> $this->input->post('SPLOTHR2'),
								'SPLTOP'		=> $this->input->post('SPLTOP'),
								'SPLTOPD'		=> $this->input->post('SPLTOPD'),
								'SPLBNKBI'		=> $this->input->post('SPLBNKBI'),
								'SPLKTP'		=> $this->input->post('SPLKTP'),
								'SPLUSERN'		=> $this->input->post('SPLUSERN'),
								'SPL_INVACC'	=> $this->input->post('SPL_INVACC'),
								'SPLPASSW'		=> md5($this->input->post('SPLPASSW')),
								'SPLSTAT'		=> $this->input->post('SPLSTAT'));
			$this->m_vendor->update($SPLCODE, $vendor);

			// START : SAVE APPROVE HISTORY
				$this->load->model('m_updash/m_updash', '', TRUE);
				
				$AH_APPLEV		= $this->input->post('APP_LEVEL');
				$AH_ISLAST		= $this->input->post('IS_LAST');
				$insAppHist 	= array('PRJCODE'		=> $SPLCODE,
										'AH_CODE'		=> $SPLCODE,
										'AH_APPLEV'		=> $AH_APPLEV,
										'AH_APPROVER'	=> $this->session->userdata['Emp_ID'],
										'AH_APPROVED'	=> date('Y-m-d H:i:s'),
										'AH_NOTES'		=> "",
										'AH_ISLAST'		=> $AH_ISLAST);										
				$this->m_updash->insAppHist($insAppHist);
			// END : SAVE APPROVE HISTORY

			if($AH_ISLAST == 0)
			{
				$vendor 	= array('SPLSTAT'		=> 0);
				$this->m_vendor->update($SPLCODE, $vendor);
			}
			else
			{
				$vendor 	= array('SPLSTAT'		=> 1);
				$this->m_vendor->update($SPLCODE, $vendor);
			}

			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $SPLCODE;
				$MenuCode 		= 'MN008';
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
			
			$url			= site_url('c_purchase/c_v3N/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function aP_L15t()
	{
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$SPLCODE	= $_GET['id'];
		$SPLCODE	= $this->url_encryption_helper->decode_url($SPLCODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['SPLCODE'] 	= $SPLCODE;
			
			$data['countAP']	= $this->m_vendor->count_all_AP($SPLCODE);
			$data['vwAP'] 		= $this->m_vendor->get_all_AP($SPLCODE)->result();
			$gettotAP 			= $this->m_vendor->get_all_TOTAP($SPLCODE)->row();
			$data['REMTOT']		= $gettotAP->REMTOT;
							
			$this->load->view('v_purchase/v_vendor/v_vendor_aplist', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function gLastCd()
	{
		$SPLCAT		= $this->input->post('SPLCAT');
		
		// $sql 	= "tbl_supplier WHERE SPLCAT = '$SPLCAT'";
		// $result = $this->db->count_all($sql);
		// $myMax 	= $result+1;
		
		$Pattern_Length	= 5;
		$sql 	= "SELECT RIGHT(SPLCODE, $Pattern_Length) AS KODE 
					FROM tbl_supplier 
					WHERE SPLCAT = '$SPLCAT'
					ORDER BY SPLCODE DESC LIMIT 1";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $r):
				$KODE 	= intval($r->KODE);
			endforeach;
			$myMax 	= $KODE + 1;
		}
		else
		{
			$myMax = 1;
		}

		$len = strlen($myMax);
		$nol = '';
		if($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		
		$lastPatt 	= $nol.$myMax;
		$DocNo 		= "$SPLCAT$lastPatt";

		echo $DocNo;
	}
	
	function chkName()
	{
		$SPLDESC	= $this->input->post('SPLDESC');
		
		$s_00 		= "tbl_supplier WHERE SPLDESC = '$SPLDESC'";
		$r_00 		= $this->db->count_all($s_00);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
			$alert1 = "Nama yang Anda masukan sudah ada, silahkan ganti dengan nama lain!";
		else
			$alert1 = "Nama yang Anda masukan sudah ada, silahkan ganti dengan nama lain!";

		if($r_00 > 0)
			$sendDt = "1~$alert1";
		else
			$sendDt = "0~$alert1";

		echo $sendDt;
	}
	
	function chgSTAT1()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $SPLCODE	= $colExpl[0];
        $APPSTAT	= $colExpl[1];
        $APPROVER_1	= $colExpl[2];
        $APPROVED_1 = date('Y-m-d H:i:s');

        $s_00		= "tbl_supplier_app WHERE SPLCODE = '$SPLCODE'";
		$r_00 		= $this->db->count_all($s_00);
		if($r_00 == 0)
		{
	        $sqlIns	= "INSERT INTO tbl_supplier_app (SPLCODE, APPROVER_1, APPROVED_1)
	        			VALUES ('$SPLCODE', '$APPROVER_1', '$APPROVED_1')";
			$this->db->query($sqlIns);
		}
		else
		{
			if($APPSTAT == 1)
			{
		        $sqlUpd	= "UPDATE tbl_supplier_app SET APPROVER_1 ='$APPROVER_1', APPROVED_1 ='$APPROVED_1' WHERE SPLCODE = '$SPLCODE'";
				$this->db->query($sqlUpd);

				$s_01 	= "SELECT APPROVER_1, APPROVER_2 FROM tbl_supplier_app WHERE SPLCODE = '$SPLCODE'";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$APPROVER_1 = $rw_01->APPROVER_1;
					$APPROVER_2 = $rw_01->APPROVER_2;

					if($APPROVER_1 != '' && $APPROVER_2 != '')
					{
						$u_01	= "UPDATE tbl_supplier SET SPLSTAT = 1 WHERE SPLCODE = '$SPLCODE'";
						$this->db->query($u_01);
					}
				endforeach;
			}
			else
			{
		        $sqlUpd	= "UPDATE tbl_supplier_app SET APPROVER_1 ='', APPROVED_1 ='' WHERE SPLCODE = '$SPLCODE'";
				$this->db->query($sqlUpd);

				$u_01	= "UPDATE tbl_supplier SET SPLSTAT = 0 WHERE SPLCODE = '$SPLCODE'";
				$this->db->query($u_01);
			}
		}
		echo "$APPSTAT";
	}
	
	function chgSTAT2()
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
        $SPLCODE	= $colExpl[0];
        $APPSTAT	= $colExpl[1];
        $APPROVER_2	= $colExpl[2];
        $APPROVED_2 = date('Y-m-d H:i:s');

        $s_00		= "tbl_supplier_app WHERE SPLCODE = '$SPLCODE'";
		$r_00 		= $this->db->count_all($s_00);
		if($r_00 == 0)
		{
	        $sqlIns	= "INSERT INTO tbl_supplier_app (SPLCODE, APPROVER_2, APPROVED_2)
	        			VALUES ('$SPLCODE', '$APPROVER_2', '$APPROVED_2')";
			$this->db->query($sqlIns);
		}
		else
		{
			if($APPSTAT == 1)
			{
		        $sqlUpd	= "UPDATE tbl_supplier_app SET APPROVER_2 ='$APPROVER_2', APPROVED_2 ='$APPROVED_2' WHERE SPLCODE = '$SPLCODE'";
				$this->db->query($sqlUpd);

				$s_01 	= "SELECT APPROVER_1, APPROVER_2 FROM tbl_supplier_app WHERE SPLCODE = '$SPLCODE'";
				$r_01 	= $this->db->query($s_01)->result();
				foreach($r_01 as $rw_01) :
					$APPROVER_1 = $rw_01->APPROVER_1;
					$APPROVER_2 = $rw_01->APPROVER_2;

					if($APPROVER_1 != '' && $APPROVER_2 != '')
					{
						$u_01	= "UPDATE tbl_supplier SET SPLSTAT = 1 WHERE SPLCODE = '$SPLCODE'";
						$this->db->query($u_01);
					}
				endforeach;
			}
			else
			{
		        $sqlUpd	= "UPDATE tbl_supplier_app SET APPROVER_2 ='', APPROVED_2 ='' WHERE SPLCODE = '$SPLCODE'";
				$this->db->query($sqlUpd);

				$u_01	= "UPDATE tbl_supplier SET SPLSTAT = 0 WHERE SPLCODE = '$SPLCODE'";
				$this->db->query($u_01);
			}
		}
		echo "$APPSTAT";
	}

	function trashFile()
	{
		$SPLCODE	= $this->input->post("SPLCODE");
		$fileName	= $this->input->post("fileName");
		
		$this->m_vendor->delUPL_SPL($SPLCODE, $fileName); // Delete File
		
		// Delete file in path folder
			$path = "assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/$fileName";
			unlink($path);
	}

	function downloadFile()
	{
		$this->load->helper('download');

		$fileName 	= $_GET['file'];
		$SPLCODE 	= $_GET['SPLCODE'];
		$path 		= "assets/AdminLTE-2.0.5/doc_center/uploads/SUPPLIER/$SPLCODE/$fileName";
		force_download($path, NULL);
	}
}