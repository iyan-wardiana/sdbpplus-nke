<?php 
$appName 		= $this->session->userdata('appName');
$ISPRINT_ORI	= $this->session->userdata('ISPRINT_ORI');

class moneyFormat
{ 
    public function rupiah ($angka) 
    {
        $rupiah = number_format($angka ,2, ',' , '.' );
        return $rupiah;
    }
 
    // public function terbilang ($angka)
    // {
    //     $angka = (float)$angka;
    //     $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
    //     if ($angka < 12) {
    //         return $bilangan[$angka];
    //     } else if ($angka < 20) {
    //         return $bilangan[$angka - 10] . ' Belas';
    //     } else if ($angka < 100) {
    //         $hasil_bagi = (int)($angka / 10);
    //         $hasil_mod = $angka % 10;
    //         return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
    //     } else if ($angka < 200) {
    //         return sprintf('Seratus %s', $this->terbilang($angka - 100));
    //     } else if ($angka < 1000) {
    //         $hasil_bagi = (int)($angka / 100);
    //         $hasil_mod = $angka % 100;
    //         return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
    //     } else if ($angka < 2000) {
    //         return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
    //     } else if ($angka < 1000000) {
    //         $hasil_bagi = (int)($angka / 1000); 
    //         $hasil_mod = $angka % 1000;
    //         return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
    //     } else if ($angka < 1000000000) {
    //         $hasil_bagi = (int)($angka / 1000000);
    //         $hasil_mod = $angka % 1000000;
    //         return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
    //     } else if ($angka < 1000000000000) {
    //         $hasil_bagi = (int)($angka / 1000000000);
    //         $hasil_mod = fmod($angka, 1000000000);
    //         return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
    //     } else if ($angka < 1000000000000000) {
    //         $hasil_bagi = $angka / 1000000000000;
    //         $hasil_mod = fmod($angka, 1000000000000);
    //         return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
    //     } else {
    //         return 'Data Salah';
    //     }
    // }
}

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

    // if(($ex[1]/10) >= 1){
    //     $a = abs($ex[1]);
    // }

    $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
    $temp = "";

    // $a = $ex[1]/10;
    $a = $ex[1];
    $pjg = strlen($str);
    $i =1;

    while ($i<$pjg)
    {     
        $char = substr($str,$i,1);
        if($char == 0)
        {
            $temp .= " ".$string[0];
            $i++;
        }
        else
        {
            $a = (int)$a;
            if($a>=1 && $a< 12)
            {   
                $temp .= " ".$string[$a];
            }
            else if($a>12 && $a < 20)
            {   
                $temp .= konversi($a - 10)." belas";
            }
            else if ($a>20 && $a<100)
            {   
                $temp .= konversi($a / 10)." puluh". konversi($a % 10);
            }
            else
            {
                /*if($a<1)
                {*/
                    while ($i<$pjg){     
                        $char = substr($str,$i,1);     
                        $i++;
                        $temp .= " ".$string[$char];
                    }
                //}
            }
            $i++;
        }
    }

    /*if($a>=1 && $a< 12){
        $temp .= " ".$string[$a];
    }else if($a>12 && $a < 20){   
        $temp .= konversi($a - 10)." belas";
    }else if ($a>20 && $a<100){   
        $temp .= konversi($a / 10)." puluh". konversi($a % 10);
    }else{
        if($a<1){
            while ($i<$pjg){     
                $char = substr($str,$i,1);     
                $i++;
                $temp .= " ".$string[$char];
            }
        }
    }*/

    return $temp;
}

