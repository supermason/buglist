<?php

namespace App\UI;

use App\Constants\BugStatus;

/**
 * Description of GeneralBeautifier
 *
 * @author mason.ding
 */
class GeneralBeautifier {
    
    /**
     * 截短内容
     * @param string $content
     * @param int $displayLength
     * @return string 
     */
    public static function truncateContent($content, $displayLength)
    {
        $contentLength = strlen($content);
        
        if ($contentLength <= $displayLength)
        {
            return $content;
        }
        else
        {
            return substr($content, 0, $displayLength);
        }
    }
    
    /**
     * 修饰优先级文字
     * @param int $priority
     */
    public static function decoratePriority($priority)
    {
        return '<span class="' . ($priority == 0 ? 'normal' : 'emergent') . '">[' . ($priority == 0 ? '一般' : '紧急') . ']</span>';
    }
    
    /**
     * 对应状态码到文字描述
     * @param int $status
     */
    public static function mapStatusToString($status)
    {
        // Pending，Standby，OK
        switch ($status)
        {
            case 1:
                return "Standby";
            case 2:
                return "OK";
            case 0:
            default:
                return "Pending";
        }
    }
    
    /**
     * 根据当前用户判断其可操作的类型
     * @param array $bug
     */
    public static function createOperationBtn($bug)
    {
        $curUserId = \Illuminate\Support\Facades\Auth::user()->id;
        
        $html = '<a href="/@/' . $bug->id . '" class="btn # btn-sm">%</a>';
        
        // 如果是提交人或者解决人，则跳转到编辑页面
        if ($bug->solver_id == $curUserId || $bug->presenter_id == $curUserId)
        {
            $html = str_replace('@', 'edit', $html);
            $html = str_replace('#', 'btn-danger', $html);
            $html = str_replace('%', '修改', $html);
        }
        else
        {
            $html = str_replace('@', 'show', $html);
            $html = str_replace('#', 'btn-info', $html);
            $html = str_replace('%', '查看', $html);
        }
        
        return $html;
    }
    
    /**
     * 填充select控件
     * 
     * @param array $options
     * @param int $selectedId
     * @return string
     */
    public static function fillSelect($options, $selectedId)
    {
        $innerHtml = '';
        
        foreach ($options as $option)
        {
            $innerHtml .= ('<option value="'. $option->id . '"' . GeneralBeautifier::checkSelection($option->id,
                            $selectedId) . '>' . $option->name . '</option>');
        }
        
        return $innerHtml;
    }
    
    /**
     * 判断两个值是否相等
     * @param int $val1
     * @param int $val2
     * @return string
     */
    private static function checkSelection($val1, $val2)
    {
        return  $val1 == $val2 ? 'selected="selected"' : '';
    }
    
    public static function setTrColorByBugStatus($bug)
    {
        if ($bug->status == BugStatus::PENDING)
        {
            //红底                  3-7天之内Pending
            //黑底                  7天之外的
            $day = floor((strtotime('now') - strtotime($bug->created_at)) / 86400);
            if ($day <= 3)
            {
                return 'active';
            }
            else if ($day > 3 && $day <= 7)
            {
                return 'danger';
            }
            else
            {
                return 'dark';
            }
        }
        else if ($bug->status == BugStatus::STANDBY)
        {
            return 'warning';
        }
        else
        {
            return 'success';
        }
    }
}
