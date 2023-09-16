<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2018
 * File Name	= C_t180c2hr
 * Function		= -
*/

class C_t180c2hr extends CI_Controller
{
	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_help/m_task_request', '', TRUE);
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
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
	}
	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/c_t180c2hr/ts180c2hdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ts180c2hdx()
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			
			// GET MENU DESC
				$mnCode				= 'MN300';
				$data["MenuApp"] 	= 'MN300';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Permintaan';
				$data['h2_title']	= 'Bantuan';
				$data['h3_title']	= 'Database';
			}
			else
			{
				$data['h1_title']	= 'Request';
				$data['h2_title']	= 'Assistance';
				$data['h3_title']	= 'Database';
			}
			
			$data['secAddURL'] 	= site_url('c_help/c_t180c2hr/a180c2hdd/?id='.$this->url_encryption_helper->encode_url($appName));
			
			//$data["countTask"] 	= $this->m_task_request->count_all_task($DefEmp_ID);	 
			//$data['vwTask'] 	= $this->m_task_request->view_all_task($DefEmp_ID)->result();
			
			$this->load->view('v_help/v_task_request/v_task_request', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}

  	function get_AllData() // GOOD
	{
		$EMP_ID		= $_GET['id'];
		$TREQ		= $_GET['TREQ'];
		$TSTAT		= $_GET['TSTAT'];
		$TRSTAT 	= $_GET['TRSTAT'];

		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
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
			
			$columns_valid 	= array("A.TASK_CODE",
									"A.TASK_MENUNM", 
									"A.TASKD_TITLE", 
									"A.TASKD_TITLE", 
									"A.TASKD_TITLE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_task_request->get_AllDataC($EMP_ID, $TREQ, $TSTAT, $TRSTAT, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_task_request->get_AllDataL($EMP_ID, $TREQ, $TSTAT, $TRSTAT, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$TASK_CODE		= $dataI['TASK_CODE'];
				$TASK_MENU		= $dataI['TASK_MENU'];
				$TASK_DATE		= $dataI['TASK_DATE'];
				$TASK_DATEV 	= strftime('%d %B %Y', strtotime($TASK_DATE));
				$TASK_TITLE		= $dataI['TASK_TITLE'];
				$TASK_CATEG		= $dataI['TASK_CATEG'];
				if($TASK_CATEG == 1)
				{
					$TASK_CATEGC= "primary";
					$TASK_CATEGD= "Pertanyaan";
				}
				elseif($TASK_CATEG == 2)
				{
					$TASK_CATEGC= "warning";
					$TASK_CATEGD= "Misinformasi";
				}
				elseif($TASK_CATEG == 3)
				{
					$TASK_CATEGC= "danger";
					$TASK_CATEGD= "Bug/Error";
				}
				elseif($TASK_CATEG == 4)
				{
					$TASK_CATEGC= "info";
					$TASK_CATEGD= "Otorisasi";
				}
				elseif($TASK_CATEG == 5)
				{
					$TASK_CATEGC= "warning";
					$TASK_CATEGD= "Perubahan";
				}
				elseif($TASK_CATEG == 6)
				{
					$TASK_CATEGC= "success";
					$TASK_CATEGD= "Penambahan";
				}
				else
				{
					$TASK_CATEGC= "default";
					$TASK_CATEGD= "Misinformasi";
				}

				$TASK_MENUNM	= $dataI['TASK_MENUNM'];
				if($TASK_MENUNM == '')
					$TASK_MENUNM= 'none';
				$TASK_AUTHOR	= $dataI['TASK_AUTHOR'];
				$TASK_REQUESTER	= $dataI['TASK_REQUESTER'];
				$TASK_STAT		= $dataI['TASK_STAT'];
				$REQUESTER_NAME	= $dataI['REQ_NAME'];

				$imgempfNmX 	= "username.jpg";
				$sqlIMGCrt		= "SELECT imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$TASK_REQUESTER' LIMIT 1";
				$resIMGCrt 		= $this->db->query($sqlIMGCrt)->result();
				foreach($resIMGCrt as $rowGIMGCrt) :
					$imgempfNmX = $rowGIMGCrt->imgemp_filenameX;
				endforeach;
				
				$imgMng			= base_url('assets/AdminLTE-2.0.5/emp_image/'.$TASK_REQUESTER.'/'.$imgempfNmX);
				if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$TASK_REQUESTER))
				{
					$imgMng		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
				}
											
				$TASKD_CONTENT	= '';
				/*$sqlC		= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND (TASKD_EMPID2 = '$TASK_AUTHOR' OR TASKD_EMPID2 = '$TASK_REQUESTER')
									 AND TASKD_RSTAT = '1'";*/
				$sqlC		= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID2 = '$DefEmp_ID' AND TASKD_RSTAT = '1'";
				$resC		= $this->db->count_all($sqlC); 		// NOT YET READ BY AUTHOR

				$sqlC1		= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID2 != '$DefEmp_ID' AND TASKD_RSTAT = '1'";
				$resC1		= $this->db->count_all($sqlC1); 	// NOT YET READ BY REQUESTER
				
				$sqlV		= "SELECT TASKD_CONTENT FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' ORDER BY TASKD_CREATED ASC LIMIT 1";
				$vwTaskD	= $this->db->query($sqlV)->result();
				foreach($vwTaskD as $rowD) :
					$TASKD_CONTENT	= $rowD->TASKD_CONTENT;
				endforeach;
				
				$STAT_S 	= "<a class='btn btn-danger btn-xs' title='Unread'><i class='glyphicon glyphicon-eye-close'></i></a>";		// STATUS SENDER
				$STAT_R 	= "<a class='btn btn-danger btn-xs' title='Unread'><i class='glyphicon glyphicon-eye-close'></i></a>";		// STATUS RECEIVER
				if($resC > 0)
				{
					$readStat	= "Unread";
					$readCOL	= 'danger';
					$STAT_S 	= "<a class='btn btn-danger btn-xs' title='Belum anda baca'><i class='glyphicon glyphicon-eye-close'></i></a>";		// STATUS SENDER
				}
				else
				{
					$readStat	= "read";
					$readCOL	= 'success';
					$STAT_S 	= "<a class='btn btn-success btn-xs' title='Sudah anda baca'><i class='glyphicon glyphicon-eye-open'></i></a>";		// STATUS SENDER
				}

				if($resC1 > 0)
				{
					$readStat	= "Unread";
					$readCOL	= 'danger';
					$STAT_R 	= "<a class='btn btn-danger btn-xs' title='Belum dibaca penerima'><i class='glyphicon glyphicon-eye-close'></i></a>";		// STATUS SENDER
				}
				else
				{
					$readStat	= "read";
					$readCOL	= 'success';
					$STAT_R 	= "<a class='btn btn-success btn-xs' title='Sudah dibaca penerima'><i class='glyphicon glyphicon-eye-open'></i></a>";		// STATUS SENDER
				}
				
				if($TASK_STAT == 1)
				{
					$isActDesc	= 'New';
					$STATCOL	= 'warning';
				}
				elseif($TASK_STAT == 2)
				{
					$isActDesc	= 'Process';
					$STATCOL	= 'primary';
				}
				else
				{
					$isActDesc	= 'Closed';
					$STATCOL	= 'success';
				}
				
				$secUpd		= site_url('c_help/c_t180c2hr/t180c2htread/?id='.$this->url_encryption_helper->encode_url($TASK_CODE));
				$secPrint	= site_url('c_help/c_t180c2hr/print_tr/?id='.$this->url_encryption_helper->encode_url($TASK_CODE));

				$secAction	= 	"<input type='hidden' name='urlPrint".$noU."' id='urlPrint".$noU."' value='".$secPrint."'>
							   	<label style='white-space:nowrap'>
							   	<a href='".$secUpd."' class='btn btn-info btn-xs' title='Buka'>
									<i class='glyphicon glyphicon-check'></i>
							   	</a>
								</label>";

				$output['data'][] 	= array("<strong>".$TASK_CODE."</strong><br>
											<strong><i class='fa fa-calendar margin-r-5'></i> Tanggal</strong>
											<div style='margin-left: 15px'>
										  		<p class='text-muted'>
										  			".$TASK_DATEV."
										  		</p>
										  	</div>",
										  	"<strong style='white-space:nowrap'><i class='fa fa-bell margin-r-5'></i> Modul / Menu </strong>
									  		<div style='margin-left: 20px'>
										  		<p class='text-muted'>
										  			".$TASK_MENUNM."
										  		</p>
										  	</div>
										  	<strong style='white-space:nowrap'><i class='fa fa-question-circle margin-r-5'></i>
										  		<span class='label label-".$TASK_CATEGC."' style='font-size:12px'>
													".$TASK_CATEGD."
					                            </span>
										  	</strong>",
										  	"<strong>".$TASK_TITLE."</strong>
										  	<div>
										  		<p class='text-muted'>
										  			".$TASKD_CONTENT."
										  		</p>
										  		<div style='margin-left: 20px'>
											  		<div class='box-comments' style='background-color: transparent;'>
												  		<div class='box-comment'>
											                <img class='img-circle img-sm' src='".$imgMng."' alt='User Image'>
											                <div class='comment-text'>
											                   	<span class='username'>
											                        ".ucwords($REQUESTER_NAME)."
											                    </span>
										                  		".ucwords($TASK_REQUESTER)."
											                </div>
											            </div>
										            </div>
											  	</div>
										  	</div>",
										  	$STAT_S." ".$STAT_R,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>
												".$isActDesc."
				                            </span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
									  	"A",
									  	"A",
									  	"A",
									  	"A",
									  	"A");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function a180c2hdd() // OK
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Buat Permintaan';
				$data['h2_title']	= 'Bantuan';
			}
			else
			{
				$data['h1_title']	= 'Add Request';
				$data['h2_title']	= 'Assistance';
			}
			
			// GET MENU DESC
				$mnCode				= 'MN300';
				$data["MenuApp"] 	= 'MN300';
				//$data['PRJCODE_HO']	= $this->data['PRJCODE_HO'];
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['MenuParentC'] 	= $this->m_task_request->getCount_menu();		
			$data['MenuParent'] 	= $this->m_task_request->get_menu()->result();
			
			$data['form_action']	= site_url('c_help/c_t180c2hr/add_process');
			$data['link'] 			= array('link_back' => anchor('c_help/c_t180c2hr/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_help/c_t180c2hr/');
			
			$MenuCode 				= 'MN208';
			$data['viewDocPattern'] = $this->m_task_request->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_help/v_task_request/v_task_request_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_help/m_task_request', '', TRUE);
		$comp_init	= $this->session->userdata('comp_init');
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		date_default_timezone_set("Asia/Jakarta");
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$TASK_TITLE = addslashes($this->input->post('TASK_TITLE'));
		$TASK_DATE	= date('Y-m-d',strtotime($this->input->post('TASK_DATE')));
		$Patt_Year	= date('Y',strtotime($this->input->post('TASK_DATE')));
		$Patt_Month	= date('m',strtotime($this->input->post('TASK_DATE')));
		$Patt_Date	= date('d',strtotime($this->input->post('TASK_DATE')));
		
		$TASK_CODE		= $comp_init.".".date('YmdHis');
		$TASK_CATEG		= $this->input->post('TASK_CATEG');
		$TASK_TYPE		= $this->input->post('TASK_TYPE');
		
		if($TASK_TYPE == '')
			$TASK_TYPE = 0;
			
		$TASK_AUTHOR	= $this->input->post('TASK_AUTHOR');
		$selStepA	= 0;
		foreach ($TASK_AUTHOR as $sel_usersA)
		{
			$selStepA	= $selStepA + 1;
			if($selStepA == 1)
			{
				$user_toA		= explode ("|",$sel_usersA);
				$user_IDA		= $user_toA[0];
				//$user_ADDA		= $user_toA[1];
				$TASKD_EMPID2A	= $user_IDA;
			}
			else
			{					
				$user_toA		= explode ("|",$sel_usersA);
				$user_IDA		= $user_toA[0];
				//$user_ADDA		= $user_toA[1];
				
				$TASKD_EMPID2A	= "$TASKD_EMPID2A;$user_IDA";
			}
		}
		$TASK_AUTHOR			= $TASKD_EMPID2A;
					
		$TASK_REQUESTER	= $this->input->post('TASK_REQUESTER');
		$TASK_FOR		= $this->input->post('TASK_FOR');
		
		if($TASK_TYPE > 0) // this is concultant information for users
		{
			$TASK_AUTHOR	= $DefEmp_ID;
		}

		$NOTIFSTAT		= 1;
		$s_00			= "SELECT NOTIFSTAT from tglobalsetting";
		$r_00			= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
			$NOTIFSTAT 	= $rw_00->NOTIFSTAT;
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$file 				= $_FILES['userfile'];
			$file_name 			= $file['name'];
			
			// $fileName1	= str_replace(" ","_", $file_name);
			// $fileName	= str_replace(" ","_", $fileName1);
			
			$TASK_MENU	= $this->input->post('TASK_MENU');
			$MENU_NAME	= 'none';
			$sqlMN 		= "SELECT menu_name_IND FROM tbl_menu WHERE menu_code = '$TASK_MENU'";
			$resMN 		= $this->db->query($sqlMN)->result();
			foreach($resMN as $rowMN) :
				$MENU_NAME = $rowMN->menu_name_IND;		
			endforeach;

			// CEK LAST Patt_Number
				$Patt_Year	= date('Y');
				$LASTNO 	= 0;
				$s_LNO 		= "SELECT max(Patt_Number) AS LNO FROM tbl_task_request WHERE Patt_Year = $Patt_Year";
				$r_LNO 		= $this->db->query($s_LNO)->result();
				foreach($r_LNO as $rw_LNO) :
					$LASTNO = $rw_LNO->LNO;
				endforeach;
				$MAXNO 		= $LASTNO+1;

				$len 		= strlen($MAXNO);
				$nol 		= '';
				$Pattern_Length	= 5;
				if($Pattern_Length==2)
				{
					if($len==1) $nol="0";
				}
				elseif($Pattern_Length==3)
				{if($len==1) $nol="00";else if($len==2) $nol="0";
				}
				elseif($Pattern_Length==4)
				{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
				}
				elseif($Pattern_Length==5)
				{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
				}
				elseif($Pattern_Length==6)
				{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
				}
				elseif($Pattern_Length==7)
				{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
				}
				$LPattNo 	= $nol.$MAXNO;
				
				$DOCNKE     = strtoupper($comp_init);
				$DOCTIME 	= date('ymd');
			    $TASK_CODE 	= "$DOCNKE.TR-$DOCTIME.$LPattNo";

			
			// CREATE HEADER
			$InsTR 		= array('TASK_CODE' 	=> $TASK_CODE,
								'TASK_DATE'		=> $TASK_DATE,
								'TASK_TITLE'	=> addslashes($this->input->post('TASK_TITLE')),
								'TASK_MENU'		=> $this->input->post('TASK_MENU'),
								'TASK_NOREF'	=> $this->input->post('TASK_NOREF'),
								'TASK_CATEG'	=> $TASK_CATEG,
								'TASK_MENUNM'	=> $MENU_NAME,
								'TASK_TYPE'		=> $this->input->post('TASK_TYPE'),
								'TASK_AUTHOR'	=> $TASK_AUTHOR,
								'TASK_REQUESTER'=> $this->input->post('TASK_REQUESTER'),
								'TASK_STAT'		=> $this->input->post('TASK_STAT'),
								'PRJCODE'		=> $this->input->post('PRJCODE'),
								'TASK_CREATED'	=> date('Y-m-d H:i:s'),
								'Patt_Year'		=> $Patt_Year,
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $MAXNO);												
			$this->m_task_request->add($InsTR);
			
			if($TASK_TYPE == 0)	// From user to author
			{
				// CREATE DETAIL
				// Karena $TASK_AUTHOR = "All", maka cari salah  satu author dari detail
				
				// ------------------ START : SEMENTARA DITUTUP AGAR TIDAK AUTO KE HELPER, AGAR BISA KE SEMUA TIM USER				
					/*$getC1	= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER'";
					$resC1	= $this->db->count_all($getC1);
					if($resC1 > 0)
					{
						$getID1		= "SELECT TASKD_EMPID
										FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER' LIMIT 1";
						$resID1		= $this->db->query($getID1)->result();
						foreach($resID1 as $rowID1) :
							$TASKD_EMPID2 	= $rowID1->TASKD_EMPID;
						endforeach;
					}
					else
					{
						$myrow		= 0;
						$getAuthID	= "SELECT Emp_ID FROM tbl_employee WHERE isHelper = 1";
						$resAuthID	= $this->db->query($getAuthID)->result();
						foreach($resAuthID as $rowAuthID) :
							$myrow	= $myrow + 1;
							$Emp_ID 	= $rowAuthID->Emp_ID;
							if($myrow == 1)
							{
								$TASKD_EMPID2	= "$Emp_ID";
							}
							if($myrow > 1)
							{
								$TASKD_EMPID2	= "$TASKD_EMPID2;$Emp_ID";
							}
						endforeach;
					}*/
				// ------------------ END : SEMENTARA DITUTUP AGAR TIDAK AUTO KE HELPER, AGAR BISA KE SEMUA TIM USER
				
				$TASKD_EMPID2	= $TASK_AUTHOR;
				/*$InsTRD		= array('TASKD_PARENT' 		=> $this->input->post('TASK_CODE'),
									'TASKD_TITLE'		=> $this->input->post('TASK_TITLE'),
									'TASKD_CONTENT'		=> $this->input->post('TASK_CONTENT'),
									'TASKD_FILENAME'	=> $fileName,
									'TASKD_DATE'		=> date('Y-m-d'),
									'TASKD_CREATED'		=> date('Y-m-d H:i:s'),
									'TASKD_EMPID'		=> $DefEmp_ID,
									'TASKD_EMPID2'		=> $TASKD_EMPID2,
									'TASKD_EMPID'		=> $DefEmp_ID);													
				$this->m_task_request->addDet($InsTRD);*/
			}
			elseif($TASK_TYPE == 1)	// From user to author
			{
				if($TASK_TYPE == 1)
				{
					$TASKD_EMPID2	= "All";
				}
			}
			elseif($TASK_TYPE == 2)
			{
				// FOR GROUPING RECEIVING BY PERSONAL
				$selStep	= 0;
				foreach ($TASK_FOR as $sel_users)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to		= explode ("|",$sel_users);
						$user_ID		= $user_to[0];
						$user_ADD		= $user_to[1];
						$TASKD_EMPID2	= $user_ID;
						//$coll_MADD	= $user_ADD;
						//echo "1. TASKD_EMPID2 = $TASKD_EMPID2<br>";
					}
					else
					{					
						$user_to		= explode ("|",$sel_users);
						$user_ID		= $user_to[0];
						$user_ADD		= $user_to[1];
						
						$TASKD_EMPID2	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
						//echo "2. TASKD_EMPID2 = $TASKD_EMPID2<br>";
					}
				}
				//$TASKD_EMPID2 = $TASK_AUTHOR;
			}

			$fileName 	= "";
			if($file_name != '')
			{
				$info 	= $_FILES['userfile']['name'];
				$ext 	= pathinfo($info, PATHINFO_EXTENSION);
				
				if($ext == 'rar' || $ext == 'zip')
				{
					$filename 	= $_FILES['userfile']['name'];
					$source 	= $_FILES['userfile']['tmp_name'];
					$type 		= $_FILES['userfile']['type'];
					
					$name 		= explode('.', $filename);
					$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
					foreach($accepted_types as $mime_type) 
					{
						if($mime_type == $type) 
						{
							$okay = true;
							break;
						} 
					}
					
					$continue = strtolower($name[1]) == 'zip' ? true : false;
					if(!$continue)
					{
						$message = "The file you are trying to upload is not a .zip file. Please try again.";
					}
					
					$target_path = "assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/".$filename;  // change this to the correct site path
					if(move_uploaded_file($source, $target_path))
					{
						$zip = new ZipArchive();
						$x = $zip->open($target_path);
						if ($x === true) 
						{
							$zip->extractTo("assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/");
							$zip->close();
					
							unlink($target_path);
						}
						//$message 	= "Your .zip file was uploaded and unpacked.";
						$success	= 1;
					} 
					else 
					{	
						$message 	= "There was a problem with the upload. Please try again.";
						$success	= 0;
					}
				}
				else
				{
					$file 						= $_FILES['userfile'];
					$file_name 					= $file['name'];
					$config['upload_path']   	= "assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/"; 
					$config['allowed_types']	= 'pdf|doc|docx|xlsx|xls|gif|jpg|jpeg|png'; 
					$config['overwrite'] 		= TRUE;
					//$config['max_size']     	= 1000000; 
					//$config['max_width']    	= 10024; 
					//$config['max_height']    	= 10000;  
					$config['file_name']       	= $file['name'];
			
					$this->load->library('upload', $config);
					
					$this->upload->do_upload('userfile');

					// $datafile 	= $this->upload->data();
					$upl_data 	= $this->upload->data();
					$fileName 	= $upl_data['file_name'];
					$srvURL 	= $_SERVER['SERVER_ADDR'];

					// $fileName 	= $datafile['file_name'];
					$source		= "assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/$fileName";

					if($srvURL == '10.0.0.144')
					{
						$this->load->library('ftp');
		
						$config['hostname'] = 'sdbpplus.nke.co.id';
						$config['username'] = 'sdbpplus@sdbpplus.nke.co.id';
						$config['password'] = 'NKE@dmin2021';
						$config['debug']    = TRUE;
						
						$this->ftp->connect($config);
						
						$destination = "/assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/$fileName";
						
						$this->ftp->upload($source, ".".$destination);
						
						$this->ftp->close();
					}
				}
			}
			
			$InsTRD		= array('TASKD_PARENT' 		=> $TASK_CODE,
								'TASKD_TITLE'		=> addslashes($this->input->post('TASK_TITLE')),
								'TASKD_CONTENT'		=> addslashes($this->input->post('TASK_CONTENT')),
								'TASKD_FILENAME'	=> $fileName,
								'TASKD_DATE'		=> date('Y-m-d'),
								'TASKD_CREATED'		=> date('Y-m-d H:i:s'),
								'TASKD_EMPID'		=> $DefEmp_ID,
								'TASKD_EMPID2'		=> $TASKD_EMPID2,
								'TASKD_EMPID'		=> $DefEmp_ID);
			$this->m_task_request->addDet($InsTRD);
			
			// START : ALERT WA PROCEDURE
				$TASKD_PARENT 	= $TASK_CODE;
				$TASK_TITLE 	= $this->input->post('TASK_TITLE');
				$TASKD_CONTENTA	= $this->input->post('TASK_CONTENT');
				$TASKD_CONTENT 	= strip_tags($TASKD_CONTENTA);
				
				if($TASKD_CONTENT > 50)
					$TASK_CONTA	= substr($TASKD_CONTENT,0,50);
				else
					$TASK_CONTA	= $TASKD_CONTENT;

				$TASK_CONT 	= $TASK_CONTA;

				$AS_EMPNAME	= "";
				$AS_MPHONE 	= "";
				$s_EMP		= "SELECT CONCAT(TRIM(First_Name),IF(Last_Name = '','',' '),TRIM(Last_Name)) AS COMPLNAME, Middle_Name,
									REPLACE(Mobile_Phone,' ','') AS AS_MPHONE
								FROM tbl_employee WHERE Emp_ID = '$TASKD_EMPID2'";
				$r_EMP 		= $this->db->query($s_EMP)->result();
				foreach($r_EMP as $rw_EMP) :
					$AS_EMPNAME	= $rw_EMP->COMPLNAME;
					$AS_MPHONE 	= $rw_EMP->AS_MPHONE;
				endforeach;

				$AS_SENDER 	= "";
				$s_SEND		= "SELECT CONCAT(TRIM(First_Name),IF(Last_Name = '','',' '),TRIM(Last_Name)) AS COMPLNAME, Middle_Name,
									REPLACE(Mobile_Phone,' ','') AS AS_MPHONE
								FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
				$r_SEND 	= $this->db->query($s_SEND)->result();
				foreach($r_SEND as $rw_SEND) :
					$AS_SENDER	= $rw_SEND->COMPLNAME;
				endforeach;

				if($TASKD_EMPID2 == 'D15040004221')
				{
					$AS_MPHONE1 = "6285722980308";
					$AS_MPHONE2 = "6282116474710";
					if($NOTIFSTAT == 1)
					{
						/* ------------------------------- pickyassist.com -----------------------------------
						$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE1.'","message":"Bapak/Ibu *_'.$AS_EMPNAME.'_*, Anda mendapatkan pesan dari *'.$AS_SENDER.'* Task Request No. '.$TASKD_PARENT.' : _'.$TASK_TITLE.'_ \n Isi Pesan : _*'.$TASK_CONT.'*_ \n\n Terimakasih. \n *_NKE Smart System_*"}]}';


					    //--CURL FUNCTION TO CALL THE API--
					    $url = 'https://pickyassist.com/app/api/v2/push';

					    $ch = curl_init($url);                                                                      
					    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
					    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					        'Content-Type: application/json',                                                                                
					        'Content-Length: ' . strlen($JSON_DATA))                                                                       
					    );                                                                                                                   
					                                                                                                                            
					    $result = curl_exec($ch);

					    //--API RESPONSE--
					    //print_r( json_decode($result,true) );

						$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE2.'","message":"Bapak/Ibu *_'.$AS_EMPNAME.'_*, Anda mendapatkan respon dari *'.$AS_SENDER.'* Task Request No. '.$TASKD_PARENT.' : _'.$TASK_TITLE.'_ \n Isi Pesan : _*'.$TASK_CONT.'*_ \n\n Terimakasih. \n *_NKE Smart System_*"}]}';


					    //--CURL FUNCTION TO CALL THE API--
					    $url = 'https://pickyassist.com/app/api/v2/push';

					    $ch = curl_init($url);                                                                      
					    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
					    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					        'Content-Type: application/json',                                                                                
					        'Content-Length: ' . strlen($JSON_DATA))                                                                       
					    );                                                                                                                   
					                                                                                                                            
					    $result = curl_exec($ch);

					    //--API RESPONSE--
					    //print_r( json_decode($result,true) );
						--------------------------------------- pickyassist.com -------------------------- */

						/* ------------------------------ Maxhat.id -------------------------------------- */
							$url 		= "https://demo.maxchat.id/demo4/api//messages/push";
							$token 		= "CY94HXh7bYDTkcuMPWxUNo";

							$JSON_DATA	= array("to" => $AS_MPHONE1, "text" => "Bapak/Ibu *_".$AS_EMPNAME."_*, Anda mendapatkan pesan dari *$AS_SENDER* Task Request No. $TASKD_PARENT : _".$TASK_TITLE."_ \n Isi Pesan : _*$TASK_CONT*_ \n\n Terimakasih. \n *_NKE Smart System_*");
							$curl 		= curl_init();

							curl_setopt_array($curl, array(
								CURLOPT_URL => $url,
								CURLOPT_SSL_VERIFYHOST => false,
								CURLOPT_SSL_VERIFYPEER => false,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_ENCODING => "",
								CURLOPT_MAXREDIRS => 10,
								CURLOPT_TIMEOUT => 30,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => "POST",
								CURLOPT_POSTFIELDS => json_encode($JSON_DATA),
								CURLOPT_HTTPHEADER => array(
									"Authorization: Bearer " . $token,
									"Content-Type: application/json",
									"cache-control: no-cache"
								),
							));

							$response = curl_exec($curl);
							$err = curl_error($curl);

							curl_close($curl);

							if ($err) {
								echo "cURL Error #:" . $err;
							} else {
								echo $response;
							}

							$JSON_DATA	= array("to" => $AS_MPHONE2, "text" => "Bapak/Ibu *_".$AS_EMPNAME."_*, Anda mendapatkan pesan dari *$AS_SENDER* Task Request No. $TASKD_PARENT : _".$TASK_TITLE."_ \n Isi Pesan : _*$TASK_CONT*_ \n\n Terimakasih. \n *_NKE Smart System_*");
							$curl 		= curl_init();

							curl_setopt_array($curl, array(
								CURLOPT_URL => $url,
								CURLOPT_SSL_VERIFYHOST => false,
								CURLOPT_SSL_VERIFYPEER => false,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_ENCODING => "",
								CURLOPT_MAXREDIRS => 10,
								CURLOPT_TIMEOUT => 30,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => "POST",
								CURLOPT_POSTFIELDS => json_encode($JSON_DATA),
								CURLOPT_HTTPHEADER => array(
									"Authorization: Bearer " . $token,
									"Content-Type: application/json",
									"cache-control: no-cache"
								),
							));

							$response = curl_exec($curl);
							$err = curl_error($curl);

							curl_close($curl);

							if ($err) {
								echo "cURL Error #:" . $err;
							} else {
								echo $response;
							}
						/*-------------------------------- Maxhat.id ---------------------------------- */
					}
				}
				else
				{
					if($NOTIFSTAT == 1)
					{
						/* ------------------------------- pickyassist.com -----------------------------------
						$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Bapak/Ibu *_'.$AS_EMPNAME.'_*, Anda mendapatkan pesan dari *'.$AS_SENDER.'* Task Request No. '.$TASKD_PARENT.' : _'.$TASK_TITLE.'_ \n Isi Pesan : _*'.$TASK_CONT.'*_ \n\n Terimakasih. \n *_NKE Smart System_*"}]}';


					    //--CURL FUNCTION TO CALL THE API--
					    $url = 'https://pickyassist.com/app/api/v2/push';

					    $ch = curl_init($url);                                                                      
					    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
					    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					        'Content-Type: application/json',                                                                                
					        'Content-Length: ' . strlen($JSON_DATA))                                                                       
					    );                                                                                                                   
					                                                                                                                            
					    $result = curl_exec($ch);

					    //--API RESPONSE--
					    //print_r( json_decode($result,true) );
						--------------------------------------- pickyassist.com -------------------------- */

						/*-------------------------------- Maxhat.id ---------------------------------- */
							$url 		= "https://demo.maxchat.id/demo4/api//messages/push";
							$token 		= "CY94HXh7bYDTkcuMPWxUNo";

							$JSON_DATA	= array("to" => $AS_MPHONE, "text" => "Bapak/Ibu *_".$AS_EMPNAME."_*, Anda mendapatkan pesan dari *$AS_SENDER* Task Request No. $TASKD_PARENT : _".$TASK_TITLE."_ \n Isi Pesan : _*$TASK_CONT*_ \n\n Terimakasih. \n *_NKE Smart System_*");
							$curl 		= curl_init();

							curl_setopt_array($curl, array(
								CURLOPT_URL => $url,
								CURLOPT_SSL_VERIFYHOST => false,
								CURLOPT_SSL_VERIFYPEER => false,
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_ENCODING => "",
								CURLOPT_MAXREDIRS => 10,
								CURLOPT_TIMEOUT => 30,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => "POST",
								CURLOPT_POSTFIELDS => json_encode($JSON_DATA),
								CURLOPT_HTTPHEADER => array(
									"Authorization: Bearer " . $token,
									"Content-Type: application/json",
									"cache-control: no-cache"
								),
							));

							$response = curl_exec($curl);
							$err = curl_error($curl);

							curl_close($curl);

							if ($err) {
								echo "cURL Error #:" . $err;
							} else {
								echo $response;
							}
						/*-------------------------------- Maxhat.id ---------------------------------- */
					}
				}
			// END : ALERT WA PROCEDURE
			
			// UPDATE NEW
			$UPD_HD_A	= "UPDATE tbl_task_request SET TASK_TO = '$TASKD_EMPID2' WHERE TASK_CODE = '$TASK_CODE'";
			$this->db->query($UPD_HD_A);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_help/c_t180c2hr/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function t180c2htread() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_help/m_task_request', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			
			$TASK_CODE		= $_GET['id'];
			$TASK_CODE		= $this->url_encryption_helper->decode_url($TASK_CODE);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Task View';
			$data['h3_title']	= 'help';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Tinjau Permintaan';
				$data['h2_title']	= 'Bantuan';
			}
			else
			{
				$data['h1_title']	= 'View Request';
				$data['h2_title']	= 'Assistance';
			}
			
			$data['TASK_CODE'] 	= $TASK_CODE;
			$data['link'] 		= array('link_back' => anchor('c_help/c_t180c2hr/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
					
			$this->load->view('v_help/v_task_request/v_task_request_read', $data);
		}
		else
		{
			redirect('__I1y');
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
		// echo $theLink;
		// return false;
		header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);

		ob_clean();
		flush();
		readfile($theLink); //Absolute URL
		exit();

		// force_download($theLink, NULL);
	}
	
	function upd_readstat() // OK
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$TASKD_ID	= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->m_task_request->UpdateOriginal($TASKD_ID);
		}
		else
		{
			redirect('__I1y');
		}
	}

	function print_tr()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 	= $appName;

			$TASK_CODE			= $_GET['id'];
			$TASK_CODE			= $this->url_encryption_helper->decode_url($TASK_CODE);	
			$data['TASK_CODE'] 	= $TASK_CODE;		
			$this->load->view('v_help/v_task_request/v_task_print', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function getDoc() // GOOD
	{
		$collData1	= $_GET['id'];
		$collData	= explode('~', $collData1);
		$PRJCODE	= $collData[0];
		$menu_code	= $collData[1];

		$LangID     	= $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
        endforeach;
		
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
			
			$columns_valid 	= array("",
									"",
									"",
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
        
	        if($menu_code == 'MN468')                   // FPA
	        {
	        	$tblNameH 	= "tbl_pr_header_fpa";
	        	$FldCd 		= "PR_CODE";
	        	$FldDt 		= "PR_DATE";
	        	$FldDesc	= "PR_NOTE";
	        }
	        else if($menu_code == 'MN017')              // SPP
	        {
	        	$tblNameH 	= "tbl_pr_header";
	        	$FldCd 		= "PR_CODE";
	        	$FldDt 		= "PR_DATE";
	        	$FldDesc	= "PR_NOTE";
	        }
	        else if($menu_code == 'MN019')              // OP
	        {
	        	$tblNameH 	= "tbl_po_header";
	        	$FldCd 		= "PO_CODE";
	        	$FldDt 		= "PO_DATE";
	        	$FldDesc	= "PO_NOTES";
	        }
	        else if($menu_code == 'MN067')              // LPM
	        {
	        	$tblNameH 	= "tbl_ir_header";
	        	$FldCd 		= "IR_CODE";
	        	$FldDt 		= "IR_DATE";
	        	$FldDesc	= "IR_NOTE";
	        }
	        else if($menu_code == 'MN189')              // U-MATERIAL
	        {
	        	$tblNameH 	= "tbl_um_header";
	        	$FldCd 		= "UM_CODE";
	        	$FldDt 		= "UM_DATE";
	        	$FldDesc	= "UM_NOTE";
	        }
	        else if($menu_code == 'MN238')              // SPK
	        {
	        	$tblNameH 	= "tbl_wo_header";
	        	$FldCd 		= "WO_CODE";
	        	$FldDt 		= "WO_DATE";
	        	$FldDesc	= "WO_NOTE";
	        }
	        else if($menu_code == 'MN241')              // OPNAME
	        {
	        	$tblNameH 	= "tbl_opn_header";
	        	$FldCd 		= "OPNH_CODE";
	        	$FldDt 		= "OPNH_DATE";
	        	$FldDesc	= "OPNH_NOTE";
	        }
	        else if($menu_code == 'MN338')              // TTK
	        {
	        	$tblNameH 	= "tbl_ttk_header";
	        	$FldCd 		= "TTK_CODE";
	        	$FldDt 		= "TTK_DATE";
	        	$FldDesc	= "TTK_NOTES";
	        }
	        else if($menu_code == 'MN009')              // VOUCHER LPM/OPN
	        {
	        	$tblNameH 	= "tbl_pinv_header";
	        	$FldCd 		= "INV_CODE";
	        	$FldDt 		= "INV_DATE";
	        	$FldDesc	= "INV_NOTES";
	        }
	        else if($menu_code == 'MN045')              // VOUCHER CASH
	        {
	        	$tblNameH 	= "tbl_journalheader_vcash";
	        	$FldCd 		= "Manual_No";
	        	$FldDt 		= "JournalH_Date";
	        	$FldDesc	= "JournalH_Desc";
	        }
	        else if($menu_code == 'MN147')              // VOUCHER LK
	        {
	        	$tblNameH 	= "tbl_journalheader_cprj";
	        	$FldCd 		= "Manual_No";
	        	$FldDt 		= "JournalH_Date";
	        	$FldDesc	= "JournalH_Desc";
	        }
	        else if($menu_code == 'MN359')              // PEMBAYARAN DIMUKA
	        {
	        	$tblNameH 	= "tbl_journalheader_pd";
	        	$FldCd 		= "Manual_No";
	        	$FldDt 		= "JournalH_Date";
	        	$FldDesc	= "JournalH_Desc";
	        }
	        else if($menu_code == 'MN144')              // PEMBAYARAN
	        {
	        	$tblNameH 	= "tbl_bp_header";
	        	$FldCd 		= "CB_CODE";
	        	$FldDt 		= "CB_DATE";
	        	$FldDesc	= "CB_NOTES";
	        }
	        else if($menu_code == 'MN165')              // PINDAH BUKU
	        {
	        	$tblNameH 	= "tbl_journalheader_pb";
	        	$FldCd 		= "Manual_No";
	        	$FldDt 		= "JournalH_Date";
	        	$FldDesc	= "JournalH_Desc";
	        }
	        else if($menu_code == 'MN347')              // UANG MUKA
	        {
	        	$tblNameH 	= "tbl_dp_header";
	        	$FldCd 		= "DP_CODE";
	        	$FldDt 		= "DP_DATE";
	        	$FldDesc	= "DP_NOTES";
	        }
	        else if($menu_code == 'MN406' || $menu_code == 'MN037')              // AMD. ITEM
	        {
	        	$tblNameH 	= "tbl_amd_header";
	        	$FldCd 		= "AMD_CODE";
	        	$FldDt 		= "AMD_DATE";
	        	$FldDesc	= "AMD_NOTES";
	        }
	        else if($menu_code == 'MN225' || $menu_code == 'MN111')              // PROGRESS MINGGUAN
	        {
	        	$tblNameH 	= "tbl_project_progress";
	        	$FldCd 		= "PRJP_NUM";
	        	$FldDt 		= "PRJP_DATE";
	        	$FldDesc	= "PRJP_DESC";
	        }
	        else
	        {
	        	$tblNameH 	= "";
	        	$FldCd 		= "";
	        	$FldDt 		= "";
	        	$FldDesc 	= "";
	        }
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_task_request->get_AllDataDOCC($PRJCODE, $tblNameH, $FldCd, $FldDt, $FldDesc, $search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_task_request->get_AllDataDOCL($PRJCODE, $tblNameH, $FldCd, $FldDt, $FldDesc, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$DOC_CODE		= $dataI['DOC_CODE'];
				$DOC_DATE		= $dataI['DOC_DATE'];
				$DOC_DATEV		= strftime('%d %b %Y', strtotime($DOC_DATE));
				$DOC_DESC		= $dataI['DOC_DESC'];

				$chkBox			= "<input type='radio' name='chk1' value='".$DOC_CODE."' onClick='add_doc(this.value);'/>";

				$output['data'][] 	= array($chkBox,
										  	"<strong>".$DOC_CODE." </strong>
										  	<strong><i class='fa fa-calendar margin-r-5'></i> ".$Date." </strong>
									  		<p class='text-muted' style='margin-left: 20px; white-space:nowrap'>
									  			".$DOC_DATEV."
									  		</p>",
										  	"<strong><i class='fa fa-commenting margin-r-5'></i> ".$Description." </strong>
									  		<div class='text-muted' style='margin-left: 20px'>
										  		$DOC_DESC
										  	</div>");
				$noU		= $noU + 1;
			}

			/*$output['data'][] 	= array("A = $PRJCODE, $tblNameH, $FldCd",
										"B = $menu_code",
										"C",
										"D");*/

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}
?>