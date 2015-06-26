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
        $newContent = trim($content);
        
        $contentLength = strlen($newContent);
        
        if ($contentLength <= $displayLength)
        {
            return $newContent;
        }
        else
        {
            return substr($newContent, 0, $displayLength);
        }
    }
    
    /**
     * 修饰优先级文字
     * @param int $priority
     */
    public static function decoratePriority($priority)
    {
        return '<span class="' . ($priority == 0 ? 'emergent' : 'normal') . '">[' . ($priority == 0 ? '紧急' : '一般') . ']</span>';
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
            case BugStatus::STANDBY:
                return "Standby";
            case BugStatus::OK:
                return "OK";
            case BugStatus::PENDING:
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
        
        //如果是未修复状态，
        if ($bug->status != BugStatus::OK)
        {
            //如果是提交人，则可以修改[修改+修复]
            if ($bug->presenter_id == $curUserId)
            {
                $html = str_replace('@', 'edit', $html);
                $html = str_replace('#', 'btn-danger', $html);
                $html = str_replace('%', '修改', $html);
            }
            else // 其他人是[查看+修复]
            {
                $html = str_replace('@', 'show', $html);
                $html = str_replace('#', 'btn-info', $html);
                $html = str_replace('%', '查看', $html);
            }
            
            $html .= ('&nbsp;&nbsp;<a href="/fix/' . $bug->id . '" class="btn btn-success btn-sm">修复</a>');
        }
        else //修复状态直接是查看
        {
            return '<a href="/show/' . $bug->id . '" class="btn btn-info btn-sm">查看</a>';
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
    public static function checkSelection($val1, $val2)
    {
        return  $val1 == $val2 ? 'selected="selected"' : '';
    }
    
    /**
     * 判断两个值是否相等
     * @param int $val1
     * @param int $val2
     * @return string
     */
    public static function checkChecked($val1, $val2)
    {
        return  $val1 == $val2 ? 'checked="checked"' : '';
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
