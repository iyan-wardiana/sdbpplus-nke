<?php
setlocale(LC_ALL, 'id-ID', 'id_ID');
$appName 		= $this->session->userdata('appName');
$ISPRINT_ORI	= $this->session->userdata('ISPRINT_ORI');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;
$Start_DateY 	= date('Y');
$Start_DateM 	= date('m');
$Start_DateD 	= date('d');
$Start_Date 	= "$Start_DateY-$Start_DateM-$Start_DateD";
$LangID 		= $this->session->userdata['LangID'];

$sql_01	= "SELECT * FROM tappname";
$res_01	= $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
	$comp_name	= $row_01->comp_name;
	$comp_add	= $row_01->comp_add;
	$comp_phone	= $row_01->comp_phone;
	$comp_mail	= $row_01->comp_mail;
endforeach;

$PO_DATEV	= strftime('%d %B %Y', strtotime($PO_DATE));
$PO_DUEDV	= strftime('%d %B %Y', strtotime($PO_DUED));

if($PO_PLANIR != '' || $PO_PLANIR != '0000-00-00')
	$PO_PLANIRV	= strftime('%d %B %Y', strtotime($PO_PLANIR));
else
	$PO_PLANIRV = '';

if($PO_NOTESIR == '') $PO_NOTESIR = "-";
	
$PRJHO          = "KTR";
$sql            = "SELECT PRJNAME, PRJLOCT, PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result         = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME    = $row->PRJNAME;
    $PRJLOCT    = $row->PRJLOCT;
    $PRJHO      = $row->PRJCODE_HO;
endforeach;

$sqlSUPL  	= "SELECT SPLDESC, SPLADD1, SPLADD2, SPLTELP, SPLMAIL, SPLPERS, SPLNPWP
          		FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
$resSUPL  	= $this->db->query($sqlSUPL)->result();
foreach($resSUPL as $rowSUPL) :
	$SPLDESC  = $rowSUPL->SPLDESC;
	$SPLADD1  = $rowSUPL->SPLADD1;
	$SPLADD2  = $rowSUPL->SPLADD2;
	$SPLPERS  = $rowSUPL->SPLPERS;
	$SPLTELP  = $rowSUPL->SPLTELP;
	$SPLMAIL  = $rowSUPL->SPLMAIL;
	$SPLNPWP  = $rowSUPL->SPLNPWP;
endforeach;

$currency = 'rupiah';
if($PO_CURR == 'USD') $currency = 'dolar';

