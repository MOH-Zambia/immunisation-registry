<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientIDRequest;
use App\Http\Requests\UpdatePatientIDRequest;
use App\Repositories\PatientIDRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PatientIDController extends AppBaseController
{
    /** @var  PatientIDRepository */
    private $patientIDRepository;

    public function __construct(PatientIDRepository $patientIDRepo)
    {
        $this->patientIDRepository = $patientIDRepo;
    }

    /**
     * Display a listing of the PatientID.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $patientIDs = $this->patientIDRepository->all();

        return view('patient_i_ds.index')
            ->with('patientIDs', $patientIDs);
    }

    /**
     * Show the form for creating a new PatientID.
     *
     * @return Response
     */
    public function create()
    {
        return view('patient_i_ds.create');
    }

    /**
     * Store a newly created PatientID in storage.
     *
     * @param CreatePatientIDRequest $request
     *
     * @return Response
     */
    public function store(CreatePatientIDRequest $request)
    {
        $input = $request->all();

        $patientID = $this->patientIDRepository->create($input);

        Flash::success('Patient I D saved successfully.');

        return redirect(route('patientIDs.index'));
    }

    /**
     * Display the specified PatientID.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $patientID = $this->patientIDRepository->find($id);

        if (empty($patientID)) {
            Flash::error('Patient I D not found');

            return redirect(route('patientIDs.index'));
        }

        return view('patient_i_ds.show')->with('patientID', $patientID);
    }

    /**
     * Show the form for editing the specified PatientID.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $patientID = $this->patientIDRepository->find($id);

        if (empty($patientID)) {
            Flash::error('Patient I D not found');

            return redirect(route('patientIDs.index'));
        }

        return view('patient_i_ds.edit')->with('patientID', $patientID);
    }

    /**
     * Update the specified PatientID in storage.
     *
     * @param int $id
     * @param UpdatePatientIDRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePatientIDRequest $request)
    {
        $patientID = $this->patientIDRepository->find($id);

        if (empty($patientID)) {
            Flash::error('Patient I D not found');

            return redirect(route('patientIDs.index'));
        }

        $patientID = $this->patientIDRepository->update($request->all(), $id);

        Flash::success('Patient I D updated successfully.');

        return redirect(route('patientIDs.index'));
    }

    /**
     * Remove the specified PatientID from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $patientID = $this->patientIDRepository->find($id);

        if (empty($patientID)) {
            Flash::error('Patient I D not found');

            return redirect(route('patientIDs.index'));
        }

        $this->patientIDRepository->delete($id);

        Flash::success('Patient I D deleted successfully.');

        return redirect(route('patientIDs.index'));
    }
}
