<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Agustus 2017
 * File Name	= Profit_loss.php
 * Location		= -
*/

class Profit_loss  extends CI_Controller  
{
 	public function index() // U
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_gl/profit_loss/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1() // U
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 	= 'Project List';
			$data['h3_title'] 	= 'stock opname';
			
			$num_rows 			= $this->m_profit_loss->count_all_project($DefEmp_ID);
			$data["recCount"] 	= $num_rows;
	 
			$data['vwProject']	= $this->m_profit_loss->get_all_project($DefEmp_ID)->result();
			
			$this->load->view('v_gl/v_profit_loss/project_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_all_stock_opn() // U
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
						
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Stock List';
			$data['h3_title'] 		= 'stock opname';		
			$data['link'] 			= array('link_back' => anchor('c_gl/profit_loss/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 		= $PRJCODE;
			
			$num_rows 				= $this->m_profit_loss->count_all_stock_opn($PRJCODE);
			$data["recCount"] 		= $num_rows;
	 
			$data['vwStockOpn']		= $this->m_profit_loss->get_all_stock_opn($PRJCODE)->result();
			
			$this->load->view('v_gl/v_profit_loss/stock_opn_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // U
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$backURL			= site_url('c_gl/profit_loss/get_all_stock_opn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['PRJCODE'] 	= $PRJCODE;	
			$docPatternPosition = 'Especially';	
			$data['title'] 		= $appName;
			$data['task'] 		= 'add';
			$data['h2_title']	= 'Add';
			$data['h3_title']	= 'stock opname';
			$data['form_action']= site_url('c_gl/profit_loss/add_process');
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$data['recCountPrj']= $this->m_profit_loss->count_all_project($DefEmp_ID);
			$data['vwProject'] 	= $this->m_profit_loss->get_all_project($DefEmp_ID)->result();
			
			$MenuCode 			= 'MN302';
			$data['vwDocPatt'] 	= $this->m_profit_loss->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_gl/v_profit_loss/stock_opn_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallitem() // U
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'List Item';
			$data['h3_title'] 		= 'stock opname';
			$data['form_action']	= site_url('c_gl/profit_loss/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			$data['secShowAll']		= site_url('c_gl/profit_loss/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recCountItem'] 	= $this->m_profit_loss->count_Proj_Item($PRJCODE);
			$data['vwItem'] 		= $this->m_profit_loss->viewProjItem($PRJCODE)->result();
					
			$this->load->view('v_gl/v_profit_loss/select_item', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // U
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{			
			$SOPNH_CODE 	= $this->input->post('SOPNH_CODE');
			$SOPNH_MCODE 	= $this->input->post('SOPNH_MCODE');
			$SOPNH_PRJCODE 	= $this->input->post('SOPNH_PRJCODE');
			$PRJCODE 		= $this->input->post('SOPNH_PRJCODE');
			$SOPNH_PERIODE 	= date('Y-m-d',strtotime($this->input->post('SOPNH_PERIODE')));
			$SOPNH_WH 		= $this->input->post('SOPNH_WH');
			$SOPNH_NOTES 	= $this->input->post('SOPNH_NOTES');
			$SOPND_CREATED 	= date('Y-m-d H:i:s');;
			$SOPND_EMPID 	= $DefEmp_ID;
			$SOPNH_STAT		= $this->input->post('SOPNH_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			
			$SOPNH_DATE		= date('Y-m-d',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Number 	= $this->input->post('Patt_Number');
			
			$InpStockOpn 	= array('SOPNH_CODE' => $SOPNH_CODE,
								'SOPNH_MCODE' 	=> $SOPNH_MCODE,
								'SOPNH_DATE'	=> $SOPNH_DATE,
								'SOPNH_PRJCODE'	=> $SOPNH_PRJCODE,
								'SOPNH_PERIODE'	=> $SOPNH_PERIODE,
								'SOPNH_WH'		=> $SOPNH_WH,
								'SOPNH_NOTES'	=> $SOPNH_NOTES,
								'SOPND_CREATED'	=> $SOPND_CREATED, 
								'SOPND_EMPID'	=> $SOPND_EMPID, 
								'SOPNH_STAT'	=> $SOPNH_STAT, 
								'Patt_Year'		=> $Patt_Year, 
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $Patt_Number);
							
			$this->m_profit_loss->add($InpStockOpn);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_sopn_detail',$d);
			}
			
			// UPDATE GRAND TOTAL PRICE --  HARUS SUDAH APPROVE
				/*$GTPrice	= 0;
				$sqlGT 		= "SELECT SUM(ITEM_REM_PRICE) AS GTPrice FROM tbl_sopn_detail WHERE SOPND_PRJCODE = '$PRJCODE'";
				$resGT 		= $this->db->query($sqlGT)->result();
				foreach($resGT as $row) :
					$GTPrice = $row->GTPrice;		
				endforeach;
				
				$UpdGT 	= array('SOPND_GTPrice' => $GTPrice);
				
				$this->m_profit_loss->updateGT($SOPNH_CODE, $UpdGT);
*/		
			$url			= site_url('c_gl/profit_loss/get_all_stock_opn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // U
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$SOPNH_CODE		= $_GET['id'];
		$SOPNH_CODE		= $this->url_encryption_helper->decode_url($SOPNH_CODE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title']				= 'Update';
			$data['h3_title']				= 'stock opname';
			$data['form_action']			= site_url('c_gl/profit_loss/update_process');
			
			$data['recCountPrj']			= $this->m_profit_loss->count_all_project($DefEmp_ID);
			$data['vwProject'] 				= $this->m_profit_loss->get_all_project($DefEmp_ID)->result();
			
			$getStockOpn						= $this->m_profit_loss->get_stock_opn_det($SOPNH_CODE)->row();
			$data['default']['SOPNH_CODE'] 		= $getStockOpn->SOPNH_CODE;
			$data['default']['SOPNH_MCODE']		= $getStockOpn->SOPNH_MCODE;
			$data['default']['SOPNH_DATE']		= $getStockOpn->SOPNH_DATE;
			$data['default']['SOPNH_PRJCODE']	= $getStockOpn->SOPNH_PRJCODE;
			$data['PRJCODE']					= $getStockOpn->SOPNH_PRJCODE;
			$PRJCODE 							= $getStockOpn->SOPNH_PRJCODE;
			$data['default']['SOPNH_PERIODE'] 	= $getStockOpn->SOPNH_PERIODE;
			$data['default']['SOPNH_WH'] 		= $getStockOpn->SOPNH_WH;
			$data['default']['SOPNH_NOTES'] 	= $getStockOpn->SOPNH_NOTES;
			$data['default']['SOPNH_STAT'] 		= $getStockOpn->SOPNH_STAT;
			$data['default']['SOPNH_REVMEMO'] 	= $getStockOpn->SOPNH_REVMEMO;
			$data['default']['Patt_Year'] 		= $getStockOpn->Patt_Year;
			$data['default']['Patt_Month'] 		= $getStockOpn->Patt_Month;
			$data['default']['Patt_Date'] 		= $getStockOpn->Patt_Date;
			$data['default']['Patt_Number'] 	= $getStockOpn->Patt_Number;
			
			$backURL			= site_url('c_gl/profit_loss/get_all_stock_opn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 		= array('link_back' => anchor("$backURL",'<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$this->load->view('v_gl/v_profit_loss/stock_opn_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // U
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$SOPNH_CODE 	= $this->input->post('SOPNH_CODE');
			$SOPNH_MCODE 	= $this->input->post('SOPNH_MCODE');
			$SOPNH_PRJCODE 	= $this->input->post('SOPNH_PRJCODE');
			$PRJCODE 		= $this->input->post('SOPNH_PRJCODE');
			$SOPNH_PERIODE 	= date('Y-m-d',strtotime($this->input->post('SOPNH_PERIODE')));
			$SOPNH_WH 		= $this->input->post('SOPNH_WH');
			$SOPNH_NOTES 	= $this->input->post('SOPNH_NOTES');
			$SOPND_CREATED 	= date('Y-m-d H:i:s');;
			$SOPND_EMPID 	= $DefEmp_ID;
			$SOPNH_STAT		= $this->input->post('SOPNH_STAT'); // 1 = New, 2 = Awaiting, 3 = Approve, 4 = Revise, 5 = Reject
			
			$SOPNH_DATE		= date('Y-m-d',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Year		= date('Y',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Month		= date('m',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Date		= date('d',strtotime($this->input->post('SOPNH_DATE')));
			$Patt_Number 	= $this->input->post('Patt_Number');
			
			$UpdStockOpn 	= array('SOPNH_CODE' => $SOPNH_CODE,
								'SOPNH_MCODE' 	=> $SOPNH_MCODE,
								'SOPNH_DATE'	=> $SOPNH_DATE,
								'SOPNH_PRJCODE'	=> $SOPNH_PRJCODE,
								'SOPNH_PERIODE'	=> $SOPNH_PERIODE,
								'SOPNH_WH'		=> $SOPNH_WH,
								'SOPNH_NOTES'	=> $SOPNH_NOTES,
								'SOPND_CREATED'	=> $SOPND_CREATED, 
								'SOPND_EMPID'	=> $SOPND_EMPID, 
								'SOPNH_STAT'	=> $SOPNH_STAT, 
								'Patt_Year'		=> $Patt_Year, 
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $Patt_Number);
			
			$this->m_profit_loss->update($SOPNH_CODE, $UpdStockOpn);
			$this->m_profit_loss->deleteDetail($SOPNH_CODE);
			
			foreach($_POST['data'] as $d)
			{
				$this->db->insert('tbl_sopn_detail',$d);
			}
			
			// UPDATE GRAND TOTAL PRICE -- INGAT !!! APABILA ADA REVISE HARUS ADA PENGURANGAN
			$GTPrice	= 0;
			$sqlGT 		= "SELECT SUM(ITEM_REM_PRICE) AS GTPrice 
							FROM tbl_sopn_detail
							WHERE SOPND_PRJCODE = '$PRJCODE' AND SOPND_STAT = 3";
			$resGT 		= $this->db->query($sqlGT)->result();
			foreach($resGT as $row) :
				$GTPrice = $row->GTPrice;		
			endforeach;
			
			/*$UpdStOpnRep 	= array('RSOPN_PRJCODE'	=> $SOPNH_PRJCODE,
								'RSOPN_DATE'	=> $SOPNH_PERIODE,
								'RSOPN_YEAR'	=> date('Y',strtotime($SOPNH_PERIODE)),
								'RSOPN_MONTH'	=> date('m',strtotime($SOPNH_PERIODE)),
								'RSOPN_DAY'		=> date('d',strtotime($SOPNH_PERIODE)), 
								'RSOPN_QTYIN'	=> $RSOPN_QTYIN, 
								'RSOPN_QTYOUT'	=> $RSOPN_QTYOUT, 
								'RSOPN_PRICE'	=> $RSOPN_PRICE, 
								'RSOPN_INP'		=> $RSOPN_INP,
								'RSOPN_OUTP'	=> $RSOPN_OUTP,
								'RSOPN_REMQTY'	=> $RSOPN_REMQTY,
								'RSOPN_REMP'	=> $RSOPN_REMP);
			
			$UpdGT 	= array('SOPND_GTPrice' => $GTPrice);
			
			$this->m_profit_loss->updateGT($SOPNH_CODE, $UpdGT);*/
		
			$url			= site_url('c_gl/profit_loss/get_all_stock_opn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
 	public function inbox()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/material_request/inbox1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function inbox1($offset=0)
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			//$data['secAddURL'] 		= site_url('c_project/material_request_sd/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['h2_title']			= 'Planning List';
			$data['main_view'] 			= 'v_project/v_material_request/project_planning_inb';
			//$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/material_request_sd'),'inbox_src');
			$data['moffset'] 			= $offset;

			//$num_rows = $this->m_profit_loss->count_all_num_rows();
			$num_rows 					= $this->m_profit_loss->count_all_num_rowsInbox($DefEmp_ID);		
			$data["recordcount"] 		= $num_rows;
			
			$config 					= array();
			$config['base_url'] 		= site_url('c_project/project_planning/get_last_ten_project');
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"] 		= 3;				
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
		
			$data['vewproject'] 		= $this->m_profit_loss->get_last_ten_projectInbox($config["per_page"], $offset, $DefEmp_ID)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_project/v_material_request/project_planning_inb', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_all_stock_opnInb($offset=0)
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			// Secure URL
			$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 		= $PRJCODE; // Session Project Per User
			
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Material Request';
			$data['main_view'] 			= 'v_project/v_material_request/material_request_inbox';
			//$data['srch_url']			= site_url('c_project/material_request/get_all_stock_opnInb_src/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['link'] 				= array('link_back' => anchor('c_project/material_request/inbox','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Back" />', array('style' => 'text-decoration: none;')));
			$data['PRJCODE'] 			= $PRJCODE;
			$data['moffset'] 			= $offset;	
			
			$num_rows 					= $this->m_profit_loss->count_all_stock_opn_Inb($PRJCODE);
			$data["recordcount"] 		= $num_rows;
			$data["recordcount1"]		= $num_rows;
			$config						= array();
			
			$config['base_url'] 		= site_url('c_project/material_request_sd/get_all_stock_opn');
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 15;
			$config["uri_segment"] 		= 3;				
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
	
			$data['viewprojmatreq'] 	= $this->m_profit_loss->get_all_stock_opnInb($PRJCODE, $config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			/*$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');*/
			// End : Searching Function	
			
			
			$this->load->view('v_project/v_material_request/material_request_inbox', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_inbox()
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		$SPPNUM		= $_GET['id'];
		$SPPNUM		= $this->url_encryption_helper->decode_url($SPPNUM);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 					= $appName;
			$data['task'] 					= 'edit';
			$data['h2_title'] 				= 'Update Request';
			$data['main_view'] 				= 'v_project/v_material_request/material_request_form_inbox';
			$data['form_action']			= site_url('c_project/material_request/update_process_inbox');
			
			$data['recordcountProject']		= $this->m_profit_loss->count_all_num_rowsProject();
			$data['viewProject'] 			= $this->m_profit_loss->viewProject()->result();
			
			$getpurreq 						= $this->m_profit_loss->get_stock_opn_det($SPPNUM)->row();
			$data['default']['SPPNUM'] 		= $getpurreq->SPPNUM;
			$data['default']['PRJCODE']		= $getpurreq->PRJCODE;
			$data['default']['SPPCODE']		= $getpurreq->SPPCODE;
			$PRJCODE 						= $getpurreq->PRJCODE;
			$data['PRJCODE']				= $getpurreq->PRJCODE;
			$data['default']['TRXDATE'] 	= $getpurreq->TRXDATE;
			$data['default']['PRJCODE'] 	= $getpurreq->PRJCODE;
			$data['default']['TRXOPEN'] 	= $getpurreq->TRXOPEN;
			$data['default']['TRXUSER'] 	= $getpurreq->TRXUSER;
			$data['default']['APPROVE'] 	= $getpurreq->APPROVE;
			$data['default']['APPRUSR'] 	= $getpurreq->APPRUSR;
			$data['default']['JOBCODE'] 	= $getpurreq->JOBCODE;
			$data['default']['SPPNOTE'] 	= $getpurreq->SPPNOTE;
			$data['default']['SPPSTAT'] 	= $getpurreq->SPPSTAT;
			$data['default']['REVMEMO'] 	= $getpurreq->REVMEMO;
			$data['default']['PRJNAME'] 	= $getpurreq->PRJNAME;
			$data['default']['Patt_Year'] 	= $getpurreq->Patt_Year;
			$data['default']['Patt_Month'] 	= $getpurreq->Patt_Month;
			$data['default']['Patt_Date'] 	= $getpurreq->Patt_Date;
			$data['default']['Patt_Number']	= $getpurreq->Patt_Number;
			
			
			$cancelURL						= site_url('c_project/material_request/get_all_stock_opnInb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data['link'] 					= array('link_back' => anchor("$cancelURL",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));			
			
			$this->load->view('v_project/v_material_request/material_request_form_inbox', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process_inbox()
	{
		$this->load->model('m_gl/m_profit_loss/m_profit_loss', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Setting Approve Date
			date_default_timezone_set("Asia/Jakarta");
			
			$APPDATE 			= date('Y-m-d H:i:s');
			$PRJCODE 			= $this->input->post('PRJCODE');
			$SPPNUM 			= $this->input->post('SPPNUM');
			$SPPCODE 			= $this->input->post('SPPCODE');
			$REVMEMO			= $this->input->post('REVMEMO');
			$APPROVE			= $this->input->post('APPROVE');
			$APPRUSR			= $this->session->userdata['Emp_ID'];
			
			// Dapatkan status MR sebelum ada perubahan status
			$sqlSPPN 		= "SELECT APPROVE FROM tbl_spp_header WHERE SPPNUM = '$SPPNUM' AND PRJCODE = '$PRJCODE'";
			$resSPPN 		= $this->db->query($sqlSPPN)->result();
			foreach($resSPPN as $rowSPPN) :
				$APPROVEBEF = $rowSPPN->APPROVE;		
			endforeach;
			
			$projMatReqH = array('APPROVE'		=> $APPROVE,
							'SPPSTAT'			=> $APPROVE,
							'REVMEMO'			=> $REVMEMO,
							'APPDATE'			=> $APPDATE,
							'APPRUSR'			=> $APPRUSR);
							
			$this->m_profit_loss->update_inbox($SPPNUM, $projMatReqH);
			if($APPROVE == 3)
			{
				foreach($_POST['data'] as $d)
				{
					$SPPVOLM 	= $d['SPPVOLM'];
					$CSTCODE 	= $d['CSTCODE'];
					$parameters = array(
							'PRJCODE' 	=> $PRJCODE,
							'SPPNUM' 	=> $SPPNUM,
							'SPPCODE' 	=> $SPPCODE,
							'SPPVOLM' 	=> $SPPVOLM,
							'CSTCODE' 	=> $CSTCODE							
						);
					$this->m_profit_loss->updatePP($SPPNUM, $parameters);
				}
			}
			
			// UPDATE TO TRANS-COUNT
				if($APPROVEBEF != $APPROVE)
				{
					// Kurangi 1 hitungan untuk MR dengan status TR_CONFIRM
						$sqlTRS 		= "SELECT TR_CONFIRM FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_CONFIRMBEF = $rowTRS->TR_CONFIRM;		
						endforeach;							
						$TR_CONFIRMNOW		= $TR_CONFIRMBEF - 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_CONFIRM = $TR_CONFIRMNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
						
					// JIKA APPROVE
					if($APPROVE == 3) // Kondisinya berarti Status Approve = 1, Status SPP = 2 (confirm)
					{
						$sqlTRS 		= "SELECT TR_APPROVED FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_APPROVEDBEF = $rowTRS->TR_APPROVED;		
						endforeach;							
						$TR_APPROVEDNOW		= $TR_APPROVEDBEF + 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_APPROVED = $TR_APPROVEDNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
					// JIKA REVISE
					elseif($APPROVE == 4)
					{
						$sqlTRS 		= "SELECT TR_REVISE FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_REVISEBEF = $rowTRS->TR_REVISE;		
						endforeach;							
						$TR_REVISENOW		= $TR_REVISEBEF + 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_REVISE = $TR_REVISENOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
					// JIKA REVISE
					elseif($APPROVE == 5)
					{
						$sqlTRS 		= "SELECT TR_REJECT FROM tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$resTRS			= $this->db->query($sqlTRS)->result();
						foreach($resTRS as $rowTRS) :
							$TR_REJECTBEF = $rowTRS->TR_REJECT;		
						endforeach;							
						$TR_REJECTNOW		= $TR_REJECTBEF + 1;				
						$sqlUpd1		= "UPDATE tbl_trans_count SET TR_REJECT = $TR_REJECTNOW WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = 'SPP'";
						$this->db->query($sqlUpd1);
					}
				}
		
			$url			= site_url('c_project/material_request/get_all_stock_opnInb/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpr()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Select Purchase Requisition';
			$data['form_action']	= site_url('c_project/material_request_sd/update_process');
			$data['txtRefference'] 	= '';
			$data['resultCount']	= 0;
			$data['pageFrom']		= 'PR';
			
			$data['recordcountAllPR'] = $this->m_profit_loss->count_all_num_rowsAllPR();
			$data['viewAllPR'] 	= $this->m_profit_loss->viewAllPR()->result();
					
			$this->load->view('v_project/v_listproject/purchase_selectpr', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function popupallpr2()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;
		$data['h2_title'] 		= 'Select Item';
		$data['form_action']	= site_url('c_project/material_request_sd/update_process');
		$data['txtRefference'] 	= '';
		$data['resultCount']	= 0;
		$data['pageFrom']		= 'DIR'; //DIR = Direct (non PR)
		
		$data['recordcountAllItem'] = $this->m_profit_loss->count_all_Item();
		$data['viewAllItem'] 	= $this->m_profit_loss->viewAllItem()->result();
				
		$this->load->view('v_project/v_listproject/purchase_selectitem', $data);
	}
	
	function delete($PR_Number)
	{
		$this->m_profit_loss->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_project/material_request_sd/');
	}
	
	function getVendAddress($vendCode)
	{
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}
	
	function printdocument($MR_Number)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Document Print';
			$data['form_action']	= site_url('c_project/material_request_sd/update_process_inbox');
			
			$data['recordcountVend'] 	= $this->m_profit_loss->count_all_num_rowsVend();
			$data['viewvendor'] 	= $this->m_profit_loss->viewvendor()->result();
			$data['recordcountDept'] 	= $this->m_profit_loss->count_all_num_rowsDept();
			$data['viewDepartment'] 	= $this->m_profit_loss->viewDepartment()->result();
			$data['recordcountEmpDept'] 	= $this->m_profit_loss->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->m_profit_loss->viewEmployeeDept()->result();
			$data['recordcountProject']	= $this->m_profit_loss->count_all_num_rowsProject();
			$data['viewProject'] 		= $this->m_profit_loss->viewProject()->result();
			
			$getpurreq = $this->m_profit_loss->get_stock_opn_det($MR_Number)->row();
			
			$this->session->set_userdata('MR_Number', $getpurreq->MR_Number);
			
			$data['link'] 			= array('link_back' => anchor('c_project/material_request_sd/get_all_stock_opnInb/'.$getpurreq->proj_Code,'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			
			$data['default']['proj_ID'] = $getpurreq->proj_ID;
			$data['default']['proj_Code'] = $getpurreq->proj_Code;
			$data['default']['MR_Number'] = $getpurreq->MR_Number;
			$data['default']['MR_Date'] = $getpurreq->MR_Date;
			$data['default']['req_date'] = $getpurreq->req_date;
			$data['default']['latest_date'] = $getpurreq->latest_date;
			$data['default']['MR_Class'] = $getpurreq->MR_Class;
			$data['default']['MR_Type'] = $getpurreq->MR_Type;
			$data['default']['MR_DepID'] = $getpurreq->MR_DepID;
			$data['default']['MR_EmpID'] = $getpurreq->MR_EmpID;
			$data['default']['Vend_Code'] = $getpurreq->Vend_Code;
			$data['default']['MR_Notes'] = $getpurreq->MR_Notes;
			$data['default']['MR_Status'] = $getpurreq->MR_Status;
			$data['default']['Approval_Status'] = $getpurreq->Approval_Status;
			$data['default']['Patt_Year'] = $getpurreq->Patt_Year;
			$data['default']['Patt_Number'] = $getpurreq->Patt_Number;
			$data['default']['Memo_Revisi'] = $getpurreq->Memo_Revisi;
							
			$this->load->view('v_project/v_material_request/print_matreq', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	/*function get_last_ten_projList_src($offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			// Secure URL
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$DefProj_Code		 	= $this->session->userdata['sessionProject']['mysessionProject']; // Session Project Per User
			
			$data['secAddURL'] 		= site_url('c_project/material_request_sd/add');
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/material_request_sd'),'index');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/material_request_sd'),'get_last_ten_projList_src');
			
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Project Planning List';
			$data['main_view'] 		= 'v_project/v_material_request/project_planning_sd';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			$config 				= array();
			$config["per_page"] 	= 20;
			$config["uri_segment"] 	= 3;
			$config['cur_page']		= $offset;
			
			$data['selSearchType'] 	= $this->input->post('selSearchType');
			$data['txtSearch'] 		= $this->input->post('txtSearch');
			$selSearchType			= $this->input->post('selSearchType');
			$txtSearch 				= $this->input->post('txtSearch');
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows 				= $this->m_profit_loss->count_all_num_rows_PNo($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_profit_loss->get_last_ten_project_PNo($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			else
			{
				$num_rows 				= $this->m_profit_loss->count_all_num_rows_PNm($txtSearch, $DefEmp_ID);
				$data["recordcount"] 	= $num_rows;
				$data['vewproject'] 	= $this->m_profit_loss->get_last_ten_project_PNm($config["per_page"], $offset, $txtSearch, $DefEmp_ID)->result();
			}
			
			$config["total_rows"] 		= $num_rows;
			$config['base_url'] 		= site_url('c_project/material_request_sd/get_last_ten_projList');			
			
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open'] 	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link']		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close']	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open']	 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close']	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data["pagination"] 		= $this->pagination->create_links();
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchproj_Code' => $DefProj_Code,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			// End : Searching Function	
			
			$this->load->view('template', $data);		
		}
		else
		{
			redirect('Auth');
		}
	}
*/

	/*function get_all_stock_opn_src($offset=0) // HOLD
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			// Secure URL
			$data['secAddURL'] 			= site_url('c_project/material_request_sd/add');
			$data['showIdxMReq']		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/material_request_sd'),'get_all_stock_opn');		
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Material Request';
			$data['main_view'] 			= 'v_project/v_material_request/material_request_sd';			
			$data['link'] 				= array('link_back' => anchor('c_project/material_request_sd/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Back" />', array('style' => 'text-decoration: none;')));
			
			$data['selSearchType'] 		= $this->input->post('selSearchType');
			$data['txtSearch'] 			= $this->input->post('txtSearch');	
			$selSearchType				= $this->input->post('selSearchType');
			$txtSearch 					= $this->input->post('txtSearch');
				
			$data['PRJCODE'] 			= $this->input->post('PRJCODE');
			$PRJCODE 					= $this->input->post('PRJCODE');	
			$data['PRJCODE'] 			= $PRJCODE;
			$data['PRJCODE1'] 			= $PRJCODE;
			$data['moffset'] 			= $offset;	
			$data['PRJCODE'] 			= $PRJCODE;	
			
			if($selSearchType == 'MRNumber')
			{
				$num_rows = $this->m_profit_loss->count_all_num_rows_projMatReq_srcMR($txtSearch);
			}
			else
			{
				$num_rows = $this->m_profit_loss->count_all_num_rows_projMatReq_srcPN($txtSearch);
			}
			
			//$num_rows = $this->m_profit_loss->count_all_num_rows_projMatReq_src();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/material_request_sd/get_all_stock_opn');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 15;
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
			
			if($selSearchType == 'MRNumber')
			{
				$data['viewprojmatreq'] = $this->m_profit_loss->get_all_stock_opn_MRNo($config["per_page"], $offset, $txtSearch)->result();
			}
			else
			{
				$data['viewprojmatreq'] = $this->m_profit_loss->get_all_stock_opn_PNm($config["per_page"], $offset, $txtSearch)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();	
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('Auth');
		}
	}*/
}