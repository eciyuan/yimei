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
<script type="text/javascript" src="/yimei/Public/static/qrcode/qrcode.js"></script>
<script type="text/javascript" src="/yimei/Public/static/qrcode/jquery.qrcode.js"></script>
<link href="<?php echo ADDON_PUBLIC_PATH;?>/css.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
<body>
	<div id="container" class="container body">
    	<div class="block_content_bg m_10">
            <div class="block_content_top_min custom-theme-blue">
                <center>请填写以下信息</center>
            </div>
        <!-- 表单 -->
        <form id="form" action="<?php echo addons_url('HumanTranslation://Wap/add');?>" method="post" class="form-horizontal p_10">
                  <input type="hidden" class="text input-large" name="<?php echo ($field["name"]); ?>" value="<?php echo I($field[name], $field[value]);?>">
                  <div class="form-item cf">
                    <div class="controls">
                        <!--多个数组选择-->
                        <label class="item-label">选择擅长语言<span class="check-tips"></span></label>
                        <select name="select_language_id" id="select_language_id">
                            <option value="" selected >请选择语言</option>
                            <?php if(is_array($select_language_id)): $i = 0; $__LIST__ = $select_language_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>

                        <!--输入框-->
                        <label class="item-label">姓名<span class="check-tips">要与上传的证件等证明文件一致</span></label>
                          <input type="text" class="text input-medium" name="name" value="">

                        <label class="item-label">手机<span class="check-tips">方便联系</span></label>
                        <input type="text" class="text input-medium" name="mobile" value="">

                        <label class="item-label">毕业学校<span class="check-tips"></span></label>
                        <input type="text" class="text input-medium" name="graduate" value="">

                        <label class="item-label">专业<span class="check-tips"></span></label>
                        <input type="text" class="text input-medium" name="major" value="">

                        <label class="item-label">外语等级<span class="check-tips"></span></label>
                        <input type="text" class="text input-medium" name="language_level" value="">

                        <label class="item-label">从事翻译时间<span class="check-tips">一年以下 三年以上五年以下 五年以上等</span></label>
                        <input type="text" class="text input-medium" name="engaged_time" value="">

                        <div id="text-translation">
                            <label class="item-label">基本介绍<span class="check-tips">选填</span></label>
                            <label class="textarea">
                                <textarea id="textarea-content" name="intro"></textarea>
                            </label>
                        </div>
                        <div id="img-translation" class="hidden">
                            <label class="item-label">相关证明文件<span class="check-tips">身份证正面，等级证书，奖励证书等</span></label>
                            <div class="controls">
                                <div class="upload_row muti_picture_row">
                                    <?php if(!empty($data[$field['name']])): $tempArr =$data['img_url']; for($i=0;$i<count($tempArr);$i++){ if(!empty($data['id']) && $forms['can_edit']!=1){ echo '<div class="img_item"><input type="hidden" name="img_url[]" value="'.$tempArr[$i].'"/><img src="'.get_cover_url($tempArr[$i]).'"/></div>'; }else{ echo '<div class="img_item"><em>X</em><input type="hidden" name="img_url[]" value="'.$tempArr[$i].'"/><img src="'.get_cover_url($tempArr[$i]).'"/></div>'; } } endif; ?>
                                    <?php if(empty($data[id]) || $forms[can_edit]==1): ?><a class="img_item add_btn" href="javascript:;" style="width: 84px;height: 84px;" onClick="$.WeiPHP.wxChooseImg(this,1,'img_url[]')"><img  src="/yimei/Public/Home/images/add.png"/></a><?php endif; ?>
                                </div>
                            </div>
                        <!--文本编辑器-->
                       <!--   <label class="textarea input-large">
                            <textarea name="<?php echo ($field["name"]); ?>"><?php echo ($data[$field['name']]); ?></textarea>
                          </label>-->
                        <!--时间-->
                          <!--<input type="text" name="<?php echo ($field["name"]); ?>" class="text input-large time" value="<?php echo (time_format($data[$field['name']])); ?>" placeholder="请选择时间" />-->
                       <!-- <div id="cascade_<?php echo ($field["name"]); ?>"></div>
                             <?php echo hook('cascade', array('name'=>$field['name'],'value'=>$data[$field['name']],'extra'=>$field['extra']));?>
                        <div id="dynamic_select_<?php echo ($field["name"]); ?>"></div>
                        <?php echo hook('dynamic_select', array('name'=>$field['name'],'value'=>$data[$field['name']],'extra'=>$field['extra']));?>-->
