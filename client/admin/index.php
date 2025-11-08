<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLU Bazaar | Home</title>
    <link rel="stylesheet" href="../admin/assets/style.css">
    <link rel="stylesheet" href="/client/global.css">
    <script src="/client/admin/assets/main.js" defer></script>
</head>
<body>
    <div class="bg-overlay"></div>

    <div class="title-tagline-box">
        <div class="title-tagline">
        <img src="/client/assets/img/SLU Logo.png" alt="slu_logo">
        <div>
            <h1>SLU Bazaar</h1>
            <p>Admin Hub</p>
        </div>
    </div>

    <div class="dashboard">
        <ul id="tabs">
            <li data-tab-target="#overview" class="active-tab">Overview</li>
            <li data-tab-target="#users">Users</li>
            <li data-tab-target="#listings">Listings</li>
        </ul>

        <div class="tab-contents">
            <div id="overview" data-tab-content class="active">
                <h1 id="today" >Today</h1>
                <ul class="values">
                    <li>
                        <div>
                            <h1>1</h1>
                            <p>Active Listing</p>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h1>1</h1>
                            <p>Closed Listing</p>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h1>1</h1>
                            <p>New Users</p>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h1>1</h1>
                            <p>Sold</p>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h1>1</h1>
                            <p>Reported Listings</p>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h1>1</h1>
                            <p>Reported Users</p>
                        </div>
                    </li> 
                </ul>
            </div>
            <div id="users" data-tab-content>
                <h1>swhasup</h1>
            </div>
            <div id="listings" data-tab-content>
                <h1>sup dude</h1>
            </div>
        </div>
    </div>
</body>
</html>
