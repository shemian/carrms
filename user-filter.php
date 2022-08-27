<?php

function getLoggedInUserId(string $email, $dbh){
  
    $sql ="SELECT id FROM tblusers WHERE EmailId=:email ";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> execute();
    $result=$query->fetch(PDO::FETCH_OBJ);
    


    return $result->id;    
}


?>