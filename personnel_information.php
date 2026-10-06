<?php
require_once __DIR__ . '/auth.php';
$pageTitle='Personnel Information'; $pageSubtitle='Quarterly personnel and contract management'; $activePage='personnel';
include 'admin_header.php';
$csrf=$_SESSION['csrf_token'];
$currentYear=(int)date('Y'); $currentQuarter='Q'.ceil(date('n')/3);
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
  <div><h2 class="h4 mb-1">Personnel Information</h2><div class="muted small">Manage personnel assignments, quarterly renewals, contracts, and history.</div></div>
  <button class="btn btn-primary px-3" onclick="openPersonnelModal('new')"><i class="bi bi-person-plus me-1"></i> Add Personnel</button>
</div>

<div class="row g-3 mb-4" id="stats">
<?php foreach([['total','Total Personnel','bi-people'],['active','Active','bi-person-check'],['new','New','bi-person-plus'],['renewed','Renewed','bi-arrow-repeat'],['inactive','Inactive','bi-person-x'],['expired','Expired','bi-calendar-x']] as $s): ?>
<div class="col-6 col-md-4 col-xl-2"><div class="card-ca stat-card h-100"><div class="stat-icon"><i class="bi <?= $s[2] ?>"></i></div><div><div class="stat-value" id="stat-<?= $s[0] ?>">0</div><div class="stat-label"><?= $s[1] ?></div></div></div></div>
<?php endforeach; ?>
</div>

<div class="card-ca">
<div class="toolbar">
<div class="row g-2 align-items-end">
 <div class="col-6 col-md-2"><label class="form-label small fw-semibold">Year</label><select id="filterYear" class="form-select"></select></div>
 <div class="col-6 col-md-2"><label class="form-label small fw-semibold">Quarter</label><select id="filterQuarter" class="form-select"><option>Q1</option><option>Q2</option><option>Q3</option><option>Q4</option></select></div>
 <div class="col-6 col-md-2"><label class="form-label small fw-semibold">Status</label><select id="filterStatus" class="form-select"><option value="">All Status</option><option>New</option><option>Active</option><option>Renewed</option><option>Inactive</option><option>Expired</option></select></div>
 <div class="col-6 col-md-2"><label class="form-label small fw-semibold">NID / Reg. No.</label><input id="filterNid" class="form-control" placeholder="Search NID"></div>
 <div class="col-md-3"><label class="form-label small fw-semibold">Name</label><input id="filterName" class="form-control" placeholder="Search personnel name"></div>
 <div class="col-md-1 d-grid"><button class="btn btn-light border" onclick="loadPersonnel()" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button></div>
</div>
</div>
<div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2"><div class="small muted" id="resultLabel">Loading...</div><div class="small"><span class="badge bg-light text-dark border" id="periodLabel"></span></div></div>
<div class="table-wrap">
<table class="table table-hover mb-0 align-middle"><thead><tr><th>Personnel</th><th>Position</th><th>Municipality</th><th>NID / Reg. No.</th><th>Contract</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody id="personnelBody"></tbody></table>
</div>
</div>

<!-- Personnel modal -->
<div class="modal fade" id="personnelModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable modal-fixed-scroll"><div class="modal-content border-0 shadow quarterly-modal-content">
<form id="personnelForm">
<div class="modal-header"><div><h5 class="modal-title" id="modalTitle">Add Personnel</h5><div class="small muted" id="modalSubtitle">Create master record and quarterly assignment</div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><input type="hidden" name="id" id="f_id"><input type="hidden" name="quarter_id" id="f_quarter_id"><input type="hidden" name="existing_personnel_id" id="f_existing_personnel_id">
<div class="alert alert-info d-flex align-items-start gap-2 py-2" id="existingHelp">
  <i class="bi bi-info-circle mt-1"></i><div><strong>Quarterly workflow:</strong> select an existing personnel record to create a new quarterly renewal record. If the person is not in the list, choose <strong>New Personnel</strong> and create a new master record.</div>
