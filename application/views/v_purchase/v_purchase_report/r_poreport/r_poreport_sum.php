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
                    <td><img src="<?= base_url('assets/AdminLTE-2.0.5/dist/img/compLog/NKES/compLog.png') ?>" alt="" width="181" height="44"></td>
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
			                  	<th width="10" rowspan="2" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
			                  	<th width="70" rowspan="2" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL.</th>
			                  	<th colspan="6" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">UANG MUKA / DOWN PAYMENT (DP)</th>
			              	</tr>
			                <tr style="background:#CCCCCC">
								<th width="250" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">PEMASOK</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">DESKRIPSI</th>
								<th width="120" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TOTAL DP</th>
								<th width="120" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL POT.DP</th>
								<th width="120" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL SISA DP</th>
								<th width="120" style="text-align:center; font-weight:bold;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">KETERANGAN</th>
			                </tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
			                <td style="text-align:center;border:none">&nbsp;</td>
								<td style="text-align:center;border:none">&nbsp;</td>
								<td style="text-align:center;border:none">&nbsp;</td>
								<td style="text-align:center;border:none">&nbsp;</td>
								<td style="text-align:center;border:none">&nbsp;</td>
								<td colspan="3" style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
								// $addQuery = "";
			               		// if($PRJCODE != 1)
								// {
								// 	$addQuery .= " AND A.PRJCODE = '$PRJCODE'";
								// }

								// $addQSPL = "";
								// if($SPLCODE[0] != 1)
								// {
								// 	$joinQSPL 	= join("','", $SPLCODE);
								// 	$addQuery 	.= " AND A.SPLCODE IN ('$joinQSPL')";
								// }

			                    // $getDP 		= "SELECT A.* FROM tbl_dp_header A 
								// 				WHERE A.DP_DATE BETWEEN '$Start_Date' AND '$End_Date' $addQuery";
								$getDP 		= "SELECT A.* FROM tbl_dp_header A 
												WHERE A.DP_DATE BETWEEN '$Start_Date' AND '$End_Date' 
												AND A.PRJCODE = '$PRJCODE'AND A.SPLCODE = '$SPLCODE'";
								$resDP 		= $this->db->query($getDP);
								if($resDP->num_rows() > 0)
								{
									$newNo = 0;
									$REM_DPVAL = 0;

									$GTOT_DPAM 		= 0;
									$GTOT_POTDPVAL	= 0;
									$GTOT_REMDPVAL 	= 0;
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
										-------------------- END Hidden --------------------------------------------------------------- */

										// get POT. DP in VOUCHER
										$getPOTDP 	= "SELECT SUM(B.INV_AMOUNT_DPB) AS TOT_DPVAL FROM tbl_pinv_detail A
														INNER JOIN tbl_pinv_header B ON B.INV_NUM = A.INV_NUM AND B.PRJCODE = A.PRJCODE
														WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_ttk_detail 
																			WHERE PRJCODE = '$PRJCODE' AND TTK_REF2_NUM = '$DP_REFNUM') 
														AND B.PRJCODE = '$PRJCODE'";
										$resPOTDP 	= $this->db->query($getPOTDP);
										foreach($resPOTDP->result() as $rPOTDP):
											$TOT_DPVAL 	= $rPOTDP->TOT_DPVAL;
										endforeach;

										$REM_DPVAL = $DP_AMOUN_TOT - $TOT_DPVAL;

										$GTOT_DPAM 		= $GTOT_DPAM + $DP_AMOUN_TOT;
										$GTOT_POTDPVAL	= $GTOT_POTDPVAL + $TOT_DPVAL;
										$GTOT_REMDPVAL 	= $GTOT_REMDPVAL + $REM_DPVAL;

										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;"><?=$DP_DATEV?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo "$SPLDESC ($SPLCODE)"; ?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo "$DP_NOTES"; ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DP_AMOUN_TOT, 2); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($TOT_DPVAL, 2); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($REM_DPVAL, 2); ?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
											</tr>
										<?php
									endforeach;

									if($newNo <= 5)
									{
										for($i=$newNo;$i<5;$i++) {
											?>
												<tr>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
												</tr>
											<?php
										}
									}

									?>
										<tr>
											<td colspan="4" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>TOTAL</b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_DPAM, 2); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_POTDPVAL, 2); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_REMDPVAL, 2); ?></b></td>
											<td style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
										</tr>
									<?php
								}
								else
								{
									?>
										<tr>
											<td colspan="8" style="text-align:center;">--- none ---</td>
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