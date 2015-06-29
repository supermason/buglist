@extends('app')

@section('content')

<script src="/js/jquery.hotkeys.js"></script>
<script src="/js/bootstrap-wysiwyg.js"></script>

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
            <fieldset disabled>
                <form class="form-horizontal" method="POST" id="modifyForm" action="{{ URL('/update/' . $data['bug']->id) }}" onsubmit="return fillFormData(this);" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="process" value="modify" />
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
                    <div class="form-group">
                        <label for="bugDetail" class="col-sm-1 control-label">bug详情：</label>
                        <div class=" col-sm-11">
                            <input type="hidden" id="bugDetail" name="bugDetail"/>
                            <div id="editor">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolver" class="col-sm-1 control-label">{!!App\UI\GeneralBeautifier::getSolverTitle($data['bug'])!!}</label>
                        <div class=" col-sm-11">
                            <select class="form-control" name="bugSolver">
                                {!! App\UI\GeneralBeautifier::fillSelect($data['solvers'], App\UI\BugsHelper::fillForm($data['bug'], 'solver_id')) !!}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolution" class="col-sm-1 control-label">解决方案：</label>
                        <div class=" col-sm-11">
                            <div id="editorSolution">

                            </div>
                        </div>
                    </div>
                </form>  
            </fieldset>
        </div>
    </div>
</div>

<script>
  $(function(){
    $('#editor').wysiwyg();
    $('#editor').html('{!!str_replace(PHP_EOL, "", trim($data["bug"]->bug_detail))!!}');
    
    $('#editorSolution').wysiwyg();
    $('#editorSolution').html('{!!str_replace(PHP_EOL, "", trim($data["bug"]->solution))!!}');
  });
</script>

@endsection