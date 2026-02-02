<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Certificate;
use App\Repositories\ClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;

use Response;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends AppBaseController
{
    /** @var  ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('clients.datatable');
    }

    public function datatable(Request $request)
    {
        // Increase memory limit for this request
        ini_set('memory_limit', '256M');

        $query = Client::select([
            'clients.id',
            'clients.source_id',
            'clients.card_number',
            'clients.NRC',
            'clients.passport_number',
            'clients.last_name',
            'clients.first_name',
            'clients.other_names',
            'clients.sex',
            'clients.contact_number',
            'clients.contact_email_address',
            'clients.date_of_birth',
            'clients.created_at'
        ])
        ->selectSub(function($query) {
            $query->selectRaw('MAX(id)')
                ->from('certificates')
                ->whereColumn('certificates.client_id', 'clients.id');
        }, 'certificate_id')
        ->selectSub(function($query) {
            $query->selectRaw('MAX(certificate_number)')
                ->from('certificates')
                ->whereColumn('certificates.client_id', 'clients.id');
        }, 'certificate_number')
        ->selectSub(function($query) {
            $query->selectRaw('MAX(export_status)')
                ->from('certificates')
                ->whereColumn('certificates.client_id', 'clients.id');
        }, 'certificate_export_status')
        ->selectSub(function($query) {
            $query->selectRaw('COUNT(DISTINCT id)')
                ->from('vaccinations')
                ->whereColumn('vaccinations.client_id', 'clients.id');
        }, 'vaccination_count');

        // Date range filter
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('clients.created_at', '>=', $request->start_date . ' 00:00:00');
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('clients.created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Certificate status filter
        if ($request->has('certificate_status') && $request->certificate_status != '') {
            if ($request->certificate_status == 'has_certificate') {
                $query->whereHas('certificates');
            } elseif ($request->certificate_status == 'no_certificate') {
                $query->whereDoesntHave('certificates');
            } elseif ($request->certificate_status == 'exported') {
                $query->whereHas('certificates', function($q) {
                    $q->where('export_status', 1);
                });
            } elseif ($request->certificate_status == 'not_exported') {
                $query->whereHas('certificates', function($q) {
                    $q->where('export_status', 0);
                });
            }
        }

        // Gender filter
        if ($request->has('gender') && $request->gender != '') {
            $query->where('clients.sex', $request->gender);
        }

        // Vaccination status filter - use whereHas instead of havingRaw
        if ($request->has('vaccination_status') && $request->vaccination_status != '') {
            if ($request->vaccination_status == 'fully_vaccinated') {
                $query->whereHas('vaccinations', function($q) {
                    // We need to count in PHP side or use a subquery
                }, '>=', 2);
            } elseif ($request->vaccination_status == 'partially_vaccinated') {
                $query->whereHas('vaccinations', function($q) {
                    // Count = 1
                }, '=', 1);
            } elseif ($request->vaccination_status == 'not_vaccinated') {
                $query->whereDoesntHave('vaccinations');
            }
        }

        $query->orderBy('clients.id', 'DESC');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('full_name', function($row) {
                return trim($row->first_name . ' ' . $row->other_names . ' ' . $row->last_name);
            })
            ->addColumn('age', function($row) {
                if ($row->date_of_birth) {
                    return \Carbon\Carbon::parse($row->date_of_birth)->age;
                }
                return 'N/A';
            })
            ->addColumn('certificate_status', function($row) {
                if ($row->certificate_id) {
                    if ($row->certificate_export_status == 1) {
                        return '<span class="badge badge-success">Certificate Exported</span>';
                    } else {
                        return '<span class="badge badge-info">Certificate Available</span>';
                    }
                }
                return '<span class="badge badge-warning">No Certificate</span>';
            })
            ->addColumn('vaccination_status', function($row) {
                if ($row->vaccination_count >= 2) {
                    return '<span class="badge badge-success">Fully Vaccinated (' . $row->vaccination_count . ')</span>';
                } elseif ($row->vaccination_count == 1) {
                    return '<span class="badge badge-warning">Partially Vaccinated (1)</span>';
                }
                return '<span class="badge badge-secondary">Not Vaccinated</span>';
            })
            ->addColumn('action', function($row) {
                $buttons = '<a class="btn btn-sm btn-info mr-1" href="'.route('clients.show', [$row->id]).'">
                    <i class="fas fa-eye"></i> View
                </a>';

                if ($row->certificate_id) {
                    $buttons .= '<a class="btn btn-sm btn-success mr-1" href="'.route('certificates.show', [$row->certificate_id]).'" target="_blank">
                        <i class="fas fa-certificate"></i> View Certificate
                    </a>';
                } else {
                    $buttons .= '<button class="btn btn-sm btn-primary generate-certificate" data-client-id="'.$row->id.'">
                        <i class="fas fa-plus"></i> Generate Certificate
                    </button>';
                }

                return $buttons;
            })
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(clients.first_name, ' ', COALESCE(clients.other_names, ''), ' ', clients.last_name) like ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['certificate_status', 'vaccination_status', 'action'])
            ->toJson();
    }

    /**
     * Generate certificate for a specific client
     *
     * @param Request $request
     * @return Response
     */
    public function generateCertificateForClient(Request $request)
    {
        try {
            $clientId = $request->input('client_id');

            // Verify client exists
            $client = Client::find($clientId);
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            // Check if client already has a certificate
            $existingCertificate = Certificate::where('client_id', $clientId)->first();
            if ($existingCertificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client already has a certificate',
                    'certificate_id' => $existingCertificate->id
                ], 400);
            }

            // Check if client has vaccinations
            $vaccinationCount = $client->vaccinations()->count();
            if ($vaccinationCount < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client must have at least one vaccination record to generate a certificate'
                ], 400);
            }

            // Log the certificate generation attempt
            Log::info('Generating certificate for individual client', [
                'client_id' => $clientId,
                'user_id' => Auth::id(),
                'vaccination_count' => $vaccinationCount
            ]);

            // Call the Artisan command to generate certificate for this specific client
            Artisan::call('generate:certificates', [
                '--client' => $clientId
            ]);

            // Verify certificate was created
            $certificate = Certificate::where('client_id', $clientId)->first();

            if ($certificate) {
                Log::info('Certificate generated successfully', [
                    'client_id' => $clientId,
                    'certificate_id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Certificate generated successfully',
                    'certificate_id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number
                ]);
            } else {
                Log::error('Certificate generation failed - certificate not created', [
                    'client_id' => $clientId
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate certificate. Please check vaccination records and try again.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Certificate generation error', [
                'client_id' => $request->input('client_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param CreateClientRequest $request
     *
     * @return Response
     */
//    public function store(CreateClientRequest $request)
//    {
//        $input = $request->all();
//
//        $client = $this->clientRepository->create($input);
//
//        Flash::success('Client saved successfully.');
//
//        return redirect(route('clients.index'));
//    }

    /**
     * Store a newly created Client in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        $verifyId = uniqid('CLIENT-VERIFY-', true);

        Log::info("[$verifyId] Client verification request initiated", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'nrc' => 'nullable|string|max:50',
            'passport' => 'nullable|string|max:50',
            'drivers_license' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $input = $request->all();
        $client = null;
        $searchMethod = 'none';

        if (array_key_exists('nrc', $input) && !empty($input['nrc'])) {
            $searchMethod = 'NRC';
            Log::info("[$verifyId] Searching by NRC: " . substr($input['nrc'], -4));
            $client = Client::where('NRC', $input['nrc'])->first();
        }

        if (!$client && array_key_exists('passport', $input) && !empty($input['passport'])) {
            $searchMethod = 'Passport';
            Log::info("[$verifyId] Searching by Passport: " . substr($input['passport'], -4));
            $client = Client::where('passport_number', $input['passport'])->first();
        }

        if (!$client && array_key_exists('drivers_license', $input) && !empty($input['drivers_license'])) {
            $searchMethod = 'Drivers License';
            Log::info("[$verifyId] Searching by Drivers License: " . substr($input['drivers_license'], -4));
            $client = Client::where('drivers_license', $input['drivers_license'])->first();
        }

        if (!$client && array_key_exists('email', $input) && !empty($input['email'])) {
            $searchMethod = 'Email';
            $maskedEmail = substr($input['email'], 0, 3) . '***@' . explode('@', $input['email'])[1];
            Log::info("[$verifyId] Searching by Email: $maskedEmail");
            $client = Client::where('contact_email_address', $input['email'])->first();
        }

        if(empty($client)){
            Log::warning("[$verifyId] Client not found", [
                'search_method' => $searchMethod,
                'has_nrc' => array_key_exists('nrc', $input) && !empty($input['nrc']),
                'has_passport' => array_key_exists('passport', $input) && !empty($input['passport']),
                'has_license' => array_key_exists('drivers_license', $input) && !empty($input['drivers_license']),
                'has_email' => array_key_exists('email', $input) && !empty($input['email'])
            ]);
            return $this->sendError('Client not found');
        } else {
            Log::info("[$verifyId] Client found successfully", [
                'search_method' => $searchMethod,
                'client_id' => $client->id,
                'has_contact_number' => !empty($client->contact_number),
                'has_email' => !empty($client->contact_email_address)
            ]);
            return $this->sendSuccess($client->toJson());
        }
    }

    /**
     * Display the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($id != Auth::user()->client['id']){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        return view('clients.show')->with('client', $client);
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
//    public function edit($id)
//    {
//        $client = $this->clientRepository->find($id);
//
//        if (empty($client)) {
//            Flash::error('Client not found');
//
//            return redirect(route('clients.index'));
//        }
//
//        return view('clients.edit')->with('client', $client);
//    }

    /**
     * Update the specified Client in storage.
     *
     * @param int $id
     * @param UpdateClientRequest $request
     *
     * @return Response
     */
//    public function update($id, UpdateClientRequest $request)
//    {
//        $client = $this->clientRepository->find($id);
//
//        if (empty($client)) {
//            Flash::error('Client not found');
//
//            return redirect(route('clients.index'));
//        }
//
//        $client = $this->clientRepository->update($request->all(), $id);
//
//        Flash::success('Client updated successfully.');
//
//        return redirect(route('clients.index'));
//    }

    /**
     * Remove the specified Client from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
//    public function destroy($id)
//    {
//        $client = $this->clientRepository->find($id);
//
//        if (empty($client)) {
//            Flash::error('Client not found');
//
//            return redirect(route('clients.index'));
//        }
//
//        $this->clientRepository->delete($id);
//
//        Flash::success('Client deleted successfully.');
//
//        return redirect(route('clients.index'));
//    }
}
