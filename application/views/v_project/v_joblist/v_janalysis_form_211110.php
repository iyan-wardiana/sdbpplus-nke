<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 3 Nopember 2021
	* File Name		= v_janalysis_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$currentRow = 0;
if($task == 'add')
{
	$y 		= date('y');
	$md 	= date('md');
	$s 		= date('His');
	$JAN_NUM 		= "JA.$y.$md.$s";
	$JAN_CODE 		= "";
	$JAN_NAME 		= "";
	$JAN_DESC 		= "";
	$JAN_TYPE 		= 2;
	$JAN_STAT 		= 0;

	$JOBCODEID 		= "";
	$JOBDESC 		= "";
	$MAN_NUM 		= "";
	$MAN_CODE 		= "";
	$MAN_DESC 		= "";
	$JOBCOST 		= 0;
	$JOBPARENT 		= "";
	$JOBDESCP 		= "";
}
else
{
	$JOBPARENT 	= "-";
	$JOBDESC 	= "-";
	$JOBUNIT 	= "-";
	$JOBVOLM 	= 0;
	$JOBCOST 	= 0;
	$BOQ_JOBCOST= 0;
	$sqlJlD		= "SELECT JOBPARENT, JOBDESC, JOBUNIT, JOBVOLM, JOBCOST, BOQ_JOBCOST
					FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
    $resJlD		= $this->db->query($sqlJlD)->result();
    foreach($resJlD as $rowJLD) :
	    $JOBPARENT 		= $rowJLD->JOBPARENT;
	    $JOBDESC 		= $rowJLD->JOBDESC;
	    $JOBUNIT 		= $rowJLD->JOBUNIT;
	    $JOBVOLM 		= $rowJLD->JOBVOLM;
	    $JOBCOST 		= $rowJLD->JOBCOST;
	    $BOQ_JOBCOST 	= $rowJLD->BOQ_JOBCOST;
	endforeach;

	$JOBDESCP	= "-";
	$sqlJlP		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
    $resJlP		= $this->db->query($sqlJlP)->result();
    foreach($resJlP as $rowJLP) :
	    $JOBDESCP 	= $rowJLD->JOBDESC;
	endforeach;


	$sqlmanD	= "SELECT MAN_CODE, MAN_NAME FROM tbl_manalysis_header WHERE MAN_NUM = '$MAN_NUM' LIMIT 1";
    $resmanD	= $this->db->query($sqlmanD)->result();
    foreach($resmanD as $rowJD) :
	    $MAN_CODE 	= $rowJD->MAN_CODE;
	    $MAN_NAME 	= $rowJD->MAN_NAME;
	endforeach;
	$MAN_DESC	= "$MAN_CODE : $MAN_NAME";
}

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

if(isset($_POST['MAN_NUMX']))
{
	$MAN_NUM 	= $_POST['MAN_NUMX'];
	$sqlmanD	= "SELECT MAN_CODE, MAN_NAME FROM tbl_manalysis_header WHERE MAN_NUM = '$MAN_NUM' LIMIT 1";
    $resmanD	= $this->db->query($sqlmanD)->result();
    foreach($resmanD as $rowJD) :
	    $MAN_CODE 	= $rowJD->MAN_CODE;
	    $MAN_NAME 	= $rowJD->MAN_NAME;
	endforeach;
	$MAN_DESC	= "$MAN_CODE : $MAN_NAME";

	$JOBCODEID 	= $_POST['JOBCODEIDX'];
	$JOBPARENT 	= "-";
	$JOBDESC 	= "-";
	$JOBUNIT 	= "-";
	$JOBVOLM 	= 0;
	$JOBCOST 	= 0;
	$BOQ_JOBCOST= 0;
	$sqlJlD		= "SELECT JOBPARENT, JOBDESC, JOBUNIT, JOBVOLM, JOBCOST, BOQ_JOBCOST
					FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
    $resJlD		= $this->db->query($sqlJlD)->result();
    foreach($resJlD as $rowJLD) :
	    $JOBPARENT 		= $rowJLD->JOBPARENT;
	    $JOBDESC 		= $rowJLD->JOBDESC;
	    $JOBUNIT 		= $rowJLD->JOBUNIT;
	    $JOBVOLM 		= $rowJLD->JOBVOLM;
	    $JOBCOST 		= $rowJLD->JOBCOST;
	    $BOQ_JOBCOST 	= $rowJLD->BOQ_JOBCOST;
	endforeach;

	$JOBDESCP	= "-";
	$sqlJlP		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
    $resJlP		= $this->db->query($sqlJlP)->result();
    foreach($resJlP as $rowJLP) :
	    $JOBDESCP 	= $rowJLD->JOBDESC;
	endforeach;
}

