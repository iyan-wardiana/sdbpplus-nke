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
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
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
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $h1_title; ?> (<?php echo $CFTyped; ?>)<br><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span></td>
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
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">NO.</td>
                  <td width="23%" nowrap style="text-align:center; font-weight:bold">PROJECT</td>
                  <td width="7%" nowrap style="text-align:center; font-weight:bold">KODE</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                  <td width="32%" nowrap style="text-align:center; font-weight:bold">PEKERJAAN</td>
                  <td width="12%" nowrap style="text-align:center; font-weight:bold">RENCANA DITERIMA</td>
                  <td width="5%" nowrap style="text-align:center; font-weight:bold">STATUS</td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold">ID</td>
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
								$sql0 		= "tbl_pr_header WHERE PRJCODE IN ($PRJCODECOL)";
								$sql1 		= "SELECT * FROM tbl_pr_header WHERE PRJCODE IN ($PRJCODECOL)";
							}
							else
							{
								$sql0 		= "tbl_pr_header WHERE PRJCODE IN ($PRJCODECOL) AND MONTH(PR_DATE) = $VMonth";
								$sql1 		= "SELECT * FROM tbl_pr_header WHERE PRJCODE IN ($PRJCODECOL) AND MONTH(PR_DATE) = $VMonth";
							}
						}
						else				// ALL PROJECT
						{
							if($VMonth == 'All')
							{
								$sql0 		= "tbl_pr_header";
								$sql1 		= "SELECT * FROM tbl_pr_header";
							}
							else
							{
								$sql0 		= "tbl_pr_header WHERE MONTH(PR_DATE) = $VMonth";
								$sql1 		= "SELECT * FROM tbl_pr_header WHERE MONTH(PR_DATE) = $VMonth";
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
								$PR_NUM 	= $rowsql1->PR_NUM;
								$PR_CODE 	= $rowsql1->PR_CODE;
								$PR_DATE 	= $rowsql1->PR_DATE;
								$PRJCODE 	= $rowsql1->PRJCODE;
								$PRJNAME	= '';
								$sql2 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
								$res2 		= $this->db->query($sql2)->result();
								foreach($res2 as $rowsql2) :
									$PRJNAME 	= $rowsql2->PRJNAME;
								endforeach;
								$SPLCODE 	= $rowsql1->SPLCODE;
								$JOBCODE 	= $rowsql1->JOBCODE;
								$JOBDESC	= '';
								$sql3 		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBCODE'";
								$res3 		= $this->db->query($sql3)->result();
								foreach($res3 as $rowsql3) :
									$JOBDESC 	= $rowsql3->JOBDESC;
								endforeach;
								$PR_NOTE 	= $rowsql1->PR_NOTE;
								$PR_NOTE2 	= $rowsql1->PR_NOTE2;
								$PR_PLAN_IR	= $rowsql1->PR_PLAN_IR;
								$PR_STAT 	= $rowsql1->PR_STAT;
								$PR_VALUE 	= $rowsql1->PR_VALUE;
								$PR_REFNO 	= $rowsql1->PR_REFNO;
								?>
								<tr>
									<td nowrap style="text-align:left;">
										<?php
											echo "$therow.";
										?>
									</td>
									<td nowrap style="text-align:left;">
										<?php
												echo "$PRJCODE - $PRJNAME"; 
										?>
									</td>
									<td nowrap style="text-align:left; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PR_CODE;
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PR_DATE;
										?>
                                    </td>
									<td nowrap style="text-align:left; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $JOBDESC;
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PR_PLAN_IR;
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">
										<?php
											echo $PR_STAT;
										?>
									</td>
									<td nowrap style="text-align:center; <?php if($PR_STAT == 1) { ?> background:#C63 <?php } ?>">&nbsp;</td>
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