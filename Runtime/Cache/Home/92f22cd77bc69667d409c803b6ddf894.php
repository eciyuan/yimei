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
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">我的评价</h1>
		</header>
		<div class="mui-content">
				<div class="height10px"></div>
			<form class="mui-input-group" id="commentForm" action="<?php echo U('submitCommentOrder');?>" >
			   <ul class="mui-table-view">
			    <li class="mui-table-view-cell">
			        <a class="mui-navigate-right" data="{info.id}" id="goodsInfo">{info.title}</a>
			    </li>
				</ul>
		    	<div class="mui-input-row" >
					<textarea id="textarea" rows="5" placeholder="请输入评论内容"></textarea>
				</div>
		    		<div class="height10px"></div>
			    <div class="">
					<div class="mui-card-header">晒图</div>
					<div class="mui-card-content">
						<div class="mui-card-content-inner">
						    <li class="contentItem">
			                    <div class="upload_img_wrap">
			                        <div class="controls">
			                            <div class="upload_row muti_picture_row">
			                                <?php if(!empty($info['idcardfront'])): echo '<div class="img_item"><em>X</em><input type="hidden" name="image_url" value="'.$info['image_url'].'"/><img src="'.get_cover_url($info['idcardfront']).'"/></div>'; ?>
			                                <?php else: ?>
			                                <a class="img_item add_btn" href="javascript:;" onClick="$.WeiPHP.wxChooseImg(this,1,'image_url',uploadImg);"><img width="100%" src="/yimei/Public/Home/images/add.png"/></a><?php endif; ?>
			                            </div>
			                        </div>
			                    </div>
			                </li>
						</div>
					</div>
				</div>
					<div class="height10px"></div>
					<div class="mui-input-row" id="logistic"> 
						<label>物流评级</label>     
						<span class="mui-input-clear"data-score="3" id="logistic-raty"></span>
					</div>     
						<div class="height10px"></div>
					<div class="mui-input-row" id="description"> 
						<label>描述评级</label>     
						<span class="mui-input-clear"data-score="4" id="description-raty"></span>
					</div>    
						<div class="height10px"></div>
					<div class="mui-input-row" id="server"> 
						<label>服务评级</label>     
						<span class="mui-input-clear"data-score="1" id="server-raty"></span>
					</div>   
						<div class="height10px"></div>
					  <div class="mui-button-row">
				        <button type="button" class="mui-btn mui-btn-primary" id="addComment" >确认</button>
				    </div>
					
				</form>
		</div>
</div>
</body>
<script src="<?php echo CUSTOM_TEMPLATE_PATH;?>Public/mui.min.js?v=<?php echo SITE_VERSION;?>"></script>
<script src="/yimei/Public/static/raty-2.5.2/lib/jquery.raty.min.js"></script>
<script type="text/javascript">
    mui.init();
    mui.ready(function() {
    });
      function uploadImg(localIds,name,target){
        var localId = localIds.pop();
        $.Dialog.loading();
        wx.uploadImage({
            localId: localId, // 需要上传的图片的本地ID，由chooseImage接口获得
            isShowProgressTips: 0, // 默认为1，显示进度提示
            success: function (res) {
                $.get(SITE_URL+"/index.php?s=/Home/Weixin/downloadPic/media_id/"+res.serverId+".html",function(data){
                    $.Dialog.close();
                    if(data.result=="success"){
                        var addImg = $('<div class="img_item"><em>X</em><input type="hidden" id="'+name+'" value="'+data.id+'"/><img src="'+data.picUrl+'"/></div>');
                        addImg.insertBefore($(target));
                        $(target).hide();
                        var uploadImgWidth = $('.muti_picture_row .img_item').width()-10;
                        $('.muti_picture_row .img_item').height(uploadImgWidth).width(uploadImgWidth);
                        $('em',addImg).click(function(){
                            $(this).parent().remove();
                            $(target).show();
                        });
                    }else{
                        $.Dialog.fail('上传图片失败，请通知管理员处理');
                    }
                })
            }
        });
    }
    $(function(){
    	 $.fn.raty.defaults.path = '/yimei/Public/static/raty-2.5.2/lib/img';
	    $('#description-raty').raty({ 
	    	  score: function() { 
	    	    return $(this).attr('data-score'); 
	    	  } 
	    	}); 
	    $('#logistic-raty').raty({ 
	    	  score: function() { 
	    	    return $(this).attr('data-score'); 
	    	  } 
	    	}); 
	    $('#server-raty').raty({ 
	    	  score: function() { 
	    	    return $(this).attr('data-score'); 
	    	  } 
	    	}); 
	    	 $('#description-raty').raty({ 
	    	   click: function(score, evt) {
	    	     descriptionScore=score;
	    	  } 
	    	}); 
	    $('#logistic-raty').raty({ 
	    	 click: function(score, evt) {
	    	    logisticScore=score;
	    	  } 
	    	}); 
	    $('#server-raty').raty({ 
	    	  click: function(score, evt) {
	    	   serverScore=score;
	    	  } 
	    	}); 
    	 $('#addComment').click(function() {
    	 	if(descriptionScore==null){
    	 		descriptionScore='';
    	 	}
    	 	if(logisticScore==null){
    	 		logisticScore='';
    	 	}
    	 	if(serverScore==null){
    	 		serverScore='';
    	 	}
           var image_url = $('#image_url').val();
            var goods_id = $('#goodsInfo').prop("data");
            var desc = $('#desc').val();
            var goods_id = $('#goods_id').val();
               $.Dialog.loading();
               $.post($('#commentForm').attr('action'), {
               		goods_id:goods_id,
                   desc:desc,miao_star:descriptionScore,liu_star:logisticScore,
                   fuwu_star: serverScore
               }, function(data){
                   if(data.status){
                       $.Dialog.success('保存成功');
                       setTimeout(function(){
                           window.location.reload();
                       },2000)
                   }else{
                       $.Dialog.fail('保存失败');
                   }
               },'JSON');
       });
    })
</script>
</html>