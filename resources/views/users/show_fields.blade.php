<!-- User Role Id Field -->
<div class="col-sm-12">
    {!! Form::label('role_id', 'User Role:') !!}
    <p>{{ $user->role['name'] }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p>{{ $user->last_name }}</p>
</div>

<!-- Last Name Field -->
<div class="col-sm-12">
    {!! Form::label('firt_name', 'First Name:') !!}
    <p>{{ $user->first_name }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>

<!-- Last Login Field -->
<div class="col-sm-12">
    {!! Form::label('last_login', 'Last Login:') !!}
    <p>{{ $user->last_login }}</p>
</div>

<!-- Last Login IP Field -->
<div class="col-sm-12">
    {!! Form::label('last_login_ip', 'Last Login IP:') !!}
    <p>{{ $user->last_login_ip }}</p>
</div>

