@extends('app')

@section('content')
<div class="container-fluid">
    @if (count($data['bugs']) == 0)
    <div class="alert alert-info">
        当前没有需要您解决的bug！您可以
        <a href="{{ URL('/create')}}" class="btn btn-primary">添加新bug</a>&nbsp;或
        <a href="{{ URL('/search')}}" class="btn btn-danger">查看全部Bug</a>
    </div>
    @else
    <form class="form-inline search-form" onsubmit="return false;">
        <div class="form-group bordered-group hidden">
            <label for="bugTitle">标题：</label>
            <input type="text" class="form-control" id="bugTitle" placeholder="标题模糊查询">
        </div>
        <div class="form-group bordered-group">
            <div class="form-group">
                <label for="bugStatus">状态：</label>
                <select class="form-control" id="statusSelect">
                    <option value="-1">--</option>
                    <option value="0" selected="selected">Pending</option>
                    <option value="1">Stand By</option>
                    <option value="2">OK</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bugSolver">解决人：</label>
                <select class="form-control" id="solverSelect" >
                    <option value="-1">--</option>
                    {!! App\UI\GeneralBeautifier::fillSelect($data['solvers'], -1) !!}
                </select>
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="" >查询&nbsp;&nbsp;<span class="glyphicon glyphicon-search"></span></button>
        </div>
        <div class="form-group bordered-group">
            <a class="btn btn-primary" href="{{URL('/search')}}">查看全部&nbsp;&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>
            <a class="btn btn-primary" href="{{URL('/')}}">查看我的&nbsp;&nbsp;<span class="glyphicon glyphicon-eye-open"></span></a>
            <a href="{{ URL('/create')}}" class="btn btn-primary">添加新bug&nbsp;&nbsp;<span class="glyphicon glyphicon-plus-sign"></a>
        </div>
    </form>

    {!! $data['bugs']->render() !!}
    
    <table class="table table-responsive table-hover table-striped">
        <thead>
            <tr>
                <td>编号</td>
                <td>状态</td>
                <td>标题</td>
                <td>内容</td>
                <td>提交时间</td>
                <td>提交人</td>
                <td>解决时间</td>
                <td>解决方案</td>
                <td>解决人</td>
                <td>优先级</td>
                <td>模块</td>
                <td>错误号</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        @foreach ($data['bugs'] as $bug)
        <tr class="{{ App\UI\GeneralBeautifier::setTrColorByBugStatus($bug) }}">
                <td>{{ $bug->id }}</td>
                <td>{{ App\UI\GeneralBeautifier::mapStatusToString($bug->status) }}</td>
                <td>{{ $bug->title }}</td>
                <td>{{ App\UI\GeneralBeautifier::truncateContent($bug->content, 30) }}</td>
                <td>{{ $bug->created_at }}</td>
                <td>{{ $bug->presenter }}</td>
                <td>{{ $bug->solved_at }}</td>
                <td>{{ App\UI\GeneralBeautifier::truncateContent($bug->solution, 30) }}</td>
                <td>{{ $bug->solver }}</td>
                <td>{!! App\UI\GeneralBeautifier::decoratePriority($bug->priority) !!}</td>
                <td>{{ $bug->model }}</td>
                <td>{{ $bug->error_code }}</td>
                <td>{!! App\UI\GeneralBeautifier::createOperationBtn($bug) !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    {!! $data['bugs']->render() !!}
    
    @endif
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.masonUI.bindEnterEvtToText(".form-inline.search-form", searchBugByName);
    });
</script>

@endsection