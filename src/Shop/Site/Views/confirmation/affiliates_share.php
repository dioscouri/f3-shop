<?php $settings = \Affiliates\Models\Settings::fetch(); ?>
<?php $link = $SCHEME . '://' . $HOST . $BASE . '/affiliate/' . $this->auth->getIdentity()->id; ?>
<?php $encoded_link = urlencode($link); ?>

<h4>
    Share your order with your friends
    <small>and earn store credit for your next purchase</small>
</h4>

<ul class="list-group">
    <li class="list-group-item">
        <a class="btn btn-default" href="./affiliate/invite-friends/email" target="_blank"><i class="fa fa-envelope"></i> <span>Send an email</span></a>
    </li>
    
    <?php if ($settings->isSocialProviderEnabled('facebook') ) { ?>
    <?php $fb_app_id = $settings->{'social.providers.Facebook.keys.id'}; ?>
    <?php $fb_redirect_uri = $SCHEME . '://' . $HOST . $BASE . '/affiliate/share/thanks'; ?>
    <li class="list-group-item">
        <a class="btn btn-default" href="javascript:void(0);" onclick="window.open('https://www.facebook.com/dialog/share?app_id=<?php echo $fb_app_id; ?>&display=popup&href=<?php echo $encoded_link; ?>&redirect_uri=<?php echo $fb_redirect_uri; ?>', '_blank', 'width=520,height=570'); return false;"><i class="fa fa-facebook"></i> Share with your Facebook friends</a>
    </li>
    <?php } ?>
    
    <?php if ($settings->isSocialProviderEnabled('twitter') ) { ?>
    <?php $default_message = $settings->{'social.providers.Twitter.default_message'}; ?>
    <li class="list-group-item">
        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
        <a class="btn btn-default" target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo $encoded_link; if ($default_message) { echo '&text=' . $default_message; } ?>"><i class="fa fa-twitter"></i> Share with your Twitter followers</a>
    </li>
    <?php } ?>
    
    <?php if ($settings->isSocialProviderEnabled('linkedin') ) { ?>
    <?php $default_title = urlencode( trim($settings->{'social.providers.LinkedIn.default_title'}) ); ?>
    <?php $default_message = urlencode( trim($settings->{'social.providers.LinkedIn.default_message'}) ); ?>
    <li class="list-group-item">
        <a class="btn btn-default" href="javascript:void(0);" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $encoded_link; if ($default_title) { echo '&title=' . $default_title; } if ($default_message) { echo '&summary=' . $default_message; }?>', '_blank', 'width=520,height=570'); return false;"><i class="fa fa-linkedin"></i> Share with your LinkedIn connections</a>
    </li>
    <?php } ?>
    
    <?php if ($settings->isSocialProviderEnabled('google') ) { ?>
    <li class="list-group-item">
        <a class="btn btn-default" href="https://plus.google.com/share?url=<?php echo $encoded_link; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;"><i class="fa fa-google"></i> Share with your Google+ followers</a>
    </li>
    <?php } ?>                
</ul>

