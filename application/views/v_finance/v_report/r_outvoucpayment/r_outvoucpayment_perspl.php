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

<body class="page A4">
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
					if($PRJCODE[0] == 'All') $PRJCODE_D = "Semua";
					else $PRJCODE_D = implode(", ", $PRJCODE);
				?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
						<tr>
							<th width="100">Kode Proyek</th>
							<th width="10">:</th>
							<th><?php echo "$PRJCODE_D";?></th>
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
							<th width="80">Kode Proyek</th>
							<th>Supplier</th>
							<th width="120">Kode Voucher</th>
							<th width="80">Tanggal Voucher</th>
							<th width="80">Tanggal <br>Jatuh Tempo</th>
							<th width="120">Jumlah Tagihan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// get PRJ & SPL
							if($PRJCODE[0] != 'All')
							{
								$ADDQRYPRJ 	= implode("','", $PRJCODE);
								$ADDQRYPRJ1	= "AND A.PRJCODE IN ('$ADDQRYPRJ')";
								$ADDQRYPRJ2	= "AND B.PRJCODE IN ('$ADDQRYPRJ')";
								$ADDQRYPRJ3	= "AND A.proj_Code IN ('$ADDQRYPRJ')";
							}
							else
							{
								$ADDQRYPRJ1	= "";
								$ADDQRYPRJ2	= "";
								$ADDQRYPRJ3	= "";
							}

							if($SPLCODE[0] != 'All')
							{
								$ADDQRYSPL 	= implode("','", $SPLCODE);
								$ADDQRYSPL1	= "AND A.SPLCODE IN ('$ADDQRYSPL')";
								$ADDQRYSPL2	= "AND B.SPLCODE IN ('$ADDQRYSPL')";
							}
							else
							{
								$ADDQRYSPL1	= "";
								$ADDQRYSPL2	= "";
							}

							$VOC_NUM 	= [];
							$s_repstat 	= "SELECT REPDOC_NUM FROM tbl_report_status";
							$r_repstat 	= $this->db->query($s_repstat);
							if($r_repstat->num_rows() > 0)
							{
								foreach($r_repstat->result() as $rw_repstat):
									$VOC_NUM[] 	= $rw_repstat->REPDOC_NUM;
								endforeach;
							}

							$ADDQRYREPINV_STAT 		= "";
							$ADDQRYREPDP_STAT 		= "";
							$ADDQRYREPVCASH_STAT 	= "";
							$ADDQRYREPPD_STAT 		= "";
							if($REPORT_STAT == 0)
							{
								$arrVOCNUM 				= join("','", $VOC_NUM);
								$ADDQRYREPINV_STAT 		= "AND B.INV_NUM NOT IN ('$arrVOCNUM')";
								$ADDQRYREPDP_STAT 		= "AND A.DP_NUM NOT IN ('$arrVOCNUM')";
								$ADDQRYREPVCASH_STAT 	= "AND A.JournalH_Code NOT IN ('$arrVOCNUM')";
								$ADDQRYREPPD_STAT 		= "AND A.JournalH_Code NOT IN ('$arrVOCNUM')";
							}
							elseif($REPORT_STAT == 1)
							{
								$arrVOCNUM 				= join("','", $VOC_NUM);
								$ADDQRYREPINV_STAT 		= "AND B.INV_NUM IN ('$arrVOCNUM')";
								$ADDQRYREPDP_STAT 		= "AND A.DP_NUM IN ('$arrVOCNUM')";
								$ADDQRYREPVCASH_STAT 	= "AND A.JournalH_Code IN ('$arrVOCNUM')";
								$ADDQRYREPPD_STAT 		= "AND A.JournalH_Code IN ('$arrVOCNUM')";
							}

							// get HUT. Supplier
								$get_HUTSPL 	= "SELECT B.INV_NUM, B.INV_CODE, '' AS Reference_Number, B.INV_DATE, B.INV_DUEDATE, B.INV_AMOUNT_TOT, B.INV_AMOUNT_PAID,
													B.PRJCODE, B.SPLCODE, D.SPLDESC 
													FROM tbl_pinv_detail A
													INNER JOIN tbl_pinv_header B ON B.INV_NUM = A.INV_NUM AND B.PRJCODE = A.PRJCODE
													INNER JOIN tbl_ttk_header C ON C.TTK_NUM = A.TTK_NUM AND C.PRJCODE = A.PRJCODE
													INNER JOIN tbl_supplier D ON D.SPLCODE = B.SPLCODE
													WHERE B.INV_PAYSTAT NOT IN ('FP') AND B.INV_STAT IN (3,6)
													$ADDQRYPRJ2 $ADDQRYSPL2 $ADDQRYREPINV_STAT
													AND B.INV_DATE BETWEEN '$Start_Date' AND '$End_Date'
													UNION
													SELECT A.DP_NUM AS INV_NUM, A.DP_CODE AS INV_CODE, '' AS Reference_Number, A.DP_DATE AS INV_DATE, '' AS INV_DUEDATE, A.DP_AMOUN_TOT AS INV_AMOUNT_TOT, 0 AS INV_AMOUNT_PAID,
													A.PRJCODE, A.SPLCODE, B.SPLDESC 
													FROM tbl_dp_header A
													INNER JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
													WHERE A.DP_PAID NOT IN (2) AND A.DP_STAT IN (3,6)
													$ADDQRYPRJ1 $ADDQRYSPL1 $ADDQRYREPDP_STAT
													AND A.DP_DATE BETWEEN '$Start_Date' AND '$End_Date'
													UNION
													SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, '' AS Reference_Number, A.JournalH_Date AS INV_DATE, '' AS INV_DUEDATE, A.GJournal_Total AS INV_AMOUNT_TOT, A.Journal_AmountReal AS INV_AMOUNT_PAID,
													A.proj_Code AS PRJCODE, A.SPLCODE, B.SPLDESC 
													FROM tbl_journalheader_vcash A
													INNER JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
													WHERE A.GEJ_STAT_VCASH NOT IN (6) AND isCanceled = 0 AND A.GEJ_STAT IN (3,6)
													$ADDQRYPRJ3 $ADDQRYSPL1 $ADDQRYREPVCASH_STAT
													AND A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'
													UNION
													SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.Reference_Number, A.PD_Date AS INV_DATE, '' AS INV_DUEDATE, A.GJournal_Total AS INV_AMOUNT_TOT, A.Journal_AmountTsf AS INV_AMOUNT_PAID,
													A.proj_Code AS PRJCODE, A.SPLCODE, B.SPLDESC 
													FROM tbl_journalheader_pd A
													INNER JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
													WHERE A.Journal_Amount != A.Journal_AmountTsf AND A.GEJ_STAT IN (3,6)
													$ADDQRYPRJ3 $ADDQRYSPL1 $ADDQRYREPPD_STAT
													AND A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date'";
													/* ------------------- Hold --------------------------------
													UNION
													SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, A.PD_Date AS INV_DATE, '' AS INV_DUEDATE, A.GJournal_Total AS INV_AMOUNT_TOT, 
													A.SPLCODE AS SPLCODE, B.SPLDESC AS SPLDESC 
													FROM tbl_journalheader_pd A
													INNER JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE
													WHERE (A.Journal_AmountTsf - (A.Journal_AmountReal + A.PPNH_Amount - A.PPHH_Amount) + 
													A.PDPaid_Amount - A.PDRec_Amount) != 0 AND A.GEJ_STAT IN (3,6)";
													----------------------------------------------------------------- */
								$res_HUTSPL		= $this->db->query($get_HUTSPL);
								if($res_HUTSPL->num_rows() > 0)
								{
									$no = 0;
									$GTOT_REMINV = 0;
									foreach($res_HUTSPL->result() as $rHUTSPL):
										$no 			= $no + 1;
										$INV_NUM 		= $rHUTSPL->INV_NUM;
										$INV_CODE 		= $rHUTSPL->INV_CODE;
										$REF_CODE 		= $rHUTSPL->Reference_Number;
										$INV_DATE 		= $rHUTSPL->INV_DATE;
										$INV_DATEV 		= date('d/m/Y', strtotime($INV_DATE));
										$INV_DUEDATE 	= $rHUTSPL->INV_DUEDATE;
										$PRJCODE 		= $rHUTSPL->PRJCODE;
										$INV_AMOUNT_TOT = $rHUTSPL->INV_AMOUNT_TOT;
										$INV_AMOUNT_PAID= $rHUTSPL->INV_AMOUNT_PAID;
										$SPLCODE 		= $rHUTSPL->SPLCODE;
										$SPLDESC 		= $rHUTSPL->SPLDESC;

										$REM_INVAMOUNT 	= $INV_AMOUNT_TOT - $INV_AMOUNT_PAID;
										
										if($INV_DUEDATE == '') $INV_DUEDATEV = '';
										else $INV_DUEDATEV = date('d/m/Y', strtotime($INV_DUEDATE));
										
										if($REM_INVAMOUNT != 0):
											$GTOT_REMINV 	= $GTOT_REMINV + $REM_INVAMOUNT;

										if($REF_CODE != '')
										{
											$INV_CODE = "$INV_CODE<div style='font-style: italic;'>(Ref.No: $REF_CODE)</div>";
										}
										?>
											<tr>
												<td style="text-align: center;"><?=$no?></td>
												<td style="text-align: center;"><?=$PRJCODE?></td>
												<td><?php echo "$SPLCODE - $SPLDESC"; ?></td>
												<td style="text-align: center;"><?=$INV_CODE?></td>
												<td style="text-align: center;"><?=$INV_DATEV?></td>
												<td style="text-align: center;"><?=$INV_DUEDATEV?></td>
												<td style="text-align: right;"><?php echo number_format($REM_INVAMOUNT, 2); ?></td>
											</tr>
										<?php
										endif;
									endforeach;
									?>
										<tr>
											<td colspan="6" style="text-align: right; font-weight:bold;">TOTAL</td>
											<td style="text-align: right; font-weight:bold;"><?php echo number_format($GTOT_REMINV, 2); ?></td>
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
		// window.print();
		$('#Layer1 > a').on('click', function(){
			$(this).css("visibility", "hidden");
			window.print();
		});
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
				let form_data 	= new FormData();                  
				form_data.append('PRJCODE', '<?php echo $PRJCODE; ?>');
				form_data.append('SPLCODE', '<?php echo $SPLCODE; ?>');
				
				// fetch("<?php // echo site_url('__l1y/lockRAPT')?>", {
				// 	method: "POST",
				// 	body: form_data
				// }).then(function(response) {
				// 	return response.json();
				// }).then(function(result) {
				// 	console.log(result);
				// 	const inputArray 	= document.querySelectorAll('input');
				// 	inputArray.forEach(function (input) {
				// 		input.value = "";
				// 	});
				// 	swal({
				// 		title: "Berhasil!",
				// 		text: "Untuk perubahan budget di RAPT, hanya dapat dilakukan melalui RAPP",
				// 		icon: "success",
				// 	})
				// 	.then(function()
				// 	{
				// 		swal.close();
				// 		document.getElementById('idClose1').click();
				// 		document.getElementById('divUnLock').style.display 	= 'none';
				// 		document.getElementById('divLock').style.display 	= '';
				// 		$('#jlist_detail').DataTable().ajax.reload();
				// 	})
				// }).catch((error) => {
				// 	console.error('Error:', error);
				// });
			}
		});
	});
</script>