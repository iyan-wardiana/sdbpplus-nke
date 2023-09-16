<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= v_stock_opname_report_sum.php
 * Location		= -
*/

/*
 * Author		= Hendar Permana
 * Edit Date	= 16 Agustius 2017
 * File Name	= v_stock_opname_report_adm.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

date_default_timezone_set("Asia/Jakarta");
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat	= 2;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];
/*if($viewProj == 0) // SELECTED PROJECT
{
	if($TOTPROJ == 1)
	{
		$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
						WHERE A.PRJCODE = '$PRJCODE'
						ORDER BY A.PRJCODE";
		$result 	= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJCODED 	= $row ->PRJCODE;
			$PRJNAMED 	= $row ->PRJNAME;
		endforeach;
		$PRJCODECOLL	= "$PRJCODED";
		$PRJNAMECOLL	= "$PRJNAMED";
	}
	else
	{
		$PRJCODED	= 'Multi Project Code';
		$PRJNAMED 	= 'Multi Project Name';
		$myrow		= 0;
		$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A
						WHERE A.PRJCODE = '$PRJCODE'
						ORDER BY A.PRJCODE";
		$result 	= $this->db->query($sql)->result();
		foreach($result as $row) :
			$myrow		= $myrow + 1;
			$PRJCODED 	= $row ->PRJCODE;
			$PRJNAMED 	= $row ->PRJNAME;
			if($myrow == 1)
			{
				$PRJCODECOLL	= "$PRJCODED";
				$PRJCODECOL1	= "$PRJCODED";
				$PRJNAMECOLL	= "$PRJNAMED";
				$PRJNAMECOL1	= "$PRJNAMED";
			}
			if($myrow > 1)
			{
				$PRJCODECOL1	= "$PRJCODECOL1, $PRJCODED";
				$PRJCODECOLL	= "$PRJCODECOL1";
				$PRJNAMECOL1	= "$PRJNAMECOL1, $PRJNAMED";
				$PRJNAMECOLL	= "$PRJNAMECOL1";
			}
		endforeach;
	}
	$PRJCODECOLLD	= $PRJCODECOLL;
	$PRJNAMECOLLD	= $PRJNAMECOLL;
}
else
{
	$myrow			= 0;
	$sql 			= "SELECT DISTINCT PRJCODE FROM tbl_project WHERE PRJCOST > 1000000";
	$result 		= $this->db->query($sql)->result();
	foreach($result as $row) :
		$myrow		= $myrow + 1;
		$PRJCODE	= $row->PRJCODE;
		if($myrow == 1)
		{
			$NPRJCODE = $PRJCODE;
		}
		else if($myrow == 2)
		{
			$NPRJCODE = "'$NPRJCODE', '$PRJCODE'";
		}
		else if($myrow > 2)
		{
			$NPRJCODE = "$NPRJCODE, '$PRJCODE'";
		}
	endforeach;
	$PRJCODECOL		= $NPRJCODE;
	//echo "$NPRJCODE";
	//return false;
	$PRJCODECOLLD	= "All";
	$PRJNAMECOLLD	= "All";
}*/

$PRJNAME	= '';
$PRJCOST	= 0;
$sqlPRJ		= "SELECT PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJNAME	= $rowPRJ->PRJNAME;
	$PRJCOST	= $rowPRJ->PRJCOST;
endforeach;

// $StartDate	= date('d M Y', strtotime($Start_Date));
// $EndDate	= date('d M Y', strtotime($End_Date));


$LangID 	= $this->session->userdata['LangID'];

$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl		= $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
	$TranslCode	= $rowTransl->MLANG_CODE;
	$LangTransl	= $rowTransl->LangTransl;

	if($TranslCode == 'ReportName')$ReportName = $LangTransl;
	if($TranslCode == 'Periode')$Periode = $LangTransl;
	if($TranslCode == 'ProjectCode')$ProjectCode = $LangTransl;
	if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
	if($TranslCode == 'PrintDate')$PrintDate = $LangTransl;
	if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
	if($TranslCode == 'Description')$Description = $LangTransl;
	if($TranslCode == 'Unit')$Unit = $LangTransl;
	if($TranslCode == 'Budget')$Budget = $LangTransl;
	if($TranslCode == 'Requested')$Requested = $LangTransl;
	if($TranslCode == 'Ordered')$Ordered = $LangTransl;
	if($TranslCode == 'Received')$Received = $LangTransl;
	if($TranslCode == 'Used')$Used = $LangTransl;
	if($TranslCode == 'Remain')$Remain = $LangTransl;
	if($TranslCode == 'Volume')$Volume = $LangTransl;
	if($TranslCode == 'Price')$Price = $LangTransl;
	if($TranslCode == 'Stock')$Stock = $LangTransl;
