<!-- Province Id Field -->
<div class="col-sm-12">
    {!! Form::label('province_id', 'Province Id:') !!}
    <p>{{ $district->province_id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $district->name }}</p>
</div>

<!-- District Type Field -->
<div class="col-sm-12">
    {!! Form::label('district_type', 'District Type:') !!}
    <p>{{ $district->district_type }}</p>
</div>

<!-- Population Field -->
<div class="col-sm-12">
    {!! Form::label('population', 'Population:') !!}
    <p>{{ $district->population }}</p>
</div>

<!-- Pop Density Field -->
<div class="col-sm-12">
    {!! Form::label('pop_density', 'Pop Density:') !!}
    <p>{{ $district->pop_density }}</p>
</div>

<!-- Area Sq Km Field -->
<div class="col-sm-12">
    {!! Form::label('area_sq_km', 'Area Sq Km:') !!}
    <p>{{ $district->area_sq_km }}</p>
</div>

<!-- Geometry Field -->
<div class="col-sm-12">
    {!! Form::label('geometry', 'Geometry:') !!}
    <p>{{ $district->geometry }}</p>
</div>

