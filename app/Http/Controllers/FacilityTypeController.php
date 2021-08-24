<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFacilityTypeRequest;
use App\Http\Requests\UpdateFacilityTypeRequest;
use App\Repositories\FacilityTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FacilityTypeController extends AppBaseController
{
    /** @var  FacilityTypeRepository */
    private $facilityTypeRepository;

    public function __construct(FacilityTypeRepository $facilityTypeRepo)
    {
        $this->facilityTypeRepository = $facilityTypeRepo;
    }

    /**
     * Display a listing of the FacilityType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $facilityTypes = $this->facilityTypeRepository->all();

        return view('facility_types.index')
            ->with('facilityTypes', $facilityTypes);
    }

    /**
     * Show the form for creating a new FacilityType.
     *
     * @return Response
     */
    public function create()
    {
        return view('facility_types.create');
    }

    /**
     * Store a newly created FacilityType in storage.
     *
     * @param CreateFacilityTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateFacilityTypeRequest $request)
    {
        $input = $request->all();

        $facilityType = $this->facilityTypeRepository->create($input);

        Flash::success('Facility Type saved successfully.');

        return redirect(route('facilityTypes.index'));
    }

    /**
     * Display the specified FacilityType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $facilityType = $this->facilityTypeRepository->find($id);

        if (empty($facilityType)) {
            Flash::error('Facility Type not found');

            return redirect(route('facilityTypes.index'));
        }

        return view('facility_types.show')->with('facilityType', $facilityType);
    }

    /**
     * Show the form for editing the specified FacilityType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $facilityType = $this->facilityTypeRepository->find($id);

        if (empty($facilityType)) {
            Flash::error('Facility Type not found');

            return redirect(route('facilityTypes.index'));
        }

        return view('facility_types.edit')->with('facilityType', $facilityType);
    }

    /**
     * Update the specified FacilityType in storage.
     *
     * @param int $id
     * @param UpdateFacilityTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFacilityTypeRequest $request)
    {
        $facilityType = $this->facilityTypeRepository->find($id);

        if (empty($facilityType)) {
            Flash::error('Facility Type not found');

            return redirect(route('facilityTypes.index'));
        }

        $facilityType = $this->facilityTypeRepository->update($request->all(), $id);

        Flash::success('Facility Type updated successfully.');

        return redirect(route('facilityTypes.index'));
    }

    /**
     * Remove the specified FacilityType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $facilityType = $this->facilityTypeRepository->find($id);

        if (empty($facilityType)) {
            Flash::error('Facility Type not found');

            return redirect(route('facilityTypes.index'));
        }

        $this->facilityTypeRepository->delete($id);

        Flash::success('Facility Type deleted successfully.');

        return redirect(route('facilityTypes.index'));
    }
}
