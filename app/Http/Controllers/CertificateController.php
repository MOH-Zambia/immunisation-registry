<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Models\Certificate;
use App\Models\User;
use App\Repositories\CertificateRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Response;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Symfony\Component\Console\Output\ConsoleOutput;

class CertificateController extends AppBaseController
{
    /** @var  CertificateRepository */
    private $certificateRepository;

    public function __construct(CertificateRepository $certificateRepo)
    {
        $this->certificateRepository = $certificateRepo;
    }

    /**
     * Display a listing of the Certificate.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('certificates.datatable');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $query = Certificate::join('clients', 'certificates.client_id', '=', 'clients.id')
                ->select([
                    'certificates.id',
                    'certificates.certificate_uuid',
                    'clients.NRC',
                    'clients.passport_number',
                    'clients.drivers_license',
                    'clients.last_name',
                    'clients.first_name',
                    'clients.other_names',
                    'clients.contact_number',
                    'clients.contact_email_address',
                    'certificates.trusted_vaccine_code',
                    'certificates.created_at',
                    'certificates.updated_at'
                ]);

            // Apply date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('certificates.created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            // Apply trusted vaccine code filter
            if ($request->filled('trusted_filter')) {
                if ($request->trusted_filter === 'exported') {
                    $query->whereNotNull('certificates.trusted_vaccine_code');
                } elseif ($request->trusted_filter === 'pending') {
                    $query->whereNull('certificates.trusted_vaccine_code');
                }
            }

            $query->orderBy('certificates.id', 'DESC');

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $viewBtn = '<a href="/certificates/'.$row->id.'" class="btn btn-success btn-sm" title="View Details"><i class="fas fa-eye"></i></a>';
                    $downloadBtn = '<a href="/certificates/'.$row->id.'/pdf" class="btn btn-primary btn-sm ml-1" title="Download PDF"><i class="fas fa-download"></i></a>';
                    $copyBtn = '<button class="btn btn-info btn-sm ml-1 copy-uuid" data-uuid="'.$row->certificate_uuid.'" title="Copy Certificate URL"><i class="fas fa-copy"></i></button>';
                    return $viewBtn . $downloadBtn . $copyBtn;
                })
                ->addColumn('client_name', function($row) {
                    return trim($row->first_name . ' ' . ($row->other_names ?? '') . ' ' . $row->last_name);
                })
                ->addColumn('identification', function($row) {
                    if ($row->NRC) return 'NRC: ' . $row->NRC;
                    if ($row->passport_number) return 'Passport: ' . $row->passport_number;
                    if ($row->drivers_license) return 'DL: ' . $row->drivers_license;
                    return 'N/A';
                })
                ->addColumn('trusted_status', function($row) {
                    if ($row->trusted_vaccine_code) {
                        return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Exported</span>';
                    }
                    return '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->filterColumn('client_name', function($query, $keyword) {
                    $query->whereRaw("CONCAT(clients.first_name, ' ', COALESCE(clients.other_names, ''), ' ', clients.last_name) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('identification', function($query, $keyword) {
                    $query->where(function($q) use ($keyword) {
                        $q->where('clients.NRC', 'like', "%{$keyword}%")
                          ->orWhere('clients.passport_number', 'like', "%{$keyword}%")
                          ->orWhere('clients.drivers_license', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['action', 'trusted_status'])
                ->toJson();
        }
    }

    /**
     * Show the form for creating a new Certificate.
     *
     * @return Response
     */
//    public function create()
//    {
//        return view('certificates.create');
//    }

    /**
     * Store a newly created Certificate in storage.
     *
     * @param CreateCertificateRequest $request
     *
     * @return Response
     */
//    public function store(CreateCertificateRequest $request)
//    {
//        $input = $request->all();
//
//        $certificate = $this->certificateRepository->create($input);
//
//        Flash::success('Certificate saved successfully.');
//
//        return redirect(route('certificates.index'));
//    }

