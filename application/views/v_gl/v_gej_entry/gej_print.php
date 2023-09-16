<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 25 Januari 2019
 * File Name    = gej_print.php
 * Location     = -
*/

//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;

$sqlApp         = "SELECT * FROM tappname";
$resultaApp     = $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
    $appName    = $therow->app_name;
    $comp_init  = $therow->comp_init;
    $comp_name  = $therow->comp_name;
endforeach;

$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$PRJCODEVW  = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$LangID     = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
    $h1_title   = 'JURNAL UMUM';
}
else
{
    $h1_title   = 'GENERAL JOURNAL';
}
//$CB_DATE = date('Y-m-d',strtotime($CB_DATE));

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

$JH_CODE    = '';
$JH_CODEY   = '';
$JH_DATEY   = '';
$JH_DATEM   = '';
$JH_DATED   = '';
$BNK_DESC   = '';
$JH_DESC    = '';
$CREATER_ID = '';
$sqlJH      = "SELECT JournalH_Code, Manual_No, JournalH_Date, JournalH_Desc, Journal_Amount, Emp_ID AS CREATER_ID
                FROM tbl_journalheader_gj
                WHERE JournalH_Code = '$CB_NUM'";
$resJH  = $this->db->query($sqlJH)->result();
foreach($resJH as $rowJH) :
    $JH_CODE    = $rowJH->JournalH_Code;
    $JH_CODEM   = $rowJH->Manual_No;
    $JH_DATEV   = date('d/m/Y', strtotime($rowJH->JournalH_Date));
    $JH_AMOUNT  = $rowJH->Journal_Amount;
    $JH_DATEY   = date('Y', strtotime($rowJH->JournalH_Date));
    $JH_DATEM   = date('m', strtotime($rowJH->JournalH_Date));
    $JH_DATED   = date('d', strtotime($rowJH->JournalH_Date));
    $JH_DESC    = $rowJH->JournalH_Desc;
    // $BNK_DESC   = $rowJH->Account_NameId;
    $CREATER_ID  = $rowJH->CREATER_ID;
endforeach;

