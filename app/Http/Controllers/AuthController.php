<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class AuthController extends Controller
{
    
    /**
     * login API
     *
     * @param \Illuminate\Http\Request  $request
     * @param string email required
     * @param string password required
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
    	$input = $request->all();
    	$validator = Validator::make($input, [
    		'email' => 'required|email',
    		'password' => 'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json($validator->errors(), 417);
    	}

    	$credentials = $request->only(['email', 'password']);
    	if (Auth::attempt($credentials)) {
    		$user = Auth::user();
    		$success['token'] = $user->createToken('MyApp', ['create-recipe', 'edit-recipe', 'delete-recipe', 'create-ingredient', 'edit-ingredient', 'delete-ingredient', 'create-direction', 'edit-direction', 'delete-direction', 'add-review', 'edit-review', 'delete-review'])->accessToken;
    		return response()->json(['success' => $success], 200);
    	}
    	else {
    		return response()->json(['error' => 'Unauthorized'], 401);
    	}
    }

    /**
     * register API
     *
     * @param \Illuminate\Http\Request  $request
     * @param string email required
     * @param string password required
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
    	$input = $request->all();
    	$validator = Validator::make($input, [
    		'name' => 'required',
    		'email' => 'required|email',
    		'password' => 'required',
    		'c_password' => 'required|same:password',
    	]);

    	if ($validator->fails()) {
    		return response()->json($validator->errors(), 417);
    	}

    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password),
    	]);
    	$success['name'] = $user->name;
    	$success['token'] = $user->createToken('MyApp', ['create-recipe', 'edit-recipe', 'delete-recipe', 'create-ingredient', 'edit-ingredient', 'create-direction', 'edit-direction', 'delete-direction', 'add-review', 'edit-review', 'delete-review'])->accessToken;

    	return response()->json(['success' => $success], 200);
    }

    /**
     * admin Login API
     *
     * @param \Illuminate\Http\Request  $request
     * @param string email required
     * @param string password required
     * @return \Illuminate\Http\Response
     */
    //TODO: Rewrite entire Admin workflow to give admin more privilages
    //TODO: Implement User Roles and Privilages (use Spatie or scafold own user roles and privilages)
    public function adminLogin(Request $request)
    {
    	$input = $request->all();
    	$validator = Validator::make($input, [
    		'email' => 'required|email',
    		'password' => 'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json($validator->errors(), 417);
    	}

    	$credentials = $request->only(['email', 'password']);
    	if (Auth::attempt($credentials)) {
    		$user = Auth::user();
    		$success['token'] = $user->createToken('MyApp', ['*'])->accessToken;
    		return response()->json(['success' => $success], 200);
    	}
    	else {
    		return response()->json(['error' => 'Unauthorized'], 401);
    	}
    }

    /**
     * admin Register API
     *
     * @param \Illuminate\Http\Request  $request
     * @param string email required
     * @param string password required
     * @return \Illuminate\Http\Response
     */

    public function adminRegister(Request $request)
    {
    	$input = $request->all();
    	$validator = Validator::make($input, [
    		'name' => 'required',
    		'email' => 'required|email',
    		'password' => 'required',
    		'c_password' => 'required|same:password',
    	]);

    	if ($validator->fails()) {
    		return response()->json($validator->errors(), 417);
    	}

    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password),
    	]);

    	$success['name'] = $user->name;
    	$success['token'] = $user->createToken('MyApp', ['*'])->accessToken;
    	return response()->json(['success' => $success], 200);
    }
}
