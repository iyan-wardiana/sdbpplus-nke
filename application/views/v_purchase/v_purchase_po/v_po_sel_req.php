<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= v_po_sel_req.php
 * Location		= -
*/
$this->load->view('template/head');
$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
if($decFormat == 0)
	$decFormat		= 2;

$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;

function cut_text($var, $len = 200, $txt_titik = "...") 
{
	$var1	= explode("</p>",$var);
	$var	= $var1[0];
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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
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

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">   
    </head>

	<?php
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'DateRequest')$DateRequest = $LangTransl;
			if($TranslCode == 'StartDate')$StartDate = $LangTransl;
			if($TranslCode == 'EndDate')$EndDate = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ReceivePlan')$ReceivePlan = $LangTransl;
			if($TranslCode == 'Deviation')$Deviation = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
		endforeach;
	?>

	<body class="<?php echo $appBody; ?>">
		<section class="content-header">
		</section>
		<style>
			.search-table, td, th {
				border-collapse: collapse;
			}
			.search-table-outter { overflow-x: scroll; }
		</style>
		<!-- Main content -->

		<section class="content">
			<div class="row">
				<div class="box-body">
				    <div class="callout callout-success" style="vertical-align:top">
				        <?php echo "$PRJCODE - $PRJNAME"; ?>
				    </div>
					<div class="search-table-outter">
				        <form method="post" name="frmSearch" action="">
				        <?php
						{
							if($pageFrom == 'SPK')
							{
								?>
								<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
									<thead>
										<tr>
											<th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
											<th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
											<th width="10%" style="vertical-align:middle; text-align:center"><?php echo $StartDate; ?></th>
											<th width="11%" nowrap style="text-align:center"><?php echo $EndDate; ?></th>
											<th width="60%" nowrap style="text-align:center"><?php echo $Description; ?></th>
											<th width="8%" nowrap style="text-align:center; display:none"><?php echo $ReceivePlan; ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										$i = 0;
										$j = 0;
										$sqlSPKC	= "tbl_wo_detail A
														INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
														AND B.PRJCODE = '$PRJCODE'
														INNER JOIN tbl_joblist_detail C ON A.ITM_CODE = C.ITM_CODE
														AND C.PRJCODE = '$PRJCODE'
														AND C.ITM_GROUP = 'T'
														WHERE
															A.PRJCODE = '$PRJCODE'
															AND B.WO_STAT = 3
															AND B.WO_CATEG = 'SALT'";				
										$resSPKC 	= $this->db->count_all($sqlSPKC);
									
										$sqlSPK		= "SELECT DISTINCT
															B.WO_NUM AS PR_NUM,
															B.WO_CODE AS PR_CODE,
															B.WO_DATE PR_DATE,
															B.WO_ENDD PR_EDATE,
															'' AS PR_RECEIPTD,
															'' AS PR_RECEIPTD,
															B.WO_CREATER AS PR_CREATER,
															B.WO_APPROVER AS PR_APPROVER,
															B.JOBCODEID AS JOBCODE,
															B.WO_NOTE AS PR_NOTE,
															B.WO_STAT AS PR_STAT,
															B.WO_MEMO AS PR_MEMO,
															B.PRJCODE,
															C.ITM_GROUP
														FROM
															tbl_wo_detail A
														INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
														AND B.PRJCODE = '$PRJCODE'
														INNER JOIN tbl_joblist_detail C ON A.ITM_CODE = C.ITM_CODE
														AND C.PRJCODE = '$PRJCODE'
														AND C.ITM_GROUP = 'T'
														WHERE
															A.PRJCODE = '$PRJCODE'
															AND B.WO_STAT = 3
															AND B.WO_CATEG = 'SALT'";
										$vwAllSPK 	= $this->db->query($sqlSPK)->result();
										if($resSPKC > 0)
										{
											$totRow	= 0;
											foreach($vwAllSPK as $row) :
												$PR_NUM 		= $row->PR_NUM;
												$PR_CODE 		= $row->PR_CODE;
												$PR_DATE 		= $row->PR_DATE;
												$PR_DATE		= date('d M Y', strtotime($PR_DATE));
												$PR_EDATE 		= $row->PR_EDATE;
												$PR_CREATER 	= $row->PR_CREATER;
												
												// CARI DATA KARYAWAN
				                                $compName		= '';
				                                $sqlJOBDESC		= "SELECT First_Name, Last_Name FROM tbl_employee 
																	WHERE Emp_ID = '$PR_CREATER' LIMIT 1";
				                                $resJOBDESC		= $this->db->query($sqlJOBDESC)->result();
				                                foreach($resJOBDESC as $rowJOBDESC) :
				                                    $First_Name	= $rowJOBDESC->First_Name;
				                                    $Last_Name	= $rowJOBDESC->Last_Name;
													$empName1	= "$First_Name $Last_Name";
													$compName	= cut_text ("$empName1", 20);
				                                endforeach;
												
												$PR_RECEIPTD 	= $row->PR_RECEIPTD;
												$PR_RECEIPTD	= date('d M Y', strtotime($PR_RECEIPTD));
												$PR_STAT 		= $row->PR_STAT;
												$PR_APPROVER 	= $row->PR_APPROVER;
												$PR_NOTE 		= $row->PR_NOTE;
												//$PR_PLAN_IR	= $row->PR_PLAN_IR;
												//$proj_Number	= $row->proj_Number;
												$PRJCODE		= $row->PRJCODE;
												$PRJNAME		= $PRJNAME;	
												
												$totRow			= $totRow + 1;
											
												if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}
												?>
												<td style="text-align:center"><input type="radio" name="chk" value="<?php echo $PR_NUM;?>|<?php echo $PR_CODE;?>" onClick="pickThis(this);" /></td>
												<td nowrap>
													<a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
													<?php echo $PR_CODE; ?></a> 
													<input type="hidden" name="PR_NUM<?php echo $totRow; ?>" id="PR_NUM<?php echo $totRow; ?>" value="<?php echo $PR_NUM; ?>" />
												</td>
												<td style="text-align:center"><?php echo $PR_DATE; ?></td>
												<td style="text-align:center"><?php echo $PR_EDATE; ?></td>
												<td><?php echo $PR_NOTE; ?></td>
												<td style="text-align:center; display:none"><?php echo $PR_RECEIPTD; ?></td>
												</tr>
											<?php
											endforeach;
										}
									?>
									</tbody>
									<tr>
										<td colspan="6" nowrap>
										<button class="btn btn-primary" type="button" onClick="get_req();">
											<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
											<button class="btn btn-danger" type="button" onClick="window.close()">
											<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
										</td>
									</tr>
								</table>
								<?php
							}
							else
							{
								?>
								<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
									<thead>
										<tr>
											<th width="2%"><input type="checkbox" name="ChkAllItem" id="ChkAllItem" onClick="check_all(this)" style="display:none" /></th>
											<th width="9%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Code; ?></th>
											<th width="10%" style="vertical-align:middle; text-align:center"><?php echo $DateRequest; ?></th>
											<th width="71%" nowrap style="text-align:center"><?php echo $Description; ?></th>
											<th width="8%" nowrap style="text-align:center"><?php echo $ReceivePlan; ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										$i = 0;
										$j = 0;
										$sqlReqC	= "tbl_pr_header A
															LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
															INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
														WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
														ORDER BY A.PR_NUM ASC";				
										$resReqC 	= $this->db->count_all($sqlReqC);
									
										$sql 		= "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PR_RECEIPTD, A.PR_CREATER, A.PR_APPROVER, 
															A.JOBCODE, A.PR_NOTE, A.PR_STAT, A.PR_MEMO, A.PR_PLAN_IR,
															B.First_Name, B.Middle_Name, B.Last_Name,
															C.proj_Number, C.PRJCODE, C.PRJNAME
														FROM tbl_pr_header A
															LEFT JOIN  tbl_employee B ON A.PR_CREATER = B.Emp_ID
															INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
														WHERE A.PR_STAT = 3 AND A.PRJCODE = '$PRJCODE'
														ORDER BY A.PR_NUM ASC";
										$viewAllMR 	= $this->db->query($sql)->result();
										if($resReqC > 0)
										{
											$totRow	= 0;
											foreach($viewAllMR as $row) :
												$PR_NUM 		= $row->PR_NUM;
												$PR_CODE 		= $row->PR_CODE;
												$PR_DATE 		= $row->PR_DATE;
												$PR_DATE		= date('d M Y', strtotime($PR_DATE));
												$PR_CREATER 	= $row->PR_CREATER;
												$PR_RECEIPTD 	= $row->PR_RECEIPTD;
												$PR_RECEIPTD	= date('d M Y', strtotime($PR_RECEIPTD));
												$PR_STAT 		= $row->PR_STAT;
												$PR_APPROVER 	= $row->PR_APPROVER;
												$PR_NOTE 		= $row->PR_NOTE;
												$PR_PLAN_IR		= $row->PR_PLAN_IR;
												$proj_Number	= $row->proj_Number;
												$PRJCODE		= $row->PRJCODE;
												$PRJNAME		= $row->PRJNAME;
												$First_Name		= $row->First_Name;
												$Middle_Name	= $row->Middle_Name;
												$Last_Name		= $row->Last_Name;
												$compName 		= "$First_Name $Middle_Name $Last_Name";	
												
												$totRow			= $totRow + 1;
											
												if ($j==1) {
													echo "<tr class=zebra1>";
													$j++;
												} else {
													echo "<tr class=zebra2>";
													$j--;
												}
												?>
								  				<td style="text-align:center"><input type="radio" name="chk" value="<?php echo $PR_NUM;?>|<?php echo $PR_CODE;?>" onClick="pickThis(this);" /></td>
												<td nowrap>
													<a href="javascript:void(null);" onClick="showItem(<?php echo $totRow; ?>)" class="link">
													<?php echo $PR_CODE; ?></a> 
													<input type="hidden" name="PR_NUM<?php echo $totRow; ?>" id="PR_NUM<?php echo $totRow; ?>" value="<?php echo $PR_NUM; ?>" />
												</td>
												<td><?php echo $PR_DATE; ?></td>
												<td><?php echo $PR_NOTE; ?></td>
												<td style="text-align:center"><?php echo $PR_RECEIPTD; ?></td>
												</tr>
											<?php
											endforeach;
										}
									?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5" nowrap>
											<button class="btn btn-primary" type="button" onClick="get_req();">
												<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>                    </button> 
												<button class="btn btn-danger" type="button" onClick="window.close()">
												<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>                    </button>
											</td>
										</tr>
									</tfoot>
						  		</table>
								<?php
							}
						}
						?>
				        </form>
				    </div>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
				</div>
			</div>
		</section>
	</body>
