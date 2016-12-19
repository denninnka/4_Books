<?php
$pageTitle = 'Нов автор';
include dirname(__FILE__) . '/includes/header.php';
?>
<div><h3>Добави нов автор</h3></div>
<div><a href="index.php">Книги</a></div><div><br/></div>
<form method="POST" action="newauthor.php">
<div>Име: <input type="text" name="author_name" />
<input type="submit" name="addauthor" value="Добави" /></div><div><br/></div>
</form>
<?php
if (isset($_POST['addauthor'])) {
    $author_name = trim($_POST['author_name']);
    if (mb_strlen($author_name) < 2) {
        echo "<p>Името на автора трябва да не е по-късо от 2 символа</p>";

    } else {
        $author_name = mysqli_real_escape_string($con, $author_name);
        $q           = mysqli_query($con, 'SELECT * FROM authors WHERE author_name = "' . $author_name . '"');
        if (mysqli_error($con)) {
            echo "Грешка";
        }

        if (mysqli_num_rows($q) > 0) {
            echo "<p>Съществува автор с такова име</p>";
        } else {
            mysqli_query($con, 'INSERT INTO authors (author_name) VALUES ("' . $author_name . '")');
        }
        if (mysqli_error($con)) {
            echo "Грешка";
        } else {
            echo "Успешен запис";
        }
    }
}
$authors = getAuthors($con);
if ($authors === false) {
    echo "Грешка";
}
?>
<table border="1">
	<tr><td>Автори:</td></tr>
	<?php
foreach ($authors as $row) {
    echo '<tr><td><a href="#">' . $row['author_name'] . '</a></td></tr>';
}
?>

</table>

<?php
include dirname(__FILE__) . '/includes/footer.php';
?>