$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
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
    <script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/jquery.min.js'; ?>"></script>
    <script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/qrcode.js'; ?>"></script>

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
		.sheet.custom { padding: 1cm 0.38cm 0.97cm 0.5cm }

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
		}
		.box-header {
			margin-bottom: 10px;
		}
		.box-header .box-column-5 {
			float: left;
			width: 320px;
			/* border: 1px solid; */
		}
		.box-header .box-column-2 {
			float: right;
			width: 120px;
			/*border: 1px solid;*/
		}
		.box-header .box-column-5 .logo img {
			margin: 5px auto;
			width: 6.93cm;
		}
		.box-header .box-column-5 .address {
			padding: 10px;
		}
		.box-header .box-column-5 .address span {
			display: block;
			font-family: "Arial";
			font-size: 7pt;
		}
		.box-header .box-column-5 .title {
			margin-left: -20px;
			font-family: "Arial Black";
			font-size: 14pt;
		}
		.box-header .box-column-5 .title-header {
			margin-left: -20px;
			margin-top: 15px;
			font-family: "Arial";
			font-size: 9pt;
		}
		.box-header .box-column-5 .title-header table td {
			padding: 3px;
		}
		.box-header .box-column-2 .header-notes {
			padding: 5px;
			font-family: "Arial";
			font-size: 5pt;
		}
		.box-header .box-column-2 .header-notes table td {
			padding: 5px;
		}
		.box-detail {
			border: 2px solid;
			background-color: rgba(152, 156, 153, 0.3) !important;
		}
		.box-detail .box-column-6 {
			float: left;
			width: 350px;
			padding: 5px;
			border-right: 1px solid;
		}
		.box-detail .box-column-6 table {
			font-family: "Arial Narrow";
			font-size: 10pt;
			font-weight: bold;
		}
		.box-detail .box-column-6 table tr td {
			padding: 5px;
		}
		.detail-table table {
			font-family: "Arial Narrow";
			font-size: 9pt;
			border-left: 2px solid;
			border-right: 2px solid;
			border-bottom: 2px solid;
		}
		.detail-table table thead th {
			text-align: center;
			border-right: 1px dashed;
			border-bottom: 2px solid;
			padding: 3px;
		}
		.detail-table table tbody td {
			border-right: 1px dashed;
			padding: 3px;
		}
		.detail-table table tfoot {
			border: 2px solid;
			vertical-align: top;
			font-weight: bold;
			background-color: rgba(152, 156, 153, 0.3) !important;
		}
		.detail-table table tfoot td {
			padding: 3px;
			/*padding-bottom: 25px;*/
		}
		.detail-table-notes {
			position: absolute;
			top: 275mm;
			width: 100%;
			font-family: "Calibri";
			font-size: 6pt;
			font-style: italic;
			border-top: 2px solid;
			border-top-color: rgba(0, 0, 0, 0.5);
			display: none;
		}
		.detail-table-notes .box-column-6#company {
			float: left;
		}
		.detail-table-notes .box-column-6#docfile {
			float: right;
		}
		.box-notes {
			border: 2px solid;
		}
		.box-notes .box-column-12 {
			width: 100%;
			padding: 5px;
			font-family: "Arial";
			font-size: 8pt;
		}
		.box-notes .box-column-9 {
			float: left;
			width: 500px;
			font-family: "Arial";
			font-size: 8pt;
		}
		.box-notes .box-column-9 ol {
			padding-left: 20px;
		}
		.box-notes .box-column-9 ul {
			padding-left: 7px;
		}
		.box-notes .box-column-9 ol li {
			padding-bottom: 1px;
		}
		.box-notes .box-column-9 ol ul li {
			padding-top: 1px;
			font-family: Arial; 
			font-size: 7pt;
			font-weight: normal;
		}
		.box-notes .box-column-9 .box-column-li-6 {
			float: left;
			width: 50%;
		}
		.box-notes .box-column-3 {
			float: left;
			width: 237px;
			font-family: "Arial";
			font-size: 8pt;
		}
		.box-notes .box-column-3 ol {
			padding-left: 20px;
		}
		.box-notes .box-column-3 ul {
			padding-left: 7px;
		}
		.box-notes .box-column-3 ol li {
			padding-bottom: 1px;
			font-family: Arial; 
			font-size: 8pt;
			font-weight: normal;
		}
		.box-notes .box-column-3 ol ul li {
			padding-top: 1px;
			font-family: Arial; 
			font-size: 7pt;
		}
		ul.dashed {
			list-style-type: none;
		}
		ul.dashed > li {
			text-indent: -6px;
		}
		ul.dashed > li::before {
			content: "- ";
			text-indent: -5px;
		}
		.box-asign {
			border-left: 2px solid;
			border-right: 2px solid;
			border-bottom: 2px solid;
			font-family: "Arial";
			font-size: 9pt;
			text-align: center;
			vertical-align: top;
		}
		.box-asign .box-column-3 {
			float: left;
			width: 33.3%;
			border-right: 1px solid;
		}
		.box-asign .box-column-3 span#acc {
			display: block;
			padding-top: 5px;
			/*padding-bottom: 50px;*/
			padding-bottom: 10px;
		}
		.box-asign .box-column-3 span#nama {
			display: block;
		}
		.box-asign .box-column-3 span#jab {
			display: block;
			font-size: 8pt;
			padding-bottom: 5px;
		}
		.box-asign .box-column-4 {
			float: left;
			width: 25%;
			border-right: 1px solid;
		}
		.box-asign .box-column-4 span#acc {
			display: block;
			padding-top: 5px;
			/*padding-bottom: 50px;*/
			padding-bottom: 10px;
		}
		.box-asign .box-column-4 span#nama {
			display: block;
		}
		.box-asign .box-column-4 span#jab {
			display: block;
			font-size: 8pt;
			padding-bottom: 5px;
		}
		.page-notes {
			font-family: "Arial";
			font-size: 7pt;
			text-align: center;
		}
		#Layer1 {
			position: absolute;
			top: 10px;
			left: 10px;
		}
		#background{
			position:absolute;
			top: 320px;
			left: 30%;
			z-index:0;
			background:white !important;
			display:block;
			min-height:50%; 
			min-width:50%;
			color:yellow;
		}

		#content{
			position:absolute;
			z-index:1;
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
	<?php
		function konversi($x)
		{
	  		$x = abs($x);
			$angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
			$temp = "";
	  
			if($x < 12){
	   			$temp = " ".$angka[$x];
	 	 	}else if($x<20){
	   			$temp = konversi($x - 10)." belas";
	  		}else if ($x<100){
	   			$temp = konversi($x/10)." puluh". konversi($x%10);
	  		}else if($x<200){
	   			$temp = " seratus".konversi($x-100);
	  		}else if($x<1000){
	   			$temp = konversi($x/100)." ratus".konversi($x%100);   
	  		}else if($x<2000){
	   			$temp = " seribu".konversi($x-1000);
	  		}else if($x<1000000){
	   			$temp = konversi($x/1000)." ribu".konversi($x%1000);   
	  		}else if($x<1000000000){
	   			$temp = konversi($x/1000000)." juta".konversi($x%1000000);
	  		}else if($x<1000000000000){
	   			$temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
	  		}
	  
	  		return $temp;
	 	}
	  
	 	function tkoma($x)
	 	{
	 		$str_dec = ',';
	 		if(strpos($x, $str_dec) == false)
	 			$str_dec = '.';

	  		$str = stristr($x, $str_dec);
	  		$ex = explode($str_dec, $x);

	  		if(($ex[1]/10) >= 1){
	   			$a = abs($ex[1]);
	  		}

	  		$string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
	  		$temp = "";
	 
	  		$a = $ex[1]/10;
	  		$pjg = strlen($str);
	  		$i =1;
	    
	  		if($a>=1 && $a< 12){   
	   			$temp .= " ".$string[$a];
	  		}else if($a>12 && $a < 20){   
	   			$temp .= konversi($a - 10)." belas";
	  		}else if ($a>20 && $a<100){   
	   			$temp .= konversi($a / 10)." puluh". konversi($a % 10);
	  		}else{
	   			if($a<1){
	    			while ($i<$pjg){     
		     			$char = substr($str,$i,1);     
		     			$i++;
	     				$temp .= " ".$string[$char];
	    			}
	   			}
	  		}  

	  		return $temp;
	 	}
	 
		function terbilang($x)
		{
	  		if($x<0){
	   			$hasil = "minus ".trim(konversi($x));
	  		}else{
	  			$str_dec = ',';
		 		if(strpos($x, $str_dec) == false)
		 			$str_dec = '.';

		  		$ex = explode($str_dec, $x);
		  		if(count($ex) != 1)
		  		{
					$poin 	= trim(tkoma($x));
	   				$hasil1 = trim(konversi($x));
	   				$hasil 	= $hasil1." koma ".$poin;
		  		}
		  		else
		  		{
		  			$hasil1 = trim(konversi($x));
		  			$hasil 	= $hasil1;
		  		}

	  		}

	  		return $hasil;  
	 	}
 	?>
