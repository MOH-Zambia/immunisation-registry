<!-- Certificate Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('certificate_uuid', 'Certificate Uuid:') !!}
    {!! Form::text('certificate_uuid', null, ['class' => 'form-control','maxlength' => 36,'maxlength' => 36]) !!}
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

<!-- Client Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('client_id', 'Client Id:') !!}
    {!! Form::number('client_id', null, ['class' => 'form-control']) !!}
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

<!-- Client Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('client_status', 'Client Status:') !!}
    {!! Form::text('client_status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dose 1 Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_1_date', 'Dose 1 Date:') !!}
    {!! Form::text('dose_1_date', null, ['class' => 'form-control','id'=>'dose_1_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#dose_1_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dose 2 Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_2_date', 'Dose 2 Date:') !!}
    {!! Form::text('dose_2_date', null, ['class' => 'form-control','id'=>'dose_2_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#dose_2_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dose 3 Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_3_date', 'Dose 3 Date:') !!}
    {!! Form::text('dose_3_date', null, ['class' => 'form-control','id'=>'dose_3_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#dose_3_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dose 4 Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_4_date', 'Dose 4 Date:') !!}
    {!! Form::text('dose_4_date', null, ['class' => 'form-control','id'=>'dose_4_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#dose_4_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dose 5 Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_5_date', 'Dose 5 Date:') !!}
    {!! Form::text('dose_5_date', null, ['class' => 'form-control','id'=>'dose_5_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#dose_5_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Booster Dose Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('booster_dose_date', 'Booster Dose Date:') !!}
    {!! Form::text('booster_dose_date', null, ['class' => 'form-control','id'=>'booster_dose_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#booster_dose_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Qr Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qr_code', 'Qr Code:') !!}
    {!! Form::text('qr_code', null, ['class' => 'form-control','maxlength' => 65535,'maxlength' => 65535]) !!}
</div>

<!-- Qr Code Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qr_code_path', 'Qr Code Path:') !!}
    {!! Form::text('qr_code_path', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Certificate Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('certificate_url', 'Certificate Url:') !!}
    {!! Form::text('certificate_url', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>