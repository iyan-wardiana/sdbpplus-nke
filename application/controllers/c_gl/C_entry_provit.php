<?php
/* 
 * Author		= Hendar Permana
 * Create Date	= 14 Juni 2017
 * File Name	= c_entry_provit.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_entry_provit  extends CI_Controller  
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	// Start : Index tiap halaman
 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_gl/c_entry_provit/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_gl/m_entry_provit/m_entry_provit', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Entry Provit Loss';
			$data['h3_title']			= 'General Ledger';
			$data['secAddURL'] 			= site_url('c_gl/c_entry_provit/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_gl/c_entry_provit/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->m_entry_provit->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 			= 'MN290';
			
			$data['vAssetGroup']		= $this->m_entry_provit->get_last_ten_AG()->result();
			
			
			$this->load->view('v_gl/v_entry_provit/v_entry_provit', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_gl/m_entry_provit/m_entry_provit', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Entry Provit Loss';
			$data['h3_title']			= 'General Ledger';
			$data['form_action']		= site_url('c_gl/c_entry_provit/add_process');
			$data['link'] 				= array('link_back' => anchor('c_gl/c_entry_provit/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN297';
			$data["MenuCode"] 			= 'MN290';
			$data['viewDocPattern'] 	= $this->m_entry_provit->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_gl/v_entry_provit/v_entry_provit_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_gl/m_entry_provit/m_entry_provit', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$InsAG 		= array('ID' 				=> $this->input->post('ID'),
								'CODE_PROFLOSS' 	=> $this->input->post('CODE_PROFLOSS'),
								'PRJCODE'		 	=> $this->input->post('PRJCODE'),
								'DATE'			 	=> date('Y-m-d',strtotime($this->input->post('DATE'))),
								'PROG_KONTRAKTUIL' 	=> $this->input->post('PROG_KONTRAKTUIL'),
								'PEKERJAAN' 		=> $this->input->post('PEKERJAAN'),
								'PROYEK_WIP'		=> $this->input->post('PROYEK_WIP'),
								'PROYEK_MATERIAL'	=> $this->input->post('PROYEK_MATERIAL'),
								'PROYEK_UPAH'		=> $this->input->post('PROYEK_UPAH'),
								'PROYEK_SUBKON'		=> $this->input->post('PROYEK_SUBKON'),
								'PROYEK_ALAT'		=> $this->input->post('PROYEK_ALAT'),
								'PROYEK_OVERHEAD'	=> $this->input->post('PROYEK_OVERHEAD'),
								'PUSAT_MATERIAL'	=> $this->input->post('PUSAT_MATERIAL'),
								'PUSAT_SUBKON'		=> $this->input->post('PUSAT_SUBKON'),
								'PUSAT_ALAT'		=> $this->input->post('PUSAT_ALAT'),
								'PUSAT_OVERHEAD'	=> $this->input->post('PUSAT_OVERHEAD'),
								'STOK'				=> $this->input->post('STOK'),
								'STOK_MATERIAL'		=> $this->input->post('STOK_MATERIAL'),
								'STOK_PELUMAS_BBM'	=> $this->input->post('STOK_PELUMAS_BBM'),
								'STOK_SUKU_CADANG'	=> $this->input->post('STOK_SUKU_CADANG'),
								'STOK_LAIN_LAIN'	=> $this->input->post('STOK_LAIN_LAIN'),
								'STOK_INV_BEKISTING'=> $this->input->post('STOK_INV_BEKISTING'),
								'OVER_BOOKING'		=> $this->input->post('OVER_BOOKING'),
								'BEBAN_ALAT' 		=> $this->input->post('BEBAN_ALAT'));

			$this->m_entry_provit->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_gl/c_entry_provit/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_gl/m_entry_provit/m_entry_provit', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CODE_PROFLOSS	= $_GET['id'];
			$CODE_PROFLOSS	= $this->url_encryption_helper->decode_url($CODE_PROFLOSS);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Entry Provit Loss';
			$data['h3_title']		= 'General Ledger';
			$data['form_action']	= site_url('c_gl/c_entry_provit/update_process');
			$data['link'] 			= array('link_back' => anchor('c_gl/c_entry_provit/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN290';
			
			$getAG 					= $this->m_entry_provit->get_AG($CODE_PROFLOSS)->row();
			
			$data['default']['ID'] 					= $getAG->ID;
			$data['default']['CODE_PROFLOSS'] 		= $getAG->CODE_PROFLOSS;
			$data['default']['PRJCODE']		 		= $getAG->PRJCODE;
			$data['default']['DATE']		 		= $getAG->DATE;
			$data['default']['PROG_KONTRAKTUIL'] 	= $getAG->PROG_KONTRAKTUIL;
			$data['default']['PEKERJAAN'] 			= $getAG->PEKERJAAN;
			$data['default']['PROYEK_WIP'] 			= $getAG->PROYEK_WIP;
			$data['default']['PROYEK_MATERIAL'] 	= $getAG->PROYEK_MATERIAL;
			$data['default']['PROYEK_UPAH'] 		= $getAG->PROYEK_UPAH;
			$data['default']['PROYEK_SUBKON'] 		= $getAG->PROYEK_SUBKON;
			$data['default']['PROYEK_ALAT'] 		= $getAG->PROYEK_ALAT;
			$data['default']['PROYEK_OVERHEAD'] 	= $getAG->PROYEK_OVERHEAD;
			$data['default']['PUSAT_MATERIAL'] 		= $getAG->PUSAT_MATERIAL;
			$data['default']['PUSAT_SUBKON'] 		= $getAG->PUSAT_SUBKON;
			$data['default']['PUSAT_ALAT'] 			= $getAG->PUSAT_ALAT;
			$data['default']['PUSAT_OVERHEAD'] 		= $getAG->PUSAT_OVERHEAD;
			$data['default']['STOK'] 				= $getAG->STOK;
			$data['default']['STOK_MATERIAL'] 		= $getAG->STOK_MATERIAL;
			$data['default']['STOK_PELUMAS_BBM'] 	= $getAG->STOK_PELUMAS_BBM;
			$data['default']['STOK_SUKU_CADANG'] 	= $getAG->STOK_SUKU_CADANG;
			$data['default']['STOK_LAIN_LAIN'] 		= $getAG->STOK_LAIN_LAIN;
			$data['default']['STOK_INV_BEKISTING'] 	= $getAG->STOK_INV_BEKISTING;
			$data['default']['OVER_BOOKING'] 		= $getAG->OVER_BOOKING;
			$data['default']['BEBAN_ALAT'] 			= $getAG->BEBAN_ALAT;
			
			$this->load->view('v_gl/v_entry_provit/v_entry_provit_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_gl/m_entry_provit/m_entry_provit', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$CODE_PROFLOSS	= $this->input->post('CODE_PROFLOSS');
			
			$UpdAG 		= array(
								'CODE_PROFLOSS' 	=> $this->input->post('CODE_PROFLOSS'),
								'PRJCODE'		 	=> $this->input->post('PRJCODE'),
								'DATE'			 	=> date('Y-m-d',strtotime($this->input->post('DATE'))),
								'PROG_KONTRAKTUIL' 	=> $this->input->post('PROG_KONTRAKTUIL'),
								'PEKERJAAN' 		=> $this->input->post('PEKERJAAN'),
								'PROYEK_WIP'		=> $this->input->post('PROYEK_WIP'),
								'PROYEK_MATERIAL'	=> $this->input->post('PROYEK_MATERIAL'),
								'PROYEK_UPAH'		=> $this->input->post('PROYEK_UPAH'),
								'PROYEK_SUBKON'		=> $this->input->post('PROYEK_SUBKON'),
								'PROYEK_ALAT'		=> $this->input->post('PROYEK_ALAT'),
								'PROYEK_OVERHEAD'	=> $this->input->post('PROYEK_OVERHEAD'),
								'PUSAT_MATERIAL'	=> $this->input->post('PUSAT_MATERIAL'),
								'PUSAT_SUBKON'		=> $this->input->post('PUSAT_SUBKON'),
								'PUSAT_ALAT'		=> $this->input->post('PUSAT_ALAT'),
								'PUSAT_OVERHEAD'	=> $this->input->post('PUSAT_OVERHEAD'),
								'STOK'				=> $this->input->post('STOK'),
								'STOK_MATERIAL'		=> $this->input->post('STOK_MATERIAL'),
								'STOK_PELUMAS_BBM'	=> $this->input->post('STOK_PELUMAS_BBM'),
								'STOK_SUKU_CADANG'	=> $this->input->post('STOK_SUKU_CADANG'),
								'STOK_LAIN_LAIN'	=> $this->input->post('STOK_LAIN_LAIN'),
								'STOK_INV_BEKISTING'=> $this->input->post('STOK_INV_BEKISTING'),
								'OVER_BOOKING'		=> $this->input->post('OVER_BOOKING'),
								'BEBAN_ALAT' 		=> $this->input->post('BEBAN_ALAT'));
								
			$this->m_entry_provit->update($CODE_PROFLOSS, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_gl/c_entry_provit/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}