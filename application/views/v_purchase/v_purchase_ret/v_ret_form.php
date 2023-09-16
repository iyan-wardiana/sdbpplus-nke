<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Agustus 2018
 * File Name	= v_ret_form.php
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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$curRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$RET_NUM		= '';
	$IR_NUM			= '';
	$IR_CODE		= '';
	$RET_CODE 		= '';
	$RET_TYPE 		= 1; 		// Internal
	$RET_CAT		= 0;		// In Direct
	$RET_DATE		= '';
	$PRJNAME 		= '';
	$SPLCODE 		= '0';
	$SPLDESC 		= '';
	$SPLADD1 		= '';
	$RET_CURR 		= 'IDR';
	$RET_CURRATE	= 1;
	$RET_TAXCURR 	= 'IDR';
	$RET_TAXRATE 	= 1;
	$RET_TOTCOST	= 0;
	$DP_CODE		= '';
	$DP_PPN_		= 0;
	$DP_JUML		= 0;
	$RET_PAYTYPE 	= 'Cash';
	$RET_TENOR 		= 0;
	$RET_STAT 		= 1;
	$RET_INVSTAT	= 0;					
	$RET_NOTES		= '';
	$RET_NOTES1		= '';
	$RET_MEMO 		= '';
	$IR_VOLM		= 0;
	
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

	/*$this->db->where('Patt_Year', $year);
	$this->db->where('Patt_Month', $month);
	$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_RET_header');
	
	$sql 		= "SELECT MAX(Patt_Number) as maxNumber FROM tbl_RET_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$result 	= $this->db->query($sql)->result();
	if($myCount>0)
	{
		foreach($result as $row) :
			$myMax 	= $row->maxNumber;
			$myMax 	= $myMax+1;
		endforeach;
	}	else	{		$myMax = 1;	}*/
	
	$sqlC 		= "tbl_ret_header WHERE Patt_Year = $year AND PRJCODE = '$PRJCODE'";
	$resC 		= $this->db->count_all($sqlC);
	$myMax 		= $resC+1;
	
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
	
	if($RET_TYPE == 1) 
	{
		$POType = '01';
	}
	else if($RET_TYPE == 2) 
	{
		$POType = '02';
	}
	
	$year			= date('y');
	$month			= date('m');
	$days			= date('d');
	$DocNumber 		= "$Pattern_Code$PRJCODE$year$month$days$POType-$lastPatternNumb";
	$RET_NUM		= $DocNumber;
	$RET_CODE		= "$lastPatternNumb"; // OP MANUAL
	
	//$RETCODE		= substr($lastPatternNumb, -4);
	$RETCODE		= $lastPatternNumb;
	$RETYEAR		= date('y');
	$RETMONTH		= date('m');
	$RET_CODE		= "$Pattern_Code.$RETCODE.$RETYEAR.$RETMONTH"; // MANUAL CODE
	
	$RET_DATEY 		= date('Y');
	$RET_DATEM 		= date('m');
	$RET_DATED 		= date('d');
	$RET_DATE 		= date('d/m/Y');
	
	$Patt_Year 		= date('Y');
	$Patt_Month		= date('m');
	$Patt_Date		= date('d');
	
	$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resultPRJ 		= $this->db->query($sqlPRJ)->result();
	
	foreach($resultPRJ as $rowPRJ) :
		$PRJCODE1 	= $rowPRJ->PRJCODE;
		$PRJNAME1 	= $rowPRJ->PRJNAME;
	endforeach;
	$IR_NUMX		= '';
	$IR_CODEX		= '';
	$RET_TYPE		= '';
	$SPLCODE		= '';
}
else
{
	$isSetDocNo = 1;
	$RET_NUM 		= $default['RET_NUM'];
	$DocNumber		= $RET_NUM;
	$RET_CODE 		= $default['RET_CODE'];
	$RET_DATE 		= $default['RET_DATE'];
	$RET_TYPE 		= $default['RET_TYPE'];
	$RET_DATE		= date('d/m/Y', strtotime($RET_DATE));
	$PRJCODE 		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$IR_NUM 		= $default['IR_NUM'];
	$IR_NUMX		= $IR_NUM;
	$IR_CODE 		= $default['IR_CODE'];
	$IR_CODEX		= $IR_CODE;
	$RET_NOTES 		= $default['RET_NOTES'];
	$RET_NOTES1		= $default['RET_NOTES1'];
	$PRJNAME1 		= $default['PRJNAME'];
	$RET_STAT 		= $default['RET_STAT'];
	$JOBCODEID 		= $default['JOBCODEID'];
	$lastPatternNumb1= $default['Patt_Number'];
	
	$RET_TAXRATE		= 1;
	$totTaxPPnAmount	= 1;
	$totTaxPPhAmount	= 1;
}

