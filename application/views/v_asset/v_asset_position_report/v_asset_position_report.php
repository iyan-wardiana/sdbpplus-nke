<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 April 2017
 * File Name	= r_usagereq_report_adm.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 20 September 2017
 * File Name	= v_asset_position_report_adm.php
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
if($viewProj == 0) // SELECTED PROJECT
{
	if($TOTPROJ == 1)
	{
		$sql 		= "SELECT A.PRJCODE, A.PRJNAME FROM tbl_project A 
						WHERE A.PRJCODE IN ($PRJCODECOL)
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
						WHERE A.PRJCODE IN ($PRJCODECOL)
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

	<?php
	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'AssetPositionReport')$AssetPositionReport = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'ProjectCode')$ProjectCode = $LangTransl;
			if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'AssetName')$AssetName = $LangTransl;
			if($TranslCode == 'HourMeter')$HourMeter = $LangTransl;
			if($TranslCode == 'LastPosition')$LastPosition = $LangTransl;
			if($TranslCode == 'LastJob')$LastJob = $LangTransl;
			if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
	
		endforeach;
	
	?>

<body>
<section class="content">
    <table width="100%" border="0" style="size:auto">
    <tr>
        <td width="19%">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
        <td class="style2">&nbsp;</td>
        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" width="100" height="50"></td>
        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px"><?php echo $AssetPositionReport ?></td>
  </tr>
    <tr>
        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px"><?php $comp_name 	= $this->session->userdata['comp_name']; echo $comp_name; ?></span></td>
    </tr>
        <?php
            //n$StartDate1 = date('Y/m/d',strtotime($Start_Date));
            //n$EndDate1 = date('Y/m/d',strtotime($End_Date));
			//n$DOCTYPE1 = $TYPE;
			/*if($TYPE==1)
			{
				$DOCTYPE1 	= "Asli";
				$TYPE1		= "HRDOCJNS = 1";
			}
			elseif($TYPE==2)
			{
				$DOCTYPE1 	= "Copy";
				$TYPE1		= "HRDOCJNS = 2";
			}
			else
			{
				$DOCTYPE1 	= "All";	
				$TYPE1		= "HRDOCJNS IN (1,2)";		
			}*/
			
			if($GROUP == 'All')
			{
				$GROUP1	= "AG_CODE like '%AG%'";
				$AG_CODE1 = "All";
				$AG_NAME1 = "All";
			}
			else
			{
				$GROUP1	= "AG_CODE = '$GROUP'";
				
				$sqlG 		= "SELECT AG_CODE,AG_NAME FROM tbl_asset_group WHERE AG_CODE='$GROUP'";
				$resultG 	= $this->db->query($sqlG)->result();
				
				foreach($resultG as $rowG) :
					$AG_CODE1 	= $rowG->AG_CODE;
					$AG_NAME1	= $rowG->AG_NAME;
				endforeach;	
				
			}
		
			     
		
                            
        ?>
        
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="style2" style="text-align:left; font-style:italic">
            <table width="100%">
            	<!--<tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top">Type Dokumen</td>
        			<td width="1%">:</td>
        			<td width="91%"><?php //echo $DOCTYPE1; ?> </td>
    			</tr>-->
                <tr style="text-align:left; font-style:italic">
        			<td width="8%" nowrap valign="top"><?php echo $Group ?></td>
        			<td width="1%">:</td>
        			<td width="91%"><?php echo " <b> $AG_NAME1 </b>"; ?> </td>
    			</tr>
                <tr style="text-align:left; font-style:italic">
                    <td nowrap valign="top"><?php echo $ProjectCode ?> </td>
                    <td>:</td>
                    <td><?php echo "<b> $PRJCODECOLLD </b>"; ?> </span></td>
                </tr>
               <tr style="text-align:left; font-style:italic">
                  <td nowrap valign="top"><?php echo $ProjectName ?> </td>
                  <td>:</td>
                  <td><span class="style2" style="text-align:left; font-style:italic"><?php echo "<b> $PRJNAMECOLLD <b>";?></span></td>
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
                  <td width="7%" height="28" nowrap style="text-align:center; font-weight:bold">No.</td>
                  <td width="11%" nowrap style="text-align:center; font-weight:bold"><?php echo $Code ?></td>
                  <!--<td width="15%" nowrap style="text-align:center; font-weight:bold">Gruop</td>-->
                  <td width="19%" nowrap style="text-align:center; font-weight:bold"><?php echo $AssetName ?></td>
                  <td width="10%" nowrap style="text-align:center; font-weight:bold"><?php echo $HourMeter ?></td>
                  <td width="22%" nowrap style="text-align:center; font-weight:bold"><?php echo $LastPosition ?></td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold"><?php echo $LastJob ?></td>
                  <td width="14%" nowrap style="text-align:center; font-weight:bold"><?php echo $JobDescription ?></td>
                  <td width="9%" nowrap style="text-align:center; font-weight:bold"><?php echo $Status ?></td> <!--<td width="7%" nowrap style="text-align:center; font-weight:bold">TGL TERBIT</td>
                  <td width="5%" nowrap style="text-align:center; font-weight:bold">BERLAKU<br>S/D</td>
                  <td width="8%" nowrap style="text-align:center; font-weight:bold">KETERANGAN</td>-->
              </tr>
            
                <?php						
					$therow			= 0;
					/*if ($TYPE==1)
						$TYPE1= "HRDOCJNS = 1";
					elseif ($TYPE==2)
						$TYPE1= "HRDOCJNS = 2";
					else
						$TYPE1= "HRDOCJNS IN (1,2)";*/

					/*if($SUB_BIDANG=="D02430")
						$SUB_BIDANG1 = "DOCCODE='D02430'";
					elseif($SUB_BIDANG=="D02431")
						$SUB_BIDANG1 = "DOCCODE='D02431'";
					elseif($SUB_BIDANG=="D02432")
						$SUB_BIDANG1 = "DOCCODE='D02432'";
					elseif($SUB_BIDANG=="D02433")
						$SUB_BIDANG1 = "DOCCODE='D02433'";
					elseif($SUB_BIDANG=="D02434")
						$SUB_BIDANG1 = "DOCCODE='D02434'";
					elseif($SUB_BIDANG=="D02435")
						$SUB_BIDANG1 = "DOCCODE='D02435'";
					elseif($SUB_BIDANG=="D02436")
						$SUB_BIDANG1 = "DOCCODE='D02436'";
					else
						$SUB_BIDANG1 = "DOCCODE IN ('D02430','D02431','D02432','D02433','D02434','D02435','D02436')";			
      				
					
					if($SUB_BIDANG=="D02430")
						$SUB_BIDANG2 = "doc_code='D02430'";
					elseif($SUB_BIDANG=="D02431")
						$SUB_BIDANG2 = "doc_code='D02431'";
					elseif($SUB_BIDANG=="D02432")
						$SUB_BIDANG2 = "doc_code='D02432'";
					elseif($SUB_BIDANG=="D02433")
						$SUB_BIDANG2 = "doc_code='D02433'";
					elseif($SUB_BIDANG=="D02434")
						$SUB_BIDANG2 = "doc_code='D02434'";
					elseif($SUB_BIDANG=="D02435")
						$SUB_BIDANG2 = "doc_code='D02435'";
					elseif($SUB_BIDANG=="D02436")
						$SUB_BIDANG2 = "doc_code='D02436'";
					else
						$SUB_BIDANG2 = "doc_code IN ('D02430','D02431','D02432','D02433','D02434','D02435','D02436')";*/
						
                    if($viewProj == 0)	// PER PROJECT
                    {
						/*$sqlq1 		= "SELECT * FROM tbl_hrdoc_header
											WHERE PRJCODE IN ($PRJCODECOL) AND DOCCODE='D02410' AND $TYPE1 ORDER BY HRDOCNO";*/
						$sqlq1 			= "SELECT * FROM tbl_asset_list
											WHERE AS_LASTPOS IN ($PRJCODECOL) AND $GROUP1
											ORDER BY AS_NAME";
													
                        $resq1 			= $this->db->query($sqlq1)->result();
                    }
                    else
                    {
						//$sqlq1 			= "SELECT * FROM tbl_hrdoc_header WHERE DOCCODE='D02410' AND  $TYPE1 ORDER BY HRDOCNO";
						$sqlq1 			= "SELECT * FROM tbl_asset_list WHERE $GROUP1
											ORDER BY AS_NAME";
                        $resq1 			= $this->db->query($sqlq1)->result();
                    }
                    
                    foreach($resq1 as $rowsqlq1) :
                        $therow			= $therow + 1;
                        /*$PRJCODE 		= $rowsqlq1->PRJCODE;
                        $HRD_EMPID	 	= $rowsqlq1->HRD_EMPID;
							if($HRD_EMPID == '')
							{
								$HRD_EMPIDV	= "<em>NIK not found</em>";
								$HRD_EMPNMV	= "-";
							}
							else
							{
								$sqlEMPID 	= "SELECT Fist_Name, Last_Name tbl_employee WHERE Emp_ID = '$HRD_EMPID'";
								$resq3a 	= $this->db->query($sqlEMPID)->result();
								foreach($resq3a as $rowq3a) :
									$Fist_Name	= $rowq3a->Fist_Name;
									$Last_Name	= $rowq3a->Last_Name;									
								endforeach;
								$HRD_EMPIDV	= $HRD_EMPID;
								$HRD_EMPNMV	= $Fist_Name.$Last_Name;
							}*/
						
                        /*$HRDOCNO	 	= $rowsqlq1->HRDOCNO;
                        $DOCCODE 		= $rowsqlq1->DOCCODE;
							//DATA DARI TBL_DOCUMENT
							//$sqlq3a 	= "SELECT doc_name, BDG_Code FROM tbl_document WHERE $SUB_BIDANG2";
							$sqlq3a 	= "SELECT doc_name, BDG_Code FROM tbl_document WHERE doc_code = '$DOCCODE'";
							$resq3a 	= $this->db->query($sqlq3a)->result();
							foreach($resq3a as $rowq3a) :
								$doc_name	= $rowq3a->doc_name;
								$BDG_Code	= $rowq3a->BDG_Code;									
							endforeach;*/
							
						// PROJECT IS NOT NEEDED
							/*$PRJNAME1	= "";
							$sqlq2a 	= "SELECT PRJCODE,PRJNAME FROM tbl_project WHERE PRJCODE ='$PRJCODE'";
							$resq2a 	= $this->db->query($sqlq2a)->result();
							foreach($resq2a as $rowq2a) :
								$PRJNAME1	= $rowq2a->PRJNAME;									
							endforeach;*/
						
                        /*$HRDOCJNS		= $rowsqlq1->HRDOCJNS;
							if ($HRDOCJNS==1)
								$HRDOCJNS1="Asli";
							elseif($HRDOCJNS==2)
								$HRDOCJNS1="Copy";
							else
								$HRDOCJNS1="<em>No data</em>";
						
						$HRD_CUALIF		= $rowsqlq1->HRD_CUALIF;
						$HRD_PUBLISHER	= $rowsqlq1->HRD_PUBLISHER;								
                        $START_DATE		= $rowsqlq1->START_DATE;
                        $END_DATE		= $rowsqlq1->END_DATE;
                        $HRDOC_NOTE		= $rowsqlq1->HRDOC_NOTE;*/
						
						
						$AS_CODE		= $rowsqlq1->AS_CODE;
						$AS_NAME		= $rowsqlq1->AS_NAME;
						$AS_HM			= $rowsqlq1->AS_HM;
						$AS_LASTPOS		= $rowsqlq1->AS_LASTPOS;
						$AS_LASTJOB		= $rowsqlq1->AS_LASTJOB;
						$AS_LASTSTAT	= $rowsqlq1->AS_LASTSTAT;
						$AS_STAT		= $rowsqlq1->AS_STAT;
						
                        ?>
                            <tr>
                                <td nowrap style="text-align:center;"><?php echo "$therow"; ?>.</td>
                                <td nowrap style="text-align:center;"><?php echo $AS_CODE; ?></td>
                              	<!--<td nowrap style="text-align:center;"><?php //echo $HRDOCNO; ?></td>-->
                                <td nowrap style="text-align:left;"><?php echo $AS_NAME; ?></td>
                                <td nowrap style="text-align:right;"><?php echo number_format($AS_HM, $decFormat); ?></td>
                                <td nowrap style="text-align:left;">
								<?php
									$PRJNAME1	= '';
									$sqlP 	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$AS_LASTPOS'";
									$resP 	= $this->db->query($sqlP)->result();
									foreach($resP as $rowP) :
										$PRJNAME1	= $rowP->PRJNAME;									
									endforeach;
												
                               		echo "$AS_LASTPOS - $PRJNAME1";
                                ?>
                                </td>
                                <td nowrap style="text-align:center;">
								<?php 
                               		echo $AS_LASTJOB;
                                ?>
                                </td>
                                <td nowrap style="text-align:center;">
										<?php 
											$JL_DESC="-";
											if($AS_LASTJOB == "MNT")
											{
												$JL_DESC="Maintenance";	
											}
											elseif($AS_LASTJOB == "")
											{
												$JL_DESC="-";	
											}
											else
											{
												$JL_DESC = '-';
												$sqlJ 	= "SELECT JL_DESC FROM tbl_asset_joblist WHERE JL_CODE = '$AS_LASTJOB'";
												$resJ 	= $this->db->query($sqlJ)->result();
												foreach($resJ as $rowJ) :
													$JL_DESC	= $rowJ->JL_DESC;									
												endforeach;
												//$JL_DESC="-";	
											}
											echo "$JL_DESC";
										?></td>
                                <td nowrap style="text-align:center;">
									<?php
										/*if ($AS_LASTSTAT==0)
										{
											echo "Ready";
										}
										elseif ($AS_LASTSTAT==1)
										{
											echo "New";
										}
										elseif ($AS_LASTSTAT==2)
										{
											echo "Confirm";
										}
										elseif ($AS_LASTSTAT==3)
										{
											echo "Approve";
										}
										else
										{
											echo "Not Found";
										}*/
										
										if($AS_STAT==1)
										{
											echo "Active";
										}
										elseif($AS_STAT==2)
										{
											echo "In Active";
										}
										elseif($AS_STAT==3)
										{
											echo "Used";
										}
										elseif($AS_STAT==3)
										{
											echo "Reapir";
										}
									?>
                                </td>
                                <!--<td nowrap style="text-align:center;"><?php //echo $START_DATE; ?></td>
                                <td nowrap style="text-align:center;"><?php //echo $END_DATE; ?></td>
                                <td nowrap style="text-align:left;"><?php //echo $HRDOC_NOTE; ?></td>-->
                            </tr>
                        <?php
                    endforeach;
                ?>
            </table>
      </td>
    </tr>
</table>
</section>
</body>
</html>