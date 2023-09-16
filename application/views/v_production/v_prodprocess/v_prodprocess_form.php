<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Oktober 2018
	* File Name	= v_prodprocess_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
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

$currRow = 0;
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
	$STF_STAT		= 1;
	
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
	
	$STF_NUM 		= $default['STF_NUM'];
	$DocNumber		= $default['STF_NUM'];
	$STF_CODE 		= $default['STF_CODE'];
	$STF_DATE 		= $default['STF_DATE'];
	$STF_DATE		= date('m/d/Y', strtotime($STF_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$PRJCODE		= $default['PRJCODE'];
	$JO_NUM 		= $default['JO_NUM'];
	$JO_CODE 		= $default['JO_CODE'];
	$SO_NUM 		= $default['SO_NUM'];
	$SO_CODE 		= $default['SO_CODE'];
	$CCAL_NUM 		= $default['CCAL_NUM'];
	$CCAL_CODE 		= $default['CCAL_CODE'];
	$BOM_NUM 		= $default['BOM_NUM'];
	$BOM_CODE 		= $default['BOM_CODE'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$CUST_DESC		= $default['CUST_DESC'];
	$STF_TYPE 		= $default['STF_TYPE'];
	$STF_FROM 		= $default['STF_FROM'];
	$STF_DEST 		= $default['STF_DEST'];
	$STF_NOTES 		= $default['STF_NOTES'];
	$STF_NOTES1 	= $default['STF_NOTES1'];
	$STF_STAT 		= $default['STF_STAT'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number 	= $default['Patt_Number'];
	$lastPatternNumb1	= $default['Patt_Number'];
}

$PRJNAME	= '';
$sqlPRJ 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME = $rowPRJ->PRJNAME;
endforeach;

$WH_CODE	= '';
$sqlWH 		= "SELECT WH_CODE FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE' AND ISWHPROD = 1";
$resWH 		= $this->db->query($sqlWH)->result();
foreach($resWH as $rowWH) :
	$WH_CODE = $rowWH->WH_CODE;
endforeach;

$secGenCode	= base_url().'index.php/c_production/c_b0fm47/genCode/'; // Generate Code
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
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
			if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
			if($TranslCode == 'ProcessCode')$ProcessCode = $LangTransl;
			if($TranslCode == 'TsfFrom')$TsfFrom = $LangTransl;
			if($TranslCode == 'TsfTo')$TsfTo = $LangTransl;
			if($TranslCode == 'Section')$Section = $LangTransl;
			if($TranslCode == 'Warehouse')$Warehouse = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= 'Jumlah ';
			$alert2		= ' tidak boleh nol.';
			$alert3		= 'Tahapan produksi tidak boleh sama.';
			$alert4		= 'No. JO tidak boleh kosong.';
			$alert5		= 'Tentukan tahapan produksi.';
			$alert6		= 'Tentukan tahapan produksi selanjutnya.';
			$alert7		= 'Jumlah yang dimasukan melebihi stock.';
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert1		= 'Qty of ';
			$alert2		= ' can not be empty.';
			$alert3		= 'Production stages cannot be the same..';
			$alert4		= 'JO Number can not ve empty.';
			$alert5		= 'Select the step of production.';
			$alert6		= 'Select the next step of production.';
			$alert7		= 'Qty you entered exceeds the stock.';
			$isManual	= "Check to manual code.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
				<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/request.png'; ?>" style="max-width:40px; max-height:40px" >
			    <?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
	            <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <input type="hidden" name="rowCount" id="rowCount" value="0">
				                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
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
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $PONumber ?> </label>
				                    <div class="col-sm-9">
				                        <input type="text" class="form-control" style="max-width:195px" name="STF_NUM1" id="STF_NUM1" value="<?php echo $DocNumber; ?>" disabled >
				                        <input type="hidden" class="textbox" name="STF_NUM" id="STF_NUM" size="30" value="<?php echo $DocNumber; ?>" />
				                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ProcessCode; ?> </label>
				                    <div class="col-sm-9">
				                        <!-- <label> -->
				                            <input type="text" class="form-control" name="STF_CODE1" id="STF_CODE1" value="<?php echo $STF_CODE; ?>" disabled >
				                            <input type="hidden" class="form-control" style="min-width:100px" name="STF_CODE" id="STF_CODE" value="<?php echo $STF_CODE; ?>" >
				                        <!-- </label>
				                        <label>
				                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
				                        </label>
				                        <label style="font-style:italic">
				                            <?php echo $isManual; ?>
				                        </label> -->
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
				                    <div class="col-sm-9">
				                        <div class="input-group date">
				                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
				                            <input type="text" name="STF_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $STF_DATE; ?>" style="width:100px" disabled>
				                        </div>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?> JO</label>
				                    <div class="col-sm-9">
				                        <div class="input-group">
				                            <div class="input-group-btn">
				                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
				                            </div>
				                            <input type="hidden" class="form-control" name="JO_NUM" id="JO_NUM" style="max-width:350px;" value="<?php echo $JO_NUM; ?>" />
				                            <input type="hidden" class="form-control" name="JO_CODE" id="JO_CODE" style="max-width:350px;" value="<?php echo $JO_CODE; ?>" />
				                            <input type="text" class="form-control" name="JO_CODE1" id="JO_CODE1" value="<?php echo $JO_CODE; ?>" onClick="getJOCODE();" <?php if($STF_STAT != 1 && $STF_STAT != 4) { ?> disabled <?php } ?>>
				                        </div>
				                    </div>
				                </div>
				                <?php
				                    $url_selJO	= site_url('c_production/c_pR04uctpr0535/s3l4llj0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				                ?>
				                <script>
				                    var url1 = "<?php echo $url_selJO;?>";
				                    function getJOCODE()
				                    {
				                        PRJCODE	= document.getElementById('PRJCODE').value;
				                        
				                        title 	= 'Select Item';
				                        w = 1000;
				                        h = 550;
				                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
				                        return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                    }
				                </script>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $TsfFrom ?> </label>
				                    <div class="col-sm-9">
				                        <select name="STF_FROM" id="STF_FROM" class="form-control select2" onChange="chk1Stp(this.value)" disabled>
				                            <option value="" >--- None ---</option>
				                            <?php
				                                $sqlSTEPC	= "tbl_prodstep";
				                                $resSTEPC	= $this->db->count_all($sqlSTEPC);
				                                
				                                if($resSTEPC > 0)
				                                {
				                                    $sqlSTEP	= "SELECT * FROM tbl_prodstep ORDER BY PRODS_ID ASC";
				                                    $resSTEP	= $this->db->query($sqlSTEP)->result();
				                                    foreach($resSTEP as $row) :
				                                        $PRODS_ID	= $row->PRODS_ID;
				                                        $PRODS_CODE	= $row->PRODS_CODE;
				                                        $PRODS_STEP	= $row->PRODS_STEP;
				                                        $PRODS_NAME	= $row->PRODS_NAME;
				                                        ?>
				                                            <option value="<?php echo $PRODS_STEP; ?>" <?php if($PRODS_STEP == $STF_FROM) { ?> selected <?php } ?>><?php echo $PRODS_NAME; ?></option>
				                                        <?php
				                                    endforeach;
				                                }
				                            ?>
				                        </select>
				                    </div>
				                </div>
				                <script>
				                    function getTSfType(thisVal)
				                    {
				                        if(thisVal == 1)
				                        {
				                            document.getElementById('tsf').style.display 	= '';
				                            document.getElementById('wh').style.display 	= 'none';
				                        }
				                        else if(thisVal == 2)
				                        {
				                            document.getElementById('tsf').style.display 	= 'none';
				                            document.getElementById('wh').style.display 	= '';
				                        }
				                    }
				                </script>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Type; ?> </label>
				                    <div class="col-sm-9">
			                            <select name="STF_TYPE" id="STF_TYPE" class="form-control" onChange="getTSfType(this.value)">
			                                <option value="" > --</option>
			                                <option value="1" selected ><?php echo $Section; ?></option>
			                                <option value="2" ><?php echo $Warehouse; ?></option>
			                            </select>
			                    	</div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $TsfTo ?> </label>
				                    <div class="col-sm-9">
				                        <!-- <label id="tsf"> -->
				                            <select name="STF_DEST" id="STF_DEST" class="form-control select2" onChange="chkStep(this.value)" disabled>
				                                <option value="" >  -- <?php echo $Section; ?> -- </option>
				                                <?php
				                                    $sqlSTEP	= "SELECT * FROM tbl_prodstep ORDER BY PRODS_ID ASC";
				                                    $resSTEP	= $this->db->query($sqlSTEP)->result();
				                                    if($resSTEPC > 0)
				                                    {
				                                        foreach($resSTEP as $row) :
				                                            $PRODS_ID	= $row->PRODS_ID;
				                                            $PRODS_CODE	= $row->PRODS_CODE;
				                                            $PRODS_STEP	= $row->PRODS_STEP;
				                                            $PRODS_NAME	= $row->PRODS_NAME;
				                                            ?>
				                                                <option value="<?php echo $PRODS_STEP; ?>" <?php if($PRODS_STEP == $STF_DEST) { ?> selected <?php } ?>><?php echo $PRODS_NAME; ?></option>
				                                            <?php
				                                        endforeach;
				                                    }
				                                ?>
				                            </select>
				                        <!-- </label> -->
				                    </div>
				                </div>
				                <script>
				                    function chk1Stp(thisVal)
				                    {
				                        //var firstStep	= $("#STF_FROM").val();
				                        var firstStep	= thisVal;
				                        var JO_NUM		= $("#JO_NUM").val();
				                        if(JO_NUM == '')
				                        {
				                            swal('<?php echo $alert4; ?>');
				                            $("#STF_FROM").val(0);
				                            $("#JO_CODE1").focus();
				                            return false;
				                        }
				                        
				                        var	PRJCODE		= '<?php echo $PRJCODE; ?>';
				                        var dataColl	= PRJCODE+'~'+firstStep;
				                        
				                        var selectElement = document.getElementById("STF_DEST");		
				                        while (selectElement.length > 0) {
				                          selectElement.remove(0);
				                        }
				                        
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
				                                    var options = JSON.parse(xmlhttpTask.responseText);
				                                      $("#STF_DEST").append("<option value='0'>--- None ---</option>");
				                                    for(var i in options)
				                                    {
				                                      $("#STF_DEST").append("<option value='"+ options[i].PRODS_STEP +"'>"+ options[i].PRODS_NAME +"</option>");
				                                    }
				                                }
				                            }
				                        }
				                        xmlhttpTask.open("GET","<?php echo base_url().'index.php/c_production/c_j0b0rd3r/getSTEPLIST/';?>"+dataColl,true);
				                        xmlhttpTask.send();
				                    }
				                    
				                    function chkStep(thisVal)
				                    {
				                        var firstStep	= $("#STF_FROM").val();
				                        var nextStep	= $("#STF_DEST").val();
				                        var JO_NUM		= $("#JO_NUM").val();
				                        
				                        if(JO_NUM == '')
				                        {
				                            swal('<?php echo $alert4; ?>');
				                            $("#STF_DEST").val(0);
				                            $("#JO_CODE1").focus();
				                            return false;
				                        }
				                        
				                        if(firstStep == nextStep)
				                        {
				                            swal('<?php echo $alert3; ?>');
				                            $("#STF_DEST").val(0);
				                            $("#STF_DEST").focus();
				                            return false;
				                        }
				                    }
				                </script>
				                <div class="form-group" style="display:none">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
				                    <div class="col-sm-9">
				                        <select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
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
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Description ?> </label>
				                    <div class="col-sm-9">
				                        <textarea class="form-control" name="STF_NOTES"  id="STF_NOTES" style="height:70px" disabled><?php echo $STF_NOTES; ?></textarea>
				                    </div>
				                </div>
				                <!--
				                    APPROVE STATUS
				                    1 - New
				                    2 - Confirm
				                    3 - Approve
				                -->
				                <div class="form-group" style="display: none;">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
				                    <div class="col-sm-9">
				                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $STF_STAT; ?>">
				                        <?php
				                            $isDisabled = 1;
				                            if($STF_STAT == 1 || $STF_STAT == 4)
				                            {
				                                $isDisabled = 0;
				                            }
				                        ?>
				                        <select name="STF_STAT" id="STF_STAT" class="form-control select2" style="max-width:150px" disabled>
				                            <?php
				                            if($STF_STAT != 1 AND $STF_STAT != 4) 
				                            {
				                                ?>
				                                    <option value="1"<?php if($STF_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
				                                    <option value="2"<?php if($STF_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
				                                    <option value="3"<?php if($STF_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
				                                    <option value="4"<?php if($STF_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
				                                    <option value="5"<?php if($STF_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
				                                    <option value="6"<?php if($STF_STAT == 6) { ?> selected <?php } ?>>Closed</option>
				                                    <option value="7"<?php if($STF_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
				                                <?php
				                            }
				                            else
				                            {
				                                ?>
				                                    <option value="1"<?php if($STF_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                    <option value="2"<?php if($STF_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                <?php
				                            }
				                            ?>
				                        </select>
				                    </div>
				                </div>
				                <?php
					                $url_WIP	= site_url('c_production/C_pR04uctpr0535/s3l4llW1p/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					                $url_RM		= site_url('c_production/C_pR04uctpr0535/s3l4llRM/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				                ?>
				            </div>
				        </div>
				    </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-cubes"></i>
								<h3 class="box-title"><?php echo $RawMtr; ?></h3>
							</div>
							<div class="box-body">
			                    <!-- START : RM NEEDED -->
	                                <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="4%" height="25" style="text-align:center">No.</th>
	                                        <th width="9%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
	                                        <th width="65%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
	                                        <th width="4%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
	                                        <th width="18%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
	                                    </tr>
	                                    <?php
	                                        $resRMC	= 0;
	                                        if($task == 'edit')
	                                        {																
	                                            $sqlRM		= "SELECT A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, B.ITM_NAME, B.ITM_UNIT, 
	                                                                A.STF_VOLM, A.STF_PRICE, A.STF_TOTAL, A.ACC_ID, A.ACC_ID_UM
	                                                            FROM tbl_stf_detail A
	                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                                    AND B.PRJCODE = '$PRJCODE'
	                                                            WHERE A.STF_NUM = '$STF_NUM'
	                                                                AND A.ITM_TYPE = 'IN'";
	                                            $resRM 	= $this->db->query($sqlRM)->result();
	                                            
	                                            $sqlRMC	= "tbl_stf_detail A
	                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                                    AND B.PRJCODE = '$PRJCODE'
	                                                            WHERE A.STF_NUM = '$STF_NUM'
	                                                                AND A.ITM_TYPE = 'IN'";
	                                            $resRMC 	= $this->db->count_all($sqlRMC);
	                                        }
	                                            
	                                        $i			= 0;
	                                        $j			= 0;
	                                        if($resRMC > 0)
	                                        {
	                                            foreach($resRM as $rowRM) :
	                                                $currRow  		= ++$i;																
	                                                $STF_NUM 		= $STF_NUM;
	                                                $STF_CODE 		= $STF_CODE;
	                                                $PRJCODE		= $PRJCODE;
	                                                $ITM_TYPE		= $rowRM->ITM_TYPE;
	                                                $ITM_CODE		= $rowRM->ITM_CODE;
	                                                $ITM_GROUP		= $rowRM->ITM_GROUP;
	                                                $ITM_NAME 		= $rowRM->ITM_NAME;
	                                                $ITM_UNIT 		= $rowRM->ITM_UNIT;
	                                                $STF_VOLM 		= $rowRM->STF_VOLM;
	                                                $STF_PRICE 		= $rowRM->STF_PRICE;
	                                                $STF_TOTAL 		= $rowRM->STF_TOTAL;
	                                                $ACC_ID 		= $rowRM->ACC_ID;
	                                                $ACC_ID_UM 		= $rowRM->ACC_ID_UM;
	                                                
	                                                // CEK STOCK PER WH
	                                                    $ITM_STOCK	= 0;
	                                                    $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
	                                                                    WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
	                                                                        AND WH_CODE = '$WH_CODE'";
	                                                    $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
	                                                    foreach($resWHSTOCK as $rowSTOCK) :
	                                                        $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
	                                                    endforeach;
	                                    
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
	                                                    <td width="4%" height="25" style="text-align:center" nowrap>
	                                                        <?php
	                                                            if($STF_STAT == 1)
	                                                            {
	                                                                ?>
	                                                                    <a href="#" onClick="deleteRow(<?php echo $currRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
	                                                                <?php
	                                                            }
	                                                            else
	                                                            {
	                                                                echo "$currRow.";
	                                                            }
	                                                        ?>
	                                                        <input style="display:none" type="Checkbox" id="dataRM[<?php echo $currRow; ?>][chk]" name="dataRM[<?php echo $currRow; ?>][chk]" value="<?php echo $currRow; ?>" onClick="pickThis(this,<?php echo $currRow; ?>)">
	                                                        <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       					</td>
	                                                    <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
	                                                    <td width="9%" style="text-align:left" nowrap>
	                                                        <?php print $ITM_CODE; ?>
	                                                        <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_TYPE" name="dataRM[<?php echo $currRow; ?>][ITM_TYPE]" value="IN" width="10" size="15">
	                                                        <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_CODE" name="dataRM[<?php echo $currRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
	                                                        <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_GROUP" name="dataRM[<?php echo $currRow; ?>][ITM_GROUP]" value="<?php print $ITM_GROUP; ?>" width="10" size="15">
	                                                    </td>
	                                                    <!-- ITM_NAME -->
	                                                    <td width="65%" style="text-align:left">
	                                                        <?php echo $ITM_NAME; ?>
	                                                        <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_NAME" name="dataRM[<?php echo $currRow; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>">
	                                                    </td>
	                                                    <!-- ITM_UNIT -->  
	                                                    <td width="4%" style="text-align:center" nowrap>
	                                                        <?php echo $ITM_UNIT; ?>
	                                                        <input type="hidden" id="dataRM<?php echo $currRow; ?>ITM_NAME" name="dataRM[<?php echo $currRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
	                                                    </td>
	                                                    <!-- STF_VOLM, STF_PRICE -->
	                                                    <td width="18%" style="text-align:right" nowrap>
	                                                        <?php if($STF_STAT == 1 || $STF_STAT == 4) { ?>
	                                                            <input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM<?php echo $currRow; ?>" id="IN<?php echo $currRow; ?>STF_VOLM" value="<?php echo number_format($STF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, <?php echo $currRow; ?>);" >
	                                                        <?php } else { ?>
	                                                            <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM<?php echo $currRow; ?>" id="IN<?php echo $currRow; ?>STF_VOLM" value="<?php echo number_format($STF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLMRM(this, <?php echo $currRow; ?>);" >
	                                                            <?php echo number_format($STF_VOLM, 2);
	                                                        } ?>
	                                                        <input style="text-align:right" type="hidden" name="dataRM[<?php echo $currRow; ?>][STF_VOLM]" id="dataRM<?php echo $currRow; ?>STF_VOLM" value="<?php echo $STF_VOLM; ?>">
	                                                        <input type="hidden" style="text-align:right" name="dataRM[<?php echo $currRow; ?>][STF_PRICE]" id="dataRM<?php echo $currRow; ?>STF_PRICE" size="6" value="<?php echo $STF_PRICE; ?>">
	                                                        <input type="hidden" style="text-align:right" name="dataRM[<?php echo $currRow; ?>][ACC_ID]" id="dataRM<?php echo $currRow; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
	                                                        <input type="hidden" style="text-align:right" name="dataRM[<?php echo $currRow; ?>][ACC_ID_UM]" id="dataRM<?php echo $currRow; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
	                                                    </td>
	                                                </tr>
	                                                <?php
	                                            endforeach;
	                                        }
	                                    ?>
	                                    <input type="hidden" name="totalrowRM" id="totalrowRM" value="<?php echo $currRow; ?>">
	                                </table>
			                    <!-- END : RM NEEDED -->
				            </div>
			            </div>
			        </div>
					<div class="col-md-6">
						<div class="box box-success">
							<div class="box-header with-border">
								<i class="fa fa-cube"></i>
								<h3 class="box-title"><?php echo $Product; ?></h3>
							</div>
							<div class="box-body">
			                    <!-- START : OUTPUT ITEM -->
	                                <table id="tbl" class="table table-bordered table-striped" width="100%">
	                                    <tr style="background:#CCCCCC">
	                                        <th width="4%" height="25" style="text-align:center">No.</th>
	                                        <th width="9%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
	                                        <th width="68%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
	                                        <th width="3%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
	                                        <th width="16%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
	                                    </tr>
	                                    <?php
	                                        $resOUTC	= 0;
	                                        if($task == 'edit')
	                                        {																
	                                            $sqlOUT		= "SELECT A.ITM_TYPE, A.ITM_CODE, A.ITM_GROUP, B.ITM_NAME, B.ITM_UNIT, 
	                                                                A.STF_VOLM, A.STF_PRICE, A.STF_TOTAL, A.ACC_ID, A.ACC_ID_UM
	                                                            FROM tbl_stf_detail A
	                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                                    AND B.PRJCODE = '$PRJCODE'
	                                                            WHERE A.STF_NUM = '$STF_NUM'
	                                                                AND A.ITM_TYPE = 'OUT'";
	                                            $resOUT 	= $this->db->query($sqlOUT)->result();
	                                            
	                                            $sqlOUTC	= "tbl_stf_detail A
	                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
	                                                                    AND B.PRJCODE = '$PRJCODE'
	                                                            WHERE A.STF_NUM = '$STF_NUM'
	                                                                AND A.ITM_TYPE = 'OUT'";
	                                            $resOUTC 	= $this->db->count_all($sqlOUTC);
	                                        }
	                                            
	                                        $i			= 0;
	                                        $j			= 0;
	                                        if($resOUTC > 0)
	                                        {
	                                            foreach($resOUT as $rowOUT) :
	                                                $currRow  		= ++$i;																
	                                                $STF_NUM 		= $STF_NUM;
	                                                $STF_CODE 		= $STF_CODE;
	                                                $PRJCODE		= $PRJCODE;
	                                                $ITM_TYPE		= $rowOUT->ITM_TYPE;
	                                                $ITM_CODE		= $rowOUT->ITM_CODE;
	                                                $ITM_GROUP		= $rowOUT->ITM_GROUP;
	                                                $ITM_NAME 		= $rowOUT->ITM_NAME;
	                                                $ITM_UNIT 		= $rowOUT->ITM_UNIT;
	                                                $STF_VOLM 		= $rowOUT->STF_VOLM;
	                                                $STF_PRICE 		= $rowOUT->STF_PRICE;
	                                                $STF_TOTAL 		= $rowOUT->STF_TOTAL;
	                                                $ACC_ID 		= $rowOUT->ACC_ID;
	                                                $ACC_ID_UM 		= $rowOUT->ACC_ID_UM;
	                                                
	                                                // CEK STOCK PER WH
	                                                    $ITM_STOCK	= 0;
	                                                    $sqlWHSTOCK	= "SELECT ITM_VOLM AS ITM_STOCK FROM tbl_item_whqty
	                                                                    WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
	                                                                        AND WH_CODE = '$WH_CODE'";
	                                                    $resWHSTOCK	= $this->db->query($sqlWHSTOCK)->result();
	                                                    foreach($resWHSTOCK as $rowSTOCK) :
	                                                        $ITM_STOCK	= $rowSTOCK->ITM_STOCK;
	                                                    endforeach;
	                                    
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
	                                                    <td width="4%" height="25" style="text-align:center" nowrap>
	                                                        <?php
	                                                            if($STF_STAT == 1)
	                                                            {
	                                                                ?>
	                                                                    <a href="#" onClick="deleteRow(<?php echo $currRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
	                                                                <?php
	                                                            }
	                                                            else
	                                                            {
	                                                                echo "$currRow.";
	                                                            }
	                                                        ?>
	                                                        <input style="display:none" type="Checkbox" id="data[<?php echo $currRow; ?>][chk]" name="data[<?php echo $currRow; ?>][chk]" value="<?php echo $currRow; ?>" onClick="pickThis(this,<?php echo $currRow; ?>)">
	                                                        <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >                                       					</td>
	                                                    <!-- ITM_CODE, ITM_TYPE, ITM_GROUP -->
	                                                    <td width="9%" style="text-align:left" nowrap>
	                                                        <?php print $ITM_CODE; ?>
	                                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_TYPE" name="data[<?php echo $currRow; ?>][ITM_TYPE]" value="OUT" width="10" size="15">
	                                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_CODE" name="data[<?php echo $currRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
	                                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_GROUP" name="data[<?php echo $currRow; ?>][ITM_GROUP]" value="<?php print $ITM_GROUP; ?>" width="10" size="15">
	                                                    </td>
	                                                    <!-- ITM_NAME -->
	                                                    <td width="68%" style="text-align:left">
	                                                        <?php echo $ITM_NAME; ?>
	                                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_NAME" name="data[<?php echo $currRow; ?>][ITM_NAME]" value="<?php print $ITM_NAME; ?>">
	                                                    </td>
	                                                    <!-- ITM_UNIT -->  
	                                                    <td width="3%" style="text-align:center" nowrap>
	                                                        <?php echo $ITM_UNIT; ?>
	                                                        <input type="hidden" id="data<?php echo $currRow; ?>ITM_NAME" name="data[<?php echo $currRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
	                                                    </td>
	                                                    <!-- STF_VOLM, STF_PRICE -->
	                                                    <td width="16%" style="text-align:right" nowrap>
	                                                        <?php if($STF_STAT == 1 || $STF_STAT == 4) { ?>
	                                                            <input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM<?php echo $currRow; ?>" id="OUT<?php echo $currRow; ?>STF_VOLM" value="<?php echo number_format($STF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLM(this, <?php echo $currRow; ?>);" >
	                                                        <?php } else { ?>
	                                                            <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM<?php echo $currRow; ?>" id="OUT<?php echo $currRow; ?>STF_VOLM" value="<?php echo number_format($STF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="cVOLM(this, <?php echo $currRow; ?>);" >
	                                                            <?php echo number_format($STF_VOLM, 2);
	                                                        } ?>
	                                                        <input style="text-align:right" type="hidden" name="data[<?php echo $currRow; ?>][STF_VOLM]" id="data<?php echo $currRow; ?>STF_VOLM" value="<?php echo $STF_VOLM; ?>">
	                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currRow; ?>][STF_PRICE]" id="data<?php echo $currRow; ?>STF_PRICE" size="6" value="<?php echo $STF_PRICE; ?>">
	                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currRow; ?>][ACC_ID]" id="data<?php echo $currRow; ?>ACC_ID" size="6" value="<?php echo $ACC_ID; ?>">
	                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currRow; ?>][ACC_ID_UM]" id="data<?php echo $currRow; ?>ACC_ID_UM" size="6" value="<?php echo $ACC_ID_UM; ?>">
	                                                    </td>
	                                                </tr>
	                                                <?php
	                                            endforeach;
	                                        }
	                                    ?>
	                                    <input type="hidden" name="totalrowOUT" id="totalrowOUT" value="<?php echo $currRow; ?>">
	                                </table>
			                    <!-- END : OUTPUT ITEM -->
				            </div>
			            </div>
			        </div>
                    <div class="col-md-12">
                        <?php
                            if($task=='add')
                            {
                                if(($STF_STAT == 1 || $STF_STAT == 4) && $ISCREATE == 1)
                                {
                                    ?>
                                        <button class="btn btn-primary">
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                        </button>&nbsp;
                                    <?php
                                }
                            }
                            else
                            {
                                if(($STF_STAT == 1 || $STF_STAT == 4) && $ISCREATE == 1)
                                {
                                    ?>
                                        <button class="btn btn-primary" >
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                        </button>&nbsp;
                                    <?php
                                }
                            }
                            $backURL	= site_url('c_production/c_pR04uctpr0535/glpR04uctpr0535/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                        ?>
                    </div>
	            </form>
	        </div>
		</section>
	</body>
</html>

<?php
	if($LangID == 'IND')
	{
		$qtyDetOUT	= 'Material keluaran tidak boleh kosong.';
		$qtyDetRM	= 'Detail bahan yang digunakan tidak boleh kosong.';
		$volmAlert	= 'Qty order tidak boleh nol.';
	}
	else
	{
		$qtyDetOUT	= 'Output material can not be empty.';
		$qtyDetRM	= 'The details of the material used cannot be empty.';
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
  
	var decFormat		= 2;
	
	function add_header(strItem) 
	{
		arrItem = strItem.split('|');
		JO_NUM	= arrItem[0];
		JO_CODE	= arrItem[1];
		
		$(document).ready(function(e) {
			$("#JO_NUM").val(JO_NUM);
            $("#JO_CODE").val(JO_CODE);
            $("#JO_CODE1").val(JO_CODE);
        });
	}
	
	function add_item(strItem) 
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
		STF_VOLM 		= arrItem[5];
		REM_QTY			= arrItem[6];
		STF_PRICE 		= arrItem[7];
		ACC_ID 			= arrItem[8];
		ACC_ID_UM 		= arrItem[9];
		
		objTable 		= document.getElementById('tbl_OUT');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_TYPE" name="data['+intIndex+'][ITM_TYPE]" value="OUT" width="10" size="15"><input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_NAME" name="data['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// STF_VOLM, STF_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM'+intIndex+'" id="OUT'+intIndex+'STF_VOLM" value="0.00" onBlur="cVOLM(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="data['+intIndex+'][STF_VOLM]" id="data'+intIndex+'STF_VOLM" value="0.00"><input type="hidden" style="text-align:right" name="data['+intIndex+'][STF_PRICE]" id="data'+intIndex+'STF_PRICE" size="6" value="'+STF_PRICE+'"><input type="hidden" style="text-align:right" name="data['+intIndex+'][ACC_ID]" id="data'+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="data['+intIndex+'][ACC_ID_UM]" id="data'+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		
		document.getElementById('totalrowOUT').value = intIndex;
	}
	
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
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		ITM_GROUP 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		STOCK_PRJ 		= arrItem[5];	// STOCK PER PROJECT
		REM_QTY			= arrItem[6];
		STF_PRICE 		= arrItem[7];
		ACC_ID 			= arrItem[8];
		ACC_ID_UM 		= arrItem[9];
		STOCK_WH		= arrItem[10];	// STOCK PER WH
		
		ITM_STOCK		= parseFloat(STOCK_PRJ);
		
		objTable 		= document.getElementById('tbl_IN');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="dataRM['+intIndex+'][chk]" name="dataRM['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" >';
		
		// ITM_CODE, ITM_TYPE, ITM_GROUP
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="dataRM'+intIndex+'ITM_TYPE" name="dataRM['+intIndex+'][ITM_TYPE]" value="IN" width="10" size="15"><input type="hidden" id="dataRM'+intIndex+'ITM_CODE" name="dataRM['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15"><input type="hidden" id="dataRM'+intIndex+'ITM_GROUP" name="dataRM['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" width="10" size="15">';
		
		// ITM_NAME
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="dataRM'+intIndex+'ITM_NAME" name="dataRM['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'">';
				
		// ITM_UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataRM'+intIndex+'ITM_UNIT" name="dataRM['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// STF_VOLM, STF_PRICE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="STF_VOLM'+intIndex+'" id="IN'+intIndex+'STF_VOLM" value="0.00" onBlur="cVOLMRM(this, '+intIndex+');" ><input style="text-align:right" type="hidden" name="dataRM['+intIndex+'][STF_VOLM]" id="dataRM'+intIndex+'STF_VOLM" value="0.00"><input style="text-align:right" type="hidden" name="dataRM'+intIndex+'ITM_STOCK" id="dataRM'+intIndex+'ITM_STOCK" value="'+ITM_STOCK+'"><input type="hidden" style="text-align:right" name="dataRM['+intIndex+'][STF_PRICE]" id="dataRM'+intIndex+'STF_PRICE" size="6" value="'+STF_PRICE+'"><input type="hidden" style="text-align:right" name="dataRM['+intIndex+'][ACC_ID]" id="dataRM'+intIndex+'ACC_ID" size="6" value="'+ACC_ID+'"><input type="hidden" style="text-align:right" name="dataRM['+intIndex+'][ACC_ID_UM]" id="dataRM'+intIndex+'ACC_ID_UM" size="6" value="'+ACC_ID_UM+'">';
		
		document.getElementById('totalrowRM').value = intIndex;
	}
	
	function cVOLM(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		STF_VOLM1		= document.getElementById('OUT'+row+'STF_VOLM');
		STF_VOLM 		= parseFloat(eval(STF_VOLM1).value.split(",").join(""));
		
		document.getElementById('data'+row+'STF_VOLM').value 	= parseFloat(Math.abs(STF_VOLM));
		document.getElementById('OUT'+row+'STF_VOLM').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(STF_VOLM)),decFormat));
	}
	
	function cVOLMRM(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		ITM_STOCK		= parseFloat(document.getElementById('dataRM'+row+'ITM_STOCK').value);
		STF_VOLM1		= document.getElementById('IN'+row+'STF_VOLM');
		STF_VOLM 		= parseFloat(eval(STF_VOLM1).value.split(",").join(""));
		
		if(STF_VOLM > ITM_STOCK)
		{
			swal('<?php echo $alert7; ?>');
			document.getElementById('IN'+row+'STF_VOLM').focus();
			STF_VOLM	= parseFloat(ITM_STOCK);
		}
		
		document.getElementById('dataRM'+row+'STF_VOLM').value 	= parseFloat(Math.abs(STF_VOLM));
		document.getElementById('IN'+row+'STF_VOLM').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(STF_VOLM)),decFormat));
	}
	
	function checkInp()
	{
		totalrowOUT	= document.getElementById('totalrowOUT').value;	
		totalrowRM	= document.getElementById('totalrowRM').value;		
		var JO_NUM	= $("#JO_NUM").val();
		if(JO_NUM == '')
		{
			swal('<?php echo $alert4; ?>');
			$("#STF_FROM").val(0);
			$("#JO_CODE1").focus();
			return false;
		}
		
		if(totalrowOUT == 0)
		{
			swal('<?php echo $qtyDetOUT; ?>');
			return false;
		}
		else
		{
			for(i=1;i<=totalrowOUT;i++)
			{
				var STF_VOLM = parseFloat(document.getElementById('OUT'+i+'STF_VOLM').value);
				var ITM_NAME = document.getElementById('data'+i+'ITM_NAME').value;
				if(STF_VOLM == 0)
				{
					swal('<?php echo $alert1; ?>'+ITM_NAME+'<?php echo $alert2; ?>');
					document.getElementById('OUT'+i+'STF_VOLM').focus();
					return false;	
				}
			}
		}
		
		if(totalrowRM == 0)
		{
			swal('<?php echo $qtyDetRM; ?>');
			return false;
		}
		else
		{
			for(i=1;i<=totalrowRM;i++)
			{
				var STF_VOLM = parseFloat(document.getElementById('IN'+i+'STF_VOLM').value);
				var ITM_NAME = document.getElementById('dataRM'+i+'ITM_NAME').value;
				if(STF_VOLM == 0)
				{
					swal('<?php echo $alert1; ?>'+ITM_NAME+'<?php echo $alert2; ?>');
					document.getElementById('IN'+i+'STF_VOLM').focus();
					return false;	
				}
			}
		}
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