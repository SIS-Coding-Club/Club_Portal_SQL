<?php
session_start();

$secret = require __DIR__ . '/../auth/secret.php';
$SignedIn = isset($_SESSION['user']);
$user = $_SESSION['user'] ?? null;

$host = $secret['host'];
$username = $secret['username'];
$password = $secret['password'];
$dbname = $secret['dbname'];

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    exit('Database connection failed.');
}

function e($value): string
{
    return htmlspecialchars(($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if (!$SignedIn) {
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiger Clubs Portal - Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css"/>
</head>
<body>
<div id="top-nav-bar" class="classic">
    <div id="pagetop" class="sis-bar notranslate primary-white">
        <a id="sis-logo" href="../index.php" class="sis-bar-item sis-button sis-left" title="Home">
            <i class="fa" aria-hidden="true">1</i>
        </a>
        <nav class="tnb-desktop-nav sis-bar-item">
            <a id="inactive" href="../index.php" class="sis-bar-item sis-padding-16 sis-button ">Home</a>
            <a id="inactive" href="../feed" class="sis-bar-item  sis-padding-16 sis-button">Feed</a>
            <a id="inactive" href="../calendar" class="sis-bar-item sis-padding-16 sis-button">Calendar</a>
            <a id="active" href="admin.php" class="sis-bar-item sis-padding-16 sis-button">Admin Dashboard</a>
        </nav>
        <div class="tnb-right-section">
            <a href="../auth/signout.php">
                <div id="tnb-sign-btn" class="tnb-sign-btn sis-bar-item sis-right sis-button"
                     title="Sign in to your account">
                    <span class="button-text">Sign Out</span>
                </div>
            </a>
            <a href="../assets/site_images/fair_map.png" class="tnb-right-side-btn sis-bar-item sis-button sis-right" title="Club Fair Map" aria-label="Club Fair Map">Fair Map</a>
        </div>
    </div>
</div>
<div class="topnavbackground"></div>
<div class="topnavcontainer">
    Placeholder for announcements
</div>
<div class="background-image"></div>
<div class="contentcontainer">
    <div class="belowtopnavcontainer">
        <div class="sis-main" id="main">
            <div class="content">
                <div class="section-head">
                    <h2>Admin Dashboard</h2>
                    <p>Manage clubs and users</p>
                </div>
                <div class="club-panel">
                    <div class="club-section">
                        <h2>Select Club</h2>
                        <label for="club-search" style="display: none">Search Clubs...</label>
                        <input id="club-search" type="text" class="club-search" placeholder="Search Clubs...">
                        <div class="club-list">
                            <label for="club-options" style="display: none">Clubs</label>
                            <select id="club-options" class="club-options" size="10">
                                <option value="newentry">Add a new club...</option>
                                <?php
                                $sql = "SELECT DirName, Name FROM clubs ORDER BY Name ASC";
                                $result = $conn->query($sql);

                                if (!$result) {
                                    die("Query failed: " . $conn->error);
                                }
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='".$row["DirName"]."'>" . $row["Name"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <h2>Banner Preview</h2>
                        <div class="banner-section see-thru">
                            <div>No Image...</div>
                        </div>
                        <h2>Upload Banner</h2>
                        <div class="banner-upload see-thru">
                            <label for="banner-input" class="upload-label">
                                Upload banners in only png format.
                                <input id="banner-input" type="file" accept="image/png" style="display: none">
                            </label>
                        </div>
                    </div>
                    <div class="club-section">
                        <h2>Modify Club</h2>
                        <div class="form-group see-thru">
                            <div class="form-group-title">Generic Information</div>
                            <div class="form-grid">
                                <label for="club-name">Club Name</label>
                                <input id="club-name" type="text" class="form-input" placeholder="Club Name">
                                <label for="club-type">Club Type(s) – Separate by comma with spaces</label>
                                <input id="club-type" class="form-input" placeholder="Club Types">
                            </div>
                        </div>
                        <div class="form-group see-thru">
                            <div class="form-group-title">Club Descriptions</div>
                            <div class="form-grid">
                                <label for="club-summary">Club Summary – Main page card</label>
                                <textarea id="club-summary" class="form-input" placeholder="Club Summary"></textarea>
                                <label for="club-about">Club Description - Detailed description</label>
                                <textarea id="club-about" class="form-input" placeholder="Club Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group see-thru">
                            <div class="form-group-title">Additional Information</div>
                            <div class="form-grid">
                                <label for="club-day">Meeting Day</label>
                                <select id="club-day" class="form-input">
                                    <option value="Monday">Monday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday A">Thursday A</option>
                                    <option value="Thursday B">Thursday B</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="club-location">Meeting Location</label>
                                <input id="club-location" type="text" class="form-input" placeholder="Meeting Location">
                                <label for="club-members">Member Count</label>
                                <input id="club-members" type="number" class="form-input" value="0" min="0">
                            </div>
                        </div>
                        <div class="form-group see-thru">
                            <div class="form-group-title">Contact Information</div>
                            <div class="form-grid">
                                <label for="club-advisors">Advisor Email(s) – Separate by comma with spaces</label>
                                <input id="club-advisors" type="text" class="form-input">
                                <label for="club-executives">Executive Emails – Separate by comma with spaces</label>
                                <input id="club-executives" type="text" class="form-input">
                                <label for="club-instagram">Instagram Handle – Exclude the @ symbol</label>
                                <input id="club-instagram" type="text" class="form-input" placeholder="sis_tigers">
                                <label for="club-youtube">YouTube – Include the full URL</label>
                                <input id="club-youtube" type="text" class="form-input" placeholder="https://www.youtube.com/playlist?list=PLY4AlYc_waYI">
                                <label for="club-website">Website – Include the full URL</label>
                                <input id="club-website" type="text" class="form-input" placeholder="https://tigerclubs.org">
                                <label for="club-social">Extra Socials – Include the full URL</label>
                                <input id="club-social" type="text" class="form-input" placeholder="https://github.com/JAYDY0102/Club_Portal_SQL">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="wrappercontainer">
    <div class="footerwrapper">
        <div class="spacefooter">
            <div class="footerlinks" style="overflow:hidden;">
                <div class="footerlinks_1">
                    <a href="https://tigerclubs.org/index.php" aria-label="Tigerclubs.org">
                        <i class="fa fa-logo">1</i>
                    </a>
                </div>
                <div class="footerlinks_1">
                    <a href="https://forms.gle/mgUxnthy2izYn4yi8" title="Submit a request to add an image on the main banner">BANNER REQUEST</a>
                </div>
                <div class="footerlinks_1">
                    <a href="https://forms.gle/QwJxodQaQRro4cqB7" title="Submit an interest form cooperatively create a website for your own club with Coding Club">INTEREST FORM</a>
                </div>
                <div class="footerlinks_1">
                    <a href="https://forms.gle/KFqJG2EHqEsWUuB47" title="Submit a bug report that you have encountered on the website">BUG REPORT</a>
                </div>
                <div class="footerlinks_1">
                    <?php
                    $sqlContact = "SELECT Executives FROM clubs WHERE DirName='coding_club'";
                    $resultContact = $conn->query($sqlContact);
                    $ExecutivesContacts = $resultContact->fetch_assoc()['Executives'];
                    $ExecutivesContactList = array_map('trim', explode(',', $ExecutivesContacts));
                    $PresidentContact = $ExecutivesContactList[0];
                    echo
                    "<a href='mailto:$PresidentContact' title='Contact Us!'>CONTACT US</a>";
                    $conn->close();
                    ?>
                </div>
            </div>
            <div class="footertext">
                Tigerclubs.org is made to promote connectivity across all clubs of SIS. It prioritizes accessibility over functionality.
                <br>
                Select members of Coding Club are constantly working to improve the website, but we cannot warrant that it will be free of bugs.
                <br>
                Please use the links below to submit any main banner request, club-specific website interest form, or bug reports if you happen to notice any.
                <br>
                <br>
                <a href="https://github.com/JAYDY0102/Club_Portal_SQL/blob/master/LICENSE">MIT License</a>
                of the website's source code.
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    const clubOptions = document.getElementById('club-options');
    const bannerSection = document.querySelector('.banner-section');
    const bannerInput = document.getElementById('banner-input');
    const nameInput = document.getElementById('club-name');
    const typeInput = document.getElementById('club-type');
    const summaryInput = document.getElementById('club-summary');
    const aboutInput = document.getElementById('club-about');
    const dayInput = document.getElementById('club-day');
    const locationInput = document.getElementById('club-location');
    const membersInput = document.getElementById('club-members');
    const advisorsInput = document.getElementById('club-advisors');
    const executivesInput = document.getElementById('club-executives');
    const instagramInput = document.getElementById('club-instagram');
    const youtubeInput = document.getElementById('club-youtube');
    const websiteInput = document.getElementById('club-website');
    const socialInput = document.getElementById('club-social');

    clubOptions.addEventListener('change', async () => {
        const DirName = clubOptions.value;
        updateBannerPreview(DirName, '')
        updateClubInformation(DirName)
    })

    bannerInput.addEventListener('change', async () => {
        const DirName = clubOptions.value;
        const file = bannerInput.files[0];

        if (!file) {
            return;
        }

        const formData = new FormData();
        formData.append('type', 'banner')
        formData.append('file', file);
        formData.append('dirName', DirName);

        try {
            const response = await fetch('../post.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            const status = result.split(', ');
            if (status[0] === 'rei') {
                updateBannerPreview(DirName, status[1]);
                console.log(status[0],status[1])
            } else if (status[0] === 'asuka') {
                updateBannerPreview(status[1], '');
                console.log(status[0],status[1])
            } else if (status[0] === 'shinji-01') {
                console.error('kaworu',status[1]);
            } else if (status[0] === 'shinji-13') {
                console.error('mari',status[1]);
            }
        } catch (error) {
            console.error('Error uploading banner:', error);
        }
    })

    function updateBannerPreview(DirName, version) {
        if (DirName === 'newentry') {
            bannerSection.innerHTML = `<div>No Image...</div>`;
        } else {
            if (version !== '') {
                const versionParam = `?v=${version}`;
                bannerSection.innerHTML = `<img src="../assets/banners/${DirName}.png${versionParam}" alt="Banner Preview">`;
            } else {
                bannerSection.innerHTML = `<img src="../assets/banners/${DirName}.png" alt="Banner Preview">`;
            }
        }
    }

    async function updateClubInformation(DirName) {
        const formData = new FormData();
        formData.append('type', 'club')
        formData.append('dirName', DirName);

        try {
            const response = await fetch('../post.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            const status = result.split(';');
            if (status[0] === 'rei') {
                console.log(status[0])
                nameInput.value = status[1];
                typeInput.value = status[2];
                summaryInput.value = status[3];
                aboutInput.value = status[4];
                dayInput.value = status[5];
                locationInput.value = status[6];
                membersInput.value = status[7];
                advisorsInput.value = status[8];
                executivesInput.value = status[9];
                instagramInput.value = status[10];
                youtubeInput.value = status[11];
                websiteInput.value = status[12];
                socialInput.value = status[13];
            } else if (status[0] === 'shinji-01') {
                console.error('kaworu','query failed');
            } else if (status[0] === 'shinji-13') {
                console.error('mari','query failed');
            } else {
                console.error('unknown error');
            }
        } catch (error) {
            console.error('Error updating club information:', error);
        }
    }
</script>