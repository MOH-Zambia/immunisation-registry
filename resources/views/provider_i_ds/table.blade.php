<div class="table-responsive">
    <table class="table" id="providerIDs-table">
        <thead>
            <tr>
                <th>Country Id</th>
        <th>Expiration Date</th>
        <th>Id Num</th>
        <th>Id Type Id</th>
        <th>Issue Date</th>
        <th>Issue Place</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($providerIDs as $providerID)
            <tr>
                <td>{{ $providerID->country_id }}</td>
            <td>{{ $providerID->expiration_date }}</td>
            <td>{{ $providerID->id_num }}</td>
            <td>{{ $providerID->id_type_id }}</td>
            <td>{{ $providerID->issue_date }}</td>
            <td>{{ $providerID->issue_place }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['providerIDs.destroy', $providerID->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('providerIDs.show', [$providerID->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('providerIDs.edit', [$providerID->id]) }}" class='btn btn-default btn-xs'>
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
