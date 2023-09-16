<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 8 Oktober 2018
   	* File Name	= r_paymentreport_report.php
    * Location		= -
*/
if($viewType == 1)
{
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=exceldata.xls");
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

if($PRJCODE[0] != 'All')
{
    $COLLPRJ    = implode(", ", $PRJCODE);
    $arrPRJ     = implode("','", $PRJCODE);
    $addQPRJ1   = "AND A.PRJCODE IN ('$arrPRJ')";
    $addQPRJ2   = "AND B.proj_Code IN ('$arrPRJ')";
}
else
{
    $COLLPRJ    = "Semua";
    $addQPRJ1   = "";
    $addQPRJ2   = "";
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Laporan Pembayaran</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers     = $this->session->userdata('vers');

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
            $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
            $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <style>
        body
        {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 10px Arial, Helvetica, sans-serif;
        }
        *{
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .page {
            width: 29.7cm;
            min-height: 21cm;
            padding: 0.5cm;
            margin: 0.5cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .subpage {
            padding: 1cm;
            height: 256mm;
        }
        
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
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
        
        .inplabel {border:none;background-color:white;}
        .inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
        .inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
        .inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
        .inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
        .inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
        .inpdim {border:none;background-color:white;}
    </style>

    <body style="overflow:auto">
        <div class="page">
            <table width="100%" border="0" style="size:auto; overflow-x:scroll">
                <tr>
                    <td width="19%">
                        <div id="Layer1">
                            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                            <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
                    <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
                    <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
                </tr>
                <tr>
                    <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
                    <td class="style2">&nbsp;</td>
                    <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></td>
                    <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><?php echo $mnName; ?></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php echo $comp_name; ?></span></td>
                </tr>
                    <?php
                        $StartDate1 = date('Y/m/d',strtotime($Start_Date));
                        $EndDate1 = date('Y/m/d',strtotime($End_Date));
                    ?>
                <tr>
                    <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" class="style2" style="text-align:left; font-style:italic">
                        <table width="100%">
                            <tr style="text-align:left; font-style:italic">
                                <td width="8%" nowrap valign="top">PROJ. CODE</td>
                                <td width="1%">:</td>
                                <td width="91%"><span class="style2" style="text-align:left; font-style:italic"><?php echo "$COLLPRJ"; ?></span></td>
                            </tr>
                            <tr style="text-align:left; font-style:italic; display:none;">
                              <td nowrap valign="top">PROJ. NAME</td>
                              <td>:</td>
                              <td><span class="style2" style="text-align:left; font-style:italic"></span></td>
                          </tr>
                            <tr style="text-align:left; font-style:italic">
                              <td nowrap valign="top">Sampai Tgl.</td>
                              <td>:</td>
                              <td><span class="style2" style="text-align:left; font-style:italic"><?php echo date('Y-m-d', strtotime($EndDate1)); ?></span></td>
                          </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="style2" style="text-align:center"><hr /></td>
                </tr>
            </table>
            
            <table width="100%" border="1" rules="all" style="overflow-x:scroll">
                <tr style="background:#CCCCCC">
                    <td width="3%" nowrap style="text-align:center; font-weight:bold">NO.</td>
                    <td width="11%" nowrap style="text-align:center; font-weight:bold">NO.<br>PEMBAYARAN</td>
                    <td width="7%" nowrap style="text-align:center; font-weight:bold">TANGGAL</td>
                    <td width="23%" nowrap style="text-align:center; font-weight:bold">SUPPLIER</td>
                    <td width="27%" style="text-align:center; font-weight:bold">DESKRIPSI</td>
                    <td width="21%" style="text-align:center; font-weight:bold">NAMA KAS / BANK</td>
                    <td width="8%" nowrap style="text-align:center; font-weight:bold">NOMINAL<br>(Rp)</td>
                </tr>
                <?php
                $noUx           = 0;
                $NVOCCODEX      = '';
                $GTOTAL         = 0;

                if($SPLCODE[0] != 'All')
                {
                   $arrSPL         = implode("','", $SPLCODE);
                   $addQSPLA       = "AND A.CB_PAYFOR IN ('$arrSPL')";
                   $addQSPLB       = "AND B.PERSL_EMPID IN ('$arrSPL')";
                }
                else
                {
                    $addQSPLA = '';
                    $addQSPLB = '';
                }

                if($selAccount[0] != 'All')
                {
                   $arrACC         = implode("','", $selAccount);
                   $addQACCA       = "AND A.CB_ACCID IN ('$arrACC')";
                   $addQACCB       = "AND C.Acc_Id IN ('$arrACC')";
                }
                else
                {
                    $addQACCA = '';
                    $addQACCB = '';
                }
                
                $sqlq3          = "SELECT DISTINCT A.JournalH_Code AS GEJ_NUM, A.CB_CODE, A.PRJCODE, A.CB_DATE, A.CB_TYPE,
                                        A.CB_SOURCE, A.CB_ACCID, A.CB_PAYFOR, A.CB_TOTAM, A.CB_TOTAM_PPn, A.CB_NOTES 
                                    FROM tbl_bp_header A
                                    WHERE 
                                        A.CB_STAT = 3 AND A.CB_DATE >= '$Start_Date' AND A.CB_DATE <= '$End_Date'
                                        AND A.CB_PAYTYPE != 'PD'
                                        $addQPRJ1 $addQSPLA $addQACCA
                                    UNION ALL
                                    SELECT DISTINCT B.JournalH_Code AS GEJ_NUM, B.Manual_No AS CB_CODE, B.proj_Code AS PRJCODE,
                                        B.JournalH_Date AS CB_DATE, B.JournalType AS CB_TYPE, '' AS CB_SOURCE,
                                        B.acc_number AS CB_ACCID, PERSL_EMPID AS CB_PAYFOR, B.GJournal_Total AS CB_TOTAM, 0 AS CB_TOTAM_PPn, 
                                        B.JournalH_Desc AS CB_NOTES
                                    FROM
                                        tbl_journalheader B
                                    INNER JOIN tbl_journaldetail C ON C.JournalH_Code = B.JournalH_Code
                                    AND C.proj_Code = B.proj_Code
                                    WHERE 
                                        B.JournalType IN ('CPRJ','VCPRJ','CHO','VCHO')
                                        $addQSPLB $addQACCB $addQPRJ2
                                        AND B.GEJ_STAT = 3 AND B.JournalH_Date >= '$Start_Date' AND B.JournalH_Date <= '$End_Date'
                                    ORDER BY CB_DATE";
                $resq3          = $this->db->query($sqlq3)->result();                           
                foreach($resq3 as $rowsqlq3) :
                    $noUx       = $noUx + 1;
                    $GEJ_NUM    = $rowsqlq3->GEJ_NUM;
                    $CB_CODE    = $rowsqlq3->CB_CODE;
                    $PRJCODE    = $rowsqlq3->PRJCODE;               
                    $CB_DATE    = $rowsqlq3->CB_DATE;
                    $CB_TYPE    = $rowsqlq3->CB_TYPE;
                    $CB_SOURCE  = $rowsqlq3->CB_SOURCE;
                    $Acc_ID     = $rowsqlq3->CB_ACCID;
                    $CB_PAYFOR  = $rowsqlq3->CB_PAYFOR;
                    $CB_TOTAM   = $rowsqlq3->CB_TOTAM;
                    $CB_TOTAMPPn= $rowsqlq3->CB_TOTAM_PPn;
                    $CB_NOTES   = $rowsqlq3->CB_NOTES;
                    $GTOTAL     = $GTOTAL + $CB_TOTAM;
                    
                    $SPLDESC    = '';
                    $sqlq4a     = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$CB_PAYFOR' LIMIT 1";
                    $resq4a     = $this->db->query($sqlq4a)->result();
                    foreach($resq4a as $rowq4a) :
                        $SPLDESC    = $rowq4a->SPLDESC;
                    endforeach;

                    if($SPLDESC == '')
                    {
                        $sqlq4a     = "SELECT (CONCAT(First_Name,' ',Last_Name)) AS SPLDESC FROM tbl_employee WHERE Emp_ID = '$CB_PAYFOR' LIMIT 1";
                        $resq4a     = $this->db->query($sqlq4a)->result();
                        foreach($resq4a as $rowq4a) :
                            $SPLDESC    = $rowq4a->SPLDESC;
                        endforeach;
                    }
                    
                    $ACCNAME    = '';
                    $sqlq5a     = "SELECT Account_NameId FROM tbl_chartaccount WHERE Account_Number = '$Acc_ID' LIMIT 1";
                    $resq5a     = $this->db->query($sqlq5a)->result();
                    foreach($resq5a as $rowq5a) :
                        $ACCNAME    = $rowq5a->Account_NameId;
                    endforeach;

                    if($CB_TYPE == 'BP')
                    {                        
                        $sqlCB 		= "SELECT CBD_DOCCODE, CBD_DESC 
                                        FROM tbl_bp_detail WHERE CB_NUM = '$GEJ_NUM'";
                        $resCB 		= $this->db->query($sqlCB)->result();
                        $row 		= 0;
                        $CBD_DOCCODE 	= '';
                        $CBD_DESC 	= '';
                        foreach($resCB AS $rowCB):
                            $row 		= $row + 1;
                            $CBD_DOCCODE= $rowCB->CBD_DOCCODE;
                            $CBD_DESC 	= $rowCB->CBD_DESC;
                            if($row == 1)
                            {
                                $CB_NOTES = "<div>$CBD_DOCCODE - <span style='font-style: italic;'>$CBD_DESC</span></div>";
                            }
                            else
                            {
                                $CB_NOTES = "$CB_NOTES<div>$CBD_DOCCODE - <span style='font-style: italic;'>$CBD_DESC</span></div>";
                            }
                        endforeach;
                    }
                    ?>
                        <tr>
                            <td nowrap style="text-align:left;"><?php echo $noUx; ?>.</td>
                            <td nowrap style="text-align:left;"><?php echo "$CB_CODE"; ?></td>
                            <td nowrap style="text-align:center;"><?php echo date('d M Y', strtotime($CB_DATE)); ?></td>
                            <td style="text-align:left;"><?php echo $SPLDESC; ?></td>
                            <td style="text-align:left;"><?php echo $CB_NOTES; ?></td>
                            <td style="text-align:left;"><?php echo "$Acc_ID - $ACCNAME"; ?></td>
                            <td nowrap style="text-align:right;"><?php echo number_format($CB_TOTAM, 2); ?>&nbsp;</td>
                        </tr>
                    <?php
                endforeach;
                ?>
                <tr>
                    <td colspan="6" nowrap style="text-align:right; font-weight:bold;">T O T A L</td>
                  <td nowrap style="text-align:right; font-weight:bold"><?php echo number_format($GTOTAL, 2); ?>&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>