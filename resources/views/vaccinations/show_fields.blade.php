<!-- Client Information -->
<div class="col-sm-12">
    <h5 class="text-primary"><i class="fas fa-user"></i> Client Information</h5>
    <hr>
</div>

<div class="col-sm-6">
    {!! Form::label('client_name', 'Client Name:') !!}
    <p><strong>{{ $vaccination->client ? $vaccination->client->last_name . ' ' . $vaccination->client->first_name . ' ' . ($vaccination->client->other_names ?? '') : 'N/A' }}</strong></p>
</div>

<div class="col-sm-6">
    {!! Form::label('client_nrc', 'NRC:') !!}
    <p>{{ $vaccination->client ? $vaccination->client->nrc : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('client_passport', 'Passport:') !!}
    <p>{{ $vaccination->client ? $vaccination->client->passport : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('client_dob', 'Date of Birth:') !!}
    <p>{{ $vaccination->client && $vaccination->client->date_of_birth ? $vaccination->client->date_of_birth->format('Y-m-d') : 'N/A' }}</p>
</div>

<!-- Vaccination Information -->
<div class="col-sm-12 mt-3">
    <h5 class="text-primary"><i class="fas fa-syringe"></i> Vaccination Details</h5>
    <hr>
</div>

<div class="col-sm-6">
    {!! Form::label('vaccine', 'Vaccine:') !!}
    <p><strong>{{ $vaccination->vaccine ? $vaccination->vaccine->product_name : 'N/A' }}</strong></p>
</div>

<div class="col-sm-6">
    {!! Form::label('dose_number', 'Dose Number:') !!}
    <p><span class="badge badge-info">{{ $vaccination->dose_number }}</span></p>
</div>

<div class="col-sm-6">
    {!! Form::label('date', 'Vaccination Date:') !!}
    <p>{{ $vaccination->date ? $vaccination->date->format('Y-m-d') : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('date_of_next_dose', 'Date Of Next Dose:') !!}
    <p>{{ $vaccination->date_of_next_dose ? $vaccination->date_of_next_dose->format('Y-m-d') : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('vaccine_batch_number', 'Vaccine Batch Number:') !!}
    <p>{{ $vaccination->vaccine_batch_number ?? 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('vaccine_batch_expiration_date', 'Vaccine Batch Expiration Date:') !!}
    <p>{{ $vaccination->vaccine_batch_expiration_date ? $vaccination->vaccine_batch_expiration_date->format('Y-m-d') : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('type_of_strategy', 'Type Of Strategy:') !!}
    <p>{{ $vaccination->type_of_strategy ?? 'N/A' }}</p>
</div>

<!-- Facility & Provider Information -->
<div class="col-sm-12 mt-3">
    <h5 class="text-primary"><i class="fas fa-hospital"></i> Facility & Provider</h5>
    <hr>
</div>

<div class="col-sm-6">
    {!! Form::label('facility', 'Facility:') !!}
    <p><strong>{{ $vaccination->facility ? $vaccination->facility->name : 'N/A' }}</strong></p>
</div>

<div class="col-sm-6">
    {!! Form::label('provider', 'Provider:') !!}
    <p>{{ $vaccination->provider ? $vaccination->provider->name : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('vaccinating_organization', 'Vaccinating Organization:') !!}
    <p>{{ $vaccination->vaccinating_organization ?? 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('vaccinating_country', 'Vaccinating Country:') !!}
    <p>{{ $vaccination->country ? $vaccination->country->name : 'N/A' }}</p>
</div>

<!-- Certificate Information -->
<div class="col-sm-12 mt-3">
    <h5 class="text-primary"><i class="fas fa-certificate"></i> Certificate</h5>
    <hr>
</div>

<div class="col-sm-6">
    {!! Form::label('certificate_id', 'Certificate:') !!}
    <p>
        @if($vaccination->certificate_id)
            <a href="/certificates/{{ $vaccination->certificate_id }}" class="btn btn-success btn-sm">
                <i class="fas fa-certificate"></i> View Certificate #{{ $vaccination->certificate_id }}
            </a>
        @else
            <span class="badge badge-secondary">No Certificate</span>
        @endif
    </p>
</div>

<!-- System Information -->
<div class="col-sm-12 mt-3">
    <h5 class="text-primary"><i class="fas fa-info-circle"></i> System Information</h5>
    <hr>
</div>

<div class="col-sm-6">
    {!! Form::label('source_id', 'Source ID:') !!}
    <p>{{ $vaccination->source_id ?? 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $vaccination->created_at ? $vaccination->created_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
</div>

<div class="col-sm-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $vaccination->updated_at ? $vaccination->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
</div>

