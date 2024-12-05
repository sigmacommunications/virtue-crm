<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Virtue CRM</title>
    <!-- plugins:css -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/summernote.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('/admin') }}/vendors/iconfonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/admin') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('/admin') }}/vendors/css/vendor.bundle.addons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('/admin') }}/css/style.css">
    <!-- toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('/admin') }}/vendors/summernote/dist/summernote-bs4.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="http://www.urbanui.com/" />

    <!-- Gallery -->
    <link rel="stylesheet" href="{{ asset('/admin') }}/vendors/lightgallery/css/lightgallery.css">
    <style>
        .sidebar .nav .nav-item.active>.nav-link:hover i,
        .sidebar .nav .nav-item.active>.nav-link:hover span {
            color: #222b34 !important;
        }


        .sidebar .nav:not(.sub-menu)>.nav-item:hover:not(.nav-profile)>.nav-link {
            background: #f6f6f6;
            color: #222b34;
        }

        .sidebar .nav .nav-item .nav-link:hover i.menu-icon {
            color: #222b34;
        }

        .sidebar .nav .nav-item .nav-link:hover i.menu-arrow:before {
            color: #222b34;
        }

        .sidebar .nav .nav-item .nav-link i.menu-arrow:before {
            color: #f6f6f6;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
    @php use  Rmunate\Utilities\SpellNumber; @endphp

    <div class="container-scroller">
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background-color: #f5f5f5;
                color: #333;
            }

            .invoice-box {
                max-width: 800px;
                margin: 30px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .invoice-title {
                margin-bottom: 30px;
            }

            .invoice-title h2,
            .invoice-title h3 {
                margin: 0;
                padding: 0;
            }

            h2 {
                font-weight: 500;
                font-size: 18px;
            }

            h3 {
                font-weight: 400;
                font-size: 14px;
                color: #777;
            }

            address {
                font-weight: 400;
                font-size: 12px;
            }

            .divider {
                margin: 20px 0;
            }

            .table-responsive {
                margin-bottom: 30px;
            }

            .total-row {
                font-weight: 700;
            }

            .table thead th {
                font-size: 12px;
                background-color: #f5f5f5;
            }

            .table tbody tr {
                border-bottom: 1px solid #ddd;
                font-size: 12px;

            }

            .text-right {
                text-align: right;
            }

            .text-left {
                text-align: left;
            }

            .text-center {
                text-align: center;
            }

            .mb-0 {
                margin-bottom: 0;
            }

            .invoice-box p {
                margin: 0 0 10px;
            }

            .invoice-box strong {
                font-weight: 500;
            }

            .invoice-summary p {
                font-size: 12px;
            }
        </style>
        </style>

        <div class="content-wrapper">

            <div class="container invoice-box">
                <div class="row justify-content-end mb-4">
                    <a class="btn btn-primary btn-sm" href="{{ route('invoices.payment', $invoice->id) }}">Paynow</a>
                </div>
                <div class="row invoice-title">
                    <div class="col-sm-6">
                        @if ($invoice->companyLogo)
                            <img src="{{ asset('storage/' . $invoice->companyLogo->logo) }}" alt="Company Logo"
                                class="img-fluid" style="max-width: 150px;">
                        @else
                            <h1>LOGO HERE</h1>
                        @endif
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="mb-3">
                            <h2>INVOICE</h2>
                            <h3>Invoice# {{ $invoice->invoice_id }}</h3>
                        </div>
                        <div>
                            <strong>Balance Due:</strong>
                            <h3>${{ $invoice->total_amount }}</h3>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row p-3">
                    <div class="col-sm-6">
                        <address>
                            <strong>Bill To:</strong><br>
                            {{ $invoice->client->description }}
                        </address>
                    </div>
                    <div class="col-sm-6 text-right">
                        <address>
                            <strong>Invoice Date:</strong> {{ date('d-M-Y', strtotime($invoice->created_at)) }}<br>
                            <strong>Due Date:</strong> {{ date('d-M-Y', strtotime($invoice->created_at)) }}<br>
                            {{-- <strong>P.O.#:</strong> SO-17<br>
                          <strong>Project Name:</strong> Design project --}}
                        </address>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item & Description</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Rate</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Tax %</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sub_total = 0;
                                    $total_discount = 0;
                                    $total_tax = 0;
                                @endphp
                                @foreach ($invoice->details as $k => $detail)
                                    <tr>
                                        <td>{{ ++$k }}</td>
                                        <td>
                                            {{ $detail->product_detail->name }}
                                            <br>
                                            <small>{{ $detail->description }}</small>
                                        </td>
                                        <td class="text-right"> {{ $detail->qty }}</td>
                                        <td class="text-right">${{ $detail->amount }}</td>
                                        <td class="text-right">${{ $detail->discount }}</td>
                                        <td class="text-right">{{ $detail->tax_detail->percentage ?? 0 }} %</td>
                                        <td class="text-right">
                                            ${{ $sub_total += $detail->amount * $detail->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row p-3  flex-row-reverse">

                    <div class="col-sm-6 text-right invoice-summary">
                        <p class="total-row">Sub Total: ${{ $sub_total }}</p>
                        @foreach ($invoice->details as $k => $detail_tax)
                            @if ($detail_tax->tax > 0)
                                <p class="total-row">{{ $detail_tax->tax_detail->name }}
                                    ({{ $detail_tax->tax_detail->percentage }}%)
                                    :
                                    ${{ $total_tax += (($detail->amount * $detail->qty) / 100) * $detail_tax->tax_detail->percentage }}
                                </p>
                            @endif
                        @endforeach
                        <p class="total-row">Discount Amount: ${{ $invoice->details->sum('discount') }}</p>
                        <p class="total-row total">Total: $
                            {{ $sub_total + $total_tax - $invoice->details->sum('discount') }}</p>
                        {{-- <p class="total-row">Payment Made (-): $100.00</p> --}}
                        {{-- <p class="total-row total">Balance Due: $562.75</p> --}}
                    </div>
                    <div class="col-sm-6">
                        <p><strong>Total In Words:</strong> United States Dollar
                            {{ SpellNumber::value($sub_total + $total_tax - $invoice->details->sum('discount'))->locale('en')->toLetters() }}
                        </p>
                        <p><strong>Notes:</strong> {{ $invoice->comment ?? 'Thanks for your business.' }}.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <hr>
                        <p><strong>Terms & Conditions</strong></p>
                        <p> {{ $invoice->tnc ?? "Your company's Terms and Conditions will be displayed here. You can add it in the Invoice Preferences page under Settings" }}.
                        </p>
                        {{-- <p><strong>Payment Options</strong></p>
                      <p>[Payment options details]</p> --}}
                    </div>
                </div>
            </div>

        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    <!-- plugins:js -->
    <script src="{{ asset('/admin') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="{{ asset('/admin') }}/vendors/js/vendor.bundle.addons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <!-- endinject -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ asset('/admin') }}/js/off-canvas.js"></script>
    <script src="{{ asset('/admin') }}/js/hoverable-collapse.js"></script>
    <script src="{{ asset('/admin') }}/js/misc.js"></script>
    <script src="{{ asset('/admin') }}/js/settings.js"></script>
    <script src="{{ asset('/admin') }}/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Gallery-->
    <script src="{{ asset('/admin') }}/vendors/lightgallery/js/lightgallery-all.min.js"></script>
    <script src="{{ asset('/admin') }}/js/light-gallery.js"></script>
    <!-- Custom js for this page-->
    <script src="{{ asset('/admin') }}/js/dashboard.js"></script>
    <!--Data table-->
    <script src="{{ asset('/admin') }}/js/data-table.js"></script>
    <!-- Summernote -->
    <script src="{{ asset('/admin') }}/vendors/summernote/dist/summernote-bs4.min.js"></script>

    @stack('js')
    <!-- select 2 -->
    <script src="{{ asset('/admin') }}/js/select2.js"></script>


    <script src="{{ asset('/admin') }}/js/file-upload.js"></script>
    <script src="{{ asset('/admin') }}/js/typeahead.js"></script>
</body>
<script>
    function assign_users() {
        $.ajax({
            url: "{{ route('user.fetch') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                jQuery.each(data.users, function(index, item) {
                    $('<option/>').val(item.id).text(item.name).appendTo('#assign_user');
                });
            }
        });
    }

    function assign_clients() {
        $.ajax({
            url: "{{ route('clients.fetch') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                jQuery.each(data.clients, function(index, item) {
                    $('<option/>').val(item.id).text(item.company_name).appendTo('#assign_client');
                });
            }
        });
    }

    function assign_projects() {
        $.ajax({
            url: "{{ route('projects.fetch') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                jQuery.each(data.projects, function(index, item) {
                    $('<option/>').val(item.id).text(item.name).appendTo('#assign_project');
                });
            }
        });
    }


    assign_users();
    assign_clients();
    assign_projects();
</script>



<script>
    $(document).ready(function() {
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });
</script>
<script>
    $(function() {
        $(".new-item-drop, .complete-item-drop, .process-item-drop, .test-item-drop").sortable({
            connectWith: ".connectedSortable",
            opacity: 0.5,
            receive: function(event, ui) {
                // $(".container").css("background-color", "red");
            }
        }).disableSelection();

        $(".connectedSortable").on("sortupdate", function(event, ui) {
            var panddingArr = [];
            var completeArr = [];

            $(".new-item-drop #wrapper").each(function(index) {
                panddingArr[index] = $(this).attr('item-id');
            });
            $(".process-item-drop #wrapper").each(function(index) {
                panddingArr[index] = $(this).attr('item-id');
            });
            $(".test-item-drop #wrapper").each(function(index) {
                panddingArr[index] = $(this).attr('item-id');
            });

            $(".complete-item-drop #wrapper").each(function(index) {
                completeArr[index] = $(this).attr('item-id');
            });

            /* $.ajax({
                url: "{{ route('task.update', 1) }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {panddingArr:panddingArr,completeArr:completeArr},
                success: function(data) {
                console.log('success');
                }
            }); */

        });
    });
</script>

</html>
