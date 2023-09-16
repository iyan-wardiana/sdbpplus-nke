<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2017
 * File Name	= admin_ttk.php
 * Location		= -
*/
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');

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

$selProject = '';
if(isset($_POST['submit']))
{
	$selProject = $_POST['selProject'];
}
	
// Project List
$sqlPLC	= "tbl_project";
$resPLC	= $this->db->count_all($sqlPLC);

$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
$sqlPL 	= "SELECT proj_Number, PRJCODE, PRJNAME
			FROM tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
			ORDER BY PRJNAME";
$resPL	= $this->db->query($sqlPL)->result();
?>
<script>
	function chooseProject(thisVal)
	{
		proj_Code	= thisVal.value;
		document.frmsrch.submitSrch.click();
	}
</script>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/style.css'; ?>");</style>
    <style type="text/css">@import url("<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/css/styleZebra.css'; ?>");</style>
    <title><?php echo $appName; ?> | Data Tables</title>
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
			
		if($TranslCode == 'exporttoexcel')$exporttoexcel = $LangTransl;
		if($TranslCode == 'Back')$Back = $LangTransl;
		if($TranslCode == 'CheckReport')$CheckReport = $LangTransl;
		if($TranslCode == 'Upload')$Upload = $LangTransl;
	
	endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">
<!-- Content Header (Page header) -->
<section class="content-header">
  


<h1>
    <?php echo $h2_title; ?>
    <small>Outstanding Approve</small>
  </h1>
  <br>
  <?php /*?><ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Tables</a></li>
    <li class="active">Data tables</li>
  </ol><?php */?>
</section>
<style>
	.search-table, td, th {
		border-collapse: collapse;
	}
	.search-table-outter { overflow-x: scroll; }
</style>
<!-- Main content -->

