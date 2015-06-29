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
    <a class="btn btn-default right-btn" href="javascript:history.go(-1);" ><span class="glyphicon glyphicon-backward"></span>&nbsp;返回</a>
    <div class="panel panel-primary">
        <div class="panel-heading">
            修改Bug信息
        </div>
        <div class="panel-body">
            <fieldset>
                <form class="form-horizontal" method="POST" id="modifyForm" action="{{ URL('/update/' . $data['bug']->id) }}" onsubmit="return fillFormData(this);" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="process" value="modify" />
                    <div class="form-group">
                        <label for="bugTitle" class="col-sm-1 control-label">标题：</label>
                        <div class=" col-sm-11">
                            <input type="text" class="form-control" name="bugTitle" placeholder="请输入标题" required="required" 
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'title') }}" />
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
                            <div class="btn-toolbar {!! App\UI\BugsHelper::needHideEditorToolBar($data['bug']) !!}" data-role="editor-toolbar" data-target="#editor">
                                  <div class="btn-group">
                                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="icon-font"></i><b class="caret"></b></a>
                                      <ul class="dropdown-menu">
                                      </ul>
                                    </div>
                                  <div class="btn-group">
                                    <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
                                      <ul class="dropdown-menu">
                                      <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
                                      <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                                      <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
                                      </ul>
                                  </div>
                                  <div class="btn-group">
                                    <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
                                    <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
                                    <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
                                    <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
                                  </div>
                                  <div class="btn-group">
                                    <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
                                    <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
                                    <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
                                    <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
                                  </div>
                                  <div class="btn-group">
                                    <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
                                    <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
                                    <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
                                    <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
                                  </div>
                                  <div class="btn-group">
                                              <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
                                                <div class="dropdown-menu input-append">
                                                        <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
                                                        <button class="btn" type="button">Add</button>
                                    </div>
                                    <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>

                                  </div>

                                  <div class="btn-group">
                                    <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
                                    <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                                  </div>
                                  <div class="btn-group">
                                    <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
                                    <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
                                  </div>
                                  <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
                                </div>

                                <div id="editor">

                                </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugPriority" class="col-sm-1 control-label">优先级：</label>
                        <div class=" col-sm-4">
                            <label class="radio-inline">
                                <input type="radio" name="bugPriority" id="inlineRadio1" value="0" 
                                       {!! App\UI\GeneralBeautifier::checkChecked($data['bug']['priority'], 0) !!}}/> 紧急
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="bugPriority" id="inlineRadio2" value="1"
                                       {!! App\UI\GeneralBeautifier::checkChecked($data['bug']['priority'], 1) !!}}/> 一般
                            </label>
                        </div>
                        <label for="bugSolver" class="col-sm-2 control-label">指定解决人：</label>
                        <div class=" col-sm-5">
                            <select class="form-control" name="bugSolver">
                                {!! App\UI\GeneralBeautifier::fillSelect($data['solvers'], App\UI\BugsHelper::fillForm($data['bug'], 'solver_id')) !!}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugModel" class="col-sm-1 control-label">模块：</label>
                        <div class=" col-sm-5">
                            <input type="text" class="form-control" name="bugModel" placeholder="请输入bug所属的模块，可以不填"
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'model') }}" />
                        </div>
                        <label for="bugErrorCode" class="col-sm-1 control-label">错误码：</label>
                        <div class=" col-sm-5">
                            <input type="text" class="form-control" name="bugErrorCode" placeholder="请输入与该bug相关的错误码，可以不填"
                                   value="{{ App\UI\BugsHelper::fillForm($data['bug'], 'error_code') }}" />
                        </div>
                    </div>
                    <div class="form-group {!!App\UI\BugsHelper::needHideSolution($data['bug'])!!}">
                        <label for="bugSolution" class="col-sm-1 control-label">解决方案：</label>
                        <div class=" col-sm-11">
                            <textarea class="form-control" name="bugSolution" placeholder="请将解决方案填写在此处" rows="10">{{App\UI\BugsHelper::fillForm($data['bug'], 'solution') }}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary right-btn" >
                        <span class="glyphicon glyphicon-edit"></span>&nbsp;修改
                    </button>
                </form>  
            </fieldset>
        </div>
    </div>
</div>

<script>
  $(function(){
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
            'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
            'Times New Roman', 'Verdana'],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function (idx, fontName) {
          fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
      });
      $('a[title]').tooltip({container:'body'});
    	$('.dropdown-menu input').click(function() {return false;})
		    .change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
        .keydown('esc', function () {this.value='';$(this).change();});

      $('[data-role=magic-overlay]').each(function () { 
        var overlay = $(this), target = $(overlay.data('target')); 
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
      $('#voiceBtn').hide();
      // if ("onwebkitspeechchange"  in document.createElement("input")) {
      //   var editorOffset = $('#editor').offset();
      //   $('#voiceBtn').css('position','absolute').offset({top: editorOffset.top, left: editorOffset.left+$('#editor').innerWidth()-35});
      // } else {
      //   $('#voiceBtn').hide();
      // }
    };
    initToolbarBootstrapBindings();  
    $('#editor').wysiwyg();
    window.prettyPrint && prettyPrint();
    
    $('#editor').html('{!!str_replace(PHP_EOL, "", trim($data["bug"]->bug_detail))!!}');
  });
</script>

@endsection