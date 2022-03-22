<?php require('inc_connexion.php')?>
<?php
if(isset($_COOKIE['user_search'])) 
{
	$result = $mysqli->query('SELECT villes.ville_id, ville_nom FROM villes INNER JOIN user_search WHERE villes.ville_id = user_search.ville_id WHERE  user_id = "'.$user_id.'"');
	$row = $result->fetch_array();
	echo $row['ville_id'];
	echo $row['ville_nom'];
	foreach ($row as $user_search) 
	{
		echo '<a href = ville.php?='.$user_search['ville_id'].'>'.$ville_nom.'</a>';
	}
}
else
{
	$user_id = uniqid();
}

if ((isset($_POST['submit_form']))) 
{
	$ville_nom= $_POST['ville_nom'];
	if (empty($ville_nom)) 
	{
		$message = '<p>Veuillez saisir le nom d\'une ville</p>';
	}
	else 
	{
		$result = $mysqli->query('SELECT count(ville_id) FROM villes WHERE  ville_nom = "'.$ville_nom.'"');
			$row = $result->fetch_array();
			if($row [0]>0)
			{
			 	$result = $mysqli->query('SELECT ville_nom, ville_id FROM villes WHERE  ville_nom = "'.$ville_nom.'"'); 
				$row = $result->fetch_array();
				$ville_id = $row['ville_id']; 
				$user_search['ville_id'] = $ville_id; 
				$user_search['user_id'] = $user_id;
				$search_data = serialize($user_search);
				$mysqli->query ('INSERT INTO user_search (user_id, ville_id) VALUES ("'.$user_id.'", "'.$ville_id.'")');
				setcookie('user_search', $search_data, time()+259200); 
				$row = $result->fetch_array();
			}
			else 
			{
				$message = '<p>La ville '.$ville_nom.' n\'est pas enregistrée dans la base de données</p>';
			}
	}
	
}	
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Document sans titre</title>
</head>
<?php if (isset ($message)) echo $message ?>
	<form method="post">
		<input type="text" name="ville_nom">
		<input type="submit" value="envoyer" name="submit_form">
	</form>
<body>
</body>
</html>