    /**
     * Display the specified Certificate.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            Flash::error('Certificate not found');
            return redirect(route('certificates.index'));
        }

        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($certificate->client_id != Auth::user()->client['id']){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        return view('certificates.show')->with('certificate', $certificate);
    }

    /**
     * Display the specified Certificate.
     *
     * @param int $id
     *
     * @return Response
     */
    public function view($uuid)
    {
        $certificate = Certificate::where('certificate_uuid', $uuid)->first();

        if (empty($certificate)) {
            Flash::error('Certificate not found');

//            return redirect(route('certificates.index'));
            return abort(404, 'Certificate not found!');
        }

        return view('certificates.certificate')->with('certificate', $certificate);
    }

    /**
     * Show the form for editing the specified Certificate.
     *
     * @param int $id
     *
     * @return Response
     */
//    public function edit($id)
//    {
//        $certificate = $this->certificateRepository->find($id);
//
//        if (empty($certificate)) {
//            Flash::error('Certificate not found');
//
//            return redirect(route('certificates.index'));
//        }
//
//        return view('certificates.edit')->with('certificate', $certificate);
//    }

    /**
     * Update the specified Certificate in storage.
     *
     * @param int $id
     * @param UpdateCertificateRequest $request
     *
     * @return Response
     */
//    public function update($id, UpdateCertificateRequest $request)
//    {
//        $certificate = $this->certificateRepository->find($id);
//
//        if (empty($certificate)) {
//            Flash::error('Certificate not found');
//
//            return redirect(route('certificates.index'));
//        }
//
//        $certificate = $this->certificateRepository->update($request->all(), $id);
//
//        Flash::success('Certificate updated successfully.');
//
//        return redirect(route('certificates.index'));
//    }

