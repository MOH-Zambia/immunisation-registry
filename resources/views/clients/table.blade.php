<div class="table-responsive">
    <table id="clients-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Client UID</th>
                <th>Card Number</th>
                <th>NRC</th>
                <th>Passport Number</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Other Names</th>
                <th>Sex</th>
                <th>Date Of Birth</th>
                <th>Occupation</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Facility</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td><a href="{{ route('clients.show', [$client->id]) }}">{{ $client->client_uid }}</a></td>
                <td>{{ $client->card_number }}</td>
                <td>{{ $client->NRC }}</td>
                <td>{{ $client->passport_number }}</td>
                <td>{{ $client->last_name }}</td>
                <td>{{ $client->first_name }}</td>
                <td>{{ $client->other_names }}</td>
                <td>{{ $client->sex }}</td>
                <td>{{ $client->date_of_birth }}</td>
                <td>{{ $client->occupation }}</td>
                <td>{{ $client->contact_number }}</td>
                <td>{{ $client->contact_email_address }}</td>
                <td>{{ $client->facility->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

