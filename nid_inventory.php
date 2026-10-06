<?php
require_once __DIR__ . '/auth.php';
// nid_inventory.php - Updated: Adds Date Filter and Creation Time Column, with persistent limit/search/date filter

require_once __DIR__ . '/db.php';

// --- 1. CONFIGURATION ---
$records_per_page = 10;
$current_page = 1;
$search_term = '';
$date_filter = '';
$status_filter = '';
$peripheral_filter = '';
$freeze_filter = '';
$total_records = 0;
$total_pages = 0;
$peripherals_list = [];

// List of peripherals for column generation
$peripherals_columns = [
    'fp_scanner', 'iris_scanner', 'webcam', 'document_scanner',
    'extended_monitor', 'printer', 'battery_pack', 'photobooth_kit'
];

// --- 2. INPUT HANDLING ---
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = (int)$_GET['page'];
}
if (isset($_GET['search'])) {
    $search_term = trim($_GET['search']);
}
if (isset($_GET['date_filter'])) {
    $date_filter = trim($_GET['date_filter']);
}
if (isset($_GET['status_filter'])) { $status_filter = trim($_GET['status_filter']); }
if (isset($_GET['peripheral_filter'])) { $peripheral_filter = trim($_GET['peripheral_filter']); }
if (isset($_GET['freeze_filter'])) { $freeze_filter = trim($_GET['freeze_filter']); }

// Handle records per page limit input
$allowed_limits = [5, 10, 25, 50];
$limit = 10; // Default
if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
    $requested_limit = (int)$_GET['limit'];
    if (in_array($requested_limit, $allowed_limits)) {
        $limit = $requested_limit;
    }
}
$records_per_page = $limit;

// --- 3. BUILD QUERY ---
$select_cols = ['id', 'regno', 'creation_time', 'is_frozen'];
foreach ($peripherals_columns as $p) {
    $select_cols[] = $p;
    $select_cols[] = $p . '_status';
    $select_cols[] = $p . '_serial';
}
$select_list = implode(', ', $select_cols);

try {
    // Build dynamic WHERE clause
    $where_conditions = [];
    $params = [];

    if (!empty($search_term)) {
        $search_fields = ['regno'];
        foreach ($peripherals_columns as $p) {
            $search_fields[] = $p . '_serial';
        }

        $or_conditions = [];
        foreach ($search_fields as $field) {
            $or_conditions[] = "{$field} LIKE :search_term";
        }
        $where_conditions[] = "(" . implode(" OR ", $or_conditions) . ")";
        $params[':search_term'] = '%' . $search_term . '%';
    }

    if (!empty($date_filter)) {
        $where_conditions[] = "DATE(creation_time) = :date_filter";
        $params[':date_filter'] = $date_filter;
    }

    $allowed_status_filters = ['serviceable','unserviceable','unidentified','for verification','for retrieval','transferred'];
    if ($status_filter !== '' && in_array(strtolower($status_filter), $allowed_status_filters, true)) {
        $statusOr = [];
        foreach ($peripherals_columns as $p) { $statusOr[] = $p . '_status = :status_filter_' . $p; $params[':status_filter_' . $p] = strtolower($status_filter); }
        $where_conditions[] = '(' . implode(' OR ', $statusOr) . ')';
    }
    if ($peripheral_filter !== '' && in_array($peripheral_filter, $peripherals_columns, true)) {
        $where_conditions[] = $peripheral_filter . ' = 1';
    }
    if ($freeze_filter === 'frozen') { $where_conditions[] = 'is_frozen = 1'; }
    elseif ($freeze_filter === 'unfrozen') { $where_conditions[] = 'is_frozen = 0'; }

    $where_clause = count($where_conditions) > 0 ? " WHERE " . implode(" AND ", $where_conditions) : '';

    // --- COUNT QUERY ---
    $count_sql = "SELECT COUNT(*) FROM peripherals" . $where_clause;
    $count_stmt = $conn->prepare($count_sql);
    foreach ($params as $key => $val) {
        $count_stmt->bindValue($key, $val);
    }
    $count_stmt->execute();
    $total_records = $count_stmt->fetchColumn();

    // Pagination calculations
    $total_pages = max(1, ceil($total_records / $records_per_page));
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $records_per_page;

    // --- DATA FETCH QUERY ---
    $data_sql = "SELECT {$select_list} FROM peripherals" . $where_clause . " ORDER BY regno ASC LIMIT :limit OFFSET :offset";
    $data_stmt = $conn->prepare($data_sql);

    foreach ($params as $key => $val) {
        $data_stmt->bindValue($key, $val);
    }
    $data_stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $data_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $data_stmt->execute();

    $peripherals_list = $data_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $peripherals_list = [];
    // Optionally log $e->getMessage();
}

