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
<link href="<?php echo ADDON_PUBLIC_PATH;?>/mobile/common.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
<link href="<?php echo CUSTOM_TEMPLATE_PATH;?>Public/mui.min.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
<link href="<?php echo CUSTOM_TEMPLATE_PATH;?>Public/shop.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
<body class="withFoot">
<div class="container">
    <!-- banner -->
    <div id="banner" class="banner">
        <ul>
            <?php if(is_array($slideshow_list)): $i = 0; $__LIST__ = $slideshow_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <a href="<?php echo ($vo["url"]); ?>"><img src="<?php echo (get_cover_url($vo["img"])); ?>"/></a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <span class="identify">
            <?php if(is_array($slideshow_list)): $i = 0; $__LIST__ = $slideshow_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><em></em><?php endforeach; endif; else: echo "" ;endif; ?>
        </span>
    </div>
    <!-- 搜索 -->
    <form class="search_form" action="<?php echo U('lists',array('shop_id'=>$shop_id));?>" method="get">
        <a href="javascript:void(0);" class="cate_icon" onClick="showPopCategory()">&nbsp;</a>
        <input type="text" placeholder="输入关键字搜索商品" value="<?php echo I('search_key');?>" id="search_key" name="search_key"/>
        <button type="button" id="search" url="<?php echo U('lists',array('shop_id'=>$shop_id));?>">搜索</button>
    </form>
    <!-- 推荐类目 -->
    <?php if(!empty($recommend_cate)): ?><div class="recommend_cate">
            <div id="Gallery" class="mui-slider">
                <div class="mui-slider-group">
                    <div class="mui-slider-item">
                        <ul class="mui-table-view mui-grid-view mui-grid-9">
                            <?php if(is_array($recommend_cate)): $k = 0; $__LIST__ = $recommend_cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($k % 2 );++$k;?><li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                                <a href="<?php echo U('goodsListsByCategory',array('shop_id'=>$shop_id,'cid0'=>$cate[id],'token'=>$token));?>">
                                    <img src="<?php echo (get_cover_url($cate["icon"])); ?>"/>
                                    <div class="mui-media-body"><?php echo ($cate["title"]); ?></div>
                                </a>
                            </li>
                            <?php if(($k%8) == "0"): ?></ul>
                            </div>
                            <div class="mui-slider-item">
                                <ul class="mui-table-view mui-grid-view mui-grid-9"><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="mui-slider-indicator">
                    <div class="mui-indicator mui-active"></div>
                    <?php if(is_array($recommend_cate)): $k = 0; $__LIST__ = $recommend_cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($k % 2 );++$k; if(($k%8) == "0"): ?><div class="mui-indicator"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div><?php endif; ?>
    <!-- 推荐商品 -->
    <?php if(!empty($recommend_list)): ?><div class="recommend_list">
            <h6 class="cate_title">商城推荐</h6>
            <div class="product_list">
                <ul>
                    <?php if(is_array($recommend_list)): $i = 0; $__LIST__ = $recommend_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a href="<?php echo U('detail',array('shop_id'=>$shop_id,'id'=>$vo['id'],'token'=>$token));?>">
                                <img src="<?php echo (get_cover_url($vo["cover"])); ?>"/>
                                <div class="desc">
                                    <p class="name"><?php echo ($vo["title"]); ?></p>
                                    <p class="price">￥<?php echo (wp_money_format($vo["price"])); ?></p>
                                </div>
                            </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div><?php endif; ?>
    <!-- 推荐商品 -->
    <div class="all_list">
        <h6 class="cate_title">所有商品</h6>
        <div class="product_list">
            <ul>
                <?php if(is_array($goods_list)): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                        <a href="<?php echo U('detail',array('shop_id'=>$shop_id,'id'=>$vo['id'],'token'=>$token));?>">
                            <img src="<?php echo (get_cover_url($vo["cover"])); ?>"/>
                            <div class="desc">
                                <p class="name"><?php echo ($vo["title"]); ?></p>
                                <p class="price">￥<?php echo (wp_money_format($vo["price"])); ?></p>
                            </div>
                        </a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <a class="list_bottom_btn" href="<?php echo U('lists',array('shop_id'=>$shop_id,'token'=>$token));?>">查看所有商品&gt;</a>
    </div>
</div>
<!-- 分类目录 -->
<section class="pop_category" style="display:none">
  <div class="pop_category_head"> <a href="javascript:;" onClick="hidePopCategory()">取消</a> </div>
  <ul>
    <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('goodsListsByCategory',array('shop_id'=>$shop_id,'cid0'=>$cate[id]));?>"><?php echo ($cate["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
</section>
<script type="text/javascript">
function showPopCategory(){
	$('body').addClass('noscroll');
	$('.pop_category').addClass('show_category').show();
}
function hidePopCategory(){
	$('body').removeClass('noscroll');
	$('.pop_category').removeClass('show_category').hide();	
}
</script> 
<!-- 底部导航 -->
<div class="bottom_menu"> 
<a class="home" href="<?php echo U('index', array('shop_id'=>$shop_id));?>">首页</a> 
<a class="cart" href="<?php echo U('cart', array('shop_id'=>$shop_id));?>">购物车<span class="count"><?php echo ($cart_count); ?></span></a> 
<a class="center" href="<?php echo U('user_center', array('shop_id'=>$shop_id));?>">我</a>
</div>
<p class="copyright"><?php echo ($system_copy_right); echo ($tongji_code); ?></p>

<script src="<?php echo CUSTOM_TEMPLATE_PATH;?>Public/mui.min.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript">
    mui.init();
    mui.ready(function() {
        var slider = document.getElementById('Gallery');
        var group = slider.querySelector('.mui-slider-group');
        var items = mui('.mui-slider-item', group);
        //克隆第一个节点
        var first = items[0].cloneNode(true);
        first.classList.add('mui-slider-item-duplicate');
        //克隆最后一个节点
        var last = items[items.length - 1].cloneNode(true);
        last.classList.add('mui-slider-item-duplicate');
        //处理是否循环逻辑，若支持循环，需支持两点：
        //1、在.mui-slider-group节点上增加.mui-slider-loop类
        //2、重复增加2个循环节点，图片顺序变为：N、1、2...N、1
        var sliderApi = mui(slider).slider();
    });
    $(function(){
        //通用banner 滑动
        $.WeiPHP.initBanner('#banner', true, 5000);

        //搜索功能
        $("#search").click(function(){
            var url = $(this).attr('url');
            var query  = $('.search_form').serialize();
            query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
            query = query.replace(/^&/g,'');
            if( url.indexOf('?')>0 ){
                url += '&' + query;
            }else{
                url += '?' + query;
            }
            window.location.href = url;
        });
        /* $("#search_key").keydown(function (e)   
        }); */
    })
</script>
</body>
</html>