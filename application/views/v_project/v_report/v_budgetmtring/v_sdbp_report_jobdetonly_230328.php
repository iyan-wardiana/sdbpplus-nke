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
		$fileNm 	= "RingkasanBudgetDet_".$repDate;
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
	    <title>Laporan Pekerjaan Tambah/Kurang</title>
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
	                margin: 0;
	                border: initial;
	                border-radius: initial;
	                width: initial;
	                min-height: initial;
	                box-shadow: initial;
	                background: initial;
	                page-break-after: always;
	            }
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

        <div class="print_body" style="font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td colspan="3"><?php echo "$h1_title"; ?> (Detil Pekerjaan)</td>
                </tr>
                <tr>
                    <td width="100">Periode</td>
                    <td width="10">:</td>
                    <td><?php echo date('d-m-Y', strtotime($Start_Date));?>  S/D <?php echo date('d-m-Y', strtotime($End_Date));?></td>
                </tr>
                <tr>
                    <td width="100">Kode Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJCODE"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr style="display: none;">
                    <td width="100">Kategori</td>
                    <td width="10">:</td>
                    <td><?php echo $ITMGRP_NM; ?></td>
                </tr>
                <tr>
                    <td width="100">Tgl. Cetak</td>
                    <td width="10">:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
        	<table width="100%" border="1" rules="all" style="border-color: black;">
        		<thead>
        			<tr style="background:#CCCCCC">
						<th width="400" rowspan="2" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
						<th rowspan="2" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
						<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
						<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
						<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
						<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">DIMINTA</th>
						<th colspan="2" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA TERHADAP BUDGET</th>
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
	               	</tr>
        		</thead>
        		<tbody>
        			<?php
						$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

						if($JOBPARENT == 'All')
						{
							$addQJOB_ID = "";
							$addQJOB_P 	= "";
						} 
						else 
						{
							$addQJOB_ID = "WHERE (ISLAST = 1 OR ISLASTH = 1) AND JOBCODEID = '$JOBPARENT'";
							$addQJOB_P 	= "WHERE (ISLAST = 1 OR ISLASTH = 1) AND JOBCODEID LIKE '$JOBPARENT%'";
						}

						$getJOBD 		= "SELECT ORD_ID, JOBCODEID, JOBPARENT, PRJCODE, JOBDESC, ITM_GROUP, ITM_CODE, 
											ITM_UNIT, ITM_VOLM,	ITM_PRICE, ITM_LASTP, ITM_AVGP, ITM_BUDG, IS_LEVEL, ISLASTH,
											ISLAST, WBSD_STAT
											FROM tbl_joblist_detail_$PRJCODEVW
											$addQJOB_P ORDER BY JOBCODEID, ORD_ID ASC";
						$resJOBD 		= $this->db->query($getJOBD);
						if($resJOBD->num_rows() > 0)
						{
							$RAPT_VOL 		= 0;
							$RAPT_VAL 		= 0;
							$AMDT_VOL 		= 0;
							$AMDT_VAL 		= 0;
							$RAPT_VOL2 		= 0;
							$RAPT_VAL2 		= 0;
							$REQT_VOL 		= 0;
							$REQT_VAL 		= 0;
							$REMT_VOL 		= 0;
							$REMT_VAL 		= 0;
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
								$ITM_BUDG 		= $rJOBD->ITM_BUDG;
								$IS_LEVEL 		= $rJOBD->IS_LEVEL;
								$ISLASTH 		= $rJOBD->ISLASTH;
								$ISLAST 		= $rJOBD->ISLAST;
								$WBSD_STAT 		= $rJOBD->WBSD_STAT;

								$ITM_VOLMBG 	= $ITM_VOLM;

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

								$CELL_COL	= "";
								if($ISLASTH == 1)
								{
									$CELL_COL	= "font-weight:bold;";
									?>
										<tr>
											<td style="text-align:left; border-bottom-color:#000;">
												<span style="white-space:nowrap;<?=$CELL_COL?>"><div style='margin-left: <?=$spaceLev?>px;'><?php echo "$JobView"; ?></div></span>
											</td>
											<td colspan="12">&nbsp;</td>
										</tr>
									<?php
								}
								else
								{
									if($ISLAST == 0)
										$ADDQRY_01 		= "JOBCODEID LIKE '$JOBCODEID%'";
									else
										$ADDQRY_01 		= "JOBCODEID = '$JOBCODEID'";

									$RAP_VOL2 		= 0;
									$RAP_VAL2 		= 0;
									$REQ_VOL 		= 0;
									$REQ_VAL 		= 0;
									$REQ_VOL_R 		= 0;
									$REQ_VAL_R 		= 0;
									$USED_VOL 		= 0;
									$USED_VAL 		= 0;
									$USED_VOL_R		= 0;
									$USED_VAL_R		= 0;
									$RAPT_VOL 		= 0;
									$RAPT_VAL 		= 0;
									$RAP_REM_VOL 	= 0;
									$RAP_REM_VAL 	= 0;
									$get_QRY 		= "SELECT 	SUM(AMD_VOL 	- AMDM_VOL) AS ADD_VOL,
															SUM(AMD_VAL 	- AMDM_VAL) AS ADD_VAL,
															SUM(PR_VOL 		- PR_CVOL) AS PR_VOL,
															SUM(PR_VAL 		- PR_CVAL) AS PR_VAL,
															SUM(PR_VOL_R) 	AS PR_VOL_R,
															SUM(PR_VAL_R) 	AS PR_VAL_R,

															SUM(PO_VOL 		- PO_CVOL) AS PO_VOL,
															SUM(PO_VAL 		- PO_CVAL) AS PO_VAL,
															SUM(PO_VOL_R) 	AS PO_VOL_R,
															SUM(PO_VAL_R) 	AS PO_VAL_R,

															SUM(IR_VOL) 	AS IR_VOL,
															SUM(IR_VAL) 	AS IR_VAL,
															SUM(IR_VOL_R) 	AS IR_VOL_R,
															SUM(IR_VAL_R) 	AS IR_VAL_R,

															SUM(UM_VOL) 	AS UM_VOL,
															SUM(UM_VAL) 	AS UM_VAL,
															SUM(UM_VOL_R) 	AS UM_VOL_R,
															SUM(UM_VAL_R) 	AS UM_VAL_R,

															SUM(WO_VOL 		- WO_CVOL) AS WO_VOL,
															SUM(WO_VAL 		- WO_CVAL) AS WO_VAL,
															SUM(WO_VOL_R) 	AS WO_VOL_R,
															SUM(WO_VAL_R) 	AS WO_VAL_R,

															SUM(OPN_VOL) 	AS OPN_VOL,
															SUM(OPN_VAL) 	AS OPN_VAL,
															SUM(OPN_VOL_R) 	AS OPN_VOL_R,
															SUM(OPN_VAL_R) 	AS OPN_VAL_R,

															SUM(VCASH_VOL) 		AS VCASH_VOL,
															SUM(VCASH_VAL) 		AS VCASH_VAL,
															SUM(VCASH_VOL_R) 	AS VCASH_VOL_R,
															SUM(VCASH_VAL_R) 	AS VCASH_VAL_R,

															SUM(VLK_VOL) 		AS VLK_VOL,
															SUM(VLK_VAL) 		AS VLK_VAL,
															SUM(VLK_VOL_R) 		AS VLK_VOL_R,
															SUM(VLK_VAL_R) 		AS VLK_VAL_R,

															SUM(PPD_VOL) 		AS PPD_VOL,
															SUM(PPD_VAL) 		AS PPD_VAL,
															SUM(PPD_VOL_R) 		AS PPD_VOL_R,
															SUM(PPD_VAL_R) 		AS PPD_VAL_R
													FROM tbl_item_logbook_$PRJCODEVW
													WHERE $ADDQRY_01";
									$res_QRY 		= $this->db->query($get_QRY);
									foreach($res_QRY->result() as $rQRY):
										$ADD_VOL 		= $rQRY->ADD_VOL;
										$ADD_VAL 		= $rQRY->ADD_VAL;
										
										$PR_VOL 		= $rQRY->PR_VOL;
										$PR_VAL 		= $rQRY->PR_VAL;
										$PR_VOL_R 		= $rQRY->PR_VOL_R;
										$PR_VAL_R 		= $rQRY->PR_VAL_R;
										
										$PO_VOL 		= $rQRY->PO_VOL;
										$PO_VAL 		= $rQRY->PO_VAL;
										$PO_VOL_R 		= $rQRY->PO_VOL_R;
										$PO_VAL_R 		= $rQRY->PO_VAL_R;
										
										$IR_VOL 		= $rQRY->IR_VOL;
										$IR_VAL 		= $rQRY->IR_VAL;
										$IR_VOL_R 		= $rQRY->IR_VOL_R;
										$IR_VAL_R 		= $rQRY->IR_VAL_R;
										
										$UM_VOL 		= $rQRY->UM_VOL;
										$UM_VAL 		= $rQRY->UM_VAL;
										$UM_VOL_R 		= $rQRY->UM_VOL_R;
										$UM_VAL_R 		= $rQRY->UM_VAL_R;
										
										$WO_VOL 		= $rQRY->WO_VOL;
										$WO_VAL 		= $rQRY->WO_VAL;
										$WO_VOL_R 		= $rQRY->WO_VOL_R;
										$WO_VAL_R 		= $rQRY->WO_VAL_R;
										
										$OPN_VOL 		= $rQRY->OPN_VOL;
										$OPN_VAL 		= $rQRY->OPN_VAL;
										$OPN_VOL_R 		= $rQRY->OPN_VOL_R;
										$OPN_VAL_R 		= $rQRY->OPN_VAL_R;
										
										$VCASH_VOL 		= $rQRY->VCASH_VOL;
										$VCASH_VAL 		= $rQRY->VCASH_VAL;
										$VCASH_VOL_R 	= $rQRY->VCASH_VOL_R;
										$VCASH_VAL_R 	= $rQRY->VCASH_VAL_R;
										
										$VLK_VOL 		= $rQRY->VLK_VOL;
										$VLK_VAL 		= $rQRY->VLK_VAL;
										$VLK_VOL_R 		= $rQRY->VLK_VOL_R;
										$VLK_VAL_R 		= $rQRY->VLK_VAL_R;
										
										$PPD_VOL 		= $rQRY->PPD_VOL;
										$PPD_VAL 		= $rQRY->PPD_VAL;
										$PPD_VOL_R 		= $rQRY->PPD_VOL_R;
										$PPD_VAL_R 		= $rQRY->PPD_VAL_R;
									endforeach;

									// AFTER ADDENDUM
										$RAP_VOL2 		= $ITM_VOLM + $ADD_VOL;
										$RAP_VAL2 		= $ITM_BUDG + $ADD_VAL;

									// REQ TOTAL
										$REQ_VOL 		= $PR_VOL + $WO_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
										$REQ_VAL 		= $PO_VAL + $WO_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
										$REQ_VOL_R 		= $PR_VOL_R + $WO_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;
										$REQ_VAL_R 		= $PO_VAL_R + $WO_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;

									// USED TOTAL
										$USED_VOL 		= $UM_VOL + $OPN_VOL + $VCASH_VOL + $VLK_VOL + $PPD_VOL;
										$USED_VAL 		= $UM_VAL + $OPN_VAL + $VCASH_VAL + $VLK_VAL + $PPD_VAL;
										$USED_VOL_R		= $UM_VOL_R + $OPN_VOL_R + $VCASH_VOL_R + $VLK_VOL_R + $PPD_VOL_R;
										$USED_VAL_R		= $UM_VAL_R + $OPN_VAL_R + $VCASH_VAL_R + $VLK_VAL_R + $PPD_VAL_R;
									
									// SISA BUDG THD REALISASI
										$RAP_REM_VOL 	= $RAP_VOL2 - $REQ_VOL;
										$RAP_REM_VAL 	= $RAP_VAL2 - $REQ_VAL;

									// BUDGET TOTAL
										$RAPT_VOL 		= $RAPT_VOL + $ITM_VOLM;
										$RAPT_VAL 		= $RAPT_VAL + $ITM_BUDG;

									// AMD TOTAL
										$AMDT_VOL 		= $AMDT_VOL + $ADD_VOL;
										$AMDT_VAL 		= $AMDT_VAL + $ADD_VAL;

									// BUDGET TOTAL AFTER AMD
										$RAPT_VOL2 		= $RAPT_VOL2 + $RAP_VOL2;
										$RAPT_VAL2 		= $RAPT_VAL2 + $RAP_VAL2;

									// REQUESTED TOTAL
										$REQT_VOL 		= $REQT_VOL + $REQ_VOL + $REQ_VOL_R;
										$REQT_VAL 		= $REQT_VAL + $REQ_VAL + $REQ_VAL_R;

									// REQUESTED TOTAL
										$REMT_VOL 		= $REMT_VOL + $RAP_REM_VOL;
										$REMT_VAL 		= $REMT_VAL + $RAP_REM_VAL;

									$s_isLS = "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
									$r_isLS = $this->db->count_all($s_isLS);

									$vwAMDD		= "$JOBCODEID~$PRJCODE~ADD~$r_isLS";
									$vwREQD		= "$JOBCODEID~$PRJCODE~REQ~$r_isLS";
									$vwUSED		= "$JOBCODEID~$PRJCODE~USE~$r_isLS";
									$secvwAMDD 	= site_url('c_project/c_r3p/shwItm_H15tDETPERJOB/?id='.$this->url_encryption_helper->encode_url($vwAMDD));
									$secvwREQD 	= site_url('c_project/c_r3p/shwItm_H15tDETPERJOB/?id='.$this->url_encryption_helper->encode_url($vwREQD));
									$secvwUSED 	= site_url('c_project/c_r3p/shwItm_H15tDETPERJOB/?id='.$this->url_encryption_helper->encode_url($vwUSED));

									$alrtStyl1 		= "";
									$alrtStyl2 		= "";
									if(round($RAP_REM_VOL, 2) < 0 && $r_isLS == 0)
									{
										$alrtStyl1 	= "background-color: gray;";
									}

									if(round($RAP_REM_VAL, 2) < 0)
									{
										$alrtStyl2 	= "background-color: gray;";
									}
									?>
										<tr>
											<td style="text-align:left; border-bottom-color:#000;">
												<span style="white-space:nowrap;<?=$CELL_COL?>"><div style='margin-left: <?=$spaceLev?>px;'><?php echo "$JobView"; ?></div></span>
											</td>
											<td  style="text-align:center; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo $ITM_UNIT; ?></td>
										<!-- BUDGET AWAL --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_VOLM, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ITM_BUDG, 2); ?></td>

										<!-- PERUBAHAN PERIODE INI --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($ADD_VOL, 3); ?></td>
											<td  style="text-align:right; text-decoration: underline; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>">
												<a onclick="showITMDET('<?php echo $secvwAMDD; ?>')" style="cursor: pointer;">
													<?php echo number_format($ADD_VAL, 2); ?>
												</a>
											</td>
											
										<!-- SETELAH PERUBAHAN --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($RAP_VOL2, 3); ?></td>
											<td  style="text-align:right; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($RAP_VAL2, 2); ?></td>
											
										<!-- REALISASI : PERIODE INI --->
											<td  style="text-align:right; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($REQ_VOL, 3); ?></td>
											<td  style="text-align:right; text-decoration: underline; border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>">
												<a onclick="showITMDET('<?php echo $secvwREQD; ?>')" style="cursor: pointer;">
													<?php echo number_format($REQ_VAL, 2); ?>
												</a>
											</td>
											
										<!-- REALISASI : SISA BUDGET THD REALISASI --->
											<td  style="text-align:right;<?=$alrtStyl1?> border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($RAP_REM_VOL, 3); ?></td>
											<td  style="text-align:right;<?=$alrtStyl2?> border-right-color:#000; border-bottom-color:#000;<?=$CELL_COL?>"><?php echo number_format($RAP_REM_VAL, 2); ?></td>
										</tr>
									<?php
								}
							endforeach;

							?>
								<tr style="border: 2px solid;">
									<td colspan="3" style="text-align: right; font-weight:bold;">TOTAL</td>
									<td style="text-align: right; font-weight:bold;"><?php echo number_format($RAPT_VAL, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($AMDT_VAL, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($RAPT_VAL2, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($REQT_VAL, 2); ?></td>
									<td style="text-align: right; font-weight:bold;" colspan="2"><?php echo number_format($REMT_VAL, 2); ?></td>
								</tr>
							<?php
						}
        			?>
        		</tbody>
        	</table>
        </div>
	</body>
</html>
<script type="text/javascript">
	function showITMDET(LinkD)
	{
		w = 1000;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}

</script>