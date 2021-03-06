<?php if (!defined('THINK_PATH')) exit();?><meta charset="UTF-8">
<meta content="<?php echo C('WEB_SITE_KEYWORD');?>" name="keywords"/>
<meta content="<?php echo C('WEB_SITE_DESCRIPTION');?>" name="description"/>
<link rel="shortcut icon" href="<?php echo SITE_URL;?>/favicon.ico">
<title><?php echo empty($page_title) ? C('WEB_SITE_TITLE') : $page_title; ?></title>
<link href="/yimei/Public/static/font-awesome/css/font-awesome.min.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/yimei/Public/Home/css/base.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/yimei/Public/Home/css/module.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/yimei/Public/Home/css/weiphp.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/yimei/Public/static/emoji.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">

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
<script type="text/javascript" src="/yimei/Public/Home/js/admin_common.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript" src="/yimei/Public/Home/js/admin_image.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript" src="/yimei/Public/static/masonry/masonry.pkgd.min.js"></script>
<script type="text/javascript" src="/yimei/Public/static/jquery.dragsort-0.5.2.min.js"></script> 
<script type="text/javascript">
var  IMG_PATH = "/yimei/Public/Home/images";
var  STATIC = "/yimei/Public/static";
var  ROOT = "/yimei";
var  UPLOAD_PICTURE = "<?php echo U('home/File/uploadPicture',array('session_id'=>session_id()));?>";
var  UPLOAD_FILE = "<?php echo U('File/upload',array('session_id'=>session_id()));?>";
var  UPLOAD_DIALOG_URL = "<?php echo U('home/File/uploadDialog',array('session_id'=>session_id()));?>";
</script>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>

<body style="background:#fff; padding:40px 0;">
  <section id="uploadDialogContent">
  	<div class="upload_img_tab">
    	<a class="current" href="javascript:;" onClick="switchTab(this,1);">本地上传</a>
        <a href="javascript:;" onClick="switchTab(this,2);">系统图标</a>
        <a href="javascript:;" onClick="switchTab(this,3);">最近上传</a>
    </div>
    <div class="tab_content" id="tab1" style="padding:20px; display:block">
      <form id="form"  method="post" class="form-horizontal form-center">
        <div class="mult_imgs">
                <div class="upload-img-view" id='mutl_picture'>
                   
                </div>
                <div class="controls uploadrow2" data-max="9" title="点击上传图片">
                  <input type="file" id="upload_picture">
                </div>
            </div>    
      </form>
	</div>
    <div class="tab_content load_piclist_box" id="tab2" style="padding:20px;"></div>
	<div class="tab_content load_piclist_box" id="tab3" style="padding:20px;"></div>
  </section>
  <div class="upload_dialog_bar"><a id="conBtn" class="btn" href="javascript:;" onClick="confirmImage();">确定</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="border-btn" href="javascript:;" onClick="window.parent.$.Dialog.close();">取消</a></div>
