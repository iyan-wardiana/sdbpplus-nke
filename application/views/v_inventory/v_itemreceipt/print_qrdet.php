<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | General UI</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/bootstrap/css/bootstrap.min.css'; ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/AdminLTE.min.css'; ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/css/skins/_all-skins.min.css'; ?>">
  <script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/jquery.min.js'; ?>"></script>
  <script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/qrcode.js'; ?>"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  	<style>
		.color-palette {
		height: 35px;
		line-height: 35px;
		text-align: center;
		}
		
		.color-palette-set {
		margin-bottom: 15px;
		}
		
		.color-palette span {
		display: none;
		font-size: 12px;
		}
		
		.color-palette:hover span {
		display: block;
		}
		
		.color-palette-box h4 {
		position: absolute;
		top: 100%;
		left: 25px;
		margin-top: -40px;
		color: rgba(255, 255, 255, 0.8);
		font-size: 12px;
		display: block;
		z-index: 7;
		}
		
		imgFlip {
		-webkit-transform: scaleX(-1);
		transform: scaleX(-1);
		}
		
		.example-modal .modal {
		position: relative;
		top: auto;
		bottom: auto;
		right: auto;
		left: auto;
		display: block;
		z-index: 1;
		}
		
		.example-modal .modal {
		background: transparent !important;
		}
		
		.flip-card {
		background-color: transparent;
		}
		
		.flip-card-inner {
		position: relative;
		width: 100%;
		height: 100%;
		text-align: center;
		transition: transform 0.6s;
		transform-style: preserve-3d;
		}
		
		.flip-card .flip-card-inner {
		-webkit-transform: scaleX(-1);
		transform: scaleX(-1);
		}
		
		.flip-card-front, .flip-card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
		}
		
		.flip-card-front {
		background-color: #bbb;
		color: black;
		z-index: 2;
		}
		
		.flip-card-back {
		background-color: #2980b9;
		color: white;
		transform: rotateY(180deg);
		z-index: 1;
		}
		
    	body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 10px; Arial, Helvetica, sans-serif;
			text-align:center;
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
        width: 7cm;
        min-height: 29.7cm;
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
			text-align:center;
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
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php
    $PRJCODE    = $default['PRJCODE'];

    //include APPPATH.'views/phpqrcode/qrlib.php';
    //$qrcDir   = 'qrcodelist/'.$PRJCODE.'/';

    $IR_NUM 	  = $default['IR_NUM'];
    $IR_CODE 	  = $default['IR_CODE'];
    $IR_DATE 	  = $default['IR_DATE'];
    $IR_DUEDATE = $default['IR_DUEDATE'];
    $CUST_CODE 	= $default['SPLCODE'];		// CUST_CODE
    $SO_NUM 	  = $default['PO_NUM'];		// SO NUM
    $SO_CODE 	  = $default['PO_CODE'];		// SO CODE
    $OFF_NUM 	  = $default['PR_NUM'];		// OFFERING NUM
    $OFF_CODE 	= $default['PR_CODE'];		// OFFERING NUM
    $BOM_NUM 	  = $default['IR_REFER'];		// BOM NUM
    $ITM_CODE   = $default['ITM_CODE'];
    $IR_ID      = $default['IR_ID'];

    $SO_DATE	  = '';
    $sqlSOH	    = "SELECT SO_DATE from tbl_so_header WHERE SO_NUM = '$SO_NUM'";
    $resSOH	    = $this->db->query($sqlSOH)->result();
    foreach($resSOH as $row) :
    	$SO_DATE	= $row->SO_DATE;
    endforeach;

    $CUST_DESC	= '';
    $sqlCUST	= "SELECT CUST_DESC from tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
    $resCUST	= $this->db->query($sqlCUST)->result();
    foreach($resCUST as $row) :
    	$CUST_DESC	= $row->CUST_DESC;
    endforeach;

    $JO_CODE	= 'NO JO';

    //$COMPIL	= "$IR_CODE~$JO_CODE~$SO_CODE~$CUST_DESC";
    $COMPIL	= "$IR_CODE~$SO_NUM";

    $sqlIRDC = "tbl_qrc_detail WHERE IR_NUM = '$IR_NUM' AND ITM_CODE = '$ITM_CODE' AND IR_ID = '$IR_ID'";
    $resIRDC = $this->db->count_all($sqlIRDC);

    $LangID   = $this->session->userdata['LangID'];

    $sqlTransl    = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    $resTransl    = $this->db->query($sqlTransl)->result();
    foreach($resTransl as $rowTransl) :
        $TranslCode = $rowTransl->MLANG_CODE;
        $LangTransl = $rowTransl->LangTransl;   
        if($TranslCode == 'QRCList')$QRCList = $LangTransl;
    endforeach;
