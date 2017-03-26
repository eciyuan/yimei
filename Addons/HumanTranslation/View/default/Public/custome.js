/**
 * Created by mxc on 2017/3/2.
 */
// 获取url中的参数
function getUrlParam (name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!= null) {
        return unescape(r[2]);
    }else{
        return null;
    }
}

