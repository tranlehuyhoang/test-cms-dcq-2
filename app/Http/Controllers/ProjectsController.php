<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Projects;
use App\Models\Customer;


class ProjectsController extends BaseController {
	public function index() {
		$data['arProjects'] = Projects::with('projectCustomer')->get()->keyBy('id')->toArray();

		return view('projects.index', $data);
	}

	public function add() {
		$data['arCustomer'] = Customer::get()->pluck('name', 'id')->toArray();

		return view('projects.add', $data);
	}

	public function edit(Request $request) {
		$data['arCustomer'] = Customer::get()->pluck('name', 'id')->toArray();
		$data['project'] = Projects::with('projectCustomer')->where('id', '=', $request->id)->get()->toArray();
		$data['arDoc'] = Projects::find($request->id)->getMedia('document');

		return view('projects.edit', $data);
	}

	public function update(Request $request) {
		// dd($request->input());
		if (empty($request->project['id'])) {
			Projects::create(array(
				"name" => $request->project["name"],
				"code" => $request->project["code"],
				"description" => $request->project["description"],
				"budget" => $request->project["budget"],
				"customer_id" => $request->project["customer_id"],
				"due_date" => $request->project["due_date"],
				"progress" => 0,
				"status" => $request->project["status"],
			))->id;
		} else {
			$project = Projects::find($request->project["id"]);
			
			$project->code = $request->project["code"];
			$project->name = $request->project["name"];
			$project->description = $request->project["description"];
			$project->budget = $request->project["budget"];
			$project->customer_id = $request->project["customer_id"];
			$project->due_date = $request->project["due_date"];
			$project->status = $request->project["status"];

			$project->save();

			if (isset($request->project['file']) && !empty($request->project['file'])) {
				$project->addMedia($request->project['file'])->toMediaCollection('document');
			}
		}

		return redirect()->route('projects.index');

	}
}