</html>

<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<script>
var selectedRows = 0

function check_all(chk) {
	if(chk.checked) {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = true;
			}
		} else {
			document.frmSearch.chk.checked = true;
		}
		selectedRows = document.frmSearch.chk.length;
	} else {
		if(typeof(document.frmSearch.chk[0]) == 'object') {
			for(i=0;i<document.frmSearch.chk.length;i++) {
				document.frmSearch.chk[i].checked = false;
			}
		} else {
			document.frmSearch.chk.checked = false;
		}
		selectedRows = 0;
	}
}

function pickThis(thisobj) 
{
	var NumOfRows = document.frmSearch.chk.length; // minus 1 because it's the header
	if (thisobj!= '') 
	{
		if (thisobj.checked) selectedRows++;
		else selectedRows--;
	}
	if(selectedRows > 1)
	{
		swal('Please select one Request');
		return false;
	}
	
	if (selectedRows==NumOfRows) 
	{
		document.frmSearch.ChkAllItem.checked = true;
	}
	else
	{
		document.frmSearch.ChkAllItem.checked = false;
	}
}
	

function get_req() 
	{
		if(typeof(document.frmSearch.chk[0]) == 'object') 
		{
			for(i=0;i<document.frmSearch.chk.length;i++) 
			{
				if(document.frmSearch.chk[i].checked) 
				{
					A = document.frmSearch.chk[i].value
					arrItem = A.split('|');
					arrparent = document.frmSearch.chk[i].value.split('|');

					window.opener.add_header(document.frmSearch.chk[i].value);				
				}
			}
		}
		else
		{
			if(document.frmSearch.chk.checked)
			{
				window.opener.add_header(document.frmSearch.chk.value);
				//swal('2' + '\n' + document.frmSearch.chk.value)
				/*A = document.frmSearch.chk.value
				arrItem = A.split('|');
				//swal(arrItem)
				for(z=1;z<=5;z++)
				{
					swal('1')
					B=eval("document.frmSearch.chk_"+arrItem[0]+"_"+z).value;
					//window.opener.add_item(B,'child');
					swal(B)
				}*/
			}
		}
		window.close();		
	}
</script>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

    // Right side column. contains the Control Panel
    //$this->load->view('template/aside');

    //$this->load->view('template/js_data');

    //$this->load->view('template/foot');
?>