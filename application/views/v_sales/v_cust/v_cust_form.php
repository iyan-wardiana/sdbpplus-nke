<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= v_cust_form.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

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
$IMGC_FILENAMEX 	= '';

if($task == 'add')
{
	foreach($viewDocPattern as $row) :
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
	
	$year = substr((int)$Pattern_YearAktive, 2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_MonthAktive;
	$konst = "000";
	
	$myCount = $this->db->count_all('tbl_customer');
	
	$this->db->select('Patt_Number');
	$result = $this->db->get('tbl_customer')->result();
	
	$myMax = $myCount + 1;
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = 24;
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
	$len = strlen($lastPatternNumb);

	if($Pattern_Length==1)
	{
		if($len==0) $nol="";
	}
	elseif($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{
		if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";else if($len==4) $nol="";
	}
	elseif($Pattern_Length==5)
	{
		if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";else if($len==5) $nol="";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	//$DocNumber 		= "$Pattern_Code$groupPattern$konst$lastPatternNumb";
	$DocNumber 		= "$Pattern_Code$lastPatternNumb";
	
	$CUST_CODE 		= "$Pattern_Code$lastPatternNumb";
	$CUST_DESC		= '';
	$CUST_CODE		= $CUST_CODE;
	$CUST_ADD1		= '';
	$CUST_KOTA		= '';
	$CUST_NPWP		= '';
	$CUST_PERS		= '';
	$CUST_TELP		= '';
	$CUST_MAIL		= '';
	$CUST_NOREK		= '';
	$CUST_NMREK		= '';
	$CUST_BANK		= '';
	$CUST_OTHR		= '';
	$CUST_CAT		= '';
	$CUST_STAT		= '';
	
	$TOTSales		= 0;
	$TOTAR			= 0;
	$TOTPAID		= 0;
	
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/username.jpg');
	$urlAddProf		= site_url('c_sales/c_cu5t/add_process/?id='.$this->url_encryption_helper->encode_url($appName));
	
	$CUST_TOP		= 0;
	$CUST_TOPD		= 0;
}
else
{
	$isSetDocNo = 1;
	$CUST_CODE		= $default['CUST_CODE'];
	$CUST_DESC		= $default['CUST_DESC'];
	$CUST_CODE		= $default['CUST_CODE'];
	if(isset($_POST['CUST_CODE1']))
	{
		$CUST_CODE		= $_POST['CUST_CODE1'];
	}
	$CUST_ADD1		= $default['CUST_ADD1'];
	$CUST_KOTA		= $default['CUST_KOTA'];
	$CUST_NPWP		= $default['CUST_NPWP'];
	$CUST_PERS		= $default['CUST_PERS'];
	$CUST_TELP		= $default['CUST_TELP'];
	$CUST_MAIL		= $default['CUST_MAIL'];
	$CUST_NOREK		= $default['CUST_NOREK'];
	$CUST_NMREK		= $default['CUST_NMREK'];
	$CUST_BANK		= $default['CUST_BANK'];
	$CUST_OTHR		= $default['CUST_OTHR'];
	$CUST_CAT		= $default['CUST_CAT'];
	$CUST_TOP		= $default['CUST_TOP'];
	$CUST_TOPD		= $default['CUST_TOPD'];
	$CUST_STAT		= $default['CUST_STAT'];
	
	$TOTSales		= 0;
	$TOTAR			= 0;
	$TOTPAID		= 0;
	
	$sqlGetIMG		= "SELECT IMGC_FILENAME, IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE'";
	$resGetIMG 		= $this->db->query($sqlGetIMG)->result();
	foreach($resGetIMG as $rowGIMG) :
		$IMGC_FILENAME 	= $rowGIMG ->IMGC_FILENAME;
		$IMGC_FILENAMEX = $rowGIMG ->IMGC_FILENAMEX;
	endforeach;
	
	$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE.'/'.$IMGC_FILENAMEX);
	if (!file_exists('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE))
	{
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/username.jpg');
	}
	$urlAddProf			= site_url('c_sales/c_cu5t/update_process/?id='.$this->url_encryption_helper->encode_url($appName));
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
			
			if($TranslCode == 'CustPict')$CustPict = $LangTransl;
			if($TranslCode == 'Biodata')$Biodata = $LangTransl;
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'City')$City = $LangTransl;
			if($TranslCode == 'DateofBirth')$DateofBirth = $LangTransl;
			if($TranslCode == 'ContactPerson')$ContactPerson = $LangTransl;
			if($TranslCode == 'Contact')$Contact = $LangTransl;
			if($TranslCode == 'Male')$Male = $LangTransl;
			if($TranslCode == 'Female')$Female = $LangTransl;
			if($TranslCode == 'RekInfo')$RekInfo = $LangTransl;
			if($TranslCode == 'RekNo')$RekNo = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'BankName')$BankName = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Province')$Province = $LangTransl;
			if($TranslCode == 'State')$State = $LangTransl;
			if($TranslCode == 'Email')$Email = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'Location')$Location = $LangTransl;
			if($TranslCode == 'Position')$Position = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'CustInfo')$CustInfo = $LangTransl;
			if($TranslCode == 'Authorization')$Authorization = $LangTransl;
			if($TranslCode == 'StatusnControl')$StatusnControl = $LangTransl;
			if($TranslCode == 'UpPict')$UpPict = $LangTransl;
			
			if($TranslCode == 'AboutCust')$AboutCust = $LangTransl;
			if($TranslCode == 'Sales')$Sales = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'Received')$Received = $LangTransl;
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'Location')$Location = $LangTransl;
			if($TranslCode == 'Skills')$Skills = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'None')$None = $LangTransl;
			if($TranslCode == 'CustCode')$CustCode = $LangTransl;
			
			if($TranslCode == 'Username')$Username = $LangTransl;
			if($TranslCode == 'Password')$Password = $LangTransl;
			if($TranslCode == 'ConfirmPass')$ConfirmPass = $LangTransl;
			if($TranslCode == 'Hint')$Hint = $LangTransl;
			if($TranslCode == 'UserStatus')$UserStatus = $LangTransl;
			if($TranslCode == 'UserFlag')$UserFlag = $LangTransl;
			if($TranslCode == 'FileName')$FileName = $LangTransl;
			if($TranslCode == 'ChooseFile')$ChooseFile = $LangTransl;
			if($TranslCode == 'Addemployee')$Addemployee = $LangTransl;
			if($TranslCode == 'Employee')$Employee = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PayTenor')$PayTenor = $LangTransl;
			if($TranslCode == 'Day')$Day = $LangTransl;
            if($TranslCode == 'empCatNm')$empCatNm = $LangTransl;
            if($TranslCode == 'NotReceipt')$NotReceipt = $LangTransl;
            if($TranslCode == 'Received')$Received = $LangTransl;
            if($TranslCode == 'Remain')$Remain = $LangTransl;
            if($TranslCode == 'receivables')$receivables = $LangTransl;
            if($TranslCode == 'remAr')$remAr = $LangTransl;
            if($TranslCode == 'ReceiptAmount')$ReceiptAmount = $LangTransl;
            if($TranslCode == 'lastBill')$lastBill = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'AmountReceipt')$AmountReceipt = $LangTransl;
            if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
            if($TranslCode == 'billList')$billList = $LangTransl;
            if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
            if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert00	= "Masukan kode pelanggan.";
			$alert01	= "Masukan nama pelanggan.";
			$alert02	= "Masukan alamat pelanggan.";
			$alert03	= "Masukan nama kota.";
			$alert04	= "Masukan nomor telepon";
			$alert05	= "Masukan nomor rekening bank";
			$alert06	= "Masukan nama rekening bank";
			$alert07	= "Kode ini sudah digunakan oleh ";
			$alert08	= "Silahkan masukan nama file.";
		}
		else
		{
			$alert00	= "Input customer code.";
			$alert01	= "Input customer name.";
			$alert02	= "Input customer address.";
			$alert03	= "Input customer city.";
			$alert04	= "Input phone number.";
			$alert05	= "Input bank acount number.";
			$alert06	= "Input bank acount name.";
			$alert07	= "This code has already been used by ";
			$alert08	= "Please input file name.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header" style="display: none;">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/client.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
			    <small><?php //echo $h2_title; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>

	    <section class="content">
	      <div class="row">
	        <div class="col-md-3">
				<?php
	                if($CUST_ADD1 == '')
	                {
	                    $CUST_ADDD	= $None;
	                }
	                else
	                {
	                    $CUST_ADDD	= "$CUST_ADD1, $CUST_KOTA $CUST_TELP";
	                }
	                if($CUST_KOTA == '')
	                {
	                    $CUST_KOTAD	= $None;
	                }
	                else
	                {
	                    $CUST_KOTAD	= $CUST_KOTA;
	                }
	                if($CUST_DESC == '')
	                {
	                    $CUST_DESCD	= "Unknown";
	                }
	                else
	                {
	                    $CUST_DESCD	= $CUST_DESC;
	                }
	            ?>
	          	<div class="box box-primary">
	                <div class="box-body box-profile">
	                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
	                    <h3 class="profile-username text-center"><?php echo $CUST_DESCD; ?></h3>
	                    <p class="text-muted text-center"><?php //echo $PRJLOCT; ?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <?php echo $CUST_ADD1; ?>
                                </li>
                            </ul>
	                    <a href="#" class="btn btn-primary btn-block" style="display:none"><b>Follow</b></a>
	                </div>
	          	</div>

                <?php
                    // AR
                        $AR_T   = 0;
                        $sART   = "SELECT SUM(SINV_AMOUNT_FINAL) AS AR_T FROM tbl_sinv_header
                                    WHERE CUST_CODE = '$CUST_CODE' AND SINV_STAT IN (3,6)";
                        $rART   = $this->db->query($sART)->result();
                        foreach($rART as $rwART) :
                            $AR_T   = $rwART->AR_T;
                        endforeach;
                        if($AR_T == '') $AR_T = 0;

                    // BR
                        $BR_T   = 0;
                        $sBRT   = "SELECT SUM(A.GAmount) AS BR_T FROM tbl_br_detail A
                                    INNER JOIN tbl_br_header B ON A.BR_NUM = B.BR_NUM
                                    WHERE B.BR_RECTYPE in ('SAL') AND B.BR_PAYFROM = '$CUST_CODE' AND BR_STAT IN (3,6)";
                        $rBRT   = $this->db->query($sBRT)->result();
                        foreach($rBRT as $rwBRT) :
                            $BR_T   = $rwBRT->BR_T;
                        endforeach;
                        if($BR_T == '') $BR_T = 0;

                    // REM
                        $AR_R   = $AR_T - $BR_T;

                    // LAST AR
                        $SINV_MANNO     = "-";
                        $SINV_DATE      = "-";
                        $SINV_ENDDATE   = "-";
                        $GSINV_TOTVAL   = 0;
                        $sLD            = "SELECT SINV_CODE, SINV_DATE, SINV_DUEDATE, SINV_AMOUNT_FINAL FROM tbl_sinv_header
                                            WHERE CUST_CODE = '$CUST_CODE' AND SINV_STAT IN (3,6) ORDER BY SINV_DATE DESC LIMIT 1";
                        $rLD            = $this->db->query($sLD)->result();
                        foreach($rLD as $rwLD) :
                            $SINV_MANNO     = $rwLD->SINV_CODE;
                            $SINV_DATE      = date('d-m-Y', strtotime($rwLD->SINV_DATE));
                            $SINV_ENDDATE   = date('d-m-Y', strtotime($rwLD->SINV_DUEDATE));
                            $GSINV_TOTVAL   = $rwLD->SINV_AMOUNT_FINAL;
                        endforeach;

                    // LIST BR
                        $BR_CODE    = "-";
                        $BR_DATE    = "-";
                        $BR_AMOUNT  = 0;
                        $sBRLC      = "tbl_br_detail A
                                        INNER JOIN tbl_br_header B ON B.BR_NUM = A.BR_NUM
                                        WHERE B.BR_RECTYPE in ('SAL') AND B.BR_DOCTYPE = 'PINV' AND B.BR_STAT IN (3,6)";
                        $rBRLC      = $this->db->count_all($sBRLC);

                        $sBRL       = "SELECT B.BR_CODE, B.BR_DATE, A.GAmount AS BR_AMOUNT, B.Acc_ID FROM tbl_br_detail A
                                        INNER JOIN tbl_br_header B ON B.BR_NUM = A.BR_NUM
                                        WHERE B.BR_RECTYPE in ('SAL') AND B.BR_DOCTYPE = 'PINV' AND B.BR_STAT IN (3,6)";
                        $rBRL       = $this->db->query($sBRL)->result();

                    // LIST AR
                        $sARLC      = "tbl_sinv_header WHERE CUST_CODE = '$CUST_CODE' AND SINV_STAT IN (3,6)";
                        $rARLC      = $this->db->count_all($sARLC);

                        $sARL       = "SELECT SINV_CODE, SINV_DATE, SINV_DUEDATE, SINV_AMOUNT_FINAL, SINV_AMOUNT_PAID FROM tbl_sinv_header
                                        WHERE CUST_CODE = '$CUST_CODE' AND SINV_STAT IN (3,6) ORDER BY SINV_DATE";
                        $rARL       = $this->db->query($sARL)->result();
                ?>
                    
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <?php echo $receivables; ?>
                            </a>
                        </h4>
                    </div>

                    <div id="collapseOne" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="form-group">
                                <strong><i class='glyphicon glyphicon-ok margin-r-5'></i><?php echo $remAr; ?> </strong>
                                <div style='margin-left: 20px'>
                                    <p>
                                        <?php echo number_format($AR_R, 2); ?>
                                    </p>
                                </div>
                                <strong><i class='glyphicon glyphicon-usd margin-r-5'></i><?php echo $ReceiptAmount; ?> </strong>
                                <div style='margin-left: 20px'>
                                    <p>
                                        <?php echo number_format($BR_T, 2); ?>
                                    </p>
                                </div>
                                <strong><i class='glyphicon glyphicon-briefcase margin-r-5'></i>Total <?php echo $receivables; ?> </strong>
                                <div style='margin-left: 20px'>
                                    <p>
                                        <?php echo number_format($AR_T, 2); ?>
                                    </p>
                                </div>
                                <strong><i class='glyphicon glyphicon-duplicate margin-r-5'></i><?php echo $lastBill; ?> </strong>
                                <div style='margin-left: 20px'>
                                    <p>
                                        <?php echo $SINV_MANNO; ?><br>
                                        <b><?php echo $Date; ?> </b><br>
                                        <?php echo $SINV_DATE; ?><br>
                                        <b><?php echo $AmountReceipt; ?> </b><br>
                                        <?php echo number_format($GSINV_TOTVAL,2); ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="panel box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                <?php echo $BankReceipt; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="form-group">
                                <?php
                                    if($rBRLC > 0)
                                    {
                                        foreach($rBRL as $rwBRL) :
                                            $BR_CODE    = $rwBRL->BR_CODE;
                                            $BR_DATE    = date('d-m-Y', strtotime($rwBRL->BR_DATE));
                                            $BR_AMOUNT  = number_format($rwBRL->BR_AMOUNT,2);
                                            $AccID    	= $rwBRL->Acc_ID;
                                            $AccNm 		= "-";
                                            $sAccNm     = "SELECT Account_NameId AS AccNm FROM tbl_chartaccount WHERE Account_Number = '$AccID'";
				                            $rAccNm     = $this->db->query($sAccNm)->result();
				                            foreach($rAccNm as $rwAccNm) :
				                                $AccNm 	= $rwAccNm->AccNm;
				                            endforeach;

                                            echo "<strong><i class='fa fa-file margin-r-5'></i>$BR_CODE</strong> <spin class='text-muted'>($BR_DATE)</spin>
                                            <div style='margin-left: 20px'>
                                                <p class='text-muted'>
                                                    $BR_AMOUNT<br>
                                                    $AccNm
                                                </p>
                                            </div>";
                                        endforeach;
                                    }
                                    else
                                    {
                                        echo "-";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                <?php echo $billList; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="form-group">
                                <?php
                                    $SINVMANNO      = "-";
                                    $SINVDATE       = "-";
                                    $SINVENDDATE    = "-";
                                    $GSINVTOTVAL    = 0;
                                    $SINVPAIDAM     = 0;
                                    if($rARLC > 0)
                                    { 
                                        foreach($rARL as $rwARL) :
                                            $SINVMANNO      = $rwARL->SINV_CODE;
                                            $SINVDATE       = date('d-m-Y', strtotime($rwARL->SINV_DATE));
                                            $SINVENDDATE    = date('d-m-Y', strtotime($rwARL->SINV_DUEDATE));
                                            $GSINVTOTVAL    = $rwARL->SINV_AMOUNT_FINAL;
                                            $SINVPAIDAM     = $rwARL->SINV_AMOUNT_PAID;
                                            $GSINVTOTVAL    = number_format($rwARL->SINV_AMOUNT_FINAL,2);
                                            $SINVPAIDAM     = number_format($rwARL->SINV_AMOUNT_PAID,2);
                                            $DEVAMOUN       = $GSINVTOTVAL - $SINVPAIDAM;

                                            $STATCOL        = 'red';
                                            $STATICON       = 'remove';
                                            $PINSTATD       = 'NR';
                                            if($DEVAMOUN <= 1000 AND $DEVAMOUN > -1000)
                                                $PINSTATD   = 'FR';

                                            if($PINSTATD == 'FR')
                                            {
                                                $STATCOL    = 'green';
                                                $STATICON   = 'ok';
                                            }

                                            echo "<strong><i class='fa fa-file margin-r-5'></i>$SINVMANNO</strong> <spin class='text-muted'>($SINVDATE)</spin><span class='pull-right badge bg-$STATCOL'><i class='glyphicon glyphicon-$STATICON'></i></span>
                                                <div style='margin-left: 20px'>
                                                    <p>
                                                        $GSINVTOTVAL
                                                    </p>
                                                </div>";
                                        endforeach;
                                    }
                                    else
                                    {
                                        echo "-";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
	        </div>
				<?php
					$AR_TA 	= 0;
					$AR_PA 	= 0;
					$sTOTA	= "SELECT SUM(SINV_AMOUNT_FINAL) AS AR_TA, SUM(SINV_AMOUNT_PAID) AS AR_PA FROM tbl_sinv_header";
					$qTOTA	= $this->db->query($sTOTA)->result();
					foreach($qTOTA as $rTOTA) :
						$AR_TA	= $rTOTA->AR_TA;
						$AR_PA	= $rTOTA->AR_PA;
					endforeach;
					$AR_TAP 	= $AR_TA;
					if($AR_TA == 0)
						$AR_TAP	= 1;

					$AR_T 	= 0;
					$AR_P 	= 0;
					$sTOT	= "SELECT SUM(SINV_AMOUNT_FINAL) AS AR_T, SUM(SINV_AMOUNT_PAID) AS AR_P FROM tbl_sinv_header WHERE CUST_CODE = '$CUST_CODE'";
					$qTOT	= $this->db->query($sTOT)->result();
					foreach($qTOT as $rTOT) :
						$AR_T	= $rTOT->AR_T;
						$AR_P	= $rTOT->AR_P;
					endforeach;

					$AR_R 		= $AR_T - $AR_P;

					$AR_TP 	= $AR_T;
					if($AR_T == 0)
						$AR_TP	= 1;

					$ART_PERCA	= $AR_T / $AR_TAP * 100;
			        $ART_PERC	= $AR_R / $AR_TP * 100;
			        $ARP_PERC	= $AR_P / $AR_TP * 100;

		            // ----------- HUTANG
						if($AR_T <= 1000000000)
			            {
			            	$AR_TV = number_format($AR_T / 1000);
			            	$ARTCOD	= " K";
			            }
			            elseif($AR_T <= 1000000000000)
			            {
			            	$AR_TV = number_format($AR_T / 1000000);
			            	$ARTCOD	= " M";
			            }
			            else
			            {
			            	$AR_TV = $AR_T;
			            	$ARTCOD	= "";
			            }
			            $ART_V 		= $AR_TV.$ARTCOD;

		            // ----------- DIBAYAR
						if($AR_P <= 1000000000)
			            {
			            	$AR_PV = number_format($AR_P / 1000);
			            	$ARPCOD	= " K";
			            }
			            elseif($AR_P <= 1000000000000)
			            {
			            	$AR_PV = number_format($AR_P / 1000000);
			            	$ARPCOD	= " M";
			            }
			            else
			            {
			            	$AR_PV = $AR_P;
			            	$ARPCOD	= "";
			            }
			            $ARP_V 		= $AR_PV.$ARPCOD;

		            // ----------- SISA
						if($AR_R <= 1000000000)
			            {
			            	$AR_RV = number_format($AR_R / 1000);
			            	$DBRCOD	= " K";
			            }
			            elseif($AR_R <= 1000000000000)
			            {
			            	$AR_RV = number_format($AR_R / 1000000);
			            	$DBRCOD	= " M";
			            }
			            else
			            {
			            	$AR_RV = $AR_R;
			            	$DBRCOD	= "";
			            }
			            $DBR_V 		= $AR_RV.$DBRCOD;
				?>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-yellow-gradient">
						<span class="info-box-icon"><i class="fa fa-opencart"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $Sales; ?></span>
							<span class="info-box-number"><?php echo $ART_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$ARP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								<div class="col-md-6" title="Remain">
									<?=$ART_PERC?> %
								</div>
								<div class="col-md-6" title="Perc. from All Sales">
									<?=$ART_PERCA?> %
								</div>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-green-gradient">
						<span class="info-box-icon"><i class="fa fa-money"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $Received; ?></span>
							<span class="info-box-number"><?php echo $ARP_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$ARP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								<?=$ARP_PERC?> % <?php echo $Received; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-aqua-gradient">
						<span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $Remain; ?></span>
							<span class="info-box-number"><?php echo $DBR_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$ARP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								Total <?php echo $NotReceipt; ?>
							</span>
						</div>
					</div>
				</div>
	        <div class="col-md-9">
				<div class="nav-tabs-custom">
	            	<ul class="nav nav-tabs">
	                	<li class="active"><a href="#settings" data-toggle="tab"><?php echo $Biodata; ?></a></li> 		<!-- Tab 1 -->
	                    <li><a href="#profPicture" data-toggle="tab"><?php echo $CustPict; ?></a></li>			<!-- Tab 2 -->
	                </ul>
	                <!-- Biodata -->
	                <div class="tab-content">
	                    <div class="active tab-pane" id="settings">
	                        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return validate_cust()">
	                            <div class="box box-primary">
	                                <div class="box-header with-border">
	                                    <h3 class="box-title"><?php echo $CustInfo; ?></h3>
	                                </div>
									<?php if($isSetDocNo == 0) { ?>
	                                <br>
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
	                                <?php } else { ?>
	                                <br>
	                                <?php } ?>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo "$Code / $Name"; ?> </label>
	                                    <div class="col-sm-4">
	                                    	<?php
											if($task == 'add')
											{
												?>
	                                      		<input type="text" class="form-control" name="CUST_CODE" id="CUST_CODE" placeholder="<?php echo $CustCode; ?>"  value="<?php echo "$CUST_CODE"; ?>" onChange="functioncheck(this.value)">
	                                            <?php
											}
											else
											{
												?>
	                                      		<input type="text" class="form-control" name="CUST_CODE1" id="CUST_CODE1" placeholder="<?php echo $CustCode; ?>"  value="<?php echo "$CUST_CODE"; ?>" disabled >
	                                      		<input type="hidden" class="form-control" name="CUST_CODE" id="CUST_CODE" value="<?php echo "$CUST_CODE"; ?>" >
	                                            <?php
											}
											?>
	                                    </div>
	                                    <div class="col-sm-6">
	                                    	<input type="text" class="form-control" name="CUST_DESC" id="CUST_DESC" placeholder="<?php echo $Name; ?>"  value="<?php echo "$CUST_DESC"; ?>" >
	                                    </div>
	                                </div>
	                                <?php
	                                	$urlGetData	= base_url().'index.php/__l1y/GetCust/';
	                                ?>
                                    <script>
            							function functioncheck(myValue)
            							{
	                                        $.ajax({
	                                            type: 'POST',
	                                            url: '<?php echo $urlGetData; ?>',
	                                            data: $('#frm').serialize(),
	                                            success: function(response)
	                                            {
	                                            	var myarr	= response.split("~");
	                                                var resC 	= myarr[0];
	                                                var custNm	= myarr[1];
	                                                if(resC > 0)
	                                                {
														swal("<?php echo $alert07; ?> "+custNm,
														{
															icon: "error"
														})
														.then(function()
														{
															document.getElementById('CUST_CODE').value = '';
															document.getElementById('CUST_CODE').focus();
														});
														return false;
	                                                }
	                                            }
	                                        });
            							}
            						</script>
            						<div class="row">
            							<div class="col-sm-6">
			                                <div class="form-group">
			                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $Category; ?> </label>
			                                    <div class="col-sm-8">
			                                      	<select name="CUST_CAT" id="CUST_CAT" class="form-control select2">
				                                      	<option value="" > --- </option>
														<?php
				                                            $sql = "SELECT CUSTC_CODE, CUSTC_NAME FROM tbl_custcat";
				                                            $result = $this->db->query($sql)->result();
				                                            $i = 0;
				                                            foreach($result as $row) :
				                                                $CUSTC_CODE2= $row->CUSTC_CODE;
				                                                $CUSTC_DESC	= $row->CUSTC_NAME;
				                                                ?>
				                                                <option value="<?php echo $CUSTC_CODE2; ?>" <?php if($CUSTC_CODE2 == $CUST_CAT) { ?> selected <?php } ?>>
				                                                <?php echo $CUSTC_DESC; ?>                                </option>
				                                                <?php
				                                            endforeach;
				                                        ?>
			                                    	</select>
			                                    </div>
			                                </div>
			                                <div class="form-group">
			                                    <label for="inputName" class="col-sm-4 control-label"><?php echo $City; ?> </label>
			                                    <div class="col-sm-8">
			                                    	<input type="text" class="form-control" name="CUST_KOTA" id="CUST_KOTA" placeholder="<?php echo $City; ?>" value="<?php echo $CUST_KOTA; ?>" />
			                                    </div>
			                                </div>
            							</div>
            							<div class="col-sm-6">
			                                <div class="form-group">
			                                    <div class="col-sm-12">
			                                      	<textarea class="form-control" name="CUST_ADD1"  id="CUST_ADD1" style="height:83px" placeholder="<?php echo $Address ?>"><?php echo $CUST_ADD1; ?></textarea>
			                                    	</select>
			                                    </div>
			                                </div>
            							</div>
            						</div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label">NPWP / e-Mail</label>
	                                    <div class="col-sm-4">
	                                        <input type="text" class="form-control" name="CUST_NPWP" id="CUST_NPWP" onKeyPress="return isIntOnlyNew(event);" placeholder="NPWP"  value="<?php echo $CUST_NPWP; ?>" />
	                                    </div>
	                                    <div class="col-sm-6">
	                                        <input type="email" class="form-control" name="CUST_MAIL" id="CUST_MAIL" placeholder="Email" value="<?php echo "$CUST_MAIL"; ?>">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Contact; ?> / Telp. </label>
	                                    <div class="col-sm-4">
	                                        <input type="text" class="form-control" name="CUST_PERS" id="CUST_PERS" onKeyPress="return isIntOnlyNew(event);" placeholder="<?php echo $ContactPerson; ?>" value="<?php echo $CUST_PERS; ?>" />
	                                    </div>
	                                    <div class="col-sm-6">
	                                        <input type="text" class="form-control" name="CUST_TELP" id="CUST_TELP" onKeyPress="return isIntOnlyNew(event);" placeholder="<?php echo $Phone; ?>" value="<?php echo $CUST_TELP; ?>" />
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentType; ?></label>
	                                    <div class="col-sm-4">
	                                        <select name="CUST_TOP" id="CUST_TOP" class="form-control select2" onChange="chgTOP(this.value)">
	                                            <option value="0" <?php if($CUST_TOP == 0) { ?> selected <?php } ?>>Cash</option>
	                                            <option value="1" <?php if($CUST_TOP == 1) { ?> selected <?php } ?>>Kredit</option>
	                                        </select>
	                                    </div>
		                                <script>
		                            		function chgTOP(custTOP)
		                            		{
												if(custTOP == 0)
												{
													//$('#CUST_TOPD').val(0).trigger('change');
													var CUST_TOPD = $('#CUST_TOPD').val();
													if(CUST_TOPD > 0)
														$('#CUST_TOPD').val(0).trigger('change');
												}
												else
												{
													$('#CUST_TOPD').val(7).trigger('change');
												}
		                            		}

		                            		function chgTOPD(custTOP)
		                            		{
		                            			$('#CUST_TOPD').val(custTOP);
		                            			if(custTOP == 0)
		                            			{
		                            				$('#CUST_TOP').val(0).trigger('change');
		                            				$(this).blur();
		                            				return false;
		                            			}
		                            		}
		                            	</script>
	                                    <div class="col-sm-6">
	                                        <select name="CUST_TOPD" id="CUST_TOPD" class="form-control select2" onChange="chgTOPD(this.value)">
	                                            <option value="0" <?php if($CUST_TOPD == 0) { ?> selected <?php } ?>> --- </option>
	                                            <option value="7" <?php if($CUST_TOPD == 7) { ?> selected <?php } ?>>7 <?php echo $Day; ?></option>
	                                            <option value="15" <?php if($CUST_TOPD == 15) { ?> selected <?php } ?>>15 <?php echo $Day; ?></option>
	                                            <option value="30" <?php if($CUST_TOPD == 30) { ?> selected <?php } ?>>30 <?php echo $Day; ?></option>
	                                            <option value="45" <?php if($CUST_TOPD == 45) { ?> selected <?php } ?>>45 <?php echo $Day; ?></option>
	                                            <option value="60" <?php if($CUST_TOPD == 60) { ?> selected <?php } ?>>60 <?php echo $Day; ?></option>
	                                            <option value="75" <?php if($CUST_TOPD == 75) { ?> selected <?php } ?>>75 <?php echo $Day; ?></option>
	                                            <option value="90" <?php if($CUST_TOPD == 90) { ?> selected <?php } ?>>90 <?php echo $Day; ?></option>
	                                            <option value="120" <?php if($CUST_TOPD == 120) { ?> selected <?php } ?>>120 <?php echo $Day; ?></option>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $RekInfo; ?> </label>
	                                    <div class="col-sm-3">
	                                        <input type="text" class="form-control" name="CUST_NOREK" id="CUST_NOREK" onKeyPress="return isIntOnlyNew(event);" placeholder="<?php echo $RekNo; ?>" value="<?php echo "$CUST_NOREK"; ?>">
	                                    </div>
	                                    <div class="col-sm-3">
	                                        <input type="text" class="form-control" name="CUST_NMREK" id="CUST_NMREK" placeholder="<?php echo $AccountName; ?>" value="<?php echo "$CUST_NMREK"; ?>">
	                                    </div>
	                                    <div class="col-sm-4">
	                                        <input type="text" class="form-control" name="CUST_BANK" id="CUST_BANK" placeholder="<?php echo $BankName; ?>" value="<?php echo "$CUST_BANK"; ?>">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputSkills" class="col-sm-2 control-label"><?php echo $Status ?> </label>
	                                    <div class="col-sm-10">
	                                        <select name="CUST_STAT" id="CUST_STAT" class="form-control select2" >
	                                        	<option value="1" <?php if($CUST_STAT == 1) { ?>selected<?php } ?>><?php echo $Active;?></option>
	                                        	<option value="0" <?php if($CUST_STAT == 0) { ?>selected<?php } ?>><?php echo $Inactive;?></option>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputSkills" class="col-sm-2 control-label">&nbsp;</label>
	                                    <div class="col-sm-10">
		                                    <?php
												if($ISCREATE == 1)
												{
													if($task=='add')
													{
														?>
															<button class="btn btn-primary">
															<i class="fa fa-save"></i></button>&nbsp;
														<?php
													}
													else
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i></button>&nbsp;
														<?php
													}
												}
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
											?>
										</div>
	                                </div>
	                                <br>
	                            </div>
	                        </form>
				            <?php
				                $DefID      = $this->session->userdata['Emp_ID'];
				                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				                if($DefID == 'D15040004221')
				                    echo "<font size='1'><i>$act_lnk</i></font>";
				            ?>
	                    </div>
	                    <script>
							function validate_cust()
							{
								CUST_CODE	= document.getElementById('CUST_CODE').value;
								if(CUST_CODE == '')
								{
									swal('<?php echo $alert00; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_CODE').focus();
									});
									return false;
								}
								CUST_CAT	= document.getElementById('CUST_CAT').value;
								if(CUST_CAT == '')
								{
									swal('<?php echo $empCatNm; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_CAT').focus();
									});
									return false;
								}
								CUST_DESC	= document.getElementById('CUST_DESC').value;
								if(CUST_DESC == '')
								{
									swal('<?php echo $alert01; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_DESC').focus();
									});
									return false;
								}
								CUST_ADD1	= document.getElementById('CUST_ADD1').value;
								if(CUST_ADD1 == '')
								{
									swal('<?php echo $alert02; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_ADD1').focus();
									});
									return false;
								}
								CUST_KOTA	= document.getElementById('CUST_KOTA').value;
								if(CUST_KOTA == '')
								{
									swal('<?php echo $alert03; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_KOTA').focus();
									});
									return false;
								}
								CUST_TELP	= document.getElementById('CUST_TELP').value;
								if(CUST_TELP == '')
								{
									swal('<?php echo $alert04; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_TELP').focus();
									});
									return false;
								}
								CUST_NOREK	= document.getElementById('CUST_NOREK').value;
								if(CUST_NOREK == '')
								{
									swal('<?php echo $alert05; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_NOREK').focus();
									});
									return false;
								}
								CUST_BANK	= document.getElementById('CUST_BANK').value;
								if(CUST_BANK == '')
								{
									swal('<?php echo $alert06; ?>',
									{
										icon: "warning",
									})
									.then(function()
									{
										document.getElementById('CUST_BANK').focus();
									});
									return false;
								}
							}
						</script>
	                    <div class="tab-pane" id="profPicture">
	                        <form class="form-horizontal" name="frm" method="post" action="<?php echo $urlUpdProfPic; ?>" enctype="multipart/form-data" onSubmit="return checkData()">
	                            <div class="box box-primary">
	                                <div class="box-header with-border">
	                                    <h3 class="box-title"><?php echo $UpPict; ?></h3>
	                                </div>
	                                <br>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
	                                    <div class="col-sm-10">
	                                    	<?php
											if($task == 'add')
											{
												?>
	                                      		<input type="text" class="form-control" name="CUST_CODE" id="CUST_CODE" placeholder="EMP ID"  value="<?php echo "$CUST_CODE"; ?>" >
	                                            <?php
											}
											else
											{
												?>
	                                      		<input type="text" class="form-control" name="CUST_CODE1" id="CUST_CODE1" placeholder="EMP ID"  value="<?php echo "$CUST_CODE"; ?>" disabled >
	                                      		<input type="hidden" class="form-control" name="CUST_CODE" id="CUST_CODE" placeholder="EMP ID"  value="<?php echo "$CUST_CODE"; ?>" >
	                                            <?php
											}
											?>
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $FileName ?> </label>
	                                    <div class="col-sm-10">
	                                      <input type="text" class="form-control" name="FileName" id="FileName" placeholder="File Name" value="<?php echo $CUST_DESC; ?>">
	                                    </div>
	                                </div>
	                                <div class="form-group">
	                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ChooseFile ?> </label>
	                                    <div class="col-sm-10">
	                                      <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
	                                    </div>
	                                </div>
	                                <br>
	                            </div>
	                            <div class="form-group">
	                                <div class="col-sm-offset-2 col-sm-10">
	                                    <?php
											if($ISCREATE == 1)
											{
												if($task=='add')
												{
													?>
														<button class="btn btn-primary" style="display:none">
														<i class="cus-save-16x16"></i></button>&nbsp;
													<?php
												}
												else
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
											}
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
	                                    ?>
	                                </div>
	                            </div>
	                        </form>
	               		</div>
	                    <script>
							function checkData()
							{
								filename	= document.getElementById('FileName').value;
								if(filename == '')
								{
									swal('<?php echo $alert08; ?>',
									{
										icon: "warning",
									});
									document.getElementById('FileName').focus();
									return false;
								}
							}
						</script>                    
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