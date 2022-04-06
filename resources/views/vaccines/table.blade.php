<div class="table-responsive">
    <table class="table" id="vaccines-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Short Description</th>
                <th>Vaccine Manufacturer</th>
                <th>Vaccine Group</th>
                <th>Commercial Formulation</th>
                <th>Vaccine Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vaccines as $vaccine)
            <tr>
                <td>{{ $vaccine->product_name }}</td>
                <td>{{ $vaccine->short_description }}</td>
                <td>{{ $vaccine->vaccine_manufacturer }}</td>
                <td>{{ $vaccine->vaccine_group }}</td>
                <td>{{ $vaccine->commercial_formulation }}</td>
                <td>{{ $vaccine->vaccine_status }}</td>
                <td>{{ $vaccine->notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['vaccines.destroy', $vaccine->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('vaccines.show', [$vaccine->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <!-- <a href="{{ route('vaccines.edit', [$vaccine->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a> -->
                        <!-- {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} -->
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
