function create_csv(data) {
    var blob = new Blob([data], {
        type: 'text/csv;charset=utf-8;'
    });

    // Create a temporary URL for the Blob
    var url = URL.createObjectURL(blob);

    // Create a temporary <a> element
    var link = document.createElement('a');
    link.href = url;
    link.download = 'trade_report.csv';

    // Trigger the download
    link.click();

    // Clean up
    URL.revokeObjectURL(url);
}
// create exel
function create_excel(data) {
    // Split the data into rows
    var rows = data.split('\n');
    // Create a Workbook object
    var workbook = XLSX.utils.book_new();
    // Create a Worksheet object
    var worksheet = XLSX.utils.aoa_to_sheet(rows.map(row => row.split(',')));
    // Add the Worksheet to the Workbook
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
    // Convert the Workbook to an array buffer
    var wbArrayBuffer = XLSX.write(workbook, {
        bookType: 'xlsx',
        type: 'array'
    });
    // Convert the array buffer to a Blob
    var blob = new Blob([wbArrayBuffer], {
        type: 'application/octet-stream'
    });
    // Create a temporary URL for the Blob
    var url = URL.createObjectURL(blob);
    // Create a temporary <a> element
    var link = document.createElement('a');
    link.href = url;
    link.download = 'trade_report.xlsx';
    // Trigger the download
    link.click();
    // Clean up
    URL.revokeObjectURL(url);
}

// download data by ajax
function download_data(data) {
    var link = document.createElement('a');
    link.href = data;
    link.download = 'name-export.csv';

    // Trigger the download by programmatically clicking the link
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
function download_excel(data) {
    var link = document.createElement('a');
    link.href = data;
    link.download = 'name-export.xlsx'; // Change the file extension to .xlsx
    // Trigger the download by programmatically clicking the link
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
function getNextData(form_data, file_type) {
    // Send the AJAX request with the updated offset
    var form_data = form_data;
    $.ajax({
        url: '/admin/manage-trade/trading-trade-report-dt/export',
        data: form_data,
        success: function (response) {
            if (response.offset) {
                // If there is more data, send the next AJAX request recursively
                form_data['offset'] = response.offset;

                $("#data-write").text(response.offset);
                getNextData(form_data, file_type);
            } else {
                // If all data is retrieved, download the CSV file
                if (file_type === 'csv') {
                    // create_csv(response);
                    download_data(response);
                } else if (file_type === 'excel') {
                    // create_excel(response);
                    download_excel(response);
                }
                $("#count-export").slideUp();
                setTimeout(function() {
                    delete_exports(response);
                }, 3000);
            }
        }
    });
}
function delete_exports($done) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/export/manage-trade/export-done',
        data: { done: $done },
        method: 'POST',
        success: function (data) {
            console.log(data.message);
        }
    });
}
function export_partial($path) {
    $.ajax({
        url: '/admin/manage-trade/trading-trade-report/export',
        data: { file_path:$path },
        success: function (data) {
            console.log(data.message);
        }
    });
}