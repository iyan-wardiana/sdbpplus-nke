<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Mei 2018
 * File Name	= C_it180e2elst.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_it180e2elst  extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_inventory/m_itemlist/m_masteritem', '', TRUE);
	
		$this->data['UserID'] 	= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 	= $this->session->userdata['appName'];
		
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
	
 	function index() // GOOD
	{
		$url	= site_url('c_inventory/c_it180e2elst/P7j_l5t/?id='.$this->url_encryption_helper->encode_url($this->data['appName']));
		redirect($url);
	}
	
	function P7j_l5t() // GOOD
	{
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h1_title"] 	= "List of Material";
			}
			
			$num_rows 			= $this->m_projectlist->count_all_project($DefEmp_ID);
			$data["countPRJ"] 	= $num_rows;
			$data["MenuCode"] 	= 'MN017';	 
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();
			$data['viewPRJ'] 	= $this->m_projectlist->get_all_project($DefEmp_ID)->result();			
			$data["secURL"] 	= "c_inventory/c_it180e2elst/it180e2elst_lti/?id=";
			
			$this->load->view('v_projectlist/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function it180e2elst_lti() // GOOD
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');
				
			$data['title'] 		= $this->data['appName'];
			$data['h2_title'] 	= 'Item List';
			$data['h3_title']	= 'inventory';
			$data['secAdd'] 	= site_url('c_inventory/c_it180e2elst/it180e2elst_upl/?id='.$this->url_encryption_helper->encode_url($PRJCODE));			
			$data['backURL']	= site_url('c_inventory/c_it180e2elst/P7j_l5t/?id='.$this->url_encryption_helper->encode_url($this->data['appName']));
						
			$data['PRJCODE'] 	= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN188';
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
			
			$this->load->view('v_inventory/v_itemlist/item_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_data_item() // GOOD
	{
		$this->load->model('m_inventory/m_itemlist/m_masteritem', '', TRUE);
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$PRJCODE		= $_GET['id'];
		
		$draw			= $_REQUEST['draw'];
		$length			= $_REQUEST['length'];
		$start			= $_REQUEST['start'];
		$search			= $_REQUEST['search']["value"];
		$num_rows 		= $this->m_itemlist->get_dataItmC($PRJCODE, $search);
		$total			= $num_rows;
		$output			= array();
		$output['draw']	= $draw;
		$output['recordsTotal'] = $output['recordsFiltered']= $total;
		$output['data']	= array();
		$query 			= $this->m_itemlist->get_dataItmV($PRJCODE, $search, $length,$start);
							
		$noU			= $start + 1;
		foreach ($query->result_array() as $dataI) 
		{
			$ITM_CODE	= $dataI['ITM_CODE'];
			$ITM_NAME2	= $dataI['ITM_NAME'];
			$ITM_NAME	= cut_text2 ("$ITM_NAME2", 40);
			$STATUS		= $dataI['STATUS'];
			if($STATUS == 1)
			{
				$STATUSD 	= 'Active';
				$STATCOL	= 'success';
			}
			elseif($STATUS == 0)
			{
				$STATUSD 	= 'In Active';
				$STATCOL	= 'danger';
			}
			else
			{
				$STATUSD 	= 'Fake';
				$STATCOL	= 'danger';
			}
			
			$COLLVAR		= "$PRJCODE~$ITM_CODE";
			$secUpd			= site_url('c_inventory/c_it180e2elst/update/?id='.$this->url_encryption_helper->encode_url($COLLVAR));
			$secUpd2		= "<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								<i class='glyphicon glyphicon-pencil'></i></a>";
			
			$output['data'][] = array($dataI['ITM_CODE'],
									  $ITM_NAME,
									  number_format($dataI['ITM_VOLMBG'], 2),
									  number_format($dataI['ITM_IN'], 2),
									  number_format($dataI['ITM_VOLMBGR'], 2),
									  number_format($dataI['ITM_OUT'], 2),
									  number_format($dataI['ITM_VOLM'], 2),
									  number_format($dataI['ITM_PRICE'], 2),
									  $dataI['ITM_UNIT'],
									  "<span class='label label-".$STATCOL."' style='font-size:12px'>".$STATUSD."</span>",
									  $secUpd2);
			$noU++;
		}

		echo json_encode($output);
	}
	
	function it180e2elst_upl() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$backURL	= site_url('c_inventory/c_it180e2elst/it180e2elst_lti/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['ITMH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master item';
			$data['form_action']	= site_url('c_inventory/c_it180e2elst/do_upload');
			$data['backURL'] 		= $backURL;
			
			$this->load->view('v_inventory/v_itemlist/v_item_upload_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$ITMH_DATE		= date('Y-m-d H:i:s');
			$ITMH_DATEY		= date('Y');
			$ITMH_DATEM		= date('m');
			$ITMH_DATED		= date('d');
			$ITMH_DATEH		= date('H');
			$ITMH_DATEm		= date('i');
			$ITMH_DATES		= date('s');
			
			$ITMH_CODE		= "ITM$ITMH_DATEY$ITMH_DATEM$ITMH_DATED-$ITMH_DATEH$ITMH_DATEm$ITMH_DATES";
			$ITMH_DATE		= date('Y-m-d H:i:s');
			$ITMH_PRJCODE	= $this->input->post('PRJCODE');
			$ITMH_DESC		= $this->input->post('ITMH_DESC');
			$ITMH_USER		= $DefEmp_ID;
			$ITMH_STAT		= 1;
			
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
					
			$filename 	= $_FILES["userfile"]["name"];
			$source 	= $_FILES["userfile"]["tmp_name"];
			$type 		= $_FILES["userfile"]["type"];
			
			$name 		= explode(".", $filename);
			$fileExt	= $name[1];
			
			$target_path = "import_excel/import_item/".$filename;  // change this to the correct site path
				
			$myPath 	= "import_excel/import_item/$filename";
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
			
			$data['isUploaded']	= 1;	
			if(move_uploaded_file($source, $target_path))
			{
				$message = "Your file was uploaded";
				$data['message'] 	= $message;
				$data['isSuccess']	= 1;
				$data['ITMH_DESC']	= $ITMH_DESC;
				
				//$this->m_itemlist->updateStat();
				
				$ItemHist = array('ITMH_CODE' 	=> $ITMH_CODE,
								'ITMH_DATE'		=> $ITMH_DATE,
								'ITMH_PRJCODE'	=> $ITMH_PRJCODE,
								'ITMH_DESC'		=> $ITMH_DESC,
								'ITMH_FN'		=> $filename,
								'ITMH_USER'		=> $ITMH_USER,
								'ITMH_STAT'		=> $ITMH_STAT);

				$this->m_itemlist->add_importitem($ItemHist);
			} 
			else 
			{	
				$message = "There was a problem with the upload. Please try again.";
				$data['message'] 	= $message;
				$data['isSuccess']	= 0;
				$data['ITMH_DESC']	= $ITMH_DESC;
			}
			
			$backURL				= site_url('c_inventory/c_it180e2elst/it180e2elst_lti/?id='.$this->url_encryption_helper->encode_url($ITMH_PRJCODE));
			$data['isProcess'] 		= 1;
			$data['PRJCODE']		= $ITMH_PRJCODE;
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master item';
			$data['form_action']	= site_url('c_inventory/c_it180e2elst/do_upload');
			$data['backURL'] 		= $backURL;
			
			//$this->load->view('v_inventory/v_itemlist/v_item_upload_form', $data);
			
			$url			= site_url('c_inventory/c_it180e2elst/it180e2elst_upl/?id='.$this->url_encryption_helper->encode_url($ITMH_PRJCODE));
			redirect($url);
		}
	}
	
	function a180e2edd() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$LangID = $this->session->userdata['LangID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Item';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_it180e2elst/add_process');
			$data['backURL'] 		= site_url('c_inventory/c_it180e2elst/');
			$data["MenuCode"] 		= 'MN188';
					
			$data['recUType'] 		= $this->m_itemlist->count_all_num_rowsUnit();
			$data['viewUnit'] 		= $this->m_itemlist->viewunit()->result();
			//$data['recCateg'] 	= $this->m_itemlist->count_all_num_rowsCateg();
			//$data['viewCateg'] 	= $this->m_itemlist->viewCateg()->result();
			$data['PRJCODE'] 		= $PRJCODE;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN188';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_inventory/v_itemlist/item_list_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$ITM_CATEG 	= $this->input->post('ITM_CATEG');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$ITM_CODE 	= $this->input->post('ITM_CODE');
			$ITM_CODE_H	= $this->input->post('ITM_CODE_H');
			$JOBCODEID	= $this->input->post('JOBCODEID');
			
			$ISRENT		= 0;
			$ISPART		= 0;
			$ISFUEL		= 0;
			$ISLUBRIC	= 0;
			$ISFASTM	= 0;
			$ISWAGE		= 0;
			$ISMTRL		= 0;
			$ISRM		= 0;
			$ISWIP		= 0;
			$ISFG		= 0;
			$ISCOST		= 0;
			$ITM_KIND	= $this->input->post('ITM_KIND');
			if($ITM_KIND == 'ISRENT')
			{
				$ISRENT		= 1;
				$ITM_KIND	= 1;
			}
			if($ITM_KIND == 'ISPART')
			{
				$ISPART		= 1;
				$ITM_KIND	= 2;
			}
			if($ITM_KIND == 'ISFUEL')
			{
				$ISFUEL		= 1;
				$ITM_KIND	= 3;
			}
			if($ITM_KIND == 'ISLUBRIC')
			{
				$ISLUBRIC	= 1;
				$ITM_KIND	= 4;
			}
			if($ITM_KIND == 'ISFASTM')
			{
				$ISFASTM	= 1;
				$ITM_KIND	= 5;
			}
			if($ITM_KIND == 'ISWAGE')
			{
				$ISWAGE		= 1;
				$ITM_KIND	= 6;
			}
			if($ITM_KIND == 'ISMTRL')
			{
				$ISMTRL		= 1;
				$ITM_KIND	= 7;
			}
			if($ITM_KIND == 'ISRM')
			{
				$ISRM		= 1;
				$ITM_KIND	= 8;
			}
			if($ITM_KIND == 'ISWIP')
			{
				$ISWIP		= 1;
				$ITM_KIND	= 9;
			}
			if($ITM_KIND == 'ISFG')
			{
				$ISFG		= 1;
				$ITM_KIND	= 10;
			}
			if($ITM_KIND == 'ISCOST')
			{
				$ISFG		= 1;
				$ITM_KIND	= 11;
			}
			else
			{
				$ITM_KIND	= 0;
				$ITM_KIND	= 0;
			}
			
			$ITM_VOLMBG		= $this->input->post('ITM_VOLMBG');
			$ITM_VOLM		= $this->input->post('ITM_VOLM');
			$ITM_PRICE		= $this->input->post('ITM_PRICE');
			
			if($ITM_VOLMBG == 0)
				$ITM_VOLMBG	= $this->input->post('ITM_VOLM');
			elseif($ITM_VOLMBG < $ITM_VOLM)
				$ITM_VOLMBG	= $this->input->post('ITM_VOLM');
				
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_VOLM;
			
			$ITM_IN			= $this->input->post('ITM_VOLM');
			$ITM_INP		= $ITM_IN * $ITM_PRICE;
			$ITM_TOTALP		= $ITM_VOLM * $ITM_PRICE;
			
			$itemPar 	= array('PRJCODE' 		=> $PRJCODE,
								'ITM_CODE'		=> $ITM_CODE,
								'ITM_CODE_H'	=> $ITM_CODE_H,
								'JOBCODEID'		=> $JOBCODEID,
								'ITM_GROUP'		=> $this->input->post('ITM_GROUP'),
								'ITM_CATEG'		=> $this->input->post('ITM_CATEG'),
								'ITM_NAME'		=> $this->input->post('ITM_NAME'),
								'ITM_DESC'		=> $this->input->post('ITM_DESC'),
								'ITM_TYPE'		=> $this->input->post('ITM_TYPE'),
								'ITM_UNIT'		=> $this->input->post('ITM_UNIT'),
								'UMCODE'		=> $this->input->post('ITM_UNIT'),
								'ITM_CURRENCY'	=> $this->input->post('ITM_CURRENCY'),
								'ITM_PRICE'		=> $this->input->post('ITM_PRICE'),
								'ITM_LASTP'		=> $this->input->post('ITM_PRICE'),
								'ITM_TOTALP'	=> $ITM_TOTALP,
								'ITM_VOLMBG'	=> $ITM_VOLMBG,
								'ITM_VOLMBGR'	=> $ITM_VOLMBGR,
								'ITM_VOLM'		=> $this->input->post('ITM_VOLM'),
								'ACC_ID'		=> $this->input->post('ACC_ID'),
								'ACC_ID_UM'		=> $this->input->post('ACC_ID_UM'),
								'ITM_IN'		=> $ITM_IN,
								'ITM_INP'		=> $ITM_INP,
								'STATUS'		=> $this->input->post('STATUS'),
								'ISRENT'		=> $ISRENT,
								'ISPART'		=> $ISPART,
								'ISFUEL'		=> $ISFUEL,
								'ISLUBRIC'		=> $ISLUBRIC,
								'ISFASTM'		=> $ISFASTM,
								'ISWAGE'		=> $ISWAGE,
								'ISMTRL'		=> $ISMTRL,
								'ISRM'			=> $ISRM,
								'ISWIP'			=> $ISWIP,
								'ISFG'			=> $ISFG,
								'ISCOST'		=> $ISCOST,
								'ITM_KIND'		=> $ITM_KIND,
								'ISMAJOR'		=> $this->input->post('ISMAJOR'),
								'LASTNO'		=> $this->input->post('LASTNO'));
			$this->m_itemlist->add($itemPar);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITM_CODE;
				$MenuCode 		= 'MN188';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_it180e2elst/it180e2elst_lti/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$ITM_CODE	= $_GET['id'];
			$ITM_CODE	= $this->url_encryption_helper->decode_url($ITM_CODE);
			
			$ITM_CODEA	= explode("~", $ITM_CODE);
			$PRJCODE	= $ITM_CODEA[0];
			$ITM_CODE	= $ITM_CODEA[1];
			
			$data['title'] 			= $this->data['appName'];
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Item';
			$data['h3_title']		= 'inventory';
			$data['form_action']	= site_url('c_inventory/c_it180e2elst/update_process');
			//$data['link'] 			= array('link_back' => anchor('c_inventory/c_it180e2elst/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_inventory/c_it180e2elst/');
			$data["MenuCode"] 		= 'MN188';
					
			$data['recUType'] 		= $this->m_itemlist->count_all_num_rowsUnit();
			$data['viewUnit'] 		= $this->m_itemlist->viewunit()->result();
			$data['recCateg'] 		= $this->m_itemlist->count_all_num_rowsCateg();
			$data['viewCateg'] 		= $this->m_itemlist->viewCateg()->result();
			
			$geITEM = $this->m_itemlist->get_Item_by_Code($ITM_CODE, $PRJCODE)->row();			
			
			$data['default']['ITM_CODE'] 		= $geITEM->ITM_CODE;
			$data['default']['ITM_CODE_H'] 		= $geITEM->ITM_CODE_H;
			$data['default']['JOBCODEID'] 		= $geITEM->JOBCODEID;
			$data['default']['ITM_NAME'] 		= $geITEM->ITM_NAME;
			$data['default']['ITM_GROUP'] 		= $geITEM->ITM_GROUP;
			$data['default']['ITM_CATEG'] 		= $geITEM->ITM_CATEG;
			$data['default']['ITM_DESC'] 		= $geITEM->ITM_DESC;
			$data['default']['ITM_TYPE'] 		= $geITEM->ITM_TYPE;
			$data['default']['ITM_UNIT'] 		= $geITEM->ITM_UNIT;
			$data['default']['ITM_CURRENCY'] 	= $geITEM->ITM_CURRENCY;
			$data['default']['ITM_VOLMBG'] 		= $geITEM->ITM_VOLMBG;
			$data['default']['ITM_VOLM'] 		= $geITEM->ITM_VOLM;
			$data['default']['ADDVOLM'] 		= $geITEM->ADDVOLM;
			$data['default']['ADDMVOLM'] 		= $geITEM->ADDMVOLM;
			$data['default']['ITM_IN'] 			= $geITEM->ITM_IN;
			$data['default']['ITM_OUT'] 		= $geITEM->ITM_OUT;
			$data['default']['ITM_PRICE']		= $geITEM->ITM_PRICE;
			$data['default']['ITM_LASTP']		= $geITEM->ITM_LASTP;
			$data['default']['UMCODE'] 			= $geITEM->UMCODE;
			$data['default']['Unit_Type_Name'] 	= $geITEM->Unit_Type_Name;
			$data['default']['UMCODE'] 			= $geITEM->UMCODE;
			$data['default']['STATUS'] 			= $geITEM->STATUS;
			$data['default']['LASTNO'] 			= $geITEM->LASTNO;
			$data['default']['ISMTRL'] 			= $geITEM->ISMTRL;
			$data['default']['ISRENT'] 			= $geITEM->ISRENT;
			$data['default']['ISPART'] 			= $geITEM->ISPART;
			$data['default']['ISFUEL'] 			= $geITEM->ISFUEL;
			$data['default']['ISLUBRIC'] 		= $geITEM->ISLUBRIC;
			$data['default']['ISFASTM'] 		= $geITEM->ISFASTM;
			$data['default']['ISWAGE'] 			= $geITEM->ISWAGE;
			$data['default']['ISRM'] 			= $geITEM->ISRM;
			$data['default']['ISWIP'] 			= $geITEM->ISWIP;
			$data['default']['ISFG'] 			= $geITEM->ISFG;
			$data['default']['ISCOST'] 			= $geITEM->ISCOST;
			$data['default']['ITM_KIND'] 		= $geITEM->ITM_KIND;
			$data['default']['PRJCODE'] 		= $geITEM->PRJCODE;
			$data['default']['ACC_ID'] 			= $geITEM->ACC_ID;
			$data['default']['ACC_ID_UM'] 		= $geITEM->ACC_ID_UM;
			$data['PRJCODE'] 					= $geITEM->PRJCODE;
			$data['default']['ISMAJOR'] 		= $geITEM->ISMAJOR;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $geITEM->ITM_CODE;
				$MenuCode 		= 'MN188';
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
			
			$this->load->view('v_inventory/v_itemlist/item_list_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$ITM_CATEG 	= $this->input->post('ITM_CATEG');
			$PRJCODE 	= $this->input->post('PRJCODE');
			$ITM_CODE 	= $this->input->post('ITM_CODE');
			$ITM_CODE_H	= $this->input->post('ITM_CODE_H');
			$JOBCODEID	= $this->input->post('JOBCODEID');
			$ITM_GROUP	= $this->input->post('ITM_GROUP');
								
			$ISRENT		= 0;
			$ISPART		= 0;
			$ISFUEL		= 0;
			$ISLUBRIC	= 0;
			$ISFASTM	= 0;
			$ISWAGE		= 0;
			$ISMTRL		= 0;
			$ISRM		= 0;
			$ISWIP		= 0;
			$ISFG		= 0;
			$ISCOST		= 0;
			$ITM_KIND	= $this->input->post('ITM_KIND');
			if($ITM_KIND == 'ISRENT')
			{
				$ISRENT		= 1;
				$ITM_KIND	= 1;
			}
			if($ITM_KIND == 'ISPART')
			{
				$ISPART		= 1;
				$ITM_KIND	= 2;
			}
			if($ITM_KIND == 'ISFUEL')
			{
				$ISFUEL		= 1;
				$ITM_KIND	= 3;
			}
			if($ITM_KIND == 'ISLUBRIC')
			{
				$ISLUBRIC	= 1;
				$ITM_KIND	= 4;
			}
			if($ITM_KIND == 'ISFASTM')
			{
				$ISFASTM	= 1;
				$ITM_KIND	= 5;
			}
			if($ITM_KIND == 'ISWAGE')
			{
				$ISWAGE		= 1;
				$ITM_KIND	= 6;
			}
			if($ITM_KIND == 'ISMTRL')
			{
				$ISMTRL		= 1;
				$ITM_KIND	= 7;
			}
			if($ITM_KIND == 'ISRM')
			{
				$ISRM		= 1;
				$ITM_KIND	= 8;
			}
			if($ITM_KIND == 'ISWIP')
			{
				$ISWIP		= 1;
				$ITM_KIND	= 9;
			}
			if($ITM_KIND == 'ISFG')
			{
				$ISFG		= 1;
				$ITM_KIND	= 10;
			}
			if($ITM_KIND == 'ISCOST')
			{
				$ISCOST		= 1;
				$ITM_KIND	= 11;
			}
			else
			{
				$ITM_KIND	= 0;
				$ITM_KIND	= 0;
			}
			
			$geITEM = $this->m_itemlist->get_Item_by_Code($ITM_CODE, $PRJCODE)->row();
			
			$ITM_VOLMBG1 	= $geITEM->ITM_VOLMBG;
			$ITM_VOLMBGR1 	= $geITEM->ITM_VOLMBGR;
			$ITM_VOLM1 		= $geITEM->ITM_VOLM;
			$ITM_IN1 		= $geITEM->ITM_IN;
			$ITM_INP1 		= $geITEM->ITM_INP;
			$ITM_OUT1 		= $geITEM->ITM_OUT;
			$ITM_PRICE1		= $geITEM->ITM_PRICE;
			$ITM_LASTP1		= $geITEM->ITM_LASTP;
			
			$ACC_ID			= $this->input->post('ACC_ID');
			
			$ITM_VOLMBG		= $this->input->post('ITM_VOLMBG');
			$ITM_VOLM		= $this->input->post('ITM_VOLM');
			$ITM_PRICE		= $this->input->post('ITM_PRICE');
			
			$ITM_TOTALP		= $ITM_VOLM1 * $ITM_PRICE;
			
			$itemPar 	= array('PRJCODE' 		=> $PRJCODE,
								'ITM_CODE'		=> $ITM_CODE,
								'ITM_CODE_H'	=> $ITM_CODE_H,
								'JOBCODEID'		=> $JOBCODEID,
								'ITM_GROUP'		=> $this->input->post('ITM_GROUP'),
								'ITM_CATEG'		=> $this->input->post('ITM_CATEG'),
								'ITM_NAME'		=> $this->input->post('ITM_NAME'),
								'ITM_DESC'		=> $this->input->post('ITM_DESC'),
								'ITM_TYPE'		=> $this->input->post('ITM_TYPE'),
								'ITM_UNIT'		=> $this->input->post('ITM_UNIT'),
								'UMCODE'		=> $this->input->post('ITM_UNIT'),
								'ITM_CURRENCY'	=> $this->input->post('ITM_CURRENCY'),
								'ITM_PRICE'		=> $this->input->post('ITM_PRICE'),
								'ITM_LASTP'		=> $this->input->post('ITM_PRICE'),
								'ITM_TOTALP'	=> $ITM_TOTALP,
								'ITM_VOLMBG'	=> $ITM_VOLMBG1,
								'ITM_VOLMBGR'	=> $ITM_VOLMBGR1,
								'ITM_VOLM'		=> $this->input->post('ITM_VOLM'),
								'ACC_ID'		=> $this->input->post('ACC_ID'),
								'ACC_ID_UM'		=> $this->input->post('ACC_ID_UM'),
								'ITM_IN'		=> $ITM_IN1,
								'ITM_INP'		=> $ITM_INP1,
								'STATUS'		=> $this->input->post('STATUS'),
								'ISRENT'		=> $ISRENT,
								'ISPART'		=> $ISPART,
								'ISFUEL'		=> $ISFUEL,
								'ISLUBRIC'		=> $ISLUBRIC,
								'ISFASTM'		=> $ISFASTM,
								'ISWAGE'		=> $ISWAGE,
								'ISMTRL'		=> $ISMTRL,
								'ISRM'			=> $ISRM,
								'ISWIP'			=> $ISWIP,
								'ISFG'			=> $ISFG,
								'ISCOST'		=> $ISCOST,
								'ITM_KIND'		=> $ITM_KIND,
								'ISMAJOR'		=> $this->input->post('ISMAJOR'));
			$this->m_itemlist->update($ITM_CODE, $itemPar);
			
			$upWBSD	= "UPDATE tbl_joblist_detail SET ITM_GROUP = '$ITM_GROUP' WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($upWBSD);
			
			$upWBS	= "UPDATE tbl_joblist SET JOBGRP = '$ITM_GROUP' WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($upWBS);
			
			$upBOQ	= "UPDATE tbl_boqlist SET JOBGRP = '$ITM_GROUP' WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($upBOQ);
						
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= $PRJCODE;
				$TTR_REFDOC		= $ITM_CODE;
				$MenuCode 		= 'MN188';
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
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_inventory/c_it180e2elst/it180e2elst_lti/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function changeStatusItem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CodeItem 			= $this->input->post('chkDetail');
			$gesd_tcost 		= $this->m_itemlist->get_Item_by_Code($CodeItem)->row();
			
			$ItemStatus			= $gesd_tcost->Status;
			if($ItemStatus == 'Active')
			{
				$NItemStatus = 'InActive';
			}
			else
			{
				$NItemStatus = 'Active';
			}
							
			$this->m_itemlist->updateStatus($CodeItem, $NItemStatus);
			
			redirect('c_inventory/c_it180e2elst/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function delete($Item_Code)
	{
		$this->m_itemlist->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_inventory/c_it180e2elst/');
	}
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
			
			$COLLID		= $_GET['id'];
			$COLLID		= $this->url_encryption_helper->decode_url($COLLID);
			$plitWord	= explode('~', $COLLID);
			$PRJCODE	= $plitWord[0];
			$ITM_GROUP	= $plitWord[1];
			
			$data['title'] 			= $this->data['appName'];
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h2_title"] 	= "Daftar Material";
			}
			else
			{
				$data["h2_title"] 	= "List Item";
			}
			
			$data['form_action']	= site_url('c_purchase/c_pr180d0c/update_process');
			$data['PRJCODE'] 		= $PRJCODE;
			
			$data['countAllItem']	= $this->m_itemlist->count_all_Item($PRJCODE, $ITM_GROUP);
			$data['vwAllItem'] 		= $this->m_itemlist->view_all_Item($PRJCODE, $ITM_GROUP)->result();
					
			$this->load->view('v_inventory/v_itemlist/v_selitem', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function inbox($offset=0)
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $MyAppName;
			$data['h2_title']	= 'Item List Inbox';
			$data['main_view'] 	= 'v_inventory/v_itemlist/Itemlist_inbox_sd';

			$num_rows = $this->m_itemlist->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_inventory/c_it180e2elst/it180e2elst_lti');
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
	 		
			$data['viewpurreq'] = $this->m_itemlist->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function view_itemup() // OK
	{
		$this->load->model('m_inventory/m_itemlist/m_itemlist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$ITMH_CODE	= $_GET['id'];
			$ITMH_CODE	= $this->url_encryption_helper->decode_url($ITMH_CODE);
			
			$sqlPRJ		= "SELECT ITMH_PRJCODE FROM tbl_item_uphist WHERE ITMH_CODE = '$ITMH_CODE'";
			$sqlPRJR	= $this->db->query($sqlPRJ)->result();
			foreach($sqlPRJR as $rowPRJ) :
				$ITMH_PRJ		= $rowPRJ->ITMH_PRJCODE;
			endforeach;
	
			$data['PRJCODE']		= $ITMH_PRJ;
			$data['ITMH_CODE']		= $ITMH_CODE;
			$data['title'] 			= $this->data['appName'];
			$data['h2_title']		= 'View';
			$data['h3_title'] 		= 'master item';
			
			$this->load->view('v_inventory/v_itemlist/v_item_view_xl', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function downloadFile()
	{
		$this->load->helper('download');

		$collLink	= $_GET['id'];
		$collLink	= $this->url_encryption_helper->decode_url($collLink);
		$collLink1	= explode('~', $collLink);
		$theLink	= $collLink1[0];
		$FileUpName	= $collLink1[1];
		header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);
	}
}