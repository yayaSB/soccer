<?php
session_start();
include 'inc/db.php'; // Database connection

// Fetch all blog posts with a simpler query
$query = "SELECT id, title, content, image, created_at, 'News' as category FROM blogs ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
      .blog-card {
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 30px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
      }
      .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(227, 24, 55, 0.2);
      }
      .blog-image-container {
        position: relative;
        width: 100%;
        padding-top: 60%; /* Aspect ratio 16:9 */
        overflow: hidden;
      }
      .blog-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
      }
      .blog-card:hover .blog-image {
        transform: scale(1.05);
      }
      .blog-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #fff, #f8f9fa);
      }
      .blog-meta {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        gap: 15px;
      }
      .blog-date {
        color: #666;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
      }
      .blog-date i {
        margin-right: 5px;
      }
      .blog-category {
        background-color: #e31837;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 600;
      }
      .blog-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.4;
      }
      .blog-excerpt {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
        flex-grow: 1;
      }
      .read-more {
        display: inline-block;
        padding: 10px 25px;
        background-color: #e31837;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s ease;
        text-align: center;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        align-self: flex-start;
      }
      .read-more:hover {
        background-color: #c0142f;
        color: white;
        transform: translateY(-2px);
      }
      .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        padding: 40px 0;
      }
      .section-title {
        text-align: center;
        margin-bottom: 40px;
        position: relative;
        padding-bottom: 20px;
      }
      .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background-color: #e31837;
      }
      @media (max-width: 768px) {
        .blog-grid {
          grid-template-columns: 1fr;
          padding: 20px 0;
        }
      }
    </style>
</head>

<body>
    <div class="site-wrap">
        <!-- Header -->
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
                <li><a href="players.php" class="nav-link">Players</a></li>
                <li class="active"><a href="blog.php" class="nav-link">Blog</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
                <li><a href="logout.php" class="nav-link">Logout</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>

        <!-- Hero Section -->
        <div class="hero overlay" style="background-image: url('images/anfield.jpg');">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 mx-auto text-center">
                        <h1 class="text-white">Latest News</h1>
                        <p>Stay updated with the latest information</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section-title">
                <h2>Latest Updates</h2>
            </div>

            <div class="blog-grid">
                <?php
                if ($result->num_rows > 0) {
                    while ($blog = $result->fetch_assoc()) {
                ?>
                        <div class="blog-card">
                            <div class="blog-image-container">
                                <img src="images/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-image">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-date">
                                        <i class="icon-calendar"></i>
                                        <?php echo date('M j, Y', strtotime($blog['created_at'])); ?>
                                    </span>
                                    <span class="blog-category"><?php echo htmlspecialchars($blog['category']); ?></span>
                                </div>
                                <h3 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                <p class="blog-excerpt"><?php echo htmlspecialchars(substr($blog['content'], 0, 150)) . '...'; ?></p>
                                <a href="blog_details.php?id=<?php echo $blog['id']; ?>" class="read-more">Read More</a>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center'>No articles found!</p>";
                }
                ?>
            </div>
        </div>

        <!-- Footer -->
        <?php include 'inc/footer.php'; ?>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
