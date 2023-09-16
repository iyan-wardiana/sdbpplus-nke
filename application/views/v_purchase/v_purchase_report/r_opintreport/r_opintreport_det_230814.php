<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 14 Agustus 2023
 	* File Name		= r_opintreport_det.php
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


$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
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
	            width: 21cm;
	            min-height: 29.7cm; 
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
	            /* margin: 0; */
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
                    <td><img src="<?= base_url('assets/AdminLTE-2.0.5/dist/img/compLog/nkes/compLog.png') ?>" alt="" width="181" height="44"></td>
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
                    <td><?php echo "$mnName"; ?></td>
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
			                  	<th width="5%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000" nowrap>NO. OPNAME</th>
			                  	<th width="5%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;" nowrap>TANGGAL</th>
								<th width="45%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">DESKRIPSI</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">NILAI SPK</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">NILAI OPNAME</th>
			              	</tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
								<td colspan="13" style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
								$addQuery = "";
								if($SPLCODE[0] != 1)
								{
									$joinQSPL 	= join("','", $SPLCODE);
									$addQuery 	= "AND SPLCODE IN ('$joinQSPL')";
								}

								$addQRYPRJ 	= "";
								if($PRJCODE != 0)
									$addQRYPRJ	= " AND PRJCODE = '$PRJCODE'";

								$addQRYWO 	= "";
								if($WO_NUM != 0)
									$addQRYWO	= " AND WO_NUM = '$WO_NUM'";

								$getPO 		= "SELECT OPNH_CODE, OPNH_DATE, SPLCODE, SPLDESC, WO_NUM, WO_CODE, OPNH_NOTE, OPNH_AMOUNT, OPNH_AMOUNTPPN,
													OPNH_AMOUNTPPH, OPNH_RETAMN
												FROM tbl_opn_header
												WHERE OPNH_STAT NOT IN (5,9) AND OPNH_TYPE = 0 AND WO_CATEG = 'T'
								 					AND OPNH_DATE BETWEEN '$Start_Date' AND '$End_Date' $addQuery $addQRYPRJ $addQRYWO
												ORDER BY WO_CODE ASC";
								$resPO 		= $this->db->query($getPO);
								$JOBCODEID_B 	= "";
								$PO_NUM_B 		= "";
								$ITM_CODE_B 	= "";
								$PO_DESC_B 		= "";
								$newNo_B		= "";
								if($resPO->num_rows() > 0)
								{
									$newNo 			= 0;
									$WO_CODE2 		= "";
									$GTOT_OPNH 		= 0;
									$GTOT_SPK		= 0;
									foreach($resPO->result() as $rPO):
										$newNo 			= $newNo + 1;
										$OPNH_CODE 		= $rPO->OPNH_CODE;
										$OPNH_DATE   	= $rPO->OPNH_DATE;
										$OPNH_DATEV 	= date('d/m/Y', strtotime($OPNH_DATE));
										$SPLCODE 		= $rPO->SPLCODE;
										$SPLDESC 		= $rPO->SPLDESC;
										$WO_NUM   		= $rPO->WO_NUM;
										$WO_CODE   		= $rPO->WO_CODE;
										$OPNH_NOTE 		= $rPO->OPNH_NOTE;
										$OPNH_AMOUNT 	= $rPO->OPNH_AMOUNT;
										$OPNH_AMOUNTPPN	= $rPO->OPNH_AMOUNTPPN;
										$OPNH_AMOUNTPPH	= $rPO->OPNH_AMOUNTPPH;
										$OPNH_RETAMN	= $rPO->OPNH_RETAMN;

										$GTOT_OPNH 		= $GTOT_OPNH+$OPNH_AMOUNT;

										$OPNH_AMOUNTVW 	= number_format($OPNH_AMOUNT, $decFormat);

										// get SPLDESC
										if($SPLDESC == '')
										{
											$getSPL 	= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
											$resSPL 	= $this->db->query($getSPL);
											if($resSPL->num_rows() > 0)
											{
												foreach($resSPL->result() as $rSPL):
													$SPLDESC = $rSPL->SPLDESC;
												endforeach;
											}
										}

										$WO_DATEVW 	= "";
										$WO_AMOUNT 	= 0;
										$getWO 		= "SELECT WO_DATE, WO_VALUE FROM tbl_wo_header WHERE WO_NUM = '$WO_NUM' LIMIT 1";
										$resWO 		= $this->db->query($getWO);
										if($resWO->num_rows() > 0)
										{
											foreach($resWO->result() as $rWO):
												$WO_DATEVW 	= date('d/m/Y', strtotime($rWO->WO_DATE));
												$WO_AMOUNT 	= $rWO->WO_VALUE;
											endforeach;
										}
										if($WO_CODE2 == $WO_CODE)
										{
											$WO_DATEVW 		= "";
											$WO_AMOUNTVW 	= "";
										}
										else
										{
											$WO_AMOUNTVW 	= number_format($WO_AMOUNT, $decFormat);
											$GTOT_SPK 		= $GTOT_SPK+$WO_AMOUNT;
										}

										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 5px; vertical-align: top;"><?=$newNo?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
													<?php echo $OPNH_CODE; ?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
													<?php echo $OPNH_DATEV; ?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
													<?php echo "$WO_CODE : $WO_DATEVW<br>$OPNH_NOTE"; ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
													<?php echo $WO_AMOUNTVW; ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;border-right-width:2px; border-right-color:#000; vertical-align: top;">
													<?php echo $OPNH_AMOUNTVW; ?></td>
											</tr>
										<?php
										$WO_CODE2 	= $WO_CODE;
									endforeach;
									$REM_WOAMN 		= $GTOT_SPK-$GTOT_OPNH;
									?>
										<tr>
											<td colspan="4" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>TOTAL</b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_OPNH, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_SPK, $decFormat); ?></b></td>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>SISA SPK</b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($REM_WOAMN, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
										</tr>
									<?php
								}
								else
								{
									?>
										<tr>
											<td colspan="14" style="text-align:center;">--- none ---</td>
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