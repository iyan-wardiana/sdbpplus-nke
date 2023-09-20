<?php
class moneyFormat
{ 
    public function rupiah ($angka) 
    {
        $rupiah = number_format($angka ,2, ',' , '.' );
        return $rupiah;
    }
 
    public function terbilang ($angka)
    {
        $angka = (float)$angka;
        $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . ' Belas';
        } else if ($angka < 100) {
            $hasil_bagi = (int)($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) {
            return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) {
            $hasil_bagi = (int)($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 2000) {
            return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
        } else if ($angka < 1000000) {
            $hasil_bagi = (int)($angka / 1000); 
            $hasil_mod = $angka % 1000;
            return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
        } else if ($angka < 1000000000) {
            $hasil_bagi = (int)($angka / 1000000);
            $hasil_mod = $angka % 1000000;
            return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) {
            $hasil_bagi = (int)($angka / 1000000000);
            $hasil_mod = fmod($angka, 1000000000);
            return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) {
            $hasil_bagi = $angka / 1000000000000;
            $hasil_mod = fmod($angka, 1000000000000);
            return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else {
            return 'Data Salah';
        }
    }
}

$moneyFormat = new moneyFormat();

// $EMPNAME = $this->db->select("CONCAT(First_Name,' ',Last_Name) AS FullName", false)->from("tbl_employee")->where(["Emp_ID" => $SPLCODE, "Employee_status" => 1])->get()->row("FullName");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/contract.png'; ?>" sizes="32x32">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        /*@page { margin: 0 }*/
        body { margin: 0 }
        .sheet {
          margin: 0;
          overflow: hidden;
          position: relative;
          box-sizing: border-box;
          page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; }
        body.A3.landscape     .sheet { width: 420mm; }
        body.A4               .sheet { width: 210mm; }
        body.A4.landscape     .sheet { width: 297mm; }
        body.A5               .sheet { width: 148mm; }
        body.A5.landscape     .sheet { width: 210mm; }
        body.letter           .sheet { width: 216mm; }
        body.letter.landscape .sheet { width: 280mm; }
        body.legal            .sheet { width: 216mm; }
        body.legal.landscape  .sheet { width: 357mm; }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 1cm 1cm 0.97cm 1cm }

        /** For screen preview **/
        @media screen {
          body { background: #e0e0e0 }
          .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
            margin: 5mm auto;
            border-radius: 5px 5px 5px 5px;
          }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
          @page { size: a4;}
          body.A3.landscape { width: 420mm }
          body.A3, body.A4.landscape { width: 297mm }
          body.A4, body.A5.landscape { width: 210mm }
          body.A5                    { width: 148mm }
          body.letter, body.legal    { width: 216mm }
          body.letter.landscape      { width: 280mm }
          body.legal.landscape       { width: 357mm }
        }

