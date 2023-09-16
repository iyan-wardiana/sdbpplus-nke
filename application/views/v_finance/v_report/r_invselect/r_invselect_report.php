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
$comp_name 	= $this->session->userdata['comp_name'];
$appBody    = $this->session->userdata('appBody');

if($CFType == 1)
	$CFTyped	= "Detail";
else
	$CFTyped	= "Summary";

$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJDATE_CO, A.PRJEDAT,
			A.PRJCOST, A.PRJCATEG,
			A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.ISCHANGE, A.REFCHGNO, A.PRJCOST2, 
			A.PRJ_MNG, A.PRJBOQ,
			A.CHGUSER, A.CHGSTAT, A.PRJPROG, A.QTY_SPYR, A.PRC_STRK, A.PRC_ARST, A.PRC_MKNK, A.PRC_ELCT, A.PRJ_IMGNAME
		FROM tbl_project A
		WHERE A.PRJCODE = $COLREFPRJ";

$resPROJ	= $this->db->query($sql)->result();
foreach($resPROJ as $rowPROJ):
	$PRJCODE	= $rowPROJ->PRJCODE;
	$PRJNAME	= $rowPROJ->PRJNAME;
	$PRJCNUM	= $rowPROJ->PRJCNUM;
	$PRJDATE	= $rowPROJ->PRJDATE;
	$PRJEDAT	= $rowPROJ->PRJEDAT;
	$PRJOWN		= $rowPROJ->PRJOWN;
	$PRJLOCT	= $rowPROJ->PRJLOCT;
	
	$own_Code 	= '';
	$own_Name	= 'none';
	$CountOwn 	= $this->db->count_all('tbl_owner');
	$sqlOwn 	= "SELECT own_Name FROM tbl_owner WHERE own_Code = '$PRJOWN'";
	$resultOwn = $this->db->query($sqlOwn)->result();
	if($CountOwn > 0)
	{
	  foreach($resultOwn as $rowOwn) :
		  $own_Name = $rowOwn->own_Name;
	  endforeach;
	}
endforeach;

$Start_Date	= date('Y-m-d', strtotime($Start_Date));
$End_Date	= date('Y-m-d', strtotime($End_Date));

