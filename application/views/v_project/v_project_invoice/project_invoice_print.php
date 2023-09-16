<?php
/*
 * Author       = Dian Hermanto
 * Create Date  = 11 Maret 2017
 * File Name    = project_invoice_print.php
 * Location     = -
*/
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

function KonDecRomawi($angka)
{
    $hsl = "";
    if($angka<1||$angka>3999){
        $hsl = "Batas Angka 1 s/d 3999";
    }else{
         while($angka>=1000){
             $hsl .= "M";
             $angka -= 1000;
         }
         if($angka>=500){
             if($angka>500){
                 if($angka>=900){
                     $hsl .= "CM";
                     $angka-=900;
                 }else{
                     $hsl .= "D";
                     $angka-=500;
                 }
             }
         }
         while($angka>=100){
             if($angka>=400){
                 $hsl .= "CD";
                 $angka-=400;
             }else{
                 $angka-=100;
             }
         }
         if($angka>=50){
             if($angka>=90){
                 $hsl .= "XC";
                  $angka-=90;
             }else{
                $hsl .= "L";
                $angka-=50;
             }
         }
         while($angka>=10){
             if($angka>=40){
                $hsl .= "XL";
                $angka-=40;
             }else{
                $hsl .= "X";
                $angka-=10;
             }
         }
         if($angka>=5){
             if($angka==9){
                 $hsl .= "IX";
                 $angka-=9;
             }else{
                $hsl .= "V";
                $angka-=5;
             }
         }
         while($angka>=1){
             if($angka==4){
                $hsl .= "IV";
                $angka-=4;
             }else{
                $hsl .= "I";
                $angka-=1;
             }
         }
    }
    return ($hsl);
}

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;

$comp_name      = $this->session->userdata['comp_name'];
$sqlApp         = "SELECT * FROM tappname";
$resultaApp     = $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
    $comp_add   = $therow->comp_add;
endforeach;
$own_Title      = '';
$own_Code       = '';
$own_Name       = '';
$own_Add1       = '';

$sqlOwner       = "SELECT B.own_Code, B.own_Title, B.own_Name, B.own_Add1
                    FROM tbl_project A
                        INNER JOIN tbl_owner B ON B.own_Code = A.PRJOWN
                    WHERE A.PRJCODE = '$PRJCODE'";
$ressqlOwner    = $this->db->query($sqlOwner)->result();
foreach($ressqlOwner as $rowqlOwner) :
    $own_Title      = $rowqlOwner ->own_Title;
    $own_Code       = $rowqlOwner ->own_Code;
    $own_Name       = $rowqlOwner ->own_Name;
    $own_Add1       = $rowqlOwner ->own_Add1;
endforeach;

