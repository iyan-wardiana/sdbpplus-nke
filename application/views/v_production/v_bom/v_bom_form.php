<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Oktober 2018
	* File Name	= v_bom_form.php
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
$decFormat	= 2;
$decForm2	= 2;
$decForm3	= 3;
$decForm4	= 4;

$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$BOM_NUM		= '';
	$BOM_CODE 		= '';
	$BOM_NAME		= '';
	$PRJNAME 		= '';
	$BOM_FG			= '';
	$CUST_CODE 		= '';
	$CUST_DESC 		= '';
	$BOM_DESC 		= '';
	$BOM_STAT 		= 1;
	
	foreach($viewDocPattern as $row) :
		$Pattern_Code 			= $row->Pattern_Code;
		$Pattern_Position 		= $row->Pattern_Position;
		$Pattern_YearAktive 	= $row->Pattern_YearAktive;
		$Pattern_MonthAktive 	= $row->Pattern_MonthAktive;
		$Pattern_DateAktive 	= $row->Pattern_DateAktive;
		$Pattern_Length 		= $row->Pattern_Length;
		$useYear 				= $row->useYear;
		$useMonth 				= $row->useMonth;
		$useDate 				= $row->useDate;
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

	$this->db->where('PRJCODE', $PRJCODE);
	$myCount = $this->db->count_all('tbl_bom_header');
	
	$myMax 	= $myCount+1;
	
	$sql 		= "tbl_bom_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->count_all($sql);
	$myMax 		= $result+1;
			
	$thisMonth 	= $month;
	
	$lenMonth 		= strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth 		= $nolMonth.$thisMonth;
	
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
		
	$lastPatternNumb 	= $myMax;
	$lastPatternNumb1 	= $myMax;
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
	
	$PATTCODE1		= $lastPatternNumb;
	$PATTCODE2		= date('y');
	$PATTCODE3		= date('m');
	$PATTDATET 		= date('ymdHis');
	$BOM_NUM		= "$Pattern_Code.$PATTDATET"; 						// MANUAL CODE
	$DocNumber		= $BOM_NUM;
	$BOM_CODE		= "$Pattern_Code.$PATTCODE1.$PATTCODE2.$PATTCODE3"; // MANUAL CODE
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_bom_header~$Pattern_Length";
	$dataTarget		= "BOM_CODE";
	
	$SO_NUM			= '';
	$BOM_UC 		= '';

	$BOM_FGNM 		= '';
}
else
{
	$isSetDocNo = 1;
	$BOM_NUM 		= $default['BOM_NUM'];
	$BOM_NUM		= $BOM_NUM;
	$DocNumber		= $BOM_NUM;
	$BOM_CODE 		= $default['BOM_CODE'];
	$BOM_NAME 		= $default['BOM_NAME'];
	$PRJCODE 		= $default['PRJCODE'];
	$BOM_FG 		= $default['BOM_FG'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$BOM_DESC 		= $default['BOM_DESC'];
	$BOM_STAT 		= $default['BOM_STAT'];
	$BOM_UC			= $default['BOM_UC'];
	$BOM_CREATER	= $default['BOM_CREATER'];
	$BOM_CREATED	= $default['BOM_CREATED'];
	$Patt_Number	= $default['Patt_Number'];
	
	$SO_NUM			= '';

	$BOM_FGNM 		= "";
	$sqlFGNM 		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$BOM_FG' AND PRJCODE = '$PRJCODE'";
	$resFGNM 		= $this->db->query($sqlFGNM)->result();
	foreach($resFGNM as $rowFGNM) :
		$BOM_FGNM 	= $rowFGNM->ITM_NAME;
	endforeach;
}

$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();

foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;

$isDis	= 1;
if($BOM_STAT == 1 || $BOM_STAT == 4 || $BOM_STAT == 7)
{
	$isDis		= 0;
}
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
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
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
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'ColorName')$ColorName = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Material')$Material = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'FinGoods')$FinGoods = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Selected')$Selected = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
			if($TranslCode == 'Customer')$Customer = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'OrdeList')$OrdeList = $LangTransl;
			if($TranslCode == 'ItemListOrd')$ItemListOrd = $LangTransl;
			if($TranslCode == 'ShowDetail')$ShowDetail = $LangTransl;
			if($TranslCode == 'output')$output = $LangTransl;
			if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
			if($TranslCode == 'DocStatus')$DocStatus = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1			= 'Jumlah pemesanan tidak boleh kosong';
			$alert2			= 'Nama Resep tidak boleh kosong.';
			$alert3			= 'Tentukan barang jadi yang akan diproduksi.';
			$stepalert3		= "Tentukan tahapan-tahapan beserta bahan-bahan yang akan digunakan selama proses produksi.";
			$isManual		= "Centang untuk kode manual.";
			$alert6			= "Tahapan yang dipilih tidak digunakan.";
			$alert7			= "Tahapan ke ";
			$alert8			= "sudah ditempati oleh tahapan ";
		}
		else
		{
			$alert1			= 'Qty order can not be empty';
			$alert2			= 'Formula name can not be empty.';
			$alert3			= 'Determine the Finish Goods.';
			$stepalert3		= "Determine the stages and Raw Material of the production process to be used.";
			$isManual		= "Check to manual code.";
			$alert6			= "Step you selected is not active.";
			$alert7			= "Step ";
			$alert8			= "is already set to step ";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - IR_AMOUNT
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
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
				$APPROVE_AMOUNT 	= 0;
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
			<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bom.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
		    <?php echo $mnName; ?>
		    <small><?php echo $PRJNAME1; ?></small>
		  	</h1>
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
			
			.inplabel {border:none;background-color:white;}
			.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
			.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
			.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
			.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
			.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
			.inpdim {border:none;background-color:white;}
			
			input[type="checkbox"] {
			  -webkit-appearance: none;
			  -moz-appearance: none;
			  appearance: none;
			
			  /* Styling checkbox */
			  width: 16px;
			  height: 16px;
			  background-color: red;
			  cursor:pointer;
			}
			
			input[type="checkbox"]:checked {
			  background-color: green;
			  cursor:pointer;
			}
		</style>

		<section class="content">	
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
		                <div class="box-header with-border" style="display:none">               
		              		<div class="box-tools pull-right">
		                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		                    </div>
		                </div>
		                <div class="box-body chart-responsive">
		                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
		                        <input type="hidden" name="BOM_UC" id="BOM_UC" value="<?php echo $BOM_UC; ?>">
		                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		            			<input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
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
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
		                          	<div class="col-sm-10">
		                            	<input type="hidden" class="form-control" style="max-width:195px" name="BOM_NUM1" id="BOM_NUM1" value="<?php echo $BOM_NUM; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="BOM_NUM" id="BOM_NUM" size="30" value="<?php echo $BOM_NUM; ?>" />
		                                <label>
		                                    <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="BOM_CODE" id="BOM_CODE" value="<?php echo $BOM_CODE; ?>" >
		                                </label>
		                                <label>
		                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
		                                </label>
		                                <label style="font-style:italic">
		                                    <?php echo $isManual; ?>
		                                </label>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-10">
		                            	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
		                          <option value="none">--- None ---</option>
		                          <?php echo $i = 0;
		                            if($countPRJ > 0)
		                            {
		                                foreach($vwPRJ as $row) :
		                                    $PRJCODE1 	= $row->PRJCODE;
		                                    $PRJNAME 	= $row->PRJNAME;
		                                    ?>
		                                  <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
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
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ColorName; ?> </label>
		                          	<div class="col-sm-10">
		                                <input type="text" class="form-control" id="BOM_NAME" name="BOM_NAME" value="<?php echo $BOM_NAME; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $FinGoods ?> </label>
		                          	<div class="col-sm-4">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="BOM_FG" id="BOM_FG" style="max-width:160px" value="<?php echo $BOM_FG; ?>" >
		                                    <input type="text" class="form-control" name="BOM_FG1" id="BOM_FG1" value="<?php echo $BOM_FG; ?>" onClick="pleaseCheck();" <?php if($BOM_STAT != 1 && $BOM_STAT != 4) { ?> disabled <?php } ?>>
		                                </div>
		                            </div>
		                          	<div class="col-sm-2">
		                          		<label for="inputName" class="control-label pull-right"><?php echo $ItemName ?> </label>
		                          	</div>
		                          	<div class="col-sm-4">
		                                <input type="text" class="form-control" name="BOM_FGNM" id="BOM_FGNM" value="<?php echo $BOM_FGNM; ?>" disabled>
		                            </div>
		                        </div>
								<?php
									$selFG	= site_url('c_production/c_b0fm47/s3l4llF9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $selFG;?>";
									function pleaseCheck()
									{
										BOM_NAME	= $("#BOM_NAME").val();
										if(BOM_NAME == '')
										{
											swal('<?php echo $alert2; ?>',
											{
												icon:"warning"
											})
								            .then(function()
								            {
								                swal.close();
								                $("#BOM_NAME").focus();
								            });
											return false;
										}
										
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
		                        <?php
		                        	$custType	= 1;
		                        	if($task == 'edit')
		                        	{
										if($CUST_CODE == '')
											$custType	= 0;
										else
											$custType	= 1;
									}
								?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Customer ?> </label>
		                          	<div class="col-sm-10">
		                            	<label>
		                                    <input type="radio" name="custType" value="1" class="flat-red" <?php if($custType == 1) { ?> checked <?php } ?>>
		                                    <?php echo $Selected ?>&nbsp;&nbsp;&nbsp;
		                                    <input type="radio" name="custType" value="0" class="flat-red" <?php if($custType == 0) { ?> checked <?php } ?>>
		                                    <?php echo $All ?>
		                                </label>
		                          	</div>
		                        </div>
		                        <script>
									$(document).ready(function () {							
										$('input[name="custType"]').on('ifClicked', function (event) 
										{
											if(this.value == 1)
												document.getElementById("CUST_CODE").disabled = false;
											else
												document.getElementById("CUST_CODE").disabled = true;
										});
									});
								</script>

		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-2 control-label"><?php echo $CustName ?> </label>
		                            <div class="col-sm-10">
		                                <select name="CUST_CODE[]" id="CUST_CODE" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;<?php echo $CustName; ?>">
		                                    <option value="">--- None ---</option>
		                                    <?php
		                                        $Disabled_1	= 0;
		                                        $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
		                                        $resJob_1	= $this->db->query($sqlJob_1)->result();
		                                        foreach($vwCUST as $row_1) :
		                                            $CUST_CODE1	= $row_1->CUST_CODE;
		                                            $CUST_DESC1	= $row_1->CUST_DESC;
		                                            
		                                            $JIDExplode = explode('~', $CUST_CODE);
		                                            $CUSTCODE1	= '';
		                                            $SELECTED	= 0;
		                                            foreach($JIDExplode as $i => $key)
		                                            {
		                                                $CUSTCODE1	= $key;
		                                                if($CUST_CODE1 == $CUSTCODE1)
		                                                {
		                                                    $SELECTED	= 1;
		                                                }
		                                            }
		                                            ?>
		                                            <option value="<?php echo $CUST_CODE1; ?>" <?php if($SELECTED == 1) { ?> selected <?php } ?> title="<?php echo $CustName; ?>">
		                                                <?php echo "$CUST_CODE1 : $CUST_DESC1"; ?>
		                                            </option>
		                                            <?php
		                                        endforeach;
		                                    ?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
		                          	<div class="col-sm-10">
		                                <textarea class="form-control" name="BOM_DESC"  id="BOM_DESC" style="height:70px"><?php echo $BOM_DESC; ?></textarea>
		                          	</div>
		                        </div>
		                        <!--
		                        	APPROVE STATUS
		                            1 - New
		                            2 - Confirm
		                            3 - Approve
		                        -->
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-10">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $BOM_STAT; ?>">
										<?php
										// START : FOR ALL APPROVAL FUNCTION
											$disButton	= 0;
											$disButtonE	= 0;
											if($task == 'add')
											{
												if($ISCREATE == 1 || $ISAPPROVE == 1)
												{
													?>
														<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" >
															<option value="1">New</option>
															<option value="2">Confirm</option>
														</select>
													<?php
												}
											}
											else
											{
												$disButton	= 1;
												if(($ISCREATE == 1 && $ISAPPROVE == 1))
												{
													if($BOM_STAT == 1 || $BOM_STAT == 4)
													{
														$disButton	= 0;
														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" >
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
													elseif($BOM_STAT == 2 || $BOM_STAT == 7)
													{
														$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;
														
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$BOM_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
													
														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($BOM_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																<option value="2"<?php if($BOM_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																<option value="3"<?php if($BOM_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																<option value="4"<?php if($BOM_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																<option value="5"<?php if($BOM_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																<option value="6"<?php if($BOM_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																<option value="7"<?php if($BOM_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																<?php if($BOM_STAT == 3 || $BOM_STAT == 9) { ?>
																<option value="9"<?php if($BOM_STAT == 9) { ?> selected <?php } ?>>Void</option>
																<?php } ?>
															</select>
														<?php
													}
													elseif($BOM_STAT == 3)
													{
														$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;

														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$BOM_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														if($ISDELETE == 1)
															$disButton	= 0;

														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" <?php if($disButton == 1){ ?> disabled <?php } ?>>
																<option value="1"<?php if($BOM_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
																<option value="2"<?php if($BOM_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
																<option value="3"<?php if($BOM_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																<option value="4"<?php if($BOM_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																<option value="5"<?php if($BOM_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
																<option value="6"<?php if($BOM_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<option value="7"<?php if($BOM_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																<option value="9"<?php if($BOM_STAT == 9) { ?> selected <?php } ?>>Void</option>
															</select>
														<?php
													}
												}
												elseif($ISAPPROVE == 1)
												{
													if($BOM_STAT == 1 || $BOM_STAT == 4)
													{
														$disButton	= 1;
														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1">New</option>
																<option value="2">Confirm</option>
															</select>
														<?php
													}
													else if($BOM_STAT == 2 || $BOM_STAT == 3 || $BOM_STAT == 7)
													{
														$disButton	= 0;
														if($canApprove == 0)
															$disButton	= 1;
															
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$BOM_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
													
														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($BOM_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																<option value="2"<?php if($BOM_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																<option value="3"<?php if($BOM_STAT == 3) { ?> selected <?php } ?> >Approved</option>
																<option value="4"<?php if($BOM_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																<option value="5"<?php if($BOM_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																<option value="6"<?php if($BOM_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																<option value="7"<?php if($BOM_STAT == 7) { ?> selected <?php } ?> >Waiting</option>
																<?php if($BOM_STAT == 3 || $BOM_STAT == 9) { ?>
																<option value="9"<?php if($BOM_STAT == 9) { ?> selected <?php } ?>>Void</option>
																<?php } ?>
															</select>
														<?php
													}
												}
												elseif($ISCREATE == 1)
												{
													if($BOM_STAT == 1 || $BOM_STAT == 4)
													{
														$disButton	= 0;
														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($BOM_STAT == 1) { ?> selected <?php } ?> >New</option>
																<option value="2"<?php if($BOM_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
																<option value="3"<?php if($BOM_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																<option value="4"<?php if($BOM_STAT == 4) { ?> selected <?php } ?> >Revising</option>
																<option value="5"<?php if($BOM_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>

																<option value="6"<?php if($BOM_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																<option value="7"<?php if($BOM_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																<?php if($BOM_STAT == 3 || $BOM_STAT == 9) { ?>
																<option value="9"<?php if($BOM_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																<?php } ?>
															</select>
														<?php
													}
													else
													{
														$disButton	= 1;
														?>
															<select name="BOM_STAT" id="BOM_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($BOM_STAT == 1) { ?> selected <?php } ?> disabled >New</option>
																<option value="2"<?php if($BOM_STAT == 2) { ?> selected <?php } ?> disabled >Confirm</option>
																<option value="3"<?php if($BOM_STAT == 3) { ?> selected <?php } ?> disabled >Approved</option>
																<option value="4"<?php if($BOM_STAT == 4) { ?> selected <?php } ?> disabled >Revising</option>
																<option value="5"<?php if($BOM_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
																<option value="6"<?php if($BOM_STAT == 6) { ?> selected <?php } ?> style="display:none" >Closed</option>
																<option value="7"<?php if($BOM_STAT == 7) { ?> selected <?php } ?> disabled >Waiting</option>
																<?php if($BOM_STAT == 3 || $BOM_STAT == 9) { ?>
																<option value="9"<?php if($BOM_STAT == 9) { ?> selected <?php } ?> disabled>Void</option>
																<?php } ?>
															</select>
														<?php
													}
												}
											}
										// END : FOR ALL APPROVAL FUNCTION
			                        ?>
		                            </div>
		                        </div>
		                        <?php
									$url_Material	= site_url('c_production/c_b0fm47/s3l4llit3m/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <div class="row">
		                            <div class="col-md-12">
		                                <div class="box box-warning">
		                                	<br>
		                               		<div class="alert alert-warning alert-dismissible">
		                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                        <i class="icon fa fa-warning"></i><?php echo $stepalert3; ?>
		                                    </div>
		                                     <script>
		                                        function chkSTEP(row, thisSYEP)
		                                        {
													if(document.getElementById('BOM_NAME').value == '')
													{
														swal("<?php echo $alert2; ?>",
														{
															icon:"warning",
														})
														.then(function()
														{
															document.getElementById('chkSTEP'+row+'P_STEP').checked = false;
															$('#BOM_NAME').focus();
														});
														return false;
													}

													if(document.getElementById('BOM_FG1').value == '')
													{
														swal("<?php echo $alert3; ?>",
														{
															icon:"warning",
														})
														.then(function()
														{
															document.getElementById('chkSTEP'+row+'P_STEP').checked = false;
															$('#BOM_FG1').focus();
														});
														return false;
													}
														
		                                            document.getElementById('selSTEP'+row+'P_STEP').value = thisSYEP;
		                                            isChk	= document.getElementById('chkSTEP'+row+'P_STEP').checked;
		                                            if(isChk == true)
		                                            {
		                                                document.getElementById('shwSTEP'+row+'P_STEP').disabled = false;
		                                            }
		                                            else
		                                            {
		                                                document.getElementById('shwSTEP'+row+'P_STEP').disabled = true;
		                                            }
		                                        }
		                                    </script>
			                                <?php
			                                    $TOTSTEP	= 0;
			                                   	$sqlTSTEP	= "SELECT COUNT(DISTINCT PRODS_ORDER) AS TOTSTEP
			                                   					FROM tbl_prodstep";
			                                	$resTSTEP 	= $this->db->query($sqlTSTEP)->result();
			                                	 foreach($resTSTEP as $rowTSTEP) :
			                                        $TOTSTEP= $rowTSTEP->TOTSTEP;
			                                    endforeach;
			                                ?>
			                            	<!-- PROCESS LIST -->
			                            	<input type="hidden" name="totSTP" id="totSTP" value="<?php echo $TOTSTEP; ?>">
			                            	<script>
			                            		function chgORD(selORD, rowSel)
			                            		{
													totSTP	= document.getElementById('totSTP').value; 
													chkSel	= document.getElementById('chkSTEP'+selORD+'P_STEP').checked;
			                            			for(i=1; i<=totSTP; i++)
													{
														stfORD	= document.getElementById('selSTEP'+i+'BOMSTF_ORD').value;
														STEP_X	= document.getElementById('dataSTEP'+i+'STEP_X').value;
														if(chkSel == false)
														{
																swal('<?php echo $alert6; ?>');
																$('#selSTEP'+rowSel+'BOMSTF_ORD').val(0).trigger('change');
														}
														else
														{
															if(stfORD == selORD && selORD == i)
															{
																swal('<?php echo $alert7; ?> '+i+' <?php echo $alert8; ?>'+STEP_X);
																$('#selSTEP'+rowSel+'BOMSTF_ORD').val(0).trigger('change');
															}
														}
													}
			                            		}
			                            	</script>
		                                    <div class="box-body">
		                                        <ul class="timeline">
		                                            <?php
		                                                $url_WIP	= site_url('c_production/c_b0fm47/s3l4llW1p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                                                $url_RM		= site_url('c_production/c_b0fm47/s3l4llRM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                                                $rowStep	= 0;
		                                                $resSTEP	= 0;
		                                                $sqlProds	= "SELECT PRODS_STEP, PRODS_NAME, PRODS_DESC, PRODS_ORDER
		                                                                FROM tbl_prodstep WHERE PRODS_STAT = 1";
		                                                $resProds	= $this->db->query($sqlProds)->result();
		                                                foreach($resProds as $rowProds) :
		                                                    $rowStep	= $rowStep + 1;
		                                                    $PRODS_STEP	= $rowProds->PRODS_STEP;
		                                                    $P_STEP		= $rowProds->PRODS_STEP;
		                                                    $STEP_X		= $rowProds->PRODS_NAME;
		                                                    $STEP_XD	= $rowProds->PRODS_DESC;
		                                                    $BOMSTF_ORD	= $rowProds->PRODS_ORDER;
		                                                    
		                                                    // CHECK STEP PROCESS
		                                                    $sqlSTEP	= "tbl_bom_stfdetail WHERE BOM_NUM = '$BOM_NUM' 
		                                                                    AND BOMSTF_STEP = '$P_STEP'
		                                                                    AND PRJCODE = '$PRJCODE'";
		                                                    $resSTEP 	= $this->db->count_all($sqlSTEP);
		                                                    $selSTEP	= '';
		                                                    if($resSTEP > 0)
		                                                    {
		                                                        $selSTEP= $P_STEP ;

			                                                   	$sqlTSORD	= "SELECT BOMSTF_ORD FROM tbl_bom_stfdetail
			                                                   					WHERE BOM_NUM = '$BOM_NUM' 
				                                                                   AND BOMSTF_STEP = '$P_STEP' AND PRJCODE = '$PRJCODE'";
			                                                	$resSORD 	= $this->db->query($sqlTSORD)->result();
			                                                	 foreach($resSORD as $rowSORD) :
					                                                $BOMSTF_ORD	= $rowSORD->BOMSTF_ORD;
					                                            endforeach;
		                                                    }
		                                                    
		                                                    
		                                                    $resRMC		= 0;
		                                                    $resFGC		= 0;
		                                                    if($task == 'edit')
		                                                    {
		                                                        $sqlRMC	= "tbl_bom_stfdetail A
		                                                                    WHERE A.BOM_NUM = '$BOM_NUM'
		                                                                        AND A.BOMSTF_TYPE = 'IN'
		                                                                        AND A.PRJCODE = '$PRJCODE'";
		                                                        $resRMC 	= $this->db->count_all($sqlRMC);
		                                                                                                                                                            
		                                                        $sqlFGC	= "tbl_bom_stfdetail A
		                                                                    WHERE A.BOM_NUM = '$BOM_NUM'
		                                                                        AND A.BOMSTF_TYPE = 'OUT'
		                                                                        AND A.PRJCODE = '$PRJCODE'";
		                                                        $resFGC 	= $this->db->count_all($sqlFGC);
		                                                    }
		                                                    ?>
		                                                        <input type="hidden" name="dataSTEP[<?php echo $rowStep; ?>][P_STEP]" value="<?php echo $PRODS_STEP; ?>">
		                                                        <input type="hidden" id="dataSTEP<?php echo $rowStep; ?>STEP_X" value="<?php echo $STEP_X; ?>">
		                                                        <div class="box box-success collapsed-box">
		                                                            <div class="box-header with-border">
		                                                                <font style="font-weight:bold"><?php echo "$rowStep. $STEP_X"; ?></font>
		                                                                <div class="box-tools pull-right">
		                                                                    <input type="checkbox" name="chkSTEP[<?php echo $rowStep; ?>][P_STEP]" id="chkSTEP<?php echo $rowStep; ?>P_STEP" onClick="chkSTEP('<?php echo $rowStep; ?>', '<?php echo $P_STEP; ?>')" <?php if($resSTEP > 0) { ?> checked <?php } if($isDis != 0) { ?> disabled <?php } ?>>
		                                                                    <input type="hidden" name="selSTEP[<?php echo $rowStep; ?>][P_STEP]" id="selSTEP<?php echo $rowStep; ?>P_STEP" value="<?php echo $selSTEP; ?>">
		                                                                    <button type="button" id="shwSTEP<?php echo $rowStep; ?>P_STEP" class="btn btn-box-tool" data-widget="collapse" <?php if($resSTEP == 0) { ?> disabled <?php } ?>><i class="fa fa-plus"></i>
		                                                                    </button>
		                                                                    <button type="button" class="btn btn-box-tool" data-widget="remove" style="display:none"><i class="fa fa-times"></i>
		                                                                    </button>
				                                                            <select name="selSTEP[<?php echo $rowStep; ?>][BOMSTF_ORD]" id="selSTEP<?php echo $rowStep; ?>BOMSTF_ORD" class="form-control select2" style="max-width:100px" onChange="chgORD(this.value, <?php echo $rowStep; ?>)">
				                                                            	<option value="0">0</option>
				                                                            <?php
				                                                            	for($a=1; $a<=$TOTSTEP; $a++)
			                                    								{ ?>
						                                            			<option value="<?php echo $a; ?>" <?php if($a == $BOMSTF_ORD) { ?> selected <?php } ?>><?php echo $a; ?></option>
						                                            		<?php } ?>
						                                            		</select>
		                                                                </div>
		                                                            </div>
		                                                            
		                                                            <div class="box-body">
		                                                                <div class="row">
		                                                                    <!-- START : RM NEEDED -->
		                                                                        <div class="col-md-6">
		                                                                            <div class="box box-warning">
		                                                                                <br>
		                                                                                <div class="form-group">
		                                                                                    <div class="col-sm-10">
		                                                                                        <script>
		                                                                                            var urlRM 	= "<?php echo $url_RM;?>";
		                                                                                            var BOMNUM	= "<?php echo $BOM_NUM; ?>";
		                                                                                            function selectRM(PRODSTEP)
		                                                                                            {
		                                                                                                title = 'Select Item';
		                                                                                                w = 800;
		                                                                                                h = 500;
		                                                                                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                                                                                var left = (screen.width/2)-(w/2);
		                                                                                                var top = (screen.height/2)-(h/2);
		                                                                                                return window.open(urlRM+'&BOMNUM='+BOMNUM+'&PRODSTEP='+PRODSTEP, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                                                                                            }
		                                                                                        </script>
		                                                                                        <button class="btn btn-warning" type="button" onClick="selectRM('<?php echo $P_STEP; ?>');" <?php if($isDis != 0) { ?> style="display:none" <?php } ?>>
		                                                                                        <i class="glyphicon glyphicon-save"></i></button>
		                                                                                    </div>
		                                                                                </div>
		                                                                                <table width="100%" border="1" id="tbl_IN<?php echo $P_STEP; ?>">
		                                                                                    <tr style="background:#CCCCCC">
		                                                                                        <th width="4%" height="25" style="text-align:center">No.</th>
		                                                                                        <th width="9%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
		                                                                                        <th width="65%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
		                                                                                        <th width="4%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
		                                                                                        <th width="18%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
		                                                                                    </tr>
		                                                                                    <?php
		                                                                                        $rowMTR	= 0;
		                                                                                        $i		= 0;
		                                                                                        $j		= 0;
		                                                                                        if($resRMC > 0)
		                                                                                        {																
		                                                                                            $sqlRM		= "SELECT A.BOMSTF_STEP, A.BOMSTF_TYPE,
		                                                                                                                A.ITM_CODE, B.ITM_NAME, 
		                                                                                                                A.ITM_UNIT, A.ITM_QTY, 
		                                                                                                                A.ITM_PRICE, B.ITM_GROUP, B.ITM_CATEG,
		                                                                                                                B.ACC_ID, B.ACC_ID_UM,
		                                                                                                                B.NEEDQRC, A.BOMSTF_NUM,
																														A.ITM_UNIT, A.BOMSTF_UC
		                                                                                                            FROM tbl_bom_stfdetail A
		                                                                                                                LEFT JOIN tbl_item B
		                                                                                                                ON A.ITM_CODE = B.ITM_CODE
		                                                                                                                    AND B.PRJCODE = '$PRJCODE'
		                                                                                                            WHERE A.BOM_NUM = '$BOM_NUM'
		                                                                                                                AND A.BOMSTF_TYPE = 'IN'
		                                                                                                                AND A.BOMSTF_STEP = '$P_STEP'
		                                                                                                                AND A.PRJCODE = '$PRJCODE'
		                                                                                                            /*UNION
		                                                                                                            SELECT A.BOMSTF_STEP, A.BOMSTF_TYPE,
																														A.ITM_CODE, B.ITM_NAME,
																														A.ITM_UNIT, A.ITM_QTY,
																														A.ITM_PRICE, B.ITM_GROUP,
																														B.ACC_ID, B.ACC_ID_UM,
																														B.NEEDQRC, A.BOMSTF_NUM,
																														A.ITM_UNIT, A.BOMSTF_UC
																													FROM
																															tbl_bom_stfdetail A
																														INNER JOIN tbl_item_collh C 
																														ON A.ITM_CODE = C.ICOLL_CODE
																															AND C.PRJCODE = '$PRJCODE'
																														INNER JOIN tbl_item B 
																														ON B.ITM_CODE = C.ICOLL_FG
																															AND B.PRJCODE = '$PRJCODE'
																													WHERE
																														A.BOM_NUM = '$BOM_NUM'
																														AND A.BOMSTF_TYPE = 'IN'
																														AND A.BOMSTF_STEP = '$P_STEP'
																														AND A.PRJCODE = '$PRJCODE'*/
																														";
		                                                                                            $resRM 	= $this->db->query($sqlRM)->result();
		                                                                                            foreach($resRM as $rowRM) :
		                                                                                                $rowMTR  		= ++$i;
		                                                                                                $BOMSTF_NUM		= $rowRM->BOMSTF_NUM;
		                                                                                                $BOMSTF_STEP	= $rowRM->BOMSTF_STEP;
		                                                                                                $BOMSTF_TYPE 	= $rowRM->BOMSTF_TYPE;
		                                                                                                $ITM_CODE 		= $rowRM->ITM_CODE;
		                                                                                                $ITM_GROUP 		= $rowRM->ITM_GROUP;
		                                                                                                $ITM_CATEG 		= $rowRM->ITM_CATEG;
		                                                                                                $ITM_NAME 		= $rowRM->ITM_NAME;
		                                                                                                $ITM_QTY 		= $rowRM->ITM_QTY;
		                                                                                                $ITM_PRICE 		= $rowRM->ITM_PRICE;
		                                                                                                $ACC_ID 		= $rowRM->ACC_ID;
		                                                                                                $ACC_ID_UM 		= $rowRM->ACC_ID_UM;
		                                                                                                $NEEDQRC 		= $rowRM->NEEDQRC;
		                                                                                                $ITM_UNIT 		= $rowRM->ITM_UNIT;
		                                                                                                $BOMSTF_UC		= $rowRM->BOMSTF_UC;
		                                                                                                
		                                                                                                // CEK STOCK PER WH
		                                                                                                    $ITM_STOCK	= 0;
		                                                                                                    $sqlWHSTOCK	= "SELECT SUM(ITM_VOLM) 
		                                                                                                                        AS ITM_STOCK
		                                                                                                                    FROM tbl_item_whqty
		                                                                                                                    WHERE ITM_CODE = '$ITM_CODE'
		                                                                                                                    AND PRJCODE = '$PRJCODE'";
		                                                                                                    $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
		                                                                                                    foreach($resWHSTOCK as $rowSTOCK) :
		                                                                                                        $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
		                                                                                                    endforeach;

		                                                                                                $ITM_UNITV 	= $ITM_UNIT;
		                                                                                                //$decForm 	= 2;
		                                                                                                $decForm 	= $decForm4;
		                                                                                                if($ITM_CATEG == 'DY')
		                                                                                                {
		                                                                                                	$decForm 	= $decForm3;
		                                                                                                	$ITM_UNITV 	= "%";
		                                                                                                }
		                                                                                                elseif($ITM_CATEG == 'WIP')
		                                                                                                {
		                                                                                                	$decForm 	= $decForm2;
		                                                                                                }
		                                                                                    
		                                                                                                if ($j==1) {
		                                                                                                    echo "<tr class=zebra1>";
		                                                                                                    $j++;
		                                                                                                } else {
		                                                                                                    echo "<tr class=zebra2>";
		                                                                                                    $j--;
		                                                                                                }
		                                                                                                ?> 
		                                                                                                <tr id="trIN_<?php echo "$P_STEP"."_$rowMTR"; ?>">
		                                                                                                    <!-- NO URUT -->
		                                                                                                    <td width="4%" height="25" style="text-align:center" nowrap>
		                                                                                                        <?php
		                                                                                                            if($BOM_STAT == 1)
		                                                                                                            {
		                                                                                                                ?>
		                                                                                                                    <a href="#" onClick="deldataRM('<?php echo $P_STEP; ?>',<?php echo $rowMTR; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
		                                                                                                                <?php
		                                                                                                            }
		                                                                                                            else
		                                                                                                            {
		                                                                                                                echo "$rowMTR.";
		                                                                                                            }
		                                                                                                        ?>
		                                                                                                        <input style="display:none" type="Checkbox" id="dataRM[<?php echo $rowMTR; ?>][chk]" name="dataRM[<?php echo $rowMTR; ?>][chk]" value="<?php echo $rowMTR; ?>" onClick="pickThis(this,<?php echo $rowMTR; ?>)">
		                                                                                                        <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>BOMSTF_NUM" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][BOMSTF_NUM]" value="<?php echo $BOMSTF_NUM; ?>" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>BOMSTF_UC" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][BOMSTF_UC]" value="<?php echo $BOMSTF_UC; ?>" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>NEEDQRC" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][NEEDQRC]" value="'+NEEDQRC+'" width="10" size="15">
		                                                                                                    </td>
		                                                                                                        
		                                                                                                    <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
		                                                                                                    <td width="9%" style="text-align:left" nowrap>
		                                                                                                    <?php
		                                                                                                        if($NEEDQRC == 0)
		                                                                                                        {
		                                                                                                            echo $ITM_CODE;
		                                                                                                        }
		                                                                                                        elseif($NEEDQRC == 1 AND ($BOM_STAT == 1 OR $BOM_STAT == 4))
		                                                                                                        {
		                                                                                                            ?>
		                                                                                                            <a onclick="setQRC(<?php echo $rowMTR; ?>, '<?php echo $P_STEP; ?>');">
		                                                                                                                <?php echo $ITM_CODE; ?>
		                                                                                                            </a>
		                                                                                                            <?php
		                                                                                                        }
		                                                                                                        else
		                                                                                                        {
		                                                                                                            echo $ITM_CODE;
		                                                                                                        }
		                                                                                                    ?>
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_TYPE" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_TYPE]" value="IN" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_CODE" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_GROUP" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" width="10" size="15">
		                                                                                                        
		                                                                                                    </td>
		                                                                                                    <!-- ITM_NAME -->
		                                                                                                    <td width="65%" style="text-align:left">
		                                                                                                        <?php echo $ITM_NAME; ?>
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_NAME" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>" width="10" size="15">
		                                                                                                    </td>
		                                                                                                    <!-- ITM_UNIT -->  
		                                                                                                    <td width="4%" style="text-align:center" nowrap>
		                                                                                                        <?php echo $ITM_UNITV; ?>
		                                                                                                        <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_UNIT" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>" width="10" size="15">
		                                                                                                    </td>
		                                                                                                    <!-- ITM_QTY, ITM_PRICE -->
		                                                                                                    <td width="18%" style="text-align:right" nowrap>
		                                                                                                        <?php if($BOM_STAT == 1 || $BOM_STAT == 4) { ?>
		                                                                                                            <input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY<?php echo $rowMTR; ?>" id="IN<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY" value="<?php echo number_format($ITM_QTY, $decForm); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, <?php echo $rowMTR; ?>,  '<?php echo $P_STEP; ?>');" >
		                                                                                                        <?php } else { ?>
		                                                                                                            <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY<?php echo $rowMTR; ?>" id="IN<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY" value="<?php echo number_format($ITM_QTY, $decForm); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, <?php echo $rowMTR; ?>,  '<?php echo $P_STEP; ?>');" >
		                                                                                                            <?php echo number_format($ITM_QTY, $decForm);
		                                                                                                        } ?>
		                                                                                                        <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_QTY]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>">
		                                                                                                        <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_STOCK" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_STOCK" value="<?php echo $ITM_STOCK; ?>">
		                                                                                                        <input type="hidden" style="text-align:right" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_PRICE]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>">
		                                                                                                        <input type="hidden" style="text-align:right" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ACC_ID]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
		                                                                                                        <input type="hidden" style="text-align:right" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ACC_ID_UM]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                                <?php
		                                                                                            endforeach;
		                                                                                        }
		                                                                                    ?>
		                                                                                    <input type="hidden" name="totRM_<?php echo $P_STEP; ?>" id="totRM_<?php echo $P_STEP; ?>" value="<?php echo $rowMTR; ?>">
		                                                                                </table>
		                                                                            </div>
		                                                                        </div>
		                                                                    <!-- END : RM NEEDED -->

		                                                                    <!-- START : OUTPUT ITEM -->
		                                                                        <div class="col-md-6">
		                                                                            <div class="box box-success">
		                                                                                <br>
		                                                                                <div class="form-group">
		                                                                                    <div class="col-sm-10">
		                                                                                        <script>
		                                                                                            var urlWIP	= "<?php echo $url_WIP; ?>";
		                                                                                            var BOMNUM	= "<?php echo $BOM_NUM; ?>";
		                                                                                            function selectWIP(PRODSTEP)
		                                                                                            {
		                                                                                                title = 'Select Item';
		                                                                                                w = 900;
		                                                                                                h = 550;
		                                                                                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		                                                                                                var left = (screen.width/2)-(w/2);
		                                                                                                var top = (screen.height/2)-(h/2);
		                                                                                                return window.open(urlWIP+'&BOMNUM='+BOMNUM+'&PRODSTEP='+PRODSTEP, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		                                                                                            }
		                                                                                        </script>
		                                                                                        <button class="btn btn-success" type="button" onClick="selectWIP('<?php echo $P_STEP; ?>');" <?php if($isDis != 0) { ?> style="display:none" <?php } ?>>
		                                                                                        <i class="fa fa-cubes"></i>
		                                                                                        </button>
		                                                                                    </div>
		                                                                                </div>
		                                                                                <table width="100%" border="1" id="tbl_OUT<?php echo $P_STEP; ?>">
		                                                                                    <tr style="background:#CCCCCC">
		                                                                                        <th width="4%" height="25" style="text-align:center">No.</th>
		                                                                                        <th width="9%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
		                                                                                        <th width="65%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
		                                                                                        <th width="4%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
		                                                                                        <th width="18%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
		                                                                                    </tr>
		                                                                                    <?php
		                                                                                        $rowFG1	= 0;
		                                                                                        $i		= 0;
		                                                                                        $j		= 0;
		                                                                                        if($resFGC > 0)
		                                                                                        {																
		                                                                                            $sqlFG		= "SELECT A.BOMSTF_STEP, A.BOMSTF_TYPE,
		                                                                                                                A.ITM_CODE, B.ITM_NAME, 
		                                                                                                                A.ITM_UNIT, A.ITM_QTY, 
		                                                                                                                A.ITM_PRICE, B.ITM_GROUP,
		                                                                                                                B.ACC_ID, B.ACC_ID_UM,
		                                                                                                                B.NEEDQRC, A.BOMSTF_NUM,
																														A.ITM_UNIT, A.BOMSTF_UC
		                                                                                                            FROM tbl_bom_stfdetail A
		                                                                                                                INNER JOIN tbl_item B
		                                                                                                                ON A.ITM_CODE = B.ITM_CODE
		                                                                                                                    AND B.PRJCODE = '$PRJCODE'
		                                                                                                            WHERE A.BOM_NUM = '$BOM_NUM'
		                                                                                                                AND A.BOMSTF_TYPE = 'OUT'
		                                                                                                                AND A.BOMSTF_STEP = '$P_STEP'
		                                                                                                                AND A.PRJCODE = '$PRJCODE'";
		                                                                                            $resFG 	= $this->db->query($sqlFG)->result();
		                                                                                            foreach($resFG as $rowFG) :
		                                                                                                $rowFG1  		= ++$i;
		                                                                                                $BOMSTF_NUM		= $rowFG->BOMSTF_NUM;
		                                                                                                $BOMSTF_UC		= $rowFG->BOMSTF_UC;
		                                                                                                $BOMSTF_STEP	= $rowFG->BOMSTF_STEP;
		                                                                                                $BOMSTF_TYPE 	= $rowFG->BOMSTF_TYPE;
		                                                                                                $ITM_CODE 		= $rowFG->ITM_CODE;
		                                                                                                $ITM_GROUP 		= $rowFG->ITM_GROUP;
		                                                                                                $ITM_NAME 		= $rowFG->ITM_NAME;
		                                                                                                $ITM_QTY 		= $rowFG->ITM_QTY;
		                                                                                                $ITM_PRICE 		= $rowFG->ITM_PRICE;
		                                                                                                $ACC_ID 		= $rowFG->ACC_ID;
		                                                                                                $ACC_ID_UM 		= $rowFG->ACC_ID_UM;
		                                                                                                $NEEDQRC 		= $rowFG->NEEDQRC;
		                                                                                                $ITM_UNIT 		= $rowFG->ITM_UNIT;
		                                                                                                
		                                                                                                // CEK STOCK PER WH
		                                                                                                    $ITM_STOCK	= 0;
		                                                                                                    $sqlWHSTOCK	= "SELECT SUM(ITM_VOLM) 
		                                                                                                                        AS ITM_STOCK
		                                                                                                                    FROM tbl_item_whqty
		                                                                                                                    WHERE ITM_CODE = '$ITM_CODE'
		                                                                                                                    AND PRJCODE = '$PRJCODE'";
		                                                                                                    $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
		                                                                                                    foreach($resWHSTOCK as $rowSTOCK) :
		                                                                                                        $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
		                                                                                                    endforeach;
		                                                                                    
		                                                                                                if ($j==1) {
		                                                                                                    echo "<tr class=zebra1>";
		                                                                                                    $j++;
		                                                                                                } else {
		                                                                                                    echo "<tr class=zebra2>";
		                                                                                                    $j--;
		                                                                                                }
		                                                                                                ?> 
		                                                                                               	<tr id="trOUT_<?php echo "$P_STEP"."_$rowFG1"; ?>">
		                                                                                                    <!-- NO URUT -->
		                                                                                                    <td width="4%" height="25" style="text-align:center" nowrap>
		                                                                                                        <?php
		                                                                                                            if($BOM_STAT == 1)
		                                                                                                            {
		                                                                                                                ?>
		                                                                                                                    <a href="#" onClick="deldataFG('<?php echo $P_STEP; ?>',<?php echo $rowFG1; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
		                                                                                                                <?php
		                                                                                                            }
		                                                                                                            else
		                                                                                                            {
		                                                                                                                echo "$rowFG1.";
		                                                                                                            }
		                                                                                                        ?>
		                                                                                                        <input style="display:none" type="Checkbox" id="dataFG[<?php echo $rowFG1; ?>][chk]" name="dataFG[<?php echo $rowFG1; ?>][chk]" value="<?php echo $rowFG1; ?>" onClick="pickThis(this,<?php echo $rowFG1; ?>)">
		                                                                                                        <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>BOMSTF_NUM" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][BOMSTF_NUM]" value="<?php echo $BOMSTF_NUM; ?>" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>BOMSTF_UC" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][BOMSTF_UC]" value="<?php echo $BOMSTF_UC; ?>" width="10" size="15">
		                                                                                                         <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>NEEDQRC" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][NEEDQRC]" value="'+NEEDQRC+'" width="10" size="15">
		                                                                                                    </td>
		                                                                                                    <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
		                                                                                                    <td width="9%" style="text-align:left" nowrap>
		                                                                                                        <?php print $ITM_CODE; ?>
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_TYPE" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_TYPE]" value="OUT" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_CODE" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>" width="10" size="15">
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_GROUP" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" width="10" size="15">
		                                                                                                        
		                                                                                                    </td>
		                                                                                                    <!-- ITM_NAME -->
		                                                                                                    <td width="65%" style="text-align:left">
		                                                                                                        <?php echo $ITM_NAME; ?>
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_NAME" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>" width="10" size="15">
		                                                                                                    </td>
		                                                                                                    <!-- ITM_UNIT -->  
		                                                                                                    <td width="4%" style="text-align:center" nowrap>
		                                                                                                        <?php echo $ITM_UNIT; ?>
		                                                                                                        <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_UNIT" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>" width="10" size="15">
		                                                                                                    </td>
		                                                                                                    <!-- ITM_QTY, ITM_PRICE -->
		                                                                                                    <td width="18%" style="text-align:right" nowrap>
		                                                                                                        <?php if($BOM_STAT == 1 || $BOM_STAT == 4) { ?>
		                                                                                                            <input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY<?php echo $rowFG1; ?>" id="OUT<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_QTY" value="<?php echo number_format($ITM_QTY,2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMFG(this, <?php echo $rowFG1; ?>,  '<?php echo $P_STEP; ?>');" >
		                                                                                                        <?php } else { ?>
		                                                                                                            <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY<?php echo $rowFG1; ?>" id="OUT<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_QTY" value="<?php echo number_format($ITM_QTY,2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMFG(this, <?php echo $rowFG1; ?>,  '<?php echo $P_STEP; ?>');" >
		                                                                                                            <?php echo number_format($ITM_QTY, 2);
		                                                                                                        } ?>
		                                                                                                        <input style="text-align:right" type="hidden" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_QTY]" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>">
		                                                                                                        <input style="text-align:right" type="hidden" name="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_STOCK" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_STOCK" value="<?php echo $ITM_STOCK; ?>">
		                                                                                                        <input type="hidden" style="text-align:right" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_PRICE]" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>">
		                                                                                                        <input type="hidden" style="text-align:right" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ACC_ID]" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
		                                                                                                        <input type="hidden" style="text-align:right" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ACC_ID_UM]" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                                <?php
		                                                                                            endforeach;
		                                                                                        }
		                                                                                    ?>
		                                                                                    <input type="hidden" name="totFG_<?php echo $P_STEP; ?>" id="totFG_<?php echo $P_STEP; ?>" value="<?php echo $rowFG1; ?>">
		                                                                                </table>
		                                                                            </div>
		                                                                        </div>
		                                                                    <!-- END : OUTPUT ITEM -->
		                                                                </div>
		                                                            </div>
		                                                        </div>
		                                                    <?php
		                                                endforeach;
		                                            ?>
		                                        </ul>
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
												//if($BOM_STAT == 1 && $ISCREATE == 1)
												//{
													?>
														<button class="btn btn-primary">
														<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												//}
											}
											else
											{
												if($ISCREATE == 1 && $BOM_STAT == 3)
												{
													?>
														<button class="btn btn-primary" style="display:none" id="tblClose">
														<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
												elseif($ISAPPROVE == 1 && $BOM_STAT != 3)
												{
													?>
														<button class="btn btn-primary">
														<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
												elseif($ISCREATE == 1 && ($BOM_STAT == 1 || $BOM_STAT == 4))
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i>
														</button>&nbsp;
													<?php
												}
											}
											$backURL	= site_url('c_production/c_b0fm47/gl20M/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
										?>
		                            </div>
		                        </div>
								<?php
		                            $DOC_NUM	= $BOM_NUM;
		                            $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
		                            $resCAPPH	= $this->db->count_all($sqlCAPPH);
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
		        </div>
		    </div>
		</section>
	</body>
</html>

<?php
	if($LangID == 'IND')
	{
		$qtyDetail	= 'Detail item tidak boleh kosong.';
		$volmAlert	= 'Qty order tidak boleh nol.';
	}
	else
	{
		$qtyDetail	= 'Item Detail can not be empty.';
		$volmAlert	= 'Order qty can not be zero.';
	}
?>

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
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker4').datepicker({
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
  
	var decFormat		= 2;
	
	function add_itemRM(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		BOM_NUM			= "<?php echo $BOM_NUM; ?>";
		BOMSTF_NUM		= "STFQC<?php echo $BOM_NUM; ?>";
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_GROUP 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		STOCK_PRJ 		= arrItem[5];	// STOCK PER PROJECT
		REM_QTY			= arrItem[6];
		ITM_PRICE 		= arrItem[7];
		ACC_ID 			= arrItem[8];
		ACC_ID_UM 		= arrItem[9];
		STOCK_WH		= arrItem[10];	// STOCK PER WH
		P_STEP			= arrItem[11];	// STEP
		NEEDQRC			= arrItem[12];	// NEEDQRC
		ITM_QTY			= arrItem[12];	// ITM_QTY
		SRC				= arrItem[14];	// ITM_QTY
		ITM_CODE1		= arrItem[15];	// ITM_QTY
		ITM_CATEG		= arrItem[16];	// ITM_QTY

		ITM_UNITV 		= ITM_UNIT;
		if(ITM_CATEG == 'DY')
		{
			ITM_UNITV	= '%';
		}

		if(ITM_CODE1 != '')
			ITM_CODE	= ITM_CODE1;

		ITM_STOCK		= parseFloat(STOCK_PRJ);
		
		objTable 		= document.getElementById('tbl_IN'+P_STEP);
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		//document.frmSOInfo.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'trIN_'+P_STEP+'_'+ intIndex;
		
		var P_STEP1	= '\''+P_STEP+'\'';
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deldataRM('+P_STEP1+','+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="dataRM'+P_STEP+'['+intIndex+'][chk]" name="dataRM'+P_STEP+'['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" ><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'BOMSTF_NUM" name="dataRM'+P_STEP+'['+intIndex+'][BOMSTF_NUM]" value="'+BOMSTF_NUM+'" width="10" size="15">';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		if(NEEDQRC == 1)
		{
			objTD.innerHTML = '<a onclick="setQRC('+intIndex+',  '+P_STEP1+');">'+ITM_CODE+'</a><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_TYPE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="IN" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_CODE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_GROUP" name="dataRM'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'NEEDQRC" name="dataRM'+P_STEP+'['+intIndex+'][NEEDQRC]" value="'+NEEDQRC+'" width="10" size="15">';
		}
		else
		{
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_TYPE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="IN" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_CODE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_GROUP" name="dataRM'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'NEEDQRC" name="dataRM'+P_STEP+'['+intIndex+'][NEEDQRC]" value="0" width="10" size="15">';
		}
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_NAME" name="dataRM'+P_STEP+'['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNITV+'<input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_UNIT" name="dataRM'+P_STEP+'['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM_QTY, ITM_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		if(SRC == 'ITM')
		{
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="IN'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, '+intIndex+',  '+P_STEP1+');" ><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataRM'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'"><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" id="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataRM'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		}
		else
		{
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="IN'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, '+intIndex+',  '+P_STEP1+');" readonly ><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataRM'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'"><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" id="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataRM'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		}
		
		document.getElementById('totRM_'+P_STEP).value = intIndex;
	}
	
	function add_itemFG(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		BOM_NUM			= "<?php echo $BOM_NUM; ?>";
		BOMSTF_NUM		= "STFQC<?php echo $BOM_NUM; ?>";
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_GROUP 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		STOCK_PRJ 		= arrItem[5];	// STOCK PER PROJECT
		REM_QTY			= arrItem[6];
		ITM_PRICE 		= arrItem[7];
		ACC_ID 			= arrItem[8];
		ACC_ID_UM 		= arrItem[9];
		STOCK_WH		= arrItem[10];	// STOCK PER WH
		P_STEP			= arrItem[11];	// STEP
		NEEDQRC			= arrItem[12];	// NEEDQRC
		ITM_QTY			= arrItem[12];	// ITM_QTY
		SRC				= arrItem[14];	// ITM_QTY

		ITM_STOCK		= parseFloat(STOCK_PRJ);
		
		objTable 		= document.getElementById('tbl_OUT'+P_STEP);
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		//document.frmSOInfo.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'trOUT_'+P_STEP+'_'+ intIndex;
		
		var P_STEP1	= '\''+P_STEP+'\'';
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deldataFG('+P_STEP1+','+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="dataFG'+P_STEP+'['+intIndex+'][chk]" name="dataFG'+P_STEP+'['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" ><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'BOMSTF_NUM" name="dataFG'+P_STEP+'['+intIndex+'][BOMSTF_NUM]" value="'+BOMSTF_NUM+'" width="10" size="15">';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		if(NEEDQRC == 1)
		{
			objTD.innerHTML = '<a onclick="setQRC('+intIndex+',  '+P_STEP1+');">'+ITM_CODE+'</a><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_TYPE" name="dataFG'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="OUT" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_CODE" name="dataFG'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_GROUP" name="dataFG'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'NEEDQRC" name="dataFG'+P_STEP+'['+intIndex+'][NEEDQRC]" value="'+NEEDQRC+'" width="10" size="15">';
		}
		else
		{
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_TYPE" name="dataFG'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="OUT" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_CODE" name="dataFG'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_GROUP" name="dataFG'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'NEEDQRC" name="dataFG'+P_STEP+'['+intIndex+'][NEEDQRC]" value="0" width="10" size="15">';
		}
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_NAME" name="dataFG'+P_STEP+'['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_UNIT" name="dataFG'+P_STEP+'['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM_QTY, ITM_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		if(SRC == 'ITM')
		{
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="OUT'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMFG(this, '+intIndex+',  '+P_STEP1+');" ><input style="text-align:right" type="hidden" name="dataFG'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataFG'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'"><input style="text-align:right" type="hidden" name="dataFG'+P_STEP+''+intIndex+'ITM_STOCK" id="dataFG'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataFG'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataFG'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataFG'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		}
		else
		{
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="OUT'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMFG(this, '+intIndex+',  '+P_STEP1+');" readonly ><input style="text-align:right" type="hidden" name="dataFG'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataFG'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'"><input style="text-align:right" type="hidden" name="dataFG'+P_STEP+''+intIndex+'ITM_STOCK" id="dataFG'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataFG'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataFG'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataFG'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		}
		
		document.getElementById('totFG_'+P_STEP).value = intIndex;
	}
	
	function add_FG(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_NAME 		= arrItem[2];
		
		$("#BOM_FG").val(ITM_CODE);
		$("#BOM_FG1").val(ITM_CODE);
		$("#BOM_FGNM").val(ITM_NAME);
	}
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var BOM_NUM 	= "<?php echo $BOM_NUM; ?>";
		var BOM_CODE 	= "<?php echo $BOM_CODE; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/		
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_NAME 		= arrItem[2];
		ITM_UNIT 		= arrItem[4];
		ITM_PRICE 		= arrItem[5];
		
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'BOM_NUM" name="data['+intIndex+'][BOM_NUM]" value="'+BOM_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'BOM_CODE" name="data['+intIndex+'][BOM_CODE]" value="'+BOM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';
		
		// ITM QTY NEEDED
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" style="max-width:200px; text-align:right" name="ITM_QTYX'+intIndex+'" id="ITM_QTYX'+intIndex+'" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="getQtyITM(this, '+intIndex+');" ><input type="hidden" id="data'+intIndex+'ITM_QTY" name="data['+intIndex+'][ITM_QTY]" value="0">';
		
		// ITM UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM PRICE
		ITM_PRICE		= parseFloat(Math.abs(ITM_PRICE));
		ITM_PRICEV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="ITM_PRICEX'+intIndex+'" id="ITM_PRICEX'+intIndex+'" size="10" value="'+ITM_PRICEV+'" onKeyPress="return isIntOnlyNew(event);" onBlur="getPRICE(this, '+intIndex+');"><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'">';

		// ITEM TOTAL COST
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = 'hidden<input type="hidden" class="form-control" style="min-width:130px; max-width:350px;" name="ITM_TOTALX'+intIndex+'" id="ITM_TOTALX'+intIndex+'" value="0.00" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="0.00">';
		
		// ITEM NOTES
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" name="data['+intIndex+'][ITM_NOTES]" id="data'+intIndex+'ITM_NOTES" value="">';
				
		document.getElementById('totalrow').value = intIndex;	
	}
	
	function cVOLMRM(thisVal, row, P_STEP)
	{
		var decFormat	= document.getElementById('decFormat').value;
		//var decForm 	= "<?php echo $decForm3; ?>";
		var decForm 	= "<?php echo $decForm4; ?>";
		
		ITM_QTY 	= parseFloat(eval(thisVal).value.split(",").join(""));
		document.getElementById('dataRM'+P_STEP+row+'ITM_QTY').value	= ITM_QTY;
		document.getElementById('IN'+P_STEP+row+'ITM_QTY').value 		= doDecimalFormat(RoundNDecimal(parseFloat(ITM_QTY), decForm));
	}
	
	function cVOLMFG(thisVal, row, P_STEP)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		ITM_QTY 	= parseFloat(eval(thisVal).value.split(",").join(""));
		document.getElementById('dataFG'+P_STEP+row+'ITM_QTY').value	= ITM_QTY;
		document.getElementById('OUT'+P_STEP+row+'ITM_QTY').value 		= doDecimalFormat(RoundNDecimal(parseFloat(ITM_QTY), decFormat));
	}
	
	function getQtyITM(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		//var decForm 	= "<?php echo $decForm3; ?>";
		var decForm 	= "<?php echo $decForm4; ?>";
		
		var ITM_QTYX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTYX));
		document.getElementById('ITM_QTYX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYX)), decForm));
		
		var ITM_PRICE 	= document.getElementById('data'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYX) * parseFloat(ITM_PRICE);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), decForm));
	}
	
	function getPRICE(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function checkInp()
	{
		// check detail per step
		var totChk 	= 0;
		for(var i=1;i<=10;i++)
		{
			if(i == 1)
				var id 	= 'ONE';
			else if(i == 2)
				var id 	= 'TWO';
			else if(i == 3)
				var id 	= 'THR';
			else if(i == 4)
				var id 	= 'FOU';
			else if(i == 5)
				var id 	= 'FIV';
			else if(i == 6)
				var id 	= 'SIX';
			else if(i == 7)
				var id 	= 'SEV';
			else if(i == 8)
				var id 	= 'EIG';
			else if(i == 9)
				var id 	= 'NIN';
			else if(i == 10)
				var id 	= 'TEN';

			var xx = chkObj(document.getElementById('totRM_'+id));

			if(xx != null)
			{
				var totRMS = parseInt(document.getElementById('totRM_'+id).value);
				if(totRMS > 0)
				{
					totChk = totChk+1;
					for(iRM=1; iRM<=totRMS; iRM++)
					{
						var RM_ITMQTY = parseFloat(document.getElementById('dataRM'+id+iRM+'ITM_QTY').value);
						if(RM_ITMQTY == 0)
						{
							swal('Data qty RM tidak boleh ada yang 0',
							{
								icon: "warning",
							})
							.then(function()
							{
								$('#IN'+id+iRM+'ITM_QTY').focus();
							});
							return false;
						}
					}
				}
			}
		}

		//totRow	= document.getElementById('totalrow').value;
		BOM_NAME	= document.getElementById('BOM_NAME').value;
		
		if(BOM_NAME == '')
		{
			swal("<?php echo $alert2; ?>",
			{
				icon:"warning",
			})
			.then(function()
			{
				$('#BOM_NAME').focus();
			});
			return false;	
		}
		
		if(totChk == 0)
		{
			var totRow 	= 0;
			swal("<?php echo $qtyDetail; ?>",
			{
				icon:"warning",
			});
			return false;
		}
		else
		{
			totRow	= document.getElementById('totalrow').value;
		}
		alert(totChk)
		/*for(i=1;i<=totRow;i++)
		{
			var ITM_QTY = parseFloat(document.getElementById('data'+i+'ITM_QTY').value);
			if(ITM_QTY == 0)
			{
				swal("<?php echo $alert1; ?>");
				document.getElementById('ITM_QTYX'+i).focus();
				return false;	
			}
		}*/
		return false;
	}

	function chkObj(t) 
	{
		if (t === undefined) 
		{
			return 'Undefined value!';
		}
		return t;
	}

	function deldataRM(P_STEP1, row)
	{
		var row = document.getElementById("trIN_"+P_STEP1+'_'+ row);
		row.remove();
	}
	
	function deldataFG(P_STEP1, row)
	{
		var row = document.getElementById("trOUT_"+P_STEP1+'_'+ row);
		row.remove();
	}
	
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