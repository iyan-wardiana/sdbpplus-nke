<?php
	date_default_timezone_set("Asia/Bangkok");
	
	class moneyFormat
	{ 
	    public function rupiah ($angka) 
	    {
	        $rupiah = number_format($angka ,2, ',' , '.' );
	        return $rupiah;
	    }
	 
	    public function terbilang ($angka)
	    {
	        $angka = (float)$angka;
	        $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
	        if ($angka < 12) {
	            return $bilangan[$angka];
	        } else if ($angka < 20) {
	            return $bilangan[$angka - 10] . ' Belas';
	        } else if ($angka < 100) {
	            $hasil_bagi = (int)($angka / 10);
	            $hasil_mod = $angka % 10;
	            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
	        } else if ($angka < 200) {
	            return sprintf('Seratus %s', $this->terbilang($angka - 100));
	        } else if ($angka < 1000) {
	            $hasil_bagi = (int)($angka / 100);
	            $hasil_mod = $angka % 100;
	            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
	        } else if ($angka < 2000) {
	            return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
	        } else if ($angka < 1000000) {
	            $hasil_bagi = (int)($angka / 1000); 
	            $hasil_mod = $angka % 1000;
	            return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
	        } else if ($angka < 1000000000) {
	            $hasil_bagi = (int)($angka / 1000000);
	            $hasil_mod = $angka % 1000000;
	            return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
	        } else if ($angka < 1000000000000) {
	            $hasil_bagi = (int)($angka / 1000000000);
	            $hasil_mod = fmod($angka, 1000000000);
	            return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
	        } else if ($angka < 1000000000000000) {
	            $hasil_bagi = $angka / 1000000000000;
	            $hasil_mod = fmod($angka, 1000000000000);
	            return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
	        } else {
	            return 'Data Salah';
	        }
	    }
	}

	$moneyFormat = new moneyFormat();

	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];


	if($DROP_STAT == 0)
	{
		// Create DROP_CODE
		$query	= $this->db->query("SELECT MAX(RIGHT(DROP_CODE, 4)) AS MAX_NO FROM tbl_drop_document WHERE DROP_STATD = 3");
		if($query->num_rows() > 0)
		{
			// $data	= $query->row();
			// $kode	= intval($data->kode) + 1;
			foreach($query->result() as $rw_max):
				$last_kode = (int)$rw_max->MAX_NO;
			endforeach;
			if($last_kode == '')
				$last_kode = 0;
			$myMax = $last_kode + 1;
		}
		else
		{
			$myMax	= 1;
		}

		$kodemax	= str_pad($myMax, 4, "0", STR_PAD_LEFT);
		$date_code 	= date('ymd');
		$DROP_NUM 	= "D".date('YmdHis');
		$DROP_CODE 	= "$date_code-$kodemax";
		$DROP_DATE 	= date('Y-m-d');
	}
	else
	{
		$DROP_CODE 	= $DROP_No;
		$kodemax	= substr($DROP_CODE, -4);
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $h1_title; ?></title>
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
            /*background-color: gold;*/
        }
        .box-header .box-column-logo {
            float: left;
            width: 20%;
            /*border: 1px solid;*/
        }
        .box-header .box-column-logo img {
            margin: 9px auto;
            width: 5cm;
        }
        .box-header .box-column-title {
            position: absolute;
            top: 10px;
            float: left;
            width: 100%;
            height: 50px;
            padding-top: 15px;
            /*border: 1px solid;*/
            text-align: center;
            /*background-color: green;*/
        }
        .box-header .box-column-title > span {
            font-size: 12pt;
            font-weight: bold;
        }
        .box-header .pageno {
        	float: right;
        	width: 200px;
        	margin-top: 15px;
        	/*background-color: yellow;*/
        }
        .box-header .pageno table td {
        	border: hidden;
        }
        .box-detail {
        	width: 100%;
        	margin-top: 10px;
        }
        .box-detail table thead th, tbody td, tfoot td {
        	border-top: 1px dashed rgba(140, 145, 141, 0.3);
        	border-bottom: 1px dashed rgba(140, 145, 141, 0.3);
        	padding: 3px;
        	font-family: Courier, monospace;
        }
        .box-detail table thead th {
        	text-align: center;
        }
        .box-asign {
        	position: absolute;
        	top: 850px;
        	width: 100%;
        	margin-top: 30px;
        }
        .box-asign table td {
        	border: hidden;
        	text-align: center;
        }
        .box-asign table td #pos, #name {
        	display: block;
        	padding-bottom: 70px;
        }
        #Layer1 {
        	position: absolute;
        	top: 30px;
        	left: 740px;
        }
    </style>
