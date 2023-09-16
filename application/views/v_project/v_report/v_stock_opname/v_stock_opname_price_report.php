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

$PRJNAME	= '';
$PRJCOST	= 0;
$sqlPRJ		= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJNAME	= $rowPRJ->PRJNAME;
	$PRJCOST	= $rowPRJ->PRJCOST;
endforeach;


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
	if($TranslCode == 'Amount')$Amount = $LangTransl;
	if($TranslCode == 'Stock')$Stock = $LangTransl;
	if($TranslCode == 'Volume')$Volume = $LangTransl;
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
                <tr style="text-align:left; font-style:italic;">
                    <td width="8%" nowrap valign="top"><?php echo $Periode; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo date('d-m-Y', strtotime($Start_Date));?>  S/D <?php echo date('d-m-Y', strtotime($End_Date));?></td>
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
                  <td width="8%" colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Budget; ?></td>
                  <td width="8%" rowspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Requested; ?></td>
                  <td width="8%" colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Ordered; ?></td>
                  <td width="7%" colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Received; ?></td>
                  <td width="7%" colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Used; ?></td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo "Sisa Anggaran"; ?></td>
                  <td colspan="2" nowrap style="text-align:center; font-weight:bold"><?php echo $Stock; ?></td>
              </tr>
              <tr style="background:#CCCCCC">
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Volume; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Volume; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Volume; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Volume; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Volume; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Volume; ?></td>
                    <td nowrap style="text-align:center; font-weight:bold"><?php echo $Amount; ?></td>
              </tr>
              <?php
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			  		$addQITM 	= "";
					if($ITM_CODE[0] != 1) $addQITM = "AND A.ITM_CODE = '$ITM_CODE'";

                    /*$sqlStock     = "SELECT ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
                                    ITM_VOLMBG, ITM_VOLM, ITM_PRICE, ITM_TOTALP
                                    AMD_VOL, AMD_VAL, AMD_VOL_R, AMD_VAL_R, AMDM_VOL, AMDM_VAL,
                                    PR_VOL, PR_VAL, PR_VOL_R, PR_VAL_R, PR_CVOL, PR_CVAL,
                                    PO_VOL, PO_VAL, PO_VOL_R, PO_VAL_R, PO_CVOL, PO_CVAL, 
                                    IR_VOL, IR_VAL, IR_VOL_R, IR_VAL_R, 
                                    UM_VOL, UM_VAL, UM_VOL_R, UM_VAL_R
                                    FROM tbl_item_$PRJCODEVW
                                    WHERE ITM_GROUP IN ('M','T') $addQITM
                                    ORDER BY ITM_NAME";*/
                    $sqlStock   = "SELECT A.ITM_CODE, A.ITM_GROUP, B.ITM_NAME, A.ITM_UNIT,
                                    SUM(A.ITM_VOLM) AS ITM_VOLMBG, SUM(A.ITM_BUDG) AS ITM_TOTALP
                                    FROM tbl_joblist_detail_$PRJCODEVW A INNER JOIN tbl_item_$PRJCODEVW B ON A.ITM_CODE = B.ITM_CODE
                                    WHERE A.ITM_GROUP IN ('M','T') $addQITM
                                    AND A.ITM_CODE IN (SELECT DISTINCT ITM_CODE FROM tbl_ir_detail WHERE PRJCODE = '$PRJCODE' AND IR_STAT IN (3,6))
                                    GROUP BY A.ITM_CODE
                                    ORDER BY B.ITM_NAME";
                    $resStock   = $this->db->query($sqlStock);
                    if($resStock->num_rows() > 0)
                    {
                        $therow         = 0;
                        $GTREMR_VOLV    = 0;
                        $GTREMR_VALV    = 0;
                        $GITM_VAL2V     = 0;
                        $GPOT_VALV      = 0;
                        $GIRT_VALV      = 0;
                        $GUMT_VALV      = 0;
                        $GREMB_VALV      = 0;
                        $GGTREMR_VALV    = 0;
                        foreach($resStock->result() as $rowsqlq1):
                            $therow     = $therow + 1;
                            $ITM_CODE   = $rowsqlq1->ITM_CODE;
                            $ITM_GROUP  = $rowsqlq1->ITM_GROUP;
                            $ITM_DESC   = $rowsqlq1->ITM_NAME;
                            $ITM_UNIT   = $rowsqlq1->ITM_UNIT;
                            $ITM_VOLB   = $rowsqlq1->ITM_VOLMBG;
                            $ITM_VALB   = $rowsqlq1->ITM_TOTALP;

                            $s_isLS = "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
                            $r_isLS = $this->db->count_all($s_isLS);

                            $ITM_VOL2   = 0;
                            $ITM_VAL2   = 0;
                            $PRT_VOL    = 0;
                            $PRT_VAL    = 0;
                            $POT_VOL    = 0;
                            $POT_VAL    = 0;
                            $IRT_VOL    = 0;
                            $IRT_VAL    = 0;
                            $UMT_VOL    = 0;
                            $UMT_VAL    = 0;
                            $REMB_VOL   = 0;
                            $REMB_VAL   = 0;
                            $REMR_VOL   = 0;
                            $REMR_VAL   = 0;
                            $sNOW   = "SELECT   SUM(AMD_VOL - AMDM_VOL) AS AMD_VOL, SUM(AMD_VAL - AMDM_VAL) AS AMD_VAL,
                                                SUM(PR_VOL) AS PR_VOL, SUM(PR_VAL) AS PR_VAL, SUM(PR_VOL_R) AS PR_VOL_R, SUM(PR_VAL_R) AS PR_VAL_R,
                                                SUM(PR_CVOL) AS PR_CVOL, SUM(PR_CVAL) AS PR_CVAL,
                                                SUM(PO_VOL) AS PO_VOL, SUM(PO_VAL) AS PO_VAL, SUM(PO_VOL_R) AS PO_VOL_R, SUM(PO_VAL_R) AS PO_VAL_R,
                                                SUM(PO_CVOL) AS PO_CVOL, SUM(PO_CVAL) AS PO_CVAL,
                                                SUM(IR_VOL) AS IR_VOL, SUM(IR_VAL) AS IR_VAL, SUM(IR_VOL_R) AS IR_VOL_R, SUM(IR_VAL_R) AS IR_VAL_R,
                                                SUM(UM_VOL) AS UM_VOL, SUM(UM_VAL) AS UM_VAL, SUM(UM_VOL_R) AS UM_VOL_R, SUM(UM_VAL_R) AS UM_VAL_R,
                                                SUM(WO_VOL) AS WO_VOL, SUM(WO_VAL) AS WO_VAL, SUM(WO_VOL_R) AS WO_VOL_R, SUM(WO_VAL_R) AS WO_VAL_R,
                                                SUM(WO_CVOL) AS WO_CVOL, SUM(WO_CVAL) AS WO_CVAL,
                                                SUM(OPN_VOL) AS OPN_VOL, SUM(OPN_VAL) AS OPN_VAL, SUM(OPN_VOL_R) AS OPN_VOL_R, SUM(OPN_VAL_R) AS OPN_VAL_R,
                                                SUM(VCASH_VOL) AS VCASH_VOL, SUM(VCASH_VAL) AS VCASH_VAL, SUM(VCASH_VOL_R) AS VCASH_VOL_R, SUM(VCASH_VAL_R) AS VCASH_VAL_R,
                                                SUM(VLK_VOL) AS VLK_VOL, SUM(VLK_VAL) AS VLK_VAL, SUM(VLK_VOL_R) AS VLK_VOL_R, SUM(VLK_VAL_R) AS VLK_VAL_R,
                                                SUM(PPD_VOL) AS PPD_VOL, SUM(PPD_VAL) AS PPD_VAL, SUM(PPD_VOL_R) AS PPD_VOL_R, SUM(PPD_VAL_R) AS PPD_VAL_R
                                        FROM tbl_item_logbook_$PRJCODEVW
                                        WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND DOC_DATE BETWEEN '$Start_Date' AND '$End_Date'
                                        GROUP BY ITM_CODE";
                            $rNOW   = $this->db->query($sNOW);
                            foreach($rNOW->result() as $rwNOW):
                                $AMD_VOL    = $rwNOW->AMD_VOL;
                                $AMD_VAL    = $rwNOW->AMD_VAL;

                                $PR_VOL     = $rwNOW->PR_VOL;
                                $PR_VAL     = $rwNOW->PR_VAL;
                                $PR_VOL_R   = $rwNOW->PR_VOL_R;
                                $PR_VAL_R   = $rwNOW->PR_VAL_R;
                                $PR_CVOL    = $rwNOW->PR_CVOL;
                                $PR_CVAL    = $rwNOW->PR_CVAL;

                                $PO_VOL     = $rwNOW->PO_VOL;
                                $PO_VAL     = $rwNOW->PO_VAL;
                                $PO_VOL_R   = $rwNOW->PO_VOL_R;
                                $PO_VAL_R   = $rwNOW->PO_VAL_R;
                                $PO_CVOL    = $rwNOW->PO_CVOL;
                                $PO_CVAL    = $rwNOW->PO_CVAL;

                                $IR_VOL     = $rwNOW->IR_VOL;
                                $IR_VAL     = $rwNOW->IR_VAL;
                                $IR_VOL_R   = $rwNOW->IR_VOL_R;
                                $IR_VAL_R   = $rwNOW->IR_VAL_R;

                                $UM_VOL     = $rwNOW->UM_VOL;
                                $UM_VAL     = $rwNOW->UM_VAL;
                                $UM_VOL_R   = $rwNOW->UM_VOL_R;
                                $UM_VAL_R   = $rwNOW->UM_VAL_R;

                                $WO_VOL     = $rwNOW->WO_VOL;
                                $WO_VAL     = $rwNOW->WO_VAL;
                                $WO_VOL_R   = $rwNOW->WO_VOL_R;
                                $WO_VAL_R   = $rwNOW->WO_VAL_R;
                                $WO_CVOL    = $rwNOW->WO_CVOL;
                                $WO_CVAL    = $rwNOW->WO_CVAL;

                                $OPN_VOL    = $rwNOW->OPN_VOL;
                                $OPN_VAL    = $rwNOW->OPN_VAL;
                                $OPN_VOL_R  = $rwNOW->OPN_VOL_R;
                                $OPN_VAL_R  = $rwNOW->OPN_VAL_R;

                                $VCASH_VOL  = $rwNOW->VCASH_VOL;
                                $VCASH_VAL  = $rwNOW->VCASH_VAL;
                                $VCASH_VOL_R= $rwNOW->VCASH_VOL_R;
                                $VCASH_VAL_R= $rwNOW->VCASH_VAL_R;

                                $VLK_VOL    = $rwNOW->VLK_VOL;
                                $VLK_VAL    = $rwNOW->VLK_VAL;
                                $VLK_VOL_R  = $rwNOW->VLK_VOL_R;
                                $VLK_VAL_R  = $rwNOW->VLK_VAL_R;

                                $PPD_VOL    = $rwNOW->PPD_VOL;
                                $PPD_VAL    = $rwNOW->PPD_VAL;
                                $PPD_VOL_R  = $rwNOW->PPD_VOL_R;
                                $PPD_VAL_R  = $rwNOW->PPD_VAL_R;

                                // AFTER ADDEUNDUM
                                    $ITM_VOL2   = $ITM_VOLB + $AMD_VOL;
                                    $ITM_VAL2   = $ITM_VALB + $AMD_VAL;

                                // TOTAL DIMINTA
                                    $PRT_VOL    = $PR_VOL+$PR_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$PR_CVOL-$WO_CVOL;
                                    $PRT_VAL    = $PO_VAL+$PO_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$PO_CVAL-$WO_CVAL;

                                // TOTAL DIPESAN
                                    $POT_VOL    = $PO_VOL+$PO_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$PO_CVOL-$WO_CVOL;
                                    $POT_VAL    = $PO_VAL+$PO_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$PO_CVAL-$WO_CVAL;

                                // TOTAL DITERIMA
                                    $IRT_VOL    = $IR_VOL+$IR_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$WO_CVOL;
                                    $IRT_VAL    = $IR_VAL+$IR_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$WO_CVAL;

                                // TOTAL DIGUNAKAN
                                    // $UMT_VOL    = $UM_VOL+$UM_VOL_R+$OPN_VOL+$OPN_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R;
                                    // $UMT_VAL    = $UM_VAL+$UM_VAL_R+$OPN_VAL+$OPN_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R;
                                /* 	Penggunaan saat new & confirm tidak dihitung karna harga item belum ada, 
                                    harga akan terbentuk saat proses approve penggunaan.
                                ------------------------update tgl. 28-Agust-2023 ---------------------------- */
                                    $UMT_VOL    = $UM_VOL+$UM_VOL_R+$OPN_VOL+$OPN_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R;
                                    $UMT_VAL    = $UM_VAL+$UM_VAL_R+$OPN_VAL+$OPN_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R;

                                // TOTAL SISA
                                    $REMB_VOL   = $ITM_VOL2 - $POT_VOL;
                                    $REMB_VAL   = $ITM_VAL2 - $POT_VAL;
                                    $REMR_VOL   = $IRT_VOL - $UMT_VOL;
                                    $REMR_VAL   = $IRT_VAL - $UMT_VAL;
                            endforeach;

                            $alrtStyl1      = "";
                            $alrtStyl2      = "";
                            $alrtStyl3      = "";
                            $alrtStyl4      = "";
                            $alrtStyl5      = "";
                            $alrtStyl6      = "";

                            // AFTER ADDEUNDUM
                                $ITM_VOL2V  = $ITM_VOL2;
                                $ITM_VAL2V  = $ITM_VAL2;

                            // TOTAL DIMINTA
                                $PRT_VOLV   = $PRT_VOL;
                                $PRT_VALV   = $PRT_VAL;

                            // TOTAL DIPEASN
                                $POT_VOLV   = $POT_VOL;
                                $POT_VALV   = $POT_VAL;

                            // TOTAL DITERIMA
                                $IRT_VOLV   = $IRT_VOL;
                                $IRT_VALV   = $IRT_VAL;

                            // TOTAL DIGUNAKAN
                                $UMT_VOLV   = $UMT_VOL;
                                $UMT_VALV   = $UMT_VAL;

                            // TOTAL SISA
                                $REMB_VOLV  = $REMB_VOL;
                                $REMB_VALV  = $REMB_VAL;
                                $REMR_VOLV  = $REMR_VOL;
                                $REMR_VALV  = $REMR_VAL;

                            // Grand TOTAL
                                $GTREMR_VOLV    = $GTREMR_VOLV + $REMR_VOLV;
                                $GTREMR_VALV    = $GTREMR_VALV + $REMR_VALV;

                            if($r_isLS == 0)                        // NOT LS
                            {
                                if(round($PRT_VOL, 2) > round($ITM_VOL2, 2))
                                {
                                    $alrtStyl1  = "background-color: gray;";
                                }
                                if(round($POT_VOL, 2) > round($PRT_VOL, 2))
                                {
                                    $alrtStyl2  = "background-color: gray;";
                                }
                                if(round($IRT_VOL, 2) > round($POT_VOL, 2))
                                {
                                    $alrtStyl3  = "background-color: gray;";
                                }
                                if(round($UMT_VOL, 2) > round($IRT_VOL, 2))
                                {
                                    $alrtStyl4  = "background-color: gray;";
                                }
                                if(round($REMB_VOL, 2) < 0)
                                {
                                    $alrtStyl5  = "background-color: gray;";
                                }
                                if(round($REMR_VOL, 2) < 0)
                                {
                                    $alrtStyl6  = "background-color: gray;";
                                }
                            }
                            else                                    // LS
                            {
                                if(round($PRT_VAL, 2) > round($ITM_VAL2, 2))
                                {
                                    $alrtStyl1  = "background-color: gray;";
                                }
                                if(round($POT_VAL, 2) > round($PRT_VAL, 2))
                                {
                                    $alrtStyl2  = "background-color: gray;";
                                }
                                if(round($IRT_VAL, 2) > round($POT_VAL, 2))
                                {
                                    $alrtStyl3  = "background-color: gray;";
                                }
                                if(round($UMT_VAL, 2) > round($IRT_VAL, 2))
                                {
                                    $alrtStyl4  = "background-color: gray;";
                                }
                                if(round($REMB_VAL, 2) < 0)
                                {
                                    $alrtStyl5  = "background-color: gray;";
                                }
                                if(round($REMR_VAL, 2) < 0)
                                {
                                    $alrtStyl6  = "background-color: gray;";
                                }
                            }

                            $vwPRD      = "$ITM_CODE~$PRJCODE~PR~$r_isLS~$Start_Date~$End_Date";
                            $vwPOD      = "$ITM_CODE~$PRJCODE~PO~$r_isLS~$Start_Date~$End_Date";
                            $vwIRD      = "$ITM_CODE~$PRJCODE~IR~$r_isLS~$Start_Date~$End_Date";
                            $vwUMD      = "$ITM_CODE~$PRJCODE~UM~$r_isLS~$Start_Date~$End_Date";
                            $secvwPRD   = site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwPRD));
                            $secvwPOD   = site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwPOD));
                            $secvwIRD   = site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwIRD));
                            $secvwUMD   = site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwUMD));
                            ?>
								<tr>
									<?php // echo number_format($HRDOCCOST, $decFormat); ?>
									<td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
									<td nowrap style="text-align:left;"><?php echo "$ITM_CODE"; ?></td>
									<td width="30%" nowrap style="text-align:left;"><?php echo $ITM_DESC; ?></td>
									<td width="4%" nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOL2,$decFormat);?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($ITM_VAL2,$decFormat);?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($PRT_VOL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($POT_VOL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($POT_VAL,$decFormat); ?></td>
									<td width="7%" nowrap style="text-align:right;"><?php echo number_format($IRT_VOL,$decFormat); ?></td>
									<td width="7%" nowrap style="text-align:right;"><?php echo number_format($IRT_VAL,$decFormat); ?></td>
									<td width="7%" nowrap style="text-align:right;"><?php echo number_format($UMT_VOL,$decFormat); ?></td>
									<td width="7%" nowrap style="text-align:right;"><?php echo number_format($UMT_VAL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($REMB_VOL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($REMB_VAL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($REMR_VOL,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right;"><?php echo number_format($REMR_VAL,$decFormat); ?></td>
								</tr>
							<?php
                            $GITM_VAL2V     = $GITM_VAL2V + $ITM_VAL2V;
                            $GPOT_VALV      = $GPOT_VALV + $POT_VALV;
                            $GIRT_VALV      = $GIRT_VALV + $IRT_VALV;
                            $GUMT_VALV      = $GUMT_VALV + $UMT_VALV;
                            $GREMB_VALV     = $GREMB_VALV + $REMB_VALV;
                            $GGTREMR_VALV   = $GGTREMR_VALV + $REMR_VAL;
						endforeach;

                        ?>
                        <tr>
                            <td colspan="4" nowrap style="text-align:right; font-weight:800;">TOTAL</td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format($GITM_VAL2V,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format($GPOT_VALV,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format($GIRT_VALV,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format($GUMT_VALV,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format($GREMB_VALV,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format(0,$decFormat); ?></td>
                            <td nowrap style="text-align:right; font-weight:800;"><?php echo number_format($GGTREMR_VALV,$decFormat); ?></td>
                        </tr>
                        <?php
					}
					else
					{
						?>
						<tr>
						  <td colspan="17" nowrap style="text-align:center;">--- none ---</td>
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
