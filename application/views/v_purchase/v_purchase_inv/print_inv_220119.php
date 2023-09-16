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
        .box-detail table thead th, tbody td, tfoot td {
            padding: 2px;
        }
        .box-detail table thead th {
            text-align: center;
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
        // $INV_AMOUNT_BASE = $default['INV_AMOUNT_BASE'];
        $INV_TERM       = $default['INV_TERM'];
        $INV_STAT       = $default['INV_STAT'];
        $INV_PAYSTAT    = $default['INV_PAYSTAT'];
        $COMPANY_ID     = $default['COMPANY_ID'];
        $VENDINV_NUM    = $default['VENDINV_NUM'];
        $INV_NOTES      = $default['INV_NOTES'];

        $INV_TOTCOST    = $INV_AMOUNT + $INV_AMOUNT_PPN - $INV_AMOUNT_PPH;

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
            $this->db->select("TTK_REF2_NUM")->where_in("TTK_NUM", $arrTTK_NUM)->where("PRJCODE", $PRJCODE);
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
                    <span>PAYMENT VOUCHER<br>BANK</span>
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
                        <td width="70">INV. NO.</td>
                        <td width="5">:</td>
                        <td>&nbsp;<?php echo $INV_NUM; ?></td>
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
                            <th width="200">AMOUNT (RP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;Pembayaran Tagihan No.&nbsp;<?php echo $INV_CODE; ?></td>
                            <td width="200" style="text-align: right;"><?php echo number_format($INV_AMOUNT); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;Nomor Kwitansi : &nbsp;<?php echo $TTK_CODE; ?></td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;<?php echo $PRJNAME; ?></td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;PPN</td>
                            <td width="200" style="text-align: right;"><?php echo number_format($INV_AMOUNT_PPN, 2); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;Pph</td>
                            <td width="200" style="text-align: right;"><?php echo number_format($INV_AMOUNT_PPH, 2); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="100">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="200">&nbsp;</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="border-right: hidden; text-align: right;">Terbilang :</td>
                            <td>&nbsp;<?php echo $moneyFormat->terbilang($INV_TOTCOST); ?></td>
                            <td style="text-align: right;"><?php echo number_format($INV_TOTCOST, 2); ?>&nbsp;</td>
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
    </section>
</body>
</html>