<div class="box">
<!-- /.box-header -->
<div class="box-body">
    <div class="search-table-outter">
    	<form name="myformupload" id="myformupload" enctype="multipart/form-data" method="post" action="<?php echo $myFormAction; ?>" onSubmit="return chcekFile();" >
            <table width="100%" border="0">
                <tr height="20">
                    <td valign="top">&nbsp;<input type="text" style="display:none" name="isUploaded" id="isUploaded" value="<?php echo $isUploaded; ?>" /></td>
                    <td valign="top">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="20" id="tblUpload">
                    <td width="20%" valign="top"><b>Upload File 1</b></td>
                    <td width="2%" valign="top"><b>:</b></td>
                    <td width="78%">  
                        <?php
                            if($error == 'Error')
                            {
                                echo "Upload error... !!! Pilih file txt.";
                                echo "<br /><br />";
                            }
                            elseif($error == 'Sukses')
                            {
                                $empID	= $this->session->userdata('Emp_ID');
                                $SQL = "DELETE FROM tbl_decreaseinvoice WHERE empID = '$empID'";
								$this->db->query($SQL);
                                                            
                                echo "Upload File $myFileNameShow Sukses... !!! Silahkan klik tombol Check Report atau Export to Excel.";
                                echo "<br /><br />";
                                
                                $sqlgetData	= "SELECT * FROM tbl_currentupreceipt WHERE empID = '$empID'";
                                $resultgetData = $this->db->query($sqlgetData)->result();
                                foreach($resultgetData as $nRow) :
                                    $FileUpName = $nRow->FileUpName;
                                    $empID 		= $nRow->empID;
                                    $IPAddress = $nRow->IPAddress;
                                    $myPath = "ttk_upload/$FileUpName";
                                    $file = file(base_url() . "$myPath"); # read file into array
                                    $count = count($file);
                                    if($count > 0) # file is not empty
                                    {
                                        $milestone_query = "INSERT into tbl_decreaseinvoice(ttk_no, ttk_date, ttk_duedate, ttk_supplier, ttk_projcode, ttk_nominal, ttk_voucherno, ttk_nominalVouch, ttk_vocuser, empID) values";
                                        $i = 1;
                                        foreach($file as $row)
                                        {
                                            $milestone 	= explode('|',$row);
                                            $mydatea 	= $milestone[2];
                                            $mydate1a	= date('Y-m-d',strtotime($mydatea));
                                            $mydateb 	= $milestone[3];
                                            $mydate1b	= date('Y-m-d',strtotime($mydateb));
                                            $myNomi		= floatval(preg_replace("/[^-0-9\.]/","",$milestone[6]));
                                            $myNomiVouch= floatval(preg_replace("/[^-0-9\.]/","",$milestone[8]));
                                            
                                            $milestone_query .= "('$milestone[1]', '$mydate1a', '$mydate1b', '$milestone[4]', '$milestone[5]', '$myNomi', '$milestone[7]', '$myNomiVouch', '$milestone[9]', '$empID')";
                                            $milestone_query .= $i < $count ? ',':'';
                                            $i++;
                                        }
										$this->db->query($milestone_query);
                                    }
                                endforeach;                    
                            }
                        ?>
                    
                        <input type="file" name="userfile[]" id="userfile1" size="20" />
                    </td>
                </tr>
                <tr height="20" id="tblUpload1">
                    <td width="20%" valign="top"><b>Upload File 2</b></td>
                    <td width="2%" valign="top"><b>:</b></td>
                    <td width="78%"> 
                        <input type="file" name="userfile[]" id="userfile2" size="20" />
                    </td>
                </tr>
                <tr height="20" id="tblUpload2">
                    <td width="20%" valign="top"><b>Upload File 3</b></td>
                    <td width="2%" valign="top"><b>:</b></td>
                    <td width="78%">         
                        <input type="file" name="userfile[]" id="userfile3" size="20" />
                        &nbsp;&nbsp;
                    </td>
                </tr>
            </table>
            <table width="100%" border="0">
                <tr height="20">
                    <td width="20%" valign="top">&nbsp;</td>
                    <td width="2%" valign="top">&nbsp;</td>
                    <td width="78%">
                        <!--<input type="submit" value="Upload File" class="btn btn-warning" style="width:120px;" />&nbsp;&nbsp;
                        <input name="btnDl2" type="button" id="btnDl2" style="width:120px;" value="Check Report" class="btn btn-success" onClick="checkReport(1);" />&nbsp;&nbsp;
                        <input name="btnDl" type="button" id="btnDl" style="width:120px;" value="Export to Excel" class="btn btn-primary" onClick="checkReport(2);" /> -->
                        
                        <button class="btn btn-warning" ><i class="cus-upload-16x16"></i>&nbsp;&nbsp;<?php echo "$Upload File"; ?></button>&nbsp;
                        <button type="button" class="btn btn-success" onClick="checkReport(1);"><i class="cus-check-report-16x16"></i>&nbsp;&nbsp;<?php echo $CheckReport; ?></button>&nbsp;
                        <button type="button" class="btn btn-primary" onClick="checkReport(2);"><i class="cus-excel-16x16"></i>&nbsp;&nbsp;<?php echo $exporttoexcel; ?></button>&nbsp;
                                           </td>
                </tr>
                <tr height="20">
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
            </table>
        </form>
        <script>
            function chcekFile()
            {
                var userfile1 = document.getElementById('userfile1').value;
                var userfile2 = document.getElementById('userfile2').value;
                var userfile3 = document.getElementById('userfile3').value;
                
                if(userfile1 == '' && userfile2 == '' && userfile3 == '')
                {
                    alert('Please insert file(s) txt which will you be uploaded.');
                    return false;
                }
                
                if(userfile1 != '')
                {
                    var isTXTFile1 = userfile1.substr(-3,3);
                    if(isTXTFile1 != 'TXT')
                    {
                        alert('You can upload TXT File Type only. On File 1.');
                        return false;
                    }
                }
                
                if(userfile2 != '')
                {
                    var isTXTFile2 = userfile2.substr(-3,3);
                    if(isTXTFile2 != 'TXT')
                    {
                        alert('You can upload TXT File Type only. On File 2.');
                        return false;
                    }
                }
                
                if(userfile3 != '')
                {
                    var isTXTFile3 = userfile3.substr(-3,3);
                    if(isTXTFile3 != 'TXT')
                    {
                        alert('You can upload TXT File Type only. On File 3.');
                        return false;
                    }
                }
            }
            
            function checkReport(myVal)
            {
                hasUploadFile = document.getElementById('isUploaded').value;
                if(hasUploadFile == 0)
                {
                    if(myVal == 1)
                    {
                        alert('Please upload file(s) before you Check Report.');
                    }
                    else
                    {
                        alert('Please upload file(s) before you Export File.');
                    }
                    return false;
                }
                var url = "<?php echo base_url().'index.php/c_finance/c_admin_ttk/export_txt/';?>"+myVal;
                title = 'Report';
                w = 780;
                h = 550;
                //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                var left = (screen.width/2)-(w/2);
                var top = (screen.height/2)-(h/2);
                return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left+', myVal = '+myVal);
            }
        </script>
    </div>
    <!-- /.box-body -->
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
</script>
<script>
	function getValueNo(thisVal)
	{
		myValue = thisVal;
		document.getElementById('myProjCode').value = myValue;
		document.getElementById('selProject').value = myValue;
		chooseProject(thisVal);
	}
	
	function chooseProject(thisVal)
	{
		document.frmselect.submit.click();
	}
		
	function vProjPerform()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjPerF; ?>';
		title = 'Select Item';		
		
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+screen.width+', height='+screen.height);
	}
		
	function vInpProjDet()
	{
		myVal = document.getElementById('myProjCode').value;
		
		if(myVal == '')
		{
			alert('Please select one of Project Code.')
			return false;
		}
		var url = '<?php echo $urlProjInDet; ?>';
		title = 'Select Item';		
		w = 900;
		h = 550;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
</script>

<?php 
$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>