<!--多选框-->
                        <!--  <?php $_result=parse_field_attr($field['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="checkbox"> <input type="checkbox" value="<?php echo ($key); ?>" name="<?php echo ($field["name"]); ?>[]" 
                              <?php if(in_array(($key), is_array($data[$field['name']])?$data[$field['name']]:explode(',',$data[$field['name']]))): ?>checked="checked"<?php endif; ?>
                              ><?php echo ($vo); ?> </label><?php endforeach; endif; else: echo "" ;endif; ?>-->
                        <!--文本编辑器-->

                        <!--上传文件-->
                          <!--<div class="controls">
                            <input type="file" id="upload_file_<?php echo ($field["name"]); ?>">
                            <input type="hidden" name="<?php echo ($field["name"]); ?>" value="<?php echo ($data[$field['name']]); ?>"/>
                            <div class="upload-img-box">
                              <?php if(isset($data[$field['name']])): ?><div class="upload-pre-file"><span class="upload_icon_all"></span><?php echo (get_table_field($data[$field['name']],'id','name','File')); ?></div><?php endif; ?>
                            </div>
                          </div>-->
                          <script type="text/javascript">
								//上传图片
							    /* 初始化上传插件 */
								$("#upload_file_<?php echo ($field["name"]); ?>").uploadify({
							        "height"          : 30,
							        "swf"             : "/yimei/Public/static/uploadify/uploadify.swf",
							        "fileObjName"     : "download",
							        "buttonText"      : "上传附件",
							        "uploader"        : "<?php echo U('File/upload',array('session_id'=>session_id()));?>",
							        "width"           : 120,
							        'removeTimeout'	  : 1,
							        "onUploadSuccess" : uploadFile<?php echo ($field["name"]); ?>
							    });
								function uploadFile<?php echo ($field["name"]); ?>(file, data){
									var data = $.parseJSON(data);
							        if(data.status){
							        	var name = "<?php echo ($field["name"]); ?>";
							        	$("input[name="+name+"]").val(data.id);
							        	$("input[name="+name+"]").parent().find('.upload-img-box').html(
							        		"<div class=\"upload-pre-file\"><span class=\"upload_icon_all\"></span>" + data.name + "</div>"
							        	);
							        } else {
							        	updateAlert(data.info);
							        	setTimeout(function(){
							                $('#top-alert').find('button').click();
							                $(that).removeClass('disabled').prop('disabled',false);
							            },1500);
							        }
							    }
								</script>
                    </div>
                  </div>
                    <div id="top-alert" class="fixed alert alert-error" style="display: none;">
                    <button class="close fixed" style="margin-top: 4px;">&times;</button>
                      <div class="alert-content"></div>
                      </div>
          	<div class="form-item cf">
                <button class="home_btn submit-btn mb_10 mt_10 custom-theme-blue" id="submit" type="button" target-form="form-horizontal">翻译提交并支付</button>
                <button class="home_btn submit-btn mb_10 mt_10 custom-theme-blue hidden" id="submitImg" type="button" target-form="form-horizontal">翻译提交</button>
            </div>
        </form>

      </div>
       <p class="copyright"><?php echo ($system_copy_right); ?></p>
  </div>
  <!-- Wap页面脚部 -->
<div style="height:0; visibility:hidden; overflow:hidden;">
<?php echo ($tongji_code); ?>
</div>
<script type="text/javascript">
	$.WeiPHP.initWxShare({
		title:"<?php echo ($forms["title"]); ?>",
		imgUrl:"<?php echo ($forms["cover"]); ?>",
		desc:"<?php echo ($forms["intro"]); ?>",
		link:window.location.href
	})
</script>
 <block name="script">
 <link href="<?php echo SITE_URL;?>/Public/static/datetimepicker/css/datetimepicker.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
  <?php if(C('COLOR_STYLE')=='blue_color') { ?>
    <link href="<?php echo SITE_URL;?>/Public/static/datetimepicker/css/datetimepicker_blue.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
  <?php } ?>
  <link href="<?php echo SITE_URL;?>/Public/static/datetimepicker/css/dropdown.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="<?php echo SITE_URL;?>/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
  <script type="text/javascript" src="<?php echo SITE_URL;?>/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js?v=<?php echo SITE_VERSION;?>" charset="UTF-8"></script>
<script type="text/javascript">
    function check_subscribe(){
        //判断是否关注公众号
        var has_subscribe = "<?php echo ($has_subscribe); ?>";
        //var has_subscribe = 1;
        if(has_subscribe=="0"){
            $.WeiPHP.showSubscribeTipsHandle({'title':'{$public_info.public_name}','qrcode': '<?php echo ($qrcode); ?>'});
            return false;
        }
    }

$('#submit').click(function(){
   // $('#form').submit();
    check_subscribe();
   $.Dialog.loading();
   $.ajax({
// 	   url:'<?php echo U('add?model='.$model['id']);?>',
	   url:"<?php echo addons_url('HumanTranslation://Wap/index');?>",
	   type:'post',
	   data:$('#form').serializeArray(),
	   dataType:'json',
	   success:function(json){
           console.log(json);
		    //$.Dialog.close();
			if(json.status==1){
			   		$.Dialog.success(json.info);
					//alert('2');
			   }else{
				   	$.Dialog.fail(json.info);
					//alert('3');
			}
		   if(json.url!=""){
			   setTimeout(function(){
				   window.location.href=json.url;
				   },2000);
			   }
   		},
		error:function(){
				$.Dialog.close();
			 //$.Dialog.fail();
			}
	   });
});
$(function(){
    $('.time').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:0,
        autoclose:true
    });
    $("#translation_type_img").click(function(){
        $("#img-translation").fadeIn(); //.css("visibility","hidden");
        $("#text-translation").fadeOut();
        $("#submitImg").fadeIn();//显示按钮
        $("#submit").fadeOut();
    });
    $("#translation_type_text").click(function(){
        $("#text-translation").fadeIn();
        $("#img-translation").fadeOut();
        $("#submitImg").fadeOut();//显示按钮
        $("#submit").fadeIn();
    });
    $("#goal_language_id").bind("change keyup", function () {
        var goal_language_val = $(this).prop("value");
        loadSingelPrice(goal_language_val);
    });
    $('#textarea-content').bind('keypress mouseleave', function(){
        if($("#goal_language_id").val()==null||$("#goal_language_id").val()==''||$("#goal_language_id").val()==undefined){
            $.Dialog.fail('请选择翻译语言！');
            return false;
        }
        var _length = $(this).val().length;
        var singel_price=$("#singel-price").val();
        $("#total_price").val(_length*singel_price);
    });
    //根据语言取出该类下的单价
    function loadSingelPrice(goal_language_val){
        $.ajax({
            type: 'post',
            url:"<?php echo addons_url('HumanTranslation://Wap/getSingelPrice');?>",
            data: {id:goal_language_val},
            dataType: 'json',
            success: function (result) {
                //console.log(result.singel_price);
                $("#singel-price").val(result.singel_price);
            }
        });
    }
});
</script> 
</body>
</html>