// --- Helper Functions ---
function get_peripheral_label($name)
{
    $labels = [
        'fp_scanner' => 'F.P. Scanner',
        'iris_scanner' => 'Iris Scanner',
        'webcam' => 'Webcam',
        'document_scanner' => 'Doc Scanner',
        'extended_monitor' => 'Monitor',
        'printer' => 'Printer',
        'battery_pack' => 'Battery Pack',
        'photobooth_kit' => 'Photobooth Kit'
    ];
    return $labels[$name] ?? ucfirst(str_replace('_', ' ', $name));
}

function render_status($is_present, $status)
{
    if (!$is_present) {
        return '<span class="badge bg-secondary-subtle text-secondary">N/A</span>';
    }

    $status_lower = strtolower($status);
    $class = 'bg-primary';
    switch ($status_lower) {
        case 'serviceable':
            $class = 'bg-success';
            break;
        case 'unserviceable':
            $class = 'bg-danger';
            break;
        case 'unidentified':
            $class = 'bg-secondary';
            break;
        case 'for verification':
            $class = 'bg-warning text-dark';
            break;
        case 'for retrieval':
            $class = 'bg-info';
            break;
        case 'for disposal':
            $class = 'bg-dark';
            break;
    }
    return '<span class="badge ' . $class . '">' . htmlspecialchars(ucwords($status)) . '</span>';
}
?>
<?php $pageTitle='NID Inventory'; $pageSubtitle='Peripheral inventory and equipment status'; $activePage='inventory'; include 'admin_header.php'; ?>
<style>
    /* Inventory page layout */
    .inventory-page { width: 100%; }
    .inventory-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        border: 1px solid var(--ca-border);
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(15,23,42,.04);
    }
    .inventory-toolbar {
        background: #fff;
        border: 1px solid var(--ca-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15,23,42,.04);
        padding: 16px;
    }
    .inventory-filter { min-width: 150px; }
    .inventory-search { min-width: 260px; }
    .inventory-table-card {
        background: #fff;
        border: 1px solid var(--ca-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(15,23,42,.04);
        overflow: hidden;
    }
    .inventory-table-scroll {
        width: 100%;
        overflow: auto;
        max-height: calc(100vh - 390px);
        min-height: 260px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    .inventory-table-scroll table {
        min-width: 2200px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .inventory-table-scroll thead {
        position: sticky;
        top: 0;
        z-index: 20;
    }
    .inventory-table-scroll thead th {
        background: #f8fafc !important;
        color: #64748b;
        font-size: .70rem;
        text-transform: uppercase;
        letter-spacing: .045em;
        white-space: nowrap;
        border-bottom: 1px solid var(--ca-border);
        vertical-align: middle;
        box-shadow: inset 0 -1px 0 var(--ca-border);
    }
    .inventory-table-scroll tbody td {
        white-space: nowrap;
        vertical-align: middle;
        border-bottom: 1px solid #eef0f4;
    }
    .inventory-table-scroll tbody tr:last-child td { border-bottom: 0; }
    .inventory-table-scroll tbody tr:hover td { background: #fafbff; }
    .inventory-table-scroll .sticky-col {
        position: sticky;
        z-index: 11;
        background: #fff;
        box-shadow: 2px 0 5px rgba(15,23,42,.04);
    }
    .inventory-table-scroll thead .sticky-col {
        z-index: 35;
        background: #f8fafc !important;
    }
    .sticky-col-id { left: 0; width: 64px; min-width: 64px; }
    .sticky-col-regno { left: 64px; width: 110px; min-width: 110px; }
    .sticky-col-datetime { left: 174px; width: 190px; min-width: 190px; }
    .inventory-table-scroll .peripheral-col { min-width: 145px; }
    .inventory-table-scroll .serial-cell { min-width: 155px; max-width: 180px; overflow-wrap: anywhere; white-space: normal; }
    .inventory-table-scroll .action-col { min-width: 260px; }
    .inventory-table-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--ca-border);
        background: #fff;
    }
    .inventory-pagination .page-link { border-radius: 8px; margin: 0 2px; }
    .inventory-pagination .page-item.active .page-link { background: var(--ca-primary); border-color: var(--ca-primary); }
    .inventory-stat-note { font-size: .72rem; color: var(--ca-muted); }
    .inventory-scroll-hint { font-size: .72rem; color: var(--ca-muted); }
    @media (max-width: 1199.98px) {
        .inventory-search { min-width: 220px; }
        .inventory-table-scroll { max-height: calc(100vh - 430px); }
    }
    @media (max-width: 767.98px) {
        .inventory-toolbar { padding: 12px; }
        .inventory-filter, .inventory-search { width: 100% !important; min-width: 0; }
        .inventory-table-scroll { max-height: calc(100vh - 500px); min-height: 220px; }
    }
</style>
<div class="container-fluid inventory-page">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <button class="btn btn-light border btn-sm" type="button" onclick="history.back();">
            <i class="bi bi-arrow-left me-1"></i> Back
        </button>
        <div class="inventory-scroll-hint"><i class="bi bi-arrows-angle-expand me-1"></i> Scroll horizontally to view all peripheral columns</div>
    </div>

    <section class="inventory-hero p-4 mb-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="stat-icon"><i class="bi bi-cpu"></i></div>
                    <div>
                        <h1 class="h4 mb-0 fw-bold">NID Peripherals Inventory</h1>
                        <div class="muted small">Track peripheral assignments, serviceability, serial numbers, and inventory records.</div>
                    </div>
                </div>
            </div>
            <span class="badge rounded-pill bg-primary-subtle text-primary border px-3 py-2">
                <?= (int)$total_records ?> matching record<?= ((int)$total_records === 1 ? '' : 's') ?>
            </span>
        </div>
    </section>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3"><div class="stat-card card-ca"><div class="stat-icon"><i class="bi bi-cpu"></i></div><div><div class="stat-value"><?= (int)$total_records ?></div><div class="stat-label">Inventory Records</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="stat-card card-ca"><div class="stat-icon"><i class="bi bi-upc-scan"></i></div><div><div class="stat-value"><?= count($peripherals_columns) ?></div><div class="stat-label">Peripheral Types</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="stat-card card-ca"><div class="stat-icon"><i class="bi bi-funnel"></i></div><div><div class="stat-value"><?= ($search_term !== '' || $date_filter !== '' || $status_filter !== '' || $peripheral_filter !== '' || $freeze_filter !== '') ? 'Filtered' : 'All' ?></div><div class="stat-label">Current View</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="stat-card card-ca"><div class="stat-icon"><i class="bi bi-lock"></i></div><div><div class="stat-value"><?= htmlspecialchars($freeze_filter === 'frozen' ? 'Frozen' : ($freeze_filter === 'unfrozen' ? 'Open' : 'All')) ?></div><div class="stat-label">Freeze Filter</div></div></div></div>
    </div>

    <form method="GET" class="inventory-toolbar mb-3" id="inventoryFilterForm">
        <input type="hidden" name="page" value="1">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary d-inline-flex align-items-center" id="createPeripheralBtn">
                <i class="bi bi-plus-lg me-1"></i> Add Peripheral Record
            </button>

            <div class="d-flex align-items-center gap-2 ms-xl-2">
                <label class="small text-muted mb-0">Show</label>
                <select name="limit" class="form-select form-select-sm" style="width:82px" aria-label="Records per page">
                    <?php foreach ([5,10,25,50] as $opt): ?><option value="<?= $opt ?>" <?= $limit === $opt ? 'selected' : '' ?>><?= $opt ?></option><?php endforeach; ?>
                </select>
                <span class="small text-muted">entries</span>
            </div>

            <input type="date" name="date_filter" value="<?= htmlspecialchars($date_filter) ?>" class="form-control form-control-sm inventory-filter" aria-label="Creation date">

            <select name="peripheral_filter" class="form-select form-select-sm inventory-filter" aria-label="Equipment filter">
                <option value="">All Equipment</option>
                <?php foreach ($peripherals_columns as $pf): ?><option value="<?= htmlspecialchars($pf) ?>" <?= $peripheral_filter === $pf ? 'selected' : '' ?>><?= htmlspecialchars(get_peripheral_label($pf)) ?></option><?php endforeach; ?>
            </select>

            <select name="status_filter" class="form-select form-select-sm inventory-filter" aria-label="Status filter">
                <option value="">All Status</option>
                <?php foreach (['serviceable','unserviceable','unidentified','for verification','for retrieval','transferred'] as $sf): ?><option value="<?= htmlspecialchars($sf) ?>" <?= strtolower($status_filter) === $sf ? 'selected' : '' ?>><?= htmlspecialchars(ucwords($sf)) ?></option><?php endforeach; ?>
            </select>

            <select name="freeze_filter" class="form-select form-select-sm inventory-filter" aria-label="Freeze filter">
                <option value="">All Records</option>
                <option value="frozen" <?= $freeze_filter === 'frozen' ? 'selected' : '' ?>>🔒 Frozen</option>
                <option value="unfrozen" <?= $freeze_filter === 'unfrozen' ? 'selected' : '' ?>>Unfrozen</option>
            </select>

            <div class="input-group input-group-sm inventory-search ms-xl-auto">
                <span class="input-group-text bg-primary text-white border-0"><i class="bi bi-search"></i></span>
                <input class="form-control" type="search" name="search" placeholder="Search Reg. No. / Serial No." value="<?= htmlspecialchars($search_term) ?>" autocomplete="off">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>

            <?php if ($search_term !== '' || $date_filter !== '' || $status_filter !== '' || $peripheral_filter !== '' || $freeze_filter !== ''): ?>
                <a class="btn btn-light border btn-sm" href="nid_inventory.php"><i class="bi bi-x-circle me-1"></i>Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <section class="inventory-table-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-2 border-bottom">
            <div class="fw-semibold">Peripheral Details</div>
            <div class="inventory-stat-note">Showing <?= count($peripherals_list) ?> of <?= (int)$total_records ?> matching records</div>
        </div>

        <div class="inventory-table-scroll">
            <table class="table table-hover align-middle">
                <thead class="text-center">
                    <tr>
                        <th rowspan="3" class="sticky-col sticky-col-id">ID</th>
                        <th rowspan="3" class="sticky-col sticky-col-regno">REG NO.</th>
                        <th rowspan="3" class="sticky-col sticky-col-datetime">CREATION DATE/TIME</th>
                        <th colspan="<?= count($peripherals_columns) * 2 ?>" class="border-start border-end">PERIPHERALS DETAILS</th>
                        <th rowspan="3" class="action-col">ACTION</th>
                    </tr>
                    <tr>
                        <?php foreach ($peripherals_columns as $col): ?><th colspan="2" class="peripheral-col"><?= htmlspecialchars(get_peripheral_label($col)) ?></th><?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($peripherals_columns as $col): ?><th>Status</th><th>Serial No.</th><?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($peripherals_list) > 0): ?>
                        <?php foreach ($peripherals_list as $peripheral): ?>
                            <tr class="text-center">
                                <td class="sticky-col sticky-col-id"><?= htmlspecialchars($peripheral['id']) ?></td>
                                <td class="sticky-col sticky-col-regno fw-semibold"><?= htmlspecialchars($peripheral['regno']) ?></td>
                                <td class="text-muted sticky-col sticky-col-datetime"><?= date('Y-m-d h:i A', strtotime($peripheral['creation_time'])) ?></td>
                                <?php foreach ($peripherals_columns as $col): ?>
                                    <?php $is_present=(int)$peripheral[$col]; $status=$peripheral[$col.'_status']; $serial=htmlspecialchars($peripheral[$col.'_serial']) ?: '—'; ?>
                                    <td><?= render_status($is_present, $status) ?></td>
                                    <td class="text-muted serial-cell"><?= $is_present ? $serial : '—' ?></td>
                                <?php endforeach; ?>
                                <td class="action-col">
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="viewRegistrationDetails('<?= htmlspecialchars($peripheral['regno']) ?>')"><i class="bi bi-eye"></i> View</button>
                                        <?php if ((int)$peripheral['is_frozen'] === 1): ?><span class="badge bg-warning text-dark d-inline-flex align-items-center"><i class="bi bi-lock-fill me-1"></i>Frozen</span><?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning" <?= ((int)$peripheral['is_frozen']===1)?'disabled':'' ?> onclick="updatePeripheral(<?= (int)$peripheral['id'] ?>)"><i class="bi bi-pencil"></i> Edit</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" <?= ((int)$peripheral['is_frozen']===1)?'disabled':'' ?> onclick="deletePeripheral(<?= (int)$peripheral['id'] ?>)"><i class="bi bi-trash"></i> Delete</button>
                                        <button type="button" class="btn btn-sm <?= ((int)$peripheral['is_frozen']===1)?'btn-outline-success':'btn-outline-secondary' ?>" onclick="togglePeripheralFreeze(<?= (int)$peripheral['id'] ?>, <?= ((int)$peripheral['is_frozen']===1)?'true':'false' ?>)"><i class="bi <?= ((int)$peripheral['is_frozen']===1)?'bi-unlock':'bi-lock' ?>"></i> <?= ((int)$peripheral['is_frozen']===1)?'Unfreeze':'Freeze' ?></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="<?= 3 + (count($peripherals_columns) * 2) + 1 ?>" class="text-center py-5"><div class="empty-state"><i class="bi bi-inbox fs-2 d-block mb-2"></i><?= (!empty($search_term) || !empty($status_filter) || !empty($peripheral_filter) || !empty($freeze_filter) || !empty($date_filter)) ? 'No inventory records match the selected filters.' : 'No peripheral inventory records found.' ?></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="inventory-table-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="inventory-stat-note">Page <?= (int)$current_page ?> of <?= (int)$total_pages ?></div>
            <?php if ($total_pages >= 1): ?>
                <nav aria-label="Inventory pagination" class="inventory-pagination">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= max(1,$current_page-1) ?>&search=<?= urlencode($search_term) ?>&limit=<?= $limit ?>&date_filter=<?= urlencode($date_filter) ?>&peripheral_filter=<?= urlencode($peripheral_filter) ?>&status_filter=<?= urlencode($status_filter) ?>&freeze_filter=<?= urlencode($freeze_filter) ?>">&laquo;</a></li>
                        <?php $startPage=max(1,$current_page-2); $endPage=min($total_pages,$current_page+2); for($i=$startPage;$i<=$endPage;$i++): ?>
                            <li class="page-item <?= $i === $current_page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search_term) ?>&limit=<?= $limit ?>&date_filter=<?= urlencode($date_filter) ?>&peripheral_filter=<?= urlencode($peripheral_filter) ?>&status_filter=<?= urlencode($status_filter) ?>&freeze_filter=<?= urlencode($freeze_filter) ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= min($total_pages,$current_page+1) ?>&search=<?= urlencode($search_term) ?>&limit=<?= $limit ?>&date_filter=<?= urlencode($date_filter) ?>&peripheral_filter=<?= urlencode($peripheral_filter) ?>&status_filter=<?= urlencode($status_filter) ?>&freeze_filter=<?= urlencode($freeze_filter) ?>">&raquo;</a></li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
// =======================================================
// FIX: Define peripherals array in the global scope 
// so window.updatePeripheral and the document.ready functions can both access it.
// =======================================================
const peripherals = [
    { name: 'fp_scanner', label: 'Fingerprint Scanner' },
    { name: 'iris_scanner', label: 'Iris Scanner' },
    { name: 'webcam', label: 'Webcam' },
    { name: 'document_scanner', label: 'Document Scanner' },
    { name: 'extended_monitor', label: 'Extended Monitor' },
    { name: 'printer', label: 'Printer' },
    { name: 'battery_pack', label: 'Battery Pack' },
    { name: 'photobooth_kit', label: 'Photobooth Kit' }
];

// Helper function to generate HTML rows for the Add Modal (move this out too, for re-use)
function generatePeripheralRow(name, label, record = {}) {
    // This is the combined function used by both Add and Update modals
    const id = name;
    const status_id = name + '_status';
    const serial_id = name + '_serial';

    // Check if we are initializing the form for an UPDATE (record object is passed)
    const isUpdate = Object.keys(record).length > 0;
    
    // Values based on record (for update) or defaults (for add)
    const isPresent = isUpdate ? (parseInt(record[name]) === 1) : false; 
    const serialValue = isUpdate ? (record[serial_id] || '') : '';
    const statusValue = isUpdate ? (record[status_id] || 'unidentified') : 'serviceable';
    
    const isDisabled = !isPresent && isUpdate ? 'disabled' : (isUpdate ? '' : 'disabled');
    const isChecked = isPresent ? 'checked' : '';
    const isRequired = isPresent ? 'required' : '';
    
    // Status options HTML
    const statusOptions = `
        <option value="serviceable" ${statusValue === 'serviceable' ? 'selected' : ''}>Serviceable</option>
        <option value="unserviceable" ${statusValue === 'unserviceable' ? 'selected' : ''}>Unserviceable</option>
        <option value="unidentified" ${statusValue === 'unidentified' ? 'selected' : ''}>Unidentified</option>
        <option value="for verification" ${statusValue === 'for verification' ? 'selected' : ''}>For Verification</option>
        <option value="for retrieval" ${statusValue === 'for retrieval' ? 'selected' : ''}>For Retrieval</option>
        <option value="transferred" ${statusValue === 'transferred' ? 'selected' : ''}>Transferred</option>
    `;

    return `
        <div class="peripheral-row border-bottom py-2" id="row-${id}">
            <div class="row align-items-center mb-1">
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input peripheral-checkbox" type="checkbox" name="${id}" id="${id}" ${isChecked}>
                        <label class="form-check-label fw-bold" for="${id}">${label}</label>
                    </div>
                </div>
                <div class="col-6">
                    <select class="form-select form-select-sm peripheral-status" name="${status_id}" id="${status_id}" ${isDisabled}>
                        ${statusOptions}
                    </select>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <label for="${serial_id}" class="form-label small text-muted mb-0">Serial Number (Required if Present)</label>
                    <input type="text" class="form-control form-control-sm peripheral-serial" name="${serial_id}" id="${serial_id}" value="${serialValue}" ${isDisabled} placeholder="Enter serial number" ${isRequired}>
                </div>
            </div>
        </div>
    `;
}

// Global Checkbox Handler logic (can be outside document.ready)
function attachCheckboxHandler(formId) {
    $(`#${formId}`).off('change', '.peripheral-checkbox').on('change', '.peripheral-checkbox', function() {
        const parentRow = $(this).closest('.peripheral-row');
        const statusDropdown = parentRow.find('.peripheral-status');
        const serialInput = parentRow.find('.peripheral-serial');
        const isChecked = this.checked;

        statusDropdown.prop('disabled', !isChecked);
        serialInput.prop('disabled', !isChecked);
        
        if (!isChecked) {
            statusDropdown.val('unidentified'); 
            serialInput.removeAttr('required'); 
        } else {
            // Set a default status when checked if it's the ADD form, 
            // otherwise leave the status as is for the UPDATE form
            if (formId === 'addPeripheralForm') {
                statusDropdown.val('serviceable'); 
            }
            serialInput.attr('required', 'required'); 
        }
    });
}


// --- NEW 4. VIEW REGISTRATION DETAILS FUNCTION (using Swal) ---
window.viewRegistrationDetails = function(regno) {
    Swal.fire({
        title: 'Fetching Registration Details...',
        text: `Looking up details for Reg No. ${regno}...`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            
            // AJAX call to fetch the registration details
            $.ajax({
                url: 'get_registration_details.php', 
                type: 'GET',
                data: { regno: regno },
                dataType: 'json',
                success: function(response) {
                    Swal.hideLoading();
                    
                    if (response.success && response.data) {
                        const data = response.data;
                        const htmlContent = `
                            <div class="text-start mt-3">
                                <p class="mb-1"><strong>Registration Number:</strong> ${regno}</p>
                                <p class="mb-1"><strong>Computer Name:</strong> ${data.computer_name || 'N/A'}</p>
                                <p class="mb-1"><strong>SN Number:</strong> ${data.serial_number || 'N/A'}</p>
                                <p class="mb-1"><strong>Luggage Number:</strong> ${data.regkit_serial_num || 'N/A'}</p>
                            </div>
                        `;

                        Swal.update({
                            title: `Registration Details for Kit ${regno}`,
                            html: htmlContent,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonText: 'Close'
                        });

                    } else {
                        Swal.update({
                            title: 'Details Not Found',
                            html: `<p>Could not find registration details for Reg No. <strong>${regno}</strong>.</p><p>${response.message}</p>`,
                            icon: 'warning',
                            showConfirmButton: true,
                            confirmButtonText: 'Close'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.update({
                        title: 'Error',
                        text: 'Failed to connect to the server to fetch data.',
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonText: 'Close'
                    });
                }
            });
        }
    });
}

// --- 3. UPDATE PERIPHERAL FUNCTION (using Swal) ---
window.updatePeripheral = function(id) {
    // 1. Fetch existing data for the selected record
    $.ajax({
        url: 'get_peripheral_data.php', 
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (!response.success) {
                Swal.fire('Error', response.message, 'error');
                return;
            }
            
            const record = response.data;
            let peripheralRows = '';
            
            // Generate rows using the helper function and fetched record data
            peripherals.forEach(p => {
                peripheralRows += generatePeripheralRow(p.name, p.label, record);
            });
            
            // Build the final form HTML
            const formHtml = `
                <form id="updatePeripheralForm">
                    <input type="hidden" name="id" value="${id}">
                    <div class="mb-3 text-start">
                        <label for="regno_display" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="regno_display" value="${record.regno}" disabled>
                        <input type="hidden" name="regno" value="${record.regno}">
                    </div>
                    <hr>
                    <div class="text-start">
                        <p class="fw-bold mb-2 text-primary">Update Peripheral Details:</p>
                        ${peripheralRows}
                    </div>
                </form>
            `;

            // 2. Display the SweetAlert Modal
            Swal.fire({
                title: `Update Inventory (ID: ${id})`,
                html: formHtml,
                icon: 'warning',
                width: '550px',
                showCancelButton: true,
                confirmButtonText: 'Save Changes',
                showLoaderOnConfirm: true,
                focusConfirm: false,
                didOpen: () => {
                    // Attach the dynamic checkbox handler to the new form
                    attachCheckboxHandler('updatePeripheralForm');
                },
                preConfirm: () => {
                    // Validation logic for required serials
                    let serialsValid = true;
                    $('#updatePeripheralForm .peripheral-checkbox:checked').each(function() {
                        const serialInput = $(this).closest('.peripheral-row').find('.peripheral-serial');
                        if (!serialInput.val().trim()) {
                            serialsValid = false;
                            serialInput.addClass('is-invalid');
                        } else {
                            serialInput.removeClass('is-invalid');
                        }
                    });

                    if (!serialsValid) {
                        Swal.showValidationMessage('Please provide a Serial Number for all checked peripherals.');
                        return false;
                    }
                    
                    const formData = $('#updatePeripheralForm').serialize();
                    
                    // 3. AJAX call to update_peripheral.php
                    return $.ajax({
                        url: 'update_peripheral.php', 
                        type: 'POST',
                        data: formData,
                        dataType: 'json'
                    })
                    .then(response => {
                        if (!response.success) {
                            throw new Error(response.message || 'Update failed.');
                        }
                        return response;
                    })
                    .catch(error => {
                        let errorMessage = error.message || (error.responseJSON ? error.responseJSON.message : (error.responseText || 'Server error.'));
                        Swal.showValidationMessage(`Request failed: ${errorMessage}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Updated!', result.value.message, 'success')
                    .then(() => {
                        window.location.reload(); 
                    });
                }
            });

        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'Failed to fetch record data for update.', 'error');
        }
    });
};


window.togglePeripheralFreeze = function(id, currentlyFrozen) {
    const action = currentlyFrozen ? 'unfreeze' : 'freeze';
    Swal.fire({
        title: currentlyFrozen ? 'Unfreeze inventory record?' : 'Freeze inventory record?',
        text: currentlyFrozen ? 'This will allow the record to be edited and deleted again.' : 'A frozen record cannot be edited or deleted until it is unfrozen.',
        icon: currentlyFrozen ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonText: currentlyFrozen ? 'Yes, unfreeze' : 'Yes, freeze',
        showLoaderOnConfirm: true,
        preConfirm: () => $.ajax({ url: 'freeze_peripheral.php', type: 'POST', data: { id:id, action:action }, dataType:'json' })
            .then(r => { if(!r.success) throw new Error(r.message || 'Operation failed.'); return r; })
            .catch(e => { Swal.showValidationMessage(e.message); })
    }).then(result => { if(result.isConfirmed) Swal.fire('Success', result.value.message, 'success').then(()=>location.reload()); });
};

// --- JQUERY DOCUMENT READY ---
$(document).ready(function() {
    
    // --- ADD PERIPHERAL BUTTON CLICK HANDLER (using Swal) ---
    $('#createPeripheralBtn').on('click', function() {
        // ... (The AJAX call to fetch_available_regnos.php and the subsequent Swal.fire) ...
        $.ajax({
            url: 'fetch_inventory_regnos.php', 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let regNoOptions = response.regnos.map(regno => 
                        `<option value="${regno}">${regno}</option>`
                    ).join('');
                    
                    if (regNoOptions === '') {
                         regNoOptions = '<option value="" disabled selected>No available RegNos found (All kits have inventory records)</option>';
                    }

                    // Use the centralized generatePeripheralRow function for ADD form (pass no record data)
                    let peripheralRows = peripherals.map(p => generatePeripheralRow(p.name, p.label, {})).join('');

                    const formHtml = `
                        <form id="addPeripheralForm">
                            <div class="mb-3 text-start">
                                <label for="regno_select" class="form-label">Registration Number</label>
                                <select class="form-select" id="regno_select" name="regno" required ${response.regnos.length === 0 ? 'disabled' : ''}>
                                    <option value="" disabled selected>Select Registration Number</option>
                                    ${regNoOptions}
                                </select>
                            </div>
                            <hr>
                            <div class="text-start">
                                <p class="fw-bold mb-2 text-primary">Individual Peripheral Details:</p>
                                ${peripheralRows}
                            </div>
                        </form>
                    `;

                    Swal.fire({
                        title: 'Add New Peripheral Inventory',
                        html: formHtml,
                        icon: 'info',
                        width: '550px',
                        showCancelButton: true,
                        confirmButtonText: 'Save Inventory',
                        showLoaderOnConfirm: true,
                        focusConfirm: false,
                        didOpen: () => {
                            // Attach the dynamic checkbox handler to the new form
                            attachCheckboxHandler('addPeripheralForm');
                        },
                        preConfirm: () => {
                            // ... (preConfirm validation and AJAX to add_peripheral.php remains the same) ...
                            if (!$('#regno_select').val()) {
                                Swal.showValidationMessage('Please select a Registration Number.');
                                return false;
                            }
                            
                            let serialsValid = true;
                            $('#addPeripheralForm .peripheral-checkbox:checked').each(function() {
                                const parentRow = $(this).closest('.peripheral-row');
                                const serialInput = parentRow.find('.peripheral-serial');
                                if (!serialInput.val().trim()) {
                                    serialsValid = false;
                                    serialInput.addClass('is-invalid');
                                } else {
                                    serialInput.removeClass('is-invalid');
                                }
                            });

                            if (!serialsValid) {
                                Swal.showValidationMessage('Please provide a Serial Number for all checked peripherals.');
                                return false;
                            }
                            
                            const formData = $('#addPeripheralForm').serialize();
                            
                            return $.ajax({
                                url: 'add_peripheral.php', 
                                type: 'POST',
                                data: formData,
                                dataType: 'json'
                            })
                            .then(response => {
                                if (!response.success) {
                                    throw new Error(response.message || 'Insertion failed.');
                                }
                                return response;
                            })
                            .catch(error => {
                                let errorMessage = error.message || (error.responseJSON ? error.responseJSON.message : (error.responseText || 'Server error.'));
                                Swal.showValidationMessage(`Request failed: ${errorMessage}`);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Success!', result.value.message, 'success')
                            .then(() => {
                                window.location.reload(); 
                            });
                        }
                    });

                } else {
                    Swal.fire('Error', 'Could not fetch registration numbers: ' + response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to connect to server to fetch registration numbers.', 'error');
            }
        });
    });

    // --- DELETE PERIPHERAL FUNCTION (Unchanged) ---
    window.deletePeripheral = function(id) {
        // ... (deletePeripheral function logic remains the same) ...
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! This will delete the peripheral record associated with ID: " + id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            
            preConfirm: () => {
                return $.ajax({
                    url: 'delete_peripheral.php', 
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json'
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message || 'Deletion failed.');
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message || 'Server error.'}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    result.value.message,
                    'success'
                ).then(() => {
                    window.location.reload(); 
                });
            }
        });
    }
});

document.querySelector('#inventoryFilterForm')?.addEventListener('change', function(event) {
    if (event.target.matches('select, input[type="date"]')) {
        const pageInput = this.querySelector('input[name="page"]');
        if (pageInput) pageInput.value = 1;
        this.submit();
    }
});

</script>

<?php include 'admin_footer.php'; ?>