</div>
<div class="row g-3">
<div class="col-12" id="existingPersonnelWrap"><label class="form-label fw-semibold">Existing Personnel / Quarterly Renewal</label><select class="form-select" id="existingPersonnel"><option value="">+ New Personnel — not found in existing personnel records</option></select><div class="form-text">Select a personnel record to renew for the selected Year and Quarter. Existing records are never overwritten.</div></div>
<div class="col-md-4"><label class="form-label">Last Name *</label><input class="form-control" name="last_name" id="f_last" required></div>
<div class="col-md-4"><label class="form-label">First Name *</label><input class="form-control" name="first_name" id="f_first" required></div>
<div class="col-md-4"><label class="form-label">Middle Name</label><input class="form-control" name="middle_name" id="f_middle"></div>
<div class="col-md-4"><label class="form-label">Position *</label><input class="form-control" name="position" id="f_position" required></div>
<div class="col-md-4"><label class="form-label">Municipality</label><select class="form-select" name="municipality" id="f_municipality"><option value="">Select municipality</option><?php
$mun=['Abuyog','Alangalang','Albuera','Babatngon','Barugo','Bato','Burauen','Calubian','Capoocan','Carigara','Dagami','Dulag','Hilongos','Hindang','Inopacan','Isabel','Jaro','Javier','Julita','Kananga','La Paz','Leyte','MacArthur','Mahaplag','Matag-Ob','Matalom','Mayorga','Merida','Palo','Palompon','Pastrana','San Isidro','San Miguel','Santa Fe','Tabango','Tabontabon','Tanauan','Tolosa','Tunga','Villaba','Tacloban City','Ormoc City','Baybay City']; foreach($mun as $m) echo '<option>'.htmlspecialchars($m).'</option>';?></select></div>
<div class="col-md-4"><label class="form-label">NID / Reg. No. *</label><div class="input-group"><input class="form-control" name="regno" id="f_regno" required><button type="button" class="btn btn-outline-primary" id="kitDetailsBtn" onclick="showKitDetails()" title="View registration kit details"><i class="bi bi-pc-display"></i></button></div><div class="form-text" id="kitLookupHint">Registration Kit details are matched by Reg. No.</div></div>
<div class="col-md-4"><label class="form-label">Contact No.</label><input class="form-control" name="contact_num" id="f_contact"></div>
<div class="col-md-4"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="f_email"></div>
<div class="col-md-4"><label class="form-label">Year Started *</label><input type="number" class="form-control" name="year_started" id="f_year" min="2000" max="2100" required></div>
<div class="col-12"><div class="card border bg-light-subtle mt-1" id="kitDetailsCard" style="display:none"><div class="card-body py-3"><div class="d-flex justify-content-between align-items-center mb-2"><div class="fw-semibold"><i class="bi bi-box-seam me-1"></i> Registration Kit Details</div><span class="badge bg-light text-dark border" id="kitMatchBadge">Matched</span></div><div class="row g-2 small" id="kitDetailsBody"></div></div></div></div>
<div class="col-12"><hr class="my-1"><div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2"><div><div class="fw-semibold"><i class="bi bi-calendar3 me-1"></i>Quarterly Assignment / Contract</div><div class="small text-muted">This is where you assign the personnel to the selected quarter. Previous quarters are preserved.</div></div><span class="badge rounded-pill bg-primary-subtle text-primary border" id="assignmentPeriodBadge">2026 · Q3</span></div></div>
<div class="col-md-3"><label class="form-label">Year *</label><input type="number" class="form-control" name="year" id="f_qyear" min="2000" max="2100" required></div>
<div class="col-md-3"><label class="form-label">Quarter *</label><select class="form-select" name="quarter" id="f_quarter"><option>Q1</option><option>Q2</option><option>Q3</option><option>Q4</option></select></div>
<div class="col-md-3"><label class="form-label">Contract Start *</label><input type="date" class="form-control" name="contract_start_date" id="f_start" required></div>
<div class="col-md-3"><label class="form-label">Contract End *</label><input type="date" class="form-control" name="contract_end_date" id="f_end" required></div>
<div class="col-md-4" id="quarterStatusWrap"><label class="form-label">Quarter Record Status *</label><select class="form-select" name="quarter_status" id="f_qstatus"><option value="active">Active</option><option value="inactive">Inactive</option></select><div class="form-text">Active/Inactive is stored for this quarter. <strong>Expired</strong> is calculated automatically from the contract end date.</div></div>
</div></div>
<div class="modal-footer sticky-modal-footer"><div class="me-auto small text-muted d-none d-md-block" id="submitHelp"><i class="bi bi-info-circle me-1"></i>Review the quarter and contract dates before saving.</div><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary px-4" id="saveBtn"><i class="bi bi-check2 me-1"></i>Save</button></div>
</form></div></div></div>

