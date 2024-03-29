<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /> 
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/Validform_v5.3.2.css" />
    <script type="text/javascript" src="/Public/Admin/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/bootstrap.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/ckform.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/common.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.form.js"></script>
 

    <style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
.red{color:red;font-size:18px;margin-left:2px;}
.demo p{line-height:32px}
.image_btn{position: relative;overflow: hidden;margin-right: 4px;display:inline-block;*display:inline;padding:4px 10px 4px;font-size:14px;line-height:18px;*line-height:20px;color:#fff;text-align:center;vertical-align:middle;cursor:pointer;background-color:#5bb75b;border:1px solid #cccccc;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-bottom-color:#b3b3b3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
.image_btn input {position: absolute;top: 0; right: 0;margin: 0;border: solid transparent;opacity: 0;filter:alpha(opacity=0); cursor: pointer;}
.progress { position:relative; margin-left:100px; margin-top:-24px; width:200px;padding: 1px; border-radius:3px; display:none}
.bar {background-color: green; display:block; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; height:20px; display:inline-block; top:3px; left:2%; color:#fff }
.files{height:30px; line-height:22px; margin:10px 0}
.delimg{margin-left:20px; color:#090; cursor:pointer}

    </style>
    <link rel="stylesheet" href="/Public/Admin/kindeditor-4.1.10/themes/default/default.css" />
    <script charset="utf-8" src="/Public/Admin/kindeditor-4.1.10/kindeditor-all.js"></script>
    <script charset="utf-8" src="/Public/Admin/kindeditor-4.1.10/lang/zh_CN.js"></script>
    <script>
        var editor;
        KindEditor.ready(function(K) {
            editor = K.create('textarea[name="content"]', {
                width:'700px',
                height:'400px',
                cssPath : '/Public/Admin/kindeditor-4.1.10/plugins/code/prettify.css',
                uploadJson : '/index.php?s=/admin/index/uploadImg.html',
                fileManagerJson : '/index.php?s=/admin/index/uploadManager.html',
                allowFileManager : true
            });
        });
    </script>
</head>
<body>
    <form action="/index.php?s=/admin/goods/save.html" id="registerform">
        <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>">
    <table class="table table-bordered table-hover definewidth m10">
        <tr>
            <td width="10%" class="tableleft">项目名称<span class="red">*</span></td>
            <td><input type="text" value="<?php echo ($data["good_name"]); ?>" name="good_name" class="inputxt" datatype="*" nullmsg="请输入项目名称！" errormsg="请输入项目名称！" style="width:500px;" /></td>
        </tr>
        <tr>
            <td width="10%" class="tableleft">项目价格<span class="red">*</span></td>
            <td><input type="text" value="<?php echo ($data["good_price"]); ?>" name="good_price" class="inputxt" datatype="*" nullmsg="请输入项目价格！" errormsg="请输入项目价格！" style="width:500px;" /></td>
        </tr>
            <tr>
                <td width="10%" class="tableleft">补贴比例（<?php echo ($package["0"]["package_name"]); ?>）<span class="red">*</span></td>
                <td><input type="text" value="<?php echo ($data["good_scale1"]); ?>" name="good_scale1" class="inputxt" datatype="*" nullmsg="请输入补贴比例！" errormsg="请输入补贴比例！" style="width:500px;" />%</td>
            </tr>
        <tr>
            <td width="10%" class="tableleft">补贴比例（<?php echo ($package["1"]["package_name"]); ?>）<span class="red">*</span></td>
            <td><input type="text" value="<?php echo ($data["good_scale2"]); ?>" name="good_scale2" class="inputxt" datatype="*" nullmsg="请输入补贴比例！" errormsg="请输入补贴比例！" style="width:500px;" />%</td>
        </tr>
        <tr>
            <td width="10%" class="tableleft">补贴比例（<?php echo ($package["2"]["package_name"]); ?>）<span class="red">*</span></td>
            <td><input type="text" value="<?php echo ($data["good_scale3"]); ?>" name="good_scale3" class="inputxt" datatype="*" nullmsg="请输入补贴比例！" errormsg="请输入补贴比例！" style="width:500px;" />%</td>
        </tr>
        <tr>
            <td width="10%" class="tableleft">缩略图</td>
            <td>
                <div class="demo">
                    <div class="image_btn">
                        <span>添加附件</span>
                        <input id="fileupload" type="file" name="mypic">
                        <input  type="hidden" name="image" value="<?php echo ($data["image"]); ?>"  id="image">
                    </div>
                    <div class="progress" style="display: none;">
                        <span class="bar" style="width: 100%;"></span><span class="percent">100%</span>
                    </div>
                    <div class="files" <?php if(empty($data)): ?>style="display: none;<?php endif; ?>"><b><?php echo ($data["image"]); ?></b><span class="delimg" rel="<?php echo ($data["image"]); ?>">删除</span></div>
                <div id="showimg"><?php if(!empty($data)): ?><img src="/<?php echo ($data["image"]); ?>"><?php endif; ?></div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="tableleft">详细介绍</td>
            <td><textarea name="content"><?php echo ($data["good_detail"]); ?></textarea></td>
        </tr>
        <tr>
            <td class="tableleft"></td>
            <td>
                <button id="btn" class="btn btn-primary" type="button">保存</button> &nbsp;&nbsp;<button type="button" class="btn btn-success" name="backid" id="backid">返回列表</button>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
<script type="text/javascript" src="/Public/Admin/js/Validform_v5.3.2/Validform_v5.3.2_min.js"></script>
<script type="text/javascript">
$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btn span");
	$("#fileupload").wrap("<form id='myupload' action='/index.php?s=/admin/index/uploadFile.html' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg.empty();
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				btn.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success: function(data) {
    		    if( data.status == 'y' ){
                    files.find('b').html(data.name+"("+data.size+"k)");
                    files.find('span').attr('rel',data.pic);
                    files.show();
                    var img = "/"+data.pic;
                    showimg.html("<img src='"+img+"'>");
                    $("#image").val(data.pic);
                    btn.html("添加附件");
                }else{
                    btn.html("上传失败");
                    bar.width('0')
                    files.html(data.message);
                }
			},
			error:function(xhr){
				btn.html("上传失败");
				bar.width('0')
				files.html(xhr.responseText);
			}
		});
	});
	
	$(".delimg").click(function(){
        var pic = $(this).attr("rel");
		$.post("/index.php?s=/admin/index/uploadFile/act/delimg.html",{imagename:pic},function(msg){
			if(msg==1){
                alert('删除成功');
				showimg.empty();
				progress.hide();
                files.hide();
			}else{
				alert(msg);
			}
		},'json');
	});
});
</script>
<script>
$(function () {  

    $.Tipmsg.r='';
	$("#registerform").Validform({
	    tiptype:3,
        ajaxPost:true,
        btnSubmit:"#btn",
        callback:function(data){
            if(data.status == 'y'){
                $.Hidemsg();
                alert(data.msg);
                    <?php if(!empty($data)): ?>window.location.href="/index.php?s=/admin/goods/list.html";
                    <?php else: ?>
                        window.location.href="/index.php?s=/admin/goods/edit/"+data.id+".html";<?php endif; ?>
            }else{
                alert(data.msg);
            }
        return false;
        },
        beforeSubmit:function(curform){
            $('textarea[name="content"]').html(editor.html());
        },
	});

});
</script>