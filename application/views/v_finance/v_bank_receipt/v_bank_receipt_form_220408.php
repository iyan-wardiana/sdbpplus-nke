<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Desember 2017
	* File Name	= v_bank_receipt_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
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
$decFormat	= 2;

$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

/*$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;*/

$currentRow = 0;
if($task == 'add')
{
	foreach($vwDocPatt as $row) :
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
	
	$yearC = (int)$Pattern_YearAktive;
	$year = substr($Pattern_YearAktive,2,2);
	$month = (int)$Pattern_MonthAktive;
	$date = (int)$Pattern_DateAktive;

	$this->db->where('Patt_Year', $year);
	//$this->db->where('Patt_Month', $month);
	//$this->db->where('Patt_Date', $date);
	$myCount = $this->db->count_all('tbl_br_header');
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_br_header
			WHERE Patt_Year = $yearC AND BR_TYPE = 'BR'";
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
	
	
	/*$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;*/
	$DocNumber 		= "$Pattern_Code$groupPattern-$lastPatternNumb";
	$BR_NUM			= "$DocNumber";
	$BR_CODE		= "$lastPatternNumb"; // MANUAL CODE
	$BR_DATE		= date('d/m/Y');
	$BR_TYPE		= 'BR';
	if($PRJSCATEG == 1)
		$BR_RECTYPE	= 'DP';
	else
		$BR_RECTYPE	= 'SAL';

	$BR_CURRID		= 'IDR';
	$BR_CURRCONV	= 1;
	$BankAcc_ID		= '';
	$BR_PAYFROM		= '';
	$BR_CHEQNO		= '';
	$BR_NOTES		= '';
	$BR_STAT 		= 1;	
	$Patt_Year 		= date('Y');
	$Patt_Number	= $lastPatternNumb1;	
	$PR_VALUE		= 0;
	$PR_VALUEAPP	= 0;
	$BR_TOTAM		= 0;
	$BR_TOTAM_PPn	= 0;
}
else
{
	$isSetDocNo = 1;
	$BR_NUM 		= $default['BR_NUM'];
	$JournalH_Code	= $BR_NUM;
	$DocNumber		= $BR_NUM;
	$BR_CODE 		= $default['BR_CODE'];
	$BR_DATE1		=  $default['BR_DATE'];
	$BR_DATE		= date('d/m/Y',strtotime($BR_DATE1));
	$BR_TYPE 		= $default['BR_TYPE'];
	$BR_RECTYPE		= $default['BR_RECTYPE'];
	$BR_CURRID 		= $default['BR_CURRID'];
	$BR_CURRCONV	= $default['BR_CURRCONV'];
	$BankAcc_ID		= $default['Acc_ID'];
	$BR_PAYFROM 	= $default['BR_PAYFROM'];
	$BR_CHEQNO 		= $default['BR_CHEQNO'];
	$BR_TOTAM 		= $default['BR_TOTAM'];
	$BR_TOTAM_PPn	= $default['BR_TOTAM_PPn'];
	$BR_NOTES 		= $default['BR_NOTES'];
	$BR_STAT		= $default['BR_STAT'];
	$Acc_ID 		= $default['Acc_ID'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];
}
	