<style>
/* Fixed modal shell: header/footer stay visible while ONLY the body scrolls. */
.modal-fixed-scroll{
  height:calc(100vh - 1rem);
  max-height:calc(100vh - 1rem);
  margin-top:.5rem;
  margin-bottom:.5rem;
}
.modal-fixed-scroll .modal-content{
  height:100%;
  max-height:100%;
  display:flex;
  flex-direction:column;
  min-height:0;
}
.modal-fixed-scroll .modal-content > form{
  display:flex;
  flex:1 1 auto;
  flex-direction:column;
  min-height:0;
}
.modal-fixed-scroll .modal-header,
.modal-fixed-scroll .modal-footer{
  flex:0 0 auto;
  background:#fff;
  position:relative;
  z-index:2;
}
.modal-fixed-scroll .modal-body{
  flex:1 1 auto;
  min-height:0;
  overflow-y:auto !important;
  overflow-x:hidden;
  -webkit-overflow-scrolling:touch;
}
.modal-fixed-scroll .modal-footer{
  border-top:1px solid #e5e7eb;
  box-shadow:0 -2px 8px rgba(15,23,42,.06);
}
#assignmentPeriodBadge{font-size:.78rem;}
#quarterStatusWrap .form-text{font-size:.75rem;}
@media(max-width:767.98px){
  .modal-fixed-scroll{height:calc(100vh - .5rem);max-height:calc(100vh - .5rem);margin-top:.25rem;margin-bottom:.25rem;}
  .modal-fixed-scroll .modal-header,.modal-fixed-scroll .modal-footer{padding:.75rem;}
  .modal-fixed-scroll .modal-footer #saveBtn{min-width:145px;}
}
</style>

<!-- History modal -->
<div class="modal fade" id="historyModal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content border-0 shadow"><div class="modal-header"><div><h5 class="modal-title">Personnel History</h5><div class="small muted" id="historyName"></div></div><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="table-responsive"><table class="table table-sm align-middle"><thead><tr><th>Year</th><th>Quarter</th><th>Position</th><th>Municipality</th><th>NID</th><th>Contract</th><th>Status</th><th></th></tr></thead><tbody id="historyBody"></tbody></table></div><hr><div class="d-flex align-items-center justify-content-between mb-2"><div class="fw-semibold">Quarterly Remarks</div><span class="badge bg-primary-subtle text-primary-emphasis border">Remarks are tied to a quarter</span></div><div id="remarksBody" class="small muted">Loading...</div><div class="card border-0 bg-light-subtle mt-3"><div class="card-body p-3"><div class="row g-2 align-items-end"><div class="col-md-4"><label class="form-label small fw-semibold">Quarter for Remark *</label><select id="remarkQuarter" class="form-select form-select-sm"></select></div><div class="col-md-6"><label class="form-label small fw-semibold">Remark *</label><input id="remarkInput" class="form-control form-control-sm" placeholder="Enter a remark for the selected quarter..."></div><div class="col-md-2"><button class="btn btn-primary btn-sm w-100" onclick="addRemark()"><i class="bi bi-plus-circle me-1"></i>Add Remark</button></div></div><div class="small text-muted mt-2"><i class="bi bi-info-circle me-1"></i>Each remark is stored against the selected Year and Quarter and remains visible in the personnel history.</div></div></div></div></div></div></div>

