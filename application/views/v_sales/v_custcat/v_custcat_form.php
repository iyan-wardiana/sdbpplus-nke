<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= v_custcat_form.php
 * Location		= -
*/

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
	$CUSTC_CODE 	= '';
	$CUSTC_NAME 	= '';
	$CUSTC_DESC 	= '';
    $CC_LA_CINV     = '';
    $CC_LA_CINVK    = '';
	$CC_LA_RECDP	= '';
}
else
{
	$CUSTC_CODE 	= $default['CUSTC_CODE'];
	$CUSTC_NAME 	= $default['CUSTC_NAME'];
	$CUSTC_DESC 	= $default['CUSTC_DESC'];
    $CC_LA_CINV     = $default['CC_LA_CINV'];
    $CC_LA_CINVK    = $default['CC_LA_CINVK'];
	$CC_LA_RECDP 	= $default['CC_LA_RECDP'];
}
// Tambah Faktur
$Acc_DirParent1A    = $CC_LA_CINV;

// Tambah Faktur Kredit
$Acc_DirParent1AK   = $CC_LA_CINVK;

// Uang Muka
$Acc_DirParent2A	= $CC_LA_RECDP;
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
            if($TranslCode == 'emptyCodCat')$emptyCodCat = $LangTransl;
            if($TranslCode == 'empCatNm')$empCatNm = $LangTransl;
    	endforeach;

        if($LangID == 'IND')
        {
            $alert1   = "Silahkan tentukan Akun faktur penjualan (Debet).";
            $alert2   = "Silahkan tentukan Akun faktur penjualan (Kredit).";
        }
        else
        {
            $alert1   = "Please select a sales account (Debet).";
            $alert2   = "Please select a sales account (Credit).";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/category.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $h1_title; ?>
                <small><?php echo $h2_title; ?></small>
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
                                        <label>
                                        <input type="text" name="CUSTC_CODE1" id="CUSTC_CODE1" class="form-control" value="<?php echo $CUSTC_CODE; ?>" onChange="functioncheck(this.value)" <?php if($task == 'edit') { ?> disabled <?php } ?> maxlength="10"/>
                                        <input type="hidden" name="CUSTC_CODE" id="CUSTC_CODE" value="<?php echo $CUSTC_CODE; ?>" />
                                    	</label><label>&nbsp;&nbsp;</label><label id="theCode"></label>&nbsp;&nbsp;&nbsp;
                                    	<input type="hidden" name="CheckThe_Code" id="CheckThe_Code" size="20" maxlength="25" value="0" >
                                  	</div>
                                </div>
        						<script>
                                    function functioncheck(myValue)
                                    {
                                        CUSTC_CODE1	= document.getElementById('CUSTC_CODE1').value;
                                        document.getElementById('CUSTC_CODE').value	= CUSTC_CODE1;
        								
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
                                                    document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById("theCode").innerHTML 	= ' The code already exist ... !';
                                                    document.getElementById("theCode").style.color 	= "#ff0000";
                                                }
                                                else
                                                {
                                                    document.getElementById('CheckThe_Code').value	= recordcount;
                                                    document.getElementById("theCode").innerHTML 	= ' The code : OK ... !';
                                                    document.getElementById("theCode").style.color 	= "green";
                                                }
                                            }				
                                        }
                                        var CUSTC_CODE = document.getElementById('CUSTC_CODE').value;
                                        ajaxRequest.open("GET", "<?php echo base_url().'index.php/c_sales/c_cu5tc47/getCUSTCAT/';?>" + CUSTC_CODE, true);
                                        ajaxRequest.send(null);
                                    }
                                </script>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Name ?></label>
                                  	<div class="col-sm-10">
                                    	<input type="text" class="form-control" name="CUSTC_NAME" id="CUSTC_NAME" value="<?php echo $CUSTC_NAME; ?>" />
                                  	</div>
                                </div>
                                <div class="form-group">
                                  	<label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?></label>
                                  	<div class="col-sm-10">
                                        <textarea class="form-control" name="CUSTC_DESC" y id="CUSTC_DESC" style="height:70px"><?php echo $CUSTC_DESC; ?></textarea>
                                  	</div>
                                </div>
        						<?php
        							$PRJCODE		= 'KTR';                            
                                    $sqlC0x			= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1";
                                    $resC0x 		= $this->db->query($sqlC0x)->result();
        							foreach($resC0x as $rowC0x) :
        								$PRJCODE	= $rowC0x->PRJCODE;
        							endforeach;
        							
                                    $sqlC0a		= "tbl_chartaccount WHERE Account_Level = '0' AND PRJCODE = '$PRJCODE' 
        												AND Account_Category = '1'";
                                    $resC0a 	= $this->db->count_all($sqlC0a);
                                    
                                    $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, 
        												Acc_DirParent, isLast
                                                    FROM tbl_chartaccount WHERE Account_Level = '0' AND PRJCODE = '$PRJCODE'
        												AND Account_Category = '1'";
                                    $resC0b 	= $this->db->query($sqlC0b)->result();
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $AddInvoice; ?></label>
                                    <div class="col-sm-5">
                                        <select name="Acc_DirParentA" id="Acc_DirParentA" class="form-control select2">
                                			<option value="" > --- </option>
                                            <?php
        									if($resC0a>0)
        									{
        										foreach($resC0b as $rowC0b) :
        											$Acc_ID0		= $rowC0b->Acc_ID;
        											$Account_Number0= $rowC0b->Account_Number;
        											$Acc_DirParent0	= $rowC0b->Acc_DirParent;
        											if($LangID == 'IND')
        											{
        												$Account_Name0	= $rowC0b->Account_NameId;
        											}
        											else
        											{
        												$Account_Name0	= $rowC0b->Account_NameEn;
        											}
        											
        											$Acc_ParentList0	= $rowC0b->Acc_ParentList;
        											
        											$resC1a		= 0;
        											$sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
        															AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
        															AND Account_Category = '1'";
        											$resC1a 	= $this->db->count_all($sqlC1a);
        											
        											//$collData0	= "$Account_Number0~$Acc_ParentList0";
        											$collData0	= "$Account_Number0";
        										?>
                                            	<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent1A) { ?> selected <?php } if($resC1a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$Account_Name0 - $collData0"; ?></option>
                                                <?php
        										if($resC1a>0)
        										{
        											$sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
        																Acc_ParentList
        															FROM tbl_chartaccount 
        															WHERE Acc_DirParent = '$Account_Number0'
        																AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
        																AND Account_Category = '1'";
        											$resC1b 	= $this->db->query($sqlC1b)->result();
        											foreach($resC1b as $rowC1b) :
        												$Acc_ID1		= $rowC1b->Acc_ID;
        												$Account_Number1= $rowC1b->Account_Number;
        												$Acc_DirParent1	= $rowC0b->Acc_DirParent;
        												if($LangID == 'IND')
        												{
        													$Account_Name1	= $rowC1b->Account_NameId;
        												}
        												else
        												{
        													$Account_Name1	= $rowC1b->Account_NameEn;
        												}
        												
        												$Acc_ParentList1	= $rowC1b->Acc_ParentList;
        												$level_coa1			= "&nbsp;&nbsp;&nbsp;";
        												
        												$resC2a		= 0;
        												$sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
        																AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
        																AND Account_Category = '1'";
        												$resC2a 	= $this->db->count_all($sqlC2a);
        												
        												$collData1	= "$Account_Number1";
        												?>
        												<option value="<?php echo $Account_Number1; ?>" <?php if($Account_Number1 == $Acc_DirParent1A) { ?> selected <?php }  if($resC2a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa1$Account_Name1 - $collData1"; ?></option>
        												<?php
                                                        if($resC2a>0)
                                                        {
                                                            $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
        																		Acc_ParentList
                                                                            FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
        																		AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
        																		AND Account_Category = '1'";
                                                            $resC2b 	= $this->db->query($sqlC2b)->result();
                                                            foreach($resC2b as $rowC2b) :
                                                                $Acc_ID2		= $rowC2b->Acc_ID;
                                                                $Account_Number2= $rowC2b->Account_Number;
        														$Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name2	= $rowC2b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name2	= $rowC2b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        		
        														$resC3a		= 0;
                                                                $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
        																		AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
        																		AND Account_Category = '1'";
                                                                $resC3a 	= $this->db->count_all($sqlC3a);
        														
        														$collData2	= "$Account_Number2";
                                                                ?>
                                                                <option value="<?php echo $Account_Number2; ?>" <?php if($Account_Number2 == $Acc_DirParent1A) { ?> selected <?php } if($resC3a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa2$Account_Name2 - $collData2"; ?></option>
        														<?php
                                                                if($resC3a>0)
                                                                {
                                                                    $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
        																				Account_NameId, Acc_ParentList
                                                                                    FROM tbl_chartaccount 
        																			WHERE Acc_DirParent = '$Account_Number2'
        																				AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
        																				AND Account_Category = '1'";
                                                                    $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                    foreach($resC3b as $rowC3b) :
                                                                        $Acc_ID3		= $rowC3b->Acc_ID;
                                                                        $Account_Number3= $rowC3b->Account_Number;
        																$Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name3	= $rowC3b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name3	= $rowC3b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                        $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                		$resC4a		= 0;
                                                                        $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
        																				AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
        																				AND Account_Category = '1'";
                                                                        $resC4a 	= $this->db->count_all($sqlC4a);
        																
        																$collData3	= "$Account_Number3";
                                                                        ?>
                                                                        <option value="<?php echo $Account_Number3; ?>" <?php if($Account_Number3 == $Acc_DirParent1A) { ?> selected <?php } if($resC4a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa3$Account_Name3 - $collData3"; ?></option>
        																<?php
                                                                        if($resC4a>0)
                                                                        {
                                                                            $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
        																						Account_NameId, Acc_ParentList
                                                                                            FROM tbl_chartaccount 
        																					WHERE Acc_DirParent = '$Account_Number3'
        																						AND Account_Level = '4' 
        																						AND PRJCODE = '$PRJCODE'
        																						AND Account_Category = '1'";
                                                                            $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                            foreach($resC4b as $rowC4b) :
                                                                                $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                $Account_Number4= $rowC4b->Account_Number;
        																		$Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                if($LangID == 'IND')
                                                                                {
                                                                                    $Account_Name4	= $rowC4b->Account_NameId;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                }
                                                                                
                                                                                $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                        		$resC5a		= 0;
                                                                                $sqlC5a		= "tbl_chartaccount WHERE
        																						Acc_DirParent = '$Account_Number4'
        																						AND Account_Level = '5'
        																						AND Account_Category = '1'";
                                                                                $resC5a 	= $this->db->count_all($sqlC5a);
        																		
        																		$collData4	= "$Account_Number4";
                                                                                ?>
                                                                                <option value="<?php echo $Account_Number4; ?>" <?php if($Account_Number4 == $Acc_DirParent1A) { ?> selected <?php }  if($resC5a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa4$Account_Name4 - $Account_Number4"; ?></option>
                                                                                <?php
                                                                            endforeach;
                                                                        }
                                                                    endforeach;
                                                                }
                                                            endforeach;
                                                        }
        											endforeach;
        										}
        										endforeach;
        									}
        									?>
                                        </select>
                                    </div>
                                    <?php
                                        $sqlC0a     = "tbl_chartaccount WHERE Account_Level = '0' AND PRJCODE = '$PRJCODE'
                                                        AND Account_Category = '4'";
                                        $resC0a     = $this->db->count_all($sqlC0a);
                                        
                                        $sqlC0b     = "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, 
                                                            Acc_DirParent, isLast
                                                        FROM tbl_chartaccount WHERE Account_Level = '0' AND PRJCODE = '$PRJCODE'
                                                            AND Account_Category = '4'";
                                        $resC0b     = $this->db->query($sqlC0b)->result();
                                    ?>
                                    <div class="col-sm-5">
                                        <select name="Acc_DirParentAK" id="Acc_DirParentAK" class="form-control select2">
                                            <option value="" > --- </option>
                                            <?php
                                            if($resC0a>0)
                                            {
                                                foreach($resC0b as $rowC0b) :
                                                    $Acc_ID0        = $rowC0b->Acc_ID;
                                                    $Account_Number0= $rowC0b->Account_Number;
                                                    $Acc_DirParent0 = $rowC0b->Acc_DirParent;
                                                    if($LangID == 'IND')
                                                    {
                                                        $Account_Name0  = $rowC0b->Account_NameId;
                                                    }
                                                    else
                                                    {
                                                        $Account_Name0  = $rowC0b->Account_NameEn;
                                                    }
                                                    
                                                    $Acc_ParentList0    = $rowC0b->Acc_ParentList;
                                                    
                                                    $resC1a     = 0;
                                                    $sqlC1a     = "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
                                                                    AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
                                                                    AND Account_Category = '4'";
                                                    $resC1a     = $this->db->count_all($sqlC1a);
                                                    
                                                    //$collData0    = "$Account_Number0~$Acc_ParentList0";
                                                    $collData0  = "$Account_Number0";
                                                ?>
                                                <option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent1AK) { ?> selected <?php } if($resC1a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$Account_Name0 - $collData0"; ?></option>
                                                <?php
                                                if($resC1a>0)
                                                {
                                                    $sqlC1b     = "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
                                                                        Acc_ParentList
                                                                    FROM tbl_chartaccount 
                                                                    WHERE Acc_DirParent = '$Account_Number0'
                                                                        AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
                                                                        AND Account_Category = '4'";
                                                    $resC1b     = $this->db->query($sqlC1b)->result();
                                                    foreach($resC1b as $rowC1b) :
                                                        $Acc_ID1        = $rowC1b->Acc_ID;
                                                        $Account_Number1= $rowC1b->Account_Number;
                                                        $Acc_DirParent1 = $rowC0b->Acc_DirParent;
                                                        if($LangID == 'IND')
                                                        {
                                                            $Account_Name1  = $rowC1b->Account_NameId;
                                                        }
                                                        else
                                                        {
                                                            $Account_Name1  = $rowC1b->Account_NameEn;
                                                        }
                                                        
                                                        $Acc_ParentList1    = $rowC1b->Acc_ParentList;
                                                        $level_coa1         = "&nbsp;&nbsp;&nbsp;";
                                                        
                                                        $resC2a     = 0;
                                                        $sqlC2a     = "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                        AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                        AND Account_Category = '4'";
                                                        $resC2a     = $this->db->count_all($sqlC2a);
                                                        
                                                        $collData1  = "$Account_Number1";
                                                        ?>
                                                        <option value="<?php echo $Account_Number1; ?>" <?php if($Account_Number1 == $Acc_DirParent1AK) { ?> selected <?php }  if($resC2a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa1$Account_Name1 - $collData1"; ?></option>
                                                        <?php
                                                        if($resC2a>0)
                                                        {
                                                            $sqlC2b     = "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
                                                                                Acc_ParentList
                                                                            FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
                                                                                AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
                                                                                AND Account_Category = '4'";                                                    $resC2b     = $this->db->query($sqlC2b)->result();
                                                            foreach($resC2b as $rowC2b) :
                                                                $Acc_ID2        = $rowC2b->Acc_ID;
                                                                $Account_Number2= $rowC2b->Account_Number;
                                                                $Acc_DirParent2 = $rowC0b->Acc_DirParent;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name2  = $rowC2b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name2  = $rowC2b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList2    = $rowC2b->Acc_ParentList;
                                                                $level_coa2         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                
                                                                $resC3a     = 0;
                                                                $sqlC3a     = "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
                                                                                AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                AND Account_Category = '4'";
                                                                $resC3a     = $this->db->count_all($sqlC3a);
                                                                
                                                                $collData2  = "$Account_Number2";
                                                                ?>
                                                                <option value="<?php echo $Account_Number2; ?>" <?php if($Account_Number2 == $Acc_DirParent1AK) { ?> selected <?php } if($resC3a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa2$Account_Name2 - $collData2"; ?></option>
                                                                <?php
                                                                if($resC3a>0)
                                                                {
                                                                    $sqlC3b     = "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                        Account_NameId, Acc_ParentList
                                                                                    FROM tbl_chartaccount 
                                                                                    WHERE Acc_DirParent = '$Account_Number2'
                                                                                        AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
                                                                                        AND Account_Category = '4'";
                                                                    $resC3b     = $this->db->query($sqlC3b)->result();
                                                                    foreach($resC3b as $rowC3b) :
                                                                        $Acc_ID3        = $rowC3b->Acc_ID;
                                                                        $Account_Number3= $rowC3b->Account_Number;
                                                                        $Acc_DirParent3 = $rowC0b->Acc_DirParent;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name3  = $rowC3b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name3  = $rowC3b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList3    = $rowC3b->Acc_ParentList;
                                                                        $level_coa3         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                        $resC4a     = 0;
                                                                        $sqlC4a     = "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
                                                                                        AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
                                                                                        AND Account_Category = '4'";
                                                                        $resC4a     = $this->db->count_all($sqlC4a);
                                                                        
                                                                        $collData3  = "$Account_Number3";
                                                                        ?>
                                                                        <option value="<?php echo $Account_Number3; ?>" <?php if($Account_Number3 == $Acc_DirParent1AK) { ?> selected <?php } if($resC4a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa3$Account_Name3 - $collData3"; ?></option>
                                                                        <?php
                                                                        if($resC4a>0)
                                                                        {
                                                                            $sqlC4b     = "SELECT Acc_ID, Account_Number, Account_NameEn, 
                                                                                                Account_NameId, Acc_ParentList
                                                                                            FROM tbl_chartaccount 
                                                                                            WHERE Acc_DirParent = '$Account_Number3'
                                                                                                AND Account_Level = '4' 
                                                                                                AND PRJCODE = '$PRJCODE'
                                                                                                AND Account_Category = '4'";
                                                                            $resC4b     = $this->db->query($sqlC4b)->result();
                                                                            foreach($resC4b as $rowC4b) :
                                                                                $Acc_ID4        = $rowC4b->Acc_ID;
                                                                                $Account_Number4= $rowC4b->Account_Number;
                                                                                $Acc_DirParent4 = $rowC0b->Acc_DirParent;
                                                                                if($LangID == 'IND')
                                                                                {
                                                                                    $Account_Name4  = $rowC4b->Account_NameId;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $Account_Name4  = $rowC4b->Account_NameEn;
                                                                                }
                                                                                
                                                                                $Acc_ParentList4    = $rowC4b->Acc_ParentList;
                                                                                $level_coa4         = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                $resC5a     = 0;
                                                                                $sqlC5a     = "tbl_chartaccount WHERE
                                                                                                Acc_DirParent = '$Account_Number4'
                                                                                                AND Account_Level = '5'
                                                                                                AND Account_Category = '4'";
                                                                                $resC5a     = $this->db->count_all($sqlC5a);
                                                                                
                                                                                $collData4  = "$Account_Number4";
                                                                                ?>
                                                                                <option value="<?php echo $Account_Number4; ?>" <?php if($Account_Number4 == $Acc_DirParent1AK) { ?> selected <?php }  if($resC5a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa4$Account_Name4 - $Account_Number4"; ?></option>
                                                                                <?php
                                                                            endforeach;
                                                                        }
                                                                    endforeach;
                                                                }
                                                            endforeach;
                                                        }
                                                    endforeach;
                                                }
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
        						<?php
                                    $sqlC0a		= "tbl_chartaccount WHERE Account_Level = '0' AND PRJCODE = '$PRJCODE' 
        												AND Account_Category = '4'";
                                    $resC0a 	= $this->db->count_all($sqlC0a);
                                    
                                    $sqlC0b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, 
        												Acc_DirParent, isLast
                                                    FROM tbl_chartaccount WHERE Account_Level = '0' AND PRJCODE = '$PRJCODE'
        												AND Account_Category = '4'";
                                    $resC0b 	= $this->db->query($sqlC0b)->result();
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $DownPayment; ?></label>
                                    <div class="col-sm-10">
                                        <select name="Acc_DirParentB" id="Acc_DirParentB" class="form-control select2">
                                			<option value="" > --- </option>
                                            <?php
        									if($resC0a>0)
        									{
        										foreach($resC0b as $rowC0b) :
        											$Acc_ID0		= $rowC0b->Acc_ID;
        											$Account_Number0= $rowC0b->Account_Number;
        											$Acc_DirParent0	= $rowC0b->Acc_DirParent;
        											if($LangID == 'IND')
        											{
        												$Account_Name0	= $rowC0b->Account_NameId;
        											}
        											else
        											{
        												$Account_Name0	= $rowC0b->Account_NameEn;
        											}
        											
        											$Acc_ParentList0	= $rowC0b->Acc_ParentList;
        											
        											$resC1a		= 0;
        											$sqlC1a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number0' 
        															AND Account_Level = '1' AND PRJCODE = '$PRJCODE' 
        															AND Account_Category = '4'";
        											$resC1a 	= $this->db->count_all($sqlC1a);
        											
        											//$collData0	= "$Account_Number0~$Acc_ParentList0";
        											$collData0	= "$Account_Number0";
        										?>
                                            	<option value="<?php echo $Account_Number0; ?>" <?php if($Account_Number0 == $Acc_DirParent2A) { ?> selected <?php } if($resC1a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$Account_Name0 - $collData0"; ?></option>
                                                <?php
        										if($resC1a>0)
        										{
        											$sqlC1b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, 
        																Acc_ParentList
        															FROM tbl_chartaccount 
        															WHERE Acc_DirParent = '$Account_Number0'
        																AND Account_Level = '1' AND PRJCODE = '$PRJCODE'
        																AND Account_Category = '4'";
        											$resC1b 	= $this->db->query($sqlC1b)->result();
        											foreach($resC1b as $rowC1b) :
        												$Acc_ID1		= $rowC1b->Acc_ID;
        												$Account_Number1= $rowC1b->Account_Number;
        												$Acc_DirParent1	= $rowC0b->Acc_DirParent;
        												if($LangID == 'IND')
        												{
        													$Account_Name1	= $rowC1b->Account_NameId;
        												}
        												else
        												{
        													$Account_Name1	= $rowC1b->Account_NameEn;
        												}
        												
        												$Acc_ParentList1	= $rowC1b->Acc_ParentList;
        												$level_coa1			= "&nbsp;&nbsp;&nbsp;";
        												
        												$resC2a		= 0;
        												$sqlC2a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
        																AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
        																AND Account_Category = '4'";
        												$resC2a 	= $this->db->count_all($sqlC2a);
        												
        												$collData1	= "$Account_Number1";
        												?>
        												<option value="<?php echo $Account_Number1; ?>" <?php if($Account_Number1 == $Acc_DirParent2A) { ?> selected <?php }  if($resC2a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa1$Account_Name1 - $collData1"; ?></option>
        												<?php
                                                        if($resC2a>0)
                                                        {
                                                            $sqlC2b		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId,
        																		Acc_ParentList
                                                                            FROM tbl_chartaccount WHERE Acc_DirParent = '$Account_Number1'
        																		AND Account_Level = '2' AND PRJCODE = '$PRJCODE'
        																		AND Account_Category = '4'";                                                    $resC2b 	= $this->db->query($sqlC2b)->result();
                                                            foreach($resC2b as $rowC2b) :
                                                                $Acc_ID2		= $rowC2b->Acc_ID;
                                                                $Account_Number2= $rowC2b->Account_Number;
        														$Acc_DirParent2	= $rowC0b->Acc_DirParent;
                                                                if($LangID == 'IND')
                                                                {
                                                                    $Account_Name2	= $rowC2b->Account_NameId;
                                                                }
                                                                else
                                                                {
                                                                    $Account_Name2	= $rowC2b->Account_NameEn;
                                                                }
                                                                
                                                                $Acc_ParentList2	= $rowC2b->Acc_ParentList;
                                                                $level_coa2			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                        		
        														$resC3a		= 0;
                                                                $sqlC3a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number2'
        																		AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
        																		AND Account_Category = '4'";
                                                                $resC3a 	= $this->db->count_all($sqlC3a);
        														
        														$collData2	= "$Account_Number2";
                                                                ?>
                                                                <option value="<?php echo $Account_Number2; ?>" <?php if($Account_Number2 == $Acc_DirParent2A) { ?> selected <?php } if($resC3a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa2$Account_Name2 - $collData2"; ?></option>
        														<?php
                                                                if($resC3a>0)
                                                                {
                                                                    $sqlC3b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
        																				Account_NameId, Acc_ParentList
                                                                                    FROM tbl_chartaccount 
        																			WHERE Acc_DirParent = '$Account_Number2'
        																				AND Account_Level = '3' AND PRJCODE = '$PRJCODE'
        																				AND Account_Category = '4'";
                                                                    $resC3b 	= $this->db->query($sqlC3b)->result();
                                                                    foreach($resC3b as $rowC3b) :
                                                                        $Acc_ID3		= $rowC3b->Acc_ID;
                                                                        $Account_Number3= $rowC3b->Account_Number;
        																$Acc_DirParent3	= $rowC0b->Acc_DirParent;
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $Account_Name3	= $rowC3b->Account_NameId;
                                                                        }
                                                                        else
                                                                        {
                                                                            $Account_Name3	= $rowC3b->Account_NameEn;
                                                                        }
                                                                        
                                                                        $Acc_ParentList3	= $rowC3b->Acc_ParentList;
                                                                        $level_coa3			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                		$resC4a		= 0;
                                                                        $sqlC4a		= "tbl_chartaccount WHERE Acc_DirParent = '$Account_Number3'
        																				AND Account_Level = '4' AND PRJCODE = '$PRJCODE'
        																				AND Account_Category = '4'";
                                                                        $resC4a 	= $this->db->count_all($sqlC4a);
        																
        																$collData3	= "$Account_Number3";
                                                                        ?>
                                                                        <option value="<?php echo $Account_Number3; ?>" <?php if($Account_Number3 == $Acc_DirParent2A) { ?> selected <?php } if($resC4a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa3$Account_Name3 - $collData3"; ?></option>
        																<?php
                                                                        if($resC4a>0)
                                                                        {
                                                                            $sqlC4b		= "SELECT Acc_ID, Account_Number, Account_NameEn, 
        																						Account_NameId, Acc_ParentList
                                                                                            FROM tbl_chartaccount 
        																					WHERE Acc_DirParent = '$Account_Number3'
        																						AND Account_Level = '4' 
        																						AND PRJCODE = '$PRJCODE'
        																						AND Account_Category = '4'";
                                                                            $resC4b 	= $this->db->query($sqlC4b)->result();
                                                                            foreach($resC4b as $rowC4b) :
                                                                                $Acc_ID4		= $rowC4b->Acc_ID;
                                                                                $Account_Number4= $rowC4b->Account_Number;
        																		$Acc_DirParent4	= $rowC0b->Acc_DirParent;
                                                                                if($LangID == 'IND')
                                                                                {
                                                                                    $Account_Name4	= $rowC4b->Account_NameId;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $Account_Name4	= $rowC4b->Account_NameEn;
                                                                                }
                                                                                
                                                                                $Acc_ParentList4	= $rowC4b->Acc_ParentList;
                                                                                $level_coa4			= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                        		$resC5a		= 0;
                                                                                $sqlC5a		= "tbl_chartaccount WHERE
        																						Acc_DirParent = '$Account_Number4'
        																						AND Account_Level = '5'
        																						AND Account_Category = '4'";
                                                                                $resC5a 	= $this->db->count_all($sqlC5a);
        																		
        																		$collData4	= "$Account_Number4";
                                                                                ?>
                                                                                <option value="<?php echo $Account_Number4; ?>" <?php if($Account_Number4 == $Acc_DirParent2A) { ?> selected <?php }  if($resC5a > 0) { ?> disabled style="background-color:#EAEAEA" <?php } ?>><?php echo "$level_coa4$Account_Name4 - $Account_Number4"; ?></option>
                                                                                <?php
                                                                            endforeach;
                                                                        }
                                                                    endforeach;
                                                                }
                                                            endforeach;
                                                        }
        											endforeach;
        										}
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
        								
        							
        								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
        							?>
                                    </div>
                                </div>
                            </form>
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

	function saveCategory()
	{
		Check_Code   = document.getElementById('CheckThe_Code').value;

		if(Check_Code > 0)
		{
			swal('<?php echo $codeExist; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                document.getElementById('CUSTC_CODE').value     = '';
                document.getElementById('CUSTC_CODE1').value    = '';
                document.getElementById('CheckThe_Code').value  = 0;
                $("#CUSTC_CODE1").focus();
            });
			return false;
		}
        
        CUSTC_CODE  = document.getElementById('CUSTC_CODE').value;
        if(CUSTC_CODE == '')
        {
            swal('<?php echo $emptyCodCat; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                document.getElementById('CheckThe_Code').value  = 0;
                $("#CUSTC_CODE1").focus();
            });
            return false;         
        }
        
        CUSTC_NAME  = document.getElementById('CUSTC_NAME').value;
        if(CUSTC_NAME == '')
        {
            swal('<?php echo $empCatNm; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                $("#CUSTC_NAME").focus();
            });
            return false;
        }

        ACC_IDINVD   = document.getElementById('Acc_DirParentA').value;
        if(ACC_IDINVD == '')
        {
            swal('<?php echo $alert1; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                $("#Acc_DirParentA").focus();
            });
            return false;
        }

        ACC_IDINVK   = document.getElementById('Acc_DirParentAK').value;
        if(ACC_IDINVK == '')
        {
            swal('<?php echo $alert2; ?>',
            {
                icon: "warning",
            })
            .then(function()
            {
                $("#Acc_DirParentAK").focus();
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