<?php

// rappel : gare direction univ = SEM:2216
$stop = htmlspecialchars($_GET["stop"]);


function curl($href=""){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    $result = curl_exec($curl);
    return $result;
}
function tram($tram=""){
        $color = array(
            "A" => "#3376B8",
            "B" => "#479A45",
            "C" => "#C20078",
            "D" => "#DE9917",
            "E" => "#533786"
        );
        $exploded = explode(':', $tram);
        $letter = $exploded[1];
        return '<svg id="svg_'.$letter.'" data-code="SEM_'.$letter.'" class="logo" style="font-size:56px;font-family: Arial;font-weight:bold;letter-spacing: 2px;text-anchor: middle;stroke-width:6px;" viewBox="0 0 100 100">		<title>Ligne '.$letter.' - M TAG</title>		<circle class="svgFond" cx="50" cy="50" r="45" stroke="white" stroke-width="0" fill='."$color[$letter]".'></circle>				<text class="svgNumLigne" x="50%" y="50%" dy="0.33em" fill="#FFFFFF">'.$letter.'</text>	</svg>';
}

// envoie de la requete

$url = "https://data.mobilites-m.fr/api/routers/default/index/stops/$stop/stoptimes/";
$encoded_json = curl($url);
$lignes = json_decode($encoded_json, true);

date_default_timezone_set("Europe/Paris");

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5">
    <title><?php echo $lignes[0]["times"][0]["stopName"]; ?></title>
</head>
<body>
    <h1>MODIF</h1>
    <table>
        <tr>
            <th>ligne</th>
            <th>direction</th>
            <th colspan="2">passage(s)</th>
        </tr>
    <?php
        foreach ($lignes as $ligne){
            $pattern = $ligne["pattern"];
            ?>
        <tr>
            <td>
                <?php
                echo tram($pattern["id"]);
                ?>
            </td>
            <td>
                <?php
                echo $ligne["times"][0]["headsign"];
                ?>
            </td>
            <?php foreach($ligne["times"] as $time) {?>
            <td>
                <?php
                $seconds = ($time["realtimeArrival"] - (time() % 86400 + date("Z")));
                if ($seconds > 60){
                    echo (int) ($seconds / 60);
                    echo " min";
                }
                elseif($seconds < 0){
                    echo "à quai";
                }
                else{
                    echo $seconds;
                    echo " sec";
                }
                ?>
            </td>
            <?php } ?>
        </tr>
    <?php } ?>
    </table>
</body>
</html>
