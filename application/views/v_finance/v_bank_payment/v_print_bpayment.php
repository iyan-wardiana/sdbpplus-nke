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

$DefEmp_ID  = $this->session->userdata['Emp_ID'];

$LangID     = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
    $h1_title   = 'Bukti Pengeluaran Kas / Bank';
}
else
{
    $dh1_title  = 'Bukti Pengeluaran Kas / Bank';
}

// get SPLCODE
    if($CB_SOURCE == 'PD' || $CB_SOURCE == 'PPD')
    {
        $CB_PAYFOR  = '';
        $get_SPL    = "SELECT SPLCODE, PERSL_EMPID FROM tbl_journalheader_pd 
                        WHERE JournalH_Code = (SELECT CBD_DOCNO FROM tbl_bp_detail 
                                                WHERE CB_NUM = '$CB_NUM' AND PRJCODE = '$PRJCODE' LIMIT 1)
                        AND proj_Code = '$PRJCODE'";
        $res_SPL    = $this->db->query($get_SPL);
        if($res_SPL->num_rows() > 0)
        {
            foreach($res_SPL->result() as $rSPL):
                $SPLCODE        = $rSPL->SPLCODE;
                $PERSL_EMPID    = $rSPL->PERSL_EMPID;
            endforeach;
            
            if($SPLCODE == '') $CB_PAYFOR = $PERSL_EMPID;
            else $CB_PAYFOR = $SPLCODE;
        }
    }

$PRJCODEVW      = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
$EMP_NAME       = "";
$REK            = "";
$s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Bank_Account AS NO_REK, Bank_Name AS NM_REK, Bank_Branch AS NM_BANK FROM tbl_employee WHERE Emp_ID = '$CB_PAYFOR'
                    UNION
                    SELECT SPLDESC AS EMP_NAME, SPLNOREK AS NO_REK, SPLNMREK AS NM_REK, SPLBANK AS NM_BANK FROM tbl_supplier WHERE SPLCODE = '$CB_PAYFOR'";
$r_emp          = $this->db->query($s_emp);
if($r_emp->num_rows() > 0)
{
    foreach($r_emp->result() as $rw_emp) :
        $EMP_NAME   = $rw_emp->EMP_NAME;
        $NO_REK     = $rw_emp->NO_REK;
        $NM_REK     = $rw_emp->NM_REK;
        $NM_BANK    = $rw_emp->NM_BANK;
        if($NO_REK != '')
            $REK        = "$NO_REK ($NM_BANK) / an. $NM_REK";
    endforeach;
}

