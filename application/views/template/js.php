</div><!-- /.content-wrapper -->
<?php
$sqlApp 		= "SELECT * FROM tappname";
$resultaApp 	= $this->db->query($sqlApp)->result();
foreach($resultaApp as $therow) :
	$appName	= $therow->app_name;
	$foot_name 	= $therow->foot_name;
endforeach;
?>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b><?php echo $foot_name; ?> Version</b> 2.0
    </div>
    <strong>Copyright &copy; 2017-2020 <a href="http://nusakonstruksi.com" target="_blank"><?php echo $foot_name; ?></a>.</strong> All rights reserved.
</footer>
</div><!-- ./wrapper -->
