<?php
require_once 'ddb.php';

$uriexploded = explode('/',$_SERVER['REQUEST_URI']);
$index = 0;
$i = 1;

if (isset($uriexploded[2])) {
  $i = 2;
}

if(isset(explode('.', $uriexploded[$i])[1])){
  $index = intval(explode('.', $uriexploded[$i])[0])*20;
  //echo $index;
  $filter = $_SESSION['filter'];
  $listings = getFilterListings($filter, $index, $pdo);
}
else{
  $cc = isset($uriexploded[2]) ? $uriexploded[1] : NULL;
  $filter['cat'] = isp('cat') ? $_POST['cat'] : $cc;
  $filter['keyword'] = isp('keyword') ? $_POST['keyword'] : NULL;
  $filter['location'] = isp('location') ? $_POST['location'] : NULL;
  $_SESSION['filter'] = $filter;
  $listings = getFilterListings($filter, $index, $pdo);
}

?>

<main>
  <div class="container" style="
    margin-top: 20px;
    position: relative;">
    <div style="">   
      <h2><span>Explore Businesses</span></h2>
      <?php if (isset($filter['cat'])) {
      echo "
      <h4><span>" .getCategoryName(intval($filter['cat']), $pdo). "</span></h4>
      ";
      }?>
      <hr class="divider-dpage">
    </div>
  </div>
  <div class="container marketing explore-content">
    <div class="row featurette" style="">
      <div class="col-md-5 col-md-5_directory" style="">
        <form method="post" action="/directory" style="/* position: fixed; */">
            <p class="lead">What do you need?</p>
            <input type="text" name="keyword" class="filter_inputs">
            <p><span class="suggested_input">Garri</span><span class="suggested_input">Floating Berries</span></p>
            <p class="lead">Location</p>
            <input type="text" name="location" class="filter_inputs">
            <p><span class="suggested_input">Ondo City</span><span class="suggested_input">Akure</span></p>
            <input type="hidden" name="cat" <?php if(isp('cat')){echo 'value="'.$_POST['cat'].'"';}?> >
            <button class="btn btn-outline-success" type="submit" style="color: var(--s-primary);font-weight: bold;border-color: #876607;font-size: 13px;background: whitesmoke;">Apply Filter</button>
        </form>
      </div>
      <div class="col-md-7 col-md-7_directory" style="">   
        
        <?php

        foreach ($listings as $listing) {
          $category = getCategoryName($listing['category'], $pdo);
        ?>
        <div class="row featurette" style="align-items: center;background: whitesmoke;padding: 10px;border-radius: 3px;margin-bottom: 10px;">
          <div style="display:inline-block;width: 130px;padding: 0;margin: 0;text-align: center; margin-right: 10px;">
            <img src="/uploads/images/<?php echo $listing['image_id']?>" width="120" height="120" style=" object-fit: cover;">
          </div>
          <div class="" style="display: inline-block;width: calc(100% - 142px);background: white;border-radius: 10px;">
            <span class="lead2"><?php echo $category; ?></span><h4 class="results_li_h"><?php echo $listing['name']?><span></span></h4>
            <p class="lead" style="margin-bottom: 5px;">
              <img src="/assets/images/pin.jpg" width="20px"><span>&nbsp;&nbsp;<?php echo $listing['full_address']?></span>
              <br>
              <img src="/assets/images/tel.jpg" width="20px"><span>&nbsp;&nbsp;<?php echo $listing['tel_1']?><?php if(!is_null($listing['tel_2'])){echo ', '.$listing['tel_2'];}?></span>
              <br>
              <a href="/<?php echo $listing['id']?>/v"><span class="btn btn-primary" style="padding: 1px 3px;margin-top: 11px;margin-bottom: 0px;">View Details</span></a>
            </p>
          </div>
        </div>

        <?php
        }
        ?>
        <p><a href="0.directory">Previous</a> <a href="1.directory">Next</a> </p>
      </div>
    </div>
  </div><!-- /.container -->
</main>
