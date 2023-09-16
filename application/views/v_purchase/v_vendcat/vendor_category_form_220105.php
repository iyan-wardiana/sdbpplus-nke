<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 23 Maret 2017
    * File Name	= vendor_category_form.php
    * Location		= -
*/
?>
<?php
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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

if($task == 'add')
{
	$VendCat_Code 	= '';
	$VendCat_Name 	= '';
	$VendCat_Desc 	= '';
}
else
{
	$VendCat_Code 	= $default['VendCat_Code'];
	$VendCat_Name 	= $default['VendCat_Name'];
	$VendCat_Desc 	= $default['VendCat_Desc'];
}
// Tambah Faktur
$LA_ACCID1	= '';
$sqlAcc1	= "SELECT LA_ITM_CODE, LA_CATEG, LA_ACCID, LA_DK FROM tbl_link_account
				WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'PINV'";
$resAcc1	= $this->db->query($sqlAcc1)->result();
foreach($resAcc1 as $rowAcc1) :
	$LA_CATEG1	= $rowAcc1->LA_CATEG;
	$LA_ACCID1	= $rowAcc1->LA_ACCID;
	$LA_DK1		= $rowAcc1->LA_DK;
endforeach;
$Acc_DirParent1A	= $LA_ACCID1;
$LA_ACCID3	= '';
$sqlAcc3    = "SELECT LA_ITM_CODE, LA_CATEG, LA_ACCID, LA_DK FROM tbl_link_account
                WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'RET'";
$resAcc3    = $this->db->query($sqlAcc3)->result();
foreach($resAcc3 as $rowAcc3) :
    $LA_CATEG3  = $rowAcc3->LA_CATEG;
    $LA_ACCID3  = $rowAcc3->LA_ACCID;
    $LA_DK3     = $rowAcc3->LA_DK;
endforeach;
$Acc_DirParent1C    = $LA_ACCID3;

$LA_ACCID4  = "";
$sqlAcc4    = "SELECT LA_ITM_CODE, LA_CATEG, LA_ACCID, LA_DK FROM tbl_link_account
                WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'OPN-RET'";
$resAcc4    = $this->db->query($sqlAcc4)->result();
foreach($resAcc4 as $rowAcc4) :
    $LA_CATEG4  = $rowAcc4->LA_CATEG;
    $LA_ACCID4  = $rowAcc4->LA_ACCID;
    $LA_DK4     = $rowAcc4->LA_DK;
endforeach;
$Acc_DirParent1D    = $LA_ACCID4;

// Uang Muka
$LA_ACCID2	= '';
$sqlAcc2	= "SELECT LA_ITM_CODE, LA_CATEG, LA_ACCID, LA_DK FROM tbl_link_account
				WHERE LA_ITM_CODE = '$VendCat_Code' AND LA_CATEG = 'DP'";
$resAcc2	= $this->db->query($sqlAcc2)->result();
foreach($resAcc2 as $rowAcc2) :
	$LA_CATEG2	= $rowAcc2->LA_CATEG;
	$LA_ACCID2	= $rowAcc2->LA_ACCID;
	$LA_DK2		= $rowAcc2->LA_DK;
endforeach;
$Acc_DirParent2A	= $LA_ACCID2;

