<div class="table-responsive">
    <table class="table" id="certificates-table">
        <thead>
            <tr>
                <th>Certificate UUID</th>
                <th>Trusted Vaccine Code</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Other Names</th>
                <th>Certificate Issuing Authority</th>
                <th>Certificate Expiration Date</th>
                <th>Dose 1 Date</th>
                <th>Dose 2 Date</th>
                <th>Dose 3 Date</th>
                <th>Booster Dose Date</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($certificates as $certificate)
            <tr>
                <td>{{ $certificate->certificate_uuid }}</td>
                <td>{{ $certificate->africa_cdc_trusted_vaccine_code }}</td>
                <td>{{ $certificate->client->last_name }}</td>
                <td>{{ $certificate->client->first_name }}</td>
                <td>{{ $certificate->client->other_names }}</td>
                <td>{{ $certificate->certificate_issuing_authority_id }}</td>
                <td>{{ $certificate->certificate_expiration_date }}</td>
                <td>{{ $certificate->dose_1_date }}</td>
                <td>{{ $certificate->dose_2_date }}</td>
                <td>{{ $certificate->dose_3_date }}</td>
                <td>{{ $certificate->booster_dose_date }}</td>
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
