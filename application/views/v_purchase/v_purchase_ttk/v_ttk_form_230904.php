<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 18 April 2018
	* File Name	= v_ttk_form.php
	* Location		= -
*/
setlocale(LC_ALL, 'id-ID', 'id_ID');

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
$TTK_CHECKER = "";
$sqlEmp 	= "SELECT CONCAT(First_Name, ' ', Last_Name) AS compName FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
$resEmp 	= $this->db->query($sqlEmp)->result();
foreach($resEmp as $row) :
	$TTK_CHECKER 	= $row->compName;
endforeach;

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
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

$editable 	= 1;
if($task == 'add')
{
	$TRXTIME		= date('ymdHis');
	$TTK_NUM		= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$TTK_CODE		= $DocNumber;
	
	$TTK_DATEY 		= date('Y');
	$TTK_DATEM		= date('m');
	$TTK_DATED 		= date('d');
	$TTK_DATE 		= date('d/m/Y');
	$Patt_Year 		= date('Y');
	$TTK_DUEDATE	= date('d/m/Y');
	$TTK_ESTDATE	= date('d/m/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$TTK_CATEG		= 'IR';
	$TTK_NOTES		= '';
	$TTK_NOTES1		= '';
	$TTK_STAT		= 1;

	if(isset($_POST['submitSrch1']))
	{
		$mySPLCODE 		= $_POST['SPLCODE1'];
		$TTK_CODE 		= $_POST['TTK_CODEY'];
		$SPLCODE		= $mySPLCODE;
	}
	else
	{
		$mySPLCODE		= '0';
		$SPLCODE		= $mySPLCODE;
	}
	$TTK_AMOUNT			= 0;
	$TTK_AMOUNT_PPNH	= 0;
	$TTK_AMOUNT_PPHH	= 0;
	$TTK_AMOUNT_DPBH	= 0;
	$TTK_AMOUNT_RETH	= 0;
	$TTK_AMOUNT_POTH	= 0;
	$TTK_AMOUNT_OTHH 	= 0;
	$TTK_GTOTAL			= 0;
	$TTK_GTOTALV		= 0;
	$TTK_ACC_OTH		= '';

	$TAXCODE_PPN 		= "";
	$TAXCODE_PPH 		= "";
	$TTK_TOTKWIT 		= 0;
}
else
{
	$isSetDocNo = 1;
	$TTK_NUM 			= $default['TTK_NUM'];
	$TTK_CODE 			= $default['TTK_CODE'];
	$TTK_DATE 			= date('d/m/Y', strtotime($default['TTK_DATE']));
	$TTK_DUEDATE		= date('d/m/Y', strtotime($default['TTK_DUEDATE']));
	$TTK_ESTDATE		= date('d/m/Y', strtotime($default['TTK_ESTDATE']));
	$JournalY 			= date('Y', strtotime($default['TTK_DATE']));
	$JournalM 			= date('n', strtotime($default['TTK_DATE']));
	$TTK_AMOUNT			= $default['TTK_AMOUNT'];
	$TTK_AMOUNT_PPNH	= $default['TTK_AMOUNT_PPN'];
	$TTK_AMOUNT_PPHH	= $default['TTK_AMOUNT_PPH'];
	$TTK_AMOUNT_DPBH	= $default['TTK_AMOUNT_DPB'];
	$TTK_AMOUNT_RETH	= $default['TTK_AMOUNT_RET'];
	$TTK_AMOUNT_POTH	= $default['TTK_AMOUNT_POT'];
	$TTK_AMOUNT_OTHH	= $default['TTK_AMOUNT_OTH'];
	$TTK_GTOTAL			= $default['TTK_GTOTAL'];
	$TTK_ACC_OTH		= $default['TTK_ACC_OTH'];
	$TTK_NOTES 			= $default['TTK_NOTES'];
	$TTK_NOTES1 		= $default['TTK_NOTES1'];
	$TTK_CHECKER		= $default['TTK_CHECKER'];
	$TTK_CATEG			= $default['TTK_CATEG'];
	$PRJCODE 			= $default['PRJCODE'];
	$SPLCODE 			= $default['SPLCODE'];
	$TTK_STAT 			= $default['TTK_STAT'];
	$Patt_Number 		= $default['Patt_Number'];
	$lastPattNumb		= $Patt_Number;
	
	if($TTK_CATEG == 'OPN')
	{
		//$TTK_GTOTALV	= $TTK_AMOUNT + $TTK_AMOUNT_PPN - $TTK_AMOUNT_POT - $TTK_AMOUNT_RET;
		$TTK_GTOTALV	= $TTK_GTOTAL;
	}
	else
	{
		//$TTK_GTOTALV	= $TTK_GTOTAL - $TTK_AMOUNT_POT - $TTK_AMOUNT_RET;
		//$TTK_GTOTALV	= $TTK_GTOTAL + $TTK_AMOUNT_PPN - $TTK_AMOUNT_POT - $TTK_AMOUNT_RET;
		$TTK_GTOTALV	= $TTK_GTOTAL;
	}

	$TTK_TOTKWIT 		= 0;
	$s_TKWIT 			= "SELECT SUM(TTKT_SPLINVVAL) AS TTK_TOTKWIT FROM tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM'";
	$r_TKWIT 			= $this->db->query($s_TKWIT)->result();
	foreach($r_TKWIT as $row) :
		$TTK_TOTKWIT 	= $row->TTK_TOTKWIT;
	endforeach;
}

$s_INVC	= "tbl_pinv_detail WHERE TTK_NUM = '$TTK_NUM' AND INV_STAT NOT IN (5,9)";
$r_INVC	= $this->db->count_all($s_INVC);
if($r_INVC > 0)
	$editable 	= 0;
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
			if($TranslCode == 'Invoice')$Invoice = $LangTransl;
			if($TranslCode == 'TTKNumber')$TTKNumber = $LangTransl;
			if($TranslCode == 'TTKCode')$TTKCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'DueDate')$DueDate = $LangTransl;
			if($TranslCode == 'estDPay')$estDPay = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'OthNotes')$OthNotes = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'BillDate')$BillDate = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Total')$Total = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;				
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'SuplInvNo')$SuplInvNo = $LangTransl;
			if($TranslCode == 'taxNo')$taxNo = $LangTransl;
			if($TranslCode == 'InvList')$InvList = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'TotAmount')$TotAmount = $LangTransl;
			if($TranslCode == 'SelDoc')$SelDoc = $LangTransl;
			if($TranslCode == 'ItemAcceptence')$ItemAcceptence = $LangTransl;
			if($TranslCode == 'OthDisc')$OthDisc = $LangTransl;
			if($TranslCode == 'OthExp')$OthExp = $LangTransl;
			if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
			if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
			if($TranslCode == 'OpnNo')$OpnNo = $LangTransl;
			if($TranslCode == 'Receiver')$Receiver = $LangTransl;
			if($TranslCode == 'OthNotes')$OthNotes = $LangTransl;
			if($TranslCode == 'Reason')$Reason = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'Complete')$Complete = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'SJList')$SJList = $LangTransl;
			if($TranslCode == 'TaxSerList')$TaxSerList = $LangTransl;
			if($TranslCode == 'PPNValue')$PPNValue = $LangTransl;
			if($TranslCode == 'splInvNo')$splInvNo = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Peringatan';
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$h1_title 	= "Tambah";
			$h2_title 	= "Tanda Terima Dokumen";
			$h3_title	= "Penerimaan Barang";
			$isManual	= "Centang untuk kode manual.";
			$alert1		= "Masukan alasan mengapa dokumen ini di-close / void.";
			$alert2		= "Pilih satu atau lebih Dokumen Penerimaan.";
			$alert3		= "Silahkan centang Cek Total.";
			$alert4		= "Silahkan pilih Akun Beban Lainnya.";
			$splInvNo 	= "No. Kwitansi";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert1	= 'Warning';
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$h1_title 	= "Add";
			$h2_title 	= "Document Receipt";
			$h3_title	= "Receiving Goods";
			$isManual	= "Check to manual code.";
			$alert1		= "Input the reason why you close / void this document.";
			$alert2		= "Select one or more IR Document(s).";
			$alert3		= "Please check Total Check.";
			$alert4		= "Please select an Other Exp. Acccount.";
		}
		
		$PRJCODE1	= $PRJCODE;
		$SPLCODE1	= $SPLCODE;
		$TTK_CATEG1	= $TTK_CATEG;
		if(isset($_POST['PRJCODE1']))
		{
			$PRJCODE1	= $_POST['PRJCODE1'];
			$SPLCODE1	= $_POST['SPLCODE1'];
			$TTK_CATEG1	= $_POST['TTK_CATEG1'];
		}
		
		$SPLADD1	= '';
		$sqlSUPL	= "SELECT SPLADD1 FROM tbl_supplier WHERE SPLCODE = '$SPLCODE1' AND SPLSTAT = '1' LIMIT 1";
		$resSUPL	= $this->db->query($sqlSUPL)->result();
		foreach($resSUPL as $rowSUPL):
			$SPLADD1	= $rowSUPL->SPLADD1;
		endforeach;
		
		if(isset($_POST['IR_NUM1']))
		{
			$IR_NUM1	= $_POST['IR_NUM1'];
		}
		
		$sqlPRJ1 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
		$restPRJ1 	= $this->db->query($sqlPRJ1)->result();	
		foreach($restPRJ1 as $rowPRJ1) :
			$PRJNAME1 	= $rowPRJ1->PRJNAME;
		endforeach;

		if($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ')
		{
			$sqlSUPLC	= "tbl_ir_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.IR_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.TTK_CREATED = '0'";
			$countSUPL	= $this->db->count_all($sqlSUPLC);
			
			$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
							FROM tbl_ir_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.IR_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.TTK_CREATED = '0'";
			$vwSUPL	= $this->db->query($sqlSUPL)->result();
		}
		elseif($TTK_CATEG == 'OPN')
		{
			$sqlSUPLC	= "tbl_opn_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNH_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$countSUPL	= $this->db->count_all($sqlSUPLC);
			
			$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
							FROM tbl_opn_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNH_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$vwSUPL	= $this->db->query($sqlSUPL)->result();
		}
		elseif($TTK_CATEG == 'OPN-RET')
		{
			$sqlSUPLC	= "tbl_opn_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNH_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$countSUPL	= $this->db->count_all($sqlSUPLC);
			
			$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
							FROM tbl_opn_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.OPNH_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$vwSUPL	= $this->db->query($sqlSUPL)->result();
		}
		elseif($TTK_CATEG == 'OTH')
		{
			$sqlSUPLC	= "tbl_fpa_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.FPA_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$countSUPL	= $this->db->count_all($sqlSUPLC);
			
			$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
							FROM tbl_fpa_header A
								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
							WHERE A.FPA_STAT = 3
								AND A.PRJCODE = '$PRJCODE'
								AND A.TTK_CREATED = '0'";
			$vwSUPL	= $this->db->query($sqlSUPL)->result();
		}
		
		if(isset($_POST['submitSrch1']))
		{
			$myTTKCATEG		= $_POST['TTK_CATEG1'];
			$TTK_CATEG		= $myTTKCATEG;
			if($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ')
			{
				$sqlSUPLC	= "tbl_ir_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.IR_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.INVSTAT NOT IN ('FI')
									AND A.TTK_CREATED = '0'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_ir_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.IR_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.INVSTAT NOT IN ('FI')
									AND A.TTK_CREATED = '0'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
			elseif($TTK_CATEG == 'OPN')
			{
				$sqlSUPLC	= "tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.TTK_CREATED = '0'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.TTK_CREATED = '0'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
			elseif($TTK_CATEG == 'OPN-RET')
			{
				$sqlSUPLC	= "tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.TTK_CREATED = '0'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.TTK_CREATED = '0'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
			elseif($TTK_CATEG == 'OTH')
			{
				$sqlSUPLC	= "tbl_fpa_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.FPA_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.TTK_CREATED = '0'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_fpa_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.FPA_STAT = 3
									AND A.PRJCODE = '$PRJCODE'
									AND A.TTK_CREATED = '0'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
		}
		
		if($task == 'edit')
		{
			if($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ')
			{
				$sqlSUPLC	= "tbl_ir_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.IR_STAT IN (3,6)
									AND A.PRJCODE = '$PRJCODE'
									AND A.INVSTAT NOT IN ('FI')";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_ir_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.IR_STAT IN (3,6)
									AND A.PRJCODE = '$PRJCODE'
									AND A.INVSTAT NOT IN ('FI')";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
			elseif($TTK_CATEG == 'OPN')
			{
				$sqlSUPLC	= "tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
			elseif($TTK_CATEG == 'OPN-RET')
			{
				$sqlSUPLC	= "tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_opn_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.OPNH_STAT = 3
									AND A.PRJCODE = '$PRJCODE'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
			elseif($TTK_CATEG == 'OTH')
			{
				$sqlSUPLC	= "tbl_fpa_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.FPA_STAT = 3
									AND A.PRJCODE = '$PRJCODE'";
				$countSUPL	= $this->db->count_all($sqlSUPLC);
				
				$sqlSUPL	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
								FROM tbl_fpa_header A
									INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
								WHERE A.FPA_STAT = 3
									AND A.PRJCODE = '$PRJCODE'";
				$vwSUPL	= $this->db->query($sqlSUPL)->result();
			}
		}
		
		if($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ')
			$ReceiptNumber	= $ReceiptNumber;
		elseif($TTK_CATEG == 'OPN')
			$ReceiptNumber	= $Code;	// $OpnNo;
		else
			$ReceiptNumber	= $Code;

		$comp_color = $this->session->userdata('comp_color');
    ?>

	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
	</style>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/ttk.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$mnName ($PRJCODE)"; ?>
			    <small><?php echo "$PRJNAME1 ($PRJCODE)"; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
            	<!-- after get Supplier code -->
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display: none;">
                    <input type="text" name="PRJCODE1" id="PRJCODE1" value="<?php echo $PRJCODE1; ?>" />
                    <input type="text" name="SPLCODE1" id="SPLCODE1" value="<?php echo $SPLCODE1; ?>" />
                    <input type="text" name="TTK_CATEG1" id="TTK_CATEG1" value="<?php echo $TTK_CATEG1; ?>" />
                    <input type="text" name="TTK_CODEY" id="TTK_CODEY" value="<?php echo $TTK_CODE; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
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
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
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
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $TTKNumber; ?></label>
		                          	<div class="col-sm-9">
		                        		<input type="hidden" class="textbox" name="TTK_NUM" id="TTK_NUM" size="30" value="<?php echo $TTK_NUM; ?>" />
		                                <input type="text" class="form-control" style="max-width:195px" name="TTK_NUM1" id="TTK_NUM1" value="<?php echo $TTK_NUM; ?>" readonly >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $TTKCode; ?></label>
		                          	<div class="col-sm-9">
		                          		<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                            		<input type="text" class="form-control" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>" >
		                        		<?php } else { ?>
			                        		<input type="hidden" class="form-control" name="TTK_CODE" id="TTK_CODE" value="<?php echo $TTK_CODE; ?>" >
		                            		<input type="text" class="form-control" name="TTK_CODEX" id="TTK_CODEX" value="<?php echo $TTK_CODE; ?>" disabled >
		                        		<?php } ?>
		                          	</div>
		                          	<div class="col-sm-4" style="display: none;">
		                          		<label for="inputName" class="control-label"><?php echo $estDPay; ?></label>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $TTKCode; ?></label>
		                          	<div class="col-sm-9">
		                                <label>
		                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked="checked">
		                                </label>
		                                <label style="font-style:italic">
		                                    <?php echo $isManual; ?>
		                                </label>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date; ?></label>
		                          	<div class="col-sm-5">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="hidden" name="TTK_DATE" class="form-control pull-left" id="TTK_DATE1" value="<?php echo $TTK_DATE; ?>" style="width:120px">
		                                    <input type="text" name="TTK_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $TTK_DATE; ?>" style="width:120px" disabled>
		                                </div>
		                          	</div>
		                          	<div class="col-sm-4" style="display: none;">
		                          		<div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="text" name="TTK_ESTDATE" class="form-control pull-left" id="datepicker3" value="<?php echo $TTK_ESTDATE; ?>" >
		                                </div>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $DueDate; ?></label>
		                          	<div class="col-sm-5">
		                                <div class="input-group date">
		                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
		                                    <input type="tetx" name="TTK_DUEDATE" class="form-control pull-left" id="datepicker2" value="<?php echo $TTK_DUEDATE; ?>" style="width:120px">
		                                    <input type="hidden" name="TTK_DUEDATEX" class="form-control pull-left" id="datepickerX" value="<?php echo $TTK_DUEDATE; ?>" style="width:120px">
		                                </div>
		                          	</div>
		                          	<div class="col-sm-4">
		                          		<label for="inputName" class="control-label"><?php echo $Receiver; ?></label>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SourceDocument; ?></label>
		                          	<div class="col-sm-9">
		                                <div class="input-group">
		                                    <select name="RRSource" id="RRSource" class="form-control" style="max-width:150px">
		                                        <option value="IR" >Item Receipt</option>
		                                        <option value="OTH" style="display:none">Other</option>    
		                                    </select>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
		                          	<div class="col-sm-5">
		                            	<select name="TTK_CATEG" id="TTK_CATEG" class="form-control select2" onChange="getSUPPLIER(this.value)">
		                                    <option value="IR" <?php if($TTK_CATEG1 == 'IR') { ?> selected <?php } ?>>LPM - A</option>
		                                    <option value="SJ" <?php if($TTK_CATEG1 == 'SJ') { ?> selected <?php } ?>>Surat Jalan</option>
		                                    <option value="OPN" <?php if($TTK_CATEG1 == 'OPN') { ?> selected <?php } ?>>Opname</option>?> - IR</option>
		                                    <option value="OPN-RET" <?php if($TTK_CATEG1 == 'OPN-RET') { ?> selected <?php } ?>>Opname - Retensi</option>
		                                    <!-- <option value="OTH" <?php if($TTK_CATEG1 == 'OTH') { ?> selected <?php } ?>><?php echo $Others; ?> - OTH</option> -->
		                                </select>
		                          	</div>
		                          	<div class="col-sm-4">
		                                <input type="text" class="form-control" name="TTK_CHECKER" id="TTK_CHECKER" value="<?php echo $TTK_CHECKER; ?>" readonly >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $SupplierName; ?></label>
		                          	<div class="col-sm-9">
		                            	<?php if($TTK_STAT != 1) { ?> 
		                                	<input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE1; ?>" />
		                                    <select name="SPLCODE1" id="SPLCODE1" class="form-control select2" onChange="getSUPPLIER(this.value)" disabled>
		                                        <option value="0"> --- </option>
		                                        <?php echo $i = 0;
		                                        if($countSUPL > 0)
		                                        {
		                                            foreach($vwSUPL as $row) :
		                                            ?>
		                                                <option value="<?php echo $row->SPLCODE; ?>" <?php if($SPLCODE1 == $row->SPLCODE) { ?> selected <?php } ?>>
		                                                    <?php echo $row->SPLDESC; ?>
		                                                </option>
		                                            <?php
		                                            endforeach;
		                                        }
		                                        ?>
		                                    </select>
										<?php } else { ?>
		                                    <select name="SPLCODE" id="SPLCODE" class="form-control select2" onChange="getSUPPLIER(this.value)">
		                                        <option value="0"> --- </option>
		                                        <?php echo $i = 0;
		                                        if($countSUPL > 0)
		                                        {
		                                            foreach($vwSUPL as $row) :
		                                            ?>
		                                                <option value="<?php echo $row->SPLCODE; ?>" <?php if($SPLCODE1 == $row->SPLCODE) { ?> selected <?php } ?>>
		                                                    <?php echo $row->SPLDESC; ?>
		                                                </option>
		                                            <?php
		                                            endforeach;
		                                        }
		                                        ?>
		                                    </select>
		                            	<?php } ?>
		                          	</div>
		                        </div>
		                        <script>
									function getSUPPLIER(SPLCODE) 
									{
										TTK_CATEG	= document.getElementById("TTK_CATEG").value
										document.getElementById("TTK_CATEG1").value = TTK_CATEG;
										SPLCODE	= document.getElementById("SPLCODE").value
										document.getElementById("SPLCODE1").value = SPLCODE;
										PRJCODE	= document.getElementById("PRJCODE").value
										document.getElementById("PRJCODE1").value = PRJCODE;
										document.frmsrch1.submitSrch1.click();
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="max-width:195px" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" >
		                            	<select name="selPRJCODE" id="selPRJCODE" class="form-control" <?php if($TTK_STAT != 1) { ?> disabled <?php } ?>>
		                                <?php
		                                    $sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		                                    $resultPRJ 	= $this->db->query($sqlPRJ)->result();
											
											foreach($resultPRJ as $rowPRJ) :
												$PRJCODE1 	= $rowPRJ->PRJCODE;
												$PRJNAME1 	= $rowPRJ->PRJNAME;
												?>
													<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?>selected <?php } ?>>
														<?php echo $PRJNAME1; ?>
													</option>
												<?php
											 endforeach;
												 
		                                ?>
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
		                            <label for="inputName" class="col-sm-3 control-label">Cek Total / PPn</label>
		                            <div class="col-sm-4">
		                                <div class="input-group">
		                                    <span class="input-group-addon">
		                                      <input type="checkbox" name="chkTotal" id="chkTotal" onClick="checkTotalTTK()">
		                                    </span>
		                                    <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT" id="TTK_AMOUNT" value="<?php echo $TTK_AMOUNT; ?>" >
			                                <?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
			                               		<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNTX" id="TTK_AMOUNTX" value="<?php echo number_format($TTK_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" >
			                                <?php } else { ?>
			                                	<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNTX" id="TTK_AMOUNTX" value="<?php echo number_format($TTK_AMOUNT, 2); ?>" onBlur="getAmount(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
			                                <?php } ?>
		                        		</div>
		                            </div>
		                            <label for="inputName" class="col-sm-2 control-label">PPn</label>
		                            <div class="col-sm-3">
		                                <?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                               		<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPNX" id="TTK_AMOUNT_PPNX" value="<?php echo number_format($TTK_AMOUNT_PPNH, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" readonly>
		                               		<input type="hidden" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPN" id="TTK_AMOUNT_PPN" value="<?php echo number_format($TTK_AMOUNT_PPNH, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);">
		                                <?php } else { ?>
		                                	<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPNX" id="TTK_AMOUNT_PPNX" value="<?php echo number_format($TTK_AMOUNT_PPNH, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
		                               		<input type="hidden" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPN" id="TTK_AMOUNT_PPN" value="<?php echo number_format($TTK_AMOUNT_PPNH, 2); ?>" onBlur="getAmountPPn(this)" onKeyPress="return isIntOnlyNew(event);">
		                                <?php } ?>
		                            </div>
		                        </div>
								<?php
		                            $sqlC0a		= "tbl_chartaccount WHERE Account_Category IN (5,6,7,8) AND PRJCODE = '$PRJCODE'";
		                            $resC0a 	= $this->db->count_all($sqlC0a);
		                            
		                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
														Acc_DirParent, isLast
		                                            FROM tbl_chartaccount WHERE Account_Category IN (5,6,7,8) AND PRJCODE = '$PRJCODE' ORDER BY Account_Number";
		                            $resC0b 	= $this->db->query($sqlC0b)->result();
		                        ?>
		                        <div class="form-group" style="display: none;">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $OthExp; ?></label>
		                            <div class="col-sm-9">
		                            	<div class="row">
		                                    <div class="col-xs-4">
		                                    	<input type="hidden" class="form-control" style="text-align:right" name="TTK_AMOUNT_OTH" id="TTK_AMOUNT_OTH" value="<?php echo $TTK_AMOUNT_OTHH; ?>">
		                                		<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_OTHX" id="TTK_AMOUNT_OTHX" value="<?php echo number_format($TTK_AMOUNT_OTHH, 2); ?>" onBlur="getAmountOthEXP()" onKeyPress="return isIntOnlyNew(event);" readonly>
		                                    </div>
		                                    <div class="col-xs-8">
		                                        <select name="TTK_ACC_OTH" id="TTK_ACC_OTH" class="form-control select2" disabled>
				                        			<option value=""> --- </option>
				                                    <?php
													if($resC0a>0)
													{
														foreach($resC0b as $rowC0b) :
															$Acc_ID0		= $rowC0b->Acc_ID;
															$Account_Number0= $rowC0b->Account_Number;
															$Acc_DirParent0	= $rowC0b->Acc_DirParent;
															$Account_Level0	= $rowC0b->Account_Level;
															if($LangID == 'IND')
															{
																$Account_Name0	= $rowC0b->Account_NameId;
															}
															else
															{
																$Account_Name0	= $rowC0b->Account_NameEn;
															}
															
															$Acc_ParentList0	= $rowC0b->Acc_ParentList;
															$isLast_0			= $rowC0b->isLast;
															$disbaled_0			= 0;
															if($isLast_0 == 0)
																$disbaled_0		= 1;
																
															if($Account_Level0 == 0)
																$level_coa1			= "";
															elseif($Account_Level0 == 1)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 2)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 3)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 4)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 5)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 6)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															elseif($Account_Level0 == 7)
																$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
															
															$collData0	= "$Account_Number0";
															?>
															<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $TTK_ACC_OTH) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
															<?php
														endforeach;
													}
													?>
		                                        </select>
		                                    </div>
		                            	</div>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">Pot. DP</label>
		                            <div class="col-sm-4">
		                                <?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                                	<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_DPBX" id="TTK_AMOUNT_DPBX" value="<?php echo number_format($TTK_AMOUNT_DPBH, 2); ?>" onBlur="getAmountPotDP(this)" onKeyPress="return isIntOnlyNew(event);" >
		                                	<input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_DPB" id="TTK_AMOUNT_DPB" value="<?php echo $TTK_AMOUNT_DPBH; ?>" >
		                                <?php } else { ?>
		                                	<input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_DPBX" id="TTK_AMOUNT_DPBX" value="<?php echo number_format($TTK_AMOUNT_DPBH, 2); ?>" readonly>
		                                	<input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_DPB" id="TTK_AMOUNT_DPB" value="<?php echo $TTK_AMOUNT_DPBH; ?>" >
		                                <?php } ?>
		                            </div>
		                            <label for="inputName" class="col-sm-2 control-label">Retensi</label>
		                            <div class="col-sm-3">
		                               	<input type="hidden" class="form-control" style="text-align:right" name="TTK_AMOUNT_POTX" id="TTK_AMOUNT_POTX" value="<?php echo number_format($TTK_AMOUNT_POTH, 2); ?>" onBlur="getAmountPot(this)"  onKeyPress="return isIntOnlyNew(event);" title="PPh, Pengemb. DP, Retensi, Potongan" readonly>
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_POT" id="TTK_AMOUNT_POT" value="<?php echo $TTK_AMOUNT_POTH; ?>" >

		                                <input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_RETX" id="TTK_AMOUNT_RETX" value="<?php echo number_format($TTK_AMOUNT_RETH, 2); ?>" readonly>
		                                <input type="hidden" class="form-control" style="text-align:right" name="TTK_AMOUNT_RET" id="TTK_AMOUNT_RET" value="<?php echo $TTK_AMOUNT_RETH; ?>" >
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">PPh</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" style="text-align:right" name="TTK_AMOUNT_PPHX" id="TTK_AMOUNT_PPHX" value="<?php echo number_format($TTK_AMOUNT_PPHH, 2); ?>" onBlur="getAmountPPh(this)">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_AMOUNT_PPH" id="TTK_AMOUNT_PPH" value="<?php echo $TTK_AMOUNT_PPHH; ?>" >
		                            </div>
		                            <label for="inputName" class="col-sm-2 control-label">G.Total</label>
		                            <div class="col-sm-3">
		                                <input type="hidden" class="form-control" style="max-width:120px; text-align:right" name="TTK_GTOTAL" id="TTK_GTOTAL" value="<?php echo $TTK_GTOTAL; ?>" >
		                                <input type="text" class="form-control" style="text-align:right; background-color: rgb(225, 193, 110); font-weight: bold;" name="TTK_GTOTALX" id="TTK_GTOTALX" value="<?php echo number_format($TTK_GTOTALV, 2); ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
		                            </div>
		                        </div>
		                        <script type="text/javascript">
		                        	function getAmount(thisVal)
		                        	{
		                        		var TTK_AMOUNT 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT').value 	= TTK_AMOUNT;
		                        		document.getElementById('TTK_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT)),2));

		                        		var TTK_TOTAL 	= parseFloat(TTK_AMOUNT);
		                        		var TTK_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;
		                        		var TTK_PPH 	= document.getElementById('TTK_AMOUNT_PPH').value;
		                        		var TTK_POTDP 	= document.getElementById('TTK_AMOUNT_DPB').value;
		                        		var TTK_POT 	= document.getElementById('TTK_AMOUNT_POT').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POTDP) - parseFloat(TTK_POT) - parseFloat(TTK_PPH);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}

		                        	function getAmountPPn(thisVal)
		                        	{
		                        		var TTK_PPN 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_PPN;
		                        		document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_PPN)),2));

		                        		var TTK_TOTAL 	= document.getElementById('TTK_AMOUNT').value;
		                        		var TTK_PPH 	= document.getElementById('TTK_AMOUNT_PPH').value;
		                        		var TTK_POTDP 	= document.getElementById('TTK_AMOUNT_DPB').value;
		                        		var TTK_POT 	= document.getElementById('TTK_AMOUNT_POT').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POTDP) - parseFloat(TTK_POT) - parseFloat(TTK_PPH);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}

		                        	function getAmountPPh(thisVal)
		                        	{
		                        		var TTK_PPH 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT_PPH').value 	= TTK_PPH;
		                        		document.getElementById('TTK_AMOUNT_PPHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_PPH)),2));

		                        		var TTK_TOTAL 	= document.getElementById('TTK_AMOUNT').value;
		                        		var TTK_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;
		                        		var TTK_POTDP 	= document.getElementById('TTK_AMOUNT_DPB').value;
		                        		var TTK_POT 	= document.getElementById('TTK_AMOUNT_POT').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POTDP) - parseFloat(TTK_POT) - parseFloat(TTK_PPH);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}


		                        	function getAmountPotDP(thisVal)
		                        	{
		                        		var TTK_POTDP 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT_DPB').value 	= TTK_POTDP;
		                        		document.getElementById('TTK_AMOUNT_DPBX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_POTDP)),2));

		                        		var TTK_TOTAL 	= document.getElementById('TTK_AMOUNT').value;
		                        		var TTK_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;
		                        		var TTK_PPH 	= document.getElementById('TTK_AMOUNT_PPH').value;
		                        		var TTK_POT 	= document.getElementById('TTK_AMOUNT_POT').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POTDP) - parseFloat(TTK_POT) - parseFloat(TTK_PPH);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}

		                        	function getAmountPot(thisVal)
		                        	{
		                        		var TTK_POT 	= eval(thisVal).value.split(",").join("");
		                        		document.getElementById('TTK_AMOUNT_POT').value 	= TTK_POT;
		                        		document.getElementById('TTK_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_POT)),2));

		                        		var TTK_TOTAL 	= document.getElementById('TTK_AMOUNT').value;
		                        		var TTK_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;
		                        		var TTK_PPH 	= document.getElementById('TTK_AMOUNT_PPH').value;
		                        		var TTK_POTDP 	= document.getElementById('TTK_AMOUNT_DPB').value;

		                        		var TTK_GTOTAL 	= parseFloat(TTK_TOTAL) + parseFloat(TTK_PPN) - parseFloat(TTK_POTDP) - parseFloat(TTK_POT) - parseFloat(TTK_PPH);
		                        		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
										document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		                        	}
		                        </script>
		                        <div class="form-group">
		                       	  	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?></label>
		                          	<div class="col-sm-6">
		                                <?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
		                               		<textarea class="form-control" name="TTK_NOTES"  id="TTK_NOTES" style="height:80px"><?php echo $TTK_NOTES; ?></textarea>
		                                <?php } else { ?>
		                                	<textarea class="form-control" name="TTK_NOTES"  id="TTK_NOTES" style="height:80px" readonly><?php echo $TTK_NOTES; ?></textarea>
		                                <?php } ?>
		                          	</div>
		                          	<div class="col-sm-3">
		                       	  		<label class="pull-left" style="padding-left: 0;">Tot. Kwitansi</label>
		                                <input type="text" class="form-control" style="text-align:right" name="TTK_TOTKWIT" id="TTK_TOTKWIT" value="<?php echo number_format($TTK_TOTKWIT, 2); ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
		                          	</div>
		                        </div>
		                        <div class="form-group" id="voidNote" style="display: none;">
		                       	  <label for="inputName" class="col-sm-3 control-label"><?php echo $OthNotes; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="TTK_NOTES1"  id="TTK_NOTES1" style="height:60px"><?php echo $TTK_NOTES1; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Status; ?></label>
		                            <div class="col-sm-6">
		                                <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $TTK_STAT; ?>">
										<?php
		                                    // START : FOR ALL APPROVAL FUNCTION
												$isDisabled	= 0;
												if($ISAPPROVE == 1)
												{
													if(($TTK_STAT == 3 || $TTK_STAT == 6 || $TTK_STAT == 7) && ($task == "add"))
													{
														$isDisabled	= 1;
													}
													if($task == "add")
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
																<option value="1">New</option>
																<option value="3">Complete</option>
															</select>
														<?php
													}
													else
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($TTK_STAT == 1) { ?> selected <?php } ?> >New</option>
																<option value="3"<?php if($TTK_STAT == 3) { ?> selected <?php } ?> >Complete</option>
																<?php if($TTK_STAT == 1 || $TTK_STAT == 2) { ?>
																	<option value="5"<?php if($TTK_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
																<?php } ?>
																<?php if($editable == 1) { ?>
																	<option value="4"<?php if($TTK_STAT == 4) { ?> selected <?php } ?> >Revisi</option>
																	<option value="9"<?php if($TTK_STAT == 9) { ?> selected <?php } ?> >Void</option>
																<?php } ?>
															</select>
														<?php
													}
												}                            
												elseif($ISCREATE == 1)
												{
													if($TTK_STAT == 6 || $TTK_STAT == 7)
													{
														$isDisabled	= 1;
													}
													if($task == "add")
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
																<option value="1">New</option>
																<option value="3">Complete</option>
															</select>
														<?php
													}
													else
													{
														?>
															<select name="TTK_STAT" id="TTK_STAT" class="form-control select2" onChange="selStat(this.value)" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
																<option value="1"<?php if($TTK_STAT == 1) { ?> selected <?php } ?>>New</option>
																<!-- <option value="2"<?php if($TTK_STAT == 2) { ?> selected <?php } ?>>Confirm</option> -->
																<option value="3"<?php if($TTK_STAT == 3) { ?> selected <?php } ?> disabled>Complete</option>
																<!-- <option value="4"<?php if($TTK_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
																<option value="5"<?php if($TTK_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option> -->
																<option value="6"<?php if($TTK_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
																<!-- <option value="7"<?php if($TTK_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option> -->
																<?php if($TTK_STAT == 3 || $TTK_STAT == 9) { ?>
																<option value="9"<?php if($TTK_STAT == 9) { ?> selected <?php } ?>>Void</option>
																<?php } ?>
															</select>
														<?php
													}
												}
											// END : FOR ALL APPROVAL FUNCTION
		                                ?>
		                            </div>
			                        <?php
										$theProjCode 	= "$PRJCODE~$SPLCODE1~$TTK_CATEG1";
										$url_SelectINV	= site_url('c_purchase/c_pi180c23/pall180dIR/?id='.$this->url_encryption_helper->encode_url($theProjCode));
										
										if($SPLCODE1 != '0')
										{
											?>
												<div class="col-sm-3">
													<script>
														var url = "<?php echo $url_SelectINV;?>";
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
													<button class="btn btn-warning" type="button" onClick="selectitem();" style="display: none;">
														<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelDoc; ?>
													</button>
													<a class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#mdl_addItm" id="btnModal" >
						                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelDoc; ?>
						                        	</a>
												</div>
											<?php
										}
									?>
		                        </div>
				                <script>
									function selStat(thisValue)
									{
										STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
										if(STAT_BEFORE == 2 || STAT_BEFORE == 3)
										{
											if(thisValue == 3 || thisValue == 4)
											{
												document.getElementById('tblClose').style.display = '';
											}
											else if(thisValue == 5 || thisValue == 9)
											{
												document.getElementById('voidNote').style.display = '';
												document.getElementById('tblClose').style.display = '';
											}
											else
											{
												document.getElementById('tblClose').style.display = 'none';
											}
										}
									}
								</script>
							</div>
						</div>
					</div>

                    <div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<i class="fa fa-file-text"></i>
								<h3 class="box-title">Daftar LPM/Opname</h3>

					          	<div class="box-tools pull-right">
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
		                        <div class="search-table-outter">
		                            <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="2%" height="25" style="text-align:center">&nbsp;</th>
	                                        <th width="12%" style="text-align:center" nowrap>No. LPM/Opname</th>
	                                        <th width="5%" style="text-align:center" nowrap><?php echo $Date; ?></th>
	                                        <th width="8%" style="text-align:center; display: none;" nowrap><?php echo $SuplInvNo; ?></th>
	                                        <th width="8%" style="text-align:center; display: none;" nowrap><?php echo $taxNo; ?></th>
	                                        <th width="30%" style="text-align:center" nowrap><?php echo $Description; ?></th>
	                                        <th width="4%" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></th>
	                                        <th width="3%" style="text-align:center" nowrap><?php echo $Discount; ?></th>
	                                        <th width="5%" style="text-align:center" nowrap>PPn</th>
	                                        <th width="5%" style="text-align:center" nowrap>Pot. UM</th>
	                                        <th width="5%" style="text-align:center" nowrap>Retensi</th>
	                                        <th width="5%" style="text-align:center" nowrap>PPh</th>
	                                        <th width="10%" style="text-align:center" nowrap><?php echo $TotAmount; ?></th>
	                                    </tr>
										<?php
										$TOT_AMOUNT2	= 0;
	                                    if($task == 'edit')
	                                    {
	                                        // count data
	                                        $sqlIRc		= "tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM' AND TTK_ISCOST = 0";
	                                        $resIRc 	= $this->db->count_all($sqlIRc);
	                                        // End count data
	                                        
	                                        // 1. Ambil detail IR
	                                        $sqlIR			= "SELECT A.* FROM tbl_ttk_detail A
																	INNER JOIN tbl_ttk_header B ON B.TTK_NUM = A.TTK_NUM
																		AND B.PRJCODE = '$PRJCODE'
																WHERE A.TTK_NUM = '$TTK_NUM' AND A.PRJCODE = '$PRJCODE' AND TTK_ISCOST = 0";
	                                        $resIRc 		= $this->db->query($sqlIR)->result();
	                                        $i				= 0;
											$TAXCODE_PPN 	= "";
											$TAXCODE_PPH	= "";
	                                        if($resIRc > 0)
	                                        {
	                                            foreach($resIRc as $row) :
	                                                $currentRow  	= ++$i;
	                                                $TTK_REF1_NUM 	= $row->TTK_REF1_NUM;
	                                                $TTK_REF1_CODE 	= $row->TTK_REF1_CODE;
	                                                $TTK_REF1_DATE	= $row->TTK_REF1_DATE;
													$TTK_REF1_DATED	= $row->TTK_REF1_DATED;
	                                                $TTK_REF1_AM	= $row->TTK_REF1_AM;
	                                                $TTK_REF1_PPN	= $row->TTK_REF1_PPN;
	                                                $TTK_REF1_PPH	= $row->TTK_REF1_PPH;
	                                                $TTK_REF1_DPB	= $row->TTK_REF1_DPB;
	                                                $TTK_REF1_RET	= $row->TTK_REF1_RET;
	                                                $TTK_REF1_POT	= $row->TTK_REF1_POT;
	                                                $TTK_REF1_GTOT	= $row->TTK_REF1_GTOT; 
	                                                $TTK_REF1_SPLINV= $row->TTK_REF1_SPLINV;
	                                                $TTK_REF1_PPNNO = $row->TTK_REF1_PPNNO;
	                                                $TTK_REF2_NUM 	= $row->TTK_REF2_NUM;
	                                                $TTK_REF2_CODE 	= $row->TTK_REF2_CODE;
	                                                $TTK_REF2_DATE	= $row->TTK_REF2_DATE;
	                                                $TTK_DESC 		= $row->TTK_DESC;

	                                                if($TTK_CATEG == 'OPN-RET')
													{
														$RET_PPHP 		= 0;
														$s_RETPPP		= "SELECT OPNH_AMOUNTPPHP FROM tbl_opn_header WHERE OPNH_NUM = '$TTK_REF1_NUM' AND OPNH_TYPE = 0";
														$r_RETPPP		= $this->db->query($s_RETPPP)->result();
														foreach($r_RETPPP as $rw_RETPPP) :
															$RET_PPHP	= $rw_RETPPP->OPNH_AMOUNTPPHP;
														endforeach;
														$TTK_REF1_PPH1 	= $RET_PPHP * $TTK_REF1_AM / 100;
													}

	                                                $TAXCODE_PPN 	= $row->TAXCODE_PPN;
	                                                $TAXCODE_PPH 	= $row->TAXCODE_PPH;

													if($TAXCODE_PPN != '')
														$TAXCODE_PPN	= $TAXCODE_PPN;

													if($TAXCODE_PPH != '')
														$TAXCODE_PPH	= $TAXCODE_PPH;
													
													$TTK_REF_CODE	= '';
													$PO_CODE		= '';
													$PO_NUM			= $TTK_REF2_NUM;
													if($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ' AND $PO_NUM != '')
													{
														$PO_CODE	= '';
														$sqlPOCODE	= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' LIMIT 1";
														$resPOCODE	= $this->db->query($sqlPOCODE)->result();
														foreach($resPOCODE as $rowPOCODE) :
															$PO_CODE	= $rowPOCODE->PO_CODE;
														endforeach;
														
														$TTK_REF_CODE1	= '';
														$sqlREF1	= "SELECT IR_CODE AS TTK_REF_CODE1 FROM tbl_ir_header 
																		WHERE IR_NUM = '$TTK_REF1_NUM' LIMIT 1";
														$resREF1	= $this->db->query($sqlREF1)->result();
														foreach($resREF1 as $rowREF1) :
															$TTK_REF_CODE1	= $rowREF1->TTK_REF_CODE1;
														endforeach;
														
														$TTK_REF_CODE2	= '';
														$sqlREF2	= "SELECT PO_CODE FROM tbl_po_header
																		WHERE PO_NUM = '$PO_NUM' LIMIT 1";
														$resREF2	= $this->db->query($sqlREF2)->result();
														foreach($resREF2 as $rowREF2) :
															$TTK_REF_CODE2	= $rowREF2->PO_CODE;
														endforeach;
													}
													elseif($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ' AND $PO_NUM == '')
													{
														$PO_CODE	= '';
														$sqlPOCODE	= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' LIMIT 1";
														$resPOCODE	= $this->db->query($sqlPOCODE)->result();
														foreach($resPOCODE as $rowPOCODE) :
															$PO_CODE	= $rowPOCODE->PO_CODE;
														endforeach;
														
														$TTK_REF_CODE1	= $TTK_REF1_CODE;													
														$TTK_REF_CODE2	= 'Direct';
													}
													elseif($TTK_CATEG == 'OPN')
													{
														$TTK_REF_CODE1	= '';
														$sqlREF1	= "SELECT OPNH_CODE AS TTK_REF_CODE1 FROM tbl_opn_header 
																		WHERE OPNH_NUM = '$TTK_REF1_NUM' LIMIT 1";
														$resREF1	= $this->db->query($sqlREF1)->result();
														foreach($resREF1 as $rowREF1) :
															$TTK_REF_CODE1	= $rowREF1->TTK_REF_CODE1;
														endforeach;
														
														$TTK_REF_CODE2	= '';
														$sqlREF2	= "SELECT WO_CODE AS TTK_REF_CODE2 FROM tbl_wo_header
																		WHERE WO_NUM = '$TTK_REF2_NUM' LIMIT 1";
														$resREF2	= $this->db->query($sqlREF2)->result();
														foreach($resREF2 as $rowREF2) :
															$TTK_REF_CODE2	= $rowREF2->TTK_REF_CODE2;
														endforeach;
														//$TTK_GTOTAL	= $TTK_REF1_AM - $TTK_REF1_RET - $TTK_REF1_POT + $TTK_REF1_PPN;
													}
													else
													{
														$TTK_REF_CODE1	= '';
														$sqlREF1	= "SELECT FPA_CODE AS TTK_REF_CODE1 FROM tbl_fpa_header 
																		WHERE FPA_NUM = '$TTK_REF1_NUM' LIMIT 1";
														$resREF1	= $this->db->query($sqlREF1)->result();
														foreach($resREF1 as $rowREF1) :
															$TTK_REF_CODE1	= $rowREF1->TTK_REF_CODE1;
														endforeach;
														
														$TTK_REF_CODE2	= $TTK_REF2_NUM;
													}

													$TTK_REF1_TOT	= $TTK_REF1_AM + $TTK_REF1_PPN - $TTK_REF1_PPH - $TTK_REF1_DPB - $TTK_REF1_RET - $TTK_REF1_POT;
	                                                ?>
	                                                    <tr id="tr_<?php echo $currentRow; ?>">
	                                                        <td height="25" style="text-align:left">
																<?php
																	if($TTK_STAT == 1)
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
	                                                        </td>
	                                                        <td style="text-align:left">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_NUM" name="data[<?php echo $currentRow; ?>][TTK_NUM]" value="<?php echo $TTK_NUM; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_CODE" name="data[<?php echo $currentRow; ?>][TTK_CODE]" value="<?php echo $TTK_CODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_NUM" name="data[<?php echo $currentRow; ?>][TTK_REF1_NUM]" value="<?php echo $TTK_REF1_NUM; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_CODE" name="data[<?php echo $currentRow; ?>][TTK_REF1_CODE]" value="<?php echo $TTK_REF1_CODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <?php print $TTK_REF1_CODE; ?>
	                                                        </td>
	                                                        <td style="text-align:center" nowrap>
																<?php print $TTK_REF1_DATE; ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_DATE" name="data[<?php echo $currentRow; ?>][TTK_REF1_DATE]" value="<?php echo $TTK_REF1_DATE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_DATED" name="data[<?php echo $currentRow; ?>][TTK_REF1_DATED]" value="<?php echo $TTK_REF1_DATED; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="display: none;" nowrap>
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
	                                                            	<input type="text" id="data<?php echo $currentRow; ?>TTK_REF1_SPLINV" name="data[<?php echo $currentRow; ?>][TTK_REF1_SPLINV]" value="<?php echo $TTK_REF1_SPLINV; ?>" class="form-control" style="min-width:100px;">
	                                                        	<?php } else { ?>
																	<?php print $TTK_REF1_SPLINV; ?>
	                                                            	<input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_SPLINV" name="data[<?php echo $currentRow; ?>][TTK_REF1_SPLINV]" value="<?php echo $TTK_REF1_SPLINV; ?>" class="form-control" style="max-width:300px;">
	                                                        	<?php } ?>
	                                                        </td>
	                                                        <td style="display: none;" nowrap>
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
	                                                            	<input type="text" id="data<?php echo $currentRow; ?>TTK_REF1_PPNNO" name="data[<?php echo $currentRow; ?>][TTK_REF1_PPNNO]" value="<?php echo $TTK_REF1_PPNNO; ?>" class="form-control" style="min-width:100px;">
	                                                        	<?php } else { ?>
																	<?php print $TTK_REF1_PPNNO; ?>
	                                                            	<input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_PPNNO" name="data[<?php echo $currentRow; ?>][TTK_REF1_PPNNO]" value="<?php echo $TTK_REF1_PPNNO; ?>" class="form-control" style="max-width:300px;">
	                                                        	<?php } ?>

	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF2_NUM" name="data[<?php echo $currentRow; ?>][TTK_REF2_NUM]" value="<?php echo $TTK_REF2_NUM; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF2_CODE" name="data[<?php echo $currentRow; ?>][TTK_REF2_CODE]" value="<?php echo $TTK_REF2_CODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF2_DATE" name="data[<?php echo $currentRow; ?>][TTK_REF2_DATE]" value="<?php echo $TTK_REF2_DATE; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td>
																<?php print $TTK_DESC; ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_DESC" name="data[<?php echo $currentRow; ?>][TTK_DESC]" value="<?php echo $TTK_DESC; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- AMN DETIL -->
	                                                            <?php echo number_format($TTK_REF1_AM, 2); ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_AM" name="data[<?php echo $currentRow; ?>][TTK_REF1_AM]" value="<?php echo $TTK_REF1_AM; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- POT DETIL -->
	                                                        	<?php echo number_format($TTK_REF1_POT, 2); ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_POT" name="data[<?php echo $currentRow; ?>][TTK_REF1_POT]" value="<?php echo $TTK_REF1_POT; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- PPN DETIL -->
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
	                                                        		<?php echo number_format($TTK_REF1_PPN, 2); ?>
	                                                            	<input type="hidden" id="TTK_REF1_PPN<?php echo $currentRow; ?>" value="<?php echo number_format($TTK_REF1_PPN, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgTAX(this, <?php echo $currentRow; ?>)">
	                                                        	<?php } else {  echo number_format($TTK_REF1_PPN, 2); } ?>

	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_PPN" name="data[<?php echo $currentRow; ?>][TTK_REF1_PPN]" value="<?=$TTK_REF1_PPN?>" class="form-control" style="min-width:100px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TAXCODE_PPN" name="data[<?php echo $currentRow; ?>][TAXCODE_PPN]" value="<?=$TAXCODE_PPN?>" class="form-control" style="min-width:100px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- DPB DETIL -->
	                                                        	<?php echo number_format($TTK_REF1_DPB, 2); ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_DPB" name="data[<?php echo $currentRow; ?>][TTK_REF1_DPB]" value="<?php echo $TTK_REF1_DPB; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- RET DETIL -->
	                                                        	<?php echo number_format($TTK_REF1_RET, 2); ?>
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_RET" name="data[<?php echo $currentRow; ?>][TTK_REF1_RET]" value="<?php echo $TTK_REF1_RET; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- PPH DETIL -->
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { echo number_format($TTK_REF1_PPH, 2); ?>
	                                                            	<input type="hidden" id="TTK_REF1_PPH<?php echo $currentRow; ?>" value="<?php echo number_format($TTK_REF1_PPH, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgTAX(this, <?php echo $currentRow; ?>)">
	                                                        	<?php } else {  echo number_format($TTK_REF1_PPH, 2); } ?>

	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_PPH" name="data[<?php echo $currentRow; ?>][TTK_REF1_PPH]" value="<?=$TTK_REF1_PPH?>" class="form-control" style="min-width:100px;">
	                                                            <input type="hidden" id="data<?php echo $currentRow; ?>TAXCODE_PPH" name="data[<?php echo $currentRow; ?>][TAXCODE_PPH]" value="<?=$TAXCODE_PPH?>" class="form-control" style="min-width:100px;">
	                                                        </td>
	                                                        <td style="text-align:right">
	                                                        	<input type="checkbox" name="chkTotal<?php echo $currentRow; ?>" id="chkTotal<?php echo $currentRow; ?>" onClick="checkPPn(<?php echo $currentRow; ?>)" style="display: none;">
	                                                        	<span id="data<?php echo $currentRow; ?>TTK_REF1_GTOTV"><?php echo number_format($TTK_REF1_TOT, 2); ?></span>
	                                                        	<input type="hidden" id="data<?php echo $currentRow; ?>TTK_REF1_GTOT" name="data[<?php echo $currentRow; ?>][TTK_REF1_GTOT]" value="<?php echo $TTK_REF1_TOT; ?>" class="form-control" style="min-width:100px; text-align:right;">
	                                                        	<input type="hidden" id="dataUA<?php echo $currentRow; ?>TTK_ISCOST" name="dataUA[<?php echo $currentRow; ?>][TTK_ISCOST]" value="0" class="form-control" style="min-width:100px; text-align:right;">
	                                                        </td>
	                                                    </tr>
	                                                <?php
	                                            endforeach;
	                                        }
	                                    }
	                                    /*if($task == 'add')
	                                    {*/
	                                        ?>
	                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
	                                        <?php
	                                    /*}*/
	                                    ?>
	                                </table>
	                                <input type="hidden" name="TAXCODE_PPN" id="TAXCODE_PPN" value="<?php echo $TAXCODE_PPN; ?>" />
	                                <input type="hidden" name="TAXCODE_PPH" id="TAXCODE_PPH" value="<?php echo $TAXCODE_PPH; ?>" />
			                    </div>
			                </div>
		        		</div>
		        	</div>

                    <div class="col-md-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<i class="fa fa-qrcode"></i>
								<h3 class="box-title">No. Kwitansi dan Faktur Pajak</h3>

					          	<div class="box-tools pull-right">
					          		<div onClick="add_TAXNO()" title="Tambah No. Seri Pajak" class="btn btn-danger btn-xs">
					          			<i class="glyphicon glyphicon-plus"></i>
					          		</div>
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
		                        <div class="search-table-outter">
		                            <table id="tbl_tax" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="10" style="text-align:center">&nbsp;</th>
	                                        <th width="150" style="text-align:center" nowrap><?php echo "No. Kwitansi"; ?></th>
											<th width="80" style="text-align:center"><?php echo "Tgl. Kwitansi"; ?></th>
	                                        <th width="100" style="text-align:center" nowrap><?php echo "Nilai Kwitansi"; ?></th>
	                                        <th width="150" style="text-align:center" nowrap><?php echo "No. Seri Pajak"; ?></th>
	                                        <th width="80" style="text-align:center"><?php echo "Tgl. Faktur Pajak"; ?></th>
	                                        <th width="100" style="text-align:center" nowrap><?php echo $AmountReceipt; ?></th>
	                                        <th width="100" style="text-align:center" nowrap><?php echo $PPNValue; ?></th>
	                                    </tr>
										<?php
											$rowTAX 	= 0;
											$TOT_AMOUNT2	= 0;
		                                    if($task == 'edit')
		                                    {
		                                        // count data
		                                        $sqlIRc		= "tbl_ttk_tax WHERE TTK_NUM = '$TTK_NUM'";
		                                        $resIRc 	= $this->db->count_all($sqlIRc);
		                                        // End count data

		                                        $sqlIR			= "SELECT A.* FROM tbl_ttk_tax A
																	WHERE A.TTK_NUM = '$TTK_NUM' AND A.PRJCODE = '$PRJCODE'";
		                                        $resIRc 		= $this->db->query($sqlIR)->result();
		                                        $i				= 0;
		                                        if($resIRc > 0)
		                                        {
		                                            foreach($resIRc as $row) :
		                                                $rowTAX  	= ++$i;
		                                                $TTKT_DATE 		= $row->TTKT_DATE;
		                                                $TTKT_TAXNO 	= $row->TTKT_TAXNO;
														$TTKT_SPLINVDATE= $row->TTKT_SPLINVDATE;
		                                                $TTKT_SPLINVNO 	= $row->TTKT_SPLINVNO;
		                                                $TTKT_SPLINVVAL	= $row->TTKT_SPLINVVAL;
		                                                $TTKT_AMOUNT	= $row->TTKT_AMOUNT;
														$TTKT_TAXAMOUNT	= $row->TTKT_TAXAMOUNT;

														if($TTKT_SPLINVDATE == '' || $TTKT_SPLINVDATE == '0000-00-00') $TTKT_SPLINVDATEV = '';
														else $TTKT_SPLINVDATEV = strftime('%d %b %Y', strtotime($TTKT_SPLINVDATE));
		                                                ?>
		                                                    <tr id="trTAX_<?php echo $rowTAX; ?>">
		                                                        <td height="25" style="text-align:left">
																	<?php
																		if($TTK_STAT == 1)
																		{
																			?>
																				<a href="#" onClick="deleteRow(<?php echo $rowTAX; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
																			<?php
																		}
																		else
																		{
																			echo "$rowTAX.";
																		}
		                                                            ?>
		                                                        </td>
		                                                        <td style="text-align:left;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $rowTAX; ?>TTKT_SPLINVNO" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_SPLINVNO]" value="<?php echo $TTKT_SPLINVNO; ?>" class="form-control" maxlength="100">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo $TTKT_SPLINVNO;
			                                                        		?>
			                                                            	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_SPLINVNO" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_SPLINVNO]" value="<?php echo $TTKT_SPLINVNO; ?>" class="form-control" maxlength="100">
			                                                        		<?php 
			                                                        	}
		                                                        	?>
		                                                        </td>
																<td style="text-align:center;">
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $rowTAX; ?>TTKT_SPLINVDATE" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_SPLINVDATE]" value="<?php echo $TTKT_SPLINVDATE; ?>" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD" maxlength="10">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo $TTKT_SPLINVDATEV;
			                                                        		?>
			                                                            	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_SPLINVDATE" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_SPLINVDATE]" value="<?php echo $TTKT_SPLINVDATE; ?>" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD">
			                                                        		<?php 
			                                                        	}
		                                                        	?> 
		                                                        </td>
		                                                        <td style="text-align:right;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{ 
			                                                        		?>
			                                                            	<input type="text" id="TTKT_SPLINVVAL<?php echo $rowTAX; ?>" value="<?php echo number_format($TTKT_SPLINVVAL, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgKWITVAL(this, <?php echo $rowTAX; ?>)">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{ 
			                                                        		echo number_format($TTKT_SPLINVVAL, 2); 
			                                                        		?>
			                                                            	<input type="hidden" id="TTKT_SPLINVVAL<?php echo $rowTAX; ?>" value="<?php echo number_format($TTKT_SPLINVVAL, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, <?php echo $rowTAX; ?>)">
			                                                        		<?php 
			                                                        	}
		                                                        	?>

		                                                        	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_SPLINVVAL" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_SPLINVVAL]" value="<?php echo $TTKT_SPLINVVAL; ?>" class="form-control" style="min-width:100px;">
		                                                        </td>
		                                                        <td style="text-align:left;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $rowTAX; ?>TTKT_TAXNO" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_TAXNO]" value="<?php echo $TTKT_TAXNO; ?>" class="form-control" onKeyUp="chkMask(this)" placeholder="XXX.XXX-XX.XXXXXXXX" onChange="chkCont(this, <?php echo $rowTAX; ?>)" maxlength="19">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo $TTKT_TAXNO;
			                                                        		?>
			                                                            	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_TAXNO" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_TAXNO]" value="<?php echo $TTKT_TAXNO; ?>" class="form-control" onKeyUp="chkMask(this)" placeholder="XXX.XXX-XX.XXXXXXXX" maxlength="19">
			                                                        		<?php 
			                                                        	}
		                                                        	?>
		                                                        </td>
		                                                        <td style="text-align:center;">
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{
			                                                        		?>
			                                                            	<input type="text" id="dataTAX<?php echo $rowTAX; ?>TTKT_DATE" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_DATE]" value="<?php echo $TTKT_DATE; ?>" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD" maxlength="10">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{
			                                                        		echo strftime('%d %b %Y', strtotime($TTKT_DATE));
			                                                        		?>
			                                                            	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_DATE" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_DATE]" value="<?php echo $TTKT_DATE; ?>" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD">
			                                                        		<?php 
			                                                        	}
		                                                        	?>   
		                                                        </td>
		                                                        <td style="text-align:right;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{ 
			                                                        		?>
			                                                            	<input type="text" id="TTKT_AMOUNT<?php echo $rowTAX; ?>" value="<?php echo number_format($TTKT_AMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, <?php echo $rowTAX; ?>)">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{ 
			                                                        		echo number_format($TTKT_AMOUNT, 2); 
			                                                        		?>
			                                                            	<input type="hidden" id="TTKT_AMOUNT<?php echo $rowTAX; ?>" value="<?php echo number_format($TTKT_AMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, <?php echo $rowTAX; ?>)">
			                                                        		<?php 
			                                                        	}
		                                                        	?>

		                                                        	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_AMOUNT" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_AMOUNT]" value="<?php echo $TTKT_AMOUNT; ?>" class="form-control" style="min-width:100px;">
		                                                        </td>
		                                                        <td style="text-align:right;" nowrap>
		                                                        	<?php 
			                                                        	if($TTK_STAT == 1 || $TTK_STAT == 4)
			                                                        	{ 
			                                                        		?>
			                                                            	<input type="text" id="TTKT_TAXAMOUNT<?php echo $rowTAX; ?>" value="<?php echo number_format($TTKT_TAXAMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgPPN(this, <?php echo $rowTAX; ?>)">
			                                                        		<?php 
			                                                        	} 
			                                                        	else 
			                                                        	{ 
			                                                        		echo number_format($TTKT_TAXAMOUNT, 2); 
			                                                        		?>
			                                                            	<input type="hidden" id="TTKT_TAXAMOUNT<?php echo $rowTAX; ?>" value="<?php echo number_format($TTKT_TAXAMOUNT, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgPPN(this, <?php echo $rowTAX; ?>)">
			                                                        		<?php 
			                                                        	}
		                                                        	?>

		                                                        	
		                                                        	<input type="hidden" id="dataTAX<?php echo $rowTAX; ?>TTKT_TAXAMOUNT" name="dataTAX[<?php echo $rowTAX; ?>][TTKT_TAXAMOUNT]" value="<?php echo $TTKT_TAXAMOUNT; ?>" class="form-control" style="min-width:100px;">
		                                                        </td>
		                                                    </tr>
		                                                <?php
		                                            endforeach;
		                                        }
		                                    }
	                                    ?>
	                                    <input type="hidden" name="totalrowTAX" id="totalrowTAX" value="<?php echo $rowTAX; ?>">
	                                </table>
	                            </div>
							</div>
						</div>
					</div>

					<div class="col-md-12" style="display: none;">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-question-circle"></i>
								<h3 class="box-title"><?=$OthExp?></h3>

					          	<div class="box-tools pull-right">
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
		                        <div class="search-table-outter">
		                            <table id="tbl_exp" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="30" style="text-align:center">&nbsp;</th>
	                                        <th width="150" style="text-align:center">No. LPM/Opname</th>
	                                        <th style="text-align:center; display: none;" nowrap><?php echo $Date; ?></th>
	                                        <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $Description; ?></th>
	                                        <th style="text-align:center; vertical-align: middle;" nowrap><?php echo $AmountReceipt; ?></th>
	                                        <th style="text-align:center; display: none;" nowrap><?php echo $Discount; ?></th>
	                                        <th style="text-align:center; display: none;" nowrap>PPn</th>
	                                        <th style="text-align:center; display: none;" nowrap>PPh</th>
	                                        <th style="text-align:center; display: none;" nowrap>Pot. UM</th>
	                                        <th style="text-align:center; display: none;" nowrap>Retensi</th>
	                                        <th style="text-align:center; display: none;" nowrap><?php echo $TotAmount; ?></th>
	                                    </tr>
										<?php
										$rowUA 	= 0;
										$TOT_AMOUNT2	= 0;
	                                    if($task == 'edit')
	                                    {
	                                        // count data
	                                        $sqlIRc		= "tbl_ttk_detail WHERE TTK_NUM = '$TTK_NUM' AND TTK_ISCOST = 1";
	                                        $resIRc 	= $this->db->count_all($sqlIRc);
	                                        // End count data
	                                        
	                                        // 1. Ambil detail IR
	                                        $sqlIR			= "SELECT A.* FROM tbl_ttk_detail A
																	INNER JOIN tbl_ttk_header B ON B.TTK_NUM = A.TTK_NUM
																		AND B.PRJCODE = '$PRJCODE'
																WHERE A.TTK_NUM = '$TTK_NUM' AND A.PRJCODE = '$PRJCODE' AND TTK_ISCOST = 1";
	                                        $resIRc 		= $this->db->query($sqlIR)->result();
	                                        $i				= 0;
											$TAXCODE_PPN 	= "";
											$TAXCODE_PPH	= "";
	                                        if($resIRc > 0)
	                                        {
	                                            foreach($resIRc as $row) :
	                                                $rowUA  	= ++$i;
	                                                $TTK_REF1_NUM 	= $row->TTK_REF1_NUM;
	                                                $TTK_REF1_CODE 	= $row->TTK_REF1_CODE;
	                                                $TTK_REF1_DATE	= $row->TTK_REF1_DATE;
													$TTK_REF1_DATED	= $row->TTK_REF1_DATED;
	                                                $TTK_REF1_AM	= $row->TTK_REF1_AM;
	                                                $TTK_REF1_PPN	= $row->TTK_REF1_PPN;
	                                                $TTK_REF1_PPH	= $row->TTK_REF1_PPH;
	                                                $TTK_REF1_DPB	= $row->TTK_REF1_DPB;
	                                                $TTK_REF1_RET	= $row->TTK_REF1_RET;
	                                                $TTK_REF1_POT	= $row->TTK_REF1_POT;
	                                                $TTK_REF1_GTOT	= $row->TTK_REF1_GTOT; 
	                                                $TTK_REF1_SPLINV= $row->TTK_REF1_SPLINV;
	                                                $TTK_REF1_PPNNO = $row->TTK_REF1_PPNNO;
	                                                $TTK_REF2_NUM 	= $row->TTK_REF2_NUM;
	                                                $TTK_REF2_CODE 	= $row->TTK_REF2_CODE;
	                                                $TTK_REF2_DATE	= $row->TTK_REF2_DATE;
	                                                $TTK_DESC 		= $row->TTK_DESC;

	                                                $TAXCODE_PPN 	= $row->TAXCODE_PPN;
	                                                $TAXCODE_PPH 	= $row->TAXCODE_PPH;

													if($TAXCODE_PPN != '')
														$TAXCODE_PPN	= $TAXCODE_PPN;

													if($TAXCODE_PPH != '')
														$TAXCODE_PPH	= $TAXCODE_PPH;
													
													$TTK_REF_CODE	= '';
													$PO_CODE		= '';
													$PO_NUM			= $TTK_REF2_NUM;
													if($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ' AND $PO_NUM != '')
													{
														$PO_CODE	= '';
														$sqlPOCODE	= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' LIMIT 1";
														$resPOCODE	= $this->db->query($sqlPOCODE)->result();
														foreach($resPOCODE as $rowPOCODE) :
															$PO_CODE	= $rowPOCODE->PO_CODE;
														endforeach;
														
														$TTK_REF_CODE1	= '';
														$sqlREF1	= "SELECT IR_CODE AS TTK_REF_CODE1 FROM tbl_ir_header 
																		WHERE IR_NUM = '$TTK_REF1_NUM' LIMIT 1";
														$resREF1	= $this->db->query($sqlREF1)->result();
														foreach($resREF1 as $rowREF1) :
															$TTK_REF_CODE1	= $rowREF1->TTK_REF_CODE1;
														endforeach;
														
														$TTK_REF_CODE2	= '';
														$sqlREF2	= "SELECT PO_CODE FROM tbl_po_header
																		WHERE PO_NUM = '$PO_NUM' LIMIT 1";
														$resREF2	= $this->db->query($sqlREF2)->result();
														foreach($resREF2 as $rowREF2) :
															$TTK_REF_CODE2	= $rowREF2->PO_CODE;
														endforeach;
													}
													elseif($TTK_CATEG == 'IR' || $TTK_CATEG == 'SJ' AND $PO_NUM == '')
													{
														$PO_CODE	= '';
														$sqlPOCODE	= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' LIMIT 1";
														$resPOCODE	= $this->db->query($sqlPOCODE)->result();
														foreach($resPOCODE as $rowPOCODE) :
															$PO_CODE	= $rowPOCODE->PO_CODE;
														endforeach;
														
														$TTK_REF_CODE1	= $TTK_REF1;													
														$TTK_REF_CODE2	= 'Direct';
													}
													elseif($TTK_CATEG == 'OPN')
													{
														$TTK_REF_CODE1	= '';
														$sqlREF1	= "SELECT OPNH_CODE AS TTK_REF_CODE1 FROM tbl_opn_header 
																		WHERE OPNH_NUM = '$TTK_REF1_NUM' LIMIT 1";
														$resREF1	= $this->db->query($sqlREF1)->result();
														foreach($resREF1 as $rowREF1) :
															$TTK_REF_CODE1	= $rowREF1->TTK_REF_CODE1;
														endforeach;
														
														$TTK_REF_CODE2	= '';
														$sqlREF2	= "SELECT WO_CODE AS TTK_REF_CODE2 FROM tbl_wo_header
																		WHERE WO_NUM = '$TTK_REF2_NUM' LIMIT 1";
														$resREF2	= $this->db->query($sqlREF2)->result();
														foreach($resREF2 as $rowREF2) :
															$TTK_REF_CODE2	= $rowREF2->TTK_REF_CODE2;
														endforeach;
														//$TTK_GTOTAL	= $TTK_REF1_AM - $TTK_REF1_RET - $TTK_REF1_POT + $TTK_REF1_PPN;
													}
													else
													{
														$TTK_REF_CODE1	= '';
														$sqlREF1	= "SELECT FPA_CODE AS TTK_REF_CODE1 FROM tbl_fpa_header 
																		WHERE FPA_NUM = '$TTK_REF1_NUM' LIMIT 1";
														$resREF1	= $this->db->query($sqlREF1)->result();
														foreach($resREF1 as $rowREF1) :
															$TTK_REF_CODE1	= $rowREF1->TTK_REF_CODE1;
														endforeach;
														
														$TTK_REF_CODE2	= $TTK_REF2_NUM;
													}

													$TTK_REF1_TOT	= $TTK_REF1_AM + $TTK_REF1_PPN - $TTK_REF1_PPH - $TTK_REF1_DPB - $TTK_REF1_RET - $TTK_REF1_POT;
	                                                ?>
	                                                    <tr id="trEXP_<?php echo $rowUA; ?>">
	                                                        <td height="25" style="text-align:left">
																<?php
																	if($TTK_STAT == 1)
																	{
																		?>
																			<a href="#" onClick="deleteRow(<?php echo $rowUA; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
																		<?php
																	}
																	else
																	{
																		echo "$rowUA.";
																	}
	                                                            ?>
	                                                        </td>
	                                                        <td style="text-align:left">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_NUM" name="dataUA[<?php echo $rowUA; ?>][TTK_NUM]" value="<?php echo $TTK_NUM; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_CODE" name="dataUA[<?php echo $rowUA; ?>][TTK_CODE]" value="<?php echo $TTK_CODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>PRJCODE" name="dataUA[<?php echo $rowUA; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_NUM" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_NUM]" value="<?php echo $TTK_REF1_NUM; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_CODE" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_CODE]" value="<?php echo $TTK_REF1_CODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <?php print $TTK_REF1_CODE; ?>
	                                                        </td>
	                                                        <td style="text-align:center; display: none;" nowrap>
																<?php print $TTK_REF1_DATE; ?>
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_DATE" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_DATE]" value="<?php echo $TTK_REF1_DATE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_DATED" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_DATED]" value="<?php echo $TTK_REF1_DATED; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="display: none;" nowrap>
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
	                                                            	<input type="text" id="dataUA<?php echo $rowUA; ?>TTK_REF1_SPLINV" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_SPLINV]" value="<?php echo $TTK_REF1_SPLINV; ?>" class="form-control" style="min-width:100px;">
	                                                        	<?php } else { ?>
																	<?php print $TTK_REF1_SPLINV; ?>
	                                                            	<input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_SPLINV" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_SPLINV]" value="<?php echo $TTK_REF1_SPLINV; ?>" class="form-control" style="max-width:300px;">
	                                                        	<?php } ?>
	                                                        </td>
	                                                        <td style="display: none;" nowrap>
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
	                                                            	<input type="text" id="dataUA<?php echo $rowUA; ?>TTK_REF1_PPNNO" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_PPNNO]" value="<?php echo $TTK_REF1_PPNNO; ?>" class="form-control" style="min-width:100px;">
	                                                        	<?php } else { ?>
																	<?php print $TTK_REF1_PPNNO; ?>
	                                                            	<input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_PPNNO" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_PPNNO]" value="<?php echo $TTK_REF1_PPNNO; ?>" class="form-control" style="max-width:300px;">
	                                                        	<?php } ?>

	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF2_NUM" name="dataUA[<?php echo $rowUA; ?>][TTK_REF2_NUM]" value="<?php echo $TTK_REF2_NUM; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF2_CODE" name="dataUA[<?php echo $rowUA; ?>][TTK_REF2_CODE]" value="<?php echo $TTK_REF2_CODE; ?>" class="form-control" style="max-width:300px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF2_DATE" name="dataUA[<?php echo $rowUA; ?>][TTK_REF2_DATE]" value="<?php echo $TTK_REF2_DATE; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td>
																<?php print $TTK_DESC; ?>
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_DESC" name="dataUA[<?php echo $rowUA; ?>][TTK_DESC]" value="<?php echo $TTK_DESC; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right">			<!-- AMN DETIL -->
	                                                            <?php echo number_format($TTK_REF1_AM, 2); ?>
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_AM" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_AM]" value="<?php echo $TTK_REF1_AM; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right; display: none;">			<!-- POT DETIL -->
	                                                        	<?php echo number_format($TTK_REF1_POT, 2); ?>
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_POT" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_POT]" value="<?php echo $TTK_REF1_POT; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right; display: none;">			<!-- PPN DETIL -->
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { ?>
	                                                            	<input type="hidden" id="TTK_REF1_PPN<?php echo $rowUA; ?>" value="<?php echo number_format($TTK_REF1_PPN, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgTAX(this, <?php echo $rowUA; ?>)">
	                                                        	<?php } else {  echo number_format($TTK_REF1_PPN, 2); } ?>

	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_PPN" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_PPN]" value="<?=$TTK_REF1_PPN?>" class="form-control" style="min-width:100px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TAXCODE_PPN" name="dataUA[<?php echo $rowUA; ?>][TAXCODE_PPN]" value="<?=$TAXCODE_PPN?>" class="form-control" style="min-width:100px;">
	                                                        </td>
	                                                        <td style="text-align:right; display: none;">			<!-- PPH DETIL -->
	                                                        	<?php if($TTK_STAT == 1 || $TTK_STAT == 4) { echo number_format($TTK_REF1_PPH, 2); ?>
	                                                            	<input type="hidden" id="TTK_REF1_PPH<?php echo $rowUA; ?>" value="<?php echo number_format($TTK_REF1_PPH, 2); ?>" class="form-control" style="min-width:100px; text-align: right" onBlur="chgTAX(this, <?php echo $rowUA; ?>)">
	                                                        	<?php } else {  echo number_format($TTK_REF1_PPH, 2); } ?>

	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_PPH" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_PPH]" value="<?=$TTK_REF1_PPH?>" class="form-control" style="min-width:100px;">
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TAXCODE_PPH" name="dataUA[<?php echo $rowUA; ?>][TAXCODE_PPH]" value="<?=$TAXCODE_PPH?>" class="form-control" style="min-width:100px;">
	                                                        </td>
	                                                        <td style="text-align:right; display: none;">			<!-- DPB DETIL -->
	                                                        	<?php echo number_format($TTK_REF1_DPB, 2); ?>
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_DPB" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_DPB]" value="<?php echo $TTK_REF1_DPB; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right; display: none;">			<!-- RET DETIL -->
	                                                        	<?php echo number_format($TTK_REF1_RET, 2); ?>
	                                                            <input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_RET" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_RET]" value="<?php echo $TTK_REF1_RET; ?>" class="form-control" style="max-width:300px;">
	                                                        </td>
	                                                        <td style="text-align:right; display: none;">
	                                                        	<input type="checkbox" name="chkTotal<?php echo $rowUA; ?>" id="chkTotal<?php echo $rowUA; ?>" onClick="checkPPn(<?php echo $rowUA; ?>)" style="display: none;">
	                                                        	<span id="dataUA<?php echo $rowUA; ?>TTK_REF1_GTOTV"><?php echo number_format($TTK_REF1_TOT, 2); ?></span>
	                                                        	<input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_REF1_GTOT" name="dataUA[<?php echo $rowUA; ?>][TTK_REF1_GTOT]" value="<?php echo $TTK_REF1_TOT; ?>" class="form-control" style="min-width:100px; text-align:right;">
	                                                        	<input type="hidden" id="dataUA<?php echo $rowUA; ?>TTK_ISCOST" name="dataUA[<?php echo $rowUA; ?>][TTK_ISCOST]" value="1" class="form-control" style="min-width:100px; text-align:right;">
	                                                        </td>
	                                                    </tr>
	                                                <?php
	                                            endforeach;
	                                        }
	                                    }
	                                    /*if($task == 'add')
	                                    {*/
	                                        ?>
	                                            <input type="hidden" name="totalrowUA" id="totalrowUA" value="<?php echo $rowUA; ?>">
	                                        <?php
	                                    /*}*/
	                                    ?>
	                                </table>
	                            </div>
							</div>
						</div>
					</div>

                    <div class="col-md-6">
						<div>
							<div class="box-body">
								<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
	                          	<div class="col-sm-9">
	                            	<?php
										if($ISAPPROVE == 1)
											$ISCREATE = 1;
											
										if($task=='add')
										{
											if($TTK_STAT == 1 && $ISCREATE == 1)
											{
												?>
													<button class="btn btn-primary" id="btnSave">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										else
										{
											if($ISAPPROVE == 1 && $TTK_STAT == 2)
											{
												?>
			                                        <button class="btn btn-primary" style="display:none" id="tblClose">
			                                        <i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											elseif($ISAPPROVE == 1 && $TTK_STAT == 3)
											{
												?>
			                                        <button class="btn btn-primary" style="display:none" id="tblClose">
			                                        <i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											elseif($ISAPPROVE == 1 && $TTK_STAT != 3)
											{
												?>
													<button class="btn btn-primary" id="btnSave">
			                                        <i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
											elseif($ISCREATE == 1 && ($TTK_STAT == 1 || $TTK_STAT == 4))
											{
												?>
													<button class="btn btn-primary" id="btnSave">
													<i class="fa fa-save"></i></button>&nbsp;
												<?php
											}
										}
										$backURL	= site_url('c_purchase/c_pi180c23/galli180dah/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
									?>
	                            </div>
                    		</div>
                    	</div>
                    </div>
                    <br>
                </form>
		    </div>
		    <?php
		    	if($SPLCODE1 != '0')
				{
		    		?>
				    	<!-- ============ START MODAL ITEM =============== -->
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
								$Active3		= "";
								$Active1Cls		= "class='active'";
								$Active2Cls		= "";
								$Active3Cls		= "";

								$collData 		= "$PRJCODE~$SPLCODE1~$TTK_CATEG1";
								$popURLExp 		= "";
								if($TTK_CATEG1 == 'IR' || $TTK_CATEG1 == 'SJ')
								{
									$popURL 	= site_url('c_purchase/c_pi180c23/get_AllDataIR/?idIR='.$collData);
									$popURLExp 	= site_url('c_purchase/c_pi180c23/get_AllDataIRExp/?idIR='.$collData);
									$popURLSJ 	= site_url('c_purchase/c_pi180c23/get_AllDataIRSJ/?idIR='.$collData);
								}
								elseif($TTK_CATEG1 == 'OPN')
									$popURL 	= site_url('c_purchase/c_pi180c23/get_AllDataOPN/?idOPN='.$collData);
								else
									$popURL 	= site_url('c_purchase/c_pi180c23/get_AllDataOPN_RET/?idRET='.$collData);
						
								$LangID 	= $this->session->userdata['LangID'];
								if($LangID == 'IND')
								{
									if($TTK_CATEG == 'IR')
										$h3_title 	= "Daftar Penerimaan";
									elseif($TTK_CATEG == 'OPN')
										$h3_title 	= "Daftar Opname";
									elseif($TTK_CATEG == 'OPN-RET')
										$h3_title 	= "Daftar Retensi Opname";
								}
								else
								{
									if($TTK_CATEG == 'IR')
										$h3_title 	= "Item Receipt List";
									elseif($TTK_CATEG == 'OPN')
										$h3_title	= "Opname List";
									elseif($TTK_CATEG == 'OPN-RET')
										$h3_title	= "Opname Retention List";
								}
					    	?>
					        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					            <div class="modal-dialog">
						            <div class="modal-content">
						                <div class="modal-body">
											<div class="row">
										    	<div class="col-md-12">
									              	<ul class="nav nav-tabs">
									                    <li id="li1">
									                    	<a href="#itm1" data-toggle="tab" onClick="shwTab(1)"><?php echo $h3_title; ?></a>
									                    </li>
									                    <li id="li2">
									                    	<a href="#itm2" data-toggle="tab" onClick="shwTab(2)"><?php echo $OthExp; ?></a>
									                    </li>
									                    <li id="li3">
									                    	<a href="#itm2" data-toggle="tab" onClick="shwTab(3)"><?php echo $SJList; ?></a>
									                    </li>
									                </ul>
									                <script type="text/javascript">
									                	function shwTab(valTab)
									                	{
									                		if(valTab == 1)
									                		{
									                			document.getElementById('itm1').style.display = '';
									                			document.getElementById('itm2').style.display = 'none';
									                			document.getElementById('itm3').style.display = 'none';
									                		}
									                		else if(valTab == 2)
									                		{
									                			document.getElementById('itm1').style.display = 'none';
									                			document.getElementById('itm2').style.display = '';
									                			document.getElementById('itm3').style.display = 'none';
									                		}
									                		else
									                		{
									                			document.getElementById('itm1').style.display = 'none';
									                			document.getElementById('itm2').style.display = 'none';
									                			document.getElementById('itm3').style.display = '';
									                		}
									                	}
									                </script>
										            <div class="box-body">
										            	<div class="tab-pane" id="itm1">
					                                        <div class="form-group">
					                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
						                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						                                                <thead>
						                                                    <tr>
														                        <th width="2%">&nbsp;</th>
															                    <th width="10%" style="vertical-align:middle; text-align:center" nowrap>
															                    	<?php echo $ReceiptNumber; ?>
															                    </th>
															                    <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
																				<th width="33%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Description; ?></th>
															                    <th width="10%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Amount; ?></th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Discount; ?></th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center">PPn</th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center">PPh</th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center" title="Pengembalian Uang Muka">Pot. UM</th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center">Retensi</th>
																				<th width="15%" nowrap style="vertical-align:middle; text-align:center">Total</th>
														                  	</tr>
						                                                </thead>
						                                                <tbody>
						                                                </tbody>
						                                            </table>
			                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
			                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
			                                                    	</button>
			                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
			                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
			                                                    	</button>
			                                      					<button class="btn btn-warning" type="button" id="idRefresh1" >
			                                                    		<i class="glyphicon glyphicon-refresh"></i>
			                                                    	</button>
					                                            </form>
					                                      	</div>
					                                    </div>
										            	<div class="tab-pane" id="itm2" style="display: none;">
					                                        <div class="form-group">
					                                        	<form method="post" name="frmSearch2" id="frmSearch2" action="">
						                                            <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						                                                <thead>
						                                                    <tr>
														                        <th width="2%">&nbsp;</th>
															                    <th width="15%" style="vertical-align:middle; text-align:center" nowrap>
															                    	<?php echo $ReceiptNumber; ?>
															                    </th>
															                    <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
																				<th width="63%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Description; ?></th>
															                    <th width="15%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Amount; ?></th>
														                  	</tr>
						                                                </thead>
						                                                <tbody>
						                                                </tbody>
						                                            </table>
			                                                    	<button class="btn btn-primary" type="button" id="btnDetail2" name="btnDetail2">
			                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
			                                                    	</button>
			                                      					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
			                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
			                                                    	</button>
			                                      					<button class="btn btn-warning" type="button" id="idRefresh2" >
			                                                    		<i class="glyphicon glyphicon-refresh"></i>
			                                                    	</button>
					                                            </form>
					                                      	</div>
					                                    </div>
					                                    <div class="tab-pane" id="itm3" style="display: none;">
					                                        <div class="form-group">
					                                        	<form method="post" name="frmSearch3" id="frmSearch2" action="">
						                                            <table id="example3" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						                                                <thead>
						                                                    <tr>
														                        <th width="5%">&nbsp;</th>
															                    <th width="15%" style="vertical-align:middle; text-align:center" nowrap>
															                    	No. SJ
															                    </th>
															                    <th width="15%" style="vertical-align:middle; text-align:center" nowrap>
															                    	<?php echo $ReceiptNumber; ?>
															                    </th>
															                    <th width="5%" style="vertical-align:middle; text-align:center"><?php echo $Date; ?></th>
																				<th width="45%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Description; ?></th>
															                    <th width="15%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Amount; ?></th>
															                    <th width="5%" nowrap style="vertical-align:middle; text-align:center"><?php echo $Discount; ?></th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center">PPn</th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center">PPh</th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center" title="Pengembalian Uang Muka">Pot. UM</th>
																				<th width="5%" nowrap style="vertical-align:middle; text-align:center">Retensi</th>
																				<th width="15%" nowrap style="vertical-align:middle; text-align:center">Total</th>
														                  	</tr>
						                                                </thead>
						                                                <tbody>
						                                                </tbody>
						                                            </table>
			                                                    	<button class="btn btn-primary" type="button" id="btnDetail3" name="btnDetail3">
			                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
			                                                    	</button>
			                                      					<button type="button" id="idClose3" class="btn btn-danger" data-dismiss="modal">
			                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
			                                                    	</button>
			                                      					<button class="btn btn-warning" type="button" id="idRefresh3" >
			                                                    		<i class="glyphicon glyphicon-refresh"></i>
			                                                    	</button>
					                                            </form>
					                                      	</div>
					                                    </div>
			                                      	</div>
			                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
			                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
				                                </div>
				                            </div>
						                </div>
							        </div>
							    </div>
							</div>

							<script type="text/javascript">
								$(document).ready(function()
								{
									$('#btnModal').on('click', function(e){
										let TTK_CATEG = $('#TTK_CATEG').val();
										if(TTK_CATEG == 'IR')
										{
											$('#li1').addClass('active'); // tab IR
											$('#li1').show(); // tab IR
											$('#itm1').addClass('active'); // modal IR
											$('#itm1').show(); // modal IR
											$('#li2').show(); // tab OthExp
											$('#itm2').hide(); // modal OthExp
											$('#li3').removeClass('active'); // tab SJ
											$('#li3').hide(); // tab SJ
											$('#itm3').removeClass('active'); // modal SJ
											$('#itm3').hide(); // modal SJ
										}
										else if(TTK_CATEG == 'SJ')
										{
											$('#li1').removeClass('active'); // tab IR
											$('#li1').hide(); // tab IR
											$('#itm1').removeClass('active'); // modal IR
											$('#itm1').hide(); // modal IR
											$('#li2').show(); // tab OthExp
											$('#itm2').hide(); // modal OthExp
											$('#li3').addClass('active'); // tab SJ
											$('#li3').show(); // tab SJ
											$('#itm3').addClass('active'); // modal SJ
											$('#itm3').show(); // modal SJ
										}
										else
										{
											$('#li1').addClass('active'); // tab IR
											$('#li1').show(); // tab IR
											$('#itm1').addClass('active'); // modal IR
											$('#itm1').show(); // modal IR
											$('#li2').hide(); // tab OthExp
											$('#itm2').hide(); // modal OthExp
											$('#li3').removeClass('active'); // tab SJ
											$('#li3').hide(); // tab SJ
											$('#itm3').removeClass('active'); // modal SJ
											$('#itm3').hide(); // modal SJ
										}
									});

							    	$('#example1').DataTable(
							    	{
								        "processing": true,
								        "serverSide": true,
										//"scrollX": false,
										"autoWidth": true,
										"filter": true,
								        "ajax": "<?php echo $popURL;?>",
								        "type": "POST",
										//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
										"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
										"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
														{ targets: [4,5,6,7,8,9,10], className: 'dt-body-right' },
														{ sortable: false, targets: [3,5,6,7,8,9] }
													  ],
										"language": {
								            "infoFiltered":"",
								            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
								        },
									});

							    	<?php
								    	if($TTK_CATEG1 == 'IR' || $TTK_CATEG1 == 'SJ')
										{
											?>
									    	$('#example2').DataTable(
									    	{
										        "processing": true,
										        "serverSide": true,
												//"scrollX": false,
												"autoWidth": true,
												"filter": true,
										        "ajax": "<?php echo $popURLExp;?>",
										        "type": "POST",
												//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
												"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
												"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
																{ targets: [4], className: 'dt-body-right' },
																{ sortable: false, targets: [3] }
															  ],
												"language": {
										            "infoFiltered":"",
										            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
										        },
											});

									    	$('#example3').DataTable(
									    	{
										        "processing": true,
										        "serverSide": true,
												//"scrollX": false,
												"autoWidth": true,
												"filter": true,
										        "ajax": "<?php echo $popURLSJ;?>",
										        "type": "POST",
												//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
												"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
												"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
																{ targets: [5,6,7,8,9,10,11], className: 'dt-body-right' },
																{ sortable: false, targets: [3] }
															  ],
												"language": {
										            "infoFiltered":"",
										            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
										        },
											});
											<?php	
										}
									?>
								});

								var selectedRows = 0;
								function pickThis1(thisobj) 
								{
									var favorite = [];
									$.each($("input[name='chk1']:checked"), function() {
								      	favorite.push($(this).val());
								    });
								    $("#rowCheck").val(favorite.length);
								}

								function pickThis2(thisobj) 
								{
									var favorite = [];
									$.each($("input[name='chk2']:checked"), function() {
								      	favorite.push($(this).val());
								    });
								    $("#rowCheck").val(favorite.length);
								}

								$(document).ready(function()
								{
								   	$("#btnDetail1").click(function()
								    {
										var totChck 	= $("#rowCheck").val();
										if(totChck == 0)
										{
											swal('<?php echo $alert2; ?>',
											{
												icon: "warning",
											})
											.then(function()
								            {
								                swal.close();
								            });
											return false;
										}

									    $.each($("input[name='chk1']:checked"), function()
									    {
									      	add_DETIL($(this).val());
									    });

									    $('#mdl_addItm').on('hidden.bs.modal', function () {
										    $(this)
											    .find("input,textarea,select")
												    //.val('')
												    .end()
											    .find("input[type=checkbox], input[type=radio]")
											       .prop("checked", "")
											       .end();
										});
			                        	document.getElementById("idClose").click()
								    });

								   	$("#btnDetail2").click(function()
								    {
										var totChck 	= $("#rowCheck").val();
										if(totChck == 0)
										{
											swal('<?php echo $alert2; ?>',
											{
												icon: "warning",
											})
											.then(function()
								            {
								                swal.close();
								            });
											return false;
										}

									    $.each($("input[name='chk2']:checked"), function()
									    {
									      	add_DETILEXP($(this).val());
									    });

									    $('#mdl_addItm').on('hidden.bs.modal', function () {
										    $(this)
											    .find("input,textarea,select")
												    //.val('')
												    .end()
											    .find("input[type=checkbox], input[type=radio]")
											       .prop("checked", "")
											       .end();
										});
			                        	document.getElementById("idClose").click()
								    });

								    $("#btnDetail3").click(function()
								    {
										var totChck 	= $("#rowCheck").val();
										if(totChck == 0)
										{
											swal('<?php echo $alert2; ?>',
											{
												icon: "warning",
											})
											.then(function()
								            {
								                swal.close();
								            });
											return false;
										}

									    $.each($("input[name='chk1']:checked"), function()
									    {
									      	add_DETIL($(this).val());
									    });

									    $('#mdl_addItm').on('hidden.bs.modal', function () {
										    $(this)
											    .find("input,textarea,select")
												    //.val('')
												    .end()
											    .find("input[type=checkbox], input[type=radio]")
											       .prop("checked", "")
											       .end();
										});
			                        	document.getElementById("idClose").click()
								    });
								});
					    
							   	$("#idRefresh1").click(function()
							    {
									$('#example1').DataTable().ajax.reload();
							    });
					    
							   	$("#idRefresh2").click(function()
							    {
									$('#example2').DataTable().ajax.reload();
							    });
							</script>
				    	<!-- ============ END MODAL ITEM =============== -->
				    <?php
				}
			?>

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
	    $('#datepicker1').datepicker({
	      autoclose: true,
		  startDate: '+0d',
		  endDate: '+0d'
	    });

		$('#TTK_DATE1').datepicker({
	      autoclose: true,
		//   startDate: '+0d',
		  endDate: '+0d'
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
					// 	// $('#TTK_STAT>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#TTK_STAT>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#TTK_STAT').removeAttr('disabled','disabled');
							// $('#TTK_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#TTK_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#TTK_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#TTK_STAT').removeAttr('disabled','disabled');
							// $('#TTK_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#TTK_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#TTK_STAT').removeAttr('disabled','disabled');
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
		// END : GENERATE MANUAL CODE
	?>

	function addUCODE()
	{
		var task 		= "<?=$task?>";
		var DOCNUM		= document.getElementById('TTK_NUM').value;
		var DOCCODE		= document.getElementById('TTK_CODE').value;
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
							DOCTYPE 		: 'TTK'
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	console.log(response)
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	$('#TTK_CODE').val(docCode);
            	$('#TTK_CODEX').val(docCode);
            	$('#TTK_CODEY').val(docCode);
            }
        });
	}
	
	var decFormat		= 2;

	function add_header(IR_NUM) 
	{
		//swal(IR_NUM)
		//document.getElementById("IR_NUM1").value = IR_NUM;
		//document.frmsrch.submitSrch.click();
	}
	
	function checkForm()
	{
		totalrow	= document.getElementById('totalrow').value;
		TTK_STAT	= document.getElementById('TTK_STAT').value;
		TTK_AMOTH	= document.getElementById('TTK_AMOUNT_OTH').value;
		chkTotal	= document.getElementById('chkTotal').checked;
		
		if(totalrow == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		
		if(TTK_STAT != 6 && TTK_STAT != 9)
		{
			if(chkTotal == false)
			{
				swal('<?php echo $alert3; ?>',
				{
					icon: "warning",
				});
				checkTotalTTK();
				return false;
			}
		}
		
		if(TTK_AMOTH > 0)
		{
			TTK_ACC_OTH	= document.getElementById('TTK_ACC_OTH').value;
			if(TTK_ACC_OTH == '')
			{
				swal('<?php echo $alert4; ?>',
			{
				icon: "warning",
			});
				return false;
			}
		}
		
		if(TTK_STAT == 6 || TTK_STAT == 9)
		{
			TTK_NOTES1	= document.getElementById('TTK_NOTES1').value;
			if(TTK_NOTES1 == '')
			{
				swal('<?php echo $alert1; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					document.getElementById('TTK_NOTES1').focus();
				});
				return false;
			}
		}
		
		TTK_AMOUNT	= 0;
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('data'+i+'TTK_REF1_AM');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			/*if(theObj != null)
			{
				IR_AMOUNTnT	= document.getElementById('data'+i+'TTK_REF1_AM').value;
				TTK_AMOUNT	= parseFloat(TTK_AMOUNT) + parseFloat(IR_AMOUNTnT);
				document.getElementById('TTK_AMOUNT').value = TTK_AMOUNT;
			}*/
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
	
	/*function add_IR(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		ilvl = arrItem[1];
		
		var TTK_NUM		= '<?php echo $TTK_NUM; ?>';

		TTK_REF1		= arrItem[0];	// IR NUM OR OPN NUM 					- IR_NUM
		TTK_REF1_DATE	= arrItem[1];	//										- IR_DATEV
		TTK_REF1_DATED	= arrItem[2];	// Due Date 							- IR_DUEDATEV
		TTK_REF2		= arrItem[3];	//										- PO_NUM
		TTK_REF2_DATE	= arrItem[4];	//										- PO_DATEV
		TTK_REF1_AM		= arrItem[5];	// Total sebl. Disc m PPn				- IR_AMOUNT
		TTK_REF1_PPN	= arrItem[6];	// PPn 									- GTTax
		TTK_REF1_TOT	= arrItem[7];	// Total Inc Tax n Disc 				- IR_AMOUNTwPPN
		TTK_DESC		= arrItem[8];	//										- IR_NOTE
		TTK_REF3_CODE	= arrItem[9];	// IR CODE OR OPN CODE 					- IR_CODE
		TTK_REF4_CODE	= arrItem[10];	// PO CODE OR SPK CODE 					- PO_CODE / WO_CODE
		TTK_REF1_RET	= arrItem[11];	// RETENSI 								- OPNH_RETAMN
		TTK_REF1_POT	= arrItem[12];	// POTONGAN 							- TotDisc
		TTK_REF1_DPB	= 0;
		
		TTK_CATEG		= document.getElementById('TTK_CATEG').value;
		TTK_REF1_TOT1	= parseFloat(TTK_REF1_TOT);
		if(TTK_CATEG == 'IR')
		{
			//TTK_REF1_TOT1	= parseFloat(TTK_REF1_TOT) + parseFloat(TTK_REF1_PPN);
			//TTK_REF1_TOT1	= parseFloat(TTK_REF1_TOT) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_RET) - parseFloat(TTK_REF1_POT);
			TTK_REF1_TOT1	= parseFloat(TTK_REF1_TOT);
		}
		else if(TTK_CATEG == 'OPN')
		{
			TTK_REF1_DPB	= arrItem[13];
			//TTK_REF1_TOT1	= parseFloat(TTK_REF1_TOT) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_RET) - parseFloat(TTK_REF1_POT);
			//TTK_REF1_TOT1	= parseFloat(TTK_REF1_TOT) + parseFloat(TTK_REF1_RET);
			// TIDAK PERLU DITAMBAH RETENSI TAPI DITAMBAH PPN : 191016
			// RETENSI DAN PENGEMBALIAN DP JUST INFO
			TTK_REF1_TOT1	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_POT);
		}
		
		TTK_REF1_TOT	= parseFloat(TTK_REF1_TOT1);
		
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		
		intIndex = parseInt(objTable.rows.length);
		document.frm.rowCount.value = intIndex;
		
		objTR 		= objTable.insertRow(intTable);
		objTR.id 	= 'tr_' + intIndex;
		
		// Checkbox
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		//objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_REF3_CODE+'<input type="hidden" id="data'+intIndex+'TTK_REF1" name="data['+intIndex+'][TTK_REF1]" value="'+TTK_REF1+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_NUM" name="data['+intIndex+'][TTK_NUM]" value="'+TTK_NUM+'" class="form-control" style="max-width:300px;">';
		
		// TTK_REF1_DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_REF1_DATE+'<input type="hidden" id="data'+intIndex+'TTK_REF1_DATE" name="data['+intIndex+'][TTK_REF1_DATE]" value="'+TTK_REF1_DATE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF1_DATED" name="data['+intIndex+'][TTK_REF1_DATED]" value="'+TTK_REF1_DATED+'" class="form-control" style="max-width:300px;">';
		
		// TTK_REF2_DATE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		//objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_REF4_CODE+'<input type="hidden" id="data'+intIndex+'TTK_REF2" name="data['+intIndex+'][TTK_REF2]" value="'+TTK_REF2+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_DATE" name="data['+intIndex+'][TTK_REF2_DATE]" value="'+TTK_REF2_DATE+'" class="form-control" style="max-width:300px;">';
		
		// TTK_DESC
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'left';
		// objTD.noWrap = true;
		objTD.innerHTML = ''+TTK_DESC+'<input type="hidden" id="data'+intIndex+'TTK_DESC" name="data['+intIndex+'][TTK_DESC]" value="'+TTK_DESC+'" class="form-control" style="max-width:300px;">';
		
		// IR_AMOUNT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_AM)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_AM" name="data['+intIndex+'][TTK_REF1_AM]" value="'+TTK_REF1_AM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="TTK_REF1_AM'+intIndex+'" name="TTK_REF1_AM'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_AM)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;" readonly>';
		
		// DPBACK_AMOUNT IF OPNAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_DPB)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_DPB" name="data['+intIndex+'][TTK_REF1_DPB]" value="'+TTK_REF1_DPB+'" class="form-control" style="min-width:100px;"><input type="hidden" id="TTK_REF1_DPB'+intIndex+'" name="TTK_REF1_DPB'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_DPB)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;" readonly>';
		
		// RET_AMOUNT IF OPNAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_RET)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_RET" name="data['+intIndex+'][TTK_REF1_RET]" value="'+TTK_REF1_RET+'" class="form-control" style="min-width:100px;"><input type="hidden" id="TTK_REF1_RET'+intIndex+'" name="TTK_REF1_RET'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_RET)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;" readonly>';
		
		// POT_AMOUNT IF OPNAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_POT)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_POT" name="data['+intIndex+'][TTK_REF1_POT]" value="'+TTK_REF1_POT+'" class="form-control" style="min-width:100px; text-align:right;"><input type="hidden" id="TTK_REF1_POT'+intIndex+'" name="TTK_REF1_POT'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_POT)),2))+'" class="form-control" style="min-width:100px; text-align:right;" onKeyPress="return isIntOnlyNew(event);" onBlur="getPOT(this, '+intIndex+')">';
		
		// CHECK
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		if(TTK_REF1_PPN > 0)
		{
			objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')" disabled checked>';
		}
		else
		{
			objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')" style="display: none>';
		}
		
		// GTTax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_PPN" name="data['+intIndex+'][TTK_REF1_PPN]" value="'+TTK_REF1_PPN+'" class="form-control" style="max-width:300px;"><input type="hidden" id="TTK_REF1_PPN'+intIndex+'" name="TTK_REF1_PPN'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2))+'" class="form-control" style="min-width:100px; max-width:300px; text-align:right;" readonly>';
		
		// GTotal
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2))+'<input type="hidden" id="TTK_REF1_TOT'+intIndex+'" name="TTK_REF1_TOT'+intIndex+'" value="'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2))+'" class="form-control" style="min-width:120px; max-width:300px; text-align:right;" readonly>';
		
		document.getElementById('totalrow').value = intIndex;
	}*/
	
	function add_DETIL(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		ilvl = arrItem[1];
		
		var TTK_NUM		= '<?php echo $TTK_NUM; ?>';

		OPNH_NUM		= arrItem[0];	//
		OPNH_CODE		= arrItem[1];	//
		OPNH_DATE		= arrItem[2];	//
		OPNH_DATEV		= arrItem[3];	//
		OPNH_NOTE		= arrItem[4];	//
		WO_NUM			= arrItem[5];	//
		WO_CODE			= arrItem[6];	//
		WO_DATE			= arrItem[7];	//
		WO_DUEDATE		= arrItem[8];	//
		OPNH_AMOUNT		= arrItem[9];	//
		OPNH_AMOUNTPPN	= arrItem[10];	//
		OPNH_AMOUNTPPH	= arrItem[11];	//
		OPNH_RETAMN		= arrItem[12];	//
		OPNH_DPVAL		= arrItem[13];	//
		OPNH_POT		= arrItem[14];	//
		TAXCODE_PPN		= arrItem[15];	//
		TAXCODE_PPH		= arrItem[16];	//
		SJ_NUM 			= arrItem[17];

		TTK_REF1_SPLINV	= "";
		TTK_REF1_PPNNO	= "";

		$('#datepicker2').datepicker('setDate', new Date(WO_DUEDATE));
		$('#datepicker3').datepicker('setDate', new Date(WO_DUEDATE));
		$('#datepickerX').datepicker('setDate', new Date(WO_DUEDATE));
		
		//console.log('a')
		TTK_CATEG		= document.getElementById('TTK_CATEG').value;

		GTOTALROW		= parseFloat(OPNH_AMOUNT) + parseFloat(OPNH_AMOUNTPPN) - parseFloat(OPNH_AMOUNTPPH) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_POT);
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		//console.log('c')
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// TTK_REF1_NUM : OPN NUM/CODE
			objTD = objTR.insertCell(objTR.cells.length);
				objTD.align = "center";
				//objTD.noWrap = true;
				objTD.innerHTML = ''+OPNH_CODE+'<input type="hidden" id="data'+intIndex+'TTK_NUM" name="data['+intIndex+'][TTK_NUM]" value="'+TTK_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF1_NUM" name="data['+intIndex+'][TTK_REF1_NUM]" value="'+OPNH_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF1_CODE" name="data['+intIndex+'][TTK_REF1_CODE]" value="'+OPNH_CODE+'" class="form-control" style="max-width:300px;">';
			
		// TTK_REF1_DATE : TGL. OPN
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = ''+OPNH_DATEV+'<input type="hidden" id="data'+intIndex+'TTK_REF1_DATE" name="data['+intIndex+'][TTK_REF1_DATE]" value="'+OPNH_DATE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF1_DATED" name="data['+intIndex+'][TTK_REF1_DATED]" value="'+OPNH_DATE+'" class="form-control" style="max-width:300px;">';
		
		//console.log('d')
		// TTK_REF2_DATE : KODE WO.
		// DIGANTI DENGAN NOMOR SFAKTUR SUPPLIER (30 NOP 2021. REQ BY DEDE UBAY). TTK_REF1_SPLINV
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'left';
			objTD.style.display 	= 'none';
			//objTD.noWrap = true;
			/*objTD.innerHTML = ''+WO_CODE+'<input type="hidden" id="data'+intIndex+'TTK_REF2_NUM" name="data['+intIndex+'][TTK_REF2_NUM]" value="'+WO_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_CODE" name="data['+intIndex+'][TTK_REF2_CODE]" value="'+WO_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_DATE" name="data['+intIndex+'][TTK_REF2_DATE]" value="'+WO_DATE+'" class="form-control" style="max-width:300px;">';*/
			objTD.innerHTML = '<input type="text" id="data'+intIndex+'TTK_REF1_SPLINV" name="data['+intIndex+'][TTK_REF1_SPLINV]" value="'+TTK_REF1_SPLINV+'" class="form-control" style="min-width:100px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_NUM" name="data['+intIndex+'][TTK_REF2_NUM]" value="'+WO_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_CODE" name="data['+intIndex+'][TTK_REF2_CODE]" value="'+WO_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'TTK_REF2_DATE" name="data['+intIndex+'][TTK_REF2_DATE]" value="'+WO_DATE+'" class="form-control" style="max-width:300px;">';
		
		// TTK_REF1_PPNNO : NO FAKTUR PPN
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'left';
			objTD.style.display 	= 'none';
			//objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="data'+intIndex+'TTK_REF1_PPNNO" name="data['+intIndex+'][TTK_REF1_PPNNO]" value="'+TTK_REF1_PPNNO+'" class="form-control" style="min-width:100px;">';
		
		// TTK_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'left';
			// objTD.noWrap = true;
			objTD.innerHTML = ''+OPNH_NOTE+'<input type="hidden" id="data'+intIndex+'TTK_DESC" name="data['+intIndex+'][TTK_DESC]" value="'+OPNH_NOTE+'" class="form-control" style="max-width:300px;">';
		
		//console.log('e')
		// TTK_REF1_AM
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_AM" name="data['+intIndex+'][TTK_REF1_AM]" value="'+OPNH_AMOUNT+'" class="form-control" style="max-width:300px;">';
		
		//console.log('h')
		// TTK_REF1_POT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_POT)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_POT" name="data['+intIndex+'][TTK_REF1_POT]" value="'+OPNH_POT+'" class="form-control" style="min-width:100px; text-align:right;">';
		
		// TTK_REF1_PPN
			OPNH_AMOUNTPPNV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTPPN)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTPPN)),2))+'<input type="hidden" id="TTK_REF1_PPN'+intIndex+'" value="'+OPNH_AMOUNTPPNV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgTAX(this, '+intIndex+')"><input type="hidden" id="data'+intIndex+'TTK_REF1_PPN" name="data['+intIndex+'][TTK_REF1_PPN]" value="'+OPNH_AMOUNTPPN+'" class="form-control" style="min-width:100px;"><input type="hidden" id="data'+intIndex+'TAXCODE_PPN" name="data['+intIndex+'][TAXCODE_PPN]" value="'+TAXCODE_PPN+'" class="form-control" style="min-width:100px;">';
		
		// TTK_REF1_DPB
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_DPB" name="data['+intIndex+'][TTK_REF1_DPB]" value="'+OPNH_DPVAL+'" class="form-control" style="min-width:100px;">';
		
		//console.log('g')
		// TTK_REF1_RET
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETAMN)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_RET" name="data['+intIndex+'][TTK_REF1_RET]" value="'+OPNH_RETAMN+'" class="form-control" style="min-width:100px;">';
		
		//console.log('f')
		// TTK_REF1_PPH
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = ''+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTPPH)),2))+'<input type="hidden" id="data'+intIndex+'TTK_REF1_PPH" name="data['+intIndex+'][TTK_REF1_PPH]" value="'+OPNH_AMOUNTPPH+'" class="form-control" style="min-width:100px;"><input type="hidden" id="data'+intIndex+'TAXCODE_PPH" name="data['+intIndex+'][TAXCODE_PPH]" value="'+TAXCODE_PPH+'" class="form-control" style="min-width:100px;">';
		
		//console.log('i')
		// CHECK
			/*objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			if(OPNH_AMOUNTPPN > 0)
			{
				objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')" disabled checked>';
			}
			else
			{
				objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')" style="display: none>';
			}*/
		
		//console.log('j')
		// GTotal
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<span id="data'+intIndex+'TTK_REF1_GTOTV">'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTALROW)),2))+'</span><input type="hidden" id="data'+intIndex+'TTK_REF1_GTOT" name="data['+intIndex+'][TTK_REF1_GTOT]" value="'+GTOTALROW+'" class="form-control" style="min-width:100px; text-align:right;"><input type="hidden" id="data'+intIndex+'TTK_ISCOST" name="data['+intIndex+'][TTK_ISCOST]" value="0" class="form-control" style="min-width:100px; text-align:right;">';
		
		document.getElementById('totalrow').value = intIndex;
	}
	
	function add_DETILEXP(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		ilvl = arrItem[1];
		
		var TTK_NUM		= '<?php echo $TTK_NUM; ?>';

		OPNH_NUM		= arrItem[0];	//
		OPNH_CODE		= arrItem[1];	//
		OPNH_DATE		= arrItem[2];	//
		OPNH_DATEV		= arrItem[3];	//
		OPNH_NOTE		= arrItem[4];	//
		WO_NUM			= arrItem[5];	//
		WO_CODE			= arrItem[6];	//
		WO_DATE			= arrItem[7];	//
		WO_DUEDATE		= arrItem[8];	//
		OPNH_AMOUNT		= arrItem[9];	//
		OPNH_AMOUNTPPN	= arrItem[10];	//
		OPNH_AMOUNTPPH	= arrItem[11];	//
		OPNH_RETAMN		= arrItem[12];	//
		OPNH_DPVAL		= arrItem[13];	//
		OPNH_POT		= arrItem[14];	//
		TAXCODE_PPN		= arrItem[15];	//
		TAXCODE_PPH		= arrItem[16];	//

		TTK_REF1_SPLINV	= "";
		TTK_REF1_PPNNO	= "";

		$('#datepicker2').datepicker('setDate', new Date(WO_DUEDATE));
		$('#datepicker3').datepicker('setDate', new Date(WO_DUEDATE));
		$('#datepickerX').datepicker('setDate', new Date(WO_DUEDATE));
		
		//console.log('a')
		TTK_CATEG		= document.getElementById('TTK_CATEG').value;

		GTOTALROW		= parseFloat(OPNH_AMOUNT) + parseFloat(OPNH_AMOUNTPPN) - parseFloat(OPNH_AMOUNTPPH) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_POT);
		
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrowUA').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl_exp');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		//console.log('c')
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRowUA('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// TTK_REF1_NUM : OPN NUM/CODE
			objTD = objTR.insertCell(objTR.cells.length);
				objTD.align = "center";
				//objTD.noWrap = true;
				objTD.innerHTML = ''+OPNH_CODE+'<input type="hidden" id="dataUA'+intIndex+'TTK_NUM" name="dataUA['+intIndex+'][TTK_NUM]" value="'+TTK_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF1_NUM" name="dataUA['+intIndex+'][TTK_REF1_NUM]" value="'+OPNH_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF1_CODE" name="dataUA['+intIndex+'][TTK_REF1_CODE]" value="'+OPNH_CODE+'" class="form-control" style="max-width:300px;">';
			
		// TTK_REF1_DATE : TGL. OPN
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'center';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = ''+OPNH_DATEV+'<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_DATE" name="dataUA['+intIndex+'][TTK_REF1_DATE]" value="'+OPNH_DATE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF1_DATED" name="dataUA['+intIndex+'][TTK_REF1_DATED]" value="'+OPNH_DATE+'" class="form-control" style="max-width:300px;">';
		
		//console.log('d')
		// TTK_REF2_DATE : KODE WO.
		// DIGANTI DENGAN NOMOR SFAKTUR SUPPLIER (30 NOP 2021. REQ BY DEDE UBAY). TTK_REF1_SPLINV
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'left';
			objTD.style.display 	= 'none';
			//objTD.noWrap = true;
			/*objTD.innerHTML = ''+WO_CODE+'<input type="hidden" id="dataUA'+intIndex+'TTK_REF2_NUM" name="dataUA['+intIndex+'][TTK_REF2_NUM]" value="'+WO_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF2_CODE" name="dataUA['+intIndex+'][TTK_REF2_CODE]" value="'+WO_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF2_DATE" name="dataUA['+intIndex+'][TTK_REF2_DATE]" value="'+WO_DATE+'" class="form-control" style="max-width:300px;">';*/
			objTD.innerHTML = '<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_SPLINV" name="dataUA['+intIndex+'][TTK_REF1_SPLINV]" value="'+TTK_REF1_SPLINV+'" class="form-control" style="min-width:100px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF2_NUM" name="dataUA['+intIndex+'][TTK_REF2_NUM]" value="'+WO_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF2_CODE" name="dataUA['+intIndex+'][TTK_REF2_CODE]" value="'+WO_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="dataUA'+intIndex+'TTK_REF2_DATE" name="dataUA['+intIndex+'][TTK_REF2_DATE]" value="'+WO_DATE+'" class="form-control" style="max-width:300px;">';
		
		// TTK_REF1_PPNNO : NO FAKTUR PPN
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'left';
			objTD.style.display 	= 'none';
			//objTD.noWrap = true;
			objTD.innerHTML = '<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_PPNNO" name="dataUA['+intIndex+'][TTK_REF1_PPNNO]" value="'+TTK_REF1_PPNNO+'" class="form-control" style="min-width:100px;">';
		
		// TTK_DESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'left';
			// objTD.noWrap = true;
			objTD.innerHTML = ''+OPNH_NOTE+'<input type="hidden" id="dataUA'+intIndex+'TTK_DESC" name="dataUA['+intIndex+'][TTK_DESC]" value="'+OPNH_NOTE+'" class="form-control" style="max-width:300px;">';
		
		//console.log('e')
		// TTK_REF1_AM
			OPNH_AMOUNTV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="TTK_REF1_AM'+intIndex+'" value="'+OPNH_AMOUNTV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgUA(this, '+intIndex+')"><input type="hidden" id="dataUA'+intIndex+'TTK_REF1_AM" name="dataUA['+intIndex+'][TTK_REF1_AM]" value="'+OPNH_AMOUNT+'" class="form-control" style="min-width:100px;">';
		
		//console.log('h')
		// TTK_REF1_POT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '-<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_POT" name="dataUA['+intIndex+'][TTK_REF1_POT]" value="'+OPNH_POT+'" class="form-control" style="min-width:100px; text-align:right;">';
		
		// TTK_REF1_PPN
			OPNH_AMOUNTPPNV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTPPN)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '-<input type="hidden" id="TTK_REF1_PPN'+intIndex+'" value="'+OPNH_AMOUNTPPNV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgTAX(this, '+intIndex+')"><input type="hidden" id="dataUA'+intIndex+'TTK_REF1_PPN" name="dataUA['+intIndex+'][TTK_REF1_PPN]" value="'+OPNH_AMOUNTPPN+'" class="form-control" style="min-width:100px;"><input type="hidden" id="dataUA'+intIndex+'TAXCODE_PPN" name="dataUA['+intIndex+'][TAXCODE_PPN]" value="'+TAXCODE_PPN+'" class="form-control" style="min-width:100px;">';
		
		//console.log('f')
		// TTK_REF1_PPH
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '-<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_PPH" name="dataUA['+intIndex+'][TTK_REF1_PPH]" value="'+OPNH_AMOUNTPPH+'" class="form-control" style="min-width:100px;"><input type="hidden" id="dataUA'+intIndex+'TAXCODE_PPH" name="dataUA['+intIndex+'][TAXCODE_PPH]" value="'+TAXCODE_PPH+'" class="form-control" style="min-width:100px;">';
		
		// TTK_REF1_DPB
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '-<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_DPB" name="dataUA['+intIndex+'][TTK_REF1_DPB]" value="'+OPNH_DPVAL+'" class="form-control" style="min-width:100px;">';
		
		//console.log('g')
		// TTK_REF1_RET
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign	= 'right';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '-<input type="hidden" id="dataUA'+intIndex+'TTK_REF1_RET" name="dataUA['+intIndex+'][TTK_REF1_RET]" value="'+OPNH_RETAMN+'" class="form-control" style="min-width:100px;">';
		
		//console.log('i')
		// CHECK
			/*objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			if(OPNH_AMOUNTPPN > 0)
			{
				objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')" disabled checked>';
			}
			else
			{
				objTD.innerHTML = '<input type="checkbox" name="chkTotal'+intIndex+'" id="chkTotal'+intIndex+'" onClick="checkPPn('+intIndex+')" style="display: none>';
			}*/
		
		//console.log('j')
		// GTotal
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign 	= 'right';
			objTD.style.display 	= 'none';
			objTD.noWrap = true;
			objTD.innerHTML = '<span id="dataUA'+intIndex+'TTK_REF1_GTOTV">'+doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOTALROW)),2))+'</span><input type="hidden" id="dataUA'+intIndex+'TTK_REF1_GTOT" name="dataUA['+intIndex+'][TTK_REF1_GTOT]" value="'+GTOTALROW+'" class="form-control" style="min-width:100px; text-align:right;"><input type="hidden" id="dataUA'+intIndex+'TTK_ISCOST" name="dataUA['+intIndex+'][TTK_ISCOST]" value="1" class="form-control" style="min-width:100px; text-align:right;">';
		
		document.getElementById('totalrowUA').value = intIndex;
	}
	
	function add_TAXNO() 
	{
		var TTK_NUM			= '<?php echo $TTK_NUM; ?>';
		var TTKT_TAXNO 		= "";
		var TTKT_SPLINVNO 	= "";
		var TTKT_SPLINVVAL 	= 0;
		var TTKT_AMOUNT 	= 0;
		var TTKT_TAXAMOUNT 	= 0;
		let totalrow 		= document.getElementById('totalrow').value;
		let TTKT_REF1_AM 	= 0;

		// Clear check total
		$("#chkTotal").prop("checked", false);

		if(totalrow == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		else
		{
			// Document No. 21.002/MN-IT.NKE /XII/2021 => point 13
			// nilai Faktur default diambil dari total TTK, dan nilai Pajak diambi secara default 10% dari nilai Fakturnya.
			for(let i = 1; i <= totalrow; i++ ) {
				let TTK_REF1_AM 	= document.getElementById('data'+i+'TTK_REF1_AM').value;
				let TTK_REF1_RET 	= document.getElementById('data'+i+'TTK_REF1_RET').value;
				let TTK_REF1_DPB 	= document.getElementById('data'+i+'TTK_REF1_DPB').value;
				// TTKT_REF1_AM 	= parseFloat(TTKT_REF1_AM) + parseFloat(TTK_REF1_AM);
				TTKT_REF1_AM 		= parseFloat(TTKT_REF1_AM) + parseFloat(TTK_REF1_AM-TTK_REF1_RET-TTK_REF1_DPB); // TOTAL DPP setelah dipotong retensi & POT. DP 
				console.log(TTKT_REF1_AM);
			}
		}
		
		textIn 				= "";
		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrowTAX').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl_tax');
			intTable 		= objTable.rows.length;

			if(intIndex == 1)
			{
				TTKT_AMOUNT 	= TTKT_REF1_AM;
				TTKT_TAXAMOUNT 	= parseFloat(0.11 * TTKT_AMOUNT);
			}

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX

		// Checkbox
			objTD 			= objTR.insertCell(objTR.cells.length);
			objTD.align 	= "center";
			objTD.noWrap 	= true;
			objTD.innerHTML = '<a href="#" onClick="deleteRowTAX('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
			
		// TTKT_SPLINVNO
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_SPLINVNO" name="dataTAX['+intIndex+'][TTKT_SPLINVNO]" value="'+TTKT_SPLINVNO+'" class="form-control" maxlength="100">';

		// TTKT_DATE
			objTD 			= objTR.insertCell(objTR.cells.length);
			objTD.align 	= "center";
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_SPLINVDATE" name="dataTAX['+intIndex+'][TTKT_SPLINVDATE]" value="" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD" maxlength="10">';

		// TTKT_SPLINVVAL
			TTKT_SPLINVVALV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_SPLINVVAL)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="TTKT_SPLINVVAL'+intIndex+'" value="'+TTKT_SPLINVVALV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgKWITVAL(this, '+intIndex+')"><input type="hidden" id="dataTAX'+intIndex+'TTKT_SPLINVVAL" name="dataTAX['+intIndex+'][TTKT_SPLINVVAL]" value="'+TTKT_SPLINVVAL+'" class="form-control" style="min-width:100px;">';
			
		// TTKT_TAXNO
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_TAXNO" name="dataTAX['+intIndex+'][TTKT_TAXNO]" value="'+TTKT_TAXNO+'" class="form-control" onKeyUp="chkMask(this)" placeholder="XXX.XXX-XX.XXXXXXXX" maxlength="19" onChange="chkCont(this, '+intIndex+')">';
		
		// TTKT_DATE
			objTD 			= objTR.insertCell(objTR.cells.length);
			objTD.align 	= "center";
			objTD.innerHTML = '<input type="text" id="dataTAX'+intIndex+'TTKT_DATE" name="dataTAX['+intIndex+'][TTKT_DATE]" value="" class="form-control" onKeyUp="chkDate(this)" placeholder="YYYY-MM-DD" maxlength="10">';

		// TTKT_AMOUNT
			TTKT_AMOUNTV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_AMOUNT)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="TTKT_AMOUNT'+intIndex+'" value="'+TTKT_AMOUNTV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgAMN(this, '+intIndex+')"><input type="hidden" id="dataTAX'+intIndex+'TTKT_AMOUNT" name="dataTAX['+intIndex+'][TTKT_AMOUNT]" value="'+TTKT_AMOUNT+'" class="form-control" style="min-width:100px;">';

		// TTKT_TAXAMOUNT
			var TTKT_TAXAMOUNT 	= 0;
			TTKT_TAXAMOUNTV		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_TAXAMOUNT)),2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" id="TTKT_TAXAMOUNT'+intIndex+'" value="'+TTKT_TAXAMOUNTV+'" class="form-control" style="min-width:100px; text-align: right" onBlur="chgPPN(this, '+intIndex+')"><input type="hidden" id="dataTAX'+intIndex+'TTKT_TAXAMOUNT" name="dataTAX['+intIndex+'][TTKT_TAXAMOUNT]" value="'+TTKT_TAXAMOUNT+'" class="form-control" style="min-width:100px;">';
		
		document.getElementById('totalrowTAX').value = intIndex;
	}

	function chkDate(thisVal)
	{
		var totChar 	= thisVal.value.length;
		var theCont 	= thisVal.value;

		if(totChar == 4)
		{
			theYear 		= theCont;
			thisVal.value 	= thisVal.value+'-';
		}
		else if(totChar == 7)
		{
			theMonth 	= theCont.substring(5, 7);
			if(theMonth > 12)
			{
				swal("Masukan bulan dengan angka : 01 - 12")
				.then(function()
				{
					tYear 			= theCont.substring(0, 5);
					swal.close();
					thisVal.value 	= tYear;
					thisVal.focus();
					return false;
				})
			}
			else
			{
				thisVal.value 	= theCont+'-';
			}
		}
		else
		{
			theDate 	= theCont.substring(8, 10);
			if(theDate > 31)
			{
				swal("Masukan tanggal dengan angka : 01 - 31")
				.then(function()
				{
					tMonth 			= theCont.substring(0, 8);
					swal.close();
					thisVal.value 	= tMonth;
					thisVal.focus();
					return false;
				})
			}
			else
			{
				thisVal.value 	= theCont;
			}
		}
	}

	function chkMask(thisVal)
	{
		var totChar 	= thisVal.value.length;

		if(totChar == 3)
			thisValc 	= thisVal.value+'.';
		else if(totChar == 7)
			thisValc 	= thisVal.value+'-';
		else if(totChar == 10)
			thisValc 	= thisVal.value+'.';
		/*else if(totChar == 19)
		{
			swal("Melebihi batas maksimal penomoran pajak")
			.then(function()
			{
				swal.close();
				thisVal.focus();
				return false;
			})
		}*/
		else
			thisValc 	= thisVal.value;

		thisVal.value = thisValc;
	}

	function chkCont(thisVal, row)
	{
		if(thisVal.value == '000.000-00.00000000')
		{
			swal("Masukan nomo seri faktur pajak dengan benar.",
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				document.getElementById('dataTAX'+row+'TTKT_TAXNO').focus();
				document.getElementById('dataTAX'+row+'TTKT_TAXNO').value 	= '';
			})
		}
	}
	
	function chgUA(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTK_REF1_AM	= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTK_REF1_AM'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_AM)),decFormat));
		document.getElementById('dataUA'+row+'TTK_REF1_AM').value 	= parseFloat(Math.abs(TTK_REF1_AM));
		changeValue_UA(TTK_REF1_AM, row)
	}
	
	function chgKWITVAL(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTKT_SPLINVVAL		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTKT_SPLINVVAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_SPLINVVAL)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_SPLINVVAL').value 	= parseFloat(Math.abs(TTKT_SPLINVVAL));
	}
	
	function chgAMN(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTKT_AMOUNT		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTKT_AMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_AMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_AMOUNT').value 	= parseFloat(Math.abs(TTKT_AMOUNT));

		var TTKT_TAXAMOUNT 	= parseFloat(0.11 * TTKT_AMOUNT);
		
		document.getElementById('TTKT_TAXAMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_TAXAMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_TAXAMOUNT').value 	= parseFloat(Math.abs(TTKT_TAXAMOUNT));
	}
	
	function chgPPN(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTKT_TAXAMOUNT	= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTKT_TAXAMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_TAXAMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_TAXAMOUNT').value 	= parseFloat(Math.abs(TTKT_TAXAMOUNT));

		document.getElementById('chkTotal').checked	 	= true;
		checkTotalTTK();
		/*var TTKT_AMOUNT 	= parseFloat(10 * TTKT_TAXAMOUNT);
		
		document.getElementById('TTKT_AMOUNT'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTKT_AMOUNT)),decFormat));
		document.getElementById('dataTAX'+row+'TTKT_AMOUNT').value 	= parseFloat(Math.abs(TTKT_AMOUNT));*/
	}
	
	function chgTAX(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var TTK_REF1_PPN	= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('TTK_REF1_PPN'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),decFormat));
		document.getElementById('data'+row+'TTK_REF1_PPN').value 	= parseFloat(Math.abs(TTK_REF1_PPN));
		changeValue_new(TTK_REF1_PPN, row)
	}
	
	function checkTotalTTK()
	{
		totalrow		= document.getElementById('totalrow').value;
		TTKT_SPLINVVAL	= 0;
		TTK_AMOUNT		= 0;
		TTK_AMOUNT_PPN	= 0;
		TTK_AMOUNT_PPH 	= 0;
		TTK_AMOUNT_DPB 	= 0;
		TTK_AMOUNT_RET	= 0;
		TTK_AMOUNT_POT 	= 0;
		TTK_GTOTAL		= 0;
		TTK_TOTPOT 		= 0;
		console.log('a')
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('data'+i+'TTK_REF1_AM');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				TTK_REF1_AM		= document.getElementById('data'+i+'TTK_REF1_AM').value;
				TTK_AMOUNT		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_REF1_AM);

				TTK_AMOUNT_PPN 	= 0; 	// TOTAL PPN DIDAPATKAN DARI TOTAL NO SERI PPN

				TTK_REF1_PPH	= document.getElementById('data'+i+'TTK_REF1_PPH').value;
				TTK_AMOUNT_PPH	= parseFloat(TTK_AMOUNT_PPH) + parseFloat(TTK_REF1_PPH);

				TTK_REF1_DPB	= document.getElementById('data'+i+'TTK_REF1_DPB').value;
				TTK_AMOUNT_DPB	= parseFloat(TTK_AMOUNT_DPB) + parseFloat(TTK_REF1_DPB);

				TTK_REF1_RET	= document.getElementById('data'+i+'TTK_REF1_RET').value;
				TTK_AMOUNT_RET	= parseFloat(TTK_AMOUNT_RET) + parseFloat(TTK_REF1_RET);

				//TTK_REF1_POT	= document.getElementById('data'+i+'TTK_REF1_POT').value; 		// DISKON JUST INFO
				TTK_REF1_POT	= 0;
				TTK_AMOUNT_POT	= parseFloat(TTK_AMOUNT_POT) + parseFloat(TTK_REF1_POT);

				TTK_TOTPOT 		= parseFloat(TTK_TOTPOT) + parseFloat(TTK_AMOUNT_PPH) + parseFloat(TTK_AMOUNT_DPB) + parseFloat(TTK_AMOUNT_RET) + parseFloat(TTK_AMOUNT_POT);
			}
		}

		TTK_AMOUNTUA	= 0;
		var varTUA = document.getElementById('totalrowUA');
		if (typeof varTUA !== 'undefined' && varTUA !== null)
		{
			totalrowUA		= document.getElementById('totalrowUA').value;
			for(i=1; i<=totalrowUA; i++)
			{
				let myObj 	= document.getElementById('dataUA'+i+'TTK_REF1_AM');
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					TTK_REF1_AMUA	= document.getElementById('dataUA'+i+'TTK_REF1_AM').value;
					TTK_AMOUNTUA	= parseFloat(TTK_AMOUNTUA) + parseFloat(TTK_REF1_AMUA);
				}
			}
		}

		TTK_AMOUNTAX	= 0;
		TTK_AMOUNSPL	= 0;
		var varTAX = document.getElementById('totalrowTAX');
		if(varTAX.value == 0)
		{
			swal("Anda belum menambahkan satupun kwitansi dari supplier",
			{
				icon: "warning",
			});
			document.getElementById('chkTotal').checked 		= false;
			document.getElementById('btnSave').style.display    = 'none';
			return false;
		}

		if (typeof varTAX !== 'undefined' && varTAX !== null)
		{
			totalrowTAX		= document.getElementById('totalrowTAX').value;
			console.log('totalrowTAX = '+totalrowTAX)
			for(i=1; i<=totalrowTAX; i++)
			{
				let myObj 	= document.getElementById('dataTAX'+i+'TTKT_TAXAMOUNT');
				var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

				//console.log(i+' = '+ theObj)
				
				if(theObj != null)
				{
					TTKT_SPLINVNO	= document.getElementById('dataTAX'+i+'TTKT_SPLINVNO').value;
					if(TTKT_SPLINVNO == '')
					{
						swal("Nomor kwitansi supplier tidak boleh kosong",
						{
							icon: "warning",
						})
						.then(function()
						{
							swal.close();
							document.getElementById('chkTotal').checked = false;
							document.getElementById('dataTAX'+i+'TTKT_SPLINVNO').focus();
							document.getElementById('btnSave').style.display    = 'none';
						})
						return false;
					}

					TTKT_SPLINVVAL	= document.getElementById('dataTAX'+i+'TTKT_SPLINVVAL').value;
					if(TTKT_SPLINVVAL == 0)
					{
						swal("Nilai kwitansi supplier tidak boleh nol",
						{
							icon: "warning",
						})
						.then(function()
						{
							swal.close();
							document.getElementById('chkTotal').checked = false;
							document.getElementById('TTKT_SPLINVVAL'+i).focus();
							document.getElementById('btnSave').style.display    = 'none';
						})
						return false;
					}

					TTK_AMOUNSPL	= parseFloat(TTK_AMOUNSPL) + parseFloat(TTKT_SPLINVVAL);

					TTKT_TAXAMOUNT	= document.getElementById('dataTAX'+i+'TTKT_TAXAMOUNT').value;
					TTK_AMOUNTAX	= parseFloat(TTK_AMOUNTAX) + parseFloat(TTKT_TAXAMOUNT);
				}
			}
		}

		TTK_AMOUNT_SPL 		= parseFloat(TTK_AMOUNSPL);
		TTK_AMOUNT_PPN 		= parseFloat(TTK_AMOUNTAX);

		// TTK AMOUNT DIGANTI DENGAN NILAI KWITANSI SUPPLIER
		//TTK_AMOUNT 		= parseFloat(TTK_AMOUNSPL);

		console.log('TTK_AMOUNT_PPN = '+TTK_AMOUNT_PPN)
		//document.getElementById('chkTotal').checked = true;

		//if(TTK_AMOUNT_PPN == 0)
			//var TTK_AMOUNT_PPN 	= document.getElementById('TTK_AMOUNT_PPN').value;

		if(TTK_AMOUNT_POT == 0)
			var TTK_AMOUNT_POT 	= document.getElementById('TTK_AMOUNT_POT').value;
			
		console.log('d2')
		document.getElementById('TTK_AMOUNT').value 		= TTK_AMOUNT;
		document.getElementById('TTK_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT)),2));
		
		console.log('d3 = '+TTK_AMOUNT_PPN)
		document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_AMOUNT_PPN;
		document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_PPN)),2));
		
		console.log('d4')
		document.getElementById('TTK_AMOUNT_PPH').value 	= TTK_AMOUNT_PPH;
		document.getElementById('TTK_AMOUNT_PPHX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_PPH)),2));
		document.getElementById('TTK_AMOUNT_RET').value 	= TTK_AMOUNT_RET;
		document.getElementById('TTK_AMOUNT_RETX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_RET)),2));

		document.getElementById('TTK_AMOUNT_DPB').value 	= TTK_AMOUNT_DPB;
		document.getElementById('TTK_AMOUNT_DPBX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_DPB)),2));
		
		document.getElementById('TTK_AMOUNT_POT').value 	= TTK_AMOUNT_POT;

		document.getElementById('TTK_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_POT)),2));

		console.log('e')
		/*TTK_GTOTAL		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) - parseFloat(TTK_AMOUNT_PPH) - parseFloat(TTK_AMOUNT_DPB) - parseFloat(TTK_AMOUNT_RET) - parseFloat(TTK_AMOUNT_POT);*/

		console.log('f')
		TTK_CATEG			= document.getElementById('TTK_CATEG').value;
		console.log('f')
		TTK_AMOUNT_OTH		= document.getElementById('TTK_AMOUNT_OTH').value;

		TTK_GTOTAL			= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) - parseFloat(TTK_AMOUNT_PPH) - parseFloat(TTK_AMOUNT_DPB) - parseFloat(TTK_AMOUNT_RET) - parseFloat(TTK_AMOUNT_POT) + parseFloat(TTK_AMOUNTUA);
		
		console.log('g = '+TTK_GTOTAL)
		document.getElementById('TTK_GTOTAL').value 		= TTK_GTOTAL;
		document.getElementById('TTK_GTOTALX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTAL)),2));
		document.getElementById('TTK_TOTKWIT').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_SPL)),2));

		document.getElementById('btnSave').style.display 	= '';
	}
	
	/*function checkPPn(row)
	{
		chkTotal		= document.getElementById('chkTotal'+row).checked;
		if(chkTotal == true)
		{
			TTK_REF1_AM		= document.getElementById('data'+row+'TTK_REF1_AM').value;
			TTK_REF1_PPN	= parseFloat(TTK_REF1_AM) * 0.1;
			document.getElementById('data'+row+'TTK_REF1_PPN').value = TTK_REF1_PPN;
			document.getElementById('TTK_REF1_PPN'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2));		
			TTK_REF1_RET	= document.getElementById('data'+row+'TTK_REF1_RET').value;
			TTK_REF1_POT	= document.getElementById('data'+row+'TTK_REF1_POT').value;
			
			// POTONGAN RETENSI SUDAH DILAKUKAN DI HALAMAN SELECT
				//TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) - parseFloat(TTK_REF1_RET) - parseFloat(TTK_REF1_POT) +parseFloat(TTK_REF1_PPN);
				TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) - parseFloat(TTK_REF1_POT) +parseFloat(TTK_REF1_PPN);

			// HASIL MEETING 27 DES 18 DI MS, RETENSI TIDAK TERMASUK DI TTK, HANYA INFORMASI
			TTK_CATEG		= document.getElementById('TTK_CATEG').value;
			if(TTK_CATEG == 'OPN')
				TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_POT);
			else
				TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_POT);
			
			document.getElementById('TTK_REF1_TOT'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2));
		}
		else
		{
			TTK_REF1_AM		= document.getElementById('data'+row+'TTK_REF1_AM').value;
			TTK_REF1_PPN	= parseFloat(TTK_REF1_AM) * 0;
			document.getElementById('data'+row+'TTK_REF1_PPN').value = TTK_REF1_PPN;
			document.getElementById('TTK_REF1_PPN'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_PPN)),2));			
			TTK_REF1_RET	= document.getElementById('data'+row+'TTK_REF1_RET').value;
			TTK_REF1_POT	= document.getElementById('data'+row+'TTK_REF1_POT').value;
			
			// POTONGAN RETENSI SUDAH DILAKUKAN DI HALAMAN SELECT
				//TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) - parseFloat(TTK_REF1_RET) - parseFloat(TTK_REF1_POT) + parseFloat(TTK_REF1_PPN);
				TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) - parseFloat(TTK_REF1_POT) + parseFloat(TTK_REF1_PPN);

			// HASIL MEETING 27 DES 18 DI MS, RETENSI TIDAK TERMASUK DI TTK, HANYA INFORMASI
			TTK_CATEG		= document.getElementById('TTK_CATEG').value;
			if(TTK_CATEG == 'OPN')
			{
				// POTONGAN RETENSI SUDAH DILAKUKAN DI HALAMAN SELECT
				//TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) - parseFloat(TTK_REF1_RET) - parseFloat(TTK_REF1_POT);
				TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_POT);
			}
			else
			{
				TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) + parseFloat(TTK_REF1_PPN) - parseFloat(TTK_REF1_POT);
			}
			
			document.getElementById('TTK_REF1_TOT'+row).value		 = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),2));
		}
		
		totalrow		= document.getElementById('totalrow').value;
		TTK_AMOUNT		= 0;
		TTK_AMOUNT_RET	= 0;
		TTK_AMOUNT_POT	= 0;
		TTK_AMOUNT_PPN	= 0;
		TTK_GTOTAL		= 0;
		for(i=1; i<=totalrow; i++)
		{
			TTK_REF1_AM		= document.getElementById('data'+i+'TTK_REF1_AM').value;
			TTK_AMOUNT		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_REF1_AM);
			
			TTK_REF1_RET	= document.getElementById('data'+i+'TTK_REF1_RET').value;
			TTK_AMOUNT_RET	= parseFloat(TTK_AMOUNT_RET) + parseFloat(TTK_REF1_RET);
			
			TTK_REF1_POT	= document.getElementById('data'+i+'TTK_REF1_POT').value;
			TTK_AMOUNT_POT	= parseFloat(TTK_AMOUNT_POT) + parseFloat(TTK_REF1_POT);
			
			TTK_REF1_PPN	= document.getElementById('data'+i+'TTK_REF1_PPN').value;
			TTK_AMOUNT_PPN	= parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_REF1_PPN);

			
			document.getElementById('TTK_AMOUNT').value 		= TTK_AMOUNT;
			document.getElementById('TTK_AMOUNTX').value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT)),2));
			
			document.getElementById('TTK_AMOUNT_RET').value 	= TTK_AMOUNT_RET;
			document.getElementById('TTK_AMOUNT_RETX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_RET)),2));
			document.getElementById('TTK_AMOUNT_POT').value 	= TTK_AMOUNT_POT;
			document.getElementById('TTK_AMOUNT_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_POT)),2));
			
			document.getElementById('TTK_AMOUNT_PPN').value 	= TTK_AMOUNT_PPN;
			document.getElementById('TTK_AMOUNT_PPNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_PPN)),2));
		}
		document.getElementById('chkTotal').checked = true;

		// POTONGAN RETENSI SUDAH DILAKUKAN DI HALAMAN SELECT
			//TTK_GTOTAL	= parseFloat(TTK_AMOUNT) - parseFloat(TTK_AMOUNT_RET) - parseFloat(TTK_AMOUNT_POT) + parseFloat(TTK_AMOUNT_PPN);
			TTK_GTOTAL		= parseFloat(TTK_AMOUNT) - parseFloat(TTK_AMOUNT_POT) + parseFloat(TTK_AMOUNT_PPN);

		// HASIL MEETING 27 DES 18 DI MS, RETENSI TIDAK TERMASUK DI TTK, HANYA INFORMASI
		TTK_CATEG			= document.getElementById('TTK_CATEG').value;
		TTK_AMOUNT_OTH	= document.getElementById('TTK_AMOUNT_OTH').value;
		if(TTK_CATEG == 'OPN')
		{
			//TTK_GTOTAL	= parseFloat(TTK_AMOUNT) - parseFloat(TTK_AMOUNT_POT);
			// POTONGAN RETENSI SUDAH DILAKUKAN DI HALAMAN SELECT
				//TTK_GTOTAL	= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_AMOUNT_OTH) - parseFloat(TTK_AMOUNT_RET) - parseFloat(TTK_AMOUNT_POT);
				TTK_GTOTAL		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_AMOUNT_OTH) -parseFloat(TTK_AMOUNT_POT);
				TTK_GTOTALV		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_AMOUNT_OTH) - parseFloat(TTK_AMOUNT_POT);		// ONLY VIEW
		}
		else
		{
			TTK_GTOTAL		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_AMOUNT_OTH) - parseFloat(TTK_AMOUNT_POT);
			TTK_GTOTALV		= parseFloat(TTK_AMOUNT) + parseFloat(TTK_AMOUNT_PPN) + parseFloat(TTK_AMOUNT_OTH) - parseFloat(TTK_AMOUNT_POT);		// ONLY VIEW
		}
		
		document.getElementById('TTK_GTOTAL').value 	= TTK_GTOTAL;
		document.getElementById('TTK_GTOTALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_GTOTALV)),2));
	}*/
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function deleteRowUA(btn)
	{
		var row = document.getElementById("trEXP_" + btn);
		row.remove();
	}
	
	function deleteRowTAX(btn)
	{
		var row = document.getElementById("trTAX_" + btn);
		row.remove();
	}
	
	function countDisp(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISP		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		document.getElementById('ITM_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISP)),decFormat));
		document.getElementById('data'+row+'ITM_DISP').value 	= parseFloat(Math.abs(ITM_DISP));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP		= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		var DISCOUNT		= parseFloat(ITM_DISP * ITM_TOTAL / 100);
		
		document.getElementById('data'+row+'ITM_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISCX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function countDisc(thisVal, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var ITM_DISC		= parseFloat(eval(thisVal).value.split(",").join(""));
		
		var ITM_QTY			= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP		= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		
		var DISCOUNTP		= parseFloat(ITM_DISC / ITM_TOTAL * 100);
		
		document.getElementById('ITM_DISP'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNTP)),decFormat));
		document.getElementById('data'+row+'ITM_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		
		document.getElementById('data'+row+'ITM_DISC').value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISCX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVOLM		= document.getElementById('ITM_QTY'+row);
		changeValue(thisVOLM, row)
	}
	
	function changeValue(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		//var ITM_QTYx 		= eval(thisVal).value.split(",").join("");
		ITM_QTY1			= document.getElementById('ITM_QTY'+row);
		ITM_QTY 			= parseFloat(eval(ITM_QTY1).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_QTY').value = parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		//var ITM_DISP			= document.getElementById('ITM_DISP'+row).value;
		var ITM_QTY				= document.getElementById('ITM_QTY'+row).value;
		var ITM_UNITP			= document.getElementById('data'+row+'ITM_UNITP').value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_UNITP);
		
		var DISCOUNT			= parseFloat(document.getElementById('data'+row+'ITM_DISC').value);
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		
		var theTAX				= document.getElementById('data'+row+'TAXCODE1').value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.11;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= 0;
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= 0;
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+row+'TAX_AMOUNT_PPn1').value 	= 0;
			document.getElementById('data'+row+'TAX_AMOUNT_PPh1').value 	= 0;
		}
		document.getElementById('data'+row+'ITM_AMOUNT').value 		= parseFloat(Math.abs(G_itmTot));
		document.getElementById('data'+row+'ITM_AMOUNT_BASE').value = parseFloat(Math.abs(G_itmTot));
		document.getElementById('ITM_AMOUNTX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow		= document.getElementById("totalrow").value;	
		INV_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_AMOUNT');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			//console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				GT_ITMPRICE		= document.getElementById('data'+i+'ITM_AMOUNT').value;
				INV_TOTAL_AM	= parseFloat(INV_TOTAL_AM) + parseFloat(GT_ITMPRICE);
			}
		}
		
		document.getElementById('INV_AMOUNT').value = INV_TOTAL_AM;
	}
	
	function getAmountOthEXP()
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		TTK_AMOUNT_OTH 		= parseFloat(eval(TTK_AMOUNT_OTHX).value.split(",").join(""));
		document.getElementById('TTK_AMOUNT_OTH').value = parseFloat(Math.abs(TTK_AMOUNT_OTH));
		document.getElementById('TTK_AMOUNT_OTHX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_AMOUNT_OTH)),decFormat));

		if(TTK_AMOUNT_OTH == 0)
		{
			$('#TTK_ACC_OTH').val('');
			$('#TTK_ACC_OTH').trigger('change');
		}
		console.log('a')
		checkTotalTTK();
		console.log('b')
	}
	
	function changeValue_UA(TTK_REF1_PPN, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		var TTK_REF1_AM		= document.getElementById('dataUA'+row+'TTK_REF1_AM').value;
		//var TTK_REF1_POT	= document.getElementById('dataUA'+row+'TTK_REF1_POT').value; 		// DISKON JUST INFO
		var TTK_REF1_POT	= 0;
		var TTK_REF1_PPH	= document.getElementById('dataUA'+row+'TTK_REF1_PPH').value;
		var TTK_REF1_DPB	= document.getElementById('dataUA'+row+'TTK_REF1_DPB').value;
		var TTK_REF1_RET	= document.getElementById('dataUA'+row+'TTK_REF1_RET').value;

		document.getElementById('dataUA'+row+'TTK_REF1_GTOT').value 		= parseFloat(TTK_REF1_AM);
		document.getElementById('dataUA'+row+'TTK_REF1_GTOTV').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_AM)),decFormat));
	}
	
	function changeValue_new(TTK_REF1_PPN, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		
		var TTK_REF1_AM		= document.getElementById('data'+row+'TTK_REF1_AM').value;
		// var TTK_REF1_POT	= document.getElementById('data'+row+'TTK_REF1_POT').value; 		// DISKON JUST INFO
		var TTK_REF1_POT	= 0;
		var TTK_REF1_PPH	= document.getElementById('data'+row+'TTK_REF1_PPH').value;
		var TTK_REF1_DPB	= document.getElementById('data'+row+'TTK_REF1_DPB').value;
		var TTK_REF1_RET	= document.getElementById('data'+row+'TTK_REF1_RET').value;

		var TTK_REF1_TOT	= parseFloat(TTK_REF1_AM) +  parseFloat(TTK_REF1_PPN) -  parseFloat(TTK_REF1_POT) -  parseFloat(TTK_REF1_PPH) -  parseFloat(TTK_REF1_DPB) -  parseFloat(TTK_REF1_RET);

		document.getElementById('data'+row+'TTK_REF1_GTOT').value 		= parseFloat(TTK_REF1_TOT);
		document.getElementById('data'+row+'TTK_REF1_GTOTV').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TTK_REF1_TOT)),decFormat));

		
		/*totalrow		= document.getElementById("totalrow").value;	
		INV_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('data'+i+'ITM_AMOUNT').value;
			INV_TOTAL_AM	= parseFloat(INV_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		
		document.getElementById('INV_AMOUNT').value = INV_TOTAL_AM;*/
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