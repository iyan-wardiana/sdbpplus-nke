<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 30 Agustus 2017
	* File Name	= M_task_request.php
	* Location		= -
*/

class M_task_request extends CI_Model
{
	function count_all_task($DefEmp_ID) // OK
	{
		$sqlOpen		= "SELECT isHelper FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
		$sqlOpen		= $this->db->query($sqlOpen)->result();
		foreach($sqlOpen as $rowOpen) :
			$isHelper	= $rowOpen->isHelper;
		endforeach;
		
		/****if($isHelper == 1)
		{
			$sql = "tbl_task_request";
		}
		else
		{*/
			//$sql = "tbl_task_request WHERE TASK_REQUESTER = '$DefEmp_ID' OR (TASK_TO LIKE '%DefEmp_ID%' OR TASK_TO = 'All')";
			$sql = "tbl_task_request WHERE TASK_REQUESTER = '$DefEmp_ID' OR (TASK_AUTHOR LIKE '%$DefEmp_ID%' OR TASK_TO LIKE '%$DefEmp_ID%' OR TASK_TO = 'All') AND TASK_STAT != 99";
		//****}
		return $this->db->count_all($sql);
	}
	
	function view_all_task($DefEmp_ID) // OK
	{
		$sqlOpen		= "SELECT isHelper FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
		$sqlOpen		= $this->db->query($sqlOpen)->result();
		foreach($sqlOpen as $rowOpen) :
			$isHelper	= $rowOpen->isHelper;
		endforeach;
		
		/****if($isHelper == 1)
		{
			$sql = "SELECT * FROM tbl_task_request ORDER BY TASK_DATE DESC";
		}
		else
		{*/
			$sql = "SELECT * FROM tbl_task_request WHERE TASK_REQUESTER = '$DefEmp_ID' OR (TASK_AUTHOR LIKE '%$DefEmp_ID%' OR TASK_TO LIKE '%$DefEmp_ID%'
							OR TASK_TO = 'All') AND TASK_STAT != 99
						ORDER BY TASK_DATE DESC";
		//****}
		
