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
if($PRJCODE == 0) 
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
                    <td width="10%" nowrap>Nama Laporan</td>
                    <td width="2%">:</td>
                    <td width="40%" nowrap><?php echo "$mnName"; ?></td>
                    <td width="48%" rowspan="4">
			            <table width="100%" border="1" style="font-size: 10px; font-weight: bold;" rules="all">
						    <tr>
						    	<td style="text-align: center;" colspan="2">Peralatan</td>
						    	<td style="text-align: center;" colspan="2">Operasi</td>
						    	<td style="text-align: center;" colspan="2">Accounting</td>
						    </tr>
						    <tr>
						    	<td style="text-align: center;" width="150">Ka. Divisi<br><br><br><br></td>
						    	<td style="text-align: center;" width="100" nowrap>Ka. Departemen<br><br><br><br></td>
						    	<td style="text-align: center;" width="150">Ka. Divisi<br><br><br><br></td>
						    	<td style="text-align: center;" width="100" nowrap>Ka. Departemen<br><br><br><br></td>
						    	<td style="text-align: center;" width="150">Ka. Divisi<br><br><br><br></td>
						    	<td style="text-align: center;" width="100" nowrap>Ka. Departemen<br><br><br><br></td>
						    </tr>
						</table>
					</td>
                </tr>
                <tr>
                    <td>Nama Proyek</td>
                    <td>:</td>
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>:</td>
                    <td><?php echo $datePeriod; ?></td>
                </tr>
                <tr>
                    <td>Tgl. Cetak</td>
                    <td>:</td>
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
			                  	<th width="2%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000" rowspan="2">NO.</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000" rowspan="2" nowrap>NO. SPK</th>
			                  	<th width="48%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;" rowspan="2" nowrap>DESKRIPSI SPK</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;" rowspan="2">NILAI SPK</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000" rowspan="2">OPNAME SEBELUMNYA</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000" colspan="4">OPNAME PERIODE INI</th>
			              	</tr>
			                <tr style="background:#CCCCCC">
								<th width="10%" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">NO. OPNAME</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TANGGAL</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">PERIODE</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000" nowrap>NILAI OPNAME</th>
			              	</tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
								<td colspan="13" style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
								$addQuery = "";
								if($SPLCODE[0] != 1)
								{
									$joinQSPL 	= join("','", $SPLCODE);
									$addQuery 	= "AND A.SPLCODE IN ('$joinQSPL')";
								}

								$addQRYPRJ 	= "";
								if($PRJCODE != '0')
									$addQRYPRJ	= " AND A.PRJCODE = '$PRJCODE'";

								$addQRYWO 	= "";
								if($WO_NUM != '0')
									$addQRYWO	= " AND A.WO_NUM = '$WO_NUM'";
                                
								/*$getPO 		= "SELECT OPNH_NUM, OPNH_CODE, OPNH_DATE, OPNH_DATESP, OPNH_DATEEP, SPLCODE, SPLDESC, WO_NUM, WO_CODE, OPNH_NOTE,
													OPNH_AMOUNT, OPNH_AMOUNTPPN, OPNH_AMOUNTPPH, OPNH_RETAMN
												FROM tbl_opn_header
												WHERE OPNH_STAT NOT IN (5,9) AND OPNH_TYPE = 0 AND WO_CATEG = 'T'
													AND (OPNH_DATE BETWEEN '$Start_Date' AND '$End_Date') $addQuery $addQRYPRJ $addQRYWO
												ORDER BY WO_CODE ASC";*/
								$getWO 		= "SELECT DISTINCT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.WO_VALUE, A.WO_NOTE, A.SPLCODE
												FROM tbl_wo_header A INNER JOIN tbl_opn_header B ON A.WO_NUM = B.WO_NUM
												WHERE A.WO_STAT NOT IN (5,9) AND A.WO_CATEG = 'T' $addQuery $addQRYPRJ $addQRYWO
												ORDER BY WO_CODE ASC";
								$resWO 		= $this->db->query($getWO);
								$JOBCODEID_B 	= "";
								$PO_NUM_B 		= "";
								$ITM_CODE_B 	= "";
								$PO_DESC_B 		= "";
								$newNo_B		= "";
								if($resWO->num_rows() > 0)
								{
									$newNo 			= 0;
									$WO_CODE2 		= "";
									$GTOT_OPNHBEF	= 0;
									$GTOT_OPNHCUR 	= 0;
									$GTOT_SPK		= 0;
									foreach($resWO->result() as $rWO):
										$newNo 			= $newNo + 1;
										$WO_NUM   		= $rWO->WO_NUM;
										$WO_CODE   		= $rWO->WO_CODE;
										$WO_DATEVW 		= date('d/m/Y', strtotime($rWO->WO_DATE));
										$WO_VALUE   	= $rWO->WO_VALUE;
										$WO_NOTE 		= $rWO->WO_NOTE;
										$SPLCODE 		= $rWO->SPLCODE;

										$getSPL 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
										$resSPL 		= $this->db->query($getSPL);
										if($resSPL->num_rows() > 0)
										{
											foreach($resSPL->result() as $rSPL):
												$SPLDESC = $rSPL->SPLDESC;
											endforeach;
										}

										$TOT_OPNBEF 	= 0;
										$s_OPNBEF 		= "SELECT IFNULL(SUM(A.OPND_ITMTOTAL),0) AS OPNH_AMOUNT FROM tbl_opn_detail A
																INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM AND B.OPNH_TYPE = 0 AND B.WO_CATEG = 'T'
															WHERE A.WO_NUM = '$WO_NUM' AND A.OPNH_STAT NOT IN (5,9)
								 								AND A.OPNH_DATE < '$Start_Date'";
										$r_OPNBEF 		= $this->db->query($s_OPNBEF)->result();
										foreach($r_OPNBEF as $rw_OPNBEF):
											$TOT_OPNBEF = $rw_OPNBEF->OPNH_AMOUNT;
										endforeach;
										$GTOT_OPNHBEF 	= $GTOT_OPNHBEF+$TOT_OPNBEF;

										$OPNH_CODE 		= "";
										$OPNH_DATEV 	= "";
										$OPNH_DATEPERV 	= "";
										$OPNH_AMOUNT 	= 0;
										$s_OPNCUR 		= "SELECT OPNH_NUM, OPNH_CODE, OPNH_DATE, OPNH_DATESP, OPNH_DATEEP, OPNH_AMOUNT, OPNH_RETAMN
															FROM tbl_opn_header
															WHERE WO_NUM = '$WO_NUM' AND OPNH_STAT NOT IN (5,9)
								 								AND OPNH_DATE BETWEEN '$Start_Date' AND '$End_Date'";
										$r_OPNCUR 		= $this->db->query($s_OPNCUR);
										if($r_OPNCUR->num_rows() > 0)
										{
											foreach($r_OPNCUR->result() as $rw_OPNCUR):
												$OPNH_NUM 		= $rw_OPNCUR->OPNH_NUM;
												$OPNH_CODE 		= $rw_OPNCUR->OPNH_CODE;
												$OPNH_DATE   	= $rw_OPNCUR->OPNH_DATE;
												$OPNH_DATEV 	= date('d/m/Y', strtotime($OPNH_DATE));
												$OPNH_DATESP   	= $rw_OPNCUR->OPNH_DATESP;
												$OPNH_DATESPV 	= date('d/m/Y', strtotime($OPNH_DATESP));
												$OPNH_DATEEP   	= $rw_OPNCUR->OPNH_DATEEP;
												$OPNH_DATEEPV 	= date('d/m/Y', strtotime($OPNH_DATEEP));
												$OPNH_AMOUNT 	= $rw_OPNCUR->OPNH_AMOUNT;
												
												$GTOT_OPNHCUR 	= $GTOT_OPNHCUR+$OPNH_AMOUNT;

												?>
													<tr>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 5px; vertical-align: top;"><?=$newNo?></td>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
															<?php echo $WO_CODE; ?></td>
														<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
															<?php echo $WO_NOTE; ?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
															<?php echo number_format($WO_VALUE, 2); ?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;" nowrap>
															<?php echo number_format($TOT_OPNBEF, 2); ?></td>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
															<?php echo $OPNH_CODE; ?></td>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
															<?php echo $OPNH_DATEV; ?></td>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;" nowrap>
															<?php echo "$OPNH_DATESPV - $OPNH_DATEEPV"; ?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;border-right-width:2px; border-right-color:#000; vertical-align: top;">
															<?php echo number_format($OPNH_AMOUNT, 2); ?></td>
													</tr>
												<?php
											endforeach;
										}
										else
										{

											?>
												<tr>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 5px; vertical-align: top;"><?=$newNo?></td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
														<?php echo $WO_CODE; ?></td>
													<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
														<?php echo $WO_NOTE; ?></td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">
														<?php echo number_format($WO_VALUE, 2); ?></td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;" nowrap>
														<?php echo number_format($TOT_OPNBEF, 2); ?></td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">&nbsp;</td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">&nbsp;</td>
													<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px; vertical-align: top;">&nbsp;</td>
													<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;border-right-width:2px; border-right-color:#000; vertical-align: top;">
														<?php echo number_format(0, 2); ?></td>
												</tr>
											<?php
										}

										if($WO_CODE2 == $WO_CODE)
										{
											$WO_DATEVW 		= "";
											$WO_VALUEVW 	= "";
										}
										else
										{
											$WO_VALUEVW 	= number_format($WO_VALUE, $decFormat);
											$GTOT_SPK 		= $GTOT_SPK+$WO_VALUE;
										}
										$WO_CODE2 	= $WO_CODE;
									endforeach;
									$REM_WOAMN 		= $GTOT_SPK-$GTOT_OPNHBEF-$GTOT_OPNHCUR;
									?>
										<tr>
											<td colspan="3" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>TOTAL</b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_SPK, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_OPNHBEF, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_OPNHCUR, $decFormat); ?></b></td>
										</tr>
										<tr>
											<td colspan="3" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>SISA SPK</b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($REM_WOAMN, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
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