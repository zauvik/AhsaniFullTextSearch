<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/app.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <title></title>
</head>
<body>
    <div class="wrapper-body">
        <div class="header">
            <div class="left-area">
                <h3>Ahsani <span>Afif</span></h3>
            </div>
            <div class="right-area">
                <a href="#" class="logout_btn">Logout</a>
            </div>
        </div>
        <div class="sidebar">
            @include('page.menu')
        </div>
        <div class="main">
            @yield('content')
        </div>
    </div>
</body>
</html>
