<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateImportLogRequest;
use App\Http\Requests\UpdateImportLogRequest;
use App\Repositories\ImportLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ImportLogController extends AppBaseController
{
    /** @var  ImportLogRepository */
    private $importLogRepository;

    public function __construct(ImportLogRepository $importLogRepo)
    {
        $this->importLogRepository = $importLogRepo;
    }

    /**
     * Display a listing of the ImportLog.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $importLogs = $this->importLogRepository->all();

        return view('import_logs.index')
            ->with('importLogs', $importLogs);
    }

    /**
     * Show the form for creating a new ImportLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('import_logs.create');
    }

    /**
     * Store a newly created ImportLog in storage.
     *
     * @param CreateImportLogRequest $request
     *
     * @return Response
     */
    public function store(CreateImportLogRequest $request)
    {
        $input = $request->all();

        $importLog = $this->importLogRepository->create($input);

        Flash::success('Import Log saved successfully.');

        return redirect(route('importLogs.index'));
    }

    /**
     * Display the specified ImportLog.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $importLog = $this->importLogRepository->find($id);

        if (empty($importLog)) {
            Flash::error('Import Log not found');

            return redirect(route('importLogs.index'));
        }

        return view('import_logs.show')->with('importLog', $importLog);
    }

    /**
     * Show the form for editing the specified ImportLog.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $importLog = $this->importLogRepository->find($id);

        if (empty($importLog)) {
            Flash::error('Import Log not found');

            return redirect(route('importLogs.index'));
        }

        return view('import_logs.edit')->with('importLog', $importLog);
    }

    /**
     * Update the specified ImportLog in storage.
     *
     * @param int $id
     * @param UpdateImportLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportLogRequest $request)
    {
        $importLog = $this->importLogRepository->find($id);

        if (empty($importLog)) {
            Flash::error('Import Log not found');

            return redirect(route('importLogs.index'));
        }

        $importLog = $this->importLogRepository->update($request->all(), $id);

        Flash::success('Import Log updated successfully.');

        return redirect(route('importLogs.index'));
    }

    /**
     * Remove the specified ImportLog from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $importLog = $this->importLogRepository->find($id);

        if (empty($importLog)) {
            Flash::error('Import Log not found');

            return redirect(route('importLogs.index'));
        }

        $this->importLogRepository->delete($id);

        Flash::success('Import Log deleted successfully.');

        return redirect(route('importLogs.index'));
    }
}
