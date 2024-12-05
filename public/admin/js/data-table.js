(function($) {
  'use strict';
  $(function() {
    $('#order-listing').DataTable({
      "aLengthMenu": [
        [5, 10, 15, -1],
        [5, 10, 15, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      }
    });
    $('#order-listing').each(function() {
      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });


    var customer_report = $('#Client_report_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/salesbycustomer",
        columns: [
            { data: 'id', name: 'id', title: 'ID' },  // The title defines the header
            { data: 'invoice_id', name: 'invoice_id', title: 'Invoice ID' },
            { data: 'company_name', name: 'c.company_name', title: 'Company Name' },
            { data: 'total_amount', name: 'total_amount', title: 'Total Amount', render: function(data, type, row) {
                return '$' + data; // Add a prefix to total_amount
            }},
            { data: 'created_at', name: 'created_at', title: 'Created At', render: function(data, type, row) {
                if (type === 'display' || type === 'filter') {
                    return moment(data).format('YYYY-MM-DD'); // Format created_at as date
                }
                return data; // Return the original data for sorting and other purposes
            }},
            {
                data: 'id', // This is where the button will be added
                name: 'id',
                title: 'Actions', // Header for the actions columnw
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<a class="btn btn-primary" href="/invoices/${data}" target="_blank"k data-id="${data}">View Invoice</a>`;
                    }
                    return data; // For sorting purposes
                },
                orderable: false, // Disable ordering on this column
                searchable: false // Disable searching on this column
            }
        ],
    });
    $('#Client_report_filter').on('click', function() {
        var client = $('#Client-report-dropdown').val();
        var date = $('#Client-report-date').val();
        customer_report.ajax.url("/salesbycustomer?client_id=" + client+ "&date="+ date ).load();

    });



    var companies_table = $('#companies_report_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/companies-report",
        columns: [
            { data: 'id', name: 'id',title: 'ID'  },
            { data: 'invoice_id', name: 'invoice_id',title: 'Invoice ID'  },
            { data: 'company_name', name: 'c.company_name',title: 'Client Name'  },
            { data: 'total_amount', name: 'total_amount',title: 'Amount' , render: function(data, type, row) {
                return '$' + data; // Add a prefix to column1
            }},
            { data: 'created_at', name: 'created_at' ,title: 'Date' ,render: function(data, type, row) {
                if (type === 'display' || type === 'filter') {
                    return moment(data).format('YYYY-M-DD'); // Format column2 as date
                }
                return data; // Return the original data for sorting and other purposes
            }},
             {
                data: 'id', // This is where the button will be added
                name: 'id',
                title: 'Actions', // Header for the actions columnw
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<a class="btn btn-primary" href="/invoices/${data}" target="_blank"k data-id="${data}">View Invoice</a>`;
                    }
                    return data; // For sorting purposes
                },
                orderable: false, // Disable ordering on this column
                searchable: false // Disable searching on this column
            }
            // Add more columns as needed
        ]
    });
    $('#company_report_filter').on('click', function() {
        debugger
        var cp = companies_table;
        debugger
        var company = $('#companies-report-dropdown').val();
        var date = $('#companies-report-date').val();
        debugger
        companies_table.ajax.url("/companies-report?company_id=" + company+ "&date="+ date ).load();

    });


    var users_table = $('#users_report_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/users-report",
        columns: [
            { data: 'id', name: 'id' ,title: 'ID'  },
            { data: 'invoice_id', name: 'invoice_id' ,title: 'Invoice ID'  },
            { data: 'company_name', name: 'c.company_name',title: 'Company Name' },
            { data: 'total_amount', name: 'total_amount', render: function(data, type, row) {
                return '$' + data; // Add a prefix to column1
            } },
            { data: 'created_at', name: 'created_at',title: 'Date' ,render: function(data, type, row) {
                if (type === 'display' || type === 'filter') {
                    return moment(data).format('YYYY-M-DD'); // Format column2 as date
                }
                return data; // Return the original data for sorting and other purposes
            }},
            {
                data: 'id', // This is where the button will be added
                name: 'id',
                title: 'Actions', // Header for the actions columnw
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<a class="btn btn-primary" href="/invoices/${data}" target="_blank"k data-id="${data}">View Invoice</a>`;
                    }
                    return data; // For sorting purposes
                },
                orderable: false, // Disable ordering on this column
                searchable: false // Disable searching on this column
            }
            // Add more columns as needed
        ]
    });
    $('#users_report_filter').on('click', function() {

        var user = $('#users-report-dropdown').val();
        var date = $('#users-report-date').val();
        users_table.ajax.url("/users-report?user_id=" + user+ "&date="+ date ).load();

    });




   });
})(jQuery);
