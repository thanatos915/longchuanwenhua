<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="/zzyl/favicon.ico" type="image/x-icon"/>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/zzyl/Public/Admin/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="/zzyl/Public/Admin/css/bootstrap-responsive.css"/>
    <link rel="stylesheet" type="text/css" href="/zzyl/Public/Admin/css/style.css"/>
    <script type="text/javascript" src="/zzyl/Public/Admin/js/jquery.js"></script>
    <script type="text/javascript" src="/zzyl/Public/Admin/js/bootstrap.js"></script>
    <script type="text/javascript" src="/zzyl/Public/Admin/js/ckform.js"></script>
    <script type="text/javascript" src="/zzyl/Public/Admin/js/common.js"></script>
    <script src="/zzyl/Public/Admin/js/jquery.page.js"></script>


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


    </style>
    <style>
        .tcdPageCode{padding: 15px 20px;text-align: left;color: #ccc;text-align:center;} .tcdPageCode a{display: inline-block;color: #428bca;display: inline-block;height: 25px;	line-height: 25px;	padding: 0 10px;border: 1px solid #ddd;	margin: 0 2px;border-radius: 4px;vertical-align: middle;} .tcdPageCode a:hover{text-decoration: none;border: 1px solid #428bca;} .tcdPageCode span.current{display: inline-block;height: 25px;line-height: 25px;padding: 0 10px;margin: 0 2px;color: #fff;background-color: #428bca;	border: 1px solid #428bca;border-radius: 4px;vertical-align: middle;} .tcdPageCode span.disabled {
            display: inline-block;
            height: 25px;
            line-height: 25px;
            padding: 0 10px;
            margin: 0 2px;
            color: #bfbfbf;
            background: #f2f2f2;
            border: 1px solid #bfbfbf;
            border-radius: 4px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="form-inline definewidth m20" action="index.html" method="get">
    <button type="button" class="btn btn-success" id="addnew">新增用户</button>
</div>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <?php if(empty($list)): ?><tr>
            <th style="text-align:center">
                管理员太懒了！什么都没有留下！
            </th>
        </tr>
        <?php else: ?>
        <tr>
            <th>新闻标题</th>
            <th>更新时间</th>
            <th>操作</th>
        </tr><?php endif; ?>
    </thead>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
            <td><?php echo ($vo["title"]); ?></td>
            <td style="text-align:center"><?php echo (substr($vo["createtime"],0,10)); ?></td>
            <td style="width:120px;">
                <?php if($vo["top"] == 1): ?><a href="javascript:;" class="deltop" name="<?php echo ($vo["id"]); ?>">取消置顶</a>
                    <?php else: ?>
                    <a href="javascript:;" class="top" name="<?php echo ($vo["id"]); ?>">置顶</a><?php endif; ?>
                <a href="/zzyl/index.php/admin/newsEdit/<?php echo ($type); ?>/<?php echo ($vo["id"]); ?>.html">编辑</a>
                <a href="javascript:;" class="del" name="<?php echo ($vo["id"]); ?>">删除</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<div class="tcdPageCode"></div>
</body>
</html>
<script>
    $(".tcdPageCode").createPage({
        pageCount:<?php echo ($show["pageCount"]); ?>,
        current:<?php echo ($show["current"]); ?>,
        backFn: function ( p ){
            window.location.href = '/zzyl/index.php/admin/news/<?php echo ($type); ?>/' + p + '.html';
        }
    });
    $(function (){
        $('.top').click(function (){
            $.post('/zzyl/index.php/admin/index/newsTop.html',{id:$(this).attr('name'),top:1}, function ( data ){
                if ( data.status == 'y' ){
                    alert(data.msg);
                    document.location.reload();
                } else{
                    alert(data.msg);
                }
            }, 'json');
        });
        $('.deltop').click(function (){
            $.post('/zzyl/index.php/admin/index/newsTop.html',{id:$(this).attr('name'),top:0}, function ( data ){
                if ( data.status == 'y' ){
                    alert(data.msg);
                    document.location.reload();
                } else{
                    alert(data.msg);
                }
            }, 'json');
        });
        $('#addnew').click(function (){
            window.location.href = "/zzyl/index.php/admin/user/edit.html";
        });

        $('.del').click(function (){
            var r = confirm("确定要删除该数据？");
            var newsId = $(this).attr("name");
            if ( r == true ){
                $.post("/zzyl/index.php/admin/newsDel/<?php echo ($type); ?>.html",{id:newsId}, function ( data ){
                    if ( data.status == 'y' ){
                        alert(data.msg);
                        document.location.reload();
                    } else{
                        alert(data.msg);
                    }
                }, 'json');
            }
        });
    });

</script>