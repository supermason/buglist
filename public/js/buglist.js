/* 
 |-------------------------------------------------------------------------- 
 | 所有页面通用JS
 |-------------------------------------------------------------------------- 
 */ 
$(document).ready(function(){
    $.masonUI.fixEleToBottom('.footer');
});

/* 
 |-------------------------------------------------------------------------- 
 | home 页面的查询
 |-------------------------------------------------------------------------- 
 */ 

/**
 * 根据bug名称模糊查询
 * 
 * @returns {json}
 */
function searchBugByName() {
    
    var bugName = $.trim($("#bugTitle").val());
    
    if (bugName === '') {
        $.masonUI.modalInfo("温馨提示", "请输入至少一个关键字", ModalType.WARNING);
    } else {
        
    }
}

/**
 * 根据优先级+解决人查询
 * @returns {void}
 */
function searchBuyByOption()
{
    var solverId = $('#bugSolver').val();
    var status = $('#statusSelect').val();
    
    window.location.href = "/search/" + solverId + "/" + status;
}

/**
 * 在表单提交前将富文本框中的内容保存到一个input内
 * @param {htmlele} form
 * @returns {Boolean}
 */
function fillFormData(form) {
    var jqForm = $(form);
    var formId = jqForm.attr('id');
    var content = '';
    
    if (formId === 'addForm' || formId === 'modifyForm') {
        var input = jqForm.find('input#bugDetail');
        content = $.trim($('#editor').html());
        input.val(content);
    }
    
    if (!isEmptyContent(content)) {
        return true;
    } else {
        $.masonUI.modalInfo('提示', '请填写Bug详细信息！', ModalType.ERROR);
        return false;
    }
}

/**
 * 提交表单
 * @param {html ele} form
 * @returns {bool}
 */
function doSubmit(form) {
    var jqForm = $(form);
    var content = '';
    
    var input = jqForm.find('input#bugSolution');
    content = $.trim($('#editorSolution').html());
    input.val(content);
    
    if (!isEmptyContent(content)) {
        return true;
    } else {
        $.masonUI.modalInfo('提示', '请填写解决方案内容！', ModalType.ERROR);
        return false;
    }
}

/**
 * 判断内容是否为空
 * @param {String} content
 * @returns {Boolean}
 */
function isEmptyContent(content) {
    content = $.trim(content).replace(/&nbsp;/ig,''); //去掉&nbsp;
    return content === '' || content === '<br>' || content === '<br/>';
}
