<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 7 November 2020
	* File Name		= v_joblist.php
	* Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$Emp_ID 	= $this->session->userdata['Emp_ID'];
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

$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          	$vers   = $this->session->userdata['vers'];

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
        
	  	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE 	= $this->session->userdata['ISDELETE'];
		if($ISDELETE == 1)
		{
			$ISREAD 	= 1;
			$ISCREATE 	= 1;
			$ISAPPROVE 	= 1;
		}
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Class')$Class = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Qty')$Qty = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Upload')$Upload = $LangTransl;
			if($TranslCode == 'sureReset')$sureReset = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$sureDelete	= "Anda yakin akan menghapus data ini?";
			$sureDelJob	= "Anda yakin akan menghapus pekerjaan ini?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'Sistem akan mengatur ulang urutan BoQ secara otomatis. Yakin?';
		}
		else
		{
			$sureDelete	= "Are your sure want to delete?";
			$sureDelJob	= "Are your sure want to delete this Job?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'System will reset BoQ Order ID automatically. Are you sure?';
		}

		$PRJCODE		= $PRJCODE;
		$PRJ_IMGNAME 	= "building.jpg";
		$RAPP_STAT 		= 0;
		$RAPT_STAT 		= 0;
		$PRJADD 		= "-";
		$PRJ_ISLOCK 	= 0;
		$RAPT_EDATE 	= date('Y-m-d');
		$RAPP_EDATE 	= date('Y-m-d');
		$PRJSTAT 		= 0;
		$sql 			= "SELECT PRJNAME, PRJPERIOD, PRJ_IMGNAME, PRJCOST, PRJRAPT, RAPP_STAT, RAPT_STAT, PRJADD, PRJ_LOCK_STAT, RAPT_EDATE, RAPP_EDATE, PRJSTAT
								FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME 	= $row->PRJNAME;
			$PRJPERIOD 	= $row->PRJPERIOD;
			$PRJ_IMGNAME= $row->PRJ_IMGNAME;
			$PRJCOST 	= $row->PRJCOST;
			$PRJRAPT 	= $row->PRJRAPT;
			$RAPP_STAT 	= $row->RAPP_STAT;
			$RAPT_STAT 	= $row->RAPT_STAT;
			$PRJADD 	= $row->PRJADD;
			$PRJ_ISLOCK	= $row->PRJ_LOCK_STAT;
			$RAPT_EDATE = $row->RAPT_EDATE;
			$RAPP_EDATE = $row->RAPP_EDATE;
			$PRJSTAT 	= $row->PRJSTAT;
		endforeach;
		if($RAPT_STAT == 0)
			$RAPP_STAT 	= 0;

		// START : LOCK PROCEDURE
			$dtRAPT   	= date('Y-m-d', strtotime($RAPT_EDATE));
			$dtRAPP   	= date('Y-m-d', strtotime($RAPP_EDATE));
			$dtN 		= date('Y-m-d');
			$dtNOW 		= new DateTime($dtN);
			$dtRAPTE	= new DateTime($dtRAPT);
			$dtRAPPE	= new DateTime($dtRAPP);
			$remRAPT	= $dtNOW->diff($dtRAPTE)->days + 1;
			$remRAPP	= $dtNOW->diff($dtRAPPE)->days + 1;

			$remRAPTD 	= $remRAPT." hari";
			$remRAPPD 	= $remRAPP." hari";
			$LOCRAPTKD 	= "";
			$LOCRAPPKD 	= "";
			if($dtN > $dtRAPT && $RAPT_STAT == 0 && $PRJSTAT == 1)			// LOCK RAPT 16 DAYS AFTER BOQ
			{
				$remRAPTD 	= "- ".$remRAPT." hari";
				$RAPT_STAT 	= 1;
				$LOCRAPTKD 	= "Locked by System.";
			}
			elseif($dtRAPT < $dtN && $RAPT_STAT == 1 && $PRJSTAT == 1)			// LOCK RAPT 16 DAYS AFTER BOQ
			{
				$remRAPTD 	= "Selesai";
				$RAPT_STAT 	= 1;
			}

			if($dtN > $dtRAPP && $RAPP_STAT == 0 && $PRJSTAT == 1)			// LOCK RAPP 60 DAYS AFTER RAPT
			{
				$remRAPPD 	= "- ".$remRAPP." hari";
				$RAPP_STAT 	= 1;
				$LOCRAPPKD 	= "Locked by System.";
			}
			elseif($dtRAPP < $dtN && $RAPP_STAT == 1 && $PRJSTAT == 1)			// LOCK RAPT 16 DAYS AFTER BOQ
			{
				$remRAPPD 	= "Selesai";
				$RAPP_STAT 	= 1;
			}
		// END : LOCK PROCEDURE
	?>
			
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>
    
    <?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
				<img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/wbs.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;
				<?php echo "$mnName ($PRJCODE)"; ?>
				<small><?php echo "$PRJNAME"; ?> </small>
				<input type="hidden" name="RAPT_STAT" id="RAPT_STAT" value="<?=$RAPT_STAT?>">
                <div class="pull-right">
                    <?php
                        echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>&nbsp;&nbsp;');
                        if($DefEmp_ID == 'D15040004221')
                        {
                        	echo '<button class="btn btn-info" onClick="RECOUNTRAP1()" title="Recount RAP"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';
                        	echo '<button class="btn btn-danger" onClick="RECOUNTJRN()" title="Recount Transaksi"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';
                        	echo '<button class="btn btn-warning" onClick="RESETVIEW1()" title="Reset View"><i class="glyphicon glyphicon-repeat"></i></button>&nbsp;';
                        }
                    ?>
                </div>
			</h1>
		</section>

        <section class="content">
			<div class="row">
				<?php
					// 1. TOTAL BOQ DAN RAP
						//$s_00 = "SELECT SUM(JOBCOST) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
						/*$s_00 	= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ FROM tbl_joblist_detail_$PRJCODEVW
									WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";*/

						$JOBPARID	= "A";
						$s_0a 		= "SELECT JOBCODEID FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' ORDER BY JOBCODEID LIMIT 1";
						$r_0a 		= $this->db->query($s_0a)->result();
						foreach($r_0a as $rw_0a):
							$JOBPARID 	= $rw_0a->JOBCODEID;
						endforeach;

						$RAPT_STAT	= 0;
						$s_00 		= "SELECT SUM(ITM_BUDG) AS TOT_RAPP, SUM(BOQ_JOBCOST) AS TOT_BOQ, RAPT_ISLOCK FROM tbl_joblist_detail_$PRJCODEVW
										WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
						$r_00 		= $this->db->query($s_00)->result();
						foreach($r_00 as $rw_00):
							//$TOT_RAPP = $rw_00->TOT_RAPP;
							$TOT_BOQ 	= $rw_00->TOT_BOQ;
							$RAPT_STAT 	= $rw_00->RAPT_ISLOCK;
						endforeach;

						$TOT_RAPT 	= 0;
						$TOT_RAPP	= 0;
						$TOT_ADD	= 0;
						$TOT_USED	= 0;
						$s_00 		= "SELECT SUM(ITM_BUDG) AS TOT_RAPP, SUM(RAPT_JOBCOST) AS TOT_RAPT, SUM(ADD_JOBCOST) AS TOT_ADD, SUM(ITM_USED_AM) AS TOT_USED
										FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";
						$r_00 		= $this->db->query($s_00)->result();
						foreach($r_00 as $rw_00):
							$TOT_RAPT 	= $rw_00->TOT_RAPT;
							$TOT_RAPP 	= $rw_00->TOT_RAPP;
							$TOT_ADD 	= $rw_00->TOT_ADD;
							$TOT_USED 	= $rw_00->TOT_USED;
						endforeach;
						$TOT_DEV	= $TOT_BOQ - $TOT_RAPP - $TOT_ADD;

						//$TOT_BOQ 		= $PRJCOST;
						if($TOT_RAPP == '' || $TOT_RAPP == 0)
						{
							$TOT_RAPP 	= 0;
							$TOT_RAPP 	= 1;
						}

						$TOT_BOQP 	= $TOT_BOQ;
						if($TOT_BOQ == '' || $TOT_BOQ == 0)
						{
							$TOT_BOQ 	= 0;
							$TOT_BOQP 	= 1;
						}

						$VDEV_RAPTBOQ 	= ($TOT_BOQ - $TOT_RAPT);
						$VDEV_RAPTBOQP = number_format($VDEV_RAPTBOQ / $TOT_BOQP * 100, 2);

						$VDEV_RAPPBOQ 	= ($TOT_BOQ - $TOT_RAPP);
						$VDEV_RAPPBOQP = number_format($VDEV_RAPPBOQ / $TOT_BOQP * 100, 2);
						
						$TOT_PROGIN 	= 0;
						$TOT_PROGEKS 	= 0;
						$s_02 		= "SELECT IFNULL(SUM(BOQ_BOBOT_PI),0) AS TOT_PROGIN, IFNULL(SUM(BOQ_BOBOT_PIEKS),0) AS TOT_PROGEKS
										FROM tbl_joblist_$PRJCODEVW WHERE PRJCODE = '$PRJCODE'";
						$r_02 		= $this->db->query($s_02)->result();
						foreach($r_02 as $rw_02):
							$TOT_PROGIN 	= $rw_02->TOT_PROGIN;
							$TOT_PROGEKS 	= $rw_02->TOT_PROGEKS;
						endforeach;


					// 2. TOTAL DIGUNAKAN
						/*$s_01 	= "SELECT SUM(REQ_AMOUNT) AS TOT_REQAMN, SUM(ITM_USED_AM) AS TOT_USEAMN FROM tbl_joblist_detail_$PRJCODEVW
									WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";*/
						$s_01 	= "SELECT SUM(PO_VAL+PO_VAL_R-PO_CVAL+WO_VAL-WO_CVOL+WO_CVAL+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS TOT_REQAMN,
										SUM(OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS TOT_USEAMN FROM tbl_joblist_detail_$PRJCODEVW
									WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";
						$r_01 	= $this->db->query($s_01)->result();
						foreach($r_01 as $rw_01):
							$TOT_REQAMN = $rw_01->TOT_REQAMN;
							$TOT_USEAMN = $rw_01->TOT_USEAMN;
						endforeach;
						if($TOT_REQAMN == '')
							$TOT_REQAMN = 0;
						if($TOT_USEAMN == '')
							$TOT_USEAMN = 0;

					// KOLOM RAP
						if($VDEV_RAPPBOQ > 0)
						{
							$DEVDESC_1 	= "<span class='pull-right'><i class='fa fa-chevron-circle-up'></i> $VDEV_RAPPBOQP % </span>";
							$DEVDESC_2 	= "<i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAP'></i> ".number_format(abs($VDEV_RAPTBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAPT (%)'></i> $VDEV_RAPTBOQP % </span>";
							$DEVDESC_3 	= "<span class='pull-right'><i title='Deviasi BoQ vs RAPT'></i><i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAPT'></i>&nbsp; ".number_format(abs($VDEV_RAPTBOQ), 0)."</span>";
							$DEVDESC_4 	= "<i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAP'></i> ".number_format(abs($VDEV_RAPPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAP (%)'></i> $VDEV_RAPPBOQP % </span>";
							$DEVDESC_5 	= "<span class='pull-right'><i title='Deviasi BoQ vs RAPP'></i><i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAPP'></i>&nbsp; ".number_format(abs($VDEV_RAPPBOQ), 0)."</span>";
						}
						else
						{
							$DEVDESC_1 	= "<span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $VDEV_RAPPBOQP % </span>";
							$DEVDESC_2 	= "<i class='fa fa-chevron-circle-down'></i> ".number_format(abs($VDEV_RAPTBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $VDEV_RAPTBOQP % </span>";
							$DEVDESC_3 	= "<span class='pull-right'><i title='Deviasi BoQ vs RAPT'></i><i class='fa fa-chevron-circle-down' title='Deviasi BoQ vs RAPT'></i>&nbsp; ".number_format(abs($VDEV_RAPTBOQ), 0)."</span>";
							$DEVDESC_4 	= "<i class='fa fa-chevron-circle-down'></i> ".number_format(abs($VDEV_RAPPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $VDEV_RAPPBOQP % </span>";
							$DEVDESC_5 	= "<span class='pull-right'><i title='Deviasi BoQ vs RAPP'></i><i class='fa fa-chevron-circle-down' title='Deviasi BoQ vs RAPP'></i>&nbsp; ".number_format(abs($VDEV_RAPPBOQ), 0)."</span>";
						}
					
					// KOLOM REQUEST AND USED
						$VTOT_REQAMN 	= number_format($TOT_REQAMN,0);
						$VTOT_USEAMN 	= number_format($TOT_USEAMN,0);
						$VTOT_REQAMNPERC= $TOT_REQAMN / $TOT_RAPP * 100;
						$VTOT_USEAMNPERC= $TOT_USEAMN / $TOT_RAPP * 100;
					
					// KOLOM REMAIN
						$REM_RAPREQ		= $TOT_RAPP - $TOT_REQAMN;
						$REM_RAP 		= $TOT_RAPP - $TOT_USEAMN;
						$REM_RAPPERCRQ 	= $REM_RAPREQ / $TOT_RAPP * 100;
						$REM_RAPPERC 	= $REM_RAP / $TOT_RAPP * 100;
				?>
		        <div class="col-md-3">
		          	<div class="info-box bg-blue">
		            	<span class="info-box-icon"><i class="ion ion-clipboard"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">Bill of Quantity (BoQ)</span>
			              	<span class="info-box-number"><?=number_format($TOT_BOQ, 0)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
		                		<i class="fa fa-line-chart" title="Internal"></i> 
			                	<?=number_format($TOT_PROGIN, 4)?>
			                	<span class='pull-right' title="Eksternal">
			                		<i class="fa fa-bar-chart"></i>
				                	<?=number_format($TOT_PROGEKS, 4)?> %
				                </span>
			                </span>
			            </div>
			        </div>
			    </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-green">
			            <span class="info-box-icon"><i class="ion ion-calculator"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">
			              		RAPT
				                <div class="pull-right">
									<div id="divLock3" <?php if($RAPT_STAT == 0) { ?> style="display: none;" <?php } ?>>
										<i class="fa fa-lock"></i>
									</div>
								</div>
			              	</span>
			              	<span class="info-box-number"><?=number_format($TOT_RAPT, 0)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
			               		<?=$DEVDESC_2?>
			                </span>
			            </div>
		         	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-yellow">
			            <span class="info-box-icon"><i class="ion ion-calculator"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text">
			              		RAPP
				                <div class="pull-right">
									<div id="divLock2" <?php if($RAPP_STAT == 0) { ?> style="display: none;" <?php } ?>>
										<i class="fa fa-lock"></i>
									</div>
								</div>
			              	</span>
			              	<span class="info-box-number"><?=number_format($TOT_RAPP, 0)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: 100%"></div>
			              	</div>
			                <span class="progress-description">
			               		<?=$DEVDESC_4?>
			                </span>
			            </div>
		         	</div>
		        </div>
		        <div class="col-md-3" style="display: none;">
		          	<div class="info-box bg-yellow ">
			            <span class="info-box-icon"><i class="ion ion-connection-bars"></i></span>

			            <div class="info-box-content">
			              	<span class="info-box-text" title="OP, SPK, VCASH, PPD, VLK">Diminta</span>
			              	<span class="info-box-number" title="OP, SPK, VCASH, PPD, VLK"><?=$VTOT_REQAMN?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$VTOT_REQAMNPERC?>%"></div>
			              	</div>
			                <span class="progress-description">
		                		<i class="fa fa-cog" title="Total Permintaan <?php echo $VTOT_REQAMN; ?>"></i> 
			                	<?=number_format($VTOT_REQAMNPERC,2)?> %
			                	<span class='pull-right' title="Total Penggunaan <?php echo $VTOT_USEAMN; ?>">
			                		<i class="fa fa-cogs"></i>
				                	<?=number_format($VTOT_USEAMNPERC,2)?> %
				                </span>
			                </span>
			            </div>
		          	</div>
		        </div>
		        <div class="col-md-3">
		          	<div class="info-box bg-aqua">
		            	<div class="box-body">
		              		<span class="info-box-text">Sisa Anggaran</span>
		              		<span class="info-box-number"><?=number_format($REM_RAPREQ,2)?><div class="pull-right"><?=number_format($REM_RAPPERCRQ,2)?>%</div></span>
		              		

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$VTOT_REQAMNPERC?>%"></div>
			              	</div>
			              	<span class="progress-description" title="Diminta">
			              		<i class="fa fa-exclamation-triangle" title="Diminta"></i>&nbsp;
			                	<?=$VTOT_REQAMN?>
			                	<div class="pull-right"><?=number_format($VTOT_REQAMNPERC,2)?>%</div>
			              	</span>
			           	</div>
		            </div>
		      	</div>
		   	</div>

			<div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
		  	<div class="box">
				<div class="box-body">
					<div class="search-table-outter">
						<!-- <table id="tree-table" class="table table-hover table-bordered"> -->
						<table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					        <thead>
					            <tr>
					                <th width="2%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					                <th width="36%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description; ?></th>
					                <th width="2%" style="text-align:center; vertical-align:middle" nowrap>Lev.</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Sat.</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Volume; ?></th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?> (BoQ)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Amount; ?> (RAP)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Add. (+)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Deviasi<br>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Used; ?></th>
					          </tr>
					        </thead>
							<tbody>
							</tbody>
							<tfoot>
								<?php
									/*$TOT_RAP	= 0;
									$TOT_RAB 	= 0;
									$TOT_ADD	= 0;
									$TOT_USED	= 0;*/
									/*$sqlTBUDG	= "SELECT SUM(ITM_VOLM * ITM_PRICE) AS TOT_RAP,
														SUM(BOQ_JOBCOST) AS TOT_RAB,
														SUM(ADD_VOLM * ADD_PRICE) AS TOT_ADD,
														SUM(ITM_USED_AM) AS TOT_USED
													FROM tbl_joblist_detail WHERE IS_LEVEL = 1 AND PRJCODE = '$PRJCODE'";*/
									/*$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_RAB
													FROM tbl_joblist_detail WHERE ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_RAP	= $rowTBUDG->TOT_RAP;
										$TOT_RAB 	= $rowTBUDG->TOT_RAB;
									endforeach;
									//$TOT_DEV	= $TOT_RAB - $TOT_RAP;

									$sqlTBUDG	= "SELECT SUM(ADD_JOBCOST) AS TOT_ADD, SUM(ITM_USED_AM) AS TOT_USED
													FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_ADD 	= $rowTBUDG->TOT_ADD;
										$TOT_USED 	= $rowTBUDG->TOT_USED;
									endforeach;
									$TOT_DEV	= $TOT_RAB - $TOT_RAP - $TOT_ADD;*/
								?>
					            <tr>
					                <th colspan="4" style="text-align:center; vertical-align:middle" nowrap>T O T A L</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?=number_format($TOT_BOQ, 2)?></th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?=number_format($TOT_RAPP, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_ADD, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_DEV, 2)?></th>
					                <th width="10%" style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_USED, 2)?></th>
					          	</tr>
			                </tfoot>
						</table>
					</div>
					<br>
				</div>
				<div id="loading_1" class="overlay" style="display:none">
		            <i class="fa fa-refresh fa-spin"></i>
		        </div>
			</div>
			<div class="row" id="RECOUNTRAPDESC" style="display: none;">
                <div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
                        Proses ini akan melakukan:<br>
                        1. Menghapus komponen/pekerjaan/item yang double dalam 1 induk berdasarkan kode item<br> 
                        2. Menghitung ulang jumlah total RAP, harga rata-rata dari masing-masing tingkatan / level.<br>
                        <button class="btn btn-info" onClick="RECOUNTRAP()"></i>Lanjutkan</button>
                    </div>
                </div>
            </div>
			<div class="row" id="RESETVIEWDESC" style="display: none;">
                <div class="col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
                        Proses ini akan menghapus seluruh view kemudian membuat ulang semua view table.<br>
                        <button class="btn btn-info" onClick="RESETVIEW()"></i>Lanjutkan</button>
                    </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-12" id="idprogbarXY" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXY" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>
        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/impCOA' ) ?>" style="width: 100%; display: none;"></iframe>
		
		<!-- <script src="http://code.jquery.com/jquery-1.12.4.min.js"></script> 
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/js_tree/javascript.js'; ?>"></script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-36251023-1']);
			_gaq.push(['_setDomainName', 'jqueryscript.net']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script> -->
	</body> 
</html>

<script>
	$(document).ready(function()
	{
		setInterval(function(){checkLOCK()}, 2000);
	});

	function checkLOCK()
	{
		PRJCODE 	= "<?=$PRJCODE?>";
		RAPTSTATB 	= document.getElementById('RAPT_STAT').value;

		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getRAPTSTAT')?>",
            data: {PRJCODE : PRJCODE},
            success: function(response)
            {
            	if(RAPTSTATB != response)
            		$('#example').DataTable().ajax.reload();
            }
        });
	}

    $(document).ready(function()
    {
	    $('#example').DataTable( {
			"dom": "<'row'<'col-sm-2'l><'col-sm-8'<'toolbar'>><'col-sm-2'f>>"+
					"<'row'<'col-sm-12'tr>>",
	    	"bDestroy": true,
	    	"bSort" : false,
	        "processing": true,
	        "serverSide": true,
	        //"scrollX": false,
	        "autoWidth": true,
	        "filter": true,
	        //"ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataJL/?id='.$PRJCODE)?>",
	        "ajax": {
				        "url": "<?php echo site_url('c_project/c_joblist/get_AllDataJL/?id='.$PRJCODE)?>",
				        "type": "POST",
						"data": function(data) {
							data.JOBPARID = $('#JOBPARID').val();
						},
			        },
	        "type": "POST",
	        "lengthMenu": [[50, 200, -1], [50, 200, "All"]],
	        //"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
	        "columnDefs": [ { targets: [5], className: 'dt-body-center' }
	                      ],
	        //"order": [[ 2, "desc" ]],
	        "language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
	    } );
		
		$('div.toolbar').html('<form id="form-filter" class="form-horizontal">'+
							  '<input type="hidden" name="JOBPARIDX" class="form-control" id="JOBPARIDX" value="<?php echo $JOBPARID; ?>">'+
							  '</div>&nbsp;'+
							  '<select class="form-control select2" name="JOBPARID" id="JOBPARID" data-placeholder="Daftar Pekerjaan" style="width: 250px;">'+
							  '<option value=""></option></select>&nbsp;'+
							  '<button type="button" id="btn-filter" class="btn btn-success"><i class="fa fa-check"></i>&nbsp;&nbsp;OK</button>&nbsp;'+
							  '</form>');

		var PRJCODE 	= "<?=$PRJCODE?>";
		$.ajax({
			url: "<?php echo site_url('__l1y/get_AllJLPRJ'); ?>",
			type: "POST",
			dataType: "JSON",
			data: {PRJCODE:PRJCODE},
			success: function(result) {
				console.log(result.length);
				var selected 	= "";
				var JOBPDESC 	= "<option value=''></option>";
				for(let i in result) {
					JID 		= result[i]['JOBCODEID'];
					JDESC 		= result[i]['JOBDESC'];
					JOBPDESC 	+= '<option value="'+JID+'">'+JID+' : '+JDESC+'</option>';
				}
				$('#JOBPARID').html(JOBPDESC);
				$('#JOBPARID').val(EmpID).trigger('change');
			}
		});

		$('#JOBPARID').change(function(e)
		{
			id = $(this).val();
			$('#JOBPARIDX').val(id);
		});

		$('#btn-filter').bind('click', function(){
			$('#example').DataTable().ajax.reload();
		});
    });
	
	function RECOUNTRAP1()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
		document.getElementById('RECOUNTRAPDESC').style.display 	= '';
	}
	
	function RECOUNTRAP()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
		document.getElementById('RECOUNTRAPDESC').style.display 	= 'none';
		
		document.getElementById('idprogbar').style.display 		= '';
	    document.getElementById("progressbarXX").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 	= '';
	    document.getElementById("progressbarXY").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('loading_1').style.display = '';
    	var collID	= PRJCODE;

		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RECOUNTRAP';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RECOUNTRAP';
		butSubm.submit();

        /*$.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	document.getElementById('loading_1').style.display = 'none';
            	swal(response, 
				{
					icon: "success",
				});
            }
        });*/
	}
	
	function RECOUNTJRN()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";

		document.getElementById('idprogbar').style.display 		= '';
	    document.getElementById("progressbarXX").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 	= '';
	    document.getElementById("progressbarXY").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('loading_1').style.display = '';
    	var collID	= PRJCODE;

		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RECOUNTJRN';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RECOUNTJRN';
		butSubm.submit();

        /*$.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	document.getElementById('loading_1').style.display = 'none';
            	swal(response, 
				{
					icon: "success",
				});
            }
        });*/
	}
	
	function RESETVIEW1()
	{
		document.getElementById('RESETVIEWDESC').style.display 	= '';
	}
	
	function RESETVIEW()
	{
		document.getElementById('RESETVIEWDESC').style.display 	= 'none';
		
		document.getElementById('idprogbar').style.display 		= '';
	    document.getElementById("progressbarXX").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 	= '';
	    document.getElementById("progressbarXY").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('loading_1').style.display = '';
    	var collID	= PRJCODE;

		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETVIEW';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETVIEW';
		butSubm.submit();
	}

	function ReSUM(row)
	{
        var collID	= document.getElementById('urlReSUM'+row).value;
        var myarr 	= collID.split("~");
        var url 	= myarr[0];
        
        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	/*swal(response,
				{
					icon: "success",
				});*/
                $('#example').DataTable().ajax.reload();
            }
        });
	}
	
	function syncJLD()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
	    swal({
            text: "<?php echo $sureReset; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETWBD';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETWBD';
				butSubm.submit();

		        /*$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
		            	swal(response, 
						{
							icon: "success",
						});
		            }
		        });*/
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function updJob(row)
	{
		var url	= document.getElementById('urlUpdate'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function showDetC(LinkD)
	{
		w = 700;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}
	
	function resOrder()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
	    swal({
            text: "<?php echo $sureResOrd; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'REODERBOQ';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'REODERBOQ';
				butSubm.submit();

		        /*$.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	document.getElementById('loading_1').style.display = 'none';
		            	swal(response, 
						{
							icon: "success",
						});
		            }
		        });*/
            } 
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function deleteJL(row)
	{
	    swal({
            text: "<?php echo $sureDelJob; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlDelJob'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	/*swal(response, 
						{
							icon: "success",
						});*/
		                $('#example').DataTable().ajax.reload();
		            }
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
		
		//Datemask dd/mm/yyyy
		$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
		//Datemask2 mm/dd/yyyy
		$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
		//Money Euro
		$("[data-mask]").inputmask();
		
		//Date range picker
		$('#reservation').daterangepicker();
		//Date range picker with time picker
		$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
		//Date range as a button
		$('#daterange-btn').daterangepicker(
			{
			  ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			  },
			  startDate: moment().subtract(29, 'days'),
			  endDate: moment()
			},
			function (start, end) {
			  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);
		
		//Date picker
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true,
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
		});
		
		//iCheck for checkbox and radio inputs
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass: 'iradio_minimal-blue'
		});
		//Red color scheme for iCheck
		$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		  checkboxClass: 'icheckbox_minimal-red',
		  radioClass: 'iradio_minimal-red'
		});
		//Flat red color scheme for iCheck
		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		  checkboxClass: 'icheckbox_flat-green',
		  radioClass: 'iradio_flat-green'
		});
		
		//Colorpicker
		$(".my-colorpicker1").colorpicker();
		//color picker with addon
		$(".my-colorpicker2").colorpicker();
		
		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});
	});
	
    function sleep(milliseconds) { 
        let timeStart = new Date().getTime(); 
        while (true) { 
            let elapsedTime = new Date().getTime() - timeStart; 
            if (elapsedTime > milliseconds) { 
                break; 
            } 
        } 
    }

	function updStat()
	{
		var timer = setInterval(function()
		{
	       	clsBar();
      	}, 4000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display = 'none';
		document.getElementById('idprogbarXY').style.display = 'none';
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
    //______$this->load->view('template/aside');

    //______$this->load->view('template/js_data');

    //______$this->load->view('template/foot');
?>