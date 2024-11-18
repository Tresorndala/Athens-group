<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About CampusFixIt</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div w3-include-html="nav.html"></div>
    <div class="container">
        <div class="hero-section">
            <div class="floating-elements">
                <div class="floating-element"></div>
                <div class="floating-element"></div>
                <div class="floating-element"></div>
            </div>
            <div class="title-wrapper">
                <h1>About CampusFixIt <span class="tools-icon">🛠️</span></h1>
            </div>
            <div class="content-wrapper">
                <p class="description">
                    CampusFixIt is designed to streamline campus maintenance requests. Students and staff can easily report issues, track progress, and help maintain a better learning environment for all.
                </p>
            </div>
            <footer class="footer">
                <p>© 2024 CampusFixIt</p>
            </footer>
        </div>
    </div>

    <script>
        function includeHTML() {
            var z, i, elmnt, file, xhttp;
            z = document.getElementsByTagName("*");
            for (i = 0; i < z.length; i++) {
                elmnt = z[i];
                file = elmnt.getAttribute("w3-include-html");
                if (file) {
                    xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4) {
                            if (this.status == 200) {elmnt.innerHTML = this.responseText;}
                            if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
                            elmnt.removeAttribute("w3-include-html");
                            includeHTML();
                        }
                    }
                    xhttp.open("GET", file, true);
                    xhttp.send();
                    return;
                }
            }
        }
        includeHTML();
    </script>
    <script src="about.js"></script>
</body>
</html>