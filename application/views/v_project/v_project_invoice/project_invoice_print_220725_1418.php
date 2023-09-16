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
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #ddd;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }

        .container {
            margin: 50px auto;
            padding: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-areas: 
                'header header header'
                'content content content'
                'footer footer footer'
            ;
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
                background: initial;
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
				/* background-color: initial; */
				page-break-after: always;
		    }
        }

        .header {
            grid-area: header;
            /* background-color: lightblue; */
        }

        .header-title {
            display: grid;
            grid-template-columns: max-content 1fr;
        }

        .sidebar {
            grid-area: sidebar;
            /* background-color: aqua; */
        }

        .content {
            grid-area: content;
            /* background-color: gold; */
        }

        .header-content {
            display: grid;
            grid-template-columns: 1fr 250px;
        }

        .header-content .title {
            grid-column: 1/3;
            grid-row: 1/3;
            text-align: center;
        }
        
        .footer {
            grid-area: footer;
            /* background-color: grey; */
        }
    </style>
</head>
<body>
    <div class="container A4">
        <div class="header">
            <div class="header-title">
                <div class="logo">
                    <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png') ?>" width="140">
                </div>
                <div class="title">
                    <div><?php echo strtoupper($comp_name); ?></div>
                    <div><?php echo $comp_add; ?></div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="header-content">
                <div class="title">
                    <span style="display: block; font-weight: bold; font-size: 12pt; text-decoration: underline; letter-spacing: 5px;">INVOICE</span>
                    <span style="display: block; font-size: 9pt;">No. : <?php echo $PINV_MANNO; ?></span>
                    <span style="display: block; font-size: 9pt;">Tanggal : <?php echo date('d-m-Y', strtotime($PINV_DATE)); ?></span>
                </div>
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
        <div class="footer">footer</div>
    </div>
</body>
</html>