if($PINV_CAT != 1)
{
    $sqlPINVH           = "SELECT * FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_CODE = '$PINV_CODE'";
    $ressqlPINVH        = $this->db->query($sqlPINVH)->result();
    foreach($ressqlPINVH as $rowPINVH) :
        $PINV_CODE      = $rowPINVH ->PINV_CODE;
        $PINV_MANNO     = $rowPINVH ->PINV_MANNO;
        $PINV_STEP      = $rowPINVH ->PINV_STEP;
        $PINV_CAT       = $rowPINVH ->PINV_CAT;
        $PINV_SOURCE    = $rowPINVH ->PINV_SOURCE;
        $PRJCODE        = $rowPINVH ->PRJCODE;
        $PINV_OWNER     = $rowPINVH ->PINV_OWNER;
        $PINV_DATE      = $rowPINVH ->PINV_DATE;
        $PINV_DATEx     = date('Y-m-d', strtotime('-1 days', strtotime($PINV_DATE)));
        $PINV_ENDDATE   = $rowPINVH ->PINV_ENDDATE;
        $PINV_CHECKD    = $rowPINVH ->PINV_CHECKD;
        $PINV_CREATED   = $rowPINVH ->PINV_CREATED;
        $PINV_RETVAL    = $rowPINVH ->PINV_RETVAL;
        $PINV_RETCUT    = $rowPINVH ->PINV_RETCUT;
        $PINV_RETCUTPPn = round($PINV_PPNPERC * $PINV_RETCUT / 100);
        $PINV_DPPER     = $rowPINVH ->PINV_DPPER;
        $PINV_DPVAL     = $rowPINVH ->PINV_DPVAL;
        $PINV_DPVALPPn  = $rowPINVH ->PINV_DPVALPPn;
        $PINV_DPBACKCUR = $rowPINVH ->PINV_DPBACKCUR;

        $s_DPB          = "SELECT SUM(PINV_DPBACKCUR) AS PINV_DPBACK FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_STEP <= $PINV_STEP";
        $r_DPB          = $this->db->query($s_DPB)->result();
        foreach($r_DPB as $rw_DPB) :
            $PINV_DPBACK    = $rw_DPB ->PINV_DPBACK;
        endforeach;

        $PINV_PROG      = $rowPINVH ->PINV_PROG;
        $PINV_PROGVAL   = $rowPINVH ->PINV_PROGVAL;
        $PINV_PROGVALPPn= $rowPINVH ->PINV_PROGVALPPn;
        $PINV_PROGAPP   = $rowPINVH ->PINV_PROGAPP;
        $PINV_PROGAPPVAL= $rowPINVH ->PINV_PROGAPPVAL;
        $PINV_VALADD    = $rowPINVH ->PINV_VALADD;
        $PINV_VALADDPPn = $rowPINVH ->PINV_VALADDPPn;
        $PINV_MATVAL    = $rowPINVH ->PINV_MATVAL;
        $PINV_VALBEF    = $rowPINVH ->PINV_VALBEF;
        $PINV_VALBEFPPn = $rowPINVH ->PINV_VALBEFPPn;
        $PINV_AKUMNEXT  = $rowPINVH ->PINV_AKUMNEXT;
        $PINV_TOTVAL    = $rowPINVH ->PINV_TOTVAL;
        $PINV_TOTVALPPn = $rowPINVH ->PINV_TOTVALPPn;
        $PINV_TOTVALPPh = $rowPINVH ->PINV_TOTVALPPh;
        $PINV_TOTVALPPhP    = $rowPINVH ->PINV_TOTVALPPhP;
        $PINV_NOTES     = $rowPINVH ->PINV_NOTES;
        $PINV_STAT      = $rowPINVH ->PINV_STAT;
        $PATT_NUMBEP    = $rowPINVH ->PATT_NUMBER;
    endforeach;
    
    // MENCARI NILAI PEMBAYARAN SEBELUMNYA, DIAMBIL SECARA JESELURUHAN DARI MC
        $PINV_VALBEF        = 0;
        $PINV_VALBEFPPn     = 0;
        $PINV_AKUMNEXT      = 0;
        $sqlMC              = "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEP";
        //$sqlMC            = "tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER < $PATT_NUMBEP";
        $resMC              = $this->db->count_all($sqlMC);
        if($resMC > 0)
        {
            //$sqlAKN = "SELECT PINV_AKUMNEXT FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PATT_NUMBER = $PATT_NUMBEP";
            $sqlAKN = "SELECT SUM(PINV_TOTVAL) AS PINV_TOTVAL FROM tbl_projinv_header WHERE PRJCODE = '$PRJCODE' AND PINV_STEP < $PINV_STEP AND PINV_STAT IN (3,6)";
            $resAKN = $this->db->query($sqlAKN)->result();
            foreach($resAKN as $rowAKN) :
                $PINV_VALBEF        = $rowAKN ->PINV_TOTVAL;
                $PINV_VALBEFNonPPn  = round($PINV_VALBEF * 100 / 110);              // Nilai Pembayaran Sebelumnya, tanpa PPn
                $PINV_VALBEFPPn     = round($PINV_VALBEF - $PINV_VALBEFNonPPn);     // PPn Pembayaran Sebelumnya
            endforeach;
        }
}
$PINV_DATEx     = date('Y-m-d', strtotime('-1 days', strtotime($PINV_DATE)));
$PINV_STEPB     = $PINV_STEP - 1;

if($PINV_STAT == 2)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafCONFIRM.png) no-repeat center !important";
}
elseif($PINV_STAT == 9)
{
    $DrafTTD1   = "url(".base_url() . "assets/AdminLTE-2.0.5/drafStatusDoc/DrafVOID.png) no-repeat center !important";
}
else
{
    $DrafTTD1   = "white";
}

$PRJNAME = $this->db->select("PRJNAME")->get_where("tbl_project", ["PRJCODE" => $PRJCODE])->row("PRJNAME");

$PINV_CATD = "";
if($PINV_CAT == 1) $PINV_CATD = "Tagihan Uang Muka";
elseif($PINV_CAT == 2) $PINV_CATD = "Tagihan MC";
elseif($PINV_CAT == 3) $PINV_CATD = "Tagihan SI";

$GPINV_TOTVAL = $PINV_TOTVAL + $PINV_TOTVALPPn;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Print</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
    <script language="javascript" src="<?php echo base_url() . 'assets/js/allscript.js'; ?>"></script>

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
    font: 12pt Arial, Helvetica, sans-serif;
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        font-size: 12px;
        width: 21cm;
        min-height: 29.7cm;
        padding-left:1.5cm;
        padding-right:1cm;
        padding-top:1.5cm;
        padding-bottom:1cm;
        /*padding: 0.01cm 0.2cm;*/
        margin: 0.5cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: <?php echo $DrafTTD1;?>;
        background-size: 550px 300px !important;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @page {
            /*size: A4;*/
            margin: 0;
    }
    @media print {
        /*@page {size: potrait}*/
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

    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }

    .color-palette-set {
      margin-bottom: 15px;
    }

    .color-palette span {
      display: none;
      font-size: 12px;
    }

    .color-palette:hover span {
      display: block;
    }

    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }

    hr {
        display: block;
        margin-top: 0.5em;
        margin-bottom: 0.5em;
        margin-left: auto;
        margin-right: auto;
        border-style: inset;
        border-width: 1px;
    }

    .title {
        position: relative;
        width: 100%;
        /* border: 1px solid black; */
    }
    .title span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        font-size: 14pt;
        font-weight: bold;
    }

    .title div {
        float: right;
    }

    .title:after {
        content: "";
        clear: both;
        display: table;
    }

    .title div table td {
        padding: 3px;
    }

    #JHAmount table td {
        padding: 3px;
    }

    #JHNotes table td {
        padding: 3px;
    }

    #tblDetail th {
        border: 1px solid black;
        text-align: center;
        font-weight: bold;
        padding: 3px;
    }

    #tblDetail td {
        border: 1px solid black;
        padding: 3px;
    }

    #JHFooter table {
        border-collapse: separate;
        border-spacing: 2px 0;
    }
    
    #JHFooter table td {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
        padding: 5px;
        border-collapse: separate;
        border-spacing: 0 2px;
    }

    #Layer1 {
        position: absolute;
        top: 30px;
        left: 730px;
    }
