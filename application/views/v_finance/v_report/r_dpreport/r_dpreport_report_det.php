<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Agustus 2018
 * File Name	= r_invselect_report.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');



if($PRJCODE == 1) 
{
	$PRJNAME = "Semua Proyek";
}
else
{
	$getPRJN = $this->db->select("PRJNAME")->get_where("tbl_project", ["PRJCODE" => $PRJCODE])->row("PRJNAME");
	$PRJNAME = "$getPRJN ($PRJCODE)";
}
?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title><?=$title?></title>
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
	            width: 29.7cm;
	            /* min-height: 29.7cm; */
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
    			size: A4;*/
	            margin: 0;
	        }

	        @media print {

	            @page{size: lanscap;}
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

			table th {
				padding: 3px;;
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
                    <td><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/nkes/compLog.png') ?>" alt="" width="181" height="44"></td>
                    <td>
                        <h3><?php //echo $h1_title; ?></h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td><?php echo "$h1_title"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr>
                    <td width="100">Periode</td>
                    <td width="10">:</td>
                    <td><?php echo $datePeriod; ?></td>
                </tr>
                <tr>
                    <td width="100">Tgl. Cetak</td>
                    <td width="10">:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
            <table width="100%" border="1" rules="all">
			    <tr>
			        <td colspan="3" class="style2">
			            <table width="100%" border="1" rules="all">
			                <tr style="background:#CCCCCC">
			                  	<th width="10" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
			                  	<th width="70" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL. TRX</th>
								<th width="100" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">NOMOR TRX</th>
								<th style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">PEMASOK</th>
								<th width="100" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">NO. OP/SPK</th>
								<th width="50" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">DP (%)</th>
								<th width="120" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">NILAI DP</th>
								<th width="120" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TOTAL POT.DP</th>
								<th width="120" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TOTAL SISA DP</th>
								<th width="120" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">KODE BAYAR</th>
			              	</tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
								<td colspan="10" style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
								// $addQuery = "";
			               		// if($PRJCODE != 1)
								// {
								// 	$addQuery .= " AND A.PRJCODE = '$PRJCODE'";
								// }

								$addQuery = "";
								if($SPLCODE != 1)
								{
									$addQuery 	.= " AND A.SPLCODE = '$SPLCODE'";
								}

			                    // $getDP 		= "SELECT A.* FROM tbl_dp_header A 
								// 				WHERE A.DP_DATE BETWEEN '$Start_Date' AND '$End_Date' $addQuery";
								$getDP 		= "SELECT A.* FROM tbl_dp_header A 
												WHERE A.DP_DATE BETWEEN '$Start_Date' AND '$End_Date'
												AND A.PRJCODE = '$PRJCODE' $addQuery";
								$resDP 		= $this->db->query($getDP);
								if($resDP->num_rows() > 0)
								{
									$REM_DPVAL = 0;
									
									$GTOT_DPAM 		= 0;
									$GTOT_POTDPVAL	= 0;
									$GTOT_REMDPVAL 	= 0;
									$POT_TOTDP 		= 0;
									$REM_TOTDP 		= 0;
									$DP_AMOUNT 		= 0;
									$newNo 			= 0;
									foreach($resDP->result() as $rDP):
										$newNo 			= $newNo + 1;
										$DP_NUM 		= $rDP->DP_NUM;
										$DP_CODE 		= $rDP->DP_CODE;	
										$DP_DATE 		= $rDP->DP_DATE;
										$DP_DATEV 		= date('d/m/Y', strtotime($DP_DATE));
										$PRJCODE 		= $rDP->PRJCODE;	
										$SPLCODE 		= $rDP->SPLCODE;	
										$DP_REFNUM 		= $rDP->DP_REFNUM;	
										$DP_REFCODE 	= $rDP->DP_REFCODE;	
										$DP_REFAMOUNT 	= $rDP->DP_REFAMOUNT;	
										$DP_DESC 		= $rDP->DP_DESC;	
										$DP_PERC 		= $rDP->DP_PERC;	
										$DP_AMOUNT 		= $rDP->DP_AMOUNT;	
										$DP_AMOUNT_USED = $rDP->DP_AMOUNT_USED;	
										$TAXCODE_PPN 	= $rDP->TAXCODE_PPN;	
										$DP_AMOUNT_PPN 	= $rDP->DP_AMOUNT_PPN;
										$DP_AMOUNT_PPNP = $rDP->DP_AMOUNT_PPNP;	
										$TAXCODE_PPH 	= $rDP->TAXCODE_PPH;	
										$DP_AMOUNT_PPH 	= $rDP->DP_AMOUNT_PPH;
										$DP_AMOUNT_PPHP = $rDP->DP_AMOUNT_PPHP;	
										$DP_AMOUN_TOT 	= $rDP->DP_AMOUN_TOT;	
										$DP_NOTES 		= $rDP->DP_NOTES;	
										$TTK_NUM 		= $rDP->TTK_NUM;	
										$TTK_CODE 		= $rDP->TTK_CODE;	
										$BP_CODE 		= $rDP->BP_CODE;	
										$BP_DATE 		= $rDP->BP_DATE;	
										$BP_AMOUNT 		= $rDP->BP_AMOUNT;	
										$DP_STAT 		= $rDP->DP_STAT;	
										$DP_PAID 		= $rDP->DP_PAID;

										//$GTOT_DPAM 		= $GTOT_DPAM + $DP_AMOUN_TOT;
										$GTOT_DPAM 		= $GTOT_DPAM + $DP_AMOUNT;

										// get SPLDESC
											$SPLDESC 	= "";
											$getSPL 	= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
											$resSPL 	= $this->db->query($getSPL);
											if($resSPL->num_rows() > 0)
											{
												foreach($resSPL->result() as $rSPL):
													$SPLDESC = $rSPL->SPLDESC;
												endforeach;
											}

										// get POT. DP in SPK
										/* ------------------- hidden: Pot. DP diambil dari Voucher ------------------------------------
											$POT_TOTDP 	= 0;
											$getPOTDP  	= "SELECT SUM(OPNH_DPVAL) TOT_DPVAL FROM tbl_opn_header 
															WHERE WO_NUM = '$DP_REFNUM' AND OPNH_TYPE = 0 AND OPNH_STAT NOT IN (5,9)";
											$resPOTDP 	= $this->db->query($getPOTDP);
											foreach($resPOTDP->result() as $rPOTDP):
												$TOT_DPVAL 	= $rPOTDP->TOT_DPVAL;
											endforeach;

											$REM_DPVAL = $DP_AMOUNT - $TOT_DPVAL;

											$GTOT_DPAM 		= $GTOT_DPAM + $DP_AMOUNT;
											$GTOT_POTDPVAL	= $GTOT_POTDPVAL + $TOT_DPVAL;
											$GTOT_REMDPVAL 	= $GTOT_REMDPVAL + $REM_DPVAL;
										-------------------- END Hidden --------------------------------------------------------------- */

										//$REM_TOTDP = $DP_AMOUN_TOT - $POT_TOTDP;
										$REM_TOTDP = $DP_AMOUNT;

										// get Kode Bayar DP
											$CB_CODE = "";
											$getPAYDP 	= "SELECT A.CB_CODE FROM tbl_bp_detail A
															INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE
															WHERE B.PRJCODE = '$PRJCODE' AND A.CBD_DOCNO = '$DP_NUM' AND B.CB_STAT NOT IN (5,9)";
											$resPAYDP 	= $this->db->query($getPAYDP);
											if($resPAYDP->num_rows() > 0)
											{
												$CB_CODE1 = [];
												foreach($resPAYDP->result() as $rPAYDP):
													$CB_CODE1[] = $rPAYDP->CB_CODE;
												endforeach;
												$CB_CODE 	= join(", ", $CB_CODE1);
											}

											$POT_TOTDP 		= 0;

										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 5px;"><?=$newNo?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?=$DP_DATEV?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?=$DP_CODE?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo "$SPLDESC ($SPLCODE)"; ?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?=$DP_REFCODE?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo number_format($DP_PERC, $decFormat); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo number_format($DP_AMOUNT, $decFormat); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo number_format($POT_TOTDP, $decFormat); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo number_format($REM_TOTDP, $decFormat); ?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 5px;"><?=$CB_CODE?></td>
											</tr>
										<?php

										// get POT. DP in VOUCHER
											$getPOTDP 	= "SELECT B.*, A.TTK_NUM FROM tbl_pinv_detail A
															INNER JOIN tbl_pinv_header B ON B.INV_NUM = A.INV_NUM AND B.PRJCODE = A.PRJCODE
															WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_ttk_detail 
																				WHERE PRJCODE = '$PRJCODE' AND (TTK_REF2_NUM = '$DP_REFNUM' OR TTK_REF2_CODE = '$DP_REFCODE'))
															AND B.PRJCODE = '$PRJCODE' AND A.INV_STAT NOT IN (5,9)";
											$resPOTDP 	= $this->db->query($getPOTDP);
											if($resPOTDP->num_rows() > 0)
											{
												$newNo = $newNo;
												foreach($resPOTDP->result() as $rPOTDP):
													$newNo 			= $newNo + 1;
													$INV_NUM 		= $rPOTDP->INV_NUM;
													$INV_CODE 		= $rPOTDP->INV_CODE;
													$INV_DATE 		= $rPOTDP->INV_DATE;
													$INV_DATEV 		= date('d/m/Y', strtotime($INV_DATE));
													$TTK_NUM 		= $rPOTDP->TTK_NUM;
													$SPLCODE 		= $rPOTDP->SPLCODE;
													$INV_AMOUNT_DPB = $rPOTDP->INV_AMOUNT_DPB;
													$BP_CODE 		= $rPOTDP->BP_CODE;
													$BP_DATE 		= $rPOTDP->BP_DATE;

													// get referensi
														$REF_NUM 	= "";
														$REF_CODE 	= "";
														$getREF 	= "SELECT TTK_REF2_NUM, TTK_REF2_CODE FROM tbl_ttk_detail
																		WHERE TTK_NUM = '$TTK_NUM' AND PRJCODE = '$PRJCODE'";
														$resREF 	= $this->db->query($getREF);
														if($resREF->num_rows() > 0)
														{
															foreach($resREF->result() as $rREF):
																$REF_NUM 	= $rREF->TTK_REF2_NUM;
																$REF_CODE 	= $rREF->TTK_REF2_CODE;
															endforeach;
														}

													$POT_TOTDP 		= $INV_AMOUNT_DPB;
													$REM_TOTDP 		= $REM_TOTDP - $POT_TOTDP;

													?>
														<tr>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 5px;"><?=$newNo?></td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?=$INV_DATEV?></td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?=$INV_CODE?></td>
															<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo "$SPLDESC ($SPLCODE)"; ?></td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?=$REF_CODE?></td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;">-</td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;">-</td>
															<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo number_format($POT_TOTDP, $decFormat); ?></td>
															<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo number_format($REM_TOTDP, $decFormat); ?></td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 5px;"><?=$BP_CODE?></td>
														</tr>
													<?php

													$GTOT_POTDPVAL	= $GTOT_POTDPVAL + $POT_TOTDP;
													//$GTOT_REMDPVAL 	= $GTOT_DPAM - $GTOT_POTDPVAL;
													$GTOT_REMDPVAL 	= $DP_AMOUNT - $GTOT_POTDPVAL;

												endforeach;
											}

											?>
												<tr>
													<td colspan="6" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>TOTAL</b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_DPAM, $decFormat); ?></b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_POTDPVAL, $decFormat); ?></b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
												</tr>
											<?php

									endforeach;

									/* ----------------------------- Hidden -----------------
									if($newNo <= 5)
									{
										for($i=$newNo;$i<5;$i++) {
											?>
												<tr>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
												</tr>
											<?php
										}
									------------------------------------- END Hidden -----------------------*/

									
								}
								else
								{
									?>
										<tr>
											<td colspan="10" style="text-align:center;">--- none ---</td>
										</tr>
									<?php
								}
			               	?>
			            </table>
			      </td>
			    </tr>
			</table>
		</div>
	</body>
</body>
</html>