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

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";

date_default_timezone_set("Asia/Jakarta");

$PRJNAME	= '';
/*if($VMonth == 'All')
{
	$PRJNAME	= "All";
}
else
{*/
	$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE IN ($PRJCODECOL)";
	$resPRJ		= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $PRJNM) :
		$PRJNAME = $PRJNM->PRJNAME;
	endforeach;
//}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <title><?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="overflow:auto">
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="19%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>
            </div>            </td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?><br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span></td>
  </tr>
    <tr>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        <td colspan="2" class="style2" style="text-align:center; font-style:italic">
        	Periode : <?php echo date('d M Y', strtotime($StartDate)); ?> - <?php echo date('d M Y', strtotime($EndDate)); ?>
        </td>
      </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
        	<table width="100%" style="font-weight:bold">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"><?php// echo $DOCTYPE1; ?></td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo str_replace("'","", $PRJCODECOL); ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">TANGGAL CETAK</td>
                  <td>:</td>
                  <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </td>
      </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NO.</td>
                  <td width="10%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KODE</td>
                  <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                  <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold; display:none">PEKERJAAN</td>
                  <td width="29%" rowspan="2" nowrap style="text-align:center; font-weight:bold">ITEM</td>
                  <td colspan="4" nowrap style="text-align:center; font-weight:bold">VOLUME</td>
                  <td width="6%" rowspan="2" nowrap style="text-align:center; font-weight:bold">SATUAN</td>
                  <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">STATUS</td>
              </tr>
                <tr style="background:#CCCCCC">
                  <td nowrap style="text-align:center; font-weight:bold">FPA</td>
                  <td nowrap style="text-align:center; font-weight:bold">PO</td>
                  <td nowrap style="text-align:center; font-weight:bold">RR</td>
                  <td nowrap style="text-align:center; font-weight:bold">SISA FPA</td>
                </tr>
                <?php
					function hitungHari($awal,$akhir)
					{
						$tglAwal = strtotime($awal);
						$tglAkhir = strtotime($akhir);
						$jeda = $tglAkhir - $tglAwal;
						return floor($jeda/(60*60*24));
					}
					
					if($CFType == 1) // 1. Detail, 2. Sumamry
					{
						$therow		= 0;
						$VMonth		= 'All';	// Diganti dengan periode
						if($CFStat == 'All')
							$QUERY1	= "";
						else
							$QUERY1	= "AND B.PR_STAT = $CFStat";
						
						if($viewProj == 0)		// SELECTED PROJECT
						{
							if($VMonth == 'All')
							{
								$sql0 		= "tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.PRJCODE IN ($PRJCODECOL)
													$QUERY1
													AND B.PR_DATE > '$StartDate' AND B.PR_DATE < '$EndDate'";
								$sql1 		= "SELECT A.ID, A.ITM_CODE, A.ITM_UNIT, A.PR_VOLM, A.PO_VOLM, A.IR_VOLM, A.PR_TOTAL, A.PR_DESC,
													B.PR_NUM, B.PR_CODE, B.JOBCODE, B.PR_DATE, A.PRJCODE, B.SPLCODE, B.PR_NOTE, 
													B.PR_NOTE2, B.PR_PLAN_IR,
													B.PR_STAT, B.PR_VALUE, B.PR_REFNO
												FROM tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.PRJCODE IN ($PRJCODECOL)
													$QUERY1
													AND B.PR_DATE >= '$StartDate' AND B.PR_DATE <= '$EndDate'
												ORDER BY B.PR_DATE ASC";
							}
							else
							{
								$sql0 		= "tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.PRJCODE IN ($PRJCODECOL)
													AND B.PR_DATE > '$StartDate' AND B.PR_DATE < '$EndDate'";
								$sql1 		= "SELECT A.ID, A.ITM_CODE, A.ITM_UNIT, A.PR_VOLM, A.PO_VOLM, A.IR_VOLM, A.PR_TOTAL, A.PR_DESC,
													B.PR_NUM, B.PR_CODE, B.JOBCODE, B.PR_DATE, A.PRJCODE, B.SPLCODE, B.PR_NOTE, 
													B.PR_NOTE2, B.PR_PLAN_IR,
													B.PR_STAT, B.PR_VALUE, B.PR_REFNO
												FROM tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.PRJCODE IN ($PRJCODECOL)
													AND B.PR_DATE >= '$StartDate' AND B.PR_DATE <= '$EndDate'
												ORDER BY B.PR_NUM";
							}
						}
						else					// ALL PROJECT
						{
							if($VMonth == 'All')
							{
								$sql0 		= "tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.PR_STAT = $CFStat
													AND B.PR_DATE > '$StartDate' AND B.PR_DATE < '$EndDate'";
								$sql1 		= "SELECT A.ID, A.ITM_CODE, A.ITM_UNIT, A.PR_VOLM, A.PO_VOLM, A.IR_VOLM, A.PR_TOTAL, A.PR_DESC,
													B.PR_NUM, B.PR_CODE, B.JOBCODE, B.PR_DATE, A.PRJCODE, B.SPLCODE, B.PR_NOTE, 
													B.PR_NOTE2, B.PR_PLAN_IR,
													B.PR_STAT, B.PR_VALUE, B.PR_REFNO
												FROM tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.PR_STAT = $CFStat
													AND B.PR_DATE >= '$StartDate' AND B.PR_DATE <= '$EndDate'
												ORDER BY B.PR_DATE ASC";
							}
							else
							{
								$sql0 		= "tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.MONTH(PR_DATE) = $VMonth";
								$sql1 		= "SELECT A.ID, A.ITM_CODE, A.ITM_UNIT, A.PR_VOLM, A.PO_VOLM, A.IR_VOLM, A.PR_TOTAL, A.PR_DESC,
													B.PR_NUM, B.PR_CODE, B.JOBCODE, B.PR_DATE, A.PRJCODE, B.SPLCODE, B.PR_NOTE, B.PR_NOTE2, B.PR_PLAN_IR,
													B.PR_STAT, B.PR_VALUE, B.PR_REFNO
												FROM tbl_pr_detail A
													INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
												WHERE B.MONTH(PR_DATE) = $VMonth ORDER BY B.PR_NUM";
							}
						}
						
						$res0 			= $this->db->count_all($sql0);
						$res1 			= $this->db->query($sql1)->result();
						
						$PR_NUM			= '';
						$PR_CODE		= '';
						$PR_DATE 		= '';
						$SPLCODE 		= '';
						$JOBCODE 		= '';
						$PR_NOTE 		= 0;
						$PR_NOTE2		= '';
						$PR_PLAN_IR 	= '';
						$PR_STAT		= 0;
						$PR_VALUE		= '';
						$PR_REFNO		= '';
						
						if($res0 > 0)
						{
							foreach($res1 as $rowsql1) :
								$therow		= $therow + 1;
								$PRD_ID 	= $rowsql1->ID;
								$PR_NUM 	= $rowsql1->PR_NUM;
								$PR_CODE 	= $rowsql1->PR_CODE;
								$PR_DATE 	= $rowsql1->PR_DATE;
								$PRJCODE 	= $rowsql1->PRJCODE;
								$PRJNAME	= '';
								$SPLCODE 	= $rowsql1->SPLCODE;
								$JOBCODE 	= $rowsql1->JOBCODE;
								$JOBDESC	= '';
								$PR_NOTE 	= $rowsql1->PR_NOTE;
								$PR_NOTE2 	= $rowsql1->PR_NOTE2;
								$PR_PLAN_IR	= $rowsql1->PR_PLAN_IR;
								$PR_STAT 	= $rowsql1->PR_STAT;
								$PR_VALUE 	= $rowsql1->PR_VALUE;
								$PR_REFNO 	= $rowsql1->PR_REFNO;
								$ITM_CODE 	= $rowsql1->ITM_CODE;
								$ITM_NAME	= '';
								$ITM_UNIT 	= $rowsql1->ITM_UNIT;
								$PR_VOLM 	= $rowsql1->PR_VOLM;
								$PO_VOLM 	= $rowsql1->PO_VOLM;
								$IR_VOLM 	= $rowsql1->IR_VOLM;
								$PR_TOTAL 	= $rowsql1->PR_TOTAL;
								$PR_DESC 	= $rowsql1->PR_DESC;
								$sql3 		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODE'";
								$res3 		= $this->db->query($sql3)->result();
								foreach($res3 as $rowsql3) :
									$JOBDESC 	= $rowsql3->JOBDESC;
								endforeach;
								$sql4 		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'";
								$res4 		= $this->db->query($sql4)->result();
								foreach($res4 as $rowsql4) :
									$ITM_NAME 	= $rowsql4->ITM_NAME;
								endforeach;
								
								$POVOLM		= 0;
								$sqlPO 		= "SELECT SUM(A.PO_VOLM) AS POVOLM FROM tbl_po_detail A
												INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE A.PR_NUM = '$PR_NUM' AND B.PO_STAT IN (3,6)
													AND A.PRD_ID = $PRD_ID";
								$resPO 		= $this->db->query($sqlPO)->result();
								foreach($resPO as $rowPO) :
									$POVOLM 	= $rowPO->POVOLM;
								endforeach;
								
								$POVOLM		= 0;
								$IRVOLM		= 0;
								$sqlPO 		= "SELECT SUM(A.PO_VOLM) AS POVOLM FROM tbl_po_detail A
												INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE A.PR_NUM = '$PR_NUM' AND B.PO_STAT IN (3,6)
													AND A.PRD_ID = $PRD_ID";
								$resPO 		= $this->db->query($sqlPO)->result();
								foreach($resPO as $rowPO) :
									$POVOLM 	= $rowPO->POVOLM;
									
									if($POVOLM > 0)
									{
										$sqlPOD	= "SELECT A.ID, A.PO_NUM, A.ITM_CODE FROM tbl_po_detail A
													INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
													WHERE A.PR_NUM = '$PR_NUM' AND B.PO_STAT IN (3,6) AND A.ITM_CODE = '$ITM_CODE'
														AND A.PRD_ID = $PRD_ID";
										$resPOD	= $this->db->query($sqlPOD)->result();
										foreach($resPOD as $rowPOD) :
											$PODID 		= $rowPOD->ID;
											$PONUM 		= $rowPOD->PO_NUM;
											$ITMCODE 	= $rowPOD->ITM_CODE;
											$sqlIR 		= "SELECT SUM(A.ITM_QTY) AS IRVOLM FROM tbl_ir_detail A
															INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
															WHERE A.PO_NUM = '$PONUM' AND B.IR_STAT IN (3,6) AND A.ITM_CODE = '$ITMCODE'
																AND A.POD_ID = $PODID";
											$resIR 		= $this->db->query($sqlIR)->result();
											foreach($resIR as $rowIR) :
												$IRVOLM1 	= $rowIR->IRVOLM;
												$IRVOLM		= $IRVOLM + $IRVOLM1;
											endforeach;
										endforeach;
									}
								endforeach;
								
								if($JOBDESC == '')
									$JOBDESC = "-";
								
								if($PR_STAT == 0)
								{
									$PR_STATD 	= 'fake';
									$STATCOL	= 'danger';
								}
								elseif($PR_STAT == 1)
								{
									$PR_STATD 	= 'New';
									$STATCOL	= 'warning';
								}
								elseif($PR_STAT == 2)
								{
									$PR_STATD 	= 'Confirm';
									$STATCOL	= 'primary';
								}
								elseif($PR_STAT == 3)
								{
									$PR_STATD 	= 'Approved';
									$STATCOL	= 'success';
								}
								elseif($PR_STAT == 4)
								{
									$PR_STATD 	= 'Revise';
									$STATCOL	= 'danger';
								}
								elseif($PR_STAT == 5)
								{
									$PR_STATD 	= 'Rejected';
									$STATCOL	= 'danger';
								}
								elseif($PR_STAT == 6)
								{
									$PR_STATD 	= 'Close';
									$STATCOL	= 'danger';
								}
								else
								{
									$PR_STATD 	= 'Awaiting';
									$STATCOL	= 'warning';
								}
								?>
								<tr>
									<td nowrap style="text-align:left;">
										<?php
											echo "$therow.";
										?>
									</td>
									<td nowrap style="text-align:left;">
										<?php
												echo "$PR_CODE-$PRD_ID"; 
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PR_DATE;
										?>
									</td>
									<td nowrap style="text-align:left; display:none; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $JOBDESC;
										?>
                                    </td>
									<td style="text-align:left; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo "$ITM_CODE - $ITM_NAME : $PR_DESC";
										?>
									</td>
									<td width="8%" nowrap style="text-align:right; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo number_format($PR_VOLM, 2);
										?>
									</td>
									<td width="8%" nowrap style="text-align:right; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo number_format($POVOLM, 2);
										?>
									</td>
									<td width="7%" nowrap style="text-align:right; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo number_format($IRVOLM, 2);
										?>
									</td>
									<td width="8%" nowrap style="text-align:right; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											$PR_REMAIN	= $PR_VOLM - $POVOLM;
											echo number_format($PR_REMAIN, 2);
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $ITM_UNIT;
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PR_STATD;
										?>
									</td>
								</tr>
								<?php
							endforeach;
						}
						else
						{
							?>
								<tr>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic; display:none">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
								</tr>
							<?php
						}
					}
                ?>
            </table>
	  </td>
    </tr>
</table>
</section>
</body>
</html>