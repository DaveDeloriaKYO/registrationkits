<?php require_once __DIR__ . '/auth.php'; ?>
<!doctype html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NID</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/psalogo.png" />
    <link rel="stylesheet" href="assets/css/styles.min.css" />
  </head>

  <body>
    <!--  Body Wrapper -->

      <!-- Sidebar Start -->
     
      <!--  Sidebar End -->
      <!--  Main wrapper -->
      <div class="container-fluid">
       
        <!--  Header End -->

<script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sidebarmenu.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>



<?php
require_once __DIR__ . '/db.php';

// Default parameters
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_filter = isset($_GET['date_filter']) ? trim($_GET['date_filter']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build base query
$sql = "
SELECT r.trn, r.first_name, r.middle_name, r.last_name,
       r.date_received, r.status,
       p.province_name, m.municipality_name, 
       b.barangay_name, k.purok_name
FROM rts r
LEFT JOIN provinces p ON r.province_id = p.province_id
LEFT JOIN municipalities m ON r.municipality_id = m.municipality_id
LEFT JOIN barangays b ON r.barangay_id = b.barangay_id
LEFT JOIN puroks k ON r.purok_id = k.purok_id
WHERE 1
";

// Apply search filter
$params = [];
if ($search_term !== '') {
    $sql .= " AND (r.trn LIKE :search OR r.first_name LIKE :search OR r.last_name LIKE :search)";
    $params[':search'] = "%$search_term%";
}

// Apply date filter
if ($date_filter !== '') {
    $sql .= " AND DATE(r.date_received) = :date_filter";
    $params[':date_filter'] = $date_filter;
}

// Count total records
$count_sql = "SELECT COUNT(*) FROM ($sql) AS total_query";
$stmt = $conn->prepare($count_sql);
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Apply ordering and pagination
$sql .= " ORDER BY r.date_received DESC, r.trn DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <button class="btn btn-secondary mb-3" onclick="history.back();">← Back</button>

    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                NID RTS RECORDS
                <span class="text-success">(<?= htmlspecialchars($total_records) ?> RTS created)</span>
            </h5>

            <!-- Filter and Search Form -->
            <form method="GET" class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 p-3 bg-light rounded-3 shadow-sm">
                <div class="d-flex align-items-center flex-wrap gap-3">

                    <!-- Add RTS -->
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createRTSModal">
                        <i class="ti ti-plus me-1"></i> Add RTS
                    </button>

                    <!-- Show Entries -->
                    <div class="d-flex align-items-center bg-white border rounded px-2 py-1">
                        <span class="text-muted me-2">Show</span>
                        <select name="limit" onchange="this.form.submit()" class="form-select form-select-sm w-auto border-0">
                            <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        </select>
                        <span class="text-muted ms-2">entries</span>
                    </div>

                    <!-- Date Filter -->
                    <div class="d-flex align-items-center bg-white border rounded px-2 py-1">
                        <label for="date_filter" class="text-muted me-2 mb-0">Date:</label>
                        <input type="date"
                               name="date_filter"
                               id="date_filter"
                               value="<?= htmlspecialchars($date_filter) ?>"
                               class="form-control form-control-sm border-0"
                               onchange="this.form.submit()">
                    </div>
                </div>

                <!-- Search -->
                <div class="d-flex align-items-center">
                    <div class="input-group input-group-sm shadow-sm">
                        <span class="input-group-text bg-primary text-white border-0">
                            <i class="ti ti-search"></i>
                        </span>
                        <input class="form-control border-0"
                               type="search"
                               name="search"
                               placeholder="Search by TRN, First or Last Name"
                               value="<?= htmlspecialchars($search_term) ?>"
                               onchange="this.form.submit()">
                    </div>
                </div>

                <input type="hidden" name="page" value="1">
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Transaction No. (TRN)</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Date Received IN</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rows): ?>
                            <?php foreach ($rows as $row): ?>
                                <?php
                                    $addressParts = array_filter([
                                        $row['province_name'] ?? '',
                                        $row['municipality_name'] ?? '',
                                        $row['barangay_name'] ?? '',
                                        $row['purok_name'] ?? ''
                                    ]);
                                    $address = implode(', ', $addressParts);

                                    $status = ucfirst($row['status'] ?? 'N/A');
                                    $badgeClass = strtolower($status) === 'released'
                                        ? 'bg-success text-white'
                                        : 'bg-warning text-dark';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['trn']) ?></td>
                                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                                    <td><?= htmlspecialchars($row['middle_name']) ?></td>
                                    <td><?= htmlspecialchars($row['last_name']) ?></td>
                                    <td><?= htmlspecialchars($address ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['date_received']) ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info viewBtn" data-trn="<?= htmlspecialchars($row['trn']) ?>">View</button>
                                        <button class="btn btn-sm btn-secondary editBtn" data-trn="<?= htmlspecialchars($row['trn']) ?>">Edit</button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-trn="<?= htmlspecialchars($row['trn']) ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-muted">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="RTS Pagination">
                <ul class="pagination justify-content-center mt-3">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link"
                               href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search_term) ?>&date_filter=<?= urlencode($date_filter) ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>
