<div class="table-responsive">
    <table class="table" id="vaccinations-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Date</th>
                <th>Vaccine</th>
                <th>Dose Number</th>
                <th>Vaccine Batch Number</th>
                <th>Facility</th>
                <th>Certificate ID</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vaccinations as $vaccinations)
            <tr>
                <td><a href="{{ route('vaccinations.show', [$vaccinations->id]) }}">{{ $vaccinations->id }}</a></td>
                <td><a href="{{ route('clients.show', [$vaccinations->client['id']]) }}">{{ $vaccinations->client['last_name'] }}</a></td>
                <td><a href="{{ route('clients.show', [$vaccinations->client['id']]) }}">{{ $vaccinations->client['first_name'] }}</a></td>
                <td>{{ $vaccinations->date }}</td>
                <td>{{ $vaccinations->vaccine->product_name }}</td>
                <td>{{ $vaccinations->dose_number }}</td>
                <td>{{ $vaccinations->vaccine_batch_number }}</td>
                <td>{{ $vaccinations->facility->name }}</td>
                <td>{{ $vaccinations->certificate_id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
