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
use Response;
use Yajra\DataTables\Facades\DataTables;
use PDF;

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
        $certificates = $this->certificateRepository->paginate(50);

        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            $certificates = $this->certificateRepository->paginate(50, ['client_id' => Auth::user()->client['id']]);
            return view('certificates.index')
                ->with('certificates', $certificates);
        }

        return view('certificates.datatable');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $certificates = Certificate::join('clients', 'certificates.client_id', '=', 'clients.id')
                ->select([
                    'certificates.id',
                    'certificates.certificate_uuid',
                    'clients.NRC',
                    'clients.last_name',
                    'clients.first_name',
                    'clients.other_names',
                    'certificates.trusted_vaccine_code',
                    'certificates.created_at',
                    'certificates.updated_at'
                ]);

            return Datatables::of($certificates)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '<a href="/certificates/'.$row->id.'" class="edit btn btn-success btn-sm">View</a>';
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('Y-m-d');
                })
                ->editColumn('updated_at', function ($request) {
                    return $request->updated_at->format('Y-m-d');
                })
                ->rawColumns(['action'])
                ->make(true);
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

        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($certificate->client_id != Auth::user()->client['id']){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        if (empty($certificate)) {
            Flash::error('Certificate not found');

            return redirect(route('certificates.index'));
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($uuid)
    {
        $covid19_certificate = Certificate::where('certificate_uuid', $uuid)->first();

        // set certificate file
        $certificate = 'file://'.base_path().'/public/STAR_moh_gov_zm.crt';

        // set additional information in the signature
        $info = array(
            'Name' => 'Ministry of Health',
            'Location' => 'Ndeke House, Longacres, Lusaka',
            'Reason' => 'COVID 19 Vaccination Certificate',
            'Website' => 'http://www.moh.gov.zm',
        );

        // set document signature
//        PDF::setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

        PDF::SetFont('helvetica', '', 12);
        PDF::SetTitle('COVID 19 Vaccination Certificate');
        PDF::AddPage();

        // print a line of text
        $text = view('certificates.pdf_certificate')->with('certificate', $covid19_certificate);

        // add view content
        PDF::writeHTML($text, true, 0, true, 0);

        // add image for signature
        PDF::Image('tcpdf.png', 180, 60, 15, 15, 'PNG');

        // define active area for signature appearance
        PDF::setSignatureAppearance(180, 60, 15, 15);

        // save pdf file
        PDF::Output(public_path('files/certificates/'.$covid19_certificate->uuid.'pdf'), 'F');

        PDF::reset();

        dd('pdf created');
    }
}
