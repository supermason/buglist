@extends('app')

@section('content')

<div class="container">
    <div class="panel panel-success">
        <div class="panel-heading">
            温馨提示
        </div>
        <div class="panel-body">
            <div class="alert alert-success">
                {{ $data['info'] }}
            </div>
        </div>
        <div class="panel-footer">
            @if ($data['back'] != '')
            <a href="{{$data['back']}}" class="btn btn-default">继续添加</a>
            @endif
            <a href="{{ URL('/') }}" class="btn btn-default">查看我的</a>
            <a href="{{$data['to']}}" class="btn btn-default">查看全部</a>
        </div>
    </div>
</div>



@endsection