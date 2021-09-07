<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVaccinationsRequest;
use App\Http\Requests\UpdateVaccinationsRequest;
use App\Repositories\VaccinationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class VaccinationsController extends AppBaseController
{
    /** @var  VaccinationsRepository */
    private $vaccinationsRepository;

    public function __construct(VaccinationsRepository $vaccinationsRepo)
    {
        $this->vaccinationsRepository = $vaccinationsRepo;
    }

    /**
     * Display a listing of the Vaccinations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $vaccinations = $this->vaccinationsRepository->paginate(20);

        return view('vaccinations.index')
            ->with('vaccinations', $vaccinations);
    }

    /**
     * Show the form for creating a new Vaccinations.
     *
     * @return Response
     */
    public function create()
    {
        return view('vaccinations.create');
    }

    /**
     * Store a newly created Vaccinations in storage.
     *
     * @param CreateVaccinationsRequest $request
     *
     * @return Response
     */
    public function store(CreateVaccinationsRequest $request)
    {
        $input = $request->all();

        $vaccinations = $this->vaccinationsRepository->create($input);

        Flash::success('Vaccinations saved successfully.');

        return redirect(route('vaccinations.index'));
    }

    /**
     * Display the specified Vaccinations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $vaccinations = $this->vaccinationsRepository->find($id);

        if (empty($vaccinations)) {
            Flash::error('Vaccinations not found');

            return redirect(route('vaccinations.index'));
        }

        return view('vaccinations.show')->with('vaccinations', $vaccinations);
    }

    /**
     * Show the form for editing the specified Vaccinations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $vaccinations = $this->vaccinationsRepository->find($id);

        if (empty($vaccinations)) {
            Flash::error('Vaccinations not found');

            return redirect(route('vaccinations.index'));
        }

        return view('vaccinations.edit')->with('vaccinations', $vaccinations);
    }

    /**
     * Update the specified Vaccinations in storage.
     *
     * @param int $id
     * @param UpdateVaccinationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVaccinationsRequest $request)
    {
        $vaccinations = $this->vaccinationsRepository->find($id);

        if (empty($vaccinations)) {
            Flash::error('Vaccinations not found');

            return redirect(route('vaccinations.index'));
        }

        $vaccinations = $this->vaccinationsRepository->update($request->all(), $id);

        Flash::success('Vaccinations updated successfully.');

        return redirect(route('vaccinations.index'));
    }

    /**
     * Remove the specified Vaccinations from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $vaccinations = $this->vaccinationsRepository->find($id);

        if (empty($vaccinations)) {
            Flash::error('Vaccinations not found');

            return redirect(route('vaccinations.index'));
        }

        $this->vaccinationsRepository->delete($id);

        Flash::success('Vaccinations deleted successfully.');

        return redirect(route('vaccinations.index'));
    }
}
