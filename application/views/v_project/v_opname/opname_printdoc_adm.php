<?php
    $OPNH_NUM     = $default['OPNH_NUM'];
    $OPNH_CODE    = $default['OPNH_CODE'];  
    $OPNH_DATE    = $default['OPNH_DATE'];  
    $OPNH_DATESP  = $default['OPNH_DATESP']; 
    $OPNH_DATEEP  = $default['OPNH_DATEEP']; 
    $OPNH_TYPE    = $default['OPNH_TYPE'];  
    $OPNH_NOTE    = $default['OPNH_NOTE'];  
    $OPNH_STAT    = $default['OPNH_STAT'];  
    $WO_NUM       = $default['WO_NUM'];         
    $WO_CODE      = $default['WO_CODE'];    
    $WO_DATE      = $default['WO_DATE'];    
    $WO_STARTD    = $default['WO_STARTD'];  
    $WO_ENDD      = $default['WO_ENDD'];    
    $PRJCODE      = $default['PRJCODE'];
    $SPLCODE      = $default['SPLCODE'];    
    $WO_DEPT      = $default['WO_DEPT'];    
    $WO_CATEG     = $default['WO_CATEG'];
    $WO_TYPE      = $default['WO_TYPE'];    
    $JOBCODEID    = $default['JOBCODEID'];  
    $WO_NOTE      = $default['WO_NOTE'];    
    $WO_NOTE2     = $default['WO_NOTE2'];   
    $WO_STAT      = $default['WO_STAT'];    
    $WO_VALUE     = $default['WO_VALUE'];   
    $WO_MEMO      = $default['WO_MEMO'];    
    $PRJNAME      = $default['PRJNAME'];    
    $WO_REFNO     = $default['WO_REFNO'];   
    $FPA_NUM      = $default['FPA_NUM'];    
    $FPA_CODE     = $default['FPA_CODE'];   
    $WO_QUOT      = $default['WO_QUOT'];    
    $WO_NEGO      = $default['WO_NEGO']; 
    $OPNH_TYPE    = $default['OPNH_TYPE'];

    // get SPK Kategori
        $get_WOCATEG    = "SELECT VendCat_Name FROM tbl_vendcat WHERE VendCat_Code = '$WO_CATEG'";
        $res_WOCATEG    = $this->db->query($get_WOCATEG);
        $WO_CATEGD      = "";
        if($res_WOCATEG->num_rows() > 0)
        {
            foreach($res_WOCATEG->result() as $rWOC):
                $WO_CATEGD  = $rWOC->VendCat_Name;
            endforeach;
        }

    $WO_DATEV = date('d-m-Y', strtotime($WO_DATE));

    // get PRJNAME
        $PRJNAME = "";
        $PRJLOCT = "";
        $PRJ_HO  = "$PRJCODE";
        $get_PRJ = "SELECT PRJNAME, PRJLOCT, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
        $res_PRJ = $this->db->query($get_PRJ);
        if($res_PRJ->num_rows() > 0)
        {
            foreach($res_PRJ->result() as $rPRJ):
                $PRJNAME    = $rPRJ->PRJNAME;
                $PRJLOCT    = $rPRJ->PRJLOCT;
                $PRJ_HO     = $rPRJ->PRJCODE_HO;
            endforeach;
        }

    // get SUPPLIER
        $SPLDESC = $this->db->get_where("tbl_supplier", ["SPLCODE" => $SPLCODE])->row("SPLDESC");

    $OPNH_DATEV = date('d-m-Y', strtotime($OPNH_DATE));

    //GET Opname Number
        // $queryOPN   = $this->db->order_by('OPNH_DATE','ASC')->get_where('tbl_opn_header', array('WO_CODE' => $WO_CODE, 'OPNH_STAT' => 3));
        $getOPN     = "SELECT * FROM tbl_opn_header WHERE WO_CODE = '$WO_CODE' AND OPNH_STAT NOT IN (5,9) AND OPNH_TYPE = 0
                        ORDER BY OPNH_DATE ASC";
        $queryOPN   = $this->db->query($getOPN);
        if($queryOPN->num_rows() > 0)
        {
            $noOPN = 0;
            $OPNH_TO = "";
            foreach($queryOPN->result() as $rOPN):
                $noOPN       = $noOPN + 1;
                $OPNH_CODE_T  = $rOPN->OPNH_CODE;
                if($OPNH_CODE == $OPNH_CODE_T)
                {
                    $OPNH_TO  = $noOPN;
                }
            endforeach;
        }
        else
        {
            $OPNH_TO = "";
        }

        $startD = date('d', strtotime($OPNH_DATESP));
        // $startM = tanggal_indo($OPNH_DATESP);
        $startM = date('m', strtotime($OPNH_DATESP));
        $startY = date('Y', strtotime($OPNH_DATESP));
        $endD   = date('d', strtotime($OPNH_DATEEP));
        // $endM   = tanggal_indo($OPNH_DATEEP);
        $endM   = date('m', strtotime($OPNH_DATEEP));
        $endY   = date('Y', strtotime($OPNH_DATEEP));
        $day1   = strtotime($OPNH_DATEEP) - strtotime($OPNH_DATESP);
        $day    = floor($day1/(60 * 60 * 24));

    // get OPN - RET
        if($OPNH_TYPE == 1)
        {
            $s_getRET   = "SELECT OPNH_CODE FROM tbl_opn_header WHERE OPNH_NUM = '$OPNH_NUM' AND OPNH_TYPE = 1 AND OPNH_STAT NOT IN (5,9)";
            $r_getRET   = $this->db->query($s_getRET);
            if($r_getRET->num_rows() > 0)
            {
                foreach($r_getRET->result() as $rw_getRET):
                    $OPNH_CODE = $rw_getRET->OPNH_CODE;
                endforeach;
            }
        }

    // Get Prgrs Opname
        $TOT_SPKVAL = 0;
        $get_WOD    = "SELECT SUM(B.WO_TOTAL) AS TOT_SPK, SUM(WO_CAMN) AS TOT_CAMN,
                        SUM(B.OPN_AMOUNT) AS TOT_OPN
                        FROM tbl_wo_header A
                        INNER JOIN tbl_wo_detail B ON B.WO_NUM = A.WO_NUM AND B.PRJCODE = A.PRJCODE
                        WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND A.WO_STAT NOT IN (5,9)";
        $res_WOD    = $this->db->query($get_WOD);
        foreach($res_WOD->result() as $rWOD):
            $TOT_SPK    = $rWOD->TOT_SPK;
            $TOT_CAMN   = $rWOD->TOT_CAMN;
            $TOT_SPKVAL = $TOT_SPK - $TOT_CAMN;
            //$PRGRS_OPN  = ($TOT_OPN / ($TOT_SPK - $TOT_CAMN)) * 100;
        endforeach;

    // Get Prgrs Opname
        $TOT_OPNVAL = 0;
        /*$get_OPND   = "SELECT SUM(B.OPND_VOLM) AS TOT_OPNVOL, SUM(B.OPND_ITMTOTAL) AS TOT_OPNVAL
                        FROM tbl_opn_header A
                        INNER JOIN tbl_opn_detail B ON B.OPNH_NUM = A.OPNH_NUM AND B.PRJCODE = A.PRJCODE
                        WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND A.OPNH_STAT NOT IN (5,9)
                            AND B.OPNH_DATE <= '$OPNH_DATE' AND A.OPNH_TYPE = 0";*/
        $get_OPND   = "SELECT SUM(A.OPND_VOLM) AS TOT_OPNVOL, SUM(A.OPND_ITMTOTAL) AS TOT_OPNVAL
                        FROM tbl_opn_detail A
                        INNER JOIN tbl_opn_header B ON B.OPNH_NUM = A.OPNH_NUM AND A.PRJCODE = B.PRJCODE
                        WHERE A.WO_NUM = '$WO_NUM' AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT NOT IN (5,9)
                            AND B.OPNH_DATE <= '$OPNH_DATE' AND B.OPNH_TYPE = 0";
        $res_OPND   = $this->db->query($get_OPND);
        foreach($res_OPND->result() as $rOPND):
            $TOT_OPNVOL = $rOPND->TOT_OPNVOL;
            $TOT_OPNVAL = $rOPND->TOT_OPNVAL;
        endforeach;
        if($TOT_SPKVAL == 0)
            $TOT_SPKVAL = 1;

        $PRGRS_OPN      = ($TOT_OPNVAL / $TOT_SPKVAL) * 100;
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
        .sheet.custom { padding: 1cm 0.5cm 0.97cm 0.5cm }

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
          /* @page { size: a4;} */
          body.A3.landscape { width: 420mm }
          body.A3, body.A4.landscape { width: 297mm }
          body.A4, body.A5.landscape { width: 210mm }
          body.A5                    { width: 148mm }
          body.letter, body.legal    { width: 216mm }
          body.letter.landscape      { width: 280mm }
          body.legal.landscape       { width: 357mm }
        }

        .main-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            /* background-color: #2196F3; */
            /* padding: 10px; */
            grid-template-areas: 'header header header'
                                 'content content content'
                                 'footer footer footer';
            border: 1px solid;
        }

        .header-item {
            grid-area: header;
            /* background-color: red; */
            /* border: 1px solid rgba(0, 0, 0, 0.8); */
            /* padding: 20px; */
            /* font-size: 30px; */
            /* text-align: center; */
            display: grid;
            grid-template-columns: 130px 1fr;
            border-bottom: 2px solid;
        }
        .logo {
            /* background-color: red; */
            text-align: center;
            vertical-align: middle;
        }

        .logo img {
            width: 200px;
            margin: 5px 5px 5px 5px;
        }

        .title {
            padding-top: 10px;
            /* background-color: red; */
            font-family: Impact;
            text-align: center;
            font-weight: bold;
            font-size: 18pt;
        }

        .content-item {
            grid-area: content;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-areas: 'content-header'
                                 'content-detail'
                                 'footer-detail'
                                 'asign-detail';

            font-size: 9pt;
        }
        .content-header {
            grid-area: content-header;
            padding: 5px;
        }
        .content-detail table th {
            border: 1px solid;
            padding: 3px;
            text-align: center;
        }
        .content-detail table td {
            border: 1px solid;
            padding: 3px;
        }

        .footer-detail {
            grid-area: footer-detail;
            padding-left: 650px;
            padding-right: 240px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .asign-detail {
            grid-area: asign-detail;
        }

        .asign-detail table td {
            border-top: double;
            border-right: double;
            width: 20%;
            /* padding: 5px; */
            text-align: center;
        }

        .printed {
            position: absolute;
            left: 785px;
            top: 20px;
            font-size: 10pt;
            font-style: italic;
            white-space: nowrap;
        }

        
        #Layer1 {
            position: absolute;
            top: 10px;
            left: 20px;
        }

        #background{
            position:absolute;
            top: 30%;
            left: 30%;
            z-index:0;
            display:block;
            min-height:50%; 
            min-width:50%;
            color:yellow;
            opacity: 0.6;
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
    </style>
