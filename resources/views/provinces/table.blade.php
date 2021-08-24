<div class="table-responsive">
    <table class="table" id="provinces-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Population</th>
        <th>Pop Density</th>
        <th>Area Sq Km</th>
        <th>Geometry</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($provinces as $province)
            <tr>
                <td>{{ $province->name }}</td>
            <td>{{ $province->population }}</td>
            <td>{{ $province->pop_density }}</td>
            <td>{{ $province->area_sq_km }}</td>
            <td>{{ $province->geometry }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['provinces.destroy', $province->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('provinces.show', [$province->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('provinces.edit', [$province->id]) }}" class='btn btn-default btn-xs'>
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
