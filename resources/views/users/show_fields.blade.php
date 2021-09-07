<!-- User Role Id Field -->
<div class="col-sm-12">
    {!! Form::label('user_role_id', 'User Role Id:') !!}
    <p>{{ $user->user_role_id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $user->name }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>

<!-- Email Code Field -->
<div class="col-sm-12">
    {!! Form::label('email_code', 'Email Code:') !!}
    <p>{{ $user->email_code }}</p>
</div>

<!-- Password Field -->
<div class="col-sm-12">
    {!! Form::label('password', 'Password:') !!}
    <p>{{ $user->password }}</p>
</div>

<!-- Last Login Field -->
<div class="col-sm-12">
    {!! Form::label('last_login', 'Last Login:') !!}
    <p>{{ $user->last_login }}</p>
</div>

<!-- Ip Field -->
<div class="col-sm-12">
    {!! Form::label('ip', 'Ip:') !!}
    <p>{{ $user->ip }}</p>
</div>

<!-- Salt Field -->
<div class="col-sm-12">
    {!! Form::label('salt', 'Salt:') !!}
    <p>{{ $user->salt }}</p>
</div>

