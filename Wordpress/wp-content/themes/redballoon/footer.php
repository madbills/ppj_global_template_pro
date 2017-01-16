<? // Theme footer template ?>
</div>

<!-- Extra CTA or aside elements can go here -->


<?
// Values that get brought out from the settings page:

// Contact number:
$social_text = get_option( 'social-text' );
// If not set bring it from site 1:
$social_text = strlen($social_text) > 0 ? $social_text : esc_attr( get_blog_option( 1, 'social-text' ) );


// Contact number:
$email_signup_page = get_option( 'email-signup-page-id' );
// If not set bring it from site 1:
$email_signup_page = strlen($email_signup_page) > 0 ? $email_signup_page : esc_attr( get_blog_option( 1, 'email-signup-page-id' ) );

// Contact number:
$legal_text = get_option( 'legal-text' );
// If not set bring it from site 1:
$legal_text = strlen($legal_text) > 0 ? $legal_text : esc_attr( get_blog_option( 1, 'legal-text' ) );
$legal_text = stripslashes($legal_text); 
?>


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="social">
                <div class="group">
                    <p><?=$social_text?></p>
                    <?php 
                    if (get_current_blog_id() !== 1){
                        switch_to_blog( 1 );
                            if ( function_exists('cn_social_icon') ) echo cn_social_icon(); 
                        restore_current_blog();
                    } else {
                        if ( function_exists('cn_social_icon') ) echo cn_social_icon(); 
                    }
                    ?>
                </div>
                <div class="group">
                    <?// get the email signup page and ouput the url and the page title ?>
                    <a href="<?=get_permalink($email_signup_page)?>">
                        <p><?=get_the_title($email_signup_page)?></p>
                    </a>
                    <ul class="emailbutton">
                        <li><a class="icon icon-email icon-social" href="<?=home_url()?>/email-signup">Sign up for Email Offers</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row footer-content">
            <div class="col-md-10 footer-links">
                <?// output the footer menu ?>
                <?php wp_nav_menu( array(
                    'menu'=>'Footer Menu',
                    'depth' => 1,
                    'container' => false, // set true to wrap ul in a div with class "container".
                    'walker'=> new Custom_Nav_Walker() // Defined in functions.php
                ));?>
            </div>
            <div class="col-md-14 copyrights">
                <p>&copy; <?echo date("Y");?> <?=$legal_text?></p>
            </div>

        </div>

        <!-- .legal -->

    </div>
    <!-- .wrap -->
</footer>

<!-- this element is used to detect what the bootstrap screen size is with JS. It is not visible to users even if not used. -->
<div id="bs-size-check" style="opacity:0; pointer-event:none;position:absolute;bottom:0;right:0;visibility: hidden;">
    <div class="visible-xs"></div>
    <div class="visible-sm"></div>
    <div class="visible-md"></div>
    <div class="visible-lg"></div>
</div>

<?php // add scripts to inc/enqueue-resources.php. They will be output below here: ?>

<?php wp_footer(); ?>

<?php // Add analytics here ?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=INSERT_GTM_ID_HERE"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            '//www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'INSERT_GTM_ID_HERE');
</script>
<!-- End Google Tag Manager -->

</body>
</html>
