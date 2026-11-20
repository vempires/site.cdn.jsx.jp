<?php
// Demonstration copy: an intentional, removable page-response delay.
require_once __DIR__ . '/demo-delay.php';

function df_detect_base_path(): string {
    $app = realpath(__DIR__) ?: __DIR__;
    $doc = realpath((string)($_SERVER['DOCUMENT_ROOT'] ?? '')) ?: '';

    if ($doc !== '') {
        $appNorm = str_replace('\\', '/', $app);
        $docNorm = rtrim(str_replace('\\', '/', $doc), '/');
        if ($docNorm !== '' && str_starts_with($appNorm, $docNorm)) {
            $rel = substr($appNorm, strlen($docNorm));
            $rel = '/' . trim((string)$rel, '/');
            return $rel === '/' ? '' : $rel;
        }
    }

    // Fallback for unusual hosting layouts.
    $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    $folder = basename(__DIR__);
    $needle = '/' . $folder . '/';
    $pos = strpos($script, $needle);
    if ($pos !== false) {
        $base = substr($script, 0, $pos + strlen('/' . $folder));
        return rtrim($base, '/');
    }

    return '';
}

$BASE_PATH = df_detect_base_path();


require_once __DIR__ . '/content.php';
$DF_CONTENT=df_load_content();
$SPORTS=df_sports($DF_CONTENT);
$DF_ADS = require __DIR__ . '/ads.php';