</head>
<body class="page A4">
	<?php
		$block_print    = 'block';
		$watermark_text = '';
		if($PO_STAT == 1 || $PO_STAT == 2 || $PO_STAT == 4 || $PO_STAT == 7)
		{
			$watermark_text = "DRAFT";
			$block_print 	= '';
		}
		else
		{
			$watermark_text = "ASLI";
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			$s_CP  		= "tbl_employee_pdoc WHERE P_MENU = '$MNCODE' AND P_EMPID = '$DefEmp_ID' AND P_CANP = '1'";
			$canP 		= $this->db->count_all($s_CP);
			if($canP > 0)
			{
				$watermark_text = "ASLI";
				$block_print 	= 'block';
				if($isPrint == 1)
				{
					$watermark_text = "COPY";
					$block_print 	= 'block';
				}
			}
			else
			{
				$watermark_text = "COPY";
				$block_print 	= 'block';
			}
		}
	?>
	<section class="page sheet custom">
		<div id="Layer1" style="display: <?=$block_print?>;">
            <a href="#" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
		<div id="background">
			<p id="bg-text"><?php echo $watermark_text; ?></p>
		</div>
		<div class="cont">
			
			<div class="box-header">
			<?php
				$IR_PLN 	= date('Y-m-d');
            	$PAYTYP 	= 0;
            	$PTENOR 	= 0;
            	$PODATE 	= "-";
            	$sqlPOH 	= "SELECT PO_NUM, PO_CODE, PO_DATE, PR_NUM, PO_NOTES, PO_PLANIR, PO_PAYTYPE, PO_TENOR, PO_CONFIRMED
            					FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
	            $resPOH   	= $this->db->query($sqlPOH)->result();
	            foreach($resPOH as $row) :
	            	$PO_NUM 	= $row->PO_NUM;
	            	$PO_CODE	= $row->PO_CODE;
	            	$PO_DATE	= date('d M Y', strtotime($row->PO_DATE));
	            	$PR_NUM 	= $row->PR_NUM;
					$PONOTE 	= $row->PO_NOTES;
					$IR_PLN 	= $row->PO_PLANIR;
					$PAYTYP 	= $row->PO_PAYTYPE;
					$PTENOR 	= $row->PO_TENOR;
					$CONFD 		= $row->PO_CONFIRMED;
					if(trim($CONFD) == '')
						$PODATE = "-";
					else
						$PODATE = date('d M Y', strtotime($row->PO_CONFIRMED));
				endforeach;
				if($PAYTYP == 0)
					$PAYTYPD	= "Cash";
				else
					$PAYTYPD	= "Credit";

				$getPR = $this->db->select("PR_NUM, PR_CODE, PR_DATE")->from("tbl_pr_header")->where(["PR_NUM" => $PR_NUM, "PRJCODE" => $PRJCODE])->get();
				$PR_NUM  	= '';
				$PR_CODE 	= '';
				$PR_DATE 	= '';
				if($getPR->num_rows() > 0)
				{
					foreach($getPR->result() as $r):
						$PR_NUM  	= $r->PR_NUM;
						$PR_CODE 	= $r->PR_CODE;
						$PR_DATE 	= date('d M Y', strtotime($r->PR_DATE));
					endforeach;
				}

				$partNo 		= explode('.' , $PO_CODE);
				$partNoC 		= count($partNo);
				$lastPatt_OP	= "".$partNo[$partNoC-1]."";

				$partNo 		= explode('.' , $PR_CODE);
				$partNoC 		= count($partNo);
				$lastPatt_PR	= "".$partNo[$partNoC-1]."";
			?>
				<div class="box-column-5">
					<div class="logo">
						<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
					</div>
					<div class="address">
						<span><?=$comp_add?></span>
					</div>
				</div>
				<div class="box-column-5">
					<div class="title">
						<span>ORDER PEMBELIAN (OP)</span>
					</div>
					<div class="title-header">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="90">Nomor Order</td>
								<td width="5">:</td>
								<td width="50">&nbsp;<b><?php echo $lastPatt_OP; ?></b></td>
								<td width="90">Tanggal Order</td>
								<td>:</td>
								<td>&nbsp;<b><?php echo $PODATE; ?></b></td>
							</tr>
							<tr>
								<td width="90">Nomor SPP/P</td>
								<td width="5">:</td>
								<td>&nbsp;<b><?php echo $lastPatt_PR; ?></b></td>
								<td width="90">Tanggal SPP/P</td>
								<td width="5">:</td>
								<td>&nbsp;<b><?php echo $PR_DATE; ?></b></td>
							</tr>
							<tr>
								<td width="67" style="vertical-align: top;">Kode Proyek</td>
								<td width="5" style="vertical-align: top;">:</td>
								<td colspan="4" style="vertical-align: top;"><b><?php echo "$PRJNAME ($PRJCODE)"; ?></b></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-column-2">
					<div class="header-notes">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="50">Doc. No</td>
								<td width="5">:</td>
								<td>FRM.NKE.07.31</td>
							</tr>
							<tr>
								<td width="50">Rev</td>
								<td width="5">:</td>
								<td>(06/02/20)</td>
							</tr>
							<tr>
								<td width="50">Amd</td>
								<td width="5">:</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="box-detail">
				<div class="box-column-6">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="100">KEPADA YTH.</td>
							<td>:</td>
							<td><?=$SPLDESC?></td>
						</tr>
						<tr>
							<td width="100">ALAMAT</td>
							<td>:</td>
							<td><?=$SPLADD1?></td>
						</tr>
					</table>
				</div>
				<div class="box-column-6" style="border-right: hidden;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="140">MOHON DIKIRIM KE</td>
							<td>:</td>
							<td><?=$PO_RECEIVLOC?></td>
						</tr>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="detail-table">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="30">NO.</th>
							<th width="70">KODE</th>
							<th>KETERANGAN</th>
							<th width="50">VOLUME</th>
							<th width="35">SAT</th>
							<th width="80">HARGA SATUAN</th>
							<th width="80">VOLUME BATAL</th>
							<th width="35">DSC %</th>
							<th width="80">DISCOUNT (Rp.)</th>
							<th width="80" colspan="2">JUMLAH NET</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sqlPODET 	= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEDET, 
											A.JOBCODEID, A.JOBPARDESC, A.ITM_CODE, A.ITM_UNIT, A.ITM_UNIT2,
											SUM(A.PR_VOLM) AS TOTPR_VOLM, SUM(A.PO_VOLM) AS TOTPO_VOLM, 
											SUM(A.PO_CVOL) AS TOTPO_CVOL, SUM(A.PO_CTOTAL) AS TOTPO_CTOTAL,
											SUM(A.IR_VOLM) AS TOTIR_VOLM, SUM(A.IR_AMOUNT) AS TOTIR_AMOUNT, 
											A.IR_PAVG, A.PO_PRICE, A.PO_DISP, SUM(A.PO_DISC) AS TOTPO_DISC, 
											SUM(A.PO_OA) AS TOTPO_OA, SUM(A.PO_COST) AS TOTPO_COST, 
											A.PO_DESC, A.PO_DESC1, A.TAXCODE1, A.TAXCODE2, SUM(A.TAXPRICE1) AS TOTTAX_PPN, 
											SUM(A.TAXPRICE2) AS TOTTAXPPH, C.ITM_NAME
					                    FROM tbl_po_detail A
					                    	INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM AND A.PRJCODE = B.PRJCODE
					                      	INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
					                    WHERE 
					                      	A.PO_NUM = '$PO_NUM' 
					                      	AND B.PRJCODE = '$PRJCODE'
										GROUP BY A.ITM_CODE, A.PO_DESC_ID
										ORDER BY A.JOBCODEID ASC";
				            $resPODET   = $this->db->query($sqlPODET)->result();

				            $GTITMPRICE   	= 0;
				            $PO_TOTCOST   	= 0;
				            $TOTDISC    	= 0;
				            $TOTPPN     	= 0;
				            $TOT_COST    	= 0;
				            $TOTPRICE2    	= 0;
				            $rem_str 		= 0;
				            $Totrem_str		= 0;
				            $no = 0;
				            foreach($resPODET as $row) :
				            	$no 			= $no + 1;
				            	$PR_NUM     	= $row->PR_NUM;
								$PO_CODE    	= $row->PO_CODE;
								$PO_DATE    	= $row->PO_DATE;
								$JOBCODEDET   	= $row->JOBCODEDET;
								$JOBCODEID    	= $row->JOBCODEID;
								$JOBPARDESC 	= $row->JOBPARDESC;
								$ITM_CODE     	= $row->ITM_CODE;
								$ITM_NAME     	= $row->ITM_NAME;
								$ITM_UNIT     	= $row->ITM_UNIT2;
								// $ITM_PRICE    	= $row->PO_PRICE;
								$ITM_PRICEV    	= $row->PO_PRICE;
								// $PR_VOLM    	= $row->PR_VOLM;
								$TOTPR_VOLM 	= $row->TOTPR_VOLM;
								$TOTPO_CVOL 	= $row->TOTPO_CVOL;
								// $PO_VOLM    	= $row->PO_VOLM;
								$TOTPO_VOLM    	= $row->TOTPO_VOLM;
								// $IR_VOLM    	= $row->IR_VOLM;
								$TOTIR_VOLM    	= $row->TOTIR_VOLM;
								// $IR_AMOUNT    	= $row->IR_AMOUNT;
								$TOTIR_AMOUNT   = $row->TOTIR_AMOUNT;
								$IR_PAVG    	= $row->IR_PAVG;
								$PO_PRICE     	= $row->PO_PRICE;
								$PO_DISP    	= $row->PO_DISP;
								// $PO_DISC    	= $row->PO_DISC;
								$TOTPO_DISC    	= $row->TOTPO_DISC;
								$TOTPO_OA 		= $row->TOTPO_OA;
								// $PO_COST    	= $row->PO_COST;
								$TOTPO_COST    	= $row->TOTPO_COST;
								$TOTPO_CTOTAL 	= $row->TOTPO_CTOTAL;
								// $TAXPRICE1    	= $row->TAXPRICE1;
								$TOTTAX_PPN    	= $row->TOTTAX_PPN;
								// $ITM_TOTP   	= $PO_VOLM * $ITM_PRICE;
								//$JML_NET 		= $PO_COST - $PO_DISC;
								// $JML_NET 		= $ITM_TOTP - $PO_DISC;
								$JML_NET 		= ($TOTPO_COST - $TOTPO_OA - $TOTPO_CTOTAL);
								$TOTDISC    	= $TOTDISC + $TOTPO_DISC;
								//$TOT_COST    	= $TOT_COST + $PO_COST - $PO_DISC;
								// $TOT_COST    	= $TOT_COST + $JML_NET;
								$TOTPPN     	= $TOTPPN + $TOTTAX_PPN;
								// $TOTITEM 		= $ITM_TOTP - $TOTDISC;
								//$PO_TOTCOST 	= $TOT_COST + $TOTPPN - $TOTDISC;
								// $PO_TOTCOST 	= $PO_TOTCOST + $JML_NET + $TOTTAX_PPN;

								$ITM_PRICE    	= ($JML_NET + $TOTPO_DISC) / $TOTPO_VOLM;

								if($TOTPO_DISC == 0)
								{
									$TOTPO_DISC = 0;
									$PO_DISP 	= 0;
								}
				              
				              	$PO_DESC    	= $row->PO_DESC;
				              	$lnPO_DESC 		= strlen($PO_DESC);
				              	if($lnPO_DESC >= 20)
				              	{
				              		$rem_str 		= ($lnPO_DESC % 20);
				              		if($rem_str >= 20)
				              		{
				              			$rem_str 		= ($rem_str % 20);
				              		}

				              		if($rem_str < 20)
			              			{
			              				$rem_str = 2;
			              			}

				              		$Totrem_str 	= $Totrem_str + $rem_str;
				              	}

								$JOBDESC 	= "";
								$s_JOBD 	= "SELECT JOBDESC FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
								$r_JOBD 	= $this->db->query($s_JOBD);
								if($r_JOBD->num_rows() > 0)
								{
									foreach($r_JOBD->result() as $rJOBD):
										$JOBDESC 	= $rJOBD->JOBDESC;
									endforeach;
								}

								if($PO_DESC == '') $PO_DESC = $JOBDESC;

								if($TOTPO_DISC != 0)
								  	$TOTPO_DISCV = number_format($TOTPO_DISC, 2);
								else
									$TOTPO_DISCV = '';

				              	?>
				              		<tr style="border-bottom: 2px solid;">
										<td style="text-align: center;"><?php echo $no; ?></td>
										<td style="text-align: center;"><?php echo $ITM_CODE; ?></td>
										<td>
											<!-- <span><?php // echo $JOBPARDESC; ?></span>
											<div class="text-mute" style="font-style: italic;"><?php // echo "$PO_DESC"; ?></div> -->
											<span><?php echo $PO_DESC; ?></span>
										</td>
										<td style="text-align: center;"><?php echo number_format($TOTPO_VOLM, 2); ?></td>
										<td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
										<td style="text-align: right;"><?php echo number_format($ITM_PRICEV, 2); ?></td>
										<td style="text-align: right;"><?php echo number_format($TOTPO_CVOL, 2); ?></td>
										<td style="text-align: center;"><?php echo $PO_DISP; ?></td>
										<td style="text-align: center;"><?php echo $TOTPO_DISCV; ?></td>
										<td colspan="2" style="text-align: right;"><?php echo number_format($JML_NET, 2); ?></td>
									</tr>
									
				              	<?php

								if($TOTPO_OA > 0)
								{
									$no 	 		= $no + 1;
									$ITMPRICE_OA	= $TOTPO_OA / ($TOTPO_VOLM-$TOTPO_CVOL);
									$JML_NETOA 		= $TOTPO_OA;
									?>
										<tr style="border-bottom: 2px solid;">
											<td style="text-align: center;"><?php echo $no; ?></td>
											<td style="text-align: center;"><?php echo $ITM_CODE; ?></td>
											<td>
												<!-- <span><?php // echo $JOBPARDESC; ?></span>
												<div class="text-mute" style="font-style: italic;"><?php // echo "$PO_DESC"; ?></div> -->
												<span>Ongkos Angkut</span>
												<div style="font-style: italic;"><?php echo "($PO_DESC)"; ?></div>
											</td>
											<td style="text-align: center;"><?php echo number_format($TOTPO_VOLM, 2); ?></td>
											<td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
											<td style="text-align: right;"><?php echo number_format($ITMPRICE_OA, 2); ?></td>
											<td style="text-align: right;"><?php echo number_format($TOTPO_CVOL, 2); ?></td>
											<td style="text-align: center;"><?php echo $PO_DISP; ?></td>
											<td style="text-align: center;"><?php echo $TOTPO_DISCV; ?></td>
											<td colspan="2" style="text-align: right;"><?php echo number_format($JML_NETOA, 2); ?></td>
										</tr>
									<?php
									$JML_NET = $JML_NET + $JML_NETOA;
								}

								$TOT_COST    	= $TOT_COST + $JML_NET;
								$PO_TOTCOST 	= $PO_TOTCOST + $JML_NET + $TOTTAX_PPN;
				            endforeach;
						?>
						<tr style="border-top: 2px solid; background-color: rgba(152, 156, 153, 0.3) !important; font-weight: bold; vertical-align: top; font-size:10pt;">
							<td colspan="7" rowspan="3" style="vertical-align: top; border-right: hidden;">TERBILANG :&nbsp;<i><?php echo terbilang(round($PO_TOTCOST,2)); ?>&nbsp;<?=$currency?></i></td>
							<td colspan="3" style="text-align: right; border-right: hidden;">TOTAL :</td>
							<td style="text-align: right;">&nbsp;<?php echo number_format($TOT_COST, 2); ?></td>
						</tr>
						<tr style="background-color: rgba(152, 156, 153, 0.3) !important; font-weight: bold; vertical-align: top; font-size:10pt;">
							<td colspan="3" style="text-align: right; border-right: hidden;">PPN :</td>
							<td style="text-align: right;">&nbsp;<?php echo number_format($TOTPPN, 2); ?></td>
						</tr>
						<tr style="background-color: rgba(152, 156, 153, 0.3) !important; font-weight: bold; vertical-align: top; font-size:10pt;">
							<td colspan="3" style="text-align: right; border-right: hidden;">GRAND TOTAL :</td>
							<td style="text-align: right; border-top-style: double;">&nbsp;<?php echo number_format($PO_TOTCOST, 2); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<table border="0" width="100%">
				<thead>
					<th>
						<div class="cont">
							<div class="box-notes" style="border-top: hidden;">
								<div class="box-column-12">CATATAN :</div>
								<div class="box-column-9">
									<ol>
										<li>
											Pengiriman paling lambat;
											<dd>Tanggal : <span style="font-weight: normal;"><?=$PO_PLANIRV?></span></dd>
											<dd>Keterangan : <span style="font-weight: normal;"><?=$PO_NOTESIR?></span></dd>
										</li>
										<li>
											<span>Syarat Pembayaran :</span>
											<div style="font-weight: normal;"><?=$PO_PAYNOTES?></div>
										</li>
										<li>Order Pembelian (OP) ini tidak berlaku dan Tagihan tidak dapat dibayar, bila :</li>
											<ul class="dashed">
												<li>Tidak mencantumkan No. Order ini ke dalam Surat Jalan & Nota / Faktur tagihan atau Invoice.</li>
												<li>Pesanan dikirim tidak sesuai dengan Spesifikasi Mutu, Harga dan jumlah yang tertulis disini.</li>
												<li>Tidak memenuhi persyaratan K3 (Peraturan Pemerintah No. 50 Tahun 2012)</li>
												<li>Surat jalan tidak tertulis No. GUDANG dan No.LAPORAN PENERIMAAN BARANG (LPB) dari Proyek.</li>
												<li><u>(Surat Jalan asli / OP asli) hilang tanpa ada surat keterangan hilang dari Kepolisian.</u></li>
											</ul class="dashed">
										<li>Tagihan Disampaikan dengan menyerahkan :</li>
										<div class="box-column-li-6">
											<ul class="dashed">
												<li>Kuitansi Bermaterai,</li>
												<li>Nota / Faktur Tagihan atau Invoice,</li>
												<li>Surat Jalan asli dan Order Pembelian (OP) asli,</li>
											</ul class="dashed">
										</div>
										<div class="box-column-li-6">
											<ul class="dashed">
												<li>Faktur  PPN  (Bila ada),</li>
												<li>Berkas² lain yang diperlukan untuk kelengkapan tagihan.</li>
											</ul class="dashed">
										</div>
									</ol>
								</div>
								<div class="box-column-3">
									<ol start="5">
										<li style="font-weight: bold;">Keterlambatan</li>
											<ul class="dashed">
												<li>Keterlambatan Pengiriman Dikenakan Penalty 0.1% per hari, maksimum 5% dari nilai pemesanan.</li>
											</ul class="dashed">
										<li style="font-weight: bold;">Pembatalan / Perubahan OP</li>
											<!-- <ul class="dashed">
												<li>Jika OP batal, maka OP ditarik oleh Unit Pengadaan.</li>
												<li>Jika OP berubah, maka OP ditarik untuk diberikan keterangan perubahan.</li>
											</ul class="dashed"> -->
											<ul class="dashed">
												<li>Jika OP batal / berubah, OP ditarik, diganti OP baru.</li>
											</ul class="dashed">
									</ol>
									<div style="padding-left: 10px;">
										<?php
											if($PO_NOTES == '') $PO_NOTESV = '-';
											else $PO_NOTESV = nl2br($PO_NOTES);
										?>
										<span style="font-weight: bold; font-size: 9pt;">Catatan Tambahan :</span>
										<div style="font-weight:normal; font-size: 7pt;"><?php echo $PO_NOTESV; ?></div>
									</div>
								</div>
								<!-- <div class="box-column-3">
									<ol start="5">
										<li>Kode item</li>
											<ul class="dashed">
												<li>Kode item OP diatas diisi sesuai  kode item SPP/P</li>
											</ul class="dashed">
										<li>Pembatalan / Perubahan OP</li>
											<ul class="dashed">
												<li>Jika OP batal, maka OP ditarik oleh Unit Pengadaan.</li>
												<li>Jika OP berubah, maka OP ditarik untuk diberikan keterangan perubahan.</li>
											</ul class="dashed">
									</ol>
								</div> -->
								<div class="clearfix"></div>
							</div>
	                        <?php
	                            // Approver 01
	                                $APPD_01    = "";
									$APPDV_01   = "";
	                                $APP_01     = "";
	                                $COMPIL_01  = "";
	                                $POSIT_01   = "";
	                                $s_01       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
	                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
	                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
	                                                WHERE AH_CODE = '$PO_NUM' AND AH_APPLEV = 1";
	                                $r_01       = $this->db->query($s_01)->result();
	                                foreach($r_01 as $rw_01) :
	                                    $APPD_01    = $rw_01->AH_APPROVED;
										$APPDV_01 	= "Tanggal ".date("d/m/Y", strtotime($APPD_01));
	                                    $APP_01     = $rw_01->complName;
	                                    $COMPIL_01  = $APP_01." ".$APPD_01;
	                                    $POSIT_01   = $rw_01->POSS_NAME;
	                                endforeach;

	                            // Approver 02
	                                $APPD_02    = "";
									$APPDV_02   = "";
	                                $APP_02     = "";
	                                $COMPIL_02  = "";
	                                $POSIT_02   = "";
	                                $s_02       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
	                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
	                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
	                                                WHERE AH_CODE = '$PO_NUM' AND AH_APPLEV = 2";
	                                $r_02       = $this->db->query($s_02)->result();
	                                foreach($r_02 as $rw_02) :
	                                    $APPD_02    = $rw_02->AH_APPROVED;
										$APPDV_02 	= "Tanggal ".date("d/m/Y", strtotime($APPD_02));
	                                    $APP_02     = $rw_02->complName;
	                                    $COMPIL_02  = $APP_02." ".$APPD_02;
	                                    $POSIT_02   = $rw_02->POSS_NAME;
	                                endforeach;

	                            // Approver 03
	                                $APPD_03    = "";
									$APPDV_03   = "";
	                                $APP_03     = "";
	                                $COMPIL_03  = "";
	                                $POSIT_03   = "";
	                                $s_03       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
	                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
	                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
	                                                WHERE AH_CODE = '$PO_NUM' AND AH_APPLEV = 3";
	                                $r_03       = $this->db->query($s_03)->result();
	                                foreach($r_03 as $rw_03) :
	                                    $APPD_03    = $rw_03->AH_APPROVED;
										$APPDV_03 	= "Tanggal ".date("d/m/Y", strtotime($APPD_03));
	                                    $APP_03     = $rw_03->complName;
	                                    $COMPIL_03  = $APP_03." ".$APPD_03;
	                                    $POSIT_03   = $rw_03->POSS_NAME;
	                                endforeach;

	                            // Approver 04
	                                $APPD_04    = "";
									$APPDV_04   = "";
	                                $APP_04     = "";
	                                $COMPIL_04  = "";
	                                $POSIT_04   = "";
	                                $s_04       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
	                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
	                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
	                                                WHERE AH_CODE = '$PO_NUM' AND AH_APPLEV = 4";
	                                $r_04       = $this->db->query($s_04)->result();
	                                foreach($r_04 as $rw_04) :
	                                    $APPD_04    = $rw_04->AH_APPROVED;
										$APPDV_04 	= "Tanggal ".date("d/m/Y", strtotime($APPD_04));
	                                    $APP_04     = $rw_04->complName;
	                                    $COMPIL_04  = $APP_04." ".$APPD_04;
	                                    $POSIT_04   = $rw_04->POSS_NAME;
	                                endforeach;

	                            // Approver 05
	                                $APPD_05    = "";
									$APPDV_05   = "";
	                                $APP_05     = "";
	                                $COMPIL_05  = "";
	                                $POSIT_05   = "";
	                                $s_05       = "SELECT A.AH_APPROVED, CONCAT(B.First_Name,' ', B.Last_Name) AS complName, C.POSS_NAME
	                                                FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
	                                                    LEFT JOIN tbl_position_str C ON B.Pos_Code = C.POSS_CODE
	                                                WHERE AH_CODE = '$PO_NUM' AND AH_APPLEV = 5";
	                                $r_05       = $this->db->query($s_05)->result();
	                                foreach($r_05 as $rw_05) :
	                                    $APPD_05    = $rw_05->AH_APPROVED;
										$APPDV_05 	= "Tanggal ".date("d/m/Y", strtotime($APPD_05));
	                                    $APP_05     = $rw_05->complName;
	                                    $COMPIL_05  = $APP_05." ".$APPD_05;
	                                    $POSIT_05   = $rw_05->POSS_NAME;
	                                endforeach;

	                            if($PRJHO == 'NKE')         // PROYEK
	                                $TOT_SIGN   = 3;
	                            elseif($PRJHO == 'KTR')     // KTR
	                                $TOT_SIGN   = 3;
	                            else                        // AB
	                                $TOT_SIGN   = 2;

	                            $COLW           = 170;
	                            $COLCLS     	= "box-column-4";
	                            if($TOT_SIGN == 2)
	                            {
	                                $COLW       = 255;
	                                $COLCLS     = "box-column-3";
	                            }
	                        ?>
							<div class="box-asign">
								<div class="<?=$COLCLS?>" style="<?php if($TOT_SIGN == 3) { ?> display: none; <?php } ?>">
									<span id="acc"><?=$POSIT_01?><br></span>
                                    <input id="app_01" type="app_01" value="<?php echo $COMPIL_01; ?>" style="width:80%; display:none" />
									<div id="qrc_01"></div>
									<div id="dt_app01" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPDV_01; ?></span></div>
								</div>
								<div class="<?=$COLCLS?>" >
									<span id="acc"><?=$POSIT_02?><br></span>
                                    <input id="app_02" type="app_02" value="<?php echo $COMPIL_02; ?>" style="width:80%; display:none" />
									<div id="qrc_02"></div>
									<div id="dt_app02" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPDV_02; ?></span></div>
								</div>
								<div class="<?=$COLCLS?>" style="<?php if($PRJHO == 'AB' || $PRJHO == 'NKE') { ?> display: none; <?php } ?>" >
									<span id="acc"><?=$POSIT_03?><br></span>
                                    <input id="app_03" type="app_03" value="<?php echo $COMPIL_03; ?>" style="width:80%; display:none" />
									<div id="qrc_03"></div>
									<div id="dt_app03" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPDV_03; ?></span></div>
								</div>
								<div class="<?=$COLCLS?>" style="<?php if($TOT_SIGN == 2) { ?> display: none; <?php } ?>" >
									<span id="acc"><?=$POSIT_04?><br></span>
                                    <input id="app_04" type="app_04" value="<?php echo $COMPIL_04; ?>" style="width:80%; display:none" />
									<div id="qrc_04"></div>
									<div id="dt_app04" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPDV_04; ?></span></div>
								</div>
								<div class="<?=$COLCLS?>" style="<?php if($TOT_SIGN == 2 || $PRJHO == 'KTR') { ?> display: none; <?php } ?>" >
									<span id="acc"><?=$POSIT_05?><br></span>
                                    <input id="app_05" type="app_05" value="<?php echo $COMPIL_05; ?>" style="width:80%; display:none" />
									<div id="qrc_05"></div>
									<div id="dt_app05" style="padding-top: 5px;"><span style="font-style: italic;"><?php echo $APPDV_05; ?></span></div>
								</div>
								<div class="<?=$COLCLS?>" style="border-right: hidden;">
									<span id="acc">Pernyataan Setuju Supplier</span><br><br><br>
									<span id="nama">Nama: .....................</span>
									<span id="jab">Jab.: .....................</span>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="detail-table-notes">
								<div class="box-column-6" id="company">© PT  NUSA KONSTRUKSI ENJINIRING Tbk</div>
								<div class="box-column-6" id="docfile">Doc.File: IQ221_06.doc,  Auth: WHY</div>
							</div>
						</div>
						<div class="page-notes">
							<b>Distribusi:</b> Original : Supplier, Copy : Arsip Pengadaan (Pusat/ Proyek),  Copy : Gudang/Peralatan/Penerima Barang
						</div>
						<div class="detail-table-notes">
							<div class="box-column-6" id="company">© PT  NUSA KONSTRUKSI ENJINIRING Tbk</div>
							<div class="box-column-6" id="docfile">Doc.File: IQ221_06.doc,  Auth: WHY</div>
						</div>
					</th>
				</thead>
			</table>
		</div>
	</section>
