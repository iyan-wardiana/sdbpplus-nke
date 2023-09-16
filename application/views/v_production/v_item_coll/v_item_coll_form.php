<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 07 September 2019
	* File Name	= v_item_coll_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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
$decFormat	= 2;

$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

$PRJNAME	= '';
$sql 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 	= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME = $row ->PRJNAME;
endforeach;

$currentRow = 0;
if($task == 'add')
{
	$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
	
	$ICOLL_NUM		= '';
	$ICOLL_CODE 	= '';
	$CUST_CODE		= '';
	$ICOLL_FG 		= '';
	$ICOLL_STAT 	= 1;
	
	foreach($viewDocPattern as $row) :
		$Pattern_Code 			= $row->Pattern_Code;
		$Pattern_Position 		= $row->Pattern_Position;
		$Pattern_YearAktive 	= $row->Pattern_YearAktive;
		$Pattern_MonthAktive 	= $row->Pattern_MonthAktive;
		$Pattern_DateAktive 	= $row->Pattern_DateAktive;
		$Pattern_Length 		= $row->Pattern_Length;
		$useYear 				= $row->useYear;
		$useMonth 				= $row->useMonth;
		$useDate 				= $row->useDate;
	endforeach;
	$LangID 	= $this->session->userdata['LangID'];
	if(isset($Pattern_Position))
	{
		$isSetDocNo = 1;
		if($Pattern_Position == 'Especially')
		{
			$Pattern_YearAktive 	= date('Y');
			$Pattern_MonthAktive 	= date('m');
			$Pattern_DateAktive 	= date('d');
		}
		$year 						= (int)$Pattern_YearAktive;
		$month 						= (int)$Pattern_MonthAktive;
		$date 						= (int)$Pattern_DateAktive;
	}
	else
	{
		$isSetDocNo = 0;
		$Pattern_Code 			= "GRP";
		$Pattern_Length 		= "5";
		$useYear 				= 1;
		$useMonth 				= 1;
		$useDate 				= 1;
		
		$Pattern_YearAktive 	= date('Y');
		$Pattern_MonthAktive 	= date('m');
		$Pattern_DateAktive 	= date('d');
		$year 					= (int)$Pattern_YearAktive;
		$month 					= (int)$Pattern_MonthAktive;
		$date 					= (int)$Pattern_DateAktive;
		
		if($LangID == 'IND')
		{
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
		}
		else
		{
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
		}
	}

	$this->db->where('PRJCODE', $PRJCODE);
	$myCount = $this->db->count_all('tbl_item_collh');
	
	$myMax 	= $myCount+1;
	
	$sql 		= "tbl_item_collh WHERE PRJCODE = '$PRJCODE'";
	$result 	= $this->db->count_all($sql);
	$myMax 		= $result+1;
			
	$thisMonth 	= $month;
	
	$lenMonth 		= strlen($thisMonth);
	if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
	$pattMonth 		= $nolMonth.$thisMonth;
	
	$thisDate = $date;
	$lenDate = strlen($thisDate);
	if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
	$pattDate = $nolDate.$thisDate;
	
	// group year, month and date
	if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$year$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$year$pattMonth";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$year$pattDate";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
		$groupPattern = "$pattMonth$pattDate";
	elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "$year";
	elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
		$groupPattern = "$pattMonth";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
		$groupPattern = "$pattDate";
	elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
		$groupPattern = "";
	
		
	$lastPatternNumb 	= $myMax;
	$lastPatternNumb1 	= $myMax;
	$len = strlen($lastPatternNumb);
	
	if($Pattern_Length==2)
	{
		if($len==1) $nol="0";
	}
	elseif($Pattern_Length==3)
	{if($len==1) $nol="00";else if($len==2) $nol="0";
	}
	elseif($Pattern_Length==4)
	{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
	}
	elseif($Pattern_Length==5)
	{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
	}
	elseif($Pattern_Length==6)
	{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
	}
	elseif($Pattern_Length==7)
	{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
	}
	
	$lastPatternNumb = $nol.$lastPatternNumb;
	
	$PATTCODE1		= $lastPatternNumb;
	$PATTCODE2		= date('y');
	$PATTCODE3		= date('m');
	$PATTCODEX		= date('His');
	$ICOLL_NUM		= '';
	$ICOLL_NOTES	= '';
	$ICOLL_FG		= '';
	$JO_NUM			= '';
	$JO_CODE 		= '';
	$ICOLL_REFSJ	= '';
	$ICOLL_QTYTOT	= 0;
	$ICOLL_CODE		= "$Pattern_Code.$PATTCODE2.$PATTCODEX.$PATTCODE1";
}
else
{	
	$ICOLL_NUM 		= $default['ICOLL_NUM'];
	$ICOLL_CODE 	= $default['ICOLL_CODE'];
	$PRJCODE 		= $default['PRJCODE'];
	$PRJCODE		= $default['PRJCODE'];
	$PRJCODE_HO 	= $default['PRJCODE_HO'];
	$JO_NUM			= $default['JO_NUM'];
	$ICOLL_NOTES	= $default['ICOLL_NOTES'];
	$CUST_CODE 		= $default['CUST_CODE'];
	$CUST_DESC 		= $default['CUST_DESC'];
	$ICOLL_FG 		= $default['ICOLL_FG'];
	$ICOLL_REFSJ	= $default['ICOLL_REFSJ'];
	$ICOLL_QTYTOT	= $default['ICOLL_QTYTOT'];
	$ICOLL_REFNUM	= $default['ICOLL_REFNUM'];
	$ICOLL_STAT 	= $default['ICOLL_STAT'];
}
	
