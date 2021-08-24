<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProviderIDRequest;
use App\Http\Requests\UpdateProviderIDRequest;
use App\Repositories\ProviderIDRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ProviderIDController extends AppBaseController
{
    /** @var  ProviderIDRepository */
    private $providerIDRepository;

    public function __construct(ProviderIDRepository $providerIDRepo)
    {
        $this->providerIDRepository = $providerIDRepo;
    }

    /**
     * Display a listing of the ProviderID.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $providerIDs = $this->providerIDRepository->all();

        return view('provider_i_ds.index')
            ->with('providerIDs', $providerIDs);
    }

    /**
     * Show the form for creating a new ProviderID.
     *
     * @return Response
     */
    public function create()
    {
        return view('provider_i_ds.create');
    }

    /**
     * Store a newly created ProviderID in storage.
     *
     * @param CreateProviderIDRequest $request
     *
     * @return Response
     */
    public function store(CreateProviderIDRequest $request)
    {
        $input = $request->all();

        $providerID = $this->providerIDRepository->create($input);

        Flash::success('Provider I D saved successfully.');

        return redirect(route('providerIDs.index'));
    }

    /**
     * Display the specified ProviderID.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $providerID = $this->providerIDRepository->find($id);

        if (empty($providerID)) {
            Flash::error('Provider I D not found');

            return redirect(route('providerIDs.index'));
        }

        return view('provider_i_ds.show')->with('providerID', $providerID);
    }

    /**
     * Show the form for editing the specified ProviderID.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $providerID = $this->providerIDRepository->find($id);

        if (empty($providerID)) {
            Flash::error('Provider I D not found');

            return redirect(route('providerIDs.index'));
        }

        return view('provider_i_ds.edit')->with('providerID', $providerID);
    }

    /**
     * Update the specified ProviderID in storage.
     *
     * @param int $id
     * @param UpdateProviderIDRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProviderIDRequest $request)
    {
        $providerID = $this->providerIDRepository->find($id);

        if (empty($providerID)) {
            Flash::error('Provider I D not found');

            return redirect(route('providerIDs.index'));
        }

        $providerID = $this->providerIDRepository->update($request->all(), $id);

        Flash::success('Provider I D updated successfully.');

        return redirect(route('providerIDs.index'));
    }

    /**
     * Remove the specified ProviderID from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $providerID = $this->providerIDRepository->find($id);

        if (empty($providerID)) {
            Flash::error('Provider I D not found');

            return redirect(route('providerIDs.index'));
        }

        $this->providerIDRepository->delete($id);

        Flash::success('Provider I D deleted successfully.');

        return redirect(route('providerIDs.index'));
    }
}
