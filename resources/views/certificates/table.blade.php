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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($certificates as $certificate)
            <tr>
                <td><a href="{{ route('certificates.show', [$certificate->id]) }}"> {{ $certificate->certificate_uuid }}</a> </td>
                <td>{{ $certificate->trusted_vaccine_code }}</td>
                <td><a href="{{ route('clients.show', [$certificate->client_id]) }}"> {{ $certificate->client->last_name }} </a></td>
                <td><a href="{{ route('clients.show', [$certificate->client_id]) }}"> {{ $certificate->client->first_name }} </a></td>
                <td><a href="{{ route('clients.show', [$certificate->client_id]) }}"> {{ $certificate->client->other_names }} </a></td>
                <td>{{ $certificate->certificate_issuing_authority_id }}</td>
                <td>{{ $certificate->certificate_expiration_date }}</td>
                <td>{{ $certificate->dose_1_date }}</td>
                <td>{{ $certificate->dose_2_date }}</td>
                <td>{{ $certificate->dose_3_date }}</td>
                <td>{{ $certificate->booster_dose_date }}</td>
                <td>
                    <div class='btn-group'>
                        <a href="{{ route('certificates.show', [$certificate->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
