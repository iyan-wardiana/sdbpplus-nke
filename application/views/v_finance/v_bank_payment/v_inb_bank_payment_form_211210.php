<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 12 November 2017
	* File Name	= v_inb_bank_payment_form.php
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
$decFormat	= 2;

$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;

$CB_NUM 		= $default['CB_NUM'];
$CB_SOURCE 		= $default['CB_SOURCE'];
$CB_SOURCENO	= $default['CB_SOURCENO'];
$PRJCODE		= $default['PRJCODE'];
$JournalH_Code	= $CB_NUM;
$DocNumber		= $CB_NUM;
$CB_CODE 		= $default['CB_CODE'];
$CB_DATE1		=  $default['CB_DATE'];
$CB_DATE		= date('d/m/Y',strtotime($CB_DATE1));
$CB_TYPE 		= $default['CB_TYPE'];
$CB_RECTYPE		= $default['CB_RECTYPE'];
$CB_CURRID 		= $default['CB_CURRID'];
$SelCurr		= $default['CB_CURRID'];
$CB_DOCTYPE		= $default['CB_DOCTYPE'];
$CB_CURRCONV	= $default['CB_CURRCONV'];
$BankAcc_ID		= $default['ACC_NUM'];
$CB_PAYFOR 		= $default['CB_PAYFOR'];
$CB_CHEQNO 		= $default['CB_CHEQNO'];
$CB_TOTAM 		= $default['CB_TOTAM'];
$CB_TOTAM_PPN	= $default['CB_TOTAM_PPN'];
$CB_DPAMOUNT	= $default['CB_DPAMOUNT'];
$CB_NOTES 		= $default['CB_NOTES'];
$VOID_REASON	= $default['VOID_REASON'];
$CB_STAT		= $default['CB_STAT'];
$CB_ACCID 		= $default['CB_ACCID'];
$ACC_NUM		= $default['CB_ACCID'];
$Patt_Year 		= $default['Patt_Year'];
$Patt_Month 	= $default['Patt_Month'];
$Patt_Date 		= $default['Patt_Date'];
$Patt_Number	= $default['Patt_Number'];
$CB_MEMO		= "";
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
			if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;

			if($TranslCode == 'Date')$Date = $LangTransl;		
			if($TranslCode == 'ChooseInvoice')$ChooseInvoice = $LangTransl;
			if($TranslCode == 'Payment')$Payment = $LangTransl;
			if($TranslCode == 'Finance')$Finance = $LangTransl;		
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;		
			if($TranslCode == 'Payment')$Payment = $LangTransl;
			
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'PaymentNow')$PaymentNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'PaymentFor')$PaymentFor = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'BankAccount')$BankAccount = $LangTransl;
			if($TranslCode == 'ActualBalance')$ActualBalance = $LangTransl;
			if($TranslCode == 'Reserved')$Reserved = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'BGNumber')$BGNumber = $LangTransl;
			if($TranslCode == 'BGAmount')$BGAmount = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'BGRemaining')$BGRemaining = $LangTransl;
			if($TranslCode == 'Remaining')$Remain = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'DocNumber')$Doc_Number = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'DPAkum')$DPAkum = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'VoidNotes')$VoidNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
			if($TranslCode == 'billList')$billList = $LangTransl;
			if($TranslCode == 'amGrThen')$amGrThen = $LangTransl;
			if($TranslCode == 'selInv')$selInv = $LangTransl;
			if($TranslCode == 'inAmInv')$inAmInv = $LangTransl;
			if($TranslCode == 'selSrcPay')$selSrcPay = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Saldo bank yang dipilih kurang dari total yang harus dibayar.";
			$alert2		= "Silahkan pilih status persetujuan.";
			$alert3		= "Silahkan tulis catatan alasan Anda merevisi dokumen ini.";
			
			$paymentApp	= "Persetujuan Pembayaran";
			$descNotes	= "Silahkan periksa kembali sebelum Anda setujui.";
		}
		else
		{
			$alert1		= "Amount of Bank Account is less then Total Payment.";
			$alert2		= "Please select approval status.";
			$alert3		= "Please write why you revising this document.";
			
			$paymentApp	= "Payment Approval";
			$descNotes	= "Please check be carefully before you Approve.";
		}
		
		$secGenCode	= base_url().'index.php/c_finance/c_bp0c07180851/genCode/'; // Generate Code
		
		$TOT_AMOUNT_DP	= 0;
		$GEJ_ACCID		= '';
		if($CB_SOURCE == 'PINV')
		{
			$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.INV_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0' AND INV_CATEG != 'OTH'
							AND A.PRJCODE = '$PRJCODE'
						UNION ALL
						SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_opn_inv A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNI_STAT = '3' AND A.selectedINV = '1' AND A.OPNI_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0'
							AND A.PRJCODE = '$PRJCODE'";
			if($task == 'edit')
			{
				$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_pinv_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.ISVOID = '0' AND INV_CATEG != 'OTH' AND A.PRJCODE = '$PRJCODE'
							UNION ALL
							SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_opn_inv A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNI_STAT = '3' AND A.selectedINV = '1' AND A.ISVOID = '0' AND A.PRJCODE = '$PRJCODE'";
			}
			$vwSPL	= $this->db->query($sql)->result();
			$PAGEFORM	= "PINV";
		}
		elseif($CB_SOURCE == 'DP')
		{
			$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.DP_STAT = '3' AND A.DP_PAID NOT IN (2) AND A.PRJCODE = '$PRJCODE'";
			if($task == 'edit')
			{
				$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_dp_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.DP_STAT = '3' AND A.PRJCODE = '$PRJCODE'";
			}
			$vwSPL	= $this->db->query($sql)->result();
			$PAGEFORM	= "DP";
		}
		else
		{
			$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.INV_PAYSTAT NOT IN ('FP') AND A.ISVOID = '0' AND INV_CATEG = 'OTH'
							 AND A.PRJCODE = '$PRJCODE'";
			if($task == 'edit')
			{
				$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1 AS Vend_Address
							FROM tbl_pinv_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.INV_STAT = '3' AND A.selectedINV = '1' AND A.ISVOID = '0' AND INV_CATEG = 'OTH' AND A.PRJCODE = '$PRJCODE'";
			}
			$vwSPL	= $this->db->query($sql)->result();
			
			// GET ACCOUNT
			$GEJ_ACCID	= '';
			$sqlAcc		= "SELECT GEJ_ACCID
							FROM tbl_fpa_header
							WHERE SPLCODE = '$CB_PAYFOR' AND FPA_STAT = 3 AND PRJCODE = '$PRJCODE'";
			$resAcc		= $this->db->query($sqlAcc)->result();
			foreach($resAcc as $rowAcc):
				$GEJ_ACCID	= $rowAcc->GEJ_ACCID;
			endforeach;
			
			$PAGEFORM	= "OTH";
		}
		
		// GET DP SUPPLIER
		/*$sqlTOTDP	= "SELECT SUM(DP_AMOUNT - DP_AMOUNT_USED) AS TOT_AMOUNT_DP
						FROM tbl_dp_header
						WHERE DP_STAT = '3' AND SPLCODE = '$CB_PAYFOR'";
		$resTOTDP	= $this->db->query($sqlTOTDP)->result();
		foreach($resTOTDP as $rowTOTDP):
			$TOT_AMOUNT_DP	= $rowTOTDP->TOT_AMOUNT_DP;
		endforeach;*/
		$sqlTOTDP	= "SELECT SUM(A.DP_AMOUNT - A.DP_AMOUNT_USED) AS TOT_AMOUNT_DP FROM tbl_dp_header A
						INNER JOIN tbl_bp_detail B ON A.DP_NUM = B.CBD_DOCNO
							AND B.CB_CATEG = 'DP'
						INNER JOIN tbl_bp_header C ON B.CB_NUM = C.CB_NUM
							AND C.CB_PAYFOR = '$CB_PAYFOR'
						WHERE A.DP_AMOUNT > A.DP_AMOUNT_USED";
		$resTOTDP	= $this->db->query($sqlTOTDP)->result();
		foreach($resTOTDP as $rowTOTDP):
			$TOT_AMOUNT_DP	= $rowTOTDP->TOT_AMOUNT_DP;
		endforeach;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - CB_TOTAM
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
				$APPROVE_AMOUNT 	= $CB_TOTAM;
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
	?>

	<style>
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}

		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">	
	        <form class="form-horizontal" name="frm1" id="frm1" method="post" action="" style="display:none">
	        	<input type="text" name="CB_CURRIDA" id="CB_CURRIDA" value="<?php echo $SelCurr; ?>" />
	        	<input type="text" name="AccSelected" id="AccSelected" value="<?php echo $selAccount; ?>" />
	        	<input type="text" name="SPLSelected" id="SPLSelected" value="<?php echo $CB_PAYFOR; ?>" />
	        	<input type="text" name="SRCSelected" id="SRCSelected" value="<?php echo $CB_SOURCE; ?>" />
	        	<input type="text" name="SRCNumber" id="SRCNumber" value="<?php echo $CB_SOURCENO; ?>" />
	            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
	        </form>
	        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
	            <table>
	                <tr>
	                    <td>
	                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
	                        <input type="TEXT" name="CB_TYPE" id="CB_TYPE" value="<?php echo $CB_TYPE; ?>">
	                        <input type="TEXT" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
	                        <input type="TEXT" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
	                        <input type="TEXT" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
	                        <input type="TEXT" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
	                        <input type="TEXT" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
	                        <input type="TEXT" name="CBDate" id="CBDate" value="">
	                    </td>
	                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
	                </tr>
	            </table>
	        </form>
		    
		    <div class="row">
			    <form name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
		            <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		            <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		            <input type="Hidden" name="rowCount" id="rowCount" value="0">
	                <div class="col-md-6">
	                    <div class="box box-success">
	                        <div class="box-header with-border" style="display: none;">
	                            <h3 class="box-title"><?php echo $DetInfo; ?></h3>
	                        </div>
	                        <div class="box-body">
	                        	<div class="col-sm-12"> <!-- CB_NUM -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Code; ?></label>
			                            <input type="hidden" name="CB_NUM" id="CB_NUM" value="<?php echo $DocNumber; ?>">
			                      		<input type="text" class="form-control" name="CB_CODE" id="CB_CODE" value="<?php echo $CB_CODE; ?>" readonly />
	                                </div>
	                            </div>
	                        	<div class="col-sm-12"> <!-- CB_DATE -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Date; ?></label>
	                                    <div class="input-group date">
			                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                            	<input type="text" name="CB_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $CB_DATE; ?>" style="width:100px" readonly>
			                            </div>
	                                </div>
	                            </div>
	                        	<div class="col-sm-4"> <!-- CB_SOURCE -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $SourceDocument; ?></label>
	                                    <select name="CB_SOURCE1" id="CB_SOURCE1" class="form-control select2" onChange="selectAccount(this.value)" disabled>
			                                <option value=""> --- </option>
			                                <option value="PINV" <?php if($CB_SOURCE == 'PINV'){ ?> selected <?php } ?>>Faktur</option>
			                                <option value="DP" <?php if($CB_SOURCE == 'DP'){ ?> selected <?php } ?>><?php echo $DownPayment; ?></option>
			                                <!-- <option value="GEJ" <?php if($CB_SOURCE == 'GEJ'){ ?> selected <?php } ?> style="display:none">General Journal</option> -->
			                                <option value="OTH" <?php if($CB_SOURCE == 'OTH'){ ?> selected <?php } ?>><?php echo $Others; ?></option>
			                            </select>
			                      		<input type="hidden" class="form-control" name="CB_SOURCE" id="CB_SOURCE" value="<?php echo $CB_SOURCE; ?>"/>
	                                </div>
	                            </div>
	                        	<div class="col-sm-8"> <!-- CB_PAYFOR -->
			                        <?php if($CB_SOURCENO == '') { ?>
	                                    <div class="form-group">
	                                        <label for="exampleInputEmail1"><?php echo $PaymentFor; ?></label>
	                                        <select name="CB_PAYFOR1" id="CB_PAYFOR1" class="form-control select2" onChange="selectAccount(this.value)" <?php if($CB_SOURCE == 'GEJ') { ?> disabled <?php } ?> disabled>
				                                <option value=""> --- </option>
				                                <?php
				                                    foreach($vwSPL as $row) :
				                                         $SPLCODE1	= $row->SPLCODE;
				                                         $SPLDESC1	= $row->SPLDESC;
				                                        ?>
				                                            <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $CB_PAYFOR) { ?> selected <?php } ?>><?php echo "$SPLCODE1 - $SPLDESC1"; ?></option>
				                                        <?php
				                                    endforeach;
				                                ?>
				                            </select>
	                                    </div>
			                        <?php } else { 
			                        	if($CB_PAYFOR == 'none')
											$CB_PAYFOR	= '';
			                        	?>
			                        	<input type="text" class="form-control" name="CB_PAYFOR1" id="CB_PAYFOR1" value="<?php echo $CB_PAYFOR; ?>" >
			                        <?php } ?>
			                      	<input type="hidden" class="form-control" name="CB_PAYFOR" id="CB_PAYFOR" value="<?php echo $CB_PAYFOR; ?>"/>
			                    </div>
	                        	<div class="col-sm-12"> <!-- NOTES -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Notes; ?></label>
	                                    <textarea name="CB_NOTES" class="form-control" id="CB_NOTES" cols="30" style="height: 80px"><?php echo $CB_NOTES; ?></textarea>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="box box-warning">
	                        <div class="box-header with-border">
	                            <h3 class="box-title">Sumber Pembayaran</h3>
	                        </div>
	                        <div class="box-body">
	                        	<div class="col-sm-12"> <!-- CB_SOURCENO -->
	                            	<?php if($CB_SOURCE == "") { ?>
	                                    <div class="form-group">
	                                        <label for="exampleInputEmail1"><?php echo $Doc_Number; ?></label>
	                                    </div>
	                                	<input type="text" class="form-control" name="CB_SOURCENO" id="CB_SOURCENO" value="<?php echo $CB_SOURCENO; ?>" onClick="selectSources()" >
	                            	<?php } else { ?>
	                                	<input type="hidden" class="form-control" name="CB_SOURCENO" id="CB_SOURCENO" value="<?php echo $CB_SOURCENO; ?>" onClick="selectSources()" >
	                            	<?php } ?>
	                            </div>
			                    <?php
			                    	$url_selGEJ_NUM1	= site_url('c_finance/c_bp0c07180851/sgejbp0c07180851/?id='.$this->url_encryption_helper->encode_url($DocNumber));
								?>
			                    <script>
									var url1 = "<?php echo $url_selGEJ_NUM1;?>";
									function selectSources()
									{
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
		                        </script>
	                        	<div class="col-sm-12"> <!-- CB_CURRID, Acc_ID -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $BankAccount; ?></label>
	                                    <select name="CB_CURRID" id="CB_CURRID" class="form-control" style="display: none;" onChange="selectAccount(this.value)">
			                                <option value="IDR" <?php if($SelCurr == 'IDR'){ ?> selected <?php } ?>>IDR</option>
			                                <option value="USD" <?php if($SelCurr == 'USD'){ ?> selected <?php } ?>>USD</option>
			                            </select>
			                            <select name="CB_ACCID1" id="CB_ACCID1" class="form-control select2" onChange="selectAccount(this.value)" disabled>
			                            	<option value="0"> --- </option>
			                                <?php echo $i = 0;
				                                if($countAcc > 0)
				                                {
													foreach($vwAcc as $row) :
														$Acc_ID					= $row->Acc_ID;
														$Account_Category		= $row->Account_Category;
														$Account_Number 		= $row->Account_Number;
														$Account_Name 			= cut_text ($row->Account_Name, 50);
														$Base_OpeningBalance 	= $row->Base_OpeningBalance;
														$Base_Debet 			= $row->Base_Debet;
														$Base_Kredit 			= $row->Base_Kredit;
														$BalAcc					= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
														if($BalAcc <= 0)
															$disable = 1;
														else
															$disable = 0;
														?>
															<option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $CB_ACCID){ ?> selected <?php } if($disable == 1) { ?> style="color:#999" disabled <?php } else { ?> style="color:#00F; font-weight:bold" <?php } ?>>
																<?php echo "$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
															</option>
													 	<?php
													endforeach;
				                                }
												if($CB_SOURCE == 'OTH')
												{
													$sqlx	 = "SELECT DISTINCT
																	B.Acc_ID, 
																	B.Account_Number, 
																	B.Account_Nameen as Account_Name,
																	B.Account_Category,
																	B.Account_Class,			
																	B.currency_ID,
																	B.Base_OpeningBalance,
																	B.Base_Debet,
																	B.Base_Kredit
																FROM tbl_chartaccount B
																WHERE B.isLast = '1'
																	AND B.PRJCODE = '$PRJCODE'
																	AND B.Currency_id = '$SelCurr'
																	AND B.Account_Number = '$GEJ_ACCID'
																Order by B.Account_Category, B.Account_Number";
													$resx	= $this->db->query($sqlx)->result();
													foreach($resx as $rowX) :
														$Acc_ID					= $rowX->Acc_ID;
														$Account_Category		= $rowX->Account_Category;
														$Account_Number 		= $rowX->Account_Number;
														$Account_Name 			= $rowX->Account_Name;
														$Base_OpeningBalance 	= $rowX->Base_OpeningBalance;
														$Base_Debet 			= $rowX->Base_Debet;
														$Base_Kredit 			= $rowX->Base_Kredit;
														$BalAcc					= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
														if($BalAcc <= 0)
															$disable1 = 1;
														else
															$disable1 = 0;
														?>
														<option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $CB_ACCID){ ?> selected <?php } if($disable1 == 1) { ?> style="color:#999" disabled <?php } else { ?> style="color:#00F; font-weight:bold" <?php } ?>>
															<?php echo "$Account_Category-$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
														</option>
													 <?php
													 endforeach;
												}
			                                ?>
			                            </select>
			                            <input type="hidden" class="form-control" name="CB_ACCID" id="CB_ACCID" value="<?php echo $CB_ACCID; ?>"/>
				                        <script>
				                            function selectAccount()
				                            {
												CB_ACCID	= document.getElementById('CB_ACCID').value;
												document.getElementById('AccSelected').value = CB_ACCID;
												CB_CURRIDA	= document.getElementById('CB_CURRID').value;
												document.getElementById('CB_CURRIDA').value = CB_CURRIDA;
												SPLSelected	= document.getElementById('CB_PAYFOR').value;
												document.getElementById('SPLSelected').value = SPLSelected;
												SRCSelected	= document.getElementById('CB_SOURCE').value;
												document.getElementById('SRCSelected').value = SRCSelected;
												SRCNumber	= document.getElementById('CB_SOURCENO').value;
												document.getElementById('SRCNumber').value = SRCNumber;
												
				                                document.frm1.submit1.click();
				                            }
				                        </script>
	                                </div>
	                            </div>
	                            <?php
	                            	// CEK SALDO AKUN KAS BANK
			                            $sql1C 		= "tbl_chartaccount A
			                                            INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
			                                            WHERE
															A.Account_Number = '$CB_ACCID'
															AND A.PRJCODE = '$PRJCODE'
			                                            Order by A.Account_Category, A.Account_Number";
			                            $retSQL1C 	= $this->db->count_all($sql1C);
			                            
			                            if($retSQL1C > 0)
			                            {
			                                $sql1 = "SELECT A.Base_OpeningBalance, A.Base_Debet, A.Base_Kredit
			                                        FROM tbl_chartaccount A
			                                        INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
			                                        WHERE
														A.Account_Number = '$CB_ACCID'
														AND A.PRJCODE = '$PRJCODE'
			                                        Order by A.Account_Category, A.Account_Number";
			                                $retSQL1 	= $this->db->query($sql1)->result();
			                                foreach($retSQL1 as $row1):
			                                    $opBal		= $row1->Base_OpeningBalance;
			                                    $BaseDebet	= $row1->Base_Debet;
			                                    $BaseKredit	= $row1->Base_Kredit;
			                                endforeach;
			                            }
			                            else
			                            {
			                                $opBal		= 0;
			                                $BaseDebet	= 0;
			                                $BaseKredit	= 0;
			                            }
									
									// CEK TOTAL YANG AKAN DIBAYAR
			                            $GTInvoicePayX	= 0;
										if($CB_SOURCENO == '' && $task == 'edit' && $CB_STAT == 3)
			                            {
			                                $sqlDET	= "SELECT A.JournalH_Code, A.CB_NUM, A.CB_CODE, A.CBD_DOCNO, A.CBD_DOCREF, 
															A.CBD_DESC, A.CBD_DEBCRED, A.CBD_ACCID,
															A.INV_AMOUNT, A.INV_AMOUNT_PPN, A.Amount, A.AMOUNT_PAID_PPN, 
															A.INV_AMOUNT_PPN, A.INV_AMOUNT_PPH, A.INV_AMOUNT_Ret,
															A.Notes, A.CB_CATEG
														FROM tbl_bp_detail A
															INNER JOIN tbl_bp_header B ON A.JournalH_Code = B.JournalH_Code
														WHERE A.JournalH_Code = '$JournalH_Code'";
			                                // count data
			                                    $resultCount = $this->db->where('JournalH_Code', $JournalH_Code);
			                                    $resultCount = $this->db->count_all_results('tbl_bp_detail');
			                                // End count data
			                                $result = $this->db->query($sqlDET)->result();
			                                if($resultCount > 0)
			                                {
												$TOTInvoiceX	= 0;
												$TOTPPnX		= 0;
												$TOTPPhX		= 0;
												$TOTRetX		= 0;
												$TOTDiscX		= 0;
												$GTInvoiceX		= 0;
												$TOTInvoicePayX	= 0;
												$TOTPPnPayX		= 0;
												$GTInvoicePayX	= 0;
			                                    foreach($result as $row) :
			                                        $INV_AMOUNT1		= $row->INV_AMOUNT;
			                                        $INV_AMOUNT_PPN1	= $row->INV_AMOUNT_PPN;
			                                        $INV_AMOUNT_PPH1	= $row->INV_AMOUNT_PPH;
			                                        $INV_AMOUNT_Ret1	= $row->INV_AMOUNT_Ret;
			                                        $Amount1 			= $row->Amount;
			                                        $AMOUNT_PAID_PPN1	= $row->AMOUNT_PAID_PPN;
													
													$TOTInvoiceX	= $TOTInvoiceX + $INV_AMOUNT1;
													$TOTPPnX		= $TOTPPnX + $INV_AMOUNT_PPN1;
													$TOTPPhX		= $TOTPPhX + $INV_AMOUNT_PPH1;
													$TOTRetX		= $TOTRetX + $INV_AMOUNT_Ret1;
													$GTInvoiceX		= $GTInvoiceX + $INV_AMOUNT1 + $INV_AMOUNT_PPN1 - $INV_AMOUNT_PPH1 - $INV_AMOUNT_Ret1;
												
													$TOTInvoicePayX	= $TOTInvoicePayX + $Amount1;
													$TOTPPnPayX		= $TOTPPnPayX + $AMOUNT_PAID_PPN1;
													$GTInvoicePayX	= $GTInvoicePayX + $Amount1 + $AMOUNT_PAID_PPN1;
												endforeach;
											}
										}

		                            $ActBal	= $opBal + $BaseDebet - $BaseKredit + $GTInvoicePayX;

		                            // TOTAL JUMLAH PEMBAYARAN BERSTATUS RESERVE; dari Bank Payment yang belum di Approve dan tidak reject
										$CB_TOTAM		= 0;
										$CB_TOTAM_PPN	= 0;
			                            $sql2 			= "SELECT SUM(CB_TOTAM) AS Tot_AM, SUM(CB_TOTAM_PPN) AS Tot_AMPPn
															FROM tbl_bp_header
															WHERE 
																CB_STAT IN (2) AND 
																CB_CURRID = '$SelCurr'
																AND CB_ACCID = '$CB_ACCID'
																AND JournalH_Code != '$JournalH_Code'";
			                            $retSQL2 	= $this->db->query($sql2)->result();
			                            foreach($retSQL2 as $row2):
			                                $CB_TOTAM		= $row2->Tot_AM;
			                                $CB_TOTAM_PPN	= $row2->Tot_AMPPn;
			                            endforeach;
			                            //$TotReserve	= $CB_TOTAM + $CB_TOTAM_PPN;
			                            $TotReserve		= $CB_TOTAM;

		                            // Total Ammount : Total nilai yang saat ini akan dibayarkan
		                            	$TotAmmount	= 0;
		                            
		                            // Total Remain
		                            	$TotRemain	= $ActBal - $TotReserve - $TotAmmount;				 
		                        ?>
	                        	<div class="col-sm-12"> <!-- AMOUNT -->
			                        <div class="row">
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $ActualBalance; ?></label>
	                                            <div class="form-group has-primary">
	                                            	<a href="" class="btn btn-primary btn-xs">
			                                            <?php echo number_format($ActBal, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $Reserved; ?></label>
	                                            <div class="form-group has-error">
	                                            	<a href="" class="btn btn-danger btn-xs">
			                                            <?php echo number_format($TotReserve, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $Amount; ?></label>
	                                            <div class="form-group has-warning">
	                                            	<a href="" class="btn btn-warning btn-xs">
			                                            <?php echo number_format($TotAmmount, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label for="exampleInputPassword1"><?php echo $Remain; ?></label>
	                                            <div class="form-group has-success">
	                                            	<a href="" class="btn btn-success btn-xs">
			                                            <?php echo number_format($TotRemain, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                            <input type="hidden" name="TotRemAccount" id="TotRemAccount" value="<?php echo $TotRemain; ?>" class="form-control" style="max-width:80px; text-align:right">
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        	<div class="col-sm-12"> <!-- STATUS -->
	                                <div class="form-group">
	                                    <label for="exampleInputEmail1"><?php echo $Status; ?></label>
			                        	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $CB_STAT; ?>" />
										<?php
			                                if($canApprove == 1 && $ISAPPROVE == 1)
			                                {
			                                ?>
			                                    <select name="CB_STAT" id="CB_STAT" class="form-control select2" >
			                                        <option value=""> --- </option>
			                                        <option value="3"<?php if($CB_STAT == 3) { ?> selected <?php } ?>>Approve</option>
			                                        <option value="4"<?php if($CB_STAT == 4) { ?> selected <?php } ?>>Revise</option>
			                                        <option value="5"<?php if($CB_STAT == 5) { ?> selected <?php } ?>>Reject</option>
			                                        <!-- <option value="6"<?php if($CB_STAT == 6) { ?> selected <?php } ?>>Close</option> -->
			                                        <option value="7"<?php if($CB_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
			                                    </select>
			                                <?php
			                                }
											elseif($canApprove == 0)
											{
												$descNotes	= "Anda tidak bisa menyetujui dokumen ini, dikarenakan tidak memiliki otorisasi untuk melakukan persetujuan. Silahkan hubungi Admin.";
												?>
													<br>
			                                        <a href="" class="btn btn-danger btn-xs">
			                                            <?php echo $descApp; ?>
			                                        </a>
			                                    <?php
											}
			                                elseif($ISCREATE == 1)
			                                {
												$descNotes	= "Anda memiliki otorisasi untuk membuat dokumen, namun Anda tidak memiliki otorisasi untuk menyetujui dokumen ini.";
			                                    if($CB_STAT == 1 || $CB_STAT == 2)
			                                    {
			                                        ?>
			                                            <select name="CB_STAT" id="CB_STAT" class="form-control select2" style="max-width:120px" >
			                                                <option value="1"<?php if($CB_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                                <option value="2"<?php if($CB_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                                <option value="6"<?php if($CB_STAT == 6) { ?> selected <?php } ?> style="display:none">Close</option>
			                                                <option value="7"<?php if($CB_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
			                                            </select>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <select name="CB_STAT" id="CB_STAT" class="form-control select2" style="max-width:120px" >
			                                                <option value="3"<?php if($CB_STAT == 3) { ?> selected <?php } ?>>Approve</option>
			                                                <option value="4"<?php if($CB_STAT == 4) { ?> selected <?php } ?>>Revise</option>
			                                                <option value="5"<?php if($CB_STAT == 5) { ?> selected <?php } ?>>Reject</option>
			                                                <option value="6"<?php if($CB_STAT == 6) { ?> selected <?php } ?>>Close</option>
			                                                <option value="7"<?php if($CB_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
			                                            </select>
			                                        <?php
			                                    }
			                                }
			                            ?>
	                                </div>
	                            </div>
								<script>
			                        function selStat(thisValue)
			                        {
			                        	var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
			                            if(thisValue == 9)
			                            {
			                                document.getElementById('tblSave').style.display 		= '';
			                                document.getElementById('labVoid').style.display 		= '';
			                                document.getElementById('VOID_REASON').style.display 	= '';
			                            }
			                            else if(thisValue == 2)
			                            {
			                            	if(STAT_BEFORE == 4)
			                                	document.getElementById('tblSave').style.display 		= '';
			                            }
			                            else
			                            {
			                                document.getElementById('tblSave').style.display 		= 'none';
			                                document.getElementById('labVoid').style.display 		= 'none';
			                                document.getElementById('VOID_REASON').style.display 	= 'none';
			                            }
			                        }
			                    </script>
	                        	<div class="col-sm-12">
				                    <?php if($CB_SOURCE != 'DP') { ?>
				                        <div class="row"> <!-- DP -->
	                                        <div class="col-xs-6">
	                                            <div class="form-group">
	                                                <label for="exampleInputPassword1"><?php echo $DPAkum; ?></label><br>
	                                                <input type="text" name="TOT_AMOUNT_DPX" id="TOT_AMOUNT_DPX" value="<?php echo number_format($TOT_AMOUNT_DP, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" readonly >
	                                            </div>
	                                        </div>
	                                        <div class="col-xs-6">
	                                            <div class="form-group">
	                                                <label for="exampleInputPassword1"><?php echo "-"; ?></label><br>
	                                                <input type="text" name="CB_DPAMOUNTX" id="CB_DPAMOUNTX" value="<?php echo number_format($CB_DPAMOUNT, $decFormat); ?>" class="form-control" style="text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAMOUNT_DP(this);" readonly>
													<input type="hidden" name="CB_DPAMOUNT" id="CB_DPAMOUNT" value="<?php echo $CB_DPAMOUNT; ?>" class="form-control" style="min-width:130px; max-width:200px; text-align:right" >
	                                            </div>
	                                        </div>
	                                    </div>
				                    <?php } else { ?>
			                            <input type="hidden" name="CB_DPAMOUNTX" id="CB_DPAMOUNTX" value="<?php echo number_format($CB_DPAMOUNT, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAMOUNT_DP(this);" >
			                            <input type="hidden" name="CB_DPAMOUNT" id="CB_DPAMOUNT" value="<?php echo $CB_DPAMOUNT; ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" >
			                        <?php } ?>
			                    </div>
			                    <select name="CB_RECTYPE" id="CB_RECTYPE" class="form-control" style="max-width:120px; display: none;">
	                                <option value="PRJ" <?php if($CB_RECTYPE == 'PRJ'){ ?> selected <?php } ?>><?php echo $Project; ?></option>
	                                <option value="NPRJ" <?php if($CB_RECTYPE == 'NPRJ'){ ?> selected <?php } ?>>Non <?php echo $Project; ?></option>
	                            </select><br>
			                    <?php
									$colPAYERID	= "$CB_PAYFOR~$PAGEFORM~$PRJCODE";
									$selSource	= site_url('c_finance/c_bp0c07180851/pall180c2cinv/?id='.$this->url_encryption_helper->encode_url($colPAYERID));
								?>
	                        	<div class="col-sm-12">
									<?php if($CB_SOURCE != 'GEJ') { ?>
	                                    <div class="form-group"> <!-- CHOOSE DOC. -->
	                                        <div class="form-group has-error" style="display: none;">
		                                        <span class="help-block" style="font-style: italic;">Silahkan pilih dokumen yang akan dibayar</span>
		                                    </div>
	                                        <button class="btn btn-warning" type="button" onClick="selectitem();" style="display: none;" <?php if($CB_STAT != 1 && $CB_STAT != 4) { ?>disabled <?php } ?>>
				                        		<i class="fa fa-folder-open"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
				                        	</button>

				                        	<?php
				                        		if(($CB_PAYFOR != '') && ($CB_STAT == 1 || $CB_STAT == 4))
				                        		{
				                        			?>
														<button class="btn btn-warning" type="button" onClick="selectitem();">
															<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
														</button>
														<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addTTK" id="btnModal" style="display: none;">
							                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
							                        	</a>
				                        			<?php
				                        		}
				                        	?>
	                                    </div>
									<?php } ?>
								</div>
			                    <script>
									var url = "<?php echo $selSource;?>";
									function selectitem()
									{
										CB_PAYFOR	= document.getElementById('CB_PAYFOR').value;
										if(CB_PAYFOR == '')
										{
											swal('<?php echo $alert4; ?>',
											{
												icon: "warning",
											});
											document.getElementById('CB_PAYFOR').focus();
											return false;
										}

										SEL_ACC	= document.getElementById('CB_ACCID').value;
										if(SEL_ACC == 0)
										{
											swal('<?php echo $selSrcPay; ?>',
											{
												icon: "warning",
											});
											document.getElementById('CB_ACCID').focus();
											return false;
										}
										
										/*title = 'Select Item';
										w = 1200;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/

										document.getElementById('btnModal').click();
									}

									/* DITUTUP SEMENTARA : 02-11-2021
										function chgAMOUNT_DP(thisVal)
										{
											var decFormat		= document.getElementById('decFormat').value;
											var TOT_AMOUNT_DPX	= eval(document.getElementById('TOT_AMOUNT_DPX')).value.split(",").join("");
											var CB_DPAMOUNT		= eval(thisVal).value.split(",").join("");
											if(CB_DPAMOUNT > TOT_AMOUNT_DPX)
											{
												swal('<?php echo $alert6; ?>',
												{
													icon: "warning",
												})
												.then(function()
												{
													document.getElementById('CB_DPAMOUNTX').focus();
													document.getElementById('CB_DPAMOUNTX').value = '0.00';
												});
												return false;
											}
											document.getElementById('CB_DPAMOUNT').value	= CB_DPAMOUNT;
											document.getElementById('CB_DPAMOUNTX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(CB_DPAMOUNT)), decFormat));
						
											var totRow = document.getElementById('totalrow').value;
											var TOTInvoice_p 	= 0;
											var TOTPPn_p		= 0;
											var TOTDisc_p		= 0;
											var TOTDiscG_p		= 0;
											var GTInvoice_p1	= 0;
											
											if(totRow > 0)
											{
												for(i=1;i<=totRow;i++)
												{
													var element = document.getElementById('data'+i+'INV_AMOUNT');
													if (element != null)
													{
														var INV_AMOUNT			= document.getElementById('data'+i+'INV_AMOUNT').value;
														var INV_AMOUNT_p		= document.getElementById('data'+i+'Amount').value;
													}
													else
													{
														var INV_AMOUNT			= 0;
														var INV_AMOUNT_p		= 0;
													}
													
													var element = document.getElementById('data'+i+'PPhAmount');
													if (element != null)
													{
														var PPhAmount		= document.getElementById('data'+i+'PPhAmount').value;
														var INV_AMOUNT_PPH	= document.getElementById('data'+i+'INV_AMOUNT_PPH').value;
													}
													else
													{
														var PPhAmount		= 0;
														var INV_AMOUNT_PPH	= 0;
													}
													
													var element = document.getElementById('data'+i+'RetAmount');
													if (element != null)
													{
														var RetAmount		= document.getElementById('data'+i+'INV_AMOUNT_Ret').value;
														var INV_AMOUNT_Ret	= document.getElementById('data'+i+'INV_AMOUNT_Ret').value;
													}
													else
													{
														var RetAmount		= 0;
														var INV_AMOUNT_Ret	= 0;
													}

													var element = document.getElementById('data'+i+'CBD_AMOUNT_DISC');
													if (element != null)
													{
														var CBD_AMOUNT_DISC		= document.getElementById('data'+i+'CBD_AMOUNT_DISC').value;
														var INV_AMOUNT_DISC	= document.getElementById('data'+i+'INV_AMOUNT_DISC').value;
													}
													else
													{
														var CBD_AMOUNT_DISC		= 0;
														var INV_AMOUNT_DISC	= 0;
													}

													TOTInvoicex_p		= parseFloat(INV_AMOUNT_p);
													TOTInvoice_p		= parseFloat(TOTInvoice_p) + parseFloat(INV_AMOUNT_p);
													//TOTPPn_p			= parseFloat(TOTPPn_p) + parseFloat(INV_AMOUNT_PPN_p);	
													TOTDisc_p			= parseFloat(TOTDisc_p) + parseFloat(CBD_AMOUNT_DISC);		
													GTInvoice_p1		= parseFloat(GTInvoice_p1) + parseFloat(TOTInvoicex_p);
												}
											}
											
											TOTDisc_p1			= parseFloat(CB_DPAMOUNT) + parseFloat(TOTDisc_p);
											GTInvoice_p			= parseFloat(TOTInvoice_p) - parseFloat(TOTDisc_p1);
											GTInvoice_p			= parseFloat(TOTDisc_p1);
											
											document.getElementById('TOTDisc1_p').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTDisc_p1)),decFormat));
											document.getElementById('GTInvoice1_p').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat((GTInvoice_p)),decFormat));
											document.getElementById('GTInPay').value			= parseFloat(GTInvoice_p);
										}
									*/
	                       		</script>
	                        </div>
	                    </div>
	                </div>

	                <div class="col-sm-12">
	                	<div class="box box-primary">
	                        <div class="box-header with-border">
	                            <h3 class="box-title center"><?php echo $billList; ?></h3>
	                        </div>
	                        <div class="box-body">
					            <div class="search-table-outter">
					                <table id="tbl" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                            <tr style="background:#CCCCCC">
			                              	<th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
			                              	<th width="10%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $InvoiceNo; ?> </th>
			                              	<th width="30%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Description; ?> </th>
			                              	<th colspan="2" style="text-align:center"><?php echo $InvoiceAmount; ?> </th>
			                              	<th colspan="5" style="text-align:center"><?php echo $Payment; ?> </th>
			                            </tr>
			                            <tr style="background:#CCCCCC">
			                              	<th width="10%" style="text-align:center;"><?php echo $Amount; ?> </th>
			                              	<th width="10%" style="text-align:center;"><?php echo $Paid; ?> </th>
			                              	<th width="10%" style="text-align:center; display:none">Disc.</th>
			                              	<th width="10%" style="text-align:center;"><?php echo $Amount; ?> </th>
			                              	<th width="10%" style="text-align:center; display:none">PPh</th>
			                              	<th width="10%" style="text-align:center; display:none">&nbsp;</th>
			                              	<th width="10%" style="text-align:center;" nowrap>DP</th>
			                            </tr>
			                            <?php
											$CB_TOTAM		= 0;
											$CB_TOTAM_PPN	= 0;
											$CB_TOTAM_DISC	= 0;
											$CB_TPAID 		= 0;
											$CB_TPAID_DISC 	= 0;
											
				                            if($CB_SOURCENO == '' && $task == 'edit')
				                            {

				                                $sqlDET	= "SELECT A.*, B.CB_SOURCE, B.CB_PAYFOR FROM tbl_bp_detail A
																INNER JOIN tbl_bp_header B ON A.JournalH_Code = B.JournalH_Code
															WHERE A.JournalH_Code = '$JournalH_Code'";
				                                // count data
				                                    $resultCount = $this->db->where('JournalH_Code', $JournalH_Code);
				                                    $resultCount = $this->db->count_all_results('tbl_bp_detail');
				                                // End count data
				                                $result = $this->db->query($sqlDET)->result();
				                                $i		= 0;
				                                $j		= 0;
												
				                                if($resultCount > 0)
				                                {
				                                    foreach($result as $row) :
				                                        $currentRow  	= ++$i;
				                                        $JournalH_Code 	= $row->JournalH_Code;
				                                        $CB_SOURCE 		= $row->CB_SOURCE;
				                                        $CB_PAYFOR 		= $row->CB_PAYFOR;
				                                        $CB_NUM 		= $row->CB_NUM;
				                                        $CB_CODE 		= $row->CB_CODE;
				                                        $CB_CATEG		= $row->CB_CATEG;
				                                        $CBD_DOCNO		= $row->CBD_DOCNO;
				                                        $CBD_DOCCODE	= $row->CBD_DOCCODE;
				                                        $CBD_DOCREF		= $row->CBD_DOCREF;
				                                        $CBD_DESC		= $row->CBD_DESC;
				                                        $CBD_DEBCRED 	= $row->CBD_DEBCRED;
				                                        $CBD_ACCID 		= $row->CBD_ACCID;
				                                        $INV_AMOUNT		= $row->INV_AMOUNT;
				                                        $INV_AMOUNT_PPN	= $row->INV_AMOUNT_PPN;
				                                        $INV_AMOUNT_PPH	= $row->INV_AMOUNT_PPH;
				                                        $INV_AMOUNT_DISC= $row->INV_AMOUNT_DISC;
				                                        $AMOUNT_PAID 	= $row->AMOUNT_PAID;
				                                        $AMOUNT_DP		= $row->AMOUNT_DP;
				                                        $CBD_AMOUNT 	= $row->CBD_AMOUNT;
				                                        $CBD_AMOUNT_DISC= $row->CBD_AMOUNT_DISC;
				                                        $Notes			= $row->Notes;
														$INV_CODE		= $CBD_DOCREF;

														$INV_AMOUNT_REM = $INV_AMOUNT - $AMOUNT_PAID;

														$INV_DATEV 		= "";
														$SPLCODE 		= "";
														$INV_NOTES 		= "";
														if($CB_SOURCE == 'DP')
														{
							                                $s_INVH			= "SELECT DP_DATE, SPLCODE, DP_NOTES
							                                					FROM tbl_dp_header WHERE DP_NUM = '$CBD_DOCNO' LIMIT 1";
							                                $r_INVH 		= $this->db->query($s_INVH)->result();
						                                    foreach($r_INVH as $rw_INVH) :
						                                        $DP_DATE 	= $rw_INVH->DP_DATE;
						                                        $INV_DATEV	= strftime('%d %b %Y', strtotime($DP_DATE));
						                                        $SPLCODE 	= $rw_INVH->SPLCODE;
						                                        $INV_NOTES 	= $rw_INVH->DP_NOTES;
						                                    endforeach;
														}
														else
														{
							                                $s_INVH			= "SELECT INV_DATE, SPLCODE, INV_NOTES
							                                					FROM tbl_pinv_header WHERE INV_NUM = '$CBD_DOCNO' LIMIT 1";
							                                $r_INVH 		= $this->db->query($s_INVH)->result();
						                                    foreach($r_INVH as $rw_INVH) :
						                                        $INV_DATE 	= $rw_INVH->INV_DATE;
						                                        $INV_DATEV	= strftime('%d %b %Y', strtotime($INV_DATE));
						                                        $SPLCODE 	= $rw_INVH->SPLCODE;
						                                        $INV_NOTES 	= $rw_INVH->INV_NOTES;
						                                    endforeach;
						                                }
															
				                                        /*if ($j==1) {
				                                            echo "<tr class=zebra1>";
				                                            $j++;
				                                        } else {
				                                            echo "<tr class=zebra2>";
				                                            $j--;
				                                        }*/
				                                        ?>
				                                        <tr>
					                                        <td style="text-align:left"> <!-- CB_NUM, CB_CODE -->
																<?php
						                                            if($CB_STAT == 1)
						                                            {
						                                                ?>
						                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>); countAllInvoice();" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
						                                                <?php
						                                            }
						                                            else
						                                            {
						                                                echo "$currentRow.";
						                                            }
					                                            ?>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>JournalH_Code" name="data[<?php echo $currentRow; ?>][JournalH_Code]" value="<?php echo $CB_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CB_NUM" name="data[<?php echo $currentRow; ?>][CB_NUM]" value="<?php echo $CB_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CB_CODE" name="data[<?php echo $currentRow; ?>][CB_CODE]" value="<?php echo $CB_CODE; ?>" class="form-control" style="max-width:300px;" readonly>
					                                      	</td>
					                                        
					                                      	<td style="text-align:left" nowrap> <!-- CBD_DOCNO, CBD_DOCREF, PRJCODE -->
																<?php echo $CBD_DOCCODE; ?><br>
																<strong><i class='fa fa-calendar margin-r-5'></i> <?=$Date?> </strong>
														  		<div style='margin-left: 18px'>
															  		<p class='text-muted'>
															  			<?=$INV_DATEV?>
															  		</p>
															  	</div>
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCNO" name="data[<?php echo $currentRow; ?>][CBD_DOCNO]" value="<?php echo $CBD_DOCNO; ?>" class="form-control" style="max-width:300px;" >
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>CBD_DOCREF" name="data[<?php echo $currentRow; ?>][CBD_DOCREF]" value="<?php echo $CBD_DOCREF; ?>" class="form-control" style="max-width:300px;" >
					                                            <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:300px;" readonly>
					                                        </td>
					                                        
					                                      	<td style="text-align:left"> <!-- CBD_DESC, CB_CATEG -->
																<?php echo "$Description. $INV_NOTES"; ?><br>
																<strong><i class='fa fa-users margin-r-5'></i><?=$SPLCODE?> </strong>
														  		<div style='margin-left: 18px'>
															  		<p class='text-muted'>
															  			<?=$CBD_DESC?>
															  		</p>
															  	</div>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CBD_DESC]" id="data<?php echo $currentRow; ?>CBD_DESC" value="<?php echo $CBD_DESC; ?>" class="form-control" style="max-width:600px;" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CB_CATEG]" id="data<?php echo $currentRow; ?>CB_CATEG" value="<?php echo $CB_CATEG; ?>" class="form-control" style="max-width:600px;" >
					                                       	</td>
					                                        
					                                        <td style="text-align:right" > <!-- INV_AMOUNT -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
																	<input type="text" name="INV_AMOUNT<?php echo $currentRow; ?>" id="INV_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($INV_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:150px; text-align:right" title="Nilai Total Inv" disabled >
																<?php } else { ?>
																	<?php echo number_format($INV_AMOUNT, $decFormat); ?>
																	<input type="hidden" name="INV_AMOUNT<?php echo $currentRow; ?>" id="INV_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($INV_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:150px; text-align:right" title="Nilai Total Inv" disabled >
																<?php } ?>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][INV_AMOUNT]" id="data<?php echo $currentRow; ?>INV_AMOUNT" size="10" value="<?php echo $INV_AMOUNT; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Inv" >
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][INV_AMOUNT_PPN]" id="data<?php echo $currentRow; ?>INV_AMOUNT_PPN" size="10" value="<?php echo $INV_AMOUNT_PPN; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai PPn Inv" >
					                                        </td>

					                                        <td style="text-align:right" > <!-- AMOUNT_PAID, INV_AMOUNT_REM -->
					                                        	<?php echo number_format($AMOUNT_PAID, $decFormat); ?>
																<input type="hidden" name="AMOUNT_PAID<?php echo $currentRow; ?>" id="AMOUNT_PAID<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_PAID, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" readonly>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][AMOUNT_PAID]" id="data<?php echo $currentRow; ?>AMOUNT_PAID" size="10" value="<?php echo $AMOUNT_PAID; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" >
					                                            <input type="hidden" name="INV_AMOUNT_REM<?php echo $currentRow; ?>" id="INV_AMOUNT_REM<?php echo $currentRow; ?>" value="<?php echo $INV_AMOUNT_REM; ?>" title="Nilai Max Pemb." >
					                                        </td>
					                                      	
					                                        <td style="text-align:right; display:none" > <!-- CBD_AMOUNT_DISC -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
																	<input type="text" name="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" id="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT_DISC, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDisc(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } else { ?>
																	<?php echo number_format($CBD_AMOUNT_DISC, $decFormat); ?>
																	<input type="hidden" name="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" id="CBD_AMOUNT_DISC<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT_DISC, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDisc(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } ?>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CBD_AMOUNT_DISC]" id="data<?php echo $currentRow; ?>CBD_AMOUNT_DISC" size="10" value="<?php echo $CBD_AMOUNT_DISC; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" >
					                                        </td>
					                                      	
					                                        <td style="text-align:right" > <!-- CBD_AMOUNT -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
																	<input type="text" name="CBD_AMOUNT<?php echo $currentRow; ?>" id="CBD_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } else { ?>
																	<?php echo number_format($CBD_AMOUNT, $decFormat); ?>
																	<input type="hidden" name="CBD_AMOUNT<?php echo $currentRow; ?>" id="CBD_AMOUNT<?php echo $currentRow; ?>" value="<?php echo number_format($CBD_AMOUNT, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,<?php echo $currentRow; ?>);" title="Nilai Total Inv" >
																<?php } ?>
					                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][CBD_AMOUNT]" id="data<?php echo $currentRow; ?>CBD_AMOUNT" size="10" value="<?php echo $CBD_AMOUNT; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" >
					                                        </td>

					                                      	<td style="text-align:right;"> <!-- AMOUNT_DP -->
																<?php if($CB_STAT == 1 || $CB_STAT == 4) { ?>
					                                        		<input type="text" name="AMOUNT_DP<?php echo $currentRow; ?>" id="AMOUNT_DP<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_DP, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDP(this,<?php echo $currentRow; ?>);" title="Nilai Pot Pemb." >
																<?php } else { ?>
																	<?php echo number_format($AMOUNT_DP, $decFormat); ?>
					                                        		<input type="hidden" name="AMOUNT_DP<?php echo $currentRow; ?>" id="AMOUNT_DP<?php echo $currentRow; ?>" value="<?php echo number_format($AMOUNT_DP, $decFormat); ?>" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDP(this,<?php echo $currentRow; ?>);" title="Nilai Pot Pemb." >
																<?php } ?>
																<input type="hidden" name="data[<?php echo $currentRow; ?>][AMOUNT_DP]" id="data<?php echo $currentRow; ?>AMOUNT_DP" size="10" value="<?php echo $AMOUNT_DP; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." >
					                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Notes]" id="data<?php echo $currentRow; ?>Notes" value="<?php echo $Notes; ?>" class="form-control" style="max-width:500px;" >
					                                  		</td>
				                                  		</tr>
				                                		<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
				                              		<?php
				                                    endforeach;
				                                }
												/*$CB_TOTAM		= $CB_TOTAM + $INV_AMOUNT;
												$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $INV_AMOUNT_PPN;
												$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $INV_AMOUNT_DISC;*/

												$CB_TOTAM		= $CB_TOTAM + $CBD_AMOUNT;
												$CB_TOTAM_PPN	= $CB_TOTAM_PPN + $INV_AMOUNT_PPN;
												$CB_TOTAM_DISC	= $CB_TOTAM_DISC + $CBD_AMOUNT_DISC;
				                            }				
			                            ?>
			                            <input type="hidden" name="CB_TOTAM" id="CB_TOTAM" value="<?php echo $CB_TOTAM; ?>">
			                            <input type="hidden" name="CB_TOTAM_PPN" id="CB_TOTAM_PPN" value="<?php echo $CB_TOTAM_PPN; ?>">
			                            <input type="hidden" name="CB_TOTAM_DISC" id="CB_TOTAM_DISC" value="<?php echo $CB_TOTAM_DISC; ?>">
			                        </table>
			                    </div>
				            </div>
	                    </div>
				    </div>

	                    <div class="col-sm-6">
	                        <div class="box-body">
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
										<?php
											$ShowBtn	= 0;
											if($canApprove == 1 && $ISAPPROVE == 1)
											{
		                                        ?>
		                                            <button class="btn btn-primary" id="tblSave">
		                                            <i class="fa fa-save"></i></button>&nbsp;
		                                        <?php
			                                }
			                                echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="tblBack"><i class="fa fa-reply"></i></button>');
											$secPrint	= site_url('c_finance/c_bp0c07180851/printdocument/?id='.$this->url_encryption_helper->encode_url($JournalH_Code));
										?>
		                            </div>
		                        </div>
	                        </div>
	                    </div>
	                <br>
					<?php
	                    $DOC_NUM	= $CB_NUM;
	                    $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
	                    $resCAPPH	= $this->db->count_all($sqlCAPPH);
	                ?>

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
				</form>
			</div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
	$.fn.datepicker.defaults.format = "dd/mm/yyyy";
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
	
	function add_gej(strItem) 
	{
		selAccount	= document.getElementById('selAccount').value;
		document.getElementById('AccSelected').value = selAccount;
		CB_CURRIDA	= document.getElementById('CB_CURRID').value;
		document.getElementById('CB_CURRIDA').value = CB_CURRIDA;
		SPLSelected	= document.getElementById('CB_PAYFOR').value;
		document.getElementById('SPLSelected').value = SPLSelected;
		SRCSelected	= document.getElementById('CB_SOURCE').value;
		document.getElementById('SRCSelected').value = SRCSelected;
		SRCNumber	= document.getElementById('CB_SOURCENO').value;
		document.getElementById('SRCNumber').value = strItem;
		
		
		document.frm1.submit1.click();
	}
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		TOTInvoice		= 0;
		TOTPPn			= 0;
		TOTPPh			= 0;
		TOTRet			= 0;
		TOTDisc			= 0;
		TOTDP			= 0;
		GTInvoice		= 0;
		TOTInvoiceR		= 0;
		TOTPPnR			= 0;
		GTInvoiceR		= 0;
		
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var CB_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var CB_CODEx 	= "<?php echo $CB_CODE; ?>";
		ilvl = arrItem[1];
		
		//validateDouble(arrItem[0],arrItem[1])
		//if(validateDouble(arrItem[0],arrItem[1]))
		//{
			//swal("Double Item for " + arrItem[0]);
			//return;
		//}
		
		INV_NUM 		= arrItem[0];
		INV_CODE 		= arrItem[1];
		PRJCODE 		= arrItem[2];
		INV_AMOUNT 		= arrItem[3];		// NILAI INVOICE SETELAH DIPOTONG "Potongan Lainnya"
		INV_AMOUNT_PPn	= arrItem[4];
		IR_NUM 			= arrItem[5];
		INV_NOTES 		= arrItem[6];
		INV_REM 		= arrItem[7];		// TOTAL NILAI INVOICE PLUS PPn
		INV_PPNREM 		= arrItem[8];
		INV_CATEG 		= arrItem[9];
		Amount_max		= arrItem[10];
		PRJCODE			= arrItem[11];
		INV_AMOUNT_Ret	= arrItem[12];		// Retensi Invoice
		INV_AMOUNT_PPn	= arrItem[13];		// PPn Invoice
		INV_AMOUNT_PPh	= arrItem[14];		// PPh Invoice
		INV_AMOUNT_Disc	= 0;				// Potongan pembayaran
		INV_AMOUNT_DP	= 0;				// Pemotongan DP Pembayaran
		
		if(IR_NUM == '')
			IR_NUM		= INV_CODE;
		
		TOTInvoice	= parseFloat(TOTInvoice) + parseFloat(INV_AMOUNT);		
		TOTPPn		= parseFloat(TOTPPn) + parseFloat(INV_AMOUNT_PPn);
		TOTPPh		= parseFloat(TOTPPh) + parseFloat(INV_AMOUNT_PPh);
		TOTRet		= parseFloat(TOTRet) + parseFloat(INV_AMOUNT_Ret);
		TOTDisc		= parseFloat(TOTDisc) + parseFloat(INV_AMOUNT_Disc);
		TOTDP		= parseFloat(TOTDP) + parseFloat(INV_AMOUNT_DP);
		
		// TOTAL SEMUA POTONGAN SISI INVOICE SEMUA POTONGAN
		TOTDiscG	= parseFloat(TOTDiscG) + parseFloat(INV_AMOUNT_PPh) + parseFloat(INV_AMOUNT_Ret) + parseFloat(INV_AMOUNT_Disc);
		
		// TOTAL SISI INVOICE  = TOTAL NILAI SELAIN POTONGAN
		if(INV_CATEG == 'IR')
		{
			GTInvoice	= parseFloat(GTInvoice) + parseFloat(INV_AMOUNT) + parseFloat(INV_AMOUNT_PPn) - parseFloat(INV_AMOUNT_Ret) - parseFloat(INV_AMOUNT_PPh);
		}
		else
		{
			GTInvoice	= parseFloat(GTInvoice) + parseFloat(INV_AMOUNT) + parseFloat(INV_AMOUNT_PPn) - parseFloat(INV_AMOUNT_Ret) - parseFloat(INV_AMOUNT_PPh);
		}
		
		TOTInvoiceR		= parseFloat(TOTInvoiceR) + parseFloat(INV_REM);
		TOTPPnR			= parseFloat(TOTPPnR) + parseFloat(INV_PPNREM);
		GTInvoiceR		= parseFloat(GTInvoiceR) + parseFloat(INV_REM);
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		document.getElementById('PRJCODE').value	= PRJCODE;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+'); countAllInvoice();" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="data'+intIndex+'JournalH_Code" name="data['+intIndex+'][JournalH_Code]" value="'+CB_NUMx+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'CB_NUM" name="data['+intIndex+'][CB_NUM]" value="'+CB_NUMx+'" class="form-control" style="max-width:300px;" readonly>';
		
		// No. Faktur
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+INV_CODE+'<input type="hidden" id="data['+intIndex+'][CB_CODE]" name="data['+intIndex+'][CB_CODE]" value="'+CB_CODEx+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'DocumentNo" name="data['+intIndex+'][DocumentNo]" value="'+INV_NUM+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'DocumentRef" name="data['+intIndex+'][DocumentRef]" value="'+IR_NUM+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" class="form-control" style="max-width:300px;" readonly>';
		
		// Deskripsi
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+INV_NOTES+'<input type="hidden" name="data['+intIndex+'][Description]" id="data'+intIndex+'Description" size="10" value="'+INV_NOTES+'" class="form-control" style="max-width:300px;" ><input type="hidden" name="data['+intIndex+'][CB_CATEG]" id="data'+intIndex+'CB_CATEG" size="10" value="'+INV_CATEG+'" class="form-control" style="max-width:300px;" >';
		
		// Invoice Amount
		INVAMOUNT	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTInvoice)),decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="Inv_Amount'+intIndex+'" id="Inv_Amount'+intIndex+'" value="'+INVAMOUNT+'" class="form-control" style="min-width:120px; max-width:150px; text-align:right" title="Nilai Total Inv" disabled ><input type="hidden" name="data['+intIndex+'][Inv_Amount]" id="data'+intIndex+'Inv_Amount" size="10" value="'+INV_AMOUNT+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Inv" ><input type="hidden" name="data['+intIndex+'][Inv_Amount_PPn]" id="data'+intIndex+'Inv_Amount_PPn" size="10" value="'+INV_AMOUNT_PPn+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai PPn Inv" ><input type="hidden" name="data['+intIndex+'][Inv_Amount_PPh]" id="data'+intIndex+'Inv_Amount_PPh" size="10" value="'+INV_AMOUNT_PPh+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai PPh Inv" ><input type="hidden" name="data['+intIndex+'][Inv_Amount_Ret]" id="data'+intIndex+'Inv_Amount_Ret" size="10" value="'+INV_AMOUNT_Ret+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Ret Inv" >';
		
		// Payment - Amount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="Amount'+intIndex+'" id="Amount'+intIndex+'" value="'+TOTInvoiceR+'" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPayAmount(this,'+intIndex+');" title="Nilai Total Inv" ><input type="hidden" name="data['+intIndex+'][Amount]" id="data'+intIndex+'Amount" size="10" value="'+INV_REM+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Sisa Inv" ><input type="hidden" name="Amount_max'+intIndex+'" id="Amount_max'+intIndex+'" value="'+Amount_max+'" title="Nilai Max Pemb." >';
		
		// PPh Selection
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.display = 'none';
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="PPhTax" id="PPhTax" class="form-control" style="max-width:100px" onChange="selPPhStat(this.value)"><option value="">-- none --</option><?php $sqlTLA = "SELECT TAXLA_NUM, TAXLA_CODE FROM tbl_tax_la"; $resTLA 	= $this->db->query($sqlTLA)->result(); foreach($resTLA as $rowTLA): $TAXLA_NUM	= $rowTLA->TAXLA_NUM; $TAXLA_CODE	= $rowTLA->TAXLA_CODE; ?><option value="<?php echo $TAXLA_NUM; ?>"><?php echo $TAXLA_CODE; ?></option><?php endforeach; ?></select>';
		
		// PPh & Retensi Amount
		INVPPHVAL	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(INV_AMOUNT_PPh)),decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.display = 'none';
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="PPhAmount'+intIndex+'" id="PPhAmount'+intIndex+'" value="0" class="form-control" style="min-width:100px; max-width:120px; text-align:right" onKeyPress="return isIntOnlyNew(event);" title="Nilai PPh Pemb." onBlur="chgPPhAm(this,'+intIndex+');"><input type="hidden" name="data['+intIndex+'][PPhAmount]" id="data'+intIndex+'PPhAmount" size="10" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai PPh Pemb." ><input type="hidden" name="data['+intIndex+'][RetAmount]" id="data'+intIndex+'RetAmount" size="10" value="0" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Ret Pemb." >';
		
		// Discount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="DiscAmount'+intIndex+'" id="DiscAmount'+intIndex+'" value="0.00" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDisc(this,'+intIndex+');" title="Nilai Pot Pemb." ><input type="hidden" name="data['+intIndex+'][DiscAmount]" id="data'+intIndex+'DiscAmount" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." ><input type="hidden" name="data['+intIndex+'][Inv_Amount_Disc]" id="data'+intIndex+'Inv_Amount_Disc" size="10" value="'+INV_AMOUNT_Disc+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." >';
		
		// Keterangan
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.style.display = '';
		objTD.innerHTML = '<input type="text" name="DPAmount'+intIndex+'" id="DPAmount'+intIndex+'" value="0.00" class="form-control" style="min-width:130px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgAftDP(this,'+intIndex+');" title="Nilai Pot Pemb." ><input type="hidden" name="data['+intIndex+'][DPAmount]" id="data'+intIndex+'DPAmount" size="10" value="'+INV_AMOUNT_DP+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" title="Nilai Pot Pemb." ><input type="hidden" name="data['+intIndex+'][Notes]" id="data'+intIndex+'Notes" size="15" value="" class="form-control" style="max-width:300px;" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		document.getElementById('Inv_Amount'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTInvoice)),decFormat));
		document.getElementById('Amount'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTInvoiceR)),decFormat));
		
		//document.getElementById('TOTPPn').value 				= parseFloat(TOTPPn);
		//document.getElementById('TOTPPn1').value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPPn)),decFormat));
		//document.getElementById('TOTInvoice').value 			= parseFloat(TOTInvoice);
		//document.getElementById('TOTInvoice1').value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTInvoice)),decFormat));
		//document.getElementById('GTInvoice').value 			= parseFloat(GTInvoice);
		//document.getElementById('GTInvoice1').value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTInvoice)),decFormat));
		
		document.getElementById('totalrow').value = intIndex;
		
		countAllInvoice();
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();	
			
		countAllInvoice()
	}
	
	function chgPayAmount(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		var Amount_max		= parseFloat(document.getElementById('Amount_max'+row).value);
		var thisVal			= eval(thisVal1).value.split(",").join("");
		var Amount_Pay		= parseFloat(thisVal);
		
		if(Amount_Pay > Amount_max)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			document.getElementById('Amount'+row).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Amount_max)),decFormat));
			document.getElementById('data'+row+'Amount').value	= Amount_max;
			
			Amount_Pay		= parseFloat(Amount_max);
		}
		
		document.getElementById('data'+row+'Amount').value 	= Amount_Pay;
		document.getElementById('Amount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Amount_Pay)),decFormat));
		// PPn Dihidden saja karena rencana penghitungannya di file control
		/*document.getElementById('data'+row+'Amount_PPn').value 	= Amount_PPn;
		document.getElementById('Amount_PPn'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Amount_PPn)),decFormat));*/
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PPn	= 0;
		for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var Amount_PPn		= 0;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PPn	= parseFloat(TOT_AMOUNT_PPn) + parseFloat(Amount_PPn);
		}
		document.getElementById('CB_TOTAM').value		= parseFloat(TOT_AMOUNT);
		document.getElementById('CB_TOTAM_PPn').value	= parseFloat(TOT_AMOUNT_PPn);
		
		countAllInvoice();
	}
	
	function chgAftDisc(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		
		var InvAmount	= document.getElementById('data'+row+'Amount').value;
		var discount	= eval(thisVal1).value.split(",").join("");					// Potongan Diskon
		var pot_dp		= document.getElementById('data'+row+'DPAmount').value;		// Potongan DP
		
		// TIDAK PERLU MENGURANGI NILAI PEMBAYARAN, DILAKUKANNYA SAAT DI JURNAL SAJA
		// var remInvAm	= parseFloat(InvAmount) - parseFloat(discount) - parseFloat(pot_dp);
		
		//document.getElementById('data'+row+'Amount').value 	= remInvAm;
		//document.getElementById('Amount'+row).value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(remInvAm)),decFormat));
		
		document.getElementById('data'+row+'DiscAmount').value 	= discount;
		document.getElementById('DiscAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(discount)),decFormat));
				
		countAllInvoice();
	}
	
	function chgAftDP(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		var DP_REM		= parseFloat(document.getElementById('TOT_DPAMOUNTX').value);
		
		var pot_dp		= eval(thisVal1).value.split(",").join("");
		var DiscAmount	= parseFloat(document.getElementById('data'+row+'DiscAmount').value);
		var InvAmount	= parseFloat(document.getElementById('data'+row+'Amount').value);

		if(DP_REM > 0)
		{
			if(pot_dp > DP_REM)
			{
				swal('<?php echo $alert3; ?>',
				{
					icon: "warning",
				});
				document.getElementById('data'+row+'DPAmount').value 	= 0;
				document.getElementById('DPAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
			}
			else
			{
				document.getElementById('data'+row+'DPAmount').value 	= pot_dp;
				document.getElementById('DPAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(pot_dp)),decFormat));
			}
		}
		else
		{
			document.getElementById('data'+row+'DPAmount').value 	= 0;
			document.getElementById('DPAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
		}
		
		// TIDAK PERLU MENGURANGI NILAI PEMBAYARAN, DILAKUKANNYA SAAT DI JURNAL SAJA
		// var remInvAm	= parseFloat(InvAmount) - parseFloat(DiscAmount) - parseFloat(pot_dp);
		// var remInvAm	= parseFloat(InvAmount);
		// document.getElementById('data'+row+'Amount').value 		= remInvAm;
		// document.getElementById('Amountx'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(remInvAm)),decFormat)); // nilai yang dibayarkan berubah
		
		
		var TOT_POTDP	= 0;
		for(i=1;i<=totRow;i++)
		{
			var POT_DP		= document.getElementById('data'+row+'DPAmount').value;
			var TOT_POTDP	= parseFloat(TOT_POTDP) + parseFloat(POT_DP);
		}
		document.getElementById('CB_DPAMOUNT').value	= TOT_POTDP;
		document.getElementById('CB_DPAMOUNTX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_POTDP)),decFormat));
		
		countAllInvoice();
	}
	
	function chgPayAmountPPn(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		
		var thisVal			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'Amount_PPn').value 	= thisVal;
		document.getElementById('Amount_PPn'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PPn	= 0;
		for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var Amount_PPn		= document.getElementById('data'+row+'Amount_PPn').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PPn	= parseFloat(TOT_AMOUNT_PPn) + parseFloat(Amount_PPn);
		}
		document.getElementById('CB_TOTAM').value		= parseFloat(TOT_AMOUNT);
		document.getElementById('CB_TOTAM_PPn').value	= parseFloat(TOT_AMOUNT_PPn);
		
		countAllInvoice();
	}
	
	function chgPPhAm(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		
		var PPhAmount	= eval(thisVal1).value.split(",").join("");
		var InvAmount	= document.getElementById('data'+row+'Amount').value;
		var remInvAm	= parseFloat(InvAmount - PPhAmount);
		document.getElementById('data'+row+'Amount').value 		= remInvAm;
		document.getElementById('Amount'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(remInvAm)),decFormat));
		
		document.getElementById('data'+row+'PPhAmount').value 	= PPhAmount;
		document.getElementById('PPhAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPhAmount)),decFormat));
		
		countAllInvoice();
	}
	
	function countAllInvoice()
	{
		var decFormat	= document.getElementById('decFormat').value;
		totrow			= document.getElementById('totalrow').value;		
		var CB_CATEG 	= document.getElementById('CB_CATEG');		
		
		var Inv_TOT_Am		= 0;
		var Inv_TOT_AmG		= 0;
		var Inv_TOT_PPn		= 0;
		var Inv_TOT_PPh		= 0;
		var Inv_TOT_Ret		= 0;
		var Inv_TOT_Disc	= 0;
		var Inv_TOT_POT		= 0;
		var Inv_TOT_POTG	= 0;
		
		var Pay_TOT_Am		= 0;
		var Pay_TOT_AmG		= 0;
		var Pay_TOT_PPn		= 0;
		var Pay_TOT_PPh		= 0;
		var Pay_TOT_Ret		= 0;
		var Pay_TOT_Disc	= 0;
		var Pay_TOT_DP		= 0;
		var Pay_TOT_POT		= 0;
		var Pay_TOT_POTG	= 0;
		
		for(i=1;i<=totrow;i++)
		{
			var INV_CATEG			= document.getElementById('data'+i+'CB_CATEG').value;
			// SISI NILAI INVOICE
				var Inv_Amount		= document.getElementById('data'+i+'Inv_Amount').value;			// Nilai Inv. Non PPn
				var Inv_Amount_PPn	= document.getElementById('data'+i+'Inv_Amount_PPn').value;		// Nilai PPn Inv.
				var Inv_Amount_PPh	= document.getElementById('data'+i+'Inv_Amount_PPh').value;
				var Inv_Amount_Ret	= document.getElementById('data'+i+'Inv_Amount_Ret').value;
				var Inv_Amount_Disc	= document.getElementById('data'+i+'Inv_Amount_Disc').value;
				
			// SISI NILAI PEMBAYARAN INVOICE
				var Pay_Amount		= document.getElementById('data'+i+'Amount').value;				// Nilai Pemb. Inv.
				var Pay_Amount_PPn	= 0;															// Nilai PPn Inv.
				var Pay_Amount_PPh	= 0;
				var Pay_Amount_Ret	= 0;
				var Pay_Amount_Disc	= document.getElementById('data'+i+'DiscAmount').value;
				var Pay_Amount_DP	= document.getElementById('data'+i+'DPAmount').value;
			
			// SISI NILAI INVOICE
				Inv_TOT_Am		= parseFloat(Inv_Amount) + parseFloat(Inv_Amount_PPn);				// Nilai Inv. dan PPn
				Inv_TOT_AmG		= parseFloat(Inv_TOT_AmG) + parseFloat(Inv_Amount);
				Inv_TOT_PPn		= parseFloat(Inv_TOT_PPn) + parseFloat(Inv_Amount_PPn);
				Inv_TOT_PPh		= parseFloat(Inv_TOT_PPh) + parseFloat(Inv_Amount_PPh);
				Inv_TOT_Ret		= parseFloat(Inv_TOT_Ret) + parseFloat(Inv_Amount_Ret);
				Inv_TOT_Disc	= parseFloat(Inv_TOT_Disc) + parseFloat(Inv_Amount_Disc);
				if(INV_CATEG == 'OPN-RET')
					Inv_TOT_POT	= parseFloat(Inv_Amount_PPh) + parseFloat(Inv_TOT_Disc);
				else
					Inv_TOT_POT	= parseFloat(Inv_Amount_PPh) + parseFloat(Inv_Amount_Ret) + parseFloat(Inv_Amount_Disc);
					
				Inv_TOT_POTG	= parseFloat(Inv_TOT_POTG)  + parseFloat(Inv_TOT_POT);
				
			// SISI NILAI PEMBAYARAN INVOICE
				Pay_TOT_Am		= parseFloat(Pay_Amount) + parseFloat(Pay_Amount_PPn);				// Nilai Inv. dan PPn
				Pay_TOT_AmG		= parseFloat(Pay_TOT_AmG) + parseFloat(Pay_Amount);
				Pay_TOT_PPn		= parseFloat(Pay_TOT_PPn) + parseFloat(Pay_Amount_PPn);
				Pay_TOT_PPh		= parseFloat(Pay_TOT_PPh) + parseFloat(Pay_Amount_PPh);
				Pay_TOT_Ret		= parseFloat(Pay_TOT_Ret) + parseFloat(Pay_Amount_Ret);
				Pay_TOT_Disc	= parseFloat(Pay_TOT_Disc) + parseFloat(Pay_Amount_Disc);
				Pay_TOT_DP		= parseFloat(Pay_TOT_DP) + parseFloat(Pay_Amount_DP);
				if(INV_CATEG == 'OPN-RET')
				Pay_TOT_POT		= parseFloat(Pay_Amount_PPh)+parseFloat(Pay_Amount_Disc)+parseFloat(Pay_Amount_DP);
				else
				Pay_TOT_POT		= parseFloat(Pay_Amount_PPh)+parseFloat(Pay_Amount_Ret)+parseFloat(Pay_Amount_Disc)+parseFloat(Pay_Amount_DP);
				
				Pay_TOT_POTG	= parseFloat(Pay_TOT_POTG)  + parseFloat(Pay_TOT_POT);
			
			if(INV_CATEG == 'IR')
			{
				document.getElementById('Inv_Amount'+i).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Inv_TOT_Am)),decFormat));
			}
			else if(INV_CATEG == 'OPN-RET')
			{
				Inv_Amount										= parseFloat(Inv_TOT_Am) - parseFloat(Inv_TOT_Disc) - parseFloat(Inv_Amount_PPh);
				//swal('Inv_Amount == '+Inv_Amount)
				document.getElementById('Inv_Amount'+i).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Inv_Amount)),decFormat));
			}
			else 
			{
				Inv_Amount										= parseFloat(Inv_TOT_Am) - parseFloat(Inv_TOT_Ret) - parseFloat(Inv_TOT_Disc) - parseFloat(Inv_Amount_PPh);
				//swal('Inv_Amount == '+Inv_Amount)
				document.getElementById('Inv_Amount'+i).value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Inv_Amount)),decFormat));
			}
			document.getElementById('Amount'+i).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Pay_Amount)),decFormat));
		}
		
		var GTOT_DP		= eval(document.getElementById('CB_DPAMOUNTX')).value.split(",").join("");
		Pay_TOT_POTG	= parseFloat(Pay_TOT_POTG) + parseFloat(GTOT_DP);
		
		var GTInvoice	= parseFloat(Inv_TOT_AmG) + parseFloat(Inv_TOT_PPn) - parseFloat(Inv_TOT_POTG);
		var GTInvoice_p	= parseFloat(Pay_TOT_AmG) - parseFloat(Pay_TOT_POTG);
		
		// KALKULASI SISI NILAI INVOICE

		document.getElementById('TOTInvoice').value 	= parseFloat(Inv_TOT_AmG);
		document.getElementById('TOTInvoice1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Inv_TOT_AmG)),2));		
		document.getElementById('TOTPPn').value 		= parseFloat(Inv_TOT_PPn);
		document.getElementById('TOTPPn1').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Inv_TOT_PPn)),2));
		document.getElementById('TOTDiscG').value 		= parseFloat(Inv_TOT_POTG);
		document.getElementById('TOTDiscG1').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Inv_TOT_POTG)),2));
		document.getElementById('GTInvoice').value 		= parseFloat(GTInvoice);
		document.getElementById('GTInvoice1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTInvoice)),2));
		
		// SISI INFORMASI PEMBAYARAN
		document.getElementById('TOTInvoice1_p').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Pay_TOT_AmG)),2));
		document.getElementById('TOTDisc1_p').innerHTML		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(Pay_TOT_POTG)),2));
		document.getElementById('GTInvoice1_p').innerHTML	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTInvoice_p)),2));
		document.getElementById('GTInPay').value			= GTInvoice_p;
		
		document.getElementById('CB_TOTAM').value			= parseFloat(Pay_TOT_AmG);
		document.getElementById('CB_TOTAM_PPn').value		= parseFloat(Pay_TOT_PPn);
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function validateInData(value)
	{
		var totrow 			= document.getElementById('totalrow').value;
		//var venCode 		= document.getElementById('Vend_Code').value;
		var TotRemAccount 	= document.getElementById('TotRemAccount').value;
		var GTInvoice 		= document.getElementById('GTInvoice').value;
		var GTInPay 		= document.getElementById('GTInPay').value;
		var CB_STAT 		= document.getElementById('CB_STAT').value;
		
		if(parseFloat(TotRemAccount) < parseFloat(GTInPay))
		{
			if(B_STAT == 3)
			{
				swal('<?php echo $alert1; ?>',
				{
					icon: "warning",
				});
				return false;
			}
		}
		
		if(totrow == 0)
		{
			swal('<?php echo $selInv; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		for(i=1;i<=totrow;i++)
		{			
			var thisVal		= document.getElementById('data'+i+'Amount').value;
			
			if(thisVal == 0)
			{
				swal('<?php echo $inAmInv; ?>',
				{
					icon: "warning",
				});
				document.getElementById('Amount'+i).value = '0';
				document.getElementById('Amount'+i).focus();
				return false;
			}
		}
		
		if(CB_STAT == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			document.getElementById('CB_STAT').focus();
			return false;
		}
		
		if(totrow == 0)
		{
			swal('<?php echo $selInv; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		else
		{
			document.getElementById('tblSave').style.display = 'none';
			document.getElementById('tblBack').style.display = 'none';
			document.frm.submit();
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