function terbilang($x)
{
    if($x<0){
        $hasil = "minus ".trim(konversi($x));
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

// $moneyFormat = new moneyFormat();

$WO_NUM      = $default['WO_NUM'];
$WO_CODE     = $default['WO_CODE'];
$WO_DATE     = $default['WO_DATE'];
$WO_STARTD   = $default['WO_STARTD'];
$WO_STARTDv  = date('d/m/Y', strtotime($WO_STARTD));
$WO_ENDD     = $default['WO_ENDD'];
$WO_ENDDv    = date('d/m/Y', strtotime($WO_ENDD));
$PRJCODE     = $default['PRJCODE'];
$PRJCODE     = $default['PRJCODE'];
$SPLCODE     = $default['SPLCODE'];
$WO_DEPT     = $default['WO_DEPT'];
$WO_CATEG    = $default['WO_CATEG'];
$WO_TYPE     = $default['WO_TYPE'];
//$WO_PAYTYPE    = $default['WO_PAYTYPE'];
$JOBCODEID   = $default['JOBCODEID'];
$WO_NOTE     = nl2br($default['WO_NOTE']);
$WO_NOTE2    = nl2br($default['WO_NOTE2']);
$isPrint     = $default['isPrint'];
$WO_STAT     = $default['WO_STAT'];
$WO_VALUE    = $default['WO_VALUE'];
$WO_MEMO     = nl2br($default['WO_MEMO']);
$WO_PAYNOTE  = nl2br($default['WO_PAYNOTE']);
$PRJNAME     = $default['PRJNAME'];
$FPA_NUM     = $default['FPA_NUM'];
$FPA_CODE    = $default['FPA_CODE'];
$WO_QUOT     = $default['WO_QUOT'];
$WO_NEGO     = $default['WO_NEGO'];
$WO_STEP_AMD = $default['WO_STEP_AMD'];
$WO_ENDD_AMD = $default['WO_ENDD_AMD'];

if($WO_CATEG == 'U') $WO_CATEGD =  "Upah";
elseif($WO_CATEG == 'A') $WO_CATEGD = "Alat";
elseif($WO_CATEG == 'S') $WO_CATEGD = "Subkon";
elseif($WO_CATEG == 'O') $WO_CATEGD = "Overhead";
else $WO_CATEGD = "Alat Int.";

$sqlSpl = "SELECT SPLCODE, SPLDESC, SPLADD1, SPLTELP FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$sqlSpl = $this->db->query($sqlSpl)->result();
foreach($sqlSpl as $row) :
    $SPLCODE    = $row->SPLCODE;
    $SPLDESC    = $row->SPLDESC;
    $SPLADD1    = $row->SPLADD1;
    $SPLTELP    = $row->SPLTELP;
endforeach;
if($SPLADD1 == '') $SPLADD1 = '-';
if($SPLTELP == '') $SPLTELP = '-';

$PRJHO          = "KTR";
$sql            = "SELECT PRJNAME, PRJLOCT, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result         = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME    = $row->PRJNAME;
    $PRJLOCT    = $row->PRJLOCT;
    $PRJHO      = $row->PRJCODE_HO;
endforeach;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $appName; ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/contract.png'; ?>" sizes="32x32">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/jquery.min.js'; ?>"></script>
    <script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/qrcode.js'; ?>"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        @font-face {
            font-family: 'Revision Triangles';
            src: url('<?php echo base_url("assets/fonts/ZAREVS2.TTF") ?>') format('truetype'); /* Chrome 4+, Firefox 3.5, Opera 10+, Safari 3—5 */
        }

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
        .sheet.custom { padding: 1cm 1cm 0.97cm 1cm }
        
        #pageNo {
            position: absolute !important;
            top: 15px !important;
            left: 670px !important;
        }
        #pageNo span {
            font-size: 10pt !important;
            font-style: italic !important;
        }

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

            .sheet.custom { padding: 0.5cm 1cm 0cm 1cm }
            #pageNo {
                position: absolute !important;
                top: 0 !important;
                left: 670px !important;
            }
            #pageNo span {
                font-size: 10pt !important;
                font-style: italic !important;
            }
        }
        .cont {
            position: relative;
            /*border: 2px solid;*/
            font-family: "Arial";
            font-size: 8pt;
        }
        .box-header {
            /*position: relative;*/
            width: 100%;
            height: 70px;
            padding: 5px;
            border: 1px solid;
        }
        .box-header .box-column-logo {
            float: left;
            width: 200px;
            /*border: 1px solid;*/
        }
        .box-header .box-column-title {
            /*position: absolute;*/
            /*top: 20px;*/
            float: left;
            width: 500px;
            height: 100%;
            padding-top: 5px;
            /*border: 1px solid;*/
            text-align: center;
            /*background-color: gold;*/
        }
        .box-header .box-column-title > span {
            font-family: "Impact";
            font-size: 24pt;
            font-weight: bold;
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 5cm;
        }
        .box-header2 {
            /*position: relative;*/
            width: 100%;
            height: 50px;
            /* padding: 5px; */
            border-bottom: 1px solid;
        }
        .box-header2 .box-column-logo {
            float: left;
            width: 80px;
            /*border: 1px solid;*/
        }
        .box-header2 .box-column-title {
            /*position: absolute;*/
            /*top: 20px;*/
            float: left;
            width: 500px;
            height: 100%;
            padding-top: 5px;
            /*border: 1px solid;*/
            text-align: center;
            /*background-color: gold;*/
        }
        .box-header2 .box-column-title > span {
            font-family: "Impact";
            font-size: 11pt;
            /* font-weight: bold; */
        }
        .box-header2 .box-column-title#frm {
            /*position: absolute;*/
            /*top: 20px;*/
            float: left;
            width: 100px;
            height: 100%;
            padding-top: 5px;
            /*border: 1px solid;*/
            /*background-color: gold;*/
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7pt;
            text-align: left;
            line-height: 10px;
        }
        .box-header2 .box-column-logo img {
            margin: 9px auto;
            width: 80px;
        }
        .box-header-detail-col-6 {
            float: left;
            width: 50%;
            padding: 5px;
            height: 100px;
            border: 1px solid;
        }
        .box-header-detail-col-6 table td {
            /*background-color: gold;*/
            padding: 3px;
        }
        .box-header-detail-col-12 {
            border: 1px solid;
        }
        .box-header-detail-col-12 table td {
            /*background-color: gold;*/
            padding: 3px;
        }
        .box-detail {
            margin-top: 2px;
        }
        .box-detail table th, .box-detail table td {
            border: 1px solid;
            padding: 2px;
        }
        .box-detail table thead th, tbody td, tfoot td {
            padding: 2px;
        }
        .box-detail table thead th {
            text-align: center;
            border-top: double;
        }
        .box-detail #box-asign-1 tr td {
            border: hidden;
        }
        .box-detail tfoot td > p {
            margin: 0;
            padding: 0;
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
        #background{
			position:absolute;
			top: 50%;
			left: 30%;
			z-index:0;
			background:white !important;
			display:block;
			min-height:50%; 
			min-width:50%;
			color:yellow;
		}
        #bg-text
		{
			font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
			letter-spacing: 10px;
			color:lightgrey !important;
			font-size:70px;
			transform:rotate(300deg);
			-webkit-transform:rotate(300deg);
		}
        #Layer1 {
            position: absolute;
            top: 10px;
            left: 35px;
        }
    </style>
