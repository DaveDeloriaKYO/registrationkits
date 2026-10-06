<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) session_start();

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
    http_response_code(419);
    echo json_encode(['success' => false, 'message' => 'Security token expired.']);
    exit;
}

function ro($data, $code = 200): void {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

function ensureKitTables(PDO $db): void {
    $db->exec("CREATE TABLE IF NOT EXISTS registration_kit_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        regno INT NOT NULL,
        action VARCHAR(30) NOT NULL,
        snapshot_json LONGTEXT NULL,
        changed_by VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_rkh_regno (regno),
        INDEX idx_rkh_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    $db->exec("CREATE TABLE IF NOT EXISTS registration_kit_remarks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        regno INT NOT NULL,
        remark TEXT NOT NULL,
        created_by VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_rkr_regno (regno),
        INDEX idx_rkr_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

function kitLocation(?string $text): array {
    $text = trim((string)$text);
    if (preg_match('/TRANSFER(?:RED)?\s+TO\s+(.+)/i', $text, $m)) {
        return ['Transferred', trim($m[1])];
    }
    if (stripos($text, 'TRANSFER') !== false) return ['Transferred', null];
    return ['Leyte Inventory', null];
}

function snapshot(array $r): array {
    // Deliberately exclude passwords from history snapshots.
    unset($r['password']);
    return $r;
}

try {
    ensureKitTables($conn);
    $a = $_GET['action'] ?? $_POST['action'] ?? 'list';
    $currentYear = (int)date('Y');
    $currentQuarter = 'Q' . (int)ceil((int)date('n') / 3);

    if ($a === 'list') {
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        $location = $_GET['location'] ?? '';
        $assignment = $_GET['assignment'] ?? '';

        $sql = "SELECT r.*,
                    CASE WHEN v.id IS NULL THEN 'Unvalidated' ELSE 'Validated' END AS kit_status,
                    CASE WHEN r.regkit_serial_num LIKE '%TRANSFER%' THEN 'Transferred' ELSE 'Leyte Inventory' END AS location_status,
                    CASE WHEN pq.id IS NULL THEN 'Unassigned' ELSE 'Assigned' END AS assignment_status,
                    pq.personnel_id AS assigned_personnel_id,
                    CONCAT(pi.last_name, ', ', pi.first_name, CASE WHEN COALESCE(pi.middle_name,'')='' THEN '' ELSE CONCAT(' ',pi.middle_name) END) AS assigned_personnel
                FROM registrationdetails r
                LEFT JOIN (SELECT MAX(id) id, reg_code FROM ismd_validated_regkits GROUP BY reg_code) v ON v.reg_code=r.reg_code
                LEFT JOIN personnel_quarterly_history pq ON pq.regno=r.regno AND pq.year=? AND pq.quarter=?
                LEFT JOIN personnel_info pi ON pi.id=pq.personnel_id
                WHERE 1=1";
        $params = [$currentYear, $currentQuarter];
        if ($search !== '') {
            $sql .= " AND (CAST(r.regno AS CHAR) LIKE ? OR r.reg_code LIKE ? OR r.computer_name LIKE ? OR r.serial_number LIKE ? OR r.regkit_serial_num LIKE ? OR r.property_num LIKE ? OR r.reg_center_code LIKE ? OR pi.last_name LIKE ? OR pi.first_name LIKE ?)";
            for ($i = 0; $i < 9; $i++) $params[] = "%{$search}%";
        }
        if (in_array($status, ['Validated','Unvalidated'], true)) $sql .= $status === 'Validated' ? ' AND v.id IS NOT NULL' : ' AND v.id IS NULL';
        if (in_array($location, ['Leyte Inventory','Transferred'], true)) $sql .= $location === 'Transferred' ? " AND r.regkit_serial_num LIKE '%TRANSFER%'" : " AND r.regkit_serial_num NOT LIKE '%TRANSFER%'";
        if ($assignment === 'Assigned') $sql .= ' AND pq.id IS NOT NULL';
        if ($assignment === 'Unassigned') $sql .= ' AND pq.id IS NULL';
        $sql .= ' ORDER BY r.regno ASC';

        $s = $conn->prepare($sql);
        $s->execute($params);
        $rows = $s->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            [$loc, $dest] = kitLocation($row['regkit_serial_num'] ?? '');
            $row['location_status'] = $loc;
            $row['transfer_destination'] = $dest;
        }
        unset($row);

        $total = (int)$conn->query('SELECT COUNT(*) FROM registrationdetails')->fetchColumn();
        $transferred = (int)$conn->query("SELECT COUNT(*) FROM registrationdetails WHERE regkit_serial_num LIKE '%TRANSFER%'")->fetchColumn();
        $validated = (int)$conn->query("SELECT COUNT(DISTINCT r.regno) FROM registrationdetails r INNER JOIN ismd_validated_regkits v ON v.reg_code=r.reg_code")->fetchColumn();
        $assignedStmt = $conn->prepare("SELECT COUNT(DISTINCT regno) FROM personnel_quarterly_history WHERE year=? AND quarter=? AND regno IS NOT NULL");
        $assignedStmt->execute([$currentYear, $currentQuarter]);
        $assigned = (int)$assignedStmt->fetchColumn();
        ro(['success'=>true,'data'=>$rows,'counts'=>[
            'total'=>$total,
            'leyte'=>max(0,$total-$transferred),
            'transferred'=>$transferred,
            'validated'=>$validated,
            'unvalidated'=>max(0,$total-$validated),
            'assigned'=>$assigned,
            'unassigned'=>max(0,$total-$assigned)
        ],'period'=>['year'=>$currentYear,'quarter'=>$currentQuarter]]);
    }

    if ($a === 'get') {
        $regno = (int)($_GET['regno'] ?? 0);
        $s = $conn->prepare('SELECT * FROM registrationdetails WHERE regno=?');
        $s->execute([$regno]);
        $r = $s->fetch(PDO::FETCH_ASSOC);
        if (!$r) ro(['success'=>false,'message'=>'Registration kit not found.'],404);
        [$loc,$dest]=kitLocation($r['regkit_serial_num']??'');
        $r['location_status']=$loc; $r['transfer_destination']=$dest;
        ro(['success'=>true,'data'=>$r]);
    }

    if ($a === 'history') {
        $regno=(int)($_GET['regno']??0);
        $s=$conn->prepare('SELECT id,regno,action,snapshot_json,changed_by,created_at FROM registration_kit_history WHERE regno=? ORDER BY created_at DESC,id DESC');
        $s->execute([$regno]);
        $history=$s->fetchAll(PDO::FETCH_ASSOC);
        foreach($history as &$h){ $h['snapshot']=json_decode($h['snapshot_json']??'{}',true) ?: []; unset($h['snapshot_json']); }
        unset($h);
        $r=$conn->prepare('SELECT id,remark,created_by,created_at FROM registration_kit_remarks WHERE regno=? ORDER BY created_at DESC,id DESC');
        $r->execute([$regno]);
        ro(['success'=>true,'history'=>$history,'remarks'=>$r->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($a === 'add_remark') {
        $regno=(int)($_POST['regno']??0); $remark=trim($_POST['remark']??'');
        if(!$regno || $remark==='') ro(['success'=>false,'message'=>'Registration number and remark are required.'],422);
        $s=$conn->prepare('SELECT regno FROM registrationdetails WHERE regno=?');$s->execute([$regno]);if(!$s->fetch())ro(['success'=>false,'message'=>'Registration kit not found.'],404);
        $s=$conn->prepare('INSERT INTO registration_kit_remarks(regno,remark,created_by) VALUES(?,?,?)');$s->execute([$regno,$remark,$_SESSION['username']??null]);
        ro(['success'=>true,'message'=>'Remark added.']);
    }

    if ($a === 'delete_remark') {
        $id=(int)($_POST['id']??0); $regno=(int)($_POST['regno']??0);
        if(!$id||!$regno)ro(['success'=>false,'message'=>'Invalid remark.'],422);
        $s=$conn->prepare('DELETE FROM registration_kit_remarks WHERE id=? AND regno=?');$s->execute([$id,$regno]);
        if($s->rowCount()===0)ro(['success'=>false,'message'=>'Remark not found.'],404);
        ro(['success'=>true,'message'=>'Remark deleted.']);
    }

    if ($a==='save'||$a==='update') {
        $regno=(int)($_POST['regno']??0);
        $fields=['reg_code','computer_name','regkit_serial_num','serial_number','property_num','machine_id','password','rcs_uname_code','reg_center_code'];
        foreach($fields as $f) if($f!=='regkit_serial_num' && trim((string)($_POST[$f]??''))==='') ro(['success'=>false,'message'=>"Missing required field: $f"],422);
        if(!$regno)ro(['success'=>false,'message'=>'Registration number is required.'],422);
        $old=null;
        if($a==='update'){
            $s=$conn->prepare('SELECT * FROM registrationdetails WHERE regno=?');$s->execute([$regno]);$old=$s->fetch(PDO::FETCH_ASSOC);if(!$old)ro(['success'=>false,'message'=>'Registration kit not found.'],404);
            $sql='UPDATE registrationdetails SET reg_code=?,computer_name=?,regkit_serial_num=?,serial_number=?,property_num=?,machine_id=?,password=?,rcs_uname_code=?,reg_center_code=? WHERE regno=?';
            $p=[$_POST['reg_code'],$_POST['computer_name'],$_POST['regkit_serial_num']??null,$_POST['serial_number'],$_POST['property_num'],(int)$_POST['machine_id'],$_POST['password'],$_POST['rcs_uname_code'],$_POST['reg_center_code'],$regno];
        } else {
            $s=$conn->prepare('SELECT regno FROM registrationdetails WHERE regno=?');$s->execute([$regno]);if($s->fetch())ro(['success'=>false,'message'=>'Registration number already exists.'],409);
            $sql='INSERT INTO registrationdetails(regno,reg_code,computer_name,regkit_serial_num,serial_number,property_num,machine_id,password,rcs_uname_code,reg_center_code) VALUES(?,?,?,?,?,?,?,?,?,?)';
            $p=[$regno,$_POST['reg_code'],$_POST['computer_name'],$_POST['regkit_serial_num']??null,$_POST['serial_number'],$_POST['property_num'],(int)$_POST['machine_id'],$_POST['password'],$_POST['rcs_uname_code'],$_POST['reg_center_code']];
        }
        $conn->beginTransaction();
        try{
            $s=$conn->prepare($sql);$s->execute($p);
            $s=$conn->prepare('SELECT * FROM registrationdetails WHERE regno=?');$s->execute([$regno]);$current=$s->fetch(PDO::FETCH_ASSOC);
            $action=$a==='save'?'CREATED':'UPDATED';
            $h=$conn->prepare('INSERT INTO registration_kit_history(regno,action,snapshot_json,changed_by) VALUES(?,?,?,?)');
            $h->execute([$regno,$action,json_encode(snapshot($current),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),$_SESSION['username']??null]);
            $conn->commit();
        }catch(Throwable $e){if($conn->inTransaction())$conn->rollBack();throw $e;}
        ro(['success'=>true,'message'=>$a==='save'?'Registration kit added.':'Registration kit updated.']);
    }

    if ($a==='delete') {
        $regno=(int)($_POST['regno']??0);if(!$regno)ro(['success'=>false,'message'=>'Invalid registration number.'],422);
        $s=$conn->prepare('SELECT * FROM registrationdetails WHERE regno=?');$s->execute([$regno]);$old=$s->fetch(PDO::FETCH_ASSOC);if(!$old)ro(['success'=>false,'message'=>'Registration kit not found.'],404);
        $conn->beginTransaction();
        try{
            $h=$conn->prepare('INSERT INTO registration_kit_history(regno,action,snapshot_json,changed_by) VALUES(?,?,?,?)');$h->execute([$regno,'DELETED',json_encode(snapshot($old),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),$_SESSION['username']??null]);
            $d=$conn->prepare('DELETE FROM registrationdetails WHERE regno=?');$d->execute([$regno]);
            $conn->commit();
        }catch(Throwable $e){if($conn->inTransaction())$conn->rollBack();throw $e;}
        ro(['success'=>true,'message'=>'Registration kit deleted. History was preserved.']);
    }

    ro(['success'=>false,'message'=>'Unknown action.'],400);
}catch(Throwable $e){
    error_log('registration_api.php: '.$e->getMessage());
    ro(['success'=>false,'message'=>'Database operation failed. Please check the system logs.'],500);
}
