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
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";


$End_Date = date('Y-m-d',strtotime($End_Date));
$EndDate = date('d M Y',strtotime($End_Date));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk  = $rowcss->cssjs_lnk;
              ?>
                  <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
              <?php
          endforeach;

          $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
          $rescss = $this->db->query($sqlcss)->result();
          foreach($rescss as $rowcss) :
              $cssjs_lnk1  = $rowcss->cssjs_lnk;
              ?>
                  <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
              <?php
          endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

  	<style>
    	body
    	{
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		*{
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
	        width: 29.7cm;
	        min-height: 21cm;
	        padding: 0.5cm;
	        margin: 0.5cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .subpage {
	        padding: 1cm;
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

	<body style="overflow:auto">
		<div class="page">
			<section class="content">
			    <table width="100%" border="0" style="size:auto">
				    <tr>
				        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
				        <td width="58%" class="style2">&nbsp;</td>
				        <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
				    </tr>
				    <tr>
				        <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:180px; max-height:180px" ></td>
				        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:26px"><?php echo $comp_name; ?><br>
				          </td>
				  	</tr>
				    <tr>
				      	<td colspan="3"style="text-align:center; font-weight:bold;">Buku Besar - Rinci</td>
				    </tr>
				    <tr>
				      	<td colspan="3" class="style2" style="text-align:center; font-weight:bold;"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">Per Tgl. <?php echo $EndDate; ?></span></td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2">
				            <table width="100%" border="1" rules="all">
				                <tr style="background:#CCCCCC">
				                  <td width="24%" nowrap style="text-align:center; font-weight:bold">Tanggal</td>
				                  <td width="13%" nowrap style="text-align:center; font-weight:bold">Sumber</td>
				                  <td width="7%" nowrap style="text-align:center; font-weight:bold">No. Sumber</td>
				                  <td width="8%" nowrap style="text-align:center; font-weight:bold">Keterangan</td>
				                  <td width="9%" nowrap style="text-align:center; font-weight:bold">Debit</td>
				                  <td width="7%" nowrap style="text-align:center; font-weight:bold">Kredit</td>
				                  <td width="7%" nowrap style="text-align:center; font-weight:bold">Saldo</td>
				              </tr>
				                <?php
									function hitungHari($awal,$akhir)
									{
										$tglAwal = strtotime($awal);
										$tglAkhir = strtotime($akhir);
										$jeda = $tglAkhir - $tglAwal;
										return floor($jeda/(60*60*24));
									}
									
									if($CFType == 1) // 1. Sumamry
									{
										$therow		= 0;
										$sql0 		= "tbl_pinv_header 
														WHERE PRJCODE IN ($COLREFPRJ)
															AND INV_DATE <= '$End_Date' AND SPLCODE IN ($COLREFSPL)
															AND INV_PAYSTAT IN ('NP', 'HP') AND INV_STAT = 3";
										$sql1 		= "SELECT SPLCODE, INV_DUEDATE, SUM(INV_AMOUNT) AS TOTAMOUNT,
															SUM(INV_AMOUNT_PAID) AS TOTPAID, SUM(INV_LISTTAXVAL) AS INV_TAXVAL
														FROM tbl_pinv_header 
														WHERE PRJCODE IN ($COLREFPRJ)
															AND INV_DATE <= '$End_Date' AND SPLCODE IN ($COLREFSPL)
															AND INV_PAYSTAT IN ('NP', 'HP') AND INV_STAT = 3
															GROUP BY SPLCODE, INV_DUEDATE";
															
										$res0 			= $this->db->count_all($sql0);
										$res1 			= $this->db->query($sql1)->result();
										
										$SPLCODE		= '';
										$INV_DUEDATE	= '';
										$SPLCODE 		= '';
										$TOTAMOUNT		= 0;
										$TOTPAID		= 0;
										$TOTAM_30		= 0;
										$TOTAM_60		= 0;
										$TOTAM_90		= 0;
										$TOTAM_120		= 0;
										$TOTAM_121		= 0;
										
										if($res0 > 0)
										{
											foreach($res1 as $row1) :
												$therow			= $therow + 1;
												$SPLCODE 		= $row1->SPLCODE;
												$INV_DUEDATE 	= $row1->INV_DUEDATE;
												$INV_AMOUNT1	= $row1->TOTAMOUNT;
												$INV_TAXVAL		= $row1->INV_TAXVAL;
												$INV_AMOUNT		= $INV_AMOUNT1 + $INV_TAXVAL;
												$INV_AMOUNT_P	= $row1->TOTPAID;
												$INV_AMOUNT_REM	= $INV_AMOUNT - $INV_AMOUNT_P;
												
												$dateNow		= date('Y-m-d');
												$start_date 	= new DateTime("$dateNow");
												$end_date		= new DateTime("$INV_DUEDATE");
												$interval 		= $start_date->diff($end_date);
												$totDAYS		= $interval->days;
												
												if($totDAYS <= 30)
												{
													$INV_AM_30	= $INV_AMOUNT_REM;
													$INV_AM_60	= 0;
													$INV_AM_90	= 0;
													$INV_AM_120	= 0;
													$INV_AM_121	= 0;
													$TOTAM_30	= $TOTAM_30 + $INV_AM_30;
												}
												elseif($totDAYS <= 60)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= $INV_AMOUNT_REM;
													$INV_AM_90	= 0;
													$INV_AM_120	= 0;
													$INV_AM_121	= 0;
													$TOTAM_60	= $TOTAM_60 + $INV_AM_60;
												}
												elseif($totDAYS <= 90)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= 0;
													$INV_AM_90	= $INV_AMOUNT_REM;
													$INV_AM_120	= 0;
													$INV_AM_121	= 0;
													$TOTAM_90	= $TOTAM_90 + $INV_AM_90;
												}
												elseif($totDAYS <= 120)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= 0;
													$INV_AM_90	= 0;
													$INV_AM_120	= $INV_AMOUNT_REM;
													$INV_AM_121	= 0;
													$TOTAM_120	= $TOTAM_120 + $INV_AM_120;
												}
												elseif($totDAYS > 120)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= 0;
													$INV_AM_90	= 0;
													$INV_AM_120	= 0;
													$INV_AM_121	= $INV_AMOUNT_REM;
													$TOTAM_121	= $TOTAM_121 + $INV_AM_121;
												}
												$SPLDESC	= '';
												$sql2 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' ";
												$res2 		= $this->db->query($sql2)->result();
												foreach($res2 as $row2) :
													$SPLDESC 	= $row2->SPLDESC;
												endforeach;
												
												//$PO_NUM	= $row1->PO_NUM;
												$PO_CODE	= '-';
												/*$sql3 		= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' ";
												$res3 		= $this->db->query($sql3)->result();
												foreach($res3 as $row3) :
													$PO_CODE 	= $row3->PO_CODE;
												endforeach;*/
												?>
												<tr>
													<td nowrap style="text-align:left;">
														<?php echo $SPLDESC; ?>
													</td>
													<td nowrap style="text-align:center;">
														<?php echo $PO_CODE; ?>
				                                    </td>
													<td nowrap style="text-align:center;">
														<?php //echo $INV_DATE; ?>
													</td>
													<td nowrap style="text-align:center;">
														<?php echo $INV_DUEDATE; ?>
				                                    </td>
													<td style="text-align:right;" nowrap>
														<?php echo number_format($INV_AMOUNT_REM, 2); ?>
				                                    </td>
													<td style="text-align:right;" nowrap>
														<?php echo number_format($INV_AM_30, 2); ?>
				                                    </td>
													<td nowrap style="text-align:right">
														<?php echo number_format($INV_AM_60, 2); ?>
													</td>
												</tr>
												<?php
											endforeach;
										}
										else
										{
											?>
												<tr>
													<td colspan="7" nowrap style="text-align:center; font-style:italic">&nbsp;</td>
												</tr>
											<?php
										}
										?>
											<tr>
												<td colspan="4" nowrap style="text-align:Right; font-style:italic; font-weight:bold">Total</td>
				                                <td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOTAM_30, 2); ?></td>
												<td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOTAM_30, 2); ?></td>
												<td style="text-align:right; font-weight:bold" nowrap><?php echo number_format($TOTAM_60, 2); ?></td>
											</tr>
										<?php
									}
									elseif($CFType == 2) // 2. Detail
									{
										$therow		= 0;

										$sql0 		= "tbl_pinv_header 
														WHERE PRJCODE IN ($COLREFPRJ)
															AND INV_DATE <= '$End_Date' AND SPLCODE IN ($COLREFSPL)
															-- AND INV_PAYSTAT IN ('NP', 'HP')";
										$sql1 		= "SELECT * FROM tbl_pinv_header 
														WHERE PRJCODE IN ($COLREFPRJ)
															AND INV_DATE <= '$End_Date' AND SPLCODE IN ($COLREFSPL)
															-- AND INV_PAYSTAT IN ('NP', 'HP')
															GROUP BY SPLCODE, INV_NUM";
															
										$res0 			= $this->db->count_all($sql0);
										$res1 			= $this->db->query($sql1)->result();
										
										$INV_NUM		= '';
										$INV_CODE		= '';
										$INV_DATE 		= '';
										$INV_NUM		= '';
										$SPLCODE 		= '';
										$JOBCODE 		= '';
										$INV_NOTES 		= 0;
										$INV_NOTES1		= '';
										$INV_STAT		= 0;
										$INV_TOTCOST	= 0;
										$JOBCODE		= '';
										$TOTAM_30		= 0;
										$TOTAM_60		= 0;
										$TOTAM_90		= 0;
										$TOTAM_120		= 0;
										$TOTAM_121		= 0;
										
										if($res0 > 0)
										{
											foreach($res1 as $row1) :
												$therow			= $therow + 1;
												$INV_NUM 		= $row1->INV_NUM;
												$INV_CODE 		= $row1->INV_CODE;
												$INV_DATE 		= $row1->INV_DATE;
												$INV_DUEDATE 	= $row1->INV_DUEDATE;
												$IR_NUM 		= $row1->IR_NUM;
												$PRJCODE 		= $row1->PRJCODE;
												$SPLCODE 		= $row1->SPLCODE;
												$DP_AMOUNT 		= $row1->DP_AMOUNT;
												$INV_AMOUNT		= $row1->INV_AMOUNT;
												$INV_AMOUNT_P	= $row1->INV_AMOUNT_PAID;
												$INV_AMOUNT_REM	= $INV_AMOUNT - $INV_AMOUNT_P;
												
												$dateNow		= date('Y-m-d');
												$start_date 	= new DateTime("$dateNow");
												$end_date		= new DateTime("$INV_DUEDATE");
												$interval 		= $start_date->diff($end_date);
												$totDAYS		= $interval->days;
												
												if($totDAYS <= 30)
												{
													$INV_AM_30	= $INV_AMOUNT_REM;
													$INV_AM_60	= 0;
													$INV_AM_90	= 0;
													$INV_AM_120	= 0;
													$INV_AM_121	= 0;
													$TOTAM_30	= $TOTAM_30 + $INV_AM_30;
												}
												elseif($totDAYS <= 60)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= $INV_AMOUNT_REM;
													$INV_AM_90	= 0;
													$INV_AM_120	= 0;
													$INV_AM_121	= 0;
													$TOTAM_60	= $TOTAM_60 + $INV_AM_60;
												}
												elseif($totDAYS <= 90)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= 0;
													$INV_AM_90	= $INV_AMOUNT_REM;
													$INV_AM_120	= 0;
													$INV_AM_121	= 0;
													$TOTAM_90	= $TOTAM_90 + $INV_AM_90;
												}
												elseif($totDAYS <= 120)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= 0;
													$INV_AM_90	= 0;
													$INV_AM_120	= $INV_AMOUNT_REM;
													$INV_AM_121	= 0;
													$TOTAM_120	= $TOTAM_120 + $INV_AM_120;
												}
												elseif($totDAYS > 120)
												{
													$INV_AM_30	= 0;
													$INV_AM_60	= 0;
													$INV_AM_90	= 0;
													$INV_AM_120	= 0;
													$INV_AM_121	= $INV_AMOUNT_REM;
													$TOTAM_121	= $TOTAM_121 + $INV_AM_121;
												}
												$SPLDESC	= '';
												$sql2 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' ";
												$res2 		= $this->db->query($sql2)->result();
												foreach($res2 as $row2) :
													$SPLDESC 	= $row2->SPLDESC;
												endforeach;
												
												$PO_NUM		= $row1->PO_NUM;
												$PO_CODE	= '';
												$sql3 		= "SELECT PO_CODE FROM tbl_po_header WHERE PO_NUM = '$PO_NUM' ";
												$res3 		= $this->db->query($sql3)->result();
												foreach($res3 as $row3) :
													$PO_CODE 	= $row3->PO_CODE;
												endforeach;
												?>
												<tr>
													<td nowrap style="text-align:left;">
														<?php echo $SPLDESC; ?>
													</td>
													<td nowrap style="text-align:center;">
														<?php echo $PO_CODE; ?>
				                                    </td>
													<td nowrap style="text-align:center;">
														<?php echo $INV_DATE; ?>
													</td>
													<td nowrap style="text-align:center;">
														<?php echo $INV_DUEDATE; ?>
				                                    </td>
													<td style="text-align:right;" nowrap>
														<?php echo number_format($INV_AMOUNT_REM, 2); ?>
				                                    </td>
													<td style="text-align:right;" nowrap>
														<?php echo number_format($INV_AM_30, 2); ?>
				                                    </td>
													<td nowrap style="text-align:right">
														<?php echo number_format($INV_AM_60, 2); ?>
													</td>
												</tr>
												<?php
											endforeach;
										}
										else
										{
											?>
												<tr>
													<td colspan="7" nowrap style="text-align:center; font-style:italic">&nbsp;</td>
												</tr>
											<?php
										}
										?>
											<tr>
												<td colspan="4" nowrap style="text-align:right; font-style:italic; font-weight:bold">Total</td>
				                                <td style="text-align:right" nowrap><?php echo number_format($TOTAM_30, 2); ?></td>
												<td style="text-align:right" nowrap><?php echo number_format($TOTAM_30, 2); ?></td>
												<td style="text-align:right" nowrap><?php echo number_format($TOTAM_60, 2); ?></td>
											</tr>
										<?php
									}
				                ?>
				            </table>
				      <span style="text-align:right; display:none"><?php echo number_format($TOTAM_120, 2); ?></span>	    </td>
				    </tr>
				</table>
			</section>
		</div>
	</body>
</html>