</div>








<!-- Add RTS Modal -->
<div class="modal fade" id="createRTSModal" tabindex="-1" aria-labelledby="createRTSModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content shadow-lg rounded-3">
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="createRTSModalLabel">
          <i class="bi bi-plus-circle me-2"></i> Add RTS Record
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Form -->
      <form id="rtsForm" novalidate>
        <div class="modal-body">
          <!-- Transaction Info -->
          <h6 class="text-primary fw-semibold mb-3">Transaction Details</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="trn" class="form-label">Transaction Number (TRN) <span class="text-danger">*</span></label>
              <input type="text" id="trn" name="trn" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label for="date_received" class="form-label">Date Received <span class="text-danger">*</span></label>
              <input type="date" id="date_received" name="date_received" class="form-control" required>
            </div>
          </div>

          <!-- Personal Info -->
          <h6 class="text-primary fw-semibold mt-4 mb-3">Recipient Information</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" id="first_name" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label for="middle_name" class="form-label">Middle Name</label>
              <input type="text" id="middle_name" name="middle_name" class="form-control">
            </div>
            <div class="col-md-4">
              <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" id="last_name" name="last_name" class="form-control" required>
            </div>
          </div>

          <!-- Address -->
          <h6 class="text-primary fw-semibold mt-4 mb-3">Address</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
              <select id="province" name="province_id" class="form-select" required>
                <option value="">Select Province</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
              <select id="municipality" name="municipality_id" class="form-select" required>
                <option value="">Select Municipality</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
              <select id="barangay" name="barangay_id" class="form-select" required>
                <option value="">Select Barangay</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="purok" class="form-label">Purok <small class="text-muted">(optional)</small></label>
              <select id="purok" name="purok_id" class="form-select">
                <option value="">-- None --</option>
              </select>
            </div>
          </div>

          <!-- Status -->
          <div class="mt-4">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select" required>
              <option value="">Select Status</option>
              <option value="Unclaimed">Unclaimed</option>
              <option value="Released">Released</option>
            </select>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success px-4">
            <i class="bi bi-save me-1"></i> Save
          </button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>




<?php include 'script.php' ?>

