<?php

namespace App\Http\Controllers;

use App\Bug;
use App\User;

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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('bugdetail')->withData([
            'bug' => null,
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
            'bugContent' => 'required',
        ]);
        
        $bug = new Bug();
        $bug->title = Input::get('bugTitle');
        $bug->bug_img = Input::get('imgEditor');
        $bug->content = Input::get('bugContent');
        $bug->presenter_id = Auth::user()->id;
        $bug->solver_id = 1; //Input::get('bugSolver');
        $bug->priority = Input::get('bugPriority');
        $bug->model = Input::get('bugModel');
        $bug->error_code = Input::get('bugErrorCode');
        $bug->solution = Input::get('bugSolution');
        
        if ($bug->save())
        {
            return Redirect::to('/');
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
        return view('bugdetail')->withData([
            'bug' => $this->queryByBugId($id),
            'solvers' => User::all(),
        ]);
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
     * @param string $query
     */
    public function search($query=null)
    {
        if ($query == null)
        {
            return view('home')->withData([
                'bugs' => $this->defaultOrder($this->createQueryObj())->paginate(20),
                'solvers' => User::all(),
            ]);
        }
        else
        {
            
        }
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
        return Bug::select('bugs.id', 'title', 'bug_img', 'content', 'status', 'bugs.created_at', 
                            'presenter_id', 'u1.name as presenter', 
                            'solved_at', 'solution', 
                            'solver_id', 'u2.name as solver', 'priority', 'model', 'error_code')
                ->leftJoin('users As u1', 'presenter_id', '=', 'u1.id')
                ->leftJoin('users AS u2', 'solver_id', '=', 'u2.id');
    }
    
    /**
     * 对bug按照status 降序排列
     * @param model $query
     * @return model
     */
    private function defaultOrder($query)
    {
        return $query->orderBy('status', 'asc')->orderBy('created_at', 'desc');
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
            $query = $this->defaultOrder($this->createQueryObj()->where('solver_id', '=', $id));
        } 
        else // 根据状态查询
        {
            $query = $this->defaultOrder($this->createQueryObj()->whereRaw('solver_id = ? and status = ?', [$id, $status]));
        }
        
        return $query ->paginate($pageSize);
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