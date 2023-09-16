<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 18 April 2017
	* File Name	= itemreceipt_form.php
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

$ACC_ID_IR= "";
$sqlACCIR = "SELECT ACC_ID_IR FROM tglobalsetting";
$resACCIR = $this->db->query($sqlACCIR)->result();
foreach($resACCIR as $rowACCIR) :
	$ACC_ID_IR = $rowACCIR->ACC_ID_IR;
endforeach;

$currentRow 	= 0;
$IR_NUM 		= $default['IR_NUM'];
$IR_NUM_BEF		= $IR_NUM;
$IR_CODE 		= $default['IR_CODE'];
$IR_SOURCE 		= $default['IR_SOURCE'];
$IR_DATE 		= $default['IR_DATE'];
$JournalY 		= date('Y', strtotime($IR_DATE));
$JournalM 		= date('n', strtotime($IR_DATE));
$IR_DUEDATE		= $default['IR_DUEDATE'];
$IR_DATE		= date('d/m/Y', strtotime($IR_DATE));
$IR_DUEDATE		= date('d/m/Y', strtotime($IR_DUEDATE));
$PRJCODE 		= $default['PRJCODE'];
$DEPCODE 		= $default['DEPCODE'];
$SPLCODE 		= $default['SPLCODE'];
$PO_NUM 		= $default['PO_NUM'];
$PO_NUMX 		= $default['PO_NUM'];
$PO_CODE 		= $default['PO_CODE'];
$PR_NUM 		= $default['PR_NUM'];
$PR_CODE 		= $default['PR_CODE'];
$IR_REFER 		= $default['IR_REFER'];
$IR_AMOUNT 		= $default['IR_AMOUNT'];
$TERM_PAY 		= $default['TERM_PAY'];
$TRXUSER 		= $default['TRXUSER'];
$APPROVE 		= $default['APPROVE'];
$IR_STAT 		= $default['IR_STAT'];
$INVSTAT 		= $default['INVSTAT'];
$IR_NOTE 		= $default['IR_NOTE'];
$IR_NOTE2		= $default['IR_NOTE2'];
$REVMEMO		= $default['REVMEMO'];
$WH_CODE		= $default['WH_CODE'];
$IR_RECD		= $default['IR_RECD'];
$IR_RECD		= date('d/m/Y', strtotime($IR_RECD));
$IR_LOC			= $default['IR_LOC'];
$PR_CREATE		= $default['PR_CREATE'];
$Patt_Year 		= $default['Patt_Year'];
$Patt_Number	= $default['Patt_Number'];

// GET Journal Lock
	$disabled 	= 0;
	$getJLock 	= "SELECT * FROM tbl_journal_lock 
					WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
	$resJLock 	= $this->db->query($getJLock);
	$countJLock = $resJLock->num_rows();
	if($countJLock == 1) $disabled = 1;

// Cek Jumlah Item dengan ITM_CATEG = 'MC'
$this->db->from('tbl_po_detail A');
$this->db->join('tbl_item B','B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE','INNER');
$this->db->where('A.PRJCODE', $PRJCODE);
$this->db->where('A.PO_NUM', $PO_NUMX);
$this->db->where('B.ITM_CATEG', 'MC');
$qCountITM_CATEG = $this->db->get()->num_rows();
$styleMoreItem = 'none';
if($qCountITM_CATEG > 0)
{
	// Cek Authorization Emp ir_sett
	$qCountAuth_Items = $this->db->get_where('tbl_ir_sett', ['PRJCODE' => $PRJCODE, 
															 'EMP_ID' => $DefEmp_ID])->num_rows();
	if($qCountAuth_Items > 0)
	{
		// open penerimaan material lebih dengan categori MC (Material Curah)
		$styleMoreItem = '';
	}
}

// End Check

if($IR_SOURCE == 4)
{
	$form_action	= site_url('c_inventory/c_ir180c15/update_inbox_process_SO');
}

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

// Warehouse List
$sqlWHC		= "tbl_warehouse WHERE PRJCODE = '$PRJCODE'";
$resWHC		= $this->db->count_all($sqlWHC);

$sqlWH 		= "SELECT WH_NUM, WH_CODE, WH_NAME
				FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE' ORDER BY WH_NAME";
$resWH		= $this->db->query($sqlWH)->result();

$sqlTAX		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE' ORDER BY WH_NAME";
$resTAX		= $this->db->query($sqlTAX)->result();

