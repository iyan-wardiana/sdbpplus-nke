<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= C_outapprove.php
 * Location		= -
*/

class C_outapprove  extends CI_Controller  
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	// Start : Index tiap halaman
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/c_outapprove/prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function prjlist($offset=0)
	{
		$this->load->model('m_finance/m_outapprove/m_outapprove', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 		= 'Project List';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			
			$num_rows 				= $this->m_outapprove->count_all_project($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
			$config 				= array();
			$config["total_rows"] 	= $num_rows;
			$config["per_page"] 	= 20;
			$config["uri_segment"] 	= 3;
			$config['cur_page']		= $offset;
				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close']	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close']	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open']		= '<li>';
			$config['num_tag_close']	= '</li>';
			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';
			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data['vewproject'] 		= $this->m_outapprove->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_finance/v_outapprove/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_last_ten_Alloutapprove($offset=0)
	{
		$this->load->model('m_finance/m_outapprove/m_outapprove', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$sqlGS		= "SELECT isUpdOutApp FROM tglobalsetting";
			$sqlResGS	= $this->db->query($sqlGS)->result();
			foreach($sqlResGS as $rowGS) :
				$isUpdOutApp	= $rowGS->isUpdOutApp;
			endforeach;
			
			$DefProj_Code	= $PRJCODE;
			
			$empID 			= $this->session->userdata('Emp_ID');
			$Emp_DeptCode 	= $this->session->userdata('Emp_DeptCode');
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Outstanding Approve';
			$data['main_view'] 	= 'v_help/v_track_outapprove/track_outapprove';
			$data['srch_url'] 	= site_url('c_finance/c_outapprove/get_last_ten_Alloutapprove_src/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['srch_again']	= site_url('c_finance/c_outapprove/get_last_ten_Alloutapprove/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['moffset'] 	= $offset;
			$data['perpage'] 	= 20;
			$data['theoffset'] 	= 0;
			
			$data['selSearchproj_Code'] = $PRJCODE;
			$data['PRJCODE'] 			= $PRJCODE;
			$data['selSearchType'] 		= '';
			$data['txtSearch'] 			= '';
			$data['SelIsApprove'] 		= 0;
			$data["selSearchTypex"] 	= '';
			$data['hideSrch']			= 0;
			$data['link'] 				= array('link_back' => anchor('c_finance/C_outapprove/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_finance/C_outapprove/');
			
			date_default_timezone_set("Asia/Jakarta");
			
			$dateNow = date('Y-m-d H:i:s');
			$sqlUpdDate	= "UPDATE tout_approve SET OA_Update = '$dateNow'";
			$this->db->query($sqlUpdDate);
			
			$LHint = $this->session->userdata('log_passHint');
			if($LHint == 'DH' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'UP' || $LHint == 'EA' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR') // QUERY - 2
			{
				$selSearchType = "PD_HD";
				$txtSearch = "";
			}
			elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF') // QUERY - 2
			{
				$selSearchType = "SPPHD";
				$txtSearch = "";
			}
			
			$this->load->view('v_finance/v_outapprove/track_outapprove', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_last_ten_Alloutapprove_src($offset=0)
	{
		$this->load->model('m_finance/m_outapprove/m_outapprove', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$sqlGS		= "SELECT isUpdOutApp FROM tglobalsetting";
			$sqlResGS	= $this->db->query($sqlGS)->result();
			foreach($sqlResGS as $rowGS) :
				$isUpdOutApp	= $rowGS->isUpdOutApp;
			endforeach;
			
			$PRJCODE		= $this->input->post('selSearchproj_Code');
			$DefProj_Code	= $this->input->post('selSearchproj_Code');
			$empID 			= $this->session->userdata('Emp_ID');
			$Emp_DeptCode 	= $this->session->userdata('Emp_DeptCode');
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Outstanding Approve';
			$data['main_view'] 	= 'v_help/v_track_outapprove/track_outapprove';
			$data['srch_url'] 	= site_url('c_finance/c_outapprove/get_last_ten_Alloutapprove_src/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['srch_again']	= site_url('c_finance/c_outapprove/get_last_ten_Alloutapprove/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['moffset'] 	= $offset;
			$data['perpage'] 	= 20;
			$data['theoffset'] 	= 0;
			
			$data['selSearchproj_Code'] = $PRJCODE;
			$data['PRJCODE'] 			= $PRJCODE;
			$data['def_ProjCode'] 		= $this->input->post('selSearchproj_Code');
			$data['selSearchType'] 		= $this->input->post('selSearchType');
			$data['txtSearch'] 			= $this->input->post('txtSearch');
			$data['SelIsApprove'] 		= $this->input->post('SelIsApprove');
			$data["selSearchTypex"] 	= $this->input->post('selSearchType');
			$data["isexcel"] 			= $this->input->post('isexcel');
			$data['hideSrch']			= 1;
			$data['link'] 				= array('link_back' => anchor('c_finance/C_outapprove/','<input type="button" class="btn btn-primary" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			date_default_timezone_set("Asia/Jakarta");
			
			$dateNow = date('Y-m-d H:i:s');
			$sqlUpdDate	= "UPDATE tout_approve SET OA_Update = '$dateNow'";
			$this->db->query($sqlUpdDate);
			
			$LHint = $this->session->userdata('log_passHint');
			if($LHint == 'DH' || $LHint == 'PRY' || $LHint == 'HR' || $LHint == 'RN' || $LHint == 'LN' || $LHint == 'MD' || $LHint == 'MG' || $LHint == 'UP' || $LHint == 'EA' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR') // QUERY - 2
			{
				$selSearchType = "PD_HD";
				$txtSearch = "";
			}
			elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF') // QUERY - 2
			{
				$selSearchType = "SPPHD";
				$txtSearch = "";
			}
			
			$isexcel		= $this->input->post('isexcel');
			if($isexcel == 0)
				$this->load->view('v_finance/v_outapprove/outapprove_list', $data);
			else				
				$this->load->view('v_finance/v_outapprove/outapprove_list_excel', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function openDocument($offset=0)
	{
		$sqlGS		= "SELECT isUpdOutApp FROM tglobalsetting";
		$sqlResGS	= $this->db->query($sqlGS)->result();
		foreach($sqlResGS as $rowGS) :
			$isUpdOutApp	= $rowGS->isUpdOutApp;
		endforeach;
		if ($this->session->userdata('login') == TRUE)
		{
			$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
			$DefProj_Code	= $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
			
			// Secure URL
			//$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_help/c_track_outapprove'),'get_last_ten_Alloutapprove');
			//$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_help/c_track_outapprove'),'index');
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_help/c_track_outapprove'),'index');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_help/c_track_outapprove'),'get_last_ten_Alloutapprove_src');
			$data['showIndexGD'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_help/c_track_outapprove'),'get_last_ten_Alloutapprove');
			
			$empID 			= $this->session->userdata('Emp_ID');
			$Emp_DeptCode 	= $this->session->userdata('Emp_DeptCode');
			
			$data['title'] = $MyAppName;
			$data['h2_title'] = 'Outstanding Approve';
			$data['main_view'] = 'v_help/v_track_outapprove/track_outapprove';
			$data['moffset'] = $offset;
			$data['perpage'] = 20;
			$data['theoffset'] = 0;	
			
			if (isset($_POST['submit']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
				
				$dataSessSrc = array(
						'selSearchproj_Code' => $DefProj_Code,
						'selSearchType' => $this->input->post('selSearchType'),
						'txtSearch' => $this->input->post('txtSearch'));
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				
				$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			}
			else
			{
				$selSearchType      = '';
				$txtSearch        	= '';
				
				$dataSessSrc = array(
						'selSearchproj_Code' => $DefProj_Code,
						'selSearchType' => $selSearchType,
						'txtSearch' => $txtSearch);
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			}
			
			// Membaca, Meng-insert dan mem-view seluruh isi tabel
				// Check Last Update
				date_default_timezone_set("Asia/Jakarta");
				
				$sqlGLU		= "SELECT OA_Update FROM tout_approve GROUP BY OA_Update";
				$sqlResGLU	= $this->db->query($sqlGLU)->result();
				foreach($sqlResGLU as $rowGLU) :
					$OA_LastUpdate	= $rowGLU->OA_Update;
				endforeach;
				
				$dateStarta	= date('Y-m-d H:i:s');
				$dateStart 	= date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateStarta)));
				
				$dateNow	= date('Y-m-d H:i:s');
				//echo "Awal : $dateStarta = $dateStart -> $dateNow<br>";
				//return false;
				// 1. Cara pertama untuk ambil database dari DBF
					//$dsn = "Driver={Microsoft dBase Driver (*.dbf)};SourceType=DBF;SourceDB=D:\\New folder\\SDBP\\SDBP;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
					//$odbc= odbc_connect($dsn,"","");
				
				// 2. Cara ke-dua untuk ambil database dari DBF
					$odbc = odbc_connect ("DBaseNKE", "" , "") or die (odbc_errormsg());
					//$odbc = odbc_connect ("DBaseNKE1", "" , "") or die (odbc_errormsg());
				if($isUpdOutApp == 1 || $dateNow > $dateStart)
				{
					//$conn = new COM('ADODB.Connection') or exit('Cannot start ADO.'); 
					//$odbc = odbc_connect ("DBaseNKE2", "" , "") or die (odbc_errormsg());
					// Tabel DP_HD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'DP_HD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table	
						$getTID		= "SELECT DP_CODE, DP_DATE, PRJCODE FROM DP_HD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totDP_HD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$DP_CODE		= $vTID['DP_CODE'];
							$DP_DATE		= $vTID['DP_DATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('DP_HD', $DP_CODE, '$PRJCODE', '$DP_DATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel LPMHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'LPMHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table	
						$getTID		= "SELECT LPMCODE, TRXDATE, PRJCODE FROM LPMHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totLPMHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$LPMCODE		= $vTID['LPMCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('LPMHD', $LPMCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel OP_HD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'OP_HD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table	
						$getTID		= "SELECT OP_CODE, TRXDATE, PRJCODE FROM OP_HD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totOP_HD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$OP_CODE		= $vTID['OP_CODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('OP_HD', $OP_CODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel OPNHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'OPNHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT OPNCODE, TRXDATE, PRJCODE FROM OPNHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totOPNHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$OPNCODE		= $vTID['OPNCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('OPNHD', $OPNCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel PD_HD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'PD_HD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT TDPCODE, TDPDATE, PRJCODE FROM PD_HD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totPD_HD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$TDPCODE		= $vTID['TDPCODE'];
							$TDPDATE		= $vTID['TDPDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('PD_HD', $TDPCODE, '$PRJCODE', '$TDPDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel SPKHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'SPKHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT SPKCODE, TRXDATE, PRJCODE FROM SPKHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totSPKHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$SPKCODE		= $vTID['SPKCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('SPKHD', $SPKCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel SPPHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'SPPHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT SPPCODE, TRXDATE, PRJCODE FROM SPPHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totSPPHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$SPPCODE		= $vTID['SPPCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('SPPHD', $SPPCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}				
					// Tabel TLKHD.DBF // Tidak ada kolom approve
						// 1. Delete All in Table
						//$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'TLKHD'";
						//$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						//$getTID		= "SELECT VOCCODE, TRNDATE FROM TLKHD.DBF WHERE APPROVE = FALSE";
						//$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						//$totTLKHD	= 0;				
						//while($vTID = odbc_fetch_array($qTID))
						//{
							//$VOCCODE		= $vTID['VOCCODE'];
							//$TRNDATE		= $vTID['TRNDATE'];				
							//$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, OA_Date) VALUES ('TLKHD', $VOCCODE, '$TRNDATE')";
							//$this->db->query($sqlInsOutApp);		
						//}
					// Tabel VLKHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'VLKHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE FROM VLKHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totVLKHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$VOCCODE		= $vTID['VOCCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('VLKHD', '$VOCCODE', '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel VOCHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'VOCHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE FROM VOCHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totVOCHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$VOCCODE		= $vTID['VOCCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('VOCHD', $VOCCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel VOTHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'VOTHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE FROM VOTHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totVOTHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$VOCCODE		= $vTID['VOCCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('VOTHD', $VOCCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}
					// Tabel VTPHD.DBF
						// 1. Delete All in Table
						$sqlDelOutApp	= "DELETE FROM tout_approve WHERE OA_Category = 'VTPHD'";
						$this->db->query($sqlDelOutApp);
						// 2. Insert into Table
						$getTID		= "SELECT VOCCODE, TRXDATE, PRJCODE FROM VTPHD.DBF WHERE APPROVE = FALSE";
						$qTID 		= odbc_exec($odbc, $getTID) or die (odbc_errormsg());
						$totVTPHD	= 0;				
						while($vTID = odbc_fetch_array($qTID))
						{
							$VOCCODE		= $vTID['VOCCODE'];
							$TRXDATE		= $vTID['TRXDATE'];
							$PRJCODE		= $vTID['PRJCODE'];
							$sqlInsOutApp	= "INSERT INTO tout_approve (OA_Category, OA_Code, proj_Code, OA_Date) VALUES ('VTPHD', $VOCCODE, '$PRJCODE', '$TRXDATE')";
							$this->db->query($sqlInsOutApp);		
						}			
					
					// Update Last Update
					$dateNow = date('Y-m-d H:i:s');
					$sqlUpdDate	= "UPDATE tout_approve SET OA_Update = '$dateNow'";
					$this->db->query($sqlUpdDate);
				}
				
			$num_rows = $this->M_track_outapprove->count_all_num_rows($Emp_DeptCode); // Ambil dari semua tabel header
						
			$data["recordcount"] = $num_rows;
			$config = array();
			//$config['base_url'] = $this->mza_secureurl->setSecureUrl_encode(site_url('c_help/c_track_outapprove'),'get_last_ten_Alloutapprove_src');
			$config['base_url'] = site_url('c_help/c_track_outapprove/get_last_ten_Alloutapprove');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 20;
			$config["uri_segment"] = 3;
			$config['cur_page'] = $offset;
			
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data['viewbankguar'] = $this->M_track_outapprove->get_last_ten_outapprove($config["per_page"], $offset, $empID, $Emp_DeptCode)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}