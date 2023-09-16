<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 April 2017
 * File Name	= itemreceipt.php
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

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$PRJCODE		= $PRJCODE;
$PRJNAME		= '';
$sqlPRJ 		= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE  = '$PRJCODE'";
$resultPRJ 		= $this->db->query($sqlPRJ)->result();
foreach($resultPRJ as $rowPRJ) :
	$PRJNAME 	= $rowPRJ->PRJNAME;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrapa.min.css'; ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/ionicons.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.min.css'; ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.minaa.css'; ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
        <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE.css'; ?>">
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
<?php
	$this->load->view('template/topbar');
	$this->load->view('template/sidebar');
	
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
		
		if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
		if($TranslCode == 'Add')$Add = $LangTransl;
		if($TranslCode == 'Edit')$Edit = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'ReceiptNumber')$ReceiptNumber = $LangTransl;
		if($TranslCode == 'Code')$Code = $LangTransl;
		if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
		if($TranslCode == 'Date')$Date = $LangTransl;
		if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
		if($TranslCode == 'Description')$Description = $LangTransl;
		if($TranslCode == 'Status')$Status = $LangTransl;
		if($TranslCode == 'Invoiced')$Invoiced = $LangTransl;
		if($TranslCode == 'Void')$Void = $LangTransl;
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <?php echo $h2_title; ?>
        <small><?php echo $PRJNAME; ?></small>
        </h1><br>
        <?php /*?><ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
        </ol><?php */?>
    </section>

    <!-- Main content -->
    
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <form class="form-horizontal" name="frmDel" id="frmDel" method="post" action="" onSubmit="return checkInp()">
            	<input type="hidden" name="colSelINV" id="colSelINV" value="">
            	<input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>">
           </form>
            <table id="example1" class="table table-bordered table-striped" width="100%">
            	<thead>
               	  <tr>
                      	<th width="3%" style="display:none"><input name="chkAll" id="chkAll" type="checkbox" value="" style="display:none" /></th>
                      	<th width="9%" style="text-align:center"><?php echo $Code ?>  </th>
                      	<th width="5%" style="text-align:center" nowrap><?php echo $Date ?> </th>
                      	<th width="10%" style="text-align:center">Ref. No. </th>
                      	<th width="57%" style="text-align:center"><?php echo $Description ?> </th>
                     	<th width="10%" style="text-align:center"><?php echo $Status ?> </th>
						<th width="3%" style="text-align:center" nowrap><?php echo $Invoiced ?> </th>
						<th width="3%" style="text-align:center" nowrap>&nbsp;</th>
                	</tr>
                </thead>
                <tbody id="show_data"> 
                    <?php 
                    $i = 0;
					$j = 0;
                    if($cData > 0)
                    {
                        $Unit_Type_Name2	= '';
						foreach($vData as $row) :
							$myNewNo 		= ++$i;
							$IR_NUM 		= $row->IR_NUM;
							$IR_CODE 		= $row->IR_CODE;
							$IR_DATE		= $row->IR_DATE;
							$PRJCODE 		= $row->PRJCODE;
							$SPLCODE 		= $row->SPLCODE;
							$PO_NUM			= $row->PO_NUM;
							$IR_REFER		= $row->IR_REFER;
							$IR_AMOUNT		= $row->IR_AMOUNT;
							$IR_NOTE		= $row->IR_NOTE;
							$IR_STAT		= $row->IR_STAT;
                            $IR_STATD		= $row->STATDESC;
                            $STATCOL		= $row->STATCOL;
                            $CREATERNM		= $row->CREATERNM;							
							$empName		= cut_text ("$CREATERNM", 15);
							
							$INVSTAT		= $row->INVSTAT;														
							if($INVSTAT == 'NI')
							{
								$INVSTATDes	= 'No';
								$STATCOLV	= 'danger';
							}
							elseif($INVSTAT == 'HI')
							{
								$INVSTATDes	= 'Half';
								$STATCOLV	= 'warning';
							}
							elseif($INVSTAT == 'FI')
							{
								$INVSTATDes	= 'Full';
								$STATCOLV	= 'success';
							}
							
							$REVMEMO		= $row->REVMEMO;							
							
							$secUpd		= site_url('c_inventory/c_ir180c15/up180c15dt/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
							
							if ($j==1) {
								echo "<tr class=zebra1>";
								$j++;
							} else {
								echo "<tr class=zebra2>";
								$j--;
							}
							?>
                                    <td style="text-align:center; display:none"> <?php print '<input name="chkDetail" id="chkDetail" type="radio" value="'.$IR_NUM.'" />'; ?> <?php print $IR_NUM; ?></td>
                                    <td nowrap><?php print $IR_CODE; ?> </td>
                                    <td style="text-align:center" nowrap><?php print date('d M Y', strtotime($IR_DATE)); ?> </td>
                                    <td nowrap><?php print $IR_REFER; ?> </td>
                                    <td><?php print $IR_NOTE; ?></td>
                                    <td nowrap style="text-align:center">
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:11px">
                                            <?php
                                                echo $IR_STATD;
                                             ?>
                                         </span>
                                    </td>
                                    <td nowrap style="text-align:center">
                                    <span class="label label-<?php echo $STATCOLV; ?>" style="font-size:11px">
                                        <?php
                                            echo $INVSTATDes;
                                         ?>
                                     </span>
                                    </td>
									<?php
                                        $secPrint	= site_url('c_inventory/c_ir180c15/printdocument/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
                                        $secPrintQR	= site_url('c_inventory/c_ir180c15/printQR/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
                                        $secVoid	= site_url('c_inventory/c_ir180c15/voiddocument/?id='.$this->url_encryption_helper->encode_url($IR_NUM));
										$CollID		= "IR~$IR_NUM~$PRJCODE";
										$secDel_DOC = base_url().'index.php/__l1y/trash_DOC/?id='.$CollID;
                                    ?>
                                    <input type="hidden" name="urlPrint<?php echo $myNewNo; ?>" id="urlPrint<?php echo $myNewNo; ?>" value="<?php echo $secPrint; ?>">
                                    <input type="hidden" name="urlPrintQR<?php echo $myNewNo; ?>" id="urlPrintQR<?php echo $myNewNo; ?>" value="<?php echo $secPrintQR; ?>">
                                    <input type="hidden" name="urlVoid<?php echo $myNewNo; ?>" id="urlVoid<?php echo $myNewNo; ?>" value="<?php echo $secVoid; ?>">
                                    <td nowrap style="text-align:center">
                                        <a href="<?php echo $secUpd; ?>" class="btn btn-info btn-xs" title="Update">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-primary btn-xs" title="Print" onClick="printDocument('<?php echo $myNewNo; ?>')">
                                            <i class="glyphicon glyphicon-print"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-warning btn-xs" title="<?php echo $Void; ?>" onClick="voidDoc('<?php echo $myNewNo; ?>')" style="display:none">
                                            <i class="glyphicon glyphicon-ban-circle"></i>
                                        </a>
                                        <a href="avascript:void(null);" class="btn btn-warning btn-xs" title="Show QRC" onClick=" <?php if($IR_STAT != 3 && $IR_STAT != 6) { ?> return false <?php } else {?> printQR('<?php echo $myNewNo; ?>' <?php } ?>)" <?php if($IR_STAT != 3 && $IR_STAT != 6) { ?>disabled="disabled" <?php } ?> style="display:none">
                                            <i class="glyphicon glyphicon-qrcode"></i>
                                        </a>
                                        <a href="#" onClick="deleteDOC('<?php echo $secDel_DOC; ?>')" title="Delete file" class="btn btn-danger btn-xs" <?php if($IR_STAT > 1) { ?>disabled="disabled" <?php } ?>>
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>                                 
                                </tr>
                                <?php 
                        endforeach;
                    }
					$secAddURL 		= site_url('c_inventory/c_ir180c15/a180c15dd/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					$secAddSO 		= site_url('c_inventory/c_ir180c15/a180c15dd50/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
					$secAddURLDir	= site_url('c_inventory/c_ir180c15/a180c15ddDir/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                    ?> 
                </tbody>
                <tr>
                    <td colspan="8">
                    	<?php
							if($ISCREATE == 1)
							{
								echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-cube"></i>&nbsp;&nbsp;IR</button>');
								echo anchor("$secAddSO",'&nbsp;&nbsp;<button class="btn btn-success"><i class="fa fa-cube"></i>&nbsp;&nbsp;IR - SO</button>');
								echo anchor("$secAddURLDir",'&nbsp;&nbsp;<button class="btn btn-warning"><i class="fa fa-cube"></i>&nbsp;&nbsp;IR Direct</button>&nbsp;');
							}
							echo anchor("$backURL",'&nbsp;<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
						?>
					</td>
			    </tr>                           
            </table>
      </div>
      	<!-- /.box -->
    </div>
</body>
</html>

<!-- jQuery 2.2.3 -->
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/jQuery/jquery-2.2.3.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap/js/bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/jquery.dataTables.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/datatables/dataTables.bootstrap.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/fastclick/fastclick.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE/dist/js/demo.js'; ?>"></script>
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

	<?php
		$secIdx_DOC	= site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
	?>
  
	function deleteDOC(thisVal)
	{
		var r = confirm("<?php echo $sureDelete; ?>");
		if (r == true) 
		{
			var idxData	= "<?php echo $secIdx_DOC; ?>";
			$.ajax({
				type  : 'ajax',
		        url   : thisVal,				
		        async : false,
		        //dataType : 'json',
				success: function(data)
				{
					//$( "#example1" ).load( ""+idxData+" #example1" );
					//$('#example1').DataTable().ajax.reload();
					window.location.reload(true);
					
					/*var html = '';
		            var i;
		            for(i=0; i<data.length; i++)
					{
						var IR_STAT 	= data[i].IR_STAT;
						var INVSTAT 	= data[i].INVSTAT;
						
						if(IR_STAT == 1)
						{
							var IR_STATD 	= 'New';
							var STATCOL		= 'warning';
						}
						else if(IR_STAT == 2)
						{
							var IR_STATD 	= 'Confirmed';
							var STATCOL		= 'primary';
						}
						else if(IR_STAT == 3)
						{
							var IR_STATD 	= 'New';
							var STATCOL		= 'success';
						}
						else if(IR_STAT == 4)
						{
							var IR_STATD 	= 'New';
							var STATCOL		= 'danger';
						}
						else if(IR_STAT ==5)
						{
							var IR_STATD 	= 'New';
							var STATCOL		= 'danger';
						}
						else if(IR_STAT == 6)
						{
							var IR_STATD 	= 'New';
							var STATCOL		= 'info';
						}
						else if(IR_STAT == 7)
						{
							var IR_STATD 	= 'New';
							var STATCOL		= 'warning';
						}
						else if(IR_STAT == 9)
						{
							var IR_STATD 	= 'Void';
							var STATCOL		= 'danger';
						}
						else
						{
							var IR_STATD 	= 'Fake';
							var STATCOL		= 'danger';
						}	
																		
						if(INVSTAT == 'NI')
						{
							var INVSTATDes	= 'No';
							var STATCOLV	= 'danger';
						}
						else if(INVSTAT == 'HI')
						{
							var INVSTATDes	= 'Half';
							var STATCOLV	= 'warning';
						}
						else if(INVSTAT == 'FI')
						{
							var INVSTATDes	= 'Full';
							var STATCOLV	= 'success';
						}
						
		                html += '<tr>'+
		                  		'<td style="text-align:center; display:none">'+data[i].IR_NUM+'</td>'+
		                        '<td nowrap>'+data[i].IR_CODE+'</td>'+
		                        '<td nowrap>'+data[i].IR_DATE+'</td>'+
		                        '<td nowrap>'+data[i].IR_REFER+'</td>'+
		                        '<td nowrap>'+data[i].IR_NOTE+'</td>'+
		                        '<td style="text-align:center"><span class="label label-'+STATCOL+'" style="font-size:11px">'+IR_STATD+'</span></td>'+
		                        '<td nowrap style="text-align:center"><span class="label label-'+STATCOLV+'" style="font-size:11px">'+INVSTATDes+'</span></td>'+
		                        '<td>'+data[i].INVSTAT+'</td>'+
		                        '</tr>';
		            }
		            $('#show_data').html(html);*/
				}
			});
		}
	}
	
	function printDocument(row)
	{
		var url	= document.getElementById('urlPrint'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function printQR(row)
	{
		var url	= document.getElementById('urlPrintQR'+row).value;
		w = 400;
		h = 300;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
	
	function voidDoc(row)
	{
		var url	= document.getElementById('urlVoid'+row).value;
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		window.open(url, 'formpopup', 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		form.target = 'formpopup';
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>