</head>
<body class="page A4">
    <?php
        // START: Create Paging
            $TOT_PG = 1;
            $PG     = 1;
        // END: Create Paging
    ?>
    <section class="page sheet custom">
        <?php
            $block_print    = 'block';
            $watermark_text = '';
            if($WO_STAT == 1 || $WO_STAT == 2 || $WO_STAT == 4 || $WO_STAT == 7)
            {
                $watermark_text = "DRAFT";
			    $block_print 	= '';
            }
            elseif($WO_STAT == 5 || $WO_STAT == 9)
            {
                $watermark_text = "VOID";
			    $block_print 	= '';
            }
            else
            {
                // if($ISPRINT_ORI == 1) // HOLD
                // {
                    $watermark_text = "ASLI";
                    $block_print 	= 'block';
                    if($isPrint == 1)
                    {
                        $watermark_text = "COPY";
                        $block_print 	= 'block';
                    }
                // }
                // else
                // {
                //     $watermark_text = "COPY";
                //     $block_print 	= 'block';
                // }
            }
        ?>
        <div id="Layer1" style="display: <?=$block_print?>;">
            <a href="#" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
        <div id="pageNo" style="display: none;">
            <span>Halaman : <?php echo "$PG/$TOT_PG";?></span>
        </div>
        <div id="background">
			<p id="bg-text"><?php echo $watermark_text; ?></p>
		</div>
        <div class="cont">
            <div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span>SURAT PERINTAH KERJA - <?php echo strtoupper($WO_CATEGD);?></span>
                </div>
            </div>
            <div style="width: 100%; height: 3px; background-color: gray !important;"></div>
            <div class="box-header-detail-col-6">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="110">Nomor SPK</td>
                        <td width="10">:</td>
                        <td><?php echo $WO_CODE; ?></td>
                    </tr>
                    <tr>
                        <td width="110">Tanggal SPK</td>
                        <td width="10">:</td>
                        <td><?php echo date('d-m-Y', strtotime($WO_DATE)); ?></td>
                    </tr>
                    <?php
                        $WO_DATEV   = date('d/m/Y', strtotime($WO_DATE));
                        $AMD_ENDDD  = "End Date Before : $WO_DATEV";
                        if($WO_ENDD_AMD == "0000-00-00" || $WO_ENDD_AMD == "")
                            $AMD_ENDDD  = "-";

                        $AMD_STEPD  = "";
                        if($WO_STEP_AMD > 0)
                            $AMD_STEPD  = "Add ke-$WO_STEP_AMD";
                    ?>
                    <tr>
                        <td width="110">Periode</td>
                        <td width="10">:</td>
                        <td title="<?=$AMD_ENDDD?>"><?php echo "$WO_STARTDv s/d $WO_ENDDv $AMD_STEPD"; ?></td>
                    </tr>
                    <tr>
                        <td width="110">Waktu Pelaksanaan</td>
                        <td width="10">:</td>
                        <td>Disesuaikan dengan schedule lapangan</td>
                    </tr>
                </table>
            </div>
            <div class="box-header-detail-col-6" style="border-left: hidden;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50">Supplier</td>
                        <td width="10">:</td>
                        <td><?php echo "$SPLDESC ($SPLCODE)"; ?></td>
                    </tr>
                    <tr>
                        <td width="50">Alamat</td>
                        <td width="10">:</td>
                        <td><?php echo $SPLADD1; ?></td>
                    </tr>
                    <tr>
                        <td width="50">Telp.</td>
                        <td width="10">:</td>
                        <td><?php echo $SPLTELP; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-header-detail-col-12">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50">Proyek</td>
                        <td width="10">:</td>
                        <td><?php echo "$PRJNAME - $PRJCODE"; ?></td>
                    </tr>
                    <tr>
                        <td width="50">Lokasi</td>
                        <td width="10">:</td>
                        <td><?php echo $PRJLOCT; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Uraian Pekerjaan</th>
                            <th width="80">Volume</th>
                            <th width="50">Sat</th>
                            <th width="100">Harga</th>
                            <th width="100">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            /*$sqlDET = "SELECT A.*,
                                            C.JOBDESC, C.JOBPARENT
                                        FROM tbl_wo_detail A
                                            INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
                                                AND C.PRJCODE = '$PRJCODE'
                                                AND A.ITM_CODE = C.ITM_CODE
                                            LEFT JOIN tbl_wo_header D ON D.WO_NUM = A.WO_NUM
                                                AND D.PRJCODE = C.PRJCODE
                                        WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND (A.WO_VOLM - A.WO_CVOL) > 0";*/
                            $sqlDET = "SELECT A.*,
                                            C.JOBDESC, C.JOBPARENT
                                        FROM tbl_wo_detail A
                                            INNER JOIN tbl_joblist_detail C ON A.JOBCODEID = C.JOBCODEID
                                                AND C.PRJCODE = '$PRJCODE'
                                            LEFT JOIN tbl_wo_header D ON D.WO_NUM = A.WO_NUM
                                                AND D.PRJCODE = C.PRJCODE
                                        WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND (A.WO_VOLM - A.WO_CVOL) > 0";
                            $result = $this->db->query($sqlDET)->result();
                            $no = 0;
                            $WO_SUBTOTAL    = 0;
                            $WO_GTOTAL      = 0;
                            $TOT_TAXPPN     = 0;
                            $TOT_TAXPPH     = 0;
                            foreach($result as $row) :
                                $no             = $no + 1;
                                $WO_ID          = $row->WO_ID;
                                $WO_NUM         = $row->WO_NUM;
                                $WO_CODE        = $row->WO_CODE;
                                $WO_DATE        = $row->WO_DATE;
                                $PRJCODE        = $row->PRJCODE;
                                $JOBCODEDET     = $row->JOBCODEDET;
                                $JOBCODEID      = $row->JOBCODEID;
                                $ITM_CODE       = $row->ITM_CODE;
                                $ITM_NAME       = $row->JOBDESC;
                                $SNCODE         = $row->SNCODE;
                                $ITM_UNIT       = $row->ITM_UNIT;
                                $ITM_UNIT2      = $row->ITM_UNIT2;
                                $WO_VOLM        = $row->WO_VOLM;
                                $ITM_PRICE      = $row->ITM_PRICE;
                                $WO_DISC        = $row->WO_DISC;
                                $WO_DISCP       = $row->WO_DISCP;
                                $WO_TOTAL       = $row->WO_TOTAL;
                                $WO_CVOL        = $row->WO_CVOL;
                                $WO_CAMN        = $row->WO_CAMN;
                                $WO_DESC        = $row->WO_DESC;
                                $TAXCODE1       = $row->TAXCODE1;
                                $TAXPERC1       = $row->TAXPERC1;
                                $TAXPRICE1      = $row->TAXPRICE1;
                                $TAXCODE2       = $row->TAXCODE2;
                                $TAXPERC2       = $row->TAXPERC2;
                                $TAXPRICE2      = $row->TAXPRICE2;
                                $WO_TOTAL2      = $row->WO_TOTAL2;
                                $ITM_BUDG_VOL   = $row->ITM_BUDG_VOL;

                                $WO_VOLM        = $WO_VOLM - $WO_CVOL;
                                $WO_TOTAL       = $WO_TOTAL - $WO_CAMN;
                                
                                $ITM_BUDG_AMN   = $row->ITM_BUDG_AMN;
                                $WO_SUBTOTAL    = $WO_SUBTOTAL + $WO_TOTAL;
                                $WO_GTOTAL      = $WO_GTOTAL + $WO_TOTAL + $TAXPRICE1 - $TAXPRICE2;
                                $WO_GTOTALv     = round($WO_GTOTAL, 2);
                                $itemConvertion = 1;

                                if($ITM_UNIT2 == '') $ITM_UNIT2 = $ITM_UNIT;


                                $OPN_VOLM       = $row->OPN_VOLM;
                                $REM_VOLWO      = $WO_VOLM - $OPN_VOLM;
                                
                                $TOT_TAXPPN     = $TOT_TAXPPN + $TAXPRICE1;
                                $TOT_TAXPPH     = $TOT_TAXPPH + $TAXPRICE2;

                                $UNITTYPE       = strtoupper($ITM_UNIT);
                                if($UNITTYPE == 'LS' )
                                    $ITM_BUDQTY = $ITM_BUDG_AMN;
                                else
                                    $ITM_BUDQTY     = $ITM_BUDG_VOL;

                                $JOBPARENT      = $row->JOBPARENT;
                                $JOBDESCH       = '';
                                $sqlJOBDESC     = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE' LIMIT 1";
                                $resJOBDESC     = $this->db->query($sqlJOBDESC)->result();
                                foreach($resJOBDESC as $rowJOBDESC) :
                                    $JOBDESCH   = $rowJOBDESC->JOBDESC;
                                endforeach;

                                ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $no; ?></td>
                                        <td>
                                            <div>
                                                <!-- <span><?php echo "$JOBCODEID - $ITM_NAME"; ?></span> -->
                                                <span><?php echo "$JOBCODEID"; ?></span>
                                            </div>
                                            <div style="font-style: italic;">
                                                <?php
                                                    $JOBDS  = strlen($JOBDESCH);
                                                    if($JOBDS > 50)
                                                    {
                                                        echo wordwrap($JOBDESCH, 45, '<br>');
                                                        echo " ...";
                                                    }
                                                    else
                                                    {
                                                        echo $JOBDESCH;
                                                    }
                                                ?>
                                            </div>
                                            <div>
                                                <?php 
                                                    if($WO_DESC == '') echo "";
                                                    else echo "<i class='text-muted fa fa-rss'></i> $WO_DESC"; 
                                                ?>
                                            </div>
                                        </td>
                                        <td style="text-align: center;"><?php echo number_format($WO_VOLM, 2); ?></td>
                                        <td style="text-align: center;"><?php echo $ITM_UNIT2; ?></td>
                                        <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($WO_TOTAL, 2); ?></td>
                                    </tr>
                                <?php

                            endforeach;
                            if($no <= 7)
                            {
                                $amRow = 7 - $no;
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
                                        </tr>
                                    <?php
                                }
                            }
                        ?>
                        
                        <tr>
                            <td colspan="4" rowspan="4" valign="top">
                                <p><b>Terbilang :</b></p>
                                <p><?php echo terbilang($WO_GTOTALv)." rupiah"; ?></p>
                            </td>
                            <td width="100">Subtotal :</td>
                            <td width="100" style="text-align: right;"><?php echo number_format($WO_SUBTOTAL, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">
                                <?php echo $TAXPERC1 != 0 ? "PPN $TAXPERC1 %":"PPN"; ?> :
                            </td>
                            <td width="100" style="text-align: right;"><?php echo number_format($TOT_TAXPPN, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">
                                <?php echo $TAXPERC2 != 0 ? "PPh $TAXPERC2 %":"PPh"; ?> :
                            </td>
                            <td width="100" style="text-align: right;"><?php echo number_format($TOT_TAXPPH, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100">Total :</td>
                            <td width="100" style="text-align: right;"><?php echo number_format($WO_GTOTAL, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php echo $this->db->get_where("tbl_wo_detail", ["PRJCODE" => $PRJCODE, "WO_NUM" => $WO_NUM])->num_rows() > 8 ? "</section>":""; ?>
        <?php echo $this->db->get_where("tbl_wo_detail", ["PRJCODE" => $PRJCODE, "WO_NUM" => $WO_NUM])->num_rows() > 8 ? "<section class=\"\page sheet custom\"\>":""; ?>
        <div class="cont">
            <div class="box-detail" style="margin-top: -1px;">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <td colspan="6">
                                <p><b><u>CATATAN :</u></b></p>
                                <p><?=$WO_NOTE?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <p><b><u>CARA PEMBAYARAN :</u></b></p>
                                <p><?=$WO_PAYNOTE?></p>
                            </td>
                        </tr>
                        <?php
                            // Approver 01
                                $APPD_01    = "";
                                $APPDV_01   = "";
                                $APPNV_01   = "";
                                $APP_01     = "";
                                $COMPIL_01  = "";
                                $POSIT_01   = "";
                                $s_01       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
                                                WHERE AH_CODE = '$WO_NUM' AND AH_APPLEV = 1";
                                $r_01       = $this->db->query($s_01)->result();
                                foreach($r_01 as $rw_01) :
                                    $APPD_01    = $rw_01->AH_APPROVED;
                                    $APPDV_01 	= "Tanggal : ".date("d/m/Y", strtotime($APPD_01));
                                    $APP_01     = $rw_01->complName;
                                    $APPNV_01   = "Nama : ".$APP_01;
                                    $COMPIL_01  = $APP_01." ".$APPD_01;
                                    $POSIT_01   = $rw_01->POSS_NAME;
                                endforeach;
                                $POSIT_01       = ""; // HOLD

                            // Approver 02
                                $APPD_02    = "";
                                $APPDV_02   = "";
                                $APPNV_02   = "";
                                $APP_02     = "";
                                $COMPIL_02  = "";
                                $POSIT_02   = "";
                                $s_02       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
                                                WHERE AH_CODE = '$WO_NUM' AND AH_APPLEV = 2";
                                $r_02       = $this->db->query($s_02)->result();
                                foreach($r_02 as $rw_02) :
                                    $APPD_02    = $rw_02->AH_APPROVED;
                                    $APPDV_02 	= "Tanggal : ".date("d/m/Y", strtotime($APPD_02));
                                    $APP_02     = $rw_02->complName;
                                    $APPNV_02   = "Nama : ".$APP_02;
                                    $COMPIL_02  = $APP_02." ".$APPD_02;
                                    $POSIT_02   = $rw_02->POSS_NAME;
                                endforeach;
                                $POSIT_02       = ""; // HOLD

                            // Approver 03
                                $APPD_03    = "";
                                $APPDV_03   = "";
                                $APPNV_03   = "";
                                $APP_03     = "";
                                $COMPIL_03  = "";
                                $POSIT_03   = "";
                                $s_03       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
                                                WHERE AH_CODE = '$WO_NUM' AND AH_APPLEV = 3";
                                $r_03       = $this->db->query($s_03)->result();
                                foreach($r_03 as $rw_03) :
                                    $APPD_03    = $rw_03->AH_APPROVED;
                                    $APPDV_03 	= "Tanggal : ".date("d/m/Y", strtotime($APPD_03));
                                    $APP_03     = $rw_03->complName;
                                    $APPNV_03   = "Nama : ".$APP_03;
                                    $COMPIL_03  = $APP_03." ".$APPD_03;
                                    $POSIT_03   = $rw_03->POSS_NAME;
                                endforeach;
                                $POSIT_03       = ""; // HOLD

                            // Approver 04
                                $APPD_04    = "";
                                $APPDV_04   = "";
                                $APPNV_04   = "";
                                $APP_04     = "";
                                $COMPIL_04  = "";
                                $POSIT_04   = "";
                                $s_04       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
                                                WHERE AH_CODE = '$WO_NUM' AND AH_APPLEV = 4";
                                $r_04       = $this->db->query($s_04)->result();
                                foreach($r_04 as $rw_04) :
                                    $APPD_04    = $rw_04->AH_APPROVED;
                                    $APPDV_04 	= "Tanggal : ".date("d/m/Y", strtotime($APPD_04));
                                    $APP_04     = $rw_04->complName;
                                    $APPNV_04   = "Nama : ".$APP_04;
                                    $COMPIL_04  = $APP_04." ".$APPD_04;
                                    $POSIT_04   = $rw_04->POSS_NAME;
                                endforeach;
                                $POSIT_04       = ""; // HOLD

                            // Approver 05
                                $APPD_05    = "";
                                $APPDV_05   = "";
                                $APPNV_05   = "";
                                $APP_05     = "";
                                $COMPIL_05  = "";
                                $POSIT_05   = "";
                                $s_05       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
                                                WHERE AH_CODE = '$WO_NUM' AND AH_APPLEV = 5";
                                $r_05       = $this->db->query($s_05)->result();
                                foreach($r_05 as $rw_05) :
                                    $APPD_05    = $rw_05->AH_APPROVED;
                                    $APPDV_05 	= "Tanggal : ".date("d/m/Y", strtotime($APPD_05));
                                    $APP_05     = $rw_05->complName;
                                    $APPNV_05   = "Nama : ".$APP_05;
                                    $COMPIL_05  = $APP_05." ".$APPD_05;
                                    $POSIT_05   = $rw_05->POSS_NAME;
                                endforeach;
                                $POSIT_05       = ""; // HOLD

                            if($PRJHO == 'NKE')         // PROYEK
                            {
                                $TOT_SIGN   = 3;
                                if($WO_CATEG == 'A') $TOT_SIGN = 2;
                            }
                            elseif($PRJHO == 'KTR')     // KTR
                                $TOT_SIGN   = 3;
                            else                        // AB
                                $TOT_SIGN   = 2;

                            $COLW           = 130;
                            $COLSP          = 4;
                            if($TOT_SIGN == 2)
                            {
                                $COLW       = 170;
                                $COLSP      = 3;
                                if($PRJHO == 'AB')
                                {
                                    $COLW       = 220;
                                    $COLSP      = 2;
                                }
                            }
                            else
                            {
                                if($PRJHO == 'KTR')
                                {
                                    $COLW       = 170;
                                    $COLSP      = 3;
                                }
                            }
                        ?>
                        <tr>
                            <td colspan="<?=$COLSP?>">
                                <div>
                                    <span style="display: inline-block; width: 150px;">Dikeluarkan di</span>
                                    <span style="display: inline-block;">: <?php echo "$PRJLOCT, ".date('d F Y', strtotime($WO_DATE)); ?></span>
                                </div>
                                <div>
                                    <span style="display: inline-block; width: 150px;">Yang memberikan pekerjaan</span>
                                    <span style="display: inline-block;">: <?php echo "$appName"; ?></span>
                                </div>
                            </td>
                            <td style="text-align: center;">Menyatakan Setuju Menerima Pekerjaan</td>
                        </tr>
                        <tr style="height: 100px;">
                            <td width="<?=$COLW?>" style="vertical-align: top; display: none;">
                                <div style="text-align: center; font-weight: bold;"><?=strtoupper($POSIT_01)?><br><br>
                                    <input id="app_01" type="app_01" value="<?php echo $COMPIL_01; ?>" style="width:80%; display:none" />
                                    <div id="qrc_01"></div>
                                    <div id="dt_app01" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPNV_01; ?></span></div>
									<div id="dt_app01"><span style="font-style: italic;"><?php echo $APPDV_01; ?></span></div>
                                </div><br>
                                <div style="display: none;">Nama : </div>
                            </td>
                            <td width="<?=$COLW?>" style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;"><?=strtoupper($POSIT_02)?><br><br>
                                    <input id="app_02" type="app_02" value="<?php echo $COMPIL_02; ?>" style="width:80%; display:none" />
                                    <div id="qrc_02"></div>
                                    <div id="dt_app02" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPNV_02; ?></span></div>
									<div id="dt_app02"><span style="font-style: italic;"><?php echo $APPDV_02; ?></span></div>
                                </div><br>
                                <div style="display: none;">Nama : </div>
                            </td>
                            <td width="<?=$COLW?>" style="vertical-align: top;" >
                                <div style="text-align: center; font-weight: bold;"><?=strtoupper($POSIT_03)?><br><br>
                                    <input id="app_03" type="app_03" value="<?php echo $COMPIL_03; ?>" style="width:80%; display:none" />
                                    <div id="qrc_03"></div>
                                    <div id="dt_app03" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPNV_03; ?></span></div>
									<div id="dt_app03"><span style="font-style: italic;"><?php echo $APPDV_03; ?></span></div>
                                </div><br>
                                <div style="display: none;">Nama : </div>
                            </td>
                            <td width="<?=$COLW?>" style="vertical-align: top; <?php if($PRJHO == 'AB') { ?> display: none; <?php } ?>" >
                                <div style="text-align: center; font-weight: bold;"><?=strtoupper($POSIT_04)?><br><br>
                                    <input id="app_04" type="app_04" value="<?php echo $COMPIL_04; ?>" style="width:80%; display:none" />
                                    <div id="qrc_04"></div>
                                    <div id="dt_app04" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPNV_04; ?></span></div>
									<div id="dt_app04"><span style="font-style: italic;"><?php echo $APPDV_04; ?></span></div>
                                </div><br>
                                <div style="display: none;">Nama : </div>
                            </td>
                            <td width="<?=$COLW?>" style="vertical-align: top; <?php if($TOT_SIGN == 2 || ($PRJHO == 'NKE' && $WO_CATEG == 'A') || ($TOT_SIGN == 3 && $PRJHO == 'KTR')) { ?> display: none; <?php } ?>" >
                                <div style="text-align: center; font-weight: bold;"><?=strtoupper($POSIT_05)?><br><br>
                                    <input id="app_05" type="app_05" value="<?php echo $COMPIL_05; ?>" style="width:80%; display:none" />
                                    <div id="qrc_05"></div>
                                    <div id="dt_app05" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPNV_05; ?></span></div>
									<div id="dt_app05"><span style="font-style: italic;"><?php echo $APPDV_05; ?></span></div>
                                </div><br>
                                <div style="display: none;">Nama : </div>
                            </td>
                            <td style="vertical-align: top;">
                                <div style="text-align: center; font-weight: bold;">PEMASOK</div>
                                <div style="margin-top: 70px;"></div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
    <section class="page sheet custom" id="cetakBalik">
        <div class="cont" style="padding-left: 5px; padding-right: 5px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>
                            <div class="box-header2">
                                <div class="box-column-logo">
                                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                                </div>
                                <div class="box-column-title">
                                    <span>KETENTUAN PELAKSANAAN SURAT PERINTAH KERJA (SPK) PEKERJAAN PEMBORONGAN</span>
                                    <div style="font-style: italic;">Note: <span style="color: red;">*</span>) Coret / hilangkan yang tidak perlu</div>
                                </div>
                                <div class="box-column-title" id="frm">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td nowrap>Dok. No.</td>
                                            <td>:</td>
                                            <td>FRM.NKE.07.46</td>
                                        </tr>
                                        <tr>
                                            <td nowrap>Revisi</td>
                                            <td>:</td>
                                            <td><span style="font-family: 'Revision Triangles'; color: red;">R</span>(15/03/23)</td>
                                        </tr>
                                        <tr>
                                            <td nowrap>Amand.</td>
                                            <td>:</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="box-detail" style="padding-top: 5px; font-family: 'Arial Narrow'; font-size: 8pt;">
                <ol type="1" style="padding-left: 10px; text-align: justify;">
                    <li>
                        <span style="font-weight: bold;">HARGA BORONGAN</span>
                        <dd style="text-align: justify;">Harga Borongan yang telah disetujui, adalah merupakan harga yang sudah pasti dan tidak terjadi klaim yang diakibatkan naiknya harga.</dd>
                    </li>
                    <li>
                        <span style="font-weight: bold;">SYARAT PEMBAYARAN</span>
                        <ol type="a" style="padding-left: 10px;">
                            <li>Uang muka akan dibayarkan (dipotong PPh 23) setelah tagihan diterima benar & lengkap dan setelah pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> menerima pembayaran dari <span style="font-weight: bold;">Pemilik Proyek</span> serta <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> memberikan Jaminan Bank dengan jangka waktu jaminan selama periode SPK ini atau Kontrak. Uang muka ini akan dikurangkan berangsur-angsur pada setiap tahap pembayaran dengan ketentuan selambat-lambatnyanya harus lunas pada saat penyelesaian pekerjaan mencapai 80%.<span style="color: red;">*</span>)</li>
                            <li>Pembayaran selanjutnya diperhitungkan sesuai prestasi / opname pekerjaan yang telah diselesaikan atau sesuai kontrak yang telah disetujui, dikurangi pengembalian uang muka secara proporsional dan retensi sebesar 5%, serta dipotong PPh 23 yang pembayarannya dilaksanakan setelah berkas tagihan opname benar dan lengkap (terlampir opname sebelumnya untuk opname ke-2, ke-3, dst.) serta berdasarkan besaran prestasi <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> yang diakui oleh <span style="font-weight: bold;">Pemilik Proyek</span> sesuai pekerjaan yang telah dikerjakan <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span>.</li>
                            <li>Pembayaran terakhir sebesar 5% (dipotong PPh 23), akan dibayarkan setelah selesainya masa pemeliharaan dan pekerjaan telah diserahkan untuk kedua kalinya serta diterima baik oleh pihak <span style="font-weight: bold;">Pemberi tugas / Kontraktor <span style="color: red;">*</span>)</span> dan <span style="font-weight: bold;">Pemilik Proyek</span>. Tagihan retensi hanya dapat dibayarkan setelah berkas tagihan lengkap dengan BAST II dan hanya jumlah nilai tertera pada lembar retensi asli saja yang dapat ditagihkan.</li>
                            <li>Pembayaran Pemasok dilakukan pada hari Rabu.</li>
                        </ol>
                    </li>
                    <li>
                        <span style="font-weight: bold;">PENYELENGGARAAN PEKERJAAN</span>
                        <ol type="a" style="padding-left: 10px;">
                            <li><span style="font-weight: bold;">Mandor / Subkon<span style="color: red;">*</span>)</span> bertanggung-jawab sepenuhnya ketentuan-ketentuan Keselamatan, Kesehatan Kerja dan Lingkungan Hidup (K3LH) :
                                <ol type="1" style="padding-left: 10px;">
                                    <li>Peraturan tentang Sistem Manajemen Keselamatan dan Kesehatan Kerja (SMK3) dan Lingkungan Hidup,</li>
                                    <li>Persyaratan Sistem Manajemen Keselamatan dan Kesehatan Kerja ISO 45001, Sistem Manajemen Lingkungan ISO 14001 <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>,</li>
                                    <li>Menyujutui dan mematuhi Surat Kesanggupan <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> terhadap penyelenggaraan Sistem Manajemen K3L,</li>
                                    <li>Menyetujui dan mematuhi Surat Pernyataan Sanksi / Denda Pelanggaran Program / Ketentuan “Keselamatan, Kesehatan Kerja Dan Lingkungan Hidup (K3LH) <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span></li>
                                    <li>Memahami dan mengikuti Kebijakan K3L dan Kebijakan Khusus K3L <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> sesuai Peraturan Perundangan yang berlaku,</li>
                                    <li>Melaksanakan Program SMK3L <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>,</li>
                                </ol>
                            </li>
                            <li><span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> bertanggung jawab sepenuhnya terhadap volume, kualitas dan waktu pekerjaannya.</li>
                            <li><span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> wajib membuat bobot prestasi mingguan dan diserahkan kepada pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span></li>
                            <li><span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> tidak boleh melimpahkan sebagian atau kesuluruhan pekerjaan kepada <span style="font-weight: bold;">PIHAK KE-TIGA</span> kecuali telah disetujui secara tertulis pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span></li>
                            <li><span style="font-weight: bold;">Mandor / Subkon<span style="color: red;">*</span>)</span> menyerahkan Jaminan Pelaksanaan  sebesar 5% dari nilai SPK ini atau kontrak dengan jangka waktu Jaminan selama periode SPK ini atau kontrak. *)</li>
                        </ol>
                    </li>
                    <li>
                        <span style="font-weight: bold;">SERAH-TERIMA PEKERJAAN PERTAMA</span>
                        <dd>Pada akhir jangka waktu pelaksanaan pekerjaan, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> diwajibkan menyerahkan untuk yang pertama kali semua jenis pekerjaan Kepada <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dalam keadaan selesai seluruhnya, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span>  wajib membuat Berita Acara Serah-Terima Pekerjaan Pertama (BAST I).</dd>
                    </li>
                    <li>
                        <span style="font-weight: bold;">MASA PEMELIHARAAN</span>
                        <ol type="a" style="padding-left: 10px;">
                            <li>Terhitung mulai pekerjaan diserahkan untuk pertama kalinya, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> setuju bahwa pemeliharaan hasil pekerjaan tetap menjadi tanggung-jawabnya, karena itu <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> atas perintah <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dengan segera mengadakan perbaikan / pembetulan segala kekurangan-kekurangan atau cacat-cacat (defect) hingga sampai pada penyelesaian akhir dapat memuaskan pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dan Pemilik Proyek.</li>
                            <li><span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> setuju jika dengan sekali diperingatkan secara tertulis oleh <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> tidak melaksanakan perintah perbaikan pekerjaan tersebut, maka <span style="font-weight: bold;">PIHAK KE-TIGA</span> akan melaksanakan pekerjaan perbaikan tersebut atas perintah pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dengan seluruh biaya dibebankan kepada <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span>, dengan secara sepihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dapat mencairkan uang / dana retensi yang ada atau pemotongan nilai tagihan opname pekerjaan.</li>
                            <li>Pada masa pemeliharaan berakhir, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> wajib menyerahkan hasil-hasil pekerjaan untuk yang ke dua kalinya kepada pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>. Serah-terima Pekerjaan oleh <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> harus dinyatakan dalam Berita Acara Serah-Terima Pekerjaan Kedua (BAST II) yang ditanda-tangani bersama oleh <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dan <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> serta Pemilik Proyek.</li>
                        </ol>
                    </li>
                    <li>
                        <span style="font-weight: bold;">PEKERJAAN TAMBAH</span>
                        <ol type="a" style="padding-left: 10px;">
                            <li>Pekerjaan tambah hanya dapat dilaksanakan setelah mendapat persetujuan tertulis pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dengan dibuat <u>SPK baru</u> tentang Pekerjaan tambah.</li>
                            <li>Bila pekerjaan tambah tetap dijalankan tanpa mendapat persetujuan tertulis pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>, segala akibat pelaksanaan pekerjaan tambah tersebut menjadi tanggungan <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> dan <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> tidak berhak meminta pembayaran dari pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>.</li>
                        </ol>
                    </li>
                    <li>
                        <span style="font-weight: bold;">SANKSI DAN DENDA</span>
                        <ol type="a" style="padding-left: 10px;">
                            <li>Apabila <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> tidak mematuhi atau tidak melaksanakan ketentuan K3L yang telah ditetapkan, akan diberikan sanksi / denda sesuai kesepakatan butir <u>3.a.4</u> diatas.</li>
                            <li>Untuk setiap kelalaian <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> menjalankan peraturan dalam bestek, dan tidak segera melaksanakan perintah atau petunjuk tertulis dari pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> yang merupakan surat teguran sebanyak <u>1 (satu) kali</u>, <span style="font-weight: bold;">PIHAK KE-TIGA</span> akan melaksanakan pekerjaan tersebut atas perintah <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> dengan seluruh biaya dibebankan kepada <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span>.</li>
                            <li>Untuk keterlambatan penyelesaian pekerjaan tersebut, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> dikenakan denda sebesar 0,1% dari total harga SPK ini untuk setiap hari keterlambatan atau setinggi-tingginya sebesar 5% dari total harga SPK.</li>
                        </ol>
                    </li>
                    <li>
                        <span style="font-weight: bold;">PEMUTUSAN SPK</span>
                        <ol type="a" style="padding-left: 10px;">
                            <li>Dalam waktu <u>7 (tujuh) hari kalender</u> terhitung mulai berlakunya SPK ini, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> tidak atau belum memulai pekerjaannya secara pisik di lapangan.</li>
                            <li>Dalam waktu <u>14 (empat belas) hari kalender</u> berturut-turut tanpa ada alasan <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> tidak melanjutkan pekerjaan yang sudah dimulai sehingga menimbulkan kemacetan.</li>
                            <li>Pihak  <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> memberikan keterangan tidak benar yang merugikan pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span> sehubungan dengan pekerjaan borongan  tersebut.</li>
                            <li><span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> memberikan sebagian atau seluruh pekerjaan kepada PIHAK  KE-TIGA tanpa persetujuan tertulis <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>.</li>
                            <li>Bila <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> melakukan kesalahan / kelalaian yang menyebabkan pekerjaan tertunda / terlambat melebihi 5% dari harga upah / pekerjaan borongan.</li>
                        </ol>
                    </li>
                    <li>Sebagaimana tersebut pada butir 8 diatas, <span style="font-weight: bold;">Mandor / Subkon <span style="color: red;">*</span>)</span> setuju bahwa dapat dituntut secara hukum karena merugikan pihak <span style="font-weight: bold;">Pemberi Tugas / Kontraktor <span style="color: red;">*</span>)</span>.</li>
                    <li>SPK berikut persyaratan yang berlaku disini adalah merupakan bagian yang tidak dapat dipisahkan dengan Surat Perjanjian Pekerjaan Pemborongan (Kontrak) yang telah disetujui ke dua belah pihak.</li>
                </ol>
                <span style="display: none;">Ditetapkan dan disetujui di ……………………………………..………,  tanggal ……………………..………………….</span>
                <table width="100%" cellpadding="0" cellspacing="0" rule="all" style="display: none;">
                    <tr>
                        <td width="50%">
                            <span>Pemberi Tugas / SPK (PT NUSA KONSTRUKSI ENJINIRING TBK)</span>
                            <div style="width: 100%; text-align: right; padding-right: 50px;">Tanda-tangan :</div>
                            <div style="width: 100%;">Nama :</div>
                            <div style="width: 100%;">Jabatan : Direktur Operasi / Direktur …… *)</div>
                        </td>
                        <td width="50%">
                            <span>Penerima SPK (MANDOR / SUBKONTRAKTOR) *)</span>
                            <div style="width: 100%; text-align: right; padding-right: 50px;">Tanda-tangan :</div>
                            <div style="width: 100%;">Nama :</div>
                            <div style="width: 100%;">Jabatan :</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
