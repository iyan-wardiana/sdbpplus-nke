<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 Juli 2019
 * File Name	= joblistdet.php
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

		$canAppDesc	= "Mohon maaf, Anda tidak memiliki otorisasi untuk menyetujui RAPT ini.";
		if($ISDELETE == 1)
		{
			$ISREAD 	= 1;
			$ISCREATE 	= 1;
			$ISAPPROVE 	= 1;
			$canAppDesc	= "";
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
			if($TranslCode == 'Budget')$Budget = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'copyProg')$copyProg = $LangTransl;
			if($TranslCode == 'copyBudg')$copyBudg = $LangTransl;
			if($TranslCode == 'addJob')$addJob = $LangTransl;
			if($TranslCode == 'budgSrc')$budgSrc = $LangTransl;
			if($TranslCode == 'budgDest')$budgDest = $LangTransl;
			if($TranslCode == 'Success')$Success = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'LastLevel')$LastLevel = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'lockRAP')$lockRAP = $LangTransl;
			if($TranslCode == 'lockRAPT')$lockRAPT = $LangTransl;
			if($TranslCode == 'lockRAPP')$lockRAPP = $LangTransl;
			if($TranslCode == 'lockInfo')$lockInfo = $LangTransl;
			if($TranslCode == 'ProjectInformation')$ProjectInformation = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert1		= "Anda Yakin?";
			$alert2		= "Proses ini akan me-reset anggaran sebelumnya.";
			$alert3		= "Baik! Proses akan dilanjutkan. Mohon tunggu beberapa saat.";
			$alert4		= "Baik! Proses reset master budget akan dibatalkan.";
			$alert5		= "Anda belum memilih Anggaran tujuan penyalinan.";

			$alert6		= "Anda belum menentukan induk pekerjaan.";
            $alert7     = "Kode pekerjaan tidak boleh kosong.";
            $alert8     = "Nama pekerjaan tidak boleh kosong.";
            $alert9     = "Anda belum menentukan unit pekerjaan.";
            $alert10    = "Silahkan tentukan level pekerjaan.";
            $alert11    = "Anda tidak dapat membuka kembali penguncian setelah Anda menyetujuinya.";

			$sureDelete	= "Anda yakin akan menghapus data ini?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'Sistem akan mengatur ulang urutan BoQ secara otomatis. Yakin?';
		}
		else
		{
			$alert1		= "Are you sure?";
			$alert2		= "This process will reset the previous budget.";
			$alert3		= "Well! The process will be processed. Please wait a few moments.";
			$alert4		= "Well! The budget master reset process will be canceled.";
			$alert5		= "You have not selected a copy destination budget.";

    		$alert6		= "You have not defined a parent job yet.";
    		$alert7		= "Job code can not be empty.";
            $alert8     = "Job description can not be empty.";
            $alert9     = "You have not defined a job unit yet.";
            $alert10    = "Please set level of job.";
            $alert11    = "You cannot re-open this lock once you agree to it.";

			$sureDelete	= "Are your sure want to delete?";
			$h_title	= "WBS Detail";
			$sureResOrd	= 'System will reset BoQ Order ID automatically. Are you sure?';
		}

		$PRJCOST 		= 0;
		$PRJRAPT 		= 0;
		$PRJCODE		= $PRJCODE;
		$PRJ_IMGNAME 	= "building.jpg";
		$RAP_STAT 		= 0;
		$PRJADD 		= "-";
		$sql 			= "SELECT PRJNAME, PRJPERIOD, PRJ_IMGNAME, PRJCOST, PRJRAPT, RAP_STAT, PRJADD FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$result 		= $this->db->query($sql)->result();
		foreach($result as $row) :
			$PRJNAME 	= $row->PRJNAME;
			$PRJPERIOD 	= $row->PRJPERIOD;
			$PRJ_IMGNAME= $row->PRJ_IMGNAME;
			$PRJCOST 	= $row->PRJCOST;
			$PRJRAPT 	= $row->PRJRAPT;
			$RAP_STAT 	= $row->RAP_STAT;
			$PRJADD 	= $row->PRJADD;
		endforeach;

		$sIMP	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
		$rJL	= $this->db->count_all($sIMP);

		$sIMP	= "tbl_item WHERE PRJCODE = '$PRJCODE'";
		$rITM	= $this->db->count_all($sIMP);

		$imgLoc		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE.'/'.$PRJ_IMGNAME);
		if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODE))
		{
			$imgLoc	= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
		}
		$isLoadDone_1	= 1;

		$LOCK_DOCDATE 	= date('d/m/Y');
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
				<?php echo $mnName." ".$PRJCODE; ?>
				<small><?php echo "$PRJNAME"; ?> </small>
				<div class="pull-right" style="display: none;">
					<div id="divUnLock" <?php if($RAP_STAT == 1) { ?> style="display: none;" <?php } ?>>
						<span class="label label-danger" data-toggle="modal" data-target="#mdl_LOCKRAPT" style="cursor: pointer;" style="display: none;"><i class="fa fa-unlock-alt"></i></span>
					</div>
					<div id="divLock" <?php if($RAP_STAT == 0) { ?> style="display: none;" <?php } ?>>
						<span class="label label-success" onClick="showLOCKER('<?=$PRJCODE?>')" style="cursor: pointer;"><i class="fa fa-lock"></i>
						</span>
					</div>
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

						$RAPT_STAT	= 0;
						$s_00 		= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ, RAPT_ISLOCK FROM tbl_joblist_detail_$PRJCODEVW
										WHERE PRJCODE = '$PRJCODE' AND ISLASTH = 1";
						$r_00 		= $this->db->query($s_00)->result();
						foreach($r_00 as $rw_00):
							//$TOT_RAP 	= $rw_00->TOT_RAP;
							$TOT_BOQ 	= $rw_00->TOT_BOQ;
							$RAPT_STAT 	= $rw_00->RAPT_ISLOCK;
						endforeach;

						$TOT_RAPT 	= 0;
						$TOT_RAP	= 0;
						$TOT_ADD	= 0;
						$TOT_USED	= 0;
						$s_00 		= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(RAPT_JOBCOST) AS TOT_RAPT, SUM(ADD_JOBCOST) AS TOT_ADD, SUM(ITM_USED_AM) AS TOT_USED
										FROM tbl_joblist_detail_$PRJCODEVW WHERE PRJCODE = '$PRJCODE' AND ISLAST = 1";
						$r_00 		= $this->db->query($s_00)->result();
						foreach($r_00 as $rw_00):
							$TOT_RAPT 	= $rw_00->TOT_RAPT;
							$TOT_RAP 	= $rw_00->TOT_RAP;
							$TOT_ADD 	= $rw_00->TOT_ADD;
							$TOT_USED 	= $rw_00->TOT_USED;
						endforeach;
						$TOT_DEV	= $TOT_BOQ - $TOT_RAP - $TOT_ADD;

						//$TOT_BOQ 		= $PRJCOST;
						$TOT_RAPP 		= $TOT_RAP;
						if($TOT_RAP == '' || $TOT_RAP == 0)
						{
							$TOT_RAP 	= 0;
							$TOT_RAPP 	= 1;
						}

						$TOT_BOQP 	= $TOT_BOQ;
						if($TOT_BOQ == '' || $TOT_BOQ == 0)
						{
							$TOT_BOQ 	= 0;
							$TOT_BOQP 	= 1;
						}

						$VDEV_RAPBOQ 	= ($TOT_BOQ - $TOT_RAP);
						$DEV_RAPBOQPERC = number_format($VDEV_RAPBOQ / $TOT_BOQP * 100, 2);
						
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
						$s_01 	= "SELECT SUM(REQ_AMOUNT) AS TOT_REQAMN, SUM(ITM_USED_AM) AS TOT_USEAMN FROM tbl_joblist_detail_$PRJCODEVW
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
						if($VDEV_RAPBOQ > 0)
						{
							$DEVDESC_1 	= "<span class='pull-right'><i class='fa fa-chevron-circle-up'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_2 	= "<i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAP'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAP (%)'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_3 	= "<span class='pull-right'><i title='Deviasi BoQ vs RAPP'></i><i class='fa fa-chevron-circle-up' title='Deviasi BoQ vs RAPP'></i>&nbsp; ".number_format(abs($VDEV_RAPBOQ), 0)."</span>";
						}
						else
						{
							$DEVDESC_1 	= "<span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_2 	= "<i class='fa fa-chevron-circle-down'></i> ".number_format(abs($VDEV_RAPBOQ), 0)." <span class='pull-right'><i class='fa fa-chevron-circle-down'></i> $DEV_RAPBOQPERC % </span>";
							$DEVDESC_3 	= "<span class='pull-right'><i title='Deviasi BoQ vs RAPP'></i><i class='fa fa-chevron-circle-down' title='Deviasi BoQ vs RAPP'></i>&nbsp; ".number_format(abs($VDEV_RAPBOQ), 0)."</span>";
						}
						$VTOT_RAP 		= $TOT_RAP;
					
					// KOLOM REQUEST AND USED
						$VTOT_REQAMN 	= number_format($TOT_REQAMN,0);
						$VTOT_USEAMN 	= number_format($TOT_USEAMN,0);
						$VTOT_REQAMNPERC= $TOT_REQAMN / $TOT_RAPP * 100;
						$VTOT_USEAMNPERC= $TOT_USEAMN / $TOT_RAPP * 100;
					
					// KOLOM REMAIN
						$REM_RAPREQ		= $TOT_RAP - $TOT_REQAMN;
						$REM_RAP 		= $TOT_RAP - $TOT_USEAMN;
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
			              	<span class="info-box-text">RAP</span>
			              	<span class="info-box-number"><?=number_format($VTOT_RAP, 0)?></span>

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
		            	<span class="info-box-icon"><i class="ion-information-circled"></i></span>

		            	<div class="info-box-content">
		              		<span class="info-box-text">Sisa Anggaran</span>
		              		<span class="info-box-number"><?=number_format($REM_RAPREQ,2)?></span>

			              	<div class="progress">
			                	<div class="progress-bar" style="width: <?=$REM_RAPPERCRQ?>%"></div>
			              	</div>
			              	<span class="progress-description" title="Sisa RAP (%)">
			              		<i class="fa fa-cubes"></i>
			                	<?=number_format($REM_RAPPERCRQ,2)?> %
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
					                <th width="25%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Description; ?></th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Sat</th>
					                <th width="5%" style="text-align:center; vertical-align:middle" nowrap>Vol.</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Price; ?></th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>
						                RAPT
						                <div class="pull-right">
											<div id="divUnLock" <?php if($RAPT_STAT == 1) { ?> style="display: none;" <?php } ?>>
												<span data-toggle="modal" data-target="#mdl_LOCKRAPT" style="cursor: pointer;" style="display: none;"><i class="fa fa-unlock"></i></span>
											</div>
											<div id="divLock" <?php if($RAPT_STAT == 0) { ?> style="display: none;" <?php } ?>>
												<span onClick="showLOCKER('<?=$PRJCODE?>')" style="cursor: pointer;"><i class="fa fa-lock"></i></span>
											</div>
										</div>
						            </th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>
						                RAPP
						                <div class="pull-right">
											<div id="divUnLock" <?php if($RAP_STAT == 1) { ?> style="display: none;" <?php } ?>>
												<span data-toggle="modal" data-target="#mdl_LOCKRAPP" style="cursor: pointer;" style="display: none;"><i class="fa fa-unlock"></i></span>
											</div>
											<div id="divLock" <?php if($RAP_STAT == 0) { ?> style="display: none;" <?php } ?>>
												<span onClick="showLOCKER('<?=$PRJCODE?>')" style="cursor: pointer;"><i class="fa fa-lock"></i></span>
											</div>
										</div>
						            </th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>BoQ</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" nowrap>Add. (+)</th>
					                <th width="10%" style="text-align:center; vertical-align:middle" title="BoQ - RAPP - BA" nowrap>Deviasi</th>
					                <th width="3%" style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					          </tr>
					        </thead>
							<tbody>
							</tbody>
							<tfoot>
								<?php
									//$TOT_RAB 	= 0;
									//$TOT_RAP	= 0;
									/*$TOT_ADD	= 0;
									$TOT_USED	= 0;*/
									/*$sqlTBUDG	= "SELECT SUM(ITM_VOLM * ITM_PRICE) AS TOT_VOLBG, SUM(ADD_VOLM * ADD_PRICE) AS TOT_ADDBG, SUM(ITM_USED_AM) AS TOT_USEBG
													FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";*/
									/*$sqlTBUDG	= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_RAB
													FROM tbl_joblist_detail WHERE ISLASTH = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_RAP	= $rowTBUDG->TOT_RAP;
										$TOT_RAB 	= $rowTBUDG->TOT_RAB;
									endforeach;*/
									//$TOT_REMBG	= $TOT_RAP + $TOT_ADD - $TOT_USED;

									/*$sqlTBUDG	= "SELECT SUM(ADD_JOBCOST) AS TOT_ADD, SUM(ITM_USED_AM) AS TOT_USED
													FROM tbl_joblist_detail WHERE ISLAST = 1 AND PRJCODE = '$PRJCODE'";
									$resTBUDG 	= $this->db->query($sqlTBUDG)->result();
									foreach($resTBUDG as $rowTBUDG) :
										$TOT_ADD 	= $rowTBUDG->TOT_ADD;
										$TOT_USED 	= $rowTBUDG->TOT_USED;
									endforeach;
									$TOT_DEV	= $TOT_BOQ - $TOT_RAP - $TOT_ADD;*/

									if($RAPT_STAT == 0)
										$TOT_RAP = 0;
								?>
					            <tr>
					                <th colspan="5" style="text-align:center; vertical-align:middle" nowrap>T O T A L</th>
					                <th style="text-align:center; vertical-align:middle" nowrap><?=number_format($TOT_RAPT, 2)?></th>
					                <th style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_RAP, 2)?></th>
					                <th style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_BOQ, 2)?></th>
					                <th style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_ADD, 2)?></th>
					                <th style="text-align:right; vertical-align:middle" nowrap><?=number_format($TOT_DEV, 2)?></th>
					                <th style="text-align:center; vertical-align:middle" nowrap>&nbsp;</th>
					          	</tr>
			                </tfoot>
						</table>
					</div>
					<br>
	                <?php
	                    if($DefEmp_ID == 'D15040004221')
	                    {
	                    	echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
	                    	if($RAP_STAT == 0)
	                        	echo anchor("$secUpl",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>&nbsp;');
	                        echo '<button class="btn btn-info" onClick="ShwResOrder()" title="Reset Order"><i class="glyphicon glyphicon-th-list"></i></button>&nbsp;';
	                		//echo '<button class="btn btn-danger" onClick="ShwSyncJLD()" title="Recount"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';
	                		echo '<button class="btn btn-danger" onClick="ShwSyncLR()" title="Sinkronisasi L/R"><i class="glyphicon glyphicon-refresh"></i></button>&nbsp;';
	                		echo '<button class="btn btn-success" onClick="ShwSyncJLDTRX()" title="Sync. Transaksi ke Pekerjaan"><i class="glyphicon glyphicon-list-alt"></i></button>&nbsp;';
	                		echo '<button class="btn btn-success" onClick="ShwSyncDASHTRX()" title="Sync. Transaksi ke Dashboard"><i class="glyphicon glyphicon-dashboard"></i></button>&nbsp;';
							//echo '<button class="btn btn-warning" title="Tambahkan Pekerjaan" data-toggle="modal" data-target="#mdl_addJH"><i class="fa fa-copy" id="mdlAddJH"></i></button>&nbsp;';
	                    }
	                    else if($ISCREATE == 1)
	                    {
	                    	if($RAP_STAT == 0)
	                    	{
	                    		echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
	                        	echo anchor("$secUpl",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>&nbsp;');
	                        }
	                    }
	                    if($pgFrom == 'HO')
							echo '<button class="btn btn-warning" title="Copy Budget" data-toggle="modal" data-target="#mdl_addPR"><i class="fa fa-copy" id="mdlAddPR"></i></button>&nbsp;';
						
	                    echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>&nbsp;');
	                ?>
				</div>
				<div id="loading_1" class="overlay" style="display:none">
		            <i class="fa fa-refresh fa-spin"></i>
		        </div>
				<div class="row" id="RESORDERDESC" style="display: none;">
	                <div class="col-sm-12">
	                    <div class="alert alert-danger alert-dismissible">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
	                        Proses ini akan melakukan:<br>
	                        1. Mengatur ulang susunan struktur hierarki pekerjaan dalam daftar pekerjaan<br> 
	                        2. Namun, tidak menghitung ulang jumlah total RAP, harga rata-rata dari masing-masing tingkatan / level.<br>
	                        <button class="btn btn-info" onClick="resOrder()"></i>Lanjutkan</button>
	                    </div>
	                </div>
	            </div>
				<div class="row" id="SYNCJLDDESC" style="display: none;">
	                <div class="col-sm-12">
	                    <div class="alert alert-danger alert-dismissible">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
	                        Proses ini akan melakukan:<br>
	                        1. Mereset data SPP, OP, LPM, SPK, Opname, Penggunaan Item per komponen<br> 
	                        2. Melacak di seluruh jurnal yang memiliki kode pekerjaan dan item untuk dihitung ulang, kemudian diupdate ke daftar pekerjaan.<br>
	                        <button class="btn btn-info" onClick="syncJLD()"></i>Lanjutkan</button>
	                    </div>
	                </div>
	            </div>
				<div class="row" id="SYNCLRDESC" style="display: none;">
	                <div class="col-sm-12">
	                    <div class="alert alert-danger alert-dismissible">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
	                        Proses ini akan melakukan:<br>
	                        Melacak seluruh jurnal penggunaan item (UM, Opname, VCash, VLK, Penyelesaian PD, kemudian diupdate ke tabel profit and loss.<br>
	                        <button class="btn btn-info" onClick="syncLR()"></i>Lanjutkan</button>
	                    </div>
	                </div>
	            </div>
				<div class="row" id="SYNCJLDTRXDESC" style="display: none;">
	                <div class="col-sm-12">
	                    <div class="alert alert-danger alert-dismissible">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
	                        Proses ini akan melakukan:<br>
	                        1. Mengkalkulasi semua data SPP, OP, LPM, SPK, Opname, Voucher Cash dan Voucher Luar Kota.<br> 
	                        2. Melacak di seluruh jurnal yang memiliki kode pekerjaan dan item untuk dihitung ulang, kemudian diupdate ke daftar pekerjaan.<br>
	                        <button class="btn btn-info" onClick="SyncJLDTRX()"></i>Lanjutkan</button>
	                    </div>
	                </div>
	            </div>
				<div class="row" id="SYNCDASHTRXDESC" style="display: none;">
	                <div class="col-sm-12">
	                    <div class="alert alert-danger alert-dismissible">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h4><i class="icon fa fa-warning"></i> PERHATIAN</h4>
	                        Proses ini akan melakukan:<br>
	                        1. Mengkalkulasi semua data SPP, OP, LPM, SPK, Opname, Voucher Cash dan Voucher Luar Kota TANPA me-reset ulang.<br> 
	                        2. Hasil akumulasi pada point pertama (semua dokumen berstatus Confirm, Approce dan Close) akan disimpan ke dalam tabel dashboard.<br>
	                        <button class="btn btn-info" onClick="SyncDASHTRX()"></i>Lanjutkan</button>
	                    </div>
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

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    max-width: 900px;   
					}

					.datatable td span{
					    width: 80%;
					    display: block;
					    overflow: hidden;
					}

					.modal {
						display: 'flex';
						justify-content: 'center';
						align-items: 'center';
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addPR" name='mdl_addPR' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
							            <div class="box-header">
							              	<ul class="nav nav-tabs">
							                    <li id="li1" <?php echo $Active1Cls; ?>>
							                    	<a href="#" data-toggle="tab"><?=$copyBudg?></a>
							                    </li>
							                </ul>
							            </div>
							            <div class="box-body">
                                        	<div class="col-md-6">
												<div class="box box-warning">
													<div class="box-header with-border">
														<i class="glyphicon glyphicon-play-circle"></i>
														<h3 class="box-title"><?=$budgSrc?></h3>
													</div>
													<div class="box-body">
														<div class="col-md-12">
															<ul class="list-group list-group-unbordered">
																<li class="list-group-item">
																	<b><?php echo "$PRJCODE - $PRJNAME"; ?></b>
																</li>
																<li class="list-group-item">
																	<b><?=$Budget?></b> <a class="pull-right"><?php echo number_format($PRJCOST, 2); ?></a>
																</li>
															</ul>
														</div>
														<div class="col-md-6">
															<div class="box-body box-profile">
																<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:140px; width:150px" alt="User profile picture">
															</div>
														</div>
														<div class="col-md-6">
															<ul class="list-group list-group-unbordered">
																<li class="list-group-item">
																	<b><?=$JobNm?></b> <a class="pull-right"><?php echo number_format($rJL, 0); ?></a>
																</li>
																<li class="list-group-item">
																	<b>Item</b> <a class="pull-right"><?php echo number_format($rITM, 0); ?></a>
																</li>
															</ul>
							                                <button class="btn btn-warning btn-block" type="button" id="btnDetail1" name="btnDetail1" <?php if($rJL == 0) { ?> disabled <?php } ?>>
	                                                    		<b><?=$copyBudg?></b>
	                                                    	</button>
														</div>
														<div class="col-md-12">
		                                                    <?php if($rJL == 0) { ?>
								                                <div class="alert alert-warning alert-dismissible">
													                Tidak ada data yang dapat disalin dari RAP ini.
												              	</div>
		                                                    <?php } ?>
														</div>
													</div>
												</div>
                                      		</div>

                                        	<div class="col-md-6">
												<div class="box box-success">
													<div class="box-header with-border">
														<i class="glyphicon glyphicon-download"></i>
														<h3 class="box-title"><?=$budgDest?></h3>
													</div>
													<div class="box-body">
														<div class="alert alert-info alert-dismissible">
											                <!-- <h4><i class="icon fa fa-warning"></i> Perhatian</h4> -->
											                Proses penyalinan data RAP memerlukan beberapa menit, silahkan menunggu.
										              	</div>
														<div class="col-md-12">
															<ul class="list-group list-group-unbordered">
																<li class="list-group-item">
																	<select name="PRJCODE_DEST" id="PRJCODE_DEST" class="form-control select2" style="width: 100%">
											                          <option value=""> --- </option>
												                        <?php
												                            $sql 	= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJLEV = 2 
												                            			AND PRJCODE != '$PRJCODE'";
																			$result = $this->db->query($sql)->result();
																			foreach($result as $row) :
											                                    $PRJCODE1 	= $row->PRJCODE;
											                                    $PRJNAME1 	= $row->PRJNAME;
											                                    ?>
											                                		<option value="<?php echo $PRJCODE1; ?>">
											                                			<?php echo "$PRJCODE1 - $PRJNAME1"; ?>
											                                		</option>
											                                	<?php
											                                endforeach;
											                            ?>
											                        </select>
																</li>
																<li class="list-group-item" id="perProgInf">
																	<b><?php echo "$copyProg - $Budget"; ?> (%)</b>
																</li>
																<div class="cssProgress" id="idprogbarMDL">
															      	<div class="cssProgress">
																	    <div class="progress3">
																			<div id="progressbarXXMDL" style="text-align: center;">0%</div>
																		</div>
																		<span class="cssProgress-label" id="information" ></span>
																	</div>
															    </div>
															    <div class="alert alert-success alert-dismissible" id="SuccessInf" style="display: none;">
													                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													                <h4><i class="icon fa fa-check"></i><?=$Success?></h4> Proses Penyalinan Selesai.
												              	</div>
															</ul>
														</div>
													</div>
												</div>
                                      		</div>
                                      		<div id="loading_1" class="overlay" <?php if($isLoadDone_1 == 1) { ?> style="display:none" <?php } ?>>
									            <i class="fa fa-refresh fa-spin"></i>
									        </div>
		                                </div>
		                            </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<?php
					$securlImp 	= base_url().'index.php/__l1y/impCOA/?id=';
					$impLink 	= "$securlImp~$PRJCODE";
				?>
				<script type="text/javascript">
					$(document).ready(function()
					{
						$("#idRefresh1").click(function()
						{
							$('#example1').DataTable().ajax.reload();
						});

					   	$("#btnDetail1").click(function()
					    {
							PRJCODE 	= "<?php echo $PRJCODE; ?>";
						    swal({
					            title: "<?php echo $alert1; ?>",
					            text: "<?php echo $alert2 ?>",
					            icon: "warning",
					            buttons: ["Tidak", "Ya"],
							})
					        .then((willDelete) => 
					        {
					            if (willDelete) 
					            {
									//document.getElementById('idprogbar').style.display = '';
									PRJCODE_D 	= document.getElementById('PRJCODE_DEST').value;
									var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
									if(PRJCODE_D == '')
									{
										swal('<?php echo $alert5; ?>',
										{
											icon: "warning",
										});
	                                    return false;
									}
									else
									{
									    document.getElementById("progressbarXXMDL").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
										//document.getElementById('loading_1').style.display = '';
										document.getElementById('btnDetail1').disabled 		= true;
										$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
										$("#myiFrame")[0].contentWindow.IMP_CODEX.
										value 	= PRJCODE_D;
										$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'COPY_BUDGET';
										butSubm.submit();
									}
					            }
	        				});
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

	    	<!-- ============ START MODAL : ADD JOB =============== -->
		        <div class="modal fade" id="mdl_addJH" name='mdl_addJH' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
						            <div class="box-body">
										<div class="box box-warning">
											<div class="box-header with-border">
												<i class="glyphicon glyphicon-play-circle"></i>
												<h3 class="box-title"><?=$addJob?></h3>
											</div>
											<div class="box-body">
												<form class="form-horizontal" name="frmAddJH" id="frmAddJH" method="post" action="" enctype="multipart/form-data" onSubmit="return submitForm()">
							                        <input type="hidden" name="selROW" id="selROW" value="" />
							                        <input type="hidden" name="pgFrom" id="pgFrom" value="<?php echo $pgFrom; ?>" />
							                        <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
							                        <input type="Hidden" name="rowCount" id="rowCount" value="0">
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobParent; ?></label>
							                            <div class="col-sm-10">
							                            	<input type="hidden" class="form-control" name="JOBPARENT" id="JOBPARENT" value="" />
							                              	<select name="JOBPARENTX" id="JOBPARENTX" class="form-control select2" style="width: 100%" disabled>
							                                    <option value="0"> --- </option>
							                                    <?php
							                                        $s_BOQ    = "SELECT JOBCODEID, JOBDESC FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
							                                        $r_BOQ    = $this->db->query($s_BOQ)->result();
							                                        foreach($r_BOQ as $rw_BOQ) :
							                                            $JOBID      = $rw_BOQ->JOBCODEID;
							                                            $JOBDESC    = $rw_BOQ->JOBDESC;
							                                            ?>
							                                            <option value="<?php echo $JOBID; ?>"><?php echo "$JOBID : $JOBDESC"; ?></option>
							                                            <?php
							                                        endforeach;
							                                    ?>
							                                </select>
							                            </div>
							                        </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label"><?php echo $JobCode; ?></label>
							                            <div class="col-sm-5">
							                                <input type="text" class="form-control" style="text-align:left" name="JOBCODEID" id="JOBCODEID" value="" />
							                            </div>
							                            <label for="inputName" class="col-sm-2 control-label" title="Induk RAP"><?php echo $LastLevel; ?></label>
							                            <div class="col-sm-3">
							                                <select name="ISLASTH" id="ISLASTH" class="form-control select2" style="width: 100%">
							                                    <option value=""> --- </option>
							                                    <option value="1"> Ya </option>
							                                    <option value="0"> Bukan </option>
							                                </select>
							                            </div>
							                        </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label" style="vertical-align:top"><?php echo $JobName; ?></label>
							                            <div class="col-sm-5">
							                                <input type="text" class="form-control" style="text-align:left" name="JOBDESC" id="JOBDESC" value="" />
							                            </div>
							                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Unit; ?></label>
							                            <div class="col-sm-3">
							                                <select name="JOBUNIT" id="JOBUNIT" class="form-control select2" style="width: 100%">
							                                    <option value="0"> --- </option>
							                                    <?php
							                                        $sqlUnit 	= "SELECT * FROM tbl_unittype";
							                                        $resUnit 	= $this->db->query($sqlUnit)->result();
							                                        foreach($resUnit as $rowUM) :
							                                            $Unit_Type_Code = $rowUM->Unit_Type_Code;
							                                            $UMCODE 		= $rowUM->UMCODE;
							                                            $Unit_Type_Name	= $rowUM->Unit_Type_Name;
							                                            ?>
							                                            <option value="<?php echo $Unit_Type_Code; ?>"><?php echo $Unit_Type_Name; ?></option>
							                                            <?php
							                                        endforeach;
							                                    ?>
							                                </select>
							                            </div>
							                        </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">BoQ</label>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label">Volume</label>
							                            </div>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label"><?=$Price?></label>
							                            </div>
							                       	</div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="BOQ_VOLM1" id="BOQ_VOLM1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolmBQ(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="BOQ_VOLM" id="BOQ_VOLM" value="0.00" />
							                            </div>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="BOQ_PRICE1" id="BOQ_PRICE1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrcBQ(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="BOQ_PRICE" id="BOQ_PRICE" value="0.00" />
							                            </div>
							                       </div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">RAP</label>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label">Volume</label>
							                            </div>
							                            <div class="col-sm-3">
							                                <label for="inputName" class="control-label"><?=$Price?></label>
							                            </div>  
							                       	</div>
							                        <div class="form-group">
							                            <label for="inputName" class="col-sm-2 control-label">&nbsp;</label>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_VOLM1" id="ITM_VOLM1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVolmRAP(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_VOLM" id="ITM_VOLM" value="0.00" />
							                            </div>
							                            <div class="col-sm-3">
							                                <input type="text" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE1" id="ITM_PRICE1" value="0.00" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrcRAP(this);" />
							                                <input type="hidden" class="form-control" style="max-width:150px;text-align:right" name="ITM_PRICE" id="ITM_PRICE" value="0.00" />
							                            </div>
							                       	</div>
							                        <div class="form-group">
							                            <div class="col-sm-offset-2 col-sm-10">
			                                            	<button type="button" class="btn btn-primary" onClick="proc_AddJH()">
			                                            		<i class="glyphicon glyphicon-ok"></i>
			                                            	</button>
			                              					<button type="button" id="idClose2" class="btn btn-danger" data-dismiss="modal">
			                                            		<i class="glyphicon glyphicon-remove"></i>
			                                            	</button>
							                            </div>
							                        </div>
							                    </form>
											</div>
										</div>
	                                </div>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$("#idClose2").click(function(){
			            $("#mdl_addJH").modal('hide');
			        });

					function proc_AddJH(row)
					{
						var JOBPARENT   = document.getElementById('JOBPARENT').value;
				        var JOBCODEID   = document.getElementById('JOBCODEID').value;
				        var ISLASTH     = document.getElementById('ISLASTH').value;
				        var JOBDESC     = document.getElementById('JOBDESC').value;
				        var JOBUNIT     = document.getElementById('JOBUNIT').value;

				        if(JOBPARENT == 0)
				        {
				            swal('<?php echo $alert6; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBPARENT').focus();
				            });
				            return false;
				        }

				        if(JOBCODEID == '')
				        {
				            swal('<?php echo $alert7; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBCODEID').focus();
				            });
				            return false;
				        }

				        if(ISLASTH == '')
				        {
				            swal('<?php echo $alert10; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('ISLASTH').focus();
				            });
				            return false;
				        }

				        if(JOBDESC == '')
				        {
				            swal('<?php echo $alert8; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBDESC').focus();
				            });
				            return false;
				        }

				        if(JOBUNIT == 0)
				        {
				            swal('<?php echo $alert9; ?>',
				            {
				                icon:"warning"
				            })
				            .then(function()
				            {
				                document.getElementById('JOBUNIT').focus();
				            });
				            return false;
				        }

						var frmData = 	{
											PRJCODE		: $("#PRJCODE").val(),
											JOBPARENT	: $("#JOBPARENT").val(),
											JOBCODEID	: $("#JOBCODEID").val(),
											ISLASTH		: $("#ISLASTH").val(),
											JOBDESC		: $("#JOBDESC").val(),
											JOBUNIT		: $("#JOBUNIT").val(),
											BOQ_VOLM	: $("#BOQ_VOLM").val(),
											BOQ_PRICE	: $("#BOQ_PRICE").val(),
											ITM_VOLM	: $("#ITM_VOLM").val(),
											ITM_PRICE	: $("#ITM_PRICE").val(),
										};

						$.ajax({
							type 	: 'POST',
							url 	: "<?php echo site_url('c_comprof/c_bUd93tL15t/svJHD')?>",
							data 	: frmData,
							success : function(response)
							{
								$('#frmAddJH').trigger("reset");
								document.getElementById("idClose2").click();
								//swal(response)
								$('#example').DataTable().ajax.reload();
							}
						});
				    }
				</script>
	    	<!-- ============ END MODAL : ADD JOB =============== -->

	    	<!-- ============ START MODAL LOCK RAPT/RAPP =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    max-width: 900px;   
					}

					.datatable td span{
					    width: 80%;
					    display: block;
					    overflow: hidden;
					}

					.modal {
						display: 'flex';
						justify-content: 'center';
						align-items: 'center';
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>

		        <div class="modal fade" id="mdl_LOCKRAPT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		        	<div class="modal-dialog" role="document">
					    <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
									<form class="form-horizontal" name="frm" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
								    	<div class="col-md-12">
								            <div class="box-header">
								              	<ul class="nav nav-tabs">
								                    <li id="li1" <?php echo $Active1Cls; ?>>
								                    	<a href="#" data-toggle="tab"><?=$lockRAP?></a>
								                    </li>
								                </ul>
								            </div>
								            <div class="box-body">
	                                        	<div class="col-md-6">
													<div class="box box-warning">
														<div class="box-header with-border">
															<i class="glyphicon glyphicon-home"></i>
															<h3 class="box-title"><?=$ProjectInformation?></h3>
														</div>
														<div class="box-body">
															<div class="col-md-12">
																<ul class="list-group list-group-unbordered">
																	<li class="list-group-item">
																		<b><?php echo "$PRJCODE - $PRJNAME"; ?></b>
																	</li>
																	<li class="list-group-item" <?php if($PRJADD == '') { ?> style="display: none;"<?php } ?>>
																		<span class="description"><?=$PRJADD?></span>
																	</li>
																</ul>
															</div>
															<div class="col-md-6">
																<div class="box-body box-profile">
																	<img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" style="height:140px; width:150px" alt="User profile picture">
																</div>
															</div>
															<div class="col-md-6">
																<ul class="list-group list-group-unbordered">
																	<li class="list-group-item">
																		<b>RAB</b> <a class="pull-right"><?=number_format($TOT_BOQP)?></a>
																	</li>
																	<li class="list-group-item">
																		<b>RAPT</b> <a class="pull-right"><?=number_format($PRJRAPT)?></a>
																	</li>
																	<li class="list-group-item">
																		<b>RAPP</b> <a class="pull-right"><?=number_format($TOT_RAPP)?></a>
																	</li>
																	<li class="list-group-item">
																		<b>DEV.</b>
																		<a class="pull-right"><?=$DEVDESC_3?></a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
	                                      		</div>

	                                        	<div class="col-md-6">
													<div class="box box-success">
														<div class="box-header with-border" style="display: none;">
															<i class="glyphicon glyphicon-info-sign"></i>
															<h3 class="box-title"><?=$lockInfo?></h3>
														</div>
														<div class="box-body">
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName">No. SK / Tgl. Dokumen</label>
											                    </div>
											                    <div class="col-sm-12">
											                    	<input type="text" class="form-control" style="text-align:left" name="LOCK_DOCREF1" id="LOCK_DOCREF1" value="" placeholder="No. SK Penguncian" />
											                    </div>
											                    <div class="col-sm-5" style="display: none;">
											                    	<div class="input-group date">
									                            		<div class="input-group-addon">
									                            		<i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="LOCK_DOCDATE" class="form-control pull-left" id="datepicker1" value="<?php echo $LOCK_DOCDATE; ?>" style="width:100%">
									                            	</div>
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<input type="file" name="userfile1" id="userfile1" class="form-control" />
											                    </div>
											                </div>
						                                    <?php
						                                    	$LOCK_EMP1 	= "";
						                                        $sqlEmp1	= "SELECT CONCAT(First_Name,' ', Last_Name) AS complName FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
						                                        $sqlEmp1	= $this->db->query($sqlEmp1)->result();
						                                        foreach($sqlEmp1 as $row1) :
						                                            $LOCK_EMP1	= $row1->complName;
						                                        endforeach;
						                                    ?>
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName">Penanggung Jawab</label>
											                    </div>
											                    <div class="col-sm-12">
											                    	<input type="text" class="form-control" style="text-align:left" name="LOCK_EMP1" id="LOCK_EMP1" value="<?=$LOCK_EMP1?>" placeholder="Pengunci" readonly />
											                    </div>
											                </div>
															<div class="form-group">
											                    <div class="col-sm-12">
											                    	<label for="inputName">Catatan</label>
											                    </div>
											                    <div class="col-sm-12">
											                    	<input type="text" class="form-control" style="text-align:left" name="LOCK_DESC1" id="LOCK_DESC1" value="" placeholder="Catatan Penguncian RAP" />
											                    </div>
											                </div>
								                    		<?php
								                    			if($ISDELETE == 1)
								                    			{
									                    			?>
																		<div class="form-group">
														                    <div class="col-sm-12">
															                	<button class="btn btn-warning btn-block" type="button" id="btnLockRAPT" name="btnLockRAPT" onclick="procLockRAPT()">
						                                                    		<b><i class="fa fa-lock"></i>&nbsp;&nbsp;<?=$lockRAPT?></b>
						                                                    	</button>
						                                                    	<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal" style="display: none;">
						                                                    		<i class="glyphicon glyphicon-remove"></i>
						                                                    	</button>
						                                                    </div>
						                                                </div>
								                    				<?php
								                    			}
								                    		?>
														</div>
														<div class="overlay" style="display: none;">
															<i class="fa fa-refresh fa-spin"></i>
														</div>
													</div>
	                                      		</div>
					                    		<?php
					                    			if($ISDELETE == 0)
					                    			{
						                    			?>
										                    <div class="col-sm-12">
											                	<a class="btn btn-block btn-social btn-google" style="text-align: center;">
													                <i class="glyphicon glyphicon-remove"></i> <?=$canAppDesc?>
													            </a>
		                                                    </div>
					                    				<?php
					                    			}

					                    		?>
			                                </div>
			                            </div>
			                        </form>
		                        </div>
			                </div>
				        </div>
				    </div>
				</div>

				<?php
					$securlImp 	= base_url().'index.php/__l1y/impCOA/?id=';
					$impLink 	= "$securlImp~$PRJCODE";
				?>
				<script type="text/javascript">
					async function procLockRAPT()
					{
						let PRJCODE 	= "<?php echo $PRJCODE; ?>";
						let DOC_REF		= document.querySelector('#LOCK_DOCREF1');
						let DOC_DATE	= document.querySelector('#datepicker1');
						let DOC_FILE	= document.querySelectorAll('#userfile1')[0];
						let DOC_EMP1	= document.querySelector('#LOCK_EMP1');
						let DOC_DESC	= document.querySelector('#LOCK_DESC1');

						if (DOC_FILE.value!="") 
						{
							let form_data 	= new FormData();                  
							form_data.append('PRJCODE', PRJCODE);
							form_data.append('DOC_REF', DOC_REF.value);
							form_data.append('DOC_DATE', DOC_DATE.value);
							form_data.append('file', DOC_FILE.files[0]);
							form_data.append('DOC_EMP1', DOC_EMP1.value);
							form_data.append('DOC_DESC', DOC_DESC.value);
							
							fetch("<?php echo site_url('__l1y/lockRAPT')?>", {
								method: "POST",
								body: form_data
							}).then(function(response) {
								// document.querySelector('.overlay').style.display = 'block';
								return response.json();
							}).then(function(result) {
								console.log(result);
								const inputArray 	= document.querySelectorAll('input');
								inputArray.forEach(function (input) {
									input.value = "";
								});
								// document.querySelector('.overlay').style.display = 'none';
								swal({
									title: "Berhasil!",
									text: "Untuk perubahan budget di RAP, hanya dapat dilakukan melalui Amandemen / BA",
									icon: "success",
								})
								.then(function()
								{
									swal.close();
									document.getElementById('idClose1').click();
									document.getElementById('divUnLock').style.display 	= 'none';
									document.getElementById('divLock').style.display 	= '';
									$('#example').DataTable().ajax.reload();
								})
							}).catch((error) => {
								console.error('Error:', error);
							});
						}
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
					    	PRJCODE 	= "<?php echo $PRJCODE; ?>";
					    	DOC_REF		= document.getElementById('LOCK_DOCREF').value;
					    	DOC_DATE	= document.getElementById('datepicker').value;
					    	DOC_DESC	= document.getElementById('LOCK_DESC').value;
					    	DOC_FILE	= document.getElementById('userfile').value;
					    	DOC_EMP1	= document.getElementById('LOCK_EMP1').value;
					    	DOC_EMP2	= document.getElementById('LOCK_EMP1').value;
					    	
					    	if(DOC_REF == '')
					    	{
					    		swal('No. SK Direksi tidak boleh kosong',
					    		{
					    			icon:"warning"
					    		})
					    		.then(function ()
					    		{
					    			swal.close();
					    			document.getElementById('LOCK_DOCREF').focus();
					    		})
					    		return false;
					    	}
					    	
					    	if(DOC_EMP1 == '')
					    	{
					    		swal('Anda belum memilih 1 pun Penanggung Jawab untuk mengunci RAP ini.',
					    		{
					    			icon:"warning"
					    		});
					    		return false;
					    	}

					    	swal({
					            title: "<?php echo $alert1; ?>",
					            text: "<?php echo $alert2 ?>",
					            icon: "warning",
					            buttons: ["Tidak", "Ya"],
							})
					        .then((willDelete) => 
					        {
					            if (willDelete) 
					            {
							    	var frmdata = { PRJCODE 	: PRJCODE,
							    					DOC_REF		: DOC_REF,
							    					DOC_DATE	: DOC_DATE,
							    					DOC_DESC	: DOC_DESC,
							    					DOC_FILE	: DOC_FILE,
							    					DOC_EMP1	: DOC_EMP1,
							    					DOC_EMP2	: DOC_EMP2,
							    				}
		                            $.ajax({
		                                type: 'POST',
		                                url: "<?php echo site_url('__l1y/lockRAP')?>",
		                                data: frmdata,
		                                success: function(isRespon)
		                                {
		                                	swal({
											  	title: "Berhasil!",
											  	text: "Untuk perubahan budget di RAP, hanya dapat dilakukan melalui Amandemen / BA",
											  	icon: "success",
											})
											.then(function()
											{
												swal.close();
												document.getElementById('idClose1').click();
												document.getElementById('divUnLock').style.display 	= 'none';
												document.getElementById('divLock').style.display 	= '';
		                                		$('#example').DataTable().ajax.reload();
											})
		                                }
		                            });
							    };
							});
						});

					   	// $("#btnLockRAPT").click(function()
					    // {
						// 	const PRJCODE 	= "<?php echo $PRJCODE; ?>";
					    // 	const DOC_REF	= $('#LOCK_DOCREF1');
					    // 	const DOC_DATE	= $('#datepicker1');
					    // 	const DOC_FILE	= $('#userfile1')[0];
					    // 	const DOC_EMP1	= $('#LOCK_EMP1');
					    // 	const DOC_DESC	= $('#LOCK_DESC1');

						// 	var form_data 	= new FormData();                  
						// 	form_data.append('file', file_data);
						// 	// console.log(form_data);
						// 	// return false;
					    	
					    // 	if(DOC_REF == '')
					    // 	{
					    // 		/*swal('No. dokumen referensi tidak boleh kosong',
					    // 		{
					    // 			icon:"warning"
					    // 		})
					    // 		.then(function ()
					    // 		{
					    // 			swal.close();
					    // 			document.getElementById('LOCK_DOCREF1').focus();
					    // 		})
					    // 		return false;*/
					    // 	}
					    	
					    // 	if(DOC_FILE == '')
					    // 	{
					    // 		/*swal('Dokumen upload tidak boleh kosong',
					    // 		{
					    // 			icon:"warning"
					    // 		})
					    // 		.then(function ()
					    // 		{
					    // 			swal.close();
					    // 			document.getElementById('userfile1').focus();
					    // 		})
					    // 		return false;*/
					    // 	}
					    	
					    // 	if(DOC_EMP1 == '')
					    // 	{
					    // 		/*swal('Anda belum memilih 1 pun Penanggung Jawab untuk mengunci RAP ini.',
					    // 		{
					    // 			icon:"warning"
					    // 		});
					    // 		return false;*/
					    // 	}
				  
				        //     if (DOC_FILE!="") {
				        //         // let formData = new FormData();
				        //         // formData.append('DOC_REF', DOC_REF);
				        //         // formData.append('DOC_FILE', DOC_FILE);
				        //         // formData.append('DOC_DATE', DOC_DATE);
				        //         // formData.append('DOC_EMP1', DOC_EMP1);
				        //         // formData.append('DOC_DESC', DOC_DESC);
				        //         // formData.append('PRJCODE', PRJCODE);
				  
				        //         $.ajax({
				        //             type: 'POST',
				        //             url: "<?php echo site_url('__l1y/lockRAPT')?>",
				        //             data: form_data,
						// 			error: function (xhr, error, status) {
				        //                 alert(xhr);
				        //             },
				        //             // cache: false,
				        //             processData: false,
				        //             contentType: false,
				        //             success: function (data) {
				        //                 console.log(data[0]);
				        //                 //document.getElementById("form-data").reset();
				        //             },
				        //         });
						// 		return false;
				        //     }
						// });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
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

    $(document).ready(function() {
	    $('#example').DataTable( {
	        "processing": true,
	        "serverSide": true,
	        //"scrollX": false,
	        "autoWidth": true,
	        "ordering": false,
	        "filter": true,
	        "ajax": "<?php echo site_url('c_comprof/c_bUd93tL15t/get_AllDataJL/?id='.$PRJCODE)?>",
	        "type": "POST",
	        "lengthMenu": [[100, 200, -1], [100, 200, "All"]],
	        //"lengthMenu": [[50, 100, 200], [50, 100, 200]],
	        "columnDefs": [ { targets: [5], className: 'dt-body-center' }
	                      ],
	        "order": [[ 2, "desc" ]],
	        "language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
	        } );
    });
	
	function ShwResOrder()
	{
		document.getElementById('RESORDERDESC').style.display 		= '';
		document.getElementById('SYNCLRDESC').style.display 		= 'none';
		document.getElementById('SYNCJLDDESC').style.display 		= 'none';
		document.getElementById('SYNCJLDTRXDESC').style.display 	= 'none';
		document.getElementById('SYNCDASHTRXDESC').style.display 	= 'none';
	}
	
	function ShwSyncJLD()
	{
		document.getElementById('SYNCJLDDESC').style.display 		= '';
		document.getElementById('SYNCLRDESC').style.display 		= 'none';
		document.getElementById('RESORDERDESC').style.display 		= 'none';
		document.getElementById('SYNCJLDTRXDESC').style.display 	= 'none';
		document.getElementById('SYNCDASHTRXDESC').style.display 	= 'none';
	}
	
	function ShwSyncLR()
	{
		document.getElementById('SYNCJLDDESC').style.display 		= 'none';
		document.getElementById('SYNCLRDESC').style.display 		= '';
		document.getElementById('RESORDERDESC').style.display 		= 'none';
		document.getElementById('SYNCJLDTRXDESC').style.display 	= 'none';
		document.getElementById('SYNCDASHTRXDESC').style.display 	= 'none';
	}

	function ShwSyncJLDTRX()
	{
		document.getElementById('SYNCJLDDESC').style.display 		= 'none';
		document.getElementById('SYNCLRDESC').style.display 		= 'none';
		document.getElementById('RESORDERDESC').style.display 		= 'none';
		document.getElementById('SYNCJLDTRXDESC').style.display 	= '';
		document.getElementById('SYNCDASHTRXDESC').style.display 	= 'none';
	}
	
	function ShwSyncDASHTRX()
	{
		document.getElementById('SYNCJLDDESC').style.display 		= 'none';
		document.getElementById('SYNCLRDESC').style.display 		= 'none';
		document.getElementById('RESORDERDESC').style.display 		= 'none';
		document.getElementById('SYNCJLDTRXDESC').style.display 	= 'none';
		document.getElementById('SYNCDASHTRXDESC').style.display 	= '';
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
				document.getElementById('RESORDERDESC').style.display 	= 'none';

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('idprogbarXY').style.display = '';
			    document.getElementById("progressbarXY").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
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
				document.getElementById('SYNCJLDDESC').style.display 	= 'none';

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('idprogbarXY').style.display = '';
			    document.getElementById("progressbarXY").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';

            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				/*$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETWBD';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETWBD';*/
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
            else 
            {
                /*swal("<?php echo $canReset; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}
	
	function syncLR()
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
				document.getElementById('SYNCLRDESC').style.display 	= 'none';

				document.getElementById('idprogbar').style.display 		= '';
			    document.getElementById("progressbarXX").innerHTML 		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('idprogbarXY').style.display 	= '';
			    document.getElementById("progressbarXY").innerHTML 		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display 		= '';

            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				/*$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETWBD';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETWBD';*/
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RECOUNTLR';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RECOUNTLR';
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
	
	function SyncJLDTRX()
	{
		document.getElementById('SYNCJLDTRXDESC').style.display 	= 'none';

		document.getElementById('idprogbar').style.display 			= '';
	    document.getElementById("progressbarXX").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 		= '';
	    document.getElementById("progressbarXY").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('loading_1').style.display 			= '';

    	var PRJCODE	= "<?php echo $PRJCODE; ?>";
		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'SYNCTRXJLD';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'SYNCTRXJLD';
		butSubm.submit();
	}
	
	function SyncDASHTRX()
	{
		document.getElementById('SYNCDASHTRXDESC').style.display 	= 'none';

		document.getElementById('idprogbar').style.display 			= '';
	    document.getElementById("progressbarXX").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('idprogbarXY').style.display 		= '';
	    document.getElementById("progressbarXY").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
		document.getElementById('loading_1').style.display 			= '';

    	var PRJCODE	= "<?php echo $PRJCODE; ?>";
		var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
		$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
		$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'SYNCDASHTRX';
		$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'SYNCDASHTRX';
		butSubm.submit();
	}
	
	function delJOB(collDtItm)
	{
		collDtItm 	= collDtItm.split('~');
		urlDel 		= collDtItm[0];
		PRJCODE 	= collDtItm[1];
		JOBCODEID 	= collDtItm[2];
		JOBDESC 	= collDtItm[3];
		console.log(collDtItm)
	    swal({
            text: "Komponen "+JOBCODEID+" ("+JOBDESC+") tidak terdapat di Analisa BoQ, sebaiknya dihapus. Hapus data ini?",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
            	var frmData = 	{
									PRJCODE		: PRJCODE,
									JOBCODEID	: JOBCODEID,
									JOBDESC		: JOBDESC
								};

				$.ajax({
					type 	: 'POST',
					url 	: "<?php echo site_url('c_comprof/c_bUd93tL15t/delJOBItm')?>",
					data 	: frmData,
					success : function(response)
					{
						swal(response)
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
	
	function syncJob(row)
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
            	var JOBID 	= document.getElementById('JOBID'+row).value;

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETJLH';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETJLH';
				$("#myiFrame")[0].contentWindow.DESCRIPT.value 		= JOBID;
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
	
	function syncJobRAP(row)
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
            	var JOBID 	= document.getElementById('JOBID'+row).value;

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETRAPH';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETRAPH';
				$("#myiFrame")[0].contentWindow.DESCRIPT.value 		= JOBID;
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
	
	function syncJobRAPT(row)
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
            	var JOBID 	= document.getElementById('JOBID'+row).value;

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETRAPT';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETRAPT';
				$("#myiFrame")[0].contentWindow.DESCRIPT.value 		= JOBID;
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
	
	function syncJobRAB(row)
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
            	var JOBID 	= document.getElementById('JOBID'+row).value;

				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 		= PRJCODE;
				$("#myiFrame")[0].contentWindow.IMP_CODEX.value 	= 'RESETRABH';
				$("#myiFrame")[0].contentWindow.IMP_TYPE.value 		= 'RESETRABH';
				$("#myiFrame")[0].contentWindow.DESCRIPT.value 		= JOBID;
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
	
	function showDetC(LinkD)
	{
		w = 700;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
	}
	
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
      	}, 2000);
	}

	function clsBar()
	{
		document.getElementById('idprogbar').style.display 		= 'none';
		document.getElementById('idprogbarXY').style.display 	= 'none';
	}

	function clsBarX()
	{
		$('#example').DataTable().ajax.reload();
	}

	function updStatMDL()
	{
		var timer = setInterval(function()
		{
	       	clsBarMDL();
      	}, 2000);
	}

	function clsBarMDL()
	{
		document.getElementById('perProgInf').style.display 	= 'none';
		document.getElementById('idprogbarMDL').style.display 	= 'none';
		document.getElementById('SuccessInf').style.display 	= '';
		document.getElementById('btnDetail1').disabled 			= false;
	}

	function addHd(row)
	{
		document.getElementById("selROW").value	= row;
		collD 	= document.getElementById('urlAddJH'+row).value;
		arrVAR 	= collD.split("~");
		JPARENT = arrVAR[2];
		document.getElementById("JOBPARENT").value	= JPARENT;
		$('#JOBPARENTX').val(JPARENT).trigger('change');
		var PRJCODE		= document.getElementById("PRJCODE").value;
		var deptid		= JPARENT+'~'+PRJCODE;
		
		$.ajax({
			url: '<?php echo site_url('c_project/c_joblistdet/getCODEJOBLIST/?id=')?>',
			type: 'post',
			data: {depart:deptid},
			dataType: 'json',
			success:function(response)
			{
				document.getElementById("JOBCODEID").value	= response;
			}
		});
		document.getElementById('mdlAddJH').click();
		//$("#mdl_addJH").modal('show');
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