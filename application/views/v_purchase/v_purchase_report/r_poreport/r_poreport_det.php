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
	            width: 42cm;
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
			                  	<th width="10" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
			                  	<th width="50" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL. PO</th>
								<th width="100" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">NOMOR PO</th>
								<th width="200" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">PEMASOK</th>
								<th width="150" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">ITEM</th>
								<th width="150" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">Pekerjaan</th>
								<th width="50" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">Vol. PO</th>
								<th width="70" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000">Harga PO</th>
								<th width="100" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">Jumlah PO</th>
								<th width="100" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">Kode LPM</th>
								<th width="100" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">No. Gudang</th>
								<th width="50" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">Vol. LPM</th>
								<th width="50" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">Sisa <br>Vol. PO</th>
								<th width="50" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">Kode Voucher</th>
			              	</tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
								<td colspan="14" style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
								// $addQuery = "";
			               		// if($PRJCODE != 1)
								// {
								// 	$addQuery .= " AND A.PRJCODE = '$PRJCODE'";
								// }

								$addQuery = "";
								if($SPLCODE[0] != 1)
								{
									$joinQSPL 	= join("','", $SPLCODE);
									$addQuery 	.= " AND SPLCODE IN ('$joinQSPL')";
								}

								if($PO_NUM[0] != 1)
								{
									$joinQPONUM	= join("','", $PO_NUM);
									$addQuery 	.= " AND PO_NUM IN ('$joinQPONUM')";
								}

								$s_POH 		= "SELECT * FROM tbl_po_header WHERE PRJCODE = '$PRJCODE' $addQuery
												AND PO_STAT IN (3,6) AND PO_DATE BETWEEN '$Start_Date' AND '$End_Date'
												ORDER BY PO_NUM, SPLCODE ASC";
								$r_POH 		= $this->db->query($s_POH);
								
								$GTOTPO_VOL 	= 0;
								$GTOT_POCOST 	= 0;
								$GTOT_POPRICE	= 0;
								$GTOT_ITMQTY 	= 0;
								$GTOTREM_PO 	= 0;
								if($r_POH->num_rows() > 0)
								{
									foreach($r_POH->result() as $rw_POH):
										$PO_NUM 	= $rw_POH->PO_NUM;
										$PO_CODE 	= $rw_POH->PO_CODE;
										$PO_DATE   	= $rw_POH->PO_DATE;
										$PO_DATEV 	= date('d/m/Y', strtotime($PO_DATE));
										$PRJCODE   	= $rw_POH->PRJCODE;	
										$SPLCODE 	= $rw_POH->SPLCODE;
										$PO_TYPE 	= $rw_POH->PO_TYPE;
										$PO_CAT 	= $rw_POH->PO_CAT;
										$PO_DATE 	= $rw_POH->PO_DATE;
										$PRJCODE 	= $rw_POH->PRJCODE;
										$SPLCODE 	= $rw_POH->SPLCODE;
										$PR_NUM 	= $rw_POH->PR_NUM;
										$PR_CODE 	= $rw_POH->PR_CODE;
										$PO_CATEG 	= $rw_POH->PO_CATEG;
										$PO_PLANIR 	= $rw_POH->PO_PLANIR;
										$PO_NOTES 	= $rw_POH->PO_NOTES;
										$PO_STAT 	= $rw_POH->PO_STAT;
										
										$s_POD 		= "SELECT PO_ID, JOBCODEID, JOBPARENT, JOBPARDESC, PO_VOLM, PO_DESC_ID,
														PO_PRICE, PO_COST, PO_DESC, ITM_CODE, PO_CVOL, PO_CTOTAL
														FROM tbl_po_detail A WHERE PO_NUM = '$PO_NUM'
														ORDER BY PO_ID, JOBCODEID, ITM_CODE, PO_DESC_ID ASC";
										$r_POD 		= $this->db->query($s_POD);
										$JOBCODEID_B 	= "";
										$PO_NUM_B 		= "";
										$ITM_CODE_B 	= "";
										$PO_DESC_B 		= "";
										$newNo_B		= "";
										
										$TOTPO_VOL 		= 0;
										$TOT_POCOST 	= 0;
										$TOT_POPRICE 	= 0;
										$TOTREM_PO 		= 0;
										if($r_POD->num_rows() > 0)
										{
											$REM_DPVAL 		= 0;
											$GTOT_DPAM 		= 0;
											$GTOT_POTDPVAL	= 0;
											$GTOT_REMDPVAL 	= 0;
											$POT_TOTDP 		= 0;
											$REM_TOTDP 		= 0;
											$REM_PO 		= 0;
											$PO_CODEB 		= "";
											$TOT_ITMQTY 	= 0;
											$newNo 			= 0;
											foreach($r_POD->result() as $rw_POD):
												$newNo 			= $newNo + 1;
												$newNo_A 		= $newNo;
												$PO_ID 			= $rw_POD->PO_ID;
												$ITM_CODE 		= $rw_POD->ITM_CODE;
												$JOBCODEID   	= $rw_POD->JOBCODEID;
												$JOBPARENT 		= $rw_POD->JOBPARENT;
												$JOBPARDESC   	= $rw_POD->JOBPARDESC;	
												$PO_VOLM   		= $rw_POD->PO_VOLM;
												$PO_PRICE   	= $rw_POD->PO_PRICE;	
												$PO_COST   		= $rw_POD->PO_COST;
												$PO_DESC_ID 	= $rw_POD->PO_DESC_ID;
												$PO_DESC   		= $rw_POD->PO_DESC;
												$PO_CVOL   		= $rw_POD->PO_CVOL;
												$PO_CTOTAL   	= $rw_POD->PO_CTOTAL;
												// $IR_CODE   		= $rw_POD->IR_CODE;	
												// $IR_DATE   		= $rw_POD->IR_DATE;	
												// $SJ_NUM   		= $rw_POD->SJ_NUM;	
												// $ITM_QTY   		= $rw_POD->ITM_QTY;	
												// $TTK_CODE   	= $rw_POD->TTK_CODE;	
												// $INV_CODE   	= $rw_POD->INV_CODE;

												// $REM_PO 		= $PO_VOLM - $ITM_QTY;

												$JOBPARDESCV 	= "$JOBPARDESC ($JOBPARENT)";
												$PO_VOLMV 		= number_format($PO_VOLM, $decFormat);
												$PO_PRICEV 		= number_format($PO_PRICE, $decFormat);
												$PO_COSTV 		= number_format($PO_COST, $decFormat);

												$PO_NUM_A 		= $PO_NUM;
												$JOBCODEID_A 	= $JOBCODEID;
												$ITM_CODE_A 	= $ITM_CODE;
												$PO_DESC_A 		= $PO_DESC;

												$REM_PO 		= $PO_VOLM;

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

													$SPLDESCV 		= "$SPLDESC ($SPLCODE)";

												// GET ITM NAME
													$ITM_NAME = "";
													$getITM 	= "SELECT ITM_NAME FROM tbl_item_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE'";
													$resITM 	= $this->db->query($getITM);
													if($resITM->num_rows() > 0)
													{
														foreach($resITM->result() as $rITM):
															$ITM_NAME = $rITM->ITM_NAME;
														endforeach;
													}

													$ITM_NAMEV 	= "$ITM_NAME ($ITM_CODE)";

												// GET JOBDESC

												$TOTPO_VOL 		= $TOTPO_VOL + $PO_VOLM - $PO_CVOL;
												$TOT_POCOST 	= $TOT_POCOST + $PO_COST - $PO_CTOTAL;
												$TOT_POPRICE	= $TOT_POCOST / $TOTPO_VOL;

												// GET LPM
												/*$getLPM 	= "SELECT DISTINCT B.ITM_QTY AS ITM_QTY, B.JOBCODEID, B1.IR_CODE, B1.IR_DATE, B1.PO_NUM,
																C.TTK_CODE, D.INV_CODE, B.SJ_NUM
																FROM tbl_ir_detail B
																INNER JOIN tbl_ir_header B1 ON B1.IR_NUM = B.IR_NUM AND B1.PRJCODE = B.PRJCODE
																LEFT JOIN tbl_ttk_detail C ON C.TTK_REF1_NUM = B.IR_NUM AND C.PRJCODE = B.PRJCODE
																-- INNER JOIN tbl_ttk_header C1 ON C1.TTK_NUM = C.TTK_NUM AND C1.PRJCODE = C.PRJCODE
																LEFT JOIN tbl_pinv_detail D ON D.TTK_NUM = C.TTK_NUM AND D.PRJCODE = C.PRJCODE
																-- INNER JOIN tbl_pinv_header D1 ON D1.INV_NUM = D.INV_NUM AND D1.PRJCODE = D.PRJCODE
																WHERE B1.PO_NUM = '$PO_NUM' AND B.ITM_CODE = '$ITM_CODE' 
																AND B.JOBCODEID = '$JOBCODEID' AND B.POD_ID = '$PO_ID'
																AND B1.PRJCODE = '$PRJCODE' AND B1.IR_STAT NOT IN (5,9)
																ORDER BY B1.PO_NUM, B1.IR_NUM ASC";*/
												$getLPM 	= "SELECT SUM(B.ITM_QTY) AS ITM_QTY, B.JOBCODEID, B1.IR_CODE, B1.IR_DATE, B1.PO_NUM,
												B1.TTK_CODE, B1.INV_CODE, B.SJ_NUM
																FROM tbl_ir_detail B
																INNER JOIN tbl_ir_header B1 ON B1.IR_NUM = B.IR_NUM AND B1.PRJCODE = B.PRJCODE
																-- LEFT JOIN tbl_ttk_detail C ON C.TTK_REF1_NUM = B.IR_NUM AND C.PRJCODE = B.PRJCODE
																-- LEFT JOIN tbl_pinv_detail D ON D.TTK_NUM = C.TTK_NUM AND D.PRJCODE = C.PRJCODE
																WHERE B1.PO_NUM = '$PO_NUM' AND B.ITM_CODE = '$ITM_CODE' 
																AND B.JOBCODEID = '$JOBCODEID' AND B.POD_ID = '$PO_ID'
																AND B1.PRJCODE = '$PRJCODE' AND B1.IR_STAT NOT IN (5,9)
																GROUP BY B.IR_NUM, B.PO_NUM, B.POD_ID
																ORDER BY B1.PO_NUM, B1.IR_NUM ASC";
												$resLPM 	= $this->db->query($getLPM);
												$TTK_CODE 	= "";
												$INV_CODE 	= "";
												$SJ_NUM 	= "";
												if($resLPM->num_rows() > 0)
												{
													$newNoD = $newNo - 1;
													foreach($resLPM->result() as $rLPM):
														$newNoD 	= $newNoD + 1;
														$IR_CODE 	= $rLPM->IR_CODE;
														$JOBCODEID  = $rLPM->JOBCODEID;
														$ITM_QTY 	= $rLPM->ITM_QTY;
														$TTK_CODE 	= $rLPM->TTK_CODE;
														$INV_CODE 	= $rLPM->INV_CODE;
														$SJ_NUM 	= $rLPM->SJ_NUM;

														$REM_PO 	= $REM_PO - $ITM_QTY;
														// $newNoD 	= $newNo;
														// if($newNo_A == $newNo_B) 
														// {
														// 	$newNoD = "";
														// }

														// if($JOBCODEID_A == $JOBCODEID_B)
														// {
														// 	$JOBPARDESCV 	= "";
														// 	$PO_VOLMV 		= "";
														// 	$PO_VOLM 		= 0;
														// 	$PO_PRICEV 		= "";
														// 	$PO_COSTV 		= "";
														// 	$PO_COST 		= 0;
														// }

														// if($PO_NUM_A == $PO_NUM_B)
														// {
														// 	$PO_CODE	= "";
														// 	$PO_DATEV	= "";
														// 	$SPLDESCV 	= "";
														// }

														// if($ITM_CODE_A == $ITM_CODE_B || $PO_DESC_A == $PO_DESC_B)
														// {
														// 	$ITM_NAMEV 	= "";
														// 	$PO_DESC 	= "";
														// }

														$TOT_ITMQTY 	= $TOT_ITMQTY + $ITM_QTY;
														$TOTREM_PO 		= $TOTPO_VOL - $TOT_ITMQTY;

														?>
															<tr>
																<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 5px;"><?=$newNoD?></td>
																<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo $PO_DATEV; ?></td>
																<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo $PO_CODE; ?></td>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo "$SPLDESCV"; ?></td>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo "$ITM_NAMEV"; ?><div class="text-mute" style="font-style: italic;"><?php echo "$PO_DESC ($JOBCODEID)"; ?></div></td>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo "$JOBPARDESCV" ?></td>
																<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo $PO_VOLMV; ?></td>
																<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo $PO_PRICEV; ?></td>
																<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 5px;"><?php echo $PO_COSTV; ?></td>
																<td style="text-align:center;border-top-width:1px; border-top-color:#000;padding: 5px;"><?php echo "$IR_CODE"; ?></td>
																<td style="text-align:center;border-top-width:1px; border-top-color:#000;padding: 5px;"><?php echo "$SJ_NUM"; ?></td>
																<td style="text-align:right;border-top-width:1px; border-top-color:#000;padding: 5px;"><?php echo number_format($ITM_QTY, $decFormat); ?></td>
																<td style="text-align:right;border-top-width:1px; border-top-color:#000;padding: 5px;"><?php echo number_format($REM_PO, $decFormat); ?></td>
																<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 5px;border-right-width:2px; border-right-color:#000;"><?php echo $INV_CODE; ?></td>
															</tr>
														<?php

														// $newNo_B 		= $newNo;
														// $PO_NUM_B		= $rLPM->PO_NUM;
														// $JOBCODEID_B 	= $JOBCODEID;
														// $ITM_CODE_B 	= $ITM_CODE;
														// $PO_DESC_B 		= $PO_DESC;
													endforeach;
													$newNo 	= $newNoD;
												}
											endforeach;

											$GTOTPO_VOL 	= $GTOTPO_VOL + $TOTPO_VOL;
											$GTOT_POCOST 	= $GTOT_POCOST + $TOT_POCOST;
											$GTOT_POPRICE	= $GTOT_POCOST / $GTOTPO_VOL;

											$GTOT_ITMQTY 	=  $GTOT_ITMQTY + $TOT_ITMQTY;
											// $GTOTREM_PO 	=  $GTOTPO_VOL - $GTOT_ITMQTY;
											?>
												<tr>
													<td colspan="6" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>TOTAL</b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($TOTPO_VOL, $decFormat); ?></b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($TOT_POPRICE, $decFormat); ?></b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($TOT_POCOST, $decFormat); ?></b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($TOT_ITMQTY, $decFormat); ?></b></td>
													<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($TOTREM_PO, $decFormat); ?></b></td>
													<td style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
												</tr>
											<?php
										}
									endforeach;

									?>
										<tr>
											<td colspan="6" style="text-align:right;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><b>GRAND TOTAL</b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOTPO_VOL, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_POPRICE, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_POCOST, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;"><b><?php echo number_format($GTOT_ITMQTY, $decFormat); ?></b></td>
											<td style="text-align:right;border-bottom-width:2px; border-bottom-color:#000; padding: 2px;">&nbsp;</td>
											<td style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
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