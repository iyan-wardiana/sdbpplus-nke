<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 01 April 2019
	* File Name	= v_joborder_mnt.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$decFormat		= 2;

$username 			= $this->session->userdata('username');
$imgemp_filename 	= '';
$imgemp_filenameX 	= '';

$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
$DefEmp_ID 			= $this->session->userdata['Emp_ID'];

$JO_NUM			= '';
$JO_CODE 		= '';
$PRJCODE		= $PRJCODE;
$JO_DATE		= date('m/d/Y');
$JO_PRODD		= date('m/d/Y');
$CUST_CODE		= '';
$JO_DESC		= '';
$JO_VOLM		= 0;
$JO_NOTES		= '';
$JO_NOTES2		= '';
$JO_STAT		= 1;

$dataTarget		= "JO_CODE";
$CUST_ADDRESS	= '';
$SO_NUM			= '';
$SO_CODE		= '';
$SO_NOTES1		= '';


$isDis	= 1;
if($JO_STAT == 1 || $JO_STAT == 4 || $JO_STAT == 7)
{
	$isDis		= 0;
}

$STEP_1		= '';
$STEP_1D	= '';
$STEP_2		= '';
$STEP_2D	= '';
$STEP_3		= '';
$STEP_3D	= '';
$STEP_4		= '';
$STEP_4D	= '';
$STEP_5		= '';
$STEP_5D	= '';
$STEP_6		= '';
$STEP_6D	= '';
$STEP_7		= '';
$STEP_7D	= '';
$STEP_8		= '';
$STEP_8D	= '';
$STEP_9		= '';
$STEP_9D	= '';
$STEP_10	= '';
$STEP_10D	= '';
$sqlProds	= "SELECT DISTINCT JOSTF_STEP AS PRODS_STEP, PRODS_NAME, PRODS_DESC FROM tbl_jo_stfdetail
				INNER JOIN tbl_prodstep ON JOSTF_STEP = PRODS_STEP WHERE PRODS_STAT = 1";
$resProds	= $this->db->query($sqlProds)->result();
foreach($resProds as $rowProds) :
	$PRODS_STEP	= $rowProds->PRODS_STEP;
	if($PRODS_STEP == 'ONE')
	{
		$STEP_1		= $rowProds->PRODS_NAME;
		$STEP_1D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'TWO')
	{
		$STEP_2		= $rowProds->PRODS_NAME;
		$STEP_2D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'THR')
	{
		$STEP_3		= $rowProds->PRODS_NAME;
		$STEP_3D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'FOU')
	{
		$STEP_4		= $rowProds->PRODS_NAME;
		$STEP_4D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'FIV')
	{
		$STEP_5		= $rowProds->PRODS_NAME;
		$STEP_5D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'SIX')
	{
		$STEP_6		= $rowProds->PRODS_NAME;
		$STEP_6D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'SEV')
	{
		$STEP_7		= $rowProds->PRODS_NAME;
		$STEP_7D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'EIG')
	{
		$STEP_8		= $rowProds->PRODS_NAME;
		$STEP_8D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'NIN')
	{
		$STEP_9		= $rowProds->PRODS_NAME;
		$STEP_9D	= $rowProds->PRODS_DESC;
	}
	if($PRODS_STEP == 'TEN')
	{
		$STEP_10	= $rowProds->PRODS_NAME;
		$STEP_10D	= $rowProds->PRODS_DESC;
	}
