<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coding Challenge</title>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="js.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="container">
        <div id="header">
            <form id="form" action="" method="post">
                <input name="show" id="show" placeholder="Search TV Shows" required>
                <button id='searchButton'><input id='search' type="submit" value="search" /></button>
            </form>
        </div>  
        <input type="hidden" id="hidden" value="<?= $hidden ?>">
        <div id="body">
            <div id="whole">
                <div id="left">

                </div>
                <div id="right">
                
                </div>
            </div>
            <div id="episodes">
                <div id="episode1">
                    
                </div>
                <div id="episode2">
                    
                </div>
                <div id="episode3">
                    
                </div>
            </div>
        </div>
        <div id="footer">

        </div>
    </div>
</body>
</html>