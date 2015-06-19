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


