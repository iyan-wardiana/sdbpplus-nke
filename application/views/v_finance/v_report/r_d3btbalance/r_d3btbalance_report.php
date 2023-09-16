<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 19 Agustus 2020
 * File Name	= r_outvoucpayment_perspl.php
 * Location		= -*/
if($viewType == 1)
{
	$DNOW 	= date('YmdHis');
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LAPHUT_$DNOW.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
date_default_timezone_set("Asia/Bangkok");

// $this->load->view('template/head');

$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');
$LangID 	= $this->session->userdata['LangID'];

$repDay 		= date('Y-m-d');

	//$this->load->view('template/topbar');
	//$this->load->view('template/sidebar');
	
	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl		= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode	= $rowTransl->MLANG_CODE;
		$LangTransl	= $rowTransl->LangTransl;
		
		if($TranslCode == 'DocNumber')$DocNumber = $LangTransl;
        if($TranslCode == 'Date')$Date = $LangTransl;
        if($TranslCode == 'CustName')$CustName = $LangTransl;
        if($TranslCode == 'Color')$Color = $LangTransl;
        if($TranslCode == 'Remarks')$Remarks = $LangTransl;
        if($TranslCode == 'Nominal')$Nominal = $LangTransl;
        if($TranslCode == 'salesPrcCust')$salesPrcCust = $LangTransl;
        if($TranslCode == 'reportedBy')$reportedBy = $LangTransl;
        if($TranslCode == 'ApprovedBy')$ApprovedBy = $LangTransl;
        if($TranslCode == 'knownBy')$knownBy = $LangTransl;
	endforeach;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<style type="text/css">
		/* @page { margin: 0 } */
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
          @page { size: landscape;}
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
			background-color: rgba(72,78,73,.2) !important;
        }
		#Layer1 {
			position: absolute;
			top: 10px;
			left: 10px;
		}
	</style>
	
	<?php
		$vers   = $this->session->userdata['vers'];

		$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
		$rescss = $this->db->query($sqlcss)->result();
		foreach($rescss as $rowcss) :
			$cssjs_lnk1  = $rowcss->cssjs_lnk;
			?>
				<script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
			<?php
		endforeach;
	?>
    
</head>
<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

