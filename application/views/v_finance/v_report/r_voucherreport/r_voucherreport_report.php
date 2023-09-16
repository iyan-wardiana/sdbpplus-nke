<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Februari 2018
 * File Name	= r_cashbankreport_report.php
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
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
?>
<!DOCTYPE html>
<html>
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
				font-size: 8pt;
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
					}
				}

			/** Fix for Chrome issue #273306 **/
				@media print {
					@page { 
						size: a4;
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
					left: 10px;
					display: none;
				}

			/* Header */
				.header {
					width: 100%;
					/* background-color: blue; */
				}
				.header .logo {
					width: 200px;
					height: 70px;
					padding-top: 15px;
					/* background-color: red; */
					float: left;
				}
				.header .logo img {
					width: 190px;
				}
				.header .title {
					/* background-color: aqua; */
					width: 100%;
					height: 70px;
					text-align: center;
					font-size: 14pt;
				}
				.header .title div:first-child {
					font-size: 12pt;
					font-weight: bold;
					text-align: center;
				}
				.header .title div:nth-child(2) {
					font-size: 12pt;
					font-weight: bold;
					text-align: center;
				}
				.header .title div:last-child {
					font-size: 9pt;
					font-weight: bold;
					text-align: center;
				}
				.header .header-content {
					width: 100%;
					margin-top: 20px;
				}
				.header .header-content span {
					font-style: italic;
				}
			/* Content */
				.content .content-detail {
					width: 100%;
				}
				.content .content-detail table thead {
					background-color: #CCCCCC !important;
					border: 1px black;
				}
				.content .content-detail table thead th {
					text-align: center;
				}
				.content .content-detail table tbody {
					border: 1px black;
				}
				.content .content-detail table tbody td {
					padding: 2px;
				}

			/* Footer */
		</style>
    </head>

	<body class="page A4">
		<section class="page sheet custom">
			<div id="Layer1">
				<a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
				<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
				<i class="fa fa-download"></i> Generate PDF
				</button>
			</div>
			<div class="header">
				<div class="logo">
					<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
				</div>
				<div class="title">
					<?php echo $h1_title; ?>
				</div>
				<div class="header-content">
                    <table width="100%" border="0">
						<tr>
							<td width="50">Supplier</td>
							<td width="10">:</td>
							<td>
								<?php
									 // get supplier/Employee
									 $SPL_NAME       = "";
									 $s_spl          =  "-- SELECT Emp_ID AS SPL_CODE, CONCAT(First_Name, ' ', Last_Name) AS SPL_NAME 
														 -- FROM tbl_employee WHERE Emp_Status = 1 AND Emp_ID = '$SPLCODE'
														 -- UNION
														 SELECT SPLCODE AS SPL_CODE, SPLDESC AS SPL_NAME FROM tbl_supplier WHERE SPLSTAT = 1 AND SPLCODE = '$SPLCODE'";
									 $r_spl          = $this->db->query($s_spl)->result();
									 foreach($r_spl as $rw_spl) :
										 $SPL_CODE   = $rw_spl->SPL_CODE;
										 $SPL_NAME   = $rw_spl->SPL_NAME;
										 ?>
											 <?php echo "$SPL_CODE - $SPL_NAME"; ?>
										 <?php
									 endforeach;
								?>
							</td>
						</tr>
						<tr>
							<td width="50">Periode</td>
							<td width="10">:</td>
							<td><?php echo date('d-m-Y', strtotime($Start_Date))." s.d. ".date('d-m-Y', strtotime($End_Date)); ?></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="content">
				<div class="content-detail">
					<table width="100%" border="1">
						<thead>
							<tr>
								<th>Pry</th>
								<th>Voucher</th>
								<th>Tanggal</th>
								<th>Item</th>
								<th>Keterangan</th>
								<th>Harga</th>
								<th>Volume</th>
								<th>Nilai</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($PRJCODE[0] != 'All')
								{
									$arrPRJ 	= implode("','", $PRJCODE);
									$addQPRJ 	= "AND A.proj_Code IN('$arrPRJ')";
								}
								else
								{
									$addQPRJ = '';
								}
									

								$q_v1 		= "SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.proj_Code, 
												A.JOBCODEID, A.ITM_PRICE, A.ITM_UNIT, A.ITM_VOLM, A.Other_Desc, B.Manual_No
												FROM tbl_journaldetail_vcash A
												INNER JOIN tbl_journalheader_vcash B ON B.JournalH_Code = A.JournalH_Code
												AND B.proj_Code = A.proj_Code
												WHERE B.SPLCODE = '$SPLCODE' $addQPRJ AND A.JOBCODEID != '' AND B.GEJ_STAT = 3
												UNION
												SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.proj_Code, 
												A.JOBCODEID, A.ITM_PRICE, A.ITM_UNIT, A.ITM_VOLM, A.Other_Desc, B.Manual_No
												FROM tbl_journaldetail_cprj A
												INNER JOIN tbl_journalheader_cprj B ON B.JournalH_Code = A.JournalH_Code
												AND B.proj_Code = A.proj_Code
												WHERE B.SPLCODE = '$SPLCODE' $addQPRJ AND A.JOBCODEID != '' AND B.GEJ_STAT = 3
												UNION
												SELECT A.JournalH_Code, A.JournalH_Date, A.JournalType, A.proj_Code, 
												A.JOBCODEID, A.ITM_PRICE, A.ITM_UNIT, A.ITM_VOLM, A.Other_Desc, B.Manual_No
												FROM tbl_journaldetail_pd A
												INNER JOIN tbl_journalheader_pd B ON B.JournalH_Code = A.JournalH_Code
												AND B.proj_Code = A.proj_Code
												WHERE B.SPLCODE = '$SPLCODE' $addQPRJ AND A.JOBCODEID != '' AND B.GEJ_STAT = 3";
								$resQ_v1	= $this->db->query($q_v1);
								if($resQ_v1->num_rows() > 0)
								{
									$ITM_GTOTAL = 0;
									foreach($resQ_v1->result() as $rV1):
										$JournalH_Code 		= $rV1->JournalH_Code; 	
										$Manual_No 			= $rV1->Manual_No;
										$JournalH_Date 		= $rV1->JournalH_Date;	
										$JournalType 		= $rV1->JournalType;	
										$proj_Code 			= $rV1->proj_Code;	
										$JOBCODEID 			= $rV1->JOBCODEID;	
										$ITM_PRICE 			= $rV1->ITM_PRICE;	
										$ITM_UNIT 			= $rV1->ITM_UNIT;	
										$ITM_VOLM 			= $rV1->ITM_VOLM;
										$ITM_TOTAL 			= $ITM_VOLM * $ITM_PRICE;
										$Other_Desc 		= $rV1->Other_Desc;
										$ITM_GTOTAL 		= $ITM_GTOTAL + $ITM_TOTAL;
										?>
											<tr>
												<td style="text-align: center;"><?php echo $proj_Code; ?></td>
												<td style="text-align: center;"><?php echo $Manual_No; ?></td>
												<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($JournalH_Date)); ?></td>
												<td style="text-align: center;"><?php echo $JOBCODEID; ?></td>
												<td><?php echo $Other_Desc; ?></td>
												<td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?></td>
												<td style="text-align: center;"><?php echo number_format($ITM_VOLM, 3); ?></td>
												<td style="text-align: right;"><?php echo number_format($ITM_TOTAL, 2); ?></td>
											</tr>
										<?php
									endforeach;
									?>
										<tr>
											<td colspan="7" style="text-align: right; font-weight: bold;">TOTAL</td>
											<td style="text-align: right; font-weight: bold;"><?php echo number_format($ITM_GTOTAL, 2); ?></td>
										</tr>
									<?php
								}
							?>
						</tbody>
					</table>
				</div>
				<div class="content-asign">

				</div>
			</div>
			<div class="footer">
			</div>
		</section>
	</body>
</html>
<?php
    if(isset($this->session->userdata['vers']))
        $vers  = $this->session->userdata['vers'];
    else
        $vers  = '2.0.5';
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk1  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
        <?php
    endforeach;
?>
<script>
	$(function(){
		window.print();
		document.onkeydown = (event) => {
			console.log(event);
		    if (event.ctrlKey) {
		        event.preventDefault();
		        // sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
		    }   
		};

		const mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function(mql) {
		    if (mql.matches) {
		        console.log('onbeforeprint equivalent');
		    } else {
		        console.log('onafterprint equivalent');
		        window.opener.location.reload();
				close();
		    }
		});
	});
</script>