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
            修复Bug
            <a class="btn btn-default btn-sm top-right" href="javascript:history.go(-1);" >
                <span class="glyphicon glyphicon-backward"></span>&nbsp;返回
            </a>
        </div>
        <div class="panel-body">
            <fieldset>
                <form class="form-horizontal" id="fixForm" method="POST" action="{{URL('/update/' . $data->id)}}" onsubmit="return doSubmit(this);">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="process" value="fix" />
                    <input type="hidden" name="solverId" value="{{ Auth::user()->id }}" />
                    <input type="hidden" name="bug_id" value="{{$data->id}}"/>
                    <div class="form-group">
                        <label for="bugTitle" class="col-sm-1 control-label">标题：</label>
                        <div class=" col-sm-11">
                            <div class="form-control none-selection">
                                {!! App\UI\GeneralBeautifier::getBugTitle($data) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugDetail" class="col-sm-1 control-label">bug详情：</label>
                        <div class=" col-sm-11">
                            <div class="bug-detail">
                                {!!str_replace(PHP_EOL, "", trim($data->bug_detail))!!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolver" class="col-sm-1 control-label">解决人：</label>
                        <div class=" col-sm-11">
                            <input name="bugSolver" class="form-control" value="{{Auth::user()->name}}" disabled />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bugSolution" class="col-sm-1 control-label">解决方案：</label>
                        <div class=" col-sm-11">
                             <input id="bugSolution" name="bugSolution" type="hidden"/>
                             <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
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

                            <div id="editorSolution">

                            </div>
                        </div>
                    </div>
                    <a href="{{URL('/negotiate/' . $data->id)}}" class="btn btn-warning right-btn"  >
                        <span class="glyphicon glyphicon-phone-alt"></span>&nbsp;需要沟通
                    </a>
                    <button id="btnFix" class="btn btn-primary right-btn" >
                        <span class="glyphicon glyphicon-plus"></span>&nbsp;修复
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
        $('#editorSolution').wysiwyg();
        window.prettyPrint && prettyPrint();
    });
</script>

@endsection