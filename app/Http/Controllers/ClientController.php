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
        return view('clients.datatable');
    }

    public function datatable(Request $request)
    {
        $clients = Client::select([
            'id',
            'source_id',
            'card_number',
            'NRC',
            'passport_number',
            'last_name',
            'first_name',
            'other_names',
            'sex',
            'contact_number',
            'contact_email_address'
        ])->orderBy('id', 'DESC')->limit(50);

        return Datatables::of($clients)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '<a href="/clients/'.$row->id.'" class="edit btn btn-success btn-sm">View</a>';
            })
            ->rawColumns(['action'])
            ->toJson();
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
        $verifyId = uniqid('CLIENT-VERIFY-', true);

        Log::info("[$verifyId] Client verification request initiated", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'nrc' => 'nullable|string|max:50',
            'passport' => 'nullable|string|max:50',
            'drivers_license' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $input = $request->all();
        $client = null;
        $searchMethod = 'none';

        if (array_key_exists('nrc', $input) && !empty($input['nrc'])) {
            $searchMethod = 'NRC';
            Log::info("[$verifyId] Searching by NRC: " . substr($input['nrc'], -4));
            $client = Client::where('NRC', $input['nrc'])->first();
        }

        if (!$client && array_key_exists('passport', $input) && !empty($input['passport'])) {
            $searchMethod = 'Passport';
            Log::info("[$verifyId] Searching by Passport: " . substr($input['passport'], -4));
            $client = Client::where('passport_number', $input['passport'])->first();
        }

        if (!$client && array_key_exists('drivers_license', $input) && !empty($input['drivers_license'])) {
            $searchMethod = 'Drivers License';
            Log::info("[$verifyId] Searching by Drivers License: " . substr($input['drivers_license'], -4));
            $client = Client::where('drivers_license', $input['drivers_license'])->first();
        }

        if (!$client && array_key_exists('email', $input) && !empty($input['email'])) {
            $searchMethod = 'Email';
            $maskedEmail = substr($input['email'], 0, 3) . '***@' . explode('@', $input['email'])[1];
            Log::info("[$verifyId] Searching by Email: $maskedEmail");
            $client = Client::where('contact_email_address', $input['email'])->first();
        }

        if(empty($client)){
            Log::warning("[$verifyId] Client not found", [
                'search_method' => $searchMethod,
                'has_nrc' => array_key_exists('nrc', $input) && !empty($input['nrc']),
                'has_passport' => array_key_exists('passport', $input) && !empty($input['passport']),
                'has_license' => array_key_exists('drivers_license', $input) && !empty($input['drivers_license']),
                'has_email' => array_key_exists('email', $input) && !empty($input['email'])
            ]);
            return $this->sendError('Client not found');
        } else {
            Log::info("[$verifyId] Client found successfully", [
                'search_method' => $searchMethod,
                'client_id' => $client->id,
                'has_contact_number' => !empty($client->contact_number),
                'has_email' => !empty($client->contact_email_address)
            ]);
            return $this->sendSuccess($client->toJson());
        }
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
