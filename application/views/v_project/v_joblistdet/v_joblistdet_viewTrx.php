<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 01 Agustus 2022
	* File Name		= v_joblistdet_viewTrx.php
	* Location		= -
*/
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
$PRJCODEVW 		= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
	<title><?=$h1_title?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  	<style>
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
	        width: 21cm;
	        min-height: 29.7cm;
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
</head>
<body style="overflow:auto">
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
	                         	<td nowrap>KODE PEK.</td>
	                          	<td>:</td>
	                            <td style="text-align:left; font-weight:bold"><?php echo strtoupper($JOBCODEID); ?></td>
	                       	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>NAMA PEK.</td>
	                          	<td>:</td>
	                            <td style="text-align:left; font-weight:bold"><?php echo strtoupper($JDESC); ?></td>
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
	                    	<td width="2%" style="text-align:center">&nbsp;No.&nbsp;</td>
	                      	<td width="15%" style="text-align:center">Kode</td>
	                      	<td width="8%" style="text-align:center">Tanggal</td>
	                      	<td width="5%" style="text-align:center">Kategori</td>
	                      	<td width="10%" style="text-align:center">Kode Item</td>
	                        <td width="50%" style="text-align:center">Deskripsi</td>
	                        <td width="10%" style="text-align:center" nowrap>Jumlah</td>
	                        <td width="10%" style="text-align:center" nowrap>Total</td>
	                    </tr>
	                    <?php									
							$no		= 0;
							$totAm	= 0;
							$sqlJD	= "SELECT
											A.ITM_GROUP,
											A.ITM_CODE,
											A.DOC_STAT,
											A.DOC_CATEG,
											A.DOC_CODE,
											A.DOC_DATE,
											A.DOC_VAL,
											A.DOC_CVAL,
											A.DOC_DESC
										FROM
											tbl_item_logbook_$PRJCODEVW A
                                            WHERE
												A.JOBCODEID IN ('$JOBCODEID') AND A.DOC_STAT NOT IN (5,9)
												AND A.DOC_CATEG IN ('PO', 'WO', 'VCASH', 'CPRJ', 'PPD') 	-- view by request value
                                            ORDER BY A.DOC_DATE, A.DOC_CODE";


							$resJD	= $this->db->query($sqlJD)->result();
							foreach($resJD as $rowJD) :
								$no				= $no+1;
								$DOC_CODE 		= $rowJD->DOC_CODE;
								$DOC_STAT 		= $rowJD->DOC_STAT;
								$DOC_DATE 		= $rowJD->DOC_DATE;
								$DOC_CATEG 		= $rowJD->DOC_CATEG;
								$ITM_GROUP 		= $rowJD->ITM_GROUP;
								$ITM_CODE 		= $rowJD->ITM_CODE;
								$DOC_VAL 		= $rowJD->DOC_VAL;
								$DOC_CVAL 		= $rowJD->DOC_CVAL;
								$NET_VAL 		= $DOC_VAL-$DOC_CVAL;
								$totAm			= $totAm + $NET_VAL;
								$JournalDesc 	= $rowJD->DOC_DESC;							
								if($JournalDesc == '')
								{
									if($DOC_CATEG == 'UM')
										$JournalDesc	= "Penggunaan material";
									elseif($DOC_CATEG == 'OPN')
										$JournalDesc	= "Opname";
								}

								$BGCOL 			= "background-color: yellow;";
								if($DOC_STAT == 3 || $DOC_STAT == 6)
									$BGCOL 		= "";

								if($DOC_CATEG == 'CHO-PD')
									$DOC_CATEGD = "PENY. PD";
								elseif($DOC_CATEG == 'CPRJ')
									$DOC_CATEGD = "VLK";
								elseif($DOC_CATEG == 'OPN')
									$DOC_CATEGD = "OPNAME";
								elseif($DOC_CATEG == 'VCASH')
									$DOC_CATEGD = "VOC. CASH";
								elseif($DOC_CATEG == 'UM')
									$DOC_CATEGD = "PENGG. MATERIAL";
								else
									$DOC_CATEGD = "LAIN-LAIN";
								?>
	                                <tr>
	                                    <td style="text-align:center"><?php echo $no; ?></td>
	                                    <td style="text-align:center" nowrap><?php echo $DOC_CODE; ?></td>
	                                    <td style="text-align:center" nowrap><?php echo $DOC_DATE; ?></td>
	                                    <td style="text-align:center"><?php echo $DOC_CATEG; ?></td>
	                                    <td style="text-align:center"><?php echo $ITM_CODE; ?></td>
	                                    <td style="text-align:left"><?php echo $JournalDesc; ?></td>
	                                    <td style="text-align:right; <?=$BGCOL?>" nowrap><?php echo number_format($NET_VAL,2); ?></td>
	                                    <td style="text-align:right" nowrap><?php echo number_format($totAm,2); ?></td>
	                                </tr>
	                    		<?php
							endforeach;
						?>
	                </table>
	           	  </td>
	            </tr>
	        </table>

	        <br>
	        <div class="row no-print">
	            <div class="col-xs-12">
	                <div id="Layer1">
	                    <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
	                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px; display: none;">
	                    <i class="fa fa-download"></i> Generate PDF
	                    </button>
	                </div>
	            </div>
	        </div>
		</section>
	</div>
</body>
</html>