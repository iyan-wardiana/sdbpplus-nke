<?php
/* 
 * Author   = Dian Hermanto
 * Create Date  = 21 Januari 2018
 * File Name  = print_po.php
 * Location   = -
*/
$this->load->view('template/head');
$appName  = $this->session->userdata('appName');

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
  $Display_Rows = $row->Display_Rows;
  $decFormat = $row->decFormat;
endforeach;
$decFormat    = 2;

$Start_DateY  = date('Y');
$Start_DateM  = date('m');
$Start_DateD  = date('d');
$Start_Date   = "$Start_DateY-$Start_DateM-$Start_DateD";

$LangID     = $this->session->userdata['LangID'];
if($LangID == 'IND')
{
  $Transl_01  = "Halaman ini merupakan contoh untuk mencetak dokumen permintaan pembelian. Silahkan ajukan kepada kami untuk membuat halaman yang sebenarnya.";
}
else
{
  $Transl_01  = "This page is an example to print a purchase request document. Please feel free to ask us to create an actual page.";
}

$sql_01 = "SELECT * FROM tappname";
$res_01 = $this->db->query($sql_01)->result();
foreach($res_01 as $row_01):
  $comp_name  = $row_01->comp_name;
  $comp_add = $row_01->comp_add;
  $comp_phone = $row_01->comp_phone;
  $comp_fax = $row_01->comp_fax;
  $comp_mail  = $row_01->comp_mail;
  $app_notes1 = $row_01->app_notes1;
endforeach;
class moneyFormat
{ 
  public function rupiah ($angka) 
  {
    $rupiah = number_format($angka ,2, ',' , '.' );
    return $rupiah;
  }
 
