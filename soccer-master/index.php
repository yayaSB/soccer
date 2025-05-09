<?php include 'inc/db.php'; ?> <!-- Include database connection -->
<?php include 'inc/header.php'; ?> <!-- Include reusable header -->

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] == 'admin') {
    header("Location: admin/dashboard.php");
    exit();
}
?>
<header class="site-navbar py-4" role="banner">
<div class="container mt-5">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <p>You have normal user privileges.</p>
    
</div>
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
                        <li class="active"><a href="index.php" class="nav-link">Home</a></li>
                        <li><a href="matches.php" class="nav-link">Matches</a></li>
                        <li><a href="players.php" class="nav-link">Players</a></li>
                        <li><a href="blog.php" class="nav-link">Blog</a></li>
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                        <li><a href="logout.php" class="nav-link">Logout</a></li>
                    </ul>
                </nav>
                <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right text-white">
                    <span class="icon-menu h3 text-white"></span>
                </a>
            </div>
        </div>
    </div>
</header>

<div class="hero overlay" style="background-image: url('images/anfield.jpg');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 ml-auto">
                <h1 class="text-white">SportSync</h1>
                <p>Liverpool's clash against Real Madrid in the Champions League has become a highly anticipated rivalry, showcasing intense battles between two of Europe's most successful football clubs.</p>
                <div id="date-countdown"></div>
                <p>
                    <a href="#" class="btn btn-primary py-3 px-4 mr-3">Book Ticket</a>
                    <a href="#" class="more light">Learn More</a>
                </p>  
            </div>
        </div>
    </div>
</div>

