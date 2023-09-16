<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Oktober 2018
	* File Name	= v_joborder_form.php
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
	$JO_AMOUNT		= 0;
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

if(isset($_POST['SO_NUMX']))
{
	$SO_NUM		= $_POST['SO_NUMX'];
}

$back1	= site_url('c_production/c_j0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));

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
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

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
			if($TranslCode == 'ShowDetail')$ShowDetail = $LangTransl;
			if($TranslCode == 'Product')$Product = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
			if($TranslCode == 'DocStatus')$DocStatus = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$stepalert1		= "Pilih salah satu Nomor Sales Order yang akan dibuatkan Job Order.";
			$stepalert2		= "Perhatian ...! Cek informasi dokumen dengan detail. Dan isikan data JO dengan benar.";
			$stepalert3		= "Tentukan Barang Jadi (Finish Good) yang akan diproduksi.";
			$stepalert3a	= "Tentukan tahapan-tahapan proses produksi yang akan digunakan.";
			$stepalert4		= "Pastikan bahwa data yang Anda masukan sudah benar.";
			$docalert1		= "Peringatan";
			$docalert2		= "Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.";
	        $isManual		= "Centang untuk kode manual.";
			$Step1Des		= "Orde Penjualan";
			$Step2Des		= "Informasi Dokumen";
			$Step3Des		= "Pemilihan Produk";
			$Step3Desa		= "Tahapan Produksi";
			$Step4Des		= "Rekapitulasi";
			
			$alert1			= "Pilih salah satu nomor Sales Order (SO).";
			$alert2			= "Masukan jumlah volume produksi.";
			$alert3			= "Masukan catatan dokumen JO.";
			$alert4			= "Jumlah yang di-JO lebih besar dari sisa SO.";
			$alert5			= "Tidak ada material FG yang akan diproduksi.";
			$alert6			= "Sudah tidak ada sisa SO yang bisa Anda buatkan JO.";
			$alert7			= "Tahapan ke ";
			$alert8			= "sudah ditempati oleh tahapan ";
			$alert9			= "silahkan pilih mesin pada tahapan ";
			$alert10		= "Anda belum menentukan urutan proses pada tahapan ";
		}
		else
		{
			$stepalert1		= "Select one of the Sales Order Numbers that will be made a Job Order.";
			$stepalert2		= "Attention ...! Check document information in detail. And fill in the JO data correctly.";
			$stepalert3		= "Please specify Finished Goods to be produced.";
			$stepalert3a	= "Determine the stages of the production process to be used.";
			$stepalert4		= "Make sure that the data you entered is correct.";
			$docalert1		= "Warning";
			$docalert2		= "Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.";
			$isManual		= "Check to manual code.";
			$Step1Des		= "Sales Order";
			$Step2Des		= "Document Information";
			$Step3Des		= "Finish Goods Selection";
			$Step3Desa		= "Production Stages";
			$Step4Des		= "Summary";
			
			$alert1			= "Please select one of Sales Order (SO) Number.";
			$alert2			= "Please input prodcution volume.";
			$alert3			= "Please input Notes of this JO document.";
			$alert4			= "JO Qty is greater than of Remaining Qty.";
			$alert5			= "No FG Material will be produced.";
			$alert6			= "There are no remaining of SO Qty that you can make JO.";
			$alert7			= "Step ";
			$alert8			= "is already set to step ";
			$alert9			= "Please select a machine for step ";
			$alert10		= "You not yet select an process order for step ";
		}
		
		$SO_DATEV		= '';
		$SO_DATEV1		= '';
		$CUST_DESC		= '';
		$CUST_ADDRESS	= '';
		$SO_NOTES		= '';
		$SO_NOTES1		= '';
		$SO_REFRENS		= '';
		$OFF_NUM 		= '';
		$OFF_CODE 		= '';
		$CCAL_NUM 		= '';
		$CCAL_CODE 		= '';
		$BOM_NUM 		= '';
		$BOM_CODE 		= '';
		
		if($SO_NUM != '')
		{
			$sqlSOA			= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD, A.SO_NOTES, A.SO_NOTES1, A.SO_REFRENS,
									A.CUST_CODE, B.CUST_DESC, B.CUST_ADD1,
									A.OFF_NUM, A.OFF_CODE, A.CCAL_NUM, A.CCAL_CODE, A.BOM_NUM, A.BOM_CODE
								FROM tbl_so_header A
									INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.SO_STAT IN (3,6)
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
				$SO_REFRENS		= $rowSOA->SO_REFRENS;
				$CUST_CODE		= $rowSOA->CUST_CODE;
				$CUST_DESC		= $rowSOA->CUST_DESC;
				$CUST_ADDRESS	= $rowSOA->CUST_ADD1;
				
				$OFF_NUM 		= $rowSOA->OFF_NUM;
				$OFF_CODE 		= $rowSOA->OFF_CODE;
				$CCAL_NUM 		= $rowSOA->CCAL_NUM;
				$CCAL_CODE 		= $rowSOA->CCAL_CODE;
				$BOM_NUM 		= $rowSOA->BOM_NUM;
				$BOM_CODE 		= $rowSOA->BOM_CODE;
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
		
		$IMGC_FILENAMEX	= 'username.jpg';
		$sqlCST			= "SELECT IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE' LIMIT 1";
		$resCST			= $this->db->query($sqlCST)->result();
		foreach($resCST as $rowCST) :
			$IMGC_FILENAMEX		= $rowCST->IMGC_FILENAMEX;
		endforeach;
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE.'/'.$IMGC_FILENAMEX);
		if (!file_exists('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE))
		{
			$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/username.jpg');
		}
		
		$showFORM		= 1;
		$loading_2		= 1;

		$url_QRC		= site_url('c_production/c_j0b0rd3r/s3l4llQRC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// JO_NUM - JO_AMOUNT
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$JO_NUM'";
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
				$APPROVE_AMOUNT 	= $JO_AMOUNT;
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

		$secGetMcn	= base_url().'index.php/c_production/c_j0b0rd3r/genMcnCal/'; // Generate Code
	?>

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
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $mnName; ?>
			    <small>&nbsp;</small>
			  </h1>
		</section>

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
				<div class="box box-primary">
	                <div class="tab-content">
	                	<form class="form-horizontal" name="frmsrch1" method="post" action="" style="display:none">
	                    	<input type="text" name="SO_NUMX" id="SO_NUMX" value="<?php echo $SO_NUM; ?>">
	                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
	                    </form>
	                	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
	                    <form class="form-horizontal" name="frmSOInfo" id="frmSOInfo" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkSOInfo()">
	                    	<input type="hidden" name="rowCount" id="rowCount" value="0">
	                        <div class="active tab-pane" id="SOInfo">
	                            <input type="hidden" name="Step_Bef" id="Step_Bef" value="1">
	                            <input type="hidden" name="Step_Next" id="Step_Next" value="3">
	                            <input type="hidden" name="JO_NUM" id="JO_NUM" value="<?php echo $JO_NUM; ?>">
	                            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
	                            <input type="hidden" name="SO_NUM" id="SO_NUM" value="<?php echo $SO_NUM; ?>">
	                            <input type="hidden" name="SO_CODE" id="SO_CODE" value="<?php echo $SO_CODE; ?>">
	                            <input type="hidden" name="CUST_CODE" id="CUST_CODE" value="<?php echo $CUST_CODE; ?>">
	                            <input type="hidden" name="CUST_DESC" id="CUST_DESC" value="<?php echo $CUST_DESC; ?>">
	                            <input type="hidden" name="JOSTF_NUM" id="JOSTF_NUM" value="STFQC<?php echo $JO_NUM; ?>">
	                            <div class="box-body">
	                                <?php if($isSetDocNo == 0) { ?>
	                                    <div class="alert alert-danger alert-dismissible">
	                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
	                                            <?php echo $docalert2; ?>
	                                    </div>
	                                <?php } ?>
	                                
	                                <!-- JO_CODE -->
	                                <div class="form-group">
	                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name"><?php echo $Code; ?> JO</span>
	                                    </label>
	                                    <div class="col-md-10 col-sm-10 col-xs-12">
	                                        <?php if($isDis == 0) { ?>
	                                            <input type="text" class="form-control" name="JO_CODE" id="JO_CODE" value="<?php echo $JO_CODE; ?>" >
	                                        <?php } else { ?>
	                                            <input type="hidden" class="form-control" name="JO_CODE" id="JO_CODE" value="<?php echo $JO_CODE; ?>" >
	                                            <input type="text" class="form-control" name="JO_CODE1" id="JO_CODE1" value="<?php echo $JO_CODE; ?>" disabled >
	                                        <?php } ?>
	                                    </div>
	                                </div>
	                                
	                                <!-- SO_CODE -->
	                                <div class="form-group">
	                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name"><?php echo $Code; ?> SO</span>
	                                    </label>
	                                    <div class="col-md-10 col-sm-10 col-xs-12">
	                                        <select name="SO_NUM1" id="SO_NUM1" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;><?php echo $Code; ?> SO" onChange="getSODET(this.value)">
	                                            <option value="0"> --- </option>
	                                            <?php
	                                            	if($task == 'add')
	                                            	{
	                                                	$sqlSO	= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, 
	                                                					A.SO_DUED, A.SO_PRODD, A.CUST_CODE, 
	                                                					B.CUST_DESC
	                                                                FROM tbl_so_header A
	                                                                    INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
	                                                                WHERE A.PRJCODE = '$PRJCODE'
	                                                                    AND A.SO_STAT = 3";
	                                                }
	                                                else
	                                                {
	                                                	$sqlSO	= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, 
	                                                					A.SO_DUED, A.SO_PRODD, A.CUST_CODE, 
	                                                					B.CUST_DESC
	                                                                FROM tbl_so_header A
	                                                                    INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
	                                                                WHERE A.PRJCODE = '$PRJCODE'
	                                                                    AND A.SO_STAT = 3
	                                                                UNION ALL
	                                                                SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, 
	                                                					A.SO_PRODD, A.CUST_CODE, B.CUST_DESC
	                                                                FROM tbl_so_header A
	                                                                    INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
	                                                                WHERE A.PRJCODE = '$PRJCODE'
	                                                                    AND A.SO_NUM = '$SO_NUM'";
	                                                }
	                                                $resSO 		= $this->db->query($sqlSO)->result();
	                                                foreach($resSO as $rowSO) :
	                                                    $SO_NUM1	= $rowSO->SO_NUM;
	                                                    $SO_CODE1	= $rowSO->SO_CODE;
	                                                    $SO_DATE1	= date('d M Y', strtotime($rowSO->SO_DATE));
	                                                    $CUST_DESC1	= $rowSO->CUST_DESC;
	                                                    ?>
	                                                    <option value="<?php echo $SO_NUM1; ?>" <?php if($SO_NUM1 == $SO_NUM){?> selected <?php } ?>> <?php echo "$SO_CODE1&nbsp;&nbsp;&nbsp;$SO_DATE1&nbsp;&nbsp;&nbsp;$CUST_DESC1"; ?> </option>
	                                                    <?php
	                                                endforeach;
	                                            ?>
	                                        </select>
	                                    </div>
	                                </div>
	                                <script>
										function getSODET(SONUM) 
										{
											document.getElementById("SO_NUMX").value = SONUM;
											document.frmsrch1.submitSrch1.click();
										}
									</script>
	                                
	                                <!-- JO_DATE -->
	                                <div class="form-group">
	                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="last-name"><?php echo $Date ?></span>
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
	                                
	                                <!-- JO_PRODD -->
	                                <div class="form-group">
	                                    <label for="middle-name" class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo $ProdPlan ?></label>
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
	                                
	                                <!-- PRJCODE -->
	                                <div class="form-group" style="display:none">
	                                    <label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo $Project ?></label>
	                                    <div class="col-md-6 col-sm-6 col-xs-12">
	                                        <select name="PRJCODE" id="PRJCODE" class="form-control" disabled>
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
	                                    </div>
	                                </div>
	                                
	                                <!-- JO_NOTES -->
	                                <div class="form-group">
	                                    <label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo $Description ?></span>
	                                    </label>
	                                    <div class="col-md-10 col-sm-10 col-xs-12">
	                                        <?php if($isDis == 0) { ?>
	                                            <textarea class="form-control" name="JO_NOTES"  id="JO_NOTES"><?php echo $JO_NOTES; ?></textarea>
	                                        <?php } else { ?>
	                                            <textarea class="form-control" name="JO_NOTES"  id="JO_NOTES" style="display:none"><?php echo $JO_NOTES; ?></textarea>
	                                            <textarea class="form-control" name="JO_NOTES1"  id="JO_NOTES1" disabled><?php echo $JO_NOTES; ?></textarea>
	                                        <?php } ?>
	                                    </div>
	                                </div>
	                                
	                                <!-- JO_NOTES2 -->
	                                <div class="form-group" <?php if($JO_NOTES2 == '') { ?> style="display:none" <?php } ?>>
	                                    <label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo $ApproverNotes ?></span>
	                                    </label>
	                                    <div class="col-md-10 col-sm-10 col-xs-12">
	                                        <textarea class="form-control" name="JO_NOTES2"  id="JO_NOTES2" disabled><?php echo $JO_NOTES2; ?></textarea>
	                                    </div>
	                                </div>
	                                
			                        <div class="form-group" > <!-- JO_STAT -->
			                          	<label class="control-label col-md-2 col-sm-2 col-xs-12"><?php echo $Status ?> </label>
			                          	<div class="col-md-10 col-sm-10 col-xs-12">
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $JO_STAT; ?>">
			                                <?php
												$isDisabled = 1;
												if($JO_STAT == 1 || $JO_STAT == 4)
												{
													$isDisabled = 0;
												}
											?>
			                                <select name="JO_STAT" id="JO_STAT" class="form-control select2" onChange="selStat(this.value)">
			                                    <?php
			                                    if($JO_STAT != 1 AND $JO_STAT != 4) 
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($JO_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                            <option value="2"<?php if($JO_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
			                                            <option value="3"<?php if($JO_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
			                                            <option value="4"<?php if($JO_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                            <option value="5"<?php if($JO_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                            <option value="6"<?php if($JO_STAT == 6) { ?> selected <?php } ?>>Closed</option>
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
			                        <script type="text/javascript">
			                        	function selStat(statVal)
			                        	{
			                        		var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
			                        		if(STAT_BEFORE == 3 && statVal == 6)
			                        		{
			                        			document.getElementById('tblClose').style.display = '';
			                        		}
			                        		else if(STAT_BEFORE == 5 || statVal == 6)
			                        		{
			                        			document.getElementById('tblClose').style.display = 'none';
			                        		}
			                        	}
			                        </script>
	                                
	                                <!-- ITEM FG - DETAIL -->
	                                <div class="box box-success">
	                                	<br>
	                                    <table id="example2" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
	                                        <thead>
	                                            <tr>
	                                                <th width="2%" height="40" style="text-align:center;">&nbsp;</th>
	                                                <th width="14%" style="text-align:center" nowrap><?php echo $ItemCode; ?></th>
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
	                                                $sqlSO	= "SELECT A.SO_NUM, A.SO_CODE, A.ITM_CODE,
																	B.ITM_CATEG, B.ITM_NAME, A.ITM_UNIT,
	                                                                0 AS ITM_QTY, A.SO_PRICE AS ITM_PRICE,
	                                                                A.SO_VOLM, A.SO_COST AS ITM_TOTAL																				
	                                                            FROM tbl_so_detail A
	                                                                INNER JOIN tbl_item B ON A.ITM_CODE  = B.ITM_CODE
	                                                                    AND B.PRJCODE  = '$PRJCODE'
	                                                            WHERE A.PRJCODE = '$PRJCODE' AND A.SO_NUM = '$SO_NUM'";
	                                            }
	                                            else
	                                            {
	                                               $sqlSO	= "SELECT A.SO_NUM, A.SO_CODE, A.ITM_CODE,
	                                                   				A.ITM_UNIT, A.ITM_QTY, A.ITM_PRICE,
			                                                        B.SO_VOLM, B.SO_COST AS ITM_TOTAL
			                                                    FROM tbl_jo_detail A
			                                                    INNER JOIN tbl_so_detail B ON A.SO_NUM = B.SO_NUM
																		AND B.PRJCODE = '$PRJCODE'
			                                                    WHERE A.PRJCODE = '$PRJCODE'
			                                                    	AND A.JO_NUM = '$JO_NUM'";
	                                            }
	                                            $resSO 		= $this->db->query($sqlSO)->result();
	                                            
	                                            $i		= 0;
	                                            $j		= 0;
	                                            $cRow	= 0;
	                                            $TOTFG 	= 0;
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
	                                                $SO_VOLM		= $rowSO->SO_VOLM;
	                                                $ITM_QTY		= $rowSO->ITM_QTY;		// JO_QTY
													
	                                                $ITM_NAME		= '';
	                                                $ITM_CATEG		= '';
													$ITM_STOCK		= 0;
													$NEEDQRC		= 0;
	                                                $sqlITM			= "SELECT ITM_NAME, ITM_CATEG, ITM_VOLM, NEEDQRC FROM tbl_item
	                                                                    WHERE PRJCODE = '$PRJCODE' 
	                                                                        AND ITM_CODE = '$ITM_CODE' LIMIT 1";
	                                                $resITM 		= $this->db->query($sqlITM)->result();															
	                                                foreach($resITM as $rowITM) :
	                                                    $ITM_NAME	= $rowITM->ITM_NAME;
	                                                    $ITM_CATEG	= $rowITM->ITM_CATEG;
	                                                    $ITM_STOCK	= $rowITM->ITM_VOLM;
	                                                    $NEEDQRC	= $rowITM->NEEDQRC;
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

	                                               	$TOTFG 			= $TOTFG + $ITM_QTY;
	                                                ?>
	                                                <tr>
	                                                    <td width="2%" height="25" style="text-align:center; vertical-align:middle;">
	                                                        <input type="hidden" name="data1[<?php echo $cRow; ?>][JO_NUM]" id="data1<?php echo $cRow; ?>JO_NUM" value="<?php echo $JO_NUM; ?>">
	                                                        <input type="hidden" name="data1[<?php echo $cRow; ?>][JO_CODE]" id="data1<?php echo $cRow; ?>JO_CODE" value="<?php echo $JO_CODE; ?>">
	                                                        <input type="hidden" name="data1[<?php echo $cRow; ?>][SO_NUM]" id="data1<?php echo $cRow; ?>SO_NUM" value="<?php echo $SO_NUM; ?>">
	                                                        <input type="hidden" name="data1[<?php echo $cRow; ?>][SO_CODE]" id="data1<?php echo $cRow; ?>SO_CODE" value="<?php echo $SO_CODE; ?>">
	                                                        <input type="hidden" name="data1[<?php echo $cRow; ?>][PRJCODE]" id="data1<?php echo $cRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>">
	                                                        <?php
																/*if($JO_STAT == 1)
																{
																	?>
																		<a href="#" onClick="delFG()" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
																	<?php
																}
																else
																{*/
																	echo "$cRow.";
																//}
															?>
	                                                    </td>
	                                                    <td width="14%" style="text-align:left; vertical-align:middle" nowrap>
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
	                                                            <input type="text" class="form-control" name="ITM_QTYX3_<?php echo $cRow; ?>" id="ITM_QTYX3_<?php echo $cRow; ?>" style="text-align:right" value="<?php echo number_format($ITM_QTY,2); ?>" onBlur="chgQTY(this,'<?php echo $cRow; ?>');" onKeyPress="return isIntOnlyNew(event);">
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
	                                        <input type="hidden" name="TOTFG" id="TOTFG" value="<?php echo $TOTFG; ?>">
	                                        </tbody>
	                                    </table>
	                                </div>
	                                <?php
	                                    $TOTSTEP	= 0;
	                                   	$sqlTSTEP	= "SELECT COUNT(DISTINCT BOMSTF_ORD) AS TOTSTEP
	                                   					FROM tbl_bom_stfdetail WHERE BOM_NUM = '$BOM_NUM'";
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
	                            			for(i=1; i<=totSTP; i++)
											{
												stfORD	= document.getElementById('selSTEP'+i+'JOSTF_ORD').value;
												STEP_X	= document.getElementById('dataSTEP'+i+'STEP_X').value;
												//if(stfORD == selORD && selORD == i)
												if(rowSel != i && stfORD == selORD && selORD != 0)
												{
													swal('<?php echo $alert7; ?>'+i+' <?php echo $alert8; ?>'+STEP_X,
													{
														icon: "warning",
													})
										            .then(function()
										            {
										                swal.close();
														$('#selSTEP'+rowSel+'JOSTF_ORD').val(0).trigger('change');
										            });
													return false;
												}
											}
	                            		}
	                            	</script>
	                                <div class="box-body">
	                                    <ul class="timeline">
	                                        <?php
	                                            $rowStep	= 0;
	                                            $resSTEP	= 0;
	                                            /*$sqlProds	= "SELECT PRODS_STEP, PRODS_NAME, PRODS_DESC
	                                                            FROM tbl_prodstep WHERE PRODS_STAT = 1";*/
	                                            if($task == 'add')
	                                            {
		                                            $sqlProds	= "SELECT DISTINCT A.PRODS_STEP, A.PRODS_NAME, A.PRODS_DESC,
		                                            					A.PRODS_ORDER, B.BOMSTF_ORD
		                                            				FROM tbl_prodstep A
																		INNER JOIN tbl_bom_stfdetail B ON A.PRODS_STEP = B.BOMSTF_STEP
																	WHERE A.PRODS_STAT = 1
																		AND B.BOM_NUM = '$BOM_NUM'
																		ORDER BY A.PRODS_ORDER ASC";
												}
												else
												{
		                                            $sqlProds	= "SELECT DISTINCT A.PRODS_STEP, A.PRODS_NAME, A.PRODS_DESC,
		                                            					A.PRODS_ORDER, B.JOSTF_ORD AS BOMSTF_ORD
		                                            				FROM tbl_prodstep A
																		INNER JOIN tbl_jo_stfdetail B ON A.PRODS_STEP = B.JOSTF_STEP
																	WHERE A.PRODS_STAT = 1
																		AND B.BOM_NUM = '$BOM_NUM'
																		ORDER BY A.PRODS_ORDER ASC";
												}
	                                            $resProds	= $this->db->query($sqlProds)->result();
	                                            foreach($resProds as $rowProds) :
	                                                $rowStep	= $rowStep + 1;
	                                                $PRODS_STEP	= $rowProds->PRODS_STEP;
	                                                $P_STEP		= $rowProds->PRODS_STEP;
	                                                $STEP_X		= $rowProds->PRODS_NAME;
	                                                $STEP_XD	= $rowProds->PRODS_DESC;
	                                                $STEP_ORDER	= $rowProds->BOMSTF_ORD;
	                                                $MCN_NUM	= '';
	                                                $MCN_CODE	= '';
	                                                $sqlMCN		= "SELECT MCN_NUM, MCN_CODE FROM tbl_jo_stfdetail
	                                               					WHERE BOM_NUM = '$BOM_NUM' AND JO_NUM = '$JO_NUM'
	                                               						AND JOSTF_STEP = '$PRODS_STEP'";
	                                            	$resMCN 	= $this->db->query($sqlMCN)->result();
	                                            	 foreach($resMCN as $rowMCN) :
		                                                $MCN_NUM	= $rowMCN->MCN_NUM;
		                                                $MCN_CODE	= $rowMCN->MCN_CODE;
		                                            endforeach;

	                                                
	                                                // CHECK STEP PROCESS
	                                                $sqlSTEP	= "tbl_bom_stfdetail WHERE BOM_NUM = '$BOM_NUM' 
	                                                                AND BOMSTF_STEP = '$P_STEP'
	                                                                AND PRJCODE = '$PRJCODE'";
	                                                $resSTEP 	= $this->db->count_all($sqlSTEP);
	                                                $selSTEP	= '';
	                                                if($resSTEP > 0)
	                                                {
	                                                    $selSTEP= $P_STEP ;
	                                                }
	                                                
	                                                
	                                                $resRMC		= 0;
	                                                $resFGC		= 0;
	                                                //if($task == 'edit')
	                                                //{
	                                                    $sqlRMC		= "tbl_bom_stfdetail A
		                                                                WHERE A.BOM_NUM = '$BOM_NUM'
		                                                                    AND A.BOMSTF_TYPE = 'IN'
		                                                                    AND A.PRJCODE = '$PRJCODE'";
	                                                    $resRMC 	= $this->db->count_all($sqlRMC);
	                                                                                                                                                        
	                                                    $sqlFGC		= "tbl_bom_stfdetail A
		                                                                WHERE A.BOM_NUM = '$BOM_NUM'
		                                                                    AND A.BOMSTF_TYPE = 'OUT'
		                                                                    AND A.PRJCODE = '$PRJCODE'";
	                                                    $resFGC 	= $this->db->count_all($sqlFGC);
	                                                //}

	                                                    //$JOSTF_ORD	= $STEP_ORDER;
	                                                    $JOSTF_ORD	= $rowStep;

	                                                   	/*$sqlTSORD1	= "tbl_jo_stfdetail
	                                                   					WHERE BOM_NUM = '$BOM_NUM' AND JOSTF_STEP = '$PRODS_STEP'";
	                                                	$resSORD1 	= $this->db->count_all($sqlTSORD1);
	                                                	if($resSORD1 > 0)
	                                                	{
		                                                   	$sqlTSORD		= "SELECT JOSTF_ORD FROM tbl_jo_stfdetail
		                                                   					WHERE BOM_NUM = '$BOM_NUM'
		                                                   						AND JOSTF_STEP = '$PRODS_STEP'";
		                                                	$resSORD 		= $this->db->query($sqlTSORD)->result();
		                                                	 foreach($resSORD as $rowSORD) :
				                                                $JOSTF_ORD	= $rowSORD->JOSTF_ORD;
				                                            endforeach;
				                                        }*/
	                                                ?>
	                                                    <input type="hidden" name="dataSTEP[<?php echo $rowStep; ?>][P_STEP]" id="dataSTEP<?php echo $rowStep; ?>P_STEP" value="<?php echo $PRODS_STEP; ?>">
	                                                    <input type="hidden" id="dataSTEP<?php echo $rowStep; ?>STEP_X" value="<?php echo $STEP_X; ?>">
	                                                    <div class="box box-warning">
	                                                        <div class="box-header with-border">
	                                                            <font style="font-weight:bold"><?php echo "$rowStep. $STEP_X"; ?></font>
	                                                            <input type="hidden" name="selSTEP[<?php echo $rowStep; ?>][P_STEP]" id="selSTEP<?php echo $rowStep; ?>P_STEP" value="<?php echo $selSTEP; ?>">
	                                                            <div class="box-tools pull-right">
	                                                            	<!-- SELECT MACHINE -->
		                                                            <select name="selSTEP[<?php echo $rowStep; ?>][MCN_NUM]" id="selSTEP<?php echo $rowStep; ?>MCN_NUM" class="form-control select2" onChange="chgMCN(this.value, <?php echo $rowStep; ?>,  '<?php echo $P_STEP; ?>')">
		                                                            	<option value="0"> --- </option>
		                                                            	<option value="NMCN" <?php if($MCN_NUM == "NMCN") { ?> selected <?php } ?>> Manual </option>
			                                                            <?php
			                                                            	$sqlMCN	= "SELECT MCN_NUM, MCN_CODE, MCN_NAME, MCN_ITMCAL
			                                                            				FROM tbl_machine
			                                                            				WHERE MCN_PSTEP LIKE '%$PRODS_STEP%'";
							                                            	$resMCN = $this->db->query($sqlMCN)->result();
							                                            	foreach($resMCN as $rowMCN) :
								                                                $MCN_NUM1	= $rowMCN->MCN_NUM;
								                                                $MCN_CODE1	= $rowMCN->MCN_CODE;
								                                                $MCN_NAME1	= $rowMCN->MCN_NAME;
								                                                $MCN_ITMCAL	= $rowMCN->MCN_ITMCAL;
		                                    									?>
					                                            				<option value="<?php echo $MCN_NUM1; ?>" <?php if($MCN_NUM1 == $MCN_NUM) { ?> selected <?php } ?>><?php echo $MCN_NAME1; ?></option>
					                                            				<?php
					                                            			endforeach;
					                                            		?>
				                                            		</select>
		                                                            <select name="selSTEP[<?php echo $rowStep; ?>][JOSTF_ORD]" id="selSTEP<?php echo $rowStep; ?>JOSTF_ORD" class="form-control select2" style="max-width:80px" onChange="chgORD(this.value, <?php echo $rowStep; ?>)">
		                                                            	<option value="0"> --- </option>
		                                                            	<option value="99">Skip</option>
			                                                            <?php
			                                                            	for($a=1; $a<=$TOTSTEP; $a++)
		                                    								{ ?>
					                                            			<option value="<?php echo $a; ?>" <?php if($a == $JOSTF_ORD) { ?> selected <?php } ?>><?php echo $a; ?></option>
					                                            		<?php } ?>
				                                            		</select>
	                                                            </div>
	                                                        </div>
	                                                        
	                                                        <div class="box-body">
	                                                            <div class="row">
	                                                                <!-- START : RM NEEDED -->
	                                                                    <div class="col-md-6">
	                                                                        <div class="box box-danger">
	                                                                            <br>
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
	                                                                                    	if($task=='add'){
	                                                                                        $sqlRM	= "SELECT A.ITM_CODE, B.ITM_NAME,
	                                                                                        				A.ITM_QTY AS BOM_QTY,
	                                                                                        				A.ITM_PRICE AS BOM_PRICE,
	                                                                                        				A.ITM_QTY, A.ITM_PRICE,
	                                                                                        				B.ITM_GROUP, B.ITM_CATEG, B.NEEDQRC,
	                                                                                                        B.ACC_ID, B.ACC_ID_UM, B.NEEDQRC,
	                                                                                                        A.ITM_UNIT, A.ITM_SCALE, B.ISWIP,
	                                                                                                        B.ISFG
	                                                                                                    FROM tbl_bom_stfdetail A
	                                                                                                        LEFT JOIN tbl_item B
	                                                                                                        ON A.ITM_CODE = B.ITM_CODE
	                                                                                                            AND B.PRJCODE = '$PRJCODE'
	                                                                                                    WHERE A.BOM_NUM = '$BOM_NUM'
	                                                                                                        AND A.BOMSTF_TYPE = 'IN'
	                                                                                                        AND A.BOMSTF_STEP = '$P_STEP'
	                                                                                                        AND A.PRJCODE = '$PRJCODE'";
	                                                                                        }
	                                                                                        else {
	                                                                                        $sqlRM	= "SELECT A.ITM_CODE, B.ITM_NAME,
	                                                                                        				A.JOSTF_NUM, A.BOM_QTY,
	                                                                                        				A.BOM_PRICE,
	                                                                                        				A.ITM_QTY, A.ITM_PRICE,
	                                                                                                        B.ITM_GROUP, B.ITM_CATEG, B.NEEDQRC,
	                                                                                                        B.ACC_ID, B.ACC_ID_UM, B.NEEDQRC,
	                                                                                                        A.ITM_UNIT, A.ITM_SCALE, B.ISWIP,
	                                                                                                        B.ISFG
	                                                                                                    FROM tbl_jo_stfdetail A
	                                                                                                        LEFT JOIN tbl_item B
	                                                                                                        ON A.ITM_CODE = B.ITM_CODE
	                                                                                                            AND B.PRJCODE = '$PRJCODE'
	                                                                                                    WHERE A.BOM_NUM = '$BOM_NUM'
	                                                                                                        AND A.JOSTF_TYPE = 'IN'
	                                                                                                        AND A.JOSTF_STEP = '$P_STEP'
	                                                                                                        AND A.PRJCODE = '$PRJCODE'
	                                                                                                        AND A.JO_NUM = '$JO_NUM'";
	                                                                                        }
	                                                                                        $resRM 	= $this->db->query($sqlRM)->result();
	                                                                                        foreach($resRM as $rowRM) :
	                                                                                            $rowMTR  		= ++$i;
	                                                                                            if($task=='add')
			                                                                                    	$JOSTF_NUM	= "STFQC".$TRXTIME1;
			                                                                                   	else
			                                                                                    	$JOSTF_NUM	= $rowRM->JOSTF_NUM;

	                                                                                            $ITM_CODE 		= $rowRM->ITM_CODE;
	                                                                                            $ITM_GROUP 		= $rowRM->ITM_GROUP;
	                                                                                            $ITM_CATEG 		= $rowRM->ITM_CATEG;
	                                                                                            $ITM_NAME 		= $rowRM->ITM_NAME;
	                                                                                            if($ITM_NAME == '')
	                                                                                            {
	                                                                                            	$sqlGRP		= "SELECT ICOLL_NOTES 
	                                                                                            					FROM tbl_item_collh 
	                                                                                            					WHERE ICOLL_CODE = '$ITM_CODE'
	                                                                                                                AND PRJCODE = '$PRJCODE'";
	                                                                                                $resGRP		= $this->db->query($sqlGRP)->result();
	                                                                                                foreach($resGRP as $rowGRP) :
	                                                                                                    $ITM_NAME	= $rowGRP->ICOLL_NOTES;
	                                                                                                endforeach;
	                                                                                            }
	                                                                                            $BOM_QTY 		= $rowRM->BOM_QTY;
	                                                                                            $BOM_PRICE 		= $rowRM->BOM_PRICE;
	                                                                                            $ITM_QTY 		= $rowRM->ITM_QTY;
	                                                                                            $ITM_PRICE 		= $rowRM->ITM_PRICE;
	                                                                                            $ACC_ID 		= $rowRM->ACC_ID;
	                                                                                            $ACC_ID_UM 		= $rowRM->ACC_ID_UM;
	                                                                                            $NEEDQRC 		= $rowRM->NEEDQRC;
	                                                                                            $ITM_UNIT 		= $rowRM->ITM_UNIT;
	                                                                                            $ITM_SCALE		= $rowRM->ITM_SCALE;
	                                                                                            $ISWIP			= $rowRM->ISWIP;
	                                                                                            $ISFG			= $rowRM->ISFG;
	                                                                                            if($ISFG == 1)
	                                                                                            	$ISWIP		= 1;
	                                                                                            
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
	                                                                                            <tr id="trIN_<?php echo "$P_STEP"."_$rowMTR"; ?>">
	                                                                                                <!-- NO URUT -->
	                                                                                                <td width="4%" height="25" style="text-align:center" nowrap>

	                                                                                                    <?php if($JO_STAT == 1 || $JO_STAT == 4) { ?>
	                                                                                                	<a href="#" onClick="deldataRMIN('<?php echo $P_STEP; ?>', <?php echo $rowMTR; ?>)" title="Delete Document" class="btn btn-danger btn-xs" style="display: none;"><i class="fa fa-trash-o"></i></a>
	                                                                                                    <?php
	                                                                                                    echo "$rowMTR.";
	                                                                                                	} 
	                                                                                                	else 
	                                                                                                	{
	                                                                                                    	echo "$rowMTR.";
	                                                                                                    }
	                                                                                                    ?>
	                                                                                                    <input style="display:none" type="Checkbox" id="dataRM[<?php echo $rowMTR; ?>][chk]" name="dataRM[<?php echo $rowMTR; ?>][chk]" value="<?php echo $rowMTR; ?>" onClick="pickThis(this,<?php echo $rowMTR; ?>)">
	                                                                                                    <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>NEEDQRC" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][NEEDQRC]" value="<?php echo $NEEDQRC; ?>" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ISWIP" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ISWIP]" value="<?php echo $ISWIP; ?>" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>JOSTF_NUM" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][JOSTF_NUM]" value="<?php echo $JOSTF_NUM; ?>" width="10" size="15">
	                                                                                                </td>
	                                                                                                    
	                                                                                                <!-- ITM_CODE, ITM_TYPE, ITM_GROUP, ITM_CATEG -->
	                                                                                                <td width="9%" style="text-align:left">
	                                                                                                <?php if($NEEDQRC == 1) { ?>
	                                                                                                	<a onclick="setQRC(<?php echo $rowMTR; ?>,'<?php echo $P_STEP; ?>')"><?php echo $ITM_CODE; ?></a>
	                                                                                                <?php } else { echo $ITM_CODE; } ?>
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_TYPE" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_TYPE]" value="IN" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_CODE" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_GROUP" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_CATEG" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_CATEG]" value="<?php echo $ITM_CATEG; ?>" width="10" size="15">
	                                                                                                </td>
	                                                                                                <!-- ITM_NAME -->
	                                                                                                <td width="65%" style="text-align:left">
	                                                                                                    <?php echo $ITM_NAME; ?>
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_NAME" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>" width="10" size="15">
	                                                                                                </td>
	                                                                                                <!-- ITM_UNIT -->  
	                                                                                                <td width="4%" style="text-align:center" nowrap>
	                                                                                                    <?php echo $ITM_UNIT; ?>
	                                                                                                    <input type="hidden" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_UNIT" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>" width="10" size="15">
	                                                                                                </td>
	                                                                                                <!-- ITM_QTY, ITM_PRICE -->
	                                                                                                <td width="18%" style="text-align:right" nowrap>
	                                                                                                    <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY<?php echo $rowMTR; ?>" id="IN<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY" value="<?php echo number_format($ITM_QTY,2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, <?php echo $rowMTR; ?>,  '<?php echo $P_STEP; ?>');" >
	                                                                                                    <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_SCALE]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_SCALE" value="<?php echo $ITM_SCALE; ?>">
	                                                                                                    <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][BOM_QTY]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>BOM_QTY" value="<?php echo $BOM_QTY; ?>">
	                                                                                                    <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][BOM_PRICE]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>BOM_PRICE" value="<?php echo $BOM_PRICE; ?>">
	                                                                                                    <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_QTY]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY" value="<?php echo $ITM_QTY; ?>">
	                                                                                                    <input style="text-align:right" type="hidden" name="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_STOCK" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_STOCK" value="<?php echo $ITM_STOCK; ?>">
	                                                                                                    <input type="hidden" style="text-align:right" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ITM_PRICE]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_PRICE" size="6" value="<?php echo $ITM_PRICE; ?>">
	                                                                                                    <input type="hidden" style="text-align:right" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ACC_ID]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
	                                                                                                    <input type="hidden" style="text-align:right" name="dataRM<?php echo $P_STEP; ?>[<?php echo $rowMTR; ?>][ACC_ID_UM]" id="dataRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
	                                                                                                    <?php if($ITM_CATEG == 'DY') { ?>
	                                                                                                    	<div id="htmlRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY"><?php echo number_format($ITM_QTY, 3); ?></div>
		                                                                                                <?php } else { ?>
	                                                                                                    	<div id="htmlRM<?php echo $P_STEP; ?><?php echo $rowMTR; ?>ITM_QTY"><?php echo number_format($ITM_QTY, 2); ?></div>
		                                                                                                <?php } ?>
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
	                                                                                    	if($task=='add'){
	                                                                                        $sqlFG	= "SELECT A.ITM_CODE, B.ITM_NAME,
	                                                                                        				A.ITM_QTY AS BOM_QTY,
	                                                                                        				A.ITM_PRICE AS BOM_PRICE,
	                                                                                        				A.ITM_QTY, A.ITM_PRICE,
	                                                                                        				B.ITM_GROUP, B.NEEDQRC,
	                                                                                                        B.ACC_ID, B.ACC_ID_UM, B.NEEDQRC,
	                                                                                                        A.ITM_UNIT, A.ITM_SCALE, B.ISWIP,
	                                                                                                        B.ISFG
	                                                                                                    FROM tbl_bom_stfdetail A
	                                                                                                        LEFT JOIN tbl_item B
	                                                                                                        ON A.ITM_CODE = B.ITM_CODE
	                                                                                                            AND B.PRJCODE = '$PRJCODE'
	                                                                                                    WHERE A.BOM_NUM = '$BOM_NUM'
	                                                                                                        AND A.BOMSTF_TYPE = 'OUT'
	                                                                                                        AND A.BOMSTF_STEP = '$P_STEP'
	                                                                                                        AND A.PRJCODE = '$PRJCODE'";
	                                                                                        }
	                                                                                        else {
	                                                                                        $sqlFG	= "SELECT A.ITM_CODE, B.ITM_NAME,
	                                                                                        				A.JOSTF_NUM, A.BOM_QTY,
	                                                                                        				A.BOM_PRICE,
	                                                                                        				A.ITM_QTY, A.ITM_PRICE,
	                                                                                                        B.ITM_GROUP, B.NEEDQRC,
	                                                                                                        B.ACC_ID, B.ACC_ID_UM, B.NEEDQRC,
	                                                                                                        A.ITM_UNIT, A.ITM_SCALE, B.ISWIP,
	                                                                                                        B.ISFG
	                                                                                                    FROM tbl_jo_stfdetail A
	                                                                                                        LEFT JOIN tbl_item B
	                                                                                                        ON A.ITM_CODE = B.ITM_CODE
	                                                                                                            AND B.PRJCODE = '$PRJCODE'
	                                                                                                    WHERE A.BOM_NUM = '$BOM_NUM'
	                                                                                                        AND A.JOSTF_TYPE = 'OUT'
	                                                                                                        AND A.JOSTF_STEP = '$P_STEP'
	                                                                                                        AND A.PRJCODE = '$PRJCODE'
	                                                                                                        AND A.JO_NUM = '$JO_NUM'";
	                                                                                        }
	                                                                                        $resFG 	= $this->db->query($sqlFG)->result();
	                                                                                        foreach($resFG as $rowFG) :
	                                                                                            $rowFG1  		= ++$i;
	                                                                                            if($task=='add')
			                                                                                    	$JOSTF_NUM	= "STFQC".$TRXTIME1;
			                                                                                   	else
			                                                                                    	$JOSTF_NUM	= $rowRM->JOSTF_NUM;

	                                                                                            $ITM_CODE 		= $rowFG->ITM_CODE;
	                                                                                            $ITM_GROUP 		= $rowFG->ITM_GROUP;
	                                                                                            $ITM_NAME 		= $rowFG->ITM_NAME;
	                                                                                            $ITM_QTY 		= $rowFG->ITM_QTY;
	                                                                                            $ITM_PRICE 		= $rowFG->ITM_PRICE;
	                                                                                            $ACC_ID 		= $rowFG->ACC_ID;
	                                                                                            $ACC_ID_UM 		= $rowFG->ACC_ID_UM;
	                                                                                            $NEEDQRC 		= $rowFG->NEEDQRC;
	                                                                                            $ITM_UNIT 		= $rowFG->ITM_UNIT;
	                                                                                            $BOM_QTY 		= $rowFG->BOM_QTY;
	                                                                                            $ITM_SCALE		= $rowFG->ITM_SCALE;
	                                                                                            $ISWIP			= $rowFG->ISWIP;
	                                                                                            $ISFG			= $rowFG->ISFG;
	                                                                                            if($ISFG == 1)
	                                                                                            	$ISWIP		= 1;
	                                                                                            
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
	                                                                                                    	echo "$rowFG1.";
	                                                                                                    ?>
	                                                                                                    <input style="display:none" type="Checkbox" id="dataFG[<?php echo $rowFG1; ?>][chk]" name="dataFG[<?php echo $rowFG1; ?>][chk]" value="<?php echo $rowFG1; ?>" onClick="pickThis(this,<?php echo $rowFG1; ?>)">
	                                                                                                    <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
	                                                                                                     <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>NEEDQRC" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][NEEDQRC]" value="<?php echo $NEEDQRC; ?>" width="10" size="15">
	                                                                                                </td>
	                                                                                                <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
	                                                                                                <td width="9%" style="text-align:left">
	                                                                                                    <?php print $ITM_CODE; ?>
	                                                                                                    <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_TYPE" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_TYPE]" value="OUT" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_CODE" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_GROUP" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" width="10" size="15">
	                                                                                                    <input type="hidden" id="dataFG<?php echo $JOSTF_NUM; ?><?php echo $rowMTR; ?>JOSTF_NUM" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][JOSTF_NUM]" value="<?php echo $JOSTF_NUM; ?>" width="10" size="15">
	                                                                                                    
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
	                                                                                                    <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY<?php echo $rowFG1; ?>" id="OUT<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_QTY" value="<?php echo number_format($ITM_QTY,2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMFG(this, <?php echo $rowFG1; ?>,  '<?php echo $P_STEP; ?>');" >
	                                                                                                    <div id="htmlFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_QTY"><?php echo number_format($ITM_QTY, 2); ?></div>
	                                                                                                    <input style="text-align:right" type="hidden" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ITM_SCALE]" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ITM_SCALE" value="<?php echo $ITM_SCALE; ?>">
	                                                                                                    <input style="text-align:right" type="hidden" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][BOM_QTY]" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>BOM_QTY" value="<?php echo $BOM_QTY; ?>">
	                                                                                                    <input type="hidden" id="dataFG<?php echo $P_STEP; ?><?php echo $rowFG1; ?>ISWIP" name="dataFG<?php echo $P_STEP; ?>[<?php echo $rowFG1; ?>][ISWIP]" value="<?php echo $ISWIP; ?>" width="10" size="15">
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
	                            	<!-- SUBMIT BUTTON -->
			                        <div class="box-header with-border">
			                            	<?php
												if($task=='add')
												{
													if($ISCREATE == 1)
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
													if($ISAPPROVE == 1 && $JO_STAT == 3)
													{
														?>
															<button class="btn btn-primary" style="display:none" id="tblClose">
															<i class="fa fa-save"></i>
															</button>&nbsp;
														<?php
													}
													elseif($ISAPPROVE == 1 && $JO_STAT != 3)
													{
														?>
															<button class="btn btn-primary">
															<i class="fa fa-save"></i>
															</button>&nbsp;
														<?php
													}
													elseif($ISCREATE == 1 && ($JO_STAT == 1 || $JO_STAT == 4))
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i>
															</button>&nbsp;
														<?php
													}
												}
												$backURL	= site_url('c_production/c_j0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
											?>
			                        </div>

									<?php
			                            $DOC_NUM	= $JO_NUM;
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
															<div class="col-md-4">
																<div class="info-box bg-<?php echo $boxCol_1; ?>">
																	<span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
																	<div class="info-box-content" nowrap>

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
															<div class="col-md-4">
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
															<div class="col-md-4">
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
															<div class="col-md-4">
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
															<div class="col-md-4">
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
					                                            <div class="col-md-4">
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
					                                            <div class="col-md-4">
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
					                                            <div class="col-md-4">
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
					                                            <div class="col-md-4">
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
					                                            <div class="col-md-4">
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
	                            </div>
	                        </div>
	               		</form>  
					</div>
				</div>
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
      autoclose: true,
      endDate: '+1d'
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true,
      startDate: '+0d'
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
  
	var decFormat		= 2;
	
	function add_itemRM(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		JO_NUM			= "<?php echo $JO_NUM; ?>";
		JOSTF_NUM		= "STFQC<?php echo $JO_NUM; ?>";
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];	// GROUP CODE
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
		ICOLL_CODE		= arrItem[15];	// GROUP CODE
		ROW_SELECTED	= arrItem[16];	// SELECTED ROW
		ITM_SCALE		= 1;

		var P_STEPX		= '\''+P_STEP+'\'';

		deldataRMIN(P_STEP, ROW_SELECTED);

		ITM_STOCK		= parseFloat(STOCK_PRJ);

		objTable 		= document.getElementById('tbl_IN'+P_STEP);
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex 		= parseInt(objTable.rows.length);
		//intIndex = intTable;
		//document.frmSOInfo.rowCount.value = intIndex;

		objTR = objTable.insertRow(intTable);
		objTR.id = 'trIN_'+P_STEP+'_'+ intIndex;
		
		var P_STEP1	= '\''+P_STEP+'\'';
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deldataRM('+P_STEP1+','+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="dataRM'+P_STEP+'['+intIndex+'][chk]" name="dataRM'+P_STEP+'['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" ><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'JOSTF_NUM" name="dataRM'+P_STEP+'['+intIndex+'][JOSTF_NUM]" value="'+JOSTF_NUM+'" width="10" size="15">';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		if(NEEDQRC == 1)
		{
			objTD.innerHTML = '<a onclick="setQRC('+intIndex+',  '+P_STEP1+');">'+ITM_CODE+'</a><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_TYPE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="IN" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_CODE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_GROUP" name="dataRM'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		}
		else
		{
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_TYPE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="IN" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_CODE" name="dataRM'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_GROUP" name="dataRM'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		}
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_NAME" name="dataRM'+P_STEP+'['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataRM'+P_STEP+''+intIndex+'ITM_UNIT" name="dataRM'+P_STEP+'['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM_QTY, ITM_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		if(SRC == 'ITM')
		{
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="IN'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, '+intIndex+',  '+P_STEP1+');" ><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+'['+intIndex+'][ITM_SCALE]" id="dataRM'+P_STEP+''+intIndex+'ITM_SCALE" value="'+ITM_SCALE+'"><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataRM'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'"><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" id="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataRM'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		}
		else
		{
			objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="IN'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, '+intIndex+',  '+P_STEP1+');" readonly ><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+'['+intIndex+'][ITM_SCALE]" id="dataRM'+P_STEP+''+intIndex+'ITM_SCALE" value="'+ITM_SCALE+'"><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataRM'+P_STEP+''+intIndex+'ITM_QTY" value="'+ITM_QTY+'"><input style="text-align:right" type="hidden" name="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" id="dataRM'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataRM'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataRM'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataRM'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		}
		
		document.getElementById('totRM_'+P_STEP).value = intIndex;
	}
	
	function add_itemFG(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/
				
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
		
		ITM_STOCK		= parseFloat(STOCK_PRJ);
		
		objTable 		= document.getElementById('tbl_OUT'+P_STEP);
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		//document.frmSOInfo.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="dataFG'+P_STEP+'['+intIndex+'][chk]" name="dataFG'+P_STEP+'['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_TYPE" name="dataFG'+P_STEP+'['+intIndex+'][ITM_TYPE]" value="OUT" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_CODE" name="dataFG'+P_STEP+'['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_GROUP" name="dataFG'+P_STEP+'['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_NAME" name="dataFG'+P_STEP+'['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataFG'+P_STEP+''+intIndex+'ITM_UNIT" name="dataFG'+P_STEP+'['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM_QTY, ITM_PRICE
		var P_STEP1	= '\''+P_STEP+'\'';
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="ITM_QTY'+intIndex+'" id="OUT'+P_STEP+''+intIndex+'ITM_QTY" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMFG(this, '+intIndex+',  '+P_STEP1+');" ><input style="text-align:right" type="hidden" name="dataFG'+P_STEP+'['+intIndex+'][ITM_QTY]" id="dataFG'+P_STEP+''+intIndex+'ITM_QTY" value="0.00"><input style="text-align:right" type="hidden" name="dataFG'+P_STEP+''+intIndex+'ITM_STOCK" id="dataFG'+P_STEP+''+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ITM_PRICE]" id="dataFG'+P_STEP+''+intIndex+'ITM_PRICE" size="6" value="'+ITM_PRICE+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ACC_ID]" id="dataFG'+P_STEP+''+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataFG'+P_STEP+'['+intIndex+'][ACC_ID_UM]" id="dataFG'+P_STEP+''+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		
		document.getElementById('totFG_'+P_STEP).value = intIndex;
	}
	
	function setQRC(row, P_STEP)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var JO_NUM		= document.getElementById('JO_NUM').value;
		var JOSTF_NUM	= document.getElementById('dataRM'+P_STEP+row+'JOSTF_NUM').value;
		var CUST_CODE	= document.getElementById('CUST_CODE').value;
		var ITM_CODE	= document.getElementById('dataRM'+P_STEP+row+'ITM_CODE').value;
		
		var urlQRC 		= "<?php echo $url_QRC;?>";

        title = 'Select Item';
        w = 700;
        h = 500;
        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        return window.open(urlQRC+'&JONUM='+JO_NUM+'&JOSTFNUM='+JOSTF_NUM+'&PSTEP='+P_STEP+'&CUSTCODE='+CUST_CODE+'&ITMCODE='+ITM_CODE+'&SELROW='+row, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

	}
	
	function cVOLMRM(thisVal, row, P_STEP)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		ITM_QTY 	= parseFloat(eval(thisVal).value.split(",").join(""));
		document.getElementById('dataRM'+P_STEP+row+'ITM_QTY').value	= ITM_QTY;
		document.getElementById('IN'+P_STEP+row+'ITM_QTY').value 		= doDecimalFormat(RoundNDecimal(parseFloat(ITM_QTY),decFormat));
	}
	
	function cVOLMFG(thisVal, row, P_STEP)
	{
		var decFormat	= document.getElementById('decFormat').value;

		ITM_QTY 	= parseFloat(eval(thisVal).value.split(",").join(""));
		document.getElementById('dataFG'+P_STEP+row+'ITM_QTY').value	= ITM_QTY;
		document.getElementById('OUT'+P_STEP+row+'ITM_QTY').value 		= doDecimalFormat(RoundNDecimal(parseFloat(ITM_QTY),decFormat));
	}

	function getTotRowRM(strItem)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');
		var arrItem;
		
		var totRow = arrItem[0];
		var SELROW = arrItem[1];
		var P_STEP = arrItem[2];

		ITM_QTY 	= parseFloat(totRow);
		document.getElementById('dataRM'+P_STEP+SELROW+'ITM_QTY').value	= ITM_QTY;
		document.getElementById('IN'+P_STEP+SELROW+'ITM_QTY').value 	= doDecimalFormat(RoundNDecimal(parseFloat(ITM_QTY),decFormat));
		//cVOLMRM(totRow, SELROW, P_STEP1);
		// INONE1ITM_QTY
	}

	function chgQTY(thisVal, row)
	{
        var decFormat	= document.getElementById('decFormat').value;

		var SO_VOLM 	= parseFloat(eval(document.getElementById('SO_VOLMX_'+row)).value.split(",").join(""));
		var JO_VOLM 	= parseFloat(eval(document.getElementById('SO_JOVOLM_'+row)).value.split(",").join(""));
		var REM_VOLM	= parseFloat(SO_VOLM - JO_VOLM);

		var FG_VOLM 	= parseFloat(eval(thisVal).value.split(",").join(""));

		if(REM_VOLM == 0)
		{
			swal('<?php echo $alert6; ?>',
			{
				icon: "warning",
			});
			return false;
		}
		else if(FG_VOLM > REM_VOLM)
		{
			swal('<?php echo $alert4; ?>',
			{
				icon: "warning",
			});
			var FG_VOLM = parseFloat(REM_VOLM);
		}

		document.getElementById('data1'+row+'ITM_QTY').value 	= FG_VOLM;
		document.getElementById('ITM_QTYX3_'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(FG_VOLM),decFormat)); 

		var FG_PRICE	= document.getElementById('data1'+row+'ITM_PRICE').value;
		var ITM_TOTAL	= parseFloat(FG_VOLM) * parseFloat(FG_PRICE);
		document.getElementById('data1'+row+'ITM_TOTAL').value	= ITM_TOTAL;

		totRow3		= document.getElementById('totRow3').value;
		var TOTFG	= 0;
		for(i=1;i<=totRow3;i++)
		{
			var FG_VOLM = parseFloat(document.getElementById('data1'+i+'ITM_QTY').value);
			TOTFG		= parseFloat(TOTFG) + parseFloat(FG_VOLM);
		}
		document.getElementById('TOTFG').value 	= parseFloat(TOTFG);

		totSTP		= document.getElementById('totSTP').value;
        for(i=1;i<=totSTP;i++)
		{
			var mcnCode = document.getElementById('selSTEP'+i+'MCN_NUM').value;
			var P_STEP 	= document.getElementById('dataSTEP'+i+'P_STEP').value;

			chgMCN(mcnCode, i, P_STEP)
		}
	}

	function chgMCN(mcnCode, row, P_STEP)
	{
		var decFormat	= document.getElementById('decFormat').value;

		// CARI JUMLAH PRODUKSI FG
		var TOTFG		= parseFloat(document.getElementById('TOTFG').value);

		if(TOTFG == 0)
		{
			if(mcnCode != 0)
			{
				swal('<?php echo $alert5; ?>',
				{
					icon: "warning",
				})
	            .then(function()
	            {
	                swal.close();
					$('#selSTEP'+row+'MCN_NUM').val(0).trigger('change');
	            });
				return false;
			}
		}
		else {
			$.ajax({
			    url: "<?php echo $secGetMcn; ?>",
			    type: 'POST',
			    data: {option : mcnCode},
			    success: function(newScaleMcn) 
			    {
					var tRM = document.getElementById('totRM_'+P_STEP).value;
					for(i=1;i<=tRM;i++)
					{
				        ITMCAT 	= document.getElementById('dataRM'+P_STEP+i+'ITM_CATEG').value;
				        ISWIP 	= parseFloat(document.getElementById('dataRM'+P_STEP+i+'ISWIP').value);
				        x1A 	= parseFloat(document.getElementById('dataRM'+P_STEP+i+'ITM_SCALE').value);
				        x1B 	= parseFloat(document.getElementById('dataRM'+P_STEP+i+'BOM_QTY').value);

				        if(ITMCAT == 'DY')
				        {
				        	x2B 	= parseFloat(x1B * TOTFG / 100);

							document.getElementById('htmlRM'+P_STEP+i+'ITM_QTY').innerHTML = doDecimalFormat(RoundNDecimal(parseFloat(x2B),3));
							document.getElementById('dataRM'+P_STEP+i+'ITM_QTY').value = x2B;
				        }
				        else
				        {
					        var isNotNum = isNaN(ISWIP);
					        if(ISWIP == 1 || isNotNum == true)
					        {
					        	//x2B = x1B;
					        	x2B = TOTFG;
					        }
					        else
					        {
					        	x2A		= parseFloat(newScaleMcn);	// newScaleMcn = konstanta perkalian mesin yg ada di tabel mesin
								x2B1 	= parseFloat(x1B * x2A);
								x2B 	= parseFloat(x2B1 * TOTFG);
					        }

							document.getElementById('htmlRM'+P_STEP+i+'ITM_QTY').innerHTML = doDecimalFormat(RoundNDecimal(parseFloat(x2B),2));
							document.getElementById('dataRM'+P_STEP+i+'ITM_QTY').value = x2B;
					    }
					}
					var tFG = document.getElementById('totFG_'+P_STEP).value;
					for(j=1;j<=tFG;j++)
					{
				        ISWIP 	= parseFloat(document.getElementById('dataFG'+P_STEP+j+'ISWIP').value);
				        x1A 	= parseFloat(document.getElementById('dataFG'+P_STEP+j+'ITM_SCALE').value);
				        x1B 	= parseFloat(document.getElementById('dataFG'+P_STEP+j+'BOM_QTY').value);

				        var isNotNum = isNaN(ISWIP);
				        if(ISWIP == 1 || isNotNum == true)
				        {
				        	//x2B = x1B;
				        	x2B = TOTFG;
				        }
				        else
				        {
				        	x2A		= parseFloat(newScaleMcn);
							x2B1 	= parseFloat(x1B * x2A);
							x2B 	= parseFloat(x2B1 * TOTFG);
				        }

						document.getElementById('htmlFG'+P_STEP+j+'ITM_QTY').innerHTML = doDecimalFormat(RoundNDecimal(parseFloat(x2B),2));
						document.getElementById('dataFG'+P_STEP+j+'ITM_QTY').value = x2B;
					}
			    }
			});
		}
	}
	
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

        SO_NUM1		= document.getElementById('SO_NUM1').value;
        if(SO_NUM1 == 0)
        {
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#SO_NUM1').focus();

            });
            return false;
        }
		
        JO_NOTES	= document.getElementById('JO_NOTES').value;
        if(JO_NOTES == '')
        {
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#JO_NOTES').focus();

            });
            return false;
        }

        totRow3		= document.getElementById('totRow3').value;
		var TOTFG	= 0;
		for(i=1;i<=totRow3;i++)
		{
			var FG_VOLM = parseFloat(document.getElementById('data1'+i+'ITM_QTY').value);
			TOTFG		= parseFloat(TOTFG) + parseFloat(FG_VOLM);
		}
		
		if(TOTFG == 0)
		{
			swal('<?php echo $alert5; ?>',
			{
				icon: "warning",
			});
			return false;
		}

        totSTP		= document.getElementById('totSTP').value;
        for(i=1;i<=totSTP;i++)
		{
			var STF_ORD = document.getElementById('selSTEP'+i+'JOSTF_ORD').value
			var MCN_NUM = document.getElementById('selSTEP'+i+'MCN_NUM').value;
			var STEP_X 	= document.getElementById('dataSTEP'+i+'STEP_X').value;

			if(STF_ORD == 0)
			{
				swal('<?php echo $alert10; ?>',
				{
					icon: "warning",
				});
				return false;
			}

			if(STF_ORD!='99' && MCN_NUM == 0)
			{
				swal('<?php echo $alert9; ?>',
				{
					icon: "warning",
				});
				return false;
			}
		} 
    }
	
	function deldataRM(P_STEP1, row)
	{
		var row = document.getElementById("trIN_"+P_STEP1+'_'+ row);
		row.remove();
	}
	
	function deldataRMIN(P_STEP1, row)
	{
		var row = document.getElementById("trIN_"+P_STEP1+'_'+ row);
		row.remove();
	}
    
    function checkSODetProc()
    {
		document.getElementById('SOInfo').style.display 	= '';
		document.getElementById('SelectFG').style.display 	= 'none';
		
		document.getElementById('showFORM1').className 		= 'active';
		document.getElementById('showFORM2').className 		= '';
    }
	
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
	
	function RoundNDecimal(X, N)
	{
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