if(isset($_POST['JOBCODEIDX2']))
{
	$JOBCODEID 	= $_POST['JOBCODEIDX2'];
	$JOBPARENT 	= "-";
	$JOBDESC 	= "-";
	$JOBUNIT 	= "-";
	$JOBVOLM 	= 0;
	$JOBCOST 	= 0;
	$BOQ_JOBCOST= 0;
	$sqlJlD		= "SELECT JOBPARENT, JOBDESC, JOBUNIT, JOBVOLM, JOBCOST, BOQ_JOBCOST
					FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID' LIMIT 1";
    $resJlD		= $this->db->query($sqlJlD)->result();
    foreach($resJlD as $rowJLD) :
	    $JOBPARENT 		= $rowJLD->JOBPARENT;
	    $JOBDESC 		= $rowJLD->JOBDESC;
	    $JOBUNIT 		= $rowJLD->JOBUNIT;
	    $JOBVOLM 		= $rowJLD->JOBVOLM;
	    $JOBCOST 		= $rowJLD->JOBCOST;
	    $BOQ_JOBCOST 	= $rowJLD->BOQ_JOBCOST;
	endforeach;

	$JOBDESCP	= "-";
	$sqlJlP		= "SELECT JOBDESC FROM tbl_joblist WHERE JOBCODEID = '$JOBPARENT' LIMIT 1";
    $resJlP		= $this->db->query($sqlJlP)->result();
    foreach($resJlP as $rowJLP) :
	    $JOBDESCP 	= $rowJLD->JOBDESC;
	endforeach;

	$MAN_NUM 	= $_POST['MAN_NUMX2'];
	$sqlmanD	= "SELECT MAN_CODE, MAN_NAME FROM tbl_manalysis_header WHERE MAN_NUM = '$MAN_NUM' LIMIT 1";
    $resmanD	= $this->db->query($sqlmanD)->result();
    foreach($resmanD as $rowJD) :
	    $MAN_CODE 	= $rowJD->MAN_CODE;
	    $MAN_NAME 	= $rowJD->MAN_NAME;
	endforeach;
	$MAN_DESC	= "$MAN_CODE : $MAN_NAME";
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE	= $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'anlCode')$anlCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'anlNm')$anlNm = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'JobList')$JobList = $LangTransl;
			if($TranslCode == 'Name')$Name = $LangTransl;
			if($TranslCode == 'mstAnl')$mstAnl = $LangTransl;
			if($TranslCode == 'selAnl')$selAnl = $LangTransl;
			if($TranslCode == 'SelectJob')$SelectJob = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'JobCost')$JobCost = $LangTransl;
			if($TranslCode == 'JobDescription')$JobDescription = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'sureProcess')$sureProcess = $LangTransl;
			if($TranslCode == 'resDet')$resDet = $LangTransl;
			if($TranslCode == 'Increase')$Increase = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'JobNm')$JobNm = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'mstAnlNm')$mstAnlNm = $LangTransl;
			if($TranslCode == 'itmLisMAnl')$itmLisMAnl = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Kode analisa tidak boleh kosong";
			$alert2		= "Nama analisa tidak boleh kosong";
			$alert3		= "Silahkan pilih salah satu item";
			$alert4 	= "Total analisa lebih besar dari Nilai Pekerjaan";
			$applyAnl 	= "Terapkan analisis pekerjaan ini ke ...";
		}
		else
		{
			$alert1		= "Analysis code can not be empty";
			$alert2		= "Analysis name can not be empty";
			$alert3		= "Please select an item";
			$alert4 	= "Total analysis is greater than Job Cost";
			$applyAnl 	= "Apply this job analysis to ...";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo "$PRJNAME"; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
	            <form name="frmsrch1" id="frmsrch1" action="" method=POST style="display:none">
	                <input type="text" name="MAN_NUMX" id="MAN_NUMX" value="<?php echo $MAN_NUM; ?>" />
	                <input type="text" name="JOBCODEIDX" id="JOBCODEIDX" value="<?php echo $JOBCODEID; ?>" />
	                <input type="submit" class="button_css" name="submitSrch1" id="submitSrch1" value=" search " />
	            </form>
	            <form name="frmsrch2" id="frmsrch2" action="" method=POST style="display:none">
	                <input type="text" name="JOBCODEIDX" id="JOBCODEIDX2" value="<?php echo $JOBCODEID; ?>" />
	                <input type="text" name="MAN_NUMX" id="MAN_NUMX2" value="<?php echo $MAN_NUM; ?>" />
	                <input type="submit" class="button_css" name="submitSrch2" id="submitSrch2" value=" search " />
	            </form>
	            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cogs"></i>
								<h3 class="box-title"><?=$JobList?></h3>
							</div>
							<div class="box-body">
				                <input type="Hidden" name="rowCount" id="rowCount" value="0">
				                <div class="form-group">
	                            	<div class="col-sm-4" style="display: none;">
				                    	<label><?php echo $anlCode; ?></label>
				                    	<input type="text" class="form-control" style="text-align:left" id="JAN_NUM" name="JAN_NUM" size="5" value="<?php echo $JAN_NUM; ?>" readonly/>
				                    </div>
				                    <div class="col-sm-3">
				                    	<label><?php echo $Type; ?></label>
				                    	<select name="JAN_TYPE" id="JAN_TYPE" class="form-control select2">
				                          	<option value="0">---</option>
				                          	<option value="1" <?php if($JAN_TYPE == 1) { ?> selected <?php } ?>><?=$Increase?></option>
				                          	<option value="2" <?php if($JAN_TYPE == 2) { ?> selected <?php } ?>>Reset</option>
				                            ?>
				                        </select>
				                    </div>
				                    <div class="col-sm-5">
				                    	<label><?php echo $Name; ?></label>
				                    	<input type="hidden" class="form-control" style="text-align:left" id="JAN_CODE" name="JAN_CODE" size="5" value="<?php echo $JAN_NUM; ?>" />
				                    	<input type="text" class="form-control" style="text-align:left" id="JAN_NAME" name="JAN_NAME" size="5" value="<?php echo $JAN_NAME; ?>" />
				                    </div>
				                    <div class="col-sm-4">
				                    	<label><?php echo $mstAnl; ?></label>
			                        	<a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addMAnl" id="btnModal" <?php if($JAN_STAT != 0 && $JAN_STAT != 1) { ?>} disabled="disabled" <?php } ?> width="100%">
			                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo "$Select $mstAnl"; ?>
			                        	</a>
				                    	<input type="hidden" class="form-control" style="text-align:left" id="MAN_NUM" name="MAN_NUM" size="5" value="<?php echo $MAN_NUM; ?>" />
				                    	<input type="hidden" class="form-control" style="text-align:left" id="MAN_CODE" name="MAN_CODE" size="5" value="<?php echo $MAN_CODE; ?>" />
				                    	<input type="hidden" class="form-control" style="text-align:left" id="MAN_DESC" name="MAN_DESC" size="5" value="<?php echo $MAN_DESC; ?>" />
				                    	<input type="hidden" class="form-control" style="text-align:left" id="PRJCODE" name="PRJCODE" size="5" value="<?php echo $PRJCODE; ?>" />
				                    </div>
				                </div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cogs"></i>
								<h3 class="box-title"><?=$JobList?></h3>
							</div>
							<div class="box-body">
				                <div class="form-group">
				                    <div class="col-sm-4">
				                    	<label><?php echo $JobNm; ?></label>
			                        	<a class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdl_addJList" id="btnModal" <?php if($JAN_STAT != 0 && $JAN_STAT != 1) { ?>} disabled="disabled" <?php } ?> width="100%">
			                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo "$SelectJob"; ?>
			                        	</a>
				                    </div>
				                    <div class="col-sm-3">
				                    	<label><?php echo $JobName; ?></label>s
				                    </div>
				                    <div class="col-sm-5">
				                    	<label><?php echo $Notes; ?></label>
				                    	<input type="text" class="form-control" style="text-align:left" id="JAN_DESC" name="JAN_DESC" value="<?php echo $JAN_DESC; ?>" />
			                        	</a>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>

					<div class="col-md-6">
						<div class="box box-danger">
							<div class="box-header with-border">
								<i class="fa fa-cogs"></i>
								<h3 class="box-title"><?=$mstAnl?></h3>

					          	<div class="box-tools pull-right">
									<a href="javascript:void(null);" class="btn btn-danger btn-xs" onClick="rendMAnl()" title="Tampilkan Item Master Analisa"><i class="fa fa-refresh"></i></a>
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
								<input type="Hidden" name="rowCountJL" id="rowCountJL" value="0">
		                        <div class="search-table-outter">
		                            <table id="tbl_manl" class="table table-bordered table-striped" width="100%">
		                                <tr style="background:#CCCCCC">
		                                  	<th width="5%" style="text-align:left">&nbsp;</th>
		                                  	<th width="25%" style="text-align:center; vertical-align: middle;"><?php echo $Code ?> </th>
		                                  	<th width="70%" style="text-align:center; vertical-align: middle;"><?php echo $mstAnlNm ?> </th>
		                                </tr>
		                            </table>
		                            <input type="hidden" name="totalrowMANL" id="totalrowMANL" value="">
		                        </div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-success">
							<div class="box-header with-border">
								<i class="fa fa-share-alt"></i>
								<h3 class="box-title"><?=$applyAnl?></h3>

					          	<div class="box-tools pull-right">
					          		<a class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdl_addJList" id="btnModal" <?php if($JAN_STAT != 0 && $JAN_STAT != 1) { ?>} disabled="disabled" <?php } ?> width="100%">
		                        		<i class="glyphicon glyphicon-search"></i>
		                        	</a>
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
								<input type="Hidden" name="rowCountJL" id="rowCountJL" value="0">
		                        <div class="search-table-outter">
		                            <table id="tbl_jl" class="table table-bordered table-striped" width="100%">
		                                <tr style="background:#CCCCCC">
		                                  	<th width="5%" style="text-align:left">&nbsp;</th>
		                                  	<th width="25%" style="text-align:center; vertical-align: middle;"><?php echo $JobCode ?> </th>
		                                  	<th width="50%" style="text-align:center; vertical-align: middle;"><?php echo $JobName ?> </th>
		                                  	<th width="20%" style="text-align:center; vertical-align: middle;">Total</th>
		                                </tr>
		                            </table>
		                            <input type="hidden" name="totalrowJL" id="totalrowJL" value="">
		                        </div>
							</div>
						</div>
					</div>

	                <div class="col-md-12">
	                    <div class="box box-info">
							<div class="box-header with-border">
								<i class="fa fa-cubes"></i>
								<h3 class="box-title"><?=$itmLisMAnl?></h3>

					          	<div class="box-tools pull-right">
						            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					          	</div>
							</div>
							<div class="box-body">
		                        <div class="search-table-outter">
		                            <table id="tbl_manldet" class="table table-bordered table-striped" width="100%">
								  		<thead>
								            <tr>
								                <th width="5%" style="text-align:left">&nbsp;</th>
			                                  	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
			                                  	<th width="30%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
			                                  	<th width="10%" style="text-align:center"><?php echo $ItemQty; ?> </th>
			                                  	<th width="5%" style="text-align:center; vertical-align: middle;">Unit </th>
			                                  	<th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $Price ?> </th>
			                                  	<th width="10%" style="text-align:center; vertical-align: middle;">Koef</th>
			                                  	<th width="15%" style="text-align:center; vertical-align: middle;">Total</th>
								        	</tr>
								        </thead>
								        <tbody>
								        </tbody>
								        <tfoot>
								        </tfoot>
								   	</table>
		                        </div>
		                    </div>
	                  	</div>
		            </div>
	                <br>
	                <div class="col-md-6">
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                    	<input type="hidden" name="JAN_STAT" id="JAN_STAT" value="<?php echo $JAN_STAT; ?>" />
		                    	<?php
		                    		if($JAN_STAT == 2)
			                    	{
			                    		?>
			                                <button type="button" class="btn btn-info" onclick="procJAN()" title="Proses">
				                                <i class="fa fa-spinner"></i>
				                            </button>&nbsp;
		                            	<?php
		                        	}
		                    		elseif($JAN_STAT == 0 || $JAN_STAT == 1)
		                        	{
			                    		?>
			                                <button class="btn btn-primary" id="btnSave" onclick="saveDat(1)" title="Simpan" style="display: none;">
				                                <i class="fa fa-save"></i>
				                            </button>&nbsp;
			                                <button class="btn btn-success" id="btnFix" onclick="saveDat(2)" title="Fix" style="display: none;">
				                                <i class="fa fa-thumbs-o-up"></i>
				                            </button>&nbsp;
		                            	<?php
		                        	}
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply" title="Kembali"></i></button>');
		                        ?>
		                    </div>
		                </div>
		            </div>
	            </form>
	        </div>

	    	<!-- ============ START MODAL MASTER ANALISA =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addMAnl" name='mdl_addMAnl' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)"><?php echo $ItemList; ?></a>
						                    </li>	
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example" class="table table-bordered table-striped" width="100%">
													  		<thead>
													            <tr>
													                <th width="5%" style="text-align:center; vertical-align: middle;" nowrap>&nbsp;</th>
													                <th width="15%" style="text-align:center; vertical-align: middle;" nowrap> <?php echo $Code ?> </th>
													                <th width="40%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $anlNm ?> </th>
													                <th width="40%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Description ?> </th>
													        	</tr>
													        </thead>
													        <tbody>
													        </tbody>
													        <tfoot>
													        </tfoot>
													   	</table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setType(tabType)
					{
						if(tabType == 1)
						{
							document.getElementById('itm1').style.display	= '';
							document.getElementById('itm2').style.display	= 'none';
						}
						else
						{
							document.getElementById('itm1').style.display	= 'none';
							document.getElementById('itm2').style.display	= '';
						}
					}

					$(document).ready(function()
					{
				    	$('#example').DataTable( {
					        "processing": true,
					        "serverSide": true,
					        //"scrollX": false,
					        "autoWidth": true,
					        "filter": true,
					        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataMANAL2/?id=')?>",
					        "type": "POST",
					        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
					        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
					        "columnDefs": [ { targets: [0,1,3], className: 'dt-body-center' },
					                        { "width": "100px", "targets": [1] }
					                      ],
					        "order": [[ 2, "desc" ]],
					        "language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
					    });
					});

					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert3; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}
							console.log('1')
						    $.each($("input[name='chk1']:checked"), function()
						    {
								console.log('2 = '+$(this).val())
						      	add_MAnl($(this).val());
						    });

						    $('#mdl_addMAnl').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL  MASTER ANALISA =============== -->

	    	<!-- ============ START MODAL JOBLIST =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addJList" name='mdl_addJList' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $JobList; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
											                        <th width="1%" style="text-align: center;">&nbsp;</th>
		                        									<th width="85%" style="text-align: center;"nowrap><?php echo $Description; ?></th>
		                        									<th width="14%" style="text-align: center;"nowrap><?php echo $JobCost; ?></th>
											                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>&nbsp;
                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					$(document).ready(function()
					{
				    	$('#example0').DataTable(
				    	{
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataSRV/?id='.$PRJCODE)?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
											{ "width": "2px", "targets": [0] },
											{ "width": "98px", "targets": [1] },
											{ targets: [2], className: 'dt-body-right' }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});
					});

					var selectedRows = 0;
					function pickThis0(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk0']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck0").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail0").click(function()
					    {
							var totChck 	= $("#rowCheck0").val();
							
							if(totChck == 0)
							{
								swal('<?php echo $alert1; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk0']:checked"), function()
						    {
						      	add_JList($(this).val());
						    });

						    $('#mdl_addJList').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    .val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose0").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL JOBLIST =============== -->

        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
<?php
	$urlProc 	= base_url().'index.php/__l1y/JaNlProc/?id=';
	$urlMND 	= base_url().'c_project/c_joblist/getMANDet/?id='.$PRJCODE;
?>
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
	
	function add_header(strItem) 
	{
		arrItem 	= strItem.split('|');
		
		MAN_NUM 	= arrItem[0];
		MAN_CODE 	= arrItem[1];
		MAN_NAME 	= arrItem[2];

		JOBCODEID 	= document.getElementById('JOBCODEID').value;

		document.getElementById('MAN_NUM').value = MAN_NUM;
		document.getElementById('MAN_DESC').value = MAN_CODE+' : '+MAN_NAME;

		document.getElementById("MAN_NUMX").value = MAN_NUM;
		document.getElementById("JOBCODEIDX").value = JOBCODEID;
        document.frmsrch1.submitSrch1.click();
	}

	function add_JL(strItem) 
	{
		arrItem 	= strItem.split('|');
		
		JOBCODEID 		= arrItem[0];
		PRJCODE 		= arrItem[1];
		JOBDESC 		= arrItem[2];
		ITM_UNIT 		= arrItem[3];
		JOBCOST 		= arrItem[4];

		MAN_NUM 		= document.getElementById('MAN_NUM').value;

		document.getElementById("JOBCODEIDX2").value = JOBCODEID;
		document.getElementById("MAN_NUMX2").value = MAN_NUM;
        document.frmsrch2.submitSrch2.click();
	}

	function add_MAnl(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var JAN_NUMX 	= "<?php echo $JAN_NUM; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		MAN_NUM 		= arrItem[0];
		MAN_CODE 		= arrItem[1];
		MAN_NAME 		= arrItem[2];

		objTable 		= document.getElementById('tbl_manl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCountJL.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		// MAN_NUM
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+MAN_NUM+'<input type="hidden" id="dataMAN_NUM" name="dataMAN_NUM[]" value="'+MAN_NUM+'" class="form-control" style="max-width:300px;" scope="MAnlScope"><input type="hidden" id="dataMANL'+intIndex+'MAN_NUM" name="dataMANL['+intIndex+'][MAN_NUM]" value="'+MAN_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" style="text-align:right" name="dataMANL['+intIndex+'][MAN_CODE]" id="MAN_CODE'+intIndex+'" value="'+MAN_CODE+'" >';
		
		// MAN_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+MAN_NAME+'<input type="hidden" style="text-align:right" name="dataMANL['+intIndex+'][MAN_NAME]" id="MAN_NAME'+intIndex+'" value="'+MAN_NAME+'" >';
		
		// MAN_NAME
			/*objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="javascript:void(null);" class="btn btn-warning btn-xs" onClick="getItem('+MAN_NUM+')" title="Tampilkan Item"><i class="fa fa-sign-out"></i></a>';*/

		document.getElementById('totalrowMANL').value = intIndex;
	}

	function rendMAnl()
	{
		/*data = $('[scope="MAnlScope"]').serialize();
		console.log(data[dataMAN_NUM][0]);*/
		//$('#tbl_manldet').dataTable().clear();
		$('#tbl_manldet tbody').empty();

		PRJCODE 	= "<?=$PRJCODE?>";
		totMANL 	= document.getElementById('totalrowMANL').value;
		collMANL	= '';
		for(i=1;i<=totMANL;i++)
		{
			MAN_NUM 	= document.getElementById('dataMANL'+i+'MAN_NUM').value;
			if(i == 1)
				collMANL 	= MAN_NUM;
			else
				collMANL 	= collMANL+"','"+MAN_NUM;
		}

    	$('#tbl_manldet').DataTable( {
    		"paging": false,
	        "processing": true,
	        "serverSide": true,
	        //"scrollX": false,
	        "bDestroy": true,
	        "autoWidth": true,
	        "filter": true,
	        "searching": false,
	        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITMMANAL/?id=')?>"+collMANL+'&PRJCODE='+PRJCODE,
	        "type": "POST",
	        //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
	        //"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
	        "columnDefs": [ { targets: [0,1,3], className: 'dt-body-center' },
	                        { "width": "100px", "targets": [1] }
	                      ],
	        "order": [[ 2, "desc" ]],
	        "language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
	    });
    }


	function addItem(strItem) 
	{
		arrItem = strItem.split('~');
		var objTable, objTR, objTD, intIndex, arrItem;
		var JAN_NUMX 	= "<?php echo $JAN_NUM; ?>";
		ilvl = arrItem[1];
		console.log('a')
		MAN_NUM 		= arrItem[0];
		MAN_CODE 		= arrItem[1];
		ITM_CODE 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		ITM_GROUP 		= arrItem[5];
		ITM_QTY 		= arrItem[6];
		ITM_PRICE 		= arrItem[7];
		ITM_KOEF 		= arrItem[8];
		ITM_TOTAL 		= arrItem[9];
		console.log('b')

		PRJCODE 		= "<?=$PRJCODE?>";

		objTable 		= document.getElementById('tbl_manldet');
		intTable 		= objTable.rows.length;
		intIndex = parseInt(objTable.rows.length);
		document.frm.rowCountJL.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		console.log('c')
		
		// ITM_CODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="dataMANLD'+intIndex+'MAN_NUM" name="dataMANLD['+intIndex+'][MAN_NUM]" value="'+MAN_NUM+'" class="form-control" style="max-width:300px;"><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][MAN_CODE]" id="MAN_CODE'+intIndex+'" value="'+MAN_CODE+'" ><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][PRJCODE]" id="PRJCODE'+intIndex+'" value="'+PRJCODE+'" ><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_CODE]" id="ITM_CODE'+intIndex+'" value="'+ITM_CODE+'" ><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_GROUP]" id="ITM_GROUP'+intIndex+'" value="'+ITM_GROUP+'" >';
		console.log('d')

		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_NAME]" id="ITM_NAME'+intIndex+'" value="'+ITM_NAME+'" >';
		console.log('e')

		// ITM_QTY
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" name="ITM_QTY'+intIndex+'" id="ITM_QTY'+intIndex+'" value="'+ITM_QTY+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgQty(this,'+intIndex+');" readonly ><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_QTY]" id="ITM_QTY'+intIndex+'" value="'+ITM_QTY+'" >';
		console.log('f')

		// ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" >';
		console.log('g')

		// ITM_PRICE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" name="ITM_PRICE'+intIndex+'" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICE+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPrc(this,'+intIndex+');"><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_PRICE]" id="ITM_PRICE'+intIndex+'" value="'+ITM_PRICE+'" >';
		console.log('h')

		// ITM_KOEF
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" name="ITM_KOEF'+intIndex+'" id="ITM_KOEF'+intIndex+'" value="'+ITM_KOEF+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgKoef(this,'+intIndex+');"><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_KOEF]" id="ITM_KOEF'+intIndex+'" value="'+ITM_KOEF+'" >';
		console.log('i')

		// ITM_TOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = '<input type="text" name="ITM_TOTAL'+intIndex+'" id="ITM_TOTAL'+intIndex+'" value="'+ITM_TOTAL+'" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgQty(this,'+intIndex+');" readonly ><input type="hidden" style="text-align:right" name="dataMANLD['+intIndex+'][ITM_TOTAL]" id="ITM_TOTAL'+intIndex+'" value="'+ITM_TOTAL+'" >';
		console.log('j')

		document.getElementById('totalrow').value = intIndex;
	}

	function add_JList(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;
		var JAN_NUMX 	= "<?php echo $JAN_NUM; ?>";
		ilvl = arrItem[1];
		
		/*validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0],arrItem[1]))
		{
			swal("Double Item for " + arrItem[0]);
			return;
		}*/

		JOBCODEID 		= arrItem[0];
		PRJCODE 		= arrItem[1];
		JOBDESC 		= arrItem[2];
		ITM_UNIT 		= arrItem[3];
		JOBCOST 		= arrItem[4];

		objTable 		= document.getElementById('tbl_jl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCountJL.value = intIndex;
		
		console.log('ab')
		objTR = objTable.insertRow(intTable);
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>';
		
		console.log('ac')
		// JOBCODEID, PRJCODE
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+JOBCODEID+'<input type="hidden" id="dataJL'+intIndex+'JOBCODEID" name="dataJL['+intIndex+'][JOBCODEID]" value="'+JOBCODEID+'" class="form-control" style="max-width:300px;"><input type="hidden" style="text-align:right" name="dataJL['+intIndex+'][PRJCODE]" id="PRJCODE'+intIndex+'" value="'+PRJCODE+'" >';
		
		// JOBDESC
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.noWrap = true;
			objTD.innerHTML = ''+JOBDESC+'<input type="hidden" style="text-align:right" name="dataJL['+intIndex+'][JOBDESC]" id="JOBDESC'+intIndex+'" value="'+JOBDESC+'" ><input type="hidden" style="text-align:right" name="dataJL['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" >';
		
		console.log('ad')
		// ITM_UNIT
			/*objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" style="text-align:right" name="dataJL['+intIndex+'][ITM_UNIT]" id="ITM_UNIT'+intIndex+'" value="'+ITM_UNIT+'" >';*/
		
		// JOBCOST, JOBCOSTV
			JOBCOSTV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(JOBCOST)), 2));
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = ''+JOBCOSTV+'<input type="hidden" name="dataJL['+intIndex+'][JOBCOST]" id="dataJL'+intIndex+'JOBCOST" value="'+JOBCOST+'" class="form-control" style="max-width:300px;" >';

		console.log('ae')
		document.getElementById('totalrowJL').value = intIndex;
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}

	function saveDat(thisVal)
	{
		document.getElementById('JAN_STAT').value = thisVal;
	}
	
	function procJAN()
	{
	    swal({
            text: "<?php echo $sureProcess; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
		        var url 	= "<?php echo $urlProc; ?>";
		        var collID 	= "<?php echo "$JAN_NUM~$JOBCODEID~$PRJCODE~$JAN_TYPE"; ?>";
		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	swal(response, 
						{
							icon: "success",
						});
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
	
	function chgQty(thisVal1, row)
	{
		thisVal 			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'ITM_QTY').value 	= thisVal;
		document.getElementById('ITM_QTY'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));

		ITM_PRICE 	= document.getElementById('data'+row+'ITM_PRICE').value
		ITM_TOTAL 	= parseFloat(thisVal) * parseFloat(ITM_PRICE);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= ITM_TOTAL;
		document.getElementById('ITM_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		checkTotal(row);
	}
	
	function chgPrc(thisVal1, row)
	{
		thisVal 			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'ITM_PRICE').value 	= thisVal;
		document.getElementById('ITM_PRICE'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 2));

		ITM_QTY 	= document.getElementById('data'+row+'ITM_QTY').value
		ITM_TOTAL 	= parseFloat(thisVal) * parseFloat(ITM_QTY);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= ITM_TOTAL;
		document.getElementById('ITM_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		checkTotal(row);
	}
	
	function chgKoef(thisVal1, row)
	{
		thisVal 			= eval(thisVal1).value.split(",").join("");
		document.getElementById('data'+row+'ITM_KOEF').value 	= thisVal;
		document.getElementById('ITM_KOEF'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(thisVal)), 4));
		console.log('aa');
		JOBVOLM 	= document.getElementById('JOBVOLM').value;
		ITMVOLM 	= parseFloat(JOBVOLM) * parseFloat(thisVal);
		document.getElementById('data'+row+'ITM_QTY').value 	= ITMVOLM;
		document.getElementById('ITM_QTY'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITMVOLM)), 2));
		console.log('bb');

		ITM_PRICE 	= document.getElementById('data'+row+'ITM_PRICE').value;
		ITM_TOTAL 	= parseFloat(ITMVOLM) * parseFloat(ITM_PRICE);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= ITM_TOTAL;
		document.getElementById('ITM_TOTAL'+row).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));
		console.log('cc');

		checkTotal(row);
	}

	function checkTotal(row)
	{
		JOBCOST 	= parseFloat(document.getElementById('JOBCOST').value);
		GTOTAL 		= 0;
		totrow 		= document.getElementById('totalrow').value;
		for(i=1;i<=totrow;i++)
		{
			ITM_TOTAL 	= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
			GTOTAL 		= parseFloat(GTOTAL) + parseFloat(ITM_TOTAL);

			if(GTOTAL > JOBCOST)
			{
				swal('<?php echo $alert4; ?>',
				{
					icon: "warning",
				})
				.then(function()
	            {
	                swal.close();

					document.getElementById('btnSave').style.display 	= 'none';
					document.getElementById('btnFix').style.display 	= 'none';

	                document.getElementById('data'+i+'ITM_QTY').value 	= 0;
					document.getElementById('ITM_QTY'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

	                document.getElementById('data'+i+'ITM_KOEF').value 	= 0;
					document.getElementById('ITM_KOEF'+i).value			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
					
					thisVal1 	= document.getElementById('data'+i+'ITM_QTY');
				    chgQty(thisVal1, i)
	            });
				return false;
			}
		}

		document.getElementById('btnSave').style.display 	= '';
		document.getElementById('btnFix').style.display 	= '';
	}
	
	function checkForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var JAN_CODE 	= document.getElementById('JAN_CODE').value;
		var JAN_NAME 	= document.getElementById('JAN_NAME').value;

		if(JAN_CODE == "")
		{
			swal('<?php echo $alert1; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#JAN_CODE').focus();
            });
			return false;
		}

		if(JAN_NAME == "")
		{
			swal('<?php echo $alert2; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#JAN_NAME').focus();
            });
			return false;
		}

		if(totrow == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon: "warning",
			});
			return false;		
		}
		else
		{
			/*for(i=1;i<=totrow;i++)
			{
				ITM_QTY = parseFloat(document.getElementById('data'+row+'ITM_QTY').value);
				if(ITM_QTY == 0)
				{
					swal('<?php echo $inpMRQTY; ?>',
					{
						icon: "warning",
					})
					.then(function()
		            {
		                swal.close();
						document.getElementById('PR_VOLM'+i).value = '0';
		                $('#PR_VOLM'+i).focus();
		            });
					return false;
				}
			}*/
		}
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}
  
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}

	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}

	function decimalin(ini)
	{
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
	
	function RoundNDecimal(X, N)
	{
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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