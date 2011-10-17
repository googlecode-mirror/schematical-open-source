<!-- Fancy and Simple Version -->
<!--
<div class="item">
    <a href="#"><?php FBContestApplication::RenderContestField('Photo'); ?></a>
    <div class="caption">
     <p class="cursive"><?php FBContestApplication::RenderContestField('FirstName'); ?> </p>
   
    </div>
</div>
-->

<div class="grid12 first">
<div class="note nopadding">
<div class="photo">
<?php FBContestApplication::RenderContestField('Photo'); ?>
</div>
<div class="entrycontent">
<h4><?php FBContestApplication::RenderContestField('FirstName'); ?> <?php FBContestApplication::RenderContestField('LastName'); ?><span class="details"><?php FBContestApplication::RenderContestField('City'); ?> <?php FBContestApplication::RenderContestField('State'); ?></span></h4>
<h5 class="right"><?php FBContestApplication::RenderContestField('Credate', 'M j, Y'); ?></h5>
</div>
</div>
</div>

