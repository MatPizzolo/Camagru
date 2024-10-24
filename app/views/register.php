<!-- /app/views/register.php -->
<!DOCTYPE html>
<html lang="en">
<?php include 'components/header.php'; ?>
<body>
    <h2>Registerr</h2>
    <form action="index.php?action=register" method="post">
        <label>Username:</label><br>
        <input type="text" name="username"><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Register">
    </form>
</body>
<?php include 'components/footer.php'; ?>
</html>
