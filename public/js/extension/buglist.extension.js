/* 
 * 基于Jquery的一些扩展
 */
//$.ajaxSetup({
//    headers: {
//        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//    }
//});


/* 
 |-------------------------------------------------------------------------- 
 |  业务逻辑扩展
 |-------------------------------------------------------------------------- 
 */ 
$.mason = {
   
   /**
    * 跳转到 url 指定界面
    * @param {String} url
    * @param {String} query 
    * @returns {void}
    */
   gotoPage:function(url, query) {

       // 正则表达式没找到，先用笨办法
       if (url.startWith('/')) {
           url = url.substr(1, url.length);
       }
       
       if (url.endWith('/')) {
           url = url.substr(0, url.length - 1);
       }

       if (arguments.length === 1) {
           window.location.href = "/general/" + url;
       } else {
           
           if (query.endWith('&')) {
               query = query.substr(0, query.length-1);
           }
           
//           alert(query);
           
           window.location.href = "/general/" + url + "/" + encodeURIComponent(encodeURIComponent(query));

//            window.location.href = "/general/" + url + "/" + query;
       }
   }
};

/* 
 |-------------------------------------------------------------------------- 
 | UI界面的扩展
 |-------------------------------------------------------------------------- 
 */ 

var ModalType = {
    INFO: 1,
    WARNING: 2,
    ERROR: 3
};

$.masonUI = {
   
    /**
     * 弹出模态消息对话框
     * @param  {string} title 标题
     * @param  {string} content 内容
     * @param  {int} type 类型
     * @returns {string}
     */
    modalInfo:function(title, content, type) {
        
        var modal = 
                    "<div class=\"modal fade\" id=\"modelInfo\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">"
                    +    "<div class=\"modal-dialog modal-sm\">"
                    +        "<div class=\"modal-content\">"
                    +           "<div class=\"modal-header\"><p>" + title + "</p></div>"
                    +           "<div class=\"modal-body\">" + formatContent(content, type) + "</div>"       
                    +        "</div>"
                    +    "</div>"
                    + "</div>";
        
        // 模态窗体设置
        $("body").append(modal);
        $('#modelInfo').on('hidden.bs.modal', function (e) { this.remove(); });
        $("#modelInfo").modal();
        
        // 获取模态窗体的提示图标
        function formatContent(content, type) {
            var icon = 
                    "<div class=\"alert alert-type\" role=\"alert\">"
                   +    "<p>"
                   +        "<span class=\"glyphicon  glyphicon-type\" aria-hidden=\"true\"></span>&nbsp;&nbsp;"
                   +        content
                   +    "</p>"
                   +"</div>";
           
            switch (type) {
                
                case ModalType.WARNING: // 警告提示
                    icon = icon.replace("alert-type", "alert-warning").replace("glyphicon-type", "glyphicon-exclamation-sign");
                    break;
                case ModalType.ERROR: // 错误提示
                    icon = icon.replace("alert-type", "alert-danger").replace("glyphicon-type", "glyphicon-remove-sign");
                    break;
                default: // 普通信息提示 
                    icon = icon.replace("alert-type", "alert-info").replace("glyphicon-type", "glyphicon-info-sign");
                    break;
            }
            
            return icon;
        }
    },
    
    /**
     * 为指定容器内的text加入回车键的支持
     * 
     * @param {String} selector 选择器
     * @param {function} handler 回调方法
     * @returns {void}
     */
    bindEnterEvtToText:function(selector, handler) {
        // 这里一定注意chidlren和find方法的区别：
        // children方法是寻找直接子类
        // find方法会一直遍历到最下层
        var ele = $(selector);
        if (ele.is('input[type=text]')) {
            bindKeydownEvt(ele);
        } else {
            ele.find('input[type=text]').each(function(){
                bindKeydownEvt($(this));
            });
        }
        
        /**
         * 给text元素绑定键盘按下事件
         * @param {jqueryObj} textEle
         * @returns {void}
         */
        function bindKeydownEvt(textEle) {
            textEle.keydown(function(event){
                if (event.keyCode === 13) {
                    if (handler !== null) {
                        handler(this);
                    }
                }
            });
        }
    },
    
    /**
     * 为某个元素寻找指定父容器，如果找不到返回null
     * @param {jqueryObj} jqEle
     * @param {String} matchedParent
     * @returns {jqueryObj}
     */
    lookupMacthedParent:function(jqEle, matchedParent) {
        
        var parent = jqEle.parent();
        if (parent.is(matchedParent)) {
            return parent;
        } else if (parent.is('body')) { // 如果找到了body这一层，还没有那么直接返回null，跳出递归
            return null;
        } else {
            return $.masonUI.lookupMacthedParent(parent, matchedParent);
        }
    },
    
    /**
     * 设置一个元素始终位于页面底部
     * @param {string} selector
     * @returns {void}
     */
    fixEleToBottom:function(selector) {
        
        var jqEle = $(selector);
        
        if (jqEle.length > 0) {
            var parentHeight = jqEle.parent().height(); 
            var documentHeight = $(window).height();
            
//            alert(parentHeight + " | " + documentHeight);
            if (parentHeight < documentHeight) {
                var mt = documentHeight - parentHeight - jqEle.height();
//                alert("mt: " + mt);
                jqEle.css("margin-top", mt + "px"); 
            }
        }
    }
    
};