$disBtn 	= 0;
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

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
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
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'WHLocation')$WHLocation = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Receipt')$Receipt = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'payType')$payType = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'recMore')$recMore = $LangTransl;
			if($TranslCode == 'excRec')$excRec = $LangTransl;
		endforeach;
		$secGenCode	= base_url().'index.php/c_inventory/c_item_receipt/genCode/'; // Generate Code
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$alertAcc 	= "Belum diset kode akun penerimaan.";
			$alertAccUM	= "Akun Penggunaan belum diset.";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$alertAcc 	= "Not set account receipt.";
			$alertAccUM	= "Not Set used account.";
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM'";
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
				$APPROVE_AMOUNT 	= $IR_AMOUNT;
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
		    <?php echo "$mnName ($PRJCODE)"; ?>
		    <small><?php echo $PRJNAME; ?></small>  </h1>
		  	<?php /*?><ol class="breadcrumb">
		    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		    <li><a href="#">Tables</a></li>
		    <li class="active">Data tables</li>
		  	</ol><?php */?>
		</section>

		<section class="content">	
		    <div class="row">
            	<!-- Mencari Kode Purchase Order Number -->
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="text" name="PO_NUMX" id="PO_NUMX" class="textbox" value="<?php echo $PO_NUMX; ?>" />
                    <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
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
                                <input type="hidden" name="IRDate" id="IRDate" value="">
                            </td>
                            <td><a class="tombol-date" id="dateClass">Simpan</a></td>
                        </tr>
                    </table>
                </form>
                <!-- End -->
		        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkForm()">
					<?php
						// START : LOCK PROCEDURE
							$app_stat 	= $this->session->userdata['app_stat'];
							if($LangID == 'IND')
							{
								$appAlert1	= 'Terkunci!';
								$appAlert2	= 'Mohon maaf, saat ini transaksi penjurnalan sedang terkunci.';
							}
							else
							{
								$appAlert1	= 'Locked!';
								$appAlert2	= 'Sorry, the journalizing transaction is currently locked.';
							}
							?>
								<input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
					            <div class="col-sm-12" id="divAlert" style="display: none;">
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
		                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptCode ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:150px" disabled >
		                                <input type="hidden" name="Patt_Year" id="Patt_Year" value="<?php echo $Patt_Year; ?>">
		                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
		                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
		                                <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
		                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="hidden" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" class="form-control" style="max-width:150px" >
		                                <input type="text" name="IR_CODE1" id="IR_CODE1" value="<?php echo $IR_CODE; ?>" class="form-control" disabled >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
		                          	<div class="col-sm-4">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    	<input type="hidden" name="IR_DATE" class="form-control pull-left" id="IR_DATE" value="<?php echo $IR_DATE; ?>" >
		                                    	<input type="hidden" name="IR_DUEDATE" class="form-control pull-left" id="IR_DUEDATE" value="<?php echo $IR_DUEDATE; ?>" >
		                                    	<input type="text" name="IR_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px" disabled>
		                            	</div>
		                            </div>
		                          	<label for="inputName" class="col-sm-2 control-label">Tgl. Terima</label>
		                          	<div class="col-sm-3">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                   	<input type="text" name="IR_RECD" class="form-control pull-left" id="datepicker2" value="<?php echo $IR_RECD; ?>" style="width:105px">
		                            	</div>
		                            </div>
		                        </div>
								<script>
		                            function getIR_NUM(selDate)
		                            {
		                                document.getElementById('IRDate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
			
									$(document).ready(function()
									{
										$(".tombol-date").click(function()
										{
											var add_IR	= "<?php echo $secGenCode; ?>";
											var formAction 	= $('#sendDate')[0].action;
											var data = $('.form-user').serialize();
											$.ajax({
												type: 'POST',
												url: formAction,
												data: data,
												success: function(response)
												{
													var myarr = response.split("~");
													document.getElementById('IR_NUM1').value 	= myarr[0];
													document.getElementById('IR_NUM').value 	= myarr[0];
													document.getElementById('IR_CODE').value 	= myarr[1];
												}
											});
										});
									});
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SourceDocument; ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE1" value="1" <?php if($IR_SOURCE == 1) { ?> checked <?php } ?> disabled>
		                                &nbsp;&nbsp;Direct&nbsp;&nbsp;&nbsp;&nbsp;
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE2" value="2" <?php if($IR_SOURCE == 2) { ?> checked <?php } ?> disabled>
		                                &nbsp;&nbsp;MR&nbsp;&nbsp;&nbsp;&nbsp;
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" <?php if($IR_SOURCE == 3) { ?> checked <?php } ?>>
		                                &nbsp;&nbsp;PO
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE4" value="4" <?php if($IR_SOURCE == 4) { ?> checked <?php } ?>>
		                                &nbsp;&nbsp;SO
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $RefNumber; ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary"><?php echo $Search; ?> </button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="Ref_NumberPO" id="Ref_NumberPO" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="PO_NUM" id="PO_NUM" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="PO_CODE" id="PO_CODE" style="max-width:160px" value="<?php echo $PO_CODE; ?>" >
		                                    <input type="hidden" class="form-control" name="PR_NUM" id="PR_NUM" style="max-width:160px" value="<?php echo $PR_NUM; ?>" >
		                                    <input type="hidden" class="form-control" name="PR_CODE" id="PR_CODE" style="max-width:160px" value="<?php echo $PR_CODE; ?>" >
		                                    <input type="hidden" class="form-control" name="IR_REFER" id="IR_REFER" style="max-width:160px" value="<?php echo $IR_REFER; ?>" >
		                                    <input type="text" class="form-control" name="PO_NUM1" id="PO_NUM1" value="<?php echo $PO_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?>>
		                                </div>
		                            </div>
		                        </div>
								<?php
									$url_selIR_CODE		= site_url('c_inventory/c_item_receipt/popupallpo/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selIR_CODE;?>";
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
		                           		<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
		                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" disabled >
		                                  	<?php
		                                        if($resPLC > 0)
		                                        {
		                                            foreach($resPL as $rowPL) :
		                                                $PRJCODE1 = $rowPL->PRJCODE;
		                                                $PRJNAME1 = $rowPL->PRJNAME;
		                                                ?>
		                                  				<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
		                                  	<?php
		                                            endforeach;
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                  				<option value="none">--- No Project Found ---</option>
		                                  	<?php
		                                        }
		                                        ?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName; ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>">
		                            	<select name="SPLCODE1" id="SPLCODE1" class="form-control" onChange="getVendName(this.value)" disabled>
		                                    <?php
			                                    $sqlSPL 	= "SELECT A.SPLCODE, A.SPLDESC FROM tbl_supplier A WHERE A.SPLCODE = '$SPLCODE'";
												$resSPL 	= $this->db->query($sqlSPL)->result();
												foreach($resSPL as $rowSPL) :
		                                            $SPLCODE1	= $rowSPL->SPLCODE;
		                                            $SPLDESC1	= $rowSPL->SPLDESC;
		                                            ?>
		                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLDESC1 - $SPLCODE1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                        if($task == 'add')
		                                        {
		                                            ?>
		                                                <option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>>--- None ---</option>
		                                            <?php
		                                        }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
		                        <?php if($IR_SOURCE != 4) { ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $payType; ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="TERM_PAY" id="TERM_PAY" value="<?php echo $TERM_PAY; ?>">
		                                <select name="TERM_PAY1" id="TERM_PAY1" class="form-control" disabled>
		                                    <option value="0" <?php if($TERM_PAY == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($TERM_PAY == 7) { ?> selected <?php } ?>>7 Days</option>
		                                    <option value="14" <?php if($TERM_PAY == 14) { ?> selected <?php } ?>>14 Days</option>
		                                    <option value="30" <?php if($TERM_PAY == 30) { ?> selected <?php } ?>>30 Days</option>
		                                    <option value="45" <?php if($TERM_PAY == 45) { ?> selected <?php } ?>>45 Days</option>
		                                    <option value="60" <?php if($TERM_PAY == 60) { ?> selected <?php } ?>>60 Days</option>
		                                    <option value="75" <?php if($TERM_PAY == 75) { ?> selected <?php } ?>>75 Days</option>
		                                    <option value="90" <?php if($TERM_PAY == 90) { ?> selected <?php } ?>>90 Days</option>
		                                    <option value="120" <?php if($TERM_PAY == 120) { ?> selected <?php } ?>>120 Days</option>
		                                </select>
		                          	</div>
		                        </div>
		                        <?php } ?>
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="height:60px; display:none"><?php echo $IR_NOTE; ?></textarea>
		                                <textarea class="form-control" name="IR_NOTE1"  id="IR_NOTE1" style="height:60px" disabled><?php echo $IR_NOTE; ?></textarea>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ApproverNotes ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="IR_NOTE2"  id="IR_NOTE2" style="height:60px"><?php echo $IR_NOTE2; ?></textarea>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $WHLocation ?> </label>
		                          	<div class="col-sm-6">
		                            	<input type="hidden" name="WH_CODE" id="WH_CODE" value="<?php echo $WH_CODE; ?>">
		                                <select name="WH_CODE1" id="WH_CODE1" class="form-control" style="display: none;" disabled>
		                                  	<?php
		                                  		$WHNM 	= "-";
		                                        if($resWHC > 0)
		                                        {
		                                            foreach($resWH as $rowWH) :
		                                                $WH_CODE1 = $rowWH->WH_CODE;
		                                                $WH_NAME1 = $rowWH->WH_NAME;
		                                                ?>
		                                  					<option value="<?php echo $WH_CODE1; ?>" <?php if($WH_CODE1 == $WH_CODE) { ?> selected <?php } ?>><?php echo "$WH_CODE1 - $WH_NAME1"; ?></option>
		                                  				<?php
		                                            endforeach;
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                  				<option value="" style="font-style:italic; text-align:center">--- No Data Warehouse ---</option>
		                                  			<?php
		                                        }
		                                        ?>
		                                </select>
		                                <?php
                                            foreach($resWH as $rw_H) :
                                                $WH_NUMH 	= $rw_H->WH_NUM;
                                                $WH_CODEH 	= $rw_H->WH_CODE;
                                                $WH_NAMEH 	= $rw_H->WH_NAME;
                                                if($WH_NUMH == $WH_CODE) 
                                                { 
                                                	$WHNM = $WH_NAME1;
                                                }
                                            endforeach;
		                                ?>
		                                <input type="text" class="form-control" name="IR_LOCXX" id="IR_LOCXX" value="<?php echo "$WHNM : $IR_LOC"; ?>" />
		                            </div>
		                            <div class="col-sm-3" style="display: <?=$styleMoreItem?>;">
		                                <label for="inputName" class="control-label"><?php echo $recMore; ?> ?</label>
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
		                          	<div class="col-sm-6">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
										<?php
											// START : FOR ALL APPROVAL FUNCTION
												if($disableAll == 0)
												{
													if($canApprove == 1)
													{
														$disButton	= 0;
														$sqlCAPPHE	= "tbl_approve_hist WHERE AH_CODE = '$IR_NUM' AND AH_APPROVER = '$DefEmp_ID'";
														$resCAPPHE	= $this->db->count_all($sqlCAPPHE);
														if($resCAPPHE > 0)
															$disButton	= 1;
														?>
															<select name="IR_STAT" id="IR_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
																<option value="0"> --- </option>
																<option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
																<option value="4"<?php if($IR_STAT == 4) { ?> selected <?php } ?>>Revised</option>
																<option value="5"<?php if($IR_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
																<!-- <option value="6"<?php if($IR_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
																<option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?> disabled>Awaiting</option>
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
															Step approval not set;
														</a>
													<?php
												}
											// END : FOR ALL APPROVAL FUNCTION
		                                ?>
		                            </div>
		                            <div class="col-sm-3" style="display: <?=$styleMoreItem?>;">
							            <input type="radio" name="PR_CREATE1" id="iCheck1" value="1" <?php if($PR_CREATE == 1) { ?> checked <?php } else { ?> disabled <?php } ?>>
							            <label>Yes</label>&nbsp;&nbsp;
							            <input type="radio" name="PR_CREATE1" id="iCheck2" value="0" <?php if($PR_CREATE == 0) { ?> checked <?php } else { ?> disabled <?php } ?>>
							            <label>No</label>
							            <input type="hidden" class="form-control" name="PR_CREATE" id="PR_CREATE" value="<?php echo $PR_CREATE; ?>" />
		                            </div>
		                        </div>
		                        <script type="text/javascript">
									$(document).ready(function (){
									    $('#iCheck1, #iCheck2').iCheck({
									        radioClass: 'iradio_flat-orange'
									    });
									});
		                        </script>
								<?php
									$url_AddItem	= site_url('c_inventory/c_item_receipt/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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
										<!--<a href="javascript:void(null);" onClick="selectitem();">
											Add Item [+]
		                                </a>-->
		                                
		                                <button class="btn btn-success" type="button" onClick="selectitem();">
		                                    <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
		                                </button><br>
		                                </div>
		                        </div>
							</div>
						</div>
					</div>

					<div class="col-sm-12" id="alrtLockJ" style="display: none;">
						<div class="alert alert-warning alert-dismissible col-md-12">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-warning"></i> <?php echo $docalert1; ?>!</h4>
							<?php echo $docalert4; ?>
						</div>
					</div>

					<div class="col-md-12" id="RECMORE_NOTES" <?php if($PR_CREATE == 0) { ?> style="display:none" <?php } ?>>
						<div class="alert alert-warning alert-dismissible">
			                <h4 style="display: none;"><i class="icon fa fa-warning"></i> <?=$Notes?>!</h4>
			                Dokumen ini akan menerima material lebih besar dari jumlah yang dipesan. Sistem akan membuatkan dokumen SPP dan akan disinkronisasi dengan PO yang terkait dengan dokumen LPM ini sesuai dengan jumlah kelebihan yang dimasukan. Dokumen SPP akan terbentuk setelah disetujui tingkat terakhir.
		              	</div>
		            </div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
                                      	<th width="0%" rowspan="2" style="text-align:center; vertical-align: middle; display: none;">&nbsp;</th>
                                      	<th width="30%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $ItemName; ?> </th>
                                      	<th colspan="3" style="text-align:center"><?php echo $Receipt; ?> </th>
                                        <th width="5%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $Discount; ?><br>(%)</th>
                                        <th width="5%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $Discount; ?></th>
                                        <th width="5%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $PPn; ?></th>
                                        <th width="10%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $Total; ?></th>
                                        <th width="9%" rowspan="2" style="text-align:center; vertical-align: middle; display: none;">Bonus<br>(Qty)</th>
                                        <th width="30%" rowspan="2" style="text-align:center; vertical-align: middle"><?php echo $Remarks ?></th>
                                  	</tr>
                                    <tr style="background:#CCCCCC">
                                        <th width="10%" style="text-align:center;"><?php echo $Quantity; ?> </th>
                                        <th width="5%" style="text-align:center;"><?php echo $Unit; ?> </th>
                                        <th width="10%" style="text-align:center"><?php echo $Price; ?> </th>
                                    </tr>
                                    <?php
									$resultC			= 0;
									$disBtn 			= 0;
									$isDisabled			= 0;
									$IR_AMOUNT			= 0;
									$IR_DISC			= 0;
									$IR_PPN				= 0;
									$IR_PPH 			= 0;
									$IR_AMOUNT_NETT		= 0;
									$TAXCODE_PPN 		= "";
									$TAXCODE_PPH		= "";
									$LPMDesc            = "";
									
									$sqlDET		= "SELECT A.IR_ID, A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
														A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT, A.ITM_UNIT2,
														A.ITM_QTY_REM, A.ITM_QTY, 0 AS PO_VOLM, A.POD_ID,
														A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
														A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
														A.ISPRCREATE, A.ADD_PRVOLM, A.ISCOST AS ISITMCOST,
														B.ITM_NAME, B.ACC_ID, B.ITM_NAME, B.ITM_GROUP, B.ITM_CATEG,
														B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, A.JOBPARENT, A.JOBPARDESC,
														B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
														B.ISFASTM, B.ISWAGE, B.ISRM, B.ISWIP, B.ISFG, B.ISCOST,
														C.PR_NUM, C.PO_NUM
													FROM tbl_ir_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
														INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
															AND C.PRJCODE = '$PRJCODE'
													WHERE 
													A.IR_NUM = '$IR_NUM' 
													AND A.PRJCODE = '$PRJCODE'
													AND A.ITM_QTY > 0";
									$result = $this->db->query($sqlDET)->result();
									// count data
									$sqlDETC	= "tbl_ir_detail A
														INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
															AND B.PRJCODE = '$PRJCODE'
													WHERE 
													A.IR_NUM = '$IR_NUM' 
													AND A.PRJCODE = '$PRJCODE'";
									$resultC 	= $this->db->count_all($sqlDETC);
									
									
									$i		= 0;
									$j		= 0;
									if($resultC > 0)
									{
										foreach($result as $row) :
											$IR_ID 			= $row->IR_ID;
											$IR_NUM 		= $IR_NUM;
											$IR_CODE 		= $IR_CODE;
											$PO_NUM 		= $PO_NUM;
											if($task == 'add' && $PO_NUMX != '')
											{
												$PR_NUM 	= $row->PR_NUM;
												$PO_NUM 	= $row->PO_NUM;
												$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
											}
											elseif($PO_NUMX != '')
											{
												$PR_NUM 	= $row->PR_NUM;
												$PO_NUM 	= $row->PO_NUM;
												$ADDQIERY	= "AND A.PO_NUM = '$PO_NUM'";
											}
											else
											{
												$PR_NUM 	= $PR_NUM;
												$PO_NUM 	= $PO_NUM;
												$ADDQIERY	= "";
											}
											
											$PRJCODE		= $PRJCODE;
											$JOBCODEDET 	= $row->JOBCODEDET;
											$JOBCODEID		= $row->JOBCODEID;
											$JOBPARENT		= $row->JOBPARENT;
											$JOBPARDESC		= $row->JOBPARDESC;
											$ACC_ID 		= $row->ACC_ID;
											$ACC_ID_UM 		= $row->ACC_ID_UM;
											$POD_ID 		= $row->POD_ID;
											$ITM_CODE 		= $row->ITM_CODE;
											$ITM_UNIT 		= $row->ITM_UNIT;
											$ITM_UNIT2 		= $row->ITM_UNIT2;
											$ITM_GROUP 		= $row->ITM_GROUP;
											$ITM_CATEG 		= $row->ITM_CATEG;
											$ITM_NAME 		= $row->ITM_NAME;
											$ITM_QTY_REM 	= $row->ITM_QTY_REM;
											$ITM_QTY1 		= $row->ITM_QTY;

											if($JOBPARENT == '')
											{
												$sqlJDP 	= "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
																WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
																	WHERE B.JOBCODEID = '$JOBCODEID')";
												$resJDP 	= $this->db->query($sqlJDP)->result();
												foreach($resJDP as $rowJDP) :
													$JOBPARENT 	= $rowJDP->JOBCODEID;
													$JOBPARDESC = $rowJDP->JOBDESC;
												endforeach;
											}
											
											// GET REMAIN
												$TOT_IRQTY		= 0;
												$sqlQTY		= "SELECT SUM(A.ITM_QTY) AS TOT_IRQTY 
																FROM tbl_ir_detail A
																	INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
																WHERE 
																	B.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
																	AND A.IR_NUM != '$IR_NUM' AND B.IR_STAT IN (2,3)
																	AND A.POD_ID = $POD_ID
																	$ADDQIERY";
												$resQTY 	= $this->db->query($sqlQTY)->result();
												foreach($resQTY as $row1a) :
													$TOT_IRQTY 	= $row1a->TOT_IRQTY;
												endforeach;
												if($TOT_IRQTY == '')
													$TOT_IRQTY	= 0;
											
											$ITM_QTY 		= $ITM_QTY1;
											$PO_VOLM 		= $row->PO_VOLM;
											$ITM_QTY_BONUS	= $row->ITM_QTY_BONUS;
											$ITM_PRICE 		= $row->ITM_PRICE;
											$ITM_DISP 		= $row->ITM_DISP;
											$ITM_DISC 		= $row->ITM_DISC;
											$ITM_TOTAL 		= $row->ITM_TOTAL;
											$ITM_DESC 		= $row->NOTES;
											//$IR_VOLM 		= $row->IR_VOLM;
											//$IR_AMOUNT 	= $row->IR_AMOUNT;
											$TAXCODE1		= $row->TAXCODE1;
											$TAXCODE2		= $row->TAXCODE2;
											$TAXPRICE1		= $row->TAXPRICE1;
											$TAXPRICE2		= $row->TAXPRICE2;

											if($TAXCODE1 != '')
												$TAXCODE_PPN	= $TAXCODE1;

											if($TAXCODE2 != '')
												$TAXCODE_PPH	= $TAXCODE2;

											$ISPRCREATE 	= $row->ISPRCREATE;
											$ADD_PRVOLM 	= $row->ADD_PRVOLM;
											$itemConvertion	= 1;

											$ISITMCOST 		= $row->ISITMCOST;
											$ISMTRL 		= $row->ISMTRL;
											$ISRENT 		= $row->ISRENT;
											$ISPART 		= $row->ISPART;
											$ISFUEL 		= $row->ISFUEL;
											$ISLUBRIC 		= $row->ISLUBRIC;
											$ISFASTM 		= $row->ISFASTM;
											$ISWAGE 		= $row->ISWAGE;
											$ISRM 			= $row->ISRM;
											$ISWIP 			= $row->ISWIP;
											$ISCOST 		= $row->ISCOST;
											$ISFG 			= $row->ISFG;
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
												elseif($ISWAGE == 1)
													$ITM_TYPE	= 7;
												elseif($ISRM == 1)
													$ITM_TYPE	= 8;
												elseif($ISWIP == 1)
													$ITM_TYPE	= 9;
												elseif($ISFG == 1)
													$ITM_TYPE	= 10;
												elseif($ISCOST == 1)
													$ITM_TYPE	= 11;
												else
													$ITM_TYPE	= 1;
																
											$REMAINQTY		= $ITM_QTY1 - $TOT_IRQTY;
											
											if($task == 'add')
												$ITM_QTY 	= $REMAINQTY;
											else
												$ITM_QTY 	= $ITM_QTY;
											
											if($task == 'add')
											{
												$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;
											}
											else
											{
												$ITM_TOTAL 	= $row->ITM_TOTAL;						// Non-PPn
											}


											$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
											$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
											$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
											$IR_PPH 		= $IR_PPH + $TAXPRICE2;
											$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
											$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

											$ItmCol1	= '';
											$ItmCol2	= '';
											$ttl 		= '';
											$divDesc 	= '';

											if($ITM_GROUP == 'M' OR $ITM_GROUP == 'T')
												$IS_M 		= 1;
											else
												$IS_M 		= 0;

											if($IS_M == 1 && $ACC_ID == '')
											{
												$disBtnX 	= 1;
												$disBtn 	= $disBtn+$disBtnX;
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= 'Belum disetting kode akun penerimaan';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
												$isDisabled = 1;
											}
											elseif($IS_M == 0)
											{
												$disBtnX 	= 0;
												$disBtn 	= $disBtn+$disBtnX;
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= '';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;Penerimaan Overhead";
												$isDisabled = 0;
												if($ITM_CATEG == 'UA' && $ACC_ID_UM == '')
												{
													$disBtnX 	= 1;
													$disBtn 	= $disBtn+$disBtnX;
													$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
													$ItmCol2	= '</span>';
													$ttl 		= 'Item ongkos angkut ini belum disetting Kode Akun';
													$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
													$isDisabled = 1;
												}
												elseif($ACC_ID_UM == '')
												{
													$disBtnX 	= 1;
													$disBtn 	= $disBtn+$disBtnX;
													$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
													$ItmCol2	= '</span>';
													$ttl 		= 'Belum disetting kode akun penggunaan';
													$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;Penerimaan Overhead : $alertAccUM";
													$isDisabled = 1;
												}
											}

											$ItmCol0a	= '';
											$ItmCol1a	= '';
											$ItmCol2a	= '';
											$ttla 		= '';
											$divDesca 	= '';
											if($ADD_PRVOLM > 0)
											{
												$ItmCol0a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol1a	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2a	= '</span>';
												$ttla 		= $excRec;
												$divDesca 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;$excRec : $ADD_PRVOLM $ITM_UNIT2";
											}

											if($ITM_GROUP != 'M' && $ITM_GROUP != 'M')
											{
												$ItmCol0a	= '';
												$ItmCol1a	= '';
												$ItmCol2a	= '';
												$ttla 		= '';
												$divDesca 	= '';
											}
												
											/*if ($j==1) {
												echo "<tr>";
												$j++;
											} else {
												echo "<tr>";
												$j--;
											}*/

											$currentRow  	= ++$i;
											?>
											<tr id="tr_<?php echo $currentRow; ?>" style="background-color: #red">
                                                <td style="text-align:left; vertical-align: middle;">
													<?php
			                                            if($IR_STAT == 1)
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
													<input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
													<input type="Checkbox" style="display:none" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="">
													<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php echo $IR_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>IR_CODE" name="data[<?php echo $currentRow; ?>][IR_CODE]" value="<?php echo $IR_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>PO_NUM" name="data[<?php echo $currentRow; ?>][PO_NUM]" value="<?php echo $PO_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<!-- Checkbox -->
												</td>
												<td style="text-align:left; display: none;" nowrap>
													<?php echo "$ItmCol1$ITM_CODE$ItmCol2"; ?>
													<input type="hidden" id="data<?php echo $currentRow; ?>POD_ID" name="data[<?php echo $currentRow; ?>][POD_ID]" value="<?php echo $POD_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15" readonly class="form-control">
													<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php echo $JOBCODEDET; ?>" width="10" size="15" readonly class="form-control">
													<input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" width="10" size="15" readonly class="form-control">
                                                    <input type="text" id="data<?php echo $currentRow; ?>JOBPARENT" name="data[<?php echo $currentRow; ?>][JOBPARENT]" value="<?php print $JOBPARENT; ?>" width="10" size="15">
                                                    <input type="text" id="data<?php echo $currentRow; ?>JOBPARDESC" name="data[<?php echo $currentRow; ?>][JOBPARDESC]" value="<?php print $JOBPARDESC; ?>" width="10" size="15">
													<!-- Item Code --></td>

												<td style="text-align:left; vertical-align: middle;">
													<?php echo "$ITM_CODE : $ITM_NAME"; ?>
													<input type="hidden" id="data<?php echo $currentRow; ?>ISCOST" name="data[<?php echo $currentRow; ?>][ISCOST]" value="<?php echo $ISITMCOST; ?>" class="form-control">
		                                        	<div style="font-style: italic;">
												  		<i class='text-muted fa fa-chevron-circle-right'></i>&nbsp;&nbsp;<?php echo "$JOBPARDESC ($JOBCODEID)"; ?>
												  	</div>
												  	<?php echo "$ItmCol1$divDesc$ItmCol2"; ?>
											  		<?php echo "$ItmCol1a$divDesca$ItmCol2a"; ?>
													<input type="hidden" class="form-control" name="itemname<?php echo $currentRow; ?>" id="itemname<?php echo $currentRow; ?>" value="<?php echo $ITM_NAME; ?>" >
													<input type="hidden" id="data<?php echo $currentRow; ?>ITM_NAME" name="data[<?php echo $currentRow; ?>][ITM_NAME]" value="<?php echo $ITM_NAME; ?>" width="10" size="15" readonly class="form-control">
													<!-- Item Name --></td>

												<!-- ITM_QTY -->
												<td style="text-align:right; vertical-align: middle;">
													<?php print number_format($ITM_QTY, $decFormat); ?>
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="ITM_QTY<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY; ?>" >
													<input type="hidden" style="text-align:right" id="REMAINQTY<?php echo $currentRow; ?>" size="10" value="<?php echo $REMAINQTY; ?>" >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_REM]" id="ITM_QTY_REM<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_REM; ?>" >
													<!-- Item Qty -->
												</td>

												<td style="text-align:center; vertical-align: middle;">
													<?php echo $ITM_UNIT2; ?>
													<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
													<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT2]" id="ITM_UNIT2<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT2; ?>" >
                                                    <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" id="ITM_GROUP<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_GROUP; ?>" >
                                                    <input type="hidden" class="form-control" style="max-width:350px; text-align:right" id="ITM_CATEG<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_CATEG; ?>" >
                                                    <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TYPE]" id="ITM_TYPE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TYPE; ?>" >
													<!-- Item Unit --></td>

												<!-- ITM_PRICE, ITM_TOTAL -->
												<td style="text-align:center; vertical-align: middle; font-style:italic">
                                                	hidden
													<input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right;" name="ITM_PRICEX<?php echo $currentRow; ?>" id="ITM_PRICEX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" disabled >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="ITM_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_PRICE; ?>" >
													<!-- Item Price -->
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="ITM_TOTAL<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_TOTAL; ?>" >
												</td>

												<!-- ITM_DISP -->
												<td style="text-align:center; vertical-align: middle; font-style:italic">
                                                	hidden
													<input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right;" name="ITM_DISPx<?php echo $currentRow; ?>" id="ITM_DISPx<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISP, $decFormat); ?>" onBlur="countDisp(this, <?php echo $currentRow; ?>);" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="ITM_DISP<?php echo $currentRow; ?>" value="<?php echo $ITM_DISP; ?>"></td>

												<!-- ITM_DISC -->
												<td style="text-align:center; vertical-align: middle; font-style:italic">
                                                	hidden
													<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right; display:none" name="ITM_DISCX<?php echo $currentRow; ?>" id="ITM_DISCX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISC, $decFormat); ?>" onBlur="countDisc(this, <?php echo $currentRow; ?>);" disabled >
													<input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="ITM_DISC<?php echo $currentRow; ?>" value="<?php echo $ITM_DISC; ?>"></td>

												<!-- TAXPRICE1 -->
												<td style="text-align:center; vertical-align: middle; font-style:italic">
                                                	hidden
                                                	<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="TAXCODE1<?php echo $currentRow; ?>" value="<?php echo $TAXCODE1; ?>">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="TAXCODE2<?php echo $currentRow; ?>" value="<?php echo $TAXCODE2; ?>">
													<input type="hidden" id="data<?php echo $currentRow; ?>IR_ID" name="data[<?php echo $currentRow; ?>][IR_ID]" value="<?php echo $IR_ID; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="TAXPRICE1<?php echo $currentRow; ?>" size="10" value="<?php echo $TAXPRICE1; ?>" >
													<!-- Item Price Total PPn -->
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE2]" id="TAXPRICE2<?php echo $currentRow; ?>" size="10" value="<?php echo $TAXPRICE2; ?>" >
													<!-- Item Price Total PPh --></td>

												<!-- ITM_TOTAL_NETT -->
												<td style="text-align:center; vertical-align: middle; font-style:italic">
                                                	hidden
                                                    <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right;" name="ITM_TOTAL_NETT<?php echo $currentRow; ?>" id="ITM_TOTAL_NETT<?php echo $currentRow; ?>" value="<?php print number_format($IR_AMOUNTNET, $decFormat); ?>" disabled >
												</td>

												<!-- ITM_QTY_BONUS -->
												<td style="text-align:center; vertical-align: middle; font-style:italic; display: none;">
                                                	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY_BONUSX<?php echo $currentRow; ?>" id="ITM_QTY_BONUSX<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY_BONUS, $decFormat); ?>" onBlur="changeValueQtyBonus(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" <?php if($isDisabled == 1) { ?> disabled <?php } ?> size="20" >
													<input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_BONUS]" id="ITM_QTY_BONUS<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_BONUS; ?>" >
												</td>

												<td style="text-align:left; vertical-align: middle; font-style:italic">
													<?php echo $ITM_DESC; ?>
													<input type="hidden" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $ITM_DESC; ?>" class="form-control" style="text-align:left" size="10">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][ISPRCREATE]" id="ISPRCREATE<?php echo $currentRow; ?>" value="<?php echo $ISPRCREATE; ?>" class="form-control">
													<input type="hidden" name="data[<?php echo $currentRow; ?>][ADD_PRVOLM]" id="ADD_PRVOLM<?php echo $currentRow; ?>" value="<?php echo $ADD_PRVOLM; ?>" class="form-control">
												</td>
									  		</tr>
											<?php
										endforeach;
										?>
										<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
										<?php
									}
                                    ?>
                                </table>
                            </div>
                      	</div>
                    </div>
                    <input type="hidden" name="disBtn" id="disBtn" value="<?php echo $disBtn; ?>">
                    <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $IR_AMOUNT; ?>">
                    <input type="hidden" name="IR_DISC" id="IR_DISC" value="<?php echo $IR_DISC; ?>">
                    <input type="hidden" name="IR_PPN" id="IR_PPN" value="<?php echo $IR_PPN; ?>">
					<input type="hidden" name="IR_PPH" id="IR_PPH" value="<?php echo $IR_PPH; ?>">
                    <input type="hidden" name="IR_AMOUNT_NETT" id="IR_AMOUNT_NETT" value="<?php echo $IR_AMOUNT_NETT; ?>">

                    <input type="hidden" name="TAXCODE_PPN" id="TAXCODE_PPN" value="<?php echo $TAXCODE_PPN; ?>">
                    <input type="hidden" name="TAXCODE_PPH" id="TAXCODE_PPH" value="<?php echo $TAXCODE_PPH; ?>">
                    <br>
					<?php
                        $DOC_NUM	= $IR_NUM;
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

                        $s_APPEMP	= "tbl_docstepapp WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$MenuCode'
										AND (APPROVER_1 = '$DefEmp_ID' OR APPROVER_2 = '$DefEmp_ID' OR APPROVER_3 = '$DefEmp_ID'
										OR APPROVER_4 = '$DefEmp_ID' OR APPROVER_5 = '$DefEmp_ID')";
                        $r_APPEMP	= $this->db->count_all($s_APPEMP);
						
                    	if($resCAPP == 0 || $r_APPEMP == 0)
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
									if(($disableAll == 0) && ($ACC_ID_IR != "" && (($IR_STAT == 2 || $IR_STAT == 7) && ($ISAPPROVE == 1 && $canApprove == 1))))
										$btnShow 	= 1;
									else
										$btnShow 	= 0;

									if($disBtn > 0)
										$btnShow 	= 0;
								?>
								<button class="btn btn-primary" id="btnSave" <?php if($btnShow == 0) { ?> style="display: none;" <?php } ?> >
								<i class="fa fa-save"></i></button>&nbsp;
								<?php
	                                $backURL	= site_url('c_inventory/c_ir180c15/in180c15box/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	                                echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
								?>
	                        </div>
	                    </div>
	                </div>
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
															?>
												                <tr>
												                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
																	<?php
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
										                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT	= $rw_01->AH_APPROVED;
										                                        endforeach;

										                                    	$APPCOL 	= "success";
										                                    	$APPIC 		= "check";
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
		    </div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>

<?php
	if($LangID == 'IND')
	{
		$alert1	= "Silahkan tambahkan item...!";
		$alert2	= "Silahkan pilih status persetujuan...!";
		$alert3	= "qty tidak boleh kosong.";
		$alert4	= "Isikan alasan Anda me-Revise/Reject dokumen.";
	}
	else
	{
		$alert1	= "Please add item...!";
		$alert2	= "Please select approval status...!";
		$alert3	= "qty can not be empty.";
		$alert4	= "Please input the reason why you revised/rejected document.";
	}
?>

<script>
  	$(function ()
  	{
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

					if(isLockJ == 1)
					{
						$('#alrtLockJ').css('display','');
						document.getElementById('divAlert').style.display   = 'none';
						$('#IR_STAT>option[value="3"]').attr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}
					else
					{
						$('#alrtLockJ').css('display','none');
						document.getElementById('divAlert').style.display   = 'none';
						$('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
						// document.getElementById('btnSave').style.display    = '';
					}

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							$('#alrtLockJ').css('display','');
							document.getElementById('divAlert').style.display   = 'none';
							$('#IR_STAT').removeAttr('disabled','disabled');
							$('#IR_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = '';
							$('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#IR_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#IR_STAT').removeAttr('disabled','disabled');
							$('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							$('#alrtLockJ').css('display','none');
							document.getElementById('divAlert').style.display   = 'none';
							$('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#IR_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}


					// if(arrStat == 1 && app_stat == 0 && LockCateg == 2)
					// {
					//     $('#app_stat').val(arrStat);
					//     swal(arrAlert, 
					//     {
					//         icon: "success",
					//     })
					//     .then(function()
					//     {
					//         swal.close();
					//         document.getElementById('btnSave').style.display    = 'none';
					//         document.getElementById('divAlert').style.display   = '';
					// 		$('#INV_STAT').attr('disabled','disabled');
					// 		console.log('tes');
					//     })
					// }
					// else if(arrStat == 1 && app_stat == 1 && LockCateg == 2)
					// {
					//     $('#app_stat').val(arrStat);
					//     document.getElementById('btnSave').style.display    = 'none';
					//     document.getElementById('divAlert').style.display   = '';
					// 	$('#INV_STAT').attr('disabled','disabled');
					// 	console.log('tes1');
					// }
					// else
					// {
					//     $('#app_stat').val(arrStat);
					//     document.getElementById('btnSave').style.display    = '';
					//     document.getElementById('divAlert').style.display   = 'none';
					// 	$('#INV_STAT').removeAttr('disabled','disabled');
					// 	console.log('tes1');
					// }
				}
			});
		}
    // END : LOCK PROCEDURE
  
	var decFormat		= 2;
	
	function checkForm()
	{
		IR_STAT		= document.getElementById("IR_STAT").value;
		var btndis	= document.getElementById('disBtn').value;
		document.getElementById('disBtn').value 	= 1;

		totalrow	= document.getElementById("totalrow").value;
		if(totalrow == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			document.getElementById('disBtn').value 	= btndis;
			selectitem();
			return false;
		}
		
		if(IR_STAT == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			document.getElementById("IR_STAT").focus();
			document.getElementById('disBtn').value 	= btndis;
			return false;
		}
		else if(IR_STAT == 4 || IR_STAT == 5)
		{
			IR_NOTE2 = document.getElementById("IR_NOTE2").value;
			if(IR_NOTE2 == '')
			{
				swal("<?php echo $alert4; ?>",
				{
					icon: "warning",
				})
	            .then(function()
	            {
	                swal.close();
	                $('#IR_NOTE2').focus();
	            });
	            document.getElementById('disBtn').value 	= btndis;
				return false;
			}
		}
		
		var TOTQTY	= 0;
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('ITM_QTY'+i);
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				ITM_QTY	= document.getElementById('ITM_QTY'+i).value;
				ITM_NM	= document.getElementById('itemname'+i).value;
				if(ITM_QTY == 0)
				{
					//swal('Item '+ ITM_NM +' <?php echo $alert3; ?>');
					//document.getElementById('ITM_QTYx'+i).focus();
					//return false;
				}
				TOTQTY	= parseFloat(TOTQTY) + parseFloat(ITM_QTY);
			}
		}
		
		if(TOTQTY == 0)
		{
			swal("qty can not be empty.",
			{
				icon: "warning",
			});
			//document.getElementById('ITM_QTYx'+i).focus();
			document.getElementById('disBtn').value 	= btndis;
			return false;
		}

		var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		}

		let frm = document.getElementById('frm');
		frm.addEventListener('submit', (e) => {
			console.log(e)
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		});
	}
	
	function changeValue(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYx 	= eval(thisVal).value.split(",").join("");		
		document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYx));
		document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYx)),decFormat));
		
		var ITM_DISP			= document.getElementById('ITM_DISP'+theRow).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE			= document.getElementById('ITM_PRICE'+theRow).value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT			= parseFloat(ITM_DISP * ITM_TOTAL / 100);		
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		document.getElementById('ITM_DISC'+theRow).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCx'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var theTAX				= document.getElementById('TAXCODE1'+theRow).value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(G_itmTot));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('GT_ITMPRICE'+i);
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				GT_ITMPRICE		= document.getElementById('GT_ITMPRICE'+i).value;
				IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
			}
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISP		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(ITM_DISP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISP)),decFormat));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_PRICE		= document.getElementById('ITM_PRICE'+row).value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT		= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
				
		var GT_ITMPRICE 	= document.getElementById('GT_ITMPRICE'+row).value
		var DISCOUNTP		= parseFloat(ITM_DISC / GT_ITMPRICE * 100);
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function getValueIR(thisVal, row)
	{
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function changeValueTax(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		//var ITM_QTY_MIN	= document.getElementById('ITM_QTY_MIN'+theRow).value;
		//var ITM_QTY		= eval(document.getElementById('ITM_QTY'+theRow)).value.split(",").join("");
		/*if(parseFloat(ITM_QTYx) > parseFloat(ITM_QTY_MIN))
		{
			swal('Qty can not greater then '+ ITM_QTY_MIN);
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{*/
			//document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY));
			//document.getElementById('ITM_QTYx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		//}
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		// PAJAK
		theTAX			= document.getElementById('TAXCODE1'+theRow).value;
		
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.1;
			G_itmTot	= parseFloat(ITM_TOTAL) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.03;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('GT_ITMPRICE'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('GT_ITMPRICE'+i);
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				GT_ITMPRICE		= document.getElementById('GT_ITMPRICE'+i).value;
				IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
			}
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEx 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEx));
		document.getElementById('ITM_PRICEx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEx)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PR_NUM		= arrItem[0];
		//swal(PR_NUM);
		//document.getElementById("PO_NUM1").value = PO_NUM;
		document.getElementById("PO_NUMX").value = PR_NUM;
		document.frmsrch.submitSrch.click();
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var IR_NUM 	= "<?php echo $IR_NUM; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[0], PRJCODE)
		if(validateDouble(arrItem[0], PRJCODE))
		{
			swal("Double Item for " + arrItem[0]);
			return false;
		}
		
		itemcode 		= arrItem[0];
		itemserial 		= arrItem[1];
		itemname 		= arrItem[2];
		itemUnit 		= arrItem[3];
		itemUnitName 	= arrItem[4];
		itemUnit2 		= arrItem[5];
		itemUnitName2 	= arrItem[6];
		itemConvertion 	= arrItem[9];
		itemQty 		= 0;
		itemPrice 		= arrItem[11];
		Acc_Id 			= arrItem[12];
		
		ITM_TOTAL		= itemQty * itemPrice;
		
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
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onClick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value=""><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_CODE" name="data['+intIndex+'][IR_CODE]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+Acc_Id+'" width="10" size="15" readonly class="form-control">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'<input type="hidden" class="form-control" name="itemname'+intIndex+'" id="itemname'+intIndex+'" value="'+itemname+'" >';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYx'+intIndex+'" id="ITM_QTYx'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="ITM_QTY'+intIndex+'" size="10" value="'+itemQty+'" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEx'+intIndex+'" id="ITM_PRICEx'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" size="10" value="'+itemPrice+'" >';
		
		// Item Price Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTALx'+intIndex+'" id="ITM_TOTALx'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" size="10" value="'+ITM_TOTAL+'" >';
		
		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="max-width:150px" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:450px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="TAXPRICE1'+intIndex+'" value=""><input type="hidden" style="text-align:right" name="GT_ITMPRICE'+intIndex+'" id="GT_ITMPRICE'+intIndex+'" value="">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var ITM_QTY												= document.getElementById('ITM_QTY'+intIndex).value
		document.getElementById('ITM_QTY'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTYx'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE											= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('ITM_PRICE'+intIndex).value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICEx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL											= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('ITM_TOTAL'+intIndex).value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALx'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		document.getElementById('totalrow').value 				= intIndex;
	}	
	
	function validateDouble(vcode,PRJCODE) 
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
				var elitem1		= document.getElementById('data'+i+'ITM_CODE').value;
				var PRJCODE1	= document.getElementById('data'+i+'PRJCODE').value;
				if (elitem1 == vcode && PRJCODE == PRJCODE)
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