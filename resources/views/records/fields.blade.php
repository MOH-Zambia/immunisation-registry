<!-- Data Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_source', 'Data Source:') !!}
    {!! Form::text('data_source', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Data Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_type', 'Data Type:') !!}
    {!! Form::text('data_type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Data Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('data', 'Data:') !!}
    {!! Form::textarea('data', null, ['class' => 'form-control']) !!}
</div>