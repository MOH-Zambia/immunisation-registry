<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientIDTypeRequest;
use App\Http\Requests\UpdatePatientIDTypeRequest;
use App\Repositories\PatientIDTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PatientIDTypeController extends AppBaseController
{
    /** @var  PatientIDTypeRepository */
    private $patientIDTypeRepository;

    public function __construct(PatientIDTypeRepository $patientIDTypeRepo)
    {
        $this->patientIDTypeRepository = $patientIDTypeRepo;
    }

    /**
     * Display a listing of the PatientIDType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $patientIDTypes = $this->patientIDTypeRepository->all();

        return view('patient_i_d_types.index')
            ->with('patientIDTypes', $patientIDTypes);
    }

    /**
     * Show the form for creating a new PatientIDType.
     *
     * @return Response
     */
    public function create()
    {
        return view('patient_i_d_types.create');
    }

    /**
     * Store a newly created PatientIDType in storage.
     *
     * @param CreatePatientIDTypeRequest $request
     *
     * @return Response
     */
    public function store(CreatePatientIDTypeRequest $request)
    {
        $input = $request->all();

        $patientIDType = $this->patientIDTypeRepository->create($input);

        Flash::success('Patient I D Type saved successfully.');

        return redirect(route('patientIDTypes.index'));
    }

    /**
     * Display the specified PatientIDType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $patientIDType = $this->patientIDTypeRepository->find($id);

        if (empty($patientIDType)) {
            Flash::error('Patient I D Type not found');

            return redirect(route('patientIDTypes.index'));
        }

        return view('patient_i_d_types.show')->with('patientIDType', $patientIDType);
    }

    /**
     * Show the form for editing the specified PatientIDType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $patientIDType = $this->patientIDTypeRepository->find($id);

        if (empty($patientIDType)) {
            Flash::error('Patient I D Type not found');

            return redirect(route('patientIDTypes.index'));
        }

        return view('patient_i_d_types.edit')->with('patientIDType', $patientIDType);
    }

    /**
     * Update the specified PatientIDType in storage.
     *
     * @param int $id
     * @param UpdatePatientIDTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePatientIDTypeRequest $request)
    {
        $patientIDType = $this->patientIDTypeRepository->find($id);

        if (empty($patientIDType)) {
            Flash::error('Patient I D Type not found');

            return redirect(route('patientIDTypes.index'));
        }

        $patientIDType = $this->patientIDTypeRepository->update($request->all(), $id);

        Flash::success('Patient I D Type updated successfully.');

        return redirect(route('patientIDTypes.index'));
    }

    /**
     * Remove the specified PatientIDType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $patientIDType = $this->patientIDTypeRepository->find($id);

        if (empty($patientIDType)) {
            Flash::error('Patient I D Type not found');

            return redirect(route('patientIDTypes.index'));
        }

        $this->patientIDTypeRepository->delete($id);

        Flash::success('Patient I D Type deleted successfully.');

        return redirect(route('patientIDTypes.index'));
    }
}
