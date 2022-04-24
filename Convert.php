<?php
    // security advice from https://www.media-division.com/the-right-way-to-handle-file-downloads-in-php/ can be apply to this project
    // CSV function's https://www.php.net/manual/en/function.fgetcsv.php i can look into this later

    //get directory name
    $dirname = getcwd()."\upload";

    // check if directory exist
    function Check_Create_Upload($dirname){
        if(!file_exists(getcwd()."\upload")){
            mkdir(getcwd()."\upload");
        }  
    }
    Check_Create_Upload($dirname);


    $filename = $_FILES["file"];
    $FilExtension = pathinfo($filename['name']);
    
    // make security update check https://www.php.net/manual/en/function.move-uploaded-file.php for more info 
    // som security measures
    function check_file_properties($filename,$FilExtension){      
        if($FilExtension['extension'] != 'txt'){
            echo "sorry extension is not same as need";

            //can create additional site for this error
            //header("Location: E_send_data.php");
        }

        if(mb_strlen($filename['name'],"UTF-8")>=225){
            echo "sorry file name is to long";

            //can create additional site for this error
            header("Location: E_send_data.php");
        }

        // check how large file is
        if(filesize($filename['tmp_name'])==0){
            echo "sorry you file is empty cannot convert \n";

            //can create additional site for this error
            header("Location: E_send_data.php");
        }
    }
    check_file_properties($filename,$FilExtension);

    // download file into server 
    move_uploaded_file($filename["tmp_name"],"upload/" . $filename["name"]);


    // can additional site for good  upload file 
    //header("Location: E_send_data.php");

    $myfile = fopen("upload/".$filename['name'],"r");
    $data = fread($myfile,filesize("upload/".$filename['name']));
    fclose($myfile);


    $data = explode(" ",$data);
    $data = implode(", ", $data);


    
    $FileNameWithoutFormat = pathinfo("upload/".$filename['name'], PATHINFO_FILENAME);



    $Download_File = fopen("upload/".$FileNameWithoutFormat."DOW".".csv","w");
    fwrite($Download_File,$data);


    //upload file to client browser
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"$FileNameWithoutFormat.csv\"");
    readfile("upload/".$FileNameWithoutFormat."DOW".".csv");
 
    //make function that delete file from server
    fclose($Download_File);
    

?>
