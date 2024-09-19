<?php
require_once 'ddb.php';
$listings = getUserListings($userid, 0, $pdo);
?>

<main>
  <div class="container-fluid" style="
    margin-top: 20px;
    position: relative;">
    <div style="">   
      <h2><span id="title">Dashboard</span></h2>
      <?php
      echo "
      <h4><span>" .$username. "</span></h4>
      ";
      ?>
      <hr class="divider-dpage">
    </div>
  </div>
  <div class="container-fluid marketing explore-content">
    <div class="row featurette" style="">
      <div class="col-sm-3 dashboard-col-1">
        <br>
        <p class="lead"><a href="/dashboard">My Listings</a></p>
        <p class="lead"><a href="/addbusiness">Add Listing</a></p>
        <p class="lead"><a href="/profile">My Profile</a></p>
        <p class="lead"><a href="/password">Change Password</a></p>
        <p class="lead"><a href="/signout">Sign Out</a></p> 
      </div>
      <div class="col-md-7 dashboard-col-2" style="">   
        <!-- Listings View -->
        <?php
        if(isset($dashboard)){
        foreach ($listings as $listing) {
          $category = getCategoryName($listing['category'], $pdo);
        ?>
        <div class="row featurette" style="align-items: center;background: whitesmoke;padding: 10px;border-radius: 5px; margin: 3px 6px 10px calc(var(--bs-gutter-x)* .5);">
          <div style="display:inline-block;width: fit-content; padding: 0;  margin: 0;margin-right: 15px;text-align: center;">
            <img src="/uploads/images/<?php echo $listing['image_id']?>" width="120" height="120">
          </div>
          <div class="" style="display: inline-block;width: calc(100% - 155px);background: white;border-radius: 10px;">
            <span class="lead2"><?php echo $category; ?></span><h4 class="results_li_h"><?php echo $listing['name']?><span></span></h4>
            <p class="lead" style="margin-bottom: 5px;">
              <img src="assets/images/pin.jpg" width="20px"><span>&nbsp;&nbsp;<?php echo $listing['full_address']?></span>
              <br>
              <img src="assets/images/tel.jpg" width="20px"><span>&nbsp;&nbsp;<?php echo $listing['tel_1']?><?php if(!is_null($listing['tel_2'])){echo ', '.$listing['tel_2'];}?></span>
              <br>
              <a href="/<?php echo $listing['id']?>/v"><span class="btn btn-primary" style="padding: 1px 3px;margin-top: 11px;margin-bottom: 0px;">View Details</span></a>
              <a href="/<?php echo $listing['id']?>/editbusiness"><span class="btn btn-primary" style="padding: 1px 3px;margin-top: 11px;margin-bottom: 0px;margin-left:20px;">Edit Business</span></a>
              <a href="/<?php echo $listing['id']?>/remove"><span class="btn btn-primary" style="padding: 1px 3px;margin-top: 11px;margin-bottom: 0px;margin-left:20px;">Remove Business</span></a>
            </p>
          </div>
        </div>

        <?php
        }}elseif(isset($profile)){
        ?>
        <!-- End: Listings View -->
        <!-- Profile -->
        <script>document.getElementById('title').innerHTML = 'Profile';</script>
        <div class="profile">
          <p class="lead profile-field-title"> Username </p>
          <p class="profile-field"> <?php echo $username;?> </p>
          <p class="lead profile-field-title"> First Name </p>
          <p class="profile-field"> <?php echo $userfname;?> </p>
          <p class="lead profile-field-title"> Last Name </p>
          <p class="profile-field"> <?php echo $userlname;?> </p>
          <p class="lead profile-field-title"> Email </p>
          <p class="profile-field"> <?php echo $useremail;?> </p>
          <a href="/editprofile"><p class="btn-primary profile-field" style="background:var(--s-primary)">Edit</p></a>
        </div>
        <?php
        }elseif(isset($editprofile)){
        ?>
        <!-- End: Listings View -->
        <!-- Profile -->
        <script>document.getElementById('title').innerHTML = 'Profile';</script>
        <div class="profile">
        <form action="/updateprofile" method="post">
          <p class="lead profile-field-title"> Username </p>
          <input name="username" type="text" class="profile-field" value="<?php echo $username;?>"/>
          <p class="lead profile-field-title"> First Name </p>
          <input name="fname" type="text" class="profile-field" value="<?php echo $userfname;?>"/>
          <p class="lead profile-field-title"> Last Name </p>
          <input name="lname" type="text" class="profile-field" value="<?php echo $userlname;?>"/>
          <p class="lead profile-field-title"> Email </p>
          <input name="email" type="email" value="<?php echo $useremail;?>" class="profile-field"/>
          <br><br>
          <button type="submit" class="btn-primary profile-field" style="background:var(--s-primary)">Save</button>
        </form>
        </div>
        <?php
        }elseif(isset($editpassword)){
        ?>
        <!-- End: Listings View -->
        <!-- Profile -->
        <script>document.getElementById('title').innerHTML = 'Profile';</script>
        <div class="profile">
        <form action="/updatepassword" method="post">
          <p class="lead profile-field-title"> Password </p>
          <input name="password" type="text" class="profile-field" value="" placeholder="********" />
          <br><br>
          <button type="submit" class="btn-primary profile-field" style="background:var(--s-primary)">Change Password</button>
        </form>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div><!-- /.container -->
</main>
