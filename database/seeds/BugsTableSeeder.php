<?php
/**
 * Description of BugsTableSeeder
 *
 * @author mason.ding
 */

use App\Bug;

class BugsTableSeeder  extends Illuminate\Database\Seeder {
    
    public function run() 
    {
        for ($i = 0; $i < 100; ++$i)
        {
            //标题、内容、状态（Pending，Standby，OK）、提交时间、提交人、解决时间、解决方案、解决人、优先级（紧急、一般）、模块、错误号。
            Bug::create([
                'title' => '测试数据',
                'bug_detail' => '这就是一段测试数据而已，无视就好',
                'status' => $i % 3 + 1,
                'presenter_id' => 1,
                'solved_at' => NULL,
                'solution' => '',
                'solver_id' => 1,
                'priority' => 0,
                'model' => 'Customer',
                'error_code' => '908732'
            ]);
        }
    }
}