$KD_BANK    = substr($JH_CODEM, 0, 3);
if($KD_BANK != 'GEJ')
{
    $NO_CODE    = substr($JH_CODEM, -3, 3);
    $DATE_CODE  = substr($JH_CODEM, 3, -3);
    $DATE_CODEY = substr($DATE_CODE, 0, 4);
    $DATE_CODEM = substr($DATE_CODE, 4, -2);
    $DATE_CODED = substr($DATE_CODE, -2, 2);
}
else
{
    $NO_CODE    = substr($JH_CODEM, -3, 3);
    $DATE_CODE  = substr($JH_CODEM, 3, -3);
    $DATE_CODEY = $JH_DATEY;
    $DATE_CODEM = $JH_DATEM;
    $DATE_CODED = $JH_DATED;
}
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
            width: 100%;
            margin-top: 50px;
        }
        .box-footer .box-asign-1 table td {
            border: 1px solid;
            width: 100px;
            border: hidden;
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
        $sqlPRJ     = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
        $resPRJ     = $this->db->query($sqlPRJ)->result();
        foreach($resPRJ as $rowPRJ) :
            $PRJNAME= $rowPRJ->PRJNAME;
        endforeach;
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
                    <span>JURNAL UMUM</span>
                </div>
            </div>
            <div class="box-header-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="70">&nbsp;</td>
                        <td width="5">&nbsp;</td>
                        <td width="450" style="font-weight: 700;">&nbsp;<?php // echo number_format($JH_AMOUNT, $decFormat); ?></td>
                        <td width="70">NOMOR</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $JH_CODEM; ?></td>
                    </tr>
                    <tr>
                        <td width="70">DESKRIPSI</td>
                        <td width="5">:</td>
                        <td width="450">&nbsp;<?php echo $JH_DESC; ?></td>
                        <td width="70">TANGGAL</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $JH_DATEV; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="10">No.</th>
                            <th width="50" nowrap>Kode Proyek</th>
                            <th width="200">Akun</th>
                            <th>Keterangan</th>
                            <th width="100">Debet</th>
                            <th width="100">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- GET Detail Jurnal -->
                            <?php
                                /*$getJD  = "SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Name, proj_Code,
                                            JournalD_Debet, JournalD_Kredit, Manual_No, Other_Desc, Journal_DK
                                            FROM tbl_journaldetail_$PRJCODEVW
                                            WHERE JournalH_Code = '$CB_NUM'
                                            ORDER BY FIELD(Journal_DK, 'D','K')";*/
                                $getJD  = "SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Name, proj_Code,
                                            JournalD_Debet, JournalD_Kredit, Manual_No, Other_Desc, Journal_DK
                                            FROM tbl_journaldetail_gj
                                            WHERE JournalH_Code = '$CB_NUM'
                                            ORDER BY FIELD(Journal_DK, 'D','K')";
                                $resJD  = $this->db->query($getJD);
                                if($resJD->num_rows() > 0)
                                {
                                    $no     = 0;
                                    $TOTJD  = 0;
                                    $TOTJK  = 0;
                                    foreach($resJD->result() as $rJD):
                                        $no                 = $no + 1;
                                        $JournalH_Code      = $rJD->JournalH_Code;
                                        $JournalH_Date      = $rJD->JournalH_Date;
                                        $JournalType        = $rJD->JournalType;
                                        $Acc_Id             = $rJD->Acc_Id;
                                        $Acc_Name           = $rJD->Acc_Name;
                                        $PRJCODE            = $rJD->proj_Code;
                                        $JournalD_Debet     = $rJD->JournalD_Debet;
                                        $JournalD_Kredit    = $rJD->JournalD_Kredit;
                                        $Manual_No          = $rJD->Manual_No;
                                        $Other_Desc         = $rJD->Other_Desc;
                                        $Journal_DK         = $rJD->Journal_DK;

                                        $TOTJD      = $TOTJD + $JournalD_Debet;
                                        $TOTJK      = $TOTJK + $JournalD_Kredit;
                                        $Account    = "$Acc_Id - $Acc_Name";

                                        if(strlen($Acc_Name) == 0)
                                        {
                                            // GET Account
                                                $getACC     = "SELECT Account_Number, Account_NameId, Account_NameEn
                                                                FROM tbl_chartaccount_$PRJCODEVW
                                                                WHERE Account_Number = '$Acc_Id'";
                                                $resACC     = $this->db->query($getACC);
                                                if($resACC->num_rows() > 0)
                                                {
                                                    $Acc_Name = '';
                                                    foreach($resACC->result() as $rACC):
                                                        $Account_Number     = $rACC->Account_Number;
                                                        $Account_NameId     = $rACC->Account_NameId;
                                                        $Account_NameEn     = $rACC->Account_NameEn;
                                                        if($LangID == 'ENG')
                                                            $Acc_Name = $Account_NameEn;
                                                        else
                                                            $Acc_Name       = $Account_NameId;

                                                        $Account            = "$Account_Number - $Acc_Name";
                                                    endforeach;
                                                }
                                        }

                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?=$no?></td>
                                                <td style="text-align: center;"><?=$PRJCODE?></td>
                                                <td style="padding-left: 5px;"><?php echo $Account; ?></td>
                                                <td style="padding-left: 5px;"><?php echo $Other_Desc; ?></td>
                                                <td style="text-align: right; padding-right: 5px;"><?php echo number_format($JournalD_Debet, $decFormat) ?></td>
                                                <td style="text-align: right; padding-right: 5px;"><?php echo number_format($JournalD_Kredit, $decFormat) ?></td>
                                            </tr>
                                        <?php
                                    endforeach;
                                }
                            ?>
                        <!-- END GET Detail Jurnal -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right; vertical-align: top; font-weight: bold;">Total</td>
                            <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?php echo number_format($TOTJD, $decFormat) ?></td>
                            <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?php echo number_format($TOTJK, $decFormat) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer" id="detail_JurnalHutang">
                <div class="box-asign-1">
                    <table width="100%" border="0">
                        <tr>
                            <td colspan="2" style="text-align:center">Dibuat Oleh:</td>
                            <td colspan="2" style="text-align:center">Diperiksa Oleh</td>
                            <td colspan="2" style="text-align:center">Disetujui Oleh:</td>
                        </tr>
                        <tr height="40px">
                        <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                        <td width="8%" style="text-align:center" valign="top">&nbsp;</td>
                        <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                        <td width="8%" style="text-align:center" valign="top">&nbsp;</td>
                        <td width="12%" style="text-align:center" valign="top">&nbsp;</td>
                        <td width="8%" style="text-align:center" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan="2" style="text-align:center;"><font style="text-decoration:underline">Nama : ..........................</font><br>Tanggal : ......................</td>
                        <td colspan="2" style="text-align:center"><font style="text-decoration:underline">Nama : ..........................</font><br>Tanggal : ......................</td>
                        <td colspan="2" style="text-align:center"><font style="text-decoration:underline">Nama : ..........................</font><br>Tanggal : ......................</td>
                        </tr>
                        <tr>
                        <td colspan="2" style="text-align:left">&nbsp;</td>
                        <td colspan="2" style="text-align:left">&nbsp;</td>
                        <td colspan="2" style="text-align:center">&nbsp;</td>
                        </tr>
                    </table>
                </div>
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