    /**
     * Remove the specified Certificate from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
//    public function destroy($id)
//    {
//        $certificate = $this->certificateRepository->find($id);
//
//        if (empty($certificate)) {
//            Flash::error('Certificate not found');
//
//            return redirect(route('certificates.index'));
//        }
//
//        $this->certificateRepository->delete($id);
//
//        Flash::success('Certificate deleted successfully.');
//
//        return redirect(route('certificates.index'));
//    }

    public function createDirectoryIfNonExistence($path)
    {
        $out = new ConsoleOutput();
        if (!(file_exists($path) && is_dir($path))) {
            $directory = mkdir($path);
            $out->writeln("Successfully Created Directory at : '$path'");
        } else {
    	    $out->writeln("Directory at : '$path', already Exists!");
        }
    }

    /**
     * Verify a certificate by UUID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCertificate(Request $request)
    {
        // Generate unique request ID for tracking
        $requestId = 'VERIFY-' . uniqid();

        try {
            // Validate input
            $validated = $request->validate([
                'certificate_uuid' => 'required|string|max:255',
            ]);

            $uuid = trim($validated['certificate_uuid']);

            Log::channel('sms')->info("[$requestId] Certificate verification request", [
                'uuid' => $uuid,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Search for certificate by UUID
            $certificate = Certificate::where('certificate_uuid', $uuid)
                ->with(['client' => function($query) {
                    $query->select('id', 'first_name', 'last_name', 'other_names', 'date_of_birth', 'NRC', 'passport_number');
                }])
                ->first();

            if (empty($certificate)) {
                Log::channel('sms')->warning("[$requestId] Certificate not found", [
                    'uuid' => $uuid
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found. Please verify the UUID and try again.',
                ], 404);
            }

            // Check if certificate has expired
            $certificateStatus = 'Valid';
            if ($certificate->certificate_expiration_date) {
                $expirationDate = \Carbon\Carbon::parse($certificate->certificate_expiration_date);
                if ($expirationDate->isPast()) {
                    $certificateStatus = 'Expired';
                }
            }

            // Update certificate status if needed
            if ($certificateStatus !== $certificate->certificate_status) {
                $certificate->certificate_status = $certificateStatus;
                $certificate->save();
            }

            Log::channel('sms')->info("[$requestId] Certificate verified successfully", [
                'uuid' => $uuid,
                'client_id' => $certificate->client_id,
                'status' => $certificateStatus,
                'holder_name' => $certificate->client->first_name . ' ' . $certificate->client->last_name,
            ]);

            // Prepare response data
            $responseData = [
                'certificate_uuid' => $certificate->certificate_uuid,
                'certificate_status' => $certificateStatus,
                'target_disease' => $certificate->target_disease,
                'trusted_vaccine_code' => $certificate->trusted_vaccine_code,
                'certificate_expiration_date' => $certificate->certificate_expiration_date,
                'certificate_url' => $certificate->certificate_url,
                'qr_code' => $certificate->qr_code,
                'created_at' => $certificate->created_at,
                'client' => [
                    'first_name' => $certificate->client->first_name,
                    'last_name' => $certificate->client->last_name,
                    'other_names' => $certificate->client->other_names,
                    'date_of_birth' => $certificate->client->date_of_birth,
                    // Mask sensitive information for security
                    'NRC' => $certificate->client->NRC ? substr($certificate->client->NRC, 0, 3) . '***' . substr($certificate->client->NRC, -2) : null,
                    'passport_number' => $certificate->client->passport_number ? substr($certificate->client->passport_number, 0, 2) . '***' . substr($certificate->client->passport_number, -2) : null,
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Certificate verified successfully',
                'data' => $responseData,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('sms')->error("[$requestId] Validation failed", [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid input: ' . implode(', ', $e->validator->errors()->all()),
            ], 422);

        } catch (\Exception $e) {
            Log::channel('sms')->error("[$requestId] Certificate verification error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during verification. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($uuid)
    {
        try {
            $out = new ConsoleOutput();

            self::createDirectoryIfNonExistence(public_path('files'));
            self::createDirectoryIfNonExistence(public_path('files/certificates'));

            // get certificate based on uuid supplied
            $covid19_certificate = Certificate::where('certificate_uuid', $uuid)->first();

            if (empty($covid19_certificate)) {
                $out->writeln('Certificate of supplied UUID NOT FOUND');
                abort(404, 'Certificate not found');
            }

            $filename = 'files/certificates/'. $covid19_certificate->certificate_uuid . '.pdf';

            // load certificate public and private keys
            $certificate = 'file://'.base_path().'/public/STAR_moh_gov_zm.crt';

            // set additional information in the signature
            $info = array(
                'Name' => 'Ministry of Health',
                'Location' => 'Ndeke House, Longacres, Lusaka',
                'Reason' => 'COVID 19 Vaccination Certificate',
                'Website' => 'http://www.moh.gov.zm',
            );

            // set document signature
            PDF::setSignature($certificate, $certificate, 'm0h1ct11', '', 2, $info);

            PDF::SetFont('helvetica', '', 12);
            PDF::SetTitle('COVID 19 Vaccination Certificate');
            PDF::AddPage();

            // print certificate content
            $certificate_content = view('certificates.pdf_certificate')->with('certificate', $covid19_certificate);

            // write certificate content(HTML) to PDF
            PDF::writeHTML($certificate_content, true, 0, true, 0);

            // add image for signature
            PDF::Image('tcpdf.png', 180, 60, 15, 15, 'PNG');

            // define active area for signature appearance
            PDF::setSignatureAppearance(180, 60, 15, 15);

            // save certificate file to system
            PDF::Output(public_path($filename), 'F');

            PDF::reset();

            $out->writeln("Done!");

            return response()->download(public_path($filename));

        } catch (\Exception $e) {
            Log::error('Error generating PDF certificate: ' . $e->getMessage());
            abort(500, 'Failed to generate certificate');
        }
    }
}
