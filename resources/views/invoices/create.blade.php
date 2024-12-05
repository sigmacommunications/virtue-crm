@extends('layouts.app')

{{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
@section('content')
    <style>
        .content-header h1 {
            color: #007bff;
        }

        .breadcrumb {
            background-color: #e9ecef;
            padding: 8px;
            border-radius: 4px;
        }


        .card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-top: 20px;
        }


        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header img {
            max-width: 100px;
            margin-top: 10px;
        }

        article,
        aside {
            padding: 20px;
        }

        .meta,
        .inventory,
        .balance {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .meta th,
        .meta td,
        .inventory th,
        .inventory td,
        .balance th,
        .balance td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        .meta th,
        .inventory th,
        .balance th {
            background-color: #f8f9fa;
        }

        .cut,
        .add {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
            cursor: pointer;
            margin-left: 5px;
            border-radius: 4px;
        }

        .address {
            display: inline-block;
            vertical-align: top;
            /* Align the address to the top of the line */
        }

        .meta {
            display: inline-block;
            vertical-align: top;
            /* Align the table with the address */
            margin-left: 148px;
            /* Add some space between the address and the table */
        }

        aside {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
        }

        aside h1 {
            color: #007bff;
        }

        .comment-section {
            position: relative;
        }

        .toggle-comment-btn {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
            cursor: pointer;
            margin-left: 5px;
            border-radius: 4px;
            border: none;
        }

        .comment-field {
            margin-top: 10px;
            text-align: justify;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h3>INVOICE</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Customer Invoice</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <form method="post" action="{{ route('invoices.store') }}">
                                    @csrf
                                    <article>
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Agents</label>
                                                    <select name="user_id" class="form-control">
                                                        <option value="0">Select Agent</option>
                                                        @foreach ($users as $agent)
                                                            <option value="{{ $agent->id }}">{{ $agent->name }}
                                                                - {{ $agent->email }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Company</label>
                                                    <select name="company" class="form-control">
                                                        <option value="0" selected hidden>Select Company</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->id }}">{{ $company->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Customer</label>
                                                    <select name="client_id" class="form-control">
                                                        <option value="0">Select Customer</option>
                                                        @foreach ($clients as $cl)
                                                            <option value="{{ $cl->id }}">
                                                                {{ $cl->company_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Customer Notes</label>
                                                    <textarea class="form-control"></textarea>
                                                </div>

                                                {{-- <div class="comment-section">
                        <div class="comment-field" >
                            <input type="text" name="comment" class="form-control" style="display: none;"></input>
                            <input type="text" name="lead_id" class="form-control" value="{{ isset($invoice->lead_id) ? $invoice->lead_id : '' }}"></input>
                            </div>
                    </div> --}}
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Payment Method</label>
                                                    <select name="payment_method" class="form-control">
                                                        <option value="0">Select Payment Method</option>
                                                        @foreach ($pms as $pm)
                                                            <option value="{{ $pm->id }}">{{ $pm->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <table class="meta">
                                                    <tr>
                                                        <th><span>Invoice #</span></th>
                                                        <td><span>{{ $invoice_id }}</span></td>
                                                        <input type="hidden" value="{{ $invoice_id }}"
                                                            name="invoice_id" />
                                                    </tr>
                                                    <tr>
                                                        <th><span>Date</span></th>
                                                        <td><span>{{ now()->format('F j, Y') }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span>Amount Due</span></th>
                                                        <td><span id="prefix">$</span><span id="amount-due">0.00</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <table class="inventory" id="invoice-items">
                                            <thead>
                                                <tr>
                                                    <th><span>Item</span></th>
                                                    <th><span>Description</span></th>
                                                    <th><span>Rate</span></th>
                                                    <th><span>Quantity</span></th>
                                                    <th><span>Discount</span></th>
                                                    <th><span>Tax</span></th>
                                                    <th><span>Total Price</span></th>
                                                    <th><span></span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="append-row">
                                                    <td>
                                                        <select name="product[]" class="form-control">
                                                            @foreach ($products as $pro)
                                                                <option value="{{ $pro->id }}">
                                                                    {{ $pro->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input name="description[]" class="form-control"></input>
                                                    </td>
                                                    <td><input type="number" name="amount[]" class="form-control"></input></td>
                                                    <td><input type="number" name="qty[]" class="form-control"></input></td>
                                                    <td><input type="number" name="discount[]" class="form-control"></input></td>
                                                    <td>
                                                        <select class="form-control tax-select" name="tax[]">
                                                            <option value="">Select a Tax</option>
                                                            @foreach ($taxes as $tax)
                                                                <option value="{{ $tax->id }}">{{ $tax->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input name="total_amount[]" class="form-control" readonly></input>
                                                    </td>
                                                    <td><a class="cut">-</a></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <br />
                                        <a class="add" id="add-item">+</a>

                                        <table class="balance">
                                            <tr>
                                                <th><span>Total</span></th>
                                                <td><span id="overall-total">0.00</span></td>
                                            </tr>
                                            {{-- <tr>
                                                <th><span >Amount Paid</span></th>
                                                <td><input name="amount_paid" id="amount_paid"
                                                        class="form-control"></input></td>
                                            </tr> --}}
                                            <tr>
                                                <th><span>Balance Due</span></th>
                                                <td><span id="balance-due">0.00</span></td>
                                            </tr>
                                        </table>

                                    </article>

                                    <aside>
                                        <h1><span>Additional Notes</span></h1>
                                        <div>
                                            <textarea class="form-control h-100" cols="9" name="additional_text">A finance charge of 1.5% will be made on unpaid balances after 30 days.</textarea>
                                        </div>
                                    </aside>

                                    <button type="submit" class="btn btn-primary">Mark Convert</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
    @endsection
    @section('scripts')
        <script>
            $(document).ready(function() {
                const taxes = JSON.parse('@php echo json_encode($taxes); @endphp');
                const invoiceItems = $('#invoice-items tbody');
                const amountPaidInput = $('#amount_paid');
                const balanceDueSpan = $('#balance-due');
                const amountDueSpan = $('#amount-due');
                const prefixSpan = $('#prefix');

                // Set initial values
                amountDueSpan.text('0.00');

                function updateTotal() {
                    let total = 0;

                    invoiceItems.find('tr').each(function() {
                        const price = parseFloat($(this).find('[name="amount[]"]').val()) || 0;
                        const quantity = parseInt($(this).find('[name="qty[]"]').val()) || 0;
                        const discount = parseInt($(this).find('[name="discount[]"]').val()) || 0;
                        const tax_id = parseInt($(this).find('[name="tax[]"]').val()) || 0;
                        const tax = taxes.find((element) => id = tax_id);
                        let tax_amount = 0;
                        // debugger
                        if (tax) {
                            tax_amount = (price * quantity) / 100 * tax.percentage;
                        }


                        const totalAmount = ((price * quantity) + tax_amount) - discount;
                        $(this).find('[name="total_amount[]"]').val(totalAmount.toFixed(2));



                        total += totalAmount;
                    });

                    $('.balance td:last-child span').text(total.toFixed(2));
                    updateBalance();
                }

                function updateBalance() {
                    const total = parseFloat($('.balance td:last-child span').text()) || 0;
                    // debugger;
                    const amountPaid = parseFloat(amountPaidInput.val()) || 0;

                    const balanceDue = total - amountPaid;
                    balanceDueSpan.text(balanceDue.toFixed(2));
                    amountDueSpan.text(balanceDue.toFixed(2));
                    prefixSpan.text('$'); // Set or update the prefix
                }

                $('#add-item').on('click', function() {
                    const newRow =
                        `<tr><td><select name="product[]" class="form-control">@foreach ($products as $pro)<option value="{{ $pro->id }}">{{ $pro->name }}</option>@endforeach</select></td><td><input name="description[]" class="form-control"></td><td><input name="amount[]" class="form-control"></td><td><input name="qty[]" class="form-control"></td>
                           <td><input name="discount[]" class="form-control"></td>
                             <td>
                                <select class="form-control tax-select">
                                    <option value="">Select a Tax</option>
                                    @foreach ($taxes as $tax)
                                        <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input name="total_amount[]" class="form-control" readonly></td><td><a class="cut">-</a></td></tr>`;

                    invoiceItems.append(newRow);
                    updateTotal();
                });

                invoiceItems.on('click', '.cut', function() {
                    $(this).closest('tr').remove();
                    updateTotal();
                });

                invoiceItems.on('input', 'input[name="amount[]"], input[name="qty[]"] , input[name="discount[]"]',
                    updateTotal);
                amountPaidInput.on('input', updateBalance);
            });


            $('.toggle-comment-btn').on('click', function() {
                $('.comment-field').toggle();
            });
        </script>
    @endsection
