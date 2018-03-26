<?php
  SetLocale(LC_ALL, "cs_CZ.utf8");
  include("mysql.php");
  
$op = $_REQUEST["op"];
$uid = $_REQUEST["uid"];
$tid = $_REQUEST["tid"];
$ucastnik = $_REQUEST["ucastnik"];

$result = array();

switch ($op) {
    case "addUcast": {
        $sql->sql_transaction("begin");

        $res = $sql->sql_query("select id from ba_ucastnik where termin={$tid} and ucastnik='{$ucastnik}'");
        if ($sql->sql_numrows($res) == 0) {
            $res = $sql->sql_query("insert into ba_ucastnik(termin,ucastnik) values ({$tid},'{$ucastnik}')");
            if ($res) {
                $res = $sql->sql_query("select id from ba_ucastnik where id=last_insert_id()");
                if ($row = $sql->sql_fetchrow($res)) {
                    $result["Result"] = "OK";
                    $result["Record"] = array("uid" => $row['id'], "ucastnik" => $ucastnik);
                }
                $sql->sql_transaction("commit");
            } else {
                $result["Result"] = "Error";
                $result["Message"] = "Pøidání úèastníka se nezdaøilo!";
                $sql->sql_transaction("rollback");
            }
        } else {
            $result["Result"] = "Error";
            $result["Message"] = "Zadaný úèastník již existuje!";
            $sql->sql_transaction("rollback");
        }
        break;
    }
    case "delUcast": {
        $res = $sql->sql_query("delete from ba_ucastnik where id={$uid}");
        if ($res)
            $result["Result"] = "OK";
        else {
            $result["Result"] = "Error";  
            $result["Message"] = "Smazání úèastníka se nezdaøilo!";
        }
        break;
    }
    default: {
        $result["Result"] = "OK";
        $result["Records"] = array();
        $sql_q = "select bau.id,bau.ucastnik from ba_ucastnik bau where bau.termin={$tid} order by bau.ucastnik";
        $res = $sql->sql_query($sql_q);
        while($row = $sql->sql_fetchrow($res)){
            //echo "{id:{$row['id']},termin:\"{$row['termin']}\",ftermin:\"{$fdate}\",ucast:\"{$row['ucast']}\"}";
            $result["Records"][] = array("uid" => $row['id'], "ucastnik" => $row['ucastnik']);
        }
    }        
}

echo json_encode($result);


?>