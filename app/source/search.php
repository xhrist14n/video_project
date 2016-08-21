<?php
if(isset($_GET['search'])==TRUE){
	if(strlen(trim($_GET['search']))==0){
		?>
		<tr>
			<td>
				No hay datos
			</td>
		</tr>
		<?php
		die("");
		return;
	}
}

if(isset($_GET['search'])){
	$search= $_GET['search'];
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://api.themoviedb.org/3/search/multi?query=".
	$search.
	"&language=es&search_type=ngram&api_key=e8d51c10f6e4a9fd8da7b9cfa010bb26");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Accept: application/json"
	));

	$response = curl_exec($ch);
	$data = json_decode($response,true);
	curl_close($ch);
	//echo json_encode($data['results']);
	$table = array();
	foreach($data['results'] as $i=>$item){
		$flag = true;
		$info = array();
		if(isset($item['original_title'])==TRUE){
			$info["titulo"] = ucwords($item['original_title']);
		}else{
			$info["titulo"] = '';
			$flag = false;
		}
		if(isset($item['release_date'])==TRUE){
			$info["fecha"] = $item['release_date'];
		}else{
			$info["fecha"] = '';
		}
		if(isset($item['popularity'])==TRUE){
			$info['popularidad']=$item['popularity'];
		}else{
			$info['popularidad']='';
		}
		if(isset($item['original_language'])==TRUE){
			$info['idioma']=$item['original_language'];
		}else{
			$info['idioma']='';
		}
		if($flag==true){
			$table[]=$info;
		}
	}
	echo "<thead><tr>";
	foreach($table[0] as $i=>$j){
		echo "<th>";
		echo ucwords($i);
		echo "</th>";
	}
	echo "</tr></thead>";
	echo "<tfoot><tr>";
	foreach($table[0] as $i=>$j){
		echo "<th>";
		echo ucwords($i);
		echo "</th>";
	}
	echo "</tr></tfoot>";
	echo "<tbody>";
	foreach($table as $i=>$j){
		echo "<tr>";
		foreach($j as $k=>$l){
			echo "<td>";
			echo ucwords($l);
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</tbody>";

}else{
	?>
	<tr>
		<td>
			No hay datos
		</td>
	</tr>
	<?php
	die("");
}

?>
