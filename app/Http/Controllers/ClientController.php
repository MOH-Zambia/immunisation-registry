<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;

use Response;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends AppBaseController
{
    /** @var  ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
//        $clients = $this->clientRepository->paginate(50);

//        return view('clients.index')
//            ->with('clients', $clients);

        return view('clients.datatable');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(Client::all())
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a>
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param CreateClientRequest $request
     *
     * @return Response
     */
//    public function store(CreateClientRequest $request)
//    {
//        $input = $request->all();
//
//        $client = $this->clientRepository->create($input);
//
//        Flash::success('Client saved successfully.');
//
//        return redirect(route('clients.index'));
//    }

    /**
     * Store a newly created Client in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();

        if (array_key_exists('nrc', $input)) {

            $client = Client::where([
                ['NRC', '=', $input['nrc']],
                ['last_name', '=', $input['last_name']],
                ['first_name', '=', $input['first_name']],
                ['other_names', '=', $input['other_names']]
            ])->first();

            if(!empty($client))
                return response()->json(['verification'=>'Successful']);
        }

        if (array_key_exists('passport', $input)) {
            $client = Client::where([
                ['passport_number', '=', $input['passport']],
                ['last_name', '=', $input['last_name']],
                ['first_name', '=', $input['first_name']],
                ['other_names', '=', $input['other_names']]
            ])->first();

            if(!empty($client))
                return response()->json(['verification'=>'Successful']);
        }

        if (array_key_exists('drivers_license', $input)) {
            $client = Client::where([
                ['drivers_license', '=', $input['drivers_license']],
                ['last_name', '=', $input['last_name']],
                ['first_name', '=', $input['first_name']],
                ['other_names', '=', $input['other_names']]
            ])->first();

            if(!empty($client))
                return response()->json(['verification'=>'Successful']);
        }

        if (array_key_exists('email', $input)) {
            $client = Client::where([
                ['contact_email_address', '=', $input['email']],
                ['last_name', '=', $input['last_name']],
                ['first_name', '=', $input['first_name']],
                ['other_names', '=', $input['other_names']]
            ])->first();

            if(!empty($client))
                return response()->json(['verification'=>'Successful']);
        }

        return response()->json(['verification'=>'Unsuccessful']);
    }

    /**
     * Display the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($id != Auth::user()->client['id']){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        return view('clients.show')->with('client', $client);
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
//    public function edit($id)
//    {
//        $client = $this->clientRepository->find($id);
//
//        if (empty($client)) {
//            Flash::error('Client not found');
//
//            return redirect(route('clients.index'));
//        }
//
//        return view('clients.edit')->with('client', $client);
//    }

    /**
     * Update the specified Client in storage.
     *
     * @param int $id
     * @param UpdateClientRequest $request
     *
     * @return Response
     */
//    public function update($id, UpdateClientRequest $request)
//    {
//        $client = $this->clientRepository->find($id);
//
//        if (empty($client)) {
//            Flash::error('Client not found');
//
//            return redirect(route('clients.index'));
//        }
//
//        $client = $this->clientRepository->update($request->all(), $id);
//
//        Flash::success('Client updated successfully.');
//
//        return redirect(route('clients.index'));
//    }

    /**
     * Remove the specified Client from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
//    public function destroy($id)
//    {
//        $client = $this->clientRepository->find($id);
//
//        if (empty($client)) {
//            Flash::error('Client not found');
//
//            return redirect(route('clients.index'));
//        }
//
//        $this->clientRepository->delete($id);
//
//        Flash::success('Client deleted successfully.');
//
//        return redirect(route('clients.index'));
//    }
}
