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
        <div class="panel-heading">
            修复Bug
        </div>
        <div class="panel-body">
            <fieldset>
                <form class="form-horizontal" id="fixForm" method="POST" action="{{$data->id}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="process" value="fix" />
                    <input type="hidden" name="solverId" value="{{ Auth::user()->id }}" />
                    <div class="form-group">
                        <label for="bugTitle" class="col-sm-2 control-label">标题：</label>
                        <div class=" col-sm-10">
                            <input type="text" class="form-control" name="bugTitle" value="{{$data->title}}" disabled/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugDetail" class="col-sm-2 control-label">bug详情：</label>
                        <div class=" col-sm-10">
                                <div id="editor">

                                </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugPriority" class="col-sm-2 control-label">优先级：</label>
                        <div class=" col-sm-4">
                            <input name="bugPriority" class="form-control" value="{{$data->priority == 0 ? '紧急': '一般'}}" disabled />
                        </div>
                        <label for="bugSolver" class="col-sm-2 control-label">解决人：</label>
                        <div class=" col-sm-4">
                            <input name="bugSolver" class="form-control" value="{{Auth::user()->name}}" disabled />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugModel" class="col-sm-2 control-label">模块：</label>
                        <div class=" col-sm-4">
                            <input type="text" class="form-control" name="bugModel" value="{{$data->model}}" disabled />
                        </div>
                        <label for="bugErrorCode" class="col-sm-2 control-label">错误码：</label>
                        <div class=" col-sm-4">
                            <input type="text" class="form-control" name="bugErrorCode" value="{{$data->error_code}}" disabled />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolution" class="col-sm-2 control-label">解决方案：</label>
                        <div class=" col-sm-10">
                            <textarea class="form-control" name="bugSolution" placeholder="请将解决方案填写在此处" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button id="btnFix" class="btn btn-primary " onclick="doSubmit(this);" >修复 &nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                            <button id="btnNegotiate" class="btn btn-warning " onclick="doSubmit(this);" >需要沟通 &nbsp;<span class="glyphicon glyphicon-phone-alt"></span></a>
                        </div>
                    </div>
                </form>  
            </fieldset>
            <a class="btn btn-default" href="javascript:history.go(-1);" style="float: right;">返回&nbsp;<span class="glyphicon glyphicon-backward"></span></a>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#editor').wysiwyg();
        $('#editor').html('{!!trim($data->bug_detail)!!}');
        $('#editor').attr('disabled', 'disabled');
    });
</script>

@endsection