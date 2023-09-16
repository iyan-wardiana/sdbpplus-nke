<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Agustus 2018
 * File Name	= r_invselect_report.php
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
$appBody    = $this->session->userdata('appBody');
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
                    <td><img src="<?= base_url('assets/AdminLTE-2.0.5/dist/img/compLog/NKES/compLog.png') ?>" alt="" width="181" height="44"></td>
                    <td>
                        <h3><?php //echo $h1_title; ?></h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="padding-top: 10px; font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td><?php echo "$h1_title"; ?></td>
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
                <tr>
                    <td width="100">Periode</td>
                    <td width="10">:</td>
                    <td>
                    	<?php
				            $StartDate1 = date('d/m/Y',strtotime($Start_Date));
				            $EndDate1 = date('d/m/Y',strtotime($End_Date));
							echo $EndDate1;
				        ?>
                    </td>
                </tr>
                <tr>
                    <td width="100">Tgl. Cetak</td>
                    <td width="10">:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
            <table width="100%" border="1" rules="all">
			    <tr>
			        <td colspan="3" class="style2">
			            <table width="100%" border="1" rules="all">
			                <tr style="background:#CCCCCC">
			                  	<th width="2%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
			                  	<th width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL.</th>
			                  	<th colspan="8" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">UANG MUKA / DOWN PAYMENT (DP)</th>
			              	</tr>
			                <tr style="background:#CCCCCC">
								<th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">PROYEK</th>
								<th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">PEMASOK</th>
								<th width="19%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">DESKRIPSI</th>
								<th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">KATEG. ITEM</th>
								<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TOTAL DP</th>
								<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL POT.DP</th>
								<th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL SISA DP</th>
								<th width="13%" nowrap style="text-align:center; font-weight:bold;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">KETERANGAN</th>
			                </tr>
			              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
			                <td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td nowrap style="text-align:center;border:none">&nbsp;</td>
								<td colspan="3" nowrap style="text-align:center;border:none">&nbsp;</td>
			               	</tr>
			               	<?php
			               		if($COLREFPRJ == 1) 	// All Project
			               		{
			               			$qryPrj 	= "";
			               		}
			               		else
			               		{
			               			$qryPrj 	= "AND PRJCODE = '$COLREFPRJ'";
			               		}

			               		if($COLREFSPL == 1) 	// All Supplier
			               		{
			               			$qrySpl 	= "";
			               		}
			               		else
			               		{
			               			$qrySpl 	= "AND SPLCODE IN ($COLREFSPL)";
			               		}

			                    $theRow         = 0;
			                    $TAMD_AMOUNT    = 0;
			                    $sqlAMC         = "tbl_dp_header A
			                                        WHERE DP_STAT = 3 AND (DP_DATE BETWEEN '$Start_Date' AND '$End_Date')
			                                        $qryPrj $qrySpl";
			                    $resAMC         = $this->db->count_all($sqlAMC);
			                    if($resAMC > 0)
			                    {
			                        $sqlAM      = "SELECT A.PRJCODE, A.ITM_CODE, A.ITM_NAME, A.ITM_UNIT, A.ITM_VOLMBG, A.ITM_PRICE, 
			                                            A.ITM_LASTP, A.ITM_TOTALP,
			                                            SUM(B.AMD_VOLM) AS ADDVOLM, SUM(B.AMD_TOTAL) AS ADDCOST, A.ADDMVOLM, A.ADDMCOST,
			                                            C.AMD_CODE, C.AMD_CATEG, C.AMD_JOBDESC
			                                        FROM tbl_item A
			                                            INNER JOIN tbl_amd_detail B ON B.ITM_CODE = A.ITM_CODE
			                                                AND B.PRJCODE = '$PRJCODE'
			                                            INNER JOIN tbl_amd_header C ON C.AMD_NUM = B.AMD_NUM
			                                                AND C.PRJCODE = '$PRJCODE'
			                                        WHERE A.PRJCODE = '$PRJCODE' AND C.AMD_STAT = 3
			                                            AND (C.AMD_DATE BETWEEN '$Start_Date' AND '$End_Date')
			                                            GROUP BY A.ITM_CODE";
			                        $resAM      = $this->db->query($sqlAM)->result();
			                        foreach($resAM as $rowAM):
			                            $theRow     = $theRow + 1;
			                            $PRJCODE    = $rowAM->PRJCODE;
			                            $ITM_CODE   = $rowAM->ITM_CODE;
			                            $ITM_NAME   = $rowAM->ITM_NAME;
			                            $ITM_UNIT   = $rowAM->ITM_UNIT;
			                            $ITM_VOLMBG = $rowAM->ITM_VOLMBG;
			                            $ITM_PRICE  = $rowAM->ITM_PRICE;
			                            $ITM_LASTP  = $rowAM->ITM_LASTP;
			                        endforeach;
			                    }
			               	?>
			               	<tr>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
			               	</tr>
			               	<tr>
								<td colspan="6" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000"><b>TOTAL</b></td>
								<td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
								<td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
			               	</tr>
			                <tr style="display:none">
			                  <td colspan="10" nowrap style="text-align:center;">--- none ---</td>
			                </tr>
			            </table>
			      </td>
			    </tr>
			</table>
		</div>
	</body>
</body>
</html>