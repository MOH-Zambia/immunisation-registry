<div class="table-responsive">
    <table class="table" id="importLogs-table">
        <thead>
            <tr>
                <th>Hash</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($importLogs as $importLog)
            <tr>
                <td>{{ $importLog->hash }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['importLogs.destroy', $importLog->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('importLogs.show', [$importLog->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('importLogs.edit', [$importLog->id]) }}" class='btn btn-default btn-xs'>
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
