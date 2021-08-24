<!-- Country Id Field -->
<div class="col-sm-12">
    {!! Form::label('country_id', 'Country Id:') !!}
    <p>{{ $patientID->country_id }}</p>
</div>

<!-- Expiration Date Field -->
<div class="col-sm-12">
    {!! Form::label('expiration_date', 'Expiration Date:') !!}
    <p>{{ $patientID->expiration_date }}</p>
</div>

<!-- Id Num Field -->
<div class="col-sm-12">
    {!! Form::label('id_num', 'Id Num:') !!}
    <p>{{ $patientID->id_num }}</p>
</div>

<!-- Id Type Id Field -->
<div class="col-sm-12">
    {!! Form::label('id_type_id', 'Id Type Id:') !!}
    <p>{{ $patientID->id_type_id }}</p>
</div>

<!-- Issue Date Field -->
<div class="col-sm-12">
    {!! Form::label('issue_date', 'Issue Date:') !!}
    <p>{{ $patientID->issue_date }}</p>
</div>

<!-- Issue Place Field -->
<div class="col-sm-12">
    {!! Form::label('issue_place', 'Issue Place:') !!}
    <p>{{ $patientID->issue_place }}</p>
</div>

