<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= v_stock_opname_report_sum.php
 * Location		= -
*/

/*
 * Author		= Hendar Permana
 * Edit Date	= 16 Agustius 2017
 * File Name	= v_stock_opname_report_adm.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

date_default_timezone_set("Asia/Jakarta");
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 2;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
/*if($viewProj == 0) // SELECTED PROJECT
{
	if($TOTPROJ == 1)
	{
		$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
						WHERE A.PRJCODE = '$PRJCODE'
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
						WHERE A.PRJCODE = '$PRJCODE'
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
}*/

$PRJNAME	= '';
$PRJCOST	= 0;
$sqlPRJ		= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJNAME	= $rowPRJ->PRJNAME;
	$PRJCOST	= $rowPRJ->PRJCOST;
endforeach;

// $StartDate	= date('d M Y', strtotime($Start_Date));
// $EndDate	= date('d M Y', strtotime($End_Date));


$LangID 	= $this->session->userdata['LangID'];

$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl		= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;

	if($TranslCode == 'ReportName')$ReportName = $LangTransl;
	if($TranslCode == 'Periode')$Periode = $LangTransl;
	if($TranslCode == 'ProjectCode')$ProjectCode = $LangTransl;
	if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
	if($TranslCode == 'PrintDate')$PrintDate = $LangTransl;
	if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'Unit')$Unit = $LangTransl;
	if($TranslCode == 'Budget')$Budget = $LangTransl;
	if($TranslCode == 'Requested')$Requested = $LangTransl;
	if($TranslCode == 'Ordered')$Ordered = $LangTransl;
	if($TranslCode == 'Received')$Received = $LangTransl;
	if($TranslCode == 'Used')$Used = $LangTransl;
	if($TranslCode == 'Remain')$Remain = $LangTransl;
	if($TranslCode == 'Volume')$Volume = $LangTransl;
	if($TranslCode == 'Price')$Price = $LangTransl;
	if($TranslCode == 'Stock')$Stock = $LangTransl;
