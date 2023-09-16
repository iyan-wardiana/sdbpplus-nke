<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 22 Maret 2019
 * File Name  = v_itembudet_report.php
 * Location   = -
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
$DefEmp_ID  = $this->session->userdata['Emp_ID'];

$PRJCOST  = 0;
$PRJNAME  = '';
$PRJDATE  = date('Y/m/d');
$PRJDATE_CO = date('Y/m/d');
$sqlPRJ   = "SELECT PRJDATE, PRJNAME, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
$resPRJ   = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
  $PRJCOST  = $rowPRJ->PRJCOST;
  $PRJNAME  = $rowPRJ->PRJNAME;
  $PRJDATE  = date('Y/m/d', strtotime($rowPRJ->PRJDATE));
  $PRJDATE_CO = date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;

$ITM_NAME = '';
$sqlITM   = "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
$resITM   = $this->db->query($sqlITM)->result();
foreach($resITM as $rowITM):
  $ITM_NAME = $rowITM->ITM_NAME;
endforeach;

function cut_text($var, $len = 200, $txt_titik = "-") 
{
  $var1 = explode("</p>",$var);
  $var  = $var1[0];
  if (strlen ($var) < $len) 
  { 
    return $var; 
  }
  if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
  {
    return $match [1] . $txt_titik;
  }
  else
  {
    return substr ($var, 0, $len) . $txt_titik;
  }
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
        <td colspan="2" class="style2" style="text-align:left; font-weight:bold; text-transform:uppercase; font-size:20px"><?php //echo $h1_title; ?>
        </td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:16px"><?php /*?>&nbsp;Periode : <?php */?>
        <?php 
        /*$CRDate = date_create($End_Date);
        echo date_format($CRDate,"d-m-Y");*/
        $StartDate  = date('d-m-Y');
        //echo "$StartDate";
      ?></td>
    </tr>
    <tr>
        <td colspan="2" class="style2" style="text-align:center; font-style:italic">&nbsp;</td>
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
                    <td width="8%" nowrap valign="top">NAMA LAPORAN</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$h1_title"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">PERIODE LAPORAN</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$datePeriod"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA ITEM</td>
                  <td>:</td>
                  <td><?php echo "$ITM_CODE - $ITM_NAME"; ?></td>
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
                  <th width="1%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                  <th colspan="5" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PERMINTAAN (SPP)</th>
                  <th colspan="7" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PEMBELIAN (PO)</th>
                  <th colspan="5" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">PENERIMAAN (LPM)</th>
                  <th colspan="7" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-color:#000">SPK</th>
                  <th colspan="7" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000; border-left-width:2px; border-left-color:#000;">OPNAME</th>
              </tr>
                <tr style="background:#CCCCCC">
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KODE</th>
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TANGGAL</th>
                  <th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KETERANGAN</th>
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">VOLUME</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">SATUAN</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KODE</th>
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TANGGAL</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">KETERANGAN</th>
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">VOLUME</th>
                  <th width="5%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">SATUAN</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">HARGA</th>
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">KODE</th>
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">TANGAL</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">KETERANGAN</th>
                  <th width="7%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">VOLUME</th>
                  <th width="7%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">SATUAN</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">KODE</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">TANGGAL</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">KETERANGAN</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">VOLUME</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">SATUAN</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">HARGA</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">KODE</th>
                  <th width="2%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">TANGGAL</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">KETERANGAN</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">VOLUME</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">SATUAN</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">HARGA</th>
                  <th width="4%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">JUMLAH</th>
                </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="5" nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="4" nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="6" nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="7" nowrap style="text-align:center;border:none">&nbsp;</td>
               </tr>
               <?php
            $theRow   = 0;
            $sqlAMC   = "tbl_pr_detail A WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'";
          $resAMC   = $this->db->count_all($sqlAMC);
          if($resAMC > 0)
          {
            $PR_CODE2 = '';
            $PO_CODE2 = '';
            $IR_CODE2 = '';
            $sqlITMH  = "SELECT A.PR_CODE, A.PR_DATE, A.ITM_CODE, A.PR_VOLM, A.ITM_UNIT, A.PR_DESC,
                      B.PO_NUM, B.PO_CODE, B.PO_DATE, B.PO_VOLM, B.PO_PRICE, B.PO_COST, B.PO_DESC,
                      C.IR_NUM, C.IR_CODE, C.IR_DATE, C.ITM_QTY, C.ITM_QTY_BONUS
                    FROM tbl_pr_detail A
                    LEFT JOIN tbl_po_detail B ON A.PR_NUM = B.PR_NUM
                      AND A.PR_ID = B.PRD_ID AND B.PRJCODE = '$PRJCODE' AND B.ITM_CODE = '$ITM_CODE'
                    LEFT JOIN tbl_ir_detail C ON B.PO_NUM = C.PO_NUM
                      AND C.PRJCODE = '$PRJCODE' AND C.ITM_CODE = '$ITM_CODE'
                    WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CODE = '$ITM_CODE'
                    ORDER BY A.PR_DATE, A.PR_CODE";
            $resITMH  = $this->db->query($sqlITMH)->result();
            foreach($resITMH as $rowITMH):
              $theRow   = $theRow + 1;
              $PR_CODE  = $rowITMH->PR_CODE;
              $PR_DATE  = $rowITMH->PR_DATE;
              $ITM_CODE = $rowITMH->ITM_CODE;
              $PR_VOLM  = $rowITMH->PR_VOLM;
              $ITM_UNIT = $rowITMH->ITM_UNIT;
              $PR_DESC  = $rowITMH->PR_DESC;
              $PO_NUM   = $rowITMH->PO_NUM;
              $PO_CODE  = $rowITMH->PO_CODE;
              $PO_DATE  = $rowITMH->PO_DATE;
              $PO_VOLM  = $rowITMH->PO_VOLM;
              $PO_PRICE = $rowITMH->PO_PRICE;
              $PO_COST  = $rowITMH->PO_COST;
              $PO_DESC  = $rowITMH->PO_DESC;
              $IR_NUM   = $rowITMH->IR_NUM;
              $IR_CODE  = $rowITMH->IR_CODE;
              $IR_DATE  = $rowITMH->IR_DATE;
              $IR_DESC  = '';
              $ITM_QTY  = $rowITMH->ITM_QTY;
              $ITM_BONUS  = $rowITMH->ITM_QTY_BONUS;  
              $TITM_QTY = $ITM_QTY + $ITM_BONUS;
            ?>
              <tr>
                                <td nowrap style="text-align:center;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;"><?php echo $theRow; ?></td>
                                <td nowrap style="text-align:left;">
                  <?php if($PR_CODE != $PR_CODE2) { echo $PR_CODE; } ?>
                                </td>
                                <td nowrap style="text-align:center;"><?php echo $PR_DATE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $PR_DESC; ?></td>
                                <td nowrap style="text-align:right"><?php echo number_format($PR_VOLM,2); ?></td>
                                <td nowrap style="text-align:center; border-right-color:#000; border-right-width:2px;">
                  <?php echo $ITM_UNIT; ?>
                                </td>
                                <td nowrap style="text-align:left;">
                                    <?php if($PO_CODE != $PO_CODE2) { echo $PO_CODE; } ?>
                                </td>
                                <td nowrap style="text-align:center;"><?php echo $PO_DATE; ?></td>
                                <td nowrap style="text-align:left;"><?php echo $PO_DESC; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($PO_VOLM, 2); ?></td>
                                <td nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($PO_PRICE, 2); ?></td>
                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
                  <?php echo number_format($PO_COST, 2); ?>
                                </td>
                                <td nowrap style="text-align:left;">
                                    <?php if($IR_CODE != $IR_CODE2) { echo $IR_CODE; } ?>
                                </td>
                                <td nowrap style="text-align:center;"><?php echo $IR_DATE; ?></td>
                                <td nowrap style="text-align:right;"><?php echo $IR_DESC; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($ITM_QTY, 2); ?></td>
                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">
                                  <?php echo number_format($TITM_QTY,2); ?>
                                </td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right;">&nbsp;</td>
                                <td nowrap style="text-align:right; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                            </tr>
                    <?php
            $PR_CODE2 = $PR_CODE;
            $PO_CODE2 = $PO_CODE;
            $IR_CODE2 = $IR_CODE;
            endforeach;
          }
          else
          {
            ?>
                            <tr>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-color:#000; border-right-width:2px;">&nbsp;</td>
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