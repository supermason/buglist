@extends('app')

@section('content')

{{ App\UI\BugsHelper::updateCurrentURI() }}

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
        <div class="panel-heading">
            {{ App\UI\BugsHelper::GetPanelHeading() }}
        </div>
        <div class="panel-body">
            <fieldset {{ App\UI\BugsHelper::needDisabled($data['bug']) }}>
                <form class="form-horizontal" method="POST" action="{{ App\UI\BugsHelper::createActionURL($data['bug'])}}" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="bugTitle" class="col-sm-2 control-label">标题：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugTitle" placeholder="请输入标题" required="required" 
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'title') }}"
                                   {!! App\UI\BugsHelper::canEdit($data['bug']) !!}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugImg" class="col-sm-2 control-label">bug截图：</label>
                        <div class=" col-sm-10">
                            @if (App\UI\BugsHelper::isAddPage())
                            <div id="imgEditor"></div>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $.getScript("/js/bootstrap-wysiwyg.js?" + ((new Date()).getMilliseconds())).done(function(){
                                        $('#imgEditor').wysiwyg();
                                    }).fail(function(){
                                        alert('bootstrap-wysiwyg.js 加载失败！');
                                    });
                                });
                            </script>
                            @else
                            <img src="{{$data['bug']->bug_img}}"/>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugContent" class="col-sm-2 control-label">文字内容：</label>
                        <div class=" col-sm-10">
                            <textarea class="form-control" name="bugContent" placeholder="请将bug的描述信息填写在此处" rows="10" required="required"
                                      {!! App\UI\BugsHelper::canEdit($data['bug']) !!}>{{ App\UI\BugsHelper::fillForm($data['bug'], 'content') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group {!! App\UI\BugsHelper::needHideForAdd() !!}">
                        <label for="bugStatus" class="col-sm-2 control-label">状态：</label>
                        <div class=" col-sm-10">
                            <select class="form-control" name="bugStatus">
                                <option value="{{App\Constants\BugStatus::PENDING}}" {!! App\UI\BugsHelper::checkSelection($data['bug'], 'status', 1, 0) !!}>Pending</option>
                                <option value="{{App\Constants\BugStatus::STANDBY}}" {!! App\UI\BugsHelper::checkSelection($data['bug'], 'status', 2, 1) !!}>Stand By</option>
                                <option value="{{App\Constants\BugStatus::OK}}" {!! App\UI\BugsHelper::checkSelection($data['bug'], 'status', 3, 2) !!}>OK</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group {!! App\UI\BugsHelper::needHideForAdd() !!}">
                        <label for="bugCreatedAt" class="col-sm-2 control-label">提交时间：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugCreatedAt" placeholder="" disabled 
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'created_at') }}">
                        </div>
                    </div>
                    <div class="form-group {!! App\UI\BugsHelper::needHideForAdd() !!}">
                        <label for="bugPresenter" class="col-sm-2 control-label">提交人：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugPresenter" placeholder="" disabled 
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'presenter') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugPriority" class="col-sm-2 control-label">优先级：</label>
                        <div class=" col-sm-10">
                            <select class="form-control" name="bugPriority" {!! App\UI\BugsHelper::canEdit($data['bug']) !!}>
                                <option value="0" {!! App\UI\BugsHelper::checkSelection($data['bug'], 'priority', 0, 0) !!}>紧急</option>
                                <option value="1" {!! App\UI\BugsHelper::checkSelection($data['bug'], 'priority', 1, 1) !!}>一般</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugModel" class="col-sm-2 control-label">模块：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugModel" placeholder="请输入bug所属的模块，可以不填"
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'model') }}" 
                                   {!! App\UI\BugsHelper::canEdit($data['bug']) !!}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugErrorCode" class="col-sm-2 control-label">错误码：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugErrorCode" placeholder="请输入与该bug相关的错误码，可以不填"
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'error_code') }}" 
                                   {!! App\UI\BugsHelper::canEdit($data['bug']) !!}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolver" class="col-sm-2 control-label">解决人：</label>
                        <div class=" col-sm-10">
                            <select class="form-control" name="bugSolver">
                                {!! App\UI\GeneralBeautifier::fillSelect($data['solvers'], App\UI\BugsHelper::getBugId($data['bug'])) !!}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolution" class="col-sm-2 control-label">解决方案：</label>
                        <div class=" col-sm-10">
                            <textarea class="form-control" name="bugSolution" placeholder="请将解决方案填写在此处" rows="10">{{ App\UI\BugsHelper::fillForm($data['bug'], 'solution') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group {!! App\UI\BugsHelper::needHideForAdd() !!}">
                        <label for="bugSolvedAt" class="col-sm-2 control-label">解决时间：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugSolvedAt" placeholder="暂无..." disabled
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'solved_at') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary  {!! App\UI\BugsHelper::needHideForShow() !!}" >{{ App\UI\BugsHelper::getSubmitBtnLbl() }} &nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                        </div>
                    </div>
                </form>  
            </fieldset>
            <a class="btn btn-primary" href="javascript:history.go(-1);" style="float: right;">返回&nbsp;<span class="glyphicon glyphicon-backward"></span></a>
        </div>
    </div>
</div>

@endsection