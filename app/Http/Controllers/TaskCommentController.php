<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function index()
    {
        // Lấy tất cả các task comments
        $taskComments = TaskComment::all();

        // Trả về view hiển thị danh sách task comments
        return view('task_comments.index', compact('taskComments'));
    }

    public function create()
    {
        // Trả về view để tạo mới task comment
        return view('task_comments.create');
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $taskcomment = new TaskComment();
        $taskcomment->create_by = $user_id;
        $taskcomment->task_id = $request->input('task_id');
        $taskcomment->content = $request->input('content');
        $taskcomment->reply_id = $request->input('reply_id');
        $taskcomment->save();

        // Truy vấn thông tin người dùng liên quan đến TaskComment
        $taskcomment->user = User::find($user_id);

        $createdDate = \Carbon\Carbon::parse($taskcomment->created_at)->setTimezone('Asia/Ho_Chi_Minh');
        $taskcomment->diffForHumansInVietnam = $createdDate->diffForHumans();

        // Kiểm tra xem có TaskComment nào có id giống với reply_id không
        $hasReply_id_level_2 = TaskComment::where('id', $request->input('reply_id'))->first();
        $hasReply_id_level_3 = $hasReply_id_level_2 ? TaskComment::where('id', $hasReply_id_level_2->reply_id)->exists() : false;
        $taskcomment->can_reply = $hasReply_id_level_3;
        $html = view('taskcomment.comment', ['taskComment' => $taskcomment])->render();

        // Trả về dữ liệu TaskComment đã tạo thành công
        return response()->json(['html' => $html], 200);
    }

    public function getcommentlevel2(Request $request)
    {
        $taskCommentId = $request->input('taskCommentId');

        // Xử lý logic để xem taskCommentId và trả về kết quả phản hồi

        // Ví dụ:

        $data['taskComments'] = TaskComment::with('user')
            ->where('reply_id', $taskCommentId)
            ->orderBy('created_at', 'desc')
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

        $taskComment = TaskComment::find($taskCommentId);

        $data['html'] = view('taskcomment.comment_level_2', ['taskComments' => $data['taskComments']])->render();
        if ($taskComment) {
            $replyComments = $taskComment->replyComments;
            return response()->json(['replyComments' => $data], 200);
        } else {
            return response()->json(['message' => 'Task comment not found'], 404);
        }
    }


    public function getcommentlevel3(Request $request)
    {
        $taskCommentId = $request->input('taskCommentId');

        $taskComments = TaskComment::with('user')
            ->where('reply_id', $taskCommentId)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($taskComments as $taskComment) {
            $createdDate = $taskComment->created_at->setTimezone('Asia/Ho_Chi_Minh');
            $taskComment->diffForHumansInVietnam = $createdDate->diffForHumans();
            $taskComment->replyCount = TaskComment::where('reply_id', $taskComment->id)->count();
        }

        if ($taskComments->isEmpty()) {
            return response()->json(['message' => 'Task comment not found'], 404);
        }

        $replyComments = TaskComment::find($taskCommentId)->replyComments;
        $html = view('taskcomment.comment_level_3', ['taskComments' => $taskComments])->render();

        return response()->json(['taskComments' => $taskComments, 'replyComments' => $replyComments, 'html' => $html], 200);
    }
    public function pagination(Request $request)
    {
        $id_pagination = $request->input('pageId');
        $task_id = $request->input('taskId');

        $taskComments = TaskComment::with('user')
            ->where('task_id', $task_id)
            ->where('reply_id', TaskComment::DEFAULT_COMMENTS)
            ->orderBy('created_at', TaskComment::SORT_COMMENTS)
            ->skip($id_pagination * TaskComment::LIMIT_COMMENTS)
            ->take(TaskComment::LIMIT_COMMENTS)
            ->get();

        foreach ($taskComments as $taskComment) {
            $createdDate = $taskComment->created_at->setTimezone('Asia/Ho_Chi_Minh');
            $taskComment->diffForHumansInVietnam = $createdDate->diffForHumans();
            $taskComment->replyCount = TaskComment::where('reply_id', $taskComment->id)->count();
        }

        if ($taskComments->isEmpty()) {
            return response()->json(['message' => 'Task comment not found'], 404);
        }

        // Render view và trả về HTML
        $html = view('taskcomment.pagination', ['taskComments' => $taskComments])->render();

        return response()->json(['html' => $html], 200);
    }

    public function show(TaskComment $taskComment)
    {
        // Trả về view hiển thị chi tiết task comment
        return view('task_comments.show', compact('taskComment'));
    }

    public function edit(TaskComment $taskComment)
    {
        // Trả về view để chỉnh sửa task comment
        return view('task_comments.edit', compact('taskComment'));
    }

    public function update(Request $request, TaskComment $taskComment)
    {
        // Kiểm tra và cập nhật thông tin task comment vào cơ sở dữ liệu
        $taskComment->fill($request->all());
        $taskComment->save();

        // Chuyển hướng về trang danh sách task comments hoặc trang chi tiết task comment vừa cập nhật
        return redirect()->route('task_comments.index');
    }

    public function destroy(TaskComment $taskComment)
    {
        // Xóa task comment khỏi cơ sở dữ liệu
        $taskComment->delete();

        // Chuyển hướng về trang danh sách task comments
        return redirect()->route('task_comments.index');
    }
}
