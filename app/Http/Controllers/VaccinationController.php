<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVaccinationRequest;
use App\Http\Requests\UpdateVaccinationRequest;
use App\Models\Certificate;
use App\Models\Vaccination;
use App\Repositories\VaccinationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Response;
use Yajra\DataTables\Facades\DataTables;

class VaccinationController extends AppBaseController
{
    /** @var  VaccinationRepository */
    private $vaccinationRepository;

    public function __construct(VaccinationRepository $vaccinationRepo)
    {
        $this->vaccinationRepository = $vaccinationRepo;
    }

    /**
     * Display a listing of the Vaccination.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $vaccinations = $this->vaccinationRepository->paginate(20);

        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            $vaccinations = $this->vaccinationRepository->paginate(20, ['client_id' => Auth::user()->client['id']]);
            return view('vaccinations.index')
                ->with('vaccinations', $vaccinations);
        }

        return view('vaccinations.datatable');
    }

    public function datatable(Request $request)
    {
        // Increase memory limit for large datasets
        ini_set('memory_limit', '256M');

        // Use subqueries to avoid JOIN with GROUP BY issues
        $query = Vaccination::query()
            ->select([
                'vaccinations.id',
                'vaccinations.client_id',
                'vaccinations.vaccine_id',
                'vaccinations.facility_id',
                'vaccinations.date',
                'vaccinations.dose_number',
                'vaccinations.vaccine_batch_number',
                'vaccinations.certificate_id',
                'vaccinations.created_at',
            ]);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('vaccinations.date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('vaccinations.date', '<=', $request->date_to);
        }

        if ($request->filled('facility_id')) {
            $query->where('vaccinations.facility_id', $request->facility_id);
        }

        if ($request->filled('vaccine_id')) {
            $query->where('vaccinations.vaccine_id', $request->vaccine_id);
        }

        if ($request->filled('dose_number')) {
            $query->where('vaccinations.dose_number', $request->dose_number);
        }

        if ($request->filled('certificate_status')) {
            if ($request->certificate_status === 'with_certificate') {
                $query->whereNotNull('vaccinations.certificate_id');
            } elseif ($request->certificate_status === 'without_certificate') {
                $query->whereNull('vaccinations.certificate_id');
            }
        }

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('client_name', function ($vaccination) {
                $client = $vaccination->client;
                if ($client) {
                    return $client->last_name . ' ' . $client->first_name . ' ' . ($client->other_names ?? '');
                }
                return 'N/A';
            })
            ->addColumn('client_nrc', function ($vaccination) {
                return $vaccination->client ? $vaccination->client->nrc : 'N/A';
            })
            ->addColumn('vaccine_name', function ($vaccination) {
                return $vaccination->vaccine ? $vaccination->vaccine->product_name : 'N/A';
            })
            ->addColumn('facility_name', function ($vaccination) {
                return $vaccination->facility ? $vaccination->facility->name : 'N/A';
            })
            ->editColumn('date', function ($vaccination) {
                return $vaccination->date ? $vaccination->date->format('Y-m-d') : 'N/A';
            })
            ->editColumn('certificate_id', function ($vaccination) {
                if ($vaccination->certificate_id) {
                    return '<a href="/certificates/' . $vaccination->certificate_id . '" class="badge badge-success">View Certificate</a>';
                }
                return '<span class="badge badge-secondary">No Certificate</span>';
            })
            ->addColumn('action', function($vaccination){
                $btn = '<a href="/vaccinations/' . $vaccination->id . '" class="btn btn-info btn-sm" title="View"><i class="fas fa-eye"></i></a> ';
                $btn .= '<a href="/vaccinations/' . $vaccination->id . '/edit" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a> ';
                $btn .= '<button type="button" class="btn btn-danger btn-sm delete-vaccination" data-id="' . $vaccination->id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['certificate_id', 'action'])
            ->toJson();
    }

    /**
     * Export vaccinations to CSV
     */
    public function export(Request $request)
    {
        $query = Vaccination::with(['client', 'vaccine', 'facility', 'provider', 'country']);

        // Apply same filters as datatable
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }
        if ($request->filled('vaccine_id')) {
            $query->where('vaccine_id', $request->vaccine_id);
        }
        if ($request->filled('dose_number')) {
            $query->where('dose_number', $request->dose_number);
        }
        if ($request->filled('certificate_status')) {
            if ($request->certificate_status === 'with_certificate') {
                $query->whereNotNull('certificate_id');
            } elseif ($request->certificate_status === 'without_certificate') {
                $query->whereNull('certificate_id');
            }
        }

