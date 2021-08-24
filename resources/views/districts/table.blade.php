<div class="table-responsive">
    <table class="table" id="districts-table">
        <thead>
            <tr>
                <th>Province Id</th>
        <th>Name</th>
        <th>District Type</th>
        <th>Population</th>
        <th>Pop Density</th>
        <th>Area Sq Km</th>
        <th>Geometry</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($districts as $district)
            <tr>
                <td>{{ $district->province_id }}</td>
            <td>{{ $district->name }}</td>
            <td>{{ $district->district_type }}</td>
            <td>{{ $district->population }}</td>
            <td>{{ $district->pop_density }}</td>
            <td>{{ $district->area_sq_km }}</td>
            <td>{{ $district->geometry }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['districts.destroy', $district->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('districts.show', [$district->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('districts.edit', [$district->id]) }}" class='btn btn-default btn-xs'>
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
