@extends('app')

@section('content')

{{ App\UI\BugsHelper::updateCurrentURI() }}

<div class="container-fluid">
    @if (count($data['bugs']) == 0)
    <div class="alert alert-info">
        @if (App\UI\BugsHelper::isAllPage())
        太棒了，目前一个bug也没有！当然，你可以
        <a href="{{ URL('/create')}}" class="btn btn-primary">添加新bug&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        @else
        当前没有需要{{App\UI\BugsHelper::getSolverName($data['query']['id'], $data['solvers'])}}解决的bug！您可以
        <a href="{{ URL('/create')}}" class="btn btn-primary">添加新bug&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;或
        <a href="{{ URL('/all')}}" class="btn btn-danger">查看全部Bug&nbsp;&nbsp;<span class="glyphicon glyphicon-list"></span></a>
        @endif
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
                    <option value="0" {!! App\UI\GeneralBeautifier::checkSelection(0, $data['query']['status']) !!}}>--</option>
                    <option value="1" {!! App\UI\GeneralBeautifier::checkSelection(1, $data['query']['status']) !!}}>Pending</option>
                    <option value="2" {!! App\UI\GeneralBeautifier::checkSelection(2, $data['query']['status']) !!}}>Stand By</option>
                    <option value="3" {!! App\UI\GeneralBeautifier::checkSelection(3, $data['query']['status']) !!}}>OK</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bugSolver">解决人：</label>
                <select class="form-control" id="bugSolver" >
                    <option value="0" selected="selected">--</option>
                    {!! App\UI\GeneralBeautifier::fillSelect($data['solvers'], $data['query']['id']) !!}
                </select>
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="searchBuyByOption();" >查询&nbsp;&nbsp;<span class="glyphicon glyphicon-search"></span></button>
        </div>
        <div class="form-group bordered-group">
            <a class="btn btn-primary" href="{{URL('/all')}}">全部bug&nbsp;&nbsp;<span class="glyphicon glyphicon-list-alt"></span></a>
            <a class="btn btn-primary" href="{{URL('/')}}">需要我解决的&nbsp;&nbsp;<span class="glyphicon glyphicon-eye-open"></span></a>
        </div>
        <div class="form-group bordered-group" style="float: right;">
            <a href="{{ URL('/create')}}" class="btn btn-danger">添加新bug&nbsp;&nbsp;<span class="glyphicon glyphicon-plus-sign"></a>
        </div>
    </form>

    {!! $data['bugs']->render() !!}
    
    <table class="table table-responsive table-hover table-striped table-responsive">
        <thead>
            <tr>
                <td>编号</td>
                <td>状态</td>
                <td>标题</td>
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