		return $this->db->query($sql);
	}
	
	function get_AllDataC($EMP_ID, $TREQ, $TSTAT, $TRSTAT, $search) // GOOD
	{
		$ADDQRY0 		= "(TASK_REQUESTER LIKE '$EMP_ID' OR TASK_TO = '$EMP_ID' OR TASK_TO = 'All')";
		if($TREQ != "0")
			$ADDQRY0 	= "(TASK_REQUESTER LIKE '$TREQ' OR TASK_TO = '$TREQ' OR TASK_TO = 'All')";

		$ADDQRY1 		= "";
		if($TSTAT != 0)
			$ADDQRY1 	= "AND A.TASK_STAT = '$TSTAT'";

		$ADDQRY2 	= "";
		if($TRSTAT != 0)
			$ADDQRY2 	= "AND A.TASK_CODE IN (SELECT C.TASKD_PARENT FROM tbl_task_request_detail C WHERE C.TASKD_EMPID2 LIKE '%$EMP_ID%' AND C.TASKD_RSTAT = '$TRSTAT')";

		/*$sql 	= "tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
					WHERE A.TASK_REQUESTER = '$EMP_ID'
						AND (A.TASK_CODE LIKE '%$search%' ESCAPE '!' OR A.TASK_TITLE LIKE '%$search%' ESCAPE '!'
						OR A.TASK_MENUNM LIKE '%$search%' ESCAPE '!' OR CONCAT(B.First_Name,' ',B.Last_Name) LIKE '%$search%' ESCAPE '!')";*/

		$sql 	= "tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
					WHERE $ADDQRY0 AND TASK_STAT != 99 $ADDQRY1 $ADDQRY2
						AND (A.TASK_CODE LIKE '%$search%' ESCAPE '!' OR A.TASK_TITLE LIKE '%$search%' ESCAPE '!'
						OR A.TASK_MENUNM LIKE '%$search%' ESCAPE '!' OR CONCAT(B.First_Name,' ',B.Last_Name) LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($EMP_ID, $TREQ, $TSTAT, $TRSTAT, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY0 		= "(TASK_REQUESTER LIKE '$EMP_ID' OR TASK_TO = '$EMP_ID' OR TASK_TO = 'All')";
		if($TREQ != "0")
			$ADDQRY0 	= "(TASK_REQUESTER LIKE '$TREQ' OR TASK_TO = '$TREQ' OR TASK_TO = 'All')";

		$ADDQRY1 		= "";
		if($TSTAT != 0)
			$ADDQRY1 	= "AND A.TASK_STAT = $TSTAT";

		$ADDQRY2 	= "";
		if($TRSTAT != 0)
			$ADDQRY2 	= "AND A.TASK_CODE IN (SELECT C.TASKD_PARENT FROM tbl_task_request_detail C WHERE C.TASKD_EMPID2 LIKE '%$EMP_ID%' AND C.TASKD_RSTAT = $TRSTAT)";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, CONCAT(B.First_Name,' ',B.Last_Name) AS REQ_NAME
						FROM tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
						WHERE $ADDQRY0 AND TASK_STAT != 99 $ADDQRY1 $ADDQRY2
							AND (A.TASK_CODE LIKE '%$search%' ESCAPE '!' OR A.TASK_TITLE LIKE '%$search%' ESCAPE '!'
							OR A.TASK_MENUNM LIKE '%$search%' ESCAPE '!' OR CONCAT(B.First_Name,' ',B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, CONCAT(B.First_Name,' ',B.Last_Name) AS REQ_NAME
						FROM tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
						WHERE $ADDQRY0 AND TASK_STAT != 99 $ADDQRY1 $ADDQRY2
							AND (A.TASK_CODE LIKE '%$search%' ESCAPE '!' OR A.TASK_TITLE LIKE '%$search%' ESCAPE '!'
							OR A.TASK_MENUNM LIKE '%$search%' ESCAPE '!' OR CONCAT(B.First_Name,' ',B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, CONCAT(B.First_Name,' ',B.Last_Name) AS REQ_NAME
						FROM tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
						WHERE $ADDQRY0 AND TASK_STAT != 99 $ADDQRY1 $ADDQRY2
							AND (A.TASK_CODE LIKE '%$search%' ESCAPE '!' OR A.TASK_TITLE LIKE '%$search%' ESCAPE '!'
							OR A.TASK_MENUNM LIKE '%$search%' ESCAPE '!' OR CONCAT(B.First_Name,' ',B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, CONCAT(B.First_Name,' ',B.Last_Name) AS REQ_NAME
						FROM tbl_task_request A INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
						WHERE $ADDQRY0 AND TASK_STAT != 99 $ADDQRY1 $ADDQRY2
							AND (A.TASK_CODE LIKE '%$search%' ESCAPE '!' OR A.TASK_TITLE LIKE '%$search%' ESCAPE '!'
							OR A.TASK_MENUNM LIKE '%$search%' ESCAPE '!' OR CONCAT(B.First_Name,' ',B.Last_Name) LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			return $this->db->query($sql);
			//return $sql;
		}
	}
	
	function getCount_menu() // OK
	{
		$DefID      = $this->session->userdata['Emp_ID'];
		$sql		= "tbl_menu WHERE level_menu = '1' AND isActive = 1 AND menu_user = 1
						AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefID')";
		return $this->db->count_all($sql);
	}

	function get_menu() // OK
	{
		$DefID  = $this->session->userdata['Emp_ID'];
		$sql 	= "SELECT * FROM tbl_menu WHERE level_menu = '1' AND isActive = 1 AND menu_user = 1
					AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefID') ORDER BY no_urut";
		return $this->db->query($sql);
	}
	
	function add($InsTR) // OK
	{
		$this->db->insert('tbl_task_request', $InsTR);
	}
	
	function addDet($InsTRD) // OK
	{
		$this->db->insert('tbl_task_request_detail', $InsTRD);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function viewTaskDet($TASK_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_task_request  WHERE TASK_CODE = '$TASK_CODE'";
		return $this->db->query($sql);
	}
	
	function update($IK_CODE, $indic) // OK
	{
		$this->db->where('IK_CODE', $IK_CODE);
		$this->db->update('tbl_indikator ', $indic);
	}
					
	function UpdateOriginal($TASKD_ID) // OK
	{
		$sql = "UPDATE tbl_task_request_detail SET TASKD_RSTAT = '2' WHERE TASKD_ID = '$TASKD_ID'";
		return $this->db->query($sql);
	}
	
	function get_AllDataDOCC($PRJCODE, $tblNameH, $FldCd, $FldDt, $FldDesc, $search) // GOOD
	{
		$sql = "$tblNameH
                WHERE PRJCODE = '$PRJCODE'
					AND ($FldCd LIKE '%$search%' ESCAPE '!' OR $FldDesc LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataDOCL($PRJCODE, $tblNameH, $FldCd, $FldDt, $FldDesc, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT $FldCd AS DOC_CODE, $FldDt AS DOC_DATE, $FldDesc AS DOC_DESC
						FROM $tblNameH
		                WHERE PRJCODE = '$PRJCODE' AND ($FldCd LIKE '%$search%' ESCAPE '!' OR $FldDesc LIKE '%$search%' ESCAPE '!')";
			}
			else
			{
				$sql = "SELECT $FldCd AS DOC_CODE, $FldDt AS DOC_DATE, $FldDesc AS DOC_DESC
						FROM $tblNameH
		                WHERE PRJCODE = '$PRJCODE' AND ($FldCd LIKE '%$search%' ESCAPE '!' OR $FldDesc LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
			//return $sql;
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT $FldCd AS DOC_CODE, $FldDt AS DOC_DATE, $FldDesc AS DOC_DESC
						FROM $tblNameH
		                WHERE PRJCODE = '$PRJCODE' AND ($FldCd LIKE '%$search%' ESCAPE '!' OR $FldDesc LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT $FldCd AS DOC_CODE, $FldDt AS DOC_DATE, $FldDesc AS DOC_DESC
						FROM $tblNameH
		                WHERE PRJCODE = '$PRJCODE' AND ($FldCd LIKE '%$search%' ESCAPE '!' OR $FldDesc LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
			//return $sql;
		}
	}
}
?>