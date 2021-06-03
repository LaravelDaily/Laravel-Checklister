@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive-sm">
                                <thead>
                                <tr>
                                    <th>{{ __('Register Time') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Website') }}</th>
                                    <th>{{ __('Payment Plan') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->website }}</td>
                                        <td>
                                            @if ($user->has_free_access)
                                                {{ __('Free Access') }}
                                                <form action="{{ route('admin.users.toggle_free_access', $user) }}"
                                                      method="POST"
                                                      style="display: inline-block; margin-left: 10px">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        {{ __('Remove Free Access') }}
                                                    </button>
                                                </form>
                                            @elseif ($user->subscribed())
                                                {{ __('Paid Plan') }}
                                            @else
                                                ---
                                                <form action="{{ route('admin.users.toggle_free_access', $user) }}"
                                                      method="POST"
                                                      style="display: inline-block; margin-left: 10px">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info">
                                                        {{ __('Give Free Access') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
