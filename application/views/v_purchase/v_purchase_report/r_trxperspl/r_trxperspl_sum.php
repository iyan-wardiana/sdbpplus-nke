<?php
/* 
 	* Author		= Dian Hermanto
 	* Create Date	= 26 Juli 2023
 	* File Name		= r_trxperspl_sum.php
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

$getSPL 	= $this->db->select("SPLDESC")->get_where("tbl_supplier", ["SPLCODE" => $SPLCODE])->row("SPLDESC");
$SPLDESC 	= "$getSPL ($SPLCODE)";
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
                    <td><?php echo "Lap. Transaksi Per Supplier"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Supplier</td>
                    <td width="10">:</td>
                    <td><?php echo "$SPLDESC"; ?></td>
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
			                  	<th width="15%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">KODE</th>
			                  	<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL.</th>
								<th width="50%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">DESKRIPSI</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">JUMLAH</th>
								<th width="10%" style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">LPM/OPN/VOC</th>
			                </tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
								<td colspan="6" style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
								$QryPRJ 	= "";
								$QryProj 	= "";
			               		if($PRJCODE != 1)
								{
									$QryPRJ 	.= " AND A.PRJCODE = '$PRJCODE'";
									$QryProj 	.= " AND B.Proj_Code = '$PRJCODE'";
								}

								$newNo 		= 0;
								$TOT_DOC	= 0;
								$TOT_DOC1 	= 0;
								// START : OP
									$s_DOC 		= "SELECT PO_NUM AS DOC_NUM, PO_CODE AS DOC_CODE, PO_DATE AS DOC_DATE, PO_NOTES AS DOC_NOTE,
														PO_TOTCOST AS DOC_VAL
													FROM tbl_po_header A 
														WHERE PO_DATE BETWEEN '$Start_Date' AND '$End_Date' 
													AND SPLCODE = '$SPLCODE' $QryPRJ ORDER BY DOC_DATE";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC):
										$newNo 		= $newNo + 1;
										$DOC_NUM 	= $rw_DOC->DOC_NUM;
										$DOC_CODE 	= $rw_DOC->DOC_CODE;
										$DOC_DATE 	= $rw_DOC->DOC_DATE;
										$DOC_DATEV 	= date('Y-m-d', strtotime($DOC_DATE));
										$DOC_NOTE 	= $rw_DOC->DOC_NOTE;
										$DOC_VAL 	= $rw_DOC->DOC_VAL;
										$TOT_DOC 	= $TOT_DOC+$DOC_VAL;

										$DOC_REM 	= $DOC_VAL;
										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_CODE?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_DATEV?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_NOTE?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DOC_VAL, 2); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
											</tr>
										<?php
										// START : LPM
											$s_DOC1 	= "SELECT IR_NUM AS DOC1_NUM, IR_CODE AS DOC1_CODE, IR_DATE AS DOC1_DATE,
																IR_NOTE AS DOC1_NOTE, IR_AMOUNT AS DOC1_VAL
															FROM tbl_ir_header A WHERE PO_NUM = '$DOC_NUM'
															ORDER BY DOC1_DATE";
											$r_DOC1 	= $this->db->query($s_DOC1)->result();
											foreach($r_DOC1 as $rw_DOC1):
												$newNo 		= $newNo + 1;
												$DOC1_NUM 	= $rw_DOC1->DOC1_NUM;
												$DOC1_CODE 	= $rw_DOC1->DOC1_CODE;
												$DOC1_DATE 	= $rw_DOC1->DOC1_DATE;
												$DOC1_DATEV = date('Y-m-d', strtotime($DOC1_DATE));
												$DOC1_NOTE 	= $rw_DOC1->DOC1_NOTE;
												$DOC1_VAL 	= $rw_DOC1->DOC1_VAL;
												$TOT_DOC1 	= $TOT_DOC1+$DOC1_VAL;

												$DOC_REM 	= $DOC_REM - $DOC1_VAL;
												?>
													<tr>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">
															<?=$DOC1_CODE?></td>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">
															<?=$DOC1_DATEV?></td>
														<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
															<?=$DOC1_NOTE?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DOC1_VAL, 2); ?></td>
													</tr>
												<?php
											endforeach;
										// END : LPM
									endforeach;
								// END : OP

								//$TOT_DOC	= 0;
								//$TOT_DOC1 = 0;
								// START : SPK
									$s_DOC 		= "SELECT WO_NUM AS DOC_NUM, WO_CODE AS DOC_CODE, WO_DATE AS DOC_DATE, WO_NOTE AS DOC_NOTE,
														WO_VALUE AS DOC_VAL
													FROM tbl_wo_header A 
														WHERE WO_DATE BETWEEN '$Start_Date' AND '$End_Date' 
													AND SPLCODE = '$SPLCODE' ORDER BY DOC_DATE";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC):
										$newNo 		= $newNo + 1;
										$DOC_NUM 	= $rw_DOC->DOC_NUM;
										$DOC_CODE 	= $rw_DOC->DOC_CODE;
										$DOC_DATE 	= $rw_DOC->DOC_DATE;
										$DOC_DATEV 	= date('Y-m-d', strtotime($DOC_DATE));
										$DOC_NOTE 	= $rw_DOC->DOC_NOTE;
										$DOC_VAL 	= $rw_DOC->DOC_VAL;
										$TOT_DOC 	= $TOT_DOC+$DOC_VAL;

										$DOC_REM 	= $DOC_VAL;
										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_CODE?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_DATEV?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_NOTE?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DOC_VAL, 2); ?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
											</tr>
										<?php
										// START : OPNAME
											$s_DOC1 	= "SELECT OPNH_NUM AS DOC1_NUM, OPNH_CODE AS DOC1_CODE, OPNH_DATE AS DOC1_DATE,
																OPNH_NOTE AS DOC1_NOTE, OPNH_AMOUNT AS DOC1_VAL
															FROM tbl_opn_header A WHERE WO_NUM = '$DOC_NUM'
															ORDER BY DOC1_DATE";
											$r_DOC1 	= $this->db->query($s_DOC1)->result();
											foreach($r_DOC1 as $rw_DOC1):
												$DOC1_NUM 	= $rw_DOC1->DOC1_NUM;
												$DOC1_CODE 	= $rw_DOC1->DOC1_CODE;
												$DOC1_DATE 	= $rw_DOC1->DOC1_DATE;
												$DOC1_DATEV = date('Y-m-d', strtotime($DOC1_DATE));
												$DOC1_NOTE 	= $rw_DOC1->DOC1_NOTE;
												$DOC1_VAL 	= $rw_DOC1->DOC1_VAL;
												$TOT_DOC1 	= $TOT_DOC1+$DOC1_VAL;

												$DOC_REM 	= $DOC_REM - $DOC1_VAL;
												?>
													<tr>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">
															<?=$DOC1_CODE?></td>
														<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">
															<?=$DOC1_DATEV?></td>
														<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
															<?=$DOC1_NOTE?></td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
														<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DOC1_VAL, 2); ?></td>
													</tr>
												<?php
											endforeach;
										// END : OPNAME
									endforeach;
								// END : SPK

								//$TOT_DOC	= 0;
								//$TOT_DOC1 = 0;
								// START : VCASH
									$s_DOC 		= "SELECT B.JournalH_Code AS DOC_NUM, B.Manual_No AS DOC_CODE, B.JournalH_Date AS DOC_DATE,
														B.JournalH_Desc AS DOC_NOTE, SUM(A.JournalD_Debet) AS DOC_VAL 
													FROM tbl_journaldetail_vcash A INNER JOIN tbl_journalheader_vcash B ON A.JournalH_Code = B.JournalH_Code
													WHERE B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' 
													AND B.SPLCODE = '$SPLCODE' $QryProj GROUP BY B.JournalH_Code ORDER BY DOC_DATE";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC):
										$newNo 		= $newNo + 1;
										$DOC_NUM 	= $rw_DOC->DOC_NUM;
										$DOC_CODE 	= $rw_DOC->DOC_CODE;
										$DOC_DATE 	= $rw_DOC->DOC_DATE;
										$DOC_DATEV 	= date('Y-m-d', strtotime($DOC_DATE));
										$DOC_NOTE 	= $rw_DOC->DOC_NOTE;
										$DOC_VAL 	= $rw_DOC->DOC_VAL;
										$TOT_DOC 	= $TOT_DOC+$DOC_VAL;

										$DOC_REM 	= $DOC_VAL;
										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_CODE?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_DATEV?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_NOTE?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DOC_VAL, 2); ?></td>
											</tr>
										<?php
									endforeach;
								// END : VCASH

								// $TOT_DOC	= 0;
								// $TOT_DOC1 = 0;
								// START : VLK
									$s_DOC 		= "SELECT B.JournalH_Code AS DOC_NUM, B.Manual_No AS DOC_CODE, B.JournalH_Date AS DOC_DATE,
														B.JournalH_Desc AS DOC_NOTE, SUM(A.JournalD_Debet) AS DOC_VAL 
													FROM tbl_journaldetail_cprj A INNER JOIN tbl_journalheader_vcash B ON A.JournalH_Code = B.JournalH_Code
													WHERE B.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' 
													AND B.SPLCODE = '$SPLCODE' $QryProj GROUP BY B.JournalH_Code ORDER BY DOC_DATE";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC):
										$newNo 		= $newNo + 1;
										$DOC_NUM 	= $rw_DOC->DOC_NUM;
										$DOC_CODE 	= $rw_DOC->DOC_CODE;
										$DOC_DATE 	= $rw_DOC->DOC_DATE;
										$DOC_DATEV 	= date('Y-m-d', strtotime($DOC_DATE));
										$DOC_NOTE 	= $rw_DOC->DOC_NOTE;
										$DOC_VAL 	= $rw_DOC->DOC_VAL;
										$TOT_DOC 	= $TOT_DOC+$DOC_VAL;

										$DOC_REM 	= $DOC_VAL;
										?>
											<tr>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;"><?=$newNo?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_CODE?></td>
												<td style="text-align:center;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_DATEV?></td>
												<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">
													<?=$DOC_NOTE?></td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
												<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;"><?php echo number_format($DOC_VAL, 2); ?></td>
											</tr>
										<?php
									endforeach;
								// END : VLK

								if($newNo == 0)
								{
									?>
										<tr>
											<td colspan="8" style="text-align:center;">--- none ---</td>
										</tr>
									<?php
								}
								else
								{
									?>
										<tr>
											<td colspan="4">&nbsp;</td>
											<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px; font-weight: bold;"><?php echo number_format($TOT_DOC, 2); ?></td>
											<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px; font-weight: bold;"><?php echo number_format($TOT_DOC1, 2); ?></td>
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