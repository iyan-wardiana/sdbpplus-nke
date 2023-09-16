<?php
	/* 
		* Author		   = Dian Hermanto
		* Create Date	= 20 April 2018
		* File Name	 = v_sdbp_report_item.php
		* Location		 = -
	*/

	date_default_timezone_set("Asia/Jakarta");

	if($viewType == 1)
	{
		$repDate 	= date('ymdHis');
		$fileNm 	= "ItemBudgetDet_".$repDate;
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$fileNm.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

    function cut_text($var, $len = 200, $txt_titik = "-") 
    {
        $var1   = explode("</p>",$var);
        $var    = $var1[0];
        if (strlen ($var) < $len) 
        { 
            return $var; 
        }
        if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
        {
            return $match [1] . $txt_titik;
        }
        else
        {
            return substr ($var, 0, $len) . $txt_titik;
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Laporan Pekerjaan Tambah/Kurang</title>
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
	            width: 50cm;
	            min-height: 21cm;
	            padding-left: 1cm;
	            padding-right: 1cm;
	            padding-top: 1cm;
	            padding-bottom: 1cm;
	            margin: 0.5cm auto;
	            border: 1px #D3D3D3 solid;
	            border-radius: 5px;
	            background: white;
	           /* background-size: 400px 200px !important;*/
	            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	        }

	        @page {
	            /*size: auto;
    			size: A3;*/
	            margin: 0;
	        }

	        @media print {

	            @page{size: landscape;}
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
	    </style>
	</head>

	<div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>
        </div>
        <div class="print_title">
            <table width="100%" border="0" style="size:auto">
                <tr>
					<td width="50" height="50" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" width="180"></td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td colspan="3"><?php echo "$h1_title"; ?></td>
                </tr>
                <tr>
                    <td width="100">Periode</td>
                    <td width="10">:</td>
                    <td><?php echo date('d-m-Y', strtotime($Start_Date));?>  S/D <?php echo date('d-m-Y', strtotime($End_Date));?></td>
                </tr>
                <tr>
                    <td width="100">Kode Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJCODE"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Proyek</td>
                    <td width="10">:</td>
                    <td colspan="3"><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr>
                    <td width="100">Kategori</td>
                    <td width="10">:</td>
                    <td><?php echo $ITMGRP_NM; ?></td>
                </tr>
                <tr>
                    <td width="100">Tgl. Cetak</td>
                    <td width="10">:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
        	<table width="100%" border="1" rules="all" style="border-color: black;">
        		<thead>
	                <tr style="background:#CCCCCC">
						<th width="5%" rowspan="3" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
						<th colspan="4" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">DAFTAR ITEM</th>
						<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">BUDGET AWAL</th>
						<th colspan="2" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
						<th colspan="6" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">REQUEST</th>
						<th colspan="6" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">REALISASI</th>
	              	</tr>
	                <tr style="background:#CCCCCC">
						<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">PERIODE INI</th>
						<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">KOMULATIF</th>
						<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">SISA BUDGET THD REQUEST</th>
						<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">PERIODE INI</th>
						<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">KOMULATIF</th>
						<th colspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
	                </tr>
	                <tr style="background:#CCCCCC">
						<th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;  border-left-width:2px; border-left-color:#000;">KODE</th>
						<th width="9%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NAMA ITEM</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">SATUAN</th>
						<th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">GROUP</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
						<th width="6%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">VOL.</th>
						<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
	                </tr>
	              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
						<td nowrap style="text-align:center; border-right: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
						<td nowrap style="text-align:center; border-right: none; border-left: none; border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
	               	</tr>
	            </thead>
	            <tbody>
               	<?php
               		$DATES 	= $Start_Date;
               		$DATEE 	= $End_Date;
					$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

					if($ITM_GROUP == 'All') $addQGItem = '';
					else $addQGItem = "WHERE IG_Code = '$ITM_GROUP'";

					// get Group Item
						$getGItem 	= "SELECT * FROM tbl_itemgroup $addQGItem";
						$resGItem 	= $this->db->query($getGItem);
						if($resGItem->num_rows() > 0)
						{
							$newNo = 0;
							$GTOT_ITMB_VAL 	= 0;
							$GTOT_ADDM 		= 0;
							$GTOT_ADDM_KOM 	= 0;
							$GTOT_ITMB2_VAL	= 0;
							$GTOT_REQM 		= 0;
							$GTOT_REQM_KOM 	= 0;
							$GTOT_REMREQM	= 0;
							$GTOT_REALZM 	= 0;
							$GTOT_REALZM_KOM= 0;
							$GTOT_REMREALZM	= 0;
							
							$GTOT_ITMB_VOL		= 0; 		// GRAND TOTAL ITEM BUDGET PER GROUP
							$GTOT_ITMB_VAL		= 0; 		// GRAND TOTAL ITEM BUDGET PER GROUP
							$GTOT_ITMB2_VOL		= 0; 		// GRAND TOTAL ITEM BUDGET SETELAH EPRUBAHAN PER GROUP
							$GTOT_ITMB2_VAL		= 0; 		// GRAND TOTAL ITEM BUDGET SETELAH EPRUBAHAN PER GROUP
							$GTOT_REQ_NOW_VAL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REQ_ALL_VAL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REMREQ_VOL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REMREQ_VAL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REAL_NOW_VAL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REAL_ALL_VAL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REMREAL_VOL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP
							$GTOT_REMREAL_VAL	= 0; 		// GRAND TOTAL ITEM REQUEEST PERIODE PER GROUP

							if($ITM_CODE == 1)
								$qryITM 	= "";
							else
								$qryITM 	= "AND A.ITM_CODE = '$ITM_CODE'";

							foreach($resGItem->result() as $rGItem):
								$IG_Code	= $rGItem->IG_Code;
								$IG_Name 	= $rGItem->IG_Name;

								// PER GROUP
								$TOT_ITMB_VOL		= 0; 		// TOTAL ITEM BUDGET PER GROUP
								$TOT_ITMB_VAL		= 0; 		// TOTAL ITEM BUDGET PER GROUP

								$TOT_ITMB2_VOL		= 0; 		// TOTAL ITEM BUDGET SETELAH EPRUBAHAN PER GROUP
								$TOT_ITMB2_VAL		= 0; 		// TOTAL ITEM BUDGET SETELAH EPRUBAHAN PER GROUP

								$TOT_REQ_VOL		= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REQ_NOW_VAL	= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REQ_ALL_VAL	= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REMREQ_VOL		= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REMREQ_VAL		= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP

								$TOT_REAL_VOL		= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REAL_NOW_VAL	= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REAL_ALL_VAL	= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REMREAL_VOL	= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP
								$TOT_REMREAL_VAL	= 0; 		// TOTAL ITEM REQUEEST PERIODE PER GROUP

								$TOT_ADDM 			= 0;
								$TOT_REQM 			= 0;
								$TOT_REMREQM 		= 0;
								$TOT_REALZM 		= 0;
								$TOT_REMREALZM 		= 0;

								$TOT_ADDM_KOM 		= 0;
								$TOT_ITMB2_VAL_KOM 	= 0;
								$TOT_REQM_KOM 		= 0;
								$TOT_REMREQM_KOM 	= 0;
								$TOT_REALZM_KOM 	= 0;
								$TOT_REMREALZM_KOM 	= 0;
								$getJobD 	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.JOBDESC,
												SUM(A.ITM_VOLM) AS ITM_VOL_BG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_VAL_BG,
												A.IS_LEVEL, A.ISLASTH, A.ISLAST, A.WBSD_STAT, B.ITM_NAME
												FROM tbl_joblist_detail_$PRJCODEVW A
												LEFT JOIN tbl_item_$PRJCODEVW B ON B.ITM_CODE = A.ITM_CODE 
												AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
												WHERE A.ITM_CODE != '' AND A.ITM_GROUP = '$IG_Code' AND A.ISLAST = 1 $qryITM
												GROUP BY A.ITM_CODE";
								$resJobD 	= $this->db->query($getJobD);
								if($resJobD->num_rows() > 0)
								{
									$newNo = $newNo;
									foreach($resJobD->result() as $rJobD):
										$newNo 		= $newNo + 1;
										$PRJCODE 	= $rJobD->PRJCODE;
										$ITM_GROUP 	= $rJobD->ITM_GROUP;
										$ITM_CODE 	= $rJobD->ITM_CODE;
										$JOBDESC 	= $rJobD->JOBDESC;
										$ITM_NAME1 	= $rJobD->ITM_NAME;
										$ITM_NAME	= wordwrap($ITM_NAME1, 50, "<br>", true);
										$ITM_UNIT 	= $rJobD->ITM_UNIT;
										$ITM_VOL_BG = $rJobD->ITM_VOL_BG;
										$ITM_PRICE 	= $rJobD->ITM_PRICE;
										$ITM_LASTP 	= $rJobD->ITM_LASTP;
										$ITM_VAL_BG = $rJobD->ITM_VAL_BG;
										$IS_LEVEL 	= $rJobD->IS_LEVEL;
										$ISLAST 	= $rJobD->ISLAST;
										$ISLASTH 	= $rJobD->ISLASTH;

										// START : CURRENT
											$AMD_VOL_NOW	= 0;
											$AMD_VAL_NOW 	= 0;
											$PR_VOL_NOW		= 0;
											$PR_VAL_NOW 	= 0;
											$PR_VOL_R_NOW	= 0;
											$PR_VAL_R_NOW 	= 0;
											$PR_CVOL_NOW	= 0;
											$PR_CVAL_NOW 	= 0;
											$PO_VOL_NOW		= 0;
											$PO_VAL_NOW 	= 0;
											$PO_VOL_R_NOW	= 0;
											$PO_VAL_R_NOW 	= 0;
											$PO_CVOL_NOW	= 0;
											$PO_CVAL_NOW 	= 0;
											$IR_VOL_NOW		= 0;
											$IR_VAL_NOW 	= 0;
											$IR_VOL_R_NOW	= 0;
											$IR_VAL_R_NOW 	= 0;
											$UM_VOL_NOW		= 0;
											$UM_VAL_NOW 	= 0;
											$UM_VOL_R_NOW	= 0;
											$UM_VAL_R_NOW 	= 0;
											$WO_VOL_NOW		= 0;
											$WO_VAL_NOW 	= 0;
											$WO_VOL_R_NOW	= 0;
											$WO_VAL_R_NOW 	= 0;
											$WO_CVOL_NOW	= 0;
											$WO_CVAL_NOW 	= 0;
											$OPN_VOL_NOW	= 0;
											$OPN_VAL_NOW 	= 0;
											$OPN_VOL_R_NOW	= 0;
											$OPN_VAL_R_NOW 	= 0;
											$VCASH_VOL_NOW	= 0;
											$VCASH_VAL_NOW 	= 0;
											$VCASH_VOL_R_NOW= 0;
											$VCASH_VAL_R_NOW= 0;
											$VLK_VOL_NOW	= 0;
											$VLK_VAL_NOW 	= 0;
											$VLK_VOL_R_NOW	= 0;
											$VLK_VAL_R_NOW 	= 0;
											$PPD_VOL_NOW	= 0;
											$PPD_VAL_NOW 	= 0;
											$PPD_VOL_R_NOW	= 0;
											$PPD_VAL_R_NOW 	= 0;
											$sNOW 	= "SELECT 	SUM(AMD_VOL) AS AMD_VOL, SUM(AMD_VAL) AS AMD_VAL, SUM(AMD_VOL_R) AS AMD_VOL_R, SUM(AMD_VAL_R) AS AMD_VAL_R,
																SUM(PR_VOL) AS PR_VOL, SUM(PR_VAL) AS PR_VAL, SUM(PR_VOL_R) AS PR_VOL_R, SUM(PR_VAL_R) AS PR_VAL_R,
																SUM(PR_CVOL) AS PR_CVOL, SUM(PR_CVAL) AS PR_CVAL,
																SUM(PO_VOL) AS PO_VOL, SUM(PO_VAL) AS PO_VAL, SUM(PO_VOL_R) AS PO_VOL_R, SUM(PO_VAL_R) AS PO_VAL_R,
																SUM(PO_CVOL) AS PO_CVOL, SUM(PO_CVAL) AS PO_CVAL,
																SUM(IR_VOL) AS IR_VOL, SUM(IR_VAL) AS IR_VAL, SUM(IR_VOL_R) AS IR_VOL_R, SUM(IR_VAL_R) AS IR_VAL_R,
																SUM(UM_VOL) AS UM_VOL, SUM(UM_VAL) AS UM_VAL, SUM(UM_VOL_R) AS UM_VOL_R, SUM(UM_VAL_R) AS UM_VAL_R,
																SUM(WO_VOL) AS WO_VOL, SUM(WO_VAL) AS WO_VAL, SUM(WO_VOL_R) AS WO_VOL_R, SUM(WO_VAL_R) AS WO_VAL_R,
																SUM(WO_CVOL) AS WO_CVOL, SUM(WO_CVAL) AS WO_CVAL,
																SUM(OPN_VOL) AS OPN_VOL, SUM(OPN_VAL) AS OPN_VAL, SUM(OPN_VOL_R) AS OPN_VOL_R, SUM(OPN_VAL_R) AS OPN_VAL_R,
																SUM(VCASH_VOL) AS VCASH_VOL, SUM(VCASH_VAL) AS VCASH_VAL, SUM(VCASH_VOL_R) AS VCASH_VOL_R, SUM(VCASH_VAL_R) AS VCASH_VAL_R,
																SUM(VLK_VOL) AS VLK_VOL, SUM(VLK_VAL) AS VLK_VAL, SUM(VLK_VOL_R) AS VLK_VOL_R, SUM(VLK_VAL_R) AS VLK_VAL_R,
																SUM(PPD_VOL) AS PPD_VOL, SUM(PPD_VAL) AS PPD_VAL, SUM(PPD_VOL_R) AS PPD_VOL_R, SUM(PPD_VAL_R) AS PPD_VAL_R
														FROM tbl_item_logbook_$PRJCODEVW
														WHERE ITM_CODE = '$ITM_CODE' AND DOC_DATE BETWEEN '$Start_Date' AND '$End_Date' AND PRJCODE = '$PRJCODE'
														GROUP BY ITM_CODE";
											$rNOW 	= $this->db->query($sNOW);
											foreach($rNOW->result() as $rwNOW):
												$AMD_VOL_NOW	= $rwNOW->AMD_VOL;
												$AMD_VAL_NOW 	= $rwNOW->AMD_VOL;

												$PR_VOL_NOW		= $rwNOW->PR_VOL;
												$PR_VAL_NOW 	= $rwNOW->PR_VAL;
												$PR_VOL_R_NOW	= $rwNOW->PR_VOL_R;
												$PR_VAL_R_NOW 	= $rwNOW->PR_VAL_R;
												$PR_CVOL_NOW	= $rwNOW->PR_CVOL;
												$PR_CVAL_NOW 	= $rwNOW->PR_CVAL;

												$PO_VOL_NOW		= $rwNOW->PO_VOL;
												$PO_VAL_NOW 	= $rwNOW->PO_VAL;
												$PO_VOL_R_NOW	= $rwNOW->PO_VOL_R;
												$PO_VAL_R_NOW 	= $rwNOW->PO_VAL_R;
												$PO_CVOL_NOW	= $rwNOW->PO_CVOL;
												$PO_CVAL_NOW 	= $rwNOW->PO_CVAL;

												$IR_VOL_NOW		= $rwNOW->IR_VOL;
												$IR_VAL_NOW 	= $rwNOW->IR_VAL;
												$IR_VOL_R_NOW	= $rwNOW->IR_VOL_R;
												$IR_VAL_R_NOW 	= $rwNOW->IR_VOL_R;

												$UM_VOL_NOW		= $rwNOW->UM_VOL;
												$UM_VAL_NOW 	= $rwNOW->UM_VAL;
												$UM_VOL_R_NOW	= $rwNOW->UM_VOL_R;
												$UM_VAL_R_NOW 	= $rwNOW->UM_VOL_R;

												$WO_VOL_NOW		= $rwNOW->WO_VOL;
												$WO_VAL_NOW 	= $rwNOW->WO_VAL;
												$WO_VOL_R_NOW	= $rwNOW->WO_VOL_R;
												$WO_VAL_R_NOW 	= $rwNOW->WO_VAL_R;
												$WO_CVOL_NOW	= $rwNOW->WO_CVOL;
												$WO_CVAL_NOW 	= $rwNOW->WO_CVAL;

												$OPN_VOL_NOW	= $rwNOW->OPN_VOL;
												$OPN_VAL_NOW 	= $rwNOW->OPN_VAL;
												$OPN_VOL_R_NOW	= $rwNOW->OPN_VOL_R;
												$OPN_VAL_R_NOW 	= $rwNOW->OPN_VOL_R;

												$VCASH_VOL_NOW	= $rwNOW->VCASH_VOL;
												$VCASH_VAL_NOW 	= $rwNOW->VCASH_VAL;
												$VCASH_VOL_R_NOW= $rwNOW->VCASH_VOL_R;
												$VCASH_VAL_R_NOW= $rwNOW->VCASH_VOL_R;

												$VLK_VOL_NOW	= $rwNOW->VLK_VOL;
												$VLK_VAL_NOW 	= $rwNOW->VLK_VAL;
												$VLK_VOL_R_NOW	= $rwNOW->VLK_VOL_R;
												$VLK_VAL_R_NOW 	= $rwNOW->VLK_VOL_R;

												$PPD_VOL_NOW	= $rwNOW->PPD_VOL;
												$PPD_VAL_NOW 	= $rwNOW->PPD_VAL;
												$PPD_VOL_R_NOW	= $rwNOW->PPD_VOL_R;
												$PPD_VAL_R_NOW 	= $rwNOW->PPD_VOL_R;
											endforeach;
										// END : CURRENT

										// START : KOMULATIF
											$AMD_VOL_ALL	= 0;
											$AMD_VAL_ALL 	= 0;
											$PR_VOL_ALL		= 0;
											$PR_VAL_ALL 	= 0;
											$PR_VOL_R_ALL	= 0;
											$PR_VAL_R_ALL 	= 0;
											$PR_CVOL_ALL	= 0;
											$PR_CVAL_ALL 	= 0;
											$PO_VOL_ALL		= 0;
											$PO_VAL_ALL 	= 0;
											$PO_VOL_R_ALL	= 0;
											$PO_VAL_R_ALL 	= 0;
											$PO_CVOL_ALL	= 0;
											$PO_CVAL_ALL 	= 0;
											$IR_VOL_ALL		= 0;
											$IR_VAL_ALL 	= 0;
											$IR_VOL_R_ALL	= 0;
											$IR_VAL_R_ALL 	= 0;
											$UM_VOL_ALL		= 0;
											$UM_VAL_ALL 	= 0;
											$UM_VOL_R_ALL	= 0;
											$UM_VAL_R_ALL 	= 0;
											$WO_VOL_ALL		= 0;
											$WO_VAL_ALL 	= 0;
											$WO_VOL_R_ALL	= 0;
											$WO_VAL_R_ALL 	= 0;
											$WO_CVOL_ALL	= 0;
											$WO_CVAL_ALL 	= 0;
											$OPN_VOL_ALL	= 0;
											$OPN_VAL_ALL 	= 0;
											$OPN_VOL_R_ALL	= 0;
											$OPN_VAL_R_ALL 	= 0;
											$VCASH_VOL_ALL	= 0;
											$VCASH_VAL_ALL 	= 0;
											$VCASH_VOL_R_ALL= 0;
											$VCASH_VAL_R_ALL= 0;
											$VLK_VOL_ALL	= 0;
											$VLK_VAL_ALL 	= 0;
											$VLK_VOL_R_ALL	= 0;
											$VLK_VAL_R_ALL 	= 0;
											$PPD_VOL_ALL	= 0;
											$PPD_VAL_ALL 	= 0;
											$PPD_VOL_R_ALL	= 0;
											$PPD_VAL_R_ALL 	= 0;
											$sNOW 	= "SELECT 	SUM(AMD_VOL) AS AMD_VOL, SUM(AMD_VAL) AS AMD_VAL, SUM(AMD_VOL_R) AS AMD_VOL_R, SUM(AMD_VAL_R) AS AMD_VAL_R,
																SUM(PR_VOL) AS PR_VOL, SUM(PR_VAL) AS PR_VAL, SUM(PR_VOL_R) AS PR_VOL_R, SUM(PR_VAL_R) AS PR_VAL_R,
																SUM(PR_CVOL) AS PR_CVOL, SUM(PR_CVAL) AS PR_CVAL,
																SUM(PO_VOL) AS PO_VOL, SUM(PO_VAL) AS PO_VAL, SUM(PO_VOL_R) AS PO_VOL_R, SUM(PO_VAL_R) AS PO_VAL_R,
																SUM(PO_CVOL) AS PO_CVOL, SUM(PO_CVAL) AS PO_CVAL,
																SUM(IR_VOL) AS IR_VOL, SUM(IR_VAL) AS IR_VAL, SUM(IR_VOL_R) AS IR_VOL_R, SUM(IR_VAL_R) AS IR_VAL_R,
																SUM(UM_VOL) AS UM_VOL, SUM(UM_VAL) AS UM_VAL, SUM(UM_VOL_R) AS UM_VOL_R, SUM(UM_VAL_R) AS UM_VAL_R,
																SUM(WO_VOL) AS WO_VOL, SUM(WO_VAL) AS WO_VAL, SUM(WO_VOL_R) AS WO_VOL_R, SUM(WO_VAL_R) AS WO_VAL_R,
																SUM(WO_CVOL) AS WO_CVOL, SUM(WO_CVAL) AS WO_CVAL,
																SUM(OPN_VOL) AS OPN_VOL, SUM(OPN_VAL) AS OPN_VAL, SUM(OPN_VOL_R) AS OPN_VOL_R, SUM(OPN_VAL_R) AS OPN_VAL_R,
																SUM(VCASH_VOL) AS VCASH_VOL, SUM(VCASH_VAL) AS VCASH_VAL, SUM(VCASH_VOL_R) AS VCASH_VOL_R, SUM(VCASH_VAL_R) AS VCASH_VAL_R,
																SUM(VLK_VOL) AS VLK_VOL, SUM(VLK_VAL) AS VLK_VAL, SUM(VLK_VOL_R) AS VLK_VOL_R, SUM(VLK_VAL_R) AS VLK_VAL_R,
																SUM(PPD_VOL) AS PPD_VOL, SUM(PPD_VAL) AS PPD_VAL, SUM(PPD_VOL_R) AS PPD_VOL_R, SUM(PPD_VAL_R) AS PPD_VAL_R
														FROM tbl_item_logbook_$PRJCODEVW
														WHERE ITM_CODE = '$ITM_CODE' AND DOC_DATE <= '$End_Date' AND PRJCODE = '$PRJCODE'
														GROUP BY ITM_CODE";
											$rNOW 	= $this->db->query($sNOW);
											foreach($rNOW->result() as $rwNOW):
												$AMD_VOL_ALL	= $rwNOW->AMD_VOL;
												$AMD_VAL_ALL 	= $rwNOW->AMD_VOL;

												$PR_VOL_ALL		= $rwNOW->PR_VOL;
												$PR_VAL_ALL 	= $rwNOW->PR_VAL;
												$PR_VOL_R_ALL	= $rwNOW->PR_VOL_R;
												$PR_VAL_R_ALL 	= $rwNOW->PR_VAL_R;
												$PR_CVOL_ALL	= $rwNOW->PR_CVOL;
												$PR_CVAL_ALL 	= $rwNOW->PR_CVAL;

												$PO_VOL_ALL		= $rwNOW->PO_VOL;
												$PO_VAL_ALL 	= $rwNOW->PO_VAL;
												$PO_VOL_R_ALL	= $rwNOW->PO_VOL_R;
												$PO_VAL_R_ALL 	= $rwNOW->PO_VAL_R;
												$PO_CVOL_ALL	= $rwNOW->PO_CVOL;
												$PO_CVAL_ALL 	= $rwNOW->PO_CVAL;

												$IR_VOL_ALL		= $rwNOW->IR_VOL;
												$IR_VAL_ALL 	= $rwNOW->IR_VAL;
												$IR_VOL_R_ALL	= $rwNOW->IR_VOL_R;
												$IR_VAL_R_ALL 	= $rwNOW->IR_VOL_R;

												$UM_VOL_ALL		= $rwNOW->UM_VOL;
												$UM_VAL_ALL 	= $rwNOW->UM_VAL;
												$UM_VOL_R_ALL	= $rwNOW->UM_VOL_R;
												$UM_VAL_R_ALL 	= $rwNOW->UM_VOL_R;

												$WO_VOL_ALL		= $rwNOW->WO_VOL;
												$WO_VAL_ALL 	= $rwNOW->WO_VAL;
												$WO_VOL_R_ALL	= $rwNOW->WO_VOL_R;
												$WO_VAL_R_ALL 	= $rwNOW->WO_VAL_R;
												$WO_CVOL_ALL	= $rwNOW->WO_CVOL;
												$WO_CVAL_ALL 	= $rwNOW->WO_CVAL;

												$OPN_VOL_ALL	= $rwNOW->OPN_VOL;
												$OPN_VAL_ALL 	= $rwNOW->OPN_VAL;
												$OPN_VOL_R_ALL	= $rwNOW->OPN_VOL_R;
												$OPN_VAL_R_ALL 	= $rwNOW->OPN_VOL_R;

												$VCASH_VOL_ALL	= $rwNOW->VCASH_VOL;
												$VCASH_VAL_ALL 	= $rwNOW->VCASH_VAL;
												$VCASH_VOL_R_ALL= $rwNOW->VCASH_VOL_R;
												$VCASH_VAL_R_ALL= $rwNOW->VCASH_VOL_R;

												$VLK_VOL_ALL	= $rwNOW->VLK_VOL;
												$VLK_VAL_ALL 	= $rwNOW->VLK_VAL;
												$VLK_VOL_R_ALL	= $rwNOW->VLK_VOL_R;
												$VLK_VAL_R_ALL 	= $rwNOW->VLK_VOL_R;

												$PPD_VOL_ALL	= $rwNOW->PPD_VOL;
												$PPD_VAL_ALL 	= $rwNOW->PPD_VAL;
												$PPD_VOL_R_ALL	= $rwNOW->PPD_VOL_R;
												$PPD_VAL_R_ALL 	= $rwNOW->PPD_VOL_R;
											endforeach;
										// END : KOMULATIF

										// AFTER ADDEUNDUM
											$ITM_VOL_BG2 	= $ITM_VOL_BG + $AMD_VOL_ALL;
											$ITM_VAL_BG2	= $ITM_VAL_BG + $AMD_VAL_ALL;

										// TOTAL REQUEST NOW
											$PRT_VOL_NOW 	= $PR_VOL_NOW + $WO_VOL_NOW + $VCASH_VOL_NOW + $VLK_VOL_NOW + $PPD_VOL_NOW;
											$PRT_VAL_NOW 	= $PO_VAL_NOW + $WO_VAL_NOW + $VCASH_VAL_NOW + $VLK_VAL_NOW + $PPD_VAL_NOW;

										// TOTAL REQUEST KOMULATIF
											$PRT_VOL_ALL	= $PR_VOL_ALL + $WO_VOL_ALL + $VCASH_VOL_ALL + $VLK_VOL_ALL + $PPD_VOL_ALL;
											$PRT_VAL_ALL	= $PO_VAL_ALL + $WO_VAL_ALL + $VCASH_VAL_ALL + $VLK_VAL_ALL + $PPD_VAL_ALL;

										// TOTAL REALISASI NOW
											$RLT_VOL_NOW 	= $IR_VOL_NOW + $OPN_VOL_NOW + $VCASH_VOL_NOW + $VLK_VOL_NOW + $PPD_VOL_NOW;
											$RLT_VAL_NOW 	= $IR_VAL_NOW + $OPN_VAL_NOW + $VCASH_VAL_NOW + $VLK_VAL_NOW + $PPD_VAL_NOW;

										// TOTAL REALISASI KOMULATIF
											$RLT_VOL_ALL 	= $IR_VOL_ALL + $OPN_VOL_ALL + $VCASH_VOL_ALL + $VLK_VOL_ALL + $PPD_VOL_ALL;
											$RLT_VAL_ALL 	= $IR_VAL_ALL + $OPN_VAL_ALL + $VCASH_VAL_ALL + $VLK_VAL_ALL + $PPD_VAL_ALL;

										// REM TO REQUEST
											$PR_VOL_REM		= $ITM_VOL_BG2 - $PRT_VOL_ALL;
											$PR_VAL_REM		= $ITM_VAL_BG2 - $PRT_VAL_ALL;

										// REM TO REALISASI
											$RL_VOL_REM		= $ITM_VOL_BG2 - $RLT_VOL_ALL;
											$RL_VAL_REM		= $ITM_VAL_BG2 - $RLT_VAL_ALL;

										// TOTAL ITEM
											$TOT_ITMB_VAL 		= $TOT_ITMB_VAL 	+ $ITM_VAL_BG;
											$TOT_ITMB2_VAL		= $TOT_ITMB2_VAL 	+ $ITM_VAL_BG2;
											$TOT_REQ_NOW_VAL	= $TOT_REQ_NOW_VAL 	+ $PRT_VAL_NOW;
											$TOT_REQ_ALL_VAL	= $TOT_REQ_ALL_VAL 	+ $PRT_VAL_ALL;
											$TOT_REMREQ_VAL		= $TOT_REMREQ_VAL 	+ $PR_VAL_REM;
											$TOT_REAL_NOW_VAL	= $TOT_REAL_NOW_VAL + $RLT_VAL_NOW;
											$TOT_REAL_ALL_VAL	= $TOT_REAL_ALL_VAL + $RLT_VAL_ALL;
											$TOT_REMREAL_VAL	= $TOT_REMREAL_VAL 	+ $RL_VAL_REM;

										if($ITM_NAME == '') $ITM_NAME = $JOBDESC;

										$secRecount	= base_url().'index.php/__l1y/recItemReport/?id=';
										$secvwRECD 	= "$secRecount~$PRJCODE~$ITM_CODE~$ITM_NAME";

										$vwREQDET	= "$ITM_CODE~$PRJCODE~$DATES~$DATEE";
										$secvwREQD 	= site_url('c_project/c_r3p/shwItm_H15t/?id='.$this->url_encryption_helper->encode_url($vwREQDET));
										?>
											<tr>
												<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?=$newNo?></td>

												<!-- ITEM CODEL -->
													<td nowrap style="text-align:left;">
														<a onclick="recountItemR('<?php echo $secvwRECD; ?>')" style="cursor: pointer;">
															<?php echo $ITM_CODE; ?>
														</a>
													</td>

												<!-- ITEM NAME -->
													<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>

												<!-- ITEM UNIT -->
													<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>

												<!-- ITEM GROUP -->
													<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>

												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOL_BG, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_VAL_BG, 2); ?></td>

												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOL_BG2, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_VAL_BG2, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRT_VOL_NOW, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($PRT_VAL_NOW, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;"><?php echo number_format($PRT_VOL_ALL, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($PRT_VAL_ALL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;"><?php echo number_format($PR_VOL_REM, 3); ?></td>
													<td nowrap style="text-align:right; text-decoration: underline; border-right-color:#000; border-right-width:2px;">
														<a onclick="showREQDET('<?php echo $secvwREQD; ?>')" style="cursor: pointer;">
															<?php echo number_format($PR_VAL_REM, 2); ?>
														</a>
													</td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;"><?php echo number_format($RLT_VOL_NOW, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($RLT_VAL_NOW, 2); ?></td>
												
												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;"><?php echo number_format($RLT_VOL_ALL, 3); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($RLT_VAL_ALL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;"><?php echo number_format($RL_VOL_REM, 2); ?></td>
													<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($RL_VAL_REM, 2); ?></td>
											</tr>
										<?php
									endforeach;

									if($IG_Code == 'M')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									if($IG_Code == 'U')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									if($IG_Code == 'S')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									if($IG_Code == 'T')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									if($IG_Code == 'I')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									if($IG_Code == 'R')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									if($IG_Code == 'O')
									{
										?>
											
											<tr style="background:#CCCCCC; font-weight: bold;">
												<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
												<!-- BUDGET AWAL --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB_VAL, 2); ?></td>
													
												<!-- SETELAH PERUBAHAN --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_ITMB2_VAL, 2); ?></td>
													
												<!-- REQUEST : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_NOW_VAL, 2); ?></td>

												<!-- REQUEST : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQ_ALL_VAL, 2); ?></td>
													
												<!-- REQUEST : SISA BUDGET THD REQUEST --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQ_VAL, 2); ?></td>
													
												<!-- REALISASI : PERIODE INI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_NOW_VAL, 2); ?></td>

												<!-- REALISASI : KOMULATIF --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REAL_ALL_VAL, 2); ?></td>
													
												<!-- REALISASI : SISA BUDGET THD REALISASI --->
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
													<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREAL_VAL, 2); ?></td>
											</tr>
											
										<?php
									}

									// GRAND TOTAL ITEM
										$GTOT_ITMB_VAL 		= $GTOT_ITMB_VAL 		+ $TOT_ITMB_VAL;
										$GTOT_ITMB2_VAL		= $GTOT_ITMB2_VAL 		+ $TOT_ITMB2_VAL;
										$GTOT_REQ_NOW_VAL	= $GTOT_REQ_NOW_VAL 	+ $TOT_REQ_NOW_VAL;
										$GTOT_REQ_ALL_VAL	= $GTOT_REQ_ALL_VAL 	+ $TOT_REQ_ALL_VAL;
										$GTOT_REMREQ_VAL	= $GTOT_REMREQ_VAL 		+ $TOT_REMREQ_VAL;
										$GTOT_REAL_NOW_VAL	= $GTOT_REAL_NOW_VAL 	+ $TOT_REAL_NOW_VAL;
										$GTOT_REAL_ALL_VAL	= $GTOT_REAL_ALL_VAL	+ $TOT_REAL_ALL_VAL;
										$GTOT_REMREAL_VAL	= $GTOT_REMREAL_VAL 	+ $TOT_REMREAL_VAL;
								}
							endforeach;
							
							?>
												
								<tr style="background:#CCCCCC; font-weight: bold;">
									<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL KESELURUHAN</td>
									<!-- BUDGET AWAL --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<!-- <td nowrap style="text-align:right;"><?php echo number_format($ITM_LASTP, 2); ?></td> -->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_ITMB_VAL, 2); ?></td>

									<!-- SETELAH PERUBAHAN --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_ITMB2_VAL, 2); ?></td>
										
									<!-- REQUEST : PERIODE INI --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REQ_NOW_VAL, 2); ?></td>

									<!-- REQUEST : KOMULATIF --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REQ_ALL_VAL, 2); ?></td>
										
									<!-- REQUEST : SISA BUDGET THD REQUEST --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REMREQ_VAL, 2); ?></td>
										
									<!-- REALISASI : PERIODE INI --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REAL_NOW_VAL, 2); ?></td>

									<!-- REALISASI : KOMULATIF --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REAL_ALL_VAL, 2); ?></td>
										
									<!-- REALISASI : SISA BUDGET THD REALISASI --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REMREAL_VAL, 2); ?></td>
								</tr>
								
							<?php
						}
				?>
				</tbody>
            </table>
        </div>
	</body>

    <script>
		function showREQDET(LinkD)
		{
			w = 1000;
			h = 550;
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
		}

		function recountItemR(row)
		{
			var JOBCODEID  	= document.getElementById('urlRec'+row).value;
			var collID  	= document.getElementById('urlRec'+row).value;
			myarr 		= collID.split('~');
	    	url			= myarr[0];

			document.getElementById('btnDetail1').style.display 		= 'none';
			document.getElementById('idClose1').style.display 			= 'none';
			document.getElementById('idRefresh1').style.display 		= 'none';

			document.getElementById('idprogbar1').style.display 		= '';
		    document.getElementById("progressbarXX1").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";

	        $.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(response)
	            {
	                swal(response, 
	                {
	                    icon: "success",
	                })
	                .then(function()
	                {
	                	swal.close();
	                	//location.reload();
	                	$('#mtr_perjob').DataTable().ajax.reload();

						document.getElementById('btnDetail1').style.display 		= '';
						document.getElementById('idClose1').style.display 			= '';
						document.getElementById('idRefresh1').style.display 		= '';

						document.getElementById('idprogbar1').style.display 		= 'none';
					    document.getElementById("progressbarXX1").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";
	                })
	            }
	        });
		}
    </script>
</html>