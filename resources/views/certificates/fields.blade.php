<!-- Vaccination Certificate Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccination_certificate_id', 'Vaccination Certificate Id:') !!}
    {!! Form::text('vaccination_certificate_id', null, ['class' => 'form-control','maxlength' => 36,'maxlength' => 36]) !!}
</div>

<!-- Signature Algorithm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('signature_algorithm', 'Signature Algorithm:') !!}
    {!! Form::text('signature_algorithm', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Certificate Issuing Authority Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('certificate_issuing_authority_id', 'Certificate Issuing Authority Id:') !!}
    {!! Form::number('certificate_issuing_authority_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Vaccination Certificate Batch Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccination_certificate_batch_number', 'Vaccination Certificate Batch Number:') !!}
    {!! Form::text('vaccination_certificate_batch_number', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Patient Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('patient_id', 'Patient Id:') !!}
    {!! Form::number('patient_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Certificate Expiration Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('certificate_expiration_date', 'Certificate Expiration Date:') !!}
    {!! Form::text('certificate_expiration_date', null, ['class' => 'form-control','id'=>'certificate_expiration_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#certificate_expiration_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Innoculated Since Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('innoculated_since_date', 'Innoculated Since Date:') !!}
    {!! Form::text('innoculated_since_date', null, ['class' => 'form-control','id'=>'innoculated_since_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#innoculated_since_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Recovery Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('recovery_date', 'Recovery Date:') !!}
    {!! Form::text('recovery_date', null, ['class' => 'form-control','id'=>'recovery_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#recovery_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Patient Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('patient_status', 'Patient Status:') !!}
    {!! Form::text('patient_status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dose 1 Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_1_id', 'Dose 1 Id:') !!}
    {!! Form::number('dose_1_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Dose 2 Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_2_id', 'Dose 2 Id:') !!}
    {!! Form::number('dose_2_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Dose 3 Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_3_id', 'Dose 3 Id:') !!}
    {!! Form::number('dose_3_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Dose 4 Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_4_id', 'Dose 4 Id:') !!}
    {!! Form::number('dose_4_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Qr Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qr_code', 'Qr Code:') !!}
    {!! Form::text('qr_code', null, ['class' => 'form-control','maxlength' => 65535,'maxlength' => 65535]) !!}
</div>

<!-- Certificate Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('certificate_url', 'Certificate Url:') !!}
    {!! Form::text('certificate_url', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>