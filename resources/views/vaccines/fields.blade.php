<!-- Product Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_name', 'Product Name:') !!}
    {!! Form::text('product_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Short Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('short_description', 'Short Description:') !!}
    {!! Form::text('short_description', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_code', 'Vaccine Code:') !!}
    {!! Form::text('vaccine_code', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Manufacturer Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_manufacturer', 'Vaccine Manufacturer:') !!}
    {!! Form::text('vaccine_manufacturer', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_type', 'Vaccine Type:') !!}
    {!! Form::text('vaccine_type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Commercial Formulation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('commercial_formulation', 'Commercial Formulation:') !!}
    {!! Form::text('commercial_formulation', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_status', 'Vaccine Status:') !!}
    {!! Form::text('vaccine_status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
</div>