endforeach;

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
	            margin: 0;
	            padding: 0;
	            background-color: #FAFAFA;
	            font: 12pt Arial, Helvetica, sans-serif;
	        }

	        * {
	            box-sizing: border-box;
	            -moz-box-sizing: border-box;
	        }

	        .page {
	            width: 50cm;
	            min-height: 21cm;
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
	            /*size: auto;
    			size: A3;*/
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

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top"><?php echo $ReportName; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$h1_title"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic; display:none">
                    <td width="8%" nowrap valign="top"><?php echo $Periode; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php // echo "$StartDate s.d. $EndDate"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top"><?php echo $ProjectCode; ?></td>
                    <td width="0%">:</td>
                    <td width="92%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $ProjectName; ?></td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              	</tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $PrintDate; ?></td>
                  <td>:</td>
                  <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
    	</div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
        	<table width="100%" border="1" rules="all" style="border-color: black;">
                <tr style="background:#CCCCCC">
                  	<td width="3%" nowrap style="text-align:center; font-weight:bold">NO</td>
                  	<td width="9%" nowrap style="text-align:center; font-weight:bold"><?php echo $ItemCode; ?></td>
                  	<td width="30%" nowrap style="text-align:center; font-weight:bold"><?php echo $Description; ?></td>
                  	<td width="4%" nowrap style="text-align:center; font-weight:bold"><?php echo $Unit; ?></td>
                  	<td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Budget; ?><br>(A)</td>
                  	<td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Requested; ?><br>(B)</td>
                  	<td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $Ordered; ?><br>(C)</td>
                  	<td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $Received; ?><br>(D)</td>
                  	<td width="7%" nowrap style="text-align:center; font-weight:bold"><?php echo $Used; ?><br>(E)</td>
                	<td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo "$Remain $Budget"; ?><br>(A-C)</td>
                  	<td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo "$Remain $Stock"; ?><br>(D-E)</td>
              	</tr>
              	<?php
					$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
			  		$addQITM 	= "";
					if($ITM_CODE[0] != 1) $addQITM = "AND A.ITM_CODE = '$ITM_CODE'";

					/*$sqlStock 	= "SELECT ITM_CODE, ITM_GROUP, ITM_NAME, ITM_UNIT,
									ITM_VOLMBG, ITM_VOLM, ITM_PRICE, ITM_TOTALP
									AMD_VOL, AMD_VAL, AMD_VOL_R, AMD_VAL_R, AMDM_VOL, AMDM_VAL,
									PR_VOL, PR_VAL, PR_VOL_R, PR_VAL_R, PR_CVOL, PR_CVAL,
									PO_VOL, PO_VAL, PO_VOL_R, PO_VAL_R, PO_CVOL, PO_CVAL, 
									IR_VOL, IR_VAL, IR_VOL_R, IR_VAL_R, 
									UM_VOL, UM_VAL, UM_VOL_R, UM_VAL_R
									FROM tbl_item_$PRJCODEVW
									WHERE ITM_GROUP IN ('M','T') $addQITM
									ORDER BY ITM_NAME";*/
					$sqlStock 	= "SELECT A.ITM_CODE, A.ITM_GROUP, B.ITM_NAME, A.ITM_UNIT,
									SUM(A.ITM_VOLM) AS ITM_VOLMBG, SUM(A.ITM_BUDG) AS ITM_TOTALP
									FROM tbl_joblist_detail_$PRJCODEVW A INNER JOIN tbl_item_$PRJCODEVW B ON A.ITM_CODE = B.ITM_CODE
									WHERE A.ITM_GROUP IN ('M','T') $addQITM
									GROUP BY A.ITM_CODE
									ORDER BY B.ITM_NAME";
					$resStock 	= $this->db->query($sqlStock);
					if($resStock->num_rows() > 0)
					{
						$therow		= 0;
						foreach($resStock->result() as $rowsqlq1):
							$therow		= $therow + 1;
							$ITM_CODE	= $rowsqlq1->ITM_CODE;
							$ITM_GROUP	= $rowsqlq1->ITM_GROUP;
							$ITM_DESC 	= $rowsqlq1->ITM_NAME;
							$ITM_UNIT	= $rowsqlq1->ITM_UNIT;
							$ITM_VOLB	= $rowsqlq1->ITM_VOLMBG;
							$ITM_VALB	= $rowsqlq1->ITM_TOTALP;

							$s_isLS = "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
							$r_isLS = $this->db->count_all($s_isLS);

							$ITM_VOL2	= 0;
							$ITM_VAL2	= 0;
							$PRT_VOL 	= 0;
							$PRT_VAL 	= 0;
							$POT_VOL	= 0;
							$POT_VAL	= 0;
							$IRT_VOL 	= 0;
							$IRT_VAL 	= 0;
							$UMT_VOL	= 0;
							$UMT_VAL	= 0;
							$REMB_VOL 	= 0;
							$REMB_VAL 	= 0;
							$REMR_VOL	= 0;
							$REMR_VAL	= 0;
							$sNOW 	= "SELECT 	SUM(AMD_VOL - AMDM_VOL) AS AMD_VOL, SUM(AMD_VAL - AMDM_VAL) AS AMD_VAL,
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
										FROM tbl_item_logbook_$PRJCODEVW
										WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'
										GROUP BY ITM_CODE";
							$rNOW 	= $this->db->query($sNOW);
							foreach($rNOW->result() as $rwNOW):
								$AMD_VOL	= $rwNOW->AMD_VOL;
								$AMD_VAL 	= $rwNOW->AMD_VAL;

								$PR_VOL		= $rwNOW->PR_VOL;
								$PR_VAL 	= $rwNOW->PR_VAL;
								$PR_VOL_R	= $rwNOW->PR_VOL_R;
								$PR_VAL_R 	= $rwNOW->PR_VAL_R;
								$PR_CVOL	= $rwNOW->PR_CVOL;
								$PR_CVAL 	= $rwNOW->PR_CVAL;

								$PO_VOL		= $rwNOW->PO_VOL;
								$PO_VAL 	= $rwNOW->PO_VAL;
								$PO_VOL_R	= $rwNOW->PO_VOL_R;
								$PO_VAL_R 	= $rwNOW->PO_VAL_R;
								$PO_CVOL	= $rwNOW->PO_CVOL;
								$PO_CVAL 	= $rwNOW->PO_CVAL;

								$IR_VOL		= $rwNOW->IR_VOL;
								$IR_VAL 	= $rwNOW->IR_VAL;
								$IR_VOL_R	= $rwNOW->IR_VOL_R;
								$IR_VAL_R 	= $rwNOW->IR_VAL_R;

								$UM_VOL		= $rwNOW->UM_VOL;
								$UM_VAL 	= $rwNOW->UM_VAL;
								$UM_VOL_R	= $rwNOW->UM_VOL_R;
								$UM_VAL_R 	= $rwNOW->UM_VAL_R;

								$WO_VOL		= $rwNOW->WO_VOL;
								$WO_VAL 	= $rwNOW->WO_VAL;
								$WO_VOL_R	= $rwNOW->WO_VOL_R;
								$WO_VAL_R 	= $rwNOW->WO_VAL_R;
								$WO_CVOL	= $rwNOW->WO_CVOL;
								$WO_CVAL 	= $rwNOW->WO_CVAL;

								$OPN_VOL	= $rwNOW->OPN_VOL;
								$OPN_VAL 	= $rwNOW->OPN_VAL;
								$OPN_VOL_R	= $rwNOW->OPN_VOL_R;
								$OPN_VAL_R 	= $rwNOW->OPN_VAL_R;

								$VCASH_VOL	= $rwNOW->VCASH_VOL;
								$VCASH_VAL 	= $rwNOW->VCASH_VAL;
								$VCASH_VOL_R= $rwNOW->VCASH_VOL_R;
								$VCASH_VAL_R= $rwNOW->VCASH_VAL_R;

								$VLK_VOL	= $rwNOW->VLK_VOL;
								$VLK_VAL 	= $rwNOW->VLK_VAL;
								$VLK_VOL_R	= $rwNOW->VLK_VOL_R;
								$VLK_VAL_R 	= $rwNOW->VLK_VAL_R;

								$PPD_VOL	= $rwNOW->PPD_VOL;
								$PPD_VAL 	= $rwNOW->PPD_VAL;
								$PPD_VOL_R	= $rwNOW->PPD_VOL_R;
								$PPD_VAL_R 	= $rwNOW->PPD_VAL_R;

								// AFTER ADDEUNDUM
									$ITM_VOL2 	= $ITM_VOLB + $AMD_VOL;
									$ITM_VAL2 	= $ITM_VALB + $AMD_VAL;

								// TOTAL DIMINTA
									$PRT_VOL 	= $PR_VOL+$PR_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$PR_CVOL-$WO_CVOL;
									$PRT_VAL 	= $PO_VAL+$PO_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$PO_CVAL-$WO_CVAL;

								// TOTAL DIPESAN
									$POT_VOL 	= $PO_VOL+$PO_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$PO_CVOL-$WO_CVOL;
									$POT_VAL 	= $PO_VAL+$PO_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$PO_CVAL-$WO_CVAL;

								// TOTAL DITERIMA
									$IRT_VOL 	= $IR_VOL+$IR_VOL_R+$WO_VOL+$WO_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R-$WO_CVOL;
									$IRT_VAL 	= $IR_VAL+$IR_VAL_R+$WO_VAL+$WO_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R-$WO_CVAL;

								// TOTAL DIGUNAKAN
									$UMT_VOL 	= $UM_VOL+$UM_VOL_R+$OPN_VOL+$OPN_VOL_R+$VCASH_VOL+$VCASH_VOL_R+$VLK_VOL+$VLK_VOL_R+$PPD_VOL+$PPD_VOL_R;
									$UMT_VAL 	= $UM_VAL+$UM_VAL_R+$OPN_VAL+$OPN_VAL_R+$VCASH_VAL+$VCASH_VAL_R+$VLK_VAL+$VLK_VAL_R+$PPD_VAL+$PPD_VAL_R;

								// TOTAL SISA
									$REMB_VOL	= $ITM_VOL2 - $POT_VOL;
									$REMB_VAL	= $ITM_VAL2 - $POT_VAL;
									$REMR_VOL	= $IRT_VOL - $UMT_VOL;
									$REMR_VAL	= $IRT_VAL - $UMT_VAL;
							endforeach;

							$alrtStyl1 		= "";
							$alrtStyl2 		= "";
							$alrtStyl3 		= "";
							$alrtStyl4 		= "";
							$alrtStyl5 		= "";
							$alrtStyl6 		= "";

							if($r_isLS == 0)						// NOT LS
							{
								if(round($PRT_VOL, 2) > round($ITM_VOL2, 2))
								{
									$alrtStyl1 	= "background-color: gray;";
								}
								if(round($POT_VOL, 2) > round($PRT_VOL, 2))
								{
									$alrtStyl2 	= "background-color: gray;";
								}
								if(round($IRT_VOL, 2) > round($POT_VOL, 2))
								{
									$alrtStyl3 	= "background-color: gray;";
								}
								if(round($UMT_VOL, 2) > round($IRT_VOL, 2))
								{
									$alrtStyl4 	= "background-color: gray;";
								}
								if(round($REMB_VOL, 2) < 0)
								{
									$alrtStyl5 	= "background-color: gray;";
								}
								if(round($REMR_VOL, 2) < 0)
								{
									$alrtStyl6	= "background-color: gray;";
								}

								// AFTER ADDEUNDUM
									$ITM_VOL2V 	= $ITM_VOL2;

								// TOTAL DIMINTA
									$PRT_VOLV 	= $PRT_VOL;

								// TOTAL DIPEASN
									$POT_VOLV 	= $POT_VOL;

								// TOTAL DITERIMA
									$IRT_VOLV 	= $IRT_VOL;

								// TOTAL DIGUNAKAN
									$UMT_VOLV 	= $UMT_VOL;

								// TOTAL SISA
									$REMB_VOLV	= $REMB_VOL;
									$REMR_VOLV	= $REMR_VOL;
							}
							else									// LS
							{
								if(round($PRT_VAL, 2) > round($ITM_VAL2, 2))
								{
									$alrtStyl1 	= "background-color: gray;";
								}
								if(round($POT_VAL, 2) > round($PRT_VAL, 2))
								{
									$alrtStyl2 	= "background-color: gray;";
								}
								if(round($IRT_VAL, 2) > round($POT_VAL, 2))
								{
									$alrtStyl3 	= "background-color: gray;";
								}
								if(round($UMT_VAL, 2) > round($IRT_VAL, 2))
								{
									$alrtStyl4 	= "background-color: gray;";
								}
								if(round($REMB_VAL, 2) < 0)
								{
									$alrtStyl5 	= "background-color: gray;";
								}
								if(round($REMR_VAL, 2) < 0)
								{
									$alrtStyl6 	= "background-color: gray;";
								}

								// AFTER ADDEUNDUM
									$ITM_VOL2V 	= $ITM_VAL2;

								// TOTAL DIMINTA
									$PRT_VOLV 	= $PRT_VAL;

								// TOTAL DIPEASN
									$POT_VOLV 	= $POT_VAL;

								// TOTAL DITERIMA
									$IRT_VOLV 	= $IRT_VAL;

								// TOTAL DIGUNAKAN
									$UMT_VOLV 	= $UMT_VAL;

								// TOTAL SISA
									$REMB_VOLV	= $REMB_VAL;
									$REMR_VOLV	= $REMR_VAL;
							}

							$vwPRD		= "$ITM_CODE~$PRJCODE~PR~$r_isLS";
							$vwPOD		= "$ITM_CODE~$PRJCODE~PO~$r_isLS";
							$vwIRD		= "$ITM_CODE~$PRJCODE~IR~$r_isLS";
							$vwUMD		= "$ITM_CODE~$PRJCODE~UM~$r_isLS";
							$secvwPRD 	= site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwPRD));
							$secvwPOD 	= site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwPOD));
							$secvwIRD 	= site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwIRD));
							$secvwUMD 	= site_url('c_project/c_r3p/shwItm_H15tDET/?id='.$this->url_encryption_helper->encode_url($vwUMD));
							?>
								<tr>
									<td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
									<td nowrap style="text-align:left;"><?php echo "$ITM_CODE"; ?></td>
									<td width="30%" nowrap style="text-align:left;"><?php echo $ITM_DESC; ?></td>
									<td width="4%" nowrap style="text-align:center;"><?php echo $ITM_UNIT; ?></td>
									<td nowrap style="text-align:right;"><?php echo number_format($ITM_VOL2V,$decFormat);?></td>
									<td width="8%" nowrap style="text-align:right; text-decoration: underline; border-right-color:#000; border-right-width:2px; <?=$alrtStyl1?>">										
										<a onclick="showITMDET('<?php echo $secvwPRD; ?>')" style="cursor: pointer;">
											<?php echo number_format($PRT_VOLV, 2); ?>
										</a>
									</td>
									<td width="8%" nowrap style="text-align:right; text-decoration: underline; border-right-color:#000; border-right-width:2px; <?=$alrtStyl2?>">
										<a onclick="showITMDET('<?php echo $secvwPOD; ?>')" style="cursor: pointer;">
											<?php echo number_format($POT_VOLV, 2); ?>
										</a>
									</td>
									<td width="7%" nowrap style="text-align:right; text-decoration: underline; border-right-color:#000; border-right-width:2px; <?=$alrtStyl3?>">										
										<a onclick="showITMDET('<?php echo $secvwIRD; ?>')" style="cursor: pointer;">
											<?php echo number_format($IRT_VOLV, 2); ?>
										</a>
									</td>
									<td width="7%" nowrap style="text-align:right; text-decoration: underline; border-right-color:#000; border-right-width:2px; <?=$alrtStyl4?>">										
										<a onclick="showITMDET('<?php echo $secvwUMD; ?>')" style="cursor: pointer;">
											<?php echo number_format($UMT_VOLV, 2); ?>
										</a>
									</td>
									<td width="8%" nowrap style="text-align:right; <?=$alrtStyl5?>"><?php echo number_format($REMB_VOLV,$decFormat); ?></td>
									<td width="8%" nowrap style="text-align:right; <?=$alrtStyl6?>"><?php echo number_format($REMR_VOLV,$decFormat); ?></td>
								</tr>
							<?php
						endforeach;
					}
					else
					{
						?>
						<tr>
						  <td colspan="13" nowrap style="text-align:center;">--- none ---</td>
						</tr>
                        <?php
					}
				?>
            </table>
      	</div>
    </div>
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