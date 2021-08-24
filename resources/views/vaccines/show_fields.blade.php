<!-- Product Name Field -->
<div class="col-sm-12">
    {!! Form::label('product_name', 'Product Name:') !!}
    <p>{{ $vaccine->product_name }}</p>
</div>

<!-- Short Description Field -->
<div class="col-sm-12">
    {!! Form::label('short_description', 'Short Description:') !!}
    <p>{{ $vaccine->short_description }}</p>
</div>

<!-- Vaccine Code Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_code', 'Vaccine Code:') !!}
    <p>{{ $vaccine->vaccine_code }}</p>
</div>

<!-- Vaccine Manufacturer Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_manufacturer', 'Vaccine Manufacturer:') !!}
    <p>{{ $vaccine->vaccine_manufacturer }}</p>
</div>

<!-- Vaccine Type Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_type', 'Vaccine Type:') !!}
    <p>{{ $vaccine->vaccine_type }}</p>
</div>

<!-- Commercial Formulation Field -->
<div class="col-sm-12">
    {!! Form::label('commercial_formulation', 'Commercial Formulation:') !!}
    <p>{{ $vaccine->commercial_formulation }}</p>
</div>

<!-- Vaccine Status Field -->
<div class="col-sm-12">
    {!! Form::label('vaccine_status', 'Vaccine Status:') !!}
    <p>{{ $vaccine->vaccine_status }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('notes', 'Notes:') !!}
    <p>{{ $vaccine->notes }}</p>
</div>

