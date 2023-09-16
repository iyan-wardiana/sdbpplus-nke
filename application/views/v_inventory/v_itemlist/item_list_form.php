<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 5 April 2017
	* File Name		= item_list_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$PRJSCATEG 	= $this->session->userdata['PRJSCATEG'];

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

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

if($task == 'add')
{
	$PRJCODE		= $PRJCODE;
	$ITM_GROUP 		= 'MF';
	$ITM_CATEG 		= 'MF';
	$ITM_REFR		= '';
	$ITM_NAME		= '';
	$ITM_DESC 		= '';
	$ITM_TYPE1 		= '';
	$ITM_UNIT 		= 'LS';
	$ITM_CURRENCY 	= "IDR";
	$ITM_PRICE		= 0;
	$ITM_VOLMBG		= 0;
	$ITM_VOLM 		= 0;
	$ADDVOLM 		= 0;
	$ADDMVOLM 		= 0;
	$ISMTRL			= 0;
	$ISRENT			= 0;
	$ISPART 		= 0;
	$ISFUEL 		= 0;
	$ISLUBRIC 		= 0;
	$ISFASTM 		= 0;
	$ISWAGE 		= 0;
	$ISRM 			= 0;
	$ISWIP 			= 0;
	$ISFG 			= 0;
	$ISRIB 			= 0;
	$ISCOST			= 0;
	$NEEDQRC		= 0;
	$ACC_ID			= 0;
	$ACC_ID_UM		= 0;
	$ACC_ID_SAL		= 0;
	$ITM_LR			= 0;
	$THEYEAR		= date('y');
	
	$STATUS			= 1;
	
	//$ITM_TYPE	= "$ITM_CATEG$THEYEAR"; change on 2018-07-07
	// ke Primary or Substitute/ Pengganti
	$ITM_TYPE	= "PRM";

	$ITM_CODE_H	= '';
	$JOBCODEID	= '';
	$ITM_IN		= 0;
	$ISMAJOR	= 0;
	$ITM_CODE 	= "";
	$ITM_CODEN 	= "";
	$PITM_CODE 	= "";
	$LASTNO 	= 0;
}
else
{
	$PRJPERIOD 		= $default['PRJPERIOD'];
	$ITM_CODE 		= $default['ITM_CODE'];
	$ITM_CODEN 		= $ITM_CODE;
	$ITM_CODE_H 	= $default['ITM_CODE_H'];
	$PITM_CODE 		= $default['PITM_CODE'];
	$JOBCODEID 		= $default['JOBCODEID'];
	$ITM_NAME 		= $default['ITM_NAME'];
	$ITM_GROUP 		= $default['ITM_GROUP'];
	$ITM_CATEG 		= $default['ITM_CATEG'];
	$ITM_DESC 		= $default['ITM_DESC'];
	$ITM_TYPE 		= $default['ITM_TYPE'];
	$ITM_UNIT 		= strtoupper($default['ITM_UNIT']);
	$ITM_CURRENCY	= $default['ITM_CURRENCY'];
	$ITM_VOLMBG		= $default['ITM_VOLMBG'];
	$ITM_VOLM 		= $default['ITM_VOLM'];
	$ADDVOLM 		= $default['ADDVOLM'];
	$ADDMVOLM 		= $default['ADDMVOLM'];
	$ITM_IN 		= $default['ITM_IN'];
	$ITM_OUT 		= $default['ITM_OUT'];
	$ITM_PRICE		= $default['ITM_PRICE'];
	if($ITM_PRICE < 100)
	{
		$sqlITM 	= "SELECT ITM_PRICE FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE' LIMIT 1";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $rowITM):
			$ITM_PRICE	= $rowITM->ITM_PRICE;
		endforeach;
	}
	
	$ACC_ID			= $default['ACC_ID'];
	$ACC_ID_UM		= $default['ACC_ID_UM'];
	$ACC_ID_SAL		= $default['ACC_ID_SAL'];
	$UMCODE 		= $default['UMCODE'];
	$Unit_Type_Name = $default['Unit_Type_Name'];
	$UMCODE 		= $default['UMCODE'];
	$STATUS 		= $default['STATUS'];
	$ISMTRL 		= $default['ISMTRL'];
	$ISRENT 		= $default['ISRENT'];
	$ISPART 		= $default['ISPART'];
	$ISFUEL 		= $default['ISFUEL'];
	$ISLUBRIC 		= $default['ISLUBRIC'];
	$ISFASTM 		= $default['ISFASTM'];
	$ISWAGE 		= $default['ISWAGE'];
	$ISRM 			= $default['ISRM'];
	$ISWIP 			= $default['ISWIP'];
	$ISFG 			= $default['ISFG'];
	$ISRIB 			= $default['ISRIB'];
	$ISCOST			= $default['ISCOST'];
	$NEEDQRC		= $default['NEEDQRC'];
	$ITM_KIND 		= $default['ITM_KIND'];
	$ITM_LR 		= $default['ITM_LR'];
	$LASTNO 		= $default['LASTNO'];
	$PRJCODE 		= $default['PRJCODE'];
	$ISMAJOR 		= $default['ISMAJOR'];
}
$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

if(isset($_POST['ITM_CATEGX']))
{
	$ITM_CATEG 	= $_POST['ITM_CATEGX'];
	$PITM_CODE 	= $_POST['PITM_CODEX'];

	$s_GRP 		= "SELECT IG_Code FROM tbl_itemcategory WHERE IC_Num = '$ITM_CATEG'";
	$r_GRP		= $this->db->query($s_GRP)->result();
	foreach($r_GRP as $rw_GRP):
		$ITM_GROUP	= $rw_GRP->IG_Code;
	endforeach;

	$MAX_PATT	= 0;
	$s_LPATT	= "tbl_item_$PRJCODEVW WHERE PITM_CODE = '$PITM_CODE' AND PRJCODE = '$PRJCODE'";
	$MAX_PATT	= $this->db->count_all($s_LPATT);
	$MAX_PATT 	= $MAX_PATT+1;

	$len 		= strlen($MAX_PATT);
	
	if($len==1) $nol="00";
	else if($len==2) $nol="0";
	else if($len==3) $nol="";
	else $nol="";

	$ITM_CODEN	= "$PITM_CODE$nol$MAX_PATT";
	if($PITM_CODE == '')
		$ITM_CODEN	= "";

	$LASTNO 	= $MAX_PATT;
}

