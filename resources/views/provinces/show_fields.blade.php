<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $province->name }}</p>
</div>

<!-- Population Field -->
<div class="col-sm-12">
    {!! Form::label('population', 'Population:') !!}
    <p>{{ $province->population }}</p>
</div>

<!-- Pop Density Field -->
<div class="col-sm-12">
    {!! Form::label('pop_density', 'Pop Density:') !!}
    <p>{{ $province->pop_density }}</p>
</div>

<!-- Area Sq Km Field -->
<div class="col-sm-12">
    {!! Form::label('area_sq_km', 'Area Sq Km:') !!}
    <p>{{ $province->area_sq_km }}</p>
</div>

<!-- Geometry Field -->
<div class="col-sm-12">
    {!! Form::label('geometry', 'Geometry:') !!}
    <p>{{ $province->geometry }}</p>
</div>

