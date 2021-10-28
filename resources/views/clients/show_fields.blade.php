<div class="col-md-6">
    <!-- Client UID Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('client_uid', 'Client UID:') !!} {{ $client->client_uid }}</p>
    </div>

    <!-- Card Number Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('card_number', 'Card Number:') !!} {{ $client->card_number }}</p>
    </div>

    <!-- Nrc Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('NRC', 'NRC:') !!} {{ $client->NRC }}</p>
    </div>

    <!-- Passport Number Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('passport_number', 'Passport Number:') !!} {{ $client->passport_number }}</p>
    </div>

    <!-- First Name Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('first_name', 'First Name:') !!} {{ $client->first_name }}</p>
    </div>

    <!-- Last Name Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('last_name', 'Last Name:') !!} {{ $client->last_name }}</p>
    </div>

    <!-- Other Names Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('other_names', 'Other Names:') !!} {{ $client->other_names }}</p>
    </div>

    <!-- Sex Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('sex', 'Sex:') !!} {{ $client->sex }}</p>
    </div>

    <!-- Date Of Birth Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('date_of_birth', 'Date Of Birth:') !!} {{ $client->date_of_birth }}</p>
    </div>

    <!-- Place Of Birth Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('place_of_birth', 'Place Of Birth:') !!} {{ $client->place_of_birth }}</p>
    </div>

    <!-- Occupation Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('occupation', 'Occupation:') !!} {{ $client->occupation }}</p>
    </div>

    <!-- Status Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('status', 'Status:') !!} {{ $client->status }}</p>
    </div>
</div>

<div class="col-md-6">
    <!-- Contact Number Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('contact_number', 'Contact Number:') !!} {{ $client->contact_number }}</p>
    </div>

    <!-- Contact Email Address Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('contact_email_address', 'Contact Email Address:') !!} {{ $client->contact_email_address }}</p>
    </div>

    <!-- Address Line1 Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('address_line1', 'Address Line1:') !!} {{ $client->address_line1 }}</p>
    </div>

    <!-- Address Line2 Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('address_line2', 'Address Line2:') !!} {{ $client->address_line2 }}</p>
    </div>

    <!-- Next Of Kin Name Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('next_of_kin_name', 'Next Of Kin Name:') !!} {{ $client->next_of_kin_name }}</p>
    </div>

    <!-- Next Of Kin Contact Number Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('next_of_kin_contact_number', 'Next Of Kin Contact Number:') !!} {{ $client->next_of_kin_contact_number }}</p>
    </div>

    <!-- Next Of Kin Contact Email Address Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('next_of_kin_contact_email_address', 'Next Of Kin Contact Email Address:') !!} {{ $client->next_of_kin_contact_email_address }}</p>
    </div>

    <!-- Facility Id Field -->
    <div class="col-sm-12">
        <p>{!! Form::label('facility_id', 'Facility:') !!} {{ $client->facility->name }}</p>
    </div>
</div>

