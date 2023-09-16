<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Maret 2017
 * File Name	= itemreceipt_form_dir.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
	
	$year		= substr($year, 2, 4);
	$Patt_Year 	= (int)$Pattern_YearAktive;

	//$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_ir_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_ir_header
			WHERE Patt_Year = $Patt_Year AND PRJCODE = '$PRJCODE'";
	$result = $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax = $row->maxNumber;
			$myMax = $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}
	
	$thisMonth = $month;
	
	$lenMonth = strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth = $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$PRJCODE$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$PRJCODE$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$PRJCODE$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$PRJCODE$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
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
	
	$DocNumber		= "$Pattern_Code$groupPattern-$lastPatternNumb"."-D";
	
	$IR_NUM 		= $DocNumber;
	$IR_NUM_BEF		= '';
	$IR_CODE 		= $lastPatternNumb;
	
	$IRCODE			= substr($lastPatternNumb, -4);
	$IRYEAR			= date('y');
	$IRMONTH		= date('m');
	$IR_CODE		= "$Pattern_Code.$IRCODE.$IRYEAR.$IRMONTH-D"; // MANUAL CODE
	
	$IR_SOURCE		= 1;
	$IR_DATE		= date('m/d/Y');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$IR_REFER		= '';
	$PO_NUM			= '';
	$IR_AMOUNT		= 0;
	$APPROVE		= 0;
	$IR_STAT		= 0;
	$IR_NOTE		= '';
	$REVMEMO		= '';
	$WH_CODE		= '';
	$IR_STAT		= '';
	$Patt_Number	= $lastPatternNumb1;
	$ISDIRECT		= 1;
	$TERM_PAY		= 30;
	
	$dataColl 		= "$PRJCODE~$Pattern_Code~tbl_ir_header~$Pattern_Length";
	$dataTarget		= "IR_CODE";
}
else
{
	$isSetDocNo = 1;
	$IR_NUM 		= $default['IR_NUM'];
	$IR_NUM_BEF		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_SOURCE 		= $default['IR_SOURCE'];
	$IR_DATE 		= $default['IR_DATE'];
	$IR_DUEDATE		= $default['IR_DUEDATE'];
	$IR_DATE		= date('m/d/Y', strtotime($IR_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$IR_REFER 		= $default['IR_REFER'];
	$PO_NUM 		= $default['PO_NUM'];
	$IR_AMOUNT 		= $default['IR_AMOUNT'];
	$TERM_PAY 		= $default['TERM_PAY'];
	$TRXUSER 		= $default['TRXUSER'];
	$APPROVE 		= $default['APPROVE'];
	$IR_STAT 		= $default['IR_STAT'];
	$INVSTAT 		= $default['INVSTAT'];
	$IR_NOTE 		= $default['IR_NOTE'];
	$REVMEMO		= $default['REVMEMO'];
	$WH_CODE		= $default['WH_CODE'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Number	= $default['Patt_Number'];
	$ISDIRECT		= 1;
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

$sqlWH 		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE' ORDER BY WH_NAME";
$resWH		= $this->db->query($sqlWH)->result();

$sqlTAX		= "SELECT WH_CODE, WH_NAME
				FROM tbl_warehouse WHERE PRJCODE = '$PRJCODE' ORDER BY WH_NAME";
$resTAX		= $this->db->query($sqlTAX)->result();
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
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'ReceiptCode')$ReceiptCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
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
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
		endforeach;
		$secGenCode	= base_url().'index.php/c_inventory/c_ir180c15/genCode/'; // Generate Code
		if($LangID == 'IND')
		{
			$h1_title	= 'Terima Item';
			$h2_title	= 'Inventaris';
			$alert1		= 'Jumlah pemesanan tidak boleh kosong';
			$alert2		= 'Silahkan pilih nama supplier';
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$h1_title	= 'Item Receipt';
			$h2_title	= 'Inventory';
			$alert1		= 'Qty order can not be empty';
			$alert2		= 'Please select a supplier name';
			$isManual	= "Check to manual code.";
		}
	?>
    
    <body class="<?php echo $appBody; ?>">
        <div class="content-wrapper">
			<section class="content-header">
				<h1>
			    <?php echo $h2_title; ?>
			    <small><?php echo $h3_title; ?></small>  </h1>
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
			                                    <input type="hidden" name="ISDIRECT" id="ISDIRECTS" value="<?php echo $ISDIRECT; ?>">
			                                </td>
			                                <td><a class="tombol-date" id="dateClass">Simpan</a></td>
			                            </tr>
			                        </table>
			                    </form>
			                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
			                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
			                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $IR_STAT; ?>">
			                        <input type="hidden" name="IR_SOURCE" id="IR_SOURCE" value="<?php echo $ISDIRECT; ?>">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
									<?php if($isSetDocNo == 0) { ?>
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
			                        <?php } ?>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ReceiptCode ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_NUM; ?>" class="form-control" style="max-width:175px" disabled >
			                                <input type="hidden" name="Patt_Year" id="Patt_Year" value="<?php echo $Patt_Year; ?>">
			                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>">
			                                <input type="hidden" name="IR_NUM" id="IR_NUM" value="<?php echo $IR_NUM; ?>" >
			                                <input type="hidden" name="IR_NUM_BEF" id="IR_NUM_BEF" value="<?php echo $IR_NUM_BEF; ?>" >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode; ?> </label>
			                          	<div class="col-sm-10">
			                                <label>
			                                    <input type="text" class="form-control" style="min-width:width:200px; max-width:200px" name="IR_CODE" id="IR_CODE" value="<?php echo $IR_CODE; ?>" >
			                                </label>
			                                <label>
			                                    &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
			                                </label>
			                                <label style="font-style:italic">
			                                    <?php echo $isManual; ?>
			                                </label>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <?php
													if($task == 'add')
													{
														?>
			                                            <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px" onChange="getIR_NUM(this.value)">
			                                            <?php
													}
													else
													{
														?>
			                                            <input type="text" name="IR_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $IR_DATE; ?>" style="width:105px">
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
														document.getElementById('IR_NUM1').value 	= myarr[0];
														document.getElementById('IR_NUM').value 	= myarr[0];
														document.getElementById('IR_CODE').value 	= myarr[1];
													}
												});
											});
										});
									</script>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SourceDocument; ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE1" value="1" <?php if($IR_SOURCE == 1) { ?> checked <?php } ?>>
			                                &nbsp;&nbsp;Direct&nbsp;&nbsp;&nbsp;&nbsp;
			                                <?php /*?><input type="radio" name="IR_SOURCE" id="IR_SOURCE2" value="2" <?php if($IR_SOURCE == 2) { ?> checked <?php } ?> disabled>
			                                &nbsp;&nbsp;MR&nbsp;&nbsp;&nbsp;&nbsp;
			                                <input type="radio" name="IR_SOURCE" id="IR_SOURCE3" value="3" <?php if($IR_SOURCE == 3) { ?> checked <?php } ?>>
			                                &nbsp;&nbsp;PO<?php */?>
			                            </div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-10">
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName; ?> </label>
			                          	<div class="col-sm-10">
			                                <select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" style="max-width:350px;">
			                                    <?php
			                                    $i = 0;
			                                    if($countSUPL > 0)
			                                    {
			                                        foreach($vwSUPL as $row) :
			                                            $SPLCODE1	= $row->SPLCODE;
			                                            $SPLDESC1	= $row->SPLDESC;
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
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="0">--- No Vendor Found ---</option>
			                                        <?php
			                                    }
			                                    ?>
			                                </select>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $PaymentTerm; ?> </label>
			                          	<div class="col-sm-10">
			                                <select name="TERM_PAY" id="TERM_PAY" class="form-control" style="max-width:100px">
			                                    <option value="0" <?php if($TERM_PAY == 0) { ?> selected <?php } ?>>Cash</option>
			                                    <option value="7" <?php if($TERM_PAY == 7) { ?> selected <?php } ?>>7 Days</option>
			                                    <option value="15" <?php if($TERM_PAY == 15) { ?> selected <?php } ?>>15 Days</option>
			                                    <option value="30" <?php if($TERM_PAY == 30) { ?> selected <?php } ?>>30 Days</option>
			                                    <option value="45" <?php if($TERM_PAY == 45) { ?> selected <?php } ?>>45 Days</option>
			                                    <option value="60" <?php if($TERM_PAY == 60) { ?> selected <?php } ?>>60 Days</option>
			                                    <option value="75" <?php if($TERM_PAY == 75) { ?> selected <?php } ?>>75 Days</option>
			                                    <option value="90" <?php if($TERM_PAY == 90) { ?> selected <?php } ?>>90 Days</option>
			                                    <option value="120" <?php if($TERM_PAY == 120) { ?> selected <?php } ?>>120 Days</option>
			                                </select>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $WHLocation ?> </label>
			                          	<div class="col-sm-10">
			                                <select name="WH_CODE" id="WH_CODE" class="form-control" style="max-width:350px" >
			                                  	<?php
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
			                                  				<option value="none">--- No Project Found ---</option>
			                                  	<?php
			                                        }
			                                        ?>
			                                </select>
			                            </div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="IR_NOTE"  id="IR_NOTE" style="max-width:350px;height:70px"><?php echo $IR_NOTE; ?></textarea>
			                            </div>
			                        </div>
			                        <!--
			                        	APPROVE STATUS
			                            1 - New
			                            2 - Confirm
			                            3 - Approve
			                        -->
			                        <div class="form-group" >
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                          	<div class="col-sm-10">
			                                <select name="IR_STAT" id="IR_STAT" class="form-control" style="max-width:100px">
			                                	<option value="1" <?php if($IR_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                	<option value="2" <?php if($IR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                    <?php
													if($IR_STAT == 7) 
													{
														?>
															<option value="7"<?php if($IR_STAT == 7) { ?> selected <?php } ?>>Awaiting</option>
														<?php
													}
												?>
			                                    <?php
													if($IR_STAT == 3) 
													{
														?>
															<option value="3"<?php if($IR_STAT == 3) { ?> selected <?php } ?>>Approve</option>
														<?php
													}
												?>
			                                </select>
			                            </div>
			                        </div>
									<?php
										$url_AddItem	= site_url('c_inventory/c_ir180c15/pop180c22all/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			                        ?>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                          	<div class="col-sm-10">
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
			                                
			                                <button class="btn btn-success" type="button" onClick="selectitem();">
			                                    <i class="cus-add-item-16x16"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
			                                </button><br>
			                                </div>
			                        </div>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-primary">
			                                <br>
			                                    <table width="100%" border="1" id="tbl">
			                                        <tr style="background:#CCCCCC">
			                                            <th width="2%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
			                                          	<th width="7%" rowspan="2" style="text-align:center"><?php echo $ItemCode; ?> </th>
			                                          	<th width="21%" rowspan="2" style="text-align:center"><?php echo $ItemName; ?> </th>
			                                          	<th colspan="3" style="text-align:center"><?php echo $Receipt; ?> </th>
			                                            <th rowspan="2" style="text-align:center"><?php echo $Discount; ?></th>
			                                            <th rowspan="2" style="text-align:center"><?php echo $PPn; ?></th>
			                                            <th rowspan="2" style="text-align:center"><?php echo $Total; ?></th>
			                                            <th width="22%" rowspan="2" style="text-align:center"><?php echo $Remarks ?> </th>
			                                      	</tr>
			                                        <tr style="background:#CCCCCC">
			                                            <th style="text-align:center;"><?php echo $Quantity; ?> </th>
			                                            <th style="text-align:center;"><?php echo $Unit; ?> </th>
			                                            <th style="text-align:center"><?php echo $Price; ?> </th>
			                                        </tr>
			                                        <?php
			                                        if($task == 'edit')
			                                        {
			                                            $sqlDET		= "SELECT A.IR_NUM, A.PRJCODE, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.ITM_QTY,
																		A.ITM_UNIT, A.ITM_PRICE, A.NOTES,
																		A.TAXCODE1, A.TAXPRICE1, A.ITM_TOTAL, A.ACC_ID, A.ITM_DISP, A.ITM_DISC,
																		B.ITM_NAME
			                                                            FROM tbl_ir_detail A
			                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                            WHERE 
																		A.IR_NUM = '$IR_NUM' 
			                                                            AND B.PRJCODE = '$PRJCODE'";
			                                            $result = $this->db->query($sqlDET)->result();
			                                            // count data
														$sqlDETC	= "tbl_ir_detail A
			                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
			                                                            WHERE 
																		A.IR_NUM = '$IR_NUM' 
			                                                            AND B.PRJCODE = '$PRJCODE'";
														$resultC 	= $this->db->count_all($sqlDETC);
			                                            $i		= 0;
			                                            $j		= 0;
			                                            if($resultC > 0)
			                                            {
			                                                foreach($result as $row) :
			                                                    $currentRow  	= ++$i;
			                                                    $IR_NUM 		= $row->IR_NUM;
			                                                    $IR_CODE 		= $row->IR_CODE;
			                                                    $ITM_CODE 		= $row->ITM_CODE;
			                                                    $JOBCODEDET		= $row->JOBCODEDET;
			                                                    $JOBCODEID 		= $row->JOBCODEID;
			                                                    $ACC_ID 		= $row->ACC_ID;
			                                                    $ITM_NAME 		= $row->ITM_NAME;
			                                                    $PRJCODE		= $row->PRJCODE;
			                                                    $ITM_QTY 		= $row->ITM_QTY;
																$ITM_QTY_BONUS	= 0;
			                                                    $ITM_UNIT 		= $row->ITM_UNIT;
			                                                    $ITM_PRICE 		= $row->ITM_PRICE;
			                                                    $ITM_TOTAL 		= $row->ITM_TOTAL;
			                                                    $ITM_DISP 		= $row->ITM_DISP;
			                                                    $ITM_DISC 		= $row->ITM_DISC;
			                                                    $TAXCODE1		= $row->TAXCODE1;
			                                                    $TAXPRICE1		= $row->TAXPRICE1;
			                                                    $NOTES			= $row->NOTES;
			                                                    $itemConvertion	= 1;
																
																$ITM_TOTAL		= ($ITM_QTY * $ITM_PRICE) - $ITM_DISC;
																
																$GT_ITMPRICE	= $ITM_TOTAL;
																if($TAXCODE1 == 'TAX01')
																{
																	$GT_ITMPRICE= $ITM_TOTAL + $TAXPRICE1;
																}
																if($TAXCODE1 == 'TAX02')
																{
																	$GT_ITMPRICE= $ITM_TOTAL - $TAXPRICE1;
																}
			                                        
			                                                    if ($j==1) {
			                                                        echo "<tr class=zebra1>";
			                                                        $j++;
			                                                    } else {
			                                                        echo "<tr class=zebra2>";
			                                                        $j--;
			                                                    }
			                                                    ?> 
			                                                    <tr><td width="2%" height="25" style="text-align:left">
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
			                                                        <input type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onclick="pickThis(this,<?php echo $currentRow; ?>)" style="display:none">
			                                                        <input type="Checkbox" id="chk<?php echo $currentRow; ?>" name="chk<?php echo $currentRow; ?>" value="" style="display:none" ><input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                                                        <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_NUM" name="data[<?php echo $currentRow; ?>][IR_NUM]" value="<?php echo $IR_NUM; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>IR_CODE" name="data[<?php echo $currentRow; ?>][IR_CODE]" value="<?php echo $IR_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
			                                                    	<!-- Checkbox -->
			                                                    </td>
			                                               	  	<td width="7%" style="text-align:left" nowrap>
			                                                      	<?php echo $ITM_CODE; ?>
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID" name="data[<?php echo $currentRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15" readonly class="form-control">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEDET" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" value="<?php echo $JOBCODEDET; ?>" class="form-control">
			                                                        <input type="hidden" id="data<?php echo $currentRow; ?>JOBCODEID" name="data[<?php echo $currentRow; ?>][JOBCODEID]" value="<?php echo $JOBCODEID; ?>" class="form-control">
			                                                        <!-- Item Code -->
			                                                    </td>
			                                               	  	<td width="21%" style="text-align:left">
			                                                      	<?php echo $ITM_NAME; ?>
			                                                        <input type="hidden" class="form-control" name="itemname<?php echo $currentRow; ?>" id="itemname<?php echo $currentRow; ?>" value="<?php echo $ITM_NAME; ?>" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_NAME]" id="data<?php echo $currentRow; ?>ITM_NAME" size="10" value="<?php echo $ITM_NAME; ?>" >
																	<!-- Item Name -->                                                    </td>
			                                               	  	<td width="12%" style="text-align:right" nowrap>
			                                                        <input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY<?php echo $currentRow; ?>" id="ITM_QTY<?php echo $currentRow; ?>" value="<?php print number_format($ITM_QTY, $decFormat); ?>" onBlur="changeValue(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY]" id="data<?php echo $currentRow; ?>ITM_QTY" size="10" value="<?php echo $ITM_QTY; ?>" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_QTY_BONUS]" id="ITM_QTY_BONUS<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_QTY_BONUS; ?>" >
			                                                        <!-- Item Qty -->                                                    </td>
			                                               	  	<td width="3%" nowrap style="text-align:center">
			                                                      	<?php echo $ITM_UNIT; ?>
			                                                        <input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" id="ITM_UNIT<?php echo $currentRow; ?>" size="10" value="<?php echo $ITM_UNIT; ?>" >
			                                                      	<!-- Item Unit -->                                                    </td>
			                                               	  	<td width="12%" nowrap style="text-align:center">
			                                                    	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICE<?php echo $currentRow; ?>" id="ITM_PRICE<?php echo $currentRow; ?>" value="<?php print number_format($ITM_PRICE, $decFormat); ?>" onBlur="changeValuePrc(this, <?php echo $currentRow; ?>)" onKeyPress="return isIntOnlyNew(event);" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_PRICE]" id="data<?php echo $currentRow; ?>ITM_PRICE" size="10" value="<?php echo $ITM_PRICE; ?>" >
			                                                        <!-- Item Price -->                                                    </td>
			                                               	  	<td width="6%" nowrap style="text-align:center">
			                                                    	<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_DISC<?php echo $currentRow; ?>" id="ITM_DISC<?php echo $currentRow; ?>" value="<?php print number_format($ITM_DISC, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValueDisc(this, <?php echo $currentRow; ?>)" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISP]" id="data<?php echo $currentRow; ?>ITM_DISP" size="10" value="<?php echo $ITM_DISP; ?>" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_DISC]" id="data<?php echo $currentRow; ?>ITM_DISC" size="10" value="<?php echo $ITM_DISC; ?>" >
			                                                    </td>
			                                               	  	<td width="6%" nowrap style="text-align:center">
			                                                    	<select name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" class="form-control" style="min-width:100px; max-width:150px" onChange="changeValueTax(this, <?php echo $currentRow; ?>)">
			                                                        	<option value=""> --- no tax --- </option>
			                                                        	<option value="TAX01" <?php if($TAXCODE1 == 'TAX01') { ?> selected <?PHP } ?>>PPn 10% </option>
			                                                        	<option value="TAX02" <?php if($TAXCODE1 == 'TAX02') { ?> selected <?PHP } ?>>PPh 3%</option>
			                                                    	</select>
			                                                        <!-- Item Price Total PPn --></td>
			                                               	  	<td width="9%" nowrap style="text-align:center"> 
			                                                    	<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTAL<?php echo $currentRow; ?>" id="ITM_TOTAL<?php echo $currentRow; ?>" value="<?php print number_format($GT_ITMPRICE, $decFormat); ?>" >
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][ITM_TOTAL]" id="data<?php echo $currentRow; ?>ITM_TOTAL" value="<?php echo $GT_ITMPRICE; ?>" >
			                                                        <input type="hidden" style="min-width:130px; max-width:350px; text-align:right" name="GT_ITMPRICE<?php echo $currentRow; ?>" id="data<?php echo $currentRow; ?>GT_ITMPRICE" value="<?php echo $GT_ITMPRICE; ?>">
			                                                        <!-- Item Price Total -->
			                                                    </td>
			                                               	  	<td width="22%" style="text-align:center">
			                                           				<input type="text" name="data[<?php echo $currentRow; ?>][NOTES]" id="data<?php echo $currentRow; ?>NOTES" value="<?php echo $NOTES; ?>" class="form-control" style="max-width:350px;text-align:left">
			                                                        <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
			                                                        <!-- Notes -->													</td>
																<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
			                                              </tr>
			                                          <?php
			                                                endforeach;
			                                            }
			                                        }
			                                        if($task == 'add')
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
			                        <input type="hidden" name="IR_AMOUNT" id="IR_AMOUNT" value="<?php echo $IR_AMOUNT; ?>">
			                        <br>
			                        <div class="form-group">
			                            <div class="col-sm-offset-2 col-sm-10">
			                            	<?php
												$showBtn	= 0;
												if($IR_STAT == 2 || $IR_STAT == 3)
												{
													$showBtn	= 0;
												}
												else
												{
													$showBtn	= 1;
												}
												if($ISCREATE == 1 && $showBtn == 1)
												{
													if($task=='add')
													{
														?>
															<button class="btn btn-primary">
															<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
															</button>&nbsp;
														<?php
													}
													else
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
															</button>&nbsp;
														<?php
													}
												}
												echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;'.$Cancel.'</button>');
											?>
			                            </div>
			                        </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			    </div>
			</section>
		</div>
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

	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var IR_NUM 	= "<?php echo $IR_NUM; ?>";
		var IR_CODE	= "<?php echo $IR_CODE; ?>";
		var PRJCODE = "<?php echo $PRJCODE; ?>";
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
		JOBCODEDET		= arrItem[13];
		JOBCODEID		= arrItem[14];
		ITM_DISP		= 0;
		ITM_DISC		= 0;
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" style="display:none" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+IR_NUM+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'IR_CODE" name="data['+intIndex+'][IR_CODE]" value="'+IR_CODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">';	
		
		// Item Code
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+itemcode+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+itemcode+'" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+Acc_Id+'" width="10" size="15" readonly class="form-control"><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEDET+'" class="form-control"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control">';
		
		// Item Name
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+itemname+'<input type="hidden" class="form-control" name="itemname'+intIndex+'" id="itemname'+intIndex+'" value="'+itemname+'" >';
		
		// Item Qty
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="'+itemQty+'" onBlur="changeValue(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" size="10" value="'+itemQty+'" >';
		
		// Item Unit
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+itemUnitName+'<input type="hidden" class="form-control" style="max-width:350px; text-align:right" name="data['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" size="10" value="'+itemUnit+'" >';
		
		// Item Price
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+itemPrice+'" onBlur="changeValuePrc(this, '+intIndex+')" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_PRICE]" id="data'+intIndex+'ITM_PRICE" size="10" value="'+itemPrice+'" >';
		
		// Item Disc
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="ITM_DISC'+intIndex+'" id="ITM_DISC'+intIndex+'" value="'+ITM_DISC+'" onKeyPress="return isIntOnlyNew(event);" onBlur="changeValueDisc(this, '+intIndex+')" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_DISP]" id="data'+intIndex+'ITM_DISP" size="10" value="'+ITM_DISP+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_DISC]" id="data'+intIndex+'ITM_DISC" size="10" value="'+ITM_DISC+'" >';
		
		// Item Tax
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<select name="data['+intIndex+'][TAXCODE1]" id="data'+intIndex+'TAXCODE1" class="form-control" style="min-width:100px; max-width:150px" onChange="changeValueTax(this, '+intIndex+')"><option value=""> --- no tax --- </option><option value="TAX01">PPn 10%</option><option value="TAX02">PPh 3%</option></select>';
		
		// tem Total
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="ITM_TOTAL'+intIndex+'" id="ITM_TOTAL'+intIndex+'" value="'+ITM_TOTAL+'" ><input type="hidden" style="text-align:right" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="'+ITM_TOTAL+'" ><input type="hidden" style="min-width:130px; max-width:350px; text-align:right" name="GT_ITMPRICE'+intIndex+'" id="data'+intIndex+'GT_ITMPRICE" value="0">';
		
		// Notes
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][NOTES]" id="data'+intIndex+'NOTES" value="" class="form-control" style="max-width:350px;text-align:left"><input type="hidden" style="text-align:right" name="data['+intIndex+'][TAXPRICE1]" id="data'+intIndex+'TAXPRICE1" value="0">';
		
		var decFormat												= document.getElementById('decFormat').value;
		var ITM_QTY													= document.getElementById('ITM_QTY'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		
		var ITM_PRICE												= document.getElementById('ITM_PRICE'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICE'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		
		var ITM_TOTAL												= document.getElementById('ITM_TOTAL'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTAL'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
		
		var ITM_DISC												= document.getElementById('ITM_DISC'+intIndex).value
		document.getElementById('data'+intIndex+'ITM_DISC').value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISC'+intIndex).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		document.getElementById('totalrow').value 					= intIndex;
	}
	
	function changeValue(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_QTY 	= eval(thisVal).value.split(",").join("");
		var ITM_PRICE 	= document.getElementById('data'+theRow+'ITM_PRICE').value;
		document.getElementById('data'+theRow+'ITM_QTY').value 			= parseFloat(Math.abs(ITM_QTY));
		document.getElementById('ITM_QTY'+theRow).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
		var ITM_DISC			= document.getElementById('data'+theRow+'ITM_DISC').value;
		var ITM_QTY				= document.getElementById('data'+theRow+'ITM_QTY').value;
		var ITM_PRICE			= document.getElementById('data'+theRow+'ITM_PRICE').value;
		var ITM_TOTAL			= parseFloat(ITM_QTY) * parseFloat(ITM_PRICE);
		var DISCOUNT			= parseFloat(ITM_DISC);	
		var DISCOUNTP			= parseFloat(ITM_DISC / ITM_TOTAL * 100);		
		var TOT_ITMTEMP			= parseFloat(ITM_TOTAL - DISCOUNT);
		document.getElementById('data'+theRow+'ITM_DISP').value 	= parseFloat(Math.abs(DISCOUNTP));
		document.getElementById('data'+theRow+'ITM_DISC').value 	= parseFloat(Math.abs(DISCOUNT));
		document.getElementById('ITM_DISC'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DISCOUNT)),decFormat));
		
		var theTAX				= document.getElementById('data'+theRow+'TAXCODE1').value;
		if(theTAX == 'TAX01')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.1;
			G_itmTot	= parseFloat(TOT_ITMTEMP) + parseFloat(itmTax);
			document.getElementById('data'+theRow+'TAXPRICE1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+theRow+'GT_ITMPRICE').value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else if(theTAX == 'TAX02')
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0.03;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+theRow+'TAXPRICE1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+theRow+'GT_ITMPRICE').value = RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		else
		{
			itmTax		= parseFloat(TOT_ITMTEMP) * 0;
			G_itmTot	= parseFloat(TOT_ITMTEMP) - parseFloat(itmTax);
			document.getElementById('data'+theRow+'TAXPRICE1').value 	= RoundNDecimal(parseFloat(Math.abs(itmTax)),decFormat);
			document.getElementById('data'+theRow+'GT_ITMPRICE').value 	= RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat);
		}
		document.getElementById('data'+theRow+'ITM_TOTAL').value 		= parseFloat(Math.abs(G_itmTot));
		document.getElementById('ITM_TOTAL'+theRow).value 				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(G_itmTot)),decFormat));
		
		totalrow	= document.getElementById("totalrow").value;	
		IR_TOTAL_AM	= 0;	
		for(i=1; i<=totalrow; i++)
		{
			GT_ITMPRICE		= document.getElementById('data'+i+'GT_ITMPRICE').value;
			IR_TOTAL_AM		= parseFloat(IR_TOTAL_AM) + parseFloat(GT_ITMPRICE);
		}
		document.getElementById('IR_AMOUNT').value = IR_TOTAL_AM;
	}
	
	function changeValueDisc(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_DISC 	= eval(thisVal).value.split(",").join("");
		document.getElementById('data'+theRow+'ITM_DISC').value 	= parseFloat(Math.abs(ITM_DISC));
		document.getElementById('ITM_DISC'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_DISC)),decFormat));
		
		var thisVal		= document.getElementById('data'+theRow+'ITM_QTY');
		changeValue(thisVal, theRow);
	}
	
	function changeValueTax(thisVal, theRow)
	{
		var thisVal		= document.getElementById('ITM_QTY'+theRow);
		changeValue(thisVal, theRow);
	}
	
	function changeValuePrc(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICE 	= eval(thisVal).value.split(",").join("");
		document.getElementById('data'+theRow+'ITM_PRICE').value	= parseFloat(Math.abs(ITM_PRICE));
		document.getElementById('ITM_PRICE'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));
		var thisVal		= document.getElementById('ITM_QTY'+theRow);
		changeValue(thisVal, theRow);
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}	
	
	function checkInp()
	{
		totalrow	= document.getElementById("totalrow").value;
		SPLCODE		= document.getElementById("SPLCODE").value;
		
		if(SPLCODE == 0)
		{
			swal("<?php echo $alert2; ?>");
			document.getElementById('SPLCODE').focus();
			return false;
		}
		
		if(totalrow == 0)
		{
			swal("<?php echo $alert1; ?>");
			selectitem();
			return false;
		}
		
		for(i=1; i<=totalrow; i++)
		{
			ITM_QTY	= document.getElementById('data'+i+'ITM_QTY').value;
			ITM_NM	= document.getElementById('itemname'+i).value;
			if(ITM_QTY == 0)
			{
				swal('Item '+ ITM_NM +' qty can not be empty.');
				document.getElementById('ITM_QTY'+i).focus();
				return false;
			}
		}
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
    $this->load->view('template/aside');

    $this->load->view('template/js_data');

    $this->load->view('template/foot');
?>