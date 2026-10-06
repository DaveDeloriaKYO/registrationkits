<?php require_once __DIR__ . '/auth.php'; ?>
<?php
include 'db.php';

// Pagination setup
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $limit;

$whereClause = '';
$params = [];

if ($search !== '') {
    $whereClause = "WHERE description LIKE :search OR property_no LIKE :search OR purpose LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

// Count total slips
$totalQuery = $conn->prepare("SELECT COUNT(*) FROM borrower $whereClause");
$totalQuery->execute($params);
$total = $totalQuery->fetchColumn();

// Get slips for current page
$query = $conn->prepare("SELECT * FROM borrower $whereClause ORDER BY id DESC LIMIT :limit OFFSET :offset");
foreach ($params as $key => &$val) {
    $query->bindParam($key, $val, PDO::PARAM_STR);
}
$query->bindParam(':limit', $limit, PDO::PARAM_INT);
$query->bindParam(':offset', $offset, PDO::PARAM_INT);
$query->execute();
$borrowers = $query->fetchAll(PDO::FETCH_ASSOC);

// Pagination calc
$totalPages = ceil($total / $limit);
$borrowedCount=(int)$conn->query("SELECT COUNT(*) FROM borrower WHERE LOWER(status)='borrowed'")->fetchColumn();
$returnedCount=(int)$conn->query("SELECT COUNT(*) FROM borrower WHERE LOWER(status)='returned'")->fetchColumn();
?>

<?php $pageTitle='Borrowers'; $pageSubtitle='Borrowing, return tracking, and borrower slips'; $activePage='borrowers'; include 'admin_header.php'; ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4"><div><h2 class="h4 mb-1">Borrowers</h2><div class="muted small">Manage borrowed equipment, return status, and borrower slips.</div></div><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBorrowerModal"><i class="bi bi-plus-lg me-1"></i>Create Borrower</button></div>
<div class="row g-3 mb-4"><div class="col-6 col-xl-3"><div class="card-ca stat-card"><div class="stat-icon"><i class="bi bi-clipboard-check"></i></div><div><div class="stat-value"><?= (int)$total ?></div><div class="stat-label">Total Slips</div></div></div></div><div class="col-6 col-xl-3"><div class="card-ca stat-card"><div class="stat-icon"><i class="bi bi-box-arrow-up-right"></i></div><div><div class="stat-value"><?= $borrowedCount ?></div><div class="stat-label">Borrowed</div></div></div></div><div class="col-6 col-xl-3"><div class="card-ca stat-card"><div class="stat-icon"><i class="bi bi-arrow-return-left"></i></div><div><div class="stat-value"><?= $returnedCount ?></div><div class="stat-label">Returned</div></div></div></div><div class="col-6 col-xl-3"><div class="card-ca stat-card"><div class="stat-icon"><i class="bi bi-search"></i></div><div><div class="stat-value">Search</div><div class="stat-label">Quick Filter</div></div></div></div></div>
<div class="container-fluid p-0">
    <button class="btn btn-secondary mb-3" onclick="history.back();">← Back</button>
    
    <div class="legacy-page-card mt-4">
        <div class="card-body p-0">
         <h5 class="card-title fw-semibold mb-4">LIST OF BORROWERS <span class="text-success">(<?= $total ?> borrower slips created)</span></h5>
<form method="GET" class="toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    Show 
                    <select name="limit" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
                        <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                    </select> entries
                </div>
                <input type="text" name="search" class="form-control w-25" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" />
            </form>

            <div class="table-wrap">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Property No.</th>
                            <th>Purpose</th>
                            <th>Date Borrowed</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($borrowers) > 0): ?>
                            <?php foreach ($borrowers as $index => $row): ?>
                                <tr>
                                    <td><?= $offset + $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= htmlspecialchars($row['property_no']) ?></td>
                                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                                    <td><?= htmlspecialchars($row['borrow_datetime']) ?></td>
                                    <td><?= htmlspecialchars($row['duration_days']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] === 'Returned' ? 'success' : 'warning' ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="print_borrower_slip.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary" target="_blank">Print</a>
                                        <button class="btn btn-sm btn-primary" onclick="editBorrower(<?= $row['id'] ?>)">Update</button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteBorrower(<?= $row['id'] ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>



<!-- Create Borrower Modal -->
<div class="modal fade" id="createBorrowerModal" tabindex="-1" aria-labelledby="createBorrowerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fixed-scroll">
    <div class="modal-content">
      <form id="createBorrowerForm">
        <div class="modal-header">
          <h5 class="modal-title" id="createBorrowerModalLabel">Create Borrower</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="description" class="form-label">Description of Property/Equipment</label>
              <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="col-md-6">
              <label for="property_no" class="form-label">Property No.</label>
              <input type="text" class="form-control" id="property_no" name="property_no" required>
            </div>
            <div class="col-md-6">
              <label for="purpose" class="form-label">Purpose</label>
              <input type="text" class="form-control" id="purpose" name="purpose" required>
            </div>
            <div class="col-md-6">
              <label for="borrow_datetime" class="form-label">Date and Time Borrowed</label>
              <input type="datetime-local" class="form-control" id="borrow_datetime" name="borrow_datetime" required>
            </div>
            <div class="col-md-6">
              <label for="duration_days" class="form-label">Duration (days)</label>
              <input type="number" class="form-control" id="duration_days" name="duration_days" required>
            </div>

            <hr class="my-3">

            <!-- Borrowed By -->
            <div class="col-md-6">
              <label for="borrowed_by" class="form-label">Borrowed By (Name)</label>
              <input type="text" class="form-control" id="borrowed_by" name="borrowed_by" required>
            </div>
            <div class="col-md-6">
              <label for="borrowed_by_designation" class="form-label">Borrowed By (Designation)</label>
              <input type="text" class="form-control" id="borrowed_by_designation" name="borrowed_by_designation" required>
            </div>

            <!-- Noted By -->
            <div class="col-md-6">
              <label for="noted_by" class="form-label">Noted By (Name)</label>
              <input type="text" class="form-control" id="noted_by" name="noted_by" required>
            </div>
            <div class="col-md-6">
              <label for="noted_by_designation" class="form-label">Noted By (Designation)</label>
              <input type="text" class="form-control" id="noted_by_designation" name="noted_by_designation" required>
            </div>

            <!-- Approved By -->
            <div class="col-md-6">
              <label for="approved_by" class="form-label">Approved By (Name)</label>
              <input type="text" class="form-control" id="approved_by" name="approved_by" required>
            </div>
            <div class="col-md-6">
              <label for="approved_by_designation" class="form-label">Approved By (Designation)</label>
              <input type="text" class="form-control" id="approved_by_designation" name="approved_by_designation" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>

      </form>
    </div>
  </div>
</div>


<!-- Update Borrower Modal -->
<div class="modal fade" id="updateBorrowerModal" tabindex="-1" aria-labelledby="updateBorrowerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fixed-scroll">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateBorrowerModalLabel">Update Borrower</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateBorrowerForm">
        <div class="modal-body">
          <input type="hidden" name="id" id="update_id">
          
          <div class="mb-3">
            <label for="update_description" class="form-label">Description of Property/Equipment</label>
            <input type="text" class="form-control" id="update_description" name="description" required>
          </div>

          <div class="mb-3">
            <label for="update_property_no" class="form-label">Property No.</label>
            <input type="text" class="form-control" id="update_property_no" name="property_no" required>
          </div>

          <div class="mb-3">
            <label for="update_purpose" class="form-label">Purpose</label>
            <input type="text" class="form-control" id="update_purpose" name="purpose" required>
          </div>

          <div class="mb-3">
            <label for="update_borrow_datetime" class="form-label">Date and Time Borrowed</label>
            <input type="datetime-local" class="form-control" id="update_borrow_datetime" name="borrow_datetime" required>
          </div>

          <div class="mb-3">
            <label for="update_duration_days" class="form-label">Duration (Days)</label>
            <input type="number" class="form-control" id="update_duration_days" name="duration_days" required>
          </div>

          <!-- Borrowed By -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="update_borrowed_by" class="form-label">Borrowed By</label>
              <input type="text" class="form-control" id="update_borrowed_by" name="borrowed_by" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="update_borrowed_by_designation" class="form-label">Designation</label>
              <input type="text" class="form-control" id="update_borrowed_by_designation" name="borrowed_by_designation" required>
            </div>
          </div>

          <!-- Noted By -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="update_noted_by" class="form-label">Noted By</label>
              <input type="text" class="form-control" id="update_noted_by" name="noted_by" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="update_noted_by_designation" class="form-label">Designation</label>
              <input type="text" class="form-control" id="update_noted_by_designation" name="noted_by_designation" required>
            </div>
          </div>

          <!-- Approved By -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="update_approved_by" class="form-label">Approved By</label>
              <input type="text" class="form-control" id="update_approved_by" name="approved_by" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="update_approved_by_designation" class="form-label">Designation</label>
              <input type="text" class="form-control" id="update_approved_by_designation" name="approved_by_designation" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="update_status" class="form-label">Status</label>
            <select name="status" id="update_status" class="form-select" required>
              <option value="" disabled>Select status</option>
              <option value="Borrowed">Borrowed</option>
              <option value="Returned">Returned</option>
            </select>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="editBorrowerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fixed-scroll">
    <form id="editBorrowerForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Borrower</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="mb-3">
            <label>Description</label>
            <input type="text" class="form-control" name="description" id="edit_description" required>
          </div>
          <div class="mb-3">
            <label>Property No.</label>
            <input type="text" class="form-control" name="property_no" id="edit_property_no" required>
          </div>
          <div class="mb-3">
            <label>Purpose</label>
            <input type="text" class="form-control" name="purpose" id="edit_purpose" required>
          </div>
          <div class="mb-3">
            <label>Date & Time Borrowed</label>
            <input type="datetime-local" class="form-control" name="borrow_datetime" id="edit_borrow_datetime" required>
          </div>
          <div class="mb-3">
            <label>Duration (days)</label>
            <input type="number" class="form-control" name="duration_days" id="edit_duration_days" required>
          </div>
          <div class="mb-3">
            <label>Borrowed By</label>
            <input type="text" class="form-control" name="borrowed_by" id="edit_borrowed_by" required>
          </div>
          <div class="mb-3">
            <label>Noted By</label>
            <input type="text" class="form-control" name="noted_by" id="edit_noted_by" required>
          </div>
          <div class="mb-3">
            <label>Approved By</label>
            <input type="text" class="form-control" name="approved_by" id="edit_approved_by" required>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select class="form-select" name="status" id="edit_status" required>
              <option value="" selected disabled>Select status</option>
              <option value="borrowed">Borrowed</option>
              <option value="returned">Returned</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Borrower</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- SweetAlert CDN -->
<!-- SweetAlert CDN -->

<script>
document.getElementById("createBorrowerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // Optional: Confirm submission with SweetAlert
    Swal.fire({
        title: 'Save Borrower Entry?',
        text: "Please confirm the information before saving.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754', // Bootstrap success green
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Save it'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("save_borrower.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Borrower record has been saved!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        form.reset(); // Clear the form
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createBorrowerModal'));
                        modal.hide();
                        location.reload(); // Optional: refresh list
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: data.error || 'An error occurred during save operation.'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Error',
                    text: error.message || 'Failed to connect to server.'
                });
            });
        }
    });
});
</script>


