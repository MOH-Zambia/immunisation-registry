<div class="table-responsive">
    <table id="clients-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Card Number</th>
                <th>NRC</th>
                <th>Passport Number</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Other Names</th>
                <th>Sex</th>
                <th>Date Of Birth</th>
                <th>Occupation</th>
                <th>Status</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Address</th>
                <th>Facility</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td>{{ $client->client_uid }}</td>
                <td>{{ $client->card_number }}</td>
                <td>{{ $client->NRC }}</td>
                <td>{{ $client->passport_number }}</td>
                <td>{{ $client->last_name }}</td>
                <td>{{ $client->first_name }}</td>
                <td>{{ $client->other_names }}</td>
                <td>{{ $client->sex }}</td>
                <td>{{ $client->date_of_birth }}</td>
                <td>{{ $client->occupation }}</td>
                <td>{{ $client->status }}</td>
                <td>{{ $client->contact_number }}</td>
                <td>{{ $client->contact_email_address }}</td>
                <td>{{ $client->address_line1 }} {{ $client->address_line2 }}</td>
                <td>{{ $client->facility->name }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['clients.destroy', $client->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('clients.show', [$client->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('clients.edit', [$client->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

