<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

if (session_status()===PHP_SESSION_NONE) session_start();
$csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? '');
if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
    http_response_code(419); echo json_encode(['success'=>false,'message'=>'Security token expired. Please refresh the page.']); exit;
}
function out($data,$code=200){http_response_code($code);echo json_encode($data);exit;}
function qnum($q){return ['Q1'=>1,'Q2'=>2,'Q3'=>3,'Q4'=>4][$q]??0;}
function validDate($d){$x=DateTime::createFromFormat('Y-m-d',$d);return $x && $x->format('Y-m-d')===$d;}
function statusFor($row,$history){
    if (!$row) return 'New';
    $today=new DateTimeImmutable('today');
    if (($row['status']??'active')==='inactive') return 'Inactive';
    if (!empty($row['contract_end_date']) && new DateTimeImmutable($row['contract_end_date']) < $today) return 'Expired';
    if (count($history)<=1) return 'New';
    return 'Renewed';
}
function getHistory(PDO $db,$pid){
    $s=$db->prepare("SELECT id,quarter,year,position,municipality,regno,contract_start_date,contract_end_date,status,created_at FROM personnel_quarterly_history WHERE personnel_id=? ORDER BY year DESC, FIELD(quarter,'Q4','Q3','Q2','Q1')");
    $s->execute([$pid]); return $s->fetchAll(PDO::FETCH_ASSOC);
}
$action=$_GET['action']??$_POST['action']??'list';

