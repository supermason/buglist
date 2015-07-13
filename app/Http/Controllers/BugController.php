<?php

namespace App\Http\Controllers;

use App\Bug;
use App\User;

use App\Constants\BugStatus;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Redirect, Input, Auth;

class BugController extends Controller
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        //标题、内容、状态（Pending，Standby，OK）、提交时间、提交人、解决时间、解决方案、解决人、优先级（紧急、一般）、模块、错误号。
        return view('home')->withData([
            'bugs' => $this->queryBySolverId(\Illuminate\Support\Facades\Auth::user()->id),
            'solvers' => User::all(),
            'query' => [
                'id' => Auth::user()->id,
                'status' => 0,
            ],
            'keywords' => '',
        ]);
    }
    
    /**
     * 返回全部bug
     * 
     * @return Response
     */
    public function all()
    {
        return view('home')->withData([
            'bugs' => $this->defaultOrder($this->createQueryObj())->paginate(20),
            'solvers' => User::all(),
            'query' => [
                'id' => 0,
                'status' => 0,
            ],
            'keywords' => '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('addbug')->withData([
            'solvers' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'bugTitle' => 'required',
        ]);
        
        $bug = new Bug();
        $bug->title = Input::get('bugTitle');
        $bug->bug_detail = Input::get('bugDetail');
        $bug->presenter_id = Auth::user()->id;
        $bug->solver_id = Input::get('bugSolver');
        $bug->status = BugStatus::PENDING;
        $bug->priority = Input::get('bugPriority');
        $bug->model = Input::get('bugModel');
        $bug->error_code = Input::get('bugErrorCode');
        
        if ($bug->save())
        {
//            return redirect('/success')->withMessage([
//                'info' => 'Bug[' . Input::get('bugTitle')  . ']添加成功！',
//                'to' => '/all',
//                'back' => '/create',
//            ]);
            return Redirect::to('/all');
        }
        else
        {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * 显示一条bug的详细信息
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('bugdetail')->withData([
            'bug' => $this->queryByBugId($id),
            'solvers' => User::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('editbug')->withData([
            'bug' => $this->queryByBugId($id),
            'solvers' => User::all(),
        ]);
    }
    
    /**
     * 进入修复bug页面
     * @param int $id
     * @return Response
     */
    public function fix($id)
    {
        return view('fixbug')->withData($this->queryByBugId($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $bug = Bug::find($id);
        
        if (empty($bug))
        {
            abort(500);
        }
        else
        {
            $process = Input::get('process');
            
            if ($process == 'fix') // 修复bug
            {
                if ($bug->status == BugStatus::OK) // 不能修复以修复的bug
                {
                    abort(501);
                }
                else
                {
                    $bug->status = BugStatus::OK;
                    $bug->solved_at = date('Y-m-d H:i:s');
                    $bug->solution = trim(Input::get('bugSolution'));
                    $bug->solver_id = Input::get('solverId');
                }
            }
            else if ($process == 'modify') // 提交人自己修改bug
            {
                $bug->title = Input::get('bugTitle');
                $bug->bug_detail = trim(Input::get('bugDetail'));
                $bug->solver_id = Input::get('bugSolver');
                $bug->priority = Input::get('bugPriority');
                $bug->model = Input::get('bugModel');
                $bug->error_code = Input::get('bugErrorCode');
            }
            else if ($process == 'transfer') // 之前指定的解决人将bug转移给新的解决人
            {
                //这里就不管是不是和之前指定的是同一个人了
                $bug->solver_id = Input::get('bugSolver');
            }
            
            
            if ($bug->push())
            {
//                return redirect('/success')->withMessage([
//                    'info' => 'Bug[' . Input::get('bugTitle')  . ']修改成功！',
//                    'to' => '/all',
//                    'back' => '',
//                ]);
                return Redirect::to('/all');
            }
            else
            {
                return Redirect::back()->withInput()->withErrors('修改失败失败！');
            }
        }
    }
    
     /**
     * 暂停一条bug
     *
     * @param  int  $id
     * @return Response
     */
    public function negotiate($id)
    {
        $bug = Bug::find($id);
        
        if (empty($bug))
        {
            abort(500);
        }
        else
        {
            $bug->status = BugStatus::STANDBY;
            
            if ($bug->push())
            {
                return Redirect::to('/all');
            }
            else
            {
                return Redirect::back()->withInput()->withErrors('修改失败失败！');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * 根据条件进行查询
     * @param int $id
     * @param int $status 
     */
    public function search($id, $status)
    {
        $whereCluase = '';
        $condition = [];
        
        if ($id != 0 && $status != 0) 
        {
            $whereCluase = 'solver_id = ? and status = ?';
            $condition = [$id, $status];
        }
        else if ($id == 0 && $status == 0)
        {
            return $this->all();
        }
        else
        {
            if ($id != 0)
            {
                $whereCluase = 'solver_id = ?';
                $condition = [$id];
            }
            else
            {
                $whereCluase = 'status = ?';
                $condition = [$status];
            }
        }
        
        return view('home')->withData([
            'bugs' => $this->defaultOrder($this->createQueryObj()->whereRaw($whereCluase, $condition))->paginate(20),
            'solvers' => User::all(),
            'query' => [
                'id' => $id,
                'status' => $status,
            ],
            'keywords' => '',
        ]);
    }
    
    /**
     * 根据bug的标题进行模糊查询
     * @param String $keywords
     * @return Response
     */
    public function fuzzySearch($keywords)
    {
        return view('home')->withData([
            'bugs' => $this->defaultOrder($this->createQueryObj()->where('title', 'like', '%' . $keywords . '%'))->paginate(20),
            'solvers' => User::all(),
            'query' => [
                'id' => 0,
                'status' => 0,
            ],
            'keywords' => $keywords,
        ]);
    }
    
    /**
     * 查看bug原图
     * @param int id
     * @return Response
     */
    public function viewImg($id) 
    {
        return view('bugimg')->withSrc(Bug::find($id)->bug_img);
    }
    
    /*
    |--------------------------------------------------------------------------
    | 私有工具方法
    |--------------------------------------------------------------------------
    |
    */

    /**
     * 创建查询Bug的通用代码
     * @return Bug
     */
    private function createQueryObj() 
    {
        return Bug::select('bugs.id', 'title', 'bug_detail', 'status', 'bugs.created_at', 
                            'presenter_id', 'u1.name as presenter', 
                            'solved_at', 'solution', 
                            'solver_id', 'u2.name as solver', 'priority', 'model', 'error_code')
                ->leftJoin('users As u1', 'presenter_id', '=', 'u1.id')
                ->leftJoin('users AS u2', 'solver_id', '=', 'u2.id');
    }
    
    /**
     * 对bug按照status | priority | created_at 排列
     * @param model $query
     * @return model
     */
    private function defaultOrder($query)
    {
        return $query->orderBy('status', 'asc')->orderBy('priority', 'asc')->orderBy('created_at', 'desc');
    }
    
    /**
     * 根据用户编号查询该用户需要解决的bug列表[默认每页20个]
     * @param int $id
     * @param int $status
     * @param int $pageSize
     */
    private function queryBySolverId($id, $status = -1, $pageSize = 20) 
    {
        $query = null;
        
        if ($status == -1) // 查询全部
        {
            $query = $this->createQueryObj()->where('solver_id', '=', $id);
        } 
        else // 根据状态查询
        {
            $query = $this->createQueryObj()->whereRaw('solver_id = ? and status = ?', [$id, $status]);
        }
        
        return $this->defaultOrder($query) ->paginate($pageSize);
    }
    
    /**
     * 根据bug编号查询一条bug记录
     * @param int $bugId
     */
    private function queryByBugId($bugId)
    {
        return $this->defaultOrder($this->createQueryObj()->where('bugs.id', '=', $bugId))
                ->firstOrFail();
    }
}
