<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title><?php echo empty($page_title) ? C('WEB_SITE_TITLE') : $page_title; ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
    <meta content="no-cache" http-equiv="pragma">
    <meta content="0" http-equiv="expires">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="stylesheet" type="text/css" href="/yimei/Public/Home/css/mobile_module.css?v=<?php echo SITE_VERSION;?>" media="all">
    <script type="text/javascript">
		//静态变量
		var SITE_URL = "<?php echo SITE_URL;?>";
		var IMG_PATH = "/yimei/Public/Home/images";
		var STATIC_PATH = "/yimei/Public/static";
		var WX_APPID = "<?php echo ($jsapiParams["appId"]); ?>";
		var	WXJS_TIMESTAMP='<?php echo ($jsapiParams["timestamp"]); ?>'; 
		var NONCESTR= '<?php echo ($jsapiParams["nonceStr"]); ?>'; 
		var SIGNATURE= '<?php echo ($jsapiParams["signature"]); ?>';
	</script>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="/yimei/Public/static/bootstrap/js/html5shiv.js?v=<?php echo SITE_VERSION;?>"></script>
<![endif]-->

<!--[if lt IE 9]>
<script type="text/javascript" src="/yimei/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/yimei/Public/static/jquery-2.0.3.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="/yimei/Public/static/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/yimei/Public/static/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript" src="/yimei/Public/static/zclip/ZeroClipboard.min.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript" src="/yimei/Public/Home/js/dialog.js?v=<?php echo SITE_VERSION;?>"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="minify.php?f=/yimei/Public/Home/js/prefixfree.min.js,/yimei/Public/Home/js/m/dialog.js,/yimei/Public/Home/js/m/flipsnap.min.js,/yimei/Public/Home/js/m/mobile_module.js&v=<?php echo SITE_VERSION;?>"></script>
</head>	
<header class="mui-bar mui-bar-nav custom-theme-blue">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title"><?php echo ($commonTopName); ?></h1>
</header>
<link rel="stylesheet" href="/yimei/Public/static/mui.3.5.0/css/mui.min.css?v=<?php echo SITE_VERSION;?>">
<link href="<?php echo ADDON_PUBLIC_PATH;?>/css.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
<body>
	<div  class="mui-content">
        <div class="my_order">
            <div class="mui-card" style="margin: 0px;">
                <div class="mui-row">
                    <div class="mui-card-header">
                       <div class=" mui-col-xs-3">
                            <li class="mui-table-view-cell">编号</li>
                         </div>
                        <div class="mui-col-xs-3">
                            <li class="mui-table-view-cell">原文</li>
                         </div>
                        <div class="mui-col-xs-3">
                            <li class="mui-table-view-cell">译文</li>
                         </div>
                          <div class="mui-col-xs-3">
                            <li class="mui-table-view-cell">状态</li>
                         </div>
                    </div>
              </div>
                <div class="mui-row">
                    <ul  class="mui-table-view">
                        <?php if(is_array($orderList)): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell mui-collapse">
                                <a class="mui-navigate-right">
                                    <div class=" mui-col-xs-3" style="float: left;"><?php echo ($vo["order_number"]); ?></div>
                                    <div class=" mui-col-xs-2" style="float: left;"><?php echo ($vo["after_content"]); ?></div>
                                    <div class=" mui-col-xs-4" style="float: left;"><?php echo ($vo["after_content"]); ?></div>
                                    <div class=" mui-col-xs-1">
                                        <?php if($vo["pay_status"] == 0): ?><span class="mui-badge mui-badge-danger mui-badge-inverted">待支付</span>
                                            <?php elseif($vo["pay_status"] == 1): ?>
                                            <span class="mui-badge mui-badge-danger mui-badge-inverted">等待翻译</span>
                                            <?php elseif($vo["pay_status"] == 2): ?>
                                            <span class="mui-badge mui-badge-danger mui-badge-inverted">确认评价</span>
                                            <?php else: ?>
                                            <span class="mui-badge mui-badge-success mui-badge-inverted">成功</span><?php endif; ?>
                                    </div>
                                </a>
                                <div class="mui-collapse-content">
                                    <p> <a href="<?php echo U('orderDetail',array('id'=>$vo['id']));?>"><span class="mui-badge  mui-badge-warning mui-badge-inverted">查看详情</span></a></p>
                                    <p><a id="delOrder" data="<?php echo ($vo["id"]); ?>"><span class="mui-badge  mui-badge-warning mui-badge-inverted">删除</span></a></p>
                                    <?php if($vo["pay_status"] == 0): ?><p><a id="toPay" data="<?php echo ($vo["id"]); ?>"><span class="mui-badge  mui-badge-warning mui-badge-inverted">前去支付</span></a></p>
                                        <?php else: endif; ?>
                                </div>
                             </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
             </div>
         </div>
    <!-- 底部导航 -->
    <div class="bottom_menu"> 
<a class="current" href="<?php echo U('myOrder');?>">
    <i class="mui-icon mui-icon-contact"></i>
    <span class="title">我的订单</span>
    <span class="count"><?php echo ($count); ?></span>
</a>
<a  href="<?php echo U('term');?>">
    <i class="mui-icon mui-icon-help"></i>
    <span class="title">服务说明</span>
</a>
<a  href="tel://15909581102">
    <i class="mui-icon mui-icon-phone"></i>
    <span class="title">联系客服</span>
</a>
</div>
<p class="copyright"><?php echo ($system_copy_right); echo ($tongji_code); ?></p>

    <script src="/yimei/Public/static/mui.3.5.0/js/mui.min.js?v=<?php echo SITE_VERSION;?>"></script>
    <script>
        document.getElementById("delOrder").addEventListener('tap', function() {
            var btnArray = ['否', '是'];
            alert($(this).attr('data'));
            mui.confirm('你确认删除该订单？', '', btnArray, function(e) {
                if (e.index == 1) {
                   delOrder($(this).attr('data'));
                } else {

                    //info.innerText = 'MUI没有得到你的认可，继续加油'
                }
            })
        });
        function delOrder(id){
            $.Dialog.loading();
            $.ajax({
                type: 'post',
                url:"<?php echo addons_url('HumanTranslation://Wap/delOrder');?>",
                data: {id:id},
                dataType: 'json',
                success: function (result) {
                    if (result==1){
                        $.Dialog.success('删除成功');
                    }else {
                        $.Dialog.fail('删除失败，稍后再试');
                    }

                }
            });
        }
    </script>
</body>
</html>