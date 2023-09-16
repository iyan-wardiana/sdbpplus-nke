<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 5 April 2017
	* File Name		= item_list.php
	* Location		= -
*/

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJNAME		= '';
$PRJNAME1		= '';
$PRJLEV 		= 2;
$PRJCODE		= $PRJCODE;
$sqlPRJ 		= "SELECT A.PRJNAME, A.PRJLEV, A.PRJCODEVW FROM tbl_project A WHERE A.PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
	$PRJLEV 	= $rowPRJ->PRJLEV;
endforeach;
$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
if($PRJLEV == 3)
{
	$accIN = 1;
	$accUM = 5;
}
else
{
	$accIN = 1;
	$accUM = "1,2,5,6,7,8";
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
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'StockQuantity')$StockQuantity = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'ReceiptQty')$ReceiptQty = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Used')$Used = $LangTransl;
			if($TranslCode == 'OnHand')$OnHand = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'UnitName')$UnitName = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'Upload')$Upload = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'recAcc')$recAcc = $LangTransl;
			if($TranslCode == 'UsedAcc')$UsedAcc = $LangTransl;
			if($TranslCode == 'Grouping')$Grouping = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'Group')$Group = $LangTransl;
		endforeach;

		$selPRJSYNC	= '';
		if(isset($_POST['submitSYNC']))
		{
			$selPRJSYNC = $_POST['selPRJSYNC'];
			
			$sqlITMSYN	= "SELECT ITM_CODE, ITM_UNIT FROM tbl_item_$PRJCODEVW WHERE PRJCODE = '$selPRJSYNC' AND STATUS = 1";
			$resITMSYN 	= $this->db->query($sqlITMSYN)->result();
			foreach($resITMSYN as $rowSYNC) :
				$ITM_CODE 	= $rowSYNC->ITM_CODE;
				$ITM_UNIT 	= $rowSYNC->ITM_UNIT;
				
				// GET ALL BUDGET FROM JOBLIST DETAIL
				$TOT_BUDVOLM	= 0;
				$TOT_BUDAM 		= 0;
				$PRICE_AVG		= 0;
				if($ITM_UNIT == 'LS' || $ITM_UNIT == 'ls')
				{
					$sqlJLD	= "SELECT SUM(ITM_VOLM) AS TOT_BUDVOLM, SUM(ITM_BUDG) AS TOT_BUDAM,
										SUM(ADD_VOLM) AS TOT_ADDVOLM, SUM(ADD_JOBCOST) AS TOT_ADDCOST,
										SUM(ADDM_VOLM) AS TOT_ADDMVOLM, SUM(ADDM_JOBCOST) AS TOT_ADDMCOST
									FROM
										tbl_joblist_detail_$PRJCODEVW
									WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE' AND ITM_UNIT = '$ITM_UNIT'";
					$resJLD	= $this->db->query($sqlJLD)->result();
					foreach($resJLD as $rowJLD) :
						$TOT_BUDVOLM1 	= $rowJLD->TOT_BUDVOLM;
						$TOT_BUDAM1 	= $rowJLD->TOT_BUDAM;
						$TOT_ADDVOLM1 	= $rowJLD->TOT_ADDVOLM;
						$TOT_ADDCOST1 	= $rowJLD->TOT_ADDCOST;
						$TOT_ADDMVOLM1 	= $rowJLD->TOT_ADDMVOLM;
						$TOT_ADDMCOST1 	= $rowJLD->TOT_ADDMCOST;
						$TOT_BUDVOLM	= 1;
						$TOT_BUDAM		= $TOT_BUDAM1 + $TOT_ADDCOST1 - $TOT_ADDMCOST1;
						$TOT_BUDVOLMB 	= $TOT_BUDVOLM;
						if($TOT_BUDVOLMB == 0)
							$TOT_BUDVOLMB= 1;
						$PRICE_AVG		= $TOT_BUDAM / $TOT_BUDVOLM;
					endforeach;
				}
				else
				{
					$sqlJLD	= "SELECT SUM(ITM_VOLM) AS TOT_BUDVOLM, SUM(ITM_BUDG) AS TOT_BUDAM,
										SUM(ADD_VOLM) AS TOT_ADDVOLM, SUM(ADD_JOBCOST) AS TOT_ADDCOST,
										SUM(ADDM_VOLM) AS TOT_ADDMVOLM, SUM(ADDM_JOBCOST) AS TOT_ADDMCOST
									FROM
										tbl_joblist_detail_$PRJCODEVW
									WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE' AND ITM_UNIT = '$ITM_UNIT'";
					$resJLD	= $this->db->query($sqlJLD)->result();
					foreach($resJLD as $rowJLD) :
						$TOT_BUDVOLM1 	= $rowJLD->TOT_BUDVOLM;
						$TOT_BUDAM1 	= $rowJLD->TOT_BUDAM;
						$TOT_ADDVOLM1 	= $rowJLD->TOT_ADDVOLM;
						$TOT_ADDCOST1 	= $rowJLD->TOT_ADDCOST;
						$TOT_ADDMVOLM1 	= $rowJLD->TOT_ADDMVOLM;
						$TOT_ADDMCOST1 	= $rowJLD->TOT_ADDMCOST;
						$TOT_BUDVOLM	= $TOT_BUDVOLM1 + $TOT_ADDVOLM1 - $TOT_ADDMVOLM1;
						$TOT_BUDAM		= $TOT_BUDAM1 + $TOT_ADDCOST1 - $TOT_ADDMCOST1;
						$TOT_BUDVOLMB 	= $TOT_BUDVOLM;
						if($TOT_BUDVOLMB == 0)
							$TOT_BUDVOLMB= 1;
						$PRICE_AVG		= $TOT_BUDAM / $TOT_BUDVOLMB;
					endforeach;
				}
				
				$sqlUpdCOA		= "UPDATE tbl_item SET 
										ITM_VOLMBG = $TOT_BUDVOLM
									WHERE PRJCODE = '$selPRJSYNC' AND ITM_CODE = '$ITM_CODE'";
				$this->db->query($sqlUpdCOA);
			endforeach;
		}
		$isLoadDone	= 0;
		$isSyncDone	= 0;
	?>
	<script>
		function syncJournal()
		{
			document.getElementById('loading_1').style.display = '';
			document.frmsync.submitSYNC.click();
		}

		function setAccIN(collDtItm)
		{
			collDtItm 	= collDtItm.split('~');
			urlSv 		= collDtItm[0];
			PRJCODE 	= collDtItm[1];
			ITM_CODE 	= collDtItm[2];
			ITM_NAME 	= collDtItm[3];
			ACC_ID 		= collDtItm[4];
			ACC_ID_UM 	= collDtItm[5];
			ACC_ID_SAL 	= collDtItm[6];

			$('#ACC_ID').val(ACC_ID).trigger('change');
			$('#ACC_ID_UM').val(ACC_ID_UM).trigger('change');
			$('#ACC_ID_SAL').val(ACC_ID_SAL).trigger('change');

			document.getElementById('PRJCODE').value 		= PRJCODE;
			document.getElementById('ITM_CODE').value 		= ITM_CODE;
			document.getElementById('ITM_CODEV').innerHTML 	= ITM_CODE;
			document.getElementById('ITM_NAME').value 		= ITM_NAME;
			document.getElementById('ITM_NAMEV').innerHTML 	= ITM_NAME;
			document.getElementById('urlSv').value 			= urlSv;
			document.getElementById('btnModal').click();
		}
	</script>
	<?php

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
	    <section class="content-header">
	        <h1>
		        <?php echo $mnName." $PRJCODE"; ?>
		        <small><?php echo $PRJNAME; ?></small>
	        </h1>
	    </section>

		<style>
	        .search-table, td, th {
	            border-collapse: collapse;
	        }
	        .search-table-outter { overflow-x: scroll; }
	    </style>

        <section class="content">
		    <div class="row">
		    	<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<i class="glyphicon glyphicon-list"></i>
							<h3 class="box-title"><?php echo $Grouping ?> </h3>

				          	<div class="box-tools pull-right">
				            	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				            	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
				          	</div>
						</div>
						<div class="box-body">
	                        <div class="form-group">
	                          	<label for="inputName" class="col-sm-1 control-label" style="display: none;"><?php echo $Group ?> </label>
	                          	<div class="col-sm-2">
	                            	<select name="ITM_GROUP" id="ITM_GROUP" class="form-control select2">
	                            		<option value=""> --- </option>
										<?php
											$sqlGRPC	= "tbl_itemgroup";
											$resGRPC	= $this->db->count_all($sqlGRPC);
		                                    if($resGRPC > 0)
		                                    {
												$sql		= "SELECT IG_Num, IG_Code, IG_Name
																FROM tbl_itemgroup ORDER BY ID ASC";
												$viewCateg	= $this->db->query($sql)->result();
		                                        foreach($viewCateg as $row) :
		                                            $IG_Num1	= $row->IG_Num;
		                                            $IG_Code1 	= $row->IG_Code;
		                                            $IG_Name1 	= $row->IG_Name;
		                                            ?>
		                                            <option value="<?php echo $IG_Code1; ?>"><?php echo "$IG_Code1 - $IG_Name1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    }
	                                    ?>
	                                </select>
	                          	</div>
	                          	<div class="col-sm-3">
	                            	<select name="ITM_CATEG" id="ITM_CATEG" class="form-control select2">
	                            		<option value=""> --- </option>
										<?php
											$sqlGC		= "tbl_itemcategory";
											$resGC		= $this->db->count_all($sqlGC);
		                                    if($resGC > 0)
		                                    {
												$sql		= "SELECT IC_Num, IC_Code, IC_Name
																FROM tbl_itemcategory ORDER BY IC_Name ASC";
												$viewCateg	= $this->db->query($sql)->result();
		                                        foreach($viewCateg as $row) :
		                                            $IC_Num1	= $row->IC_Num;
		                                            $IC_Code1 	= $row->IC_Code;
		                                            $IC_Name1 	= $row->IC_Name;
		                                            ?>
		                                            <option value="<?php echo $IC_Code1; ?>"><?php echo "$IC_Code1 - $IC_Name1"; ?></option>
		                                            <?php
		                                        endforeach;
		                                    }
	                                    ?>
	                                </select>
	                          	</div>
	                          	<button class="btn btn-primary" type="button" id="btnGrp" name="btnGrp" onClick="grpItem()">
                            		<i class="glyphicon glyphicon-ok"></i>
                            	</button>
	                        </div>
	                    </div>
	            	</div>
		        </div>
		    </div>
		    <script type="text/javascript">
		    	function grpItem()
		    	{
		    		var ITM_GROUP 	= document.getElementById('ITM_GROUP').value;
		    		var ITM_CATEG 	= document.getElementById('ITM_CATEG').value;
		    		//console.log(ITM_GROUP)
		    		//console.log(ITM_CATEG)
		    		$('#example').DataTable( {
		    			"bDestroy": true,
				        "processing": true,
				        "serverSide": true,
						//"scrollX": false,
						"autoWidth": true,
						"filter": true,
				        "ajax": "<?php echo site_url('c_inventory/c_it180e2elst/get_AllDataGRP/?id='.$PRJCODE)?>"+'&ITMGRP='+ITM_GROUP+'&ITMCAT='+ITM_CATEG,
				        "type": "POST",
						"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
						"columnDefs": [	{ targets: [2,3,4,5,6], className: 'dt-body-right' },
									   	{ targets: [0,7,8], className: 'dt-body-center' },
									   	{ targets: [0], className: 'dt-body-nowrap' },
									   	//{ targets: [2,3,4,5,6,9], orderable: false }
									  ],
						"order": [[ 2, "desc" ]],
						"language": {
				            "infoFiltered":"",
				            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
				        },
				    });
		    	}
		    </script>
		    <div class="box box-success">
		    	<div class="box-body">
		    		<div class="search-table-outter">
		                <form name="frmsync" id="frmsync" action="" method=POST>
		                    <input type="hidden" name="selPRJSYNC" id="selPRJSYNC" value="<?php echo $PRJCODE; ?>">
		                    <input type="submit" name="submitSYNC" id="submitSYNC" style="display:none">
		                </form>
		                <table id="example" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
		                    <thead>
			                    <tr>
			                        <th width="10%" rowspan="2" style="vertical-align:middle; text-align:center" nowrap><?php echo $ItemCode ?> </th>
			                        <th width="50%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $ItemName ?> </th>
			                        <th colspan="5" style="vertical-align:middle; text-align:center">Volume</th>
			                        <!-- <th width="6%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $Price ?> </th> -->
			                        <th width="3%" rowspan="2" style="vertical-align:middle; text-align:center" nowrap>Unit</th>
			                        <th width="2%" rowspan="2" style="vertical-align:middle; text-align:center"><?php echo $Status ?> </th>
			                        <th width="3%" rowspan="2" style="vertical-align:middle; text-align:center">&nbsp;</th>
			                    </tr>
			                    <tr>
			                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $BudgetQty; ?> </th>
			                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Ordered; ?> </th>
			                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $ReceiptQty; ?></th>
			                        <th width="5%" style="vertical-align:middle; text-align:center" nowrap><?php echo $Used; ?></th>
			                        <th width="6%" style="vertical-align:middle; text-align:center" title="Stock in Warehouse" nowrap><?php echo $OnHand; ?> </th>
			                    </tr>
		                    </thead>
		                    <tbody>
		                    </tbody>
		                    <tfoot>
		                    </tfoot>
		                </table>
		            </div>
		            <br>
                    <?php
						$collData	= "$PRJCODE~$PRJPERIOD";
                        $secAddURL = site_url('c_inventory/c_it180e2elst/a180e2edd/?id='.$this->url_encryption_helper->encode_url($collData));
                        if($ISCREATE == 1)
                        {								
                            echo anchor("$secAddURL",'<button class="btn btn-primary" title="'.$Add.'"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;&nbsp;');
                            //echo anchor("$secAdd",'<button class="btn btn-warning" title="'.$Upload.'"><i class="glyphicon glyphicon-import"></i></button>&nbsp;&nbsp;');
                            echo anchor("$backURL",'<button class="btn btn-danger" title="'.$Back.'"><i class="fa fa-reply"></i></button>');
                        }
                        if($DefEmp_ID == 'D15040004221')
                        {
                            echo '&nbsp;&nbsp;<button class="btn btn-info" onClick="syncJournal()" title="Sync"><i class="glyphicon glyphicon-refresh"></i></button>';
                        }
                    ?>
				</div>
		    </div>
		    <div id="loading_1" class="overlay" style="display:none">
		        <i class="fa fa-refresh fa-spin"></i>
		    </div>

		    <a class="btn btn-sm btn-warning" data-toggle="modal" data-target="#mdl_addItm" id="btnModal" style="display: none;">
        		<i class="glyphicon glyphicon-search"></i>
        	</a>
        	<input type="hidden" name="urlSv" id="urlSv">

	    	<!-- ============ START MODAL ITEM =============== -->
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
			
					$LangID 	= $this->session->userdata['LangID'];
					if($LangID == 'IND')
					{
						$setAcc 	= "Pengaturan Akun Item";
					}
					else
					{
						$setAcc 	= "Item Account Setting";
					}
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab"><?php echo $setAcc; ?></a>
						                    </li>
						                </ul>
							            <div class="box-body">
	                                    	<form method="post" name="frmSearch1" id="frmSearch1" action="">
	                                        	<div class="row">
							                        <div class="box box-success">
							                            <div class="box-body">
										                    <div class="col-sm-5">
										                    	<label for="inputName" class="control-label"><?php echo "$ItemCode"; ?></label>
										                    	<input type="hidden" class="form-control" name="PRJCODE" id="PRJCODE" value="" />
										                    	<input type="hidden" class="form-control" name="ITM_CODE" id="ITM_CODE" value="" />
										                    	<div id="ITM_CODEV"></div>
										                    </div>
										                    <div class="col-sm-7">
										                    	<label for="inputName" class="control-label"><?php echo "$ItemName"; ?></label>
										                    	<div id="ITM_NAMEV"></div>
										                    	<input type="hidden" class="form-control" name="ITM_NAME" id="ITM_NAME" value="" />
										                    	<input type="hidden" class="form-control" name="ITM_NAME" id="ITM_NAME" value="" />
										                    </div>
															<?php
									                            $s_ac	= "tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accIN) AND PRJCODE = '$PRJCODE'";
									                            $r_ac 	= $this->db->count_all($s_ac);
									                            
									                            $s_av	= "SELECT Acc_ID, Account_Number, Account_Level,
								                            					Account_NameEn, Account_NameId, Acc_ParentList,
																				Acc_DirParent, isLast
								                                            FROM tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accIN) AND PRJCODE = '$PRJCODE'
								                                            	ORDER BY ORD_ID";
									                            $r_av 	= $this->db->query($s_av)->result();

									                            $s_bc	= "tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accUM) AND PRJCODE = '$PRJCODE'";
									                            $r_bc 	= $this->db->count_all($s_bc);
									                            
									                            $s_bv	= "SELECT Acc_ID, Account_Number, Account_Level,
								                            					Account_NameEn, Account_NameId, Acc_ParentList,
																				Acc_DirParent, isLast
								                                            FROM tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accUM) AND PRJCODE = '$PRJCODE'
								                                            	ORDER BY ORD_ID";
									                            $r_bv 	= $this->db->query($s_bv)->result();
									                        ?>
										                    <div class="col-sm-12">
										                    	<label for="inputName" class="control-label">&nbsp;</label>
										                    </div>
										                    <div class="col-sm-12">
										                    	<label for="inputName" class="control-label"><?php echo "$recAcc"; ?></label>
										                    	<select name="ACC_ID" id="ACC_ID" class="form-control select2" style="width: 100%">
								                        			<option value="" > --- </option>
								                                    <?php
																	if($r_ac>0)
																	{
																		foreach($r_av as $rw_av) :
																			$Acc_ID0		= $rw_av->Acc_ID;
																			$Account_Number0= $rw_av->Account_Number;
																			$Acc_DirParent0	= $rw_av->Acc_DirParent;
																			$Account_Level0	= $rw_av->Account_Level;
																			if($LangID == 'IND')
																			{
																				$Account_Name0	= $rw_av->Account_NameId;
																			}
																			else
																			{
																				$Account_Name0	= $rw_av->Account_NameEn;
																			}
																			
																			$Acc_ParentList0	= $rw_av->Acc_ParentList;
																			$isLast_0			= $rw_av->isLast;
																			$disbaled_0			= 0;
																			if($isLast_0 == 0)
																				$disbaled_0		= 1;
																				
																			if($Account_Level0 == 0)
																				$level_coa1			= "";
																			elseif($Account_Level0 == 1)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 2)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 3)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 4)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 5)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 6)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 7)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			
																			$collData0	= "$Account_Number0";
																			?>
																				<option value="<?php echo $Account_Number0; ?>"><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
																			<?php
																		endforeach;
																	}
																	?>
								                                </select>
										                    </div>
										                    <div class="col-sm-12">
										                    	<label for="inputName" class="control-label">&nbsp;</label>
										                    </div>
										                    <div class="col-sm-12">
										                    	<label for="inputName" class="control-label"><?php echo "$UsedAcc"; ?></label>
										                    	<select name="ACC_ID_UM" id="ACC_ID_UM" class="form-control select2" style="width: 100%">
								                        			<option value="" > --- </option>
								                                    <?php
																	if($r_bc>0)
																	{
																		foreach($r_bv as $rw_bv) :
																			$Acc_ID0		= $rw_bv->Acc_ID;
																			$Account_Number0= $rw_bv->Account_Number;
																			$Acc_DirParent0	= $rw_bv->Acc_DirParent;
																			$Account_Level0	= $rw_bv->Account_Level;
																			if($LangID == 'IND')
																			{
																				$Account_Name0	= $rw_bv->Account_NameId;
																			}
																			else
																			{
																				$Account_Name0	= $rw_bv->Account_NameEn;
																			}
																			
																			$Acc_ParentList0	= $rw_bv->Acc_ParentList;
																			$isLast_0			= $rw_bv->isLast;
																			$disbaled_0			= 0;
																			if($isLast_0 == 0)
																				$disbaled_0		= 1;
																				
																			if($Account_Level0 == 0)
																				$level_coa1			= "";
																			elseif($Account_Level0 == 1)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 2)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 3)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 4)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 5)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 6)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 7)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			
																			$collData0	= "$Account_Number0";
																			?>
																				<option value="<?php echo $Account_Number0; ?>"><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
																			<?php
																		endforeach;
																	}
																	?>
								                                </select>
										                    </div>
										                    <div class="col-sm-12">
										                    	<label for="inputName" class="control-label">&nbsp;</label>
										                    </div>
										                    <div class="col-sm-12" style="display: none;">
										                    	<label for="inputName" class="control-label"><?php echo "$UsedAcc"; ?></label>
										                    	<select name="ACC_ID_SAL" id="ACC_ID_SAL" class="form-control select2" style="width: 100%">
								                        			<option value="" > --- </option>
								                                    <?php
																	if($r_bc>0)
																	{
																		foreach($r_av as $rw_bv) :
																			$Acc_ID0		= $rw_bv->Acc_ID;
																			$Account_Number0= $rw_bv->Account_Number;
																			$Acc_DirParent0	= $rw_bv->Acc_DirParent;
																			$Account_Level0	= $rw_bv->Account_Level;
																			if($LangID == 'IND')
																			{
																				$Account_Name0	= $rw_bv->Account_NameId;
																			}
																			else
																			{
																				$Account_Name0	= $rw_bv->Account_NameEn;
																			}
																			
																			$Acc_ParentList0	= $rw_bv->Acc_ParentList;
																			$isLast_0			= $rw_bv->isLast;
																			$disbaled_0			= 0;
																			if($isLast_0 == 0)
																				$disbaled_0		= 1;
																				
																			if($Account_Level0 == 0)
																				$level_coa1			= "";
																			elseif($Account_Level0 == 1)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 2)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 3)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 4)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 5)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 6)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			elseif($Account_Level0 == 7)
																				$level_coa1			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
																			
																			$collData0	= "$Account_Number0";
																			?>
																				<option value="<?php echo $Account_Number0; ?>"><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
																			<?php
																		endforeach;
																	}
																	?>
								                                </select>
										                    </div>
							                            </div>
							                        </div>
							                    </div>

	                                        	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
	                                        		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;OK
	                                        	</button>&nbsp;
	                          					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
	                                        		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
	                                        	</button>
	                                        </form>
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
					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
							var urlSv 		= $("#urlSv").val(); 
							var PRJCODE 	= $("#PRJCODE").val();
							var ITM_CODE 	= $("#ITM_CODE").val(); 
							var ACC_ID 		= $("#ACC_ID").val(); 
							var ACC_ID_UM 	= $("#ACC_ID_UM").val();
							var ACC_ID_SAL	= $("#ACC_ID_SAL").val();
							var dataString 	= 'PRJCODE='+PRJCODE+'&ITM_CODE='+ITM_CODE+'&ACC_ID='+ACC_ID+'&ACC_ID_UM='+ACC_ID_UM+'&ACC_ID_SAL='+ACC_ID_SAL;
							$.ajax({
			                    type: 'POST',
			                    url: urlSv,
			                    data: dataString,
			                    success: function(response)
			                    {
			                        swal(response, 
			                        {
			                            icon: "success",
			                        });
			                        $('#example').DataTable().ajax.reload();
			                    }
			                });

                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL ITEM =============== -->

            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		    
		    <?php 
				$isLoadDone = 1;
				if($isLoadDone == 1)
				{
					?>
						<script>
							document.getElementById('loading_1').style.display = 'none';
		                </script>
		    		<?php
				}
			?> 
		</section>
	</body>
</html>

<script>
	$(function ()
	{
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
	    $('#datepicker1').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker2').datepicker({
	      autoclose: true
	    });

	    //Date picker
	    $('#datepicker3').datepicker({
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

	$(document).ready(function() {
	    $('#example').DataTable( {
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        "ajax": "<?php echo site_url('c_inventory/c_it180e2elst/get_AllData/?id='.$PRJCODE)?>",
	        "type": "POST",
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [2,3,4,5,6], className: 'dt-body-right' },
						   	{ targets: [0,7,8], className: 'dt-body-center' },
						   	{ targets: [0], className: 'dt-body-nowrap' },
						   	//{ targets: [2,3,4,5,6,9], orderable: false }
						  ],
			"order": [[ 2, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
	    });
	});
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