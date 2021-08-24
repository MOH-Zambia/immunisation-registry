<!-- Patient Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('patient_id', 'Patient Id:') !!}
    {!! Form::text('patient_id', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Other Names Field -->
<div class="form-group col-sm-6">
    {!! Form::label('other_names', 'Other Names:') !!}
    {!! Form::text('other_names', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Sex Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sex', 'Sex:') !!}
    {!! Form::text('sex', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Occupation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('occupation', 'Occupation:') !!}
    {!! Form::text('occupation', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Contact Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact_number', 'Contact Number:') !!}
    {!! Form::text('contact_number', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Contact Email Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact_email_address', 'Contact Email Address:') !!}
    {!! Form::text('contact_email_address', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Next Of Kin Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('next_of_kin_name', 'Next Of Kin Name:') !!}
    {!! Form::text('next_of_kin_name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Next Of Kin Contact Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('next_of_kin_contact_number', 'Next Of Kin Contact Number:') !!}
    {!! Form::text('next_of_kin_contact_number', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Next Of Kin Contact Email Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('next_of_kin_contact_email_address', 'Next Of Kin Contact Email Address:') !!}
    {!! Form::text('next_of_kin_contact_email_address', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Date Of Birh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_of_birh', 'Date Of Birh:') !!}
    {!! Form::text('date_of_birh', null, ['class' => 'form-control','id'=>'date_of_birh']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_of_birh').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Place Of Birth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('place_of_birth', 'Place Of Birth:') !!}
    {!! Form::text('place_of_birth', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Address Line1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_line1', 'Address Line1:') !!}
    {!! Form::text('address_line1', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Address Line2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address_line2', 'Address Line2:') !!}
    {!! Form::text('address_line2', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Residence Field -->
<div class="form-group col-sm-6">
    {!! Form::label('residence', 'Residence:') !!}
    {!! Form::text('residence', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Record Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('record_id', 'Record Id:') !!}
    {!! Form::number('record_id', null, ['class' => 'form-control']) !!}
</div>