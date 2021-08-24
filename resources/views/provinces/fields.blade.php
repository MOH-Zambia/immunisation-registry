<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Population Field -->
<div class="form-group col-sm-6">
    {!! Form::label('population', 'Population:') !!}
    {!! Form::number('population', null, ['class' => 'form-control']) !!}
</div>

<!-- Pop Density Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pop_density', 'Pop Density:') !!}
    {!! Form::number('pop_density', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Sq Km Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_sq_km', 'Area Sq Km:') !!}
    {!! Form::number('area_sq_km', null, ['class' => 'form-control']) !!}
</div>

<!-- Geometry Field -->
<div class="form-group col-sm-6">
    {!! Form::label('geometry', 'Geometry:') !!}
    {!! Form::text('geometry', null, ['class' => 'form-control']) !!}
</div>