{{-- @dd($banUsers) --}}
@extends('layouts.user-layout')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>{{ __('messages.all_banned_users') }}</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ __('messages.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('messages.coaches') }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.banned') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped projects" id="proj">
                        <thead>
                            <tr>
                                <th class="project-state">{{ __('messages.id') }}</th>
                                <th class="project-state">{{ __('messages.name') }}</th>
                                <th class="project-state">{{ __('messages.email') }}</th>
                                <th class="project-state">{{ __('messages.profile_image') }}</th>
                                <th class="project-state">{{ __('messages.unban') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banUsers as $user)
                                <tr id="cid{{ $user->id }}">
                                    <th class="project-state" scope="row">{{ $user->id }}</th>
                                    <td class="project-state">
                                        <span>{{ $user->name }}</span>
                                    </td>
                                    <td class="project-state">
                                        <span>{{ $user->email }}</span>
                                    </td>
                                    <td class="project-state">
                                        <img alt="Avatar" class="table-avatar" src="{{ $user->profile_image }}">
                                    </td>
                                    <td class="project-actions text-center">
                                        <a href="javascript:void(0)" onclick="unBanUsers({{ $user->id }})"
                                            class="btn btn-dark btn-sm"><i class="fas fa-unlock"></i></a>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
    </div>
    <script>
        function unBanUsers(id) {
            if (confirm("{{ __('messages.confirm_unban') }}")) {
                $.ajax({
                    url: '/unBan/' + id,
                    type: 'PATCH',
                    data: {
                        _token: $("input[name=_token]").val()
                    },
                    success: function(response) {
                        $("#cid" + id).remove();
                    }
                });
            }
        }
    </script>
@endsection
