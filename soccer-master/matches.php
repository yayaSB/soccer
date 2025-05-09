<?php
// Start the session
session_start();
include 'inc/db.php'; // Include database connection

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php include 'inc/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Soccer &mdash; Matches</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<header class="site-navbar py-4" role="banner">
      <div class="container">
        <div class="d-flex align-items-center">
          <div class="site-logo">
            <a href="index.php">
              <img src="images/logo.png" alt="Logo">
            </a>
          </div>
          <div class="ml-auto">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li class="active"><a href="matches.php" class="nav-link">Matches</a></li>
                <li><a href="players.php" class="nav-link">Players</a></li>
                <li><a href="blog.php" class="nav-link">Blog</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
                <li><a href="logout.php" class="nav-link">Logout</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>
<body>
  

    <!-- Header -->
  
    <div class="site-wrap">

    <!-- Mobile Menu -->
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    <!-- Hero Section -->
    <div class="hero overlay" style="background-image: url('images/anfield.jpg');">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5 mx-auto text-center">
            <h1 class="text-white">Matches</h1>
            <p>you'll never walk alone</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Matches Section -->
    <div class="container py-5">
      <div class="row">
        <div class="col-lg-12">
          <?php
          // Fetch matches from the database
          $query = "SELECT * FROM matches ORDER BY match_date ASC";
          $result = $conn->query($query);

          if (!$result) {
              echo "<p class='text-danger'>Error fetching matches: " . $conn->error . "</p>";
          } elseif ($result->num_rows == 0) {
              echo "<p class='text-center'>No matches available.</p>";
          } else {
              while ($match = $result->fetch_assoc()) {
          ?>
                <div class="d-flex team-vs mb-4">
                  <!-- Score Section -->
                  <span class="score"><?php echo htmlspecialchars($match['score'] ?? '-'); ?></span>
                  <!-- Team 1 Details -->
                  <div class="team-1 w-50">
                    <div class="team-details w-100 text-center">
                      <img src="images/<?php echo htmlspecialchars($match['team_1_logo']); ?>" alt="Team 1 Logo" class="img-fluid" style="max-width: 100px;">
                      <h3><?php echo htmlspecialchars($match['team_1']); ?></h3>
                    </div>
                  </div>
                  <!-- Team 2 Details -->
                  <div class="team-2 w-50">
                    <div class="team-details w-100 text-center">
                      <img src="images/<?php echo htmlspecialchars($match['team_2_logo']); ?>" alt="Team 2 Logo" class="img-fluid" style="max-width: 100px;">
                      <h3><?php echo htmlspecialchars($match['team_2']); ?></h3>
                    </div>
                  </div>
                </div>
                
      
          <?php
              }
          }
          ?>
           <div class="row">
            <div class="col-12 title-section">
            <h2 class="heading">Upcoming Match</h2>
          </div>
          <div class="col-lg-6 mb-4">
            <div class="bg-light p-4 rounded">
              <div class="widget-body">
                  <div class="widget-vs">
                    <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                      <div class="team-1 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg" alt="Logo de Liverpool FC" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Liverpool</h3>
                      </div>
                      <div>
                        <span class="vs"><span>VS</span></span>
                      </div>
                      <div class="team-2 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/4/47/FC_Barcelona_%28crest%29.svg" alt="Logo de FC Barcelone" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Barcelone</h3>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-center widget-vs-contents mb-4">
                  <h4>Champions League</h4>
                  <p class="mb-5">
                    <span class="d-block">December 20th, 2025</span>
                    <span class="d-block">9:30 AM GMT+0</span>
                    <strong class="text-primary">anfield</strong>
                  </p>

                </div>
              
            </div>
          </div>
          <div class="col-lg-6 mb-4">
            <div class="bg-light p-4 rounded">
              <div class="widget-body">
                  <div class="widget-vs">
                    <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                      <div class="team-1 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg" alt="Logo de Liverpool FC" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Liverpool</h3>
                      </div>
                      <div>
                        <span class="vs"><span>VS</span></span>
                      </div>
                      <div class="team-2 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/5/56/Real_Madrid_CF.svg" alt="Logo de Real Madrid" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Real Madrid</h3>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-center widget-vs-contents mb-4">
                  <h4>Champions League</h4>
                  <p class="mb-5">
                    <span class="d-block">December 20th, 2025</span>
                    <span class="d-block">9:30 AM GMT+0</span>
                    <strong class="text-primary">anfield</strong>
                  </p>

                </div>
              
            </div>
          </div>

          <div class="col-lg-6 mb-4">
            <div class="bg-light p-4 rounded">
              <div class="widget-body">
                  <div class="widget-vs">
                    <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                      <div class="team-1 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg" alt="Logo de Liverpool FC" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Liverpool</h3>
                      </div>
                      <div>
                        <span class="vs"><span>VS</span></span>
                      </div>
                      <div class="team-2 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_United_FC_crest.svg" alt="Logo de Manchester United" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Man United</h3>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-center widget-vs-contents mb-4">
                  <h4>Premier League</h4>
                  <p class="mb-5">
                    <span class="d-block">January 21th, 2020</span>
                    <span class="d-block">9:30 AM GMT+0</span>
                    <strong class="text-primary">anfield</strong>
                  </p>

                </div>
              
            </div>
          </div>
          <div class="col-lg-6 mb-4">
            <div class="bg-light p-4 rounded">
              <div class="widget-body">
                  <div class="widget-vs">
                    <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                      <div class="team-1 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg" alt="Logo de Liverpool FC" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Liverpool</h3>
                      </div>
                      <div>
                        <span class="vs"><span>VS</span></span>
                      </div>
                      <div class="team-2 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/en/c/cc/Chelsea_FC.svg" alt="Logo de Chelsea" style="width: 100px; height: 100px; object-fit: contain;">
                        <h3>Chelsea</h3>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="text-center widget-vs-contents mb-4">
                  <h4>EFL CUP</h4>
                  <p class="mb-5">
                    <span class="d-block">March 20th, 2025</span>
                    <span class="d-block">9:30 AM GMT+0</span>
                    <strong class="text-primary">anfield</strong>
                  </p>

                </div>
              
            </div>
          </div>
          
        </div>
      </div>
    </div>
        </div>
      </div>
    </div>
    <div class="container site-section">
      <div class="row">
        <div class="col-6 title-section">
          <h2 class="heading">NEWS</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="custom-media d-flex">
            <div class="img mr-4">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8J_0E1_X9tR7CRmzMkzxUWpKruwg4T3MbQg&s" alt="Image" class="img-fluid">
            </div>
            <div class="text">
              <span class="meta">May 10, 2024</span>
              <h3 class="mb-4"><a href="#">LIV VS RMA</a></h3>
              <p>Real Madrid and Liverpool’s 2024 Champions League clash promises a thrilling encounter between two football giants.</p>
              <p><a href="#">Read more</a></p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="custom-media d-flex">
            <div class="img mr-4">
              <img src="https://images2.minutemediacdn.com/image/upload/c_fill,w_1200,ar_1:1,f_auto,q_auto,g_auto/images/GettyImages/mmsport/287/01j7384d3z1kgw7ma39f.jpg" alt="Image" class="img-fluid">
            </div>
            <div class="text">
              <span class="meta">December 20, 2024</span>
              <h3 class="mb-4"><a href="#">LIV VS CHE</a></h3>
              <p>Chelsea and Liverpool’s 2024 Champions League clash promises a thrilling encounter between two football giants.</p>
              <p><a href="#">Read more</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php include 'inc/footer.php'; ?>

  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/jquery.mb.YTPlayer.min.js"></script>
  <script src="js/main.js"></script>
</body>

</html>
