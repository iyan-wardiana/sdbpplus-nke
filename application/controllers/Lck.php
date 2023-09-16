<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 20 Februari 2022
	* File Name		= Lck.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lck extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('lck/lckTrx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function lckTrx($offset=0)
	{
		$this->load->model('m_howtouse', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = "Kunci Jurnal / Transaksi";
			else
				$data["mnName"] = "Lock Journal / Transactions";
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Pencarian';
			$data['h3_title']			= 'Help';

			$this->load->view('v_lock/v_lock', $data); 
		}
		else
		{
			redirect('__I1y');
		}
	}

	function get_AllData()
	{
		$LOCKJYEAR	= $_GET['id'];

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
			
			$columns_valid 	= array("LockY",
									"LockM");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}

			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_updash->get_AllDataLockC($LOCKJYEAR, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_updash->get_AllDataLockL($LOCKJYEAR, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$LockY		= $dataI['LockY'];
				$LockM		= $dataI['LockM'];
				$LockCateg	= $dataI['LockCateg'];
				$isLockJ	= $dataI['isLockJ'];
				$LockJDate	= $dataI['LockJDate'];
				$UserJLock	= $dataI['UserJLock'];
				$UserJ 		= $this->db->select('CONCAT(First_Name," ", Last_Name) AS fullName', false)->from('tbl_employee')->where(['Emp_Status' => 1, 'Emp_ID' => $UserJLock])->get()->row('fullName');
				$isLock	= $dataI['isLock'];
				$LockDate	= $dataI['LockDate'];
				$UserLock	= $dataI['UserLock'];
				$UserT 		= $this->db->select('CONCAT(First_Name," ", Last_Name) AS fullName', false)->from('tbl_employee')->where(['Emp_Status' => 1, 'Emp_ID' => $UserLock])->get()->row('fullName');

				$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

				$isLockvw 	= "<i class='fa fa-2x fa-unlock' title='unlock' style='color: green;'></i>";
				if($isLock == 1) $isLockvw 	= "<i class='fa fa-2x fa-lock' title='Lock' style='color: red;'></i>";

				$isLockJvw 	= "<i class='fa fa-2x fa-unlock' title='unlock' style='color: green;'></i>";
				if($isLockJ == 1) $isLockJvw 	= "<i class='fa fa-2x fa-lock' title='Lock' style='color: red;'></i>";

				$LockCategVw = "";
				if($LockCateg == 1) $LockCategVw = "Hanya Dokumen Jurnal";
				elseif($LockCateg == 2) $LockCategVw = "Semua Dokumen Transaksi";

				$output['data'][] 	= [$noU, $isLockvw, $LockCategVw, $isLockJvw, $Month[$LockM-1], $LockDate, $UserT, $LockJDate, $UserJ];
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function lTrx()
	{
		$statApp 	= $_POST['statApp'];
		$LOCKJYEAR	= $this->input->post('LOCKJYEAR');
		$LOCKJMONTH	= $this->input->post('LOCKJMONTH');
		$LOCKCATEG	= $this->input->post('LOCKCATEG');
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$delSQL		= "UPDATE tappname SET app_stat = $statApp";
		$this->db->query($delSQL);

		date_default_timezone_set("Asia/Jakarta");
		$curDate 	= date('Y-m-d H:i:s');
		$addQM 	= "";
		if($LOCKJMONTH != '') $addQM = "AND LockM = '$LOCKJMONTH'";
		$updLJ 		= "UPDATE tbl_journal_lock SET LockCateg = '$LOCKCATEG', isLock = '$statApp', 
						LockDate = '$curDate', UserLock = '$DefEmp_ID'
						WHERE LockY = '$LOCKJYEAR' $addQM";
		$this->db->query($updLJ);

		$data 		= array('app_stat' => $statApp);
		$this->session->set_userdata($data);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			if($LOCKJMONTH != '')
			{
				$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
				$MonthVw 	= "bulan ".$Month[$LOCKJMONTH-1]." $LOCKJYEAR";
			}
			else
			{
				$MonthVw 	= "semua periode tahun $LOCKJYEAR";
			}

			if($statApp == 1)
				$alert1	= "Transaksi penjurnalan $MonthVw sudah kami kunci.";
			else
				$alert1	= "Transaksi penjurnalan $MonthVw sudah kami buka kembali.";
		}
		else
		{
			if($LOCKJMONTH != '')
			{
				$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
				$MonthVw 	= "month ".$Month[$LOCKJMONTH-1]." $LOCKJYEAR";
			}
			else
			{
				$MonthVw 	= "all period year $LOCKJYEAR";
			}

			if($statApp == 1)
				$alert1	= "Journal transaction $MonthVw has been locked.";
			else
				$alert1	= "Journal transaction $MonthVw has been opened.";
		}

		echo "$alert1";
	}

	function appStat()
	{
		$s_00		= "SELECT app_stat FROM tappname";
		$r_00 		= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00):
			$app_stat 	= $rw_00->app_stat;
		endforeach;

		$DOC_DATE 	= date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('DOC_DATE'))));
		$JournalY 	= date('Y', strtotime($DOC_DATE));
		$JournalM 	= date('n', strtotime($DOC_DATE));

		// GET LOCK JOURNAL
			$getLJ	= "SELECT * FROM tbl_journal_lock WHERE LockY = '$JournalY' AND	LockM = '$JournalM'";
			$resLJ 	= $this->db->query($getLJ);
			echo json_encode($resLJ->result());
		// END GET LOCK JOURNAL
		

		// $LangID 	= $this->session->userdata['LangID'];
		// if($LangID == 'IND')
		// {
		// 	$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
		// 	$MonthVw 	= $Month[$LockM-1];
		// 	if($app_stat == 1 && $LockCateg == 2)
		// 		$alert1	= "Mohon maaf, saat ini transaksi bulan $MonthVw $LockY sedang terkunci.";
		// 	elseif($app_stat == 0 && $LockCateg == 2)
		// 		$alert1	= "Saat ini transaksi bulan $MonthVw $LockY sedang terbuka.";
		// }
		// else
		// {
		// 	$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
		// 	$MonthVw 	= $Month[$LockM-1];
		// 	if($app_stat == 1 && $LockCateg == 2)
		// 		$alert1	= "Sorry, the transaction month $MonthVw $LockY is currently locked.";
		// 	elseif($app_stat == 0 && $LockCateg == 2)
		// 		$alert1	= "The transaction month $MonthVw $LockY is currently unlocked.";
		// }

    	// $ACC_ID_PERSL	= "";
    	// $ACC_ID_EMPAP	= "";
    	// $s_01 			= "SELECT A.ACC_ID_PERSL, A.ACC_ID_EMPAP FROM tglobalsetting A";
    	// $r_01 			= $this->db->query($s_01)->result();
    	// foreach($r_01 as $rw_01):
    	// 	$ACC_ID_PERSL 	= $rw_01->ACC_ID_PERSL;
    	// 	$ACC_ID_EMPAP 	= $rw_01->ACC_ID_EMPAP;
    	// endforeach;

		// echo "$app_stat~$alert1~$LockCateg~$isLockJ~$ACC_ID_PERSL~$ACC_ID_EMPAP";
	}

	function lJRN()
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$LOCKJMONTH	= $this->input->post('LOCKJMONTH');
		$LOCKJYEAR	= $this->input->post('LOCKJYEAR');
		$LOCKCATEG	= $this->input->post('LOCKCATEG');
		$lokJrn 	= $this->input->post('lokJrn');

		date_default_timezone_set("Asia/Jakarta");
		$curDate 	= date('Y-m-d H:i:s');
		$addQM 	= "";
		if($LOCKJMONTH != '') $addQM = "AND LockM = '$LOCKJMONTH'";
		$updLJ 		= "UPDATE tbl_journal_lock SET isLockJ = '$lokJrn', 
						LockJDate = '$curDate', UserJLock = '$DefEmp_ID'
						WHERE LockY = '$LOCKJYEAR' $addQM";
		$this->db->query($updLJ);

		$s_0a		= "UPDATE tbl_journalheader SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT = 3";
		$this->db->query($s_0a);

		$s_0b		= "UPDATE tbl_journaldetail SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT = 3";
		$this->db->query($s_0b);

		$s_1a		= "UPDATE tbl_journalheader_vcash SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT IN (3,6)";
		$this->db->query($s_1a);

		$s_1b		= "UPDATE tbl_journaldetail_vcash SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT IN (3,6)";
		$this->db->query($s_1b);

		$s_2a		= "UPDATE tbl_journalheader_cprj SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT IN (3,6)";
		$this->db->query($s_2a);

		$s_2b		= "UPDATE tbl_journaldetail_cprj SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT IN (3,6)";
		$this->db->query($s_2b);

		$s_3a		= "UPDATE tbl_journalheader_pb SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT IN (3,6)";
		$this->db->query($s_3a);

		$s_3b		= "UPDATE tbl_journaldetail_pb SET isLock = '$lokJrn' WHERE MONTH(JournalH_Date) = '$LOCKJMONTH' AND YEAR(JournalH_Date) = '$LOCKJYEAR' AND GEJ_STAT IN (3,6)";
		$this->db->query($s_3b);

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			if($LOCKJMONTH != '')
			{
				$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
				$MonthVw 	= "bulan ".$Month[$LOCKJMONTH-1]." $LOCKJYEAR";
			}
			else
			{
				$MonthVw 	= "semua periode tahun $LOCKJYEAR";
			}

			if($lokJrn == 0) $alert1	= "Semua jurnal $MonthVw sudah kami buka.";
			else $alert1	= "Semua jurnal $MonthVw sudah kami kunci.";
		}
		else
		{
			if($LOCKJMONTH != '')
			{
				$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
				$MonthVw 	= "month ".$Month[$LOCKJMONTH-1]." $LOCKJYEAR";
			}
			else
			{
				$MonthVw 	= "all period year $LOCKJYEAR";
			}
			if($lokJrn == 0) $alert1	= "All Journal $MonthVw has been unlocked.";
			else $alert1	= "All Journal $MonthVw has been locked.";
		}

		echo "$alert1";
	}

	function getLockJrn()
	{
		$JrnY = $this->input->post('JrnY');
		$JrnM = $this->input->post('JrnM');

		// Get lock Jurnal
			$getLJ 	= "SELECT * FROM tbl_journal_lock WHERE LockY = '$JrnY' AND LockM = '$JrnM'";
			$resLJ 	= $this->db->query($getLJ);
			if($resLJ->num_rows() > 0)
			{
				$data = $resLJ->result();
			}
			else
			{
				// create lock journal => islock = 0
			}

			echo json_encode($data);
	}
}