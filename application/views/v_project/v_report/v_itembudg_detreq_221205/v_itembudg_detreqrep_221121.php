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

        .header_content .col-8 {
            float: left;
            width: 60%;
        }

        .header_content .col-8 table tr th {
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

        #sub_total table  tr td {
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
                $PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
				$JOBCODEID 	= [];
				$getJOBPAR 	= "SELECT JOBCODEID FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID LIKE '$JOBPARENT%' AND ISLASTH = 1";
				$resJOBPAR 	= $this->db->query($getJOBPAR);
				if($resJOBPAR->num_rows() > 0)
				{
					foreach($resJOBPAR->result() as $rJOB):
						$JOBCODEID[] = $rJOB->JOBCODEID;
					endforeach;
				}

				$JoinJOBPAR 	= join("','", $JOBCODEID);

                $addQITM        = "";
                if($ITM_CODE[0] != 1)
                {
                    $JoinITMCODE    = join("','", $ITM_CODE);
                    $addQITM        = "AND ITM_CODE IN ('$JoinITMCODE')";
                }

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

                $getBUD_ITM     = "SELECT JOBCODEID, JOBPARENT, JOBDESC, ITM_CODE, ITM_UNIT, ITM_VOLM, ITM_BUDG, ADD_VOLM, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail_$PRJCODEVW
                                    WHERE ISLAST = 1 $addQITM AND JOBPARENT IN ('$JoinJOBPAR')";
                $resBUD_ITM     = $this->db->query($getBUD_ITM);
                $ITM_VOLM2      = 0;
                $ITM_BUDG2      = 0;
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
                        $ADD_VOLM       = $rB->ADD_VOLM;
                        $ADD_JOBCOST    = $rB->ADD_JOBCOST;
                        $ADDM_VOLM      = $rB->ADDM_VOLM;
                        $ADDM_JOBCOST   = $rB->ADDM_JOBCOST;

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
                                <div class="col-8">
                                    <table width="100%" border="0" style="size:auto">
                                        <thead>
                                            <tr style="text-align:left; font-style:italic; display: none;">
                                                <th width="150" nowrap valign="top">NAMA LAPORAN</th>
                                                <th width="10">:</th>
                                                <th><?php echo "$h1_title"; ?></th>
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
                                <div class="col-4">
                                    <table width="100%" border="0">
                                        <tr style="text-align:left; font-style:italic;  border: 2px double;">
                                            <th width="50" nowrap valign="top" style="font-weight: bold; border: hidden;"><?php //echo "$SelJOBCODEID_H - $SelJOBDESC_H"; ?></th>
                                            <th width="50" style="font-weight: bold; text-align: center;">VOL.</th>
                                            <th width="150" style="font-weight: bold; text-align: center;">JML HARGA</th>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <th width="50" nowrap valign="top">BUDGET AWAL</th>
                                            <th width="50" style="text-align: right;"><?php echo number_format($ITM_VOLMBG, 2); ?></th>
                                            <th width="150" style="text-align: right;"><?php echo number_format($ITM_BUDG, 2);?></th>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <th width="50" nowrap valign="top">AMANDEMENT BUDGET</th>
                                            <th width="50" style="text-align: right;"><?php echo number_format($ADDVOLM, 2); ?></th>
                                            <th width="150" style="text-align: right;"><?php echo number_format($ADD_TOTAL, 2); ?></th>
                                        </tr>
                                        <tr style="text-align:left; font-style:italic">
                                            <th nowrap valign="top">BUDGET AKHIR</th>
                                            <th style="text-align: right;"><?php echo number_format($ITM_VOLMBG2, 2); ?></th>
                                            <th style="text-align: right;"><?php echo number_format($ITM_BUDG2, 2); ?></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php

                        // get Data SPP - OP => REQUEST
                            $TOTPO_ITMUSEDAM    = 0;
                            $getDataSPP     = "SELECT A.*, 
                                                B.PR_CODE, B.PR_STAT, B.STATDESC, '' AS SPLCODE, '' AS SPLDESC
                                                FROM tbl_pr_detail A 
                                                INNER JOIN tbl_pr_header B ON B.PR_NUM = A.PR_NUM AND B.PRJCODE = A.PRJCODE
                                                WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.PR_STAT NOT IN (5,9)
                                                AND (A.PR_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.PR_DATE ASC";
                            $resDataSPP     = $this->db->query($getDataSPP);
                            if($resDataSPP->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI SPP - OP</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                $REM_VOLMBG         = $ITM_VOLMBG2;
                                                $REM_BUDG           = $ITM_BUDG2;
                                                foreach($resDataSPP->result() as $rSPP):
                                                    $no         = $no + 1;
                                                    $PR_ID      = $rSPP->PR_ID;
                                                    $PR_NUM     = $rSPP->PR_NUM;
                                                    $PR_CODE    = $rSPP->PR_CODE;
                                                    $SPLCODE    = $rSPP->SPLCODE;
                                                    $SPLDESC    = $rSPP->SPLDESC;
                                                    $PR_DATE    = $rSPP->PR_DATE;
                                                    $PRJCODE    = $rSPP->PRJCODE;
                                                    $JOBCODEID  = $rSPP->JOBCODEID;
                                                    $JOBPARDESC = $rSPP->JOBPARDESC;
                                                    $ITM_CODE   = $rSPP->ITM_CODE;
                                                    $ITM_UNIT   = $rSPP->ITM_UNIT;
                                                    $PR_VOLM    = $rSPP->PR_VOLM;
                                                    $PR_PRICE   = $rSPP->PR_PRICE;
                                                    $PR_TOTAL   = $rSPP->PR_TOTAL;
                                                    $PR_CVOL    = $rSPP->PR_CVOL;
                                                    $PR_CTOTAL  = $rSPP->PR_CTOTAL;
                                                    $PR_DESC_ID = $rSPP->PR_DESC_ID;
                                                    $PR_DESC    = $rSPP->PR_DESC;
                                                    $ISCLOSE    = $rSPP->ISCLOSE;
                                                    $PR_STAT    = $rSPP->PR_STAT;
                                                    $STATDESC   = $rSPP->STATDESC;

                                                    $Trans_Type         = "SPP";

                                                    // SPP belum menentukan harga
                                                        $PR_PRICE   = 0;
                                                        $PR_TOTAL   = ($PR_VOLM - $PR_CVOL) * $PR_PRICE;

                                                        $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				                                        $r_isLS 	= $this->db->count_all($s_isLS);

                                                        if($r_isLS > 0)
                                                        {
                                                            $REM_VOLMBG     = $REM_VOLMBG;
                                                        }
                                                        else
                                                        {
                                                            $REM_VOLMBG     = $REM_VOLMBG - ($PR_VOLM - $PR_CVOL);
                                                        }


                                                    $TOTPO_ITMUSEDAM      = $TOTPO_ITMUSEDAM + $PR_TOTAL;

                                                    // sisa budget
                                                        // if($PR_STAT != 9)
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        //     $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }
                                                        // else
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG;
                                                        //     $REM_BUDG       = $REM_BUDG;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }

                                                    ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($PR_DATE)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $PR_CODE; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo "-"; ?></td>
                                                            <td><?php echo "$PR_DESC"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($PR_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($PR_CVOL, $decFormat); ?></td>
                                                            <td style="text-align: right;">-</td>
                                                            <td style="text-align: right;">-</td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($REM_BUDG, $decFormat); ?></td>
                                                        </tr>
                                                    <?php

                                                    // GET PO BY PR_NUM
                                                        $getPO  = "SELECT A.*, B.PR_CODE AS REF_CODE, B.SPLCODE, B.STATDESC FROM tbl_po_detail A 
                                                                    INNER JOIN tbl_po_header B ON B.PO_NUM = A.PO_NUM AND B.PRJCODE = A.PRJCODE
                                                                    WHERE A.PRJCODE = '$PRJCODE' AND A.PR_NUM = '$PR_NUM' AND A.PRD_ID = '$PR_ID'
                                                                    AND A.ITM_CODE = '$ITM_CODE' AND A.JOBCODEID = '$JOBCODEID'
                                                                    AND B.PO_STAT NOT IN (5,9)
                                                                    ORDER BY B.PO_DATE ASC";
                                                        $resPO  = $this->db->query($getPO);
                                                        if($resPO->num_rows() > 0)
                                                        {
                                                            $no = $no;
                                                            $PO_TOTAL = "";
                                                            foreach($resPO->result() as $rPO):
                                                                $no         = $no + 1;
                                                                $PO_NUM     = $rPO->PO_NUM;
                                                                $PO_CODE    = $rPO->PO_CODE;
                                                                $SPLCODE    = $rPO->SPLCODE;
                                                                $PO_DATE    = $rPO->PO_DATE;
                                                                $PRJCODE    = $rPO->PRJCODE;
                                                                $PRD_ID     = $rPO->PRD_ID;
                                                                $PO_VOLM    = $rPO->PO_VOLM;
                                                                $ITM_UNIT   = $rPO->ITM_UNIT;
                                                                $PO_PRICE   = $rPO->PO_PRICE;
                                                                $PO_DISP    = $rPO->PO_DISP;
                                                                $PO_DISC    = $rPO->PO_DISC;
                                                                $PO_COST    = $rPO->PO_COST;
                                                                $PO_DESC_ID = $rPO->PO_DESC_ID;
                                                                $PO_DESC    = $rPO->PO_DESC;
                                                                $PO_CVOL    = $rPO->PO_CVOL;
                                                                $PO_CTOTAL  = $rPO->PO_CTOTAL;
                                                                $TAXCODE1   = $rPO->TAXCODE1;
                                                                $TAXCODE2   = $rPO->TAXCODE2;
                                                                $TAXPRICE1  = $rPO->TAXPRICE1;
                                                                $TAXPRICE2  = $rPO->TAXPRICE2;
                                                                $ISCLOSE    = $rPO->ISCLOSE;
                                                                $PO_ISCANC  = $rPO->PO_ISCANC;
                                                                $PO_STAT    = $rPO->PO_STAT;
                                                                $STATDESC   = $rPO->STATDESC;

                                                                $REF_CODE   = $rPO->REF_CODE;

                                                                $PO_TOTAL   = ($PO_VOLM - $PO_CVOL) * $PO_PRICE;

                                                                $TOTPO_ITMUSEDAM      = $TOTPO_ITMUSEDAM + $PO_TOTAL;

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

                                                                    $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				                                                    $r_isLS 	= $this->db->count_all($s_isLS);

                                                                    if($r_isLS > 0)
                                                                    {
                                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                                        $REM_BUDG       = $REM_BUDG - $PO_TOTAL;
                                                                    }
                                                                    else
                                                                    {
                                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                                        $REM_BUDG       = $REM_BUDG - $PO_TOTAL;
                                                                    }
                                                                
                                                                ?>
                                                                    <tr>
                                                                        <td style="text-align: center;"><?php echo $no; ?></td>
                                                                        <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($PO_DATE)); ?></td>
                                                                        <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                                        <td nowrap style="text-align: center;"><?php echo $PO_CODE; ?></td>
                                                                        <td nowrap style="text-align: center;"><?php echo $REF_CODE; ?></td>
                                                                        <td><?php echo "$PO_DESC"; ?></td>
                                                                        <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                                        <td style="text-align: center;"><?php echo number_format($PO_VOLM, $decFormat); ?></td>
                                                                        <td style="text-align: center;"><?php echo number_format($PO_CVOL, $decFormat); ?></td>
                                                                        <td style="text-align: right;"><?php echo number_format($PO_PRICE, $decFormat); ?></td>
                                                                        <td style="text-align: right;"><?php echo number_format($PO_COST, $decFormat); ?></td>
                                                                        <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                                        <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, $decFormat); ?></td>
                                                                        <td style="text-align: right;"><?php echo number_format($REM_BUDG, $decFormat); ?></td>
                                                                    </tr>
                                                                <?php
                                                            endforeach;

                                                        }
                                                endforeach;
                                                    ?>
                                                    <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                        <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTPO_ITMUSEDAM, 2); ?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            /* -------------------------------------- HOLD ----------------------------------------------
                            else
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI SPP - OP</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                            </tr>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            -------------------------------------- END HOLD ---------------------------------------------- */
                        // END SPP - OP => REQUEST

                        ?> <div class="pBreak"></div> <?php

                        // get Data SPK => REQUEST
                            $TOTSPK_ITMUSEDAM   = 0;
                            $getDataSPK     = "SELECT A.*, 
                                                B.WO_CODE, B.WO_STAT, B.STATDESC, B.SPLCODE
                                                FROM tbl_wo_detail A 
                                                INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM AND B.PRJCODE = A.PRJCODE
                                                WHERE B.PRJCODE = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.WO_STAT NOT IN (5,9)
                                                AND (A.WO_DATE BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.WO_DATE ASC";
                            $resDataSPK     = $this->db->query($getDataSPK);
                            if($resDataSPK->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI SPK</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                $REM_VOLMBG         = $REM_VOLMBG;
                                                $REM_BUDG           = $REM_BUDG;
                                                foreach($resDataSPK->result() as $rSPK):
                                                    $no         = $no + 1;
                                                    $WO_ID      = $rSPK->WO_ID;
                                                    $WO_NUM     = $rSPK->WO_NUM;
                                                    $WO_CODE    = $rSPK->WO_CODE;
                                                    $WO_DATE    = $rSPK->WO_DATE;
                                                    $WO_STAT    = $rSPK->WO_STAT;
                                                    $PRJCODE    = $rSPK->PRJCODE;
                                                    $SPLCODE    = $rSPK->SPLCODE;
                                                    $JOBCODEID  = $rSPK->JOBCODEID;
                                                    $ITM_CODE   = $rSPK->ITM_CODE;
                                                    $ITM_UNIT   = $rSPK->ITM_UNIT;
                                                    $WO_VOLM    = $rSPK->WO_VOLM;
                                                    $ITM_PRICE  = $rSPK->ITM_PRICE;
                                                    $WO_TOTAL   = $rSPK->WO_TOTAL;
                                                    $WO_CVOL    = $rSPK->WO_CVOL;
                                                    $WO_CAMN    = $rSPK->WO_CAMN;
                                                    $WO_DESC    = $rSPK->WO_DESC;
                                                    $TAXCODE1   = $rSPK->TAXCODE1;
                                                    $TAXPERC1   = $rSPK->TAXPERC1;
                                                    $TAXPRICE1  = $rSPK->TAXPRICE1;
                                                    $TAXCODE2   = $rSPK->TAXCODE2;
                                                    $TAXPERC2   = $rSPK->TAXPERC2;
                                                    $TAXPRICE2  = $rSPK->TAXPRICE2;
                                                    $WO_TOTAL2  = $rSPK->WO_TOTAL2;
                                                    $ISCLOSE    = $rSPK->ISCLOSE;
                                                    $STATDESC   = $rSPK->STATDESC;

                                                    $Trans_Type = "SPK";

                                                    $WO_TOTAL   = ($WO_VOLM - $WO_CVOL) * $ITM_PRICE;

                                                    $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				                                    $r_isLS 	= $this->db->count_all($s_isLS);

                                                    if($r_isLS > 0)
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG - $WO_TOTAL;
                                                    }
                                                    else
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG - ($WO_VOLM - $WO_CVOL);
                                                        $REM_BUDG       = $REM_BUDG - $WO_TOTAL;
                                                    }


                                                    $TOTSPK_ITMUSEDAM      = $TOTSPK_ITMUSEDAM + $WO_TOTAL;

                                                    // sisa budget
                                                        // if($PR_STAT != 9)
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        //     $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }
                                                        // else
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG;
                                                        //     $REM_BUDG       = $REM_BUDG;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }

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
                                                            <td style="text-align: center;"><?php echo date('d/m/Y', strtotime($WO_DATE)); ?></td>
                                                            <td style="text-align: left;"><?php echo $SPLDESC; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo $WO_CODE; ?></td>
                                                            <td nowrap style="text-align: center;"><?php echo "-"; ?></td>
                                                            <td><?php echo "$WO_DESC"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($WO_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($WO_CVOL, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($WO_TOTAL, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($REM_BUDG, $decFormat); ?></td>
                                                        </tr>
                                                    <?php

                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTSPK_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            /* -------------------------------------- HOLD ----------------------------------------------
                            else
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI SPK</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                            </tr>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            -------------------------------------- END HOLD ---------------------------------------------- */
                        // END SPK => REQUEST

                        ?> <div class="pBreak"></div> <?php

                        // get Data VC
                            $TOTVC_ITMUSEDAM    = 0;
                            $getDataVC     = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, A.JournalD_Debet, A.JournalD_Kredit,
                                                A.PPN_Code, A.PPN_Perc, A.PPN_Amount, A.PPH_Code, A.PPH_Perc, A.PPH_Amount, A.JOBCODEID, A.ITM_CODE, 
                                                A.ITM_GROUP, A.ITM_CATEG, A.ITM_VOLM, A.ITM_PRICE, A.ITM_UNIT, A.Other_Desc, 
                                                B.GEJ_STAT, B.STATDESC, B.Manual_No, B.JournalH_Desc, A.SPLDESC, B.SPLCODE, B.SPLDESC AS SPLDESC2
                                                FROM tbl_journaldetail_vcash A 
                                                INNER JOIN tbl_journalheader_vcash B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                                WHERE A.proj_Code = '$PRJCODE' AND A.JOBCODEID = '$JOBCODEID' AND B.GEJ_STAT NOT IN (5,9)
                                                AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.JournalH_Date ASC";
                            $resDataVC     = $this->db->query($getDataVC);
                            if($resDataVC->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER CASH</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                $REM_VOLMBG         = $REM_VOLMBG;
                                                $REM_BUDG           = $REM_BUDG;
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

                                                    $s_00   = "SELECT Other_Desc FROM tbl_journaldetail_vcash WHERE JournalH_Code = '$JournalH_Code'
                                                                AND JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE'";
                                                    $r_00   = $this->db->query($s_00)->result();
                                                    foreach($r_00 as $rw_00):
                                                        $JournalH_Desc    = $rw_00->Other_Desc;
                                                    endforeach;

                                                    $TOTVC_ITMUSEDAM      = $TOTVC_ITMUSEDAM + $JournalD_Debet;

                                                    $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				                                    $r_isLS 	= $this->db->count_all($s_isLS);

                                                    if($r_isLS > 0)
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                    }
                                                    else
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                    }

                                                    // sisa budget
                                                        // if($GEJ_STAT != 9)
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        //     $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }
                                                        // else
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG;
                                                        //     $REM_BUDG       = $REM_BUDG;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }

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
                                                            <td><?php echo "$JournalH_Desc"; ?></td>
                                                            <td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($ITM_VOLM, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo number_format(0, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTVC_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            /* -------------------------------------- HOLD ----------------------------------------------
                            else
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER CASH</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                            </tr>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            -------------------------------------- END HOLD ---------------------------------------------- */
                        // END Voucher Cash => REQUEST

                        ?> <div class="pBreak"></div> <?php

                        // get Data VLK
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
                                                AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.JournalH_Date ASC";
                            $resDataVLK     = $this->db->query($getDataVLK);
                            if($resDataVLK->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                $REM_VOLMBG         = $REM_VOLMBG;
                                                $REM_BUDG           = $REM_BUDG;
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

                                                    $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				                                    $r_isLS 	= $this->db->count_all($s_isLS);

                                                    if($r_isLS > 0)
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                    }
                                                    else
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                    }

                                                    // sisa budget
                                                        // if($GEJ_STAT != 9)
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        //     $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }
                                                        // else
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG;
                                                        //     $REM_BUDG       = $REM_BUDG;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }

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
                                                            <td style="text-align: center;"><?php echo number_format(0, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, $decFormat); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($REM_BUDG, $decFormat); ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTVLK_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            /* -------------------------------------- HOLD ----------------------------------------------
                            else
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER LUAR KOTA</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                            </tr>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            ----------------------------------------- END HOLD ----------------------------------------- */
                        // END Voucher Luar Kota => REQUEST

                        ?> <div class="pBreak"></div> <?php

                        // get Data PD
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
                                                AND (A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date')
                                                ORDER BY A.JournalH_Date ASC";
                            $resDataPD     = $this->db->query($getDataPD);
                            if($resDataPD->num_rows() > 0)
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER PD</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no                 = 0;
                                                $REM_VOLMBG         = $REM_VOLMBG;
                                                $REM_BUDG           = $REM_BUDG;
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

                                                    $s_isLS 	= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
				                                    $r_isLS 	= $this->db->count_all($s_isLS);

                                                    if($r_isLS > 0)
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG;
                                                        $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                    }
                                                    else
                                                    {
                                                        $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                    }
                                        
                                                    // sisa budget
                                                        // if($GEJ_STAT != 9)
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG - $ITM_VOLM;
                                                        //     $REM_BUDG       = $REM_BUDG - $JournalD_Debet;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }
                                                        // else
                                                        // {
                                                        //     $REM_VOLMBG     = $REM_VOLMBG;
                                                        //     $REM_BUDG       = $REM_BUDG;
                                                        //     if($REM_VOLMBG == 0)
                                                        //         $REM_PRICEBG = 0;
                                                        //     else
                                                        //         $REM_PRICEBG = $REM_BUDG / $REM_VOLMBG;
                                                        // }

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
                                                            <td style="text-align: center;"><?php echo number_format(0, $decFormat); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                            <td style="text-align: center;"><?php echo $STATDESC; ?></td>
                                                            <td style="text-align: center;"><?php echo number_format($REM_VOLMBG, 3); ?></td>
                                                            <td style="text-align: right;"><?php echo number_format($REM_BUDG, 2); ?></td>
                                                        </tr>
                                                    <?php
                                                endforeach;
                                                    ?>
                                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                            <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTPD_ITMUSEDAM, 2); ?></td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    <?php
                                            ?>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            /* -------------------------------------- HOLD ----------------------------------------------
                            else
                            {
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                        <thead style="background-color: lightgray;">
                                            <tr>
                                                <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI VOUCHER PD</th>
                                                <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                                <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                                <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                                <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                                <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                                <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                            </tr>
                                            <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                                <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                                <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            ----------------------------------------END HOLD -------------------------------------- */
                        // END Voucher PD => REQUEST

                        $SUBTOT_ITMUSEDAM = $TOTPO_ITMUSEDAM + $TOTSPK_ITMUSEDAM + $TOTVC_ITMUSEDAM + $TOTVLK_ITMUSEDAM + $TOTPD_ITMUSEDAM;

                        if($resDataSPP->num_rows() == 0 && $resDataSPK->num_rows() == 0 && $resDataVC->num_rows() == 0 && $resDataVLK->num_rows() == 0 && $resDataPD->num_rows() == 0)
                        {
                            ?>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"> 
                                    <thead style="background-color: lightgray;">
                                        <tr>
                                            <th colspan="12" style="height: 30px; vertical-align: middle; text-align: center;">DETIL TRANSAKSI</th>
                                            <th colspan="2" style="height: 30px; vertical-align: middle; text-align: center; border-right: hidden;">SISA BUDGET</th>
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
                                            <th width="50" style="font-style: italic; text-align: center;">VOL. BATAL</th>
                                            <th width="100" style="font-style: italic; text-align: center;">HARSAT</th>
                                            <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                            <th width="100" style="font-style: italic; text-align: center;">STATUS DOC.</th>
                                            <th width="50" style="font-style: italic; text-align: center;">VOL.</th>
                                            <th width="100" style="border-right: hidden; font-style: italic; text-align: center;">JUMLAH HARGA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="14" style="text-align: center; font-style: italic;">No data available</td>
                                        </tr>
                                        <tr style="background-color: rgba(181, 178, 177, 0.3);">
                                            <td colspan="10" style="text-align: right; font-weight: bold;">TOTAL</td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format(0, 2); ?></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                        }
                        ?>
                            <div id="sub_total">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="1065" style="text-align: right; font-weight: bold;">SUB. TOTAL</td>
                                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($SUBTOT_ITMUSEDAM, 2); ?></td>
                                        <td width="270">&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        <?php
                    endforeach;
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