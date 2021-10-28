<!-- User Role Id Field -->
{{--<div class="form-group col-sm-6">--}}
{{--    {!! Form::label('role_id', 'User Role:') !!}--}}
{{--    {!! Form::number('role_id', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- User Role -->
<div class="form-group col-sm-6">
    <label for="role">Role</label>
    <select class="form-control" id="role">
{{--        <option value="{!! $user->role['id'] !!}">{!! $user->role['name'] !!}</option>--}}
        @foreach($roles as $role)
            @if($role['id'] = $user->role['id'])
                <option selected value="{!! $role['id'] !!}">{!! $role['name'] !!}</option>
            @else
                <option value="{!! $role['id'] !!}">{!! $role['name'] !!}</option>
            @endif
        @endforeach
    </select>
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    <label for="confirm_password">Confirm Password</label>
    <input class="form-control" maxlength="255" name="confirm_password" type="password" value="" id="confirm_password">
</div>
