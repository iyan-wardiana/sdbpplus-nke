<?php
/* 
 * Author		= Hendar Permana
 * Edit Date	= 05 September 2017
 * File Name	= c_entry_other.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_entry_other  extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_gl/m_entry_other/m_entry_other', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_gl/c_entry_other/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
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
			$data['h3_title']			= 'deviat. proggress';
		
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			$data["MenuCode"] 			= 'MN313';
			$this->load->view('v_gl/v_entry_other/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_item() // OK
	{
		$this->load->model('m_gl/m_entry_other/m_entry_other', '', TRUE);
		
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
				
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Deviation List';
			$data['h3_title']		= 'deviat. proggress';
			$data['main_view'] 		= 'v_gl/v_entry_other/entry_other';
				
			$linkback				= site_url('c_gl/c_entry_other/listproject/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkback;
			
			$num_rows 				= $this->m_entry_other->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['viewentry_other'] 	= $this->m_entry_other->get_last_ten_item($PRJCODE)->result();
			
			$this->load->view('v_gl/v_entry_other/entry_other', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_gl/m_entry_other/m_entry_other', '', TRUE);
		
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
			$data['h2_title']		= 'Add Deviation';
			$data['h3_title']		= 'deviat. proggress';
			$data['form_action']	= site_url('c_gl/c_entry_other/add_process');
			$linkback				= site_url('c_gl/c_entry_other/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkback;
					
			$data['recordcountUnit'] 	= $this->m_entry_other->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_entry_other->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_entry_other->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_entry_other->viewCateg()->result();
			$data['PRJCODE'] 			= $PRJCODE;
			$data["MenuCode"] 			= 'MN313';
			
			$this->load->view('v_gl/v_entry_other/entry_other_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_gl/m_entry_other/m_entry_other', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
				
			$FM_CODE 		= $this->input->post('FM_CODE');
			$FM_PERIODE 	= date('Y-m-d',strtotime($this->input->post('FM_PERIODE')));
			$YEARP			= date('Y',strtotime($this->input->post('FM_PERIODE')));
			$MONTHP			= (int)date('m',strtotime($this->input->post('FM_PERIODE')));
			$FM_PRJCODE 	= $this->input->post('FM_PRJCODE');
			$FM_PROGRES	 	= $this->input->post('FM_PROGRES');
			$FM_PREDICTION 	= $this->input->post('FM_PREDICTION');
			$FM_MCKATROLAN 	= $this->input->post('FM_MCKATROLAN');
			$FM_NOTE	 	= $this->input->post('FM_NOTE');
			$FM_STATUS	 	= $this->input->post('FM_STATUS');
			$FM_CREATER 	= $this->input->post('FM_CREATER');
			$FM_CREATED 	= date('Y-m-d',strtotime($this->input->post('FM_CREATED')));
			
			//cek duplikat
			/*$sqlCPerT	= "tbl_FM WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_TYPE = '$FM_TYPE'
							AND YEAR(FM_PERIODE) = $YEARP AND MONTH(FM_PERIODE) = $MONTHP AND FM_STAT = '3'";
			$resCCPerT	= $this->db->count_all($sqlCPerT);
				
				if($resCCPerT > 0)
				{	
					$sqlUpdT	= "UPDATE tbl_FM SET FM_STAT = '6'
										WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_TYPE = '$FM_TYPE'
										AND YEAR(FM_PERIODE) = $YEARP AND MONTH(FM_PERIODE) = $MONTHP AND FM_STAT = '3'";
					$this->db->query($sqlUpdT);
				}*/
				
				$itemPar 	= array('FM_CODE' 		=> $this->input->post('FM_CODE'),
									'FM_PERIODE'		=> date('Y-m-d',strtotime($this->input->post('FM_PERIODE'))),
									'FM_PRJCODE'		=> $this->input->post('FM_PRJCODE'),
									'FM_PROGRES'		=> $this->input->post('FM_PROGRES'),
									'FM_PREDICTION'		=> $this->input->post('FM_PREDICTION'),
									'FM_MCKATROLAN'		=> $this->input->post('FM_MCKATROLAN'),
									'FM_NOTE'			=> $this->input->post('FM_NOTE'),
									'FM_STATUS'			=> $this->input->post('FM_STATUS'),
									'FM_CREATER'		=> $this->input->post('FM_CREATER'),
									'FM_CREATED'		=> date('Y-m-d',strtotime($this->input->post('FM_CREATED'))));
			$this->m_entry_other->add($itemPar);
			
			if($FM_STATUS == 3)
			{
				// Add by DH for insert into profitloss on 2017-10-02
				// Check untuk bulan yang sama
				$FM_PERIODEY	= date('Y',strtotime($FM_PERIODE));
				$FM_PERIODEM	= (int)date('m',strtotime($FM_PERIODE));
				$sqlPL			= "tbl_profitloss
				 					WHERE PRJCODE = '$FM_PRJCODE' AND YEAR(PERIODE) = $FM_PERIODEY AND MONTH(PERIODE) = $FM_PERIODEM";
				$resPL	= $this->db->count_all($sqlPL);
				if($resPL == 0)
				{
					// GET PRJECT DETAIL			
						$sqlPRJ	= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$FM_PRJCODE'";
						$resPRJ	= $this->db->query($sqlPRJ)->result();
						foreach($resPRJ as $rowPRJ) :
							$PRJNAME 	= $rowPRJ->PRJNAME;
							$PRJCOST 	= $rowPRJ->PRJCOST;
						endforeach;
						
					// GET FM
						$FM_PROGRES		= 0;
						$FM_MCKATROLAN	= 0;
						$FM_PREDICTION	= 0;
						$sqlOTH			= "SELECT FM_PROGRES, FM_PREDICTION, FM_MCKATROLAN, FM_NOTE
											FROM tbl_profloss_man
											WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_PERIODE = '$FM_PERIODE'";
						$resOTH			= $this->db->query($sqlOTH)->result();
						foreach($resOTH as $rowOTH) :
							$FM_PROGRES		= $rowOTH ->FM_PROGRES;
							$FM_MCKATROL	= $rowOTH ->FM_MCKATROLAN;
							$FM_PREDIC		= $rowOTH ->FM_PREDICTION;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$insPL	= "INSERT INTO tbl_profitloss (PERIODE, PRJCODE, PRJNAME, PRJCOST, PLANPROG, MCCATROL, SIPREDIC)
									VALUES ('$FM_PERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$FM_PROGRES', '$FM_MCKATROL', 
									'$FM_PREDIC')";
						$this->db->query($insPL);
				}
				else
				{
					// GET FM
						$FM_PROGRES		= 0;
						$FM_MCKATROLAN	= 0;
						$FM_PREDICTION	= 0;
						$sqlOTH			= "SELECT FM_PROGRES, FM_PREDICTION, FM_MCKATROLAN, FM_NOTE
											FROM tbl_profloss_man
											WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_PERIODE = '$FM_PERIODE'";
						$resOTH			= $this->db->query($sqlOTH)->result();
						foreach($resOTH as $rowOTH) :
							$FM_PROGRES		= $rowOTH ->FM_PROGRES;
							$FM_MCKATROL	= $rowOTH ->FM_MCKATROLAN;
							$FM_PREDIC		= $rowOTH ->FM_PREDICTION;
						endforeach;
					
					// SAVE TO PROFITLOSS
						$updPL	= "UPDATE tbl_profitloss SET PLANPROG = '$FM_PROGRES', MCCATROL = '$FM_MCKATROL',
									SIPREDIC = '$FM_PREDIC' 
									WHERE PRJCODE = '$FM_PRJCODE' AND YEAR(PERIODE) = $FM_PERIODEY AND MONTH(PERIODE) = $FM_PERIODEM";
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
			
			$url			= site_url('c_gl/c_entry_other/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FM_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_gl/m_entry_other/m_entry_other', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$FM_CODE	= $_GET['id'];
			$FM_CODE	= $this->url_encryption_helper->decode_url($FM_CODE);
			
			$geITEM = $this->m_entry_other->get_Item_by_Code($FM_CODE)->row();
			$FM_PRJCODE = $geITEM->FM_PRJCODE;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Other';
			$data['h3_title']		= 'Other';
			$data['form_action']	= site_url('c_gl/c_entry_other/update_process');
			$linkback				= site_url('c_gl/c_entry_other/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FM_PRJCODE));
			//$data['link'] 			= array('link_back' => anchor("$linkback",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= $linkback;
			$data["MenuCode"] 		= 'MN313';
			
			//$data['link'] 			= array('link_back' => anchor('c_gl/c_entry_FM','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
					
			$data['recordcountUnit'] 	= $this->m_entry_other->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_entry_other->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_entry_other->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_entry_other->viewCateg()->result();
			
						
			
			$data['default']['FM_CODE'] 		= $geITEM->FM_CODE;
			$data['default']['FM_PERIODE']		= $geITEM->FM_PERIODE;
			$data['default']['FM_PRJCODE']		= $geITEM->FM_PRJCODE;
			$data['default']['FM_PROGRES'] 		= $geITEM->FM_PROGRES;
			$data['default']['FM_PREDICTION'] 	= $geITEM->FM_PREDICTION;
			$data['default']['FM_MCKATROLAN'] 	= $geITEM->FM_MCKATROLAN;
			$data['default']['FM_NOTE'] 		= $geITEM->FM_NOTE;
			$data['default']['FM_STATUS'] 		= $geITEM->FM_STATUS;
			$data['default']['FM_CREATER']		= $geITEM->FM_CREATER;
			$data['default']['FM_CREATED'] 		= $geITEM->FM_CREATED;
			//$data['default']['ITM_VOLM'] 		= $geITEM->ITM_VOLM;
			//$data['default']['ITM_IN'] 			= $geITEM->ITM_IN;
			//$data['default']['ITM_OUT'] 		= $geITEM->ITM_OUT;
			//$data['default']['ITM_PRICE']		= $geITEM->ITM_PRICE;
			//$data['default']['UMCODE'] 			= $geITEM->UMCODE;
			//$data['default']['Unit_Type_Name'] 	= $geITEM->Unit_Type_Name;
			//$data['default']['UMCODE'] 			= $geITEM->UMCODE;
			//$data['default']['STATUS'] 			= $geITEM->STATUS;
			//$data['default']['LASTNO'] 			= $geITEM->LASTNO;
			//$data['default']['ISRENT'] 			= $geITEM->ISRENT;
			//$data['default']['ISPART'] 			= $geITEM->ISPART;
			//$data['default']['ISFUEL'] 			= $geITEM->ISFUEL;
			//$data['default']['ISLUBRIC'] 		= $geITEM->ISLUBRIC;
			//$data['default']['ISFASTM'] 		= $geITEM->ISFASTM;
			//$data['default']['ISWAGE'] 			= $geITEM->ISWAGE;
			//$data['default']['ITM_KIND'] 		= $geITEM->ITM_KIND;
			//$data['default']['PRJCODE'] 		= $geITEM->PRJCODE;
			//$data['PRJCODE'] 					= $geITEM->PRJCODE;
			
			$this->load->view('v_gl/v_entry_other/entry_other_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_gl/m_entry_other/m_entry_other', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$FM_CODE 		= $this->input->post('FM_CODE');
			$FM_PERIODE 	= date('Y-m-d',strtotime($this->input->post('FM_PERIODE')));
			$YEARP			= date('Y',strtotime($this->input->post('FM_PERIODE')));
			$MONTHP			= (int)date('m',strtotime($this->input->post('FM_PERIODE')));
			$FM_PRJCODE 	= $this->input->post('FM_PRJCODE');
			$FM_PROGRES	 	= $this->input->post('FM_PROGRES');
			$FM_PREDICTION 	= $this->input->post('FM_PREDICTION');
			$FM_MC_KATROLAN	= $this->input->post('FM_MC_KATROLAN');
			$FM_NOTE	 	= $this->input->post('FM_NOTE');
			$FM_STATUS	 	= $this->input->post('FM_STATUS');
			$FM_CREATER 	= $this->input->post('FM_CREATER');
			$FM_CREATED 	= date('Y-m-d',strtotime($this->input->post('FM_CREATED')));
			
			//$ISRENT		= 0;
			//$ISPART		= 0;
			//$ISFUEL		= 0;
			//$ISLUBRIC	= 0;
			//$ISFASTM	= 0;
			//$ISWAGE		= 0;
			//$ITM_KIND	= $this->input->post('ITM_KIND');
			//echo "hehe $ITM_KIND - ";
			//if($ITM_KIND == 1)
			//	$ISRENT		= 1;
			//if($ITM_KIND == 2)
			//	$ISPART		= 1;
			//if($ITM_KIND == 3)
			//	$ISFUEL		= 1;
			//if($ITM_KIND == 4)
			//	$ISLUBRIC	= 1;
			//if($ITM_KIND == 5)
			//	$ISFASTM	= 1;
			//if($ITM_KIND == 6)
			//	$ISWAGE		= 1;
			
			//echo "$ISLUBRIC";
			//return false;
			
			//cek duplikat
			/*$sqlCPerT	= "tbl_FM WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_TYPE = '$FM_TYPE'
							AND YEAR(FM_PERIODE) = $YEARP AND MONTH(FM_PERIODE) = $MONTHP AND FM_STAT = '3'";
			$resCCPerT	= $this->db->count_all($sqlCPerT);
				
				if($resCCPerT > 0)
				{	
					$sqlUpdT	= "UPDATE tbl_FM SET FM_STAT = '6'
										WHERE FM_PRJCODE = '$FM_PRJCODE' AND FM_TYPE = '$FM_TYPE'
										AND YEAR(FM_PERIODE) = $YEARP AND MONTH(FM_PERIODE) = $MONTHP AND FM_STAT = '3'";
					$this->db->query($sqlUpdT);
				}*/
			
			$itemPar 	= array('FM_CODE' 		=> $FM_CODE,
							'FM_PERIODE'		=> date('Y-m-d',strtotime($this->input->post('FM_PERIODE'))),
							'FM_PRJCODE'		=> $FM_PRJCODE,
							'FM_PROGRES'		=> $this->input->post('FM_PROGRES'),
							'FM_PREDICTION'		=> $this->input->post('FM_PREDICTION'),
							'FM_MCKATROLAN'		=> $this->input->post('FM_MCKATROLAN'),
							'FM_NOTE'			=> $this->input->post('FM_NOTE'),
							'FM_STATUS'			=> $this->input->post('FM_STATUS'),
							'FM_CREATER'		=> $this->input->post('FM_CREATER'),
							'FM_CREATED'		=> date('Y-m-d',strtotime($this->input->post('FM_CREATED'))));
							//'UMCODE'			=> $this->input->post('ITM_UNIT'),
							//'ITM_CURRENCY'		=> $this->input->post('ITM_CURRENCY'),
							//'ITM_PRICE'			=> $this->input->post('ITM_PRICE'),
							//'ITM_VOLM'			=> $this->input->post('ITM_VOLM'),
							//'STATUS'			=> $this->input->post('STATUS'),
							//'ISRENT'			=> $ISRENT,
							//'ISPART'			=> $ISPART,
							//'ISFUEL'			=> $ISFUEL,
							//'ISLUBRIC'			=> $ISLUBRIC,
							//'ISFASTM'			=> $ISFASTM,
							//'ISWAGE'			=> $ISWAGE,
							//'ITM_KIND'			=> $ITM_KIND,
							//'LASTNO'			=> $this->input->post('LASTNO'));
							
			$this->m_entry_other->update($FM_CODE, $itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_gl/c_entry_other/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($FM_PRJCODE));
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
			$gesd_tcost 		= $this->m_entry_other->get_Item_by_Code($CodeItem)->row();
			
			$ItemStatus			= $gesd_tcost->Status;
			if($ItemStatus == 'Active')
			{
				$NItemStatus = 'InActive';
			}
			else
			{
				$NItemStatus = 'Active';
			}
							
			$this->m_entry_other->updateStatus($CodeItem, $NItemStatus);
			
			redirect('c_gl/c_entry_other/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($Item_Code)
	{
		$this->m_entry_other->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_gl/c_entry_other/');
	}
	
	function popupallitem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $MyAppName;;
			$data['h2_title'] 		= 'Select Item';
			$data['main_view'] 		= 'v_gl/v_entry_other/Itemlist_sd_form';
			$data['form_action']	= site_url('c_gl/c_entry_other/update_process');
			
			$data['recordcountAllItem'] = $this->m_entry_other->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_entry_other->viewAllItem()->result();
					
			$this->load->view('v_gl/v_entry_other/purchase_reqselecsd_tcost_sd', $data);
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
			$data['main_view'] 	= 'v_gl/v_entry_other/Itemlist_inbox_sd';

			$num_rows = $this->m_entry_other->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_gl/c_entry_other/get_last_ten_item');
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
	 		
			$data['viewpurreq'] = $this->m_entry_other->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
    }
}