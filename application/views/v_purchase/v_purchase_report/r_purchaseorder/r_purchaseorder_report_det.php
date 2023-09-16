<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 September 2018
 * File Name	= r_purchaseorder_report_det.php
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

$StartDate	= date('Y-m-d', strtotime($Start_Date));
$EndDate	= date('Y-m-d', strtotime($End_Date));

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";
	
date_default_timezone_set("Asia/Jakarta");

$PRJNAME	= '';
if($viewProj == 'All')
{
	$PRJNAME	= "All";
}
else
{
	$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE IN ($PRJCODECOL)";
	$resPRJ		= $this->db->query($sqlPRJ)->result();
	foreach($resPRJ as $PRJNM) :
		$PRJNAME = $PRJNM->PRJNAME;
	endforeach;
}
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
        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td width="58%" class="style2">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?> (<?php echo $CFTyped; ?>)<br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span><br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">Periode : <?php echo date('d M Y', strtotime($StartDate)); ?> - <?php echo date('d M Y', strtotime($EndDate)); ?></span></td>
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
                    <td width="92%"><?php echo "$PRJCODECOL"; ?></span></td>
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
                    <td width="4%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NO.</td>
                  <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KODE</td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                  <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold">PROYEK</td>
                  <td width="28%" rowspan="2" nowrap style="text-align:center; font-weight:bold">SUPPLIER</td>
                  <td width="18%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NAMA ITEM</td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold">VOLUME</td>
                  <td width="6%" rowspan="2" nowrap style="text-align:center; font-weight:bold">SATUAN</td>
                  <td width="11%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
              </tr>
                <tr style="background:#CCCCCC">
                  <td width="6%" nowrap style="text-align:center; font-weight:bold">PO</td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold">RR</td>
                </tr>
                <?php
					function hitungHari($awal,$akhir)
					{
						$tglAwal = strtotime($awal);
						$tglAkhir = strtotime($akhir);
						$jeda = $tglAkhir - $tglAwal;
						return floor($jeda/(60*60*24));
					}
					
					if($RepStatus == 'All')
					{
						$ADDSTATQRY = "";
					}
					else
					{
						$ADDSTATQRY = "AND B.PO_STAT = '$RepStatus'";
					}
					
					if($CFType == 1) 	// Detail
					{
						$therow		= 0;
						if($viewProj == 0)	// SELECTED PROJECT
						{
							if($EndDate == 'All')
							{
								/*$sql0 		= "tbl_po_detail WHERE PRJCODE IN ($PRJCODECOL)";
								$sql1 		= "SELECT A.PRJCODE, A.PO_NUM, A.PO_CODE, A.PO_DATE, A.ITM_CODE, A.ITM_UNIT, 
													A.PO_VOLM, A.IR_VOLM
												FROM tbl_po_detail A INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE A.PRJCODE IN ($PRJCODECOL)";*/
							}
							else
							{
								$sql0 		= "tbl_po_detail WHERE PRJCODE IN ($PRJCODECOL) AND PO_DATE >= '$StartDate' 
												AND PO_DATE <= '$EndDate'";
								$sql1 		= "SELECT A.PRJCODE, A.PO_NUM, A.PO_CODE, A.PO_DATE, A.ITM_CODE, A.ITM_UNIT,
													A.PO_VOLM, A.IR_VOLM, B.SPLCODE
												FROM tbl_po_detail A INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE A.PRJCODE IN ($PRJCODECOL) AND A.PO_DATE >= '$StartDate'
													AND A.PO_DATE <= '$EndDate' $ADDSTATQRY l";
							}
						}
						else				// ALL PROJECT
						{
							if($EndDate == 'All')
							{
								/*$sql0 		= "tbl_po_detail";
								$sql1 		= "SELECT A.PRJCODE, A.PO_NUM, A.PO_CODE, A.PO_DATE, A.ITM_CODE, A.ITM_UNIT, 
													A.PO_VOLM, A.IR_VOLM 
												FROM tbl_po_detail A INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM";*/
							}
							else
							{
								$sql0 		= "tbl_po_detail WHERE PO_DATE >= '$StartDate' AND PO_DATE <= '$EndDate'";
								$sql1 		= "SELECT A.PRJCODE, A.PO_NUM, A.PO_CODE, A.PO_DATE, A.ITM_CODE, A.ITM_UNIT, 
													A.PO_VOLM, A.IR_VOLM, B.SPLCODE
												FROM tbl_po_detail A INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
												WHERE A.PO_DATE >= '$StartDate' AND A.PO_DATE <= '$EndDate' $ADDSTATQRY v";
							}
						}
						
						$res0 			= $this->db->count_all($sql0);
						$res1 			= $this->db->query($sql1)->result();
													
						$PO_NUM			= '';
						$PO_CODE		= '';
						$PO_DATE 		= '';
						$SPLCODE 		= '';
						$ITM_CODE 		= '';
						$ITM_UNIT 		= '';
						$PO_VOLM		= 0;
						$IR_VOLM		= 0;
						$PO_TOTCOST		= 0;
						
						if($res0 > 0)
						{
							foreach($res1 as $rowsql1) :
								$therow		= $therow + 1;
								$PRJCODE 	= $rowsql1->PRJCODE;
								$PO_NUM 	= $rowsql1->PO_NUM;
								$PO_CODE 	= $rowsql1->PO_CODE;
								$PO_DATE 	= $rowsql1->PO_DATE;
								$SPLCODE 	= $rowsql1->SPLCODE;
								$ITM_CODE 	= $rowsql1->ITM_CODE;
								$ITM_UNIT 	= $rowsql1->ITM_UNIT;
								$ITM_NAME	= '';
								$sql2 		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE'
												AND PRJCODE = '$PRJCODE' LIMIT 1";
								$res2 		= $this->db->query($sql2)->result();
								foreach($res2 as $rowsql2) :
									$ITM_NAME 	= $rowsql2->ITM_NAME;
								endforeach;
								$SPLDESC	= '';
								$sql3 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
								$res3 		= $this->db->query($sql3)->result();
								foreach($res3 as $rowsql3) :
									$SPLDESC 	= $rowsql3->SPLDESC;
								endforeach;
								$PO_VOLM 	= $rowsql1->PO_VOLM;
								$IR_VOLM 	= $rowsql1->IR_VOLM;
								?>
								<tr>
									<td nowrap style="text-align:left;">
										<?php
											echo "$therow.";
										?>
									</td>
									<td nowrap style="text-align:left;">
										<?php
												echo "$PO_CODE"; 
										?>
									</td>
									<td nowrap style="text-align:left;">
										<?php
											echo $PO_DATE;
										?>
                                    </td>
									<td nowrap style="text-align:center;"><?php
												echo "$PRJCODE"; 
										?></td>
									<td style="text-align:left;">
									  <?php
											echo $SPLDESC;
										?>
									</td>
									<td nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
									<td nowrap style="text-align:right">
										<?php
											echo number_format($PO_VOLM, 2);
										?>
                                    </td>
									<td nowrap style="text-align:right">
										<?php
											echo number_format($IR_VOLM, 2);
										?>
                                    </td>
									<td style="text-align:center" nowrap>
										<?php
											echo $ITM_UNIT;
										?>
									</td>
									<td nowrap style="text-align:center;">&nbsp;</td>
								</tr>
								<?php
							endforeach;
						}
						else
						{
							?>
								<tr>
									<td nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td colspan="5" nowrap style="text-align:center; font-style:italic">&nbsp;</td>
									<td colspan="2" nowrap style="text-align:center; font-style:italic">&nbsp;</td>
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