<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 27 November 2017
 * File Name	= v_inb_po_form.php
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
$DEPCODE 		= $this->session->userdata['DEPCODE'];

$PRJHO 			= "";
$PRJNAME		= '';
$PO_RECEIVLOC	= '';
$sql 			= "SELECT PRJCODE_HO, PRJNAME, PRJADD FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 		= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJHO 			= $row ->PRJCODE_HO;
	$PRJNAME 		= $row ->PRJNAME;
endforeach;

$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$noU = 0;

$PO_NUM 		= $default['PO_NUM'];
$DocNumber		= $PO_NUM;
$PO_CODE 		= $default['PO_CODE'];
$PO_CATEG 		= $default['PO_CATEG'];
$PO_TYPE 		= $default['PO_TYPE'];
$PO_CAT 		= $default['PO_CAT'];
$PO_DATEA 		= $default['PO_DATE'];
$PO_DUED 		= $default['PO_DUED'];
$PO_DATE		= date('d/m/Y', strtotime($PO_DATEA));
$PO_DUED		= date('d/m/Y', strtotime($PO_DUED));
$JournalY 		= date('Y', strtotime($default['PO_DATE']));
$JournalM 		= date('n', strtotime($default['PO_DATE']));
$RECOM_DATE		= date('d/m/Y', strtotime($default['RECOM_DATE']));
$PRJCODE 		= $default['PRJCODE'];
$DEPCODE 		= $default['DEPCODE'];
$SPLCODE 		= $default['SPLCODE'];
$PR_NUM 		= $default['PR_NUM'];
$PR_CODE 		= $default['PR_CODE'];
$PR_NUMX		= $PR_NUM;
$PO_DPPER 		= $default['PO_DPPER'];
$PO_DPVAL 		= $default['PO_DPVAL'];
$PO_CURR 		= $default['PO_CURR'];
$PO_CURRATE 	= $default['PO_CURRATE'];
$PO_PAYTYPE 	= $default['PO_PAYTYPE'];
$PO_TENOR 		= $default['PO_TENOR'];
$PO_PLANIRA		= $default['PO_PLANIR'];
$PO_PLANIR		= date('d/m/Y', strtotime($PO_PLANIRA));
$PO_NOTESIR 	= $default['PO_NOTESIR'];
$PO_NOTES 		= $default['PO_NOTES'];
$PO_MEMO 		= $default['PO_MEMO'];
$PO_NOTES1		= $default['PO_NOTES1'];
$PO_PAYNOTES 	= $default['PO_PAYNOTES'];
$PRJNAME1 		= $default['PRJNAME'];
$PO_TOTCOST		= $default['PO_TOTCOST'];
$PO_STAT 		= $default['PO_STAT'];
$lastPatternNumb1= $default['Patt_Number'];

$shwBtn 		= 1;
if($PO_PLANIRA < $PO_DATEA)
	$shwBtn 	= 0;

$PO_TAXRATE			= 1;
$totTaxPPnAmount	= 1;
$totTaxPPhAmount	= 1;
	
$PO_RECEIVLOC	= $default['PO_RECEIVLOC'];
$PO_RECEIVCP	= $default['PO_RECEIVCP'];
$PO_SENTROLES 	= $default['PO_SENTROLES'];
$PO_REFRENS 	= $default['PO_REFRENS'];
$PO_CONTRNO 	= $default['PO_CONTRNO'];

$PR_NUMX		= $PR_NUMX;
$DOC_NUM		= $PO_NUM;

//echo $PR_NUMX;

