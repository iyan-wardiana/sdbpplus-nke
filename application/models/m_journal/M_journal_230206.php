<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 April 2017
 * File Name	= m_journal.php
 * Location		= -
*/

class M_journal extends CI_Model
{
	// ---------------- START : Pembuatan Journal Header ----------------
	function createJournalH($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];

		$PERSL_EMPID		= '';
		if(isset($parameters['PERSL_EMPID']))
		{
			$PERSL_EMPID	= $parameters['PERSL_EMPID'];
		}

		$SPLCODE		= '';
		if(isset($parameters['SPLCODE']))
		{
			$SPLCODE	= $parameters['SPLCODE'];
		}

		$SPLDESC		= '';
		if(isset($parameters['SPLDESC']))
		{
			$SPLDESC	= $parameters['SPLDESC'];
		}

		$PPNH_Amount		= 0;
		if(isset($parameters['PPNH_Amount']))
		{
			$PPNH_Amount	= $parameters['PPNH_Amount'];
		}

		$PPHH_Amount		= 0;
		if(isset($parameters['PPHH_Amount']))
		{
			$PPHH_Amount	= $parameters['PPHH_Amount'];
		}

		$JournalH_Desc		= '';
		if(isset($parameters['JournalH_Desc']))
		{
			$JournalH_Desc	= $parameters['JournalH_Desc'];
		}

		$Manual_No			= '';
		if(isset($parameters['Manual_No']))
		{
			$Manual_No		= $parameters['Manual_No'];
		}
		
