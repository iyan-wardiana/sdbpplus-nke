<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Oktober 2018
	* File Name	= v_prodprocess_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$comp_name 	= $this->session->userdata('comp_name');
$appBody    = $this->session->userdata['appBody'];

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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$STF_NUM		= '';
	$SC_NUM			= '';
	$STF_CODE 		= '';
	$STF_DATE		= '';
	$PRJNAME 		= '';
	$CUST_CODE 		= '0';
	$CUST_DESC 		= '';
	$CUST_ADD1 		= '';
	
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

	$yearCur	= date('Y');
	$sqlC		= "tbl_stf_header WHERE Patt_Year = $yearCur AND PRJCODE = '$PRJCODE'";
	$myCount 	= $this->db->count_all($sqlC);
	
	/*$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_stf_header
			WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
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
	
	$TRXTIME1		= date('ymdHis');
	$DocNumber 		= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
	$DocNumber		= "$Pattern_Code$PRJCODE-$TRXTIME1";
	
	$STFYEAR		= date('y');
	$STFMONTH		= date('m');
	$STF_CODE		= "$Pattern_Code.$lastPatternNumb.$STFYEAR.$STFMONTH"; // MANUAL CODE
	
	$STF_DATE 		= date('m/d/Y');
	$ETD			= date('m/d/Y');
	
	$JO_NUM			= '';
	$JO_CODE		= '';
	$STF_FROM		= '';
	$STF_DEST_TYPE	= '';
	$STF_DEST		= '';
	$STF_NOTES		= '';
	$STF_NOTES1		= '';
	$STF_STAT		= 3;
	$ISREADY		= 0;
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	$SC_NUMX		= $DocNumber;
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_stf_header~$Pattern_Length";
	$dataTarget		= "STF_CODE";
}
else
{
	$isSetDocNo = 1;
	$SO_NUM 		= $default['SO_NUM'];
	$DocNumber		= $SO_NUM;
	$SO_CODE 		= $default['SO_CODE'];
	$SO_TYPE 		= $default['SO_TYPE'];
	$SO_CAT 		= $default['SO_CAT'];
	$SO_DATE 		= $default['SO_DATE'];
	$SO_DATE		= date('m/d/Y', strtotime($SO_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$SC_NUM 		= $default['SC_NUM'];
	$SC_NUMX		= $SC_NUM;
	$SO_CURR 		= $default['SO_CURR'];
	$SO_CURRATE 	= $default['SO_CURRATE'];
	$SO_TOTCOST 	= $default['SO_TOTCOST'];
	$SO_PAYTYPE 	= $default['SO_PAYTYPE'];
	$SO_TENOR 		= $default['SO_TENOR'];
	$SO_PRODD 		= $default['SO_PRODD'];
	$SO_NOTES 		= $default['SO_NOTES'];
	$PRJNAME1 		= $default['PRJNAME'];
	$SO_STAT 		= $default['SO_STAT'];
	$lastPatternNumb1= $default['Patt_Number'];
	
	$SO_TAXRATE			= 1;
	$totTaxPPnAmount	= 1;
	$totTaxPPhAmount	= 1;
	
	$SO_RECEIVLOC	= $default['SO_RECEIVLOC'];
	$SO_RECEIVCP	= $default['SO_RECEIVCP'];
	$SO_SENTROLES 	= $default['SO_SENTROLES'];
	$SO_REFRENS 	= $default['SO_REFRENS'];
}
//echo $SC_NUMX;
$secGenCode	= base_url().'index.php/c_production/c_b0fm47/genCode/'; // Generate Code

$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;
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

    	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/qrcode/css/style.css'; ?>">
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
			if($TranslCode == 'PONumber')$PONumber = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
			if($TranslCode == 'Product')$Product = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'ProcessCode')$ProcessCode = $LangTransl;
			if($TranslCode == 'TsfFrom')$TsfFrom = $LangTransl;
			if($TranslCode == 'OthNotes')$OthNotes = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'SODate')$SODate = $LangTransl;
			if($TranslCode == 'JODate')$JODate = $LangTransl;
			if($TranslCode == 'machineNm')$machineNm = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= 'Silahkan Scan QR Code.';
			$alert2		= 'Silahkan pilih nama supplier';
			$alert3		= 'Ada tahapan sebelumnya yang belum diproses';
			$alert4		= "Silahkan pilih mesin yang akan digunakan.";
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert1		= 'Please scan the QR Code.';
			$alert2		= 'Please select a supplier name';
			$alert3		= 'There are previous stages that have not been processed';
			$alert4		= "Please select the machine to be used.";
			$isManual	= "Check to manual code.";
		}

		$sqlProds	= "SELECT PRODS_STEP, PRODS_NAME, PRODS_DESC FROM tbl_prodstep WHERE PRODS_STEP = '$PRODS_STEP' LIMIT 1";
	    $resProds	= $this->db->query($sqlProds)->result();
	    foreach($resProds as $rowProds) :
	        $PRODS_NAME	= $rowProds->PRODS_NAME;
	        $PRODS_DESC	= $rowProds->PRODS_DESC;
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
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/secttransfer.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

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
		                    <div class="container" id="QR-Code">
		                        <div class="form-group">
		                            <div class="col-sm-10">
		                                <select class="form-control" id="camera-select" style="display:none"></select>
		                                <div class="form-group" style="text-align:center">
		                                    <input id="image-url" type="text" class="form-control" placeholder="Image url" style="display:none">
		                                    <button title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip" style="display:none"><span class="glyphicon glyphicon-upload"></span></button>
		                                    <button title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip" style="display:none"><span class="glyphicon glyphicon-picture"></span></button>
		                                    <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play"></span></button>
		                                    <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-pause"></span></button>
		                                    <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop"></span></button>
		                                </div>
		                            </div>
		                        </div>
		                    
		                        <div class="form-group">
		                            <div class="col-sm-10">
		                                <div class="form-group" style="text-align:center">
		                                    <div class="well" style="position: relative;display: inline-block;">
		                                        <canvas style="max-width: 250px; max-height: 250px" id="webcodecam-canvas"></canvas>
		                                        <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
		                                        <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
		                                        <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
		                                        <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        <?php
		                            $urlGetData	= base_url().'index.php/c_production/c_pR04uctpr0535/Get_Data/';
		                            $urlProcess	= base_url().'index.php/c_production/c_pR04uctpr0535/add_process_qrc/';
		                            $back		= site_url('c_production/c_pR04uctpr0535/a44QR_pR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <form method="post" name="sendData" id="sendData" class="form-user" action="<?php echo $urlProcess; ?>" onSubmit="return checkQRC()">
		                        	<input type="hidden" name="STF_CODE" id="STF_CODE" value="<?php echo $STF_CODE; ?>">
		                        	<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
		                        	<input type="hidden" name="JO_NUM" id="JO_NUM" value="<?php echo $JO_NUM; ?>">
		                        	<input type="hidden" name="STF_FROM" id="STF_FROM" value="<?php echo $PRODS_STEP; ?>">
		                        	<input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $myMax; ?>">
		                        	<input type="hidden" name="CSTEP" id="CSTEP" value="<?php echo $PRODS_STEP; ?>">
		                        	<input type="hidden" name="ISREADY" id="ISREADY" value="<?php echo $ISREADY; ?>">
		                        	<input type="hidden" name="STF_STAT" id="STF_STAT" value="3">
			                        <div class="form-group">
			                            <div class="col-sm-10">
		                                    <div class="form-group" style="text-align:center">
		                                        <div class="thumbnail" id="result">
		                                            <div class="well" style="overflow: hidden; display:none">
		                                                <img width="150" height="150" id="scanned-img" src="">
		                                            </div>
		                                            <div class="caption">
		                                                <p id="scanned-QR"></p>
		                                                <input type="text" name="scanned-QRText" id="scanned-QRText" value="" width="250px" class="inplabel" style="display:none" >
		                                                <button type="button" class="btn btn-primary" id="btnDetail">
		                                                    <i class="glyphicon glyphicon glyphicon-th"></i>&nbsp;Detail
		                                                </button>
		                                                <button type="button" class="btn btn-danger" onClick="subReject();" style="display: none;">
		                                                    <i class="glyphicon glyphicon glyphicon-retweet"></i>&nbsp;Revise
		                                                </button>
		                                                <button class="btn btn-success" name="idNext" id="idNext" onclick="STFStat(3)" style="display: none;">
		                                                    <i class="glyphicon glyphicon glyphicon-thumbs-up"></i>&nbsp;Next
		                                                </button>
		                                                <button class="btn btn-danger" name="idReject" id="idReject" onclick="STFStat(5)" style="display: none;">
		                                                    <i class="glyphicon glyphicon glyphicon-thumbs-down"></i>&nbsp;Reject
		                                                </button>
		                                                <?php echo anchor("$back",'<button class="btn btn-info" type="button"><i class="fa fa-reply"></i></button>'); ?>
		                                            </div>
		                                        </div>
		                                    </div>
			                            </div>
										<script>
											function STFStat(stfStat)
											{
												$("#STF_STAT").val(stfStat);
											}
											
			                                $(document).ready(function()
			                                {
			                                    $("#btnDetail").button().click(function()
			                                    {
			                                        var textField1 	= $("#scanned-QRText").val();
			                                        var cStep 		= $("#CSTEP").val();
			                                        if(textField1 == '')
			                                        {
								                        swal('<?php echo $alert1; ?>', 
								                        {
								                            icon: "warning",
								                        });
			                                            return false;
			                                        }
			                                        else
			                                        {
			                                        	var textField 	= textField1+'~'+cStep;
			                                        }

			                                        $.ajax({
			                                            type: 'POST',
			                                            url: '<?php echo $urlGetData; ?>',
			                                            data: $('#sendData').serialize(),
			                                            success: function(response)
			                                            {
			                                                // swal(response)
			                                                // IR.000002.19.03~NO JO~SO.000001.19.03~PT Dian Abadi Jaya
			                                                // $PRJCODE~$JO_NUM~$JO_CODE~$JO_DATE~$SO_NUM~$SO_CODE~$SO_DATE~$JO_DESC~$CUST_CODE~$CUST_DESC
			                                                var myarr = response.split("~");
			                                                document.getElementById('JO_NUM').value 	= myarr[1];
			                                                document.getElementById('JOCode').innerHTML = myarr[2];
			                                                document.getElementById('JODate').innerHTML = myarr[3];
			                                                document.getElementById('SOCode').innerHTML = myarr[5];
			                                                document.getElementById('SODate').innerHTML = myarr[6];
			                                                document.getElementById('JODesc').innerHTML = myarr[7];
			                                                document.getElementById('custNm').innerHTML = myarr[9];
			                                                document.getElementById('ISREADY').value 	= myarr[10];
			                                                var ISREADY = myarr[10];
			                                                var ISPROC 	= myarr[11];
			                                                var ISPROCD = myarr[12];
			                                                var ISAUTH	= myarr[13];
			                                                var NEEDRM	= myarr[14];
			                                                var NEEDRMD	= myarr[15];
			                                                var NEEDLR	= myarr[16];
			                                                var NEEDLRD	= myarr[17];

			                                                if(response == '~')
			                                                {
					                                        	document.getElementById('detInfo').style.display 	= 'none';
					                                        	document.getElementById('warnStep').style.display 	= 'none';
					                                        	document.getElementById('warnBStep').style.display 	= 'none';
					                                        	document.getElementById('warnNoP').style.display 	= 'none';
					                                        	document.getElementById('warnAuth').style.display 	= 'none';
					                                        	document.getElementById('warnJO').style.display 	= '';
			                                                }
			                                                else if(response == 'NoP')
			                                                {
					                                        	document.getElementById('detInfo').style.display 	= 'none';
					                                        	document.getElementById('warnJO').style.display 	= 'none';
					                                        	document.getElementById('warnStep').style.display 	= 'none';
					                                        	document.getElementById('warnBStep').style.display 	= 'none';
					                                        	document.getElementById('warnAuth').style.display 	= 'none';
					                                        	document.getElementById('warnNoP').style.display 	= '';
			                                                }
			                                                else
			                                                {
					                                        	document.getElementById('warnJO').style.display 	= 'none';
					                                        	document.getElementById('warnStep').style.display 	= 'none';
					                                        	document.getElementById('warnBStep').style.display 	= 'none';
					                                        	document.getElementById('warnNoP').style.display 	= 'none';
					                                        	document.getElementById('warnAuth').style.display 	= 'none';

				                                                if(myarr[10] == 1)
				                                                {
					                                                document.getElementById('idNext').style.display 	= '';
					                                                document.getElementById('idReject').style.display 	= '';
				                                                }

				                                                if(ISPROC == 1)
				                                                {
					                                                document.getElementById('idNext').style.display 		= 'none';
					                                                document.getElementById('idReject').style.display 		= 'none';
					                                                document.getElementById('warnStep').style.display 		= '';
					                                        		document.getElementById('warnBStep').style.display 		= 'none';
					                                                document.getElementById('detInfo').style.display 		= 'none';
					                                                document.getElementById('STF_NOTESID1').style.display 	= 'none';
					                                                document.getElementById('STF_NOTESID2').style.display 	= 'none';
				                                                	document.getElementById('procD').innerHTML 				= ISPROCD;
				                                                }
				                                                else
				                                                {
					                                                document.getElementById('warnStep').style.display 		= 'none';
					                                                document.getElementById('detInfo').style.display 		= '';
				                                                }

				                                                if(ISAUTH == 0)
				                                                {
				                                                	document.getElementById('idNext').style.display 		= 'none';
				                                                	document.getElementById('idReject').style.display 		= 'none';
						                                        	document.getElementById('detInfo').style.display 		= 'none';
						                                        	document.getElementById('warnJO').style.display 		= 'none';
						                                        	document.getElementById('warnStep').style.display 		= 'none';
					                                        		document.getElementById('warnBStep').style.display 		= 'none';
						                                        	document.getElementById('warnNoP').style.display 		= 'none';
					                                        		document.getElementById('warnAuth').style.display 		= '';
				                                                }
				                                                if(ISREADY == 0)
				                                                {
				                                                	document.getElementById('warnBStep').style.display 		= '';
				                                                	document.getElementById('detInfo').style.display 		= 'none';
				                                                }

				                                                if(NEEDRM > 0)
				                                                {
				                                                	document.getElementById('needMR').style.display 		= '';
				                                                	document.getElementById('needMR').innerHTML 			= 'There are a material(s) that are out of stock. '+NEEDRMD;
				                                                	document.getElementById('detInfo').style.display 		= 'none';
					                                                document.getElementById('idNext').style.display 		= 'none';
					                                                document.getElementById('idReject').style.display 		= 'none';
				                                                }
				                                                else
				                                                {
					                                                if(NEEDLR > 0)
					                                                {
					                                                	document.getElementById('needMR').style.display 		= '';
					                                                	document.getElementById('needMR').innerHTML 			= 'There are a material(s) '+NEEDLRD;
					                                                	document.getElementById('detInfo').style.display 		= 'none';
						                                                document.getElementById('idNext').style.display 		= 'none';
						                                                document.getElementById('idReject').style.display 		= 'none';
					                                                }
					                                            }
			                                                }
			                                            }
			                                        });
			                                    });
			                                });
			                            </script>
			                            <div class="form-group">
			                                <div class="row">
			                                    <div class="col-md-12">
			                                        <div class="box box-widget widget-user-2">
			                                            <div class="widget-user-header bg-red" style="text-align:center; display: none;" id="warnStep">
			                                                Step <?php echo $PRODS_NAME; ?> Already Processed - <div id="procD"></div>
			                                            </div>
			                                            <div class="widget-user-header bg-red" style="text-align:center; display: none;" id="warnBStep">
			                                               There are previous stages that have not been processed.
			                                            </div>
			                                            <div class="widget-user-header bg-red" style="text-align:center; display: none;" id="needMR">
			                                               There are a material that is out of stock.
			                                            </div>
			                                            <div class="widget-user-header bg-red" style="text-align:center; display: none;" id="warnNoP">
			                                                <?php echo $PRODS_NAME; ?> : Sorry, this process is not found.
			                                            </div>
			                                            <div class="widget-user-header bg-red" style="text-align:center; display: none;" id="warnJO">
			                                                <?php echo $PRODS_NAME; ?> : Sorry, JO Number is not found.
			                                            </div>
			                                            <div class="widget-user-header bg-aqua" style="text-align:center; display: none;" id="warnAuth">
			                                                <?php echo $PRODS_NAME; ?> : Sorry, You do not have authorization to process this stage.
			                                            </div>
			                                            <div class="widget-user-header bg-yellow" style="text-align:center" id="detInfo">
			                                                Detail Information : <?php echo $PRODS_NAME; ?>
			                                            </div>
			                                            <div class="box-footer no-padding">
			                                                <ul class="nav nav-stacked">
			                                                    <li><a href="#" onClick="return false"><?php echo $CustName; ?>
			                                                    	<span class="pull-right badge bg-aqua">
			                                                        	<div id="custNm"></div>
			                                                        </span>
			                                                        </a>
			                                                    </li>
			                                                    <li><a href="#" onClick="return false">SO No.
			                                                    	<span class="pull-right badge bg-green">
			                                                        	<div id="SOCode"></div>
			                                                        </span>
			                                                        </a>
			                                                    </li>
			                                                    <li><a href="#" onClick="return false"><?php echo $SODate; ?>
			                                                    	<span class="pull-right badge bg-green">
			                                                        	<div id="SODate"></div>
			                                                        </span>
			                                                        </a>
			                                                    </li>
			                                                    <li><a href="#" onClick="return false">JO No.
			                                                    	<span class="pull-right badge bg-blue">
			                                                        	<div id="JOCode"></div>
			                                                        </span>
			                                                        </a>
			                                                    </li>
			                                                    <li><a href="#" onClick="return false"><?php echo $JODate; ?>
			                                                    	<span class="pull-right badge bg-blue">
			                                                    		<div id="JODate"></div>
			                                                        </span></a>
			                                                    </li>
			                                                    <li><a href="#" onClick="return false"><?php echo $Notes; ?> JO</a>
			                                                    </li>
			                                                    <li><a href="#" onClick="return false">
			                                                        <div class="text-red" id="JODesc" style="font-style: italic;">-</div></a>
			                                                    </li>
			                                                    <li id="STF_NOTESID1" style="display: none;"><a href="#" onClick="return false"><?php echo $machineNm; ?></a>
			                                                    </li>
			                                                    <li id="STF_NOTESID2" style="display: none;">
			                                                    	<a href="#" onClick="return false">
				                                                    	<select name="MCN_NUM" id="MCN_NUM" class="form-control select2">
				                                                    		<option value="0"> --- </option>
			                                                            	<option value="NMCN"> Manual </option>
				                                                            <?php
				                                                            	$sqlMCN	= "SELECT MCN_NUM, MCN_CODE, MCN_NAME, MCN_ITMCAL
				                                                            				FROM tbl_machine
				                                                            				WHERE MCN_PSTEP = '$PRODS_STEP'";
								                                            	$resMCN = $this->db->query($sqlMCN)->result();
								                                            	foreach($resMCN as $rowMCN) :
									                                                $MCN_NUM1	= $rowMCN->MCN_NUM;
									                                                $MCN_CODE1	= $rowMCN->MCN_CODE;
									                                                $MCN_NAME1	= $rowMCN->MCN_NAME;
									                                                $MCN_ITMCAL	= $rowMCN->MCN_ITMCAL;
			                                    									?>
						                                            				<option value="<?php echo $MCN_NUM1; ?>"><?php echo $MCN_NAME1; ?></option>
						                                            				<?php
						                                            			endforeach;
						                                            		?>
				                                                    	</select>
				                                                    </a>
			                                                    </li>
			                                                    <li id="STF_NOTESID1"><a href="#" onClick="return false"><?php echo $OthNotes; ?></a>
			                                                    </li>
			                                                    <li id="STF_NOTESID2"><a href="#" onClick="return false">
			                                                        <textarea class="form-control" name="STF_NOTES"  id="STF_NOTES"></textarea></a>
			                                                    </li>
			                                                    <li>
			                                                    	<td width="15%" style="text-align:left;" nowrap>
		                                                            	<?php echo anchor("$back",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;</button>'); ?>
		                                                        	</td>
			                                                    </li>
			                                                </ul>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
		                    	</form>
		                    </div>
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

	function checkQRC()
	{
		//return false;
		var textField = $("#scanned-QRText").val();
        if(textField == '')
        {
            swal('<?php echo $alert1; ?>', 
            {
                icon: "warning",
            });
            return false;
        }

		var ISREADY = $("#ISREADY").val();
        if(ISREADY == 0)
        {
            swal('<?php echo $alert3; ?>', 
            {
                icon: "warning",
            });
            return false;
        }
        
        /*var MCN_NUM 	= $("#MCN_NUM").val();
        if(MCN_NUM == 0)
        {
        	swal('<?php echo $alert4; ?>', 
            {
                icon: "warning",
            });
            return false;
        }*/
	}
	
	function subReject(strItem) 
	{		
		document.getElementById('STF_STAT').value 	= 5;
		document.sendData.idNext.click();
	}
  
	var decFormat		= 2;
	
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
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
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