<?php

    function countItem( $id , $table){
        global $con;
        $stmt= $con->prepare("SELECT COUNT($id) FROM $table WHERE groupid=0");
        $stmt->execute();
        $count= $stmt->fetchColumn();
        return $count;
    }
?>