</head>
<body class="page A4 landscape">
    <?php
        if($OPNH_STAT == 1 || $OPNH_STAT == 2 || $OPNH_STAT == 4)
        {
            $watermark_text = "";
        }
        else
        {
            $watermark_text = "";
        }
    ?>
    <section class="page sheet custom">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
        <div id="background">
            <p id="bg-text"><?php echo $watermark_text; ?></p>
        </div>
        <div class="printed">Tanggal Cetak : <?php echo $PRJLOCT; ?>, <?php echo date('d-m-Y') ?></div>
        <div class="main-container">
            <div class="header-item">
                <div class="logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="title">
                    Opname (SPK - <?=$WO_CATEGD?>)
                </div>
            </div>
            <div class="content-item">
                <div class="content-header">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="110">Kode/Tgl. Opname</td>
                            <td width="10">:</td>
                            <td width="230" style="font-weight: 700;"><?php echo "$OPNH_CODE - $OPNH_TO / $OPNH_DATEV"; ?></td>
                            <td width="60">Kode SPK</td>
                            <td width="10">:</td>
                            <td style="font-weight: 800;"><?php echo $WO_CODE; ?></td>
                            <td width="80" nowrap>Kode Proyek</td>
                            <td width="10">:</td>
                            <td style="font-weight: 800;"><?php echo "$PRJCODE"; ?></td>
                        </tr>
                        <tr>
                            <td width="100">Periode Opname</td>
                            <td width="10">:</td>
                            <!-- <td><?php echo "$startD-$startM-$startY s/d $endD-$endM-$endY";?></td> -->
                            <td><?php echo date("d-m-Y", strtotime($OPNH_DATESP))." s/d ".date("d-m-Y", strtotime($OPNH_DATEEP));?></td>
                            <td width="60">Tgl. SPK</td>
                            <td width="10">:</td>
                            <td><?php echo $WO_DATEV; ?></td>
                            <td width="80" nowrap>Nama Proyek</td>
                            <td width="10">:</td>
                            <td style="font-weight: 800;"><?php echo "$PRJNAME"; ?></td>
                        </tr>
                        <tr>
                            <td width="100">Akum. Progress</td>
                            <td width="10">:</td>
                            <td><?php echo number_format($PRGRS_OPN, 2)."%"; ?></td>
                            <td width="60" nowrap>Periode SPK</td>
                            <td width="10">:</td>
                            <td><?php echo date("d-m-Y", strtotime($WO_STARTD))." s/d ".date("d-m-Y", strtotime($WO_ENDD));?></td>
                            <td width="80" nowrap>Supplier</td>
                            <td width="10">:</td>
                            <td style="font-weight: 800;"><?php echo "$SPLDESC ($SPLCODE)"; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="content-detail">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-left: hidden; border-right: hidden;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Sat.</th>
                                <th>Vol. SPK</th>
                                <th>Harga SPK</th>
                                <th>Jumlah SPK</th>
                                <th>Vol. sd lalu</th>
                                <th>Vol. Ini</th>
                                <th>Vol. sd Ini</th>
                                <th>Jumlah sd Lalu</th>
                                <th>Jumlah Ini</th>
                                <th>Jumlah sd Ini</th>
                                <th>Pekerjaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                /*$get_OPND   = "SELECT A.WO_NUM, B.*, C.OPNH_NUM, C.OPNH_CODE, C.OPNH_DATE, C.OPNH_DATESP, C.OPNH_DATEEP, 
                                                    C.OPNH_AMOUNT, C.OPNH_AMOUNTPPNP, C.OPNH_AMOUNTPPN, C.OPNH_AMOUNTPPHP, C.OPNH_AMOUNTPPH, 
                                                    C.OPNH_DPPER, C.OPNH_DPVAL, C.OPNH_RETPERC, C.OPNH_RETAMN, C.OPNH_NOTE, C.OPNH_STAT
                                                FROM tbl_wo_header A
                                                    INNER JOIN tbl_wo_detail B ON B.WO_NUM = A.WO_NUM AND B.PRJCODE = A.PRJCODE
                                                    LEFT JOIN tbl_opn_header C ON C.WO_NUM = B.WO_NUM AND C.PRJCODE = B.PRJCODE
                                                    INNER JOIN tbl_opn_detail D ON D.OPNH_NUM = C.OPNH_NUM AND D.PRJCODE = C.PRJCODE
                                                WHERE C.OPNH_NUM = '$OPNH_NUM' AND C.OPNH_TYPE = 0 AND C.OPNH_STAT NOT IN (5,9) 
                                                AND A.PRJCODE = '$PRJCODE' AND D.WO_ID = B.WO_ID
                                                ORDER BY C.OPNH_DATE ASC";*/
                                $get_OPND   = "SELECT DISTINCT
                                                    B.*, C.OPNH_NUM, C.OPNH_CODE, C.OPNH_DATE, C.OPNH_DATESP, C.OPNH_DATEEP, 
                                                        C.OPNH_AMOUNT, C.OPNH_AMOUNTPPNP, C.OPNH_AMOUNTPPN, C.OPNH_AMOUNTPPHP, C.OPNH_AMOUNTPPH, 
                                                        C.OPNH_DPPER, C.OPNH_DPVAL, C.OPNH_RETPERC, C.OPNH_RETAMN, C.OPNH_NOTE, C.OPNH_STAT
                                                FROM
                                                    tbl_opn_detail A
                                                    RIGHT JOIN tbl_wo_detail B ON A.WO_NUM = B.WO_NUM
                                                    INNER JOIN tbl_opn_header C ON C.OPNH_NUM = A.OPNH_NUM
                                                WHERE
                                                    A.OPNH_NUM = '$OPNH_NUM'
                                                    AND C.OPNH_TYPE = 0 AND C.OPNH_STAT NOT IN (5,9)
                                                ORDER BY A.OPNH_DATE ASC";
                                $res_OPND   = $this->db->query($get_OPND);
                                if($res_OPND->num_rows() > 0)
                                {
                                    $no = 0;
                                    $SUBTOT_OPNVOL  = 0;
                                    $SUBTOT_OPNVAL  = 0;
                                    $WO_GTOTAL      = 0;
                                    $GTOT_OPNVALBF  = 0;
                                    $GTOT_OPNVALNOW = 0;
                                    $GTOT_OPNVAL    = 0;
                                    $INV_AMOUNT     = 0;
                                    $TOT_NETT       = 0;
                                    foreach($res_OPND->result() as $rOPND):
                                        $no             = $no + 1;
                                        $OPNH_NUM       = $rOPND->OPNH_NUM;
                                        $OPNH_CODE      = $rOPND->OPNH_CODE;
                                        $OPNH_DATE      = $rOPND->OPNH_DATE;
                                        $OPNH_AMOUNT    = $rOPND->OPNH_AMOUNT;
                                        $OPNH_DPVAL     = $rOPND->OPNH_DPVAL;
                                        $OPNH_RETAMN    = $rOPND->OPNH_RETAMN;
                                        $OPNH_AMOUNTPPN = $rOPND->OPNH_AMOUNTPPN;
                                        $OPNH_AMOUNTPPH = $rOPND->OPNH_AMOUNTPPH;

                                        $WO_NUM         = $rOPND->WO_NUM;
                                        $WO_ID          = $rOPND->WO_ID;
                                        $WO_DESC        = $rOPND->WO_DESC;
                                        $JOBCODEID      = $rOPND->JOBCODEID;
                                        // $JOBPARENT      = $this->db->get_where("tbl_joblist_detail", ["JOBCODEID" => $JOBCODEID, "PRJCODE" => $PRJCODE])->row("JOBPARENT");
                                        // echo "JOBCODEID: $JOBCODEID -- JOBPARENT: $JOBPARENT<br>";
                                        $s_JP           = "SELECT JOBPARENT FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
                                        $r_JP           = $this->db->query($s_JP);
                                        if($r_JP->num_rows() > 0)
                                        {
                                            foreach($r_JP->result() as $rw_JP):
                                                $JOBPARENT = $rw_JP->JOBPARENT;
                                            endforeach;
                                        }

                                        // Get JOBDESC
                                            $getJOBP = "SELECT JOBDESC, WBS_STAT FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
                                            $resJOBP = $this->db->query($getJOBP);
                                            if($resJOBP->num_rows() > 0)
                                            {
                                                foreach($resJOBP->result() as $rJP):
                                                    $JOBDESC    = $rJP->JOBDESC;
                                                    $BOQSTAT    = $rJP->WBS_STAT;
                                                    if($BOQSTAT == 2)
                                                    {
                                                        $JOBDH  = explode(".", $JOBCODEID);
                                                        $JOBDHC = count($JOBDH);
                                                        $i      = 0;
                                                        $JOBPARC= "";
                                                        foreach($JOBDH as $arr1):
                                                            if($i < $JOBDHC-1)
                                                            {
                                                                if($i == 1)
                                                                    $JOBPARC = $arr1;
                                                                else
                                                                    $JOBPARC = $JOBPARC.".".$arr1;
                                                            }
                                                            $i      = $i+1;
                                                        endforeach;
                                                        $g_JOBH     = "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARC' AND PRJCODE_HO = '$PRJ_HO'";
                                                        $r_JOBH     = $this->db->query($g_JOBH);
                                                        if($r_JOBH->num_rows() > 0)
                                                        {
                                                            foreach($r_JOBH->result() as $rw_JOBH):
                                                                $JOBDESC    = $rw_JOBH->JOBDESC;
                                                            endforeach;
                                                        }
                                                        $JOBPARENT  = $JOBPARC;
                                                    }
                                                endforeach;
                                            }
                                            else
                                            {
                                                $getJOBP = "SELECT JOBDESC FROM tbl_joblist_detail WHERE JOBCODEID = '$JOBPARENT' AND PRJCODE = '$PRJCODE'";
                                                $resJOBP = $this->db->query($getJOBP);
                                                if($resJOBP->num_rows() > 0)
                                                {
                                                    foreach($resJOBP->result() as $rJP):
                                                        $JOBDESC    = $rJP->JOBDESC;
                                                    endforeach;
                                                }
                                            }

                                        $ITM_CODE   = $rOPND->ITM_CODE;
                                        $ITM_UNIT   = $rOPND->ITM_UNIT2;
                                        $ITM_NAME   = $this->db->get_where("tbl_item", ["ITM_CODE" => $ITM_CODE, "PRJCODE" => $PRJCODE])->row("ITM_NAME");
                                        $WO_VOLM    = $rOPND->WO_VOLM;
                                        $ITM_PRICE  = $rOPND->ITM_PRICE;
                                        $WO_CVOL    = $rOPND->WO_CVOL;
                                        $WO_CAMN    = $rOPND->WO_CAMN;
                                        $WO_TOTVOL  = $WO_VOLM - $WO_CVOL;
                                        $WO_TOTAL   = $WO_TOTVOL * $ITM_PRICE;

                                        // get Opname Lalu
                                            $get_OPNBF = "SELECT SUM(B.OPND_VOLM) AS TOT_OPNVOL, 
                                                            B.OPND_ITMPRICE, SUM(B.OPND_ITMTOTAL) AS TOT_OPNVAL 
                                                            FROM tbl_opn_header A
                                                            INNER JOIN tbl_opn_detail B ON B.OPNH_NUM = A.OPNH_NUM
                                                            WHERE A.WO_NUM = '$WO_NUM' AND A.OPNH_NUM != '$OPNH_NUM' AND A.OPNH_DATE < '$OPNH_DATE'
                                                            AND A.OPNH_TYPE = 0 AND A.OPNH_STAT NOT IN (5,9) AND A.PRJCODE = '$PRJCODE'
                                                            AND B.ITM_CODE = '$ITM_CODE' AND B.WO_ID = '$WO_ID'";
                                            $res_OPNBF = $this->db->query($get_OPNBF);
                                            if($res_OPNBF->num_rows() > 0)
                                            {
                                                foreach($res_OPNBF->result() as $rOPNDBF):
                                                    $TOT_OPNVOLBF     = $rOPNDBF->TOT_OPNVOL;
                                                    $TOT_OPNVALBF     = $rOPNDBF->TOT_OPNVAL;
                                                endforeach;
                                            }

                                        // get Opname Ini
                                            $get_OPN = "SELECT SUM(B.OPND_VOLM) AS TOT_OPNVOL, 
                                                            B.OPND_ITMPRICE, SUM(B.OPND_ITMTOTAL) AS TOT_OPNVAL 
                                                            FROM tbl_opn_header A
                                                            INNER JOIN tbl_opn_detail B ON B.OPNH_NUM = A.OPNH_NUM
                                                            WHERE A.WO_NUM = '$WO_NUM' AND A.OPNH_NUM = '$OPNH_NUM'
                                                            AND A.OPNH_TYPE = 0 AND A.OPNH_STAT NOT IN (5,9) AND A.PRJCODE = '$PRJCODE'
                                                            AND B.ITM_CODE = '$ITM_CODE' AND B.WO_ID = '$WO_ID'";
                                            $res_OPN = $this->db->query($get_OPN);
                                            if($res_OPN->num_rows() > 0)
                                            {
                                                foreach($res_OPN->result() as $rOPND):
                                                    $TOT_OPNVOL     = $rOPND->TOT_OPNVOL;
                                                    $TOT_OPNVAL     = $rOPND->TOT_OPNVAL;
                                                endforeach;
                                            }

                                            $SUBTOT_OPNVOL  = $TOT_OPNVOLBF + $TOT_OPNVOL;
                                            $SUBTOT_OPNVAL  = $TOT_OPNVALBF + $TOT_OPNVAL;

                                            // TOTAL
                                                $WO_GTOTAL      = $WO_GTOTAL + $WO_TOTAL;
                                                $GTOT_OPNVALBF  = $GTOT_OPNVALBF + $TOT_OPNVALBF;
                                                $GTOT_OPNVALNOW = $GTOT_OPNVALNOW + $TOT_OPNVAL;
                                                $GTOT_OPNVAL    = $GTOT_OPNVAL + $SUBTOT_OPNVAL;
                                                //$INV_AMOUNT    = ($OPNH_AMOUNT - $OPNH_DPVAL - $OPNH_RETAMN );
                                                $INV_AMOUNT     = ($OPNH_AMOUNT - $OPNH_RETAMN );
                                                $TOT_NETT       = $INV_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH;
                                            
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?=$no?></td>
                                                <td>
                                                    <?php 
                                                        $WO_DESCD = "";
                                                        if($WO_DESC != '') $WO_DESCD = "($WO_DESC)";
                                                        echo "<b>$ITM_CODE</b><div style='padding-left: 2px;'>$ITM_NAME</div><div style='padding-left: 2px; font-style: italic;'>$WO_DESCD</div>";  
                                                    ?>
                                                </td>
                                                <td style="text-align: center;"><?=$ITM_UNIT?></td>
                                                <td style="text-align: right;"><?php echo number_format($WO_TOTVOL, 2) ?></td>
                                                <td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2) ?></td>
                                                <td style="text-align: right;"><?php echo number_format($WO_TOTAL, 2) ?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_OPNVOLBF, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_OPNVOL, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($SUBTOT_OPNVOL, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_OPNVALBF, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_OPNVAL, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($SUBTOT_OPNVAL, 2); ?></td>
                                                <td>
                                                    <?php
                                                        echo "<b>$JOBPARENT</b><div>$JOBDESC</div>";
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                    endforeach;

                                    ?>
                                        <tr>
                                            <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL SPK</td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($WO_GTOTAL, 2); ?></td>
                                            <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL OPNAME</td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_OPNVALBF, 2); ?></td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_OPNVALNOW, 2); ?></td>
                                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_OPNVAL, 2); ?></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                        <tfoot style="display: none;">
                            <tr>
                                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL</td>
                                <td style="text-align: right; font-weight: bold;"><?php echo number_format($WO_GTOTAL, 2); ?></td>
                                <td colspan="5">&nbsp;</td>
                                <td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_OPNVAL, 2); ?></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="footer-detail">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr style="display: none;">
                            <td width="100" style="font-weight: bold;">Pot. Uang Muka</td>
                            <td width="10">:</td>
                            <td style="text-align: right;"><?php echo number_format($OPNH_DPVAL, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100" style="font-weight: bold;">RETENSI</td>
                            <td width="10">:</td>
                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($OPNH_RETAMN, 2); ?></td>
                        </tr>
                        <tr>
                            <td width="100" style="font-weight: bold;">SUB. TOTAL</td>
                            <td width="10">:</td>
                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($INV_AMOUNT, 2); ?></td>
                        </tr>
                        <tr style="display: none;">
                            <td width="100" style="font-weight: bold;">PPN</td>
                            <td width="10">:</td>
                            <td style="text-align: right;"><?php echo number_format($OPNH_AMOUNTPPN, 2); ?></td>
                        </tr>
                        <tr style="display: none;">
                            <td width="100" style="font-weight: bold;">PPh</td>
                            <td width="10">:</td>
                            <td style="text-align: right;"><?php echo number_format($OPNH_AMOUNTPPH, 2); ?></td>
                        </tr>
                        <tr style="display: none;">
                            <td width="100" style="font-weight: bold;">Netto</td>
                            <td width="10">:</td>
                            <td style="text-align: right; font-weight: bold;"><?php echo number_format($TOT_NETT, 2); ?></td>
                        </tr>
                    </table>
                </div>
                <?php
                    $TTD1   = "";
                    $TTD2   = "";
                    $TTD3   = "";
                    if($WO_CATEG == 'A')
                    {
                        $TTD1   = "KA. MEKANIK";
                        $TTD2   = "KA. DIV.";
                        $TTD3   = "KA. DEP";
                    }
                ?>
                <div class="asign-detail">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-right: hidden;">
                        <tr>
                            <td style="font-weight: bold;">ENGINEERING (QS) /<br><?=$TTD1?></td>
                            <td style="font-weight: bold; text-align: left; padding-left: 2px;">SITE MANAGER (SM) / KOORD. MEP PROYEK / <?=$TTD2?> / ...........</td>
                            <td style="font-weight: bold; text-align: left; padding-left: 2px;">PROJECT MANAGER / <?=$TTD3?> / <br>...........</td>
                            <td style="font-weight: bold;">Mgr. Subkon/ Mgr. ME/ Mgr. Opr/ Ka. Cabang (untuk nilai ≥ 100jt)</td>
                            <td style="font-weight: bold;">MANDOR / SUB / PEMASOK *) <br>JAB.:</td>
                        </tr>
                        <tr height="100">
                            <td style="vertical-align: bottom; text-align: left; font-style: italic;">
                                <div style="border-bottom: dashed 1px;">NAMA :</div>
                                <div>TGL. :</div>
                            </td>
                            <td style="vertical-align: bottom; text-align: left; font-style: italic;">
                                <div style="border-bottom: dashed 1px;">NAMA :</div>
                                <div>TGL. :</div>
                            </td>
                            <td style="vertical-align: bottom; text-align: left; font-style: italic;">
                                <div style="border-bottom: dashed 1px;">NAMA :</div>
                                <div>TGL. :</div>
                            </td>
                            <td style="vertical-align: bottom; text-align: left; font-style: italic;">
                                <div style="border-bottom: dashed 1px;">NAMA :</div>
                                <div>TGL. :</div>
                            </td>
                            <td style="vertical-align: bottom; text-align: left; font-style: italic;">
                                <div style="border-bottom: dashed 1px;">NAMA :</div>
                                <div>TGL. :</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>