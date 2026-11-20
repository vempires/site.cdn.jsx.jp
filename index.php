<?php
require_once __DIR__.'/common.php';
df_head('Live Sports Hub — Football, Basketball, Baseball, Hockey & More | SportsHub','Browse live sports viewing options across major sports categories on SportsHub.',$BASE_PATH.'/');
df_utility();df_nav('');
?>
<section class="hero"><div class="wrap hero-grid">
<div>
<div class="kicker"><span class="live-dot"></span><?=df_h(df_g('home_kicker'))?></div>
<h1><?=df_h(df_g('home_title_line1'))?><br><?=df_h(df_g('home_title_line2'))?></h1>
<p><?=df_h(df_g('home_intro'))?></p>
<div class="hero-actions">
<a class="btn btn-primary" href="<?=df_h(df_affiliate_url($DF_CONTENT,'all-sports'))?>" target="_blank" rel="nofollow sponsored"><?=df_h(df_watch_label($DF_CONTENT))?></a>
<a class="btn btn-ghost" href="#sports"><?=df_h(df_g('home_secondary_button','Browse Sports ↓'))?></a>
</div></div>
<aside class="feature-card"><div class="kicker"><?=df_h(df_g('feature_kicker'))?></div><h3 style="font-size:29px;margin:0 0 8px"><?=df_h(df_g('feature_title'))?></h3>
<p class="section-sub"><?=df_h(df_g('feature_text'))?></p>
<div class="stat-row"><div class="stat"><b><?=count($SPORTS)?></b><span>SPORT CATEGORIES</span></div><div class="stat"><b><?=df_h(df_g('feature_stat_value'))?></b><span><?=df_h(df_g('feature_stat_label'))?></span></div><div class="stat"><b>3</b><span>AD FORMATS</span></div></div>
</aside></div></section>

<div class="wrap"><?php df_ad_responsive_top(); ?></div>

<section class="section" id="sports"><div class="wrap">
<div class="section-title-row"><div><h2><?=df_h(df_g('channels_heading'))?></h2><p class="section-sub"><?=df_h(df_g('channels_subtitle'))?></p></div></div>
<div class="search"><input id="sportSearch" type="search" placeholder="<?=df_h(df_g('search_placeholder'))?>" autocomplete="off"></div>
<div class="sports-grid">
<?php foreach($SPORTS as $s):?>
<a class="sport-card" data-search="<?=df_h(strtolower($s['name'].' '.$s['short'].' '.$s['slug']))?>" style="--accent:<?=df_h($s['accent'])?>" href="<?=df_h($BASE_PATH.'/'.$s['slug'].'/')?>" target="_self">
<?=df_sport_icon($s,'sport-icon')?><h3><?=df_h($s['name'])?></h3><p><?=df_h($s['desc'])?></p><span class="sport-link">Open <?=df_h($s['short'])?> →</span>
</a>
<?php endforeach;?>
</div></div></section>

<div class="wrap"><?php df_ad_native(); ?></div>
<section class="section"><div class="wrap"><div class="watch-panel"><div><h3><?=df_h(df_g('ready_heading'))?></h3><p><?=df_h(df_g('ready_text'))?></p></div>
<a class="btn btn-primary" href="<?=df_h(df_affiliate_url($DF_CONTENT,'all-sports'))?>" target="_blank" rel="nofollow sponsored"><?=df_h(df_watch_label($DF_CONTENT))?></a>
</div></div></section>

<?php df_footer();df_mobile_cta('','all-sports');df_close();?>
