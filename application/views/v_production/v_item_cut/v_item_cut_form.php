<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 12 Oktober 2019
	* File Name	= v_item_cut_form.php
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

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
	
	$ICUT_NUM	= '';
	$ICUT_CODE 	= '';
	$CUST_CODE	= '';
	$ICUT_QRCN 	= '';
	$ICUT_STAT 	= 1;
	
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
		$Pattern_Code 			= "CG";
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

	$this->db->where('PRJCODE', $PRJCODE);
	$myCount = $this->db->count_all('tbl_item_cuth');
	
	$myMax 	= $myCount+1;
	
	$sql 		= "tbl_item_cuth WHERE PRJCODE = '$PRJCODE'";
	$result 	= $this->db->count_all($sql);
	$myMax 		= $result+1;
			
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
	
	$PATTCODE1		= $lastPatternNumb;
	$PATTCODE2		= date('y');
	$PATTCODE3		= date('m');
	$ICUT_NUM		= '';
	$CUST_SJNO 		= '';
	$ICUT_NOTES		= '';
	$ICUT_QTY		= 0;
	$ICUT_QRCN		= '';
	$REM_QTY		= 0;
	$ICUT_CODE		= "$Pattern_Code.$PATTCODE2.$PATTCODE3.$PATTCODE1";
}
else
{	
	$ICUT_NUM 		= $default['ICUT_NUM'];
	$ICUT_CODE 		= $default['ICUT_CODE'];
	$PRJCODE 		= $default['PRJCODE'];
	$PRJCODE		= $default['PRJCODE'];
	$PRJCODE_HO 	= $default['PRJCODE_HO'];
	$ICUT_NOTES		= $default['ICUT_NOTES'];
	$CUST_SJNO		= $default['CUST_SJNO'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$CUST_DESC 		= $default['CUST_DESC'];
	$ICUT_QRCN 		= $default['ICUT_QRCN'];
	$ICUT_REFNUM	= $default['ICUT_REFNUM'];
	$ICUT_QTY		= $default['ICUT_QTY'];
	$ICUT_STAT 		= $default['ICUT_STAT'];
}

if(isset($_POST['QRC_NUMX']))
{
	$ICUT_QRCN	= $_POST['QRC_NUMX'];
	$CUST_SJNO	= $_POST['CUST_SJNOX'];
}

// QTY QRCODE
$QRC_CODEV		= '';
$BUDG_QTY		= 0;
$ITM_UNIT		= '';
$sqlQTY			= "SELECT A.QRC_CODEV, A.ITM_QTY AS BUDG_QTY, A.ITM_UNIT FROM tbl_qrc_detail A WHERE A.QRC_NUM = '$ICUT_QRCN'";
$resQTY			= $this->db->query($sqlQTY)->result();
foreach ($resQTY as $keyQTY)
{
	$QRC_CODEV	= $keyQTY->QRC_CODEV;
	$BUDG_QTY	= $keyQTY->BUDG_QTY;
	$ITM_UNIT	= $keyQTY->ITM_UNIT;
}

// GET ORDERED QTY
$TOT_ORD		= 0;
$sqlORD			= "SELECT SUM(ICUT_QTY) TOT_ORD FROM tbl_item_cuth
					WHERE PRJCODE = '$PRJCODE' AND ICUT_QRCN = '$ICUT_QRCN' AND ICUT_NUM != '$ICUT_NUM'";
$resORD			= $this->db->query($sqlORD)->result();
foreach ($resORD as $key)
{
	$TOT_ORD	= $key->TOT_ORD;
}
$REM_QTY		= $BUDG_QTY - $TOT_ORD;

if($task == 'add')
{
	//$ICUT_QTY 	= 0.06 * $BUDG_QTY;
	$ICUT_QTY 	= 0 * $BUDG_QTY;
	if($ICUT_QTY > $BUDG_QTY)
		$ICUT_QTY = $REM_QTY;
}

$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();

foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;

$disRow	= 1;
if($ICUT_STAT == 1 || $ICUT_STAT == 4)
{
	$disRow	= 0;
}
?>
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
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'ColorName')$ColorName = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Material')$Material = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'FinGoods')$FinGoods = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Complete')$Complete = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
			if($TranslCode == 'Created')$Created = $LangTransl;
			if($TranslCode == 'doNo')$doNo = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert0		= 'Catatan tidak boleh kosong.';
			$alert1		= 'Surat Jalan tidak boleh kosong.';
			$alert2		= 'Greige-RIB tidak boleh kosong.';
			$alert3		= 'Jumlah pemecahan greige tidak boleh kosong.';
			$alert4		= 'Sisa qty lebih kecil dari yang diinputkan.';
			$alert5		= 'Silahkan tentukan terlebih dahulu Surat Jalan.';
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert0		= 'Notes can not be empty.';
			$alert1		= 'Delivery Order can not be empty.';
			$alert2		= 'Greige-RIB can not be empty.';
			$alert3		= 'Qty split of Greige can not be empty';
			$alert4		= 'Remain qty is less then qty inputed.';
			$alert5		= 'Please select a Delivery Order Number.';
			$isManual	= "Check to manual code.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bom.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">	
		    <div class="row">
		        <div class="col-md-12">
		            <div class="box box-primary">
		    			<div class="box-body chart-responsive">
			                <div class="box-header with-border" style="display:none">               
			              		<div class="box-tools pull-right">
			                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			                        </button>
			                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			                    </div>
			                </div>
			                <div class="box-body chart-responsive">
			                	<form class="form-horizontal" name="frmsrch1" method="post" action="" style="display:none">
			                    	<input type="text" name="QRC_NUMX" id="QRC_NUMX" value="<?php echo $ICUT_QRCN; ?>">
			                    	<input type="text" name="CUST_SJNOX" id="CUST_SJNOX" value="<?php echo $CUST_SJNO; ?>">
			                        <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
			                    </form>
			                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" class="form-control" style="max-width:195px" name="ICUT_NUM1" id="ICUT_NUM1" value="<?php echo $ICUT_NUM; ?>" disabled >
			                       			<input type="hidden" class="textbox" name="ICUT_NUM" id="ICUT_NUM" size="30" value="<?php echo $ICUT_NUM; ?>" />
			                       			<input type="hidden" class="textbox" name="ICUT_CODE" id="ICUT_CODE" size="30" value="<?php echo $ICUT_CODE; ?>" />
			                                <input type="text" class="form-control" name="ICUT_CODE1" id="ICUT_CODE1" value="<?php echo $ICUT_CODE; ?>" disabled >
			                          	</div>
			                        </div>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
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
			                        <div class="form-group" style="display: none;">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $CustName ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="CUST_CODE" id="CUST_CODE" class="form-control select2">
			                                	<option value="none" >--- None ---</option>
			                                    <?php
			                                    $i = 0;
			                                    if($countCUST > 0)
			                                    {
			                                        foreach($vwCUST as $row) :
			                                            $CUST_CODE1	= $row->CUST_CODE;
			                                            $CUST_DESC1	= $row->CUST_DESC;
			                                            ?>
			                                                <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
			                                            <?php
			                                        endforeach;
			                                    }
			                                    ?>
			                                </select>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $doNo ?></label>
			                          	<div class="col-sm-5">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary">SJ</button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="CUST_SJNO" id="CUST_SJNO" style="max-width:160px" value="<?php echo $CUST_SJNO; ?>" >
			                                    <input type="text" class="form-control" name="CUST_SJNO1" id="CUST_SJNO1" value="<?php echo $CUST_SJNO; ?>" onClick="getSJNo();" <?php if($ICUT_STAT != 1 && $ICUT_STAT != 4) { ?> disabled <?php } ?>>
			                                </div>
			                            </div>
			                          	<div class="col-sm-5">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-warning">RIB</button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="ICUT_QRCN" id="ICUT_QRCN" style="max-width:160px" value="<?php echo $ICUT_QRCN; ?>" >
			                                    <input type="text" class="form-control" name="ICUT_QRCN1" id="ICUT_QRCN1" value="<?php echo $QRC_CODEV; ?>" onClick="pleaseCheck();" <?php if($ICUT_STAT != 1 && $ICUT_STAT != 4) { ?> disabled <?php } ?>>
			                                </div>
			                            </div>
			                        </div>
									<?php
										$selSJ	= site_url('c_production/c_b0fm47/s3l4ll5J/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
										$selFG	= site_url('c_production/c_b0fm47/s3l4llR1B/?id='.$this->url_encryption_helper->encode_url($PRJCODE)); 
			                        ?>
			                        <script>
										var url0 = "<?php echo $selSJ;?>";
										var url1 = "<?php echo $selFG;?>";
										function getSJNo()
										{
											title = 'Select Item';
											w = 1000;
											h = 550;
											//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											return window.open(url0, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}

										function pleaseCheck()
										{
											var sJc4	= document.getElementById('CUST_SJNO').value;
											if(sJc4 == '')
											{
												swal('<?php echo $alert5; ?>',
												{
													icon: "warning",
												})
												.then(function()
												{
													document.getElementById('CUST_SJNO1').focus();
												});
												return false;
											}

											title = 'Select Item';
											w = 1000;
											h = 550;
											//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											return window.open(url1+'&sJc4='+sJc4, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
									</script>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?> </label>
			                          	<div class="col-sm-10">
			                                <?php if($disRow == 0) { ?>
			                                	<input type="text" class="form-control" name="ICUT_NOTES" id="ICUT_NOTES" value="<?php echo $ICUT_NOTES; ?>" placeholder="<?php echo $Notes; ?>">
			                            	<?php } else { ?>
				                                <input type="text" class="form-control" name="ICUT_NOTES1" id="ICUT_NOTES1" value="<?php echo $ICUT_NOTES; ?>" placehodler="<?php echo $Notes; ?>" disabled>
				                                <input type="hidden" class="form-control" name="ICUT_NOTES" id="ICUT_NOTES" value="<?php echo $ICUT_NOTES; ?>">
			                            	<?php } ?>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "$Remain ($ITM_UNIT)"; ?></label>
			                          	<div class="col-sm-10">
			                                <input type="hidden" class="form-control" name="REM_QTY" id="REM_QTY" value="<?php echo $REM_QTY; ?>" >
			                                <input type="text" class="form-control" name="REM_QTY1" id="REM_QTY1" value="<?php echo number_format($REM_QTY, 2); ?>" disabled >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo "Qty ($ITM_UNIT)"; ?></label>
			                          	<div class="col-sm-10">
			                                <input type="hidden" class="form-control" name="ICUT_QTY" id="ICUT_QTY" value="<?php echo $ICUT_QTY; ?>" >
			                                <input type="text" class="form-control" name="ICUT_QTY1" id="ICUT_QTY1" value="<?php echo number_format($ICUT_QTY, 2); ?>" onBlur="chgQTY(this);" onKeyPress="return isIntOnlyNew(event);" <?php if($disRow == 1) { ?> disabled <?php } ?>>
			                          	</div>
			                        </div>
									<script>
										function chgQTY(thisVal)
										{
											var REM_QTY		= parseFloat(document.getElementById('REM_QTY').value);
											var ICUT_QTY 	= parseFloat(eval(thisVal).value.split(",").join(""));

											var REM_QTY2	= parseFloat(REM_QTY - ICUT_QTY);

											if(REM_QTY2 < 0)
											{
												swal('<?php echo $alert4; ?>',
												{
													icon:"warning",
												})
												.then(function()
												{
													$("#ICUT_QTY1").focus();
												});
												ICUT_QTY	= parseFloat(REM_QTY);
											}

											document.getElementById('ICUT_QTY').value 	  	= ICUT_QTY;
											document.getElementById('ICUT_QTY1').value 		= doDecimalFormat(RoundNDecimal(ICUT_QTY, 2));
										}
									</script>
			                        <div class="form-group" >
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $ICUT_STAT; ?>">
											<?php
			                                    // START : FOR ALL APPROVAL FUNCTION
			                                        $isDisabled	= 0;
			                                        if($ICUT_STAT == 1 || $ICUT_STAT == 4)
			                                        {
			                                            ?>
			                                                <select name="ICUT_STAT" id="ICUT_STAT" class="form-control select2" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
			                                                    <option value="1"<?php if($ICUT_STAT == 1) { ?> selected <?php } ?> >New</option>
			                                                    <option value="2"<?php if($ICUT_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
			                                                    <option value="3"<?php if($ICUT_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
			                                                    <option value="4"<?php if($ICUT_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                                    <option value="5"<?php if($ICUT_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                                    <option value="6"<?php if($ICUT_STAT == 6) { ?> selected <?php } ?> disabled>Closed</option>
			                                                    <option value="7"<?php if($ICUT_STAT == 7) { ?> selected <?php } ?> style="display:none" >Waiting</option>
			                                                </select>
			                                            <?php
			                                        }
			                                        else
			                                        {
				                                        if($ISAPPROVE == 1)
				                                        {
				                                            if(($ICUT_STAT == 3 || $ICUT_STAT == 6 || $ICUT_STAT == 7) && ($task == "add"))
				                                            {
				                                                $isDisabled	= 1;
				                                            }
				                                            ?>
				                                                <select name="ICUT_STAT" id="ICUT_STAT" class="form-control select2" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
				                                                    <option value="1"<?php if($ICUT_STAT == 1) { ?> selected <?php } ?> >New</option>
				                                                    <option value="2"<?php if($ICUT_STAT == 2) { ?> selected <?php } ?> >Confirm</option>
				                                                    <option value="3"<?php if($ICUT_STAT == 3) { ?> selected <?php } ?> >Approve</option>
				                                                    <option value="4"<?php if($ICUT_STAT == 4) { ?> selected <?php } ?> >Revising</option>
				                                                    <option value="5"<?php if($ICUT_STAT == 5) { ?> selected <?php } ?> >Rejected</option>
				                                                    <option value="6"<?php if($ICUT_STAT == 6) { ?> selected <?php } ?> >Closed</option>
				                                                    <option value="7"<?php if($ICUT_STAT == 7) { ?> selected <?php } ?> style="display:none" >Waiting</option>
				                                                </select>
				                                            <?php
				                                        }                            
				                                        elseif($ISCREATE == 1)
				                                        {
				                                            if($ICUT_STAT == 6 || $ICUT_STAT == 7)
				                                            {
				                                                $isDisabled	= 1;
				                                            }
				                                            ?>
				                                                <select name="ICUT_STAT" id="ICUT_STAT" class="form-control select2" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
				                                                    <option value="1"<?php if($ICUT_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                                    <option value="2"<?php if($ICUT_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                                    <option value="3"<?php if($ICUT_STAT == 3) { ?> selected <?php } ?>>Approve</option>
				                                                </select>
				                                            <?php
				                                        }
				                                    }
			                                    // END : FOR ALL APPROVAL FUNCTION
			                                ?>
			                            </div>
			                            </div>
			                        </div>
			                        <?php
										$url_Material	= site_url('c_production/c_b0fm47/s3l4llQRC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-primary">
			                               		<table id="tbl" class="table table-bordered table-striped" width="100%">
			                                        <tr style="background:#CCCCCC">
			                                            <th width="3%" height="25" style="text-align:center">No.</th>
			                                          	<th width="6%" style="text-align:center" nowrap><?php echo $Code; ?> </th>
			                                          	<th width="10%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
			                                          	<th width="40%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
			                                          	<th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
			                                            <th width="5%" style="text-align:center" nowrap>Qty </th>
			                                          	<th width="10%" style="text-align:center" nowrap><?php echo $CreatedBy; ?></th>
			                                          	<th width="10%" style="text-align:center" nowrap><?php echo $Created; ?></th>
			                                      	</tr>
													<?php															
			                                            $sqlDET		= "SELECT * FROM tbl_item_cuth WHERE PRJCODE = '$PRJCODE'
			                                            					AND ICUT_QRCN = '$ICUT_QRCN'";
			                                            $resDET 	= $this->db->query($sqlDET)->result();
			                                            // count data
			                                            $sqlDETC	= "tbl_item_cuth WHERE PRJCODE = '$PRJCODE' AND ICUT_QRCN = '$ICUT_QRCN'";
			                                            $resultC 	= $this->db->count_all($sqlDETC);
			                                            $i			= 0;
			                                            $j			= 0;
			                                            if($resultC > 0)
			                                            {
			                                                $GT_ITMPRICE	= 0;
			                                                foreach($resDET as $row) :
			                                                    $currentRow  	= ++$i;
			                                                    $ICUT_NUM 		= $row->ICUT_NUM;
			                                                    $ICUT_CODE 		= $row->ICUT_CODE;
			                                                    $ICUT_NOTES 	= $row->ICUT_NOTES;
			                                                    $ICUT_QRCN 		= $row->ICUT_QRCN;
			                                                    $ICUT_QTY 		= $row->ICUT_QTY;
			                                                    $ICUT_QTY 		= $row->ICUT_QTY;
			                                                    $CREATERNM 		= $row->CREATERNM;
			                                                    $ICUT_CREATED 	= $row->ICUT_CREATED;
			                                                    $JO_CODE 		= $row->JO_CODE;

			                                                    $QRCCODEV		= '';
			                                                    $ITMCODE 		= '';
			                                                    $ITMNAME 		= '';
			                                                    $ITMUNIT 		= '';
			                                                    $sqlQRC			= "SELECT QRC_CODEV, ITM_CODE, ITM_NAME, ITM_UNIT FROM tbl_qrc_detail 
			                                                    					WHERE QRC_NUM = '$ICUT_QRCN'";
																$resQRC			= $this->db->query($sqlQRC)->result();
																foreach ($resQRC as $keyQRC)
																{
																	$QRCCODEV	= $keyQRC->QRC_CODEV;
																	$ITMCODE	= $keyQRC->ITM_CODE;
																	$ITMNAME	= $keyQRC->ITM_NAME;
																	$ITMUNIT	= $keyQRC->ITM_UNIT;
																}
			                                                    ?>
			                                                    <tr>
				                                                    <td height="25" style="text-align:center"><?php echo "$currentRow."; ?></td>
				                                                    <td style="text-align:left" nowrap><?php print $QRCCODEV; ?></td>
				                                                    <td style="text-align:left" nowrap><?php echo $ITMCODE; ?></td> 
				                                                    <td><?php echo $ITMNAME; ?></td>
				                                                    <td style="text-align:center"><?php print $ITM_UNIT; ?></td>
				                                                    <td style="text-align:right"><?php print number_format($ICUT_QTY, 2); ?> </td>
				                                                    <td style="text-align:center"><?php print $CREATERNM; ?></td>
				                                                    <td style="text-align:center"><?php print $ICUT_CREATED; ?> </td>
				                                                </tr>
			                                                    <?php
			                                                endforeach;
			                                                ?>
			                                                	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                                                <?php
			                                            }
			                                            else
			                                            {
			                                                ?>
			                                                	<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                                                <?php
			                                            }
			                                        ?>
			                                	</table>
			                              	</div>
			                            </div>
			                        </div>
			                        <br>
			                        <div class="form-group">
			                            <div class="col-sm-offset-2 col-sm-10">
			                            	<?php
												if($task=='add')
												{
													if($ICUT_STAT == 1 && $ISCREATE == 1)
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
													//if($ICUT_STAT == 1 && $ISCREATE == 1)
													if($ICUT_STAT != 3 AND ($ISCREATE == 1 || $ISAPPROVE == 1))
													{
														?>
															<button class="btn btn-primary">
															<i class="fa fa-save"></i>
															</button>&nbsp;
														<?php
													}
												}
												$backURL	= site_url('c_production/c_b0fm47/glcU7_9R3193/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
											?>
			                            </div>
			                        </div>
			                    </form>
			                </div>
			        	</div>
		            </div>
		        </div>
		    </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
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
  
	var decFormat		= 2;
	
	function add_SJ(strItem) 
	{
		arrItem = strItem.split('|');
		
		CUST_SJNO 		= arrItem[0];

		document.getElementById("CUST_SJNO").value 	= CUST_SJNO;
		document.getElementById("CUST_SJNO1").value = CUST_SJNO;
	}
	
	function add_FG(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		var CUST_SJNO	= document.getElementById('CUST_SJNO').value;
		
		arrItem = strItem.split('|');
		
		PRJCODE 		= arrItem[0];
		QRC_NUM 		= arrItem[1];
		QRC_CODEV 		= arrItem[2];
		REM_QTY 		= arrItem[8];

		document.getElementById("QRC_NUMX").value 	= QRC_NUM;
		document.getElementById("CUST_SJNOX").value = CUST_SJNO;
		document.frmsrch1.submitSrch1.click();
	}
	
	function checkInp()
	{
		totRow	= document.getElementById('totalrow').value;

		CUST_SJNO1	= $("#CUST_SJNO1").val();
		if(CUST_SJNO1 == '')
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				$("#CUST_SJNO1").focus();
			});
			return false;
		}

		ICUT_QRCN	= $("#ICUT_QRCN1").val();
		if(ICUT_QRCN == '')
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				$("#ICUT_QRCN1").focus();
			});
			return false;
		}
		
		ICUT_NOTES	= $("#ICUT_NOTES").val();
		if(ICUT_NOTES == '')
		{
			swal('<?php echo $alert0; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				$("#ICUT_NOTES").focus();
			});
			return false;
		}

		var thisVal		= document.getElementById('ICUT_QTY1');
		var ICUT_QTY 	= parseFloat(eval(thisVal).value.split(",").join(""));
		if(ICUT_QTY == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				$("#ICUT_QTY1").focus();
			});
			return false;
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