endforeach;

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
        <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; text-transform:uppercase; font-size:20px"><span class="style2" style="text-align:center; font-family:'Times New Roman', Times, serif; font-size:24px">Laporan Stock Opname</span></td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-size:12px">
			<span class="style2" style="text-align:center; font-family:'Times New Roman', Times, serif; font-size:18px"><?php echo $comp_name; ?></span>				
        </td>
    </tr>
        <?php
            //n$StartDate1 = date('Y/m/d',strtotime($Start_Date));
            //n$EndDate1 = date('Y/m/d',strtotime($End_Date));
			//n$DOCTYPE1 = $TYPE;
			//if($TYPE==1)
			//	$DOCTYPE1 = "Asli";
			//elseif($TYPE==2)
			//	$DOCTYPE1 = "Copy";
			//else
			//	$DOCTYPE1 = "All";
        ?>
    <tr>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
        <td class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%" style="font-weight:bold">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"></td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top"><?php echo $ReportName; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$h1_title"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic; display:none">
                    <td width="8%" nowrap valign="top"><?php echo $Periode; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php // echo "$StartDate s.d. $EndDate"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top"><?php echo $ProjectCode; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $ProjectName; ?></td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $PrintDate; ?></td>
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
                  <td rowspan="2" width="3%" nowrap style="text-align:center; font-weight:bold">NO</td>
                  <td rowspan="2" width="9%" nowrap style="text-align:center; font-weight:bold"><?php echo $ItemCode; ?></td>
                  <td rowspan="2" width="30%" nowrap style="text-align:center; font-weight:bold"><?php echo $Description; ?></td>
                  <td rowspan="2" width="4%" nowrap style="text-align:center; font-weight:bold"><?php echo $Unit; ?></td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Budget; ?></td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Requested; ?></td>
                  <td width="8%" colspan="1" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Ordered; ?></td>
                  <td width="7%" colspan="1" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Received; ?></td>
                  <td width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Used; ?></td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Remain; ?></td>
              </tr>
              <tr style="background:#CCCCCC">
                	<td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Budget; ?></td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Stock; ?></td>
              </tr>
              <?php
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			  		$addQITM 	= "";
					if($ITM_CODE[0] != 1) $addQITM = "AND ITM_CODE = '$ITM_CODE'";

					$sqlStock 	= "SELECT ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
									ITM_VOLMBG, ITM_VOLM, ITM_PRICE, ITM_TOTALP,
									AMD_VOL, AMD_VAL, AMD_VOL_R, AMD_VAL_R, AMDM_VOL, AMDM_VAL,
									PR_VOL, PR_VAL, PR_VOL_R, PR_VAL_R, PR_CVOL, PR_CVAL,
									PO_VOL, PO_VAL, PO_VOL_R, PO_VAL_R, PO_CVOL, PO_CVAL, 
									IR_VOL, IR_VAL, IR_VOL_R, IR_VAL_R, 
									UM_VOL, UM_VAL, UM_VOL_R, UM_VAL_R
									FROM tbl_item_$PRJCODEVW
									WHERE ITM_GROUP IN ('M','T') $addQITM
									ORDER BY ITM_NAME";
					$resStock 	= $this->db->query($sqlStock);
					if($resStock->num_rows() > 0)
					{
						$therow		= 0;
						$REM_BUD	= 0;
						$REM_STOK	= 0;
						foreach($resStock->result() as $rowsqlq1):
							$therow		= $therow + 1;
							$ITM_CODE	= $rowsqlq1->ITM_CODE;
							$ITM_DESC 	= $rowsqlq1->ITM_NAME;
							$ITM_UNIT	= $rowsqlq1->ITM_UNIT;
							$ITM_PRICE	= $rowsqlq1->ITM_PRICE;
							$ITM_VOLM	= $rowsqlq1->ITM_VOLM;
							$ITM_GROUP	= $rowsqlq1->ITM_GROUP;
							//$ITM_VOLMBG	= $ITM_VOLM + $ADD_VOLM;
							// $ITM_VOLMBG1= $rowsqlq1->ITM_VOLMBG;
							// $ITM_VOLMBG	= $ITM_VOLMBG1 + $ADD_VOLM;
							// $PR_VOLM	= $rowsqlq1->PR_VOLM;
							// $PR_AMOUNT	= $rowsqlq1->PR_AMOUNT;
							// $PO_VOLM	= $rowsqlq1->PO_VOLM;
							// $PO_AMOUNT	= $rowsqlq1->PO_AMOUNT;
							// $IR_VOLM	= $rowsqlq1->IR_VOLM;
							// $IR_AMOUNT	= $rowsqlq1->IR_AMOUNT;

							$ITM_VOLMBG1	= $rowsqlq1->ITM_VOLMBG;
							$ITM_BUDG1		= $rowsqlq1->ITM_TOTALP;
							$AMD_VOL 		= $rowsqlq1->AMD_VOL;
							$AMD_VAL		= $rowsqlq1->AMD_VAL;
							$AMD_VOL_R		= $rowsqlq1->AMD_VOL_R;
							$AMD_VAL_R		= $rowsqlq1->AMD_VAL_R;
							$AMDM_VOL		= $rowsqlq1->AMDM_VOL;
							$AMDM_VAL		= $rowsqlq1->AMDM_VAL;
							$AMDVOL 		= ($AMD_VOL + $AMD_VOL_R);
							$AMDVAL 		= ($AMD_VAL + $AMD_VAL_R);
							$ITM_VOLMBG		= $ITM_VOLMBG1 + $AMDVOL;
							$ITM_BUDG 		= $ITM_BUDG1 + $AMDVAL;

							$PR_VOL			= $rowsqlq1->PR_VOL;
							$PR_VAL			= $rowsqlq1->PR_VAL;
							$PR_VOL_R		= $rowsqlq1->PR_VOL_R;
							$PR_VAL_R		= $rowsqlq1->PR_VAL_R;
							$PR_CVOL		= $rowsqlq1->PR_CVOL;
							$PR_CVAL		= $rowsqlq1->PR_CVAL;
							$PRVOL 			= ($PR_VOL + $PR_VOL_R) - $PR_CVOL;
							$PRVAL 			= ($PR_VAL + $PR_VAL_R) - $PR_CVAL;

							$PO_VOL			= $rowsqlq1->PO_VOL;
							$PO_VAL			= $rowsqlq1->PO_VAL;
							$PO_VOL_R		= $rowsqlq1->PO_VOL_R;
							$PO_VAL_R		= $rowsqlq1->PO_VAL_R;
							$PO_CVOL		= $rowsqlq1->PO_CVOL;
							$PO_CVAL		= $rowsqlq1->PO_CVAL;
							$POVOL 			= ($PO_VOL + $PO_VOL_R) - $PO_CVOL;
							$POVAL 			= ($PO_VAL + $PO_VAL_R) - $PO_CVAL;

							$IR_VOL			= $rowsqlq1->IR_VOL;
							$IR_VAL			= $rowsqlq1->IR_VAL;
							$IR_VOL_R		= $rowsqlq1->IR_VOL_R;
							$IR_VAL_R		= $rowsqlq1->IR_VAL_R;
							$IRVOL 			= $IR_VOL + $IR_VOL_R;
							$IRVAL 			= $IR_VAL + $IR_VAL_R;

							$UM_VOL			= $rowsqlq1->UM_VOL;
							$UM_VAL			= $rowsqlq1->UM_VAL;
							$UM_VOL_R		= $rowsqlq1->UM_VOL_R;
							$UM_VAL_R		= $rowsqlq1->UM_VAL_R;
							$UMVOL 			= $UM_VOL + $UM_VOL_R;
							$UMVAL 			= $UM_VAL + $UM_VAL_R;

							$REM_BUD		= $ITM_VOLMBG - $IRVOL;
							$REM_STOK		= $IRVOL - $UMVOL;

							?>
								<tr>
									<?php // echo number_format($HRDOCCOST, $decFormat); ?>
									<td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
									<td nowrap style="text-align:left;"><?php echo "$ITM_CODE"; ?></td>
									<td width="30%" nowrap style="text-align:left;"><?php echo $ITM_DESC; ?></td>
									<td width="4%" nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOLMBG,$decFormat);?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($PRVOL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($POVOL,$decFormat); ?></td>
									<td width="7%" nowrap style="text-align:right;"><?php echo number_format($IRVOL,$decFormat); ?></td>
									<td width="7%" nowrap style="text-align:right;"><?php echo number_format($UMVOL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($REM_BUD,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($REM_STOK,$decFormat); ?></td>
								</tr>
							<?php
						endforeach;
					}
					else
					{
						?>
						<tr>
						  <td colspan="13" nowrap style="text-align:center;">--- none ---</td>
						</tr>
                        <?php
					}
				?>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>