</body>
</html>
<style>
#qrc_01 {
  display: table;
  margin: 0 auto;
}
#qrc_02 {
  display: table;
  margin: 0 auto;
}
#qrc_03 {
  display: table;
  margin: 0 auto;
}
#qrc_04 {
  display: table;
  margin: 0 auto;
}
#qrc_05 {
  display: table;
  margin: 0 auto;
}
</style>
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
	    var PO_STAT 	= <?php echo $PO_STAT ?>;
		var ISPRINT_ORI	= <?php echo $ISPRINT_ORI ?>;
		$('#Layer1 > a').on('click', function(){
			$(this).css("visibility", "hidden");
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
				// Update isPrint = 1
				if(PO_STAT == 3 || PO_STAT == 6 && ISPRINT_ORI == 1)
				{
					let PO_CODE = '<?php echo $PO_CODE; ?>';
					let isPrint = '<?php echo $isPrint; ?>';
    				$.ajax({
    					url: "<?php echo base_url('c_purchase/c_p180c21o/watermark_upd/?id='.$this->url_encryption_helper->encode_url($PO_NUM)); ?>",
						type: "POST",
						data: {PO_CODE:PO_CODE, isPrint:isPrint},
    					success: () => {
    						close();
    					}
    				});
				}
		    }
		});
	})

    var qrc_01 = new QRCode(document.getElementById("qrc_01"), {
        width : 80,
        height : 80
    });

    var qrc_02 = new QRCode(document.getElementById("qrc_02"), {
        width : 80,
        height : 80
    });

    var qrc_03 = new QRCode(document.getElementById("qrc_03"), {
        width : 80,
        height : 80
    });

    var qrc_04 = new QRCode(document.getElementById("qrc_04"), {
        width : 80,
        height : 80
    });

	var qrc_05 = new QRCode(document.getElementById("qrc_05"), {
        width : 80,
        height : 80
    });

    function makeCode ()
    {      
        var elText_01 = document.getElementById("app_01");
        var elText_02 = document.getElementById("app_02");
        var elText_03 = document.getElementById("app_03");
        var elText_04 = document.getElementById("app_04");
		var elText_05 = document.getElementById("app_05");

        if(elText_01.value != '')
        {
            if (!elText_01.value)
            {
                alert("Input a text");
                elText_01.focus();
                return;
            }
            qrc_01.makeCode(elText_01.value);
        }
        
        if(elText_02.value != '')
        {
            if (!elText_02.value)
            {
                alert("Input a text 2");
                elText_02.focus();
                return;
            }
            qrc_02.makeCode(elText_02.value);
        }

        if(elText_03.value != '')
        {
            if (!elText_03.value)
            {
                alert("Input a text 3");
                elText_03.focus();
                return;
            }
            qrc_03.makeCode(elText_03.value);
        }

        if(elText_04.value != '')
        {
            if (!elText_04.value)
            {
                alert("Input a text 4");
                elText_04.focus();
                return;
            }
            qrc_04.makeCode(elText_04.value);
        }

		if(elText_05.value != '')
        {
            if (!elText_05.value)
            {
                alert("Input a text 5");
                elText_05.focus();
                return;
            }
            qrc_05.makeCode(elText_05.value);
        }
    }

    makeCode();
</script>