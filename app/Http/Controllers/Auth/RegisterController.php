<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    private $userRepository;
    private $uploadRepository;
    private $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository)
    {
        $this->middleware('guest');
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->name =  $data['name'];
        $user->email =  $data['email'];
        $user->password = Hash::make($data['password']);
        $user->api_token = str_random(60);
        $user->save();

        /*$defaultRoles = $this->roleRepository->findByField('default', '1');
        $defaultRoles = $defaultRoles->pluck('name')->toArray();
        //$defaultRoles = array('admin');*/
        $role = array('client');
        $user->assignRole($role);

        $user->addMediaFromUrl("https://na.ui-avatars.com/api/?name=".str_replace(" ","+",$user->name))
            ->withCustomProperties(['uuid' => bcrypt(str_random())])
            ->toMediaCollection('avatar');

        return $user;
    }
}
