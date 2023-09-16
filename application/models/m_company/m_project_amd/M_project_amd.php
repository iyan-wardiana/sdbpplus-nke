<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 April 2018
	* File Name		= M_project_amd.php
	* Location		= -
*/

class M_project_amd extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_amd_header A
					/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'*/
				WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'PRJ'
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	 
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_pek($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_amd_header A
					/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'*/
				WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'OVH'
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	 
	function get_AllDataL_pek($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A AND A.AMD_TYPE = 'OVH'
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A AND A.AMD_TYPE = 'OVH'
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'OVH'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, A.AMD_JOBID, A.AMD_JOBDESC AS JOBDESC
						FROM tbl_amd_header A
							/*LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'*/
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_TYPE = 'OVH'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR A.AMD_JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_amd($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_amd($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllItem($PRJCODE, $AMD_CATEG, $JOBCODE) // OK
	{
		if($PRJCODE == 'KTR')
		{
			if($AMD_CATEG == 'NB')
			{
				$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE'";
			}
			else
			{
				$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
		}
		else
		{
			if($AMD_CATEG == 'OB')
			{
				$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SI')
			{
				$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SINJ')
			{
				/*$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE'";*/
				$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
			elseif($AMD_CATEG == 'NB')
			{
				/*$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";*/
				$sql		= "tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE'";
			}
			elseif($AMD_CATEG == 'OTH')
			{
				$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
			else
			{
				$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}			
		}		
		return $this->db->count_all($sql);		
	}
	
	function viewAllItemMatBudget($PRJCODE, $AMD_CATEG, $JOBCODE) // OK
	{
		if($PRJCODE == 'KTR')
		{
			if($AMD_CATEG == 'NB')
			{
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE'";
			}
			else
			{
				$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
								LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
		}
		else
		{
			if($AMD_CATEG == 'OB')
			{
				$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SI')
			{
				$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'";
			}
			elseif($AMD_CATEG == 'SINJ')
			{
				/*$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT,
								A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, A.ADD_PRICE, A.ITM_GROUP, 
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_NAME, B.ITM_TYPE
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'";*/
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
			elseif($AMD_CATEG == 'NB')
			{
				/*$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE, 
								A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, A.ADD_VOLM, 
								A.ADD_PRICE, A.ITM_GROUP, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, 
								A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, 
								A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
								B.ITM_TYPE, A.JOBDESC AS ITM_NAME
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
								WHERE A.PRJCODE = '$PRJCODE' AND A.JOBPARENT = '$JOBCODE'
							UNION ALL
							SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND ISOUTB = 1";*/
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE'";
			}
			elseif($AMD_CATEG == 'OTH')
			{
				$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
								PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLM, ADDVOLM AS ADD_VOLM, 
								ADDCOST AS ADD_PRICE, ITM_GROUP, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, 
								PO_AMOUNT, IR_VOLM, IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, 
								ITM_VOLM AS ITM_STOCK, ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
								ITM_TYPE, ITM_NAME
							FROM tbl_item
								WHERE PRJCODE = '$PRJCODE' AND STATUS = 1";
			}
		}
		return $this->db->query($sql);
	}
	
	function addAMD($insertAMD) // OK
	{
		$this->db->insert('tbl_amd_header', $insertAMD);
	}
	
	function get_amd_by_number($AMD_NUM) // OK
	{
		$sql		= "SELECT * FROM tbl_amd_header WHERE AMD_NUM = '$AMD_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateAMD($AMD_NUM, $updateAMD) // OK
	{
		$this->db->where('AMD_NUM', $AMD_NUM);
		$this->db->update('tbl_amd_header', $updateAMD);
	}
	
	function deleteAMDDet($AMD_NUM) // OK
	{
		$this->db->where('AMD_NUM', $AMD_NUM);
		$this->db->delete('tbl_amd_detail');
	}
	
	function deleteAMDDetSUBS($AMD_NUM) // OK
	{
		$this->db->where('AMD_NUM', $AMD_NUM);
		$this->db->delete('tbl_amd_detail_subs');
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_amd_header A
					LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'PRJ'
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'PRJ'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_AMDInb($PRJCODE, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7)
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_AMDInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
						A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
						B.JOBDESC
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.AMD_STAT IN (2,7)
						AND (A.AMD_NUM LIKE '%$key%' ESCAPE '!' OR A.AMD_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.AMD_JOBID LIKE '%$key%' ESCAPE '!' OR A.AMD_DESC LIKE '%$key%' ESCAPE '!'
						OR A.AMD_NOTES LIKE '%$key%' ESCAPE '!' OR B.JOBDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function get_amd_by_numberLC($DefEmp_ID) // OK
	{
		$sql 		= "tbl_amd_header A
							INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
							AND A.AMD_STAT IN (2,7)";
		$countAMD 	= $this->db->count_all($sql);
		return $countAMD;
	}
	
	function get_amd_by_numberL($DefEmp_ID) // OK
	{
		$sql 	= "SELECT A.PRJCODE
					FROM tbl_amd_header A
						INNER JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND A.AMD_STAT IN (2,7) LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateWBSH($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$PRJCODE		= $paramWBS['PRJCODE'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$JOBPAR 		= explode(".", $JOBCODEID);
		$JOBLEV1 		= $JOBPAR[0];

		$s_isLS 	= "tbl_unitls WHERE ITM_UNIT IN (SELECT ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
		$r_isLS 	= $this->db->count_all($s_isLS);

		$s_u1	= "UPDATE tbl_joblist_detail SET AMD_VOL = IF($r_isLS = 1, 1, AMD_VOL + $ADD_VOLM),
						AMD_VAL = AMD_VAL + $ADD_JOBCOST, ADD_PRICE = AMD_VAL / AMD_VOL
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($s_u1);

		$s_u2	= "UPDATE tbl_joblist_detail SET AMD_VOL = IF($r_isLS = 1, 1, AMD_VOL + $ADD_VOLM), AMD_VAL = AMD_VAL + $ADD_JOBCOST
					WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
		$this->db->query($s_u2);

		$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM), ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
					WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
		$this->db->query($s_u3);

		$s_JID 		= "SELECT
							IF(SUM(ITM_VOLM) IS NULL, 0, SUM(ITM_VOLM))		AS TITM_VOL,
							IF(SUM(ITM_BUDG) IS NULL, 0, SUM(ITM_BUDG)) 	AS TITM_VAL,
							IF(SUM(AMD_VOL) IS NULL, 0, SUM(AMD_VOL)) 		AS TAMD_VOL,
							IF(SUM(AMD_VAL) IS NULL, 0, SUM(AMD_VAL)) 		AS TAMD_VAL,
							IF(SUM(AMDM_VOL) IS NULL, 0, SUM(AMDM_VOL)) 	AS TAMDM_VOL,
							IF(SUM(AMDM_VAL) IS NULL, 0, SUM(AMDM_VAL)) 	AS TAMDM_VAL,
							IF(SUM(FPA_VOL) IS NULL, 0, SUM(FPA_VOL)) 		AS TFPA_VOL,
							IF(SUM(FPA_VAL) IS NULL, 0, SUM(FPA_VAL)) 		AS TFPA_VAL,
							IF(SUM(FPA_CVOL) IS NULL, 0, SUM(FPA_CVOL)) 	AS TFPA_CVOL,
							IF(SUM(FPA_CVAL) IS NULL, 0, SUM(FPA_CVAL)) 	AS TFPA_CVAL,
							IF(SUM(PR_VOL) IS NULL, 0, SUM(PR_VOL)) 		AS TPR_VOL,
							IF(SUM(PR_VAL) IS NULL, 0, SUM(PR_VAL)) 		AS TPR_VALL,
							IF(SUM(PR_CVOL) IS NULL, 0, SUM(PR_CVOL)) 		AS TPR_CVOL,
							IF(SUM(PR_CVAL) IS NULL, 0, SUM(PR_CVAL)) 		AS TPR_CVAL,
							IF(SUM(PO_VOL) IS NULL, 0, SUM(PO_VOL)) 		AS TPO_VOL,
							IF(SUM(PO_VAL) IS NULL, 0, SUM(PO_VAL)) 		AS TPO_VAL,
							IF(SUM(PO_CVOL) IS NULL, 0, SUM(PO_CVOL)) 		AS TPO_CVOL,
							IF(SUM(PO_CVAL) IS NULL, 0, SUM(PO_CVAL)) 		AS TPO_CVAL,
							IF(SUM(IR_VOL) IS NULL, 0, SUM(IR_VOL)) 		AS TIR_VOL,
							IF(SUM(IR_VAL) IS NULL, 0, SUM(IR_VAL)) 		AS TIR_VAL,
							IF(SUM(UM_VOL) IS NULL, 0, SUM(UM_VOL)) 		AS TUM_VOL,
							IF(SUM(UM_VAL) IS NULL, 0, SUM(UM_VAL)) 		AS TUM_VAL,
							IF(SUM(WO_VOL) IS NULL, 0, SUM(WO_VOL)) 		AS TWO_VOL,
							IF(SUM(WO_VAL) IS NULL, 0, SUM(WO_VAL)) 		AS TWO_VAL,
							IF(SUM(WO_CVOL) IS NULL, 0, SUM(WO_CVOL)) 		AS TWO_CVOL,
							IF(SUM(WO_CVAL) IS NULL, 0, SUM(WO_CVAL)) 		AS TWO_CVAL,
							IF(SUM(OPN_VOL) IS NULL, 0, SUM(OPN_VOL)) 		AS TOPN_VOL,
							IF(SUM(OPN_VAL) IS NULL, 0, SUM(OPN_VAL)) 		AS TOPN_VAL,
							IF(SUM(VCASH_VOL) IS NULL, 0, SUM(VCASH_VOL)) 	AS TVCASH_VOL,
							IF(SUM(VCASH_VAL) IS NULL, 0, SUM(VCASH_VAL)) 	AS TVCASH_VAL,
							IF(SUM(VLK_VOL) IS NULL, 0, SUM(VLK_VOL)) 		AS TVLK_VOL,
							IF(SUM(VLK_VAL) IS NULL, 0, SUM(VLK_VAL)) 		AS TVLK_VAL,
							IF(SUM(PPD_VOL) IS NULL, 0, SUM(PPD_VOL)) 		AS TPPD_VOL,
							IF(SUM(PPD_VAL) IS NULL, 0, SUM(PPD_VAL)) 		AS TPPD_VAL
						FROM tbl_joblist_detail_$PRJCODEVW
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' GROUP BY ITM_CODE";
		$r_JID		= $this->db->query($s_JID)->result();
		foreach($r_JID as $rw_JID):
			$ITM_VOLM 	= $rw_JID->TITM_VOL;
			$ITM_BUDG 	= $rw_JID->TITM_VAL;
			$AMD_VOL 	= $rw_JID->TAMD_VOL;
			$AMD_VAL 	= $rw_JID->TAMD_VAL;
			$AMDM_VOL 	= $rw_JID->TAMDM_VOL;
			$AMDM_VAL 	= $rw_JID->TAMDM_VAL;
			$FPA_VOL 	= $rw_JID->TFPA_VOL;
			$FPA_VAL 	= $rw_JID->TFPA_VAL;
			$FPA_CVOL 	= $rw_JID->TFPA_CVOL;
			$FPA_CVAL 	= $rw_JID->TFPA_CVAL;
			$PR_VOL 	= $rw_JID->TPR_VOL;
			$PR_VAL 	= $rw_JID->TPR_VALL;
			$PR_CVOL 	= $rw_JID->TPR_CVOL;
			$PR_CVAL 	= $rw_JID->TPR_CVAL;
			$PO_VOL 	= $rw_JID->TPO_VOL;
			$PO_VAL 	= $rw_JID->TPO_VAL;
			$PO_CVOL 	= $rw_JID->TPO_CVOL;
			$PO_CVAL 	= $rw_JID->TPO_CVAL;
			$IR_VOL 	= $rw_JID->TIR_VOL;
			$IR_VAL 	= $rw_JID->TIR_VAL;
			$UM_VOL 	= $rw_JID->TUM_VOL;
			$UM_VAL 	= $rw_JID->TUM_VAL;
			$WO_VOL 	= $rw_JID->TWO_VOL;
			$WO_VAL 	= $rw_JID->TWO_VAL;
			$WO_CVOL 	= $rw_JID->TWO_CVOL;
			$WO_CVAL 	= $rw_JID->TWO_CVAL;
			$OPN_VOL 	= $rw_JID->TOPN_VOL;
			$OPN_VAL 	= $rw_JID->TOPN_VAL;
			$VCASH_VOL 	= $rw_JID->TVCASH_VOL;
			$VCASH_VAL 	= $rw_JID->TVCASH_VAL;
			$VLK_VOL 	= $rw_JID->TVLK_VOL;
			$VLK_VAL	= $rw_JID->TVLK_VAL;
			$PPD_VOL	= $rw_JID->TPPD_VOL;
			$PPD_VAL 	= $rw_JID->TPPD_VAL;

			$s_01		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLM,	ITM_IN = $IR_VOL, FPA_VOL = $FPA_VOL, FPA_VAL = $FPA_VAL, FPA_CVOL = $FPA_CVOL, FPA_CVAL = $FPA_CVAL,
								 PR_VOL = $PR_VOL, PR_VAL = $PR_VAL, PO_VOL = $PO_VOL, PO_VAL = $PO_VAL, IR_VOL = $IR_VOL, IR_VAL = $IR_VAL,
								 UM_VOL = $UM_VOL, UM_VAL = $UM_VAL, WO_VOL = $WO_VOL, WO_VAL = $WO_VAL, OPN_VOL = $OPN_VOL, OPN_VAL = $OPN_VAL,
								 VCASH_VOL = $VCASH_VOL, VCASH_VAL = $VCASH_VAL, VLK_VOL = $VLK_VOL, VLK_VAL = $VLK_VAL, PPD_VOL = $PPD_VOL, PPD_VAL = $PPD_VAL,
								 AMD_VOL = $AMD_VOL, AMD_VAL = $AMD_VAL, AMDM_VOL = $AMDM_VOL, AMDM_VAL = $AMDM_VAL
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_01);
		endforeach;
	}
	
	function updateWBSD($paramWBS) // HOLD
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		$sql	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
						ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);
	}
	
	function updateWBS_220604($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_VOLM1 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);*/
		
		// SELECT ITM_VOLMBG, ITM_VOLMBGR, ITM_PRICE, ITM_LASTP, ADDVOLM, ADDCOST FROM tbl_item; 
		// SELECT JOBVOLM, PRICE, JOBCOST, ADD_VOLM, ADD_PRICE, ADD_JOBCOST FROM tbl_joblist; 
		// SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST  FROM tbl_joblist_detail;

		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			/*echo "DONE 11<br>";
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			echo "DONE 12<br>";*/
		}
		elseif($AMD_CLASS == 1) // VOL. ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. Harga diambil dari harga rata2 terakhir
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					/*$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $REQ_AMOUNT;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $ITM_BUDG / $ITM_VOLMP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN*/
					$BUD_USEVP 		= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					/*$ADD_VOLM		= $BUD_REMV;				// DIANGGAP SEBAGAI AMANDEMEN VOLUME*/
					$ADD_JOBCOST	= $ADD_VOLM * $ADD_PRICE;	// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 	4. UPDATE PRICE IN MASTER ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 2) // PRICE ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN HARGA
				1. Hanya melakukan perubahan harga
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 50			1,000		 50,000		Digunakan
					Sisa 		 50 		1,000 		 50,000		Sisa
					Amandemen 	 50 		1,200 		 60,000		Amandemen
					Total 		 50 		1,200 		110,000 	(Sisa + Amandemen)
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// HARGA RATA2 TERAKHIR SETELAH DIKURANGI RAP YANG DIGUNAKAN
					/*$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $REQ_AMOUNT;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $ITM_BUDG / $ITM_VOLMP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN*/
					$BUD_USEVP 		= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_JOBCOST	= $BUD_REMV*$ADD_PRICE;

			// 	3. UPDATE JOBLIST
				$ITM_BUDGB 	= $BUD_USEAMN + $ADD_JOBCOST;		// BUDGET YANG SUDAH DIGUNAKAN + SISA BUDGET SETELAH DIKALI HARGA AMANDEMEN

				$s_u1		= "UPDATE tbl_joblist_detail SET
									ADD_VOLM = ADD_VOLM+$ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 	4. UPDATE PRICE IN MASTER ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 10) // PRICE IS DISABLED. QTY ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. TIDAK merubah TOTAL BUDGET
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 80			1,000		 80,000		Digunakan
					Sisa 		 20 		1,000 		 20,000		Sisa
					Amandemen 	  5 		0,000 		  0,000		Amandemen
					Total Sisa	 25 		  800 		 20,000 	(HRG. = Sisa Amount / (Sisa + Amandemen))
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;

					$BUD_USEVP 		= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV + $ADD_VOLM1;	// SISA VOLUME + AMD_VOLM DIJADIKAN SEBAGAI AMANDEMEN VOLUME
					$ADD_PRICE 		= $BUD_REMAMN / $ADD_VOLM;
					$ADD_JOBCOST	= $BUD_REMAMN;				// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
	}
	
	function updateWBS_230219($paramWBS) 		// OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_VOLM1 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;

		$s_isLS 	= "tbl_unitls WHERE ITM_UNIT IN (SELECT ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
		$r_isLS 	= $this->db->count_all($s_isLS);

		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM),
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			/*echo "DONE 11<br>";
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			echo "DONE 12<br>";*/
		}
		elseif($AMD_CLASS == 1) // VOL. ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME --> TIDAK MERUBAH TOTAL
				0. Perubahan hanya volume biasanya terjadi karena volume sudah habis sementara nilai masih tersedia dan terjadi kepada item selain 'LS'
				1. Hanya melakukan perubahan volume. Harga berubah dengan sendirinya sebagai nilai rata2 dari sisa nilai dibagi dengan volume setelah perubahan
				2. Volume yang sudah digunakan seharusnya tidak ikut terakumulasi terhadap perubahan harga
			*/

			// 	1. MENDAPATKAN INFORMASI SISA BUDGET
				$ITM_REMV 	= 0;
				$ITM_PRICE 	= 0;
				$ITM_REMVAL = 0;
				$s_JID 		= "SELECT JOBCODEID, IF(ITM_UNIT = 'LS', 1, (ITM_VOLM + ADD_VOLM - REQ_VOLM - ADDM_VOLM)) AS ITM_REMV, ITM_LASTP,
									(ITM_BUDG + ADD_JOBCOST - REQ_AMOUNT - ADDM_JOBCOST) AS ITM_REMVAL
								FROM tbl_joblist_detail
								WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$JOBCODEID	= $rw_JID->JOBCODEID;
					$ITM_REMV	= $rw_JID->ITM_REMV;
					$ITM_REMVAL	= $rw_JID->ITM_REMVAL;
					//$ITM_PRICE= $rw_JID->ITM_LASTP;
					$ITM_REMVP 	= $ITM_REMV;
					if($ITM_REMV == 0)
						$ITM_REMVP = 1;
					$ITM_PRICE	= $ITM_REMVAL/$ITM_REMVP;
				endforeach;

			// 	2. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				/*$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ITM_LASTP = ($ITM_REMVAL / ($ITM_REMV + ADD_VOLM))
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// KARENA HANYA MELAKUKAN PERUBAHAN VOLUME DAN PENYESUAIAN HARGA TERAKHIR, MAKA TIDAK MERUBAH TOTAL KE HEADER
				// KODE ASLI ADA DI FUNGSI function updateWBS_220604($paramWBS) PADA LINE // DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST

			// 	3. UPDATE PRICE IN MASTER ITEM
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 2) // PRICE ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN HARGA
				1. Hanya melakukan perubahan harga. Namun berpengaruh terhadap nilai total (Sisa Vol. * Harga Baru)
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan nilai total
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 50			1,000		 50,000		Digunakan
					Sisa 		 50 		1,000 		 50,000		Sisa
					Amandemen 	 50 		  200 		 10,000		Amandemen
					Total Sisa	 50 		1,200 		 60,000 	(Sisa + Amandemen)
					Total Budget 50 		1,200 		110,000 	(Budget + Amandemen)
			*/

			// 	1. MENDAPATKAN INFORMASI SISA BUDGET
				$ITM_REMV 	= 0;
				$ITM_PRICE 	= 0;
				$ITM_REMVAL = 0;
				$s_JID 		= "SELECT JOBCODEID, IF(ITM_UNIT = 'LS', 1, (ITM_VOLM + ADD_VOLM - REQ_VOLM - ADDM_VOLM)) AS ITM_REMV, ITM_LASTP,
									(ITM_BUDG + ADD_JOBCOST - REQ_AMOUNT - ADDM_JOBCOST) AS ITM_REMVAL
								FROM tbl_joblist_detail
								WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$JOBCODEID	= $rw_JID->JOBCODEID;
					$ITM_REMV	= $rw_JID->ITM_REMV;
					$ITM_REMVAL	= $rw_JID->ITM_REMVAL;
					//$ITM_PRICE= $rw_JID->ITM_LASTP;
					$ITM_REMVP 	= $ITM_REMV;
					if($ITM_REMV == 0)
						$ITM_REMVP = 1;
					$ITM_PRICE	= $ITM_REMVAL/$ITM_REMVP;
				endforeach;

			// 	2. MENGHITUNG NILAI TOTAL BUDGET YANG BARU DARI SISA VOLUME
				$TOT_AMNVAL 	= $ITM_REMV * $ADD_PRICE;

			// 	3. UPDATE JOBLIST (TIDAK ADA PENAMBAHAN VOLUME, HANYA PERUBAHAN TOTAL SBG AKIBTA DARI PERUBAHAN HARGA)
				/*$s_u1		= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM+$ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$s_u1		= "UPDATE tbl_joblist_detail SET ADD_VOLM = IF(ADD_VOLM=0, $ITM_REMV, ADD_VOLM),
									ADD_JOBCOST = ADD_JOBCOST + $TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 	4. UPDATE PRICE IN MASTER ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 10) // PRICE IS DISABLED. QTY ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. TIDAK merubah TOTAL BUDGET
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 80			1,000		 80,000		Digunakan
					Sisa 		 20 		1,000 		 20,000		Sisa
					Amandemen 	  5 		0,000 		  0,000		Amandemen
					Total Sisa	 25 		  800 		 20,000 	(HRG. = Sisa Amount / (Sisa + Amandemen))
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;

					$BUD_USEVP 		= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV + $ADD_VOLM1;	// SISA VOLUME + AMD_VOLM DIJADIKAN SEBAGAI AMANDEMEN VOLUME
					$ADD_PRICE 		= $BUD_REMAMN / $ADD_VOLM;
					$ADD_JOBCOST	= $BUD_REMAMN;				// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
	}
	
	function updateWBS($paramWBS) 		// OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_VOLM1 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$JOBPAR 		= explode(".", $JOBCODEID);
		$JOBLEV1 		= $JOBPAR[0];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;

		$s_isLS 	= "tbl_unitls WHERE ITM_UNIT IN (SELECT ITM_UNIT FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE')";
		$r_isLS 	= $this->db->count_all($s_isLS);

		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET AMD_VOL = IF($r_isLS = 1, 1, AMD_VOL + $ADD_VOLM),
							AMD_VAL = AMD_VAL + $ADD_JOBCOST, ADD_PRICE = AMD_VAL / AMD_VOL
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_u2	= "UPDATE tbl_joblist_detail SET AMD_VOL = IF($r_isLS = 1, 1, AMD_VOL + $ADD_VOLM), AMD_VAL = AMD_VAL + $ADD_JOBCOST
						WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
			$this->db->query($s_u2);

			$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = IF($r_isLS = 1, 1, ADD_VOLM + $ADD_VOLM), ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
						WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
			$this->db->query($s_u3);

			$s_JID 		= "SELECT
								IF(SUM(ITM_VOLM) IS NULL, 0, SUM(ITM_VOLM))		AS TITM_VOL,
								IF(SUM(ITM_BUDG) IS NULL, 0, SUM(ITM_BUDG)) 	AS TITM_VAL,
								IF(SUM(AMD_VOL) IS NULL, 0, SUM(AMD_VOL)) 		AS TAMD_VOL,
								IF(SUM(AMD_VAL) IS NULL, 0, SUM(AMD_VAL)) 		AS TAMD_VAL,
								IF(SUM(AMDM_VOL) IS NULL, 0, SUM(AMDM_VOL)) 	AS TAMDM_VOL,
								IF(SUM(AMDM_VAL) IS NULL, 0, SUM(AMDM_VAL)) 	AS TAMDM_VAL,
								IF(SUM(FPA_VOL) IS NULL, 0, SUM(FPA_VOL)) 		AS TFPA_VOL,
								IF(SUM(FPA_VAL) IS NULL, 0, SUM(FPA_VAL)) 		AS TFPA_VAL,
								IF(SUM(FPA_CVOL) IS NULL, 0, SUM(FPA_CVOL)) 	AS TFPA_CVOL,
								IF(SUM(FPA_CVAL) IS NULL, 0, SUM(FPA_CVAL)) 	AS TFPA_CVAL,
								IF(SUM(PR_VOL) IS NULL, 0, SUM(PR_VOL)) 		AS TPR_VOL,
								IF(SUM(PR_VAL) IS NULL, 0, SUM(PR_VAL)) 		AS TPR_VALL,
								IF(SUM(PR_CVOL) IS NULL, 0, SUM(PR_CVOL)) 		AS TPR_CVOL,
								IF(SUM(PR_CVAL) IS NULL, 0, SUM(PR_CVAL)) 		AS TPR_CVAL,
								IF(SUM(PO_VOL) IS NULL, 0, SUM(PO_VOL)) 		AS TPO_VOL,
								IF(SUM(PO_VAL) IS NULL, 0, SUM(PO_VAL)) 		AS TPO_VAL,
								IF(SUM(PO_CVOL) IS NULL, 0, SUM(PO_CVOL)) 		AS TPO_CVOL,
								IF(SUM(PO_CVAL) IS NULL, 0, SUM(PO_CVAL)) 		AS TPO_CVAL,
								IF(SUM(IR_VOL) IS NULL, 0, SUM(IR_VOL)) 		AS TIR_VOL,
								IF(SUM(IR_VAL) IS NULL, 0, SUM(IR_VAL)) 		AS TIR_VAL,
								IF(SUM(UM_VOL) IS NULL, 0, SUM(UM_VOL)) 		AS TUM_VOL,
								IF(SUM(UM_VAL) IS NULL, 0, SUM(UM_VAL)) 		AS TUM_VAL,
								IF(SUM(WO_VOL) IS NULL, 0, SUM(WO_VOL)) 		AS TWO_VOL,
								IF(SUM(WO_VAL) IS NULL, 0, SUM(WO_VAL)) 		AS TWO_VAL,
								IF(SUM(WO_CVOL) IS NULL, 0, SUM(WO_CVOL)) 		AS TWO_CVOL,
								IF(SUM(WO_CVAL) IS NULL, 0, SUM(WO_CVAL)) 		AS TWO_CVAL,
								IF(SUM(OPN_VOL) IS NULL, 0, SUM(OPN_VOL)) 		AS TOPN_VOL,
								IF(SUM(OPN_VAL) IS NULL, 0, SUM(OPN_VAL)) 		AS TOPN_VAL,
								IF(SUM(VCASH_VOL) IS NULL, 0, SUM(VCASH_VOL)) 	AS TVCASH_VOL,
								IF(SUM(VCASH_VAL) IS NULL, 0, SUM(VCASH_VAL)) 	AS TVCASH_VAL,
								IF(SUM(VLK_VOL) IS NULL, 0, SUM(VLK_VOL)) 		AS TVLK_VOL,
								IF(SUM(VLK_VAL) IS NULL, 0, SUM(VLK_VAL)) 		AS TVLK_VAL,
								IF(SUM(PPD_VOL) IS NULL, 0, SUM(PPD_VOL)) 		AS TPPD_VOL,
								IF(SUM(PPD_VAL) IS NULL, 0, SUM(PPD_VAL)) 		AS TPPD_VAL
							FROM tbl_joblist_detail_$PRJCODEVW
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' GROUP BY ITM_CODE";
			$r_JID		= $this->db->query($s_JID)->result();
			foreach($r_JID as $rw_JID):
				$ITM_VOLM 	= $rw_JID->TITM_VOL;
				$ITM_BUDG 	= $rw_JID->TITM_VAL;
				$AMD_VOL 	= $rw_JID->TAMD_VOL;
				$AMD_VAL 	= $rw_JID->TAMD_VAL;
				$AMDM_VOL 	= $rw_JID->TAMDM_VOL;
				$AMDM_VAL 	= $rw_JID->TAMDM_VAL;
				$FPA_VOL 	= $rw_JID->TFPA_VOL;
				$FPA_VAL 	= $rw_JID->TFPA_VAL;
				$FPA_CVOL 	= $rw_JID->TFPA_CVOL;
				$FPA_CVAL 	= $rw_JID->TFPA_CVAL;
				$PR_VOL 	= $rw_JID->TPR_VOL;
				$PR_VAL 	= $rw_JID->TPR_VALL;
				$PR_CVOL 	= $rw_JID->TPR_CVOL;
				$PR_CVAL 	= $rw_JID->TPR_CVAL;
				$PO_VOL 	= $rw_JID->TPO_VOL;
				$PO_VAL 	= $rw_JID->TPO_VAL;
				$PO_CVOL 	= $rw_JID->TPO_CVOL;
				$PO_CVAL 	= $rw_JID->TPO_CVAL;
				$IR_VOL 	= $rw_JID->TIR_VOL;
				$IR_VAL 	= $rw_JID->TIR_VAL;
				$UM_VOL 	= $rw_JID->TUM_VOL;
				$UM_VAL 	= $rw_JID->TUM_VAL;
				$WO_VOL 	= $rw_JID->TWO_VOL;
				$WO_VAL 	= $rw_JID->TWO_VAL;
				$WO_CVOL 	= $rw_JID->TWO_CVOL;
				$WO_CVAL 	= $rw_JID->TWO_CVAL;
				$OPN_VOL 	= $rw_JID->TOPN_VOL;
				$OPN_VAL 	= $rw_JID->TOPN_VAL;
				$VCASH_VOL 	= $rw_JID->TVCASH_VOL;
				$VCASH_VAL 	= $rw_JID->TVCASH_VAL;
				$VLK_VOL 	= $rw_JID->TVLK_VOL;
				$VLK_VAL	= $rw_JID->TVLK_VAL;
				$PPD_VOL	= $rw_JID->TPPD_VOL;
				$PPD_VAL 	= $rw_JID->TPPD_VAL;

				$s_01		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLM,	ITM_IN = $IR_VOL, FPA_VOL = $FPA_VOL, FPA_VAL = $FPA_VAL, FPA_CVOL = $FPA_CVOL, FPA_CVAL = $FPA_CVAL,
									 PR_VOL = $PR_VOL, PR_VAL = $PR_VAL, PO_VOL = $PO_VOL, PO_VAL = $PO_VAL, IR_VOL = $IR_VOL, IR_VAL = $IR_VAL,
									 UM_VOL = $UM_VOL, UM_VAL = $UM_VAL, WO_VOL = $WO_VOL, WO_VAL = $WO_VAL, OPN_VOL = $OPN_VOL, OPN_VAL = $OPN_VAL,
									 VCASH_VOL = $VCASH_VOL, VCASH_VAL = $VCASH_VAL, VLK_VOL = $VLK_VOL, VLK_VAL = $VLK_VAL, PPD_VOL = $PPD_VOL, PPD_VAL = $PPD_VAL,
									 AMD_VOL = $AMD_VOL, AMD_VAL = $AMD_VAL, AMDM_VOL = $AMDM_VOL, AMDM_VAL = $AMDM_VAL
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);
			endforeach;
		}
		elseif($AMD_CLASS == 1) // VOL. ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME --> TIDAK MERUBAH TOTAL
				0. Perubahan hanya volume biasanya terjadi karena volume sudah habis sementara nilai masih tersedia dan terjadi kepada item selain 'LS'
				1. Hanya melakukan perubahan volume. Harga berubah dengan sendirinya sebagai nilai rata2 dari sisa nilai dibagi dengan volume setelah perubahan
				2. Volume yang sudah digunakan seharusnya tidak ikut terakumulasi terhadap perubahan harga
			*/

			// 	1. MENDAPATKAN INFORMASI SISA BUDGET
				$ITM_REMV 	= 0;
				$ITM_PRICE 	= 0;
				$ITM_REMVAL = 0;
				$s_JID 		= "SELECT JOBCODEID, IF(ITM_UNIT = 'LS', 1, (ITM_VOLM + AMD_VOL - REQ_VOLM - AMDM_VOL)) AS ITM_REMV, ITM_LASTP,
									(ITM_BUDG + ADD_JOBCOST - REQ_AMOUNT - ADDM_JOBCOST) AS ITM_REMVAL
								FROM tbl_joblist_detail_$PRJCODEVW
								WHERE ITM_CODE = '$ITM_CODE' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$JOBCODEID	= $rw_JID->JOBCODEID;
					$ITM_REMV	= $rw_JID->ITM_REMV;
					$ITM_REMVAL	= $rw_JID->ITM_REMVAL;
					//$ITM_PRICE= $rw_JID->ITM_LASTP;
					$ITM_REMVP 	= $ITM_REMV;
					if($ITM_REMV == 0)
						$ITM_REMVP = 1;
					$ITM_PRICE	= $ITM_REMVAL/$ITM_REMVP;
				endforeach;

			// 	2. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				/*$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$s_u1	= "UPDATE tbl_joblist_detail SET AMD_VOL = AMD_VOL + $ADD_VOLM
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// KARENA HANYA MELAKUKAN PERUBAHAN VOLUME DAN PENYESUAIAN HARGA TERAKHIR, MAKA TIDAK MERUBAH TOTAL KE HEADER
				// KODE ASLI ADA DI FUNGSI function updateWBS_220604($paramWBS) PADA LINE // DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST

			// 	3. UPDATE PRICE IN MASTER ITEM
				$s_JID 		= "SELECT
									IF(SUM(ITM_VOLM) IS NULL, 0, SUM(ITM_VOLM))		AS TITM_VOL,
									IF(SUM(ITM_BUDG) IS NULL, 0, SUM(ITM_BUDG)) 	AS TITM_VAL,
									IF(SUM(AMD_VOL) IS NULL, 0, SUM(AMD_VOL)) 		AS TAMD_VOL,
									IF(SUM(AMD_VAL) IS NULL, 0, SUM(AMD_VAL)) 		AS TAMD_VAL,
									IF(SUM(AMDM_VOL) IS NULL, 0, SUM(AMDM_VOL)) 	AS TAMDM_VOL,
									IF(SUM(AMDM_VAL) IS NULL, 0, SUM(AMDM_VAL)) 	AS TAMDM_VAL,
									IF(SUM(FPA_VOL) IS NULL, 0, SUM(FPA_VOL)) 		AS TFPA_VOL,
									IF(SUM(FPA_VAL) IS NULL, 0, SUM(FPA_VAL)) 		AS TFPA_VAL,
									IF(SUM(FPA_CVOL) IS NULL, 0, SUM(FPA_CVOL)) 	AS TFPA_CVOL,
									IF(SUM(FPA_CVAL) IS NULL, 0, SUM(FPA_CVAL)) 	AS TFPA_CVAL,
									IF(SUM(PR_VOL) IS NULL, 0, SUM(PR_VOL)) 		AS TPR_VOL,
									IF(SUM(PR_VAL) IS NULL, 0, SUM(PR_VAL)) 		AS TPR_VALL,
									IF(SUM(PR_CVOL) IS NULL, 0, SUM(PR_CVOL)) 		AS TPR_CVOL,
									IF(SUM(PR_CVAL) IS NULL, 0, SUM(PR_CVAL)) 		AS TPR_CVAL,
									IF(SUM(PO_VOL) IS NULL, 0, SUM(PO_VOL)) 		AS TPO_VOL,
									IF(SUM(PO_VAL) IS NULL, 0, SUM(PO_VAL)) 		AS TPO_VAL,
									IF(SUM(PO_CVOL) IS NULL, 0, SUM(PO_CVOL)) 		AS TPO_CVOL,
									IF(SUM(PO_CVAL) IS NULL, 0, SUM(PO_CVAL)) 		AS TPO_CVAL,
									IF(SUM(IR_VOL) IS NULL, 0, SUM(IR_VOL)) 		AS TIR_VOL,
									IF(SUM(IR_VAL) IS NULL, 0, SUM(IR_VAL)) 		AS TIR_VAL,
									IF(SUM(UM_VOL) IS NULL, 0, SUM(UM_VOL)) 		AS TUM_VOL,
									IF(SUM(UM_VAL) IS NULL, 0, SUM(UM_VAL)) 		AS TUM_VAL,
									IF(SUM(WO_VOL) IS NULL, 0, SUM(WO_VOL)) 		AS TWO_VOL,
									IF(SUM(WO_VAL) IS NULL, 0, SUM(WO_VAL)) 		AS TWO_VAL,
									IF(SUM(WO_CVOL) IS NULL, 0, SUM(WO_CVOL)) 		AS TWO_CVOL,
									IF(SUM(WO_CVAL) IS NULL, 0, SUM(WO_CVAL)) 		AS TWO_CVAL,
									IF(SUM(OPN_VOL) IS NULL, 0, SUM(OPN_VOL)) 		AS TOPN_VOL,
									IF(SUM(OPN_VAL) IS NULL, 0, SUM(OPN_VAL)) 		AS TOPN_VAL,
									IF(SUM(VCASH_VOL) IS NULL, 0, SUM(VCASH_VOL)) 	AS TVCASH_VOL,
									IF(SUM(VCASH_VAL) IS NULL, 0, SUM(VCASH_VAL)) 	AS TVCASH_VAL,
									IF(SUM(VLK_VOL) IS NULL, 0, SUM(VLK_VOL)) 		AS TVLK_VOL,
									IF(SUM(VLK_VAL) IS NULL, 0, SUM(VLK_VAL)) 		AS TVLK_VAL,
									IF(SUM(PPD_VOL) IS NULL, 0, SUM(PPD_VOL)) 		AS TPPD_VOL,
									IF(SUM(PPD_VAL) IS NULL, 0, SUM(PPD_VAL)) 		AS TPPD_VAL
								FROM tbl_joblist_detail_$PRJCODEVW
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' GROUP BY ITM_CODE";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$ITM_VOLM 	= $rw_JID->TITM_VOL;
					$ITM_BUDG 	= $rw_JID->TITM_VAL;
					$AMD_VOL 	= $rw_JID->TAMD_VOL;
					$AMD_VAL 	= $rw_JID->TAMD_VAL;
					$AMDM_VOL 	= $rw_JID->TAMDM_VOL;
					$AMDM_VAL 	= $rw_JID->TAMDM_VAL;
					$FPA_VOL 	= $rw_JID->TFPA_VOL;
					$FPA_VAL 	= $rw_JID->TFPA_VAL;
					$FPA_CVOL 	= $rw_JID->TFPA_CVOL;
					$FPA_CVAL 	= $rw_JID->TFPA_CVAL;
					$PR_VOL 	= $rw_JID->TPR_VOL;
					$PR_VAL 	= $rw_JID->TPR_VALL;
					$PR_CVOL 	= $rw_JID->TPR_CVOL;
					$PR_CVAL 	= $rw_JID->TPR_CVAL;
					$PO_VOL 	= $rw_JID->TPO_VOL;
					$PO_VAL 	= $rw_JID->TPO_VAL;
					$PO_CVOL 	= $rw_JID->TPO_CVOL;
					$PO_CVAL 	= $rw_JID->TPO_CVAL;
					$IR_VOL 	= $rw_JID->TIR_VOL;
					$IR_VAL 	= $rw_JID->TIR_VAL;
					$UM_VOL 	= $rw_JID->TUM_VOL;
					$UM_VAL 	= $rw_JID->TUM_VAL;
					$WO_VOL 	= $rw_JID->TWO_VOL;
					$WO_VAL 	= $rw_JID->TWO_VAL;
					$WO_CVOL 	= $rw_JID->TWO_CVOL;
					$WO_CVAL 	= $rw_JID->TWO_CVAL;
					$OPN_VOL 	= $rw_JID->TOPN_VOL;
					$OPN_VAL 	= $rw_JID->TOPN_VAL;
					$VCASH_VOL 	= $rw_JID->TVCASH_VOL;
					$VCASH_VAL 	= $rw_JID->TVCASH_VAL;
					$VLK_VOL 	= $rw_JID->TVLK_VOL;
					$VLK_VAL	= $rw_JID->TVLK_VAL;
					$PPD_VOL	= $rw_JID->TPPD_VOL;
					$PPD_VAL 	= $rw_JID->TPPD_VAL;

					$s_01		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLM,	ITM_IN = $IR_VOL, FPA_VOL = $FPA_VOL, FPA_VAL = $FPA_VAL, FPA_CVOL = $FPA_CVOL, FPA_CVAL = $FPA_CVAL,
										 PR_VOL = $PR_VOL, PR_VAL = $PR_VAL, PO_VOL = $PO_VOL, PO_VAL = $PO_VAL, IR_VOL = $IR_VOL, IR_VAL = $IR_VAL,
										 UM_VOL = $UM_VOL, UM_VAL = $UM_VAL, WO_VOL = $WO_VOL, WO_VAL = $WO_VAL, OPN_VOL = $OPN_VOL, OPN_VAL = $OPN_VAL,
										 VCASH_VOL = $VCASH_VOL, VCASH_VAL = $VCASH_VAL, VLK_VOL = $VLK_VOL, VLK_VAL = $VLK_VAL, PPD_VOL = $PPD_VOL, PPD_VAL = $PPD_VAL,
										 AMD_VOL = $AMD_VOL, AMD_VAL = $AMD_VAL, AMDM_VOL = $AMDM_VOL, AMDM_VAL = $AMDM_VAL
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($s_01);
				endforeach;
		}
		elseif($AMD_CLASS == 2) // PRICE ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN HARGA
				1. Hanya melakukan perubahan harga. Namun berpengaruh terhadap nilai total (Sisa Vol. * Harga Baru)
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan nilai total
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 50			1,000		 50,000		Digunakan
					Sisa 		 50 		1,000 		 50,000		Sisa
					Amandemen 	 50 		  200 		 10,000		Amandemen
					Total Sisa	 50 		1,200 		 60,000 	(Sisa + Amandemen)
					Total Budget 50 		1,200 		110,000 	(Budget + Amandemen)
			*/

			// 	1. MENDAPATKAN INFORMASI SISA BUDGET
				$ITM_REMV 	= 0;
				$ITM_PRICE 	= 0;
				$ITM_REMVAL = 0;

				$s_JID 		= "SELECT JOBCODEID, ITM_UNIT, ITM_VOLM, ITM_LASTP,
									(ITM_VOLM + AMD_VOL - AMDM_VOL) AS ITM_BUDGV,
									(ITM_BUDG + AMD_VAL - AMDM_VAL) AS ITM_BUDGVAL,
									(FPA_VOL+FPA_VOL_R+PR_VOL+PR_VOL_R+WO_VOL+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS ITM_REQV,
									(FPA_CVOL+PR_CVOL+WO_CVOL) AS ITM_CREQV,
									(FPA_VAL+FPA_VAL_R+PR_VAL+PR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS ITM_REQVAL,
									(FPA_CVAL+PR_CVAL+WO_CVAL) AS ITM_CREQVAL
								FROM tbl_joblist_detail_$PRJCODEVW
								WHERE ITM_CODE = '$ITM_CODE' AND JOBPARENT = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$JOBCODEID		= $rw_JID->JOBCODEID;
					$ITM_BUDGV 		= $rw_JID->ITM_BUDGV;
					$ITM_BUDGVAL	= $rw_JID->ITM_BUDGVAL;
					$ITM_REQV1 		= $rw_JID->ITM_REQV;
					$ITM_CREQV 		= $rw_JID->ITM_CREQV;
					$ITM_REQVAL1 	= $rw_JID->ITM_REQVAL;
					$ITM_CREQVAL	= $rw_JID->ITM_CREQVAL;
					$ITM_REQV 		= ($ITM_REQV1 - $ITM_CREQV);
					$ITM_REQVAL 	= ($ITM_REQVAL1 - $ITM_CREQVAL);
					$ITM_REMV		= ($ITM_BUDGV - $ITM_REQV);
					$ITM_REMVAL		= ($ITM_BUDGVAL - $ITM_REQVAL);
					$ITM_REMVP 		= $ITM_REMV;
					if($ITM_REMV == 0)
						$ITM_REMVP = 1;
					$ITM_PRICE		= $ITM_REMVAL/$ITM_REMVP;
				endforeach;

			// 	2. MENGHITUNG NILAI TOTAL BUDGET YANG BARU DARI SISA VOLUME
				$TOT_AMNVAL 	= $ITM_REMV * $ADD_PRICE;

			// 	3. UPDATE JOBLIST (TIDAK ADA PENAMBAHAN VOLUME, HANYA PERUBAHAN TOTAL SBG AKIBTA DARI PERUBAHAN HARGA)
				/*$s_u1		= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM+$ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$s_u1		= "UPDATE tbl_joblist_detail SET AMD_VOL = IF(AMD_VOL=0, $ITM_REMV, AMD_VOL),
									AMD_VAL = AMD_VAL + $TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				$s_u2	= "UPDATE tbl_joblist_detail SET AMD_VAL = AMD_VAL + $TOT_AMNVAL
							WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
				$this->db->query($s_u2);

				$s_u3	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST + $TOT_AMNVAL, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
							WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
				$this->db->query($s_u3);

				$s_JID 		= "SELECT
									IF(SUM(ITM_VOLM) IS NULL, 0, SUM(ITM_VOLM))		AS TITM_VOL,
									IF(SUM(ITM_BUDG) IS NULL, 0, SUM(ITM_BUDG)) 	AS TITM_VAL,
									IF(SUM(AMD_VOL) IS NULL, 0, SUM(AMD_VOL)) 		AS TAMD_VOL,
									IF(SUM(AMD_VAL) IS NULL, 0, SUM(AMD_VAL)) 		AS TAMD_VAL,
									IF(SUM(AMDM_VOL) IS NULL, 0, SUM(AMDM_VOL)) 	AS TAMDM_VOL,
									IF(SUM(AMDM_VAL) IS NULL, 0, SUM(AMDM_VAL)) 	AS TAMDM_VAL,
									IF(SUM(FPA_VOL) IS NULL, 0, SUM(FPA_VOL)) 		AS TFPA_VOL,
									IF(SUM(FPA_VAL) IS NULL, 0, SUM(FPA_VAL)) 		AS TFPA_VAL,
									IF(SUM(FPA_CVOL) IS NULL, 0, SUM(FPA_CVOL)) 	AS TFPA_CVOL,
									IF(SUM(FPA_CVAL) IS NULL, 0, SUM(FPA_CVAL)) 	AS TFPA_CVAL,
									IF(SUM(PR_VOL) IS NULL, 0, SUM(PR_VOL)) 		AS TPR_VOL,
									IF(SUM(PR_VAL) IS NULL, 0, SUM(PR_VAL)) 		AS TPR_VALL,
									IF(SUM(PR_CVOL) IS NULL, 0, SUM(PR_CVOL)) 		AS TPR_CVOL,
									IF(SUM(PR_CVAL) IS NULL, 0, SUM(PR_CVAL)) 		AS TPR_CVAL,
									IF(SUM(PO_VOL) IS NULL, 0, SUM(PO_VOL)) 		AS TPO_VOL,
									IF(SUM(PO_VAL) IS NULL, 0, SUM(PO_VAL)) 		AS TPO_VAL,
									IF(SUM(PO_CVOL) IS NULL, 0, SUM(PO_CVOL)) 		AS TPO_CVOL,
									IF(SUM(PO_CVAL) IS NULL, 0, SUM(PO_CVAL)) 		AS TPO_CVAL,
									IF(SUM(IR_VOL) IS NULL, 0, SUM(IR_VOL)) 		AS TIR_VOL,
									IF(SUM(IR_VAL) IS NULL, 0, SUM(IR_VAL)) 		AS TIR_VAL,
									IF(SUM(UM_VOL) IS NULL, 0, SUM(UM_VOL)) 		AS TUM_VOL,
									IF(SUM(UM_VAL) IS NULL, 0, SUM(UM_VAL)) 		AS TUM_VAL,
									IF(SUM(WO_VOL) IS NULL, 0, SUM(WO_VOL)) 		AS TWO_VOL,
									IF(SUM(WO_VAL) IS NULL, 0, SUM(WO_VAL)) 		AS TWO_VAL,
									IF(SUM(WO_CVOL) IS NULL, 0, SUM(WO_CVOL)) 		AS TWO_CVOL,
									IF(SUM(WO_CVAL) IS NULL, 0, SUM(WO_CVAL)) 		AS TWO_CVAL,
									IF(SUM(OPN_VOL) IS NULL, 0, SUM(OPN_VOL)) 		AS TOPN_VOL,
									IF(SUM(OPN_VAL) IS NULL, 0, SUM(OPN_VAL)) 		AS TOPN_VAL,
									IF(SUM(VCASH_VOL) IS NULL, 0, SUM(VCASH_VOL)) 	AS TVCASH_VOL,
									IF(SUM(VCASH_VAL) IS NULL, 0, SUM(VCASH_VAL)) 	AS TVCASH_VAL,
									IF(SUM(VLK_VOL) IS NULL, 0, SUM(VLK_VOL)) 		AS TVLK_VOL,
									IF(SUM(VLK_VAL) IS NULL, 0, SUM(VLK_VAL)) 		AS TVLK_VAL,
									IF(SUM(PPD_VOL) IS NULL, 0, SUM(PPD_VOL)) 		AS TPPD_VOL,
									IF(SUM(PPD_VAL) IS NULL, 0, SUM(PPD_VAL)) 		AS TPPD_VAL
								FROM tbl_joblist_detail_$PRJCODEVW
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' GROUP BY ITM_CODE";
				$r_JID		= $this->db->query($s_JID)->result();
				foreach($r_JID as $rw_JID):
					$ITM_VOLM 	= $rw_JID->TITM_VOL;
					$ITM_BUDG 	= $rw_JID->TITM_VAL;
					$AMD_VOL 	= $rw_JID->TAMD_VOL;
					$AMD_VAL 	= $rw_JID->TAMD_VAL;
					$AMDM_VOL 	= $rw_JID->TAMDM_VOL;
					$AMDM_VAL 	= $rw_JID->TAMDM_VAL;
					$FPA_VOL 	= $rw_JID->TFPA_VOL;
					$FPA_VAL 	= $rw_JID->TFPA_VAL;
					$FPA_CVOL 	= $rw_JID->TFPA_CVOL;
					$FPA_CVAL 	= $rw_JID->TFPA_CVAL;
					$PR_VOL 	= $rw_JID->TPR_VOL;
					$PR_VAL 	= $rw_JID->TPR_VALL;
					$PR_CVOL 	= $rw_JID->TPR_CVOL;
					$PR_CVAL 	= $rw_JID->TPR_CVAL;
					$PO_VOL 	= $rw_JID->TPO_VOL;
					$PO_VAL 	= $rw_JID->TPO_VAL;
					$PO_CVOL 	= $rw_JID->TPO_CVOL;
					$PO_CVAL 	= $rw_JID->TPO_CVAL;
					$IR_VOL 	= $rw_JID->TIR_VOL;
					$IR_VAL 	= $rw_JID->TIR_VAL;
					$UM_VOL 	= $rw_JID->TUM_VOL;
					$UM_VAL 	= $rw_JID->TUM_VAL;
					$WO_VOL 	= $rw_JID->TWO_VOL;
					$WO_VAL 	= $rw_JID->TWO_VAL;
					$WO_CVOL 	= $rw_JID->TWO_CVOL;
					$WO_CVAL 	= $rw_JID->TWO_CVAL;
					$OPN_VOL 	= $rw_JID->TOPN_VOL;
					$OPN_VAL 	= $rw_JID->TOPN_VAL;
					$VCASH_VOL 	= $rw_JID->TVCASH_VOL;
					$VCASH_VAL 	= $rw_JID->TVCASH_VAL;
					$VLK_VOL 	= $rw_JID->TVLK_VOL;
					$VLK_VAL	= $rw_JID->TVLK_VAL;
					$PPD_VOL	= $rw_JID->TPPD_VOL;
					$PPD_VAL 	= $rw_JID->TPPD_VAL;

					$s_01		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLM,	ITM_IN = $IR_VOL, FPA_VOL = $FPA_VOL, FPA_VAL = $FPA_VAL, FPA_CVOL = $FPA_CVOL, FPA_CVAL = $FPA_CVAL,
										 PR_VOL = $PR_VOL, PR_VAL = $PR_VAL, PO_VOL = $PO_VOL, PO_VAL = $PO_VAL, IR_VOL = $IR_VOL, IR_VAL = $IR_VAL,
										 UM_VOL = $UM_VOL, UM_VAL = $UM_VAL, WO_VOL = $WO_VOL, WO_VAL = $WO_VAL, OPN_VOL = $OPN_VOL, OPN_VAL = $OPN_VAL,
										 VCASH_VOL = $VCASH_VOL, VCASH_VAL = $VCASH_VAL, VLK_VOL = $VLK_VOL, VLK_VAL = $VLK_VAL, PPD_VOL = $PPD_VOL, PPD_VAL = $PPD_VAL,
										 AMD_VOL = $AMD_VOL, AMD_VAL = $AMD_VAL, AMDM_VOL = $AMDM_VOL, AMDM_VAL = $AMDM_VAL
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($s_01);
				endforeach;
		}
		elseif($AMD_CLASS == 10) // PRICE IS DISABLED. QTY ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. TIDAK merubah TOTAL BUDGET
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 80			1,000		 80,000		Digunakan
					Sisa 		 20 		1,000 		 20,000		Sisa
					Amandemen 	  5 		0,000 		  0,000		Amandemen
					Total Sisa	 25 		  800 		 20,000 	(HRG. = Sisa Amount / (Sisa + Amandemen))
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;

					$BUD_USEVP 		= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV + $ADD_VOLM1;	// SISA VOLUME + AMD_VOLM DIJADIKAN SEBAGAI AMANDEMEN VOLUME
					$ADD_PRICE 		= $BUD_REMAMN / $ADD_VOLM;
					$ADD_JOBCOST	= $BUD_REMAMN;				// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									AMD_VOL = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, AMD_VOL, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->AMD_VOL;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, AMD_VOL = AMD_VOL + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
	}
	
	function updateWBSM($paramWBS) 			// OK - SI MINUS
	{
		$ADDM_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADDM_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADDM_VOLM == '')
			$ADDM_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADDM_JOBCOST == '')
			$ADDM_JOBCOST	= 0;

		if($AMD_CLASS == 0) 					// IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					// echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						// echo "DONE 4<br>";
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							// echo "DONE 5<br>";
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								// echo "DONE 6<br>";
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									// echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										// echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											// echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDMVOLM = ADDMVOLM + $ADDM_VOLM, ADDMCOST = $ADDM_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		}
		elseif($AMD_CLASS == 1) // VOLUME IS DISABLED. PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			// HANYA MERUBAH HARGA, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MENAMBAH VOLUME DAN NILAI ADDENDUM
				$ITM_VOLM	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ITM_VOLM * $ADD_PRICE;

			// CARI SELISIH TOTAL AWAL VS TOTAL AKHIR
				$DELTA_BUDG	= $ITM_BUDGB - $ITM_BUDGA;
			
			// UPDATE PRICE IN MASTER ITEM
				$s_01		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);

			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = $DELTA_BUDG, ITM_BUDG = $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				// echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
									ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ITM_BUDG = ITM_BUDG + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
										ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
										ITM_BUDG = ITM_BUDG + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
											ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
											ITM_BUDG = ITM_BUDG + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
												ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
												ITM_BUDG = ITM_BUDG + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
													ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
													ITM_BUDG = ITM_BUDG + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
														ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
														ITM_BUDG = ITM_BUDG + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
															ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
															ITM_BUDG = ITM_BUDG + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																ITM_BUDG = ITM_BUDG + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																	ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																	ITM_BUDG = ITM_BUDG + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
		}
		elseif($AMD_CLASS == 2) // PRICE IS DISABLED. QTY ONLY
		{
			// MERUBAH QTY, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MERUBAH HARGA
				$ITM_PRICE 	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_PRICE, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_PRICE	= $rowJL->ITM_PRICE;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ADDM_VOLM * $ITM_PRICE;
			
			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADDM_JOBCOST = $ITM_BUDGB,
									ITM_BUDG = ITM_BUDG + $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				// echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 		= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 		= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 		= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 		= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 		= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 		= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 		= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 		= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADDM_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			
			$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = ADDMVOLM + $ADDM_VOLM,
							ADDMCOST = ADDMCOST + $ITM_BUDGB
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateOTHWBS_220603($paramWBS)
	{
		$ADDM_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADDM_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADDM_VOLM == '')
			$ADDM_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADDM_JOBCOST == '')
			$ADDM_JOBCOST	= 0;

		if($AMD_CLASS == 1) 		// YANG DIAMANDEMEN ADALAH VOLUME
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					// echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						// echo "DONE 4<br>";
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							// echo "DONE 5<br>";
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								// echo "DONE 6<br>";
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									// echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										// echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											// echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADD_PRICE = $ADD_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST + $ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDMVOLM = ADDMVOLM + $ADDM_VOLM, ADDMCOST = $ADDM_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		}
		elseif($AMD_CLASS == 2) 	// YANG DIAMANDEMEN ADALAH HARGA. VOLUME IS DISABLED. PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			// HANYA MERUBAH HARGA, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MENAMBAH VOLUME DAN NILAI ADDENDUM
				$ITM_VOLM	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ITM_VOLM * $ADD_PRICE;

			// CARI SELISIH TOTAL AWAL VS TOTAL AKHIR
				$DELTA_BUDG	= $ITM_BUDGB - $ITM_BUDGA;
			
			// UPDATE PRICE IN MASTER ITEM
				$s_01		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);

			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ADDM_JOBCOST = $DELTA_BUDG, ITM_BUDG = $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				// echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
									ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
									ITM_BUDG = ITM_BUDG + $DELTA_BUDG
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
										ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
										ITM_BUDG = ITM_BUDG + $DELTA_BUDG
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
											ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
											ITM_BUDG = ITM_BUDG + $DELTA_BUDG
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
												ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
												ITM_BUDG = ITM_BUDG + $DELTA_BUDG
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
													ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
													ITM_BUDG = ITM_BUDG + $DELTA_BUDG
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
														ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
														ITM_BUDG = ITM_BUDG + $DELTA_BUDG
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
															ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
															ITM_BUDG = ITM_BUDG + $DELTA_BUDG
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																ITM_BUDG = ITM_BUDG + $DELTA_BUDG
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE, JOBCOST = JOBCOST + $DELTA_BUDG,
																	ADDM_JOBCOST = ADDM_JOBCOST + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
																	ITM_BUDG = ITM_BUDG + $DELTA_BUDG
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
		}
		elseif($AMD_CLASS == 2) // PRICE IS DISABLED. QTY ONLY
		{
			// MERUBAH QTY, MAKA SEHARUSNYA TOTAL PUN BERUBAH
			// TIDAK MERUBAH HARGA
				$ITM_PRICE 	= 1;
				$ITM_BUDGA 	= 0;
				$sqlJL		= "SELECT ITM_PRICE, ITM_BUDG FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_PRICE	= $rowJL->ITM_PRICE;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
				endforeach;
				$ITM_BUDGB	= $ADDM_VOLM * $ITM_PRICE;
			
			// UPDATE JOBLIST
				$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADDM_VOLM, ADDM_JOBCOST = $ITM_BUDGB,
									ITM_BUDG = ITM_BUDG + $ITM_BUDGB
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);
				// echo "DONE 1<br>";
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);

					$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 		= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);

						$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 		= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);

							$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 		= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 		= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 		= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 		= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 		= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 		= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 		= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG + $ITM_BUDGB, ADDM_JOBCOST = ADDM_JOBCOST + $ITM_BUDGB
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADDM_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			
			$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = ADDMVOLM + $ADDM_VOLM,
							ADDMCOST = ADDMCOST + $ITM_BUDGB
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateOTHWBS($paramWBS)
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;

		$JOBPAR 		= explode(".", $JOBCODEID);
		$JOBLEV1 		= $JOBPAR[0];

		// HASIL DISKUSI DENGAN PAK EDI WONG TGL. 6 JUNI 2022 BAHWA UNTUK AMANDEMEN ALOKASI BUDGET HANYA BISA AMANDEMEN NILAI
		// JADI, TIDAK BOLEH ADA PERUBAHAN TERHADAP VOLUME. HARGA AKAN MENYESUAIKAN TOTAL AKHIR DIBAGI VOLUME

		$JOBPAR 	= explode(".", $JOBCODEID);
		$JOBLEV1 	= $JOBPAR[0];

		// 	1. UPDATE JOBLIST
			$s_u1		= "UPDATE tbl_joblist_detail SET AMD_VOL = AMD_VOL + $ADD_VOLM, AMD_VAL = AMD_VAL + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_u2	= "UPDATE tbl_joblist_detail SET AMD_VOL = AMD_VOL + $ADD_VOLM, AMD_VAL = AMD_VAL + $ADD_JOBCOST
						WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
			$this->db->query($s_u2);

			$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST, ADD_PRICE = ADD_JOBCOST / ADD_VOLM
						WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
			$this->db->query($s_u3);

		// 	2. UPDATE ITEM
			$s_JID 		= "SELECT
								IF(SUM(ITM_VOLM) IS NULL, 0, SUM(ITM_VOLM))		AS TITM_VOL,
								IF(SUM(ITM_BUDG) IS NULL, 0, SUM(ITM_BUDG)) 	AS TITM_VAL,
								IF(SUM(AMD_VOL) IS NULL, 0, SUM(AMD_VOL)) 		AS TAMD_VOL,
								IF(SUM(AMD_VAL) IS NULL, 0, SUM(AMD_VAL)) 		AS TAMD_VAL,
								IF(SUM(AMDM_VOL) IS NULL, 0, SUM(AMDM_VOL)) 	AS TAMDM_VOL,
								IF(SUM(AMDM_VAL) IS NULL, 0, SUM(AMDM_VAL)) 	AS TAMDM_VAL,
								IF(SUM(FPA_VOL) IS NULL, 0, SUM(FPA_VOL)) 		AS TFPA_VOL,
								IF(SUM(FPA_VAL) IS NULL, 0, SUM(FPA_VAL)) 		AS TFPA_VAL,
								IF(SUM(FPA_CVOL) IS NULL, 0, SUM(FPA_CVOL)) 	AS TFPA_CVOL,
								IF(SUM(FPA_CVAL) IS NULL, 0, SUM(FPA_CVAL)) 	AS TFPA_CVAL,
								IF(SUM(PR_VOL) IS NULL, 0, SUM(PR_VOL)) 		AS TPR_VOL,
								IF(SUM(PR_VAL) IS NULL, 0, SUM(PR_VAL)) 		AS TPR_VALL,
								IF(SUM(PR_CVOL) IS NULL, 0, SUM(PR_CVOL)) 		AS TPR_CVOL,
								IF(SUM(PR_CVAL) IS NULL, 0, SUM(PR_CVAL)) 		AS TPR_CVAL,
								IF(SUM(PO_VOL) IS NULL, 0, SUM(PO_VOL)) 		AS TPO_VOL,
								IF(SUM(PO_VAL) IS NULL, 0, SUM(PO_VAL)) 		AS TPO_VAL,
								IF(SUM(PO_CVOL) IS NULL, 0, SUM(PO_CVOL)) 		AS TPO_CVOL,
								IF(SUM(PO_CVAL) IS NULL, 0, SUM(PO_CVAL)) 		AS TPO_CVAL,
								IF(SUM(IR_VOL) IS NULL, 0, SUM(IR_VOL)) 		AS TIR_VOL,
								IF(SUM(IR_VAL) IS NULL, 0, SUM(IR_VAL)) 		AS TIR_VAL,
								IF(SUM(UM_VOL) IS NULL, 0, SUM(UM_VOL)) 		AS TUM_VOL,
								IF(SUM(UM_VAL) IS NULL, 0, SUM(UM_VAL)) 		AS TUM_VAL,
								IF(SUM(WO_VOL) IS NULL, 0, SUM(WO_VOL)) 		AS TWO_VOL,
								IF(SUM(WO_VAL) IS NULL, 0, SUM(WO_VAL)) 		AS TWO_VAL,
								IF(SUM(WO_CVOL) IS NULL, 0, SUM(WO_CVOL)) 		AS TWO_CVOL,
								IF(SUM(WO_CVAL) IS NULL, 0, SUM(WO_CVAL)) 		AS TWO_CVAL,
								IF(SUM(OPN_VOL) IS NULL, 0, SUM(OPN_VOL)) 		AS TOPN_VOL,
								IF(SUM(OPN_VAL) IS NULL, 0, SUM(OPN_VAL)) 		AS TOPN_VAL,
								IF(SUM(VCASH_VOL) IS NULL, 0, SUM(VCASH_VOL)) 	AS TVCASH_VOL,
								IF(SUM(VCASH_VAL) IS NULL, 0, SUM(VCASH_VAL)) 	AS TVCASH_VAL,
								IF(SUM(VLK_VOL) IS NULL, 0, SUM(VLK_VOL)) 		AS TVLK_VOL,
								IF(SUM(VLK_VAL) IS NULL, 0, SUM(VLK_VAL)) 		AS TVLK_VAL,
								IF(SUM(PPD_VOL) IS NULL, 0, SUM(PPD_VOL)) 		AS TPPD_VOL,
								IF(SUM(PPD_VAL) IS NULL, 0, SUM(PPD_VAL)) 		AS TPPD_VAL
							FROM tbl_joblist_detail_$PRJCODEVW
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' GROUP BY ITM_CODE";
			$r_JID		= $this->db->query($s_JID)->result();
			foreach($r_JID as $rw_JID):
				$ITM_VOLM 	= $rw_JID->TITM_VOL;
				$ITM_BUDG 	= $rw_JID->TITM_VAL;
				$AMD_VOL 	= $rw_JID->TAMD_VOL;
				$AMD_VAL 	= $rw_JID->TAMD_VAL;
				$AMDM_VOL 	= $rw_JID->TAMDM_VOL;
				$AMDM_VAL 	= $rw_JID->TAMDM_VAL;
				$FPA_VOL 	= $rw_JID->TFPA_VOL;
				$FPA_VAL 	= $rw_JID->TFPA_VAL;
				$FPA_CVOL 	= $rw_JID->TFPA_CVOL;
				$FPA_CVAL 	= $rw_JID->TFPA_CVAL;
				$PR_VOL 	= $rw_JID->TPR_VOL;
				$PR_VAL 	= $rw_JID->TPR_VALL;
				$PR_CVOL 	= $rw_JID->TPR_CVOL;
				$PR_CVAL 	= $rw_JID->TPR_CVAL;
				$PO_VOL 	= $rw_JID->TPO_VOL;
				$PO_VAL 	= $rw_JID->TPO_VAL;
				$PO_CVOL 	= $rw_JID->TPO_CVOL;
				$PO_CVAL 	= $rw_JID->TPO_CVAL;
				$IR_VOL 	= $rw_JID->TIR_VOL;
				$IR_VAL 	= $rw_JID->TIR_VAL;
				$UM_VOL 	= $rw_JID->TUM_VOL;
				$UM_VAL 	= $rw_JID->TUM_VAL;
				$WO_VOL 	= $rw_JID->TWO_VOL;
				$WO_VAL 	= $rw_JID->TWO_VAL;
				$WO_CVOL 	= $rw_JID->TWO_CVOL;
				$WO_CVAL 	= $rw_JID->TWO_CVAL;
				$OPN_VOL 	= $rw_JID->TOPN_VOL;
				$OPN_VAL 	= $rw_JID->TOPN_VAL;
				$VCASH_VOL 	= $rw_JID->TVCASH_VOL;
				$VCASH_VAL 	= $rw_JID->TVCASH_VAL;
				$VLK_VOL 	= $rw_JID->TVLK_VOL;
				$VLK_VAL	= $rw_JID->TVLK_VAL;
				$PPD_VOL	= $rw_JID->TPPD_VOL;
				$PPD_VAL 	= $rw_JID->TPPD_VAL;

				$s_01		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLM,	ITM_IN = $IR_VOL, FPA_VOL = $FPA_VOL, FPA_VAL = $FPA_VAL, FPA_CVOL = $FPA_CVOL, FPA_CVAL = $FPA_CVAL,
									 PR_VOL = $PR_VOL, PR_VAL = $PR_VAL, PO_VOL = $PO_VOL, PO_VAL = $PO_VAL, IR_VOL = $IR_VOL, IR_VAL = $IR_VAL,
									 UM_VOL = $UM_VOL, UM_VAL = $UM_VAL, WO_VOL = $WO_VOL, WO_VAL = $WO_VAL, OPN_VOL = $OPN_VOL, OPN_VAL = $OPN_VAL,
									 VCASH_VOL = $VCASH_VOL, VCASH_VAL = $VCASH_VAL, VLK_VOL = $VLK_VOL, VLK_VAL = $VLK_VAL, PPD_VOL = $PPD_VOL, PPD_VAL = $PPD_VAL,
									 AMD_VOL = $AMD_VOL, AMD_VAL = $AMD_VAL, AMDM_VOL = $AMDM_VOL, AMDM_VAL = $AMDM_VAL
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);
			endforeach;
	}
	
	function updateWBSMINUS($paramWBS) 			// PENGURANG ITEM YANG DIJADIKAN PENGGANTI UNTUK BUDGET LAIN
	{
		$ADDM_VOLM 		= $paramWBS['ADD_VOLM'];			// VOLUME DARI SISA YANG ADA
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADDM_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$AMD_TOTTSF		= $paramWBS['AMD_TOTTSF'];			// NILAI TRANSFER
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADDM_VOLM == '')
			$ADDM_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADDM_JOBCOST == '')
			$ADDM_JOBCOST	= 0;

		/* 	PENGURANGAN INI SEBETULNYA HANYA MELAKUKAN PERUBAHAN HARGA, KARENA YANG DIKURANGI ADALAH NILAINYA, SEHINGGA VOLUME AKAN TETAP
			DIAMBIL DARI SISA YANG SUDAH DIGUNAKAN.
			PERLAKUKANNYA AKAN DISAMAKAN DENGAN MELAKUKAN AMANDEMEN VOLUME DAN HARGA, KARENA ADANYA PERUBAHAN HARGA DARI SISA VOLUME YANG ADA.
		*/

		/* 	CATATAN HANYA AMANDEMEN HARGA
			1. Hanya melakukan perubahan harga
			2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
			3. Pisahkan Volume dan Total yang sudah digunakan
			4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

							VOL. 		HRG.		TOTAL
				Budget 		100			1,000		100,000
				Digunakan 	 50			1,000		 50,000		Digunakan
				Sisa 		 50 		1,000 		 50,000		Sisa
				Amandemen 	 50 		1,200 		 60,000		Amandemen
				Total 		 50 		1,200 		110,000 	(Sisa + Amandemen)
		*/

		// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
			$ITM_VOLM	= 0;
			$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
			$REQ_VOLM	= 0;
			$REQ_AMOUNT = 0;
			$BUD_REMV	= 0;
			$BUD_REMAMN = 0;
			$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_VOLM	= $rowJL->ITM_VOLM;
				$ITM_BUDGA	= $rowJL->ITM_BUDG;
				$REQ_VOLM	= $rowJL->REQ_VOLM;
				$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
				$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
				$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
			endforeach;

		// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG DITRANSFER KE BUDGET LAIN
			$BUD_USEV 	= $REQ_VOLM;					// VOLUME TIDAK TERMASUK KE DALAM KOEFISIEN PENGURANG, KARENA NILAINYA SAMA
			$BUD_USEAMN = $REQ_AMOUNT;

			// NILAI AWAL
				$ITM_VOLM	= $BUD_USEV;
				$ITM_BUDG 	= $BUD_USEAMN;
				$ITM_VOLMP 	= $ITM_VOLM;
				$BUD_USEVP 	= $BUD_USEV;
				if($BUD_USEV == 0)
					$BUD_USEVP 	= 1;
				$ITM_PRICE	= $ITM_BUDG / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIKURANGI NILAI TRANSFER

			// NILAI ADDENDUM
				$ADD_VOLM		= $BUD_REMV;				// DIANGGAP SEBAGAI AMANDEMEN VOLUME

				// HARGA RATA-RATA DARI SISA SETELAH DIKURANGI NILAI YANG DITRANSFER
				$BUD_USEAMN2	= $BUD_REMAMN - $AMD_TOTTSF;
				$ADD_VOLMP 		= $ADD_VOLM;
				if($ADD_VOLM == 0)
					$ADD_VOLMP 	= 1;
				$ADD_PRICE 		= $BUD_USEAMN2 / $ADD_VOLMP;
				$ADD_JOBCOST	= $ADD_VOLM * $ADD_PRICE;	// DIANGGAP SEBAGAI TOTAL AMANDEMEN

		// 	3. UPDATE JOBLIST
			$ITM_BUDGB 	= $BUD_USEAMN2 + $ADD_JOBCOST;		// BUDGET YANG SUDAH DIGUNAKAN + SISA BUDGET SETELAH DIKALI HARGA AMANDEMEN

			$s_u1		= "UPDATE tbl_joblist_detail SET 
								ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
								ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 		= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
								ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);
				$s_u2b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
								ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);
				// echo "DONE 2<br>";
				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3a);
					$s_u3b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					// echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
										ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4a);
						$s_u4b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
										ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						// echo "DONE 4<br>";
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
											ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5a);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
											ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							// echo "DONE 5<br>";
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
												ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6a);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
												ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								// echo "DONE 6<br>";
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
													ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7a);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
													ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									// echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
														ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8a);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
														ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										// echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
															ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9a);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
															ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											// echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10a	= "UPDATE tbl_joblist SET JOBCOST = JOBCOST - $ITM_BUDGA + $ITM_BUDGB,
																	ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10a);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ITM_BUDG = ITM_BUDG - $ITM_BUDGA + $ITM_BUDGB,
																ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

		// 	4. UPDATE PRICE IN MASTER ITEM
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$PR_VOLM	= $rowITM->PR_VOLM;
				$PR_AMOUNT	= $rowITM->PR_AMOUNT;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
			
			/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							ITM_VOLMBGR = $ITM_VOLMBGR
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
	}
	
	function updateWBSMINUS_NEW($paramWBS) 			// PENGURANG ITEM YANG DIJADIKAN PENGGANTI UNTUK BUDGET LAIN
	{
		$AMD_TOTTSF		= $paramWBS['AMD_TOTTSF'];			// NILAI TRANSFER
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];

		// 	1. BUDGET
			$ITM_VOLM	= 0;
			$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
			$REQ_VOLM	= 0;
			$REQ_AMOUNT = 0;
			$BUD_REMV	= 0;
			$BUD_REMAMN = 0;
			$ITM_AVGP 	= 0;
			$ITM_AVGPP 	= 1;
			$sqlJL		= "SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, REQ_VOLM, REQ_AMOUNT
								FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_VOLM		= $rowJL->ITM_VOLM;
				$ITM_PRICE		= $rowJL->ITM_PRICE;
				$ITM_LASTP		= $rowJL->ITM_LASTP;
				$ITM_BUDG		= $rowJL->ITM_BUDG;
				$ADD_VOLM		= $rowJL->ADD_VOLM;
				$ADD_PRICE		= $rowJL->ADD_PRICE;
				$ADD_JOBCOST	= $rowJL->ADD_JOBCOST;
				$ADDM_VOLM		= $rowJL->ADDM_VOLM;
				$ADDM_JOBCOST	= $rowJL->ADDM_JOBCOST;
				$REQ_VOLM		= $rowJL->REQ_VOLM;
				$REQ_AMOUNT		= $rowJL->REQ_AMOUNT;

				$TOT_REMVOL 	= ($ITM_VOLM+$ADD_VOLM) - ($ADDM_VOLM+$REQ_VOLM);
				$TOT_REMVAL 	= ($ITM_BUDG+$ADD_JOBCOST) - ($ADDM_JOBCOST+$REQ_AMOUNT);

				$TOT_REMVOLP 	= $TOT_REMVOL;
				if($TOT_REMVOL == 0 || $TOT_REMVOL == '')
					$TOT_REMVOLP= 1;
				$ITM_AVGP 		= $TOT_REMVAL / $TOT_REMVOLP;
				$ITM_AVGPP 		= $ITM_AVGP;
				if($ITM_AVGP == 0 || $ITM_AVGP == '')
					$ITM_AVGPP 	= 1;
			endforeach;

		// 2. MENENTUKAN NILAI VOLUME DARI NILAI YANG DITRANSFER
			$ADDM_VOLMNEW		= $AMD_TOTTSF / $ITM_AVGPP;

		// 	3. UPDATE JOBLIST
			$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
			$r_P2 		= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);
				$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);
				// echo "DONE 2<br>";
				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3a);
					$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);
					// echo "DONE 3<br>";
					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4a);
						$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
						// echo "DONE 4<br>";
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5a);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
							// echo "DONE 5<br>";
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6a);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
								// echo "DONE 6<br>";
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7a);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);
									// echo "DONE 7<br>";
									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8a);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);
										// echo "DONE 8<br>";
										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9a);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);
											// echo "DONE 9<br>";
											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10a);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$ADDM_VOLMNEW, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

		// 	4. UPDATE PRICE IN MASTER ITEM
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$PR_VOLM	= $rowITM->PR_VOLM;
				$PR_AMOUNT	= $rowITM->PR_AMOUNT;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
			
			/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							ITM_VOLMBGR = $ITM_VOLMBGR
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
	}
	
	function updateWBSMINUS_OTH_220603($paramWBS) 			// PENGURANG ITEM YANG DIJADIKAN PENGGANTI UNTUK BUDGET LAIN
	{
		$AMD_VOLM		= $paramWBS['ADD_VOLM'];
		$AMD_TOTTSF		= $paramWBS['AMD_TOTTSF'];			// NILAI TRANSFER
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];

		// 	1. BUDGET
			$ITM_VOLM	= 0;
			$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
			$REQ_VOLM	= 0;
			$REQ_AMOUNT = 0;
			$BUD_REMV	= 0;
			$BUD_REMAMN = 0;
			$ITM_AVGP 	= 0;
			$ITM_AVGPP 	= 1;
			$sqlJL		= "SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST,
								REQ_VOLM, REQ_AMOUNT
							FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_VOLM		= $rowJL->ITM_VOLM;
				$ITM_PRICE		= $rowJL->ITM_PRICE;
				$ITM_LASTP		= $rowJL->ITM_LASTP;
				$ITM_BUDG		= $rowJL->ITM_BUDG;
				$ADD_VOLM		= $rowJL->ADD_VOLM;
				$ADD_PRICE		= $rowJL->ADD_PRICE;
				$ADD_JOBCOST	= $rowJL->ADD_JOBCOST;
				$ADDM_VOLM		= $rowJL->ADDM_VOLM;
				$ADDM_JOBCOST	= $rowJL->ADDM_JOBCOST;
				$REQ_VOLM		= $rowJL->REQ_VOLM;
				$REQ_AMOUNT		= $rowJL->REQ_AMOUNT;

				$TOT_REMVOL 	= ($ITM_VOLM+$ADD_VOLM) - ($ADDM_VOLM+$REQ_VOLM);
				$TOT_REMVAL 	= ($ITM_BUDG+$ADD_JOBCOST) - ($ADDM_JOBCOST+$REQ_AMOUNT);

				$TOT_REMVOLP 	= $TOT_REMVOL;
				if($TOT_REMVOL == 0 || $TOT_REMVOL == '')
					$TOT_REMVOLP= 1;
				$ITM_AVGP 		= $TOT_REMVAL / $TOT_REMVOLP;
				$ITM_AVGPP 		= $ITM_AVGP;
				if($ITM_AVGP == 0 || $ITM_AVGP == '')
					$ITM_AVGPP 	= 1;
			endforeach;

		// 2. MENENTUKAN NILAI VOLUME DARI NILAI YANG DITRANSFER
			$ADDM_VOLMNEW		= $AMD_TOTTSF / $ITM_AVGPP;

			if($AMD_CLASS == 1) 		// YANG AKAN DIAMANDEMEN : VOLUME
			{
				/*
					1. TOTAL TRANSFER DIBAGI DENGAN HARGA
					2. VOLUME RAP DIKURANGI VOLUME TRANSFER (HASIL POINT 1)
				*/
				$MIN_VOL 	= $AMD_TOTTSF / $ITM_AVGP;
				$VOL_NEW 	= $TOT_REMVOL - $MIN_VOL;

				// 	START : UPDATE JOBLIST
					$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($s_u1);

					$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_P2 		= $this->db->query($s_P2)->result();
					foreach($r_P2 as $rw_P2) :
						$JP2 	= $rw_P2->JOBPARENT;
						$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
									WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u2a);
						$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
									WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u2b);
						// echo "DONE 2<br>";
						$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
						$r_P3 	= $this->db->query($s_P3)->result();
						foreach($r_P3 as $rw_P3) :
							$JP3 	= $rw_P3->JOBPARENT;
							$s_u3a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
										WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u3a);
							$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
										WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u3b);
							// echo "DONE 3<br>";
							$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
							$r_P4 	= $this->db->query($s_P4)->result();
							foreach($r_P4 as $rw_P4) :
								$JP4 	= $rw_P4->JOBPARENT;
								$s_u4a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
											WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u4a);
								$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
											WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u4b);
								// echo "DONE 4<br>";
								$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
								$r_P5 	= $this->db->query($s_P5)->result();
								foreach($r_P5 as $rw_P5) :
									$JP5 	= $rw_P5->JOBPARENT;
									$s_u5a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
												WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u5a);

									$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
												WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u5b);
									// echo "DONE 5<br>";
									$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
									$r_P6 	= $this->db->query($s_P6)->result();
									foreach($r_P6 as $rw_P6) :
										$JP6 	= $rw_P6->JOBPARENT;
										$s_u6a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
													WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u6a);

										$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
													WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u6b);
										// echo "DONE 6<br>";
										$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
										$r_P7 	= $this->db->query($s_P7)->result();
										foreach($r_P7 as $rw_P7) :
											$JP7 	= $rw_P7->JOBPARENT;
											$s_u7a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
														WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u7a);

											$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
														WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u7b);
											// echo "DONE 7<br>";
											$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
											$r_P8 	= $this->db->query($s_P8)->result();
											foreach($r_P8 as $rw_P8) :
												$JP8 	= $rw_P8->JOBPARENT;
												$s_u8a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
															WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u8a);

												$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
															WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u8b);
												// echo "DONE 8<br>";
												$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
												$r_P9 	= $this->db->query($s_P9)->result();
												foreach($r_P9 as $rw_P9) :
													$JP9 	= $rw_P9->JOBPARENT;
													$s_u9a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
																WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u9a);

													$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
																WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u9b);
													// echo "DONE 9<br>";
													$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
													$r_P10 	= $this->db->query($s_P10)->result();
													foreach($r_P10 as $rw_P10) :
														$JP10 	= $rw_P10->JOBPARENT;
														$s_u10a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
																	WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
														$this->db->query($s_u10a);

														$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF
																	WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
														$this->db->query($s_u10b);
														// echo "DONE 10<br>";
													endforeach;
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				// 	END : UPDATE JOBLIST
			}
			elseif($AMD_CLASS == 2)
			{
				/*
					1. TOTAL SISA BUDGET DIKURANGI DENGAN TOTAL TRANSFER
					2. SISA BUDGET SETELAH DITRANSFER DIBAGI SISA VOLUME
				*/
				$NEW_VAL 	= $TOT_REMVAL - $AMD_TOTTSF;
				
				$TOT_REMVOLP 	= $TOT_REMVOL;
				if($TOT_REMVOLP == 0)
					$TOT_REMVOLP 	= 1;
					
				$NEW_PRICE 	= $NEW_VAL / $TOT_REMVOLP;
				$MIN_VOL 	= 0; 							// KARENA YANG BERUBAH HANYA HARGA
				
				// 	START : UPDATE JOBLIST
					$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
										ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
									WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($s_u1);

					$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
					$r_P2 		= $this->db->query($s_P2)->result();
					foreach($r_P2 as $rw_P2) :
						$JP2 	= $rw_P2->JOBPARENT;
						$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
										PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
									WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u2a);
						$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
										ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
									WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u2b);

						$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
						$r_P3 	= $this->db->query($s_P3)->result();
						foreach($r_P3 as $rw_P3) :
							$JP3 	= $rw_P3->JOBPARENT;
							$s_u3a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
											PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
										WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u3a);
							$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
											ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
										WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u3b);

							$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
							$r_P4 	= $this->db->query($s_P4)->result();
							foreach($r_P4 as $rw_P4) :
								$JP4 	= $rw_P4->JOBPARENT;
								$s_u4a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
												PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
											WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u4a);
								$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
												ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
											WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u4b);

								$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
								$r_P5 	= $this->db->query($s_P5)->result();
								foreach($r_P5 as $rw_P5) :
									$JP5 	= $rw_P5->JOBPARENT;
									$s_u5a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
													PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
												WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u5a);
									$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
													ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
												WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u5b);

									$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
									$r_P6 	= $this->db->query($s_P6)->result();
									foreach($r_P6 as $rw_P6) :
										$JP6 	= $rw_P6->JOBPARENT;
										$s_u6a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL, ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF,
														PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
													WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u6a);
										$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
														ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,
														ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
													WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u6b);

										$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
										$r_P7 	= $this->db->query($s_P7)->result();
										foreach($r_P7 as $rw_P7) :
											$JP7 	= $rw_P7->JOBPARENT;
											$s_u7a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
															ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
														WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u7a);
											$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
															ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, ITM_PRICE = $NEW_PRICE, ITM_LASTP = $NEW_PRICE,
															ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
														WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u7b);

											$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
											$r_P8 	= $this->db->query($s_P8)->result();
											foreach($r_P8 as $rw_P8) :
												$JP8 	= $rw_P8->JOBPARENT;
												$s_u8a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
																ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
															WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u8a);
												$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
																ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, ITM_PRICE = $NEW_PRICE,
																ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
															WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u8b);

												$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
												$r_P9 	= $this->db->query($s_P9)->result();
												foreach($r_P9 as $rw_P9) :
													$JP9 	= $rw_P9->JOBPARENT;
													$s_u9a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
																	ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, PRICE = $NEW_PRICE, JOBCOST = $NEW_VAL
																WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u9a);
													$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
																	ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, ITM_PRICE = $NEW_PRICE,
																	ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
																WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u9b);

													$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
													$r_P10 	= $this->db->query($s_P10)->result();
													foreach($r_P10 as $rw_P10) :
														$JP10 	= $rw_P10->JOBPARENT;
														$s_u10a	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
																		ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, PRICE = $NEW_PRICE,
																		JOBCOST = $NEW_VAL
																	WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
														$this->db->query($s_u10a);
														$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM+$MIN_VOL,
																		ADDM_JOBCOST = ADDM_JOBCOST+$AMD_TOTTSF, ITM_PRICE = $NEW_PRICE,
																		ITM_LASTP = $NEW_PRICE,	ITM_AVGP = $NEW_PRICE, ITM_BUDG = $NEW_VAL
																	WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
														$this->db->query($s_u10b);
													endforeach;
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				// 	END : UPDATE JOBLIST
			}

		// 	4. UPDATE PRICE IN MASTER ITEM
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
		
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$PR_VOLM	= $rowITM->PR_VOLM;
				$PR_AMOUNT	= $rowITM->PR_AMOUNT;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
			
			/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							ITM_VOLMBGR = $ITM_VOLMBGR
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
	}
	
	function updateWBSMINUS_OTH_230219($paramWBS) 			// PENGURANG ITEM YANG DIJADIKAN PENGGANTI UNTUK BUDGET LAIN
	{
		$AMD_VOLM		= $paramWBS['ADD_VOLM'];
		$AMD_TOTTSF		= $paramWBS['AMD_TOTTSF'];			// NILAI TRANSFER
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($AMD_VOLM == '')
			$AMD_VOLM	= 0;
		if($AMD_TOTTSF == '')
			$AMD_TOTTSF	= 0;

		$ADDM_JOBCOST 	= $AMD_TOTTSF;

		// HASIL DISKUSI DENGAN PAK EDI WONG TGL. 6 JUNI 2022 BAHWA UNTUK AMANDEMEN ALOKASI BUDGET HANYA BISA AMANDEMEN NILAI
		// JADI, TIDAK BOLEH ADA PERUBAHAN TERHADAP VOLUME. HARGA AKAN MENYESUAIKAN TOTAL AKHIR DIBAGI VOLUME

		// 	1. MENDAPATKAN SISA BUDGET
			$ITM_UNIT 	= 'LS';
			$ITM_VOLM	= 1;
			$ITM_REMVAL	= 0;
			$sqlJL		= "SELECT ITM_UNIT, IF(ITM_UNIT = 'LS', 1, (ITM_VOLM + ADD_VOLM - REQ_VOLM - ADDM_VOLM)) AS ITM_REMV,
								(ITM_BUDG + ADD_JOBCOST - REQ_AMOUNT - ADDM_JOBCOST) AS ITM_REMVAL
							FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_UNIT	= strtoupper($rowJL->ITM_UNIT);
				$ITM_VOLM	= $rowJL->ITM_REMV;
				$ITM_REMVAL	= $rowJL->ITM_REMVAL;
			endforeach;
			if($ITM_UNIT == 'LS')
			{
				$ITM_VOLM 	= 1;
				$ITM_VOLMP 	= 1;
			}
			else
			{
				$ITM_VOLMP 	= $ITM_VOLM;
				if($ITM_VOLM == '' || $ITM_VOLM == 0)
					$ITM_VOLMP 	= 1;
			}

		// 	2. TOTAL BUDGET SETELAH AMANDEMEN
			$NEW_BUDG	= $ITM_REMVAL - $ADDM_JOBCOST;

		// 	3. HARGA SETELAH AMANDEMEN
			$NEW_PRICE	= $NEW_BUDG / $ITM_VOLMP;
		
		// 	4. UPDATE MASTER ITEM
			$s_01		= "UPDATE tbl_item SET ADDMVOLM = IF(ADDMVOLM=0, 1, ADDMVOLM), ADDMCOST = ADDMCOST + $NEW_BUDG
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_01);
		
		// 	5. START : UPDATE JOBLIST
			$s_u1		= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' LIMIT 1";
			$r_P2 		= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);
				$s_u2b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
								ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3a);
					$s_u3b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
									ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4a);
						$s_u4b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
										ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);

						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5a);
							$s_u5b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
											ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);

							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6a);
								$s_u6b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
												ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);

								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7a);
									$s_u7b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
													ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8a);
										$s_u8b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
														ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9a);
											$s_u9b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
															ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10a	= "UPDATE tbl_joblist SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10a);
												$s_u10b	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = IF(ADDM_VOLM=0, 1, ADDM_VOLM), ITM_LASTP = $NEW_PRICE,
																ADDM_JOBCOST = ADDM_JOBCOST+$ADDM_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;
		// 	END : UPDATE JOBLIST
	}
	
	function updateWBSMINUS_OTH($paramWBS) 			// PENGURANG ITEM YANG DIJADIKAN PENGGANTI UNTUK BUDGET LAIN
	{
		$ADD_VOLM		= $paramWBS['ADD_VOLM'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];			// NILAI TRANSFER
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];

		$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;

		$JOBPAR 		= explode(".", $JOBCODEID);
		$JOBLEV1 		= $JOBPAR[0];

		// 	1. UPDATE JOBLIST 	
			$s_u1		= "UPDATE tbl_joblist_detail SET AMDM_VOL = AMDM_VOL + $ADD_VOLM, AMDM_VAL = AMDM_VAL + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_u2	= "UPDATE tbl_joblist_detail SET AMDM_VOL = AMDM_VOL + $ADD_VOLM, AMDM_VAL = AMDM_VAL + $ADD_JOBCOST
						WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
			$this->db->query($s_u2);

			$s_u3	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM, ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID LIKE '$JOBLEV1%' AND PRJCODE = '$PRJCODE' AND ISLAST = 0";
			$this->db->query($s_u3);

		// 	2. UPDATE ITEM
			$s_JID 		= "SELECT
								IF(SUM(ITM_VOLM) IS NULL, 0, SUM(ITM_VOLM))		AS TITM_VOL,
								IF(SUM(ITM_BUDG) IS NULL, 0, SUM(ITM_BUDG)) 	AS TITM_VAL,
								IF(SUM(AMD_VOL) IS NULL, 0, SUM(AMD_VOL)) 		AS TAMD_VOL,
								IF(SUM(AMD_VAL) IS NULL, 0, SUM(AMD_VAL)) 		AS TAMD_VAL,
								IF(SUM(AMDM_VOL) IS NULL, 0, SUM(AMDM_VOL)) 	AS TAMDM_VOL,
								IF(SUM(AMDM_VAL) IS NULL, 0, SUM(AMDM_VAL)) 	AS TAMDM_VAL,
								IF(SUM(FPA_VOL) IS NULL, 0, SUM(FPA_VOL)) 		AS TFPA_VOL,
								IF(SUM(FPA_VAL) IS NULL, 0, SUM(FPA_VAL)) 		AS TFPA_VAL,
								IF(SUM(FPA_CVOL) IS NULL, 0, SUM(FPA_CVOL)) 	AS TFPA_CVOL,
								IF(SUM(FPA_CVAL) IS NULL, 0, SUM(FPA_CVAL)) 	AS TFPA_CVAL,
								IF(SUM(PR_VOL) IS NULL, 0, SUM(PR_VOL)) 		AS TPR_VOL,
								IF(SUM(PR_VAL) IS NULL, 0, SUM(PR_VAL)) 		AS TPR_VALL,
								IF(SUM(PR_CVOL) IS NULL, 0, SUM(PR_CVOL)) 		AS TPR_CVOL,
								IF(SUM(PR_CVAL) IS NULL, 0, SUM(PR_CVAL)) 		AS TPR_CVAL,
								IF(SUM(PO_VOL) IS NULL, 0, SUM(PO_VOL)) 		AS TPO_VOL,
								IF(SUM(PO_VAL) IS NULL, 0, SUM(PO_VAL)) 		AS TPO_VAL,
								IF(SUM(PO_CVOL) IS NULL, 0, SUM(PO_CVOL)) 		AS TPO_CVOL,
								IF(SUM(PO_CVAL) IS NULL, 0, SUM(PO_CVAL)) 		AS TPO_CVAL,
								IF(SUM(IR_VOL) IS NULL, 0, SUM(IR_VOL)) 		AS TIR_VOL,
								IF(SUM(IR_VAL) IS NULL, 0, SUM(IR_VAL)) 		AS TIR_VAL,
								IF(SUM(UM_VOL) IS NULL, 0, SUM(UM_VOL)) 		AS TUM_VOL,
								IF(SUM(UM_VAL) IS NULL, 0, SUM(UM_VAL)) 		AS TUM_VAL,
								IF(SUM(WO_VOL) IS NULL, 0, SUM(WO_VOL)) 		AS TWO_VOL,
								IF(SUM(WO_VAL) IS NULL, 0, SUM(WO_VAL)) 		AS TWO_VAL,
								IF(SUM(WO_CVOL) IS NULL, 0, SUM(WO_CVOL)) 		AS TWO_CVOL,
								IF(SUM(WO_CVAL) IS NULL, 0, SUM(WO_CVAL)) 		AS TWO_CVAL,
								IF(SUM(OPN_VOL) IS NULL, 0, SUM(OPN_VOL)) 		AS TOPN_VOL,
								IF(SUM(OPN_VAL) IS NULL, 0, SUM(OPN_VAL)) 		AS TOPN_VAL,
								IF(SUM(VCASH_VOL) IS NULL, 0, SUM(VCASH_VOL)) 	AS TVCASH_VOL,
								IF(SUM(VCASH_VAL) IS NULL, 0, SUM(VCASH_VAL)) 	AS TVCASH_VAL,
								IF(SUM(VLK_VOL) IS NULL, 0, SUM(VLK_VOL)) 		AS TVLK_VOL,
								IF(SUM(VLK_VAL) IS NULL, 0, SUM(VLK_VAL)) 		AS TVLK_VAL,
								IF(SUM(PPD_VOL) IS NULL, 0, SUM(PPD_VOL)) 		AS TPPD_VOL,
								IF(SUM(PPD_VAL) IS NULL, 0, SUM(PPD_VAL)) 		AS TPPD_VAL
							FROM tbl_joblist_detail_$PRJCODEVW
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' GROUP BY ITM_CODE";
			$r_JID		= $this->db->query($s_JID)->result();
			foreach($r_JID as $rw_JID):
				$ITM_VOLM 	= $rw_JID->TITM_VOL;
				$ITM_BUDG 	= $rw_JID->TITM_VAL;
				$AMD_VOL 	= $rw_JID->TAMD_VOL;
				$AMD_VAL 	= $rw_JID->TAMD_VAL;
				$AMDM_VOL 	= $rw_JID->TAMDM_VOL;
				$AMDM_VAL 	= $rw_JID->TAMDM_VAL;
				$FPA_VOL 	= $rw_JID->TFPA_VOL;
				$FPA_VAL 	= $rw_JID->TFPA_VAL;
				$FPA_CVOL 	= $rw_JID->TFPA_CVOL;
				$FPA_CVAL 	= $rw_JID->TFPA_CVAL;
				$PR_VOL 	= $rw_JID->TPR_VOL;
				$PR_VAL 	= $rw_JID->TPR_VALL;
				$PR_CVOL 	= $rw_JID->TPR_CVOL;
				$PR_CVAL 	= $rw_JID->TPR_CVAL;
				$PO_VOL 	= $rw_JID->TPO_VOL;
				$PO_VAL 	= $rw_JID->TPO_VAL;
				$PO_CVOL 	= $rw_JID->TPO_CVOL;
				$PO_CVAL 	= $rw_JID->TPO_CVAL;
				$IR_VOL 	= $rw_JID->TIR_VOL;
				$IR_VAL 	= $rw_JID->TIR_VAL;
				$UM_VOL 	= $rw_JID->TUM_VOL;
				$UM_VAL 	= $rw_JID->TUM_VAL;
				$WO_VOL 	= $rw_JID->TWO_VOL;
				$WO_VAL 	= $rw_JID->TWO_VAL;
				$WO_CVOL 	= $rw_JID->TWO_CVOL;
				$WO_CVAL 	= $rw_JID->TWO_CVAL;
				$OPN_VOL 	= $rw_JID->TOPN_VOL;
				$OPN_VAL 	= $rw_JID->TOPN_VAL;
				$VCASH_VOL 	= $rw_JID->TVCASH_VOL;
				$VCASH_VAL 	= $rw_JID->TVCASH_VAL;
				$VLK_VOL 	= $rw_JID->TVLK_VOL;
				$VLK_VAL	= $rw_JID->TVLK_VAL;
				$PPD_VOL	= $rw_JID->TPPD_VOL;
				$PPD_VAL 	= $rw_JID->TPPD_VAL;

				$s_01		= "UPDATE tbl_item SET ITM_VOLMBG = $ITM_VOLM,	ITM_IN = $IR_VOL, FPA_VOL = $FPA_VOL, FPA_VAL = $FPA_VAL, FPA_CVOL = $FPA_CVOL, FPA_CVAL = $FPA_CVAL,
									 PR_VOL = $PR_VOL, PR_VAL = $PR_VAL, PO_VOL = $PO_VOL, PO_VAL = $PO_VAL, IR_VOL = $IR_VOL, IR_VAL = $IR_VAL,
									 UM_VOL = $UM_VOL, UM_VAL = $UM_VAL, WO_VOL = $WO_VOL, WO_VAL = $WO_VAL, OPN_VOL = $OPN_VOL, OPN_VAL = $OPN_VAL,
									 VCASH_VOL = $VCASH_VOL, VCASH_VAL = $VCASH_VAL, VLK_VOL = $VLK_VOL, VLK_VAL = $VLK_VAL, PPD_VOL = $PPD_VOL, PPD_VAL = $PPD_VAL,
									 AMD_VOL = $AMD_VOL, AMD_VAL = $AMD_VAL, AMDM_VOL = $AMDM_VOL, AMDM_VAL = $AMDM_VAL
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_01);
			endforeach;
	}
	
	function updateWBSM_211229($paramWBS) 	// OK - SI MINUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' c";
		$this->db->query($sql);*/
		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$/*sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);*/
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
							ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = $ADDVOLM2
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql6	= "UPDATE tbl_joblist SET ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADDM_JOBCOST = ADDM_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM + $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDMVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDMVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDMVOLM	= $rowITM->ADDMVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDMVOLM2		= $ADDMVOLM + $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateWBSDM($paramWBS) // OK
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		$sql	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE, 
						ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);
	}
	
	function updateSIH($paramSIH) // G
	{
		$AMD_NUM 		= $paramSIH['SI_AMANDNO'];
		$SI_AMANDVAL 	= $paramSIH['SI_AMANDVAL'];
		$SI_AMANDSTAT	= $paramSIH['SI_AMANDSTAT'];
		$SI_CODE		= $paramSIH['SI_CODE'];
		$PRJCODE		= $paramSIH['PRJCODE'];
		
		if($SI_AMANDVAL == '')
			$SI_AMANDVAL	= 0;
			
		$sqlUPDSI	= "UPDATE tbl_siheader SET SI_AMAND = 1, SI_AMDCREATED = 1, SI_AMANDNO = '$AMD_NUM', 
							SI_AMANDVAL = '$SI_AMANDVAL', SI_AMANDSTAT = $SI_AMANDSTAT
						WHERE SI_CODE = '$SI_CODE' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUPDSI);
			
		// UPDATE BERDASARKAN TOTAL AMD KE TOTAL SI
			$TOT_AMD		= 0;
			$SI_APPVAL		= 0;
			$sqlTOTAMD		= "SELECT SUM(A.AMD_AMOUNT) TOT_AMD, B.SI_APPVAL FROM tbl_amd_header A, tbl_siheader B
								WHERE A.AMD_REFNO = B.SI_CODE AND AMD_REFNO = '$SI_CODE' AND AMD_STAT IN (3,6)";
			$resTOTAMD		= $this->db->query($sqlTOTAMD)->result();
			foreach($resTOTAMD as $rowTOTAMD):
				$TOT_AMD	= $rowTOTAMD->TOT_AMD;
				$SI_APPVAL	= $rowTOTAMD->SI_APPVAL;
				if($TOT_AMD >= $SI_APPVAL)
				{
					$sqlUPDSI	= "UPDATE tbl_siheader SET SI_STAT = 6
									WHERE SI_CODE = '$SI_CODE' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUPDSI);
				}
			endforeach;		
	}
	
	function updateWBSMin($paramWBS) // G - NOT BUDGETING - MINUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$sql1	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM - $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ADDVOLM = $ADDVOLM2
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			
			$sql6	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			/*$JOBVOLM	= 1;
			$sqlJL		= "SELECT JOBVOLM FROM tbl_joblist 
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$JOBVOLM	= $rowJL->JOBVOLM;
			endforeach;*/
			$ITM_VOLM	= 1;
			$sqlJL		= "SELECT ITM_VOLM FROM tbl_joblist_detail
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resJL		= $this->db->query($sqlJL)->result();
			foreach($resJL as $rowJL):
				$ITM_VOLM	= $rowJL->ITM_VOLM;
			endforeach;
			$ITM_BUDG	= $ITM_VOLM * $ADD_PRICE;
			
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql1);
			
			//$JOBCOST	= $JOBVOLM * $ADD_PRICE;
			/*$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, JOBCOST = $JOBCOST, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql2);
			
			/*$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ITM_BUDG = $JOBCOST, 
								ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE,
								ITM_BUDG = $ITM_BUDG
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			
			$sql1	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM - $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = $ADDVOLM2
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateWBSMPlus($paramWBS) // G - PEMBALIKAN SI MINUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM - $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST - $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' c";
		$this->db->query($sql);*/
		
		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM, ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM, ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM - $ADD_VOLM;
			
			$sql6	= "UPDATE tbl_joblist SET ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql6);
			
			$sql7	= "UPDATE tbl_joblist_detail SET ADDM_JOBCOST = ADDM_JOBCOST - $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sql7);
		}
		elseif($AMD_CLASS == 1) // IF PRICE ONLY. SO NOT AMOUNT UPDATE
		{
			$sql1		= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql1);
			
			$sql2		= "UPDATE tbl_joblist SET PRICE = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql2);
			
			$sql3		= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE, ADD_PRICE = $ADD_PRICE
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			//$this->db->query($sql3);
		}
		elseif($AMD_CLASS == 2) // IF QTY ONLY
		{
			$sql1	= "UPDATE tbl_joblist SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
			
			$sql2	= "UPDATE tbl_joblist_detail SET ADDM_VOLM = ADDM_VOLM - $ADD_VOLM
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql2);
			
			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							)
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			
			$ITM_VOLMBG	= 0;
			$ADDMVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDMVOLM, ITM_IN FROM tbl_item 
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDMVOLM	= $rowITM->ADDMVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDMVOLM2		= $ADDMVOLM - $ADD_VOLM;
			
			$sql1		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDMVOLM = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql1);
		}
	}
	
	function updateSIHCanc($paramSIH) // G
	{
		$AMD_NUM 		= $paramSIH['SI_AMANDNO'];
		$SI_AMANDVAL 	= $paramSIH['SI_AMANDVAL'];
		$SI_AMANDSTAT	= $paramSIH['SI_AMANDSTAT'];
		$SI_CODE		= $paramSIH['SI_CODE'];
		$PRJCODE		= $paramSIH['PRJCODE'];
		
		if($SI_AMANDVAL == '')
			$SI_AMANDVAL	= 0;
			
		$sqlUPDSI	= "UPDATE tbl_siheader SET SI_AMAND = 0, SI_AMDCREATED = 0, SI_AMANDNO = '', 
							SI_AMANDVAL = 0, SI_AMANDSTAT = 0
						WHERE SI_CODE = '$SI_CODE' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUPDSI);
	}
	
	function deleteBOQ($AMD_NUM) // G
	{
		$this->db->where('JOBCOD2', $AMD_NUM);
		$this->db->delete('tbl_boqlist');
	}
	
	function deleteJL($AMD_NUM) // G
	{
		$this->db->where('JOBCOD2', $AMD_NUM);
		$this->db->delete('tbl_joblist');
	}
	
	function deleteJLD($AMD_NUM) // G
	{
		$this->db->where('JOBCOD2', $AMD_NUM);
		$this->db->delete('tbl_joblist_detail');
	}

	function search_blog($title){
        //$this->db->like('JOBDESC', $title , 'both');
        $this->db->order_by('ORD_ID', 'ASC');
        return $this->db->get('tbl_joblist')->result();
    }
	
	function get_AllDataJLC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist A
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLDC($PRJCODE, $ITM_CODEH, $AMD_CATEG, $search) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$tblName 	= "tbl_joblist_detail_$PRJCODEVW";

		/*if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI' || $AMD_CATEG == 'OTH')
			$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH' AND REQ_VOLM > 0";
		else if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
			$ADDQRY1	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ITM_CODE != '$ITM_CODEH' AND ISLASTH = '1' AND REQ_VOLM > 0";*/

		if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI' || $AMD_CATEG == 'OTH')
			$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH'";
		else if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
			$ADDQRY1	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ISLASTH = '1'";

		if($AMD_CATEG == 'SINJ')
		{
			$sql = "tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "$tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}

		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLDL($PRJCODE, $ITM_CODEH, $AMD_CATEG, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$tblName 	= "tbl_joblist_detail_$PRJCODEVW";

		if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI' || $AMD_CATEG == 'OTH')
			$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH'";
		else if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
			$ADDQRY1	= "SELECT JOBCODEID FROM tbl_joblist_detail WHERE ISLASTH = '1'";

		if($AMD_CATEG == 'SINJ')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST, A.ISLASTH, A.JOBLEV AS IS_LEVEL
							FROM tbl_boqlist A
							WHERE A.PRJCODE = '$PRJCODE' AND A.oth_reason = 'NEW'
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT $start, $length";*/
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
							FROM $tblName A
							WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataJLDETC($PRJCODE, $ITM_CODEH, $AMD_CATEG, $JOBID_SEL, $JOBPAR_SEL, $search) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$tblName 	= "tbl_joblist_detail_$PRJCODEVW";

		$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH' AND JOBCODEID != '$JOBID_SEL'";

		/*$sql 		= "$tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLASTH = '1'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";*/

		// HASIL MEETING TGL 18 MARET 2022
		// UNTUK ITEM PENGGANTI AMANDEMEN KATEGORI  "LAINNYA", TIDAK DIBATASI HY UTK PEKERJAAN YG MENGANDUNG ITEM YG AKAN DIAMANDEMEN/DITAMBAHKAN

		$sql 		= "$tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST = '1'
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLDETL($PRJCODE, $ITM_CODEH, $AMD_CATEG, $JOBID_SEL, $JOBPAR_SEL, $search, $length, $start, $order, $dir) // GOOD
	{
		// HASIL MEETING TGL 18 MARET 2022
		// UNTUK ITEM PENGGANTI AMANDEMEN KATEGORI  "LAINNYA", TIDAK DIBATASI HY UTK PEKERJAAN YG MENGANDUNG ITEM YG AKAN DIAMANDEMEN/DITAMBAHKAN

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$tblName 	= "tbl_joblist_detail_$PRJCODEVW";

		$ADDQRY1	= "SELECT JOBPARENT FROM $tblName WHERE ITM_CODE = '$ITM_CODEH' AND JOBCODEID != '$JOBID_SEL'";

		if($length == -1)
		{
			if($order !=null)
			{
				/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1) AND JOBCODEID != '$JOBPAR_SEL' AND A.ISLASTH = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";*/
				$sql = "SELECT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,A.ITM_CODE, A.ITM_UNIT AS JOBUNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.ITM_LASTP,
							-- A.REQ_VOLM, A.REQ_AMOUNT,
							((FPA_VOL-FPA_CVOL)+FPA_VOL_R+(PR_VOL-PR_CVOL)+PR_VOL_R+(WO_VOL-WO_CVOL)+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
							((FPA_VAL-FPA_CVAL)+FPA_VAL_R+(PO_VAL-PO_CVAL)+PO_VAL_R+(WO_VAL-WO_CVAL)+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
							A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,A.ITM_CODE, A.ITM_UNIT AS JOBUNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.ITM_LASTP,
							-- A.REQ_VOLM, A.REQ_AMOUNT,
							((FPA_VOL-FPA_CVOL)+FPA_VOL_R+(PR_VOL-PR_CVOL)+PR_VOL_R+(WO_VOL-WO_CVOL)+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
							((FPA_VAL-FPA_CVAL)+FPA_VAL_R+(PO_VAL-PO_CVAL)+PO_VAL_R+(WO_VAL-WO_CVAL)+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
							A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				/*$sql = "SELECT DISTINCT A.JOBCODEID, A.JOBDESC, A.ITM_UNIT AS JOBUNIT, A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID IN ($ADDQRY1)
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";*/
				$sql = "SELECT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,A.ITM_CODE, A.ITM_UNIT AS JOBUNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.ITM_LASTP,
							-- A.REQ_VOLM, A.REQ_AMOUNT,
							((FPA_VOL-FPA_CVOL)+FPA_VOL_R+(PR_VOL-PR_CVOL)+PR_VOL_R+(WO_VOL-WO_CVOL)+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
							((FPA_VAL-FPA_CVAL)+FPA_VAL_R+(PO_VAL-PO_CVAL)+PO_VAL_R+(WO_VAL-WO_CVAL)+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
							A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.JOBCODEID, A.JOBPARENT, A.JOBDESC,A.ITM_CODE, A.ITM_UNIT AS JOBUNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.ITM_LASTP,
							-- A.REQ_VOLM, A.REQ_AMOUNT,
							((FPA_VOL-FPA_CVOL)+FPA_VOL_R+(PR_VOL-PR_CVOL)+PR_VOL_R+(WO_VOL-WO_CVOL)+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
							((FPA_VAL-FPA_CVAL)+FPA_VAL_R+(PO_VAL-PO_CVAL)+PO_VAL_R+(WO_VAL-WO_CVAL)+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
							A.IS_LEVEL AS JOBLEV, A.ISLAST, A.ISLASTH, A.IS_LEVEL
						FROM $tblName A
						WHERE A.PRJCODE = '$PRJCODE' AND A.JOBCODEID != '$JOBPAR_SEL' AND A.ISLAST = 1
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMC($PRJCODE, $AMD_CATEG, $JPARENT, $search) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
		{
			$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		}
		else if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI')
		{
			$sql	= "tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $AMD_CATEG, $JPARENT, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.STATUS = 1
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1 AND A.WBSD_STAT = 1
								AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR B.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY B.ITM_NAME $order $dir";*/


					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC $order $dir";
				}
				else
				{
					$sql = "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataITMHC($PRJCODE, $AMDCAT, $search) // GOOD
	{
		$PRJHO 			= "";
		$sql 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJHO 		= $row ->PRJCODE_HO;
		endforeach;
		if($AMDCAT == 'NB')
		{
			$PRJCOLL 	= "'$PRJCODE', '$PRJHO'";
			$sql		= 	"tbl_item WHERE PRJCODE IN ($PRJCOLL) AND STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			$PRJCOLL 	= "'$PRJCODE'";
			$sql		= 	"tbl_item_$PRJCODEVW WHERE STATUS = 1 AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		}

		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMHL($PRJCODE, $AMDCAT, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJHO 			= "";
		$sql 			= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJHO 		= $row ->PRJCODE_HO;
		endforeach;
		if($AMDCAT == 'NB')
		{
			$PRJCOLL 	= "'$PRJCODE', '$PRJHO'";
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item WHERE PRJCODE IN ($PRJCOLL) AND STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item WHERE PRJCODE IN ($PRJCOLL) AND STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item WHERE PRJCODE IN ($PRJCOLL) AND STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item WHERE PRJCODE IN ($PRJCOLL) AND STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else
		{
			$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item_$PRJCODEVW WHERE STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item_$PRJCODEVW WHERE STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item_$PRJCODEVW WHERE STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, ITM_UNIT, ITM_VOLMBG, ITM_PRICE, ITM_TOTALP,
								PR_VOLM, PR_AMOUNT, AMD_VOL, AMD_VAL
							FROM tbl_item_$PRJCODEVW WHERE STATUS = 1
								AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataITMHOTHC($PRJCODE, $search) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$sql	= 	"tbl_joblist_detail_$PRJCODEVW A LEFT JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != ''
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMHOTHL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.ITM_CODE, A.ITM_GROUP, A.JOBCODEID, A.JOBDESC, A.JOBPARENT, B.JOBDESC AS JOBPARDESC, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.REQ_VOLM, A.REQ_AMOUNT, A.ADD_VOLM, A.ADD_JOBCOST
						FROM tbl_joblist_detail_$PRJCODEVW A LEFT JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != ''
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.ITM_CODE, A.ITM_GROUP, A.JOBCODEID, A.JOBDESC, A.JOBPARENT, B.JOBDESC AS JOBPARDESC, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.REQ_VOLM, A.REQ_AMOUNT, A.ADD_VOLM, A.ADD_JOBCOST
						FROM tbl_joblist_detail_$PRJCODEVW A LEFT JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != ''
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.ITM_CODE, A.ITM_GROUP, A.JOBCODEID, A.JOBDESC, A.JOBPARENT, B.JOBDESC AS JOBPARDESC, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.REQ_VOLM, A.REQ_AMOUNT, A.ADD_VOLM, A.ADD_JOBCOST
						FROM tbl_joblist_detail_$PRJCODEVW A LEFT JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != ''
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.ITM_CODE, A.ITM_GROUP, A.JOBCODEID, A.JOBDESC, A.JOBPARENT, B.JOBDESC AS JOBPARDESC, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_BUDG,
							A.REQ_VOLM, A.REQ_AMOUNT, A.ADD_VOLM, A.ADD_JOBCOST
						FROM tbl_joblist_detail_$PRJCODEVW A LEFT JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE != ''
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataSIC($PRJCODE, $search) // GOOD
	{
		$sql	= "tbl_siheader A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                    WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataSIL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            ORDER BY A.SI_CODE";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.SI_CODE, A.SI_MANNO, A.SI_STEP, A.SI_OWNER, A.SI_DATE, A.SI_ENDDATE, 
                                A.SI_DESC, A.SI_VALUE, A.SI_APPVAL
                            FROM tbl_siheader A
								INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
                            WHERE A.SI_STAT = 3 AND A.PRJCODE = '$PRJCODE'
                            	AND (A.SI_MANNO LIKE '%$search%' ESCAPE '!' OR A.SI_DESC LIKE '%$search%' ESCAPE '!')
                            LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataJLHC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist A
				WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = '0'
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLHL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEID, A.JOBDESC, A.JOBUNIT, A.JOBLEV, A.ISLAST
						FROM tbl_joblist A
						WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMC_pek($PRJCODE, $AMD_CATEG, $JPARENT, $search) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
		{
			/*$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND STATUS = 1
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR ITM_NAME LIKE '%$search%' ESCAPE '!')";*/
			$sql	= "tbl_item WHERE STATUS = 1
						AND (ITM_CODE LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR ITM_NAME LIKE '%$search%' ESCAPE '!')";
		}
		else if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI')
		{
			$sql	= "tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML_pek($PRJCODE, $AMD_CATEG, $JPARENT, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		if($AMD_CATEG == 'NB' || $AMD_CATEG == 'SINJ')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE AND B.STATUS = 1
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1 AND A.WBSD_STAT = 1
								AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR B.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY B.ITM_NAME $order $dir";*/


					/*$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME $order $dir";*/

					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME $order $dir";
				}
				else
				{
					/*$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";*/
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir
								LIMIT $start, $length";*/
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.STATUS = 1
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					/*$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.PRJCODE = '$PRJCODE' AND A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";*/
					$sql = "SELECT DISTINCT 0 AS ORD_ID, '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, ITM_NAME AS JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_VOLMBG AS ITM_BUDG,
								A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_PRICE, A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_OUT AS ITM_USED, A.ITM_OUTP AS ITM_USED_AM, A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM, 
								 0 AS IS_LEVEL, 1 AS ISLAST
							FROM tbl_item A WHERE A.STATUS = 1
								AND A.ITM_CODE NOT IN (SELECT B.ITM_CODE FROM tbl_joblist_detail B WHERE B.JOBPARENT = '$JPARENT' AND B.PRJCODE = '$PRJCODE')
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
								OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		else if($AMD_CATEG == 'OB' || $AMD_CATEG == 'SI')
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC $order $dir";
				}
				else
				{
					$sql = "SELECT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, 
								A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail_$PRJCODEVW A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 1
								AND A.JOBPARENT = '$JPARENT'
								AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!'
								OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.JOBDESC LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
	
	function get_AllDataC_1n2_pek($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_amd_header A
					LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
						AND B.PRJCODE = '$PRJCODE'
				WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'OVH'
					AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2_pek($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'OVH'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'OVH'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'OVH'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.AMD_ID, A.AMD_NUM, A.AMD_CODE, A.PRJCODE, A.AMD_CATEG, A.AMD_JOBID, A.AMD_DATE,
							A.AMD_DESC, A.AMD_NOTES,  A.AMD_STAT, A.AMD_MEMO, A.AMD_AMOUNT,
							A.STATDESC, A.STATCOL, A.CREATERNM, B.JOBDESC
						FROM tbl_amd_header A
							LEFT JOIN tbl_joblist B ON A.AMD_JOBID = B.JOBCODEID
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.AMD_STAT IN (2,7) AND A.AMD_TYPE = 'OVH'
							AND (A.AMD_CODE LIKE '%$search%' ESCAPE '!' OR A.AMD_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AMD_NOTES LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function updateWBS_pek_220604($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_VOLM1 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);*/
		
		// SELECT ITM_VOLMBG, ITM_VOLMBGR, ITM_PRICE, ITM_LASTP, ADDVOLM, ADDCOST FROM tbl_item; 
		// SELECT JOBVOLM, PRICE, JOBCOST, ADD_VOLM, ADD_PRICE, ADD_JOBCOST FROM tbl_joblist; 
		// SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST  FROM tbl_joblist_detail;

		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			/*echo "DONE 11<br>";
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			echo "DONE 12<br>";*/
		}
		elseif($AMD_CLASS == 1) // VOL. ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. Harga diambil dari harga rata2 terakhir
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// HARGA RATA2 TERAKHIR
					/*$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEV;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN*/
					$BUD_USEVP 	= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_JOBCOST	= $ADD_VOLM*$ADD_PRICE;

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 2) // PRICE ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN HARGA
				1. Hanya melakukan perubahan harga
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 50			1,000		 50,000		Digunakan
					Sisa 		 50 		1,000 		 50,000		Sisa
					Amandemen 	 50 		1,200 		 60,000		Amandemen
					Total 		 50 		1,200 		110,000 	(Sisa + Amandemen)
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// HARGA RATA2 TERAKHIR SETELAH DIKURANGI RAP YANG DIGUNAKAN
					/*$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $REQ_AMOUNT;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $ITM_BUDG / $ITM_VOLMP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN*/
					$BUD_USEVP 	= $BUD_USEV;
					if($BUD_USEV == 0)
						$BUD_USEVP 	= 1;

					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_JOBCOST	= $BUD_REMV*$ADD_PRICE;

			// 	3. UPDATE JOBLIST
				$ITM_BUDGB 	= $BUD_USEAMN + $ADD_JOBCOST;		// BUDGET YANG SUDAH DIGUNAKAN + SISA BUDGET SETELAH DIKALI HARGA AMANDEMEN

				$s_u1		= "UPDATE tbl_joblist_detail SET
									ADD_VOLM = ADD_VOLM+$ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 	4. UPDATE PRICE IN MASTER ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 10) // PRICE IS DISABLED. QTY ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. TIDAK merubah TOTAL BUDGET
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 80			1,000		 80,000		Digunakan
					Sisa 		 20 		1,000 		 20,000		Sisa
					Amandemen 	  5 		0,000 		  0,000		Amandemen
					Total Sisa	 25 		  800 		 20,000 	(HRG. = Sisa Amount / (Sisa + Amandemen))
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;

					$BUD_USEVP 	= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV + $ADD_VOLM1;	// SISA VOLUME + AMD_VOLM DIJADIKAN SEBAGAI AMANDEMEN VOLUME
					$ADD_VOLMP 		= $ADD_VOLM;
					if($ADD_VOLMP == 0)
						$ADD_VOLMP 	= 1;
						
					$ADD_PRICE 		= $BUD_REMAMN / $ADD_VOLMP;
					$ADD_JOBCOST	= $BUD_REMAMN;				// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
	}
	
	function updateWBS_pek($paramWBS) // OK - NOT BUDGETING - SI PLUS
	{
		$ADD_VOLM 		= $paramWBS['ADD_VOLM'];
		$ADD_VOLM1 		= $paramWBS['ADD_VOLM'];
		$ADD_PRICE 		= $paramWBS['ADD_PRICE'];
		$ADD_JOBCOST	= $paramWBS['ADD_JOBCOST'];
		$JOBPARENT		= $paramWBS['JOBPARENT'];
		$JOBCODEID		= $paramWBS['JOBCODEID'];
		$ITM_CODE		= $paramWBS['ITM_CODE'];
		$AMD_CLASS		= $paramWBS['AMD_CLASS'];
		$PRJCODE		= $paramWBS['PRJCODE'];
		
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		if($ADD_VOLM == '')
			$ADD_VOLM	= 0;
		if($ADD_PRICE == '')
			$ADD_PRICE	= 0;
		if($ADD_JOBCOST == '')
			$ADD_JOBCOST	= 0;
		
		/*$sql	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
						ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
					WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sql);*/
		
		// SELECT ITM_VOLMBG, ITM_VOLMBGR, ITM_PRICE, ITM_LASTP, ADDVOLM, ADDCOST FROM tbl_item; 
		// SELECT JOBVOLM, PRICE, JOBCOST, ADD_VOLM, ADD_PRICE, ADD_JOBCOST FROM tbl_joblist; 
		// SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG, ADD_VOLM, ADD_PRICE, ADD_JOBCOST  FROM tbl_joblist_detail;

		if($AMD_CLASS == 0) // IF VOLM AND PRICE
		{
			$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
							ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
						WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_u1);

			$s_P2 	= "SELECT JOBPARENT FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
			$r_P2 	= $this->db->query($s_P2)->result();
			foreach($r_P2 as $rw_P2) :
				$JP2 	= $rw_P2->JOBPARENT;
				$s_u2a	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2a);

				$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_u2b);

				$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP2' LIMIT 1";
				$r_P3 	= $this->db->query($s_P3)->result();
				foreach($r_P3 as $rw_P3) :
					$JP3 	= $rw_P3->JOBPARENT;
					$s_u3	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3);

					$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
									ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
								WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u3b);

					$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP3' LIMIT 1";
					$r_P4 	= $this->db->query($s_P4)->result();
					foreach($r_P4 as $rw_P4) :
						$JP4 	= $rw_P4->JOBPARENT;
						$s_u4	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4);

						$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
										ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
									WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u4b);
					
						$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP4' LIMIT 1";
						$r_P5 	= $this->db->query($s_P5)->result();
						foreach($r_P5 as $rw_P5) :
							$JP5 	= $rw_P5->JOBPARENT;
							$s_u5	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5);

							$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
											ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
										WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u5b);
					
							$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP5' LIMIT 1";
							$r_P6 	= $this->db->query($s_P6)->result();
							foreach($r_P6 as $rw_P6) :
								$JP6 	= $rw_P6->JOBPARENT;
								$s_u6	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6);

								$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
												ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
											WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u6b);
					
								$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP6' LIMIT 1";
								$r_P7 	= $this->db->query($s_P7)->result();
								foreach($r_P7 as $rw_P7) :
									$JP7 	= $rw_P7->JOBPARENT;
									$s_u7	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7);

									$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
													ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
												WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u7b);

									$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP7' LIMIT 1";
									$r_P8 	= $this->db->query($s_P8)->result();
									foreach($r_P8 as $rw_P8) :
										$JP8 	= $rw_P8->JOBPARENT;
										$s_u8	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8);

										$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
														ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
													WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u8b);

										$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP8' LIMIT 1";
										$r_P9 	= $this->db->query($s_P9)->result();
										foreach($r_P9 as $rw_P9) :
											$JP9 	= $rw_P9->JOBPARENT;
											$s_u9	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9);

											$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
															ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
														WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u9b);

											$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP9' LIMIT 1";
											$r_P10 	= $this->db->query($s_P10)->result();
											foreach($r_P10 as $rw_P10) :
												$JP10 	= $rw_P10->JOBPARENT;
												$s_u10	= "UPDATE tbl_joblist SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10);

												$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
																ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
															WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u10b);
												// echo "DONE 10<br>";
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;
			endforeach;

			$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
							(
								SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
								FROM tbl_joblist_detail_$PRJCODEVW B 
								WHERE B.ITM_CODE = A.ITM_CODE
									AND B.PRJCODE = '$PRJCODE'
									AND B.ITM_CODE = '$ITM_CODE'
							), ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql4);
			/*echo "DONE 11<br>";
			$ITM_VOLMBG	= 0;
			$ADDVOLM	= 0;
			$ITM_IN		= 0;
			$sqlITM		= "SELECT ITM_VOLMBG, ADDVOLM, ITM_IN FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
			$resITM		= $this->db->query($sqlITM)->result();
			foreach($resITM as $rowITM):
				$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
				$ADDVOLM	= $rowITM->ADDVOLM;
				$ITM_IN		= $rowITM->ITM_IN;
			endforeach;
			$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
			$ADDVOLM2		= $ADDVOLM + $ADD_VOLM;
			
			$sql5		= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM, ADDCOST = $ADD_JOBCOST
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sql5);
			echo "DONE 12<br>";*/
		}
		elseif($AMD_CLASS == 1) // VOL. ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. Harga diambil dari harga rata2 terakhir
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail_$PRJCODEVW
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// HARGA RATA2 TERAKHIR
					/*$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEV;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN*/
					$BUD_USEVP 	= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_JOBCOST	= $ADD_VOLM*$ADD_PRICE;

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1	= "UPDATE tbl_joblist_detail SET ADD_VOLM = ADD_VOLM + $ADD_VOLM, ADD_PRICE = $ADD_PRICE,
								ADD_JOBCOST = ADD_JOBCOST + $ADD_JOBCOST
							WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_$PRJCODEVW_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 2) // PRICE ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN HARGA
				1. Hanya melakukan perubahan harga
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 50			1,000		 50,000		Digunakan
					Sisa 		 50 		1,000 		 50,000		Sisa
					Amandemen 	 50 		1,200 		 60,000		Amandemen
					Total 		 50 		1,200 		110,000 	(Sisa + Amandemen)
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail_$PRJCODEVW
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// HARGA RATA2 TERAKHIR SETELAH DIKURANGI RAP YANG DIGUNAKAN
					/*$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $REQ_AMOUNT;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;
					$ITM_PRICE	= $ITM_BUDG / $ITM_VOLMP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN*/
					$BUD_USEVP 	= $BUD_USEV;
					if($BUD_USEV == 0)
						$BUD_USEVP 	= 1;

					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_JOBCOST	= $BUD_REMV*$ADD_PRICE;

			// 	3. UPDATE JOBLIST
				$ITM_BUDGB 	= $BUD_USEAMN + $ADD_JOBCOST;		// BUDGET YANG SUDAH DIGUNAKAN + SISA BUDGET SETELAH DIKALI HARGA AMANDEMEN

				$s_u1		= "UPDATE tbl_joblist_detail SET
									ADD_VOLM = ADD_VOLM+$ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

				// DI HEADER SELANJUTNYA HANYA MERUBAH ADD_JOBCOST
				$s_P2 		= "SELECT JOBPARENT FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
				$r_P2 		= $this->db->query($s_P2)->result();
				foreach($r_P2 as $rw_P2) :
					$JP2 	= $rw_P2->JOBPARENT;
					$s_u2a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2a);
					$s_u2b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
								WHERE JOBCODEID = '$JP2' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_u2b);
					// echo "DONE 2<br>";
					$s_P3 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP2' LIMIT 1";
					$r_P3 	= $this->db->query($s_P3)->result();
					foreach($r_P3 as $rw_P3) :
						$JP3 	= $rw_P3->JOBPARENT;
						$s_u3a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3a);
						$s_u3b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
									WHERE JOBCODEID = '$JP3' AND PRJCODE = '$PRJCODE'";
						$this->db->query($s_u3b);
						// echo "DONE 3<br>";
						$s_P4 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP3' LIMIT 1";
						$r_P4 	= $this->db->query($s_P4)->result();
						foreach($r_P4 as $rw_P4) :
							$JP4 	= $rw_P4->JOBPARENT;
							$s_u4a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4a);
							$s_u4b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
										WHERE JOBCODEID = '$JP4' AND PRJCODE = '$PRJCODE'";
							$this->db->query($s_u4b);
							// echo "DONE 4<br>";
							$s_P5 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP4' LIMIT 1";
							$r_P5 	= $this->db->query($s_P5)->result();
							foreach($r_P5 as $rw_P5) :
								$JP5 	= $rw_P5->JOBPARENT;
								$s_u5a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5a);

								$s_u5b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
											WHERE JOBCODEID = '$JP5' AND PRJCODE = '$PRJCODE'";
								$this->db->query($s_u5b);
								// echo "DONE 5<br>";
								$s_P6 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP5' LIMIT 1";
								$r_P6 	= $this->db->query($s_P6)->result();
								foreach($r_P6 as $rw_P6) :
									$JP6 	= $rw_P6->JOBPARENT;
									$s_u6a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6a);

									$s_u6b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
												WHERE JOBCODEID = '$JP6' AND PRJCODE = '$PRJCODE'";
									$this->db->query($s_u6b);
									// echo "DONE 6<br>";
									$s_P7 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP6' LIMIT 1";
									$r_P7 	= $this->db->query($s_P7)->result();
									foreach($r_P7 as $rw_P7) :
										$JP7 	= $rw_P7->JOBPARENT;
										$s_u7a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7a);

										$s_u7b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
													WHERE JOBCODEID = '$JP7' AND PRJCODE = '$PRJCODE'";
										$this->db->query($s_u7b);
										// echo "DONE 7<br>";
										$s_P8 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP7' LIMIT 1";
										$r_P8 	= $this->db->query($s_P8)->result();
										foreach($r_P8 as $rw_P8) :
											$JP8 	= $rw_P8->JOBPARENT;
											$s_u8a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8a);

											$s_u8b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
														WHERE JOBCODEID = '$JP8' AND PRJCODE = '$PRJCODE'";
											$this->db->query($s_u8b);
											// echo "DONE 8<br>";
											$s_P9 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP8' LIMIT 1";
											$r_P9 	= $this->db->query($s_P9)->result();
											foreach($r_P9 as $rw_P9) :
												$JP9 	= $rw_P9->JOBPARENT;
												$s_u9a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9a);

												$s_u9b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
															WHERE JOBCODEID = '$JP9' AND PRJCODE = '$PRJCODE'";
												$this->db->query($s_u9b);
												// echo "DONE 9<br>";
												$s_P10 	= "SELECT JOBPARENT FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JP9' LIMIT 1";
												$r_P10 	= $this->db->query($s_P10)->result();
												foreach($r_P10 as $rw_P10) :
													$JP10 	= $rw_P10->JOBPARENT;
													$s_u10a	= "UPDATE tbl_joblist SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10a);

													$s_u10b	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = ADD_JOBCOST+$ADD_JOBCOST
																WHERE JOBCODEID = '$JP10' AND PRJCODE = '$PRJCODE'";
													$this->db->query($s_u10b);
													// echo "DONE 10<br>";
												endforeach;
											endforeach;
										endforeach;
									endforeach;
								endforeach;
							endforeach;
						endforeach;
					endforeach;
				endforeach;

			// 	4. UPDATE PRICE IN MASTER ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_$PRJCODEVW_detail B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_PRICE = $ADD_PRICE, ITM_LASTP = $ADD_PRICE,
								ITM_VOLMBGR = $ITM_VOLMBGR
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
		elseif($AMD_CLASS == 10) // PRICE IS DISABLED. QTY ONLY
		{
			/* 	CATATAN HANYA AMANDEMEN VOLUME
				1. Hanya melakukan perubahan volume. TIDAK merubah TOTAL BUDGET
				2. Volume yang sudah digunakan seharus tidak ikut terakumulasi terhadap perubahan harga
				3. Pisahkan Volume dan Total yang sudah digunakan
				4. Harga yang diamandemen, dilakukan terpisah dengan volume dan total yang sudah digunakan

								VOL. 		HRG.		TOTAL
					Budget 		100			1,000		100,000
					Digunakan 	 80			1,000		 80,000		Digunakan
					Sisa 		 20 		1,000 		 20,000		Sisa
					Amandemen 	  5 		0,000 		  0,000		Amandemen
					Total Sisa	 25 		  800 		 20,000 	(HRG. = Sisa Amount / (Sisa + Amandemen))
			*/

			// 	1. BUDGET YANG SUDAH DIGUNAKAN (REQUEST / SPK)
				$ITM_VOLM	= 0;
				$ITM_BUDGA 	= 0;		// BUDGET SEBELUM AMANDEMEN
				$REQ_VOLM	= 0;
				$REQ_AMOUNT = 0;
				$BUD_REMV	= 0;
				$BUD_REMAMN = 0;
				$sqlJL		= "SELECT ITM_VOLM, ITM_BUDG, REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail_$PRJCODEVW
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resJL		= $this->db->query($sqlJL)->result();
				foreach($resJL as $rowJL):
					$ITM_VOLM	= $rowJL->ITM_VOLM;
					$ITM_BUDGA	= $rowJL->ITM_BUDG;
					$REQ_VOLM	= $rowJL->REQ_VOLM;
					$REQ_AMOUNT	= $rowJL->REQ_AMOUNT;
					$BUD_REMV 	= $ITM_VOLM - $REQ_VOLM;
					$BUD_REMAMN = $ITM_BUDGA - $REQ_AMOUNT;
				endforeach;

			// 	2. MEMISAHKAN VOLUME DAN NILAI YANG SUDAH DIGUNAKAN DENGAN VOLUME DAN NILAI YANG AKAN DIAMANDEMEN
				$BUD_USEV 	= $REQ_VOLM;
				$BUD_USEAMN = $REQ_AMOUNT;

				// NILAI AWAL
					$ITM_VOLM	= $BUD_USEV;
					$ITM_BUDG 	= $BUD_USEAMN;
					$ITM_VOLMP 	= $ITM_VOLM;
					if($ITM_VOLM == 0)
						$ITM_VOLMP 	= 1;

					$BUD_USEVP 	= $BUD_USEV;
					if($BUD_USEVP == 0)
						$BUD_USEVP 	= 1;
						
					$ITM_PRICE	= $BUD_USEAMN / $BUD_USEVP;		// HARGA RATA-RATA DARI SISA BUDGET YANG SUDAH DIGUNAKAN

				// NILAI ADDENDUM
					$ADD_VOLM		= $BUD_REMV + $ADD_VOLM1;	// SISA VOLUME + AMD_VOLM DIJADIKAN SEBAGAI AMANDEMEN VOLUME
					$ADD_VOLMP 		= $ADD_VOLM;
					if($ADD_VOLMP == 0)
						$ADD_VOLMP 	= 1;
						
					$ADD_PRICE 		= $BUD_REMAMN / $ADD_VOLMP;
					$ADD_JOBCOST	= $BUD_REMAMN;				// DIANGGAP SEBAGAI TOTAL AMANDEMEN

			// 	3. UPDATE JOBLIST. ONLY JOBLIST_DETAIL KARENA TOTAL BUDGET TIDAK BERUBAH, YANG BERUBAH ADALAH HARGA
				$s_u1		= "UPDATE tbl_joblist_detail SET 
									ITM_VOLM = $ITM_VOLM, ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ADD_PRICE, ITM_AVGP = $ADD_PRICE, ITM_BUDG = $ITM_BUDG,
									ADD_VOLM = $ADD_VOLM, ADD_PRICE = $ADD_PRICE, ADD_JOBCOST = $ADD_JOBCOST
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($s_u1);

			// UPDATE ITEM
				$sql4		= "UPDATE tbl_item A SET A.ITM_VOLMBG = 
								(
									SELECT SUM(B.ITM_VOLM + B.ADD_VOLM - B.ADDM_VOLM) 
									FROM tbl_joblist_detail_$PRJCODEVW B 
									WHERE B.ITM_CODE = A.ITM_CODE
										AND B.PRJCODE = '$PRJCODE'
										AND B.ITM_CODE = '$ITM_CODE'
								)
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql4);
			
				$ITM_VOLMBG	= 0;
				$ADDVOLM	= 0;
				$ITM_IN		= 0;
				$sqlITM		= "SELECT ITM_VOLMBG, PR_VOLM, PR_AMOUNT, ADDVOLM, ITM_IN FROM tbl_item 
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$resITM		= $this->db->query($sqlITM)->result();
				foreach($resITM as $rowITM):
					$ITM_VOLMBG	= $rowITM->ITM_VOLMBG;
					$PR_VOLM	= $rowITM->PR_VOLM;
					$PR_AMOUNT	= $rowITM->PR_AMOUNT;
					$ADDVOLM	= $rowITM->ADDVOLM;
					$ITM_IN		= $rowITM->ITM_IN;
				endforeach;
				//$ITM_VOLMBGR	= $ITM_VOLMBG - $ITM_IN;
				$ITM_VOLMBGR	= $ITM_VOLMBG - $PR_VOLM;
				
				/*$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM,
								ADDCOST = ADDCOST + $ITM_BUDGB
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";*/
				$sql1	= "UPDATE tbl_item SET ITM_VOLMBGR = $ITM_VOLMBGR, ADDVOLM = ADDVOLM + $ADD_VOLM
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sql1);
		}
	}
}
?>