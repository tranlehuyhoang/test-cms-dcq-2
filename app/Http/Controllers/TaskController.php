<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

use Illuminate\Routing\Controller as BaseController;

use App\Models\Tasks;
use App\Models\Roles;
use App\Models\User;
use App\Models\Projects;
use App\Models\TaskComment;
use Carbon\Carbon;

class TaskController extends BaseController
{
	public function index()
	{
		$user = Auth::user();

		$data['arRoles'] = Roles::whereIn('code', array(User::ADMIN, User::MANAGER))->get()->pluck('name', 'id')->toArray();

		// if (in_array($user->role_id, array_keys($data['arRoles']))) {
		// 	$data['arTasks'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('parent_id', '=', 0)->get()->keyBy('id')->toArray();
		// } else {
		// 	$data['arTasks'] = array();
		// }

		$data['arTasks'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('parent_id', '=', 0)->get()->keyBy('id')->toArray();

		$data['arProject'] = Projects::get()->pluck('name', 'id')->toArray();
		$data['user_id'] = $user->id;

		return view('tasks.index', $data);
	}

	public function detail(Request $request)
	{
		$user = Auth::user();

		$data = array();

		$data['task'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('id', '=', $request->id)->get()->toArray();

		$data['arChildTasks'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('parent_id', '=', $request->id)->get()->toArray();

		$data['arStatus'] = Tasks::STATUS;
		$data['arPriority'] = Tasks::PRIORITY;
		$data['task_id'] = $request->id;

		$data['taskComments'] = TaskComment::where('task_id', '=', $request->id)->get();

		$data['user_id'] =  Auth::user()->id;

		$data['taskComments'] = TaskComment::with('user')
			->where('task_id', $request->id)
			->where('reply_id', 0)
			->orderBy('created_at', 'desc')
			->take(3)
			->get();

		// Chuyển đổi múi giờ và định dạng thời gian cho mỗi TaskComment
		foreach ($data['taskComments'] as $taskComment) {
			$createdDate = \Carbon\Carbon::parse($taskComment->created_at)->setTimezone('Asia/Ho_Chi_Minh');
			$taskComment->diffForHumansInVietnam = $createdDate->diffForHumans();
		}

		$data['taskCommentsWithReplyCount'] = [];

		foreach ($data['taskComments'] as $taskComment) {
			$taskComment->replyCount = TaskComment::where('reply_id', $taskComment->id)->count();
			$data['taskCommentsWithReplyCount'][] = $taskComment;
		}
		// Chuyển đổi múi giờ và định dạng thời gian cho mỗi TaskComment có reply_id khác 0

		// Chuyển đổi múi giờ và định dạng thời gian cho mỗi TaskComment có reply_id khác 0


		$data['taskCommentss'] = TaskComment::where('task_id', '=', $request->id)->get();

		$data['commentCount'] = count($data['taskCommentss']);
		return view('tasks.detail', $data);
	}

	// public function detail(Request $request)
	// {
	// 	$user = Auth::user();

	// 	$data = array();

	// 	$data['task'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('id', '=', $request->id)->get()->toArray();

	// 	$data['arChildTasks'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('parent_id', '=', $request->id)->get()->toArray();

	// 	$data['arStatus'] = Tasks::STATUS;
	// 	$data['arPriority'] = Tasks::PRIORITY;
	// 	$data['task_id'] = $request->id;

	// 	$data['taskComments'] = TaskComment::where('task_id', '=', $request->id)->get();

	// 	$data['users'] = User::whereIn('id', $data['taskComments']->pluck('create_by'))->get();
	// 	$data['user_id'] =  Auth::user()->id;

	// 	$data['taskComments'] = TaskComment::with('user')
	// 		->where('task_id', $request->id)
	// 		->where('reply_id', 0)
	// 		->orderBy('created_at', 'desc')
	// 		->get();

	// 	// Chuyển đổi múi giờ và định dạng thời gian cho mỗi TaskComment
	// 	foreach ($data['taskComments'] as $taskComment) {
	// 		$createdDate = \Carbon\Carbon::parse($taskComment->created_at)->setTimezone('Asia/Ho_Chi_Minh');
	// 		$taskComment->diffForHumansInVietnam = $createdDate->diffForHumans();
	// 	}
	// 	$data['taskCommentsWithReplys'] = TaskComment::with('user')
	// 		->where('task_id', $request->id)
	// 		->where('reply_id', '!=', 0)
	// 		->orderBy('created_at', 'asc')
	// 		->get();

	// 	// Chuyển đổi múi giờ và định dạng thời gian cho mỗi TaskComment có reply_id khác 0
	// 	foreach ($data['taskCommentsWithReplys'] as $taskComment) {
	// 		$createdDate = \Carbon\Carbon::parse($taskComment->created_at)->setTimezone('Asia/Ho_Chi_Minh');
	// 		$taskComment->diffForHumansInVietnam = $createdDate->diffForHumans();
	// 	}

	// 	$data['taskCommentss'] = TaskComment::where('task_id', '=', $request->id)->get();

	// 	$data['commentCount'] = count($data['taskCommentss']);
	// 	return view('tasks.detail', $data);
	// }

	public function add(Request $request)
	{
		$data['users'] = User::get()->pluck('name', 'id')->toArray();

		$data['parentId'] = $request->parent_id;

		$data['parentTask'] = array();
		$data['taskCode'] = '';

		if ($data['parentId'] != 0) {
			$data['parentTask'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('id', '=', $data['parentId'])->get()->toArray();
		}

		$data['arProject'] = Projects::get()->pluck('name', 'id')->toArray();

		// dd($data);

		return view('tasks.add', $data);
	}

	public function edit(Request $request)
	{
		$data['task'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('id', '=', $request->id)->get()->toArray();

		$data['users'] = User::get()->pluck('name', 'id')->toArray();

		$data['arStatus'] = Tasks::STATUS;
		$data['arPriority'] = Tasks::PRIORITY;

		return view('tasks.edit', $data);
	}

	public function update(Request $request)
	{
		if (empty($request->task['id'])) {
			$taskId = Tasks::create(array(
				"project_id" => $request->task["project_id"],
				"parent_id" => $request->task["parent_id"],
				"name" => $request->task["name"],
				"description" => $request->task["description"],
				"assign_to" => $request->task["assign_to"],
				"approved_by" => $request->task["approved_by"],
				"due_date" => $request->task["due_date"],
				"task_value" => $request->task["task_value"],
				"priority" => $request->task["priority"],
				"status" => $request->task["status"],
			))->id;
		} else {
			$taskId = $request->task["id"];
			$task = Tasks::find($request->task["id"]);

			$task->name = $request->task["name"];
			$task->description = $request->task["description"];
			$task->assign_to = $request->task["assign_to"];
			$task->approved_by = $request->task["approved_by"];
			$task->task_value = $request->task["task_value"];
			$task->due_date = $request->task["due_date"];
			$task->priority = $request->task["priority"];
			$task->status = $request->task["status"];

			$task->save();
		}

		return redirect()->route('task.detail', ['id' => $taskId]);
	}
}
