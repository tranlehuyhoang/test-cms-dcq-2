@extends('layouts.app')
@section('content')

    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __('messages.task_detail') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dcq') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('task.index') }}">{{ __('messages.tasks') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('messages.task_detail') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            <h4 class="header-title"><?php echo $task[0]['name']?></h4>
                        </div>

                        <div class="card-body">
                            <form method="post" id="task_form" accept-charset="UTF-8" enctype="multipart/form-data" action="{{ route('task.update') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="task[id]" value="<?php echo $task[0]['id']?>">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.task_name') }}</label>
                                    <input type="text" name="task[name]" class="form-control" value="<?php echo $task[0]['name']?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.task_description') }}</label>
                                    <textarea style="display: none;" name="task[description]"></textarea>
                                    <div id="snow-editor" style="height: 300px;">
                                        <?php echo $task[0]['description']?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label class="form-label">{{ __('messages.task_due_date') }}</label>
                                        <input type="datetime-local" class="form-control" value="<?php echo \Illuminate\Support\Carbon::parse($task[0]['due_date'])->format('Y-m-d H:i')?>" name="task[due_date]">
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">{{ __('messages.task_task_value') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ __('messages.task_hour') }}</span>
                                            <input type="number" step="0.125" class="form-control" value="<?php echo $task[0]['task_value']?>" name="task[task_value]">
                                        </div>

                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label class="form-label">{{ __('messages.task_assign_to') }}</label>
                                        <select id="task_assign_to" class="form-control" name="task[assign_to]">
                                            <?php
                                            foreach ($users as $id => $name) {
                                                if ($id == $task[0]['assign_to']) {
                                                    $select = 'selected';
                                                } else {
                                                    $select = '';
                                                }
                                                ?>
                                                <option <?php echo $select?> value="<?php echo $id?>"><?php echo $name?></option>
                                                <?php
                                            };
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">{{ __('messages.task_approved_by') }}</label>
                                        <select id="task_approved_by" class="form-control" name="task[approved_by]">
                                            <?php
                                            foreach ($users as $id => $name) {
                                                if ($id == $task[0]['approved_by']) {
                                                    $select = 'selected';
                                                } else {
                                                    $select = '';
                                                }
                                                ?>
                                                <option <?php echo $select?> value="<?php echo $id?>"><?php echo $name?></option>
                                                <?php
                                            };
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    
                                    <div class="col-lg-6">
                                        <label class="form-label">{{ __('messages.task_priority') }}</label>
                                        <div class="d-flex">
                                            <?php
                                            $ind = 0;
                                            foreach ($arPriority as $code => $name) {
                                                $ind++;
                                                if ($code == $task[0]['priority']) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                                ?>
                                                <div class="form-check me-1">
                                                    <input class="form-check-input" type="radio" name="task[priority]" id="priority<?php echo $ind;?>" value='<?php echo $code;?>' <?php echo $checked?>>
                                                    <label class="form-check-label" for="priority<?php echo $ind;?>"><?php echo $name;?></label>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <label class="form-label">{{ __('messages.task_status') }}</label>
                                        <div class="d-flex">
                                            <?php
                                            $ind = 0;
                                            foreach ($arStatus as $code => $name) {
                                                $ind++;
                                                if ($code == $task[0]['status']) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                                ?>
                                                <div class="form-check me-1">
                                                    <input class="form-check-input" type="radio" name="task[status]" id="status<?php echo $ind;?>" value='<?php echo $code;?>' <?php echo $checked?>>
                                                    <label class="form-check-label" for="status<?php echo $ind;?>"><?php echo $name;?></label>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a class="btn btn-secondary waves-effect ms-2" href="{{ route('task.detail', $task[0]['id']) }}">Cancel</a>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->


            
            
        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>document.write(new Date().getFullYear())</script> &copy; Minton theme by <a href="">Coderthemes</a> 
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
