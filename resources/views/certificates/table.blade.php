<div class="table-responsive">
    <table class="table" id="certificates-table">
        <thead>
            <tr>
                <th>Vaccination Certificate Id</th>
        <th>Signature Algorithm</th>
        <th>Certificate Issuing Authority Id</th>
        <th>Vaccination Certificate Batch Number</th>
        <th>Patient Id</th>
        <th>Certificate Expiration Date</th>
        <th>Innoculated Since Date</th>
        <th>Recovery Date</th>
        <th>Patient Status</th>
        <th>Dose 1 Id</th>
        <th>Dose 2 Id</th>
        <th>Dose 3 Id</th>
        <th>Dose 4 Id</th>
        <th>Qr Code</th>
        <th>Certificate Url</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($certificates as $certificate)
            <tr>
                <td>{{ $certificate->vaccination_certificate_id }}</td>
            <td>{{ $certificate->signature_algorithm }}</td>
            <td>{{ $certificate->certificate_issuing_authority_id }}</td>
            <td>{{ $certificate->vaccination_certificate_batch_number }}</td>
            <td>{{ $certificate->patient_id }}</td>
            <td>{{ $certificate->certificate_expiration_date }}</td>
            <td>{{ $certificate->innoculated_since_date }}</td>
            <td>{{ $certificate->recovery_date }}</td>
            <td>{{ $certificate->patient_status }}</td>
            <td>{{ $certificate->dose_1_id }}</td>
            <td>{{ $certificate->dose_2_id }}</td>
            <td>{{ $certificate->dose_3_id }}</td>
            <td>{{ $certificate->dose_4_id }}</td>
            <td>{{ $certificate->qr_code }}</td>
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
