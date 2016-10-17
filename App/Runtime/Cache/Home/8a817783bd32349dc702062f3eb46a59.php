<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>系统提示</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<!-- <meta name="format-detection" content="telephone=no"/> -->
	<meta name="format-detection" content="email=no"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
	<link rel="stylesheet" href="/longhcuanwenhua/Public/Home/css/style.css">
</head>
<body>
	
	<header>
		<h1>系统提示</h1>
		<a href="" class="glyphicon glyphicon-chevron-left"></a>
	</header>

	<section class="page">
		
		<div class="tips success">
			
			<span class="glyphicon glyphicon-ok"></span>

			<strong><?php echo ($message); ?></strong>

			<button class="btn btn-info btn-block" id="btn">确定</button>

		</div>

	</section>
	
	<script src="/longhcuanwenhua/Public/Home/js/jquery.min.js"></script>
	<script>
		$('#btn').click(function (){
			window.location.href = '<?php echo ($url); ?>';
		})
	</script>
</body>
</html>