<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Agustus 2023
 * File Name	= C_grntf1l3.php
 * Location		= -
*/

class C_grntf1l3 extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_finance/m_gf/m_gf', '', TRUE);
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
 	public function index() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_grntf1l3/grntFile/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function grntFile() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);

			$data["MenuCode"] 		= 'MN157';
			
			// GET MENU DESC
				$mnCode				= 'MN157';
				$data["MenuApp"] 	= 'MN157';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$num_rows 				= $this->m_gf->count_all_gf();
			$data["cData"] 			= $num_rows;	 
			$data['vData'] 			= $this->m_gf->get_all_gf()->result();
			
			$this->load->view('v_finance/v_gf/v_gf', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function gfile_l44d() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';	
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN157';
				$data["MenuApp"] 	= 'MN157';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_finance/c_grntf1l3/add_process');
			$data['backURL'] 		= site_url('c_finance/c_grntf1l3/');
			
			$MenuCode 			= 'MN157';
			$data["MenuCode"] 	= 'MN157';
			
			$this->load->view('v_finance/v_gf/v_gf_form', $data);
		}
		else
		{
			rredirect('__l1y');
		}
	}
	
	function add_process() // GOOD
	{
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();
	
			$this->load->model('m_projectlist/m_projectlist', '', TRUE);
			
			$MenuCode 		= 'MN157';
			
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$GF_NUM			= date('ymdHis');
			$GF_DATES	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('GF_DATES'))));
			$GF_DATEE	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('GF_DATEE'))));
			$GF_STATUS		= $this->input->post('GF_STATUS');
			$GF_CODE		= $this->input->post('GF_CODE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$GF_FILENAME 	= $this->input->post('GF_FILENAME');
			$SPLCODE		= $this->input->post('SPLCODE');

			$SPLDESC 		= "";
	        $sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
		    $resSPL 		= $this->db->query($sqlSPL)->result();
		    foreach($resSPL as $rowSPL) :
		        $SPLDESC 	= $rowSPL->SPLDESC;
		    endforeach;

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE";
				$config['allowed_types'] 	= "jpg|jpeg|png|gif|pdf";
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
							$GF_FILENAME 	= $fileName;
							$srvURL 		= $_SERVER['SERVER_ADDR'];
							// $fileName 	= $file['file_name'];
							$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/$fileName";

							if($srvURL == '10.0.0.144')
							{
								$this->load->library('ftp');
				
								$config['hostname'] = 'sdbpplus.nke.co.id';
								$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
								$config['password'] = 'NKE@dmin2021';
								$config['debug']    = TRUE;
								
								$this->ftp->connect($config);
								
								$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/$fileName";

								if($this->ftp->list_files("./assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/") == FALSE) 
								{
									$this->ftp->mkdir("./assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/", 0777);
								}
								
								$this->ftp->upload($source, ".".$destination);
								
								$this->ftp->close();
							}

							$UPL_NUM  = "UPL_BG".date('YmdHis');
							$UPL_DATE = date('Y-m-d');
							$uplFile  = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $GF_NUM, "REF_CODE" => $GF_CODE,
										"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
										"UPL_FILENAME" => $upl_data['file_name'], "UPL_FILESIZE" => $upl_data['file_size'],
										"UPL_FILETYPE" => $upl_data['file_type'], "UPL_FILEPATH" => $upl_data['file_path'], 
										"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_gf->uplDOC_TRX($uplFile);
						}
						
					}
				}
			
			// ============================= End Upload File ========================================== //


			$InsGF	= array('GF_NUM'			=> $GF_NUM,
							'GF_CODE'			=> $this->input->post('GF_CODE'),
							'GF_NAME'			=> $this->input->post('GF_NAME'),
							'GF_DATES'			=> $GF_DATES,
							'GF_DATEE'			=> $GF_DATEE,
							'GF_PENJAMIN'		=> htmlspecialchars($this->input->post('GF_PENJAMIN'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
							'GF_NILAI_JAMINAN'	=> $this->input->post('GF_NILAI_JAMINAN'),
							'GF_FILENAME'		=> $GF_FILENAME,
							'PRJCODE'			=> $this->input->post('PRJCODE'),
							'SPLCODE'			=> $this->input->post('SPLCODE'),
							'SPLDESC'			=> $SPLDESC,
							'WO_NUM'			=> $this->input->post('WO_NUM'),
							'WO_CODE'			=> $this->input->post('WO_CODE'),
							'WO_VALUE'			=> $this->input->post('WO_VALUE'),
							'GF_STATDOC'		=> $this->input->post('GF_STATDOC'),
							'GF_STATUS'			=> $this->input->post('GF_STATUS'));
			$this->m_gf->add($InsGF);

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "GF_NUM",
										'DOC_CODE' 		=> $GF_NUM,
										'DOC_STAT' 		=> $GF_STATUS,
										'PRJCODE' 		=> $PRJCODE,
										'CREATERNM'		=> $completeName,
										'TBLNAME'		=> "tbl_gfile");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_finance/c_grntf1l3/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // GOOD
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$GF_NUM				= $_GET['id'];
		$GF_NUM				= $this->url_encryption_helper->decode_url($GF_NUM);
		$data["MenuCode"] 	= 'MN157';
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN157';
				$data["MenuApp"] 	= 'MN157';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['form_action']	= site_url('c_finance/c_grntf1l3/update_process');
			$data['backURL'] 		= site_url('c_finance/c_grntf1l3/');
			
			$getGF 					= $this->m_gf->get_gf($GF_NUM)->row();

			$data['GF_NUM']				= $getGF->GF_NUM;
			$data['GF_CODE']			= $getGF->GF_CODE;
			$data['GF_NAME']			= $getGF->GF_NAME;
			$data['GF_DATES']			= $getGF->GF_DATES;
			$data['GF_DATEE'] 			= $getGF->GF_DATEE;
			$data['GF_PENJAMIN'] 		= $getGF->GF_PENJAMIN;
			$data['GF_NILAI_JAMINAN'] 	= $getGF->GF_NILAI_JAMINAN;
			$data['GF_FILENAME'] 		= $getGF->GF_FILENAME;
			$data['PRJCODE'] 			= $getGF->PRJCODE;
			$PRJCODE 					= $getGF->PRJCODE;
			$data['SPLCODE'] 			= $getGF->SPLCODE;
			$data['WO_NUM'] 			= $getGF->WO_NUM;
			$data['WO_CODE'] 			= $getGF->WO_CODE;
			$data['WO_VALUE'] 			= $getGF->WO_VALUE;
			$data['GF_STATDOC'] 		= $getGF->GF_STATDOC;
			$data['GF_STATUS'] 			= $getGF->GF_STATUS;
			$GF_STATUS 					= $getGF->GF_STATUS;
			
			$this->load->view('v_finance/v_gf/v_gf_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // GOOD
	{
		$this->load->model('m_setting/m_tax/m_tax', '', TRUE);

		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");

			$this->db->trans_begin();

			$GF_NUM			= $this->input->post('GF_NUM');
			$GF_DATES	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('GF_DATES'))));
			$GF_DATEE	 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('GF_DATEE'))));
			$GF_STATUS		= $this->input->post('GF_STATUS');
			$GF_CODE		= $this->input->post('GF_CODE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$GF_FILENAME 	= $this->input->post('GF_FILENAME');
			$SPLCODE		= $this->input->post('SPLCODE');

			$SPLDESC 		= "";
	        $sqlSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
		    $resSPL 		= $this->db->query($sqlSPL)->result();
		    foreach($resSPL as $rowSPL) :
		        $SPLDESC 	= $rowSPL->SPLDESC;
		    endforeach;

			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$AH_CODE		= $GF_NUM;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			$AH_ISLAST		= $this->input->post('IS_LAST');

			if($GF_STATUS == 3 && $AH_ISLAST == 1)
				$GF_STATUS 	= 3;
			elseif($GF_STATUS == 3 && $AH_ISLAST == 0)
				$GF_STATUS 	= 7;

			$UpdGF			= array('GF_CODE'			=> $this->input->post('GF_CODE'),
									'GF_NAME'			=> $this->input->post('GF_NAME'),
									'GF_DATES'			=> $GF_DATES,
									'GF_DATEE'			=> $GF_DATEE,
									'GF_PENJAMIN'		=> htmlspecialchars($this->input->post('GF_PENJAMIN'), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5),
									'GF_NILAI_JAMINAN'	=> $this->input->post('GF_NILAI_JAMINAN'),
									'PRJCODE'			=> $this->input->post('PRJCODE'),
									'SPLCODE'			=> $this->input->post('SPLCODE'),
									'SPLDESC'			=> $SPLDESC,
									'WO_NUM'			=> $this->input->post('WO_NUM'),
									'WO_CODE'			=> $this->input->post('WO_CODE'),
									'WO_VALUE'			=> $this->input->post('WO_VALUE'),
									'GF_STATDOC'		=> $this->input->post('GF_STATDOC'),
									'GF_STATUS'			=> $GF_STATUS);						
			$this->m_gf->update($GF_NUM, $UpdGF);

			// START : UPDATE STATUS
				$completeName 	= $this->session->userdata['completeName'];
				$paramStat 		= array('PM_KEY' 		=> "GF_NUM",
										'DOC_CODE' 		=> $GF_NUM,
										'DOC_STAT' 		=> $GF_STATUS,
										'PRJCODE' 		=> $PRJCODE,
										//'CREATERNM'	=> $completeName,
										'TBLNAME'		=> "tbl_gfile");
				$this->m_updash->updateStatus($paramStat);
			// END : UPDATE STATUS

			// ============================= Start Upload File ========================================== //
				$fileUpload	= null;
				$files 		= $_FILES;
				$count 		= count($_FILES['userfile']['name']);

				if (!file_exists("assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE")) {
					mkdir("assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE", 0777, true);
				}
				
				$config['upload_path'] 		= "assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE";
				$config['allowed_types'] 	= "jpg|jpeg|png|gif|pdf";
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
							$GF_FILENAME 	= $fileName;
							$srvURL 		= $_SERVER['SERVER_ADDR'];
							// $fileName 	= $file['file_name'];
							$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/$fileName";

							if($srvURL == '10.0.0.144')
							{
								$this->load->library('ftp');
				
								$config['hostname'] = 'sdbpplus.nke.co.id';
								$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
								$config['password'] = 'NKE@dmin2021';
								$config['debug']    = TRUE;
								
								$this->ftp->connect($config);
								
								$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/$fileName";

								if($this->ftp->list_files("./assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/") == FALSE) 
								{
									$this->ftp->mkdir("./assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/", 0777);
								}
								
								$this->ftp->upload($source, ".".$destination);
								
								$this->ftp->close();
							}

							$UPL_NUM  = "UPL_BG".date('YmdHis');
							$UPL_DATE = date('Y-m-d');
							$uplFile  = ["UPL_NUM" => $UPL_NUM, "REF_NUM" => $GF_NUM, "REF_CODE" => $GF_CODE,
										"PRJCODE" => $PRJCODE, "UPL_DATE" => $UPL_DATE, 
										"UPL_FILENAME" => $upl_data['file_name'], "UPL_FILESIZE" => $upl_data['file_size'],
										"UPL_FILETYPE" => $upl_data['file_type'], "UPL_FILEPATH" => $upl_data['file_path'], 
										"UPL_CREATER" => $DefEmp_ID, "UPL_CREATED" => date('Y-m-d H:i:s')];
							$this->m_gf->uplDOC_TRX($uplFile);

							$UpdGF			= array('GF_FILENAME' => $GF_FILENAME);						
							$this->m_gf->update($GF_NUM, $UpdGF);
						}
						
					}
				}
			
			// ============================= End Upload File ========================================== //
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp 	= $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_finance/c_grntf1l3/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getCode() // GOOD
	{
		$collData	= $_POST['collID'];
		$colExpl	= explode("~", $collData);
		$WHCODE 	= $colExpl[1];

		$sqlWH 		= "tbl_gfile WHERE GF_CODE = '$WHCODE'";
		$resWH 		= $this->db->count_all($sqlWH);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$alert1	= "Kode Gudang sudah digunakan.";
		}
		else
		{
			$alert1	= "Warehouse code already used.";
		}
		$resultWH	= "$resWH~$alert1";
		echo $resultWH;
	}

	function s3l4llW_0L() // GOOD
	{
		$PRJCODE = $this->input->post('PRJCODE');
		$SPLCODE = $this->input->post('SPLCODE');
		
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
									"WO_CODE", 
									"WO_DATE", 
									"WO_NOTE",
									"WO_STARTD",
									"WO_ENDD",
									"WO_VALUE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_gf->get_AllDataWOC($PRJCODE, $SPLCODE, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_gf->get_AllDataWOL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$WO_NUM 		= $dataI['WO_NUM'];
				$WO_CODE 		= $dataI['WO_CODE'];
				$WO_DATE 		= $dataI['WO_DATE'];
				$WO_DATEV		= date('d F Y', strtotime($WO_DATE));
				$WO_STARTD 		= $dataI['WO_STARTD'];
				$WO_STARTD		= date('d M Y', strtotime($WO_STARTD));
				$WO_ENDD 		= $dataI['WO_ENDD'];
				$WO_ENDD		= date('d M Y', strtotime($WO_ENDD));
				$PRJCODE 		= $dataI['PRJCODE'];
				$JOBCODEID 		= $dataI['JOBCODEID'];
				$SPLDESC 		= $dataI['SPLDESC'];
				$WO_NOTE 		= wordwrap($dataI['WO_NOTE'], 60, "<br>", TRUE);
				$WO_VALUE 		= $dataI['WO_VALUE'];
				$DP_PERC		= $dataI['WO_DPPER'];
				$WO_VALPPN 		= $dataI['WO_VALPPN'];
				$WO_CATEG 		= $dataI['WO_CATEG'];
				$REF_TYPE		= 'WO';

				if($WO_CATEG == 'MDR')
				{
					$WO_CATEGD	= 'Mandor';
				}
				elseif($WO_CATEG == 'SUB')
				{
					$WO_CATEGD 	= 'Subkon';
				}
				else
				{
					$WO_CATEGD 	= 'Sewa Alat';
				}
				
				// GET JOB DETAIL
					$JOBDESC		= '';
					$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
					$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
					foreach($resJOBDESC as $rowJOBDESC) :
						$JOBDESC	= $rowJOBDESC->JOBDESC;
					endforeach;

				$chkBox	= "<input type='radio' name='chk1' id='chk1' value='".$WO_NUM."|".$WO_CODE."|".$WO_VALUE."' onClick='pickThisWO(this);' />";
				
				$output['data'][] 	= array("$chkBox",
										  	"<div style='white-space:nowrap'>$WO_CODE</div>",
										  	"<div style='white-space:nowrap'>$WO_DATEV</div>",
										 	"<div style='white-space:nowrap'>
												<strong><i class='fa fa-user margin-r-5'></i> $SPLDESC / $WO_CATEGD</strong>
											</div>
										  	<div style='margin-left: 20px;'>
										  		".$WO_NOTE."
										  	</div>",
										  	number_format($WO_VALUE,2));
				$noU			= $noU + 1;
			}
				
			/*$output['data'][] 	= array("A",
									  	"B",
									  	"C",
									 	"D",
									  	"E",
									  	"F",
									  	"G");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function trashFile()
	{
		$GF_NUM		= $this->input->post("GF_NUM");
		$PRJCODE	= $this->input->post("PRJCODE");
		// $index 		= $this->input->post("indexRow");
		$fileName	= $this->input->post("fileName");

		/* Change PR_DOC to array => upd: 2022-09-16
			$arrDOC = explode(", ", $PR_DOC);
			foreach($arrDOC as $r => $val):
				$getarr[] = $val;
			endforeach;

			unset($getarr[$index]); // Delete index array
		---------------------- End Hidden --------------------- */
		
		$this->m_gf->delUPL_DOC($GF_NUM, $PRJCODE, $fileName); // Delete File
		
		// Delete file in path folder
			$path = "assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/$fileName";
			unlink($path);
	}

	function downloadFile()
	{
		$this->load->helper('download');

		$fileName 	= $_GET['file'];
		$PRJCODE 	= $_GET['prjCode'];
		$path 		= "assets/AdminLTE-2.0.5/doc_center/uploads/GFile/$PRJCODE/$fileName";
		force_download($path, NULL);
	}
}