<body class="page A4 landscape">
    <section class="page sheet custom">
		<div id="Layer1">
            <a href="#" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
		<div class="cont">
			<div class="box-header">
                <div class="box-column-logo">
                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
                </div>
                <div class="box-column-title">
                    <span><?php echo "$h1_title"; ?></span>
                    <span style="display: block; font-size: 10pt; font-style:italic;"><?php echo "Periode: $datePeriod"; ?></span>
                </div>
            </div>
			<div class="box-header-detail">
				<?php
					// if($PRJCODE[0] == 'All') $PRJCODE_D = "Semua";
					// else $PRJCODE_D = implode(", ", $PRJCODE);
				?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
						<tr>
							<th width="100">Kode Proyek</th>
							<th width="10">:</th>
							<th><?php echo "$PRJCODE";?></th>
						</tr>
						<tr>
							<th width="100">Tanggal Cetak</th>
							<th width="10">:</th>
							<th><?php echo date('d/m/Y H:i:s') ?></th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="box-detail">
                <table width="100%" border="1" cellpadding="0" cellspacing="0">
					<thead>
						<tr>	
							<th width="30">No.</th>
							<th>Supplier</th>
							<th width="150">Kode LPM/OPN</th>
							<th width="100">Jumlah LPM/OPN</th>
							<th width="100">Kode Voucher</th>
							<th width="80">Tanggal Voucher</th>
							<th width="100">Jumlah Tagihan</th>
							<th width="100">Kode Bayar</th>
							<th width="80">Tanggal Bayar</th>
							<th width="100">Jumlah Bayar</th>
							<th width="100">Sisa Bayar</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// get PRJ & SPL
							// if($PRJCODE[0] != 'All')
							// {
							// 	$ADDQRYPRJ 	= implode("','", $PRJCODE);
							// 	$ADDQRYPRJ	= "AND A.PRJCODE IN ('$ADDQRYPRJ')";
							// }
							// else
							// {
							// 	$ADDQRYPRJ	= "";
							// }

							if($SPLCODE[0] != 'All')
							{
								$ADDQRYSPL 	= implode("','", $SPLCODE);
								$ADDQRYSPL	= "AND A.SPLCODE IN ('$ADDQRYSPL')";
							}
							else
							{
								$ADDQRYSPL	= "";
							}
							
							$s_HUTSPL 		= "SELECT A.IR_NUM AS DOC_NUM, A.IR_CODE AS DOC_CODE, A.PRJCODE, A.SPLCODE, A.IR_NOTE AS DOC_NOTES, A.IR_AMOUNT_NETT AS DOC_AMN, 
												A.TTK_CODE, A.TTK_DATE, A.INV_CODE, A.INV_DATE, A.BP_CODE, A.BP_DATE 
												FROM tbl_ir_header A WHERE A.IR_STAT IN (3,6) $ADDQRYSPL AND A.PRJCODE = '$PRJCODE'
												UNION
												SELECT A.OPNH_NUM AS DOC_NUM, A.OPNH_CODE AS DOC_CODE, A.PRJCODE, A.SPLCODE, A.OPNH_NOTE AS DOC_NOTES, (A.OPNH_AMOUNT+A.OPNH_AMOUNTPPN-A.OPNH_AMOUNTPPH-A.OPNH_DPVAL-A.OPNH_RETAMN) AS DOC_AMN,
												A.TTK_CODE, A.TTK_DATE, A.INV_CODE, A.INV_DATE, A.BP_CODE, A.BP_DATE 
												FROM tbl_opn_header A WHERE A.OPNH_STAT IN (3,6) $ADDQRYSPL AND A.PRJCODE = '$PRJCODE'";
							$res_HUTSPL		= $this->db->query($s_HUTSPL);
							if($res_HUTSPL->num_rows() > 0)
							{
								$no = 0;
								$GTOT_VOUHER 	= 0;
								$GTOT_PAID 		= 0;
								$INV_AMOUNT_TOT = 0;
								$INV_AMOUNT_PAID= 0;
								$REM_PAID 		= 0;
								$TOTREM_PAID 	= 0;
								$INV_CODE 		= "";
								$INV_DATEV 		= "";
								$INV_DUEDATEV 	= "";
								$BP_CODE 		= "";
								$BP_DATEV 		= "";
								foreach($res_HUTSPL->result() as $rHUTSPL):
									$no 			= $no + 1;
									$DOC_NUM 		= $rHUTSPL->DOC_NUM;
									$DOC_CODE 		= $rHUTSPL->DOC_CODE;
									$PRJCODE 		= $rHUTSPL->PRJCODE;
									$SPLCODE 		= $rHUTSPL->SPLCODE;
									$DOC_NOTES 		= $rHUTSPL->DOC_NOTES;
									$DOC_AMN 		= $rHUTSPL->DOC_AMN;
									$TTK_CODE 		= $rHUTSPL->TTK_CODE;
									$TTK_DATE 		= $rHUTSPL->TTK_DATE;
									$INV_CODE 		= $rHUTSPL->INV_CODE;

									// START: GET VOUCHER LPM/OPN
										$s_VOC 		= "SELECT INV_NUM, INV_CODE, INV_DATE, INV_DUEDATE,	INV_AMOUNT_TOT, INV_AMOUNT_PAID, 
														BP_CODE, BP_DATE 
														FROM tbl_pinv_header WHERE PRJCODE = '$PRJCODE' AND SPLCODE = '$SPLCODE' AND INV_CODE = '$INV_CODE'
														AND INV_STAT IN (3,6)";
										$r_VOC 		= $this->db->query($s_VOC);
										if($r_VOC->num_rows() > 0)
										{
											foreach($r_VOC->result() as $rw_VOC):
												$INV_NUM 			= $rw_VOC->INV_NUM;	
												$INV_CODE 			= $rw_VOC->INV_CODE;	
												$INV_DATE 			= $rw_VOC->INV_DATE;	
												$INV_DUEDATE 		= $rw_VOC->INV_DUEDATE;	
												$INV_DUEDATEV 		= date('d/m/Y', strtotime($INV_DUEDATE));
												$INV_DATEV 			= date('d/m/Y', strtotime($INV_DATE));
												$INV_AMOUNT_TOT 	= $rw_VOC->INV_AMOUNT_TOT;	
												$INV_AMOUNT_PAID 	= $rw_VOC->INV_AMOUNT_PAID;
											endforeach;

											// START: GET BP LPM/OPN
												$s_bp 		= "SELECT A.CB_NUM, A.CB_CODE, A.CBD_DOCNO, A.CBD_DOCCODE, SUM(A.CBD_AMOUNT) AS INV_AMOUNT_PAID, B.CB_DATE FROM tbl_bp_detail A
																INNER JOIN tbl_bp_header B ON B.CB_NUM = A.CB_NUM AND B.PRJCODE = A.PRJCODE
																WHERE B.CB_STAT IN (3,6) AND A.CBD_DOCNO = '$INV_NUM'";
												$r_bp 		= $this->db->query($s_bp);
												if($r_bp->num_rows() > 0)
												{
													foreach($r_bp->result() as $rw_BP):
														$CB_NUM 		= $rw_BP->CB_NUM;
														$BP_CODE 		= $rw_BP->CB_CODE;
														$CBD_DOCNO 		= $rw_BP->CBD_DOCNO;
														$CBD_DOCCODE 	= $rw_BP->CBD_DOCCODE;
														$INV_AMOUNT_PAID= $rw_BP->INV_AMOUNT_PAID;
														$CB_DATE 		= $rw_BP->CB_DATE;
														$BP_DATEV 		= date('d/m/Y', strtotime($CB_DATE));
													endforeach;
													// if($CB_DATE == '') $BP_DATEV = "";
												}
											// END: GET BP LPM/OPN

										}
										else
										{
											
											$INV_AMOUNT_TOT 	= 0;
											$INV_AMOUNT_PAID	= 0;
											$INV_CODE 			= "";
											$INV_DATEV 			= "";
											$INV_DUEDATEV 		= "";
											$BP_CODE 			= "";
											$BP_DATEV 			= "";
										}
									// END: GET VOUCHER LPM/OPN

									
									$REM_PAID 		= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;

									$s_SPL 			= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
									$r_SPL 			= $this->db->query($s_SPL);
									$SPLDESC 		= "";
									if($r_SPL->num_rows() > 0)
									{
										foreach($r_SPL->result() as $rw_SPL):
											$SPLDESC = $rw_SPL->SPLDESC;
										endforeach;
									}

									$GTOT_VOUHER 	= $GTOT_VOUHER + $INV_AMOUNT_TOT;
									$GTOT_PAID 		= $GTOT_PAID + $INV_AMOUNT_PAID;
									$TOTREM_PAID 	= $GTOT_VOUHER - $GTOT_PAID;
									
									?>
										<tr>
											<td style="text-align: center;"><?=$no?></td>
											<td><?php echo "$SPLCODE - $SPLDESC"; ?></td>
											<td style="text-align: center;"><?=$DOC_CODE?></td>
											<td style="text-align: right;"><?php echo number_format($DOC_AMN, 2);?></td>
											<td style="text-align: center;"><?=$INV_CODE?></td>
											<td style="text-align: center;"><?=$INV_DATEV?></td>
											<td style="text-align: right;"><?php echo number_format($INV_AMOUNT_TOT, 2); ?></td>
											<td style="text-align: center;"><?=$BP_CODE?></td>
											<td style="text-align: center;"><?=$BP_DATEV?></td>
											<td style="text-align: right;"><?php echo number_format($INV_AMOUNT_PAID, 2);?></td>
											<td style="text-align: right;"><?php echo number_format($REM_PAID, 2);?></td>
										</tr>
									<?php
								endforeach;
								?>
									<tr>
										<td colspan="6" style="text-align: right; font-weight: bold;">TOTAL</td>
										<td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_VOUHER, 2);?></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td style="text-align: right; font-weight: bold;"><?php echo number_format($GTOT_PAID, 2);?></td>
										<td style="text-align: right; font-weight: bold;"><?php echo number_format($TOTREM_PAID, 2);?></td>
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
<script>
	$(function(){
		$('#Layer1 > a').on('click', function(){
			// $(this).css("visibility", "hidden");
			window.print();
		});
		// window.print();
		document.onkeydown = (event) => {
			console.log(event);
			if (event.ctrlKey) {
				event.preventDefault();
				// sebuah method yang berfungsi untuk mencegah terjadinya event bawaan dari sebuah DOM
			}   
		};

		var mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function(mql) {
			if (mql.matches) {
				console.log('onbeforeprint');
			} else {
				console.log('onafterprint');
				// INSERT tbl_report_status
				// let form_data 	= new FormData();                  
				// form_data.append('PRJCODE', '<?php echo json_decode($PRJCODE); ?>');
				// form_data.append('SPLCODE', '<?php echo json_decode($SPLCODE); ?>');
				// form_data.append('Start_Date', '<?php echo $Start_Date; ?>');
				// form_data.append('End_Date', '<?php echo $End_Date; ?>');

				// fetch("<?php echo site_url('c_finance/c_f1nR3p07t/insReport_Status')?>", {
				// 	method: "POST",
				// 	body: form_data
				// }).then(function(response) {
				// 	return response.json();
				// }).then(function(result) {
				// 	console.log(result);
				// }).catch((error) => {
				// 	console.error('Error:', error);
				// });
			}
		});
	});
</script>