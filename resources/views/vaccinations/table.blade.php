<div class="table-responsive">
    <table class="table" id="vaccinations-table">
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Date</th>
                <th>Vaccine</th>
                <th>Dose Number</th>
                <th>Date Of Next Dose</th>
                <th>Vaccine Batch Number</th>
                <th>Vaccinating Organization</th>
                <th>Country</th>
                <th>Certificate ID</th>
                <th>Facility</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vaccinations as $vaccinations)
            <tr>
                <td>{{ $vaccinations->client_id }}</td>
                <td>{{ $vaccinations->date }}</td>
                <td>{{ $vaccinations->vaccine->product_name }}</td>
                <td>{{ $vaccinations->dose_number }}</td>
                <td>{{ $vaccinations->date_of_next_dose }}</td>
                <td>{{ $vaccinations->vaccine_batch_number }}</td>
                <td>{{ $vaccinations->vaccinating_organization }}</td>
                <td>{{ $vaccinations->country->name }}</td>
                <td>{{ $vaccinations->certificate_id }}</td>
                <td>{{ $vaccinations->facility->name }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['vaccinations.destroy', $vaccinations->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('vaccinations.show', [$vaccinations->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('vaccinations.edit', [$vaccinations->id]) }}" class='btn btn-default btn-xs'>
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
