<div class="table-responsive">
    <table class="table" id="providers-table">
        <thead>
            <tr>
                <th>First Name</th>
        <th>Last Name</th>
        <th>Other Names</th>
        <th>Sex</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($providers as $provider)
            <tr>
                <td>{{ $provider->first_name }}</td>
            <td>{{ $provider->last_name }}</td>
            <td>{{ $provider->other_names }}</td>
            <td>{{ $provider->sex }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['providers.destroy', $provider->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('providers.show', [$provider->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('providers.edit', [$provider->id]) }}" class='btn btn-default btn-xs'>
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
