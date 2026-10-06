<?php
require_once __DIR__ . '/auth.php';
$pageTitle='Registration Kits';
$pageSubtitle='Registration kit inventory, location, assignment and history';
$activePage='kits';
include 'admin_header.php';
$csrf=$_SESSION['csrf_token'];
$currentYear=(int)date('Y');
$currentQuarter='Q'.(int)ceil((int)date('n')/3);
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
  <div><h2 class="h4 mb-1">Registration Kits</h2><div class="muted small">Track the Leyte-held kits, transferred kits, validation, current-quarter assignment, history and remarks.</div></div>
  <button class="btn btn-primary" onclick="openKit('new')"><i class="bi bi-plus-lg me-1"></i>Add Registration Kit</button>
</div>

<div class="row g-3 mb-4">
  <?php foreach([
    ['total','Total Kits','bi-box-seam',''],
    ['leyte','Leyte Inventory','bi-geo-alt',''],
    ['transferred','Transferred','bi-truck',''],
    ['validated','Validated','bi-patch-check',''],
    ['unvalidated','Unvalidated','bi-exclamation-circle',''],
    ['assigned','Assigned This Quarter','bi-person-check','']
  ] as $s): ?>
  <div class="col-6 col-md-4 col-xl-2"><div class="card-ca stat-card h-100"><div class="stat-icon"><i class="bi <?= $s[2] ?>"></i></div><div><div class="stat-value" id="stat-<?= $s[0] ?>">0</div><div class="stat-label"><?= $s[1] ?></div></div></div></div>
  <?php endforeach; ?>
</div>

<div class="card-ca mb-4">
  <div class="p-3 border-bottom bg-light-subtle rounded-top">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div><div class="fw-semibold">Inventory Classification</div><div class="small muted">The system tags a kit as <b>Leyte Inventory</b> unless its kit-serial field contains a transfer notation such as “TRANSFERED TO …”.</div></div>
      <span class="badge bg-primary-subtle text-primary-emphasis border" id="periodBadge">Current quarter: <?= htmlspecialchars($currentYear.' '.$currentQuarter) ?></span>
    </div>
  </div>
  <div class="toolbar">
    <div class="row g-2 align-items-end">
      <div class="col-lg-4 col-md-6"><label class="form-label small fw-semibold">Search</label><input id="search" class="form-control" placeholder="Reg. no., code, computer, serial, property, personnel..."></div>
      <div class="col-md-2"><label class="form-label small fw-semibold">Location</label><select id="location" class="form-select"><option value="">All Locations</option><option>Leyte Inventory</option><option>Transferred</option></select></div>
      <div class="col-md-2"><label class="form-label small fw-semibold">Validation</label><select id="status" class="form-select"><option value="">All Status</option><option>Validated</option><option>Unvalidated</option></select></div>
      <div class="col-md-2"><label class="form-label small fw-semibold">Assignment</label><select id="assignment" class="form-select"><option value="">All</option><option value="Assigned">Assigned This Quarter</option><option value="Unassigned">Unassigned This Quarter</option></select></div>
      <div class="col-md-2 d-grid"><button class="btn btn-light border" onclick="loadKits()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button></div>
    </div>
  </div>
  <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2"><div class="small muted" id="result">Loading...</div><div class="small muted">Use the location tag to separate the Leyte-held inventory from kits marked as transferred.</div></div>
  <div class="table-wrap"><table class="table table-hover mb-0 align-middle"><thead><tr><th>Reg. No.</th><th>Kit / Computer</th><th>Location</th><th>Validation</th><th>Current Quarter Assignment</th><th>Remarks</th><th class="text-end">Actions</th></tr></thead><tbody id="kitBody"></tbody></table></div>
</div>

