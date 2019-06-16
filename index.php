<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Reporting</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
    @media screen and (max-width: 1024px){
        article {
            width:100%;
            left: -10px;
            position: absolute;
        }
        .navbar, .toc, .toctitle {
            display: none;
            visibility: hidden;
            position: absolute;
            top: -999px;
            left: -9999px;
        }
        img.flag{
            vertical-align:text-top;
            margin-left:3px;
        }
    }
    @media screen and (min-width: 1024px) {
        .navbar {
            overflow: hidden;
            position: fixed;
            top: 100;
            width: 160px;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: left;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 14px;
        }
        img.flag{
            vertical-align:text-top;
            margin-left:3px;
        }
    }
    @media print {
        .firstHeading{display:none;}
        .sheet {
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }
        .code-block {
            background-color: #858789;
            padding: 5mm
        }
        /** Paper sizes **/
        body.A4 article { width: 210mm; height: 296mm }
        header, content, aside, footer, nav {display:none;}
        p {font-size:10pt;color: black;}
        h1{font-size:18pt;color: red; font-family:Serif;}
        h2{font-size:14pt;color: black;}
        h3{font-size:12pt;color: black;}
        h4{font-size:11pt;color: black;}
        a { color: #000!important; text-decoration: underline!important;}
        a[href]:after {content: " (" attr(href) ")"; /* affichage des URL des liens */}
        h1, h2, h3 {page-break-after: avoid; /* pas de saut après ces éléments */}
    }
      </style>
    <link rel="stylesheet" href="https://fr.wikipedia.org/w/load.php?debug=false&amp;lang=fr&amp;modules=ext.uls.interlanguage%7Cext.visualEditor.desktopArticleTarget.noscript%7Cext.wikimediaBadges%7Cmediawiki.legacy.commonPrint%2Cshared%7Cmediawiki.sectionAnchor%7Cmediawiki.skinning.interface%7Cskins.vector.styles&amp;only=styles&amp;skin=vector"/>
<script async="" src="https://fr.wikipedia.org/w/load.php?debug=false&amp;lang=fr&amp;modules=startup&amp;only=scripts&amp;skin=vector"></script>
        <!-- Chargement de la feuille de style -->
</head>
<body class="mediawiki ltr sitedir-ltr mw-hide-empty-elt ns-4 ns-subject page-Wikipédia_Accueil_principal rootpage-Wikipédia_Accueil_principal vector-nav-directionality skin-vector action-view">
<article class="mw-body" role="main">
<h1 id="firstHeading" class="firstHeading" lang="fr">Members of the DTU-C Scientific Committee</h1>
<?php

// chargement de l'adresse du google spread sheet
$spreadsheet_url="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_iFxMy5BqMHZ_nuyJimpVVlIsEkGkmKzVTThS82epj--S9agLqTJza0SyLUlxZD_MNcMwmP1u8WD5/pub?output=csv";

// message d'erreur en  as de time out
if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

// Boucle qui charge les données du google spreadsheet dans une variable de type array()
if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $spreadsheet_data[] = $data;
    }
    fclose($handle);
    asort($spreadsheet_data);
    $france=0;
    $tunisie=0;
    $international=0;
    $nationalites=array();
    echo "<ol>";
    foreach($spreadsheet_data as $requested_member){
        if($requested_member[9]=='yes'){
            array_push($nationalites,$requested_member[4]);
            echo "<li>";
            echo "<a href='";
            echo "https://scholar.google.fr/scholar?q=author:\"";
            echo urlencode($requested_member[0]);
            echo ", ";
            echo urlencode($requested_member[1]);
            echo "\"' >";
            echo "$requested_member[1] ";
            echo strtoupper($requested_member[0]);
            echo "</a>";
            //echo ", <a href='mailto:$requested_member[2]'>$requested_member[2]</a>";
            echo ", $requested_member[3]";
            echo ", $requested_member[4]";
            echo "<img class='flag' src='flags-mini/$requested_member[6].png' width='25px' height='15px' title='flag of $requested_member[4]' alt=', $requested_member[4]'>";
            if($requested_member[6]=='fr'){
                $france++;
            }
            elseif($requested_member[6]=='tn'){
                $tunisie++;
            }
            else {
                $international++;
            }
            echo "</li>";
        }
    }
    echo "</ol>";
    echo "There are $france members from France, $tunisie members from Tunisia and $international members from other countries in the Scientific Committee";
    $tableau_des_frequences=array_count_values($nationalites);
}
else
    die("Problem reading csv");
?>

    <div id="piechart" style="width: 1080px; height: 500px;">
        <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Members of de CS', 'Nb per country'],
                        <?php
                            $count=0;
                            foreach($tableau_des_frequences as $key => $value) {
                                echo "\n['" . $key . "',      " . $value . "]";
                                //echo "$count sur ".count($tableau_des_frequences);
                                if(++$count < count($tableau_des_frequences)) echo ",";
                            } 
                            ?>
                    ]);
                    var options = {
                        title: 'Distribution of reviewers / country',
                        showTooltip: true,
                        showInfoWindow: true
                    };
                    var pie = new google.visualization.PieChart(document.getElementById('piechart'));
                    pie.draw(data, options);
                  }
        </script>
    </div>
    <div id="regions_div" style="width: 800px; height: 600px">
    <script type="text/javascript">
      google.charts.load('current', {
        'packages':['geochart'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
      });
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Reviewers'],
          <?php
                $count=0;
                foreach($tableau_des_frequences as $key => $value) {
                    echo "['" . $key . "',      " . $value . "]";
                    //echo "$count sur ".count($tableau_des_frequences);
                    if(++$count < count($tableau_des_frequences)) echo ",";
                    echo "\n          ";
                } 
        ?>
        ]);
        var options = {};
        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
        chart.draw(data, options);
      }
    </script>

    </div>

</article>
</body>
</html>
