<?php
$ROOT=__DIR__;
require_once $ROOT.'/common.php';
$slug=df_slugify_sport((string)($_GET['slug']??''));
$current=df_find_sport($DF_CONTENT,$slug);
if(!$current||empty($current['enabled'])){http_response_code(404);exit('Sport not found');}

$title=trim((string)($current['seo_title']??''))?:($current['hero_title'].' | SportsHub');
$desc=trim((string)($current['seo_description']??''))?:$current['desc'];
df_head($title,$desc,$BASE_PATH.'/'.$slug.'/',$current['accent']??'#52f2a8');
df_utility();df_nav($slug);

$url=df_affiliate_url($DF_CONTENT,$slug);
$hero=df_media_url((string)($current['hero_image_url']??''));
$playerImg=df_media_url((string)($current['player_image_url']??''));
if($playerImg==='')$playerImg=$hero;

?>
<section class="compact-watch-section">
<div class="wrap">
  <div class="crumbs"><a href="<?=df_h($BASE_PATH)?>/" target="_self">All Sports</a> / <?=df_h($current['name'])?></div>

  <article class="compact-watch-card" style="--accent:<?=df_h($current['accent'])?>">
    <header class="compact-watch-header">
      <div class="kicker"><?=df_sport_icon($current,'kicker-icon')?> <span><?=df_h($current['kicker'])?></span></div>
      <h1><?=df_h($current['hero_title'])?></h1>
      <p><?=df_h($current['desc'])?></p>
    </header>

    <div class="compact-watch-grid">
      <?php if(!empty($current['player_enabled'])):?>
      <div class="compact-player" data-premium-player>
        <div class="compact-player-media">
          <?php if($playerImg!==''):?>
            <img src="<?=df_h($playerImg)?>" alt="<?=df_h($current['name'])?> viewing preview" loading="eager">
          <?php else:?>
            <div class="compact-player-fallback"><?=df_sport_icon($current,'compact-player-fallback-icon')?></div>
          <?php endif;?>
          <div class="compact-player-shade"></div>
          <div class="compact-player-badge"><span class="live-dot"></span><?=df_h($current['player_badge']??'LIVE VIEWING ACCESS')?></div>

          <button class="compact-play-trigger" type="button" data-player-open aria-label="Show viewing access options">
            <span>▶</span>
          </button>

          <div class="compact-player-gate premium-player-gate" data-player-gate aria-hidden="true" hidden>
            <div class="compact-gate-card">
              <div class="compact-gate-icon">▶</div>
              <h2><?=df_h($current['player_gate_heading']??'Access Required')?></h2>
              <p><?=df_h($current['player_gate_text']??'Continue with the viewing provider to access available live coverage.')?></p>
              <a class="btn btn-primary compact-gate-button"
                 href="<?=df_h($url)?>" target="_blank" rel="nofollow sponsored">
                <?=df_h($current['player_gate_button']??'Sign Up to Watch')?>
              </a>
              <small><?=df_h($current['player_notice']??'Opens the current provider in a new tab. Availability may vary by location and event.')?></small>
            </div>
          </div>
        </div>
      </div>
      <?php endif;?>

      <div class="desktop-side-panel" data-desktop-side-panel>
        <aside class="compact-quick-access desktop-quick-access" data-desktop-quick>
          <h3><?=df_h($current['quick_heading']??($current['short'].' Quick Access'))?></h3>
          <ul>
            <?php foreach(($current['quick_items']??[]) as $item):?>
              <li><span>✓</span><?=df_h($item)?></li>
            <?php endforeach;?>
          </ul>
        </aside>
      </div>

      <aside class="compact-quick-access mobile-quick-access">
        <h3><?=df_h($current['quick_heading']??($current['short'].' Quick Access'))?></h3>
        <ul>
          <?php foreach(($current['quick_items']??[]) as $item):?>
            <li><span>✓</span><?=df_h($item)?></li>
          <?php endforeach;?>
        </ul>
      </aside>
    </div>

    <div class="compact-watch-footer">
      <div class="compact-watch-footer-copy">
        <strong><?=df_h($current['player_title']??('Watch '.$current['name'].' Live'))?></strong>
        <span><?=df_h($current['player_text']??'Check current live viewing options from the destination provider.')?></span>
      </div>
      <a class="btn btn-primary compact-main-cta"
         href="<?=df_h($url)?>" target="_blank" rel="nofollow sponsored">
         <?=df_h(df_watch_label($DF_CONTENT,$current))?>
      </a>
    </div>
  </article>
</div>
</section>

<div class="wrap ad-breakout-mobile"><?php df_ad_responsive_top(); ?></div>
<div class="wrap"><?php df_ad_native(); ?></div>

<section class="section" id="guide">
<div class="wrap">
  <div class="section-title-row"><div><h2><?=df_h($current['guide_heading'])?></h2><p class="section-sub"><?=df_h($current['guide_subtitle'])?></p></div></div>
  <div class="info-grid">
    <div class="info-card"><b>1. Choose your sport</b><span>You are on the dedicated <?=df_h($current['name'])?> page.</span></div>
    <div class="info-card"><b>2. Check availability</b><span>Use the player access button or Watch Live button to continue to the current provider.</span></div>
    <div class="info-card"><b>3. Follow provider terms</b><span>Access, pricing and regional availability are determined by the destination provider.</span></div>
  </div>
</div>
</section>



<section class="section"><div class="wrap">
<div class="section-title-row"><div><h2>More Sports</h2><p class="section-sub">Jump to another category without returning to the home page.</p></div></div>
<div class="sports-grid">
<?php $shown=0;foreach($SPORTS as $s):if($s['slug']===$slug)continue;if($shown++>=8)break;?>
<a class="sport-card" style="--accent:<?=df_h($s['accent'])?>" href="<?=df_h($BASE_PATH.'/'.$s['slug'].'/')?>" target="_self">
  <?=df_sport_icon($s,'sport-icon')?><h3><?=df_h($s['name'])?></h3><p><?=df_h($s['desc'])?></p><span class="sport-link">Open <?=df_h($s['short'])?> →</span>
</a>
<?php endforeach;?>
</div></div></section>

<?php df_footer();df_close();?>
