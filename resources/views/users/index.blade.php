@extends('layouts.app')
@section('content')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __('messages.user_member_list') }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="">{{ __('messages.dcq') }}</a></li>
                                <li class="breadcrumb-item active">{{ __('messages.user_member_list') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>     
            <!-- end page title --> 

            <div class="row mb-2">
                <div class="col-sm-4">
                    <a href="{{ route('user.add') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add New</a>
                </div>
                <div class="col-sm-8">
                    <div>
                        <form class="d-flex align-items-start flex-wrap justify-content-sm-end">
                            <div class="d-flex align-items-start flex-wrap me-2">
                                <label for="membersearch-input" class="visually-hidden">Search</label>
                                <input type="search" class="form-control" id="membersearch-input" placeholder="Search...">
                            </div>
                            <button type="button" class="btn btn-success mb-2 mb-sm-0"><i class="mdi mdi-cog"></i></button>
                        </form>
                        
                    </div>
                </div><!-- end col-->
            </div>
            <!-- end row -->

            <div class="row">
                <?php
                foreach ($arUsers as $key => $value) {
                    ?>
                    <div class="col-xl-3 col-sm-6">
                        <div class="text-center card">
                            <div class="card-body">

                                <div class="dropdown float-end">
                                    <a class="text-body dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical font-20"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                                <?php 
                                    foreach($avatarUsers[$value['id']] as $key => $avatar) {
                                ?>
                                <img src="<?php echo $avatar->getFullUrl(); ?>" class="rounded-circle img-thumbnail avatar-xl mt-1" alt="profile-image">
                                <?php       
                                    }
                                ?>

                                <h4 class="mt-3 mb-1"><a href="{{ route('user.edit', $value['id']) }}" class="text-dark"><?php echo $value['name']?></a></h4>
                                <p class="text-muted">@Role <span> | </span> <span> <a href="{{ route('user.edit', $value['id']) }}" class="text-pink"><?php echo $value['user_role']['name']?></a> </span></p>

                                <ul class="social-list list-inline mt-4 mb-2">
                                    <li class="list-inline-item">
                                        <a href="<?php echo $value['facebook']?>" class="social-list-item border-purple text-purple" target='_blank'><i class="mdi mdi-facebook"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github"></i></a>
                                    </li>
                                </ul>

                            </div>
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                    <?php
                }
                ?>
                
            </div>
            <!-- end row -->

            <div class="row mb-4">
                <div class="col-sm-6">
                    <div>
                        <h5 class="font-14 text-body">Showing Page 2 Of 12</h5>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-end">
                        <ul class="pagination pagination-rounded mb-sm-0">
                            <li class="page-item disabled">
                                <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="page-item active">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">4</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">5</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                            </li>
                        </ul>
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
