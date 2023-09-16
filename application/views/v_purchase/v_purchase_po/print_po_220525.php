<?php
setlocale(LC_ALL, 'id-ID', 'id_ID');
$appName 	= $this->session->userdata('appName');

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
$sqlPRJ		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJNAME= $rowPRJ->PRJNAME;
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
		@page { margin: 0 }
		body { margin: 0 }
		.sheet {
		  margin: 0;
		  overflow: hidden;
		  position: relative;
		  box-sizing: border-box;
		  page-break-after: always;
		}

		/** Paper sizes **/
		body.A3               .sheet { width: 297mm; height: 419mm }
		body.A3.landscape     .sheet { width: 420mm; height: 296mm }
		body.A4               .sheet { width: 210mm; height: 296mm }
		body.A4.landscape     .sheet { width: 297mm; height: 209mm }
		body.A5               .sheet { width: 148mm; height: 209mm }
		body.A5.landscape     .sheet { width: 210mm; height: 147mm }
		body.letter           .sheet { width: 216mm; height: 279mm }
		body.letter.landscape .sheet { width: 280mm; height: 215mm }
		body.legal            .sheet { width: 216mm; height: 356mm }
		body.legal.landscape  .sheet { width: 357mm; height: 215mm }

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
			width: 310px;
			/*border: 1px solid;*/
		}
		.box-header .box-column-2 {
			float: left;
			width: 140px;
			/*border: 1px solid;*/
		}
		.box-header .box-column-5 .logo img {
			margin: 5px auto;
			width: 6.93cm;
		}
		.box-header .box-column-5 .address {
			padding: 5px;
		}
		.box-header .box-column-5 .address span {
			display: block;
			font-family: "Arial";
			font-size: 7pt;
		}
		.box-header .box-column-5 .title {
			margin-left: -10px;
			font-family: "Arial Black";
			font-size: 14pt;
		}
		.box-header .box-column-5 .title-header {
			margin-left: -10px;
			margin-top: 15px;
			font-family: "Arial";
			font-size: 7pt;
		}
		.box-header .box-column-5 .title-header table td {
			padding: 3px;
		}
		.box-header .box-column-2 .header-notes {
			padding: 5px;
			font-family: "Arial";
			font-size: 6pt;
		}
		.box-header .box-column-2 .header-notes table td {
			padding: 5px;
		}
		.box-detail {
			border: 2px solid;
			background-color: rgb(152, 156, 153) !important;
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
		}
		.detail-table table tbody td {
			border-right: 1px dashed;
		}
		.detail-table table tfoot {
			border: 2px solid;
			vertical-align: top;
			background-color: rgb(152, 156, 153) !important;
		}
		.detail-table table tfoot td {
			padding-left: 5px;
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
			font-family: "Arial Black";
			font-size: 8pt;
		}
		.box-notes .box-column-9 {
			float: left;
			width: 500px;
			font-family: "Arial Black";
			font-size: 7pt;
		}
		.box-notes .box-column-9 ol {
			padding-left: 20px;
		}
		.box-notes .box-column-9 ul {
			padding-left: 7px;
		}
		.box-notes .box-column-9 ol li {
			padding-bottom: 5px;
		}
		.box-notes .box-column-9 ol ul li {
			padding-top: 5px;
			font-family: Arial; 
			font-size: 7pt;
		}
		.box-notes .box-column-9 .box-column-li-6 {
			float: left;
			width: 50%;
		}
		.box-notes .box-column-3 {
			float: left;
			width: 237px;
			font-family: "Arial Black";
			font-size: 7pt;
		}
		.box-notes .box-column-3 ol {
			padding-left: 20px;
		}
		.box-notes .box-column-3 ul {
			padding-left: 7px;
		}
		.box-notes .box-column-3 ol li {
			padding-bottom: 5px;
		}
		.box-notes .box-column-3 ol ul li {
			padding-top: 5px;
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
			padding-bottom: 150px;
		}
		.box-asign .box-column-3 span#nama {
			display: block;
		}
		.box-asign .box-column-3 span#jab {
			display: block;
			font-size: 8pt;
			padding-bottom: 5px;
		}
		.page-notes {
			font-family: "Arial";
			font-size: 7pt;
			text-align: center;
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
	 
	  		$a2 = $ex[1]/10;
	  		$pjg = strlen($str);
	  		$i =1;
	    
	  		if($a>=1 && $a< 12){   
	   			$temp .= " ".$string[$a];
	  		}else if($a>12 && $a < 20){   
	   			$temp .= konversi($a - 10)." belas";
	  		}else if ($a>20 && $a<100){   
	   			$temp .= konversi($a / 10)." puluh". konversi($a % 10);
	  		}else{
	   			if($a2<1){
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
	   			$hasil = "minus ".trim(konversi(x));
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
	<section class="page sheet custom">
		<div class="cont">
			<div class="box-header">
			<?php
				$IR_PLN 	= date('Y-m-d');
            	$PAYTYP 	= 0;
            	$PTENOR 	= 0;
            	$sqlPOH 	= "SELECT PO_NUM, PO_CODE, PO_DATE, PR_NUM, PO_NOTES, PO_PLANIR, PO_PAYTYPE, PO_TENOR FROM tbl_po_header WHERE PO_NUM = '$PO_NUM'";
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
			?>
				<div class="box-column-5">
					<div class="logo">
						<img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
					</div>
					<div class="address">
						<span>ITS Tower Niffaro Park Lantai 20 & 21</span>
						<span>Jalan Raya Pasar Minggu Km 18 Jakarta Selatan 15210, Indonesia</span>
					</div>
				</div>
				<div class="box-column-5">
					<div class="title">
						<span>ORDER PEMBELIAN (OP)</span>
					</div>
					<div class="title-header">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="70">Nomor Order</td>
								<td width="5">:</td>
								<td width="50">&nbsp;<b><?php echo $PO_CODE; ?></b></td>
								<td width="70">Tanggal Order</td>
								<td>:</td>
								<td>&nbsp;<b><?php echo $PO_DATE; ?></b></td>
							</tr>
							<tr>
								<td width="70">Nomor SPP/P</td>
								<td width="5">:</td>
								<td>&nbsp;<b><?php echo $PR_CODE; ?></b></td>
								<td width="70">Tanggal SPP/P</td>
								<td width="5">:</td>
								<td>&nbsp;<b><?php echo $PR_DATE; ?></b></td>
							</tr>
							<tr>
								<td width="67">Kode Proyek</td>
								<td width="5">:</td>
								<td colspan="4">&nbsp;<b><?php echo $PRJCODE; ?></b></td>
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
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="100">ALAMAT</td>
							<td>:</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div class="box-column-6" style="border-right: hidden;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="140">MOHON DIKIRIM KE</td>
							<td>:</td>
							<td>&nbsp;</td>
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
							<th width="110">KODE</th>
							<th>KETERANGAN</th>
							<th width="60">VOLUME</th>
							<th width="40">SAT</th>
							<th width="120">HARGA SATUAN</th>
							<th width="50">DSC %</th>
							<th width="110">DISCOUNT (Rp.)</th>
							<th width="120">JUMLAH NET</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sqlPODET 	= "SELECT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEDET, 
											A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT, A.PR_VOLM, A.PO_VOLM, A.IR_VOLM,
											A.IR_AMOUNT, A.IR_PAVG, A.PO_PRICE, A.PO_DISP,
											A.PO_DISC, A.PO_COST, A.PO_DESC, A.PO_DESC1,
											A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
											C.ITM_NAME
					                    FROM tbl_po_detail A
					                    	INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM AND A.PRJCODE = B.PRJCODE
					                      	INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE AND A.PRJCODE = C.PRJCODE
					                    WHERE 
					                      	A.PO_NUM = '$PO_NUM' 
					                      	AND B.PRJCODE = '$PRJCODE'";
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
								$ITM_CODE     	= $row->ITM_CODE;
								$ITM_NAME     	= $row->ITM_NAME;
								$ITM_UNIT     	= $row->ITM_UNIT;
								$ITM_PRICE    	= $row->PO_PRICE;
								$PR_VOLM    	= $row->PR_VOLM;
								$PO_VOLM    	= $row->PO_VOLM;
								$IR_VOLM    	= $row->IR_VOLM;
								$IR_AMOUNT    	= $row->IR_AMOUNT;
								$IR_PAVG    	= $row->IR_PAVG;
								$PO_PRICE     	= $row->PO_PRICE;
								$PO_DISP    	= $row->PO_DISP;
								$PO_DISC    	= $row->PO_DISC;
								$PO_COST    	= $row->PO_COST;
								$TAXPRICE1    	= $row->TAXPRICE1;
								$ITM_TOTP   	= $PO_VOLM * $ITM_PRICE;
								$JML_NET 		= $PO_COST - $PO_DISC;
								$TOTDISC    	= $TOTDISC + $PO_DISC;
								$TOT_COST    	= $TOT_COST + $PO_COST - $PO_DISC;
								$TOTPPN     	= $TOTPPN + $TAXPRICE1;
								$TOTITEM 		= $ITM_TOTP - $TOTDISC;
								$PO_TOTCOST 	= $TOT_COST + $TOTPPN - $TOTDISC;

								if($PO_DISC == 0)
								{
									$PO_DISC = '-';
									$PO_DISP = '-';
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

				              	?>
				              		<tr>
										<td style="text-align: center;"><?php echo $no; ?></td>
										<td style="text-align: center;"><?php echo $PO_CODE; ?></td>
										<td>&nbsp;<?php echo "$PO_DESC"; ?></td>
										<td style="text-align: center;"><?php echo $PO_VOLM; ?></td>
										<td style="text-align: center;"><?php echo $ITM_UNIT; ?></td>
										<td style="text-align: right;"><?php echo number_format($ITM_PRICE, 2); ?>&nbsp;</td>
										<td style="text-align: center;"><?php echo $PO_DISC; ?></td>
										<td style="text-align: center;"><?php echo $PO_DISP; ?></td>
										<td style="text-align: right;"><?php echo number_format($JML_NET, 2); ?>&nbsp;</td>
									</tr>
				              	<?php
				            endforeach;
							$totRow = 45;
							if($no <= $totRow)
							{
								$amRow = $totRow - $no - $Totrem_str;
								for($i=0;$i<$amRow;$i++)
								{
									?>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									<?php
								}
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6" rowspan="3" style="vertical-align: top;">TERBILANG :&nbsp;<i><?php echo terbilang($PO_TOTCOST); ?>&nbsp;rupiah</i></td>
							<td colspan="2" style="text-align: right;">TOTAL :</td>
							<td style="text-align: right;">&nbsp;<?php echo number_format($TOT_COST, 2); ?></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: right;">PPN :</td>
							<td style="text-align: right;">&nbsp;<?php echo number_format($TOTPPN, 2); ?></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: right;">GRAND TOTAL :</td>
							<td style="text-align: right; border-top-style: double;">&nbsp;<?php echo number_format($PO_TOTCOST, 2); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="detail-table-notes">
				<div class="box-column-6" id="company">© PT  NUSA KONSTRUKSI ENJINIRING Tbk</div>
				<div class="box-column-6" id="docfile">Doc.File: IQ221_06.doc,  Auth: WHY</div>
			</div>
		</div>
	</section>
	<section class="page sheet custom">
		<div class="cont">
			<div class="box-notes">
				<div class="box-column-12">CATATAN :</div>
				<div class="box-column-9">
					<ol>
						<li>Pengiriman paling lambat tanggal :</li>
						<li>Syarat Pembayaran :</li>
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
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="box-asign">
				<div class="box-column-3">
					<span id="acc">D i a j u k a n</span>
					<span id="nama">Nama: .................................................</span>
					<span id="jab">Jab.:Unit Pengadaan Barang (Pusat/Proy.).</span>
				</div>
				<div class="box-column-3">
					<span id="acc">M e n y e t u j u i</span>
					<span id="nama">Nama: .................................................</span>
					<span id="jab">Jab.: (Ka.Unit Pengadaan/Ka. Perwakilan /PM)</span>
				</div>
				<div class="box-column-3" style="border-right: hidden;">
					<span id="acc">Pernyataan Setuju (Supplier)</span>
					<span id="nama">Nama: .................................................</span>
					<span id="jab">Jab.: ............................................................</span>
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
		<div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
            <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>
	</section>
</body>
</html>