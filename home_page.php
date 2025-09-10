<?php
require_once 'conn.php';

// =====================
// Handle Search
// =====================
$q = trim($_GET['q'] ?? '');

if ($q) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') OR brand LIKE CONCAT('%', ?, '%') ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, "ss", $q, $q);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // Show all products
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PhoneShop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#e0f7ff; color:#0b3d91; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
a { text-decoration:none; }
.navbar { background:#0b3d91; border-radius:0 0 15px 15px; }
.navbar-brand { color:#fff !important; font-weight:bold; font-size:1.8rem; }
.nav-link { color:#fff !important; margin:0 10px; }
.nav-link:hover { color:#a3d2ff !important; }
.btn-outline-light { border-radius:50px; }
.btn-outline-light:hover { background:#fff; color:#0b3d91; }

.hero { background:linear-gradient(to right, #0b3d91, #1e90ff); color:#fff; text-align:center; padding:120px 20px; border-radius:15px; }
.hero h1 { font-size:3rem; font-weight:bold; }
.hero p { font-size:1.2rem; margin-bottom:20px; }
.btn-shop { background:#1e90ff; color:#fff; border:none; padding:10px 25px; border-radius:50px; transition:0.3s; }
.btn-shop:hover { background:#63b3ed; transform:scale(1.05); }

.card { background:#f0f8ff; color:#0b3d91; border:1px solid #a3d2ff; border-radius:15px; transition:0.3s; }
.card:hover { transform:translateY(-5px); box-shadow:0 8px 20px rgba(30,144,255,0.5); }
.card img { height:200px; object-fit:cover; border-radius:15px 15px 0 0; }

.search-bar input { border-radius:50px; border:1px solid #0b3d91; background:#fff; color:#0b3d91; padding:10px 15px; }
.search-bar input:focus { border-color:#1e90ff; box-shadow:0 0 8px #1e90ff; }

section { padding:80px 0; }
section h2 { color:#0b3d91; margin-bottom:40px; }

footer { background:#0b3d91; color:#fff; padding:40px 20px; text-align:center; border-top-left-radius:15px; border-top-right-radius:15px; }
footer a { color:#fff; margin:0 10px; font-size:1.2rem; transition:0.3s; }
footer a:hover { color:#63b3ed; transform:scale(1.2); }

</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">PhoneShop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">login</a></li>
        <li class="nav-item"><a class="nav-link" href="register_customer.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero">
  <div class="container">
    <h1>Welcome to PhoneShop</h1>
    <p>Your one-stop shop for the latest smartphones</p>
    <form method="GET" action="" class="search-bar d-flex justify-content-center mt-4">
      <input type="text" name="q" class="form-control me-2 w-50" placeholder="🔍 Search phones..." value="<?= htmlspecialchars($q) ?>">
      <button type="submit" class="btn btn-shop">Search</button>
    </form>
    <a href="#products" class="btn btn-shop mt-4">Shop Now</a>
  </div>
</section>

<!-- Products Section -->
<section id="products" class="container">
  <h2 class="text-center mb-5"><?= $q ? "🔍 Search Results for: ".htmlspecialchars($q) : " All Products" ?></h2>

  <div class="row g-4">
    <?php if(mysqli_num_rows($result) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
      <div class="col-md-3">
        <div class="card h-100 text-center p-2">
          <?php if($row['image']): ?>
            <img src="uploads/<?= $row['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
          <?php else: ?>
            <img src="https://via.placeholder.com/200x200?text=No+Image" class="card-img-top">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="text-muted"><?= htmlspecialchars($row['brand']) ?></p>
            <p class="fw-bold">$<?= number_format($row['price'],2) ?></p>
            <p class="small"><?= htmlspecialchars(substr($row['description'],0,60)) ?>...</p>
            <a href="product_detail.php?id=<?= $row['product_id'] ?>" class="btn btn-shop w-100">Shop Now</a>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">❌ No products found.</p>
    <?php endif; ?>
  </div>
</section>

<!-- About Section -->
<section id="about" class="text-center bg-light py-5 rounded">
  <div class="container">
    <h2>About PhoneShop</h2>
    <p>We bring you the latest smartphones at the best prices. Fast shipping, secure payments, and top-notch customer service.</p>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="text-center py-5">
  <div class="container">
    <h2>Contact Us</h2>
    <p>Email: support@phoneshop.com | Phone: +123 456 7890</p>
    <p>Follow us on social media!</p>
  </div>
</section>

<!-- Footer -->
<footer>
  <p>💖 Follow us:</p>
  <a href="#">Facebook</a>
  <a href="#">Instagram</a>
  <a href="#">Twitter</a>
  <p class="mt-2">&copy; <?= date("Y") ?> PhoneShop. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>
