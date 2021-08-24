<!-- Patient Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('patient_id', 'Patient Id:') !!}
    {!! Form::number('patient_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Vaccine Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_id', 'Vaccine Id:') !!}
    {!! Form::number('vaccine_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Provider Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('provider_id', 'Provider Id:') !!}
    {!! Form::number('provider_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::text('date', null, ['class' => 'form-control','id'=>'date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Type Of Strategy Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type_of_strategy', 'Type Of Strategy:') !!}
    {!! Form::text('type_of_strategy', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Batch Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_batch_number', 'Vaccine Batch Number:') !!}
    {!! Form::text('vaccine_batch_number', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Batch Expiration Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_batch_expiration_date', 'Vaccine Batch Expiration Date:') !!}
    {!! Form::text('vaccine_batch_expiration_date', null, ['class' => 'form-control','id'=>'vaccine_batch_expiration_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#vaccine_batch_expiration_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Vaccinating Organization Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccinating_organization_id', 'Vaccinating Organization Id:') !!}
    {!! Form::text('vaccinating_organization_id', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccinating Country Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccinating_country', 'Vaccinating Country:') !!}
    {!! Form::text('vaccinating_country', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Record Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('record_id', 'Record Id:') !!}
    {!! Form::number('record_id', null, ['class' => 'form-control']) !!}
</div>