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

// $EMPNAME = $this->db->select("CONCAT(First_Name,' ',Last_Name) AS FullName", false)->from("tbl_employee")->where(["Emp_ID" => $PERSL_EMPID, "Employee_status" => 1])->get()->row("FullName");

    // get supplier
    $EMP_NAME       = "";
    $s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME, Emp_ID AS PERSL_EMPID FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
                        UNION
                        SELECT SPLDESC AS EMP_NAME, SPLCODE AS PERSL_EMPID FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
    $r_emp          = $this->db->query($s_emp)->result();
    foreach($r_emp as $rw_emp) :
        $PERSL_EMPID    = $rw_emp->PERSL_EMPID;
        $EMP_NAME       = $rw_emp->EMP_NAME;
    endforeach;

    $TAXNO = '';
    $this->db->select("Ref_Number");
    $this->db->where(["JournalH_Code" => $JournalH_Code, "Ref_Number !=" => "", "Journal_DK" => 'D']);
    $this->db->where_in("GEJ_STAT", [2,3,4]);
    $getTAXNO = $this->db->get("tbl_journaldetail_cprj");
    if($getTAXNO->num_rows() > 0)
    {
        $row = 0;
        foreach($getTAXNO->result() as $rTAX):
            $row    = $row + 1;
            $TAXNO1 = $rTAX->Ref_Number;

            if($row == 1)
            {
                $TAXNO = $TAXNO1;
            }
            else
            {
                $TAXNO = "$TAXNO1, $TAXNO";
            }
        endforeach;
    }

    $Amount         = $Journal_Amount;
    $TAmount        = $Journal_Amount + $PPNH_Amount - $PPHH_Amount;
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
                    <span>PAYMENT VOUCHER</span>
                    <span style="color: red !important; display: block;">LUAR KOTA</span>
                </div>
            </div>
            <div class="box-header-detail">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="70">NO.VOC</td>
                        <td width="5">:</td>
                        <td width="450"><?php echo $Manual_No; ?></td>
                        <td width="70">PROYEK.</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $PRJCODE; ?></td>
                    </tr>
                    <tr>
                        <td width="70">DATE</td>
                        <td width="5">:</td>
                        <td width="450">&nbsp;<?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
                        <td width="70">TTK NO.</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php //echo $Manual_No; ?></td>
                    </tr>
                    <tr>
                        <td width="70">PAY TO</td>
                        <td width="5">:</td>
                        <td width="450">&nbsp;<?php echo "$PERSL_EMPID - $EMP_NAME"; ?></td>
                        <td width="70">PO NO.</td>
                        <td width="5">:</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="box-detail">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th rowspan="3" width="100">COA</th>
                            <th rowspan="3" width="350">DESCRIPTION</th>
                            <th colspan="3">AMOUNT (RP)</th>
                        </tr>
                        <tr>
                            <th rowspan="2" width="100">DEBET</th>
                            <th colspan="2">KREDIT</th>
                        </tr>
                        <tr>
                            <th width="100">DPP+PPN</th>
                            <th width="100">PPH</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $getJCPRJ   = "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.Acc_Id, 
                                            A.Acc_Id_Cross, A.Acc_Name, A.proj_Code, A.JOBCODEID, A.isTax,
                                            SUM(A.JournalD_Debet) AS TOT_DEBET, 
                                            SUM(A.JournalD_Kredit) AS TOT_KREDIT, A.PPN_Code, A.PPN_Perc, 
                                            SUM(A.PPN_Amount) AS TOT_PPN, A.PPH_Code, A.PPH_Perc, 
                                            SUM(A.PPH_Amount) AS TOT_PPH, A.Manual_No, A.Other_Desc, 
                                            A.Journal_DK, A.GEJ_STAT, B.JournalH_Desc
                                            FROM tbl_journaldetail A
                                            INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
                                            AND B.proj_Code = A.proj_Code
                                            WHERE A.JournalH_Code = '$JournalH_Code' AND A.proj_Code = '$PRJCODE'
                                            AND A.JournalType = 'CPRJ' AND A.GEJ_STAT = 3
                                            GROUP BY A.Acc_Id ORDER BY A.JournalD_Id ASC";
                            $resJCPRJ   = $this->db->query($getJCPRJ);
                            if($resJCPRJ->num_rows() > 0)
                            {
                                $TOT_DEBET  = 0;
                                $TOT_KREDIT = 0;
                                $row        = 0;
                                foreach($resJCPRJ->result() as $rJCPRJ):
                                    $row                = $row + 1;
                                    $JournalH_Code      = $rJCPRJ->JournalH_Code;
                                    $JournalH_Date      = $rJCPRJ->JournalH_Date;
                                    $JournalType        = $rJCPRJ->JournalType;	
                                    $Acc_Id             = $rJCPRJ->Acc_Id;	
                                    $Acc_Id_Cross       = $rJCPRJ->Acc_Id_Cross;	
                                    $Acc_Name           = $rJCPRJ->Acc_Name;
                                    $isTax              = $rJCPRJ->isTax;
                                    $PRJCODE            = $rJCPRJ->proj_Code;	
                                    $JOBCODEID          = $rJCPRJ->JOBCODEID;	
                                    $JournalD_Debet     = $rJCPRJ->TOT_DEBET;	
                                    $JournalD_Kredit    = $rJCPRJ->TOT_KREDIT;	
                                    $PPN_Code           = $rJCPRJ->PPN_Code;	
                                    $PPN_Perc           = $rJCPRJ->PPN_Perc;	
                                    $PPN_Amount         = $rJCPRJ->TOT_PPN;	
                                    $PPH_Code           = $rJCPRJ->PPH_Code;	
                                    $PPH_Perc           = $rJCPRJ->PPH_Perc;	
                                    $PPH_Amount         = $rJCPRJ->TOT_PPH;	
                                    $Manual_No          = $rJCPRJ->Manual_No;	
                                    $Other_Desc         = $rJCPRJ->Other_Desc;
                                    $JournalH_Desc      = $rJCPRJ->JournalH_Desc;
                                    $Journal_DK         = $rJCPRJ->Journal_DK;	
                                    $GEJ_STAT           = $rJCPRJ->GEJ_STAT;
                                    
                                    if($Journal_DK == 'D')
                                    {
                                        $TOT_DEBET = $TOT_DEBET + $JournalD_Debet;
                                        ?>
                                            <tr>
                                                <td width="100"><?php echo $Acc_Id; ?></td>
                                                <td width="350">
                                                    <div class="acc_desc"><?php echo $Acc_Name; ?></div>
                                                    <?php if($isTax == 0): ?>
                                                    <div class="desc_itm" style="font-style: italic;"><?php echo $JournalH_Desc; ?></div>
                                                    <?php else: ?>
                                                    <div class="desc_itm" style="font-style: italic;"><?php echo $Other_Desc; ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td width="100" style="text-align: right;"><?php echo number_format($JournalD_Debet, 2); ?></td>
                                                <td width="100">&nbsp;</td>
                                                <td width="100">&nbsp;</td>
                                            </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        $TOT_KREDIT = $TOT_KREDIT + $JournalD_Kredit;
                                        if($isTax == 1)
                                        {
                                            ?>
                                                <tr>
                                                    <td width="100"><?php echo $Acc_Id; ?></td>
                                                    <td width="350">
                                                        <div class="acc_desc"><?php echo $Acc_Name; ?></div>
                                                        <div class="desc_itm" style="font-style: italic;"><?php echo $Other_Desc; ?></div>
                                                    </td>
                                                    <td width="100">&nbsp;</td>
                                                    <td width="100" style="text-align: right;">&nbsp;</td>
                                                    <td width="100" style="text-align: right;"><?php echo number_format($JournalD_Kredit, 2); ?></td>
                                                </tr>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td width="100"><?php echo $Acc_Id; ?></td>
                                                    <td width="350">
                                                        <div class="acc_desc"><?php echo $Acc_Name; ?></div>
                                                    </td>
                                                    <td width="100">&nbsp;</td>
                                                    <td width="100" style="text-align: right;"><?php echo number_format($JournalD_Kredit, 2); ?></td>
                                                    <td width="100" style="text-align: right;">&nbsp;</td>
                                                </tr>
                                            <?php
                                        }
                                    }
                                    
                                endforeach;
                                
                                $maxRow = 15;
                                $addRow = 0;
                                if($row <= $maxRow) $addRow = 5;

                                for($i = 0; $i < $addRow; $i++)
                                {
                                    ?>
                                        <tr>
                                            <td width="100">&nbsp;</td>
                                            <td width="350">&nbsp;</td>
                                            <td width="100" style="text-align: right;">&nbsp;</td>
                                            <td width="100">&nbsp;</td>
                                            <td width="100">&nbsp;</td>
                                        </tr>
                                    <?php
                                }
                                
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: right; font-weight: bold;">TOTAL</td>
                            <td width="100" style="text-align: right; font-weight: bold;"><?php echo number_format($TOT_DEBET, 2); ?></td>
                            <td colspan="2" style="text-align: center; font-weight: bold;"><?php echo number_format($TOT_KREDIT, 2); ?></td>
                        </tr>
                        <tr>
                            <td style="border-right: hidden; text-align: right; vertical-align: top;">Terbilang :</td>
                            <td colspan="4"><?php echo $moneyFormat->terbilang($TOT_DEBET, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-asign-1">
                <table width="75%" border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>PREPARED BY</td>
                        <!-- <td>CERTIFIED BY</td> -->
                        <td>APPROVED BY</td>
                        <td>POSTED BY</td>
                        <td>PAYMENT BY</td>
                    </tr>
                    <tr style="height: 50px">
                        <td>&nbsp;</td>
                        <!-- <td>&nbsp;</td> -->
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="box-asign-2" style="display: none;">
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
                            <td colspan="3"><?php echo $Manual_No; ?>&nbsp;</td>
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
                        <td>&nbsp;<?php echo "$PERSL_EMPID - $EMP_NAME"; ?></td>
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

                            // get Journal hutang voucher
                            $this->db->select("A.JournalH_Code, A.SPLCODE, B.Acc_Id, B.proj_Code, B.Journal_DK, B.Base_Debet, B.Base_Kredit, B.PPN_Code, B.PPN_Amount, B.PPH_Code, B.PPH_Amount, B.Ref_Number, C.ITM_CODE, C.ITM_NAME");
                            // $this->db->select_sum("B.Base_Debet");
                            // $this->db->select_sum("B.Base_Kredit");
                            $this->db->from("tbl_journalheader_cprj A");
                            $this->db->join("tbl_journaldetail_cprj B", "B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code");
                            $this->db->join("tbl_item C", "C.ITM_CODE = B.ITM_CODE AND C.PRJCODE = B.proj_Code", "LEFT");
                            $this->db->where_in("A.JournalH_Code", $JournalH_Code);
                            // $this->db->group_by("B.Acc_Id");
                            $this->db->order_by("B.Base_Debet, B.Base_Kredit", "DESC");
                            $getJournal_Hutang = $this->db->get();
                            if($getJournal_Hutang->num_rows() > 0)
                            {
                                $no = 0;
                                $BaseKredit = 0;
                                $TBalance_Debet = 0;
                                $TBalance_Kredit = 0;
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

                                    // $TPPN_Amount = $TPPN_Amount + $PPN_Amount;
                                    // $TPPH_Amount = $TPPH_Amount + $PPH_Amount;

                                    $BaseKredit         = $Base_Kredit - $PPH_Amount;
                                    $TBalance_Debet     = $TBalance_Debet + $Base_Debet + $PPN_Amount;
                                    $TBalance_Kredit    = $TBalance_Debet;

                                    if($Journal_DK == 'D')
                                        $JournalDesc = $ITM_NAME;

                                    if($Journal_DK == 'K')
                                    {
                                        $JournalDesc = "$EMP_NAME";  // Hut Supplier
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
                                endforeach;
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