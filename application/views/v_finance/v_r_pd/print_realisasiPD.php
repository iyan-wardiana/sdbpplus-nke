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
            border: 2px solid;
        }
        .box-header {
            border-bottom: 2px solid;
        }
        .box-header .box-column-logo {
            float: left;
            width: 15%;
            height: 50px;
            border-right: 2px solid;
            /*border: 1px solid;*/
        }
        .box-header .box-column-title {
            float: left;
            width: 50%;
            height: 50px;
            border-right: 2px solid;
            /*border: 1px solid;*/
            text-align: center;
            padding-top: 7px;
        }
        .box-header .box-column-no {
            float: left;
            width: 20%;
            height: 50px;
            padding-left: 5px;
            border-right: 2px solid;
            /*border: 1px solid;*/
            font-family: "Arial Black";
            font-size: 10pt;
            padding-top: 15px;
        }
        .box-header .box-column-frm {
            float: left;
            width: 15%;
            height: 50px;
            /*border: 1px solid;*/
            font-family: "Arial";
            font-size: 6pt;
            /*margin-top: 5px;*/
            padding-left: 5px;
            padding-top: 2px;
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 4cm;
        }
        .box-header-notes {
            padding-top: 10px;
            padding-left: 10px;
            font-family: "Arial";
            font-size: 7pt;
            font-weight: bold;
            border-bottom: 2px solid;
        }
        .box-header-notes #notes {
            float: left;
            width: 70px;
        }
        .box-header-notes #notes-content {
            float: left;
            font-family: "Arial";
            font-size: 7pt;
            font-weight: bold;
        }
        .box-header-content {
            /*padding: 5px;*/
            border-bottom: 2px solid;
        }
        .box-header-content .box-column-10 {
            float: left;
            width: 88%;
            height: 60px;
            border-right: 2px solid;
            font-family: "Arial";
            font-size: 6pt;
            padding-top: 15px;
            padding-left: 5px;
        }
        .box-header-content .box-column-2 {
            float: left;
            width: 12%;
            margin: 5px auto;
            padding-left: 5px;
            font-family: "Arial";
            font-size: 6pt;
        }
        .box-content {
            position: relative;
            border-bottom: 2px solid;
        }
        .box-content .box-column-6 {
            float: left;
            width: 50%;
            height: 200px;
            border-right: 2px solid;
            padding-left: 5px;
        }
        .box-content .box-column-6 #notes {
            position: absolute;
            top: 175px;
        }
        .box-content .box-column-6 #notes-content {
            position: absolute;
            top: 185px;
        }
        .box-asign {
            position: relative;
            font-family: "Arial";
        }
        .box-asign .box-column-2 {
            float: left;
            width: 16.66%;
            height: 150px;
            border-right: 2px solid;
            text-align: center;
        }
        .box-asign .box-column-2 #acc-top {
            height: 30px;
            border-bottom: 1px solid;
            padding-top: 2px;
        }
        .box-asign .box-column-2 #acc-bottom {
            position: relative;
            padding-left: 5px;
            font-family: "Arial";
            font-size: 5pt;
            font-style: italic;
        }
        .box-asign .box-column-2 #acc-bottom #nama {
            display: block;
            position: absolute;
            top: 85px;
        }
        .box-asign .box-column-2 #acc-bottom #tanggal {
            display: block;
            position: absolute;
            top: 105px;
        }
        .detail-page-notes {
            font-family: "Arial";
            font-size: 5pt;
            font-style: italic;
        }
        .detail-page-notes .box-column-6#company {
            float: left;
        }
        .detail-page-notes .box-column-6#docfile {
            float: right;
            padding-right: 5px;
        }
        .cont-stampe {
            position: relative;
        }
        .stample {
            position: absolute;
            top: 600px;
            left: 700px;
            width: 400px;
            font-family: "Arial";
            font-size: 6pt;
        }
        .stample .stample-title {
            font-size: 8pt;
            margin-bottom: 5px;
        }
        .stample .stample-content {
            border: 2px solid;
        }
        .stample .stample-content table td {
            padding: 5px 5px 5px 15px;
            border-bottom: 1px solid;
        }
        .stample .stample-asign {
            font-family: "Arial";
            font-size: 6pt;
            width: 100%;
            border-left: 2px solid;
            border-right: 2px solid;
            border-bottom: 2px solid;
        }
        .stample .stample-asign table td {
            width: 20%;
            text-align: center;
            border-bottom: 1px solid;
            border-right: 1px solid;
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
        label {
          display: inline-block;
          padding-left: 15px;
          text-indent: -15px;
        }
        input {
          width: 13px;
          height: 13px;
          padding: 0;
          margin:0;
          vertical-align: bottom;
          position: relative;
          top: -1px;
          *overflow: hidden;
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
<body class="page A4 landscape">
    <section class="page sheet custom">
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <div style="font-family: 'Arial Black'; font-size: 18pt;">PINJAMAN DINAS (PD)</div>
                </div>
                <div class="box-column-no">
                    <span>NO :</span>
                </div>
                <div class="box-column-frm">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>Dok. No.</td>
                            <td>:</td>
                            <td>&nbsp;FRM.NKE.13.01</td>
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
                        <tr>
                            <td>Lembar</td>
                            <td>:</td>
                            <td>&nbsp;1/1</td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="box-header-notes">
                <div id="notes"><b>CATATAN :</b></div>
                <div id="notes-content">
                    <ul class="dashed">
                        <li>PENGAJUAN INI TIDAK DILAYANI BILA : PENGAJUAN SEBELUMNYA BELUM DISELESAIKAN, BUDGET TIDAK MENCUKUPI / TIDAK ADA BUDGETNYA,</li>
                        <li>FORM LEMBAR MERAH UNTUK PERSONIL YANG BERSANGKUTAN, DILAMPIRKAN KEMBALI SAAT PENYELESAIAN DAN FORM LEMBAR KUNING DITARIK.</li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="box-header-content">
                <div class="box-column-10">
                    <span style="display: inline-block; width: 200px; padding: 5px;">NAMA : &nbsp;<?php echo $EMPNAME; ?></span>
                    <span style="display: inline-block; width: 300px; padding: 5px;">PROYEK/BAG.: &nbsp;<?php echo $PRJCODE; ?></span>
                    <span style="display: inline-block; width: 150px; padding: 5px;">JUMLAH : RP &nbsp;<?php echo number_format($Journal_Amount, 2); ?></span>
                    <span style="display: inline-block; width: 200px; padding: 5px;">(<?php echo $moneyFormat->terbilang($Journal_Amount); ?>)</span>
                    <span style="display: inline-block; width: 200px; padding: 5px;">RENCANA PEMBAYARAN : &nbsp;<?php echo date('d M Y', strtotime($JournalH_Date_PD)); ?></span>
                    <span style="display: inline-block; width: 100px; padding: 5px;">
                        <label><input type="checkbox" name="KAS" id="KAS" /> KAS</label>
                    </span>
                    <span style="display: inline-block; width: 100px; padding: 5px;">
                        <label><input type="checkbox" name="CHEQUE" id="CHEQUE" /> CHEQUE</label>
                    </span>
                    <span style="display: inline-block; width: 100px; padding: 5px;">
                        <label><input type="checkbox" name="GIRO" id="GIRO" /> GIRO</label>
                    </span>
                    <span style="display: inline-block; width: 200px; padding: 5px;">TANGGAL : &nbsp;<?php echo date('d M Y', strtotime($JournalH_Date)); ?></span>
                </div>
                <div class="box-column-2">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>PUTIH</td>
                            <td>:</td>
                            <td>&nbsp;VOUCHER</td>
                        </tr>
                        <tr>
                            <td>MERAH</td>
                            <td>:</td>
                            <td>&nbsp;PERSONIL YBS.</td>
                        </tr>
                        <tr>
                            <td>BIRU</td>
                            <td>:</td>
                            <td>&nbsp;COST CONTROL</td>
                        </tr>
                        <tr>
                            <td>KUNING</td>
                            <td>:</td>
                            <td>&nbsp;KEU/DITARIK</td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="box-content">
                <div class="box-column-6">
                    <span style="font-family: 'Arial Black'; font-size: 14pt;">UNTUK : &nbsp;</span>
                    <span style="font-family: 'Arial'; font-size: 10pt; display: block;">
                        <?php echo $JournalH_Desc; ?>
                    </span>
                    <div id="notes" style="font-family: 'Arial'; font-size: 6pt; font-weight: bold;">CATATAN :</div>
                    <div id="notes-content" style="font-family: 'Arial'; font-size: 6pt; font-weight: bold;">PENGGUNAAN DANA INI HARUS DISELESAIKAN SESUAI DENGAN RENCANA TERTULIS DISINI !!</div>
                </div>
                <div class="box-column-6" style="border-right: hidden;">
                    <span style="font-family: 'Arial Black'; font-size: 14pt;">SELESAI :</span>
                    <span style="font-family: 'Arial'; font-size: 10pt; display: block;">
                        
                    </span>
                    <div id="notes" style="font-family: 'Arial'; font-size: 6pt; font-weight: bold;">CATATAN :</div>
                    <div id="notes-content" style="font-family: 'Arial'; font-size: 6pt; font-weight: bold;">PENGGUNAAN DANA INI HARUS DISELESAIKAN SESUAI DENGAN RENCANA TERTULIS DISINI !!</div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="box-asign">
                <div class="box-column-2">
                    <div id="acc-top">
                        <div style="font-size: 6pt;">DISETUJUI DIBAYAR</div>
                        <div style="font-size: 6pt;">KEUANGAN</div>
                    </div>
                    <div id="acc-bottom">
                        <span id="nama">NAMA : &nbsp;____________________________________</span>
                        <span id="tanggal">Tanggal : &nbsp;____________________________________</span>
                    </div>
                </div>
                <div class="box-column-2">
                    <div id="acc-top">
                        <div style="font-size: 6pt;">D I S E T U J U I</div>
                        <div style="font-size: 6pt;">DIREKSI / KEP. CABANG</div>
                    </div>
                    <div id="acc-bottom">
                        <span id="nama">NAMA : &nbsp;____________________________________</span>
                        <span id="tanggal">Tanggal : &nbsp;____________________________________</span>
                    </div>
                </div>
                <div class="box-column-2">    
                    <div id="acc-top">
                        <div style="font-size: 6pt;">YANG BERTANGGUNG JAWAB</div>
                        <div style="font-size: 6pt; text-align: left; padding-left: 5px;">JAB.:</div>
                    </div>
                    <div id="acc-bottom">
                        <span id="nama">NAMA : &nbsp;____________________________________</span>
                        <span id="tanggal">Tanggal : &nbsp;____________________________________</span>
                    </div>
                </div>
                <div class="box-column-2">
                    <div id="acc-top">
                        <div style="font-size: 6pt; padding-top: 7px;">COST  CONTROL</div>
                    </div>
                    <div id="acc-bottom">
                        <span id="nama">NAMA : &nbsp;____________________________________</span>
                        <span id="tanggal">Tanggal : &nbsp;____________________________________</span>
                    </div>
                </div>
                <div class="box-column-2">
                    <div id="acc-top">
                        <div style="font-size: 6pt; padding-top: 7px;">YANG  MENERIMA</div>
                    </div>
                    <div id="acc-bottom">
                        <span id="nama">NAMA : &nbsp;____________________________________</span>
                        <span id="tanggal">Tanggal : &nbsp;____________________________________</span>
                    </div>
                </div>
                <div class="box-column-2" style="border-right: hidden;">
                    <div id="acc-top">
                        <div style="font-size: 6pt; padding-top: 7px;">K  A  S  I  R</div>
                    </div>
                    <div id="acc-bottom">
                        <span id="nama">NAMA : &nbsp;____________________________________</span>
                        <span id="tanggal">Tanggal : &nbsp;____________________________________</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="detail-page-notes">
            <div class="box-column-6" id="company">© PT  NUSA KONSTRUKSI ENJINIRING Tbk</div>
            <div class="box-column-6" id="docfile">File : FRM.NKE.13.01,  Auth : LT, DSR</div>
            <div class="clearfix"></div>
        </div>
        <div class="cont-stample">
            <div class="stample">
                <div class="stample-title">BENTUK  STEMPEL  PENYELESAIAN  :</div>
                <div class="stample-content">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="150">PINJAMAN  DINAS  ( PD )</td>
                            <td>= Rp</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="150">PENYELESAIAN  PD</td>
                            <td>= Rp</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr style="border-bottom: hidden;">
                            <td width="150">SISA  ( KURANG )</td>
                            <td>= Rp</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div class="stample-asign">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr style="border-right: hidden;">
                            <td>Verifikasi</td>
                            <td>PERSONIL</td>
                            <td>A C C</td>
                            <td>ACCOUNTING</td>
                            <td>KASIR</td>
                        </tr>
                        <tr style="height: 35px; border-right: hidden; border-bottom: hidden;">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>