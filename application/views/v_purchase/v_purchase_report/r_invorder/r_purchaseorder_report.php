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

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?> (<?php echo $CFTyped; ?>)<br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">PT 1ST-WEB SYSTEM MEDIA</span></td>
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
                    <td width="3%" rowspan="2" nowrap style="text-align:center; font-weight:bold">NO.</td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KODE PESAN</td>
                  <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">PROYEK</td>
                  <td width="16%" rowspan="2" nowrap style="text-align:center; font-weight:bold">PEKERJAAN</td>
                  <td width="27%" rowspan="2" nowrap style="text-align:center; font-weight:bold">SUPPLIER</td>
                  <td width="6%" rowspan="2" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                  <td nowrap style="text-align:center; font-weight:bold"> TOTAL</td>
                  <td width="20%" rowspan="2" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>
                  <td width="5%" rowspan="2" nowrap style="text-align:center; font-weight:bold">STATUS</td>
              </tr>
                <tr style="background:#CCCCCC">
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">RP</td>
                </tr>
                <?php
					function hitungHari($awal,$akhir)
					{
						$tglAwal = strtotime($awal);
						$tglAkhir = strtotime($akhir);
						$jeda = $tglAkhir - $tglAwal;
						return floor($jeda/(60*60*24));
					}
					
					if($CFType == 2) // 1. Detail, 2. Sumamry
					{
						$therow		= 0;
						if($viewProj == 0)	// SELECTED PROJECT
						{
							if($VMonth == 'All')
							{
								$sql0 		= "tbl_po_header WHERE PRJCODE IN ($PRJCODECOL)";
								$sql1 		= "SELECT * FROM tbl_po_header WHERE PRJCODE IN ($PRJCODECOL)";
							}
							else
							{
								$sql0 		= "tbl_po_header WHERE PRJCODE IN ($PRJCODECOL) AND MONTH(PO_DATE) = $VMonth";
								$sql1 		= "SELECT * FROM tbl_po_header WHERE PRJCODE IN ($PRJCODECOL) AND MONTH(PO_DATE) = $VMonth";
							}
						}
						else				// ALL PROJECT
						{
							if($VMonth == 'All')
							{
								$sql0 		= "tbl_po_header";
								$sql1 		= "SELECT * FROM tbl_PO_header";
							}
							else
							{
								$sql0 		= "tbl_po_header WHERE MONTH(PO_DATE) = $VMonth";
								$sql1 		= "SELECT * FROM tbl_po_header WHERE MONTH(PO_DATE) = $VMonth";
							}
						}
						
						$res0 			= $this->db->count_all($sql0);
						$res1 			= $this->db->query($sql1)->result();
						
						$PO_NUM			= '';
						$PO_CODE		= '';
						$PO_DATE 		= '';
						$SPLCODE 		= '';
						$JOBCODE 		= '';
						$PO_NOTES 		= 0;
						$PO_NOTES1		= '';
						$PO_STAT		= 0;
						$PO_TOTCOST		= 0;
						$JOBCODE		= '';
						
						if($res0 > 0)
						{
							foreach($res1 as $rowsql1) :
								$therow		= $therow + 1;
								$PO_NUM 	= $rowsql1->PO_NUM;
								$PO_CODE 	= $rowsql1->PO_CODE;
								$PO_DATE 	= $rowsql1->PO_DATE;
								$PRJCODE 	= $rowsql1->PRJCODE;
								$SPLCODE 	= $rowsql1->SPLCODE;
								$SPLDESC	= '';
								$sql2 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' ";
								$res2 		= $this->db->query($sql2)->result();
								foreach($res2 as $rowsql2) :
									$SPLDESC 	= $rowsql2->SPLDESC;
								endforeach;
								$JOBCODE 	= $rowsql1->JOBCODE;
								$JOBDESC	= '';
								$sql3 		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODE'";
								$res3 		= $this->db->query($sql3)->result();
								foreach($res3 as $rowsql3) :
									$JOBDESC 	= $rowsql3->JOBDESC;
								endforeach;
								$PO_NOTES 	= $rowsql1->PO_NOTES;
								$PO_NOTES1 	= $rowsql1->PO_NOTES1;
								//$PO_PLAN_IR	= $rowsql1->PO_PLAN_IR;
								$PO_STAT 	= $rowsql1->PO_STAT;
								$PO_TOTCOST = $rowsql1->PO_TOTCOST;
								
								if($PO_STAT == 0)
								{
									$PO_STATD 	= 'fake';
								}
								elseif($PO_STAT == 1)
								{
									$PO_STATD 	= 'New';
								}
								elseif($PO_STAT == 2)
								{
									$PO_STATD 	= 'Confirm';
								}
								elseif($PO_STAT == 3)
								{
									$PO_STATD 	= 'Approved';
								}
								elseif($PO_STAT == 4)
								{
									$PO_STATD 	= 'Revise';
								}
								elseif($PO_STAT == 5)
								{
									$PO_STATD 	= 'Rejected';
								}
								elseif($PO_STAT == 6)
								{
									$PO_STATD 	= 'Close';
								}
								else
								{
									$PO_STATD 	= 'Awaiting';
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
												echo "$PO_CODE"; 
										?>
									</td>
									<td nowrap style="text-align:center;"><?php
												echo "$PRJCODE"; 
										?></td>
									<td nowrap style="text-align:left;">
									  <?php
											echo $JOBDESC;
										?>
									</td>
									<td nowrap style="text-align:left;"><?php echo $SPLDESC; ?></td>
									<td nowrap>
										<?php
											echo $PO_DATE;
										?>
                                    </td>
									<td style="text-align:right" nowrap>
										<?php
											echo number_format($PO_TOTCOST, 2);
										?>
									</td>
									<td>&nbsp;</td>
									<td nowrap style="text-align:center; <?php if($PO_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PO_STATD;
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
									<td colspan="4" nowrap style="text-align:center; font-style:italic">&nbsp;</td>
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