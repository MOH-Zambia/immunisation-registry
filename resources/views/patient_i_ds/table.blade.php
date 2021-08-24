<div class="table-responsive">
    <table class="table" id="patientIDs-table">
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
        @foreach($patientIDs as $patientID)
            <tr>
                <td>{{ $patientID->country_id }}</td>
            <td>{{ $patientID->expiration_date }}</td>
            <td>{{ $patientID->id_num }}</td>
            <td>{{ $patientID->id_type_id }}</td>
            <td>{{ $patientID->issue_date }}</td>
            <td>{{ $patientID->issue_place }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['patientIDs.destroy', $patientID->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('patientIDs.show', [$patientID->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('patientIDs.edit', [$patientID->id]) }}" class='btn btn-default btn-xs'>
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
