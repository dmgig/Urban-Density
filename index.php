<?php

/* CONTROLLER ********************************************/

include('model.php');

$model = new Model();

$all_countries = $model->getAllCountriesInfo();
$most_urban = $model->getUrbanized(10,'most');
$least_urban = $model->getUrbanized(10,'least');

/* VIEW *********************************************/

?><!DOCTYPE html>
<html>
<head>
<title>Urban Density</title>

<link rel="stylesheet" type="text/css" href="ui/css/jquery.dataTables.css">

<script type="text/javascript" src="ui/js/jquery.js"></script>
<script type="text/javascript" src="ui/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="ui/js/dataTables.numericComma.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".active.dataTable").dataTable({
			"aaSorting": [[ 5, "desc" ]],
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"sPaginationType": "full_numbers",
			"aoColumns": [
							null,
							null,
							{ "sType": "formatted-num" },
							{ "sType": "formatted-num" },
							{ "sType": "formatted-num" },
							null
						],
			"iDisplayLength": 50
		});	
		
		$(".qdisp").toggle(function(){
			$(this).siblings(".query").show('slow');							
		},function(){
			$(this).siblings(".query").hide('slow');								
		});
	});
</script>

</head>

<body>

	<div id="header">
    	Urban Density
    </div>
    
    <table class="dataTable" cellpadding="0" cellspacing="0" border="0" width="100%">
    <thead class="alt">
        <tr class="qdisp">
            <th colspan="6"><strong>MOST URBANIZED COUNTRIES</strong> (click to display query)</th>
        </tr>
        <tr class="query" style="display:none">
            <th colspan="6">
            	<pre>
					<?=$most_urban['q']?>
            	</pre>
            </th>
        </tr>          
    </thead>
    <thead>    
        <tr>
            <th></th>
            <th>Country (Continent)</th>
            <th>Total Population</th>
            <th>GNP (per capita, in USD)</th>
            <th>Urbanized %</th>
        </tr>        
    </thead>
    <tbody>    
    <?php
	$bg = 'odd';
	$i = 1;
    foreach($most_urban['rows'] as $mu): 
		$bg = ($bg == 'odd' ? 'even' : 'odd');
		?>
        <tr class="<?=$bg?>">
            <td>#<?=$i?></td>
            <td><strong><?=$mu['CountryName']?></strong> (<?=$mu['Continent']?>)</td>
            <td class="aright"><?=number_format($mu['CountryPopulation'])?></td>
            <td class="aright"><?=($mu['GNPPC'] != 0 ? '$' . number_format($mu['GNPPC']) : 'n/a' )?></td>
            <td class="aright"><?=number_format(($mu['Urbanized'] * 100),2)?>%</td>
        </tr>
    <?php
		$i++;
    endforeach; ?>  
    </tbody> 
     <thead class="alt">
        <tr class="qdisp">
            <th colspan="6"><strong>LEAST URBANIZED COUNTRIES</strong> (click to display query)</th>
        </tr>
         <tr class="query" style="display:none">
            <th colspan="6">
            	<pre>
            		<?=$least_urban['q']?>
            	</pre>
            </th>
        </tr>         
    </thead>
    <thead>         
        <tr>
            <th></th>
            <th>Country (Continent)</th>
            <th>Total Population</th>
            <th>GNP (per capita, in USD)</th>
            <th>Urbanized %</th>
        </tr>            
    </thead>   
    <tbody> 
	<?php
    $bg = 'odd';
	$i = 1;
	foreach($least_urban['rows'] as $lu): 
		$bg = ($bg == 'odd' ? 'even' : 'odd');
		?>
        <tr class="<?=$bg?>">
            <td>#<?=$i?></td>
            <td><strong><?=$lu['CountryName']?></strong> (<?=$lu['Continent']?>)</td>
            <td class="aright"><?=number_format($lu['CountryPopulation'])?></td>
            <td class="aright"><?=($lu['GNPPC'] != 0 ? '$' . number_format($lu['GNPPC']) : 'n/a' )?></td>
            <td class="aright"><?=number_format(($lu['Urbanized'] * 100),2)?>%</td>
        </tr>
    <?php
		$i++;
    endforeach; ?>
    </tbody>    
    </table>    

    <table class="dataTable" cellpadding="0" cellspacing="0" border="0" width="100%">
     <thead class="alt">
        <tr class="qdisp">
            <th colspan="6"><strong>COMPLETE LIST</strong> (click to display query)</th>
        </tr>
        <tr class="query" style="display:none">
            <th colspan="6">
            	<pre>
					<?=$all_countries['q']?>
            	</pre>
            </th>
        </tr>        
    </thead>    
    </table>
    
    <table class="active dataTable" cellpadding="0" cellspacing="0" border="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Continent</th>
            <th>Population</th>
            <th>Urban Population</th>
            <th>GNP (per capita)</th>
            <th>Urbanized %</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($all_countries['rows'] as $country): ?>
        <tr>
            <td><?=$country['CountryName']?></td>
            <td><?=$country['Continent']?></td>
            <td class="aright"><?=number_format($country['CountryPopulation'])?></td>
            <td class="aright"><?=number_format($country['UrbanPopulation'])?></td>
            <td class="aright"><?=($country['GNPPC'] != 0 ? number_format($country['GNPPC']) : '' )?></td>
            <td class="aright"><?=number_format(($country['Urbanized'] * 100),2)?></td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
    </table>
    <br /><br />
    
</body>
</html>
