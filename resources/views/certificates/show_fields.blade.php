<!-- Vaccination Certificate Id Field -->
<div class="col-sm-12">
    {!! Form::label('vaccination_certificate_id', 'Vaccination Certificate ID:') !!}
    <p>{{ $certificate->vaccination_certificate_id }}</p>
</div>

<!-- Signature Algorithm Field -->
<div class="col-sm-12">
    {!! Form::label('signature_algorithm', 'Signature Algorithm:') !!}
    <p>{{ $certificate->signature_algorithm }}</p>
</div>

<!-- Certificate Issuing Authority Id Field -->
<div class="col-sm-12">
    {!! Form::label('certificate_issuing_authority_id', 'Certificate Issuing Authority Id:') !!}
    <p>{{ $certificate->certificate_issuing_authority_id }}</p>
</div>

<!-- Vaccination Certificate Batch Number Field -->
<div class="col-sm-12">
    {!! Form::label('vaccination_certificate_batch_number', 'Vaccination Certificate Batch Number:') !!}
    <p>{{ $certificate->vaccination_certificate_batch_number }}</p>
</div>

<!-- Client Id Field -->
<div class="col-sm-12">
    {!! Form::label('client_id', 'Client Id:') !!}
    <p>{{ $certificate->client_id }}</p>
</div>

<!-- Certificate Expiration Date Field -->
<div class="col-sm-12">
    {!! Form::label('certificate_expiration_date', 'Certificate Expiration Date:') !!}
    <p>{{ $certificate->certificate_expiration_date }}</p>
</div>

<!-- Innoculated Since Date Field -->
<div class="col-sm-12">
    {!! Form::label('innoculated_since_date', 'Innoculated Since Date:') !!}
    <p>{{ $certificate->innoculated_since_date }}</p>
</div>

<!-- Recovery Date Field -->
<div class="col-sm-12">
    {!! Form::label('recovery_date', 'Recovery Date:') !!}
    <p>{{ $certificate->recovery_date }}</p>
</div>

<!-- Client Status Field -->
<div class="col-sm-12">
    {!! Form::label('client_status', 'Client Status:') !!}
    <p>{{ $certificate->client_status }}</p>
</div>

<!-- Dose 1 Date Field -->
<div class="col-sm-12">
    {!! Form::label('dose_1_date', 'Dose 1 Date:') !!}
    <p>{{ $certificate->dose_1_date }}</p>
</div>

<!-- Dose 2 Date Field -->
<div class="col-sm-12">
    {!! Form::label('dose_2_date', 'Dose 2 Date:') !!}
    <p>{{ $certificate->dose_2_date }}</p>
</div>

<!-- Dose 3 Date Field -->
<div class="col-sm-12">
    {!! Form::label('dose_3_date', 'Dose 3 Date:') !!}
    <p>{{ $certificate->dose_3_date }}</p>
</div>

<!-- Dose 4 Date Field -->
<div class="col-sm-12">
    {!! Form::label('dose_4_date', 'Dose 4 Date:') !!}
    <p>{{ $certificate->dose_4_date }}</p>
</div>

<!-- Dose 5 Date Field -->
<div class="col-sm-12">
    {!! Form::label('dose_5_date', 'Dose 5 Date:') !!}
    <p>{{ $certificate->dose_5_date }}</p>
</div>

<!-- Booster Dose Date Field -->
<div class="col-sm-12">
    {!! Form::label('booster_dose_date', 'Booster Dose Date:') !!}
    <p>{{ $certificate->booster_dose_date }}</p>
</div>

<!-- Qr Code Field -->
<div class="col-sm-12">
    {!! Form::label('qr_code', 'Qr Code:') !!}
    <p><img src="{{ url('img/qrcodes/'.$certificate->vaccination_certificate_id.'.png') }}" alt="QR Code"/></p>
</div>

<!-- Certificate Url Field -->
<div class="col-sm-12">
    {!! Form::label('certificate_url', 'Certificate Url:') !!}
    <p>{{ $certificate->certificate_url }}</p>
</div>

