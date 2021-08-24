<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Repositories\CertificateRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

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
        $certificates = $this->certificateRepository->all();

        return view('certificates.index')
            ->with('certificates', $certificates);
    }

    /**
     * Show the form for creating a new Certificate.
     *
     * @return Response
     */
    public function create()
    {
        return view('certificates.create');
    }

    /**
     * Store a newly created Certificate in storage.
     *
     * @param CreateCertificateRequest $request
     *
     * @return Response
     */
    public function store(CreateCertificateRequest $request)
    {
        $input = $request->all();

        $certificate = $this->certificateRepository->create($input);

        Flash::success('Certificate saved successfully.');

        return redirect(route('certificates.index'));
    }

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

        return view('certificates.show')->with('certificate', $certificate);
    }

    /**
     * Show the form for editing the specified Certificate.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            Flash::error('Certificate not found');

            return redirect(route('certificates.index'));
        }

        return view('certificates.edit')->with('certificate', $certificate);
    }

    /**
     * Update the specified Certificate in storage.
     *
     * @param int $id
     * @param UpdateCertificateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCertificateRequest $request)
    {
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            Flash::error('Certificate not found');

            return redirect(route('certificates.index'));
        }

        $certificate = $this->certificateRepository->update($request->all(), $id);

        Flash::success('Certificate updated successfully.');

        return redirect(route('certificates.index'));
    }

    /**
     * Remove the specified Certificate from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            Flash::error('Certificate not found');

            return redirect(route('certificates.index'));
        }

        $this->certificateRepository->delete($id);

        Flash::success('Certificate deleted successfully.');

        return redirect(route('certificates.index'));
    }
}
