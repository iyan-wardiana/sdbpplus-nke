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
	               	</tr>
	            </thead>
	            <tbody>
               	<?php
					$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

					if($ITM_GROUP == 'All') $addQGItem = '';
					else $addQGItem = "WHERE IG_Code = '$ITM_GROUP'";

					// get Group Item
						$getGItem 	= "SELECT * FROM tbl_itemgroup $addQGItem";
						$resGItem 	= $this->db->query($getGItem);
						if($resGItem->num_rows() > 0)
						{
							$newNo = 0;
							$GTOT_BUDGM 	= 0;
							$GTOT_ADDM 		= 0;
							$GTOT_ADDM_KOM 	= 0;
							$GTOT_BUDGM2	= 0;
							$GTOT_REQM 		= 0;
							$GTOT_REQM_KOM 	= 0;
							$GTOT_REMREQM	= 0;
							$GTOT_REALZM 	= 0;
							$GTOT_REALZM_KOM= 0;
							$GTOT_REMREALZM	= 0;
							foreach($resGItem->result() as $rGItem):
								$IG_Code	= $rGItem->IG_Code;
								$IG_Name 	= $rGItem->IG_Name;

								// get Joblist_detail
									$TOT_BUDGM 			= 0;
									$TOT_ADDM 			= 0;
									$TOT_BUDGM2 		= 0;
									$TOT_REQM 			= 0;
									$TOT_REMREQM 		= 0;
									$TOT_REALZM 		= 0;
									$TOT_REMREALZM 		= 0;

									$TOT_ADDM_KOM 		= 0;
									$TOT_BUDGM2_KOM 	= 0;
									$TOT_REQM_KOM 		= 0;
									$TOT_REMREQM_KOM 	= 0;
									$TOT_REALZM_KOM 	= 0;
									$TOT_REMREALZM_KOM 	= 0;
									$getJobD 	= "SELECT A.PRJCODE, A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.JOBDESC,
													SUM(A.ITM_VOLM) AS ITM_VOLMBG, A.ITM_PRICE, A.ITM_LASTP, SUM(A.ITM_BUDG) AS ITM_BUDG,
													A.IS_LEVEL, A.ISLASTH, A.ISLAST, A.WBSD_STAT, B.ITM_NAME
													FROM tbl_joblist_detail_$PRJCODEVW A
													LEFT JOIN tbl_item_$PRJCODEVW B ON B.ITM_CODE = A.ITM_CODE 
													AND B.ITM_GROUP = A.ITM_GROUP AND B.PRJCODE = A.PRJCODE
													WHERE A.ITM_CODE != '' AND A.ITM_GROUP = '$IG_Code' AND A.ISLAST = 1
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
											$ITM_VOLMBG = $rJobD->ITM_VOLMBG;
											$ITM_PRICE 	= $rJobD->ITM_PRICE;
											$ITM_LASTP 	= $rJobD->ITM_LASTP;
											$ITM_BUDG 	= $rJobD->ITM_BUDG;
											$IS_LEVEL 	= $rJobD->IS_LEVEL;
											$ISLAST 	= $rJobD->ISLAST;
											$ISLASTH 	= $rJobD->ISLASTH;

											// Get ADD, REQ, REALIZ PERIODE INI
												$getBUDGItem = "SELECT SUM(AMD_VOL+AMDM_VOL) AS ADDVOLM, SUM(AMD_VAL+AMDM_VAL) AS ADD_TOTAL,
																SUM(PR_VOL+PR_VOL_R+WO_VOL+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
																SUM(PO_VAL+PO_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
																-- SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
																SUM(IR_VOL+IR_VOL_R) AS REALZIR_VOLM,
																SUM(UM_VOL+UM_VOL_R) AS REALZUM_VOLM,
																SUM(OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
																-- SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
																SUM(IR_VAL+IR_VAL_R) AS REALZIR_AMOUNT,
																SUM(UM_VAL+UM_VAL_R) AS REALZUM_AMOUNT,
																SUM(OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
																FROM tbl_joblist_report_$PRJCODEVW
																WHERE ITM_CODE = '$ITM_CODE' 
																AND PERIODE BETWEEN '$Start_Date' AND '$End_Date'
																GROUP BY ITM_CODE";
												$resBUDGItem = $this->db->query($getBUDGItem);

												$ADDVOLM 			= 0;
												$ADD_TOTAL 			= 0;
												$REQ_VOLM 			= 0;
												$REQ_AMOUNT 		= 0;
												$ITM_USED 			= 0;
												$ITM_USED_AM 		= 0;

												$TOT_REQ_VOLM 		= 0;
												$TOT_REQ_AMOUNT		= 0;
												$TOT_ADD_TOTAL 		= 0;
												$TOT_ADDVOLM 		= 0;
												$TOT_ITM_USED 		= 0;
												$TOT_ITM_USED_AM 	= 0;
												
												$REMREQ_VOLM 		= 0;
												$REMREQ_AMOUNT 		= 0;
												$REMREALZ_VOLM 		= 0;
												$REMREALZ_AMOUNT 	= 0;
												if($resBUDGItem->num_rows() > 0)
												{
													foreach($resBUDGItem->result() as $rBUDGItem):
														// Addendum => SAAT INI
															$ADDVOLM 		= $rBUDGItem->ADDVOLM;
															$ADD_TOTAL 		= $rBUDGItem->ADD_TOTAL;
			
														// REQUEST => SAAT INI
															$REQ_VOLM 		= $rBUDGItem->REQ_VOLM;
															$REQ_AMOUNT 	= $rBUDGItem->REQ_AMOUNT;
			
														// REALISASI => SAAT INI
															// $ITM_USED 		= $rBUDGItem->REALZ_VOLM;
															// $ITM_USED_AM 	= $rBUDGItem->REALZ_AMOUNT;
															// START : REALISASI ITEM
																// Berdasarkan Memo tgl. 17-01-2023 dari pak wawan => nilai realisasi lebih besar dari nilai request
																// dikarenakan nilai penggunaan (UM) > IR
																// berdasarkan kesepakatan dengan pak dede by phone tgl. 02-02-2023 nilai realisasi diambil dari nilai penerimaan (IR)
																/* ----------------- Hidden -----------------------------
																if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
																{
																	$ITM_USED 		= ($rBUDGItem->REALZ_VOLM+$rBUDGItem->REALZUM_VOLM);
																	$ITM_USED_AM 	= ($rBUDGItem->REALZ_AMOUNT+$rBUDGItem->REALZUM_AMOUNT);
																}
																else
																{
																	$ITM_USED 		= ($rBUDGItem->REALZ_VOLM+$rBUDGItem->REALZIR_VOLM);
																	$ITM_USED_AM 	= ($rBUDGItem->REALZ_AMOUNT+$rBUDGItem->REALZIR_AMOUNT);
																}
																----------------- End Hidden ------------------------------ */
																$ITM_USED 		= ($rBUDGItem->REALZ_VOLM+$rBUDGItem->REALZIR_VOLM);
																$ITM_USED_AM 	= ($rBUDGItem->REALZ_AMOUNT+$rBUDGItem->REALZIR_AMOUNT);
															// END : REALISASI ITEM

														// TOTAL 
															$TOT_ADDVOLM 		= $TOT_ADDVOLM + $ADDVOLM;
															$TOT_ADD_TOTAL 		= $TOT_ADD_TOTAL + $ADD_TOTAL;

															$TOT_REQ_VOLM 		= $TOT_REQ_VOLM + $REQ_VOLM;
															$TOT_REQ_AMOUNT 	= $TOT_REQ_AMOUNT + $REQ_AMOUNT;

															$TOT_ITM_USED 		= $TOT_ITM_USED + $ITM_USED;
															$TOT_ITM_USED_AM 	= $TOT_ITM_USED_AM + $ITM_USED_AM;
													endforeach;
												}

											// Total => SAAT INI
												$TOT_ADDM 		= $TOT_ADDM + $TOT_ADD_TOTAL;
												$TOT_REQM 		= $TOT_REQM + $TOT_REQ_AMOUNT;
												$TOT_REALZM 	= $TOT_REALZM + $TOT_ITM_USED_AM;

											// Get ADD, REQ, REALIZ KOMULATIF
												$getBUDGLItem = "SELECT SUM(AMD_VOL+AMDM_VOL) AS ADDVOLM, SUM(AMD_VAL+AMDM_VAL) AS ADD_TOTAL,
																SUM(PR_VOL+PR_VOL_R+WO_VOL+WO_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REQ_VOLM,
																SUM(PO_VAL+PO_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REQ_AMOUNT,
																-- SUM(IR_VOL+IR_VOL_R+OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
																SUM(IR_VOL+IR_VOL_R) AS REALZIR_VOLM,
																SUM(UM_VOL+UM_VOL_R) AS REALZUM_VOLM,
																SUM(OPN_VOL+OPN_VOL_R+VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS REALZ_VOLM,
																-- SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
																SUM(IR_VAL+IR_VAL_R) AS REALZIR_AMOUNT,
																SUM(UM_VAL+UM_VAL_R) AS REALZUM_AMOUNT,
																SUM(OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS REALZ_AMOUNT
																FROM tbl_joblist_report_$PRJCODEVW
																WHERE ITM_CODE = '$ITM_CODE'
																AND PERIODE <= '$End_Date'
																GROUP BY ITM_CODE";
												$resBUDGLItem = $this->db->query($getBUDGLItem);	
												
												$ADDVOLM_KOM 			= 0;
												$ADD_TOTAL_KOM 			= 0;
												$REQ_VOLM_KOM 			= 0;
												$REQ_AMOUNT_KOM 		= 0;
												$ITM_USED_KOM 			= 0;
												$ITM_USED_AM_KOM 		= 0;

												$TOT_ADDVOLM_KOM 		= 0;
												$TOT_ADD_TOTAL_KOM 		= 0;
												$TOT_REQ_VOLM_KOM 		= 0;
												$TOT_REQ_AMOUNT_KOM 	= 0;
												$TOT_ITM_USED_KOM 		= 0;
												$TOT_ITM_USED_AM_KOM 	= 0;

												$REMREQ_VOLM_KOM 		= 0;
												$REMREQ_AMOUNT_KOM 		= 0;
												$REMREALZ_VOLM_KOM 		= 0;
												$REMREALZ_AMOUNT_KOM 	= 0;
												if($resBUDGLItem->num_rows() > 0)
												{
													foreach($resBUDGLItem->result() as $rBUDGLItem):
														// Addendum => KOMULATIF
															$ADDVOLM_KOM 		= $rBUDGLItem->ADDVOLM;
															$ADD_TOTAL_KOM 		= $rBUDGLItem->ADD_TOTAL;
			
														// REQUEST => KOMULATIF
															$REQ_VOLM_KOM 		= $rBUDGLItem->REQ_VOLM;
															$REQ_AMOUNT_KOM 	= $rBUDGLItem->REQ_AMOUNT;
			
														// REALISASI => KOMULATIF
															// $ITM_USED_KOM 		= $rBUDGLItem->REALZ_VOLM;
															// $ITM_USED_AM_KOM 	= $rBUDGLItem->REALZ_AMOUNT;
															// START : REALISASI ITEM
																// Berdasarkan Memo tgl. 17-01-2023 dari pak wawan => nilai realisasi lebih besar dari nilai request
																// dikarenakan nilai penggunaan (UM) > IR
																// berdasarkan kesepakatan dengan pak dede by phone tgl. 02-02-2023 nilai realisasi diambil dari nilai penerimaan (IR)
																/* ----------------- Hidden -----------------------------
																if($ITM_GROUP == 'M' || $ITM_GROUP == 'T')
																{
																	$ITM_USED_KOM 		= ($rBUDGLItem->REALZ_VOLM+$rBUDGLItem->REALZUM_VOLM);
																	$ITM_USED_AM_KOM 	= ($rBUDGLItem->REALZ_AMOUNT+$rBUDGLItem->REALZUM_AMOUNT);
																}
																else
																{
																	$ITM_USED_KOM 		= ($rBUDGLItem->REALZ_VOLM+$rBUDGLItem->REALZIR_VOLM);
																	$ITM_USED_AM_KOM 	= ($rBUDGLItem->REALZ_AMOUNT+$rBUDGLItem->REALZIR_AMOUNT);
																}
																----------------- End Hidden ----------------------------- */
																$ITM_USED_KOM 		= ($rBUDGLItem->REALZ_VOLM+$rBUDGLItem->REALZIR_VOLM);
																$ITM_USED_AM_KOM 	= ($rBUDGLItem->REALZ_AMOUNT+$rBUDGLItem->REALZIR_AMOUNT);
															// END : REALISASI ITEM

														// TOTAL 
															$TOT_ADDVOLM_KOM 		= $TOT_ADDVOLM + $ADDVOLM_KOM;
															$TOT_ADD_TOTAL_KOM 		= $TOT_ADD_TOTAL + $ADD_TOTAL_KOM;

															$TOT_REQ_VOLM_KOM 		= $TOT_REQ_VOLM + $REQ_VOLM_KOM;
															$TOT_REQ_AMOUNT_KOM 	= $TOT_REQ_AMOUNT + $REQ_AMOUNT_KOM;

															$TOT_ITM_USED_KOM 		= $TOT_ITM_USED + $ITM_USED_KOM;
															$TOT_ITM_USED_AM_KOM 	= $TOT_ITM_USED_AM_KOM + $ITM_USED_AM_KOM;
													endforeach;
												}

												// after addendum
													$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM_KOM;
													$ITM_BUDG2		= $ITM_BUDG + $ADD_TOTAL_KOM;

												// SISA BUDG THD REQ
													$REMREQ_VOLM 	= $ITM_VOLM2 - $REQ_VOLM_KOM;
													$REMREQ_AMOUNT 	= $ITM_BUDG2 - $REQ_AMOUNT_KOM;
												
												// SISA BUDG THD REALISASI
													$REMREALZ_VOLM 	= $ITM_VOLM2 - $ITM_USED_KOM;
													$REMREALZ_AMOUNT= $ITM_BUDG2 - $ITM_USED_AM_KOM;

												// Total => BULAN LALU
													$TOT_ADDM_KOM 		= $TOT_ADDM_KOM + $TOT_ADD_TOTAL_KOM;
													$TOT_REQM_KOM 		= $TOT_REQM_KOM + $TOT_REQ_AMOUNT_KOM;
													$TOT_REALZM_KOM 	= $TOT_REALZM_KOM + $TOT_ITM_USED_AM_KOM;

												// TOTAL ITEM
													$TOT_BUDGM 		= $TOT_BUDGM + $ITM_BUDG;
													$TOT_BUDGM2		= $TOT_BUDGM2 + $ITM_BUDG2;
													$TOT_REQM_KOM 	= $TOT_REQM_KOM + $REQ_AMOUNT_KOM;
													$TOT_REMREQM	= $TOT_REMREQM + $REMREQ_AMOUNT;
													$TOT_REMREALZM	= $TOT_REMREALZM + $REMREALZ_AMOUNT;

												if($ITM_NAME == '') $ITM_NAME = $JOBDESC;
													
												?>
													<tr>
														<td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?=$newNo?></td>
														<td nowrap style="text-align:left;"><?php echo $ITM_CODE; ?></td>
														<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
														<td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
														<td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;"><?php echo $ITM_GROUP; ?></td>
														<!-- SETELAH PERUBAHAN --->
															<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLM2, 2); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_BUDG2, 2); ?></td>
															
														<!-- REQUEST : PERIODE INI --->
															<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM, 3); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT, 2); ?></td>

														<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;"><?php echo number_format($REQ_VOLM_KOM, 3); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REQ_AMOUNT_KOM, 2); ?></td>
															
														<!-- REQUEST : SISA BUDGET THD REQUEST --->
															<td nowrap style="text-align:right;"><?php echo number_format($REMREQ_VOLM, 3); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REMREQ_AMOUNT, 2); ?></td>
															
														<!-- REALISASI : PERIODE INI --->
															<td nowrap style="text-align:right;"><?php echo number_format($ITM_USED, 3); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_USED_AM, 2); ?></td>
														
														<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;"><?php echo number_format($ITM_USED_KOM, 3); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($ITM_USED_AM_KOM, 2); ?></td>
															
														<!-- REALISASI : SISA BUDGET THD REALISASI --->
															<td nowrap style="text-align:right;"><?php echo number_format($REMREALZ_VOLM, 2); ?></td>
															<td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;"><?php echo number_format($REMREALZ_AMOUNT, 2); ?></td>
													</tr>
												<?php
										endforeach;

										if($IG_Code == 'M')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										if($IG_Code == 'U')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										if($IG_Code == 'S')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										if($IG_Code == 'T')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										if($IG_Code == 'I')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php // echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										if($IG_Code == 'R')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										if($IG_Code == 'O')
										{
											?>
												
												<tr style="background:#CCCCCC; font-weight: bold;">
													<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL <?=$IG_Name?></td>
													<!-- SETELAH PERUBAHAN --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_BUDGM2, 2); ?></td>
														
													<!-- REQUEST : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM, 2); ?></td>

													<!-- REQUEST : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REQM_KOM, 2); ?></td>
														
													<!-- REQUEST : SISA BUDGET THD REQUEST --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREQM, 2); ?></td>
														
													<!-- REALISASI : PERIODE INI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM, 2); ?></td>

													<!-- REALISASI : KOMULATIF --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REALZM_KOM, 2); ?></td>
														
													<!-- REALISASI : SISA BUDGET THD REALISASI --->
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
														<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($TOT_REMREALZM, 2); ?></td>
												</tr>
												
											<?php
										}

										// TOTAL Keseluruhan
											$GTOT_BUDGM 	= $GTOT_BUDGM + $TOT_BUDGM;
											$GTOT_ADDM 		= $GTOT_ADDM + $TOT_ADDM;
											$GTOT_ADDM_KOM	= $GTOT_ADDM_KOM + $TOT_ADDM_KOM;
											$GTOT_BUDGM2	= $GTOT_BUDGM2 + $TOT_BUDGM2;
											$GTOT_REQM 		= $GTOT_REQM + $TOT_REQM;
											$GTOT_REQM_KOM	= $GTOT_REQM_KOM + $TOT_REQM_KOM;
											$GTOT_REMREQM	= $GTOT_REMREQM + $TOT_REMREQM;
											$GTOT_REALZM 	= $GTOT_REALZM + $TOT_REALZM;
											$GTOT_REALZM_KOM= $GTOT_REALZM_KOM + $TOT_REALZM_KOM;
											$GTOT_REMREALZM	= $GTOT_REMREALZM + $TOT_REMREALZM;
									}
							endforeach;
							
							?>
												
								<tr style="background:#CCCCCC; font-weight: bold;">
									<td nowrap colspan="5" style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;">TOTAL KESELURUHAN</td>
									<!-- SETELAH PERUBAHAN --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_BUDGM2, 2); ?></td>
										
									<!-- REQUEST : PERIODE INI --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REQM, 2); ?></td>

									<!-- REQUEST : KOMULATIF --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REQM_KOM, 2); ?></td>
										
									<!-- REQUEST : SISA BUDGET THD REQUEST --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REMREQM, 2); ?></td>
										
									<!-- REALISASI : PERIODE INI --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REALZM, 2); ?></td>

									<!-- REALISASI : KOMULATIF --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REALZM_KOM, 2); ?></td>
										
									<!-- REALISASI : SISA BUDGET THD REALISASI --->
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format(0, 2); ?></td>
										<td nowrap style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; border-top-width:2px; border-top-color:#000;"><?php echo number_format($GTOT_REMREALZM, 2); ?></td>
								</tr>
								
							<?php
						}
				?>
				</tbody>
            </table>
        </div>
	</body>
</html>