endforeach;

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	    </head>
		<style>
			a:hover {
			  cursor: pointer;
			}
		</style>

	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$LangID 	= $this->session->userdata['LangID'];
		
		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Address')$Address = $LangTransl;
			if($TranslCode == 'None')$None = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'CustName')$CustName = $LangTransl;
			if($TranslCode == 'Canceled')$Canceled = $LangTransl;
			if($TranslCode == 'Next')$Next = $LangTransl;
			if($TranslCode == 'Prev')$Prev = $LangTransl;
			if($TranslCode == 'Finish')$Finish = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'ProdTotal')$ProdTotal = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'CustAddres')$CustAddres = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Stock')$Stock = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'OrdeList')$OrdeList = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'ItemListOrd')$ItemListOrd = $LangTransl;
			if($TranslCode == 'None')$None = $LangTransl;
			if($TranslCode == 'ShowDetail')$ShowDetail = $LangTransl;
			if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
			if($TranslCode == 'custPONo')$custPONo = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$stepalert1		= "Pilih salah satu Nomor Sales Order yang akan dibuatkan Job Order.";
			$stepalert2		= "Daftar Job Order (JO) atas SO : ";
			$stepalert3		= "Daftar Proses Produksi untuk Job Order : ";
			$stepalert4		= "Pastikan bahwa data yang Anda masukan sudah benar.";
			$docalert1		= "Peringatan";
			$docalert2		= "Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.";
	        $isManual		= "Centang untuk kode manual.";
			$Step1Des		= "Order Penjualan";
			$Step2Des		= "Daftar JO";
			$Step3Des		= "Proses Produksi";
			$Step4Des		= "Rekapitulasi";
			
			$alert1			= "Pilih salah satu nomor Sales Order (SO).";
			$alert2			= "Masukan jumlah volume produksi.";
			$alert3			= "Masukan catatan dokumen JO.";
			$alert4			= "Jumlah yang di-JO lebih besar dari sisa SO.";
		}
		else
		{
			$stepalert1		= "Select one of the Sales Order Numbers that will be made a Job Order.";
			$stepalert2		= "List of Job Orders (JO) for the SO : ";
			$stepalert3		= "List of Production Process for the JO : ";
			$stepalert4		= "Make sure that the data you entered is correct.";
			$docalert1		= "Warning";
			$docalert2		= "Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.";
			$isManual		= "Check to manual code.";
			$Step1Des		= "Sales Order";
			$Step2Des		= "Job Order List";
			$Step3Des		= "Production Process";
			$Step4Des		= "Summary";
			
			$alert1			= "Please select one of Sales Order (SO) Number.";
			$alert2			= "Please input prodcution volume.";
			$alert3			= "Please input Notes of this JO document.";
			$alert4			= "JO Qty is greater than of Remaining Qty.";
		}
		
		$SO_DATEV		= '';
		$SO_DATEV1		= '';
		$CUST_DESC		= '';
		$CUST_ADDRESS	= '';
		$SO_NOTES		= '';
		$SO_NOTES1		= '';
		$SO_REFRENS		= '';
		
		$showFORM		= 1;				// 1 = Select SO, 2 = SO Info, 3 = Pilih FG, 4 = Rekapitulasi
		$Step_Bef		= 0;
		$Step_Next		= 1;
		$STEP_BEF		= 1;
		$loading_1		= 1;
		$loading_2		= 1;
		$loading_3		= 1;
		$loading_4		= 1;
		$SO_CODEV		= '';
		
		if(isset($_POST['Step_Next']))
		{
			$Step_Next		= $_POST['Step_Next'];
		}
		
		if($Step_Next == 2)
		{
			$SO_NUM			= $_POST['SO_NUM1'];
			
			$sqlSOD			= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD,
									A.CUST_CODE, A.SO_NOTES, A.SO_NOTES1, A.SO_REFRENS,
									B.CUST_DESC, B.CUST_ADD1
								FROM tbl_so_header A
									INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.SO_STAT = 3
									AND A.SO_NUM = '$SO_NUM' LIMIT 1";
			$resSOD			= $this->db->query($sqlSOD)->result();
			foreach($resSOD as $rowSOD) :
				$SO_NUM			= $rowSOD->SO_NUM;
				$SO_CODE		= $rowSOD->SO_CODE;
				$SO_DATE		= $rowSOD->SO_DATE;
				$SO_DATEV		= date('m/d/Y', strtotime($SO_DATE));
				$SO_DATEV1		= date('d M Y', strtotime($SO_DATE));
				$SO_DUED		= $rowSOD->SO_DUED;
				$SO_PRODD		= $rowSOD->SO_PRODD;
				$SO_PRODDV		= date('m/d/Y', strtotime($SO_PRODD));
				$SO_REFRENS		= $rowSOD->SO_REFRENS;
				$SO_NOTES		= $rowSOD->SO_NOTES;
				$SO_NOTES1		= $rowSOD->SO_NOTES1;
				
				$CUST_CODE		= $rowSOD->CUST_CODE;
				$CUST_DESC		= $rowSOD->CUST_DESC;
				$CUST_ADDRESS	= $rowSOD->CUST_ADD1;
				$CUST_DESC		= $rowSOD->CUST_DESC;
			endforeach;
			
			$SO_CODEV			= '';
			
			$IMGC_FILENAMEX	= 'username.jpg';
			$sqlCST			= "SELECT IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE' LIMIT 1";
			$resCST			= $this->db->query($sqlCST)->result();
			foreach($resCST as $rowCST) :
				$IMGC_FILENAMEX		= $rowCST->IMGC_FILENAMEX;
			endforeach;
			$imgLoc			= base_url('assets/AdminLTE-2.0.5/cust_image/'.$CUST_CODE.'/'.$IMGC_FILENAMEX);
			
			$showFORM		= 2;
			$loading_2		= 1;
		}
		elseif($Step_Next == 3)
		{
			$SO_NUM		= $_POST['SO_NUM2'];
			$JO_NUM		= $_POST['JO_NUM2'];
			$JO_CODE	= $_POST['JO_CODE2'];
			$JO_CODEV	= $_POST['JO_CODE2'];
			
			$sqlSOD			= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD,
									A.CUST_CODE, A.SO_NOTES, A.SO_NOTES1, A.SO_REFRENS,
									B.CUST_DESC, B.CUST_ADD1
								FROM tbl_so_header A
									INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
								WHERE A.PRJCODE = '$PRJCODE'
									AND A.SO_STAT = 3
									AND A.SO_NUM = '$SO_NUM' LIMIT 1";
			$resSOD			= $this->db->query($sqlSOD)->result();
			foreach($resSOD as $rowSOD) :
				$SO_NUM			= $rowSOD->SO_NUM;
				$SO_CODE		= $rowSOD->SO_CODE;
				$SO_CODEV		= $rowSOD->SO_CODE;
				$SO_DATE		= $rowSOD->SO_DATE;
				$SO_DATEV		= date('m/d/Y', strtotime($SO_DATE));
				$SO_DATEV1		= date('d M Y', strtotime($SO_DATE));
				$SO_DUED		= $rowSOD->SO_DUED;
				$SO_PRODD		= $rowSOD->SO_PRODD;
				$SO_PRODDV		= date('m/d/Y', strtotime($SO_PRODD));
				$SO_REFRENS		= $rowSOD->SO_REFRENS;
				$SO_NOTES		= $rowSOD->SO_NOTES;
				$SO_NOTES1		= $rowSOD->SO_NOTES1;
				
				$CUST_CODE		= $rowSOD->CUST_CODE;
				$CUST_DESC		= $rowSOD->CUST_DESC;
				$CUST_ADDRESS	= $rowSOD->CUST_ADD1;
				$CUST_DESC		= $rowSOD->CUST_DESC;
			endforeach;
			
			$JO_NOTES		= '';
			$sqlJOD			= "SELECT A.JO_NOTES
								FROM tbl_jo_header A
								WHERE A.JO_NUM = '$JO_NUM' LIMIT 1";
			$resJOD			= $this->db->query($sqlJOD)->result();
			foreach($resJOD as $rowJOD) :
				$JO_NOTES	= $rowJOD->JO_NOTES;
			endforeach;
			
			$showFORM	= 3;
			$loading_3	= 1;
		}
	?>
	
	<style>
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>
			  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

        <section class="content">
	      	<div class="row">
		        <div class="col-md-3">
		          	<!-- Profile Image -->
		          	<div class="box box-primary">
		                <div class="box-body box-profile">
		                    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgLoc; ?>" alt="User profile picture">
		                    <h3 class="profile-username text-center"></h3>                    
		                    <p class="text-muted text-center">
		                        <a><b>
		                    		<?php 
										if($CUST_DESC == '')
											echo $CustName;
										else
											echo $CUST_DESC;
									?>
		                            </b></a>
		                    </p>
		                    <ul class="list-group list-group-unbordered">
		                    	<li class="list-group-item" style="text-align:center">
		                            <p class="text-muted"><em>
		                                <i class="fa fa-map-marker margin-r-5"></i>
		                                <?php 
		                                    if($CUST_ADDRESS == '')
		                                        echo $None;
		                                    else
		                                        echo $CUST_ADDRESS;
		                                ?>
		                            </em></p>
		                    	</li>
		                    </ul>
		                </div>
		          	</div>

		          	<div class="box box-primary">
		                <div class="box-header with-border">
		                	<h3 class="box-title"><?php echo $Description; ?> (SO)</h3>
		                </div>
		                <!-- /.box-header -->
		                <div class="box-body">
		                	<strong><i class="fa fa-pencil margin-r-5"></i> <?php echo $Code; ?> (SO)</strong>
		                	<p class="text-muted">
		                		<em>
									<?php 
		                                if($SO_CODE == '')
		                                    echo $None;
		                                else
		                                    echo "$SO_CODE - $SO_DATEV1";
		                            ?>
		                        </em>
		                	</p>
		               		<hr>
		                    <strong><i class="fa fa-link margin-r-5"></i> No. Ref.</strong>
		                    <p class="text-muted">
		                		<em>
									<?php 
		                                if($SO_REFRENS == '')
		                                    echo $None;
		                                else
		                                    echo $SO_REFRENS;
		                            ?>
		                        </em>
		                    </p>
							<hr>
		                	<strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $Notes; ?> (SO)</strong>
		                    <p><em>
								<?php
		                            if($SO_NOTES == '')
		                                echo $None;
		                            else
		                                echo $SO_NOTES;
		                        ?>
		                    </em></p>
							<hr>
		                	<strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $ApproverNotes; ?> (SO)</strong>
		                    <p><em>
								<?php 
		                            if($SO_NOTES1 == '')
		                                echo $None;
		                            else
		                                echo $SO_NOTES1;
		                        ?>
		                    </em></p>
		                </div>
		            </div>
		        </div>
		        <style>
				    .disabled {
				        pointer-events: none;
				        cursor: default;
				    }
				</style>
		        <div class="col-md-9">
					<div class="nav-tabs-custom">
		            	<ul class="nav nav-tabs">
		                	<li <?php if($showFORM == 1) { ?> class="active" <?php } ?>><a href="#selectSO" data-toggle="tab" class="disabled">1. <?php echo $Step1Des; ?></a></li> 		<!-- Tab 1 -->
		                    <li <?php if($showFORM == 2) { ?> class="active" <?php } ?>><a href="#SOInfo" data-toggle="tab" class="disabled">2. <?php echo $Step2Des; ?></a></li>					<!-- Tab 2 -->
		                    <li <?php if($showFORM == 3) { ?> class="active" <?php } ?>><a href="#SelectFG" data-toggle="tab" class="disabled">3. <?php echo "$Step3Des : $SO_CODEV"; ?></a></li>					<!-- Tab 3 -->
		                </ul>
		                <div class="tab-content">
		                	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
							<?php
		                        $back1	= site_url('c_production/c_j0b0rd3r/glj0b0rd3r/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        if($showFORM == 1)
		                        {
		                    	?>
		                            <div class="active tab-pane" id="selectSO">
		                                <form class="form-horizontal" name="frmSOrder" method="post" action="" onSubmit="return checkSOrder()">
		                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="0">
		                                    <input type="hidden" name="Step_Next" id="Step_Next" value="2">
		                                    <input type="hidden" name="SO_NUM1" id="SO_NUM1" value="<?php echo $SO_NUM; ?>">
		                                    <div class="box-body">
		                                        <div class="box box-primary">
		                                            <br>
		                                            <div class="alert alert-warning alert-dismissible" style="display:none">
		                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                                <i class="icon fa fa-warning"></i><?php echo $stepalert1; ?>
		                                            </div>
		                                            <div class="search-table-outter">
		                                                <table id="example1" class="table table-bordered table-striped" width="100%">
		                                                    <thead>
		                                                        <tr>
		                                                            <th width="2%" height="25" style="text-align:center"></th>
		                                                            <th width="15%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Code; ?> SO</th>
		                                                            <th width="10%" style="text-align:center; vertical-align:middle" nowrap><?php echo $Date; ?> </th>
		                                                          	<th width="10%" style="text-align:center; vertical-align:middle; white-space: nowrap"><?php echo $ProdPlan; ?> </th>
		                                                          	<th width="15%" style="text-align:center; vertical-align:middle; white-space: nowrap"><?php echo $custPONo; ?> </th>
		                                                            <th width="48%" style="text-align:center; vertical-align:middle" nowrap><?php echo $CustName; ?> </th>
		                                                        </tr>
		                                                    </thead>
		                                                    <tbody>
		                                                        <?php
		                                                            $sqlSO		= "SELECT A.SO_NUM, A.SO_CODE, A.SO_DATE, A.SO_DUED, A.SO_PRODD, A.SO_REFRENS,
		                                                                                A.CUST_CODE, B.CUST_DESC
		                                                                            FROM tbl_so_header A
		                                                                                INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
		                                                                            WHERE A.PRJCODE = '$PRJCODE'
		                                                                                AND A.SO_STAT IN (3,6)";
		                                                            $resSO 		= $this->db->query($sqlSO)->result();
		                                                            
		                                                            $sqlSOC		= "tbl_so_header A
		                                                                                INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
		                                                                            WHERE A.PRJCODE = '$PRJCODE'
		                                                                                AND A.SO_STAT IN (3,6)";
		                                                            $resSOC 	= $this->db->count_all($sqlSOC);
		                                                            
		                                                            $i		= 0;
		                                                            $j		= 0;
		                                                            $cRow	= 0;
		                                                            if($resSOC > 0)
		                                                            {
		                                                                foreach($resSO as $rowSO) :
		                                                                    $cRow  			= ++$i;
		                                                                    $SO_NUM1		= $rowSO->SO_NUM;
		                                                                    $SO_CODE		= $rowSO->SO_CODE;
		                                                                    $SO_DATE		= $rowSO->SO_DATE;
		                                                                    $SO_DUED		= $rowSO->SO_DUED;
		                                                                    $SO_PRODD		= $rowSO->SO_PRODD;
		                                                                    $SO_REFRENS		= $rowSO->SO_REFRENS;
		                                                                    $CUST_CODE		= $rowSO->CUST_CODE;
		                                                                    $CUST_DESC		= strtolower($rowSO->CUST_DESC);
		                                                                    ?>
		                                                                    <tr>
		                                                                        <td style="text-align:center" nowrap>
		                                                                            <input type="radio" name="chkSO" id="chkSO_<?php echo $cRow; ?>" value="<?php echo $SO_NUM1; ?>" onClick="pickSO(this);" <?php if($SO_NUM == $SO_NUM1) { ?> checked <?php } ?>>
		                                                                        </td>
		                                                                        <td style="text-align:left" nowrap>
		                                                                            <?php echo $SO_CODE; ?>
		                                                                        </td>
		                                                                        <td style="text-align:center" nowrap>
		                                                                            <?php echo date('d M Y', strtotime($SO_DATE)); ?>
		                                                                        </td>  
		                                                                        <td style="text-align:center" nowrap>
		                                                                            <?php echo date('d M Y', strtotime($SO_PRODD)); ?>
		                                                                        </td>
		                                                                        <td style="text-align:left" nowrap>
		                                                                            <?php echo ucwords($SO_REFRENS); ?>
		                                                                        </td>
		                                                                        <td style="text-align:left" nowrap>
		                                                                            <?php echo ucwords($CUST_DESC); ?>
		                                                                        </td>
		                                                                    </tr>
		                                                                    <?php
		                                                                endforeach;
		                                                            }
		                                                        ?>
		                                                        <input type="text" name="totRow1" id="totRow1" value="<?php echo $cRow; ?>" style="display:none">
		                                                    </tbody>
		                                                </table>
		                                            </div>
		                                        
		                                            <div class="box-header with-border">
		                                                <table width="100%" border="0">
		                                                    <tr>
		                                                        <td width="15%" style="text-align:left;" nowrap>&nbsp;</td>
		                                                        <td width="85%" style="text-align:right" nowrap>
		                                                            <button class="btn btn-warning" style="display:none" disabled>
		                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
		                                                            </button>
		                                                            <button class="btn btn-success" onClick="isNext1(1);">
		                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
		                                                            </button>
		                                                            <button class="btn btn-success" style="display:none" disabled>
		                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
		                                                            </button>
		                                                        </td>
		                                                    </tr>
		                                                </table>
		                                            </div>
		                                            <div id="loading_1" class="overlay" <?php if($loading_1 == 1) { ?> style="display:none" <?php } ?>>
		                                                <i class="fa fa-refresh fa-spin"></i>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </form>
										<script>
		                                    function pickSO(thisobj)
		                                    {
		                                        var totRow	= document.getElementById("totRow1").value;
												
		                                        var SONUM	= '';
		                                        j	= 0;
		                                        for(i=1; i <= totRow; i++)
		                                        {
		                                            var isChk	= document.getElementById('chkSO_'+i).checked;
		                                            
		                                            if(isChk == true)
		                                            {
		                                                j = j+1;
		                                                if(j == 1)
		                                                {
		                                                    var SO_NUM	= document.getElementById('chkSO_'+i).value;
		                                                    document.getElementById('SO_NUM1').value	= SO_NUM;
		                                                }
		                                                else
		                                                {
		                                                    var SONUM	= document.getElementById('SO_NUM1').value;
		                                                    var SO_NUM	= document.getElementById('chkSO_'+i).value;
		                                                    SONUM_		= SONUM+'~'+SO_NUM;
		                                                    document.getElementById('SO_NUM1').value	= SONUM_;
		                                                }
		                                            }
		                                        }
		                                    }
		                
		                                    function checkSOrder()
		                                    {
		                                        SO_NUM		= document.getElementById('SO_NUM1').value;
		                                        if(SO_NUM == '')
		                                        {
		                                            alert('<?php echo $alert1; ?>');
		                                            return false;
		                                        }
		                                        
		                                        document.getElementById('loading_1').style.display = '';
		                                    }
		                                </script>
		                            </div>
								<?php
		                        }
		                        if($showFORM == 2)
		                        {
		                    	?>
		                            <div class="active tab-pane" id="SOInfo">
		                                <form class="form-horizontal" name="frmSOInfo" method="post" action="" onSubmit="return checkSOInfo()">
		                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="1">
		                                    <input type="hidden" name="Step_Next" id="Step_Next" value="3">
		                                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		                                    <input type="hidden" name="SO_NUM2" id="SO_NUM2" value="<?php echo $SO_NUM; ?>">
		                                    <input type="hidden" name="JO_NUM2" id="JO_NUM2" value="">
		                                    <input type="hidden" name="JO_CODE2" id="JO_CODE2" value="">
		                                    <div class="box-body">
		                                        <div class="box box-primary">
		                                            <br>
		                                            <div class="alert alert-success alert-dismissible">
		                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                                <i class="icon fa fa-file"></i><?php echo "$stepalert2 $SO_CODE<br>"; ?>
		                                                <i class="icon fa fa-exclamation-triangle"></i><?php echo "$Notes : $SO_NOTES"; ?>
		                                            </div>
		                                            <div class="row">
		                                            <?php
														$theRow		= 0;
														$JO_NUM		= '';
														$JO_CODE	= '';
														$JO_DATE	= '';
														$CUST_DESC	= '';
														$sqlJO	= "SELECT JO_NUM, JO_CODE, JO_DATE, CUST_DESC from tbl_jo_header
																	WHERE PRJCODE = '$PRJCODE' AND SO_NUM = '$SO_NUM'";
														$resJO	= $this->db->query($sqlJO)->result();
														foreach ($resJO as $rowJO):
															$theRow		= $theRow + 1;
															$JO_NUM		= $rowJO->JO_NUM;
															$JO_CODE	= $rowJO->JO_CODE;
															$JO_DATE	= $rowJO->JO_DATE;
															$CUST_DESC	= $rowJO->CUST_DESC;
															
															$ONE		= 0;
															$TWO		= 0;
															$THR		= 0;
															$FOU		= 0;
															$FIV		= 0;
															$SIX		= 0;
															$SEV		= 0;
															$EIG		= 0;
															$NIN		= 0;
															$TEN		= 0;
															
															$sqlSTF	= "SELECT ONE, TWO, THR, FOU, FIV, SIX, SEV, EIG, NIN, TEN
																		FROM tbl_jo_concl WHERE JO_NUM = '$JO_NUM'";
															$resSTF	= $this->db->query($sqlSTF)->result();
															foreach ($resSTF as $rowSTF):
																$ONE	= $rowSTF->ONE;
																$TWO	= $rowSTF->TWO;
																$THR	= $rowSTF->THR;
																$FOU	= $rowSTF->FOU;
																$FIV	= $rowSTF->FIV;
																$SIX	= $rowSTF->SIX;
																$SEV	= $rowSTF->SEV;
																$EIG	= $rowSTF->EIG;
																$NIN	= $rowSTF->NIN;
																$TEN	= $rowSTF->TEN;
															endforeach;
															?>
															<div class="col-md-12">
																<div class="box box-success collapsed-box">
																	<div class="box-header with-border">
																		<font style="font-weight:bold"><?php echo "$theRow. $JO_CODE"; ?>&nbsp;&nbsp;</font><a title="<?php echo $ShowDetail; ?>" style="" onClick="getJONUM('<?php echo $JO_NUM; ?>','<?php echo $JO_CODE; ?>')"><i class="fa fa-expand"></i></a>
																		<div class="box-tools pull-right">
																			<span class="label label-info">
																				<?php echo date('d M Y', strtotime($JO_DATE)); ?>
		                                                                    </span>
																			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
																			</button>
																			<button type="button" class="btn btn-box-tool" data-widget="remove" style="display:none"><i class="fa fa-times"></i>
																			</button>
																		</div>
																	</div>
																	<div class="box-body">
																		<blockquote>
		                                                                    <ul class="timeline">
		                                                                    	<?php if(isset($STEP_1) && ($STEP_1 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-red"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">1. <?php echo $STEP_1; ?></a></h3>
		                                                                                    </h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_1D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-red btn-flat margin">
		                                                                                        <?php echo number_format($ONE,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_2) && ($STEP_2 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-yellow"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">2. <?php echo $STEP_2; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_2D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-yellow btn-flat margin">
		                                                                                        <?php echo number_format($TWO,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_3) && ($STEP_3 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-purple"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">3. <?php echo $STEP_3; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_3D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-purple btn-flat margin">
		                                                                                        <?php echo number_format($THR,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_4) && ($STEP_4 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-teal"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">4. <?php echo $STEP_4; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_4D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-teal btn-flat margin">
		                                                                                        <?php echo number_format($FOU,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_5) && ($STEP_5 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-blue"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">5. <?php echo $STEP_5; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_5D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-blue btn-flat margin">
		                                                                                        <?php echo number_format($FIV,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_6) && ($STEP_6 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-green"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">6. <?php echo $STEP_6; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_6D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-green btn-flat margin">
		                                                                                        <?php echo number_format($SIX,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_7) && ($STEP_7 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-maroon"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">7. <?php echo $STEP_7; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_7D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-maroon btn-flat margin">
		                                                                                        <?php echo number_format($SEV,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_8) && ($STEP_8 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-teal"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">8. <?php echo $STEP_8; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_8D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-teal btn-flat margin">
		                                                                                        <?php echo number_format($EIG,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_9) && ($STEP_9 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-purple"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">9. <?php echo $STEP_9; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_9D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-purple btn-flat margin">
		                                                                                        <?php echo number_format($NIN,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    	<?php if(isset($STEP_10) && ($STEP_10 != '')) { ?>
		                                                                            <li>
		                                                                                <i class="glyphicon glyphicon-refresh bg-black"></i>
		                                                                                <div class="timeline-item">
		                                                                                    <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                    <h3 class="timeline-header"><a href="#">10. <?php echo $STEP_10; ?></a></h3>
		                                                                                    <div class="timeline-body" style="font-size:14px">
		                                                                                        <?php echo $STEP_10D; ?>
		                                                                                    </div>
		                                                                                    <button type="button" class="btn bg-black btn-flat margin">
		                                                                                        <?php echo number_format($TEN,2); ?>
		                                                                                    </button>
		                                                                                </div>
		                                                                            </li>
		                                                                        <?php } ?>
		                                                                    </ul>
		                                                                </blockquote>
																	</div>
																</div>
															</div>
															<?php
														endforeach;
													?>
		                                        	</div>
		                                            <div class="box-header with-border">
		                                                <table width="100%" border="0">
		                                                    <tr>
		                                                        <td width="15%" style="text-align:left;" nowrap>
		                                                            <button class="btn btn-danger" onClick="isBack1(1);">
		                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
		                                                            </button>
		                                                        </td>
		                                                        <td width="85%" style="text-align:right" nowrap>
		                                                            <button class="btn btn-warning" style="display:none" onClick="isBack1(1);">
		                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
		                                                            </button>
		                                                            <button class="btn btn-primary" onClick="isNext2(2);" id="btnSubmit2" style="display: none;">
		                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
		                                                            </button>
		                                                            <button class="btn btn-success" disabled>
		                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
		                                                            </button>
		                                                        </td>
		                                                    </tr>
		                                                </table>
		                                            </div>
		                                            <div id="loading_2" class="overlay" <?php if($loading_2 == 1) { ?> style="display:none" <?php } ?>>
		                                                <i class="fa fa-refresh fa-spin"></i>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </form>
		                            </div>
									<script>
		                                function isBack1(thisValue)
		                                {
		                                    document.getElementById('Step_Bef').value 	= 2;
		                                    document.getElementById('Step_Next').value 	= 1;
		                                }
		                                
		                                function getJONUM(JONUM, JOCODE)
		                                {
											document.getElementById('JO_NUM2').value = JONUM;
											document.getElementById('JO_CODE2').value = JOCODE;
											document.getElementById("btnSubmit2").click();
											
		                                    document.getElementById('loading_2').style.display = '';
		                                }
		                            </script>
								<?php
		                        }
		                        if($showFORM == 3)
		                        {
		                    	?>
		                            <div class="active tab-pane" id="SelectFG">
		                                <form class="form-horizontal" name="frmSelFG" method="post" action="" onSubmit="return checkSOInfo()">
		                                    <input type="hidden" name="Step_Bef" id="Step_Bef" value="2">
		                                    <input type="hidden" name="Step_Next" id="Step_Next" value="4">
		                                    <input type="hidden" name="JO_NUM3" id="JO_NUM3" value="<?php echo $JO_NUM; ?>">
		                                    <input type="hidden" name="JO_CODE3" id="JO_CODE3" value="<?php echo $JO_CODE; ?>">
		                                    <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		                                    <input type="hidden" name="SO_NUM" id="SO_NUM" value="<?php echo $SO_NUM; ?>">
		                                    <div class="box-body">
		                                        <div class="box box-primary">
		                                            <br>
		                                            <div class="alert alert-success alert-dismissible">
		                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                                                <i class="icon fa fa-file"></i><?php echo "$stepalert3 $JO_CODE<br>"; ?>
		                                                <i class="icon fa fa-exclamation-triangle"></i><?php echo "$Notes : $JO_NOTES"; ?>
		                                            </div>
		                                            <div class="row">
		                                            <?php
														//$theRow	= 0;
														$sqlJO	= "SELECT JO_NUM, JO_CODE, JO_DATE, CUST_DESC from tbl_jo_header
																	WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$JO_NUM' LIMIT 1";
														$resJO	= $this->db->query($sqlJO)->result();
														foreach ($resJO as $rowJO):
															//$theRow		= $theRow + 1;
															$JO_NUM		= $rowJO->JO_NUM;
															$JO_CODE	= $rowJO->JO_CODE;
															$JO_DATE	= $rowJO->JO_DATE;
															$CUST_DESC	= $rowJO->CUST_DESC;
														endforeach;
														?>
															<div class="col-md-12">
																<div class="box box-success collapsed-box">
																	<div class="box-header with-border">
																		<font style="font-weight:bold"><?php echo "$JO_CODE"; ?>&nbsp;&nbsp;</font>
																		<div class="box-tools pull-right">
																			<span class="label label-info">
																				<?php echo date('d M Y', strtotime($JO_DATE)); ?>
		                                                                    </span>
																			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
																			</button>
																			<button type="button" class="btn btn-box-tool" data-widget="remove" style="display:none"><i class="fa fa-times"></i>
																			</button>
																		</div>
																	</div>
																	<div class="box-body">
		                                                                <ul class="timeline">
		                                                                	<?php if(isset($STEP_1) && ($STEP_1 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-red"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_1; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr style="text-align: center;">
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'ONE' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-red">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_2) && ($STEP_2 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-yellow"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_2; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'TWO' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-yellow">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_3) && ($STEP_3 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-purple"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_3; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'THR' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-purple">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_4) && ($STEP_4 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-teal"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_4; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'FOU' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-teal">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_5) && ($STEP_5 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-blue"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_5; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'FIV' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-blue">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_6)  && ($STEP_6 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-green"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_6; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'SIX' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-green">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_7) && ($STEP_7 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-maroon"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_7; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'SEV' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-maroon">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_8) && ($STEP_8 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-teal"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#"><?php echo $STEP_8; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'EIG' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-teal">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_9) && ($STEP_9 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-purple"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#">9. <?php echo $STEP_9; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'NIN' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-purple">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
		                                                                	<?php if(isset($STEP_10) && ($STEP_10 != '')) { ?>
		                                                                        <li>
		                                                                            <i class="glyphicon glyphicon-refresh bg-black"></i>
		                                                                            <div class="timeline-item">
		                                                                                <span class="time"><i class="fa fa-clock-o" style="display:none"></i> &nbsp;</span>
		                                                                                <h3 class="timeline-header"><a href="#">10. <?php echo $STEP_10; ?></a></h3>
		                                                                                <table class="table table-bordered table-striped" width="100%">
		                                                                                    <tr>
		                                                                                        <th width="17" style="width: 10px">&nbsp;</th>
		                                                                                        <th width="108" style="text-align: center;" nowrap><?php echo $Code; ?></th>
		                                                                                        <th width="32" style="text-align: center;" nowrap><?php echo $Date; ?></th>
		                                                                                        <th width="83" style="text-align: center;" nowrap><?php echo $BOMCode; ?></th>
		                                                                                        <th width="785" style="text-align: center;"><?php echo $ItemName; ?></th>
		                                                                                        <th width="108" style="text-align: center;">Qty</th>
		                                                                                        <th width="105" style="text-align: center;">Unit</th>
		                                                                                    </tr>
																							<?php
																								$theRowSTF	= 0;
		                                                                                        $sqlSTF	= "SELECT A.STF_CODE, A.STF_DATE, A.BOM_CODE,
																												A.ITM_NAME, A.STF_VOLM, A.ITM_UNIT,
																												A.ITM_TYPE
		                                                                                                    FROM tbl_stf_detail A
																												INNER JOIN tbl_stf_header B
																													ON A.STF_NUM = B.STF_NUM
																													AND B.PRJCODE = '$PRJCODE'
																											WHERE A.JO_NUM = '$JO_NUM'
																												AND B.STF_FROM = 'TEN' AND B.STF_STAT = 3";
		                                                                                        $resSTF	= $this->db->query($sqlSTF)->result();
		                                                                                        foreach($resSTF as $rwSTF):
																									$theRowSTF	= $theRowSTF + 1;
		                                                                                            $STF_CODE	= $rwSTF->STF_CODE;
		                                                                                            $STF_DATE	= $rwSTF->STF_DATE;
		                                                                                            $BOM_CODE	= $rwSTF->BOM_CODE;
		                                                                                            $STF_NOTES	= $rwSTF->ITM_NAME;
		                                                                                            $STF_UNIT	= $rwSTF->ITM_UNIT;
																									$STF_QTY	= number_format($rwSTF->STF_VOLM,2);
		                                                                                            $ITM_TYPE	= $rwSTF->ITM_TYPE;
																									$DTYPE 		= "";
																									if($ITM_TYPE == 'OUT')
																										$DTYPE 	= "<i class='text-green'>(output)</i>";
		                                                                                            ?>
		                                                                                                <tr>
		                                                                                                    <td><?php echo $theRowSTF; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_CODE; ?></td>
		                                                                                                    <td nowrap><?php echo $STF_DATE; ?></td>
		                                                                                                    <td nowrap><?php echo $BOM_CODE; ?></td>
		                                                                                                    <td><?php echo "$STF_NOTES $DTYPE"; ?></td>
		                                                                                                    <td style="text-align:right">
		                                                                                                    	<span class="badge bg-black">
		                                                                                                        	<?php echo $STF_QTY; ?>
		                                                                                                        </span>
		                                                                                                    </td>
		                                                                                                    <td style="text-align:center;">
		                                                                                                    	<?php echo $STF_UNIT; ?>
		                                                                                                    </td>
		                                                                                                </tr>
		                                                                                            <?php
																								endforeach;
																							?>
		                                                                                </table>
		                                                                            </div>
		                                                                        </li>
		                                                                    <?php } ?>
																            <li>
																              	<i class="fa fa-clock-o bg-gray"></i>
																            </li>
																	</div>
																</div>
															</div>
															<?php
														//endforeach;
													?>
		                                            </div>
		                                        
		                                            <div class="box-header with-border">
		                                                <table width="100%" border="0">
		                                                    <tr>
		                                                        <td width="15%" style="text-align:left;" nowrap>
		                                                            <button class="btn btn-danger" onClick="isBack1(1);">
		                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
		                                                            </button>
		                                                        </td>
		                                                        <td width="85%" style="text-align:right" nowrap>
		                                                            <button class="btn btn-warning" style="display:none" onClick="isBack2(2);">
		                                                                <i class="glyphicon glyphicon-triangle-left"></i><?php echo $Prev; ?>
		                                                            </button>
		                                                            <button class="btn btn-primary" style="display:none" onClick="isNext3(4);">
		                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Next; ?>
		                                                            </button>
		                                                            <button class="btn btn-success" style="display:none" disabled>
		                                                                <i class="glyphicon glyphicon-triangle-right"></i><?php echo $Finish; ?>
		                                                            </button>
		                                                        </td>
		                                                    </tr>
		                                                </table>
		                                            </div>
		                                            <div id="loading_3" class="overlay" <?php if($loading_3 == 1) { ?> style="display:none" <?php } ?>>
		                                                <i class="fa fa-refresh fa-spin"></i>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </form>
		                            </div>
								<?php
		                        }
							?>                  
						</div>
					</div>
		        </div>
		    </div>
		</section>
	</body>
</html>
<script>
	$("#example1").DataTable();
	$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": false,
		//"scrollX": false,
		"autoWidth": true,
		"filter": true,
        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllData/?id='.$PRJCODE)?>",
        "type": "POST",
		//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
		"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
		"columnDefs": [	{ targets: [0,1,2,4,5], className: 'dt-body-center' },
						{ "width": "100px", "targets": [1] }
					  ],
		} );
	} );
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
    $('#datepicker').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
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
</script>

<script>
	var decFormat		= 2;
	
	function doDecimalFormat(angka) 
	{
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
		
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
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