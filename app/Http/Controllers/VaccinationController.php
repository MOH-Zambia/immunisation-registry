<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVaccinationRequest;
use App\Http\Requests\UpdateVaccinationRequest;
use App\Repositories\VaccinationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

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

        return view('vaccinations.index')
            ->with('vaccinations', $vaccinations);
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
