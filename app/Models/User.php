<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class User extends Authenticatable
{
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'birth_date',
        'photo'
    ];

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    public $isSuccess = TRUE;
    public $errors;
    public $data;

    private $rules = [
        'full_name' => 'required',
        'password' => 'required|confirmed',
        'birth_date' => 'required|date',
        'photo' => 'image'
    ];

    private $ruleMessages = [
        'email.unique' => 'User already exists'
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getPhotoAttribute($value) {
        return \Storage::url(\Config::get('mcoin.path.user_photo') . '/' . $value);
    }

    private function isInputValid($input) {
        $validator = Validator::make($input, $this->rules, $this->ruleMessages);

        if ($validator->fails()) {
            $this->isSuccess = FALSE;
            $this->errors = $validator->errors()->first();
            return FALSE;
        }

        return TRUE;
    }

    public function storeUser($request) {
        $input = $request->input();
        $rules = $this->rules;
        $rules['email'] = 'required|email|unique:users,email';

        if (!$this->isInputValid($input, $rules)) return;

        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                $fileName = 'PHOTO' . date('YmdHis') . '.' . $request->photo->extension();
                $path = $request->photo->storeAs(\Config::get('mcoin.path.user_photo'), $fileName);
                $input['photo'] = $fileName;
            }
        }

        $this->data = $this->create($input);
        return;
    }

    public function updateUser($request, $userToBeUpdated) {
        $input = $request->input();
        $rules = $this->rules;
        $rules['email'] = 'required|email|unique:users,email,' . $userToBeUpdated->id;

        if (!$this->isInputValid($input, $rules)) return;

        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                // Delete existing photo
                \Storage::delete(\Config::get('mcoin.path.user_photo') . '/' . $userToBeUpdated->getOriginal('photo'));

                $fileName = 'PHOTO' . date('YmdHis') . '.' . $request->photo->extension();
                $path = $request->photo->storeAs(\Config::get('mcoin.path.user_photo'), $fileName, 'public');
                $input['photo'] = $fileName;
            }
        }

        $this->data = $userToBeUpdated->update($input);
        return;
    }

    public function deleteUser($userToBeDeleted) {
        if (!empty($userToBeDeleted->photo)) {
            // Delete existing photo
            \Storage::delete(\Config::get('mcoin.path.user_photo') . '/' . $userToBeDeleted->getOriginal('photo'));
        }

        $userToBeDeleted->delete();
        return;
    }
}
