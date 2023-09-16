<?php
	/* 
		* Author		= Dian Hermanto
		* Create Date	= 10 Februari 2020
		* File Name		= M_gej.php
		* Location		= -
	*/

	class M_gej extends CI_Model
	{
		public function __construct() // GOOD
		{
			parent::__construct();
			$this->load->database();
		}
		
		function get_AllDataC($PRJCODE, $search, $jrnCat) // GOOD
		{
			if($jrnCat == 'GEJ')					// General Journal
				$jrnCATCODE	 = "'GEJ'";
			elseif($jrnCat == 'PRJ')				// Project
				$jrnCATCODE	 = "'PRJINV'";
			elseif($jrnCat == 'SAL')				// Sales
				$jrnCATCODE	 = "'SINV'";
			elseif($jrnCat == 'INVT')				// Acceptance / Use of Materials
				$jrnCATCODE	 = "'UM','IR'";
			elseif($jrnCat == 'PROD')				// Production
				$jrnCATCODE	 = "'STF'";
			elseif($jrnCat == 'FIN')				// Acceptance / Use of MaterialsFinance and Accounting
				$jrnCATCODE  = "'PINV', 'BP', 'BR', 'CHO', 'VCASH', 'TTK-D'";
			elseif($jrnCat == 'OPN')				// Opname
				$jrnCATCODE	 = "'OPN'";
			elseif($jrnCat == 'LPM')				// LPM
				$jrnCATCODE	 = "'IR'";

			$sql = "tbl_journaldetail A
						INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
					WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = '3' AND A.JournalType IN ($jrnCATCODE)
						AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
						OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
						OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
						OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
						OR A.Other_Desc LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir, $jrnCat) // GOOD
		{
			if($jrnCat == 'GEJ')					// General Journal
				$jrnCATCODE	 = "'GEJ'";
			elseif($jrnCat == 'PRJ')				// Project
				$jrnCATCODE	 = "'PRJINV'";
			elseif($jrnCat == 'SAL')				// Sales
				$jrnCATCODE	 = "'SINV'";
			elseif($jrnCat == 'INVT')				// Acceptance / Use of Materials
				$jrnCATCODE	 = "'UM','IR'";
			elseif($jrnCat == 'PROD')				// Production
				$jrnCATCODE	 = "'STF'";
			elseif($jrnCat == 'FIN')				// Acceptance / Use of MaterialsFinance and Accounting
				$jrnCATCODE  = "'PINV', 'BP', 'BR', 'CHO', 'VCASH', 'TTK-D'";
			elseif($jrnCat == 'OPN')				// Opname
				$jrnCATCODE	 = "'OPN'";
			elseif($jrnCat == 'LPM')				// LPM
				$jrnCATCODE	 = "'IR'";

			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!') ORDER BY B.LastUpdate DESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code
							FROM tbl_journaldetail A
								INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
							WHERE A.proj_Code = '$PRJCODE' AND B.GEJ_STAT = 3 AND A.JournalType IN ($jrnCATCODE)
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!') ORDER BY B.LastUpdate DESC
							LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}

		function get_AllDataAC_230627($PRJCODE, $ACCID, $search, $STARTD, $ENDD, $GEJSTAT) // GOOD
		{
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			$TBLNMH 	 	= "tbl_journalheader_$PRJCODEVW";
			$TBLNMD 	 	= "tbl_journaldetail_$PRJCODEVW";
			$QRYPRJ 		= "AND A.proj_Code = '$PRJCODE'";

			if($PRJCODE == 'All')
			{
				$QRYPRJ	 	= "";
				$TBLNMH 	= "tbl_journalheader";
				$TBLNMD 	= "tbl_journaldetail";
			}

			if($ACCID == 'All')
			{
				$QRYPRJACC 	= "";
			}
			else
			{
				$QRYPRJACC 	= "AND A.Acc_Id = '$ACCID'";
			}

			$QRYSTAT = "";
			if($GEJSTAT == 3)
				$QRYSTAT = "A.GEJ_STAT = 3 AND";
			elseif($GEJSTAT == 9)
				$QRYSTAT = "A.GEJ_STAT = 9 AND";

			$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			$sql = "$TBLNMD A
						WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ $QRYPRJACC
						AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!' OR A.Other_Desc LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataAL_230627($PRJCODE, $ACCID, $search, $length, $start, $order, $dir, $STARTD, $ENDD, $GEJSTAT) // GOOD
		{
			$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			$TBLNMH 	 	= "tbl_journalheader_$PRJCODEVW";
			$TBLNMD 	 	= "tbl_journaldetail_$PRJCODEVW";
			$QRYPRJ 		= "AND A.proj_Code = '$PRJCODE'";
			if($PRJCODE == 'All')
			{
				$QRYPRJ	 	= "";
				$TBLNMH 	 = "tbl_journalheader";
				$TBLNMD 	 = "tbl_journaldetail";
			}

			if($ACCID == 'All')
			{
				$QRYPRJACC 	= "";
			}
			else
			{
				$QRYPRJACC 	= "AND A.Acc_Id = '$ACCID'";
			}

			$QRYSTAT = "";
			if($GEJSTAT == 3)
				$QRYSTAT = "A.GEJ_STAT = 3 AND";
			elseif($GEJSTAT == 9)
				$QRYSTAT = "A.GEJ_STAT = 9 AND";

			$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			if($length == -1)
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
								INNER JOIN $TBLNMH B ON B.JournalH_Code = A.JournalH_Code
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!' OR A.JournalType LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir";*/
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ $QRYPRJACC
								AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!' OR A.Other_Desc LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!')
							ORDER BY A.JournalH_Date DESC";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ $QRYPRJACC
								AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!' OR A.Other_Desc LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!')
							ORDER BY A.JournalH_Date DESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ $QRYPRJACC
								AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!' OR A.Other_Desc LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!')
							ORDER BY A.JournalH_Date DESC LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ $QRYPRJACC
								AND (A.Manual_No LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!' OR A.Other_Desc LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!')
							ORDER BY A.JournalH_Date DESC LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		
		function get_AllDataAC($search, $STARTD, $ENDD, $GEJSTAT) // GOOD
		{
			$TBLNMH 	= "tbl_journalheader";
			$TBLNMD 	= "tbl_journaldetail";

			## Custom Field value
			$PRJCODE 			= $this->input->get('PRJCODE');
			$ACCID 				= $this->input->get('ACCID');
			$TYPE_JRN 			= $this->input->get('TYPE_JRN');
			$Manual_No 			= $this->input->get('Manual_No');
			$JournalD_Debet 	= $this->input->get('JournalD_Debet');
			$JournalD_Kredit 	= $this->input->get('JournalD_Kredit');

			## Search 
			$addQuery = "";
			if($GEJSTAT != '')
			{
				$GEJSTAT 	= $this->input->get('GEJSTAT');
				$addQuery .= " AND A.GEJ_STAT = '".$GEJSTAT."'";
			}
			else
			{
				$addQuery .= " AND A.GEJ_STAT = '".$GEJSTAT."'";
			}

			if($STARTD != '' && $ENDD != '')
			{
				$STARTD 	= $this->input->get('STARTD');
				$ENDD 		= $this->input->get('ENDD');
				$STARTD		= date('Y-m-d',strtotime(str_replace('/', '-', $STARTD)));
				$ENDD		= date('Y-m-d',strtotime(str_replace('/', '-', $ENDD)));
				$addQuery .= " AND A.JournalH_Date BETWEEN '".$STARTD."' AND '".$ENDD."'";
			}
			else
			{
				$addQuery .= " AND A.JournalH_Date BETWEEN '".$STARTD."' AND '".$ENDD."'";
			}

			if($PRJCODE != '')
			{
				$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
				$TBLNMH 	= "tbl_journalheader_$PRJCODEVW";
				$TBLNMD 	= "tbl_journaldetail_$PRJCODEVW";
			}

			if($ACCID != '')
			{
				$addQuery .= " AND A.Acc_Id = '".$ACCID."'";
			}

			if($TYPE_JRN != '')
			{
				$addQuery .= " AND A.JournalType = '".$TYPE_JRN."'";
			}

			if($Manual_No != '')
			{
				$addQuery .= " AND A.Manual_No LIKE '%".$Manual_No."%'";
			}

			if($JournalD_Debet != '')
			{
				$addQuery .= " AND A.JournalD_Debet LIKE '%".$JournalD_Debet."%'";
			}

			if($JournalD_Kredit != '')
			{
				$addQuery .= " AND A.JournalD_Kredit LIKE '%".$JournalD_Kredit."%'";
			}

			$sql = "$TBLNMD A
						WHERE A.Manual_No LIKE '%$search%' ESCAPE '!'
							$addQuery";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataAL($search, $length, $start, $order, $dir, $STARTD, $ENDD, $GEJSTAT) // GOOD
		{
			$TBLNMH 	= "tbl_journalheader";
			$TBLNMD 	= "tbl_journaldetail";

			## Custom Field value
			$PRJCODE 	= $this->input->get('PRJCODE');
			$ACCID 		= $this->input->get('ACCID');
			$TYPE_JRN 	= $this->input->get('TYPE_JRN');
			$Manual_No 	= $this->input->get('Manual_No');
			$JournalD_Debet 	= $this->input->get('JournalD_Debet');
			$JournalD_Kredit 	= $this->input->get('JournalD_Kredit');

			## Search 
			$addQuery = "";
			if($GEJSTAT != '')
			{
				$GEJSTAT 	= $this->input->get('GEJSTAT');
				$addQuery .= " AND A.GEJ_STAT = '".$GEJSTAT."'";
			}
			else
			{
				$addQuery .= " AND A.GEJ_STAT = '".$GEJSTAT."'";
			}

			if($STARTD != '' && $ENDD != '')
			{
				$STARTD 	= $this->input->get('STARTD');
				$ENDD 		= $this->input->get('ENDD');
				$STARTD		= date('Y-m-d',strtotime(str_replace('/', '-', $STARTD)));
				$ENDD		= date('Y-m-d',strtotime(str_replace('/', '-', $ENDD)));
				$addQuery .= " AND A.JournalH_Date BETWEEN '".$STARTD."' AND '".$ENDD."'";
			}
			else
			{
				$addQuery .= " AND A.JournalH_Date BETWEEN '".$STARTD."' AND '".$ENDD."'";
			}

			if($PRJCODE != '')
			{
				$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
				$TBLNMH 	= "tbl_journalheader_$PRJCODEVW";
				$TBLNMD 	= "tbl_journaldetail_$PRJCODEVW";
			}

			if($ACCID != '')
			{
				$addQuery .= " AND A.Acc_Id = '".$ACCID."'";
			}

			if($TYPE_JRN != '')
			{
				$addQuery .= " AND A.JournalType = '".$TYPE_JRN."'";
			}

			if($Manual_No != '')
			{
				$addQuery .= " AND A.Manual_No LIKE '%".$Manual_No."%'";
			}

			if($JournalD_Debet != '')
			{
				$addQuery .= " AND A.JournalD_Debet LIKE '%".$JournalD_Debet."%'";
			}

			if($JournalD_Kredit != '')
			{
				$addQuery .= " AND A.JournalD_Kredit LIKE '%".$JournalD_Kredit."%'";
			}

			if($length == -1)
			{
				if($order !=null)
				{
					/*$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
								INNER JOIN $TBLNMH B ON B.JournalH_Code = A.JournalH_Code
							WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ 
								AND (A.JournalH_Code LIKE '%$search%' ESCAPE '!' OR B.Manual_No LIKE '%$search%' ESCAPE '!'
								OR A.Acc_Id LIKE '%$search%' ESCAPE '!' OR A.Acc_Name LIKE '%$search%' ESCAPE '!'
								OR A.Base_Debet LIKE '%$search%' ESCAPE '!' OR A.Base_Kredit LIKE '%$search%' ESCAPE '!'
								OR B.JournalH_Desc LIKE '%$search%' ESCAPE '!' OR B.CREATERNM LIKE '%$search%' ESCAPE '!'
								OR A.Other_Desc LIKE '%$search%' ESCAPE '!' OR A.JournalType LIKE '%$search%' ESCAPE '!')
							ORDER BY B.LastUpdate DESC, $order $dir";*/
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE A.Manual_No LIKE '%$search%' ESCAPE '!'
								$addQuery
							ORDER BY A.JournalH_Date DESC";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE A.Manual_No LIKE '%$search%' ESCAPE '!'
								$addQuery
							ORDER BY A.JournalH_Date DESC";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE A.Manual_No LIKE '%$search%' ESCAPE '!'
								$addQuery
							ORDER BY A.JournalH_Date DESC LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType
							FROM $TBLNMD A
							WHERE A.Manual_No LIKE '%$search%' ESCAPE '!'
								$addQuery
							ORDER BY A.JournalH_Date DESC LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
	}
?>