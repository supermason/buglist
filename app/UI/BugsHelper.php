<?php

namespace App\UI;

/**
 * Description of BugsHelper
 *
 * @author mason.ding
 */
class BugsHelper {
   
    /**
     * 当前页面的路径
     * @var type 
     */
    private static $curURL;
    
    /**
     *是否是修改界面
     * @var type 
     */
    private static $isEditPage;
    
    /**
     *是否是添加界面
     * @var type 
     */
    private static $isAddPage;
    
    /**
     * 是否是查看全部界面
     * @var type 
     */
    private static $isAllPage;
    
    /**
     * 当前登录的用户编号
     * @var type 
     */
    private static $userId;
    
    /**
     * 更新一下当前页面的URL
     */
    public static function updateCurrentURI()
    {
        BugsHelper::$curURL = \Illuminate\Support\Facades\Request::getRequestUri();
        
        BugsHelper::$isEditPage = strpos(BugsHelper::$curURL, 'edit');
        
        BugsHelper::$isAddPage = strpos(BugsHelper::$curURL, 'create');
        
        BugsHelper::$isAllPage = strpos(BugsHelper::$curURL, 'all');
        
        BugsHelper::$userId = \Illuminate\Support\Facades\Auth::user()->id;
    }
    
    /**
     * 是否为可编辑界面
     * @return mixed
     */
    public static function isEditablePage()
    {
        return BugsHelper::$isEditPage || BugsHelper::$isAddPage;
    }

    /**
     * 是否为添加界面
     */
    public static function isAddPage()
    {
        return BugsHelper::$isAddPage;
    }
    
    /**
     * 是否为显示全部界面
     * @return bool
     */
    public static function isAllPage()
    {
        return BugsHelper::$isAllPage;
    }

    /**
     * 根据路由名称获取表头提示文字
     * 
     * @return string
     */
    public static function getPanelHeading()
    {
        if (BugsHelper::$isAddPage)
        {
            return '添加Bug';
        }
        else if (BugsHelper::$isEditPage)
        {
            return '修改Bug信息';
        }
        else // 说明是添加
        {
            return 'Bug信息';
        }
    }
    
    /**
     * 根据不同需求创建表单提交地址
     * @param array $bug
     * @return string
     */
    public static function createActionURL($bug)
    {
        if (BugsHelper::$isAddPage)
        {
            return '/store';
        }
        else if (BugsHelper::$isEditPage)
        {
            return '/update/' . BugsHelper::getBugId($bug);
        }
        else
        {
            return '/';
        }
    }
    
    /**
     * 添加页面，隐藏[提交时间]和[提交人]
     * @return string
     */
    public static function needHideForAdd()
    {
        return BugsHelper::$isAddPage ? "hidden" : "";
    }
    
    /**
     * 查看页面，隐藏提交按钮
     * @return string
     */
    public static function needHideForShow($bug)
    {
//        return !BugsHelper::$isAddPage && !BugsHelper::$isEditPage ? "hidden" : "";
        return \Illuminate\Support\Facades\Auth::user()->id != $bug->presenter_id || $bug->status == \App\Constants\BugStatus::OK ? "hidden" : "";
    }

    /**
     * bug查看页面，如果是提交人或者解决人，可以修改部分内容
     * 
     * @param array $data
     * @return string
     */
    public static function needDisabled($data)
    {
        // 浏览bug的人既不是提交人也不是解决人或者bug已经解决了，就只能看
        if ($data["presenter_id"] != \Illuminate\Support\Facades\Auth::user()->id 
                || ($data['status'] == \App\Constants\BugStatus::OK))
        {
            return "disabled";
        }
        else
        {
            return "";
        }
    }
    
    /**
     * input元素是否可以编辑
     * @param array $bug
     * @return string
     */
    public static function canEdit($bug)
    {
        if (BugsHelper::$isAddPage)
        {
            return "";
        }
        else if (BugsHelper::$isEditPage)
        {
            // 只有提交人可以修改
            return BugsHelper::$userId == $bug['presenter_id'] ? '' : 'disabled';
        }
        else
        {
            return "disabled";
        }
    }

        /**
     * 获取当前bug的编号
     * @param array $data
     * @return int
     */
    public static function getBugId($data)
    {
        if ($data != null)
        {
            return $data['id'];
        }
        else
        {
            return 0;
        }
    }
    
    /**
     * 填充表格
     * @param array $bug
     * @param string $key
     * @return string
     */
    public static function fillForm($bug, $key)
    {
        if ($bug != null)
        {
            return trim($bug[$key]);
        }
        else
        {
            return "";
        }
    }
    
    /**
     * 判断select控件的选中状态
     * @param array $bug
     * @param string $key
     * @param int $val
     * @param int $index
     * @return string
     */
    public static function checkSelection($bug, $key, $val, $index)
    {
        if ($bug == null)
        {
            return $index == 0 ? 'selected="selected"' : '';
        }
        else
        {
            return $bug[$key] == $val ? 'selected="selected"' : '';
        }
    }
    
    /**
     * 根据当前页面返回提交按钮的文本显示
     * @return string
     */
    public static function getSubmitBtnLbl()
    {
        if (BugsHelper::$isAddPage)
        {
            return "提交bug";
        }
        else if (BugsHelper::$isEditPage)
        {
            return '修改bug';
        }
        else
        {
            return '你不应该看见这个按钮';
        }
    }
    
    /**
     * 查找解决人姓名
     * @param int $id
     * @param array $solvers
     * @return string
     */
    public static function getSolverName($id, $solvers)
    {
        if ($id == BugsHelper::$userId)
        {
            return '你';
        }
        else
        {
            foreach ($solvers as $solver)
            {
                if ($id == $solver->id)
                {
                    return $solver->name;
                }
            }
            
            return '【无名氏】';
        }
    }
    
    /**
     * 查看信息时，隐藏编辑菜单
     * @param type $bug
     * @return string
     */
    public static function needHideEditorToolBar($bug)
    {
        return $bug->present_id != \Illuminate\Support\Facades\Auth::user()->id ? "hidden" : "";
    }
    
    /**
     * 
     * @param type $bug
     * @return string
     */
    public static function needHideSolution($bug)
    {
        return ($bug->status != \App\Constants\BugStatus::OK) ? "hidden" : "";
    }
}
