<html>
	<head>
		<title>Revision Notes</title>
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
		<style>
			body
			{
				padding-left:5%;
				padding-right:5%;
			}
		</style>
		<?php
			$categories = array();
			$categories[] = "General";
			$categories[] = "Computing";
			$categories[] = "ICT";
			$categories[] = "History";
			$categories[] = "French";
			$categories[] = "Physics";
			$categories[] = "Biology";
			$categories[] = "Chemistry";
			
			function Query($sql, $type=MYSQLI_ASSOC){
				if ($sql) {
					$con = mysqli_connect("your_host", "your_user", "your_pass", "your_db");
					return $query = mysqli_query($con, $sql);
				}
			}
			
			function GetNotes($cat="all") {
				if ($cat == "all") {
					$query = "SELECT * FROM notes";
				} else {
					$query = "SELECT * FROM notes WHERE category='$cat'";
				}
				return Query($query, MYSQLI_ASSOC);
			}
			
			function AddNote($cat, $title, $text) {
				Query("INSERT INTO notes (category, title, text) VALUES ('$cat', '$title', '$text')");
			}
			
			function RemoveNote($title) {
				Query("DELETE FROM notes WHERE title='$title'");
			}
			
		if ($_POST) {
			if ($_POST['action'] == "newnote") {
				AddNote($_POST['category'], $_POST['title'], $_POST['text']);
				echo("<meta http-equiv='refresh' content='0; url=index.php' />");
			}
		}
		
		if ($_GET) {
			if ($_GET['action'] == "remove") {
				$noteToRemove = urldecode($_GET['name']);
				RemoveNote($noteToRemove);
				echo("<meta http-equiv='refresh' content='0; url=index.php' />");
			}
		}
			
		?>
	</head>
	<body>
		<?php
			if (isset($banner)) {
				echo($banner);
			}
		?>
		<div class="page-header">
			<h1>Revision Notes <small>This thing took far too much time.</small></h1>
		</div>
		<ul class="nav nav-tabs">
			<li><a href="#home" data-toggle="tab">New Note</a></li>
			<?php
				foreach ($categories as $cat) {
					echo("<li><a href='#" . str_replace(' ', '', $cat) . "' data-toggle='tab'>" . $cat . "</a></li>");
				}
			?>
		</ul>
		
		<br />
		
		<div class="tab-content">
			<div class="tab-pane fade in active" id="home">
				<h2>New note</h2><br />
				<form name="newnote" method="post">
					<input class="form-control" type="hidden" name="action" value="newnote">
					<input class="form-control" style="width: 160px;" type="text" name="title" placeholder="Title"><br />
					<select style="width: 130px;" name="category" class="form-control">
						<option value="General">General</option>
						<option value="Computing">Computing</option>
						<option value="ICT">ICT</option>
						<option value="History">History</option>
						<option value="French">French</option>
						<option value="Physics">Physics</option>
						<option value="Biology">Biology</option>
						<option value="Chemistry">Chemistry</option>
					</select><br />
					<textarea style="width: 700px;" class="form-control" name="text" cols="40" rows="5" placeholder="Lorem ipsum dolor sit amet..." ></textarea><br />
					<button type="submit" class="btn btn-primary">Create</button>
				</form>
			</div>
			<?php
				$bin = "<span class='glyphicon glyphicon-trash pull-right'></span>";
				
				foreach ($categories as $cat) {
					echo("<div class='tab-pane fade' id='" . str_replace(' ', '', $cat) . "'>");
					$qry = Query("SELECT * FROM notes WHERE category='$cat'");
					while ($note = mysqli_fetch_assoc($qry)) {
						echo("<div class='panel panel-default'>");
							echo("<div class='panel-heading'><h2>" . $note["title"] . "</h2></div>");
							echo("<div class='panel-body'>" . $note["text"]);
							echo("<div class='pull-right'><a href='index.php?action=remove&name=" . urlencode($note["title"]) . "'><span class='glyphicon glyphicon-trash'></span></a></div>");
							echo("</div>");
						echo("</div>");
					}
					echo("</div>");
				}
			?>
		</div>
	</body>
</html>