<!-- Add / Update -->
<div class="modal fade" id="kitModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable modal-fixed-scroll"><div class="modal-content border-0 shadow"><form id="kitForm">
  <div class="modal-header"><div><h5 class="modal-title" id="kitTitle">Add Registration Kit</h5><div class="small muted">Registration kit master record</div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body"><div class="row g-3">
    <div class="col-md-4"><label class="form-label">Registration No. *</label><input type="number" class="form-control" name="regno" id="k_regno" required></div>
    <div class="col-md-4"><label class="form-label">Reg. Code *</label><input class="form-control" name="reg_code" id="k_reg_code" required></div>
    <div class="col-md-4"><label class="form-label">Machine ID *</label><input type="number" class="form-control" name="machine_id" id="k_machine" required></div>
    <div class="col-md-6"><label class="form-label">Computer Name *</label><input class="form-control" name="computer_name" id="k_computer" required></div>
    <div class="col-md-6"><label class="form-label">Registration Kit Serial</label><input class="form-control" name="regkit_serial_num" id="k_kitserial"></div>
    <div class="col-md-6"><label class="form-label">Serial Number *</label><input class="form-control" name="serial_number" id="k_serial" required></div>
    <div class="col-md-6"><label class="form-label">Property Number *</label><input class="form-control" name="property_num" id="k_property" required></div>
    <div class="col-md-6"><label class="form-label">Password *</label><div class="input-group"><input class="form-control" name="password" id="k_password" required><button type="button" class="btn btn-light border" onclick="togglePw()"><i class="bi bi-eye"></i></button></div></div>
    <div class="col-md-6"><label class="form-label">RCS Username Code *</label><input class="form-control" name="rcs_uname_code" id="k_rcs" required></div>
    <div class="col-md-6"><label class="form-label">Registration Center Code *</label><input class="form-control" name="reg_center_code" id="k_center" required></div>
    <div class="col-12"><div class="alert alert-info small mb-0"><i class="bi bi-info-circle me-1"></i>To tag a kit as transferred, retain the existing transfer notation in the <b>Registration Kit Serial</b> field, e.g. <b>TRANSFERED TO SAMAR</b>. The inventory dashboard will classify it automatically.</div></div>
  </div></div>
  <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary" id="kitSave"><i class="bi bi-check2 me-1"></i>Save</button></div>
</form></div></div></div>

<!-- History / Remarks -->
<div class="modal fade" id="kitHistoryModal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable modal-fixed-scroll"><div class="modal-content border-0 shadow">
  <div class="modal-header"><div><h5 class="modal-title">Registration Kit History</h5><div class="small muted" id="historySubtitle"></div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">
    <div class="row g-3 mb-4" id="historySummary"></div>
    <div class="card border-0 bg-light-subtle mb-4"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-semibold"><i class="bi bi-chat-left-text me-1"></i>Remarks</div><span class="badge bg-primary-subtle text-primary-emphasis border">Kit-specific remarks</span></div><div id="kitRemarksBody" class="mb-3">Loading...</div><div class="row g-2 align-items-end"><div class="col-md-10"><label class="form-label small fw-semibold">Add Remark *</label><textarea id="kitRemarkInput" class="form-control" rows="2" placeholder="Enter a remark for this registration kit..."></textarea></div><div class="col-md-2 d-grid"><button type="button" class="btn btn-primary" onclick="addKitRemark()"><i class="bi bi-plus-circle me-1"></i>Add Remark</button></div></div></div></div>
    <div class="fw-semibold mb-2"><i class="bi bi-clock-history me-1"></i>Record History</div>
    <div class="table-responsive"><table class="table table-sm align-middle"><thead><tr><th>Date</th><th>Action</th><th>Changed By</th><th>Snapshot / Details</th></tr></thead><tbody id="historyBody"></tbody></table></div>
  </div>
  <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button></div>
</div></div></div>

