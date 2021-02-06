<?php    
    // gets entire POST body
    $data = file_get_contents('php://input');
    // write the data out to the file
    $user="user";
    $fp = fopen("temp/audio_".$user.".wav", "wb");

    fwrite($fp, $data);
    fclose($fp);
?>