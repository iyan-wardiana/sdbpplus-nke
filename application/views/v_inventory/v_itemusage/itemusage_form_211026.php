<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 11 Desember 2017
	* File Name	= itemusage_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

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

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_um_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_um_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
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
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$UM_NUM			= "$DocNumber";
	$UM_CODE		= "$lastPatternNumb"; // MANUAL CODE
	
	$UMCODE			= substr($lastPatternNumb, -4);
	$UMYEAR			= date('y');
	$UMMONTH		= date('m');
	$UM_CODE		= "$Pattern_Code.$UMCODE.$UMYEAR.$UMMONTH"; // MANUAL CODE
	
	$TRXDATEY 		= date('Y');
	$TRXDATEM 		= date('m');
	$TRXDATED 		= date('d');
	//$UM_DATE		= "$TRXDATEM/$TRXDATED/$TRXDATEY";
	$UM_DATE		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$JOBCODE		= '';
	$UM_NOTE		= '';
	$UM_STAT 		= 1;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;	
	$WH_CODE		= 0;
	$JOBCODEID		= '';
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_um_header~$Pattern_Length";
	$dataTarget		= "UM_CODE";

	$JOBCODEIDD		= "";
}
else
{
	$isSetDocNo = 1;	
	$UM_NUM				= $default['UM_NUM'];
	$DocNumber			= $default['UM_NUM'];
	$UM_CODE			= $default['UM_CODE'];
	$UM_DATED			= $default['UM_DATE'];
	$UM_DATE			= date('m/d/Y',strtotime($UM_DATED));
	$PRJCODE			= $default['PRJCODE'];
	$UM_NOTE			= $default['UM_NOTE'];
	$UM_NOTE2			= $default['UM_NOTE2'];
	$UM_STAT			= $default['UM_STAT'];
	$WH_CODE			= $default['WH_CODE'];
	$JOBCODEID			= $default['JOBCODEID'];
	$Patt_Year			= $default['Patt_Year'];
	$Patt_Number		= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
	
	$dataSessSrc = array(
			'selSearchproj_Code' => $PRJCODE,
			'selSearchType' => $this->input->post('selSearchType'),
			'txtSearch' => $this->input->post('txtSearch'));
	$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
	$this->session->set_userdata('dtSessSrc2', $dataSessSrc);

	$JOBD 		= "";
	$sqlJobD	= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
    $resJobD	= $this->db->query($sqlJobD)->result();
    foreach($resJobD as $rowJD) :
	    $JOBD 	= $rowJD->JOBDESC;
	endforeach;
	$JOBCODEIDD	= "$JOBCODEID : $JOBD";
}
$TRXTIME1		= date('ymdhIs');
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
			if($TranslCode == 'UsageCode')$UsageCode = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'BudName')$BudName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;

			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$subTitleH	= "Tambah";
			$subTitleD	= "penggunaan materil";
			$isManual	= "Centang untuk kode manual.";
			$alert1		= "Silahkan pilih anggaran pembiayaan.";
			$alert2		= "Anda belum memilih material yang akan digunakan.";
			$alert3		= "Masukan jumlah material yang digunakan.";
			$alert4		= "Silahkan masukan catatan penggunaan.";
		}
		else
		{
			$subTitleH	= "Add";
			$subTitleD	= "material usage";
			$isManual	= "Check to manual code.";
			$alert1		= "Please select budget.";
			$alert2		= "Please select an item.";
			$alert3		= "Please input material qty.";
			$alert4		= "Please input note of material usgae.";
		}
			
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $PRJCODE;
		
		// START : APPROVE PROCEDURE
			// UM_NUM - PO_TOTCOST
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode1' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode1'
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
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode1'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode1' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode1'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$UM_NUM'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				//$appReady	= $APP_STEP;
				//if($resC_App == 0)
				//echo "APP_STEP = $APP_STEP = $resC_App = $MAX_STEP";
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
				$APPROVE_AMOUNT = 10000000000;
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
		
		$secAddURL	= site_url('c_inventory/c_iu180c16/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_inventory/c_iu180c16/genCode/'; // Generate Code

			$comp_color = $this->session->userdata('comp_color');
	?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="box box-primary">
		    	<div class="box-body chart-responsive">
		        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">		
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
		                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
		                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="hidden" name="PRDate" id="PRDate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		            <input type="Hidden" name="rowCount" id="rowCount" value="0">
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
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $UsageCode; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="UM_NUM1" id="UM_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
		                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="UM_NUM" id="UM_NUM" size="30" value="<?php echo $DocNumber; ?>" />
		                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?></label>
		                    <div class="col-sm-10">
		                    	 <input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="UM_CODE" name="UM_CODE" size="5" value="<?php echo $UM_CODE; ?>" />
		                    	 <input type="text" class="form-control" id="UM_CODEX" name="UM_CODEX" size="5" value="<?php echo $UM_CODE; ?>" disabled />
		                    </div>
		                </div>
		            	<div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?></label>
		                    <div class="col-sm-10">
		                        <label>
		                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
		                        </label>
		                        <label style="font-style:italic">
		                            <?php echo $isManual; ?>
		                        </label>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
		                    <div class="col-sm-10">
		                    	<div class="input-group date">
		                        <div class="input-group-addon">
		                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="UM_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $UM_DATE; ?>" style="width:106px" onChange="getUM_NUM(this.value)"></div>
		                    </div>
		                </div>
		                <script>
							function getUM_NUM(selDate)
							{
								document.getElementById('PRDate').value = selDate;
								document.getElementById('dateClass').click();
							}
			
							$(document).ready(function()
							{
								$(".tombol-date").click(function()
								{
									var add_PR	= "<?php echo $secGenCode; ?>";
									var formAction 	= $('#sendDate')[0].action;
									var data = $('.form-user').serialize();
									$.ajax({
										type: 'POST',
										url: formAction,
										data: data,
										success: function(response)
										{
											var myarr = response.split("~");
											document.getElementById('UM_NUM1').value = myarr[0];
											document.getElementById('UM_CODE').value = myarr[1];
											document.getElementById('UM_CODEX').value = myarr[1];
										}
									});
								});
							});
						</script>
		            	<div class="form-group" style="display:none">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Project; ?></label>
		                    <div class="col-sm-10">
		                    	<select name="PRJCODE" id="PRJCODE" class="form-control" onChange="chooseProject()">
		                          <option value="none"> --- </option>
		                          <?php
								  	$sqlCPRJ	= "tbl_project";
									$countPRJ	= $this->db->count_all($sqlCPRJ);
		                            if($countPRJ > 0)
		                            {
										$sqlPRJ 	= "SELECT PRJCODE, PRJNAME
														FROM tbl_project WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj 
															WHERE Emp_ID = '$DefEmp_ID')
														ORDER BY PRJNAME";
										$resPRJ		= $this->db->query($sqlPRJ)->result();
		                                foreach($resPRJ as $row) :
		                                    $PRJCODE1 	= $row->PRJCODE;
		                                    $PRJNAME 	= $row->PRJNAME;
		                                    ?>
		                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME"; ?></option>
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
		                    <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />                        
		                    </div>

		                </div>

                        <div class="form-group">
                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $BudName ?> </label>
                          	<div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-btn">
										<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
                                    </div>
                                    <input type="hidden" class="form-control" name="UM_REFNO" id="UM_REFNO" value="<?php echo $JOBCODEID; ?>" onClick="selJOB();">
                                    <input type="text" class="form-control" name="UM_REFNO1" id="UM_REFNO1" value="<?php echo $JOBCODEIDD; ?>" onClick="selJOB();">
                                </div>
                            </div>
                        </div>
						<?php
							$theProjCode 	= $PRJCODE;
							$url_selJOB		= site_url('c_inventory/c_iu180c16/pall180c16Jb/?id='.$this->url_encryption_helper->encode_url($theProjCode));
                        ?>
                        <script>
							var url1 = "<?php echo $url_selJOB;?>";
							function selJOB()
							{
                                PR_REFNO 	= '';
                                PRJCODE		= document.getElementById('PRJCODE').value;

								title 		= 'Select Job';
								w = 1000;
								h = 550;
								//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
								var left = (screen.width/2)-(w/2);
								var top = (screen.height/2)-(h/2);
								return window.open(url1+PR_REFNO+'&pr1h0ec0JcoDe='+PRJCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
							}
						</script>

		            	<div class="form-group" style="display: none;">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $BudName; ?></label>
		                    <div class="col-sm-10">
		                        <select name="UM_REFNO[]" id="UM_REFNO" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $BudName; ?>">
		                        <option value="0"> --- </option>
									<?php
		                                /*$sqlJob_1	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBLEV = 1 AND PRJCODE = '$PRJCODE'";
		                                $resJob_1	= $this->db->query($sqlJob_1)->result();
		                                foreach($resJob_1 as $row_1) :
		                                    $JOBCODEID_1	= $row_1->JOBCODEID;
		                                    $JOBDESC_1		= $row_1->JOBDESC;
		                                    $space_level_1	= "";
		                                    
		                                	$Disabled_1	= 0;
		                                    $sqlC_2		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                    $resC_2 	= $this->db->count_all($sqlC_2);
		                                    if($resC_2 > 0)
		                                        $Disabled_1 = 1;
		                                    ?>
		                                    <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($JOBCODEID_1 == $JOBCODEID) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?>>
		                                        <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
		                                    </option>
		                                    <?php
		                                    if($resC_2 > 0)
		                                    {
		                                        $sqlJob_2	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                        $resJob_2	= $this->db->query($sqlJob_2)->result();
		                                        foreach($resJob_2 as $row_2) :
		                                            $JOBCODEID_2	= $row_2->JOBCODEID;
		                                            $JOBDESC_2		= $row_2->JOBDESC;
		                                            $space_level_2	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                            
		                                            $sqlC_3		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                            $resC_3 	= $this->db->count_all($sqlC_3);
		                                            if($resC_3 > 0)
		                                                $Disabled_2 = 1;
		                                            else
		                                                $Disabled_2 = 0;
		                                            ?>
		                                            <option value="<?php echo "$JOBCODEID_2"; ?>" <?php if($JOBCODEID_2 == $JOBCODEID) { ?> selected <?php } if($Disabled_2 == 1) {?> disabled <?php } ?>>
		                                                <?php echo "$space_level_2 $JOBCODEID_2 : $JOBDESC_2"; ?>
		                                            </option>
		                                            <?php
		                                            if($resC_3 > 0)
		                                            {
		                                                $sqlJob_3	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_2' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                $resJob_3	= $this->db->query($sqlJob_3)->result();
		                                                foreach($resJob_3 as $row_3) :
		                                                    $JOBCODEID_3	= $row_3->JOBCODEID;
		                                                    $JOBDESC_3		= $row_3->JOBDESC;
		                                                    $space_level_3	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                    
		                                                    $sqlC_4		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                    $resC_4 	= $this->db->count_all($sqlC_4);
		                                                    if($resC_4 > 0)
		                                                        $Disabled_3 = 1;
		                                                    else
		                                                        $Disabled_3 = 0;
		                                                    ?>
		                                                    <option value="<?php echo "$JOBCODEID_3"; ?>" <?php if($JOBCODEID_3 == $JOBCODEID) { ?> selected <?php } if($Disabled_3 == 1) {?> disabled <?php } ?>>
		                                                        <?php echo "$space_level_3 $JOBCODEID_3 : $JOBDESC_3"; ?>
		                                                    </option>
		                                                    <?php
		                                                    if($resC_4 > 0)
		                                                    {
		                                                        $Disabled_4	= 0;
		                                                        $sqlJob_4	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_3' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                        $resJob_4	= $this->db->query($sqlJob_4)->result();
		                                                        foreach($resJob_4 as $row_4) :
		                                                            $JOBCODEID_4	= $row_4->JOBCODEID;
		                                                            $JOBDESC_4		= $row_4->JOBDESC;
		                                                            $space_level_4	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                            
		                                                            $sqlC_5		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                            $resC_5 	= $this->db->count_all($sqlC_5);
		                                                            if($resC_5 > 0)
		                                                                $Disabled_4 = 1;
		                                                            else
		                                                                $Disabled_4 = 0;
		                                                            ?>
		                                                            <option value="<?php echo "$JOBCODEID_4"; ?>" <?php if($JOBCODEID_4 == $JOBCODEID) { ?> selected <?php } if($Disabled_4 == 1) {?> disabled <?php } ?>>
		                                                                <?php echo "$space_level_4 $JOBCODEID_4 : $JOBDESC_4"; ?>
		                                                            </option>
		                                                            <?php
		                                                            if($resC_5 > 0)
		                                                            {
		                                                                $Disabled_5	= 0;
		                                                                $sqlJob_5	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_4' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                $resJob_5	= $this->db->query($sqlJob_5)->result();
		                                                                foreach($resJob_5 as $row_5) :
		                                                                    $JOBCODEID_5	= $row_5->JOBCODEID;
		                                                                    $JOBDESC_5		= $row_5->JOBDESC;
		                                                                    $space_level_5	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                    
		                                                                    $sqlC_6		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                    $resC_6 	= $this->db->count_all($sqlC_6);
		                                                                    if($resC_6 > 0)
		                                                                        $Disabled_5 = 1;
		                                                                    else
		                                                                        $Disabled_5 = 0;
		                                                                    ?>
		                                                                    <option value="<?php echo "$JOBCODEID_5"; ?>" <?php if($JOBCODEID_5 == $JOBCODEID) { ?> selected <?php } if($Disabled_5 == 1) {?> disabled <?php } ?>>
		                                                                        <?php echo "$space_level_5 $JOBCODEID_5 : $JOBDESC_5"; ?>
		                                                                    </option>
		                                                                    <?php
		                                                                    if($resC_6 > 0)
		                                                                    {
		                                                                        $Disabled_6	= 0;
		                                                                        $sqlJob_6	= "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE JOBPARENT = '$JOBCODEID_5' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                        $resJob_6	= $this->db->query($sqlJob_6)->result();
		                                                                        foreach($resJob_6 as $row_6) :
		                                                                            $JOBCODEID_6	= $row_6->JOBCODEID;
		                                                                            $JOBDESC_6		= $row_6->JOBDESC;
		                                                                            $space_level_6	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                                                                            
		                                                                            $sqlC_7		= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_6' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                                                            $resC_7 	= $this->db->count_all($sqlC_7);
		                                                                            if($resC_7 > 0)
		                                                                                $Disabled_6 = 1;
		                                                                            else
		                                                                                $Disabled_6 = 0;
		                                                                            ?>
		                                                                            <option value="<?php echo "$JOBCODEID_6"; ?>" <?php if($JOBCODEID_6 == $JOBCODEID) { ?> selected <?php } if($Disabled_6 == 1) {?> disabled <?php } ?>>
		                                                                                <?php echo "$space_level_6 $JOBCODEID_6 : $JOBDESC_6"; ?>
		                                                                            </option>
		                                                                            <?php
		                                                                        endforeach;
		                                                                    }
		                                                                endforeach;
		                                                            }
		                                                        endforeach;
		                                                    }
		                                                endforeach;
		                                            }
		                                        endforeach;
		                                    }
		                                endforeach;*/
		                            ?>
		                        </select>
		                    </div>
		                </div>
		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
		                    <div class="col-sm-10">
		                    	<textarea name="UM_NOTE" class="form-control" id="UM_NOTE" cols="30"><?php echo $UM_NOTE; ?></textarea>                        
		                    </div>
		                </div>

		            	<div class="form-group">
		                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status; ?></label>
		                    <div class="col-sm-10">
		                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $UM_STAT; ?>">
								<?php
		                            if($ISCREATE == 1 && ($UM_STAT == 1 || $UM_STAT == 2))
		                            {
										?>
											<select name="UM_STAT" id="UM_STAT" class="form-control select2" onChange="selStat(this.value)">
												<option value="1"<?php if($UM_STAT == 1) { ?> selected <?php } ?>>New</option>
												<option value="2"<?php if($UM_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
		                                        <option value="3"<?php if($UM_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
		                                        <option value="4"<?php if($UM_STAT == 4) { ?> selected <?php } ?> disabled>Revise</option>
		                                        <option value="5"<?php if($UM_STAT == 5) { ?> selected <?php } ?> disabled>Reject</option>
		                                        <option value="6"<?php if($UM_STAT == 6) { ?> selected <?php } ?>>Close</option>
		                                        <option value="7"<?php if($UM_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
		                                        <option value="9"<?php if($UM_STAT == 7) { ?> selected <?php } ?> >Void</option>
											</select>
										<?php
		                            }
		                            elseif($ISCREATE == 1 && ($UM_STAT != 1 && $UM_STAT != 2))
		                            {
										?>
											<select name="UM_STAT" id="UM_STAT" class="form-control select2" onChange="selStat(this.value)">
												<!-- <option value="1"<?php if($UM_STAT == 1) { ?> selected <?php } ?>>New</option>
												<option value="2"<?php if($UM_STAT == 2) { ?> selected <?php } ?>>Confirm</option> -->
		                                        <option value="3"<?php if($UM_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
		                                        <option value="4"<?php if($UM_STAT == 4) { ?> selected <?php } ?> disabled>Revise</option>
		                                        <option value="5"<?php if($UM_STAT == 5) { ?> selected <?php } ?> disabled>Reject</option>
		                                        <option value="6"<?php if($UM_STAT == 6) { ?> selected <?php } ?>>Close</option>
		                                        <option value="7"<?php if($UM_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
		                                        <option value="9"<?php if($UM_STAT == 7) { ?> selected <?php } ?> >Void</option>
											</select>
										<?php
		                            }
									$theProjCode 	= $PRJCODE;
		                        	$url_AddItem	= site_url('c_inventory/c_iu180c16/pall180c16itm/?id='.$this->url_encryption_helper->encode_url($theProjCode));
		                        ?>
		                        <input type="hidden" name="WH_CODE" id="WH_CODE" value="<?php echo $WH_CODE; ?>">
		                    </div>
		                </div>
		                <script>
							function selStat(thisVal)
							{
								var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
								if(STAT_BEFORE == 3)
								{
									if(thisVal == 9)
										document.getElementById('btnVoid').style.display = '';
									else
										document.getElementById('btnVoid').style.display = 'none';
								}
							}
						</script>

						<?php
							if($UM_STAT == 1 || $UM_STAT == 4)
								$isEdit	= 1;
							else
								$isEdit	= 0;
						?>

		                <div class="form-group" <?php if($UM_STAT != 1 && $UM_STAT != 4) { ?> style="display: none;" <?php } ?>>
		                    <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
		                    <div class="col-sm-10">
		                        <script>
		                            var url = "<?php echo $url_AddItem;?>";
		                            function selectitem()
		                            {
		                                title = 'Select Item';
		                                w = 1000;
		                                h = 550;
		                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                var left = (screen.width/2)-(w/2);
		                                var top = (screen.height/2)-(h/2);
		                                return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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
		                        <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
		                              	<th width="3%" height="25" rowspan="2" style="text-align:left; vertical-align: middle;">&nbsp;</th>
		                              	<th width="3%" rowspan="2" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemCode ?> </th>
		                              	<th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
		                              	<th colspan="2" style="text-align:center"><?php echo $ItemQty; ?> </th>
		                              	<th rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
		                              	<th width="24%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                              <th style="text-align:center;"><?php echo $Stock ?> </th>
		                              <th style="text-align:center"><?php echo $Used ?></th>
		                            </tr>
		                            <?php					
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.UM_NUM, A.UM_CODE, A.ACC_ID, A.PRJCODE, A.JOBCODEDET, A.JOBCODEID, 
														A.ITM_CODE, A.ITM_UNIT, A.ITM_STOCK, A.ITM_QTY, A.ITM_PRICE, A.UM_DESC,
														B.ITM_NAME, B.ITM_REMQTY, B.ITM_GROUP,
														B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, B.ISFASTM, B.ISWAGE
													FROM tbl_um_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE UM_NUM = '$UM_NUM' 
														AND B.PRJCODE = '$PRJCODE'";
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
										
										foreach($result as $row) :
											$currentRow  	= ++$i;
											$UM_NUM 		= $row->UM_NUM;
											$UM_CODE 		= $row->UM_CODE;
											$PRJCODE 		= $row->PRJCODE;
											$JOBCODEDET		= $row->JOBCODEDET;
											$JOBCODEID 		= $row->JOBCODEID;
											$ITM_CODE 		= $row->ITM_CODE;
											$ACC_ID			= $row->ACC_ID;
											$ITM_NAME 		= $row->ITM_NAME;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITM_GROUP 		= $row->ITM_GROUP;
											$ITM_STOCK1		= $row->ITM_STOCK;
									
											// GET RESERVE ITEM
												$ITMRSV		= 0;
												$sqlITMRSV	= "SELECT SUM(A.ITM_QTY) AS ITMRESERVE FROM tbl_um_detail A
																INNER JOIN tbl_um_header B ON A.UM_NUM = B.UM_NUM
																WHERE A.PRJCODE = '$PRJCODE' 
																	AND A.ITM_CODE = '$ITM_CODE' 
																	AND B.UM_STAT IN (2,7)
																	AND A.UM_NUM != '$UM_NUM'";
												$resITMRSV	= $this->db->query($sqlITMRSV)->result();
												foreach($resITMRSV as $rowITMRSV) :
													$ITMRSV	= $rowITMRSV->ITMRESERVE;
													if($ITMRSV == '')
														$ITMRSV	= 0;
												endforeach;
												
											$ITM_STOCK 		= $ITM_STOCK1 - $ITMRSV;
											$ITM_QTY 		= $row->ITM_QTY;
											$ITM_PRICE 		= $row->ITM_PRICE;
											$UM_DESC 		= $row->UM_DESC;
											$ITM_NAME		= $row->ITM_NAME;
											$ISMTRL 		= $row->ISMTRL;
											$ISRENT 		= $row->ISRENT;
											$ISPART 		= $row->ISPART;
											$ISFUEL 		= $row->ISFUEL;
											$ISLUBRIC 		= $row->ISLUBRIC;
											$ISFASTM 		= $row->ISFASTM;
											$ISWAGE 		= $row->ISWAGE;
											if($ISMTRL == 1)
												$ITM_TYPE	= 1;
											elseif($ISRENT == 1)
												$ITM_TYPE	= 2;
											elseif($ISPART == 1)
												$ITM_TYPE	= 3;
											elseif($ISFUEL == 1)
												$ITM_TYPE	= 4;
											elseif($ISLUBRIC == 1)
												$ITM_TYPE	= 5;
											elseif($ISFASTM == 1)
												$ITM_TYPE	= 6;
											else
												$ITM_TYPE	= 1;
												
											$itemConvertion	= 1;
								
											if ($j==1) {
												echo "<tr class=zebra1>";
												$j++;
											} else {
												echo "<tr class=zebra2>";
												$j--;
											}
											?> 
		                                    <tr id="tr_<?php echo $currentRow; ?>">
											<td width="3%" height="25" style="text-align:left">
											  <?php
												if($UM_STAT == 1)
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
										 	<td width="3%" style="text-align:left" nowrap> <!-- Item Code -->
												<?php echo $ITM_CODE; ?>
												<input type="hidden" id="data<?php echo $currentRow; ?>UM_NUM" name="data[<?php echo $currentRow; ?>][UM_NUM]" value="<?php echo $UM_NUM; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" id="data<?php echo $currentRow; ?>UM_CODE" name="data[<?php echo $currentRow; ?>][UM_CODE]" value="<?php echo $UM_CODE; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
												<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" class="form-control" style="max-width:300px;">
		                                  	</td>
										  	<td width="33%" style="text-align:left">
												<?php echo $ITM_NAME; ?> <!-- Item Name -->
											</td>
											<td width="11%" style="text-align:right"> <!-- Item Stock -->
												<?php print number_format($ITM_STOCK, $decFormat); ?>
												<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_STOCK<?php echo $currentRow; ?>" id="ITM_STOCK<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_STOCK, 2); ?>" disabled >
												<input type="hidden" id="data<?php echo $currentRow; ?>ITM_STOCK" name="data[<?php echo $currentRow; ?>][ITM_STOCK]" value="<?php echo $ITM_STOCK; ?>">
		                                  	</td>
										 	<td width="11%" style="text-align:right"> <!-- Item QTY Use -->
												<?php if($isEdit == 1) { ?>
													<input type="text" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_QTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
												<?php } else { print number_format($ITM_QTY, $decFormat); ?>
													<input type="hidden" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_QTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
												<?php } ?>
												<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>" class="form-control" style="max-width:300px;" >
												<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:300px;" >
		                                 	</td>
										  	<td width="4%" style="text-align:center" nowrap>
											  <?php echo $ITM_UNIT; ?>
		                                    	<input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
		                                        <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
		                                         <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
		                                    <!-- Item Unit Type -- ITM_UNIT --></td>
										  	<td width="24%">
												<?php if($isEdit == 1) { ?>
													<input type="text" name="data[<?php echo $currentRow; ?>][UM_DESC]" id="data<?php echo $currentRow; ?>UM_DESC" size="20" value="<?php echo $UM_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
												<?php } else { print $UM_DESC; ?>
													<input type="hidden" name="data[<?php echo $currentRow; ?>][UM_DESC]" id="data<?php echo $currentRow; ?>UM_DESC" size="20" value="<?php echo $UM_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
												<?php } ?>
								        		
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
		                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
		                                        <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>"></td>
									  </tr>
		                              	<?php
		                             	endforeach;
		                            }
		                            if($task == 'add')
		                            {
		                                ?>
		                                  <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                              <?php
		                            }
		                            ?>
		                        </table>
		                      </div>
		                    </div>
		                </div>
		                <br>
		                <div class="form-group">
		                    <div class="col-sm-offset-2 col-sm-10">
		                        <?php
									if($task=='add')
									{
										if($UM_STAT == 1 && $ISCREATE == 1)
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
										if($UM_STAT == 1 && $ISCREATE == 1)
										{
											?>
												<button class="btn btn-primary" >
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
										elseif($UM_STAT == 3)
										{
											?>
												<button class="btn btn-primary" id="btnVoid" style="display: none;">
												<i class="fa fa-save"></i>
												</button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_inventory/c_iu180c16/gum180c16/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
					</form>
			        <div class="col-md-12">
						<?php
                            $DOC_NUM	= $UM_NUM;
                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
							$sqlAPP		= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
											AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
							$resAPP		= $this->db->query($sqlAPP)->result();
							foreach($resAPP as $rowAPP) :
								$MAX_STEP		= $rowAPP->MAX_STEP;
								$APPROVER_1		= $rowAPP->APPROVER_1;
								$APPROVER_2		= $rowAPP->APPROVER_2;
								$APPROVER_3		= $rowAPP->APPROVER_3;
								$APPROVER_4		= $rowAPP->APPROVER_4;
								$APPROVER_5		= $rowAPP->APPROVER_5;
							endforeach;
							
                        	if($resCAPP == 0)
                        	{
                        		if($LangID == 'IND')
								{
									$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini.";
								}
								else
								{
									$zerSetApp	= "There are no arrangements for the approval of this document.";
								}
                        		?>
                        			<div class="alert alert-warning alert-dismissible">
					                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                	<?php echo $zerSetApp; ?>
					              	</div>
                        		<?php
                        	}
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
			        </div>
		            <?php
		                $DefID      = $this->session->userdata['Emp_ID'];
		                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		                if($DefID == 'D15040004221')
		                    echo "<font size='1'><i>$act_lnk</i></font>";
		            ?>
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
		arrItem 	= strItem.split('|');
		
		JOBCODEID 	= arrItem[0];
		PRJCODE 	= arrItem[1];
		JOBDESC 	= arrItem[2];

		document.getElementById('UM_REFNO').value = JOBCODEID;
		document.getElementById('UM_REFNO1').value = JOBCODEID+' : '+JOBDESC;
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var UM_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var UM_CODEx 	= "<?php echo $UM_CODE; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
		
		JOBCODEDET 		= arrItem[0];
		JOBCODEID 		= arrItem[1];
		JOBCODE 		= arrItem[2];
		PRJCODE 		= arrItem[3];
		ITM_CODE 		= arrItem[4];
		ITM_NAME 		= arrItem[5];
		ITM_UNIT 		= arrItem[6];
		ITM_PRICE 		= arrItem[7];
		ITM_VOLM 		= arrItem[8];
		ITM_STOCK 		= arrItem[9];
		ACC_ID 			= arrItem[10];
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
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'UM_NUM" name="data['+intIndex+'][UM_NUM]" value="'+UM_NUMx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'UM_CODE" name="data['+intIndex+'][UM_CODE]" value="'+UM_CODEx+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+ACC_ID+'" class="form-control" style="max-width:300px;">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// Item Stock
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="ITM_STOCK'+intIndex+'" id="ITM_STOCK'+intIndex+'" value="'+ITM_STOCK+'" disabled ><input type="hidden" id="data'+intIndex+'ITM_STOCK" name="data['+intIndex+'][ITM_STOCK]" value="'+ITM_STOCK+'">';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" value="0.00" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" value="'+ITM_PRICE+'" class="form-control" style="max-width:300px;" >';
		
		// Item Unit Type -- ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// Remarks -- UM_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][UM_DESC]" id="data'+intIndex+'UM_DESC" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][JOBCODEDET]" id="data'+intIndex+'JOBCODEDET" value="'+JOBCODEDET+'" class="form-control" ><input type="hidden" name="data['+intIndex+'][JOBCODEID]" id="data'+intIndex+'JOBCODEID" value="'+JOBCODEID+'" class="form-control" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var PPMat_Budget											= document.getElementById('ITM_STOCK'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_STOCK').value 	= parseFloat(Math.abs(PPMat_Budget));
		document.getElementById('ITM_STOCK'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPMat_Budget)),decFormat));
		document.getElementById('totalrow').value = intIndex;
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
		//itemConvertion		= document.getElementById('itemConvertion'+row).value;	
		itemConvertion		= 1;	
		document.getElementById('data'+row+'ITM_QTY').value = thisVal;
		ITM_QTY				= parseFloat(thisVal);													// Item Qty
		document.getElementById('ITM_QTY'+row).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		ITM_PRICE			= parseFloat(document.getElementById('data'+row+'ITM_QTY').value);		// Item Price
		ITM_STOCK			= parseFloat(document.getElementById('data'+row+'ITM_STOCK').value);	// Stock Qty
		TOT_UM_AM			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);							// Total UM Amount
		TOT_BU_AM			= parseFloat(ITM_STOCK) * parseFloat(ITM_PRICE);						// Total Budget Amount
		
		// REMAIN
		REM_UM_QTY			= parseFloat(ITM_STOCK) - parseFloat(ITM_QTY);
		REM_UM_AMOUNT		= parseFloat(TOT_BU_AM) - parseFloat(TOT_UM_AM);
				
		if(ITM_QTY > ITM_STOCK)
		{
			swal('Your Qty has input is Greater than Stock Qty. Maximum Qty is '+ITM_STOCK,
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('data'+row+'ITM_QTY').value = ITM_STOCK;
				document.getElementById('ITM_QTY'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_STOCK)),decFormat));
			});
			return false;
		}
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
		var UM_REFNO	= document.getElementById('UM_REFNO').value;
		var UM_NOTE		= document.getElementById('UM_NOTE').value;
		
		if(UM_REFNO == '0')
		{
			swal('<?php echo $alert1; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('UM_REFNO').focus();
			});
			return false;		
		}
		
		if(UM_NOTE == '')
		{
			swal('<?php echo $alert4; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('UM_NOTE').focus();
			});
			return false;		
		}
		
		if(totrow == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon:"warning",
			})
			.then(function()
			{
				document.getElementById('UM_REFNO').focus();
			});
			return false;		
		}
		
		for(i=1;i<=totrow;i++)
		{
			var ITM_QTY = parseFloat(document.getElementById('ITM_QTY'+i).value);
			if(ITM_QTY == 0)
			{
				swal('<?php echo $alert3; ?>',
				{
					icon:"warning",
				})
				.then(function()
				{
					document.getElementById('ITM_QTY'+i).value = '0';
					document.getElementById('ITM_QTY'+i).focus();
				});
				return false;
			}
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