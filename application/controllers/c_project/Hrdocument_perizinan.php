<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= hrdocument.php
 * Location		= -
*/

class Hrdocument_perizinan  extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	// Start : Index tiap halaman
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/hrdocument_perizinan/get_last_ten_GroupType/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_last_ten_projList($offset=0) // HOLD
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Project List';
			$data['h3_title'] 			= 'HR Document';
			$data['main_view'] 			= 'v_project/v_project_mc/project_planning';
			$data['secAddURL'] 			= site_url('c_project/hrdocument_perizinan/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['showIndex'] 			= site_url('c_project/hrdocument_perizinan/index/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/hrdocument_perizinan/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));			
			$data['moffset'] 			= $offset;
			$data['perpage'] 			= 20;
			$data['theoffset']			= 0;
			
			$num_rows					= $this->m_hrdocument->count_all_num_rows($DefEmp_ID);
			$data["recordcount"] 		= $num_rows;
			$config 					= array();
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 3;
			$config['cur_page'] 		= $offset;
			$config['base_url'] 		= site_url('c_project/hrdocument_perizinan/get_last_ten_projList');				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data['viewproject'] = $this->m_hrdocument->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] = $this->pagination->create_links();
			
			$this->load->view('v_project/v_hrdocument_perizinan/project_planning', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function hr_documentlistx($offset=0) // HOLD
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$sqlPRJNM		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
			$resPRJNM 		= $this->db->query($sqlPRJNM)->result();
			foreach($resPRJNM as $rowPRJNM) :
				$PRJNAME 	= $rowPRJNM->PRJNAME;
			endforeach;
			$data["PRJNAME"]= $PRJNAME;
			
			$offset			= 0;
			$selGROUPDOC	= 'D0240';
			
			$sqlAC 			= "tbl_document WHERE doc_parent = '$selGROUPDOC' AND isHRD = 1";
			$ressqlAC		= $this->db->count_all($sqlAC);
			$sqlA 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selGROUPDOC' AND isHRD = 1";
			$resultA 		= $this->db->query($sqlA)->result();
			foreach($resultA as $rowA) :
				$doc_codeA 	= $rowA->doc_code;
			endforeach;
			$selCLASSDOC 	= $doc_codeA;
			
			$sqlBC 			= "tbl_document WHERE doc_parent = '$selCLASSDOC' AND isHRD = 1";
			$ressqlBC		= $this->db->count_all($sqlBC);
			$sqlB 			= "SELECT doc_code, doc_name FROM tbl_document WHERE doc_parent = '$selCLASSDOC' AND isHRD = 1";
			$resultB 		= $this->db->query($sqlB)->result();
			foreach($resultB as $rowB) :
				$doc_codeB	= $rowB->doc_code;
			endforeach;
			$selTYPEDOC 	= $doc_codeB;
			$data['txtSearch']			= '';
				
			$data["selGROUPDOC1"] 		= $selGROUPDOC;
			$data["selCLASSDOC1"] 		= $selCLASSDOC;
			$data["selTYPEDOC1"] 		= $selTYPEDOC;
			
			// Secure URL
			$data['srch_url'] 			= site_url('c_project/project_owner/get_last_ten_projHRDOC_src/?id='.$this->url_encryption_helper->encode_url($appName));		
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document List';
			$data['h3_title'] 			= 'HR Document';
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocumentlist';	
					
			$data['link'] 				= array('link_back' => anchor('c_project/hrdocument_perizinan/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $PRJCODE;
			//$data['moffset'] 			= $offset;
			
			$selSearchproj_Code 		= $PRJCODE;
			$selSearchCat				= '';
			$selSearchType      		= '';
			$txtSearch        			= '';
			
			$dataSessSrc = array(
				'selSearchproj_Code' 	=> $selSearchproj_Code,
				'selSearchCat' 			=> $selSearchCat,
				'selSearchType' 		=> $selSearchType,
				'txtSearch' 			=> $txtSearch);
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
			
			$num_rows 					= $this->m_hrdocument->count_all_num_rowsProjDOC($PRJCODE, $selTYPEDOC, $txtSearch);
			
			$data["recordcount"] 		= $num_rows;
			
			$data['CATTYPE'] 			= '';
			
			$data["recordcount"] 		= $num_rows;			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/hrdocument_perizinan/get_last_ten_projHRDOC1');
			$config["total_rows"] 		= $num_rows;			
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 4;				
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close']	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link']		= '&gt;&gt;';
			
			$this->pagination->initialize($config);
	 
			$data['viewdocument'] = $this->m_hrdocument->get_last_ten_projHRDOC($PRJCODE, $config["per_page"], $offset, $selTYPEDOC, $txtSearch)->result();
			
			$data["pagination"] = $this->pagination->create_links();
			
			$myProjectSess = array(
					'myProjSession' => $selSearchproj_Code);
			$this->session->set_userdata('dtProjSess', $myProjectSess);
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocumentlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_GroupType($offset=0) // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document Group List';
			$data['h3_title'] 			= 'HR Document';
			$data['DefEmp_ID'] 			= $this->session->userdata['Emp_ID'];
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_type', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_Type($offset=0) // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$doc_code			= $_GET['id'];
			$doc_code			= $this->url_encryption_helper->decode_url($doc_code);
			
			$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Document Type List';
			$data['h3_title'] 		= 'HR Document';
			$data['DefEmp_ID'] 		= $this->session->userdata['Emp_ID'];
			$data['parentCode'] 	= $doc_code;
			$cancel_url				= site_url('c_project/hrdocument_perizinan/get_last_ten_GroupType/?id='.$this->url_encryption_helper->encode_url($appName));
			//$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url;
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function hr_documentlist() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		$doc_code	= $_GET['id'];
		$doc_code	= $this->url_encryption_helper->decode_url($doc_code);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			
			$sqlA 			= "SELECT doc_code, doc_parent, doc_name FROM tbl_document WHERE doc_code = '$doc_code' AND isHRD = 1";
			$resultA 		= $this->db->query($sqlA)->result();
			foreach($resultA as $rowA) :
				$doc_codeA 		= $rowA->doc_code;
				$doc_parentA	= $rowA->doc_parent;
				$doc_nameA 		= $rowA->doc_name;
			endforeach;
			$data["doc_parent"]	= $doc_parentA;	
			$data["doc_code"]	= $doc_code;			
			$data["doc_name"]	= $doc_nameA;
			
			$cancel_url			= site_url('c_project/hrdocument_perizinan/get_last_ten_Type/?id='.$this->url_encryption_helper->encode_url($doc_parentA));
					
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Document List';
			$data['h3_title'] 	= $doc_nameA;
			$data['main_view'] 	= 'v_project/v_hrdocument_perizinan/hrdocumentlist';
			$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $cancel_url;
			$num_rows 				= $this->m_hrdocument->count_all_num_DokHR($doc_code);			
			$data["countDoc"] 		= $num_rows;	 
			$data['viewdocument'] 	= $this->m_hrdocument->get_last_ten_DokHR($doc_code)->result();
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocumentlist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$doc_code	= $_GET['id'];
			$DOCCODE	= $this->url_encryption_helper->decode_url($doc_code);
			
			$getDOCType 				= $this->m_hrdocument->get_DOC_Type($DOCCODE)->row();
			$doc_name					= $getDOCType->doc_name;
			
			$data['title'] 				= $appName;
			$data['DOCCODE'] 			= $DOCCODE;
			$data['doc_name'] 			= $doc_name;
			$cancel_url					= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			
			$docPatternPosition 		= 'Especially';
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add HR Document';
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocument_form';
			$data['form_action']		= site_url('c_project/hrdocument_perizinan/do_upload');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN234';
			$data['viewDocPattern'] 	= $this->m_hrdocument->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function hrdocproject_upload() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HRDOCNO	= $_GET['id'];
			$HRDOCNO	= $this->url_encryption_helper->decode_url($HRDOCNO);
		
			$getprojectSPK 				= $this->m_hrdocument->get_DOC_by_number($HRDOCNO)->row();
			$PRJCODE					= $getprojectSPK->PRJCODE;
			
			$cancel_url 				= site_url('c_project/sd_hrdocument/get_last_ten_projHRDOC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 			= $PRJCODE;	
			$data['proj_Code'] 			= $PRJCODE;
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Document Upload';
			$data['h9_title']			= $HRDOCNO;
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocument_form';
			$data['form_action']		= site_url('c_project/sd_hrdocument/do_upload_update');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['HRDOCNO']			= $getprojectSPK->HRDOCNO;
			$data['default']['HRDOCCODE']		= $getprojectSPK->HRDOCCODE;
			$data['default']['HRDOCTYPE']		= $getprojectSPK->HRDOCTYPE;
			$data['default']['TRXDATE']			= $getprojectSPK->TRXDATE;
			$data['default']['PRJCODE']			= $getprojectSPK->PRJCODE;
			//$data['default']['HRDOCCOST']		= $getprojectSPK->HRDOCCOST;
			$data['default']['HRDOCCOST']		= 0;
			$data['default']['HRDOCJNS']		= $getprojectSPK->HRDOCTYPE;
			$data['default']['HRDOCJML']		= $getprojectSPK->HRDOCJML;
			$data['default']['HRDOCLBR']		= $getprojectSPK->HRDOCLBR;
			$data['default']['HRDOCLOK']		= $getprojectSPK->HRDOCLOK;
			$data['default']['Patt_Date'] 		= $getprojectSPK->Patt_Date;
			$data['default']['Patt_Month'] 		= $getprojectSPK->Patt_Month;
			$data['default']['Patt_Year'] 		= $getprojectSPK->Patt_Year;
			$data['default']['Patt_Number'] 	= $getprojectSPK->Patt_Number;
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_upload', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function do_upload() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$this->db->trans_begin();
		
		// Pembuatan ID Penyimpanan Per Tanggal	
		date_default_timezone_set("Asia/Jakarta");
		$HRDOC_CREATED		= date('Y-m-d H:i:s');
		
		$istask				= $this->input->post('istask');
		$HRDOCNO			= $this->input->post('HRDOCNO');
		$DOCCODE			= $this->input->post('DOCCODE');
		$Patt_Number		= $this->input->post('Patt_Number');			
		$HRDOCCODE			= $this->input->post('HRDOCCODE');
		$TRXDATE			= date('Y-m-d',strtotime($this->input->post('TRXDATE')));
		$SHOW_SE_DATE		= $this->input->post('SHOW_SE_DATE');
		$START_DATE			= $this->input->post('START_DATE');
		$END_DATE			= $this->input->post('END_DATE');
		$HRDOCTYPE			= $this->input->post('HRDOCTYPE');	// ASLI / COPY
		if($HRDOCTYPE == 1)
		{
			$HRDOCJNS		= "ASLI";
		}
		else
		{
			$HRDOCJNS		= "COPY";
		}
		$HRDOCJML			= $this->input->post('HRDOCJML');	// JML HAL
		$HRDOCLBR			= $this->input->post('HRDOCLBR');	// LEMBAR / BUKU / BUKU TIPIS
		$HRDOCLOK			= $this->input->post('HRDOCLOK');
		$PRJCODE			= $this->input->post('PRJCODE');
		$OWNER_CODE			= $this->input->post('OWNER_CODE');
		$OWNER_DESC			= $this->input->post('OWNER_DESC');
		$OWNER_ADD			= $this->input->post('OWNER_ADD');
		$HRDOCCOST			= $this->input->post('HRDOCCOST');
		$PM_EMPCODE			= $this->input->post('PM_EMPCODE');
		$PM_NAME			= $this->input->post('PM_NAME');
		$PM_STATUS			= $this->input->post('PM_STATUS');
		$DIR_EMPCODE		= $this->input->post('DIR_EMPCODE');
		$DIR_NAME			= $this->input->post('DIR_NAME');
		$HRDOCSTAT			= $this->input->post('HRDOCSTAT');
		if($HRDOCSTAT == 1)
		{
			$BORROW_EMP		= '';
		}
		else
		{
			$BORROW_EMP		= $this->input->post('BORROW_EMP');
		}
		$HRDOC_NOTE			= $this->input->post('HRDOC_NOTE');
		$TRXUSER			= $DefEmp_ID;
		$Patt_Date			= date('d',strtotime($HRDOC_CREATED));
		$Patt_Month			= date('m',strtotime($HRDOC_CREATED));
		$Patt_Year			= date('Y',strtotime($HRDOC_CREATED));
		
		$file 				= $_FILES['userfile'];
		$file_name 			= $file['name'];
		/*echo "HRDOCNO = $HRDOCNO<br>";
		echo "DOCCODE = $DOCCODE<br>";
		echo "HRDOCCODE = $HRDOCCODE<br>";
		echo "SHOW_SE_DATE = $SHOW_SE_DATE<br>";
		echo "START_DATE = $START_DATE<br>";
		echo "END_DATE = $END_DATE<br>";
		echo "HRDOCTYPE = $HRDOCTYPE<br>";
		echo "HRDOCJNS = $HRDOCJNS<br>";
		echo "HRDOCJML = $HRDOCJML<br>";
		echo "HRDOCLBR = $HRDOCLBR<br>";
		echo "HRDOCLOK = $HRDOCLOK<br>";
		echo "PRJCODE = $PRJCODE<br>";
		echo "OWNER_DESC = $OWNER_DESC<br>";
		echo "OWNER_ADD = $OWNER_ADD<br>";
		echo "HRDOCCOST = $HRDOCCOST<br>";
		echo "PM_EMPCODE = $PM_EMPCODE<br>";
		echo "PM_NAME = $PM_NAME<br>";
		echo "PM_STATUS = $PM_STATUS<br>";
		echo "DIR_EMPCODE = $DIR_EMPCODE<br>";
		echo "DIR_NAME = $DIR_NAME<br>";
		echo "HRDOCSTAT = $HRDOCSTAT<br>";
		echo "file_name = $file_name<br>";*/
		
		if($file_name != '')
		{
			/*$config['upload_path']   	= "assets/AdminLTE-2.0.5/doc_center/uploads/$file_name/"; 
			$config['allowed_types']	= 'pdf'; 
			$config['overwrite'] 		= TRUE;
			$config['max_size']     	= 1000000; 
			$config['max_width']    	= 10024; 
			$config['max_height']    	= 10000;  
			$config['file_name']       	= $file['name'];
			
			$this->load->library('upload', $config);
			
			$this->load->library('upload', $config);*/
			$file 						= $_FILES['userfile'];
			$file_name 					= $file['name'];
			$config['upload_path']   	= "assets/AdminLTE-2.0.5/doc_center/uploads/"; 
			$config['allowed_types']	= 'pdf|gif|jpg|png'; 
			$config['overwrite'] 		= TRUE;
			//$config['max_size']     	= 1000000; 
			//$config['max_width']    	= 10024; 
			//$config['max_height']    	= 10000;  
			$config['file_name']       	= $file['name'];
	
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('userfile'))
			{
				$DOCCODE				= $DOCCODE;			
				$getDOCType 			= $this->m_hrdocument->get_DOC_Type($DOCCODE)->row();
				$doc_name				= $getDOCType->doc_name;
				
				$data['title'] 			= $appName;
				$data['DOCCODE'] 		= $DOCCODE;
				$data['doc_name'] 		= $doc_name;
				$cancel_url				= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
				
				$docPatternPosition 	= 'Especially';
				$data['title'] 			= $appName;
				$data['task'] 			= 'add';
				$data['h2_title']		= 'Add HR Document';
				$data['main_view'] 		= 'v_project/v_hrdocument_perizinan/hrdocument_form';
				$data['form_action']	= site_url('c_project/hrdocument_perizinan/do_upload');
				$data['link'] 			= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
				
				$MenuCode 				= 'MN234';
				$data['viewDocPattern'] = $this->m_hrdocument->getDataDocPat($MenuCode)->result();
				
				$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_form', $data);
			}
			else 
			{
				/*$dataINSDOC = array('HRDOCNO' => $HRDOCNO,		// OK
							'HRDOCCODE'		=> $HRDOCCODE,		// OK
							'DOCCODE'		=> $DOCCODE,		// OK
							'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
							'TRXDATE'		=> $TRXDATE,		// OK
							'PRJCODE'		=> $PRJCODE,		// OK
							'OWNER_CODE'	=> $OWNER_CODE,		// OK
							'OWNER_DESC'	=> $OWNER_DESC,		// OK
							'OWNER_ADD'		=> $OWNER_ADD,		// OK
							'HRDOCCOST'		=> $HRDOCCOST,		// OK
							'HRDOCJNS'		=> $HRDOCJNS,		// OK
							'HRDOCJML'		=> $HRDOCJML,		// OK
							'HRDOCLBR'		=> $HRDOCLBR,		// OK
							'HRDOCLOK'		=> $HRDOCLOK,		// OK
							'TRXUSER'		=> $TRXUSER,		// OK
							'START_DATE'	=> $START_DATE,		// OK
							'END_DATE'		=> $END_DATE,		// OK
							'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
							'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
							'HRDOC_NAME'	=> $file_name,		// OK
							'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
							'PM_NAME'		=> $PM_NAME,		// OK
							'PM_STATUS'		=> $PM_STATUS,		// OK
							'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
							'DIR_NAME'		=> $DIR_NAME,		// OK
							'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
							'BORROW_EMP'	=> $BORROW_EMP,		// OK
							'HRDOC_NOTE'	=> $HRDOC_NOTE,		// OK
							'Patt_Date'		=> $Patt_Date,		// OK
							'Patt_Month'	=> $Patt_Month,		// OK
							'Patt_Year'		=> $Patt_Year,		// OK
							'Patt_Number'	=> $this->input->post('Patt_Number'));	
				
				$this->m_hrdocument->add($dataINSDOC);*/
			}
		}
		else
		{
			$dataINSDOC = array('HRDOCNO' => $HRDOCNO,		// OK
						'HRDOCCODE'		=> $HRDOCCODE,		// OK
						'DOCCODE'		=> $DOCCODE,		// OK
						'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
						'TRXDATE'		=> $TRXDATE,		// OK
						'PRJCODE'		=> $PRJCODE,		// OK
						'OWNER_CODE'	=> $OWNER_CODE,		// OK
						'OWNER_DESC'	=> $OWNER_DESC,		// OK
						'OWNER_ADD'		=> $OWNER_ADD,		// OK
						'HRDOCCOST'		=> $HRDOCCOST,		// OK
						'HRDOCJNS'		=> $HRDOCJNS,		// OK
						'HRDOCJML'		=> $HRDOCJML,		// OK
						'HRDOCLBR'		=> $HRDOCLBR,		// OK
						'HRDOCLOK'		=> $HRDOCLOK,		// OK
						'TRXUSER'		=> $TRXUSER,		// OK
						'START_DATE'	=> $START_DATE,		// OK
						'END_DATE'		=> $END_DATE,		// OK
						'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
						'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
						//'HRDOC_NAME'	=> $file_name,		// OK
						'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
						'PM_NAME'		=> $PM_NAME,		// OK
						'PM_STATUS'		=> $PM_STATUS,		// OK
						'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
						'DIR_NAME'		=> $DIR_NAME,		// OK
						'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
						'BORROW_EMP'	=> $BORROW_EMP,		// OK
						'HRDOC_NOTE'	=> $HRDOC_NOTE,		// OK
						'Patt_Date'		=> $Patt_Date,		// OK
						'Patt_Month'	=> $Patt_Month,		// OK
						'Patt_Year'		=> $Patt_Year,		// OK
						'Patt_Number'	=> $this->input->post('Patt_Number'));	
			
			$this->m_hrdocument->add($dataINSDOC);
		}
		
		if($istask == 'add')
		{
			$dataINSDOC = array('HRDOCNO' => $HRDOCNO,		// OK
						'HRDOCCODE'		=> $HRDOCCODE,		// OK
						'DOCCODE'		=> $DOCCODE,		// OK
						'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
						'TRXDATE'		=> $TRXDATE,		// OK
						'PRJCODE'		=> $PRJCODE,		// OK
						'OWNER_CODE'	=> $OWNER_CODE,		// OK
						'OWNER_DESC'	=> $OWNER_DESC,		// OK
						'OWNER_ADD'		=> $OWNER_ADD,		// OK
						'HRDOCCOST'		=> $HRDOCCOST,		// OK
						'HRDOCJNS'		=> $HRDOCJNS,		// OK
						'HRDOCJML'		=> $HRDOCJML,		// OK
						'HRDOCLBR'		=> $HRDOCLBR,		// OK
						'HRDOCLOK'		=> $HRDOCLOK,		// OK
						'TRXUSER'		=> $TRXUSER,		// OK
						'START_DATE'	=> $START_DATE,		// OK
						'END_DATE'		=> $END_DATE,		// OK
						'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
						'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
						'HRDOC_NAME'	=> $file_name,		// OK
						'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
						'PM_NAME'		=> $PM_NAME,		// OK
						'PM_STATUS'		=> $PM_STATUS,		// OK
						'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
						'DIR_NAME'		=> $DIR_NAME,		// OK
						'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
						'BORROW_EMP'	=> $BORROW_EMP,		// OK
						'HRDOC_NOTE'	=> $HRDOC_NOTE,		// OK
						'Patt_Date'		=> $Patt_Date,		// OK
						'Patt_Month'	=> $Patt_Month,		// OK
						'Patt_Year'		=> $Patt_Year,		// OK
						'Patt_Number'	=> $this->input->post('Patt_Number'));
			$this->m_hrdocument->add($dataINSDOC);
		}
		else
		{
			if($file_name != '')
			{
				$dataUPDDOC = array('HRDOCNO' => $HRDOCNO,		// OK
							'HRDOCCODE'		=> $HRDOCCODE,		// OK
							'DOCCODE'		=> $DOCCODE,		// OK
							'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
							'TRXDATE'		=> $TRXDATE,		// OK
							'PRJCODE'		=> $PRJCODE,		// OK
							'OWNER_CODE'	=> $OWNER_CODE,		// OK
							'OWNER_DESC'	=> $OWNER_DESC,		// OK
							'OWNER_ADD'		=> $OWNER_ADD,		// OK
							'HRDOCCOST'		=> $HRDOCCOST,		// OK
							'HRDOCJNS'		=> $HRDOCJNS,		// OK
							'HRDOCJML'		=> $HRDOCJML,		// OK
							'HRDOCLBR'		=> $HRDOCLBR,		// OK
							'HRDOCLOK'		=> $HRDOCLOK,		// OK
							'TRXUSER'		=> $TRXUSER,		// OK
							'START_DATE'	=> $START_DATE,		// OK
							'END_DATE'		=> $END_DATE,		// OK
							'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
							'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
							'HRDOC_NAME'	=> $file_name,		// OK
							'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
							'PM_NAME'		=> $PM_NAME,		// OK
							'PM_STATUS'		=> $PM_STATUS,		// OK
							'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
							'DIR_NAME'		=> $DIR_NAME,		// OK
							'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
							'BORROW_EMP'	=> $BORROW_EMP,		// OK
							'HRDOC_NOTE'	=> $HRDOC_NOTE);
			}
			else
			{
				$dataUPDDOC = array('HRDOCNO' => $HRDOCNO,		// OK
							'HRDOCCODE'		=> $HRDOCCODE,		// OK
							'DOCCODE'		=> $DOCCODE,		// OK
							'HRDOCTYPE'		=> $HRDOCTYPE,		// OK
							'TRXDATE'		=> $TRXDATE,		// OK
							'PRJCODE'		=> $PRJCODE,		// OK
							'OWNER_CODE'	=> $OWNER_CODE,		// OK
							'OWNER_DESC'	=> $OWNER_DESC,		// OK
							'OWNER_ADD'		=> $OWNER_ADD,		// OK
							'HRDOCCOST'		=> $HRDOCCOST,		// OK
							'HRDOCJNS'		=> $HRDOCJNS,		// OK
							'HRDOCJML'		=> $HRDOCJML,		// OK
							'HRDOCLBR'		=> $HRDOCLBR,		// OK
							'HRDOCLOK'		=> $HRDOCLOK,		// OK
							'TRXUSER'		=> $TRXUSER,		// OK
							'START_DATE'	=> $START_DATE,		// OK
							'END_DATE'		=> $END_DATE,		// OK
							'HRDOCSTAT'		=> $HRDOCSTAT,		// OK
							'HRDOC_CREATED'	=> $HRDOC_CREATED,	// OK
							//'HRDOC_NAME'	=> $file_name,		// OK
							'PM_EMPCODE'	=> $PM_EMPCODE,		// OK
							'PM_NAME'		=> $PM_NAME,		// OK
							'PM_STATUS'		=> $PM_STATUS,		// OK
							'DIR_EMPCODE'	=> $DIR_EMPCODE,	// OK
							'DIR_NAME'		=> $DIR_NAME,		// OK
							'PEMILIK_MODAL'	=> $OWNER_DESC,		// OK
							'BORROW_EMP'	=> $BORROW_EMP,		// OK
							'HRDOC_NOTE'	=> $HRDOC_NOTE);
			}				
			$this->m_hrdocument->update($HRDOCNO, $dataUPDDOC);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
		redirect($url);
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HRDOCNO	= $_GET['id'];
			$HRDOCNO	= $this->url_encryption_helper->decode_url($HRDOCNO);
			
			$getprojectDOC 				= $this->m_hrdocument->get_DOC_by_number($HRDOCNO)->row();
			$PRJCODE					= $getprojectDOC->PRJCODE;
			$DOCCODE					= $getprojectDOC->DOCCODE;
			
			$getDOCType 				= $this->m_hrdocument->get_DOC_Type($DOCCODE)->row();
			$doc_name					= $getDOCType->doc_name;
			
			$cancel_url 				= site_url('c_project/hrdocument_perizinan/hr_documentlist/?id='.$this->url_encryption_helper->encode_url($DOCCODE));
			
			$data['doc_name'] 			= $doc_name;
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title']			= 'Document Update';
			$data['main_view'] 			= 'v_project/v_hrdocument_perizinan/hrdocument_form';
			$data['form_action']		= site_url('c_project/hrdocument_perizinan/do_upload');
			$data['link'] 				= array('link_back' => anchor("$cancel_url",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= $cancel_url;
			
			$data['recordcountProject']	= $this->m_hrdocument->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_hrdocument->viewProject()->result();
			
			$MenuCode 					= 'MN238';
			$data['viewDocPattern'] 	= $this->m_hrdocument->getDataDocPat($MenuCode)->result();
			
			$getprojectSPK 				= $this->m_hrdocument->get_DOC_by_number($HRDOCNO)->row();
			
			$data['default']['HRDOCNO']			= $getprojectSPK->HRDOCNO;
			$data['default']['HRDOCCODE']		= $getprojectSPK->HRDOCCODE;
			$data['default']['DOCCODE']			= $getprojectSPK->DOCCODE;
			$data['default']['HRDOCTYPE']		= $getprojectSPK->HRDOCTYPE;
			$data['default']['TRXDATE']			= $getprojectSPK->TRXDATE;
			$data['default']['PRJCODE']			= $getprojectSPK->PRJCODE;
			$data['default']['OWNER_CODE']		= $getprojectSPK->OWNER_CODE;
			$data['default']['OWNER_DESC']		= $getprojectSPK->OWNER_DESC;
			$data['default']['OWNER_ADD']		= $getprojectSPK->OWNER_ADD;
			$data['default']['HRDOCCOST']		= $getprojectSPK->HRDOCCOST;
			$data['default']['HRDOCJNS']		= $getprojectSPK->HRDOCJNS;
			$data['default']['HRDOCJML']		= $getprojectSPK->HRDOCJML;
			$data['default']['HRDOCLBR']		= $getprojectSPK->HRDOCLBR;
			$data['default']['HRDOCLOK']		= $getprojectSPK->HRDOCLOK;
			$data['default']['TRXUSER']			= $getprojectSPK->TRXUSER;
			$data['default']['START_DATE']		= $getprojectSPK->START_DATE;
			$data['default']['END_DATE']		= $getprojectSPK->END_DATE;
			$data['default']['HRDOCSTAT']		= $getprojectSPK->HRDOCSTAT;
			$data['default']['HRDOC_NAME']		= $getprojectSPK->HRDOC_NAME;
			$data['default']['PM_EMPCODE']		= $getprojectSPK->PM_EMPCODE;
			$data['default']['PM_NAME']			= $getprojectSPK->PM_NAME;
			$data['default']['PM_STATUS']		= $getprojectSPK->PM_STATUS;
			$data['default']['DIR_EMPCODE']		= $getprojectSPK->DIR_EMPCODE;
			$data['default']['DIR_NAME']		= $getprojectSPK->DIR_NAME;
			$data['default']['STATUS_DOK']		= $getprojectSPK->STATUS_DOK;
			$data['default']['BORROW_EMP']		= $getprojectSPK->BORROW_EMP;
			$data['default']['PEMILIK_MODAL']	= $getprojectSPK->PEMILIK_MODAL;
			$data['default']['HRDOC_NOTE']		= $getprojectSPK->HRDOC_NOTE;			
			$data['default']['HRD_EMPID'] 		= $getprojectSPK->HRD_EMPID;
			$data['default']['HRD_CUALIF'] 		= $getprojectSPK->HRD_CUALIF;
			$data['default']['HRD_PUBLISHER'] 	= $getprojectSPK->HRD_PUBLISHER;	
			$data['default']['Patt_Date'] 		= $getprojectSPK->Patt_Date;
			$data['default']['Patt_Month'] 		= $getprojectSPK->Patt_Month;
			$data['default']['Patt_Year'] 		= $getprojectSPK->Patt_Year;
			$data['default']['Patt_Number'] 	= $getprojectSPK->Patt_Number;
			
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function getallemp()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_project/m_hrdocument_perizinan/m_hrdocument', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			$DOCCODE		= $_GET['id'];
			$DOCCODE		= $this->url_encryption_helper->decode_url($DOCCODE);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Employee List';
			$data['h3_title']	= 'doc. center';
			$data['DOCCODE']	= $DOCCODE;
			
			$data['countEmp'] 	= $this->m_hrdocument->count_all_emp();
			$data['vwAllEmp'] 	= $this->m_hrdocument->viewAllEmp()->result();
					
			$this->load->view('v_project/v_hrdocument_perizinan/hrdocument_emp', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}