function df_h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function df_g(string $key,string $default=''): string {
    global $DF_CONTENT;
    $v=$DF_CONTENT['general'][$key]??$default;
    return is_scalar($v)?(string)$v:$default;
}
function df_sport_icon(array $s,string $class='sport-icon'): string {
    $img=df_media_url((string)($s['icon_image_url']??''));
    if($img!=='')return '<img class="'.df_h($class).' sport-icon-image" src="'.df_h($img).'" alt="" loading="lazy">';
    return '<span class="'.df_h($class).'">'.($s['icon']??'🏆').'</span>';
}
function df_brand(): string {
    global $DF_CONTENT;
    $g=$DF_CONTENT['general'];
    $logo=df_media_url((string)($g['logo_url']??''));
    if($logo!=='')return '<img class="site-logo-img" src="'.df_h($logo).'" alt="'.df_h($g['logo_alt']??'SportsHub').'">';
    return df_h($g['site_name']??'SportsHub').'<span>'.df_h($g['brand_suffix']??'LIVE').'</span>';
}
function df_nav($active=''): void {
    global $DF_CONTENT,$BASE_PATH;
    $menuSports=df_menu_sports($DF_CONTENT);
    echo '<header class="header"><div class="wrap header-row"><a class="logo" href="'.df_h($BASE_PATH).'/">'.df_brand().'</a>';
    echo '<nav class="nav"><a class="'.($active===''?'active':'').'" href="'.df_h($BASE_PATH).'/" target="_self">All Sports</a>';
    foreach($menuSports as $s){
        echo '<a class="'.($active===$s['slug']?'active':'').'" href="'.df_h($BASE_PATH.'/'.$s['slug']).'/" target="_self">'.df_h($s['short']).'</a>';
    }
    echo '</nav><button class="menu-btn" type="button">Sports ☰</button></div></header>';
}
function df_utility(): void {
    echo '<div class="utility"><div class="wrap"><div class="live-pill"><span class="live-dot"></span> '.df_h(df_g('utility_left','LIVE SPORTS HUB')).'</div><div>'.df_h(df_g('utility_right','Fast • Mobile-first • All major sports')).'</div></div></div>';
}
function df_footer(): void {
    echo '<footer class="footer"><div class="wrap footer-grid"><div><strong>'.df_h(df_g('site_name','SportsHub')).' Sports Hub</strong><br>'.df_h(df_g('footer_intro')).'</div><div><strong>Disclosure</strong><br>'.df_h(df_g('footer_disclosure')).'</div></div></footer>';
}
function df_mobile_cta($sportName='',$slug=''): void {
    global $DF_CONTENT;
    if(empty($DF_CONTENT['general']['mobile_sticky_cta']))return;
    $sport=$slug&&$slug!=='all-sports'?df_find_sport($DF_CONTENT,$slug):null;
    $url=df_affiliate_url($DF_CONTENT,$slug?:'all-sports');
    echo '<div class="mobile-bar"><a class="btn btn-primary" href="'.df_h($url).'" target="_blank" rel="nofollow sponsored">'.df_h(df_watch_label($DF_CONTENT,$sport)).'</a></div>';
}
function df_head($title,$description,$canonicalPath,$accent='#52f2a8'): void {
    global $BASE_PATH,$DF_CONTENT;
    $scheme='http';
    if(!empty($_SERVER['HTTPS'])&&strtolower((string)$_SERVER['HTTPS'])!=='off'){
        $scheme='https';
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])){
        $xfp=strtolower(trim(explode(',',(string)$_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
        if($xfp==='https')$scheme='https';
    }elseif(!empty($_SERVER['HTTP_CF_VISITOR'])){
        $cf=json_decode((string)$_SERVER['HTTP_CF_VISITOR'],true);
        if(is_array($cf)&&(($cf['scheme']??'')==='https'))$scheme='https';
    }
    $host=preg_replace('/[^A-Za-z0-9.:-]/','',(string)($_SERVER['HTTP_HOST']??'localhost'));
    $canonical=$scheme.'://'.$host.$canonicalPath;
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">';
    echo '<meta name="theme-color" content="#050914"><meta name="robots" content="index,follow,max-image-preview:large">';
    echo '<title>'.df_h($title).'</title><meta name="description" content="'.df_h($description).'"><link rel="canonical" href="'.df_h($canonical).'">';
    $fav=df_media_url((string)($DF_CONTENT['general']['favicon_url']??''));
    if($fav!=='')echo '<link rel="icon" href="'.df_h($fav).'">';
    echo '<link rel="stylesheet" href="'.df_h($BASE_PATH).'/assets/style.css?v=simple-2.0"><style>:root{--accent:'.df_h($accent).'}</style>';
    echo '<script defer src="'.df_h($BASE_PATH).'/assets/app.js?v=simple-2.0"></script></head><body>';
}


function df_ad_responsive_top(): void {
    global $DF_ADS;
    $code=trim((string)($DF_ADS['banner_728x90']??''));
    if($code==='')return;
    $payload=base64_encode($code);
    echo '<div class="ad-unit simple-banner"><div class="ad-label">Advertisement</div><div class="ad-stage"></div>';
    echo '<script>(function(){var s=document.currentScript.previousElementSibling;';
    echo 'if(!window.matchMedia("(min-width:761px)").matches)return;';
    echo 'var f=document.createElement("iframe");f.width=728;f.height=90;f.className="df-ad-frame";f.title="Advertisement";f.scrolling="no";f.loading="lazy";';
    echo 'f.srcdoc="<!doctype html><html><head><style>html,body{margin:0;padding:0;overflow:hidden;background:transparent}</style><base target=\"_blank\"></head><body>"+new TextDecoder().decode(Uint8Array.from(atob("'.$payload.'"),function(c){return c.charCodeAt(0)}))+"</body></html>";s.appendChild(f);})();</script></div>';
}
function df_ad_native(): void {
    global $DF_ADS;
    $code=trim((string)($DF_ADS['native_banner']??''));
    if($code!=='')echo '<div class="ad-unit ad-native"><div class="ad-label">Advertisement</div><div class="native-shell">'.$code.'</div></div>';
}
function df_close(): void {
    global $DF_ADS;
    include __DIR__.'/histats.php';
    echo (string)($DF_ADS['popunder']??'');
    echo '</body></html>';
}

