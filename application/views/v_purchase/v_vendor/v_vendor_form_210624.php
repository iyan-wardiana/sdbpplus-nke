<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 November 2017
 * File Name	= v_vendor_form.php
 * Location		= -
*/
?>
<?php
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

if($task == 'add')
{
	/*foreach($viewDocPattern as $row) :
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
	$Pattern_Position	= 'Especially';	
	if($Pattern_Position == 'Especially')
	{
		$Pattern_YearAktive = date('Y');
		$Pattern_MonthAktive = date('m');
		$Pattern_DateAktive = date('d');
	}
	
	$sql = "SELECT MAX(Patt_Number) as maxNumber FROM tbl_supplier";
	$result = $this->db->query($sql)->result();
	
	foreach($result as $row) :
		$myMax = $row->maxNumber;
		$myMax = $myMax+1;
	endforeach;	
		
	$lastPatternNumb = $myMax;
	$lastPatternNumb1 = $myMax;
	$len = strlen($lastPatternNumb);
	$nol = '';
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
	$DocNumber = "SUPL$lastPatternNumb";*/
	
	$SPLCAT	= 0;
	if(isset($_POST['SPLCAT1']))
	{
		$SPLCAT		= $_POST['SPLCAT1'];
	}
	
	$sql 	= "tbl_supplier";
	$result = $this->db->count_all($sql);
	$myMax 	= $result+1;
	
	$Pattern_Length	= 6;
	$len = strlen($myMax);
	$nol = '';
	if($Pattern_Length==3)
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
	
	$lastPatternNumb = $nol.$myMax;
	$DocNumber 		= "$SPLCAT.$lastPatternNumb";
	$Patt_Number	= $myMax;
	
	$SPLCODE 		= "$DocNumber";
	$SPLDESC		= '';
	$SPLCAT			= $SPLCAT;
	$SPLADD1		= '';
	$SPLKOTA		= '';
	$SPLNPWP		= '';
	$SPLPERS		= '';
	$SPLTELP		= '';
	$SPLMAIL		= '';
	$SPLNOREK		= '';
	$SPLSCOPE		= '';
	$SPLNMREK		= '';
	$SPLBANK		= '';
	$SPLOTHR		= '';
	$SPLOTHR2		= 1;
	$SPLTOP			= 0;
	$SPLTOPD		= 0;
	$SPLSTAT		= 1;
}
else
{
	$SPLCODE		= $default['SPLCODE'];
	$SPLDESC		= $default['SPLDESC'];
	$SPLCAT			= $default['SPLCAT'];
	if(isset($_POST['SPLCAT1']))
	{
		$SPLCAT		= $_POST['SPLCAT1'];
	}
	$SPLADD1		= $default['SPLADD1'];
	$SPLKOTA		= $default['SPLKOTA'];
	$SPLNPWP		= $default['SPLNPWP'];
	$SPLPERS		= $default['SPLPERS'];
	$SPLTELP		= $default['SPLTELP'];
	$SPLMAIL		= $default['SPLMAIL'];
	$SPLNOREK		= $default['SPLNOREK'];
	$SPLSCOPE		= $default['SPLSCOPE'];
	$SPLNMREK		= $default['SPLNMREK'];
	$SPLBANK		= $default['SPLBANK'];
	$SPLOTHR		= $default['SPLOTHR'];
	$SPLOTHR2		= $default['SPLOTHR2'];
	$SPLTOP			= $default['SPLTOP'];
	$SPLTOPD		= $default['SPLTOPD'];
	$SPLSTAT		= $default['SPLSTAT'];
	$Patt_Number	= $default['Patt_Number'];
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
            $vers 	= $this->session->userdata['vers'];

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
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'Phone')$Phone = $LangTransl;
			if($TranslCode == 'City')$City = $LangTransl;
			if($TranslCode == 'ContactPersonName')$ContactPersonName = $LangTransl;
			if($TranslCode == 'Email')$Email = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'FuncDesc')$FuncDesc = $LangTransl;
			if($TranslCode == 'Others')$Others = $LangTransl;
			if($TranslCode == 'Scope')$Scope = $LangTransl;
			if($TranslCode == 'RekNo')$RekNo = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'BankName')$BankName = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
			if($TranslCode == 'PayTenor')$PayTenor = $LangTransl;
			if($TranslCode == 'Day')$Day = $LangTransl;
			if($TranslCode == 'SupplierName')$SupplierName = $LangTransl;
			if($TranslCode == 'VendAddress')$VendAddress = $LangTransl;
			if($TranslCode == 'venCatEmpty')$venCatEmpty = $LangTransl;
			if($TranslCode == 'suplNmEmpty')$suplNmEmpty = $LangTransl;
			if($TranslCode == 'payInfonOth')$payInfonOth = $LangTransl;
			if($TranslCode == 'totPurch')$totPurch = $LangTransl;
			if($TranslCode == 'totPaid')$totPaid = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Paid')$Paid = $LangTransl;
			if($TranslCode == 'NotPayment')$NotPayment = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$h1_title	= "Supplier";
			$h2_title	= "Pembelian";
		}
		else
		{
			$h1_title	= "Supplier";
			$h2_title	= "Purchase";
		}

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header" style="display: none;">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/supplier_add.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $h2_title; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<?php
			$DEB_T 	= 0;
			$DEB_P 	= 0;
			$sTOT	= "SELECT SUM(INV_AMOUNT) AS DEB_T, SUM(INV_AMOUNT_PAID) AS DEB_P FROM tbl_pinv_header WHERE SPLCODE = '$SPLCODE'";
			$qTOT	= $this->db->query($sTOT)->result();
			foreach($qTOT as $rTOT) :
				$DEB_T	= $rTOT->DEB_T;
				$DEB_P	= $rTOT->DEB_P;
			endforeach;

			$DEB_R 		= $DEB_T - $DEB_P;

			$DEB_TP 	= $DEB_T;
			if($DEB_T == 0)
				$DEB_TP	= 1;

	        $DBT_PERC	= number_format($DEB_R / $DEB_TP * 100, 2);
	        $DBP_PERC	= number_format($DEB_P / $DEB_TP * 100, 2);

            // ----------- HUTANG
				if($DEB_T < 1000)									// Ratusan
	            {
	            	$DEB_TV = number_format($DEB_T / 1, 2);
	            	$DBTCOD	= "";
	            }
				elseif($DEB_T < 1000000)							// Juta per Seribu
	            {
	            	$DEB_TV = number_format($DEB_T / 1000, 2);
	            	$DBTCOD	= " RB";
	            }
				elseif($DEB_T < 1000000000)							// Miliar per Sejuta
	            {
	            	$DEB_TV = number_format($DEB_T / 1000000, 2);
	            	$DBTCOD	= " JT";
	            }
	            elseif($DEB_T < 1000000000000)						// Triliun per Semiliar
	            {
	            	$DEB_TV = number_format($DEB_T / 1000000000, 2);
	            	$DBTCOD	= " M";
	            }
	            else
	            {
	            	$DEB_TV = number_format($DEB_T / 1000000000000, 2);
	            	$DBTCOD	= " T";
	            }
	            $DBT_V 		= $DEB_TV.$DBTCOD;

            // ----------- DIBAYAR
				if($DEB_P < 1000)									// Ratusan
	            {
	            	$DEB_PV = number_format($DEB_P / 1, 2);
	            	$DBPCOD	= "";
	            }
				elseif($DEB_P < 1000000)							// Juta per Seribu
	            {
	            	$DEB_PV = number_format($DEB_P / 1000, 2);
	            	$DBPCOD	= " RB";
	            }
	            elseif($DEB_P < 1000000000)							// Miliar per Sejuta
	            {
	            	$DEB_PV = number_format($DEB_P / 1000000, 2);
	            	$DBPCOD	= " JT";
	            }
	            elseif($DEB_P < 1000000000000)						// Triliun per Semiliar
	            {
	            	$DEB_PV = number_format($DEB_P / 1000000000, 2);
	            	$DBPCOD	= " M";
	            }
	            else
	            {
	            	$DEB_PV = number_format($DEB_P / 1000000000000, 2);
	            	$DBPCOD	= " T";
	            }
	            $DBP_V 		= $DEB_PV.$DBPCOD;

            // ----------- SISA
	            $PM 		= "";
	            if($DEB_R < 0)
	            {
	            	$PM 	= "-";
	            	$DEB_R 	= abs($DEB_R);
	            }

				if($DEB_R < 1000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1, 2);
	            	$DBRCOD	= "";
	            }
				elseif($DEB_R < 1000000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1000, 2);
	            	$DBRCOD	= " RB";
	            }
	            elseif($DEB_R < 1000000000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1000000, 2);
	            	$DBRCOD	= " JT";
	            }
	            elseif($DEB_R < 1000000000000)
	            {
	            	$DEB_RV = number_format($DEB_R / 1000000000, 2);
	            	$DBRCOD	= " M";
	            }
	            else
	            {
	            	$DEB_RV = number_format($DEB_R / 1000000000000, 2);
	            	$DBRCOD	= " T";
	            }
	            $DBR_V 		= $PM.$DEB_RV.$DBRCOD;
		?>
		<section class="content">
			<div class="row">
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-blue-gradient"><i class="glyphicon glyphicon-user"></i></span>
						<div class="info-box-content">
							<span class="info-box-text" style="font-weight: bold;"><?php echo $SPLDESC; ?></span>
							<span class="info-box-text"><?php echo $SPLKOTA; ?></span>
							<span class="progress-description"><?php echo $SPLTELP; ?></span>
							<span class="progress-description"><?php echo $SPLMAIL; ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-red-gradient">
						<span class="info-box-icon"><i class="glyphicon glyphicon-th-list"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $totPurch; ?></span>
							<span class="info-box-number"><?php echo $DBT_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$DBP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								<?=$DBT_PERC?> % <?php echo $NotPayment; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-green-gradient">
						<span class="info-box-icon"><i class="glyphicon glyphicon-ok"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $totPaid; ?></span>
							<span class="info-box-number"><?php echo $DBP_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$DBP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								<?=$DBP_PERC?> % <?php echo $Paid; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<div class="info-box bg-yellow-gradient">
						<span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $Remain; ?></span>
							<span class="info-box-number"><?php echo $DBR_V; ?></span>

							<div class="progress">
								<div class="progress-bar" style="width: <?=$DBP_PERC?>%"></div>
							</div>
							<span class="progress-description">
								Total <?php echo $NotPayment; ?>
							</span>
						</div>
					</div>
				</div>
			</div>

		    <div class="row">
                <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
                    <input type="text" name="SPLCAT1" id="SPLCAT1" value="<?php echo $SPLCAT; ?>" />
                    <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
                </form>
                <?php
                    $urlGetData	= base_url().'index.php/c_purchase/c_v3N/gLastCd/';
                ?>
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                		<input type="hidden" name="Patt_Number" id="Patt_Number" class="form-control" value="<?php echo $Patt_Number; ?>" />
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="SPLCODE" id="SPLCODE" class="form-control" value="<?php echo $SPLCODE; ?>" />
		                            	<input type="text" name="SPLCODE1" id="SPLCODE1" class="form-control" value="<?php echo $SPLCODE; ?>" disabled />   
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Category; ?></label>
		                          	<div class="col-sm-9">
		                            	<select name="SPLCAT" id="SPLCAT" class="form-control select2" <?php if($task == 'add') { ?> onChange="selCAT(this.value)" <?php } ?>>
											<?php
			                                    $sql 	= "SELECT VendCat_Code, VendCat_Name FROM tbl_vendcat";
			                                    $result = $this->db->query($sql)->result();
			                                    $i 		= 0;
			                                    foreach($result as $row) :
													$SPLCAT2	= $row->VendCat_Code;
													$SPLDESC1	= $row->VendCat_Name;
			                                        ?>
			                                        <option value="<?php echo $SPLCAT2; ?>" <?php if($SPLCAT == $SPLCAT2) { ?> selected <?php } ?>>
			                                        <?php echo $SPLDESC1; ?></option>
			                                        <?php
			                                    endforeach;
			                                ?>
			                            </select>
		                          	</div>
		                        </div>
								<script>
		                            function selCAT(SPLCAT) 
		                            {
		                                /*document.getElementById("SPLCAT1").value = SPLCAT;
		                                document.frmsrch1.submitSrch1.click();*/
		                                $.ajax({
		                                    type: 'POST',
		                                    url: '<?php echo $urlGetData; ?>',
		                                    data: $('#frm').serialize(),
		                                    success: function(response)
		                                    {
		                                        document.getElementById('SPLCODE').value 	= response;
		                                        document.getElementById('SPLCODE1').value 	= response;
		                                    }
		                                });
		                            }
		                        </script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Name; ?></label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" name="SPLDESC1" id="SPLDESC1" value="<?php echo $SPLDESC; ?>" />
		                            	<input type="text" class="form-control" name="SPLDESC" id="SPLDESC" placeholder="<?php echo $SupplierName; ?>" value="<?php echo $SPLDESC; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Scope; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLSCOPE" id="SPLSCOPE" placeholder="<?php echo $Scope; ?>" value="<?php echo "$SPLSCOPE"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $VendAddress; ?></label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="SPLADD1"  id="SPLADD1" style="height:65px" placeholder="<?php echo $VendAddress; ?>"><?php echo $SPLADD1; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $City; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLKOTA" id="SPLKOTA" placeholder="<?php echo $City; ?>"  value="<?php echo $SPLKOTA; ?>" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label">NPWP</label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLNPWP" id="SPLNPWP" placeholder="NPWP" value="<?php echo $SPLNPWP; ?>" onkeypress="return isIntOnlyNew(event)" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ContactPersonName; ?> / Telp.</label>
		                            <div class="col-sm-6">
		                                <input type="text" class="form-control" name="SPLPERS" id="SPLPERS" placeholder="<?php echo $ContactPersonName; ?>" value="<?php echo $SPLPERS; ?>" />
		                            </div>
		                            <div class="col-sm-3">
		                                <input type="text" class="form-control" name="SPLTELP" id="SPLTELP" placeholder="<?php echo $Phone; ?>" value="<?php echo $SPLTELP; ?>" onkeypress="return isIntOnlyNew(event)" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Email; ?></label>
		                            <div class="col-sm-9">
		                                <input type="email" class="form-control" name="SPLMAIL" id="SPLMAIL" placeholder="Email" value="<?php echo "$SPLMAIL"; ?>">
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-info-circle"></i>
								<h3 class="box-title"><?php echo $payInfonOth; ?></h3>
							</div>
							<div class="box-body">
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $RekNo; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLNOREK" id="SPLNOREK" placeholder="<?php echo $RekNo; ?>" value="<?php echo "$SPLNOREK"; ?>" onkeypress="return isIntOnlyNew(event)" >
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $AccountName; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLNMREK" id="SPLNMREK" placeholder="<?php echo $AccountName; ?>" value="<?php echo "$SPLNMREK"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $BankName; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLBANK" id="SPLBANK" placeholder="<?php echo $BankName; ?>" value="<?php echo "$SPLBANK"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $Others; ?></label>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control" name="SPLOTHR" id="SPLOTHR" placeholder="Informasi Lain" value="<?php echo "$SPLOTHR"; ?>">
		                            </div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $FuncDesc; ?></label>
		                          	<div class="col-sm-9">
		                            	<select name="SPLOTHR2" id="SPLOTHR2" class="form-control select2" onChange="getOth(this.value)">
		                                    <option value=""> -- </option>
		                                    <option value="Area Manager" <?php if($SPLOTHR2 == 'Area Manager') { ?> selected <?php } ?>>Area Manager</option>
		                                    <option value="Direktur" <?php if($SPLOTHR2 == 'Direktur') { ?> selected <?php } ?>>Direktur</option>
		                                    <option value="Direktur Operasional" <?php if($SPLOTHR2 == 'Direktur Operasional') { ?> selected <?php } ?>>Direktur Operasional</option>
		                                    <option value=">Direktur Utama" <?php if($SPLOTHR2 == '>Direktur Utama') { ?> selected <?php } ?>>Direktur Utama</option>
		                                    <option value="Marketing Manager" <?php if($SPLOTHR2 == 'Marketing Manager') { ?> selected <?php } ?>>Marketing Manager</option>
		                                    <option value="Pemilik Alat" <?php if($SPLOTHR2 == 'Pemilik Alat') { ?> selected <?php } ?>>Pemilik Alat</option>
		                                    <option value="Pribadi" <?php if($SPLOTHR2 == 'Pribadi') { ?> selected <?php } ?>>Pribadi</option>
		                                    <option value="Sales Manager" <?php if($SPLOTHR2 == 'Sales Manager') { ?> selected <?php } ?>>Sales Manager</option>
		                                </select>
		                          	</div>
		                        </div>
		                        <script>
									function getOth(SPLOTHR2)
									{
										if(SPLOTHR2 == 5)
										{
											//$("#SPLDESC").val() = $("#SPLPERS").val();
											document.getElementById('SPLDESC').value = document.getElementById('SPLPERS').value;
										}
										else
										{
											document.getElementById('SPLDESC').value = document.getElementById('SPLDESC1').value
										}
									}
								</script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PaymentType ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="SPLTOP" id="SPLTOP" class="form-control select2" >
		                                    <option value="0" <?php if($SPLTOP == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="1" <?php if($SPLTOP == 1) { ?> selected <?php } ?>>Kredit</option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $PayTenor ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="SPLTOPD" id="SPLTOPD" class="form-control select2" >
		                                    <option value="0" <?php if($SPLTOPD == 0) { ?> selected <?php } ?>>Cash</option>
		                                    <option value="7" <?php if($SPLTOPD == 7) { ?> selected <?php } ?>>7 <?php echo $Day; ?></option>
		                                    <option value="15" <?php if($SPLTOPD == 15) { ?> selected <?php } ?>>15 <?php echo $Day; ?></option>
		                                    <option value="30" <?php if($SPLTOPD == 30) { ?> selected <?php } ?>>30 <?php echo $Day; ?></option>
		                                    <option value="45" <?php if($SPLTOPD == 45) { ?> selected <?php } ?>>45 <?php echo $Day; ?></option>
		                                    <option value="60" <?php if($SPLTOPD == 60) { ?> selected <?php } ?>>60 <?php echo $Day; ?></option>
		                                    <option value="75" <?php if($SPLTOPD == 75) { ?> selected <?php } ?>>75 <?php echo $Day; ?></option>
		                                    <option value="90" <?php if($SPLTOPD == 90) { ?> selected <?php } ?>>90 <?php echo $Day; ?></option>
		                                    <option value="120" <?php if($SPLTOPD == 120) { ?> selected <?php } ?>>120 <?php echo $Day; ?></option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                                <select name="SPLSTAT" id="SPLSTAT" class="form-control select2" >
		                                    <option value="1" <?php if($SPLSTAT == 1) { ?> selected <?php } ?>><?php echo $Active ?></option>
		                                    <option value="0" <?php if($SPLSTAT == 0) { ?> selected <?php } ?>><?php echo $Inactive ?></option>
		                                </select>
		                            </div>
		                        </div>
		                        <br>
		                        <div class="form-group">
		                        	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                            <div class="col-sm-9">
		                              	<?php
									
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
										
									
										//echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
										?>
                                        <button class="btn btn-danger" id="tblClose" type="button"><i class="fa fa-reply"></i></button>
                                        <?php
                                            if($LangID == 'IND')
                                            {
                                                $alertCls1  = "Anda yakin?";
                                                $alertCls2  = "Sistem akan mengosongkan data inputan Anda.";
                                                $alertCls3  = "Data Anda aman.";
                                            }
                                            else
                                            {
                                                $alertCls1  = "Are you sure?";
                                                $alertCls2  = "The system will empty the data you entered.";
                                                $alertCls3  = "Your data is safe.";
                                            }
                                        ?>
                                        <script type="text/javascript">
                                            $('#tblClose').on('click',function(e) 
                                            {
                                                // swal({
                                                //       title: "<?php echo $alertCls1; ?>",
                                                //       text: "<?php echo $alertCls2; ?>",
                                                //       //icon: "warning",
                                                //       buttons: ["No", "Yes"],
                                                //       dangerMode: true,
                                                //     })
                                                //     .then((willDelete) => {
                                                //     if (willDelete) 
                                                //     {
                                                //         window.location = "<?php echo $backURL; ?>";
                                                //     } else {
                                                //         swal("<?php echo $alertCls3; ?>", {icon: "success"})
                                                //     }
                                                // });
                                                window.location = "<?php echo $backURL; ?>";
                                            });
                                        </script>
                                        <br>
                                        <br>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </form>
		    </div>
        	<?php
        		$DefID 		= $this->session->userdata['Emp_ID'];
				$act_lnk 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
	function checkInp()
	{
		SPLCAT = document.getElementById('SPLCAT').value;
		if(SPLCAT == 0)
		{
			swal('<?php echo $venCatEmpty; ?>',
			{
				icon: "warning",
			});
			return false;			
		}

		SPLDESC = document.getElementById('SPLDESC').value;
		if(SPLDESC == '')
		{
			swal('<?php echo $suplNmEmpty; ?>',
			{
				icon: "warning",
			});
			return false;			
		}
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