<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Resources\V1\UserResource;

use App\Models\User;
use App\Models\Usergroup;
use App\Models\Company;

use App\Traits\HttpResponses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => ['required'],
            'usergroup_id' => ['required'],
            'name' => ['required', 'between:3,255'],
            'email' => ['required', 'email', 'between:3,255', 'unique:users'],
            'password' => ['required', 'between:3,255'],
        ]);

        if($validator->fails()){
            return $this->error('Data Invalid', 422, $validator->errors());
        }

        $validator = $validator->safe();

        if(Company::where('id', $validator['company_id'])->doesntExist()){
            return $this->error('Company Invalid', 400, []);
        }

        if(Usergroup::where('id', $validator['usergroup_id'])->doesntExist()){
            return $this->error('Usergroup Invalid', 400, []);
        }

        $validator->safe()->merge([
            'company_name' => Company::find($validator['company_id'])->name,
            'usergroup_name' => Usergroup::find($validator['usergroup_id'])->name,
        ]);

        // Estende $validator.
        $validator['company_name'] = Company::find($validator['company_id'])->name;
        $validator['usergroup_name'] = Usergroup::find($validator['usergroup_id'])->name;

        $created = User::create([
            'company_id' => $validator['company_id'],
            'company_name' => Company::find($validator['company_id'])->name,
            'usergroup_id' => Usergroup::find($validator['usergroup_id'])->name,
            'usergroup_name' => $validator['usergroup_id'],
            'name' => $validator['name'],
            'email' => $validator['email'],
            'password' => $validator['password'],
        ]);

        if(!$created){
            return $this->error('Data Invalid', 400, $validator->errors());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
