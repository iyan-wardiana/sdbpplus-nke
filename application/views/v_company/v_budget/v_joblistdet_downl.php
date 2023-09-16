<?php
	/* 
		* Author		= Dian Hermanto
		* Create Date	= 31 Oktober 2022
		* File Name		= v_joblistdet_downl.php
		* Location		= -
	*/
	
	$dateN 		= date('ymdHis');
	$viewType 	= 1;
	if($viewType == 1)
	{
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=DaftarPek_$PRJCODE"."_$dateN.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$h1_title?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<body style="overflow:auto">
	<div class="page">
		<section class="content">
	        <table width="100%" border="0" style="size:auto">
	            <tr>
	                <td width="5%" class="style2">PRJCODE</td>
	                <td width="8%" class="style2">KODE PEK.</td>
	                <td width="8%" class="style2">INDUK KODE PEK.</td>
	                <td width="35%" class="style2">NAMA PEKERJAAN</td>
	                <td width="10%" class="style2">GROUP</td>
	                <td width="10%" class="style2">KODE ITEM</td>
	                <td width="10%" class="style2">SATUAN</td>
	                <td width="10%" class="style2">VOLUME</td>
	                <td width="10%" class="style2">HARGA</td>
	                <td width="10%" class="style2">TOTAL RAPT</td>
	                <td width="10%" class="style2">TOTAL RAPP</td>
	                <td width="10%" class="style2">TOTAL BA</td>
	                <td width="10%" class="style2">TOTAL DIMINTA</td>
	                <td width="10%" class="style2">TOTAL REALISASI</td>
	                <td width="10%" class="style2">SISA RAPP</td>
	                <td width="2%" class="style2">IS NEWJOB</td>
	                <td width="2%" class="style2">LEVEL</td>
	                <td width="2%" class="style2">LAST HEADER</td>
	                <td width="2%" class="style2">IS ITEM</td>
	            </tr>
	            <?php
	            	$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

	            	$s_00 	= "SELECT PRJCODE, JOBCODEID, JOBPARENT, JOBDESC, ITM_GROUP, ITM_CODE, ITM_UNIT,
	            					ITM_VOLM, ITM_PRICE, ITM_BUDG, RAPT_VOLM, RAPT_PRICE, RAPT_JOBCOST, IS_LEVEL, ISLASTH, ISLAST,
    								SUM(AMD_VOL - AMDM_VOL) AS AMD_VOL, SUM(AMD_VAL - AMDM_VAL) AS AMD_VAL,
									SUM(PR_VOL) AS PR_VOL, SUM(PR_VAL) AS PR_VAL, SUM(PR_VOL_R) AS PR_VOL_R, SUM(PR_VAL_R) AS PR_VAL_R,
									SUM(PR_CVOL) AS PR_CVOL, SUM(PR_CVAL) AS PR_CVAL,
									SUM(PO_VOL) AS PO_VOL, SUM(PO_VAL) AS PO_VAL, SUM(PO_VOL_R) AS PO_VOL_R, SUM(PO_VAL_R) AS PO_VAL_R,
									SUM(PO_CVOL) AS PO_CVOL, SUM(PO_CVAL) AS PO_CVAL,
									SUM(IR_VOL) AS IR_VOL, SUM(IR_VAL) AS IR_VAL, SUM(IR_VOL_R) AS IR_VOL_R, SUM(IR_VAL_R) AS IR_VAL_R,
									SUM(UM_VOL) AS UM_VOL, SUM(UM_VAL) AS UM_VAL, SUM(UM_VOL_R) AS UM_VOL_R, SUM(UM_VAL_R) AS UM_VAL_R,
									SUM(WO_VOL) AS WO_VOL, SUM(WO_VAL) AS WO_VAL, SUM(WO_VOL_R) AS WO_VOL_R, SUM(WO_VAL_R) AS WO_VAL_R,
									SUM(WO_CVOL) AS WO_CVOL, SUM(WO_CVAL) AS WO_CVAL,
									SUM(OPN_VOL) AS OPN_VOL, SUM(OPN_VAL) AS OPN_VAL, SUM(OPN_VOL_R) AS OPN_VOL_R, SUM(OPN_VAL_R) AS OPN_VAL_R,
									SUM(VCASH_VOL) AS VCASH_VOL, SUM(VCASH_VAL) AS VCASH_VAL, SUM(VCASH_VOL_R) AS VCASH_VOL_R, SUM(VCASH_VAL_R) AS VCASH_VAL_R,
									SUM(VLK_VOL) AS VLK_VOL, SUM(VLK_VAL) AS VLK_VAL, SUM(VLK_VOL_R) AS VLK_VOL_R, SUM(VLK_VAL_R) AS VLK_VAL_R,
									SUM(PPD_VOL) AS PPD_VOL, SUM(PPD_VAL) AS PPD_VAL, SUM(PPD_VOL_R) AS PPD_VOL_R, SUM(PPD_VAL_R) AS PPD_VAL_R
	            				FROM tbl_joblist_detail_$PRJCODEVW GROUP BY JOBCODEID ORDER BY JOBCODEID";
	            	$r_00 	= $this->db->query($s_00)->result();
	            	foreach($r_00 as $rw_00):
	            		$PRJCODE 	= $rw_00->PRJCODE;
	            		$JOBCODEID 	= $rw_00->JOBCODEID;
	            		$JOBPARENT 	= $rw_00->JOBPARENT;
	            		$JOBDESC 	= $rw_00->JOBDESC;
	            		$ITM_GROUP 	= $rw_00->ITM_GROUP;
	            		$ITM_CODE 	= $rw_00->ITM_CODE;
	            		$ITM_UNIT 	= $rw_00->ITM_UNIT;
	            		$JOBDESC 	= $rw_00->JOBDESC;
	            		$ITM_VOLM 	= $rw_00->ITM_VOLM;
	            		$ITM_PRICE 	= $rw_00->ITM_PRICE;
	            		$TOTAL_RAPP = $rw_00->ITM_BUDG;
	            		$TOTAL_RAPT	= $rw_00->RAPT_JOBCOST;
	            		$IS_LEVEL	= $rw_00->IS_LEVEL;
	            		$ISLASTH	= $rw_00->ISLASTH;
	            		$ISLASTH	= $rw_00->ISLASTH;
	            		$ISLAST		= $rw_00->ISLAST;


						$AMD_VOL	= $rw_00->AMD_VOL;
						$AMD_VAL 	= $rw_00->AMD_VAL;

						$PR_VOL		= $rw_00->PR_VOL;
						$PR_VAL 	= $rw_00->PR_VAL;
						$PR_VOL_R	= $rw_00->PR_VOL_R;
						$PR_VAL_R 	= $rw_00->PR_VAL_R;
						$PR_CVOL	= $rw_00->PR_CVOL;
						$PR_CVAL 	= $rw_00->PR_CVAL;

						$PO_VOL		= $rw_00->PO_VOL;
						$PO_VAL 	= $rw_00->PO_VAL;
						$PO_VOL_R	= $rw_00->PO_VOL_R;
						$PO_VAL_R 	= $rw_00->PO_VAL_R;
						$PO_CVOL	= $rw_00->PO_CVOL;
						$PO_CVAL 	= $rw_00->PO_CVAL;

						$IR_VOL		= $rw_00->IR_VOL;
						$IR_VAL 	= $rw_00->IR_VAL;
						$IR_VOL_R	= $rw_00->IR_VOL_R;
						$IR_VAL_R 	= $rw_00->IR_VAL_R;

						$UM_VOL		= $rw_00->UM_VOL;
						$UM_VAL 	= $rw_00->UM_VAL;
						$UM_VOL_R	= $rw_00->UM_VOL_R;
						$UM_VAL_R 	= $rw_00->UM_VAL_R;

						$WO_VOL		= $rw_00->WO_VOL;
						$WO_VAL 	= $rw_00->WO_VAL;
						$WO_VOL_R	= $rw_00->WO_VOL_R;
						$WO_VAL_R 	= $rw_00->WO_VAL_R;
						$WO_CVOL	= $rw_00->WO_CVOL;
						$WO_CVAL 	= $rw_00->WO_CVAL;

						$OPN_VOL	= $rw_00->OPN_VOL;
						$OPN_VAL 	= $rw_00->OPN_VAL;
						$OPN_VOL_R	= $rw_00->OPN_VOL_R;
						$OPN_VAL_R 	= $rw_00->OPN_VAL_R;

						$VCASH_VOL	= $rw_00->VCASH_VOL;
						$VCASH_VAL 	= $rw_00->VCASH_VAL;
						$VCASH_VOL_R= $rw_00->VCASH_VOL_R;
						$VCASH_VAL_R= $rw_00->VCASH_VAL_R;

						$VLK_VOL	= $rw_00->VLK_VOL;
						$VLK_VAL 	= $rw_00->VLK_VAL;
						$VLK_VOL_R	= $rw_00->VLK_VOL_R;
						$VLK_VAL_R 	= $rw_00->VLK_VAL_R;

						$PPD_VOL	= $rw_00->PPD_VOL;
						$PPD_VAL 	= $rw_00->PPD_VAL;
						$PPD_VOL_R	= $rw_00->PPD_VOL_R;
						$PPD_VAL_R 	= $rw_00->PPD_VAL_R;

						// TOTAL BUDGET BARU
							$TOT_BVOL 	= $ITM_VOLM + $AMD_VOL;
							$TOT_BVAL 	= $TOTAL_RAPP + $AMD_VAL;

						// TOTAL DIMINTA
							$PRT_VOL 	= $PR_VOL+$PR_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$PR_CVOL-$WO_CVOL;
							$PRT_VAL 	= $PO_VAL+$PO_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$PO_CVAL-$WO_CVAL;

						// TOTAL DIPESAN
							$POT_VOL 	= $PO_VOL+$PO_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$PO_CVOL-$WO_CVOL;
							$POT_VAL 	= $PO_VAL+$PO_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$PO_CVAL-$WO_CVAL;

						// TOTAL DITERIMA
							// $IRT_VOL 	= $IR_VOL+$IR_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$WO_CVOL;
							// $IRT_VAL 	= $IR_VAL+$IR_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$WO_CVAL;

						// TOTAL DIGUNAKAN
							// $UMT_VOL 	= $UM_VOL+$UM_VOL_R+$OPN_VOL+$OPN_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R;
							$USEDT_VOL 	= $IR_VOL+$IR_VOL_R+$OPN_VOL+$OPN_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R;
							// $UMT_VAL 	= $UM_VAL+$UM_VAL_R+$OPN_VAL+$OPN_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R;
							$USEDT_VAL 	= $IR_VAL+$IR_VAL_R+$OPN_VAL+$OPN_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R;

						// TOTAL SISA
							$TOT_RVAL 	= $TOTAL_RAPP + $AMD_VAL - $PRT_VAL;
	            		?>
	            		<tr>
			                <td><?=$PRJCODE?></td>
			                <td><?=$JOBCODEID?></td>
			                <td><?=$JOBPARENT?></td>
			                <td><?=$JOBDESC?></td>
			                <td><?=$ITM_GROUP?></td>
			                <td><?=$ITM_CODE?></td>
			                <td><?=$ITM_UNIT?></td>
			                <td><?=$ITM_VOLM?></td>
			                <td><?=$ITM_PRICE?></td>
			                <td><?=$TOTAL_RAPT?></td>
			                <td><?=$TOTAL_RAPP?></td>
			                <td><?=$AMD_VAL?></td>
			                <td><?=$PRT_VAL?></td>
			                <td><?=$USEDT_VAL?></td>
			                <td><?=$TOT_RVAL?></td>
			                <td>&nbsp;</td>
			                <td><?=$IS_LEVEL?></td>
			                <td><?=$ISLASTH?></td>
			                <td><?=$ISLAST?></td>
	           			</tr>
	            		<?php
	            	endforeach;
	            ?>
	        </table>
		</section>
	</div>
</body>
</html>