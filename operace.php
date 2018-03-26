<?php
  SetLocale(LC_ALL, "cs_CZ.utf8");
  include("mysql.php");
  
$op = $_REQUEST["op"];
$tid = $_REQUEST["tid"];
$termin = $_REQUEST["termin"];
$uname = $_REQUEST["uname"];


$result = array();

switch ($op) {
    case "addUcast": {
        $res = $sql->sql_query("insert into ba_ucastnik (termin,ucastnik) values ({$tid},'{$uname}')");
        if ($res)
            $result["Result"] = "OK";
        else {
            $result["Result"] = "Error";  
            $result["Message"] = "Přidání účastníka se nezdařilo!";
        }
        break;
    }
    case "delUcast": {
        $res = $sql->sql_query("delete from ba_ucastnik where termin={$tid} and ucastnik='{$uname}'");
        if ($res)
            $result["Result"] = "OK";
        else {
            $result["Result"] = "Error";  
            $result["Message"] = "Smazání účastníka se nezdařilo!";
        }
        break;
    }
    case "addDate": {
        $result["Debug"] = "{$termin}";
        $sql->sql_transaction("begin");
        $res = $sql->sql_query("select id from ba_termin where termin='{$termin}'");
        if ($sql->sql_numrows($res) == 0) {
            $res = $sql->sql_query("insert into ba_termin(termin) values ('{$termin}')");
            if ($res) {
                $res = $sql->sql_query("select id from ba_termin where id=last_insert_id()");
                if ($row = $sql->sql_fetchrow($res)) {
                    $result["Result"] = "OK";
                    $result["Record"] = array("tid" => $row['id'], "termin" => $termin, "ucast" => null, "pocet" => 0);
                }
                $sql->sql_transaction("commit");
            } else {
                $result["Result"] = "Error";
                $result["Message"] = "Vytvoření termínu se nezdařilo!";
                $sql->sql_transaction("rollback");
            }
        } else {
            $result["Result"] = "Error";
            $result["Message"] = "Zadaný termín již existuje!";
            $sql->sql_transaction("rollback");
        }
        break;
    }
    case "delDate": {
        $res = $sql->sql_query("delete from ba_termin where id='{$tid}'");
        if ($res)
            $result["Result"] = "OK";
        else {
            $result["Result"] = "Error";
            $result["Message"] = "Termín se nepodařilo smazat!";
        }
        break;
    }
    default: {
        $result["Result"] = "OK";
        $result["Records"] = array();
        $sql_q = "select bat.id,bat.termin,group_concat(bau.ucastnik order by bau.ucastnik separator ', ') ucast, count(bau.ucastnik) pocet ".
            "from ba_termin bat left join ba_ucastnik bau on bau.termin=bat.id ".
            ($uname=="admin"?"":"where bat.termin >= current_date")." group by bat.id,bat.termin order by bat.termin";
        $res = $sql->sql_query($sql_q);
        while($row = $sql->sql_fetchrow($res)){
            //echo "{id:{$row['id']},termin:\"{$row['termin']}\",ftermin:\"{$fdate}\",ucast:\"{$row['ucast']}\"}";
            $result["Records"][] = array("tid" => $row['id'], "termin" => $row['termin'], "ucast" => $row['ucast'], "pocet" => $row['pocet']);
        }
    }        
}

echo json_encode($result);


?>