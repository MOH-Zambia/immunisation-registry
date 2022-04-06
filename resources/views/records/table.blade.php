<div class="table-responsive">
    <table class="table" id="records-table">
        <thead>
            <tr>
                <th>Data Source</th>
        <th>Data Type</th>
        <th>Data</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td>{{ $record->data_source }}</td>
            <td>{{ $record->data_type }}</td>
            <td>{{ $record->data }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['records.destroy', $record->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('records.show', [$record->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <!-- <a href="{{ route('records.edit', [$record->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} -->
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
