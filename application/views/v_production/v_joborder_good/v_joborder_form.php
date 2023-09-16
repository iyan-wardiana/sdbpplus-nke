<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= v_joborder_form.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$username 			= $this->session->userdata('username');
$imgemp_filename 	= '';
$imgemp_filenameX 	= '';

$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');

if($task == 'add')
{
	$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
	
	$JO_NUM			= '';
	$JO_CODE 		= '';
	$PRJCODE		= $PRJCODE;
	$JO_DATE		= date('m/d/Y');
	$JO_PRODD		= date('m/d/Y');
	$CUST_CODE		= '';
	$JO_DESC		= '';
	$JO_VOLM		= 0;
	$JO_NOTES		= '';
	$JO_NOTES2		= '';
	$JO_STAT		= 1;
	
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
	}
	
	$yearCur	= date('Y');
	$sqlC		= "tbl_jo_header WHERE Patt_Year = $yearCur AND PRJCODE = '$PRJCODE'";
	$myCount 	= $this->db->count_all($sqlC);
	
	/*$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM tbl_jo_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax 	= $row->maxNumber;
			$myMax 	= $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$myMax 		= $myCount+1;
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
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber1		= "$Pattern_Code$PRJCODE$year$month$days$lastPatternNumb";
	//$DocNumber	= "$DocNumber1"."-D";
	$TRXTIME1		= date('ymdHis');
	$DocNumber		= "$Pattern_Code$PRJCODE-$TRXTIME1";
	
	$JO_NUM			= $DocNumber;	
	$JOCODE			= substr($lastPatternNumb, -4);
	$JOYEAR			= date('y');
	$JOMONTH		= date('m');
	$JO_CODE		= "$Pattern_Code.$JOCODE.$JOYEAR.$JOMONTH"; // MANUAL CODE
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_jo_header~$Pattern_Length";
	$dataTarget		= "JO_CODE";
	$CUST_ADDRESS	= '';
	$SO_NUM			= '';
	$SO_CODE		= '';
	$SO_NOTES1		= '';
}
else
{
	$isSetDocNo 	= 1;
	$JO_NUM 		= $default['JO_NUM'];
	$JO_CODE 		= $default['JO_CODE'];
	$PRJCODE 		= $default['PRJCODE'];
	$JO_DATE 		= $default['JO_DATE'];
	$JO_DATE		= date('m/d/Y', strtotime($JO_DATE));
	$JO_PRODD 		= $default['JO_PRODD'];
	$JO_PRODD		= date('m/d/Y', strtotime($JO_PRODD));
	$SO_NUM 		= $default['SO_NUM'];
	$SO_CODE 		= $default['SO_CODE'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$CUST_DESC 		= $default['CUST_DESC'];
	$CUST_ADDRESS	= $default['CUST_ADDRESS'];
	$JO_DESC 		= $default['JO_DESC'];
	$JO_VOLM 		= $default['JO_VOLM'];
	$JO_AMOUNT 		= $default['JO_AMOUNT'];
	$JO_NOTES 		= $default['JO_NOTES'];
	$JO_NOTES2 		= $default['JO_NOTES2'];
	$JO_STAT 		= $default['JO_STAT'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number 	= $default['Patt_Number'];
}
$FRST_STAT	= $JO_STAT;		// STATUS AWAL

$isDis	= 1;
if($JO_STAT == 1 || $JO_STAT == 4 || $JO_STAT == 7)
{
	$isDis		= 0;
}
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    
    <link href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/build/css/custom.min.css'; ?>" rel="stylesheet">
</head>
	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
		
		if($TranslCode == 'Address')$Address = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'Notes')$Notes = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
		if($TranslCode == 'CustName')$CustName = $LangTransl;
		if($TranslCode == 'Canceled')$Canceled = $LangTransl;
		if($TranslCode == 'Next')$Next = $LangTransl;
		if($TranslCode == 'Prev')$Prev = $LangTransl;
		if($TranslCode == 'Finish')$Finish = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'ProdTotal')$ProdTotal = $LangTransl;
		if($TranslCode == 'Project')$Project = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
		if($TranslCode == 'CustAddres')$CustAddres = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
		if($TranslCode == 'ItemName')$ItemName = $LangTransl;
		if($TranslCode == 'Stock')$Stock = $LangTransl;
		if($TranslCode == 'Ordered')$Ordered = $LangTransl;
		if($TranslCode == 'OrdeList')$OrdeList = $LangTransl;
		if($TranslCode == 'Quantity')$Quantity = $LangTransl;
		if($TranslCode == 'ItemListOrd')$ItemListOrd = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
		if($TranslCode == 'None')$None = $LangTransl;
	endforeach;
	
	if($LangID == 'IND')
	{
		$stepalert1		= "Pilih salah satu Nomor Sales Order yang akan dibuatkan Job Order.";
		$stepalert2		= "Perhatian ...! Cek informasi dokumen dengan detail. Dan isikan data JO dengan benar.";
		$stepalert3		= "Tentukan Barang Jadi (Finish Good) yang akan diproduksi.";
		$stepalert4		= "Pastikan bahwa data yang Anda masukan sudah benar.";
		$docalert1		= "Peringatan";
		$docalert2		= "Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.";
        $isManual		= "Centang untuk kode manual.";
		$Step1Des		= "Orde Penjualan";
		$Step2Des		= "Informasi Dokumen";
		$Step3Des		= "Pemilihan Produk";
		$Step4Des		= "Rekapitulasi";
		
		$alert1			= "Pilih salah satu nomor Sales Order (SO).";
		$alert2			= "Masukan jumlah volume produksi.";
		$alert3			= "Masukan catatan dokumen JO.";
		$alert4			= "Jumlah yang di-JO lebih besar dari sisa SO.";
	}
	else
	{
		$stepalert1		= "Select one of the Sales Order Numbers that will be made a Job Order.";
		$stepalert2		= "Attention ...! Check document information in detail. And fill in the JO data correctly.";
		$stepalert3		= "Please specify Finished Goods to be produced.";
		$stepalert4		= "Make sure that the data you entered is correct.";
		$docalert1		= "Warning";
		$docalert2		= "Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.";
		$isManual		= "Check to manual code.";
		$Step1Des		= "Sales Order";
		$Step2Des		= "Document Information";
		$Step3Des		= "Finish Goods Selection";
		$Step4Des		= "Summary";
		
		$alert1			= "Please select one of Sales Order (SO) Number.";
		$alert2			= "Please input prodcution volume.";
		$alert3			= "Please input Notes of this JO document.";
		$alert4			= "JO Qty is greater than of Remaining Qty.";
	}
	
	$SO_DATEV		= '';
	$SO_DATEV1		= '';
	$CUST_DESC		= '';
	$CUST_ADDRESS	= '';
	$SO_NOTES		= '';
	$SO_NOTES1		= '';
	$SO_REFRENS		= '';
	
	if($task == 'edit')
	{
		$sqlSOA			= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD,
								A.CUST_CODE, A.SO_NOTES, A.SO_NOTES1, A.SO_REFRENS,
								B.CUST_DESC, B.CUST_ADD1
							FROM tbl_so_header A
								INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.SO_STAT = 3
								AND A.SO_NUM = '$SO_NUM' LIMIT 1";
		$resSOA			= $this->db->query($sqlSOA)->result();
		foreach($resSOA as $rowSOA) :
			$SO_NUM			= $rowSOA->SO_NUM;
			$SO_CODE		= $rowSOA->SO_CODE;
			$SO_DATE		= $rowSOA->SO_DATE;
			$SO_DATEV		= date('m/d/Y', strtotime($SO_DATE));
			$SO_DATEV1		= date('d M Y', strtotime($SO_DATE));
			$SO_PRODD		= $rowSOA->SO_PRODD;
			$SO_PRODDV		= date('m/d/Y', strtotime($SO_PRODD));
			$SO_NOTES		= $rowSOA->SO_NOTES;
			$SO_NOTES1		= $rowSOA->SO_NOTES1;
			$CUST_DESC		= $rowSOA->CUST_DESC;
			$CUST_ADDRESS	= $rowSOA->CUST_ADD1;
		endforeach;
	}
	
	$showFORM			= 1;				// 1 = Select SO, 2 = SO Info, 3 = Pilih FG, 4 = Rekapitulasi
	$Step_Bef			= 0;
	$Step_Next			= 1;
	$STEP_BEF			= 1;
	$loading_1			= 1;
	$loading_2			= 1;
	$loading_3			= 1;
	$loading_4			= 1;
	
	if(isset($_POST['Step_Next']))
	{
		$Step_Next		= $_POST['Step_Next'];
	}
	
	if($Step_Next == 2)
	{
		$SO_NUM			= $_POST['SO_NUM'];
		$sqlSOD			= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD,
								A.CUST_CODE, A.SO_NOTES, A.SO_NOTES1, A.SO_REFRENS,
								B.CUST_DESC, B.CUST_ADD1
							FROM tbl_so_header A
								INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.SO_STAT = 3
								AND A.SO_NUM = '$SO_NUM' LIMIT 1";
		$resSOD			= $this->db->query($sqlSOD)->result();
		foreach($resSOD as $rowSOD) :
			$SO_NUM			= $rowSOD->SO_NUM;
			$SO_CODE		= $rowSOD->SO_CODE;
			$SO_DATE		= $rowSOD->SO_DATE;
			$SO_DATEV		= date('m/d/Y', strtotime($SO_DATE));
			$SO_DATEV1		= date('d M Y', strtotime($SO_DATE));
			$SO_DUED		= $rowSOD->SO_DUED;
			$SO_PRODD		= $rowSOD->SO_PRODD;
			$SO_PRODDV		= date('m/d/Y', strtotime($SO_PRODD));
			if($task == 'add')
			{
				$JO_PRODD	= date('m/d/Y', strtotime($SO_PRODD));
			}
			$SO_REFRENS		= $rowSOD->SO_REFRENS;
			$SO_NOTES		= $rowSOD->SO_NOTES;
			$SO_NOTES1		= $rowSOD->SO_NOTES1;
			
			$CUST_CODE		= $rowSOD->CUST_CODE;
			$CUST_DESC		= $rowSOD->CUST_DESC;
			$CUST_ADDRESS	= $rowSOD->CUST_ADD1;
			$CUST_DESC		= $rowSOD->CUST_DESC;
		endforeach;
		
		$Step_Bef		= $_POST['Step_Bef'];
		if($Step_Bef == 3)
		{
			$JO_NUM			= $_POST['JO_NUM'];
			$JO_CODE 		= $_POST['JO_CODE'];
			$PRJCODE		= $_POST['PRJCODE'];
			$JO_DATE		= $_POST['JO_DATE'];
			$JO_PRODD		= $_POST['JO_PRODD'];
			$SO_NUM			= $_POST['SO_NUM'];
			$SO_CODE		= $_POST['SO_CODE'];
			$CUST_CODE		= $_POST['CUST_CODE'];
			$CUST_DESC		= $_POST['CUST_DESC'];
			$JO_VOLM		= $_POST['JO_VOLM'];
			$JO_NOTES		= $_POST['JO_NOTES'];
			$JO_STAT		= $_POST['JO_STAT'];
		}
		
		$IMGC_FILENAMEX	= 'username.jpg';
		$sqlCST			= "SELECT IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE' LIMIT 1";
		$resCST			= $this->db->query($sqlCST)->result();
		foreach($resCST as $rowCST) :
			$IMGC_FILENAMEX		= $rowCST->IMGC_FILENAMEX;
		endforeach;
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE.'/'.$IMGC_FILENAMEX);
		
		$showFORM		= 2;
		$loading_2		= 1;
	}
	elseif($Step_Next == 3)
	{
		$JO_NUM			= $_POST['JO_NUM'];
		$JO_CODE 		= $_POST['JO_CODE'];
		$PRJCODE		= $_POST['PRJCODE'];
		$JO_DATE		= $_POST['JO_DATE'];
		$JO_PRODD		= $_POST['JO_PRODD'];
		$SO_NUM			= $_POST['SO_NUM'];
		$SO_CODE		= $_POST['SO_CODE'];
		$CUST_CODE		= $_POST['CUST_CODE'];
		$CUST_DESC		= $_POST['CUST_DESC'];
		$JO_VOLM		= $_POST['JO_VOLM'];
		$JO_NOTES		= $_POST['JO_NOTES'];
		$JO_STAT		= $_POST['JO_STAT'];
		
		$showFORM		= 3;
		$loading_3		= 1;
	}
	elseif($Step_Next == 4)
	{
		$SO_NUM			= $_POST['SO_NUM'];
		$JO_NUM			= $_POST['JO_NUM'];
		$JO_CODE 		= $_POST['JO_CODE'];
		$PRJCODE		= $_POST['PRJCODE'];
		$JO_DATE		= $_POST['JO_DATE'];
		$JO_DATEV		= date('d M Y', strtotime($JO_DATE));
		$JO_PRODD		= $_POST['JO_PRODD'];
		$JO_PRODDV		= date('d M Y', strtotime($JO_PRODD));
		$SO_NUM			= $_POST['SO_NUM'];
		$SO_CODE		= $_POST['SO_CODE'];
		$CUST_CODE		= $_POST['CUST_CODE'];
		$CUST_DESC		= $_POST['CUST_DESC'];
		$JO_VOLM		= $_POST['JO_VOLM'];
		$JO_NOTES		= $_POST['JO_NOTES'];
		$JO_STAT		= $_POST['JO_STAT'];
		
		$sqlSODC		= "tbl_jo_detail_tmp3
							WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' AND SO_NUM = '$SO_NUM'";
		$resSODC		= $this->db->count_all($sqlSODC);
		if($resSODC == 0)
		{
			foreach($_POST['data1'] as $d)
			{
				$ITM_QTY1		= $d['ITM_QTY'];
				$ITM_PRICE1		= $d['ITM_PRICE'];
				$ITM_TOTAL1		= $ITM_QTY1 * $ITM_QTY1;
				$ITM_TOTAL 		= $d['ITM_TOTAL'];
				$d['JO_DATE']	= date('Y-m-d', strtotime($JO_DATE));
				$d['JO_PRODD']	= date('Y-m-d', strtotime($JO_PRODD));
				$this->db->insert('tbl_jo_detail_tmp3',$d);
			}
		}
		else
		{
			$sqlSODD		= "DELETE FROM tbl_jo_detail_tmp3
								WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' AND SO_NUM = '$SO_NUM'";
			$this->db->query($sqlSODD);
			
			foreach($_POST['data1'] as $d)
			{
				$ITM_QTY1		= $d['ITM_QTY'];
				$ITM_PRICE1		= $d['ITM_PRICE'];
				$ITM_TOTAL1		= $ITM_QTY1 * $ITM_QTY1;
				$d['JO_DATE']	= date('Y-m-d', strtotime($JO_DATE));
				$d['JO_PRODD']	= date('Y-m-d', strtotime($JO_PRODD));
				$this->db->insert('tbl_jo_detail_tmp3',$d);
			}
		}
		
		$JO_VOLM		= 0;
		$sqlSOV			= "SELECT SUM(SO_VOLM) AS TOT_PROD FROM tbl_so_detail
							WHERE PRJCODE = '$PRJCODE'
								AND SO_NUM = '$SO_NUM' LIMIT 1";
		$resSOV			= $this->db->query($sqlSOV)->result();
		foreach($resSOV as $rowSOV) :
			$JO_VOLM	= $rowSOV->TOT_PROD;
		endforeach;
		
		$showFORM		= 4;
		$loading_3		= 1;
	}
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->

<section class="content-header">
<h1>
    <?php echo $h1_title; ?>
    <small>&nbsp;</small>
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
</style>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          	<!-- Profile Image -->
          	<div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
                    <h3 class="profile-username text-center"></h3>                    
                    <p class="text-muted text-center">
                        <a><b>
                    		<?php 
								if($CUST_DESC == '')
									echo $CustName;
								else
									echo $CUST_DESC;
							?>
                            </b></a>
                    </p>
                    <ul class="list-group list-group-unbordered">
                    	<li class="list-group-item" style="text-align:center">
                            <p class="text-muted"><em>
                                <i class="fa fa-map-marker margin-r-5"></i>
                                <?php 
                                    if($CUST_ADDRESS == '')
                                        echo $None;
                                    else
                                        echo $CUST_ADDRESS;
                                ?>
                            </em></p>
                    	</li>
                    </ul>
                </div>
          	</div>

          <!-- About Me Box -->
          	<div class="box box-primary">
                <div class="box-header with-border">
                	<h3 class="box-title"><?php echo $Description; ?> (SO)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                	<strong><i class="fa fa-pencil margin-r-5"></i> <?php echo $Code; ?> (SO)</strong>
                	<p class="text-muted">
                		<em>
							<?php 
                                if($SO_CODE == '')
                                    echo $None;
                                else
                                    echo "$SO_CODE - $SO_DATEV1";
                            ?>
                        </em>
                	</p>
               		<hr>
                    <strong><i class="fa fa-link margin-r-5"></i> No. Ref.</strong>
                    <p class="text-muted">
                		<em>
							<?php 
                                if($SO_REFRENS == '')
                                    echo $None;
                                else
                                    echo $SO_REFRENS;
                            ?>
                        </em>
                    </p>
					<hr>
                	<strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $Notes; ?> (SO)</strong>
                    <p><em>
						<?php
                            if($SO_NOTES == '')
                                echo $None;
                            else
                                echo $SO_NOTES;
                        ?>
                    </em></p>
					<hr>
                	<strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $ApproverNotes; ?> (SO)</strong>
                    <p><em>
						<?php 
                            if($SO_NOTES1 == '')
                                echo $None;
                            else
                                echo $SO_NOTES1;
                        ?>
                    </em></p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
			<div class="nav-tabs-custom">
            	<ul class="nav nav-tabs">
                	<li <?php if($showFORM == 1) { ?> class="active" <?php } ?>><a href="#selectSO" data-toggle="tab">1. <?php echo $Step1Des; ?></a></li> 		<!-- Tab 1 -->
                    <li <?php if($showFORM == 2) { ?> class="active" <?php } ?>><a href="#SOInfo" data-toggle="tab">2. <?php echo $Step2Des; ?></a></li>					<!-- Tab 2 -->
                    <li <?php if($showFORM == 3) { ?> class="active" <?php } ?>><a href="#SelectFG" data-toggle="tab">3. <?php echo $Step3Des; ?></a></li>					<!-- Tab 3 -->
                    <li <?php if($showFORM == 4) { ?> class="active" <?php } ?>><a href="#JOSummary" data-toggle="tab">4. <?php echo $Step4Des; ?></a></li>					<!-- Tab 4 -->
                </ul>
                <!-- Biodata -->
                <div class="tab-content">
                	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
					<?php
                        $back1	= site_url('c_production/c_j0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                        if($showFORM == 1)
                        {
                    	?>
                            <div class="active tab-pane" id="selectSO">
                                <form class="form-horizontal" name="frmSOrder" method="post" action="" onSubmit="return checkSOrder()">
                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="0">
                                    <input type="hidden" name="Step_Next" id="Step_Next" value="2">
                                    <input type="hidden" name="SO_NUM" id="SO_NUM" value="<?php echo $SO_NUM; ?>">
                                    <div class="box-body">
                                        <div class="box box-primary">
                                            <br>
                                            <div class="alert alert-warning alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <i class="icon fa fa-warning"></i><?php echo $stepalert1; ?>
                                            </div>
                                            <div class="search-table-outter">
                                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="2%" height="40" style="text-align:center"></th>
                                                            <th width="16%" style="text-align:center" nowrap><?php echo $Code; ?> SO</th>
                                                            <th width="4%" style="text-align:center" nowrap><?php echo $Date; ?> </th>
                                                            <th width="4%" style="text-align:center" nowrap><?php echo $ProdPlan; ?> </th>
                                                            <th width="74%" style="text-align:center" nowrap><?php echo $CustName; ?> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $sqlSO		= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD,
                                                                                A.CUST_CODE, B.CUST_DESC
                                                                            FROM tbl_so_header A
                                                                                INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
                                                                            WHERE A.PRJCODE = '$PRJCODE'
                                                                                AND A.SO_STAT = 3";
                                                            $resSO 		= $this->db->query($sqlSO)->result();
                                                            
                                                            $sqlSOC		= "tbl_so_header A
                                                                                INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
                                                                            WHERE A.PRJCODE = '$PRJCODE'
                                                                                AND A.SO_STAT = 3";
                                                            $resSOC 	= $this->db->count_all($sqlSOC);
                                                            
                                                            $i		= 0;
                                                            $j		= 0;
                                                            $cRow	= 0;
                                                            if($resSOC > 0)
                                                            {
                                                                foreach($resSO as $rowSO) :
                                                                    $cRow  			= ++$i;
                                                                    $SO_NUM1		= $rowSO->SO_NUM;
                                                                    $SO_CODE		= $rowSO->SO_CODE;
                                                                    $SO_DATE		= $rowSO->SO_DATE;
                                                                    $SO_DUED		= $rowSO->SO_DUED;
                                                                    $SO_PRODD		= $rowSO->SO_PRODD;
                                                                    $CUST_CODE		= $rowSO->CUST_CODE;
                                                                    $CUST_DESC		= strtolower($rowSO->CUST_DESC);
                                                                    ?>
                                                                    <tr>
                                                                        <td width="2%" height="25" style="text-align:center" nowrap>
                                                                            <input type="radio" name="chkSO" id="chkSO_<?php echo $cRow; ?>" value="<?php echo $SO_NUM1; ?>" onClick="pickSO(this);" <?php if($SO_NUM == $SO_NUM1) { ?> checked <?php } ?>>
                                                                        </td>
                                                                        <td width="16%" style="text-align:left" nowrap>
                                                                            <?php echo $SO_CODE; ?>
                                                                        </td>
                                                                        <td width="4%" style="text-align:center" nowrap>
                                                                            <?php echo date('d M Y', strtotime($SO_DATE)); ?>
                                                                        </td>  
                                                                        <td width="4%" style="text-align:center" nowrap>
                                                                            <?php echo date('d M Y', strtotime($SO_PRODD)); ?>
                                                                        </td>
                                                                        <td width="74%" style="text-align:left" nowrap>
                                                                            <?php echo ucwords($CUST_DESC); ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                endforeach;
                                                            }
                                                        ?>
                                                        <input type="hidden" name="totRow1" id="totRow1" value="<?php echo $cRow; ?>">
                                                    </tbody>
                                                </table>
                                            </div>
                                        
                                            <div class="box-header with-border">
                                                <table width="100%" border="0">
                                                    <tr>
                                                        <td width="15%" style="text-align:left;" nowrap>
                                                            <?php echo anchor("$back1",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-ban-circle"></i>&nbsp;&nbsp;'.$Canceled.'</button>'); ?>
                                                        </td>
                                                        <td width="85%" style="text-align:right" nowrap>
                                                            <button class="btn btn-warning" disabled>
                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
                                                            </button>
                                                            <button class="btn btn-primary" onClick="isNext1(1);">
                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
                                                            </button>
                                                            <button class="btn btn-success" disabled>
                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div id="loading_1" class="overlay" <?php if($loading_1 == 1) { ?> style="display:none" <?php } ?>>
                                                <i class="fa fa-refresh fa-spin"></i>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
						<?php
                        }
                    ?>
                    <script>
						function pickSO(thisobj)
						{
							var totRow	= document.getElementById('totRow1').value;
							var SONUM	= '';
							j	= 0;
							for(i=1; i <= totRow; i++)
							{
								var isChk	= document.getElementById('chkSO_'+i).checked;
								
								if(isChk == true)
								{
									j = j+1;
									if(j == 1)
									{
										var SO_NUM	= document.getElementById('chkSO_'+i).value;
										document.getElementById('SO_NUM').value	= SO_NUM;
									}
									else
									{
										var SONUM	= document.getElementById('SO_NUM').value;
										var SO_NUM	= document.getElementById('chkSO_'+i).value;
										SONUM_		= SONUM+'~'+SO_NUM;
										document.getElementById('SO_NUM').value	= SONUM_;
									}
								}
							}
						}
	
						function checkSOrder()
						{
							SO_NUM		= document.getElementById('SO_NUM').value;
							if(SO_NUM == '')
							{
								alert('<?php echo $alert1; ?>');
								return false;
							}
							
							document.getElementById('loading_1').style.display = '';
						}
					</script>
					<?php
                        if($showFORM == 2)
                        {
                    	?>
                            <div class="active tab-pane" id="SOInfo">
                                <form class="form-horizontal" name="frmSOInfo" method="post" action="" onSubmit="return checkSOInfo()">
                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="1">
                                    <input type="hidden" name="Step_Next" id="Step_Next" value="3">
                                    <input type="hidden" name="JO_NUM" id="JO_NUM" value="<?php echo $JO_NUM; ?>">
                                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                    <input type="hidden" name="SO_NUM" id="SO_NUM" value="<?php echo $SO_NUM; ?>">
                                    <input type="hidden" name="SO_CODE" id="SO_CODE" value="<?php echo $SO_CODE; ?>">
                                    <input type="hidden" name="CUST_CODE" id="CUST_CODE" value="<?php echo $CUST_CODE; ?>">
                                    <input type="hidden" name="CUST_DESC" id="CUST_DESC" value="<?php echo $CUST_DESC; ?>">
                                    <div class="box-body">
                                        <div class="box box-primary">
                                            <br>
                                            <div class="alert alert-warning alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <i class="icon fa fa-warning"></i><?php echo $stepalert2; ?>
                                            </div>
											<?php if($isSetDocNo == 0) { ?>
                                                <div class="alert alert-danger alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                        <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
                                                        <?php echo $docalert2; ?>
                                                </div>
                                            <?php } ?>
                                            <div class="search-table-outter">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $Code; ?> JO <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<?php if($isDis == 0) { ?>
                                                    		<input type="text" class="form-control" name="JO_CODE" id="JO_CODE" value="<?php echo $JO_CODE; ?>" >
                                                        <?php } else { ?>
                                                    		<input type="hidden" class="form-control" name="JO_CODE" id="JO_CODE" value="<?php echo $JO_CODE; ?>" >
                                                    		<input type="text" class="form-control" name="JO_CODE1" id="JO_CODE1" value="<?php echo $JO_CODE; ?>" disabled >
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?php echo $Date ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
															<?php if($isDis == 0) { ?>
                                                                <input type="text" name="JO_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $JO_DATE; ?>" style="width:100px">
                                                            <?php } else { ?>
                                                                <input type="hidden" name="JO_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $JO_DATE; ?>" style="width:100px">
                                                                <input type="text" name="JO_DATE1" class="form-control pull-left" id="datepicker1" value="<?php echo $JO_DATE; ?>" style="width:100px" disabled>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ProdPlan ?></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
															<?php if($isDis == 0) { ?>
                                                                <input type="text" name="JO_PRODD" class="form-control pull-left" id="datepicker2" value="<?php echo $JO_PRODD; ?>" style="width:100px">
                                                            <?php } else { ?>
                                                                <input type="hidden" name="JO_PRODD" class="form-control pull-left" id="datepicker2" value="<?php echo $JO_PRODD; ?>" style="width:100px">
                                                                <input type="text" name="JO_PRODD1" class="form-control pull-left" id="datepicker2" value="<?php echo $JO_PRODD; ?>" style="width:100px" disabled>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Project ?></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="PRJCODE1" id="PRJCODE1" class="form-control" disabled>
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
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Description ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <?php if($isDis == 0) { ?>
                                                            <textarea class="form-control" name="JO_NOTES"  id="JO_NOTES"><?php echo $JO_NOTES; ?></textarea>
                                                        <?php } else { ?>
                                                            <textarea class="form-control" name="JO_NOTES"  id="JO_NOTES" style="display:none"><?php echo $JO_NOTES; ?></textarea>
                                                            <textarea class="form-control" name="JO_NOTES1"  id="JO_NOTES1" disabled><?php echo $JO_NOTES; ?></textarea>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group" <?php if($JO_NOTES2 == '') { ?> style="display:none" <?php } ?>>
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ApproverNotes ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <textarea class="form-control" name="JO_NOTES2"  id="JO_NOTES2" disabled><?php echo $JO_NOTES2; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ProdTotal ?> <span class="required">&nbsp;</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="JO_VOLMX" class="form-control pull-left" id="JO_VOLMX" value="<?php echo number_format($JO_VOLM, 2); ?>" style="width:140px; text-align:right" onBlur="chgJOVOLM()">
                                                        <input type="hidden" name="JO_VOLM" class="form-control pull-left" id="JO_VOLM" value="<?php echo $JO_VOLM; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display:none">
                                                   	<label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Status ?> </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $JO_STAT; ?>">
                                                        <?php
                                                            $isDisabled = 1;
                                                            if($JO_STAT == 1 || $JO_STAT == 4)
                                                            {
                                                                $isDisabled = 0;
                                                            }
                                                        ?>
                                                        <select name="JO_STAT" id="JO_STAT" class="form-control" style="max-width:140px">
                                                            <?php
                                                            if($JO_STAT == 1) 
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                                <?php
                                                            }
                                                            elseif($JO_STAT == 2) 
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                                    <option value="3"<?php if($JO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
                                                                    <option value="4"<?php if($JO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
                                                                    <option value="5"<?php if($JO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
                                                                    <option value="6"<?php if($JO_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
                                                                    <option value="7"<?php if($JO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
                                                                <?php
                                                            }
                                                            elseif($JO_STAT == 3) 
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
                                                                    <option value="3"<?php if($JO_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                                                    <option value="4"<?php if($JO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
                                                                    <option value="5"<?php if($JO_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
                                                                    <option value="6"<?php if($JO_STAT == 6) { ?> selected <?php } ?> disabled >Closed</option>
                                                                    <option value="7"<?php if($JO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="box-header with-border">
                                                <table width="100%" border="0">
                                                    <tr>
                                                        <td width="15%" style="text-align:left;" nowrap>
                                                            <?php echo anchor("$back1",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-ban-circle"></i>&nbsp;&nbsp;'.$Canceled.'</button>'); ?>
                                                        </td>
                                                        <td width="85%" style="text-align:right" nowrap>
                                                            <button class="btn btn-warning" onClick="isBack1(1);">
                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
                                                            </button>
                                                            <button class="btn btn-primary" onClick="isNext2(2);">
                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
                                                            </button>
                                                            <button class="btn btn-success" disabled>
                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div id="loading_2" class="overlay" <?php if($loading_2 == 1) { ?> style="display:none" <?php } ?>>
                                                <i class="fa fa-refresh fa-spin"></i>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
						<?php
                        }
					?>
					<script>
                        function chgJOVOLM()
                        {
							var decFormat	= document.getElementById('decFormat').value;
							
							JO_VOLMX		= document.getElementById('JO_VOLMX');
							JO_VOLM 		= parseFloat(eval(JO_VOLMX).value.split(",").join(""));
							document.getElementById('JO_VOLM').value		= JO_VOLM;
							document.getElementById('JO_VOLMX').value		= doDecimalFormat(RoundNDecimal(parseFloat(JO_VOLM),decFormat));
                        }
						
                        function isBack1(thisValue)
                        {
                            document.getElementById('Step_Bef').value 	= 2;
                            document.getElementById('Step_Next').value 	= 1;
                        }
						
						function checkSOInfo()
						{
							Step_Next		= document.getElementById('Step_Next').value;
							if(Step_Next == 3)
							{
								JO_NOTES		= document.getElementById('JO_NOTES').value;
								if(JO_NOTES == '')
								{
									alert('<?php echo $alert3; ?>');
									document.getElementById('JO_NOTES').focus();
									return false;
								}
								
								<?php /*?>JO_VOLMX		= document.getElementById('JO_VOLMX');
								JO_VOLM 		= parseFloat(eval(JO_VOLMX).value.split(",").join(""));
								if(JO_VOLM == 0)
								{
									alert('<?php echo $alert2; ?>');
									document.getElementById('JO_VOLMX').focus();
									return false;
								}<?php */?>
							}
							
							document.getElementById('loading_2').style.display = '';
						}
                    </script>
                    <?php
                        if($showFORM == 3)
                        {
                    	?>
                            <div class="active tab-pane" id="SelectFG">
                                <form class="form-horizontal" name="frmSelFG" method="post" action="" onSubmit="return checkSOInfo()">
                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="2">
                                    <input type="hidden" name="Step_Next" id="Step_Next" value="4">
                                    <input type="hidden" name="JO_NUM" id="JO_NUM" value="<?php echo $JO_NUM; ?>">
                                    <input type="hidden" name="JO_CODE" id="JO_CODE" value="<?php echo $JO_CODE; ?>">
                                    <input type="hidden" name="JO_DATE" id="JO_DATE" value="<?php echo $JO_DATE; ?>" >
                                    <input type="hidden" name="JO_PRODD" id="JO_PRODD" value="<?php echo $JO_PRODD; ?>" >
                                    <input type="hidden" name="JO_STAT" id="JO_STAT" value="<?php echo $JO_STAT; ?>">
                                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                    <input type="hidden" name="SO_NUM" id="SO_NUM" value="<?php echo $SO_NUM; ?>">
                                    <input type="hidden" name="SO_CODE" id="SO_CODE" value="<?php echo $SO_CODE; ?>">
                                    <input type="hidden" name="CUST_CODE" id="CUST_CODE" value="<?php echo $CUST_CODE; ?>">
                                    <input type="hidden" name="CUST_DESC" id="CUST_DESC" value="<?php echo $CUST_DESC; ?>">
                                    <textarea name="JO_NOTES" id="JO_NOTES" style="display:none"><?php echo $JO_NOTES; ?></textarea>
                                    <input type="hidden" name="JO_VOLM" id="JO_VOLM" value="<?php echo $JO_VOLM; ?>">
                                    <div class="box-body">
                                        <div class="box box-primary">
                                            <br>
                                            <div class="alert alert-warning alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <i class="icon fa fa-warning"></i><?php echo $stepalert3; ?>
                                            </div>
                                            <div class="search-table-outter">
                                                <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="9%" height="40" style="text-align:center; display:none">&nbsp;</th>
                                                            <th width="7%" style="text-align:center" nowrap><?php echo $ItemCode; ?></th>
                                                            <th width="51%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                                            <th width="8%" style="text-align:center" nowrap>Qty (<?php echo $Stock; ?>) </th>
                                                            <th width="6%" style="text-align:center" nowrap>Qty (SO) </th>
                                                            <th width="7%" style="text-align:center" nowrap>Qty (JO)</th>
                                                            <th width="12%" style="text-align:center" nowrap>Qty (<?php echo $Ordered; ?>)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
														if($task == 'add')
														{
															$sqlSO		= "SELECT A.SO_NUM, A.SO_CODE, A.ITM_CODE, B.ITM_CATEG,
																				B.ITM_NAME, A.ITM_UNIT,
																				0 AS ITM_QTY, A.SO_PRICE AS ITM_PRICE,
																				A.SO_COST AS ITM_TOTAL																				
																			FROM tbl_so_detail A
																				INNER JOIN tbl_item B ON A.ITM_CODE  = B.ITM_CODE
																					AND B.PRJCODE  = '$PRJCODE'
																			WHERE A.PRJCODE = '$PRJCODE' AND A.SO_NUM = '$SO_NUM'";
														}
														else
														{
															if($JO_STAT == 1)
															{
																$sqlSO	= "SELECT A.SO_NUM, A.SO_CODE, A.ITM_CODE, B.ITM_CATEG,
																				B.ITM_NAME, A.ITM_UNIT,
																				0 AS ITM_QTY, A.SO_PRICE AS ITM_PRICE,
																				A.SO_COST AS ITM_TOTAL																				
																			FROM tbl_so_detail A
																				INNER JOIN tbl_item B ON A.ITM_CODE  = B.ITM_CODE
																					AND B.PRJCODE  = '$PRJCODE'
																			WHERE A.PRJCODE = '$PRJCODE' AND A.SO_NUM = '$SO_NUM'";
															}
															else
															{
																$sqlSO	= "SELECT A.SO_NUM, A.SO_CODE, A.ITM_CODE, A.ITM_CATEG,
																				B.ITM_NAME, A.ITM_UNIT, 
																				A.ITM_QTY, 
																				A.ITM_PRICE, A.ITM_TOTAL
																			FROM tbl_jo_detail A
																				INNER JOIN tbl_item B ON A.ITM_CODE  = B.ITM_CODE
																					AND B.PRJCODE  = '$PRJCODE'
																			WHERE A.PRJCODE = '$PRJCODE' 
																				AND A.JO_NUM = '$JO_NUM'";
															}
														}
														$resSO 		= $this->db->query($sqlSO)->result();
														
														$i		= 0;
														$j		= 0;
														$cRow	= 0;
														foreach($resSO as $rowSO) :
															$cRow  			= ++$i;
															$SO_NUM			= $rowSO->SO_NUM;
															$SO_CODE		= $rowSO->SO_CODE;
															$PRJCODE		= $PRJCODE;
															$ITM_CODE		= $rowSO->ITM_CODE;
															$ITM_UNIT		= $rowSO->ITM_UNIT;
															$ITM_QTY		= $rowSO->ITM_QTY;
															$ITM_PRICE		= $rowSO->ITM_PRICE;
															$ITM_TOTAL		= $rowSO->ITM_TOTAL;
															if($task == 'edit')
															{
																$sqlITMJO		= "SELECT A.ITM_QTY, A.ITM_TOTAL
																					FROM tbl_jo_detail A
																					INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
																						AND B.PRJCODE = '$PRJCODE'
																					WHERE A.PRJCODE = '$PRJCODE'
																						AND A.JO_NUM = '$JO_NUM'
																						AND A.ITM_CODE = '$ITM_CODE'";
																$resITMJO 		= $this->db->query($sqlITMJO)->result();															
																foreach($resITMJO as $rowITMJO) :
																	$ITM_QTY	= $rowITMJO->ITM_QTY;
																	$ITM_TOTAL	= $rowITMJO->ITM_TOTAL;
																endforeach;
															}
															$ITM_NAME		= $rowSO->ITM_NAME;
															$ITM_CATEG		= $rowSO->ITM_CATEG;
															
															$ITM_STOCK		= 0;
															$sqlITM			= "SELECT ITM_VOLM FROM tbl_item
																				WHERE PRJCODE = '$PRJCODE' 
																					AND ITM_CODE = '$ITM_CODE' LIMIT 1";
															$resITM 		= $this->db->query($sqlITM)->result();															
															foreach($resITM as $rowITM) :
																$ITM_STOCK	= $rowITM->ITM_VOLM;
															endforeach;
															
															$SO_VOLM		= 0;
															$sqlSO			= "SELECT A.SO_VOLM
																				FROM tbl_so_detail A
																				WHERE A.PRJCODE = '$PRJCODE' 
																					AND A.SO_NUM = '$SO_NUM'
																					AND A.ITM_CODE = '$ITM_CODE'";
															$resSO 			= $this->db->query($sqlSO)->result();
															
															foreach($resSO as $rowSO) :
																$SO_VOLM	= $rowSO->SO_VOLM;
															endforeach;
															
															$ITM_QTY_JO		= 0;
															$sqlITMQJO		= "SELECT SUM(A.ITM_QTY) AS TOT_JOQTY 
																				FROM tbl_jo_detail A
																				INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
																					AND B.PRJCODE = '$PRJCODE'
																				WHERE A.PRJCODE = '$PRJCODE'
																					AND A.JO_NUM != '$JO_NUM'
																					AND A.SO_NUM = '$SO_NUM'
																					AND A.ITM_CODE = '$ITM_CODE'
																					AND B.JO_STAT IN (2,3,6)";
															$resITMQJO 		= $this->db->query($sqlITMQJO)->result();															
															foreach($resITMQJO as $rowITMQJO) :
																$ITM_QTY_JO	= $rowITMQJO->TOT_JOQTY;
															endforeach;
															if($ITM_QTY_JO == '')
																$ITM_QTY_JO	= 0;															
															?>
															<tr>
																<td width="9%" height="25" style="text-align:center; vertical-align:middle; display:none" nowrap>
																	<input type="checkbox" class="flat-red" checked>
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][JO_NUM]" id="data1<?php echo $cRow; ?>JO_NUM" value="<?php echo $JO_NUM; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][JO_CODE]" id="data1<?php echo $cRow; ?>JO_CODE" value="<?php echo $JO_CODE; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][SO_NUM]" id="data1<?php echo $cRow; ?>SO_NUM" value="<?php echo $SO_NUM; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][SO_CODE]" id="data1<?php echo $cRow; ?>SO_CODE" value="<?php echo $SO_CODE; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][PRJCODE]" id="data1<?php echo $cRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>">
																</td>
																<td width="7%" style="text-align:left; vertical-align:middle" nowrap>
																	<?php echo $ITM_CODE; ?>
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][ITM_CODE]" id="data1<?php echo $cRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][ITM_CATEG]" id="data1<?php echo $cRow; ?>ITM_CATEG" value="<?php echo $ITM_CATEG; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][ITM_UNIT]" id="data1<?php echo $cRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>">
																</td>
																<td width="51%" style="text-align:left; vertical-align:middle" nowrap>
																	<?php echo $ITM_NAME; ?>
																</td>
																<td width="8%" style="text-align:right; vertical-align:middle" nowrap>
																	<?php echo number_format($ITM_STOCK,2); ?>
																	<input type="hidden" class="form-control" name="ITM_STOCK_<?php echo $cRow; ?>" id="ITM_STOCK_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo $ITM_STOCK; ?>">
																</td>
																<td width="6%" nowrap style="text-align:right; vertical-align:middle">
																	<?php echo number_format($SO_VOLM,2); ?>
																	<input type="hidden" class="form-control" name="SO_VOLMX_<?php echo $cRow; ?>" id="SO_VOLMX_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo $SO_VOLM; ?>">
																</td>
																<td width="7%" nowrap style="text-align:right; vertical-align:middle">
																	<?php echo number_format($ITM_QTY_JO, 2); ?>
																	<input type="hidden" class="form-control" name="SO_JOVOLM_<?php echo $cRow; ?>" id="SO_JOVOLM_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo $ITM_QTY_JO; ?>">
																</td>
																<td width="12%" style="text-align:right">
                                                                    <?php if($isDis == 0) { ?>
                                                                        <input type="text" class="form-control" name="ITM_QTYX3_<?php echo $cRow; ?>" id="ITM_QTYX3_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo number_format($ITM_QTY,2); ?>" onBlur="chgQTY(this,'<?php echo $cRow; ?>');">
                                                                    <?php } else {
                                                                        echo number_format($ITM_QTY,2);
                                                                  	} ?>
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][ITM_QTY]" id="data1<?php echo $cRow; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][ITM_PRICE]" id="data1<?php echo $cRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>">
																	<input type="hidden" name="data1[<?php echo $cRow; ?>][ITM_TOTAL]" id="data1<?php echo $cRow; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>">
																</td>
															</tr>
                                                        	<?php
														endforeach;
													?>
                                                    <input type="hidden" name="totRow3" id="totRow3" value="<?php echo $cRow; ?>">
                                                    </tbody>
                                                </table>
                                            </div>
                                        
                                            <div class="box-header with-border">
                                                <table width="100%" border="0">
                                                    <tr>
                                                        <td width="15%" style="text-align:left;" nowrap>
                                                            <?php echo anchor("$back1",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-ban-circle"></i>&nbsp;&nbsp;'.$Canceled.'</button>'); ?>
                                                        </td>
                                                        <td width="85%" style="text-align:right" nowrap>
                                                            <button class="btn btn-warning" onClick="isBack2(2);">
                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
                                                            </button>
                                                            <button class="btn btn-primary" onClick="isNext3(4);">
                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
                                                            </button>
                                                            <button class="btn btn-success" disabled>
                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div id="loading_3" class="overlay" <?php if($loading_3 == 1) { ?> style="display:none" <?php } ?>>
                                                <i class="fa fa-refresh fa-spin"></i>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
						<?php
                        }
					?>
					<script>
                        function isBack2(thisValue)
                        {
                            document.getElementById('Step_Bef').value 	= 3;
                            document.getElementById('Step_Next').value 	= 2;
                        }
						
						function chgQTY(thisVal, row)
						{
							var totRow		= document.getElementById('totRow3').value;
							var decFormat	= document.getElementById('decFormat').value;
							
							var SO_VOLMX_	= document.getElementById('SO_VOLMX_'+row);
							var SO_VOLMX	= parseFloat(eval(SO_VOLMX_).value.split(",").join(""));
							
							var ITM_QTYX3_	= document.getElementById('ITM_QTYX3_'+row);
							var ITM_QTYX3	= parseFloat(eval(ITM_QTYX3_).value.split(",").join(""));
							
							var SO_JOVOLM_	= document.getElementById('SO_JOVOLM_'+row);
							var SO_JOVOLM	= parseFloat(eval(SO_JOVOLM_).value.split(",").join(""));
							
							var SO_REMVOLM	= parseFloat(SO_VOLMX) - parseFloat(SO_JOVOLM);
							
							if(ITM_QTYX3 > SO_REMVOLM)
							{
								alert('<?php echo $alert4; ?>');
								document.getElementById('ITM_QTYX3_'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(SO_REMVOLM),decFormat));
								document.getElementById('data1'+row+'ITM_QTY').value = SO_REMVOLM;
								ITM_QTYX3	= parseFloat(SO_REMVOLM);
							}
							else
							{
								document.getElementById('ITM_QTYX3_'+row).value 	= doDecimalFormat(RoundNDecimal(parseFloat(ITM_QTYX3),decFormat));
								document.getElementById('data1'+row+'ITM_QTY').value = ITM_QTYX3;
								ITM_QTYX3	= parseFloat(ITM_QTYX3);
							}
							
							var ITM_PRICE	= parseFloat(document.getElementById('data1'+row+'ITM_PRICE').value);
							var ITM_TOTAL	= parseFloat(ITM_QTYX3) * parseFloat(ITM_PRICE);
							document.getElementById('data1'+row+'ITM_TOTAL').value = ITM_TOTAL;
						}
						
						function checkSelectFG()
						{
							Step_Next		= document.getElementById('Step_Next').value;
							if(Step_Next == 3)
							{
								JO_NOTES		= document.getElementById('JO_NOTES').value;
								if(JO_NOTES == '')
								{
									alert('<?php echo $alert3; ?>');
									document.getElementById('JO_NOTES').focus();
									return false;
								}
								
								JO_VOLMX		= document.getElementById('JO_VOLMX');
								JO_VOLM 		= parseFloat(eval(JO_VOLMX).value.split(",").join(""));
								if(JO_VOLM == 0)
								{
									alert('<?php echo $alert2; ?>');
									document.getElementById('JO_VOLMX').focus();
									return false;
								}
							}
							
							document.getElementById('loading_2').style.display = '';
						}
                    </script>
                    <?php
                        if($showFORM == 4)
                        {
							$PRJNAMEV	= '';
							foreach($vwPRJ as $row) :
								$PRJCODE1 	= $row->PRJCODE;
								$PRJNAME 	= $row->PRJNAME;
								if($PRJCODE1 == $PRJCODE)
								{
									$PRJNAMEV	= $PRJNAME;
								}
							endforeach;
                    	?>
                            <div class="active tab-pane" id="JOSummary">
                                <form class="form-horizontal" name="frmJOSum" id="frmJOSum" method="post" action="" onSubmit="return chkBtn()">
                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="2">
                                    <input type="hidden" name="Step_Next" id="Step_Next" value="4">
                                    <input type="hidden" name="JO_NUM" id="JO_NUM" value="<?php echo $JO_NUM; ?>">
                                    <input type="hidden" name="JO_CODE" id="JO_CODE" value="<?php echo $JO_CODE; ?>">
                                    <input type="hidden" name="JO_DATE" id="JO_DATE" value="<?php echo $JO_DATE; ?>" >
                                    <input type="hidden" name="JO_PRODD" id="JO_PRODD" value="<?php echo $JO_PRODD; ?>" >
                                    <input type="hidden" name="JO_STAT" id="JO_STAT" value="<?php echo $JO_STAT; ?>">
                                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
                                    <input type="hidden" name="SO_NUM" id="SO_NUM" value="<?php echo $SO_NUM; ?>">
                                    <input type="hidden" name="SO_CODE" id="SO_CODE" value="<?php echo $SO_CODE; ?>">
                                    <input type="hidden" name="CUST_CODE" id="CUST_CODE" value="<?php echo $CUST_CODE; ?>">
                                    <input type="hidden" name="CUST_DESC" id="CUST_DESC" value="<?php echo $CUST_DESC; ?>">
                                    <textarea name="JO_NOTES" id="JO_NOTES" style="display:none"><?php echo $JO_NOTES; ?></textarea>
                                    <input type="hidden" name="JO_VOLM" id="JO_VOLM" value="<?php echo $JO_VOLM; ?>">
                                    <div class="box-body">
                                        <div class="box box-primary">
                                            <br>
                                            <div class="alert alert-warning alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <i class="icon fa fa-warning"></i><?php echo $stepalert4; ?>
                                            </div>
                                            <div class="search-table-outter">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $Code; ?> JO <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<input type="text" class="form-control" name="JO_CODEX" id="JO_CODEX" value="<?php echo $JO_CODE; ?>" disabled >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"><?php echo $Date ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="input-group date">
                                                            <input type="text" name="JO_DATEX" class="form-control pull-left" id="datepicker1" value="<?php echo $JO_DATE; ?>" style="width:100px" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ProdPlan ?></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="input-group date">
                                                            <input type="text" name="JO_PRODDX" class="form-control pull-left" id="datepicker2" value="<?php echo $JO_PRODD; ?>" style="width:100px" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Project ?></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="PRJCODE1" id="PRJCODE1" class="form-control" disabled>
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
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Description ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <textarea class="form-control" name="JO_NOTESX"  id="JO_NOTESX" disabled><?php echo $JO_NOTES; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group" <?php if($JO_NOTES2 == '') { ?> style="display:none" <?php } ?>>
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ApproverNotes ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <textarea class="form-control" name="JO_NOTES2"  id="JO_NOTES2" disabled><?php echo $JO_NOTES2; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display:none">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $ProdTotal ?> <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="JO_VOLMX" class="form-control pull-left" id="JO_VOLMX" value="<?php echo number_format($JO_VOLM, 2); ?>" style="width:140px; text-align:right" onBlur="chgJOVOLM()" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group" >
                                                   	<label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $Status ?> </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $JO_STAT; ?>">
                                                        <?php
                                                            $isDisabled = 1;
                                                            if($JO_STAT == 1 || $JO_STAT == 4)
                                                            {
                                                                $isDisabled = 0;
                                                            }
                                                        ?>
                                                        <select name="JO_STAT" id="JO_STAT" class="form-control" style="max-width:120px">
                                                            <?php
                                                            if($JO_STAT == 1) 
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                                <?php
                                                            }
                                                            elseif($JO_STAT == 2) 
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                                    <option value="3"<?php if($JO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
                                                                    <option value="4"<?php if($JO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
                                                                    <option value="5"<?php if($JO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
                                                                    <option value="6"<?php if($JO_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
                                                                    <option value="7"<?php if($JO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
                                                                <?php
                                                            }
                                                            elseif($JO_STAT == 3)
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
                                                                    <option value="3"<?php if($JO_STAT == 3) { ?> selected <?php } ?>>Approve</option>
                                                                    <option value="4"<?php if($JO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
                                                                    <option value="5"<?php if($JO_STAT == 5) { ?> selected <?php } ?> disabled >Rejected</option>
                                                                    <option value="6"<?php if($JO_STAT == 6) { ?> selected <?php } ?> disabled >Closed</option>
                                                                    <option value="7"<?php if($JO_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                    <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                                    <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="box box-warning">
                                                            <div class="box-header with-border">
                                                                <h3 class="box-title"><?php echo $ItemListOrd; ?></h3>
                                                            </div>
                                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="2%" height="40" style="text-align:center; display:none">&nbsp;</th>
                                                                        <th width="6%" style="text-align:center" nowrap><?php echo $ItemCode; ?></th>
                                                                        <th width="56%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                                                        <th width="10%" style="text-align:center" nowrap>Qty (<?php echo $Stock; ?>) </th>
                                                                        <th width="8%" style="text-align:center" nowrap>Qty (SO) </th>
                                                                        <th width="18%" style="text-align:center" nowrap>Qty (JO)</th>
                                                            			<th width="18%" style="text-align:center" nowrap>Qty (<?php echo $Ordered; ?>)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
																<?php
																	// JO_NUM, JO_CODE, SO_NUM, SO_CODE, PRJCODE, ITM_CODE, ITM_CATEG,
																	// ITM_UNIT, ITM_NAME, ITM_STOCK, SO_VOLM, ITM_QTY_JO, ITM_QTY.
																	// ITM_PRICE, ITM_TOTAL
																	
																	$sqlJOTMP	= "SELECT A.SO_NUM, A.SO_CODE, A.ITM_CODE, A.ITM_CATEG,
																						B.ITM_NAME, A.ITM_UNIT, 
																						A.ITM_QTY, 
																						A.ITM_PRICE, A.ITM_TOTAL
																					FROM tbl_jo_detail_tmp3 A
																						INNER JOIN tbl_item B ON A.ITM_CODE  = B.ITM_CODE
																							AND B.PRJCODE  = '$PRJCODE'
																					WHERE A.PRJCODE = '$PRJCODE' 
																						AND A.JO_NUM = '$JO_NUM' LIMIT 1";
                                                                    $resJOTMP	= $this->db->query($sqlJOTMP)->result();
                                                                    
                                                                    $i		= 0;
                                                                    $j		= 0;
                                                                    $cRow	= 0;
                                                                    foreach($resJOTMP as $rowJOTMP) :
                                                                        $cRow  			= ++$i;
                                                                        $SO_NUM			= $rowJOTMP->SO_NUM;		//
                                                                        $SO_CODE		= $rowJOTMP->SO_CODE;		//
                                                                        $ITM_CODE		= $rowJOTMP->ITM_CODE;		//
                                                                        $ITM_CATEG		= $rowJOTMP->ITM_CATEG;		//
                                                                        $ITM_NAME		= $rowJOTMP->ITM_NAME;		//
                                                                        $ITM_UNIT		= $rowJOTMP->ITM_UNIT;		//
                                                                        $ITM_QTY		= $rowJOTMP->ITM_QTY;		//
                                                                        $ITM_PRICE		= $rowJOTMP->ITM_PRICE;		//
                                                                        $ITM_TOTAL		= $rowJOTMP->ITM_TOTAL;		//
																		
																		$SO_VOLM	= 0;
																		$sqlSO		= "SELECT A.SO_VOLM
																						FROM tbl_so_detail A
																						WHERE A.PRJCODE = '$PRJCODE' 
																							AND A.SO_NUM = '$SO_NUM'
																							AND A.ITM_CODE = '$ITM_CODE'";
																		$resSO 		= $this->db->query($sqlSO)->result();
																		
																		foreach($resSO as $rowSO) :
																			$SO_VOLM	= $rowSO->SO_VOLM;
																		endforeach;
                                                                        
                                                                        $ITM_STOCK		= 0;
                                                                        $sqlITM			= "SELECT ITM_VOLM FROM tbl_item
                                                                                            WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
                                                                        $resITM 		= $this->db->query($sqlITM)->result();															
                                                                        foreach($resITM as $rowITM) :
                                                                            $ITM_STOCK	= $rowITM->ITM_VOLM;
                                                                        endforeach;
                                                                        if($ITM_STOCK == '')
                                                                            $ITM_STOCK	= 0;
                                                                        
                                                                        $ITM_QTY_JO		= 0;
                                                                        $sqlITMQJO		= "SELECT SUM(A.ITM_QTY) AS TOT_JOQTY
																							FROM tbl_jo_detail A
                                                                                            INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
                                                                                                AND B.PRJCODE = '$PRJCODE'
                                                                                            WHERE A.PRJCODE = '$PRJCODE'
                                                                                                AND A.JO_NUM != '$JO_NUM'
                                                                                                AND A.SO_NUM = '$SO_NUM'
                                                                                                AND A.ITM_CODE = '$ITM_CODE'
                                                                                                AND B.JO_STAT IN (2,3,6)";
                                                                        $resITMQJO 		= $this->db->query($sqlITMQJO)->result();															
                                                                        foreach($resITMQJO as $rowITMQJO) :
                                                                            $ITM_QTY_JO	= $rowITMQJO->TOT_JOQTY;
                                                                        endforeach;
                                                                        if($ITM_QTY_JO == '')
                                                                            $ITM_QTY_JO	= 0;															
																		?>
																		<tr>
																			<td width="7%" height="25" style="text-align:center; vertical-align:middle; display:none" nowrap>
																				<input type="checkbox" class="flat-red" checked>
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][JO_NUM]" id="data4<?php echo $cRow; ?>JO_NUM" value="<?php echo $JO_NUM; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][JO_CODE]" id="data4<?php echo $cRow; ?>JO_CODE" value="<?php echo $JO_CODE; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][SO_NUM]" id="data4<?php echo $cRow; ?>SO_NUM" value="<?php echo $SO_NUM; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][SO_CODE]" id="data4<?php echo $cRow; ?>SO_CODE" value="<?php echo $SO_CODE; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][PRJCODE]" id="data4<?php echo $cRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>">
																			</td>
																			<td width="7%" style="text-align:left; vertical-align:middle" nowrap>
																				<?php echo $ITM_CODE; ?>
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][ITM_CODE]" id="data4<?php echo $cRow; ?>ITM_CODE" value="<?php echo $ITM_CODE; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][ITM_CATEG]" id="data4<?php echo $cRow; ?>ITM_CATEG" value="<?php echo $ITM_CATEG; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][ITM_UNIT]" id="data4<?php echo $cRow; ?>ITM_UNIT" value="<?php echo $ITM_UNIT; ?>">
																			</td>
																			<td width="45%" style="text-align:left; vertical-align:middle" nowrap>
																				<?php echo $ITM_NAME; ?>
																			</td>
																			<td width="9%" style="text-align:right; vertical-align:middle" nowrap>
																				<?php echo number_format($ITM_STOCK,2); ?>
																				<input type="hidden" class="form-control" name="ITM_STOCK_<?php echo $cRow; ?>" id="ITM_STOCK_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo $ITM_STOCK; ?>">
																			</td>
																			<td width="7%" nowrap style="text-align:right; vertical-align:middle">
																				<?php echo number_format($SO_VOLM,2); ?>
																				<input type="hidden" class="form-control" name="SO_VOLMX_<?php echo $cRow; ?>" id="SO_VOLMX_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo $SO_VOLM; ?>">
																			</td>
																			<td width="7%" nowrap style="text-align:right; vertical-align:middle">
																				<?php echo number_format($ITM_QTY_JO, 2); ?>
																				<input type="hidden" class="form-control" name="SO_JOVOLM_<?php echo $cRow; ?>" id="SO_JOVOLM_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo $ITM_QTY_JO; ?>">
																			</td>
																			<td width="18%" style="text-align:right" nowrap>
																				<?php echo number_format($ITM_QTY,2); ?>
                                                                                <input type="hidden" class="form-control" name="ITM_QTYX3_<?php echo $cRow; ?>" id="ITM_QTYX3_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo number_format($ITM_QTY,2); ?>" onBlur="chgQTY(this,'<?php echo $cRow; ?>');">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][ITM_QTY]" id="data4<?php echo $cRow; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][ITM_PRICE]" id="data4<?php echo $cRow; ?>ITM_PRICE" value="<?php echo $ITM_PRICE; ?>">
																				<input type="hidden" name="data4[<?php echo $cRow; ?>][ITM_TOTAL]" id="data4<?php echo $cRow; ?>ITM_TOTAL" value="<?php echo $ITM_TOTAL; ?>">
																			</td>
																		</tr>
																		<?php
                                                                    endforeach;
                                                                ?>
                                                                <input type="hidden" name="totRow4" id="totRow4" value="<?php echo $cRow; ?>">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                        			</div>
                                            	</div>
                                                <div class="box-header with-border">
                                                    <table width="100%" border="0">
                                                        <tr>
                                                            <td width="15%" style="text-align:left;" nowrap>
                                                                <?php echo anchor("$back1",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-ban-circle"></i>&nbsp;&nbsp;'.$Canceled.'</button>'); ?>
                                                            </td>
                                                            <td width="85%" style="text-align:right" nowrap>
                                                                <button class="btn btn-warning" onClick="isBack3(3);" name="backBtn" id="">
                                                                    <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
                                                                </button>
                                                                <button class="btn btn-primary" onClick="isNext4(4);" disabled>
                                                                    <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
                                                                </button>
                                                                <button class="btn btn-success" onClick="isNextF(5);" <?php if($FRST_STAT != 1 && $FRST_STAT != 4) { ?> disabled <?php } ?>>
                                                                    <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            
                                                <div id="loading_4" class="overlay" <?php if($loading_4 == 1) { ?> style="display:none" <?php } ?>>
                                                    <i class="fa fa-refresh fa-spin"></i>
                                                </div>
                                        	</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
						<?php
                        }
                    ?>
                    <script>
                        function isBack3(thisValue)
                        {
                            document.getElementById('Step_Bef').value 	= 4;
                            document.getElementById('Step_Next').value 	= 3;
							
                        }
						
                        function isNextF(thisValue)
                        {
                            document.getElementById('Step_Bef').value 	= 4;
                            document.getElementById('Step_Next').value 	= 5;
                        }
						
						function chkBtn()
						{
							var Step_Next	= document.getElementById('Step_Next').value;
							if(Step_Next == 3)
								$('#frmJOSum').attr('action', '');
							else
								$('#frmJOSum').attr('action', '<?php echo $form_action; ?>');
						}
					</script>                    
				</div>
			</div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
</body>

</html>

<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.full.min.js'; ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.date.extensions.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/input-mask/jquery.inputmask.extensions.js'; ?>"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.js'; ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/bootstrap-datepicker.js'; ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.js'; ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.js'; ?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/icheck.min.js'; ?>"></script>
<!-- Page script -->
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
</script>

<script>
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
	{
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
</script>
<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>