</body>
<script type="text/javascript">
var maxCount = parseInt("<?php echo ($_GET['max']); ?>");
var fieldName =  "<?php echo ($_GET['field']); ?>";
$('#upload_picture').uploadify({
	"height"          : 100,
	"width"           : 100,
	"swf"             : STATIC+"/uploadify/uploadify.swf",
	"fileObjName"     : "download",
	"buttonText"      : "上传图片",
	"uploader"        : UPLOAD_PICTURE,
	'cancelImg'		  : 'JS/jquery.uploadify-v2.1.0/cancel.png',
	'removeTimeout'	  : 1,
	'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
	"onUploadSuccess" : function(file, data, response) {
		var data = $.parseJSON(data);
		if(data.status){
			src = data.url || ROOT + data.path;
			$addImg = $('<div class="upload-pre-item22 check" onclick="toggleCheck(this);"><img width="100" height="100" src="' + src + '"/>'
				+'<input type="hidden" name="picId[]" value="'+data.id+'"/><!--<em onClick="if(confirm(\'确认删除？\')){$(this).parent().remove();}">&nbsp;</em>--><span class="ck-ico"></span></div>');
			$("#mutl_picture").append($addImg);
			if(maxCount==1){
				$("#mutl_picture .upload-pre-item22").each(function(index, element) {
					$(element).removeClass('check');
				});
				$addImg.addClass('check');
			}
		} else {
			window.parent.updateAlert(data.info);
			setTimeout(function(){
				window.parent.$('#top-alert').find('button').click();
			},1500);
		}
	},
	"onUploadError" : function(file, data, response) {
		window.parent.updateAlert(data.info);
	}
});
function switchTab(obj,index){
	$('#tab'+index).show().siblings('.tab_content').hide();
	$(obj).addClass('current').siblings().removeClass('current');
	if(index!=1 && !$(obj).hasClass('loaded')){
		if(index==2){
			//加载系统
			$(obj).addClass('loaded');
			$('#tab2').load("<?php echo U('/Home/File/systemPics',array('dir'=>$dir));?>");
		}else if(index==3){
			//加载最近
			$(obj).addClass('loaded');
			$('#tab3').load("<?php echo U('/Home/File/userPics');?>");
		}
	}
}
//切换图标分类
function switchPicCate(obj,tabIndex){
	$('#tab'+tabIndex).empty();
	$('#tab'+tabIndex).load($(obj).data('href'));
}
//选中图片
function toggleCheck(obj){
	var curItems = $('.tab_content:visible .check');
	var checkCount = curItems.size();
	if(maxCount>1){
		if(checkCount==maxCount && !$(obj).hasClass('check')){
			window.parent.updateAlert('图片不能超过'+maxCount+'张!');
			return;
		}
		$(obj).toggleClass('check');
	}else{
		if(!$(obj).hasClass('check')){
			$(obj).addClass('check').siblings().removeClass('check');
		}
	}
}
function confirmImage(){
	var curItems = $('.tab_content:visible .check');
	var checkCount = curItems.size();
	if(checkCount==0){
		window.parent.updateAlert('请选择图片!');
		return;
	}
	curItems.each(function(index, element) {
		var picId = $(element).find('input[type="hidden"]').val();
		var src = $(element).find('img').attr('src');
        if(maxCount>1){
			$addImg = $('<div class="upload-pre-item22"><img width="100" height="100" src="' + src + '"/>'
				+'<input type="hidden" name="'+fieldName+'[]" value="'+picId+'"/><em onClick="if(confirm(\'确认删除？\')){$(this).parent().remove();}">&nbsp;</em></div>');
			window.parent.$("#mutl_picture_"+fieldName).append($addImg);
			
			
			window.parent.$("#mutl_picture_"+fieldName).dragsort('destroy');
			window.parent.$("#mutl_picture_"+fieldName).dragsort({
			    itemSelector: ".upload-pre-item22", dragSelector: ".upload-pre-item22", dragBetween: false, placeHolderTemplate: "<div class='upload-pre-item22'></div>",dragSelectorExclude:'em',dragEnd: function() {$(".upload-pre-item22").attr('style','')}
		    });
		}else{	
			window.parent.$('#cover_id_'+fieldName).parent().find('.upload-img-box').html(
				'<div class="upload-pre-item2" style="width:100%;height:100px;max-height:100px"><img width="100%" height="100" src="' + src + '"/></div><em class="edit_img_icon">&nbsp;</em>'
			).show();
			window.parent.$('#cover_id_'+fieldName).val(picId);
			window.parent.$('.weixin-cover-pic').attr('src',src);
			var callback = window.parent.$('#cover_id_'+fieldName).data('callback');
			
			if(callback){
				eval('window.parent.'+callback+'("'+fieldName+'",'+picId+',"'+src+'")');
			}
		}
	});
	window.parent.$.Dialog.close();
}
</script>
</html>