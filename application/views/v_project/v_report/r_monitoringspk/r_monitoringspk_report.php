<?php
if($viewType == 1)
{
    $docDate    = date('ymdHis');
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=lap_monitspk_".$docDate.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$comp_name  = $this->session->userdata['comp_name'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
         <!-- Latest compiled and minified CSS -->
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <style>
            body { 
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10pt;
            }

            * {
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }

            .sheet {
                overflow: hidden;
                position: relative;
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
                .sheet.custom { padding: 10mm 5mm 10mm 5mm }

            /** For screen preview **/
                @media screen {
                    body { background: #e0e0e0 }
                    .sheet {
                        background: white;
                        box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                        margin: 5mm auto;
                        border-radius: 5px 5px 5px 5px;
                        zoom: 70%;
                    }
                }

            /** Fix for Chrome issue #273306 **/
                @media print {
                    @page { 
                        size: landscape;
                    }

                    body.A3.landscape { width: 420mm }
                    body.A3, body.A4.landscape { width: 297mm }
                    body.A4, body.A5.landscape { width: 210mm }
                    body.A5                    { width: 148mm }
                    body.letter, body.legal    { width: 216mm }
                    body.letter.landscape      { width: 280mm }
                    body.legal.landscape       { width: 357mm }

                    /** Padding area **/
                        .page .sheet {
                            margin: 0;
                            padding: 0;
                            background: initial;
                            box-shadow: initial;
                            border-radius: initial;
                        }
                }

                #Layer1 {
                    position: absolute;
                    top: 10px;
                    left: 1500px;
                }

                /* Header */
                .header {
                    width: 100%;
                    /* background-color: blue; */
                }
                .header .logo {
                    width: 15%;
                    height: 70px;
                    padding-top: 15px;
                    /* background-color: red; */
                    float: left;
                }
                .header .logo img {
                    width: 150px;
                }
                .header .title {
                    /* background-color: aqua; */
                    padding-top: 5px;
                    width: 85%;
                    height: 70px;
                    text-align: center;
                    font-size: 12pt;
                    float: left;
                }
                .header .title div:first-child {
                    font-size: 16pt;
                    font-weight: bold;
                    /* text-align: center; */
                }
                .header .title div:last-child {
                    font-size: 10pt;
                    font-weight: bold;
                    text-align: center;
                }
                .content-detail table thead th {
                    text-align: center;
                    font-size: 11pt;
                    border-top: 2px solid;
                    border-bottom: 2px solid;
                    border-color: black;
                    background-color: darkgrey !important;
                }
                .content-detail table tbody td {
                    font-size: 11pt;
                    border: 1px solid;
                }
        </style>
    </head>
    <body class="page A3 landscape">
        <section class="page sheet custom">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-md btn-default"><i class="fa fa-print"></i> Print</a>
                <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
                <i class="fa fa-download"></i> Generate PDF
                </button>
            </div>
            <div class="header">
                <div class="logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="title">
                    <div class="h1_title"><?php echo $h1_title; ?></div>
                    <div class="periode">PERIODE: <?php echo $datePeriod; ?></div>
                </div>
            </div>
            <div class="content">
                <div class="content-detail">
                    <table width="100%" border="1">
                        <thead>
                            <tr>
                                <th width="2%">&nbsp;</th>
                                <th width="9%">No. SPK</th>
                                <th width="5%">Tanggal</th>
                                <th width="12%">Supplier</th>
                                <th width="9%">Nilai SPK</th>
                                <th width="9%">No. Opname</th>
                                <th width="9%">Nilai Opname</th>
                                <th width="9%">No. TTK</th>
                                <th width="9%">No. Voucher</th>
                                <th width="9%">Nilai Voucher</th>
                                <th width="9%">No. Bayar</th>
                                <th width="9%">Nilai Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($PRJCODE == 'All')
                                    $qryPRJ = "";
                                else
                                    $qryPRJ = "AND A.PRJCODE = '$PRJCODE'";

                                $SPLDESC    = "";
                                if($SPLCODE == 'All')
                                {
                                    $qrySPL = "";
                                }
                                else
                                {
                                    $qrySPL = "AND A.SPLCODE = '$SPLCODE'";
                                    $qrySUP = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
                                    $resSUP = $this->db->query($qrySUP);
                                    if($resSUP->num_rows() > 0)
                                    {
                                        foreach($resSUP->result() as $rSUP):
                                            $SPLDESC = $rSUP->SPLDESC;
                                        endforeach;
                                    }
                                }

                                $no         = 0;
                                $totSPK     = 0;
                                $totOPN     = 0;
                                $totVOC     = 0;
                                $totPAY     = 0;
                                /*if($IDXSHOW == 'SPK')
                                {*/
                                    $get_SPK    = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.OPNH_STAT, A.PRJCODE, A.SPLCODE, A.SPLDESC,
                                                        A.WO_NUM, A.WO_CODE,  A.OPNH_AMOUNT, A.TTK_CODE, A.INV_CODE, A.INV_AMOUNT_TOT,
                                                        A.BP_CODE, A.CB_TOTAM 
                                                    FROM tbl_monitoringspk A
                                                    WHERE A.PRJCODE = '$PRJCODE'
                                                        AND (A.OPNH_DATE BETWEEN '$Start_Date' AND '$End_Date') $qrySPL
                                                    ORDER BY A.PRJCODE, A.WO_NUM, A.WO_CODE";
                                    $res_SPK    = $this->db->query($get_SPK);
                                /*}
                                else
                                {
                                    $get_SPK    = "SELECT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.OPNH_STAT, A.PRJCODE, A.SPLCODE, A1.SPLDESC,
                                                        A.WO_NUM, A.WO_CODE,  A.OPNH_AMOUNT, A.TTK_CODE, A.INV_CODE, A2.INV_AMOUNT_TOT,
                                                        A.BP_CODE, A.CB_TOTAM 
                                                    FROM tbl_monitoringspk A
                                                    WHERE A.PRJCODE = '$PRJCODE' $qrySPL
                                                    ORDER BY A.PRJCODE, A.OPNH_DATE, A.OPNH_CODE";
                                    $res_SPK    = $this->db->query($get_SPK);
                                }*/
                                $WO_VALUE       = 0;
                                $WO_VALUE2      = 0;
                                $totOPNPAR      = 0;
                                $totVOCPAR      = 0;
                                $REM_SPK        = 0;
                                $WO_VALUEH      = 0;
                                if($res_SPK->num_rows() > 0)
                                {
                                    $WO_CODE2       = "";
                                    $INV_CODE2      = "";
                                    $BP_CODE2       = "";
                                    $WO_DATE        = "";
                                    $WO_VALUE       = 0;
                                    $WO_VALUE2      = 0;
                                    $totOPNPAR      = 0;
                                    $totVOCPAR      = 0;
                                    $BPCOL          = array();
                                    foreach($res_SPK->result() as $rSPK):
                                        $OPNH_NUM       = $rSPK->OPNH_NUM;
                                        $OPNH_CODE      = $rSPK->OPNH_CODE;
                                        $OPNH_DATE      = $rSPK->OPNH_DATE;
                                        $SPLCODE        = $rSPK->SPLCODE;
                                        $SPLDESC        = $rSPK->SPLDESC;
                                        $WO_NUM         = $rSPK->WO_NUM;
                                        $WO_CODE        = $rSPK->WO_CODE;
                                        $WO_CODED       = $WO_CODE;
                                        $OPNH_AMOUNT    = $rSPK->OPNH_AMOUNT;
                                        $TTK_CODE       = $rSPK->TTK_CODE;
                                        $INV_CODE       = $rSPK->INV_CODE;
                                        $BP_CODE        = $rSPK->BP_CODE;
                                        $BP_CODED       = $BP_CODE;
                                        $CB_TOTAM       = $rSPK->CB_TOTAM;

                                        foreach ($BPCOL as $value) 
                                        {
                                            if($BP_CODE == $value)
                                              $CB_TOTAM = 0;  
                                        }

                                        array_push($BPCOL, $BP_CODE);


                                        /*if($TTK_CODE == "")
                                            $TTK_CODED  = "";

                                        if($INV_CODE == "")
                                            $INV_CODED  = "";*/

                                        $get_SPK        = "SELECT A.WO_DATE, A.WO_VALUE FROM tbl_wo_header A WHERE A.WO_NUM = '$WO_NUM'";
                                        $res_SPK        = $this->db->query($get_SPK)->result();
                                        foreach($res_SPK as $rSPK):
                                            $WO_DATE    = $rSPK->WO_DATE;
                                            $WO_DATEV   = $WO_DATE;
                                            $WO_VALUE   = $rSPK->WO_VALUE;
                                        endforeach;

                                        $INV_AMOUNT     = 0;
                                        $get_VOC        = "SELECT A.TTK_REF1_AM FROM tbl_ttk_detail A WHERE A.TTK_REF1_NUM = '$OPNH_NUM'";
                                        $res_VOC        = $this->db->query($get_VOC)->result();
                                        foreach($res_VOC as $rVOC):
                                            $INV_AMOUNT = $rVOC->TTK_REF1_AM;
                                        endforeach;

                                        $/*CB_TOTAM       = 0;
                                        $get_PAY        = "SELECT IFNULL(SUM(CBD_AMOUNT),0) AS CB_TOTAM FROM tbl_bp_detail A
                                                            WHERE A.CBD_DOCCODE = '$INV_CODE'";
                                        $res_PAY        = $this->db->query($get_PAY)->result();
                                        foreach($res_PAY as $rPAY):
                                            $CB_TOTAM   = $rPAY->CB_TOTAM;
                                        endforeach;*/

                                        $totOPN         = $totOPN + $OPNH_AMOUNT;

                                        $INV_CODED      = $INV_CODE;
                                        /*if($INV_CODE2 == $INV_CODE)
                                        {
                                            $INV_CODED  = "-";
                                        }*/
                                        $totVOC     = $totVOC + $INV_AMOUNT;

                                        if($BP_CODE2 == $BP_CODE)
                                        {
                                            $BP_CODED   = "";
                                            $CB_TOTAM   = 0;
                                        }
                                        $totPAY         = $totPAY + $CB_TOTAM;

                                        $WO_VALUED      = number_format($WO_VALUE,2);
                                        $WO_VALUEH      = $WO_VALUE;

                                        $REM_SPK        = 0;
                                        $WO_VALUE3      = $WO_VALUE;
                                        if($WO_CODE2 == $WO_CODE)
                                        {
                                            $WO_VALUE2  = $WO_VALUE;
                                            $REM_SPKV   = "";
                                            $WO_VALUE   = 0;
                                            $WO_CODED   = "";
                                            $WO_VALUED  = "";
                                            $WO_DATEV   = "";
                                            $SPLDESC    = "";
                                            $noD        = "";
                                        }
                                        else
                                        {
                                            $no             = $no+1;
                                            $noD            = $no;
                                            if($no > 1)
                                            {
                                                $REM_SPK    = round($WO_VALUE2 - $totOPNPAR,2);
                                                $colD       = "";
                                                if($REM_SPK < 0)
                                                {
                                                    $REM_SPKV   = number_format($REM_SPK,2);
                                                    $colD       = "background-color: darkgrey;";
                                                }
                                                else
                                                {
                                                    $REM_SPKV   = "";
                                                    $REM_SPK    = 0;
                                                }
                                                ?>
                                                    <tr>
                                                        <td colspan="4">&nbsp;</td>
                                                        <td style="text-align:right; font-weight: bold; <?=$colD;?>"><?=$REM_SPKV?></td>
                                                        <td>&nbsp;</td>
                                                        <td style="text-align:right; font-weight: bold;"><?php echo number_format($totOPNPAR,2);?></td>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td style="text-align:right; font-weight: bold;"><?php echo number_format($totVOCPAR,2);?></td>
                                                        <td>&nbsp;</td>
                                                        <td style="text-align:right; font-weight: bold;"><?php echo number_format(0,2);?></td>
                                                    </tr>
                                                <?php
                                                $totOPNPAR = 0;
                                                $totVOCPAR = 0;
                                            }
                                        }
                                        ?>
                                            <tr>
                                                <td style="text-align:center;"><?=$noD?></td>
                                                <td style="text-align:center;" nowrap><?=$WO_CODED?></td>
                                                <td style="text-align:center;"><?=$WO_DATEV?></td>
                                                <td><?=$SPLDESC?></td>
                                                <td style="text-align:right;"><?=$WO_VALUED?></td>
                                                <td style="text-align:center;"><?=$OPNH_CODE?></td>
                                                <td style="text-align:right;"><?php echo number_format($OPNH_AMOUNT,2);?></td>
                                                <td style="text-align:center;"><?=$TTK_CODE?></td>
                                                <td style="text-align:center;"><?=$INV_CODED?></td>
                                                <td style="text-align:right;"><?php echo number_format($INV_AMOUNT,2);?></td>
                                                <td style="text-align:center;"><?=$BP_CODED?></td>
                                                <td style="text-align:right;"><?php echo number_format($CB_TOTAM,2);?></td>
                                            </tr>
                                        <?php
                                        $totOPNPAR      = $totOPNPAR + $OPNH_AMOUNT;
                                        $totVOCPAR      = $totVOCPAR + $INV_AMOUNT;
                                        $totSPK         = $totSPK + $WO_VALUE;

                                        $WO_CODE2       = $WO_CODE;
                                        $INV_CODE2      = $INV_CODE;
                                        $BP_CODE2       = $BP_CODE;
                                        $WO_VALUE2      = $WO_VALUE3;
                                    endforeach;
                                }

                                if($IDXSHOW == 'SPK')
                                {
                                    $colD       = "";
                                    $REM_SPKV   = "";
                                    $REM_SPK    = $WO_VALUEH - $totOPNPAR;
                                    if($REM_SPK < 0)
                                    {
                                        $REM_SPKV   = number_format($REM_SPK,2);
                                        $colD       = "background-color: darkgrey;";
                                    }
                                    ?>
                                        <tr>
                                            <td colspan="4">&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold; <?=$colD?>"><?=$REM_SPKV;?></td>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totOPNPAR,2);?></td>
                                            <td colspan="2">&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totVOCPAR,2);?></td>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format(0,2);?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totSPK,2);?></td>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totOPN,2);?></td>
                                            <td colspan="2">&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totVOC,2);?></td>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totPAY,2);?></td>
                                        </tr>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <tr>
                                            <td colspan="6">&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totOPN,2);?></td>
                                            <td colspan="2">&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totVOC,2);?></td>
                                            <td>&nbsp;</td>
                                            <td style="text-align:right; font-weight: bold;"><?php echo number_format($totPAY,2);?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </body>
</html>