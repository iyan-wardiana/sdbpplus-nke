<?php
	/* 
		* Author		   = Dian Hermanto
		* Create Date	= 20 April 2018
		* File Name	 = v_sdbp_report.php
		* Location		 = -
	*/

	if($viewType == 1)
	{
		$repDate 	= date('ymdHis');
		$fileNm 	= "Lap.Transaksi Per Pekerjaan_".$repDate;
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$fileNm.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

    function cut_text($var, $len = 200, $txt_titik = "-") 
    {
        $var1   = explode("</p>",$var);
        $var    = $var1[0];
        if (strlen ($var) < $len) 
        { 
            return $var; 
        }
        if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
        {
            return $match [1] . $txt_titik;
        }
        else
        {
            return substr ($var, 0, $len) . $txt_titik;
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title><?=$title?></title>
	    <style>
	        body { 
	            /*margin: 0;*/
	            padding: 0;
	            background-color: #FAFAFA;
	            font: 12pt Arial, Helvetica, sans-serif;
	        }

	        * {
	            box-sizing: border-box;
	            -moz-box-sizing: border-box;
	        }

	        .page {
	            width: 600mm;
	            /*min-height: 296mm;*/
	            padding-left: 1cm;
	            padding-right: 1cm;
	            padding-top: 1cm;
	            padding-bottom: 1cm;
	            margin: 0.5cm auto;
	            border: 1px #D3D3D3 solid;
	            border-radius: 5px;
	            background: white;
	           /* background-size: 400px 200px !important;*/
	            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	        }

	        @page {
	            /*size: auto;*/
    			/*size: A3;*/
	            margin: 0;
	        }

	        @media print {

	            @page{size: landscape;}
	            .page {
	                margin: 0cm 2cm 1cm 0;
	                border: initial;
	                border-radius: initial;
	                width: initial;
	                min-height: initial;
	                box-shadow: initial;
	                background: initial;
	                page-break-after: always;
	            }
	        }

			.content {
				position: relative;
			}

			.tot_summary {
				display: flex;
				gap: 50px;
				position: absolute;
				top: 55px;
				left: 500px;
				font-size: 14px;
			}

	        .print_content table, thead, th, table, tbody, td {
	        	padding: 3px;
	        }
	    </style>
	</head>

	<div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>
        </div>
        <div class="print_title">
            <table width="100%" border="0" style="size:auto">
                <tr>
					<td width="50" height="50" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" width="180"></td>
                </tr>
            </table>
        </div>

        <?php
			$s_prog = "SELECT PRJP_GTOT FROM tbl_project_progress 
						WHERE PRJCODE = '$PRJCODE' AND PRJP_STAT = 3 ORDER BY PRJP_STEP DESC LIMIT 1";
			$r_prog = $this->db->query($s_prog);
			$PRJP_GTOT 		= 0;
			$PRJP_GTOTVAL	= 0;
			if($r_prog->num_rows() > 0)
			{
				foreach($r_prog->result() as $rw_prog):
					$PRJP_GTOT = $rw_prog->PRJP_GTOT; // nilai persentase
				endforeach;
				$PRJP_GTOTVAL = $PRJBOQ * ($PRJP_GTOT / 100);
			}
		?>

		<div class="content">
			<div class="print_body" style="padding-top: 10px; font-size: 14px;">
				<div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="140">Nama Laporan</td>
							<td width="10">:</td>
							<td colspan="3"><?php echo "$h1_title"; ?></td>
						</tr>
						<tr>
							<td width="140">Proyek</td>
							<td width="10">:</td>
							<td colspan="3"><span style="font-weight: bold;"><?php echo "$PRJNAME ($PRJCODE)"; ?></span></td>
						</tr>
						<tr>
							<td width="120">Nilai Kontrak</td>
							<td width="10">:</td>
							<td colspan="3"><span style="font-weight: bold;"><?php echo number_format($PRJBOQ, 2) ?></span></td>
						</tr>
						<tr>
							<td width="140">Progress Saat Ini</td>
							<td width="10">:</td>
							<td colspan="3"><span style="font-weight: bold;"><?php echo number_format($PRJP_GTOTVAL, 2)." (".number_format($PRJP_GTOT, 2)."%)"; ?></span></td>
						</tr>
						<tr>
							<td width="140">Tgl. Cetak</td>
							<td width="10">:</td>
							<td><?php echo date('Y-m-d:H:i:s'); ?></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="print_content" style="padding-top: 5px; font-size: 12px;">
				<table width="100%" border="1" rules="all" style="border-color: black;">
					<thead>
						<tr style="background:#CCCCCC">
							<th width="300" rowspan="2" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
							<th rowspan="2" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">VC/VPD/VLK</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SPK</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">OPNAME</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">OP</th>
							<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">LPM</th>
						  </tr>
						<tr style="background:#CCCCCC">
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
							<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
							<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						</tr>
						  <tr style="line-height:1px; border-left:hidden; border-right:hidden">
							<td style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
							<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						   </tr>
					</thead>
					<tbody>
						<?php
							$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
							
							if($JOBPARENT == 'All')
							{
								$addQJOB_ID 	= "";
								$addQJOB_P 		= "";
							} 
							else 
							{
								$addQJOB_ID 	= "AND JOBCODEID = '$JOBPARENT'";
								$addQJOB_P 	= "AND JOBCODEID LIKE '$JOBPARENT%'";
							}
	
							$getJOBD 		= "SELECT ORD_ID, JOBCODEID, JOBPARENT, PRJCODE, JOBDESC, ITM_GROUP, ITM_CODE, 
												ITM_UNIT, ITM_VOLM,	ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, IS_LEVEL, ISLASTH,
												ISLAST, WBSD_STAT
												FROM tbl_joblist_detail_$PRJCODEVW
												WHERE ISLAST = 0 $addQJOB_P  ORDER BY JOBCODEID, ORD_ID ASC";
							$resJOBD 		= $this->db->query($getJOBD);
							if($resJOBD->num_rows() > 0)
							{
								$GTITM_VOLMBG       = 0;
								$GTITM_BUDG 		= 0;
								$GTADDVOLM          = 0;
								$GTADD_TOTAL 		= 0;
								$GTITM_VOLM2        = 0;
								$GTITM_BUDG2 		= 0;
								$GTVC_VOLM          = 0;
								$GTVC_AMOUNT        = 0;
								$GTSPK_VOLM         = 0;
								$GTSPK_AMOUNT       = 0;
								$GTOPN_VOLM         = 0;
								$GTOPN_AMOUNT       = 0;
								$GTPO_VOLM          = 0;
								$GTPO_AMOUNT        = 0;
								$GTIR_VOLM          = 0;
								$GTIR_AMOUNT        = 0;
								$TOT_REQ 			= 0;
								$TOT_REAL 			= 0;
								foreach($resJOBD->result() as $rJOBD):
									$ORD_ID 		= $rJOBD->ORD_ID;
									$JOBCODEID 		= $rJOBD->JOBCODEID;
									$JOBPARENT 		= $rJOBD->JOBPARENT;
									$PRJCODE 		= $rJOBD->PRJCODE;
									$JOBDESC 		= $rJOBD->JOBDESC;
									$ITM_GROUP 		= $rJOBD->ITM_GROUP;
									$ITM_CODE 		= $rJOBD->ITM_CODE;
									$ITM_UNIT 		= $rJOBD->ITM_UNIT;
									$ITM_VOLM 		= $rJOBD->ITM_VOLM;
									$ITM_PRICE 		= $rJOBD->ITM_PRICE;
									$ITM_LASTP 		= $rJOBD->ITM_LASTP;
									$ITM_AVGP 		= $rJOBD->ITM_AVGP;
									// $ITM_BUDG 		= $rJOBD->ITM_BUDG;
									$IS_LEVEL 		= $rJOBD->IS_LEVEL;
									$ISLASTH 		= $rJOBD->ISLASTH;
									$ISLAST 		= $rJOBD->ISLAST;
									$WBSD_STAT 		= $rJOBD->WBSD_STAT;
	
									// $ITM_VOLMBG 	= $ITM_VOLM;
	
									if($ITM_UNIT == '') $ITM_UNIT = 'LS';
	
									// SPACE
										$spaceLev 		= "";
										if($IS_LEVEL == 1)
											$spaceLev 	= 0;
										elseif($IS_LEVEL == 2)
											$spaceLev 	= 15;
										elseif($IS_LEVEL == 3)
											$spaceLev 	= 30;
										elseif($IS_LEVEL == 4)
											$spaceLev 	= 45;
										elseif($IS_LEVEL == 5)
											$spaceLev 	= 60;
										elseif($IS_LEVEL == 6)
											$spaceLev 	= 75;
										elseif($IS_LEVEL == 7)
											$spaceLev 	= 90;
										elseif($IS_LEVEL == 8)
											$spaceLev 	= 105;
										elseif($IS_LEVEL == 9)
											$spaceLev 	= 120;
										elseif($IS_LEVEL == 10)
											$spaceLev 	= 135;
										elseif($IS_LEVEL == 11)
											$spaceLev 	= 150;
										elseif($IS_LEVEL == 12)
											$spaceLev 	= 165;
	
									$JobView1		= "$JOBCODEID - $JOBDESC";
									$JobView		= wordwrap($JobView1, 90, "<br>", true);
	
									$get_RQRLZ 	= "SELECT 	SUM(ITM_VOLM) AS ITM_VOLMBG, SUM(ITM_BUDG) AS ITM_BUDG,
															SUM(AMD_VOL - AMDM_VOL) AS AMD_VOL, SUM(AMD_VAL - AMDM_VAL) AS AMD_VAL,
															SUM(VCASH_VOL+VCASH_VOL_R+VLK_VOL+VLK_VOL_R+PPD_VOL+PPD_VOL_R) AS VC_VOLM,
															SUM(VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS VC_AMOUNT,
															SUM((WO_VOL-WO_CVOL)+WO_VOL_R) AS SPK_VOLM,
															SUM((WO_VAL-WO_CVAL)+WO_VAL_R) AS SPK_AMOUNT,
															SUM(OPN_VOL+OPN_VOL_R) AS OPN_VOLM,
															SUM(OPN_VAL+OPN_VAL_R) AS OPN_AMOUNT,
															SUM((PO_VOL-PO_CVOL)+PO_VOL_R) AS PO_VOLM,
															SUM((PO_VAL-PO_CVAL)+PO_VAL_R) AS PO_AMOUNT,
															SUM(IR_VOL+IR_VOL_R) AS IR_VOLM,
															SUM(IR_VAL+IR_VAL_R) AS IR_AMOUNT
													FROM tbl_joblist_detail_$PRJCODEVW
													WHERE JOBCODEID LIKE '$JOBCODEID%' AND ISLAST = 1";
									$res_RQRLZ 	= $this->db->query($get_RQRLZ);
									foreach($res_RQRLZ->result() as $rRQRLZ):
										// BUDG
											$ITM_VOLMBG     = $rRQRLZ->ITM_VOLMBG;
											$ITM_BUDG       = $rRQRLZ->ITM_BUDG;
	
										// Addendum
											$ADDVOLM 		= $rRQRLZ->AMD_VOL;
											$ADD_TOTAL 		= $rRQRLZ->AMD_VAL;
	
										// after addendum
											$ITM_VOLM2 		= $ITM_VOLMBG + $ADDVOLM;
											$ITM_BUDG2 		= $ITM_BUDG + $ADD_TOTAL;
	
										// VC (VC/VPD/VLK)
											$VC_VOLM 		= $rRQRLZ->VC_VOLM;
											$VC_AMOUNT 	    = $rRQRLZ->VC_AMOUNT;
	
										// SPK
											$SPK_VOLM 		= $rRQRLZ->SPK_VOLM;
											$SPK_AMOUNT 	= $rRQRLZ->SPK_AMOUNT;
	
										// OPNAME
											$OPN_VOLM 		= $rRQRLZ->OPN_VOLM;
											$OPN_AMOUNT 	= $rRQRLZ->OPN_AMOUNT;
	
										// OP
											$PO_VOLM 		= $rRQRLZ->PO_VOLM;
											$PO_AMOUNT 	    = $rRQRLZ->PO_AMOUNT;
	
										// IR
											$IR_VOLM 		= $rRQRLZ->IR_VOLM;
											$IR_AMOUNT 	    = $rRQRLZ->IR_AMOUNT;
	
									endforeach;
	
									$CELL_WARBUDG2  = "";
									$CELL_WAROPNVOL = "";
									$CELL_WAROPNVAL = "";
									$CELL_WARIRVOL  = "";
									$CELL_WARIRVAL  = "";
									if($ISLASTH == 1)
									{
										$CELL_COL	    = "";
										$GTITM_VOLMBG   = $GTITM_VOLMBG + $ITM_VOLMBG;
										$GTITM_BUDG     = $GTITM_BUDG + $ITM_BUDG;
										$GTADDVOLM      = $GTADDVOLM + $ADDVOLM;
										$GTADD_TOTAL 	= $GTADD_TOTAL + $ADD_TOTAL;
										$GTITM_VOLM2 	= $GTITM_VOLM2 + $ITM_VOLM2;
										$GTITM_BUDG2 	= $GTITM_BUDG2 + $ITM_BUDG2;
	
										$GTVC_VOLM      = $GTVC_VOLM + $VC_VOLM;
										$GTVC_AMOUNT    = $GTVC_AMOUNT + $VC_AMOUNT;
										$GTSPK_VOLM     = $GTSPK_VOLM + $SPK_VOLM;
										$GTSPK_AMOUNT   = $GTSPK_AMOUNT + $SPK_AMOUNT;
										$GTOPN_VOLM     = $GTOPN_VOLM + $OPN_VOLM;
										$GTOPN_AMOUNT   = $GTOPN_AMOUNT + $OPN_AMOUNT;
										$GTPO_VOLM      = $GTPO_VOLM + $PO_VOLM;
										$GTPO_AMOUNT    = $GTPO_AMOUNT + $PO_AMOUNT;
										$GTIR_VOLM      = $GTIR_VOLM + $IR_VOLM;
										$GTIR_AMOUNT    = $GTIR_AMOUNT + $IR_AMOUNT;
	
										if($DefEmp_ID == 'D15040004221')
										{
											$TOT_REQ    = $VC_AMOUNT + $SPK_AMOUNT;
											if($TOT_REQ > $ITM_BUDG2) $CELL_WARBUDG2 = "background-color:rgba(245,238,29,0.3);";
	
											if($OPN_VOLM > $SPK_VOLM) $CELL_WAROPNVOL = "background-color:rgba(255,0,0,0.3);";
											if($OPN_AMOUNT > $SPK_AMOUNT) $CELL_WAROPNVAL = "background-color:rgba(255,0,0,0.3);";
	
											if($IR_VOLM > $PO_VOLM) $CELL_WARIRVOL = "background-color:rgba(255,0,0,0.3);";
											if($IR_AMOUNT > $PO_AMOUNT) $CELL_WARIRVAL = "background-color:rgba(255,0,0,0.3);";
										}
									}
									else
									{
										$CELL_COL	= "font-weight:bold;";	
									}
									
									?>
										<tr>
											<td style="text-align:left; border-bottom-color:#000;">
												<span style="white-space:nowrap;<?=$CELL_COL?>"><div style='margin-left: <?=$spaceLev?>px;'><?php echo "$JobView"; ?></div></span>
											</td>
											<td  style="text-align:center; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo $ITM_UNIT; ?></td>
										<!-- BUDGET AWAL --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_VOLMBG, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_BUDG, 2); ?></td>
	
										<!-- PERUBAHAN --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADDVOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADD_TOTAL, 2); ?></td>
											
										<!-- SETELAH PERUBAHAN --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_VOLM2, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?><?=$CELL_WARBUDG2?>"><?php echo number_format($ITM_BUDG2, 2); ?></td>
	
										<!-- VC/VPD/VLK --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($VC_VOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($VC_AMOUNT, 2); ?></td>
										
										<!-- SPK --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($SPK_VOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($SPK_AMOUNT, 2); ?></td>
	
										<!-- OPNAME --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?><?=$CELL_WAROPNVOL?>"><?php echo number_format($OPN_VOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?><?=$CELL_WAROPNVAL?>"><?php echo number_format($OPN_AMOUNT, 2); ?></td>
	
										<!-- OP --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($PO_VOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($PO_AMOUNT, 2); ?></td>
	
										<!-- IR --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?><?=$CELL_WARIRVOL?>"><?php echo number_format($IR_VOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?><?=$CELL_WARIRVAL?>"><?php echo number_format($IR_AMOUNT, 2); ?></td>
										</tr>
									<?php
								endforeach;
								?>
									<tr>
										<td colspan="2" style="text-align: right; font-weight:800;">TOTAL</td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTITM_BUDG, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTADD_TOTAL, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTITM_BUDG2, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTVC_AMOUNT, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTSPK_AMOUNT, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTOPN_AMOUNT, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTPO_AMOUNT, 2) ?></td>
										<td colspan="2" style="text-align: right; font-weight:800;"><?php echo number_format($GTIR_AMOUNT, 2) ?></td>
									</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="tot_summary">
				<div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="120">Budget Awal</td>
							<td width="10">:</td>
							<td colspan="3"><span style="font-weight: bold;"><?php echo number_format($GTITM_BUDG, 2) ?></span></td>
						</tr>
						<tr>
							<td width="120">Perubahan</td>
							<td width="10">:</td>
							<td><span style="font-weight: bold;"><?php echo number_format($GTADD_TOTAL, 2) ?></span></td>
						</tr>
						<tr>
							<td width="120">Budget Akhir</td>
							<td width="10">:</td>
							<td colspan="3"><span style="font-weight: bold;"><?php echo number_format($GTITM_BUDG2, 2) ?></span></td>
						</tr>
					</table>
				</div>
				<div>
					<?php
						$TOT_REQ 	= $GTVC_AMOUNT + $GTSPK_AMOUNT + $GTPO_AMOUNT;
						$TOT_REAL	= $GTVC_AMOUNT + $GTOPN_AMOUNT + $GTIR_AMOUNT;
					?>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="120">Request</td>
							<td width="10">:</td>
							<td colspan="3"><span style="font-weight: bold;"><?php echo number_format($TOT_REQ, 2) ?></span></td>
						</tr>
						<tr>
							<td width="120">Realisasi</td>
							<td width="10">:</td>
							<td><span style="font-weight: bold;"><?php echo number_format($TOT_REAL, 2) ?></span></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>