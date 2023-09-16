<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 19 Oktober 2022
	* File Name		= purchase_req_inb_form_fpa.php
	* Location		= -
*/

// $this->load->view('template/head');

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

$sql = "SELECT PRJCODE_HO, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJHO 	= $row ->PRJCODE_HO;
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;

$PR_NUM				= $default['PR_NUM'];
$DocNumber			= $default['PR_NUM'];
$PR_CODE			= $default['PR_CODE'];
$PR_DATED			= $default['PR_DATE'];

$PR_DATE			= date('d/m/Y',strtotime($PR_DATED));
$PR_RECEIPTD		= $default['PR_RECEIPTD'];
$PR_RECEIPTD		= date('d/m/Y',strtotime($PR_RECEIPTD));
$JournalY 			= date('Y', strtotime($default['PR_DATE']));
$JournalM 			= date('n', strtotime($default['PR_DATE']));
$PRJCODE			= $default['PRJCODE'];
$DEPCODE			= $default['DEPCODE'];
$SPLCODE			= $default['SPLCODE'];
$PR_DEPT			= $default['PR_DEPT'];
$PR_CATEG			= $default['PR_CATEG'];
$PR_NOTE			= $default['PR_NOTE'];
$PR_NOTE2			= $default['PR_NOTE2'];
$PR_STAT			= $default['PR_STAT'];
$PR_MEMO			= $default['PR_MEMO'];
$PR_VALUE			= $default['PR_VALUE'];
$PR_VALUEAPP		= $default['PR_VALUEAPP'];
$PR_REFNO			= $default['PR_REFNO'];
$Patt_Year			= $default['Patt_Year'];
$Patt_Month			= $default['Patt_Month'];
$Patt_Date			= $default['Patt_Date'];
$PR_REQUESTER		= $default['PR_REQUESTER'];
$Reference_Number	= $default['Reference_Number'];
$Patt_Number		= $default['Patt_Number'];
$lastPatternNumb1	= $default['Patt_Number'];

