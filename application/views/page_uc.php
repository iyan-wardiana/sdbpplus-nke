<!DOCTYPE html>
<html>
	<style>
		body, html {
		  	height: 100%;
		  	margin: 0;
		}

		.bgimg {
			background-image: url(<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/under_constuction3.png'; ?>);
			height: 100%;
			background-position: center;
			background-size: cover;
			position: relative;
			color: white;
			font-family: "Courier New", Courier, monospace;
			font-size: 25px;
		}

		.topleft {
			position: absolute;
			top: 0;
			left: 16px;
		}

		.bottomleft {
			position: absolute;
			bottom: 0;
			left: 16px;
		}

		.middle {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			text-align: center;
		}

		hr {
			margin: auto;
			width: 40%;
		}
	</style>
	<body>

		<div class="bgimg">
			<div class="topleft">
				<p><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/nkes/compLog_Login.png'; ?>" style="max-width:100px; max-height:100px" ></p>
			</div>
			<div class="middle">
			</div>
			<div class="bottomleft">
				<p>Some text</p>
			</div>
		</div>

	</body>
</html>