<script>
function deleteBorrower(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This borrower record will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('delete_borrower.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Deleted!', data.message, 'success');
                    // Optionally refresh the table or remove the row
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Something went wrong.', 'error');
                console.error('Error:', error);
            });
        }
    });
}

</script>

<script>
function editBorrower(id) {
  fetch('get_borrower.php?id=' + id)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const b = data.borrower;
        document.getElementById('update_id').value = b.id;
        document.getElementById('update_description').value = b.description;
        document.getElementById('update_property_no').value = b.property_no;
        document.getElementById('update_purpose').value = b.purpose;
        document.getElementById('update_borrow_datetime').value = b.borrow_datetime;
        document.getElementById('update_duration_days').value = b.duration_days;
        document.getElementById('update_borrowed_by').value = b.borrowed_by;
        document.getElementById('update_noted_by').value = b.noted_by;
        document.getElementById('update_approved_by').value = b.approved_by;
        document.getElementById('update_borrowed_by_designation').value = b.borrowed_by_designation;
        document.getElementById('update_noted_by_designation').value = b.noted_by_designation;
        document.getElementById('update_approved_by_designation').value = b.approved_by_designation;
        document.getElementById('update_status').value = b.status;

        const modal = new bootstrap.Modal(document.getElementById('updateBorrowerModal'));
        modal.show();
      } else {
        Swal.fire('Error', data.message, 'error');
      }
    }).catch(() => {
      Swal.fire('Error', 'Something went wrong.', 'error');
    });
}

// Handle form submission
document.getElementById('updateBorrowerForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  Swal.fire({
    title: 'Confirm Update',
    text: 'Are you sure you want to update this borrower?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, update it!'
  }).then(result => {
    if (result.isConfirmed) {
      fetch('update_borrower.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Updated!', data.message, 'success').then(() => {
            location.reload(); // or refresh table only
          });
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      })
      .catch(() => {
        Swal.fire('Error', 'Update failed.', 'error');
      });
    }
  });
});
</script>

<?php include 'admin_footer.php'; ?>