$dataSessSrc = array('selSearchproj_Code' => $PRJCODE,
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
$this->session->set_userdata('dtSessSrc2', $dataSessSrc);
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

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'RequestNo')$RequestNo = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
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
			if($TranslCode == 'PR_Code')$PR_Code = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'inpMRD')$inpMRD = $LangTransl;
			if($TranslCode == 'inpMRQTY')$inpMRQTY = $LangTransl;
			if($TranslCode == 'budEmpt')$budEmpt = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'greaterBud')$greaterBud = $LangTransl;
			if($TranslCode == 'Requester')$Requester = $LangTransl;
			if($TranslCode == 'sureApprove')$sureApprove = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'dokLam')$dokLam = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$subTitleH	= "Persetujuan";
			$subTitleD	= "permintaan pembelian";
			
			$alert1		= "Silahkan pilih status persetujuan permintaan.";
			$alert2		= "Silahkan tulis catatan persetujuan dokumen.";
			$alert3		= "Dokumenn ini memerlukan persetujuan khusus.";
			$alert4		= "Silakan tulis catatan dokumen ini di-revise/reject.";
			$noAtth 	= "Tidak ada dokumen dilampirkan";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$subTitleH	= "Approval";
			$subTitleD	= "purchase request";
			
			$alert1		= "Please select an approval status.";
			$alert2		= "Please write the document approval note.";
			$alert3		= "This document needs a special approval.";
			$alert4		= "Please write a note this document was revised/rejected.";
			$noAtth 	= "No document(s) attached";
		}
		
		$secAddURL	= site_url('c_purchase/c_pr180d0c/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_purchase/c_pr180d0c/genCode/'; // Generate Code

		// SETTINGAN UNTUK APPROVAL PER DIVISI
			if($PRJHO == 'KTR')
			{
				$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_pr_detail_fpa A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
							WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'";
				$r_DIV 	= $this->db->query($s_DIV)->result();
				foreach($r_DIV as $rw_DIV):
					$MenuApp 	= $rw_DIV->JOBCOD1;

					$s_UPD1 	= "UPDATE tbl_pr_detail_fpa SET PR_DIVID = '$MenuApp' WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_UPD1);

					$s_UPD2 	= "UPDATE tbl_pr_header_fpa SET PR_DIVID = '$MenuApp' WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
					$this->db->query($s_UPD2);
				endforeach;
			}
		
		$isLoadDone_1	= 1;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];

			// DocNumber - PR_VALUE
			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			//$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV' AND POSCODE = '$DEPCODE'";
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				/*$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";*/
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
				/*$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";*/
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			/*$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";*/
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				/*$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV') AND POSCODE = '$DEPCODE'";*/
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
				$APPROVE_AMOUNT 	= $PR_VALUE;
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
			$sqlCMJR	= "tbl_pr_detail_fpa A
								INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							WHERE PR_NUM = '$PR_NUM' 
								AND B.PRJCODE = '$PRJCODE'
								AND B.ISMAJOR = 1";
			$resCMJR = $this->db->count_all($sqlCMJR);
		// END : MAJOR ITEM SETTING

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
		    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
			<?php /*?><ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Tables</a></li>
			<li class="active">Data tables</li>
			</ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
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
		        <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
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
				                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
				                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
				                <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
				                <input type="Hidden" name="rowCount" id="rowCount" value="0">
				            	<div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $RequestNo; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="max-width:180px;text-align:left" name="PR_NUM1" id="PR_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PR_NUM" id="PR_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="PRJCODE" id="PRJCODE" size="30" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
				                    <div class="col-sm-4">
				                    	<input type="text" class="form-control" style="text-align:left" id="PR_CODE1" name="PR_CODE1" size="5" value="<?php echo $PR_CODE; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="PR_CODE" name="PR_CODE" size="5" value="<?php echo $PR_CODE; ?>" />
				                    </div>
									<div class="col-sm-5">
				                    	<input type="text" class="form-control" name="Reference_Number" id="Reference_Number" size="30" value="<?php echo $Reference_Number; ?>" placeholder="No Referensi" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                        	<input type="text" name="PR_DATE1" class="form-control pull-left" id="datepicker" value="<?php echo $PR_DATE; ?>" style="width:106px" onChange="getPR_NUM(this.value)" disabled>
				                    	</div>
				                    	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="PR_DATE" name="PR_DATE" size="5" value="<?php echo $PR_DATE; ?>" />
				                    </div>
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Category; ?></label>
				                    <div class="col-sm-3">
		                                <select name="PR_CATEG1" id="PR_CATEG1" class="form-control select2" disabled>
                                            <option value="0"<?php if($PR_CATEG == 0) { ?> selected <?php } ?>>---</option>
                                            <option value="1"<?php if($PR_CATEG == 1) { ?> selected <?php } ?>>Alat</option>
		                                </select>
		                                <input type="hidden" class="form-control" id="PR_CATEG" name="PR_CATEG" size="20" value="<?php echo $PR_CATEG; ?>" />              
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?>&nbsp;</label>
				                    <div class="col-sm-9">
				                    	<select name="PRJCODE" id="PRJCODE" class="form-control" onChange="chooseProject()" disabled>
				                          <option value="none"> --- </option>
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
				                            ?>
				                        </select>
				                    	<input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
				            	<div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JobName; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" class="form-control" style="max-width:350px;" id="PR_REFNO" name="PR_REFNO" size="20" value="<?php echo $PR_REFNO; ?>" />
				                        <select name="PR_REFNOX" id="PR_REFNOX" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $JobName; ?>" onBlur="selJOB(this.value)" disabled>
				                        	<option value=""> --- </option>
											<?php
				                                /*$Disabled_1	= 0;
				                                $sqlJob_1	= "SELECT JOBCODEID, JOBPARENT, JOBLEV, JOBDESC FROM tbl_joblist WHERE ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				                                $resJob_1	= $this->db->query($sqlJob_1)->result();
				                                foreach($resJob_1 as $row_1) :
				                                    $JOBCODEID_1	= $row_1->JOBCODEID;
				                                    $JOBPARENT_1	= $row_1->JOBPARENT;
				                                    $JOBLEV_1		= $row_1->JOBLEV;
				                                    $JOBDESC_1		= $row_1->JOBDESC;
													
				                                    if($JOBLEV_1 == 1)
													{
														$space_level_1	= "";
													}
													elseif($JOBLEV_1 == 2)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													elseif($JOBLEV_1 == 3)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													elseif($JOBLEV_1 == 4)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													elseif($JOBLEV_1 == 5)
													{
														$space_level_1	= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													}
													
													$JIDExplode = explode('~', $PR_REFNO);
													$JOBCODE1	= '';
													$SELECTED	= 0;
													foreach($JIDExplode as $i => $key)
													{
														$JOBCODE1	= $key;
														if($JOBCODEID_1 == $JOBCODE1)
														{
															$SELECTED	= 1;
														}
													}
													
				                                    $sqlC_2			= "tbl_joblist WHERE JOBPARENT = '$JOBCODEID_1' AND ISHEADER = '1' AND PRJCODE = '$PRJCODE'";
				                                    $resC_2 		= $this->db->count_all($sqlC_2);
				                                    if($resC_2 > 0)
				                                        $Disabled_1 = 1;
													else
														$Disabled_1 = 0;
				                                    ?>
				                                    <option value="<?php echo "$JOBCODEID_1"; ?>" <?php if($SELECTED == 1) { ?> selected <?php } if($Disabled_1 == 1) {?> disabled <?php } ?> title="<?php echo $JOBDESC_1; ?>">
				                                        <?php echo "$space_level_1 $JOBCODEID_1 : $JOBDESC_1"; ?>
				                                    </option>
				                                    <?php
				                                endforeach;*/
				                            ?>
				                        </select>
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="PR_NOTE" class="form-control" id="PR_NOTE" cols="30" disabled><?php echo $PR_NOTE; ?></textarea>                        
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
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ReceivePlan ?> </label>
				                    <div class="col-sm-9">
				                        <div class="input-group date">
				                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                            <input type="text" name="PR_RECEIPTD1" class="form-control pull-left" id="datepicker1" value="<?php echo $PR_RECEIPTD; ?>" style="width:100px" disabled>
				                        </div>
				                    	<input type="hidden" class="form-control" style="min-width:150px; max-width:150px; text-align:left" id="PR_RECEIPTD" name="PR_RECEIPTD" size="5" value="<?php echo $PR_RECEIPTD; ?>" />
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Requester; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="text" class="form-control" style="text-align:left" id="PR_REQUESTER1" name="PR_REQUESTER1" size="5" value="<?php echo $PR_REQUESTER; ?>" disabled />
				                    	<input type="hidden" class="form-control" style="text-align:left" id="PR_REQUESTER" name="PR_REQUESTER" size="5" value="<?php echo $PR_REQUESTER; ?>" />                        
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes; ?></label>
				                    <div class="col-sm-9">
				                    	<textarea name="PR_NOTE2" class="form-control" id="PR_NOTE2" cols="30"><?php echo $PR_NOTE2; ?></textarea>                        
				                    </div>
				                </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
				                    <div class="col-sm-9">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $PR_STAT; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$PR_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="PR_STAT" id="PR_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> --- </option>
																<option value="3"<?php if($PR_STAT == 3) { ?> selected <?php } ?>>Approved</option>
																<option value="4"<?php if($PR_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($PR_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<option value="7"<?php if($PR_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
															</select>
														<?php
													}
													else
													{
														?>
															<a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs" title="ssss">
																<?php echo $descApp; ?>
															</a>
														<?php
													}
												}
												else
												{
													?>
														<a href="" class="btn btn-danger btn-xs">
															Step approval not set.
														</a>
													<?php
												}
											// END : FOR ALL APPROVAL FUNCTION
											$theProjCode 	= $PRJCODE;
				                        	$url_AddItem	= site_url('c_purchase/c_pr180d0c/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
				                        ?>
				                        <input type="hidden" name="PR_VALUE" id="PR_VALUE" value="<?php echo $PR_VALUE; ?>">
				                      	<input type="hidden" name="PR_VALUEAPP" id="PR_VALUEAPP" value="<?php echo $PR_VALUEAPP; ?>">
				                    </div>
				                </div>
				                <script>
									function selStat(PRSTAT)
									{
										/*if(PRSTAT == 4 || PRSTAT == 5)
										{
											document.getElementById('revMemo').style.display = '';
										}
										else
										{
											document.getElementById('revMemo').style.display = 'none';
										}*/
									}
								</script>
							</div>
						</div>
					</div>

					<?php
						$shAttc 	= 0;
						if($PR_STAT == 1 || $PR_STAT == 4)
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
							$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
											WHERE REF_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
							$resUPL_DOC = $this->db->query($getUPL_DOC);
							if($resUPL_DOC->num_rows() > 0)
								$shAttc = 1;
						}
					?>

					<div class="col-md-12" <?php if($shAttc == 0) { ?> style="display: none;" <?php } ?>>
						<div class="box box-default">
							<div class="box-header with-border">
								<label for="inputName"><?php echo $dokLam; ?></label>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
              					</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<div class="col-sm-4" <?php if($shTInp == 0) { ?> style="display: none;" <?php } ?>>
				                		<input type="file" class="form-control" name="userfile[]" id="userfile" accept=".pdf" multiple disabled>
										<span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span>
				                	</div>
									<div class="col-sm-<?=$smTDok?>">
									<?php
											// GET Upload Doc TRx
											$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
															WHERE REF_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
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

																	if($PR_STAT == 1 || $PR_STAT == 4)
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
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
																					<a href="<?php echo site_url("c_purchase/c_pr180d0c/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
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
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<!-- Download File -->
																					<a href="<?php echo site_url("c_purchase/c_pr180d0c/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
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
					?>

	                <div class="col-md-12" <?php if($PR_MEMO == '') { ?> style="display:none" <?php } ?>>
						<div class="alert alert-warning alert-dismissible">
			                <h4><i class="icon fa fa-warning"></i> <?php echo $reviseNotes; ?></h4>
			                <?php echo $PR_MEMO; ?>
			            </div>
			        </div>

                    <div class="col-md-12">
                      	<div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                <tr style="background:#CCCCCC">
										<th width="2%" height="25" style="text-align:left">&nbsp;</th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
	                                  	<th width="33%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
	                                  	<th width="10%" style="text-align:center;">Vol. <?php echo $Planning ?> </th>
	                                  	<th width="10%" style="text-align:center;">Vol. <?php echo $Requested ?> </th>
	                                  	<th width="10%" style="text-align:center">Volume</th>
										  <th width="10%" style="text-align:center; vertical-align: middle;">Rekap Item</th>
	                                  	<th width="5%" style="text-align:center; vertical-align: middle;">Sat.</th>
	                                  	<th width="20%" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
	                                </tr>
	                                <?php					
	                                if($task == 'edit')
	                                {
	                                    $sqlDET	= "SELECT DISTINCT A.PR_ID, A.PR_NUM, A.PR_CODE, A.PRJCODE, A.PR_REFNO, A.ITM_CODE, A.SNCODE, A.ITM_UNIT, 
	                                                    A.PR_VOLM, A.PR_VOLM2, A.PO_VOLM, A.IR_VOLM, A.PR_PRICE, A.PR_TOTAL, A.PR_DESC_ID, A.PR_DESC, 
	                                                    A.JOBCODEDET, A.JOBCODEID, A.ITM_VOLMBG, A.ITM_BUDG, A.JOBPARDESC,
	                                                    B.ITM_CODE_H, B.ITM_NAME, B.ISMAJOR, B.ITM_TYPE
	                                                FROM tbl_pr_detail_fpa A
	                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
	                                                        AND B.PRJCODE = '$PRJCODE'
	                                                WHERE PR_NUM = '$PR_NUM' 
	                                                    AND B.PRJCODE = '$PRJCODE'
													ORDER BY A.ITM_CODE, A.PR_DESC_ID ASC";
	                                    $result = $this->db->query($sqlDET)->result();
	                                    $i		= 0;
	                                    $j		= 0;
	                                    
	                                    $TOTMAJOR	= 0;
	                                    foreach($result as $row) :
	                                        $currentRow  	= ++$i;
	                                        $PR_NUM 		= $row->PR_NUM;
	                                        $PR_CODE 		= $row->PR_CODE;
	                                        $PRJCODE 		= $row->PRJCODE;
	                                        $PR_REFNO 		= $row->PR_REFNO;
	                                        $ITM_CODE_H		= $row->ITM_CODE_H;
	                                        $ITM_CODE 		= $row->ITM_CODE;
	                                        $ITM_NAME 		= $row->ITM_NAME;
	                                        $JOBPARDESC 	= $row->JOBPARDESC;
	                                        $SNCODE 		= $row->SNCODE;
	                                        $ITM_UNIT 		= $row->ITM_UNIT;
	                                        $PR_VOLM 		= $row->PR_VOLM;
	                                        $PR_VOLM2 		= $row->PR_VOLM2;
	                                        $PO_VOLM 		= $row->PO_VOLM;
	                                        $IR_VOLM 		= $row->IR_VOLM;
	                                        $PR_PRICE 		= $row->PR_PRICE;
	                                        $PR_TOTAL 		= $row->PR_TOTAL;
											$PR_DESC_ID 	= $row->PR_DESC_ID;
	                                        $PR_DESC 		= $row->PR_DESC;
	                                        $JOBCODEDET		= $row->JOBCODEDET;
	                                        $JOBCODEID 		= $row->JOBCODEID;
	                                        $ITM_VOLMBG		= $row->ITM_VOLMBG;
	                                        $ITM_BUDG 		= $row->ITM_BUDG;
	                                        $ISMAJOR 		= $row->ISMAJOR;
	                                        $ITM_TYPE 		= $row->ITM_TYPE;
	                                        $itemConvertion	= 1;
	                                        if($ISMAJOR == 1)
	                                        {
	                                            $TOTMAJOR	= $TOTMAJOR + 1;
	                                        }

	                                        if($JOBPARDESC == '')
	                                        {
                                                $sqlJPAR	= "SELECT A.JOBDESC FROM tbl_joblist_detail A 
																WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
																	WHERE B.JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE')";
                                                $resJPAR	= $this->db->query($sqlJPAR)->result();
                                                foreach($resJPAR as $rowJPAR) :
                                                    $JOBPARENT	= $rowJPAR->JOBDESC;
                                                    $JOBPARDESC	= $rowJPAR->JOBDESC;
                                                endforeach;
	                                        }

											$getPR_R 	= "SELECT IFNULL(SUM(A.PR_VOLM), 0) AS ITMVOLM_R
															FROM tbl_pr_detail_fpa A
															WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM'
															AND A.ITM_CODE = '$ITM_CODE' AND A.PR_DESC_ID = '$PR_DESC_ID'";
											$resPR_R 	= $this->db->query($getPR_R);
											foreach($resPR_R->result() as $rPR_R):
												$ITMVOLM_R 	= $rPR_R->ITMVOLM_R;
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
	                                        <td style="text-align:center; vertical-align: middle;">
	                                          <?php
	                                            if($PR_STAT == 1)
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
	                                        <td style="text-align:left; vertical-align: middle;" nowrap> <!-- Item Code -->
	                                          <?php echo $ITM_CODE; ?>                                      
	                                          <input type="hidden" id="data<?php echo $currentRow; ?>PR_NUM" name="data[<?php echo $currentRow; ?>][PR_NUM]" value="<?php echo $PR_NUM; ?>" class="form-control" style="max-width:300px;">
	                                          <input type="hidden" id="data<?php echo $currentRow; ?>PR_CODE" name="data[<?php echo $currentRow; ?>][PR_CODE]" value="<?php echo $PR_CODE; ?>" class="form-control" style="max-width:300px;">
	                                          <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
	                                          <input type="hidden" id="data<?php echo $currentRow; ?>SNCODE" name="data[<?php echo $currentRow; ?>][SNCODE]" value="<?php echo $SNCODE; ?>" class="form-control" style="max-width:300px;">
	                                      	</td>
	                                      	<td style="text-align:left; vertical-align: middle;">
	                                        	<?php echo $ITM_NAME; ?> <!-- Item Name -->
	                                        	<div style="font-style: italic;">
											  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?php echo "$JOBPARDESC ($JOBCODEID)"; ?>
											  	</div>
	                                      	</td>
	                                        <?php
	                                            // CARI TOTAL REGUSEST BUDGET APPROVED
	                                            $TOTPRAMOUNT	= 0;
	                                            $TOTPRQTY		= 0;
	                                            $sqlTOTBUDG		= "SELECT DISTINCT SUM(A.PR_VOLM * A.PR_PRICE) AS TOTPRAMOUNT, 
	                                                                    SUM(A.PR_VOLM) AS TOTPRQTY
	                                                                FROM tbl_pr_detail_fpa A
	                                                                INNER JOIN tbl_pr_header_fpa B ON A.PR_NUM = B.PR_NUM
	                                                                    AND B.PRJCODE = '$PRJCODE'
	                                                                WHERE A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE' 
	                                                                    AND A.JOBCODEDET = '$JOBCODEDET' AND B.PR_STAT IN ('2','3','6')
	                                                                    AND A.PR_NUM != '$PR_NUM'";
	                                            $resTOTBUDG		= $this->db->query($sqlTOTBUDG)->result();
	                                            foreach($resTOTBUDG as $rowTOTBUDG) :
	                                                $TOTPRAMOUNT	= $rowTOTBUDG->TOTPRAMOUNT;
	                                                $TOTPRQTY		= $rowTOTBUDG->TOTPRQTY;
	                                            endforeach;						
	                                            if($ITM_TYPE == 'SUBS')
	                                            {
	                                                $REQ_VOLM	= 0;
	                                                $REQ_AMOUNT	= 0;
	                                                $sqlREQ		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail 
	                                                                WHERE PRJCODE = '$PRJCODE'
	                                                                    AND ITM_CODE = '$ITM_CODE_H'";
	                                                $resREQ		= $this->db->query($sqlREQ)->result();
	                                                foreach($resREQ as $rowREQ) :
	                                                    $TOTPRQTY		= $rowREQ->REQ_VOLM;
	                                                    $TOTPRAMOUNT	= $rowREQ->REQ_AMOUNT;
	                                                endforeach;												
	                                            }
	                                            
	                                            $TOT_USEBUDG	= $TOTPRAMOUNT;					// 15
	                                            $ITM_BUDG		= $row->ITM_BUDG;				// 16
	                                            if($ITM_BUDG == '')
	                                                $ITM_BUDG	= 0;										
	                                            
	                                            $ITM_VOLM	= 0;
	                                            $ADD_VOLM	= 0;
	                                            
	                                            $sqlITM		= "SELECT A.ITM_VOLM, A.ADD_VOLM
	                                                            FROM tbl_joblist_detail A
	                                                            INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                                AND B.PRJCODE = '$PRJCODE'
	                                                            WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','I','T') 
	                                                            AND A.ITM_CODE = '$ITM_CODE'
	                                                            AND A.JOBPARENT = '$JOBCODEID'";
	                                                            
	                                            $BUDG_VOLM		= 0;
	                                            $ITM_BUDQTY		= 0;
	                                            $resITM			= $this->db->query($sqlITM)->result();
	                                            foreach($resITM as $rowITM) :
	                                                $ITM_VOLM	= $rowITM->ITM_VOLM;
	                                                $ADD_VOLM	= $rowITM->ADD_VOLM;
	                                                $BUDG_VOLM	= $ITM_VOLM + $ADD_VOLM;
	                                            endforeach;
	                                            $BUDG_VOLM		= $ITM_VOLMBG + $ADD_VOLM;									
	                                            $ITM_BUDQTY		= $BUDG_VOLM;
	                                            
	                                            // SISA QTY PR
	                                            $REMPRQTY		= $ITM_BUDQTY - $TOTPRQTY;
	                                        ?>
	                                        <td style="text-align:right; vertical-align: middle;"> <!-- Item Bdget -->
	                                        	<?php print number_format($ITM_BUDQTY, $decFormat); ?>
	                                          	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_BUDGQTYx<?php echo $currentRow; ?>" id="ITM_BUDGQTYx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_BUDQTY, $decFormat); ?>" disabled >
	                                          	<input type="hidden" style="text-align:right" name="ITM_BUDGQTY<?php echo $currentRow; ?>" id="ITM_BUDGQTY<?php echo $currentRow; ?>" value="<?php echo $ITM_BUDQTY; ?>" >
	                                          	<input type="hidden" id="data<?php echo $currentRow; ?>ITM_BUDG" name="data[<?php echo $currentRow; ?>][ITM_BUDG]" value="<?php echo $ITM_BUDG; ?>">
	                                      	</td>
	                                     	<td style="text-align:right; vertical-align: middle;">  <!-- Item Requested FOR INFORMATION ONLY -->
	                                     		<?php print number_format($TOTPRQTY, $decFormat); ?>
	                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTED<?php echo $currentRow; ?>" id="ITM_REQUESTED<?php echo $currentRow; ?>" value="<?php print $TOTPRAMOUNT; ?>" >
	                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTY<?php echo $currentRow; ?>" id="TOTPRQTY<?php echo $currentRow; ?>" value="<?php print $TOTPRQTY; ?>" >
	                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTPRQTYx<?php echo $currentRow; ?>" id="TOTPRQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRQTY, $decFormat); ?>" disabled >
	                                        	<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="ITM_REQUESTEDx<?php echo $currentRow; ?>" id="ITM_REQUESTEDx<?php echo $currentRow; ?>" value="<?php print number_format($TOTPRAMOUNT, $decFormat); ?>" disabled ></td>
	                                     	<td style="text-align:right; vertical-align: middle;"> <!-- Item Request Now -- PR_VOLM -->
	                                     		<?php print number_format($PR_VOLM, $decFormat); ?>
	                                       		<input type="hidden" name="PR_VOLM<?php echo $currentRow; ?>" id="PR_VOLM<?php echo $currentRow; ?>" value="<?php print number_format($PR_VOLM, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" disabled >
	                                       		<input type="hidden" name="data[<?php echo $currentRow; ?>][PR_VOLM]" id="data<?php echo $currentRow; ?>PR_VOLM" value="<?php print $PR_VOLM; ?>" class="form-control" style="max-width:300px;" ><input type="hidden" name="data[<?php echo $currentRow; ?>][PR_PRICE]" id="data<?php echo $currentRow; ?>PR_PRICE" value="<?php print $PR_PRICE; ?>" class="form-control" style="max-width:300px;" >
	                                       		<input type="hidden" name="data[<?php echo $currentRow; ?>][PR_TOTAL]" id="data<?php echo $currentRow; ?>PR_TOTAL" value="<?php print $PR_TOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
	                                       		<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
	                                        </td>
											<td style="text-align:right; vertical-align: middle;">
												<span id="ITMVOL_R<?php echo "$PR_DESC_ID$currentRow";?>" style="text-align: right;">
													<?php echo number_format($ITMVOLM_R, 2); ?>
												</span>
											</td>
	                                        <td style="text-align:left; vertical-align: middle;">
	                                          	<?php echo $ITM_UNIT; ?>
	                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="data<?php echo $currentRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
	                                        	<!-- Item Unit Type -- ITM_UNIT --></td>
	                                       <td style="text-align:left; vertical-align: middle;">
	                                        	<?php print $PR_DESC; ?>
	                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][PR_DESC]" id="data<?php echo $currentRow; ?>PR_DESC" size="20" value="<?php print $PR_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left" disabled>
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
	                                <input type="hidden" name="TOTMAJOR" id="TOTMAJOR" value="<?php echo $TOTMAJOR; ?>">
	                            </table>
	                      	</div>
                      	</div>
                    </div>

                    <div class="col-md-6">
		                <div class="form-group">
			           	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    	<div class="col-sm-9">
		                        <?php
									if($disableAll == 0)
									{
										if(($PR_STAT == 2 || $PR_STAT == 7) && $canApprove == 1)
										{
											?>
												<button type="button" class="btn btn-primary" id="btnSave" onClick="checkForm()">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
		                            $backURL	= site_url('c_purchase/c_pr180d0c/iN20_x1_fpa/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    	</div>
		                </div>
		            </div>
				</form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $PR_NUM;
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
		        </div>
		    </div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
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
		  autoclose: true,
		  format: 'dd/mm/yyyy'
		});
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  format: 'dd/mm/yyyy'
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
			let DOC_DATE 	= $('#datepicker').val();
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
					// 	// $('#PR_STAT>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#PR_STAT>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#PR_STAT').removeAttr('disabled','disabled');
							// $('#PR_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#PR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#PR_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#PR_STAT').removeAttr('disabled','disabled');
							// $('#PR_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#PR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#PR_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE
  
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
	
	function checkForm()
	{
		PR_STAT		= document.getElementById('PR_STAT').value;
		PR_NOTE2	= document.getElementById('PR_NOTE2').value;
		/*if(PR_NOTE2 == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#PR_NOTE2').focus();
			});
			return false;
		}*/
		
		if(PR_STAT == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			document.getElementById('PR_STAT').focus();
			return false;
		}

		if(PR_STAT == 4 || PR_STAT == 5)
		{
			PR_NOTE2		= document.getElementById('PR_NOTE2').value;
			if(PR_NOTE2 == '')
			{
				swal('<?php echo $alert4; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();
	                $('#PR_NOTE2').focus();
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
		
		thisVal 			= parseFloat(Math.abs(thisVal1.value))
		//tempTotMax1 		= parseFloat(document.getElementById('tempTotMax'+row).value);
		ITM_BUDG			= parseFloat(document.getElementById('data'+row+'ITM_BUDG').value);
		PR_PRICE			= parseFloat(document.getElementById('data'+row+'PR_PRICE').value);
		
		// Start : 7 Mei 2015 : Permintaan tidak boleh melebihi		
		reqQty1				= document.getElementById('PR_VOLM'+row).value;
		reqQty1x 			= parseFloat(reqQty1);
		document.getElementById('data'+row+'PR_VOLM').value = reqQty1x;
		document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		
		itemConvertion		= document.getElementById('itemConvertion'+row).value;
		
		reqQty2 			= reqQty1 * itemConvertion;
		
		//document.getElementById('PR_VOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty2)),decFormat));
		
		//tempTotMaxx = parseFloat(Math.abs(tempTotMax1));
		totITMPrice	= parseFloat(reqQty1x) * parseFloat(PR_PRICE);
		//if(reqQty1x > tempTotMaxx)
		var TOTPRQTY		= document.getElementById('TOTPRQTY'+row).value;
		var remItmQty		= parseFloat(ITM_BUDG) - parseFloat(TOTPRQTY);
		
		if(totITMPrice > ITM_BUDG)
		{
			vtotITMPrice	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totITMPrice)),decFormat));
			swal('<?php echo $greaterBud; ?> '+vtotITMPrice,
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'PR_VOLM').value = TOTPRQTY;
			document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTPRQTY)),decFormat))
			//document.getElementById('PR_VOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(tempTotMaxx)),decFormat))
			return false;
		}
		document.getElementById('data'+row+'PR_VOLM').value = reqQty1x;
		document.getElementById('PR_VOLM'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
		//document.getElementById('PR_VOLM2'+row).value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(reqQty1x)),decFormat))
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function submitForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		//var venCode 	= document.getElementById('Vend_Code').value;
		var isApproved 	= document.getElementById('isApproved').value;
		
		if(isApproved == 0)
		{
			for(i=1;i<=totrow;i++)
			{
				var PR_VOLM = parseFloat(document.getElementById('PR_VOLM'+i).value);
				if(PR_VOLM == 0)
				{
					swal('<?php echo $inpMRQTY; ?>',
					{
						icon: "warning",
					});
					document.getElementById('PR_VOLM'+i).value = '0';
					document.getElementById('PR_VOLM'+i).focus();
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
				swal('<?php echo $inpMRD; ?>',
				{
					icon: "warning",
				});
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

	function viewFile(fileName)
	{
		// const url 		= "<?php // echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		const url 		= "<?php echo 'https://sdbpplus.nke.co.id/assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		// const urlOpen	= "<?php // echo base_url(); ?>";
		const urlOpen	= "<?php echo "https://sdbpplus.nke.co.id/"; ?>";
		// const urlDom	= "<?php // echo "https://sdbpplus.nke.co.id/"; ?>";
		let PRJCODE 	= "<?php echo $PRJCODE; ?>";
		let path 		= "PR_Document/"+PRJCODE+"/"+fileName+"";
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
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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