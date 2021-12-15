<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Flash;
use Response;

use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
//        $users = $this->userRepository->paginate(50);

//        return view('users.index')
//            ->with('users', $users);

        return view('users.datatable');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(User::all())
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
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
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
            if($id != Auth::user()->id){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($id != Auth::user()->id){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        $user = $this->userRepository->find($id);
        $roles = Role::all();

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')
            ->with('user', $user)
            ->with('roles', $roles);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($id != Auth::user()->id){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $input = $request->all();

        if ($request->filled('password')) {
            //Validate password
            If($input['password'] != $input['confirm_password']){
                Flash::error('Password is not same as confirm password');
                return back();
            }

            $input['password'] = Hash::make($input['password']);
        } else {
            Flash::error('Please enter password');
            return back();
        }


        $user = $this->userRepository->update($input, $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        // User role
        $role = Auth::user()->role['name'];

        if($role == 'Authenticated User') {
            if($id != Auth::user()->id){
                Flash::error('Unauthorised access');
                return back();
            }
        }

        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
