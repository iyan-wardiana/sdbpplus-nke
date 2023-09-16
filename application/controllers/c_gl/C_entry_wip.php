<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= Item_list.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 05 September 2017
 * File Name	= c_entry_wip.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_entry_wip  extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_gl/m_entry_wip/m_entry_wip', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_gl/c_entry_wip/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'WIP';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			$data["MenuCode"] 			= 'MN312';
			$this->load->view('v_gl/v_entry_wip/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_item() // OK
	{
		$this->load->model('m_gl/m_entry_wip/m_entry_wip', '', TRUE);
		
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
				
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'WIP List';
			$data['h3_title']	= 'WIP';
			$data['main_view'] 	= 'v_gl/v_entry_wip/entry_wip';
			$linkback			= site_url('c_gl/c_entry_wip/');
			//$data['link'] 		= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= $linkback;
			$data['MenuCode'] 	= 'MN312';
			
			$num_rows 				= $this->m_entry_wip->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['viewentry_wip'] 	= $this->m_entry_wip->get_last_ten_item($PRJCODE)->result();
			
			$this->load->view('v_gl/v_entry_wip/entry_wip', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_gl/m_entry_wip/m_entry_wip', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add WIP';
			$data['h3_title']		= 'WIP';
			$data['form_action']	= site_url('c_gl/c_entry_wip/add_process');
			$linkback				= site_url('c_gl/c_entry_wip/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkback;
			
			$data["MenuCode"] 		= 'MN312';		
			$data['recordcountUnit'] 	= $this->m_entry_wip->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_entry_wip->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_entry_wip->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_entry_wip->viewCateg()->result();
			$data['PRJCODE'] 			= $PRJCODE;
			
			$this->load->view('v_gl/v_entry_wip/entry_wip_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_gl/m_entry_wip/m_entry_wip', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
				
			$WIP_CODE 		= $this->input->post('WIP_CODE');
			$WIP_PERIODE 	= date('Y-m-d',strtotime($this->input->post('WIP_PERIODE')));
			$YEARP			= date('Y',strtotime($this->input->post('WIP_PERIODE')));
			$MONTHP			= (int)date('m',strtotime($this->input->post('WIP_PERIODE')));
			$WIP_PRJCODE 	= $this->input->post('WIP_PRJCODE');
			$WIP_SUPL 		= $this->input->post('WIP_SUPL');
			$WIP_TYPE	 	= $this->input->post('WIP_TYPE');
			$WIP_VALUE	 	= $this->input->post('WIP_VALUE');
			$WIP_STAT	 	= $this->input->post('WIP_STAT');
			$WIP_NOTE	 	= $this->input->post('WIP_NOTE');
			$WIP_CREATER 	= $this->input->post('WIP_CREATER');
			$WIP_CREATED 	= date('Y-m-d',strtotime($this->input->post('WIP_CREATED')));
			
			$itemPar 	= array('WIP_CODE' 		=> $this->input->post('WIP_CODE'),
							'WIP_PERIODE'		=> date('Y-m-d',strtotime($this->input->post('WIP_PERIODE'))),
							'WIP_PRJCODE'		=> $this->input->post('WIP_PRJCODE'),
							'WIP_SUPL'			=> $this->input->post('WIP_SUPL'),
							'WIP_TYPE'			=> $this->input->post('WIP_TYPE'),
							'WIP_VALUE'			=> $this->input->post('WIP_VALUE'),
							'WIP_STAT'			=> $this->input->post('WIP_STAT'),
							'WIP_NOTE'			=> $this->input->post('WIP_NOTE'),
							'WIP_CREATER'		=> $this->input->post('WIP_CREATER'),
							'WIP_CREATED'		=> date('Y-m-d',strtotime($this->input->post('WIP_CREATED'))));
							
			$this->m_entry_wip->add($itemPar);
							
			if($WIP_STAT == 3)
			{
				// Check untuk bulan yang sama
					$YEARP	= date('Y',strtotime($WIP_PERIODE));
					$MONTHP	= (int)date('m',strtotime($WIP_PERIODE));
				// BUAT TANGGAL AKHIR BULAN PER SI
					$LASTDATE	= date('Y-m-t', strtotime($WIP_PERIODE));
					
				$sqlPL	= "tbl_profitloss 
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					// GET PRJECT DETAIL			
						$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$WIP_PRJCODE'";
						$resPRJ	= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowPRJ) :
							$PRJNAME 	= $rowPRJ->PRJNAME;
							$PRJCOST 	= $rowPRJ->PRJCOST;
						endforeach;
						
					// GET WIP TOTAL PER MONTH
						$WIP_VALUE0	= 0;
						$WIP_VALUE1	= 0;
						$WIP_VALUE2	= 0;
						$WIP_VALUE3	= 0;
						$WIP_VALUE4	= 0;
						/*$sql_WIP0	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = $WIP_PRJCODE
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";								
						$res_WIP0	= $this->db->query($sql_WIP0)->result();
						foreach($res_WIP0 as $row_WIP0) :
							$WIP_VALUE0 = $row_WIP0->WIP_VALUE;
						endforeach;
						
						$sql_WIP1	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = $WIP_PRJCODE AND WIP_TYPE = 1
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
						$res_WIP1 	= $this->db->query($sql_WIP1)->result();
						foreach($res_WIP1 as $row_WIP1) :
							$WIP_VALUE1 = $row_WIP1->WIP_VALUE;
						endforeach;
						
						$sql_WIP2	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = $WIP_PRJCODE AND WIP_TYPE = 2
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
						$res_WIP2 	= $this->db->query($sql_WIP2)->result();
						foreach($res_WIP2 as $row_WIP2) :
							$WIP_VALUE2	= $row_WIP2->WIP_VALUE;
						endforeach;*/
						
						$sql_WIP3	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 3 
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
						$res_WIP3 	= $this->db->query($sql_WIP3)->result();
						foreach($res_WIP3 as $row_WIP3) :
							$WIP_VALUE3	= $row_WIP3->WIP_VALUE;
						endforeach;
						
						$sql_WIP4	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 4
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
						$res_WIP4 	= $this->db->query($sql_WIP4)->result();
						foreach($res_WIP4 as $row_WIP4) :
							$WIP_VALUE4	= $row_WIP4->WIP_VALUE;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, WIP_SUBKON, WIP_ALAT)
									VALUES ('$LASTDATE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$WIP_VALUE3', '$WIP_VALUE4')";
						$this->db->query($insPL);
				}
				else
				{
					// GET WIP TOTAL PER MONTH
						$sql_WIP3	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 3 
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
						$res_WIP3 	= $this->db->query($sql_WIP3)->result();
						foreach($res_WIP3 as $row_WIP3) :
							$WIP_VALUE3	= $row_WIP3->WIP_VALUE;
						endforeach;
						
						$sql_WIP4	= "SELECT SUM(WIP_VALUE) AS WIP_VALUE
										FROM tbl_wip
										WHERE WIP_PRJCODE = '$WIP_PRJCODE' AND WIP_TYPE = 4
										AND YEAR(WIP_PERIODE) = $YEARP AND MONTH(WIP_PERIODE) = $MONTHP AND WIP_STAT = 3";
						$res_WIP4 	= $this->db->query($sql_WIP4)->result();
						foreach($res_WIP4 as $row_WIP4) :
							$WIP_VALUE4	= $row_WIP4->WIP_VALUE;
						endforeach;
						
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET WIP_SUBKON = '$WIP_VALUE3', WIP_ALAT = '$WIP_VALUE4'
									WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$LASTDATE'";
						$this->db->query($updPL);
				}
			}
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_gl/c_entry_wip/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($WIP_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_gl/m_entry_wip/m_entry_wip', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$WIP_CODE	= $_GET['id'];
			$WIP_CODE	= $this->url_encryption_helper->decode_url($WIP_CODE);
			
			$geITEM = $this->m_entry_wip->get_Item_by_Code($WIP_CODE)->row();
			$WIP_PRJCODE = $geITEM->WIP_PRJCODE;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit WIP';
			$data['h3_title']		= 'WIP';
			$data['form_action']	= site_url('c_gl/c_entry_wip/update_process');
			$linkback				= site_url('c_gl/c_entry_wip/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($WIP_PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkback;
			$data["MenuCode"] 		= 'MN312';
			//$data['link'] 			= array('link_back' => anchor('c_gl/c_entry_wip','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
					
			$data['recordcountUnit'] 	= $this->m_entry_wip->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_entry_wip->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_entry_wip->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_entry_wip->viewCateg()->result();
			
						
			
			$data['default']['WIP_CODE'] 		= $geITEM->WIP_CODE;
			$data['default']['WIP_PERIODE']		= $geITEM->WIP_PERIODE;
			$data['default']['WIP_PRJCODE']		= $geITEM->WIP_PRJCODE;
			$data['default']['WIP_SUPL']		= $geITEM->WIP_SUPL;
			$data['default']['WIP_TYPE'] 		= $geITEM->WIP_TYPE;
			$data['default']['WIP_VALUE'] 		= $geITEM->WIP_VALUE;
			$data['default']['WIP_STAT'] 		= $geITEM->WIP_STAT;
			$data['default']['WIP_NOTE'] 		= $geITEM->WIP_NOTE;
			$data['default']['WIP_CREATER']		= $geITEM->WIP_CREATER;
			$data['default']['WIP_CREATED'] 	= $geITEM->WIP_CREATED;
			
			$this->load->view('v_gl/v_entry_wip/entry_wip_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_gl/m_entry_wip/m_entry_wip', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$WIP_CODE 		= $this->input->post('WIP_CODE');
			$WIP_PERIODE 	= date('Y-m-d',strtotime($this->input->post('WIP_PERIODE')));
			$YEARP			= date('Y',strtotime($this->input->post('WIP_PERIODE')));
			$MONTHP			= (int)date('m',strtotime($this->input->post('WIP_PERIODE')));
			$WIP_PRJCODE 	= $this->input->post('WIP_PRJCODE');
			$WIP_SUPL 		= $this->input->post('WIP_SUPL');
			$WIP_TYPE	 	= $this->input->post('WIP_TYPE');
			$WIP_VALUE	 	= $this->input->post('WIP_VALUE');
			$WIP_STAT	 	= $this->input->post('WIP_STAT');
			$WIP_NOTE	 	= $this->input->post('WIP_NOTE');
			$WIP_CREATER 	= $this->input->post('WIP_CREATER');
			$WIP_CREATED 	= date('Y-m-d',strtotime($this->input->post('WIP_CREATED')));
			
			$itemPar 	= array('WIP_CODE' 		=> $WIP_CODE,
							'WIP_PERIODE'		=> date('Y-m-d',strtotime($this->input->post('WIP_PERIODE'))),
							'WIP_PRJCODE'		=> $WIP_PRJCODE,
							'WIP_SUPL'			=> $this->input->post('WIP_SUPL'),
							'WIP_TYPE'			=> $this->input->post('WIP_TYPE'),
							'WIP_VALUE'			=> $this->input->post('WIP_VALUE'),
							'WIP_STAT'			=> $this->input->post('WIP_STAT'),
							'WIP_NOTE'			=> $this->input->post('WIP_NOTE'),
							'WIP_CREATER'		=> $this->input->post('WIP_CREATER'),
							'WIP_CREATED'		=> date('Y-m-d',strtotime($this->input->post('WIP_CREATED'))));
							
			$this->m_entry_wip->update($WIP_CODE, $itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_gl/c_entry_wip/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($WIP_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function changeStatusItem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CodeItem 			= $this->input->post('chkDetail');
			$gesd_tcost 		= $this->m_entry_wip->get_Item_by_Code($CodeItem)->row();
			
			$ItemStatus			= $gesd_tcost->Status;
			if($ItemStatus == 'Active')
			{
				$NItemStatus = 'InActive';
			}
			else
			{
				$NItemStatus = 'Active';
			}
							
			$this->m_entry_wip->updateStatus($CodeItem, $NItemStatus);
			
			redirect('c_gl/c_entry_wip/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($Item_Code)
	{
		$this->m_entry_wip->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_gl/c_entry_wip/');
	}
	
	function popupallitem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $MyAppName;;
			$data['h2_title'] 		= 'Select Item';
			$data['main_view'] 		= 'v_gl/v_entry_wip/Itemlist_sd_form';
			$data['form_action']	= site_url('c_gl/c_entry_wip/update_process');
			
			$data['recordcountAllItem'] = $this->m_entry_wip->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_entry_wip->viewAllItem()->result();
					
			$this->load->view('v_gl/v_entry_wip/purchase_reqselecsd_tcost_sd', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
    function inbox($offset=0)
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $MyAppName;
			$data['h2_title']	= 'Item List Inbox';
			$data['main_view'] 	= 'v_gl/v_entry_wip/Itemlist_inbox_sd';

			$num_rows = $this->m_entry_wip->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_gl/c_entry_wip/get_last_ten_item');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 2;
			$config["uri_segment"] = 3;
				
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
	 		
			$data['viewpurreq'] = $this->m_entry_wip->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
    }
}