$secGenCode	= base_url().'index.php/c_purchase/c_p180c21o/genCode/'; // Generate Code

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }

		.uploaded_area {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
		}

		.file {
			display: grid;
			grid-template-columns: max-content 1fr;
			grid-template-areas: "iconfile titlefile"
								 "iconfile actfile";
		}

		.iconfile {
			grid-area: iconfile;
			padding-right: 5px;
		}

		.titlefile {
			grid-area: titlefile;
			font-size: 8pt;
		}

		.actfile {
			grid-area: actfile;
			font-size: 8pt;
		}
	</style>

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
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Request')$Request = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'payMethod')$payMethod = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'TotalPrice')$TotalPrice = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'Receiver')$Receiver = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
			if($TranslCode == 'QuotNo')$QuotNo = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'Specification')$Specification = $LangTransl;
			if($TranslCode == 'sureApprove')$sureApprove = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'dokLam')$dokLam = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$alert1	= 'Silahkan pilih status pemesanan.';
			$alert2	= "Silahkan tulis alasan Anda me-revisi/reject dokumen ini.";
			$alert2	= "Silahkan tulis catatan dokumen.";
			$alert3	= "Dokumenn ini memerlukan persetujuan khusus.";
			$noAtth = "Tidak ada dokumen dilampirkan";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$alert1	= 'Please select order status.';
			//$alert2	= "Plese input the reason why you revise the document.";
			$alert2	= "Please write the reason why you revised/rejected the document.";
			$alert3	= "This document needs a special approval.";
			$noAtth = "No document(s) attached";
		}

		// SETTINGAN UNTUK APPROVAL PER DIVISI
		if($PRJHO == 'KTR')
		{
			$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_po_detail A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'";
			$r_DIV 	= $this->db->query($s_DIV)->result();
			foreach($r_DIV as $rw_DIV):
				$MenuApp 	= $rw_DIV->JOBCOD1;

				$s_UPD1 	= "UPDATE tbl_po_detail SET PO_DIVID = '$MenuApp' WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_UPD1);

				$s_UPD2 	= "UPDATE tbl_po_header SET PO_DIVID = '$MenuApp' WHERE PO_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_UPD2);
			endforeach;
		}

		if($MenuApp == 'MN436')
			$MenuApp 	= "MN436";
		elseif($MenuApp == 'MN437')
			$MenuApp 	= "MN447";
		elseif($MenuApp == 'MN438')
			$MenuApp 	= "MN448";
		elseif($MenuApp == 'MN439')
			$MenuApp 	= "MN449";
		elseif($MenuApp == 'MN440')
			$MenuApp 	= "MN450";
		elseif($MenuApp == 'MN441')
			$MenuApp 	= "MN451";
		elseif($MenuApp == 'MN442')
			$MenuApp 	= "MN452";
		elseif($MenuApp == 'MN443')
			$MenuApp 	= "MN453";
		elseif($MenuApp == 'MN444')
			$MenuApp 	= "MN454";
		elseif($MenuApp == 'MN445')
			$MenuApp 	= "MN455";
		elseif($MenuApp == 'MN446')
			$MenuApp 	= "MN456";
		else
			$MenuApp 	= "MN020";
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PO_TOTCOST
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
				/*$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND (APPROVER_1 = '$DefEmp_ID' || APPROVER_2 = '$DefEmp_ID' || APPROVER_3 = '$DefEmp_ID' || APPROVER_4 = '$DefEmp_ID' || APPROVER_5 = '$DefEmp_ID')";*/
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE'";
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
				$APPROVE_AMOUNT 	= $PO_TOTCOST;
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
			
		// START : MAJOR ITEM SETTING
			$Emp_ID1	= '';
			$Emp_ID2	= '';
			/*$sqlMJREMP	= "SELECT * FROM tbl_major_app";
			$resMJREMP	= $this->db->query($sqlMJREMP)->result();
			foreach($resMJREMP as $rowMJR) :
				$Emp_ID1	= $rowMJR->Emp_ID1;
				$Emp_ID2	= $rowMJR->Emp_ID2;
			endforeach;*/
			if(($DefEmp_ID == $Emp_ID1) || ($DefEmp_ID == $Emp_ID2))
			{
				$canApprove	= 1;
			}
			$sqlCMJR	= "tbl_po_detail A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							WHERE A.PO_NUM = '$DOC_NUM' 
								AND B.PRJCODE = '$PRJCODE'
								AND B.ISMAJOR = 1";
			$resCMJR = $this->db->count_all($sqlCMJR);
		// END : MAJOR ITEM SETTING
		
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
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_ord.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">	
		    <div class="row">
            	<!-- Mencari Kode Purchase Requase Number -->
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="text" name="PR_NUMX" id="PR_NUMX" class="textbox" value="<?php echo $PR_NUMX; ?>" />
                    <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
                <!-- End -->
                
                <!-- Mencari Kode Purchase Requase Number -->
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
                                <input type="hidden" name="PODate" id="PODate" value="">
                            </td>
                            <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                        </tr>
                    </table>
                </form>
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkForm()">
					<?php
                        // START : LOCK PROCEDURE
                            $app_stat   = $this->session->userdata['app_stat'];
                            if($LangID == 'IND')
                            {
                                $appAlert1  = "Terkunci!";
                                $appAlert2  = "Mohon maaf, saat ini transaksi bulan $MonthVw $JournalY sedang terkunci.";
                            }
                            else
                            {
                                $appAlert1  = "Locked!";
                                $appAlert2  = "Sorry, the transaction month $MonthVw $JournalY is currently locked.";
                            }
                            ?>
                                <input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
                                <div class="col-sm-12" id="divAlert" style="display:none;">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h4><i class="icon fa fa-ban"></i> <?php echo $appAlert1; ?>!</h4>
                                                <?php echo $appAlert2; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        // END : LOCK PROCEDURE
                    ?>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                        <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
		                        <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		           				<input type="Hidden" name="ISDIRECT" id="ISDIRECT" value="0">
		                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PONumber ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" style="max-width:195px" name="PO_NUM1" id="PO_NUM1" value="<?php echo $PO_NUM; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="PO_NUM" id="PO_NUM" size="30" value="<?php echo $PO_NUM; ?>" />
		                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
		                          	<div class="col-sm-4">
		                            	<input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="PO_CODE" id="PO_CODE" value="<?php echo $PO_CODE; ?>" >
		                            	<input type="text" class="form-control" name="PO_CODEX" id="PO_CODEX" value="<?php echo $PO_CODE; ?>" readonly >
		                          	</div>
		                          	<div class="col-sm-3">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="hidden" name="PO_DATE" id="PO_DATE" class="form-control pull-left" value="<?php echo $PO_DATE; ?>" style="width:150px" onChange="getPO_NUM(this.value)">
		                                    <input type="hidden" name="PO_DUED" id="PO_DUED" class="form-control pull-left" value="<?php echo $PO_DUED; ?>" style="width:150px">
		                                    <input type="text" name="PO_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $PO_DATE; ?>" style="width:106px" disabled>
		                                </div>
		                          	</div>
		                          	<div class="col-sm-2">
		                        		<select name="PO_CURRX" id="PO_CURRX" class="form-control select2" disabled>
		                                	<option value="" > --- </option>
		                                	<option value="IDR" <?php if($PO_CURR == 'IDR') { ?> selected <?php } ?>>IDR</option>
		                                	<option value="USD" <?php if($PO_CURR == 'USD') { ?> selected <?php } ?>>USD</option>    
		                                </select>
		                                <input type="hidden" name="PO_CURR" id="PO_CURR" class="form-control pull-left" value="<?php echo $PO_CURR; ?>">
		                          	</div>
		                        </div>
		                        <script>
		                            function getPO_NUM(selDate)
		                            {
		                                document.getElementById('PODate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
			
									$(document).ready(function()
									{
										$(".tombol-date").click(function()
										{
											var add_PO	= "<?php echo $secGenCode; ?>";
											var formAction 	= $('#sendDate')[0].action;
											var data = $('.form-user').serialize();
											$.ajax({
												type: 'POST',
												url: formAction,
												data: data,
												success: function(response)
												{
													var myarr = response.split("~");
													document.getElementById('PO_NUM1').value 	= myarr[0];
													document.getElementById('PO_NUM').value 	= myarr[0];
													document.getElementById('PO_CODE').value 	= myarr[1];
												}
											});
										});
									});
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">No. SPP</label>
		                          	<div class="col-sm-4">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdl_addPR" title="Cari SPP"><i class="glyphicon glyphicon-search"></i></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUMX; ?>" >
		                                    <input type="text" class="form-control" name="PR_NUM1" id="PR_NUM1" value="<?php echo $PR_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?>>
		                                </div>
		                            </div>
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
				                    <div class="col-sm-3">
		                                <select name="PO_CATEG1" id="PO_CATEG1" class="form-control select2" disabled>
                                            <option value="0"<?php if($PO_CATEG == 0) { ?> selected <?php } ?>>---</option>
                                            <option value="1"<?php if($PO_CATEG == 1) { ?> selected <?php } ?>>Alat</option>
		                                </select>
		                                <input type="hidden" class="form-control" name="PO_CATEG" id="PO_CATEG" style="max-width:160px" value="<?php echo $PO_CATEG; ?>" >
				                    </div>
		                        </div>
								<?php
									//$url_selAURCODE	= site_url('c_asset/c_asset_usage/popupallaur/?id='.$this->url_encryption_helper->encode_url($AU_PRJCODE));
									$url_selPR_CODE		= site_url('c_purchase/c_p180c21o/popupallmr/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selPR_CODE;?>";
									function pleaseCheck()
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
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" readonly>
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName ?> </label>
		                          	<div class="col-sm-6">
		                            	<select name="SPLCODE" id="SPLCODE" class="form-control" onChange="getVendName(this.value)" readonly>
		                                    <?php
		                                    $i = 0;
		                                    if($countVend > 0)
		                                    {
		                                        foreach($vwvendor as $row) :
		                                            $SPLCODE1	= $row->SPLCODE;
		                                            $SPLDESC1	= $row->SPLDESC;
		                                            ?>
		                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLCODE1 - $SPLDESC1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                        if($task == 'add')
		                                        {
		                                            ?>
		                                                <option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>>--- None ---</option>
		                                            <?php
		                                        }
		                                    }
		                                    else
		                                    {
		                                        ?>
		                                            <option value="">--- No Vendor Found ---</option>
		                                        <?php
		                                    }
		                                    ?>
		                                </select>
		                          	</div>
		                          	<label for="inputName" class="col-sm-3 control-label">Tgl. Rekom. </label>
		                        </div>
		                        <div class="form-group">
		                       	  	<label for="inputName" class="col-sm-3 control-label"><?php echo $PaymentType ?> </label>
		                          	<div class="col-sm-2">
                                		<input type="hidden" class="form-control" name="PO_PAYTYPE" id="PO_PAYTYPE" size="30" value="<?php echo $PO_PAYTYPE; ?>" />
		                                <select name="PO_PAYTYPEX" id="PO_PAYTYPEX" class="form-control" onChange="selPO_PAYTYPE(this.value)" disabled>
		                                    <option value="0" <?php if($PO_PAYTYPE == 0) { ?> selected="selected" <?php } ?>>Cash</option>
		                                    <option value="1" <?php if($PO_PAYTYPE == 1) { ?> selected="selected" <?php } ?>>Credit</option>
		                                </select>
                                	</div>
                                	<div class="col-sm-4">
                                		<input type="hidden" class="form-control" name="PO_TENOR" id="PO_TENOR" size="30" value="<?php echo $PO_TENOR; ?>" />
		                                <select name="PO_TENORX" id="PO_TENORX" class="form-control" onChange="selPO_TENOR(this.value)" disabled >
		                                    <option value="0" <?php if($PO_TENOR == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($PO_TENOR == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="14" <?php if($PO_TENOR == 14) { ?> selected <?php } ?>>14 Days</option>
		                                    <option value="21" <?php if($PO_TENOR == 21) { ?> selected <?php } ?>>21 Days</option>
		                                    <option value="30" <?php if($PO_TENOR == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($PO_TENOR == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($PO_TENOR == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="75" <?php if($PO_TENOR == 75) { ?> selected <?php } ?>>75 Days</option>
		                                    <option value="90" <?php if($PO_TENOR == 90) { ?> selected <?php } ?>>90 Days</option>
		                                    <option value="120" <?php if($PO_TENOR == 120) { ?> selected <?php } ?>>120 Days</option>
		                                </select>
                                	</div>
		                          	<div class="col-sm-3">
		                                <div class="input-group date">
		                                    <input type="text" name="RECOM_DATE1" class="form-control pull-left" id="RECOM_DATE1" value="<?php echo $RECOM_DATE; ?>" disabled>
		                                    <input type="hidden" name="RECOM_DATE" class="form-control pull-left" id="datepicker2" value="<?php echo $RECOM_DATE; ?>">
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $QuotNo; ?></label>
		                            <div class="col-sm-3">
		                                <input type="text" class="form-control" name="PO_REFRENS" id="PO_REFRENS" size="30" value="<?php echo $PO_REFRENS; ?>" readonly />
		                            </div>
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ContractNo; ?></label>
		                            <div class="col-sm-3">
		                                <input type="text" class="form-control" name="PO_CONTRNO" id="PO_CONTRNO" size="30" value="<?php echo $PO_CONTRNO; ?>" readonly />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $payMethod; ?></label>
		                            <div class="col-sm-9">
		                                <!-- <input type="text" class="form-control" name="PO_PAYNOTES" id="PO_PAYNOTES" value="<?php // echo $PO_PAYNOTES; ?>" readonly /> -->
										<textarea class="form-control" name="PO_PAYNOTES" id="PO_PAYNOTES" rows="4" readonly><?php echo $PO_PAYNOTES; ?></textarea>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-6">
		                            	<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default" style="text-align: left; width: 100%;">
						                	<font size="2" id="PO_TOTCOSTD1">Total OP</font><br>
						                	<font size="4" id="PO_TOTCOSTD2"><?php echo number_format($PO_TOTCOST, 2); ?></font>
						              	</button>
		                            </div>
		                            <div class="col-sm-3">
					                	<label for="inputName" class="control-label">DP (%)</label>
				                        <input type="text" class="form-control" style="text-align:right;" name="PO_DPPERX" id="PO_DPPERX" value="<?php echo number_format($PO_DPPER, $decFormat); ?>" onBlur="getDPer()">
		                                <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PO_DPPER" id="PO_DPPER" value="<?php echo $PO_DPPER; ?>">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="PO_DPVAL" id="PO_DPVAL" value="<?php echo $PO_DPVAL; ?>">
			                            <input type="hidden" class="form-control" style="text-align:right;" name="PO_DPVALX" id="PO_DPVALX" value="<?php echo number_format($PO_DPVAL, 2); ?>">
		                                <input type="hidden" class="form-control" name="PO_TOTCOSTX" id="PO_TOTCOSTX" size="30" style="text-align: right;" value="<?php echo number_format($PO_TOTCOST, 2); ?>" title="Total PO" readonly />
		                                <input type="hidden" class="form-control" name="PO_TOTCOST" id="PO_TOTCOST" size="30" value="<?php echo $PO_TOTCOST; ?>" title="Total PO" />
		                            </div>
		                        </div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptLoc; ?></label>
		                            <div class="col-sm-9">
		                                <div class="row">
		                                	<div class="col-xs-6">
				                                <input type="text" class="form-control" name="PO_RECEIVLOC1" id="PO_RECEIVLOC1" size="30" value="<?php echo $PO_RECEIVLOC; ?>" readonly/>
				                                <input type="hidden" class="form-control" style="max-width:200px;" name="PO_RECEIVLOC" id="PO_RECEIVLOC" size="30" value="<?php echo $PO_RECEIVLOC; ?>" />
		                                	</div>
		                                	<div class="col-xs-6">
				                                <div class="input-group date">
				                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                                    <input type="text" name="PO_PLANIR" class="form-control pull-left" id="datepicker2" value="<?php echo $PO_PLANIR; ?>" style="width:100px" readonly>
				                                </div>
		                              		</div>
		                              	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Receiver ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="PO_RECEIVCP1" id="PO_RECEIVCP1" size="30" value="<?php echo $PO_RECEIVCP; ?>" readonly />
		                                <input type="hidden" class="form-control" style="max-width:150px;" name="PO_RECEIVCP" id="PO_RECEIVCP" size="30" value="<?php echo $PO_RECEIVCP; ?>" />
		                          	</div>
		                        </div>
								<div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo "Ket. Pengiriman"; ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="PO_NOTESIR"  id="PO_NOTESIR" rows="1" readonly><?php echo $PO_NOTESIR; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $SentRoles; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="PO_SENTROLES1" id="PO_SENTROLES1" value="<?php echo $PO_SENTROLES; ?>" readonly />
		                                <input type="hidden" class="form-control" name="PO_SENTROLES" id="PO_SENTROLES" value="<?php echo $PO_SENTROLES; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="PO_NOTES"  id="PO_NOTES" style="height:75px" readonly ><?php echo $PO_NOTES; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes; ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="PO_NOTES1"  id="PO_NOTES1" style="height:70px"><?php echo $PO_NOTES1; ?></textarea>
		                          	</div>
		                        </div>
		                        <div id="revMemo" class="form-group" <?php if($PO_MEMO == '') { ?> style="display:none" <?php } ?>>
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="PO_MEMO"  id="PO_MEMO" style="height:63px"><?php echo $PO_MEMO; ?></textarea>
		                          	</div>
		                        </div>
		                        <!--
		                        	APPROVE STATUS
		                            1 - New
		                            2 - Confirm
		                            3 - Approve
		                        -->
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PO_STAT; ?>">
		                            	<input type="hidden" name="PO_STATx" id="PO_STATx" value="<?php echo $PO_STAT; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PO_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;

														if($shwBtn == 1)
														{
															?>
																<select name="PO_STAT" id="PO_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> --- </option>
																	<option value="3"<?php if($PO_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																	<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																	<option value="5"<?php if($PO_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																	<!-- <option value="6"<?php if($PO_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																	<option value="7"<?php if($PO_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
																</select>
															<?php
														}
														elseif($shwBtn == 0)
														{
															?>
																<select name="PO_STAT" id="PO_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																	<option value="0"> --- </option>
																	<option value="3"<?php if($PO_STAT == 3) { ?> selected <?php } ?> disabled>Approved</option>
																	<option value="4"<?php if($PO_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																	<option value="5"<?php if($PO_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																	<!-- <option value="6"<?php if($PO_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																	<option value="7"<?php if($PO_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
																</select>
															<?php
														}
													}
													else
													{
														?>
															<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs">
																<?php echo $descApp; ?>
															</a>
														<?php
													}
												}
												else
												{
													?>
														<a href="" class="btn btn-danger btn-xs">
															Step approval not set;
														</a>
													<?php
												}
											// END : FOR ALL APPROVAL FUNCTION
		                                ?>
		                            </div>
		                        </div>
								<script>
		                            function selStat(thisVal)
		                            {
										/*document.getElementById('PO_STATx').value = thisVal;
		                                if(thisVal == 4)
		                                {
		                                    document.getElementById('revMemo').style.display = '';
		                                }
		                                else
		                                {
		                                    document.getElementById('revMemo').style.display = 'none';
		                                }*/
		                            }
		                        </script>
		                        <?php if($PO_STAT == 9) 
								{ 
									?>
		                                <div class="form-group">
		                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProcessStatus ?> </label>
		                                    <div class="col-sm-9">
		                                        <select name="OP_PROCS" id="OP_PROCS" class="form-control" style="max-width:130px" <?php if($OP_PROCS != 1) { ?> disabled <?php } ?>>
		                                            <option value="1" <?php if($OP_PROCS == 1) { ?> selected <?php } ?>>Processing</option>
		                                            <option value="2" <?php if($OP_PROCS == 2) { ?> selected <?php } ?>>Finish</option>
		                                            <option value="3" <?php if($OP_PROCS == 3) { ?> selected <?php } ?>>Canceled</option>
		                                        </select>
		                                    </div>
		                                </div>
									<?php
								}
								$url_AddItem	= site_url('c_purchase/c_p180c21o/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								?>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
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
										<a href="javascript:void(null);" onClick="selectitem();">
											Add Item [+]
		                                </a></div>
		                        </div>
							</div>
						</div>
					</div>

					<?php
						$shAttc 	= 0;
						if($PO_STAT == 1 || $PO_STAT == 4)
						{
							$shAttc = 1;
							$shTInp = 1;
							$smTAtt = 4;
							$smTDok = 8;
						}
						else
						{
							$shTInp = 0;
							$smTAtt = 4;
							$smTDok = 12;
							$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx WHERE REF_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
							$resUPL_DOC = $this->db->query($getUPL_DOC);
							if($resUPL_DOC->num_rows() > 0)
								$shAttc = 1;
						}
					?>

					<div class="col-md-12" <?php if($shAttc == 0) { ?> style="display: none;" <?php } ?>>
						<div class="box box-default collapsed-box">
							<div class="box-header with-border">
								<label for="inputName"><?php echo $dokLam; ?></label>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
									</button>
              					</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<div class="col-sm-4" <?php if($shTInp == 0) { ?> style="display: none;" <?php } ?>>
				                		<input type="file" class="form-control" name="userfile[]" id="userfile" accept=".pdf" multiple>
										<span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span>
				                	</div>
									<div class="col-sm-<?=$smTDok?>">
										<?php
											// GET Upload Doc TRx
											$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
															WHERE REF_NUM = '$PO_NUM' AND PRJCODE = '$PRJCODE'";
											$resUPL_DOC = $this->db->query($getUPL_DOC);
											if($resUPL_DOC->num_rows() > 0)
											{
												?>
													<label>List Uploaded</label>
													<div class="uploaded_area">
												<?php
													$newRow = 0;
													foreach($resUPL_DOC->result() as $rDOC):
														$newRow 		= $newRow + 1;
														$UPL_NUM		= $rDOC->UPL_NUM;
														$REF_NUM		= $rDOC->REF_NUM;
														$REF_CODE		= $rDOC->REF_CODE;
														$UPL_PRJCODE	= $rDOC->PRJCODE;
														$UPL_DATE		= $rDOC->UPL_DATE;
														$UPL_FILENAME	= $rDOC->UPL_FILENAME;
														$UPL_FILESIZE	= $rDOC->UPL_FILESIZE;
														$UPL_FILETYPE	= $rDOC->UPL_FILETYPE;

														?>
															<div class="itemFile_<?=$newRow?>">
																<?php
																	if($UPL_FILETYPE == 'application/pdf') $fileicon = "fa-file-pdf-o";
																	else $fileicon = "fa-file-image-o";

																	if($PO_STAT == 1 || $PO_STAT == 4)
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<!-- Download => Hold 
																					<a href="<?php // echo site_url("c_purchase/c_p180c21o/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
																						<?php // echo $UPL_FILENAME; ?>
																					</a>
																					---------------------- End Hold -------------------------->
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- Hapus File -->
																					<a href="#" onclick="trashItemFile(<?=$newRow?>, '<?php echo $UPL_FILENAME;?>')" title="Hapus File">
																						<i class="fa fa-trash" style="color: red;"></i> Delete
																					</a> 
																					&nbsp;&nbsp;&nbsp;
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<!-- Download File -->
																					<?php
																						$path 		= "https://sdbpplus.nke.co.id/c_purchase/c_p180c21o/downloadFile/?file=$UPL_FILENAME&prjCode=$UPL_PRJCODE";
																					?>
																					<a href="<?php echo $path; ?>" title="Download File">
																						<i class="fa fa-download" style="color: green;"></i> Download
																					</a>
																				</div>
																			</div>
																			
																		<?php
																	}
																	else
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<!-- Download => Hold 
																					<a href="<?php // echo site_url("c_purchase/c_p180c21o/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
																						<?php // echo $UPL_FILENAME; ?>
																					</a>
																					---------------------- End Hold -------------------------->
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<!-- Download File -->
																					<?php
																						$path 		= "https://sdbpplus.nke.co.id/c_purchase/c_p180c21o/downloadFile/?file=$UPL_FILENAME&prjCode=$UPL_PRJCODE";
																					?>
																					<a href="<?php echo $path; ?>" title="Download File">
																						<i class="fa fa-download" style="color: green;"></i> Download
																					</a>
																				</div>
																			</div>
																		<?php
																	}
																?>
															</div>
														<?php
													endforeach;

												?>
													</div>
												<?php
											}
										?>
									</div>
				                </div>
							</div>
						</div>
					</div>

					<div class="col-md-12" <?php if($shAttc == 1) { ?> style="display: none;" <?php } ?>>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-ban"></i> <?=$noAtth?>
                        </div>
					</div>

	                <?php
						if($resCMJR > 0)
						{
							?>
	                            <div class="col-sm-12">
	                                <div class="callout callout-info">
	                                    <?php echo $alert3; ?>
	                                </div>
	                            </div>
	                		<?php
						}
						
                		if($shwBtn == 0)
                		{
                		?>
                            <div class="col-sm-12">
                    			<div class="alert alert-danger alert-dismissible">
					                Tanggal rencana terima lebih kecil dari tanggal PO. Silahkan revisi terlebih dahulu ... !
				              	</div>
			              	</div>
                		<?php
                		}
					?>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center">No.</th>
                                      	<th width="7%" style="text-align:center; display:none" nowrap><?php echo $ItemCode; ?> </th>
                                      	<th width="25%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemName; ?> </th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Request; ?></th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Remain; ?></th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Quantity; ?> </th> 
                              			<!-- Input Manual -->
                                        <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Unit; ?> </th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" ><?php echo $UnitPrice; ?> </th>
                                        <!-- <th style="text-align:center; vertical-align: middle;" nowrap><?php // echo "Ongkos Angkut"; ?> </th> -->
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?><br>
                                      	(%)</th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?> </th>
                                      	<th width="9%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Tax; ?></th>
                                      	<th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $TotalPrice; ?></th>
                                      	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Specification; ?></th>
                                 	</tr>
                                    <?php
                                        $resultC	= 0;

                                        if($task == 'edit')
                                        {
                                            $sqlDET		= "SELECT A.PO_ID, A.PO_NUM, A.PO_DATE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, 
                                                                A.ITM_CODE, B.ITM_NAME, A.ITM_GROUP, A.ITM_CATEG, A.ITM_UNIT, A.ITM_UNIT2, 
                                                                A.PO_PRICE AS ITM_PRICE, A.PO_OA, A.PR_VOLM, A.PO_VOLM, A.PO_PRICE, A.PO_COST, A.PO_CVOL, A.PO_CTOTAL,
                                                                A.IR_VOLM, A.IR_AMOUNT, A.PO_DISP, 
                                                                A.PO_COST, A.PO_DISC, A.PO_DESC,A.PO_DESC1, A.TAXCODE1, 
                                                                A.TAXCODE2, A.TAXPRICE1,
                                                                A.TAXPRICE2, A.PRD_ID, A.JOBPARENT, A.JOBPARDESC,
                                                                A.PR_DESC_ID, A.PO_DESC_ID, A.PR_DESC_ID, A.PR_DESC_ID, A.PR_DESC_ID, A.PR_DESC_ID, A.PR_DESC_ID
                                                            FROM tbl_po_detail A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                            WHERE 
                                                                A.PO_NUM = '$PO_NUM' 
                                                                AND B.PRJCODE = '$PRJCODE'";


                                            $result = $this->db->query($sqlDET)->result();
                                            // count data
                                            $sqlDETC	= "tbl_po_detail A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                            WHERE 
                                                                A.PO_NUM = '$PO_NUM' 
                                                                AND B.PRJCODE = '$PRJCODE'";
                                            $resultC 	= $this->db->count_all($sqlDETC);
                                        }

                                        $i			= 0;
                                        $j			= 0;
                                        if($resultC > 0)
                                        {
                                            $GT_ITMPRICE	= 0;
                                            foreach($result as $row) :
                                                $noU  			= ++$i;
                                                $PO_NUM 		= $PO_NUM;
                                                $PO_CODE 		= $PO_CODE;
                                                $PRJCODE		= $PRJCODE;
                                                $PO_ID			= $row->PO_ID;
                                                $PR_NUM			= $row->PR_NUM;
                                                $JOBCODEDET		= $row->JOBCODEDET;
                                                $JOBCODEID		= $row->JOBCODEID;
                                                $JOBPARENT		= $row->JOBPARENT;
                                                $JOBPARDESC		= $row->JOBPARDESC;
                                                $JOBDESC		= $JOBPARDESC;
                                                $PRD_ID 		= $row->PRD_ID;
                                                $ITM_CODE 		= $row->ITM_CODE;
                                                $ITM_NAME 		= $row->ITM_NAME;
												$ITM_NAME 		= wordwrap($ITM_NAME, 60, "<br>", TRUE);
                                                $ITM_GROUP 		= $row->ITM_GROUP;
                                                $ITM_CATEG 		= $row->ITM_CATEG;
                                                $ITM_UNIT 		= strtoupper($row->ITM_UNIT);
                                                $ITM_UNIT2 		= strtoupper($row->ITM_UNIT2);
                                                $ITM_PRICE 		= $row->ITM_PRICE;
                                                $PR_VOLM 		= $row->PR_VOLM;
                                                $PR_AMOUNT		= $PR_VOLM * $ITM_PRICE;
                                                $PO_VOLM 		= $row->PO_VOLM;
                                                if($PO_VOLM == '')
                                                    $PO_VOLM	= 0;
                                                $PO_CVOL 		= $row->PO_CVOL;
                                                $PO_CTOTAL 		= $row->PO_CTOTAL;
                                                $PO_PRICE 		= $row->PO_PRICE;
                                                $PO_COST 		= $row->PO_COST;
												$PO_OA 			= $row->PO_OA;
                                                $IR_VOLM 		= $row->IR_VOLM;
                                                $IR_PRICE 		= $row->IR_AMOUNT;
                                                $PO_DISP 		= $row->PO_DISP;
                                                $PO_DISC 		= $row->PO_DISC;

												// START : GET BUDGET
													$s_BUDGVAL		= "SELECT ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
																		PR_VOL, PR_VOL_R, PR_CVOL, PO_VOL, PO_VOL_R, PO_CVOL, PO_VAL, PO_VAL_R, PO_CVAL,
																		UM_VOL, UM_VAL, UM_VOL_R, UM_VAL_R,
																		WO_VOL, WO_VAL, WO_VOL_R, WO_VAL_R, WO_CVOL, WO_CVAL, OPN_VOL, OPN_VAL, OPN_VOL_R, OPN_VAL_R,
																		VCASH_VOL, VCASH_VAL, VCASH_VOL_R, VCASH_VAL_R, VLK_VOL, VLK_VAL, VLK_VOL_R, VLK_VAL_R,
																		PPD_VOL, PPD_VAL, PPD_VOL_R, PPD_VAL_R,
																		AMD_VOL, AMD_VOL_R, AMD_VAL, AMD_VAL_R, AMDM_VOL, AMDM_VAL
																		FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
													$r_BUDGVAL		= $this->db->query($s_BUDGVAL)->result();
													foreach($r_BUDGVAL as $rowJOBDR) :
														$ITM_VOLM 		= $rowJOBDR->ITM_VOLM;		// BUDG VOL
														$RAP_PRICE 		= $rowJOBDR->ITM_PRICE;		// BUDG PRICE
														$ITM_LASTP 		= $rowJOBDR->ITM_LASTP;		// LAST PRICE
														$ITM_BUDG 		= $rowJOBDR->ITM_BUDG;		// BUDG VAL
														$PR_VOL 		= $rowJOBDR->PR_VOL;		// SPP VOL
														$PR_VOL_R 		= $rowJOBDR->PR_VOL_R;		// SPP VOL_R
														$PR_CVOL 		= $rowJOBDR->PR_CVOL;		// SPP CANCEL VOL

														$PO_VOL 		= $rowJOBDR->PO_VOL; 		// PO VOL
														$PO_VOL_R 		= $rowJOBDR->PO_VOL_R; 		// PO VOL_R
														$PO_VAL 		= $rowJOBDR->PO_VAL; 		// PO VAL
														$PO_VAL_R 		= $rowJOBDR->PO_VAL_R; 		// PO VAL_R
														$PO_CVOL 		= $rowJOBDR->PO_CVOL; 		// PO CANCEL VOL
														$PO_CVAL 		= $rowJOBDR->PO_CVAL; 		// PO CANCEL VAL

														$UM_VOL 		= $rowJOBDR->UM_VOL; 		// UM VOL
														$UM_VOL_R 		= $rowJOBDR->UM_VOL_R; 		// UM VOL_R
														$UM_VAL 		= $rowJOBDR->UM_VAL; 		// UM VAL
														$UM_VAL_R 		= $rowJOBDR->UM_VAL_R; 		// UM VAL_R

														$WO_VOL 		= $rowJOBDR->WO_VOL; 		// SPK VOL
														$WO_VOL_R 		= $rowJOBDR->WO_VOL_R; 		// SPK VOL_R
														$WO_VAL 		= $rowJOBDR->WO_VAL; 		// SPK VAL
														$WO_VAL_R 		= $rowJOBDR->WO_VAL_R; 		// SPK VAL_R
														$WO_CVOL 		= $rowJOBDR->WO_CVOL; 		// SPK CANCEL VOL
														$WO_CVAL 		= $rowJOBDR->WO_CVAL; 		// SPK CANCEL VAL_R

														$OPN_VOL 		= $rowJOBDR->OPN_VOL; 		// OPN VOL
														$OPN_VOL_R 		= $rowJOBDR->OPN_VOL_R; 	// OPN VOL_R
														$OPN_VAL 		= $rowJOBDR->OPN_VAL; 		// OPN VAL
														$OPN_VAL_R 		= $rowJOBDR->OPN_VAL_R; 	// OPN VAL_R

														$VCASH_VOL 		= $rowJOBDR->VCASH_VOL; 	// VCASH VOL
														$VCASH_VOL_R 	= $rowJOBDR->VCASH_VOL_R; 	// VCASH VOL_R
														$VCASH_VAL 		= $rowJOBDR->VCASH_VAL; 	// VCASH VAL
														$VCASH_VAL_R 	= $rowJOBDR->VCASH_VAL_R; 	// VCASH VAL_R

														$VLK_VOL 		= $rowJOBDR->VLK_VOL; 		// VLK VOL
														$VLK_VOL_R 		= $rowJOBDR->VLK_VOL_R; 	// VLK VOL_R
														$VLK_VAL 		= $rowJOBDR->VLK_VAL; 		// VLK VAL
														$VLK_VAL_R 		= $rowJOBDR->VLK_VAL_R; 	// VLK VAL_R

														$PPD_VOL 		= $rowJOBDR->PPD_VOL; 		// PPD VOL
														$PPD_VOL_R 		= $rowJOBDR->PPD_VOL_R; 	// PPD VAL_R
														$PPD_VAL 		= $rowJOBDR->PPD_VAL; 		// PPD VOL
														$PPD_VAL_R 		= $rowJOBDR->PPD_VAL_R; 	// PPD VAL_R

														$AMD_VOL 		= $rowJOBDR->AMD_VOL; 		// AMD VOL
														$AMD_VOL_R 		= $rowJOBDR->AMD_VOL_R; 	// AMD VOL_R
														$AMD_VAL 		= $rowJOBDR->AMD_VAL; 		// AMD VAL
														$AMD_VAL_R 		= $rowJOBDR->AMD_VAL_R; 	// AMD VAL_R
														$AMDM_VOL 		= $rowJOBDR->AMDM_VOL; 		// AMD MIN VOL
														$AMDM_VAL 		= $rowJOBDR->AMDM_VAL; 		// AMD MIN VAL

														$TREQ_VOL 		= ($PR_VOL - $PR_CVOL) + ($WO_VOL - $PO_CVOL) + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
														$TREQ_VAL 		= ($PO_VAL - $PO_CVAL) + ($WO_VAL - $WO_CVAL) + $VCASH_VAL + $VLK_VAL + $PPD_VAL;

														$TREQ_VOL_R		= $PR_VOL_R + $WO_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;		// VOL REQUEST NEW, CONFIRMED &  WAITING
														$TREQ_VAL_R		= $PO_VAL_R + $WO_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;		// VAL REQUEST NEW, CONFIRMED &  WAITING

														$TUSED_VOL 		= $UM_VOL + $OPN_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
														$TUSED_VOL_R	= $UM_VOL_R + $OPN_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;		// VAL PENGGUNAAN NEW, CONFIRMED &  WAITING
														$TUSED_VAL 		= $UM_VAL + $OPN_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
														$TUSED_VAL_R	= $UM_VAL_R + $OPN_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;		// VAL PENGGUNAAN NEW, CONFIRMED &  WAITING
													endforeach;
												// END : GET BUDGET

												$TBUDG_VOL	= $ITM_VOLM + $AMD_VOL - $AMDM_VOL;
												if($TBUDG_VOL == '')
													$TBUDG_VOL	= 0;

												$TBUDG_VAL	= $ITM_BUDG + $AMD_VAL - $AMDM_VAL;
												if($TBUDG_VAL == '')
													$TBUDG_VAL	= 0;

												$ITM_REMBUDVOL	= $TBUDG_VOL - $TREQ_VOL - $TREQ_VOL_R;
												$ITM_REMBUDVAL	= $TBUDG_VAL - $TREQ_VAL - $TREQ_VAL_R;

												$RAP_PRICE 		= $RAP_PRICE;
				
												// START : TOTAL VOL/VAL YANG SUDAH DIBUATKAN PO ATAS SPP YANG TERPILIH
													$TOT_PO_VOL		= 0;
													$TOT_PO_VAL		= 0;
													$TEMP_PO_VOL	= 0;
													$TEMP_PO_VAL	= 0;
													/* hide on 19-01-202 */
													$s_TOTPOVOL0		= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ORDERED_VOL, IFNULL(SUM(A.PO_AMOUNT), 0) AS ORDERED_VAL,
																				IFNULL(SUM(A.PO_TMP_VOL), 0) AS ORDERED_TMP_VOL, IFNULL(SUM(A.PO_TMP_VAL), 0) AS ORDERED_TMP_VAL
																			FROM tbl_pr_detail A
																			WHERE A.PRJCODE = '$PRJCODE'
																				AND A.JOBCODEID = '$JOBCODEID'
																				AND A.PR_NUM = '$PR_NUM'
																				AND A.ITM_CODE = '$ITM_CODE'
																				AND A.PR_ID = $PRD_ID";
													$r_TOTPOVOL0		= $this->db->query($s_TOTPOVOL0)->result();
													foreach($r_TOTPOVOL0 as $rw_TOTPOVOL0) :
														$TOT_PO_VOL		= $rw_TOTPOVOL0->ORDERED_VOL;
														$TOT_PO_VAL		= $rw_TOTPOVOL0->ORDERED_VAL;
														$TEMP_PO_VOL	= $rw_TOTPOVOL0->ORDERED_TMP_VOL;
														$TEMP_PO_VAL	= $rw_TOTPOVOL0->ORDERED_TMP_VAL;
													endforeach;

													$TOT_PO_VOL 	= $TOT_PO_VOL;
													$ORDERED_VAL 	= $TOT_PO_VOL;
													$TOTVAL_OTHDOC 	= $ORDERED_VAL;
												// END : TOTAL VOL/VAL YANG SUDAH DIBUATKAN PO ATAS SPP YANG TERPILIH

												// 1. FROM CURRENT DOCUMENT AND OTHERS ROW
													$TOT_PO_VOLC		= 0;
													$TOT_PO_VALC		= 0;
													$ORDERED_CVOLC		= 0;
													$ORDERED_CVALC		= 0;
													$s_TOTPOVOL0C		= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS TOT_PO_VOL, IFNULL(SUM(A.PO_COST), 0) AS TOT_PO_VAL,
																				IFNULL(SUM(A.PO_CVOL), 0) AS ORDERED_CVOL, IFNULL(SUM(A.PO_CTOTAL), 0) AS ORDERED_CVAL
																				FROM tbl_po_detail A
																				INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
																			WHERE B.PRJCODE = '$PRJCODE'
																				AND A.JOBCODEID = '$JOBCODEID'
																				AND A.PO_NUM = '$PO_NUM'
																				AND A.PR_NUM = '$PR_NUM'
																				AND A.ITM_CODE = '$ITM_CODE'
																				AND A.PRD_ID = $PRD_ID";
													$r_TOTPOVOL0C		= $this->db->query($s_TOTPOVOL0C)->result();
													foreach($r_TOTPOVOL0C as $rw_TOTPOVOL0C) :
														$TOT_PO_VOLC	= $rw_TOTPOVOL0C->TOT_PO_VOL;
														$TOT_PO_VALC	= $rw_TOTPOVOL0C->TOT_PO_VAL;
														$ORDERED_CVOLC 	= $rw_TOTPOVOL0C->ORDERED_CVOL;
														$ORDERED_CVALC 	= $rw_TOTPOVOL0C->ORDERED_CVAL;
													endforeach;

												//$ITM_REMVAL	= $ITM_REMBUDVAL - $TEMP_PO_VAL + $TOT_PO_VALC;
												$ITM_REMVAL		= $ITM_REMBUDVAL + $TOT_PO_VALC;

												// START : SISA BUDGET VOLUME SPP PER PR ID
													//$REM_SPP_VOLV = "$PR_VOLM - $TOT_PO_VOL - $TEMP_PO_VOL + $TOT_PO_VOLC";
													//$REM_SPP_VOL 	= $PR_VOLM - $TOT_PO_VOL - $TEMP_PO_VOL + $TOT_PO_VOLC;
													$REM_SPP_VOL 	= $PR_VOLM - $TOT_PO_VOL + $TOT_PO_VOLC;
													//$REM_SPP_VAL 	= $PR_VOLM - $TOT_PO_VAL - $TEMP_PO_VAL + $TOT_PO_VALC;
													$REM_SPP_VAL 	= $PR_VOLM - $TOT_PO_VAL + $TOT_PO_VALC;
												// END : SISA BUDGET VOLUME SPP PER PR ID

												// IS LS UNIT
													$s_ISLS 		= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
													$isLS 			= $this->db->count_all($s_ISLS);

												// ADA REGULASI BARU UNTUK OP OVERHEAD
													$ITM_AVAIL 		= 0;

													if($REM_SPP_VOL > 0 && $REM_SPP_VAL > 0) 			// ITEM LS AND NON-LS TYPE, VOL AND VAL MUST BE GREATHER
														$ITM_AVAIL	= 1;

												$JOBDESC		= $JOBPARDESC;

												$PR_DESC_ID		= $row->PR_DESC_ID;
												if($PR_DESC_ID == '')
													$PR_DESC_ID = 0;
												
												$PO_DESC_ID		= $row->PO_DESC_ID;
												if($PO_DESC_ID == '')
													$PO_DESC_ID = 0;

												$PO_DESC		= $row->PO_DESC;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXPRICE2		= $row->TAXPRICE2;

												$PO_COST 		= $row->PO_COST;			// Non-PPn
												$itemConvertion	= 1;

												// GET PO_VOLM GROUP BY ITM_CODE, PO_DESC_ID
													$getPO_R 	= "SELECT IFNULL(SUM(A.PO_VOLM), 0) AS ITMVOLM_R
																		FROM tbl_po_detail A
																	WHERE A.PRJCODE = '$PRJCODE' AND A.PO_NUM = '$PO_NUM'
																	AND A.ITM_CODE = '$ITM_CODE' AND A.PO_DESC_ID = '$PO_DESC_ID'";
													$resPO_R 	= $this->db->query($getPO_R);
													foreach($resPO_R->result() as $rPO_R):
														$ITMVOLM_R 	= $rPO_R->ITMVOLM_R;
													endforeach;

												$PO_COSTnPPn	= $PO_COST + $TAXPRICE1 - $TAXPRICE2;

												$TAXLA_PERC = 0;
												if($TAXCODE1 != '')
												{
													$TAXLA_DESC = "-";
													$sPPN		= "SELECT TAXLA_NUM, TAXLA_CODE, TAXLA_DESC
																	FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
													$rPPN		= $this->db->query($sPPN)->result();
													foreach($rPPN as $rwPPN) :
														$TAXLA_NUM	= $rwPPN->TAXLA_NUM;
														$TAXLA_DESC	= $rwPPN->TAXLA_DESC;
													endforeach;
												}

												$BTN_CNCVW = $noU;

												if($ORDERED_CVOLC != 0) $PO_CVOLv = "<div style='color:red;'><i class='fa fa-sort-down (alias)'></i>".number_format($ORDERED_CVOLC, 2)."</div>";
												else $PO_CVOLv = "";

												$vWPO_ORDER 	= number_format($PO_VOLM, 2)."$PO_CVOLv";
												$vWITM_PRICE 	= number_format($ITM_PRICE, 2);
												$vWPO_OA 		= number_format($PO_OA, 2);

												if($ITM_CATEG != 'UA') $PR_VOLMv = number_format($PR_VOLM, 2);
												else $PR_VOLMv = "-";
												if($ITM_CATEG != 'UA') $ITM_REMVOLv = number_format($REM_SPP_VOL, 2);
												else $ITM_REMVOLv = "-";
												if($ITM_CATEG != 'UA') $PO_DISPv = number_format($PO_DISP, 2);
												else $PO_DISPv = "-";
												if($ITM_CATEG != 'UA') $PO_DISCv = number_format($PO_DISC, 2);
												else $PO_DISCv = "-";

												$ITM_TAX1v = "-<input type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1'  value='0'>";
												if($ITM_CATEG != 'UA') 
												{
													if($TAXCODE1 != '') $ITM_TAX1v = "$TAXLA_DESC<input style='text-align:right' type='hidden' name='data[".$noU."][TAXCODE1]' id='data".$noU."TAXCODE1' value='".$TAXCODE1."'>";
												}

												$PO_CTOTALnPPn	= $PO_CTOTAL + ($PO_CTOTAL * $TAXLA_PERC/100);
												if($PO_CTOTALnPPn != 0) $PO_CTOTALnPPnv = "<div style='color:red;'><i class='fa fa-sort-down (alias)'></i> ".number_format($PO_CTOTALnPPn, 2)."</div>";
												else $PO_CTOTALnPPnv = "";

												$vWPO_COSTnPPn	= number_format($PO_COSTnPPn, 2)."$PO_CTOTALnPPnv";

												$PO_DESCv 	= $PO_DESC;

												$vwIRDET	= "$PRJCODE~$PO_NUM~$PO_CODE~$JOBCODEID~$JOBDESC~$JOBPARENT~$JOBPARDESC~$ITM_CODE~$PO_ID~$PO_VOLM~$PO_CVOL";
												$secvwIRD	= site_url('c_purchase/c_p180c21o/shwIR_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwIRDET));





                                                
                                                // GET TOTAL ORDERED
                                                $ORDERED_QTY	= 0;
                                                $sqlTOTORD		= "SELECT SUM(A.PO_VOLM) AS ORDERED_QTY 
                                                                    FROM tbl_po_detail A
                                                                        INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
                                                                    WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEDET = '$JOBCODEDET'
																		AND A.JOBCODEID = '$JOBCODEID'
																		AND A.PO_NUM != '$PO_NUM'
                                                                    	AND A.PR_NUM = '$PR_NUM' AND B.PO_STAT IN (2,3,6)
                                                                    	AND A.PRD_ID = $PRD_ID";
                                                $resTOTORD		= $this->db->query($sqlTOTORD)->result();
                                                foreach($resTOTORD as $rowTOTORD) :
                                                    $ORDERED_QTY  	= $rowTOTORD->ORDERED_QTY;
                                                endforeach;
                                                
                                                // GET BUDGET
	                                                $RAP_PRICE 		= $ITM_PRICE;
	                                                $s_BUDGVAL		= "SELECT  ITM_PRICE AS RAP_PRICE
	                                                					FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
	                                                $r_BUDGVAL		= $this->db->query($s_BUDGVAL)->result();
	                                                foreach($r_BUDGVAL as $rw_BUDGVAL) :
	                                                    $RAP_PRICE 	= $rw_BUDGVAL->RAP_PRICE;
	                                                endforeach;
                                                
                                                $ORDERED_QTY	= $ORDERED_QTY;
                                                //$PO_COST 		= $PO_VOLM * $ITM_PRICE;
                                                if($task == 'add')
                                                    $PO_COST 	= $PR_VOLM * $ITM_PRICE;
                                                else
                                                    $PO_COST 	= $row->PO_COST;			// Non-PPn
                                                    
                                                $PR_DESC		= $row->PO_DESC;
                                                $PO_DES1		= $row->PO_DESC1;
                                                $TAXCODE1		= $row->TAXCODE1;
                                                $TAXCODE2		= $row->TAXCODE2;
                                                $TAXPRICE1		= $row->TAXPRICE1;
                                                $TAXPRICE2		= $row->TAXPRICE2;
                                                $itemConvertion	= 1;
                                                
                                                $ITM_REMAIN		= $PR_VOLM - $ORDERED_QTY;
                                                if($task == 'add')
                                                    $PO_VOLM	= $PR_VOLM - $ORDERED_QTY;
                                                else
                                                    $PO_VOLM	= $PO_VOLM;
                                                
                                                if($TAXCODE1 == 'TAX01')
                                                {
                                                    //$GT_ITMPRICE= $GT_ITMPRICE + $PO_COST + $TAXPRICE1;
                                                }
                                                if($TAXCODE1 == 'TAX02')
                                                {
                                                    //$GT_ITMPRICE= $GT_ITMPRICE + $PO_COST - $TAXPRICE1;
                                                }

                                                // Update [iyan] - GET ITM_CATEG
                                                $ITM_CATEG = $this->db->get_where('tbl_item', ['ITM_CODE' => $ITM_CODE])->row('ITM_CATEG');
                                                // End Update [iyan]
                                                
                                                $PO_COSTnPPn	= $PO_COST + $TAXPRICE1 - $TAXPRICE2;
                                    
                                                /*if ($j==1) {
                                                    echo "<tr class=zebra1>";
                                                    $j++;
                                                } else {
                                                    echo "<tr class=zebra2>";
                                                    $j--;
                                                }*/
                                                ?> 
                                            <tr>
                                                <!-- NO URUT -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                    <?php
                                                        if($PO_STAT == 1)
                                                        {
                                                            ?>
                                                                <a href="#" onClick="deleteRow(<?php echo $noU; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            echo "$noU.";
                                                        }
                                                    ?>
                                                    <input style="display:none" type="Checkbox" id="data[<?php echo $noU; ?>][chk]" name="data[<?php echo $noU; ?>][chk]" value="<?php echo $noU; ?>" onClick="pickThis(this,<?php echo $noU; ?>)">
                                                    <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       			</td>
                                                <!-- ITEM CODE -->
                                                <td style="text-align:left; vertical-align: middle; display: none;">
                                                    <?php print $ITM_CODE; ?>
                                                    <input type="hidden" name="urlIRDet<?php echo $noU; ?>" id="urlIRDet<?php echo $noU; ?>" value="<?php echo $secvwIRD; ?>">
                                                    <input type="hidden" id="data<?php echo $noU; ?>PRD_ID" name="data[<?php echo $noU; ?>][PRD_ID]" value="<?php echo $PRD_ID; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>ITM_CODE" name="data[<?php echo $noU; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>PO_NUM" name="data[<?php echo $noU; ?>][PO_NUM]" value="<?php print $DocNumber; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>PO_CODE" name="data[<?php echo $noU; ?>][PO_CODE]" value="<?php print $PO_CODE; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>PRJCODE" name="data[<?php echo $noU; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>PR_NUM" name="data[<?php echo $noU; ?>][PR_NUM]" value="<?php print $PR_NUMX; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>JOBCODEDET" name="data[<?php echo $noU; ?>][JOBCODEDET]" value="<?php print $JOBCODEDET; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>JOBCODEID" name="data[<?php echo $noU; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>JOBPARENT" name="data[<?php echo $noU; ?>][JOBPARENT]" value="<?php print $JOBPARENT; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $noU; ?>JOBPARDESC" name="data[<?php echo $noU; ?>][JOBPARDESC]" value="<?php print $JOBDESC; ?>" width="10" size="15"></td>
                                                <!-- ITEM NAME -->
                                                <td style="text-align:left; vertical-align: middle;">
		                                            <?php echo $ITM_CODE." (".$JOBCODEID.") : ".$ITM_NAME; ?> <span class="text-red" style="font-style: italic; font-weight: bold;">(Hrg.RAP : <?=number_format($RAP_PRICE,2);?> )</span>
												  	<div style='font-style: italic;'>
														<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?php echo "$JOBDESC ($JOBPARENT)"; ?>
													</div>
													<div style='font-style: italic;'>
														<i class='text-muted fa fa-commenting-o'></i>&nbsp;&nbsp;<?php echo "$PO_DESC"; ?>
													</div>
													<div style='font-style: italic; margin-left: 15px;'>
														<span style='font-style: italic; font-weight: bold;'>
															RAP : &nbsp;&nbsp;<?php echo number_format($TBUDG_VAL,2); ?>
														</span>
														<span id='usedValue_".$noU."' style='font-style: italic; font-weight: bold; display: none;'>
															R : &nbsp;&nbsp;<?php echo number_format($TREQ_VAL,2); ?>
														</span>&nbsp;&nbsp;
														<span id='remValue_<?$noU;?>' style='font-style: italic; font-weight: bold;'>
															S : <?php echo number_format($ITM_REMVAL,2); ?>
														</span>
													</div>
												</td>
                                                
                                                <!-- ITEM BUDGET -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                	<?php echo $PR_VOLMv; ?>
                                                </td>
                                                
                                                <!-- ITEM REMAIN -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                	<?php echo $ITM_REMVOLv; ?>
                                                    <input type="hidden" class="form-control" style="min-width:130px; max-width:200px; text-align:right" name="ITM_REMAIN<?php echo $noU; ?>" id="ITM_REMAIN<?php echo $noU; ?>" value="<?php echo $ITM_REMAIN; ?>" ></td>
                                                    
                                                <!-- ITEM ORDER NOW -->  
                                                <td style="text-align:right; vertical-align: middle;">
                                                    <?php print number_format($PO_VOLM, $decFormat); ?>
                                                    <input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="PO_VOLMx<?php echo $noU; ?>" id="PO_VOLMx<?php echo $noU; ?>" value="<?php print number_format($PO_VOLM, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $noU; ?>);" disabled>
                                                     <input type="hidden" id="data<?php echo $noU; ?>PR_VOLM" name="data[<?php echo $noU; ?>][PR_VOLM]" value="<?php print $PR_VOLM; ?>">
                                                     <input type="hidden" id="data<?php echo $noU; ?>PR_AMOUNT" name="data[<?php echo $noU; ?>][PR_AMOUNT]" value="<?php print $PR_AMOUNT; ?>">
                                                     <input type="hidden" id="data<?php echo $noU; ?>PO_VOLM" name="data[<?php echo $noU; ?>][PO_VOLM]" value="<?php print $PO_VOLM; ?>"></td>
                                                    
                                                <!-- ITEM UNIT -->
                                                <td style="text-align:center; vertical-align: middle;">
                                                    <?php print $ITM_UNIT2; ?>  
                                                     <input type="hidden" id="data<?php echo $noU; ?>ITM_UNIT" name="data[<?php echo $noU; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
                                                     <input type="hidden" id="data<?php echo $noU; ?>ITM_UNIT2" name="data[<?php echo $noU; ?>][ITM_UNIT2]" value="<?php print $ITM_UNIT2; ?>">
                                                 </td>
                                                <?php
                                                    /* Perhitungan ........
                                                    $totPriceItem	= $PR_VOLM * $CSTPUNT;
                                                    $totalAmount	= $totalAmount + $totPriceItem;
                                                    $BtotalAmount	= $BtotalAmount + ($totPriceItem * $PO_CURRATE);
                                                    End */
                                                ?>
                                                
                                                <!-- ITEM PRICE -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                    <?php print number_format($ITM_PRICE, $decFormat); ?>
                                                    <input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="PO_PRICEx<?php echo $noU; ?>" id="PO_PRICEx<?php echo $noU; ?>" size="10" value="<?php echo number_format($ITM_PRICE, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php echo $noU; ?>);" disabled>
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $noU; ?>][PO_PRICE]" id="data<?php echo $noU; ?>PO_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>"></td>
												
												<!-- Ongkos Angkut : hidden 2023-08-16
                                                <td style="text-align:right; vertical-align: middle;">
                                                    <?php // print number_format($PO_OA, $decFormat); ?>
                                                    <input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="PO_OAx<?php // echo $noU; ?>" id="PO_OAx<?php // echo $noU; ?>" size="10" value="<?php // echo number_format($PO_OA, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValuePO(this, <?php // echo $noU; ?>);" disabled>
                                                    <input style="text-align:right" type="hidden" name="data[<?php // echo $noU; ?>][PO_OA]" id="data<?php // echo $noU; ?>PO_OA" size="6" value="<?php // echo $PO_OA; ?>"></td>
												-->
                                                 
                                                <!-- ITEM DISCOUNT PERCENTATION -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                	<?php if($ITM_CATEG != 'UA'): ?>
                                                    	<?php print number_format($PO_DISP, $decFormat); ?>
                                                    <?php else: echo "-"; endif; ?>
                                                    <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="PO_DISPx<?php echo $noU; ?>" id="PO_DISPx<?php echo $noU; ?>" value="<?php print number_format($PO_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $noU; ?>);" disabled>
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $noU; ?>][PO_DISP]" id="data<?php echo $noU; ?>PO_DISP" value="<?php echo $PO_DISP; ?>"></td>
                                                    
                                                <!-- ITEM DISCOUNT -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                	<?php if($ITM_CATEG != 'UA'): ?>
                                                    	<?php print number_format($PO_DISC, $decFormat); ?>
                                                    <?php else: echo "-"; endif; ?>
                                                    <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="PO_DISC<?php echo $noU; ?>" id="PO_DISC<?php echo $noU; ?>" value="<?php print number_format($PO_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $noU; ?>);" disabled>
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $noU; ?>][PO_DISC]" id="data<?php echo $noU; ?>PO_DISC" value="<?php echo $PO_DISC; ?>"></td>
                                                    
                                                <!-- ITEM TAX -->
                                                <td style="text-align:center; vertical-align: middle;">
                                                    <?php
                                                    	if($TAXCODE1 != '')
	                                                	{
	                                                		$TAXLA_DESC = "-";
	                                                		$sPPN		= "SELECT TAXLA_NUM, TAXLA_CODE, TAXLA_DESC
	                                                						FROM tbl_tax_ppn WHERE TAXLA_NUM = '$TAXCODE1'";
		                                                    $rPPN		= $this->db->query($sPPN)->result();
		                                                    foreach($rPPN as $rwPPN) :
		                                                        $TAXLA_NUM	= $rwPPN->TAXLA_NUM;
		                                                        $TAXLA_DESC	= $rwPPN->TAXLA_DESC;
	                                                        endforeach;
	                                                        echo $TAXLA_DESC;
	                                                	}
	                                                	else
	                                                		echo "-";
                                                    ?>
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $noU; ?>][TAXCODE1]" id="data<?php echo $noU; ?>TAXCODE1" value="<?php echo $TAXCODE1; ?>">
                                               	</td>
                                              	<?php
                                                    //$BTAXPRICE1	= $TAXPRICE1 * $PO_TAXRATE;
                                                    //$TAXCODE1	= "TAX01";
                                                    /*if($TAXCODE1 == "TAX01")
                                                    {
                                                        $totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICE1;
                                                        $TAXPRICEPPh1		= 0;
                                                        $totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICEPPh1;
                                                    }
                                                    else
                                                    {
                                                        $totTaxPPhAmount	= $totTaxPPhAmount + $TAXPRICE1;
                                                        $TAXPRICEPPn1		= 0;
                                                        $totTaxPPnAmount	= $totTaxPPnAmount + $TAXPRICEPPn1;
                                                    }*/
                                              	?>
                                              	<!-- ITEM TOTAL COST -->
                                                <td style="text-align:right; vertical-align: middle;">
                                                    <?php print number_format($PO_COSTnPPn, $decFormat); ?>
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $noU; ?>][PO_COST]" id="data<?php echo $noU; ?>PO_COST" value="<?php echo $PO_COST; ?>">
                                                    <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="PO_COSTnPPn<?php echo $noU; ?>" id="PO_COSTnPPn<?php echo $noU; ?>" value="<?php print number_format($PO_COSTnPPn, $decFormat); ?>" disabled>
                                                    <input style="text-align:right" type="hidden" name="data[<?php echo $noU; ?>][TAXPRICE1]" id="data<?php echo $noU; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>"></td>
                                                <td style="text-align:left; vertical-align: middle;">
                                                	<?php echo $PR_DESC; ?>
                                                    <input class="form-control" style="min-width:130px;text-align:left" type="hidden" name="data[<?php echo $noU; ?>][PO_DESC]" id="data<?php echo $noU; ?>PO_DESC" value="<?php echo $PR_DESC; ?>" size="5" > </td>
                                            </tr>
                                                <?php
                                            endforeach;
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $noU; ?>">
                                            <?php
                                        }
                                    ?>
                                    <input type="hidden" name="TOTMAJOR" id="TOTMAJOR" value="<?php echo $resCMJR; ?>">
                            	</table>
                            </div>
                        </div>
                    </div>
                    <br>
					<?php
                        $DOC_NUM	= $PO_NUM;
                        $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH	= $this->db->count_all($sqlCAPPH);
						$sqlAPP		= "SELECT * FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' ORDER BY MAX_STEP DESC LIMIT 1";
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
                    		$canApprove 	= 0;
                    		if($LangID == 'IND')
							{
								$zerSetApp	= "Belum ada pengaturan untuk persetujuan dokumen ini. Atau Anda tidak memiliki pengaturan untuk menyetujui dokumen ini.";
							}
							else
							{
								$zerSetApp	= "There are no arrangements for the approval of this document. Or you don't have the settings to approve this document.";
							}
                    		?>
	                            <div class="col-sm-12">
	                    			<div class="alert alert-warning alert-dismissible">
						                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						                <?php echo $zerSetApp; ?>
					              	</div>
				              	</div>
                    		<?php
                    	}
                    ?>
                    <div class="col-md-6">
	                    <div class="form-group">
                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                            <div class="col-sm-9">
                            	<?php
									if($canApprove == 1 && $disableAll == 0)
									{
										if(($PO_STAT == 2 || $PO_STAT == 7) && $ISAPPROVE == 1)
										{
											?>
												<button type="button" class="btn btn-primary" id="btnSave" onClick="checkForm()">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_purchase/c_p180c21o/iN20_x1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
								?>
                            </div>
                        </div>
                    </div>
				</form>
		        <div class="col-md-12">
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
						            <div class="box-body no-padding">
		                        		<div class="search-table-outter">
							              	<table id="tbl" class="table table-striped" width="100%" border="0">
												<?php
													$s_STEP		= "SELECT DISTINCT APP_STEP FROM tbl_docstepapp_det
																	WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' ORDER BY APP_STEP";
													$r_STEP		= $this->db->query($s_STEP)->result();
													foreach($r_STEP as $rw_STEP) :
														$STEP	= $rw_STEP->APP_STEP;
														$HIDE 	= 0;
														?>
											                <tr>
											                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
																<?php
																	$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP'";
								                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
								                                    if($r_APPH_1 > 0)
								                                    {
																		$s_00	= "SELECT DISTINCT A.AH_APPROVER, A.AH_APPROVED,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																					WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->AH_APPROVER;
																			$APP_NME_1	= $rw_00->complName;
																			$APP_DAT_1	= $rw_00->AH_APPROVED;

									                                    	$APPCOL 	= "success";
									                                    	$APPIC 		= "check";
																			?>
																				<td style="width: 2%;">
																					<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																						<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																					</div>
																				</td>
																				<td>
																					<?=$APP_NME_1?><br>
																					<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APP_DAT_1?></span>
																				</td>
																			<?php
																		endforeach;
																	}
																	else
																	{
																		$s_00	= "SELECT DISTINCT A.APPROVER_1,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
																					WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND APP_STEP = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->APPROVER_1;
																			$APP_NME_1	= $rw_00->complName;
																			$OTHAPP 	= 0;
																			$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
										                                    if($r_APPH_1 > 0)
										                                    {
										                                    	$HIDE 	= 1;
										                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT	= $rw_01->AH_APPROVED;
										                                        endforeach;

										                                    	$APPCOL 	= "success";
										                                    	$APPIC 		= "check";
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																					</td>
																				<?php
										                                    }
										                                    else
										                                    {
										                                    	$APPCOL 	= "danger";
										                                    	$APPIC 		= "close";
										                                    	$APPDT 		= "-";
										                                    	$s_APPH_O	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
											                                    $r_APPH_O	= $this->db->count_all($s_APPH_O);
											                                    if($r_APPH_O > 0)
											                                    	$OTHAPP = 1;
										                                    }
										                                    if($HIDE == 0)
										                                    {
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																					</td>
																				<?php
																			}

																			if($OTHAPP > 0)
																			{
																				$APPDT_OTH 	= "-";
																				$APPNM_OTH 	= "-";
										                                    	$s_01	= "SELECT A.AH_APPROVED, A.AH_APPLEV,
										                                    					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME
										                                    				FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT_LEV	= $rw_01->AH_APPLEV;
										                                            $APPDT_OTH	= $rw_01->AH_APPROVED;
										                                            $APPNM_OTH	= $rw_01->COMPLNAME;

											                                    	$APPCOL 	= "success";
											                                    	$APPIC 		= "check";
																					?>
																		                <tr>
																		                  	<td style="width: 10%" nowrap>&nbsp;</td>
																							<td style="width: 2%;">
																								<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																									<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																								</div>
																							</td>
																							<td>
																								<?=$APPNM_OTH?><br>
																								<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT_OTH?></span>
																							</td>
																						</tr>
																					<?php
										                                        endforeach;
										                                    }
																		endforeach;
																	}
																?>
															</tr>
														<?php
													endforeach;
												?>
							              	</table>
						              	</div>
						            </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
                	<?php
						$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                		if($DefEmp_ID == 'D15040004221')
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
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
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

	// START : LOCK PROCEDURE
		$(document).ready(function()
		{
			setInterval(function(){chkAppStat()}, 1000);
		});

		function chkAppStat()
		{
			var url         = "<?php echo site_url('lck/appStat')?>";
			let DOC_DATE 	= $('#datepicker1').val();
			console.log(DOC_DATE);
			
				
			$.ajax({
				type: 'POST',
				url: url,
				data: {DOC_DATE:DOC_DATE},
				dataType: "JSON",
				success: function(response)
				{
					// var arrVar      = response.split('~');
					// var arrStat     = arrVar[0];
					// var arrAlert    = arrVar[1];
					// var LockCateg 	= arrVar[2];
					// var app_stat    = document.getElementById('app_stat').value;

					let LockY		= response[0].LockY;	
					let LockM		= response[0].LockM;	
					let LockCateg	= response[0].LockCateg;	
					let isLockJ		= response[0].isLockJ;	
					let LockJDate	= response[0].LockJDate;	
					let UserJLock	= response[0].UserJLock;	
					let isLockT		= response[0].isLock;	
					let LockTDate	= response[0].LockDate;	
					let UserLockT	= response[0].UserLock;
					console.log("isLockT ="+isLockT+" isLockJ = "+isLockJ+" LockCateg = "+LockCateg);

					// if(isLockJ == 1)
					// {
					// 	// $('#alrtLockJ').css('display',''); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#PO_STAT>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#PO_STAT>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#PO_STAT').removeAttr('disabled','disabled');
							// $('#PO_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#PO_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#PO_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#PO_STAT').removeAttr('disabled','disabled');
							// $('#PO_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#PO_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#PO_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE
  
	var decFormat		= 2;
	
	function checkForm()
	{
		PO_NOTES1	= document.getElementById('PO_NOTES1').value;
		/*if(PO_NOTES1 == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#PO_NOTES1').focus();

            });
			return false;
		}*/
		
		PO_STAT		= document.getElementById('PO_STAT').value;
		
		if(PO_STAT == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#PO_STAT').focus();

            });
			return false;
		}
		
		if(PO_STAT == 4 || PO_STAT == 5)
		{
			//PO_MEMO		= document.getElementById('PO_MEMO').value;
			PO_NOTES1		= document.getElementById('PO_NOTES1').value;
			if(PO_NOTES1 == '')
			{
				swal('<?php echo $alert2; ?>',
				{
					icon: "warning",
				})
	            .then(function()
	            {
	                swal.close();
	                $('#PO_NOTES1').focus();

	            });
				return false;
			}
		}

		swal({
            text: "<?php echo $sureApprove; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var variable = document.getElementById('btnSave');
				if (typeof variable !== 'undefined' && variable !== null)
				{
					document.getElementById('btnSave').style.display 	= 'none';
					document.getElementById('btnBack').style.display 	= 'none';

					document.frm.submit();
				}
            } 
            else 
            {
                return false;
            }
        });
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

	function viewFile(fileName)
	{
		// const url 		= "<?php // echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		const url 		= "<?php echo 'https://sdbpplus.nke.co.id/assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		// const urlOpen	= "<?php // echo base_url(); ?>";
		const urlOpen	= "<?php echo "https://sdbpplus.nke.co.id/"; ?>";
		// const urlDom	= "<?php // echo "https://sdbpplus.nke.co.id/"; ?>";
		let PRJCODE 	= "<?php echo $PRJCODE; ?>";
		// let path 		= "PO_Document/"+PRJCODE+"/";
		let path 		= "PO_Document/"+PRJCODE+"/"+fileName;
		// let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen+'&base_urlDom='+urlDom;
		let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		let left = (screen.width/2)-(w/2);
		let top = (screen.height/2)-(h/2);
		return window.open(url+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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