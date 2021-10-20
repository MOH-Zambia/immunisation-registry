<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Repositories\FacilityRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class FacilityController extends AppBaseController
{
    /** @var  FacilityRepository */
    private $facilityRepository;

    public function __construct(FacilityRepository $facilityRepo)
    {
        $this->facilityRepository = $facilityRepo;
    }

    /**
     * Display a listing of the Facility.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $facilities = $this->facilityRepository->paginate(20);

        return view('facilities.index')
            ->with('facilities', $facilities);
    }

    /**
     * Show the form for creating a new Facility.
     *
     * @return Response
     */
    public function create()
    {
        return view('facilities.create');
    }

    /**
     * Store a newly created Facility in storage.
     *
     * @param CreateFacilityRequest $request
     *
     * @return Response
     */
    public function store(CreateFacilityRequest $request)
    {
        $input = $request->all();

        $facility = $this->facilityRepository->create($input);

        Flash::success('Facility saved successfully.');

        return redirect(route('facilities.index'));
    }

    /**
     * Display the specified Facility.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $facility = $this->facilityRepository->find($id);

        if (empty($facility)) {
            Flash::error('Facility not found');

            return redirect(route('facilities.index'));
        }

        return view('facilities.show')->with('facility', $facility);
    }

    /**
     * Show the form for editing the specified Facility.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $facility = $this->facilityRepository->find($id);

        if (empty($facility)) {
            Flash::error('Facility not found');

            return redirect(route('facilities.index'));
        }

        return view('facilities.edit')->with('facility', $facility);
    }

    /**
     * Update the specified Facility in storage.
     *
     * @param int $id
     * @param UpdateFacilityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFacilityRequest $request)
    {
        $facility = $this->facilityRepository->find($id);

        if (empty($facility)) {
            Flash::error('Facility not found');

            return redirect(route('facilities.index'));
        }

        $facility = $this->facilityRepository->update($request->all(), $id);

        Flash::success('Facility updated successfully.');

        return redirect(route('facilities.index'));
    }

    /**
     * Remove the specified Facility from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $facility = $this->facilityRepository->find($id);

        if (empty($facility)) {
            Flash::error('Facility not found');

            return redirect(route('facilities.index'));
        }

        $this->facilityRepository->delete($id);

        Flash::success('Facility deleted successfully.');

        return redirect(route('facilities.index'));
    }
}
