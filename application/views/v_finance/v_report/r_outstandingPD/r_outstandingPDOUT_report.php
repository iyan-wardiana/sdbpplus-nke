<?php
if($viewType == 1)
{
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=exceldata.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
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

$PRJNAME = $this->db->get_where("tbl_project", ["PRJCODE" => $PRJCODE])->row("PRJNAME");

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
        /* @page { margin: 0 } */
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
        .sheet.custom { padding: 1cm 0.5cm 0.97cm 0.5cm }

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
          @page { size: landscape;}
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
            border-color: black;
        }
        .box-detail table thead th {
            text-align: center;
            background-color: rgba(83,73,72,.3) !important;
            font-size: xx-small;
        }
        .box-detail table tbody td {
            font-size: xx-small;
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
            left: 10px;
        }
    </style>
</head>
<body class="page A4">
    <section class="page sheet custom">
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span><?php echo "LAPORAN OUTSTANDING PINJAMAN DINAS"; ?></span>
                    <span style="display: block; font-size: 10pt; font-style:italic;"><?php echo "Periode: $datePeriod"; ?></span>
                </div>
            </div>
            <div class="box-detail">
                <span style="font-weight: bold;">Proyek : <?php echo "$PRJNAME ($PRJCODE)"; ?></span>
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="20" rowspan="2">NO.</th>
                            <th width="70" rowspan="2">KODE</th>
                            <th width="50" rowspan="2">REF.</th>
                            <th width="100" rowspan="2">NAMA</th>
                            <th colspan="2">PINJAMAN DINAS</th>
                            <th rowspan="2">KETERANGAN</th>
                            <th width="50" rowspan="2">TGL. RENCANA SELESAI</th>
                            <th colspan="2">OUTSTANDING</th>
                            <th width="100" rowspan="2">CATATAN</th>
                        </tr>
                        <tr>
                            <th width="50">TANGGAL</th>
                            <th width="70">JUMLAH</th>
                            <th width="35">HARI</th>
                            <th width="70">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="13" style="line-height: 1px; border-left: hidden; border-right: hidden;">&nbsp;</td>
                        </tr>
                        <?php
                            if($SPLCODE[0] == 'All')
                            {
                                $addQSPL    = '';
                            }
                            else
                            {
                                $arrSPL     = implode("','", $SPLCODE);
                                $addQSPL    = "AND PERSL_EMPID IN ('$arrSPL')";
                            }

                            
                            $get_OUTPD      = "SELECT * FROM tbl_journalheader_pd
                                                WHERE proj_Code = '$PRJCODE' AND GEJ_STAT IN (3,6) $addQSPL AND JournalH_Date 
                                                AND (Journal_AmountReal + PPNH_Amount - PPHH_Amount) = 0
                                                BETWEEN '$Start_Date' AND '$End_Date'
                                                AND (Journal_AmountTsf - (Journal_AmountReal + PPNH_Amount - PPHH_Amount) + PDPaid_Amount - PDRec_Amount) > 0
                                                AND isManualClose = 0
                                                AND PlanRDate < DATE(NOW())";
                            $res_OUTPD      = $this->db->query($get_OUTPD);
                            $TOT_REALZ      = 0;
                            $GTOT_PD        = 0;
                            $GTOT_OUT       = 0;
                            $GTOT_CURRAM    = 0;
                            $GTOT_REALZ     = 0;
                            $GTOT_OUTCURR   = 0;
                            if($res_OUTPD->num_rows() > 0)
                            {
                                $newRow         = 0;
                                $OUT_AMOUNT     = 0;
                                $CURR_AMOUNT    = 0;
                                $selisihCV      = '';
                                $selisihOV      = '';
                                $OUT_NOTES      = '';
                                foreach($res_OUTPD->result() as $rOPD):
                                    $newRow             = $newRow + 1;
                                    $JournalH_Code      = $rOPD->JournalH_Code;
                                    $JournalType        = $rOPD->JournalType;
                                    $JournalH_Desc      = $rOPD->JournalH_Desc;
                                    $JournalH_Date      = $rOPD->JournalH_Date;
                                    $PD_Date            = $rOPD->JournalH_Date;
                                    $PD_DateV           = date('d/m/Y', strtotime($PD_Date));
                                    $JournalH_Date_PD   = $rOPD->JournalH_Date_PD;
                                    $JournalH_Date_PDV  = date('d/m/Y', strtotime($JournalH_Date_PD));
                                    $PlanRDate          = $rOPD->PlanRDate;
                                    $PlanRDateV         = date('d/m/Y', strtotime($PlanRDate));
                                    $Reference_Number   = $rOPD->Reference_Number;
                                    $ISPERSL            = $rOPD->ISPERSL;
                                    $PERSL_EMPID        = $rOPD->PERSL_EMPID;
                                    $PERSL_STAT         = $rOPD->PERSL_STAT;
                                    $acc_number         = $rOPD->acc_number;
                                    $PRJCODE            = $rOPD->proj_Code;
                                    $Journal_Amount     = $rOPD->Journal_Amount;
                                    $Journal_AmountReal = $rOPD->Journal_AmountReal;
                                    $Journal_AmountTsf  = $rOPD->Journal_AmountTsf;
                                    $PPNH_Amount        = $rOPD->PPNH_Amount;
                                    $PPHH_Amount        = $rOPD->PPHH_Amount;
                                    $PDPaid_Amount      = $rOPD->PDPaid_Amount;
                                    $PDRec_Amount       = $rOPD->PDRec_Amount;
                                    $PPD_RemAmount      = $rOPD->PPD_RemAmount;
                                    $GJournal_Total     = $rOPD->GJournal_Total;
                                    $Manual_No          = $rOPD->Manual_No;
                                    $REF_CODE           = $rOPD->REF_CODE;
                                    $SPLCODE            = $rOPD->SPLCODE;
                                    $SPLDESC            = $rOPD->SPLDESC;
                                    $GEJ_STAT           = $rOPD->GEJ_STAT;
                                    $GEJ_STAT_PD        = $rOPD->GEJ_STAT_PD;
                                    $GEJ_STAT_PPD       = $rOPD->GEJ_STAT_PPD;
                                    $STATDESC           = $rOPD->STATDESC;
                                    $isManualClose      = $rOPD->isManualClose;
                                    $Close_Notes        = $rOPD->Close_Notes;

                                    $TOT_PD             = $Journal_Amount;
                                    $TOT_REALZ          = $Journal_AmountReal + $PPNH_Amount - $PPHH_Amount;
                                    $GTOT_REALZ         = $GTOT_REALZ + $TOT_REALZ;
                                    $GTOT_PD            = $GTOT_PD + $Journal_Amount;

                                    if($SPLDESC == '')
                                    {
                                        $s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS SPLDESC FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
                                                            UNION
                                                            SELECT SPLDESC AS SPLDESC FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
                                        $r_emp          = $this->db->query($s_emp)->result();
                                        foreach($r_emp as $rw_emp) :
                                            $SPLDESC   = $rw_emp->SPLDESC;
                                        endforeach;
                                    }

                                    if($JournalH_Date_PD == null) $JournalH_Date_PDV = '';

                                    // Outstanding PD :
                                        $PlanRDateX     = $PlanRDate;
                                        $current_Date   = date('Y-m-d');
                                        $TOT_OUT        = $Journal_AmountTsf - $TOT_REALZ + $PDPaid_Amount - $PDRec_Amount;

                                        if($TOT_REALZ > 0)
                                        {
                                            $selisihCV      = '';
                                            $selisihOV      = '';
                                            $OUT_AMOUNT     = 0;
                                            $CURR_AMOUNT    = 0;

                                            $PlnDate            = new DateTime($PlanRDateX);
                                            $JournalH_Date_PD   = new DateTime($JournalH_Date_PD);
                                            $selisihR           = $PlnDate->diff($JournalH_Date_PD);
                                            // $selisihRV          = $selisihR->days." Hari";
                                            $selisihRV          = $selisihR->format('%R%a Hari');

                                            if($TOT_PD > $TOT_REALZ)
                                            {
                                                $overPayV   = number_format($TOT_OUT, 2);
                                                if($PDRec_Amount == 0) $OUT_NOTES  = "PD Lebih Bayar belum diterima senilai $overPayV";
                                                else $OUT_NOTES = '';
                                            }
                                            elseif($TOT_PD < $TOT_REALZ)
                                            {
                                                $underPayV  = number_format($TOT_OUT, 2);
                                                if($PDPaid_Amount == 0) $OUT_NOTES  = "PD Kurang Bayar belum ditransfer senilai $underPayV";
                                                else $OUT_NOTES = '';
                                            }
                                            else
                                            {
                                                $OUT_NOTES = '';
                                                if($isManualClose == 2) $OUT_NOTES = $Close_Notes;
                                            }
                                        }

                                        $OUT_NOTES  = '';
                                        if($TOT_REALZ == 0)
                                        {
                                            if($current_Date > $PlanRDateX)
                                            {
                                                // OUTSTANDING PD
                                                $PlnDate        = new DateTime($PlanRDateX);
                                                $currDate       = new DateTime($current_Date);
                                                $selisihO       = $PlnDate->diff($currDate);
                                                // $selisihOV      = $selisihO->days." Hari";
                                                $selisihOV      = $selisihO->format('%R%a Hari');
                                                $selisihCV      = '';
                                                $selisihRV      = '';
                                                $OUT_AMOUNT     = $Journal_Amount;
                                                $CURR_AMOUNT    = 0;
                                            }
                                            elseif($current_Date <= $PlanRDateX)
                                            {
                                                $PlnDate        = new DateTime($PlanRDateX);
                                                $currDate       = new DateTime($current_Date);
                                                $selisihC       = $currDate->diff($PlnDate);
                                                // $selisihCV      = $selisihC->days." Hari";
                                                $selisihCV      = $selisihC->format('%R%a Hari');
                                                $selisihOV      = '';
                                                $selisihRV      = '';
                                                $CURR_AMOUNT    = $Journal_Amount;
                                                $OUT_AMOUNT     = 0;
                                            }

                                            if($TOT_OUT == 0)
                                            {
                                                $selisihOV      = '';
                                                $selisihCV      = '';
                                                $selisihRV      = '';
                                            }

                                            if($isManualClose == 1)
                                            {
                                                $selisihCV      = '';
                                                $selisihOV      = '';
                                                $OUT_AMOUNT     = 0;
                                                $CURR_AMOUNT    = 0;
                                                $TOT_REALZ      = $Journal_Amount;

                                                $overPayV   = number_format($TOT_OUT, 2);
                                                if($PDRec_Amount == 0) $OUT_NOTES  = "$Close_Notes belum diterima senilai $overPayV";
                                                else $OUT_NOTES = '';                                             
                                            }
                                            else
                                            {
                                                $OUT_NOTES = "$Close_Notes";
                                            }
                                        }

                                        $GTOT_OUT       = $GTOT_OUT + $OUT_AMOUNT;
                                        $GTOT_CURRAM    = $GTOT_CURRAM + $CURR_AMOUNT;

                                        //$TOT_OUTCURR  = $OUT_AMOUNT + $CURR_AMOUNT - $TOT_REALZ;
                                        $TOT_OUTCURR    = $OUT_AMOUNT + $CURR_AMOUNT;
                                        $GTOT_OUTCURR   = $GTOT_OUTCURR + $TOT_OUTCURR; 
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?=$newRow?></td>
                                            <td style="text-align: center;"><?=$Manual_No?></td>
                                            <td style="text-align: center;"><?=$Reference_Number?></td>
                                            <td><?php echo "$PERSL_EMPID - $SPLDESC"; ?></td>
                                            <td style="text-align: center;"><?=$PD_DateV?></td>
                                            <td style="text-align: right;"><?php echo number_format($Journal_Amount, 2); ?></td>
                                            <td><?php echo "$JournalH_Desc"; ?></td>
                                            <td style="text-align: center;"><?=$PlanRDateV?></td>
                                            <td style="text-align: center;"><?php echo $selisihOV; ?></td>
                                            <td style="text-align: right;"><?php echo number_format($OUT_AMOUNT, 2); ?></td>
                                            <td><?php echo "$OUT_NOTES"; ?></td>
                                        </tr>
                                    <?php
                                endforeach;
                                ?>
                                    <tr>
                                        <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL</td>
                                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_PD, 2); ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_OUT, 2); ?></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <?php
                            }
                            else
                            {
                                ?>
                                    <tr>
                                        <td colspan="13" style="font-family: italic; text-align: center;">No data available</td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
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