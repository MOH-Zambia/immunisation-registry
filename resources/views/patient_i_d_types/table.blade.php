<div class="table-responsive">
    <table class="table" id="patientIDTypes-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Pattern</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($patientIDTypes as $patientIDType)
            <tr>
                <td>{{ $patientIDType->name }}</td>
            <td>{{ $patientIDType->pattern }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['patientIDTypes.destroy', $patientIDType->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('patientIDTypes.show', [$patientIDType->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('patientIDTypes.edit', [$patientIDType->id]) }}" class='btn btn-default btn-xs'>
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
