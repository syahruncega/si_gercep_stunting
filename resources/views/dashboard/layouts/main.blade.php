<!doctype html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 5 admin template and web Application ui kit.">
    <meta name="keyword"
        content="LUNO, Bootstrap 5, ReactJs, Angular, Laravel, VueJs, ASP .Net, Admin Dashboard, Admin Theme, HRMS, Projects">
    <title>MOMS CARE TERINTEGRASI | @yield('title')</title>
    <link rel="icon" href="#" type="image/x-icon"> <!-- Favicon-->

    <!-- plugin css file  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard') }}/css/daterangepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/dashboard') }}/css/dataTables.min.css">

    <!-- project css file  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard') }}/css/luno.style.min.css">
</head>

<body class="layout-1" data-luno="theme-blue">

    @include('dashboard.layouts.sidebar')
    <!-- start: body area -->
    <div class="wrapper">

        @include('dashboard.layouts.header')

        <!-- start: page toolbar -->
        <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    @yield('breadcrumb')
                </div> 
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="fs-4 color-900 mt-1 mb-0">@yield('title')</h1>
                    </div>
                </div> 
            </div>
        </div>

        <!-- start: page body -->
        <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>


    <!-- Modal: my notes -->
    <div class="modal fade" id="MynotesModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-vertical modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header px-4">
                    <h5 class="modal-title">Catatan <span class="badge bg-danger ms-2">14</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="bg-light px-4 py-3">
                    <ul class="nav nav-pills nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#Notetab-all" role="tab"
                                aria-selected="true">All Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#Notetab-Business" role="tab"
                                aria-selected="false">Business</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#Notetab-Create" role="tab"><i
                                    class="fa fa-plus me-2"></i>New</a>
                        </li>
                    </ul>
                </div>
                <div class="modal-body px-4 custom_scroll">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade active show" id="Notetab-all" role="tabpanel">
                            <div class="card ribbon mb-2">
                                <div class="option-2 bg-primary position-absolute"></div>
                                <div class="card-body">
                                    <span class="text-muted">02 January 2021</span>
                                    <p class="lead">Give Review for design</p>
                                    <span>It has roots in a piece of classical Latin literature from 45 BC, making it
                                        over 2020 years old.</span>
                                </div>
                                <div class="card-footer pt-0 border-0">
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-star favourite-note"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-trash favourite-note"></i></a>
                                </div>
                            </div>
                            <div class="card ribbon mb-2">
                                <div class="option-2 bg-success position-absolute"></div>
                                <div class="card-body">
                                    <span class="text-muted">17 January 2022</span>
                                    <p class="lead">Give salary to employee</p>
                                    <span>The generated Lorem Ipsum is therefore always free from repetition</span>
                                </div>
                                <div class="card-footer pt-0 border-0">
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-star favourite-note"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-trash favourite-note"></i></a>
                                </div>
                            </div>
                            <div class="card ribbon mb-2">
                                <div class="option-2 bg-info position-absolute"></div>
                                <div class="card-body">
                                    <span class="text-muted">02 Fabruary 2020</span>
                                    <p class="lead">Launch new template</p>
                                    <span>Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis.</span>
                                </div>
                                <div class="card-footer pt-0 border-0">
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-star favourite-note"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-trash favourite-note"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Notetab-Business" role="tabpanel">
                            <div class="card ribbon mb-2">
                                <div class="option-2 bg-warning position-absolute"></div>
                                <div class="card-body">
                                    <span class="text-muted">10 December 2021</span>
                                    <p class="lead">Meeting with Mr.Lee</p>
                                    <span>Many desktop publishing packages and web page editors now use Lorem Ipsum as
                                        their default model</span>
                                </div>
                                <div class="card-footer pt-0 border-0">
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-star favourite-note"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-trash favourite-note"></i></a>
                                </div>
                            </div>
                            <div class="card ribbon mb-2">
                                <div class="option-2 bg-danger position-absolute"></div>
                                <div class="card-body">
                                    <span class="text-muted">01 December 2021</span>
                                    <p class="lead">Change a Design</p>
                                    <span> It has survived not only five centuries, but also the leap into
                                        electronic</span>
                                </div>
                                <div class="card-footer pt-0 border-0">
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-star favourite-note"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary" href="#"><i
                                            class="fa fa-trash favourite-note"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Notetab-Create" role="tabpanel">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" placeholder="Note Title">
                                <label>Note Title</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control datepicker" placeholder="Select Date">
                                <label>Select Date</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select" id="floatingSelect"
                                    aria-label="Floating label select example">
                                    <option selected>Open this select menu</option>
                                    <option value="1">Business</option>
                                    <option value="2">Social</option>
                                </select>
                                <label>Works with selects</label>
                            </div>
                            <div class="form-floating mb-4">
                                <textarea class="form-control" placeholder="Leave a comment here"
                                    style="height: 100px"></textarea>
                                <label>Leave a comment here</label>
                            </div>
                            <button type="button" class="btn btn-primary lift">Save note</button>
                            <button type="button" class="btn btn-white border lift"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal: Setting -->
    <div class="modal fade" id="SettingsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-vertical right-side modal-dialog-scrollable">
            <div class="modal-content">

                <div class="px-xl-4 modal-header">
                    <h5 class="modal-title">Theme Setting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="px-xl-4 modal-body custom_scroll">
                    <!-- start: Light/dark -->
                    <div class="card fieldset border setting-mode mb-4">
                        <span class="fieldset-tile bg-card">Light/Dark & Contrast Layout</span>
                        <div class="c_radio d-flex text-center">
                            <label class="m-1 theme-switch" for="theme-switch">
                                <input type="checkbox" id="theme-switch" />
                                <span class="card p-2">
                                    <img class="img-fluid"
                                        src="{{ asset('assets/dashboard') }}/images/dark-version.svg"
                                        alt="Dark Mode" />
                                </span>
                            </label>
                            <label class="m-1 theme-dark" for="theme-dark">
                                <input type="checkbox" id="theme-dark" />
                                <span class="card p-2">
                                    <img class="img-fluid"
                                        src="{{ asset('assets/dashboard') }}/images/dark-theme.svg"
                                        alt="Theme Dark Mode" />
                                </span>
                            </label>
                            <label class="m-1 theme-high-contrast" for="theme-high-contrast">
                                <input type="checkbox" id="theme-high-contrast" />
                                <span class="card p-2">
                                    <img class="img-fluid"
                                        src="{{ asset('assets/dashboard') }}/images/high-version.svg"
                                        alt="High Contrast" />
                                </span>
                            </label>
                            <label class="m-1 theme-rtl" for="theme-rtl">
                                <input type="checkbox" id="theme-rtl" />
                                <span class="card p-2">
                                    <img class="img-fluid"
                                        src="{{ asset('assets/dashboard') }}/images/rtl-version.svg" alt="RTL Mode!" />
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/dashboard') }}/bundles/libscripts.bundle.js"></script>

    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard') }}/bundles/apexcharts.bundle.js"></script>
    <script src="{{ asset('assets/dashboard') }}/bundles/daterangepicker.bundle.js"></script>
    <script src="{{ asset('assets/dashboard') }}/bundles/dataTables.bundle.js"></script>

    <!-- Jquery Page Js -->
    <script src="{{ asset('assets/dashboard') }}/js/page/dashboard.js"></script>
    <script>
        // date range picker

        
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                .format('YYYY-MM-DD'));
            });
        })
        
        // project data table
        $('.myDataTable')
        .addClass('nowrap')
        .dataTable({
            responsive: true,
            searching: true,
            paging: true,
            ordering: true,
            info: false,
        });
    </script>

    @yield('script')
</body>

</html>