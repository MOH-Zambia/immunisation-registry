<div class="table-responsive">
    <table class="table" id="roles-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr>
                <td><a href="{{ route('roles.show', [$role->id]) }}">{{ $role->name }}</a></td>
                <td>{{ $role->description }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
