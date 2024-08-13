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
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        if(auth()->user()->tokenCan('user-store')):
            return $this->error('Unauthorized', 403);
        endif;

        $validator = Validator::make($request->all(), [
            'company_id' => ['required'],
            'usergroup_id' => ['required'],
            'name' => ['required', 'between:3,255'],
            'email' => ['required', 'email', 'between:3,255', 'unique:users'],
            'password' => ['required', 'between:3,255'],
        ]);

        if($validator->fails()){
            return $this->error('Invalid Data', 422, $validator->errors());
        }

        // Converte para array.
        $validator = $validator->safe();

        if(Company::where('id', $validator['company_id'])->doesntExist()){
            return $this->error('Invalid Company', 400, []);
        }

        if(Usergroup::where('id', $validator['usergroup_id'])->doesntExist()){
            return $this->error('Invalid Usergroup', 400, []);
        }

        // Estende array.
        $validator['company_name'] = Company::find($validator['company_id'])->name;
        $validator['usergroup_name'] = Usergroup::find($validator['usergroup_id'])->name;
        $validator['password'] = Hash::make($validator['password']);

        $created = User::create([
            'company_id' => $validator['company_id'],
            'company_name' => $validator['company_name'],
            'usergroup_id' => $validator['usergroup_id'],
            'usergroup_name' => $validator['usergroup_name'],
            'name' => $validator['name'],
            'email' => $validator['email'],
            'password' => $validator['password'],
        ]);

        if($created){
            return $this->response('User Created', 200, [
                'company_id' => $validator['company_id'],
                'company_name' => $validator['company_name'],
                'usergroup_id' => $validator['usergroup_id'],
                'usergroup_name' => $validator['usergroup_name'],
                'name' => $validator['name'],
                'email' => $validator['email'],
            ]);
        }

        return $this->error('User Not Created', 400);
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
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'   => ['required'],
            'usergroup_id' => ['required'],
            'name'         => ['required', 'between:3,255'],
            'email'        => ['required', 'email', 'between:3,255', 'unique:users,email,'.$id.''],
        ]);

        if($validator->fails()){
            return $this->error('Invalid Data', 422, $validator->errors());
        }

        // Converte para array.
        $validator = $validator->safe();

        if(Company::where('id', $validator['company_id'])->doesntExist()){
            return $this->error('Invalid Company', 400, []);
        }

        if(Usergroup::where('id', $validator['usergroup_id'])->doesntExist()){
            return $this->error('Invalid Usergroup', 400, []);
        }

        // Estende array.
        $validator['company_name'] = Company::find($validator['company_id'])->name;
        $validator['usergroup_name'] = Usergroup::find($validator['usergroup_id'])->name;

        $updated = User::find($id)->update([
            'company_id' => $validator['company_id'],
            'company_name' => $validator['company_name'],
            'usergroup_id' => $validator['usergroup_id'],
            'usergroup_name' => $validator['usergroup_name'],
            'name' => $validator['name'],
            'email' => $validator['email'],
        ]);

        if($updated){
            return $this->response('User Updated', 200, [
                'company_id' => $validator['company_id'],
                'company_name' => $validator['company_name'],
                'usergroup_id' => $validator['usergroup_id'],
                'usergroup_name' => $validator['usergroup_name'],
                'name' => $validator['name'],
                'email' => $validator['email'],
            ]);
        }

        return $this->error('User Not Updated', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = User::find($id)->delete();

        if($deleted){
            return $this->response('User Deleted', 200);
        }

        return $this->error('User Not Deleted', 400);
    }
}