        .cont {
            position: relative;
            /*border: 2px solid;*/
            font-family: "Arial";
            font-size: 8pt;
        }
        .box-header {
            position: relative;
            width: 100%;
            height: 70px;
            padding: 5px;
            /*border-bottom: 2px solid;*/
        }
        .box-header .box-column-logo {
            float: left;
            width: 20%;
            /*border: 1px solid;*/
        }
        .box-header .box-column-title {
            position: absolute;
            top: 10px;
            float: left;
            width: 100%;
            /*border: 1px solid;*/
            text-align: center;
            box-sizing: border-box;
        }
        .box-header .box-column-title > span {
            font-size: 12pt;
            font-weight: bold;
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 5cm;
        }
        .box-header-detail table td {
            /*background-color: gold;*/
            padding: 5px;
        }
        .box-header .pageno {
            float: right;
            width: 150px;
            margin-top: 15px;
            /*background-color: yellow;*/
        }
        .box-header .pageno table td {
            border: hidden;
        }
        .box-detail table thead th, tbody td, tfoot td {
            padding: 2px;
        }
        .box-detail table thead th {
            text-align: center;
        }
        .box-detail#detail_JurnalHutang {
            border: 1px solid;
            border-radius: 20px 20px 20px 20px;
        }
        .box-detail#detail_JurnalHutang table thead th {
            border-bottom: 1px solid;
            padding: 10px;
        }
        .box-detail#detail_JurnalHutang table tbody td {
            padding: 3px;
        }
        .box-footer .box-asign-1 {
            float: left;
        }
        .box-footer .box-asign-1 table td {
            border: 1px solid;
            width: 100px;
        }
        .box-footer .box-asign-2 {
            float: right;
        }
        .box-footer .box-asign-2 table td {
            border: 1px solid;
        }
        .box-asign-1, .box-asign-2 {
            margin-top: 10px;
        }
        .box-asign-1, .box-asign-2 table td {
            text-align: center;
        }
        ul.dashed {
            list-style-type: none;
            padding-left: 5px;
        }
        ul.dashed > li {
            text-indent: -8px;
        }
        ul.dashed > li::before {
            content: "- ";
            text-indent: -5px;
        }
        #Layer1 {
            position: absolute;
            top: 10px;
            left: 720px;
        }
    </style>
    <?php
        $INV_NUM        = $default['INV_NUM'];
        $INV_CODE       = $default['INV_CODE'];
        $INV_TYPE       = $default['INV_TYPE'];
        $PO_NUM         = $default['PO_NUM'];
        $IR_NUM         = $default['IR_NUM'];
        $PRJCODE        = $default['PRJCODE'];
        $PRJCODEVW      = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
        $INV_DATE       = $default['INV_DATE'];
        $INV_DUEDATE    = $default['INV_DUEDATE'];
        $SPLCODE        = $default['SPLCODE'];
        $INV_CURRENCY   = $default['INV_CURRENCY'];
        $INV_TAXCURR    = $default['INV_TAXCURR'];
        $DP_NUM         = $default['DP_NUM'];
        $DP_AMOUNT      = $default['DP_AMOUNT'];
        $INV_AMOUNT     = $default['INV_AMOUNT'];
        $INV_AMOUNT_PPN = $default['INV_AMOUNT_PPN'];
        //$INV_AMOUNT_PPH = $default['INV_AMOUNT_PPH'];
        $INV_AMOUNT_PPH = 0;
        // START : CACATAN 29-08-2022. BY DIAN HERMANTO
            // KHUSUS PPH AKAN MENYESUAIKAN APAKAH PPHNYA FINAL ATAU NON-FINAL. PPH-FINAL TIDAK AKAN MEMBENTUK JURNAL PPH PADA SAAT VOUCHER, NAMUN TERBENTUK SAAT PEMBAYARAN. SEHINGGA PADA SAAT PRINT OUT VOUCHER, PPH FINAL TIDAK PERLU DIMUNCULKAN
            /*$s_PPH     = "SELECT SUM(A.Base_Debet) AS TOT_PPHNONFIN FROM tbl_journaldetail A
                                INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE AND B.isPPhFinal = 0
                            WHERE A.isTax = 1 AND A.JournalH_Code = '$INV_NUM' AND A.GEJ_STAT = 3 AND A.proj_Code = '$PRJCODE'";*/


            // HASIL DISKUSI DENGAN BU LYTA, PAK EDY DAN PAK LUKI DI AKUNTING TGL. 31-08-2022 BAHWA PPH FINAL/NON-FINAL TETAP DIMUNCULKAN DI PRINT OUT VOUCHER HUTANG
            /*$s_PPH     = "SELECT SUM(ITM_AMOUNT_PPH) AS TOT_PPHNONFIN FROM tbl_pinv_detail WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE'";
            $r_PPH     = $this->db->query($s_PPH)->result();
            foreach($r_PPH as $rw_PPH) :
                $INV_AMOUNT_PPH     = $rw_PPH->TOT_PPHNONFIN;
            endforeach;*/

            $TOT_PPH    = 0;            // TOTAL PPH NON FINAL
            $TOT_PPHF   = 0;            // TOTAL PPH FINAL
            $s_01       = "SELECT A.ITM_AMOUNT_PPH, A.TAXCODE_PPH FROM tbl_pinv_detail A
                            WHERE A.INV_NUM = '$INV_NUM' AND A.PRJCODE = '$PRJCODE'";
            $r_01       = $this->db->query($s_01)->result();
            foreach($r_01 as $rw_01):
                $ITM_AMOUNT_PPH = $rw_01->ITM_AMOUNT_PPH;
                $TAXCODE_PPH    = $rw_01->TAXCODE_PPH;

                $isPPhFin       = 0;
                $s_ACCPPH       = "SELECT isPPhFinal FROM tbl_chartaccount_$PRJCODEVW
                                        WHERE PRJCODE = '$PRJCODE' AND Account_Number IN (SELECT TAXLA_LINKIN FROM tbl_tax_la WHERE TAXLA_NUM = '$TAXCODE_PPH') LIMIT 1";
                $r_ACCPPH       = $this->db->query($s_ACCPPH)->result();
                foreach($r_ACCPPH as $rw_ACCPPH):
                    $isPPhFin   = $rw_ACCPPH->isPPhFinal;
                endforeach;
                if($isPPhFin == 0)
                {
                    $TOT_PPH    = $TOT_PPH+$ITM_AMOUNT_PPH;
                    $TOT_PPHF   = $TOT_PPHF+0;
                    $TOT_PPHV   = $TOT_PPH;
                    $PPH_DESC   = "";
                }
                else
                {
                    /*$TOT_PPH    = 0;
                    $TOT_PPHF   = $TOT_PPHF+$ITM_AMOUNT_PPH;
                    $TOT_PPHV   = $TOT_PPHF;*/
                    $TOT_PPH    = $TOT_PPH+$ITM_AMOUNT_PPH;
                    $TOT_PPHF   = $TOT_PPHF+0;
                    $TOT_PPHV   = $TOT_PPH;
                    $PPH_DESC   = "(F I N A L)";
                }
            endforeach;

            // CEK APAKAH DI HEADER MEMILIH MANUAL PPH
                $VOCPPH    = 0;
                $s_02       = "SELECT A.INV_AMOUNT_PPH FROM tbl_pinv_header A WHERE A.INV_NUM = '$INV_NUM' AND A.PRJCODE = '$PRJCODE'";
                $r_02       = $this->db->query($s_02)->result();
                foreach($r_02 as $rw_02):
                    $VOCPPH = $rw_02->INV_AMOUNT_PPH;
                endforeach;
                if($VOCPPH > 0)
                {
                    $TOT_PPH        = $VOCPPH;
                    $TOT_PPHV       = $VOCPPH;
                    $ITM_AMOUNT_PPH = $VOCPPH;
                }

            $INV_AMOUNT_PPH     = $TOT_PPH;
        // END : CACATAN 29-08-2022. BY DIAN HERMANTO

        $INV_AMOUNT_POT = $default['INV_AMOUNT_POT'];
        $INV_AMOUNT_POTOTH = $default['INV_AMOUNT_POTOTH'];
        $INV_AMOUNT_DPB = $default['INV_AMOUNT_DPB'];
        $INV_AMOUNT_RET = $default['INV_AMOUNT_RET'];
        $INV_AMOUNT_POTUM = $default['INV_AMOUNT_POTUM'];
        $INV_AMOUNT_OTH = $default['INV_AMOUNT_OTH'];

        // $INV_AMOUNT_BASE = $default['INV_AMOUNT_BASE'];
        $INV_TERM       = $default['INV_TERM'];
        $INV_STAT       = $default['INV_STAT'];
        $INV_PAYSTAT    = $default['INV_PAYSTAT'];
        $COMPANY_ID     = $default['COMPANY_ID'];
        $VENDINV_NUM    = $default['VENDINV_NUM'];
        $INV_NOTES      = $default['INV_NOTES'];
        
        $INV_DPPTOT     = $INV_AMOUNT - $INV_AMOUNT_RET; // Nilai DPP sudah dipotong retensi => update 2022-07-12

        $INV_TOTPOT     = $INV_AMOUNT_PPH + $INV_AMOUNT_DPB + $INV_AMOUNT_POTUM + $INV_AMOUNT_POT + $INV_AMOUNT_POTOTH;

        $INV_TOTCOST    = $INV_DPPTOT + $INV_AMOUNT_PPN + $INV_AMOUNT_OTH - $INV_TOTPOT;

        $sqlPRJ     = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
        $resPRJ     = $this->db->query($sqlPRJ)->result();
        foreach($resPRJ as $rowPRJ) :
            $PRJNAME= $rowPRJ->PRJNAME;
        endforeach;

        $SPLDESC    = '';
        $SPLADD1    = '';
        $SPLNOREK    = '';
        $SPLBANK    = '';
        $this->db->distinct("SPLCODE, SPLDESC, SPLADD1, SPLNOREK, SPLBANK")->where(["SPLCODE" => $SPLCODE, "SPLSTAT" => 1]);
        $getSPL     = $this->db->get("tbl_supplier");
        if($getSPL->num_rows() > 0)
        {
            foreach($getSPL->result() as $r):
                $SPLCODE    = $r->SPLCODE;
                $SPLDESC    = $r->SPLDESC;
                $SPLADD1    = $r->SPLADD1;
                $SPLNOREK   = $r->SPLNOREK;
                $SPLBANK    = $r->SPLBANK;
            endforeach;
        }

        // get INV Detail
        $PO_CODE    = '';
        $TTK_CODE   = '';
        $this->db->select("INV_NUM, TTK_NUM, TTK_CODE")->where(["INV_NUM" => $INV_NUM, "PRJCODE" => $PRJCODE]);
        $getINVDET  = $this->db->get("tbl_pinv_detail");
        if($getINVDET->num_rows() > 0)
        {
            $rowINV = 0;
            $arrTTK_NUM = [];
            foreach($getINVDET->result() as $rINV):
                $rowINV     = $rowINV + 1;
                $INV_NUM1   = $rINV->INV_NUM;
                $TTK_NUM1   = $rINV->TTK_NUM;
                $TTK_CODE1  = $rINV->TTK_CODE;
                $arrTTK_NUM[] = $TTK_NUM1;
                if($rowINV == 1)
                    $TTK_CODE = $TTK_CODE1;
                else
                    $TTK_CODE = "$TTK_CODE1, $TTK_CODE";
            endforeach;

            // PO / SPK in TTK detail
            $TTK_REF2_CODE = "";
            // $this->db->distinct("TTK_REF2_CODE")->where_in("TTK_NUM", $arrTTK_NUM)->where("PRJCODE", $PRJCODE);
            // $getTTK = $this->db->get("tbl_ttk_detail");
            /*$getTTK     = "SELECT DISTINCT TTK_REF2_NUM, TTK_REF2_CODE FROM tbl_ttk_detail
                            WHERE PRJCODE = '$PRJCODE' AND TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE PRJCODE = '$PRJCODE' AND INV_NUM = '$INV_NUM')";*/
            $getTTK     = "SELECT DISTINCT TTK_REF2_NUM, TTK_REF2_CODE FROM tbl_ttk_detail
                            WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE PRJCODE = '$PRJCODE' AND INV_NUM = '$INV_NUM')";
            $resTTK     = $this->db->query($getTTK);
            if($resTTK->num_rows() > 0)
            {
                $rowTTK = 0;
                $TTK_REF2_CODE = [];
                foreach($resTTK->result() as $rTTK):
                    $rowTTK             = $rowTTK + 1;
                    // $TTK_REF2_NUM       = $rTTK->TTK_REF2_NUM;
                    $TTK_REF2_CODE[]    = $rTTK->TTK_REF2_CODE;
                endforeach;

                $TTK_REF2_CODE = join(", ", $TTK_REF2_CODE);
            }

            // Get No. Seri Pajak
            $TTKT_TAXNO = '';
            $this->db->select("TTKT_TAXNO")->where_in("TTK_NUM", $arrTTK_NUM)->where("PRJCODE", $PRJCODE);
            $getTaxSer = $this->db->get("tbl_ttk_tax");
            if($getTaxSer->num_rows() > 0)
            {
                $rowTax = 0;
                foreach($getTaxSer->result() as $rTax):
                    $rowTax         = $rowTax + 1;
                    $TTKT_TAXNO1    = $rTax->TTKT_TAXNO;
                    if ($rowTax == 1) 
                    {
                        $TTKT_TAXNO = $TTKT_TAXNO1;
                    }
                    else
                    {
                        $TTKT_TAXNO = "$TTKT_TAXNO1, $TTKT_TAXNO";
                    }
                endforeach;
            }
        }

    ?>
