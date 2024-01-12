@extends('layouts.app')
@section('title')
    {{ __('messages.tasks') }}
@endsection
@section('content')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __('messages.tasks') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('messages.dcq') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('messages.tasks') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <!-- cta -->
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <a href="{{ route('task.add', 0) }}"
                                                class="btn btn-primary waves-effect waves-light"><i
                                                    class='fe-plus me-1'></i>Add New</a>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="float-sm-end mt-3 mt-sm-0">
                                                <div class="d-flex align-items-start flex-wrap">
                                                    <div class="mb-3 mb-sm-0 me-sm-2">
                                                        <form>
                                                            <div class="position-relative">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Search...">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-light dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="mdi mdi-filter-variant"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="#">Due Date</a>
                                                            <a class="dropdown-item" href="#">Added Date</a>
                                                            <a class="dropdown-item" href="#">Assignee</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="custom-accordion">
                                        <?php
                                        foreach ($arProject as $idProject => $nameProject) {
                                            ?>
                                        <div class="mt-4">
                                            <h5 class="position-relative mb-0"><a
                                                    href="{{ route('project.edit', $idProject) }}"
                                                    target="_blank"><?php echo $nameProject; ?> </a> <a
                                                    href="#taskcollapse<?php echo $idProject; ?>" class="text-dark"
                                                    data-bs-toggle="collapse"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></a></h5>
                                            <div class="collapse show" id="taskcollapse<?php echo $idProject; ?>">
                                                <div class="table-responsive mt-3">
                                                    <table
                                                        class="table table-centered table-nowrap table-borderless table-sm">
                                                        <thead class="table-light">
                                                            <tr class="">
                                                                <th scope="col">
                                                                    Task ID
                                                                </th>
                                                                <th scope="col">Tasks</th>
                                                                <th scope="col">Assign to</th>
                                                                <th scope="col">Due Date</th>
                                                                <th scope="col">Priority</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col" style="width: 85px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($arTasks as $key => $value) {
                                                                    if ($value['project_id'] != $idProject) {
                                                                        continue;
                                                                    }
                                                                    if ($value['priority'] == 'hight') {
                                                                        $priority = 'badge-soft-danger';
                                                                    } elseif ($value['priority'] == 'medium') {
                                                                        $priority = 'badge-soft-info';
                                                                    } else {
                                                                        $priority = 'badge-soft-success';
                                                                    }

                                                                    if ($value['status'] == 'complete') {
                                                                        $classStatus = 'complete-task';
                                                                        $priority = '';
                                                                    } else {
                                                                        $classStatus = '';
                                                                    }
                                                                    
                                                                    

                                                                    ?>
                                                            <tr id="child_{{ $value['id'] }}"
                                                                class="child_tasks_<?php echo $value['parent_id']; ?> child_tasks_level_<?php echo $value['level']; ?>">
                                                                <td>
                                                                    <span class="ms-0">
                                                                        {!! $value['hasChildren']
                                                                            ? '<i class="fe-chevron-right" onclick="showChildTasks(this, ' .
                                                                                "'" .
                                                                                $value['id'] .
                                                                                "'" .
                                                                                ')" id="' .
                                                                                $value['id'] .
                                                                                '"></i>'
                                                                            : '' !!}
                                                                    </span>
                                                                    <label class="form-check-label <?php echo $classStatus; ?>"
                                                                        for="tasktodayCheck01">#<?php echo $value['id']; ?></label>
                                                                </td>
                                                                <td>
                                                                    <a class="<?php echo $classStatus; ?>"
                                                                        href="{{ route('task.detail', $value['id']) }}"><?php echo $value['name']; ?></a>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <img src="/assets/images/users/avatar-2.jpg"
                                                                            alt="image"
                                                                            class="avatar-sm img-thumbnail rounded-circle"
                                                                            title="Houston Fritz" />
                                                                        <a class="<?php echo $classStatus; ?>"
                                                                            href="{{ route('user.detail', $value['tasks_assign_to']['id']) }}"><?php echo $value['tasks_assign_to']['name']; ?></a>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="<?php echo $classStatus; ?>"><?php echo \Illuminate\Support\Carbon::parse($value['due_date'])->format('d/m/Y H:i'); ?></span>
                                                                </td>
                                                                <td><span
                                                                        class="badge <?php echo $priority; ?> p-1 <?php echo $classStatus; ?>"><?php echo $value['priority']; ?></span>
                                                                </td>
                                                                <td>
                                                                    <span class="<?php echo $classStatus; ?>">
                                                                        <?php echo $value['status']; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <ul class="list-inline table-action m-0">
                                                                        <li class="list-inline-item">
                                                                            <a href="{{ route('task.edit', $value['id']) }}"
                                                                                class="action-icon px-1"> <i
                                                                                    class="mdi mdi-square-edit-outline"></i></a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <div class="dropdown">
                                                                                <a class="action-icon px-1 dropdown-toggle"
                                                                                    href="#" data-bs-toggle="dropdown"
                                                                                    aria-haspopup="true"
                                                                                    aria-expanded="false">
                                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                                </a>

                                                                                <div
                                                                                    class="dropdown-menu dropdown-menu-end">
                                                                                    <a class="dropdown-item"
                                                                                        href="#">Action</a>
                                                                                    <a class="dropdown-item"
                                                                                        href="#">Another action</a>
                                                                                    <a class="dropdown-item"
                                                                                        href="#">Something else
                                                                                        here</a>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </td>

                                                            </tr>

                                                            <?php
                                                                }
                                                                ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <script>
                                        showChildTasks = function(element, id) {

                                            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                            var element = $('i.fe-chevron-right#' + id);

                                            if (element.hasClass('fe-chevron-right')) {
                                                $.ajax({
                                                    url: '{{ route('task.get_child_tasks') }}',
                                                    method: 'POST',
                                                    data: {
                                                        id: id,
                                                        _token: '{{ csrf_token() }}' // Thêm mã CSRF vào yêu cầu
                                                    },
                                                    success: function(response) {
                                                        var child_tasks = response.html;
                                                        $('#child_' + id).after(child_tasks);

                                                        element.removeClass('fe-chevron-right').addClass('fe-chevron-down');
                                                    },
                                                    error: function(xhr, status, error) {
                                                        // Xử lý lỗi
                                                        console.error(error);
                                                    }
                                                });
                                            } else {
                                                var element = $('i.fe-chevron-down#' + id);

                                                element.removeClass('fe-chevron-down').addClass('fe-chevron-right');
                                                $('tr.child_tasks_' + id).remove();
                                            }

                                        }
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->

    </div> <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; Minton theme by <a href="">Coderthemes</a>
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-sm-block">
                        <a href="javascript:void(0);">About Us</a>
                        <a href="javascript:void(0);">Help</a>
                        <a href="javascript:void(0);">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
@endsection
