<!-- User Role Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_role_id', 'User Role Id:') !!}
    {!! Form::number('user_role_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Email Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_code', 'Email Code:') !!}
    {!! Form::text('email_code', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Last Login Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_login', 'Last Login:') !!}
    {!! Form::text('last_login', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ip', 'Ip:') !!}
    {!! Form::text('ip', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Salt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salt', 'Salt:') !!}
    {!! Form::text('salt', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>