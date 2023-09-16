<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Agustus 2018
 * File Name	= r_invselect_report.php
 * Location		= -
*/
if($viewType == 1)
{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exceldata.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
//echo ".<br>..<br>...<br><br>Sorry this page is under construction.<br>
//By. DIAN HERMANTO - IT Department.<br><br><br>";
//return false;
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata('appBody');
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

  	<style>
    	body
    	{
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
		}
		*{
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
	        width: 29.7cm;
	        min-height: 21cm;
	        padding: 0.5cm;
	        margin: 0.5cm auto;
	        border: 1px #D3D3D3 solid;
	        border-radius: 5px;
	        background: white;
	        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	    }
	    .subpage {
	        padding: 1cm;
	        height: 256mm;
	    }
	    
	    @page {
	        size: A4;
	        margin: 0;
	    }
	    @media print {
	        .page {
	            margin: 0;
	            border: initial;
	            border-radius: initial;
	            width: initial;
	            min-height: initial;
	            box-shadow: initial;
	            background: initial;
	            page-break-after: always;
	        }
	    }
		
		.inplabel {border:none;background-color:white;}
		.inplabelOK {border:none;background-color:white; color:#009933; font-weight:bold}
		.inplabelBad {border:none;background-color:white; color:#FF0000; font-weight:bold}
		.inplabelTOT {border:none;background-color:white; color:#06F; font-weight:bold}
		.inplabelTOTPPn {border:none;background-color:white; color:#933; font-weight:bold}
		.inplabelGT {border:none;background-color:white; color:#00F; font-weight:bold}
		.inpdim {border:none;background-color:white;}
	</style>

	<body style="overflow:auto">
		<div class="page">
			<section class="content">
			    <table width="100%" border="0" style="size:auto">
				    <tr>
				        <td width="19%">
				            <div id="Layer1">
				                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
				                <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
				                <a href="#" onClick="window.close();" class="button"> close </a>                </div>            </td>
				        <td width="42%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
				        <td width="39%" class="style2" style="text-align:center; font-weight:bold">&nbsp;</td>
				    </tr>
				    <tr>
				        <td class="style2" style="text-align:left; font-weight:bold;">&nbsp;</td>
				        <td class="style2">&nbsp;</td>
				        <td class="style2" style="text-align:left; font-weight:bold">&nbsp;</td>
				    </tr>
				    <tr>
				        <td rowspan="2" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" style="max-width:120px; max-height:120px" ></td>
				        <td colspan="2" class="style2" style="text-align:center; font-weight:bold; text-transform:uppercase; font-size:12px">LAPORAN UANG MUKA PEMASOK (Summary)</td>
				  	</tr>
				    <tr>
				        <td colspan="2" valign="top" class="style2" style="text-align:center; font-weight:bold; font-size:12px"><span class="style2" style="text-align:center; font-weight:bold; font-size:12px">s.d Periode : 
				        <?php
				            $StartDate1 = date('d/m/Y',strtotime($Start_Date));
				            $EndDate1 = date('d/m/Y',strtotime($End_Date));
							echo $EndDate1;
				        ?>
				        </span></td>
				    </tr>
				        &nbsp;
				    <tr>
				        <td colspan="3" class="style2" style="text-align:left; font-style:italic">&nbsp;</td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2" style="text-align:center"><hr /></td>
				    </tr>
				    <tr>
				        <td colspan="3" class="style2">
				            <table width="100%" border="1" rules="all">
				                <tr style="background:#CCCCCC">
				                  <th width="2%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000; border-left-width:2px; border-left-color:#000">NO.</th>
				                  <th width="7%" rowspan="2" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-bottom-width:2px; border-bottom-color:#000;">TGL.</th>
				                  <th colspan="8" nowrap style="text-align:center; font-weight:bold; border-top-width:2px; border-top-color:#000; border-right-width:2px; border-right-color:#000">UANG MUKA / DOWN PAYMENT (DP)</th>
				              </tr>
				                <tr style="background:#CCCCCC">
				                  <th width="11%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">PROYEK</th>
				                  <th width="14%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Supplier</th>
				                  <th width="19%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Deskripsi</th>
				                  <th width="10%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">Kategori Item</th>
				                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000">TOTAL DP</th>
				                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL POT.DP</th>
				                  <th width="8%" nowrap style="text-align:center; font-weight:bold; border-bottom-width:2px; border-bottom-color:#000;">TOTAL SISA DP</th>
				                  <th width="13%" nowrap style="text-align:center; font-weight:bold;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">PROYEK</th>
				                </tr>
				              <tr style="line-height:1px; border-left:hidden; border-right:hidden">
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td nowrap style="text-align:center;border:none">&nbsp;</td>
				                 <td colspan="3" nowrap style="text-align:center;border:none">&nbsp;</td>
				               </tr>
				               <tr>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-left-width:2px; border-left-color:#000">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-top-width:2px; border-top-color:#000;border-right-width:2px; border-right-color:#000">&nbsp;</td>
				               </tr>
				               <tr>
				                 <td colspan="6" nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;border-left-width:2px; border-left-color:#000"><b>TOTAL</b></td>
				                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000;">&nbsp;</td>
				                 <td nowrap style="text-align:center;border-bottom-width:2px; border-bottom-color:#000; border-right-width:2px; border-right-color:#000">&nbsp;</td>
				               </tr>
				                <tr style="display:none">
				                  <td colspan="10" nowrap style="text-align:center;">--- none ---</td>
				                </tr>
				            </table>
				      </td>
				    </tr>
				</table>
			</section>
		</div>
	</body>
</body>
</html>