<style>
#qrc_01 {
  display: table;
  margin: 0 auto;
}
#qrc_02 {
  display: table;
  margin: 0 auto;
}
#qrc_03 {
  display: table;
  margin: 0 auto;
}
#qrc_04 {
  display: table;
  margin: 0 auto;
}
#qrc_05 {
  display: table;
  margin: 0 auto;
}
</style>
<script type="text/javascript">
    var WO_STAT     = <?php echo $WO_STAT ?>;
    var ISPRINT_ORI	= <?php echo $ISPRINT_ORI ?>;
    $('#Layer1 > a').on('click', function(){
        $(this).css("visibility", "hidden");
        window.print();
    });

    document.addEventListener("keydown", function (event) {
        console.log(event);
        if (event.ctrlKey) {
            event.preventDefault();
            // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
        }   
    });

    var mediaQueryList = window.matchMedia('print');
    mediaQueryList.addListener(function(mql) {
        if (mql.matches) {
            console.log('onbeforeprint');
        } else {
            console.log('onafterprint');
            // Update isPrint = 1
            // if(WO_STAT == 3 || WO_STAT == 6 && ISPRINT_ORI == 1) // HOLD
            if(WO_STAT == 3 || WO_STAT == 6)
            {
                let WO_CODE = '<?php echo $WO_CODE; ?>';
				let isPrint = '<?php echo $isPrint; ?>';
                $.ajax({
                    url: "<?php echo base_url('c_project/c_s180d0bpk/watermark_upd/?id='.$this->url_encryption_helper->encode_url($WO_NUM)); ?>",
                    type: "POST",
					data: {WO_CODE:WO_CODE, isPrint:isPrint},
                    success: () => {
                        close();
                    }
                });
            }
        }
    });

    var qrc_01 = new QRCode(document.getElementById("qrc_01"), {
        width : 80,
        height : 80
    });

    var qrc_02 = new QRCode(document.getElementById("qrc_02"), {
        width : 80,
        height : 80
    });

    var qrc_03 = new QRCode(document.getElementById("qrc_03"), {
        width : 80,
        height : 80
    });

    var qrc_04 = new QRCode(document.getElementById("qrc_04"), {
        width : 80,
        height : 80
    });

    var qrc_05 = new QRCode(document.getElementById("qrc_05"), {
        width : 80,
        height : 80
    });

    function makeCode ()
    {      
        var elText_01 = document.getElementById("app_01");
        var elText_02 = document.getElementById("app_02");
        var elText_03 = document.getElementById("app_03");
        var elText_04 = document.getElementById("app_04");
        var elText_05 = document.getElementById("app_05");

        if(elText_01.value != '')
        {
            if (!elText_01.value)
            {
                alert("Input a text");
                elText_01.focus();
                return;
            }
            qrc_01.makeCode(elText_01.value);
        }
        
        if(elText_02.value != '')
        {
            if (!elText_02.value)
            {
                alert("Input a text 2");
                elText_02.focus();
                return;
            }
            qrc_02.makeCode(elText_02.value);
        }

        if(elText_03.value != '')
        {
            if (!elText_03.value)
            {
                alert("Input a text 3");
                elText_03.focus();
                return;
            }
            qrc_03.makeCode(elText_03.value);
        }

        if(elText_04.value != '')
        {
            if (!elText_04.value)
            {
                alert("Input a text 4");
                elText_04.focus();
                return;
            }
            qrc_04.makeCode(elText_04.value);
        }

        if(elText_05.value != '')
        {
            if (!elText_05.value)
            {
                alert("Input a text 4");
                elText_05.focus();
                return;
            }
            qrc_05.makeCode(elText_05.value);
        }
    }

    makeCode();
</script>