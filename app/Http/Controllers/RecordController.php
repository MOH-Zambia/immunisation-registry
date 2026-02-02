<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class RecordController extends AppBaseController
{
    /**
     * Display a listing of the Records.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // This controller serves as a placeholder for vaccination records management
        // It can be extended to manage combined vaccination and certificate records
        return view('records.index');
    }

    /**
     * Show the form for creating a new Record.
     *
     * @return Response
     */
    public function create()
    {
        Flash::info('Records management feature coming soon.');
        return redirect()->back();
    }

    /**
     * Display the specified Record.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        Flash::info('Records management feature coming soon.');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified Record.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        Flash::info('Records management feature coming soon.');
        return redirect()->back();
    }

    /**
     * Update the specified Record in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Flash::info('Records management feature coming soon.');
        return redirect()->back();
    }

    /**
     * Remove the specified Record from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Flash::error('Records cannot be deleted.');
        return redirect()->back();
    }
}
