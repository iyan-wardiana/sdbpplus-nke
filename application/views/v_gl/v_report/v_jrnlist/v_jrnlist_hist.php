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
	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
	$comp_name 	= $this->session->userdata['comp_name'];
		    
	$sqlApp         = "SELECT * FROM tappname";
	$resultaApp     = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
	    $appName    = $therow->app_name;
	    $comp_init  = strtolower($therow->comp_init);
	    $comp_name  = $therow->comp_name;
	endforeach;

	$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$resPRJ	= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $rowPRJ) :
		$PRJCODE 	= $rowPRJ->PRJCODE;
		$PRJNAME 	= $rowPRJ->PRJNAME;
		$PRJCOST 	= $rowPRJ->PRJCOST;
	endforeach;

    $stlLine1 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine2 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden;";
    $stlLine3 		= "border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine4 		= "border-left-style: hidden; border-right-style: hidden; border-top-style: hidden;";
    $stlLine5 		= "border-left-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
    $stlLine6 		= "border-top:double; border-bottom:double; border-left-style: hidden; border-right-style: hidden;";
    $stlLine7 		= "border-bottom:groove; border-right-style: hidden; border-top-style: hidden;";
    $stlLine8 		= "border-left-style: hidden; border-top-style: hidden; border-right-style: hidden; border-bottom-style: hidden;";
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
	                <td class="style2" style="text-align:center; font-weight:bold;">
	                	<span class="style2" style="font-weight:bold; font-size:12px">DETIL DOKUMEN JURNAL</span>
	                </td>
	            </tr>
	            <tr>
	                <td class="style2" style="text-align:left;">&nbsp;</td>
	            </tr>
	            <?php
	            	$DOC_01 		= "";
	            	$DOC_NO 		= "";
	            	$SHOW_PR 		= 0;
	            	$SHOW_PO 		= 0;
	            	$SHOW_IR 		= 0;
	            	$SHOW_UM 		= 0;
	            	$SHOW_SPK 		= 0;
	            	$SHOW_OPN 		= 0;
	            	$SHOW_TTK 		= 0;
	            	$SHOW_MC 		= 0;
	            	$SHOW_PINV 		= 0;
	            	$SHOW_INV 		= 0;
	            	$SHOW_BP 		= 0;
	            	$SHOW_BR 		= 0;
	            	$SHOW_VCASH		= 0;
	            	$SHOW_VLK		= 0;
	            	$SHOW_PD		= 0;

	            	$PR_NUM 		= "";
	            	$PR_CODE 		= "";
	            	$PO_NUM 		= "";
	            	$PO_CODE 		= "";
	            	$IR_NUM 		= "";
	            	$IR_CODE 		= "";
	            	$UM_NUM 		= "";
	            	$UM_CODE 		= "";
	            	$WO_NUM 		= "";
	            	$WO_CODE 		= "";
	            	$OPNH_NUM 		= "";
	            	$OPNH_CODE 		= "";
	            	$TTK_NUM 		= "";
	            	$TTK_CODE 		= "";
	            	$INV_NUM 		= "";
	            	$INV_CODE 		= "";
	            	$BP_NUM 		= "";
	            	$BP_CODE 		= "";
	            	$JrnH_Code 		= "";
	            	$Manual_No 		= "";

	            	$QRY_PR 		= "";
	            	$QRY_PO 		= "";
	            	$QRY_WO 		= "";
	            	$QRY_OPN 		= "";
	            	$QRY_TTK 		= "";
	            	$QRY_INV 		= "";
	            	$QRY_BP 		= "";
	            	$QRY_PD 		= "";

	            	if($JRN_TYPE == 'VCASH')			// Y
	            	{
	            		$JRN_TYPED 	= "Voucher Cash";
		            	$SHOW_VCASH	= 1;
        				$IDX_02 	= 0;
	            		$QRY_VCASH 	= "SELECT JournalH_Code, JournalH_Date AS JRN_DATE, Manual_No, GEJ_STAT, JournalH_Desc, SPLCODE, SPLDESC
	            						FROM tbl_journalheader_vcash WHERE JournalH_Code IN ('$JRN_NUM')";
	            		$r_01 		= $this->db->query($QRY_VCASH)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_02 	= $IDX_02+1;
	            			$JRNVC_CODE	= $rw_01->JournalH_Code;
	            			$DOC_NO 	= $rw_01->Manual_No;
	            			$JRN_DATE 	= $rw_01->JRN_DATE;

	            			if($IDX_02 == 1)
	            				$COLL_VCASH 	= $JRNVC_CODE;
	            			else if($IDX_02 > 1)
	            				$COLL_VCASH 	= "$COLL_VCASH', '$JRNVC_CODE";
	            		endforeach;

	            		$s_BP 		= "tbl_bp_detail A
            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
            							WHERE A.CBD_DOCNO IN ('$COLL_VCASH') AND B.CB_STAT NOT IN (5,9)";
	            		$r_BP 		= $this->db->count_all($s_BP);
	            		if($r_BP > 0)
	            		{
	            			$SHOW_BP 	= 1;
	            			$QRY_BP 	= "SELECT B.CB_NUM, B.CB_CODE, B.CB_DATE, B.CB_SOURCE, A.CBD_DOCNO, B.CB_STAT, B.CB_NOTES, B.CB_PAYFOR, C.SPLDESC
	            							FROM tbl_bp_detail A
	            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
	            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
	            							WHERE A.CBD_DOCNO IN ('$COLL_VCASH') AND B.CB_STAT NOT IN (5,9)";
	            		}
	            	}
					elseif($JRN_TYPE == 'BP')			// Y
					{
	            		$JRN_TYPED 	= "Pembayaran";
	            		$COLL_VC 	= "";
	            		$COLL_TTK 	= "";
	            		$COLL_OPIR 	= "";
	            		$COLL_WO 	= "";
	            		$COLL_IR 	= "";
	            		$COLL_PO 	= "";
	            		$COLL_PR 	= "";
	            		$COLL_PD 	= "";
	            		$IDX_01 	= 0;
	            		$SHOW_BP 	= 1;
            			$QRY_BP 	= "SELECT B.CB_NUM, B.CB_CODE, B.CB_DATE, B.CB_SOURCE, A.CBD_DOCNO, B.CB_STAT, B.CB_NOTES, B.CB_PAYFOR, C.SPLDESC
            							FROM tbl_bp_detail A
            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
            							WHERE B.CB_NUM = '$JRN_NUM' AND B.CB_STAT NOT IN (5,9)";
	            		$r_01 		= $this->db->query($QRY_BP)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_01 	= $IDX_01+1;
	            			$CB_NUM 	= $rw_01->CB_NUM;
	            			$CB_CODE 	= $rw_01->CB_CODE;
	            			$DOC_NO 	= $rw_01->CB_CODE;
	            			$JRN_DATE 	= $rw_01->CB_DATE;
	            			$CB_SOURCE	= $rw_01->CB_SOURCE;
	            			$CB_SRC_NO	= $rw_01->CBD_DOCNO;
	            			$CB_STAT	= $rw_01->CB_STAT;
	            			$CB_NOTES	= $rw_01->CB_NOTES;
	            			$SPLCODE 	= $rw_01->CB_PAYFOR;
	            			$SPLDESC 	= $rw_01->SPLDESC;

	            			//echo "CB_SOURCE = $CB_SOURCE";
	            			if($IDX_01 == 1)
	            				$COLL_VC 	= $CB_SRC_NO;
	            			else if($IDX_01 > 1)
	            				$COLL_VC 	= "$COLL_VC', '$CB_SRC_NO";

	            			if($CB_SOURCE == 'VCASH')
	            			{
	            				$SHOW_VCASH	= 1;
	            				$IDX_02 	= 0;
			            		$QRY_VCASH 	= "SELECT JournalH_Code, JournalH_Date AS JRN_DATE, Manual_No, GEJ_STAT, JournalH_Desc, SPLCODE, SPLDESC
			            						FROM tbl_journalheader_vcash WHERE JournalH_Code IN ('$COLL_VC')";
			            		$r_01 		= $this->db->query($QRY_VCASH)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_02 	= $IDX_02+1;
			            			$JRNPD_CODE	= $rw_01->JournalH_Code;

			            			if($IDX_02 == 1)
			            				$COLL_PD 	= $JRNPD_CODE;
			            			else if($IDX_02 > 1)
			            				$COLL_PD 	= "$COLL_PD', '$JRNPD_CODE";
			            		endforeach;
	            			}
	            			elseif($CB_SOURCE == 'PINV')
	            			{
	            				$SHOW_INV 	= 1;
	            				$IDX_02 	= 0;
				            	$IDX_03 	= 0;
				            	$IDX_04 	= 0;
				            	$IDX_05 	= 0;
	            				$QRY_INV 	= "SELECT B.INV_NUM, B.INV_CODE, B.INV_DATE, B.INV_NOTES, B.INV_STAT, A.TTK_NUM, A.TTK_CODE, B.SPLCODE, C.SPLDESC
	            								FROM tbl_pinv_detail A
		            								INNER JOIN tbl_pinv_header B ON B.INV_NUM = A.INV_NUM
		            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
		            							WHERE A.INV_NUM IN ('$COLL_VC') AND B.INV_STAT NOT IN (5,9)";
		            			$r_01 		= $this->db->query($QRY_INV)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_02 	= $IDX_02+1;
			            			$TTK_NUM	= $rw_01->TTK_NUM;

			            			if($IDX_02 == 1)
			            				$COLL_TTK 	= $TTK_NUM;
			            			else if($IDX_02 > 1)
			            				$COLL_TTK 	= "$COLL_TTK', '$TTK_NUM";
			            		endforeach;

			            		if($IDX_02 > 0)
			            		{
			            			$s_TTK 		= "tbl_ttk_detail A INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
				            						WHERE A.TTK_NUM IN ('$COLL_TTK') AND B.TTK_STAT NOT IN (5,9)";
				            		$r_TTK 		= $this->db->count_all($s_TTK);
				            		if($r_TTK > 0)
				            		{
				            			$SHOW_TTK 	= 1;
				            			$QRY_TTK 	= "SELECT DISTINCT A.TTK_NUM, A.TTK_CODE, B.TTK_DATE, B.TTK_NOTES, B.TTK_STAT, B.TTK_CATEG, B.SPLCODE, C.SPLDESC,
				            								B.TTK_CATEG FROM tbl_ttk_detail A
				            								INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
				            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
				            							WHERE A.TTK_NUM IN ('$COLL_TTK') AND B.TTK_STAT NOT IN (5,9)";
				            			$QRY_TTK1 	= "SELECT DISTINCT A.TTK_NUM, A.TTK_CODE, B.TTK_DATE, B.TTK_NOTES, B.TTK_STAT, B.TTK_CATEG, B.SPLCODE, C.SPLDESC,
				            								A.TTK_REF1_NUM, B.TTK_CATEG FROM tbl_ttk_detail A
				            								INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
				            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
				            							WHERE A.TTK_NUM IN ('$COLL_TTK') AND B.TTK_STAT NOT IN (5,9)";
					            		$r_01 		= $this->db->query($QRY_TTK1)->result();
					            		foreach($r_01 as $rw_01):
					            			$IDX_03 	= $IDX_03+1;
					            			$REF_NUM 	= $rw_01->TTK_REF1_NUM;
					            			$REF_CATEG 	= $rw_01->TTK_CATEG;

					            			if($IDX_03 == 1)
					            				$COLL_OPIR 	= $REF_NUM;
					            			else if($IDX_03 > 1)
					            				$COLL_OPIR 	= "$COLL_OPIR', '$REF_NUM";
					            		endforeach;
				            		}

				            		if($IDX_03 > 0 && $REF_CATEG == 'OPN')
				            		{
				            			$SHOW_OPN	= 1;
				            			$QRY_OPN 	= "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.OPNH_DATE AS JRN_DATE, A.OPNH_NOTE, A.OPNH_TYPE, A.OPNH_STAT,
				            								A.WO_NUM, A.WO_CODE, B.SPLCODE, B.SPLDESC
				            							FROM tbl_opn_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE OPNH_NUM IN ('$COLL_OPIR')";
					            		$r_01 		= $this->db->query($QRY_OPN)->result();
					            		foreach($r_01 as $rw_01):
					            			$IDX_04 	= $IDX_04+1;
					            			$WO_NUM 	= $rw_01->WO_NUM;

					            			if($IDX_04 == 1)
					            				$COLL_WO 	= $WO_NUM;
					            			else if($IDX_04 > 1)
					            				$COLL_WO 	= "$COLL_WO', '$WO_NUM";
					            		endforeach;

					            		if($IDX_04 > 0)
					            		{
					            			$SHOW_SPK	= 1;
					            			$QRY_WO 	= "SELECT A.WO_CODE, A.WO_DATE, A.WO_VALUE, A.WO_NOTE, A.SPLCODE, B.SPLDESC FROM tbl_wo_header A
					            							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					            							WHERE A.WO_NUM IN ('$COLL_WO')";
					            		}
				            		}
				            		elseif($IDX_03 > 0 && $REF_CATEG == 'IR')
				            		{
				            			$SHOW_IR	= 1;
				            			$QRY_IR 	= "SELECT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DATE AS JRN_DATE, A.IR_NOTE, A.IR_STAT,
				            								A.PO_NUM, A.PO_CODE, A.PR_NUM, A.PR_CODE, A.SPLCODE, B.SPLDESC
	            										FROM tbl_ir_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE IR_NUM  IN ('$COLL_OPIR')";
					            		$r_01 		= $this->db->query($QRY_IR)->result();
					            		foreach($r_01 as $rw_01):
					            			$IDX_04 	= $IDX_04+1;
					            			$PR_NUM 	= $rw_01->PR_NUM;
					            			$PO_NUM 	= $rw_01->PO_NUM;

					            			if($IDX_04 == 1)
					            			{
					            				$COLL_PR 	= $PR_NUM;
					            				$COLL_PO 	= $PO_NUM;
					            			}
					            			else if($IDX_04 > 1)
					            			{
					            				$COLL_PR 	= "$COLL_PR', '$PR_NUM";
					            				$COLL_PO 	= "$COLL_PO', '$PO_NUM";
					            			}
					            		endforeach;

					            		if($IDX_04 > 0)
					            		{
					            			$SHOW_PO	= 1;
					            			$QRY_PO 	= "SELECT A.PO_CODE, A.PO_DATE, A.PO_TOTCOST, A.PO_NOTES, A.SPLCODE, B.SPLDESC FROM tbl_po_header A
				            								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE A.PO_NUM IN ('$COLL_PO')";

					            			$SHOW_PR	= 1;
					            			$QRY_PR 	= "SELECT PR_CODE, PR_DATE, PR_RECEIPTD, PR_REQUESTER, PR_NOTE FROM tbl_pr_header WHERE PR_NUM IN ('$COLL_PR')";
					            		}
				            		}
				            	}
	            			}
	            			elseif($CB_SOURCE == 'PD')
	            			{
	            				$SHOW_PD 	= 1;
	            				$IDX_02 	= 0;
			            		$QRY_PD 	= "SELECT DISTINCT A.Manual_No AS JournalNo, A.JournalH_Date AS JRN_DATE, A.Ref_Number, A.Faktur_No, A.Faktur_Code,
			            							A.SPLCODE, A.SPLDESC, B.JournalH_Code, B.Manual_No, B.JournalH_Date, B.JournalH_Desc, B.GEJ_STAT
			            						FROM tbl_journaldetail A LEFT JOIN tbl_journalheader_pd B ON A.Faktur_No = B.JournalH_Code 
			            						WHERE B.JournalH_Code IN ('$COLL_VC')";
			            		$r_01 		= $this->db->query($QRY_PD)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_02 	= $IDX_02+1;
			            			$JRNPD_CODE	= $rw_01->JournalH_Code;

			            			if($IDX_02 == 1)
			            				$COLL_PD 	= $JRNPD_CODE;
			            			else if($IDX_02 > 1)
			            				$COLL_PD 	= "$COLL_PD', '$JRNPD_CODE";
			            		endforeach;
	            			}
	            			elseif($CB_SOURCE == 'DP')
	            			{
	            				$TITLE_DESC = "UANG MUKA";
	            			}
	            			elseif($CB_SOURCE == 'PPD')
	            			{
	            				$TITLE_DESC = "PENYELESAIAN PEMB. DIMUKA";
	            			}
	            		endforeach;
					}
					elseif($JRN_TYPE == 'PINBUK')
					{
	            		$JRN_TYPED 	= "Pindah Buku";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader_pb WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'CPRJ')			// Y
					{
		            	$SHOW_VLK	= 1;

	            		$JRN_TYPED 	= "Voucher Luar Kota";
		            	$SHOW_VCASH	= 1;
        				$IDX_02 	= 0;
	            		$QRY_VCASH 	= "SELECT JournalH_Code, JournalH_Date AS JRN_DATE, Manual_No, GEJ_STAT, JournalH_Desc, SPLCODE, SPLDESC
	            						FROM tbl_journalheader_cprj WHERE JournalH_Code IN ('$JRN_NUM')";
	            		$r_01 		= $this->db->query($QRY_VCASH)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_02 	= $IDX_02+1;
	            			$JRNVC_CODE	= $rw_01->JournalH_Code;
	            			$DOC_NO 	= $rw_01->Manual_No;
	            			$JRN_DATE 	= $rw_01->JRN_DATE;

	            			if($IDX_02 == 1)
	            				$COLL_VCASH 	= $JRNVC_CODE;
	            			else if($IDX_02 > 1)
	            				$COLL_VCASH 	= "$COLL_VCASH', '$JRNVC_CODE";
	            		endforeach;

	            		$s_BP 		= "tbl_bp_detail A
            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
            							WHERE A.CBD_DOCNO IN ('$COLL_VCASH') AND B.CB_STAT NOT IN (5,9)";
	            		$r_BP 		= $this->db->count_all($s_BP);
	            		if($r_BP > 0)
	            		{
	            			$SHOW_BP 	= 1;
	            			$QRY_BP 	= "SELECT B.CB_NUM, B.CB_CODE, B.CB_DATE, B.CB_SOURCE, A.CBD_DOCNO, B.CB_STAT, B.CB_NOTES, B.CB_PAYFOR, C.SPLDESC
	            							FROM tbl_bp_detail A
	            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
	            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
	            							WHERE A.CBD_DOCNO IN ('$COLL_VCASH') AND B.CB_STAT NOT IN (5,9)";
	            		}
					}
					elseif($JRN_TYPE == 'CHO-PD')		// Y
					{
	            		$JRN_TYPED 	= "Pembayaran Dimuka";
		            	$SHOW_PD 	= 1;
        				$IDX_02 	= 0;
	            		$QRY_PD 	= "SELECT DISTINCT A.Manual_No AS JournalNo, A.JournalH_Date AS JRN_DATE, A.Ref_Number, A.Faktur_No, A.Faktur_Code, A.SPLCODE, A.SPLDESC,
	            							B.JournalH_Code, B.Manual_No, B.JournalH_Date, B.JournalH_Desc, B.GEJ_STAT
	            						FROM tbl_journaldetail A LEFT JOIN tbl_journalheader_pd B ON A.Faktur_No = B.JournalH_Code 
	            						WHERE A.Manual_No IN ('$JRN_CODE')";
	            		$r_01 		= $this->db->query($QRY_PD)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_02 	= $IDX_02+1;
	            			$DOC_NO1	= $rw_01->JournalH_Code;		// KODE UNIK PD
	            			$DOC_CODE 	= $rw_01->Manual_No;			// KODE MANUAL PD
	            			$DOC_NO 	= $rw_01->Manual_No;			// KODE MANUAL PD
	            			$DOC_DATE 	= $rw_01->JournalH_Date;		// TANGGAL PD
	            			$DOC_NOTE 	= $rw_01->JournalH_Desc;		// KETERANGAN PD
	            			$JRN_CODE	= $rw_01->JournalNo;			// KODE MANUAL JURNAL PD
	            			$JRN_DATE 	= $rw_01->JRN_DATE;				// TANGGAL JURNAL PD

	            			if($IDX_02 == 1)
	            				$COLL_PD 	= $DOC_NO1;
	            			else if($IDX_02 > 1)
	            				$COLL_PD 	= "$COLL_PD', '$DOC_NO1";
	            		endforeach;

	            		$s_BP 		= "tbl_bp_detail A
            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
            							WHERE A.CBD_DOCNO IN ('$COLL_PD') AND B.CB_STAT NOT IN (5,9)";
	            		$r_BP 		= $this->db->count_all($s_BP);
	            		if($r_BP > 0)
	            		{
	            			$SHOW_BP 	= 1;
	            			$QRY_BP 	= "SELECT B.CB_NUM, B.CB_CODE, B.CB_DATE, B.CB_SOURCE, A.CBD_DOCNO, B.CB_STAT, B.CB_NOTES, B.CB_PAYFOR, C.SPLDESC
	            							FROM tbl_bp_detail A
	            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
	            								LEFT JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
	            							WHERE A.CBD_DOCNO IN ('$COLL_PD') AND B.CB_STAT NOT IN (5,9)";
	            		}
					}
					elseif($JRN_TYPE == 'BR')
					{
		            	$SHOW_PINV 	= 1;
		            	$SHOW_BR 	= 1;

	            		$JRN_TYPED 	= "Penerimaan Kas / Bank";
	            		$s_01 		= "SELECT BR_DATE AS JRN_DATE FROM tbl_br_header WHERE BR_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'JRNREV')
					{
	            		$JRN_TYPED 	= "Revisi Jurnal";
					}
					elseif($JRN_TYPE == 'OPN')			// Y
					{
	            		$JRN_TYPED 	= "Opname";
		            	$SHOW_OPN 	= 1;
		            	$IDX_02 	= 0;
		            	$IDX_03 	= 0;
		            	$IDX_04 	= 0;
	            		$QRY_OPN 	= "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.OPNH_DATE AS JRN_DATE, A.OPNH_NOTE, A.OPNH_TYPE, A.OPNH_STAT,
		            								A.WO_NUM, A.WO_CODE, A.TTK_CODE, A.INV_CODE, A.BP_CODE, B.SPLCODE, B.SPLDESC
		            							FROM tbl_opn_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE OPNH_NUM IN ('$JRN_NUM')";
	            		$r_01 		= $this->db->query($QRY_OPN)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_02 	= $IDX_02+1;
	            			$OPNH_NUM 	= $rw_01->OPNH_NUM;
	            			$JRN_NUM 	= $rw_01->OPNH_NUM;
	            			$DOC_NO 	= $rw_01->OPNH_CODE;
	            			$OPNH_CODE 	= $rw_01->OPNH_CODE;
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            			$WO_NUM 	= $rw_01->WO_NUM;
	            			$TTK_CODE 	= $rw_01->TTK_CODE;
	            			$INV_CODE 	= $rw_01->INV_CODE;
	            			$BP_CODE 	= $rw_01->BP_CODE;

	            			if($IDX_02 == 1)
	            				$COLL_WO 	= $WO_NUM;
	            			else if($IDX_02 > 1)
	            				$COLL_WO 	= "$COLL_WO', '$WO_NUM";
	            		endforeach;

	            		if($IDX_02 > 0)
	            		{
	            			$SHOW_SPK	= 1;
	            			$QRY_WO 	= "SELECT A.WO_CODE, A.WO_DATE, A.WO_VALUE, A.WO_NOTE, A.SPLCODE, B.SPLDESC FROM tbl_wo_header A
	            							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
	            							WHERE A.WO_NUM IN ('$COLL_WO')";
	            		}

	            		$s_TTK 		= "tbl_ttk_detail A INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
	            						WHERE A.TTK_REF1_NUM IN ('$OPNH_NUM') AND B.TTK_STAT NOT IN (5,9)";
	            		$r_TTK 		= $this->db->count_all($s_TTK);
	            		if($r_TTK > 0)
	            		{
	            			$SHOW_TTK 	= 1;
	            			$QRY_TTK 	= "SELECT DISTINCT A.TTK_NUM, A.TTK_CODE, B.TTK_DATE, B.TTK_NOTES, B.TTK_STAT, B.TTK_CATEG, B.SPLCODE, C.SPLDESC,
	            								A.TTK_REF1_NUM, B.TTK_CATEG FROM tbl_ttk_detail A
	            								INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
	            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
	            							WHERE A.TTK_REF1_NUM IN ('$OPNH_NUM') AND B.TTK_STAT NOT IN (5,9)";
		            		$r_01 		= $this->db->query($QRY_TTK)->result();
		            		foreach($r_01 as $rw_01):
		            			$IDX_03 	= $IDX_03+1;
		            			$REF_NUM 	= $rw_01->TTK_REF1_NUM;
		            			$TTK_NUM 	= $rw_01->TTK_NUM;

		            			if($IDX_03 == 1)
		            				$COLL_TTK 	= $TTK_NUM;
		            			else if($IDX_03 > 1)
		            				$COLL_TTK 	= "$COLL_TTK', '$TTK_NUM";
		            		endforeach;

		            		if($IDX_03 > 0)
		            		{
		            			$SHOW_INV 	= 1;
			            		$QRY_INV 	= "SELECT B.INV_NUM, B.INV_CODE, B.INV_DATE, B.INV_NOTES, B.INV_STAT, A.TTK_NUM, A.TTK_CODE, B.SPLCODE, C.SPLDESC
		        								FROM tbl_pinv_detail A
		            								INNER JOIN tbl_pinv_header B ON B.INV_NUM = A.INV_NUM
		            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
		            							WHERE A.TTK_NUM IN ('$COLL_TTK') AND B.INV_STAT NOT IN (5,9)";
		            			$r_01 		= $this->db->query($QRY_INV)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_02 	= $IDX_02+1;
			            			$TTK_NUM	= $rw_01->TTK_NUM;
			            			$JRN_DATE	= $rw_01->INV_DATE;

			            			if($IDX_02 == 1)
			            				$COLL_TTK 	= $TTK_NUM;
			            			else if($IDX_02 > 1)
			            				$COLL_TTK 	= "$COLL_TTK', '$TTK_NUM";
			            		endforeach;
		            		}
	            		}
					}
					elseif($JRN_TYPE == 'IR')			// Y
					{
	            		$JRN_TYPED 	= "Penerimaan Material (LPM)";
	            		$SHOW_IR	= 1;
	            		$IDX_04 	= 0;
            			$QRY_IR 	= "SELECT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DATE AS JRN_DATE, A.IR_NOTE, A.IR_STAT,
            								A.PO_NUM, A.PO_CODE, A.PR_NUM, A.PR_CODE, A.SPLCODE, B.SPLDESC
										FROM tbl_ir_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE IR_NUM  IN ('$JRN_NUM')";
	            		$r_01 		= $this->db->query($QRY_IR)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_04 	= $IDX_04+1;
	            			$PR_NUM 	= $rw_01->PR_NUM;
	            			$PO_NUM 	= $rw_01->PO_NUM;
	            			$IR_NUM 	= $rw_01->IR_NUM;
	            			$IR_CODE 	= $rw_01->IR_CODE;
	            			$DOC_NO 	= $rw_01->IR_CODE;
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            			$PO_NUM 	= $rw_01->PO_NUM;
	            			$PO_CODE 	= $rw_01->PO_CODE;
	            			$PR_NUM 	= $rw_01->PR_NUM;
	            			$PR_CODE 	= $rw_01->PR_CODE;

	            			if($IDX_04 == 1)
	            			{
	            				$COLL_PR 	= $PR_NUM;
	            				$COLL_PO 	= $PO_NUM;
	            			}
	            			else if($IDX_04 > 1)
	            			{
	            				$COLL_PR 	= "$COLL_PR', '$PR_NUM";
	            				$COLL_PO 	= "$COLL_PO', '$PO_NUM";
	            			}
	            		endforeach;

	            		if($IDX_04 > 0)
	            		{
	            			$SHOW_PO	= 1;
	            			$QRY_PO 	= "SELECT A.PO_CODE, A.PO_DATE, A.PO_TOTCOST, A.PO_NOTES, A.SPLCODE, B.SPLDESC FROM tbl_po_header A
            								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE A.PO_NUM IN ('$COLL_PO')";

	            			$SHOW_PR	= 1;
	            			$QRY_PR 	= "SELECT PR_CODE, PR_DATE, PR_RECEIPTD, PR_REQUESTER, PR_NOTE FROM tbl_pr_header WHERE PR_NUM IN ('$COLL_PR')";
	            		}
					}
					elseif($JRN_TYPE == 'PINV')				// Y
					{
	            		$JRN_TYPED 	= "Voucher LPM / OPN";
						$SHOW_INV 	= 1;
						$IDX_02 	= 0;
						$IDX_03 	= 0;
						$IDX_04 	= 0;
		            	$QRY_INV 	= "SELECT B.INV_NUM, B.INV_CODE, B.INV_DATE, B.INV_NOTES, B.INV_STAT, A.TTK_NUM, A.TTK_CODE, B.SPLCODE, C.SPLDESC
        								FROM tbl_pinv_detail A
            								INNER JOIN tbl_pinv_header B ON B.INV_NUM = A.INV_NUM
            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
            							WHERE A.INV_NUM IN ('$JRN_NUM') AND B.INV_STAT NOT IN (5,9)";
            			$r_01 		= $this->db->query($QRY_INV)->result();
	            		foreach($r_01 as $rw_01):
	            			$IDX_02 	= $IDX_02+1;
	            			$TTK_NUM	= $rw_01->TTK_NUM;
	            			$DOC_NO		= $rw_01->INV_CODE;
	            			$JRN_DATE	= $rw_01->INV_DATE;

	            			if($IDX_02 == 1)
	            				$COLL_TTK 	= $TTK_NUM;
	            			else if($IDX_02 > 1)
	            				$COLL_TTK 	= "$COLL_TTK', '$TTK_NUM";
	            		endforeach;

	            		if($IDX_02 > 0)
	            		{
	            			$s_TTK 		= "tbl_ttk_detail A INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
		            						WHERE A.TTK_NUM IN ('$COLL_TTK') AND B.TTK_STAT NOT IN (5,9)";
		            		$r_TTK 		= $this->db->count_all($s_TTK);
		            		if($r_TTK > 0)
		            		{
		            			$SHOW_TTK 	= 1;
		            			$QRY_TTK 	= "SELECT DISTINCT A.TTK_NUM, A.TTK_CODE, B.TTK_DATE, B.TTK_NOTES, B.TTK_STAT, B.TTK_CATEG, B.SPLCODE, C.SPLDESC,
		            								A.TTK_REF1_NUM, B.TTK_CATEG FROM tbl_ttk_detail A
		            								INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
		            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.SPLCODE
		            							WHERE A.TTK_NUM IN ('$COLL_TTK') AND B.TTK_STAT NOT IN (5,9)";
			            		$r_01 		= $this->db->query($QRY_TTK)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_03 	= $IDX_03+1;
			            			$REF_NUM 	= $rw_01->TTK_REF1_NUM;
			            			$REF_CATEG 	= $rw_01->TTK_CATEG;

			            			if($IDX_03 == 1)
			            				$COLL_OPIR 	= $REF_NUM;
			            			else if($IDX_03 > 1)
			            				$COLL_OPIR 	= "$COLL_OPIR', '$REF_NUM";
			            		endforeach;
		            		}

		            		if($IDX_03 > 0 && $REF_CATEG == 'OPN')
		            		{
		            			$SHOW_OPN	= 1;
		            			$QRY_OPN 	= "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.OPNH_DATE AS JRN_DATE, A.OPNH_NOTE, A.OPNH_TYPE, A.OPNH_STAT,
		            								A.WO_NUM, A.WO_CODE, B.SPLCODE, B.SPLDESC
		            							FROM tbl_opn_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE OPNH_NUM IN ('$COLL_OPIR')";
			            		$r_01 		= $this->db->query($QRY_OPN)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_04 	= $IDX_04+1;
			            			$WO_NUM 	= $rw_01->WO_NUM;

			            			if($IDX_04 == 1)
			            				$COLL_WO 	= $WO_NUM;
			            			else if($IDX_04 > 1)
			            				$COLL_WO 	= "$COLL_WO', '$WO_NUM";
			            		endforeach;

			            		if($IDX_04 > 0)
			            		{
			            			$SHOW_SPK	= 1;
			            			$QRY_WO 	= "SELECT A.WO_CODE, A.WO_DATE, A.WO_VALUE, A.WO_NOTE, A.SPLCODE, B.SPLDESC FROM tbl_wo_header A
			            							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
			            							WHERE A.WO_NUM IN ('$COLL_WO')";
			            		}
		            		}
		            		elseif($IDX_03 > 0 && $REF_CATEG == 'IR')
		            		{
		            			$SHOW_IR	= 1;
		            			$QRY_IR 	= "SELECT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DATE AS JRN_DATE, A.IR_NOTE, A.IR_STAT,
		            								A.PO_NUM, A.PO_CODE, A.PR_NUM, A.PR_CODE, A.SPLCODE, B.SPLDESC
        										FROM tbl_ir_header A INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE IR_NUM  IN ('$COLL_OPIR')";
			            		$r_01 		= $this->db->query($QRY_IR)->result();
			            		foreach($r_01 as $rw_01):
			            			$IDX_04 	= $IDX_04+1;
			            			$PR_NUM 	= $rw_01->PR_NUM;
			            			$PO_NUM 	= $rw_01->PO_NUM;

			            			if($IDX_04 == 1)
			            			{
			            				$COLL_PR 	= $PR_NUM;
			            				$COLL_PO 	= $PO_NUM;
			            			}
			            			else if($IDX_04 > 1)
			            			{
			            				$COLL_PR 	= "$COLL_PR', '$PR_NUM";
			            				$COLL_PO 	= "$COLL_PO', '$PO_NUM";
			            			}
			            		endforeach;

			            		if($IDX_04 > 0)
			            		{
			            			$SHOW_PO	= 1;
			            			$QRY_PO 	= "SELECT A.PO_CODE, A.PO_DATE, A.PO_TOTCOST, A.PO_NOTES, A.SPLCODE, B.SPLDESC FROM tbl_po_header A
		            								INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE WHERE A.PO_NUM IN ('$COLL_PO')";

			            			$SHOW_PR	= 1;
			            			$QRY_PR 	= "SELECT PR_CODE, PR_DATE, PR_RECEIPTD, PR_REQUESTER, PR_NOTE FROM tbl_pr_header WHERE PR_NUM IN ('$COLL_PR')";
			            		}
		            		}
		            	}
					}
					elseif($JRN_TYPE == 'GEJ')
					{
	            		$JRN_TYPED 	= "Jurnal Umum / Memorial";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'PRJINV')
					{
		            	$SHOW_MC 	= 1;
		            	$SHOW_PINV 	= 1;
		            	$SHOW_BR 	= 1;

	            		$JRN_TYPED 	= "Faktur Proyek";
	            		$s_01 		= "SELECT PINV_DATE AS JRN_DATE FROM tbl_projinv_header WHERE PINV_CODE = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'UM')
					{
		            	$SHOW_PR 	= 1;
		            	$SHOW_PO 	= 1;
		            	$SHOW_IR 	= 1;
		            	$SHOW_UM 	= 1;

	            		$JRN_TYPED 	= "Penggunaan Material";
	            		$s_01 		= "SELECT UM_DATE AS JRN_DATE FROM tbl_um_header WHERE UM_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
	            ?>
	            <tr>
	                <td class="style2" style="text-align:left;">
	                	<table width="100%" style="size:auto; font-size:12px;" cellspacing="-1" cellpadding="0">
	                        <tr style="text-align:left;">
	                         	<td width="14%" nowrap>Jenis Dokuemn</td>
	                          	<td width="1%">:</td>
	                          	<td width="85%" style="font-weight: bold; font-size: 13px;" nowrap>
	                          		<?php echo $JRN_TYPED; ?>
	                          	</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>No. Dokumen</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px;" nowrap>
	                                <?php echo $DOC_NO; ?>
	                            </td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>No. Journal</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px;" nowrap>
	                                <?php echo $JRN_CODE; ?>
	                            </td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>Tgl. Dokumen</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px;" nowrap>
	                                <?php echo $JRN_DATE; ?>
	                            </td>
	                      	</tr>
	                    </table>
	                </td>
	            </tr>

	            <tr>
					<td colspan="3" class="style2" style="text-align:left; font-size:12px">
		              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
		                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:13px;">
		                        <td style="<?=$stlLine6?> text-align: center;" width="10%" nowrap>NO. DOKUMEN</td>
		                        <td style="<?=$stlLine6?> text-align: center;" width="5%" nowrap>TGL. DOK.</td>
		                        <td style="<?=$stlLine6?> text-align: center;" width="10%" nowrap>NO. JURNAL</td>
		                        <td style="<?=$stlLine6?> text-align: center;" width="5%" nowrap>TGL. JURNAL</td>
		                        <td style="<?=$stlLine6?> text-align: left;" width="70%" nowrap>DESKRIPSI</td>
		                	</tr>
		                	<tr style="size:auto; font-size:4px;">
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		                        <td style="<?=$stlLine3?>">&nbsp;</td>
		              	  	</tr>
		              	  	<?php
				            	// $SHOW_PR 		= 0;	Y
				            	// $SHOW_PO 		= 0;	Y
				            	// $SHOW_IR 		= 0;	Y
				            	// $SHOW_UM 		= 0;
				            	// $SHOW_SPK 		= 0;	Y
				            	// $SHOW_OPN 		= 0;
				            	// $SHOW_TTK 		= 0;
				            	// $SHOW_MC 		= 0;
				            	// $SHOW_PINV 		= 0;
				            	// $SHOW_INV 		= 0;
				            	// $SHOW_BP 		= 0;
				            	// $SHOW_BR 		= 0;
				            	// $SHOW_VCASH		= 0;
				            	// $SHOW_VLK		= 0;
				            	// $SHOW_PD			= 0;

		              	  		if($SHOW_PR == 1)
		              	  		{
				            		$r_01 		= $this->db->query($QRY_PR)->result();
				            		foreach($r_01 as $rw_01):
				            			$PR_CODE 	= $rw_01->PR_CODE;
				            			$PR_DATE 	= $rw_01->PR_DATE;
				            			$PR_RECD 	= $rw_01->PR_RECEIPTD;
				            			$PR_REQ 	= $rw_01->PR_REQUESTER;
				            			$PR_NOTE 	= $rw_01->PR_NOTE;
				            		endforeach;
		              	  			?>
					                	<tr style="size:auto; text-align: center;">
					                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5">SURAT PERMINTAAN PENGADAAN (SPP)</td>
					              	  	</tr>
					                	<tr style="size:auto; text-align: center;">
					                        <td style="<?=$stlLine3?>" nowrap><?=$PR_CODE?></td>
					                        <td style="<?=$stlLine3?>" nowrap><?=$PR_DATE?></td>
					                        <td style="<?=$stlLine3?>" nowrap>-</td>
					                        <td style="<?=$stlLine3?>" nowrap>-</td>
					                        <td style="<?=$stlLine3?>"><?=$PR_NOTE?></td>
					              	  	</tr>
					                	<tr style="size:auto; font-size:4px;">
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					              	  	</tr>
			              			<?php
			              		}

		              	  		if($SHOW_PO == 1)
		              	  		{
				            		$r_01 		= $this->db->query($QRY_PO)->result();
				            		foreach($r_01 as $rw_01):
				            			$PO_CODE 	= $rw_01->PO_CODE;
				            			$PO_DATE 	= $rw_01->PO_DATE;
				            			$PO_TOTCOST	= $rw_01->PO_TOTCOST;
				            			$PO_NOTES	= $rw_01->PO_NOTES;
				            			$SPLCODE 	= $rw_01->SPLCODE;
				            			$SPLDESC 	= $rw_01->SPLDESC;
				            		endforeach;
		              	  			?>
					                	<tr style="size:auto; text-align: center;">
					                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5">ORDER PEMBELIAN (OP)</td>
					              	  	</tr>
					                	<tr style="size:auto; text-align: center;">
					                        <td style="<?=$stlLine3?>" nowrap><?=$PO_CODE?></td>
					                        <td style="<?=$stlLine3?>" nowrap><?=$PO_DATE?></td>
					                        <td style="<?=$stlLine3?>" nowrap>-</td>
					                        <td style="<?=$stlLine3?>" nowrap>-</td>
					                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $PO_NOTES"; ?></td>
					              	  	</tr>
					                	<tr style="size:auto; font-size:4px;">
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					              	  	</tr>
			              			<?php
			              		}

		              	  		if($SHOW_IR == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_IR)->result();
				            		foreach($r_01 as $rw_01):
				            			$IR_NUM 	= $rw_01->IR_NUM;
				            			$IR_CODE 	= $rw_01->IR_CODE;
				            			$IR_DATE 	= $rw_01->IR_DATE;
				            			$IR_NOTE	= $rw_01->IR_NOTE;
				            			$IR_STAT	= $rw_01->IR_STAT;
				            			$SPLCODE 	= $rw_01->SPLCODE;
				            			$SPLDESC 	= $rw_01->SPLDESC;

				            			if($IR_STAT == 3 || $IR_STAT == 6)
				            			{
						            		$s_01 		= "SELECT A.Manual_No, A.JournalH_Date AS JRN_DATE FROM tbl_journalheader A WHERE A.JournalH_Code = '$IR_NUM'";
						            		$r_01 		= $this->db->query($s_01)->result();
						            		foreach($r_01 as $rw_01):
						            			$Manual_No 	= $rw_01->Manual_No;
						            			$JRN_DATE 	= $rw_01->JRN_DATE;
						            		endforeach;
				            			}
			              	  			?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5">LAPORAN PENERIMAAN MATERIAL (LPM)</td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$IR_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$IR_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$Manual_No?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $IR_NOTE"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}

		              	  		if($SHOW_SPK == 1)
		              	  		{
				            		$r_01 		= $this->db->query($QRY_WO)->result();
				            		foreach($r_01 as $rw_01):
				            			$WO_CODE 	= $rw_01->WO_CODE;
				            			$WO_DATE 	= $rw_01->WO_DATE;
				            			$WO_VALUE	= $rw_01->WO_VALUE;
				            			$WO_NOTE	= $rw_01->WO_NOTE;
				            			$SPLCODE 	= $rw_01->SPLCODE;
				            			$SPLDESC 	= $rw_01->SPLDESC;
				            		endforeach;
		              	  			?>
					                	<tr style="size:auto; text-align: center;">
					                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5">SURAT PERINTAH KERJA (SPK)</td>
					              	  	</tr>
					                	<tr style="size:auto; text-align: center;">
					                        <td style="<?=$stlLine3?>" nowrap><?=$WO_CODE?></td>
					                        <td style="<?=$stlLine3?>" nowrap><?=$WO_DATE?></td>
					                        <td style="<?=$stlLine3?>" nowrap>-</td>
					                        <td style="<?=$stlLine3?>" nowrap>-</td>
					                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $WO_NOTE"; ?></td>
					              	  	</tr>
					                	<tr style="size:auto; font-size:4px;">
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					                        <td style="<?=$stlLine3?>">&nbsp;</td>
					              	  	</tr>
			              			<?php
			              		}

		              	  		if($SHOW_OPN == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_OPN)->result();
				            		foreach($r_01 as $rw_01):
				            			$OPNH_CODE 	= $rw_01->OPNH_CODE;
				            			$OPNH_DATE 	= $rw_01->OPNH_DATE;
				            			$OPNH_NOTE	= $rw_01->OPNH_NOTE;
				            			$OPNH_TYPE	= $rw_01->OPNH_TYPE;
				            			$OPNH_STAT	= $rw_01->OPNH_STAT;
				            			$SPLCODE 	= $rw_01->SPLCODE;
				            			$SPLDESC 	= $rw_01->SPLDESC;

				            			$TITLE_DESC = "OPNAME SPK";
				            			if($OPNH_TYPE == 1)
				            				$TITLE_DESC = "OPNAME SPK (RETENSI)";

				            			if($OPNH_STAT == 3 || $OPNH_STAT == 6)
				            			{
						            		$s_01 		= "SELECT A.Manual_No, A.JournalH_Date AS JRN_DATE FROM tbl_journalheader A WHERE A.JournalH_Code = '$JRN_NUM'";
						            		$r_01 		= $this->db->query($s_01)->result();
						            		foreach($r_01 as $rw_01):
						            			$Manual_No 	= $rw_01->Manual_No;
						            			$JRN_DATE 	= $rw_01->JRN_DATE;
						            		endforeach;
				            			}
		              	  				?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5"><?=$TITLE_DESC?></td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$OPNH_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$OPNH_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$Manual_No?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $OPNH_NOTE"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}

		              	  		if($SHOW_TTK == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_TTK)->result();
				            		foreach($r_01 as $rw_01):
				            			$TTK_NUM 	= $rw_01->TTK_NUM;
				            			$TTK_CODE 	= $rw_01->TTK_CODE;
				            			$TTK_DATE 	= $rw_01->TTK_DATE;
				            			$TTK_NOTES	= $rw_01->TTK_NOTES;
				            			$TTK_CATEG	= $rw_01->TTK_CATEG;
				            			$TTK_STAT	= $rw_01->TTK_STAT;
				            			$SPLCODE 	= $rw_01->SPLCODE;
				            			$SPLDESC 	= $rw_01->SPLDESC;

				            			$TITLE_DESC = "TTK LPM / PENGADAAN";
				            			if($TTK_CATEG == 'OPN')
				            				$TITLE_DESC = "TTK OPNAME SPK";
				            			elseif($TTK_CATEG == 'IR')
				            				$TITLE_DESC = "TTK LPM PENGADAAN";
		              	  				?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5"><?=$TITLE_DESC?></td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$TTK_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$TTK_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$Manual_No?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $TTK_NOTES"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}

		              	  		if($SHOW_INV == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_INV)->result();
				            		foreach($r_01 as $rw_01):
				            			$INV_NUM 	= $rw_01->INV_NUM;
				            			$INV_CODE 	= $rw_01->INV_CODE;
				            			$INV_DATE 	= $rw_01->INV_DATE;
				            			$INV_NOTES	= $rw_01->INV_NOTES;
				            			$SPLCODE 	= $rw_01->SPLCODE;
				            			$SPLDESC 	= $rw_01->SPLDESC;

					            		$s_BP 		= "tbl_bp_detail A INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM
					            						WHERE A.CBD_DOCNO = '$INV_NUM' AND B.CB_STAT NOT IN (5,9)";
					            		$r_BP 		= $this->db->count_all($s_BP);
					            		if($r_BP > 0)
					            		{
					            			$SHOW_BP 	= 1;
					            			$QRY_BP 	= "SELECT B.CB_NUM, B.CB_CODE, B.CB_DATE, B.CB_SOURCE, A.CBD_DOCNO, B.CB_STAT, B.CB_NOTES, B.CB_PAYFOR, C.SPLDESC
					            							FROM tbl_bp_detail A
					            								INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM
					            								INNER JOIN tbl_supplier C ON C.SPLCODE = B.CB_PAYFOR
					            							WHERE A.CBD_DOCNO = '$INV_NUM' AND B.CB_STAT NOT IN (5,9)";
					            		}

				            			$TITLE_DESC = "VOUCHER LPM / OPN";
		              	  				?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5"><?=$TITLE_DESC?></td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$INV_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$INV_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$Manual_No?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $INV_NOTES"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}

		              	  		if($SHOW_PD == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_PD)->result();
				            		foreach($r_01 as $rw_01):
				            			$GEJ_NO		= $rw_01->JournalH_Code;		// KODE UNIK PD
				            			$GEJ_CODE 	= $rw_01->Manual_No;			// KODE MANUAL PD
				            			$GEJ_DATE 	= $rw_01->JournalH_Date;		// TANGGAL PD
				            			$GEJ_DESC 	= $rw_01->JournalH_Desc;		// KETERANGAN PD
				            			$GEJ_STAT 	= $rw_01->GEJ_STAT;				// STATU PD
				            			$JRN_CODE	= $rw_01->JournalNo;			// KODE MANUAL JURNAL PD
				            			$JRN_DATE 	= $rw_01->JRN_DATE;				// TANGGAL JURNAL PD
				            			$SPLCODE	= $rw_01->SPLCODE;
				            			$SPLDESC	= $rw_01->SPLDESC;

				            			$TITLE_DESC = "PEMBAYARAN DIMUKA (PD)";
		              	  				?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5"><?=$TITLE_DESC?></td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$GEJ_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$GEJ_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $GEJ_DESC"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}

		              	  		if($SHOW_BP == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_BP)->result();
				            		foreach($r_01 as $rw_01):
				            			$CB_NUM 	= $rw_01->CB_NUM;
				            			$CB_CODE 	= $rw_01->CB_CODE;
				            			$CB_DATE 	= $rw_01->CB_DATE;
				            			$CB_SOURCE	= $rw_01->CB_SOURCE;
				            			$CB_STAT	= $rw_01->CB_STAT;
				            			$CB_NOTES	= $rw_01->CB_NOTES;
				            			$SPLCODE 	= $rw_01->CB_PAYFOR;
				            			$SPLDESC 	= $rw_01->SPLDESC;

				            			$TITLE_DESC = "PEMBAYARAN LAINNYA";
				            			if($CB_SOURCE == 'VCASH')
				            				$TITLE_DESC = "PEMBAYARAN VOUCHER CASH";
				            			elseif($CB_SOURCE == 'PINV')
				            				$TITLE_DESC = "PEMBAYARAN VOUHER LPM / OPNAME";
				            			elseif($CB_SOURCE == 'PD')
				            				$TITLE_DESC = "PEMBAYARAN PD";
				            			elseif($CB_SOURCE == 'DP')
				            				$TITLE_DESC = "PEMBAYARAN UANG MUKA";
				            			elseif($CB_SOURCE == 'PPD')
				            				$TITLE_DESC = "PEMBAYARAN PENYELESAIAN PEMB. DIMUKA";

				            			if($CB_STAT == 3 || $CB_STAT == 6)
				            			{
						            		$s_01 		= "SELECT A.Manual_No, A.JournalH_Date AS JRN_DATE FROM tbl_journalheader A WHERE A.JournalH_Code = '$CB_NUM'";
						            		$r_01 		= $this->db->query($s_01)->result();
						            		foreach($r_01 as $rw_01):
						            			$Manual_No 	= $rw_01->Manual_No;
						            			$JRN_DATE 	= $rw_01->JRN_DATE;
						            		endforeach;
				            			}
		              	  				?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5"><?=$TITLE_DESC?></td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$CB_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$CB_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$Manual_No?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $CB_NOTES"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}

		              	  		if($SHOW_VCASH == 1)
		              	  		{
		              	  			$Manual_No 	= "";
		              	  			$JRN_DATE 	= "";
				            		$r_01 		= $this->db->query($QRY_VCASH)->result();
				            		foreach($r_01 as $rw_01):
				            			$GEJ_NO		= $rw_01->JournalH_Code;
				            			$GEJ_CODE	= $rw_01->Manual_No;
				            			$GEJ_DATE 	= $rw_01->JRN_DATE;
				            			$GEJ_DESC	= $rw_01->JournalH_Desc;
				            			$GEJ_STAT	= $rw_01->GEJ_STAT;
				            			$SPLCODE	= $rw_01->SPLCODE;
				            			$SPLDESC	= $rw_01->SPLDESC;

				            			$TITLE_DESC = "VOUCHER CASH";

				            			if($GEJ_STAT == 3 || $GEJ_STAT == 6)
				            			{
						            		$s_01 		= "SELECT A.Manual_No, A.JournalH_Date AS JRN_DATE FROM tbl_journalheader A WHERE A.JournalH_Code = '$GEJ_NO'";
						            		$r_01 		= $this->db->query($s_01)->result();
						            		foreach($r_01 as $rw_01):
						            			$Manual_No 	= $rw_01->Manual_No;
						            			$JRN_DATE 	= $rw_01->JRN_DATE;
						            		endforeach;
				            			}
		              	  				?>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine2?>; text-align: center; font-weight: bold; font-style: italic;" colspan="5"><?=$TITLE_DESC?></td>
						              	  	</tr>
						                	<tr style="size:auto; text-align: center;">
						                        <td style="<?=$stlLine3?>" nowrap><?=$GEJ_CODE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$GEJ_DATE?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$Manual_No?></td>
						                        <td style="<?=$stlLine3?>" nowrap><?=$JRN_DATE?></td>
						                        <td style="<?=$stlLine3?>"><?php echo "$SPLCODE : $SPLDESC <br> $GEJ_DESC"; ?></td>
						              	  	</tr>
						                	<tr style="size:auto; font-size:4px;">
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						                        <td style="<?=$stlLine3?>">&nbsp;</td>
						              	  	</tr>
			              				<?php
				            		endforeach;
			              		}
			              	?>
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