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
        @font-face {
            font-family: 'Revision Triangles';
            src: url('<?php echo base_url("assets/fonts/ZAREVS2.TTF") ?>') format('truetype'); /* Chrome 4+, Firefox 3.5, Opera 10+, Safari 3—5 */
        }
        
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
            width: 100%;
            height: 62px;
            border-bottom: 2px solid;

        }
        .box-header .box-logo {
            float: left;
            width: 135px;
            height: 100%;
            padding-left: 2px;
            padding-top: 17px;
            /*border-right: 1px solid;*/
        }
        .box-header .box-logo > img {
            width: 33.38mm;
        }
        .box-header .box-title {
            float: left;
            padding-right: 5px;
            width: 310px;
            height: 100%;
        }
        .box-header .box-title > span:first-child {
            display: grid;
            margin-bottom: 7px;
            text-align: center;
            font-family: "Impact";
            font-size: 14pt;
            font-weight: bold;
        }
        .box-header .box-title > span:nth-child(2), 
        span:nth-child(3), span:nth-child(4) {
            display: inline-grid;
            padding-left: 0px;
            font-family: "Arial Narrow";
            font-size: 7pt;
            font-weight: bold;
        }
        .box-header .box-dist {
            float: left;
            font-family: "Calibri";
            font-size: 6pt;
            border-right: 1px solid;
            border-left: 1px solid;
            height: 100%;
        }
        .box-header .box-dist > span {
            display: block;
            font-weight: bold;
            font-size: 6.5pt;
            text-align: center;
            /*border-right: 1px solid;*/
        }
        .box-header .box-dist > table td {
            padding-left: 2px;
            border-right: 1px solid;
            border-top: 1px solid;
        }
        .box-header .box-frm {
            float: left;
            font-family: "Arial";
            font-size: 6pt;
            padding-top: 15px;
            padding-left: 5px;
            height: 100%;
        }
        .box-header-content {
            width: 100%;
            height: 30px;
            font-family: "Arial Narrow";
            font-size: 7pt;
            font-weight: bold;
            border-bottom: 2px solid;
        }
        .box-header-content > span:nth-child(1) {
            float: left;
            display: grid;
            width: 446px;
            height: 100%;
            padding-left: 5px;
            padding-top: 8px;
            border-right: 1px solid;
        }
        .box-header-content > span:nth-child(2) {
            float: left;
            display: grid;
            width: 150px;
            height: 100%;
            padding-left: 5px;
            padding-top: 8px;
            border-right: 1px solid;
        }
        .box-header-content > span:nth-child(3) {
            float: left;
            display: grid;
            padding-top: 8px;
            padding-left: 5px;
            height: 100%;
        }
        .box-notes {
            width: 100%;
            height: 100px;
            border-bottom: 2px solid;
            padding: 5px;
        }
        .box-notes > span:nth-child(1) {
            display: grid;
            font-family: "Arial Black";
            font-size: 8pt;
        }
        .box-notes .notes {
            float: left;
            width: 447px;
            margin-right: 25px;
        }
        .box-notes .notes > ul li {
            font-family: "Arial Narraw";
            font-size: 8pt;
            font-weight: bold;
            padding-left: 5px;
        }
        .box-notes .notes-nb {
            float: left;
            width: 250px;
            font-family: "Arial Narraw";
            font-size: 8pt;
        }
        .box-detail-header {
            float: left;
            width: 100%;
            height: 50px;
            border-bottom: 2px solid;
            margin-top: -6px;
        }
        .box-detail-header .box-column-1 {
            float: left;
            width: 190px;
            height: 100%;
            border-right: 1px solid;
            font-family: "Arial Black";
            font-size: 7pt;
            padding-top: 7px;
            padding-left: 5px;
        }
        .box-detail-header .box-column-1 > span:nth-child(1), span:nth-child(2) {
            display: block;
        }
        .box-detail-header .box-column-2 {
            float: left;
            width: 550px;
            font-family: "Arial Black";
            font-size: 7pt;
            /* padding-top: 5px; */
            /* padding-left: 5px; */
        }
        .box-detail-header .box-column-2 > span:nth-child(1) {
            padding-left: 5px;
        }
        .box-detail-header .box-column-2 > span:nth-child(2) {
            display: inline-block;
            font-family: "Arial";
        }
        .box-detail-header .box-column-2 > span:nth-child(3) {
            display: block;
            padding-left: 5px;
        }
        /* .box-detail-header .box-column-2 > span > 
        label:nth-child(1),label:nth-child(2),label:nth-child(3) {
            width: 100px;
        } */
        .box-detail-header .box-column-2 .receivecateg {
            /* background-color: red; */
            display: flex;
            justify-content: flex-start;
        }
        .receivecateg .categ1 {
            padding-left: 5px;
            /* flex-basis: fit-content; */
            /* background-color: red; */
            padding-right: 10px;
        }

        .box-detail-no {
            float: left;
            font-family: "Arial Narrow";
            font-size: 6pt;
            font-weight: bold;
            height: 40px;
        }
        .box-detail-no .box-column-1 {
            float: left;
            width: 190px;
            height: 100%;
            padding: 10px 5px 5px 5px;
            border-right: 1px solid;
        }
        .box-detail-no .box-column-1 > span {
            display: inline-block;
            padding-bottom: 5px;
        }
        .box-detail-no .box-column-2 {
            float: left;
            width: 565px;
            height: 100%;
            /*border-right: 1px solid;*/
        }
        .box-detail-no .box-column-2 > table td {
            padding: 5px;
            border-bottom: 1px solid;
        }
        .box-detail table {
            font-family: "Arial Narrow";
            font-size: 7pt;
            font-weight: bold;
        }
        .box-detail table thead th {
            text-align: center;
            padding: 3px;
        }
        .box-detail table tbody tr td {
            padding: 1px;
        }
        .box-detail table tfoot tr td {
            padding: 5px;
        }
        .box-asign table {
            font-family: "Arial Black";
            font-size: 6pt;
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
        ul.dashed {
            list-style-type: none;
            padding-left: 5px;
        }
        ul.dashed > li {
            text-indent: -6px;
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
        input[type='checkbox'] {
          width: 13px;
          height: 13px;
          padding: 0;
          margin:0;
          vertical-align: bottom;
          position: relative;
          top: -1px;
          *overflow: hidden;
        }
        #Layer1 {
            position: absolute;
            top: 0px;
            left: 20px;
        }
    </style>
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

        $IR_NUM      = $default['IR_NUM'];
        $IR_CODE     = $default['IR_CODE'];
        $IR_DATE     = $default['IR_DATE'];
        $IR_RECD     = $default['IR_RECD'];
        $IR_DUEDATE  = $default['IR_DUEDATE'];
        $IR_SOURCE   = $default['IR_SOURCE'];
        $PRJCODE     = $default['PRJCODE'];
        $SPLCODE     = $default['SPLCODE'];
        $PO_NUM      = $default['PO_NUM'];
        $PO_CODE     = $default['PO_CODE'];
        $PO_DATE     = $this->db->select("PO_DATE")->where(["PO_NUM" => $PO_NUM])->get("tbl_po_header")->row("PO_DATE");
        $PR_NUM      = $default['PR_NUM'];
        $PR_CODE     = $default['PR_CODE'];
        $IR_REFER    = $default['IR_REFER'];
        $IR_AMOUNT   = $default['IR_AMOUNT'];
        $TERM_PAY    = $default['TERM_PAY'];
        $TRXUSER     = $default['TRXUSER'];
        $APPROVE     = $default['APPROVE'];
        $IR_STAT     = $default['IR_STAT'];
        $INVSTAT     = $default['INVSTAT'];
        $IR_NOTE     = $default['IR_NOTE'];
        $IR_NOTE2    = $default['IR_NOTE2'];
        $REVMEMO     = $default['REVMEMO'];
        $WH_CODE     = $default['WH_CODE'];
        $IR_LOC      = $default['IR_LOC'];

        // GET SPLDESC
            $getSPL     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLSTAT = 1 AND SPLCODE = '$SPLCODE'";
            $resSPL     = $this->db->query($getSPL);
            $SPLDESC = '';
            if($resSPL->num_rows() > 0)
            {
                foreach($resSPL->result() as $rSPL):
                    $SPLDESC = $rSPL->SPLDESC;
                endforeach;
            }
    ?>
</head>
<body class="page A4">
    <section class="page sheet custom">
        <div id="Layer1" style="padding-top: 10px;">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
        <div class="cont">
            <div class="box-header">
                <div class="box-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-title">
                    <span>LAPORAN PENERIMAAN BARANG (LPB)</span>
                    <span>Beri tanda &#10003; pada salah satu: </span>
                    <span>
                        <label><input type="checkbox" name="Material" id="Material" checked /> Material</label>
                    </span>
                    <span>
                        <label><input type="checkbox" name="Alat" id="Alat" /> Alat</label>
                    </span>
                </div>
                <div class="box-dist">
                    <span>DISTRIBUSI</span>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-right: hidden;">
                        <tr>
                            <td width="40">&nbsp;</td>
                            <td width="80"><b><u>Pengadaan oleh NKE:</u></b></td>
                            <td width="70"><b><u>Supply by Owner:</u></b></td>
                        </tr>
                        <tr>
                            <td width="40">Putih</td>
                            <td width="80">Comptroller</td>
                            <td width="70">O w n e r</td>
                        </tr>
                        <tr>
                            <td width="40">Biru</td>
                            <td width="80">Gudang/Peralatan</td>
                            <td width="70">Gudang/Peralatan</td>
                        </tr>
                        <tr>
                            <td width="40">Merah</td>
                            <td width="80">Pengadaan</td>
                            <td width="70">Engineering/QC</td>
                        </tr>
                    </table>
                </div>
                <div class="box-frm">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="40">Doc. No</td>
                            <td width="5">:</td>
                            <td>FRM.NKE.07.32</td>
                        </tr>
                        <tr>
                            <td width="40">Rev</td>
                            <td width="5">:</td>
                            <td><span style="font-family: 'Revision Triangles'; color: red;">R</span>(30/12/22)</td>
                        </tr>
                        <tr>
                            <td width="40">Amd</td>
                            <td width="5">:</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-header-content">
                <span>PROYEK / BAG. : <?php echo $PRJCODE;  ?></span>
                <span>KODE : &nbsp;<?php echo $IR_CODE; ?></span>
                <span>Tgl. Input Sistem : &nbsp;<?php echo date('d M Y', strtotime($IR_DATE)); ?></span>
            </div>
            <div class="box-notes">
                <span>CATATAN PENERAPAN :</span>
                <div class="notes">
                    <ul class="dashed">
                        <li style="font-weight: normal;">LPB Dibuat per no.order dan per tanggal barang diterima atas dasar harga OP/Kontrak,</li>
                        <li style="font-weight: normal;">Mencatat no.gudang/peralatan & no. LPB (cetak) ini ke dalam surat jalan,</li>
                        <li style="font-weight: normal;">Pengadaan oleh Pusat, LPB sudah diserahkan/disampaikan (terlampir : surat jalan & dok. pendukungnya) ke Pusat/Cabang paling lambat (<b style="color: red;">4</b> hari : proyek dalam kota, <b style="color: red;">7</b> hari : proyek luar kota) terhitung sejak barang diterima</li>
                    </ul>
                </div>
                <div class="notes-nb">
                    <dd>
                        <dt style="text-indent: -20px; font-weight: normal;"><span style="padding-left: 10px; color: red;">*</span>: Coret yang tidak perlu</dt>
                        <dt style="text-indent: -20px; font-weight: normal;"><span style="padding-left: 5px; color: red;">**</span>: Bila lebih dari 1 kali pengiriman dari satu OP (Partial)</dt>
                        <dt style="text-indent: -20px; font-weight: normal;"><span style="color: red;">***</span>: Kolom disetujui Owner jika disyaratkan, terutama untuk material yang disuplai oleh Owner</dt>
                    </dd>
                </div>
            </div>
            <div class="box-detail-header">
                <div class="box-column-1">
                    <span>TANGGAL</span>
                    <span>BARANG DITERIMA : &nbsp; <?php echo date('d M Y', strtotime($IR_RECD)); ?></span>
                </div>
                <div class="box-column-2">
                    <span>DITERIMA DARI  :</span>
                    <span>Pilih dengan memberikan tanda &#10003;</span>
                    <div class="receivecateg">
                        <div class="categ1">
                            <label><input type="checkbox" name="SUPPLIER" id="SUPPLIER" checked /> SUPPLIER: &nbsp;</label>
                            <span><?php echo "$SPLDESC ($SPLCODE)"; ?></span>
                        </div>
                        <div class="categ1">
                            <label><input type="checkbox" name="GUDANG" id="GUDANG" /> GUDANG:</label>
                        </div>
                        <div class="categ1" style="padding-left: 70px;">
                            <label><input type="checkbox" name="PERALATAN" id="PERALATAN" /> PERALATAN:</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-detail-no">
                <div class="box-column-1">
                    <span>Nama PETUGAS PEMBELIAN / PENGADAAN</span>
                    <span>.............................................................................</span>
                </div>
                <div class="box-column-2">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="55">SPP/P No.:</td>
                            <td>&nbsp;<?php echo $PR_CODE; ?></td>
                            <td width="45">OP No.:</td>
                            <td>&nbsp;<?php echo $PO_CODE; ?></td>
                            <td>OP Tgl :</td>
                            <td>&nbsp;<?php echo date('d M Y', strtotime($PO_DATE)); ?></td>
                            <td width="100" colspan="2" style="border-left: 1px solid;">Nota No.(tunai): ..............,</td>
                            <td width="50" colspan="2">Tgl: ..........</td>
                        </tr>
                        <tr style="font-size: 5pt; border-bottom: hidden;">
                            <td colspan="5">Pengadaan oleh Pusat: SPP/P (Acc DIV. COMPTROLLER & SYSTEM ANALYSIS Pusat) Tgl:&nbsp;&nbsp;</td>
                            <td colspan="5" style="margin-left: -5px;">SPP/P (Diterima UNIT PENGADAAN BARANG Pusat) Tgl:&nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-detail">
                <table width="100%" border="1" cellpadding="1" cellspacing="1">
                    <thead>
                        <tr>
                            <th>JENIS BARANG</th>
                            <th>KODE ITEM</th>
                            <th>KODE POS PEK</th>
                            <th>UKURAN</th>
                            <th>VOLUME</th>
                            <th>SAT</th>
                            <th>HARGA</th>
                            <th>JUMLAH HARGA</th>
                            <th>NO. GUDANG</th>
                            <th>NO.SURAT JALAN</th>
                            <th>KE <span style="color: red;">**</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sqlDET     = "SELECT A.IR_ID, A.PRJCODE, A.IR_NUM, A.IR_CODE, A.JOBCODEDET, A.JOBCODEID,
                                                A.ACC_ID, A.PO_NUM, A.ITM_CODE, A.ITM_UNIT, A.ITM_UNIT2,
                                                A.ITM_QTY_REM, A.ITM_QTY, A.POD_ID,
                                                A.ITM_QTY_BONUS, A.ITM_PRICE, A.ITM_OA, A.ITM_TOTAL, A.ITM_DISP, A.JOBPARENT, A.JOBPARDESC,
                                                A.ITM_DISC, A.NOTES, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
                                                A.ISPRCREATE, A.ADD_PRVOLM, A.SJ_NUM,
                                                B.ITM_NAME, B.ACC_ID, B.ACC_ID_UM, B.ITM_GROUP, B.ITM_CATEG,
                                                B.ISMTRL, B.ISRENT, B.ISPART, B.ISFUEL, B.ISLUBRIC, 
                                                B.ISFASTM, B.ISWAGE,
                                                C.PR_NUM, C.PO_NUM
                                            FROM tbl_ir_detail A
                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                    AND B.PRJCODE = '$PRJCODE' -- AND B.ITM_CATEG NOT IN ('UA')
                                                INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM
                                                    AND C.PRJCODE = '$PRJCODE'
                                            WHERE 
                                                A.IR_NUM = '$IR_NUM' 
                                                AND A.PRJCODE = '$PRJCODE' ORDER BY B.ITM_NAME";
                            $result = $this->db->query($sqlDET)->result();

                            $sqlDETC    = "tbl_ir_detail A
                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                    AND B.PRJCODE = '$PRJCODE'
                                            WHERE 
                                            A.IR_NUM = '$IR_NUM' 
                                            AND A.PRJCODE = '$PRJCODE'";
                            $resultC    = $this->db->count_all($sqlDETC);

                            $i      = 0;
                            if($resultC > 0)
                            {
                                $IR_AMOUNT          = 0;
                                $IR_DISC            = 0;
                                $IR_PPN             = 0;
                                $IR_AMOUNT_NETT     = 0;
                                $TAXCODE_PPN        = "";
                                $TAXCODE_PPH        = "";
                                foreach($result as $row) :
                                    $currRow        = ++$i;
                                    $IR_NUM         = $row->IR_NUM;
                                    $PR_NUM         = $row->PR_NUM;
                                    $PO_NUM         = $row->PO_NUM;
                                    $PRJCODE        = $PRJCODE;
                                    $IR_ID          = $row->IR_ID;
                                    $SJ_NUM         = $row->SJ_NUM;
                                    $JOBCODEDET     = $row->JOBCODEDET;
                                    $JOBCODEID      = $row->JOBCODEID;
                                    $JOBPARENT      = $row->JOBPARENT;
                                    $JOBPARDESC     = $row->JOBPARDESC;
                                    $ACC_ID         = $row->ACC_ID;
                                    $ACC_ID_UM      = $row->ACC_ID_UM;
                                    $POD_ID         = $row->POD_ID;
                                    $ITM_CODE       = $row->ITM_CODE;
                                    $ITM_UNIT       = $row->ITM_UNIT;
                                    $ITM_UNIT2       = $row->ITM_UNIT2;
                                    $ITM_GROUP      = $row->ITM_GROUP;
                                    $ITM_CATEG      = $row->ITM_CATEG;
                                    $ITM_NAME       = $row->ITM_NAME;
                                    $ITM_QTY_REM    = $row->ITM_QTY_REM;
                                    $ITM_QTY        = $row->ITM_QTY;
                                    $ITM_PRICE      = $row->ITM_PRICE;
                                    $ITM_OA         = $row->ITM_OA;
                                    // $ITM_TOTAL      = $row->ITM_TOTAL;
                                    $ITM_TOTAL      = $ITM_QTY * $ITM_PRICE; // jumlah penerimaan sebelum ditambah OA (ongkos angkut)
                                    $NOTES          = $row->NOTES;

                                    if($ITM_UNIT2 == '') $ITM_UNIT2 = $ITM_UNIT;

                                    if($JOBPARENT == '')
                                    {
                                        $sqlJDP     = "SELECT A.JOBCODEID, A.JOBDESC FROM tbl_joblist_detail A
                                                        WHERE A.JOBCODEID = (SELECT B.JOBPARENT FROM tbl_joblist_detail B
                                                            WHERE B.JOBCODEID = '$JOBCODEID')";
                                        $resJDP     = $this->db->query($sqlJDP)->result();
                                        foreach($resJDP as $rowJDP) :
                                            $JOBPARENT  = $rowJDP->JOBCODEID;
                                            $JOBPARDESC = $rowJDP->JOBDESC;
                                        endforeach;
                                    }

                                    $CATEGNM_ITM = $this->db->distinct("IC_Name")->where(["IG_Code" => $ITM_GROUP, "IC_Code" => $ITM_CATEG])->get("tbl_itemcategory")->row("IC_Name");

                                    ?>
                                        <tr>
                                            <td>&nbsp;<?php echo $CATEGNM_ITM; ?></td>
                                            <td>&nbsp;<?php echo $ITM_CODE; ?></td>
                                            <td style="text-align: center;"><?php echo $JOBCODEID; ?></td>
                                            <td>&nbsp;<?php echo $NOTES; ?></td>
                                            <td style="text-align: center;"><?php echo number_format($ITM_QTY, 2); ?></td>
                                            <td style="text-align: center;"><?php echo $ITM_UNIT2; ?></td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?>&nbsp;</td>
                                            <td style="text-align: right;"><?php echo number_format($ITM_TOTAL, 2); ?>&nbsp;</td>
                                            <td style="text-align: center;"><?php echo $SJ_NUM; ?></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    <?php

                                endforeach;
                            }

                            $no = $i;
                            if($no <= 20)
                            {
                                $amRow = 20 - $no;
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
                    <tfoot>
                        <tr>
                            <td colspan="11">CATATAN HASIL PENERAPAN :</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-asign">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="20">&nbsp;</td>
                        <td width="80" style="text-align: center;">DITERIMA</td>
                        <td width="80" style="text-align: center;">MENGETAHUI</td>
                        <td width="80" style="text-align: center;">MENGETAHUI</td>
                        <td width="80" style="text-align: center;">DIPERIKSA & DIINPUT</td>
                        <td width="80" style="text-align: center;">MENGETAHUI</td>
                        <td width="90">
                            <span>DISETUJUI (OWNER)<span style="color: red;">***</span></span>
                            <span>PT :</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="20" height="50" style="font-family: 'Arial'; font-size:7pt;">Tanda-Tangan</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="20" style="font-family: 'Arial'; font-size:7pt;">Nama</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="20" style="font-family: 'Arial'; font-size:7pt;">Jabatan</td>
                        <td style="text-align: center; word-wrap: break-word;">GUDANG / PERALATAN</td>
                        <td style="text-align: center; word-wrap: break-word;">GENERAL ADMINISTRATION</td>
                        <td style="text-align: center; word-wrap: break-word;">ENGINEERING/QC</td>
                        <td style="text-align: center; word-wrap: break-word;">COST CONTROL PROY. / DIV. PERALATAN / DIV. ADM. UMUM & PER.<span style="color: red;">*</span>)</td>
                        <td style="text-align: center; word-wrap: break-word;">MANAJER PROY./KA. DEP. ......................<span style="color: red;">*</span>)</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="20" style="font-family: 'Arial'; font-size:7pt; word-wrap: break-word;">Tanggal</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="detail-page-notes">
            <div class="box-column-6" id="company">© PT  NUSA KONSTRUKSI ENJINIRING Tbk</div>
            <div class="box-column-6" id="docfile">File : FRM.NKE.07.32.xls,  Auth : HR, ES</div>
            <div class="clearfix"></div>
        </div>
    </section>
</body>
</html>