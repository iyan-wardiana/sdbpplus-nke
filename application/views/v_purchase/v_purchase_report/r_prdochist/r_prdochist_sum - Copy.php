<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 10 November 2022
	* File Name		= r_prdochist_sum.php
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
	            padding-left: 0.2cm;
	            padding-right: 0.2cm;
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
                    <td><?php echo "Monitoring Pengadaan"; ?></td>
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
								<th colspan="5" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">SPP</th>
								<th colspan="5" style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">OP</th>
			                </tr>
			                <tr style="background:#CCCCCC">
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>NOMOR</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 1</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 2</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 3</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 4</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>NOMOR</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 1</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 2</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 3</th>
								<th style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000" nowrap>APPROVE 4</th>
			                </tr>
			               	<?php
								$getPR 		= "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_CREATER, A.PR_CREATED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName1
												FROM tbl_pr_header A INNER JOIN tbl_employee B ON A.PR_CREATER = B.Emp_ID
												WHERE A.PR_DATE BETWEEN '$Start_Date' AND '$End_Date' 
												AND A.PRJCODE = '$PRJCODE' $addQuery";
								$resPR 		= $this->db->query($getPR);
								if($resPR->num_rows() > 0)
								{
									$newNo 		= 0;
									$PR_NUMB 	= "";
									foreach($resPR->result() as $rPR):
										$newNo 			= $newNo + 1;
										$PR_NUM 		= $rPR->PR_NUM;
										$PR_CODE 		= $rPR->PR_CODE;	
										$PR_DATE 		= $rPR->PR_DATE;
										$PR_CREATED 	= $rPR->PR_CREATED;
										$PR_DATEV 		= date('d/m/y H:i:s', strtotime($PR_CREATED));
										$PR_CREATER 	= wordwrap($rPR->complName1, 20, "<br>", true);
										?>
											<tr>
												<td style="border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;">
													<?php if($PR_NUM != $PR_NUMB) { ?>
														<span class="text-muted" style="font-size: 10pt; font-style: italic;"><?=$PR_CODE?></span><br>
														<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$PR_DATEV?></span><br>
														<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$PR_CREATER?></span>
													<?php } ?>
												</td>
												<?php
													$getAPPH 	= "SELECT A.AH_APPROVER, A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName
																	FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE A.AH_CODE = '$PR_NUM'";
													$resAPPH 	= $this->db->query($getAPPH);
													$APPPRC		= 0;
													if($resAPPH->num_rows() > 0)
													{
														foreach($resAPPH->result() as $rAPPH):
															$APPPRC 	= $APPPRC + 1;
															$APPROVER 	= wordwrap($rAPPH->complName, 20, "<br>", true);
															$APPROVED 	= date('d/m/y H:i:s', strtotime($rAPPH->AH_APPROVED));
															?>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;" nowrap>
																	<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$APPROVED?></span><br>
																	<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$APPROVER?></span>
																</td>
															<?php
														endforeach;
														$remRow = 4 - $APPPRC;
														for($i=0; $i<$remRow; $i++)
														{
															?>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<?php
														}
													}
													else
													{
														?>
															<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
														<?php
													}
												?>
												<td style="border-top-width:1px; border-top-color:#000;border-left-width:2px; border-left-color:#000; padding: 2px;">
													<span class="text-muted" style="font-size: 10pt; font-style: italic;"><?=$PR_CODE?></span><br>
													<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$PR_DATEV?></span><br>
													<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$PR_CREATER?></span>
												</td>
												<?php
													$getAPPH 	= "SELECT A.AH_APPROVER, A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName
																	FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																	WHERE A.AH_CODE = '$PR_NUM'";
													$resAPPH 	= $this->db->query($getAPPH);
													$APPPRC		= 0;
													if($resAPPH->num_rows() > 0)
													{
														foreach($resAPPH->result() as $rAPPH):
															$APPPRC 	= $APPPRC + 1;
															$APPROVER 	= wordwrap($rAPPH->complName, 20, "<br>", true);
															$APPROVED 	= date('d/m/y H:i:s', strtotime($rAPPH->AH_APPROVED));
															?>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;" nowrap>
																	<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$APPROVED?></span><br>
																	<span class="text-muted pull-right" style="font-size: 9pt; font-style: italic;"><?=$APPROVER?></span>
																</td>
															<?php
														endforeach;
														$remRow = 4 - $APPPRC;
														for($i=0; $i<$remRow; $i++)
														{
															?>
																<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<?php
														}
													}
													else
													{
														?>
															<td style="text-align:left;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<td style="text-align:right;border-top-width:1px; border-top-color:#000; padding: 2px;">&nbsp;</td>
															<td style="text-align:center;border-top-width:1px; border-top-color:#000;border-right-width:2px; border-right-color:#000; padding: 2px;">&nbsp;</td>
														<?php
													}
												?>
											</tr>
										<?php
										$PR_NUMB 		= $PR_NUM;
									endforeach;
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