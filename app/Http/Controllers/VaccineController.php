<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVaccineRequest;
use App\Http\Requests\UpdateVaccineRequest;
use App\Repositories\VaccineRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class VaccineController extends AppBaseController
{
    /** @var  VaccineRepository */
    private $vaccineRepository;

    public function __construct(VaccineRepository $vaccineRepo)
    {
        $this->vaccineRepository = $vaccineRepo;
    }

    /**
     * Display a listing of the Vaccine.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $vaccines = $this->vaccineRepository->all();

        return view('vaccines.index')
            ->with('vaccines', $vaccines);
    }

    /**
     * Show the form for creating a new Vaccine.
     *
     * @return Response
     */
    public function create()
    {
        return view('vaccines.create');
    }

    /**
     * Store a newly created Vaccine in storage.
     *
     * @param CreateVaccineRequest $request
     *
     * @return Response
     */
    public function store(CreateVaccineRequest $request)
    {
        $input = $request->all();

        $vaccine = $this->vaccineRepository->create($input);

        Flash::success('Vaccine saved successfully.');

        return redirect(route('vaccines.index'));
    }

    /**
     * Display the specified Vaccine.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $vaccine = $this->vaccineRepository->find($id);

        if (empty($vaccine)) {
            Flash::error('Vaccine not found');

            return redirect(route('vaccines.index'));
        }

        return view('vaccines.show')->with('vaccine', $vaccine);
    }

    /**
     * Show the form for editing the specified Vaccine.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $vaccine = $this->vaccineRepository->find($id);

        if (empty($vaccine)) {
            Flash::error('Vaccine not found');

            return redirect(route('vaccines.index'));
        }

        return view('vaccines.edit')->with('vaccine', $vaccine);
    }

    /**
     * Update the specified Vaccine in storage.
     *
     * @param int $id
     * @param UpdateVaccineRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVaccineRequest $request)
    {
        $vaccine = $this->vaccineRepository->find($id);

        if (empty($vaccine)) {
            Flash::error('Vaccine not found');

            return redirect(route('vaccines.index'));
        }

        $vaccine = $this->vaccineRepository->update($request->all(), $id);

        Flash::success('Vaccine updated successfully.');

        return redirect(route('vaccines.index'));
    }

    /**
     * Remove the specified Vaccine from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $vaccine = $this->vaccineRepository->find($id);

        if (empty($vaccine)) {
            Flash::error('Vaccine not found');

            return redirect(route('vaccines.index'));
        }

        $this->vaccineRepository->delete($id);

        Flash::success('Vaccine deleted successfully.');

        return redirect(route('vaccines.index'));
    }
}
