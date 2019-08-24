<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<meta charset="utf-8">
<meta http-equiv="pragma" content="no-cache"> 
<meta http-equiv="Cache-Control" content="no-store, must-revalidate"> 
<meta name="Keywords" content="MilFun,米饭网,微信公众号,milfun.fun" />
<meta name="description" content="米饭网，成为最便捷的资讯交流平台。帮助您快速找到自己的未来，让技术变得更简单、更安全，让您在万众创业的浪潮中快速实现突破，抓住成功的机会。
"/>
<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
    <title>人脸比对</title>
    <link rel="stylesheet" type="text/css" href="//res.wx.qq.com/open/libs/weui/1.1.3/weui.min.css" />
    <script src="https://res.wx.qq.com/open/libs/weuijs/1.1.1/weui.min.js"></script>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js" type="text/javascript"></script>
</head>
<body>
    <h3 style="text-align: center;">MilFun AI Face Mix!</h3>
<div class="weui-cells__title"  style="text-align: center;">人脸上传</div>
<div class="weui-cells weui-cells_form" id="uploader">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <div class="weui-uploader">
                <div class="weui-uploader__hd">
                    <p class="weui-uploader__title">图片列表</p>
                    <div class="weui-uploader__info">
                        <span id="uploadCount">0</span>/2
                    </div>
                </div>
                <div class="weui-uploader__bd">
                    <ul class="weui-uploader__files" id="uploaderFiles"></ul>
                    <div class="weui-uploader__input-box">
                        <input id="uploaderInput" class="weui-uploader__input" type="file"
                               accept="image/*" multiple="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="button-sp-area">
    <a href="javascript:;" class="weui-btn weui-btn_plain-primary">MilFun Doing...</a>
</div>

<img src="" style="width: 100%;height: auto;">
</body>
<script type="text/javascript">
    /* *
 * 图片上传
 * */
var uploadCount = 0,  //上传图片的数量
    uploadList = [],  //上传的图片
    uploadSuccessCount = 0;  //上传成功的数量
var uploadCountDom = document.getElementById("uploadCount");
weui.uploader('#uploader', {
    url: 'http://localhost/Camer/index.php/Home/Index/upload.html',  //你要上传的url地址
    auto: true,
    type: 'file',
    fileVal: 'fileVal',  //文件上传域的name，后台通过该name拿到传输的文件
    compress: {
        width: 1600,
        height: 1600,
        quality: .8
    },
    onBeforeQueued: function onBeforeQueued(files) {
        //上传前，对上传的情况做以下多个判断，保证合法性，可自行删改
        if (["image/jpg", "image/jpeg", "image/png", "image/gif"].indexOf(this.type) < 0) {
            weui.alert('请上传图片');
            return false;
        }
        if (this.size > 5 * 1024 * 1024) {
            weui.alert('请上传不超过5M的图片');
            return false;
        }
        if (files.length > 2) {
            //防止一下子选中过多文件
            weui.alert('最多只能上传2张图片，请重新选择');
            return false;
        }
        if (uploadCount + 1 > 2) {
            weui.alert('最多只能上传2张图片');
            return false;
        }

        ++uploadCount;
        uploadCountDom.innerHTML = uploadCount;
    },
    onQueued: function onQueued() {
        uploadList.push(this);
        //手动上传，如果不想选择完图片自动上传可以通过此方法改为手动不过上面的auto要改为false
        /*var self = this;
        $('#preview_confirm').on('click',function(){
            self.upload();
        });*/
    },
    onBeforeSend: function onBeforeSend(data, headers) {
        $("#submit_order").addClass("weui-btn_disabled");
        //return false; //阻止文件上传
    },
    onProgress: function onProgress(procent) {
        //console.log(this, procent);
    },
    onSuccess: function onSuccess(ret) {
        if (ret.result == true) {
            uploadSuccessCount++;
            if (uploadSuccessCount == uploadCount) {
                //判断上传是否全部成功
                $("#submit_order").removeClass("weui-btn_disabled");
            }
        }
        var uploadID = this.id;
        $("#uploaderFiles li").each(function () {
            if ($(this).attr("data-id") == uploadID) {
                $(this).attr("DB-id", ret.DBId);  //图片后台对应的唯一编号
                $(this).attr("url", ret.url);  //图片存放地址
            }
        });
        //console.log(this, ret);
    },
    onError: function onError(err) {
        console.log(this, err);
    }
});

/* *
 * 缩略图预览
 * */
document.querySelector('#uploaderFiles').addEventListener('click', function (e) {
    var target = e.target;

    while (!target.classList.contains('weui-uploader__file') && target) {
        target = target.parentNode;
    }
    if (!target) return;

    //从图片对应的li标签中获得所需属性
    var url = target.getAttribute('url');  //图片存放地址
    var DBId = target.getAttribute('db-id');  //图片唯一编号
    var id = target.getAttribute('data-id');  //点击图片对应的id

    var gallery = weui.gallery(url, {
        className: 'custom-name',
        onDelete: function () {
            //删除图片的回调
            var isDelete = confirm('确定删除该图片？');
            if (isDelete) {
                --uploadCount;
                uploadCountDom.innerHTML = uploadCount;  //处理角标
                for (var i = 0, len = uploadList.length; i < len; ++i) {
                    var file = uploadList[i];
                    if (file.id == id) {
                        $("#uploaderFiles li").each(function () {
                            //找到对应的li标签，请求后台删除文件
                            if ($(this).attr("data-id") == id) {
                                var param = {};
                                param.DBId = DBId;
                                param.imgUrl = url;
                                $.ajax({
                                    url: "img-api/delete_imgs",
                                    type: "delete",
                                    contentType: "application/json;charset=UTF-8",
                                    dataType: "json",
                                    data: JSON.stringify(param),
                                    success: function (msg) {
                                        console.log(msg);
                                    },
                                    error: function (xhr, textstatus, thrown) {

                                    }
                                });
                            }
                        });
                        file.stop();
                        break;
                    }
                }
                target.remove();
                gallery.hide();
            }
        }
    });
});

</script>
</html>