<div class="latest-news">
    <div class="container">
        <div class="row">
            <div class="col-12 title-section text-center mb-4">
                <h2 class="heading" style="color: black; font-size: 2rem; font-weight: 600; position: relative; display: inline-block;">
                    Latest News
                    <span style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 40px; height: 2px; background: #ff4d4d;"></span>
                </h2>
            </div>
        </div>
        <div class="row">
            <?php
            $result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="post-entry" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                            <a href="#" style="display: block; overflow: hidden;">
                                <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="Image" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover; transition: transform 0.3s ease;">
                            </a>
                            <div class="caption" style="padding: 15px;">
                                <div class="caption-inner">
                                    <h3 class="mb-2" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                                        <a href="#" style="color: inherit; text-decoration: none;"><?php echo htmlspecialchars($row['title']); ?></a>
                                    </h3>
                                    <div class="author d-flex align-items-center" style="border-top: 1px solid #eee; padding-top: 10px;">
                                        <div class="img mb-2 mr-3" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                            <img src="images/fabrizio.jpg" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="text">
                                            <h4 style="font-size: 0.9rem; margin: 0; color: #666;"><?php echo htmlspecialchars($row['author']); ?></h4>
                                            <span style="font-size: 0.8rem; color: #888;"><?php echo date('F d, Y', strtotime($row['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No blog posts available.</p>";
            }
            ?>
        </div>
    </div>
</div>

<div class="site-section bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="widget-next-match">
                    <div class="widget-title">
                        <h3>Next Match</h3>
                    </div>
                    <div class="widget-body mb-3">
                        <?php
                        $result = $conn->query("SELECT * FROM matches ORDER BY match_date ASC LIMIT 1");
                        if ($result->num_rows > 0) {
                            $match = $result->fetch_assoc();
                            ?>
                            <div class="widget-vs">
                                <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                                    <div class="team-1 text-center">
                                        <img src="images/<?php echo htmlspecialchars($match['team_1_logo']); ?>" alt="Image">
                                        <h3><?php echo htmlspecialchars($match['team_1']); ?></h3>
                                    </div>
                                    <div>
                                        <span class="vs"><span>VS</span></span>
                                    </div>
                                    <div class="team-2 text-center">
                                        <img src="images/<?php echo htmlspecialchars($match['team_2_logo']); ?>" alt="Image">
                                        <h3><?php echo htmlspecialchars($match['team_2']); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center widget-vs-contents mb-4">
                                <h4><?php echo htmlspecialchars($match['league']); ?></h4>
                                <p class="mb-5">
                                    <span class="d-block"><?php echo date('F d, Y', strtotime($match['match_date'])); ?></span>
                                    <span class="d-block"><?php echo htmlspecialchars($match['match_time']); ?></span>
                                    <strong class="text-primary"><?php echo htmlspecialchars($match['venue']); ?></strong>
                                </p>
                            </div>
                            <?php
                        } else {
                            echo "<p class='text-center text-white'>No upcoming matches available.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="site-section bg-dark">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <div class="widget-next-match">
              <div class="widget-title">
                <h3>Next Match</h3>
              </div>
              <div class="widget-body mb-3">
                <div class="widget-vs">
                  <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                    <div class="team-1 text-center">
                      <img src="https://upload.wikimedia.org/wikipedia/en/0/0c/Liverpool_FC.svg" alt="Image">
                      <h3>LIVERPOOL</h3>
                    </div>
                    <div>
                      <span class="vs"><span>VS</span></span>
                    </div>
                    <div class="team-2 text-center">
                      <img src="https://upload.wikimedia.org/wikipedia/en/5/53/Arsenal_FC.svg" alt="Image">
                      <h3>ARSENAL</h3>
                    </div>
                  </div>
                </div>
              </div>

              <div class="text-center widget-vs-contents mb-4">
                <h4>Premier League</h4>
                <p class="mb-5">
                  <span class="d-block" style="color: white;">January 20th, 2025</span>
                  <span class="d-block" style="color: white;">9:30 AM GMT+0</span>
                  <strong class="text-primary">Anfield</strong>
                </p>

                <div id="date-countdown2" class="pb-1"></div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            
            <div class="widget-next-match">
              <table class="table custom-table">
                <thead>
                  <tr>
                    <th>P</th>
                    <th>Team</th>
                    <th>W</th>
                    <th>D</th>
                    <th>L</th>
                    <th>PTS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong class="text-white">1</td>
                    <td><strong class="text-white">Liverpool</strong></td>
                    <td><strong class="text-white">14</td>
                    <td><strong class="text-white">4</td>
                    <td><strong class="text-white">1</td>
                    <td><strong class="text-white">46</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">2</td>
                    <td><strong class="text-white">Arsenal_FC</strong></td>
                    <td><strong class="text-white">11</td>
                    <td><strong class="text-white">7</td>
                    <td><strong class="text-white">2</td>
                    <td><strong class="text-white">40</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">3</td>
                    <td><strong class="text-white">Nottm Forest</strong></td>
                    <td><strong class="text-white">12</td>
                    <td><strong class="text-white">4</td>
                    <td><strong class="text-white">4</td>
                    <td><strong class="text-white">40</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">4</td>
                    <td><strong class="text-white">Chelsea</strong></td>
                    <td><strong class="text-white">10</td>
                    <td><strong class="text-white">6</td>
                    <td><strong class="text-white">4</td>
                    <td><strong class="text-white">36</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">5</td>
                    <td><strong class="text-white">NewCastle</strong></td>
                    <td><strong class="text-white">10</td>
                    <td><strong class="text-white">5</td>
                    <td><strong class="text-white">25</td>
                    <td><strong class="text-white">35</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">6</td>
                    <td><strong class="text-white">Man City</strong></td>
                    <td><strong class="text-white">10</td>
                    <td><strong class="text-white">4</td>
                    <td><strong class="text-white">6</td>
                    <td><strong class="text-white">34</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">7</td>
                    <td><strong class="text-white">Bournemouth</strong></td>
                    <td><strong class="text-white">9</td>
                    <td><strong class="text-white">6</td>
                    <td><strong class="text-white">5</td>
                    <td><strong class="text-white">33</td>
                  </tr>
                  <tr>
                    <td><strong class="text-white">8</td>
                    <td><strong class="text-white">Aston Villa</strong></td>
                    <td><strong class="text-white">9</td>
                    <td><strong class="text-white">6</td>
                    <td><strong class="text-white">5</td>
                    <td><strong class="text-white">33</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div> 
    

    <?php include 'inc/footer.php'; ?>
    
