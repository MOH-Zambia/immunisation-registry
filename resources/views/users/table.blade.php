<div class="table-responsive">
    <table class="table" id="users-table">
        <thead>
            <tr>
                <th>User Role Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Email Code</th>
        <th>Password</th>
        <th>Last Login</th>
        <th>Ip</th>
        <th>Salt</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->user_role_id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->email_code }}</td>
            <td>{{ $user->password }}</td>
            <td>{{ $user->last_login }}</td>
            <td>{{ $user->ip }}</td>
            <td>{{ $user->salt }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('users.show', [$user->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', [$user->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
