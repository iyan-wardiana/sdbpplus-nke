<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 21 Oktober 2018
	* File Name	= v_material_req_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	foreach($vwDocPatt as $row) :
		$Pattern_Code = $row->Pattern_Code;
		$Pattern_Position = $row->Pattern_Position;
		$Pattern_YearAktive = $row->Pattern_YearAktive;
		$Pattern_MonthAktive = $row->Pattern_MonthAktive;
		$Pattern_DateAktive = $row->Pattern_DateAktive;
		$Pattern_Length = $row->Pattern_Length;
		$useYear = $row->useYear;
		$useMonth = $row->useMonth;
		$useDate = $row->useDate;
	endforeach;
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "XXX";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$yearCur	= date('Y');
	$sqlC		= "tbl_mr_header WHERE Patt_Year = $yearCur AND PRJCODE = '$PRJCODE'";
	$myCount 	= $this->db->count_all($sqlC);
	
	/*$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_mr_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$myMax 		= $myCount+1;
	$thisMonth 	= $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	
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
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;
	
	//$PACODE		= substr($lastPatternNumb, -4);
	$TRXTIME1		= date('ymdHis');
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$DocNumber		= "$Pattern_Code$PRJCODE-$TRXTIME1";
	$MR_NUM 		= $DocNumber;
	
	$PRYEAR			= date('y');
	$MRONTH			= date('m');
	$MR_CODE		= "$Pattern_Code.$lastPatternNumb.$PRYEAR.$MRONTH"; // MANUAL CODE
	
	$MR_DATE		= date('m/d/Y');
	$MR_DATEU		= date('m/d/Y');
	$MR_DATER		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$JO_NUM			= '';
	$JO_CODE		= '';
	$MR_NOTE		= '';
	$MR_NOTE1		= '';
	$MR_STAT 		= 1;
	$MR_AMOUNT		= 0;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;
	$MR_AMOUNT		= 0;
	$MR_AMOUNTAPP	= 0;
	$MR_REFNO		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_mr_header~$Pattern_Length";
	$dataTarget		= "MR_CODE";
}
else
{
	$isSetDocNo 		= 1;
	$MR_NUM				= $default['MR_NUM'];
	$DocNumber			= $default['MR_NUM'];
	$MR_CODE			= $default['MR_CODE'];
	$MR_DATED			= $default['MR_DATE'];
	$MR_DATE			= date('m/d/Y',strtotime($MR_DATED));
	$MR_DATEU		= $default['MR_DATEU'];
	$MR_DATEU		= date('m/d/Y',strtotime($MR_DATEU));
	$PRJCODE			= $default['PRJCODE'];
	$PRJNAME 			= $default['PRJNAME'];
	$JO_NUM 			= $default['JO_NUM'];
	$JO_CODE 			= $default['JO_CODE'];
	$MR_NOTE 			= $default['MR_NOTE'];
	$MR_NOTE1 			= $default['MR_NOTE1'];
	$MR_AMOUNT			= $default['MR_AMOUNT'];
	$MR_REFNO			= $default['MR_REFNO'];
	$MR_STAT 			= $default['MR_STAT'];
	$Patt_Year			= $default['Patt_Year'];
	$Patt_Month			= $default['Patt_Month'];
	$Patt_Date			= $default['Patt_Date'];
	$Patt_Number		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
}

// REJECT FUNCTION
	$DOC_NO	= '';
	// CEK ACCESS OTORIZATION
		/*$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		$resAPP	= $this->db->count_all($sqlAPP);*/
	// CEK IR
		/*$DOC_NO	= '';
		$sqlIRC	= "tbl_po_header WHERE MR_NUM = '$MR_NUM' AND PO_STAT != 5";
		$isUSED	= $this->db->count_all($sqlIRC);
		if($isUSED > 0)
		{
			$sqlPO 	= "SELECT PO_CODE FROM tbl_po_header WHERE MR_NUM = '$MR_NUM' AND PO_STAT != 5 LIMIT 1";
			$resPO	= $this->db->query($sqlPO)->result();
			foreach($resPO as $rowPO):
				$DOC_NO	= $rowPO->PO_CODE;
			endforeach;
		}*/
