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
        <div class="mui-card" >
            <div class="mui-card-header">
                订单状态 <span class="mui-badge mui-badge-danger mui-badge-inverted">待支付</span>
            </div>
            <div class="mui-card-content">
                <ul class="mui-table-view">
                    <li class="mui-table-view-cell">订单编号 <span class="mui-badge mui-badge-primary mui-badge-inverted"><?php echo ($detail["order_number"]); ?></span></li>
                    <li class="mui-table-view-cell">翻译类型
                        <span class="mui-badge mui-badge-primary mui-badge-inverted">
                            <?php if($vo["translation_type"] == 0): ?>文本
                                <?php else: ?>
                                图片<?php endif; ?>
                    </span>
                    </li>
                    <li class="mui-table-view-cell">字数统计
                        <span class="mui-badge mui-badge-primary mui-badge-inverted"><?php echo ($detail["count_str"]); ?></span></li>
                    <li class="mui-table-view-cell">翻译语言
                        <span class="mui-badge mui-badge-warning mui-badge-inverted">
                            <?php echo ($detail["select_language_id"]); ?>
                            <i class="mui-icon mui-icon-arrowthinright"></i>
                              <?php echo ($detail["goal_language_id"]); ?>
                        </span>
                    </li>
                    <li class="mui-table-view-cell">价格统计<span class="mui-badge mui-badge-primary mui-badge-inverted"><?php echo ($detail["total_price"]); ?></span></li>
                    <li class="mui-table-view-cell">下单时间<span class="mui-badge mui-badge-primary mui-badge-inverted"><?php echo (date("Y-m-d",$detail["cTime "])); ?></span></li>
                    <li class="mui-table-view-cell">
                        <?php if($vo["translation_type"] == 0): ?>原文
                            <div class="mui-card-content">
                                <?php echo ($detail["content"]); ?>
                            </div>
                            <?php else: ?>
                            <div class="mui-card-content">
                                <img class="mui-media-object" src="../images/shuijiao.jpg">
                            </div><?php endif; ?>
                    </li>
                    <li class="mui-table-view-cell">
                        译文
                            <div class="mui-card-content">
                                <?php echo ($detail["after_content"]); ?>
                            </div>
                    </li>
                </ul>
            </div>
            <div class="mui-card-footer"></div>
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
</body>
</html>