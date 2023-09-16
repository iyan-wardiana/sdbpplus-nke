<?php
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
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
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
                        zoom: 75%;
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
                    padding: 3px;
                    text-align: center;
                    font-size: 9pt;
                    border-top: 2px solid;
                    border-bottom: 2px solid;
                    border-color: black;
                    background-color: darkgrey !important;
                }
                .content-detail table tbody td {
                    padding: 3px;
                    font-size: 9pt;
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
                                <th colspan="11">SURAT PERINTAH KERJA (SPK)</th>
                                <th colspan="4">OPNAME (Summary)</th>
                                <th colspan="2">SISA OPNAME</th>
                            </tr>
                            <tr>
                                <th>No.</th>
                                <th>No. SPK</th>
                                <th>TANGGAL</th>
                                <th>ITEM</th>
                                <th>NAMA ITEM</th>
                                <th>DESKRIPSI</th>
                                <th>Vol. SPK</th>
                                <th>Vol. Batal</th>
                                <th>HARSAT</th>
                                <th>Jumlah Batal</th>
                                <th>Jumlah SPK</th>
                                <th>VOLUME</th>
                                <th>SAT</th>
                                <th>HARSAT</th>
                                <th>JUMLAH HARGA</th>
                                <th>VOL</th>
                                <th>JUMLAH HARGA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $get_SPK    = "SELECT A.WO_NUM, A.WO_CODE, A.WO_DATE, A.PRJCODE, SUM(A.WO_VOLM) TOT_WOVOLM, A.ITM_PRICE,
                                                SUM(A.WO_TOTAL) AS TOT_WO, SUM(A.WO_DISC) AS TOT_WODISC, 
                                                SUM(A.WO_CVOL) AS TOT_WOCVOL, SUM(A.WO_CAMN) AS TOT_WOCAMN, 
                                                SUM(A.TAXPRICE1) AS TOT_PPNAMN, SUM(A.TAXPRICE2) AS TOT_PPHAMN,
                                                SUM(A.OPN_VOLM) AS TOT_OPNVOLM, SUM(A.OPN_AMOUNT) AS TOT_OPNAMN,
                                                B.WO_NOTE
                                                FROM tbl_wo_detail A
                                                INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM AND B.PRJCODE = A.PRJCODE
                                                WHERE B.PRJCODE = '363022' AND B.WO_STAT NOT IN (5,9)
                                                GROUP BY A.WO_NUM";
                                $res_SPK    = $this->db->query($get_SPK);
                                if($res_SPK->num_rows() > 0)
                                {
                                    $no = 0;
                                    $TOT_POT        = 0;
                                    $TOT_WO2        = 0;
                                    $REMTOT_OPNVOLM = 0;
                                    $REMTOT_OPNAMN  = 0;
                                    foreach($res_SPK->result() as $rSPK):
                                        $no             = $no + 1;
                                        $WO_NUM         = $rSPK->WO_NUM;
                                        $WO_CODE        = $rSPK->WO_CODE;
                                        $WO_DATE        = $rSPK->WO_DATE;
                                        $WO_DATEV       = date('d/m/Y', strtotime($WO_DATE));
                                        $PRJCODE        = $rSPK->PRJCODE;
                                        $TOT_WOVOLM     = $rSPK->TOT_WOVOLM;
                                        $ITM_PRICE      = $rSPK->ITM_PRICE;
                                        $TOT_WO         = $rSPK->TOT_WO;
                                        $TOT_WODISC     = $rSPK->TOT_WODISC;
                                        $TOT_WOCVOL     = $rSPK->TOT_WOCVOL;
                                        $TOT_WOCAMN     = $rSPK->TOT_WOCAMN;
                                        $WO_NOTE        = $rSPK->WO_NOTE;
                                        $TOT_PPNAMN     = $rSPK->TOT_PPNAMN;
                                        $TOT_PPHAMN     = $rSPK->TOT_PPHAMN;
                                        $TOT_OPNVOLM    = $rSPK->TOT_OPNVOLM;
                                        $TOT_OPNAMN     = $rSPK->TOT_OPNAMN;

                                        $TOT_POT        = $TOT_WODISC + $TOT_PPHAMN;
                                        $TOT_WO2        = $TOT_WO + $TOT_PPNAMN - $TOT_POT;

                                        if($TOT_OPNVOLM == 0) $OPN_PRICE = 0;
                                        else $OPN_PRICE = $TOT_OPNAMN / $TOT_OPNVOLM;

                                        $REMTOT_OPNVOLM = $TOT_WOVOLM - $TOT_OPNVOLM;
                                        $REMTOT_OPNAMN  = $TOT_WO2 - $TOT_OPNAMN;
                                        
                                        ?>
                                            <tr>
                                                <td><?=$no?></td>
                                                <td><?=$WO_CODE?></td>
                                                <td style="text-align:center;"><?=$WO_DATEV?></td>
                                                <td style="text-align:center;"></td>
                                                <td style="text-align:center;"></td>
                                                <td><?php echo nl2br($WO_NOTE);?></td>
                                                <td><?php echo number_format($TOT_WOVOLM, 3);?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_WOCVOL, 2); ?></td>
                                                <td style="text-align:center;"><?php echo number_format($ITM_PRICE, 2);?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_WOCAMN, 2);?></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_WO2, 2);?></td>
                                                <td style="text-align:center;"><?php echo number_format($TOT_OPNVOLM, 2);?></td>
                                                <td style="text-align:center;"></td>
                                                <td style="text-align: right;"></td>
                                                <td style="text-align: right;"><?php echo number_format($TOT_OPNAMN, 2);?></td>
                                                <td style="text-align:center;"><?php echo number_format($REMTOT_OPNVOLM, 2);?></td>
                                                <td style="text-align: right;"><?php echo number_format($REMTOT_OPNAMN, 2);?></td>
                                            </tr>
                                        <?php
                                    endforeach;
                                }
                                else
                                {
                                    ?>
                                        <tr>
                                            <td colspan="17" style="font-style:italic; text-align:center;">No data available</td>
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