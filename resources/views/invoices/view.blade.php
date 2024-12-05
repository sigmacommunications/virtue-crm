@extends('layouts.app')
@php use  Rmunate\Utilities\SpellNumber; @endphp

{{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
@section('content')
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

    <div class="main-panel">
        <div class="content-wrapper">

            <div class="container invoice-box">
                <div class="row justify-content-end d-flex mb-4">
                    <a class="btn btn-primary btn-sm" href="{{ route('invoices.payment', $invoice->id) }}">Paynow</a>
                    <a class="btn btn-info btn-sm mx-2" href="javascript:void(0);"
                        onclick="copyInvoiceLink('{{ route('invoices.invoice', $invoice->id) }}')">Copy Link</a>
                </div>
                <div class="row invoice-title">
                    <div class="col-sm-6">
                        @if ($invoice->companyLogo->logo)
                            <img src="{{ asset('storage/' . $invoice->companyLogo->logo) }}" alt="Company Logo" class="img-fluid"
                                style="max-width: 150px;">
                        @else
                            <h1>{{$invoice->companyLogo->title}}</h1>
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
                                        <td class="text-right">${{ $sub_total += $detail->amount * $detail->qty }}</td>
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
    @endsection
    @section('scripts')
        <script>
            function copyInvoiceLink(url) {
                var tempInput = document.createElement("textarea");
                tempInput.value = url;
                document.body.appendChild(tempInput);

                tempInput.select();
                document.execCommand("copy");

                document.body.removeChild(tempInput);

                toastr.success("Link copied to clipboard!");
            }
        </script>
    @endsection