<!-- jQuery + SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function () {
    // Helper: Reset dependent selects
    function resetSelects(level) {
        if (level === 'province') {
            $('#municipality').html('<option value="">Select Municipality</option>');
            $('#barangay').html('<option value="">Select Barangay</option>');
            $('#purok').html('<option value="">Select Purok</option>');
        } else if (level === 'municipality') {
            $('#barangay').html('<option value="">Select Barangay</option>');
            $('#purok').html('<option value="">Select Purok</option>');
        } else if (level === 'barangay') {
            $('#purok').html('<option value="">Select Purok</option>');
        }
    }

    // Load provinces on modal open
    $('#createRTSModal').on('shown.bs.modal', function () {
        $.post('fetch_provinces.php')
            .done(function (data) {
                $('#province').html(data);
                resetSelects('province');
            })
            .fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Load',
                    text: 'Unable to fetch provinces. Please try again later.'
                });
            });
    });

    // Province → Municipality
    $('#province').on('change', function () {
        const province_id = $(this).val();
        if (province_id) {
            $.post('fetch_municipalities.php', { province_id })
                .done(function (data) {
                    $('#municipality').html(data);
                    resetSelects('municipality');
                })
                .fail(function () {
                    Swal.fire('Error', 'Failed to load municipalities.', 'error');
                });
        } else {
            resetSelects('province');
        }
    });

    // Municipality → Barangay
    $('#municipality').on('change', function () {
        const municipality_id = $(this).val();
        if (municipality_id) {
            $.post('fetch_barangays.php', { municipality_id })
                .done(function (data) {
                    $('#barangay').html(data);
                    resetSelects('barangay');
                })
                .fail(function () {
                    Swal.fire('Error', 'Failed to load barangays.', 'error');
                });
        } else {
            resetSelects('municipality');
        }
    });

    // Barangay → Purok
    $('#barangay').on('change', function () {
        const barangay_id = $(this).val();
        if (barangay_id) {
            $.post('fetch_puroks.php', { barangay_id })
                .done(function (data) {
                    $('#purok').html(data);
                })
                .fail(function () {
                    Swal.fire('Error', 'Failed to load puroks.', 'error');
                });
        } else {
            resetSelects('barangay');
        }
    });

    // Submit form via AJAX + SweetAlert
    $('#rtsForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: 'insert_rts.php',
            type: 'POST',
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save your record.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
                        success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'RTS Record Added',
                    text: 'The record has been saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Hide modal
                $('#createRTSModal').modal('hide');

                // Fix for lingering backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // Reset form
                $('#rtsForm')[0].reset();

                // Optional: reload your table/list here
                $(document).trigger('rts:recordAdded', [response]);
            }

        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on('click', '.editBtn', function () {
    const trn = $(this).data('trn');

    // 1. Show Loading State with SweetAlert2
    Swal.fire({
        title: 'Fetching Record...',
        text: 'Please wait while we load the data.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.post('fetch_update_rts.php', { trn: trn })
        .done(function (data) {
            Swal.close(); // Close loading modal on success
            
            try {
                const rts = JSON.parse(data);

                // Helper to create input HTML easily
                const createInput = (id, label, value, type = 'text', required = false, readonly = false) => `
                    <div class="swal2-input-container">
                        <label for="${id}" class="form-label">${label}</label>
                        <input type="${type}" id="${id}" name="${id}" class="form-control" value="${value}" ${required ? 'required' : ''} ${readonly ? 'readonly' : ''}>
                    </div>
                `;
                
                // Helper to create select HTML easily
                const createSelect = (id, label, required = false) => `
                    <div class="swal2-input-container" id="${id}-container">
                        <label for="${id}" class="form-label">${label}</label>
                        <select id="${id}" name="${id}" class="form-control" ${required ? 'required' : ''}>
                            <option value="">Select ${label}</option>
                        </select>
                    </div>
                `;

                Swal.fire({
                    title: 'Edit RTS Record',
                    html:
                        '<form id="updateRTSForm" class="swal2-form">' +
                        createInput('trn', 'Transaction Number (TRN)', rts.trn, 'text', false, true) +
                        createInput('date_received', 'Date Received', rts.date_received, 'date', true) +
                        createInput('first_name', 'First Name', rts.first_name, 'text', true) +
                        createInput('middle_name', 'Middle Name', rts.middle_name) +
                        createInput('last_name', 'Last Name', rts.last_name, 'text', true) +
                        createSelect('province_id', 'Province', true) +
                        createSelect('municipality_id', 'Municipality', true) +
                        createSelect('barangay_id', 'Barangay', true) +
                        createSelect('purok_id', 'Purok') + // No required flag for Purok
                        '<div class="swal2-input-container">' +
                            '<label for="status" class="form-label">Status</label>' +
                            '<select id="status" name="status" class="form-control" required>' +
                                '<option value="Unclaimed"' + (rts.status === 'Unclaimed' ? ' selected' : '') + '>Unclaimed</option>' +
                                '<option value="Released"' + (rts.status === 'Released' ? ' selected' : '') + '>Released</option>' +
                            '</select>' +
                        '</div>' +
                        '</form>',
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    width: '600px',
                    focusConfirm: false, // Prevent auto-focus on 'Save Changes'
                    didOpen: () => {
                        // 2. Add Change Handlers for Cascading Dropdowns
                        $('#province_id').on('change', function() {
                            const provinceId = $(this).val();
                            loadMunicipalities(provinceId);
                            $('#barangay_id').html('<option value="">Select Barangay</option>');
                            $('#purok_id').html('<option value="">Select Purok</option>');
                            $('#purok_id-container').hide();
                        });

                        $('#municipality_id').on('change', function() {
                            const municipalityId = $(this).val();
                            loadBarangays(municipalityId);
                            $('#purok_id').html('<option value="">Select Purok</option>');
                            $('#purok_id-container').hide();
                        });

                        $('#barangay_id').on('change', function() {
                            const barangayId = $(this).val();
                            loadPuroks(barangayId);
                        });

                        // 3. Initial Load of Dropdowns (Deferred)
                        loadProvinces(rts.province_id, () => {
                            loadMunicipalities(rts.province_id, rts.municipality_id, () => {
                                loadBarangays(rts.municipality_id, rts.barangay_id, () => {
                                    loadPuroks(rts.barangay_id, rts.purok_id);
                                });
                            });
                        });
                    },
                    preConfirm: function () {
                        let dataObj = {};
                        $('#updateRTSForm').serializeArray().forEach(item => dataObj[item.name] = item.value);

                        // Basic client-side validation for required fields
                        for (const key in dataObj) {
                            if ($(`#${key}`).prop('required') && !dataObj[key]) {
                                Swal.showValidationMessage(`Please select or enter the required field: ${$(`label[for=${key}]`).text()}`);
                                return false;
                            }
                        }
                        return dataObj;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 4. Show loading state for the POST request
                        Swal.fire({
                            title: 'Saving Changes...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.post('update_rts.php', result.value, function(response) {
                            Swal.close(); // Close loading modal
                            let data;
                            try {
                                data = JSON.parse(response);
                            } catch (e) {
                                // Handle non-JSON response from server
                                Swal.fire('Error', 'Server responded with an invalid format. Update failed.', 'error');
                                return;
                            }
                            
                            if (data.success) {
                                Swal.fire('Updated! 🎉', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error 😢', data.message, 'error');
                            }
                        }).fail(() => {
                            Swal.close(); // Close loading modal
                            Swal.fire('Error', 'Request failed. Could not connect to the server.', 'error');
                        });
                    }
                });
            } catch (e) {
                // Handle JSON parse error from fetch_update_rts.php
                Swal.fire('Error', 'Failed to parse record data. The server response was invalid.', 'error');
            }
        })
        .fail(function () {
            Swal.close(); // Close loading modal on fail
            Swal.fire('Error', 'Failed to fetch record data. Please try again.', 'error');
        });
});

// Load provinces (unchanged except for adding error handling)
function loadProvinces(selectedProvince, callback) {
    $.post('fetch_update_provinces.php', function(data) {
        $('#province_id').html(data);
        if (selectedProvince) $('#province_id').val(selectedProvince);
        if (callback) callback();
    }).fail(() => {
        console.error('Failed to load provinces.');
        // Optionally show a small toast error
    });
}

// Load municipalities (unchanged except for adding error handling)
function loadMunicipalities(provinceId, selectedMunicipality, callback) {
    // Only load if an ID is present
    if (!provinceId) {
        $('#municipality_id').html('<option value="">Select Municipality</option>');
        if (callback) callback();
        return;
    }
    $.post('fetch_update_municipalities.php', { province_id: provinceId }, function(data) {
        $('#municipality_id').html(data);
        if (selectedMunicipality) $('#municipality_id').val(selectedMunicipality);
        if (callback) callback();
    }).fail(() => {
        console.error('Failed to load municipalities.');
    });
}

// Load barangays (unchanged except for adding error handling)
function loadBarangays(municipalityId, selectedBarangay, callback) {
    // Only load if an ID is present
    if (!municipalityId) {
        $('#barangay_id').html('<option value="">Select Barangay</option>');
        if (callback) callback();
        return;
    }
    $.post('fetch_update_barangays.php', { municipality_id: municipalityId }, function(data) {
        $('#barangay_id').html(data);
        if (selectedBarangay) $('#barangay_id').val(selectedBarangay);
        if (callback) callback();
    }).fail(() => {
        console.error('Failed to load barangays.');
    });
}

// Load puroks (changed to hide container via ID)
function loadPuroks(barangayId, selectedPurok) {
    const $purokContainer = $('#purok_id-container');
    // Only load if an ID is present
    if (!barangayId) {
        $('#purok_id').html('<option value="">Select Purok</option>');
        $purokContainer.hide();
        return;
    }
    $.post('fetch_update_puroks.php', { barangay_id: barangayId }, function(data) {
        if (data.trim() === "" || data.trim() === '<option value="">Select Purok</option>') {
            $('#purok_id').html('<option value="">Select Purok</option>'); // Ensure a default option
            $purokContainer.slideUp(200); // Smoother transition
        } else {
            $('#purok_id').html(data);
            if (selectedPurok) $('#purok_id').val(selectedPurok);
            $purokContainer.slideDown(200); // Smoother transition
        }
    }).fail(() => {
        console.error('Failed to load puroks.');
        $purokContainer.hide();
    });
}
</script>
<script>
// Handle Delete button click
$(document).on("click", ".deleteBtn", function () {
    let trn = $(this).data("trn");

    Swal.fire({
        title: "Are you sure?",
        text: "This record will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "delete_rts.php",
                type: "POST",
                data: { trn: trn },
                success: function (response) {
                    console.log("Server Response:", response); // 👈 Debugging
                    if (response.success) {
                        Swal.fire("Deleted!", response.message, "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire("Error", "AJAX failed: " + error, "error");
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }
    });
});
</script>
