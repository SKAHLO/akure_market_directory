<?php
require_once 'ddb.php';

$categories = getHomeCategories($pdo);
$homelistings = getHomeListings($pdo);
?>
<main>

  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2" class="active" aria-current="true"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3" class=""></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="/assets/images/banner1.jpg" class="bd-placeholder-img">

        <div class="container">
          <div class="carousel-caption">
            <h1 class="carousel_h">Ondo Market Directory</h1>
            <p class="carousel_p">Explore businesses, shops and markets in just few clicks.</p>
            <p><a class="btn btn-lg btn-primary" href="/directory">Explore all</a></p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container">
          <div class="carousel-caption text-center">
            <h1 class="carousel_h">Ondo Market Directory</h1>
            <p class="carousel_p">Add your business, get more customers. Already listed? Claim to add details.</p>
            <p><a class="btn btn-lg btn-primary" href="/addbusiness">Sign up today</a></p>
          </div>
        </div>
      <img src="/assets/images/banner2.jpg" class="bd-placeholder-img"></div>
      
      
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>


  <div class="container marketing">

    <!-- Three columns of text below the carousel -->
    <div class="row">
      <?php 
        foreach ($categories as $cat) {
       ?>
      <div class="col-md-4 text-center">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text></svg>

        <h2><?php echo $cat['name']?></h2>
        <p><a href="/<?php echo $cat['id'];?>/directory"><button class="btn btn-secondary" type="submit" >Explore This Category »</button></a></p>
      </div>
      <?php
      }
      ?>
    </div><!-- /.row -->


    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider">

    <div class="" style="top: -2rem;position: relative;">
      <h2 class="featurette-heading">Explore</h2>
      <br>
      <div class="row">
        <?php 
        foreach ($homelistings as $hl) {
       ?>
        <div class="col-md-4 lead">
          <div class="popular-item">
              <img style=" object-fit: cover; height: 180px; width: 180px;" class="img" src="/uploads/images/<?php echo $hl['image_id']?>">
              <h4><?php echo $hl['name']?></h4>
              <p><img src="/assets/images/pin.jpg" width="25px"><?php echo $hl['full_address']?></p>
              <p><img src="/assets/images/tel.jpg" width="25px"><?php echo $hl['tel_1']?><?php if(!is_null($hl['tel_2'])){echo ', '.$hl['tel_2'];}?>n</p>
              <a href="/<?php echo $hl['id']?>/v" class="btn btn-primary-light" style="margin-top:5px">View Business</a>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      
      <div class="col-md-5 order-md-1">
        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="90%" height="auto" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveaspectratio="xMidYMid slice" focusable="false" src="/assets/images/business_signup.jpg" style="padding-bottom: 10px;">

      </div>
    <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading">Business Owner?</h2>
        <p class="lead">Claim or add your business now and <br> Get access to more customers.<br> Get notifications.</p>
        <p><a href="/addbusiness"><span class="btn btn-primary">Add Business</span></a></p>
      </div></div>

    <hr class="featurette-divider">

    <div class="row featurette" style="
    flex-direction: row-reverse;
">
      <div class="col-md-5 text-center">
        

      <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="90%" height="auto" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveaspectratio="xMidYMid slice" focusable="false" src="/assets/images/newsletter.jpg" style="
"></div><div class="col-md-7">
        <h2 class="featurette-heading">Sign up for Newsletter </h2>
        <p class="lead"><span class="text-muted">Get updates about Ondo State market.</span>
        </p>
        <form>
          <p><input type="email" name="newsletter_email" style="width:100%" class="form-control me-2"></p>
          <p><button class="btn btn-secondary">Sign Up</button></p>
        </form>
      </div>
      
    </div>

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->

  </div><!-- /.container -->  
  
</main>
