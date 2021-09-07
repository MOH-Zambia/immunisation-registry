<div class="table-responsive">
    <table class="table" id="certificates-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>NRC</th>
                <th>Dose 1 Date</th>
                <th>Dose 2 Date</th>
                <th>Booster Dose Date</th>
                <th>Certificate Url</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($certificates as $certificate)
            <tr>
                <td>{{ $certificate->client['first_name'] }} {{ $certificate->client['last_name'] }}</td>
                <td>{{ $certificate->client['NRC'] }}</td>
                <td>{{ $certificate->dose_1_date }}</td>
                <td>{{ $certificate->dose_2_date }}</td>
                <td>{{ $certificate->booster_dose_date }}</td>
                <td>{{ $certificate->certificate_url }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['certificates.destroy', $certificate->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('certificates.show', [$certificate->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('certificates.edit', [$certificate->id]) }}" class='btn btn-default btn-xs'>
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
