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

$EMPNAME = $this->db->select("CONCAT(First_Name,' ',Last_Name) AS FullName", false)->from("tbl_employee")->where(["Emp_ID" => $PERSL_EMPID, "Employee_status" => 1])->get()->row("FullName");
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
        body.A3               .sheet { width: 297mm; height: 419mm }
        body.A3.landscape     .sheet { width: 420mm; height: 296mm }
        body.A4               .sheet { width: 210mm; height: 296mm }
        body.A4.landscape     .sheet { width: 297mm; height: 209mm }
        body.A5               .sheet { width: 148mm; height: 209mm }
        body.A5.landscape     .sheet { width: 210mm; height: 147mm }
        body.letter           .sheet { width: 216mm; height: 279mm }
        body.letter.landscape .sheet { width: 280mm; height: 215mm }
        body.legal            .sheet { width: 216mm; height: 356mm }
        body.legal.landscape  .sheet { width: 357mm; height: 215mm }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet.custom { padding: 1cm 0.38cm 0.97cm 0.5cm }

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
            border: 2px solid;
        }
        .box-header {
            padding: 5px;
            border-bottom: 2px solid;
        }
        .box-header .box-column-logo {
            float: left;
            width: 25%;
            /*border: 1px solid;*/
        }
        .box-header .box-column-title {
            float: left;
            width: 55%;
            /*border: 1px solid;*/
            text-align: center;
        }
        .box-header .box-column-frm {
            float: left;
            width: 20%;
            /*border: 1px solid;*/
            font-family: "Arial";
            font-size: 9pt;
            padding-top: 5px;
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 5cm;
        }
        .box-detail .box-content {
            float: left;
            width: 600px;
            font-family: "Arial";
            font-size: 11pt;
            padding-right: 5px;
            padding-bottom: 5px;
            border-bottom: 2px solid;
        }
        .box-detail .box-content #maxAmn {
            text-align: right;
            font-weight: bold;
            padding-right: 5px;
        }
        .box-detail .box-asign {
            float: left;
            width: 156px;
        }
        .box-detail .box-asign #acc {
            font-family: "Arial";
            height: 100px;
            border-left: 2px solid;
            text-align: center;
            border-bottom: 2px solid;
        }
        .box-detail .box-content .content #label-nama {
            display: inline-block;
            padding-left: 5px;
            width: 100px;
        }
        .box-detail .box-content .content #content-nama {
            display: inline-block;
        }
        .box-detail .box-content .content .box-amount {
            display: inline-block;
            width: 150px;
            height: 30px;
            border: 1px solid;
            box-shadow: 1px 1px 3px;
            padding-left: 5px;
            padding-top: 8px;
            font-weight: bold;
        }
        .box-detail .box-content .content #submit_date {
            float: left;
            width: 50%;
            padding-top: 15px;
        }
        .box-detail .box-content .content #end_date {
            float: left;
            width: 50%;
            margin-bottom: 15px;
            padding-top: 15px;
        }
        .box-detail .box-content .content #notes {
            border: 1px solid;
            text-align: center;
            padding: 5px;
        }
        .box-detail .box-notes {
            position: absolute;
            top: 350px;
            left: 10px;
            /*border: 1px solid;*/
        }
        .box-detail .box-notes #notes {
            display: inline-block;
            vertical-align: top;
            /*border: 1px solid;*/
        }
        .box-detail .box-notes #notes-content {
            display: inline-block;
            width: 520px;
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
    </style>
    <?php
        function konversi($x)
        {
            $x = abs($x);
            $angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
      
            if($x < 12){
                $temp = " ".$angka[$x];
            }else if($x<20){
                $temp = konversi($x - 10)." belas";
            }else if ($x<100){
                $temp = konversi($x/10)." puluh". konversi($x%10);
            }else if($x<200){
                $temp = " seratus".konversi($x-100);
            }else if($x<1000){
                $temp = konversi($x/100)." ratus".konversi($x%100);   
            }else if($x<2000){
                $temp = " seribu".konversi($x-1000);
            }else if($x<1000000){
                $temp = konversi($x/1000)." ribu".konversi($x%1000);   
            }else if($x<1000000000){
                $temp = konversi($x/1000000)." juta".konversi($x%1000000);
            }else if($x<1000000000000){
                $temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
            }
      
            return $temp;
        }
      
        function tkoma($x)
        {
            $str_dec = ',';
            if(strpos($x, $str_dec) == false)
                $str_dec = '.';

            $str = stristr($x, $str_dec);
            $ex = explode($str_dec, $x);

            if(($ex[1]/10) >= 1){
                $a = abs($ex[1]);
            }

            $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
            $temp = "";
     
            $a2 = $ex[1]/10;
            $pjg = strlen($str);
            $i =1;
        
            if($a>=1 && $a< 12){   
                $temp .= " ".$string[$a];
            }else if($a>12 && $a < 20){   
                $temp .= konversi($a - 10)." belas";
            }else if ($a>20 && $a<100){   
                $temp .= konversi($a / 10)." puluh". konversi($a % 10);
            }else{
                if($a2<1){
                    while ($i<$pjg){     
                        $char = substr($str,$i,1);     
                        $i++;
                        $temp .= " ".$string[$char];
                    }
                }
            }  

            return $temp;
        }
     
        function terbilang($x)
        {
            if($x<0){
                $hasil = "minus ".trim(konversi(x));
            }else{
                $str_dec = ',';
                if(strpos($x, $str_dec) == false)
                    $str_dec = '.';

                $ex = explode($str_dec, $x);
                if(count($ex) != 1)
                {
                    $poin   = trim(tkoma($x));
                    $hasil1 = trim(konversi($x));
                    $hasil  = $hasil1." koma ".$poin;
                }
                else
                {
                    $hasil1 = trim(konversi($x));
                    $hasil  = $hasil1;
                }

            }

            return $hasil;  
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
                    <div style="font-family: 'Arial Black'; font-size: 18pt;"><b><u>KAS BON</u></b></div>
                    <div style="font-family: 'Arial'; font-size: 12pt;">(BON UANG TUNAI)</div>
                </div>
                <div class="box-column-frm">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>Dok. No.</td>
                            <td>:</td>
                            <td>&nbsp;FRM.NKE.13.02</td>
                        </tr>
                        <tr>
                            <td>Revisi</td>
                            <td>:</td>
                            <td>&nbsp;(06/02/20)</td>
                        </tr>
                        <tr>
                            <td>Amand.</td>
                            <td>:</td>
                            <td>&nbsp;-</td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="box-detail">
                <div class="box-content">
                    <div id="maxAmn">Max = Rp. 2.500.000,-</div>
                    <div class="content" style="padding-left: 5px">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr style="vertical-align: bottom;">
                                <td width="70">Nama</td>
                                <td width="5">:</td>
                                <td width="140"><?php echo $EMPNAME;?></td>
                                <td width="60">Proyek/Bag.</td>
                                <td width="5">:</td>
                                <td width="60"><?=$PRJCODE?></td>
                                <td width="50">Jumlah</td>
                                <td width="30">: Rp</td>
                                <td width="150">
                                    <span class="box-amount"><?php echo number_format($Journal_Amount, 2); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td width="70" style="vertical-align: top; padding-top: 15px;">Terbilang</td>
                                <td width="5" style="vertical-align: top; padding-top: 15px;">:</td>
                                <td colspan="6" width="100" height="50" style="vertical-align: top; padding-top: 15px; font-style: italic;">
                                    <?php echo $moneyFormat->terbilang($Journal_Amount); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="70" style="vertical-align: top; padding-top: 15px;">Untuk</td>
                                <td width="5" style="vertical-align: top; padding-top: 15px;">:</td>
                                <td colspan="6" width="100" height="50" style="vertical-align: top; padding-top: 15px;">
                                    <?php echo $JournalH_Desc; ?>
                                </td>
                            </tr>
                        </table>
                        <div id="submit_date">Diajukan tanggal : <?php echo date('d M Y', strtotime($JournalH_Date)); ?></div>
                        <div id="end_date">Akan diselesaikan tanggal : -</div>
                        <div class="clearfix"></div>
                        <div id="notes">WAKTU PENYELESAIAN PALING LAMBAT 4 (EMPAT) HARI SEJAK UANG KAS BON INI DITERIMA</div>
                    </div>
                </div>
                <div class="box-notes">
                    <div id="notes">Catatan :</div>
                    <div id="notes-content">
                        <ul class="dashed">
                            <li>Kas Bon ini berlaku untuk proyek dan kantor,</li>
                            <li>Lembar ke-2 untuk personil yang kas bon, dilampirkan kembali saat penyelesaian,</li>
                            <li>Lembar ke-1 akan dicabut dari Kasir (dicoret) jika Kas Bon telah diselesaikan,</li>
                            <li>Hanya diberikan kepada karyawan PT Nusa Konstruksi Enjiniring Tbk dengan ketentuan maksimal 1 lembar untuk tiap karyawan.</li>
                        </ul>
                    </div>
                </div>
                <div class="box-asign">
                    <div id="acc">
                        <div style="font-size: 11pt">Menyetujui :</div>
                        <div style="font-size: 10pt">Jab.:_____________</div>
                        <div style="font-size: 6pt; font-weight: bold; font-style: italic; padding-top: 45px; padding-left: 5px; text-align: left;">NAMA : </div>
                    </div>
                    <div id="acc">
                        <div style="font-size: 10pt">Jab.:_____________</div>
                        <div style="font-size: 6pt; font-weight: bold; font-style: italic; padding-top: 65px; padding-left: 5px; text-align: left;">NAMA : </div>
                    </div>
                    <div id="acc">
                        <div style="font-size: 10pt">Kasir</div>
                        <div style="font-size: 6pt; font-weight: bold; font-style: italic; padding-top: 65px; padding-left: 5px; text-align: left;">NAMA : </div>
                    </div>
                    <div id="acc" style="border-bottom: hidden;">
                        <div style="font-size: 10pt">Penerima</div>
                        <div style="font-size: 6pt; font-weight: bold; font-style: italic; padding-top: 65px; padding-left: 5px; text-align: left;">NAMA : </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
</body>
</html>