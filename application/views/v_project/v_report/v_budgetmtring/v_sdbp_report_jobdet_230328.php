<?php
	/* 
		* Author		   = Dian Hermanto
		* Create Date	= 20 April 2018
		* File Name	 = v_sdbp_report.php
		* Location		 = -
	*/

	if($viewType == 1)
	{
		$repDate 	= date('ymdHis');
		$fileNm 	= "RingkasanBudgetDet_".$repDate;
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
	            /*margin: 0;*/
	            padding: 0;
	            background-color: #FAFAFA;
	            font: 12pt Arial, Helvetica, sans-serif;
	        }

	        * {
	            box-sizing: border-box;
	            -moz-box-sizing: border-box;
	        }

	        .page {
	            width: 600mm;
	            /*min-height: 296mm;*/
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
	            /*size: auto;*/
    			/*size: A3;*/
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

	        .print_content table, thead, th, table, tbody, td {
	        	padding: 3px;
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

        <div class="print_body" style="font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td colspan="3"><?php echo "$h1_title"; ?> (Detil Pekerjaan)</td>
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
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr style="display: none;">
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
						<th width="400" rowspan="3" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
						<th rowspan="3" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
						<th colspan="4" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
						<th colspan="6"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REALISASI</th>
	              	</tr>
	                <tr style="background:#CCCCCC">
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PERIODE INI</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">KOMULATIF</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PERIODE INI</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">KOMULATIF</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
	                </tr>
	                <tr style="background:#CCCCCC">
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
	                </tr>
	              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
						<td style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
	               	</tr>
        		</thead>
        		<tbody>
        			<?php
						$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

						if($JOBPARENT == 'All')
						{
							$addQJOB_ID = "";
							$addQJOB_P 	= "";
						} 
						else 
						{
							$addQJOB_ID = "WHERE JOBCODEID = '$JOBPARENT'";
							$addQJOB_P 	= "WHERE JOBCODEID LIKE '$JOBPARENT%'";
						}

						$getJOBD 		= "SELECT ORD_ID, JOBCODEID, JOBPARENT, PRJCODE, JOBDESC, ITM_GROUP, ITM_CODE, 
											ITM_UNIT, ITM_VOLM,	ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, IS_LEVEL, ISLASTH,
											ISLAST, WBSD_STAT
											FROM tbl_joblist_detail_$PRJCODEVW
											$addQJOB_P ORDER BY JOBCODEID, ORD_ID ASC";
						$resJOBD 		= $this->db->query($getJOBD);
						if($resJOBD->num_rows() > 0)
						{
							$GTITM_BUDG 		= 0;
							$GTADD_TOTAL 		= 0;
							$GTADD_TOTAL_KOM 	= 0;
							$GTITM_BUDG2 		= 0;
							$GTREQ_AMOUNT 		= 0;
							$GTREQ_AMOUNT_KOM 	= 0;
							$GTITM_USED_AM 		= 0;
							$GTITM_USED_AM_KOM	= 0;
							$GTREMREQ_AMOUNT 	= 0;
							$GTREMREALZ_AMOUNT 	= 0;
							foreach($resJOBD->result() as $rJOBD):
								$ORD_ID 		= $rJOBD->ORD_ID;
								$JOBCODEID 		= $rJOBD->JOBCODEID;
								$JOBPARENT 		= $rJOBD->JOBPARENT;
								$PRJCODE 		= $rJOBD->PRJCODE;
								$JOBDESC 		= $rJOBD->JOBDESC;
								$ITM_GROUP 		= $rJOBD->ITM_GROUP;
								$ITM_CODE 		= $rJOBD->ITM_CODE;
								$ITM_UNIT 		= $rJOBD->ITM_UNIT;
								$ITM_VOLM 		= $rJOBD->ITM_VOLM;
								$ITM_PRICE 		= $rJOBD->ITM_PRICE;
								$ITM_LASTP 		= $rJOBD->ITM_LASTP;
								$ITM_AVGP 		= $rJOBD->ITM_AVGP;
								$ITM_BUDG 		= $rJOBD->ITM_BUDG;
								$IS_LEVEL 		= $rJOBD->IS_LEVEL;
								$ISLASTH 		= $rJOBD->ISLASTH;
								$ISLAST 		= $rJOBD->ISLAST;
								$WBSD_STAT 		= $rJOBD->WBSD_STAT;

								$ITM_VOLMBG 	= $ITM_VOLM;

								if($ITM_UNIT == '') $ITM_UNIT = 'LS';

								// SPACE
									$spaceLev 		= "";
									if($IS_LEVEL == 1)
										$spaceLev 	= 0;
									elseif($IS_LEVEL == 2)
										$spaceLev 	= 15;
									elseif($IS_LEVEL == 3)
										$spaceLev 	= 30;
									elseif($IS_LEVEL == 4)
										$spaceLev 	= 45;
									elseif($IS_LEVEL == 5)
										$spaceLev 	= 60;
									elseif($IS_LEVEL == 6)
										$spaceLev 	= 75;
									elseif($IS_LEVEL == 7)
										$spaceLev 	= 90;
									elseif($IS_LEVEL == 8)
										$spaceLev 	= 105;
									elseif($IS_LEVEL == 9)
										$spaceLev 	= 120;
									elseif($IS_LEVEL == 10)
										$spaceLev 	= 135;
									elseif($IS_LEVEL == 11)
										$spaceLev 	= 150;
									elseif($IS_LEVEL == 12)
										$spaceLev 	= 165;

								$JobView1		= "$JOBCODEID - $JOBDESC";
								$JobView		= wordwrap($JobView1, 90, "<br>", true);

								$REMREQ_VOLM 		= 0;
								$REMREQ_AMOUNT 		= 0;
								$REMREALZ_VOLM 		= 0;
								$REMREALZ_AMOUNT 	= 0;
								if($ISLAST == 0)
									$ADDQRY_01 		= "JOBCODEID LIKE '$JOBCODEID%'";
								else
									$ADDQRY_01 		= "JOBCODEID = '$JOBCODEID'";

								// Get BUDGET => PERIODE INI
									$get_RQRLZ 	= "SELECT 	SUM(AMD_VOL - AMDM_VOL) AS ADDVOLM, SUM(AMD_VAL - AMDM_VAL) AS ADD_TOTAL,
															SUM(PR_VOL+WO_VOL+VCASH_VOL+VLK_VOL+PPD_VOL+PR_VOL_R+WO_VOL_R+VCASH_VOL_R+VLK_VOL_R+PPD_VOL_R-PR_CVOL-WO_CVOL) AS REQ_VOLM,
															SUM(PO_VAL+WO_VAL+VCASH_VAL+VLK_VAL+PPD_VAL+PO_VAL_R+WO_VAL_R+VCASH_VAL_R+VLK_VAL_R+PPD_VAL_R-PO_CVAL-WO_CVAL) AS REQ_AMOUNT,
															SUM(IR_VOL+IR_VOL_R) AS REALZIR_VOLM,
															SUM(IR_VAL+IR_VAL_R) AS REALZIR_AMOUNT,
															SUM(UM_VOL+UM_VOL_R) AS REALZUM_VOLM,
															SUM(UM_VAL+UM_VAL_R) AS REALZUM_AMOUNT,
															SUM(UM_VOL+UM_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
															SUM(UM_VAL+UM_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
													FROM tbl_item_logbook_$PRJCODEVW
													WHERE $ADDQRY_01 AND DOC_DATE BETWEEN '$Start_Date' AND '$End_Date'";
									$res_RQRLZ 	= $this->db->query($get_RQRLZ);
									foreach($res_RQRLZ->result() as $rRQRLZ):
										// Addendum
											$ADDVOLM 		= $rRQRLZ->ADDVOLM;
											$ADD_TOTAL 		= $rRQRLZ->ADD_TOTAL;

										// REQUEST :
											$REQ_VOLM 		= $rRQRLZ->REQ_VOLM;
											$REQ_AMOUNT 	= $rRQRLZ->REQ_AMOUNT;

										// REALISASI
											// $ITM_USED 		= $rRQRLZ->REALZ_VOLM;
											// $ITM_USED_AM 	= $rRQRLZ->REALZ_AMOUNT;
											if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
											{
												$ITM_USED 		= ($rRQRLZ->REALZ_VOLM+$rRQRLZ->REALZUM_VOLM);
												$ITM_USED_AM 	= ($rRQRLZ->REALZ_AMOUNT+$rRQRLZ->REALZUM_AMOUNT);
											}
											else
											{
												$ITM_USED 		= ($rRQRLZ->REALZ_VOLM+$rRQRLZ->REALZIR_VOLM);
												$ITM_USED_AM 	= ($rRQRLZ->REALZ_AMOUNT+$rRQRLZ->REALZIR_AMOUNT);
											}

									endforeach;

								// Get BUDGET => KOMULATIF
									$get_LRQRLZ	= "SELECT 	SUM(AMD_VOL - AMDM_VOL) AS ADDVOLM, SUM(AMD_VAL - AMDM_VAL) AS ADD_TOTAL,
															SUM(PR_VOL+WO_VOL+VCASH_VOL+VLK_VOL+PPD_VOL+PR_VOL_R+WO_VOL_R+VCASH_VOL_R+VLK_VOL_R+PPD_VOL_R-PR_CVOL-WO_CVOL) AS REQ_VOLM,
															SUM(PO_VAL+WO_VAL+VCASH_VAL+VLK_VAL+PPD_VAL+PO_VAL_R+WO_VAL_R+VCASH_VAL_R+VLK_VAL_R+PPD_VAL_R-PO_CVAL-WO_CVAL) AS REQ_AMOUNT,
															SUM(IR_VOL+IR_VOL_R) AS REALZIR_VOLM,
															SUM(IR_VAL+IR_VAL_R) AS REALZIR_AMOUNT,
															SUM(UM_VOL+UM_VOL_R) AS REALZUM_VOLM,
															SUM(UM_VAL+UM_VAL_R) AS REALZUM_AMOUNT,
															SUM(UM_VOL+UM_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
															SUM(UM_VAL+UM_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
													FROM tbl_item_logbook_$PRJCODEVW
													WHERE $ADDQRY_01 AND DOC_DATE <= '$End_Date'";
								$res_LRQRLZ 	= $this->db->query($get_LRQRLZ);
								foreach($res_LRQRLZ->result() as $rLRQRLZ):
									// Addendum
										$ADDVOLM_KOM	= $rLRQRLZ->ADDVOLM;
										$ADD_TOTAL_KOM	= $rLRQRLZ->ADD_TOTAL;

									// REQUEST :
										$REQ_VOLM_KOM	= $rLRQRLZ->REQ_VOLM;
										$REQ_AMOUNT_KOM	= $rLRQRLZ->REQ_AMOUNT;

									// REALISASI
										// $ITM_USED_KOM	= $rLRQRLZ->REALZ_VOLM;
										// $ITM_USED_AM_KOM= $rLRQRLZ->REALZ_AMOUNT;
										if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
										{
											$ITM_USED_KOM 		= ($rLRQRLZ->REALZ_VOLM+$rLRQRLZ->REALZUM_VOLM);
											$ITM_USED_AM_KOM 	= ($rLRQRLZ->REALZ_AMOUNT+$rLRQRLZ->REALZUM_AMOUNT);
										}
										else
										{
											$ITM_USED_KOM 		= ($rLRQRLZ->REALZ_VOLM+$rLRQRLZ->REALZIR_VOLM);
											$ITM_USED_AM_KOM 	= ($rLRQRLZ->REALZ_AMOUNT+$rLRQRLZ->REALZIR_AMOUNT);
										}

								endforeach;

								// after addendum
									$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM_KOM;
									$ITM_BUDG2 		= $ITM_BUDG + $ADD_TOTAL_KOM;

								// SISA BUDG THD REQ
									$REMREQ_VOLM 	= $ITM_VOLM2 - $REQ_VOLM_KOM;
									$REMREQ_AMOUNT 	= $ITM_BUDG2 - $REQ_AMOUNT_KOM;
								
								// SISA BUDG THD REALISASI
									$REMREALZ_VOLM 	= $ITM_VOLM2 - $ITM_USED_KOM;
									$REMREALZ_AMOUNT= $ITM_BUDG2 - $ITM_USED_AM_KOM;

								if($ISLASTH == 1)
								{
									$CELL_COL	= "font-weight:bold;";
									$GTITM_BUDG = $GTITM_BUDG + $ITM_BUDG;

									// TOTAL
										$GTADD_TOTAL 		= $GTADD_TOTAL + $ADD_TOTAL;
										$GTADD_TOTAL_KOM	= $GTADD_TOTAL_KOM + $ADD_TOTAL_KOM;
										$GTITM_BUDG2 		= $GTITM_BUDG2 + $ITM_BUDG2;
										$GTREQ_AMOUNT 		= $GTREQ_AMOUNT + $REQ_AMOUNT;
										$GTREQ_AMOUNT_KOM 	= $GTREQ_AMOUNT_KOM + $REQ_AMOUNT_KOM;
										$GTITM_USED_AM 		= $GTITM_USED_AM + $ITM_USED_AM;
										$GTITM_USED_AM_KOM 	= $GTITM_USED_AM_KOM + $ITM_USED_AM_KOM;
										$GTREMREQ_AMOUNT	= $GTREMREQ_AMOUNT + $REMREQ_AMOUNT;
										$GTREMREALZ_AMOUNT 	= $GTREMREALZ_AMOUNT + $REMREALZ_AMOUNT;
								}
								else
								{
									$CELL_COL	= "";
								}
								
								?>
									<tr>
										<td style="text-align:left; border-bottom-color:#000;">
											<span style="white-space:nowrap;<?=$CELL_COL?>"><div style='margin-left: <?=$spaceLev?>px;'><?php echo "$JobView"; ?></div></span>
										</td>
										<td  style="text-align:center; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo $ITM_UNIT; ?></td>
									<!-- BUDGET AWAL --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_VOLM, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_BUDG, 2); ?></td>

									<!-- PERUBAHAN PERIODE INI --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADDVOLM, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADD_TOTAL, 2); ?></td>

									<!-- PERUBAHAN KOMULATIF --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADDVOLM_KOM, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADD_TOTAL_KOM, 2); ?></td>
										
									<!-- SETELAH PERUBAHAN --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_VOLM2, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_BUDG2, 2); ?></td>
										
									<!-- REALISASI : PERIODE INI --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($REQ_VOLM, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($REQ_AMOUNT, 2); ?></td>

									<!-- REALISASI : KOMULATIF --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($REQ_VOLM_KOM, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($REQ_AMOUNT_KOM, 2); ?></td>
										
									<!-- REALISASI : SISA BUDGET THD REALISASI --->
										<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_VOLM2, 3); ?></td>
										<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($REMREQ_AMOUNT, 2); ?></td>
									</tr>
								<?php
							endforeach;

							?>
								<tr style="border: 2px solid;">
									<td colspan="3" style="text-align: right; font-weight:bold;">TOTAL</td>
									<td style="text-align: right; font-weight:bold;"><?php echo number_format($GTITM_BUDG, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTADD_TOTAL, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTADD_TOTAL_KOM, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTITM_BUDG2, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREQ_AMOUNT, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREQ_AMOUNT_KOM, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($GTREMREQ_AMOUNT, 2); ?></td>
								</tr>
							<?php
						}
        			?>
        		</tbody>
        	</table>
        </div>
	</body>
</html>