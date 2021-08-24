<div class="table-responsive">
    <table class="table" id="vaccinations-table">
        <thead>
            <tr>
                <th>Patient Id</th>
        <th>Vaccine Id</th>
        <th>Provider Id</th>
        <th>Date</th>
        <th>Type Of Strategy</th>
        <th>Vaccine Batch Number</th>
        <th>Vaccine Batch Expiration Date</th>
        <th>Vaccinating Organization Id</th>
        <th>Vaccinating Country</th>
        <th>Record Id</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vaccinations as $vaccination)
            <tr>
                <td>{{ $vaccination->patient_id }}</td>
            <td>{{ $vaccination->vaccine_id }}</td>
            <td>{{ $vaccination->provider_id }}</td>
            <td>{{ $vaccination->date }}</td>
            <td>{{ $vaccination->type_of_strategy }}</td>
            <td>{{ $vaccination->vaccine_batch_number }}</td>
            <td>{{ $vaccination->vaccine_batch_expiration_date }}</td>
            <td>{{ $vaccination->vaccinating_organization_id }}</td>
            <td>{{ $vaccination->vaccinating_country }}</td>
            <td>{{ $vaccination->record_id }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['vaccinations.destroy', $vaccination->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('vaccinations.show', [$vaccination->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('vaccinations.edit', [$vaccination->id]) }}" class='btn btn-default btn-xs'>
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
