<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Januari 2018
	* File Name	= print_polist.php
	* Location		= -
*/
$dateDL = date('YmdHis');
if(isset($_POST['submit1']))
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Detil.Trx_$dateDL.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$Periode1 = date('YmdHis');
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$comp_name 	= $this->session->userdata['comp_name'];

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;

$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$PeriodeD 		= date('Y-m-d');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title>Daftar PO</title>
		
        <?php
			$vers     = $this->session->userdata['vers'];

			$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
			$rescss = $this->db->query($sqlcss)->result();
			foreach($rescss as $rowcss) :
			  	$cssjs_lnk  = $rowcss->cssjs_lnk;
			  	?>
			      	<link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
			  	<?php
			endforeach;

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
	<body style="overflow:auto">
        <form class="form-horizontal" name="frm1" id="frm1" method="post" action="" style="display: none;">
            <input type="submit" class="button_css" name="submit1" id="submit1" value="Submit" align="left" />
        </form>
		<div class="page">
			<section class="content">
		        <table width="100%" border="0" style="size:auto">
		            <tr>
		                <td width="100%" class="style2" style="text-align:left;">&nbsp;</td>
		            </tr>
		          	<tr>
		                <td class="style2" style="text-align:left;">
		                    <table width="100%" style="size:auto; font-size:12px;">
		                        <tr style="text-align:left;">
		                            <td width="15%" nowrap>NAMA LAPORAN</td>
		                          	<td width="1%">:</td>
		                            <td style="text-align:left; font-weight:bold">DAFTAR PEMBELIAN (PO)</span></td>
		                      	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>PROYEK</td>
		                          	<td>:</td>
		                          	<td style="text-align:left; font-weight:bold"><?php echo strtoupper($PRJCODE). " - " .strtoupper($PRJNAME); ?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>KODE SPP</td>
		                          	<td>:</td>
		                          	<td><?=$PR_CODE?> <span onClick="exptoXls()" style="cursor: pointer; text-decoration:none;" title="Export ke Excel"><i class="fa fa-file-excel-o"></i></span></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                          	<td nowrap valign="top">&nbsp;</td>
		                          	<td>&nbsp;</td>
		                          	<td>&nbsp;</td>
		                        </tr>
		                    </table>
				    	</td>
		            </tr>
		            <tr>
						<td class="style2" style="text-align:left; font-size:12px">
		              	<table width="100%" border="1" style="size:auto; font-size:12px;" rules="all">
		                	<tr style="font-weight:bold; text-align:center; background:#CCC; font-size:14px;">
		                    	<td width="5%" style="text-align:center">&nbsp;No.&nbsp;</td>
		                      	<td width="10%" style="text-align:center">Kode</td>
		                      	<td width="10%" style="text-align:center">Tanggal</td>
		                        <td width="20%" style="text-align:center">Supplier</td>
		                        <td width="50%" style="text-align:center" nowrap>Deskripsi</td>
		                        <td width="5%" style="text-align:center" nowrap>Status</td>
		                    </tr>
			                <?php
			                    $i = 0;
			                    $j = 0;
			                    if($countPO>0)
			                    {
			                        $totRow	= 0;
			                        foreach($vwPO as $row) :
										$i			= $i + 1;
										$PR_NUM 	= $row->PR_NUM;
										$PR_DATE 	= $row->PR_DATE;
										$PO_NUM 	= $row->PO_NUM;
										$PO_CODE 	= $row->PO_CODE;
										$PO_DATE 	= $row->PO_DATE;
										$PO_DUED 	= $row->PO_DUED;
										$SPLCODE 	= $row->SPLCODE;
										$SPLDESC 	= $row->SPLDESC;
										$PO_NOTES 	= $row->PO_NOTES;
										$PO_STAT 	= $row->PO_STAT;

										if($PO_STAT == 1)
											$PO_STATD 	= "New / Draft";
										elseif($PO_STAT == 2)
											$PO_STATD 	= "Confirmed";
										elseif($PO_STAT == 3)
											$PO_STATD 	= "Approved";
										elseif($PO_STAT == 4)
											$PO_STATD 	= "Revised";
										elseif($PO_STAT == 5)
											$PO_STATD 	= "Rejected";
										elseif($PO_STAT == 6)
											$PO_STATD 	= "Closed";
										elseif($PO_STAT == 7)
											$PO_STATD 	= "Awaiting";
										elseif($PO_STAT == 9)
											$PO_STATD 	= "Void / Cancelled";
										else
											$PO_STATD 	= "-";
										
			                            $totRow		= $totRow + 1;
									
										/*if ($j==1) {
											echo "<tr class=zebra1>";
											$j++;
										} else {
											echo "<tr class=zebra2>";
											$j--;
										}*/
										?>
			                            <td style="text-align:center" nowrap><?php echo $i; ?>.</td>
			                            <td nowrap><?php echo $PO_CODE; ?></td>
			                            <td style="text-align:center"><?php echo $PO_DATE; ?></td>
			                            <td nowrap><?php echo"$SPLCODE - $SPLDESC"; ?>&nbsp;</td>
			                            <td><?php echo"$PO_NOTES"; ?>&nbsp;</td>
			                            <td nowrap><?php echo"$PO_STATD"; ?>&nbsp;</td>
			                        </tr>
			                        <?php
			                        endforeach;
			                    }
			                ?>
		                </table>
		           	  </td>
		            </tr>
		        </table>
			</section>
		</div>
	</body>
</html>
<script>
	function exptoXls() 
	{
		document.frm1.submit1.click();
	}
</script>