<?php

require('/home/useful/dmgig.com/worldpass.php'); // secure pass storage

/**
 * Data Model
 * 
 * This model grabs the data from the MySQL database useful_world
 *
 * Two tables, `Country` & `City` containing the following columns:
 *
 * Country: Code, Name, Continent, Region, SurfaceArea, IndepYear, 
 *          Population, LifeExpectancy, GNP, GNPOld, LocalName, 
 *          GovernmentForm, HeadOfState, Capital, Code2 
 *
 * City: ID, Name, CountryCode, District, Population 
 *
 * @package None
 * @author David Giglio
 *
 */
 
 class Model{
	
	var $dbc; // db connection

	/**
	 * construct
	 * 
	 * Connect to db
	 *
	 * @return void
	 */
	function __construct(){
		$this->dbc = new mysqli('localhost', W_USER, W_PASS, 'useful_world');
		if($this->dbc->connect_errno > 0){
			die('Unable to connect to database [' . $this->dbc->connect_error . ']');
		}
	}

	/**
	 * getAllCountriesInfo
	 * 
	 * Get full list of countries w/ columns
	 * Continet, CountryName, CountryPopulation, UrbanPopulation, Urbanized, GNPPC
	 *
	 * returns all rows joined to City table as php Array 
	 *
	 * @return array('q' => query, 'rows' => data)
	 */
	function getAllCountriesInfo(){
		$q = "SELECT DISTINCT *,
				Country.Continent AS Continent,
				Country.Name AS CountryName,
				Country.Population AS CountryPopulation,
				SUM(City.Population) AS UrbanPopulation,
				SUM(City.Population) / Country.Population AS Urbanized,
				(GNP * 1000000) / Country.Population AS GNPPC
				FROM Country
				JOIN City
					ON Code = CountryCode
			 	GROUP BY Country.Code
				ORDER BY Urbanized DESC";
		$r = mysqli_query($this->dbc,$q);
		
		if($r){
			$rows = array();
			while($row = mysqli_fetch_assoc($r)){
				$rows[] = $row;	
			}
			return array('q' => $q, 'rows' => $rows);
		}else{
			die('There was an error running the query [' . $this->dbc->error . ']');
		}
	}
	
	/**
	 * getAllCountriesInfo
	 * 
	 * Get countries w/ columns
	 * Continet, CountryName, CountryPopulation, UrbanPopulation, Urbanized, GNPPC
	 * limited by number, sorted to deliver most or least as requested
	 *
	 * @param int
	 * @param enum string ('most', 'least')
	 *
	 * @return array('q' => query, 'rows' => data)
	 */	
	function getUrbanized($number,$type){
		
		$type = ($type == 'most' ? 'DESC' : 'ASC');
		
		$q = "SELECT DISTINCT *,
				Country.Continent AS Continent,
				Country.Name AS CountryName,
				Country.Population AS CountryPopulation,
				(GNP * 1000000) / Country.Population AS GNPPC,
				SUM(City.Population) / Country.Population AS Urbanized
				FROM Country
				JOIN City
					ON Code = CountryCode
			 	GROUP BY Country.Code
				ORDER BY Urbanized $type
				LIMIT $number";
		$r = mysqli_query($this->dbc,$q);
		
		if($r){
			$rows = array();
			while($row = mysqli_fetch_assoc($r)){
				$rows[] = $row;	
			}
			return array('q' => $q, 'rows' => $rows);
		}else{
			die('There was an error running the query [' . $this->dbc->error . ']');
		}
	}

	/**
	 * destruct
	 *
	 * Not used
	 * 
	 * @return void
	 */
	function __destruct(){}
	 
	 
 }
 
 
 ?>