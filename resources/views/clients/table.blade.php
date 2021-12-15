<div class="table-responsive">
    <table id="clients-table" class="table">
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
                <th>Phone Number</th>
                <th>Email Address</th>
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
                <td>{{ $client->contact_number }}</td>
                <td>{{ $client->contact_email_address }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

