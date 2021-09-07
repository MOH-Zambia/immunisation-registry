<!-- Client Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('client_id', 'Client Id:') !!}
    {!! Form::number('client_id', null, ['class' => 'form-control']) !!}
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

<!-- Dose Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('dose_number', 'Dose Number:') !!}
    {!! Form::text('dose_number', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Date Of Next Dose Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_of_next_dose', 'Date Of Next Dose:') !!}
    {!! Form::text('date_of_next_dose', null, ['class' => 'form-control','id'=>'date_of_next_dose']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_of_next_dose').datetimepicker({
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

<!-- Vaccinating Country Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccinating_country_id', 'Vaccinating Country Id:') !!}
    {!! Form::number('vaccinating_country_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Vaccination Certificate Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccination_certificate_id', 'Vaccination Certificate Id:') !!}
    {!! Form::number('vaccination_certificate_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Record Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('record_id', 'Record Id:') !!}
    {!! Form::number('record_id', null, ['class' => 'form-control']) !!}
</div>
