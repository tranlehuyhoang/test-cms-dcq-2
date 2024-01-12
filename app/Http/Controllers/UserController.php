<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Roles;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController {
	public function loginForm() {
		if (Auth::check()) {
			return redirect(route('dashboard'));
		}
		
		return view('users.login-form');
	}

	public function index() {
		$data['arUsers'] = User::with('userRole')->get()->toArray();
		$users = User::with('userRole')->get();

		foreach($users as $user){
			$data['avatarUsers'][$user->id] = User::find($user->id)->getMedia('avatar');
		}

		// dd($data);

		return view('users.index', $data);
	}

	public function add() {
		$data['arRole'] = Roles::get()->pluck('name', 'id')->toArray();
		return view('users.add', $data);
	}

	public function edit(Request $request) {
		$data['user'] = User::with('userRole')->where('id', '=', $request->id)->get()->toArray();
		$data['arRole'] = Roles::get()->pluck('name', 'id')->toArray();
		$data['avatarUsers'][$request->id] = User::find($request->id)->getMedia('avatar');
		return view('users.edit', $data);
	}

	public function login(Request $request) {
		if (Auth::check()) {
			return redirect(route('dashboard'));
		}

		if (Auth::attempt(array(
			'email' => $request->input('email'),
			'password' => $request->input('password'),
		), $request->input('remember'))) {
			return redirect(route('dashboard'));
		}

		return redirect()->back();
	}

	public function detail(Request $request) {
		return view('users.detail');
	}

	public function update(Request $request) {
		if (empty($request->id)) {
			$request->validate([
				'name' => 'required',
				'password' => 'required',
				'email' => 'required'
			]);

			$id = User::create(array(
				"name" => $request->name,
				"password" => Hash::make($request->password),
				"email" => $request->email,
				"role_id" => $request->role_id,
				"country" => $request->country,
				"phone" => $request->phone,
				"point_accumulated" => $request->point_accumulated,
				"expertise_coefficient" => $request->expertise_coefficient,
				"allowance" => $request->allowance
			))->id;

			if (isset($request->avatar) && !empty($request->avatar)){
				$user = User::findOrFail($id);
				$user->addMedia($request->avatar)->toMediaCollection('avatar');
			}
		} else {
			$user = User::find($request->id);
			
			$user->email = $request->email;
			$user->name = $request->name;
			$user->role_id = $request->role_id;
			$user->country = $request->country;
			$user->phone = $request->phone;
			$user->point_accumulated = $request->point_accumulated;
			$user->expertise_coefficient = $request->expertise_coefficient;
			$user->allowance = $request->allowance;

			$user->save();

			if(isset($request->avatar) && !empty($request->avatar)){
				$user->clearMediaCollection('avatar');
				$user->addMedia($request->avatar)->toMediaCollection('avatar');
			}
		}

		return redirect()->route('user.index');

	}
}
