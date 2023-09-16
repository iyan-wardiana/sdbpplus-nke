<?php
	date_default_timezone_set("Asia/Jakarta");
	setlocale(LC_ALL, 'id-ID', 'id_ID');
	/* 
		 * Author		= Dian Hermanto
		 * Create Date	= 22 Junli 2018
		 * File Name	= profit_loss_view.php
		 * Location		= -
	*/
	//$this->load->view('template/head');
	$Periode1 = date('YmdHis');
	if($viewType == 1)
	{
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=LapLabaRugi_$Periode1.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	    
	$sqlApp         = "SELECT * FROM tappname";
	$resultaApp     = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
	    $appName    = $therow->app_name;
	    $comp_init  = strtolower($therow->comp_init);
	    $comp_name  = $therow->comp_name;
	endforeach;

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
	$comp_name 	= $this->session->userdata['comp_name'];

	$PeriodeD 	= date('Y-m-d',strtotime($End_Date));

	$PeriodeD 	= date('Y-m-d',strtotime($End_Date));
	$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODECOL));
	$sqlPRJ		= "SELECT PRJCODE, PRJNAME, PRJCOST, PRJ_LROVH, PRJ_LRPPH, PRJ_LRBNK FROM tbl_project WHERE PRJCODE = '$PRJCODECOL' LIMIT 1";
	$resPRJ		= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE 	= $rowPRJ->PRJCODE;
		$PRJNAME 	= $rowPRJ->PRJNAME;
		$PRJCOST 	= $rowPRJ->PRJCOST;
		$PRJ_LROVH 	= $rowPRJ->PRJ_LROVH;
		$PRJ_LRPPH 	= $rowPRJ->PRJ_LRPPH;
		$PRJ_LRBNK 	= $rowPRJ->PRJ_LRBNK;
	endforeach;
	$PRJCODECOLL	= $PRJCODE;
	$PRJNAMECOLL	= $PRJNAME;

	$DEFAULTVAL		= 0;
	$PERIODE 		= 0;
	$PRJADD 		= 0;
	$SIAPPVAL 		= 0;
	$PROG_PLAN 		= 0;
	$PROG_REAL 		= 0;
	$INV_REAL 		= 0;
	$PROGMC 		= 0;
	$PROGMC_PLAN_X 	= 0;
	$PROGMC_REAL	= 0;
	$SI_PLAN 		= 0;
	$SI_REAL 		= 0;
	$SI_PROYEKSI 	= 0;
	$MC_CAT_PLAN 	= 0;
	$MC_CAT_REAL 	= 0;
	$PROGMC_REALA	= 0;
	$MC_CAT_PROYEKSI = 0;
	$MC_OTH_PLAN	= 0;
	$MC_OTH_REAL 	= 0;
	$MC_OTH_PROYEKSI= 0;
	$KURS_DEV_PLAN 	= 0;
	$KURS_DEV_REAL 	= 0;
	$KURS_DEV_PROYEKSI = 0;
	$ASSURAN_PLAN 	= 0;
	$ASSURAN_REAL 	= 0;
	$ASSURAN_PROYEKSI = 0;
	$CASH_EXPENSE 	= 0;
	$BPP_MTR_PLAN_X 	= 0;
	$BPP_MTR_REAL_X 	= 0;
	$BPP_MTR_PROYEKSI = 0;
	$BPP_UPH_PLAN_X 	= 0;
	$BPP_UPH_REAL_X 	= 0;
	$BPP_UPH_PROYEKSI = 0;
	$BPP_ALAT_PLAN_X	= 0;
	$BPP_ALAT_REAL_X 	= 0;
	$BPP_ALAT_PROYEKSI = 0;
	$BPP_SUBK_PLAN_X 	= 0;
	$BPP_SUBK_REAL_X	= 0;
	$BPP_SUBK_PROYEKSI = 0;
	$BPP_BAU_PLAN_X 	= 0;
	$BPP_BAU_REAL_X 	= 0;
	$BPP_BAU_PROYEKSI = 0;
	$BPP_OTH_PLAN_X 	= 0;
	$BPP_OTH_REAL_X 	= 0;
	$BPP_OTH_PROYEKSI = 0;
	$BPP_I_PLAN_X 	= 0;
	$BPP_I_REAL_X 	= 0;
	$BPP_I_PROYEKSI = 0;
	$STOCK 			= 0;
	$STOCK_MTR 		= 0;
	$STOCK_BBM 		= 0;
	$STOCK_TOOLS 	= 0;
	$EXP_TOOLS 		= 0;
	$EXP_BAU_HOCAB 	= 0;
	$EXP_BUNGA 		= 0;
	$EXP_PPH 		= 0;
	$GRAND_PROFLOS 	= 0;
	$LR_CREATER 	= 0;
	$LR_CREATED 	= 0;

	$PERIODEM	= date('m', strtotime($PeriodeD));
	$PERIODEY	= date('Y', strtotime($PeriodeD));
	$MSELECTED	= (int)$PERIODEM;
	$CURRDATE 	= date('Y-m-d');
	$MBEFORE 	= (int)date('m', strtotime('-1 month', strtotime($CURRDATE)));

	$sqlGetLR	= "SELECT * FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' 
					AND MONTH(PERIODE) = '$PERIODEM' AND YEAR(PERIODE) = '$PERIODEY' LIMIT 1";
	$resGetLR	= $this->db->query($sqlGetLR)->result();

	foreach($resGetLR as $rowLR):
		$PERIODE 		= $rowLR->PERIODE;
		//$PRJCODE 		= $rowLR->PRJCODE;
		//$PRJNAME 		= $rowLR->PRJNAME;
		//$PRJCOST 		= $rowLR->PRJCOST;
		$PRJADD 		= $rowLR->PRJADD;
		$SIAPPVAL 		= $rowLR->SIAPPVAL;
		$PROG_PLAN 		= $rowLR->PROG_PLAN;
		$PROG_REAL 		= $rowLR->PROG_REAL;
		$INV_REAL 		= $rowLR->INV_REAL;
		$PROGMC 		= $rowLR->PROGMC;
		$PROGMC_PLAN_X 	= $rowLR->PROGMC_PLAN;
		$PROGMC_REAL	= $rowLR->PROGMC_REAL;
		$PROGMC_REALA	= $rowLR->PROGMC_REALA;
		$SI_PLAN 		= $rowLR->SI_PLAN;
		$SI_REAL 		= $rowLR->SI_REAL;
		$SI_PROYEKSI 	= $rowLR->SI_PROYEKSI;
		$MC_CAT_PLAN 	= $rowLR->MC_CAT_PLAN;
		$MC_CAT_REAL 	= $rowLR->MC_CAT_REAL;
		$MC_CAT_PROYEKSI = $rowLR->MC_CAT_PROYEKSI;
		$MC_OTH_PLAN	= $rowLR->MC_OTH_PLAN;
		$MC_OTH_REAL 	= $rowLR->MC_OTH_REAL;
		$MC_OTH_PROYEKSI= $rowLR->MC_OTH_PROYEKSI;
		$KURS_DEV_PLAN 	= $rowLR->KURS_DEV_PLAN;
		$KURS_DEV_REAL 	= $rowLR->KURS_DEV_REAL;
		$KURS_DEV_PROYEKSI = $rowLR->KURS_DEV_PROYEKSI;
		$ASSURAN_PLAN 	= $rowLR->ASSURAN_PLAN;
		$ASSURAN_REAL 	= $rowLR->ASSURAN_REAL;
		$ASSURAN_PROYEKSI = $rowLR->ASSURAN_REAL;
		$CASH_EXPENSE 	= $rowLR->CASH_EXPENSE;
		$BPP_MTR_PLAN_X 	= $rowLR->BPP_MTR_PLAN;
		$BPP_MTR_REAL_X 	= $rowLR->BPP_MTR_REAL;
		$BPP_MTR_PROYEKSI = $rowLR->BPP_MTR_PROYEKSI;
		$BPP_UPH_PLAN_X		= $rowLR->BPP_UPH_PLAN;
		$BPP_UPH_REAL_X 	= $rowLR->BPP_UPH_REAL;
		$BPP_UPH_PROYEKSI = $rowLR->BPP_UPH_PROYEKSI;
		$BPP_ALAT_PLAN_X	= $rowLR->BPP_ALAT_PLAN;
		$BPP_ALAT_REAL_X 	= $rowLR->BPP_ALAT_REAL;
		$BPP_ALAT_PROYEKSI = $rowLR->BPP_ALAT_PROYEKSI;
		$BPP_SUBK_PLAN_X	= $rowLR->BPP_SUBK_PLAN;
		$BPP_SUBK_REAL_X	= $rowLR->BPP_SUBK_REAL;
		$BPP_SUBK_PROYEKSI = $rowLR->BPP_SUBK_PROYEKSI;
		$BPP_BAU_PLAN_X 	= $rowLR->BPP_BAU_PLAN;
		$BPP_BAU_REAL_X 	= $rowLR->BPP_BAU_REAL;
		$BPP_BAU_PROYEKSI = $rowLR->BPP_BAU_PROYEKSI;
		$BPP_OTH_PLAN_X 	= $rowLR->BPP_OTH_PLAN;
		$BPP_OTH_REAL_X 	= $rowLR->BPP_OTH_REAL;
		$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
		$BPP_I_PLAN_X 	= $rowLR->BPP_I_PLAN;
		$BPP_I_REAL_X 	= $rowLR->BPP_I_REAL;
		$BPP_I_PROYEKSI = $rowLR->BPP_I_PROYEKSI;
		$STOCK 			= $rowLR->STOCK;
		$STOCK_MTR 		= $rowLR->STOCK_MTR;
		$STOCK_BBM 		= $rowLR->STOCK_BBM;
		$STOCK_TOOLS 	= $rowLR->STOCK_TOOLS;
		$EXP_TOOLS 		= $rowLR->EXP_TOOLS;
		$EXP_BAU_HOCAB 	= $rowLR->EXP_BAU_HOCAB;
		$EXP_BUNGA 		= $rowLR->EXP_BUNGA;
		$EXP_PPH 		= $rowLR->EXP_PPH;
		$GRAND_PROFLOS 	= $rowLR->GRAND_PROFLOS;
		$LR_CREATER 	= $rowLR->LR_CREATER;
		$LR_CREATED 	= $rowLR->LR_CREATED;
	endforeach;

	$End_Date 		= date('Y-m-d',strtotime($End_Date));
	$End_DateBef1	= date('Y-m-d', strtotime('-1 month', strtotime($End_Date)));
	$End_DateBef	= date('Y-m-t', strtotime('-1 day', strtotime($End_DateBef1)));
	$PERIODEM_BEF	= date('m', strtotime($End_DateBef));
	$PERIODEY_BEF	= date('Y', strtotime($End_DateBef));

    $stlLine1 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine2 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden;";
    $stlLine3 		= "border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine4 		= "border-left-style: hidden; border-right-style: hidden; border-top-style: hidden;";
    $stlLine5 		= "border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine6 		= "border-top:double; border-bottom:double; border-left-style: hidden; border-right-style: hidden;";
    $stlLine7 		= "border-bottom:groove; border-right-style: hidden; border-top-style: hidden;";
    $stlLine8 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";

    $PRJNAMEV		= $PRJNAME;
    $PERIODEV		= date('M Y',strtotime($End_Date));
    $PERIODEV		= strftime('%B %Y', strtotime($End_Date));
    $PRJCOSTV		= $PRJCOST;
    $PRJCOSTVP		= $PRJCOST;
    if($PRJCOSTV == 0 || $PRJCOSTV == '')
    	$PRJCOSTVP 	= 1;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
	<title><?=$h1_title?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  	<style>
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
	        width: 21cm;
	        min-height: 29.7cm;
	        padding: 0.1cm;
	        margin: 0.1cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .subpage {
	        padding: 0.5cm;
	        height: 256mm;
	    }
    
	    @page {
	        size: A4;
	        margin: 0;
	    }
	    @media print {
	        .page {
	            margin: 0;
	            border: initial;
	            border-radius: initial;
	            width: initial;
	            min-height: initial;
	            box-shadow: initial;
	            background: initial;
	            page-break-after: always;
	        }
	    }
			
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>
</head>
<body style="overflow:auto">
	<div class="page">
		<section class="content">
	        <table width="100%" border="0" style="size:auto">
	            <tr>
	                <td width="19%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
	                <td width="42%" class="style2">&nbsp;</td>
	                <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
	            </tr>
	            <tr>
	                <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/'. $comp_init . '/compLog.png') ?>" width="250" height="60"></td>
	                <td colspan="2" class="style2" style="font-weight:bold; text-transform: uppercase; font-size:18px"><?=$h1_title?></td>
	          	</tr>
	            <tr>
	                <td colspan="2" valign="top" class="style2" style="font-weight:bold; font-size:12px;"><span class="style2" style="font-weight:bold; font-size:12px"><?php echo strtoupper($PRJNAMEV); ?></span></td>
	            </tr>
	            <tr>
	                <td colspan="2" valign="top" class="style2" style="font-size:12px; border-bottom:groove;"><span class="style2" style="font-weight:bold; font-size:12px"><?php echo strtoupper($comp_name); ?></span><span class="pull-right" style="font-style: italic; font-size: 10px">Tgl. Cetak : <?php echo date("d/m/Y H:i:s"); ?></span></td>
	            </tr>
	            <tr>
	                <td colspan="3" class="style2" style="text-align:left;">&nbsp;</td>
	            </tr>
				<?php
	                // ------------------------------------ H E A D E R ------------------------------------
                    // NILAI PEKERJAAN TAMBAH KURANG - TOTAL
						$A1P 			= $PRJCOST;
						$ADD_TOT 		= 0;
						$sqlLRBEF		= "SELECT SUM(SI_REAL) AS SI_REAL
											FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL'";
						$reslLRBEF		= $this->db->query($sqlLRBEF)->result();
						foreach($reslLRBEF as $rowLRB):
							$ADD_TOT 	= $rowLRB->SI_REAL;
						endforeach;
		                $TOTAL_PRJ	= $PRJCOST + $ADD_TOT;

						$TOTAL_PRJ1			= $TOTAL_PRJ;
						if($TOTAL_PRJ1 == 0)
							$TOTAL_PRJ1 = 1;
							
	                
	                // RENCANA PROGRESS & REALISASI PROGRESS	- BEFORE FROM S-CURVE
		                $PRG_PLANB 	= 0;
		                $PRG_REALB 	= 0;
		                $s_PROGPLAN	= "SELECT Prg_PlanAkum, Prg_RealAkum FROM tbl_projprogres
		                				WHERE proj_Code = '$PRJCODECOL' AND Prg_Month = $MBEFORE AND YEAR(Prg_Date1) = '$PERIODEY' ORDER BY Prg_Step DESC LIMIT 1";
                        $r_PROGPLAN	= $this->db->query($s_PROGPLAN)->result();
                        foreach($r_PROGPLAN as $rw_PROGPLAN):
                            $PRG_PLANB	= $rw_PROGPLAN->Prg_PlanAkum;
                            $PRG_REALB	= $rw_PROGPLAN->Prg_RealAkum;
                        endforeach;
	                
	                // RENCANA PROGRESS & REALISASI PROGRESS	- CURRENT MONTH FROM S-CURVE
		                $PRG_PLANC 	= 0;
		                $PRG_REALC 	= 0;
		                $s_PROGPLAN	= "SELECT Prg_PlanAkum, Prg_RealAkum FROM tbl_projprogres
		                				WHERE proj_Code = '$PRJCODECOL' AND Prg_Month = $MSELECTED AND YEAR(Prg_Date1) = '$PERIODEY' ORDER BY Prg_Step DESC LIMIT 1";
                        $r_PROGPLAN	= $this->db->query($s_PROGPLAN)->result();
                        foreach($r_PROGPLAN as $rw_PROGPLAN):
                            $PRG_PLANC	= $rw_PROGPLAN->Prg_PlanAkum;
                            $PRG_REALC	= $rw_PROGPLAN->Prg_RealAkum;
                        endforeach;
	                
	                // REALISASI PROGRESS INTERNAL & EKSTERNAL 	- BEFORE
		                $PRG_RINTB 	= 0;
		                $PRG_REKSB 	= 0;
		                $s_PRGREALB	= "SELECT PRJP_GTOT, PRJP_GTOT_EKS FROM tbl_project_progress
		                				WHERE PRJCODE = '$PRJCODECOL' AND MONTH(PRJP_DATE_S) = $MBEFORE AND YEAR(PRJP_DATE_S) = '$PERIODEY' ORDER BY PRJP_STEP DESC LIMIT 1";
                        $r_PRGREALB	= $this->db->query($s_PRGREALB)->result();
                        foreach($r_PRGREALB as $rw_PRGREALB):
                            $PRG_RINTB	= $rw_PRGREALB->PRJP_GTOT;
                            $PRG_REKSB	= $rw_PRGREALB->PRJP_GTOT_EKS;
                        endforeach;
	                
	                // REALISASI PROGRESS INTERNAL & EKSTERNAL 	- CURRENT
		                $PRG_RINTC 	= 0;
		                $PRG_REKSC 	= 0;
		                $s_PRGREALC	= "SELECT PRJP_GTOT, PRJP_GTOT_EKS FROM tbl_project_progress
		                				WHERE PRJCODE = '$PRJCODECOL' AND MONTH(PRJP_DATE_S) = $MSELECTED AND YEAR(PRJP_DATE_S) = '$PERIODEY' ORDER BY PRJP_STEP DESC LIMIT 1";
                        $r_PRGREALC	= $this->db->query($s_PRGREALC)->result();
                        foreach($r_PRGREALC as $rw_PRGREALC):
                            $PRG_RINTC	= $rw_PRGREALC->PRJP_GTOT;
                            $PRG_REKSC	= $rw_PRGREALC->PRJP_GTOT_EKS;
                        endforeach;
                        $PRG_RDEV 	= $PRG_RINTC - $PRG_REKSC;
					

					// ------------------------------------ DETAIL ------------------------------------

					// L/R COLUMN
					// PRJCOST	PRJADD	SIAPPVAL	PROG_PLAN	PROG_REAL	INV_REAL	PROGMC		 	PROGMC_REALA		SI_PROYEKSI			MC_CAT_PROYEKSI	MC_OTH_PLAN	MC_OTH_REAL	MC_OTH_PROYEKSI	KURS_DEV_PLAN	KURS_DEV_REAL	KURS_DEV_PROYEKSI	ASSURAN_PLAN	ASSURAN_REAL	ASSURAN_PROYEKSI	CASH_EXPENSE	PPA	PLL															C7BP							BPP_GAJI_PLAN	BPP_GAJI_REAL	BPP_GAJI_PROYEKSI	STOCK					STOCK_FG	EXP_TOOLS	EXP_BAU_HOCAB	EXP_BUNGA	EXP_PPH	BPP	BLL	BGP	BLAT	BAU	BOL	BPB	BPM	BPK	BNOL	GRAND_PROFLOS

					// PLANING
						$s_LR 		= "SELECT 	SUM(PROGMC_REAL) AS A1SPER, SUM(PROGMC_REALA) AS A1A, SUM(SI_PLAN) AS A2A, 0 AS A3A, SUM(MC_CAT_PLAN) AS A4A, 
												0 AS B1A, SUM(BPP_MTR_PLAN) AS C1A, SUM(BPP_UPH_PLAN) AS C2A, SUM(BPP_ALAT_PLAN) AS C3A,
												SUM(BPP_SUBK_PLAN) AS C4A, SUM(BPP_BAU_PLAN + BPP_OTH_PLAN + BPP_I_PLAN) AS C5A,
												0 AS D1A, 0 AS D2A, 0 AS E1A, 0 AS E2A, 0 AS F1A, 0 AS F2A, SUM(EXP_TOOLS) AS H1A,
												SUM(BPP_MTR_PROYEKSI) AS C1AP, SUM(BPP_UPH_PROYEKSI) AS C2AP, SUM(BPP_ALAT_PROYEKSI) AS C3AP,
												SUM(BPP_SUBK_PROYEKSI) AS C4AP, SUM(BPP_BAU_PROYEKSI + BPP_OTH_PROYEKSI + BPP_I_PROYEKSI) AS C5AP
										FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL'";
						$r_LR		= $this->db->query($s_LR)->result();
						foreach($r_LR as $rw_LR):
							$A1SPER = $rw_LR->A1SPER;	// A. PROGRES MS PERSENTASI
							$A1A 	= $rw_LR->A1A;		// A. PROGRES MS NILAI
							$A2A 	= $rw_LR->A2A;		// A. PEKERJAAN TAMBAH KURANG
							$A3A 	= $rw_LR->A3A;		// A. PROGRES KONTRAKTUIL
							$A4A 	= $rw_LR->A4A;		// A. PEKERJAAN KATROLAN
							$B1A 	= $rw_LR->B1A;		// A. BIAYA CASH
							$C1A 	= $rw_LR->C1A;		// C. BIAYA HUTANG - MATERIAL
							$C1AP 	= $rw_LR->C1AP;		// C. BIAYA HUTANG - MATERIAL PROYEKSI
							$C2A 	= $rw_LR->C2A;		// C. BIAYA HUTANG - UPAH
							$C2AP 	= $rw_LR->C2AP;		// C. BIAYA HUTANG - UPAH PROYEKSI
							$C3A 	= $rw_LR->C3A;		// C. BIAYA HUTANG - ALAT
							$C3AP 	= $rw_LR->C3AP;		// C. BIAYA HUTANG - ALAT PROYEKSI
							$C4A 	= $rw_LR->C4A;		// C. BIAYA HUTANG - SUBKON
							$C4AP 	= $rw_LR->C4AP;		// C. BIAYA HUTANG - SUBKON PROYEKSI
							$C5A 	= $rw_LR->C5A;		// C. BIAYA HUTANG - OVERHEAD
							$C5AP 	= $rw_LR->C5AP;		// C. BIAYA HUTANG - OVERHEAD PROYEKSI
							$C7A 	= 0;
							$C8A 	= 0;
							$C9A 	= 0;
							$D1A 	= $rw_LR->D1A;		// D. HUTANG DALAM PROSES - KASBON
							$D2A 	= $rw_LR->D2A;		// D. HUTANG DALAM PROSES - HUT. BELUM DIBUKUKAN
							$E1A 	= $rw_LR->E1A;		// E. BIAYA PROYEK DI PUSAT - BUASO
							$E2A 	= $rw_LR->E2A;		// E. BIAYA PROYEK DI PUSAT - CONTINGENCY
							$F1A 	= $rw_LR->F1A;		// F. STOCK / PERSEDIAAN - MATERIAL
							$F2A 	= $rw_LR->F2A;		// F. STOCK / PERSEDIAAN - LAINNYA
							$H1A 	= $rw_LR->H1A;		// H. LEASING  ALAT / PEMBEBANAN (TARIF)
						endforeach;

					// BEFORE
						$s_LR 		= "SELECT 	SUM(PROGMC_REALA) AS A1B, SUM(SI_REAL) AS A2B, 0 AS A3B, SUM(MC_CAT_REAL) AS A4B,
												0 AS B4B, SUM(BPP_MTR_REAL) AS C1B, SUM(BPP_UPH_REAL) AS C2B, SUM(BPP_ALAT_REAL) AS C3B,
												SUM(BPP_SUBK_REAL) AS C4B, SUM(BPP_BAU_REAL + BPP_OTH_REAL + BPP_I_REAL) AS C5B,
												0 AS D1B, 0 AS D2B, 0 AS E1B, 0 AS E2B, SUM(STOCK_MTR) AS F1B, SUM(STOCK_BBM + STOCK_TOOLS + STOCK_WIP) AS F2B,
												SUM(EXP_TOOLS) AS H1B,
												SUM(BPP_MTR_PROYEKSI) AS C1BP, SUM(BPP_UPH_PROYEKSI) AS C2BP, SUM(BPP_ALAT_PROYEKSI) AS C3BP,
												SUM(BPP_SUBK_PROYEKSI) AS C4BP, SUM(BPP_BAU_PROYEKSI + BPP_OTH_PROYEKSI + BPP_I_PROYEKSI) AS C5BP
										FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' AND PERIODE <= '$End_DateBef'";
						$r_LR		= $this->db->query($s_LR)->result();
						foreach($r_LR as $rw_LR):
							$A1B 	= $rw_LR->A1B;		// A. PROGRES MS NILAI
							$A2B 	= $rw_LR->A2B;		// A. PEKERJAAN TAMBAH KURANG
							$A3B 	= $rw_LR->A3B;		// A. PROGRES KONTRAKTUIL
							$A4B 	= $rw_LR->A4B;		// A. PEKERJAAN KATROLAN
							$B1B 	= $rw_LR->B4B;		// A. BIAYA CASH
							$C1B 	= $rw_LR->C1B;		// C. BIAYA HUTANG - MATERIAL
							$C1BP 	= $rw_LR->C1BP;		// C. BIAYA HUTANG - MATERIAL PROYEKSI
							$C2B 	= $rw_LR->C2B;		// C. BIAYA HUTANG - UPAH
							$C2BP 	= $rw_LR->C2BP;		// C. BIAYA HUTANG - UPAH PROYEKSI
							$C3B 	= $rw_LR->C3B;		// C. BIAYA HUTANG - ALAT
							$C3BP 	= $rw_LR->C3BP;		// C. BIAYA HUTANG - ALAT PROYEKSI
							$C4B 	= $rw_LR->C4B;		// C. BIAYA HUTANG - SUBKON
							$C4BP 	= $rw_LR->C4BP;		// C. BIAYA HUTANG - SUBKON PROYEKSI
							$C5B 	= $rw_LR->C5B;		// C. BIAYA HUTANG - OVERHEAD
							$C5BP 	= $rw_LR->C5BP;		// C. BIAYA HUTANG - OVERHEAD PROYEKSI
							$C7B 	= 0;
							$C8B 	= 0;
							$C9B 	= 0;
							$D1B 	= $rw_LR->D1B;		// D. HUTANG DALAM PROSES - KASBON
							$D2B 	= $rw_LR->D2B;		// D. HUTANG DALAM PROSES - HUT. BELUM DIBUKUKAN
							$E1B 	= $rw_LR->E1B;		// E. BIAYA PROYEK DI PUSAT - BUASO
							$E2B 	= $rw_LR->E2B;		// E. BIAYA PROYEK DI PUSAT - CONTINGENCY
							$F1B 	= $rw_LR->F1B;		// F. STOCK / PERSEDIAAN - MATERIAL
							$F2B 	= $rw_LR->F2B;		// F. STOCK / PERSEDIAAN - LAINNYA
							$H1B 	= $rw_LR->H1B;		// H. LEASING  ALAT / PEMBEBANAN (TARIF)
						endforeach;

					// CURRENT
						$s_LR 		= "SELECT 	SUM(PROGMC_REALA) AS A1C, SUM(SI_REAL) AS A2C, 0 AS A3C, SUM(MC_CAT_REAL) AS A4C,
												0 AS B4C, SUM(BPP_MTR_REAL) AS C1C, SUM(BPP_UPH_REAL) AS C2C, SUM(BPP_ALAT_REAL) AS C3C,
												SUM(BPP_SUBK_REAL) AS C4C, SUM(BPP_BAU_REAL + BPP_OTH_REAL + BPP_I_REAL) AS C5C,
												0 AS D1C, 0 AS D2C, 0 AS E1C, 0 AS E2C, SUM(STOCK_MTR) AS F1C, SUM(STOCK_BBM + STOCK_TOOLS + STOCK_WIP) AS F2C,
												SUM(EXP_TOOLS) AS H1C,
												SUM(BPP_MTR_PROYEKSI) AS C1CP, SUM(BPP_UPH_PROYEKSI) AS C2CP, SUM(BPP_ALAT_PROYEKSI) AS C3CP,
												SUM(BPP_SUBK_PROYEKSI) AS C4CP, SUM(BPP_BAU_PROYEKSI + BPP_OTH_PROYEKSI + BPP_I_PROYEKSI) AS C5CP
										FROM tbl_profitloss WHERE PRJCODE = '$PRJCODECOL' AND MONTH(PERIODE) = '$PERIODEM' AND YEAR(PERIODE) = '$PERIODEY'";
						$r_LR		= $this->db->query($s_LR)->result();
						foreach($r_LR as $rw_LR):
							$A1C 	= $rw_LR->A1C;		// A. PROGRES MS NILAI
							$A2C 	= $rw_LR->A2C;		// A. PEKERJAAN TAMBAH KURANG
							$A3C 	= $rw_LR->A3C;		// A. PROGRES KONTRAKTUIL
							$A4C 	= $rw_LR->A4C;		// A. PEKERJAAN KATROLAN
							$B1C 	= $rw_LR->B4C;		// A. BIAYA CASH
							$C1C 	= $rw_LR->C1C;		// C. BIAYA HUTANG - MATERIAL
							$C1CP 	= $rw_LR->C1CP;		// C. BIAYA HUTANG - MATERIAL PROYEK
							$C2C 	= $rw_LR->C2C;		// C. BIAYA HUTANG - UPAH
							$C2CP 	= $rw_LR->C2CP;		// C. BIAYA HUTANG - UPAH PROYEK
							$C3C 	= $rw_LR->C3C;		// C. BIAYA HUTANG - ALAT
							$C3CP 	= $rw_LR->C3CP;		// C. BIAYA HUTANG - ALAT PROYEK
							$C4C 	= $rw_LR->C4C;		// C. BIAYA HUTANG - SUBKON
							$C4CP 	= $rw_LR->C4CP;		// C. BIAYA HUTANG - SUBKON PROYEK
							$C5C 	= $rw_LR->C5C;		// C. BIAYA HUTANG - OVERHEAD
							$C5CP 	= $rw_LR->C5CP;		// C. BIAYA HUTANG - OVERHEAD PROYEK
							$C7C 	= 0;
							$C8C 	= 0;
							$C9C 	= 0;
							$D1C 	= $rw_LR->D1C;		// D. HUTANG DALAM PROSES - KASBON
							$D2C 	= $rw_LR->D2C;		// D. HUTANG DALAM PROSES - HUT. BELUM DIBUKUKAN
							$E1C 	= $rw_LR->E1C;		// E. BIAYA PROYEK DI PUSAT - BUASO
							$E2C 	= $rw_LR->E2C;		// E. BIAYA PROYEK DI PUSAT - CONTINGENCY
							$F1C 	= $rw_LR->F1C;		// F. STOCK / PERSEDIAAN - MATERIAL
							$F2C 	= $rw_LR->F2C;		// F. STOCK / PERSEDIAAN - LAINNYA
							$H1C 	= $rw_LR->H1C;		// H. LEASING  ALAT / PEMBEBANAN (TARIF)
						endforeach;

	                // ------------------------------------ A. P E N D A P A T A N ------------------------------------
						// A.1 PROGRESS MC
							$A1BPER	= $A1B / $TOTAL_PRJ1 * 100;
							$A1CPER	= $A1C / $TOTAL_PRJ1 * 100;							
							
						// A.4 PENDAPATAN LAIN-LAIN
							// A. NILAI INCOME - BEFORE 	--> DIDAPATKAN DARI SELURUH JURNAL
								$TOTAL_INDB		= 0;
								$TOTAL_INKB		= 0;
								$TOTAL_INB		= 0;
								$sqlTOTAL_INB	= "SELECT SUM(A.Base_Debet) AS TOTAL_IND, SUM(A.Base_Kredit) AS TOTAL_INK
													FROM tbl_journaldetail A INNER JOIN tbl_chartaccount_363022 B ON A.Acc_Id = B.Account_Number
													WHERE B.Account_Number = 4 AND A.proj_Code = '$PRJCODECOL' AND A.JournalH_Date <= '$End_DateBef'";
								$resTOTAL_INB	= $this->db->query($sqlTOTAL_INB)->result();
								foreach($resTOTAL_INB as $rowTOTAL_INB):
									$TOTAL_INDB	= $rowTOTAL_INB->TOTAL_IND;
									$TOTAL_INKB	= $rowTOTAL_INB->TOTAL_INK;
								endforeach;
								$A5B			= $TOTAL_INKB - $TOTAL_INDB;
								
							// B. NILAI INCOME - CURRENT 	--> vw_income_current
								$TOTAL_INDC		= 0;
								$TOTAL_INKC		= 0;
								$TOTAL_INC		= 0;
								$sqlTOTAL_INC	= "SELECT SUM(A.Base_Debet) AS TOTAL_IND, SUM(A.Base_Kredit) AS TOTAL_INK
													FROM tbl_journaldetail A INNER JOIN tbl_chartaccount_363022 B ON A.Acc_Id = B.Account_Number
													WHERE B.Account_Number = 4 AND A.proj_Code = '$PRJCODECOL'
														AND MONTH(A.JournalH_Date) = '$PERIODEM' AND YEAR(A.JournalH_Date) = '$PERIODEY'";
								$resTOTAL_INC	= $this->db->query($sqlTOTAL_INC)->result();
								foreach($resTOTAL_INC as $rowTOTAL_INC):
									$TOTAL_INDC	= $rowTOTAL_INC->TOTAL_IND;
									$TOTAL_INKC	= $rowTOTAL_INC->TOTAL_INK;
								endforeach;
								$A5C			= $TOTAL_INKC - $TOTAL_INDC;
								$A5D 			= $A5C;
							
						// SUB TOTAL A
							$SUBTOT_AA	= $PRJCOST;
							$SUBTOT_AB	= $A1B + $A2B + $A3B + $A4B + $A5B;			// BEFORE
							$SUBTOT_AC	= $A1C + $A2C + $A3C + $A4C + $A5C;			// CURRENT
							$SUBTOT_AD	= 0;
							
							
	                // ------------------------------------ B. BIAYA CASH ------------------------------------	
						$SUBTOT_BA	= 0;
						//$SUBTOT_BB= $CASH_EXPENSE;
						$SUBTOT_BB	= 0;
						$SUBTOT_BC	= 0;
						$SUBTOT_BD	= 0;
					

	                // ------------------------------------ C -> B. BIAYA HUTANG ------------------------------------
						// B.1 BIAYA PROYEK DI PUSAT : BAHAN 		--- BERTIPE M (vw_budget_m)
							$C1A	= 0;
							$C1P 	= 0;
							$s_MTRP	= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_m WHERE PRJCODE = '$PRJCODECOL'";
							$r_MTRP	= $this->db->query($s_MTRP)->result();
							foreach($r_MTRP as $rw_MTRP):
								$TOT_BUDG		= $rw_MTRP->TOT_BUDG;
								$TOT_ADDPLUS	= $rw_MTRP->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($rw_MTRP->TOT_ADDMIN);
								$C1P			= $rw_MTRP->C1P;
								$TOTAL_MTR		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
								$C1A	= $TOTAL_MTR;
								$C1A1	= $TOTAL_MTR;
								if($C1A == '' || $C1A == 0)
								{
									$C1A	= 0;
									$C1A1	= 1;
								}
							
							// BEFORE PERCENTATION
								$C1BPER	= $C1B / $C1A1 * 100;
							
							// CURRENT PERCENTATION
								//$C1C	= $BPP_MTR_REAL_X;
								$C1CPER	= $C1C / $C1A1 * 100;
										
						// B.2 BIAYA PROYEK DI PUSAT : UPAH			--- BERTIPE U (vw_budget_u)
							$TOTAL_UPH 		= 0;
							$sql_UPH_PLAN	= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_u WHERE PRJCODE = '$PRJCODECOL'";
							$res_UPH_PLAN	= $this->db->query($sql_UPH_PLAN)->result();
							foreach($res_UPH_PLAN as $row_UPH_PLAN):
								$TOT_BUDG		= $row_UPH_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_UPH_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($row_UPH_PLAN->TOT_ADDMIN);
								$C1P			= $row_UPH_PLAN->C1P;
								$TOTAL_UPH		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$C2A	= $TOTAL_UPH;
							$C2A1	= $TOTAL_UPH;
							if($C2A == '' || $C2A == 0)
							{
								$C2A	= 0;
								$C2A1	= 1;
							}
							
							// BEFORE PERCENTATION
								$C2BP	= $C2B / $C2A1 * 100;
							
							// CURRENT PERCENTATION
								//$C2C	= $BPP_UPH_REAL_X;
								$C2CPER	= $C2C / $C2A1 * 100;
							
						// B.3 BIAYA PROYEK DI PUSAT : ALAT 		--- BERTIPE T (vw_budget_t)
							$TOTAL_ALT 		= 0;
							$sql_ALAT_PLAN	= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_t WHERE PRJCODE = '$PRJCODECOL'";
							$res_ALAT_PLAN	= $this->db->query($sql_ALAT_PLAN)->result();
							foreach($res_ALAT_PLAN as $row_ALAT_PLAN):
								$TOT_BUDG		= $row_ALAT_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_ALAT_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($row_ALAT_PLAN->TOT_ADDMIN);
								$C1P			= $row_ALAT_PLAN->C1P;
								$TOTAL_ALT		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$C3A	= $TOTAL_ALT;
							$C3A1	= $TOTAL_ALT;
							if($C3A == '' || $C3A == 0)
							{
								$C3A	= 0;
								$C3A1	= 1;
							}
							
							// BEFORE PERCENTATION
							// ADA PENAMBAHAN BEBAN ALAT -> EXP_TOOLS
								$C3B1	= $C3B + $H1C;
								$C3B	= $C3B1;
								$C3BPER	= $C3B / $C3A1 * 100;
							
							// CURRENT PERCENTATION
							// ADA PENAMBAHAN BEBAN ALAT -> EXP_TOOLS
								//$C3C	= $BPP_ALAT_REAL_X + $EXP_TOOLS;
								$C3CPER	= $C3C / $C3A1 * 100;
							
						// B.4 BIAYA PROYEK DI PUSAT : SUBKON 		--- BERTIPE SC (vw_budget_ssc)
							$TOTAL_SUBK		= 0;
							$sql_SUBK_PLAN	= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_ssc WHERE PRJCODE = '$PRJCODECOL'";
							$res_SUBK_PLAN	= $this->db->query($sql_SUBK_PLAN)->result();
							foreach($res_SUBK_PLAN as $row_SUBK_PLAN):
								$TOT_BUDG		= $row_SUBK_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_SUBK_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($row_SUBK_PLAN->TOT_ADDMIN);
								$C1P			= $row_SUBK_PLAN->C1P;
								$TOTAL_SUBK		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
							// PLAN
							$C4A	= $TOTAL_SUBK;
							$C4A1	= $TOTAL_SUBK;
							if($C4A == '' || $C4A == 0)
							{
								$C4A	= 0;
								$C4A1	= 1;
							}
							
							// BEFORE PERCENTATION
								$C4BPER	= $C4B / $C4A1 * 100;
							
							// CURRENT PERCENTATION
								//$C4C	= $BPP_SUBK_REAL_X;
								$C4CPER	= $C4C / $C4A1 * 100;
							
						// B.5 BIAYA PROYEK DI PUSAT : ADUM 		--- BERTIPE SC (vw_budget_ge)
							$TOTAL_BAU		= 0;
							$sql_BAU_PLAN	= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_ge WHERE PRJCODE = '$PRJCODECOL'";
							$res_BAU_PLAN	= $this->db->query($sql_BAU_PLAN)->result();
							foreach($res_BAU_PLAN as $row_BAU_PLAN):
								$TOT_BUDG		= $row_BAU_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_BAU_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($row_BAU_PLAN->TOT_ADDMIN);
								$C1P			= $row_BAU_PLAN->C1P;
								$TOTAL_BAU		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
						// B.6 BIAYA PROYEK DI PUSAT : OVERHEAD 	--- BERTIPE O (vw_budget_o)
							$TOTAL_OTH		= 0;
							$sql_OTH_PLAN	= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_o WHERE PRJCODE = '$PRJCODECOL'";
							$res_OTH_PLAN	= $this->db->query($sql_OTH_PLAN)->result();
							foreach($res_OTH_PLAN as $row_OTH_PLAN):
								$TOT_BUDG		= $row_OTH_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_OTH_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($row_OTH_PLAN->TOT_ADDMIN);
								$C1P			= $row_OTH_PLAN->C1P;
								$TOTAL_OTH		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;
							
						// B.7 BIAYA PROYEK DI PUSAT : RUPA-RUPA 	--- BERTIPE I AND O (vw_budget_i)
							$TOTAL_I		= 0;
							$sql_I_PLAN		= "SELECT SUM(TOT_BUDG) AS TOT_BUDG, SUM(TOT_ADDPLUS) AS TOT_ADDPLUS,
													SUM(TOT_ADDMIN) AS TOT_ADDMIN, SUM(NIL_PROYEKSI) AS C1P
												FROM
													vw_budget_i WHERE PRJCODE = '$PRJCODECOL'";
							$res_I_PLAN		= $this->db->query($sql_I_PLAN)->result();
							foreach($res_I_PLAN as $row_I_PLAN):
								$TOT_BUDG		= $row_I_PLAN->TOT_BUDG;
								$TOT_ADDPLUS	= $row_I_PLAN->TOT_ADDPLUS;
								$TOT_ADDMIN		= abs($row_I_PLAN->TOT_ADDMIN);
								$C1P			= $rw_MTRP->C1P;
								$TOTAL_I		= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
							endforeach;

						// TOTAL OVERHEAD (GABUNGAN DARI OTH+BAU+I)
							$C5AP	= $TOTAL_BAU+$TOTAL_OTH+$TOTAL_I;
							$C5AP1 	= $C5AP;
							if($C5AP == '' || $C5AP == 0)
							{
								$C5AP1	= 1;
							}
							
							// BEFORE PERCENTATION
								$C5BPER	= $C5B / $C5AP1 * 100;
							
							// CURRENT PERCENTATION
								$C5CPER	= $C5C / $C5AP1 * 100;
							
						// B.7.1 BIAYA PROYEK DI PUSAT : UNDEFINE 	--- BERTIPE "-" - BEFORE
							$BPP_UNDEF_PLAN		= 0;
							$BPP_UNDEFB_REAL	= 0;
							$TOTAL_UNDEFD		= 0;
							$TOTAL_UNDEFK		= 0;
							
							$TOTAL_UNDEFB_REAL1	= 0;
							$TOTAL_UNDEFB_REAL	= 0;
							$BPP_UNDEFB_REAL	= 0;
							$BPP_UNDEFB_PLAN1	= 0;
							if($BPP_UNDEFB_PLAN1 == 0)
								$BPP_UNDEFB_PLAN1 = 1;
								
							$BPP_UNDEFB_REALP	= 100;
							if($BPP_UNDEFB_REAL == '')
								$BPP_UNDEFB_REAL	= 0;
							
						// B.7.2 BIAYA PROYEK DI PUSAT : UNDEFINE 	--- BERTIPE "-" - CURRENT
							$BPP_UNDEF_REAL	= 0;
							$TOTAL_UNDEFCD	= 0;
							$TOTAL_UNDEFCK	= 0;
							/*$sql_UNDEF_REAL	= "SELECT SUM(A.Base_Debet) AS TOTAL_UNDEFCD, SUM(A.Base_Kredit) AS TOTAL_UNDEFCK
													FROM
														tbl_journaldetail A
													INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
													INNER JOIN tbl_chartaccount D ON A.Acc_Id = D.Account_Number
														AND D.Account_Category IN (5,8,9)
														AND D.PRJCODE = '$PRJCODECOL'
													WHERE A.ITM_CATEG = '-'
														AND A.proj_Code = '$PRJCODECOL'
														AND B.GEJ_STAT IN (3,9)
														AND B.JournalType IN ('GEJ','VGEJ','CPRJ','VCPRJ','O-EXP','VO-EXP', 
														'OPN','VOPN','AU')
													AND MONTH(B.JournalH_Date) = '$PERIODEM'";
							$res_UNDEF_REAL	= $this->db->query($sql_UNDEF_REAL)->result();
							foreach($res_UNDEF_REAL as $row_UNDEF_REAL):
								$TOTAL_UNDEFCD	= $row_UNDEF_REAL->TOTAL_UNDEFCD;
								$TOTAL_UNDEFCK	= $row_UNDEF_REAL->TOTAL_UNDEFCK;
							endforeach;
							$TOTAL_UNDEF_REAL	= $TOTAL_UNDEFCD - $TOTAL_UNDEFCK + $BPP_OTH1_REAL;
							$BPP_UNDEF_REAL		= $TOTAL_UNDEF_REAL;
							$BPP_UNDEF_PLAN1	= $BPP_UNDEF_PLAN;*/
							$TOTAL_UNDEF_REAL	= 0;
							$BPP_UNDEF_REAL		= 0;
							$BPP_UNDEF_PLAN1	= 0;
							if($BPP_UNDEF_PLAN1 == 0)
								$BPP_UNDEF_PLAN1 = 1;
								
							$BPP_UNDEF_REALP	= 100;
							if($BPP_UNDEF_REAL == '')
								$BPP_UNDEF_REAL	= 0;
							
						$SUBTOT_CA 	= $C1A+$C2A+$C3A+$C4A+$C5A+$C7A+$C8A+$C9A;
						$SUBTOT_CB 	= $C1B+$C2B+$C3B+$C4B+$C5B+$C7B+$C8B+$C9B;
						$SUBTOT_CC 	= $C1C+$C2C+$C3C+$C4C+$C5C+$C7C+$C8C+$C9C;
						$SUBTOT_CD 	= $C1AP+$C2AP+$C3AP+$C4AP+$C5AP;
						
							
	                // ------------------------------------ D -> C. HUTANG DALMA PROSES ------------------------------------
						$SUBTOT_DA	= 0;
						$SUBTOT_DB	= 0;
						$SUBTOT_DC	= 0;
						$SUBTOT_DD	= 0;
						
							
	                // ------------------------------------ E -> D. BIAYA PROYEK DI PUSAT ------------------------------------
						$SUBTOT_EA	= 0;
						$SUBTOT_EB	= 0;
						$SUBTOT_EC	= 0;
						$SUBTOT_ED	= 0;
						
							
	                // ------------------------------------ F -> E. STOCK / PERSEDIAAN ------------------------------------
						$SUBTOT_FA	= 0;
						$SUBTOT_FB	= 0;
						$SUBTOT_FC	= 0;
						$SUBTOT_FD	= 0;
						
							
	                // ------------------------------------ G / F. B E B A N ------------------------------------
						// BEBAN OVERHEAD, PPH, DAN BUNGA BANK
						$BB_BAU_PLAN	= ($PRJ_LROVH/100) * $PROGMC_REAL;
						$BB_BNG_PLAN	= ($PRJ_LRBNK/100) * $PROGMC_REAL;
						//$BB_PPH_PLAN	= $PRJ_LRPPH * ($SUBTOT_AA - $SUBTOT_DA - $BB_BAU_PLAN - $BB_BNG_PLAN);
						$BB_PPH_PLAN	= ($PRJ_LRPPH/100) * ($PROGMC_REAL);
						
						// BEFORE
						$BB_BAU_PLAN1	= $BB_BAU_PLAN;
						if($BB_BAU_PLAN1 == 0)
							$BB_BAU_PLAN1 = 1;
						$BB_BNG_PLAN1	= $BB_BNG_PLAN;
						if($BB_BNG_PLAN1 == 0)
							$BB_BNG_PLAN1 = 1;
						$BB_PPH_PLAN1	= $BB_PPH_PLAN;
						if($BB_PPH_PLAN1 == 0)
							$BB_PPH_PLAN1 = 1;
							
						$BB_BAUB_PLAN	= 0 * $SUBTOT_AB;
						$BB_BAUB_PLANP	= $BB_BAUB_PLAN / $BB_BAU_PLAN1 * 100;
						$BB_BNGB_PLAN	= 0 * $SUBTOT_AB;
						$BB_BNGB_PLANP	= $BB_BNGB_PLAN / $BB_BNG_PLAN1 * 100;
						$BB_PPHB_PLAN	= 0 * ($SUBTOT_AB - $SUBTOT_DB - $BB_BAUB_PLAN - $BB_BNGB_PLAN);
						$BB_PPHB_PLANP	= $BB_PPHB_PLAN / $BB_PPH_PLAN1 * 100;
						
						// CURRENT
						$BB_BAU_REAL	= 0 * $SUBTOT_AC;
						$BB_BAU_REALP	= $BB_BAU_REAL / $BB_BAU_PLAN1 * 100;
						$BB_BNG_REAL	= 0 * $SUBTOT_AC;
						$BB_BNG_REALP	= $BB_BNG_REAL / $BB_BNG_PLAN1 * 100;
						$BB_PPH_REAL	= 0 * ($SUBTOT_AC - $SUBTOT_DD - $BB_BAU_REAL - $BB_BNG_REAL);
						$BB_PPH_REALP	= $BB_PPH_REAL / $BB_PPH_PLAN1 * 100;
					
						// BEBAN PPH
							$TOTAL_PPH	= 0;
							$sqlTOT_PPH	= "SELECT SUM(A.INV_PPHVAL) AS TOTAL_PPH
													FROM tbl_pinv_header A
													WHERE
														A.INV_STAT IN (3,6) AND PRJCODE = '$PRJCODECOL'";
							$resTOT_PPH	= $this->db->query($sqlTOT_PPH)->result();
							foreach($resTOT_PPH as $rowTOT_PPH):
								$TOTAL_PPH	= $rowTOT_PPH->TOTAL_PPH;
							endforeach;
							$BB_PPH_REAL	= $TOTAL_PPH;

						// PROYEKSI
							$BB_BAU_PROYEKSI = 0;
							$BB_BNG_PROYEKSI = 0;
							$BB_PPH_PROYEKSI = 0;
	            	$PRG_KONTRTUIL = $PRG_RINTC - $A1CPER;
	            ?>
	          	<tr>
	                <td colspan="3" class="style2" style="text-align:left;">
	                    <table width="100%" style="size:auto; font-size:12px;" cellspacing="-1" cellpadding="0">
	                        <tr style="text-align:left;">
	                         	<td width="15%" style="font-weight: bold; font-size: 13px;" nowrap>NILAI KONTRAK</td>
	                          	<td width="1%">:</td>
	                          	<td width="15%" style="font-weight: bold; font-size: 13px; text-align: right;" nowrap>
	                          		<?php echo number_format($PRJCOSTV, $decFormat); ?>
	                          	</td>
	                          	<td width="55%">&nbsp;</td>
	                          	<td width="4%">&nbsp;</td>
	                          	<td width="10%">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td style="font-weight: bold; font-size: 13px;" nowrap>NILAI ADD. KONTRAK</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px; text-align: right;" nowrap>
	                                <?php echo number_format($ADD_TOT, $decFormat); ?>
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td title="Rencana Progress Mingguan" nowrap>RENCANA PROGRESS</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php echo number_format($PRG_PLANC, 4); ?> %
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td title="Realisasi Progres Mingguan" nowrap>REALISASI PROGRESS (Int.)</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php echo number_format($PRG_RINTC, 4); ?> %
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td title="Realisasi Progres Mingguan" nowrap>REALISASI PROGRESS (Ext.)</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php echo number_format($PRG_REKSC, 4); ?> %
	                            </td>
	                          	<td style="text-align:left;">&nbsp;</td>
	                          	<td>&nbsp;</td>
	                          	<td style="text-align:right;">&nbsp;</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>REALISASI MC / TAGIHAN</td>
	                          	<td>:</td>
	                          	<td style="text-align:right;">
	                            	<?php echo number_format($A1SPER, 4); ?> %
	                            </td>
	                          	<td style="text-align:right;" colspan="3">
	                          		<span class="pull-right" style="font-style: italic;">PERIODE : <?php echo $PERIODEV; ?></span>
	                          	</td>
	                      	</tr>
	                    </table>
			    	</td>
	            </tr>
	            <tr>
					<td colspan="3" class="style2" style="text-align:left; font-size:12px">
	              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
	                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:13px;">
	                    	<td style="<?=$stlLine6?> text-align: center;" width="3%">&nbsp;NO.&nbsp;</td>
	                      	<td style="<?=$stlLine6?> text-align: center;" colspan="2">URAIAN</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%">RENCANA AWAL</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%" nowrap>PERIODE<br>SEBELUMNYA</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="5%" nowrap>%</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%" nowrap>BULAN INI</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="5%"> (%)</td>
	                        <td style="<?=$stlLine6?> text-align: center;" width="15%" nowrap>PROYEKSI</td>
	                	</tr>
	                	<tr style="size:auto; font-size:4px;">
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	                        <td style="<?=$stlLine3?>">&nbsp;</td>
	              	  	</tr>

	              	  	<?php //--- START 	: A. PENDAPATAN ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
		                        <td style="<?=$stlLine5?>; font-size: 13px;" align="center">A.</td>
		                        <td style="<?=$stlLine5?>; font-size: 13px; text-decoration: underline;" colspan="2">PENDAPATAN</td>
		                        <td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		                        <td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		                        <td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
		                        <td style="<?=$stlLine5?>">&nbsp;</td>
		                        <td width="12%" style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Progress MC</td>
		                        <td width="26%" style="<?=$stlLine5?> text-align:right; border-left: 0"><?php echo number_format($A1SPER, 2); ?> %&nbsp;</td>
		                        <td style="<?=$stlLine5?> text-align:right"><?php echo number_format($PRJCOST, 0); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right"><?php echo number_format($A1B, 0); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right" nowrap><?php echo number_format($A1BPER, 2); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right"><?php echo number_format($A1C, 0); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right" nowrap><?php echo number_format($A1CPER, 2); ?></td>
		                        <td style="<?=$stlLine5?> text-align:right" nowrap><?php echo number_format($A1P, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Progress Kontraktuil</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0"><?php echo number_format($PRG_KONTRTUIL, 2); ?> %&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Progress Internal</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0"><?php echo number_format($PRG_RINTC, 2); ?> %&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Deviasi Progress</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0"><?php echo number_format($PRG_RDEV, 2); ?> %&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> border-right: 0" nowrap>&nbsp;Pendapatan Lain-lain</td>
								<td style="<?=$stlLine5?> text-align:right; border-left: 0">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($A5B, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($A5C, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($A5D, 0); ?></td>
		              	  	</tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2" align="center">&nbsp;Sub Total (A)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_AA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_AB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_AC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_AD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr style="display:none">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: A. PENDAPATAN ---// ?>


	              	  	<?php //--- START 	: C -> B. BIAYA - BIAYA ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine5?>" align="center">B.</td>
								<td style="<?=$stlLine5?>; font-size: 13px; text-decoration: underline;" colspan="2">BIAYA - BIAYA</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
		              	  	</tr>
		                    <?php
								$LinkBef_M	= "$PRJCODECOL~$End_DateBef~M";
								$LinkBef_U	= "$PRJCODECOL~$End_DateBef~U";
								$LinkBef_T	= "$PRJCODECOL~$End_DateBef~T";
								$LinkBef_SC	= "$PRJCODECOL~$End_DateBef~SC";
								$LinkBef_GE	= "$PRJCODECOL~$End_DateBef~GE";
								$LinkBef_I	= "$PRJCODECOL~$End_DateBef~I";
								$secPrintMB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_M));
								$secPrintUB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_U));
								$secPrintTB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_T));
								$secPrintSB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_SC));
								$secPrintGB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_GE));
								$secPrintIB	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDB/?id='.$this->url_encryption_helper->encode_url($LinkBef_I));
								
								$LinkCur_M	= "$PRJCODECOL~$PeriodeD~M";
								$LinkCur_U	= "$PRJCODECOL~$PeriodeD~U";
								$LinkCur_T	= "$PRJCODECOL~$PeriodeD~T";
								$LinkCur_SC	= "$PRJCODECOL~$PeriodeD~SC";
								$LinkCur_GE	= "$PRJCODECOL~$PeriodeD~GE";
								$LinkCur_I	= "$PRJCODECOL~$PeriodeD~I";
								$secPrintMC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_M));
								$secPrintUC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_U));
								$secPrintTC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_T));
								$secPrintSC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_SC));
								$secPrintGC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_GE));
								$secPrintIC	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwDC/?id='.$this->url_encryption_helper->encode_url($LinkCur_I));
							?>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Material</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C1A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintMB; ?>')" style="cursor: pointer;">
										<?php echo number_format($C1B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C1BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetC('<?php echo $secPrintMC; ?>')" style="cursor: pointer;">
										<?php echo number_format($C1C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C1CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C1AP, 0); ?></td>
		              	  	</tr>
		                    <script>
								function showDetB(LinkD)
								{
									w = 1000;
									h = 550;
									var left = (screen.width/2)-(w/2);
									var top = (screen.height/2)-(h/2);
									window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
								}
								
								function showDetC(LinkD)
								{
									w = 1000;
									h = 550;
									var left = (screen.width/2)-(w/2);
									var top = (screen.height/2)-(h/2);
									window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
								}
		                    </script>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Upah</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C2A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintUB; ?>')" style="cursor: pointer;">
										<?php echo number_format($C2B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C2BP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetC('<?php echo $secPrintUC; ?>')" style="cursor: pointer;">
										<?php echo number_format($C2C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C2CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C2AP, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Alat</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C3A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintTB; ?>')" style="cursor: pointer;">
										<?php echo number_format($C3B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C3BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetC('<?php echo $secPrintTC; ?>')" style="cursor: pointer;">
										<?php echo number_format($C3C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C3CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C3AP, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Subkontraktor</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C4A, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintSB; ?>')" style="cursor: pointer;">
										<?php echo number_format($C4B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C4BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintSC; ?>')" style="cursor: pointer;">
										<?php echo number_format($C4C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C4CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C4AP, 0); ?></td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;Biaya Overhead</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C5AP, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintGB; ?>')" style="cursor: pointer;">
										<?php echo number_format($C5B, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C5BPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right">
									<a onclick="showDetB('<?php echo $secPrintGC; ?>')" style="cursor: pointer;">
										<?php echo number_format($C5C, 0); ?>
									</a>
								</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C5CPER, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($C5CP, 0); ?></td>
		              	  	</tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (B)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_CA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_CB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_CC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php //echo number_format($SUBTOT_CD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: C -> B. BIAYA HUTANG ---// ?>


	              	  	<?php //--- START 	: D -> C. HUTANG DALAM PROSES ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">C.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">HUTANG DALAM PROSES</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Kasbon</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Hutang Belum Dibukukan</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (C)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_DA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_DB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_DC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_DD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: D -> C. HUTANG DALAM PROSES ---// ?>


	              	  	<?php //--- START 	: E -> D. BIAYA PROYEK DI PUSAT ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">D.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">BIAYA PROYEK DI PUSAT</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;BUASO</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Contingency</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (D)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_EA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_EB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_EC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_ED, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: E -> D. BIAYA PROYEK DI PUSAT ---// ?>


	              	  	<?php //--- START 	: F -> E. STOCK / PERSEDIAAN ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">E.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">STOCK / PERSEDIAAN</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Material</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Lainnya</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (E)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_FA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_FB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_FC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_FD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: F -> E. STOCK / PERSEDIAAN ---// ?>


	              	  	<?php //--- START 	: G -> F. BEBAN-BEBAN ---// ?>
		                	<tr style="font-weight:bold; font-size:12px">
								<td style="<?=$stlLine3?>" align="center">F.</td>
								<td style="<?=$stlLine3?>; font-size: 13px; text-decoration: underline;" colspan="2">BEBAN-BEBAN</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
								<td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Beban Alat</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format(0, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Overhead Pusat (<?=number_format($PRJ_LROVH,2);?> %)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BAU_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BAUB_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BAUB_PLANP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BAU_REAL, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BAU_REALP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BAU_PROYEKSI, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;Bunga Bank (<?=number_format($PRJ_LRBNK,2);?>%)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BNG_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BNGB_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BNGB_PLANP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BNG_REAL, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BNG_REALP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_BNG_PROYEKSI, 0); ?></td>
		               	    </tr>
		                	<tr>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> border-right: 0">&nbsp;PPh (<?=number_format($PRJ_LRPPH,2);?>%)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_PPH_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_PPHB_PLAN, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_PPHB_PLANP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_PPH_REAL, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_PPH_REALP, 2); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($BB_PPH_PROYEKSI, 0); ?></td>
		               	    </tr>
		                    <?php
								$SUBTOT_GA	= $BB_BAU_PLAN + $BB_BNG_PLAN + $BB_PPH_PLAN;
								$SUBTOT_GB	= $BB_BAUB_PLAN + $BB_BNGB_PLAN + $BB_PPHB_PLAN;
								$SUBTOT_GC	= $BB_BAU_REAL + $BB_BNG_REAL + $BB_PPH_REAL;
								$SUBTOT_GD	= $BB_BAU_PROYEKSI + $BB_BNG_PROYEKSI + $BB_PPH_PROYEKSI;
							?>
		                	<tr style="font-weight: bold; color: #2E86C1; background:#CCCCCC; text-transform: uppercase;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td colspan="2" style="<?=$stlLine5?> font-weight:bold;">Sub Total (F)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_GA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_GB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_GC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_GD, 0); ?></td>
		              	  	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
	              	  	<?php //--- END 	: E -> F. BEBAN-BEBAN ---// ?>


	              	  	<?php //--- START 	: H -> G. LABA / RUGI ---// ?>
		                    <?php
		                    	// TOTAL 	= SUB TOTAL (A) - SUB TOTAL (B) - SUB TOTAL (C)
								$SUBTOT_HA	= $SUBTOT_AA - $SUBTOT_BA - $SUBTOT_CA - $SUBTOT_DA - $SUBTOT_EA + $SUBTOT_FA - $SUBTOT_GA;
								$SUBTOT_HB	= $SUBTOT_AB - $SUBTOT_BB - $SUBTOT_CB - $SUBTOT_DB - $SUBTOT_EA + $SUBTOT_FB - $SUBTOT_GA;
								$SUBTOT_HC	= $SUBTOT_AC - $SUBTOT_BC - $SUBTOT_CC - $SUBTOT_DC - $SUBTOT_EC + $SUBTOT_FC - $SUBTOT_GC;
								$SUBTOT_HD	= $SUBTOT_AD - $SUBTOT_BD - $SUBTOT_CD - $SUBTOT_DD - $SUBTOT_ED + $SUBTOT_FD - $SUBTOT_GD;
							?>
		                	<tr style="font-weight:bold; background:#CCCCCC; font-size:12px">
								<td style="<?=$stlLine5?>" align="center">G.</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;LABA / RUGI : (A-B-C-D+E-F)</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_HA, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_HB, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_HC, 0); ?></td>
								<td style="<?=$stlLine5?> text-align:right">&nbsp;</td>
								<td style="<?=$stlLine5?> text-align:right"><?php echo number_format($SUBTOT_HD, 0); ?></td>
		              	  	</tr>
		                	<tr style="font-weight:bold;">
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>" colspan="2">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
								<td style="<?=$stlLine5?>">&nbsp;</td>
		           	      	</tr>
	              	  	<?php //--- END 	: H -> G. LABA / RUGI ---// ?>
	                </table>
	           	  </td>
	            </tr>
	        </table>
	        <br>
	        <div class="row no-print">
	            <div class="col-xs-12">
	                <div id="Layer1">
	                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
	                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
	                    <i class="fa fa-download"></i> Generate PDF
	                    </button>
	                </div>
	            </div>
	        </div>
		</section>
	</div>
</body>
</html>