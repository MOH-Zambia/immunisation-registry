<div class="table-responsive">
    <table class="table" id="facilities-table">
        <thead>
            <tr>
                <th>MFL Facility ID</th>
                <th>DHIS2 UID</th>
                <th>Province</th>
                <th>District</th>
                <th>Name</th>
                <th>Facility Type</th>
                <th>Ownership</th>
                <th>Catchment Population Head Count</th>
                <th>Catchment Population CSO</th>
                <th>Operation Status</th>
                <th>Location</th>
                <th>Location Type</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($facilities as $facility)
            <tr>
                <td>{{ $facility->facility_id }}</td>
                <td>{{ $facility->DHIS2_UID }}</td>
                <td>{{ $facility->district->province['name'] }}</td>
                <td>{{ $facility->district['name'] }}</td>
                <td>{{ $facility->name }}</td>
                <td>{{ $facility->facility_type }}</td>
                <td>{{ $facility->ownership }}</td>
                <td>{{ $facility->catchment_population_head_count }}</td>
                <td>{{ $facility->catchment_population_cso }}</td>
                <td>{{ $facility->operation_status }}</td>
                <td>{{ $facility->location }}</td>
                <td>{{ $facility->location_type }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['facilities.destroy', $facility->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('facilities.show', [$facility->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('facilities.edit', [$facility->id]) }}" class='btn btn-default btn-xs'>
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
