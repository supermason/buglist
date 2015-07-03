@extends('app')

@section('content')

<div class="container">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong>输入的信息有误哦<br/><br/>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="panel panel-primary">
        <div class="panel-heading fix-right-btn">
            查看Bugu信息
            <a class="btn btn-default btn-sm top-right" href="javascript:history.go(-1);" ><span class="glyphicon glyphicon-backward"></span>&nbsp;返回</a>
        </div>
        <div class="panel-body">
            <fieldset >
                <form class="form-horizontal" method="POST" id="transferForm" action="{{ URL('/update/' . $data['bug']->id) }}" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="process" value="transfer" />
                    <input type="hidden" name="bug_id" value="{{$data['bug']->id}}"/>
                    <div class="form-group">
                        <label for="bugTitle" class="col-sm-1 control-label">标题：</label>
                        <div class=" col-sm-11">
                            <div class="form-control">
                                {!! App\UI\GeneralBeautifier::getBugTitle($data['bug']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group {!! App\UI\BugsHelper::needHideForAdd() !!}">
                        <label for="bugCreatedAt" class="col-sm-1 control-label">提交时间：</label>
                        <div class=" col-sm-5">
                            <input type="text" class="form-control" name="bugCreatedAt" placeholder="" disabled 
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'created_at') }}">
                        </div>
                        <label for="bugPresenter" class="col-sm-1 control-label">提交人：</label>
                        <div class=" col-sm-5">
                            <input type="text" class="form-control" name="bugPresenter" placeholder="" disabled 
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'presenter') }}">
                        </div>
                    </div>
                    <div class="form-group none-selection">
                        <label for="bugDetail" class="col-sm-1 control-label">bug详情：</label>
                        <div class=" col-sm-11">
                            <input type="hidden" id="bugDetail" name="bugDetail"/>
                            <div class="bug-detail">
                                {!!str_replace(PHP_EOL, "", trim($data["bug"]->bug_detail))!!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolver" class="col-sm-1 control-label">{!!App\UI\GeneralBeautifier::getSolverTitle($data['bug'])!!}</label>
                        {!! App\UI\GeneralBeautifier::createSolverSelect($data['solvers'], $data['bug']) !!}
                    </div>
                    <div class="form-group none-selection">
                        <label for="bugSolution" class="col-sm-1 control-label">解决方案：</label>
                        <div class=" col-sm-11">
                            <div class="bug-detail">
                                {!!str_replace(PHP_EOL, "", trim($data["bug"]->solution))!!} <br/>
                            </div>
                        </div>
                    </div>
                </form>  
            </fieldset>
        </div>
    </div>
</div>

@endsection