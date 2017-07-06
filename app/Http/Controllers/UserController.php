<?php
/**
 * Created By: Praditha Hidayat
 * Date: 06/07/2017
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiBaseController;
use App\Models\User;

class UserController extends ApiBaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->storeUser($request);

        if ($user->isSuccess) {
    		return $this->sendResponse('User successfully registered', $user->data);
    	} else {
    		return $this->sendError($user->errors);
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (empty($user)) {
        	return $this->sendError("User does not exist");
        } else {
        	return $this->sendResponse("User has been found", $user);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return $this->sendError("User does not exist");
        }
        
        $user->updateUser($request, $user);
        if ($user->isSuccess) {
            return $this->sendResponse('User successfully update');
        } else {
            return $this->sendError($user->errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return $this->sendError("User does not exist");
        }

        $user->deleteUser($user);
        if ($user->isSuccess) {
            return $this->sendResponse('User successfully delete');
        } else {
            return $this->sendError($user->errors);
        }
    }
}