</head>
<body class="page A4">
	<form name="frmDropDoc" id="frmDropDoc" method="post" action="">
		<input type="hidden" name="DROP_STAT" id="DROP_STAT" value="<?php echo $DROP_STAT; ?>">
		<?php
			$maxRow 	= 25;
			$page 		= 1;
			$tot_page 	= 1;
			$RowAM 		= 0;

			if($PRJCODE[0] != 1)
			{
				$ArrPRJCODE 	= join("','", $PRJCODE);
				$addQPRJ 		= "AND A.proj_Code IN ('$ArrPRJCODE')";
				$addQPRJINV 	= "AND A.PRJCODE IN ('$ArrPRJCODE')";
			}
			else
			{
				$addQPRJ 		= "";
				$addQPRJINV 	= "";
			}

			if($DROP_STAT == 0)
			{
				if($VOUCHER[0] != 1) 
				{
					$ArrVOUCHER 	= join("','", $VOUCHER);
					$addQ_VOC 		= "AND A.JournalH_Code IN ('$ArrVOUCHER')";
					$addQINV_VOC 	= "AND A.INV_NUM IN ('$ArrVOUCHER')";
					if($cDrop > 0) 
					{
						$ArrDROP_REF 	= join("','", $DROP_REF2NUM);
						$addQ_VOC 		= "AND A.JournalH_Code IN ('$ArrVOUCHER') AND A.JournalH_Code NOT IN ('$ArrDROP_REF')";
						$addQINV_VOC 	= "AND A.INV_NUM IN ('$ArrVOUCHER') AND A.INV_NUM NOT IN ('$ArrDROP_REF')";
					}
				}
				else
				{
					if($cDrop > 0) 
					{
						$ArrDROP_REF 	= join("','", $DROP_REF2NUM);
						$addQ_VOC 		= "AND A.JournalH_Code NOT IN ('$ArrDROP_REF')";
						$addQINV_VOC	= "AND A.INV_NUM NOT IN ('$ArrDROP_REF')";
					}
				}
			}
			else
			{
				if($VOUCHER_DROP[0] != 1) 
				{
					$ArrVDROP 		= join("','", $VOUCHER_DROP);
					$addQ_VOC 		= "AND A.JournalH_Code IN ('$ArrVDROP')";
					$addQINV_VOC 	= "AND A.INV_NUM IN ('$ArrVDROP')";
				}
				else 
				{
					$ArrDROP_REF 	= join("','", $DROP_REF2NUM);
					$addQ_VOC 		= "AND A.JournalH_Code IN ('$ArrDROP_REF')";
					$addQINV_VOC 	= "AND A.INV_NUM IN ('$ArrDROP_REF')";
				}
			}


			$QVOC 		= "SELECT * FROM
							(
								SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, '' AS TTK_NUM, '' AS TTK_CODE,
									A.JournalH_Date AS INV_DATE, A.proj_Code AS PRJCODE, A.JournalH_Desc AS INV_NOTES,
									A.GJournal_Total AS INV_AMOUNT_TOT, A.PPNH_Amount AS INV_PPN, A.PPHH_Amount AS INV_PPH,
									A.PERSL_EMPID, A.SPLCODE, A.Emp_ID AS EmpID
								FROM
									tbl_journalheader_vcash A
								WHERE
									A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' 
									$addQPRJ
									$addQ_VOC
									AND A.JournalType = 'VCASH' 
									AND A.GEJ_STAT IN (2,3,6)
								UNION
								SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, '' AS TTK_NUM, '' AS TTK_CODE,
									A.JournalH_Date AS INV_DATE, A.proj_Code AS PRJCODE, A.Other_Desc AS INV_NOTES,
									SUM(A.JournalD_Debet) AS INV_AMOUNT_TOT, SUM(A.PPN_Amount) AS INV_PPN, SUM(A.PPH_Amount) AS INV_PPH,
									B.PERSL_EMPID, B.SPLCODE, B.Emp_ID AS EmpID
								FROM tbl_journaldetail_pd A
								INNER JOIN tbl_journalheader_pd B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
								WHERE
									A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' 
									$addQPRJ
									$addQ_VOC
									AND A.JournalType = 'CHO-PD' 
									AND A.GEJ_STAT IN (2,3,6) AND A.ISPERSL_REALIZ = 0
								UNION
								SELECT A.JournalH_Code AS INV_NUM, A.Manual_No AS INV_CODE, '' AS TTK_NUM, '' AS TTK_CODE,
									A.JournalH_Date AS INV_DATE, A.proj_Code AS PRJCODE, A.Other_Desc AS INV_NOTES,
									SUM(A.JournalD_Debet) AS INV_AMOUNT_TOT, SUM(A.PPN_Amount) AS INV_PPN, SUM(A.PPH_Amount) AS INV_PPH,
									B.PERSL_EMPID, B.SPLCODE, B.Emp_ID AS EmpID
								FROM tbl_journaldetail_pd A
								INNER JOIN tbl_journalheader_pd B ON B.JournalH_Code = A.JournalH_Code AND B.proj_Code = A.proj_Code
								WHERE
									A.JournalH_Date BETWEEN '$Start_Date' AND '$End_Date' 
									$addQPRJ
									$addQ_VOC
									AND A.JournalType = 'CHO-PD' 
									AND A.GEJ_STAT IN (2,3,6) AND A.ISPERSL_REALIZ = 1
								UNION
									SELECT A.INV_NUM, A.INV_CODE AS INV_CODE, B.TTK_NUM, B.TTK_CODE, A.INV_DATE, A.PRJCODE, A.INV_NOTES, 
									A.INV_AMOUNT_TOT, A.INV_AMOUNT_PPN AS INV_PPN, A.INV_AMOUNT_PPH AS INV_PPH,
									'' AS PERSL_EMPID, A.SPLCODE AS SPLCODE, A.CREATER AS EmpID
								FROM
									tbl_pinv_header A
								INNER JOIN tbl_pinv_detail B ON B.INV_NUM = A.INV_NUM AND B.PRJCODE = A.PRJCODE
								WHERE
									A.INV_DATE BETWEEN '$Start_Date' AND '$End_Date'
									$addQPRJINV
									$addQINV_VOC
									AND A.INV_STAT IN (2,3,6)
							) dumTbl_Voucher
							ORDER BY INV_CODE ASC";
			/* ----------------------------------------------------------------------------------------------------------
				$this->db->from("tbl_journalheader_vcash A");
				$this->db->join("tbl_pinv_detail B", "B.INV_NUM = A.JournalH_Code AND B.PRJCODE = A.proj_Code", "LEFT");
				// $this->db->where_in("A.SPLCODE", $SPLCODE);
				$this->db->where_in("A.PERSL_EMPID", $SPLCODE);
				if($PRJCODE[0] != 1) $this->db->where_in("A.proj_Code", $PRJCODE);
				if($DROP_STAT == 0)
				{
					if($VOUCHER[0] != 1) $this->db->where_in("A.JournalH_Code", $VOUCHER);
					elseif($cDrop > 0) $this->db->where_not_in("A.JournalH_Code", $DROP_REF2NUM);
				}
				else
				{
					if($VOUCHER_DROP[0] != 1) 
					{
						$this->db->where_in("A.JournalH_Code", $VOUCHER_DROP);
					}
					else 
					{
						$this->db->where_in("A.JournalH_Code", $DROP_REF2NUM);
					}
				}
				$this->db->where("A.JournalH_Date >=", date('Y-m-d', strtotime($Start_Date)));
				$this->db->where("A.JournalH_Date <=", date('Y-m-d', strtotime($End_Date)));
				// $this->db->where("A.Emp_ID", $DefEmp_ID);
				$this->db->where_not_in("A.JournalType", ["BP","GEJ"]);
				$this->db->where_in("A.GEJ_STAT", [3]);
				$query = $this->db->get();
			------------------------------------------------------------------------------------------------------------------- */
			$query = $this->db->query($QVOC);
			$RowAM = $query->num_rows();
			if($RowAM > $maxRow)
			{
				$tot_page = ceil($RowAM/$maxRow); // ceil() adalah fungsi pembulatan angka ke atas
				// echo "page1: $page1<br>";
			}
			else
			{
				$tot_page = $page;
			}

			// echo "$RowAM - $tot_page";
			// return false;
			$Journal_GTotAmount = 0;
			$no = 0;
			for($i = 0; $i < $tot_page; $i++):
				// echo "$i<br>";
				?>
					<section class="page sheet custom">
						<div class="cont">
							<div class="box-header">
								<div class="box-column-logo">
				                    <img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg">
				                </div>
				                <div class="box-column-title">
				                    <span><?php echo $h1_title; ?></span>
				                </div>
				                <div class="pageno">
				                	<table width="100%" border="0" cellpadding="0" cellspacing="0">
				                		<tr>
				                			<td width="90">No. Penurunan</td>
				                			<td width="5">:</td>
				                			<td>&nbsp;<?php echo $kodemax; ?></td>
				                		</tr>
				                		<tr>
				                			<td width="90">Tanggal</td>
				                			<td width="5">:</td>
				                			<td>&nbsp;<?php echo date('d-m-Y'); ?></td>
				                		</tr>
				                		<tr>
				                			<td width="90">Halaman</td>
				                			<td width="5">:</td>
				                			<td>&nbsp;<?php echo $i + 1; ?></td>
				                		</tr>
				                	</table>
				                </div>
							</div>
							<div class="box-detail">

								<caption><?php echo "Realisasi"; ?></caption>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<th width="50">No.</th>
											<th width="120">No. TTK</th>
											<th width="180" style="text-align: left;">Supplier</th>
											<th width="70">Proyek</th>
											<th width="100">Nominal</th>
											<th width="150">Voucher</th>
											<th width="70">PD</th>
											<th width="70">Peminjam</th>
										</tr>
									</thead>
									<tbody>
										<?php
											/* --------------------------------------------------------------------------------------------------------
											$this->db->select("A.JournalH_Code, A.JournalH_Date, A.proj_Code, A.JournalType, A.JournalH_Desc, A.Journal_Amount, A.PPNH_Amount, A.PPHH_Amount, A.PERSL_EMPID, A.SPLCODE, A.Manual_No, A.Emp_ID, B.INV_NUM, B.INV_CODE, B.TTK_NUM, B.TTK_CODE, C.SPLCAT");
											$this->db->from("tbl_journalheader_vcash A");
											$this->db->join("tbl_pinv_detail B", "B.INV_NUM = A.JournalH_Code AND B.PRJCODE = A.proj_Code", "LEFT");
											$this->db->join("tbl_supplier C", "C.SPLCODE = A.PERSL_EMPID OR C.SPLCODE = A.SPLCODE", "LEFT");
											$this->db->limit($maxRow, $i*$maxRow);
											// $this->db->where_in("A.SPLCODE", $SPLCODE);
											$this->db->where_in("A.PERSL_EMPID", $SPLCODE);
											if($PRJCODE[0] != 1) $this->db->where_in("A.proj_Code", $PRJCODE);
											if($DROP_STAT == 0)
											{
												if($VOUCHER[0] != 1) $this->db->where_in("A.JournalH_Code", $VOUCHER);
												elseif($cDrop > 0) $this->db->where_not_in("A.JournalH_Code", $DROP_REF2NUM);
											}
											else
											{
												if($VOUCHER_DROP[0] != 1) 
												{
													$this->db->where_in("A.JournalH_Code", $VOUCHER_DROP);
												}
												else 
												{
													$this->db->where_in("A.JournalH_Code", $DROP_REF2NUM);
												}
											}
											$this->db->where("A.JournalH_Date >=", date('Y-m-d', strtotime($Start_Date)));
											$this->db->where("A.JournalH_Date <=", date('Y-m-d', strtotime($End_Date)));
											// $this->db->where("A.Emp_ID", $DefEmp_ID);
											$this->db->where_not_in("A.JournalType", ["BP","GEJ"]);
											$this->db->where_in("A.GEJ_STAT", [3]);
											$getQuery = $this->db->get();
											-------------------------------------------------------------------------------------------------- */

											if($query->num_rows() > 0)
											{
												$no = $no++;
												$Journal_TotAmount = 0;
												$Journal_GTotAmount = $Journal_GTotAmount++;
												foreach($query->result() as $r):
													$no 		= $no + 1;
													/* -------------------------------------
														$JournalH_Code = $r->JournalH_Code;
														$JournalH_Date = $r->JournalH_Date;
														$PRJCODE = $r->proj_Code;
														$JournalType = $r->JournalType;
														$Emp_ID 	= $r->Emp_ID;
														$JournalH_Desc = $r->JournalH_Desc;
														$Journal_Amount = $r->Journal_Amount;
														$PPNH_Amount = $r->PPNH_Amount;
														$PPHH_Amount = $r->PPHH_Amount;
														$PERSL_EMPID = $r->PERSL_EMPID;
														$SPLCODE = $r->SPLCODE;
														$SPLCAT = $r->SPLCAT;
														$Manual_No = $r->Manual_No;
														$INV_NUM = $r->INV_NUM;
														$INV_CODE = $r->INV_CODE;
														$TTK_NUM = $r->TTK_NUM;
														$TTK_CODE = $r->TTK_CODE;
													------------------------------------ */
													$INV_NUM 			= $r->INV_NUM;
													$INV_CODE 			= $r->INV_CODE;
													$TTK_NUM 			= $r->TTK_NUM;
													$TTK_CODE 			= $r->TTK_CODE;
													$INV_DATE 			= $r->INV_DATE;
													$PRJCODE 			= $r->PRJCODE;
													$INV_NOTES 			= $r->INV_NOTES;
													$INV_AMOUNT_TOT 	= $r->INV_AMOUNT_TOT;
													$INV_PPN 			= $r->INV_PPN;
													$INV_PPH 			= $r->INV_PPH;
													$SPLCODE 			= $r->SPLCODE;
													$PERSL_EMPID 		= $r->PERSL_EMPID;
													$EmpID 				= $r->EmpID;

													// $Journal_TotAmount = $Journal_Amount + $PPNH_Amount - $PPHH_Amount;
													$TotAmount = $INV_AMOUNT_TOT;
													$Journal_GTotAmount = $Journal_GTotAmount + $TotAmount;

													if($PERSL_EMPID == '')
														$PERSL_EMPID = $SPLCODE;
													elseif($SPLCODE == '')
														$PERSL_EMPID = $PERSL_EMPID;

													// get supplier
												    $EMP_NAME       = "";
												    $s_emp          =  "SELECT CONCAT(First_Name, ' ', Last_Name) AS EMP_NAME FROM tbl_employee WHERE Emp_ID = '$PERSL_EMPID'
												                        UNION
												                        SELECT SPLDESC AS EMP_NAME FROM tbl_supplier WHERE SPLCODE = '$PERSL_EMPID'";
												    $r_emp          = $this->db->query($s_emp)->result();
												    foreach($r_emp as $rw_emp) :
												        $EMP_NAME   = $rw_emp->EMP_NAME;
												    endforeach;

													?>
														<tr>
															<td width="50" style="text-align: center;"><?php echo $no; ?></td>
															<td width="120" style="text-align: center;">
																<?php echo $TTK_CODE; ?>
																<input type="hidden" name="data[<?=$no?>][DROP_NUM]" id="data<?=$no?>DROP_NUM" value="<?php echo "$DROP_NUM-$no"; ?>">
																<input type="hidden" name="data[<?=$no?>][DROP_CODE]" id="data<?=$no?>DROP_CODE" value="<?=$DROP_CODE?>">
																<input type="hidden" name="data[<?=$no?>][DROP_DATE]" id="data<?=$no?>DROP_DATE" value="<?=$DROP_DATE?>">
																<input type="hidden" name="data[<?=$no?>][Emp_ID]" id="data<?=$no?>EmpID" value="<?=$EmpID?>">
															</td>
															<td width="180">
																<?php echo "$PERSL_EMPID $EMP_NAME"; ?>
																<input type="hidden" name="data[<?=$no?>][PERSL_EMPID]" id="data<?=$no?>PERSL_EMPID" value="<?=$PERSL_EMPID?>">
															</td>
															<td width="70" style="text-align: center;">
																<?php echo $PRJCODE; ?>
																<input type="hidden" name="data[<?=$no?>][PRJCODE]" id="data<?=$no?>PRJCODE" value="<?=$PRJCODE?>">
															</td>
															<td width="100" style="text-align: right;"><?php echo number_format($TotAmount, 2); ?></td>
															<td width="150" style="text-align: center;">
																<?php echo $INV_CODE; ?>
																<input type="hidden" name="data[<?=$no?>][DROP_REF1NUM]" id="data<?=$no?>DROP_REF1NUM" value="<?=$TTK_NUM?>">
																<input type="hidden" name="data[<?=$no?>][DROP_REF2NUM]" id="data<?=$no?>DROP_REF2NUM" value="<?=$INV_NUM?>">
																<input type="hidden" name="data[<?=$no?>][DROP_CREATED]" id="data<?=$no?>DROP_CREATED" value="<?php echo date('Y-m-d H:i:s'); ?>">
																<input type="hidden" name="data[<?=$no?>][DROP_CREATER]" id="data<?=$no?>DROP_CREATER" value="<?php echo $DefEmp_ID; ?>">
															</td>
															<td width="70">&nbsp;</td>
															<td width="70">&nbsp;</td>
														</tr>
													<?php
												endforeach;

												if($no <= $maxRow)
												{
													$amRow = $maxRow - $no;
													for($j=0;$j<$amRow;$j++)
													{
														?>
															<tr class="blank-line">
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
											}
										?>
									</tbody>
									<tfoot style="display: <?php echo $i+1 == $tot_page ? '':'none'; ?>">
										<tr>
											<td colspan="4" style="text-align: right;">Total :&nbsp;</td>
											<td style="text-align: right;"><?php echo number_format($Journal_GTotAmount, 2); ?></td>
											<td colspan="4">&nbsp;</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="box-asign" style="display: <?php echo $i+1 == $tot_page ? '':'none'; ?>">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td>
											<span id="pos">Cost Control</span>
											<span id="name">Nama : ________________</span>
										</td>
										<td>
											<span id="pos" style="display: none;">Internal Audit</span>
											<span id="name" style="display: none;">Nama : ________________</span>
										</td>
										<td>
											<span id="pos">Keuangan</span>
											<span id="name">Nama : ________________</span>
										</td>
									</tr>
								</table>
							</div>

							<div class="clearfix"></div>
						</div>
					</section>

				<?php
			endfor;
		?>
		<div id="Layer1">
	        <a href="#" class="btn btn-md btn-default"><i class="fa fa-print"></i> Print</a>
	        <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
	        <i class="fa fa-download"></i> Generate PDF
	        </button>
	    </div>
	</form>
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
<script type="text/javascript">
	$(function(){
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

		/* can not support browser xp 
		window.onafterprint = (event) => {
			console.log(event);
			// input drop document
			// document.frmDropDoc.submit1.click();
			$.ajax({
				type: "POST",
				url: "<?php // echo base_url("c_finance/c_f1nR3p07t/pushDropDoc"); ?>",
				data: $('form').serialize(),
				success: function(data){
					window.opener.location.reload();
					close();
				}
			});
		};
		-------------------- end hidden ----------------------- */

		var mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function(mql) {
		    if (mql.matches) {
		        console.log('onbeforeprint equivalent');
		    } else {
		        console.log('onafterprint equivalent');
		        let DROP_STAT = $('#DROP_STAT').val();
		        if(DROP_STAT == 0)
		        {
			        $.ajax({
						type: "POST",
						url: "<?php echo base_url("c_finance/c_f1nR3p07t/pushDropDoc"); ?>",
						data: $('form').serialize(),
						success: function(data){
							window.opener.location.reload();
							close();
						}
					});
		        }
		        else
		        {
		        	window.opener.location.reload();
					close();
		        }
		    }
		});
	});
</script>