if(isset($_POST['submit1']))
{
	$SPLCODEX 		= $this->input->post('SPLCODEX');
	$SPLCODE 		= $this->input->post('SPLCODEX');
	$RET_TYPE 		= $this->input->post('RET_TYPEX');
}
else
{
	$SPLCODEX 		= $SPLCODE;
	$RET_TYPE		= $RET_TYPE;
	$SPLCODE 		= $SPLCODE;
}

if(isset($_POST['IR_NUMX']))
{
	$IR_NUMX		= $_POST['IR_NUMX'];
	$RET_TYPE		= $_POST['RET_TYPEX1'];
	$IR_CODEX		= $_POST['IR_CODEX'];
	$SPLCODE		= $_POST['SPLCODEX1'];
}
else
{
	$IR_NUMX		= $IR_NUMX;
	$RET_TYPE		= $RET_TYPE;
	$IR_CODEX		= $IR_CODEX;
	$SPLCODE		= $SPLCODE;
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
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'IRCode')$IRCode = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
			if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ProcessStatus')$ProcessStatus = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Received')$Received = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Discount')$Discount = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Purchase')$Purchase = $LangTransl;
			if($TranslCode == 'Tax')$Tax = $LangTransl;
			if($TranslCode == 'Request')$Request = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'Return')$return = $LangTransl;
			if($TranslCode == 'Disposal')$disposal = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'selSupl')$selSupl = $LangTransl;
			if($TranslCode == 'qtyGTStok')$qtyGTStok = $LangTransl;
			if($TranslCode == 'noMtrRet')$noMtrRet = $LangTransl;
		endforeach;
	?>
    
    <body class="<?php echo $appBody; ?>">
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
				    <?php echo $mnName; ?>
				    <small><?php echo $PRJNAME1; ?></small>
				</h1>
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
			                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
			                        <input type="text" name="IR_NUMX" id="IR_NUMX" class="textbox" value="<?php echo $IR_NUMX; ?>" />
			                        <input type="text" name="RET_TYPEX1" id="RET_TYPEX1" class="textbox" value="<?php echo $RET_TYPE; ?>" />
			                        <input type="text" name="IR_CODEX" id="IR_CODEX" class="textbox" value="<?php echo $IR_CODEX; ?>" />
			                        <input type="text" name="SPLCODEX1" id="SPLCODEX1" value="<?php echo $SPLCODE; ?>" />
			                        <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
			                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
			                    </form>
			                    <form class="form-horizontal" name="frm1" id="frm1" method="post" action="" style="display:none">
			                        <input type="text" name="SPLCODEX" id="SPLCODEX" value="<?php echo $SPLCODE; ?>" />
			                        <input type="text" name="RET_TYPEX" id="RET_TYPEX" class="textbox" value="<?php echo $RET_TYPE; ?>" />
			                        <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
			                    </form>
			                    <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
			           				<input type="hidden" name="rowCount" id="rowCount" value="0">
			                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" style="max-width:195px" name="RET_NUM1" id="RET_NUM1" value="<?php echo $DocNumber; ?>" disabled >
			                       			<input type="hidden" class="textbox" name="RET_NUM" id="RET_NUM" size="30" value="<?php echo $DocNumber; ?>" />
			                                <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPatternNumb1; ?>">
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ManualCode ?> </label>
			                          	<div class="col-sm-10">
			                                <input type="text" class="form-control" name="RET_CODE" id="RET_CODE" value="<?php echo $RET_CODE; ?>" >
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Date ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group date">
			                                    <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                    <input type="text" name="RET_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $RET_DATE; ?>" style="width:120px">
			                                </div>
			                          	</div>
			                        </div>
			                        <div class="form-group" style="display:none">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="RET_TYPE" id="RET_TYPE" class="form-control select2">
			                                	<option value="RET" <?php if($RET_TYPE == 'RET') { ?> selected <?php } ?>>
													<?php echo $return; ?>
			                                   	</option>
			                                	<option value="DIS" <?php if($RET_TYPE == 'DIS') { ?> selected <?php } ?> style="display:none">
													<?php // echo $disposal; ?>
			                                   	</option>
			                                </select>
			                          	</div>
			                        </div>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $SupplierName ?> </label>
			                          	<div class="col-sm-10">
			                        		<select name="SPLCODE" id="SPLCODE" class="form-control select2" data-placeholder="&nbsp;&nbsp;&nbsp;<?php echo $SupplierName; ?>" onChange="getVend(this.value)">
			                                	<option value="0" <?php if($SPLCODE == 0) { ?> selected <?php } ?>> --- </option>
			                                    
			                                    <?php
			                                    if($countVend > 0)
			                                    {
			                                        foreach($vwvendor as $row) :
			                                            $SPLCODE1	= $row->SPLCODE;
			                                            $SPLDESC1	= $row->SPLDESC;
			                                            ?>
			                                                <option value="<?php echo $SPLCODE1; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>><?php echo "$SPLCODE1 - $SPLDESC1"; ?></option>
			                                            <?php
			                                        endforeach;
			                                    }
			                                    ?>
			                                </select>
			                          	</div>
			                        </div>
			                        <script>
			                            function getVend()
			                            {
											SPLCODE		= document.getElementById('SPLCODE').value;
											document.getElementById('SPLCODEX').value = SPLCODE;
											
											RET_TYPE	= document.getElementById('RET_TYPE').value;
											document.getElementById("RET_TYPEX").value 	= RET_TYPE;
			                                document.frm1.submit1.click();
			                            }
			                        </script>
			                        <div class="form-group" style="display: none;">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $IRCode ?> </label>
			                          	<div class="col-sm-10">
			                                <div class="input-group">
			                                    <div class="input-group-btn">
													<button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
			                                    </div>
			                                    <input type="hidden" class="form-control" name="IR_NUM" id="IR_NUM" style="max-width:160px" value="<?php echo $IR_NUMX; ?>" >
			                                    <input type="hidden" class="form-control" name="IR_CODE" id="IR_CODE" style="max-width:160px" value="<?php echo $IR_CODEX; ?>" >
			                                    <input type="text" class="form-control" name="IR_NUM1" id="IR_NUM1" value="<?php echo $IR_CODEX; ?>" onClick="pleaseCheck();" <?php if($RET_STAT != 1 && $RET_STAT != 4) { ?> disabled <?php } ?>>
			                                </div>
			                            </div>
			                        </div>
									<?php
										$colSPL		= "$PRJCODE~$SPLCODE";
										$selSource	= site_url('c_purchase/c_po180c19ret/popupallIR/?id='.$this->url_encryption_helper->encode_url($colSPL));
			                        ?>
			                        <script>
										var url1 = "<?php echo $selSource;?>";
										function pleaseCheck()
										{
											SPLCODE	= document.getElementById('SPLCODE').value;
											if(SPLCODE == '')
											{
												swal('<?php echo $selSupl; ?>');
												document.getElementById('SPLCODE').focus();
												return false;
											}
											
											title = 'Select Item';
											w = 1000;
											h = 550;
											//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
										}
									</script>
			                        <div class="form-group" style="display: none;">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Project ?> </label>
			                          	<div class="col-sm-10">
			                            	<select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:400px" onChange="chooseProject()" disabled>
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
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Notes ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="RET_NOTES"  id="RET_NOTES" style="height:70px"><?php echo $RET_NOTES; ?></textarea>
			                          	</div>
			                        </div>
			                        <?php if($RET_NOTES1 != '') { ?>
			                        <div class="form-group">
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes ?> </label>
			                          	<div class="col-sm-10">
			                                <textarea class="form-control" name="RET_NOTES1"  id="RET_NOTES1" style="max-width:400px;height:70px"><?php echo $RET_NOTES1; ?></textarea>
			                          	</div>
			                        </div>
			                        <?php } ?>
			                        <div class="form-group" >
			                          	<label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
			                          	<div class="col-sm-10">
			                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $RET_STAT; ?>">
			                                <select name="RET_STAT" id="RET_STAT" class="form-control select2" style="max-width:150px">
			                                    <?php
												if($RET_STAT != 1 AND $RET_STAT != 4) 
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($RET_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                            <option value="2"<?php if($RET_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
			                                            <option value="3"<?php if($RET_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
			                                            <option value="4"<?php if($RET_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                            <option value="5"<?php if($RET_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                            <option value="6"<?php if($RET_STAT == 6) { ?> selected <?php } ?>>Closed</option>
			                                            <option value="7"<?php if($RET_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($RET_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                            <option value="2"<?php if($RET_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                        <?php
			                                    }
												?>
			                                </select>
			                            </div>
			                        </div>
			                        <?php
			                        	$collData	= "$PRJCODE~$SPLCODE";
										$url_AddItm	= site_url('c_purchase/c_po180c19ret/p0p1t3m/?id='.$this->url_encryption_helper->encode_url($collData));
									?>
			                        <div class="form-group">
			                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
			                            <div class="col-sm-10">
			                                <script>
			                                    var url = "<?php echo $url_AddItm;?>";
			                                    function selectitem()
			                                    {
			                                        SPLCODE	= document.getElementById('SPLCODE').value;
			                                        if(SPLCODE == 0)
			                                        {
			                                            swal('<?php echo $selSupl; ?>',
			                                            {
			                                            	icon: "warning",
			                                            });
			                                            document.getElementById('SPLCODE').focus();
			                                            return false;
			                                        }
			                                        
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
			                                <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<?php echo $AddItem; ?>
			                                </button>
			                                </div>
			                        </div>
			                        <div class="row">
			                            <div class="col-md-12">
			                                <div class="box box-primary">
			                                <br>
			                                <table width="100%" border="1" id="tbl">
			                                    <tr style="background:#CCCCCC">
			                                        <th width="3%" height="25" style="text-align:center">No.</th>
			                                      	<th width="4%" style="text-align:center" nowrap><?php echo $ItemCode; ?> </th>
			                                      	<th width="19%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
			                                      	<th width="18%" style="text-align:center" nowrap><?php echo $Description; ?></th>
			                                      	<th width="8%" style="text-align:center" nowrap><?php echo $Received; ?></th>
			                                      	<th width="6%" style="text-align:center" nowrap><?php echo $Stock; ?> </th>
			                                        <th width="9%" style="text-align:center" nowrap><?php echo $Quantity; ?> </th>
			                                      	<th width="7%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
			                                      	<th width="3%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?><br>
			                                      	(%)</th>
			                                      	<th width="2%" style="text-align:center; display:none" nowrap><?php echo $Discount; ?> </th>
			                                      	<th width="2%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?></th>
			                                      	<th width="19%" style="text-align:center" nowrap><?php echo $Remarks; ?></th>
			                                  </tr>
			                                    <?php
													$resultC	= 0;
													if($task == 'edit')
													{
														$sqlDET		= "SELECT A.IR_ID, A.IR_NUM, A.IR_CODE, A.ACC_ID, A.POD_ID, A.PO_NUM, A.PO_CODE,
																			A.JOBCODEDET, A.JOBCODEID,
																			A.WH_CODE, A.ITM_CODE, A.ITM_UNIT, 
																			A.IR_VOLM, A.IR_PRICE, A.IR_AMOUNT,
																			A.ITM_QTY AS RET_VOLM, A.ITM_PRICE AS RET_PRICE, 
																			A.RET_COST, A.TAXCODE1, A.TAXCODE2,
																			A.TAXPRICE1, A.TAXPRICE2, A.NOTES, A.RET_REMARKS,
																			B.ITM_NAME, B.ITM_GROUP
																		FROM tbl_ret_detail A
																			INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		WHERE 
																			A.RET_NUM = '$RET_NUM' 
																			AND B.PRJCODE = '$PRJCODE'";
														$result = $this->db->query($sqlDET)->result();
														
														$sqlDETC	= "tbl_ret_detail A
																			INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																		WHERE 
																			A.RET_NUM = '$RET_NUM' 
																			AND B.PRJCODE = '$PRJCODE'";
														$resultC 	= $this->db->count_all($sqlDETC);
													}

													$i			= 0;
													$j			= 0;
													if($resultC > 0)
													{
														$GT_ITMPRICE	= 0;
														foreach($result as $row) :
															$curRow  		= ++$i;																
															$RET_NUM 		= $RET_NUM;
															$RET_CODE 		= $RET_CODE;
															$PRJCODE		= $PRJCODE;
															$IR_ID			= $row->IR_ID;
															$IR_NUM			= $row->IR_NUM;
															$IR_CODE 		= $row->IR_CODE;
															$ACC_ID 		= $row->ACC_ID;
															$POD_ID 		= $row->POD_ID;
															$PO_NUM 		= $row->PO_NUM;
															$PO_CODE 		= $row->PO_CODE;
															$JOBCODEDET		= $row->JOBCODEDET;
															$JOBCODEID		= $row->JOBCODEID;
															$WH_CODE 		= $row->WH_CODE;
															$ITM_CODE 		= $row->ITM_CODE;
															$ITM_NAME 		= $row->ITM_NAME;
															$ITM_UNIT 		= $row->ITM_UNIT;
															$ITM_GROUP 		= $row->ITM_GROUP;
															$IR_VOLM 		= $row->IR_VOLM;
															$IR_PRICE 		= $row->IR_PRICE;
															$IR_AMOUNT 		= $row->IR_AMOUNT;
															$RET_VOLM 		= $row->RET_VOLM;
															$RET_PRICE 		= $row->RET_PRICE;
															$RET_COST 		= $row->RET_COST;
															$TAXCODE1		= $row->TAXCODE1;
															$TAXCODE2		= $row->TAXCODE2;
															$TAXPRICE1		= $row->TAXPRICE1;
															$TAXPRICE2		= $row->TAXPRICE2;
															$NOTES			= $row->NOTES;
															$RET_REMARKS	= $row->RET_REMARKS;
															
															// CHECK LAST STOCK
																$STOCK_A	= 0;
																$sqlSTOCK	= "SELECT ITM_VOLM FROM tbl_item 
																				WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
																$resSTOCK	= $this->db->query($sqlSTOCK)->result();
																foreach($resSTOCK as $rowSTOCK):
																	$STOCK_A= $rowSTOCK->ITM_VOLM;
																endforeach;

															$outStock		= 0;
															if($RET_VOLM > $STOCK_A)
																$outStock	= 1;
												
															/*if ($j==1) {
																echo "<tr class=zebra1>";
																$j++;
															} else {
																echo "<tr class=zebra2>";
																$j--;
															}*/
															?> 
			                                 				<tr id="tr_<?php echo $curRow; ?>">
			                                                	<!-- NO. URUT -->
																<td width="3%" height="25" style="text-align:left">
																	<?php
			                                                            if($RET_STAT == 1)
			                                                            {
			                                                                ?>
			                                                                    <a href="#" onClick="deleteRow(<?php echo $curRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs" style="display:none"><i class="fa fa-trash-o"></i></a>
			                                                                    
			                                                       				<a href="#" onClick="deleteRow(<?php echo $curRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">
			                                                                <?php
			                                                            }
			                                                            else
			                                                            {
			                                                                echo "$curRow.";
			                                                            }
			                                                        ?>
			                                                	</td>
			                                                	<!-- ITEM CODE -->
			                                                    <td width="4%" style="text-align:left">
			                                                        <?php print $ITM_CODE; ?>
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>PRJCODE" name="data[<?php echo $curRow; ?>][PRJCODE]" value="<?php print $PRJCODE; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>RET_NUM" name="data[<?php echo $curRow; ?>][RET_NUM]" value="<?php print $RET_NUM; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>RET_CODE" name="data[<?php echo $curRow; ?>][RET_CODE]" value="<?php print $RET_CODE; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>RET_DATE" name="data[<?php echo $curRow; ?>][RET_DATE]" value="<?php print $RET_DATE; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>ITM_CODE" name="data[<?php echo $curRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>ACC_ID" name="data[<?php echo $curRow; ?>][ACC_ID]" value="<?php echo $ACC_ID; ?>" width="10" size="15">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $curRow; ?>ITM_GROUP" name="data[<?php echo $curRow; ?>][ITM_GROUP]" value="<?php print $ITM_GROUP; ?>">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>IR_ID" name="data[<?php echo $curRow; ?>][IR_ID]" value="<?php print $IR_ID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>IR_NUM" name="data[<?php echo $curRow; ?>][IR_NUM]" value="<?php print $IR_NUM; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>POD_ID" name="data[<?php echo $curRow; ?>][POD_ID]" value="<?php print $POD_ID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>PO_NUM" name="data[<?php echo $curRow; ?>][PO_NUM]" value="<?php print $PO_NUM; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>JOBCODEID" name="data[<?php echo $curRow; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>JOBCODEID" name="data[<?php echo $curRow; ?>][JOBCODEID]" value="<?php print $JOBCODEID; ?>" width="10" size="15">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>WH_CODE" name="data[<?php echo $curRow; ?>][WH_CODE]" value="<?php print $WH_CODE; ?>" width="10" size="15">
			                                                	</td>
			                                                    <!-- ITEM NAME -->
			                                                    <td width="19%" style="text-align:left"><?php echo $ITM_NAME; ?></td>
			                                                    <!-- REF NUMBER -->
			                                                    <td width="18%" style="text-align:left">
			                                                    	<?php print "$IR_CODE | $PO_CODE"; ?>
			                                                         <input type="hidden" id="data<?php echo $curRow; ?>IR_CODE" name="data[<?php echo $curRow; ?>][IR_CODE]" value="<?php print $IR_CODE; ?>">
			                                                         <input type="hidden" id="data<?php echo $curRow; ?>PO_CODE" name="data[<?php echo $curRow; ?>][PO_CODE]" value="<?php print $PO_CODE; ?>">
			                                                    </td>
			                                                    <!-- RECEIPT QTY -->
			                                                    <td width="8%" style="text-align:right" nowrap>
			                                                        <?php print number_format($IR_VOLM, $decFormat); ?>
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>IR_VOLM" name="data[<?php echo $curRow; ?>][IR_VOLM]" value="<?php print $IR_VOLM; ?>">
			                                                        <input type="hidden" id="data<?php echo $curRow; ?>IR_PRICE" name="data[<?php echo $curRow; ?>][IR_PRICE]" value="<?php print $IR_PRICE; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $curRow; ?>IR_AMOUNT" name="data[<?php echo $curRow; ?>][IR_AMOUNT]" value="<?php print $IR_AMOUNT; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $curRow; ?>ITM_PRICE" name="data[<?php echo $curRow; ?>][ITM_PRICE]" value="<?php print $RET_PRICE; ?>">
			                                                    </td>
			                                                    <!-- QTY STOCK -->
			                                                    <td width="6%" style="text-align:right" nowrap>
			                                                        <?php echo number_format($STOCK_A,2); ?>
			                                                        <input type="hidden" name="ITM_STOCK<?php echo $curRow; ?>" id="ITM_STOCK<?php echo $curRow; ?>" value="<?php echo $STOCK_A; ?>" class="form-control">
			                                                    </td>
			                                                    <!-- QTY RET -->
			                                                    <td width="9%" style="text-align:center">
			                                                        <input type="text" size="10" class="form-control" style="min-width:100px; max-width:100px; text-align:right" name="ITM_QTYX<?php echo $curRow; ?>" id="ITM_QTYX<?php echo $curRow; ?>" value="<?php print number_format($RET_VOLM, $decFormat); ?>" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueRET(this, <?php echo $curRow; ?>);" onfocus="this.select();" >
			                                                         <input type="hidden" id="data<?php echo $curRow; ?>ITM_QTY" name="data[<?php echo $curRow; ?>][ITM_QTY]" value="<?php print $RET_VOLM; ?>">
			                                                    </td>
			                                                    <!-- ITEM UNIT -->
			                                                    <td width="7%" style="text-align:center; font-style:italic" nowrap>
			                                                        <?php print $ITM_UNIT; ?>
			                                                         <input type="hidden" id="data<?php echo $curRow; ?>ITM_UNIT" name="data[<?php echo $curRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">

			                                                        <input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'" class="form-control">
			                                                    </td>
			                                                    <!-- ITEM DESC. AND TOTAL COST -->
			                                                    <td width="19%" style="text-align:center; font-style:italic">
			                                                      	<input type="text" class="form-control" id="data<?php echo $curRow; ?>RET_REMARKS" name="data[<?php echo $curRow; ?>][RET_REMARKS]" value="<?php print $RET_REMARKS; ?>">
			                                                      	<input type="hidden" class="form-control" id="data<?php echo $curRow; ?>RET_COST" name="data[<?php echo $curRow; ?>][RET_COST]" value="<?php print $RET_COST; ?>">
			                                                    </td>
															</tr>
															<?php
														endforeach;
													}
			                                    ?>
			                                    <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $curRow; ?>">
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
													if($RET_STAT == 1 && $ISCREATE == 1)
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
													if($RET_STAT == 1 && $ISCREATE == 1)
													{
														?>
															<button class="btn btn-primary" >
															<i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
															</button>&nbsp;
														<?php
													}
												}
												$backURL	= site_url('c_purchase/c_po180c19ret/gl180c19ret/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
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

	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		IR_NUM		= arrItem[0];
		IR_CODE		= arrItem[1];
		
		document.getElementById("IR_NUMX").value 	= IR_NUM;
		document.getElementById("IR_CODEX").value 	= IR_CODE;
		
		RET_TYPE	= document.getElementById('RET_TYPE').value;
		document.getElementById("RET_TYPEX1").value = RET_TYPE;
		
		document.frmsrch.submitSrch.click();
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;

		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		//swal(PR_NUMx);
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		IR_ID 		= arrItem[0];
		PRJCODE 	= arrItem[1];
		IR_NUM 		= arrItem[2];
		ACC_ID 		= arrItem[3];
		POD_ID 		= arrItem[4];
		PO_NUM 		= arrItem[5];
		JOBCODEID 	= arrItem[6];
		WH_CODE 	= arrItem[7];
		ITM_CODE 	= arrItem[8];
		ITM_NAME 	= arrItem[9];
		ITM_UNIT 	= arrItem[10];
		ITM_GROUP 	= arrItem[11];
		ITM_QTY 	= arrItem[12];
		ITM_PRICE 	= arrItem[13];
		ITM_TOTAL 	= arrItem[14];
		ITM_VOLM 	= arrItem[15];

		IR_CODE 	= arrItem[16];
		PO_CODE 	= arrItem[17];

		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		intIndex = parseInt(document.frm.rowCount.value) + 1;
		//intIndex = parseInt(objTable.rows.length) - 1;
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		var decFormat	= document.getElementById('decFormat').value;

		// CHK[0]
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="Checkbox" style="display:none" id="chk'+intIndex+'" name="chk'+intIndex+'" value="" ><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';

		// ITM_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'"><input type="hidden" id="data'+intIndex+'ACC_ID" name="data['+intIndex+'][ACC_ID]" value="'+ACC_ID+'"><input type="hidden" id="data'+intIndex+'IR_ID" name="data['+intIndex+'][IR_ID]" value="'+IR_ID+'"><input type="hidden" id="data'+intIndex+'IR_NUM" name="data['+intIndex+'][IR_NUM]" value="'+IR_NUM+'"><input type="hidden" id="data'+intIndex+'POD_ID" name="data['+intIndex+'][POD_ID]" value="'+POD_ID+'"><input type="hidden" id="data'+intIndex+'PO_NUM" name="data['+intIndex+'][PO_NUM]" value="'+PO_NUM+'"><input type="hidden" id="data'+intIndex+'JOBCODEDET" name="data['+intIndex+'][JOBCODEDET]" value="'+JOBCODEID+'"><input type="hidden" id="data'+intIndex+'JOBCODEID" name="data['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'"><input type="hidden" id="data'+intIndex+'WH_CODE" name="data['+intIndex+'][WH_CODE]" value="'+WH_CODE+'"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'">';
		
		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = ''+ITM_NAME+'';
		
		// DESCRIPTION
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'left';
			objTD.innerHTML = ''+IR_CODE+' | '+PO_CODE+'<input type="hidden" name="data['+intIndex+'][IR_CODE]" id="data'+intIndex+'IR_CODE" value="'+IR_CODE+'" class="form-control"><input type="hidden" name="data['+intIndex+'][PO_CODE]" id="data'+intIndex+'PO_CODE" value="'+PO_CODE+'" class="form-control">';
				
		// RECEIPT QTY
			var IRVOLM 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTY)),decFormat));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+IRVOLM+'<input type="hidden" id="data'+intIndex+'IR_VOLM" name="data['+intIndex+'][IR_VOLM]" value="'+ITM_QTY+'" class="form-control"><input type="hidden" id="data'+intIndex+'IR_PRICE" name="data['+intIndex+'][IR_PRICE]" value="'+ITM_PRICE+'" class="form-control"><input type="hidden" id="data'+intIndex+'IR_AMOUNT" name="data['+intIndex+'][IR_AMOUNT]" value="'+ITM_TOTAL+'" class="form-control"><input type="hidden" id="data'+intIndex+'ITM_PRICE" name="data['+intIndex+'][ITM_PRICE]" value="'+ITM_PRICE+'" class="form-control">';
		
		// STOCK
			var STOCKQTY 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+STOCKQTY+'<input type="hidden" name="ITM_STOCK'+intIndex+'" id="ITM_STOCK'+intIndex+'" value="'+ITM_VOLM+'" class="form-control">';
		
		// RETURN QTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_QTYX'+intIndex+'" id="ITM_QTYX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getValueRET(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_QTY]" id="data'+intIndex+'ITM_QTY" value="0.00" class="form-control" style="max-width:300px;" >';	
		
		// ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'" class="form-control">';

		// REMARKS
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = '<input type="text" id="data'+intIndex+'RET_REMARKS" name="data['+intIndex+'][RET_REMARKS]" value="" class="form-control"><input type="hidden" id="data'+intIndex+'RET_COST" name="data['+intIndex+'][RET_COST]" value="0.00" width="10" class="form-control">';
		
		document.getElementById('totalrow').value = intIndex;	
	}
	
	function getValueRET(thisVal, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		ITMCODE_A 		= document.getElementById('data'+row+'ITM_CODE').value;
		
		// CHECK ITEM STOCK BY ITM CODE
		totRow	= document.getElementById('totalrow').value;

		STOCK_A	= 0;
		for(i=1;i<=totRow;i++)
		{
			ITMCODE 	= document.getElementById('data'+i+'ITM_CODE').value;
			if(ITMCODE_A == ITMCODE)
			{
				STOCK_A	= parseFloat(document.getElementById('ITM_STOCK'+row).value);
			}
		}

		RET_QTY1		= document.getElementById('ITM_QTYX'+row);
		RET_QTY 		= parseFloat(eval(RET_QTY1).value.split(",").join(""));
		
		if(isNaN(RET_QTY))
		{
			document.getElementById('data'+row+'ITM_QTY').value 	= 0;
			document.getElementById('ITM_QTYX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)),decFormat));
			RET_QTY		= 0;
		}

		if(RET_QTY > STOCK_A)
		{
			swal('<?php echo $qtyGTStok; ?>',
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'ITM_QTY').value 	= parseFloat(Math.abs(STOCK_A));
			document.getElementById('ITM_QTYX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(STOCK_A)),decFormat));
			return false;
		}
		
		document.getElementById('data'+row+'ITM_QTY').value 	= parseFloat(Math.abs(RET_QTY));
		document.getElementById('ITM_QTYX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(RET_QTY)),decFormat));
		
		ITM_PRICE		= document.getElementById('data'+row+'ITM_PRICE').value;
		RET_COST		= parseFloat(RET_QTY * ITM_PRICE);
		document.getElementById('data'+row+'RET_COST').value 	= parseFloat(Math.abs(RET_COST));
	}
	
	function checkInp()
	{
		totRow	= document.getElementById('totalrow').value;
		
		SPLCODE	= document.getElementById('SPLCODE').value;
		if(SPLCODE == 0)
		{
			swal("<?php echo $selSupl; ?>",
			{
				icon: "warning",
			});
			document.getElementById('SPLCODE').focus();
			return false;	
		}

		if(totRow == 0)
		{
			swal('<?php echo $noMtrRet; ?>',
			{
				icon: "warning",
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
	$this->load->view('template/aside');

	$this->load->view('template/js_data');

	$this->load->view('template/foot');
?>