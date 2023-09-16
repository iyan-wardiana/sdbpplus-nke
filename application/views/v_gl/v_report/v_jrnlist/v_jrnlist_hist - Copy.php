<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 22 Junli 2018
	* File Name	= profit_loss_view.php
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
	    
$sqlApp         = "SELECT * FROM tappname";
$resultaApp     = $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
    $appName    = $therow->app_name;
    $comp_init  = strtolower($therow->comp_init);
    $comp_name  = $therow->comp_name;
endforeach;

$sqlPRJ			= "SELECT PRJCODE, PRJNAME, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE 	= $rowPRJ->PRJCODE;
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJCOST 	= $rowPRJ->PRJCOST;
endforeach;
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
	        padding: 0.1cm;
	        margin: 0.1cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .subpage {
	        padding: 0.5cm;
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
	                <td class="style2" style="text-align:center; font-weight:bold;">
	                	<span class="style2" style="font-weight:bold; font-size:12px">DETIL DOKUMEN JURNAL</span>
	                </td>
	            </tr>
	            <tr>
	                <td class="style2" style="text-align:left;">&nbsp;</td>
	            </tr>
	            <?php
	            	$DOC_01 		= "";
	            	$DOC_NO 		= "";
	            	$SHOW_SPP 		= 0;
	            	$SHOW_OP 		= 0;
	            	$SHOW_LPM 		= 0;
	            	$SHOW_UM 		= 0;
	            	$SHOW_SPK 		= 0;
	            	$SHOW_OPN 		= 0;
	            	$SHOW_TTK 		= 0;
	            	$SHOW_MC 		= 0;
	            	$SHOW_PINV 		= 0;
	            	$SHOW_INV 		= 0;
	            	$SHOW_BP 		= 0;
	            	$SHOW_BR 		= 0;
	            	$SHOW_VCASH		= 0;
	            	$SHOW_VLK		= 0;
	            	$SHOW_PD		= 0;
	            	if($JRN_TYPE == 'VCASH')
	            	{
		            	$SHOW_BP 	= 1;
		            	$SHOW_VCASH	= 1;

	            		$JRN_TYPED 	= "Voucher Cash";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader_vcash WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
	            	}
					elseif($JRN_TYPE == 'BP')
					{
		            	$SHOW_SPP 	= 1;
		            	$SHOW_OP 	= 1;
		            	$SHOW_LPM 	= 1;
		            	$SHOW_SPK 	= 1;
		            	$SHOW_OPN 	= 1;
		            	$SHOW_TTK 	= 1;
		            	$SHOW_INV 	= 1;
		            	$SHOW_BP 	= 1;
		            	$SHOW_VCASH	= 1;
		            	$SHOW_PD	= 1;

	            		$JRN_TYPED 	= "Pembayaran";
	            		$s_01 		= "SELECT B.CB_DATE AS JRN_DATE, A.CBD_DOCNO, A.CBD_DOCCODE FROM tbl_bp_detail A
	            							INNER JOIN tbl_bp_header B ON A.CB_NUM = B.CB_NUM AND A.PRJCODE = B.PRJCODE
	            						WHERE A.CB_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            			$CBD_DOCNO 	= $rw_01->CBD_DOCNO;
	            			
		            		$s_02 		= "tbl_pinv_header WHERE INV_NUM = '$CBD_DOCNO'";
		            		$r_02 		= $this->db->count_all($s_02);
		            		if($r_02 > 0)
		            		{
		            			$DOC_01 	= "Voucher LPM/Opname";

			            		$s_03 		= "SELECT TTK_NUM, TTK_CODE, TTK_DATE FROM tbl_pinv_detail WHERE INV_NUM = '$CBD_DOCNO'";
			            		$r_03 		= $this->db->query($s_03)->result();
			            		foreach($r_03 as $rw_03):
			            			$JRN_DATE 	= $rw_03->JRN_DATE;
			            			$CBD_DOCNO 	= $rw_03->CBD_DOCNO;
			            		endforeach;
		            		}
	            		endforeach;
					}
					elseif($JRN_TYPE == 'PINBUK')
					{
	            		$JRN_TYPED 	= "Pindah Buku";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader_pb WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'CPRJ')
					{
		            	$SHOW_VCASH	= 1;

	            		$JRN_TYPED 	= "Voucher Luar Kota";
	            		$JRN_TYPED 	= "Pindah Buku";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader_cprj WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'CHO-PD')
					{
		            	$SHOW_VLK	= 1;

	            		$JRN_TYPED 	= "Pianjaman Dinas (PD)";
	            		$JRN_TYPED 	= "Pindah Buku";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader_pd WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'BR')
					{
		            	$SHOW_PINV 	= 1;
		            	$SHOW_BR 	= 1;

	            		$JRN_TYPED 	= "Penerimaan Kas / Bank";
	            		$s_01 		= "SELECT BR_DATE AS JRN_DATE FROM tbl_br_header WHERE BR_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'JRNREV')
					{
	            		$JRN_TYPED 	= "Revisi Jurnal";
					}
					elseif($JRN_TYPE == 'OPN')
					{
		            	$SHOW_SPK 	= 1;
		            	$SHOW_OPN 	= 1;
		            	$SHOW_TTK 	= 1;
		            	$SHOW_INV 	= 1;
		            	$SHOW_BP 	= 1;

	            		$JRN_TYPED 	= "Opname";
	            		$s_01 		= "SELECT OPNH_DATE AS JRN_DATE FROM tbl_opn_header WHERE OPNH_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'IR')
					{
		            	$SHOW_SPP 		= 1;
		            	$SHOW_OP 		= 1;
		            	$SHOW_LPM 		= 1;
		            	$SHOW_TTK 		= 1;
		            	$SHOW_INV 		= 1;
		            	$SHOW_BP 		= 1;

	            		$JRN_TYPED 	= "Penerimaan Material (LPM)";
	            		$s_01 		= "SELECT IR_CODE, IR_DATE AS JRN_DATE FROM tbl_ir_header WHERE IR_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$DOC_NO 	= $rw_01->IR_CODE;
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'PINV')
					{
		            	$SHOW_MC 	= 1;
		            	$SHOW_PINV 	= 1;
		            	$SHOW_BR 	= 1;

	            		$JRN_TYPED 	= "Voucher LPM/Opname";
	            		$s_01 		= "SELECT INV_DATE AS JRN_DATE FROM tbl_pinv_header WHERE INV_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'GEJ')
					{
	            		$JRN_TYPED 	= "Jurnal Umum / Memorial";
	            		$s_01 		= "SELECT JournalH_Date AS JRN_DATE FROM tbl_journalheader WHERE JournalH_Code = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'PRJINV')
					{
		            	$SHOW_MC 	= 1;
		            	$SHOW_PINV 	= 1;
		            	$SHOW_BR 	= 1;

	            		$JRN_TYPED 	= "Faktur Proyek";
	            		$s_01 		= "SELECT PINV_DATE AS JRN_DATE FROM tbl_projinv_header WHERE PINV_CODE = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
					elseif($JRN_TYPE == 'UM')
					{
		            	$SHOW_SPP 	= 1;
		            	$SHOW_OP 	= 1;
		            	$SHOW_LPM 	= 1;
		            	$SHOW_UM 	= 1;

	            		$JRN_TYPED 	= "Penggunaan Material";
	            		$s_01 		= "SELECT UM_DATE AS JRN_DATE FROM tbl_um_header WHERE UM_NUM = '$JRN_NUM'";
	            		$r_01 		= $this->db->query($s_01)->result();
	            		foreach($r_01 as $rw_01):
	            			$JRN_DATE 	= $rw_01->JRN_DATE;
	            		endforeach;
					}
	            ?>
	            <tr>
	                <td class="style2" style="text-align:left;">
	                	<table width="100%" style="size:auto; font-size:12px;" cellspacing="-1" cellpadding="0">
	                        <tr style="text-align:left;">
	                         	<td width="14%" nowrap>Jenis Dokuemn</td>
	                          	<td width="1%">:</td>
	                          	<td width="85%" style="font-weight: bold; font-size: 13px;" nowrap>
	                          		<?php echo $JRN_TYPED; ?>
	                          	</td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>No. Dokumen</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px;" nowrap>
	                                <?php echo $DOC_NO; ?>
	                            </td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>No. Journal</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px;" nowrap>
	                                <?php echo $JRN_CODE; ?>
	                            </td>
	                      	</tr>
	                        <tr style="text-align:left;">
	                         	<td nowrap>Tgl. Dokumen</td>
	                          	<td>:</td>
	                          	<td style="font-weight: bold; font-size: 13px;" nowrap>
	                                <?php echo $JRN_DATE; ?>
	                            </td>
	                      	</tr>
	                    </table>
	                </td>
	            </tr>
	        </table>
	        <br>
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
					/*if($CATEGREP == 'GE')
						$CATEGREP = "GE','O";
					if($CATEGREP == 'SC')
						$CATEGREP = "SC','S";
						
					$no		= 0;
					$totAm	= 0;
					$sqlJD	= "SELECT
									A.ITM_CODE,
									A.JournalH_Code,
									B.JournalType,
									B.Manual_No,
									A.JournalH_Date,
									A.Base_Debet,
									A.Base_Kredit,
									B.JournalH_Desc,
									A.Other_Desc
								FROM
									tbl_journaldetail A
                                    INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
                                        AND B.JournalType != 'IR' 
                                    WHERE
										A.ITM_GROUP IN ('$CATEGREP')
										AND B.GEJ_STAT = 3
                                        AND A.Base_Debet > 0
                                        AND A.proj_Code = '$PRJCODE'
										AND A.JournalH_Date <= '$END_DATE'
                                        AND (A.ITM_CODE != '' AND ! ISNULL(A.ITM_CODE))
                                    ORDER BY A.JournalH_Date, A.JournalH_Code";
					$resJD	= $this->db->query($sqlJD)->result();
					foreach($resJD as $rowJD) :
						$no				= $no+1;
						$JournalH_Code 	= $rowJD->JournalH_Code;
						$Manual_No 		= $rowJD->Manual_No;
						if($Manual_No == '')
							$Manual_No	= $JournalH_Code;
						$JournalH_Date 	= $rowJD->JournalH_Date;
						$JournalType 	= $rowJD->JournalType;
						$Base_Debet 	= $rowJD->Base_Debet;							
						$totAm			= $totAm + $Base_Debet;
						$JournalDesc 	= $rowJD->Other_Desc;							
						if($JournalDesc == '')
						{
							if($JournalType == 'UM')
								$JournalDesc	= "Penggunaan material";
							elseif($JournalType == 'OPN')
								$JournalDesc	= "Opname";
						}
						?>
                            <tr>
                                <td style="text-align:center"><?php echo $no; ?></td>
                                <td style="text-align:center" nowrap><?php echo $Manual_No; ?></td>
                                <td style="text-align:center" nowrap><?php echo $JournalH_Date; ?></td>
                                <td style="text-align:left"><?php echo $JournalDesc; ?></td>
                                <td style="text-align:right"><?php echo number_format($Base_Debet,2); ?></td>
                                <td style="text-align:right"><?php echo number_format($totAm,2); ?></td>
                            </tr>
                		<?php
					endforeach;*/
				?>
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