  public function terbilang ($angka)
  {
        $angka = (float)$angka;
        $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . ' Belas';
        } else if ($angka < 100) {
            $hasil_bagi = (int)($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) {
            return sprintf('Seratus %s', $this->terbilang($angka - 100));
        } else if ($angka < 1000) {
            $hasil_bagi = (int)($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
        } else if ($angka < 2000) {
            return trim(sprintf('Seribu %s', $this->terbilang($angka - 1000)));
        } else if ($angka < 1000000) {
            $hasil_bagi = (int)($angka / 1000); 
            $hasil_mod = $angka % 1000;
            return sprintf('%s Ribu %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
        } else if ($angka < 1000000000) {
            $hasil_bagi = (int)($angka / 1000000);
            $hasil_mod = $angka % 1000000;
            return trim(sprintf('%s Juta %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) {
            $hasil_bagi = (int)($angka / 1000000000);
            $hasil_mod = fmod($angka, 1000000000);
            return trim(sprintf('%s Milyar %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) {
            $hasil_bagi = $angka / 1000000000000;
            $hasil_mod = fmod($angka, 1000000000000);
            return trim(sprintf('%s Triliun %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
        } else {
            return 'Data Salah';
        }
    }
}

$moneyFormat = new moneyFormat();
?>
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/spritecss.css'; ?>">
</head>

<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>


<?php
  //$this->load->view('template/topbar');
  //$this->load->view('template/sidebar');
  
  $sqlTransl    = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
  $resTransl    = $this->db->query($sqlTransl)->result();
  foreach($resTransl as $rowTransl) :
    $TranslCode = $rowTransl->MLANG_CODE;
    $LangTransl = $rowTransl->LangTransl;
    
    if($TranslCode == 'From')$From = $LangTransl;
    if($TranslCode == 'To_x')$To_x = $LangTransl;
    if($TranslCode == 'InvoiceNo')$InvoiceNo = $LangTransl;
    if($TranslCode == 'OrderID')$OrderID = $LangTransl;
    if($TranslCode == 'DueDate')$DueDate = $LangTransl;
    if($TranslCode == 'To_x')$To_x = $LangTransl;
    if($TranslCode == 'To_x')$To_x = $LangTransl;
  endforeach;
  
  $PRJNAME  = '';
  $isHO   = 0;
  $sqlPRJ   = "SELECT PRJNAME, isHO
          FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
  $resPRJ   = $this->db->query($sqlPRJ)->result();
  foreach($resPRJ as $rowPRJ) :
    $PRJNAME  = $rowPRJ->PRJNAME;
    $isHO   = $rowPRJ->isHO;
  endforeach;
  if($isHO == 1)
    $AppDiv1  = "Finance Manager";
  else
    $AppDiv1  = "Cost Control";
  
  $sqlSUPL  = "SELECT SPLDESC, SPLADD1, SPLADD2, SPLTELP, SPLMAIL, SPLPERS, SPLNPWP
          FROM tbl_supplier WHERE SPLCODE = '$SPLCODE'";
  $resSUPL  = $this->db->query($sqlSUPL)->result();
  foreach($resSUPL as $rowSUPL) :
    $SPLDESC  = $rowSUPL->SPLDESC;
    $SPLADD1  = $rowSUPL->SPLADD1;
    $SPLADD2  = $rowSUPL->SPLADD2;
    $SPLPERS  = $rowSUPL->SPLPERS;
    $SPLTELP  = $rowSUPL->SPLTELP;
    $SPLMAIL  = $rowSUPL->SPLMAIL;
    $SPLNPWP  = $rowSUPL->SPLNPWP;
  endforeach;
  
  $PRJCODED   = $PRJCODE;
  if($PRJCODE == 'KTR')
    $PRJCODED = $PRJNAME;
  
  $CreaterNm  = '';
  $PO_PLANIR  = '';
  $sqlCREATER = "SELECT CONCAT(B.First_Name, ' ', B.Last_Name) as CreaterNm, A.PO_PLANIR
          FROM tbl_po_header A
            INNER JOIN tbl_employee B ON A.PO_CREATER = B.Emp_Id
          WHERE PO_NUM = '$PO_NUM'";
  $resCREATER = $this->db->query($sqlCREATER)->result();
  foreach($resCREATER as $rowCREATER) :
    $CreaterNm  = strtolower($rowCREATER->CreaterNm);
    $PO_PLANIR  = strtolower($rowCREATER->PO_PLANIR);
  endforeach;
?>

<body class="hold-transition skin-blue sidebar-mini">

<style type="text/css">
  .search-table, td, th {
    border-collapse: collapse;
  }
  .search-table-outter { overflow-x: scroll; }
  
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>

    <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
    </div>
    <div class="pad margin no-print" style="display:none">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
            <h4><i class="fa fa-info"></i> Note:</h4>
            <?php echo $Transl_01; ?>
        </div>
    </div>
    <!-- Main content -->
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/compLog.png'; ?>" style="max-width:120px; max-height:120px" ></h2>
            </div>
        </div>
        
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                <address>
                    <strong style="font-size:18px"><?php echo strtoupper($comp_name); ?></strong><br>
                    <?php echo $comp_add; ?><br>
                    NPWP : <?php echo $app_notes1; ?>
                </address>
            </div>
        
          <div class="col-sm-5 invoice-col">
            <b>&nbsp;</b><br>
            <br>
            <table width="100%">
            <tr>
              <td width="46%" style="text-align:left; font-size:14px">Email</td>
              <td width="2%" style="text-align:left;font-size:14px">:</td>
              <td width="52%" style="text-align:left;font-size:14px">
          <?php echo $comp_mail; ?>
              </td>
            </tr>
            <tr>
              <td width="46%" style="text-align:left; font-size:14px">Phone</td>
              <td width="2%" style="text-align:left;font-size:14px">:</td>
              <td width="52%" style="text-align:left;font-size:14px">
          <?php echo $comp_phone; ?>
              </td>
            </tr>
            <tr>
              <td width="46%" style="text-align:left; font-size:14px">Fax</td>
              <td width="2%" style="text-align:left;font-size:14px">:</td>
              <td width="52%" style="text-align:left;font-size:14px">
          <?php echo $comp_fax; ?>
              </td>
            </tr>
            <tr>
              <td width="46%" style="text-align:left; font-size:14px" nowrap>No. Proyek</td>
              <td width="2%" style="text-align:left;font-size:14px">:</td>
              <td width="52%" style="text-align:left;font-size:14px">
          <?php echo $PRJCODED; ?>
              </td>
            </tr>
            </table>
          </div>
        </div>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%" border="1">
                    <tr style="background-color:#999">
                        <th style="text-align:center; background-color:#999; font-weight:bold">PURCHASE ORDER</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%">
                    <tr>
                      <td style="text-align:center;">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>    
        <div class="row">
            <div class="col-xs-6 table-responsive">
              <table width="100%" border="1" style="background-color:#999">
                    <tr>
                      <td>
                        <table width="100%">
                          <tr>
                            <th width="23%" style="background-color:#999; text-align:left">No. PO</th>
                            <th width="1%" style="background-color:#999; text-align:left">:</th>
                            <th width="76%" style="background-color:#999; text-align:left"><?php echo $PO_CODE; ?></th>
                        </table>
                      </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6 table-responsive">
              <table width="100%" border="1" style="background-color:#999">
                    <tr>
                      <td>
                        <table width="100%">
                          <tr>
                            <th width="23%" style="background-color:#999; text-align:left">Supplier </th>
                      <th width="1%" style="background-color:#999; text-align:left">:</th>
                      <th width="76%" style="background-color:#999; text-align:left"><?php echo $SPLDESC; ?></th>
                        </table>
                      </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-6 table-responsive">
              <table width="100%" border="1">
                    <tr>
                      <td>
                        <table width="100%">
                          <tr>
                            <td width="23%">No. FPA</td>
                            <td width="1%">:</td>
                            <td width="76%"><?php echo $PR_CODE; ?></td>
                           </tr>
                           <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>
                <?php 
                  $date = date_create($PO_DATE);
                  echo date_format($date, 'd/m/Y'); 
                ?>
                            </td>
                           </tr>
                           <tr>
                            <td>Masa Berlaku</td>
                            <td>:</td>
                            <td><?php //echo $PO_TENOR; ?>7 hari</td>
                           </tr>
                           <?php
                if($PO_TENOR == 0)
                $descPay  = "Cash";
              else
                $descPay  = "$PO_TENOR hari setelah barang diterima";
               ?>
                           <tr>
                            <td nowrap style="vertical-align: top;">Cara Pembayaran</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="vertical-align: top;">
                              <?php
                              if($PO_PAYNOTES == '')
                                echo $descPay;
                              else
                                echo $PO_PAYNOTES;
                              ?> 
                            </td>
                           </tr>
                           <tr>
                            <td>Cara Pengiriman</td>
                            <td>:</td>
                            <td><?php echo $PO_SENTROLES; ?></td>
                           </tr>
                           <tr>
                            <td nowrap><b>Tgl. Terima Barang</b></td>
                            <td>:</td>
                            <td><b><?php echo date('d/m/Y', strtotime($PO_PLANIR)); ?></b></td>
                           </tr>
                        </table>
                      </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6 table-responsive">
              <table width="100%" border="1">
                    <tr>
                      <td>
                        <table width="100%">
                          <tr>
                            <td width="23%" style="vertical-align: top;">Alamat</td>
                            <td width="1%" style="vertical-align: top;">:</td>
                            <td width="76%" style="vertical-align: top;"><?php echo $SPLADD1; ?></td>
                           </tr>
                           <tr>
                            <td nowrap>Contact Person</td>
                            <td>:</td>
                            <td><?php echo $SPLPERS; ?></td>
                           </tr>
                           <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td><?php echo $SPLTELP; ?></td>
                           </tr>
                           <tr>
                            <td>Refrensi</td>
                            <td>:</td>
                            <td><?php echo $PO_REFRENS; ?></td>
                           </tr>
                           <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo $SPLMAIL; ?></td>
                           </tr>
                           <tr>
                            <td>NPWP</td>
                            <td>:</td>
                            <td><?php echo $SPLNPWP; ?></td>
                           </tr>
                        </table>
                      </td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%" border="1">
                    <tr style="background-color:#999">
                        <th style="text-align:left; background-color:#999; font-weight:bold">Dengan diterimanya PO ini, <br>supplier setuju dan sanggup untuk menyediakan barang seluruhnya sesuai quantity dan quality yang sudah disepakati.</th>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%" border="1" cellpadding="0.5">
                    <thead>
                        <tr style="background-color:#999">
                            <th width="2%" rowspan="2" style="background-color:#999; text-align:center">No</th>
                            <th width="35%" rowspan="2" style="background-color:#999; text-align:center">Nama Barang</th>
                            <th width="20%" rowspan="2" style="background-color:#999; text-align:center">Spesifikasi</th>
                            <th width="11%" rowspan="2" style="background-color:#999; text-align:center">Jumlah</th>
                            <th width="9%" rowspan="2" style="background-color:#999; text-align:center">Satuan</th>
                            <th width="11%" style="background-color:#999; text-align:center" nowrap>Harga Satuan</th>
                            <th width="12%" style="background-color:#999; text-align:center" nowrap>Jumlah Harga</th>
                        </tr>
                        <tr style="background-color:#999">
                          <th style="background-color:#999; text-align:center">Rp.</th>
                          <th width="12%" style="background-color:#999; text-align:center">Rp.</th>
                        </tr>
          </thead>
                    <tbody>
                    <?php
            $sqlPODET = "SELECT DISTINCT A.PO_NUM, A.PO_CODE, A.PO_DATE, A.PRJCODE, A.PR_NUM, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE,
                      A.ITM_UNIT, SUM(A.PR_VOLM) AS PR_VOLM, SUM(A.PO_VOLM) AS PO_VOLM, SUM(A.IR_VOLM) AS IR_VOLM,
                      SUM(A.IR_AMOUNT) AS IR_AMOUNT, A.IR_PAVG, A.PO_PRICE AS PR_PRICE, A.PO_PRICE AS ITM_PRICE, A.PO_DISP,
                      SUM(A.PO_DISC) AS PO_DISC, SUM(A.PO_COST) AS PO_COST, A.PO_DESC, A.PO_DESC1,
                      A.TAXCODE1, A.TAXCODE2, SUM(A.TAXPRICE1) AS TAXPRICE1, SUM(A.TAXPRICE2) AS TAXPRICE2,
                      B.ITM_NAME
                    FROM tbl_po_detail A
                      INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                    WHERE 
                      A.PO_NUM = '$PO_NUM' 
                      AND B.PRJCODE = '$PRJCODE'
                    GROUP BY A.ITM_CODE";
            $resPODET   = $this->db->query($sqlPODET)->result();
            $i      = 0;
            $j      = 0;
            
            $GTITMPRICE   = 0;
            $PO_TOTCOST   = 0;
            $TOTDISC    = 0;
            $TOTPPN     = 0;
            $TOTPRICE1    = 0;
            $TOTPRICE2    = 0;
            foreach($resPODET as $row) :
              $currentRow   = ++$i;
              $PR_NUM     = $row->PR_NUM;
              $PO_CODE    = $row->PO_CODE;
              $PO_DATE    = $row->PO_DATE;
              $JOBCODEDET   = $row->JOBCODEDET;
              $JOBCODEID    = $row->JOBCODEID;
              $ITM_CODE     = $row->ITM_CODE;
              $ITM_NAME     = $row->ITM_NAME;
              $ITM_UNIT     = $row->ITM_UNIT;
              $ITM_PRICE    = $row->ITM_PRICE;
              $PR_VOLM    = $row->PR_VOLM;
              $PO_VOLM    = $row->PO_VOLM;
              $IR_VOLM    = $row->IR_VOLM;
              $IR_AMOUNT    = $row->IR_AMOUNT;
              $IR_PAVG    = $row->IR_PAVG;
              $PO_PRICE     = $row->ITM_PRICE;
              $PO_DISP    = $row->PO_DISP;
              $PO_DISC    = $row->PO_DISC;
              $TAXPRICE1    = $row->TAXPRICE1;
              $ITM_TOTP   = $PO_VOLM * $ITM_PRICE;
              $TOTDISC    = $TOTDISC + $PO_DISC;
              $TOTPRICE1    = $TOTPRICE1 + $ITM_TOTP;
              $TOTPPN     = $TOTPPN + $TAXPRICE1;
              
              $PO_DESC    = $row->PO_DESC;
              ?>
                <tr>
                  <td nowrap><?php echo $currentRow; ?>.</td>
                  <td><?php echo $ITM_NAME; ?></td>
                  <td><?php echo $PO_DESC; ?></td>
                  <td style="text-align:right"><?php echo number_format($PO_VOLM, $decFormat); ?></td>
                  <td style="text-align:center"><?php echo $ITM_UNIT; ?></td>
                  <td style="text-align:right"><?php print number_format($ITM_PRICE, $decFormat); ?></td>
                  <td style="text-align:right"><?php print number_format($ITM_TOTP, $decFormat); ?></td>
                </tr>
              <?php
            endforeach;
            $TOTPRICE2    = $TOTPRICE1 - $TOTDISC;
            $TOTPRICE3    = $TOTPRICE2 + $TOTPPN;
          ?>
                    </tbody>
                </table>
          </div>
        </div>
        <!-- /.row -->
        <br>
    <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%" border="1">
                    <tr>
                        <th width="75%" style="text-align:left; background-color:#999; font-weight:bold">Terbilang :</th>
                        <td width="12%" style="widtd:50%">Total harga</td>
                        <td width="3%" nowrap>Rp. </td>
                        <td width="10%" style="text-align:right" nowrap><?php print number_format($TOTPRICE1, $decFormat); ?></td>
                    </tr>
                    <?php
            $TOTPRICE3A = round($TOTPRICE3,2);
            $terbilang  = $moneyFormat->terbilang($TOTPRICE3A);
          ?>
                    <tr>
                        <td rowspan="4" style="font-style:italic; font-size:14px; font-weight:bold"><?php echo $terbilang; ?> Rupiah</td>
                        <td nowrap>Discount 0 %</td>
                        <td>Rp. </td>
                        <td style="text-align:right" nowrap><?php print number_format($TOTDISC, $decFormat); ?></td>
                    </tr>
                    <tr>
                        <td nowrap>Total harga</td>
                        <td>Rp. </td>
                        <td style="text-align:right" nowrap><?php print number_format($TOTPRICE2, $decFormat); ?></td>
                    </tr>
                    <tr>
                        <td nowrap>PPN 10%</td>
                        <td>Rp. </td>
                        <td style="text-align:right" nowrap><?php print number_format($TOTPPN, $decFormat); ?></td>
                    </tr>
                    <tr style="font-weight:bold">
                        <td nowrap>Grand Total</td>
                        <td>Rp. </td>
                        <td style="text-align:right" nowrap><?php print number_format($TOTPRICE3, $decFormat); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class="row" style="display:none">
            <!-- accepted payments column -->
            <div class="col-xs-9">
                <p class="lead" style="display:none">Payment Methods:</p>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    <font style="font-style:italic; font-weight:bold"> Terbilang :</font><br>
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                </p>
            </div>
        <!-- /.col -->
        <div class="col-xs-3">
          <p class="lead" style="display:none"><?php echo $DueDate; ?> : <?php echo date('Y/m/d'); ?></p>
            <div class="table-responsive" style="display:none">
                <table border="1">
                    <tr>
                        <td style="widtd:50%">Subtotal:</td>
                        <td>Rp. </td>
                    </tr>
                    <tr>
                        <td>Tax (9.3%)</td>
                        <td>Rp. </td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td>Rp. </td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td>Rp. </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%" border="1">
                    <tr style="background-color:#999">
                        <th style="text-align:left; background-color:#999; font-weight:bold"><font color="#FFFFFF">Catatan :</font>
                        </th>
                    </tr>
                    <tr>
                      <td>
                        <ol type="1">
                          <li>Mohon Purchase Order yang sudah diterima ditandatangani, distempel dan di e-mail kembali ke
<?php echo $comp_mail; ?></li>
                            <li>Tagihan uang muka harus melampirkan Kwitansi Bermaterai, Invoice dan Faktur Pajak Asli</li>
                            <li>Tagihan pelunasan harus melampirkan Kwitansi Bermaterai, Invoice, Faktur Pajak Asli dan Surat Jalan yang sudah di
verifikasi oleh <?php echo $comp_name; ?>, termasuk Rekapitulasi Penerimaan Material beserta Berita Acara Serah Terima</li>
              <li>Tanpa faktur pajak hanya akan dibayarkan harga pokoknya saja tanpa PPN</li>
                            <li>Pengiriman barang harus melampirkan surat jalan yang mencantumkan Nomor Purchase Order ( PO )</li>
              <li>Invoice harus berdasarkan Nomor Purchase Order ( PO ) yang sudah diterbitkan.</li>
              <li>Pengiriman barang atau material yang quantitynya melebih quantity yang tertera di Purchase Order (PO) tanpa konfirmasi terlebih dahulu, maka akan dibayar sesuai quantity yang tertera di Purchase Order (PO).</li>
                        </ol>
                        </td>
                    </tr>
              </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table width="100%" border="1">
                    <tr style="background-color:#999">
                        <th style="text-align:left; background-color:#999; font-weight:bold"><font color="#FFFFFF">Lokasi Penerimaan Barang</font>
                        </th>
                    </tr>
                    <tr>
                      <td>
                          <table width="100%">
                              <tr>
                                  <td width="17%">Alamat</td>
                                    <td width="1%">:</td>
                                    <td width="82%"><?php echo $PO_RECEIVLOC; ?></td>
                                </tr>
                                <tr>
                                  <td width="17%" nowrap>Contact Person</td>
                                    <td width="1%">:</td>
                                    <td width="82%"><?php echo $PO_RECEIVCP; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
              </table>
            </div>
            <div class="col-xs-12">&nbsp;</div>
            <div class="col-xs-10 table-responsive">
              <table width="100%" border="1">
                  <tr style="background-color:#999">
                      <td width="30%" style="text-align:center"><font color="#FFFFFF"><b>Dibuat Oleh</b></font></td>
                        <td width="41%" style="text-align:center"><font color="#FFFFFF"><b>Diperiksa Oleh</b></font></td>
                        <td width="29%" style="text-align:center"><font color="#FFFFFF"><b>Disetujui</b></font></td>
                    </tr>
                    <tr height="60">
                      <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
            $CreaterNm1 = '';
            $CreaterNm2 = '';
            $sqlAPP = "SELECT APPROVER_1, APPROVER_2, APPROVER_3 FROM tbl_docstepapp WHERE MENU_CODE = 'MN020' AND PRJCODE = '$PRJCODE'";
            $resAPP = $this->db->query($sqlAPP)->result();
            foreach($resAPP as $rowAPP) :
              $APPROVER_1   = $rowAPP->APPROVER_1;
              $sqlCREATER1  = "SELECT CONCAT(B.First_Name, ' ', B.Last_Name) as CreaterNm1
                        FROM tbl_employee B WHERE Emp_Id = '$APPROVER_1'";
              $resCREATER1  = $this->db->query($sqlCREATER1)->result();
              foreach($resCREATER1 as $rowCREATER1) :
                $CreaterNm1 = strtolower($rowCREATER1->CreaterNm1);
              endforeach;
              $APPROVER_2   = $rowAPP->APPROVER_2;
              $sqlCREATER2  = "SELECT CONCAT(B.First_Name, ' ', B.Last_Name) as CreaterNm2
                        FROM tbl_employee B WHERE Emp_Id = '$APPROVER_2'";
              $resCREATER2  = $this->db->query($sqlCREATER2)->result();
              foreach($resCREATER2 as $rowCREATER2) :
                $CreaterNm2 = strtolower($rowCREATER2->CreaterNm2);
              endforeach;
            endforeach;
          ?>
                    <tr>
                      <td style="text-align:center"><?php echo ucwords($CreaterNm); ?></td>
                        <td style="text-align:center"><?php echo ucwords($CreaterNm1); ?></td>
                        <?php
              // Tanda tangan Disetujui dipatenkan berdasarkan MS.201991800070 : penandatanganan po
            ?>
                        <td style="text-align:center">Dedy Sutjipto</td>
                    </tr>
                    <tr>
                      <td style="text-align:center">Procurement</td>
                        <td style="text-align:center"><?php echo $AppDiv1; ?></td>
                        <td style="text-align:center">Operational Director</td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-2 table-responsive">
              <table width="100%" border="1">
                  <tr style="background-color:#999">
                      <td><font color="#FFFFFF"><b>Diterima Oleh</b></font></td>
                    </tr>
                    <tr height="60">
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align:center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align:center">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    <br>
      <!-- this row will not appear when printing -->
      <div class="row no-print" style="display:none">
        <div class="col-xs-12">
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
        </div>
      </div>
    </section>
    <!-- /.content -->
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

<?php 
//$this->load->view('template/js_data');
?>
<!--tambahkan custom js disini-->
<?php
$this->load->view('template/foot');
?>