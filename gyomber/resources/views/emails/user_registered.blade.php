<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Új felhasználó regisztrált</title>
</head>
<body>
    <h1>Új felhasználó regisztrált</h1>
    <p>A következő felhasználó regisztrált a rendszerbe:</p>
    <ul>
        <li>Név: {{ $user->name }}</li>
        <li>Email: {{ $user->email }}</li>
        <li>Jogosultság: {{ $user->jogosultsag_azon }}</li>
    </ul>
</body>
</html>