// START : CHECK TRANSACTION
	$TOTTRX = 0;
	$CANUPD = 0;
	$s_TRX 	= "SELECT COUNT(*) AS TOT_TRX FROM tbl_pr_detail WHERE ITM_CODE = '$ITM_CODEN' AND PR_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_po_detail WHERE ITM_CODE = '$ITM_CODEN' AND PO_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_ir_detail WHERE ITM_CODE = '$ITM_CODEN' AND IR_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_wo_detail WHERE ITM_CODE = '$ITM_CODEN' AND WO_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_opn_detail WHERE ITM_CODE = '$ITM_CODEN' AND OPNH_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_journaldetail_pd WHERE ITM_CODE = '$ITM_CODEN' AND GEJ_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_journaldetail_cprj WHERE ITM_CODE = '$ITM_CODEN' AND GEJ_STAT NOT IN (5,9)
				UNION ALL
				SELECT COUNT(*) AS TOT_TRX FROM tbl_journaldetail_vcash WHERE ITM_CODE = '$ITM_CODEN' AND GEJ_STAT NOT IN (5,9)";
	$r_TRX 	= $this->db->query($s_TRX)->result();
	foreach($r_TRX as $rw_TRX):
		$TOT_TRX 	= $rw_TRX->TOT_TRX;
		$TOTTRX 	= $TOTTRX+$TOT_TRX;
	endforeach;
	if($TOTTRX == 0)
	{
		$CANUPD 	= 1;
		$ITM_CODE 	= $ITM_CODEN;
	}
	if($task == 'add')
		$CANUPD 	= 1;
// END : CHECK TRANSACTION

// Project List
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

/*$sqlPL 	= "SELECT PRJCODE, PRJNAME
			FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
			ORDER BY PRJNAME";
$resPL	= $this->db->query($sqlPL)->result();*/
$PRJLEV = 2;
$sqlPL 	= "SELECT PRJCODE, PRJNAME, PRJLEV FROM tbl_project WHERE PRJCODE = '$PRJCODE'
			ORDER BY PRJNAME";