$StartDate	= date("d M Y", strtotime($Start_Date));
$EndDate	= date("d M Y", strtotime($End_Date));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata('vers');

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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

  	<style>
    	body
    	{
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		*{
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
	        width: 29.7cm;
	        min-height: 21cm;
	        padding: 0.5cm;
	        margin: 0.5cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .subpage {
	        padding: 1cm;
	        height: 256mm;
	    }
	    
	    @page {
	        size: A4;
	        margin: 0;
	    }
	    @media print {
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
		
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>

	<body style="overflow:auto">
		<div class="page">
			<section class="content">
			    <table width="100%" border="0" style="size:auto">
				    <tr>
				        <td width="3%" class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
				        <td width="58%" class="style2">&nbsp;</td>
				        <td width="39%" class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
				    </tr>
				    <tr>
				        <td valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
				        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; font-size:18px"><?php echo $mnName; ?> (<?php echo $CFTyped; ?>)<br>
				          <span class="style2" style="text-align:center; font-weight:bold; font-size:16px; display:none"><?php echo $comp_name; ?></span></td>
				  	</tr>
				    <tr>
				      <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
				        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
				            	<tr>
				                	<td width="17%">Nomor &amp; Nama Proyek </td>
				                    <td width="1%">:</td>
				                    <td width="38%"><?php echo "$PRJCODE - $PRJNAME";?></td>
				                    <td width="18%">Pemilik</td>
				                    <td width="0%">:</td>
				                    <td width="26%">
				                    <?php
									  echo $own_Name;
								  	?>
				                    </td>
				                </tr>
				            	<tr>
				            	  <td>Periode </td>
				            	  <td>:</td>
				            	  <td><?php echo "$StartDate s.d. $EndDate";?></td>
				            	  <td>Lokasi</td>
				            	  <td>:</td>
				            	  <td><?=$PRJLOCT?></td>
				          	  </tr>
				            	<tr>
				            	  <td>Tanggal Cetak</td>
				            	  <td>:</td>
				            	  <td><?php echo date("d M Y");?></td>
				            	  <td>&nbsp;</td>
				            	  <td>&nbsp;</td>
				            	  <td>&nbsp;</td>
				          	  </tr>
				            </table>
				        </td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:center"><hr noshade /></td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2">
				            <table width="100%" border="1" rules="all">
				                <tr style="background:#CCCCCC">
				                    <td width="4%" nowrap style="text-align:center; font-weight:bold">NO.</td>
				                  <td width="9%" nowrap style="text-align:center; font-weight:bold">Tanggal</td>
				                  <td width="17%" nowrap style="text-align:center; font-weight:bold">No Faktur</td>
				                  <td width="9%" nowrap style="text-align:center; font-weight:bold">Tgl. Faktur</td>
				                  <td width="40%" nowrap style="text-align:center; font-weight:bold">Supplier</td>
				                  <td width="10%" nowrap style="text-align:center; font-weight:bold">Jatuh Tempo</td>
				                  <td width="11%" nowrap style="text-align:center; font-weight:bold">Nilai Faktur<br>Rp</td>
				              </tr>
				                <?php
									$therow		= 0;

									$sql0 		= "tbl_pinv_header 
													WHERE PRJCODE IN ($COLREFPRJ)
														AND selectedD >= '$Start_Date' AND selectedD <= '$End_Date' 
														AND SPLCODE IN ($COLREFSPL) AND INV_STAT = '3'";
									$sql1 		= "SELECT selectedD, SPLCODE, INV_CODE, INV_DATE, INV_DUEDATE, 
														(INV_AMOUNT) AS TOTAMOUNT, (INV_LISTTAXVAL) AS INV_TAXVAL
													FROM tbl_pinv_header 
													WHERE PRJCODE IN ($COLREFPRJ)
														AND selectedD >= '$Start_Date' AND selectedD <= '$End_Date' 
														AND SPLCODE IN ($COLREFSPL) AND INV_STAT = 3";
														
									$res0 			= $this->db->count_all($sql0);
									$res1 			= $this->db->query($sql1)->result();
									
									if($res0 > 0)
									{
										foreach($res1 as $row1) :
											$therow			= $therow + 1;
											$selectedD 		= $row1->selectedD;
											$INV_CODE 		= $row1->INV_CODE;
											$INV_DATE 		= $row1->INV_DATE;
											$INV_DUEDATE 	= $row1->INV_DUEDATE;
											$SPLCODE 		= $row1->SPLCODE;
											$INV_AMOUNT		= $row1->TOTAMOUNT;
											$INV_TAXVAL		= $row1->INV_TAXVAL;
											$INV_AMOUNT_TOT	= $INV_AMOUNT + $INV_TAXVAL;
											
											$SPLDESC	= '';
											$sql2 		= "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
											$res2 		= $this->db->query($sql2)->result();
											foreach($res2 as $row2) :
												$SPLDESC 	= $row2->SPLDESC;
											endforeach;
											?>
											<tr>
												<td nowrap style="text-align:left;">
													<?php echo "$therow."; ?>
												</td>
												<td nowrap style="text-align:left;">&nbsp;
													<?php echo "$selectedD"; ?>
												</td>
												<td nowrap style="text-align:left;">&nbsp;
													<?php echo "$INV_CODE"; ?>
												</td>
												<td nowrap style="text-align:center;">&nbsp;
												  <?php echo $INV_DATE; ?>
												</td>
												<td nowrap style="text-align:left;">&nbsp;
													<?php echo $SPLDESC; ?>
												</td>
												<td nowrap style="text-align:center;">
													<?php echo $INV_DUEDATE; ?>
												</td>
												<td style="text-align:right;" nowrap>&nbsp;
													<?php echo number_format($INV_AMOUNT_TOT, 2); ?>
												</td>
											</tr>
											<?php
										endforeach;
									}
									else
									{
										?>
											<tr>
												<td colspan="11" nowrap style="text-align:center; font-style:italic">-- No Data --</td>
											</tr>
										<?php
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