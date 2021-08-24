<!-- Country Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('country_id', 'Country Id:') !!}
    {!! Form::number('country_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Expiration Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('expiration_date', 'Expiration Date:') !!}
    {!! Form::text('expiration_date', null, ['class' => 'form-control','id'=>'expiration_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#expiration_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Id Num Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_num', 'Id Num:') !!}
    {!! Form::text('id_num', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Id Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_type_id', 'Id Type Id:') !!}
    {!! Form::number('id_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Issue Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('issue_date', 'Issue Date:') !!}
    {!! Form::text('issue_date', null, ['class' => 'form-control','id'=>'issue_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#issue_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Issue Place Field -->
<div class="form-group col-sm-6">
    {!! Form::label('issue_place', 'Issue Place:') !!}
    {!! Form::text('issue_place', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>