</style>
</head>
<body>
    <div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table width="100%" border="0">
                        <tr>
                            <td width="100" style="text-align:left"><img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="130"></td>
                            <td style="text-align:left; vertical-align:top; padding-left:10px;">
                                <b><?php echo strtoupper($comp_name); ?></b><br>
                                <?php echo $comp_add; ?><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center"><hr></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center"><font size="+1" style="font-weight:bold; text-decoration:underline">I N V O I C E</font></td>
                        </tr>
                        <tr>
                          <td colspan="2" style="text-align:center">
                             No. : <?php echo $PINV_MANNO; ?><br>
                             Tanggal : <?php echo date('d-m-Y', strtotime($PINV_DATE)); ?>
                          </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <form method="post" name="frmSearch" action="">
            <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table width="100%" border="1" rules="all">
                            <tr>
                                <td colspan="4" style="border-top:hidden; border-bottom:hidden; border-left:hidden; border-right:hidden;">
                                    <?php echo "Kepada:<br>"; ?>
                                    <b><?php echo $own_Name; ?></b></td>
                                <td style="border-top:hidden; border-bottom:hidden; border-left:hidden; border-right:hidden;">&nbsp;</td>
                                <td style="border-top:hidden; border-bottom:hidden; border-left:hidden; border-right:hidden;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="400" colspan="4" style="border-top:hidden; border-bottom:hidden; border-left:hidden; border-right:hidden;">
                                    <?php
                                        if($own_Add1 != '')
                                        {
                                            $expl_own    = explode(',',$own_Add1);
                                            if(count($expl_own) > 1)
                                            {
                                                $arr_own1    = $expl_own[0];
                                                $arr_own2    = $expl_own[1];
                                                $arr_own3    = $expl_own[2];
                                                $arr_own4    = $expl_own[3];
                                            }
                                            else
                                            {
                                                $arr_own1    = $own_Add1;
                                                $arr_own2    = "";
                                                $arr_own3    = "";
                                                $arr_own4    = "";
                                            }
                                        }
                                        else
                                        {
                                            $arr_own1   = '';
                                            $arr_own2   = '';
                                            $arr_own3   = '';
                                            $arr_own4   = '';
                                        }

                                            echo $arr_own1.",".$arr_own2.",".$arr_own3."<br>&nbsp;&nbsp;&nbsp;".$arr_own4;
                                        ?>
                                </td>
                                <?php
                                    $sql_prjcont = $this->db->select('PRJCNUM, PRJDATE')->get_where('tbl_project', array('PRJCODE' => $PRJCODE));
                                    if($sql_prjcont->num_rows() > 0):
                                        foreach($sql_prjcont->result() as $r):
                                            $Contract_NUM   = $r->PRJCNUM;
                                            $tgl_Contract   = date('d-m-Y', strtotime($r->PRJDATE));
                                ?>
                                <td colspan="2" style="vertical-align:top;border-top:hidden; border-bottom:hidden; border-left:hidden; border-right:hidden;">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td width="90">Nomor Kontrak</td>
                                            <td>: <?=$Contract_NUM?></td>
                                        </tr>
                                        <tr>
                                            <td width="90">Tanggal Kontrak</td>
                                            <td>: <?=$tgl_Contract?></td>
                                        </tr>
                                    </table>
                                </td>
                                <?php
                                        endforeach;
                                    endif;
                                ?>
                            </tr>
                            <tr>
                                <td colspan="6" style="border-top:hidden;border-left:hidden; border-right:hidden;">&nbsp;</td>
                            </tr>
                            <?php
                                $sqlPRJNM   = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
                                $resultPRJNM= $this->db->query($sqlPRJNM)->result();
                                foreach($resultPRJNM  as $rowPRJNM) :
                                    $PRJNAME = $rowPRJNM->PRJNAME;
                                endforeach;
                            ?>
                        </table>
                        <table width="100%" border="1" rules="all" style="font-family:'Arial', Courier, monospace; font-size:14px">
                            <tr style="display:none">
                                <td colspan="6"  style="text-align:center; border-bottom: hidden;">
                                    <font size="+1" style="font-weight:bold;">PEKERJAAN STRUKTUR &amp; ARSITEKTUR</font></td>
                            </tr>
                            <tr>
                                <td colspan="6"  style="text-align:center; border-bottom: hidden; border-top: hidden;">
                                    <font size="+1" style="font-weight:bold;"><?php echo $PRJNAME; ?></font>                        </td>
                            </tr>
                            <tr>
                                <td width="1%"  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td width="8%" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td width="14%" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td width="43%" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6"  style="text-align:center;border-top: hidden; border-bottom: hidden; font-weight:bold"><font size="2">
                                    <?php
                                        if($PINV_CAT != 2)
                                        {
                                            echo "No. $PINV_MANNO";
                                        }
                                        else
                                        {
                                            echo "No. $PINV_MANNO";
                                        }
                                    ?></font>                       </td>
                            </tr>
                            <?php
                            {
                                $sqlPRJ = "SELECT PRJCOST AS proj_amountIDR FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
                                $resultPRJ = $this->db->query($sqlPRJ)->result();
                                foreach($resultPRJ  as $rowPRJ) :
                                    $proj_amountIDR = $rowPRJ->proj_amountIDR;
                                endforeach;
                                $proj_amountIDRDP   = $proj_amountIDR;
                                $sqlPINVD       = "SELECT * FROM tbl_projinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
                                $ressqlPINVD    = $this->db->query($sqlPINVD)->result();
                                foreach($ressqlPINVD as $rowPINVD) :
                                    $adend_step     = $rowPINVD ->adend_step;
                                    $adend_Code     = $rowPINVD ->adend_Code;
                            ?>
                                <tr>
                                    <td colspan="6"  style="text-align:center; border-top: hidden; border-bottom: hidden; font-weight:bold"><font size="2">
                                       <?php echo "Addendum - $adend_step No. $adend_Code"; ?></font>                           </td>
                                </tr>
                            <?php
                                endforeach;
                                $proj_amountIDR     = $proj_amountIDR;
                            }
                            ?>
                            <tr>
                                <td colspan="4"  style="text-align:right;border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="text-align:right;border-left: hidden;border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4"  style="text-align:right;border-right: hidden; border-top: hidden; border-bottom: hidden;">Nilai Pekerjaan :</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                <td style="text-align:right;border-left: hidden;border-top: hidden; border-bottom: hidden;"><?php print number_format($proj_amountIDR, 0); ?>&nbsp;</td>
                            </tr>
                            <?php
                                if($PINV_CAT == 4)
                                {
                                    $sqlPINVDC          = "tbl_projinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
                                    $ressqlPINVDC       = $this->db->count_all($sqlPINVDC);
                                    $rowNoa             = 0;

                                    $sqlPINVD           = "SELECT * FROM tbl_projinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
                                    $ressqlPINVD        = $this->db->query($sqlPINVD)->result();
                                    foreach($ressqlPINVD as $rowPINVD) :
                                        $rowNoa         = $rowNoa + 1;
                                        $adend_step     = $rowPINVD ->adend_step;
                                        $adend_Code     = $rowPINVD ->adend_Code;
                                        $adend_Value1A  = $rowPINVD ->adend_Value1A;

                                        $proj_amountIDR     = $proj_amountIDR + $adend_Value1A;
                                        ?>
                                        <tr>
                                            <td colspan="4"  style="text-align:right;border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                                Nilai Addendum - <?php echo "$adend_step"; ?> (Struktur) :                                    </td>
                                            <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                            <td style="text-align:right; border-left: hidden; border-top: hidden; <?php if($rowNoa != $ressqlPINVDC) { ?> border-bottom: hidden; <?php } ?>">
                                                <?php print number_format($adend_Value1A, 0); ?>&nbsp;                                    </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    $PPNValue   = $proj_amountIDR * $PINV_PPNPERC / 100;
                                    $TotPPNVal  = $PPNValue + $proj_amountIDR;
                                    ?>
                                    <tr style="font-weight:bold;">
                                        <td colspan="4"  style="text-align:right; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            Nilai Pekerjaan berikut Addendum  :                        </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-bottom: hidden;"><?php print number_format($proj_amountIDR, 0); ?>&nbsp;</td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td colspan="4"  style="text-align:right; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            PPN <?=number_format($PINV_PPNPERC,0);?> % : </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden;">Rp.</td>
                                        <td style="text-align:right; border-left: hidden; border-top: hidden; font-weight:bold;"><?php print number_format($PPNValue, 0); ?>&nbsp;</td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td colspan="4"  style="text-align:right; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            Nilai Akhir berikut PPN <?=number_format($PINV_PPNPERC,0);?> % : </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden;">Rp.</td>
                                        <td style="text-align:right; border-left: hidden; border-top: hidden; font-weight:bold;"><?php print number_format($TotPPNVal, 0); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                                else
                                {
                                    $sqlPINVDC          = "tbl_projinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
                                    $ressqlPINVDC       = $this->db->count_all($sqlPINVDC);
                                    $rowNoa             = 0;

                                    $sqlPINVD           = "SELECT * FROM tbl_projinv_detail WHERE proj_Code = '$PRJCODE' AND PINV_Number = '$PINV_CODE'";
                                    $ressqlPINVD        = $this->db->query($sqlPINVD)->result();
                                    foreach($ressqlPINVD as $rowPINVD) :
                                        $rowNoa         = $rowNoa + 1;
                                        $adend_step     = $rowPINVD ->adend_step;
                                        $adend_Code     = $rowPINVD ->adend_Code;
                                        $adend_Value1A  = $rowPINVD ->adend_Value1A;

                                        $proj_amountIDR     = $proj_amountIDR + $adend_Value1A;
                                        ?>
                                        <?php
                                    endforeach;
                                    $PPNValue   = $proj_amountIDR * $PINV_PPNPERC / 100;
                                    $TotPPNVal  = $PPNValue + $proj_amountIDR;
                                    ?>
                                    <tr style="font-weight:bold;">
                                        <td colspan="4"  style="text-align:right; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            PPN 10 % :                        </td>
                                        <td style="text-align:left; border-right: hidden; border-left: hidden; border-top: hidden; border-bottom: hidden; <?php if($rowNoa != $ressqlPINVDC) { ?> border-bottom: hidden; <?php } ?>">Rp.</td>
                                        <td style="text-align:right; border-left: hidden; border-top: hidden; <?php if($rowNoa != $ressqlPINVDC) { ?> border-bottom: hidden; <?php } ?>"><?php print number_format($PPNValue, 0); ?>&nbsp;</td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td colspan="4"  style="text-align:right; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            NILAI KONTRAK (TERMASUK PPN <?=number_format($PINV_PPNPERC,0);?> %) : </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-bottom: hidden;"><?php print number_format($TotPPNVal, 0); ?>&nbsp;</td>
                                    </tr>
                                <?php
                                }
                            ?>
                            <tr>
                                <td  style="border-right: hidden; border-top: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden;">&nbsp;</td>
                                <td style="border-left: hidden;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6"  style="text-align:center; border-bottom: hidden;">
                                    <font size="+1" style="font-weight:bold; text-decoration:underline">
                                        <?php
                                            $StepTerm   = KonDecRomawi($PINV_STEP);
                                            if($PINV_CAT == 1)
                                            {
                                                echo 'PEMBAYARAN UANG MUKA';
                                            }
                                            else
                                            {
                                                echo "PEMBAYARAN TERMIN $StepTerm";
                                            }
                                        ?>
                                    </font>                     </td>
                            </tr>
                            <tr>
                                <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                <td style="border-left: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                            </tr>
                            <?php
                                // START : A. NILAI PRESTASI
                                    $PINV_PROG      = $PINV_PROG;
                                    $PINV_PROGVAL   = $PINV_PROGVAL;
                                    $VAL_A          = $PINV_PROGVAL;
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="2" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">a. Nilai Prestasi</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            <?php
                                                $PINV_PROGAll   = $PINV_PROG;
                                                if($PINV_CAT == 1)
                                                {
                                                    $PINV_PROGx = '';
                                                    echo "$PINV_PROGx";
                                                }
                                                else
                                                {
                                                    $PINV_PROGx = number_format($PINV_PROG, 4);
                                                    echo "$PINV_PROGx %";
                                                }
                                            ?>
                                        </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden; border-bottom: hidden;">
                                            <?php
                                                if($PINV_CAT == 1)
                                                {
                                                    echo '-';
                                                }
                                                else
                                                {
                                                    echo number_format($VAL_A, 0);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : A. NILAI PRESTASI

                                // START : B. NILAI PEKERJAAN TAMBAH KURANG
                                    $PINV_VALADDX   = number_format($PINV_VALADD, 0);
                                    $VAL_B          = $PINV_VALADD;
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            b. Nilai Pekerjaan Tambahan                                </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right; border-left: hidden; border-top: hidden;"><?php echo $VAL_B; ?></td>
                                    </tr>
                                    <?php
                                // END : B. NILAI PEKERJAAN TAMBAH KURANG

                                // START : C. NILAI TERMASUK PEKERJAAN TAMBAH KURANG
                                    $TOTPROGRESS    = $PINV_PROGVAL + $PINV_VALADD;
                                    $VAL_C          = $VAL_A + $VAL_B;
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">c. Nilai termasuk Pekerjaan Tambah</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-bottom: hidden;">
                                            <?php
                                                if($PINV_CAT == 1)
                                                {
                                                    $VAL_C  = 0;
                                                    echo "-";
                                                }
                                                else
                                                {
                                                    echo number_format($VAL_C, 0);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : C. NILAI TERMASUK PEKERJAAN TAMBAH KURANG

                                // START : D. NILAI RETENSI
                                    $VAL_D          = $VAL_A + $VAL_B;
                                    if($PINV_CAT == 1)
                                    {
                                        $VAL_D      = 0;
                                        $VAL_DV     = '-';
                                    }
                                    else
                                    { 
                                        $VAL_D      = $PINV_RETCUT;
                                        $VAL_DV     = number_format($VAL_D, 0);
                                        $VAL_DV     = "($VAL_DV)";
                                    }
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">d. Nilai Retensi  (5% x c)</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden; border-bottom: hidden;">
                                            <?php
                                                echo "$VAL_DV";
                                            ?>
                                            <input type="hidden" name="RetentionAm" id="RetentionAm" value="<?php echo $VAL_DV; ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                // END : D. NILAI RETENSI

                                // START : E. NILAI UANG MUKA
                                    $VAL_E  = $PINV_DPVAL;
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">e. Nilai Uang Muka <?php echo $PINV_DPPER; ?>%</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden; border-bottom: hidden;">
                                            <?php
                                                $VAL_EV = number_format($VAL_E, 0);
                                                echo $VAL_EV;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : E. NILAI UANG MUKA

                                // START : F. NILAI POTONGAN UANG MUKA
                                    $VAL_F    = $PINV_DPBACK;
                                    //$VAL_F      = $PINV_DPBACKCUR;
                                    $VAL_FV     = number_format($VAL_F, 0);
                                    if($PINV_CAT == 1)
                                    {
                                        $VAL_FV  = "-";
                                    }
                                    else
                                    {
                                        $VAL_FV  = "($VAL_FV)";
                                    }
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;"> f. Nilai Pemotongan Uang Muka (E x A)</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden;">
                                            <?php
                                                echo "$VAL_FV";
                                            ?>
                                            <input type="hidden" name="CuttingDPAm" id="CuttingDPAm" value="<?php echo $VAL_FV; ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                // END : F. NILAI POTONGAN UANG MUKA

                                // START : G. NILAI PRESTASI SETELAH DIKURANGI RETENSI DAN UANG MUKA
                                    $VAL_G      = $VAL_A- $VAL_D + $VAL_E - $VAL_F;
                                    $VAL_GV     = number_format($VAL_G, 0);
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">g. Nilai prestasi setelah dikurangi Retensi &amp; Uang Muka </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-bottom: hidden;">
                                            <?php
                                                echo "$VAL_GV";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : G. NILAI PRESTASI SETELAH DIKURANGI RETENSI DAN UANG MUKA

                                // START : H. NILAI PEMBAYARAN SEBELUMNYA
                                    $VAL_H      = $PINV_VALBEF;
                                    if($PINV_CAT == 1)
                                    {
                                        $VAL_HV  = "-";
                                    }
                                    else
                                    {
                                        $VAL_HV  = number_format($VAL_H, 0);
                                        $VAL_HV  = "($VAL_HV)";
                                    }
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">h. Nilai Pembayaran sebelumnya</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden;">
                                            <?php

                                                echo "$VAL_HV";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : H. NILAI PEMBAYARAN SEBELUMNYA

                                // START : I. NILAI PRESTASI SAAT INI
                                    $VAL_I      = $VAL_G - $VAL_H;
                                    $VAL_IV     = number_format($VAL_I, 0);
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;"> i.  Nilai prestasi saat ini  (g - h)</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-bottom: hidden;">
                                            <?php
                                                echo "$VAL_IV";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : I. NILAI PRESTASI SAAT INI

                                // START : J. NILAI PPN
                                    $VAL_J      = $PINV_PPNPERC * $VAL_I / 100;
                                    $VAL_JV     = number_format($VAL_J, 0);
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">j.  Nilai PPN (<?=number_format($PINV_PPNPERC,0);?> % x i)</td>
                                        <td style="text-align:left; border-left: hidden; border-top: hidden; border-right: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden;">
                                            <?php
                                                echo "$VAL_JV";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : J. NILAI PPN

                                // START : K. NILAI YANG KAMI AJUKAN
                                    $VAL_K      = $VAL_I + $VAL_J;
                                    $VAL_KV     = number_format($VAL_K, 0);
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">k. Nilai yang kami ajukan (i + j)</td>
                                        <td style="text-align:left; border-left: hidden; border-top: hidden; border-right: hidden; font-weight:bold;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; font-weight:bold;">
                                            <?php
                                                echo "$VAL_KV";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : K. NILAI YANG KAMI AJUKAN

                                // START : POTONGAN PPH
                                    $VAL_PPH       = $PINV_TOTVALPPh;
                                    $VAL_PPHV      = number_format($VAL_PPH, 0);
                                    ?>
                                    <tr style="font-weight:bold;">
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="text-align:right;border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">
                                            Potongan PPh (<?php echo number_format($PINV_TOTVALPPhP,2);?>% x i) =                        </td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden;">Rp.</td>
                                        <td style="text-align:right;border-left: hidden; border-top: hidden;">
                                            <?php
                                                echo "($VAL_PPHV)";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : POTONGAN PPH

                                // START : NILAI YANG DITERIMA
                                    $VAL_FINAL      = $VAL_K - $VAL_PPH;
                                    $VAL_FINALV     = number_format($VAL_FINAL, 0);
                                    ?>
                                    <tr style="font-weight:bold;">
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td colspan="3" style="text-align:right;border-left: hidden; border-top: hidden; border-bottom: hidden;">
                                            NILAI YANG DITERIMA =                        </td>
                                        <td style="border-right: hidden; border-left: hidden; border-top: hidden;">Rp.</td>
                                        <td style="text-align:right; border-bottom: hidden;">
                                            <?php
                                                echo "$VAL_FINALV";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                // END : NILAI YANG DITERIMA

                                // START : NILAI YANG DITERIMA
                                    $VAL_FINAL      = abs($VAL_K - $VAL_PPH);
                                    $VAL_FINALV     = number_format($VAL_FINAL, 0);
                                    ?>
                                    <tr>
                                        <td  style="border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom: hidden;">&nbsp;</td>
                                        <td style="border-left: hidden; border-bottom: hidden;">&nbsp;</td>
                                    </tr>
                                    <?php
                                // END : NILAI YANG DITERIMA

                                $terbilang = $moneyFormat->terbilang($VAL_FINAL);
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="5" style="border-left: hidden; border-top: hidden; font-weight:bold">TERBILANG : "<?php echo $terbilang; ?> Rupiah"<br><br></td>
                            </tr>
                            <tr style="display: none;">
                                <td  style="border-left: hidden; border-right: hidden; border-top: hidden; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="5" style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                <table border="0" width="100%">
                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Pembayaran ini dapat dilakukan melalui transfer ke rekening :</td>
                                    </tr>
                                    <tr>
                                        <td width="17%">Atas Nama</td>
                                        <td width="1%">:</td>
                                        <td width="82%"><?php echo strtoupper($comp_name); ?></td>
                                    </tr>
                                    <tr>
                                        <td>No. Rek</td>
                                        <td>:</td>
                                        <td>142.00.1668630.4</td>
                                    </tr>
                                    <tr>
                                        <td>Bank</td>
                                        <td>:</td>
                                        <td>Mandiri</td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                            <?php
                                $DateY  = date('Y');
                                $DateM  = date('m');
                                $DateD  = date('d');
                                //$Date     = "$DateY-$DateM-$DateD";
                                $date   = new DateTime($PINV_DATE);

                                if($PINV_STAT == 2){
                                    $DrafTTD   = "DrafCONFIRM.png";
                                    $showImg   = 1;
                                }elseif($PINV_STAT == 3){
                                    $DrafTTD   = "DrafApproved.png";
                                    $showImg  = 0;
                                }elseif ($PINV_STAT == 4) {
                                    $DrafTTD   = "DrafRevised.png";
                                    $showImg  = 0;
                                }elseif ($PINV_STAT == 5) {
                                    $DrafTTD   = "DrafRejected.png";
                                    $showImg  = 0;
                                }elseif ($PINV_STAT == 6) {
                                    $DrafTTD   = "DrafClosed.png";
                                    $showImg  = 0;
                                }elseif ($PINV_STAT == 7) {
                                    $DrafTTD   = "DrafAWAITING.png";
                                    $showImg  = 0;
                                }elseif ($PINV_STAT == 9) {
                                    $DrafTTD   = "DrafVOID.png";
                                    $showImg   = 1;
                                }
                            ?>
                            <tr>
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                    <br><br>Jakarta, <?php echo $date->format('d F Y'); ?>
                                </td>
                            </tr>
                            <tr style="font-weight:bold">
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                              <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;"><?php echo strtoupper($comp_name); ?></td>
                            </tr>
                            <tr>
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;font-weight:bold;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;font-weight:bold;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                                <td colspan="3" style="text-align:center;font-weight:bold;border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">
                                <img style="<?php if($showImg == 1){?>display: none; <?php } ?> width: 120px; position: absolute; margin-top: -65px; margin-left: -20px;" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/drafStatusDoc/'.$DrafTTD; ?>" />
                                Direktur
                                </td>
                            </tr>
                            <tr>
                              <td  style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                              <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                              <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                              <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                              <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                              <td style="border-left-color:#FFFFFF; border-right-color:#FFFFFF; border-top-color:#FFFFFF; border-bottom-color:#FFFFFF;">&nbsp;</td>
                            </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
            </table>
        </form>
        <script>
            function doDecimalFormat(angka) {
                var a, b, c, dec, i, j;
                angka = String(angka);
                if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
                } else { a = angka; dec = -1; }
                b = a.replace(/[^\d]/g,"");
                c = "";
                var panjang = b.length;
                j = 0;
                for (i = panjang; i > 0; i--) {
                    j = j + 1;
                    if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
                    else c = b.substr(i-1,1) + c;
                }
                if(dec == -1) return angka;
                else return (c + '.' + dec);
            }

            function RoundNDecimal(X, N) {
                var T, S=new String(Math.round(X*Number("1e"+N)))
                while (S.length<=N) S='0'+S
                return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
            }
        </script>
    </div>
    <?php if($PINV_STAT == 3): ?>
    <div class="page">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="130">
                </td>
            </tr>
        </table>
        <div class="title">
            <span>TRANSAKSI MEMORIAL</span>
            <div style="border: 1px black; width: 170px;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid black;">
                    <tr>
                        <td width="50">Nomor</td>
                        <td width="10">:</td>
                        <td><?=$PINV_MANNO?></td>
                    </tr>
                    <tr>
                        <td width="50">Tanggal</td>
                        <td width="10">:</td>
                        <td><?php echo date('d-m-Y', strtotime($PINV_DATE)); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="JHAmount" style="padding-top: 5px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid black;">
                <tr>
                    <td width="100">Jumlah</td>
                    <td width="10">:</td>
                    <td><?php echo "Rp. ".number_format($GPINV_TOTVAL, 2); ?></td>
                </tr>
                <tr>
                    <td width="100" style="vertical-align: top;">Terbilang</td>
                    <td width="10" style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;"><?php echo $moneyFormat->terbilang($GPINV_TOTVAL); ?></td>
                </tr>
            </table>
        </div>
        <div id="JHNotes" style="padding-top: 5px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid black;">
                <tr>
                    <td width="100">Keterangan</td>
                    <td>:</td>
                    <td>
                        <?php 
                            echo "$PINV_CATD Proyek $PRJNAME - $PRJCODE";
                            echo "<div style='font-style: italic;'>$PINV_NOTES</div>"; 
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div id="JHDetail" style="padding-top: 5px;">
            <table id="tblDetail" width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="100">No. Account</th>
                        <th>Keterangan</th>
                        <th width="150">Debet</th>
                        <th width="150">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // GET Journal Faktur Proyek
                            $getJFPry   = "SELECT JournalH_Code, JournalH_Date, JournalType, Acc_Id, Acc_Name, 
                                            proj_Code, JournalD_Debet, JournalD_Kredit, PPN_Code, PPN_Amount, 
                                            PPH_Code, PPH_Amount, Manual_No, TAX_NO, TAX_DATE, Kwitansi_No, 
                                            Kwitansi_Date, SPLCODE, SPLDESC, Other_Desc, Revise_Desc, 
                                            Journal_DK, oth_reason
                                            FROM tbl_journaldetail
                                            WHERE JournalH_Code = '$PINV_CODE' AND proj_Code = '$PRJCODE'
                                            ORDER BY Journal_DK ASC";
                            $resJFPry   = $this->db->query($getJFPry);
                            if($resJFPry->num_rows() > 0)
                            {
                                $TJournalD  = 0;
                                $TJournalK  = 0;
                                foreach($resJFPry->result() as $rJFPry):
                                    $JournalH_Code      = $rJFPry->JournalH_Code;
                                    $JournalH_Date      = $rJFPry->JournalH_Date;
                                    $JournalType        = $rJFPry->JournalType;
                                    $Acc_Id             = $rJFPry->Acc_Id;
                                    $Acc_Name           = $rJFPry->Acc_Name;
                                    $proj_Code          = $rJFPry->proj_Code;
                                    $JournalD_Debet     = $rJFPry->JournalD_Debet;
                                    $JournalD_Kredit    = $rJFPry->JournalD_Kredit;
                                    $PPN_Code           = $rJFPry->PPN_Code;
                                    $PPN_Amount         = $rJFPry->PPN_Amount;
                                    $PPH_Code           = $rJFPry->PPH_Code;
                                    $PPH_Amount         = $rJFPry->PPH_Amount;
                                    $Manual_No          = $rJFPry->Manual_No;
                                    $TAX_NO             = $rJFPry->TAX_NO;
                                    $TAX_DATE           = $rJFPry->TAX_DATE;
                                    $Kwitansi_No        = $rJFPry->Kwitansi_No;
                                    $Kwitansi_Date      = $rJFPry->Kwitansi_Date;
                                    $SPLCODE            = $rJFPry->SPLCODE;
                                    $SPLDESC            = $rJFPry->SPLDESC;
                                    $Other_Desc         = $rJFPry->Other_Desc;
                                    $Revise_Desc        = $rJFPry->Revise_Desc;
                                    $Journal_DK         = $rJFPry->Journal_DK;
                                    $oth_reason         = $rJFPry->oth_reason;
    
                                    $TJournalD = $TJournalD + $JournalD_Debet;
                                    $TJournalK = $TJournalK + $JournalD_Kredit;
    
                                    ?>
                                        <tr>
                                            <td><?=$Acc_Id?></td>
                                            <td>
                                                <span><?=$Acc_Name?></span>
                                                <div><?=$Other_Desc?></div>
                                            </td>
                                            <td style="text-align: right;"><?php echo number_format($JournalD_Debet, 2) ?></td>
                                            <td style="text-align: right;"><?php echo number_format($JournalD_Kredit, 2) ?></td>
                                        </tr>
                                    <?php
                                endforeach;
                            }
                    ?>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold;">JUMLAH</td>
                        <td style="text-align: right;"><?php echo number_format($TJournalD, 2) ?></td>
                        <td style="text-align: right;"><?php echo number_format($TJournalK, 2) ?></td>
                    </tr>
                </tbody>
                
            </table>
        </div>
        <div id="JHFooter" style="padding-top: 5px; margin: -2px">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>Dibuat Oleh</td>
                    <td>Diperiksa Oleh</td>
                    <td>Disetujui Oleh</td>
                    <td>Diterima Oleh</td>
                </tr>
                <tr height="50">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>