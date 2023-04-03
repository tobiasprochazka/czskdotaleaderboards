<?php
echo("<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

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
//$output = array_search("cz",$array);
//echo(implode( "\r\n" , $array));
$i=0;
foreach($array as $profile)
{
    $check = strpos($profile, "country\":\"sk");
    if($check == true)
    {
        $output[$i] = $profile;
        $i++;
    }
    $check = strpos($profile, "country\":\"cz");
    if($check == true)
    {
        $output[$i] = $profile;
        $i++;
    }
}
$output = str_replace("\"","",$output);
//players
$countryrank = 0;
for($j = 0; $j < count($output); $j++){

    $list = explode(",", $output[$j]);
    //stats
    for($k = 0; $k < count($list); $k++){
        $pos = strpos($list[$k],":");   
        $list[$k] = substr($list[$k], $pos + 1);

    }
    $countryrank = $j+1;
    echo(
        
        "<tr>
        <td>".$countryrank."</td>
        <td>".$list[0]."</td>
        <td>".$list[1]."</td>
        </tr>"

    );       
}


// <tr>
// <td>Alfreds Futterkiste</td>
// <td>Maria Anders</td>
//</tr>


echo("
</table>

</body>
</html>

");

// $twodim[$j][$k] = $result;
//$list[$k."1"],$list[$k."3"],$list[$k."4"],$list[$k."5"]);
    

//echo(implode("\n",$twodim));
//echo(implode("\n",$list));
// echo(count($output));
// echo($result);
//echo($twodim[0]['country']);
// for($l = 0; $l < count($twodim); $l++)
// {
//     foreach ($twodim as $val){
//         echo "<tr><td>".$val[$l][0]."</td><td>".$val[$l][1]."</td></tr>";
//     }
// }

// echo($twodim[$l]['name']);


// function html_table($data = array())
// {
//     $rows = array();
//     foreach ($data as $row) {
//         $cells = array();
//         foreach ($row as $cell) {
//             $cells[] = "<td>{$cell}</td>";
//         }
//         $rows[] = "<tr>" . implode('', $cells) . "</tr>";
//     }
//     return "<table class='hci-table'>" . implode('', $rows) . "</table>";
// }

// echo(html_table($output));

?>
