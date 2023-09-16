<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 Agustus 2022
 * File Name	= C_itp4r.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_itp4r extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_inventory/m_itpar/m_itpar', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		$this->data['nSELP']		= $this->session->userdata['nSELP'];
	}

 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_inventory/c_itp4r/get_itemparent/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}

	function get_itemparent()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName			= $_GET['id'];
			$appName			= $this->url_encryption_helper->decode_url($idAppName);
			$EmpID 				= $this->session->userdata('Emp_ID');
			
			// GET MENU DESC
				$mnCode				= 'MN194';
				$data["MenuApp"] 	= 'MN194';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
	 		$data["MenuCode"] 	= 'MN194';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN194';
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

			$data['addURL'] 	= site_url('c_inventory/c_itp4r/add/?id=');
			$data['backURL'] 	= site_url('c_inventory/c_itp4r/?id=');
			
			$this->load->view('v_inventory/v_itempar/v_itempar', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataIP() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

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
			
			$columns_valid 	= array("", 
									"PITM_CODE", 
									"",
									"",
									"PITM_NAME");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_itpar->get_AllDataIPC($search);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_itpar->get_AllDataIPL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$PITM_CODE 		= $dataI['PITM_CODE'];
				$PITM_PARENT 	= $dataI['PITM_PARENT'];
				$PITM_GROUP		= $dataI['PITM_GROUP'];
				$PITM_LEVEL		= $dataI['PITM_LEVEL'];
				$PITM_NAME		= $dataI['PITM_NAME'];

				$secDel 		= base_url().'index.php/c_inventory/c_itp4r/delIP/?id=';
				$delIParID 		= "$secDel~$PITM_CODE";

				$IC_Name 		= "-";
				$s_ICAT 		= "SELECT IC_Name FROM tbl_itemcategory WHERE IC_Code = '$PITM_GROUP'";
	            $r_ICAT 		= $this->db->query($s_ICAT)->result();
	            foreach($r_ICAT as $rw_ICAT) :
	                $IC_Name  	= $rw_ICAT->IC_Name;
	            endforeach;

				// SPACE
					if($PITM_LEVEL == 1)
						$spaceLev 	= 0;
					elseif($PITM_LEVEL == 2)
						$spaceLev 	= 15;
					elseif($PITM_LEVEL == 3)
						$spaceLev 	= 30;
					elseif($PITM_LEVEL == 4)
						$spaceLev 	= 45;
					elseif($PITM_LEVEL == 5)
						$spaceLev 	= 60;
					elseif($PITM_LEVEL == 6)
						$spaceLev 	= 75;
					elseif($PITM_LEVEL == 7)
						$spaceLev 	= 90;
					elseif($PITM_LEVEL == 8)
						$spaceLev 	= 105;
					elseif($PITM_LEVEL == 9)
						$spaceLev 	= 120;
					elseif($PITM_LEVEL == 10)
						$spaceLev 	= 135;
					elseif($PITM_LEVEL == 11)
						$spaceLev 	= 150;
					elseif($PITM_LEVEL == 12)
						$spaceLev 	= 165;

				$IParView1		= "$PITM_NAME";
				$IParView 		= wordwrap($IParView1, 80, "<br>", true);
				$secDel 		= base_url().'index.php/__l1y/trashIP/?id='.$PITM_CODE;

				$secUpd			= site_url('c_inventory/c_itp4r/update/?id='.$this->url_encryption_helper->encode_url($PITM_CODE));
				$secAction		= 	"<input type='hidden' name='urlDel".$noU."' id='urlDel".$noU."' value='".$secDel."'>
							   		<label style='white-space:nowrap'>
										<a href='".$secUpd."' class='btn btn-info btn-xs' title='Update'>
											<i class='glyphicon glyphicon-pencil'></i>
										</a>
										<a href='javascript:void(null);' class='btn btn-danger btn-xs' onClick='delDOC(".$noU.")' title='Delete'>
											<i class='fa fa-trash-o'></i>
										</a>
									</label>";

				$output['data'][] 	= array($noU,
											$PITM_CODE,
											$PITM_PARENT,
											"$IC_Name ($PITM_GROUP)",
											$IParView,
											$secAction);

				$noU			= $noU + 1;
			}

			/*$output['data'][] 	= array("A",
										"B",
										"C",
										"D",
										"E");*/
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
	
	function add()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			// GET MENU DESC
				$mnCode				= 'MN194';
				$data["MenuApp"] 	= 'MN194';
				$getMN				= $this->m_updash->get_menunm($mnCode)->row();
				if($this->data['LangID'] == 'IND')
					$data["mnName"] = $getMN->menu_name_IND;
				else
					$data["mnName"] = $getMN->menu_name_ENG;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_inventory/c_itp4r/add_process');
			$data['backURL'] 		= site_url('c_inventory/c_itp4r/');			
			$data["MenuCode"] 		= 'MN194';
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN194';
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
			
			$this->load->view('v_inventory/v_itempar/v_itempar_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getNCODE()
	{
		$PITM_PARENT= $_POST['PITM_PARENT'];
		
		$s_PARC		= "tbl_item_parent WHERE PITM_PARENT = '$PITM_PARENT'";
		$r_PARC		= $this->db->count_all($s_PARC);

		// START : CARI JIKA SUDAH ADA TURUNAN SEBELUMNYA, UNTUK MENYESUAIKAN TOTAL
			$lenPATT	= 2;
			if($r_PARC > 0)
			{
	    		$s_PAR 	= "SELECT PITM_CODE, PITM_PARENT FROM tbl_item_parent WHERE PITM_PARENT = '$PITM_PARENT' LIMIT 1";
				$r_PAR	= $this->db->query($s_PAR)->result();
				foreach($r_PAR as $rw_PAR):
					$PITM_CODE		= $rw_PAR->PITM_CODE;
					$PITM_PARENT	= $rw_PAR->PITM_PARENT;
					$lenCHILD 		= strlen($PITM_CODE);
					$lenPAR 		= strlen($PITM_PARENT);
					$lenPATT 		= $lenCHILD-$lenPAR;
				endforeach;
			}
		// END : CARI JIKA SUDAH ADA TURUNAN SEBELUMNYA, UNTUK MENYESUAIKAN TOTAL
			
		$RUNNO				= $r_PARC+1;
		$len 				= strlen($RUNNO);

		$Pattern_Length		= $lenPATT;
		$nol				= '';	
		if($Pattern_Length==2)
		{
			if($len==1) $nol="0";else $nol="";
		}
		elseif($Pattern_Length==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";else $nol="";
		}
		elseif($Pattern_Length==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";else $nol="";
		}
		elseif($Pattern_Length==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else $nol="";
		}
		elseif($Pattern_Length==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";else $nol="";
		}
		elseif($Pattern_Length==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";else $nol="";
		}

		$N_RUNNO 		= $nol.$RUNNO;

		$NEWCODE		= "$PITM_PARENT$N_RUNNO";
		echo json_encode($NEWCODE);
	}
	
	function add_process()
	{ 
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PITM_PARENT 	= $this->input->post('PITM_PARENT');
			$PITM_CODE 		= $this->input->post('PITM_CODE');
			$PITM_NAME 		= $this->input->post('PITM_NAME');

    		$s_PAR 	= "SELECT PITM_ORD, PITM_GROUP, PITM_LEVEL, PITM_PATT FROM tbl_item_parent WHERE PITM_PARENT = '$PITM_PARENT' ORDER BY PITM_ORD DESC LIMIT 1 ";
			$r_PAR	= $this->db->query($s_PAR)->result();
			foreach($r_PAR as $rw_PAR):
				$BEF_ORD	= $rw_PAR->PITM_ORD;
				$BEF_GROUP	= $rw_PAR->PITM_GROUP;
				$BEF_LEVEL	= $rw_PAR->PITM_LEVEL;
				$BEF_PATT	= $rw_PAR->PITM_PATT;

				$s_PARC		= "tbl_item_parent WHERE PITM_PARENT = '$PITM_PARENT'";
				$r_PARC		= $this->db->count_all($s_PARC);

				$CHL_ORD 	= $BEF_ORD+1;
				$CHL_PATT 	= $BEF_PATT+1;

				$s_updORD 	= "UPDATE tbl_item_parent SET PITM_ORD = PITM_ORD+1 WHERE PITM_ORD > $BEF_ORD";
				$this->db->query($s_updORD);

				$iparcomp 	= array('PITM_CODE' 	=> $PITM_CODE,
									'PITM_PARENT'	=> $PITM_PARENT,
									'PITM_ORD'		=> $CHL_ORD,
									'PITM_GROUP'	=> $BEF_GROUP,
									'PITM_LEVEL'	=> $BEF_LEVEL,
									'PITM_NAME'		=> $PITM_NAME,
									'PITM_PATT'		=> $CHL_PATT);
				$this->m_itpar->add($iparcomp);
			endforeach;

			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('IC_Num');
				$MenuCode 		= 'MN194';
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
			
			redirect('c_inventory/c_itp4r/');
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update()
	{
		$this->load->model('m_inventory/m_itpar/m_itpar', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PITM_CODE	= $_GET['id'];
			$PITM_CODE	= $this->url_encryption_helper->decode_url($PITM_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_inventory/c_itp4r/update_process');
			$data['backURL'] 		= site_url('c_inventory/c_itp4r/');
			$data["MenuCode"] 		= 'MN194';
			
			$getitemcat = $this->m_itpar->get_itempar_by_code($PITM_CODE)->row();
			
			$data['default']['IC_Num'] 	= $getitemcat->IC_Num;
			$data['default']['IG_Code'] = $getitemcat->IG_Code;
			$data['default']['PITM_CODE'] = $getitemcat->PITM_CODE;
			$data['default']['IC_Name'] = $getitemcat->IC_Name;
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $getitemcat->IC_Num;
				$MenuCode 		= 'MN194';
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
			
			$this->load->view('v_inventory/v_itempar/v_itempar_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process()
	{ 
		$this->load->model('m_inventory/m_itpar/m_itpar', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$itemcat = array('IC_Num' 	=> $this->input->post('IC_Num'),
							'IG_Code'	=> $this->input->post('IG_Code'),
							'PITM_CODE'	=> $this->input->post('PITM_CODE'),
							'IC_Name'	=> $this->input->post('IC_Name'));
		
			$this->m_itpar->update($this->input->post('IC_Num'), $iparcomp);
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= $this->input->post('IC_Num');
				$MenuCode 		= 'MN194';
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
			
			redirect('c_inventory/c_itp4r/');
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($IC_Num) // OK
	{ 	
		$sqlApp 	= "tbl_itemcategory WHERE IC_Num = '$IC_Num'";
		$countCode	= $this->db->count_all($sqlApp);
		echo $countCode;
	}
}