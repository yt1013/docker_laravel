@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">ユーザ一覧</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <a class="mb-3 btn btn-success" href="{{ route('user.create') }}">新規登録</a>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>名前</th>
                                <th>権限</th>
                                <th>メールアドレス</th>
                                <th>登録日</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><a href="{{ route('user.edit', ['user' => $user->id]) }}">{{ $user->id }}</a></td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