$sqlPRJ		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
$resPRJ 	= $this->db->query($sqlPRJ)->result();
foreach($resPRJ as $rowPRJ) :
	$PRJCODE= $rowPRJ->PRJCODE;
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
    	$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
    	//______$this->load->view('template/sidebar');
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
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'Name')$Name = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'Category')$Category = $LangTransl;
    		if($TranslCode == 'AccountPosition')$AccountPosition = $LangTransl;
    		if($TranslCode == 'AddInvoice')$AddInvoice = $LangTransl;
    		if($TranslCode == 'DownPayment')$DownPayment = $LangTransl;
    		if($TranslCode == 'Payment')$Payment = $LangTransl;
            if($TranslCode == 'codeExist')$codeExist = $LangTransl;
            if($TranslCode == 'venCatEmpty')$venCatEmpty = $LangTransl;
            if($TranslCode == 'catNmEmpt')$catNmEmpt = $LangTransl;
            if($TranslCode == 'selInvAcc')$selInvAcc = $LangTransl;
            if($TranslCode == 'selDPAcc')$selDPAcc = $LangTransl;
    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/supplier_categ.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h2_title; ?>
                <small><?php echo $h3_title; ?></small>
              </h1>
              <?php /*?><ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Data tables</li>
              </ol><?php */?>
        </section>

        <section class="content">	
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="display:none">               
                      		<div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">
                        	<form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return saveCategory()">
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?></label>
                                  	<div class="col-sm-10">
                                        <!-- <label> -->
                                        <input type="text" name="VendCat_Code1" id="VendCat_Code1" class="form-control" value="<?php echo $VendCat_Code; ?>" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> disabled <?php } ?> maxlength="6"/>
                                        <input type="hidden" name="VendCat_Code" id="VendCat_Code" value="<?php echo $VendCat_Code; ?>" />
                                    	<!-- </label><label>&nbsp;&nbsp;</label><label id="theCode"></label>&nbsp;&nbsp;&nbsp; -->
                                    	<input type="hidden" name="CheckThe_Code" id="CheckThe_Code" size="20" maxlength="25" value="0" >
                                  	</div>
                                </div>
        						<script>
                                    function functioncheck(myValue)
                                    {
                                        VendCat_Code1	= document.getElementById('VendCat_Code1').value;
                                        document.getElementById('VendCat_Code').value	= VendCat_Code1;
        								
                                        var ajaxRequest;
                                        try
                                        {
                                            ajaxRequest = new XMLHttpRequest();
                                        }
                                        catch (e)
                                        {
                                            swal("Something is wrong");
                                            return false;
                                        }
                                        
                                        ajaxRequest.onreadystatechange = function()
                                        {
                                            if(ajaxRequest.readyState == 4)
                                            {
                                                recordcount = ajaxRequest.responseText;
                                                if(recordcount > 0)
                                                {
                                                    /*document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById("theCode").innerHTML 	= ' The code already exist ... !';
                                                    document.getElementById("theCode").style.color 	= "#ff0000";*/
                                                    swal('<?php echo $codeExist; ?>',
                                                    {
                                                        icon: "warning",
                                                    })
                                                    .then(function()
                                                    {
                                                        swal.close();
                                                        document.getElementById('VendCat_Code1').value = '';
                                                        $('#VendCat_Code1').focus();
                                                    })
                                                }
                                                else
                                                {
                                                    /*document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById("theCode").innerHTML 	= ' The code : OK ... !';
                                                    document.getElementById("theCode").style.color 	= "green";*/
                                                }
                                            }				
                                        }
                                        var VendCat_Code = document.getElementById('VendCat_Code').value;
                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_purchase/c_vendcat/getVENDCATCODE/';?>" + VendCat_Code, true);
                                        ajaxRequest.send(null);
                                    }
                                </script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Name ?></label>
                                  	<div class="col-sm-10">
                                    	<input type="text" class="form-control" name="VendCat_Name" id="VendCat_Name" value="<?php echo $VendCat_Name; ?>" />
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?></label>
                                  	<div class="col-sm-10">
                                        <textarea class="form-control" name="VendCat_Desc"  id="VendCat_Desc" style="height:70px"><?php echo $VendCat_Desc; ?></textarea>
                                  	</div>
                                </div>
        						<?php
                                    $sqlC0a		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Category = '2'";
                                    $resC0a 	= $this->db->count_all($sqlC0a);
                                    
                                    $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
        												Acc_DirParent, isLast
                                                    FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Category = '2' ORDER BY ORD_ID ASC";
                                    $resC0b1 	= $this->db->query($sqlC0b)->result();
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $AddInvoice; ?></label>
                                    <div class="col-sm-10">
                                        <select name="Acc_DirParentA" id="Acc_DirParentA" class="form-control select2">
                                			<option value="" > --- </option>
                                            <?php
        									if($resC0a>0)
        									{
        										foreach($resC0b1 as $rowC0b) :
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
        											
        											//$collData0	= "$Account_Number0~$Acc_ParentList0";
        											$collData0	= "$Account_Number0";
        											?>
        												<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent1A) { ?> selected <?php } if($disbaled_0 > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
        											<?php
        										endforeach;
        									}
        									?>
                                        </select>
                                    </div>
                                </div>
        						<?php
                                    $sqlC0a		= "tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Category = '1'";
                                    $resC0a 	= $this->db->count_all($sqlC0a);
                                    
                                    $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_Level, Account_NameEn, Account_NameId, Acc_ParentList,
        												Acc_DirParent, isLast
                                                    FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Category = '1' ORDER BY ORD_ID ASC";
                                    $resC0b2 	= $this->db->query($sqlC0b)->result();
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Payment; ?>&nbsp;<?php echo $DownPayment; ?></label>
                                    <div class="col-sm-10">
                                        <select name="Acc_DirParentB" id="Acc_DirParentB" class="form-control select2">
                                			<option value="" > --- </option>
                                            <?php
        									if($resC0a>0)
        									{
        										foreach($resC0b2 as $rowC0b) :
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
        											
        											//$collData0	= "$Account_Number0~$Acc_ParentList0";
        											$collData0	= "$Account_Number0";
        											?>
        												<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent2A) { ?> selected <?php } if($disbaled_0 > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
        											<?php
        										endforeach;
        									}
        									?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Opname</label>
                                    <div class="col-sm-10">
                                        <select name="Acc_DirParentD" id="Acc_DirParentD" class="form-control select2">
                                            <option value="" > --- </option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b1 as $rowC0b) :
                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                    $Account_Level0 = $rowC0b->Account_Level;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                    $isLast_0           = $rowC0b->isLast;
                                                    $disbaled_0         = 0;
                                                    if($isLast_0 == 0)
                                                        $disbaled_0     = 1;
                                                        
                                                    if($Account_Level0 == 0)
                                                        $level_coa1         = "";
                                                    elseif($Account_Level0 == 1)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 2)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 3)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 4)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 5)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 6)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    elseif($Account_Level0 == 7)
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                    
                                                    //$collData0    = "$Account_Number0~$Acc_ParentList0";
                                                    $collData0  = "$Account_Number0";
                                                    ?>
                                                        <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent1D) { ?> selected <?php } if($disbaled_0 > 0) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
                                                    <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Retensi</label>
                                    <div class="col-sm-10">
                                        <select name="Acc_DirParentC" id="Acc_DirParentC" class="form-control select2">
                                			<option value="" > --- </option>
                                            <?php
        									if($resC0a>0)
        									{
        										foreach($resC0b1 as $rowC0b) :
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
        											
        											//$collData0	= "$Account_Number0~$Acc_ParentList0";
        											$collData0	= "$Account_Number0";
        											?>
        												<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent1C) { ?> selected <?php } if($disbaled_0 > 0) { ?> disabled <?php } ?>><?php echo "$level_coa1$Account_Name0 - $collData0"; ?></option>
        											<?php
        										endforeach;
        									}
        									?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                      <?php
        							
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
        								
        							
        								//echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                        ?>
                                        <button class="btn btn-danger" id="tblClose" type="button"><i class="fa fa-reply"></i></button>
                                        <?php
                                            if($LangID == 'IND')
                                            {
                                                $alertCls1  = "Anda yakin?";
                                                $alertCls2  = "Sistem akan mengosongkan data inputan Anda.";
                                                $alertCls3  = "Data Anda aman.";
                                            }
                                            else
                                            {
                                                $alertCls1  = "Are you sure?";
                                                $alertCls2  = "The system will empty the data you entered.";
                                                $alertCls3  = "Your data is safe.";
                                            }
                                        ?>
                                        <script type="text/javascript">
                                            $('#tblClose').on('click',function(e) 
                                            {
                                                /*swal({
                                                      title: "<?php echo $alertCls1; ?>",
                                                      text: "<?php echo $alertCls2; ?>",
                                                      //icon: "warning",
                                                      buttons: ["No", "Yes"],
                                                      dangerMode: true,
                                                    })
                                                    .then((willDelete) => {
                                                    if (willDelete) 
                                                    {*/
                                                        window.location = "<?php echo $backURL; ?>";
                                                    /*} else {
                                                        swal("<?php echo $alertCls3; ?>", {icon: "success"})
                                                    }
                                                });*/
                                            });
                                        </script>
                                    </div>
                                </div>
                            </form>
                            <?php
                                $DefID      = $this->session->userdata['Emp_ID'];
                                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                if($DefID == 'D15040004221')
                                    echo "<font size='1'><i>$act_lnk</i></font>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
	function saveCategory()
	{
		/*CheckThe_Code = document.getElementById('CheckThe_Code').value;
		if(CheckThe_Code > 0)
		{
			swal('Vendor Category Code is already exist.');
			document.getElementById('VendCat_Code').value = '';
			document.getElementById('VendCat_Code').focus();
			VendCat_Code = document.getElementById('VendCat_Code').value;
			functioncheck()
			return false;
		}*/
        
        VendCat_Code    = document.getElementById('VendCat_Code').value;
        VendCat_Name    = document.getElementById('VendCat_Name').value;
        Acc_DirParentA  = document.getElementById('Acc_DirParentA').value;
        Acc_DirParentB  = document.getElementById('Acc_DirParentB').value;
        if(VendCat_Code == '')
        {
            swal('<?php echo $venCatEmpty; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#VendCat_Code1').focus();
            });
            return false;           
        }
        
        if(VendCat_Name == '')
        {
            swal('<?php echo $catNmEmpt; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                swal.close();
                $('#VendCat_Name').focus();
            });
            return false;           
        }
        
        if(Acc_DirParentA == '')
        {
            swal('<?php echo $selInvAcc; ?>',
            {
                icon: "warning",
            });
            return false;           
        }
        
        if(Acc_DirParentB == '')
        {
            swal('<?php echo $selDPAcc; ?>',
            {
                icon: "warning",
            });
            return false;           
        }
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