try {
    if($action==='existing_personnel'){
        $selectedYear=(int)($_GET['year']??date('Y'));
        $selectedQuarter=$_GET['quarter']??('Q'.ceil(date('n')/3));
        if($selectedYear<2000||$selectedYear>2100) $selectedYear=(int)date('Y');
        if(!in_array($selectedQuarter,['Q1','Q2','Q3','Q4'],true)) $selectedQuarter='Q1';

        $sql="SELECT
                p.id,p.last_name,p.first_name,p.middle_name,p.position,p.contact_num,p.email,
                p.year_started,p.contract_start_date,p.contract_end_date,p.regno,p.status,p.municipality,
                h.year AS latest_year,h.quarter AS latest_quarter,
                h.contract_end_date AS latest_contract_end,h.status AS latest_quarter_status,
                CASE WHEN sq.id IS NULL THEN 0 ELSE 1 END AS already_assigned
              FROM personnel_info p
              LEFT JOIN personnel_quarterly_history h
                ON h.id=(
                    SELECT h2.id FROM personnel_quarterly_history h2
                    WHERE h2.personnel_id=p.id
                    ORDER BY h2.year DESC,FIELD(h2.quarter,'Q4','Q3','Q2','Q1'),h2.id DESC
                    LIMIT 1
                )
              LEFT JOIN personnel_quarterly_history sq
                ON sq.personnel_id=p.id AND sq.year=? AND sq.quarter=?
              ORDER BY p.last_name,p.first_name,p.middle_name";
        $st=$conn->prepare($sql);
        $st->execute([$selectedYear,$selectedQuarter]);
        $data=$st->fetchAll(PDO::FETCH_ASSOC);
        out(['success'=>true,'year'=>$selectedYear,'quarter'=>$selectedQuarter,'data'=>$data]);
    }
    if($action==='kit_details'){
        $regno=trim((string)($_GET['regno']??''));
        if($regno==='') out(['success'=>false,'message'=>'Reg. No. is required.'],422);
        $st=$conn->prepare("SELECT regno,reg_code,computer_name,regkit_serial_num,machine_id,serial_number,property_num,rcs_uname_code,reg_center_code FROM registrationdetails WHERE CAST(regno AS CHAR)=? LIMIT 1");
        $st->execute([$regno]); $kit=$st->fetch(PDO::FETCH_ASSOC);
        if(!$kit) out(['success'=>false,'message'=>'No registration kit record found for Reg. No. '.$regno.'.'],404);
        out(['success'=>true,'data'=>$kit]);
    }
    if($action==='list'){
        $year=(int)($_GET['year']??date('Y'));
        $quarter=$_GET['quarter']??('Q'.ceil(date('n')/3));
        if($year<2000||$year>2100) $year=(int)date('Y');
        if(!in_array($quarter,['Q1','Q2','Q3','Q4'],true)) $quarter='Q1';
        $status=trim((string)($_GET['status']??''));
        $nid=trim((string)($_GET['nid']??''));
        $name=trim((string)($_GET['name']??''));

        /*
         * IMPORTANT QUARTER DISPLAY RULE:
         * Show only personnel who have an actual quarterly assignment
         * for the selected YEAR + QUARTER. Do not carry a previous
         * quarter forward into a later quarter.
         *
         * This follows the old/reference workflow supplied by the user:
         * Q1 displays Q1 records, Q2 displays Q2 records, etc.
         */
        $sql="SELECT
                p.id,
                p.last_name,p.first_name,p.middle_name,
                p.contact_num,p.email,p.year_started,
                h.id AS quarter_id,
                h.year,h.quarter,h.position,h.municipality,h.regno,
                h.contract_start_date,h.contract_end_date,h.status AS quarter_status
              FROM personnel_quarterly_history h
              INNER JOIN personnel_info p ON p.id=h.personnel_id
              WHERE h.year=? AND h.quarter=?
              ORDER BY
                CASE WHEN h.status='active' THEN 0 ELSE 1 END,
                p.last_name,p.first_name,p.middle_name";
        $st=$conn->prepare($sql);
        $st->execute([$year,$quarter]);
        $assigned=$st->fetchAll(PDO::FETCH_ASSOC);

        $rows=[];
        $counts=['total'=>0,'active'=>0,'new'=>0,'renewed'=>0,'inactive'=>0,'expired'=>0];

        foreach($assigned as $r){
            $hist=getHistory($conn,(int)$r['id']);
            $derived=statusFor([
                'status'=>$r['quarter_status'],
                'contract_end_date'=>$r['contract_end_date']
            ],$hist);

            /*
             * "New" means this is the first quarterly assignment for
             * the personnel. Any later quarterly assignment is Renewed
             * unless the contract is expired/inactive.
             */
            if($derived==='Active' && count($hist)>1) $derived='Renewed';
            if($derived==='New' && count($hist)>1) $derived='Renewed';

            $hay=strtolower(trim($r['first_name'].' '.$r['middle_name'].' '.$r['last_name']));
            if($status!=='' && strtolower($status)!==strtolower($derived)) continue;
            if($nid!=='' && stripos((string)$r['regno'],$nid)===false) continue;
            if($name!=='' && stripos($hay,strtolower($name))===false) continue;

            $key=strtolower($derived);
            if(isset($counts[$key])) $counts[$key]++;

            $rows[]=[
                'id'=>(int)$r['id'],
                'quarter_id'=>(int)$r['quarter_id'],
                'last_name'=>$r['last_name'],
                'first_name'=>$r['first_name'],
                'middle_name'=>$r['middle_name'],
                'position'=>$r['position'],
                'municipality'=>$r['municipality'],
                'regno'=>$r['regno'],
                'contact_num'=>$r['contact_num'],
                'email'=>$r['email'],
                'year_started'=>$r['year_started'],
                'contract_start_date'=>$r['contract_start_date'],
                'contract_end_date'=>$r['contract_end_date'],
                'status'=>$derived,
                'quarter'=>$r['quarter'],
                'year'=>(int)$r['year']
            ];
        }

        $counts['total']=count($rows);
        out(['success'=>true,'year'=>$year,'quarter'=>$quarter,'counts'=>$counts,'data'=>$rows]);
    }
    if($action==='history'){
        $id=(int)($_GET['personnel_id']??0); if(!$id) out(['success'=>false,'message'=>'Invalid personnel.'],422);
        $s=$conn->prepare("SELECT * FROM personnel_info WHERE id=?");$s->execute([$id]);$p=$s->fetch(PDO::FETCH_ASSOC); if(!$p) out(['success'=>false,'message'=>'Personnel not found.'],404);
        $hist=getHistory($conn,$id); foreach($hist as &$h){$h['derived_status']=statusFor($h,$hist);} unset($h);
        out(['success'=>true,'personnel'=>$p,'history'=>$hist]);
    }
    if($action==='save'){
        $required=['last_name','first_name','position','year_started','contract_start_date','contract_end_date','regno','quarter','year'];
        foreach($required as $f) if(trim((string)($_POST[$f]??''))==='') out(['success'=>false,'message'=>"Missing required field: $f"],422);
        $q=$_POST['quarter'];$y=(int)$_POST['year'];$start=$_POST['contract_start_date'];$end=$_POST['contract_end_date'];$quarterStatus=strtolower(trim((string)($_POST['quarter_status']??'active')));
        if(!in_array($quarterStatus,['active','inactive'],true)) $quarterStatus='active';
        if(!in_array($q,['Q1','Q2','Q3','Q4'],true)||$y<2000||!validDate($start)||!validDate($end)||$end<$start) out(['success'=>false,'message'=>'Invalid quarter, year, or contract dates.'],422);
        $email=trim($_POST['email']??''); if($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL)) out(['success'=>false,'message'=>'Invalid email address.'],422);
        $conn->beginTransaction();
        $s=$conn->prepare("INSERT INTO personnel_info(last_name,first_name,middle_name,position,contact_num,email,year_started,contract_start_date,contract_end_date,regno,status,municipality) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
        $s->execute([trim($_POST['last_name']),trim($_POST['first_name']),trim($_POST['middle_name']??''),trim($_POST['position']),trim($_POST['contact_num']??''),$email,$y,$start,$end,(string)$_POST['regno'],'active',trim($_POST['municipality']??'')]);
        $pid=(int)$conn->lastInsertId();
        $s=$conn->prepare("INSERT INTO personnel_quarterly_history(personnel_id,quarter,year,position,municipality,regno,contract_start_date,contract_end_date,status) VALUES(?,?,?,?,?,?,?,?,?)");
        $s->execute([$pid,$q,$y,trim($_POST['position']),trim($_POST['municipality']??''),(string)$_POST['regno'],$start,$end,$quarterStatus]);
        $conn->commit(); out(['success'=>true,'message'=>'Personnel added with quarterly record.','id'=>$pid]);
    }
    if($action==='add_quarter_existing'){
        $pid=(int)($_POST['personnel_id']??0); $q=$_POST['quarter']??''; $y=(int)($_POST['year']??0);
        $start=$_POST['contract_start_date']??''; $end=$_POST['contract_end_date']??''; $quarterStatus=strtolower(trim((string)($_POST['quarter_status']??'active')));
        if(!in_array($quarterStatus,['active','inactive'],true)) $quarterStatus='active';
        if(!$pid || !in_array($q,['Q1','Q2','Q3','Q4'],true) || $y<2000 || $y>2100 || !validDate($start) || !validDate($end) || $end<$start) out(['success'=>false,'message'=>'Complete and valid quarterly information is required.'],422);
        $st=$conn->prepare("SELECT * FROM personnel_info WHERE id=?"); $st->execute([$pid]); $p=$st->fetch(PDO::FETCH_ASSOC);
        if(!$p) out(['success'=>false,'message'=>'Existing personnel record was not found.'],404);
        $st=$conn->prepare("SELECT id FROM personnel_quarterly_history WHERE personnel_id=? AND year=? AND quarter=? LIMIT 1"); $st->execute([$pid,$y,$q]);
        if($st->fetch()) out(['success'=>false,'message'=>"$q $y already exists for this personnel. Use Edit or select another quarter."],409);
        $position=trim($_POST['position']??$p['position']); $mun=trim($_POST['municipality']??$p['municipality']); $regno=(string)($_POST['regno']??$p['regno']);
        if($position==='') out(['success'=>false,'message'=>'Position is required.'],422);
        $conn->beginTransaction();
        $st=$conn->prepare("INSERT INTO personnel_quarterly_history(personnel_id,quarter,year,position,municipality,regno,contract_start_date,contract_end_date,status) VALUES(?,?,?,?,?,?,?,?,?)");
        $st->execute([$pid,$q,$y,$position,$mun,$regno,$start,$end,$quarterStatus]);
        $st=$conn->prepare("UPDATE personnel_info SET position=?,municipality=?,regno=?,contract_start_date=?,contract_end_date=?,status='active' WHERE id=?");
        $st->execute([$position,$mun,$regno,$start,$end,$pid]);
        $conn->commit();
        out(['success'=>true,'message'=>"Existing personnel added/renewed for $q $y. Previous quarterly records were preserved."]);
    }
    if($action==='renew'){
        $pid=(int)($_POST['personnel_id']??0);$q=$_POST['quarter']??'';$y=(int)($_POST['year']??0);
        $start=$_POST['contract_start_date']??'';$end=$_POST['contract_end_date']??'';$position=trim($_POST['position']??'');$mun=trim($_POST['municipality']??'');$regno=(string)($_POST['regno']??'');$quarterStatus=strtolower(trim((string)($_POST['quarter_status']??'active')));
        if(!in_array($quarterStatus,['active','inactive'],true)) $quarterStatus='active';
        if(!$pid||!in_array($q,['Q1','Q2','Q3','Q4'],true)||$y<2000||!validDate($start)||!validDate($end)||$end<$start||$position==='') out(['success'=>false,'message'=>'Complete and valid renewal information is required.'],422);
        $s=$conn->prepare("SELECT id FROM personnel_info WHERE id=?");$s->execute([$pid]);if(!$s->fetch()) out(['success'=>false,'message'=>'Personnel not found.'],404);
        $s=$conn->prepare("SELECT id FROM personnel_quarterly_history WHERE personnel_id=? AND year=? AND quarter=?");$s->execute([$pid,$y,$q]);if($s->fetch()) out(['success'=>false,'message'=>"A record already exists for $q $y. Edit the existing record instead."],409);
        $s=$conn->prepare("INSERT INTO personnel_quarterly_history(personnel_id,quarter,year,position,municipality,regno,contract_start_date,contract_end_date,status) VALUES(?,?,?,?,?,?,?,?,?)");
        $s->execute([$pid,$q,$y,$position,$mun,$regno,$start,$end,$quarterStatus]);
        $s=$conn->prepare("UPDATE personnel_info SET position=?,municipality=?,regno=?,contract_start_date=?,contract_end_date=?,status='active' WHERE id=?");
        $s->execute([$position,$mun,$regno,$start,$end,$pid]); out(['success'=>true,'message'=>"Personnel renewed for $q $y."]);
    }
    if($action==='update'){
        $pid=(int)($_POST['id']??0);if(!$pid) out(['success'=>false,'message'=>'Invalid personnel.'],422);
        $email=trim($_POST['email']??'');if($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL))out(['success'=>false,'message'=>'Invalid email address.'],422);
        $s=$conn->prepare("UPDATE personnel_info SET last_name=?,first_name=?,middle_name=?,position=?,contact_num=?,email=?,year_started=?,contract_start_date=?,contract_end_date=?,regno=?,municipality=? WHERE id=?");
        $s->execute([trim($_POST['last_name']),trim($_POST['first_name']),trim($_POST['middle_name']??''),trim($_POST['position']),trim($_POST['contact_num']??''),$email,(int)$_POST['year_started'],$_POST['contract_start_date'],$_POST['contract_end_date'],(string)$_POST['regno'],trim($_POST['municipality']??''),$pid]);
        $qid=(int)($_POST['quarter_id']??0);
        if($qid){$s=$conn->prepare("UPDATE personnel_quarterly_history SET position=?,municipality=?,regno=?,contract_start_date=?,contract_end_date=?,status=? WHERE id=? AND personnel_id=?");$s->execute([trim($_POST['position']),trim($_POST['municipality']??''),(string)$_POST['regno'],$_POST['contract_start_date'],$_POST['contract_end_date'],($_POST['quarter_status']??'active')==='inactive'?'inactive':'active',$qid,$pid]);}
        out(['success'=>true,'message'=>'Personnel information updated.']);
    }
    if($action==='archive'){
        $pid=(int)($_POST['id']??0);if(!$pid)out(['success'=>false,'message'=>'Invalid personnel.'],422);
        $s=$conn->prepare("UPDATE personnel_info SET status='inactive' WHERE id=?");$s->execute([$pid]);
        $s=$conn->prepare("SELECT id FROM personnel_quarterly_history WHERE personnel_id=? ORDER BY year DESC,FIELD(quarter,'Q4','Q3','Q2','Q1') LIMIT 1");$s->execute([$pid]);$latest=$s->fetchColumn();
        if($latest){$s=$conn->prepare("UPDATE personnel_quarterly_history SET status='inactive' WHERE id=?");$s->execute([$latest]);}
        out(['success'=>true,'message'=>'Personnel archived. Historical records were preserved.']);
    }
    if($action==='remark'){
        $pid=(int)($_POST['personnel_id']??0);$qid=(int)($_POST['quarterly_history_id']??0);$remark=trim($_POST['remark']??'');
        if(!$pid||!$qid||$remark==='')out(['success'=>false,'message'=>'Select a quarter and enter a remark.'],422);
        $conn->exec("CREATE TABLE IF NOT EXISTS personnel_remarks (id INT AUTO_INCREMENT PRIMARY KEY, personnel_id INT NOT NULL, quarterly_history_id INT NULL, remark TEXT NOT NULL, created_by VARCHAR(100) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $s=$conn->prepare("SELECT id,year,quarter FROM personnel_quarterly_history WHERE id=? AND personnel_id=? LIMIT 1");$s->execute([$qid,$pid]);$qrow=$s->fetch(PDO::FETCH_ASSOC);
        if(!$qrow)out(['success'=>false,'message'=>'The selected quarterly record does not belong to this personnel.'],422);
        $s=$conn->prepare("INSERT INTO personnel_remarks(personnel_id,quarterly_history_id,remark,created_by) VALUES(?,?,?,?)");$s->execute([$pid,$qid,$remark,$_SESSION['username']??null]);
        out(['success'=>true,'message'=>"Remark added for {$qrow['quarter']} {$qrow['year']}."]);
    }
    if($action==='delete_remark'){
        $remarkId=(int)($_POST['remark_id']??0); $pid=(int)($_POST['personnel_id']??0);
        if(!$remarkId||!$pid) out(['success'=>false,'message'=>'Invalid remark.'],422);
        $conn->exec("CREATE TABLE IF NOT EXISTS personnel_remarks (id INT AUTO_INCREMENT PRIMARY KEY, personnel_id INT NOT NULL, remark TEXT NOT NULL, created_by VARCHAR(100) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $s=$conn->prepare("DELETE FROM personnel_remarks WHERE id=? AND personnel_id=?"); $s->execute([$remarkId,$pid]);
        if($s->rowCount()===0) out(['success'=>false,'message'=>'Remark was not found or was already deleted.'],404);
        out(['success'=>true,'message'=>'Remark deleted.']);
    }
    out(['success'=>false,'message'=>'Unknown action.'],400);
} catch(Throwable $e){
    if($conn->inTransaction())$conn->rollBack();
    error_log($e->getMessage()); out(['success'=>false,'message'=>'A database operation failed. Please check the system logs.'],500);
}
?>