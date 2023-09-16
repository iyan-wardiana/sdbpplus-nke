<?php
$comp_name 		= $this->session->userdata['comp_name'];
$sqlApp 		= "SELECT * FROM tappname";
$resultaApp		= $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
	$comp_add	= $therow->comp_add;
endforeach;

$own_Title 		= '';
$own_Code 		= '';
$own_Name 		= '';
$own_Add1 		= '';

$sqlOwner		= "SELECT B.own_Code, B.own_Title, B.own_Name, B.own_Add1
					FROM tbl_project A
						INNER JOIN tbl_owner B ON B.own_Code = A.PRJOWN
					WHERE A.PRJCODE = '$PRJCODE'";
$ressqlOwner	= $this->db->query($sqlOwner)->result();
foreach($ressqlOwner as $rowqlOwner) :
	$own_Title 		= $rowqlOwner ->own_Title;
	$own_Code 		= $rowqlOwner ->own_Code;
	$own_Name 		= $rowqlOwner ->own_Name;
	$own_Add1 		= $rowqlOwner ->own_Add1;
endforeach;

$sql_prjcont = $this->db->select('PRJCNUM, PRJDATE')->get_where('tbl_project', array('PRJCODE' => $PRJCODE));
if($sql_prjcont->num_rows() > 0):
    foreach($sql_prjcont->result() as $r):
        $Contract_NUM	= $r->PRJCNUM;
        $tgl_Contract	= date('d-m-Y', strtotime($r->PRJDATE));
    endforeach;
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Proyek</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            background-color: #ddd;
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            margin: 50px auto;
            padding: 10px;
            display: grid;
            grid-template-columns: max-content 1fr;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background-color: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
        }

        /** Paper sizes **/
        .container.A3               { width: 297mm; }
        .container.A3.landscape     { width: 420mm; }
        .container.A4               { width: 210mm; }
        .container.A4.landscape     { width: 297mm; }
        .container.A5               { width: 148mm; }
        .container.A5.landscape     { width: 210mm; }
        .container.letter           { width: 216mm; }
        .container.letter.landscape { width: 280mm; }
        .container.legal            { width: 216mm; }
        .container.legal.landscape  { width: 357mm; }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: initial;
            }

            .container.A3.landscape                   { width: 420mm }
            .container.A3, .container.A4.landscape    { width: 297mm }
            .container.A4, .container.A5.landscape    { width: 210mm }
            .container.A5                             { width: 148mm }
            .container.letter, .container.legal       { width: 216mm }
            .container.letter.landscape               { width: 280mm }
            .container.legal.landscape                { width: 357mm }

            .container {
                margin: 0;
                padding: 0;
				border: initial;
				border-radius: initial;
				/* width: initial; */
				/* min-height: initial; */
				box-shadow: initial;
				background: initial;
				page-break-after: always;
		    }
        }

        .box {
            margin: 2px;
            border: 1px solid lightblue;
        }

        .header .title{
            font-weight: bold;
        }

        .header .sub-title{
            font-size: 9pt;
        }

        .header-content {
            grid-column: 1/3;
            grid-row: 2/3;
            text-align: center;
        }

        .sub-header-content {
            grid-column: 1/3;
            grid-row: 3/5;
        }

        .content {
            grid-column: 1/3;
            grid-row: 5/7;
        }

        .footer {
            grid-column: 1/3;
            grid-row: 7/9;
        }

        .heading h3{
            text-decoration: underline;
            letter-spacing: 5px;
        }

        .sub-heading {
            font-size: 10pt;
        }

        .sub-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container A4">
        <div class="box header">
            <div class="logo">
                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="140">
            </div>
        </div>
        <div class="box header">
            <div class="title"><?php echo strtoupper($comp_name); ?></div>
            <div class="sub-title"><?php echo $comp_add; ?></div>
        </div>
        <div class="box header-content">
            <div class="heading"><h3>INVOICE</h3></div>
            <div class="sub-heading">No. : <?php echo $PINV_MANNO; ?></div>
            <div class="sub-heading">Tanggal : <?php echo date('d-m-Y', strtotime($PINV_DATE)); ?></div>
        </div>
        <div class="box sub-header-content">
            <div class="sub-box header-content">
                <div>
                    <table border="0" width="100%">
                        <tr>
                            <td>Kepada :</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;"><?php echo $own_Name; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $own_Add1; ?></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table border="0" width="100%">
                        <tr>
                            <td width="130">Nomor Kontrak</td>
                            <td width="10">:</td>
                            <td><?=$Contract_NUM?></td>
                        </tr>
                        <tr>
                            <td width="130">Tanggal Kontrak</td>
                            <td width="10">:</td>
                            <td><?=$tgl_Contract?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="box content">7</div>
        <div class="box footer">8</div>
    </div>
</body>
</html>