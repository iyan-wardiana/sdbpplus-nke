<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= r_dpreport_report_adm.php
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
if($viewProj == 0) // SELECTED PROJECT
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
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.jpg'); ?>" width="181" height="44"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">DOWN PAYMENT  BALANCED REPORT</td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">PT SASMITO INFRA</span></td>
    </tr>
        <?php
            $StartDate1 = date('Y/m/d',strtotime($Start_Date));
            $EndDate1 = date('Y/m/d',strtotime($End_Date));
        ?>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">Report Until :  <?php echo $EndDate1; ?></td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">PROJ. CODE</td>
                    <td width="1%">:</td>
                    <td width="91%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$PRJCODECOLLD"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">PROJ. NAME</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAMECOLLD;?></span></td>
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
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">NO.</td>
                    <td width="4%" nowrap style="text-align:center; font-weight:bold">PROJECT</td>
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">VOUCHER<br />CODE</td>
                    <td width="9%" nowrap style="text-align:center; font-weight:bold">DATE</td>
                    <td width="16%" style="text-align:center; font-weight:bold">SUPPLIER / SUBCONT</td>
                    <td width="39%" nowrap style="text-align:center; font-weight:bold">DESCRIPTION</td>
                    <td width="5%" nowrap style="text-align:center; font-weight:bold">VALUE (IDR)</td>
                    <td width="6%" nowrap style="text-align:center; font-weight:bold">VALUE (IDR)</td>
                </tr>
                <?php
					// 	---------- POSITIF ----------
                    $therow_P	= 0;
                    $GTOTAL_P	= 0;
                    $GTOTALA_P	= 0;
                    $GTOTALB_P	= 0;
                    $noU_P		= 0;
                    $noUa_P		= 0;
                    $noUb_P		= 0;
                    $countSPL	= strlen($SPLCODECOL);
                    if($viewProj == 0)		// PER PROJECT
                    {
                        if($countSPL > 2) 	// PER PROJECT AND PER SUPPLIER
                        {
                            $sqlq0_P 	= "tbl_dp_report 
												WHERE
													AND DPR_CSTCOST != 0
													AND DPR_PRJCODE IN ($PRJCODECOL)
													AND DPR_SPLCODE IN ($SPLCODECOL)";
                       		$ressqlq0_P	= $this->db->count_all($sqlq0_P);
							if($ressqlq0_P == 0)
							{
								$GTOTAL_P	= 0;
							}
							else
							{
								$sqlq1 		= "SELECT DPR_VOCCODE, DPR_PRJCODE, DPR_LPODESC, DPR_CSTCOST, DPR_TRXDATE, DPR_SPLCODE, 
													DPR_SPLDESC
												FROM
													tbl_dp_report
												WHERE
													DPR_CSTCOST != 0
													AND DPR_PRJCODE IN ($PRJCODECOL)
													AND DPR_SPLCODE IN ($SPLCODECOL)
													ORDER BY DPR_SPLCODE, DPR_CSTCOST DESC";
								$resq1 		= $this->db->query($sqlq1)->result();
							}
                        }
                        else 				// PER PROJECT AND ALL SUPPLIER
                        {
                        	$sqlq0_P 		= "tbl_dp_report
												WHERE DPR_CSTCOST != 0
													AND DPR_PRJCODE IN ($PRJCODECOL)";
                       		$ressqlq0_P	= $this->db->count_all($sqlq0_P);
							if($ressqlq0_P == 0)
							{
								$GTOTAL_P	= 0;
							}
							else
							{
								$sqlq1 		= "SELECT DPR_VOCCODE, DPR_PRJCODE, DPR_LPODESC, DPR_CSTCOST, DPR_TRXDATE, DPR_SPLCODE, 
													DPR_SPLDESC
												FROM
													tbl_dp_report
												WHERE DPR_CSTCOST != 0
													AND DPR_PRJCODE IN ($PRJCODECOL)
													ORDER BY DPR_SPLCODE, DPR_CSTCOST DESC";
								$resq1 		= $this->db->query($sqlq1)->result();
							}
						}
                    }
                    else 					// ALL PROJECT
                    {
                        if($countSPL > 2) 	// ALL PROJECT AND PER SUPPLIER
                        {
                            $sqlq0_P 		= "tbl_dp_report
												WHERE
													DPR_CSTCOST != 0
													AND DPR_SPLCODE IN ($SPLCODECOL)";
                       		$ressqlq0_P	= $this->db->count_all($sqlq0_P);
							
							if($ressqlq0_P == 0)
							{
								$GTOTAL_P	= 0;
							}
							else
							{
								$sqlq1 		= "SELECT DPR_VOCCODE, DPR_PRJCODE, DPR_LPODESC, DPR_CSTCOST, DPR_TRXDATE, DPR_SPLCODE, 
													DPR_SPLDESC
												FROM
													tbl_dp_report
												WHERE
													DPR_CSTCOST != 0
													AND DPR_SPLCODE IN ($SPLCODECOL)
													ORDER BY DPR_SPLCODE, DPR_CSTCOST DESC";
								$resq1 		= $this->db->query($sqlq1)->result();
							}
                        }
                        else 				// ALL PROJECT AND ALL SUPPLIER
                        {
                            $sqlq0_P 		= "tbl_dp_report
												WHERE DPR_CSTCOST != '0'";
                       		$ressqlq0_P	= $this->db->count_all($sqlq0_P);
							if($ressqlq0_P == 0)
							{
								$GTOTAL_P	= 0;
							}
							else
							{
								$sqlq1 		= "SELECT DPR_VOCCODE, DPR_PRJCODE, DPR_LPODESC, DPR_CSTCOST, DPR_TRXDATE, DPR_SPLCODE, 
													DPR_SPLDESC
												FROM
													tbl_dp_report
												WHERE DPR_CSTCOST != 0
													ORDER BY DPR_SPLCODE, DPR_CSTCOST DESC";
								$resq1 		= $this->db->query($sqlq1)->result();
							}
						}
                    }
                    
                    $SPLCODE2_P		= '';
					$GTOTAL_P		= 0;
					$GTOTALA_P		= 0;
					$GTOTALB_P		= 0;
					$GTOTALA_N		= 0;
					$GTOTALB_N		= 0;
					$SPLDESC		= '';
					$GREMAINP		= 0;
					$GREMAINN		= 0;
					if($ressqlq0_P > 0)
					{
						foreach($resq1 as $rowsqlq1) :
							$therow_P	= $therow_P + 1;
							$VOCCODE_P 	= $rowsqlq1->DPR_VOCCODE;
							$PJTCODE_P 	= $rowsqlq1->DPR_PRJCODE;
							$LPODESC_P 	= $rowsqlq1->DPR_LPODESC;
							$CSTCOST_P 	= $rowsqlq1->DPR_CSTCOST;
							$TRXDATE_P	= $rowsqlq1->DPR_TRXDATE;
							$SPLCODE_P	= $rowsqlq1->DPR_SPLCODE;
							$SPLDESC_P	= $rowsqlq1->DPR_SPLDESC;
							
							if($SPLCODE_P != $SPLCODE2_P)
							{
								$SPLDESCD	= " : $SPLDESC_P";
							}
							else
							{
								$SPLDESCD	= "";
							}
							
							if($therow_P > 1 && ($SPLCODE_P != $SPLCODE2_P))
							{
								$GREMAIN	= $GTOTALA_P + $GTOTALA_N;
								if($GREMAIN > 0)
								{
									$GREMAINP	= number_format($GREMAIN, $decFormat);
									$GREMAINN	= '';
								}
								else
								{
									$GREMAINP	= '';
									$GREMAINN	= number_format($GREMAIN, $decFormat);
								}
								?>
									<tr style="background:#CCCCCC">
										<td colspan="6" nowrap style="text-align:right; font-weight:bold;">T O T A L :</td>
										<td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTALA_P, $decFormat); ?></td>
										<td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTALA_N, $decFormat); ?></td>
									</tr>
									<tr style="background:#CCCCCC">
										<td colspan="6" nowrap style="text-align:right; font-weight:bold;">R E M A I N :</td>
										<td nowrap style="text-align:right; font-weight:bold"><?php echo $GREMAINP; ?></td>
										<td nowrap style="text-align:right; font-weight:bold"><?php echo $GREMAINN; ?></td>
									</tr>
								<?php
								$GTOTALA_P	= 0;
								$GTOTALA_N	= 0;
							}
							if($SPLCODE_P != '')
							{						
								if($CSTCOST_P > 0)
								{
									$GTOTALA_P	= $GTOTALA_P + $CSTCOST_P;
									
									?>
										<tr>
											<td nowrap style="text-align:left;"><?php echo "$therow_P"; ?>.</td>
											<td nowrap style="text-align:left;"><?php echo $PJTCODE_P; ?></td>
											<td nowrap style="text-align:left;"><?php echo $VOCCODE_P; ?></td>
											<td nowrap style="text-align:left;"><?php echo $TRXDATE_P; ?></td>
											<td nowrap style="text-align:left;"><?php echo "$SPLCODE_P$SPLDESCD"; ?></td>
											<td nowrap style="text-align:left;"><?php echo $LPODESC_P; ?></td>
											<td nowrap style="text-align:right;"><?php echo number_format($CSTCOST_P, $decFormat); ?></td>
											<td nowrap style="text-align:right;">&nbsp;</td>
										</tr>
									<?php
								}
								else
								{
									$GTOTALA_N	= $GTOTALA_N + $CSTCOST_P;
									?>
										<tr>
											<td nowrap style="text-align:left;"><?php echo "$therow_P"; ?>.</td>
											<td nowrap style="text-align:left;"><?php echo $PJTCODE_P; ?></td>
											<td nowrap style="text-align:left;"><?php echo $VOCCODE_P; ?></td>
											<td nowrap style="text-align:left;"><?php echo $TRXDATE_P; ?></td>
											<td nowrap style="text-align:left;"><?php echo "$SPLCODE_P$SPLDESCD"; ?></td>
											<td nowrap style="text-align:left;"><?php echo $LPODESC_P; ?></td>
											<td nowrap style="text-align:right;">&nbsp;</td>
											<td nowrap style="text-align:right;"><?php echo number_format($CSTCOST_P, $decFormat); ?></td>
										</tr>
									<?php
								}
							}
							$SPLCODE2_P	= $SPLCODE_P;
						endforeach;
						$GRANDTOT	= $GTOTALA_P + $GTOTALA_N;
						if($GRANDTOT == 0)
						{
							$GREMAINPB	= 0;
							$GREMAINNB	= 0;
						}
						elseif($GRANDTOT > 0)
						{
							$GREMAINPB	= $GRANDTOT;
							$GREMAINNB	= 0;
						}
						elseif($GRANDTOT < 0)
						{
							$GREMAINPB	= 0;
							$GREMAINNB	= $GRANDTOT;
						}
					}
					else
					{
						$GTOTALA_P	= 0;
						$GTOTALA_N	= 0;
						$GREMAINPB	= 0;
						$GREMAINNB	= 0;
						?>
                        <tr>
                            <td colspan="8" nowrap style="text-align:center; font-weight:bold; font-style:italic">no data found</td>
                        </tr>
                        <?php
					}
					?>
                <tr style="background:#CCCCCC">
                    <td colspan="6" nowrap style="text-align:right; font-weight:bold;">T O T A L : </td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTALA_P, $decFormat); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTALA_N, $decFormat); ?></td>
                </tr>
                <tr style="background:#CCCCCC">
                    <td colspan="6" nowrap style="text-align:right; font-weight:bold;">R E M A I N 1:</td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GREMAINPB, $decFormat); ?></td>
                    <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GREMAINNB, $decFormat); ?></td>
                </tr>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>