</head>
<body class="page A4">
    <section class="page sheet custom">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span>VOUCHER</span>
                </div>
            </div>
            <div class="box-header-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="70">NO.</td>
                        <td width="5">:</td>
                        <td width="400">&nbsp;</td>
                        <td width="70">PROYEK.</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $PRJCODE; ?></td>
                    </tr>
                    <tr>
                        <td width="70">DATE</td>
                        <td width="5">:</td>
                        <td width="400">&nbsp;<?php echo date('d M Y', strtotime($INV_DATE)); ?></td>
                        <td width="70">TTK NO.</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $TTK_CODE; ?></td>
                    </tr>
                    <tr>
                        <td width="70">PAY TO</td>
                        <td width="5">:</td>
                        <td width="400">&nbsp;<?php echo "$SPLDESC - $SPLBANK : $SPLNOREK"; ?></td>
                        <td width="70">PO NO.</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $TTK_REF2_CODE; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="100">ITEM</th>
                            <th>DESCRIPTION</th>
                            <th width="200">AMOUNT (RP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Pembayaran Tagihan No.&nbsp;<?php echo $INV_CODE; ?></td>
                            <td width="200" style="text-align: right;"><?php echo number_format($INV_DPPTOT, 2); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Nomor Seri Pajak : &nbsp;<?php echo $TTKT_TAXNO; ?></td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td><?php echo $PRJNAME; ?></td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Uang Muka</td>
                            <td width="200" style="text-align: right;">(<?php echo number_format($INV_AMOUNT_DPB, 2); ?>)&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Biaya Lainnya</td>
                            <td width="200" style="text-align: right;"><?php echo number_format($INV_AMOUNT_OTH, 2); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Potongan Lain-lain</td>
                            <td width="200" style="text-align: right;">(<?php echo number_format($INV_AMOUNT_POTOTH, 2); ?>)&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>PPN</td>
                            <td width="200" style="text-align: right;"><?php echo number_format($INV_AMOUNT_PPN, 2); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>PPh <?=$PPH_DESC?></td>
                            <td width="200" style="text-align: right;">(<?php echo number_format($TOT_PPHV, 2); ?>)&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;Jatuh Tempo Tgl <?php echo date('d-m-Y', strtotime($INV_DUEDATE)); ?></td>
                            <td width="200">&nbsp;</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="border-right: hidden; text-align: right;">Terbilang :</td>
                            <td>&nbsp;<?php echo $moneyFormat->terbilang($INV_TOTCOST); ?></td>
                            <td style="text-align: right;"><?php echo number_format($INV_TOTCOST, 2); ?>&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-asign-1">
                <table width="75%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>PREPARED BY</td>
                        <td>CERTIFIED BY</td>
                        <td>APPROVED BY</td>
                        <td>RECEIVED BY</td>
                        <td>POSTED BY</td>
                    </tr>
                    <tr style="height: 50px">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="box-asign-2">
                <table width="50%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="3">FOR ACCOUNTING USE ONLY</td>
                    </tr>
                    <tr>
                        <td width="100" rowspan="2">A/C CODE</td>
                        <td colspan="2">TOTAL</td>
                    </tr>
                    <tr>
                        <td>DR</td>
                        <td>CR</td>
                    </tr>
                    <tr style="height: 50px">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
    <?php
        // check jurnal
        $get_JINV = "";
    ?>
    <section class="page sheet custom">
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title" style="padding-top: 15px;">
                    <span>JOURNAL HUTANG</span>
                </div>
                <div class="pageno">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="3"><?php echo $INV_CODE; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="50">Halaman</td>
                            <td width="5">:</td>
                            <td>001/001</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-header-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50">Supplier</td>
                        <td width="10">:</td>
                        <td>&nbsp;<?php echo "$SPLCODE - $SPLDESC"; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-detail" id="detail_JurnalHutang">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">NO.</th>
                            <th>KETERANGAN</th>
                            <th width="50">Proyek</th>
                            <th width="100">No. Perk.</th>
                            <th width="100">Kode Item</th>
                            <th width="100">Debit</th>
                            <th width="100">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // get Journal hutang voucher OPN / IR

                            // START : CACATAN 29-08-2022. BY DIAN HERMANTO
                                // DETAIL JURNAL AKAN DIAMBIL DARI JURNAL LPM/OPNAME KARENA PADA SAAT VOUCHER TIDAK MEMBENTUK JURNAL APAPUN SELAIN DARI PPN/PPH NON-FINAL
                                // UNTUK OPNAME YANG MEMILIKI RETENSI MAKA AKAN DITAMPILKAN PADA SAAT JURNAL VOUCHER

                                // 1. DAPATKAN NO LP/OPN DARI TTK
                                    /*$s_00   = "SELECT A.TTK_REF1_NUM, B.TTK_CATEG FROM tbl_ttk_detail A
                                                    INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
                                                WHERE A.TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE INV_NUM = '$INV_NUM') AND A.PRJCODE = '$PRJCODE'";
                                    $r_00   = $this->db->query($s_00)->result();
                                    foreach($r_00 as $rw_00):
                                        $REF_DOC  = $rw_00->TTK_REF1_NUM;
                                        $REF_CAT  = $rw_00->TTK_CATEG;
                                    endforeach;*/

                                $s_SQL      = "SELECT A.JournalH_Code, A.proj_Code, A.Acc_Id, A.Acc_Name, A.ITM_CODE, C.ITM_NAME, A.Journal_DK,
                                                   A.Base_Debet, A.Base_Kredit, A.Manual_No, A.Other_Desc, A.ISRET, A.Ref_Number
                                                FROM tbl_journaldetail A
                                                INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE AND B.isPPhFinal = 0
                                                LEFT JOIN tbl_item C ON C.ITM_CODE = A.ITM_CODE AND C.PRJCODE = A.proj_Code
                                                WHERE A.JournalH_Code IN (SELECT A.TTK_REF1_NUM FROM tbl_ttk_detail A
                                                    INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
                                                WHERE A.TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE INV_NUM = '$INV_NUM')) OR A.JournalH_Code = '$INV_NUM' AND A.GEJ_STAT = 3";

                                /*$s_SQL      = "SELECT A.JournalH_Code, A.proj_Code, A.Acc_Id, A.Acc_Name, A.ITM_CODE, C.ITM_NAME, A.Journal_DK,
                                                   A.Base_Debet, A.Base_Kredit, A.Manual_No, A.Other_Desc, A.ISRET, A.Ref_Number
                                                FROM tbl_journaldetail A
                                                INNER JOIN tbl_chartaccount B ON A.Acc_Id = B.Account_Number AND A.proj_Code = B.PRJCODE AND B.isPPhFinal = 0
                                                LEFT JOIN tbl_item C ON C.ITM_CODE = A.ITM_CODE AND C.PRJCODE = A.proj_Code
                                                WHERE A.JournalH_Code IN (SELECT A.TTK_REF1_NUM FROM tbl_ttk_detail A
                                                    INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM AND A.PRJCODE = B.PRJCODE
                                                WHERE A.TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail WHERE INV_NUM = '$INV_NUM') AND A.PRJCODE = '$PRJCODE')
                                                    AND A.GEJ_STAT = 3";*/
                                $r_SQL      = $this->db->query($s_SQL)->result();
                                /*$get_JHUT = "SELECT A.JournalH_Code, A.SPLCODE, B.Acc_Id, B.proj_Code, 
                                                B.Journal_DK, B.Base_Debet, B.Base_Kredit, B.PPN_Code, 
                                                B.PPN_Amount, B.PPH_Code, B.PPH_Amount, B.Ref_Number, 
                                                B.Other_Desc, C.ITM_CODE, C.ITM_NAME
                                                FROM tbl_journalheader A
                                                INNER JOIN tbl_journaldetail B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                                LEFT JOIN tbl_item C ON C.ITM_CODE = B.ITM_CODE AND C.PRJCODE = B.proj_Code
                                                WHERE A.JournalH_Code IN (SELECT TTK_REF1_NUM FROM tbl_ttk_detail
                                                            WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail
                                                            WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE') AND PRJCODE = '$PRJCODE')
                                                AND A.GEJ_STAT IN (2,3) ORDER BY A.JournalH_Code, B.Base_Debet DESC";
                                $getJournal_Hutang = $this->db->query($get_JHUT);
                                if($getJournal_Hutang->num_rows() > 0)
                                {
                                    $no = 0;
                                    $BaseKredit = 0;
                                    $TBalance_Debet = 0;
                                    $TBalance_Kredit = 0;
                                    foreach($getJournal_Hutang->result() as $rJ):*/
                                    $TBalance_Debet     = 0;
                                    $TBalance_Kredit    = 0;
                                    $RET_DESC           = "";
                                    $RET_ACC            = "";
                                    $RET_ITM            = "";
                                    $TOT_RET            = 0;
                                    $no                 = 0;
                                    foreach($r_SQL as $rJ):
                                        $JournalH_Code  = $rJ->JournalH_Code;
                                        $PRJCODE        = $rJ->proj_Code;
                                        $Acc_Id         = $rJ->Acc_Id;
                                        $Acc_Name       = $rJ->Acc_Name;
                                        $ITM_CODE       = $rJ->ITM_CODE;
                                        $ITM_NAME       = $rJ->ITM_NAME;
                                        $Journal_DK     = $rJ->Journal_DK;
                                        $Base_Debet     = $rJ->Base_Debet;
                                        $Base_Kredit    = $rJ->Base_Kredit;
                                        $Manual_No      = $rJ->Manual_No;
                                        $Other_Desc     = $rJ->Other_Desc;
                                        $ISRET          = $rJ->ISRET;
                                        $Ref_Number     = $rJ->Ref_Number;

                                        $TTK_CATEG= '';
                                        $sTTK     = "SELECT DISTINCT A.TTK_NUM, B.TTK_CATEG FROM tbl_ttk_detail A INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM WHERE A.TTK_REF1_NUM = '$JournalH_Code'";
                                        $rTTK     = $this->db->query($sTTK)->result();
                                        foreach($rTTK as $rowTTK) :
                                            $TTK_NUM    = $rowTTK->TTK_NUM;
                                            $TTK_CATEG  = $rowTTK->TTK_CATEG;
                                            if($TTK_CATEG == 'SJ')
                                            {
                                                $sLPM     = "SELECT SUM(TTK_REF1_AM) AS TOT_REF FROM tbl_ttk_detail WHERE TTK_REF1_NUM = '$JournalH_Code'";
                                                $rLPM     = $this->db->query($sLPM)->result();
                                                foreach($rLPM as $rowLPM) :
                                                    $TOT_REF  = $rowLPM->TOT_REF;
                                                    
                                                    if($Journal_DK == 'D')
                                                        $Base_Debet     = $TOT_REF;
                                                    else
                                                        $Base_Kredit    = $TOT_REF;
                                                endforeach;
                                            }
                                        endforeach;

                                        $Ref_NumberD    = "";
                                        if($Ref_Number != '') $Ref_NumberD = "<div style='font-weight: bold; font-style: italic;'>No. $Ref_Number</div>";

                                        // $TPPN_Amount = $TPPN_Amount + $PPN_Amount;
                                        // $TPPH_Amount = $TPPH_Amount + $PPH_Amount;

                                        if($Journal_DK == 'D')
                                            $JournalDesc = "<span>$ITM_NAME</span><div class='text-muted' style='font-style: italic;'>$Other_Desc</div>";

                                        if($Journal_DK == 'K')
                                        {
                                            $JournalDesc = "<span>$SPLDESC</span><div class='text-muted' style='font-style: italic;'>TOTAL : $Acc_Name</div>";  // Hut Supplier
                                        }
                                        
                                        $TBalance_Debet     = $TBalance_Debet + $Base_Debet;
                                        $TBalance_Kredit    = $TBalance_Kredit + $Base_Kredit;

                                        $no                 = $no + 1;
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no; ?></td>
                                                <td><?php echo $JournalDesc; ?></td>
                                                <td style="text-align: center;"><?php echo $PRJCODE; ?></td>
                                                <td style="text-align: center;"><?php echo $Acc_Id; ?></td>
                                                <td style="text-align: center;">
                                                    <?php if($Journal_DK == 'D') echo $ITM_CODE; ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php echo number_format($Base_Debet, 2); ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php  echo number_format($Base_Kredit, 2); ?>
                                                </td>
                                            </tr>
                                        <?php
                                    endforeach;
                                    /*if($TOT_RET > 0)
                                    {
                                        $no     = $no+1;
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no; ?></td>
                                                <td><?php echo $RET_DESC; ?></td>
                                                <td style="text-align: center;"><?php echo $PRJCODE; ?></td>
                                                <td style="text-align: center;"><?php echo $RET_ACC; ?></td>
                                                <td style="text-align: center;">
                                                    <?php if($Journal_DK == 'D') echo $RET_ITM; ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php echo "(".number_format($TOT_RET, 2).")"; ?>
                                                </td>
                                                <td style="text-align: right;">
                                                    <?php  echo "(".number_format($TOT_RET, 2).")"; ?>
                                                </td>
                                            </tr>
                                        <?php
                                        $TBalance_Debet = $TBalance_Debet-$TOT_RET;
                                    }*/
                                    ?>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Balance :</td>
                                        <td style="border-top: 1px dashed; text-align: right;">
                                            <?php 
                                                echo number_format($TBalance_Debet, 2);
                                            ?>
                                        </td>
                                        <td style="border-top: 1px dashed; text-align: right;">
                                            <?php 
                                                echo number_format($TBalance_Kredit, 2);
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                if($no <= 5)
                                {
                                    $amRow = 5 - $no;
                                    for($i=0;$i<$amRow;$i++)
                                    {
                                        ?>
                                            <tr class="blank-line">
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        <?php
                                    }
                                }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer" id="detail_JurnalHutang">
                <div class="box-asign-1">
                    <table width="300" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>ACC</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>FIAT</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                        <tfoot style="border-right: hidden; border-bottom: hidden; border-left: hidden;">
                            <tr>
                                <td colspan="3" style="text-align: left; font-size: 7pt;">
                                    <div style="float: left; height: 30px; width: 50px;"><b>CATATAN :</b></div>
                                    <div id="notes">
                                        Tanda Tangan Harus Lengkap Dengan nama Dan Tanggal Saat Menanda Tangani
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="box-asign-2">
                    <table width="300" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="2">Yang membuat</td>
                            <td colspan="2">Diperiksa</td>
                        </tr>
                        <tr style="height: 40px;">
                            <td width="150">&nbsp;</td>
                            <td width="50" style="text-align: center; vertical-align: top;">Tgl</td>
                            <td width="150">&nbsp;</td>
                            <td width="50" style="text-align: center; vertical-align: top;">Tgl</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<script type="text/javascript">
    document.addEventListener("keydown", function (event) {
        console.log(event);
        if (event.ctrlKey) {
            event.preventDefault();
            // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
        }   
    });
</script>