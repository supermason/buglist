@extends('app')

@section('content')

{{ App\UI\BugsHelper::updateCurrentURI() }}

<div class="container-fluid">
    <form class="form-inline search-form" onsubmit="return false;">
        <div class="form-group has-feedback">
            <input type="text" name="keywords" class="form-control" id="keywords" placeholder="请输入bug名称关键字" value="{{$data['keywords']}}" />
            <span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="form-group bordered-group">
            <div class="form-group">
                <label for="bugStatus">状态：</label>
                <select class="form-control" id="statusSelect">
                    <option value="0" {!! App\UI\GeneralBeautifier::checkSelection(0, $data['query']['status']) !!}>--</option>
                    <option value="1" {!! App\UI\GeneralBeautifier::checkSelection(1, $data['query']['status']) !!}>Pending</option>
                    <option value="2" {!! App\UI\GeneralBeautifier::checkSelection(2, $data['query']['status']) !!}>Stand By</option>
                    <option value="3" {!! App\UI\GeneralBeautifier::checkSelection(3, $data['query']['status']) !!}>OK</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bugSolver">解决人：</label>
                <select class="form-control" id="bugSolver" >
                    <option value="0" selected="selected">--</option>
                    {!! App\UI\GeneralBeautifier::fillSelect($data['solvers'], $data['query']['id']) !!}
                </select>
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="searchBuyByOption();" ><span class="glyphicon glyphicon-search"></span>&nbsp;查询</button>
        </div>
        <div class="form-group ">
            <a class="btn btn-primary" href="{{URL('/all')}}"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;全部bug</a>
            <a class="btn btn-primary" href="{{URL('/')}}"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;需要我解决的</a>
        </div>
        <div class="form-group bordered-group" style="float: right;">
            <a href="{{ URL('/create')}}" class="btn btn-danger">添加新bug</a>
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
                <td>解决人</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        @foreach ($data['bugs'] as $bug)
        <tr class="{{ App\UI\GeneralBeautifier::setTrColorByBugStatus($bug) }}">
            <td>{{ $bug->id }}</td>
            <td>{{ App\UI\GeneralBeautifier::mapStatusToString($bug->status) }}</td>
            <td>{!! App\UI\GeneralBeautifier::getBugTitle($bug) !!}</td>
            <td>{{ $bug->created_at }}</td>
            <td>{{ $bug->presenter }}</td>
            <td>{{ $bug->solved_at }}</td>
            <td>{{ $bug->solver }}</td>
            <td>{!! App\UI\GeneralBeautifier::createOperationBtn($bug) !!}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    
    {!! $data['bugs']->render() !!}
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.masonUI.bindEnterEvtToText(".form-inline.search-form", searchBugByName);
    });
</script>

@endsection