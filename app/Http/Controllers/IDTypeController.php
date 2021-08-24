<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIDTypeRequest;
use App\Http\Requests\UpdateIDTypeRequest;
use App\Repositories\IDTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class IDTypeController extends AppBaseController
{
    /** @var  IDTypeRepository */
    private $iDTypeRepository;

    public function __construct(IDTypeRepository $iDTypeRepo)
    {
        $this->iDTypeRepository = $iDTypeRepo;
    }

    /**
     * Display a listing of the IDType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $iDTypes = $this->iDTypeRepository->all();

        return view('i_d_types.index')
            ->with('iDTypes', $iDTypes);
    }

    /**
     * Show the form for creating a new IDType.
     *
     * @return Response
     */
    public function create()
    {
        return view('i_d_types.create');
    }

    /**
     * Store a newly created IDType in storage.
     *
     * @param CreateIDTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateIDTypeRequest $request)
    {
        $input = $request->all();

        $iDType = $this->iDTypeRepository->create($input);

        Flash::success('I D Type saved successfully.');

        return redirect(route('iDTypes.index'));
    }

    /**
     * Display the specified IDType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $iDType = $this->iDTypeRepository->find($id);

        if (empty($iDType)) {
            Flash::error('I D Type not found');

            return redirect(route('iDTypes.index'));
        }

        return view('i_d_types.show')->with('iDType', $iDType);
    }

    /**
     * Show the form for editing the specified IDType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $iDType = $this->iDTypeRepository->find($id);

        if (empty($iDType)) {
            Flash::error('I D Type not found');

            return redirect(route('iDTypes.index'));
        }

        return view('i_d_types.edit')->with('iDType', $iDType);
    }

    /**
     * Update the specified IDType in storage.
     *
     * @param int $id
     * @param UpdateIDTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIDTypeRequest $request)
    {
        $iDType = $this->iDTypeRepository->find($id);

        if (empty($iDType)) {
            Flash::error('I D Type not found');

            return redirect(route('iDTypes.index'));
        }

        $iDType = $this->iDTypeRepository->update($request->all(), $id);

        Flash::success('I D Type updated successfully.');

        return redirect(route('iDTypes.index'));
    }

    /**
     * Remove the specified IDType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $iDType = $this->iDTypeRepository->find($id);

        if (empty($iDType)) {
            Flash::error('I D Type not found');

            return redirect(route('iDTypes.index'));
        }

        $this->iDTypeRepository->delete($id);

        Flash::success('I D Type deleted successfully.');

        return redirect(route('iDTypes.index'));
    }
}
