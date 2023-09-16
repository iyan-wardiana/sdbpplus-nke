<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Junli 2018
	* File Name	= profit_loss_view.php
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

$PeriodeD 		= date('Y-m-d',strtotime($END_DATE));

if($CATEGREP 		== 'M')
	$CATEGREPD 			= "M / Material";
elseif($CATEGREP 	== 'U')
	$CATEGREPD 			= "U / Upah";
elseif($CATEGREP 	== 'S')
	$CATEGREPD 			= "SUB / Subkon";
elseif($CATEGREP 	== 'T')
	$CATEGREPD 			= "T / Alat";
elseif($CATEGREP 	== 'I')
	$CATEGREPD 			= "I / Rupa-Rupa";
elseif($CATEGREP 	== 'R')
	$CATEGREPD 			= "Overhead Lapangan";
elseif($CATEGREP 	== 'O')
	$CATEGREPD 			= "Overhead";
else
	$CATEGREPD 			= "Lainnya";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
		<title><?php echo $h1_title; ?></title>
		
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
		                            <td width="15%" nowrap>PROYEK</td>
		                          	<td width="1%">:</td>
		                            <td style="text-align:left; font-weight:bold"><?php echo strtoupper($PRJNAME); ?></td>
		                      	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>PERIODE</td>
		                          	<td>:</td>
		                          	<td style="text-align:left;">s.d. <?php echo $PeriodeD;?></td>
		                       	</tr>
		                        <tr style="text-align:left;">
		                         	<td nowrap>KATEGORI</td>
		                          	<td>:</td>
		                          	<td><?php echo $CATEGREPD;?> <span onClick="exptoXls()" style="cursor: pointer; text-decoration:none;" title="Export ke Excel"><i class="fa fa-file-excel-o"></i></span></td>
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
		                      	<td width="11%" style="text-align:center">Kode</td>
		                      	<td width="12%" style="text-align:center">Tanggal</td>
		                        <td width="40%" style="text-align:center">Deskripsi</td>
		                        <td width="10%" style="text-align:center" nowrap>Jumlah</td>
		                        <td width="10%" style="text-align:center" nowrap>Total</td>
		                    </tr>
		                    <?php
								$no		= 0;
								$totAm	= 0;
								$sqlJD	= "SELECT DISTINCT
												A.DOC_ID,
												A.ITM_CODE,
												A.DOC_NUM,
												A.DOC_CODE,
												A.DOC_CATEG,
												A.DOC_CODE,
												A.DOC_DATE,
												A.DOC_VAL,
												A.DOC_DESC,
												A.DOC_STAT
											FROM
												tbl_item_logbook_$PRJCODEVW A
                                                WHERE
													A.ITM_GROUP IN ('$CATEGREP')
                                                    AND A.PRJCODE = '$PRJCODE'
													AND A.DOC_DATE <= '$END_DATE'
													AND A.DOC_CATEG IN ('IR','OPN','VCASH','CPRJ','PPD')
                                                ORDER BY A.DOC_DATE, A.DOC_CODE";
								$resJD	= $this->db->query($sqlJD)->result();
								foreach($resJD as $rowJD) :
									$no				= $no+1;
									$DOC_NUM 		= $rowJD->DOC_NUM;
									$DOC_CODE 		= $rowJD->DOC_CODE;
									if($DOC_CODE == '')
										$DOC_CODE	= $DOC_NUM;

									$DOC_DATE 		= $rowJD->DOC_DATE;
									$DOC_CATEG 		= $rowJD->DOC_CATEG;
									$DOC_VAL 		= $rowJD->DOC_VAL;							
									$totAm			= $totAm + $DOC_VAL;
									$DOC_DESC 		= $rowJD->DOC_DESC;							
									if($DOC_DESC == '')
									{
										if($DOC_CATEG == 'UM')
											$DOC_DESC	= "Penggunaan material";
										elseif($DOC_CATEG == 'OPN')
											$DOC_DESC	= "Opname";
									}
									?>
		                                <tr>
		                                    <td style="text-align:center"><?php echo $no; ?></td>
		                                    <td style="text-align:center" nowrap><?php echo $DOC_CODE; ?></td>
		                                    <td style="text-align:center" nowrap><?php echo $DOC_DATE; ?></td>
		                                    <td style="text-align:left"><?php echo $DOC_DESC; ?></td>
		                                    <td style="text-align:right"><?php echo number_format($DOC_VAL); ?></td>
		                                    <td style="text-align:right"><?php echo number_format($totAm); ?></td>
		                                </tr>
		                    		<?php
								endforeach;
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