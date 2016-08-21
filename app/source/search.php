<?php
if(isset($_GET['search'])==TRUE){
	if(strlen(trim($_GET['search']))==0){
		?>
		<tr>
			<td>
					<div class="alert alert-danger" role="alert">Ops! No hay datos :(</div>
			</td>
		</tr>
		<?php
		die("");
		return;
	}
}
if(isset($_GET['type'])==FALSE){
	$_GET['type']='film';
}
if(strlen(trim($_GET['type']))==0){
	$_GET['type']='film';
}
$type='';
if(strcmp($_GET['type'],"film")==0){
	$type='film';
}else{
	$type='actor';
}
if(isset($_GET['search'])){
	$search= $_GET['search'];
	$ch = curl_init();
		$url = "http://api.themoviedb.org/3/search/multi?query=".urlencode($search).
		"&language=es&search_type=".$type."&api_key=e8d51c10f6e4a9fd8da7b9cfa010bb26";
	curl_setopt($ch, CURLOPT_URL, $url );
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
	$path = "http://image.tmdb.org/t/p/w1000";
	foreach($data['results'] as $i=>$item){
		$flag = true;
		$info = array();
		$media_type='';
		if(isset($item['media_type'])==TRUE){
			$media_type=$item['media_type'];
		}

		if(strcmp($_GET['type'],"film")==0){
			if(strcmp($media_type,'movie')==0){
				if(isset($item['title'])==TRUE && isset($item['overview'])==TRUE){
					$info["titulo"] = "<p><strong>".ucwords($item['title'])."</strong></p>".$item['overview'];
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
				if(isset($item['poster_path'])==TRUE){
					$info['imagen']=$path.$item['poster_path'];
				}else{
					$info['imagen']='';
				}
				if(isset($item['media_type'])==TRUE){
					$info['tipo']= "<span class='badge'>".$item['media_type']."<span>";
				}else{
					$info['tipo']='';
				}
				if(isset($info['titulo'])==FALSE){
					$flag=false;
				}
				if($flag==true){
					$table[]=$info;
				}
			}
		}else{
			if(strcmp($media_type,'person')==0){
				if(isset($item['name'])==TRUE){
					$info["actor"] = ucwords($item['name']);
				}else{
					$info["actor"] = '';
					$flag = false;
				}
				if(isset($item['popularity'])==TRUE){
					$info['popularidad']=$item['popularity'];
				}else{
					$info['popularidad']='';
				}
				if(isset($item['known_for'])==TRUE){
					$films = '';
					foreach ($item['known_for'] as $key => $value) {
						if(isset($value['title'])==TRUE){
								$films.=$value['title'].", ";
						}
					}
					$info["peliculas"]= $films;
				}else{
					$info['peliculas']='';
				}
				if(isset($item['profile_path'])==TRUE){
					$info['imagen']=$path.$item['profile_path'];
				}else{
					$info['imagen']='';
				}
				if(isset($item['media_type'])==TRUE){
					$info['tipo']= "<span class='badge'>".$item['media_type']."<span>";
				}else{
					$info['tipo']='';
				}
			}
			if(isset($info['actor'])==FALSE){
				$flag=false;
			}
			if($flag==true){
				$table[]=$info;
			}
		}

	}
	//echo json_encode($table)."<br>";

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
			if(strcmp($k,"imagen")==0){
					echo '<img src="'.$l.'" class="img-responsive">';
			}else{
				echo ucwords($l);
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</tbody>";/**/

}else{
	?>
	<tr>
		<td>
		<div class="alert alert-danger" role="alert">Ops! No hay datos :(</div>
		</td>
	</tr>
	<?php
	die("");
}

?>
