<?php
$listing = getListing($id, $pdo);
$openhours = explode(',',str_replace('/', ' - ', $listing['open_hours']));
$mapped = 'true';
if ( is_null($listing['longitude']) || $listing['longitude']=='' ) {
  $mapped = 'false';
}
$listing['longitude'] = ( is_null($listing['longitude']) || $listing['longitude']=='' ) ? 5.283333 : $listing['longitude'];
$listing['latitude'] = ( is_null($listing['latitude']) || $listing['latitude']=='' ) ? 7.166667 : $listing['latitude'];

?>

<main>
  <div class="container" style="
    margin-top: 20px;
    position: relative;">
    <div style=""> 
      <h2><span><?php echo $listing['name']?></span></h2>
      <h4><span><?php echo getCategoryName(intval($listing['category']), $pdo); ?></span></h4>
      <hr class="divider-dpage">
    </div>
  </div>
  <div class="container marketing explore-content">
    <div class="row featurette" style="">
      <div class="col-md-7" style="">
        <div class="business-image-section">
         <img src="/uploads/images/<?php echo $listing['image_id']?>" width="95%" style=" /* min-height: 300px; */ /* object-fit: cover; */">
        </div>
        <div class="lead">
            <div class="business-detail-section">
              <h4>Business Description</h4>
              <p><?php echo $listing['description']?></p>
            </div>
            <div class="business-detail-section">
              <h4>Products/Services</h4>
              <p><?php echo $listing['products']?></p>
            </div>
            <div class="business-detail-section">
              <h4>Business Hours</h4>
              <p class="openhours">
                <?php
                if ($openhours[0]=='Not Provided') {
                echo "Not Provided";  
                }
                else{
                ?>
                  <span>Monday </span><?php echo $openhours[0]?><br>
                  <span>Tuesday </span><?php echo $openhours[1]?><br>
                  <span>Wednesday </span><?php echo $openhours[2]?><br>
                  <span>Thursday </span><?php echo $openhours[3]?><br>
                  <span>Friday </span><?php echo $openhours[4]?><br>
                  <span>Saturday </span><?php echo $openhours[5]?><br>
                  <span>Sunday </span><?php echo $openhours[6]?><br>
                <?php }?>
              </p>
            </div>
        </div>
      </div>
      <div class="col-md-5" style="">   
      <div class="" style="display: inline-block;width: 100%;background: #fbfbfb;padding: 10px;border-radius: 7px;margin-top: 15px; ">
        <p class="lead" style="margin-bottom: 5px;">
          <img src="/assets/images/pin.jpg" width="20px"><span>&nbsp;&nbsp;<?php echo $listing['full_address']?></span>
          <br>
          <img src="/assets/images/tel.jpg" width="20px"><span>&nbsp;&nbsp;<?php echo $listing['tel_1']?><?php if(!is_null($listing['tel_2'])){echo ', '.$listing['tel_2'];}?></span>
          <br>
          <a href="#"><span class="btn btn-primary" style="padding: 1px 3px;margin-top: 11px;margin-bottom: 0px;">Place a Call</span></a>
        </p>
      </div>
      <div id="map" style="width: 100%; height: 400px;    margin: 15px 0px; background: #e3e2e2a3;"></div>
      <script>
      	mapped = <?php echo $mapped; ?>;

        if (!mapped) {
          document.getElementById('map').innerHTML = "<span style='font-size: 1.4em;display: block; width: 100%; padding: 30px;'>Map data not provided for this business</span>";
          document.getElementById('map').style.height = '100px';
        } else {

        mapboxgl.accessToken = 'pk.eyJ1Ijoic2thaGxvIiwiYSI6ImNsengzbHk0azBwdzYya3NkNHhkZ3c4M2QifQ.eYQdSw4mca4z9dloA7cyiQ';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v9',
            projection: 'globe', // Display the map as a globe, since satellite-v9 defaults to Mercator
            zoom: 13,
            minZoom: 9,
            center: [<?php echo $listing['longitude'] . ', ' . $listing['latitude'];?>],
            maxBounds: [[4.363728503109807, 5.859574406653049],[6.077595691267174, 7.763067784120254]]
        });

        map.addControl(new mapboxgl.NavigationControl());
        map.scrollZoom.disable();

        //map.on('style.load', () => {
            //map.setFog({}); // Set the default atmosphere style
        //});

        let userInteracting = false;

        // Pause spinning on interaction
        map.on('mousedown', () => {
            userInteracting = true;
        });
        map.on('dragstart', () => {
            userInteracting = true;
        });
        
        marker = new mapboxgl.Marker();
        marker.setLngLat([<?php echo $listing['longitude'] . ', ' . $listing['latitude'];?>]);
        marker.addTo(map);
        // When animation is complete, start spinning if there is no ongoing interaction
        map.on('move', () => {
            marker.setLngLat(map.getCenter());
        });

        
        //marker.remove();

      }
      </script>
        
      </div>
    </div>
  </div><!-- /.container -->
</main>
