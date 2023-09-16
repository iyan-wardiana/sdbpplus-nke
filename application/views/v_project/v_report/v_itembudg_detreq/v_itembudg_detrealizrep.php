<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 22 Maret 2019
 * File Name  = v_itembudet_report.php
 * Location   = -
*/

if($viewType == 1)
{
    $repDate    = date('ymdHis');
    $fileNm     = "ItemBudgetDet_".$repDate;
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$fileNm.xls");
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
// $sqlITM   = "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' LIMIT 1";
// $resITM   = $this->db->query($sqlITM)->result();
// foreach($resITM as $rowITM):
//   $ITM_NAME = $rowITM->ITM_NAME;
// endforeach;

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
    <style type="text/css">
        body { 
            /*margin: 0;*/
            padding: 0;
            background-color: #FAFAFA;
            font: 10pt Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 400mm;
            /*min-height: 296mm;*/
            padding-left: 0.5cm;
            padding-right: 0.5cm;
            padding-top: 0.5cm;
            padding-bottom: 0.5cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            /* background-size: 400px 200px !important;*/
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        @page {
            /*size: auto;*/
            size: lanscape;
            /* margin: 0; */
        }

        @media print {

            @page{size: lanscape;}
            .page {
                margin: 0;
                /* border: initial; */
                /* border-radius: initial; */
                width: initial;
                min-height: initial;
                box-shadow: initial;
                /* background: initial; */
                page-break-after: always;
            }
        }

        .header_title table tr td {
            border: hidden;
        }

        table thead th {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-right: 1px dashed black;
            /*font-weight: bold;*/
        }
        table tr td {
            border-top: 1px solid lightgray;
            border-bottom: 1px solid lightgray;
            border-right: 1px dashed lightgray;
            /*font-weight: bold;*/
        }

        .pBreak {
            padding-top: 10px;
        }

        .header_content {
            padding-top: 20px;
        }

        /* .header_content .col-8 {
            float: left;
            width: 60%;
        } */

        .header_content .col-12 table tr th {
            border: hidden;
        }

        .header_content .col-4 {
            float: left;
            width: 40%;
            padding-top: 70px;
        }

        .header_content .col-4 table tr th {
            border: 1px solid;
        }

        #sub_total {
            background-color: rgba(181, 178, 177, 0.5);
        }

        #sub_total table  tr td {
            border: hidden;
        }

        #grand_total {
            background-color: rgba(181, 178, 177, 0.3);
        }

        #grand_total table  tr td {
            border: hidden;
        }

        #Layer1 {
            position: absolute;
            top: 30px;
            left: 50px;
        }
    </style>
