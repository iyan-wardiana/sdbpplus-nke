<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Agustus 2018
 * File Name	= v_itemusage_report.php
 * Location		= -
*/

if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
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
            <td width="16%">
                <div id="Layer1">
                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                    <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                    <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
            <td width="45%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
            <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        </tr>
        <tr>
            <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
            <td class="style2">&nbsp;</td>
            <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" width="150" height="70"></td>
            <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px"><?php echo $h1_title; ?></td>
        </tr>
    	<tr>
            <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span></td>
        </tr>
            <?php
                $StartDate1 = date('d/m/Y',strtotime($Start_Date));
                $EndDate1 	= date('d/m/Y',strtotime($End_Date));
                //n$DOCTYPE1 = $TYPE;
                //if($TYPE==1)
                //	$DOCTYPE1 = "Asli";
                //elseif($TYPE==2)
                //	$DOCTYPE1 = "Copy";
                //else
                //	$DOCTYPE1 = "All";			
            ?>
        <tr>
            <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        </tr>
    	<tr>
            <td colspan="3" class="style2" style="text-align:left; font-style:italic">
                <table width="100%">
                    <!--<tr style="text-align:left; font-style:italic">
                        <td width="8%" nowrap valign="top">Type Dokumen</td>
                        <td width="1%">:</td>
                        <td width="91%"><?php// echo $DOCTYPE1; ?></td>
                    </tr>-->
                    <tr style="text-align:left; font-style:italic">
                        <td width="8%" nowrap valign="top">Proyek</td>
                        <td width="1%">:</td>
                        <td width="91%"><?php echo "$PRJCODECOLLD - $PRJNAMECOLLD"; ?></span></td>
                    </tr>
                    <tr style="text-align:left; font-style:italic">
                      <td nowrap valign="top">Periode</td>
                      <td>:</td>
                      <td><span class="style2" style="text-align:left; font-style:italic"><?php echo "$StartDate1 s.d. $EndDate1";?></span></td>
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
                  <td width="3%" height="30" nowrap style="text-align:center; font-weight:bold">NO</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                  <td width="9%" nowrap style="text-align:center; font-weight:bold">KODE ITEM</td>
                  <td width="26%" nowrap style="text-align:center; font-weight:bold">NAMA ITEM</td>
                  <td width="35%" nowrap style="text-align:center; font-weight:bold">PEKERJAAN</td>
                  <td width="6%" nowrap style="text-align:center; font-weight:bold">SATUAN</td>
                  <td width="9%" nowrap style="text-align:center; font-weight:bold">JUMLAH</td>
                  <td width="4%" nowrap style="text-align:center; font-weight:bold">REF.</td>
              </tr>
                <?php						
					$therow		= 0;
                    $GTOTAL		= 0;
                    $GTOTALA	= 0;
                    $GTOTALB	= 0;
                    $noU		= 0;
                    $noUa		= 0;
                    $noUb		= 0;
					
					$sqlq1 		= "SELECT A.PRJCODE, A.UM_NUM, A.UM_CODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_QTY,
										B.UM_DATE, C.ITM_NAME, D.JOBPARENT
									FROM tbl_um_detail A
										INNER JOIN tbl_um_header B ON A.UM_NUM = B.UM_NUM
										INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
										INNER JOIN tbl_joblist D ON A.JOBCODEID = D.JOBCODEID
									WHERE B.UM_DATE >= '$Start_Date' AND B.UM_DATE <= '$End_Date'
										AND B.PRJCODE IN ($PRJCODECOL)
										AND A.ITM_CODE IN ($ITMSELCOL)
									ORDER BY B.UM_DATE";
                    $resq1 		= $this->db->query($sqlq1)->result();
						
                    foreach($resq1 as $rowIR) :
                        $therow			= $therow + 1;
                        $PRJCODE 		= $rowIR->PRJCODE;
                        $UM_NUM 		= $rowIR->UM_NUM;
                        $UM_DATE 		= $rowIR->UM_DATE;
                        $UM_CODE	 	= $rowIR->UM_CODE;
                        $ITM_CODE	 	= $rowIR->ITM_CODE;
                        $ITM_NAME	 	= $rowIR->ITM_NAME;
                        $ITM_UNIT	 	= $rowIR->ITM_UNIT;
                        $ITM_QTY		= $rowIR->ITM_QTY;
                        $JOBPARENT	 	= $rowIR->JOBPARENT;
						
						$JOBDESC		= '';
						$sqlq2 			= "SELECT JOBDESC 
											FROM tbl_joblist 
											WHERE JOBCODEID = '$JOBPARENT' 
												AND PRJCODE = '$PRJCODE'";													
                    	$resq2 		= $this->db->query($sqlq2)->result();
						foreach($resq2 as $row2):
							$JOBDESC	= $row2->JOBDESC;
						endforeach;
                        ?>
                            <tr>
                                <td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
                                <td nowrap style="text-align:center;"><?php echo $UM_DATE; ?></td>
                                <td nowrap style="text-align:center;"><?php echo $ITM_CODE; ?></td>
                                <td width="26%" nowrap style="text-align:left;"><?php echo $ITM_NAME; ?></td>
                                <td width="35%" nowrap style="text-align:left;"><?php echo $JOBDESC; ?></td>
                              	<td width="6%" nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
                              	<td width="9%" nowrap style="text-align:right;">
									<?php echo number_format($ITM_QTY, 2); ?>&nbsp;
                                </td>
                              	<td width="4%" nowrap style="text-align:left;"><?php echo $UM_CODE; ?></td>
                           	</tr>
                        <?php
                    endforeach;
                ?>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>