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

<!-- Cdc Cvx Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cdc_cvx_code', 'Cdc Cvx Code:') !!}
    {!! Form::text('cdc_cvx_code', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Manufacturer Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_manufacturer', 'Vaccine Manufacturer:') !!}
    {!! Form::text('vaccine_manufacturer', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Cdc Mvx Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cdc_mvx_code', 'Cdc Mvx Code:') !!}
    {!! Form::text('cdc_mvx_code', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Vaccine Group Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vaccine_group', 'Vaccine Group:') !!}
    {!! Form::text('vaccine_group', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
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