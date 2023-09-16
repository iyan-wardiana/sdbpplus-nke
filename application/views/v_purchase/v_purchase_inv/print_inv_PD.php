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
        @page { margin: 0 }
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
        $PRJCODE        = $default['PRJCODE'];
        $INV_DATE       = $default['INV_DATE'];
        $INV_DUEDATE    = $default['INV_DUEDATE'];
        $SPLCODE        = $default['SPLCODE'];
        $INV_CURRENCY   = $default['INV_CURRENCY'];
        $INV_TAXCURR    = $default['INV_TAXCURR'];
        $DP_NUM         = $default['DP_NUM'];
        $DP_AMOUNT      = $default['DP_AMOUNT'];
        $INV_AMOUNT     = $default['INV_AMOUNT'];
        $INV_AMOUNT_PPN = $default['INV_AMOUNT_PPN'];
        $INV_AMOUNT_PPH = $default['INV_AMOUNT_PPH'];
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

        $INV_TOTPOT     = $INV_AMOUNT_PPH + $INV_AMOUNT_DPB + $INV_AMOUNT_RET + $INV_AMOUNT_POTUM + $INV_AMOUNT_POTOTH;

        $INV_TOTCOST    = $INV_AMOUNT + $INV_AMOUNT_PPN + $INV_AMOUNT_OTH - $INV_TOTPOT;

        echo "$INV_TOTCOST    = $INV_AMOUNT + $INV_AMOUNT_PPN + $INV_AMOUNT_OTH - $INV_TOTPOT<br>";

        $sqlPRJ     = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
        $resPRJ     = $this->db->query($sqlPRJ)->result();
        foreach($resPRJ as $rowPRJ) :
            $PRJNAME= $rowPRJ->PRJNAME;
        endforeach;

        $SPLDESC    = '';
        $SPLADD1    = '';
        $this->db->distinct("SPLCODE, SPLDESC, SPLADD1")->where(["SPLCODE" => $SPLCODE, "SPLSTAT" => 1]);
        $getSPL     = $this->db->get("tbl_supplier");
        if($getSPL->num_rows() > 0)
        {
            foreach($getSPL->result() as $r):
                $SPLCODE    = $r->SPLCODE;
                $SPLDESC    = $r->SPLDESC;
                $SPLADD1    = $r->SPLADD1;
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
            $TTK_REF2_CODE = '';
            $this->db->select("TTK_REF2_NUM, TTK_REF2_CODE")->where_in("TTK_NUM", $arrTTK_NUM)->where("PRJCODE", $PRJCODE);
            $getTTK = $this->db->get("tbl_ttk_detail");
            if($getTTK->num_rows() > 0)
            {
                $rowTTK = 0;
                foreach($getTTK->result() as $rTTK):
                    $rowTTK         = $rowTTK + 1;
                    $TTK_REF2_NUM1  = $rTTK->TTK_REF2_NUM;
                    $TTK_REF2_CODE1 = $rTTK->TTK_REF2_CODE;
                    if($rowTTK == 1)
                    {
                        $TTK_REF2_NUM   = $TTK_REF2_NUM1;
                        $TTK_REF2_CODE  = $TTK_REF2_CODE1;
                    }
                    else
                    {
                        $TTK_REF2_NUM   = "$TTK_REF2_NUM1, $TTK_REF2_NUM";
                        $TTK_REF2_CODE  = "$TTK_REF2_CODE1, $TTK_REF2_CODE";
                    }
                endforeach;
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

        $Journal_Amount_PD  = 0;
        $TOTINV_PD          = $INV_TOTCOST; // Nilai Penyelesaian PD Saat INI
        // GET Hist. INV PD LALU
            $getHistPD = "SELECT JournalH_Id, JournalH_Code, Manual_No, Journal_Amount_PD 
                            FROM tbl_journalheader_pd_rinv
                            WHERE Proj_Code = '$PRJCODE' 
                            AND Invoice_No = '$INV_NUM'";
            $resHistPD  = $this->db->query($getHistPD);
            if($resHistPD->num_rows() > 0)
            {
                foreach($resHistPD->result() as $rHistPD):
                    $JournalH_Id        = $rHistPD->JournalH_Id;
                    $JournalH_Code      = $rHistPD->JournalH_Code;
                    $Journal_Amount_PD  = $rHistPD->Journal_Amount_PD;

                    // GET Manual_No PD & Ref
                        $Manual_No          = "";
                        $Reference_Number   = "";
                        $getPDNo    = "SELECT Manual_No, Reference_Number FROM tbl_journalheader_pd
                                        WHERE JournalH_Code = '$JournalH_Code' AND proj_Code = '$PRJCODE'";
                        $resPDNo    = $this->db->query($getPDNo);
                        if($resPDNo->num_rows() > 0)
                        {
                            foreach($resPDNo->result() as $rPDNo):
                                $Manual_No          = $rPDNo->Manual_No;
                                $Reference_Number   = $rPDNo->Reference_Number;
                            endforeach;
                        }
                    
                    // GET LastHist PD
                    $getLastPD  = "SELECT SUM(Invoice_Amount) AS TOTINV_LastPD
                                        FROM tbl_journalheader_pd_rinv
                                        WHERE JournalH_Id < '$JournalH_Id' AND JournalH_Code = '$JournalH_Code'";
                        $resLastPD  = $this->db->query($getLastPD);
                        if($resLastPD->num_rows() > 0)
                        {
                            $TOTINV_LastPD      = 0;
                            foreach($resLastPD->result() as $rLastPD):
                                $TOTINV_LastPD  = $rLastPD->TOTINV_LastPD;
                            endforeach;
                        }
                endforeach;
            }

        $TREALZAmount       = $TOTINV_PD + $TOTINV_LastPD;
        echo "$TREALZAmount       = $TOTINV_PD + $TOTINV_LastPD";
        $REMREALZ_Amount    = $TREALZAmount - $Journal_Amount_PD;
        if($TREALZAmount < $Journal_Amount_PD)
        {
            $REM_DESC = "Lebih Bayar";
            $Title_PD = "PIUTANG PEMBAYARAN DIMUKA";
            $REMREALZ_AmountD = $Journal_Amount_PD - $TREALZAmount;
        }
        elseif($TREALZAmount > $Journal_Amount_PD)
        {
            $REM_DESC = "Kurang Bayar";
            $Title_PD = "HUTANG PEMBAYARAN DIMUKA";
            $REMREALZ_AmountD = $REMREALZ_Amount;
        }
        else
        {
            $REM_DESC = "Sisa Bayar";
            $Title_PD = "PENYELESAIAN PEMBAYARAN DIMUKA";
            $REMREALZ_AmountD = $REMREALZ_Amount;
        }

    ?>
</head>
<body class="page A4">
    <section class="page sheet custom">
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span>VOUCHER<br></span>
                    <span style="color: red !important;"><?php echo $Title_PD; ?></span>
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
                        <td width="400">&nbsp;<?php echo $SPLDESC; ?></td>
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
                            <th colspan="2" width="100">AMOUNT (RP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Pembayaran Tagihan No.&nbsp;<?php echo $INV_CODE; ?></td>
                            <td colspan="2" width="100" style="text-align: right;"><?php echo number_format($INV_AMOUNT, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Nomor Seri Pajak : &nbsp;<?php echo $TTKT_TAXNO; ?></td>
                            <td colspan="2" width="100">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td><?php echo $PRJNAME; ?></td>
                            <td colspan="2" width="100">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Uang Muka</td>
                            <td colspan="2" width="100" style="text-align: right;">(<?php echo number_format($INV_AMOUNT_POTUM, 2); ?>)</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Potongan Lain-lain</td>
                            <td colspan="2" width="100" style="text-align: right;">(<?php echo number_format($INV_AMOUNT_POTOTH, 2); ?>)</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>PPN</td>
                            <td colspan="2" width="100" style="text-align: right;"><?php echo number_format($INV_AMOUNT_PPN, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Pph</td>
                            <td colspan="2" width="100" style="text-align: right;">(<?php echo number_format($INV_AMOUNT_PPH, 2); ?>)</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Nilai Pemb. Dimuka, <?php echo "$Manual_No, Referensi No. $Reference_Number"; ?></td>
                            <td colspan="2" width="100" style="text-align: right;"><?php echo number_format($Journal_Amount_PD, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Penyelesaian Sebelumnya</td>
                            <td colspan="2" width="100" style="text-align: right;"><?php echo number_format($TOTINV_LastPD, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>Penyelesaian Saat Ini</td>
                            <td colspan="2" width="100" style="text-align: right;"><?php echo number_format($TOTINV_PD, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="2" width="100">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;Jatuh Tempo Tgl <?php echo date('d-m-Y', strtotime($INV_DUEDATE)); ?></td>
                            <td colspan="2" width="100">&nbsp;</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="border-right: hidden; text-align: right; vertical-align: top;">Terbilang :</td>
                            <td>
                                <?php
                                    if($REMREALZ_AmountD == 0)
                                        echo "Nol";
                                    else 
                                        echo $moneyFormat->terbilang($REMREALZ_AmountD, 2); 
                                ?>
                            </td>
                            <td style="font-weight: bold; font-style: italic; font-size: 8pt; border-right: hidden;">
                                <?php echo $REM_DESC; ?> :
                            </td>
                            <td style="text-align: right;">
                                <?php echo number_format($REMREALZ_Amount,2); ?>
                            </td>
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
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
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
                                // $get_TTK    = "SELECT TTK_NUM FROM tbl_pinv_detail
                                //                 WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE'";
                                // $res_TTK    = $this->db->query($get_TTK);
                                // $dtTTK_NUM  = '';
                                // if($res_TTK->num_rows() > 0)
                                // {
                                //     $arrTTK     = [];
                                //     foreach($res_TTK->result() as $rTTK):
                                //         $arrTTK[] = $rTTK->TTK_NUM;
                                //     endforeach;
                                //     $dtTTK_NUM    = implode("','", $arrTTK);
                                // }

                                // $getHUT_OPNIR   = "SELECT TTK_REF1_NUM FROM tbl_ttk_detail
                                //                     WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail
                                //                 WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE') AND PRJCODE = '$PRJCODE'";
                                // $resHUT_OPNIR   = $this->db->query($getHUT_OPNIR);
                                // $dtTTK_REF  = '';
                                // if($resHUT_OPNIR->num_rows() > 0)
                                // {
                                //     $arrTTK_REF     = [];
                                //     foreach($resHUT_OPNIR->result() as $rTTK_REF):
                                //         $arrTTK_REF[] = $rTTK_REF->TTK_REF1_NUM;
                                //     endforeach;
                                //     $dtTTK_REF    = implode("','", $arrTTK_REF);
                                // }
                            
                            $get_JHUT = "SELECT A.JournalH_Code, A.SPLCODE, B.Acc_Id, B.proj_Code, 
                                            B.Journal_DK, B.Base_Debet, B.Base_Kredit, B.PPN_Code, 
                                            B.PPN_Amount, B.PPH_Code, B.PPH_Amount, B.Ref_Number, 
                                            B.Other_Desc, C.ITM_CODE, C.ITM_NAME
                                            FROM tbl_journalheader A
                                            INNER JOIN tbl_journaldetail B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
                                            LEFT JOIN tbl_item C ON C.ITM_CODE = B.ITM_CODE AND C.PRJCODE = B.proj_Code
                                            WHERE A.JournalH_Code IN (SELECT TTK_REF1_NUM FROM tbl_ttk_detail
                                                        WHERE TTK_NUM IN (SELECT TTK_NUM FROM tbl_pinv_detail
                                                        WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE') AND PRJCODE = '$PRJCODE')
                                            -- AND B.isTax = 0
                                            AND A.GEJ_STAT IN (2,3) ORDER BY A.JournalH_Code, B.Base_Debet DESC";

                            // $this->db->select("A.JournalH_Code, A.SPLCODE, B.Acc_Id, B.proj_Code, B.Journal_DK, B.Base_Debet, B.Base_Kredit, B.PPN_Code, B.PPN_Amount, B.PPH_Code, B.PPH_Amount, B.Ref_Number, B.Other_Desc, C.ITM_CODE, C.ITM_NAME");
                            // // $this->db->select_sum("B.Base_Debet");
                            // // $this->db->select_sum("B.Base_Kredit");
                            // $this->db->from("tbl_journalheader A");
                            // $this->db->join("tbl_journaldetail B", "B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code");
                            // $this->db->join("tbl_item C", "C.ITM_CODE = B.ITM_CODE AND C.PRJCODE = B.proj_Code", "LEFT");
                            // $this->db->where_in("A.JournalH_Code", $dtTTK_REF);
                            // $this->db->where_in("A.GEJ_STAT", [2,3]);
                            // // $this->db->group_by("B.Acc_Id");
                            // $this->db->order_by("B.Base_Debet, B.Base_Kredit", "DESC");
                            // $getJournal_Hutang = $this->db->get();

                            $getJournal_Hutang = $this->db->query($get_JHUT);
                            if($getJournal_Hutang->num_rows() > 0)
                            {
                                $no = 0;
                                $BaseKredit = 0;
                                $TBalance_Debet = 0;
                                $TBalance_Kredit = 0;
                                $TBalance_PPNDebet = 0;
                                $TBalance_PPNKredit = 0;
                                foreach($getJournal_Hutang->result() as $rJ):
                                    $no             = $no + 1;
                                    $JournalH_Code  = $rJ->JournalH_Code;
                                    $Acc_Id         = $rJ->Acc_Id;
                                    $PPN_Code       = $rJ->PPN_Code;
                                    $PPH_Code       = $rJ->PPH_Code;
                                    $PRJCODE        = $rJ->proj_Code;
                                    $ITM_CODE       = $rJ->ITM_CODE;
                                    $ITM_NAME       = $rJ->ITM_NAME;
                                    $Journal_DK     = $rJ->Journal_DK;
                                    $Base_Debet     = $rJ->Base_Debet;
                                    $Base_Kredit    = $rJ->Base_Kredit;
                                    $PPN_Amount     = $rJ->PPN_Amount;
                                    $PPH_Amount     = $rJ->PPH_Amount;

                                    $Ref_Number     = $rJ->Ref_Number;
                                    $Other_Desc     = $rJ->Other_Desc;

                                    // $TPPN_Amount = $TPPN_Amount + $PPN_Amount;
                                    // $TPPH_Amount = $TPPH_Amount + $PPH_Amount;

                                    $BaseKredit         = $Base_Kredit;
                                    $TBalance_Debet     = $TBalance_Debet + $Base_Debet;
                                    $TBalance_Kredit    = $TBalance_Debet;

                                    if($Journal_DK == 'D')
                                        $JournalDesc = "<span>$ITM_NAME</span><div class='text-muted' style='font-style: italic;'>$Other_Desc</div>";

                                    if($Journal_DK == 'K')
                                    {
                                        $JournalDesc = "$SPLDESC";  // Hut Supplier
                                    }

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

                                // get PPN from PINV
                                $getPPN = "SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Name, proj_Code, 
                                            JournalD_Debet, JournalD_Kredit, Manual_No, Ref_Number, Faktur_No, Faktur_Date, 
                                            Kwitansi_No, Kwitansi_Date,	SPLCODE, SPLDESC, Other_Desc
                                            FROM tbl_journaldetail
                                            WHERE JournalH_Code = '$INV_NUM' AND JournalType = 'PINV' AND proj_Code = '$PRJCODE'";
                                $resPPN = $this->db->query($getPPN);
                                if($resPPN->num_rows() > 0)
                                {
                                    $no = $no;
                                    foreach($resPPN->result() as $rPPN):
                                        $no             = $no + 1;
                                        $JournalDesc    = $rPPN->Other_Desc;
                                        $PRJCODE        = $rPPN->proj_Code;
                                        $Acc_Id         = $rPPN->Acc_Id;
                                        $Base_Debet     = $rPPN->JournalD_Debet;
                                        $Base_Kredit    = $rPPN->JournalD_Kredit;

                                        $TBalance_PPNDebet  = $TBalance_PPNDebet + $Base_Debet;
                                        $TBalance_PPNKredit = $TBalance_PPNKredit + $Base_Kredit;
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $no; ?></td>
                                                <td><?php echo $JournalDesc; ?></td>
                                                <td style="text-align: center;"><?php echo $PRJCODE; ?></td>
                                                <td style="text-align: center;"><?php echo $Acc_Id; ?></td>
                                                <td style="text-align: center;">
                                                    &nbsp;
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
                                }
                                
                                /* --------------- PPN diambil dari tbl_ttk_tax -----------------
                                    if($PPN_Amount != 0)
                                    {
                                        $no = $no + 1;
                                        // get PPN 
                                        $JournalDesc    = '';
                                        $Acc_Id         = '';
                                        $this->db->select("TAXLA_DESC, TAXLA_LINKIN");
                                        $getAccPPN = $this->db->get_where("tbl_tax_ppn", ["TAXLA_NUM" => $PPN_Code]);
                                        if($getAccPPN->num_rows() > 0)
                                        {
                                            foreach ($getAccPPN->result() as $rPPN):
                                                $TAXLA_DESC     = $rPPN->TAXLA_DESC;
                                                $TAXLA_LINKIN   = $rPPN->TAXLA_LINKIN;
                                            endforeach;
                                        }

                                        $JournalDesc    = $Ref_Number;
                                        $Acc_Id         = $TAXLA_LINKIN;
                                        $ITM_CODE       = '';
                                        $Base_Debet     = $PPN_Amount;
                                        $Base_Kredit    = 0;
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
                                                    <?php 
                                                        echo number_format($Base_Kredit, 2);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                    }

                                    if($PPH_Amount != 0)
                                    {
                                        $no = $no + 1;
                                        // get PPN 
                                        $JournalDesc    = '';
                                        $Acc_Id         = '';
                                        $this->db->select("TAXLA_DESC, TAXLA_LINKIN");
                                        $getAccPPH = $this->db->get_where("tbl_tax_la", ["TAXLA_NUM" => $PPH_Code]);
                                        if($getAccPPH->num_rows() > 0)
                                        {
                                            foreach ($getAccPPH->result() as $rPPH):
                                                $TAXLA_DESC     = $rPPH->TAXLA_DESC;
                                                $TAXLA_LINKIN   = $rPPH->TAXLA_LINKIN;
                                            endforeach;
                                        }

                                        $JournalDesc    = $TAXLA_DESC;
                                        $Acc_Id         = $TAXLA_LINKIN;
                                        $ITM_CODE       = '';
                                        $Base_Debet     = 0;
                                        $Base_Kredit    = $PPH_Amount;
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
                                                    <?php 
                                                        echo number_format($Base_Kredit, 2);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ----------------------- End Hidden ------------------------- */
                                $TBalance_Debet     = $TBalance_Debet + $TBalance_PPNDebet;
                                $TBalance_Kredit    = $TBalance_Kredit + $TBalance_PPNKredit;
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
                                if($no <= 35)
                                {
                                    $amRow = 35 - $no;
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