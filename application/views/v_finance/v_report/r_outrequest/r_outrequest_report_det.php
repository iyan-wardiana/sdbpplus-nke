<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Januari 2019
 * File Name	= r_outrequest_report_det.php
 * Location		= -
*/
$IDREP	= date('YmdHis');
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LapCashOut_$IDREP.xls");
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

$PRJCOST	= 0;
$PRJDATE	= date('Y/m/d');
$PRJDATE_CO	= date('Y/m/d');
$sqlPRJ		= "SELECT PRJDATE, PRJDATE_CO, PRJCOST FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resPRJ		= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ):
	$PRJCOST	= $rowPRJ->PRJCOST;
	$PRJDATE	= date('Y/m/d', strtotime($rowPRJ->PRJDATE));
	$PRJDATE_CO	= date('Y/m/d', strtotime($rowPRJ->PRJDATE_CO));
endforeach;

if($CFStat == 0)
{
	$CFStatD 	= 'All';
}
elseif($CFStat == 1)
{
	$CFStatD 	= 'New';
}
elseif($CFStat == 2)
{
	$CFStatD 	= 'Confirm';
}
elseif($CFStat == 3)
{
	$CFStatD 	= 'Approved';
}
elseif($CFStat == 4)
{
	$CFStatD 	= 'Revise';
}
elseif($CFStat == 5)
{
	$CFStatD 	= 'Rejected';
}
elseif($CFStat == 6)
{
	$CFStatD 	= 'Closed';
}
elseif($CFStat == 7)
{
	$CFStatD 	= 'Waiting';
}
elseif($CFStat == 9)
{
	$CFStatD 	= 'Void';
}
else
{
	$CFStatD 	= 'fake';
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/daterangepicker/daterangepicker.css'; ?>">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datepicker/datepicker3.css'; ?>">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/iCheck/all.css'; ?>">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/colorpicker/bootstrap-colorpicker.min.css'; ?>">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/timepicker/bootstrap-timepicker.min.css'; ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/select2/select2.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="16%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="45%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="3" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:20px"><?php echo $h2_title; ?>
        </td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:16px"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px">&nbsp;Periode : 
        <?php 
				/*$CRDate	= date_create($End_Date);
				echo date_format($CRDate,"d-m-Y");*/
				$StartDate	= date('d M Y', strtotime($Start_Date));
				$EndDate	= date('d M Y', strtotime($End_Date));
				echo "$StartDate s.d. $EndDate";
			?></span></td>
    </tr>
    <tr>
        <td colspan="2" class="style2" style="text-align:center; font-style:italic"><span class="style2" style="text-align:center; font-weight:bold; font-size:16px">Detail - <?php echo $CFStatD; ?></span></td>
      </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"><?php// echo $DOCTYPE1; ?></td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
                    <td width="8%" nowrap valign="top">KODE PROYEK</td>
                    <td width="1%">:</td>
                    <td width="91%"><?php echo "$PRJCODE"; ?></span></td>
                </tr>
                <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top">NAMA PROYEK</td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo $PRJNAME;?></span></td>
              </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" class="style2">
            <table width="100%" border="1" rules="all">
                <tr style="background:#CCCCCC">
                  <th width="3%" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO</th>
                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Nomor</th>
                  <th width="7%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Tanggal</th>
                  <th width="23%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Deskripsi</th>
                  <th width="22%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Nama Item</th>
                  <th width="9%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Dibuat Oleh</th>
                  <th width="7%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Qty</th>
                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000; border-top-width:2px; border-top-color:#000;">Nilai</th>
                  <th width="11%" nowrap style="text-align:center;border-top-width:2px; border-bottom-width:2px; border-bottom-color:#000; border-top-color:#000;border-right-width:2px; border-right-color:#000">Total</th>
              </tr>
              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td colspan="2" nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
               </tr>
               <?php
			   		$theRow		= 0;
					if($CFStat == 0)
					{
						$sqlAMC		= "tbl_fpa_detail A
											INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
										WHERE B.PRJCODE = '$PRJCODE'
											AND A.FPA_DATE BETWEEN '$Start_Date' AND '$End_Date'";
					}
					else
					{
						$sqlAMC		= "tbl_fpa_detail A
											INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
										WHERE B.PRJCODE = '$PRJCODE' AND B.FPA_STAT = $CFStat
											AND A.FPA_DATE BETWEEN '$Start_Date' AND '$End_Date'";
					}
					$resAMC		= $this->db->count_all($sqlAMC);
					if($resAMC > 0)
					{
						if($CFStat == 0)
						{
							$sqlAM		= "SELECT A.PRJCODE, A.FPA_CODE, A.FPA_DATE, B.FPA_NOTE, A.ITM_CODE, A.FPA_VOLM, A.ITM_PRICE, 
												A.FPA_TOTAL, B.FPA_CREATER
											FROM tbl_fpa_detail A
												INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
											WHERE B.PRJCODE = '$PRJCODE'
												AND A.FPA_DATE BETWEEN '$Start_Date' AND '$End_Date'";
						}
						else
						{
							$sqlAM		= "SELECT A.PRJCODE, A.FPA_CODE, A.FPA_DATE, B.FPA_NOTE, A.ITM_CODE, A.FPA_VOLM, A.ITM_PRICE, 
												A.FPA_TOTAL, B.FPA_CREATER
											FROM tbl_fpa_detail A
												INNER JOIN tbl_fpa_header B ON A.FPA_NUM = B.FPA_NUM
											WHERE B.PRJCODE = '$PRJCODE' AND B.FPA_STAT = $CFStat
												AND A.FPA_DATE BETWEEN '$Start_Date' AND '$End_Date'";
						}
						$resAM		= $this->db->query($sqlAM)->result();
						foreach($resAM as $rowAM):
							$theRow		= $theRow + 1;
							$PRJCODE	= $rowAM->PRJCODE;
							$FPA_CODE	= $rowAM->FPA_CODE;
							$FPA_CODE	= $rowAM->FPA_CODE;
							$FPA_DATE	= $rowAM->FPA_DATE;
							$FPA_NOTE	= $rowAM->FPA_NOTE;
							$ITM_CODE	= $rowAM->ITM_CODE;
							$ITM_VOLM	= number_format($rowAM->FPA_VOLM, 2);
							$ITM_PRICE	= $rowAM->ITM_PRICE;
							$FPA_TOTAL	= $rowAM->FPA_TOTAL;
							$CREATER	= $rowAM->FPA_CREATER;
							
							// GET ITEM NAME
								$ITM_NAME	= '';
								$sqlITM		= "SELECT ITM_NAME FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' LIMIT 1";
								$sqlITM		= $this->db->query($sqlITM)->result();
								foreach($sqlITM as $rowITM):
									$ITM_NAME	= $rowITM->ITM_NAME;
								endforeach;
								
							// GET EMP
								$First_Name = '';
								$Last_Name = '';
								$sqlEMP		= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$CREATER' LIMIT 1";
								$sqlEMP		= $this->db->query($sqlEMP)->result();
								foreach($sqlEMP as $rowEMP):
									$First_Name	= $rowEMP->First_Name;
									$Last_Name	= $rowEMP->Last_Name;
								endforeach;
								if($theRow == $resAMC)
								{
								?>
                                    <tr>
                                        <td nowrap style="text-align:center; border-bottom-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?></td>
                                        <td nowrap style="text-align:left; border-bottom-width:2px; border-top-color:#000;"><?php echo $FPA_CODE; ?></td>
                                        <td nowrap style="text-align:center; border-bottom-width:2px; border-top-color:#000;"><?php echo $FPA_DATE; ?></td>
                                        <td style="text-align:left; border-bottom-width:2px; border-top-color:#000;"><?php echo $FPA_NOTE; ?></td>
                                        <td nowrap style="text-align:left; border-bottom-width:2px; border-top-color:#000;"><?php echo $ITM_NAME; ?></td>
                                        <td nowrap style="text-align:left; border-bottom-width:2px; border-top-color:#000;"><?php echo $First_Name. ' ' .$Last_Name; ?></td>
                                        <td nowrap style="text-align:right; border-bottom-width:2px; border-top-color:#000;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                        <td nowrap style="text-align:right; border-bottom-width:2px; border-top-color:#000;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                      <td nowrap style="text-align:right; border-bottom-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000"><?php echo number_format($FPA_TOTAL, 2); ?></td>
                                    </tr>
								<?php
								}
								else
								{
								?>
                                    <tr>
                                        <td nowrap style="text-align:center; border-top-color:#000;border-left-width:2px; border-left-color:#000"><?php echo $theRow; ?></td>
                                        <td nowrap style="text-align:left; border-top-color:#000;"><?php echo $FPA_CODE; ?></td>
                                        <td nowrap style="text-align:center; border-top-color:#000;"><?php echo $FPA_DATE; ?></td>
                                        <td style="text-align:left; border-top-color:#000;"><?php echo $FPA_NOTE; ?></td>
                                        <td nowrap style="text-align:left; border-top-color:#000;"><?php echo $ITM_NAME; ?></td>
                                        <td nowrap style="text-align:left; border-top-color:#000;"><?php echo $First_Name. ' ' .$Last_Name; ?></td>
                                        <td nowrap style="text-align:right; border-top-color:#000;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                        <td nowrap style="text-align:right; border-top-color:#000;"><?php echo number_format($ITM_PRICE, 2); ?></td>
                                      <td nowrap style="text-align:right; border-top-color:#000;border-right-width:2px; border-right-color:#000"><?php echo number_format($FPA_TOTAL, 2); ?></td>
                                    </tr>
								<?php
								}
						endforeach;
					}
					else
					{
						?>
                            <tr>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td colspan="2" nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                                <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
                            </tr>
						<?php
					}
					?>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>