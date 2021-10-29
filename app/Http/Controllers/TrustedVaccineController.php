<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrustedVaccineController extends Controller
{
    /**
     * Store a newly created User in storage.
     *
     * @param CreateBookletRequest $request
     *
     * @return Response
     */
    public function store(CreateBookletRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }
}
