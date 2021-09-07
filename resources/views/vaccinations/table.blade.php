<div class="table-responsive">
    <table class="table" id="vaccinations-table">
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Vaccine</th>
                <th>Date</th>
                <th>Dose Number</th>
                <th>Date Of Next Dose</th>
                <th>Vaccine Batch Number</th>
                <th>Vaccine Batch Expiration Date</th>
                <th>Vaccinating Organization ID</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vaccinations as $vaccination)
            <tr>
                <td>{{ $vaccination->client['client_id'] }}</td>
                <td>{{ $vaccination->vaccine['product_name'] }}</td>
                <td>{{ $vaccination->date }}</td>
                <td>{{ $vaccination->dose_number }}</td>
                <td>{{ $vaccination->date_of_next_dose }}</td>
                <td>{{ $vaccination->vaccine_batch_number }}</td>
                <td>{{ $vaccination->vaccine_batch_expiration_date }}</td>
                <td>{{ $vaccination->vaccinating_organization_id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
