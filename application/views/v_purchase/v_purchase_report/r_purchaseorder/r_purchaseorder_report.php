<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2018
 * File Name	= r_purchasereq.php
 * Location		= -
*/

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

setlocale(LC_ALL, 'id-ID', 'id_ID');

$this->load->view('template/head');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');
$LangID 	= $this->session->userdata['LangID'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt Arial, Helvetica, sans-serif;
        }
        * {
          box-sizing: border-box;
          -moz-box-sizing: border-box;
        }
        .page {
            width: 21.59cm;
            min-height: 29.7cm;
            padding-left: 1cm;
            padding-right: 1cm;
            padding-top: 1cm;
            padding-bottom: 1cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            background-size: 400px 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        @page {
           /* size: A4;*/
            margin: 0;
        }
        @media print {
            /*@page{size: portrait;}*/
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
            .hcol1{
                background-color: #F1C40F !important;
            }
            .hcol2{
                background-color: #FFFF00 !important;
            }
            .hcol3{
                background-color: #5DADE2 !important;
            }
            .hcol4{
                background-color: #9ACD32 !important;
            }
            .hcol5{
                background-color: #F1C40F !important;
            }
            .hcol6{
                background-color: #27AE60 !important;
            }
        }
    </style>
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>


<?php
	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Color')$Color = $LangTransl;
        if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        if($TranslCode == 'Nominal')$Nominal = $LangTransl;
        if($TranslCode == 'salesPrcCust')$salesPrcCust = $LangTransl;
        if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
        if($TranslCode == 'reportedBy')$reportedBy = $LangTransl;
        if($TranslCode == 'ApprovedBy')$ApprovedBy = $LangTransl;
        if($TranslCode == 'checkedBy')$checkedBy = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
	<?php
		$ADDQRY1 		= "";
		$ADDQRY2 		= "";
		if($PRJCODE != "All")
		{
			$ADDQRY1	= "A.PRJCODE IN ('$PRJCODE') AND";
			$ADDQRY2	= "B.PRJCODE IN ('$PRJCODE') AND";
			$ADDQRY3	= "C.PRJCODE IN ('$PRJCODE') AND";
		}
		/*if($COLREFCUST != "'All'")
			$ADDQRY2	= "A.CUST_CODE IN ($COLREFCUST) AND";*/
	?>
	<div class="page">
		<table border="0" width="100%" style="font-family: Calibri; font-weight: bold;">
            <tr>
		        <td width="10%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
		        <td width="5%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
		        <td width="60%" class="style2">&nbsp;</td>
		        <td width="25%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
		    </tr>
		    <tr>
		        <td rowspan="3" colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:180px; max-height:180px" ></td>
		        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:26px"><?php echo $comp_name; ?><br>
		          </td>
		  	</tr>
		    <tr>
		      	<td colspan="4" style="text-align:center; font-weight:bold;">LAPORAN HARIAN PEMBELIAN</td>
		    </tr>
		    <tr>
		      	<td colspan="4" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><!-- Per Tgl. <?php echo $EndDate; ?> --></span></td>
		    </tr>
		    <tr>
		        <td colspan="4" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
		    </tr>
		    <tr>
		        <td nowrap>No. Dokumen</td>
		        <td colspan="3">:</td>
		    </tr>
		    <tr>
		        <td nowrap>Tanggal Laporan</td>
		        <td colspan="3">: <?=strftime('%d %B %Y', strtotime(date('Y-m-d')))?></td>
		    </tr>
		    <tr>
		        <td nowrap>Periode Pelaporan</td>
		        <td colspan="3">: <?=strftime('%d %B %Y', strtotime($Start_Date))?></td>
		    </tr>
		</table>
		<br>
		<table border="1" width="100%" style="font-family: Calibri">
        	<tr class="hcol1" style="background:#F1C40F">
				<td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">PERIODE</td>
				<td width="15%" rowspan="2" colspan="2" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
				<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">No. PO</td>
				<td width="35%" colspan="3" nowrap style="text-align:center; font-weight:bold">TOTAL (Rp)</td>
				<td width="15%" rowspan="2" nowrap style="text-align:center; font-weight:bold">GRAND TOTAL</td>
	      	</tr>
        	<tr class="hcol1" style="background:#F1C40F">
				<td width="30" nowrap style="text-align:center; font-weight:bold"><?=strftime('%B', strtotime($Start_Date))?></td>
				<td width="35" nowrap style="text-align:center; font-weight:bold">Realisasi PO bln<br>Sebelumnya</td>
				<td width="35" nowrap style="text-align:center; font-weight:bold">Retur</td>
	      	</tr>
        	<tr>
				<td colspan="8" style="line-height: 1px">&nbsp;</td>
	      	</tr>
	      	<?php
	      		$Start_DateC	= date('Y-m-d', strtotime($Start_Date));
	      		$Start_DateM	= date('Y-m-01', strtotime($Start_Date));
	      		$StartDateM		= date('m', strtotime($Start_Date));
	      		$StartDateY		= date('Y', strtotime($Start_Date));
	      		$End_MonthBef	= date('Y-m-t', strtotime($Start_Date . '- 1 month'));

				// START : CURRENT DATE
					// KIMIA/DYESTUFF 	- PO PER TANGGAL AKTIF (CD) PADA BULAN AKTIF
						$TPOVDY_CD	= 0;						// TOTAL PO VOLUME DY 	_ CURRENT DATE
						$TPODDY_CD	= 0;						// TOTAL PO DISC DY 	_ CURRENT DATE
						$TPOCDY_CD	= 0;						// TOTAL PO COST DY 	_ CURRENT DATE
						$TPOTDY_CD	= 0;						// TOTAL PO TAX DY 		_ CURRENT DATE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_VOLM)>0,SUM(A.PO_VOLM),0) AS TPOVOLM, IF(SUM(A.PO_DISC)>0,SUM(A.PO_DISC),0) AS TPODISC,
											IF(SUM(A.PO_COST)>0,SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.TAXPRICE1)>0,SUM(A.TAXPRICE1),0) AS TTAXPPN
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'DY'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE = '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOVDY_CD	= $rowCUR->TPOVOLM;
							$TPODDY_CD	= $rowCUR->TPODISC;
							$TPOCDY_CD	= $rowCUR->TPOCOST;
							$TPOTDY_CD	= $rowCUR->TTAXPPN;
						endforeach;

					// KIMIA/DYESTUFF 	- SISA PO SEBELUMNYA (DY) YANG BELUM DIREALISASIKAN PADA BULAN YANG AKTIF
						$TPOCDY_DB	= 0;						// TOTAL PO COST DY _ DAY BEFORE
						$TIRCDY_DB	= 0;						// TOTAL IR COST DY _ DAY BEFORE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_COST)>0, SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.IR_AMOUNT)>0,SUM(A.IR_AMOUNT),0) AS TIRCOST
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'DY'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE < '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOCDY_DB	= $rowCUR->TPOCOST;
							$TIRCDY_DB	= $rowCUR->TIRCOST;
						endforeach;
						$TPORDY_DB	= $TPOCDY_DB - $TIRCDY_DB;	// TOTAL PO REMAIN DY _ DAY BEFORE

					// KIMIA/DYESTUFF 	- RETUR PO PER TGL AKTIF (CD) DARI BULAN  AKTIF
						$TRETCDY_CD	= 0;						// TOTAL RETURN COST DY _ CURRENT DATE
						$sqlCUR 	= "SELECT IF(SUM(A.RET_COST)>0,SUM(A.RET_COST),0) AS TRETCOST
										FROM tbl_ret_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'DY'
											INNER JOIN tbl_ret_header C ON A.RET_NUM = C.RET_NUM
										WHERE $ADDQRY1
											A.RET_DATE = '$Start_DateC' AND C.RET_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TRETCDY_CD	= $rowCUR->TRETCOST;
						endforeach;

					// BATUBARA 		- PO PER TANGGAL AKTIF (CD) PADA BULAN AKTIF
						$TPOVBB_CD	= 0;						// TOTAL PO VOLUME BB 	_ CURRENT DATE
						$TPODBB_CD	= 0;						// TOTAL PO DISC BB 	_ CURRENT DATE
						$TPOCBB_CD	= 0;						// TOTAL PO COST BB 	_ CURRENT DATE
						$TPOTBB_CD	= 0;						// TOTAL PO TAX BB 		_ CURRENT DATE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_VOLM)>0,SUM(A.PO_VOLM),0) AS TPOVOLM, IF(SUM(A.PO_DISC)>0,SUM(A.PO_DISC),0) AS TPODISC,
											IF(SUM(A.PO_COST)>0,SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.TAXPRICE1)>0,SUM(A.TAXPRICE1),0) AS TTAXPPN
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'BB'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE = '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOVBB_CD	= $rowCUR->TPOVOLM;
							$TPODBB_CD	= $rowCUR->TPODISC;
							$TPOCBB_CD	= $rowCUR->TPOCOST;
							$TPOTBB_CD	= $rowCUR->TTAXPPN;
						endforeach;

					// BATUBARA 		- SISA PO SEBELUMNYA (DY) YANG BELUM DIREALISASIKAN PADA BULAN YANG AKTIF
						$TPOCBB_DB	= 0;						// TOTAL PO COST BB 	_ DAY BEFORE
						$TIRCBB_DB	= 0;						// TOTAL IR COST BB 	_ DAY BEFORE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_COST)>0, SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.IR_AMOUNT)>0,SUM(A.IR_AMOUNT),0) AS TIRCOST
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'BB'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE < '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOCBB_DB	= $rowCUR->TPOCOST;
							$TIRCBB_DB	= $rowCUR->TIRCOST;
						endforeach;
						$TPORBB_DB	= $TPOCBB_DB - $TIRCBB_DB;	// TOTAL IR COST BB 	_ MONTH BEFORE

					// BATUBARA 		- RETUR PO PER TGL AKTIF (CD) DARI BULAN  AKTIF
						$TRETCBB_CD	= 0;						// TOTAL RETURN COST DY _ CURRENT DATE
						$sqlCUR 	= "SELECT IF(SUM(A.RET_COST)>0,SUM(A.RET_COST),0) AS TRETCOST
										FROM tbl_ret_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'BB'
											INNER JOIN tbl_ret_header C ON A.RET_NUM = C.RET_NUM
										WHERE $ADDQRY1
											A.RET_DATE = '$Start_DateC' AND C.RET_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TRETCBB_CD	= $rowCUR->TRETCOST;
						endforeach;

					// LAINNYA/OTHERS 	- PO PER TANGGAL AKTIF (CD) PADA BULAN AKTIF
						$TPOVOTH_CD	= 0;						// TOTAL PO VOLUME OTH 	_ CURRENT DATE
						$TPODOTH_CD	= 0;						// TOTAL PO DISC OTH 	_ CURRENT DATE
						$TPOCOTH_CD	= 0;						// TOTAL PO COST OTH 	_ CURRENT DATE
						$TPOTOTH_CD	= 0;						// TOTAL PO TAX OTH 	_ CURRENT DATE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_VOLM)>0,SUM(A.PO_VOLM),0) AS TPOVOLM, IF(SUM(A.PO_DISC)>0,SUM(A.PO_DISC),0) AS TPODISC,
											IF(SUM(A.PO_COST)>0,SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.TAXPRICE1)>0,SUM(A.TAXPRICE1),0) AS TTAXPPN
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG NOT IN ('DY','BB')
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE = '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOVOTH_CD	= $rowCUR->TPOVOLM;
							$TPODOTH_CD	= $rowCUR->TPODISC;
							$TPOCOTH_CD	= $rowCUR->TPOCOST;
							$TPOTOTH_CD	= $rowCUR->TTAXPPN;
						endforeach;
						
					// LAINNYA/OTHERS 	- SISA PO SEBELUMNYA (DY) YANG BELUM DIREALISASIKAN PADA BULAN YANG AKTIF
						$TPOCOTH_DB	= 0;						// TOTAL PO COST OTH 	_ DAY BEFORE
						$TIRCOTH_DB	= 0;						// TOTAL IR COST OTH 	_ DAY BEFORE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_COST)>0, SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.IR_AMOUNT)>0,SUM(A.IR_AMOUNT),0) AS TIRCOST
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG NOT IN ('DY','BB')
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE < '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOCOTH_DB	= $rowCUR->TPOCOST;
							$TIRCOTH_DB	= $rowCUR->TIRCOST;
						endforeach;
						$TPOROTH_DB	= $TPOCOTH_DB - $TIRCOTH_DB;

					// LAINNYA/OTHERS 	- RETUR PO PER TGL AKTIF (CD) DARI BULAN  AKTIF
						$TRETCOTH_CD= 0;						// TOTAL RETURN COST OTH _ CURRENT DATE
						$sqlCUR 	= "SELECT IF(SUM(A.RET_COST)>0,SUM(A.RET_COST),0) AS TRETCOST
										FROM tbl_ret_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG NOT IN ('DY','BB')
											INNER JOIN tbl_ret_header C ON A.RET_NUM = C.RET_NUM
										WHERE $ADDQRY1
											A.RET_DATE = '$Start_DateC' AND C.RET_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TRETCOTH_CD	= $rowCUR->TRETCOST;
						endforeach;

					$GTPOCD_CD		= $TPOCDY_CD + $TPOCBB_CD + $TPOCOTH_CD;
					$GTPOMB_CD		= $TPORDY_DB + $TPOCBB_DB + $TPOROTH_DB;
					$GTRETCD_CD		= $TRETCDY_CD + $TRETCBB_CD + $TRETCOTH_CD;

					// ------------------- R E A L I S A S I --------------------- //

					// KIMIA/DYESTUFF 	- IR PER TANGGAL AKTIF (CD) DARI PO BULAN AKTIF
						$TIRVDY_CD		= 0;						// TOTAL IR VOLUME DY 	_ CURRENT DATE
						$TIRDDY_CD		= 0;						// TOTAL IR DISC DY 	_ CURRENT DATE
						$TIRCDY_CD		= 0;						// TOTAL IR COST DY 	_ CURRENT DATE
						$TIRTDY_CD		= 0;						// TOTAL IR RETURN DY 	_ CURRENT DATE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'DY'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND MONTH(C.PO_DATE) = '$StartDateM' AND YEAR(C.PO_DATE) = '$StartDateY'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE = '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 		= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVDY_CD	= $rowCUR->TIRVOLM;
							$TIRDDY_CD	= $rowCUR->TIRDISC;
							$TIRCDY_CD	= $rowCUR->TIRCOST;
							$TIRTDY_CD	= $rowCUR->TTAXPPN;
						endforeach;

					// KIMIA/DYESTUFF 	- IR PER TANGGAL AKTIF (CD) DARI PO SEBELUMNYA (MB)
						$TIRVDY_CDMB	= 0;						// TOTAL IR VOLUME DY 	_ DAY BEFORE
						$TIRDDY_CDMB	= 0;						// TOTAL IR DISC DY 	_ DAY BEFORE
						$TIRCDY_CDMB	= 0;						// TOTAL IR COST DY 	_ DAY BEFORE
						$TIRTDY_CDMB	= 0;						// TOTAL IR TAX DY 		_ DAY BEFORE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'DY'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND C.PO_DATE <= '$End_MonthBef'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE = '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 		= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVDY_CDMB	= $rowCUR->TIRVOLM;
							$TIRDDY_CDMB	= $rowCUR->TIRDISC;
							$TIRCDY_CDMB	= $rowCUR->TIRCOST;
							$TIRTDY_CDMB	= $rowCUR->TTAXPPN;
						endforeach;

					// KIMIA/DYESTUFF 	- RETUR IR PER TGL AKTIF (CD) DARI BULAN  AKTIF
						$TIRRETCDY_CD	= 0;

					// BATUBARA 		- IR PER TANGGAL AKTIF (CD) DARI PO BULAN AKTIF
						$TIRVBB_CD		= 0;						// TOTAL IR VOLUME BB 	_ CURRENT DATE
						$TIRDBB_CD		= 0;						// TOTAL IR DISC BB 	_ CURRENT DATE
						$TIRCBB_CD		= 0;						// TOTAL IR COST BB 	_ CURRENT DATE
						$TIRTBB_CD		= 0;						// TOTAL IR TAX BB 		_ CURRENT DATE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'BB'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND MONTH(C.PO_DATE) = '$StartDateM' AND YEAR(C.PO_DATE) = '$StartDateY'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE = '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVBB_CD	= $rowCUR->TIRVOLM;
							$TIRDBB_CD	= $rowCUR->TIRDISC;
							$TIRCBB_CD	= $rowCUR->TIRCOST;
							$TIRTBB_CD	= $rowCUR->TTAXPPN;
						endforeach;

					// BATUBARA 		- IR PER TANGGAL AKTIF (CD) DARI PO SEBELUMNYA (MB)
						$TIRVBB_CDMB	= 0;						// TOTAL IR VOLUME BB 	_ DAY BEFORE
						$TIRDBB_CDMB	= 0;						// TOTAL IR DISC BB 	_ DAY BEFORE
						$TIRCBB_CDMB	= 0;						// TOTAL IR COST BB 	_ DAY BEFORE
						$TIRTBB_CDMB	= 0;						// TOTAL IR VOLUME BB 	_ DAY BEFORE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'BB'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND C.PO_DATE <= '$End_MonthBef'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE = '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVBB_CDMB	= $rowCUR->TIRVOLM;
							$TIRDBB_CDMB	= $rowCUR->TIRDISC;
							$TIRCBB_CDMB	= $rowCUR->TIRCOST;
							$TIRTBB_CDMB	= $rowCUR->TTAXPPN;
						endforeach;

					// BATUBARA 		- RETUR IR PER TGL AKTIF (CD) DARI BULAN  AKTIF
						$TIRRETCBB_CD	= 0;

					// LAINNYA/OTHERS 	- IR PER TANGGAL AKTIF (CD) DARI PO BULAN AKTIF
						$TIRVOTH_CD		= 0;						// TOTAL IR VOLUME OTH 	_ CURRENT DATE
						$TIRDOTH_CD		= 0;						// TOTAL IR DISC OTH 	_ CURRENT DATE
						$TIRCOTH_CD		= 0;						// TOTAL IR COST OTH 	_ CURRENT DATE
						$TIRTOTH_CD		= 0;						// TOTAL IR TAX OTH 	_ CURRENT DATE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG NOT IN ('DY','BB')
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND MONTH(C.PO_DATE) = '$StartDateM' AND YEAR(C.PO_DATE) = '$StartDateY'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE = '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVOTH_CD	= $rowCUR->TIRVOLM;
							$TIRDOTH_CD	= $rowCUR->TIRDISC;
							$TIRCOTH_CD	= $rowCUR->TIRCOST;
							$TIRTOTH_CD	= $rowCUR->TTAXPPN;
						endforeach;

					// LAINNYA/OTHERS 	- IR PER TANGGAL AKTIF (CD) DARI PO BULAN SEBELUMNYA (MB)
						$TIRVOTH_CDMB	= 0;						// TOTAL IR VOLUME OTH 	_ DAY BEFORE
						$TIRDOTH_CDMB	= 0;						// TOTAL IR DISC OTH 	_ DAY BEFORE
						$TIRCOTH_CDMB	= 0;						// TOTAL IR COST OTH 	_ DAY BEFORE
						$TIRTOTH_CDMB	= 0;						// TOTAL IR TAX OTH 	_ DAY BEFORE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG NOT IN ('DY','BB')
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND C.PO_DATE <= '$End_MonthBef'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE = '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVOTH_CDMB	= $rowCUR->TIRVOLM;
							$TIRDOTH_CDMB	= $rowCUR->TIRDISC;
							$TIRCOTH_CDMB	= $rowCUR->TIRCOST;
							$TIRTOTH_CDMBS	= $rowCUR->TTAXPPN;
						endforeach;

					// LAINNYA/OTHERS 	- RETUR IR PER TGL AKTIF (CD) DARI BULAN  AKTIF
						$TIRRETCOTH_CD	= 0;

					$GTIRCD_CD		= $TIRCDY_CD + $TIRCBB_CD + $TIRCOTH_CD;
					$GTIRCD_CDMB	= $TIRCDY_CDMB + $TIRCBB_CDMB + $TIRCOTH_CDMB;
					$GTIRRETCD_CD	= $TIRRETCDY_CD + $TIRRETCBB_CD + $TIRRETCOTH_CD;
				// END : CURRENT DAY

				// START : CURRENT MONTH
					// KIMIA/DYESTUFF 	- PO PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM)
						$TPOVDY_CM	= 0;						// TOTAL PO VOLUME DY 	_ CURRENT MONTH
						$TPODDY_CM	= 0;						// TOTAL PO DISC DY 	_ CURRENT MONTH
						$TPOCDY_CM	= 0;						// TOTAL PO COST DY 	_ CURRENT MONTH
						$TPOTDY_CM	= 0;						// TOTAL PO TAX DY 		_ CURRENT MONTH
						$sqlCUR 	= "SELECT IF(SUM(A.PO_VOLM)>0,SUM(A.PO_VOLM),0) AS TPOVOLM, IF(SUM(A.PO_DISC)>0,SUM(A.PO_DISC),0) AS TPODISC,
											IF(SUM(A.PO_COST)>0,SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.TAXPRICE1)>0,SUM(A.TAXPRICE1),0) AS TTAXPPN
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'DY'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE >= '$Start_DateM' AND C.PO_DATE <= '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOVDY_CM	= $rowCUR->TPOVOLM;
							$TPODDY_CM	= $rowCUR->TPODISC;
							$TPOCDY_CM	= $rowCUR->TPOCOST;
							$TPOTDY_CM	= $rowCUR->TTAXPPN;
						endforeach;

					// KIMIA/DYESTUFF 	- SISA PO BULAN SEBELUMNYA (MB) YANG BELUM DIREALISASIKAN
						$TPOCDY_MB	= 0;						// TOTAL PO COST DY _ MONTH BEFORE
						$TIRCDY_MB	= 0;						// TOTAL IR COST DY _ MONTH BEFORE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_COST)>0, SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.IR_AMOUNT)>0,SUM(A.IR_AMOUNT),0) AS TIRCOST
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'DY'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE <= '$End_MonthBef' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOCDY_MB	= $rowCUR->TPOCOST;
							$TIRCDY_MB	= $rowCUR->TIRCOST;
						endforeach;
						$TPORDY_MB	= $TPOCDY_MB - $TIRCDY_MB;	// TOTAL PO REMAIN DY _ MONTH BEFORE

					// KIMIA/DYESTUFF 	- RETUR PO PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM)
						$TRETCDY_CM	= 0;						// TOTAL RETUR COST DY _ CURRENT MONTH
						$sqlCUR 	= "SELECT IF(SUM(A.RET_COST)>0,SUM(A.RET_COST),0) AS TRETCOST
										FROM tbl_ret_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'DY'
											INNER JOIN tbl_ret_header C ON A.RET_NUM = C.RET_NUM
										WHERE $ADDQRY1
											A.RET_DATE >= '$Start_DateM' AND A.RET_DATE <= '$Start_DateC' AND C.RET_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TRETCDY_CM	= $rowCUR->TRETCOST;
						endforeach;

					// BATUBARA 		- PO PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM)
						$TPOVBB_CM	= 0;						// TOTAL PO VOLUME BB 	_ CURRENT MONTH
						$TPODBB_CM	= 0;						// TOTAL PO DISC BB 	_ CURRENT MONTH
						$TPOCBB_CM	= 0;						// TOTAL PO COST BB 	_ CURRENT MONTH
						$TPOTBB_CM	= 0;						// TOTAL PO TAX BB 		_ CURRENT MONTH
						$sqlCUR 	= "SELECT IF(SUM(A.PO_VOLM)>0,SUM(A.PO_VOLM),0) AS TPOVOLM, IF(SUM(A.PO_DISC)>0,SUM(A.PO_DISC),0) AS TPODISC,
											IF(SUM(A.PO_COST)>0,SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.TAXPRICE1)>0,SUM(A.TAXPRICE1),0) AS TTAXPPN
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'BB'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE >= '$Start_DateM' AND A.PO_DATE <= '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOVBB_CM	= $rowCUR->TPOVOLM;
							$TPODBB_CM	= $rowCUR->TPODISC;
							$TPOCBB_CM	= $rowCUR->TPOCOST;
							$TPOTBB_CM	= $rowCUR->TTAXPPN;
						endforeach;

					// BATUBARA 		- SISA PO BULAN SEBELUMNYA (MB) YANG BELUM DIREALISASIKAN
						$TPOCBB_MB	= 0;						// TOTAL PO COST BB _ MONTH BEFORE
						$TIRCBB_MB	= 0;						// TOTAL IR COST BB _ MONTH BEFORE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_COST)>0, SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.IR_AMOUNT)>0,SUM(A.IR_AMOUNT),0) AS TIRCOST
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'BB'
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE <= '$End_MonthBef' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOCBB_MB	= $rowCUR->TPOCOST;
							$TIRCBB_MB	= $rowCUR->TIRCOST;
						endforeach;
						$TPORBB_MB	= $TPOCBB_MB - $TIRCBB_MB;	// TOTAL PO REMAIN BB _ MONTH BEFORE

					// BATUBARA 		- RETUR PO PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM)
						$TRETCBB_CM	= 0;						// TOTAL RETUR COST BB _ CURRENT MONTH
						$sqlCUR 	= "SELECT IF(SUM(A.RET_COST)>0,SUM(A.RET_COST),0) AS TRETCOST
										FROM tbl_ret_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG = 'BB'
											INNER JOIN tbl_ret_header C ON A.RET_NUM = C.RET_NUM
										WHERE $ADDQRY1
											A.RET_DATE >= '$Start_DateM' AND A.RET_DATE <= '$Start_DateC' AND C.RET_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TRETCBB_CM	= $rowCUR->TRETCOST;
						endforeach;

					// LAINNYA/OTHERS 	- PO PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM)
						$TPOVOTH_CM	= 0;						// TOTAL PO VOLUME OTH 	_ CURRENT MONTH
						$TPODOTH_CM	= 0;						// TOTAL PO DISC OTH 	_ CURRENT MONTH
						$TPOCOTH_CM	= 0;						// TOTAL PO COST OTH 	_ CURRENT MONTH
						$TPOTOTH_CM	= 0;						// TOTAL PO TAX OTH 		_ CURRENT MONTH
						$sqlCUR 	= "SELECT IF(SUM(A.PO_VOLM)>0,SUM(A.PO_VOLM),0) AS TPOVOLM, IF(SUM(A.PO_DISC)>0,SUM(A.PO_DISC),0) AS TPODISC,
											IF(SUM(A.PO_COST)>0,SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.TAXPRICE1)>0,SUM(A.TAXPRICE1),0) AS TTAXPPN
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG NOT IN ('DY','BB')
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE >= '$Start_DateM' AND A.PO_DATE <= '$Start_DateC' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOVOTH_CM	= $rowCUR->TPOVOLM;
							$TPODOTH_CM	= $rowCUR->TPODISC;
							$TPOCOTH_CM	= $rowCUR->TPOCOST;
							$TPOTOTH_CM	= $rowCUR->TTAXPPN;
						endforeach;

					// LAINNYA/OTHERS 	- SISA PO BULAN SEBELUMNYA (MB) YANG BELUM DIREALISASIKAN
						$TPOCOTH_MB	= 0;						// TOTAL PO COST OTH _ MONTH BEFORE
						$TIRCOTH_MB	= 0;						// TOTAL IR COST OTH _ MONTH BEFORE
						$sqlCUR 	= "SELECT IF(SUM(A.PO_COST)>0, SUM(A.PO_COST),0) AS TPOCOST, IF(SUM(A.IR_AMOUNT)>0,SUM(A.IR_AMOUNT),0) AS TIRCOST
										FROM tbl_po_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG NOT IN ('DY','BB')
											INNER JOIN tbl_po_header C ON A.PO_NUM = C.PO_NUM AND A.PRJCODE = C.PRJCODE
										WHERE $ADDQRY1
											A.PO_DATE <= '$End_MonthBef' AND C.PO_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TPOCOTH_MB	= $rowCUR->TPOCOST;
							$TIRCOTH_MB	= $rowCUR->TIRCOST;
						endforeach;
						$TPOROTH_MB	= $TPOCOTH_MB - $TIRCOTH_MB;	// TOTAL PO REMAIN OTH _ MONTH BEFORE

					// LAINNYA/OTHERS 	- RETUR PO PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM)
						$TRETCOTH_CM	= 0;						// TOTAL RETUR COST OTH _ CURRENT MONTH
						$sqlCUR 	= "SELECT IF(SUM(A.RET_COST)>0,SUM(A.RET_COST),0) AS TRETCOST
										FROM tbl_ret_detail A
											INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
												AND B.ITM_CATEG NOT IN ('DY','BB')
											INNER JOIN tbl_ret_header C ON A.RET_NUM = C.RET_NUM
										WHERE $ADDQRY1
											A.RET_DATE >= '$Start_DateM' AND A.RET_DATE <= '$Start_DateC' AND C.RET_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TRETCOTH_CM	= $rowCUR->TRETCOST;
						endforeach;

					$GTPOCM_CM		= $TPOCDY_CM + $TPOCBB_CM + $TPOCOTH_CM;
					$GTPOMB_CM		= $TPOCDY_MB + $TPOCBB_MB + $TPOCOTH_MB;
					$GTPORETCM_CM	= $TRETCDY_CM + $TRETCBB_CM + $TRETCOTH_CM;

					// ------------------- R E A L I S A S I --------------------- //

					// KIMIA/DYESTUFF 	- IR PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM) DARI PO BULAN AKTIF
						$TIRVDY_CM		= 0;						// TOTAL IR VOLUME DY 	_ CURRENT MONTH
						$TIRDDY_CM		= 0;						// TOTAL IR DISC DY 	_ CURRENT MONTH
						$TIRCDY_CM		= 0;						// TOTAL IR COST DY 	_ CURRENT MONTH
						$TIRTDY_CM		= 0;						// TOTAL IR RETURN DY 	_ CURRENT MONTH
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'DY'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND MONTH(C.PO_DATE) = '$StartDateM' AND YEAR(C.PO_DATE) = '$StartDateY'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE >= '$Start_DateM' AND A.IR_DATE <= '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 		= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVDY_CM	= $rowCUR->TIRVOLM;
							$TIRDDY_CM	= $rowCUR->TIRDISC;
							$TIRCDY_CM	= $rowCUR->TIRCOST;
							$TIRTDY_CM	= $rowCUR->TTAXPPN;
						endforeach;

					// KIMIA/DYESTUFF 	- IR PER TGL. 1 s.d. TGL. LAPORAN DARI PO BULAN SEBELUMNYA (MB)
						$TIRVDY_CMMB	= 0;						// TOTAL IR VOLUME DY 	_ MONTH BEFORE
						$TIRDDY_CMMB	= 0;						// TOTAL IR DISC DY 	_ MONTH BEFORE
						$TIRCDY_CMMB	= 0;						// TOTAL IR COST DY 	_ MONTH BEFORE
						$TIRTDY_CMMB	= 0;						// TOTAL IR TAX DY 		_ MONTH BEFORE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'DY'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND C.PO_DATE <= '$End_MonthBef'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE >= '$Start_DateM' AND A.IR_DATE <= '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVDY_CMMB	= $rowCUR->TIRVOLM;
							$TIRDDY_CMMB	= $rowCUR->TIRDISC;
							$TIRCDY_CMMB	= $rowCUR->TIRCOST;
							$TIRTDY_CMMB	= $rowCUR->TTAXPPN;
						endforeach;

					// KIMIA/DYESTUFF 	- RETUR IR PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM) DARI PO BULAN AKTIF
						$TIRRETCDY_CM	= 0;

					// BATUBARA 		- IR PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM) DARI PO BULAN AKTIF
						$TIRVBB_CM		= 0;						// TOTAL IR VOLUME BB 	_ CURRENT MONTH
						$TIRDBB_CM		= 0;						// TOTAL IR DISC BB 	_ CURRENT MONTH
						$TIRCBB_CM		= 0;						// TOTAL IR COST BB 	_ CURRENT MONTH
						$TIRTBB_CM		= 0;						// TOTAL IR RETURN BB 	_ CURRENT MONTH
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'BB'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND MONTH(C.PO_DATE) = '$StartDateM' AND YEAR(C.PO_DATE) = '$StartDateY'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE >= '$Start_DateM' AND A.IR_DATE <= '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 		= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVBB_CM	= $rowCUR->TIRVOLM;
							$TIRDBB_CM	= $rowCUR->TIRDISC;
							$TIRCBB_CM	= $rowCUR->TIRCOST;
							$TIRTBB_CM	= $rowCUR->TTAXPPN;
						endforeach;

					// BATUBARA 		- IR PER TGL. 1 s.d. TGL. LAPORAN DARI PO BULAN SEBELUMNYA (MB)
						$TIRVBB_CMMB	= 0;						// TOTAL IR VOLUME BB 	_ MONTH BEFORE
						$TIRDBB_CMMB	= 0;						// TOTAL IR DISC BB 	_ MONTH BEFORE
						$TIRCBB_CMMB	= 0;						// TOTAL IR COST BB 	_ MONTH BEFORE
						$TIRTBB_CMMB	= 0;						// TOTAL IR TAX BB 		_ MONTH BEFORE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG = 'BB'
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND C.PO_DATE <= '$End_MonthBef'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE >= '$Start_DateM' AND A.IR_DATE <= '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVBB_CMMB	= $rowCUR->TIRVOLM;
							$TIRDBB_CMMB	= $rowCUR->TIRDISC;
							$TIRCBB_CMMB	= $rowCUR->TIRCOST;
							$TIRTBB_CMMB	= $rowCUR->TTAXPPN;
						endforeach;

					// BATUBARA 		- RETUR IR PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM) DARI PO BULAN AKTIF
						$TIRRETCBB_CM	= 0;

					// LAINNYA/OTHERS 	- IR PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM) DARI PO BULAN AKTIF
						$TIRVOTH_CM		= 0;						// TOTAL IR VOLUME OTH 	_ CURRENT MONTH
						$TIRDOTH_CM		= 0;						// TOTAL IR DISC OTH 	_ CURRENT MONTH
						$TIRCOTH_CM		= 0;						// TOTAL IR COST OTH 	_ CURRENT MONTH
						$TIRTOTH_CM		= 0;						// TOTAL IR RETURN OTH 	_ CURRENT MONTH
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG NOT IN ('DY','BB')
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND MONTH(C.PO_DATE) = '$StartDateM' AND YEAR(C.PO_DATE) = '$StartDateY'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE >= '$Start_DateM' AND A.IR_DATE <= '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 		= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVOTH_CM	= $rowCUR->TIRVOLM;
							$TIRDOTH_CM	= $rowCUR->TIRDISC;
							$TIRCOTH_CM	= $rowCUR->TIRCOST;
							$TIRTOTH_CM	= $rowCUR->TTAXPPN;
						endforeach;

					// LAINNYA/OTHERS 	- IR PER TGL. 1 s.d. TGL. LAPORAN DARI PO BULAN SEBELUMNYA (MB)
						$TIRVOTH_CMMB	= 0;						// TOTAL IR VOLUME OTH 	_ MONTH BEFORE
						$TIRDOTH_CMMB	= 0;						// TOTAL IR DISC OTH 	_ MONTH BEFORE
						$TIRCOTH_CMMB	= 0;						// TOTAL IR COST OTH 	_ MONTH BEFORE
						$TIRTOTH_CMMB	= 0;						// TOTAL IR TAX OTH 		_ MONTH BEFORE
						$sqlCUR 		= "SELECT IF(SUM(A.ITM_QTY)>0,SUM(A.ITM_QTY),0) AS TIRVOLM, IF(SUM(A.ITM_DISC)>0,SUM(A.ITM_DISC),0) AS TIRDISC,
												IF(SUM(A.ITM_TOTAL)>0,SUM(A.ITM_TOTAL),0) AS TIRCOST, IF(SUM(A.TAXCODE1)>0,SUM(A.TAXCODE1),0) AS TTAXPPN,
												IF(SUM(A.RET_AMOUNT)>0,SUM(A.RET_AMOUNT),0) AS TIRRET
											FROM tbl_ir_detail A
												INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
													AND B.ITM_CATEG NOT IN ('DY','BB')
												INNER JOIN tbl_po_header C ON C.PO_NUM = A.PO_NUM
													AND C.PO_DATE <= '$End_MonthBef'
												INNER JOIN tbl_ir_header D ON A.IR_NUM = D.IR_NUM
											WHERE $ADDQRY1
												A.IR_DATE >= '$Start_DateM' AND A.IR_DATE <= '$Start_DateC' AND D.IR_STAT IN (3,6)";
						$resCUR 	= $this->db->query($sqlCUR)->result();
						foreach($resCUR as $rowCUR) :
							$TIRVOTH_CMMB	= $rowCUR->TIRVOLM;
							$TIRDOTH_CMMB	= $rowCUR->TIRDISC;
							$TIRCOTH_CMMB	= $rowCUR->TIRCOST;
							$TIRTOTH_CMMB	= $rowCUR->TTAXPPN;
						endforeach;

					// LAINNYA/OTHERS 	- RETUR IR PER TGL. 1 s.d. TGL. LAPORAN PADA BULAN AKTIF (CM) DARI PO BULAN AKTIF
						$TIRRETCOTH_CM	= 0;

					$GTIRCM_CM		= $TIRCDY_CM + $TIRCBB_CM + $TIRCOTH_CM;
					$GTIRCM_CMMB	= $TIRCDY_CMMB + $TIRCBB_CMMB + $TIRCOTH_CMMB;
					$GTIRRETCM_CM	= $TIRRETCDY_CM + $TIRRETCBB_CM + $TIRRETCOTH_CM;
				// END : CURRENT MONTH
	      	?>
			<tr>
                <td rowspan="6" style="text-align:center;" nowrap><?=strftime('%d %B', strtotime($Start_Date))."<br>".strftime('%Y', strtotime($Start_Date))?></td>
                <td rowspan="3" style="text-align:center;" nowrap>PO</td>
                <td style="text-align:left;" nowrap>KIMIA/DYESTUFF</td>
                <td style="text-align:center;" nowrap>&nbsp;</td>
                <td style="text-align:right;" nowrap><?=number_format($TPOCDY_CD,2)?></td>
                <td style="text-align:right;" nowrap><?=number_format($TPORDY_DB,2)?></td>
                <td style="text-align:right;" nowrap><?=number_format($TRETCDY_CD,2)?></td>
                <td rowspan="3" style="text-align:right;" nowrap>&nbsp;</td>
			</tr>
        	<tr>
				<td nowrap style="text-align:left;">BATUBARA</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TPOCBB_CD,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TPORBB_DB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TRETCBB_CD,2)?></td>
	      	</tr>
        	<tr>
				<td nowrap style="text-align:left;">SPAREPART</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TPOCOTH_CD,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TPOROTH_DB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TRETCOTH_CD,2)?></td>
	      	</tr>
        	<tr class="hcol2" style="background:#FFFF00">
				<td rowspan="3" nowrap style="text-align:center;">REALISASI PO</td>
                <td nowrap style="text-align:left;">KIMIA/DYESTUFF</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCDY_CD,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCDY_CDMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRRETCDY_CD,2)?></td>
                <td rowspan="3" style="text-align:center;" nowrap>&nbsp;</td>
	      	</tr>
        	<tr class="hcol2" style="background:#FFFF00">
				<td nowrap style="text-align:left;">BATUBARA</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCBB_CD,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCBB_CDMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRRETCBB_CD,2)?></td>
	      	</tr>
        	<tr class="hcol2" style="background:#FFFF00">
				<td nowrap style="text-align:left;">SPAREPART</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCOTH_CD,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCOTH_CDMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRRETCOTH_CD,2)?></td>
	      	</tr>
        	<tr>
				<td colspan="8" style="line-height: 1px">&nbsp;</td>
	      	</tr>
			<tr class="hcol4" style="background:#9ACD32; font-weight: bold;">
                <td rowspan="2" colspan="2" style="text-align:center;" nowrap>GRAND TOTAL</td>
                <td style="text-align:left;" nowrap>PO</td>
                <td style="text-align:center;" nowrap>&nbsp;</td>
                <td style="text-align:right;" nowrap><?=number_format($GTPOCD_CD,2)?></td>
                <td style="text-align:right;" nowrap><?=number_format($GTPOMB_CD,2)?></td>
                <td style="text-align:right;" nowrap><?=number_format($GTRETCD_CD,2)?></td>
                <td style="text-align:right;" nowrap>&nbsp;</td>
			</tr>
        	<tr class="hcol4" style="background:#9ACD32;  font-weight: bold;">
				<td nowrap style="text-align:left;">REALISASI PO</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($GTIRCD_CD,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($GTIRCD_CDMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($GTIRRETCD_CD,2)?></td>
				<td nowrap style="text-align:right;">&nbsp;</td>
	      	</tr>
        	<tr>
				<td colspan="8" style="line-height: 1px">&nbsp;</td>
	      	</tr>
        	<tr class="hcol3" style="background:#5DADE2; font-weight: bold;">
				<td nowrap style="text-align:left;">RESUME</td>
				<td nowrap style="text-align:center;" colspan="2">KETERANGAN</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;">TOTAL <?=strtoupper(strftime('%B', strtotime($Start_Date)))?></td>
				<td nowrap style="text-align:right;">TOTAL SEBELUMNYA</td>
				<td nowrap style="text-align:right;">TOTAL RETUR</td>
				<td nowrap style="text-align:right;">JUMLAH PO</td>
	      	</tr>
			<tr>
                <td rowspan="6" style="text-align:center;" nowrap>1 - <?=strftime('%d %B', strtotime($Start_Date))."<br>".strftime('%Y', strtotime($Start_Date))?></td></td>
                <td rowspan="3" style="text-align:center;" nowrap>PO</td>
                <td nowrap style="text-align:left;">KIMIA/DYESTUFF</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TPOCDY_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TPORDY_MB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TRETCDY_CM,2)?></td>
                <td rowspan="3" style="text-align:center;" nowrap>&nbsp;</td>
			</tr>
        	<tr>
				<td nowrap style="text-align:left;">BATUBARA</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TPOCBB_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TPORBB_MB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TRETCBB_CM,2)?></td>
	      	</tr>
        	<tr>
				<td nowrap style="text-align:left;">SPAREPART</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TPOCOTH_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TPOROTH_MB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TRETCOTH_CM,2)?></td>
	      	</tr>
        	<tr class="hcol2" style="background:#FFFF00">
				<td rowspan="3" nowrap style="text-align:center;">REALISASI PO</td>
                <td nowrap style="text-align:left;">KIMIA/DYESTUFF</td>
				<td nowrap style="text-align:right;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCDY_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCDY_CMMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRRETCDY_CM,2)?></td>
                <td rowspan="3" style="text-align:center;" nowrap>&nbsp;</td>
	      	</tr>
        	<tr class="hcol2" style="background:#FFFF00">
				<td nowrap style="text-align:left;">BATUBARA</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCBB_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCBB_CMMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRRETCBB_CM,2)?></td>
	      	</tr>
        	<tr class="hcol2" style="background:#FFFF00">
				<td nowrap style="text-align:left;">SPAREPART</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCOTH_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRCOTH_CMMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($TIRRETCOTH_CM,2)?></td>
	      	</tr>
			<tr class="hcol5" style="background:#F1C40F; font-weight: bold;">
                <td rowspan="2" colspan="2" style="text-align:center;" nowrap>GRAND TOTAL</td>
                <td nowrap style="text-align:left;">PO</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($GTPOCM_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($GTPOMB_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($GTPORETCM_CM,2)?></td>
				<td nowrap style="text-align:right;">&nbsp;</td>
			</tr>
        	<tr class="hcol5" style="background:#F1C40F;  font-weight: bold;">
				<td nowrap style="text-align:left;">REALISASI PO</td>
				<td nowrap style="text-align:center;">&nbsp;</td>
				<td nowrap style="text-align:right;"><?=number_format($GTIRCM_CM,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($GTIRCM_CMMB,2)?></td>
				<td nowrap style="text-align:right;"><?=number_format($GTIRRETCM_CM,2)?></td>
				<td nowrap style="text-align:right;">&nbsp;</td>
	      	</tr>
        	<tr>
				<td colspan="8" style="text-align: center;">Tembusan : -Head Committe - Finance Committe - Corporate Finance Accounting & Controller - Internal Controller</td>
	      	</tr>
        	<tr class="hcol6" style="background-color:#27AE60">
				<td colspan="8" style="text-align: center; font-weight: bold;"><?=$comp_name?></td>
	      	</tr>
        	<tr class="hcol6" style="background-color:#27AE60; font-weight: bold;">
				<td colspan="4" style="text-align: center;"><?=$CreatedBy?></td>
				<td colspan="4" style="text-align: center;"><?=$ApprovedBy?></td>
	      	</tr>
            <tr>
                <td colspan="4" style="text-align: center;"><br><br><br><br><br></td>
                <td colspan="4" style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">Nama : </td>
                <td colspan="4">Nama : </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">ADM</td>
                <td colspan="4" style="text-align: center;">SP</td>
            </tr>
		</table>
        <br>
        <table width="100%" border="0" style="display: none;">
            <tr>
                <td width="13%">Keterangan : </td>
                <td width="30%">FAM = Finance Accounting Manager</td>
                <td width="55%">FC = Finance Committee</td>
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
	</div>
</body>

</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>