?>
<input id="text" type="text" value="<?php echo $COMPIL; ?>" style="width:80%; display:none" />
<input id="totRow" type="text" value="<?php echo $resIRDC; ?>" style="width:80%; display:none" />
<div class="page">
    <div id="Layer1">
        <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
        <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
        <a href="#" onClick="window.close();" class="button"> close </a>
          
      <div class="example-modal">
          <div class="modal modal-success">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <!-- <div class="modal-header" style="text-align: center;">
                          <h4 class="modal-title"><?php echo "$QRCList $IR_CODE"; ?></h4>
                      </div> -->
                      <div class="modal-footer">
                          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="ImgStat(1)">Normal</button>
                          <button type="button" class="btn btn-outline pull-right" onclick="ImgStat(2)"><i class="glyphicon glyphicon-transfer"></i>&nbsp;&nbsp;&nbsp;Flip</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
    <div class="box-body">
        <div id="imgCenter">
            <?php
                $totRow = 0;
                if($resIRDC > 0)
                {
                  $sqlIRD = "SELECT QRC_NUM, QRC_CODEV, QRC_DATE, IR_CODE, REC_DESC, QRC_PATT 
				  				FROM tbl_qrc_detail WHERE IR_NUM = '$IR_NUM' AND ITM_CODE = '$ITM_CODE' AND IR_ID = '$IR_ID'";
                  $resIRD = $this->db->query($sqlIRD)->result();
                  $i      = 0;
                  foreach($resIRD as $row) :
                      $totRow     = ++$i;
                      $QRC_NUM    = $row->QRC_NUM;
                      $QRC_CODEV  = $row->QRC_CODEV;
                      $QRC_DATE   = $row->QRC_DATE;
                      $IR_CODE    = substr($row->IR_CODE, -10);
                      $REC_DESC   = $row->REC_DESC;
                      $QRC_PATT   = $row->QRC_PATT;
    
                      /* CREATED @ RECEIPT APPROVAL
                      $qrc_fill   = $QRC_NUM;
                      $qrc_name   = "$QRC_NUM.png";
                      $qrc_qlty   = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
                      $qrc_size   = 4; //batasan 1 paling kecil, 10 paling besar
                      $qrc_padd   = 0;
                      QRCode::png($qrc_fill, $qrcDir.'/'.$qrc_name, $qrc_qlty, $qrc_size, $qrc_padd);*/
                      ?>
                        <div class="col-sm-4 col-md-2 center" style="text-align:center" id="normal_<?php echo $totRow; ?>">
                            <div class="color-palette-set" style="text-align:center">
                                <?php
                                	$imgQRC  = base_url("qrcodelist/$PRJCODE/$QRC_NUM.png");
                                ?>
                                <div class="timeline-body">
                                	<img src="<?php echo $imgQRC; ?>" class="user-image;" alt="User Image"/>
                                </div>
                                <div style="font-size:12px; white-space:nowrap"> 
                                    <span style="text-align:center">
                                        <?php 
                                            //echo "$IR_CODE&nbsp;&nbsp;$QRC_PATT";
                                            echo "<br>$QRC_CODEV<br><br><br>";
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-2" style="display: none;" id="flip_<?php echo $totRow; ?>">
                            <div class="color-palette-set">
								<?php
                                	$imgQRC  = base_url("qrcodelist/$PRJCODE/$QRC_NUM.png");
                                ?>
                                <div class="timeline-body">
                                    <div class="flip-card">
                                        <div class="flip-card-inner">
                                        	<img src="<?php echo $imgQRC; ?>" class="user-image; center" alt="User Image"/>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-size:12px; white-space:nowrap"> 
                                    <span style="text-align:center">
                                        <div id="flipT_<?php echo $totRow; ?>">
                                          <?php 
                                              //echo "$IR_CODE&nbsp;&nbsp;$QRC_PATT";
                                              echo "<br>$QRC_CODEV<br><br><br>";
                                          ?>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                      <?php
                  endforeach;
                }
            ?>
            <input type="hidden" name="totRow" id="totRow" value="<?php echo $totRow; ?>">
        </div>
    </div>
</div>

<script type="text/javascript">
function makeCode () 
{
  totRow       = document.getElementById('totRow').value;

  for(i=1;i<=totRow;i++)
  {
  	//var elText   = document.getElementById("text");
    var qrcode = new QRCode(document.getElementById('qrcode'+i), {
      width : 100,
      height : 100
    });
    var elText   = document.getElementById('data'+i);
  	
  	if (!elText.value) {
  		alert("Input a text");
  		elText.focus();
  		return;
    }
	   qrcode.makeCode(elText.value);
  }
}

makeCode();

$("#text").
	on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

  function ImgStat(statVal)
  {
      totRow  = document.getElementById('totRow').value;

      if(statVal == 1)
      {
          for(i=1; i <= totRow; i++)
          {
              document.getElementById('normal_'+i).style.display  = '';
              document.getElementById('flip_'+i).style.display    = 'none';
          }
      }
      else
      {
          for(i=1; i <= totRow; i++)
          {
              document.getElementById('normal_'+i).style.display  = 'none';
              document.getElementById('flip_'+i).style.display    = '';
              document.getElementById('flipT_'+i).style.transform = 'rotateY(180deg)';
          }
      }
  }
</script>
</body>