<script>
const CSRF=<?= json_encode($csrf) ?>;
const api='personnel_api.php';
let people=[], currentPerson=null;
function getPersonnelModal(){
  const el=document.getElementById('personnelModal');
  if(!el) throw new Error('Personnel modal element was not found.');
  if(typeof bootstrap==='undefined'||!bootstrap.Modal) throw new Error('Bootstrap JavaScript failed to load. Please refresh the page.');
  return bootstrap.Modal.getOrCreateInstance(el);
}
function getHistoryModal(){
  const el=document.getElementById('historyModal');
  if(!el) throw new Error('History modal element was not found.');
  if(typeof bootstrap==='undefined'||!bootstrap.Modal) throw new Error('Bootstrap JavaScript failed to load. Please refresh the page.');
  return bootstrap.Modal.getOrCreateInstance(el);
}
function esc(s){return String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
function badge(s){let c=String(s).toLowerCase();return `<span class="badge-status status-${c}">${esc(s)}</span>`;}
function initials(p){return ((p.first_name||'')[0]||'')+((p.last_name||'')[0]||'');}
function apiFetch(url,opts={}){opts.headers=Object.assign({'X-CSRF-Token':CSRF},opts.headers||{});return fetch(url,opts).then(async r=>{let j=await r.json().catch(()=>({success:false,message:'Invalid server response'}));if(!r.ok||j.success===false)throw new Error(j.message||j.error||'Request failed');return j;});}
async function loadExistingPersonnel(selectedId=''){
  const sel=document.getElementById('existingPersonnel');
  const year=document.getElementById('filterYear').value;
  const quarter=document.getElementById('filterQuarter').value;
  sel.innerHTML='<option value="">+ New Personnel — not found in existing personnel records</option><option disabled>Loading personnel...</option>';
  try{
    const j=await apiFetch(api+'?action=existing_personnel&year='+encodeURIComponent(year)+'&quarter='+encodeURIComponent(quarter));
    sel.innerHTML='<option value="">+ New Personnel — not found in existing personnel records</option>';
    (j.data||[]).forEach(p=>{
      const o=document.createElement('option');
      o.value=p.id;
      const name=`${p.last_name}, ${p.first_name}${p.middle_name?' '+p.middle_name:''}`;
      const latest=p.latest_quarter?` · Last: ${p.latest_quarter} ${p.latest_year}`:'';
      if(Number(p.already_assigned)===1){
        o.textContent=`${name} — Reg. No. ${p.regno||'N/A'} · Already assigned to ${quarter} ${year}`;
        o.disabled=true;
        o.dataset.alreadyAssigned='1';
      }else{
        o.textContent=`${name} — Reg. No. ${p.regno||'N/A'}${latest}`;
      }
      o.dataset.personnel=JSON.stringify(p);
      sel.appendChild(o);
    });
    if(selectedId) sel.value=String(selectedId);
  }catch(e){
    sel.innerHTML='<option value="">+ New Personnel — not found in existing personnel records</option>';
    Swal.fire({icon:'error',title:'Unable to load personnel',text:e.message});
  }
}

function resetKitDetails(){document.getElementById('kitDetailsCard').style.display='none';document.getElementById('kitDetailsBody').innerHTML='';document.getElementById('kitLookupHint').textContent='Registration Kit details are matched by Reg. No.';}
async function lookupKitDetails(regno,showError=false){
  const value=String(regno||'').trim();
  resetKitDetails();
  if(!value) return null;
  try{
    const j=await apiFetch(api+'?action=kit_details&regno='+encodeURIComponent(value));
    const r=j.data;
    document.getElementById('kitDetailsCard').style.display='block';
    document.getElementById('kitMatchBadge').textContent='Registration Kit Found';
    document.getElementById('kitLookupHint').textContent='Matched to registrationdetails by Reg. No. '+value+'.';
    const items=[['Reg. No.',r.regno],['Reg. Code',r.reg_code],['Computer',r.computer_name],['Machine ID',r.machine_id],['Kit Serial',r.regkit_serial_num],['Serial Number',r.serial_number],['Property No.',r.property_num],['RCS Username',r.rcs_uname_code],['Registration Center',r.reg_center_code]];
    document.getElementById('kitDetailsBody').innerHTML=items.map(([k,v])=>`<div class="col-md-4"><span class="muted">${esc(k)}</span><div class="fw-semibold">${esc(v||'—')}</div></div>`).join('');
    return r;
  }catch(e){
    document.getElementById('kitDetailsCard').style.display='block';
    document.getElementById('kitMatchBadge').textContent='No Kit Record';
    document.getElementById('kitMatchBadge').className='badge bg-warning-subtle text-warning-emphasis border';
    document.getElementById('kitLookupHint').textContent='No registration kit record was found for this Reg. No. You may still enter it manually.';
    document.getElementById('kitDetailsBody').innerHTML='<div class="col-12 text-muted">No matching registration kit record found.</div>';
    if(showError) Swal.fire({icon:'info',title:'No registration kit found',text:e.message});
    return null;
  }
}
function showKitDetails(){lookupKitDetails(document.getElementById('f_regno').value,true);}
function populateExistingPersonnel(p){
  document.getElementById('f_existing_personnel_id').value=p.id||'';
  setForm(p,'existing',null);
  document.getElementById('f_id').value=p.id||'';
  document.getElementById('f_qyear').value=document.getElementById('filterYear').value;
  document.getElementById('f_quarter').value=document.getElementById('filterQuarter').value;
  document.getElementById('f_qstatus').value='active';
  document.getElementById('quarterStatusWrap').style.display='block';
  document.getElementById('modalTitle').textContent='Add Quarterly Personnel';
  document.getElementById('modalSubtitle').textContent='Existing personnel selected — create a new quarterly record without overwriting previous history.';
  document.getElementById('saveBtn').innerHTML='<i class="bi bi-arrow-repeat me-1"></i>Add / Renew Quarter';
  document.getElementById('personnelForm').dataset.mode='existing';
  updateAssignmentPeriod();
  lookupKitDetails(p.regno);
}
function fillYears(){
  const y=document.getElementById('filterYear');
  const now=new Date().getFullYear();
  const minYear=Math.min(2024, now-5);
  const maxYear=now+5;
  let h='';
  for(let year=maxYear; year>=minYear; year--) h+=`<option value="${year}">${year}</option>`;
  y.innerHTML=h;
  y.value=String(<?= $currentYear ?>);
  document.getElementById('filterQuarter').value=<?= json_encode($currentQuarter) ?>;
  document.getElementById('f_year').value=String(now);
  document.getElementById('f_qyear').value=String(now);
  document.getElementById('f_quarter').value=<?= json_encode($currentQuarter) ?>;
  updateAssignmentPeriod();
}

function updateAssignmentPeriod(){
  const y=document.getElementById('f_qyear').value || document.getElementById('filterYear').value;
  const q=document.getElementById('f_quarter').value || document.getElementById('filterQuarter').value;
  const b=document.getElementById('assignmentPeriodBadge');
  if(b) b.textContent=`${y} · ${q}`;
}
function loadPersonnel(){
let p=new URLSearchParams({action:'list',year:document.getElementById('filterYear').value,quarter:document.getElementById('filterQuarter').value,status:document.getElementById('filterStatus').value,nid:document.getElementById('filterNid').value,name:document.getElementById('filterName').value});apiFetch(api+'?'+p).then(j=>{people=j.data;['total','active','new','renewed','inactive','expired'].forEach(k=>document.getElementById('stat-'+k).textContent=j.counts[k]??0);periodLabel.textContent=j.year+' · '+j.quarter;resultLabel.textContent=people.length+' record(s) shown';personnelBody.innerHTML=people.length?people.map(row=>`<tr>
<td><div class="d-flex align-items-center gap-2"><div class="avatar">${esc(initials(row).toUpperCase())}</div><div><div class="fw-semibold">${esc(row.first_name+' '+(row.middle_name?row.middle_name+' ':'')+row.last_name)}</div><div class="small muted">${esc(row.email||'')}</div></div></div></td>
<td>${esc(row.position)}</td><td>${esc(row.municipality||'—')}</td><td><span class="fw-semibold">${esc(row.regno||'—')}</span></td>
<td><div class="small">${esc(row.contract_start_date||'—')} <span class="muted">to</span> ${esc(row.contract_end_date||'—')}</div><div class="muted" style="font-size:.7rem">${esc(row.year&&row.quarter?row.year+' · '+row.quarter:'No quarterly record')}</div></td>
<td>${badge(row.status)}</td><td class="text-end text-nowrap">
<button class="btn btn-light border action-btn" title="View history" onclick="viewHistory(${row.id})"><i class="bi bi-clock-history"></i></button>
<button class="btn btn-light border action-btn" title="Edit" onclick="openPersonnelModal('edit',${row.id})"><i class="bi bi-pencil"></i></button>
<button class="btn btn-light border action-btn" title="Renew" onclick="openPersonnelModal('renew',${row.id})"><i class="bi bi-arrow-repeat"></i></button>
<button class="btn btn-light border action-btn text-danger" title="Archive" onclick="archivePerson(${row.id})"><i class="bi bi-archive"></i></button>
</td></tr>`).join(''):`<tr><td colspan="7"><div class="empty-state"><i class="bi bi-people fs-2 d-block mb-2"></i>No personnel records match your filters.</div></td></tr>`;}).catch(e=>Swal.fire({icon:'error',title:'Unable to load',text:e.message}));}
function setForm(p={},mode='new',hist=null){document.getElementById('personnelForm').reset();['id','quarter_id','existing_personnel_id'].forEach(x=>document.getElementById('f_'+x).value=''); resetKitDetails(); if(mode==='new'){document.getElementById('existingPersonnel').value='';} if(p.id) document.getElementById('f_id').value=p.id;document.getElementById('f_year').value=new Date().getFullYear();document.getElementById('f_qyear').value=document.getElementById('filterYear').value;document.getElementById('f_quarter').value=document.getElementById('filterQuarter').value;
for(let [id,val] of Object.entries({last:p.last_name,first:p.first_name,middle:p.middle_name,position:p.position,municipality:p.municipality,regno:p.regno,contact:p.contact_num,email:p.email,year:p.year_started,start:p.contract_start_date,end:p.contract_end_date})) if(val!=null)document.getElementById('f_'+id).value=val;
if(hist){document.getElementById('f_quarter_id').value=hist.id;document.getElementById('f_qyear').value=hist.year;document.getElementById('f_quarter').value=hist.quarter;document.getElementById('f_start').value=hist.contract_start_date;document.getElementById('f_end').value=hist.contract_end_date;document.getElementById('f_position').value=hist.position;document.getElementById('f_municipality').value=hist.municipality||'';document.getElementById('f_regno').value=hist.regno||'';}
document.getElementById('f_qstatus').value=(hist&&hist.status)?String(hist.status).toLowerCase():'active';
document.getElementById('quarterStatusWrap').style.display='block';document.getElementById('modalTitle').textContent=mode==='new'?'Add Personnel':mode==='renew'?'Renew Personnel':'Update Personnel';document.getElementById('modalSubtitle').textContent=mode==='renew'?'Create a new quarterly record without overwriting history.':mode==='edit'?'Update master and selected quarterly information.':'Create master record and quarterly assignment';document.getElementById('saveBtn').innerHTML=mode==='renew'?'<i class="bi bi-arrow-repeat me-1"></i> Renew':mode==='edit'?'<i class="bi bi-check2 me-1"></i> Update':'<i class="bi bi-check2 me-1"></i> Save';document.getElementById('personnelForm').dataset.mode=mode; updateAssignmentPeriod();}
function openPersonnelModal(mode,id=null){
  const existingWrap=document.getElementById('existingPersonnelWrap');
  const help=document.getElementById('existingHelp');
  const sel=document.getElementById('existingPersonnel');
  if(mode==='new'){
    setForm({},mode);
    existingWrap.style.display='block'; help.style.display='block';
    loadExistingPersonnel().then(()=>getPersonnelModal().show());
    return;
  }
  existingWrap.style.display='none'; help.style.display='none';
  let p=people.find(x=>x.id===id); if(!p)return;
  apiFetch(api+'?action=history&personnel_id='+id).then(j=>{
    currentPerson=j;let row=people.find(x=>x.id===id);let h=(row&&row.quarter_id)?j.history.find(x=>x.id==row.quarter_id):(j.history[0]||null);
    if(mode==='edit')setForm(j.personnel,mode,h);else setForm({...j.personnel,...(h||{})},mode,null);
    document.getElementById('f_existing_personnel_id').value=j.personnel.id;
    lookupKitDetails(document.getElementById('f_regno').value);
    getPersonnelModal().show();
  }).catch(e=>Swal.fire({icon:'error',text:e.message}));
}
document.getElementById('existingPersonnel').addEventListener('change',function(){
  const id=this.value;
  if(!id){setForm({},'new');return;}
  const o=this.options[this.selectedIndex];
  if(o.disabled || o.dataset.alreadyAssigned==='1'){
    this.value='';
    Swal.fire({icon:'info',title:'Already assigned',text:'This personnel already has a quarterly record for the selected Year and Quarter.'});
    return;
  }
  try{populateExistingPersonnel(JSON.parse(o.dataset.personnel||'{}'));}catch(e){Swal.fire({icon:'error',text:'Unable to read the selected personnel record.'});}
});
document.getElementById('f_regno').addEventListener('blur',()=>lookupKitDetails(document.getElementById('f_regno').value));
document.getElementById('f_qyear').addEventListener('change',updateAssignmentPeriod);
document.getElementById('f_quarter').addEventListener('change',updateAssignmentPeriod);
document.getElementById('personnelForm').addEventListener('submit',e=>{e.preventDefault();let mode=e.target.dataset.mode||'new',fd=new FormData(e.target);fd.append('action',mode==='existing'?'add_quarter_existing':mode==='new'?'save':mode==='renew'?'renew':'update');if(mode==='existing')fd.append('personnel_id',document.getElementById('f_existing_personnel_id').value||document.getElementById('f_id').value);if(mode==='renew')fd.append('personnel_id',document.getElementById('f_id').value||currentPerson.personnel.id);Swal.fire({title:(mode==='renew'||mode==='existing')?'Confirm quarterly renewal':'Save changes?',text:(mode==='renew'||mode==='existing')?'A new quarterly history record will be created for the selected year and quarter.':'The information will be updated.',icon:'question',showCancelButton:true,confirmButtonText:'Continue'}).then(r=>{if(!r.isConfirmed)return;apiFetch(api,{method:'POST',body:fd}).then(j=>{getPersonnelModal().hide();Swal.fire({icon:'success',title:'Saved',text:j.message,timer:1600,showConfirmButton:false});loadPersonnel();}).catch(e=>Swal.fire({icon:'error',title:'Not saved',text:e.message}));});});
function viewHistory(id){currentPerson=null;apiFetch(api+'?action=history&personnel_id='+id).then(j=>{currentPerson=j;historyName.textContent=j.personnel.first_name+' '+j.personnel.last_name;historyBody.innerHTML=j.history.length?j.history.map(h=>`<tr><td>${h.year}</td><td><span class="badge bg-primary-subtle text-primary-emphasis border">${esc(h.quarter)}</span></td><td>${esc(h.position)}</td><td>${esc(h.municipality||'—')}</td><td>${esc(h.regno||'—')}</td><td>${esc(h.contract_start_date)} → ${esc(h.contract_end_date)}</td><td>${badge(h.derived_status)}</td><td><button class="btn btn-sm btn-light border" onclick="editHistory(${j.personnel.id},${h.id})"><i class="bi bi-pencil"></i></button></td></tr>`).join(''):'<tr><td colspan="8" class="text-center muted py-4">No quarterly history yet.</td></tr>';populateRemarkQuarters(j.history||[]);loadRemarks(id);getHistoryModal().show();}).catch(e=>Swal.fire({icon:'error',text:e.message}));}
function populateRemarkQuarters(history){const sel=document.getElementById('remarkQuarter');if(!sel)return;sel.innerHTML='';if(!history.length){sel.innerHTML='<option value="">No quarterly record available</option>';return;}history.forEach((h,i)=>{const o=document.createElement('option');o.value=h.id;o.textContent=`${h.year} ${h.quarter} — ${h.position||'Quarterly record'}`;sel.appendChild(o);});}
function editHistory(pid,qid){let h=currentPerson.history.find(x=>x.id==qid);setForm(currentPerson.personnel,'edit',h);getHistoryModal().hide();getPersonnelModal().show();}
function loadRemarks(id){fetch('get_personnel_details.php?personnel_id='+id).then(r=>r.json()).then(j=>{remarksBody.innerHTML=(j.remarks||[]).length?j.remarks.map(x=>{const period=x.year&&x.quarter?`<span class="badge bg-primary-subtle text-primary-emphasis border me-2">${esc(x.year)} ${esc(x.quarter)}</span>`:'<span class="badge bg-secondary-subtle text-secondary-emphasis border me-2">General / Legacy</span>';return `<div class="border-bottom py-2 d-flex gap-2 align-items-start"><div class="flex-grow-1"><div class="mb-1">${period}<span>${esc(x.remark)}</span></div><div class="muted" style="font-size:.72rem">${esc(x.created_by||'System')} · ${esc(x.created_at)}</div></div><button type="button" class="btn btn-sm btn-outline-danger action-btn" title="Delete remark" onclick="deleteRemark(${Number(x.id)})"><i class="bi bi-trash"></i></button></div>`;}).join(''):'No quarterly remarks yet.';}).catch(()=>remarksBody.textContent='No remarks available.');}
function deleteRemark(id){if(!currentPerson)return;Swal.fire({title:'Delete remark?',text:'This remark will be permanently removed.',icon:'warning',showCancelButton:true,confirmButtonText:'Delete',confirmButtonColor:'#dc2626'}).then(r=>{if(!r.isConfirmed)return;let fd=new FormData();fd.append('action','delete_remark');fd.append('personnel_id',currentPerson.personnel.id);fd.append('remark_id',id);apiFetch(api,{method:'POST',body:fd}).then(j=>{loadRemarks(currentPerson.personnel.id);Swal.fire({icon:'success',title:'Deleted',text:j.message,timer:1100,showConfirmButton:false});}).catch(e=>Swal.fire({icon:'error',text:e.message}));});}
function addRemark(){let v=document.getElementById('remarkInput').value.trim();let qid=Number(document.getElementById('remarkQuarter').value||0);if(!currentPerson)return;if(!qid){Swal.fire({icon:'warning',title:'Select a quarter',text:'Choose the Year and Quarter where this remark belongs.'});return;}if(!v){Swal.fire({icon:'warning',title:'Remark required',text:'Enter a remark before saving.'});return;}let fd=new FormData();fd.append('action','remark');fd.append('personnel_id',currentPerson.personnel.id);fd.append('quarterly_history_id',qid);fd.append('remark',v);apiFetch(api,{method:'POST',body:fd}).then(j=>{document.getElementById('remarkInput').value='';loadRemarks(currentPerson.personnel.id);Swal.fire({icon:'success',title:'Remark added',text:j.message,timer:1300,showConfirmButton:false});}).catch(e=>Swal.fire({icon:'error',text:e.message}));}
function archivePerson(id){Swal.fire({title:'Archive personnel?',text:'The master record will be marked inactive and historical quarterly records will be preserved.',icon:'warning',showCancelButton:true,confirmButtonText:'Archive',confirmButtonColor:'#dc2626'}).then(r=>{if(!r.isConfirmed)return;let fd=new FormData();fd.append('action','archive');fd.append('id',id);apiFetch(api,{method:'POST',body:fd}).then(j=>{Swal.fire({icon:'success',title:'Archived',timer:1200,showConfirmButton:false});loadPersonnel();}).catch(e=>Swal.fire({icon:'error',text:e.message}));});}
['filterYear','filterQuarter','filterStatus'].forEach(id=>document.getElementById(id).addEventListener('change',loadPersonnel));
['filterNid','filterName'].forEach(id=>document.getElementById(id).addEventListener('input',()=>{clearTimeout(window._ft);window._ft=setTimeout(loadPersonnel,250)}));
fillYears();
loadPersonnel();
</script>
<?php include 'admin_footer.php'; ?>