		$GEJ_STAT	= 2;
		if($JournalType == 'AU')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VAU')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'V-IR')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PPD')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM-SUB')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'V-UM')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VOPN')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'BP-DP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PURC-RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'ASEXP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'STF')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'SN')
			$GEJ_STAT	= 3;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source, PPNH_Amount, PPHH_Amount,
									Emp_ID, Created, LastUpdate, KursAmount_tobase, 
									Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT, Manual_No, PERSL_EMPID, SPLCODE, SPLDESC)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', '$PPNH_Amount', '$PPHH_Amount',
									'$Emp_ID', '$LastUpdate', '$LastUpdate', $KursAm_tobase, 
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$GEJ_STAT', '$Manual_No', '$PERSL_EMPID', '$SPLCODE', '$SPLDESC')";
		$this->db->query($sqlGEJH);
	}

	// ---------------- START : Pembuatan Journal Header ----------------
	function createJournalHINV($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Desc 		= $parameters['JournalH_Desc'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];

		$Manual_No		= '';
		if(isset($parameters['Manual_No']))
		{
			$Manual_No	= $parameters['Manual_No'];
		}

		$PERSL_EMPID		= '';
		if(isset($parameters['PERSL_EMPID']))
		{
			$PERSL_EMPID	= $parameters['PERSL_EMPID'];
		}

		$SPLCODE		= '';
		if(isset($parameters['SPLCODE']))
		{
			$SPLCODE	= $parameters['SPLCODE'];
		}

		$SPLDESC		= '';
		if(isset($parameters['SPLDESC']))
		{
			$SPLDESC	= $parameters['SPLDESC'];
		}
		
		$GEJ_STAT	= 2;
		if($JournalType == 'AU')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VAU')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'V-IR')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VBP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM-SUB')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VOPN')
			$GEJ_STAT	= 5;
		elseif($JournalType == 'BP-DP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PURC-RET')
			$GEJ_STAT	= 3;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Desc, JournalH_Date, Company_ID, Source,
									Emp_ID, Created, LastUpdate, KursAmount_tobase, Manual_No, PERSL_EMPID, SPLCODE, SPLDESC,
									Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Desc', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', '$LastUpdate', $KursAm_tobase, '$Manual_No', '$PERSL_EMPID', '$SPLCODE', '$SPLDESC',
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$GEJ_STAT')";
		$this->db->query($sqlGEJH);
	}
	
	function createJournalH_NEW($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];		
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$PERSL_EMPID 		= $parameters['PERSL_EMPID'];
		$SPLCODE 			= $parameters['SPLCODE'];
		$SPLDESC			= $parameters['SPLDESC'];
		if(isset($parameters['ManualNo']))
		{
			$ManualNo		= "PL1".$parameters['ManualNo'];
		}
		
		$GEJ_STAT	= 2;
		if($JournalType == 'AU')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VAU')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'V-IR')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PINV-RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM-SUB')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VOPN')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'BP-DP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'FPA-OTH')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PURC-RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'SINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'SALRET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'TTK-D')
			$GEJ_STAT	= 3;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source,
									Emp_ID, Created, LastUpdate, KursAmount_tobase, Manual_No,
									Wh_id, Reference_Number, Reference_Type, proj_Code, PERSL_EMPID, SPLCODE, SPLDESC, GEJ_STAT)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', '$LastUpdate', $KursAm_tobase, '$ManualNo',
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$PERSL_EMPID', '$SPLCODE', '$SPLDESC', '$GEJ_STAT')";
		$this->db->query($sqlGEJH);
	}
	
	function createJournalH_SALRET($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];		
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$CUSTCODE 			= $parameters['CUSTCODE'];
		$CUSTDESC 			= $parameters['CUSTDESC'];
		
		$GEJ_STAT	= 2;
		if($JournalType == 'AU')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VAU')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'V-IR')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM-SUB')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'VOPN')
			$GEJ_STAT	= 9;
		elseif($JournalType == 'BP-DP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'FPA-OTH')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PURC-RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'SINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'SALRET')
			$GEJ_STAT	= 3;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source,
									Emp_ID, Created, LastUpdate, KursAmount_tobase, 
									Wh_id, Reference_Number, Reference_Type, proj_Code, CUSTCODE, CUSTDESC, GEJ_STAT)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', '$LastUpdate', $KursAm_tobase, 
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$CUSTCODE', '$CUSTDESC', '$GEJ_STAT')";
		$this->db->query($sqlGEJH);
	}
	// ---------------- END : Pembuatan Journal Header ----------------
	
	// ---------------- START : Pembuatan Journal Header ----------------
	function updateJournalH($JournalH_Code, $prmJournal) // OK
	{
		$JournalH_Code 		= $prmJournal['JournalH_Code'];
		$GEJ_STAT 			= $prmJournal['GEJ_STAT'];
		$proj_Code 			= $prmJournal['PRJCODE'];
		
		// Update Journal Header
		$sqlGEJH 			= "UPDATE tbl_journalheader SET GEJ_STAT = $GEJ_STAT 
								WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$proj_Code'";
		$this->db->query($sqlGEJH);
		
		// Update Journal Detail
		$sqlGEJH 			= "UPDATE tbl_journaldetail SET GEJ_STAT = $GEJ_STAT
								WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$proj_Code'";
		$this->db->query($sqlGEJH);
	}
	// ---------------- END : Pembuatan Journal Header ----------------
	
	// ---------------- START : Pembuatan Journal Header - KHUSUS INVOICE ----------------
	function createJournalH_INV($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];		
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$Journal_Amount		= $parameters['Journal_Amount'];
		
		$GEJ_STAT	= 2;
		if($JournalType == 'IR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'DPP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BP')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'BR')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'UM-SUB')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PRJINV')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'OPN')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'RET')
			$GEJ_STAT	= 3;
		elseif($JournalType == 'PURC-RET')
			$GEJ_STAT	= 3;
			
		// Save Journal Header
		$sqlGEJH 			= "INSERT INTO tbl_journalheader (JournalH_Code, JournalType, JournalH_Date, Company_ID, Source,
									Emp_ID, Created, LastUpdate, KursAmount_tobase, 
									Wh_id, Reference_Number, Reference_Type, proj_Code, GEJ_STAT, Journal_Amount)
								VALUES ('$JournalH_Code', '$JournalType', '$JournalH_Date', '$Company_ID', '$Source', 
									'$Emp_ID', '$LastUpdate', '$LastUpdate', $KursAm_tobase, 
									'$Wh_id', '$REFNumb', '$RefType', '$proj_Code', '$GEJ_STAT', $Journal_Amount)";
		$this->db->query($sqlGEJH);
	}
	// ---------------- END : Pembuatan Journal Header ----------------
	
	function createJournalD($JournalH_Code, $parameters) // OK
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Currency_ID		= $parameters['Currency_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$JSource			= $parameters['JSource'];
		$TRANS_CATEG1		= $parameters['TRANS_CATEG'];
    	$Transaction_Date	= $parameters['JournalH_Date'];
    	$Item_Code 			= $parameters['ITM_CODE'];
    	$ITM_CODE 			= $parameters['ITM_CODE'];
    	$ACC_ID 			= $parameters['ACC_ID'];
    	$Qty_Plus 			= $parameters['ITM_QTY'];
    	$Item_Price 		= $parameters['ITM_PRICE'];
    	$Item_Disc 			= $parameters['ITM_DISC'];

    	$PRJCODEVW 			= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			
		$TAXCODE1			= '';
		if(isset($parameters['TAXCODE1']))
		{
			$TAXCODE1		= $parameters['TAXCODE1'];
		}

		$TAXCODE2			= '';
		if(isset($parameters['TAXCODE2']))
		{
			$TAXCODE2		= $parameters['TAXCODE2'];
		}

		$TAXPRICE1			= '';
		if(isset($parameters['TAXPRICE1']))
		{
			$TAXPRICE1		= $parameters['TAXPRICE1'];
		}

		$TAXPRICE2			= '';
		if(isset($parameters['TAXPRICE2']))
		{
			$TAXPRICE2		= $parameters['TAXPRICE2'];
		}

		$theChar		= strpos($TRANS_CATEG1,"~");
		if($theChar > 0)
		{
			$data 			= explode("~" , $TRANS_CATEG1);
			$TRANS_CATEG	= $data[0];
			$SPL_CATEG		= $data[1];

		}
		else
		{
			$TRANS_CATEG	= $TRANS_CATEG1;
			$SPL_CATEG 		= "";
		}

		$oth_reason			= '';
		if(isset($parameters['oth_reason']))
		{
			$oth_reason		= $parameters['oth_reason'];
		}

		$Ref_Number			= '';
		if(isset($parameters['Ref_Number']))
		{
			$Ref_Number		= $parameters['Ref_Number'];
		}

    	$ITM_CATEG			= '';
    	$ITM_GROUP			= '';
		$sqlL_ICAT			= "SELECT ITM_CATEG, ITM_GROUP FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resL_ICAT 			= $this->db->query($sqlL_ICAT)->result();				
		foreach($resL_ICAT as $rowL_ICAT):
			$ITM_CATEG		= $rowL_ICAT->ITM_CATEG;
			$ITM_GROUP		= $rowL_ICAT->ITM_GROUP;
		endforeach;
		
		$PRJ_isHO			= 0;
		$proj_CodeHO		= $PRJCODE;
		$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$resPRJHO			= $this->db->query($sqlPRJHO)->result();
		foreach($resPRJHO as $rowPRJHO):
			$PRJ_isHO		= $rowPRJHO->isHO;
			$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
		endforeach;
		
		$ITM_AMOUNT			= (doubleval($Qty_Plus) * doubleval($Item_Price)) - doubleval($Item_Disc);
		$AMOUNT_PPN			= 0;
		$AMOUNT_PPh			= 0;
		/*if($TAXCODE1 == 'TAX01')
			$AMOUNT_PPN		= $TAXPRICE1;
		elseif($TAXCODE1 == 'TAX02')
			$AMOUNT_PPh		= $TAXPRICE1;*/

		$sTaxPPn 	= "tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";	// Tax PPn
		$rTaxPPn	= $this->db->count_all($sTaxPPn);
		if($rTaxPPn > 0)
		{
			$AMOUNT_PPN	= $TAXPRICE1;
			$AMOUNT_PPh	= 0;
		}

		$sTaxPPh 	= "tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE2'";	// Tax PPh
		$rTaxPPh	= $this->db->count_all($sTaxPPh);
		if($rTaxPPh > 0)
		{
			$AMOUNT_PPN	= 0;
			$AMOUNT_PPh	= $TAXPRICE2;
		}
			
		$Unit_Price 		= $Item_Price;
		
		$transacValue 		= (doubleval($Qty_Plus) * doubleval($Item_Price)) - doubleval($Item_Disc);
		if($TRANS_CATEG == 'SN')
			$transacValue 	= $Qty_Plus * $Item_Price;					// TANPA DISKON KARENA DALAM SN MASIH HARGA ASLI/HPP BUKAN HARGA SO

		//echo "transacValue = $transacValue<br>";
		//echo "ACC_ID = $ACC_ID<br>";
		//return false;
		// Create on 12 Mei 2015. by. : Dian Hermanto
		
		/* ------------- PEMBENTUKAN JURNAL ---------------------
			Jurnal yang terbentuk saat IR (REC)
			10029 PERSEDIAAN											xxxx
				21008 - HUTANG USAHA YANG BELUM DIFAKTURKAN						xxxx
		
			Jurnal yang terbentuk saat PURCHASE INVOICE (PINV)
			21008 - HUTANG BELUM DIFAKTURKAN							xxxx
			20012 - PPN - Masukan										xxxx					// jika ada ppn
				20005 HUTANG SUPPLIER											xxxx
		
			Jurnal yang terbentuk saat PEMBAYARAN PINV
			20005 HUTANG SUPPLIER										xxxx
				10013 BANK ACCOUNT												xxxx
		*/
		
		// UNTUK SETTING LINK ACCOUNT
		// 1. BUAT RELASI ANTARA ITEM DENGAN COA
		// 2. BUAT RELASI ANTARA COA DENGAN TRANSAKSI. MISALNYA TRANSAKSI PENERIMAAN, PEMFAKTURAN, PEMBAYARAN DAN LAIN-LAIN
		//echo "TRANS_CATEG = $TRANS_CATEG";
		//return false;
		
		if($TRANS_CATEG == 'IR')				// IR = Item Receipt // no notes
		{
			$JOBCODEID 		= $parameters['JOBCODEID'];
			$transacValue	= ($Qty_Plus * $Item_Price) - $Item_Disc;
			$IR_AMOUNT 		= $transacValue;			
			$IR_AMOUNTnPPn	= $transacValue + $AMOUNT_PPN;
			
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_CODE 	= $parameters['ITM_CODE'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$ITM_UNIT 	= $parameters['ITM_UNIT'];
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$ITM_DISC 	= $parameters['ITM_DISC'];
				$ITM_NAME 	= $parameters['ITM_NAME'];
				$ITMNOTES 	= $parameters['ITM_NOTES'];
				$IR_NOTE 	= $parameters['IR_NOTE'];
				$IR_CODE 	= $parameters['IR_CODE'];
				$SPLCODE 	= $parameters['SPLCODE'];
				$SPLDESC 	= $parameters['SPLDESC'];

				if($ITMNOTES == '')
					$ITM_NOTES 	= "IR $ITMNOTES";
				else
					$ITM_NOTES 	= "IR $ITM_NAME";

				if($IR_NOTE == '')
					$IR_NOTE 	= "$ITMNOTES";
				
				$Manual_No 		= "";
				if(isset($parameters['ManualNo']))
					$Manual_No	= $parameters['ManualNo'];

				$PO_CODE 		= "";
				if(isset($parameters['PO_CODE']))
					$PO_CODE	= $parameters['PO_CODE'];

				$PR_CODE 		= "";
				if(isset($parameters['PR_CODE']))
					$PR_CODE	= $parameters['PR_CODE'];

				$REFNUM 		= "";
				if(isset($parameters['REFNUM']))
					$REFNUM		= $parameters['REFNUM'];

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;

				// GET PATT NUMBER
					$sqlJRN_C	= "tbl_journaldetail";
					$resJRN_C	= $this->db->count_all($sqlJRN_C);
					$JRNPatt	= $resJRN_C + 1;
				
				// ------------------------------- D E B I T : PERSEDIAAN -------------------------------
					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						// Cek Link Account untuk penerimaan di sisi Debit
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_D);
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
														AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
															JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
															Journal_DK, PPN_Code, PPN_Amount, PPH_Code, PPH_Amount,
															ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT,
															JOBCODEID, PattNum, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
															$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 
															'D', '$TAXCODE1', '$AMOUNT_PPN', '$TAXCODE2', '$AMOUNT_PPh',
															'$Item_Code','$ITM_CATEG','$ITM_GROUP', $ITM_VOLM, $ITM_PRICE,'$ITM_UNIT',
															'$JOBCODEID', $JRNPatt, '$SPLDESC. $ITM_NOTES. $REFNUM', '$IR_NOTE', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "11051100"; // PERSEDIAAN BAHAN
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
													AND ITM_CODE = '$Item_Code'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK, PPN_Code, PPN_Amount, PPH_Code, PPH_Amount,
														ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, JOBCODEID, PattNum,
														Other_Desc, oth_reason, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
														$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D', '$TAXCODE1', '$AMOUNT_PPN', '$TAXCODE2', '$AMOUNT_PPh',
														'$Item_Code','$ITM_CATEG','$ITM_GROUP',$ITM_VOLM, $ITM_PRICE,'$ITM_UNIT', '$JOBCODEID', $JRNPatt,
														'$IR_NOTE', '$ITM_NOTES', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue,  BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
			
				// PEREKAMAN JEJAK KE tbl_itemhistory
					$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
											QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
											JOBCODEID, GEJ_STAT, JournalD_Id, ItemPrice, ItemCategoryType, MEMO)
										VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $Qty_Plus, 0, 
											$Qty_Plus, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
											'$JOBCODEID', 3, $JRNPatt, '$Item_Price', '$ITM_CATEG', '$SPLDESC. $ITM_NOTES- $IR_NOTE. $REFNUM')";
					$this->db->query($sqlHist);

				// GET PATT NUMBER 2
					$sqlJRN_C	= "tbl_journaldetail";
					$resJRN_C	= $this->db->count_all($sqlJRN_C);
					$JRNPatt2	= $resJRN_C + 1;
					
				// ------------------------------- K R E D I T : HUTANG BELUM DIFAKTUR / LAIN-LAIN -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke Hutang Supplier/Sewa Belum Difakturkan
					/*	31 Maret 2021
						BERDASARKAN https://www.jurnal.id/id/blog/perhitungan-terbaru-diskon-pembelian-di-jurnal-efektif-per-tanggal-1-januari-2017/
					 	PERHITUNGAN LAMA
						STOCK 					1000
						DISKON 							100
						HUTANG 							900

						PERHITUNGAN ABRU
						STOCK 					900
						HUTANG 							900

						KESIMPULAN
						Pada saat penerimaan, tidak terbentuk akun potongan pembelian. Karena biasanya ada potongan yang bersyarat
						seperti 2/10, n/30 (ketika pelunasan dilakukan dalam kurun waktu sepuluh hari, maka pembeli akan mendapatkan diskon 2%. Jika pelunasan dalam kurun waktu tiga puluh hari, maka pembeli tidak mendapatkan diskon). Berikut jurnalnya s.d. pembayaran

						IR 	Persediaan 			1000
						IR 	Hut. Blm Difaktur			1000

						INV Hut. Blm Difaktur	1000
						INV Hutang Usaha 				900
						INV Pot. Pembelian 				100

						BP 	Hutang Usaha 		900
						BP 	Kas 						900

						SEHINGGA UNTUK SAAT INI POTONGAN PEMBELIAN AKAN DI HIDDEN
					*/

					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						$transacValue1 		= ($ITM_VOLM * $ITM_PRICE) - $ITM_DISC;
						//$transacValue		= $transacValue1 + $AMOUNT_PPN - $AMOUNT_PPh;	// PPN idak dilibatkan dalam Penerimaan
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_K	= "tglobalsetting";
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_IR;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
															JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK,
															ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, PattNum,
															oth_reason, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
														$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'K',
														'$ITM_CODE','$ITM_CATEG','$ITM_GROUP',$ITM_VOLM, $ITM_PRICE,'$ITM_UNIT', $JRNPatt2,
														'$ITM_NOTES', '$SPLDESC. $IR_NOTE. $REFNUM', '$IR_CODE', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'IR-DISC')		// POTONGAN PENERIMAAN / PEMBELIAN
		{
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate = 1; // Default IDR ke IDR
				
				$ITM_GROUP	= $parameters['ITM_GROUP'];
				$ITM_CATEG	= $parameters['ITM_GROUP'];
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;
				// ------------------------------- D E B I T -------------------------------				
					// TIDAK ADA SISI DEBET
					
				// ------------------------------- K R E D I T -------------------------------
					// START : 11-10-2018 - Ambil settingan dari Link Account Kategori Supplier
						$ACC_NUM	= "POTIRXX"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_K	= "tglobalsetting";
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_POT;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
															JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
															isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
															$transacValue,$transacValue, 'Default', 1, 0, 'K',
															'$Item_Code', '$ITM_CATEG', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValue
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}

					// END : 11-10-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'IR-OVH')		// IR = Item Receipt Overhead
		{
			$JOBCODEID 		= $parameters['JOBCODEID'];
			$transacValue	= ($Qty_Plus * $Item_Price) - $Item_Disc;
			$IR_AMOUNT 		= $transacValue;			
			$IR_AMOUNTnPPn	= $transacValue + $AMOUNT_PPN;
			
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_CODE 	= $parameters['ITM_CODE'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$ITM_UNIT 	= $parameters['ITM_UNIT'];
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$ITM_DISC 	= $parameters['ITM_DISC'];
				$ITM_NAME 	= $parameters['ITM_NAME'];
				$ITMNOTES 	= $parameters['ITM_NOTES'];
				$IR_NOTE 	= $parameters['IR_NOTE'];
				$IR_CODE 	= $parameters['IR_CODE'];

				if($ITMNOTES == '')
					$ITM_NOTES 	= "LPM $ITMNOTES";
				else
					$ITM_NOTES 	= "LPM $ITM_NAME";

				if($IR_NOTE == '')
					$IR_NOTE 	= "$ITMNOTES";

				$SPLDESC 		= '';
				if(isset($parameters['SPLDESC']))
					$SPLDESC	= $parameters['SPLDESC'];

				$REFNUM 		= '';
				if(isset($parameters['REFNUM']))
					$REFNUM		= $parameters['REFNUM'];
				
				$Other_Desc 	= "$SPLDESC. $IR_NOTE $REFNUM";

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;

				// GET PATT NUMBER
					$sqlJRN_C	= "tbl_journaldetail";
					$resJRN_C	= $this->db->count_all($sqlJRN_C);
					$JRNPatt	= $resJRN_C + 1;
				
				// ------------------------------- D E B I T : BEBAN -------------------------------
					// START : 04-30-2022
							$sqlL_D	= "SELECT ACC_ID_UM FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID_UM;

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD 		= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT,
														JOBCODEID, PattNum, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
														$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 
														'D', '$Item_Code','$ITM_CATEG','$ITM_GROUP', $ITM_VOLM, $ITM_PRICE,'$ITM_UNIT',
														'$JOBCODEID', $JRNPatt, '$ITM_NOTES', '$IR_NOTE', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
					// END : 04-30-2022
			
				// PEREKAMAN JEJAK KE tbl_itemhistory
					$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
											QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
											JOBCODEID, GEJ_STAT, JournalD_Id, ItemPrice, ItemCategoryType, MEMO)
										VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $Qty_Plus, 0, 
											$Qty_Plus, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
											'$JOBCODEID', 3, $JRNPatt, '$Item_Price', '$ITM_CATEG', '$ITM_NOTES- $IR_NOTE')";
					$this->db->query($sqlHist);

				// START : Update ITM Used
					// 1. UPDATE JOBLIST
						$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
											ITM_LASTP	= $ITM_PRICE,
											ITM_USED 	= ITM_USED+$ITM_VOLM, 
											ITM_USED_AM = ITM_USED_AM+$transacValue,
											REQ_VOLM 	= REQ_VOLM+$ITM_VOLM, 
											REQ_AMOUNT 	= REQ_AMOUNT+$transacValue
										WHERE PRJCODE = '$proj_Code' AND JOBCODEID = '$JOBCODEID'
											AND ITM_CODE = '$Item_Code'";
						$this->db->query($sqlUpdJOBL);
						
					// 2. UPDATE ITEM LIST
						$sqlUpdITML	= "UPDATE tbl_item SET
											ITM_LASTP	= $ITM_PRICE,
											ITM_OUT 	= ITM_OUT+$ITM_VOLM,
											ITM_OUTP 	= $ITM_PRICE,
											UM_VOLM 	= UM_VOLM+$ITM_VOLM,
											UM_AMOUNT 	= UM_AMOUNT+$transacValue
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->query($sqlUpdITML);
				// END : Update ITM Used

				// START : UPDATE L/R
					$Base_Debet 	= $transacValue;
					if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'U')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'O')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					
					if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
					{
						// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
						if($ITM_TYPE == 1)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$Base_Debet 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 9)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 10)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$Base_Debet
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
					}
					elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$Base_Debet 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				// END : UPDATE L/R

				// GET PATT NUMBER 2
					$sqlJRN_C	= "tbl_journaldetail";
					$resJRN_C	= $this->db->count_all($sqlJRN_C);
					$JRNPatt2	= $resJRN_C + 1;
					
				// ------------------------------- K R E D I T : HUTANG BELUM DIFAKTUR / LAIN-LAIN -------------------------------
					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						$transacValue1 		= ($ITM_VOLM * $ITM_PRICE) - $ITM_DISC;
						//$transacValue		= $transacValue1 + $AMOUNT_PPN - $AMOUNT_PPh;	// PPN idak dilibatkan dalam Penerimaan

						$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID_IR;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK,
												ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, PattNum,
												oth_reason, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
											$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'K',
											'$ITM_CODE','$ITM_CATEG','$ITM_GROUP',$ITM_VOLM, $ITM_PRICE,'$ITM_UNIT', $JRNPatt2,
											'$ITM_NOTES', '$Other_Desc', '$IR_CODE', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'IR-SO')			// IR = Item Receipt from SO
		{
			$IR_AMOUNT 		= $transacValue;			
			$IR_AMOUNTnPPn	= $transacValue + $AMOUNT_PPN;
			
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$ITM_NAME 	= $parameters['ITM_NAME'];
				$ITMNOTES 	= $parameters['ITM_NOTES'];

				$ITM_NOTES 	= "IR $ITM_NAME, $ITMNOTES";
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $Qty_Plus, 0, 
									$Qty_Plus, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$Item_Price', '$ITM_CATEG', 'IR Greige $ITM_NOTES')";
				$this->db->query($sqlHist);
			
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T : PERSEDIAAN -------------------------------
					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						// Cek Link Account untuk penerimaan di sisi Debit
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_D);
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, 
															COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK, oth_reason, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
														$transacValue, $transacValue, 'Default', 1, 0, 'D', '$ITM_NOTES', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "11051100"; // PERSEDIAAN BAHAN
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'D' AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Debet, Base_Debet, 
														COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
													$transacValue, $transacValue, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T : HUTANG BELUM DIFAKTUR / LAIN-LAIN -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
					// TIDAK ADA INI, KARENA BARANG DARI CUSTOMER, KECUALI PEMBELIAN
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'V-PINV2')		// Pengembalian KHUSUS PPn Masukan (DEBIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// START : 27-12-2018 - PPn Masukan (Hard Code)
						$sqlCL_D	= "tbl_tax_ppn";	
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_ppn";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->TAXLA_LINKIN;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
												$transacValue, 'Default', 1, 0, 'K', 'VOID PPn Masukan', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
							endforeach;
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 27-12-2018 - PPn Masukan (Hard Code)				
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV')			// PINV = Purchase Invoice
		{
			$SPLCATEG	= $data[1];
			$INV_CATEG	= $data[2];									// IR OR OPN
			

			/*$ITM_PRICE1		= $TOTINV_PPN;
			$ITM_PRICE2		= $TOTINV_EXP;
			$ITM_PRICE3		= $TOTINV_RET;
			$ITM_PRICE4		= $TOTINV_POT;
			$ITM_PRICE5		= $INV_PPHVAL;*/

			$transacValue 		= $transacValue;					// NILAI NETT SETELAH + PPN + EXP - RET - POT
			$transacValuePPn	= $parameters['ITM_PRICE1'];		// NILAI PPN
			$transacValueExp	= $parameters['ITM_PRICE2'];		// BEBAN LAINNYA
			$transacValueRet	= $parameters['ITM_PRICE3'];		// NILAI RETENSI
			$transacValuePot	= $parameters['ITM_PRICE4'];		// NILAI POT
			$transacValuePPh	= $parameters['ITM_PRICE5'];		// NILAI PPH

			// NILAI ASLI PER ROW
				$ITEM_TOT		= $parameters['ITEM_TOT'];			// NILAI ORIGINAL SEBELUM + PPN + EXP - RET - POT
				$ITEM_PPN		= $parameters['ITEM_PPN'];
				$ITEM_EXP		= 0;
				$ITEM_RET		= $parameters['ITEM_RET'];
				$ITEM_POT		= $parameters['ITEM_POT'];
				$ITEM_PPH		= 0;

			$Other_Desc			= $parameters['Other_Desc'];
			$Ref_Number			= $parameters['Ref_Number'];

			// NILAI TOTAL SETELAH DIPOTONG POTONGAN DAN PPH DITAMBHA BIAYA LAINNYA JIKA ADA
			//$transacValueGT		= $transacValue + $transacValuePPn - $transacValueRet - $transacValuePPh - $transacValuePot + $transacValueOth;
			
			// NILAI TOTAL SEBELUM DIPOTONG PPH (PPH DITENTUKAN SAAT FAKTUR), DIGUNAKAN UNTUK MEMBALIKAN NILAI HUTANG LAIN-LAIN
			/*$transacValueGT1		= $transacValue - $transacValuePot;
			if($INV_CATEG == 'IR')
				$transacValueGT1	= $transacValue - $transacValuePot + $transacValuePPn;*/

			// APABILA NILAI transacValuePot = 0 NAMUN NILAI INV_AMOUNT_POT, ITU BERARTI POTONGAN DIINPUT MANUAL SAAT TTK
			// SEHINGGA, NILAI YANG MEMOTONG HUTANG BEULM DIFAKTURKAN ADALAH NILAI SEBELUM DIPOTONG POTONGAN
				/*
					HUT. BELUM DIFAKTURKAN 			1,000				// transacValue
						HUTANG 								900			// HUTANG
						POTONGAN 							100			// INV_AMOUNT_POT
				*/

			$transacValueD 		= $ITEM_TOT;
			$transacValueK 		= $transacValue;

			/*echo "$transacValueD = $transacValueK == $ITEM_TOT - $transacValuePot - $transacValueRet";
			return false;*/
			/*echo "ORI : $INV_CATEG = $transacValue, ORI1 = $transacValueGT1, PPH = $transacValuePPh, RET = $transacValueRet, PPN = $transacValuePPn, POT = $transacValuePot, TOT = $transacValueGT";
			return false;*/
			
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T 01 : HUTANG LAIN-LAIN -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi debit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
					// sebagai kebalikan saat pembuatan journal penerimaan
					// YANG DIPOTONG HUTANG LAIN-LAIN JIKA OPN ADALAH SETELAH DIPOTONG POTONGAN DAN SEBELUM DIPOTONG PPH
				
					// START : 27-12-2018
					if($INV_CATEG == 'OTH')
					{
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_D	= "tglobalsetting";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID_IR;
								if($ACC_NUM == '')
									$ACC_NUM	= "2111";

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
															$transacValueD, $transacValueD, 'Default',
															1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValueD,
																Base_Debet = Base_Debet+$transacValueD,
																COA_Debet = COA_Debet+$transacValueD
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					}
					else
					{
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_D	= "tglobalsetting";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID_IR;
								if($ACC_NUM == '')
									$ACC_NUM	= "2111";

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
															$transacValueD, $transacValueD, 'Default',
															1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValueD,
																Base_Debet = Base_Debet+$transacValueD,
																COA_Debet = COA_Debet+$transacValueD
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					}
					// END : 27-12-2018
					
				// ------------------------------- K R E D I T 01 HUT. SUPPLIER -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2101100 Hutang Supplier/Sewa
				
					// START : 27-12-2018
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								if($ACC_NUM == '')
									$ACC_NUM	= "2101100";
									$Acc_Name 	= "-";
									$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm		= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
															$transacValueK, $transacValueK, 'Default',
															1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValueK,
																Base_Kredit = Base_Kredit+$transacValueK,
																COA_Kredit = COA_Kredit+$transacValueK
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueGT,
															Base_Kredit2 = Base_Kredit2+$transacValueGT
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 27-12-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV2')			// PINV = Purchase Invoice - KHUSUS PPn Masukan (DEBIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
    		$Ref_Number = $parameters['Reference_Number'];
			$SPLCATEG	= $data[1];
			
			$TAXCODE1 	= $parameters['TAXCODE1'];
			$TAXPRICE1 	= $parameters['TAXPRICE1'];
			
			/*$AMOUNT_PPN	= 0;
			$AMOUNT_PPh	= 0;
			if($TAXCODE1 == 'TAX01')
				$AMOUNT_PPN		= $TAXPRICE1;
			elseif($TAXCODE1 == 'TAX02')
				$AMOUNT_PPh		= $TAXPRICE1;*/

			$sTaxPPn 	= "tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";	// Tax PPn
			$rTaxPPn	= $this->db->count_all($sTaxPPn);
			if($rTaxPPn > 0)
			{
				$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
			}
			else
			{
				$sqlL_D	= "SELECT VC_LA_PAYINV FROM tbl_vendcat WHERE VendCat_Code = '$SPLCATEG'";
			}

			/*$sTaxPPh 	= "tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE1'";	// Tax PPh
			$rTaxPPh	= $this->db->count_all($sTaxPPh);
			if($rTaxPPh > 0)
			{
				$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE1'";
			}
			else
			{
				$sqlL_D	= "SELECT VC_LA_PAYINV FROM tbl_vendcat WHERE VendCat_Code = '$SPLCATEG'";
			}*/
				
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// START : 27-12-2018 - PPn Masukan (Hard Code)
						/*$sqlCL_D	= "tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{*/
							//$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->TAXLA_LINKIN;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, ITM_CODE, Journal_Type, Notes, Ref_Number, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
												$TAXPRICE1, $TAXPRICE1, $TAXPRICE1, 'Default', 1, 0, 
												'D', 'PPN Masukan', '$Item_Code', 'TAX_PPN', '$Notes', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
							endforeach;
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$TAXPRICE1,
															Base_Debet2 = Base_Debet2+$TAXPRICE1
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						//}
					// END : 27-12-2018 - PPn Masukan (Hard Code)				
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV3')			// PINV = Purchase Invoice - Khusus PPh Header
		{
			$SPLCATEG		= $data[1];
			$PPhTax1 		= $parameters['PPhTax'];
			$PPhAmount 		= $parameters['PPhAmount'];
    		$Notes 			= $parameters['Notes'];
    		$Ref_Number 	= $parameters['Ref_Number'];
			
			$splitCode 		= explode("~", $PPhTax1);
			$PPhTax			= $splitCode[0];
			
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// TIDAK ADA SISI DEBET
					
				// ------------------------------- K R E D I T -------------------------------
					// START : 11-10-2018 - Ambil settingan dari Link Account Kategori Supplier
						// Cek Link Account untuk PPh
						// didapatkan dari setinga LA Pajak
						$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKIN != ''";
						$resTLAC	= $this->db->count_all($sqlTLAC);	
						if($resTLAC > 0)
						{
							$TAXLA_LINKIN	= '';
							$sqlTLA			= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
							$resTLA 		= $this->db->query($sqlTLA)->result();					
							foreach($resTLA as $rowTLA):
								$TAXLA_LINKIN	= $rowTLA->TAXLA_LINKIN;
								$ACC_NUM	= $rowTLA->TAXLA_LINKIN;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
								
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
												curr_rate, isDirect, Journal_DK, Journal_Type, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$TAXLA_LINKIN', '$proj_Code', 'IDR', $transacValue,
												$transacValue, $transacValue, 'Default', 1, 0, 'K', 'TAX_PPH', '$Notes', '$Ref_Number', '$Acc_Name',
												'$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																	Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKIN'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 11-10-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV4')			// PINV = Purchase Invoice - KHUSUS Beban Lainnya
		{
    		$Notes 			= $parameters['Notes'];
    		$Ref_Number 	= $parameters['Ref_Number'];
    		$ACC_ID 		= $parameters['ACC_ID'];
				
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					$ACC_NUM 	= "POT_XXXX";
					$sqlL_D		= "SELECT ACC_ID_OEXP FROM tglobalsetting";
					$resL_D 	= $this->db->query($sqlL_D)->result();					
					foreach($resL_D as $rowL_D):
						$ACC_NUM	= $rowL_D->ACC_ID_OEXP;
					endforeach;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;
					
					$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
									JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
									Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
								VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $Item_Price, $Item_Price,
									$Item_Price, 'Default', 1, 0, 'D', '$Notes', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
					$this->db->query($sqlGEJDD);
				
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$AMOUNT_PPN,
													Base_Debet2 = Base_Debet2+$AMOUNT_PPN, BaseD_$accYr = BaseD_$accYr+$AMOUNT_PPN
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV5')			// PINV = Purchase Invoice - KHUSUS Potongan
		{
    		$Notes 			= $parameters['Notes'];
    		$Ref_Number 	= $parameters['Ref_Number'];
				
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- K R E D I T -------------------------------
					$ACC_NUM 	= "POT_XXXX";
					$sqlL_K		= "SELECT ACC_ID_POT FROM tglobalsetting";
					$resL_K 	= $this->db->query($sqlL_K)->result();					
					foreach($resL_K as $rowL_K):
						$ACC_NUM	= $rowL_K->ACC_ID_POT;
					endforeach;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
									AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
					$resCGEJ	= $this->db->count_all($sqlCGEJ);
					
					if($resCGEJ == 0)
					{
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK,
											Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
											$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'K',
											'$Notes', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
					}
					else
					{
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
												Base_Kredit = Base_Kredit+$transacValue,
												COA_Kredit = COA_Kredit+$transacValue
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD);
					}
				// START : Update to COA - Debit
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
												Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_RET')		// PINV = Purchase Invoice - KHUSUS RETENSI (KREDIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 27-12-2018 : Journal sisi kredit ditetapkan ke HUTANG RETENSI,
					// SETINGAN DIAMBIL DARI DAFTAR SUPPLIER
				
					// START : 27-12-2018
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'RET' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						$resCL_K = 0;
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'RET' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
															$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue,
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$sqlCL_K	= "tglobalsetting";
							$resCL_K	= $this->db->count_all($sqlCL_K);
							if($resCL_K > 0)
							{
								$sqlL_K	= "SELECT ACC_ID_RET FROM tglobalsetting";
								$resL_K = $this->db->query($sqlL_K)->result();			
								foreach($resL_K as $rowL_K):
									$ACC_NUM	= $rowL_K->ACC_ID_RET;
								endforeach;
								if($ACC_NUM == '')
									$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
							}
							else
							{
								$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
							}
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
														curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
														$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue,
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
														Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 27-12-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINVD')			// PINV = Purchase Invoice Direct
		{
			$SPLCATEG	= $data[1];
			// HANYA ADA PENAMBAHAN KE HUTANG SUPPLIER
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan sebagai kebalikan
					// DITIADAKAN					
				// ------------------------------- K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 2101100 Hutang Supplier/Sewa
				
					// START : 04-07-2018
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
															$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue,
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
									// START : Save Journal Detail - Debit
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect,
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'K', 'PID Not LA', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
									// END : Save Journal Detail - Debit
								}
								else
								{
									// START : UPDATE Journal Detail - Debit
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue,
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
									// END : UPDATE Journal Detail - Debit
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_NEW')			// PINV_NEW = Purchase Invoice
		{
			$SPLCATEG 			= $parameters['SPL_CATEG'];
			$INV_AMOUNT 		= $parameters['INV_AMOUNT'];
			$INV_AMOUNT_PPN 	= $parameters['INV_AMOUNT_PPN'];		// OK
			$INV_AMOUNT_PPNA 	= 0;
			$INV_AMOUNT_PPH		= $parameters['INV_AMOUNT_PPH'];		// OK
			$INV_AMOUNT_DPB		= $parameters['INV_AMOUNT_DPB'];		// OK
			$INV_AMOUNT_RET		= $parameters['INV_AMOUNT_RET'];		// OK
			$INV_AMOUNT_POT		= $parameters['INV_AMOUNT_POT'];		// OK
			$INV_AMOUNT_OTH		= $parameters['INV_AMOUNT_OTH'];		// OK
			$INV_AMOUNT_POTUM	= $parameters['INV_AMOUNT_POTUM']; 
			$INV_AMOUNT_TOT		= $parameters['INV_AMOUNT_TOT']; 
			$INV_PPN			= $parameters['INV_PPN'];
			$INV_PPH			= $parameters['INV_PPH'];
			$REF_CODE			= $parameters['REF_CODE'];
			$INV_ACC_OTH		= $parameters['INV_ACC_OTH'];
			$Other_Desc			= $parameters['Other_Desc'];
			$TTK_CODE			= $parameters['TTK_CODE'];
			$UM_REF				= $parameters['UM_REF'];
			$TAXCODE_PPN		= $parameters['TAXCODE_PPN'];
			$TAXCODE_PPH		= $parameters['TAXCODE_PPH'];
			$Ref_Number 		= $JournalH_Code;
			
			// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT ----------------		
				$curr_rate = 1; // Default IDR ke IDR
			
				/*
					HUT. BLM DIFAKTUR  	315,000.00 
					PPN MASUKAN	 		 31,500.00 	
					RETENSI		 					    	  0.00 	// TIDAK LAGI MENJADI KOEFISIEN
					POTONGAN		 						  0.00 	// TIDAK LAGI MENJADI KOEFISIEN
					BIAYA LAINNYA	 	     0.00 	
					PENGEMB. DP		 					      0.00 
					PPH		 							      0.00
					HUTANG SUPPLIER						346,500,00
				*/

				// KARENA RETENSI TIDAK LAGI MENJADI KOEFISIEN, MAKA INV_AMOUNT HARUS DIKURANGI (-) RETENSI
					$DEB_VALUE 	= $INV_AMOUNT - $INV_AMOUNT_POT - $INV_AMOUNT_RET;

				// ------------------------------- D E B I T 01 : HUTANG BELUM DIFAKTUR -------------------------------
				
					// START : 02-11-2021
						// SISI DEBIT ADALAH UNTUK MENYEIMBANGKAN JURNAL OPNAME (HUT. BELUM DIFAKTUR)

						$ACC_NUM	= "HBDF";
						$sqlCL_D	= "tglobalsetting";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID_IR;
								if($ACC_NUM == '')
									$ACC_NUM	= "HBDF";

								$transacValueD	= $DEB_VALUE;
								$Acc_Name 		= "-";
								$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm			= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
															$transacValueD, $transacValueD, 'Default',
															1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValueD,
																Base_Debet = Base_Debet+$transacValueD,
																COA_Debet = COA_Debet+$transacValueD
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 02-11-2021
					
				// ------------------------------- K R E D I T 01 HUT. SUPPLIER -------------------------------

					// START : 02-11-2021
						// SISI KREDIT DIAMBIL DARI SETTINGAN PER KATEGORI SUPPLIER

						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								if($ACC_NUM == '')
									$ACC_NUM	= "2101100";

								$transacValueK	= $INV_AMOUNT_TOT;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
															$transacValueK, $transacValueK, 'Default',
															1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValueK,
																Base_Kredit = Base_Kredit+$transacValueK,
																COA_Kredit = COA_Kredit+$transacValueK
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueGT,
															Base_Kredit2 = Base_Kredit2+$transacValueGT
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 02-11-2021
			// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------

			/*
				PERHITUNGAN PPN BUKAN LAGI DIHITUNG DARI DEYIL, MELAINKAN DARI DAFTAR NOMOR SERI PAJAK
			*/

			//if($INV_AMOUNT_PPN > 0)		// DEBET
			if($INV_AMOUNT_PPNA > 0)		// DEBET
			{
				$Other_Desc 		= "PPn Masukan $REF_CODE<br>TTK $TTK_CODE";
				$Ref_Number 		= $JournalH_Code;
				// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------
					// ------------------------------- D E B I T 01 : PPN KELUARAN -------------------------------
					
						// START : 02-11-2021
							$sqlCL_D	= "tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE_PPN'";
							$resCL_D	= $this->db->count_all($sqlCL_D);
							if($resCL_D > 0)
							{
								$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE_PPN'";
								$resL_D = $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ACC_NUM	= $rowL_D->TAXLA_LINKIN;
									if($ACC_NUM == '')
										$ACC_NUM	= "HBDF";

									$transacValueD	= $INV_AMOUNT_PPN;
									$Acc_Name 		= "-";
									$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm			= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;
										
									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
										/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$resCGEJ	= $this->db->count_all($sqlCGEJ);
										if($resCGEJ == 0)
										{*/
												$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
																curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
															VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
																$transacValueD, $transacValueD, 'Default',
																1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
												$this->db->query($sqlGEJDD);
										/*}
										else
										{
												$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																	JournalD_Debet = JournalD_Debet+$transacValueD,
																	Base_Debet = Base_Debet+$transacValueD,
																	COA_Debet = COA_Debet+$transacValueD
																WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																	AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
												$this->db->query($sqlUpdCOAD);
										}*/
										
									// START : Update to COA - Debit
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																	Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								endforeach;
							}
						// END : 02-11-2021

				// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
			}
			
			if($INV_AMOUNT_DPB > 0)			// KREDIT
			{
				$Other_Desc 		= "Pengembalian UM INV $REF_CODE";
				$Ref_Number 		= $JournalH_Code;
				// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------
					// ------------------------------- K R E D I T 01 : PPN KELUARAN -------------------------------
					
						// START : 02-11-2021
							$sqlCL_K	= "tglobalsetting";
							$this->db->count_all($sqlCL_K);				
							$resCL_K	= $this->db->count_all($sqlCL_K);
							if($resCL_K > 0)
							{
								$sqlL_K	= "SELECT ACC_ID_RDP FROM tglobalsetting";
								$resL_K = $this->db->query($sqlL_K)->result();					
								foreach($resL_K as $rowL_K):
									$ACC_NUM	= $rowL_K->ACC_ID_RDP;
									if($ACC_NUM == '')
										$ACC_NUM	= "2101100";

									$transacValueK	= $INV_AMOUNT_DPB;
									$Acc_Name 		= "-";
									$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm			= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;

									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
										$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$resCGEJ	= $this->db->count_all($sqlCGEJ);
										
										if($resCGEJ == 0)
										{
												$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
																curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
															VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
																$transacValueK, $transacValueK, 'Default',
																1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
												$this->db->query($sqlGEJDD);
										}
										else
										{
												$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																	JournalD_Kredit = JournalD_Kredit+$transacValueK,
																	Base_Kredit = Base_Kredit+$transacValueK,
																	COA_Kredit = COA_Kredit+$transacValueK
																WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																	AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
												$this->db->query($sqlUpdCOAD);
										}
									// START : Update to COA - Debit
										/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueGT,
																Base_Kredit2 = Base_Kredit2+$transacValueGT
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD); // OK*/
										
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																	Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								endforeach;
							}
						// END : 02-11-2021
							
				// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
			}
			
			if($INV_AMOUNT_PPH > 0)			// KREDIT
			{
				$Other_Desc 		= "PPh fr INV $REF_CODE";
				$Ref_Number 		= $JournalH_Code;
				// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------
					// ------------------------------- K R E D I T 01 : PPN KELUARAN -------------------------------
					
						// START : 02-11-2021
							$sqlCL_K	= "tbl_tax_la WHERE TAXLA_NUM = '$INV_PPH'";
							$this->db->count_all($sqlCL_K);				
							$resCL_K	= $this->db->count_all($sqlCL_K);
							if($resCL_K > 0)
							{
								$sqlL_K	= "SELECT TAXLA_LINKIN, TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$INV_PPH'";
								$resL_K = $this->db->query($sqlL_K)->result();					
								foreach($resL_K as $rowL_K):
									$ACC_NUM	= $rowL_K->TAXLA_LINKIN;
									if($ACC_NUM == '')
										$ACC_NUM	= "2101100";

									$transacValueK	= $INV_AMOUNT_PPH;
									$Acc_Name 		= "-";
									$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm			= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;

									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
										$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$resCGEJ	= $this->db->count_all($sqlCGEJ);
										
										if($resCGEJ == 0)
										{
												$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
																curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
															VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
																$transacValueK, $transacValueK, 'Default',
																1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
												$this->db->query($sqlGEJDD);
										}
										else
										{
												$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																	JournalD_Kredit = JournalD_Kredit+$transacValueK,
																	Base_Kredit = Base_Kredit+$transacValueK,
																	COA_Kredit = COA_Kredit+$transacValueK
																WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																	AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
												$this->db->query($sqlUpdCOAD);
										}
									// START : Update to COA - Debit
										/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueGT,
																Base_Kredit2 = Base_Kredit2+$transacValueGT
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD); // OK*/
										
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																	Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								endforeach;
							}
						// END : 02-11-2021
							
				// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
			}
			
			// DIBUATKAN KHUSUS JURNAL RETENSI. DU-HOLD KARENA JURNAL RETENSI SUDAH DIBUATKAN PADA SAAT OPNAME DAN AKAN TERPISAH SAAT PEMFAKTURAN
				/*if($INV_AMOUNT_RET > 0)			// KREDIT
				{
					$Other_Desc 		= "Retensi fr INV $REF_CODE";
					$Ref_Number 		= $JournalH_Code;
					// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------
						// ------------------------------- K R E D I T 01 : PPN KELUARAN -------------------------------
						
							// START : 02-11-2021
								$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'RET' AND LA_DK = 'K'";
								$this->db->count_all($sqlCL_K);				
								$resCL_K	= $this->db->count_all($sqlCL_K);
								if($resCL_K > 0)
								{
									$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'RET' AND LA_DK = 'K'";
									$resL_K = $this->db->query($sqlL_K)->result();					
									foreach($resL_K as $rowL_K):
										$ACC_NUM	= $rowL_K->LA_ACCID;
										if($ACC_NUM == '')
											$ACC_NUM	= "2101100";

										$transacValueK	= $INV_AMOUNT_RET;
										$Acc_Name 		= "-";
										$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resNm			= $this->db->query($sqlNm)->result();
										foreach($resNm as $rowNm):
											$Acc_Name	= $rowNm->Account_NameId;
										endforeach;

										// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
											$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$resCGEJ	= $this->db->count_all($sqlCGEJ);
											
											if($resCGEJ == 0)
											{
													$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																	Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
																	curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
																VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
																	$transacValueK, $transacValueK, 'Default',
																	1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
													$this->db->query($sqlGEJDD);
											}
											else
											{
													$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																		JournalD_Kredit = JournalD_Kredit+$transacValueK,
																		Base_Kredit = Base_Kredit+$transacValueK,
																		COA_Kredit = COA_Kredit+$transacValueK
																	WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																		AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
													$this->db->query($sqlUpdCOAD);
											}
										// START : Update to COA - Debit
											
											$isHO			= 0;
											$syncPRJ		= '';
											$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
																WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
											$resISHO		= $this->db->query($sqlISHO)->result();
											foreach($resISHO as $rowISHO):
												$isHO		= $rowISHO->isHO;
												$syncPRJ	= $rowISHO->syncPRJ;
											endforeach;
											$dataPecah 	= explode("~",$syncPRJ);
											$jmD 		= count($dataPecah);
										
											if($jmD > 0)
											{
												$SYNC_PRJ	= '';
												for($i=0; $i < $jmD; $i++)
												{
													$SYNC_PRJ	= $dataPecah[$i];
													$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																		Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
																	WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
													$this->db->query($sqlUpdCOA);
												}
											}
										// END : Update to COA - Debit
									endforeach;
								}
							// END : 02-11-2021
								
					// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
				}*/
			
			if($INV_AMOUNT_POT > 0)			// KREDIT : HOLDED. POTONGAN TIDAK DILIBATKAN JURNAL KARENA SUDAH MEMOTONG NILAI SAAT LPM
			{
				$Other_Desc 		= "Potongan pembayaran INV $REF_CODE";
				$Ref_Number 		= $JournalH_Code;
				// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------
					// ------------------------------- K R E D I T 01 : PPN KELUARAN -------------------------------
					
						// START : 02-11-2021
							/*$sqlCL_K	= "tglobalsetting";
							$this->db->count_all($sqlCL_K);				
							$resCL_K	= $this->db->count_all($sqlCL_K);
							if($resCL_K > 0)
							{
								$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
								$resL_K = $this->db->query($sqlL_K)->result();					
								foreach($resL_K as $rowL_K):
									$ACC_NUM	= $rowL_K->ACC_ID_POT;
									if($ACC_NUM == '')
										$ACC_NUM	= "2101100";

									$transacValueK	= $INV_AMOUNT_POT;
									$Acc_Name 		= "-";
									$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm			= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;

									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
										$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$resCGEJ	= $this->db->count_all($sqlCGEJ);
										
										if($resCGEJ == 0)
										{
												$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
																curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
															VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
																$transacValueK, $transacValueK, 'Default',
																1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
												$this->db->query($sqlGEJDD);
										}
										else
										{
												$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																	JournalD_Kredit = JournalD_Kredit+$transacValueK,
																	Base_Kredit = Base_Kredit+$transacValueK,
																	COA_Kredit = COA_Kredit+$transacValueK
																WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																	AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
												$this->db->query($sqlUpdCOAD);
										}
									// START : Update to COA - Debit
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																	Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								endforeach;
							}*/
						// END : 02-11-2021
							
				// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
			}
			
			if($INV_AMOUNT_OTH > 0)			// DEBET
			{
				$Other_Desc 		= "$Other_Desc- $REF_CODE (Other exp. _p.inv)";
				// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------
					// ------------------------------- D E B I T 01 : PPN KELUARAN -------------------------------
					
						// START : 02-11-2021
							$ACC_NUM 	= $INV_ACC_OTH;
							if($ACC_NUM == '')
							{
								$sqlL_D		= "SELECT ACC_ID_OEXP FROM tglobalsetting";
								$resL_D 	= $this->db->query($sqlL_D)->result();					
								foreach($resL_D as $rowL_D):
									$ACC_NUM	= $rowL_D->ACC_ID_OEXP;
								endforeach;
							}
							
							if($ACC_NUM != '')
							{
								$transacValueD	= $INV_AMOUNT_OTH;
								$Acc_Name 		= "-";
								$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm			= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
															$transacValueD, $transacValueD, 'Default',
															1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValueD,
																Base_Debet = Base_Debet+$transacValueD,
																COA_Debet = COA_Debet+$transacValueD
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							}
						// END : 02-11-2021

				// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
			}
			
			if($INV_AMOUNT_POTUM > 0)		// KREDIT (POTONGAN PEMINJAMAN MATERIAL OLEH SUPPLIER)
			{
				$tags 		= explode('~',$UM_REF);
				// START : MENGEMBALIKAN PIUTANG
					foreach($tags as $UM_NUM)
					{
						$Other_Desc 		= "Potongan dari Pinjaman Material: $UM_NUM";
						$Ref_Number 		= $UM_NUM;

					    $s_01 	= "SELECT Acc_Id, JournalD_Debet, JournalD_Kredit FROM tbl_journaldetail
					    			WHERE JournalH_Code = '$UM_NUM' AND proj_Code = '$proj_Code' AND UMSUB_STAT = 1";
					    $r_01 	= $this->db->query($s_01)->result();
					    foreach($r_01 as $rw_01):
					    	$ACC_NUM	= $rw_01->Acc_Id;
					    	$AMNOUNT_D	= $rw_01->JournalD_Debet;

							$transacValueK	= $AMNOUNT_D;			// Ambil dr sisi Debet saat UM
							$Acc_Name 		= "-";
							$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount
												WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm			= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
														curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, UMSUB_STAT)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
														$transacValueK, $transacValueK, 'Default',
														1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', 2)";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValueK,
															Base_Kredit = Base_Kredit+$transacValueK,
															COA_Kredit = COA_Kredit+$transacValueK
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}

							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
															Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
					    endforeach;
					}
				// END : MENGEMBALIKAN PIUTANG

				// START : MENGEMBALIKAN BUDGET
					foreach($tags as $UM_NUM)
					{
						$Other_Desc 	= "Potongan dari Pinjaman Material: $UM_NUM";
						$Ref_Number 	= $UM_NUM;
						$s_01			= "SELECT JOBCODEID, ITM_CODE FROM tbl_um_detail WHERE UM_NUM = '$UM_NUM' AND PRJCODE = '$proj_Code'";
						$r_01			= $this->db->query($s_01)->result();
						foreach($r_01 as $rw_01) :
							$JOBCODEID 	= $rw_01->JOBCODEID; 
							$ITM_CODE 	= $rw_01->ITM_CODE;

							$sup_01 	= "UPDATE tbl_joblist_detail SET REQ_VOLM = 0, REQ_AMOUNT = 0, PO_VOLM = 0, PO_AMOUNT = 0,
												IR_VOLM = 0, IR_AMOUNT = 0, WO_QTY = 0, WO_AMOUNT = 0, OPN_QTY = 0, OPN_AMOUNT = 0, 
												ITM_USED = 0, ITM_USED_AM = 0, ITM_STOCK = 0, ITM_STOCK_AM
											WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$proj_Code'";
							$this->db->query($sup_01);
						endforeach;
					}
				// END : MENGEMBALIKAN BUDGET
			}

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.JournalType = B.JournalType, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_NEW2')			// PINV_NEW = Purchase Invoice
		{
			// PADA SAAT PEMBUATAN VOUCHER/FAKTUR TIDAK MEMBENTUK JURNAL, KECUALI PPN, BEBAN LAIN DAN POTONGAN
			$SPLCATEG 			= $parameters['SPL_CATEG'];
			$INV_AMOUNT 		= $parameters['INV_AMOUNT'];
			$INV_AMOUNT_PPN 	= $parameters['INV_AMOUNT_PPN'];		// OK
			$INV_AMOUNT_PPNA 	= 0;
			$INV_AMOUNT_PPH		= $parameters['INV_AMOUNT_PPH'];		// OK
			$INV_AMOUNT_DPB		= $parameters['INV_AMOUNT_DPB'];		// OK
			$INV_AMOUNT_RET		= $parameters['INV_AMOUNT_RET'];		// OK
			$INV_AMOUNT_POT		= $parameters['INV_AMOUNT_POT'];		// OK
			$INV_AMOUNT_OTH		= $parameters['INV_AMOUNT_OTH'];		// OK
			$INV_AMOUNT_POTUM	= $parameters['INV_AMOUNT_POTUM']; 
			$INV_AMOUNT_POTOTH	= $parameters['INV_AMOUNT_POTOTH'];
			$INV_AMOUNT_TOT		= $parameters['INV_AMOUNT_TOT']; 
			$INV_PPN			= $parameters['INV_PPN'];
			$INV_PPH			= $parameters['INV_PPH'];
			$REF_CODE			= $parameters['REF_CODE'];
			$INV_ACC_OTH		= $parameters['INV_ACC_OTH'];
			$OtherDesc			= $parameters['Other_Desc'];
			$TTK_CODE			= $parameters['TTK_CODE'];
			$UM_REF				= $parameters['UM_REF'];
			$ACC_NUM			= $parameters['ACC_ID'];
			$Acc_Name			= $parameters['Acc_Name'];
			$TAXCODE_PPN		= $parameters['TAXCODE_PPN'];
			$TAXCODE_PPH		= $parameters['TAXCODE_PPH'];
			$Ref_Number 		= $JournalH_Code;

			$SPLCODE 		= '';
			if(isset($parameters['SPLCODE']))
				$SPLCODE	= $parameters['SPLCODE'];

			$SPLDESC 		= '';
			if(isset($parameters['SPLDESC']))
				$SPLDESC	= $parameters['SPLDESC'];

			$REF_CODE1 		= '';
			if(isset($parameters['REF_CODE1']))
				$REF_CODE1	= $parameters['REF_CODE1'];

			$REF_CODE2 		= '';
			if(isset($parameters['REF_CODE2']))
				$REF_CODE2	= $parameters['REF_CODE2'];
			
			$Other_Desc 	= "$SPLDESC. $OtherDesc $REF_CODE1 - $REF_CODE2";

			// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT ----------------		
				$curr_rate = 1; // Default IDR ke IDR
			
				/*
					PPN MASUKAN	 		 100,000.00 	
					HUT. SUPPLIER		 				100,000.00
					POTONGAN		 					 50,000.00
					HUT. SUPPLIER		  50,000.00
					BIAYA LAINNYA	 	  10,000.00 	
					HUT. SUPPLIER		 				 10,000.00
				*/
			// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------

			/*
				PERHITUNGAN PPN BUKAN LAGI DIHITUNG DARI DEYIL, MELAINKAN DARI DAFTAR NOMOR SERI PAJAK
			*/
			
			// HASIL DISKUSI DENGAN BU LYTA DNA PAK DEDE TGL. 25 OKT. 22
			// 1. PPH YANG DIPILIH PADA SAAT SPK, HARUS PPH FINAL SEHINGGA TIDAK ADA PEMOTONGAN HUTANG OLEH PPH
			// 2. TIDAK AKAN ADA PEMBENTUKAN JURNAL PPH BAIK SAAT OPNAME MAUPUN SAAT VOUCHER
			// 3. ALGORITMA DIGANTI KE FUNCTION PINV_NEW3

			if($INV_AMOUNT_PPH > 0)			// KREDIT
			{
				$Ref_Number 		= $JournalH_Code;
				// ---------------- START 01: Pembuatan Journal Detail INV_AMOUNT_PPN ----------------

					// ------------------------------- K R E D I T : PPN KELUARAN -------------------------------

						// PERHATIKAN ..... !!!! PPH_FINAL
						// APAKAH AKUN PPH INI TERMASUK PPH FINAL ATAU NON FINAL
						// JIKA PPH FINAL, MAKA DI JOURNAL PADA SAAT PEMBAYARAN
						// JIKA PPH NON FINAL, DIBENTUK PADA SAAT VOUCHER/FAKTUR

						// START : 02-11-2021
							/*$sqlCL_K	= "tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE_PPH'";			
							$resCL_K	= $this->db->count_all($sqlCL_K);
							if($resCL_K > 0)
							{
								$sqlL_K	= "SELECT TAXLA_LINKIN, TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE_PPH'";
								$resL_K = $this->db->query($sqlL_K)->result();					
								foreach($resL_K as $rowL_K):
									$ACC_NUM	= $rowL_K->TAXLA_LINKIN;
								endforeach;
							}
							else
							{
								$ACC_NUM	= "VC.PPH.$REF_CODE";
							}*/

							$isPPhFin 		= 0;
							$transacValueK	= $INV_AMOUNT_PPH;
							/*$Acc_Name 		= "NO ACC. SELECTED";
							$sqlNm 			= "SELECT Account_NameId, isPPhFinal FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm			= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
								$isPPhFin	= $rowNm->isPPhFinal;
							endforeach;

							if($isPPhFin == 0)			// 0 PPH NON-FINAL 1 PPH FINAL
							{*/
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									//if($resCGEJ == 0)
									//{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC, isTax)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
															$transacValueK, $transacValueK, 'Default',
															1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC', 1)";
											$this->db->query($sqlGEJDD);
									/*}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValueK,
																Base_Kredit = Base_Kredit+$transacValueK,
																COA_Kredit = COA_Kredit+$transacValueK
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}*/

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();

									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);

									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											if($SYNC_PRJ == '')
												$SYNC_PRJ = $proj_Code;

											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							//}
						// END : 02-11-2021

					// ------------------------------- D E B I T : HUT. SUPPLIER VS PPN KELUARAN -------------------------------

					// START : 02-11-2021
					// 29-08-22 PPH HANYA MEMBENTUK DI SISI KREDIT JIKA NON-FINAL
						// SISI KREDIT DIAMBIL DARI SETTINGAN PER KATEGORI SUPPLIER

						/*$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								if($ACC_NUM == '')
									$ACC_NUM	= "2101100";

								$transacValueD	= $INV_AMOUNT_PPH;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
															$transacValueD, $transacValueD, 'Default',
															1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValueD,
																Base_Debet = Base_Debet+$transacValueD,
																COA_Debet = COA_Debet+$transacValueD
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}*/
					// END : 02-11-2021
							
				// ---------------- END : Pembuatan Journal Detail INV_AMOUNT ----------------
			}
			
			// DIBUATKAN KHUSUS JURNAL RETENSI. DI-HOLD KARENA JURNAL RETENSI SUDAH DIBUATKAN PADA SAAT OPNAME DAN AKAN TERPISAH SAAT PEMFAKTURAN
			
			// POTONGAN (INV_AMOUNT_POT) TIDAK DILIBATKAN JURNAL KARENA SUDAH MEMOTONG NILAI SAAT LPM

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.JournalType = B.JournalType, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_PPN')			// PINV_NEW = Purchase Invoice
		{
			$PRJCODE			= $parameters['PRJCODE']; 
			$JournalH_Code 		= $parameters['JournalH_Code'];
			$TAXCODE_PPN 		= $parameters['TAXCODE1'];
			$Manual_No			= $parameters['Manual_No'];
			$Ref_Number 		= $parameters['Reference_Number'];
			$INV_AMOUNT_PPN 	= $parameters['INV_AMOUNT_PPN'];
			$JournalH_Date 		= $parameters['JournalH_Date'];
			$SPLCATEG 			= $parameters['SPL_CATEG'];
			$accYr				= date('Y', strtotime($JournalH_Date));

			$proj_CodeHO		= "";
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$PRJ_isHO		= $rowPRJHO->isHO;
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
			endforeach;

			$OtherDesc 		= '';
			if(isset($parameters['Other_Desc']))
				$OtherDesc	= $parameters['Other_Desc'];

			$SPLCODE 		= '';
			if(isset($parameters['SPLCODE']))
				$SPLCODE	= $parameters['SPLCODE'];

			$SPLDESC 		= '';
			if(isset($parameters['SPLDESC']))
				$SPLDESC	= $parameters['SPLDESC'];
			
			$Other_Desc 	= "$SPLDESC. $OtherDesc $Manual_No";
			$Other_DescPPN 	= "$SPLDESC. Serial No. $TAXCODE_PPN. $OtherDesc $Manual_No";
			
			// ---------------- START 01: Pembuatan Journal Detail PPN Masukan ----------------	

				// ------------------------------- D E B I T : PPN KELUARAN -------------------------------
				
					// START : 12-12-2021
						$sqlL_D		= "SELECT ACC_ID_IRPPN FROM tglobalsetting";
						$resL_D 	= $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM		= $rowL_D->ACC_ID_IRPPN;

							$transacValueD	= $INV_AMOUNT_PPN;
							$Acc_Name 		= "-";
							$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'
												AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm			= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
											Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter,
											curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC, isTax)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$PRJCODE', 'IDR', $transacValueD, 
											$transacValueD, $transacValueD, 'Default',
											1, 0, 'D', '$Other_DescPPN', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC', 1)";
							$this->db->query($sqlGEJDD);
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
															Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					// END : 12-12-2021


				// ------------------------------- K R E D I T : HUT. SUPPLIER -------------------------------

					// CATATAN 25 OKT. 2022 : SISI HUT. SUPPLIER AKAN DIBENTUK TERAKHIR SETELAH PPN DAN RETENSI (FUNCTION PINV_HUTSPL)
				
					// START : 02-11-2021
						/*$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								if($ACC_NUM == '')
									$ACC_NUM	= "2101100";

								$transacValueK	= $INV_AMOUNT_PPN;
								$Acc_Name 		= "-";
								$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm			= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
												curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
												$transacValueK, $transacValueK, 'Default',
												1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}*/
					// END : 02-11-2021

				// ---------------- END 01: Pembuatan Journal Detail PPN Masukan ----------------	

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.JournalType = B.JournalType, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_POTOTH')		// PINV_POTOTH = Potongan Lainnya (Direct COA)
		{
			$ACC_NUM			= $parameters['ACC_ID']; 
			$PRJCODE			= $parameters['PRJCODE']; 
			$JournalH_Code 		= $parameters['JournalH_Code'];
			$Manual_No			= $parameters['Manual_No'];
			$INV_AMOUNT_POTOTH 	= $parameters['INV_AMOUNT_POTOTH'];
			$JournalH_Date 		= $parameters['JournalH_Date'];
			$SPLCATEG 			= $parameters['SPL_CATEG'];
			$accYr				= date('Y', strtotime($JournalH_Date));

			$proj_CodeHO		= "";
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$PRJ_isHO		= $rowPRJHO->isHO;
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
			endforeach;
			
			// ---------------- START 01: Pembuatan Journal Detail PPN Masukan ----------------	

				// ------------------------------- K R E D I T : POT. LAIN-LAIN -------------------------------
				
					// START : 02-23-2022
						$Other_Desc = "Potongan Lain-Lain Voucher No. ($Manual_No)";

						$transacValueK	= $INV_AMOUNT_POTOTH;
						$Acc_Name 		= "-";
						$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE'
											AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm			= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
										Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter,
										curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
									VALUES ('$JournalH_Code', '$ACC_NUM', '$PRJCODE', 'IDR', $transacValueK, 
										$transacValueK, $transacValueK, 'Default',
										1, 0, 'K', '$Other_Desc', '$Manual_No', '$Acc_Name', '$proj_CodeHO')";
						$this->db->query($sqlGEJDD);
							
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
														Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 02-23-2022

						
				// ------------------------------- D E B I T : HUT. SUPPLIER -------------------------------
				
					// START : 02-11-2021
						/* HIDE ON 06/02/2023
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								if($ACC_NUM == '')
									$ACC_NUM	= "2101100";

								$transacValueD	= $INV_AMOUNT_POTOTH;
								$Acc_Name 		= "-";
								$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm			= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter,
															curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$PRJCODE', 'IDR', $transacValueD, 
															$transacValueD, $transacValueD, 'Default',
															1, 0, 'D', '$Other_Desc', '$Manual_No', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValueD,
																Base_Debet = Base_Debet+$transacValueD,
																COA_Debet = COA_Debet+$transacValueD
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
																Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}*/
					// END : 02-11-2021

				// ---------------- END 01: Pembuatan Journal Detail PPN Masukan ----------------	

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.JournalType = B.JournalType, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_POTDP')			// PINV_NEW = Purchase Invoice
		{
			$JournalH_Code 		= $parameters['JournalH_Code'];
			$Manual_No			= $parameters['Manual_No'];
			$SPLCATEG 			= $parameters['SPL_CATEG'];
			$INV_AMOUNT_DPB		= $parameters['INV_AMOUNT_DPB'];		// OK
			$REF_CODE			= $parameters['REF_CODE'];
			$Other_Desc			= $parameters['Other_Desc'];
			$TTK_CODE			= $parameters['TTK_CODE'];
			$UM_REF				= $parameters['UM_REF'];
			$PRJCODE			= $parameters['PRJCODE'];
			$SPLCODE			= $parameters['SPLCODE'];
			$SPLDESC			= $parameters['SPLDESC'];

			$proj_CodeHO		= "";
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$PRJ_isHO		= $rowPRJHO->isHO;
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
			endforeach;

			// ------------------------------- D E B E T HUT. SUPPLIER -------------------------------

					// SISI DEBET DIAMBIL DARI KREDIT SESUAI SETTINGAN PER KATEGORI SUPPLIER

					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							if($ACC_NUM == '')
								$ACC_NUM	= "2102.01";

							$trxValueD	= $INV_AMOUNT_DPB;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{*/
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $trxValueD,
														$trxValueD, $trxValueD, 'Default',
														1, 0, 'K', '$SPLDESC. $Other_Desc (Potongan UM)', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								/*}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValueK,
															Base_Kredit = Base_Kredit+$transacValueK,
															COA_Kredit = COA_Kredit+$transacValueK
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}*/
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueGT,
														Base_Kredit2 = Base_Kredit2+$transacValueGT
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$trxValueD,
															Base_Kredit2 = Base_Kredit2+$trxValueD, BaseK_$accYr = BaseK_$accYr+$trxValueD
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
			

			// ------------------------------- K R E D I T HUT. SUPPLIER -------------------------------

				$Other_Desc = "Pengembalian UM INV $REF_CODE";
				$Ref_Number = $JournalH_Code;
				
				$sqlCL_K	= "tglobalsetting";
				$this->db->count_all($sqlCL_K);				
				$resCL_K	= $this->db->count_all($sqlCL_K);
				if($resCL_K > 0)
				{
					$sqlL_K	= "SELECT ACC_ID_RDP FROM tglobalsetting";
					$resL_K = $this->db->query($sqlL_K)->result();					
					foreach($resL_K as $rowL_K):
						$ACC_NUM	= $rowL_K->ACC_ID_RDP;
						if($ACC_NUM == '')
							$ACC_NUM	= "2101100";

						$transacValueK	= $INV_AMOUNT_DPB;
						$Acc_Name 		= "-";
						$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm			= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
													curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
													$transacValueK, $transacValueK, 'Default',
													1, 0, 'K', '$SPLDESC. $Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
														JournalD_Kredit = JournalD_Kredit+$transacValueK,
														Base_Kredit = Base_Kredit+$transacValueK,
														COA_Kredit = COA_Kredit+$transacValueK
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueGT,
													Base_Kredit2 = Base_Kredit2+$transacValueGT
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
														Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					endforeach;
				}

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.JournalType = B.JournalType, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV_HUTSPL')		// PINV_NEW = Purchase Invoice
		{
			$PRJCODE			= $parameters['PRJCODE']; 
			$JournalH_Code 		= $parameters['JournalH_Code'];
			$Manual_No			= $parameters['Manual_No'];
			$Ref_Number 		= $parameters['Reference_Number'];
			$JournalH_Date 		= $parameters['JournalH_Date'];
			$SPLCATEG 			= $parameters['SPL_CATEG'];
			$ITM_PRICE 			= $parameters['ITM_PRICE'];
			$HUT_POSIT 			= $parameters['HUT_POSIT'];
			$accYr				= date('Y', strtotime($JournalH_Date));

			$proj_CodeHO		= "";
			$sqlPRJHO 			= "SELECT isHO, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJHO			= $this->db->query($sqlPRJHO)->result();
			foreach($resPRJHO as $rowPRJHO):
				$PRJ_isHO		= $rowPRJHO->isHO;
				$proj_CodeHO	= $rowPRJHO->PRJCODE_HO;
			endforeach;

			$OtherDesc 		= '';
			if(isset($parameters['Other_Desc']))
				$OtherDesc	= $parameters['Other_Desc'];

			$SPLCODE 		= '';
			if(isset($parameters['SPLCODE']))
				$SPLCODE	= $parameters['SPLCODE'];

			$SPLDESC 		= '';
			if(isset($parameters['SPLDESC']))
				$SPLDESC	= $parameters['SPLDESC'];
			
			$Other_Desc 	= "$SPLDESC. $OtherDesc $Manual_No";
			
			// ---------------- START 01: Pembuatan Journal Detail PPN Masukan ----------------	

				// ------------------------------- D E B I T : PPN KELUARAN -------------------------------


				// ------------------------------- K R E D I T : HUT. SUPPLIER -------------------------------

					// CATATAN 25 OKT. 2022 : SISI HUT. SUPPLIER AKAN DIBENTUK TERAKHIR SETELAH PPN DAN RETENSI (FUNCTION PINV_HUTSPL)
					if($HUT_POSIT == 'K')
					{
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;

								$transacValueK	= $ITM_PRICE;
								$Acc_Name 		= "-";
								$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm			= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
												curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
												$transacValueK, $transacValueK, 'Default',
												1, 0, 'K', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
																Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					}
					else
					{
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;

								$transacValueK	= $ITM_PRICE;
								$Acc_Name 		= "-";
								$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm			= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
												curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueK,
												$transacValueK, $transacValueK, 'Default',
												1, 0, 'D', '$Other_Desc', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
								$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueK,
																Base_Debet2 = Base_Debet2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					}

				// ---------------- END 01: Pembuatan Journal Detail PPN Masukan ----------------	

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.JournalType = B.JournalType, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP')		// BP = Bank Payment
		{
			$SPLCATEG		= $data[1];
			$PPhTax 		= $parameters['PPhTax'];
			$PPhAmount 		= $parameters['PPhAmount'];
			$DiscAmount		= $parameters['DiscAmount'];		// potongan per inv
			$DPAmount		= $parameters['DPAmount'];			// potongan DP inv
			
			$PPhAmount 		= $parameters['PPhAmount'];
			$InvAmount_PPn	= $parameters['InvAmount_PPn'];
			$InvAmount_PPh	= $parameters['InvAmount_PPh'];
			$InvAmount_Ret	= $parameters['InvAmount_Ret'];
			$InvAmount_Disc	= $parameters['InvAmount_Disc'];
			$Ref_Number		= $parameters['Ref_Number'];
			$CB_NOTES		= $parameters['CB_NOTES'];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				// $ACC_ID_D		= '2101100';		// HUTANG SUPPLIER - DEFAULT
				// $transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				// PPhAmount dan DiscAmount memiliki sifat memotong nominal Invoice
				
				//$transacValue	= $transacValuex + $AMOUNT_PPN - $PPhAmount - $DiscAmount;
				$transacValue	= $transacValuex + $DiscAmount + $DPAmount; // Nilai asli hutang sebelum dipotong diskon per invoice
				$transacValue1	= $transacValuex - $DPAmount; 				// Nilai sudah - $DiscAmount per invoice;
				
				// karena pada saat pembayaran, potongan diskon dan dp tidak langsung mengurangi nilai pembayaran,
				// maka nilai originalnya tetap, tanpa da penambahan dikson ataupotongan  DP terlebih dahulu
				$transacValue	= $transacValuex;
				$transacValue1	= $transacValuex - $DiscAmount - $DPAmount; // Nilai sudah - $DiscAmount per invoice;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk pembayaran di sisi Debit
					// didapatkan dari setinga kategori supplier
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";			
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', '$CB_NOTES', '$Ref_Number', '$Acc_Name',
														'$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
						endforeach;
					}
					else // if not setting in Link Account set to 2111 = Hutang Supplier/Sewa Belum Difakturkan
					{
						$ACC_NUM	= "HUT_SPL"; // Hutang Supplier/Sewa
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D', 'Hut. Spl but Not Set', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
				
					// Cek Link Account untuk PPh
					// didapatkan dari setingan LA Pajak
					// hasil meeting tgl 27 Des 2017, tidak ada jurnal PPh
					$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKIN != ''";			
					$resTLAC	= $this->db->count_all($sqlTLAC);			
					if($resTLAC > 0)
					{
						$TAXLA_LINKIN	= '';
						$sqlTLA			= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
						$this->db->query($sqlTLA);
						$resTLA 		= $this->db->query($sqlTLA)->result();					
						foreach($resTLA as $rowTLA):
							$TAXLA_LINKIN	= $rowTLA->TAXLA_LINKIN;
							$ACC_NUM	= $rowTLA->TAXLA_LINKIN;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Acc_Id = '$TAXLA_LINKIN'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$TAXLA_LINKIN', '$proj_Code', 'IDR', $PPhAmount, 
														$PPhAmount, $PPhAmount, 'Default', 1, 0, 'D', 'PPh', '$Acc_Name', '$proj_CodeHO')";
										//$this->db->query($sqlGEJDD); HOLDED ON 27 DES 2018
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$PPhAmount,
															Base_Debet = Base_Debet+$PPhAmount, COA_Debet = COA_Debet+$PPhAmount
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Acc_Id = '$TAXLA_LINKIN'";
										//$this->db->query($sqlUpdCOAD); HOLDED ON 27 DES 2018
								}
								
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPhAmount,
													Base_Debet2 = Base_Debet2+$PPhAmount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPhAmount,
															Base_Debet2 = Base_Debet2+$PPhAmount, BaseD_$accYr = BaseD_$accYr+$PPhAmount
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKIN'";
										//$this->db->query($sqlUpdCOA); HOLDED ON 27 DES 2018
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					
				// ------------------------------- K R E D I T KAS / BANK -------------------------------
				
				$ACC_ID_K	= $ACC_ID;				// BANK ACCOUNT SELECTED
				$ACC_NUM	= $ACC_ID;
				$Acc_Name 	= "-";
				$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resNm		= $this->db->query($sqlNm)->result();
				foreach($resNm as $rowNm):
					$Acc_Name	= $rowNm->Account_NameId;
				endforeach;
				
				// START : Save Journal Detail - Kredit
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
								VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue1, $transacValue1,
									$transacValue1, 'Default', 1, 0, 'K', '$CB_NOTES (Pemb. frm CB)', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
					$this->db->query($sqlGEJDK);
				// END : Save Journal Detail - Kredit
										
				// START : Update to COA - Kredit
					/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue1, 
											Base_Kredit2 = Base_Kredit2+$transacValue1
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K'";
					$this->db->query($sqlUpdCOAD); // OK*/
							
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue1, 
												Base_Kredit2 = Base_Kredit2+$transacValue1, BaseK_$accYr = BaseK_$accYr+$transacValue1
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Kredit
				
				// Cek Link Account untuk PPh
				// didapatkan dari setinga LA Pajak
				// hasil meeting tgl 27 Des 2017, tidak ada jurnal PPh
				$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKOUT != ''";			
				$resTLAC	= $this->db->count_all($sqlTLAC);			
				if($resTLAC > 0)
				{
					$TAXLA_LINKOUT	= '';
					$sqlTLA			= "SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
					$resTLA 		= $this->db->query($sqlTLA)->result();					
					foreach($resTLA as $rowTLA):
						$TAXLA_LINKOUT	= $rowTLA->TAXLA_LINKOUT;
						$ACC_NUM	= $TAXLA_LINKOUT;
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Acc_Id = '$TAXLA_LINKOUT'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$TAXLA_LINKOUT', '$proj_Code', 'IDR', $PPhAmount, 
													$PPhAmount, $PPhAmount, 'Default', 1, 0, 'K', 'PPh', '$Acc_Name', '$proj_CodeHO')";
									//$this->db->query($sqlGEJDD); HOLDED ON 27 DES 2018
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$PPhAmount,
														Base_Kredit = Base_Kredit+$PPhAmount, COA_Kredit = COA_Kredit+$PPhAmount
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Acc_Id = '$TAXLA_LINKOUT'";
									//$this->db->query($sqlUpdCOAD); HOLDED ON 27 DES 2018
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPhAmount,
												Base_Kredit2 = Base_Kredit2+$PPhAmount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKOUT'";
							$this->db->query($sqlUpdCOAD); // OK*/
						
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKOUT' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPhAmount,
														Base_Kredit2 = Base_Kredit2+$PPhAmount, BaseK_$accYr = BaseK_$accYr+$PPhAmount
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKOUT'";
									//$this->db->query($sqlUpdCOA); HOLDED ON 27 DES 2018
								}
							}
						// END : Update to COA - Debit
					endforeach;
				}
				
				// ------------------------------- K R E D I T POTONGAN PEMBAYARAN -------------------------------
				// START : 12-12-2018
				if($DiscAmount > 0)
				{
					$ACC_NUM	= "2111XX"; // Hutang Supplier/Sewa Belum Difakturkan
					$sqlCL_K	= "tglobalsetting";
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID_POT;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Ref_Number, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $DiscAmount,
													$DiscAmount, $DiscAmount, 'Default', 1, 0, 'K', '$CB_NOTES (Disc. Payment)', '$Ref_Number', '$Acc_Name',
													'$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$DiscAmount
															Base_Kredit = Base_Kredit+$DiscAmount, 
															COA_Kredit = COA_Kredit+$DiscAmount
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$DiscAmount, 
															Base_Kredit2 = Base_Kredit2+$DiscAmount, BaseK_$accYr = BaseK_$accYr+$DiscAmount
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
				}
				// END : 12-12-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-NEW')		// BP = Bank Payment
		{
			$SPLCATEG			= $data[1];
			$Manual_No 			= $parameters['Manual_No'];
			$Ref_Number			= $parameters['Ref_Number'];
			$Faktur_No 			= $parameters['Faktur_No'];
			$Faktur_Code		= $parameters['Faktur_Code'];
			$Other_Desc			= $parameters['Other_Desc'];
			$CB_NOTES			= $parameters['CB_NOTES'];
			$SPLCAT				= $parameters['SPLCAT'];
			$INV_AMOUNT			= $parameters['INV_AMOUNT'];
			$INV_AMOUNT_PPH		= $parameters['TAXPRICE2'];
			$CBD_AMOUNT			= $parameters['CBD_AMOUNT'];
			$AMOUNT_POT_DP		= $parameters['AMOUNT_POT_DP'];
			$DP_NUM 			= $parameters['DP_NUM'];
			$CBD_AMOUNT_DISC	= $parameters['CBD_AMOUNT_DISC'];
			$TOT_CBDAMOUNT		= $parameters['TOT_CBDAMOUNT'];
			$TOT_CBDAMOUNT_DISC = $parameters['TOT_CBDAMOUNT_DISC'];
			$CB_SOURCE			= $parameters['CB_SOURCE'];

			$PRJCODEVW 			= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

			//$TOT_PAYMENT		= $CBD_AMOUNT + $CBD_AMOUNT_DISC + $INV_AMOUNT_PPH;
			$TOT_PAYMENT		= $CBD_AMOUNT + $CBD_AMOUNT_DISC;

			$SPLCATEG 			= $SPLCAT;
			// $transacValue1 	= $CBD_AMOUNT;
			// $DiscAmount 		= $CBD_AMOUNT_DISC;
			$transacValue1 		= $TOT_CBDAMOUNT; // Total Hut Sup yg dibayar
			$DiscAmount 		= $TOT_CBDAMOUNT_DISC; // Total Disc

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValueD 	= $TOT_PAYMENT;

				// PERHATIKAN ..... !!!! JIKA ADA PPH_FINAL
				// APAKAH AKUN PPH INI TERMASUK PPH FINAL ATAU NON FINAL
				// JIKA PPH FINAL, MAKA DI JOURNAL PADA SAAT PEMBAYARAN
				// JIKA PPH NON FINAL, DIBENTUK PADA SAAT VOUCHER/FAKTUR

				$POT_PPHFIN 	= 0;
				$transacValueK 	= 0;
				$ACC_ID_PPH 	= "";
				$ACC_ID_PPHNM 	= "";
				if($INV_AMOUNT_PPH > 0)
				{
					$TAXCODE_PPH 	= "NOT_SET_ACCPPH";
					if($CB_SOURCE == 'VCASH')
					{
						$s_PPH	= "SELECT PPH_Code FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$Faktur_No' AND (PPH_Code IS NOT NULL AND PPH_Code != '')";
						$r_PPH 	= $this->db->query($s_PPH)->result();					
						foreach($r_PPH as $rw_PPH):
							$TAXCODE_PPH	= $rw_PPH->PPH_Code;
						endforeach;
					}
					elseif($CB_SOURCE == 'PINV')
					{
						$TAXCODE_PPH 	= $TAXCODE2;
					}
					else
					{
						$s_PPH	= "SELECT TAXCODE_PPH FROM tbl_pinv_detail WHERE INV_NUM = '$Faktur_No'";
						$r_PPH 	= $this->db->query($s_PPH)->result();					
						foreach($r_PPH as $rw_PPH):
							$TAXCODE_PPH	= $rw_PPH->TAXCODE_PPH;
						endforeach;
					}

					$sqlCL_K	= "tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE_PPH'";			
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT TAXLA_LINKIN, TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE_PPH'";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->TAXLA_LINKIN;
						endforeach;
					}
					else
					{
						$ACC_NUM	= "VC.PPH.$REF_CODE";
					}

					$isPPhFin 		= 0;
					$transacValueK	= $INV_AMOUNT_PPH;
					$ACC_ID_PPHNM	= "NO ACC. SELECTED";
					$sqlNm 			= "SELECT Account_NameId, isPPhFinal FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm			= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$ACC_ID_PPHNM	= $rowNm->Account_NameId;
						$isPPhFin		= $rowNm->isPPhFinal;
					endforeach;
					$ACC_ID_PPH 	= $ACC_NUM;
					
					if($isPPhFin == 1)			// 0 PPH NON-FINAL 1 PPH FINAL
					{
						$POT_PPHFIN 	= $POT_PPHFIN+$INV_AMOUNT_PPH;
					}
				}

				//$transacValue = $transacValueD-$POT_PPHFIN;
				$transacValue 	= $transacValueD+$POT_PPHFIN-$AMOUNT_POT_DP;

				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk pembayaran di sisi Debit
					// didapatkan dari setinga kategori supplier
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";			
					$resCL_D	= $this->db->count_all($sqlCL_D);

					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' AND Faktur_No = '$Faktur_No'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{*/
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Manual_No, Ref_Number, Faktur_No, Faktur_Code, Acc_Name, proj_CodeHO,
														SPLCODE, SPLDESC)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
														$transacValue, $transacValue, $transacValue, 'Default', 1, 0,
														'D', '$Other_Desc', '$Manual_No', '$Ref_Number', '$Faktur_No', '$Faktur_Code', '$Acc_Name', '$proj_CodeHO',
														'$SPLCODE', '$SPLDESC')";
										$this->db->query($sqlGEJDD);
								/*}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' AND Faktur_No = '$Faktur_No'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}*/
								
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
						endforeach;
					}
					else // if not setting in Link Account set to 2111 = Hutang Supplier/Sewa Belum Difakturkan
					{
						if($CB_SOURCE == 'VCASH')
						{
							$ACC_NUM 	= $parameters['ACCID_D'];
						}
						else
						{
							$ACC_NUM	= "NOT_SET_ACC"; // Hutang Supplier/Sewa
						}

						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Manual_No,
											Ref_Number, Faktur_No, Faktur_Code, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
											$transacValueD, $transacValueD, $transacValueD, 'Default', 1, 0, 'D', '$Other_Desc', '$Manual_No',
											'$Ref_Number', '$Faktur_No', '$Faktur_Code', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
						$this->db->query($sqlGEJDD);
					}

					if($AMOUNT_POT_DP > 0)
					{
						$DP_CODE 	= "";
						$OPSPK_CODE = "";
						$s_01 		= "SELECT DP_CODE, DP_REFCODE FROM tbl_dp_header WHERE DP_NUM = '$DP_NUM' LIMIT 1";
						$r_01 		= $this->db->query($s_01)->result();
						foreach($r_01 as $rw_01) :
							$DP_CODE 	= $rw_01->DP_CODE;
							$OPSPK_CODE	= $rw_01->DP_REFCODE;
						endforeach;

						$s_02 	= "SELECT Acc_Id FROM tbl_journaldetail_$PRJCODEVW
									WHERE JournalH_Code IN (SELECT JournalH_Code FROM tbl_bp_detail WHERE CBD_DOCNO = '$DP_NUM') AND Journal_DK = 'D' LIMIT 1";
						$r_02 	= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02) :
							$ACC_IDUM 	= $rw_02->Acc_Id;
						endforeach;
						if($ACC_IDUM == '')
							$ACC_IDUM= "NA_POTUM";

						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_IDUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
										JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
										Journal_DK, Other_Desc, Manual_No, Ref_Number, Faktur_No, Faktur_Code, Acc_Name, proj_CodeHO,
										SPLCODE, SPLDESC)
									VALUES ('$JournalH_Code', '$ACC_IDUM', '$proj_Code', 'IDR', 
										$AMOUNT_POT_DP, $AMOUNT_POT_DP, $AMOUNT_POT_DP, 'Default', 1, 0,
										'D', '$Other_Desc Potongan DP $DP_CODE - $OPSPK_CODE', '$Manual_No', '$Ref_Number', '$Faktur_No', '$Faktur_Code', '$Acc_Name', '$proj_CodeHO',
										'$SPLCODE', '$SPLDESC')";
						$this->db->query($sqlGEJDD);

						
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_IDUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$AMOUNT_POT_DP,
														Base_Debet2 = Base_Debet2+$AMOUNT_POT_DP, BaseD_$accYr = BaseD_$accYr+$AMOUNT_POT_DP
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_IDUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}

				// ------------------------------- END D E B I T -------------------------------


				// ------------------------------- K R E D I T -------------------------------
					// START : 03-08-2022
						if($INV_AMOUNT_PPH > 0 && $isPPhFin == 1)			// 0 PPH NON-FINAL 1 PPH FINAL
						{
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_ID_PPH'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								//if($resCGEJ == 0)
								//{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Manual_No, Ref_Number, Acc_Name, proj_CodeHO,
														SPLCODE, SPLDESC, isTax)
													VALUES ('$JournalH_Code', '$ACC_ID_PPH', '$proj_Code', 'IDR', 
														$transacValueK, $transacValueK, $transacValueK, 'Default', 1, 0,
														'K', '$ACC_ID_PPHNM. $Other_Desc', '$Manual_No', '$Ref_Number', '$ACC_ID_PPHNM', '$proj_CodeHO',
														'$SPLCODE', '$SPLDESC', 1)";
										$this->db->query($sqlGEJDD);
								/*}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValueK,
															Base_Kredit = Base_Kredit+$transacValueK,
															COA_Kredit = COA_Kredit+$transacValueK
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}*/

							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_PPH' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();

								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);

								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										if($SYNC_PRJ == '')
											$SYNC_PRJ = $proj_Code;

										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK,
															Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_PPH'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 03-08-2022
				// ------------------------------- END K R E D I T -------------------------------
						
					
				/* ------------------------------- K R E D I T KAS / BANK (Dipindahkan diluar detail hut. supplier => tgl. 2022-02-18)

					$ACC_ID_K	= $ACC_ID;				// BANK ACCOUNT SELECTED
					$ACC_NUM	= $ACC_ID;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_ID_K'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);

						if($resCGEJ == 0)
						{
							// START : Save Journal Detail - Kredit
								$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
												Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Manual_No, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue1, $transacValue1,
												$transacValue1, 'Default', 1, 0, 'K', '$CB_NOTES (Pemb. frm CB)', '$Manual_No', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDK);
							// END : Save Journal Detail - Kredit
						}
						else
						{
							$sqlUpdCOAK	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue1,
									Base_Kredit = Base_Kredit+$transacValue1, COA_Kredit = COA_Kredit+$transacValue1
								WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
									AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOAK);
						}
					
											
					// START : Update to COA - Kredit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue1, 
													Base_Kredit2 = Base_Kredit2+$transacValue1, BaseK_$accYr = BaseK_$accYr+$transacValue1
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Kredit
					
					// ------------------------------- K R E D I T POTONGAN PEMBAYARAN -------------------------------

						// START : 12-12-2018
						if($DiscAmount > 0)
						{
							$ACC_NUM	= "2111XX"; // Hutang Supplier/Sewa Belum Difakturkan
							$sqlCL_K	= "tglobalsetting";
							$resCL_K	= $this->db->count_all($sqlCL_K);
							if($resCL_K > 0)
							{
								$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
								$resL_K = $this->db->query($sqlL_K)->result();					
								foreach($resL_K as $rowL_K):
									$ACC_NUM	= $rowL_K->ACC_ID_POT;
									$Acc_Name 	= "-";
									$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm		= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;
									
									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
										$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
															AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$resCGEJ	= $this->db->count_all($sqlCGEJ);
										
										if($resCGEJ == 0)
										{
												$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
																Currency_id, JournalD_Kredit, Base_Kredit, 
																COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, Manual_No, Acc_Name, proj_CodeHO)
															VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $DiscAmount,
															$DiscAmount, $DiscAmount, 'Default', 1, 0, 'K', '$CB_NOTES (Disc. Payment)', '$Manual_No', '$Acc_Name',
															'$proj_CodeHO')";
												$this->db->query($sqlGEJDD);
										}
										else
										{
												$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																	JournalD_Kredit = JournalD_Kredit+$DiscAmount
																	Base_Kredit = Base_Kredit+$DiscAmount, 
																	COA_Kredit = COA_Kredit+$DiscAmount
																WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																	AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
												$this->db->query($sqlUpdCOAD);
										}
										
									// START : Update to COA - Debit
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$DiscAmount, 
																	Base_Kredit2 = Base_Kredit2+$DiscAmount, BaseK_$accYr = BaseK_$accYr+$DiscAmount
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								endforeach;
							}
						}
						// END : 12-12-2018
					// ------------------------------- END K R E D I T POTONGAN PEMBAYARAN -------------------------------

				// ------------------------------- END K R E D I T KAS / BANK ------------------------------- */

			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-PD')			// BP = Bank Payment PD
		{
			$SPLCATEG			= $data[1];
			$Manual_No 			= $parameters['Manual_No'];
			$Ref_Number			= $parameters['Ref_Number'];
			$Faktur_No 			= $parameters['Faktur_No'];
			$Faktur_Code		= $parameters['Faktur_Code'];
			$Other_Desc			= $parameters['Other_Desc'];
			$CB_NOTES			= $parameters['CB_NOTES'];
			$SPLCAT				= $parameters['SPLCAT'];
			$INV_AMOUNT			= $parameters['INV_AMOUNT'];
			$CBD_AMOUNT			= $parameters['CBD_AMOUNT'];
			$CBD_AMOUNT_DISC	= $parameters['CBD_AMOUNT_DISC'];
			$TOT_CBDAMOUNT		= $parameters['TOT_CBDAMOUNT'];
			$TOT_CBDAMOUNT_DISC = $parameters['TOT_CBDAMOUNT_DISC'];
			$CB_SOURCE			= $parameters['CB_SOURCE'];

			$TOT_PAYMENT		= $CBD_AMOUNT + $CBD_AMOUNT_DISC;

			$SPLCATEG 			= $SPLCAT;
			// $transacValue1 	= $CBD_AMOUNT;
			// $DiscAmount 		= $CBD_AMOUNT_DISC;
			$transacValue1 		= $TOT_CBDAMOUNT; // Total Hut Sup yg dibayar
			$DiscAmount 		= $TOT_CBDAMOUNT_DISC; // Total Disc

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue 	= $TOT_PAYMENT;
				
				// ------------------------------- START : D E B I T -------------------------------

					//$ACC_NUM 		= $parameters['ACCID_D'];
					$ACC_NUM 		= "Not Sett in Global setting";
                	$s_01 			= "SELECT ACC_ID_PERSL FROM tglobalsetting";
                	$r_01 			= $this->db->query($s_01)->result();
                	foreach($r_01 as $rw_01):
                		$ACC_NUM 	= $rw_01->ACC_ID_PERSL;					// ACC_ID PINJAMAN DINAS
                	endforeach;

					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code'
									AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
									JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
									Journal_DK, Other_Desc, Manual_No, Ref_Number, Faktur_No, Faktur_Code, Acc_Name,
									proj_CodeHO, SPLCODE, SPLDESC)
								VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
									$transacValue, $transacValue, 'Default', 1, 0, 'D', '$Other_Desc', '$Manual_No', '$Ref_Number', '$Faktur_No', '$Faktur_Code', '$Acc_Name',
									'$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
					$this->db->query($sqlGEJDD);
						
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit

				// ------------------------------- END : D E B I T -------------------------------
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-PPD')		// BP = Bank Payment PPD
		{
			$SPLCATEG			= $data[1];
			$Manual_No 			= $parameters['Manual_No'];
			$Ref_Number			= $parameters['Ref_Number'];
			$Faktur_No 			= $parameters['Faktur_No'];
			$Faktur_Code 		= $parameters['Faktur_Code'];
			$Other_Desc			= $parameters['Other_Desc'];
			$CB_NOTES			= $parameters['CB_NOTES'];
			$SPLCAT				= $parameters['SPLCAT'];
			$INV_AMOUNT			= $parameters['INV_AMOUNT'];
			$CBD_AMOUNT			= $parameters['CBD_AMOUNT'];
			$CBD_AMOUNT_DISC	= $parameters['CBD_AMOUNT_DISC'];
			$TOT_CBDAMOUNT		= $parameters['TOT_CBDAMOUNT'];
			$TOT_CBDAMOUNT_DISC = $parameters['TOT_CBDAMOUNT_DISC'];
			$CB_SOURCE			= $parameters['CB_SOURCE'];

			$TOT_PAYMENT		= $CBD_AMOUNT + $CBD_AMOUNT_DISC;

			$SPLCATEG 			= $SPLCAT;
			// $transacValue1 	= $CBD_AMOUNT;
			// $DiscAmount 		= $CBD_AMOUNT_DISC;
			$transacValue1 		= $TOT_CBDAMOUNT; // Total Hut Sup yg dibayar
			$DiscAmount 		= $TOT_CBDAMOUNT_DISC; // Total Disc

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue 	= $TOT_PAYMENT;
				
				// ------------------------------- START : D E B I T -------------------------------

					//$ACC_NUM 		= $parameters['ACCID_D'];
					$ACC_NUM 		= "Not Sett in Global setting";
                	$s_01 			= "SELECT ACC_ID_PERSL FROM tglobalsetting";
                	$r_01 			= $this->db->query($s_01)->result();
                	foreach($r_01 as $rw_01):
                		$ACC_NUM 	= $rw_01->ACC_ID_PERSL;					// ACC_ID PINJAMAN DINAS
                	endforeach;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code'
									AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
										JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
										Journal_DK, Other_Desc, Manual_No, Ref_Number, Faktur_No, Faktur_Code, Acc_Name,
										proj_CodeHO, SPLCODE, SPLDESC)
									VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
										$transacValue, $transacValue, 'Default', 1, 0, 'D', '$Other_Desc', '$Manual_No', '$Ref_Number', '$Faktur_No', '$Faktur_Code', '$Acc_Name',
										'$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
					$this->db->query($sqlGEJDD);

						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit

				// ------------------------------- END : D E B I T -------------------------------
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-ACC')		// BP = Bank Payment PPD
		{
			$SPLCATEG			= $data[1];
			$Manual_No 			= $parameters['Manual_No'];
			$Ref_Number			= $parameters['Ref_Number'];
			$Faktur_No 			= $parameters['Faktur_No'];
			$Other_Desc			= $parameters['Other_Desc'];
			$CB_NOTES			= $parameters['CB_NOTES'];
			$SPLCAT				= $parameters['SPLCAT'];
			$INV_AMOUNT			= $parameters['INV_AMOUNT'];
			$CBD_AMOUNT			= $parameters['CBD_AMOUNT'];
			$CBD_AMOUNT_DISC	= $parameters['CBD_AMOUNT_DISC'];
			$TOT_CBDAMOUNT		= $parameters['TOT_CBDAMOUNT'];
			$TOT_CBDAMOUNT_DISC = $parameters['TOT_CBDAMOUNT_DISC'];
			$CB_SOURCE			= $parameters['CB_SOURCE'];

			$TOT_PAYMENT		= $CBD_AMOUNT + $CBD_AMOUNT_DISC;

			$SPLCATEG 			= $SPLCAT;
			// $transacValue1 	= $CBD_AMOUNT;
			// $DiscAmount 		= $CBD_AMOUNT_DISC;
			$transacValue1 		= $TOT_CBDAMOUNT; // Total Hut Sup yg dibayar
			$DiscAmount 		= $TOT_CBDAMOUNT_DISC; // Total Disc
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue 	= $TOT_PAYMENT;
				
				// ------------------------------- START : D E B I T -------------------------------

					$ACC_NUM 	= $parameters['ACCID_D'];
					$ACC_BANK 	= $parameters['ACC_ID'];
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code'
									AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' AND Acc_Id = '$ACC_NUM'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);
					
						if($resCGEJ == 0)
						{
								/*$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Manual_No, Ref_Number, Faktur_No, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
												$transacValue, $transacValue, 'Default', 1, 0, 'D', '$Other_Desc', '$Manual_No', '$Ref_Number', '$Faktur_No', '$Acc_Name',
												'$proj_CodeHO')";
								$this->db->query($sqlGEJDD);*/

								$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
												Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect, Manual_No,
												ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
												proj_CodeHO, Acc_Name, ISPERSL) VALUES
												('$JournalH_Code', '$ACC_NUM', '$ACC_BANK', 'BP', '$proj_Code',
												'IDR', $transacValue, $transacValue, $transacValue, 1, 1, '$Manual_No',
												1, '$transacValue', '$Ref_Number', '$Other_Desc', 'D', 'BP', 0, 
												'$proj_CodeHO', '$Acc_Name', 0)";
								$this->db->query($insSQL);
						}
						else
						{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
													Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' AND Faktur_No = '$Faktur_No'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD);
						}
						
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit

				// ------------------------------- END : D E B I T -------------------------------
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-BYPD')		// BP = Bank Payment By PD
		{
			$Manual_No 			= $parameters['Manual_No'];;	// CB_CODE
			$Ref_Number			= $parameters['Ref_Number'];	// PD_NUM
			$PD_NUM				= $parameters['Ref_Number'];	// PD_NUM
			$Faktur_Code 		= $parameters['Faktur_Code'];	// PD_CODE
			$Faktur_Date 		= $parameters['Faktur_Date'];
			$Other_Desc			= $parameters['Other_Desc'];
			$CB_NOTES			= $parameters['CB_NOTES'];
			$SPLCATEG			= $parameters['SPLCAT'];
			$PD_PAYMENT			= $parameters['PD_PAYMENT'];
			$CB_SOURCE			= $parameters['CB_SOURCE'];
			$transacValue 		= $PD_PAYMENT;
			$curr_rate 			= 1;
			
			// ---------------- START : Pembuatan Journal Detail ----------------

				// ------------------------------- START K R E D I T POTONGAN PEMBAYARAN -------------------------------
					$ACC_UMPD 		= "Not Sett in Global setting";
                	$s_01 			= "SELECT ACC_ID_PERSL FROM tglobalsetting";
                	$r_01 			= $this->db->query($s_01)->result();
                	foreach($r_01 as $rw_01):
                		$ACC_UMPD 	= $rw_01->ACC_ID_PERSL;					// ACC_ID PINJAMAN DINAS
                	endforeach;

					$ACC_NUM 	= "";
					$s_02 		= "SELECT DISTINCT Acc_Id FROM tbl_journaldetail WHERE Faktur_No = '$PD_NUM' AND Journal_DK = 'D' AND JournalType = 'BP'";
					$r_02		= $this->db->query($s_02)->result();
					foreach($r_02 as $rw_02):
						$ACC_NUM	= $rw_02->Acc_Id;
					endforeach;
					if($ACC_NUM == "")
						$ACC_NUM= $ACC_UMPD;

					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code'
									AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code,  Currency_id,
									JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK,
									Other_Desc, Manual_No, Ref_Number, Faktur_No, Faktur_Code, Faktur_Date, Acc_Name, proj_CodeHO)
								VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
									$PD_PAYMENT, $PD_PAYMENT, $PD_PAYMENT, 'Default', 1, 0, 'K',
									'$Other_Desc', '$Manual_No', '$Ref_Number', '$Ref_Number', '$Faktur_Code', '$Faktur_Date', '$Acc_Name', '$proj_CodeHO')";
					$this->db->query($sqlGEJDD);
						
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PD_PAYMENT, 
													Base_Kredit2 = Base_Kredit2+$PD_PAYMENT, BaseK_$accYr = BaseK_$accYr+$PD_PAYMENT
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit

				// ------------------------------- END K R E D I T POTONGAN PEMBAYARAN -------------------------------

			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-RET')		// BP = Bank Payment Retensi
		{
			$SPLCATEG		= $data[1];
			$PPhTax 		= $parameters['PPhTax'];
			$PPhAmount 		= $parameters['PPhAmount'];
			$DiscAmount		= $parameters['DiscAmount'];		// potongan per inv
			$DPAmount		= $parameters['DPAmount'];			// potongan DP inv
			
			$PPhAmount 		= $parameters['PPhAmount'];
			$InvAmount_PPn	= $parameters['InvAmount_PPn'];
			$InvAmount_PPh	= $parameters['InvAmount_PPh'];
			$InvAmount_Ret	= $parameters['InvAmount_Ret'];
			$InvAmount_Disc	= $parameters['InvAmount_Disc'];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				// $ACC_ID_D		= '2101100';		// HUTANG SUPPLIER - DEFAULT
				// $transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				// PPhAmount dan DiscAmount memiliki sifat memotong nominal Invoice
				
				//$transacValue	= $transacValuex + $AMOUNT_PPN - $PPhAmount - $DiscAmount;
				$transacValue	= $transacValuex + $DiscAmount + $DPAmount; // Nilai asli hutang sebelum dipotong diskon per invoice
				$transacValue1	= $transacValuex - $DPAmount; 				// Nilai sudah - $DiscAmount per invoice;
				
				// karena pada saat pembayaran, potongan diskon dan dp tidak langsung mengurangi nilai pembayaran,
				// maka nilai originalnya tetap, tanpa da penambahan dikson ataupotongan  DP terlebih dahulu
				$transacValue	= $transacValuex;
				$transacValue1	= $transacValuex - $DiscAmount - $DPAmount; // Nilai sudah - $DiscAmount per invoice;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk pembayaran di sisi Debit
					// didapatkan dari setinga kategori supplier
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";			
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'Pemb. frm CB', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
						endforeach;
					}
					else // if not setting in Link Account set to 2111 = Hutang Supplier/Sewa Belum Difakturkan
					{
						$ACC_NUM	= "HUT_SPL"; // Hutang Supplier/Sewa
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
						
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D', 'Hut. Spl but Not Set', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
				
					// Cek Link Account untuk PPh
					// didapatkan dari setingan LA Pajak
					// hasil meeting tgl 27 Des 2017, tidak ada jurnal PPh
					$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKIN != ''";			
					$resTLAC	= $this->db->count_all($sqlTLAC);			
					if($resTLAC > 0)
					{
						$TAXLA_LINKIN	= '';
						$sqlTLA			= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
						$this->db->query($sqlTLA);
						$resTLA 		= $this->db->query($sqlTLA)->result();					
						foreach($resTLA as $rowTLA):
							$TAXLA_LINKIN	= $rowTLA->TAXLA_LINKIN;
							$ACC_NUM	= $TAXLA_LINKIN;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Acc_Id = '$TAXLA_LINKIN'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$TAXLA_LINKIN', '$proj_Code', 'IDR', $PPhAmount, 
														$PPhAmount, $PPhAmount, 'Default', 1, 0, 'D', 'PPh', '$Acc_Name', '$proj_CodeHO')";
										//$this->db->query($sqlGEJDD); HOLDED ON 27 DES 2018
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$PPhAmount,
															Base_Debet = Base_Debet+$PPhAmount, COA_Debet = COA_Debet+$PPhAmount
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Acc_Id = '$TAXLA_LINKIN'";
										//$this->db->query($sqlUpdCOAD); HOLDED ON 27 DES 2018
								}
								
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPhAmount,
													Base_Debet2 = Base_Debet2+$PPhAmount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN'";
								$this->db->query($sqlUpdCOAD); // OK*/
								
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKIN' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PPhAmount,
															Base_Debet2 = Base_Debet2+$PPhAmount, BaseD_$accYr = BaseD_$accYr+$PPhAmount
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKIN'";
										//$this->db->query($sqlUpdCOA); HOLDED ON 27 DES 2018
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					
				// ------------------------------- K R E D I T KAS / BANK -------------------------------
				
				$ACC_ID_K	= $ACC_ID;				// BANK ACCOUNT SELECTED
				$ACC_NUM	= $ACC_ID_K;
				$Acc_Name 	= "-";
				$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resNm		= $this->db->query($sqlNm)->result();
				foreach($resNm as $rowNm):
					$Acc_Name	= $rowNm->Account_NameId;
				endforeach;

				// START : Save Journal Detail - Kredit
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
								VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue1, $transacValue1,
									$transacValue1, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
					$this->db->query($sqlGEJDK);
				// END : Save Journal Detail - Kredit
										
				// START : Update to COA - Kredit
					/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue1, 
											Base_Kredit2 = Base_Kredit2+$transacValue1
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K'";
					$this->db->query($sqlUpdCOAD); // OK*/
							
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue1, 
												Base_Kredit2 = Base_Kredit2+$transacValue1, BaseK_$accYr = BaseK_$accYr+$transacValue1
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Kredit
				
				// Cek Link Account untuk PPh
				// didapatkan dari setinga LA Pajak
				// hasil meeting tgl 27 Des 2017, tidak ada jurnal PPh
				$sqlTLAC	= "tbl_tax_la WHERE TAXLA_NUM = '$PPhTax' AND TAXLA_LINKOUT != ''";			
				$resTLAC	= $this->db->count_all($sqlTLAC);			
				if($resTLAC > 0)
				{
					$TAXLA_LINKOUT	= '';
					$sqlTLA			= "SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$PPhTax'";
					$resTLA 		= $this->db->query($sqlTLA)->result();					
					foreach($resTLA as $rowTLA):
						$TAXLA_LINKOUT	= $rowTLA->TAXLA_LINKOUT;
						$ACC_NUM	= $TAXLA_LINKOUT;
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Acc_Id = '$TAXLA_LINKOUT'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$TAXLA_LINKOUT', '$proj_Code', 'IDR', $PPhAmount, 
													$PPhAmount, $PPhAmount, 'Default', 1, 0, 'K', 'PPh', '$Acc_Name', '$proj_CodeHO')";
									//$this->db->query($sqlGEJDD); HOLDED ON 27 DES 2018
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$PPhAmount,
														Base_Kredit = Base_Kredit+$PPhAmount, COA_Kredit = COA_Kredit+$PPhAmount
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Acc_Id = '$TAXLA_LINKOUT'";
									//$this->db->query($sqlUpdCOAD); HOLDED ON 27 DES 2018
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPhAmount,
												Base_Kredit2 = Base_Kredit2+$PPhAmount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKOUT'";
							$this->db->query($sqlUpdCOAD); // OK*/
						
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$TAXLA_LINKOUT' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPhAmount,
														Base_Kredit2 = Base_Kredit2+$PPhAmount, BaseK_$accYr = BaseK_$accYr+$PPhAmount
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$TAXLA_LINKOUT'";
									//$this->db->query($sqlUpdCOA); HOLDED ON 27 DES 2018
								}
							}
						// END : Update to COA - Debit
					endforeach;
				}
				
				// ------------------------------- K R E D I T POTONGAN PEMBAYARAN -------------------------------
				// START : 12-12-2018
				if($DiscAmount > 0)
				{
					$ACC_NUM	= "2111XX"; // Hutang Supplier/Sewa Belum Difakturkan
					$sqlCL_K	= "tglobalsetting";
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID_POT;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $DiscAmount,
													$DiscAmount, $DiscAmount, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$DiscAmount
															Base_Kredit = Base_Kredit+$DiscAmount, 
															COA_Kredit = COA_Kredit+$DiscAmount
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$DiscAmount, 
															Base_Kredit2 = Base_Kredit2+$DiscAmount, BaseK_$accYr = BaseK_$accYr+$DiscAmount
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
				}
				// END : 12-12-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-POT')		// BP-POT = Bank Payment - Potongan Pembayaran
		{
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex;
				
				// ------------------------------- D E B I T : POTONGAN PEMBAYARAN -------------------------------
					// DEBIT ONLY
					
					$ACC_NUM	= "XXXX-BP-POT"; // Hutang Supplier/Sewa Belum Difakturkan
					$sqlCL_K	= "tglobalsetting";
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)

					{
						$sqlL_K	= "SELECT ACC_ID_POT FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID_POT;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'D' AND Journal_Type = 'NTAX'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'Potongan Pembayaran', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX'";
										$this->db->query($sqlUpdCOAD);
								}
								
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
						endforeach;
					}
					
				// ------------------------------- K R E D I T -------------------------------
				
				
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PINV2_XX')		// PINV = Purchase Invoice - KHUSUS PPn Masukan (DEBIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// START : 27-12-2018 - PPn Masukan (Hard Code)
						$sqlCL_D	= "tbl_tax_ppn";	
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT TAXLA_LINKIN FROM tbl_tax_ppn";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->TAXLA_LINKIN;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
												$transacValue, 'Default', 1, 0, 'D', 'PPn Masukan', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
							endforeach;
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 27-12-2018 - PPn Masukan (Hard Code)				
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BP-DP')		// BP = Bank Payment
		{
    		$Notes 		= $parameters['Notes'];
			$SPLCATEG	= $data[1];
			
			//return false;
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex;
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					// CREDIT ONLY
					
				// ------------------------------- K R E D I T -------------------------------
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Notes', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Kredit					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'DP_220617')		// DP = Down Payment
		{
			$SPLCATEG	= $data[1];
			
			//return false;
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
				// ------------------------------- K R E D I T -------------------------------
				
				$ACC_ID_K	= $ACC_ID;				// BANK ACCOUNT SELECTED
				$ACC_NUM	= $ACC_ID_K;
				$Acc_Name 	= "-";
				$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resNm		= $this->db->query($sqlNm)->result();
				foreach($resNm as $rowNm):
					$Acc_Name	= $rowNm->Account_NameId;
				endforeach;

				// START : Save Journal Detail - Kredit
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
								VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue, $transacValue,
									$transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
					$this->db->query($sqlGEJDK);
				// END : Save Journal Detail - Kredit
										
				// START : Update to COA - Kredit
					/*$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
											Base_Kredit2 = Base_Kredit2+$transacValue
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K'";
					$this->db->query($sqlUpdCOAD); // OK*/
				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Kredit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'DP_220620')		// DP = Down Payment -- SUDAH GOOD NAMUN JURNAL AKAN TERBENTUK SAAT DP, BUKAN SAAT PAYMENT
		{
			$SPLCATEG		= $data[1];
			$ACCCODE_PPN 	= $TAXCODE1;
			$ACCCODE_PPN 	= $TAXCODE1;
			$AMOUNT_PPN 	= $TAXPRICE1;
			$ACCCODE_PPH 	= $TAXCODE2;
			$AMOUNT_PPH 	= $TAXPRICE2;
			
			//return false;
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPH;

				// ------------------------------- START : D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValuex, 
														$transacValuex, $transacValuex, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValuex,
															Base_Debet = Base_Debet+$transacValuex, COA_Debet = COA_Debet+$transacValuex
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValuex,
															Base_Debet2 = Base_Debet2+$transacValuex, BaseD_$accYr = BaseD_$accYr+$transacValuex
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}

					if($AMOUNT_PPN > 0)
					{
						$ACC_NUM 	= '';
						$s_ACC 		= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn WHERE TAXLA_NUM = '$ACCCODE_PPN' LIMIT 1";
						$r_ACC		= $this->db->query($s_ACC)->result();
						foreach($r_ACC as $rw_ACC):
							$ACC_NUM	= $rw_ACC->TAXLA_LINKOUT;
						endforeach;

						if($ACC_NUM == '')
						{
							$s_ACCPPN		= "SELECT ACC_ID_IRPPN FROM tglobalsetting LIMIT 1";
							$r_ACCPPN			= $this->db->query($s_ACCPPN)->result();
							foreach($r_ACCPPN as $rw_ACCPPN) :
								$ACC_NUM 	= $rw_ACCPPN->ACC_ID_IRPPN;
							endforeach;
						}
						
						if($ACC_NUM != '')
						{
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPN, 
													$AMOUNT_PPN, $AMOUNT_PPN, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$AMOUNT_PPN,
														Base_Debet = Base_Debet+$AMOUNT_PPN, COA_Debet = COA_Debet+$AMOUNT_PPN
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$AMOUNT_PPN,
															Base_Debet2 = Base_Debet2+$AMOUNT_PPN, BaseD_$accYr = BaseD_$accYr+$AMOUNT_PPN
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
						else
						{
							$ACC_NUM 	= "NOT_SET_ACC";
							$Acc_Name 	= "-";
							$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPN, 
												$AMOUNT_PPN, $AMOUNT_PPN, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
						}
					}

				// ------------------------------- END : D E B I T -------------------------------


				// ------------------------------- START : K R E D I T -------------------------------
				
				$ACC_ID_K	= $ACC_ID;				// BANK ACCOUNT SELECTED
				$ACC_NUM	= $ACC_ID_K;
				$Acc_Name 	= "-";
				$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resNm		= $this->db->query($sqlNm)->result();
				foreach($resNm as $rowNm):
					$Acc_Name	= $rowNm->Account_NameId;
				endforeach;

				// START : Save Journal Detail - Kredit
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
								VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue, $transacValue,
									$transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
					$this->db->query($sqlGEJDK);
				// END : Save Journal Detail - Kredit

				// START : Update to COA - Kredit
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Kredit

				if($AMOUNT_PPH > 0)
				{
					$ACC_NUM 	= '';
					$s_ACC 		= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$ACCCODE_PPH' LIMIT 1";
					$r_ACC		= $this->db->query($s_ACC)->result();
					foreach($r_ACC as $rw_ACC):
						$ACC_NUM	= $rw_ACC->TAXLA_LINKIN;
					endforeach;

					if($ACC_NUM != '')
					{
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPH, $AMOUNT_PPH,
											$AMOUNT_PPH, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit

						// START : Update to COA - Kredit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$AMOUNT_PPH, 
														Base_Kredit2 = Base_Kredit2+$AMOUNT_PPH, BaseK_$accYr = BaseK_$accYr+$AMOUNT_PPH
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Kredit
					}
					else
					{
						$ACC_NUM 	= "NOT_SET_ACC";
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPH, $AMOUNT_PPH,
											$AMOUNT_PPH, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit
					}
				}

				// ------------------------------- END : K R E D I T -------------------------------
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'DP')			// DP = Down Payment
		{
			$SPLCATEG		= $data[1];
			$ACCCODE_PPN 	= $TAXCODE1;
			$ACCCODE_PPN 	= $TAXCODE1;
			$AMOUNT_PPN 	= $TAXPRICE1;
			$ACCCODE_PPH 	= $TAXCODE2;
			$AMOUNT_PPH 	= $TAXPRICE2;
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				//$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPH;
				$transacValue	= $transacValuex + $AMOUNT_PPN;	// PPH TIDAK TERBENTUK SAAT DP, NAMUN TERBENTUK SAAT PEMBAYARAN

				// ------------------------------- START : D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValuex, 
														$transacValuex, $transacValuex, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValuex,
															Base_Debet = Base_Debet+$transacValuex, COA_Debet = COA_Debet+$transacValuex
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValuex,
															Base_Debet2 = Base_Debet2+$transacValuex, BaseD_$accYr = BaseD_$accYr+$transacValuex
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}

					if($AMOUNT_PPN > 0)
					{
						$ACC_NUM 	= '';
						$s_ACC 		= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn WHERE TAXLA_NUM = '$ACCCODE_PPN' LIMIT 1";
						$r_ACC		= $this->db->query($s_ACC)->result();
						foreach($r_ACC as $rw_ACC):
							$ACC_NUM	= $rw_ACC->TAXLA_LINKOUT;
						endforeach;

						if($ACC_NUM == '')
						{
							$s_ACCPPN		= "SELECT ACC_ID_IRPPN FROM tglobalsetting LIMIT 1";
							$r_ACCPPN			= $this->db->query($s_ACCPPN)->result();
							foreach($r_ACCPPN as $rw_ACCPPN) :
								$ACC_NUM 	= $rw_ACCPPN->ACC_ID_IRPPN;
							endforeach;
						}
						
						if($ACC_NUM != '')
						{
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
							
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPN, 
													$AMOUNT_PPN, $AMOUNT_PPN, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$AMOUNT_PPN,
														Base_Debet = Base_Debet+$AMOUNT_PPN, COA_Debet = COA_Debet+$AMOUNT_PPN
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$AMOUNT_PPN,
															Base_Debet2 = Base_Debet2+$AMOUNT_PPN, BaseD_$accYr = BaseD_$accYr+$AMOUNT_PPN
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
						else
						{
							$ACC_NUM 	= "NOT_SET_ACC";
							$Acc_Name 	= "-";
							$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPN, 
												$AMOUNT_PPN, $AMOUNT_PPN, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
						}
					}

				// ------------------------------- END : D E B I T -------------------------------


				// ------------------------------- START : K R E D I T -------------------------------

				$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";			
				$resCL_K	= $this->db->count_all($sqlCL_K);
				if($resCL_K > 0)
				{
					$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'PINV' AND LA_DK = 'K'";
					$resL_K = $this->db->query($sqlL_K)->result();					
					foreach($resL_K as $rowL_K):
						$ACC_ID_K	= $rowL_K->LA_ACCID;
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_ID_K' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_ID_K', '$proj_Code', 'IDR', $transacValue, $transacValue,
											$transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit

						// START : Update to COA - Kredit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_ID_K' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Kredit
					endforeach;
				}

				$AMOUNT_PPH 	= 0;		// PPH TIDAK TERBENTUK SAAT DP, NAMUN TERBENTUK SAAT PEMBAYARAN
				if($AMOUNT_PPH > 0)
				{
					$ACC_NUM 	= '';
					$s_ACC 		= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$ACCCODE_PPH' LIMIT 1";
					$r_ACC		= $this->db->query($s_ACC)->result();
					foreach($r_ACC as $rw_ACC):
						$ACC_NUM	= $rw_ACC->TAXLA_LINKIN;
					endforeach;

					if($ACC_NUM != '')
					{
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPH, $AMOUNT_PPH,
											$AMOUNT_PPH, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit

						// START : Update to COA - Kredit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$AMOUNT_PPH, 
														Base_Kredit2 = Base_Kredit2+$AMOUNT_PPH, BaseK_$accYr = BaseK_$accYr+$AMOUNT_PPH
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Kredit
					}
					else
					{
						$ACC_NUM 	= "NOT_SET_ACC";
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPH, $AMOUNT_PPH,
											$AMOUNT_PPH, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit
					}
				}

				// ------------------------------- END : K R E D I T -------------------------------
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BPDP')		// BPDP = Pembayaran Down Payment
		{
			$SPLCATEG		= $data[1];
			$ACCCODE_PPN 	= $TAXCODE1;
			$ACCCODE_PPN 	= $TAXCODE1;
			$AMOUNT_PPN 	= $TAXPRICE1;
			$ACCCODE_PPH 	= $TAXCODE2;
			$AMOUNT_PPH 	= $TAXPRICE2;

			$DP_NUM 		= "";
			if(isset($parameters['Faktur_No']))
				$DP_NUM 	= $parameters['Faktur_No'];

			$CB_NOTES 		= "";
			if(isset($parameters['CB_NOTES']))
				$CB_NOTES		= $parameters['CB_NOTES'];

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];

			$Other_Desc 		= "";
			if(isset($parameters['Other_Desc']))
				$Other_Desc		= $parameters['Other_Desc'];

			$DP_REFCODE 	= "";
			$DP_NOTES 		= "";
			$s_DP	= "SELECT DP_REFCODE, DP_NOTES FROM tbl_dp_header WHERE DP_NUM = '$DP_NUM'";
			$r_DP = $this->db->query($s_DP)->result();					
			foreach($r_DP as $rw_DP):
				$DP_REFCODE		= $rw_DP->DP_REFCODE;
				$DP_NOTES		= $rw_DP->DP_NOTES;
			endforeach;

			$TTKT_DATE 			= "";
			$TTKT_TAXNO 		= "";
			$TTKT_SPLINVDATE 	= "";
			$TTKT_SPLINVNO 		= "";
			$s_DP 			= "SELECT TTK_NUM FROM tbl_dp_header WHERE DP_NUM = '$DP_NUM' AND PRJCODE = '$proj_Code'";
			$r_DP 			= $this->db->query($s_DP)->result();
			foreach($r_DP as $rw_DP) :
				$TTK_NUM = $rw_DP->TTK_NUM;
				
				$sqlTAX	= "SELECT TTKT_DATE, TTKT_TAXNO, TTKT_SPLINVDATE, TTKT_SPLINVNO FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM'";
				$resTAX	= $this->db->query($sqlTAX)->result();
				foreach($resTAX as $rowTAX) :
					$TTKT_DATE 			= $rowTAX->TTKT_DATE;
					$TTKT_TAXNO 		= $rowTAX->TTKT_TAXNO;
					$TTKT_SPLINVDATE 	= $rowTAX->TTKT_SPLINVDATE;
					$TTKT_SPLINVNO 		= $rowTAX->TTKT_SPLINVNO;
				endforeach;
			endforeach;
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				//$transacValuex = $Qty_Plus * $Item_Price;
				$transacValuex	= ($Qty_Plus * $Item_Price) + $AMOUNT_PPN;
				$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPH;

				// ------------------------------- START : D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
											Journal_DK, Other_Desc, Acc_Name, proj_CodeHO,
											TAX_NO, TAX_DATE, Kwitansi_No, Kwitansi_Date, SPLCODE, SPLDESC)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValuex, 
											$transacValuex, $transacValuex, 'Default', 1, 0, 'D', '$SPLDESC. $DP_NOTES ($DP_REFCODE)', '$Acc_Name', '$proj_CodeHO',
											'$TTKT_TAXNO', '$TTKT_DATE', '$TTKT_SPLINVNO', '$TTKT_SPLINVDATE', '$SPLCODE', '$SPLDESC')";
							$this->db->query($sqlGEJDD);
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValuex,
															Base_Debet2 = Base_Debet2+$transacValuex, BaseD_$accYr = BaseD_$accYr+$transacValuex
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}

					$AMOUNT_PPN 	= 0; 			// PPN TIDAK TERBENTUK LAGI SAAT PEMBAYARAN
					if($AMOUNT_PPN > 0)
					{
						$ACC_NUM 	= '';
						$s_ACC 		= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn WHERE TAXLA_NUM = '$ACCCODE_PPN' LIMIT 1";
						$r_ACC		= $this->db->query($s_ACC)->result();
						foreach($r_ACC as $rw_ACC):
							$ACC_NUM	= $rw_ACC->TAXLA_LINKOUT;
						endforeach;

						if($ACC_NUM == '')
						{
							$s_ACCPPN		= "SELECT ACC_ID_IRPPN FROM tglobalsetting LIMIT 1";
							$r_ACCPPN			= $this->db->query($s_ACCPPN)->result();
							foreach($r_ACCPPN as $rw_ACCPPN) :
								$ACC_NUM 	= $rw_ACCPPN->ACC_ID_IRPPN;
							endforeach;
						}
						
						if($ACC_NUM != '')
						{
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount
											WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO,
												TAX_NO, TAX_DATE, Kwitansi_No, Kwitansi_Date, SPLCODE, SPLDESC)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPN, 
												$AMOUNT_PPN, $AMOUNT_PPN, 'Default', 1, 0, 'D', '$SPLDESC. $DP_NOTES ($DP_REFCODE)', '$Acc_Name', '$proj_CodeHO',
												'$TTKT_TAXNO', '$TTKT_DATE', '$TTKT_SPLINVNO', '$TTKT_SPLINVDATE', '$SPLCODE', '$SPLDESC')";
							$this->db->query($sqlGEJDD);
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$AMOUNT_PPN,
															Base_Debet2 = Base_Debet2+$AMOUNT_PPN, BaseD_$accYr = BaseD_$accYr+$AMOUNT_PPN
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
						else
						{
							$ACC_NUM 	= "NOT_SET_ACC";
							$Acc_Name 	= "-";
							$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPN, 
												$AMOUNT_PPN, $AMOUNT_PPN, 'Default', 1, 0, 'D', 'DP / UM Spl', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
						}
					}

				// ------------------------------- END : D E B I T -------------------------------


				// ------------------------------- START : K R E D I T -------------------------------

				if($AMOUNT_PPH > 0)
				{
					$ACC_NUM 	= '';
					$s_ACC 		= "SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$ACCCODE_PPH' LIMIT 1";
					$r_ACC		= $this->db->query($s_ACC)->result();
					foreach($r_ACC as $rw_ACC):
						$ACC_NUM	= $rw_ACC->TAXLA_LINKIN;
					endforeach;

					if($ACC_NUM != '')
					{
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO,
											Other_Desc, SPLCODE, SPLDESC)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPH, $AMOUNT_PPH,
											$AMOUNT_PPH, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO',
											'$Other_Desc','$SPLCODE','$SPLDESC')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit

						// START : Update to COA - Kredit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$AMOUNT_PPH, 
														Base_Kredit2 = Base_Kredit2+$AMOUNT_PPH, BaseK_$accYr = BaseK_$accYr+$AMOUNT_PPH
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_ID_K'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Kredit
					}
					else
					{
						$ACC_NUM 	= "NOT_SET_ACC";
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// START : Save Journal Detail - Kredit
							$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit,
											Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $AMOUNT_PPH, $AMOUNT_PPH,
											$AMOUNT_PPH, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDK);
						// END : Save Journal Detail - Kredit
					}
				}

				// ------------------------------- END : K R E D I T -------------------------------
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'DPP')		// DP = DP Payment for INV
		{
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				//$ACC_ID_D		= '110601300';		// UANG MUKA SUBKONTRAKTOR & PEMASOK
				$transacValue	= $transacValuex + $AMOUNT_PPN - $AMOUNT_PPh;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					// DISAMAKAN KONDISINYA DENGAN SAAT PEMBAYARAN. HANYA SUMBER PEMBAYARANYA (KREDIT) YANG BEDA.
					// YAITU BERASAL DARI DP, BUKAN DARI AKUN BANK
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'BP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
														Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', 'Pemb. DP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else // if not setting in Link Account set to 2111 = Hutang Supplier/Sewa Belum Difakturkan
					{
						$ACC_NUM	= "2101100"; // Hutang Supplier/Sewa
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D', 'NOT_SET_LA', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue, COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
							
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					
				// ------------------------------- K R E D I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Debit
					// KEBALIKAN DARI SAAT PEMBUATAN JURNAL DP. UANG MUKA DI SISI DEBIT. SEMENTARA UNTUK SAAT INI
					// UANG MUKA DI SISI KREDIT
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'"; // OK DI DEBIT SESUI SETTING
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "110601300"; // UANG MUKA SUBKONTRAKTOR & PEMASOK
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
						
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue,$transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, 
														COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";*/
							$this->db->query($sqlUpdCOAD); // OK
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'UM')			// UM = Use Material
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$JOBCODEID 	= $parameters['JOBCODEID'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$Notes 		= $parameters['Notes'];
				$JOBCODEID 	= $parameters['JOBCODEID'];

				$Manual_No	= "";
				if(isset($parameters['Manual_No']))
					$Manual_No		= $parameters['Manual_No'];

				$ITM_CATEG	= $ITM_GROUP;
				$ITM_LR 	= '';
				$sqlLITMCTG	= "SELECT ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resLITMCTG = $this->db->query($sqlLITMCTG)->result();					
				foreach($resLITMCTG as $rowLITMCTG):
					$ITM_CATEG	= $rowLITMCTG->ITM_CATEG;
					$ITM_LR		= $rowLITMCTG->ITM_LR;
				endforeach;
			
				// PEREKAMAN JEJAK KE tbl_itemhistory
					$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
											QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
											JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
										VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $ITM_VOLM, 
											0, 0, 'UM', $transacValue, '$Company_ID', '$Currency_ID', 
											'$JOBCODEID', 3, '$ITM_PRICE', '$ITM_CATEG', '$Notes')";
					$this->db->query($sqlHist);
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi debit ditetapkan ke 510110 BAHAN LAIN-LAIN jika belum disetting
				
					// START : 04-07-2018
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID, ITM_CATEG FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								$ITM_CATEG	= $rowL_D->ITM_CATEG;
								if($ACC_NUM == '')
									$ACC_NUM	= "UMD510110"; // BAHAN LAIN-LAIN

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{*/
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_VOLM, ITM_PRICE,
															Other_Desc, JOBCODEID, Acc_Name, proj_CodeHO, GEJ_STAT, Manual_No)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
															'IDR', $transacValue, $transacValue, $transacValue, 'Default',
															1, 0, 'D', '$Item_Code', '$ITM_GROUP','$ITM_CATEG', $Qty_Plus, $Item_Price,
															'$Notes', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO', 3, '$Manual_No')";
											$this->db->query($sqlGEJDD);
									/*}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}*/
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "UM510110"; // BAHAN LAIN-LAIN
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
														Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Other_Desc, JOBCODEID, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D',
														'$Item_Code', '$ITM_GROUP', '$ITM_CATEG', '$Notes', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 11051100 = Persediaan Bahan/Material sebagai kebalikan
					// saat pembuatan journal penerimaan
				
					// START : 04-07-2018
						// Cek Link Account untuk penerimaan di sisi Kredit
						$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
															JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
															isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Other_Desc, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
															$transacValue,$transacValue, 'Default', 1, 0, 'K',
															'$Item_Code', '$ITM_GROUP', '$ITM_CATEG', '$Notes', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "11051100"; // PERSEDIAAN BAHAN
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K',
														'$Item_Code', '$ITM_GROUP', '$ITM_CATEG', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'UM-SUB')		// UM-SUB = Use Material by Third Parties
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$JOBCODEID 	= $parameters['JOBCODEID'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$Notes 		= $parameters['Notes'];
				$JOBCODEID 	= $parameters['JOBCODEID'];

				$ITM_CATEG	= $ITM_GROUP;
				$ITM_LR 	= '';
				$sqlLITMCTG	= "SELECT ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resLITMCTG = $this->db->query($sqlLITMCTG)->result();					
				foreach($resLITMCTG as $rowLITMCTG):
					$ITM_CATEG	= $rowLITMCTG->ITM_CATEG;
					$ITM_LR		= $rowLITMCTG->ITM_LR;
				endforeach;
			
				// PEREKAMAN JEJAK KE tbl_itemhistory
					$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
											QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
											JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
										VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $ITM_VOLM, 
											0, 0, 'UM', $transacValue, '$Company_ID', '$Currency_ID', 
											'$JOBCODEID', 3, '$ITM_PRICE', '$ITM_CATEG', '$Notes')";
					$this->db->query($sqlHist);
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
				
					// START : 21-10-2021
						$sqlL_D	= "SELECT PRJ_ACCUM AS ACC_ID FROM tbl_project WHERE PRJCODE = '$proj_Code'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_ID;
						endforeach;

						if($ACC_NUM == '')
						{
							$sqlL_D	= "SELECT ACC_ID_UMSUB AS ACC_ID FROM tglobalsetting";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
							endforeach;
						}

						$ITM_CATEG	= "UM-SUB";
						if($ACC_NUM == '')
						{
							$ACC_NUM	= "UMSUB0001";
							$Acc_Name 	= "-";
								
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_VOLM, ITM_PRICE,
														Other_Desc, JOBCODEID, Acc_Name, proj_CodeHO, UMSUB_STAT)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
														'IDR', $transacValue, $transacValue, $transacValue, 'Default',
														1, 0, 'D', '$Item_Code', '$ITM_GROUP','$ITM_CATEG', $Qty_Plus, $Item_Price,
														'$Notes', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO', 1)";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
						}
						else
						{
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
								
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_VOLM, ITM_PRICE,
														Other_Desc, JOBCODEID, Acc_Name, proj_CodeHO, UMSUB_STAT)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
														'IDR', $transacValue, $transacValue, $transacValue, 'Default',
														1, 0, 'D', '$Item_Code', '$ITM_GROUP','$ITM_CATEG', $Qty_Plus, $Item_Price,
														'$Notes', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO', 1)";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 21-10-2021
					
				// ------------------------------- K R E D I T -------------------------------
					// START : 21-10-2021
						// DI SISI KREDIT ADALAH PENGURANGAN STOCK GUDANG
						$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
															JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
															isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Other_Desc, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
															$transacValue,$transacValue, 'Default', 1, 0, 'K',
															'$Item_Code', '$ITM_GROUP', '$ITM_CATEG', '$Notes', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 21-10-2021
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'FPA-OTH')	// FPA-OTH = Permintaan Penegeluaran
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				
				// ADD EXPENSE
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
								
					// START : 04-07-2018
						$ACC_NUM	= $ACC_ID;
						if($ACC_NUM == '')
							$ACC_NUM	= "FPAO510110"; // BAHAN LAIN-LAIN

						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
							
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
													curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue,
														COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 04-07-2018
					
					
				// ------------------------------- K R E D I T -------------------------------
				
					// START : 27-01-2019
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_K	= "tglobalsetting";
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_IR;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'K' AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, 
															COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
														$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 27-01-2019
					
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV_220608')	// PRINV = Project Invoice
		{
			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate = 1; // Default IDR ke IDR

				// 	-------- JURNA DP ---------

				//	PIUTANG 			A = [B+D-C]
				//	HUT. UANG MUKA 							[B]
				// 	PPH 				C = [0.03 * B]
				// 	PPN 									[D]

				//$VAL_HUM 		= $transacValue;							// HUTANG UANG MUKA
				// HASIL DISKUSI DGN BU LYTA BHW PPH TIDAK MASUK JURNAL
				$VAL_HUM 		= $transacValue + $VAL_PPN - $VAL_RET - $VAL_PPH - $VAL_PPH;		// HUTANG UANG MUKA
				$VAL_RET		= $parameters['ITM_RETENSI'];
				$VAL_PPN		= $parameters['TAXPRICE1'];
				$VAL_PPH		= $parameters['TAXPRICE2'];
				$VAL_PIUT 		= $transacValue + $VAL_PPN - $VAL_RET - $VAL_PPH;		// PIUTANG

				$transacValueD 	= $VAL_PIUT;
				$transacValueK 	= $VAL_HUM;

				/*echo "VAL_PIUT = $VAL_PIUT<br>VAL_HUM = $VAL_HUM<br>VAL_PPN = $VAL_PPN<br>VAL_PPH = $VAL_PPH";
				return false;*/
				/*if($TAXPRICE1 > 0)
				{
					$transacValueD 	= $transacValue + $TAXPRICE1;
					// KARENA ADA PPh
					$transacValueK 	= $transacValue;
				}*/
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit ditetapkan ke 1102010 Piutang Termyn Proyek (Owner)
				
					// START : 04-07-2018
						$ACC_NUM	= $ACC_ID;
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
							
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
												Journal_DK, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
												$transacValueD, $transacValueD, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
							}
							else
							{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValueD,
													Base_Debet = Base_Debet+$transacValueD,
													COA_Debet = COA_Debet+$transacValueD
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValueD
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
														Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
						
					
				// ------------------------------- K R E D I T -------------------------------	
					// START : 04-07-2018
						$ACC_INC 	= $parameters['ACC_ID2'];	// Income / Pendapatan
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_INC' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
							
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
										AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_INC'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);					
						if($resCGEJ == 0)
						{
							// START : Save Journal Detail - Debit
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect,
													Journal_DK, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_INC', '$proj_Code', 'IDR', $transacValueK, 
													$transacValueK, $transacValueK, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
							// END : Save Journal Detail - Debit
						}
						else
						{
							// START : UPDATE Journal Detail - Debit
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValueK,
													Base_Kredit = Base_Kredit+$transacValueK, COA_Kredit = COA_Kredit+$transacValueK
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_INC'";
								$this->db->query($sqlUpdCOAD);
							// END : UPDATE Journal Detail - Debit
						}
						
					// START : Update to COA - Debit					
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_INC' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK, 
													Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_INC'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV')	// PRINV = Project Invoice
		{
    		$own_Name 	= $parameters['own_Name'];
			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate = 1; // Default IDR ke IDR

				// 	-------- JURNA DP ---------
				// BERDASARKAN DISKUSI DENGAN IBU LYTA TGL. 8 JUNI 2022
				// 1. 	PADA SAAT DP/MC PPH TIDAK DILIBATKAN DI DALAM PENJURNALAN
				// 		SEHINGGA TERBENTUK JURNAL SEPERI BERIKUT

				//	PIUTANG 			1100 + PPH
				//	HUT. UANG MUKA 					1000
				// 	PPN KELUARAN					 100

				$VAL_HUM 		= $parameters['ITM_PRICE'];;								// DPP FAKTUR
				$VAL_RET		= $parameters['ITM_RETENSI'];
				$VAL_PPN		= $parameters['TAXPRICE1'];
				$VAL_PPH		= $parameters['TAXPRICE2'];
				$VAL_PIUT 		= $VAL_HUM + $VAL_PPN - $VAL_RET;							// PIUTANG

				$transacValueD 	= $VAL_PIUT;
				$transacValueK 	= $VAL_HUM;
				$PINV_NOTES		= $parameters['PINV_NOTES'];

				// ------------------------------- D E B I T -------------------------------				
					// START : 04-07-2018
						$ACC_NUM	= $ACC_ID;
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
							
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
												Journal_DK, Acc_Name, proj_CodeHO, Other_Desc, oth_reason)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
												$transacValueD, $transacValueD, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO', '$own_Name. $PINV_NOTES', '$PINV_NOTES')";
								$this->db->query($sqlGEJDD);
							}
							else
							{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValueD,
													Base_Debet = Base_Debet+$transacValueD,
													COA_Debet = COA_Debet+$transacValueD
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValueD
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueD,
														Base_Debet2 = Base_Debet2+$transacValueD, BaseD_$accYr = BaseD_$accYr+$transacValueD
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
						
					
				// ------------------------------- K R E D I T -------------------------------	
					// START : 04-07-2018
						$ACC_INC 	= $parameters['ACC_ID2'];	// Income / Pendapatan
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_INC' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
							
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
										AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_INC'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);					
						if($resCGEJ == 0)
						{
							// START : Save Journal Detail - Debit
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect,
													Journal_DK, Acc_Name, proj_CodeHO, Other_Desc, oth_reason)
												VALUES ('$JournalH_Code', '$ACC_INC', '$proj_Code', 'IDR', $transacValueK, 
													$transacValueK, $transacValueK, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO', '$PINV_NOTES', '$PINV_NOTES')";
								$this->db->query($sqlGEJDD);
							// END : Save Journal Detail - Debit
						}
						else
						{
							// START : UPDATE Journal Detail - Debit
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValueK,
													Base_Kredit = Base_Kredit+$transacValueK, COA_Kredit = COA_Kredit+$transacValueK
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_INC'";
								$this->db->query($sqlUpdCOAD);
							// END : UPDATE Journal Detail - Debit
						}
						
					// START : Update to COA - Debit					
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_INC' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueK, 
													Base_Kredit2 = Base_Kredit2+$transacValueK, BaseK_$accYr = BaseK_$accYr+$transacValueK
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_INC'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV-MC')		// PRINV = Project Invoice - MC
		{
			$ACC_ID 	= $parameters['ACC_ID'];
    		$own_Name 	= $parameters['own_Name'];
    		$Notes 		= $parameters['Notes'];
			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate = 1; // Default IDR ke IDR
				$transacValueK 	= 0;
				$transacValueD	= $transacValue;
				
				if($TAXPRICE1 > 0)
				{
					$transacValueD 	= $transacValue + $TAXPRICE1;
					// KARENA ADA PPh
					$transacValueK 	= $transacValue;
				}

				//echo "transacValue($transacValue) : (TAXPRICE1)$TAXPRICE1 : (transacValueD)$transacValueD : (transacValueK) $transacValueK : (ACC_ID)$ACC_ID";
				
				// ------------------------------- D E B I T   O N L Y -------------------------------	
					$ACC_NUM	= $ACC_ID;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;
						
					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
										AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);
						if($resCGEJ == 0)
						{*/
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
											Journal_DK, Acc_Name, proj_CodeHO, Other_Desc)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueD, 
											$transacValueD, $transacValueD, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO', '$own_Name. $Notes')";
							$this->db->query($sqlGEJDD);
						/*}
						else
						{
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValueD,
												Base_Debet = Base_Debet+$transacValueD,
												COA_Debet = COA_Debet+$transacValueD
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD);
						}*/
						
					// START : Update to COA - Debit
						/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
											Base_Debet2 = Base_Debet2+$transacValueD
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
						$this->db->query($sqlUpdCOAD); // OK*/
			
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);

					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ---------------- from PINV	

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV2')		// PINV = Purchase Invoice - KHUSUS PPn Masukan (KREDIT ONLY)
		{
    		$Notes 		= $parameters['Notes'];
    		$own_Name 	= $parameters['own_Name'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- K R E D I T -------------------------------				
					// START : 11-10-2018 - PPn Kelaran (Hard Code)
						$sqlCL_D	= "tbl_tax_ppn";	
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT TAXLA_LINKOUT FROM tbl_tax_ppn";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->TAXLA_LINKOUT;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
													Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
													$transacValue, 'Default', 1, 0, 'K', '$own_Name. $Notes', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
							endforeach;
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018						
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV3')		// KHUSUS PPh Keluaran (DEBIT ONLY) saat Faktur Proyek
		{
    		$Notes 		= $parameters['Notes'];
    		$own_Name 	= $parameters['own_Name'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------				
					// START : 11-10-2018
						$ACC_NUM	= $ACC_ID;
						if($ACC_NUM != '')
						{
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
												Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
												$transacValue, 'Default', 1, 0, 'D', '$own_Name. $Notes', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018						
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV4')		// KHUSUS PPN Keluaran (DEBIT ONLY) saat Faktur Proyek
		{
    		$Notes 		= $parameters['Notes'];
    		$TAXABLE 	= $parameters['PINV_TAXABLE'];
    		$own_Name 	= $parameters['own_Name'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- K R E D I T -------------------------------				
					// START : 11-10-2018
						$ACC_NUM	= $ACC_ID;
						if($ACC_NUM != '')
						{
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
												JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
												Journal_DK, Acc_Name, proj_CodeHO, Other_Desc, oth_reason)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
												$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO', '$own_Name. $Notes', '$Notes')";
							$this->db->query($sqlGEJDD);
						
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018

				if($TAXABLE == 0)			// 1. TAXABLE, 0. TANPA TAX
				{
					// KALAU TANPA TAX, MAKA PPN LANGSUNG DIBUATKAN JURNAL PEMBALIK
					// ------------------------------- D E B E T -------------------------------				
						// START : 09-06-2022
							$ACC_NUM	= $ACC_ID;
							if($ACC_NUM != '')
							{
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
													Journal_DK, Acc_Name, proj_CodeHO, Other_Desc, oth_reason)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
													$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO', '$own_Name. $Notes', '$Notes')";
								$this->db->query($sqlGEJDD);
							
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							}
						// END : 09-06-2022

				}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV5')		// KHUSUS Pengembalian DP Jika Ada(DEBIT ONLY) saat Faktur Proyek dr MC
		{
    		$Notes 		= $parameters['Notes'];
    		$own_Name 	= $parameters['own_Name'];
			$SPLCATEG	= $data[1];
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T  O N L Y -------------------------------
					$ACC_NUM	= $ACC_ID;
					if($ACC_NUM != '')
					{
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
											JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, 
											Journal_DK, Other_Desc, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, $transacValue,
											$transacValue, 'Default', 1, 0, 'D', '$own_Name. $Notes', '$Acc_Name', '$proj_CodeHO')";
						$this->db->query($sqlGEJDD);

						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}			
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'PRJINV6')		// PENDAPATAN PROYEK
		{
    		$Notes 		= $parameters['Notes'];
    		$ACC_ID 	= $parameters['ACC_ID'];
    		$own_Name 	= $parameters['own_Name'];
    		$ITM_PRICE 	= $parameters['ITM_PRICE'];
			$SPLCATEG	= $data[1];

			// ---------------- START : Pembuatan Journal Detail ----------------		
				
				// ------------------------------- K R E D I T -------------------------------				
					$ACC_NUM	= $ACC_ID;
					if($ACC_NUM != '')
					{
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
											JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, 
											Journal_DK, Acc_Name, proj_CodeHO, Other_Desc, oth_reason)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
											$ITM_PRICE, $ITM_PRICE, $ITM_PRICE, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO', '$own_Name. $Notes', '$Notes')";
						$this->db->query($sqlGEJDD);
					
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$ITM_PRICE,
														Base_Kredit2 = Base_Kredit2+$ITM_PRICE, BaseK_$accYr = BaseK_$accYr+$ITM_PRICE
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BR')		// BR = Bank Receipt
		{
			$PIUTANG_ACC 	= $data[1];
    		$totD 			= $parameters['totD'];
    		$totK 			= $parameters['totK'];
			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate 		= 1; // Default IDR ke IDR
				$transacValueA	= $transacValue + $AMOUNT_PPN - $AMOUNT_PPh;
				$transacValueB	= $transacValue + $AMOUNT_PPN + $totD - $totK;
				echo "$transacValue + $AMOUNT_PPN + $totD - $totK";
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit di tentukan oleh Akun Bank yang dipilih
				
					// START : 09-07-2018
						$ACC_NUM	= $ACC_ID;	// Bank Account
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
						
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
										AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);						
						if($resCGEJ == 0)
						{*/
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
												JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
												Journal_DK, Other_Desc, oth_reason, Ref_Number, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueA,
												$transacValueA, $transacValueA, 'Default', 1, 0, 'D', 'BR / Inc CB', '$oth_reason', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
						/*}
						else

						{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValueA,
													Base_Debet = Base_Debet+$transacValueA, COA_Debet = COA_Debet+$transacValueA
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD);
						}*/

						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueA,
												Base_Debet2 = Base_Debet2+$transacValueA
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueA,
														Base_Debet2 = Base_Debet2+$transacValueA, BaseD_$accYr = BaseD_$accYr+$transacValueA
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 09-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 1102010 Piutang Termyn Proyek (Owner)
					// sebagai journal pembalik saat Pembuatan Piutang
					
					$ACC_NUMK	= $PIUTANG_ACC; // Piutang Account
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					/*$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
									AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUMK'";
					$resCGEJ	= $this->db->count_all($sqlCGEJ);					
					if($resCGEJ == 0)
					{*/
						// START : Save Journal Detail - Debit
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, 
											Other_Desc, oth_reason, Ref_Number, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUMK', '$proj_Code', 'IDR',
											$transacValueB, $transacValueB, $transacValueB, 'Default', 1, 0, 'K',
											'', '$oth_reason', '$Ref_Number', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
						// END : Save Journal Detail - Debit
					/*}
					else
					{
						// START : UPDATE Journal Detail - Debit
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValueA,
												Base_Kredit = Base_Kredit+$transacValueA, COA_Kredit = COA_Kredit+$transacValueA
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUMK'";
							$this->db->query($sqlUpdCOAD);
						// END : UPDATE Journal Detail - Debit
					}*/
					
				// START : Update to COA - Debit
					/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueA, 
										Base_Kredit2 = Base_Kredit2-$transacValueA
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK'";
					$this->db->query($sqlUpdCOAD); // OK*/
				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueB, 
												Base_Kredit2 = Base_Kredit2+$transacValueB, BaseK_$accYr = BaseK_$accYr+$transacValueB
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUMK'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BR-OTH')		// BR = Bank Receipt
		{
    		$ACC_ID 		= $parameters['ACC_ID'];
    		$Acc_Name 		= $parameters['Acc_Name'];
    		$Base_Kredit 	= $parameters['Base_Kredit'];
    		$Ref_Number 	= $parameters['Ref_Number'];
    		$Other_Desc 	= $parameters['Other_Desc'];
    		$proj_CodeHO 	= $parameters['proj_CodeHO'];
    		$PRJPERIOD 		= $parameters['PRJPERIOD'];
    		$BR_ACCID 		= $parameters['BR_ACCID'];

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];

			$Journal_DK 		= "K";
			if(isset($parameters['Journal_DK']))
				$Journal_DK		= $parameters['Journal_DK'];

			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$transacValueA	= $transacValue + $AMOUNT_PPN - $AMOUNT_PPh;

				// ------------------------------- K R E D I T -------------------------------
					$ACC_NUMK	= $ACC_ID;

					if($Journal_DK == 'K')
					{
						$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
										Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, curr_rate, isDirect,
										ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, SPLCODE, SPLDESC) VALUES
										('$JournalH_Code', '$ACC_NUMK', '$BR_ACCID', 'BR', '$proj_Code',
										'IDR', $Base_Kredit, $Base_Kredit, $Base_Kredit, 1, 1, 
										1, '$Base_Kredit', '$Ref_Number', '$SPLDESC. $Other_Desc', 'K', 'BR', 0, 
										'$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, '$SPLCODE', '$SPLDESC')";
						$this->db->query($insSQL);
						
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$Base_Kredit, 
														Base_Kredit2 = Base_Kredit2+$Base_Kredit, BaseK_$accYr = BaseK_$accYr+$Base_Kredit
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUMK'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					else
					{
						$insSQL	= "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, Acc_Id_Cross, JournalType, proj_Code,
										Currency_id, JournalD_Debet, Base_Debet, COA_Debet, curr_rate, isDirect,
										ITM_VOLM, ITM_PRICE, Ref_Number, Other_Desc, Journal_DK, Journal_Type, isTax,
										proj_CodeHO, PRJPERIOD, Acc_Name, ISPERSL, SPLCODE, SPLDESC) VALUES
										('$JournalH_Code', '$ACC_NUMK', '$BR_ACCID', 'BR', '$proj_Code',
										'IDR', $Base_Kredit, $Base_Kredit, $Base_Kredit, 1, 1, 
										1, '$Base_Kredit', '$Ref_Number', '$SPLDESC. $Other_Desc', 'D', 'BR', 0, 
										'$proj_CodeHO', '$PRJPERIOD', '$Acc_Name', 0, '$SPLCODE', '$SPLDESC')";
						$this->db->query($insSQL);
						
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$Base_Kredit, 
														Base_Debet2 = Base_Debet2+$Base_Kredit, BaseD_$accYr = BaseK_$accYr+$Base_Kredit
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUMK'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BR-PD')		// BR = Bank Receipt dari Pengembalian PD
		{
			$PIUTANG_ACC	= $data[1];		// AKUN YANG MELAKUKAN PD

			if($PIUTANG_ACC == "")
			{
				$sqlL_K		= "SELECT ACC_ID_PERSL FROM tglobalsetting";
				$resL_K 	= $this->db->query($sqlL_K)->result();					
				foreach($resL_K as $rowL_K):
					$PIUTANG_ACC	= $rowL_K->ACC_ID_PERSL;
				endforeach;
			}

			$oth_reason 		= "";
			if(isset($parameters['oth_reason']))
				$oth_reason		= $parameters['oth_reason'];

			$PD_CODE 		= "";
			if(isset($parameters['PD_CODE']))
				$PD_CODE		= $parameters['PD_CODE'];

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];

			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$transacValueA	= $transacValue;

				// ------------------------------- K R E D I T -------------------------------
					// Journal sisi kredit sebagai lawan dari jurnal PD (vs KAS/BANK)
					
					$ACC_NUMK	= $PIUTANG_ACC; // Piutang Account
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					// START : Save Journal Detail - Debit
						$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
										JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, 
										Other_Desc, oth_reason, Ref_Number, Acc_Name,
										proj_CodeHO, SPLCODE, SPLDESC)
									VALUES ('$JournalH_Code', '$ACC_NUMK', '$proj_Code', 'IDR',
										$transacValueA, $transacValueA, $transacValueA, 'Default', 1, 0, 'K',
										'$SPLDESC. Pengembalian PD $PD_CODE. $oth_reason', '$oth_reason', '$Ref_Number', '$Acc_Name',
										'$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
						$this->db->query($sqlGEJDD);
					// END : Save Journal Detail - Debit

				// START : Update to COA - Debit
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueA, 
												Base_Kredit2 = Base_Kredit2+$transacValueA, BaseK_$accYr = BaseK_$accYr+$transacValueA
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUMK'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'BRDP')		// BR = Bank Receipt From DP
		{
			$data 			= explode("~" , $TRANS_CATEG1);
			$TRANS_CATEG	= $data[0];
			$UTANGDP_ACC	= $data[1];

			$oth_reason 		= "";
			if(isset($parameters['oth_reason']))
				$oth_reason		= $parameters['oth_reason'];

			$SPLCODE 		= "";
			if(isset($parameters['SPLCODE']))
				$SPLCODE		= $parameters['SPLCODE'];

			$SPLDESC 		= "";
			if(isset($parameters['SPLDESC']))
				$SPLDESC		= $parameters['SPLDESC'];

			$REF_DESC 		= "";
			if(isset($parameters['REF_DESC']))
				$REF_DESC		= $parameters['REF_DESC'];

			$Manual_No 		= "";
			if(isset($parameters['Manual_No']))
				$Manual_No		= $parameters['Manual_No'];

			$PINV_MANNO 		= "";
			if(isset($parameters['PINV_MANNO']))
				$PINV_MANNO		= $parameters['PINV_MANNO'];

			$PINV_KWITNO 		= "";
			if(isset($parameters['PINV_KWITNO']))
				$PINV_KWITNO		= $parameters['PINV_KWITNO'];

			$PINV_INVOICE 		= "";
			if(isset($parameters['PINV_INVOICE']))
				$PINV_INVOICE		= $parameters['PINV_INVOICE'];

			$totD 				= 0;
			if(isset($parameters['totD']))
				$totD			= $parameters['totD'];

			$totK 				= 0;
			if(isset($parameters['totK']))
				$totK			= $parameters['totK'];

			// ---------------- START : Pembuatan Journal Detail ---------------- from PINV	
				$curr_rate 		= 1; // Default IDR ke IDR
				$transacValueA	= $transacValue + $AMOUNT_PPN - $AMOUNT_PPh;
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit di tentukan oleh Akun Bank yang dipilih
				
					// START : 09-07-2018
						$ACC_NUM	= $ACC_ID;	// Bank Account
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, Base_Debet, COA_Debet,
										CostCenter, curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason, Manual_No, Faktur_Code, Kwitansi_No, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC)
									VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValueA, $transacValueA, $transacValueA,
										'Default', 1, 0, 'D', '$SPLDESC. $oth_reason. $REF_DESC', '$oth_reason', '$Manual_No', '$PINV_MANNO', '$PINV_KWITNO', '$PINV_INVOICE', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC')";
						$this->db->query($sqlGEJDD);

						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueA,
												Base_Debet2 = Base_Debet2+$transacValueA
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
				
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValueA,
														Base_Debet2 = Base_Debet2+$transacValueA, BaseD_$accYr = BaseD_$accYr+$transacValueA
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					// END : 09-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 1102010 Piutang Termyn Proyek (Owner)
					// sebagai journal pembalik saat Pembuatan Piutang
					
					$TOT_KREDIT = $transacValueA+$totD-$totK;

					$ACC_NUMK	= $UTANGDP_ACC; // Account Hutang Uang Muka / DP
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					// START : Save Journal Detail - Kredit
						$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter,
										curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason, Ref_Number, Acc_Name, proj_CodeHO, SPLCODE, SPLDESC, Manual_No)
									VALUES ('$JournalH_Code', '$ACC_NUMK', '$proj_Code', 'IDR', $TOT_KREDIT, $TOT_KREDIT, $TOT_KREDIT, 'Default',
										1, 0, 'K', '$SPLDESC. $oth_reason. $REF_DESC', '$oth_reason', '$Ref_Number', '$Acc_Name', '$proj_CodeHO', '$SPLCODE', '$SPLDESC', '$Manual_No')";
						$this->db->query($sqlGEJDD);
					// END : Save Journal Detail - Kredit
					
				// START : Update to COA - Debit
					/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValueA, 
										Base_Kredit2 = Base_Kredit2-$transacValueA
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK'";
					$this->db->query($sqlUpdCOAD); // OK*/
				
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount_$PRJCODEVW
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUMK' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$TOT_KREDIT, 
												Base_Kredit2 = Base_Kredit2+$TOT_KREDIT, BaseK_$accYr = BaseK_$accYr+$TOT_KREDIT
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUMK'";
							$this->db->query($sqlUpdCOA);
						}
					}
				// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'OPN')		// OPN = Opname
		{
    		$Notes 			= $parameters['Notes'];
			$curr_rate 		= 1; // Default IDR ke IDR
			$transacValuex 	= $Qty_Plus * $Item_Price;
			
			// UNTUK COGS DIINPUTKAN NILAI ASLI SEBELUM DITAMBAH PPN
			//$transacValue	= $transacValuex + $AMOUNT_PPN;
			$transacValue	= $transacValuex;
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$WO_CATEG 	= $parameters['WO_CATEG'];
				$JOBCODEID 	= $parameters['JOBCODEID'];
				$TAXCODE2 	= $parameters['TAXCODE2'];
				$TAXPRICE2 	= $parameters['TAXPRICE2'];
				$SPLCODE 	= $parameters['SPLCODE'];
				$SPLDESC 	= $parameters['SPLDESC'];
				$JOBDESC		= '';
				$sqlJOBDESC		= "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$proj_Code' LIMIT 1";
				$resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				foreach($resJOBDESC as $rowJOBDESC) :
					$JOBDESC	= $rowJOBDESC->JOBDESC;
				endforeach;
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $ITM_VOLM, 
									0, 0, 0, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'$JOBCODEID', 3, '$ITM_PRICE', '$ITM_CATEG', 'Penggunaan Material u/ Opname Proyek')";
				$this->db->query($sqlHist);
				
				// ------------------------------- D E B I T -------------------------------
					// START : 04-07-2018
						// DIINPUTKAN NILAI ASLI SEBELUM DITAMBAH PPN
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'"; 
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM	= "OPN510110"; // BAHAN LAIN-LAIN
									$Acc_Name 	= "-";
									$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resNm		= $this->db->query($sqlNm)->result();
									foreach($resNm as $rowNm):
										$Acc_Name	= $rowNm->Account_NameId;
									endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter,
													curr_rate, isDirect, Journal_DK, 
													PPN_Code, PPN_Amount, PPH_Code, PPH_Amount,
													ITM_CODE, ITM_GROUP, ITM_VOLM, ITM_PRICE,
													ITM_CATEG, Other_Desc, Notes, JOBCODEID, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$TAXCODE1', '$AMOUNT_PPN', '$TAXCODE2', '$AMOUNT_PPh',
													'$Item_Code', '$ITM_GROUP', '$Qty_Plus', '$Item_Price', 
													'$ITM_CATEG', '$Notes. Opname Pek. $JOBCODEID : $JOBDESC', '$WO_CATEG', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
						else
						{
							$ACC_NUM	= "5203_OPN"; // UPAH M/E
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
														JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
														Journal_DK, ITM_GROUP, ITM_VOLM, ITM_PRICE,
														ITM_CATEG, Other_Desc, JOBCODEID, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D', '$ITM_GROUP', '$Qty_Plus', '$Item_Price', 
														'$WO_CATEG', '$Notes', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// START : PEMBENTUKAN JURNAL PPH JIKA ADA PPH NON-FINAL (SESUAI HASIL DISKUSI DENGAN BU LYTA TGL 21 OKT. 2022)
						// HASIL DISKUSI DENGAN BU LYTA TGL 21 OKT. 2022 BAHWA PPH NON-FINAL DIBENTUK SAAT PEMBENTUKAN JURNAL HUTANG
						// ARTINYA APABILA HUTANG TERBENTUK SAAT OPNAME, MAKA PPH NON-FINAL JUGA TERBENTUK SAAT OPNAME
						
						$TAXPRICE2 	= 0;
						if($TAXPRICE2 > 0)
						{
							$isPPhFin 		= 0;
							$sqlISHO 		= "SELECT isHO, syncPRJ, Account_Number, Account_NameId AS Acc_Name, isPPhFinal FROM tbl_chartaccount
												WHERE PRJCODE = '$proj_Code' AND Account_Number IN (SELECT TAXLA_LINKOUT FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE2') LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isPPhFin	= $rowISHO->isPPhFinal;
								$syncPRJ	= $rowISHO->syncPRJ;
								$ACC_IDPPH	= $rowISHO->Account_Number;
								$Acc_Name	= $rowISHO->Acc_Name;
								if($isPPhFin == 0)
								{
									$valKredit= $transacValue - $TAXPRICE2;
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, JournalType, Currency_id,
													JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO,
													SPLCODE, SPLDESC, Other_Desc)
												VALUES ('$JournalH_Code', '$ACC_IDPPH', '$proj_Code', 'OPN', 'IDR',
													$TAXPRICE2, $TAXPRICE2, $TAXPRICE2, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO',
													'$SPLCODE', '$SPLDESC', '$Notes. PPH Opname Pek. $JOBCODEID : $JOBDESC')";
									$this->db->query($sqlGEJDD);
										
									// START : Update to COA - Debit
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_IDPPH' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$TAXPRICE2, 
																	Base_Kredit2 = Base_Kredit2+$TAXPRICE2, BaseK_$accYr = BaseK_$accYr+$TAXPRICE2
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_IDPPH'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								}
							endforeach;
						}
					// END

			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'OPN2')		// OPN = Opname - Hutang lain-lain
		{
			$curr_rate 		= 1; // Default IDR ke IDR
			$Item_Disc 		= $parameters['ITM_DISC'];
			$transacValuex 	= $Qty_Plus * $Item_Price;
			$transacValue	= $transacValuex + $AMOUNT_PPN - $Item_Disc;

			$SPLCODE 		= '';
			if(isset($parameters['SPLCODE']))
				$SPLCODE	= $parameters['SPLCODE'];

			$SPLDESC 		= '';
			if(isset($parameters['SPLDESC']))
				$SPLDESC	= $parameters['SPLDESC'];

			$Other_Desc 	= '';
			if(isset($parameters['Other_Desc']))
				$Other_Desc	= $parameters['Other_Desc'];

			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				
				// ------------------------------- D E B I T -------------------------------
					// TIDAK DIBENTUK, SUDAH DI CATEGORI 'OPN'
						
					
				// ------------------------------- K R E D I T -------------------------------
					// SISI KREDIT TIDAK ADA.
					// DALAM PENYEIMBANGAN NERACA, MAKA HARUS DIMASUKAN KE HUTANG LAIN-LAIN TERLEBIH DAHULU.
					// DI JURNAL BALIK SAAT PEMBUATAN INVOICE ATAS OPNAME
					
					// START : 12-12-2018
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_K	= "tglobalsetting";
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_IR;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, JournalType, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO,
														SPLCODE, SPLDESC, Other_Desc)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'OPN', 'IDR',
														$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO',
														'$SPLCODE', '$SPLDESC', '$Other_Desc')";
										$this->db->query($sqlGEJDD);
									}
									else
									{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 12-12-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'V-OPN2')	// OPN = Opname - Hutang lain-lain
		{
			$curr_rate 		= 1; // Default IDR ke IDR
			$Item_Disc 		= $parameters['ITM_DISC'];
			$transacValuex 	= $Qty_Plus * $Item_Price;
			$transacValue	= $transacValuex + $AMOUNT_PPN - $Item_Disc;
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				
				// ------------------------------- D E B I T -------------------------------
					// TIDAK DIBENTUK, SUDAH DI CATEGORI 'OPN'
						
					
				// ------------------------------- K R E D I T -------------------------------
					// SISI KREDIT TIDAK ADA.
					// DALAM PENYEIMBANGAN NERACA, MAKA HARUS DIMASUKAN KE HUTANG LAIN-LAIN TERLEBIH DAHULU.
					// DI JURNAL BALIK SAAT PEMBUATAN INVOICE ATAS OPNAME
					
					// START : 12-12-2018
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_K	= "tglobalsetting";
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_IR;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, 
															COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
														$transacValue, $transacValue, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue, 
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue, 
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 12-12-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'OPN3')		// DP = Down Payment
		{
			$SPLCATEG	= $data[1];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex;
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Kredit
					$sqlCL_D	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
					$this->db->count_all($sqlCL_D);				
					$resCL_D	= $this->db->count_all($sqlCL_D);			
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT LA_ACCID FROM tbl_link_account
									WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'DP' AND LA_DK = 'D'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->LA_ACCID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id,
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue,$transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
														JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, 
														COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET BaseKredit = BaseKredit+$transacValue,
													BaseKredit2 = BaseKredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
					
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'OPN4')		// RET = RETENSI
		{
			$SPLCATEG	= $data[1];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex;
				
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
					
					$transacValue1 	= $transacValue;
					//$transacValue	= $transacValue1 + $AMOUNT_PPN;
					$transacValue	= $transacValue1;
					$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
					$sqlCL_K	= "tglobalsetting";
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT ACC_ID_RET FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();			
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID_RET;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
													AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, 
														COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
													$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'OPN5')		// RET = POTONGAN LAINNYA
		{
			$SPLCATEG	= $data[1];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex;
				
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
				
					$transacValue1 	= $transacValue;
					$transacValue	= $transacValue1 + $AMOUNT_PPN;
					$ACC_NUM		= $ACC_ID;
					$Acc_Name 		= "-";
					$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm			= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;
												
					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
											AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
						if($resCGEJ == 0)
						{
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Kredit, Base_Kredit, 
												COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
											$transacValue, $transacValue, 'Default', 1, 0, 'K', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
						}
						else
						{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
													JournalD_Kredit = JournalD_Kredit+$transacValue,
													Base_Kredit = Base_Kredit+$transacValue, 
													COA_Kredit = COA_Kredit+$transacValue
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD);
						}
						
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'OPN-RET')	// OPN-RET = Opname Retensi - Hutang lain-lain
		{
			$curr_rate 		= 1; // Default IDR ke IDR
			$transacValuex 	= $Qty_Plus * $Item_Price;
			$transacValue	= $transacValuex;
			$Notes 			= $parameters['Notes'];
			$SPLCATEG		= $data[1];
			$CREATED 		= date('Y-m-d H:i:s');

			$SPLCODE 		= '';
			if(isset($parameters['SPLCODE']))
				$SPLCODE	= $parameters['SPLCODE'];

			$SPLDESC 		= '';
			if(isset($parameters['SPLDESC']))
				$SPLDESC	= $parameters['SPLDESC'];

			$Other_Desc 	= '';
			if(isset($parameters['Other_Desc']))
				$Other_Desc	= $parameters['Other_Desc'];

			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				
				// ------------------------------- D E B I T -------------------------------
					// TIDAK DIBENTUK, SUDAH DI CATEGORI 'OPN'
						
					
				// ------------------------------- K R E D I T -------------------------------
					
					// START : 14-11-2021
						$sqlCL_K	= "tbl_link_account WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'OPN-RET' AND LA_DK = 'K'";
						$this->db->count_all($sqlCL_K);
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT LA_ACCID FROM tbl_link_account
										WHERE LA_ITM_CODE = '$SPLCATEG' AND LA_CATEG = 'OPN-RET' AND LA_DK = 'K'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->LA_ACCID;
								if($ACC_NUM == '')
								{
									$sqlL_K	= "SELECT ACC_ID_RET FROM tglobalsetting";
									$resL_K = $this->db->query($sqlL_K)->result();					
									foreach($resL_K as $rowL_K):
										$ACC_NUM	= $rowL_K->ACC_ID_RET;
									endforeach;
								}
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount
												WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
								if($ACC_NUM == '')
									$Acc_Name 	= "NOT SETT IN SUPPLIER CAT. AND GLOBAL SETTING";

								// INSERT INTO JOURNA DETAIL
									$sqlGEJDD 	= "INSERT INTO tbl_journaldetail (JournalH_Code, JournalType, Acc_Id, proj_Code, PRJPERIOD,
														Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit,
														CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name,
														proj_CodeHO, Other_Desc, LastUpdate, ISRET, SPLCODE, SPLDESC)
													VALUES ('$JournalH_Code', 'OPN-RET', '$ACC_NUM', '$proj_Code', '$proj_Code',
														'IDR', $transacValue, $transacValue, $transacValue,
														'Default', 1, 0, 'K', '$Acc_Name',
														'$proj_CodeHO', '$Other_Desc', '$CREATED', 1, '$SPLCODE', '$SPLDESC')";
									$this->db->query($sqlGEJDD);

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 14-11-2021
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				/*$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);*/
		}
		elseif($TRANS_CATEG == 'V-OPN5')	// RET = POTONGAN LAINNYA
		{
			$SPLCATEG	= $data[1];
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				$curr_rate 		= 1; // Default IDR ke IDR
				$Unit_Price 	= $Item_Price;				
				$transacValuex 	= $Qty_Plus * $Item_Price;
				$transacValue	= $transacValuex;
				
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 2111 = Hutang Supplier/Sewa Belum Difakturkan
				
					$transacValue1 	= $transacValue;
					$transacValue	= $transacValue1 + $AMOUNT_PPN;
					$ACC_NUM		= $ACC_ID;
					$Acc_Name 		= "-";
					$sqlNm 			= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm			= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;
												
					// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
						$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
											AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
						$resCGEJ	= $this->db->count_all($sqlCGEJ);
						
						if($resCGEJ == 0)
						{
								$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
												Currency_id, JournalD_Debet, Base_Debet, 
												COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO)
											VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue,
											$transacValue, $transacValue, 'Default', 1, 0, 'D', '$Acc_Name', '$proj_CodeHO')";
								$this->db->query($sqlGEJDD);
						}
						else
						{
								$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
													JournalD_Debet = JournalD_Debet+$transacValue,
													Base_Debet = Base_Debet+$transacValue, 
													COA_Debet = COA_Debet+$transacValue
												WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD);
						}
						
					// START : Update to COA - Debit
						$isHO			= 0;
						$syncPRJ		= '';
						$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resISHO		= $this->db->query($sqlISHO)->result();
						foreach($resISHO as $rowISHO):
							$isHO		= $rowISHO->isHO;
							$syncPRJ	= $rowISHO->syncPRJ;
						endforeach;
						$dataPecah 	= explode("~",$syncPRJ);
						$jmD 		= count($dataPecah);
					
						if($jmD > 0)
						{
							$SYNC_PRJ	= '';
							for($i=0; $i < $jmD; $i++)
							{
								$SYNC_PRJ	= $dataPecah[$i];
								$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue, 
													Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
												WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOA);
							}
						}
					// END : Update to COA - Debit
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		if($TRANS_CATEG == 'PURC-RET')			// RETURN = Return
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				
				// MIN STOCK ON PROFIT LOSS
				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
					if($ITM_TYPE == 1)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 9)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 10)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					
					$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
					$resL_K = $this->db->query($sqlL_K)->result();					
					foreach($resL_K as $rowL_K):
						$ACC_NUM	= $rowL_K->ACC_ID_IR;
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;
							
						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
												AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK,
													ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, PattNum, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR',
													$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$ITM_CODE','$ITM_CATEG','$ITM_GROUP',$Qty_Plus, $Item_Price,'', '', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
														JournalD_Kredit = JournalD_Debet+$transacValue,
														Base_Kredit = Base_Debet+$transacValue, 
														COA_Kredit = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue, 
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					endforeach;
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 11051100 = Persediaan Bahan/Material sebagai kebalikan
					// saat pembuatan journal penerimaan
				
					// START : 04-07-2018
						// Cek Link Account untuk penerimaan di sisi Kredit
						/*$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID;*/
								$ACC_NUM	= $ACC_ID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, 
														CostCenter, curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K', '$ITM_CODE','$ITM_GROUP','$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
									}
									else
									{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							/*endforeach;
						}*/
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'ASEXP')		// ASEXP = Asset Exp.
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
				
					// Cek Link Account pada tbl_item
					$sqlCL_D	= "tbl_asset_list WHERE AS_CODE = '$Item_Code'";
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						// Akun Beban pada Aset save to DEBIT
						$sqlL_D	= "SELECT AS_LADEPREC AS ACC_IDEXP FROM tbl_asset_list WHERE AS_CODE = '$Item_Code'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_IDEXP;
							if($ACC_NUM == '')
								$ACC_NUM	= "ASEXPD510110";

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D',
														'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "ASEXP510110";
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
													curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue,
														COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					
				// ------------------------------- K R E D I T -------------------------------
				
					// Cek Link Account untuk penerimaan di sisi Kredit
					$sqlCL_K	= "tbl_asset_list WHERE AS_CODE = '$Item_Code'";
					$this->db->count_all($sqlCL_K);				
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT AS_LINKACC AS ACC_ID FROM tbl_asset_list WHERE AS_CODE = '$Item_Code'";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
														isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K',
														'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "ASEXP-11051100"; // PERSEDIAAN BAHAN
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue,$transacValue, 'Default', 1, 0, 'K',
													'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, 
														COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'AU')		// AU = Asset Usage
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					// Cek Link Account pada tbl_item
					$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
									WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_ID;
							if($ACC_NUM == '')
								$ACC_NUM	= "AU510110"; // BAHAN LAIN-LAIN

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
								
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue, $transacValue, 'Default', 1, 0, 'D',
														'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "AU510110"; // BAHAN LAIN-LAIN
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
													Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue,
														COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
							
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					
				// ------------------------------- K R E D I T -------------------------------
					// Cek Link Account untuk penerimaan di sisi Kredit
					$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
					$this->db->count_all($sqlCL_K);				
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
														isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K',
														'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "AU-11051100"; // PERSEDIAAN BAHAN
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue,$transacValue, 'Default', 1, 0, 'K',
													'$Item_Code', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, 
														COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'UM-ASEXP')	// UM = Use Material - Asset Expense
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					// Cek Link Account pada tbl_item
					$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
					$resCL_D	= $this->db->count_all($sqlCL_D);
					if($resCL_D > 0)
					{
						$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
									WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_ID;
							if($ACC_NUM == '')
								$ACC_NUM	= "UM-ASEXPD510110"; // BAHAN LAIN-LAIN

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
								
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
													curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$Item_Code', '$ITM_GROUP', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue,
														COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "UM-ASEXP510110"; // BAHAN LAIN-LAIN
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
													JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect,
													Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue, $transacValue, 'Default', 1, 0, 'D',
													'$Item_Code', '$ITM_GROUP', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
														Base_Debet = Base_Debet+$transacValue,
														COA_Debet = COA_Debet+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
					
				// ------------------------------- K R E D I T -------------------------------
					// Cek Link Account untuk penerimaan di sisi Kredit
					$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
					$this->db->count_all($sqlCL_K);				
					$resCL_K	= $this->db->count_all($sqlCL_K);
					if($resCL_K > 0)
					{
						$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID;
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
														isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
														$transacValue,$transacValue, 'Default', 1, 0, 'K',
														'$Item_Code', '$ITM_GROUP', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					}
					else
					{
						$ACC_NUM	= "UM-ASEXP-11051100"; // PERSEDIAAN BAHAN
						$Acc_Name 	= "-";
						$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
						$resNm		= $this->db->query($sqlNm)->result();
						foreach($resNm as $rowNm):
							$Acc_Name	= $rowNm->Account_NameId;
						endforeach;

						// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
							$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
											AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
							$resCGEJ	= $this->db->count_all($sqlCGEJ);
							
							if($resCGEJ == 0)
							{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
													JournalD_Kredit, Base_Kredit, 
													COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $transacValue, 
													$transacValue,$transacValue, 'Default', 1, 0, 'K',
													'$Item_Code', '$ITM_GROUP', '$ITM_GROUP', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
							}
							else
							{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
														Base_Kredit = Base_Kredit+$transacValue, 
														COA_Kredit = COA_Kredit+$transacValue
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
							}
						// START : Update to COA - Debit
							/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
												Base_Kredit2 = Base_Kredit2+$transacValue
											WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOAD); // OK*/
			
							$isHO			= 0;
							$syncPRJ		= '';
							$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resISHO		= $this->db->query($sqlISHO)->result();
							foreach($resISHO as $rowISHO):
								$isHO		= $rowISHO->isHO;
								$syncPRJ	= $rowISHO->syncPRJ;
							endforeach;
							$dataPecah 	= explode("~",$syncPRJ);
							$jmD 		= count($dataPecah);
						
							if($jmD > 0)
							{
								$SYNC_PRJ	= '';
								for($i=0; $i < $jmD; $i++)
								{
									$SYNC_PRJ	= $dataPecah[$i];
									$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
													WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOA);
								}
							}
						// END : Update to COA - Debit
					}
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'STF')						// STF = Section Transfer
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$Notes 		= $parameters['Notes'];
				$ITM_NAME 	= $parameters['ITM_NAME'];
				$ITM_UNIT 	= $parameters['ITM_UNIT'];
				$JO_CODE 	= $parameters['JO_CODE'];
				$JOBCODEID 	= $parameters['JOBCODEID'];
				$ITM_DESC 	= "Pengunaan $ITM_NAME u/ produksi $JO_CODE";

				$ITM_CATEG	= $ITM_GROUP;
				$ITM_LR 	= '';
				$sqlLITMCTG	= "SELECT ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resLITMCTG = $this->db->query($sqlLITMCTG)->result();					
				foreach($resLITMCTG as $rowLITMCTG):
					$ITM_CATEG	= $rowLITMCTG->ITM_CATEG;
					$ITM_LR		= $rowLITMCTG->ITM_LR;
				endforeach;
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				// ------------------------------- D E B I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi debit ditetapkan ke 510110 BAHAN LAIN-LAIN jika belum disetting
				
					// START : 04-07-2018
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM	= "STF510110"; // BAHAN LAIN-LAIN

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, 
															ITM_VOLM, ITM_PRICE, Other_Desc, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
															'IDR', $transacValue, $transacValue, $transacValue, 'Default',
															1, 0, 'D', '$Item_Code', '$ITM_CATEG', '$ITM_GROUP', 
															$Qty_Plus, $Item_Price, '$ITM_DESC', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 04-07-2018
					
				// ------------------------------- K R E D I T -------------------------------
					// Hasil diskusi 22/03/18 : Journal sisi kredit ditetapkan ke 11051100 = Persediaan Bahan/Material sebagai kebalikan
					// saat pembuatan journal penerimaan
				
					// START : 04-07-2018
						// Cek Link Account untuk penerimaan di sisi Kredit
						$sqlCL_K	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_K);				
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM= "STF11051100"; // PERSEDIAAN BAHAN

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
															JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
															isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, Other_Desc, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
															$transacValue, $transacValue, $transacValue, 'Default', 1,
															0, 'K', '$Item_Code', '$ITM_CATEG', '$ITM_GROUP', $Qty_Plus, $Item_Price, '$ITM_DESC', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
														Base_Kredit2 = Base_Kredit2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
					
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 22-03-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'STF_OUTPUT')				// STF_OUTPUT = Section Transfer - OUTPUT FG
		{
			$JO_CODE 		= $parameters['JO_CODE'];
			$ITM_QTY 		= $parameters['ITM_QTY'];
			$ITM_PRICE 		= $parameters['ITM_PRICE'];
			$transacValue	= $ITM_QTY * $ITM_PRICE;
			$IR_AMOUNT 		= $transacValue;
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtyProd_Plus, QtyProd_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $ITM_QTY, 0, 
									0, 0, $ITM_QTY, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$ITM_PRICE', '$ITM_CATEG', 'Hasil produksi $JO_CODE')";
				$this->db->query($sqlHist);
			
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_CODE 	= $parameters['ITM_CODE'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$ITM_UNIT 	= $parameters['ITM_UNIT'];
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$ITM_DISC 	= $parameters['ITM_DISC'];
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;
				
				// ------------------------------- D E B I T : PERSEDIAAN -------------------------------
					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						// Cek Link Account untuk penerimaan di sisi Debit
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_D);
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_PROD FROM tglobalsetting";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID_PROD;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
														AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
															JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK,
															ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, JOBCODEID, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
															$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D',
															'$Item_Code','$ITM_CATEG','$ITM_GROUP',$ITM_VOLM, $ITM_PRICE,'$ITM_UNIT', '', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'STF_IN')					// 	STF_IN = Penggunaan WIP
		{
			$ITM_QTY 		= $parameters['ITM_QTY'];
			$ITM_PRICE 		= $parameters['ITM_PRICE'];
			//$transacValue	= $ITM_QTY * $ITM_PRICE;
			$transacValue	= $ITM_PRICE;					// karena di tbl_jo_stfdetail sudah hasil perkalian
			$IR_AMOUNT 		= $transacValue;
			$JO_CODE 		= $parameters['JO_CODE'];
			$Notes 			= $parameters['Notes'];
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtyProd_Plus, QtyProd_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $ITM_QTY, 
									0, 0, 0, $ITM_QTY, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$ITM_PRICE', '$ITM_CATEG', 'Bahan Produksi WIP $JO_CODE. $Notes')";
				$this->db->query($sqlHist);

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;
					
				// ------------------- ------------ K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 1103010 PERSEDIAAN / INVENTORY DARI GLOBAL SETTING
						$sqlL_K	= "SELECT ACC_ID_WIPP AS ACC_ID FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();				
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID;
							if($ACC_NUM == '')
								$ACC_NUM= "WIPP1103010"; // PERSEDIAAN WIP

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
														isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
														$transacValue, $transacValue, $transacValue, 'Default', 1,
														0, 'K', '$Item_Code', '$ITM_CATEG', '$ITM_GROUP', $Qty_Plus, $Item_Price, 'Bahan Produksi WIP $JO_CODE. $Notes', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'STF_INWIP')					// 	Menyeimbangkan keluaran WIP sebelumnya
		{
			$ITM_QTY 		= $parameters['ITM_QTY'];
			$ITM_PRICE 		= $parameters['ITM_PRICE'];
			//$transacValue	= $ITM_QTY * $ITM_PRICE;
			$transacValue	= $ITM_PRICE;					// karena di tbl_jo_stfdetail sudah hasil perkalian
			$IR_AMOUNT 		= $transacValue;
			$JO_CODE 		= $parameters['JO_CODE'];
			$Notes 			= $parameters['Notes'];
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtyProd_Plus, QtyProd_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $ITM_QTY, 
									0, 0, 0, $ITM_QTY, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$ITM_PRICE', '$ITM_CATEG', 'Bahan Produksi WIP $JO_CODE. $Notes')";
				$this->db->query($sqlHist);

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;
					
				// ------------------- ------------ K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 1103010 PERSEDIAAN / INVENTORY DARI GLOBAL SETTING
						$sqlL_K	= "SELECT ACC_ID_WIPP AS ACC_ID FROM tglobalsetting";
						$resL_K = $this->db->query($sqlL_K)->result();				
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID;
							if($ACC_NUM == '')
								$ACC_NUM= "WIPP1103010"; // PERSEDIAAN WIP

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
														isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
														$transacValue, $transacValue, $transacValue, 'Default', 1,
														0, 'K', '$Item_Code', '$ITM_CATEG', '$ITM_GROUP', $Qty_Plus, $Item_Price, 'Bahan Produksi WIP $JO_CODE. $Notes', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'STF_OUT')					// 	STF_OUTPUT = Section Transfer - OUTPUT
		{
			$ITM_QTY 		= $parameters['ITM_QTY'];
			$ITM_PRICE 		= $parameters['ITM_PRICE'];
			$ITM_UNIT 		= $parameters['ITM_UNIT'];
			//$transacValue	= $ITM_QTY * $ITM_PRICE;
			$transacValue	= $ITM_PRICE;					// karena di tbl_jo_stfdetail sudah hasil perkalian
			$IR_AMOUNT 		= $transacValue;
			$JO_CODE 		= $parameters['JO_CODE'];
			$Notes 			= $parameters['Notes'];
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtyProd_Plus, QtyProd_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $ITM_QTY, 0, 
									0, 0, $ITM_QTY, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$ITM_PRICE', '$ITM_CATEG', 'Hasil Produksi WIP $JO_CODE. $Notes')";
				$this->db->query($sqlHist);

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;
				
				// ------------------------------- D E B I T : PERSEDIAAN -------------------------------
					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						// Cek Link Account untuk penerimaan di sisi Debit
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_D);
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								if($ACC_NUM != '')
								{
									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
										$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
															AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
															AND ITM_CODE = '$Item_Code'";
										$resCGEJ	= $this->db->count_all($sqlCGEJ);
										
										if($resCGEJ == 0)
										{
												$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
																JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK,
																ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, JOBCODEID,
																Other_Desc, Acc_Name, proj_CodeHO)
															VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
																$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D',
																'$Item_Code','$ITM_CATEG','$ITM_GROUP',$ITM_QTY, $ITM_PRICE,'$ITM_UNIT', '',
																'Hasil Produksi WIP $JO_CODE. $Notes', '$Acc_Name', '$proj_CodeHO')";
												$this->db->query($sqlGEJDD);
										}
										else
										{
												$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																	JournalD_Debet = JournalD_Debet+$transacValue,
																	Base_Debet = Base_Debet+$transacValue,
																	COA_Debet = COA_Debet+$transacValue
																WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																	AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
												$this->db->query($sqlUpdCOAD);
										}
									// START : Update to COA - Debit
										/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD); // OK*/
										
										$isHO			= 0;
										$syncPRJ		= '';
										$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
															WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
										$resISHO		= $this->db->query($sqlISHO)->result();
										foreach($resISHO as $rowISHO):
											$isHO		= $rowISHO->isHO;
											$syncPRJ	= $rowISHO->syncPRJ;
										endforeach;
										$dataPecah 	= explode("~",$syncPRJ);
										$jmD 		= count($dataPecah);
									
										if($jmD > 0)
										{
											$SYNC_PRJ	= '';
											for($i=0; $i < $jmD; $i++)
											{
												$SYNC_PRJ	= $dataPecah[$i];
												$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																	Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
																WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
												$this->db->query($sqlUpdCOA);
											}
										}
									// END : Update to COA - Debit
								}
							endforeach;
						}
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'STF_OUTWIP')				// 	STF_OUTPUT = Section Transfer - OUTPUT
		{
			$ITM_QTY 		= $parameters['ITM_QTY'];
			$ITM_PRICE 		= $parameters['ITM_PRICE'];
			$ITM_UNIT 		= $parameters['ITM_UNIT'];
			//$transacValue	= $ITM_QTY * $ITM_PRICE;
			$transacValue	= $ITM_PRICE;					// karena di tbl_jo_stfdetail sudah hasil perkalian
			$IR_AMOUNT 		= $transacValue;
			$JO_CODE 		= $parameters['JO_CODE'];
			$Notes 			= $parameters['Notes'];
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtyProd_Plus, QtyProd_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $ITM_QTY, 0, 
									0, 0, $ITM_QTY, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$ITM_PRICE', '$ITM_CATEG', 'Hasil Produksi WIP $JO_CODE. $Notes')";
				$this->db->query($sqlHist);

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR

				$ITM_CATEG	= $ITM_GROUP;
				$sqlL_ICAT	= "SELECT ITM_CATEG FROM tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
				$resL_ICAT 	= $this->db->query($sqlL_ICAT)->result();				
				foreach($resL_ICAT as $rowL_ICAT):
					$ITM_CATEG	= $rowL_ICAT->ITM_CATEG;
				endforeach;
				
				// ------------------------------- D E B I T : PERSEDIAAN -------------------------------
					// START : 04-07-2018
						// PADA PERSEDIAN SEBELUM DITAMBAH PPN, sementara utuk HUTANG LAIN-LAIN ditambah PPn
						// Cek Link Account untuk penerimaan di sisi Debit
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$this->db->count_all($sqlCL_D);
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_WIPP AS ACC_ID FROM tglobalsetting";
							$resL_D = $this->db->query($sqlL_D)->result();				
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								if($ACC_NUM == '')
									$ACC_NUM= "WIPP1103010"; // PERSEDIAAN WIP

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
														AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
															JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK,
															ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, JOBCODEID,
															Other_Desc, Acc_Name, proj_CodeHO)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
															$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D',
															'$Item_Code','$ITM_CATEG','$ITM_GROUP',$ITM_QTY, $ITM_PRICE,'$ITM_UNIT', '',
															'Hasil Produksi WIP $JO_CODE. $Notes', '$Acc_Name', '$proj_CodeHO')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
											$this->db->query($sqlUpdCOAD);
									}

								// START : Update to COA - Debit
									/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
														Base_Debet2 = Base_Debet2+$transacValue
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD); // OK*/
									
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : 04-07-2018
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'SN')		// SN = Shipment Notes
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$Notes 		= $parameters['Notes'];
			
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$JOBCODEID 	= $parameters['JOBCODEID'];
				$ITM_CODE 	= $parameters['ITM_CODE'];
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				$ITM_UNIT 	= $parameters['ITM_UNIT'];
				$ITM_VOLM 	= $parameters['ITM_QTY'];
				$ITM_PRICE 	= $parameters['ITM_PRICE'];
				$ITM_DISC 	= $parameters['ITM_DISC'];
			
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, 
									Currency_ID, JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $ITM_VOLM, 
									0, 0, 0, $ITM_VOLM, '$JSource', $transacValue, '$Company_ID',
									'$Currency_ID', '$JOBCODEID', 3, '$ITM_PRICE', '$ITM_CATEG', 'Pengiriman Barang')";
				$this->db->query($sqlHist);
				
				if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
					if($ITM_TYPE == 1)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 9)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 10)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$transacValue
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit ditetapkan ke 110205020 PIUTANG LAIN-LAIN DARI GLOBAL SETTING
						//$sqlL_D	= "SELECT ACC_ID_SN AS ACC_ID FROM tglobalsetting";
						// PER 22 MARET 2021 DIGANTI KE PENGATURAN TIAP MATERIAL (ACC SAL)
						$sqlL_D	= "SELECT ACC_ID_SAL FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->ACC_ID_SAL;
							if($ACC_NUM == '')
								$ACC_NUM	= "SN110205020"; // PIUTANG LAIN-LAIN
							
							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;
								
							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, 
														ITM_VOLM, ITM_PRICE, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
														'IDR', $transacValue, $transacValue, $transacValue, 'Default',
														1, 0, 'D', '$Item_Code', '$ITM_CATEG', '$ITM_GROUP', 
														$Qty_Plus, $Item_Price, '$Notes', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
															Base_Debet = Base_Debet+$transacValue,
															COA_Debet = COA_Debet+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}

							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
													Base_Debet2 = Base_Debet2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
															Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					
				// ------------------- ------------ K R E D I T -------------------------------
					// Journal sisi kredit ditetapkan ke 1103010 PERSEDIAAN / INVENTORY DARI GLOBAL SETTING
						// $sqlL_K	= "SELECT ACC_ID_PROD AS ACC_ID FROM tglobalsetting";
						// 21-03-22 : SEBETULNYA INI SEHARUSNYA KEBALIKAN AKUN PADA SAAT PENERIMAAN BARANG/MATERIAL ACC_ID PENERIMAAN
						// APABILA MATERIAL ETRSEBUT ADALAH FG, MAKA PAKUN PENERIMAAN SEBAIKNYA = AKUN PRODUKSI
						
						$sqlACC	= "SELECT ACC_ID FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
						$resL_K = $this->db->query($sqlACC)->result();				
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->ACC_ID;
							if($ACC_NUM == '')
								$ACC_NUM= "SN1103010"; // PERSEDIAAN BAHAN

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CARI HARGA RATA-RATA
								//$sqlITM 	= "SELECT *"

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
														JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, 
														isDirect, Journal_DK, ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, Other_Desc, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
														$transacValue, $transacValue, $transacValue, 'Default', 1,
														0, 'K', '$Item_Code', '$ITM_CATEG', '$ITM_GROUP', $Qty_Plus, $Item_Price, '$Notes', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Kredit = JournalD_Kredit+$transacValue,
															Base_Kredit = Base_Kredit+$transacValue, 
															COA_Kredit = COA_Kredit+$transacValue
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K' 
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								/*$sqlUpdCOAD	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
													Base_Kredit2 = Base_Kredit2+$transacValue
												WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM'";
								$this->db->query($sqlUpdCOAD); // OK*/
				
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
															Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'SINV')		// SINV = Sales Invoice
		{
			$CUSTCODE			= $data[1];
			$CUSTCAT			= $data[2];
			
			$transacValue 		= $transacValue;					// NILAI ORIGINAL
			$transacValuePot	= $parameters['ITM_PRICE1'];		// NILAI POT
			$transacValuePPn	= $parameters['ITM_PRICE2'];		// NILAI PPN
			$transacValuePPh	= $parameters['ITM_PRICE3'];		// NILAI PPH
			$SINV_NOTES			= $parameters['SINV_NOTES'];

			/*START SAMPLE

				FORMASI JURNAL LENGKAP
				- A. Piutang Usaha 		      105,000				(C + D + E - B)
				- B. Diskon 					5,000
				- C. Penjualan 						      100,000
				- D. Pajak / PPn Kelauaran					9,500
				- E. Ongkos Kirim							1,000

			END SAMPLE*/

			$transacValueGT		= $transacValue + $transacValuePPn - $transacValuePot - $transacValuePPh;	// NET

			/*$transacValueGT	= $transacValue + $transacValuePPn - $transacValuePot - $transacValuePPh;
			$D_01				= $transacValue + $transacValuePPn;
			$D_02				= $transacValuePPn;
			$K_01				= $transacValue + $transacValuePPn - $transacValuePot - $transacValuePPh;
			$K_02				= $transacValuePot;
			$K_03				= $transacValuePPh;*/

			// PIUTANG
				//$PIUT_VAL		= $SALS_VAL + $PPN_VAL - $DISC_VAL;
				//$PIUT_VAL		= $SALS_VAL + $PPN_VAL;
				$PIUT_VAL		= $transacValueGT;

			// DISC
				$DISC_VAL 		= $transacValuePot;

			// PENJUALAN
				//$SALS_VAL		= $transacValue - $transacValuePPn;
				$SALS_VAL		= $transacValue;

			// PPN Keluaran
				$PPN_VAL		= $transacValuePPn;

			echo "
			// PIUTANG
				$PIUT_VAL		= $transacValueGT;<br>						D

			// DISC
				$DISC_VAL 		= $transacValuePot;<br>						D

			// PENJUALAN
				$SALS_VAL		= $transacValue - $transacValuePPn;<br>		K	

			// PPN Keluaran
				$PPN_VAL		= $transacValuePPn;<br>						K
				";

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T 01 : 1102010 PIUTANG USAHA JASA/DAGANG (D) -------------------------------
					// Journal sisi debit ditetapkan ke 1102010 PIUTANG USAHA JASA/DAGANG
					// sebagai kebalikan saat pembuatan journal pengiriman FG

					// START : PIUTANG
						$sqlL_D	= "SELECT CC_LA_CINV FROM tbl_custcat WHERE CUSTC_CODE = '$CUSTCAT'";
						$resL_D = $this->db->query($sqlL_D)->result();					
						foreach($resL_D as $rowL_D):
							$ACC_NUM	= $rowL_D->CC_LA_CINV;
							if($ACC_NUM == '')
								$ACC_NUM	= "SINV1102010";

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								if($resCGEJ == 0)
								{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
														'IDR', $PIUT_VAL, $PIUT_VAL, $PIUT_VAL, 'Default',
														1, 0, 'D', '$SINV_NOTES', '$CUSTCAT', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
														JournalD_Debet = JournalD_Debet+$PIUT_VAL,
														Base_Debet = Base_Debet+$PIUT_VAL,
														COA_Debet = COA_Debet+$PIUT_VAL
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
								}
								
							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$PIUT_VAL,
															Base_Debet2 = Base_Debet2+$PIUT_VAL, BaseD_$accYr = BaseD_$accYr+$PIUT_VAL
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					// END : PIUTANG

					// POTONGAN PENJUALAN
						if($DISC_VAL > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_SPOT from tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_SPOT;
								if($ACC_NUM == '')
									$ACC_NUM	= "SPOT110205020";

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
														curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
														'IDR', $DISC_VAL, $DISC_VAL, $DISC_VAL, 'Default',
														1, 0, 'D', '$SINV_NOTES', '$CUSTCAT', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
									}
									else
									{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Debet = JournalD_Debet+$DISC_VAL,
															Base_Debet = Base_Debet+$DISC_VAL,
															COA_Debet = COA_Debet+$DISC_VAL
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$DISC_VAL,
																Base_Debet2 = Base_Debet2+$DISC_VAL, BaseD_$accYr = BaseD_$accYr+$DISC_VAL
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}

					// PPH
						/*if($K_03 > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_SPPH from tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_SPPH;
								if($ACC_NUM == '')
									$ACC_NUM	= "SPOT110205020";

									// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $K_02,
															$K_02, $K_02, 'Default', 1, 0,'K','$CUSTCODE','$CUSTCAT')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$K_02,
																Base_Kredit = Base_Kredit+$K_02,
																COA_Kredit = COA_Kredit+$K_02
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$K_02,
																Base_Kredit2 = Base_Kredit2+$K_02
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}*/

				// ------------------------------- K R E D I T 01 PIUTANG LAIN-LAIN -------------------------------
					// START : PENJUALAN
						$sqlL_K	= "SELECT CC_LA_CINVK FROM tbl_custcat WHERE CUSTC_CODE = '$CUSTCAT'";
						$resL_K = $this->db->query($sqlL_K)->result();					
						foreach($resL_K as $rowL_K):
							$ACC_NUM	= $rowL_K->CC_LA_CINVK;
							if($ACC_NUM == '')
								$ACC_NUM	= "SALS_KREDIT";

							$Acc_Name 	= "-";
							$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
							$resNm		= $this->db->query($sqlNm)->result();
							foreach($resNm as $rowNm):
								$Acc_Name	= $rowNm->Account_NameId;
							endforeach;

							// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
								$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
								$resCGEJ	= $this->db->count_all($sqlCGEJ);
								
								if($resCGEJ == 0)
								{
									$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
													Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
													curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason, Acc_Name, proj_CodeHO)
												VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $SALS_VAL,
													$SALS_VAL, $SALS_VAL, 'Default', 1, 0,'K','$SINV_NOTES','$CUSTCAT', '$Acc_Name', '$proj_CodeHO')";
									$this->db->query($sqlGEJDD);
								}
								else
								{
									$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
														JournalD_Kredit = JournalD_Kredit+$SALS_VAL,
														Base_Kredit = Base_Kredit+$SALS_VAL,
														COA_Kredit = COA_Kredit+$SALS_VAL
													WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$this->db->query($sqlUpdCOAD);
								}

							// START : Update to COA - Debit
								$isHO			= 0;
								$syncPRJ		= '';
								$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
													WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resISHO		= $this->db->query($sqlISHO)->result();
								foreach($resISHO as $rowISHO):
									$isHO		= $rowISHO->isHO;
									$syncPRJ	= $rowISHO->syncPRJ;
								endforeach;
								$dataPecah 	= explode("~",$syncPRJ);
								$jmD 		= count($dataPecah);
							
								if($jmD > 0)
								{
									$SYNC_PRJ	= '';
									for($i=0; $i < $jmD; $i++)
									{
										$SYNC_PRJ	= $dataPecah[$i];
										$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$SALS_VAL,
															Base_Kredit2 = Base_Kredit2+$SALS_VAL, BaseK_$accYr = BaseK_$accYr+$SALS_VAL
														WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
										$this->db->query($sqlUpdCOA);
									}
								}
							// END : Update to COA - Debit
						endforeach;
					// END : PENJUALAN

					// START : PPN KELUARAN
						if($PPN_VAL > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_SPPN from tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_SPPN;
								if($ACC_NUM == '')
									$ACC_NUM	= "PPN_OUT";

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
														AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
										$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
														Currency_id, JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
														curr_rate, isDirect, Journal_DK, Other_Desc, oth_reason, Acc_Name, proj_CodeHO)
													VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', $PPN_VAL,
														$PPN_VAL, $PPN_VAL, 'Default', 1, 0,'K','$SINV_NOTES','$CUSTCAT', '$Acc_Name', '$proj_CodeHO')";
										$this->db->query($sqlGEJDD);
									}
									else
									{
										$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
															JournalD_Kredit = JournalD_Kredit+$PPN_VAL,
															Base_Kredit = Base_Kredit+$PPN_VAL,
															COA_Kredit = COA_Kredit+$PPN_VAL
														WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
															AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
										$this->db->query($sqlUpdCOAD);
									}

								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$PPN_VAL,
																Base_Kredit2 = Base_Kredit2+$PPN_VAL, BaseK_$accYr = BaseK_$accYr+$PPN_VAL
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : PPN KELUARAN
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
		elseif($TRANS_CATEG == 'SALRET')		// Retur Penjualan
		{
    		$ACC_ID 	= $parameters['ACC_ID'];
    		$Notes 		= $parameters['Notes'];
    		$Other_Desc = $parameters['Other_Desc'];
    		$ITM_QTY 	= $parameters['ITM_QTY'];
    		$ITM_PRICE 	= $parameters['ITM_PRICE'];
    		$ITM_UNIT 	= $parameters['ITM_UNIT'];

			$ITM_CODE 	= $parameters['ITM_CODE'];
			$ITM_GROUP 	= $parameters['ITM_GROUP'];
			$ITM_TYPE 	= $parameters['ITM_TYPE'];
			$ITM_UNIT 	= $parameters['ITM_UNIT'];
			$ITM_VOLM 	= $parameters['ITM_QTY'];
			$ITM_PRICE 	= $parameters['ITM_PRICE'];
			$ITM_DISC 	= $parameters['ITM_DISC'];
		
			// PEREKAMAN JEJAK KE tbl_itemhistory
				$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
									QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', $ITM_VOLM, 0, 
									0, 0, $ITM_VOLM, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
									'', 3, '$ITM_PRICE', '$ITM_CATEG', 'Retur Pengiriman Barang')";
				$this->db->query($sqlHist);

			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- D E B I T -------------------------------
					$ACC_NUM	= $ACC_ID;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
										AND Journal_DK = 'D' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
										AND ITM_CODE = '$Item_Code'";
					$resCGEJ	= $this->db->count_all($sqlCGEJ);
					
					if($resCGEJ == 0)
					{
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect, Journal_DK,
											ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, Other_Desc, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
											$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'D',
											'$Item_Code','$ITM_CATEG','$ITM_GROUP',$ITM_QTY, $ITM_PRICE,'$ITM_UNIT', '$Other_Desc', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
					}
					else
					{
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
												JournalD_Debet = JournalD_Debet+$transacValue,
												Base_Debet = Base_Debet+$transacValue,
												COA_Debet = COA_Debet+$transacValue
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
							$this->db->query($sqlUpdCOAD);
					}
					
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOA);
						}
					}
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'SALRET-K')		// Retur Penjualan
		{
    		$ACC_ID 		= $parameters['ACC_ID'];
    		$Notes 			= $parameters['Notes'];
    		$Other_Desc 	= $parameters['Other_Desc'];
    		$ITM_QTY 		= $parameters['ITM_QTY'];
    		$ITM_PRICE 		= $parameters['ITM_PRICE'];
    		$ITM_UNIT 		= $parameters['ITM_UNIT'];
    		$transacValue 	= $ITM_QTY * $ITM_PRICE;
			// ---------------- START : Pembuatan Journal Detail ----------------		
				$curr_rate = 1; // Default IDR ke IDR
				
				// ------------------------------- K R E D I T -------------------------------
					$ACC_NUM	= $ACC_ID;
					$Acc_Name 	= "-";
					$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resNm		= $this->db->query($sqlNm)->result();
					foreach($resNm as $rowNm):
						$Acc_Name	= $rowNm->Account_NameId;
					endforeach;

					$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
										AND Journal_DK = 'K' AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'
										AND ITM_CODE = '$Item_Code'";
					$resCGEJ	= $this->db->count_all($sqlCGEJ);
					
					if($resCGEJ == 0)
					{
							$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
											JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect, Journal_DK,
											ITM_CODE, ITM_CATEG, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_UNIT, Other_Desc, Acc_Name, proj_CodeHO)
										VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
											$transacValue, $transacValue, $transacValue, 'Default', 1, 0, 'K',
											'$Item_Code','$ITM_CATEG','$ITM_GROUP',$ITM_QTY, $ITM_PRICE,'$ITM_UNIT', '$Other_Desc', '$Acc_Name', '$proj_CodeHO')";
							$this->db->query($sqlGEJDD);
					}
					else
					{
							$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
												JournalD_Kredit = JournalD_Kredit+$transacValue,
												Base_Kredit = Base_Kredit+$transacValue,
												COA_Kredit = COA_Kredit+$transacValue
											WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
												AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
							$this->db->query($sqlUpdCOAD);
					}
					
					$isHO			= 0;
					$syncPRJ		= '';
					$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
										WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
					$resISHO		= $this->db->query($sqlISHO)->result();
					foreach($resISHO as $rowISHO):
						$isHO		= $rowISHO->isHO;
						$syncPRJ	= $rowISHO->syncPRJ;
					endforeach;
					$dataPecah 	= explode("~",$syncPRJ);
					$jmD 		= count($dataPecah);
				
					if($jmD > 0)
					{
						$SYNC_PRJ	= '';
						for($i=0; $i < $jmD; $i++)
						{
							$SYNC_PRJ	= $dataPecah[$i];
							$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue,
												Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
											WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
							$this->db->query($sqlUpdCOA);
						}
					}
			// ---------------- END : Pembuatan Journal Detail ----------------
		}
		elseif($TRANS_CATEG == 'TTK-D')			// TTK-D = TTK Direct
		{
			$curr_rate = 1; // Default IDR ke IDR
			// START : UPDATE L/R
				$PRJCODE	= $proj_Code;
				$PERIODM	= date('m', strtotime($JournalH_Date));
				$PERIODY	= date('Y', strtotime($JournalH_Date));
				$ITM_GROUP 	= $parameters['ITM_GROUP'];
				$ITM_TYPE 	= $parameters['ITM_TYPE'];
				
				// ADD EXPENSE
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$transacValue 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			// END : UPDATE L/R
			
			// ---------------- START : Pembuatan Journal Detail ----------------
				
				// ------------------------------- D E B I T -------------------------------
					// Journal sisi debit ditetapkan ke Akun Penggunaan Masing2 Material
				
					// START : DEBIT
						// Cek Link Account pada tbl_item
						$sqlCL_D	= "tbl_item WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
						$resCL_D	= $this->db->count_all($sqlCL_D);
						if($resCL_D > 0)
						{
							$sqlL_D	= "SELECT ACC_ID_UM AS ACC_ID, ITM_CATEG FROM tbl_item 
										WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code' LIMIT 1";
							$resL_D = $this->db->query($sqlL_D)->result();					
							foreach($resL_D as $rowL_D):
								$ACC_NUM	= $rowL_D->ACC_ID;
								$ITM_CATEG	= $rowL_D->ITM_CATEG;
								if($ACC_NUM == '')
									$ACC_NUM	= "TTK-D.510110";

								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;
									
								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D' 
													AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM' AND ITM_CODE = '$Item_Code'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, 
															Currency_id, JournalD_Debet, Base_Debet, COA_Debet, CostCenter, 
															curr_rate, isDirect, Journal_DK, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_VOLM, ITM_PRICE,
															Other_Desc, JOBCODEID, Acc_Name, proj_CodeHO, oth_reason)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 
															'IDR', $transacValue, $transacValue, $transacValue, 'Default',
															1, 0, 'D', '$Item_Code', '$ITM_GROUP','$ITM_CATEG', $Qty_Plus, $Item_Price,
															'$Notes', '$JOBCODEID', '$Acc_Name', '$proj_CodeHO', '$oth_reason')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET JournalD_Debet = JournalD_Debet+$transacValue,
																Base_Debet = Base_Debet+$transacValue,
																COA_Debet = COA_Debet+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'D'
																AND Journal_Type = 'NTAX' AND Acc_Id = '$ACC_NUM'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
																Base_Debet2 = Base_Debet2+$transacValue, BaseD_$accYr = BaseD_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : KREDIT
					
					
				// ------------------------------- K R E D I T -------------------------------
				
					// START : DEBIT
						$ACC_NUM	= "2111"; // Hutang Supplier/Sewa Belum Difakturkan
						$sqlCL_K	= "tglobalsetting";
						$resCL_K	= $this->db->count_all($sqlCL_K);
						if($resCL_K > 0)
						{
							$sqlL_K	= "SELECT ACC_ID_IR FROM tglobalsetting";
							$resL_K = $this->db->query($sqlL_K)->result();					
							foreach($resL_K as $rowL_K):
								$ACC_NUM	= $rowL_K->ACC_ID_IR;
								$Acc_Name 	= "-";
								$sqlNm 		= "SELECT Account_NameId FROM tbl_chartaccount WHERE PRJCODE = '$proj_Code' AND Account_Number = '$ACC_NUM' LIMIT 1";
								$resNm		= $this->db->query($sqlNm)->result();
								foreach($resNm as $rowNm):
									$Acc_Name	= $rowNm->Account_NameId;
								endforeach;

								// CEK APAKAH SUDAH ADA JOURNAL SEBELUMNYA DENGAN KODE = JournalH_Code
									$sqlCGEJ	= "tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code' 
														AND Journal_DK = 'K' AND Journal_Type = 'NTAX'";
									$resCGEJ	= $this->db->count_all($sqlCGEJ);
									
									if($resCGEJ == 0)
									{
											$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id,
															JournalD_Kredit, Base_Kredit, COA_Kredit, CostCenter, 
															curr_rate, isDirect, Journal_DK, Acc_Name, proj_CodeHO, oth_reason)
														VALUES ('$JournalH_Code', '$ACC_NUM', '$proj_Code', 'IDR', 
															$transacValue, $transacValue, $transacValue, 'Default',
															1, 0, 'K', '$Acc_Name', '$proj_CodeHO', '$oth_reason')";
											$this->db->query($sqlGEJDD);
									}
									else
									{
											$sqlUpdCOAD	= "UPDATE tbl_journaldetail SET 
																JournalD_Kredit = JournalD_Kredit+$transacValue,
																Base_Kredit = Base_Kredit+$transacValue, 
																COA_Kredit = COA_Kredit+$transacValue
															WHERE JournalH_Code = '$JournalH_Code' AND Journal_DK = 'K'
																AND Journal_Type = 'NTAX'";
											$this->db->query($sqlUpdCOAD);
									}
									
								// START : Update to COA - Debit
									$isHO			= 0;
									$syncPRJ		= '';
									$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
														WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
									$resISHO		= $this->db->query($sqlISHO)->result();
									foreach($resISHO as $rowISHO):
										$isHO		= $rowISHO->isHO;
										$syncPRJ	= $rowISHO->syncPRJ;
									endforeach;
									$dataPecah 	= explode("~",$syncPRJ);
									$jmD 		= count($dataPecah);
								
									if($jmD > 0)
									{
										$SYNC_PRJ	= '';
										for($i=0; $i < $jmD; $i++)
										{
											$SYNC_PRJ	= $dataPecah[$i];
											$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue, 
																Base_Kredit2 = Base_Kredit2+$transacValue, BaseK_$accYr = BaseK_$accYr+$transacValue
															WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
											$this->db->query($sqlUpdCOA);
										}
									}
								// END : Update to COA - Debit
							endforeach;
						}
					// END : KREDIT
					
			// ---------------- END : Pembuatan Journal Detail ----------------

			// SYNC STAT AND DATE
				$sqlSYNCHD 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
									A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
								WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$JournalH_Code'";
				$this->db->query($sqlSYNCHD);
		}
	}
	
	function createITMHistMin($JournalH_Code, $parameters)
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$Company_ID			= $parameters['Company_ID'];
		$Currency_ID		= $parameters['Currency_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$JSource			= $parameters['JSource'];
		$TRANS_CATEG1		= $parameters['TRANS_CATEG'];
    	$Transaction_Date	= $parameters['JournalH_Date'];
    	$Item_Code 			= $parameters['ITM_CODE'];
    	$ACC_ID 			= $parameters['ACC_ID'];
    	$Qty_Min 			= $parameters['ITM_QTY'];
    	$Item_Price 		= $parameters['ITM_PRICE'];
    	$Item_Disc 			= $parameters['ITM_DISC'];
    	$TAXCODE1 			= $parameters['TAXCODE1'];
    	$TAXPRICE1 			= $parameters['TAXPRICE1'];
					
		$Unit_Price 		= $Item_Price;		
		$transacValue 		= ($Qty_Min * $Item_Price) - $Item_Disc;
		
		$data 				= explode("~" , $TRANS_CATEG1);
		$TRANS_CATEG		= $data[0];
			
		// PEREKAMAN JEJAK KE tbl_itemhistory
			$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
								QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
								JOBCODEID, GEJ_STAT, ItemPrice, MEMO)
							VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $Qty_Min, 
								0, 0, 0, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
								'', 9, '$Item_Price', 'Pembatalan Penerimaan')";
			$this->db->query($sqlHist);
		
		if($TRANS_CATEG == 'PURC-RET')
		{
			$sqlUPITM 	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $Qty_Min, RET_VOLM = RET_VOLM + $Qty_Min,
								RET_AMOUNT = RET_AMOUNT + $transacValue
							WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
			//$this->db->query($sqlUPITM);		// SUDAH DI $this->m_purchase_ret->updateITM_Min($parameters1);
		}
	}
	
	function createITMHistMinSTF($JournalH_Code, $parameters)
	{
		$JournalH_Code 		= $parameters['JournalH_Code'];
		$JournalType 		= $parameters['JournalType'];
		$JournalH_Date 		= $parameters['JournalH_Date'];
		$accYr				= date('Y', strtotime($JournalH_Date));
		$Company_ID			= $parameters['Company_ID'];
		$Currency_ID		= $parameters['Currency_ID'];
		$Source				= $parameters['Source'];
		$Emp_ID 			= $parameters['Emp_ID'];
		$LastUpdate 		= $parameters['LastUpdate'];
		$KursAm_tobase		= $parameters['KursAmount_tobase'];
		$Wh_id				= $parameters['WHCODE'];
		$REFNumb 			= $parameters['Reference_Number'];
		$RefType 			= $parameters['RefType'];
		$proj_Code 			= $parameters['PRJCODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$JSource			= $parameters['JSource'];
		$TRANS_CATEG1		= $parameters['TRANS_CATEG'];
    	$Transaction_Date	= $parameters['JournalH_Date'];
    	$Item_Code 			= $parameters['ITM_CODE'];
    	$ACC_ID 			= $parameters['ACC_ID'];
    	$Qty_Min 			= $parameters['ITM_QTY'];
    	$Item_Price 		= $parameters['ITM_PRICE'];
    	$Item_Disc 			= $parameters['ITM_DISC'];
    	$TAXCODE1 			= $parameters['TAXCODE1'];
    	$TAXPRICE1 			= $parameters['TAXPRICE1'];
    	$JO_CODE 			= $parameters['JO_CODE'];

    	if(isset($parameters['ITM_NAME']))
		{
			$ITM_NAME		= $parameters['ITM_NAME'];
		}
					
		$Unit_Price 		= $Item_Price;		
		$transacValue 		= ($Qty_Min * $Item_Price) - $Item_Disc;
		
		$data 				= explode("~" , $TRANS_CATEG1);
		$TRANS_CATEG		= $data[0];
			
		// PEREKAMAN JEJAK KE tbl_itemhistory
			$sqlHist 	= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
								QtyRR_Plus, QtyRR_Min, QtySN_Plus, QtySN_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
								JOBCODEID, GEJ_STAT, ItemPrice, MEMO)
							VALUES ('$JournalH_Code', '$proj_Code', '$Transaction_Date', '$Item_Code', 0, $Qty_Min, 
								0, 0, 0, 0, '$JSource', $transacValue, '$Company_ID', '$Currency_ID', 
								'', 3, '$Item_Price', 'Penggunaan $ITM_NAME u/ produksi $JO_CODE')";
			$this->db->query($sqlHist);
		
		if($TRANS_CATEG == 'PURC-RET')
		{
			$sqlUPITM 	= "UPDATE tbl_item SET ITM_OUT = ITM_OUT + $Qty_Min, RET_VOLM = RET_VOLM + $Qty_Min,
								RET_AMOUNT = RET_AMOUNT + $transacValue
							WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$Item_Code'";
			//$this->db->query($sqlUPITM);		// SUDAH DI $this->m_purchase_ret->updateITM_Min($parameters1);
		}
	}
	
	function updateGEJ($GEJ_NUM, $updGEJ) // U
	{
		// SYNC STAT AND DATE
			$sqlDATE 	= "UPDATE tbl_journaldetail A, tbl_journalheader B SET A.JournalH_Date = B.JournalH_Date,
								A.GEJ_STAT = B.GEJ_STAT, A.LastUpdate = B.LastUpdate, A.JournalType = B.JournalType
							WHERE A.JournalH_Code  = B.JournalH_Code AND A.JournalH_Code = '$GEJ_NUM'";
			$this->db->query($sqlDATE);

		$this->db->where('JournalH_Code', $GEJ_NUM);
		return $this->db->update('tbl_journaldetail', $updGEJ);
	}

	function updateLR($parameters)
	{
		$curr_rate = 1; // Default IDR ke IDR
		// START : UPDATE L/R
			$PRJCODE	= $parameters['PRJCODE'];
			$JOURN_DATE	= $parameters['JOURN_DATE'];
			$PERIODM	= date('m', strtotime($JOURN_DATE));
			$PERIODY	= date('Y', strtotime($JOURN_DATE));
			$accYr		= date('Y', strtotime($JOURN_DATE));
			$ITM_GROUP 	= $parameters['ITM_GROUP'];
			$ITM_TYPE 	= $parameters['ITM_TYPE'];
			$JOURN_VAL 	= $parameters['JOURN_VAL'];
			
			// ADD EXPENSE
			if($ITM_GROUP == 'M')
			{
				// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
				if($ITM_TYPE == 1)
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 9)
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 10)
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			}
			elseif($ITM_GROUP == 'SUB')
			{
				$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$JOURN_VAL
							WHERE PRJCODE = '$PRJCODE'
								AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
				$this->db->query($updLR);
			}
			else
			{
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			}
		// END : UPDATE L/R
	}

	function updateVLR($parameters)
	{
		$curr_rate = 1; // Default IDR ke IDR
		// START : UPDATE L/R
			$PRJCODE	= $parameters['PRJCODE'];
			$JOURN_DATE	= $parameters['JOURN_DATE'];
			$PERIODM	= date('m', strtotime($JOURN_DATE));
			$PERIODY	= date('Y', strtotime($JOURN_DATE));
			$accYr		= date('Y', strtotime($JOURN_DATE));
			$ITM_GROUP 	= $parameters['ITM_GROUP'];
			$ITM_CODE	= '';
			if(isset($parameters['ITM_CODE']))
			{
				$ITM_CODE	= $parameters['ITM_CODE'];
			}
			$ITM_TYPE 	= $parameters['ITM_TYPE'];
			$JOURN_VAL 	= $parameters['JOURN_VAL'];

			$ITM_CATEG	= $ITM_GROUP;
			$ITM_LR 	= '';
			$sqlLITMCTG	= "SELECT ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resLITMCTG = $this->db->query($sqlLITMCTG)->result();					
			foreach($resLITMCTG as $rowLITMCTG):
				$ITM_CATEG	= $rowLITMCTG->ITM_CATEG;
				$ITM_LR		= $rowLITMCTG->ITM_LR;
			endforeach;

			$FIELDVAL 	= $JOURN_VAL;
			// L/R MANUFACTUR
				if($ITM_LR != '')
				{
					$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				
			// L/R CONTRACTOR // ADDING COST OR EXPENS
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
	        
	        // MIN STOCK ON PROFIT LOSS
		        if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6.ISFASTM, 7.ISWAGE, 8.ISRM, 9.ISWIP, 10.ISFG, 11.ISCOST
					if($ITM_TYPE == 1 || $ITM_TYPE == 8)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 9)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 10)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
		// END : UPDATE L/R
	}

	function updateLR_VUM($parameters)
	{
		$curr_rate = 1; // Default IDR ke IDR
		// START : UPDATE L/R
			$PRJCODE	= $parameters['PRJCODE'];
			$JOURN_DATE	= $parameters['JOURN_DATE'];
			$PERIODM	= date('m', strtotime($JOURN_DATE));
			$PERIODY	= date('Y', strtotime($JOURN_DATE));
			$accYr		= date('Y', strtotime($JOURN_DATE));
			$ITM_GROUP 	= $parameters['ITM_GROUP'];
			$ITM_CODE	= '';
			if(isset($parameters['ITM_CODE']))
			{
				$ITM_CODE	= $parameters['ITM_CODE'];
			}
			$ITM_TYPE 	= $parameters['ITM_TYPE'];
			$JOURN_VAL 	= $parameters['JOURN_VAL'];
			
			$ITM_CATEG	= $ITM_GROUP;
			$ITM_LR 	= '';
			$sqlLITMCTG	= "SELECT ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resLITMCTG = $this->db->query($sqlLITMCTG)->result();					
			foreach($resLITMCTG as $rowLITMCTG):
				$ITM_CATEG	= $rowLITMCTG->ITM_CATEG;
				$ITM_LR		= $rowLITMCTG->ITM_LR;
			endforeach;

			$FIELDVAL 	= $JOURN_VAL;
			// L/R MANUFACTUR
				if($ITM_LR != '')
				{
					$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				
			// L/R CONTRACTOR // ADDING COST OR EXPENS
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
	        
	        // MIN STOCK ON PROFIT LOSS
		        if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6.ISFASTM, 7.ISWAGE, 8.ISRM, 9.ISWIP, 10.ISFG, 11.ISCOST
					if($ITM_TYPE == 1 || $ITM_TYPE == 8)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 9)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 10)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}






			// ADD EXPENSE
			if($ITM_GROUP == 'M')
			{
				// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
				if($ITM_TYPE == 1)
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 9)
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_TYPE == 10)
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			}
			elseif($ITM_GROUP == 'SUB')
			{
				$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$JOURN_VAL
							WHERE PRJCODE = '$PRJCODE'
								AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
				$this->db->query($updLR);
			}
			else
			{
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);

					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
			}
		// END : UPDATE L/R
	}

	function updVOID($JRN_CODE)
	{
		$JournalH_Code	= $JRN_CODE;
		$updDate		= date('Y-m-d H:i:s');
		// UPDATE STATUS
			$sqlUPDJRNH	= "UPDATE tbl_journalheader SET LastUpdate = '$updDate', GEJ_STAT = 9, STATCOL = 'danger', STATDESC = 'Void' WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($sqlUPDJRNH);	

			$sqlUPDJRND	= "UPDATE tbl_journaldetail SET LastUpdate = '$updDate', GEJ_STAT = 9 WHERE JournalH_Code = '$JournalH_Code'";
			$this->db->query($sqlUPDJRND);	

		$sqlJRND		= "SELECT Acc_Id, proj_Code, Base_Debet, Base_Kredit, JournalH_Date FROM tbl_journaldetail WHERE JournalH_Code = '$JournalH_Code'";
		$resJRND 		= $this->db->query($sqlJRND)->result();					
		foreach($resJRND as $rowJRND):
			$ACC_NUM		= $rowJRND->Acc_Id;
			$PRJCODE		= $rowJRND->proj_Code;
			$Base_Debet		= $rowJRND->Base_Debet;
			$Base_Kredit	= $rowJRND->Base_Kredit;
			$JournalH_Date	= $rowJRND->JournalH_Date;
			$accYr			= date('Y', strtotime($JournalH_Date));

			// START : Update to COA - Debit
				$isHO			= 0;
				$syncPRJ		= '';
				$sqlISHO 		= "SELECT isHO, syncPRJ FROM tbl_chartaccount
									WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$ACC_NUM' LIMIT 1";
				$resISHO		= $this->db->query($sqlISHO)->result();
				foreach($resISHO as $rowISHO):
					$isHO		= $rowISHO->isHO;
					$syncPRJ	= $rowISHO->syncPRJ;
				endforeach;
				$dataPecah 	= explode("~",$syncPRJ);
				$jmD 		= count($dataPecah);
			
				if($jmD > 0)
				{
					$SYNC_PRJ	= '';
					for($i=0; $i < $jmD; $i++)
					{
						$SYNC_PRJ	= $dataPecah[$i];
						$sqlUpdCOA	= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet-$Base_Debet, Base_Debet2 = Base_Debet2-$Base_Debet,
											Base_Kredit = Base_Kredit-$Base_Kredit, Base_Kredit2 = Base_Kredit2-$Base_Kredit, BaseD_$accYr = BaseD_$accYr-$Base_Debet,
											BaseK_$accYr = BaseK_$accYr-$Base_Kredit
										WHERE PRJCODE = '$SYNC_PRJ' AND Account_Number = '$ACC_NUM'";
						$this->db->query($sqlUpdCOA);
					}
				}
			// END : Update to COA - Debit
		endforeach;
	}

	function updateVLR_NEW($JournalH_Code, $parameters)
	{
		$curr_rate = 1; // Default IDR ke IDR

		$JournalH_Code 		= $parameters['JournalH_Code'];
		$PRJCODE			= $parameters['PRJCODE'];
		$JournalH_Date		= $parameters['JournalH_Date'];
		$PERIODM			= $parameters['PERIODM'];
		$PERIODY 			= $parameters['PERIODY'];
		$ITM_CODE 			= $parameters['ITM_CODE'];
		$ITM_VOLM 			= $parameters['ITM_VOLM'];
		$ITM_PRICE			= $parameters['ITM_PRICE'];
		$Base_Debet			= $parameters['Base_Debet'];
		$Base_Kredit 		= $parameters['Base_Kredit'];
		$Transaction_Type	= $parameters['Transaction_Type'];
		$comp_init			= $parameters['comp_init'];
		$JOBCODEID			= $parameters['JOBCODEID'];
		$ITM_CATEG			= $parameters['ITM_CATEG'];
		$JournalH_Desc 		= $parameters['JournalH_Desc'];
		$Other_Desc			= $parameters['Other_Desc'];

		$ITM_NAME			= '';
		$ITM_CODE_H			= '';
		$ITM_TYPE			= '';
		$ACC_ID 			= '';
		$ITM_GROUP 			= '';
		$ITM_CATEG 			= '';
		$sqlITMNM			= "SELECT ITM_NAME, ITM_TYPE, ITM_UNIT, ACC_ID_UM AS ACC_ID, ITM_GROUP, ITM_CATEG, ITM_LR
								FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
		$resITMNM			= $this->db->query($sqlITMNM)->result();
		foreach($resITMNM as $rowITMNM) :
			$ITM_NAME		= $rowITMNM->ITM_NAME;
			$ITM_TYPE		= $rowITMNM->ITM_TYPE;
			$ITM_UNIT		= $rowITMNM->ITM_UNIT;
			$ACC_ID			= $rowITMNM->ACC_ID;
			$ITM_GROUP		= $rowITMNM->ITM_GROUP;
			$ITM_CATEG		= $rowITMNM->ITM_CATEG;
			$ITM_LR			= $rowITMNM->ITM_LR;
		endforeach;

		if($Base_Debet > 0)
			$JOURN_VAL 		= $Base_Debet;
		else
			$JOURN_VAL 		= $Base_Kredit;

		// START : ITEM HISTORY
			$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code,
									Qty_Plus, Qty_Min, QtyRR_Plus, QtyRR_Min,
									Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
									JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
								VALUES ('$JournalH_Code', '$PRJCODE', '$JournalH_Date', '$ITM_CODE',
									$ITM_VOLM, 0, 0, 0,
									'$Transaction_Type', $Base_Debet, '$comp_init', 'IDR', 
									'$JOBCODEID', 3, '$Base_Debet', '$ITM_CATEG', '$Other_Desc')";
			$this->db->query($sqlHist);
		// END : ITEM HISTORY

		$FIELDVAL 	= $JOURN_VAL;

		// START : UPDATE L/R
			// L/R MANUFACTUR
				if($ITM_LR != '')
				{
					$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR-$JOURN_VAL	 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				
			// L/R CONTRACTOR // ADDING COST OR EXPENS
				if($ITM_GROUP == 'M' || $ITM_GROUP == 'B')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$JOURN_VAL	 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'U')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'S' || $ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'T' || $ITM_GROUP == 'A')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'I' || $ITM_GROUP == 'R')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				elseif($ITM_GROUP == 'O')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
				if($ITM_GROUP == 'GE' || $ITM_GROUP == 'ADM')
				{
					$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$JOURN_VAL
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
	        
	        // MIN STOCK ON PROFIT LOSS
		        if($ITM_GROUP == 'M')
				{
					// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6.ISFASTM, 7.ISWAGE, 8.ISRM, 9.ISWIP, 10.ISFG, 11.ISCOST
					if($ITM_TYPE == 1 || $ITM_TYPE == 8)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$JOURN_VAL	 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$JOURN_VAL	 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$JOURN_VAL	
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 9)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$JOURN_VAL	
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_TYPE == 10)
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$JOURN_VAL	
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
				}
				elseif($ITM_GROUP == 'T')
				{
					$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$JOURN_VAL 
								WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
					$this->db->query($updLR);
				}
		// END : UPDATE L/R

		// START : Update ITM Used
			// 1. UPDATE JOBLIST
				$ITM_USED	= 0;
				$ITM_USEDAM	= 0;
				$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
									WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
										AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resUSED1	= $this->db->query($sqlUSED1)->result();
				foreach($resUSED1 as $rowUSED1):
					$ITM_USED	= $rowUSED1->ITM_USED;
					$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
				endforeach;
				
				$sqlUpdJOBL	= "UPDATE tbl_joblist_detail SET 
									ITM_LASTP	= $ITM_PRICE,
									ITM_USED 	= ITM_USED+$ITM_VOLM, 
									ITM_USED_AM = ITM_USED_AM+$Base_Debet
								WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
									AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpdJOBL);
				
			// 2. UPDATE ITEM LIST
				$ITM_OUT	= 0;
				$UM_VOLM	= 0;
				$UM_AMOUNT	= 0;
				$sqlUSED1	= "SELECT ITM_USED, ITM_USED_AM FROM tbl_joblist_detail
									WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'
										AND ITM_CODE = '$ITM_CODE' LIMIT 1";
				$resUSED1	= $this->db->query($sqlUSED1)->result();
				foreach($resUSED1 as $rowUSED1):
					$ITM_USED	= $rowUSED1->ITM_USED;
					$ITM_USEDAM	= $rowUSED1->ITM_USED_AM;
				endforeach;
				$sqlUpdITML	= "UPDATE tbl_item SET
									ITM_LASTP	= $ITM_PRICE,
									ITM_OUT 	= ITM_OUT+$ITM_VOLM,
									ITM_OUTP 	= $ITM_PRICE,
									UM_VOLM 	= UM_VOLM+$ITM_VOLM,
									UM_AMOUNT 	= UM_AMOUNT+$Base_Debet
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpdITML);
		// END : Update ITM Used
	}
}
?>