<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>特尊医疗</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<!-- <meta name="format-detection" content="telephone=no"/> -->
	<meta name="format-detection" content="email=no"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
	<link rel="stylesheet" href="/Public/Home/css/style.css">
	<link rel="stylesheet" href="/Public/Home/js/swiper/css/swiper.min.css">
    <script src="/Public/Home/js/jquery.min.js?v=20160924"></script>
    <script  src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <script type="text/javascript" src="/Public/Home/js/layer/layer.js"></script>
</head>
<body>

	<header>
		<h1>申请退款</h1>
		<a href="" class="glyphicon glyphicon-chevron-left"></a>
	</header>

	<footer>
    <menu>
        <a href="<?php echo U('index/index');?>" <?php if(empty($action)): ?>class="active"<?php endif; ?>>
            <span class="glyphicon glyphicon-home"></span>
            <small>首页</small>
        </a>
        <a href="<?php echo U('index/goods');?>" <?php if($action == 'info'): ?>class="goods"<?php endif; ?>>
            <span class="glyphicon glyphicon-shopping-cart"></span>
            <small>购买服务</small>
        </a>
        <a href="<?php echo U('index/order');?>" <?php if($action == 'order'): ?>class="active"<?php endif; ?>>
            <span class="glyphicon glyphicon-list"></span>
            <small>订单详情</small>
        </a>

        <a href="<?php echo U('index/info');?>" <?php if($action == 'info'): ?>class="active"<?php endif; ?>>
            <span class="glyphicon glyphicon-user"></span>
            <small>会员信息</small>
        </a>
    </menu>
</footer>
	<section class="page">
        <!-- Swiper -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php if(is_array($flash)): $i = 0; $__LIST__ = $flash;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
           <img src="/<?php echo ($vo["image"]); ?>" alt="">
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <!-- 启用下标 -->
    <div class="swiper-pagination"></div>
    <!-- 启用左右箭头 -->
    <!--
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    -->
</div>



	    <!-- form start -->
		<form action="<?php echo U('index/refund', array('id'=>$data['id']));?>" method="post">
			<fieldset>
				<div class="row" style="height:6rem">
					<label for="" style="line-height:6rem">退款原因</label>
					<div class="form-group">
						<textarea name="remark"  cols="30" rows="5" style="border:1px solid #ccc" placeholder="请输入退款原因"></textarea>
					</div>
				</div>

				<input type="hidden" name="order_id" value="<?php echo ($data["id"]); ?>">
				<input type="submit" id="submit" value="提交">

			</fieldset>
		</form>
	    <!-- form end -->

	</section>

	<script src="/Public/Home/js/swiper/js/swiper.jquery.min.js"></script>

	<script src="/Public/Home/js/citys/distpicker.data.js"></script>
	<script src="/Public/Home/js/citys/distpicker.js"></script>
    <script src="/Public/Home/js/sub_form.js"></script>
	<script src="/Public/Home/js/layer/layer.js"></script>
	<script>
		var swiper = new Swiper('.swiper-container', {
			autoplay : 3000,    //可选选项，自动滑动
	        pagination: '.swiper-pagination',
	        paginationClickable: true
	        // 启用箭头
	        // nextButton: '.swiper-button-next',
	        // prevButton: '.swiper-button-prev'
	    });

		$(function(){

			// user-tab
			$('.user-tab menu button').on('touchend',function(){
				var	self =	$(this),
					nums =  self.index();

				self.addClass('active').siblings('button').removeClass('active');
				$('.user-item ul').eq(nums).show().siblings('ul').hide();
			})


			$('.page').on('click','.img-list .glyphicon-remove',function(){
                var id = $(this).attr('data-id');
                var img_str = $('#bl_image').val();
                var img_arr = img_str.split(',');
                for(var v in img_arr){
                    if( img_arr[v] == id ){
                        img_arr.splice(v,1);
                    }
                }
                var new_img_str = img_arr.join(',');
                $('#bl_image').val(new_img_str);
                $(this).parent().remove();
            })
			$('#clist').click(function(){
				layer.open({
					type: 1,
					content: '<div style="width:90%;height:80%;margin: 0 auto"><img src="<?php echo ($clist["path"]); ?>"  alt=""><a href="javascript:;" style="    display: block; color: #ffffff; -webkit-border-radius: 4px; -moz-border-radius: 4px; -o-border-radius: 4px; border-radius: 4px; margin: 1rem auto; background-color: #31619d; width: 5rem; height: 1.5rem; border-width: 0;    text-align: center; line-height: 1.5rem;" onclick="layer.closeAll();">关闭</a></div>',
					shadeClose:true,
					shade:true,
					style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;',
				});
			})
		})

	</script>
    <!-- 微信SDK -->
    <script>
        wx.config({
                appId: '<?php echo ($signPackage["appId"]); ?>', // 必填，公众号的唯一标识
                timestamp: <?php echo ($signPackage["timestamp"]); ?>, // 必填，生成签名的时间戳
            nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>', // 必填，生成签名的随机串
            signature: '<?php echo ($signPackage["signature"]); ?>',// 必填，签名，见附录1
            jsApiList: ['chooseImage','previewImage','uploadImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.ready(function(){
            document.querySelector('#bl_img').onclick = function () {
                var images = {
                    localId: [],
                    serverId: []
                };
                function upload(i, length, serverId) {
                    wx.uploadImage({
                        localId: images.localId[i],
                        success: function (res) {
                            i++;
                            serverId.push(res.serverId);
                            if (i < length) {
                                upload(i, length, serverId);
                            }
                            if(i >= length){
                                layer.open({type: 2});
                                $.post("<?php echo U('Index/uploadImg');?>", {serverIds:'"'+serverId+'"'},function(data){
                                    if( data.status == 'y' ){
                                        var server_img = data.server.server_img;
                                        var server_str = '';
                                        $('#bl_image').val(data.server.server_id);
                                        for( var i =0;i < server_img.length;i++ ){
                                           $('#bl_btn').before('<li> <img src="'+server_img[i]['path']+'" alt=""> <span class="glyphicon glyphicon-remove" data-id="'+server_img[i]['id']+'"></span> </li>');
                                            layer.closeAll();
                                        }
                                    }else{
                                        alert(data.msg);
                                        layer.closeAll();
                                    }
                                },'json')
                            }
                        },
                        fail: function (res) {
                            alert(JSON.stringify(res));
                        }
                    });
                }
                wx.chooseImage({
                    count: 9,
                    success: function (res) {
                        images.localId = res.localIds;
                        var i = 0, length = images.localId.length;
                        serverId = [];
                        setTimeout(function(){
                            upload(i, length, serverId)
                        },100)
                    }
                });
            };
            /*
            document.querySelector('.item').onclick = function () {
                wx.previewImage({
                    urls: [
                    <?php if(is_array($photo)): $i = 0; $__LIST__ = $photo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>'http://'+window.location.host+'<?php echo ($vo["path"]); ?>',<?php endforeach; endif; else: echo "" ;endif; ?>
                ]
            });
            };
           */
        });
    </script>
</body>
</html>