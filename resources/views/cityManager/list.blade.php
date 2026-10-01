@extends('layouts.user-layout')
@section('content')
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --secondary-color: #64748b;
        --success-color: #22c55e;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --dark-color: #1e293b;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-500: #64748b;
        --gray-700: #334155;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --radius-lg: 0.75rem;
        --radius-md: 0.5rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
        background: transparent;
    }

    .modern-table thead th {
        background: white;
        padding: 1rem;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.025em;
        border: none;
        border-bottom: 2px solid var(--gray-100);
    }

    .modern-table tbody tr {
        background: white;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .modern-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .modern-table td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
        background: white;
    }

    .modern-table td:first-child { border-radius: var(--radius-md) 0 0 var(--radius-md); }
    .modern-table td:last-child { border-radius: 0 var(--radius-md) var(--radius-md) 0; }

    .id-badge {
        background: var(--gray-100);
        color: var(--gray-700);
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .table-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gray-100);
    }

    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-md);
        transition: all 0.2s;
        margin: 0 2px;
        border: none;
    }

    .action-view { background: var(--info-color); color: white; }
    .action-edit { background: var(--warning-color); color: white; }
    .action-delete { background: var(--danger-color); color: white; }
    .action-ban { background: var(--dark-color); color: white; }

    .action-btn:hover {
        transform: scale(1.1);
        color: white;
        opacity: 0.9;
    }

    /* Mobile Responsive Table */
    @media (max-width: 768px) {
        .modern-table thead {
            display: none;
        }
        
        .modern-table, .modern-table tbody, .modern-table tr, .modern-table td {
            display: block;
            width: 100%;
        }
        
        .modern-table tr {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            position: relative;
        }
        
        .modern-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0.5rem;
            border: none;
            border-bottom: 1px solid var(--gray-100);
            text-align: right;
        }
        
        .modern-table td:last-child {
            border-bottom: none;
            padding-top: 1rem;
            justify-content: center;
        }
        
        .modern-table td::before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-right: 1rem;
            text-align: left;
        }

        .project-actions {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
    }
</style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="font-weight-bold">City Managers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">City Managers</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card shadow-none bg-transparent">
                <div class="card-body p-0">
                    <table class="modern-table" id="proj">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Photo</th>
                                <th>National ID</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr id="did{{ $user->id }}">
                                    <td class="text-center" data-label="{{ __('messages.id') }}">
                                        <span class="id-badge">#{{ $user->id }}</span>
                                    </td>
                                    <td data-label="{{ __('messages.name') }}">
                                        <span class="font-weight-bold">{{ $user->name }}</span>
                                    </td>
                                    <td data-label="{{ __('messages.email') }}">
                                        <span class="text-muted">{{ $user->email }}</span>
                                    </td>
                                    <td class="text-center" data-label="{{ __('messages.photo') }}">
                                        <img alt="Avatar" class="table-avatar"
                                            src="{{ asset($user->profile_image) }}"
                                            onerror="this.src='{{ asset('dist/img/avatar5.png') }}'">
                                    </td>
                                    <td data-label="{{ __('messages.national_id') }}">
                                        <span class="badge badge-light">{{ $user->national_id }}</span>
                                    </td>
                                    <td class="project-actions text-right" data-label="{{ __('messages.actions') }}">
                                        <a class="action-btn action-view"
                                            href="{{ route('cityManager.show', $user['id']) }}" title="Voir">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="action-btn action-edit"
                                            href="{{ route('cityManager.edit', $user['id']) }}" title="Modifier">
                                            <i class="fas fa-pencil-alt"></i></a>

                                        <a href="javascript:void(0)" onclick="deletecityManager({{ $user->id }})"
                                            class="action-btn action-delete" title="Supprimer">
                                            <i class="fas fa-trash"></i></a>

                                        <a href="javascript:void(0)" onclick="banUser({{ $user->id }})"
                                            class="action-btn action-ban" title="Bannir">
                                            <i class="fa fa-user-lock"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center py-3">
                        {{ $users->links() }}
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
    </div>
    <!-- /.content-wrapper -->
    <script>
        function banUser(id) {
            if (confirm("Do you want to ban this user?")) {
                $.ajax({
                    url: '/banUser/' + id,
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("#did" + id).remove();
                    }
                });
            }
        }

        function deletecityManager(id) {
            if (confirm("Do you want to delete this record?")) {
                $.ajax({
                    url: '/cityManager/' + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("#did" + id).remove();
                    }
                });
            }
        }
    </script>
@endsection
