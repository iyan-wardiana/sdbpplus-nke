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
$PRJSCATEG 	= $this->session->userdata['PRJSCATEG'];

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
$DEPCODEX 		= $this->session->userdata['DEPCODE'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currRow 	= 0;
$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
$r_PattC 	= $this->db->count_all($s_PattC);
if($r_PattC > 0)
{
	$isSetDocNo = 1;
	$s_Patt		= "SELECT Pattern_Code, Pattern_Length FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
	$r_Patt 	= $this->db->query($s_Patt)->result();
	foreach($r_Patt as $row) :
		$PATTCODE 	= $row->Pattern_Code;
	endforeach;
}
else
{
	$PATTCODE 		= "XXX";
		
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

if($task == 'add')
{
	$IR_NUM_BEF		= '';
	
	$TRXTIME		= date('ymdHis');
	$IR_NUM			= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$IR_CODE		= $DocNumber;
	
	$IR_SOURCE		= 1;
	$IR_DATE		= date('d/m/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$IR_REFER		= '';
	$PO_NUM			= '';
	$PO_CODE		= '';
	$IR_AMOUNT		= 0;
	$IR_DISC		= 0;
	$IR_PPN			= 0;
	$IR_AMOUNT_NETT	= 0;
	$TAXCODE_PPN 	= "";
	$TAXCODE_PPH	= "";
	$APPROVE		= 0;
	$IR_STAT		= 1;
	$IR_NOTE		= '';
	$IR_NOTE2		= '';
	$REVMEMO		= '';
	$WH_CODE		= '';
	$Patt_Number	= 0;
	$PO_NUMX		= '';
	
	if(isset($_POST['PO_NUMX']))
	{
		$PO_NUMX		= $_POST['PO_NUMX'];
	}

	// End Check

	$PO_CODE	= '';
	$PR_NUM		= '';
	$SPLCODE	= '';
	$TERM_PAY	= 0;
	$sqlPOH		= "SELECT PO_CODE, PR_NUM, SPLCODE, PO_TENOR FROM tbl_po_header WHERE PO_NUM = '$PO_NUMX' AND PRJCODE = '$PRJCODE'";
	$resPOH 	= $this->db->query($sqlPOH)->result();
	foreach($resPOH as $row1):
		$PO_CODE	= $row1->PO_CODE;
		$PR_NUM		= $row1->PR_NUM;
		$SPLCODE	= $row1->SPLCODE;
		$TERM_PAY	= $row1->PO_TENOR;
	endforeach;
	$GT_TOTAMOUNT	= 0;
	
	$IR_RECD		= date('d/m/Y');
	$IR_LOC			= '';
	$PR_CREATE 		= 0;
}
else
{
	$isSetDocNo = 1;
	$IR_NUM 		= $default['IR_NUM'];
	$IR_NUM_BEF		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_SOURCE 		= $default['IR_SOURCE'];
	$IR_DATE 		= $default['IR_DATE'];
	$IR_DATE		= date('d/m/Y', strtotime($IR_DATE));
	$JournalY 		= date('Y', strtotime($default['IR_DATE']));
	$JournalM 		= date('n', strtotime($default['IR_DATE']));
	$IR_DUEDATE		= $default['IR_DUEDATE'];
	$IR_DUEDATE		= date('d/m/Y', strtotime($IR_DUEDATE));
	$PRJCODE 		= $default['PRJCODE'];
	$DEPCODE 		= $default['DEPCODE'];
	if($DEPCODE == '')
		$DEPCODE 	= $DEPCODEX;
	$SPLCODE 		= $default['SPLCODE'];
	$IR_REFER 		= $default['IR_REFER'];
	$PR_NUM			= $IR_REFER;
	$PO_NUM 		= $default['PO_NUM'];
	$PO_CODE 		= $default['PO_CODE'];
	$PO_NUMX 		= $default['PO_NUM'];
	$PR_NUM 		= $default['PR_NUM'];
	$IR_AMOUNT 		= $default['IR_AMOUNT'];
	$TERM_PAY 		= $default['TERM_PAY'];
	$TRXUSER 		= $default['TRXUSER'];
	$APPROVE 		= $default['APPROVE'];
	$IR_STAT 		= $default['IR_STAT'];
	$INVSTAT 		= $default['INVSTAT'];
	$IR_NOTE 		= $default['IR_NOTE'];
	$IR_NOTE2 		= $default['IR_NOTE2'];
	$REVMEMO		= $default['REVMEMO'];
	$WH_CODE		= $default['WH_CODE'];
	$IR_RECD		= $default['IR_RECD'];
	$IR_RECD		= date('d/m/Y', strtotime($IR_RECD));
	$IR_LOC			= $default['IR_LOC'];
	$PR_CREATE		= $default['PR_CREATE'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Number	= $default['Patt_Number'];
	$GT_TOTAMOUNT	= $IR_AMOUNT;
}

if($IR_STAT == 3 || $IR_STAT == 6)
{
	$s_00 	= "UPDATE tbl_ir_detail A, tbl_ir_header B SET A.TTK_CODE = B.TTK_CODE, A.INV_CODE = B.INV_CODE, A.BP_CODE = B.BP_CODE
				WHERE A.IR_NUM = B.IR_NUM AND A.PRJCODE = B.PRJCODE AND A.IR_NUM = '$IR_NUM'";
	$this->db->query($s_00);
}

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

// Project List
$DefEmp_ID	= $this->session->userdata['Emp_ID'];
$sqlPLC		= "tbl_project WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
$resPLC		= $this->db->count_all($sqlPLC);

$sqlPL 		= "SELECT PRJCODE, PRJNAME
				FROM tbl_project WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY PRJNAME";
$resPL		= $this->db->query($sqlPL)->result();

// Warehouse List
if($PRJSCATEG == 1)
	$ADDQRY = "PRJCODE = '$PRJCODE'";
else
	$ADDQRY = "PRJCODE = '$PRJCODE_HO'";

$sqlWHC		= "tbl_warehouse WHERE $ADDQRY";
$resWHC		= $this->db->count_all($sqlWHC);

$sqlWH 		= "SELECT WH_NUM, WH_CODE, WH_NAME
				FROM tbl_warehouse WHERE $ADDQRY ORDER BY WH_NAME";
$resWH		= $this->db->query($sqlWH)->result();

// REJECT FUNCTION
	// CEK ACCESS OTORIZATION
		$resAPP	= 0;
		$sqlAPP	= "tbl_docstepapp_det WHERE MENU_CODE = 'MN020'
					AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
		//$resAPP	= $this->db->count_all($sqlAPP);
	// CEK IR
		$DOC_NO		= '';
		$isUSED		= 0;
		$sqlIRC		= "SELECT TTK_CREATED FROM tbl_ir_header WHERE IR_NUM = '$IR_NUM'";
		$resIRC		= $this->db->query($sqlIRC)->result();
		foreach($resIRC as $rowIRC):
			$isUSED	= $rowIRC->TTK_CREATED;
		endforeach;
		if($isUSED == 1)
		{
			$sqlIR 	= "SELECT TTK_NUM FROM tbl_ttk_detail WHERE TTK_REF1_NUM = '$IR_NUM' LIMIT 1";
			$resIR	= $this->db->query($sqlIR)->result();
			foreach($resIR as $rowIR):
				$DOC_NO	= $rowIR->TTK_NUM;
			endforeach;
		}

$editable	= 0;
if($IR_STAT == 1 || $IR_STAT == 4)
{
	$editable	= 1;
}

$secGTax 	= base_url().'index.php/__l1y/getTaxP/?id=';
$secUpSJ 	= base_url().'index.php/c_inventory/c_ir180c15/updSJ/?id=';
$secUpVOL 	= base_url().'index.php/c_inventory/c_ir180c15/updVOL/?id=';
$getPO 		= base_url().'index.php/c_inventory/c_ir180c15/getPO/?id=';
$tblTax 	= "tbl_tax_ppn";
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
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'WHLocation')$WHLocation = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'revision')$revision = $LangTransl;
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
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'LocPlace')$LocPlace = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
			if($TranslCode == 'POList')$POList = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'recMore')$recMore = $LangTransl;
			if($TranslCode == 'excRec')$excRec = $LangTransl;
		endforeach;
		$secGenCode	= base_url().'index.php/c_inventory/c_ir180c15/genCode/'; // Generate Code
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$alert1		= "Jumlah yang akan diterima lebih besar dari sisa pemesanan";
			$alert1a	= "Jumlah yang akan diterima lebih besar dari sisa pemesanan. Namun akan tetap bisa diterima dengan menambahkan SPP baru.";
			$alert1b	= "Jumlah yang akan diterima lebih besar dari batas toleransi.";
			$alert1c	= "Jumlah penerimaan item ini tidak boleh melebihi jumlah/qty PO.";
			$alert2		= "Silahkan Tentukan No. PO.";
			$alert3		= "Belum ada detail item.";
			$alert4		= "Silahkan tulis alasan revisi/tolak/membatalkan dokumen.";
			$alert5		= "Pilih salah satu gudang penyimpanan.";
			$alert6		= "Seluruh item PO sudah dibuatkan dokumen penerimaan.";
			$isManual	= "Centang untuk kode manual.";
			$alertREJ	= "Tidak dapat diproses. Sudah digunakan oleh Dokumen No.: ";
			$alertAcc 	= "Belum diset kode akun penerimaan.";
			$AlertPIR	= "Tanggal terima material tidak boleh melebihi tanggal rencana terima di PO";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$alert1		= "Qty received is greater than remain of PO Qty";
			$alert1a	= "Qty received is greater than remain of PO Qty. However, it will still be accepted by adding a new SPP.";
			$alert1b	= "Qty received is greater than remain of Tolerance Qty.";
			$alert1c	= "Receipt qty of this material can not be exceed of PO Qty.";
			$alert2		= "Please select PO Number";
			$alert3		= "Item can not be empty";
			$alert4		= "Plese input the reason why you revise/reject/void the document.";
			$alert5		= "Select one of the storage warehouses.";
			$alert6		= "All PO items have received.";
			$isManual	= "Check to manual code.";
			$alertREJ	= "Can not be processed. Used by document No.: ";
			$alertAcc 	= "Not set account receipt.";
			$AlertPIR	= "Recive plan date material can not past the planned date of PO";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// IR_NUM - IR_AMOUNT
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
        	<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/barcode.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
        	<small><?php echo $PRJNAME; ?></small>
        	</h1>
        </section>

		<section class="content">
		    <div class="row">
            	<!-- Mencari Kode Purchase Order Number -->
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="hidden" name="PO_NUMX" id="PO_NUMX" class="textbox" value="<?php echo $PO_NUMX; ?>" />
                    <input type="hidden" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
                <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">		
                    <table>
                        <tr>
                            <td>
                                <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
                                <input type="hidden" name="PATTCODE" id="PATTCODE" value="<?php echo $PATTCODE; ?>">
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

					<div class="col-sm-12" id="divAlertPIR" style="display: none;">
						<div class="form-group">
							<div class="col-sm-12">
								<div class="alert alert-warning alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
									<?php echo $AlertPIR; ?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                    	<input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
		                    	<input type="hidden" name="PageFrom" id="PageFrom" value="PO">
		                    	<input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
								<?php if($isSetDocNo == 0) { ?>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
			                            <div class="col-sm-9">
			                                <div class="alert alert-danger alert-dismissible">
			                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                                    <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
			                                    <?php echo $docalert2; ?>
			                                </div>
			                            </div>
			                        </div>
		                        <?php } ?>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ReceiptCode ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="hidden" name="IR_NUMX" id="IR_NUMX" value="<?php echo $IR_NUM; ?>" class="form-control">
		                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
		                          	</div>
		                        </div>
		                        <?php /*?><div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?> </label>
		                          	<div class="col-sm-9">
		                                <label>
		                                    <input type="text" class="form-control" style="min-width:width:150px; max-width:150px" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
		                                </label>
		                                <label>
		                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
		                                </label>
		                                <label style="font-style:italic">
		                                    <?php echo $isManual; ?>
		                                </label>
		                          	</div>
		                        </div><?php */?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode; ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
		                                <input type="text" class="form-control" name="IR_CODEX" id="IR_CODEX" value="<?php echo $IR_CODE; ?>" disabled >
		                                <input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
		                                <input type="checkbox" name="isManual" id="isManual" style="display:none">
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
		                          	<div class="col-sm-4">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <?php
												if($task == 'add')
												{
													?>
		                                            <input type="text" name="IR_DATE1" class="form-control pull-left" id="IR_DATE1" value="<?php echo $IR_DATE; ?>" style="width:105px" disabled>
		                                            <input type="hidden" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
		                                            <?php
												}
												else
												{
													?>
		                                            <input type="text" name="IR_DATE1" class="form-control pull-left" id="IR_DATE1" value="<?php echo $IR_DATE; ?>" style="width:105px" disabled>
		                                            <input type="hidden" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
		                                            <?php
												}
											?>
		                                    
		                            	</div>
		                            </div>
		                          	<label for="inputName" class="col-sm-2 control-label">Tgl. Terima</label>
		                          	<div class="col-sm-3">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
											<?php
												if($task == 'add')
												{
													?>
													<input type="text" name="IR_RECD" class="form-control pull-left" id="datepicker2" value="<?php echo $IR_RECD; ?>" style="width:105px" disabled>
													<?php
												}
												else
												{
													?>
													<input type="text" name="IR_RECD" class="form-control pull-left" id="datepicker2" value="<?php echo $IR_RECD; ?>" style="width:105px">
													<?php
												}
											?>
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
													document.getElementById('IR_NUMX').value 	= myarr[0];
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
		                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" checked>
		                                &nbsp;&nbsp;PO
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PONumber; ?> </label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
												<button type="button" class="btn btn-primary" onClick="pleaseCheck()"><i class="glyphicon glyphicon-search"></i></button>
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PO_NUM" id="PO_NUM" style="max-width:160px" value="<?php echo $PO_NUMX; ?>" >
		                                    <input type="hidden" class="form-control" name="IR_REFER" id="IR_REFER" style="max-width:160px" value="<?php echo $PR_NUM; ?>" >
		                                    <input type="hidden" class="form-control" name="PO_CODE" id="PO_CODE" style="max-width:160px" value="<?php echo $PO_CODE; ?>" >
		                                    <input type="text" class="form-control" name="PO_NUM1" id="PO_NUM1" value="<?php echo "$PO_CODE"; ?>" onClick="pleaseCheck();">
		                                    <div class="input-group-btn" title="Tampilkan semua item" <?php if($IR_STAT != 1 && $IR_STAT != 4) { ?> style='display: none;' <?php } ?>>
												<!-- <button type="button" class="btn btn-warning" id="shwDet" onClick="rereshfPO()"><i class="glyphicon glyphicon-refresh"></i></button> -->
												<button type="button" class="btn btn-warning" id="shwDet" onClick="refrDetilPO()"><i class="glyphicon glyphicon-refresh"></i></button>
		                                    }
		                                    </div>
		                                    <input type="hidden" class="form-control" name="PO_NUM2" id="PO_NUM2" value="<?php echo $PO_CODE; ?>" data-toggle="modal" data-target="#mdl_addJList">
		                                </div>
		                            </div>
		                        </div>
		                        <?php
		                        	$collDt 	= "$IR_NUM~$IR_CODE";
		                        	if($task == 'add')
		                        		$getTRW = base_url().'index.php/c_inventory/c_ir180c15/getTRowC/?id=';
		                        	else
		                        		$getTRW = base_url().'index.php/c_inventory/c_ir180c15/getTRowE/?id=';

		                        	$getTRWAll 	= base_url().'index.php/c_inventory/c_ir180c15/getTRowAll/?id=';
		                        ?>
		                        <script type="text/javascript">
		                        	function refrDetilPO()
		                        	{
		                        		collDt 		= "<?=$collDt?>";
		                        		PO_NUM 		= $("#PO_NUMX").val();
		                        		collDt 		= "<?=$collDt?>"+"~"+PO_NUM;

								    	$('#tbl').DataTable(
								    	{
								    		//"scrollY": "200px", "paging": false, "scrollCollapse": true,
								    		//"scrollY": "200px", "scrollCollapse": true,

								    		"bDestroy": true,
									        "processing": true,
									        "serverSide": true,
											//"scrollX": false,
											"autoWidth": true,
											"filter": true,
									        "ajax": {
										        "url": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataIRDetAftSelPO/?id='.$PRJCODE.'&task='.$task.'&collDt=')?>"+collDt,
										        "type": "POST",
										        "complete": function()
										        {
										        	let totalrow = document.getElementById('totalrow').value;
										        	for(let i = 1; i <= totalrow; i++) {
											        	let thisVal 	= document.getElementById('ITM_QTYX'+i);
														let chkCopy 	= document.getElementById('chkCopy'+i);
														let arrVal 		= chkCopy.value;
														let arrItem 	= arrVal.split('|');
														let ACC_ID 		= arrItem[6];
														let ITM_GROUP 	= arrItem[15];
														if(ACC_ID == '')
														{
															document.getElementById('btnSave').style.display = 'none';
															if(ITM_GROUP != 'M' && ITM_GROUP != 'T')
															{
																document.getElementById('btnSave').style.display = '';
															}
														}
														else document.getElementById('btnSave').style.display = '';
														changeValue(thisVal, i)
										        	}
										        }
									        },
									        "type": "POST",
											//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
											"lengthMenu": [[50, 100, 200], [50, 100, 200]],
											"columnDefs": [	{ targets: [0,4,5,6,7,8,9], className: 'dt-body-center' },
															{ targets: [3], className: 'dt-body-right' },
														  ],
				        					"order": [[ 2, "desc" ]],
											"language": {
									            "infoFiltered":"",
									            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
									        },
										});
		                        	}

		                        	function refrDetil()
		                        	{
		                        		collDt 		= "<?=$collDt?>";
		                        		PO_NUM 		= $("#PO_NUMX").val();
		                        		collDt 		= "<?=$collDt?>"+"~"+PO_NUM;

								    	$('#tbl').DataTable(
								    	{
								    		//"scrollY": "200px", "paging": false, "scrollCollapse": true,
								    		//"scrollY": "200px", "scrollCollapse": true,
								    		"bDestroy": true,
									        "processing": true,
									        "serverSide": true,
											//"scrollX": false,
											"autoWidth": true,
											"filter": true,
									        "ajax": {
										        "url": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataIRDetAftCpy/?id='.$PRJCODE.'&task='.$task.'&collDt=')?>"+collDt,
										        "type": "POST",
										        "complete": function()
										        {
										        	let totalrow = document.getElementById('totalrow').value;
										        	for(let i = 1; i <= totalrow; i++) {
											        	thisVal = document.getElementById('ITM_QTYX'+i);
														changeValue(thisVal, i)
										        	}
										        }
									        }, 
											//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
											"lengthMenu": [[50, 100, 200], [50, 100, 200]],
											"columnDefs": [	{ targets: [0,4,5,6,7,8,9], className: 'dt-body-center' },
															{ targets: [3], className: 'dt-body-right' },
														  ],
				        					"order": [[ 2, "desc" ]],
											"language": {
									            "infoFiltered":"",
									            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
									        },
										});
		                        	}
		                        </script>
								<?php
									$url_selIR_CODE		= site_url('c_inventory/c_ir180c15/all180c15po/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $url_selIR_CODE;?>";
									function pleaseCheck()
									{
										/*title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);*/
				                        document.getElementById('PO_NUM2').click();
									}

									function rereshfPO()
									{
										
										var PO_NUM 		= document.getElementById("PO_NUM").value;
										var IR_NUM 		= document.getElementById("IR_NUM").value;
										var IR_CODE 	= document.getElementById("IR_CODE").value;
										console.log(IR_CODE);
										var IR_DATE 	= $('#datepicker1').val();
										var IR_DATE1 	= IR_DATE.split('/');
										var IR_DATED 	= IR_DATE1[0];
										var IR_DATEM 	= IR_DATE1[1];
										var IR_DATEY 	= IR_DATE1[2];
										var IR_DATE		= IR_DATEY + '-' + IR_DATEM + '-' + IR_DATED;

										document.getElementById('datepicker2').disabled = false;

										collDt 		= IR_NUM+'~'+IR_CODE+'~'+PO_NUM;

										url 		= "<?=$getPO?>";								// PENAMBAHAN DETAIL PENERIMAAN SETELAH MEIMILIH NOMOR PO
										$.ajax({
								            type: 'POST',
								            url: url,
								            data: {collDt: collDt},
								            success: function(response)
								            {
								            	var PODET 	= response.split("~");
								            	PO_CODE 	= PODET[0];
								            	PR_NUM 		= PODET[1];
								            	SPLCODE 	= PODET[2];
								            	TERM_PAY 	= PODET[3];
												PO_PLANIR 	= PODET[4];
												TOTALROW 	= PODET[5];
												console.log(TOTALROW);

												let currDate = new Date().toJSON().slice(0, 10);

												console.log(currDate+' > '+PO_PLANIR);
												if(currDate > PO_PLANIR)
												{
													// document.getElementById("divAlertPIR").style.display 	= '';
													// document.getElementById('btnSave').disabled 			= true;

													var datePIR		= new Date(PO_PLANIR);
													var PO_PIRD 	= ("0" + (datePIR.getDate() + 1)).slice(-2); // Penerimaan material max. 1x24 jam => upd. 2023-05-31
													var PO_PIRM 	= ("0" + (datePIR.getMonth() + 1)).slice(-2);
													var PO_PIRY 	= datePIR.getFullYear();
													var datePIR		= PO_PIRY + '-' + PO_PIRM + '-' + PO_PIRD; 

													var dateIR		= new Date(IR_DATE);
													var IRDD 		= ("0" + (dateIR.getDate())).slice(-2);
													var IRDM 		= ("0" + (dateIR.getMonth() + 1)).slice(-2);
													var IRDY 		= dateIR.getFullYear();
													var dateIR		= IRDY + '-' + IRDM + '-' + IRDD;

													console.log(dateIR+' > '+datePIR);
													if(dateIR > datePIR)
													{
														swal("Tanggal input sudah melebihi tanggal rencana terima di PO. Max. tanggal input 1x24 jam dari tanggal terakhir rencana terima di PO.",
														{
															icon: "warning",
														})
														.then(function()
														{
															swal.close();
															document.getElementById("divAlertPIR").style.display 	= '';
															document.getElementById('btnSave').disabled 			= true;
														});
													}
													else
													{
														document.getElementById("divAlertPIR").style.display 	= 'none';
														document.getElementById('btnSave').disabled 			= false;
													}
												}
												else
												{
													var datePIR		= new Date(PO_PLANIR);
													var PO_PIRD 	= ("0" + (datePIR.getDate() + 1)).slice(-2); // Penerimaan material max. 1x24 jam => upd. 2023-05-31
													var PO_PIRM 	= ("0" + (datePIR.getMonth() + 1)).slice(-2);
													var PO_PIRY 	= datePIR.getFullYear();
													var datePIR		= PO_PIRY + '-' + PO_PIRM + '-' + PO_PIRD; 

													var dateIR		= new Date(IR_DATE);
													var IRDD 		= ("0" + (dateIR.getDate())).slice(-2);
													var IRDM 		= ("0" + (dateIR.getMonth() + 1)).slice(-2);
													var IRDY 		= dateIR.getFullYear();
													var dateIR		= IRDY + '-' + IRDM + '-' + IRDD;

													console.log(dateIR+' > '+datePIR);
													if(dateIR > datePIR)
													{
														swal("Tanggal input sudah melebihi tanggal rencana terima di PO. Max. tanggal input 1x24 jam dari tanggal terakhir rencana terima di PO.",
														{
															icon: "warning",
														})
														.then(function()
														{
															swal.close();
															document.getElementById('datepicker2').focus();
															document.getElementById("divAlertPIR").style.display 	= '';
															document.getElementById('btnSave').disabled 			= true;
														});
														return false;
													}
													else
													{
														document.getElementById("divAlertPIR").style.display 	= 'none';
														document.getElementById('btnSave').disabled 			= false;
													}
												}

												var datePIR		= new Date(PO_PLANIR);
												var dd 			= ("0" + (datePIR.getDate())).slice(-2);
												var mm 			= ("0" + (datePIR.getMonth() + 1)).slice(-2);
												var yyyy 		= datePIR.getFullYear();
												var PO_PLANIR	= dd + '/' + mm + '/' + yyyy;
												
												if(TOTALROW == 0)
												{
													swal("<?=$alert6?>",
													{
														icon:"warning"
													});
													return false;
												}
												else
												{
													document.getElementById('totalrow').value 	= TOTALROW;
									            	document.getElementById("IR_REFER").value 	= PR_NUM;
									            	document.getElementById("PO_NUM").value 	= PO_NUM;
									            	document.getElementById("PO_NUMX").value 	= PO_NUM;
									            	document.getElementById("PO_CODE").value 	= PO_CODE;
									            	document.getElementById("PO_NUM1").value 	= PO_CODE;
									            	document.getElementById("PO_NUM2").value 	= PO_CODE;
									            	document.getElementById("SPLCODE").value 	= SPLCODE;
									            	document.getElementById("datepicker2").value= PO_PLANIR;

									            	$("#SPLCODEX").val(SPLCODE).trigger('change');
									            	$("#TERM_PAYX").val(TERM_PAY).trigger('change');
													$('#TERM_PAY').val(TERM_PAY);

									            	refrDetilPO();
									            }
								            }
								        });
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-9">
		                           		<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
		                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" >
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
		                            	<input type="hidden" class="form-control" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" >
		                        		<select name="SPLCODEX" id="SPLCODEX" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" disabled>
		                        			<option value=""> --- </option>
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
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $payType; ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" name="TERM_PAY" id="TERM_PAY" value="<?php echo $TERM_PAY; ?>" >
		                                <select name="TERM_PAYX" id="TERM_PAYX" class="form-control select2" disabled>
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
		                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="height:83px"><?php echo $IR_NOTE; ?></textarea>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $WHLocation ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="WH_CODE" id="WH_CODE" class="form-control select2" >
		                                  	<?php
		                                        if($resWHC > 0)
		                                        {
													?>
		                                            <option value="" style="font-style:italic"> --- </option>
		                                            <?php
		                                            foreach($resWH as $rowWH) :
		                                                $WH_CODE1 = $rowWH->WH_NUM;
		                                                $WH_CODE2 = $rowWH->WH_CODE;
		                                                $WH_NAME1 = $rowWH->WH_NAME;
		                                                ?>
		                                  				<option value="<?php echo $WH_CODE1; ?>" <?php if($WH_CODE1 == $WH_CODE) { ?> selected <?php } ?>><?php echo "$WH_CODE2 - $WH_NAME1"; ?></option>
		                                  	<?php
		                                            endforeach;
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                  				<option value="" style="font-style:italic; text-align:center"> --- </option>
		                                  			<?php
		                                        }
		                                        ?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $LocPlace; ?></label>
		                            <div class="col-sm-6">
		                                <input type="text" class="form-control" name="IR_LOC" id="IR_LOC" value="<?php echo $IR_LOC; ?>" />
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
		                        <?php
									$isDisabled = 1;
									if($IR_STAT == 0 || $IR_STAT == 1 || $IR_STAT == 4)
									{
										$isDisabled = 0;
									}
								?>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-6">
		                                <select name="IR_STAT" id="IR_STAT" class="form-control select2" onChange="chkSTAT(this.value)">
		                                    <?php
												$disableBtn	= 0;
												if($IR_STAT == 5 || $IR_STAT == 6 || $IR_STAT == 9)
												{
													$disableBtn	= 1;
												}
												if($IR_STAT != 1 AND $IR_STAT != 4) 
												{
													?>
		                                                <option value="1"<?php if($IR_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
		                                                <option value="2"<?php if($IR_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
		                                                <option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
		                                                <option value="4"<?php if($IR_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
		                                                <option value="5"<?php if($IR_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
		                                                <option value="6"<?php if($IR_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
		                                                <option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
		                                                <option value="9"<?php if($IR_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
													<?php
												}
												else
												{
													?>
														<option value="1"<?php if($IR_STAT == 1) { ?> selected <?php } ?>>New</option>
														<option value="2"<?php if($IR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
													<?php
												}
											?>
		                                </select>
		                            </div>
		                            <div class="col-sm-3" style="display: <?=$styleMoreItem?>;">
							            <input type="radio" name="PR_CREATE" id="iCheck1" value="1" <?php if($PR_CREATE == 1) { ?> checked <?php } ?>>
							            <label>Yes</label>&nbsp;&nbsp;
							            <input type="radio" name="PR_CREATE" id="iCheck2" value="0" <?php if($PR_CREATE == 0) { ?> checked <?php } ?>>
							            <label>No</label>
		                            </div>
		                        </div>
		                        <script type="text/javascript">
									$(document).ready(function () {
									    $('#iCheck1, #iCheck2').iCheck({
									        radioClass: 'iradio_flat-orange'
									    });

									    $('input').on('ifClicked', function (event) {
								            var value = $(this).val();
									        if(value == 1)
									        {
									        	document.getElementById('RECMORE_NOTES').style.display = '';
									        }
									        else
									        {
									        	document.getElementById('RECMORE_NOTES').style.display = 'none';
									        	totalrow	= document.getElementById("totalrow").value;	
												IR_TOTAL_AM	= 0;	
												for(i=1; i<=totalrow; i++)
												{
													let myObj 	= document.getElementById('REMAINQTY'+i);
													var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

													//console.log(i+' = '+ theObj)
													
													if(theObj != null)
													{
														var REMAINQTY	= parseFloat(document.getElementById('REMAINQTY'+i).value);
														document.getElementById('ITM_QTYX'+i).value 	= REMAINQTY;
														document.getElementById('ITM_QTY'+i).value 		= parseFloat(Math.abs(REMAINQTY));
														document.getElementById('ISPRCREATE'+i).value 	= 0;
														document.getElementById('ADD_PRVOLM'+i).value 	= 0;
														thisVal 		= document.getElementById('ITM_QTYX'+i);
														changeValue(thisVal, i)
													}
												}
									        }
									    });
									});
		                        </script>
								<?php
									$url_AddItem	= site_url('c_inventory/c_ir180c15/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									function chkSTAT(selSTAT)
									{
										if(selSTAT == 5 || selSTAT == 9)
										{
											document.getElementById('IRNOT2').style.display = '';
											document.getElementById("IR_NOTE2").disabled = false;
											
											var isUSED	= document.getElementById('isUSED').value;
											if(isUSED > 0)
											{
												swal('<?php echo $alertREJ; ?>'+' <?php echo $DOC_NO; ?>',
												{
													icon: "warning",
												});
												return false;
											}
											else
											{
												document.getElementById('btnREJECT').style.display = '';
											}
										}
										else if(selSTAT == 6)
										{
											document.getElementById('IRNOT2').style.display = '';
											document.getElementById("IR_NOTE2").disabled = false;
											
											document.getElementById('btnREJECT').style.display = '';
										}
										else
										{
											document.getElementById('btnREJECT').style.display = 'none';
											document.getElementById("IR_NOTE2").disabled = true;
										}
									}
								</script>
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

					<div class="col-md-12" id="IRNOT2" <?php if($IR_NOTE2 == '') { ?> style="display:none" <?php } ?>>
						<div class="box box-danger">
							<div class="box-header with-border">
								<i class="fa fa-commenting"></i>
								<h3 class="box-title"><?php echo $ApproverNotes." / ".$revision ?></h3>
							</div>
							<div class="box-body">
								<textarea class="form-control" name="IR_NOTE2"  id="IR_NOTE2" style="height:50px" disabled><?php echo $IR_NOTE2; ?></textarea>
		                    </div>
		                </div>
		            </div>
					<div class="col-md-12" id="RECMORE_NOTES" <?php if($PR_CREATE == 0) { ?> style="display:none" <?php } ?>>
						<div class="alert alert-warning alert-dismissible">
			                <h4 style="display: none;"><i class="icon fa fa-warning"></i> <?=$Notes?>!</h4>
			                Dokumen ini akan menerima material lebih besar dari jumlah yang dipesan. Sistem akan membuatkan dokumen SPP dan akan disinkronisasi dengan PO yang terkait dengan dokumen LPM ini sesuai dengan jumlah kelebihan yang dimasukan. Dokumen SPP akan terbentuk setelah disetujui tingkat terakhir.
		              	</div>
		            </div>

                    <div class="col-md-12">
                        <!-- <div class="search-table-outter"> -->
                        <div class="search-table-outter">
		            		<table id="tbl" class="table table-bordered table-striped table-hover table-responsive search-table inner" width="100%">
                                <thead>
                                    <tr>
                                        <th width="2%" height="25" style="text-align:left">&nbsp;</th>
                                      	<th width="28%" style="text-align:center; vertical-align: middle"><?php echo $ItemName; ?> </th>
                                      	<th width="15%" style="text-align:center; vertical-align: middle">No. Gudang</th>
                                        <th width="10%" style="text-align:center; vertical-align: middle"><?php echo $Quantity; ?> </th>
                                        <th width="5%" style="text-align:center; vertical-align: middle"><?php echo $Unit; ?> </th>
                                        <th width="5%" style="text-align:center; vertical-align: middle"><?php echo $Price; ?> </th>
                                        <th width="5%" style="text-align:center; vertical-align: middle"><?php echo $Discount; ?> (%)</th>
                                        <th width="5%" style="text-align:center; vertical-align: middle"><?php echo $Discount; ?></th>
                                        <th width="5%" style="text-align:center; vertical-align: middle"><?php echo $PPn; ?></th>
                                        <th width="5%" style="text-align:center; vertical-align: middle"><?php echo $Total; ?></th>
                                        <th width="15%" style="text-align:center; vertical-align: middle"><?php echo $Remarks ?></th>
				                  	</tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <?php
									$isEdit 	= 0;
									if($IR_STAT == 1 || $IR_STAT == 4)
										$isEdit	= 1;
									
									$resultC	= 0;
	                                if($task == 'edit')
	                                {
										$sqlDET		= "SELECT A.IR_ID, A.POD_ID, A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
															A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT, A.ITM_UNIT2,
															A.ITM_QTY_REM, A.ITM_QTY, 0 AS PO_VOLM, A.POD_ID,
															A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
															A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
															A.ISPRCREATE, A.ADD_PRVOLM, A.SJ_NUM,
															B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
															B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
															B.ISFASTM, B.ISWAGE,
															C.PR_NUM, C.PO_NUM
														FROM tbl_ir_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
															INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
																AND C.PRJCODE = '$PRJCODE'
														WHERE 
															A.IR_NUM = '$IR_NUM' 
															AND A.PRJCODE = '$PRJCODE' ORDER BY B.ITM_NAME";
										$result = $this->db->query($sqlDET)->result();

										$sqlDETC	= "tbl_ir_detail A
															INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
														WHERE 
														A.IR_NUM = '$IR_NUM' 
														AND A.PRJCODE = '$PRJCODE'";
										$resultC 	= $this->db->count_all($sqlDETC);
									}
									
									$i		= 0;
									$j		= 0;
									$disBtn = 0;
									$IR_AMOUNT			= 0;
									$IR_DISC			= 0;
									$IR_PPN				= 0;
									$IR_AMOUNT_NETT		= 0;
									$TAXCODE_PPN 		= "";
									$TAXCODE_PPH		= "";
									if($resultC > 0)
									{
										foreach($result as $row) :
											$currRow  	= ++$i;
											$IR_NUM 		= $IR_NUM;
											$IR_CODE 		= $IR_CODE;
											$POD_ID 		= $row->POD_ID;
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
											$IR_ID			= $row->IR_ID;
											$SJ_NUM			= $row->SJ_NUM;
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


											$REMAINQTY		= $ITM_QTY1 - $TOT_IRQTY;
											
											if($task == 'add')
												$ITM_QTY 	= $REMAINQTY;
	                                        if($task == 'edit' && isset($_POST['PO_NUMX']))
	                                            $ITM_QTY 	= $REMAINQTY;
											else
												$ITM_QTY 	= $ITM_QTY;
											
											if($task == 'add')
												$ITM_TOTAL 	= $ITM_QTY * $ITM_PRICE;
											else
												$ITM_TOTAL 	= $row->ITM_TOTAL;						// Non-PPn

											$IR_AMOUNT 		= $IR_AMOUNT + $ITM_TOTAL;
											$IR_DISC 		= $IR_DISC + $ITM_DISC;					// TOTAL DISKON
											$IR_PPN 		= $IR_PPN + $TAXPRICE1;					// TOTAL PAJAK
											$IR_AMOUNTNET 	= $ITM_TOTAL - $ITM_DISC + $TAXPRICE1;
											$IR_AMOUNT_NETT	= $IR_AMOUNT_NETT + $IR_AMOUNTNET;

											$ItmCol1	= '';
											$ItmCol2	= '';
											$ttl 		= '';
											$divDesc 	= '';

											if($ITM_CATEG == 'UA' && $ACC_ID_UM == '')
											{
												$disBtn 	= 1;
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= 'Item ongkos angkut ini belum disetting Kode Akun';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
												$isDisabled = 1;
											}
											elseif($ACC_ID == '' && $ITM_CATEG != 'UA')
											{
												$disBtn 	= 1;
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= 'Belum disetting kode akun penerimaan';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
												$isDisabled = 1;
											}

											// ---------- ITM_GROUP = 'O' & ITM_GORUP = 'T' => tidak menjadi stock: upd: 22-06-2023 (ACC: Pak dede)
											/*
											if($ITM_GROUP != 'M' && $ITM_GROUP != 'M')
											{
												$disBtn 	= 0;
												$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
												$ItmCol2	= '</span>';
												$ttl 		= 'Belum disetting kode akun penerimaan';
												$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;Penerimaan Overhead";
												$isDisabled = 0;
											}
											--------------------------- END ----------------------- */

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

											/*if($ACC_ID_UM == '')
												$LPMDesc 	= "LPM Overhead : $alertAccUM";
											else
												$LPMDesc 	= "LPM Overhead";*/

											if($ITM_GROUP != 'M' && $ITM_GROUP != 'T')
											{
												$disBtn 	= 0;
												$ItmCol0a	= '';
												$ItmCol1a	= '';
												$ItmCol2a	= '';
												$ttla 		= '';
												$divDesca 	= '';
											}

											$secDelROW 	= base_url().'index.php/c_inventory/c_ir180c15/delROW/?id=';
											$delROW 	= "$secDelROW~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE";
											$secCopy 	= base_url().'index.php/c_inventory/c_ir180c15/copyROW/?id=';
											$copyROW 	= "$secCopy~$IR_NUM~$IR_ID~$POD_ID~$ITM_CODE~$ITM_NAME~$PRJCODE~$IR_ID";
										endforeach;
										?>
										<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currRow; ?>">
										<?php
									}
									if($task == 'add')
									{
										?>
										<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currRow; ?>">
										<?php
									}
                                ?>
                       		</table>
                       	</div>
                   	</div>
                    <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $IR_AMOUNT; ?>">
                    <input type="hidden" name="IR_DISC" id="IR_DISC" value="<?php echo $IR_DISC; ?>">
                    <input type="hidden" name="IR_PPN" id="IR_PPN" value="<?php echo $IR_PPN; ?>">
                    <input type="hidden" name="IR_AMOUNT_NETT" id="IR_AMOUNT_NETT" value="<?php echo $IR_AMOUNT_NETT; ?>">

                    <input type="hidden" name="TAXCODE_PPN" id="TAXCODE_PPN" value="<?php echo $TAXCODE_PPN; ?>">
                    <input type="hidden" name="TAXCODE_PPH" id="TAXCODE_PPH" value="<?php echo $TAXCODE_PPH; ?>">
                    &nbsp;
                    <br>
			        <div class="col-md-6">
	                    <div class="form-group">
	                        <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
	                        	<?php
									$showBtn	= 0;
									if($IR_STAT == 2 || $IR_STAT == 3 || $IR_STAT == 9)
									{
										$showBtn	= 0;
									}
									else
									{
										$showBtn	= 1;
									}
									if($resCAPP > 0)
									{
										if($ISCREATE == 1 && $showBtn == 1 && $disBtn == 0)
										{
											if($task=='add')
											{
												?>
													<button class="btn btn-primary" id="btnSave">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											else
											{
												?>
													<button class="btn btn-primary" id="btnSave">
													<i class="fa fa-save"></i></button>
												<?php
											}
										}
									}
									?>
	                               		<button class="btn btn-primary" id="btnREJECT" style="display:none" >
	                                    <i class="fa fa-save"></i></button>
	                               	<?php
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
								?>
	                        </div>
	                    </div>
	                </div>
				</form>
		        <div class="col-md-12">
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

	    	<!-- ============ START MODAL PO LIST =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addJList" name='mdl_addJList' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $POList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="2%">&nbsp;</th>
							                                        <th width="15%" style="vertical-align:middle; text-align:center" nowrap="nowrap"><?php echo $PONumber; ?></th>
							                                        <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
							                                        <th width="30%" style="vertical-align:middle; text-align:center" nowrap><?php echo $SupplierName; ?></th>
							                                        <th width="40%" style="vertical-align:middle; text-align:center" nowrap><span style="text-align:center;"><?php echo $Description; ?></span></th>
                                        							<th width="8%" style="vertical-align:middle; text-align:center;" nowrap><span style="text-align:center"><?php echo $ReceivePlan; ?></span></th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh0" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
		                                </div>
                                      	<input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$(document).ready(function()
					{
				    	$('#example0').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataPOList/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[50, 100, 200], [50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
										  ],
        					"order": [[ 2, "desc" ]],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});

					$("#idRefresh0").click(function()
					{
						$('#example0').DataTable().ajax.reload();
					});

					var selectedRows = 0;
					function pickThis0(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk0']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck0").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail0").click(function()
					    {
							var totChck 	= $("#rowCheck0").val();
							
							if(totChck == 0)
							{
								swal('<?php echo $alert1; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk0']:checked"), function()
						    {
						      	add_header($(this).val());
						    });

						    $('#mdl_addJList').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose0").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL PO LIST =============== -->
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
<script>
	document.addEventListener("contextmenu", function(e){
	    e.preventDefault();
	}, false);

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
	      autoclose: true,
		  endDate: '+1d'
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
	      autoclose: true,
		  endDate: '+0d'
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

		$('#datepicker2').on('change', function(e) {
			console.log(e.target.value);
			var PO_NUM 		= $('#PO_NUMX').val();
			var IR_DATE 	= $('#datepicker1').val();
			var IR_DATE1 	= IR_DATE.split('/');
			var IR_DATED 	= IR_DATE1[0];
			var IR_DATEM 	= IR_DATE1[1];
			var IR_DATEY 	= IR_DATE1[2];
			var IR_DATE		= IR_DATEY + '-' + IR_DATEM + '-' + IR_DATED;

			$.ajax({
				url: '<?php echo site_url("c_inventory/c_ir180c15/getDatePIR") ?>',
				type: "POST",
				dataType: "json",
				data: {PO_NUM:PO_NUM},
				success: function(result) {
					var PO_PLANIR 	= result[0].PO_PLANIR;

					var RecIRSel 	= e.target.value;
					var RecIRSel1 	= RecIRSel.split('/');
					var RecIRSelD 	= RecIRSel1[0];
					var RecIRSelM 	= RecIRSel1[1];
					var RecIRSelY 	= RecIRSel1[2];
					var RecIRSel	= RecIRSelY + '-' + RecIRSelM + '-' + RecIRSelD;

					// let dateRecIR	= new Date(RecIRSel);
					// let dd 			= ("0" + (dateRecIR.getDate())).slice(-2);
					// let mm 			= ("0" + (dateRecIR.getMonth() + 1)).slice(-2);
					// let yyyy 		= dateRecIR.getFullYear();
					// let dateRecIR	= yyyy + '-' + mm + '-' + dd;

					console.log(RecIRSel+" > "+PO_PLANIR);
					if(RecIRSel > PO_PLANIR)
					{
						document.getElementById("divAlertPIR").style.display 	= '';
						document.getElementById('btnSave').disabled 			= true;
					}
					else
					{
						var datePIR		= new Date(PO_PLANIR);
						var PO_PIRD 	= ("0" + (datePIR.getDate() + 1)).slice(-2); // Penerimaan material max. 1x24 jam => upd. 2023-05-31
						var PO_PIRM 	= ("0" + (datePIR.getMonth() + 1)).slice(-2);
						var PO_PIRY 	= datePIR.getFullYear();
						var datePIR		= PO_PIRY + '-' + PO_PIRM + '-' + PO_PIRD; 

						var dateIR		= new Date(IR_DATE);
						var IRDD 		= ("0" + (dateIR.getDate())).slice(-2);
						var IRDM 		= ("0" + (dateIR.getMonth() + 1)).slice(-2);
						var IRDY 		= dateIR.getFullYear();
						var dateIR		= IRDY + '-' + IRDM + '-' + IRDD;

						console.log(dateIR+' > '+datePIR);
						if(dateIR > datePIR)
						{
							swal("Tanggal input sudah melebihi tanggal rencana terima di PO. Max. tanggal input 1x24 jam dari tanggal terakhir rencana terima di PO.",
							{
								icon: "warning",
							})
							.then(function()
							{
								swal.close();
								document.getElementById('datepicker2').focus();
								document.getElementById("divAlertPIR").style.display 	= '';
								document.getElementById('btnSave').disabled 			= true;
							});
							return false;
						}
						else
						{
							document.getElementById("divAlertPIR").style.display 	= 'none';
							document.getElementById('btnSave').disabled 			= false;
						}
					}
				}
			})
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
					// 	// $('#IR_STAT>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#IR_STAT').removeAttr('disabled','disabled');
							// $('#IR_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#IR_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#IR_STAT').removeAttr('disabled','disabled');
							// $('#IR_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#IR_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#IR_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE

	<?php
		// START : GENERATE MANUAL CODE
			if($task == 'add')
			{
				?>
					$(document).ready(function()
					{
						setInterval(function(){addUCODE()}, 1000);
					});
				<?php
			}
			else
			{
				?>
					$(document).ready(function()
					{
						collDt 		= "<?=$collDt?>";
	            		PO_NUM 		= $("#PO_NUMX").val();

				    	$('#tbl').DataTable(
				    	{
				    		//"scrollY": "200px", "paging": false, "scrollCollapse": true,
							//"scrollY": "200px", "scrollCollapse": true,
				    		"bDestroy": true,
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataIRDetEdit/?id='.$PRJCODE.'&collDt='.$collDt.'&task='.$task.'&PO_NUM=')?>"+PO_NUM,
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[50, 100, 200], [50, 100, 200]],
							"columnDefs": [	{ targets: [0,4,5,6,7,8,9], className: 'dt-body-center' },
											{ targets: [3], className: 'dt-body-right' },
										  ],
	    					"order": [[ 2, "desc" ]],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});

				        var url 		= "<?php echo $getTRW; ?>";
						var IR_NUM 		= "<?=$IR_NUM?>";

						$.ajax({
				            type: 'POST',
				            url: url,
				            data: {IR_NUM: IR_NUM},
				            success: function(tRow)
				            {
				            	document.getElementById('totalrow').value = tRow;
				            }
				        });
					});
				<?php
			}
		// END : GENERATE MANUAL CODE
	?>

	function addUCODE()
	{
		var task 		= "<?=$task?>";
		var DOCNUM		= document.getElementById('IR_NUM').value;
		var DOCCODE		= document.getElementById('IR_CODE').value;
		var DOCDATE		= document.getElementById('datepicker1').value;
		var ACC_ID		= "";
		var PDManNo 	= "";

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: PDManNo,
							DOCDATE 		: DOCDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: 'IR'
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	$('#IR_CODE').val(docCode);
            	$('#IR_CODEX').val(docCode);
            }
        });
	}

	var decFormat		= 2;
	
	function changeSJ(SJ_NUM, IR_ID, row)
	{
		var IR_NUM 	= "<?=$IR_NUM?>";
		var ITMVOL	= document.getElementById('ITM_QTY'+row).value;
		var ITMCOD	= document.getElementById('data'+row+'ITM_CODE').value;
		var ITMREM	= document.getElementById('ITM_QTY_REM'+row).value;

		var collID 	= IR_ID+'~'+IR_NUM+'~'+SJ_NUM+'~'+ITMVOL+'~'+ITMCOD+'~'+ITMREM;
        
		totalrow	= document.getElementById("totalrow").value;

		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_CODE');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			if(theObj != null)
			{
				ITMCODE	= document.getElementById('data'+i+'ITM_CODE').value;
				SJNUM	= document.getElementById('data'+i+'SJ_NUM').value;

				if((SJ_NUM == SJNUM) && SJ_NUM != '')
				{
					SJNUM2 		= SJNUM;
					ITMCODE2	= ITMCODE;
					sameRow2 	= i;
					for(i=1; i<=totalrow; i++)
					{
						let myObj 	= document.getElementById('data'+i+'ITM_CODE');
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
						if(theObj != null)
						{
							ITMCODE3	= document.getElementById('data'+i+'ITM_CODE').value;
							ITMNAME3	= document.getElementById('itemname'+i).value;
							SJNUM3		= document.getElementById('data'+i+'SJ_NUM').value;
							if(i != sameRow2 && (ITMCODE3 == ITMCODE2 && SJNUM3 == SJNUM2))
							{
								swal("Ada nomor gudang yang sama untuk item "+ITMNAME3,
								{
									icon: "warning",
								})
								.then(function()
								{
									swal.close();
									document.getElementById('data'+row+'SJ_NUM').value 	= '';
									document.getElementById('data'+row+'SJ_NUM').focus();
								});
								return false;
							}
						}
					}
				}
			}
		}
				
		$.ajax({
            type: 'POST',
            url: "<?=$secUpSJ?>",
            data: {collID: collID},
            success: function(response)
            {
            	console.log(response)
            }
        });
	}
	
	function changeValue(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var isChecked 	= $("input[name=PR_CREATE]:checked").val();

		var REMAINQTY	= parseFloat(document.getElementById('REMAINQTY'+theRow).value);	// REMAIN QTY
		var ITM_QTYX 	= eval(thisVal).value.split(",").join("");							// IR VOLUME NOW
		//console.log('ITM_QTYX = '+ITM_QTYX+' & REMAINQTY = '+REMAINQTY+' & isChecked = '+isChecked)

		if(ITM_QTYX > REMAINQTY)
		{
			if(isChecked == 0)
			{
				swal("<?php echo $alert1; ?>",
				{
					icon: "warning",
				});
				document.getElementById('ITM_QTYX'+theRow).value 	= REMAINQTY;
				document.getElementById('ITM_QTY'+theRow).value 	= parseFloat(Math.abs(REMAINQTY));
				document.getElementById('ISPRCREATE'+theRow).value 	= 0;
				document.getElementById('ADD_PRVOLM'+theRow).value 	= 0;
				document.getElementById('ITM_QTYX'+theRow).focus();
				return false;
			}
			else
			{
				ITM_CATEG 	= document.getElementById('ITM_CATEG'+theRow).value;
				if(ITM_CATEG == 'MC')			// Material Curah
				{
					MAXTOLERANCE 	= parseFloat(REMAINQTY) + parseFloat(REMAINQTY * 0.02);
					if(ITM_QTYX > MAXTOLERANCE)
					{
						swal("<?php echo $alert1b; ?>",
						{
							icon: "warning",
						});
						document.getElementById('ITM_QTYX'+theRow).value 	= MAXTOLERANCE;
						document.getElementById('ITM_QTY'+theRow).value 	= parseFloat(Math.abs(MAXTOLERANCE));
						document.getElementById('ISPRCREATE'+theRow).value 	= 1;

						ADD_PRVOLM = parseFloat(MAXTOLERANCE) - parseFloat(REMAINQTY);
						document.getElementById('ADD_PRVOLM'+theRow).value 	= ADD_PRVOLM;
					}
					else
					{
						swal("<?php echo $alert1a; ?>",
						{
							icon: "warning",
						});
						document.getElementById('ITM_QTYX'+theRow).value 	= ITM_QTYX;
						document.getElementById('ITM_QTY'+theRow).value 	= parseFloat(Math.abs(ITM_QTYX));
						document.getElementById('ISPRCREATE'+theRow).value 	= 1;

						ADD_PRVOLM = parseFloat(ITM_QTYX) - parseFloat(REMAINQTY);
						document.getElementById('ADD_PRVOLM'+theRow).value 	= ADD_PRVOLM;
					}
				}
				else
				{
					swal("<?php echo $alert1c; ?>",
					{
						icon: "warning",
					});
					document.getElementById('ITM_QTYX'+theRow).value 	= REMAINQTY;
					document.getElementById('ITM_QTY'+theRow).value 	= parseFloat(Math.abs(REMAINQTY));
					document.getElementById('ISPRCREATE'+theRow).value 	= 0;
					document.getElementById('ADD_PRVOLM'+theRow).value 	= 0;
					document.getElementById('ITM_QTYX'+theRow).focus();
					return false;
				}
			}
		}
		
		document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTYX));
		document.getElementById('ITM_QTYX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYX)),decFormat));
		
		var ITM_DISP			= document.getElementById('ITM_DISP'+theRow).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE			= document.getElementById('ITM_PRICE'+theRow).value;

		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT			= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('ITM_DISC'+theRow).value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCX'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		document.getElementById('ITM_TOTAL'+theRow).value 	= parseFloat(Math.abs(ITM_TOTAL));
		
		var TAXCODE1		= document.getElementById('TAXCODE1'+theRow).value;
		var taxPerc			= 0;
		if(TAXCODE1 == 0 || TAXCODE1 == '')
		{
        	taxPerc 		= parseFloat(0 / 100);

			ITM_TOTAL_NETT	= parseFloat(ITM_TOTAL) - parseFloat(DISCOUNT) + parseFloat(0);
			document.getElementById('TAXPRICE1'+theRow).value 			= parseFloat(0);
			document.getElementById('ITM_TOTAL_NETT'+theRow).value 		= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL_NETT)),decFormat);

			totalrow		= document.getElementById("totalrow").value;
			TIR_AMOUNT		= 0;
			TIR_DISC		= 0;
			TIR_PPN			= 0;
			TIR_AMOUNT_NETT	= 0;
			for(i=1; i<=totalrow; i++)
			{
				let myObj 	= document.getElementById('ITM_TOTAL'+i);
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					ITM_TOTAL		= document.getElementById('ITM_TOTAL'+i).value;
					ITM_DISC		= document.getElementById('ITM_DISC'+i).value;
					TAXPRICE1		= document.getElementById('TAXPRICE1'+i).value;

					TIR_AMOUNT		= parseFloat(TIR_AMOUNT) + parseFloat(ITM_TOTAL);
					TIR_DISC		= parseFloat(TIR_DISC) + parseFloat(ITM_DISC);
					TIR_PPN			= parseFloat(TIR_PPN) + parseFloat(TAXPRICE1);
					TIR_AMOUNTX		= parseFloat(ITM_TOTAL) - parseFloat(ITM_DISC) + parseFloat(TIR_PPN);

					TIR_AMOUNT_NETT	= parseFloat(TIR_AMOUNT_NETT) + parseFloat(TIR_AMOUNTX);
				}
			}
			document.getElementById('IR_AMOUNT').value 		= TIR_AMOUNT;
			document.getElementById('IR_DISC').value 		= TIR_DISC;
			document.getElementById('IR_PPN').value 		= TIR_PPN;
			document.getElementById('IR_AMOUNT_NETT').value = TIR_AMOUNT_NETT;
		}
		else
		{
			var TOT_ITMTEMP = parseFloat(ITM_TOTAL) - parseFloat(DISCOUNT);

			var url 		= "<?php echo $secGTax; ?>";
			var collID		= "<?php echo $tblTax; ?>~"+TAXCODE1;
			$.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(taxPerc2)
	            {
	            	var myarr 		= taxPerc2.split("~");
	            	var taxType 	= myarr[0];
	            	var taxPerc1 	= myarr[1];

	            	taxPerc 		= parseFloat(taxPerc1 / 100);
	            	TAXPRICE			= parseFloat(TOT_ITMTEMP) * taxPerc;

					if(taxType == 1)		// 1 = PPn, 2 = PPh
						G_itmTot		= parseFloat(TOT_ITMTEMP) + parseFloat(TAXPRICE);
					else
						G_itmTot		= parseFloat(TOT_ITMTEMP) - parseFloat(TAXPRICE);
					
					document.getElementById('TAXPRICE1'+theRow).value 			= RoundNDecimal(parseFloat(Math.abs(TAXPRICE)),decFormat);
					document.getElementById('ITM_TOTAL_NETT'+theRow).value 		= RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);

					totalrow		= document.getElementById("totalrow").value;
					TIR_AMOUNT		= 0;
					TIR_DISC		= 0;
					TIR_PPN			= 0;
					TIR_AMOUNT_NETT	= 0;
					for(i=1; i<=totalrow; i++)
					{
						let myObj 	= document.getElementById('ITM_TOTAL'+i);
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

						//console.log(i+' = '+ theObj)
						
						if(theObj != null)
						{
							ITM_TOTAL		= document.getElementById('ITM_TOTAL'+i).value;
							ITM_DISC		= document.getElementById('ITM_DISC'+i).value;
							TAXPRICE1		= document.getElementById('TAXPRICE1'+i).value;

							TIR_AMOUNT		= parseFloat(TIR_AMOUNT) + parseFloat(ITM_TOTAL);
							TIR_DISC		= parseFloat(TIR_DISC) + parseFloat(ITM_DISC);
							TIR_PPN			= parseFloat(TIR_PPN) + parseFloat(TAXPRICE1);
							TIR_AMOUNTX		= parseFloat(ITM_TOTAL) - parseFloat(ITM_DISC) + parseFloat(TIR_PPN);

							TIR_AMOUNT_NETT	= parseFloat(TIR_AMOUNT_NETT) + parseFloat(TIR_AMOUNTX);
						}
					}
					document.getElementById('IR_AMOUNT').value 		= TIR_AMOUNT;
					document.getElementById('IR_DISC').value 		= TIR_DISC;
					document.getElementById('IR_PPN').value 		= TIR_PPN;
					document.getElementById('IR_AMOUNT_NETT').value = TIR_AMOUNT_NETT;
	            }
	        });
		}

		var POD_ID	= document.getElementById('data'+theRow+'POD_ID').value;
		var IR_ID	= document.getElementById('data'+theRow+'IR_ID').value;
		var IR_NUM	= document.getElementById('data'+theRow+'IR_NUM').value;
		var ITMVOL	= document.getElementById('ITM_QTY'+theRow).value;
		var ITMCOD	= document.getElementById('data'+theRow+'ITM_CODE').value;
		var ITMREM	= document.getElementById('ITM_QTY_REM'+theRow).value;
		var POVOLM	= document.getElementById('ITM_QTY_PO'+theRow).value;

		var collREM = IR_ID+'~'+IR_NUM+'~'+ITMVOL+'~'+ITMCOD+'~'+ITMREM+'~'+POVOLM+'~'+POD_ID;
		$.ajax({
            type: 'POST',
            url: "<?=$secUpVOL?>",
            data: {collREM: collREM},
            success: function(response)
            {
            	arrItem 	= response.split("~");
            	IR_ID 		= arrItem[0];
            	IR_NUM 		= arrItem[1];
            	QTYREM 		= arrItem[2];

            	document.getElementById('REMAINQTY'+theRow).value 	= QTYREM;
            	document.getElementById('ITM_QTY_REM'+theRow).value = QTYREM;
            }
        });
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
		document.getElementById('ITM_DISCX'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
				
		var ITM_TOTAL_NETT 	= document.getElementById('ITM_TOTAL_NETT'+row).value
		var DISCOUNTP		= parseFloat(ITM_DISC / ITM_TOTAL_NETT * 100);
		
		document.getElementById('ITM_DISP'+row).value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('ITM_DISPx'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		
		document.getElementById('ITM_DISC'+row).value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCX'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
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
		/*if(parseFloat(ITM_QTYX) > parseFloat(ITM_QTY_MIN))
		{
			swal('Qty can not greater then '+ ITM_QTY_MIN);
			document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY_MIN));
			document.getElementById('ITM_QTYX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_MIN)),decFormat));
		}
		else
		{*/
			//document.getElementById('ITM_QTY'+theRow).value 		= parseFloat(Math.abs(ITM_QTY));
			//document.getElementById('ITM_QTYX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		//}
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('IR_AMOUNTNETX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		// PAJAK
		TAXCODE1			= document.getElementById('TAXCODE1'+theRow).value;
		var taxPerc			= 0;
		if(TAXCODE1 == 0 || TAXCODE1 == '')
		{
        	taxPerc 	= parseFloat(0 / 100);

        	itmTax		= parseFloat(ITM_TOTAL) * taxPerc;
			G_itmTot	= parseFloat(ITM_TOTAL) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('ITM_TOTAL_NETT'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);

			totalrow	= document.getElementById("totalrow").value;	
			IR_TOTAL_AM	= 0;	
			for(i=1; i<=totalrow; i++)
			{
				let myObj 	= document.getElementById('ITM_TOTAL_NETT'+i);
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					ITM_TOTAL_NETT		= document.getElementById('ITM_TOTAL_NETT'+i).value;
					IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(ITM_TOTAL_NETT);
				}
			}
			document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
		}
		else
		{
			var url 		= "<?php echo $secGTax; ?>";
			var collID		= "<?php echo $tblTax; ?>~"+TAXCODE1;
			$.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(taxPerc1)
	            {
	            	taxPerc 	= parseFloat(taxPerc1 / 100);

		        	itmTax		= parseFloat(ITM_TOTAL) * taxPerc;
					G_itmTot	= parseFloat(ITM_TOTAL) + parseFloat(itmTax);
					document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
					document.getElementById('ITM_TOTAL_NETT'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);

					totalrow	= document.getElementById("totalrow").value;	
					IR_TOTAL_AM	= 0;	
					for(i=1; i<=totalrow; i++)
					{
						let myObj 	= document.getElementById('ITM_TOTAL_NETT'+i);
						var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

						//console.log(i+' = '+ theObj)
						
						if(theObj != null)
						{
							ITM_TOTAL_NETT		= document.getElementById('ITM_TOTAL_NETT'+i).value;
							IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(ITM_TOTAL_NETT);
						}
					}
					document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	            }
	        });
		}
		
		/*if(TAXCODE1 == 'TAX01')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.1;
			G_itmTot	= parseFloat(ITM_TOTAL) + parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('ITM_TOTAL_NETT'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(TAXCODE1 == 'TAX02')
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0.03;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('ITM_TOTAL_NETT'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(ITM_TOTAL) * 0;
			G_itmTot	= parseFloat(ITM_TOTAL) - parseFloat(itmTax);
			document.getElementById('TAXPRICE1'+theRow).value = RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('ITM_TOTAL_NETT'+theRow).value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			ITM_TOTAL_NETT		= document.getElementById('ITM_TOTAL_NETT'+i).value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(ITM_TOTAL_NETT);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;*/
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_PRICE'+theRow).value 		= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('ITM_QTY'+theRow).value;
		var ITM_PRICE 	= document.getElementById('ITM_PRICE'+theRow).value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		document.getElementById('ITM_TOTAL'+theRow).value 		= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('IR_AMOUNTNETX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function changeValueQtyBonus(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;	
		var ITM_QTY_BNS	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('ITM_QTY_BONUS'+theRow).value 	= parseFloat(Math.abs(ITM_QTY_BNS));
		document.getElementById('ITM_QTY_BONUSX'+theRow).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY_BNS)),decFormat));
	}

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];
		
		PO_NUM		= arrItem[0];

		document.getElementById("PO_NUMX").value = PO_NUM;
		IR_NUM 		= document.getElementById("IR_NUM").value;
		IR_CODE 	= document.getElementById("IR_CODE").value;

		collDt 		= IR_NUM+'~'+IR_CODE+'~'+PO_NUM;

		var IR_DATE 	= $('#datepicker1').val();
		var IR_DATE1 	= IR_DATE.split('/');
		var IR_DATED 	= IR_DATE1[0];
		var IR_DATEM 	= IR_DATE1[1];
		var IR_DATEY 	= IR_DATE1[2];
		var IR_DATE		= IR_DATEY + '-' + IR_DATEM + '-' + IR_DATED;

		//document.getElementById("PO_NUM1").value = PO_NUM;
		//document.frmsrch.submitSrch.click();

		url 		= "<?=$getPO?>";								// PENAMBAHAN DETAIL PENERIMAAN SETELAH MEIMILIH NOMOR PO
		$.ajax({
            type: 'POST',
            url: url,
            data: {collDt: collDt},
            success: function(response)
            {
            	var PODET 	= response.split("~");
            	PO_CODE 	= PODET[0];
            	PR_NUM 		= PODET[1];
            	SPLCODE 	= PODET[2];
            	TERM_PAY 	= PODET[3];
				PO_PLANIR 	= PODET[4];
				TOTALROW 	= PODET[5];
				console.log(TOTALROW);

				document.getElementById('datepicker2').disabled = false;

				let currDate 		= new Date().toJSON().slice(0, 10);

				console.log(currDate+' > '+PO_PLANIR);
				if(currDate > PO_PLANIR)
				{
					// document.getElementById("divAlertPIR").style.display 	= '';
					// document.getElementById('btnSave').disabled 			= true;

					var datePIR		= new Date(PO_PLANIR);
					var PO_PIRD 	= ("0" + (datePIR.getDate() + 1)).slice(-2); // Penerimaan material max. 1x24 jam => upd. 2023-05-31
					var PO_PIRM 	= ("0" + (datePIR.getMonth() + 1)).slice(-2);
					var PO_PIRY 	= datePIR.getFullYear();
					var datePIR		= PO_PIRY + '-' + PO_PIRM + '-' + PO_PIRD; 

					var dateIR		= new Date(IR_DATE);
					var IRDD 		= ("0" + (dateIR.getDate())).slice(-2);
					var IRDM 		= ("0" + (dateIR.getMonth() + 1)).slice(-2);
					var IRDY 		= dateIR.getFullYear();
					var dateIR		= IRDY + '-' + IRDM + '-' + IRDD;

					console.log(dateIR+' > '+datePIR);
					if(dateIR > datePIR)
					{
						swal("Tanggal input sudah melebihi tanggal rencana terima di PO. Max. tanggal input 1x24 jam dari tanggal terakhir rencana terima di PO.",
						{
							icon: "warning",
						})
						.then(function()
						{
							swal.close();
							document.getElementById("divAlertPIR").style.display 	= '';
							document.getElementById('btnSave').disabled 			= true;
						});
					}
					else
					{
						document.getElementById("divAlertPIR").style.display 	= 'none';
						document.getElementById('btnSave').disabled 			= false;
					}
				}
				else
				{
					document.getElementById("divAlertPIR").style.display 	= 'none';
					document.getElementById('btnSave').disabled 			= false;
				}

				var datePIR		= new Date(PO_PLANIR);
				var dd 			= ("0" + (datePIR.getDate())).slice(-2);
				var mm 			= ("0" + (datePIR.getMonth() + 1)).slice(-2);
				var yyyy 		= datePIR.getFullYear();
				var PO_PLANIR	= dd + '/' + mm + '/' + yyyy;
				
				if(TOTALROW == 0)
				{
					swal("<?=$alert6?>",
					{
						icon:"warning"
					});
					return false;
				}
				else
				{
					document.getElementById('totalrow').value 	= TOTALROW;
	            	document.getElementById("IR_REFER").value 	= PR_NUM;
	            	document.getElementById("PO_NUM").value 	= PO_NUM;
	            	document.getElementById("PO_NUMX").value 	= PO_NUM;
	            	document.getElementById("PO_CODE").value 	= PO_CODE;
	            	document.getElementById("PO_NUM1").value 	= PO_CODE;
	            	document.getElementById("PO_NUM2").value 	= PO_CODE;
	            	document.getElementById("SPLCODE").value 	= SPLCODE;
	            	document.getElementById("datepicker2").value= PO_PLANIR;

	            	$("#SPLCODEX").val(SPLCODE).trigger('change');
	            	$("#TERM_PAYX").val(TERM_PAY).trigger('change');
					$('#TERM_PAY').val(TERM_PAY);

	            	refrDetilPO();
	            }
            }
        });
	}

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var IR_NUM 	= "<?php echo $IR_NUM; ?>";
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0], PRJCODE)
		if(validateDouble(arrItem[0], PRJCODE))
		{
			swal("Double Item for " + arrItem[0]);
			return false;
		}*/
		
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
		objTD.innerHTML = ''+intIndex+'<input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onClick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value=""><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_CODE" name="data['+intIndex+'][IR_CODE]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';	
		
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
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTYX'+intIndex+'" id="ITM_QTYX'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="ITM_QTY'+intIndex+'" size="10" value="'+itemQty+'" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price

		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICEX'+intIndex+'" id="ITM_PRICEX'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" size="10" value="'+itemPrice+'" >';
		
		// Item Price Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="IR_AMOUNTNETX'+intIndex+'" id="IR_AMOUNTNETX'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" size="10" value="'+ITM_TOTAL+'" >';
		
		// Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="TAXCODE1'+intIndex+'" class="form-control" style="max-width:150px" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:450px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="TAXPRICE1'+intIndex+'" value=""><input type="hidden" style="text-align:right" name="ITM_TOTAL_NETT'+intIndex+'" id="ITM_TOTAL_NETT'+intIndex+'" value="">';
		
		var decFormat											= document.getElementById('decFormat').value;
		var ITM_QTY												= document.getElementById('ITM_QTY'+intIndex).value
		document.getElementById('ITM_QTY'+intIndex).value 		= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTYX'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE											= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('ITM_PRICE'+intIndex).value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICEX'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL											= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('ITM_TOTAL'+intIndex).value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('IR_AMOUNTNETX'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
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
	
	function deleteRow(row)
	{
		/*var row = document.getElementById("tr_" + btn);
		row.remove();*/
		var collID	= document.getElementById('urlDel'+row).value;
        var myarr 	= collID.split("~");

        var url 	= myarr[0];

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	/*swal(response, 
				{
					icon: "success",
				})
                .then(function()
                {*/
	                collDt 		= "<?=$collDt?>";
	        		PO_NUM 		= $("#PO_NUMX").val();

			    	$('#tbl').DataTable(
			    	{
			    		//"scrollY": "200px", "paging": false, "scrollCollapse": true,
						"scrollY": "200px", "scrollCollapse": true,
			    		"bDestroy": true,
				        "processing": true,
				        "serverSide": true,
						//"scrollX": false,
						"autoWidth": true,
						"filter": true,
				        "ajax": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataIRDetAftDel/?id='.$PRJCODE.'&collDt='.$collDt.'&PO_NUM=')?>"+PO_NUM,
				        "type": "POST",
						//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
						"lengthMenu": [[50, 100, 200], [50, 100, 200]],
						"columnDefs": [	{ targets: [0,4,5,6,7,8,9], className: 'dt-body-center' },
										{ targets: [3], className: 'dt-body-right' },
									  ],
						"order": [[ 2, "desc" ]],
						"language": {
				            "infoFiltered":"",
				            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
				        },
					});

					var url 		= "<?php echo $getTRWAll; ?>";
					var IR_NUM 		= "<?=$IR_NUM?>";

					$.ajax({
			            type: 'POST',
			            url: url,
			            data: {IR_NUM: IR_NUM},
			            success: function(tRow)
			            {
			            	document.getElementById('totalrow').value = tRow;
			            }
			        });
                /*})*/
            }
        });
	}

	function copyRow(row) 
	{
		var collID	= document.getElementById('urlCopy'+row).value;
        var myarr 	= collID.split("~");

        var url 	= myarr[0];

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
                /*collDt 		= "<?=$collDt?>";
        		PO_NUM 		= $("#PO_NUMX").val();*/
        		collDt 		= "<?=$collDt?>";
        		PO_NUM 		= $("#PO_NUMX").val();
        		collDt 		= "<?=$collDt?>"+"~"+PO_NUM;

		    	$('#tbl').DataTable(
		    	{
		    		//"scrollY": "200px", "paging": false, "scrollCollapse": true,
					//"scrollY": "200px", "scrollCollapse": true,
		    		"bDestroy": true,
			        "processing": true,
			        "serverSide": true,
					//"scrollX": false,
					"autoWidth": true,
					"filter": true,
			        "ajax": {
					        "url": "<?php echo site_url('c_inventory/c_ir180c15/get_AllDataIRDetAftCpy/?id='.$PRJCODE.'&task='.$task.'&collDt=')?>"+collDt,
					        "type": "POST",
					        "complete": function()
					        {
					        	let totalrow = document.getElementById('totalrow').value;
					        	for(let i = 1; i <= totalrow; i++) {
						        	thisVal = document.getElementById('ITM_QTYX'+i);
									changeValue(thisVal, i)
					        	}
					        }
				        },
			        "type": "POST",
					//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
					"lengthMenu": [[50, 100, 200], [50, 100, 200]],
					"columnDefs": [	{ targets: [0,4,5,6,7,8,9], className: 'dt-body-center' },
									{ targets: [3], className: 'dt-body-right' },
								  ],
					"order": [[ 2, "desc" ]],
					"language": {
			            "infoFiltered":"",
			            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
			        },
				});

				var url 		= "<?php echo $getTRWAll; ?>";
				var IR_NUM 		= "<?=$IR_NUM?>";

				$.ajax({
		            type: 'POST',
		            url: url,
		            data: {IR_NUM: IR_NUM},
		            success: function(tRow)
		            {
		            	document.getElementById('totalrow').value = tRow;
		            }
		        });
            }
        });
	}
	
	function checkForm()
	{
		totalrow	= document.getElementById("totalrow").value;
		PO_NUM1		= document.getElementById("PO_NUM1").value;
		IR_STAT		= document.getElementById("IR_STAT").value;
		WH_CODE		= document.getElementById("WH_CODE").value;
		
		if(PO_NUM1 == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			document.getElementById("PO_NUM1").focus();
			return false;
		}
		
		if(WH_CODE == '')
		{
			swal('<?php echo $alert5; ?>',
			{
				icon: "warning",
			});
			document.getElementById("WH_CODE").focus();
			return false;
		}
		
		if(IR_STAT == 5 || IR_STAT == 6 || IR_STAT == 9)
		{
			IR_NOTE2		= document.getElementById("IR_NOTE2").value;
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
				return false;
			}
		}
		
		if(totalrow == 0)
		{
			swal("<?php echo $alert3; ?>",
			{
				icon: "warning",
			});
			return false;
		}
		
		var TOTQTY	= 0;
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('ITM_QTY'+i);
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				SJ_NUM	= document.getElementById('data'+i+'SJ_NUM').value;
				ITM_QTY	= document.getElementById('ITM_QTY'+i).value;
				ITM_NM	= document.getElementById('itemname'+i).value;
				if(ITM_QTY == 0)
				{
					//swal('Item '+ ITM_NM +' qty can not be empty.');
					//document.getElementById('ITM_QTYX'+i).focus();
					//return false;
				}
				if(SJ_NUM == '')
				{
					swal("Nomor gudang / SJ penerimaan "+ITM_NM+" tidak boleh kosong. ",
					{
						icon:"warning"
					})
					.then(function()
					{
						swal.close()
						document.getElementById('data'+i+'SJ_NUM').focus();
					});
					return false;
				}
				TOTQTY	= parseFloat(TOTQTY) + parseFloat(ITM_QTY);
			}
		}
		
		if(TOTQTY == 0)
		{
			swal('qty can not be empty.');
			//document.getElementById('ITM_QTYX'+i).focus();
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