if(isset($_POST['submit1']))
{
	$SelCurr 		= $this->input->post('BR_CURRIDA');
	$selAccount 	= $this->input->post('AccSelected');
	$BR_PAYFROM 	= $this->input->post('SPLSelected');
	$BR_RECTYPE 	= $this->input->post('BRTSelected');
}
else
{
	$SelCurr 		= 'IDR';
	$selAccount	 	= $BankAcc_ID;
	$BR_PAYFROM 	= $BR_PAYFROM;
	$BR_RECTYPE		= $BR_RECTYPE;
}
if($BR_RECTYPE == 'SAL')
{
	$PRJCODE	= '';
	$sqlPRJ 	= "SELECT PRJCODE FROM tbl_sinv_header WHERE CUST_CODE = '$BR_PAYFROM'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
elseif($BR_RECTYPE == 'PRJ')
{
	$PRJCODE	= '';
	$sqlPRJ 	= "SELECT PRJCODE FROM tbl_projinv_header
					WHERE PINV_OWNER = '$BR_PAYFROM'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
else
{
	$PRJCODE	= '';
	$sqlPRJ 	= "SELECT PRJCODE FROM tbl_projinv_header
					WHERE PINV_OWNER = '$BR_PAYFROM'";
	$resPRJ 	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE = $rowPRJ->PRJCODE;
	endforeach;
}
?>
<!DOCTYPE html>

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
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;		
			if($TranslCode == 'ChooseInvoice')$ChooseInvoice = $LangTransl;
			if($TranslCode == 'BankReceipt')$BankReceipt = $LangTransl;
			if($TranslCode == 'Finance')$Finance = $LangTransl;		
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'PPn')$PPn = $LangTransl;		
			if($TranslCode == 'Receipt')$Receipt = $LangTransl;
			
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;		
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Planning')$Planning = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'PaymentNow')$PaymentNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'BR_CODE')$BR_CODE = $LangTransl;
			if($TranslCode == 'InvoiceAmount')$InvoiceAmount = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ReceiptFrom')$ReceiptFrom = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'BankAccount')$BankAccount = $LangTransl;
			if($TranslCode == 'ActualBalance')$ActualBalance = $LangTransl;
			if($TranslCode == 'Reserved')$Reserved = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Sales')$Sales = $LangTransl;
			if($TranslCode == 'DetInfo')$DetInfo = $LangTransl;
			if($TranslCode == 'srcRec')$srcRec = $LangTransl;
			if($TranslCode == 'SourceDocument')$SourceDocument = $LangTransl;
			if($TranslCode == 'IRList')$IRList = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
		endforeach;
		
		if($LangID = 'IND')
		{
			$alert1		= "Masukan data detail penerimaan.";
			$alert2		= "Nilai penerimaan tidak boleh kosong.";
			$alert3		= "Pilih sumber penerimaan.";
		}
		else
		{
			$alert1		= "Please insert an receipt detail.";
			$alert2		= "Receipt amount can not be empty.";
			$alert3		= "Select a Receiping Income.";
		}
		
		$secGenCode	= base_url().'index.php/c_finance/c_bank_payment/genCode/'; // Generate Code
	?>

	<style>
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bank_receipt.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $BankReceipt; ?>
			    <small><?php echo $Finance; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
		        <form class="form-horizontal" name="frm1" method="post" action="" style="display:none">
		        	<input type="text" name="BR_CURRIDA" id="BR_CURRIDA" value="<?php echo $SelCurr; ?>" />
		        	<input type="text" name="AccSelected" id="AccSelected" value="<?php echo $selAccount; ?>" />
		        	<input type="text" name="SPLSelected" id="SPLSelected" value="<?php echo $BR_PAYFROM; ?>" />
		        	<input type="text" name="BRTSelected" id="BRTSelected" value="<?php echo $BR_RECTYPE; ?>" />
		            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
		        </form>
		        <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="">
		                        <input type="TEXT" name="BR_TYPE" id="BR_TYPE" value="<?php echo $BR_TYPE; ?>">
		                        <input type="TEXT" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
		                        <input type="TEXT" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="TEXT" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="TEXT" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="TEXT" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="TEXT" name="CBDate" id="CBDate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>

                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
		            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
		            <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		            <input type="Hidden" name="rowCount" id="rowCount" value="0">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><?php echo $srcRec; ?></h3>
							</div>
							<div class="box-body">
								<?php if($isSetDocNo == 0) { ?>
				                    <div class="col-sm-12">
				                        <div class="alert alert-danger alert-dismissible">
				                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
				                            <?php echo $docalert2; ?>
				                        </div>
				                    </div>
				                <?php } ?>
                                <div class="form-group"> <!-- CB DOCUMENT -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Code ?> </label>
		                          	<div class="col-sm-9">
	                                    <input type="text" class="form-control" name="BR_CODE" id="BR_CODE" value="<?php echo $DocNumber; ?>" />
			                            <input type="hidden" name="BR_NUM" id="BR_NUM" value="<?php echo $DocNumber; ?>">
		                          	</div>
                                </div>
                                <div class="form-group"> <!-- CB_DATE -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
		                          	<div class="col-sm-9">
	                                    <div class="input-group date">
			                            	<div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
			                                <?php
												if($task == 'add')
												{
													?>
			                            			<input type="text" name="BR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $BR_DATE; ?>" style="width:105px" onChange="getBR_NUM(this.value)">
			                                        <?php
												}
												else
												{
													?>
			                            			<input type="text" name="BR_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $BR_DATE; ?>" style="width:105px">
			                                        <?php
												}
											?>
			                            </div>
		                          	</div>
                                </div>
								<script>
		                            function getBR_NUM(selDate)
		                            {
		                                document.getElementById('CBDate').value = selDate;
		                                document.getElementById('dateClass').click();
		                            }
		            
		                            $(document).ready(function()
		                            {
		                                $(".tombol-date").click(function()
		                                {
		                                    var add_CB	= "<?php echo $secGenCode; ?>";
		                                    var formAction 	= $('#sendDate')[0].action;
		                                    var data = $('.form-user').serialize();
		                                    $.ajax({
		                                        type: 'POST',
		                                        url: formAction,
		                                        data: data,
		                                        success: function(response)
		                                        {
		                                            var myarr = response.split("~");
		                                            document.getElementById('BR_NUM1').value = myarr[0];
		                                            document.getElementById('BR_NUM').value = myarr[1];
		                                        }
		                                    });
		                                });
		                            });
		                        </script>
		                    	<?php
									if($BR_RECTYPE == 'SAL')
									{
										$countOWN	= "tbl_sinv_header A
														INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
														WHERE A.SINV_STAT = '3' AND A.SINV_PAYSTAT != 'FR'";
										if($task == 'edit')
										{
											/*$countOWN	= "tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6) AND A.SINV_PAYSTAT != 'FR'";*/
											$countOWN	= "tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6)";
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.CUST_CODE AS own_Code, '' AS own_Title,
																B.CUST_DESC AS own_Name
															FROM tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT = '3' AND A.SINV_PAYSTAT != 'FR'";
											if($task == 'edit')
											{
												/*$sqlOWN	= "SELECT DISTINCT B.CUST_CODE AS own_Code, '' AS own_Title,
																B.CUST_DESC AS own_Name
															FROM tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6) AND A.SINV_PAYSTAT != 'FR'";*/
												$sqlOWN	= "SELECT DISTINCT B.CUST_CODE AS own_Code, '' AS own_Title,
																B.CUST_DESC AS own_Name
															FROM tbl_sinv_header A
																INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
															WHERE A.SINV_STAT IN (3,6)";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'PRJ')
									{
										$countOWN	= "tbl_projinv_header A
														INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
														WHERE A.PINV_STAT = '3' AND A.PINV_CAT != '1'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT != '1'";
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT = '3' AND A.PINV_CAT != '1'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT != '1'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'DP')
									{
										$countOWN	= "tbl_projinv_header A
														INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
														WHERE A.PINV_STAT = '3' AND A.PINV_CAT = '1'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT = '1'";
											
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
														FROM tbl_projinv_header A
															INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
														WHERE A.PINV_STAT = '3' AND A.PINV_CAT = '1'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT = '1'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
									elseif($BR_RECTYPE == 'OTH')
									{
										$countOWN	= "tbl_journalheader_pd A WHERE A.GEJ_STAT = '3' AND PRJCODE = '$PRJCODE'";
										if($task == 'edit')
										{
											$countOWN	= "tbl_journalheader_pd A WHERE A.GEJ_STAT IN ('3','6') AND PRJCODE = '$PRJCODE'";
											
										}
										$resCOWN	= $this->db->count_all($countOWN);
										if($resCOWN > 0)
										{
											$sqlOWN		= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
														FROM tbl_journalheader_pd A WHERE A.GEJ_STAT = '3' AND PRJCODE = '$PRJCODE'";
											if($task == 'edit')
											{
												$sqlOWN	= "SELECT DISTINCT B.own_Code, B.own_Title, B.own_Name 
															FROM tbl_projinv_header A
																INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
															WHERE A.PINV_STAT IN (3,6) AND A.PINV_CAT = '1'";
											}
											$resOWN		= $this->db->query($sqlOWN)->result();
										}
									}
								?>
                                <div class="form-group"> <!-- TYPE SOURCE. SEMENTARA HANYA DARI PENJUALAN-->
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $SourceDocument ?> </label>
		                          	<div class="col-sm-3">
	                                    <select name="BR_RECTYPE" id="BR_RECTYPE" class="form-control select2" onChange="selectAccount(this.value)">
			                                <?php if($PRJSCATEG == 1) { ?>
			                                	<option value="DP" <?php if($BR_RECTYPE == 'DP'){ ?> selected <?php } ?>>DP</option>
			                                	<option value="PRJ" <?php if($BR_RECTYPE == 'PRJ'){ ?> selected <?php } ?>>Project</option>
			                                <?php } else { ?>
			                                	<option value="SAL" <?php if($BR_RECTYPE == 'SAL'){ ?> selected <?php } ?>><?php echo $Sales; ?></option>
			                                <?php } ?>
			                                <option value="OTH" <?php if($BR_RECTYPE == 'OTH'){ ?> selected <?php } ?>>Others</option>
			                            </select>
		                          	</div>
		                          	<div class="col-sm-6">
	                                    <select name="BR_PAYFROM" id="BR_PAYFROM" class="form-control select2" onChange="selectAccount(this.value)">
				                            <option value=""> --- </option>
				                            <?php
				                            if($resCOWN > 0)
				                            {
				                            	foreach($resOWN as $row) :
													 $own_Code1		= $row->own_Code;
													 $own_Title1	= $row->own_Title;
													 $own_Name1		= $row->own_Name;
													 $compName		= "$own_Name1 $own_Title1 - $own_Code1";
				                            		?>
				                            <option value="<?php echo $own_Code1; ?>" <?php if($own_Code1 == $BR_PAYFROM) { ?> selected <?php } ?>><?php echo $compName; ?></option>
				                            <?php
				                            	endforeach;
				                            }
				                            ?>
			                          	</select>
		                          	</div>
                                </div>
                                <div class="form-group"> <!-- NOTES -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
		                          	<div class="col-sm-9">
		                          		<textarea name="BR_NOTES" class="form-control" id="BR_NOTES" style="height: 65px"><?php echo $BR_NOTES; ?></textarea>
		                          	</div>
                                </div>
                            </div>
	                    </div>
			        </div>

                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Tujuan Penerimaan</h3>
                            </div>
                            <div class="box-body">
                            	<div class="form-group"> <!-- CB_CURRID, Acc_ID -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $BankAccount ?> </label>
		                          	<div class="col-sm-9">
			                          	<select name="BR_CURRID" id="BR_CURRID" class="form-control" style="display: none;" onChange="selectAccount(this.value)">
			                                <option value="IDR" <?php if($SelCurr == 'IDR'){ ?> selected <?php } ?>>IDR</option>
			                                <option value="USD" <?php if($SelCurr == 'USD'){ ?> selected <?php } ?>>USD</option>
			                            </select>
			                            <select name="selAccount" id="selAccount" class="form-control select2" onChange="selectAccount(this.value)">
			                            	<option value=""> --- </option>
			                                <?php echo $i = 0;
				                                if($countAcc > 0)
				                                {
					                                foreach($vwAcc as $row) :
					                                    $Acc_ID					= $row->Acc_ID;
					                                    $Account_Category 		= $row->Account_Category;
					                                    $Account_Number 		= $row->Account_Number;
					                                    $Account_Name 			= $row->Account_Name;
					                                	?>
						                                    <option value="<?php echo $Account_Number; ?>" <?php if($Account_Number == $selAccount){ ?> selected <?php } ?> >
						                                        <?php echo "$Account_Number &nbsp;&nbsp;$Account_Name"; ?>
						                                    </option>
					                                 	<?php
				                                 	endforeach;
				                                 }
				                                 else
				                                 {
				                                 	?>
				                                    	<option value="none"> --- </option>
				                                    <?php
				                                 }
			                                ?>
			                            </select>
				                        <script>
				                            function selectAccount()
				                            {
												selAccount	= document.getElementById('selAccount').value;
												document.getElementById('AccSelected').value = selAccount;
												BR_CURRIDA	= document.getElementById('BR_CURRID').value;
												document.getElementById('BR_CURRIDA').value = BR_CURRIDA;
												SPLSelected	= document.getElementById('BR_PAYFROM').value;
												document.getElementById('SPLSelected').value = SPLSelected;
												
												BR_RECTYPE	= document.getElementById('BR_RECTYPE').value;
												document.getElementById('BRTSelected').value = BR_RECTYPE;
												
				                                document.frm1.submit1.click();
				                            }
				                        </script>
		                          	</div>
		                        </div>
		                        <?php
		                            $sql1C 		= "tbl_chartaccount A
		                                            INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
		                                            WHERE A.Account_Class IN (3,4)
		                                            AND A.Account_Number = '$selAccount'
		                                            Order by A.Account_Category, A.Account_Number";
		                            $retSQL1C 	= $this->db->count_all($sql1C);
		                            
		                            if($retSQL1C >0)
		                            {
		                                $sql1 = "SELECT A.Base_OpeningBalance, A.Base_Debet, A.Base_Kredit
		                                        FROM tbl_chartaccount A
		                                        INNER JOIN tbl_chartcategory B ON A.Account_Category = B.ChartCategory_ID
		                                        WHERE A.Account_Class IN (3,4)
		                                        AND A.Account_Number = '$selAccount'
		                                        Order by A.Account_Category, A.Account_Number";
		                                $retSQL1 	= $this->db->query($sql1)->result();
		                                foreach($retSQL1 as $row1):
		                                    $opBal		= $row1->Base_OpeningBalance;
		                                    $BaseDebet	= $row1->Base_Debet;
		                                    $BaseKredit	= $row1->Base_Kredit;
		                                endforeach;
		                            }
		                            else
		                            {
		                                $opBal		= 0;
		                                $BaseDebet	= 0;
		                                $BaseKredit	= 0;
		                            }
		                            $ActBal	= $opBal + $BaseDebet - $BaseKredit;
		                            //print "$SelCurr "; print number_format($ActBal, $decFormat);
		                            // Untuk jumlah reserve, cari dari Bank Payment yang belum di Approve dan tidak reject
		                            // Perhatikan IDR atau USD
									$BR_TOTAM		= 0;
									$BR_TOTAM_PPn	= 0;
		                            $sql2 			= "SELECT SUM(BR_TOTAM) AS Tot_AM, SUM(BR_TOTAM_PPn) AS Tot_AMPPn
														FROM tbl_br_header
														WHERE BR_STAT NOT IN (3,5)
														AND BR_CURRID = '$SelCurr'
														AND Acc_ID = '$selAccount'";
		                            $retSQL2 	= $this->db->query($sql2)->result();
		                            foreach($retSQL2 as $row2):
		                                $BR_TOTAM		= $row2->Tot_AM;
		                                $BR_TOTAM_PPn	= $row2->Tot_AMPPn;
		                            endforeach;
		                            $TotReserve		= $BR_TOTAM + $BR_TOTAM_PPn;
		                            // Total Ammount : Total nilai yang saat ini akan dibayarkan
		                            $TotAmmount	= 0;
		                            
		                            // Total Remain
		                            //$TotRemain	= $ActBal - $TotReserve - $TotAmmount;		
		                            $TotRemain	= $ActBal;				 
		                        ?>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp; </label>
		                          	<div class="col-sm-9">
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label ><?php echo $ActualBalance; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-primary btn-xs">
			                                            <?php echo number_format($ActBal, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label ><?php echo $Reserved; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-danger btn-xs">
			                                            <?php echo number_format($TotReserve, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label ><?php echo $Amount; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-warning btn-xs">
			                                            <?php echo number_format($TotAmmount, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-xs-3">
	                                        <div class="form-group">
	                                            <label ><?php echo $Remain; ?></label>
	                                            <div>
	                                            	<a href="" class="btn btn-success btn-xs">
			                                            <?php echo number_format($TotRemain, $decFormat); ?>
			                                        </a>
	                                            </div>
	                                            <input type="hidden" name="TotRemAccount" id="TotRemAccount" value="<?php echo $TotRemain; ?>" class="form-control" style="max-width:80px; text-align:right">
	                                        </div>
	                                    </div>
		                            </div>
		                        </div>
                                <div class="form-group"> <!-- STATUS -->
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
	                                    <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $BR_STAT; ?>" />
			                        	<?php
				                            if($ISAPPROVE == 1)
				                            {
				                            ?>
				                                <select name="BR_STAT" id="BR_STAT" class="form-control select2">
				                                    <option value="1"<?php if($BR_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                    <option value="2"<?php if($BR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                    <option value="3"<?php if($BR_STAT == 3) { ?> selected <?php } ?>>Approve</option>
				                                    <option value="4"<?php if($BR_STAT == 4) { ?> selected <?php } ?>>Revise</option>
				                                    <option value="5"<?php if($BR_STAT == 5) { ?> selected <?php } ?>>Reject</option>
				                                    <option value="6"<?php if($BR_STAT == 6) { ?> selected <?php } ?>>Close</option>
				                                </select>
				                            <?php
				                            }
				                            else
				                            {
				                            ?>
				                                <select name="BR_STAT" id="BR_STAT" class="form-control select2">
				                                    <option value="1"<?php if($BR_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                    <option value="2"<?php if($BR_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                    <option value="3"<?php if($BR_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
				                                </select>
				                            <?php
				                            }
				                        ?>
		                          	</div>
                                </div>
			                    <?php
									$collID		= "$BR_RECTYPE~$BR_PAYFROM";
									$selSource	= site_url('c_finance/c_br180c2cd0d/pall180c2ginvr/?id='.$this->url_encryption_helper->encode_url($collID));
								?>
                                <div class="form-group"> <!-- CHOOSE DOC. -->
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                          	<div class="col-sm-9">
	                                    <div class="form-group has-error" style="display: none;">
	                                        <span class="help-block" style="font-style: italic;">Silahkan pilih dokumen sumber penerimaan</span>
	                                    </div>
	                                    <button class="btn btn-warning" type="button" onClick="selectitem();">
			                        		<i class="fa fa-folder-open"></i>&nbsp;&nbsp;<?php echo $ChooseInvoice; ?>
			                        	</button>
			                        	<input type="hidden" name="BR_TOTAM" id="BR_TOTAM" value="<?php echo $BR_TOTAM; ?>">
			                            <input type="hidden" name="BR_TOTAM_PPn" id="BR_TOTAM_PPn" value="<?php echo $BR_TOTAM_PPn; ?>">
			                        </div>
                                </div>
                                <script>
									var url = "<?php echo $selSource;?>";
									function selectitem()
									{
										BR_PAYFROM	= document.getElementById('BR_PAYFROM').value;
										if(BR_PAYFROM == '')
										{
											swal('<?php echo $alert3; ?>',
											{
												icon: "warning",
											});
											document.getElementById('BR_PAYFROM').focus();
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
		                    </div>
		                </div>
		            </div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                            <tr style="background:#CCCCCC">
		                              	<th width="1%" height="25" rowspan="2" style="text-align:left; vertical-align: middle;">No.</th>
		                              	<th width="8%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $InvoiceNo; ?> </th>
		                              	<th width="40%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Description; ?> </th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $InvoiceAmount; ?> </th>
		                              	<th width="5%" style="text-align:center; display:none">&nbsp;</th>
		                              	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Receipt; ?> </th>
		                              	<th width="35%" style="text-align:center; display:none">&nbsp;</th>
		                              	<th width="30%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
		                            </tr>
		                            <tr style="background:#CCCCCC">
		                              	<th style="text-align:center;"><?php echo "(IDR)"; // $Amount; ?> </th>
		                              	<th style="text-align:center; display:none"><?php echo $PPn; ?> </th>
		                              	<th style="text-align:center;" nowrap><?php echo $Amount; ?></th>
		                              	<th style="text-align:center; display:none" nowrap><?php echo $PPn; ?></th>
		                            </tr>
		                            <?php					
		                            if($task == 'edit')
		                            {
		                                $sqlDET	= "SELECT A.JournalH_Code, A.BR_NUM, A.BR_CODE, A.DocumentNo, A.DocumentRef, 
														A.Description, A.DebCred, A.Acc_ID, A.Inv_Amount, A.Inv_Amount_PPn,
														A.GInv_Amount, A.Amount, A.Amount_PPn, A.GAmount, A.Notes
													FROM tbl_br_detail A
														INNER JOIN tbl_br_header B ON A.JournalH_Code = B.JournalH_Code
													WHERE A.JournalH_Code = '$JournalH_Code'";
		                                // count data
		                                    $resultCount = $this->db->where('JournalH_Code', $JournalH_Code);
		                                    $resultCount = $this->db->count_all_results('tbl_br_detail');
		                                // End count data
		                                $result = $this->db->query($sqlDET)->result();
		                                $i		= 0;
		                                $j		= 0;
		                                if($resultCount > 0)
		                                {
		                                    foreach($result as $row) :
		                                        $currentRow  	= ++$i;
		                                        $JournalH_Code 	= $row->JournalH_Code;
		                                        $BR_NUM 		= $row->BR_NUM;
		                                        $BR_CODE 		= $row->BR_CODE;
		                                        $DocumentNo		= $row->DocumentNo;
		                                        $DocumentRef	= $row->DocumentRef;
		                                        $Description	= $row->Description;
		                                        $DebCred 		= $row->DebCred;
		                                        $Acc_ID 		= $row->Acc_ID;
		                                        $Inv_Amount		= $row->Inv_Amount;
		                                        $Inv_Amount_PPn	= $row->Inv_Amount_PPn;
		                                        $GInv_Amount	= $row->GInv_Amount;
		                                        $Amount 		= $row->Amount;
		                                        $Amount_PPn		= $row->Amount_PPn;
		                                        $GAmount		= $row->GAmount;
		                                        $Notes			= $row->Notes;
												
												$PINVPAIDAM		= 0;
												$sql2 			= "SELECT SUM(A.PINV_PAIDAM) AS PINV_PAIDAM
																	FROM tbl_projinv_header A
																		INNER JOIN tbl_owner B ON A.PINV_OWNER = B.own_Code
																	WHERE A.PINV_OWNER = '$BR_PAYFROM' AND A.PINV_STAT = '3'
																		AND PINV_CODE = '$DocumentRef'";
												$retSQL2 	= $this->db->query($sql2)->result();
												foreach($retSQL2 as $row2):
													$PINVPAIDAM	= $row2->PINV_PAIDAM;
												endforeach;
												$GPINV_REMAIN	= $GInv_Amount - $PINVPAIDAM;
		                            
		                                        if ($j==1) {
		                                            echo "<tr class=zebra1>";
		                                            $j++;
		                                        } else {
		                                            echo "<tr class=zebra2>";
		                                            $j--;
		                                        }
		                                        ?>
		                                        <!-- JournalH_Code, BR_NUM -->
		                                        <tr><td width="1%" height="25" style="text-align:left; vertical-align: middle;">
													<?php
		                                            if($BR_STAT == 1)
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
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>JournalH_Code" name="data[<?php echo $currentRow; ?>][JournalH_Code]" value="<?php echo $BR_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>BR_NUM" name="data[<?php echo $currentRow; ?>][BR_NUM]" value="<?php echo $BR_NUM; ?>" class="form-control" style="max-width:300px;" readonly>
		                                      	</td>
		                                        <!-- BR_CODE, DocumentNo, DocumentRef -->
		                                      	<td width="8%" style="text-align:left; vertical-align: middle;" nowrap>
													<?php echo $DocumentNo; ?>
		                                        	<input type="hidden" id="data[<?php echo $currentRow; ?>][BR_CODE]" name="data[<?php echo $currentRow; ?>][BR_CODE]" value="<?php echo $BR_CODE; ?>" class="form-control" style="max-width:300px;" readonly>
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>DocumentNo" name="data[<?php echo $currentRow; ?>][DocumentNo]" value="<?php echo $DocumentNo; ?>" class="form-control" style="max-width:300px;" readonly>
		                                            <input type="hidden" id="data<?php echo $currentRow; ?>DocumentRef" name="data[<?php echo $currentRow; ?>][DocumentRef]" value="<?php echo $DocumentRef; ?>" class="form-control" style="max-width:300px;" readonly>										</td>
		                                        <!-- Description -->
		                                      	<td width="35%" style="text-align:left; vertical-align: middle;">
		                                        	<?php echo $Description; ?>
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Description]" id="data<?php echo $currentRow; ?>Description" value="<?php echo $Description; ?>" class="form-control" style="max-width:600px;" >                                      	</td>
		                                        <td width="9%" style="text-align:right; vertical-align: middle;" nowrap>
		                                        	<?php echo number_format($GInv_Amount, $decFormat); ?>
		                                        	<input type="hidden" name="INV_Amount<?php echo $currentRow; ?>" id="INV_Amount<?php echo $currentRow; ?>" value="<?php echo number_format($Inv_Amount, $decFormat); ?>" class="form-control" style="min-width:120px; max-width:200px; text-align:right" size="20" disabled >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Inv_Amount]" id="data<?php echo $currentRow; ?>Inv_Amount" value="<?php echo $Inv_Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][GInv_Amount]" id="data<?php echo $currentRow; ?>GInv_Amount" size="10" value="<?php echo $GInv_Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                        </td>
		                                        <td width="7%" style="text-align:right; display:none" nowrap><input type="text" name="INV_Amount_PPn<?php echo $currentRow; ?>" id="INV_Amount_PPn<?php echo $currentRow; ?>" value="<?php echo number_format($Inv_Amount_PPn, $decFormat); ?>" size="15" class="form-control" style="min-width:110px; max-width:200px; text-align:right" disabled >
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Inv_Amount_PPn]" id="data<?php echo $currentRow; ?>Inv_Amount_PPn" value="<?php echo $Inv_Amount_PPn; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" onBlur="getConvertion(this,<?php echo $currentRow; ?>);" >
		                                        </td>
		                                      	<!-- Amount -->
		                                      	<td width="11%" style="text-align:right; vertical-align: middle;" nowrap>
		                                      		<?php if($BR_STAT == 1 || $BR_STAT == 4) { ?>
		                                        		<input type="text" name="GAmount<?php echo $currentRow; ?>" id="GAmount<?php echo $currentRow; ?>" value="<?php echo number_format($GAmount, 2); ?>" class="form-control" style="min-width:150px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmount(this,<?php echo $currentRow; ?>);" >
		                                      		<?php } else { ?>
		                                        		<?php echo number_format($GAmount, $decFormat); ?>
		                                        		<input type="hidden" name="GAmount<?php echo $currentRow; ?>" id="GAmount<?php echo $currentRow; ?>" value="<?php echo number_format($GAmount, 2); ?>" class="form-control" style="min-width:150px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmount(this,<?php echo $currentRow; ?>);" >
		                                      		<?php } ?>
		                                           	<input type="hidden" name="data[<?php echo $currentRow; ?>][Amount]" id="data<?php echo $currentRow; ?>Amount" size="10" value="<?php echo $Amount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][GAmount]" id="data<?php echo $currentRow; ?>GAmount" size="10" value="<?php echo $GAmount; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][GInv_Remain]" id="data<?php echo $currentRow; ?>GInv_Remain" size="10" value="<?php echo $GPINV_REMAIN; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
		                                        </td>
		                                        <!-- Amount_PPn -->
		                                      	<td width="9%" style="text-align:right; display:none" nowrap>
		                                        	<input type="text" name="Amount_PPn<?php echo $currentRow; ?>" id="Amount_PPn<?php echo $currentRow; ?>" value="<?php echo number_format($Amount_PPn, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:200px; text-align:right" onKeyPress="return isIntOnlyNew(event);"  onBlur="chgRecAmountPPn(this,<?php echo $currentRow; ?>);" size="15" >
		                                            <input type="hidden" name="data[<?php echo $currentRow; ?>][Amount_PPn]" id="data<?php echo $currentRow; ?>Amount_PPn" value="<?php echo $Amount_PPn; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >										</td>
		                                      	<td width="20%" style="text-align:center; vertical-align: middle;">
		                                        	<input type="hidden" name="data[<?php echo $currentRow; ?>][Notes]" id="data<?php echo $currentRow; ?>Notes" value="<?php echo $Notes; ?>" class="form-control" style="max-width:500px;" >
		                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">                                        </td>
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
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
						<?php
							$ShowBtn	= 0;
							if($ISCREATE == 1 && ($BR_STAT == 1 || $BR_STAT == 4))
							{
								$ShowBtn	= 1;
							}
							else if($ISAPPROVE == 1 && $BR_STAT != 3)
							{
								$ShowBtn	= 1;
							}
							
                            if($ShowBtn == 1)
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
                                        <i class="fa fa-save"></i></button>&nbsp;
                                    <?php
                                }
                            }
                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                        ?>
	                </div>
			    </form>
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
	$('#datepicker').datepicker({
	  autoclose: true,
	  endDate: '+1d'
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
	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}
		
		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '') 
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}
		
		if (selectedRows==NumOfRows) 
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');		
		var objTable, objTR, objTD, intIndex, arrItem;
		var BR_NUMx 	= "<?php echo $DocNumber; ?>";
		
		var BR_CODEx 	= "<?php echo $BR_CODE; ?>";
		ilvl = arrItem[1];
		
		var decFormat		= document.getElementById('decFormat').value;
		//validateDouble(arrItem[0],arrItem[1])
		//if(validateDouble(arrItem[0],arrItem[1]))
		//{
			//swal("Double Item for " + arrItem[0]);
			//return;
		//}
		
		PINV_CODE 			= arrItem[0];
		PRINV_DESC 			= arrItem[1];
		PINV_AMOUNT 		= arrItem[2];
		PINV_AMOUNT_PPn		= arrItem[3];
		PINV_AMOUNT_PPh		= arrItem[4];
		PINV_AMOUNT_OTH		= arrItem[5];
		PINV_NOTES 			= arrItem[6];
		PINV_Number 		= arrItem[7];
		PINV_MANNO 			= arrItem[8];
		GTOT_INVOICE		= arrItem[9];
		GTOT_INVREM			= arrItem[10];  // NILAI SISA
		
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
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="data'+intIndex+'JournalH_Code" name="data['+intIndex+'][JournalH_Code]" value="'+BR_NUMx+'" class="form-control" style="max-width:300px;" readonly>';
		
		// No. Realisasi Faktur
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+PINV_MANNO+'<input type="hidden" id="data'+intIndex+'BR_NUM" name="data['+intIndex+'][BR_NUM]" value="'+BR_NUMx+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data['+intIndex+'][BR_CODE]" name="data['+intIndex+'][BR_CODE]" value="'+BR_CODEx+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'DocumentNo" name="data['+intIndex+'][DocumentNo]" value="'+PINV_CODE+'" class="form-control" style="max-width:300px;" readonly><input type="hidden" id="data'+intIndex+'DocumentRef" name="data['+intIndex+'][DocumentRef]" value="'+PINV_Number+'" class="form-control" style="max-width:300px;" readonly>';
		
		// Deskripsi
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.noWrap = true;
		objTD.innerHTML = ''+PRINV_DESC+'<input type="hidden" name="data['+intIndex+'][Description]" id="data'+intIndex+'Description" size="10" value="'+PRINV_DESC+'" class="form-control" style="max-width:300px;" >';
		
		// Invoice Realization Amount
		GTOT_INVOICEV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT_INVOICE)),decFormat));
		PINV_AMOUNTV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PINV_AMOUNT)),decFormat));		
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = ''+GTOT_INVOICEV+'<input type="hidden" name="Inv_Amount'+intIndex+'" id="Inv_Amount'+intIndex+'" value="'+PINV_AMOUNT+'" class="form-control" style="min-width:120px; max-width:150px; text-align:right" ><input type="hidden" name="data['+intIndex+'][Inv_Amount]" id="data'+intIndex+'Inv_Amount" size="10" value="'+PINV_AMOUNT+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data['+intIndex+'][GInv_Amount]" id="data'+intIndex+'GInv_Amount" size="10" value="'+GTOT_INVOICE+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data['+intIndex+'][GInv_Remain]" id="data'+intIndex+'GInv_Remain" size="10" value="'+GTOT_INVREM+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Invoice Realization Amount PPn
		PINV_AMOUNT_PPnV= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PINV_AMOUNT_PPn)),decFormat));
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.style.display = 'none';
		objTD.innerHTML = ''+PINV_AMOUNT_PPnV+'<input type="hidden" name="Inv_Amount_PPn'+intIndex+'" id="Inv_Amount_PPn'+intIndex+'" value="'+PINV_AMOUNT_PPn+'" class="form-control" style="min-width:110px; max-width:130px; text-align:right" ><input type="hidden" name="data['+intIndex+'][Inv_Amount_PPn]" id="data'+intIndex+'Inv_Amount_PPn" size="10" value="'+PINV_AMOUNT_PPn+'" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Receipt - Amount
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" name="GAmount'+intIndex+'" id="GAmount'+intIndex+'" value="0.00" class="form-control" style="min-width:150px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmount(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][Amount]" id="data'+intIndex+'Amount" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data['+intIndex+'][GAmount]" id="data'+intIndex+'GAmount" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Receipt - Amount PPn
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.style.display = 'none';
		objTD.innerHTML = '<input type="text" name="Amount_PPn'+intIndex+'" id="Amount_PPn'+intIndex+'" size="10" value="0.00" class="form-control" style="min-width:120px; max-width:150px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgRecAmountPPn(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][Amount_PPn]" id="data'+intIndex+'Amount_PPn" size="10" value="0.00" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >';
		
		// Keterangan
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = '<input type="text" name="data['+intIndex+'][Notes]" id="data'+intIndex+'Notes" size="15" value="" class="form-control" style="max-width:300px;" ><input type="hidden" name="totalrow" id="totalrow" value="'+intIndex+'">';
		
		document.getElementById('Inv_Amount'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PINV_AMOUNT)),decFormat));
		document.getElementById('Inv_Amount_PPn'+intIndex).value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PINV_AMOUNT_PPn)),decFormat));
		/*document.getElementById('Amount'+intIndex).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PINV_AMOUNT)),decFormat));
		document.getElementById('Amount_PPn'+intIndex).value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PINV_AMOUNT_PPn)),decFormat));*/
		document.getElementById('totalrow').value = intIndex;
	}
	
	function chgRecAmount(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		
		var thisVal			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'GAmount').value 	= thisVal;
		document.getElementById('GAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		
		//var AmountPPn		= 0.1 * parseFloat(thisVal);
		//document.getElementById('data'+row+'Amount_PPn').value 	= AmountPPn;
		//document.getElementById('Amount_PPn'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AmountPPn)),decFormat));
		
		var GInv_Remain		= document.getElementById('data'+row+'GInv_Remain').value;
		var GInv_RemainV	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GInv_Remain)),decFormat));
		if(parseFloat(thisVal) > parseFloat(GInv_Remain))
		{
			swal('Your minimum receipt is '+GInv_RemainV,
			{
				icon: "warning",
			});
			document.getElementById('data'+row+'GAmount').value 	= GInv_Remain;
			document.getElementById('GAmount'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GInv_Remain)),decFormat));
		}
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PPn	= 0;
		for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var Amount_PPn		= document.getElementById('data'+row+'Amount_PPn').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PPn	= parseFloat(TOT_AMOUNT_PPn) + parseFloat(Amount_PPn);
		}
		document.getElementById('BR_TOTAM').value		= parseFloat(TOT_AMOUNT);
		document.getElementById('BR_TOTAM_PPn').value	= parseFloat(TOT_AMOUNT_PPn);		
	}
	
	function chgRecAmountPPn(thisVal1, row)
	{
		var decFormat		= document.getElementById('decFormat').value;
		var totRow 			= document.getElementById('totalrow').value;
		
		var thisVal			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'Amount_PPn').value 	= thisVal;
		document.getElementById('Amount_PPn'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)),decFormat));
		
		var TOT_AMOUNT		= 0;
		var TOT_AMOUNT_PPn	= 0;
		/*for(i=1;i<=totRow;i++)
		{
			var Amount			= document.getElementById('data'+row+'Amount').value;
			var Amount_PPn		= document.getElementById('data'+row+'Amount_PPn').value;
			var TOT_AMOUNT		= parseFloat(TOT_AMOUNT) + parseFloat(Amount);
			var TOT_AMOUNT_PPn	= parseFloat(TOT_AMOUNT_PPn) + parseFloat(Amount_PPn);
		}*/
		document.getElementById('BR_TOTAM').value		= parseFloat(thisVal);
		document.getElementById('BR_TOTAM_PPn').value	= parseFloat(TOT_AMOUNT_PPn);
	}
	
	function checkInp(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var BR_NOTES 	= document.getElementById('BR_NOTES').value;

		if(BR_NOTES == '')
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
			.then(function()
			{
				swal.close();
				$('#BR_NOTES').focus();
			});
			return false;
		}
		
		if(totrow == 0)
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			});
			return false;		
		}
		
		for(i=1;i<=totrow;i++)
		{
			var Amount = parseFloat(document.getElementById('GAmount'+i).value);
			if(Amount == 0)
			{
				swal('<?php echo $alert2; ?>',
				{
					icon: "warning",
				})
				.then(function()
				{
					swal.close();
					document.getElementById('GAmount'+i).value = '0';
					document.getElementById('GAmount'+i).focus();
				});
				return false;
			}
		}

		var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
		}
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
	
	function validateDouble(vcode, SNCODE) 
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
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
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
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
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