$resPL	= $this->db->query($sqlPL)->result();
foreach($resPL as $rowPL1):
	$PRJCODE	= $rowPL1->PRJCODE;
	$PRJNAME	= $rowPL1->PRJNAME;
	$PRJLEV		= $rowPL1->PRJLEV;
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
			if($TranslCode == 'Group')$Group = $LangTransl;
			if($TranslCode == 'Category')$Category = $LangTransl;
			if($TranslCode == 'Type')$Type = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Currency')$Currency = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'ItemPrice')$ItemPrice = $LangTransl;
			if($TranslCode == 'Volume')$Volume = $LangTransl;
			if($TranslCode == 'Kind')$Kind = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Substitute')$Substitute = $LangTransl;
			if($TranslCode == 'ItemPrimary')$ItemPrimary = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'UsedAcc')$UsedAcc = $LangTransl;
			if($TranslCode == 'Sales')$Sales = $LangTransl;
			if($TranslCode == 'RentExpenses')$RentExpenses = $LangTransl;
			if($TranslCode == 'Expenses')$Expenses = $LangTransl;
			if($TranslCode == 'Part')$Part = $LangTransl;
			if($TranslCode == 'Fuel')$Fuel = $LangTransl;
			if($TranslCode == 'Lubricants')$Lubricants = $LangTransl;
			if($TranslCode == 'FastMove')$FastMove = $LangTransl;
			if($TranslCode == 'Wage')$Wage = $LangTransl;
			if($TranslCode == 'Active')$Active = $LangTransl;
			if($TranslCode == 'Inactive')$Inactive = $LangTransl;
			if($TranslCode == 'Material')$Material = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'Cancel')$Cancel = $LangTransl;
			if($TranslCode == 'RawMtr')$RawMtr = $LangTransl;
			if($TranslCode == 'FinGoods')$FinGoods = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert0		= 'Item induk tidak boleh kosong';
			$alert1		= 'Kode item tidak boleh kosong';
			$alert2		= 'Nama item tidak boleh kosong';
			$alert3		= 'Unit item tidak boleh kosong';
			$alert4		= 'Harga item tidak boleh kosong';
			$alert5		= 'Silahkan tentukan akun penerimaan.';
			$alert6		= 'Silahkan tentukan akun penggunaan.';
			$alert7		= 'Silahkan tentukan kelompok laporan Laba Rugi.';
			$alert8		= 'Grup Item tidak boleh kosong';
			$sureResPrc	= 'Sistem akan mengatur ulang harga di seluruh RAP. Yakin?';
		}
		else
		{
			$alert0		= 'Item parent can not be empty';
			$alert1		= 'Item code can not be empty.';
			$alert2		= 'Item name can not be empty.';
			$alert3		= 'Item unit can not be empty.';
			$alert4		= 'Item price can not be empty.';
			$alert5		= 'Please select a receipt account.';
			$alert6		= 'Please select an used account.';
			$alert7		= 'Please select a group of LR Report.';
			$alert8		= 'Item group parent can not be empty';
			$sureResPrc	= 'System will reset all price in RAP. Are you sure?';
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <?php echo $AddItem; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">
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
		    <div class="row">
                <div class="box-header with-border" style="display:none">               
              		<div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            	<form name="frmsrch" id="frmsrch" action="" method=POST style="display: none;" >
                    <input type="text" name="ITM_GROUPX" id="ITM_GROUPX" class="textbox"value="<?php echo $ITM_GROUP; ?>" />
                    <input type="text" name="ITM_CATEGX" id="ITM_CATEGX" class="textbox"value="<?php echo $ITM_CATEG; ?>" />
                    <input type="text" name="PITM_CODEX" id="PITM_CODEX" class="textbox"value="<?php echo $PITM_CODE; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
            	<form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveItem()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
                    			<input type="hidden" name="ITM_CODEBEF" id="ITM_CODEBEF" value="<?php echo $ITM_CODE; ?>" />
		                    	<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
		                    	<input type="hidden" name="PRJPERIOD" id="PRJPERIOD" value="<?php echo $PRJPERIOD; ?>" />
		                    	<input type="hidden" name="ITM_GROUP" id="ITM_GROUP" value="<?php echo $ITM_GROUP; ?>" />
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Group ?> </label>
		                          	<div class="col-sm-4">
		                                <?php if($CANUPD == 1) { ?>
			                            	<select name="ITM_CATEG" id="ITM_CATEG" class="form-control select2" onChange="chooseCategory()">
												<option value="">---</option>
												<?php
													// $sqlGC		= "tbl_itemcategory";
													$sqlGC		= "tbl_itemgroup";
													$resGC		= $this->db->count_all($sqlGC);
				                                    if($resGC > 0)
				                                    {
														// $sql		= "SELECT IC_Num, IC_Code, IC_Name
														// 				FROM tbl_itemcategory ORDER BY IC_Name ASC";
														$sql		= "SELECT IG_Num, IG_Code, IG_Name
																		FROM tbl_itemgroup ORDER BY field(IG_Code, 'M','U','S','T','I','R','O')";
														$viewCateg	= $this->db->query($sql)->result();
				                                        foreach($viewCateg as $row) :
				                                            // $IC_Num1	= $row->IC_Num;
															$IG_Num1	= $row->IG_Num;
				                                            // $IC_Code1 	= $row->IC_Code;
															$IG_Code1 	= $row->IG_Code;
				                                            // $IC_Name1 	= $row->IC_Name;
															$IG_Name1 	= $row->IG_Name;
				                                            ?>
															<option value="<?php echo $IG_Code1; ?>" <?php if($IG_Code1 == $ITM_CATEG) { ?> selected <?php } ?>><?php echo "$IG_Code1 - $IG_Name1"; ?></option>
				                                            <?php
				                                        endforeach;
				                                    }
				                                    else
				                                    {
				                                        ?>
				                                            <option value="none"> --- </option>
				                                        <?php
				                                    }
			                                    ?>
			                                </select>
		                                <?php } else { ?>
		                    				<input type="hidden" name="ITM_CATEG" id="ITM_CATEG" value="<?php echo $ITM_CATEG; ?>" />
			                            	<select name="ITM_CATEG1" id="ITM_CATEG1" class="form-control select2" disabled>
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
				                                            <option value="<?php echo $IC_Code1; ?>" <?php if($IC_Code1 == $ITM_CATEG) { ?> selected <?php } ?>><?php echo "$IC_Code1 - $IC_Name1"; ?></option>
				                                            <?php
				                                        endforeach;
				                                    }
				                                    else
				                                    {
				                                        ?>
				                                            <option value="none"> --- </option>
				                                        <?php
				                                    }
			                                    ?>
			                                </select>
		                                <?php } ?>
		                          	</div>
		                          	<div class="col-sm-5">
		                            	<select name="PITM_CODE" id="PITM_CODE" class="form-control select2" onChange="chooseParent()">
		                            		<option value=""> --- </option>
											<?php
												$sqlGC		= "tbl_item_parent WHERE ITM_GROUP = '$ITM_CATEG'";
												$resGC		= $this->db->count_all($sqlGC);
			                                    if($resGC > 0)
			                                    {
													$s_HDR		= "SELECT * FROM tbl_item_parent WHERE ITM_GROUP = '$ITM_CATEG' ORDER BY PITM_CODE ASC";
													$r_HDR	= $this->db->query($s_HDR)->result();
			                                        foreach($r_HDR as $rw_HDR) :
			                                            $PITM_CODE1		= $rw_HDR->PITM_CODE;
			                                            $PITM_ORD 		= $rw_HDR->PITM_ORD;
			                                            $PITM_GROUP 	= $rw_HDR->PITM_GROUP;
			                                            $PITM_LEVEL 	= $rw_HDR->PITM_LEVEL;
			                                            $PITM_NAME 		= $rw_HDR->PITM_NAME;
			                                            ?>
			                                            <option value="<?php echo $PITM_CODE1; ?>" <?php if($PITM_CODE1 == $PITM_CODE) { ?> selected <?php } ?>><?php echo "$PITM_CODE1 - $PITM_NAME"; ?></option>
			                                            <?php
			                                        endforeach;
			                                    }
		                                    ?>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Type ?> </label>
		                          	<div class="col-sm-9">
		                            	<?php if($CANUPD == 1) { ?>
			                            	<select name="ITM_TYPE" id="ITM_TYPE" class="form-control select2" onChange="chgType(this.value)">
			                                	<option value="PRM" <?php if($ITM_TYPE == "PRM") { ?> selected <?php } ?>><?php echo $Primary; ?></option> 
			                                	<option value="SUBS" <?php if($ITM_TYPE == "SUBS") { ?> selected <?php } ?>><?php echo $Substitute; ?></option>
			                                </select>
		                                <?php } else { ?>
		                                	<input type="hidden" class="form-control" style="max-width:200px" name="ITM_TYPE" id="ITM_TYPE" value="<?php echo $ITM_TYPE; ?>" />
			                            	<select name="ITM_TYPE1" id="ITM_TYPE1" class="form-control select2" disabled>
			                                	<option value="PRM" <?php if($ITM_TYPE == "PRM") { ?> selected <?php } ?>><?php echo $Primary; ?></option> 
			                                	<option value="SUBS" <?php if($ITM_TYPE == "SUBS") { ?> selected <?php } ?>><?php echo $Substitute; ?></option>
			                                </select>
		                                <?php } ?>
		                                <input type="hidden" class="textbox" name="ItemCodeCat" id="ItemCodeCat" size="3" value="<?php echo $ITM_CATEG; ?>" />
		                                <input type="hidden" class="textbox" name="LASTNO" id="LASTNO" size="5" value="<?php echo $LASTNO; ?>" />
		                          	</div>
		                        </div>
		                        <script>
									function chgType(thisValue)
									{
										if(thisValue == 'PRM')
											document.getElementById('ITMCODEH').style.display = 'none';
										else
											document.getElementById('ITMCODEH').style.display = '';								
									}
								</script>
		                        <div id="ITMCODEH" class="form-group" <?php if($ITM_TYPE == "PRM") { ?> style=" display:none" <?php } ?>>
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $ItemPrimary ?> </label>
		                            <div class="col-sm-9">
		                                <div class="input-group">
		                                    <div class="input-group-btn">
		                                        <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
		                                    </div>
		                                    <input type="text" class="form-control" name="ITM_CODE_H" id="ITM_CODE_H" value="<?php echo $ITM_CODE_H; ?>" onClick="pleaseCheck();">
		                                    <input type="hidden" class="form-control" name="JOBCODEID" id="JOBCODEID" style="max-width:180px" value="<?php echo $JOBCODEID; ?>" >
		                                </div>
		                            </div>
		                        </div>
		                        <?php
									$COLLID			= "$PRJCODE~$ITM_GROUP";
									$url_SelItem	= site_url('c_inventory/c_it180e2elst/popupallitem/?id='.$this->url_encryption_helper->encode_url($COLLID));
								?>
		                        <script>
									var url1 = "<?php echo $url_SelItem;?>";
									function pleaseCheck()
									{
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
		                                <select name="PRJCODE1" id="PRJCODE1" class="form-control" onChange="chooseProject()" disabled>
		                                	<option value="<?php echo $PRJCODE; ?>" selected><?php echo "$PRJCODE - $PRJNAME"; ?></option>
		                                  <?php /*?><?php
		                                        if($resPLC > 0)
		                                        {
		                                            foreach($resPL as $rowPL) :
		                                                $proj_ID1 = $rowPL->proj_ID;
		                                                $PRJCODE1 = $rowPL->PRJCODE;
		                                                $PRJNAME1 = $rowPL->PRJNAME;
		                                                ?>
		                                  				<option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE1 - $PRJNAME1"; ?></option>
		                                  <?php
		                                            endforeach;
		                                        }
		                                        else
		                                        {
		                                            ?>
		                                  				<option value="none">--- No Project Found ---</option>
		                                  <?php
		                                        }
		                                        ?><?php */?>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ItemCode ?> </label>
		                          	<div class="col-sm-9">
		                            	<?php if($CANUPD == 1) { ?>
		                                	<input type="text" class="form-control" name="ITM_CODE" id="ITM_CODE" value="<?php echo $ITM_CODE; ?>" />
		                                <?php } else { ?>
		                                	<input type="hidden" class="form-control" style="max-width:200px" name="ITM_CODE" id="ITM_CODE" value="<?php echo $ITM_CODE; ?>" />
		                                	<input type="text" class="form-control" name="ITM_CODE1" id="ITM_CODE1" value="<?php echo $ITM_CODE; ?>" disabled />
		                                <?php } ?>
		                                <input type="hidden" class="textbox" name="ItemCodeCat" id="ItemCodeCat" size="3" value="<?php echo $ITM_CATEG; ?>" />
		                                <input type="hidden" class="textbox" name="LASTNO" id="LASTNO" size="5" value="<?php echo $LASTNO; ?>" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $ItemName ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="ITM_NAME" id="ITM_NAME" value="<?php echo $ITM_NAME;?>" title="Input Item Name" />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Description ?> </label>
		                          	<div class="col-sm-9">
		                                <textarea class="form-control" name="ITM_DESC"  id="ITM_DESC" style="height:75px"><?php echo $ITM_DESC; ?></textarea>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Currency ?> </label>
		                          	<div class="col-sm-9">
		                    			<input type="text" class="form-control" style="max-width:70px; display:none" name="ITM_CURRENCY" id="ITM_CURRENCY" value="<?php echo $ITM_CURRENCY; ?>" title="<?php echo $ITM_CURRENCY; ?>" />
		                    			<input type="text" class="form-control" style="max-width:70px" name="ITM_CURRENCY1" id="ITM_CURRENCY1" value="<?php echo $ITM_CURRENCY; ?>" title="<?php echo $ITM_CURRENCY; ?>" disabled />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Unit ?> / <?php echo $Price ?> </label>
		                          	<div class="col-sm-4">
		                            	<?php if($CANUPD == 1) { ?>
			                            	<select name="ITM_UNIT" id="ITM_UNIT" class="form-control select2">
			                                    <option value="0"> --- </option>
			                                    <?php
													if($recUType > 0)
													{
														foreach($viewUnit as $row) :
															$Unit_Type_Code = $row->Unit_Type_Code;
															$UMCODE 		= strtoupper($row->Unit_Type_Code);
															$Unit_Type_Name	= $row->Unit_Type_Name;
															?>
															<option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $ITM_UNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
															<?php
														endforeach;
													}
													else
													{
														?>
															<option value="none">--- No Unit Found ---</option>
														<?php
													}
												?>
			                                </select>
		                                <?php } else { ?>
		                                	<input type="hidden" class="form-control" style="max-width:200px" name="ITM_UNIT" id="ITM_UNIT" value="<?php echo $ITM_UNIT; ?>" />
			                            	<select name="ITM_UNIT1" id="ITM_UNIT1" class="form-control select2" disabled>
			                                    <option value="0"> --- </option>
			                                    <?php
													if($recUType > 0)
													{
														foreach($viewUnit as $row) :
															$Unit_Type_Code = $row->Unit_Type_Code;
															$UMCODE 		= strtoupper($row->Unit_Type_Code);
															$Unit_Type_Name	= $row->Unit_Type_Name;
															?>
															<option value="<?php echo $Unit_Type_Code; ?>" <?php if($UMCODE == $ITM_UNIT) { ?> selected <?php } ?>><?php echo $Unit_Type_Name; ?></option>
															<?php
														endforeach;
													}
													else
													{
														?>
															<option value="none">--- No Unit Found ---</option>
														<?php
													}
												?>
			                                </select>
		                                <?php } ?>
		                          	</div>
		                          	<div class="col-sm-5">
		                    			<input type="hidden" class="textbox" style="text-align:right" name="ITM_PRICE" id="ITM_PRICE" size="10" value="<?php  echo $ITM_PRICE;?>"  />
		                    			<?php $CANUPD 	= 1;
		                    				if($CANUPD == 1){ ?>
		                    				<div class="input-group">
			                            		<input type="text" class="form-control" style="text-align:right;" name="ITM_PRICE1" id="ITM_PRICE1" size="10" value="<?php  echo number_format($ITM_PRICE, $decFormat);?>" onBlur="changePRICE(this)" />
		                                		<div class="input-group-btn">
													<button type="button" class="btn btn-warning" onClick="resPrice()" title="Reset Price"><i class="glyphicon glyphicon-refresh"></i></button>
		                                    	</div>
			                            	</div>
		                                <?php } else { ?>
		                    				<div class="input-group">
		                                		<input type="text" class="form-control" style="text-align:right;" name="ITM_PRICE1" id="ITM_PRICE1" size="10" value="<?php  echo number_format($ITM_PRICE, $decFormat);?>" disabled />
		                                		<div class="input-group-btn">
													<button class="btn btn-warning" title="Reset Harga" disabled><i class="glyphicon glyphicon-refresh"></i></button>
		                                    	</div>
		                                	</div>
		                                <?php } ?>
		                    			<input type="hidden" class="textbox" style="text-align:right" name="ITM_PRICE2" id="ITM_PRICE2" size="10" value="<?php  echo $ITM_PRICE;?>"  />
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo "$BudgetQty / Vol."; ?></label>
		                          	<div class="col-sm-5">
		                            	<input type="text" class="form-control" style="text-align:right;" name="ITM_VOLMBG1" id="ITM_VOLMBG1" size="10" value="<?php  echo number_format($ITM_VOLMBG, $decFormat);?>" onBlur="changeVOLMBG(this)" title="<?=$BudgetQty?>" <?php if($ITM_IN > 0) { ?> disabled <?php } ?> />
		                    			<input type="hidden" class="textbox" style="text-align:right" name="ITM_VOLMBG" id="ITM_VOLMBG" size="10" value="<?php  echo $ITM_VOLMBG;?>" />
		                          	</div>
		                          	<div class="col-sm-4">
		                            	<input type="text" class="form-control" style="text-align:right;" name="ITM_VOLM1" id="ITM_VOLM1" value="<?php  echo number_format($ITM_VOLM, $decFormat);?>" onBlur="changeVOLM(this)" title="<?=$Volume?>" <?php if($ITM_IN > 0) { ?> disabled <?php } ?> />
		                    			<input type="hidden" class="textbox" style="text-align:right" name="ITM_VOLM" id="ITM_VOLM" size="10" value="<?php  echo $ITM_VOLM;?>" />
		                          	</div>
		                        </div>
		                    </div>
							<div id="loading_1" class="overlay" style="display: none;">
					            <i class="fa fa-refresh fa-spin"></i>
					        </div>
		                </div>
		            </div>
					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border">
								<i class="fa fa-gear"></i>
								<h3 class="box-title"><?php echo $InOthSett; ?></h3>
							</div>
							<div class="box-body">
								<?php
									if($PRJLEV == 3)
									{
										$accIN = 1;
										$accUM = "1,5";
									}
									else
									{
										$accIN = 1;
										$accUM = "1,2,6,7,8";
									}

		                            $sqlC0a		= "tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accIN) AND PRJCODE = '$PRJCODE'";
		                            $resC0a 	= $this->db->count_all($sqlC0a);
		                            
		                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
														Acc_DirParent, isLast, ORD_ID
		                                            FROM tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accIN) AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID ASC";
		                            $resC0b 	= $this->db->query($sqlC0b)->result();
		                        ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $AccountName; ?></label>
		                            <div class="col-sm-9">
		                                <select name="ACC_ID" id="ACC_ID" class="form-control select2">
		                        			<option value="" > --- </option>
		                                    <?php
											if($resC0a>0)
											{
												foreach($resC0b as $rowC0b) :
													$Acc_ID0		= $rowC0b->Acc_ID;
													$Account_Number0= $rowC0b->Account_Number;
													$Acc_DirParent0	= $rowC0b->Acc_DirParent;
													$Account_Level0	= $rowC0b->Account_Level;
													if($LangID == 'IND')
													{
														$Account_Name0	= $rowC0b->Account_NameId;
													}
													else
													{
														$Account_Name0	= $rowC0b->Account_NameEn;
													}
													
													$Acc_ParentList0	= $rowC0b->Acc_ParentList;
													$isLast_0			= $rowC0b->isLast;
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
														<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 === $ACC_ID) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
													<?php
												endforeach;
											}
											?>
		                                </select>
		                            </div>
		                        </div>
								<?php
									// if($PRJSCATEG == 2)
										$QRY1 	= "Account_Category IN (1,5,8,9,10) AND ";
									// else
									// 	$QRY1 	= "Account_Category IN (5,8,9,10) AND ";

		                            $sqlC0a		= "tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accUM) AND PRJCODE = '$PRJCODE'";
		                            $resC0a 	= $this->db->count_all($sqlC0a);
		                            
		                            $sqlC0b		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
														Acc_DirParent, isLast
		                                            FROM tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN ($accUM) AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
		                            $resC0b 	= $this->db->query($sqlC0b)->result();
		                        ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label"><?php echo $UsedAcc; ?></label>
		                            <div class="col-sm-9">
		                                <select name="ACC_ID_UM" id="ACC_ID_UM" class="form-control select2">
		                        			<option value="" > --- </option>
		                                    <?php
											if($resC0a>0)
											{
												foreach($resC0b as $rowC0b) :
													$Acc_ID0		= $rowC0b->Acc_ID;
													$Account_Number0= $rowC0b->Account_Number;
													$Acc_DirParent0	= $rowC0b->Acc_DirParent;
													$Account_Level0	= $rowC0b->Account_Level;
													if($LangID == 'IND')
													{
														$Account_Name0	= $rowC0b->Account_NameId;
													}
													else
													{
														$Account_Name0	= $rowC0b->Account_NameEn;
													}
													
													$Acc_ParentList0	= $rowC0b->Acc_ParentList;
													$isLast_0			= $rowC0b->isLast;
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
													<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 === $ACC_ID_UM) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
													<?php
												endforeach;
											}
											?>
		                                </select>
		                            </div>
		                        </div>
								<?php
		                            $sqlC0d		= "tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN (4,6) AND PRJCODE = '$PRJCODE'";
		                            $resC0d 	= $this->db->count_all($sqlC0d);
		                            
		                            $sqlC0e		= "SELECT DISTINCT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, 
		                            					Acc_ParentList, Acc_DirParent, isLast
		                                            FROM tbl_chartaccount_$PRJCODEVW WHERE Account_Category IN (4,6) AND PRJCODE = '$PRJCODE' ORDER BY ORD_ID";
		                            $resC0e 	= $this->db->query($sqlC0e)->result();
		                        ?>
		                        <div class="form-group">
		                            <label for="inputName" class="col-sm-3 control-label" title="Debet Side in Shipment Notes"><?php echo $Sales; ?></label>
		                            <div class="col-sm-9">
		                                <select name="ACC_ID_SAL" id="ACC_ID_SAL" class="form-control select2">
		                        			<option value="" > --- </option>
		                                    <?php
											if($resC0e>0)
											{
												foreach($resC0e as $rowC0e) :
													$Acc_ID0		= $rowC0e->Acc_ID;
													$Account_Number0= $rowC0e->Account_Number;
													$Acc_DirParent0	= $rowC0e->Acc_DirParent;
													$Account_Level0	= $rowC0e->Account_Level;
													if($LangID == 'IND')
													{
														$Account_Name0	= $rowC0e->Account_NameId;
													}
													else
													{
														$Account_Name0	= $rowC0e->Account_NameEn;
													}
													
													$Acc_ParentList0	= $rowC0e->Acc_ParentList;
													$isLast_0			= $rowC0e->isLast;
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
													<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 === $ACC_ID_SAL) { ?> selected <?php } if($disbaled_0 == 1) { ?> disabled style="font-weight:bold" <?php } ?>><?php echo "$level_coa1$collData0 - $Account_Name0"; ?></option>
													<?php
												endforeach;
											}
											?>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Kind ?></label>
		                          	<div class="col-sm-6">
		                            	<select name="ITM_KIND" id="ITM_KIND" class="form-control select2">
		                                	<option value="0"> --- </option>
		                                	<?php if($ITM_GROUP == 'M' || $ITM_GROUP == 'T') { ?>
											<option value="ISMTRL" <?php if($ISMTRL==1) { ?> selected <?php } ?>> <?php echo $Material; ?> </option>
											<option value="ISPART" <?php if($ISPART==1) { ?> selected <?php } ?>> <?php echo $Part; ?> </option>
											<option value="ISFUEL" <?php if($ISFUEL==1) { ?> selected <?php } ?>> <?php echo $Fuel; ?> </option>
											<option value="ISLUBRIC" <?php if($ISLUBRIC==1) { ?> selected <?php } ?>> <?php echo $Lubricants; ?> </option>
											<option value="ISFASTM" <?php if($ISFASTM==1) { ?> selected <?php } ?> style="display:none"> <?php echo $FastMove; ?> </option>
											<option value="ISRM" <?php if($ISRM==1) { ?> selected <?php } ?>> <?php echo $RawMtr; ?> </option>
											<option value="ISWIP" <?php if($ISWIP==1) { ?> selected <?php } ?>> WIP </option>
											<option value="ISFG" <?php if($ISFG==1) { ?> selected <?php } ?>> <?php echo $FinGoods; ?> </option>
											<option value="ISRIB" <?php if($ISRIB==1) { ?> selected <?php } ?>> RIB </option>
		                                    <?php } else { ?>
											<option value="ISRENT" <?php if($ISRENT==1) { ?> selected <?php } ?>> <?php echo $RentExpenses; ?> </option>
											<option value="ISWAGE" <?php if($ISWAGE==1) { ?> selected <?php } ?>> <?php echo $Wage; ?> </option>
											<option value="ISCOST" <?php if($ISCOST==1) { ?> selected <?php } ?>> <?php echo $Expenses; ?> </option>
		                                    <?php } ?>
		                                </select>
		                          	</div>
		                          	<div class="col-sm-3">
		                          		<label for="inputName" class="control-label">Major ?</label>
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display: none;">
		                          	<label for="inputName" class="col-sm-3 control-label">Is Greige</label>
		                          	<div class="col-sm-9">                            
		                            	<select name="NEEDQRC" id="NEEDQRC" class="form-control select2">
											<option value="0" <?php if($NEEDQRC==0) { ?> selected <?php } ?>> No </option>
											<option value="1" <?php if($NEEDQRC==1) { ?> selected <?php } ?>> Yes </option>
		                                </select>
		                          	</div>
		                        </div>
		                        <!-- <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="radio" name="STATUS" id="STATUS1" class="flat-red" value="1" <?php if($STATUS == 1) { ?> checked <?php } ?>>
		                                <?php echo $Active ?> &nbsp;
		                                <input type="radio" name="STATUS" id="STATUS2" class="flat-red" value="0" <?php if($STATUS == 0) { ?> checked <?php } ?>>
		                                <?php echo $Inactive ?> 
		                            </div>
		                        </div> -->
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"> Laporan L/R</label>
		                          	<div class="col-sm-6">
		                            	<select name="ITM_LR" id="ITM_LR" class="form-control select2">
		                                	<option value="0"> --- </option>
											<option value="PPA" <?php if($ITM_LR=='PPA') { ?> selected <?php } ?>> 		Penjualan Aset </option>
											<option value="PLL" <?php if($ITM_LR=='PLL') { ?> selected <?php } ?>> 		Penjualan Lain-Lain </option>
											<option value="BAU" <?php if($ITM_LR=='BAU') { ?> selected <?php } ?>> 		Beban Administrasi dan Umum </option>
											<option value="BGP" <?php if($ITM_LR=='BGP') { ?> selected <?php } ?>> 		Beban Gaji Pegawai </option>
											<option value="BLAT" <?php if($ITM_LR=='BALT') { ?> selected <?php } ?>> 	Beban Listrik, Air & Telepon </option>
											<option value="BNOL" <?php if($ITM_LR=='BNOL') { ?> selected <?php } ?>> 	Beban Non Operasional Lainnya </option>
											<option value="BOL" <?php if($ITM_LR=='BOL') { ?> selected <?php } ?>> 		Beban Operasional Lainnya </option>
											<option value="BPB" <?php if($ITM_LR=='BPL') { ?> selected <?php } ?>> 		Beban Pembelian Lainnya </option>
											<option value="BPB" <?php if($ITM_LR=='BPML') { ?> selected <?php } ?>> 	Beban Pemeliharaan </option>
											<option value="BPB" <?php if($ITM_LR=='BPB') { ?> selected <?php } ?>> 		Beban Penyusutan Bangunan </option>
											<option value="BPM" <?php if($ITM_LR=='BPM') { ?> selected <?php } ?>> 		Beban Penyusutan Mesin </option>
											<option value="BPK" <?php if($ITM_LR=='BPK') { ?> selected <?php } ?>> 		Beban Penyusutan Kendaraan </option>
											<option value="BPP" <?php if($ITM_LR=='BPP') { ?> selected <?php } ?>> 		Beban Pokok Produksi </option>
											<option value="BPP" <?php if($ITM_LR=='BUL') { ?> selected <?php } ?>> 		Beban Upah Langsung </option>
											<option value="BPP" <?php if($ITM_LR=='BUTL') { ?> selected <?php } ?>> 	Beban Upah Tidak Langsung </option>
											<option value="BLL" <?php if($ITM_LR=='BLL') { ?> selected <?php } ?>> 		Beban Lain-Lain </option>
		                                </select>
		                          	</div>
		                          	<div class="col-sm-3">                            
		                            	<select name="ISMAJOR" id="ISMAJOR" class="form-control select2">
											<option value="0" <?php if($ISMAJOR==0) { ?> selected <?php } ?>> No </option>
											<option value="1" <?php if($ISMAJOR==1) { ?> selected <?php } ?>> Yes </option>
		                                </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="STATUS" id="STATUS" class="form-control select2">
											<option value="1" <?php if($STATUS==1) { ?> selected <?php } ?>> <?php echo $Active ?> </option>
											<option value="0" <?php if($STATUS==0) { ?> selected <?php } ?>> <?php echo $Inactive ?> </option>
		                                </select>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <div class="col-sm-offset-3 col-sm-9">
		                            	<?php								
											if($ISCREATE == 1)
											{
												if($task=='add')
												{
													?>
														<button class="btn btn-primary">
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
												else
												{
													?>
														<button class="btn btn-primary" >
														<i class="fa fa-save"></i></button>&nbsp;
													<?php
												}
											}
											echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
										?>
		                            </div>
		                        </div>
		                    </div>
							<div id="loading_2" class="overlay" style="display: none;">
					            <i class="fa fa-refresh fa-spin"></i>
					        </div>
		                </div>
		            </div>
                </form>
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
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
		<iframe id="myiFrame" src="<?php echo base_url('__l1y/resPRICE' ) ?>" style="width: 100%;"></iframe>
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
</script>

<script>
	function add_item(strItem) 
	{
		arrItem = strItem.split('|');
		JOBCODEID	= arrItem[1];
		ITM_CODE	= arrItem[4];
		ITM_PRICE	= arrItem[8];
		BUDG_VOLM	= arrItem[9];
		var decFormat									= document.getElementById('decFormat').value;
		document.getElementById('ITM_CODE_H').value 	= ITM_CODE;
		document.getElementById('JOBCODEID').value 		= JOBCODEID;
		document.getElementById('ITM_VOLMBG').value 	= BUDG_VOLM;
		document.getElementById('ITM_VOLMBG1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(BUDG_VOLM)),decFormat));
		document.getElementById('ITM_PRICE').value 		= ITM_PRICE;
		document.getElementById('ITM_PRICE1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));	
	}
	
	function changePRICE(thisValue)
	{
		var decFormat								= document.getElementById('decFormat').value;
		var ITM_PRICE								= eval(thisValue).value.split(",").join("");
		document.getElementById('ITM_PRICE').value 	= ITM_PRICE;
		document.getElementById('ITM_PRICE1').value = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICE)),decFormat));	
	}
	
	function changeVOLM(thisValue)
	{
		var decFormat								= document.getElementById('decFormat').value;
		var ITM_VOLM								= eval(thisValue).value.split(",").join("");
		document.getElementById('ITM_VOLM').value 	= ITM_VOLM;	
		document.getElementById('ITM_VOLM1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLM)),decFormat));	
	}
	
	function changeVOLMBG(thisValue)
	{
		var decFormat									= document.getElementById('decFormat').value;
		var ITM_VOLMBG									= eval(thisValue).value.split(",").join("");
		document.getElementById('ITM_VOLMBG').value 	= ITM_VOLMBG;	
		document.getElementById('ITM_VOLMBG1').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_VOLMBG)),decFormat));	
	}
	
	function chooseGroup(grpType)
	{
		document.getElementById('ITM_GROUPX').value = grpType;
		document.frmsrchGrp.submitSrch.click();
	}
	
	function chooseCategory()
	{
		var ITM_CATEG	= document.getElementById('ITM_CATEG').value;
		document.getElementById('ITM_CATEGX').value 	= ITM_CATEG;
		document.getElementById('PITM_CODEX').value 	= "";
		document.frmsrch.submitSrch.click();
	}
	
	function chooseParent()
	{
		var PITM_CODE	= document.getElementById('PITM_CODE').value;
		var ITM_CATEG	= document.getElementById('ITM_CATEG').value;
		document.getElementById('ITM_CATEGX').value 	= ITM_CATEG;
		document.getElementById('PITM_CODEX').value 	= PITM_CODE;
		document.frmsrch.submitSrch.click();
	}
		
	function resPrice()
	{
		PRJCODE 	= "<?php echo $PRJCODE; ?>";
		ITM_CODE 	= "<?php echo $ITM_CODE; ?>";
		ITM_PRICE 	= document.getElementById('ITM_PRICE').value;
		alert(ITM_PRICE)
	    swal({
            text: "<?php echo $sureResPrc; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
				document.getElementById('idprogbar').style.display = '';
			    document.getElementById("progressbarXX").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('idprogbarXY').style.display = '';
			    document.getElementById("progressbarXY").innerHTML="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Preparing ...</span></div>";
				document.getElementById('loading_1').style.display = '';
				document.getElementById('loading_2').style.display = '';
            	var collID	= PRJCODE;

				var butSubm = $("#myiFrame")[0].contentWindow.sample_form;
				$("#myiFrame")[0].contentWindow.PRJCODE.value 	= PRJCODE;
				$("#myiFrame")[0].contentWindow.ITM_CODE.value 	= ITM_CODE;
				$("#myiFrame")[0].contentWindow.ITM_PRICE.value = ITM_PRICE;
				butSubm.submit();
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
	
	function saveItem()
	{
		ITM_CATEG	= document.getElementById('ITM_CATEG').value;
		if(ITM_CATEG == '')
		{
			swal('<?php echo $alert8; ?>',
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close()
				$('#ITM_CATEG').focus();
			});
			return false;
		}

		ITM_TYPE	= document.getElementById('ITM_TYPE').value;
		ITM_CODE_H	= document.getElementById('ITM_CODE_H').value;
		if(ITM_TYPE == 'SUBS' && ITM_CODE_H == '')
		{
			swal('<?php echo $alert0; ?>',
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close()
				$('#ITM_CODE_H').focus();
			});
			return false;
		}

		ITM_CODE	= document.getElementById('ITM_CODE').value;
		if(ITM_CODE == '')
		{
			swal('<?php echo $alert1; ?>',
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close()
				$('#ITM_CODE').focus();
			});
			return false;
		}
		
		ITM_NAME = document.getElementById("ITM_NAME").value;
		if(ITM_NAME == 0)
		{
			swal('<?php echo $alert2; ?>',
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close();
				$('#ITM_NAME').focus();
			});
			return false;
		}
		
		ITM_UNIT = document.getElementById("ITM_UNIT").value;
		if(ITM_UNIT == 0)
		{
			swal('<?php echo $alert3; ?>',
			{
				icon:"warning"
			})
			.then(function()
			{
				swal.close();
				$('#ITM_UNIT').focus();
			});
			return false;
		}
		
		ITM_GROUP 	= $('#ITM_GROUP').val();
		/*if(ITM_GROUP == 'M' || ITM_GROUP == 'T')
		{
			ITM_CATEG 	= $('#ITM_CATEG').val();
			if(ITM_CATEG != 'WIP' && ITM_CATEG != 'FG')
			{
				ACC_ID 		= $('#ACC_ID').val();
				if(ACC_ID == '')
				{
					swal('<?php echo $alert5; ?>',
					{
						icon:"warning"
					})
					.then(function()
					{
						swal.close();
						$('#ACC_ID').focus();
					});
					return false;
				}
			}
		}*/

		ACC_ID_UM 	= $('#ACC_ID_UM').val();
		/*if(ACC_ID_UM == '')
		{
			ITM_CATEG 	= $('#ITM_CATEG').val();
			if(ITM_CATEG != 'WIP' && ITM_CATEG != 'FG')
			{
				ACC_ID_UM 		= $('#ACC_ID_UM').val();
				if(ACC_ID_UM == '')
				{
					swal('<?php echo $alert6; ?>',
					{
						icon:"warning"
					})
					.then(function()
					{
						swal.close();
						$('#ACC_ID_UM').focus();
					});
					return false;
				}
			}
			return false;
		}*/

		ITM_CATEG 	= $('#ITM_CATEG').val();
		/*if(ITM_CATEG != 'WIP' && ITM_CATEG != 'FG')
		{
			ITM_LR 	= $('#ITM_LR').val();
			if(ITM_LR == 0)
			{
				swal('<?php echo $alert7; ?>',
				{
					icon:"warning"
				})
				.then(function()
				{
					swal.close();
					$('#ITM_LR').focus();
				});
				return false;
			}
		}*/

		ITM_PRICE1 = document.getElementById("ITM_PRICE").value;
		/*if(ITM_PRICE1 == 0)
		{
			swal('<?php echo $alert4; ?>');
			document.getElementById("ITM_PRICE1").focus();
			return false;
		}*/
		
		ITM_VOLM1 = document.getElementById("ITM_VOLM1").value;
		if(ITM_VOLM1 == 0)
		{
			//swal('Please Item Volume');
			//document.getElementById("ITM_VOLM1").focus();
			//return false;
		}
		
		isChek = 0;
		ISRENT	= document.getElementById("ISRENT").checked;
		if(ISRENT == true) isChek = isChek + 1;
		ISPART	= document.getElementById("ISPART").checked;
		if(ISPART == true) isChek = isChek + 1;
		ISFUEL	= document.getElementById("ISFUEL").checked;
		if(ISFUEL == true) isChek = isChek + 1;
		ISLUBRIC	= document.getElementById("ISLUBRIC").checked;
		if(ISLUBRIC == true) isChek = isChek + 1;
		ISFASTM	= document.getElementById("ISFASTM").checked;
		if(ISFASTM == true) isChek = isChek + 1;
		ISWAGE	= document.getElementById("ISWAGE").checked;
		if(ISWAGE == true) isChek = isChek + 1;
		
		if(isChek == 0)
		{
			swal('Please check one or more of Item Type');
			return false;
		}
	}
	
	var decFormat		= 2;
	
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