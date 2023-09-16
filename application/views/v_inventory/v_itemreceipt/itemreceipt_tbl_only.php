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
                $APPROVE		= $row->APPROVE;
                $IR_NOTE		= $row->IR_NOTE;
                $IR_STAT		= $row->IR_STAT;
                
                if($IR_STAT == 0)
                {
                    $IR_STATD 	= 'fake';
                    $STATCOL	= 'danger';
                }
                elseif($IR_STAT == 1)
                {
                    $IR_STATD 	= 'New';
                    $STATCOL	= 'warning';
                }
                elseif($IR_STAT == 2)
                {
                    $IR_STATD 	= 'Confirm';
                    $STATCOL	= 'primary';
                }
                elseif($IR_STAT == 3)
                {
                    $IR_STATD 	= 'Approved';
                    $STATCOL	= 'success';
                }
                elseif($IR_STAT == 4)
                {
                    $IR_STATD 	= 'Revise';
                    $STATCOL	= 'danger';
                }
                elseif($IR_STAT == 5)
                {
                    $IR_STATD 	= 'Rejected';
                    $STATCOL	= 'danger';
                }
                elseif($IR_STAT == 6)
                {
                    $IR_STATD 	= 'Close';
                    $STATCOL	= 'danger';
                }
                elseif($IR_STAT == 7)
                {
                    $IR_STATD 	= 'Awaiting';
                    $STATCOL	= 'warning';
                }
                elseif($IR_STAT == 9)
                {
                    $IR_STATD 	= 'void';
                    $STATCOL	= 'danger';
                }
                else
                {
                    $IR_STATD 	= 'Awaiting';
                    $STATCOL	= 'warning';
                }
                
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
                        <td style="text-align:center; display:none"> <?php print '<input name="chkDetail" id="chkDetail" type="radio" value="'.$IR_NUM.'" />'; ?> </td>
                        <td nowrap><?php print $IR_CODE; ?> </td>
                        <td style="text-align:center" nowrap><?php print date('d M Y', strtotime($IR_DATE)); ?> </td>
                        <td nowrap><?php print $IR_REFER; ?> </td>
                        <td nowrap><?php print $IR_NOTE; ?></td>
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
                            <a href="avascript:void(null);" class="btn btn-warning btn-xs" title="Show QRC" onClick=" <?php if($IR_STAT != 3 && $IR_STAT != 6) { ?> return false <?php } else {?> printQR('<?php echo $myNewNo; ?>' <?php } ?>)" <?php if($IR_STAT != 3 && $IR_STAT != 6) { ?>disabled="disabled" <?php } ?>>
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
                    //echo anchor("$secAddSO",'&nbsp;&nbsp;<button class="btn btn-success"><i class="fa fa-cube"></i>&nbsp;&nbsp;IR - SO</button>');
                    echo anchor("$secAddURLDir",'&nbsp;&nbsp;<button class="btn btn-warning"><i class="fa fa-cube"></i>&nbsp;&nbsp;IR Direct</button>&nbsp;');
                }
                echo anchor("$backURL",'&nbsp;<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
            ?>
        </td>
    </tr>                           
</table>