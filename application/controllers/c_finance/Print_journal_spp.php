<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= print_journal_spp.php
 * Location		= -
*/

class Print_journal_spp  extends CI_Controller  
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
		
		$url			= site_url('c_finance/print_journal_spp/prjlist_spp/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function prjlist_spp($offset=0)
	{
		$this->load->model('m_finance/m_print_journal/m_print_journal', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 		= 'Project List';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			
			$num_rows 				= $this->m_print_journal->count_all_project($DefEmp_ID);
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
	 
			$data['vewproject'] 		= $this->m_print_journal->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_finance/v_print_journal/spp_project_list', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	public function list_spp_journal($offset=0)
	{
		$this->load->model('m_finance/m_print_journal/m_print_journal', '', TRUE);
			
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
			$data['h2_title']			= 'SPP Journal';
			$data['PRJCODE']			= $PRJCODE;
			$data['form_action'] 		= site_url('c_finance/print_journal_spp/v_spp_view_journal/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$backButton					= site_url('c_finance/print_journal_spp/prjlist_spp/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['link'] 				= array('link_back' => anchor("$backButton",'<input type="button" name="btnCancel" id="btnCancel" value=" Back " class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			
			$num_rows 					= $this->m_print_journal->count_all_num_rows_spp($PRJCODE);
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
	 
			$data['viewlpm']			= $this->m_print_journal->get_last_ten_spp($config["per_page"], $offset, $PRJCODE)->result();			
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_finance/v_print_journal/spp_idx_journal', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function v_spp_view_journal()
	{
		$this->load->model('m_finance/m_print_journal/m_print_journal', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;		
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$SPPCODE 				= $this->input->post('SPPCODE');
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'View SPP Journal';
			$secCancel				= site_url('c_finance/print_journal_spp/list_list_spp_journal/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$secCancel",'<input type="button" name="btnCancel" id="btnCancel" value=" Back " class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$data['SPPCODE'] 		= $this->input->post('SPPCODE');
			$data['PRJCODE'] 		= $this->input->post('PRJCODE');
			$data['SPLCODE'] 		= $this->input->post('SPLCODE');
			$data['SchedMatOS'] 	= $this->input->post('SchedMatOS');
			
			$this->load->view('v_finance/v_print_journal/spp_view_journal', $data);
		}
		else
		{
			redirect('login');
		}
	}
}