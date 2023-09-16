<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Maret 2017
 * File Name	= r_ttkoutstanding_report.php
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

if($viewProj == 0)
{
	if($TOTPROJ == 1)
	{
		$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
						WHERE A.PRJCODE IN ($PRJCODECOL)
						ORDER BY A.PRJCODE";
		$result 	= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJCODED 	= $row ->PRJCODE;
			$PRJNAMED 	= $row ->PRJNAME;
		endforeach;
		$PRJCODECOLL	= "$PRJCODED";
		$PRJNAMECOLL	= "$PRJNAMED";
	}
	else
	{
		$PRJCODED	= 'Multi Project Code';
		$PRJNAMED 	= 'Multi Project Name';
		$myrow		= 0;
		$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
						WHERE A.PRJCODE IN ($PRJCODECOL)
						ORDER BY A.PRJCODE";
		$result 	= $this->db->query($sql)->result();
		foreach($result as $row) :
			$myrow		= $myrow + 1;
			$PRJCODED 	= $row ->PRJCODE;
			$PRJNAMED 	= $row ->PRJNAME;
			if($myrow == 1)
			{
				$PRJCODECOLL	= "$PRJCODED";
				$PRJCODECOL1	= "$PRJCODED";
				$PRJNAMECOLL	= "$PRJNAMED";
				$PRJNAMECOL1	= "$PRJNAMED";
			}
			if($myrow > 1)
			{
				$PRJCODECOL1	= "$PRJCODECOL1, $PRJCODED";
				$PRJCODECOLL	= "$PRJCODECOL1";
				$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
				$PRJNAMECOLL	= "$PRJNAMECOL1";
			}		
		endforeach;
	}	
	$PRJCODECOLLD	= $PRJCODECOLL;
	$PRJNAMECOLLD	= $PRJNAMECOLL;
	//return false;
}
else
{
	$myrow			= 0;
	$sql 			= "SELECT DISTINCT PRJCODE FROM tbl_project WHERE PRJCOST > 1000000";
	$result 		= $this->db->query($sql)->result();	
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODE	= $row->PRJCODE;
		if($myrow == 1)
		{
			$NPRJCODE = $PRJCODE;
		}
		else if($myrow == 2)
		{
			$NPRJCODE = "'$NPRJCODE', '$PRJCODE'";
		}
		else if($myrow > 2)
		{
			$NPRJCODE = "$NPRJCODE, '$PRJCODE'";
		}
	endforeach;
	$PRJCODECOL		= $NPRJCODE;
	//echo "$NPRJCODE";
	//return false;
	$PRJCODECOLL	= "All";
	$PRJNAMECOLL	= "All";
	$PRJCODECOLLD	= "All";
	$PRJNAMECOLLD	= "All";
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
<body>
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
                <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/Logo1.jpg') ?>" width="181" height="44"></td>
                <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">TTK OUTSTANDING REPORT</span> ADMIN</td>
          </tr>
            <tr>
                <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span></td>
            </tr>
                <?php
                    $StartDate1 = date('Y/m/d',strtotime($Start_Date));
                   	$EndDate1 = date('Y/m/d',strtotime($End_Date));
                   	$End_Date = date('Y-m-d',strtotime($End_Date));
                ?>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;&nbsp;Report Until Date : <?php echo $End_Date; ?></td>
            </tr>
            <tr>
                <td colspan="3" class="style2" style="text-align:left; font-style:italic">
                    <table width="100%">
                        <tr style="text-align:left; font-style:italic">
                            <td width="8%" nowrap valign="top">PROJ. CODE</td>
                            <td width="1%">:</td>
                            <td width="91%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJCODECOLL"; ?></span></td>
                        </tr>
                        <tr style="text-align:left; font-style:italic">
                          <td nowrap valign="top">PROJ. NAME</td>
                          <td>:</td>
                          <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAMECOLL;?></span></td>
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
                        <td width="3%" nowrap style="text-align:center; font-weight:bold">No.</td>
                        <td width="4%" nowrap style="text-align:center; font-weight:bold">Project</td>
                        <td width="11%" nowrap style="text-align:center; font-weight:bold">TTK CODE</td>
                        <td width="9%" nowrap style="text-align:center; font-weight:bold">DATE</td>
                        <td width="16%" style="text-align:center; font-weight:bold">Supplier / Subkon</td>
                        <td width="39%" nowrap style="text-align:center; font-weight:bold">Description</td>
                        <td width="7%" nowrap style="text-align:center; font-weight:bold">VOUCHER<BR />CODE</td>
                        <td width="11%" nowrap style="text-align:center; font-weight:bold">Value (IDR)</td>
                    </tr>
                    <?php					
						$therow			= 0;
						$GTOTAL			= 0;
						$GTOTALA		= 0;
						$noU			= 0;
						$noUa			= 0;
						$noUb			= 0;
						
						// Mengumpulkan data TTKCODE
						/*$sqlq0 			= "SELECT TTKCODE FROM TTKHD WHERE TTKPROC = 0";
						$resq0 			= $this->db->query($sqlq0)->result();
						foreach($resq0 as $rowq0) :
							$noU		= $noU + 1;
							$TTKCODE	= $rowq0->TTKCODE;
							if($noU == 1)
							{
								$NTTKCODE = $TTKCODE;
							}
							else if($noU == 2)
							{
								$NTTKCODE = "'$NTTKCODE', '$TTKCODE'";
							}
							else if($noU > 2)
							{
								$NTTKCODE = "$NTTKCODE, '$TTKCODE'";
							}
						endforeach;*/
						if($viewProj == 0)
						{
							$sqlq1 			= "SELECT A.TTKCODE, A.PRJCODE, A.TTKCOST, A.PPN, B.TTKDATE, B.SPLCODE, B.TTKDESC, B.VOCCODE
												FROM ttkout_ttkdt A
													INNER JOIN ttkout_ttkhd B ON A.TTKCODE = B.TTKCODE
														AND B.TTKPROC = 0
												WHERE 
													A.PRJCODE IN ($PRJCODECOL)
													ORDER BY A.TTKCODE";
							$resq1 			= $this->db->query($sqlq1)->result();
						}
						else
						{
							$sqlq1 			= "SELECT A.TTKCODE, A.PRJCODE, A.TTKCOST, A.PPN, B.TTKDATE, B.SPLCODE, B.TTKDESC, B.VOCCODE
												FROM ttkout_ttkdt A
													INNER JOIN ttkout_ttkhd B ON A.TTKCODE = B.TTKCODE
														AND B.TTKPROC = 0
												ORDER BY A.TTKCODE";
							$resq1 			= $this->db->query($sqlq1)->result();
						}
						foreach($resq1 as $rowsqlq1) :
							$TTKCODE 	= $rowsqlq1->TTKCODE;
							$TTKCODEC 	= strlen($TTKCODE);
							if($TTKCODEC < 7)
							{
								$therow		= $therow + 1;
								$TTKCODE 	= $rowsqlq1->TTKCODE;
								$PRJCODE 	= $rowsqlq1->PRJCODE;
								$TTKCOST 	= $rowsqlq1->TTKCOST;
								$PPN 		= $rowsqlq1->PPN;
								$TTKDATE	= $rowsqlq1->TTKDATE;
								$SPLCODE	= $rowsqlq1->SPLCODE;
								$TTKDESC	= $rowsqlq1->TTKDESC;
								$VOCCODE	= $rowsqlq1->VOCCODE;
								
								$GTOTAL		= $GTOTAL + $TTKCOST + $PPN;
								
								if($TTKCOST > 0 && $TTKCOST < 1)
								{
									$TTKCOST	= $PPN;
								}
											
								/*$sqlq1a 		= "SELECT DISTINCT PRJCODE FROM ttkout_ttkdt WHERE TTKCODE = '$TTKCODE' LIMIT 1";
								$resq1a 		= $this->db->query($sqlq1a)->result();
								foreach($resq1a as $rowq1a) :
									$PRJCODE	= $rowq1a->PRJCODE;
								endforeach;*/
								
								$SPLDESC	= '';
								$sqlq4a 		= "SELECT SPLDESC FROM SUPPLIER WHERE SPLCODE = '$SPLCODE' LIMIT 1";
								$resq4a 		= $this->db->query($sqlq4a)->result();
								foreach($resq4a as $rowq4a) :
									$SPLDESC	= $rowq4a->SPLDESC;
								endforeach;
								?>
									<tr>
										<td nowrap style="text-align:left;"><?php echo $therow; ?>.</td>
										<td nowrap style="text-align:left;"><?php echo $PRJCODE; ?></td>
										<td nowrap style="text-align:left;"><?php echo $TTKCODE; ?></td>
										<td nowrap style="text-align:left;"><?php echo $TTKDATE; ?></td>
										<td nowrap style="text-align:left;"><?php echo "$SPLCODE - $SPLDESC"; ?></td>
										<td nowrap style="text-align:left;"><?php echo $TTKDESC; ?></td>
										<td nowrap style="text-align:left;"><?php echo $VOCCODE; ?></td>
										<td nowrap style="text-align:right;"><?php echo number_format($TTKCOST, $decFormat); ?></td>
									</tr>
								<?php
							}
						endforeach;
					?>
                    <tr>
                        <td colspan="6" nowrap style="text-align:left; font-weight:bold;">T O T A L</td>
                        <td nowrap style="text-align:right; font-weight:bold">&nbsp;</td>
                        <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTAL, $decFormat); ?></td>
                    </tr>
                </table>
              </td>
            </tr>
        </table>
</section>
</body>
</html>