?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'UsedPlan')$UsedPlan = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah";
			$subTitleD	= "permintaan pembelian";
			$isManual	= "Centang untuk kode manual.";
			$alertREJ	= "Tidak dapat direject. Sudah digunakan oleh Dokumen No.: ";
			
			$alert1		= "No. JO tidak boleh kosong.";
			$alert2		= "Catatan JO tidak boleh kosong.";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "purchase request";
			$isManual	= "Check to manual code.";
			$alertREJ	= "Can not be rejected. Used by document No.: ";
			
			$alert1		= "JO No. can not be empty.";
			$alert2		= "JO Description can not be empty.";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// MR_NUM - MR_AMOUNT
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP		= $rowAPP->MAX_STEP;
					$APPROVER_1		= $rowAPP->APPROVER_1;
					if($APPROVER_1 != '')
					{
						$EMPN_1		= '';
						$sqlEMPC_1	= "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
						$resEMPC_1	= $this->db->count_all($sqlEMPC_1);
						if($resEMPC_1 > 0)
						{
							$sqlEMP_1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
							$resEMP_1	= $this->db->query($sqlEMP_1)->result();
							foreach($resEMP_1 as $rowEMP) :
								$FN_1	= $rowEMP->First_Name;
								$LN_1	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_1		= "$FN_1 $LN_1";
						}
					}
					$APPROVER_2	= $rowAPP->APPROVER_2;
					if($APPROVER_2 != '')
					{
						$EMPN_2		= '';
						$sqlEMPC_2	= "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
						$resEMPC_2	= $this->db->count_all($sqlEMPC_2);
						if($resEMPC_2 > 0)
						{
							$sqlEMP_2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
							$resEMP_2	= $this->db->query($sqlEMP_2)->result();
							foreach($resEMP_2 as $rowEMP) :
								$FN_2	= $rowEMP->First_Name;
								$LN_2	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_2		= "$FN_2 $LN_2";
						}
					}
					$APPROVER_3	= $rowAPP->APPROVER_3;
					if($APPROVER_3 != '')
					{
						$EMPN_3		= '';

						$sqlEMPC_3	= "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
						$resEMPC_3	= $this->db->count_all($sqlEMPC_3);
						if($resEMPC_3 > 0)
						{
							$sqlEMP_3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
							$resEMP_3	= $this->db->query($sqlEMP_3)->result();
							foreach($resEMP_3 as $rowEMP) :
								$FN_3	= $rowEMP->First_Name;
								$LN_3	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_3		= "$FN_3 $LN_3";
						}
					}
					$APPROVER_4	= $rowAPP->APPROVER_4;
					if($APPROVER_4 != '')
					{
						$EMPN_4		= '';
						$sqlEMPC_4	= "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
						$resEMPC_4	= $this->db->count_all($sqlEMPC_4);
						if($resEMPC_4 > 0)
						{
							$sqlEMP_4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
							$resEMP_4	= $this->db->query($sqlEMP_4)->result();
							foreach($resEMP_4 as $rowEMP) :
								$FN_4	= $rowEMP->First_Name;
								$LN_4	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_4		= "$FN_4 $LN_4";
						}
					}
					$APPROVER_5	= $rowAPP->APPROVER_5;
					if($APPROVER_5 != '')
					{
						$EMPN_5		= '';
						$sqlEMPC_5	= "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
						$resEMPC_5	= $this->db->count_all($sqlEMPC_5);
						if($resEMPC_5 > 0)
						{
							$sqlEMP_5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
							$resEMP_5	= $this->db->query($sqlEMP_5)->result();
							foreach($resEMP_5 as $rowEMP) :
								$FN_5	= $rowEMP->First_Name;
								$LN_5	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_5		= "$FN_5 $LN_5";
						}
					}
				endforeach;
				$disableAll	= 0;
			
				// CHECK AUTH APPROVE TYPE
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$MR_NUM'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				
				$BefStepApp	= $APP_STEP - 1;
				if($resC_App == $BefStepApp)
				{
					$canApprove	= 1;
				}
				elseif($resC_App == $APP_STEP)
				{
					$canApprove	= 0;
					$descApp	= "You have Approved";
					$statcoloer	= "success";
				}
				else
				{
					$canApprove	= 0;
					$descApp	= "Awaiting";
					$statcoloer	= "warning";
				}
							 
				if($APP_STEP == $MAX_STEP)
					$IS_LAST		= 1;
				else
					$IS_LAST		= 0;
				
				// Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
				// This roles are for All Approval. Except PR and Receipt
				// NOTES
				// $APPLIMIT_1 		= Maximum Limit to Approve
				// $APPROVE_AMOUNT	= Amount must be Approved
				$APPROVE_AMOUNT 	= $MR_AMOUNT;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
				}
			}
			else
			{
				$canApprove	= 0;
				$descApp	= "You can not approve this document.";
				$statcoloer	= "danger";
				$IS_LAST	= 0;
				$APP_STEP	= 0;
			}
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE
		
		$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE_HO'";
		$resultPRJ 		= $this->db->query($sqlPRJ)->result();
		
		foreach($resultPRJ as $rowPRJ) :
			$PRJNAMEHO 	= $rowPRJ->PRJNAME;
		endforeach;

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
				<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/request.png'; ?>" style="max-width:40px; max-height:40px" >
			    <?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="box box-primary">
		    	<div class="box-body chart-responsive">
		        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		            <input type="hidden" name="rowCount" id="rowCount" value="0">
						<?php if($isSetDocNo == 0) { ?>
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <div class="alert alert-danger alert-dismissible">
		                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
		                            <?php echo $docalert2; ?>
		                        </div>
		                    </div>
		                </div>
		                <?php } ?>
		            	<div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RequestNo; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="text" class="form-control" style="max-width:200px;text-align:left" name="MR_NUM1" id="MR_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
		                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="MR_NUM" id="MR_NUM" size="30" value="<?php echo $DocNumber; ?>" />
		                        <input type="text" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RequestCode; ?></label>
		                    <div class="col-sm-10">
		                    	<!-- <label> -->
		                    		<input type="text" class="form-control" style="text-align:left" id="MR_CODE" name="MR_CODE" size="5" value="<?php echo $MR_CODE; ?>" />
		                        <!-- </label>
		                        <label>
		                        	&nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
		                        </label>
		                        <label style="font-style:italic">
		                        	<?php echo $isManual; ?>
		                        </label> -->
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
		                    <div class="col-sm-10">
		                    	<div class="input-group date">
		                        <div class="input-group-addon">
		                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="MR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $MR_DATE; ?>" style="width:106px"></div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $UsedPlan ?> </label>
		                    <div class="col-sm-10">
		                        <div class="input-group date">
		                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                            <input type="text" name="MR_DATEU" class="form-control pull-left" id="datepicker1" value="<?php echo $MR_DATEU; ?>" style="width:106px">
		                        </div>
		                    </div>
		                </div>
		            	<div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
		                    <div class="col-sm-10">
		                    	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" disabled>
		                          <option value="none">--- None ---</option>
		                          <?php echo $i = 0;
		                            if($countPRJ > 0)
		                            {
		                                foreach($vwPRJ as $row) :
		                                    $PRJCODE1 	= $row->PRJCODE;
		                                    $PRJNAME1 	= $row->PRJNAME;
		                                    ?>
		                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME1"; ?></option>
		                                  <?php
		                                endforeach;
		                            }
		                            else
		                            {
		                                ?>
		                                  <option value="none">--- No Unit Found ---</option>
		                              <?php
		                            }
		                            ?>
		                        </select>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> JO</label>
		                    <div class="col-sm-10">
		                        <div class="input-group">
		                            <div class="input-group-btn">
		                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                            </div>
		                            <input type="hidden" class="form-control" name="JO_NUM" id="JO_NUM" style="max-width:350px;" value="<?php echo $JO_NUM; ?>" />
		                            <input type="hidden" class="form-control" name="JO_CODE" id="JO_CODE" style="max-width:350px;" value="<?php echo $JO_CODE; ?>" />
		                            <input type="text" class="form-control" name="JO_CODE1" id="JO_CODE1" value="<?php echo $JO_CODE; ?>" onClick="getJOCODE();" <?php if($MR_STAT != 1 && $MR_STAT != 4) { ?> disabled <?php } ?>>
		                        </div>
		                    </div>
		                </div>
						<?php
		                    $url_selJO	= site_url('c_production/c_mr180d0c/s3l4llj0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                ?>
		                <script>
		                    var url1 = "<?php echo $url_selJO;?>";
		                    function getJOCODE()
		                    {
		                        PRJCODE	= document.getElementById('PRJCODE').value;
		                        
		                        title 	= 'Select Item';
		                        w = 1000;
		                        h = 550;
		                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                        var left = (screen.width/2)-(w/2);
		                        var top = (screen.height/2)-(h/2);
		                        return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                    }
		                </script>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
		                    <div class="col-sm-10">
		                    	<textarea name="MR_NOTE" class="form-control" id="MR_NOTE" cols="30"><?php echo $MR_NOTE; ?></textarea>
		                    </div>
		                </div>
		                <?php
						if($MR_NOTE1 != '')
						{
							?>
		                    <div class="form-group">
		                        <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
		                        <div class="col-sm-10">
		                            <textarea name="MR_NOTE1" class="form-control" id="MR_NOTE1" cols="30" disabled><?php echo $MR_NOTE1; ?></textarea>                        
		                        </div>
		                    </div>
		                	<?php
						}
						?>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $MR_STAT; ?>">
								<?php
									$isDisabled = 1;
									if($MR_STAT == 1 || $MR_STAT == 4)
									{
										$isDisabled = 0;
									}
									?>
										<select name="MR_STAT" id="MR_STAT" class="form-control select2">
											<?php
											if($MR_STAT != 1 AND $MR_STAT != 4) 
											{
												?>
													<option value="1"<?php if($MR_STAT == 1) { ?> selected <?php } else { ?> disabled <?php } ?>>New</option>
													<option value="2"<?php if($MR_STAT == 2) { ?> selected <?php } else { ?> disabled <?php } ?>>Confirm</option>
													<option value="3"<?php if($MR_STAT == 3) { ?> selected <?php } else { ?> disabled <?php } ?>>Approve</option>
													<option value="4"<?php if($MR_STAT == 4) { ?> selected <?php } else { ?> disabled <?php } ?>>Revising</option>
													<option value="5"<?php if($MR_STAT == 5) { ?> selected <?php } else { ?> disabled <?php } ?>>Rejected</option>
													<option value="6"<?php if($MR_STAT == 6) { ?> selected <?php } ?>>Closed</option>
													<option value="7"<?php if($MR_STAT == 7) { ?> selected <?php } else { ?> disabled <?php } ?>>Waiting</option>
												<?php
											}
											else
											{
												?>
													<option value="1"<?php if($MR_STAT == 1) { ?> selected <?php } ?>>New</option>
													<option value="2"<?php if($MR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
												<?php
											}
											?>
										</select>
									<?php
									$theProjCode 	= "$PRJCODE~$MR_REFNO";
		                        	$url_AddItem	= site_url('c_production/c_mr180d0c/sH0w4llI73M/?id='.$this->url_encryption_helper->encode_url($theProjCode));
		                        ?>
		                        <input type="hidden" name="MR_AMOUNT" id="MR_AMOUNT" value="<?php echo $MR_AMOUNT; ?>">
		                    </div>
		                </div>
		                <div class="form-group" <?php if($MR_STAT != 1 AND $MR_STAT != 4) { ?> style="display:none <?php } ?>">
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <script>
		                            var url = "<?php echo $url_AddItem;?>";
		                            function selectitem()
		                            {
										JO_NUM		= $("#JO_NUM").val();
										JO_CODE1	= $("#JO_CODE1").val();

										if(JO_CODE1 == '')
										{
											swal('<?php echo $alert1; ?>',
											{
												icon: "warning",
											})
											.then(function()
								            {
								                swal.close();
								                $('#JO_CODE1').focus();
								            });
											return false;
										}

										MR_NOTE	= $("#MR_NOTE").val();
										if(MR_NOTE == '')
										{
											swal('<?php echo $alert2; ?>',
											{
												icon: "warning",
											})
											.then(function()
								            {
								                swal.close();
								                $('#MR_NOTE').focus();
								            });
											return false;
										}
										
										
		                                title = 'Select Item';
		                                w = 1000;
		                                h = 550;
		                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                var left = (screen.width/2)-(w/2);
		                                var top = (screen.height/2)-(h/2);
		                                return window.open(url+'&JO_NUM='+JO_NUM, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                            }
		                        </script>
		                        <button class="btn btn-success" type="button" onClick="selectitem();">
		                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
		                        </button>
		                	</div>
		                </div>
		                <div class="row">
		                    <div class="col-md-12">
		                        <div class="box box-primary">
			                        <div class="search-table-outter">
			                            <table id="tbl" class="table table-bordered table-striped" width="100%">
			                                <tr style="background:#CCCCCC">
				                              	<th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
				                              	<th width="3%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
				                              	<th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
				                              	<th colspan="3" style="text-align:center"><?php echo $ItemQty; ?> </th>
				                              	<th rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
				                              	<th width="24%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
			                          		</tr>
				                            <tr style="background:#CCCCCC">
				                              	<th style="text-align:center;"><?php echo $Planning ?> </th>
				                              	<th style="text-align:center;"><?php echo $Requested ?> </th>
				                              	<th style="text-align:center"><?php echo $RequestNow ?></th>
				                            </tr>
				                            <?php
				                            if($task == 'edit')
				                            {
				                                $sqlDET	= "SELECT A.MR_NUM, A.MR_CODE, A.PRJCODE, A.ITM_CODE, A.SNCODE, A.ITM_CATEG, A.ITM_UNIT, 
																A.MR_VOLM, A.MR_PRICE, A.MR_TOTAL, A.MR_DESC, A.IRM_VOLM, A.IRM_AMOUNT,
																A.JOBCODEDET, A.JOBCODEID,
																B.ITM_NAME
															FROM tbl_mr_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE MR_NUM = '$MR_NUM' 
																AND B.PRJCODE = '$PRJCODE'";
				                                $result = $this->db->query($sqlDET)->result();
				                                $i		= 0;
				                                $j		= 0;
												
												foreach($result as $row) :
													$currentRow  	= ++$i;
													$MR_NUM 		= $row->MR_NUM;
													$MR_CODE 		= $row->MR_CODE;
													$PRJCODE 		= $row->PRJCODE;
													$ITM_CODE 		= $row->ITM_CODE;
													$ITM_NAME 		= $row->ITM_NAME;
													$SNCODE 		= $row->SNCODE;
													$ITM_CATEG 		= $row->ITM_CATEG;
													$ITM_UNIT 		= $row->ITM_UNIT;
													$MR_VOLM 		= $row->MR_VOLM;
													$MR_PRICE 		= $row->MR_PRICE;
													$MR_TOTAL 		= $row->MR_TOTAL;
													$MR_DESC 		= $row->MR_DESC;
													$JOBCODEDET		= $row->JOBCODEDET;
													$JOBCODEID 		= $row->JOBCODEID;
													$IRM_VOLM 		= $row->IRM_VOLM;
													$IRM_AMOUNT		= $row->IRM_AMOUNT;
													$itemConvertion	= 1;
													
													$ITM_QTY		= 0;
													$ITM_TOTAL		= 0;
													$REQ_VOLM		= 0;
													$REQ_AMOUNT		= 0;
													$sql			= "SELECT SUM(A.ITM_QTY) AS ITM_QTY, A.ITM_PRICE, A.MR_QTY, A.MR_PRICE
																		FROM tbl_jo_stfdetail A
																			INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																				AND B.PRJCODE = '$PRJCODE'
																		WHERE A.PRJCODE = '$PRJCODE' AND B.ISRM = 1
																			AND A.ITM_CODE = '$ITM_CODE'";
													$resSQL			= $this->db->query($sql)->result();
													foreach($resSQL as $rowSQL) :
														$ITM_QTY	= $rowSQL->ITM_QTY;
														$ITM_PRICE	= $rowSQL->ITM_PRICE;
														$ITM_TOTAL	= $ITM_QTY * $ITM_PRICE;
														$MR_QTY		= $rowSQL->MR_QTY;
														$MR_PRICE	= $rowSQL->MR_PRICE;
													endforeach;
										
												/*	if ($j==1) {
														echo "<tr class=zebra1>";
														$j++;
													} else {
														echo "<tr class=zebra2>";
														$j--;
													}*/
													?> 
				                                    <tr id="tr_<?php echo $currentRow; ?>">
				                                        <td width="3%" height="25" style="text-align:left">
				                                          <?php
				                                            if($MR_STAT == 1)
				                                            {
				                                                ?>
				                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
				                                                <?php
				                                            }
				                                            else
				                                            {
				                                                echo "$currentRow.";
				                                            }
				                                          ?>
				                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
				                                            <!-- Checkbox -->
				                                        </td>
				                                        <td width="3%" style="text-align:left"> <!-- Item Code -->
				                                            <?php echo $ITM_CODE; ?>                                      
				                                            <input type="hidden" id="data<?php echo $currentRow; ?>MR_NUM" name="data[<?php echo $currentRow; ?>][MR_NUM]" value="<?php echo $MR_NUM; ?>" class="form-control" style="max-width:300px;">
				                                            <input type="hidden" id="data<?php echo $currentRow; ?>MR_CODE" name="data[<?php echo $currentRow; ?>][MR_CODE]" value="<?php echo $MR_CODE; ?>" class="form-control" style="max-width:300px;">
				                                            <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
				                                            <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CATEG" name="data[<?php echo $currentRow; ?>][ITM_CATEG]" value="<?php echo $ITM_CATEG; ?>" class="form-control" style="max-width:300px;">
				                                            <input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
				                                        </td>
				                                        <td width="33%" style="text-align:left">
				                                            <?php echo $ITM_NAME; ?> <!-- Item Name -->
				                                        </td>
				                                        <td width="11%" style="text-align:right"> <!-- Item Bdget -->
				                                        	<?php print number_format($ITM_QTY, $decFormat); ?>
				                                            <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_BUDGVOLX<?php echo $currentRow; ?>" id="ITM_BUDGVOLX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" disabled >
				                                            <input type="hidden" style="text-align:right" name="ITM_BUDGVOL<?php echo $currentRow; ?>" id="ITM_BUDGVOL<?php echo $currentRow; ?>" value="<?php echo $ITM_QTY; ?>" >
				                                            <input type="hidden" style="text-align:right" name="ITM_BUDGPRC<?php echo $currentRow; ?>" id="ITM_BUDGPRC<?php echo $currentRow; ?>" value="<?php echo $MR_PRICE; ?>" >
				                                            <input type="hidden" style="text-align:right" name="ITM_BUDGTOT<?php echo $currentRow; ?>" id="ITM_BUDGTOT<?php echo $currentRow; ?>" value="<?php echo $ITM_TOTAL; ?>" >
				                                        </td>
				                                        <td width="11%" style="text-align:right">  <!-- Item Requested FOR INFORMATION ONLY -->
				                                        	<?php print number_format($REQ_VOLM, $decFormat); ?>
				                                            <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_TOTREQVOLx<?php echo $currentRow; ?>" id="ITM_TOTREQVOLx<?php echo $currentRow; ?>" value="<?php print number_format($REQ_VOLM, $decFormat); ?>" disabled >
				                                            <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_TOTREQVOL<?php echo $currentRow; ?>" id="ITM_TOTREQVOL<?php echo $currentRow; ?>" value="<?php print $REQ_VOLM; ?>" >
				                                            <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_TOTREQAMN<?php echo $currentRow; ?>" id="ITM_TOTREQAMN<?php echo $currentRow; ?>" value="<?php print $REQ_AMOUNT; ?>" >
				                                        </td>
				                                        <td width="11%" style="text-align:right"> <!-- Item Request Now -- MR_VOLM -->
				                                        	<?php print number_format($MR_VOLM, $decFormat); ?>
				                                            <input type="hidden" name="MR_VOLM<?php echo $currentRow; ?>" id="MR_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($MR_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> >
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][MR_VOLM]" id="data<?php echo $currentRow; ?>MR_VOLM" value="<?php print $MR_VOLM; ?>" class="form-control" style="max-width:300px;" >
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][MR_PRICE]" id="data<?php echo $currentRow; ?>MR_PRICE" value="<?php print $MR_PRICE; ?>" class="form-control" style="max-width:300px;" >
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][MR_TOTAL]" id="data<?php echo $currentRow; ?>MR_TOTAL" value="<?php print $MR_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
				                                            <input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
				                                        </td>
				                                        <td width="4%" style="text-align:center" nowrap> <!-- Item Unit Type -- ITM_UNIT -->
				                                            <?php echo $ITM_UNIT; ?>
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
				                                        </td>
				                                        <td width="24%">
				                                        	<?php print $MR_DESC; ?>
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][MR_DESC]" id="data<?php echo $currentRow; ?>MR_DESC" size="20" value="<?php print $MR_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left"<?php if($isDisabled == 1) { ?> disabled <?php } ?>>
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
				                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
				                                        </td>
				                                    </tr>
				                              	<?php
				                             	endforeach;
				                            }
				                            ?>
				                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                        	</table>
			                      </div>
		                    	</div>
		                    </div>
		                </div>
		                <br>
		                <div class="form-group">
		                    <div class="col-sm-offset-2 col-sm-10">
		                        <?php
		                        	if($task=='add')
									{
										if(($MR_STAT == 1 || $MR_STAT == 4) && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary">
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
									}
									else
									{
										if(($MR_STAT == 1 || $MR_STAT == 4) && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnREJECT" >
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
										elseif($MR_STAT == 3 && $ISDELETE == 1)
										{
											?>
												<button class="btn btn-primary" id="btnREJECT" style="display:none" >
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
										else
										{
											?>
												<button class="btn btn-primary" id="btnREJECT" style="display:none" >
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_production/c_mr180d0c/g4ll_m4tr3q/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>

						<?php
		                    $DOC_NUM	= $MR_NUM;
		                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
		                    $resCAPPH	= $this->db->count_all($sqlCAPPH);

                            $sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
										AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
							$resAPP	= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;;
							endforeach;
		                ?>
		                <div class="row">
		                    <div class="col-md-12">
		                        <div class="box box-danger collapsed-box">
		                            <div class="box-header with-border">
		                                <h3 class="box-title"><?php echo $Approval; ?></h3>
		                                <div class="box-tools pull-right">
		                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                    </button>
		                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                    </button>
		                                </div>
		                            </div>
		                            <div class="box-body">
		                            <?php
										$SHOWOTH		= 0;
										$AH_ISLAST		= 0;
										$APPROVER_1A	= 0;
										$APPROVER_2A	= 0;
										$APPROVER_3A	= 0;
										$APPROVER_4A	= 0;
										$APPROVER_5A	= 0;
		                                if($APPROVER_1 != '')
		                                {
		                                    $boxCol_1	= "red";
		                                    $sqlCAPPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                    $resCAPPH_1	= $this->db->count_all($sqlCAPPH_1);
		                                    if($resCAPPH_1 > 0)
		                                    {
		                                        $boxCol_1	= "green";
		                                        $Approver	= $Approved;
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_1	= "SELECT AH_APPROVED, AH_ISLAST 
																FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
		                                        $resAPPH_1	= $this->db->query($sqlAPPH_1)->result();
		                                        foreach($resAPPH_1 as $rowAPPH_1):
		                                            $APPROVED_1	= $rowAPPH_1->AH_APPROVED;
		                                            $AH_ISLAST	= $rowAPPH_1->AH_ISLAST;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_1 == 0)
		                                    {
												$Approver	= $NotYetApproved;
												$class		= "glyphicon glyphicon-remove-sign";
												$APPROVED_1	= "Not Set";
												
												$sqlCAPPH_1A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
												$resCAPPH_1A	= $this->db->count_all($sqlCAPPH_1A);
												if($resCAPPH_1A > 0)
												{
													$SHOWOTH	= 1;
													$APPROVER_1A= 1;
													$EMPN_1A	= '';
													$AH_ISLAST1A=0;
													$APPROVED_1A= '0000-00-00';
													$boxCol_1A	= "green";
													$Approver1A	= $Approved;
													$class1A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_1A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
													$resAPPH_1A	= $this->db->query($sqlAPPH_1A)->result();
													foreach($resAPPH_1A as $rowAPPH_1A):
														$EMPN_1A		= $rowAPPH_1A->COMPNAME;
														$AH_ISLAST1A	= $rowAPPH_1A->AH_ISLAST;
														$APPROVED_1A	= $rowAPPH_1A->AH_APPROVED;
													endforeach;
												}
		                                    }
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_1; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">

															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_1", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_1; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_2 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_2	= "red";
		                                    $sqlCAPPH_2	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
		                                    $resCAPPH_2	= $this->db->count_all($sqlCAPPH_2);
		                                    if($resCAPPH_2 > 0)
		                                    {
		                                        $boxCol_2	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_2	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
		                                        $resAPPH_2	= $this->db->query($sqlAPPH_2)->result();
		                                        foreach($resAPPH_2 as $rowAPPH_2):
		                                            $APPROVED_2	= $rowAPPH_2->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_2 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_2	= "Not Set";
												
												$sqlCAPPH_2A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
												$resCAPPH_2A	= $this->db->count_all($sqlCAPPH_2A);
												if($resCAPPH_2A > 0)
												{
													$APPROVER_2A= 1;
													$EMPN_2A	= '';
													$AH_ISLAST2A=0;
													$APPROVED_2A= '0000-00-00';
													$boxCol_2A	= "green";
													$Approver2A	= $Approved;
													$class2A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_2A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
													$resAPPH_2A	= $this->db->query($sqlAPPH_2A)->result();
													foreach($resAPPH_2A as $rowAPPH_2A):
														$EMPN_2A		= $rowAPPH_2A->COMPNAME;
														$AH_ISLAST2A	= $rowAPPH_2A->AH_ISLAST;
														$APPROVED_2A	= $rowAPPH_2A->AH_APPROVED;
													endforeach;
												}
											}
		                                    
		                                    /*if($resCAPPH == 0)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_2	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_2; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_2", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_2; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_3 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_3	= "red";
		                                    $sqlCAPPH_3	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
		                                    $resCAPPH_3	= $this->db->count_all($sqlCAPPH_3);
		                                    if($resCAPPH_3 > 0)
		                                    {
		                                        $boxCol_3	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_3	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
		                                        $resAPPH_3	= $this->db->query($sqlAPPH_3)->result();
		                                        foreach($resAPPH_3 as $rowAPPH_3):
		                                            $APPROVED_3	= $rowAPPH_3->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_3 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_3	= "Not Set";
												
												$sqlCAPPH_3A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
												$resCAPPH_3A	= $this->db->count_all($sqlCAPPH_3A);
												if($resCAPPH_3A > 0)
												{
													$APPROVER_3A= 1;
													$EMPN_3A	= '';
													$AH_ISLAST3A=0;
													$APPROVED_3A= '0000-00-00';
													$boxCol_3A	= "green";
													$Approver3A	= $Approved;
													$class3A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_3A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
													$resAPPH_3A	= $this->db->query($sqlAPPH_3A)->result();
													foreach($resAPPH_3A as $rowAPPH_3A):
														$EMPN_3A		= $rowAPPH_3A->COMPNAME;
														$AH_ISLAST3A	= $rowAPPH_3A->AH_ISLAST;
														$APPROVED_3A	= $rowAPPH_3A->AH_APPROVED;
													endforeach;
												}
		                                    }
		                                    
		                                    /*if($resCAPPH == 1)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_3	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_3; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_3", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_3; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_4 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_4	= "red";
		                                    $sqlCAPPH_4	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
		                                    $resCAPPH_4	= $this->db->count_all($sqlCAPPH_4);
		                                    if($resCAPPH_4 > 0)
		                                    {
		                                        $boxCol_4	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_4	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
		                                        $resAPPH_4	= $this->db->query($sqlAPPH_4)->result();
		                                        foreach($resAPPH_4 as $rowAPPH_4):
		                                            $APPROVED_4	= $rowAPPH_4->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_4 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_4	= "Not Set";
												
												$sqlCAPPH_4A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
												$resCAPPH_4A	= $this->db->count_all($sqlCAPPH_4A);
												if($resCAPPH_4A > 0)
												{
													$APPROVER_4A= 1;
													$EMPN_4A	= '';
													$AH_ISLAST4A=0;
													$APPROVED_4A= '0000-00-00';
													$boxCol_4A	= "green";
													$Approver4A	= $Approved;
													$class4A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_4A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
													$resAPPH_4A	= $this->db->query($sqlAPPH_4A)->result();
													foreach($resAPPH_4A as $rowAPPH_4A):
														$EMPN_4A		= $rowAPPH_4A->COMPNAME;
														$AH_ISLAST4A	= $rowAPPH_4A->AH_ISLAST;
														$APPROVED_4A	= $rowAPPH_4A->AH_APPROVED;
													endforeach;
												}
		                                    }
		                                    
		                                    /*if($resCAPPH == 2)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_4	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_4; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_4", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_4; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                                if($APPROVER_5 != '' && $AH_ISLAST == 0)
		                                {
		                                    $boxCol_5	= "red";
		                                    $sqlCAPPH_5	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
		                                    $resCAPPH_5	= $this->db->count_all($sqlCAPPH_5);
		                                    if($resCAPPH_5 > 0)
		                                    {
		                                        $boxCol_5	= "green";
		                                        $class		= "glyphicon glyphicon-ok-sign";
		                                        
		                                        $sqlAPPH_5	= "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
		                                        $resAPPH_5	= $this->db->query($sqlAPPH_5)->result();
		                                        foreach($resAPPH_5 as $rowAPPH_5):
		                                            $APPROVED_5	= $rowAPPH_5->AH_APPROVED;
		                                        endforeach;
		                                    }
		                                    elseif($resCAPPH_5 == 0)
		                                    {
		                                        $Approver	= $NotYetApproved;
		                                        $class		= "glyphicon glyphicon-remove-sign";
		                                        $APPROVED_5	= "Not Set";
												
												$sqlCAPPH_5A	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
												$resCAPPH_5A	= $this->db->count_all($sqlCAPPH_5A);
												if($resCAPPH_5A > 0)
												{
													$APPROVER_5A= 1;
													$EMPN_5A	= '';
													$AH_ISLAST5A=0;
													$APPROVED_5A= '0000-00-00';
													$boxCol_5A	= "green";
													$Approver5A	= $Approved;
													$class5A	= "glyphicon glyphicon-ok-sign";
													
													$sqlAPPH_5A	= "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
																		CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
																	FROM tbl_approve_hist A 
																		INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
													$resAPPH_5A	= $this->db->query($sqlAPPH_5A)->result();
													foreach($resAPPH_5A as $rowAPPH_5A):
														$EMPN_5A		= $rowAPPH_5A->COMPNAME;
														$AH_ISLAST5A	= $rowAPPH_5A->AH_ISLAST;
														$APPROVED_5A	= $rowAPPH_5A->AH_APPROVED;
													endforeach;
												}
		                                    }
		                                    
		                                    /*if($resCAPPH == 3)
		                                    {
		                                        $Approver	= $Awaiting;
		                                        $boxCol_5	= "yellow";
		                                        $class		= "glyphicon glyphicon-info-sign";
		                                    }*/
											?>
												<div class="col-md-3">
													<div class="info-box bg-<?php echo $boxCol_5; ?>">
														<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
														<div class="info-box-content">
															<span class="info-box-text"><?php echo $Approver; ?></span>
															<span class="info-box-number"><?php echo cut_text ("$EMPN_5", 20); ?></span>
															<div class="progress">
																<div class="progress-bar" style="width: 50%"></div>
															</div>
															<span class="progress-description">
																<?php echo $APPROVED_5; ?>
															</span>
														</div>
													</div>
												</div>
											<?php
		                                }
		                            ?>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <?php if($SHOWOTH == 1) { ?>
		                    <div class="row">
		                        <div class="col-md-12">
		                            <div class="box box-danger collapsed-box">
		                                <div class="box-header with-border">
		                                    <h3 class="box-title"><?php echo $InOthSett; ?></h3>
		                                    <div class="box-tools pull-right">
		                                        <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
		                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                                        </button>
		                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
		                                        </button>
		                                    </div>
		                                </div>
		                                <div class="box-body">
		                                <?php
		                                    if($APPROVER_1A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_1A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class1A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver1A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_1A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_1A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_2A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_2A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class2A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver2A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_2A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_2A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_3A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_3A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class3A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver3A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_3A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_3A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_4A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_4A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class4A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver4A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_4A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_4A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                    if($APPROVER_5A == 1)
		                                    {
		                                        ?>
		                                            <div class="col-md-3">
		                                                <div class="info-box bg-<?php echo $boxCol_5A; ?>">
		                                                    <span class="info-box-icon"><i class="<?php echo $class5A; ?>"></i></span>
		                                                    <div class="info-box-content">
		                                                        <span class="info-box-text"><?php echo $Approver5A; ?></span>
		                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_5A", 20); ?></span>
		                                                        <div class="progress">
		                                                            <div class="progress-bar" style="width: 50%"></div>
		                                                        </div>
		                                                        <span class="progress-description">
		                                                            <?php echo $APPROVED_5A; ?>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        <?php
		                                    }
		                                ?>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                <?php } ?>
			            <?php
			                $DefID      = $this->session->userdata['Emp_ID'];
			                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			                if($DefID == 'D15040004221')
			                    echo "<font size='1'><i>$act_lnk</i></font>";
			            ?>
					</form>
		    	</div>
		    </div>
		</section>
	</body>
</html>

<script>
	$(function () {
	//Initialize Select2 Elements
	$(".select2").select2();
	
	//Datemask dd/mm/yyyy
	$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
	//Datemask2 mm/dd/yyyy
	$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
	//Money Euro
	$("[data-mask]").inputmask();
	
	//Date range picker
	$('#reservation').daterangepicker();
	//Date range picker with time picker
	$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
	//Date range as a button
	$('#daterange-btn').daterangepicker(
		{
		  ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		  },
		  startDate: moment().subtract(29, 'days'),
		  endDate: moment()
		},
		function (start, end) {
		  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}
	);
	
	//Date picker
	$('#datepicker').datepicker({
	  autoclose: true
	});
	
	//Date picker
	$('#datepicker1').datepicker({
	  autoclose: true
	});
	
	//iCheck for checkbox and radio inputs
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	  checkboxClass: 'icheckbox_minimal-blue',
	  radioClass: 'iradio_minimal-blue'
	});
	//Red color scheme for iCheck
	$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
	  checkboxClass: 'icheckbox_minimal-red',
	  radioClass: 'iradio_minimal-red'
	});
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	  checkboxClass: 'icheckbox_flat-green',
	  radioClass: 'iradio_flat-green'
	});
	
	//Colorpicker
	$(".my-colorpicker1").colorpicker();
	//color picker with addon
	$(".my-colorpicker2").colorpicker();
	
	//Timepicker
	$(".timepicker").timepicker({
	  showInputs: false
	});
	});
	
	<?php
	if($task == 'add')
	{
		?>
		$(document).ready(function()
		{
			setInterval(function(){getNewCode()}, 1000);
		});
		
		function getNewCode()
		{
			var	PRJCODE		= '<?php echo $dataColl; ?>';
			var isManual	= document.getElementById('isManual').checked;
			
			if(window.XMLHttpRequest)
			{
				//code for IE7+,Firefox,Chrome,Opera,Safari
				xmlhttpTask=new XMLHttpRequest();
			}
			else
			{
				xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpTask.onreadystatechange=function()
			{
				if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
				{
					if(xmlhttpTask.responseText != '')
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = xmlhttpTask.responseText;
					}
					else
					{
						if(isManual == false)
							document.getElementById('<?php echo $dataTarget; ?>').value  = '';
					}
				}
			}
			xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE,true);
			xmlhttpTask.send();
		}
		<?php
	}
	?>
  
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}
	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}
		
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function add_header(strItem) 
	{
		arrItem = strItem.split('|');
		JO_NUM	= arrItem[0];
		JO_CODE	= arrItem[1];
		
		$("#JO_NUM").val(JO_NUM);
        $("#JO_CODE").val(JO_CODE);
        $("#JO_CODE1").val(JO_CODE);
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= '';
		JOBCODEID 		= '';
		JOBCODE 		= '';
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_NAME 		= arrItem[2];
		ITM_SN			= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		ITM_CATEG 		= arrItem[5];
		ITM_TYPE 		= arrItem[6];
		ITM_QTY			= arrItem[7];		// BUDGET VOLM IN C-CALCULATION
		ITM_PRICE 		= arrItem[8];		// BUDGET PRICE  IN C-CALCULATION
		ITM_TOTAL 		= arrItem[9];		// BUDGET AMOUNT  IN C-CALCULATION
		MR_VOLM 		= arrItem[10];		// TOTAL VOLUME OF REQUEST
		MR_AMOUNT 		= arrItem[11];		// TOTAL AMOUNT OF REQUEST
		IRM_VOLM 		= arrItem[12];		// TOTAL VOLUME OF RECEIVE
		IRM_AMOUNT 		= arrItem[13];		// TOTAL AMOUNT OF RECEIVE
		USED_VOLM		= arrItem[14];
		USED_AMOUNT		= arrItem[15];
		itemConvertion	= arrItem[16];
		REMREQ_QTY		= arrItem[17];
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// ITM_CODE, MR_NUM, MR_CODE, SNCODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'MR_NUM" name="data['+intIndex+'][MR_NUM]" value="" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'MR_CODE" name="data['+intIndex+'][MR_CODE]" value="" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CATEG" name="data['+intIndex+'][ITM_CATEG]" value="'+ITM_CATEG+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'SNCODE" name="data['+intIndex+'][SNCODE]" value="'+ITM_SN+'" class="form-control" style="max-width:300px;">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// Item Budget ; ITM_BUDGVOL, ITM_BUDGPRC, ITM_BUDGTOT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_BUDGVOLX'+intIndex+'" id="ITM_BUDGVOLX'+intIndex+'" value="'+ITM_QTY+'" disabled ><input type="hidden" style="text-align:right" name="ITM_BUDGVOL'+intIndex+'" id="ITM_BUDGVOL'+intIndex+'" value="'+ITM_QTY+'" ><input type="hidden" style="text-align:right" name="ITM_BUDGPRC'+intIndex+'" id="ITM_BUDGPRC'+intIndex+'" value="'+ITM_PRICE+'" ><input type="hidden" style="text-align:right" name="ITM_BUDGTOT'+intIndex+'" id="ITM_BUDGTOT'+intIndex+'" value="'+ITM_TOTAL+'" >';
		
		// Item Requested FOR INFORMATION ONLY ; ITM_TOTREQVOL, ITM_TOTREQAMN
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_TOTREQVOLx'+intIndex+'" id="ITM_TOTREQVOLx'+intIndex+'" value="'+MR_VOLM+'" disabled><input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_TOTREQVOL'+intIndex+'" id="ITM_TOTREQVOL'+intIndex+'" value="'+MR_VOLM+'" ><input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_TOTREQAMN'+intIndex+'" id="ITM_TOTREQAMN'+intIndex+'" value="'+MR_AMOUNT+'" >';
		
		// Item Request Now -- MR_VOLM, MR_PRICE, MR_TOTAL, itemConvertion
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="MR_VOLM'+intIndex+'" id="MR_VOLM'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][MR_VOLM]" id="data'+intIndex+'MR_VOLM" value="0.00" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][MR_PRICE]" id="data'+intIndex+'MR_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][MR_TOTAL]" id="data'+intIndex+'MR_TOTAL" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="itemConvertion'+intIndex+'" id="itemConvertion'+intIndex+'" value="'+itemConvertion+'" >';
		
		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// Remarks -- MR_DESC, JOBCODEDET, JOBCODEID, totalrow
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][MR_DESC]" id="data'+intIndex+'MR_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		var decFormat											= document.getElementById('decFormat').value;
		document.getElementById('ITM_BUDGVOLX'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		document.getElementById('ITM_TOTREQVOL'+intIndex).value = parseFloat(Math.abs(MR_VOLM));
		document.getElementById('ITM_TOTREQVOLx'+intIndex).value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(MR_VOLM)),decFormat));
		document.getElementById('totalrow').value 				= intIndex;
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{	
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function validateDouble(vcode, SNCODE) 
	{
		var thechk=new Array();
		var duplicate = false;
		
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function getConvertion(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value));
		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		ITM_BUDGVOL			= parseFloat(document.getElementById('ITM_BUDGVOL'+row).value);			// Budget Qty
		ITM_BUDGPRC			= parseFloat(document.getElementById('ITM_BUDGPRC'+row).value);			// Budget Price
		ITM_BUDGTOT			= parseFloat(document.getElementById('ITM_BUDGTOT'+row).value);			// Budget Total
		ITM_TOTREQVOL		= parseFloat(document.getElementById('ITM_TOTREQVOL'+row).value);		// Requested Total Volume
		ITM_TOTREQAMN		= parseFloat(document.getElementById('ITM_TOTREQAMN'+row).value);		// Requested Total Amount
		
		MR_PRICE			= parseFloat(document.getElementById('data'+row+'MR_PRICE').value);	// Item Price
		
		REQ_NOW				= document.getElementById('MR_VOLM'+row);
		REQ_NOW_QTY1		= eval(REQ_NOW).value.split(",").join("");								// Request Qty - Now
		REQ_NOW_AMOUNT		= parseFloat(REQ_NOW_QTY1) * parseFloat(MR_PRICE);						// Total Requested Amount
		
		// REMAIN
		REM_MR_QTY			= parseFloat(ITM_BUDGVOL) - parseFloat(ITM_TOTREQVOL);
		REM_MR_AMOUNT		= parseFloat(ITM_BUDGTOT) - parseFloat(ITM_TOTREQAMN);
				
		if(REQ_NOW_QTY1 > REM_MR_QTY)
		{
			REM_MR_QTYV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_MR_QTY)),decFormat));
			REM_MR_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_MR_AMOUNT)),decFormat));
			swal('Request Qty is Greater than Budget. Maximum Qty is '+REM_MR_QTYV);
			document.getElementById('data'+row+'MR_VOLM').value = REM_MR_QTY;
			document.getElementById('MR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_MR_QTY)),decFormat));
			return false;
		}
		
		document.getElementById('data'+row+'MR_TOTAL').value 	= REQ_NOW_AMOUNT;
		document.getElementById('data'+row+'MR_VOLM').value 	= REQ_NOW_QTY1;
		document.getElementById('MR_VOLM'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REQ_NOW_QTY1)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		//var isApproved 	= document.getElementById('isApproved').value;
		var isApproved 	= 0;
		
		if(isApproved == 0)
		{
			for(i=1;i<=totrow;i++)
			{
				var MR_VOLM = parseFloat(document.getElementById('MR_VOLM'+i).value);
				if(MR_VOLM == 0)
				{
					swal('Please input qty of requisition.');
					document.getElementById('MR_VOLM'+i).value = '0';
					document.getElementById('MR_VOLM'+i).focus();
					return false;
				}
			}
			/*if(venCode == 0)
			{
				swal('Please select a Vendor.');
				document.getElementById('selVend_Code').focus();
				return false;
			}*/
			if(totrow == 0)
			{
				swal('Please input detail Material Request.');
				return false;		
			}
			else
			{
				document.frm.submit();
			}
		}
		else
		{
			swal('Can not update this document. The document has Confirmed.');
			return false;
		}
	}
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>