$sqlPRJ 		= "SELECT PRJCODE, PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJCODE1 	= $rowPRJ->PRJCODE;
	$PRJNAME1 	= $rowPRJ->PRJNAME;
endforeach;

if(isset($_POST['IRCODEX']))
{
	$ICOLL_REFSJ	= $_POST['IRCODEX'];
}

$SPLCODE	= $CUST_CODE;
$sqlIR_D	= "SELECT SPLCODE FROM tbl_ir_header WHERE IR_CODE = '$ICOLL_REFSJ' AND PRJCODE = '$PRJCODE'";
$resIR_D	= $this->db->query($sqlIR_D)->result();
foreach($resIR_D as $row_D) :
	$SPLCODE 	= $row_D->SPLCODE;
endforeach;

$sqlCUST	= "SELECT CUST_CODE, CUST_DESC, CUST_ADD1 FROM tbl_customer  WHERE CUST_CODE = '$SPLCODE'";
$vwCUST		= $this->db->query($sqlCUST)->result();

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
			if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
			if($TranslCode == 'ColorName')$ColorName = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
			if($TranslCode == 'Material')$Material = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'FinGoods')$FinGoods = $LangTransl;
			if($TranslCode == 'JOCode')$JOCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Customer')$Customer = $LangTransl;
			if($TranslCode == 'Complete')$Complete = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'selJO')$selJO = $LangTransl;
			if($TranslCode == 'selSJ')$selSJ = $LangTransl;
		endforeach;
		if($LangID == 'IND')
		{
			$alert0		= 'Catatan tidak boleh kosong.';
			$alert1		= 'Pilih kode pelanggan.';
			$alert2		= 'Barang jadi tidak boleh kosong.';
			$alert3		= 'Jumlah pemesanan tidak boleh kosong';
			$alert4		= 'Silahkan pilih status dokumen';
			$isManual	= "Centang untuk kode manual.";
		}
		else
		{
			$alert0		= 'Notes can not be empty.';
			$alert1		= 'Select a Customer.';
			$alert2		= 'Finish Good can not be empty.';
			$alert3		= 'Qty order can not be empty';
			$alert4		= 'Please select a document status';
			$isManual	= "Check to manual code.";
		}

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/bom.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
			    <small><?php echo $PRJNAME; ?></small>  </h1>
			  <?php /*?><ol class="breadcrumb">
			    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			    <li><a href="#">Tables</a></li>
			    <li class="active">Data tables</li>
			  </ol><?php */?>
		</section>

		<section class="content">
		    <div class="row">
            	<!-- Mencari Kode Purchase Order Number -->
                <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                    <input type="text" name="IRCODEX" id="IRCODEX" class="textbox" value="<?php echo $ICOLL_REFSJ; ?>" />
                    <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                </form>
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		           				<input type="hidden" name="rowCount" id="rowCount" value="0">
		                        <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Code; ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" class="form-control" style="max-width:195px" name="ICOLL_NUM1" id="ICOLL_NUM1" value="<?php echo $ICOLL_NUM; ?>" disabled >
		                       			<input type="hidden" class="textbox" name="ICOLL_NUM" id="ICOLL_NUM" size="30" value="<?php echo $ICOLL_NUM; ?>" />
		                                <input type="hidden" class="form-control" name="ICOLL_CODE" id="ICOLL_CODE" value="<?php echo $ICOLL_CODE; ?>" >
		                                <input type="text" class="form-control" name="ICOLL_CODEX" id="ICOLL_CODEX" value="<?php echo $ICOLL_CODE; ?>" disabled >
		                          	</div>
		                        </div>
		                        <div class="form-group" style="display:none">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
		                          	<div class="col-sm-9">
		                            	<select name="PRJCODE1" id="PRJCODE1" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
		                                  <option value="none">--- None ---</option>
		                                  <?php echo $i = 0;
		                                    if($countPRJ > 0)
		                                    {
		                                        foreach($vwPRJ as $row) :
		                                            $PRJCODE1 	= $row->PRJCODE;
		                                            $PRJNAME 	= $row->PRJNAME;
		                                            ?>
		                                          <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
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
		                                <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
		                          	</div>
		                        </div>
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $JOCode; ?></label>
				                    <div class="col-sm-9">
				                        <!-- <div class="input-group">
				                            <div class="input-group-btn">
				                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
				                            </div>
				                            <input type="hidden" class="form-control" name="JO_NUM" id="JO_NUM" style="max-width:350px;" value="<?php echo $JO_NUM; ?>" />
				                            <input type="hidden" class="form-control" name="JO_CODE" id="JO_CODE" style="max-width:350px;" value="<?php echo $JO_CODE; ?>" />
				                            <input type="text" class="form-control" name="JO_CODE1" id="JO_CODE1" value="<?php echo $JO_CODE; ?>" onClick="getJOCODE();" <?php if($ICOLL_STAT != 1 && $ICOLL_STAT != 4) { ?> disabled <?php } ?>>
				                        </div> -->
				                        <select name="JO_NUM" id="JO_NUM" class="form-control select2" placeholder="<?php echo $JOCode; ?>">
				                            <option value="0"> --- </option>
				                            <?php
				                                $Disabled_1	= 0;
				                                $sqlJO_1	= "SELECT JO_NUM, JO_CODE, JO_DATE, SO_CODE, CUST_DESC, JO_DESC
				                                					FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 1";
				                                $resJO_1	= $this->db->query($sqlJO_1)->result();
				                                foreach($resJO_1 as $rowJO_1) :
				                                    $JO_NUM_1		= $rowJO_1->JO_NUM;
				                                    $JO_CODE_1		= $rowJO_1->JO_CODE;
				                                    $JO_DATE_1		= date('d/m/Y', strtotime($rowJO_1->JO_DATE));
				                                    $CUST_DESC_1	= $rowJO_1->CUST_DESC;
				                                    $JO_DESC_1		= $rowJO_1->JO_DESC;
				                                    ?>
				                                    <option value="<?php echo "$JO_NUM_1"; ?>" <?php if($JO_NUM_1 == $JO_NUM) { ?> selected <?php } ?>>
				                                        <?php echo "$JO_CODE_1&nbsp;&nbsp;&nbsp;$JO_DATE_1&nbsp;&nbsp;&nbsp;$CUST_DESC_1&nbsp;&nbsp;&nbsp;$JO_DESC_1"; ?>
				                                    </option>
				                                    <?php
				                                endforeach;
				                            ?>
				                        </select>
				                    </div>
				                </div>
				                <?php
				                	$urlx	= base_url().'index.php/c_production/c_b0fm47/getDetJO/';
				                	$urlx2	= base_url().'index.php/c_production/c_b0fm47/getDetJO1/';
				                ?>
		                        <script>
		                        	$(document).ready(function()
		                        	{
									    $("#JO_NUM").change(function()
									    {
									        var JO_NUM = $(this).val();

									        $.ajax({
			                                    type: 'POST',
			                                    url: '<?php echo $urlx; ?>',
			                                    data: {JO_NUM:JO_NUM},
			                                    dataType: 'json',
			                                    success: function(response)
			                                    {
									                var len = response.length;

									                var SPLTOP1		= response.split("~");
													var CUST_CODE	= SPLTOP1[0];
													var CUST_DESC	= SPLTOP1[1];
													var ITM_CODE	= SPLTOP1[2];
													var ITM_NAME	= SPLTOP1[3];
													var JO_QTY1		= SPLTOP1[4];
													var JO_QTY		= doDecimalFormat(RoundNDecimal(JO_QTY1, 2));
													var ITM_UNIT	= SPLTOP1[5];
													var ITM_UNIT1	= SPLTOP1[6];

													var ITMDESC		= ITM_CODE+' : '+ITM_NAME+" ("+JO_QTY+" "+ITM_UNIT+")";

													document.getElementById('CUST_CODE').value 		= CUST_CODE;
													document.getElementById('CUST_CODE1').value 	= CUST_DESC;
													document.getElementById('ICOLL_FG').value 		= ITM_CODE;
													document.getElementById('ICOLL_FG1').value 		= ITMDESC;
									            }
									        });


									        $.ajax({
			                                    type: 'POST',
			                                    url: '<?php echo $urlx2; ?>',
			                                    data: {JO_NUM:JO_NUM},
			                                    dataType: 'json',
			                                    success: function(response)
			                                    {
									                var len = response.length;

									                $("#ICOLL_REFSJ").empty();
									                $("#ICOLL_REFSJ").append("<option value='0'> --- </option>");
									                for( var i = 0; i<len; i++)
									                {
									                    var id = response[i]['IRREF'];
									                    var name = response[i]['IRREF'];
									                    
									                    $("#ICOLL_REFSJ").append("<option value='"+id+"'>"+name+"</option>");
									                }
									            }
									        });
									    });

									});
								</script>
								<?php
				                    $url_selJO	= site_url('c_production/c_mr180d0c/s3l4llj0/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				                ?>
				                <script>
				                    var urlJO = "<?php echo $url_selJO;?>";
				                    function getJOCODE1()
				                    {
				                        PRJCODE	= document.getElementById('PRJCODE').value;

				                        title 	= 'Select Item';
				                        w = 1000;
				                        h = 550;
				                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
				                        return window.open(urlJO, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                    }
				                </script>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Customer ?> / SJ No.</label>
		                          	<div class="col-sm-5">
		                                <input type="hidden" class="form-control" name="CUST_CODE" id="CUST_CODE" value="<?php echo $CUST_CODE; ?>" >
		                                <input type="text" class="form-control" name="CUST_CODE1" id="CUST_CODE1" value="<?php echo $CUST_CODE; ?>" onClick="pleaseCheck();" readonly>
		                          	</div>
		                          	<div class="col-sm-4">
		                                <select name="ICOLL_REFSJ" id="ICOLL_REFSJ" class="form-control select2" data-placeholder="SJ No.">
				                            <option value="<?php echo $ICOLL_REFSJ; ?>"> <?php echo $ICOLL_REFSJ; ?> </option>
				                        </select>
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $FinGoods ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="hidden" class="form-control" name="ICOLL_FG" id="ICOLL_FG" style="max-width:160px" value="<?php echo $ICOLL_FG; ?>" >
		                                <input type="text" class="form-control" name="ICOLL_FG1" id="ICOLL_FG1" value="<?php echo $ICOLL_FG; ?>" onClick="pleaseCheck();" readonly>
		                            </div>
		                        </div>
								<?php
									$selFG	= site_url('c_production/c_b0fm47/s3l4llF9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        ?>
		                        <script>
									var url1 = "<?php echo $selFG;?>";
									function pleaseCheck()
									{
										BOM_NAME	= $("#BOM_NAME").val();
										if(BOM_NAME == '')
										{
											swal("<?php echo $alert2; ?>",
											{
												icon: "warning",
											})
								            .then(function()
								            {
								                swal.close();
								                $('#BOM_NAME').focus();
								            });
											return false;
										}
										
										title = 'Select Item';
										w = 1000;
										h = 550;
										//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
										var left = (screen.width/2)-(w/2);
										var top = (screen.height/2)-(h/2);
										return window.open(url1, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
									}
								</script>
		                    </div>
		                </div>
		            </div>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Notes; ?> </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="ICOLL_NOTES" id="ICOLL_NOTES" value="<?php echo $ICOLL_NOTES; ?>" >
		                          	</div>
		                        </div>
		                        <div class="form-group">
		                          	<label for="inputName" class="col-sm-3 control-label">Total </label>
		                          	<div class="col-sm-9">
		                                <input type="text" class="form-control" name="ICOLL_QTYTOT" id="ICOLL_QTYTOT" value="<?php echo $ICOLL_QTYTOT; ?>" style="display: none;" >
		                                <input type="text" class="form-control" name="ICOLL_QTYTOT1" id="ICOLL_QTYTOT1" value="<?php echo number_format($ICOLL_QTYTOT, 2); ?>" readonly >
		                          	</div>
		                        </div>
		                        <div class="form-group" >
		                          	<label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
		                          	<div class="col-sm-9">
		                            	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $Complete; ?>">
										<?php
		                                    // START : FOR ALL APPROVAL FUNCTION
												?>
													<select name="ICOLL_STAT" id="ICOLL_STAT" class="form-control select2" <?php if($isDisabled == 1) { ?> disabled <?php } ?>>
														<option value="0"> --- </option>
														<option value="1"<?php if($ICOLL_STAT == 1) { ?> selected <?php } ?> >New</option>
														<option value="2"<?php if($ICOLL_STAT == 2) { ?> selected <?php } ?> ><?php echo $Complete; ?></option>
													</select>
												<?php
		                                    // END : FOR ALL APPROVAL FUNCTION
		                                ?>
		                            </div>
		                        </div>
		                        <?php
									//$url_Material	= site_url('c_production/c_b0fm47/s3l4llQRC/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		                        	$COLLDATA		= "$PRJCODE~$ICOLL_REFSJ";
									$url_Material	= site_url('c_production/c_b0fm47/s3l4llQRCM/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
									$url_qrlist		= site_url('c_production/c_b0fm47/s3l4llQRC/?id='.$this->url_encryption_helper->encode_url($COLLDATA));
		                        ?>
                                <div class="form-group" <?php if($ICOLL_STAT != 1 && $ICOLL_STAT == 4) { ?> style="display:none" <?php } ?>>
		                          	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <script>
                                            var url = "<?php echo $url_Material;?>";
                                            function selectitem()
                                            {
												IRC	= $("#ICOLL_REFSJ").val();

												ICOLL_NOTES	= $("#ICOLL_NOTES").val();
												if(ICOLL_NOTES == '')
												{
													swal("<?php echo $alert0; ?>",
													{
														icon: "warning",
													})
										            .then(function()
										            {
										                swal.close();
										                $('#ICOLL_NOTES').focus();
										            });
													return false;
												}
												
												CUST_CODE	= $("#CUST_CODE").val();
												if(CUST_CODE == 'none')
												{
													swal('<?php echo $alert1; ?>', 
							                        {
							                            icon: "warning",
							                        });
													$("#CUST_CODE").focus();
													return false;
												}
												
												ICOLL_FG	= $("#ICOLL_FG").val();
												if(ICOLL_FG == '')
												{
													swal('<?php echo $alert2; ?>', 
							                        {
							                            icon: "warning",
							                        });
													$("#ICOLL_FG").focus();
													return false;
												}
												
                                                title = 'Select Item';
                                                w = 1000;
                                                h = 550;
                                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                                var left = (screen.width/2)-(w/2);
                                                var top = (screen.height/2)-(h/2);
                                                return window.open(url+'&IRC='+IRC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                                            }

                                            var urlQR = "<?php echo $url_qrlist;?>";
                                            function selectqr()
                                            {
												IRC	= $("#ICOLL_REFSJ").val();

												ICOLL_NOTES	= $("#ICOLL_NOTES").val();
												if(ICOLL_NOTES == '')
												{
													swal('<?php echo $alert0; ?>', 
							                        {
							                            icon: "warning",
							                        })
							                        .then(function()
							                        {
														$("#ICOLL_NOTES").focus();
							                        });
													return false;
												}
												
												CUST_CODE	= $("#CUST_CODE").val();
												if(CUST_CODE == 'none')
												{
													swal('<?php echo $alert1; ?>', 
							                        {
							                            icon: "warning",
							                        })
							                        .then(function()
							                        {
														$("#CUST_CODE").focus();
							                        });
													return false;
												}
												
												ICOLL_FG	= $("#ICOLL_FG").val();
												if(ICOLL_FG == '')
												{
													swal('<?php echo $alert2; ?>', 
							                        {
							                            icon: "warning",
							                        });
													$("#ICOLL_FG").focus();
													return false;
												}
												
                                                title = 'Select Item';
                                                w = 1000;
                                                h = 550;
                                                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                                var left = (screen.width/2)-(w/2);
                                                var top = (screen.height/2)-(h/2);
                                                return window.open(urlQR+'&IRC='+IRC, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                                            }
                                        </script>
                                        <button class="btn btn-success" type="button" onClick="selectitem();" style="display: none;">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Manual
                                        </button>&nbsp;
                                        <button class="btn btn-warning" type="button" onClick="selectqr();">
                                        <i class='glyphicon glyphicon-qrcode'></i>&nbsp;&nbsp;QR
                                        </button>
                                    </div>
                                </div>
		                    </div>
		                </div>
		            </div>
                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center">No.</th>
                                      	<th width="10%" style="text-align:center" nowrap><?php echo $Code; ?> </th>
                                      	<th width="28%" style="text-align:center" nowrap><?php echo $ItemName; ?> </th>
                                      	<th width="5%" style="text-align:center" nowrap><?php echo $Unit; ?> </th>
                                        <th width="5%" style="text-align:center" nowrap>Qty </th>
                                      	<th width="17%" style="text-align:center" nowrap><?php echo $Notes; ?></th>
                                  	</tr>
									<?php
                                        $resultC	= 0;
                                        if($task == 'edit')
                                        {																
                                            $sqlDET		= "SELECT A.*, B.ITM_NAME
															FROM tbl_item_colld A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																AND B.PRJCODE = '$PRJCODE'
															WHERE A.ICOLL_NUM = '$ICOLL_NUM'";
                                            $result = $this->db->query($sqlDET)->result();
                                            // count data
                                            $sqlDETC	= "tbl_item_colld A WHERE A.ICOLL_NUM = '$ICOLL_NUM'";
                                            $resultC 	= $this->db->count_all($sqlDETC);
                                        }
                                        else
                                        {
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <?php
                                        }
                                            
                                        $i			= 0;
                                        $j			= 0;
                                        if($resultC > 0)
                                        {
                                            $GT_ITMPRICE	= 0;
                                            foreach($result as $row) :
                                                $currentRow  	= ++$i;
                                                $QRC_NUM 		= $row->QRC_NUM;
                                                $QRC_CODEV 		= $row->QRC_CODEV;
                                                $ITM_CODE 		= $row->ITM_CODE;
                                                $ITM_NAME 		= $row->ITM_NAME;
                                                $ITM_UNIT 		= $row->ITM_UNIT;
                                                $ITM_NOTES 		= $row->ITM_NOTES;
                                                $QRC_QTY 		= $row->QRC_QTY;
												
												$ITM_CODE1		= "$ITM_CODE $QRC_NUM";
												
                                                $itemConvertion	= 1;
												
                                                /*if ($j==1) {
                                                    echo "<tr class=zebra1>";
                                                    $j++;
                                                } else {
                                                    echo "<tr class=zebra2>";
                                                    $j--;
                                                }*/
                                                ?>
                                                <tr>
                                                <!-- NO URUT -->
                                                <td width="2%" height="25" style="text-align:center">
                                                    <?php
                                                        if($ICOLL_STAT == 1)
                                                        {
                                                            ?>
                                                                <a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            echo "$currentRow.";
                                                        }
                                                    ?>
                                                    <input style="display:none" type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
                                                    <input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:300px;">                                       			</td>
                                                <!-- ITEM CODE -->
                                                <td width="10%" style="text-align:left" nowrap>
                                                    <?php print $QRC_CODEV; ?>
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>QRC_NUM" name="data[<?php echo $currentRow; ?>][QRC_NUM]" value="<?php echo $QRC_NUM; ?>" width="10" size="15">
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>QRC_CODEV" name="data[<?php echo $currentRow; ?>][QRC_CODEV]" value="<?php print $QRC_CODEV; ?>" width="10" size="15">
                                                </td>
                                                
                                                <!-- ITEM CODE -->
                                                <td width="31%" style="text-align:left">
													<?php echo $ITM_NAME; ?>
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php print $ITM_CODE; ?>" width="10" size="15">
                                               	</td>
                                                
                                                <!-- ITEM NAME -->  
                                                <!-- <td width="28%"><?php echo $ITM_NAME; ?></td> -->
                                                    
                                                <!-- ITEM UNIT -->
                                                <td width="5%" style="text-align:center">
                                                    <?php print $ITM_UNIT; ?>  
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
                                                </td>
                                                    
                                                <!-- ITEM QTY -->
                                                <td width="5%" style="text-align:right;">
                                                    <?php print number_format($QRC_QTY, 2); ?>
                                                    <input type="hidden" id="data<?php echo $currentRow; ?>QRC_QTY" name="data[<?php echo $currentRow; ?>][QRC_QTY]" value="<?php print $QRC_QTY; ?>">
                                                </td>
                                                    
                                                <!-- ITEM NOTES -->
                                                <td width="17%" style="text-align:left">
                                                	<input type="text" class="form-control" name="data[<?php echo $currentRow; ?>][ITM_NOTES]" id="data<?php echo $currentRow; ?>ITM_NOTES" value="<?php echo $ITM_NOTES; ?>">
                                                </td>
                                            </tr>
                                                <?php
                                            endforeach;
                                            ?>
                                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
                                            <?php
                                        }
                                    ?>
                            	</table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                        	<?php
								if($task=='add')
								{
									if($ICOLL_STAT == 1 && $ISCREATE == 1)
									{
										?>
											<button class="btn btn-primary">
											<i class="fa fa-save"></i>
											</button>&nbsp;
										<?php
									}
								}
								else
								{
									if($ICOLL_STAT == 1 && $ISCREATE == 1)
									{
										?>
											<button class="btn btn-primary">
											<i class="fa fa-save"></i>
											</button>&nbsp;
										<?php
									}
								}
								$backURL	= site_url('c_production/c_b0fm47/gli4xIC0ll/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
								echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
							?>
                        </div>
                    </div>
                </form>
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

<?php
	if($LangID == 'IND')
	{
		$qtyDetail	= 'Detail item tidak boleh kosong.';
		$volmAlert	= 'Qty order tidak boleh nol.';
	}
	else
	{
		$qtyDetail	= 'Item Detail can not be empty.';
		$volmAlert	= 'Order qty can not be zero.';
	}
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

    //Date picker
    $('#datepicker4').datepicker({
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

	function add_IRCODE1(IRCODE) 
	{
		document.getElementById("IRCODEX").value = IRCODE;
		document.frmsrch.submitSrch.click();
	}
  
	var decFormat		= 2;
	
	function add_header(strItem) 
	{
		arrItem = strItem.split('|');
		JO_NUM	= arrItem[0];
		JO_CODE	= arrItem[1];
		
		$(document).ready(function(e) {
			$("#JO_NUM").val(JO_NUM);
            $("#JO_CODE").val(JO_CODE);
            $("#JO_CODE1").val(JO_CODE);
        });
	}
	
	function add_FG(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');
		
		PRJCODE 		= arrItem[0];
		ITM_CODE 		= arrItem[1];
		
		$("#ICOLL_FG").val(ITM_CODE);
		$("#ICOLL_FG1").val(ITM_CODE);
	}
	
	function add_item(strItem) 
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		arrItem = strItem.split('|');		
		//swal(arrItem);
		var objTable, objTR, objTD, intIndex, arrItem;
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		
		ilvl = arrItem[1];
		
		//validateDouble(arrItem[0],arrItem[1])
		if(validateDouble(arrItem[0], arrItem[1]))
		{
			swal("Double Item for " + arrItem[1]);
			return;
		}

		ICOLLQTYTOT		= document.getElementById('ICOLL_QTYTOT').value;
		
		QRC_NUM 		= arrItem[0];
		QRC_CODEV 		= arrItem[1];
		ITM_CODE 		= arrItem[2];
		ITM_NAME 		= arrItem[3];
		ITM_UNIT 		= arrItem[4];
		ITM_QTY 		= arrItem[5];
		ITM_QTYV 		= doDecimalFormat(RoundNDecimal(ITM_QTY, 2));
		ICOLLQTYTOT2	= parseFloat(ICOLLQTYTOT) + parseFloat(ITM_QTY);
		document.getElementById('ICOLL_QTYTOT').value 	= ICOLLQTYTOT2;
		document.getElementById('ICOLL_QTYTOT1').value 	= doDecimalFormat(RoundNDecimal(ICOLLQTYTOT2, 2));
		
		ITM_CODE1		= ITM_CODE+' '+QRC_NUM;
		
		//swal(Unit_Price);
		objTable 		= document.getElementById('tbl');
		intTable 		= objTable.rows.length;
		//swal('intTable = '+intTable)
		//intIndex = parseInt(document.frm.rowCount.value) + 1;
		intIndex = parseInt(objTable.rows.length);
		//intIndex = intTable;
		document.frm.rowCount.value = intIndex;
		
		objTR = objTable.insertRow(intTable);
		objTR.id = 'tr_' + intIndex;
		
		// CHECKBOX
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = "center";
		objTD.noWrap = true;
		objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="Checkbox" id="data['+intIndex+'][chk]" name="data['+intIndex+'][chk]" value="'+intIndex+'" onclick="pickThis(this,'+intIndex+')" style="display:none"><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// QRC_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+QRC_CODEV+'<input type="hidden" id="data'+intIndex+'QRC_NUM" name="data['+intIndex+'][QRC_NUM]" value="'+QRC_NUM+'" width="10" size="15"><input type="hidden" id="data'+intIndex+'QRC_CODEV" name="data['+intIndex+'][QRC_CODEV]" value="'+QRC_CODEV+'" width="10" size="15">';
		
		// ITM_CODE
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'right';
		objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" width="10" size="15">';
		
		// ITM_NAME
		/*objTD = objTR.insertCell(objTR.cells.length);
		objTD.align = 'left';
		objTD.innerHTML = ''+ITM_NAME+'';*/
		
		// ITM UNIT
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'center';
		objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" id="data'+intIndex+'ITM_UNIT" name="data['+intIndex+'][ITM_UNIT]" value="'+ITM_UNIT+'">';
		
		// ITM QTY
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.noWrap = true;
		objTD.innerHTML = ''+ITM_QTYV+'<input type="hidden" id="data'+intIndex+'QRC_QTY" name="data['+intIndex+'][QRC_QTY]" value="'+ITM_QTY+'">';
		
		// ITEM NOTES
		objTD = objTR.insertCell(objTR.cells.length);
		objTD.style.textAlign = 'right';
		objTD.innerHTML = '<input type="text" class="form-control" name="data['+intIndex+'][ITM_NOTES]" id="data'+intIndex+'ITM_NOTES" value="">';
				
		document.getElementById('totalrow').value = intIndex;
	}
	
	function validateDouble(vcode, QRC_CODEV) 
	{
		var thechk=new Array();
		var duplicate = false;
		
		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null) 
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		} 
		else 
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;

		for (var i=0;i<panjang;i++) 
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'QRC_NUM').value;
				if (elitem1 == vcode)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function getQtyITM(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_QTYX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_QTY').value 	= parseFloat(Math.abs(ITM_QTYX));
		document.getElementById('ITM_QTYX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_QTYX)),decFormat));
		
		var ITM_PRICE 	= document.getElementById('data'+theRow+'ITM_PRICE').value;
		ITM_TOTAL		= parseFloat(ITM_QTYX) * parseFloat(ITM_PRICE);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function getPRICE(thisVal, theRow)
	{
		var decFormat	= document.getElementById('decFormat').value;
		
		var ITM_PRICEX 	= eval(thisVal).value.split(",").join("");
		
		document.getElementById('data'+theRow+'ITM_PRICE').value 	= parseFloat(Math.abs(ITM_PRICEX));
		document.getElementById('ITM_PRICEX'+theRow).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_PRICEX)),decFormat));
		
		var ITM_QTY 	= document.getElementById('data'+theRow+'ITM_QTY').value;
		ITM_TOTAL		= parseFloat(ITM_QTY) * parseFloat(ITM_PRICEX);
		document.getElementById('data'+theRow+'ITM_TOTAL').value 	= parseFloat(Math.abs(ITM_TOTAL));
		document.getElementById('ITM_TOTALX'+theRow).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}
	
	function checkInp()
	{
		totRow	= document.getElementById('totalrow').value;
		
		JO_NUM	= $("#JO_NUM").val();
		if(JO_NUM == 0)
		{
			swal('<?php echo $selJO; ?>', 
            {
                icon: "warning",
            });
			$("#JO_NUM").focus();
			return false;
		}
		
		ICOLL_REFSJ	= $("#ICOLL_REFSJ").val();
		if(ICOLL_REFSJ == 0)
		{
			swal('<?php echo $selSJ; ?>', 
            {
                icon: "warning",
            });
			$("#ICOLL_REFSJ").focus();
			return false;
		}
		
		ICOLL_NOTES	= $("#ICOLL_NOTES").val();
		if(ICOLL_NOTES == '')
		{
			swal("<?php echo $alert0; ?>",
			{
				icon: "warning",
			})
            .then(function()
            {
                swal.close();
                $('#ICOLL_NOTES').focus();
            });
			return false;
		}
		
		ICOLL_FG	= $("#ICOLL_FG").val();
		if(ICOLL_FG == '')
		{
			swal('<?php echo $alert2; ?>', 
            {
                icon: "warning",
            });
			$("#ICOLL_FG").focus();
			return false;
		}
		
		ICOLL_STAT	= $("#ICOLL_STAT").val();
		if(ICOLL_STAT == 0)
		{
			swal('<?php echo $alert4; ?>', 
            {
                icon: "warning",
            });
			$("#ICOLL_STAT").focus();
			return false;
		}

		if(totRow == 0)
		{
			swal('<?php echo $qtyDetail; ?>', 
            {
                icon: "warning",
            });
			return false;
		}
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