<?php require_once __DIR__ . '/auth.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ISMD Registration Kits</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .select2-container .select2-selection--single { height: 38px; }
        .loading-spinner { display: inline-block; margin-right: 10px; }
        .table-wrapper { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body class="p-4">

<!-- Trigger Button -->
<button id="manageKitsBtn" class="btn btn-primary mt-4">
    Manage ISMD Registration Kits
</button>

<!-- Table to display registration details -->
<div id="tableWrapper" class="table-wrapper mt-4" style="display: none;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Computer Name</th>
                <th>Registration Code</th>
                <th>Machine ID</th>
                <th>Serial Number</th>
                <th>Password</th>
                <th>Action</th> <!-- Column for the Delete button -->
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Registration details will be inserted here -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    let laptopNames = [];

    // Fetch distinct laptop names when the page loads
    $.ajax({
        url: 'get_laptops.php', // Backend script to fetch distinct laptop names
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            laptopNames = data;  // Store the response data
        },
        error: function () {
            Swal.fire('Error', 'Failed to fetch laptop names.', 'error');
        }
    });

    // Handle click event on the button
    $('#manageKitsBtn').on('click', function () {
        if (laptopNames.length === 0) {
            Swal.fire('No laptops available', 'Please try again later.', 'info');
            return;
        }

        // Create the checkboxes HTML dynamically
        let checkboxesHTML = '<div style="max-height: 300px; overflow-y: auto;">';  // Scrollable container
        laptopNames.forEach(function(name) {
            const sanitizedName = sanitizeName(name);  // Sanitize name for ID use
            checkboxesHTML += `
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="laptop_${sanitizedName}" value="${name}">
                    <label class="form-check-label" for="laptop_${sanitizedName}">${name}</label>
                </div>
            `;
        });
        checkboxesHTML += '</div>';

        // Show the SweetAlert modal with checkboxes
        Swal.fire({
            title: 'Select Laptops',
            html: checkboxesHTML,
            showCancelButton: true,
            confirmButtonText: 'Add',
            preConfirm: () => {
                const selectedLaptops = [];
                laptopNames.forEach(function(name) {
                    const sanitizedName = sanitizeName(name);
                    if ($('#laptop_' + sanitizedName).prop('checked')) {
                        selectedLaptops.push(name);  // Collect selected laptop names
                    }
                });
                if (selectedLaptops.length === 0) {
                    Swal.showValidationMessage('Please select at least one laptop');
                }
                return selectedLaptops;  // Return selected laptops
            }
        }).then(result => {
            if (result.isConfirmed) {
                const selectedLaptops = result.value;

                // Store selected laptops in localStorage for persistence
                localStorage.setItem('selectedLaptops', JSON.stringify(selectedLaptops));

                // Show loading Swal while fetching kits
                Swal.fire({
                    title: 'Fetching kits...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                // Fetch registration details for selected laptops
                $.ajax({
                    url: 'get_laptops.php',  // Now it returns the full details for selected laptops
                    method: 'GET',
                    data: { laptops: selectedLaptops.join(',') },
                    dataType: 'json',
                    success: function(data) {
                        Swal.close();  // Close the loading Swal

                        if (data.length > 0) {
                            // Show the table with registration details
                            $('#tableWrapper').show();
                            $('#tableBody').html(''); // Clear previous data

                            // Loop through the data and add rows to the table
                            data.forEach(function(item) {
                                $('#tableBody').append(`
                                    <tr data-id="${item.id}">
                                        <td>${item.computer_name}</td>
                                        <td>${item.reg_code}</td>
                                        <td>${item.machine_id}</td>
                                        <td>${item.serial_number}</td>
                                        <td>${item.password}</td>
                                        <td><button class="btn btn-danger btn-sm deleteBtn">Delete</button></td>
                                    </tr>
                                `);
                            });
                        } else {
                            Swal.fire('No Data', 'No registration details found for the selected laptops.', 'info');
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Failed to fetch registration details.', 'error');
                    }
                });
            }
        });
    });

    // Delete button action
    $(document).on('click', '.deleteBtn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');  // Get the ID of the row to delete

        // Show a confirmation prompt before deleting
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to delete the registration
                $.ajax({
                    url: 'delete_ismd_registration.php', // PHP script to delete registration
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response === 'success') {
                            // Remove the row from the table
                            row.remove();
                            Swal.fire('Deleted!', 'The registration has been deleted.', 'success');
                        } else {
                            Swal.fire('Error!', 'Failed to delete the registration.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete the registration.', 'error');
                    }
                });
            }
        });
    });

    // Function to sanitize the laptop name for ID use (e.g., removing special characters)
    function sanitizeName(name) {
        return name.replace(/[^a-zA-Z0-9]/g, '_');  // Replace non-alphanumeric characters with underscore
    }

    // On page load, check if selected laptops are saved in localStorage
    const savedLaptops = JSON.parse(localStorage.getItem('selectedLaptops'));

    if (savedLaptops && savedLaptops.length > 0) {
        // Show the loading spinner while fetching saved laptop details
        Swal.fire({
            title: 'Fetching saved kits...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: 'get_laptops.php',
            method: 'GET',
            data: { laptops: savedLaptops.join(',') },
            dataType: 'json',
            success: function(data) {
                Swal.close();

                if (data.length > 0) {
                    $('#tableWrapper').show();
                    $('#tableBody').html('');
                    data.forEach(function(item) {
                        $('#tableBody').append(`
                            <tr data-id="${item.id}">
                                <td>${item.computer_name}</td>
                                <td>${item.reg_code}</td>
                                <td>${item.machine_id}</td>
                                <td>${item.serial_number}</td>
                                <td>${item.password}</td>
                                <td><button class="btn btn-danger btn-sm deleteBtn">Delete</button></td>
                            </tr>
                        `);
                    });
                }
            },
            error: function() {
                Swal.close();
                Swal.fire('Error', 'Failed to fetch saved kits.', 'error');
            }
        });
    }
});
</script>

</body>
</html>