<style>
.modal-fixed-scroll{height:calc(100vh - 1rem);max-height:calc(100vh - 1rem);margin-top:.5rem;margin-bottom:.5rem}.modal-fixed-scroll .modal-content{height:100%;max-height:100%;display:flex;flex-direction:column;min-height:0}.modal-fixed-scroll .modal-content>form{display:flex;flex:1 1 auto;flex-direction:column;min-height:0}.modal-fixed-scroll .modal-header,.modal-fixed-scroll .modal-footer{flex:0 0 auto;background:#fff;position:relative;z-index:2}.modal-fixed-scroll .modal-body{flex:1 1 auto;min-height:0;overflow-y:auto!important;overflow-x:hidden;-webkit-overflow-scrolling:touch}.modal-fixed-scroll .modal-footer{border-top:1px solid #e5e7eb;box-shadow:0 -2px 8px rgba(15,23,42,.06)}
.kit-location-badge{font-size:.72rem}.kit-row-muted{opacity:.78}.remark-item{border-left:3px solid #0d6efd;background:#f8fafc;border-radius:.5rem;padding:.65rem .75rem}.history-snapshot{font-size:.75rem;line-height:1.45;max-width:560px}.tag-filter-card{border:1px solid #e5e7eb;border-radius:.75rem;background:#fff}
@media(max-width:767.98px){.modal-fixed-scroll{height:calc(100vh - .5rem);max-height:calc(100vh - .5rem);margin-top:.25rem;margin-bottom:.25rem}.modal-fixed-scroll .modal-header,.modal-fixed-scroll .modal-footer{padding:.75rem}.table-wrap{overflow-x:auto}}
</style>

<script>
const CSRF=<?=json_encode($csrf)?>,api='registration_api.php';
let mode='new',currentKitRegno=null;
const searchInput=document.getElementById('search'),statusSelect=document.getElementById('status'),locationSelect=document.getElementById('location'),assignmentSelect=document.getElementById('assignment'),kitFormEl=document.getElementById('kitForm'),kitBodyEl=document.getElementById('kitBody');
function getModal(id){const el=document.getElementById(id);if(!el)throw new Error('Modal element not found: '+id);if(typeof bootstrap==='undefined'||!bootstrap.Modal)throw new Error('Bootstrap JavaScript failed to load. Please refresh the page.');return bootstrap.Modal.getOrCreateInstance(el)}
function esc(s){return String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]))}
function af(url,opts={}){opts.headers=Object.assign({'X-CSRF-Token':CSRF},opts.headers||{});return fetch(url,opts).then(async r=>{let j=await r.json().catch(()=>({success:false,message:'Invalid response'}));if(!r.ok||j.success===false)throw new Error(j.message||'Request failed');return j})}
function badge(text,kind='secondary'){return `<span class="badge bg-${kind}-subtle text-${kind}-emphasis border kit-location-badge">${esc(text)}</span>`}
function loadKits(){
 const p=new URLSearchParams({action:'list',search:searchInput.value,status:statusSelect.value,location:locationSelect.value,assignment:assignmentSelect.value});
 af(api+'?'+p).then(j=>{
  document.getElementById('stat-total').textContent=j.counts.total;document.getElementById('stat-leyte').textContent=j.counts.leyte;document.getElementById('stat-transferred').textContent=j.counts.transferred;document.getElementById('stat-validated').textContent=j.counts.validated;document.getElementById('stat-unvalidated').textContent=j.counts.unvalidated;document.getElementById('stat-assigned').textContent=j.counts.assigned;
  document.getElementById('periodBadge').textContent='Current quarter: '+j.period.year+' '+j.period.quarter;
  document.getElementById('result').textContent=j.data.length+' kit(s) shown';
  kitBodyEl.innerHTML=j.data.length?j.data.map(r=>{
    const loc=r.location_status==='Transferred'?badge('Transferred'+(r.transfer_destination?' · '+r.transfer_destination:''),'danger'):badge('Leyte Inventory','success');
    const val=r.kit_status==='Validated'?badge('Validated','success'):badge('Unvalidated','warning');
    const asg=r.assignment_status==='Assigned'?`<div>${badge('Assigned','primary')}</div><div class="small muted mt-1">${esc(r.assigned_personnel||'Personnel')}</div>`:badge('Unassigned','secondary');
    return `<tr class="${r.location_status==='Transferred'?'kit-row-muted':''}"><td class="fw-semibold">${esc(r.regno)}</td><td><div class="fw-semibold">${esc(r.computer_name)}</div><div class="small muted">Code ${esc(r.reg_code)} · Machine ${esc(r.machine_id)}</div><div class="small muted">Serial: ${esc(r.serial_number)}</div></td><td>${loc}</td><td>${val}</td><td>${asg}</td><td><button class="btn btn-sm btn-light border" onclick="openKitHistory(${r.regno})"><i class="bi bi-chat-left-text me-1"></i>History / Remarks</button></td><td class="text-end text-nowrap"><button class="btn btn-light border action-btn" title="View history" onclick="openKitHistory(${r.regno})"><i class="bi bi-clock-history"></i></button> <button class="btn btn-light border action-btn" title="Edit" onclick="openKit('edit',${r.regno})"><i class="bi bi-pencil"></i></button> <button class="btn btn-light border action-btn text-danger" title="Delete" onclick="deleteKit(${r.regno})"><i class="bi bi-trash"></i></button></td></tr>`
  }).join(''):`<tr><td colspan="7"><div class="empty-state"><i class="bi bi-box-seam fs-2 d-block mb-2"></i>No registration kits found for the selected filters.</div></td></tr>`;
 }).catch(e=>Swal.fire({icon:'error',title:'Unable to load',text:e.message}));
}
function openKit(m,regno=null){mode=m;kitFormEl.reset();document.getElementById('k_regno').readOnly=m==='edit';document.getElementById('kitTitle').textContent=m==='new'?'Add Registration Kit':'Update Registration Kit';document.getElementById('kitSave').innerHTML=m==='new'?'<i class="bi bi-check2 me-1"></i>Save':'<i class="bi bi-save me-1"></i>Update';if(m==='edit'){af(api+'?action=get&regno='+encodeURIComponent(regno)).then(j=>{const r=j.data;[['regno','regno'],['reg_code','reg_code'],['machine','machine_id'],['computer','computer_name'],['kitserial','regkit_serial_num'],['serial','serial_number'],['property','property_num'],['password','password'],['rcs','rcs_uname_code'],['center','reg_center_code']].forEach(x=>document.getElementById('k_'+x[0]).value=r[x[1]]??'');getModal('kitModal').show()}).catch(e=>Swal.fire({icon:'error',text:e.message}))}else getModal('kitModal').show()}
kitFormEl.addEventListener('submit',e=>{e.preventDefault();const fd=new FormData(kitFormEl);fd.append('action',mode==='new'?'save':'update');Swal.fire({title:mode==='new'?'Add registration kit?':'Save changes?',icon:'question',showCancelButton:true,confirmButtonText:'Continue'}).then(r=>{if(!r.isConfirmed)return;af(api,{method:'POST',body:fd}).then(j=>{getModal('kitModal').hide();Swal.fire({icon:'success',title:'Saved',text:j.message,timer:1400,showConfirmButton:false});loadKits()}).catch(e=>Swal.fire({icon:'error',text:e.message}))})});
function deleteKit(regno){Swal.fire({title:'Delete registration kit?',text:'The master record will be deleted, but a history snapshot will be preserved.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',confirmButtonText:'Delete'}).then(r=>{if(!r.isConfirmed)return;const fd=new FormData();fd.append('action','delete');fd.append('regno',regno);af(api,{method:'POST',body:fd}).then(j=>{Swal.fire({icon:'success',title:'Deleted',text:j.message,timer:1500,showConfirmButton:false});loadKits()}).catch(e=>Swal.fire({icon:'error',text:e.message}))})}
function togglePw(){const x=document.getElementById('k_password');x.type=x.type==='password'?'text':'password'}
function openKitHistory(regno){currentKitRegno=regno;document.getElementById('historySubtitle').textContent='Registration Kit No. '+regno;document.getElementById('kitRemarksBody').innerHTML='<div class="text-muted">Loading...</div>';document.getElementById('historyBody').innerHTML='<tr><td colspan="4" class="text-muted">Loading...</td></tr>';document.getElementById('kitRemarkInput').value='';getModal('kitHistoryModal').show();af(api+'?action=history&regno='+encodeURIComponent(regno)).then(j=>{renderRemarks(j.remarks||[]);renderHistory(j.history||[])}).catch(e=>{document.getElementById('kitRemarksBody').innerHTML='<div class="text-danger">'+esc(e.message)+'</div>';document.getElementById('historyBody').innerHTML='<tr><td colspan="4" class="text-danger">Unable to load history.</td></tr>'})}
function renderRemarks(items){const b=document.getElementById('kitRemarksBody');if(!items.length){b.innerHTML='<div class="text-muted small">No remarks have been recorded for this kit.</div>';return}b.innerHTML=items.map(r=>`<div class="remark-item mb-2"><div class="d-flex justify-content-between gap-2"><div>${esc(r.remark)}</div><button class="btn btn-sm btn-outline-danger" onclick="deleteKitRemark(${r.id})" title="Delete remark"><i class="bi bi-trash"></i></button></div><div class="small text-muted mt-1">${esc(r.created_by||'System')} · ${esc(r.created_at)}</div></div>`).join('')}
function renderHistory(items){const b=document.getElementById('historyBody');if(!items.length){b.innerHTML='<tr><td colspan="4" class="text-muted">No history yet. New changes will appear here.</td></tr>';return}b.innerHTML=items.map(h=>{const s=h.snapshot||{};const summary=[s.reg_code?'Code: '+s.reg_code:'',s.computer_name?'Computer: '+s.computer_name:'',s.regkit_serial_num?'Kit Serial: '+s.regkit_serial_num:'',s.serial_number?'Serial: '+s.serial_number:'',s.property_num?'Property: '+s.property_num:''].filter(Boolean).map(esc).join(' · ');return `<tr><td class="text-nowrap">${esc(h.created_at)}</td><td>${badge(h.action,h.action==='DELETED'?'danger':h.action==='UPDATED'?'warning':'success')}</td><td>${esc(h.changed_by||'System')}</td><td><div class="history-snapshot">${summary||'No snapshot details'}</div></td></tr>`}).join('')}
function addKitRemark(){const remark=document.getElementById('kitRemarkInput').value.trim();if(!currentKitRegno||!remark){Swal.fire({icon:'warning',title:'Remark required',text:'Enter a remark before saving.'});return}const fd=new FormData();fd.append('action','add_remark');fd.append('regno',currentKitRegno);fd.append('remark',remark);af(api,{method:'POST',body:fd}).then(j=>{document.getElementById('kitRemarkInput').value='';Swal.fire({icon:'success',title:'Remark added',timer:1000,showConfirmButton:false});return af(api+'?action=history&regno='+encodeURIComponent(currentKitRegno))}).then(j=>{if(j){renderRemarks(j.remarks||[]);renderHistory(j.history||[])}}).catch(e=>Swal.fire({icon:'error',text:e.message}))}
function deleteKitRemark(id){Swal.fire({title:'Delete this remark?',text:'This remark will be removed from the registration kit record.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',confirmButtonText:'Delete'}).then(r=>{if(!r.isConfirmed)return;const fd=new FormData();fd.append('action','delete_remark');fd.append('id',id);fd.append('regno',currentKitRegno);af(api,{method:'POST',body:fd}).then(j=>{Swal.fire({icon:'success',title:'Deleted',timer:900,showConfirmButton:false});return af(api+'?action=history&regno='+encodeURIComponent(currentKitRegno))}).then(j=>{renderRemarks(j.remarks||[]);renderHistory(j.history||[])}).catch(e=>Swal.fire({icon:'error',text:e.message}))})}
searchInput.addEventListener('input',()=>{clearTimeout(window.kitTimer);window.kitTimer=setTimeout(loadKits,250)});statusSelect.addEventListener('change',loadKits);locationSelect.addEventListener('change',loadKits);assignmentSelect.addEventListener('change',loadKits);loadKits();
</script>
<?php include 'admin_footer.php'; ?>
