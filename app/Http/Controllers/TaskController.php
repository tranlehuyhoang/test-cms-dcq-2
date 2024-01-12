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

		$data['arRoles'] = Roles::whereIn('code', [User::ADMIN, User::MANAGER])->get()->pluck('name', 'id')->toArray();
		$data['arTasks'] = Tasks::with('tasksAssignTo')->with('tasksCreatedBy')->with('tasksApprovedBy')->where('parent_id', '=', 0)->get()->keyBy('id')->toArray();

		foreach ($data['arTasks'] as $taskId => $task) {
			// Kiểm tra xem có nhiệm vụ con với parent_id = task.id hay không
			$hasChildren = Tasks::where('parent_id', '=', $taskId)->exists();

			// Gán giá trị biến kiểm tra `hasChildren` vào nhiệm vụ hiện tại
			$data['arTasks'][$taskId]['hasChildren'] = $hasChildren;

			// Calculate the level of the current task
			$level = 0;
			$parentId = $task['parent_id'];
			while ($parentId != 0) {
				$level++;
				$parentId = Tasks::where('id', $parentId)->value('parent_id');
			}
			$data['arTasks'][$taskId]['level'] = $level;
		}

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
	public function get_child_tasks(Request $request)
	{
		$id = $request->input('id');
		$arTasks = Tasks::with('tasksAssignTo')
			->with('tasksCreatedBy')
			->with('tasksApprovedBy')
			->where('parent_id', $id)
			->get()
			->keyBy('id')
			->toArray();

		foreach ($arTasks as $taskId => $task) {
			// Check if there are child tasks with parent_id = $taskId
			$hasChildren = Tasks::where('parent_id', $taskId)->exists();

			// Assign the value of `hasChildren` to the current task
			$arTasks[$taskId]['hasChildren'] = $hasChildren;

			// Calculate the level of the current task
			$level = 0;
			$parentId = $task['parent_id'];
			$parentIds = [];
			while ($parentId != 0) {
				$level++;
				$parentId = Tasks::where('id', $parentId)->value('parent_id');
				$parentIds[] = $parentId;
			}
			$arTasks[$taskId]['level'] = $level;
			$arTasks[$taskId]['parentIds'] = array_reverse($parentIds);
		}

		$html = view('tasks.render_child_tasks', ['arTasks' => $arTasks])->render();

		return response()->json(['html' => $html], 200);
	}
}