// get Journal Header
$this->db->select("A.CBD_DOCNO AS VOC_NUM");
$this->db->from("tbl_bp_detail A");
$this->db->join("tbl_bp_header B", "B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE");
$this->db->where(["A.CB_NUM" => $CB_NUM, "A.PRJCODE" => $PRJCODE]);
$this->db->where_in("B.CB_STAT", [2,3]);
$getDOCNO = $this->db->get();
if($getDOCNO->num_rows() > 0)
{
    foreach($getDOCNO->result() as $rDoc):
        $VOC_NUM[]  = $rDoc->VOC_NUM;
    endforeach;
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
        .sheet.custom { padding: 0.5cm 1cm 0.97cm 1cm }

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
            font-size: 10pt;
            /*border-bottom: 1px dashed;*/
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
        .box-header-detail {
            width: 100%;
            margin-top: 15px;
            /*background-color: gold;*/
        }
        .box-header-detail table td {
            /*background-color: gold;*/
            padding: 3px;
            border: hidden;
        }
        .box-header .pageno {
            float: right;
            width: 200px;
            margin-top: 0px;
            /*background-color: yellow;*/
        }
        .box-header .pageno table td {
            border: hidden;
        }
        .box-detail table thead th, tbody td, tfoot td {
            padding: 1px;
            border: 1px solid;
        }
        .box-detail table thead th {
            text-align: center;
        }
        .box-detail table tfoot td > span:first-child {
            display: inline-block;
            width: 70px;
            font-weight: bold;
            vertical-align: top;
            /*background-color: red;*/
        }
        .box-detail table tfoot td > span:last-child {
            display: inline-block;
            width: 430px;
            /*background-color: gold;*/
        }
        .box-asign-1 {
            margin-top: 25px;
        }
        .box-asign-1 table td {
            text-align: center;
            width: 25%;
        }
        .col-7 {
            float: left;
            width: 515px;
            /*background-color: gold;*/
        }
        .col-5 {
            float: left;
            width: 200px;
            /*background-color: gold;*/
        }
        ul.unchecked {
            padding-left: 0;
            margin-bottom: 0;
        }
        ul.unchecked > li {
            list-style: none;
        }
        ul.unchecked > li::before {
            content:"\e157";
            font-family: 'Glyphicons Halflings';
            padding-right: 5px;
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
                    <span><u><?php echo $h1_title; ?></u><br>BPK</span>
                </div>
                <div class="pageno">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="100">No.</td>
                            <td width="5">:</td>
                            <td>&nbsp;<?php echo $CB_CODE; ?></td>
                        </tr>
                        <tr>
                            <td width="100">Kode Proyek</td>
                            <td width="5">:</td>
                            <td>&nbsp;<?php echo $PRJCODE; ?></td>
                        </tr>
                        <tr>
                            <td width="100">Tgl. Jurnal</td>
                            <td width="5">:</td>
                            <td>&nbsp;<?php echo date('d-m-Y', strtotime($CB_DATE)); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-header-detail col-7" style="padding-top: 60px;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr style="vertical-align: top;">
                        <td width="100">Bayar Kepada</td>
                        <td width="5">:</td>
                        <td><?php echo "$CB_PAYFOR - $EMP_NAME"; ?></td>
                    </tr>
                    <tr>
                        <td width="100">No. Rekening</td>
                        <td width="5">:</td>
                        <td><?php echo "$REK"; ?></td>
                    </tr>
                </table>
            </div>
            <div class="box-header-detail col-5">
                <span>Dibayar Dengan :</span>
                <span>
                    <ul class="unchecked">
                        <li>Tunai</li>
                        <li>Cek / GB</li>
                    </ul>
                </span>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="30">No.</td>
                        <td width="5">:</td>
                        <td>.........................................</td>
                    </tr>
                    <tr>
                        <td width="30">Bank</td>
                        <td width="5">:</td>
                        <td>.........................................</td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;" width="50">No.</th>
                            <th rowspan="2" style="vertical-align: middle;">Keterangan</th>
                            <th rowspan="2" style="vertical-align: middle;" width="200">Debet</th>
                            <th colspan="2" style="vertical-align: middle;" width="200">Kredit</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;" width="200">DPP+PPN</th>
                            <th rowspan="2" style="vertical-align: middle;" width="200">PPH</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // get Voucher Detail
                            /*$this->db->select("A.JournalH_Code, A.JournalType, A.JournalH_Desc, A.JournalH_Desc2, A.JournalH_Date, 
                                                A.JournalH_Date_PD, A.PlanRDate, A.Reference_Number, A.Reference_Type, 
                                                A.ISPERSL, A.PERSL_EMPID, A.Journal_Amount, A.PPNH_Amount, A.PPHH_Amount, 
                                                A.GJournal_Total, A.Manual_No, A.SPLCODE, A.GEJ_STAT, 
                                                B.Manual_No, B.Faktur_No, B.Other_Desc, B.Acc_Id, B.Acc_Name,
                                                B.Base_Debet, B.Base_Kredit, B.Journal_DK, B.Faktur_No, B.Faktur_Code");
                            $this->db->from("tbl_journalheader A");
                            $this->db->join("tbl_journaldetail B", "B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code", "INNER");
                            $this->db->where(["B.JournalH_Code" => $CB_NUM, "A.GEJ_STAT" => 3]);
                            $this->db->order_by("B.Faktur_No, B.Base_Debet, B.Base_Kredit", "DESC");
                            // $this->db->where(["A.proj_Code" => $PRJCODE, "B.Journal_DK" => "D"]);
                            // $this->db->where_in("A.GEJ_STAT", [2,3]);
                            // $this->db->where_in("B.Faktur_No", $VOC_NUM);
                            $getVoucher = $this->db->get();*/

                            $s_00       = "SELECT DISTINCT A.JournalH_Code, A.JournalType, A.JournalH_Desc, A.JournalH_Desc2, A.JournalH_Date, 
                                                A.JournalH_Date_PD, A.PlanRDate, A.Reference_Number, A.Reference_Type,
                                                A.ISPERSL, A.PERSL_EMPID, A.Journal_Amount, A.PPNH_Amount, A.PPHH_Amount, 
                                                A.GJournal_Total, A.Manual_No, A.SPLCODE, A.GEJ_STAT, 
                                                B.Manual_No, B.Faktur_No, B.Other_Desc, B.Acc_Id, B.Acc_Name,
                                                B.Base_Debet, B.Base_Kredit, B.Journal_DK, B.Faktur_No, B.Faktur_Code, B.isTax
                                            FROM tbl_journalheader A INNER JOIN tbl_journaldetail B ON A.JournalH_Code = B.JournalH_Code AND A.Proj_Code = B.proj_Code
                                            WHERE B.JournalH_Code = '$CB_NUM' AND A.GEJ_STAT = 3 ORDER BY B.JournalD_Id, B.Faktur_No, B.Base_Debet, B.Base_Kredit ASC";
                            $r_00       = $this->db->query($s_00)->result();
                            /*if($getVoucher->num_rows() > 0)
                            {*/
                                $no = 0;
                                $GtotalAmount   = 0;
                                $TBase_Debet    = 0;
                                $TBase_Kredit   = 0;
                                $TBase_Kredit1  = 0;
                                $TBase_Kredit2  = 0;
                                foreach($r_00 as $rVOC):
                                    $no             = $no + 1;
                                    $VOC_NUM        = $rVOC->JournalH_Code;
                                    $VOC_CODE       = $rVOC->Manual_No;
                                    $VOC_DESC       = $rVOC->JournalH_Desc;
                                    $JournalType    = $rVOC->JournalType;
                                    $ISPERSL        = $rVOC->ISPERSL;
                                    $PERSL_EMPID    = $rVOC->PERSL_EMPID;
                                    $Journal_Amount = $rVOC->Journal_Amount;
                                    $PPNH_Amount    = $rVOC->PPNH_Amount;
                                    $PPHH_Amount    = $rVOC->PPHH_Amount;
                                    // $GJournal_Total = $rVOC->GJournal_Total;
                                    $SPLCODE        = $rVOC->SPLCODE;
                                    $GEJ_STAT       = $rVOC->GEJ_STAT;
                                    $Manual_No      = $rVOC->Manual_No;
                                    $Faktur_No      = $rVOC->Faktur_No;
                                    $Other_Desc     = $rVOC->Other_Desc;
                                    $Journal_DK     = $rVOC->Journal_DK;
                                    $Base_Debet     = $rVOC->Base_Debet;
                                    $Base_Kredit    = $rVOC->Base_Kredit;
                                    $Acc_Id         = $rVOC->Acc_Id;
                                    $Acc_Name       = $rVOC->Acc_Name;
                                    $Faktur_No      = $rVOC->Faktur_No;
                                    $Faktur_Code    = $rVOC->Faktur_Code;
                                    $isTax          = $rVOC->isTax;

                                    $TBase_Debet    = $TBase_Debet + $Base_Debet;
                                    $TBase_Kredit   = $TBase_Kredit + $Base_Kredit;
                                    $isPPhFin       = 0;
                                    $s_ACCPPH       = "SELECT isPPhFinal FROM tbl_chartaccount_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND Account_Number = '$Acc_Id' LIMIT 1";
                                    $r_ACCPPH       = $this->db->query($s_ACCPPH)->result();
                                    foreach($r_ACCPPH as $rw_ACCPPH):
                                        $isPPhFin   = $rw_ACCPPH->isPPhFinal;

                                        if($CB_SOURCE == 'DP' && $isTax == 1)
                                            $isPPhFin   = 1;
                                    endforeach;
                                    if($JournalType == 'BP' && $PPHH_Amount > 0)
                                        $isPPhFin   = 1;
                                    
                                    // get Voucher_cash
                                    // $VOUCHER_CODE = $this->db->get_where("tbl_journalheader_vcash", ["JournalH_Code" => $Faktur_No, "GEJ_STAT" => 3])->row("Manual_No");
                                    // $VOUCHER_DESC = $this->db->get_where("tbl_journalheader_vcash", ["JournalH_Code" => $Faktur_No, "GEJ_STAT" => 3])->row("JournalH_Desc");


                                    // $GtotalAmount   = $TBase_Debet;
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no; ?></td>
                                            <td>
                                                <?php
                                                    if($Journal_DK == 'D')
                                                    {
                                                        ?>
                                                            <div style="padding-left: 5px;">Voucher No. <b><?=$Faktur_Code?></b></div>
                                                            <div style="padding-left: 5px; font-style: italic;"><?=$Other_Desc?></div>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        $PayDesc = "Pembayaran ke $EMP_NAME";
                                                        ?>
                                                            <div style="padding-left: 5px;"><?=$PayDesc?></div>
                                                            <div style="padding-left: 5px; font-style: italic;">
                                                                <?=$Acc_Name?>
                                                                <div>
                                                                    <?php
                                                                        if($Other_Desc != '') echo wordwrap($Other_Desc, 50, "<br>", true);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                            <td style="padding-right: 5px; text-align: right;">
                                                <?php
                                                    if($isPPhFin == 0 && $Base_Debet != 0)
                                                        echo number_format($Base_Debet, 2);
                                                    elseif($isPPhFin == 1 && $JournalType == 'BP')
                                                        echo number_format($Base_Debet, 2);
                                                    else
                                                        echo "-";
                                                ?>
                                            </td>
                                            <td style="padding-right: 5px; text-align: right;">
                                                <?php
                                                    if($isPPhFin == 0 && $Base_Kredit != 0)
                                                    {
                                                        $TBase_Kredit1    = $TBase_Kredit1 + $Base_Kredit;
                                                        echo number_format($Base_Kredit, 2);
                                                    }
                                                    else
                                                        echo "-";
                                                ?>
                                            </td>
                                            <td style="padding-right: 5px; text-align: right;">
                                                <?php
                                                    if($isPPhFin == 1 && $Base_Kredit != 0)
                                                    {
                                                        $TBase_Kredit2    = $TBase_Kredit2 + $Base_Kredit;
                                                        echo number_format($Base_Kredit, 2);
                                                    }
                                                    else
                                                        echo "-";
                                                ?>
                                        </tr>
                                    <?php
                                endforeach;
                                
                                // if($no <= 10)
                                // {
                                //     $amRow = 10 - $no;
                                //     for($i=0;$i<$amRow;$i++)
                                //     {
                                //         ?>
                                <!-- //             <tr class="blank-line">
                                //                 <td>&nbsp;</td>
                                //                 <td>
                                //                     <div style="padding-left: 5px;">&nbsp;</div>
                                //                     <div style="padding-left: 5px; font-style: italic;">&nbsp;</div>
                                //                 </td>
                                //                 <td>&nbsp;</td>
                                //                 <td>&nbsp;</td>
                                //                 <td>&nbsp;</td>
                                //             </tr> -->
                                //         <?php
                                //     }
                                // }
                            //}
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" rowspan="2">
                                <span>Terbilang :</span>
                                <span><?php echo $moneyFormat->terbilang(abs($TBase_Kredit1)); ?></span>
                            </td>
                            <td>&nbsp;</td>
                            <td style="padding-right: 5px; text-align: right; font-weight: bold;"><?php echo number_format($TBase_Kredit1, 2); ?></td>
                            <td style="padding-right: 5px; text-align: right; font-weight: bold;"><?php echo number_format($TBase_Kredit2, 2); ?></td>
                        </tr>
                        <tr>
                            <td style="padding-right: 5px; text-align: right; font-weight: bold;"><?php echo number_format($TBase_Debet, 2); ?></td>
                            <td colspan="2" style="padding-right: 5px; text-align: center; font-weight: bold;"><?php echo number_format($TBase_Kredit, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-asign-1">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>Dibuat Oleh :</td>
                        <td>Diperiksa Oleh :</td>
                        <td>Disetujui Oleh :</td>
                        <td>Diterima Oleh :</td>
                    </tr>
                    <tr style="height: 100px">
                        <td style="padding-top: 50px;">(......................................)</td>
                        <td style="padding-top: 50px;">(......................................)</td>
                        <td style="padding-top: 50px;">(......................................)</td>
                        <td style="padding-top: 50px;">(......................................)</td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
</body>
</html>