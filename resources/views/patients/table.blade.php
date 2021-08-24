<div class="table-responsive">
    <table class="table" id="patients-table">
        <thead>
            <tr>
                <th>Patient Id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Other Names</th>
        <th>Sex</th>
        <th>Occupation</th>
        <th>Status</th>
        <th>Contact Number</th>
        <th>Contact Email Address</th>
        <th>Next Of Kin Name</th>
        <th>Next Of Kin Contact Number</th>
        <th>Next Of Kin Contact Email Address</th>
        <th>Date Of Birh</th>
        <th>Place Of Birth</th>
        <th>Address Line1</th>
        <th>Address Line2</th>
        <th>Residence</th>
        <th>Record Id</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($patients as $patient)
            <tr>
                <td>{{ $patient->patient_id }}</td>
            <td>{{ $patient->first_name }}</td>
            <td>{{ $patient->last_name }}</td>
            <td>{{ $patient->other_names }}</td>
            <td>{{ $patient->sex }}</td>
            <td>{{ $patient->occupation }}</td>
            <td>{{ $patient->status }}</td>
            <td>{{ $patient->contact_number }}</td>
            <td>{{ $patient->contact_email_address }}</td>
            <td>{{ $patient->next_of_kin_name }}</td>
            <td>{{ $patient->next_of_kin_contact_number }}</td>
            <td>{{ $patient->next_of_kin_contact_email_address }}</td>
            <td>{{ $patient->date_of_birh }}</td>
            <td>{{ $patient->place_of_birth }}</td>
            <td>{{ $patient->address_line1 }}</td>
            <td>{{ $patient->address_line2 }}</td>
            <td>{{ $patient->residence }}</td>
            <td>{{ $patient->record_id }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['patients.destroy', $patient->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('patients.show', [$patient->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('patients.edit', [$patient->id]) }}" class='btn btn-default btn-xs'>
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
