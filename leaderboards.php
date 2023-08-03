<?php

date_default_timezone_set('Europe/Prague');

$script_tz = date_default_timezone_get();


function get_string_between($string, $start, $end){
  $string = ' ' . $string;
  $ini = strpos($string, $start);
  if ($ini == 0) return '';
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}

echo("<!DOCTYPE html><html><head><link rel=\"icon\" href=\"/favicon.ico\">
<style>table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 40%;
  margin-left: auto;
  margin-right: auto;
}

td, th {
  border: 3px solid #3B3A38;
  text-align: left;
  padding: 8px;
  color: #D0D0D0;
}
tr:nth-child(odd) {
  background-color: #232426;
}
tr:nth-child(even) {
  background-color: #181818;
}
</style>
</head>
<body style=\"background-color:#212224\">

<table>
  <tr>
    <th>czsk rank</th>
    <th>eu rank</th>
    <th>name</th>
  </tr>
");
$api = file_get_contents("https://www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=europe&leaderboard=0");
$api = mb_convert_encoding($api, 'HTML-ENTITIES', "UTF-8");

$array = explode("},{",$api);

//last leaderboard update
$time_posted_position = strpos($array[0],"\"time_posted\":");
$time_posted = substr($array[0],$time_posted_position+14,10);
$time_posted_date = date('Y-m-d H:i:s', $time_posted);


$time_scheduled_position = strpos($array[0],"next_scheduled_post_time\":");
$time_scheduled = substr($array[0],$time_scheduled_position+26,10);
$time_scheduled_date = date('Y-m-d H:i:s', $time_scheduled);


echo nl2br("<a style=\"color:white\">"."last leaderboard update: ".$time_posted_date.' (UTC+2)'."</a>\n");
echo nl2br("<a style=\"color:white\">"."scheduled leaderboard update: ".$time_scheduled_date.' (UTC+2)'."</a>");
// echo date('Y-m-d H:i:s', 1680556393);
// echo date('Y-m-d H:i:s', 1680559981);
  


//czsk sort  
$i=0;
foreach($array as $profile)
{
    //sk search
    $check = strpos($profile, "country\":\"sk");
    if($check == true)
    {
        $allCZSKplayers[$i] = $profile;
        $i++;
    }
    //cz search
    $check = strpos($profile, "country\":\"cz");
    if($check == true)
    {
        $allCZSKplayers[$i] = $profile;
        $i++;
    }
    //raviente search
    $check = strpos($profile, "name\":\"Raviente-");
    if($check == true)
    {
        $allCZSKplayers[$i] = $profile;
        $i++;
    }
}
$allCZSKplayers = str_replace("\"","",$allCZSKplayers);


//writing out players
$countryrank = 0;
for($j = 0; $j < count($allCZSKplayers); $j++){

  $player = explode(",", $allCZSKplayers[$j]);

  //TEAM / COUNTRY TESTING
  $team = "";
  $team = get_string_between(implode("",$player),"team_tag:","country");
  if(strlen($team) != 0){
    $team = $team.".";
  }

  $cz_position_in_array = array_search("country:cz",$player);
  $sk_position_in_array = array_search("country:sk",$player);
  if($cz_position_in_array > 0){
    $country = "<img src=\"cz.gif\" align=\"right\">";
  }
  elseif($sk_position_in_array > 0){
    $country = "<img src=\"sk.gif\" align=\"right\">";
  }
  else{
    $country = "<img src=\"cz.gif\" align=\"right\">";
  }
    //echo implode("|||",$player);

  //stats
  for($k = 0; $k < count($player); $k++){
      $pos = strpos($player[$k],":");   
      $player[$k] = substr($player[$k], $pos + 1);
  }
  
  $countryrank = $j+1;
  
  //special players
  if($player[1] == "Hobbit")
  { 
    echo(
        
      "<tr>
      <td>".$countryrank."</td>
      <td>".$player[0]."</td>
      <td>"."<a style=\" color:#6188A4 \">".$team."</a>".$player[1]."  "."<img src=\"earthquake-20-20.png\">".$country."</td>
      </tr>"
  );
  }
  elseif($player[1] == "^Cechieaea")
  { 
    echo(
        
      "<tr>
      <td>".$countryrank."</td>
      <td>".$player[0]."  "."<img src=\"omegalul-small-15-15.png\">"."</td>
      <td>"."<a style=\" color:#6188A4 \">".$team."</a>".$player[1].$country."</td>
      </tr>"
  );
  }
  elseif($player[1] == "scream")
  { 
    echo(
        
      "<tr>
      <td>".$countryrank."</td>
      <td>"."1"."  "."<img src=\"heart-15-15.png\">"." (".$player[0].")"."</td>
      <td>"."<a style=\" color:#6188A4 \">".$team."</a>".$player[1].$country."</td>
      </tr>"
  );
  }
  elseif($player[1] == "KURRITO")
  { 
    echo(
        
      "<tr>
      <td>".$countryrank."</td>
      <td>".$player[0]."</td>
      <td>"."<a style=\" color:#6188A4 \">".$team."</a>".$player[1]."  "."<img src=\"nerd-15-18.png\">".$country."</td>
      </tr>"
  );
  }
  //everybody else
  else{
    echo(
        
        "<tr>
        <td>".$countryrank."</td>
        <td>".$player[0]."</td>
        <td>"."<a style=\" color:#6188A4 \">".$team."</a>".$player[1].$country."</td>
        </tr>"

    );     
  }  
}




echo("</table></body></html>");
?>
