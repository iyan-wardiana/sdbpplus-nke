<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= C_cu5t.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_cu5t extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_sales/m_cust', '', TRUE);
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
		
		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
			if(isset($_GET['id']))
			{
				$collDATA		= $_GET['id'];
				$EXP_COLLD1		= $this->url_encryption_helper->decode_url($collDATA);
			}
			else
			{
				$EXP_COLLD1		= '';
			}
			
			$EXP_COLLD		= explode('~', $EXP_COLLD1);	
			$C_COLLD1		= count($EXP_COLLD);
			if($C_COLLD1 > 1)
			{
				$EXP_COLLD1	= str_replace('~', '', $EXP_COLLD1);
				$PRJCODE	= $EXP_COLLD[0];
			}
			else
			{
				$PRJCODE	= $EXP_COLLD1;
			}
		
			//$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
			$sqlISHO 		= "SELECT PRJCODE, PRJCODE_HO FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$this->data['PRJCODE']		= $rowISHO->PRJCODE;
				$this->data['PRJCODE_HO']	= $rowISHO->PRJCODE_HO;
			endforeach;
	}

 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_sales/c_cu5t/g37_4ll_cu5t/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function g37_4ll_cu5t() // G
	{
		$this->load->model('m_sales/m_cust', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName				= $_GET['id'];
			$appName				= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;		
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Daftar Pelanggan";
				$data['h2_title'] 	= 'Penjualan';
			}
			else
			{
				$data["h1_title"] 	= "Customer List";
				$data['h2_title'] 	= 'Sales';
			}
			
			$data['secAddURL'] 		= site_url('c_sales/c_cu5t/a44/?id='.$this->url_encryption_helper->encode_url($appName));
			$data["MenuCode"] 		= 'MN036';
			$num_rows 				= $this->m_cust->count_all_cust();
			$data['countCUST'] 		= $num_rows;	 
			$data['vwCUST'] 		= $this->m_cust->get_all_cust()->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN036';
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
			
			$this->load->view('v_sales/v_cust/v_cust', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllData() // GOOD
	{
		//$PRJCODE		= $_GET['id'];
		
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
			
			$columns_valid 	= array("CUST_CODE", 
									"CUST_DESC", 
									"CUST_ADD1",
									"CUST_KOTA",
									"CUST_TELP");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_cust->get_AllDataC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_cust->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$CUST_CODE	= $dataI['CUST_CODE'];
				$CUST_DESC	= $dataI['CUST_DESC'];
				$CUST_ADD1	= $dataI['CUST_ADD1'];
				$CUST_KOTA	= $dataI['CUST_KOTA'];
				$CUST_TELP	= $dataI['CUST_TELP'] ?: "-";
				$CUST_MAIL	= strtolower($dataI['CUST_MAIL']) ?: "-";
				$CUST_NPWP	= $dataI['CUST_NPWP'] ?: "-";
				$CUST_BANK	= $dataI['CUST_BANK'] ?: "-";
				$CUST_NOREK	= $dataI['CUST_NOREK'] ?: "-";
				$CUST_NMREK	= $dataI['CUST_NMREK'] ?: "-";
				$CUST_STAT	= $dataI['CUST_STAT'];

				$secUpd			= site_url('c_sales/c_cu5t/up4/?id='.$this->url_encryption_helper->encode_url($CUST_CODE));
				
				$IMGC_FILENAMEX	= '';
				$sqlGetIMG		= "SELECT IMGC_FILENAME, IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE'";
				$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
				foreach($resGetIMG as $rowGIMG) :
					$IMGC_FILENAME 	= $rowGIMG ->IMGC_FILENAME;
					$IMGC_FILENAMEX = $rowGIMG ->IMGC_FILENAMEX;
				endforeach;
			
				$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE.'/'.$IMGC_FILENAMEX);
				if (!file_exists('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE))
				{
					$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/username.jpg');
				}

				$secDelIcut = base_url().'index.php/__l1y/trashCust/?id=';
				$delID 		= "$secDelIcut~$CUST_CODE";
				$secActIcut = base_url().'index.php/__l1y/acthCust/?id=';
				$actID 		= "$secActIcut~$CUST_CODE";
                                    
				if($CUST_STAT == 0) 
				{
					$secPrint	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlAct".$noU."' id='urlAct".$noU."' value='".$actID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='Aktifkan' onClick='reActivated(".$noU.")'>
                                        <i class='glyphicon glyphicon-repeat'></i>
                                    </a>
									</label>";
				}
				else
				{
					$secPrint	= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$delID."'>
									<input type='hidden' name='urlAct".$noU."' id='urlAct".$noU."' value='".$actID."'>
								   	<label style='white-space:nowrap'>
								   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
								   		<i class='glyphicon glyphicon-pencil'></i>
								   	</a>
								   	<a href='javascript:void(null);' class='btn btn-success btn-xs' title='Non aktifkan' onClick='deleteDOC(".$noU.")'>
                                        <i class='glyphicon glyphicon-ok'></i>
                                    </a>
									</label>";
				}
				
				$output['data'][] = array($CUST_CODE,
										  $CUST_DESC."<br>".$CUST_NPWP,
										  "$CUST_ADD1<br>
										  <strong><i class='fa fa-phone margin-r-5'></i></strong>$CUST_TELP<br>
										  <strong><i class='glyphicon glyphicon-envelope margin-r-5'></i></strong>$CUST_MAIL",
										  $CUST_BANK."<br>".$CUST_NOREK."<br>a/n : ".$CUST_NMREK,
										  "<img class='direct-chat-img' src='".$imgLoc."' style=border:groove; border-color:#0C3' >",
										  $secPrint);
				$noU			= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a44() // G
	{
		$this->load->model('m_sales/m_cust', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';	
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Tambah Pelanggan";
				$data['h2_title'] 	= 'Penjualan';
			}
			else
			{
				$data["h1_title"] 	= "Add Customer";
				$data['h2_title'] 	= 'Sales';
			}
			
			$data['form_action']	= site_url('c_sales/c_cu5t/add_process');
			$data['urlUpdProfPic']	= site_url('c_sales/c_cu5t/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= site_url('c_sales/c_cu5t/');
			
			$MenuCode 				= 'MN036';
			$data['MenuCode'] 		= 'MN036';
			$data['viewDocPattern'] = $this->m_cust->getDataDocPat($MenuCode)->result();
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN036';
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
			
			$this->load->view('v_sales/v_cust/v_cust_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // G
	{
		$this->load->model('m_sales/m_cust', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$customer 	= array('CUST_CODE' 	=> $this->input->post('CUST_CODE'),
								'CUST_DESC'		=> addslashes($this->input->post('CUST_DESC')),
								'CUST_CAT'		=> $this->input->post('CUST_CAT'),
								'CUST_ADD1'		=> addslashes($this->input->post('CUST_ADD1')),
								'CUST_KOTA'		=> addslashes($this->input->post('CUST_KOTA')),
								'CUST_NPWP'		=> $this->input->post('CUST_NPWP'),
								'CUST_PERS'		=> $this->input->post('CUST_PERS'),
								'CUST_TELP'		=> $this->input->post('CUST_TELP'),
								'CUST_MAIL'		=> $this->input->post('CUST_MAIL'),
								'CUST_NOREK'	=> $this->input->post('CUST_NOREK'),
								'CUST_NMREK'	=> $this->input->post('CUST_NMREK'),
								'CUST_BANK'		=> $this->input->post('CUST_BANK'),
								'CUST_OTHR'		=> $this->input->post('CUST_OTHR'),
								'CUST_CAT'		=> $this->input->post('CUST_CAT'),
								'CUST_TOP'		=> $this->input->post('CUST_TOP'),
								'CUST_TOPD'		=> $this->input->post('CUST_TOPD'),
								'CUST_STAT'		=> $this->input->post('CUST_STAT'));
			$this->m_cust->add($customer);
			
			$customerImg	= array('IMGC_CUSTCODE' 	=> $this->input->post('CUST_CODE'),
									'IMGC_FILENAME'		=> 'username',
									'IMGC_FILENAMEX'	=> 'username.jpg');
			$this->m_cust->add3($customerImg);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('CUST_CODE');
				$MenuCode 		= 'MN036';
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
			
			$url			= site_url('c_sales/c_cu5t/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload() // G
	{
		$this->load->model('m_sales/m_cust', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$CUST_CODE 			= $this->input->post('CUST_CODE');
		
		// CEK FILE
        $file 				= $_FILES['userfile'];
		$nameFile			= $_FILES["userfile"]["name"];
		$ext 				= end((explode(".", $nameFile)));
       	$fileInpName 		= $this->input->post('FileName');
			//echo "fileInpName = $fileInpName = $nameFile";
		if (!file_exists('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE))
		{
			mkdir('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE, 0777, true);
		}
		
		$file 						= $_FILES['userfile'];
		$file_name 					= $file['name'];
		$config['upload_path']   	= "assets/AdminLTE-2.0.5/cust_image/$CUST_CODE/"; 
		$config['allowed_types']	= 'gif|jpg|png'; 
		$config['overwrite'] 		= TRUE;
		$config['max_size']     	= 1000000; 
		$config['max_width']    	= 10024; 
		$config['max_height']    	= 10000;  
		$config['file_name']       	= $file['name'];
		
        $this->load->library('upload', $config);
		
        if ( ! $this->upload->do_upload('userfile')) 
		{
			$data['CUST_CODE']		= $CUST_CODE;
			$data['task'] 			= 'edit';
         }
         else 
		 {
            $data['path']			= $file_name;
			$data['CUST_CODE']		= $CUST_CODE;
			$data['task'] 			= 'edit';
            $data['showSetting']	= 0;
            $this->m_cust->updateProfPict($CUST_CODE, $nameFile, $fileInpName);
         }
		 
		$url			= site_url('c_sales/c_cu5t/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function up4() // G
	{
		$this->load->model('m_sales/m_cust', '', TRUE);
		$CUST_CODE	= $_GET['id'];
		$CUST_CODE	= $this->url_encryption_helper->decode_url($CUST_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data["h1_title"] 	= "Edit Pelanggan";
				$data['h2_title'] 	= 'Penjualan';
			}
			else
			{
				$data["h1_title"] 	= "Edit Customer";
				$data['h2_title'] 	= 'Sales';
			}
			
			$data['form_action']	= site_url('c_sales/c_cu5t/update_process');
			$data['urlUpdProfPic']	= site_url('c_sales/c_cu5t/do_upload/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['backURL'] 		= site_url('c_sales/c_cu5t/');
			
			$MenuCode 				= 'MN036';
			$data['MenuCode'] 		= 'MN036';
			$getcust 				= $this->m_cust->get_cust_by_code($CUST_CODE)->row();
			
			$data['default']['CUST_CODE']	= $getcust->CUST_CODE;
			$data['default']['CUST_DESC']	= $getcust->CUST_DESC;
			$data['default']['CUST_CAT']	= $getcust->CUST_CAT;
			$data['default']['CUST_ADD1']	= $getcust->CUST_ADD1;
			$data['default']['CUST_KOTA']	= $getcust->CUST_KOTA;
			$data['default']['CUST_NPWP']	= $getcust->CUST_NPWP;
			$data['default']['CUST_PERS']	= $getcust->CUST_PERS;
			$data['default']['CUST_TELP']	= $getcust->CUST_TELP;
			$data['default']['CUST_MAIL']	= $getcust->CUST_MAIL;
			$data['default']['CUST_NOREK']	= $getcust->CUST_NOREK;
			$data['default']['CUST_NMREK']	= $getcust->CUST_NMREK;
			$data['default']['CUST_BANK']	= $getcust->CUST_BANK;
			$data['default']['CUST_OTHR']	= $getcust->CUST_OTHR;
			$data['default']['CUST_CAT']	= $getcust->CUST_CAT;
			$data['default']['CUST_TOP']	= $getcust->CUST_TOP;
			$data['default']['CUST_TOPD']	= $getcust->CUST_TOPD;
			$data['default']['CUST_STAT']	= $getcust->CUST_STAT;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getcust->CUST_CODE;
				$MenuCode 		= 'MN036';
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
			
			$this->load->view('v_sales/v_cust/v_cust_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{
		$this->load->model('m_sales/m_cust', '', TRUE);
			
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$CUST_CODE	= $this->input->post('CUST_CODE');
			
			$customer 	= array('CUST_DESC'		=> addslashes($this->input->post('CUST_DESC')),
								'CUST_CAT'		=> $this->input->post('CUST_CAT'),
								'CUST_ADD1'		=> addslashes($this->input->post('CUST_ADD1')),
								'CUST_KOTA'		=> addslashes($this->input->post('CUST_KOTA')),
								'CUST_NPWP'		=> $this->input->post('CUST_NPWP'),
								'CUST_PERS'		=> $this->input->post('CUST_PERS'),
								'CUST_TELP'		=> $this->input->post('CUST_TELP'),
								'CUST_MAIL'		=> $this->input->post('CUST_MAIL'),
								'CUST_NOREK'	=> $this->input->post('CUST_NOREK'),
								'CUST_NMREK'	=> $this->input->post('CUST_NMREK'),
								'CUST_BANK'		=> $this->input->post('CUST_BANK'),
								'CUST_OTHR'		=> $this->input->post('CUST_OTHR'),
								'CUST_CAT'		=> $this->input->post('CUST_CAT'),
								'CUST_TOP'		=> $this->input->post('CUST_TOP'),
								'CUST_TOPD'		=> $this->input->post('CUST_TOPD'),
								'CUST_STAT'		=> $this->input->post('CUST_STAT'));
			$this->m_cust->update($CUST_CODE, $customer);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $CUST_CODE;
				$MenuCode 		= 'MN036';
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
			
			$url			= site_url('c_sales/c_cu5t/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getcustomerCODE($cinta) // OK
	{ 
		$this->load->model('m_sales/m_cust', '', TRUE);
		$recordcountVCAT 	= $this->m_cust->count_all_num_rowsVCAT($cinta);
		echo $recordcountVCAT;
	}
}