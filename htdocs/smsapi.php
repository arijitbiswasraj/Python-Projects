<?php
function sendsms($mobileno, $message){

    $message = urlencode($message);
    $sender = 'HLDGYM'; 
    $apikey = '10713t074ua2f1t0t7m4q38s98d55876ae9';
    $baseurl = 'https://instantalerts.co/api/web/send?apikey='.$apikey;

    $url = $baseurl.'&sender='.$sender.'&to='.$mobileno.'&message='.$message;    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if(!$response){
        $response = file_get_contents($url);
    }
    
}


//u510971715_gymmanagement
//u510971715_root
//Arijit@123


//call function
//sendsms('918945900669', 'Dear Arijit, your gym membership expired on 5-10-2021. For renewal please visit HALDER FITNESS CENTER, Birnagar - HALDER ENTERPRISES');
function tobeexpired($phno, $name, $days){//done
    $phno = '91'.$phno;
    sendsms($phno, 'Dear '.$name.', your gym membership is going to expire in '.$days.' day(s). For renewal please visit HALDER FITNESS CENTER, Birnagar - HALDER ENTERPRISES');
}

function expired($phno, $name, $date){//done
    $phno = '91'.$phno;
    sendsms($phno, 'Dear '.$name.', your gym membership expired on '.$date.'. For renewal please visit HALDER FITNESS CENTER, Birnagar - HALDER ENTERPRISES');
}

function tobeexpiredtoday($phno, $name){//done
    $phno = '91'.$phno;
    sendsms($phno, 'Dear '.$name.', your gym membership ends today. For renewal please visit HALDER FITNESS CENTER, Birnagar. - HALDER ENTERPRISES');
}

function closed($phno, $name, $date, $reason){
    $phno = '91'.$phno;
    sendsms($phno, 'Hi '.$name.', this is to notify you that HALDER FITNESS CENTER will be closed on '.$date.' due to '.$reason.' - HALDER ENTERPRISES');
}

function renewed($phno, $name, $date, $days){//done
    $phno = '91'.$phno;
    sendsms($phno,'Hi '.$name.', your gym membership has been renewed on '.$date.' for '.$days.' day(s). Stay safe and happy working out - HALDER ENTERPRISES');
}
function wish($phno, $name, $festival){
    $phno = '91'.$phno;
    sendsms($phno,'Hi '.$name.', we at HALDER FITNESS CENTER wish you a very HAPPY '.$festival.'. May your life fill with happiness and health - HALDER ENTERPRISES');
}

function welcome($phno, $name, $durationdays){//done
    $phno = '91'.$phno;
    sendsms($phno, 'Hi '.$name.', WELCOME to HALDER FITNESS CENTER. One of the most premium multi-equipped gym in WEST BENGAL. Your subscription duration is '.$durationdays.' Day(s). Stay safe and happy working out. - HALDER ENTERPRISES');
}

function bdaywish($phno, $name){
    $phno = '91'.$phno;
    sendsms($phno,'Dear '.$name.', we at HALDER FITNESS CENTER wish you a very happy birthday and a lifetime of good health and an amazing physique. May all your dreams and wishes come true. - HALDER ENTERPRISES');
}

function annwish($phno1, $phno2, $name1, $name2){
    $phno1 = '91'.$phno1;
    sendsms($phno1, 'Dear '.$name1.' and '.$name2.', we at HALDER FITNESS CENTER wish you a very happy and amazing anniversary. Wishing you a long and wonderful healthy life together on this grand occasion - HALDER ENTERPRISES');
    $phno2 = '91'.$phno2;
    sendsms($phno2, 'Dear '.$name1.' and '.$name2.', we at HALDER FITNESS CENTER wish you a very happy and amazing anniversary. Wishing you a long and wonderful healthy life together on this grand occasion - HALDER ENTERPRISES');    
}
?>
