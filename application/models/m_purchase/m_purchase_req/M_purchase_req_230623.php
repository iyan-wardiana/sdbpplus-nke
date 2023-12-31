<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 18 Oktober 2017
	* File Name		= M_purchase_req.php
	* Location		= -
*/
class M_purchase_req extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			$ADDQRY1 = "AND DEPCODE = '$DEPCODE'";
			if($DEPCODE == '')
				$ADDQRY1 = "AND DEPCODE = 'NO_DEPT'";
		}

		$sql = "tbl_pr_header A 
				WHERE A.PRJCODE = '$PRJCODE'
					AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
					OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
					OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			$ADDQRY1 = "AND DEPCODE = '$DEPCODE'";
			if($DEPCODE == '')
				$ADDQRY1 = "AND DEPCODE = 'NO_DEPT'";
		}

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($PRJCODE, $PO_NUM, $PR_STAT, $search, $length, $start) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";

		if($PO_NUM != '')
			$ADDQRY1 	= "AND A.PR_NUM IN (SELECT B.PR_NUM FROM tbl_po_header B WHERE B.PO_NUM = '$PO_NUM')";
		if($PR_STAT != 0)
			$ADDQRY2 	= "AND A.PR_STAT = '$PR_STAT'";

		if($length == -1)
		{
			$sql 	= "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql 	= "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}
		
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataGRPL($PRJCODE, $PO_NUM, $PR_STAT, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";

		if($PO_NUM != '')
			$ADDQRY1 	= "AND A.PR_NUM IN (SELECT B.PR_NUM FROM tbl_po_header B WHERE B.PO_NUM = '$PO_NUM')";
		if($PR_STAT != 0)
			$ADDQRY2 	= "AND A.PR_STAT = '$PR_STAT'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search, $length, $start) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			//$ADDQRY1 = "AND DEPCODE = '$DEPCODE'"; // sementara di hide dulu ya 29 06 2020
			$ADDQRY1 = "";
		}

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = '0'
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = '0'
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}

		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			//$ADDQRY1 = "AND DEPCODE = '$DEPCODE'"; // sementara di hide dulu ya 29 06 2020
			$ADDQRY1 = "";
		}

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 0
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 0
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n24lt($PRJCODE, $search, $length, $start) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			//$ADDQRY1 = "AND DEPCODE = '$DEPCODE'"; // sementara di hide dulu ya 29 06 2020
			$ADDQRY1 = "";
		}

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = '1'
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = '1'
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}

		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataL_1n24lt($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			//$ADDQRY1 = "AND DEPCODE = '$DEPCODE'"; // sementara di hide dulu ya 29 06 2020
			$ADDQRY1 = "";
		}

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 1
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 1
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rowsPR($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_pr_header A
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_pr_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PR_NUM LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$key%' ESCAPE '!' OR PR_DATE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_PR($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC,
						A.PR_NOTE, A.PR_STAT, PR_MEMO, A.PR_REFNO, A.PR_CREATER, A.PR_ISCLOSE, A.PR_POTOT,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_pr_header A
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC,
						A.PR_NOTE, A.PR_STAT, PR_MEMO, A.PR_REFNO, A.PR_CREATER, A.PR_ISCLOSE, A.PR_POTOT,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_pr_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (PR_NUM LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$key%' ESCAPE '!' OR PR_DATE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
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
	
	function count_all_prim($PRJCODE, $JOBCODE) // G
	{
		if($PRJCODE == 'KTR')
		{
			$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBPARENT IN ($JOBCODE)";
		}
		else
		{
			$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBPARENT IN ($JOBCODE)";
		}		
		return $this->db->count_all($sql);		
	}
	
	function view_all_prim($PRJCODE, $JOBCODE) // G
	{
		// HANYA KATEGORI M (SEBELUMNYA ('M','T') --> MS.201990400005
		if($PRJCODE == 'KTR')
		{
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE,
								A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM AS ITM_VOLMBG, A.ADD_VOLM, 
								A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM,
								A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, 
								A.ITM_STOCK_AM, A.ITM_BUDG
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M') AND A.JOBPARENT IN ($JOBCODE)
								AND B.STATUS = 1";
		}
		else
		{
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE,
								A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM AS ITM_VOLMBG, A.ADD_VOLM, 
								A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM,
								A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, 
								A.ITM_STOCK_AM, A.ITM_BUDG
							FROM tbl_joblist_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M')
								AND B.STATUS = 1";
		}
		return $this->db->query($sql);
	}
	
	function count_all_subs($PRJCODE, $JOBCODE) // G
	{
		$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_TYPE = 'SUBS' AND ITM_GROUP IN ('M') AND STATUS = 1";
		return $this->db->count_all($sql);
	}
	
	function view_all_subs($PRJCODE, $JOBCODE) // G
	{
		// HANYA KATEGORI M (SEBELUMNYA ('M','T') --> MS.201990400005
		$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
						PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, ITM_VOLMBG, ADDVOLM AS ADD_VOLM, 
						ADDCOST AS ADD_PRICE, PR_VOLM AS REQ_VOLM, PR_AMOUNT AS REQ_AMOUNT, PO_VOLM, PO_AMOUNT, IR_VOLM, 
						IR_AMOUNT, ITM_OUT AS ITM_USED, ITM_OUTP AS ITM_USED_AM, ITM_VOLM AS ITM_STOCK, 
						ITM_TOTALP AS ITM_STOCK_AM, ITM_VOLMBG AS ITM_BUDG,
						ITM_TYPE, ITM_NAME, ITM_CODE_H
					FROM tbl_item
						WHERE PRJCODE = '$PRJCODE' AND ITM_TYPE = 'SUBS' AND ITM_GROUP IN ('M') AND STATUS = 1";
		return $this->db->query($sql);
	}
	
	function add($projMatReqH) // G
	{
		$this->db->insert('tbl_pr_header', $projMatReqH);
	}
	
	function addH($projMatReqH, $tblPRH) // G
	{
		$this->db->insert($tblPRH, $projMatReqH);
	}
	
	function add_fpa($projMatReqH) // G
	{
		$this->db->insert('tbl_pr_header_fpa', $projMatReqH);
	}
	
	function updateDet($PR_NUM, $PRJCODE, $PR_DATE) // G
	{
		$sql = "UPDATE tbl_pr_detail SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function updatePRDet($PR_NUM, $PRJCODE, $PR_DATE, $tblPRD) // G
	{
		$sql = "UPDATE $tblPRD SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function updateDet_fpa($PR_NUM, $PRJCODE, $PR_DATE) // G
	{
		$sql = "UPDATE tbl_pr_detail_fpa SET PRJCODE = '$PRJCODE', PR_DATE = '$PR_DATE' WHERE PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function get_MR_by_number($PR_NUM, $tblPRH) // G
	{
		$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC, A.PR_DIVID,
					A.PR_NOTE, A.PR_NOTE2, A.PR_STAT, PR_MEMO, A.PR_VALUE, A.PR_VALUEAPP, A.PR_REFNO, A.PR_PLAN_IR, A.PR_CATEG,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PR_CREATER, A.DEPCODE, A.PR_REQUESTER, A.Reference_Number
				FROM $tblPRH A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function get_MR_by_number_inb($PR_NUM) // G
	{
		$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC, A.PR_DIVID,
					A.PR_NOTE, A.PR_NOTE2, A.PR_STAT, PR_MEMO, A.PR_VALUE, A.PR_VALUEAPP, A.PR_REFNO, A.PR_PLAN_IR, A.PR_CATEG,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PR_CREATER, A.DEPCODE, A.PR_REQUESTER, A.Reference_Number
				FROM tbl_pr_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function get_MR_by_number_fpa($PR_NUM) // G
	{
		$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC, A.PR_DIVID,
					A.PR_NOTE, A.PR_NOTE2, A.PR_STAT, PR_MEMO, A.PR_VALUE, A.PR_VALUEAPP, A.PR_REFNO, A.PR_PLAN_IR, A.PR_CATEG,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.PR_CREATER, A.DEPCODE, A.PR_REQUESTER, A.Reference_Number
				FROM tbl_pr_header_fpa A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function update($PR_NUM, $projMatReqH) // G
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function updateH($PR_NUM, $projMatReqH, $tblPRH) // G
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update($tblPRH, $projMatReqH);
	}
	
	function update_fpa($PR_NUM, $projMatReqH) // G
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->update('tbl_pr_header_fpa', $projMatReqH);
	}
	
	function deleteDetail($PR_NUM) // G
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->delete('tbl_pr_detail');
	}
	
	function deleteDetailD($PR_NUM, $tblPRD) // G
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->delete($tblPRD);
	}
	
	function deleteDetail_fpa($PR_NUM) // G
	{
		$this->db->where('PR_NUM', $PR_NUM);
		$this->db->delete('tbl_pr_detail_fpa');
	}
	
	function updateVolBud($PR_NUM, $PRJCODE, $ITM_CODE) // G
	{
		$s_00 		= "UPDATE tbl_pr_detail A, tbl_pr_header B SET A.PR_DATE = B.PR_DATE WHERE A.PR_NUM = B.PR_NUM";
		$this->db->query($s_00);

		$PO_VOLM 	= 0;
		$PO_AMOUNT 	= 0;
		$IR_VOLM 	= 0;			
		$sqlGetPR	= "SELECT JOBCODEID, PR_DATE, ITM_UNIT, PR_VOLM, PR_PRICE, PR_TOTAL, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT 
						FROM tbl_pr_detail
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowPR) :
			$JOBCODEID 	= $rowPR->JOBCODEID;
			$PR_DATE 	= $rowPR->PR_DATE;
			$ITM_UNIT 	= strtoupper($rowPR->ITM_UNIT);
			$PR_VOLM 	= $rowPR->PR_VOLM;
			$PR_TOTAL 	= $rowPR->PR_TOTAL;
			$PO_VOLM 	= $rowPR->PO_VOLM;
			$PO_AMOUNT 	= $rowPR->PO_AMOUNT;
			$IR_VOLM 	= $rowPR->IR_VOLM;
			$IR_AMOUNT 	= $rowPR->IR_AMOUNT;

			$REM_VOL 	= $PR_VOLM - $PO_VOLM;
			$REM_VAL 	= $PR_TOTAL - $PO_AMOUNT;

			$s_01		= "UPDATE tbl_joblist_report SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$PR_DATE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_01);
			
			$s_02		= "UPDATE tbl_joblist_detail SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_02);
			
			$s_03		= "UPDATE tbl_item SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_03);
		endforeach;		
	}
	
	function updateVolBud2($PR_NUM, $PRJCODE, $ITM_CODE, $tblPRH, $tblPRD) // G
	{
		$s_00 		= "UPDATE $tblPRD A, $tblPRH B SET A.PR_DATE = B.PR_DATE WHERE A.PR_NUM = B.PR_NUM";
		$this->db->query($s_00);

		$PO_VOLM 	= 0;
		$PO_AMOUNT 	= 0;
		$IR_VOLM 	= 0;			
		$sqlGetPR	= "SELECT JOBCODEID, PR_DATE, ITM_UNIT, PR_VOLM, PR_PRICE, PR_TOTAL, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT 
						FROM $tblPRD
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowPR) :
			$JOBCODEID 	= $rowPR->JOBCODEID;
			$PR_DATE 	= $rowPR->PR_DATE;
			$ITM_UNIT 	= strtoupper($rowPR->ITM_UNIT);
			$PR_VOLM 	= $rowPR->PR_VOLM;
			$PR_TOTAL 	= $rowPR->PR_TOTAL;
			$PO_VOLM 	= $rowPR->PO_VOLM;
			$PO_AMOUNT 	= $rowPR->PO_AMOUNT;
			$IR_VOLM 	= $rowPR->IR_VOLM;
			$IR_AMOUNT 	= $rowPR->IR_AMOUNT;

			$REM_VOL 	= $PR_VOLM - $PO_VOLM;
			$REM_VAL 	= $PR_TOTAL - $PO_AMOUNT;

			/*$s_01		= "UPDATE tbl_joblist_report SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$PR_DATE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_01);*/
			
			$s_02		= "UPDATE tbl_joblist_detail SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_02);
			
			$s_03		= "UPDATE tbl_item SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_03);
		endforeach;		
	}
	
	function updateVolBud_fpa($PR_NUM, $PRJCODE, $ITM_CODE) // G
	{
		$s_00 		= "UPDATE tbl_pr_detail_fpa A, tbl_pr_header_fpa B SET A.PR_DATE = B.PR_DATE WHERE A.PR_NUM = B.PR_NUM";
		$this->db->query($s_00);

		$PO_VOLM 	= 0;
		$PO_AMOUNT 	= 0;
		$IR_VOLM 	= 0;			
		$sqlGetPR	= "SELECT JOBCODEID, PR_DATE, ITM_UNIT, PR_VOLM, PR_PRICE, PR_TOTAL, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT 
						FROM tbl_pr_detail_fpa
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowPR) :
			$JOBCODEID 	= $rowPR->JOBCODEID;
			$PR_DATE 	= $rowPR->PR_DATE;
			$ITM_UNIT 	= strtoupper($rowPR->ITM_UNIT);
			$PR_VOLM 	= $rowPR->PR_VOLM;
			$PR_TOTAL 	= $rowPR->PR_TOTAL;
			$PO_VOLM 	= $rowPR->PO_VOLM;
			$PO_AMOUNT 	= $rowPR->PO_AMOUNT;
			$IR_VOLM 	= $rowPR->IR_VOLM;
			$IR_AMOUNT 	= $rowPR->IR_AMOUNT;

			$REM_VOL 	= $PR_VOLM - $PO_VOLM;
			$REM_VAL 	= $PR_TOTAL - $PO_AMOUNT;

			$s_01		= "UPDATE tbl_joblist_report SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND PERIODE = '$PR_DATE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_01);
			
			$s_02		= "UPDATE tbl_joblist_detail SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_02);
			
			$s_03		= "UPDATE tbl_item SET PR_VOL = PR_VOL-$REM_VOL, PR_VAL = PR_VAL-$REM_VAL
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($s_03);
		endforeach;		
	}
	
	function deletePO($CollID) // OK
	{
		$splitCode 	= explode("~", $CollID);
		$DOC_CATEG	= $splitCode[0];
		$PR_NUM		= $splitCode[1];
		$PRJCODE	= $splitCode[2];
		// 1. COPY TO tbl_pr_header_trash
			//$sqlqg11	= "INSERT INTO tbl_pr_header_trash SELECT * FROM tbl_pr_header WHERE PR_NUM = '$PR_NUM'";
			//$this->db->query($sqlqg11);
			
			$sqlqg12	= "DELETE FROM tbl_pr_header WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg12);
			
		// 2. COPY TO tbl_pr_detail_trash
			/*$sqlqg13	= "INSERT INTO tbl_pr_detail_trash SELECT * FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg13);
			
			$sqlqg14	= "DELETE FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg14);*/
			
			return site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	}
	
	function deletePO_fpa($CollID) // OK
	{
		$splitCode 	= explode("~", $CollID);
		$DOC_CATEG	= $splitCode[0];
		$PR_NUM		= $splitCode[1];
		$PRJCODE	= $splitCode[2];
		// 1. COPY TO tbl_pr_header_trash
			//$sqlqg11	= "INSERT INTO tbl_pr_header_trash SELECT * FROM tbl_pr_header WHERE PR_NUM = '$PR_NUM'";
			//$this->db->query($sqlqg11);
			
			$sqlqg12	= "DELETE FROM tbl_pr_header_fpa WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg12);
			
		// 2. COPY TO tbl_pr_detail_trash
			/*$sqlqg13	= "INSERT INTO tbl_pr_detail_trash SELECT * FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg13);
			
			$sqlqg14	= "DELETE FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg14);*/
			
			return site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	}
	
	function count_all_num_rowsPRInx($PRJCODE, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "tbl_pr_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PR_STAT IN (2,7)"; // Only Confirm Stat (2)
		}
		else
		{
			$sql = "tbl_pr_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PR_STAT IN (2,7)
						AND (PR_NUM LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$key%' ESCAPE '!' OR PR_DATE LIKE '%$key%' ESCAPE '!')"; // Only Confirm Stat (2)
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_PRInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC, A.PR_CATEG,
						A.PR_NOTE, A.PR_STAT, PR_MEMO, A.PR_REFNO, A.PR_CREATER, A.PR_ISCLOSE, A.PR_POTOT,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_pr_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND PR_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT AA.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE, A.JOBDESC, A.PR_CATEG,
						A.PR_NOTE, A.PR_STAT, PR_MEMO, A.PR_REFNO, A.PR_CREATER, A.PR_ISCLOSE, A.PR_POTOT,
						A.STATDESC, A.STATCOL, A.CREATERNM,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number
					FROM tbl_pr_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND PR_STAT IN (2,7)
						AND (PR_NUM LIKE '%$key%' ESCAPE '!' OR PR_CODE LIKE '%$key%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$key%' ESCAPE '!' OR PR_DATE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function updateJobDet($PR_NUM, $PRJCODE) // OK
	{				
		$sqlGetPR	= "SELECT PR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, PR_VOLM, PR_PRICE
						FROM tbl_pr_detail
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowRP) :
			$PR_NUM 	= $rowRP->PR_NUM;
			$JOBCODEDET	= $rowRP->JOBCODEDET;
			$JOBCODEID	= $rowRP->JOBCODEID;
			$ITM_CODE	= $rowRP->ITM_CODE;
			$PR_VOLM	= $rowRP->PR_VOLM;
			$PR_PRICE	= $rowRP->PR_PRICE;
			$PR_TOTAMN	= $PR_VOLM * $PR_PRICE;
				
			// UPDATE JOB DETAIL
			$ITM_CODE_H	= $ITM_CODE;
			$ITM_TYPE	= 'PRM';
			$sqlITYPE	= "SELECT ITM_CODE_H, ITM_TYPE FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITYPE	= $this->db->query($sqlITYPE)->result();
			foreach($resITYPE as $rowITYPE) :
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
				if($ITM_TYPE == 'SUBS')
				{
					$ITM_CODE_H	= $rowITYPE->ITM_CODE_H;
					$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
				}
			endforeach;
			
			// UPDATE JOBHEADER, UPDATE VOLUME REQ PEKERJAAN YANG DIPILIH
			$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$REQ_VOLM 	= $rowJD->REQ_VOLM;
				$REQ_AMOUNT	= $rowJD->REQ_AMOUNT;
			endforeach;
			if($REQ_VOLM == '')
				$REQ_VOLM = 0;
			if($REQ_AMOUNT == '')
				$REQ_AMOUNT = 0;
			
			if($ITM_TYPE == 'PRM')
			{
				/*$totREQQty	= $REQ_VOLM + $PR_VOLM;
				$totREQAmn	= $REQ_AMOUNT + $PR_TOTAMN;*/
				$sqlUpd		= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM + $PR_VOLM, REQ_AMOUNT = REQ_AMOUNT + $PR_TOTAMN
								WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpd);
			}
			else
			{			
				// UPDATE JOBHEADERDETAIL, UPDATE VOLUME REQ PEKERJAAN DENGAN ITEM YANG DIPILIH
				$REQ_VOLM	= 0;
				$REQ_AMOUNT	= 0;
				$sqlGetJD		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE_H'";
				$resGetJD		= $this->db->query($sqlGetJD)->result();
				foreach($resGetJD as $rowJD) :
					$REQ_VOLM 	= $rowJD->REQ_VOLM;
					$REQ_AMOUNT	= $rowJD->REQ_AMOUNT;
				endforeach;
				if($REQ_VOLM == '')
					$REQ_VOLM = 0;
				if($REQ_AMOUNT == '')
					$REQ_AMOUNT = 0;
					
				$totREQQty	= $REQ_VOLM + $PR_VOLM;
				$totREQAmn	= $REQ_AMOUNT + $PR_TOTAMN;
				$sqlUpd		= "UPDATE tbl_joblist_detail SET REQ_VOLM = $totREQQty, REQ_AMOUNT = $totREQAmn
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE_H'";
				$this->db->query($sqlUpd);
			}
			
			// UPDATE tbl_item
			$sqlUpd2	= "UPDATE tbl_item SET PR_VOLM = PR_VOLM + $PR_VOLM, PR_AMOUNT = PR_AMOUNT + $PR_TOTAMN
							WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
	}
	
	function count_all_num_rowsVend() // USED
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function viewvendor() // USED
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier
				ORDER BY SPLDESC ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsDept()
	{
		return $this->db->count_all('tdepartment');
	}
	
	function viewDepartment()
	{
		$this->db->select('Dept_ID, Dept_Name');
		$this->db->from('tdepartment');
		$this->db->order_by('Dept_Name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function viewAllPR()
	{				
		$sql = "SELECT A.MR_Number, A.MR_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tproject_mrheader A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.MR_Number";
		return $this->db->query($sql);
	}
	
	function update_inbox($SPPNUM, $projMatReqH) // USED
	{
		$this->db->where('SPPNUM', $SPPNUM);
		$this->db->update('tbl_pr_header', $projMatReqH);
	}
	
	function delete($MR_Number)
	{
		$this->db->where('MR_Number', $MR_Number);
		$this->db->delete($this->table);
	}
	
	// remarks by DH on March, 6 2014
	/*function viewAllItem()
	{
		$this->db->select('Item_Code, Item_Name, Item_Qty, Unit_Type_ID');
		$this->db->from('titem');
		$this->db->order_by('Item_Code', 'asc');
		return $this->db->get();
	}*/
	
	// add by DH on March, 6 2014
	function viewAllItem()
	{
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Item_Qty2, A.Unit_Type_ID1, A.Unit_Type_ID2, B.Unit_Type_Name, A.itemConvertion
				FROM titem A
				INNER JOIN tbl_unittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}
	
	function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all('tbl_docpattern');
	}
	
	// Add by DH on March, 7 2014
	function count_all_num_rows_inbox()
	{
		/*$sql	= 	"SELECT count(*)
					FROM TPO_Header
					WHERE Approval_Status NOT IN (3,4,5)";
		return $this->db->count_all($sql);*/
		$this->db->where('Approval_Status', 0);
		$this->db->where('Approval_Status', 1);
		$this->db->where('Approval_Status', 2);
		return $this->db->count_all('TPO_Header');
	}
	
	function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.MR_Number, A.PR_Date, A.Approval_Status, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.MR_Number";
		
		/*$this->db->select('MR_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		$this->db->from('TPO_Header');
		$this->db->order_by('PR_Date', 'asc');*/
		//$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
	
	function get_all_prjInbox($limit, $offset, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql 		= "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT FROM tbl_project A
							INNER JOIN	tbl_pr_header D ON A.PRJCODE = D.PRJCODE
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		/*$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_pr_header D ON A.PRJCODE = D.PRJCODE
				ORDER BY A.PRJCODE";*/
		return $this->db->query($sql);
	}
	
	function get_all_prjInbox_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_pr_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function get_all_prjInbox_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_pr_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	// Update Project Plan Material
	function updatePP($SPPNUM, $parameters) // USED
	{
		$PRJCODE 	= $parameters['PRJCODE'];
    	$SPPNUM 	= $parameters['SPPNUM'];
		$SPPCODE 	= $parameters['SPPCODE'];
		$CSTCODE 	= $parameters['CSTCODE'];
		$SPPVOLM 	= $parameters['SPPVOLM'];
				
		$sqlGet		= "SELECT A.request_qty, A.request_qty2
						FROM tbl_projplan_material A
						WHERE A.PRJCODE = '$PRJCODE' AND A.CSTCODE = '$CSTCODE'";
		$resREQPlan	= $this->db->query($sqlGet)->result();
		foreach($resREQPlan as $rowRP) :
			$request_qty 	= $rowRP->request_qty;
			$request_qty2 	= $rowRP->request_qty2;
		endforeach;
		$totMRQty1	= $request_qty + $SPPVOLM;
		$totMRQty2	= $request_qty2 + $SPPVOLM;
		$sqlUpd		= "UPDATE tbl_projplan_material SET request_qty = $totMRQty1, request_qty2 = $totMRQty2
						WHERE PRJCODE = '$PRJCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sqlUpd);
	}
	
	function count_all_PO($PR_NUM) // OK
	{
		$sql	= "tbl_po_header A
						INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
							AND B.PR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PR_NUM = '$PR_NUM'";
		return $this->db->count_all($sql);
	}
	
	function get_all_PO($PR_NUM) // OK
	{
		$sql 	= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PR_NUM, A.SPLCODE, A.PO_NOTES, A.PO_STAT, B.PR_DATE, A.PO_DUED, C.SPLDESC
					FROM tbl_po_header A
						INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
							AND B.PR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PR_NUM = '$PR_NUM'";
		return $this->db->query($sql);
	}
	
	function updREJECT($PR_NUM, $PRJCODE) // G
	{
		$sqlPR	= "SELECT JOBCODEID, ITM_CODE, PR_VOLM, PR_TOTAL
					FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$resPR	= $this->db->query($sqlPR)->result();
		foreach($resPR as $rowPR) :
			$JOBCODEID	= $rowPR->JOBCODEID;
			$ITM_CODE	= $rowPR->ITM_CODE;
			$PR_VOLM	= $rowPR->PR_VOLM;
			$PR_TOTAL	= $rowPR->PR_TOTAL;
			
			// RESET PR TABLE
				$sqlPR	= "UPDATE tbl_pr_detail SET PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0
							WHERE PR_NUM = '$PR_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlPR);
			
			// RETURN BUDGET QTY IN JOBLIST
				$sqlJLD	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM - $PR_VOLM, REQ_AMOUNT = REQ_AMOUNT - $PR_TOTAL
							WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlJLD);
			
			// RETURN BUDGET QTY IN MASTER ITEM
				$sqlITM	= "UPDATE tbl_item SET PR_VOLM = PR_VOLM - $PR_VOLM, PR_AMOUNT = PR_AMOUNT - $PR_TOTAL
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlITM);
		endforeach;
	}
	
	function get_AllDataITMC($PRJCODE, $search, $length, $start) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
						A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
						A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
						A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
						A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
					FROM tbl_joblist_detail_$PRJCODEVW A
					WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		}
		else
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
						A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
						A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
						A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
						A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
					FROM tbl_joblist_detail_$PRJCODEVW A
					WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A 
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMTC($PRJCODE, $search, $length, $start) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
						A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
						A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
						A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
						A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
					FROM tbl_joblist_detail_$PRJCODEVW A
					WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
						OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		}
		else
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
						A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
						A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
						A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
						A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
					FROM tbl_joblist_detail_$PRJCODEVW A
					WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
						OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataITMTL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A 
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.JOBDESC LIKE '%$search%' ESCAPE '!'
							OR A.ITM_CODE LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMSC($PRJCODE, $search, $length, $start) // G
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
						A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
						B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
						B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
						B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
					FROM tbl_item_$PRJCODEVW A LEFT JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
					WHERE A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
						AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
						OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
						A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
						B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
						B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
						B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
					FROM tbl_item_$PRJCODEVW A LEFT JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
					WHERE A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
						AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
						OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataITMSL($PRJCODE, $search, $length, $start, $order, $dir) // G
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item_$PRJCODEVW A LEFT JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item_$PRJCODEVW A LEFT JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item_$PRJCODEVW A LEFT JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT B.ORD_ID, B.JOBCODEDET, A.JOBCODEID, B.JOBPARENT, B.JOBCODE, A.ITM_NAME AS JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, B.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, B.ITM_BUDG,
							B.ADD_VOLM, B.ADD_PRICE, B.ADD_JOBCOST, B.REQ_VOLM, B.REQ_AMOUNT, B.ADDM_VOLM, B.ADDM_JOBCOST,
							B.PO_VOLM, B.PO_AMOUNT, B.IR_VOLM, B.IR_AMOUNT, B.ITM_USED, B.ITM_USED_AM, B.ITM_STOCK, B.ITM_STOCK_AM, 
							B.ITM_BUDG, B.IS_LEVEL, B.ISLAST
						FROM tbl_item_$PRJCODEVW A LEFT JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
						WHERE A.ITM_TYPE = 'SUBS' AND A.ITM_GROUP IN ('M') AND A.STATUS = 1 AND A.ITM_CODE != ''
							AND (A.ITM_NAME LIKE '%$search%' ESCAPE '!' OR A.ITM_CODE LIKE '%$search%' ESCAPE '!'
							OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMSCUTC($PRJCODE, $search, $length, $start) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			$sql = "SELECT DISTINCT
						A.ITM_CODE, A.ITM_NAME, A.ITM_GROUP, A.ITM_UNIT, A.ITM_CODE_H, A.ITM_TYPE,
						A.ITM_VOLMBG AS ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
						A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST,
						A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
						A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
						A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM
					FROM tbl_item_$PRJCODEVW A 
					WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')
						AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.ITM_NAME LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "SELECT DISTINCT
						A.ITM_CODE, A.ITM_NAME, A.ITM_GROUP, A.ITM_UNIT, A.ITM_CODE_H, A.ITM_TYPE,
						A.ITM_VOLMBG AS ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
						A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST,
						A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
						A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
						A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM
					FROM tbl_item_$PRJCODEVW A 
					WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')
						AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
						OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataITMSCUTL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT
							A.ITM_CODE, A.ITM_NAME, A.ITM_GROUP, A.ITM_UNIT, A.ITM_CODE_H, A.ITM_TYPE,
							A.ITM_VOLMBG AS ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST,
							A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT
							A.ITM_CODE, A.ITM_NAME, A.ITM_GROUP, A.ITM_UNIT, A.ITM_CODE_H, A.ITM_TYPE,
							A.ITM_VOLMBG AS ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST,
							A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT
							A.ITM_CODE, A.ITM_NAME, A.ITM_GROUP, A.ITM_UNIT, A.ITM_CODE_H, A.ITM_TYPE,
							A.ITM_VOLMBG AS ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST,
							A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT
							A.ITM_CODE, A.ITM_NAME, A.ITM_GROUP, A.ITM_UNIT, A.ITM_CODE_H, A.ITM_TYPE,
							A.ITM_VOLMBG AS ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP,
							A.ADDVOLM AS ADD_VOLM, A.ADDCOST AS ADD_JOBCOST,
							A.PR_VOLM AS REQ_VOLM, A.PR_AMOUNT AS REQ_AMOUNT,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
							A.UM_VOLM AS ITM_USED, A.UM_AMOUNT AS ITM_USED_AM,
							A.ITM_VOLM AS ITM_STOCK, A.ITM_TOTALP AS ITM_STOCK_AM
						FROM tbl_item A 
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.ITM_NAME LIKE '%$search%' ESCAPE '!') ORDER BY A.ITM_NAME LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMSCUT4C($PRJCODE, $ITM_CODE, $search) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		//$sql = "tbl_joblist_detail_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND ISLAST = '1'";
		$sql = "tbl_joblist_detail_$PRJCODEVW A
						WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.ISLAST = '1'
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMSCUT4L($PRJCODE, $ITM_CODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		if($length == -1)
		{
			if($order !=null)
			{
				/*$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST,
							(A.PR_VOL - A.PR_VOL_R + A.PR_CVOL - A.WO_VOL - A.WO_VOL_R + A.WO_CVOL - A.VCASH_VOL - A.VCASH_VOL_R - A.VLK_VOL - A.VLK_VOL_R - A.PPD_VOL - A.PPD_VOL_R + A.AMD_VOL - A.AMDM_VOL) AS REQ_VOL,
							(A.PO_VAL - A.PO_VAL_R + A.PO_CVAL - A.WO_VAL - A.WO_VAL_R + A.WO_CVAL - A.VCASH_VAL - A.VCASH_VAL_R - A.VLK_VAL - A.VLK_VAL_R - A.PPD_VAL - A.PPD_VAL_R + A.AMD_VAL - A.AMDM_VAL) AS REQ_VAL,
							(A.ITM_VOLM - A.PR_VOL - A.PR_VOL_R + A.PR_CVOL - A.WO_VOL - A.WO_VOL_R + A.WO_CVOL - A.VCASH_VOL - A.VCASH_VOL_R - A.VLK_VOL - A.VLK_VOL_R - A.PPD_VOL - A.PPD_VOL_R + A.AMD_VOL - A.AMDM_VOL) AS REM_ITM_VOL,
							(A.ITM_BUDG - A.PO_VAL - A.PO_VAL_R + A.PO_CVAL - A.WO_VAL - A.WO_VAL_R + A.WO_CVAL - A.VCASH_VAL - A.VCASH_VAL_R - A.VLK_VAL - A.VLK_VAL_R - A.PPD_VAL - A.PPD_VAL_R + A.AMD_VAL - A.AMDM_VAL) AS REM_ITM_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.ISLAST = '1'
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')
							HAVING REM_ITM_VOL > 0
							ORDER BY A.JOBCODEID ASC";*/
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST,
							(A.PR_VOL + A.PR_VOL_R - A.PR_CVOL + A.WO_VOL + A.WO_VOL_R - A.WO_CVOL + A.VCASH_VOL + A.VCASH_VOL_R + A.VLK_VOL + A.VLK_VOL_R + A.PPD_VOL + A.PPD_VOL_R) AS REQ_VOL,
							(A.PO_VAL + A.PO_VAL_R - A.PO_CVAL + A.WO_VAL + A.WO_VAL_R - A.WO_CVAL + A.VCASH_VAL + A.VCASH_VAL_R + A.VLK_VAL + A.VLK_VAL_R + A.PPD_VAL + A.PPD_VAL_R ) AS REQ_VAL,
							(A.ITM_VOLM - A.PR_VOL - A.PR_VOL_R + A.PR_CVOL - A.WO_VOL - A.WO_VOL_R + A.WO_CVOL - A.VCASH_VOL - A.VCASH_VOL_R - A.VLK_VOL - A.VLK_VOL_R - A.PPD_VOL - A.PPD_VOL_R + A.AMD_VOL - A.AMDM_VOL) AS REM_ITM_VOL,
							(A.ITM_BUDG - A.PO_VAL - A.PO_VAL_R + A.PO_CVAL - A.WO_VAL - A.WO_VAL_R + A.WO_CVAL - A.VCASH_VAL - A.VCASH_VAL_R - A.VLK_VAL - A.VLK_VAL_R - A.PPD_VAL - A.PPD_VAL_R + A.AMD_VAL - A.AMDM_VAL) AS REM_ITM_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.ISLAST = '1'
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')
							HAVING ROUND(REM_ITM_VOL,2) > 0 AND ROUND(REM_ITM_VAL,2) > 0
							ORDER BY A.JOBCODEID ASC";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST,
							(A.PR_VOL + A.PR_VOL_R - A.PR_CVOL + A.WO_VOL + A.WO_VOL_R - A.WO_CVOL + A.VCASH_VOL + A.VCASH_VOL_R + A.VLK_VOL + A.VLK_VOL_R + A.PPD_VOL + A.PPD_VOL_R) AS REQ_VOL,
							(A.PO_VAL + A.PO_VAL_R - A.PO_CVAL + A.WO_VAL + A.WO_VAL_R - A.WO_CVAL + A.VCASH_VAL + A.VCASH_VAL_R + A.VLK_VAL + A.VLK_VAL_R + A.PPD_VAL + A.PPD_VAL_R ) AS REQ_VAL,
							(A.ITM_VOLM - A.PR_VOL - A.PR_VOL_R + A.PR_CVOL - A.WO_VOL - A.WO_VOL_R + A.WO_CVOL - A.VCASH_VOL - A.VCASH_VOL_R - A.VLK_VOL - A.VLK_VOL_R - A.PPD_VOL - A.PPD_VOL_R + A.AMD_VOL - A.AMDM_VOL) AS REM_ITM_VOL,
							(A.ITM_BUDG - A.PO_VAL - A.PO_VAL_R + A.PO_CVAL - A.WO_VAL - A.WO_VAL_R + A.WO_CVAL - A.VCASH_VAL - A.VCASH_VAL_R - A.VLK_VAL - A.VLK_VAL_R - A.PPD_VAL - A.PPD_VAL_R + A.AMD_VAL - A.AMDM_VAL) AS REM_ITM_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.ISLAST = '1'
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')
							HAVING ROUND(REM_ITM_VOL,2) > 0 AND ROUND(REM_ITM_VAL,2) > 0
							ORDER BY A.JOBCODEID ASC";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST,
							(A.PR_VOL + A.PR_VOL_R - A.PR_CVOL + A.WO_VOL + A.WO_VOL_R - A.WO_CVOL + A.VCASH_VOL + A.VCASH_VOL_R + A.VLK_VOL + A.VLK_VOL_R + A.PPD_VOL + A.PPD_VOL_R) AS REQ_VOL,
							(A.PO_VAL + A.PO_VAL_R - A.PO_CVAL + A.WO_VAL + A.WO_VAL_R - A.WO_CVAL + A.VCASH_VAL + A.VCASH_VAL_R + A.VLK_VAL + A.VLK_VAL_R + A.PPD_VAL + A.PPD_VAL_R ) AS REQ_VAL,
							(A.ITM_VOLM - A.PR_VOL - A.PR_VOL_R + A.PR_CVOL - A.WO_VOL - A.WO_VOL_R + A.WO_CVOL - A.VCASH_VOL - A.VCASH_VOL_R - A.VLK_VOL - A.VLK_VOL_R - A.PPD_VOL - A.PPD_VOL_R + A.AMD_VOL - A.AMDM_VOL) AS REM_ITM_VOL,
							(A.ITM_BUDG - A.PO_VAL - A.PO_VAL_R + A.PO_CVAL - A.WO_VAL - A.WO_VAL_R + A.WO_CVAL - A.VCASH_VAL - A.VCASH_VAL_R - A.VLK_VAL - A.VLK_VAL_R - A.PPD_VAL - A.PPD_VAL_R + A.AMD_VAL - A.AMDM_VAL) AS REM_ITM_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.ISLAST = '1'
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')
							HAVING ROUND(REM_ITM_VOL,2) > 0 AND ROUND(REM_ITM_VAL,2) > 0
							ORDER BY A.JOBCODEID ASC LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST,
							(A.PR_VOL + A.PR_VOL_R - A.PR_CVOL + A.WO_VOL + A.WO_VOL_R - A.WO_CVOL + A.VCASH_VOL + A.VCASH_VOL_R + A.VLK_VOL + A.VLK_VOL_R + A.PPD_VOL + A.PPD_VOL_R) AS REQ_VOL,
							(A.PO_VAL + A.PO_VAL_R - A.PO_CVAL + A.WO_VAL + A.WO_VAL_R - A.WO_CVAL + A.VCASH_VAL + A.VCASH_VAL_R + A.VLK_VAL + A.VLK_VAL_R + A.PPD_VAL + A.PPD_VAL_R ) AS REQ_VAL,
							(A.ITM_VOLM - A.PR_VOL - A.PR_VOL_R + A.PR_CVOL - A.WO_VOL - A.WO_VOL_R + A.WO_CVOL - A.VCASH_VOL - A.VCASH_VOL_R - A.VLK_VOL - A.VLK_VOL_R - A.PPD_VOL - A.PPD_VOL_R + A.AMD_VOL - A.AMDM_VOL) AS REM_ITM_VOL,
							(A.ITM_BUDG - A.PO_VAL - A.PO_VAL_R + A.PO_CVAL - A.WO_VAL - A.WO_VAL_R + A.WO_CVAL - A.VCASH_VAL - A.VCASH_VAL_R - A.VLK_VAL - A.VLK_VAL_R - A.PPD_VAL - A.PPD_VAL_R + A.AMD_VAL - A.AMDM_VAL) AS REM_ITM_VAL
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' AND A.ISLAST = '1'
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.JOBCODEID LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!')
							HAVING ROUND(REM_ITM_VOL,2) > 0 AND ROUND(REM_ITM_VAL,2) > 0
							ORDER BY A.JOBCODEID ASC LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMOC($PRJCODE, $search, $length, $start) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
					A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
					A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
					A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
					A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
				FROM tbl_joblist_detail_$PRJCODEVW A
				WHERE ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		}
		else
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
					A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
					A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
					A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
					A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
				FROM tbl_joblist_detail_$PRJCODEVW A
				WHERE ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataITMOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A 
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE ITM_GROUP NOT IN ('M','T') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllJob($PRJCODE)
	{
		$hasil=$this->db->query("SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE'");
        return $hasil->result();
	}
	
	function get_AllDataITMDETC($PRJCODE, $PR_NUM, $tblPRH, $tblPRD, $task, $search, $length, $start) // GOOD
	{
		## Custom Field value
		$searchByITEM 	= $this->input->post('ITM_SRC');
		$searchByPRDESC = $this->input->post('PRDESC_SRC');

		## Search 
		$searchQuery = " ";
		if($searchByITEM != '')
		{
			$searchQuery .= " AND ITM_CODE = '".$searchByITEM."'";
		}

		if($searchByPRDESC != '')
		{
			$searchQuery .= " AND PR_DESC_ID = '".$searchByPRDESC."'";
		}

		if($length == -1)
		{
			$s_sql 	= "SELECT COUNT(*) AS TOT_PR
						FROM (SELECT A.* FROM $tblPRD A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT B.* FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!')) as T1";
			$r_sql 	= $this->db->query($s_sql)->result();
		}
		else
		{
			$s_sql 	= "SELECT COUNT(*) AS TOT_PR
						FROM (SELECT A.* FROM $tblPRD A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT B.* FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!')) as T1 LIMIT $start, $length";
			$r_sql 	= $this->db->query($s_sql)->result();
		}
		foreach($r_sql as $rw_sql):
			$TOT_PR 	= $rw_sql->TOT_PR;
		endforeach;
		if($TOT_PR == '')
			$TOT_PR 	= 0;

		return $TOT_PR;
	}
	
	function get_AllDataITMDETL($PRJCODE, $PR_NUM, $tblPRH, $tblPRD, $task, $search, $length, $start, $order, $dir) // GOOD
	{
		## Custom Field value
		$searchByITEM 	= $this->input->post('ITM_SRC');
		$searchByPRDESC = $this->input->post('PRDESC_SRC');

		## Search 
		$searchQuery = " ";
		if($searchByITEM != '')
		{
			$searchQuery .= " AND ITM_CODE = '".$searchByITEM."'";
		}

		if($searchByPRDESC != '')
		{
			$searchQuery .= " AND PR_DESC_ID = '".$searchByPRDESC."'";
		}
		
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM $tblPRD A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM $tblPRD A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM $tblPRD A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM $tblPRD A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataDESCC($ITM_CODE, $search) // GOOD
	{
		$sql = "tbl_desc WHERE DESC_NOTES LIKE '%$search%' ESCAPE '!' AND (ITM_CODE = '$ITM_CODE' OR ITM_CODE = 'All')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataDESCL($ITM_CODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql 	= "SELECT * FROM tbl_desc WHERE DESC_NOTES LIKE '%$search%' ESCAPE '!' AND (ITM_CODE = '$ITM_CODE' OR ITM_CODE = 'All')
							ORDER BY $order $dir";
			}
			else
			{
				$sql 	= "SELECT * FROM tbl_desc WHERE DESC_NOTES LIKE '%$search%' ESCAPE '!' AND (ITM_CODE = '$ITM_CODE' OR ITM_CODE = 'All')
							ORDER BY DESC_NOTES";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql 	= "SELECT * FROM tbl_desc WHERE DESC_NOTES LIKE '%$search%' ESCAPE '!' AND (ITM_CODE = '$ITM_CODE' OR ITM_CODE = 'All')
							ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql 	= "SELECT * FROM tbl_desc WHERE DESC_NOTES LIKE '%$search%' ESCAPE '!' AND (ITM_CODE = '$ITM_CODE' OR ITM_CODE = 'All')
							ORDER BY DESC_NOTES LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function uplDOC_TRX($uplFile)
	{
		$this->db->insert("tbl_upload_doctrx", $uplFile);
	}

	function delUPL_DOC($PR_NUM, $PRJCODE, $fileName)
	{
		$this->db->delete("tbl_upload_doctrx", ["REF_NUM" => $PR_NUM, "PRJCODE" => $PRJCODE, "UPL_FILENAME" => $fileName]);
	}

	function getITM_SRC($PRJCODE, $PR_NUM)
	{
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		$sql = "SELECT ITM_CODE, ITM_NAME FROM tbl_item_$PRJCODEVW
				WHERE ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_pr_detail WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM'
									UNION ALL
									SELECT DISTINCT ITM_CODE FROM tbl_pr_detail_tmp WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM')";
		return $this->db->query($sql);
	}

	function getPRDESC_SRC($PRJCODE, $PR_NUM, $ITM_CODE)
	{
		$sql = "SELECT DISTINCT PR_DESC_ID, PR_DESC, ITM_CODE FROM tbl_pr_detail 
				WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM' AND ITM_CODE = '$ITM_CODE'
				UNION ALL
				SELECT DISTINCT PR_DESC_ID, PR_DESC, ITM_CODE FROM tbl_pr_detail_tmp 
				WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$PR_NUM' AND ITM_CODE = '$ITM_CODE'";
		return $this->db->query($sql);
	}
	
	function get_AllDataGRPC_fpa($PRJCODE, $PO_NUM, $PR_STAT, $search, $length, $start) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";

		if($PR_STAT != 0)
			$ADDQRY1 	= "AND A.PR_STAT = '$PR_STAT'";

		if($length == -1)
		{
			$sql 	= "SELECT A.*
						FROM tbl_pr_header_fpa A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql 	= "SELECT A.*
						FROM tbl_pr_header_fpa A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
							OR STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}
		
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataGRPL_fpa($PRJCODE, $PO_NUM, $PR_STAT, $search, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";

		if($PR_STAT != 0)
			$ADDQRY1 	= "AND A.PR_STAT = '$PR_STAT'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header_fpa A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header_fpa A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header_fpa A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_pr_header_fpa A 
						WHERE A.PRJCODE = '$PRJCODE' $ADDQRY1 $ADDQRY2
							AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
							OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!'
						 	OR STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMDETC_fpa($PRJCODE, $PR_NUM, $task, $search, $length, $start) // GOOD
	{
		## Custom Field value
		$searchByITEM 	= $this->input->post('ITM_SRC');
		$searchByPRDESC = $this->input->post('PRDESC_SRC');

		## Search 
		$searchQuery = " ";
		if($searchByITEM != '')
		{
			$searchQuery .= " AND ITM_CODE = '".$searchByITEM."'";
		}

		if($searchByPRDESC != '')
		{
			$searchQuery .= " AND PR_DESC_ID = '".$searchByPRDESC."'";
		}

		if($length == -1)
		{
			$s_sql 	= "SELECT COUNT(*) AS TOT_PR
						FROM (SELECT A.* FROM tbl_pr_detail_fpa A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT B.* FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!')) as T1";
			$r_sql 	= $this->db->query($s_sql)->result();
		}
		else
		{
			$s_sql 	= "SELECT COUNT(*) AS TOT_PR
						FROM (SELECT A.* FROM tbl_pr_detail_fpa A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT B.* FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!')) as T1 LIMIT $start, $length";
			$r_sql 	= $this->db->query($s_sql)->result();
		}
		foreach($r_sql as $rw_sql):
			$TOT_PR 	= $rw_sql->TOT_PR;
		endforeach;
		if($TOT_PR == '')
			$TOT_PR 	= 0;

		return $TOT_PR;
	}
	
	function get_AllDataITMDETL_fpa($PRJCODE, $PR_NUM, $task, $search, $length, $start, $order, $dir) // GOOD
	{
		## Custom Field value
		$searchByITEM 	= $this->input->post('ITM_SRC');
		$searchByPRDESC = $this->input->post('PRDESC_SRC');

		## Search 
		$searchQuery = " ";
		if($searchByITEM != '')
		{
			$searchQuery .= " AND ITM_CODE = '".$searchByITEM."'";
		}

		if($searchByPRDESC != '')
		{
			$searchQuery .= " AND PR_DESC_ID = '".$searchByPRDESC."'";
		}
		
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM tbl_pr_detail_fpa A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM tbl_pr_detail_fpa A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM tbl_pr_detail_fpa A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.DEPCODE, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBPARDESC,
							A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.PR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, A.PO_VOLM, A.ITM_VOLMBG, A.ITM_BUDG, A.PR_STAT,
							A.PR_CVOL
						FROM tbl_pr_detail_fpa A WHERE A.PR_NUM = '$PR_NUM' AND A.PRJCODE = '$PRJCODE' $searchQuery
							AND (A.ITM_CODE LIKE '%$search%' ESCAPE '!' OR A.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR A.PR_DESC LIKE '%$search%' ESCAPE '!')
						UNION ALL
						SELECT DISTINCT B.PR_ID, B.PR_NUM, B.PR_CODE, B.PR_DATE, B.PRJCODE, B.DEPCODE, B.JOBCODEDET, B.JOBCODEID, B.JOBPARENT, B.JOBPARDESC,
							B.ITM_CODE, B.ITM_NAME, B.ITM_UNIT, B.PR_VOLM, B.PR_PRICE, B.PR_TOTAL, B.PR_DESC_ID, B.PR_DESC, B.PO_VOLM, B.ITM_VOLMBG, B.ITM_BUDG, B.PR_STAT,
							B.PR_CVOL
						FROM tbl_pr_detail_tmp B WHERE B.PR_NUM = '$PR_NUM' AND B.PRJCODE = '$PRJCODE' $searchQuery
							AND (B.ITM_CODE LIKE '%$search%' ESCAPE '!' OR B.ITM_NAME LIKE '%$search%' ESCAPE '!'
							OR B.PR_DESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2_fpa($PRJCODE, $search, $length, $start) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			//$ADDQRY1 = "AND DEPCODE = '$DEPCODE'"; // sementara di hide dulu ya 29 06 2020
			$ADDQRY1 = "";
		}

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header_fpa A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 1
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!')";
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header_fpa A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 1
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
		}

		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataL_1n2_fpa($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		$DEPCODE 	= $this->session->userdata['DEPCODE'];
		$SETDPURCH 	= $this->session->userdata['SETDPURCH'];
		$setDEP		= $SETDPURCH;
		$ADDQRY1 	= "";
		if($setDEP == 1)
		{
			//$ADDQRY1 = "AND DEPCODE = '$DEPCODE'"; // sementara di hide dulu ya 29 06 2020
			$ADDQRY1 = "";
		}

		if($length == -1)
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header_fpa A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 1
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			return $this->db->query($sql);
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_pr_header_fpa A 
					WHERE A.PRJCODE = '$PRJCODE' AND PR_STAT IN (2,7) $ADDQRY1 AND A.PR_CATEG = 1
						AND (PR_NUM LIKE '%$search%' ESCAPE '!' OR PR_CODE LIKE '%$search%' ESCAPE '!' 
						OR PR_NOTE LIKE '%$search%' ESCAPE '!' OR PR_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMALLC($PRJCODE, $ITMCAT, $search, $length, $start) // GOOD
	{
		if($ITMCAT == 'O')
			$QRY01 	= "ITM_GROUP IN ('O') AND";
		elseif($ITMCAT == 'M')
			$QRY01 	= "ITM_GROUP IN ('M','T') AND";
		elseif($ITMCAT == 'T')
			$QRY01 	= "ITM_GROUP IN ('T') AND";

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
					A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
					A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
					A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
					A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
				FROM tbl_joblist_detail_$PRJCODEVW A
				WHERE $QRY01 A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		}
		else
		{
			$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
					A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
					A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
					A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
					A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
				FROM tbl_joblist_detail_$PRJCODEVW A
				WHERE $QRY01 A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
		}
		// return $this->db->count_all($sql);
		return $this->db->query($sql)->num_rows();
	}
	
	function get_AllDataITMALLL($PRJCODE, $ITMCAT, $search, $length, $start, $order, $dir) // GOOD
	{
		if($ITMCAT == 'O')
			$QRY01 	= "ITM_GROUP IN ('O') AND";
		elseif($ITMCAT == 'M')
			$QRY01 	= "ITM_GROUP IN ('M','T') AND";
		elseif($ITMCAT == 'T')
			$QRY01 	= "ITM_GROUP IN ('T') AND";

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A 
						WHERE $QRY01 A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE $QRY01 A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE $QRY01 A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail_$PRJCODEVW A
						WHERE $QRY01 A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>