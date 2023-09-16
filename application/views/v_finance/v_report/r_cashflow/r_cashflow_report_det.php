<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 30 Januari 2019
 * File Name  = r_outrequest_report_det.php
 * Location   = -
*/
$IDREP  = date('YmdHis');
if($viewType == 1)
{
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=LapCashOut_$IDREP.xls");
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

$PRJCODEHO    = $PRJCODE;
$sqlPRJHO     = "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
$resPRJHO     = $this->db->query($sqlPRJHO)->result();
foreach($resPRJHO as $rowPRJHO):
  $PRJCODEHO  = $rowPRJHO->PRJCODE;
endforeach;

$PRJCOST  = 0;
$PRJDATE  = date('Y/m/d');
$PRJDATE_CO = date('Y/m/d');
$isHO     = 0;
$sqlPRJ   = "SELECT PRJDATE, PRJDATE_CO, PRJCOST, isHO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ   = $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
  $PRJCOST  = $rowPRJ->PRJCOST;
  $PRJDATE  = date('Y/m/d', strtotime($rowPRJ->PRJDATE));
  $PRJDATE_CO = date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
  $isHO     = $rowPRJ->isHO;
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
  <style>
      body {
      margin: 0;
      padding: 0;
      background-color: #FAFAFA;
      font: 12pt Times New Roman, Times, serif;
    }
    * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
    }
    .page {
        width: 21cm;
        min-height: 29.7cm;
    padding-left:1.5cm;
    padding-right:1cm;
    padding-top:1cm;
    padding-bottom:1.5cm;
        /*padding: 0.01cm 0.2cm;*/
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
    background-repeat: no-repeat;
    background-size: 550px 300px;
    background-position: center;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
  
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
  </style>
</head>
<body>
  <div class="page">
    <table width="100%" border="0" style="size:auto">
      <tr>
        <td style="vertical-align: middle; text-align: center; line-height: 20px;">
          <img class="logo" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px; float: left;">
          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px">
            <?=$comp_name?><br>
          </span>
          <span style="font-weight:bold; text-transform:uppercase; font-size:20px"><?php echo $h2_title; ?></span>
          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px">
            <br>
          <?php 
            /*$CRDate = date_create($End_Date);
            echo date_format($CRDate,"d-m-Y");*/
            //$StartDate  = date('d M Y', strtotime($Start_Date));
            //$EndDate  = date('d M Y', strtotime($End_Date));
            //echo "$StartDate s.d. $EndDate";
          ?>
          </span>
        </td>
      </tr>
    </table>
    <div style="padding-bottom: 5px;">&nbsp;</div>
    <table width="100%" border="0" style="size:auto;font-family: Arial, Helvetica, sans-serif; border: double black 3.5px; border-left-style: hidden; border-right-style: hidden;border-top-style: hidden;" cellpadding="0" cellspacing="0">
      <tr>
        <td width="550" style="text-align: left; font-size: 12px; font-style: italic;">
          Tanggal Cetak : <?php echo date('d-m-Y H:i:s'); ?>
        </td>
      </tr>
    </table>
    <table width="100%" border="0" style="size:auto;font-family: Arial, Helvetica, sans-serif; border: double black 3.5px; border-left-style: hidden; border-right-style: hidden;border-top-style: hidden;" cellpadding="0" cellspacing="0">
      <tr>
        <td width="550" style="text-align: center;font-weight: bold; font-size: 14px;">
          Description
        </td>
        <td width="200" style="text-align: center;font-weight: bold; font-size: 14px;">
          Saldo
        </td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; border: 1px solid rgba(255, 0, 0, .1); border-left-style: hidden; border-right-style: hidden;" rules="all">
      <tr>
        <td width="550" style="font-weight: bold; border-right-style: hidden;">
          <span style="padding-left: 0px;">ARUS KAS DARI AKTIVITAS OPERASI</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <?php
        if($isHO == 1)
        {
            $ADDQRY1  = "";
            $ADDQRY2  = "";
            $ADDQRY3  = "";
        }
        else
        {
            $ADDQRY1  = "AND PRJCODE = '$PRJCODE'";
            $ADDQRY2  = "AND A.proj_Code = '$PRJCODE'";
            $ADDQRY3  = "AND proj_Code = '$PRJCODE'";
        }

        // Koleksi seluruh journal yang berhubungan dengan kode Akun Bank - JRD_CBD
            $JRD_CBD    = "''";
            $iJRDCBD    = 0;
            $sqlJRDCBD  = "SELECT DISTINCT Z.JournalH_Code
                            FROM
                              tbl_journaldetail Z
                            WHERE
                              Z.Acc_Id IN (SELECT DISTINCT A.Account_Number FROM tbl_chartaccount A
                                WHERE A.Account_Class IN (3, 4) AND A.IsLast = 1)
                            AND Z.GEJ_STAT = 3";
            $resJRDCBD  = $this->db->query($sqlJRDCBD)->result();
            foreach($resJRDCBD as $rowJRDCBD):
              $iJRDCBD  = $iJRDCBD + 1;
              if($iJRDCBD == 1)
                $JRD_CBD  = "'".$rowJRDCBD->JournalH_Code."'";
              else
                $JRD_CBD  = $JRD_CBD.",'".$rowJRDCBD->JournalH_Code."'";
            endforeach;

        // Total Keseluruhan journal yang berhubungan dengan kode Akun Bank - GTOT_GINCB 
        // VERIFIED
            $TOT_GINCB   = 0;
            $sqlGINCB    = "SELECT SUM(A.JournalD_Debet) AS TOTAL_INCB FROM tbl_journaldetail A
                              INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                  AND A.Acc_Id in (SELECT DISTINCT X.Account_Number FROM tbl_chartaccount X
                                    WHERE X.Account_Class IN (3, 4) AND X.IsLast = 1)
                              WHERE
                                A.JournalD_Debet > 0
                                AND B.GEJ_STAT = 3 $ADDQRY2";
            $resGINCB    = $this->db->query($sqlGINCB)->result();
            foreach($resGINCB as $rowGINCB):
              $GTOT_GINCB = $rowGINCB->TOTAL_INCB;
            endforeach;

        // A.1  Penerimaan Kas dari Penjualan
        //      Sumber Data : Penerimaan kas/bank pada menu Keuangan→Penerimaan kas-bank→ Penerimaan kas / bank --> BR
        //      VERIFIED : TOT_INBR
                $TOT_INBR   = 0;
                $sqlINBR    = "SELECT SUM(A.JournalD_Debet) AS TOTAL_INBR FROM tbl_journaldetail A
                                  INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                    AND B.JournalType = 'BR'
                                    AND A.JournalD_Debet > 0
                                    AND B.GEJ_STAT = 3 $ADDQRY2";
                $resINBR    = $this->db->query($sqlINBR)->result();
                foreach($resINBR as $rowINBR):
                  $TOT_INBR = $rowINBR->TOTAL_INBR;
                endforeach;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Penerimaan Kas dari Penjualan</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_INBR,2); ?></td>
      </tr>
      <?php
        // A.2  Penerimaan dari Pendapatan Bunga
        //      Sumber Data : COA Pendapatan Deposito dan Jasa Giro (parents 4104040) - $JRD_BG - $TOT_INBG
        //      VERIFIED : TOT_INBG
                $TOT_INBG   = 0;
                $sqlINBG    = "SELECT SUM(A.JournalD_Kredit - JournalD_Debet) AS TOTAL_INBG FROM tbl_journaldetail A
                                  WHERE Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                      WHERE Account_Category = 4 AND isLast = 1 $ADDQRY1
                                      AND Account_Number LIKE '4104040%') AND GEJ_STAT = 3 $ADDQRY3";
                $resINBG    = $this->db->query($sqlINBG)->result();
                foreach($resINBG as $rowINBG):
                  $TOT_INBG = $rowINBG->TOTAL_INBG;
                endforeach;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Penerimaan dari Pendapatan Bunga</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_INBG,2); ?></td>
      </tr>
      <?php
        // NOT USED
          // A.1COA Pendapatan (in. statement) selain Pend. Usaha (4101) dan Pend. Deposito & Jasa giro Yang Real Masuk Kas / Bank
          // 1. Kolektif semua akun Kas Bank - $ACC_CB
              $ACC_CB   = "''";
              $iCB      = 0;
              $sqlACCCB = "SELECT DISTINCT Account_Number FROM tbl_chartaccount
                              WHERE Account_Class IN (3,4) AND isLast = 1 AND Account_Category = 1";
              $resACCCB = $this->db->query($sqlACCCB)->result();
              foreach($resACCCB as $rowACCCB):
                $iCB    = $iCB+ 1;
                if($iCB == 1)
                  $ACC_CB  = "'".$rowACCCB->Account_Number."'";
                else
                  $ACC_CB  = $ACC_CB.",'".$rowACCCB->Account_Number."'";
              endforeach;

              // HITUNG SEMUA TRANSASKI YANG BERHUBUNGAN DENGAN AKUN KAS BANK
              // LALU AKAN DIKURANGI OLEH Penerimaan Kas dari Penjualan DAN Penerimaan dari Pendapatan Bunga - DEBET
                $GTOT_INCB  = 0;
                /*$sqlGINOCB  = "SELECT SUM(JournalD_Debet) AS GTOTAL_INCB FROM tbl_journaldetail
                                  WHERE Acc_Id IN ($ACC_CB)
                                  AND GEJ_STAT = 3 AND JournalD_Debet > 0 $ADDQRY3";
                $resGINOCB  = $this->db->query($sqlGINOCB)->result();
                foreach($resGINOCB as $rowGINOCB):
                  $GTOT_INCB= $rowGINOCB->GTOTAL_INCB;
                endforeach;*/

          // 2. Kolektif semua akun Kategori 4 (Pendapatan) selain :
          // Pendapatan Deposito dan Jasa Giro (parents 4104040) DAN 
              /*$JRD_OT    = $JRD_BRK.",".$JRD_BG;
              $ACC_OT    = $ACC_BRK.",".$ACC_BG;
              // Karena yang sudah dihitung baru Bertipe BR dengan akun Pendapatan Deposito & Jasa Giro (parents 4104040)
              // Maka yang dijadikan pengecualian dari Income Statemen (Kategori 4) hanya $ACC_BG
              $ACC_OT    = $ACC_BG;*/

              $ACC_OTH   = "''";
              $iOTH      = 0;
              $sqlACCOTH = "SELECT DISTINCT Account_Number FROM tbl_chartaccount 
                              WHERE Account_Number NOT IN ($ACC_CB) AND isLast = 1 AND Account_Category = 4";
              $resACCOTH = $this->db->query($sqlACCOTH)->result();
              foreach($resACCOTH as $rowACCOTH):
                $iOTH    = $iOTH+ 1;
                if($iOTH == 1)
                  $ACC_OTH  = "'".$rowACCOTH->Account_Number."'";
                else
                  $ACC_OTH  = $ACC_OTH.",'".$rowACCOTH->Account_Number."'";
              endforeach;

          // 3. Kolektif semua kode jurnal berdasarkan $ACC_OTH -> $JRD_OTH
            $JRD_OTH    = "''";
            $iJRDOTH    = 0;
            $sqlJRDOTH  = "SELECT DISTINCT JournalH_Code FROM tbl_journaldetail WHERE Acc_Id IN ($ACC_OTH) AND GEJ_STAT = 3 $ADDQRY3";
            $resJRDOTH  = $this->db->query($sqlJRDOTH)->result();
            foreach($resJRDOTH as $rowJRDOTH):
              $iJRDOTH  = $iJRDOTH + 1;
              if($iJRDOTH == 1)
                $JRD_OTH  = "'".$rowJRDOTH->JournalH_Code."'";
              else
                $JRD_OTH  = $JRD_OTH.",'".$rowJRDOTH->JournalH_Code."'";
            endforeach;

        // A.3  Peneriman dari Pendapatan lain
        //      Sumber Data : COA Pendapatan (income statement) selain Pendapatan Usaha dan Pendapatan Deposito & Jasa giro Yang Real Masuk Kas atau Bank
        //      VERIFIED : TOTAL_INOTH -> GTOT_INOTH
                $TOT_INOTH  = 0;
                $sqlINOTH   = "SELECT SUM(JournalD_Debet) AS TOTAL_INOTH FROM tbl_journaldetail
                                  WHERE Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                      WHERE Account_Class IN (3,4) AND isLast = 1 $ADDQRY1)
                                  AND GEJ_STAT = 3 AND JournalD_Debet > 0 $ADDQRY3";
                $resINOTH   = $this->db->query($sqlINOTH)->result();
                foreach($resINOTH as $rowINOTH):
                  $TOT_INOTH= $rowINOTH->TOTAL_INOTH;
                endforeach;

                // NILAI Penerimaan dari Pendapatan lain diambil dari = TOT_INOTH - TOT_INCB - TOT_INBG
                // DIPINDAH KE PALING BAWAH AGAR DIKURANGI :
                // 1. Penerimaan Utang Bank
                // 2. Penerimaan Utang non Bank
                    //$GTOT_INOTH = $TOT_INOTH - $TOT_INBR - $TOT_INBG;


        // A.4  Pembayaran kas kepada:
        //      A.4.1 Pemasok dan Beban Operasi Lainnya
        //            Sumber Data : 1. Total dari pengeluaran pada menu Keuangan → Pengeluaran kas-bank → Pembayaran
        //                          2. Semua transaksi yang pengeluarannya lewat kas atau bank selain yang sudah disebutkan
        //            VERIFIED : TOT_BP
        //            1.  Total dari pengeluaran pada menu Keuangan → Pengeluaran kas-bank → Pembayaran
                          $TOT_BP   = 0;
                          $sql_BP   = "SELECT SUM(A.JournalD_Kredit) AS TOT_BP
                                        FROM tbl_journaldetail A
                                          INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                            AND B.JournalType = 'BP'
                                        WHERE A.Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                            WHERE Account_Class IN (3,4) AND isLast = 1 $ADDQRY1)
                                          AND A.GEJ_STAT = 3 AND A.JournalD_Kredit > 0 $ADDQRY3";
                          $res_BP   = $this->db->query($sql_BP)->result();
                          foreach($res_BP as $row_BP):
                              $TOT_BP  = $row_BP->TOT_BP;
                          endforeach;

        //            2.  Semua transaksi yang pengeluarannya lewat kas atau bank selain yang sudah disebutkan. Artinya selain
        //                a. COA Piutang Karyawan 110410, Hutang Gaji 2113, Gaji dan tunjangan  (parents 801)
        //                b. Pembayaran Beban bunga 84470
        //                c. Pembayaran Hutang Pajak 2105, denda pajak 84401, beban pajak 84480, pajak dibayar dimuka 1107
        //                d. COA utang Bank (Parents 2102)
        //                e. COA Utang Lain (Par ents 2104)
        //                f. COA Laba Rugi Ditahan 32010

        //                $GTOT_PSUPL   = .....


        //      A.4.2 Karyawan dan pihak ketiga lainnya
        //            Sumber Data : COA Piutang Karyawan 110410, Hutang Gaji 2113, Gaji dan tunjangan  (parents 801)
        //            1.  COA Piutang Karyawan 110410
        //                VERIFIED : TOT_AREMP
                          $TOT_AREMP  = 0;
                          $sql_AREMP  = "SELECT SUM(JournalD_Kredit - JournalD_Debet) AS TOT_AREMP FROM tbl_journaldetail
                                              WHERE Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                  WHERE Account_Category = 1 AND isLast = 1 $ADDQRY1
                                                  AND Account_Number LIKE '110410%')
                                              AND GEJ_STAT = 3
                                              -- AND JournalD_Kredit > 0
                                              $ADDQRY3";
                          $res_AREMP  = $this->db->query($sql_AREMP)->result();
                          foreach($res_AREMP as $row_AREMP):
                              $TOT_AREMP  = $row_AREMP->TOT_AREMP;
                          endforeach;

        //            2.  Hutang Gaji 2113
        //                VERIFIED : TOT_HUTGJ
                          $TOT_HUTGJ  = 0;
                          $sql_JHUTGJ = "SELECT SUM(JournalD_Kredit - JournalD_Debet) AS TOT_HUTGJ FROM tbl_journaldetail
                                              WHERE Acc_Id = 2113
                                              AND GEJ_STAT = 3
                                              -- AND JournalD_Debet > 0
                                              $ADDQRY3";
                          $res_JHUTGJ = $this->db->query($sql_JHUTGJ)->result();
                          foreach($res_JHUTGJ as $row_JHUTGJ):
                              $TOT_HUTGJ  = $row_JHUTGJ->TOT_HUTGJ;
                          endforeach;

        //            3.  Gaji dan tunjangan  (parents 801)
        //                VERIFIED : TOT_GTJ
                          $TOT_GTJ      = 0;
                          $sqlJGTJ      = "SELECT SUM(JournalD_Debet - JournalD_Kredit) AS TOT_GTJ FROM tbl_journaldetail
                                              WHERE Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                  WHERE Account_Category = 8 AND isLast = 1 $ADDQRY1
                                                  AND Account_Number LIKE '801%')
                                              AND GEJ_STAT = 3
                                              -- AND JournalD_Debet > 0
                                              $ADDQRY3";
                          $resJGTJ      = $this->db->query($sqlJGTJ)->result();
                          foreach($resJGTJ as $rowJGTJ):
                              $TOT_GTJ  = $rowJGTJ->TOT_GTJ;
                          endforeach;

                          $TOT_KPK  = $TOT_AREMP + $TOT_HUTGJ + $TOT_GTJ;

        //            4.  Pembayaran Bunga : Pembayaran Beban bunga 84470
        //                VERIFIED : TOT_BNG
                          $TOT_BNG      = 0;
                          $sqlJBNG      = "SELECT SUM(JournalD_Debet - JournalD_Kredit) AS TOT_BNG FROM tbl_journaldetail
                                              WHERE Acc_Id = 84470
                                              AND GEJ_STAT = 3
                                              -- AND JournalD_Debet > 0
                                              $ADDQRY3";
                          $resJBNG      = $this->db->query($sqlJBNG)->result();
                          foreach($resJBNG as $rowJBNG):
                              $TOT_BNG  = $rowJBNG->TOT_BNG;
                          endforeach;

                          $TOT_BBG  = $TOT_BNG;

        //            5.  Pembayaran Pajak-Pajak : Pembayaran Hutang Pajak kepala akun 2105
        //                VERIFIED : TOT_HTAX
                          $TOT_HTAX   = 0;
                          $sqlINNBG   = "SELECT SUM(JournalD_Kredit - JournalD_Debet) AS TOT_HTAX FROM tbl_journaldetail
                                              WHERE GEJ_STAT = 3
                                                AND Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                    WHERE Account_Category = 2 AND isLast = 1 $ADDQRY1
                                                    AND Account_Number LIKE '2105%')
                                                -- AND JournalD_Kredit > 0
                                                $ADDQRY3";
                          $resINNBG   = $this->db->query($sqlINNBG)->result();
                          foreach($resINNBG as $rowINNBG):
                            $TOT_HTAX = $rowINNBG->TOT_HTAX;
                          endforeach;

        //            6.  Pembayaran Pajak-Pajak : denda pajak 84401
        //                VERIFIED : TOT_DTAX
                          $TOT_DTAX       = 0;
                          $sqlJDTAX       = "SELECT SUM(JournalD_Debet - JournalD_Kredit) AS TOT_DTAX FROM tbl_journaldetail
                                              WHERE Acc_Id = 84401
                                              AND GEJ_STAT = 3
                                              -- AND JournalD_Debet > 0
                                              $ADDQRY3";
                          $resJHDTAX      = $this->db->query($sqlJDTAX)->result();
                          foreach($resJHDTAX as $rowJDTAX):
                              $TOT_DTAX   = $rowJDTAX->TOT_DTAX;
                          endforeach;

        //            7.  Pembayaran Pajak-Pajak : beban pajak 84480
        //                VERIFIED : TOT_BTAX
                          $TOT_BTAX     = 0;
                          $sqlJBTAX     = "SELECT SUM(JournalD_Debet - JournalD_Kredit) AS TOT_BTAX FROM tbl_journaldetail
                                            WHERE Acc_Id = 84480
                                            AND GEJ_STAT = 3
                                            -- AND JournalD_Debet > 0
                                            $ADDQRY3";
                          $resJHBTAX    = $this->db->query($sqlJBTAX)->result();
                          foreach($resJHBTAX as $rowJBTAX):
                              $TOT_BTAX = $rowJBTAX->TOT_BTAX;
                          endforeach;

        //            8.  Pembayaran Pajak-Pajak : pajak dibayar dimuka kepala akun 1107
        //                VERIFIED : TOT_UMTX
                          $TOT_UMTX     = 0;
                          $sqlJUMTX     = "SELECT SUM(JournalD_Debet - JournalD_Kredit) AS TOT_UMTX FROM tbl_journaldetail
                                            WHERE Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                  WHERE Account_Category = 1 AND isLast = 1 $ADDQRY1
                                                  AND Account_Number LIKE '1107%')
                                            AND GEJ_STAT = 3
                                            -- AND JournalD_Debet > 0
                                            $ADDQRY3";
                          $resJHUMTX    = $this->db->query($sqlJUMTX)->result();
                          foreach($resJHUMTX as $rowJUMTX):
                              $TOT_UMTX = $rowJUMTX->TOT_UMTX;
                          endforeach;

                          $TOT_PTAX = $TOT_HTAX + $TOT_DTAX + $TOT_BTAX + $TOT_UMTX;

        //            9.  Penerimaan dan Pembayaran Utang Bank : COA utang Bank (Parents 2102)
        //                MS.191107083030-00245 : Maaf pak ada koreksi untuk Hutang Non Bank akun yang diperhitungkan adalah akun dalam passiva KECUALI 2101 HUTANG USAHA (Parents), 2102 HUTANG BANK (Parents), 2105 HUTANG PAJAK. Posisi Kas/Bank ketika penerimaan dari hutang non bank ini adalah posisi Debet, sedangkan ketika membayar hutang ini adalah posisi kredit.
        //                Penerimaan Utang Bank : COA utang Bank (Parents 2102)
        //                VERIFIED : TOT_UTBG
                          $TOT_UTBG    = 0;
                          $sqlINNBG     = "SELECT SUM(JournalD_Debet) AS TOTAL_UTBG FROM tbl_journaldetail
                                            WHERE GEJ_STAT = 3 AND JournalD_Debet > 0
                                              AND Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                  WHERE Account_Category = 2 AND isLast = 1 $ADDQRY1
                                                  AND (Account_Number LIKE '2102%'))
                                            $ADDQRY3";
                          $resINNBG     = $this->db->query($sqlINNBG)->result();
                          foreach($resINNBG as $rowINNBG):
                            $TOT_UTBG  = $rowINNBG->TOTAL_UTBG;
                          endforeach;

        //                Pembayaran Utang Bank : COA utang Bank (Parents 2102)
        //                VERIFIED : TOT_PTBG
                          $TOT_PTBG    = 0;
                          $sqlINNBG     = "SELECT SUM(JournalD_Kredit) AS TOTAL_PTBG FROM tbl_journaldetail
                                            WHERE GEJ_STAT = 3 AND JournalD_Kredit > 0
                                              AND Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                  WHERE Account_Category = 2 AND isLast = 1 $ADDQRY1
                                                  AND (Account_Number LIKE '2102%'))
                                            $ADDQRY3";
                          $resINNBG     = $this->db->query($sqlINNBG)->result();
                          foreach($resINNBG as $rowINNBG):
                            $TOT_PTBG  = $rowINNBG->TOTAL_PTBG;
                          endforeach;

        //            10. Pembayaran Utang Non Bank : COA Utang Lain (Parents 2104)
        //                VERIFIED : TOT_PUNBG
        //                MS.191107083030-00245 : Maaf pak ada koreksi untuk Hutang Non Bank akun yang diperhitungkan adalah akun dalam passiva KECUALI 2101 HUTANG USAHA (Parents), 2102 HUTANG BANK (Parents), 2105 HUTANG PAJAK. Posisi Kas/Bank ketika penerimaan dari hutang non bank ini adalah posisi Debet, sedangkan ketika membayar hutang ini adalah posisi kredit.
        //                Penerimaan Utang Non Bank : COA Utang Lain (Parents 2104)
        //                VERIFIED : TOT_INNBG
                          $TOT_INNBG    = 0;
                          $sqlINNBG     = "SELECT SUM(JournalD_Debet) AS TOTAL_INNBG FROM tbl_journaldetail
                                          WHERE GEJ_STAT = 3 AND JournalD_Debet > 0
                                            AND Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                WHERE Account_Category = 2 AND isLast = 1 $ADDQRY1
                                                AND Account_Number NOT LIKE '2101%' AND Account_Number NOT LIKE '2102%' AND Account_Number NOT LIKE '2105%')
                                          $ADDQRY3";
                          $resINNBG     = $this->db->query($sqlINNBG)->result();
                          foreach($resINNBG as $rowINNBG):
                            $TOT_INNBG  = $rowINNBG->TOTAL_INNBG;
                          endforeach;

        //                Pembayaran Utang Non Bank : COA Utang Lain (Parents 2104)
        //                VERIFIED : TOT_PUNBG
                          $TOT_PUNBG    = 0;
                          $sqlINNBG     = "SELECT SUM(JournalD_Kredit) AS TOTAL_PUNBG FROM tbl_journaldetail
                                          WHERE GEJ_STAT = 3 AND JournalD_Kredit > 0
                                            AND Acc_Id IN (SELECT Account_Number FROM tbl_chartaccount
                                                WHERE Account_Category = 2 AND isLast = 1 $ADDQRY1
                                                AND Account_Number NOT LIKE '2101%' AND Account_Number NOT LIKE '2102%' AND Account_Number NOT LIKE '2105%')
                                          $ADDQRY3";
                          $resINNBG     = $this->db->query($sqlINNBG)->result();
                          foreach($resINNBG as $rowINNBG):
                            $TOT_PUNBG  = $rowINNBG->TOTAL_PUNBG;
                          endforeach;

        //            11. Pembayaran Dividen : COA Laba Rugi Ditahan 32010
        //                VERIFIED : TOT_PDIV
                          $TOT_PDIV   = 0;
                          $sqlPDIV    = "SELECT SUM(JournalD_Kredit - JournalD_Debet) AS TOTAL_PDIV FROM tbl_journaldetail
                                          WHERE Acc_Id LIKE '32010%'
                                          AND GEJ_STAT = 3 AND JournalD_Kredit > 0 $ADDQRY3";
                          $resPDIV    = $this->db->query($sqlPDIV)->result();
                          foreach($resPDIV as $rowPDIV):
                            $TOT_PDIV  = $rowPDIV->TOTAL_PDIV;
                          endforeach;            

        //            13. Total Pengeluaran
        //                CATAT SEMUA TRANSAKSI KAS BANK DI KREDIT, KEMUDIAN DIKURANGI OLEH SEMUA TRX DI ATAS
                          $GTOT_OUTCB   = 0;
                          $sqlOUTCB     = "SELECT SUM(A.JournalD_Kredit) AS TOTAL_OUTCB FROM tbl_journaldetail A
                                            INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                              AND A.Acc_Id IN (SELECT DISTINCT X.Account_Number FROM tbl_chartaccount X
                                                  WHERE X.Account_Class IN (3, 4) AND X.IsLast = 1)
                                              WHERE 
                                              A.JournalD_Kredit > 0
                                              AND B.GEJ_STAT = 3
                                              $ADDQRY2";
                          $resOUTCB     = $this->db->query($sqlOUTCB)->result();
                          foreach($resOUTCB as $rowOUTCB):
                            $GTOT_OUTCB = $rowOUTCB->TOTAL_OUTCB;
                          endforeach;


        //  Penerimaan dari Pendapatan lain
        //  Sumber Data : COA Pendapatan (income statement) selain Pendapatan Usaha dan Pendapatan Deposito & Jasa giro Yang Real Masuk Kas atau Bank
        //  VERIFIED : TOTAL_INOTH -> GTOT_INOTH
            $GTOT_INOTH = $TOT_INOTH - $TOT_INBR - $TOT_INBG - $TOT_UTBG - $TOT_INNBG;

        //  GRAND TOTAL
            $GTOT_GOUTCB  = $GTOT_OUTCB - $TOT_KPK - $TOT_BBG - $TOT_PTAX - $TOT_PTBG - $TOT_PUNBG - $TOT_PDIV;
            //$GTOT_GOUTCB  = $GTOT_OUTCB - $TOT_KPK - $TOT_BBG - $TOT_PTAX;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Penerimaan dari Pendapatan lain</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($GTOT_INOTH,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">Pembayaran kas kepada:</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 20px;">Pemasok dan Beban Operasi Lainnya</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($GTOT_GOUTCB,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 20px;">Karyawan dan pihak ketiga lainnya</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_KPK,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Pembayaran Bunga</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_BBG,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Pembayaran Pajak-Pajak</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_PTAX,2); ?></td>
      </tr>
      <?php
        //$TOT_01 = $TOT_INCB + $TOT_INBG + $TOT_INOTH - $TOT_OUTCBP - $TOT_KPK - $TOT_BBG - $TOT_BTAX;
        $TOT_01 = $TOT_INBR + $TOT_INBG + $GTOT_INOTH - $GTOT_GOUTCB - $TOT_KPK - $TOT_BBG - $TOT_PTAX;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">Kas Bersih yang diperoleh dari aktivitas operasi</span>
        </td>
        <td width="200" style="text-align: right; font-weight: bold; "><?php echo number_format($TOT_01,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">&nbsp;</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 0px;font-weight: bold;">ARUS KAS DARI AKTIVITAS INVESTASI</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <?php
          // Perolehan / Income Aset tetap : Aset Tetap (Parents 12011) $TOT_INAST
          // VERIFIED
            $TOT_INAST      = 0;
            $sqlINAST       = "SELECT SUM(JournalD_Debet) AS TOTAL_INAST FROM tbl_journaldetail
                                WHERE Acc_Id LIKE '12011%' AND JournalH_Code IN ($JRD_CBD)
                                AND GEJ_STAT = 3 AND JournalD_Debet > 0 $ADDQRY3";
            $resINAST       = $this->db->query($sqlINAST)->result();
            foreach($resINAST as $rowJRDAREMP):
                $TOT_INAST  = $rowJRDAREMP->TOTAL_INAST;
            endforeach;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Perolehan Aset Tetap</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_INAST,2); ?></td>
      </tr>
      <?php
          // Penjualan Aset Tetap : Aset Tetap (Parents 12011) $TOT_OUTAST
          // NOT VERIFIED
              $TOT_OUTAST     = 0;
              $sqlOUTAST      = "SELECT SUM(JournalD_Kredit) AS TOTAL_OUTAST FROM tbl_journaldetail
                                  WHERE Acc_Id LIKE '12011%' AND JournalH_Code IN ($JRD_CBD)
                                  AND GEJ_STAT = 3 AND JournalD_Kredit > 0 $ADDQRY3";
              $resOUTAST      = $this->db->query($sqlOUTAST)->result();
              foreach($resOUTAST as $rowOUTAST):
                  $TOT_OUTAST = $rowOUTAST->TOTAL_OUTAST;
              endforeach;

          // Kas Bersih yang digunakan untuk aktivitas investasi
              $GTOT_AST   = $TOT_INAST + $TOT_OUTAST;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Penjualan Aset Tetap</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_OUTAST,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px; font-weight: bold;">Kas Bersih yang digunakan untuk aktivitas investasi</span>
        </td>
        <td width="200" style="text-align: right;font-weight: bold;"><?php echo number_format($GTOT_AST,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">&nbsp;</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 0px;font-weight: bold;">ARUS KAS DARI AKTIVITAS PENDANAAN</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Penerimaan Utang Bank</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_UTBG,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Pembayaran Utang Bank</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_PTBG,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Penerimaan Utang non Bank</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_INNBG,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Pembayaran Utang non bank</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_PUNBG,2); ?></td>
      </tr>
      <?php
          $GTOT_OTHC  = $TOT_UTBG - $TOT_PTBG + $TOT_INNBG - $TOT_PUNBG - $TOT_PDIV;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;">Pembayaran Deviden</span>
        </td>
        <td width="200" style="text-align: right;"><?php echo number_format($TOT_PDIV,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">Kas Bersih yang diperoleh dari aktivitas (digunakan untuk) pendanaan</span>
        </td>
        <td width="200" style="text-align: right;font-weight: bold;"><?php echo number_format($GTOT_OTHC,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">&nbsp;</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <?php
        $TOT_KPKS = $TOT_01 + $GTOT_AST + $GTOT_OTHC;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 0px;font-weight: bold;">Kenaikan (Penurunan) Kas dan Setara Kas</span>
        </td>
        <td width="200" style="text-align: right;font-weight: bold;"><?php echo number_format($TOT_KPKS,2); ?></td>
      </tr>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 10px;font-weight: bold;">&nbsp;</span>
        </td>
        <td width="200">&nbsp;</td>
      </tr>
      <?php
        $TOT_KSKAWT = 0;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 0px;font-weight: bold;">Kas dan Setara Kas awal Tahun</span>
        </td>
        <td width="200" style="text-align: right;font-weight: bold;"><?php echo number_format($TOT_KSKAWT,2); ?></td>
      </tr>
      <?php
        $TOT_KSKAKT = $TOT_KSKAWT + $TOT_KPKS;
      ?>
      <tr>
        <td width="550" style="border-right-style: hidden;">
          <span style="padding-left: 0px;font-weight: bold;">Kas dan Setara Kas Akhir Periode</span>
        </td>
        <td width="200" style="text-align: right;font-weight: bold;"><?php echo number_format($TOT_KSKAKT,2); ?></td>
      </tr>
    </table>
  </div>
</table>
</body>
</html>