</head>
<body style="overflow:auto">
    <div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>                
        </div>            
        <section class="content">
            <div class="header_title">
                <table width="100%" border="0" style="size:auto">
                    <tr>
                        <td width="50" rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" width="180"></td>
                        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px">
                            LAPORAN DETIL REQUEST <span style="font-size: 10pt; display:block; padding-top:5px;">PERIODE: <?php echo date('d-m-Y', strtotime($Start_Date));?>  S/D <?php echo date('d-m-Y', strtotime($End_Date));?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold; font-size:16px"><?php /*?>&nbsp;Periode : <?php */?>
                        <?php 
                        /*$CRDate = date_create($End_Date);
                        echo date_format($CRDate,"d-m-Y");*/
                        $StartDate  = date('d-m-Y');
                        //echo "$StartDate";
                    ?></td>
                    </tr>
                </table>
            </div>
            <?php
            // GET JOBPARENT
                $PRJCODEVW  = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

                $addQJOBPAR = "";
                if($JOBPARENT[0] != '1')
                {
                    $joinJOBPAR     = join("','", $JOBPARENT);
                    $addQJOBPAR     = "AND JOBCODEID IN ('$joinJOBPAR')";
                }

                $JOBCODEID  = [];
                $getJOBPAR  = "SELECT JOBCODEID FROM tbl_joblist_detail_$PRJCODEVW WHERE ISLASTH = 1 $addQJOBPAR";
                $resJOBPAR  = $this->db->query($getJOBPAR);
                if($resJOBPAR->num_rows() > 0)
                {
                    foreach($resJOBPAR->result() as $rJOB):
                        $JOBCODEID[] = $rJOB->JOBCODEID;
                    endforeach;
                }

                $JoinJOBPAR     = join("','", $JOBCODEID);

                // $addQITM        = "";
                // if($ITM_CODE[0] != 1)
                // {
                //     $JoinITMCODE    = join("','", $ITM_CODE);
                //     $addQITM        = "AND ITM_CODE IN ('$JoinITMCODE')";
                // }

            // cek LS & friends
                $UNIT_CHK = [];
                $getLS = "SELECT ITM_UNIT FROM tbl_unitls";
                $resLS = $this->db->query($getLS);
                if($resLS->num_rows() > 0)
                {
                    foreach($resLS->result() as $rLS):
                        $UNIT_CHK[] = $rLS->ITM_UNIT;
                    endforeach;
                }

                $getBUD_ITM     = "SELECT JOBCODEID, JOBPARENT, JOBDESC, ITM_CODE, ITM_UNIT, ITM_VOLM, ITM_BUDG, AMD_VOL, AMD_VAL, AMDM_VOL, AMDM_VAL, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail_$PRJCODEVW
                                    WHERE ISLAST = 1 AND ITM_CODE = '$ITM_CODE' AND JOBPARENT IN ('$JoinJOBPAR')";
                $resBUD_ITM     = $this->db->query($getBUD_ITM);
                $ITM_VOLM2      = 0;
                $ITM_BUDG2      = 0;
                $GTOT_ITMUSEDAM = 0;
                if($resBUD_ITM->num_rows() > 0)
                {
                    $SUBTOT_ITMUSEDAM = 0;
                    foreach($resBUD_ITM->result() as $rB):
                        $JOBCODEID      = $rB->JOBCODEID;
                        $JOBPARENT      = $rB->JOBPARENT;
                        $JOBDESC        = $rB->JOBDESC;
                        $ITM_CODE       = $rB->ITM_CODE;
                        $ITM_UNIT       = $rB->ITM_UNIT;
                        $ITM_VOLMBG     = $rB->ITM_VOLM;
                        $ITM_BUDG       = $rB->ITM_BUDG;
                        $ADD_VOLM       = $rB->AMD_VOL;
                        $ADD_JOBCOST    = $rB->AMD_VAL;
                        $ADDM_VOLM      = $rB->AMDM_VOL;
                        $ADDM_JOBCOST   = $rB->AMDM_VAL;

                        // Addendum
                            $ADDVOLM        = $ADD_VOLM - $ADDM_VOLM;
                            $ADD_TOTAL      = $ADD_JOBCOST - $ADDM_JOBCOST;

                        // after addendum
                            $ITM_VOLMBG2    = $ITM_VOLMBG + $ADDVOLM;
                            $ITM_BUDG2      = $ITM_BUDG + $ADD_TOTAL;

                            // get Item name
                            $resITM        = $this->db->select("ITM_CODE, ITM_NAME, ITM_UNIT")->get_where("tbl_item", ["PRJCODE" => $PRJCODE, "ITM_CODE" => $ITM_CODE]);
                            if($resITM->num_rows() > 0)
                            {
                                foreach($resITM->result() as $r):
                                    $ITM_CODE    = $r->ITM_CODE;
                                    $ITM_NAME    = $r->ITM_NAME;
                                    $ITM_UNIT    = $r->ITM_UNIT;
                                endforeach;
                            }
                            
                        // get Induk pekerjaan
                            $resJOBH        = $this->db->select("JOBCODEID, JOBDESC")->get_where("tbl_joblist", ["PRJCODE" => $PRJCODE, "JOBCODEID" => $JOBPARENT]);
                            if($resJOBH->num_rows() > 0)
                            {
                                foreach($resJOBH->result() as $rJH):
                                    $JOBCODEID_H    = $rJH->JOBCODEID;
                                    $JOBDESC_H      = $rJH->JOBDESC;
                                endforeach;
                            }
                            
                        $REM_VOLMBG     = $ITM_VOLMBG2;
                        $REM_BUDG       = $ITM_BUDG2;

                        ?>
                            <div class="header_content">
                                <div class="col-12">
                                    <table width="100%" border="0" style="size:auto">
                                        <thead>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <th width="150" nowrap valign="top">NAMA LAPORAN</th>
                                                <th width="10">:</th>
                                                <th><?php // echo "$h1_title"; ?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <th width="150" nowrap valign="top">PERIODE LAPORAN</th>
                                                <th width="10">:</th>
                                                <th><?php echo "$datePeriod"; ?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Kode Proyek</th>
                                                <th width="10">:</th>
                                                <th><?php echo "$PRJCODE"; ?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Nama Proyek</th>
                                                <th width="10">:</th>
                                                <th><?php echo $PRJNAME;?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Kode Induk Pekerjaan</th>
                                                <th width="10">:</th>
                                                <th><?php echo "$JOBCODEID_H"; ?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Nama Induk Pekerjaan</th>
                                                <th width="10">:</th>
                                                <th><?php echo $JOBDESC_H;?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Kode Item</th>
                                                <th width="10">:</th>
                                                <th><?php echo "$JOBCODEID"; ?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Nama Item</th>
                                                <th width="10">:</th>
                                                <th><?php echo $JOBDESC;?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">Satuan</th>
                                                <th width="10">:</th>
                                                <th><?php echo $ITM_UNIT;?></th>
                                            </tr>
                                            <tr style="text-align:left; font-style:italic">
                                                <th width="150" nowrap valign="top">TANGGAL CETAK</th>
                                                <th width="10">:</th>
                                                <th><?php echo date('Y-m-d:H:i:s'); ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        <?php

                        // START DETIL LPM  => REALISASI => OVERHEAD: ITM_GROUP NOT IN ('M','T')
                            /* ---- Hidden => 15-03-2023
                            $TOTLPM_ITMUSEDAM    = 0;
                            $no = 0;
                            $getDataIR      = "SELECT A.*,
                                                B.IR_CODE, B.IR_STAT, B.STATDESC, B.SPLCODE, B.SPLDESC, B.PO_CODE
                                                FROM tbl_ir_detail A
                                                INNER JOIN tbl_ir_header B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
                                                INNER JOIN tbl_item_$PRJCODEVW C ON C.ITM_CODE = A.ITM_CODE
                                                WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.IR_STAT NOT IN (5,9)
                                                AND A.ITM_CODE = '$ITM_CODE' AND (B.IR_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                                AND C.ITM_GROUP NOT IN ('M','T')
                                                ORDER BY B.IR_CODE, B.IR_DATE ASC";
                            $resDataIR      = $this->db->query($getDataIR);
                            if($resDataIR->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI LPM</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="100" style="font-style: italic; text-align: center;">REF.</th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                foreach($resDataIR->result() as $rIR):
                                                    $no         = $no + 1;
                                                    $IR_NUM     = $rIR->IR_NUM;
                                                    $IR_CODE    = $rIR->IR_CODE;
                                                    $PO_CODE    = $rIR->PO_CODE;
                                                    $SPLCODE    = $rIR->SPLCODE;
                                                    $SPLDESC    = $rIR->SPLDESC;
                                                    $IR_DATE    = $rIR->IR_DATE;
                                                    $PRJCODE    = $rIR->PRJCODE;
                                                    $JOBCODEID  = $rIR->JOBCODEID;
                                                    $JOBPARDESC = $rIR->JOBPARDESC;
                                                    $ITM_CODE   = $rIR->ITM_CODE;
                                                    $ITM_UNIT   = $rIR->ITM_UNIT;
                                                    $ITM_QTY    = $rIR->ITM_QTY;
                                                    $ITM_PRICE  = $rIR->ITM_PRICE;
                                                    $ITM_TOTAL  = $rIR->ITM_TOTAL;
                                                    $POD_ID     = $rIR->POD_ID;
                                                    $NOTES      = $rIR->NOTES;
                                                    $IR_STAT    = $rIR->IR_STAT;
                                                    $STATDESC   = $rIR->STATDESC;

                                                    $Trans_Type         = "LPM";

                                                    $TOTLPM_ITMUSEDAM      = $TOTLPM_ITMUSEDAM + $ITM_TOTAL;

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($IR_DATE)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $IR_CODE; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $PO_CODE; ?></td>
                                                            <td><?php echo "$NOTES"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_QTY, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_TOTAL, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                    <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                        <td colspan="9" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTLPM_ITMUSEDAM, 2); ?></td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            ------------- End Hidden --------- */
                        // END DETIL LPM    => REALISASI

                        // START DETIL UM   => REALISASI : Material(M) & Alat(T) => Untuk kategori overhead sementara tetap ditampilkan untuk mengetahui jika ada kesalahan input, karena seharusnya untuk overhead tidak diinput di penggunaan.
                            /* ---- Hidden => 15-03-2023
                            $TOTUM_ITMUSEDAM    = 0;
                            $no = 0;
                            $getUM  = "SELECT A.*,
                                        B.UM_CODE, B.SPLCODE, B.STATDESC
                                        FROM tbl_um_detail A
                                        INNER JOIN tbl_um_header B ON B.UM_NUM = A.UM_NUM AND B.PRJCODE = A.PRJCODE
                                        WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.UM_STAT NOT IN (5,9)
                                        AND A.ITM_CODE = '$ITM_CODE' AND (B.UM_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                        ORDER BY B.UM_CODE, B.UM_DATE ASC";
                            $resDataUM  = $this->db->query($getUM);
                            if($resDataUM->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI PENGGUNAAN MATERIAL & ALAT</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="100" style="font-style: italic; text-align: center;">REF.</th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                
                                                $no = $no;
                                                foreach($resDataUM->result() as $rUM):
                                                    $no         = $no + 1;
                                                    $UM_NUM     = $rUM->UM_NUM;
                                                    $UM_CODE    = $rUM->UM_CODE;
                                                    $SPLCODE    = $rUM->SPLCODE;
                                                    $UM_DATE    = $rUM->UM_DATE;
                                                    $PRJCODE    = $rUM->PRJCODE;
                                                    $ITM_QTY    = $rUM->ITM_QTY;
                                                    $ITM_UNIT   = $rUM->ITM_UNIT;
                                                    $ITM_PRICE  = $rUM->ITM_PRICE;
                                                    $ITM_TOTAL  = $rUM->ITM_TOTAL;
                                                    $UM_DESC    = $rUM->UM_DESC;
                                                    $UM_STAT    = $rUM->UM_STAT;
                                                    $STATDESC   = $rUM->STATDESC;

                                                    $TOTUM_ITMUSEDAM      = $TOTUM_ITMUSEDAM + $ITM_TOTAL;

                                                    // get SPLDESC
                                                        $SPLDESC    = "-";
                                                        $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                                                        $resSPL     = $this->db->query($getSPL);
                                                        if($resSPL->num_rows() > 0)
                                                        {
                                                            foreach($resSPL->result() as $rSPL):
                                                                $SPLDESC = $rSPL->SPLDESC;
                                                            endforeach;
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($UM_DATE)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $UM_CODE; ?></td>
                                                            <td nowrap style="text-align: center;">-</td>
                                                            <td><?php echo "$UM_DESC"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_QTY, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_TOTAL, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                            ?>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTUM_ITMUSEDAM, 2); ?></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            ------------- End Hidden --------- */
                        // END DETIL UM     => REALISASI

                        // START DETIL IR
                            $TOTLPM_ITMUSEDAM    = 0;
                            $no = 0;
                            $getIR  = "SELECT A.*,
                                        B.IR_CODE, B.SPLCODE, B.STATDESC
                                        FROM tbl_ir_detail A
                                        INNER JOIN tbl_ir_header B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
                                        WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.IR_STAT NOT IN (5,9)
                                        AND (B.IR_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                        ORDER BY B.IR_CODE, B.IR_DATE ASC";
                            $resDataIR  = $this->db->query($getIR);
                            if($resDataIR->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI LPM</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="100" style="font-style: italic; text-align: center;">REF.</th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                
                                                $no = $no;
                                                foreach($resDataIR->result() as $rIR):
                                                    $no         = $no + 1;
                                                    $IR_NUM     = $rIR->IR_NUM;
                                                    $IR_CODE    = $rIR->IR_CODE;
                                                    $SPLCODE    = $rIR->SPLCODE;
                                                    $IR_DATE    = $rIR->IR_DATE;
                                                    $PRJCODE    = $rIR->PRJCODE;
                                                    $ITM_QTY    = $rIR->ITM_QTY;
                                                    $ITM_UNIT   = $rIR->ITM_UNIT;
                                                    $ITM_PRICE  = $rIR->ITM_PRICE;
                                                    $ITM_TOTAL  = $rIR->ITM_TOTAL;
                                                    $NOTES      = $rIR->NOTES;
                                                    $IR_STAT    = $rIR->IR_STAT;
                                                    $STATDESC   = $rIR->STATDESC;

                                                    $TOTLPM_ITMUSEDAM      = $TOTLPM_ITMUSEDAM + $ITM_TOTAL;

                                                    // get SPLDESC
                                                        $SPLDESC    = "-";
                                                        $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                                                        $resSPL     = $this->db->query($getSPL);
                                                        if($resSPL->num_rows() > 0)
                                                        {
                                                            foreach($resSPL->result() as $rSPL):
                                                                $SPLDESC = $rSPL->SPLDESC;
                                                            endforeach;
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($IR_DATE)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $IR_CODE; ?></td>
                                                            <td nowrap style="text-align: center;">-</td>
                                                            <td><?php echo "$NOTES"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_QTY, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_TOTAL, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                            ?>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTLPM_ITMUSEDAM, 2); ?></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                        // END DETIL UM     => REALISASI

                        // START DETIL OPNAME => REALISASI
                            $TOTOPN_ITMUSEDAM   = 0;
                            $getDataOPN     = "SELECT A.*,
                                                B.OPNH_CODE, B.OPNH_STAT, B.STATDESC, B.SPLCODE, B.WO_CODE
                                                FROM tbl_opn_detail A
                                                INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM AND B.PRJCODE = A.PRJCODE
                                                WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.OPNH_STAT NOT IN (5,9)
                                                AND A.ITM_CODE = '$ITM_CODE' AND B.OPNH_TYPE = 0 AND (B.OPNH_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY B.OPNH_CODE, B.OPNH_DATE ASC";
                            $resDataOPN     = $this->db->query($getDataOPN);
                            if($resDataOPN->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI OPNAME</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="100" style="font-style: italic; text-align: center;">REF.</th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                foreach($resDataOPN->result() as $rOPN):
                                                    $no             = $no + 1;
                                                    $OPNH_NUM       = $rOPN->OPNH_NUM;
                                                    $OPNH_CODE      = $rOPN->OPNH_CODE;
                                                    $OPNH_DATE      = $rOPN->OPNH_DATE;
                                                    $WO_CODE        = $rOPN->WO_CODE;
                                                    $OPNH_STAT      = $rOPN->OPNH_STAT;
                                                    $PRJCODE        = $rOPN->PRJCODE;
                                                    $SPLCODE        = $rOPN->SPLCODE;
                                                    $JOBCODEID      = $rOPN->JOBCODEID;
                                                    $ITM_CODE       = $rOPN->ITM_CODE;
                                                    $ITM_UNIT       = $rOPN->ITM_UNIT;
                                                    $OPND_VOLM      = $rOPN->OPND_VOLM;
                                                    $OPND_ITMPRICE  = $rOPN->OPND_ITMPRICE;
                                                    $OPND_ITMTOTAL  = $rOPN->OPND_ITMTOTAL;
                                                    $OPND_DESC      = $rOPN->OPND_DESC;
                                                    $TAXCODE1       = $rOPN->TAXCODE1;
                                                    $TAXPERC1       = $rOPN->TAXPERC1;
                                                    $TAXPRICE1      = $rOPN->TAXPRICE1;
                                                    $TAXCODE2       = $rOPN->TAXCODE2;
                                                    $TAXPERC2       = $rOPN->TAXPERC2;
                                                    $TAXPRICE2      = $rOPN->TAXPRICE2;
                                                    $OPND_TOTAL     = $rOPN->OPND_TOTAL;
                                                    $STATDESC       = $rOPN->STATDESC;

                                                    $Trans_Type = "OPN";


                                                    $TOTOPN_ITMUSEDAM      = $TOTOPN_ITMUSEDAM + $OPND_ITMTOTAL;

                                                    // get SPLDESC
                                                        $SPLDESC    = "";
                                                        $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                                                        $resSPL     = $this->db->query($getSPL);
                                                        if($resSPL->num_rows() > 0)
                                                        {
                                                            foreach($resSPL->result() as $rSPL):
                                                                $SPLDESC = $rSPL->SPLDESC;
                                                            endforeach;
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($OPNH_DATE)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $OPNH_CODE; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $WO_CODE; ?></td>
                                                            <td><?php echo "$OPND_DESC"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($OPND_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($OPND_ITMPRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($OPND_ITMTOTAL, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php

                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTOPN_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                        // END OPNAME => REALISASI

                        // START DETIL VOUCHER CASH => REALISASI
                            $TOTVC_ITMUSEDAM    = 0;
                            $getDataVC     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, A.JournalD_Kredit,
                                                A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                                A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                                B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc, A.SPLDESC, B.SPLCODE, B.SPLDESC AS SPLDESC2
                                                FROM tbl_journaldetail_vcash A 
                                                INNER JOIN tbl_journalheader_vcash B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                                WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT NOT IN (5,9)
                                                AND A.ITM_CODE = '$ITM_CODE' AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.JournalH_Date ASC";
                            $resDataVC     = $this->db->query($getDataVC);
                            if($resDataVC->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER CASH</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="100" style="font-style: italic; text-align: center;">REF./th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                foreach($resDataVC->result() as $rVC):
                                                    $no                 = $no + 1;
                                                    $JournalH_Code      = $rVC->JournalH_Code; 
                                                    $JournalH_Date      = $rVC->JournalH_Date; 
                                                    $JournalType        = $rVC->JournalType; 
                                                    $JournalH_Desc      = $rVC->JournalH_Desc; 
                                                    $Acc_Id             = $rVC->Acc_Id; 
                                                    $JournalD_Debet     = $rVC->JournalD_Debet; 
                                                    $JournalD_Kredit    = $rVC->JournalD_Kredit; 
                                                    $PPN_Code           = $rVC->PPN_Code; 
                                                    $PPN_Perc           = $rVC->PPN_Perc; 
                                                    $PPN_Amount         = $rVC->PPN_Amount; 
                                                    $PPH_Code           = $rVC->PPH_Code; 
                                                    $PPH_Perc           = $rVC->PPH_Perc; 
                                                    $PPH_Amount         = $rVC->PPH_Amount; 
                                                    $JOBCODEID          = $rVC->JOBCODEID; 
                                                    $ITM_CODE           = $rVC->ITM_CODE; 
                                                    $ITM_GROUP          = $rVC->ITM_GROUP; 
                                                    $ITM_CATEG          = $rVC->ITM_CATEG; 
                                                    $ITM_VOLM           = $rVC->ITM_VOLM; 
                                                    $ITM_PRICE          = $rVC->ITM_PRICE; 
                                                    $ITM_UNIT           = $rVC->ITM_UNIT; 
                                                    $Other_Desc         = $rVC->Other_Desc; 
                                                    $Manual_No          = $rVC->Manual_No; 
                                                    $GEJ_STAT           = $rVC->GEJ_STAT;
                                                    $STATDESC           = $rVC->STATDESC;
                                                    $SPLCODE            = $rVC->SPLCODE;
                                                    $SPLDESC            = $rVC->SPLDESC2;
                                                    $Trans_Type         = "Voucher Cash";

                                                    $TOTVC_ITMUSEDAM      = $TOTVC_ITMUSEDAM + $JournalD_Debet;

                                                    // get SPLDESC
                                                        $SPLDESC    = "";
                                                        $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                                                        $resSPL     = $this->db->query($getSPL);
                                                        if($resSPL->num_rows() > 0)
                                                        {
                                                            foreach($resSPL->result() as $rSPL):
                                                                $SPLDESC = $rSPL->SPLDESC;
                                                            endforeach;
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($JournalH_Date)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $Manual_No; ?></td>
                                                            <td nowrap style="text-align: center;">-</td>
                                                            <td><?php echo "$Other_Desc"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTVC_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                        // END DETIL VOUCHER CASH => REALISASI

                        // START DETIL VLK
                            $TOTVLK_ITMUSEDAM   = 0;
                            $getDataVLK     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, 
                                                A.JournalD_Kredit,
                                                A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                                A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                                B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc, B.SPLCODE, A.SPLDESC, CONCAT(C.First_Name) AS SPLDESC2
                                                FROM tbl_journaldetail_cprj A 
                                                INNER JOIN tbl_journalheader_cprj B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                                LEFT JOIN tbl_employee C ON B.PERSL_EMPID = C.Emp_ID
                                                WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT NOT IN (5,9)
                                                AND A.ITM_CODE = '$ITM_CODE' AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.JournalH_Date ASC";
                            $resDataVLK     = $this->db->query($getDataVLK);
                            if($resDataVLK->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER LK</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="100" style="font-style: italic; text-align: center;">REF.</th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                foreach($resDataVLK->result() as $rVLK):
                                                    $no                 = $no + 1;
                                                    $JournalH_Code      = $rVLK->JournalH_Code; 
                                                    $JournalH_Date      = $rVLK->JournalH_Date; 
                                                    $JournalH_Desc      = $rVLK->JournalH_Desc;
                                                    $JournalType        = $rVLK->JournalType; 
                                                    $Acc_Id             = $rVLK->Acc_Id; 
                                                    $JournalD_Debet     = $rVLK->JournalD_Debet; 
                                                    $JournalD_Kredit    = $rVLK->JournalD_Kredit; 
                                                    $PPN_Code           = $rVLK->PPN_Code; 
                                                    $PPN_Perc           = $rVLK->PPN_Perc; 
                                                    $PPN_Amount         = $rVLK->PPN_Amount; 
                                                    $PPH_Code           = $rVLK->PPH_Code; 
                                                    $PPH_Perc           = $rVLK->PPH_Perc; 
                                                    $PPH_Amount         = $rVLK->PPH_Amount; 
                                                    $JOBCODEID          = $rVLK->JOBCODEID; 
                                                    $ITM_CODE           = $rVLK->ITM_CODE; 
                                                    $ITM_GROUP          = $rVLK->ITM_GROUP; 
                                                    $ITM_CATEG          = $rVLK->ITM_CATEG; 
                                                    $ITM_VOLM           = $rVLK->ITM_VOLM; 
                                                    $ITM_PRICE          = $rVLK->ITM_PRICE; 
                                                    $ITM_UNIT           = $rVLK->ITM_UNIT; 
                                                    $Other_Desc         = $rVLK->Other_Desc; 
                                                    $Manual_No          = $rVLK->Manual_No; 
                                                    $GEJ_STAT           = $rVLK->GEJ_STAT;
                                                    $STATDESC           = $rVLK->STATDESC;
                                                    $SPLCODE            = $rVLK->SPLCODE;
                                                    $SPLDESC            = $rVLK->SPLDESC;
                                                    if($SPLDESC == '')
                                                        $SPLDESC        = $rVLK->SPLDESC2;

                                                    $Trans_Type         = "Voucher LK";

                                                    $s_00   = "SELECT Other_Desc FROM tbl_journaldetail_cprj WHERE JournalH_Code = '$JournalH_Code'
                                                                AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
                                                    $r_00   = $this->db->query($s_00)->result();
                                                    foreach($r_00 as $rw_00):
                                                        $JournalH_Desc    = $rw_00->Other_Desc;
                                                    endforeach;

                                                    $TOTVLK_ITMUSEDAM      = $TOTVLK_ITMUSEDAM + $JournalD_Debet;

                                                    // get SPLDESC
                                                        $SPLDESC    = "";
                                                        $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                                                        $resSPL     = $this->db->query($getSPL);
                                                        if($resSPL->num_rows() > 0)
                                                        {
                                                            foreach($resSPL->result() as $rSPL):
                                                                $SPLDESC = $rSPL->SPLDESC;
                                                            endforeach;
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $Manual_No; ?></td>
                                                            <td nowrap style="text-align: center;">-</td>
                                                            <td><?php echo "$JournalH_Desc"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTVLK_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                        // END DETIL VLK => REALISASI

                        // START DETIL PD
                            $TOTPD_ITMUSEDAM    = 0;
                            $getDataPD     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, 
                                                A.JournalD_Kredit,
                                                A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                                A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                                B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc, B.SPLCODE, B.SPLDESC, CONCAT(C.First_Name) AS SPLDESC2, B.Reference_Number
                                                FROM tbl_journaldetail_pd A 
                                                INNER JOIN tbl_journalheader_pd B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                                LEFT JOIN tbl_employee C ON B.PERSL_EMPID = C.Emp_ID
                                                WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT NOT IN (5,9)
                                                AND A.ITM_CODE = '$ITM_CODE' AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.JournalH_Date ASC";
                            $resDataPD     = $this->db->query($getDataPD);
                            if($resDataPD->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER PD</th>
                                            </tr>
                                            <tr>
                                                <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                                <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                                <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                                <th width="150" style="font-style: italic; text-align: center;">REF.</th>
                                                <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                                <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                foreach($resDataPD->result() as $rPD):
                                                    $no                 = $no + 1;
                                                    $JournalH_Code      = $rPD->JournalH_Code; 
                                                    $JournalH_Date      = $rPD->JournalH_Date; 
                                                    $JournalH_Desc      = $rPD->JournalH_Desc;
                                                    $JournalType        = $rPD->JournalType; 
                                                    $Acc_Id             = $rPD->Acc_Id; 
                                                    $JournalD_Debet     = $rPD->JournalD_Debet; 
                                                    $JournalD_Kredit    = $rPD->JournalD_Kredit; 
                                                    $PPN_Code           = $rPD->PPN_Code; 
                                                    $PPN_Perc           = $rPD->PPN_Perc; 
                                                    $PPN_Amount         = $rPD->PPN_Amount; 
                                                    $PPH_Code           = $rPD->PPH_Code; 
                                                    $PPH_Perc           = $rPD->PPH_Perc; 
                                                    $PPH_Amount         = $rPD->PPH_Amount; 
                                                    $JOBCODEID          = $rPD->JOBCODEID; 
                                                    $ITM_CODE           = $rPD->ITM_CODE; 
                                                    $ITM_GROUP          = $rPD->ITM_GROUP; 
                                                    $ITM_CATEG          = $rPD->ITM_CATEG; 
                                                    $ITM_VOLM           = $rPD->ITM_VOLM; 
                                                    $ITM_PRICE          = $rPD->ITM_PRICE; 
                                                    $ITM_UNIT           = $rPD->ITM_UNIT; 
                                                    $Other_Desc         = $rPD->Other_Desc; 
                                                    $Manual_No          = $rPD->Manual_No; 
                                                    $GEJ_STAT           = $rPD->GEJ_STAT;
                                                    $STATDESC           = $rPD->STATDESC;
                                                    $SPLCODE            = $rPD->SPLCODE;
                                                    $SPLDESC            = $rPD->SPLDESC;

                                                    $Reference_Number   = $rPD->Reference_Number;
                                                    // if($SPLDESC == '')
                                                    //     $SPLDESC        = $rPD->SPLDESC2;
                                                    
                                                    $Trans_Type         = "Voucher PD";

                                                    $s_00   = "SELECT Other_Desc FROM tbl_journaldetail_pd WHERE JournalH_Code = '$JournalH_Code'
                                                                AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
                                                    $r_00   = $this->db->query($s_00)->result();
                                                    foreach($r_00 as $rw_00):
                                                        $JournalH_Desc    = $rw_00->Other_Desc;
                                                    endforeach;
                                        
                                                    $TOTPD_ITMUSEDAM      = $TOTPD_ITMUSEDAM + $JournalD_Debet;

                                                    // get SPLDESC
                                                        $SPLDESC    = "";
                                                        $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
                                                        $resSPL     = $this->db->query($getSPL);
                                                        if($resSPL->num_rows() > 0)
                                                        {
                                                            foreach($resSPL->result() as $rSPL):
                                                                $SPLDESC = $rSPL->SPLDESC;
                                                            endforeach;
                                                        }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $Manual_No; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $Reference_Number; ?></td>
                                                            <td><?php echo "$JournalH_Desc"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTPD_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                        // END DETIL PD => REQUEST

                        $SUBTOT_ITMUSEDAM   = $TOTLPM_ITMUSEDAM + $TOTOPN_ITMUSEDAM + $TOTVC_ITMUSEDAM + $TOTVLK_ITMUSEDAM + $TOTPD_ITMUSEDAM;
                        $GTOT_ITMUSEDAM     = $GTOT_ITMUSEDAM + $SUBTOT_ITMUSEDAM;

                        if($resDataIR->num_rows() == 0 && $resDataOPN->num_rows() == 0 && $resDataVC->num_rows() == 0 && $resDataVLK->num_rows() == 0 && $resDataPD->num_rows() == 0)
                        {
                            ?>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                    <thead style="background-color: lightgray;">
                                        <tr>
                                            <th colspan="11" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                        </tr>
                                        <tr>
                                            <th width="20" style="font-style: italic; text-align: center;">NO.</th>
                                            <th width="100" style="font-style: italic; text-align: center;">TANGGAL</th>
                                            <th width="150" style="font-style: italic; text-align: center;">SUPPLIER</th>
                                            <th width="150" style="font-style: italic; text-align: center;">NOMOR TX</th>
                                            <th width="100" style="font-style: italic; text-align: center;">REF.</th>
                                            <th width="200" style="font-style: italic; text-align: center;">DESKRIPSI</th>
                                            <th width="50" style="font-style: italic; text-align: center;">SAT.</th>
                                            <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                            <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                            <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                        </tr>
                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                            <td colspan="9" style="text-align: right; font-weight: bold;">JUMLAH TOTAL</td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                        }

                        // START : SUB. TOTAL
                            ?>
                            <div id="sub_total">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-weight: bold; text-align: right;">SUB. TOTAL</td=>
                                        <td width="125" style="font-weight: bold; border-right: hidden; text-align: right;"><?php echo number_format($SUBTOT_ITMUSEDAM, 2); ?></td>
                                        <td width="125" style="font-style: italic; text-align: center;">&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                        // END : SUB. TOTAL
                    endforeach;

                    // START : GRAND TOTAL
                        ?>
                        <div id="grand_total">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="text-align: right; font-weight: bold;">GRAND TOTAL</td>
                                    <td width="125" style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_ITMUSEDAM, 2); ?></td>
                                    <td width="125">&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    // END : GRAND TOTAL
                }
        ?>
        </section>
    </div>
</body>
</html>
<?php
    if(isset($this->session->userdata['vers']))
        $vers  = $this->session->userdata['vers'];
    else
        $vers  = '2.0.5';

    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk1  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
        <?php
    endforeach;
?>
<script>
    $(function(){
        document.onkeydown = (event) => {
            console.log(event);
            if (event.ctrlKey) {
                event.preventDefault();
                // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
            }   
        };
    });
</script>