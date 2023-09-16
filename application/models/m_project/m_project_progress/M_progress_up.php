<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 6 Mei 2018
 * File Name	= M_progress_up.php
 * Location		= -
*/

class M_progress_up extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_project_progress A 
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
					OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
		
	function count_all_WP($PRJCODE) // G
	{
		$sql	= "tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_WP($PRJCODE) // G
	{
		$sql = "SELECT * FROM tbl_project_progress WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_Job($PRJCODE) // G
	{
		//$sql		= "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISHEADER = 1 AND BOQ_PRICE > 0 AND JOBLEV > 1";
		$sql		= "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISBOBOT = 1";
		return $this->db->count_all($sql);
	}
	
	function view_all_Job($PRJCODE) // G
	{
		/*$sql		= "SELECT DISTINCT JOBCODEID, JOBCODEIDV, PRJCODE, JOBDESC, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST
						FROM tbl_joblist
						WHERE PRJCODE = '$PRJCODE' AND ISHEADER = 1 AND BOQ_PRICE > 0 AND JOBLEV > 1";*/
		$sql		= "SELECT DISTINCT JOBCODEID, JOBCODEIDV, PRJCODE, JOBDESC, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, 
							BOQ_JOBCOST, BOQ_BOBOT, JOBUNIT
						FROM tbl_joblist
						WHERE PRJCODE = '$PRJCODE' AND ISBOBOT = 1";
		return $this->db->query($sql);
	}
	
	function add($paramPRJP) // G
	{
		$this->db->insert('tbl_project_progress', $paramPRJP);
	}
	
	function get_PRJP_by_number($PRJP_NUM) // G
	{
		$sql = "SELECT * FROM tbl_project_progress WHERE PRJP_NUM = '$PRJP_NUM'";
		return $this->db->query($sql);
	}
	
	function updatePRPJ($PRJP_NUM, $paramPRJP) // G
	{
		$this->db->where('PRJP_NUM', $PRJP_NUM);
		$this->db->update('tbl_project_progress', $paramPRJP);
	}
	
	function deletePRPJDet($PRJP_NUM) // G
	{
		$this->db->where('PRJP_NUM', $PRJP_NUM);
		$this->db->delete('tbl_project_progress_det');
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_COA($PRJCODE, $Emp_ID) // OK
	{
		$sql		= "tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID' AND B.PRJCODE = '$PRJCODE' AND B.isLast = '1'";
		return $this->db->count_all($sql);
	}
	
	function view_all_COA($PRJCODE, $Emp_ID) // OK
	{
		$sql		= "SELECT DISTINCT B.Account_Number, B.Account_NameEn, B.Account_NameId, B.Account_Class, B.PRJCODE, 
							B.Base_OpeningBalance, B.Base_Debet, B.Base_Kredit
						FROM tbl_employee_acc A
						INNER JOIN tbl_chartaccount B ON B.Account_Number = A.Acc_Number
						WHERE A.Emp_ID = '$Emp_ID' AND B.PRJCODE = '$PRJCODE' AND B.isLast = '1'";
		return $this->db->query($sql);
	}
	
	function updateDet($PR_NUM, $PRJCODE, $PR_DATE) // OK
	{
		$sql = "UPDATE tbl_pr_detail SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function update($PR_NUM, $projMatReqH) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function deleteDetail($PR_NUM) // OK
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->delete('tbl_pr_detail');
	}
	
	function updateBOQPROG($PRJCODE, $JOBCODEID, $PROG_AKUM, $PROG_AKUMP, $PRJP_STEP, $PROG_BEF, $PROG_PERC, $PROG_VAL) // OK
	{
		// PROG_PERC 	BOBOT
		// PROG_PERC2	THD BOBOT

		if($PROG_PERC > 0)
		{
			$s_01 = "UPDATE tbl_joblist SET BOQ_PROGR = $PROG_AKUM, BOQ_BOBOT_PI = BOQ_BOBOT_PI + $PROG_PERC, BOQ_BOBOT_PIV = BOQ_BOBOT_PIV + $PROG_VAL
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
			$this->db->query($s_01);

			$s_01 = "UPDATE tbl_joblist_detail SET BOQ_PROGR = $PROG_AKUM, BOQ_BOBOT_PI = BOQ_BOBOT_PI + $PROG_PERC, BOQ_BOBOT_PIV = BOQ_BOBOT_PIV + $PROG_VAL
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
			$this->db->query($s_01);
		}
	}
	
	function updateBOQPROG_EKS_220906($PRJCODE, $JOBCODEID, $PROG_PERC_EKS, $PROG_VAL_EKS) // OK
	{
		$s_01 = "UPDATE tbl_joblist SET BOQ_PROGR = $PROG_AKUM, BOQ_BOBOT_PIEKS = BOQ_BOBOT_PIEKS + $PROG_PERC_EKS, BOQ_BOBOT_PIVEKS = BOQ_BOBOT_PIVEKS + $PROG_VAL_EKS
					WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
		$this->db->query($s_01);
	}
	
	function updateBOQPROG_EKS($PRJCODE, $JOBCODEID, $PROG_PERC_EKS, $PROG_VAL_EKS) // OK
	{
		if($PROG_PERC_EKS > 0)
		{
			$s_01 = "UPDATE tbl_joblist SET BOQ_BOBOT_PIEKS = BOQ_BOBOT_PIEKS + $PROG_PERC_EKS, BOQ_BOBOT_PIEKSV = BOQ_BOBOT_PIEKSV + $PROG_VAL_EKS
						WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
			$this->db->query($s_01);
		}
	}
	
	function add_importprogg($ProggHist) // OK
	{
		$this->db->insert('tbl_progg_uphist', $ProggHist);
	}
	
	function get_AllDataEkstC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_project_progress A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
					AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
					OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataEkstL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_progress A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.PRJP_STAT = 3
							AND (A.PRJP_NUM LIKE '%$search%' ESCAPE '!' OR A.PRJP_STEP LIKE '%$search%' ESCAPE '!' 
							OR A.PRJP_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLC($PRJCODE, $search, $length, $start) // GOOD
	{
		if($PRJCODE == '537022')
			$ADDQRY 	= "";
		else
			$ADDQRY 	= "AND ISLASTH = 1";

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT,
						A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST,
						B.PROG_VAL, B.PROG_PERC, A.BOQ_BOBOT, B.PROG_DESC
					FROM tbl_joblist_detail_$PRJCODEVW A
						LEFT JOIN tbl_project_progress_det B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 AND ISLASTH = 1
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";*/
			$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT,
						A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT, A.AMD_VOL, A.AMD_VAL
					FROM tbl_joblist_detail_$PRJCODEVW A
					WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 $ADDQRY AND ISBOBOT = 1
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		}
		else
		{
			$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT,
						A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT, A.AMD_VOL, A.AMD_VAL
					FROM tbl_joblist_detail_$PRJCODEVW A
					WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 $ADDQRY AND ISBOBOT = 1
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($PRJCODE == '537022')
			$ADDQRY 	= "";
		else
			$ADDQRY 	= "AND ISLASTH = 1";

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			if($order !=null)
			{
				/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT, A.ISLASTH,
							A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST,
							B.PROG_VAL, B.PROG_PERC, A.BOQ_BOBOT, B.PROG_DESC
						FROM tbl_joblist_detail_$PRJCODEVW A
							LEFT JOIN tbl_project_progress_det B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 AND ISLASTH = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";*/
				/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT, A.ISLASTH,
							A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 AND ISLASTH = 1 AND ISBOBOT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";*/
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT, A.ISLASTH,
							A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT, A.AMD_VOL, A.AMD_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 $ADDQRY AND ISBOBOT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT, A.ISLASTH,
							A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT, A.AMD_VOL, A.AMD_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 $ADDQRY AND ISBOBOT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT, A.ISLASTH,
							A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT, A.AMD_VOL, A.AMD_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 $ADDQRY AND ISBOBOT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC, A.ITM_GROUP, A.ITM_UNIT, A.ISLASTH,
							A.ITM_VOLM, A.ITM_BUDG, A.BOQ_VOLM, A.BOQ_JOBCOST, A.BOQ_BOBOT, A.AMD_VOL, A.AMD_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.PRJCODE = '$PRJCODE' AND A.BOQ_BOBOT > 0 $ADDQRY AND ISBOBOT = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>