        $vaccinations = $query->get();

        $filename = 'vaccinations_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($vaccinations) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Client Name',
                'NRC',
                'Passport',
                'Date of Birth',
                'Gender',
                'Vaccination Date',
                'Vaccine',
                'Dose Number',
                'Batch Number',
                'Batch Expiry',
                'Facility',
                'Provider',
                'Country',
                'Certificate ID',
                'Created At'
            ]);

            foreach ($vaccinations as $vaccination) {
                $client = $vaccination->client;
                fputcsv($file, [
                    $vaccination->id,
                    $client ? $client->last_name . ' ' . $client->first_name . ' ' . ($client->other_names ?? '') : 'N/A',
                    $client ? $client->nrc : 'N/A',
                    $client ? $client->passport : 'N/A',
                    $client ? $client->date_of_birth : 'N/A',
                    $client ? $client->gender : 'N/A',
                    $vaccination->date ? $vaccination->date->format('Y-m-d') : 'N/A',
                    $vaccination->vaccine ? $vaccination->vaccine->product_name : 'N/A',
                    $vaccination->dose_number,
                    $vaccination->vaccine_batch_number,
                    $vaccination->vaccine_batch_expiration_date,
                    $vaccination->facility ? $vaccination->facility->name : 'N/A',
                    $vaccination->provider ? $vaccination->provider->name : 'N/A',
                    $vaccination->country ? $vaccination->country->name : 'N/A',
                    $vaccination->certificate_id ?? 'N/A',
                    $vaccination->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new Vaccination.
     *
     * @return Response
     */
    public function create()
    {
        return view('vaccinations.create');
    }

    /**
     * Store a newly created Vaccination in storage.
     *
     * @param CreateVaccinationRequest $request
     *
     * @return Response
     */
    public function store(CreateVaccinationRequest $request)
    {
        $input = $request->all();

        $vaccination = $this->vaccinationRepository->create($input);

        Flash::success('Vaccination saved successfully.');

        return redirect(route('vaccinations.index'));
    }

    /**
     * Display the specified Vaccination.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $vaccination = $this->vaccinationRepository->find($id);

        if (empty($vaccination)) {
            Flash::error('Vaccination not found');

            return redirect(route('vaccinations.index'));
        }

        return view('vaccinations.show')->with('vaccination', $vaccination);
    }

    /**
     * Show the form for editing the specified Vaccination.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $vaccination = $this->vaccinationRepository->find($id);

        if (empty($vaccination)) {
            Flash::error('Vaccination not found');

            return redirect(route('vaccinations.index'));
        }

        return view('vaccinations.edit')->with('vaccination', $vaccination);
    }

    /**
     * Update the specified Vaccination in storage.
     *
     * @param int $id
     * @param UpdateVaccinationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVaccinationRequest $request)
    {
        $vaccination = $this->vaccinationRepository->find($id);

        if (empty($vaccination)) {
            Flash::error('Vaccination not found');

            return redirect(route('vaccinations.index'));
        }

        $vaccination = $this->vaccinationRepository->update($request->all(), $id);

        Flash::success('Vaccination updated successfully.');

        return redirect(route('vaccinations.index'));
    }

    /**
     * Remove the specified Vaccination from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $vaccination = $this->vaccinationRepository->find($id);

        if (empty($vaccination)) {
            Flash::error('Vaccination not found');

            return redirect(route('vaccinations.index'));
        }

        $this->vaccinationRepository->delete($id);

        Flash::success('Vaccination deleted successfully.');

        return redirect(route('vaccinations.index'));
    }
}
