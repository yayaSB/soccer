<?php include 'inc/db.php'; ?>
<?php include 'inc/header.php'; ?>



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
              <li><a href="matches.php" class="nav-link">Matches</a></li>
              <li class="active"><a href="players.php" class="nav-link">Players</a></li>
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
  <div class="site-wrap">
  <div class="hero overlay" style="background-image: url('images/squad.jpg');">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-5 mx-auto text-center">
          <h1 class="text-white">Players</h1>
          <p>you'll never walk alone</p>
        </div>
      </div>
    </div>
  </div>

  <style>
    .player-card {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      margin-bottom: 30px;
      overflow: hidden;
      position: relative;
    }
    .player-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(227, 24, 55, 0.2);
    }
    .player-image-container {
      position: relative;
      width: 100%;
      height: 300px;
      overflow: hidden;
    }
    .player-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    .player-card:hover .player-image {
      transform: scale(1.05);
    }
    .player-info {
      padding: 25px;
      background: linear-gradient(to bottom, #fff, #f8f9fa);
    }
    .player-name {
      font-size: 1.8rem;
      font-weight: bold;
      color: #333;
      margin-bottom: 10px;
      text-transform: uppercase;
    }
    .player-position {
      color: #e31837;
      font-size: 1.2rem;
      margin-bottom: 20px;
      font-weight: 600;
      text-transform: uppercase;
    }
    .player-details {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-bottom: 20px;
    }
    .detail-item {
      padding: 10px;
      background: #f8f9fa;
      border-radius: 8px;
      text-align: center;
    }
    .detail-label {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 5px;
    }
    .detail-value {
      font-size: 1.2rem;
      font-weight: bold;
      color: #333;
    }
    .player-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      padding-top: 20px;
      border-top: 2px solid #eee;
    }
    .stat-item {
      text-align: center;
      padding: 10px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .stat-value {
      font-size: 1.4rem;
      font-weight: bold;
      color: #e31837;
      margin-bottom: 5px;
    }
    .stat-label {
      font-size: 0.9rem;
      color: #666;
      text-transform: uppercase;
    }
    .filter-section {
      background-color: #fff;
      padding: 25px;
      margin-bottom: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    .filter-options {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .filter-btn {
      padding: 10px 20px;
      border: 2px solid #e31837;
      border-radius: 25px;
      background: white;
      color: #e31837;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      font-size: 0.9rem;
    }
    .filter-btn:hover, .filter-btn.active {
      background: #e31837;
      color: white;
    }
    .players-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
      padding: 40px 0;
    }
    @media (max-width: 768px) {
      .players-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      }
    }
  </style>

  <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="col-12 title-section">
          <h2 class="heading" style="color: black;">Star Players</h2>
        </div>
      </div>

      <div class="filter-section">
        <h3>Filter Players</h3>
        <div class="filter-options">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="forward">Forwards</button>
          <button class="filter-btn" data-filter="midfielder">Midfielders</button>
          <button class="filter-btn" data-filter="defender">Defenders</button>
          <button class="filter-btn" data-filter="goalkeeper">Goalkeepers</button>
        </div>
      </div>

      <div class="players-grid">
        <?php
        $query = "SELECT * FROM players ORDER BY position, name";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
          while($player = $result->fetch_assoc()) {
        ?>
            <div class="player-card" data-position="<?php echo strtolower($player['position']); ?>">
              <div class="player-image-container">
                <img src="images/players/<?php echo htmlspecialchars($player['image']); ?>" alt="<?php echo htmlspecialchars($player['name']); ?>" class="player-image">
              </div>
              <div class="player-info">
                <h3 class="player-name"><?php echo htmlspecialchars($player['name']); ?></h3>
                <p class="player-position"><?php echo ucfirst(htmlspecialchars($player['position'])); ?></p>
                
                <div class="player-details">
                  <div class="detail-item">
                    <div class="detail-label">Âge</div>
                    <div class="detail-value"><?php echo $player['age']; ?> ans</div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-label">Matches</div>
                    <div class="detail-value"><?php echo $player['matches']; ?></div>
            </div>
          </div>
          
                <div class="player-stats">
                  <div class="stat-item">
                    <div class="stat-value"><?php echo $player['goals']; ?></div>
                    <div class="stat-label">Buts</div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-value"><?php echo $player['assists']; ?></div>
                    <div class="stat-label">Passes D.</div>
                  </div>
                  <?php if($player['position'] == 'goalkeeper'): ?>
                  <div class="stat-item">
                    <div class="stat-value"><?php echo $player['clean_sheets']; ?></div>
                    <div class="stat-label">Clean sheets</div>
                  </div>
                  <?php else: ?>
                  <div class="stat-item">
                    <div class="stat-value"><?php echo $player['yellow_cards']; ?></div>
                    <div class="stat-label">Cartons J.</div>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
        <?php 
          }
        }
        ?>
      </div>
    </div>
  </div>
  <div class="latest-news">
      <div class="container">
        <div class="row">
          <div class="col-12 title-section">
          <h2 class="heading" style="color: black;">Last News</h2>

          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-md-4">
            <div class="post-entry">
              <a href="#">
                <img src="https://backend.liverpoolfc.com/sites/default/files/styles/xs/public/2024-08/mohamed-salah-alternative-body-v2-2024.png?itok=KOUnzkfR" alt="Image" class="img-fluid">
              </a>
              <div class="caption">
                <div class="caption-inner">
                  <h3 class="mb-3">Salah to stay at Liverpool?</h3>
                  <div class="author d-flex align-items-center">
                    <div class="img mb-2 mr-3">
                      <img src="https://pbs.twimg.com/profile_images/1741753635158024192/j0m8Ucvv_400x400.jpg" alt="">
                    </div>
                    <div class="text">
                      <h4>Fabrizio Romano</h4>
                      <span>May 19, 2024&bullet; Sports</span>
                    </div>
                  </div>
                </div>
              </div> 
            </div>
          </div>
          <div class="col-md-4">
            <div class="post-entry">
              <a href="#">
                <img src="https://backend.liverpoolfc.com/sites/default/files/styles/lg/public/2024-06/conor-bradley-profile-action-shot-202425.png?itok=UDiNyQTS" alt="Image" class="img-fluid">
              </a>
              <div class="caption">
                <div class="caption-inner">
                  <h3 class="mb-3">Conor Bradley on defeat at Tottenham, second-leg motivation and return from injury</h3>
                  <div class="author d-flex align-items-center">
                    <div class="img mb-2 mr-3">
                      <img src="https://pbs.twimg.com/profile_images/1741753635158024192/j0m8Ucvv_400x400.jpg" alt="">
                    </div>
                    <div class="text">
                      <h4>Fabrizio Romano</h4>
                      <span>May 19, 2024 &bullet; Sports</span>
                    </div>
                  </div>
                </div>
              </div> 
            </div>
          </div>
          <div class="col-md-4">
            <div class="post-entry">
              <a href="#">
                <img src="https://backend.liverpoolfc.com/sites/default/files/styles/xs/public/2024-06/cody-gakpo-profile-body-shot-202425.webp?itok=H8FOTORF" alt="Image" class="img-fluid">
              </a>
              <div class="caption">
                <div class="caption-inner">
                  <h3 class="mb-3">Gakpo to stay at Liverpool?</h3>
                  <div class="author d-flex align-items-center">
                    <div class="img mb-2 mr-3">
                      <img src="https://pbs.twimg.com/profile_images/1741753635158024192/j0m8Ucvv_400x400.jpg" alt="">
                    </div>
                    <div class="text">
                      <h4>Fabrizio Romano</h4>
                      <span>May 19, 2024 &bullet; Sports</span>
                    </div>
                  </div>
                </div>
              </div> 
            </div>
          </div>
        </div>
        

      </div>
    </div>

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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const playerCards = document.querySelectorAll('.player-card');

    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        // Add active class to clicked button
        button.classList.add('active');

        const filter = button.getAttribute('data-filter');

        playerCards.forEach(card => {
          if (filter === 'all' || card.getAttribute('data-position') === filter) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  });
</script>
