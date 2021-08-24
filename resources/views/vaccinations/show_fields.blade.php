<!-- Patient Id Field -->
<div class="col-sm-12">
    {!! Form::label('patient_id', 'Patient Id:') !!}
    <p>{{ $vaccination->patient_id }}</p>
</div>

<!-- Vaccine Id Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_id', 'Vaccine Id:') !!}
    <p>{{ $vaccination->vaccine_id }}</p>
</div>

<!-- Provider Id Field -->
<div class="col-sm-12">
    {!! Form::label('provider_id', 'Provider Id:') !!}
    <p>{{ $vaccination->provider_id }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $vaccination->date }}</p>
</div>

<!-- Type Of Strategy Field -->
<div class="col-sm-12">
    {!! Form::label('type_of_strategy', 'Type Of Strategy:') !!}
    <p>{{ $vaccination->type_of_strategy }}</p>
</div>

<!-- Vaccine Batch Number Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_batch_number', 'Vaccine Batch Number:') !!}
    <p>{{ $vaccination->vaccine_batch_number }}</p>
</div>

<!-- Vaccine Batch Expiration Date Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_batch_expiration_date', 'Vaccine Batch Expiration Date:') !!}
    <p>{{ $vaccination->vaccine_batch_expiration_date }}</p>
</div>

<!-- Vaccinating Organization Id Field -->
<div class="col-sm-12">
    {!! Form::label('vaccinating_organization_id', 'Vaccinating Organization Id:') !!}
    <p>{{ $vaccination->vaccinating_organization_id }}</p>
</div>

<!-- Vaccinating Country Field -->
<div class="col-sm-12">
    {!! Form::label('vaccinating_country', 'Vaccinating Country:') !!}
    <p>{{ $vaccination->vaccinating_country }}</p>
</div>

<!-- Record Id Field -->
<div class="col-sm-12">
    {!! Form::label('record_id', 'Record Id:') !!}
    <p>{{ $vaccination->record_id }}</p>
</div>

