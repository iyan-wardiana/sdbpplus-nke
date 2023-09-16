<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Februari 2017
 * File Name	= print_journal_opn.php
 * Location		= -
*/

class print_journal_opn  extends CI_Controller  
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_finance/print_journal_opn/prjlist_opn/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function prjlist_opn($offset=0)
	{
		$this->load->model('m_finance/m_print_journal/M_print_journal', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 		= 'Project List';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			
			$num_rows 				= $this->M_print_journal->count_all_project($DefEmp_ID);
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
	 
			$data['vewproject'] 		= $this->M_print_journal->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_finance/v_print_journal/opn_project_list', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	public function list_opn_journal($offset=0)
	{
		$this->load->model('m_finance/m_print_journal/M_print_journal', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Opname Journal';
			$data['PRJCODE']			= $PRJCODE;
			$data['form_action'] 		= site_url('c_finance/print_journal_opn/v_opn_view_journal/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$backButton					= site_url('c_finance/print_journal_opn/prjlist_opn/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor("$backButton",'<input type="button" name="btnCancel" id="btnCancel" value=" Back " class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			
			/*$num_rows 					= $this->M_print_journal->count_all_num_rows_opn($PRJCODE);
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/listproject/get_last_ten_project');
			
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 4;							
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
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
	 
			$data['viewlpm']			= $this->M_print_journal->get_last_ten_opn($config["per_page"], $offset, $PRJCODE)->result();			
			$data["pagination"] 		= $this->pagination->create_links();*/
			
			$this->load->view('v_finance/v_print_journal/opn_idx_journal', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function v_opn_view_journal()
	{
		$this->load->model('m_finance/m_print_journal/M_print_journal', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;		
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$LPMCODE 				= $this->input->post('LPMCODE');
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'View Opname Journal';
			$secCancel				= site_url('c_finance/print_journal_opn/list_list_opn_journal/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$secCancel",'<input type="button" name="btnCancel" id="btnCancel" value=" Back " class="button_css" />', array('style' => 'text-decoration: none;')));
			$data['OPNCODE'] 		= $this->input->post('OPNCODE');
			$data['SPKCODE'] 		= $this->input->post('SPKCODE');
			$SPKCODE				= $this->input->post('SPKCODE');
			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$data['SPLCODE'] 		= $this->input->post('SPLCODE');
			$myStep					= $this->input->post('myStep');
			$myStep1				= substr("$myStep","2","1");
			$data['myStep']			= $myStep1;
			
			$myStep2 				= intval($myStep1);
			$len 					= strlen($myStep2);
			if($len==1) $nol="0";
			$newOPNCode = $SPKCODE.$nol.$myStep2;
			
			$odbc 		= odbc_connect ("DBaseNKE3", "" , "");
			$hasilC		= "SELECT COUNT(*) AS COUNTME FROM OPNHD.DBF WHERE OPNCODE = '$newOPNCode'";
			$qrhasilC	= odbc_exec($odbc, $hasilC) or die (odbc_errormsg());
			while($hasilC = odbc_fetch_array($qrhasilC))
			{
				$jumlah		= $hasilC['COUNTME'];
			}
			if($jumlah > 0)
			{
				$data['OPNCODE'] 			= $newOPNCode;
			}
			//echo "hahah $newOPNCode = $myOPNCode";
			$sqlSPKC					= $this->M_print_journal->getDataSPK($SPKCODE)->row();
			$sqlCountSPKC				= $this->M_print_journal->getCountDataSPK($SPKCODE);
	
			if($sqlCountSPKC > 0)
			{
				$data['SPKCODE'] 			= $sqlSPKC->SPKCODE;
				$data['txtPelaksana']		= $sqlSPKC->txtPelaksana;
				$data['sttgl'] 				= $sqlSPKC->sttgl;
				$data['stbln'] 				= $sqlSPKC->stbln;
				$data['stthn'] 				= $sqlSPKC->stthn;
				$data['endtgl'] 			= $sqlSPKC->endtgl;
				$data['endbln'] 			= $sqlSPKC->endbln;
				$data['endthn'] 			= $sqlSPKC->endthn;
				
				$data['sttgl1'] 			= $sqlSPKC->sttgl1;
				$data['stbln1'] 			= $sqlSPKC->stbln1;
				$data['stthn1'] 			= $sqlSPKC->stthn1;
				$data['endtgl1'] 			= $sqlSPKC->endtgl1;
				$data['endbln1'] 			= $sqlSPKC->endbln1;
				$data['endthn1'] 			= $sqlSPKC->endthn1;
				
				$data['sttgl2'] 			= $sqlSPKC->sttgl2;
				$data['stbln2'] 			= $sqlSPKC->stbln2;
				$data['stthn2'] 			= $sqlSPKC->stthn2;
				$data['endtgl2'] 			= $sqlSPKC->endtgl2;
				$data['endbln2'] 			= $sqlSPKC->endbln2;
				$data['endthn2'] 			= $sqlSPKC->endthn2;
				$data['spkdp'] 				= $sqlSPKC->spkdp;
			}
			else
			{
				$data['txtPelaksana']		= '';
				$data['sttgl'] 				= 'dd';
				$data['stbln'] 				= 'mm';
				$data['stthn'] 				= 'yyyy';
				$data['endtgl'] 			= 'dd';
				$data['endbln'] 			= 'mm';
				$data['endthn'] 			= 'yyyy';
				
				$data['sttgl1'] 			= 'dd';
				$data['stbln1'] 			= 'dd';
				$data['stthn1'] 			= 'yyyy';
				$data['endtgl1'] 			= 'dd';
				$data['endbln1'] 			= 'mm';
				$data['endthn1'] 			= 'yyyy';
				
				$data['sttgl2'] 			= 'dd';
				$data['stbln2'] 			= 'mm';
				$data['stthn2'] 			= 'yyyy';
				$data['endtgl2'] 			= 'dd';
				$data['endbln2'] 			= 'mm';
				$data['endthn2'] 			= 'yyyy';
				$data['spkdp'] 				= 0;
			}
			
			$this->load->view('v_finance/v_print_journal/opn_view_journal', $data);
		}
		else
		{
			redirect('login');
		}
	}
}